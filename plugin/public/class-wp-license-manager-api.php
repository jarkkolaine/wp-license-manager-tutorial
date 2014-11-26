<?php

/**
 * The API handler for handling API requests from themes and plugins using
 * the license manager.
 *
 * @package    Wp_License_Manager
 * @subpackage Wp_License_Manager/public
 * @author     Jarkko Laine <jarkko@jarkkolaine.com>
 */
class Wp_License_Manager_API {

    /**
     * The handler function that receives the API calls and passes them on to the
     * proper handlers.
     *
     * @param $action   string  The name of the action
     * @param $params   array   Request parameters
     */
    public function handle_request( $action, $params ) {
        switch ( $action ) {
            case 'info':
                $response = $this->verify_license_and_execute( array( $this, 'product_info' ), $params );
                break;

            case 'get':
                $response = $this->verify_license_and_execute( array( $this, 'get_product' ), $params );
                break;

            default:
                $response = $this->error_response( 'No such API action' );
                break;
        }

        $this->send_response( $response );
    }

    /**
     * Returns a list of variables used by the API
     *
     * @return  array    An array of query variable names.
     */
    public function get_api_vars() {
        return array( 'l',  'e', 'p' );
    }

    //
    // API HANDLER FUNCTIONS
    //

    /**
     * Checks the parameters and verifies the license, then forwards the request to the
     * actual API request handlers.
     *
     * @param $action_function  callable    The function (or array with class and function) to call
     * @param $params           array       The WordPress request parameters.
     * @return array            API response.
     */
    private function verify_license_and_execute( $action_function, $params ) {
        if ( ! isset( $params['p'] ) || ! isset( $params['e'] ) || ! isset( $params['l'] ) ) {
            return $this->error_response( 'Invalid request' );
        }

        $product_id = $params['p'];
        $email = $params['e'];
        $license_key = $params['l'];

        // Find product
        $posts = get_posts(
            array (
                'name' => $product_id,
                'post_type' => 'wplm_product',
                'post_status' => 'publish',
                'numberposts' => 1
            )
        );

        if ( ! isset( $posts[0] ) ) {
            return $this->error_response( 'Product not found.' );
        }

        // Verify license
        if ( ! $this->verify_license( $posts[0]->ID, $email, $license_key ) ) {
            return $this->error_response( 'Invalid license or license expired.' );
        }

        // Call the handler function
        return call_user_func_array( $action_function, array( $posts[0], $product_id, $email, $license_key ) );
    }

    /**
     * The handler for the "info" request. Checks the user's license information and
     * returns information about the product (latest version, name, update url).
     *
     * @param   $product        WP_Post   The product object
     * @param   $product_id     string    The product id (slug)
     * @param   $email          string    The email address associated with the license
     * @param   $license_key    string  The license key associated with the license
     *
     * @return  array           The API response as an array.
     */
    private function product_info( $product, $product_id, $email, $license_key ) {
        // Collect all the metadata we have and return it to the caller
        $meta = get_post_meta( $product->ID, 'wp_license_manager_product_meta' );

        $version = isset( $meta['version'] ) ? $meta['version'] : '';
        $tested = isset( $meta['tested'] ) ? $meta['tested'] : '';
        $last_updated = isset( $meta['updated'] ) ? $meta['updated'] : '';
        $author = isset( $meta['author'] ) ? $meta['author'] : '';
        $banner_low = isset( $meta['banner_low'] ) ? $meta['banner_low'] : '';
        $banner_high = isset( $meta['banner_high'] ) ? $meta['banner_high'] : '';

        return array(
            'name' => $product->post_title,
            'description' => $product->post_content,
            'version' => $version,
            'tested' => $tested,
            'author' => $author,
            'last_updated' => $last_updated,
            'banner_low' => $banner_low,
            'banner_high' => $banner_high,
            "package_url" => home_url( '/api/license-manager/v1/get?p=' . $product_id . '&e=' . $email . '&l=' . urlencode( $license_key ) ),
            "description_url" => get_permalink( $product->ID ) . '#v=' . $version
        );
    }

    /**
     * The handler for the "get" request. Redirects to the file download.
     *
     * @param   $product    WP_Post     The product object
     */
    private function get_product( $product, $product_id, $email, $license_key ) {
        // Get the AWS data from post meta fields
        $meta = get_post_meta( $product->ID, 'wp_license_manager_product_meta' );
        $bucket = isset ( $meta['file_bucket'] ) ? $meta['file_bucket'] : '';
        $file_name = isset ( $meta['file_bucket'] ) ? $meta['file_bucket'] : '';

        if ( $bucket == '' || $file_name == '' ) {
            // No file set, return error
            return $this->error_response( 'No download defined for product.' );
        }

        // Use the AWS API to set up the download
        // This API method is called directly by WordPress so we need to adhere to its
        // requirements and skip the JSON. WordPress expects to receive a ZIP file...

        $s3_url = Wp_License_Manager_S3::get_s3_url( $bucket, $file_name );
        wp_redirect( $s3_url, 302 );
    }

    //
    // HELPER FUNCTIONS
    //

    /**
     * Looks up a license that matches the given parameters.
     *
     * @param $product_id   int     The numeric ID of the product.
     * @param $email        string  The email address attached to the license.
     * @param $license_key  string  The license key
     * @return mixed                The license data if found. Otherwise false.
     */
    private function find_license( $product_id, $email, $license_key ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'product_licenses';

        $licenses = $wpdb->get_results(
            $wpdb->prepare( "SELECT * FROM $table_name WHERE product_id = %d AND email = '%s' AND license_key = '%s'",
                $product_id, $email, $license_key ), ARRAY_A);

        if ( count( $licenses ) > 0 ) {
            return $licenses[0];
        }

        return false;
    }

    /**
     * Checks whether a license with the given parameters exists and is still valid.
     *
     * @param $product_id   int     The numeric ID of the product.
     * @param $email        string  The email address attached to the license.
     * @param $license_key  string  The license key.
     * @return bool                 true if license is valid. Otherwise false.
     */
    private function verify_license( $product_id, $email, $license_key ) {
        $license = $this->find_license( $product_id, $email, $license_key );
        if ( ! $license ) {
            return false;
        }

        $valid_until = strtotime( $license['valid_until'] );
        if ( $license['valid_until'] != '0000-00-00 00:00:00' && time() > $valid_until ) {
            return false;
        }

        return true;
    }

    /**
     * Generates and returns a simple error response. Used to make sure every error
     * message uses same formatting.
     *
     * @param $msg      string  The message to be included in the error response.
     * @return array    The error response as an array that can be passed to send_response.
     */
    private function error_response( $msg ) {
        return array( 'error' => $msg );
    }

    /**
     * Prints out the JSON response for an API call.
     *
     * @param $response array   The response as associative array.
     */
    private function send_response( $response ) {
        echo json_encode( $response ) . '\n';
    }

}
<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) .'lib/s3_fallback/S3.php';

/**
 * A fallback implementation of the Amazon S3 functionality for users running
 * an old version of PHP (< 5.3).
 *
 * @package    Wp_License_Manager
 * @subpackage Wp_License_Manager/public
 * @author     Jarkko Laine <jarkko@jarkkolaine.com>
 */
class Wp_License_Manager_S3_Fallback {

    /**
     * Returns a signed Amazon S3 download URL.
     *
     * @param $key          string  AWS key
     * @param $secret       string  AWS secret
     * @param $bucket       string  Bucket name
     * @param $file_name    string  File name (URI)
     * @return string       The signed download URL
     */
    public static function get_s3_url( $key, $secret, $bucket, $file_name ) {
        $s3 = new S3( $key, $secret );

        // Duration 600 seconds (= 10 minutes)
        return $s3->getAuthenticatedURL( $bucket, $file_name, 600 );
    }

} 
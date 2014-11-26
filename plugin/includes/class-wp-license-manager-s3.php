<?php
/**
 * A wrapper for our Amazon S3 API actions.
 *
 * @package    Wp_License_Manager
 * @subpackage Wp_License_Manager/includes
 * @author     Jarkko Laine <jarkko@jarkkolaine.com>
 */
class Wp_License_Manager_S3 {

    /**
     * Returns a signed Amazon S3 download URL.
     *
     * @param $bucket       string  Bucket name
     * @param $file_name    string  File name (URI)
     * @return string       The signed download URL
     */
    public static function get_s3_url( $bucket, $file_name ) {
        $options = get_option( 'wp-license-manager-settings' );

        $s3_client = Aws\S3\S3Client::factory(
            array(
                'key'    => $options['aws_key'],
                'secret' => $options['aws_secret']
            )
        );

        return $s3_client->getObjectUrl( $bucket, $file_name, '+10 minutes' );
    }

} 

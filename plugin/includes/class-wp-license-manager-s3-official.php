<?php
use Aws\S3\S3Client;
require_once plugin_dir_path( dirname( __FILE__ ) ) .'lib/aws/aws-autoloader.php';

/**
 * The main implementation of the Amazon S3 functionality.
 * For users running a recent version (>= 5.3) of PHP. Uses the
 * official Amazon AWS API.
 *
 * @package    Wp_License_Manager
 * @subpackage Wp_License_Manager/public
 * @author     Jarkko Laine <jarkko@jarkkolaine.com>
 */
class Wp_License_Manager_S3_Official {

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
        $s3_client = S3Client::factory(
            array(
                'key'    => $key,
                'secret' => $secret
            )
        );

        return $s3_client->getObjectUrl( $bucket, $file_name, '+10 minutes' );
    }

} 
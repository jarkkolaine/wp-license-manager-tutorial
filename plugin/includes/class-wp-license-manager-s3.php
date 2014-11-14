<?php
// Namespaces are only available in PHP 5.3+ and WordPress requires 5.2.6 :(
// To make it possible for most users to use the official API, we made this little
// workaround...
if ( version_compare( PHP_VERSION, '5.3.0') >= 0 ) {
    require_once plugin_dir_path( dirname( __FILE__ ) ) .'includes/class-wp-license-manager-s3-official.php';
} else {
    require_once plugin_dir_path( dirname( __FILE__ ) ) .'includes/class-wp-license-manager-s3-fallback.php';
}

/**
 * A wrapper for our Amazon S3 API actions.
 *
 * Depending on the PHP version available, we use either the official AWS
 * API or a custom one.
 *
 * @package    Wp_License_Manager
 * @subpackage Wp_License_Manager/includes
 * @author     Jarkko Laine <jarkko@jarkkolaine.com>
 */
class Wp_License_Manager_S3 {

    /**
     * Checks if we can use the official AWS API.
     *
     * @return bool True if PHP version is high enough to use the official AWS API. Otherwise false.
     */
    public static function use_official_api() {
        return ( version_compare( PHP_VERSION, '5.3.0' ) >= 0 );
    }

    /**
     * Returns a signed Amazon S3 download URL.
     *
     * @param $bucket       string  Bucket name
     * @param $file_name    string  File name (URI)
     * @return string       The signed download URL
     */
    public static function get_s3_url( $bucket, $file_name ) {
        $s3_url = '';
        $options = get_option( 'wp-license-manager-settings' );

        if ( Wp_License_Manager_S3::use_official_api() ) {
            write_log( 'Using official API ');
            $s3_url = Wp_License_Manager_S3_Official::get_s3_url( $options['aws_key'], $options['aws_secret'], $bucket, $file_name );
        } else {
            write_log( 'Using fallback API ');
            $s3_url = Wp_License_Manager_S3_Fallback::get_s3_url( $options['aws_key'], $options['aws_secret'], $bucket, $file_name );
        }

        return $s3_url;
    }

} 
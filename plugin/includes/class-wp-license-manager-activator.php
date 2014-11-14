<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    Wp_License_Manager
 * @subpackage Wp_License_Manager/includes
 * @author     Jarkko Laine <jarkko@jarkkolaine.com>
 */
class Wp_License_Manager_Activator {

    /**
     * The database version number. Update this every time you make a change to the database structure.
     *
     * @access   protected
     * @var      string    $db_version   The database version number
     */
    protected static $db_version = 1;

    /**
     * Code that is run at plugin activation.
     *
     * Creates or updates the database structure required by the plugin and does other
     * data initialization
     */
    public static function activate() {
        // Get some version numbers
        $current_db_version = get_option( 'wp-license-manager-db-version' );
        if ( ! $current_db_version ) {
            $current_db_version = 0;
        }
        $current_db_version = intval( $current_db_version );

        // Update database if db version has increased
        if ( intval( $current_db_version ) < Wp_License_Manager_Activator::$db_version ) {
            if ( Wp_License_Manager_Activator::create_or_upgrade_db() ) {
                update_option( 'wp-license-manager-db-version', Wp_License_Manager_Activator::$db_version );
            }
        }
    }

    /**
     * Creates the database tables required for the plugin if
     * they don't exist. Otherwise updates them as needed.
     *
     * @return bool true if update was successful.
     */
    private static function create_or_upgrade_db() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'product_licenses';

        $charset_collate = '';
        if ( ! empty( $wpdb->charset ) ) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }
        if ( ! empty( $wpdb->collate ) ) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }

        $sql = "CREATE TABLE " . $table_name . "("
		     . "id mediumint(9) NOT NULL AUTO_INCREMENT, "
             . "product_id mediumint(9) DEFAULT 0 NOT NULL,"
             . "license_key varchar(48) NOT NULL, "
             . "email varchar(48) NOT NULL, "
		     . "valid_until datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, "
             . "created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, "
             . "updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, "
		     . "UNIQUE KEY id (id)"
	         . ")" . $charset_collate. ";";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        return true;
    }

}

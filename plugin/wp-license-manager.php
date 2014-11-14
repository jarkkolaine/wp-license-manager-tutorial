<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://fourbean.com/wp-license-manager/
 * @package           Wp_License_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       WP License Manager
 * Plugin URI:        http://fourbean.com/wp-license-manager/
 * Description:       Turn your WordPress site into a license manager for WordPress plugins, themes, and other downloadable products.
 * Version:           0.5.2
 * Author:            Fourbean
 * Author URI:        http://fourbean.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       wp-license-manager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Needed for listing licenses
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-license-manager-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-license-manager-deactivator.php';

/** This action is documented in includes/class-wp-license-manager-activator.php */
register_activation_hook( __FILE__, array( 'Wp_License_Manager_Activator', 'activate' ) );

/** This action is documented in includes/class-wp-license-manager-deactivator.php */
register_deactivation_hook( __FILE__, array( 'Wp_License_Manager_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-license-manager.php';


if ( !function_exists( 'write_log' ) ) {
    function write_log( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function run_wp_license_manager() {

	$plugin = new Wp_License_Manager();
	$plugin->run();

}
run_wp_license_manager();

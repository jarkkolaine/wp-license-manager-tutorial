<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Wp_License_Manager
 * @subpackage Wp_License_Manager/public
 * @author     Jarkko Laine <jarkko@jarkkolaine.com>
 */
class Wp_License_Manager_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    /**
     * @var     License_Manager_API     The API handler
     */
    private $api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @var      string    $plugin_name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

        $this->api = new Wp_License_Manager_API();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-license-manager-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-license-manager-public.js', array( 'jquery' ), $this->version, false );
	}

    /**
     * Register the new "products" post type to use for products that
     * are available for purchase through the license manager.
     */
    public function add_products_post_type() {
        register_post_type( 'wplm_product',
            array(
                'labels' => array(
                    'name' => __( 'Products', $this->plugin_name ),
                    'singular_name' => __( 'Product', $this->plugin_name ),
                    'menu_name' => __( 'Products', $this->plugin_name ),
                    'name_admin_bar' => __( 'Products', $this->plugin_name ),
                    'add_new' => __( 'Add New', $this->plugin_name ),
                    'add_new_item' => __( 'Add New Product', $this->plugin_name ),
                    'edit_item' => __( 'Edit Product', $this->plugin_name ),
                    'new_item' => __( 'New Product', $this->plugin_name ),
                    'view_item' => __( 'View Product', $this->plugin_name ),
                    'search_item' => __( 'Search Products', $this->plugin_name ),
                    'not_found' => __( 'No products found', $this->plugin_name ),
                    'not_found_in_trash' => __( 'No products found in trash', $this->plugin_name ),
                    'all_items' => __( 'All Products', $this->plugin_name ),
                ),
                'public' => true,
                'has_archive' => true,
                'supports' => array( 'title', 'editor', 'author', 'revisions', 'thumbnail' ),
                'rewrite' => array( 'slug' => 'products' ),
                'menu_icon' => 'dashicons-products',
            )
        );
    }

    //
    // Handlers for the API
    //

    /**
     * Defines the query variables used by the API.
     *
     * @param $vars     array   Existing query variables from WordPress.
     * @return array    The $vars array appended with our new variables
     */
    public function add_api_query_vars( $vars ) {
        // The parameter used for checking the action used
        $vars []= '__wp_license_api';

        // Additional parameters defined by the API requests
        $api_vars = $this->api->get_api_vars();

        return array_merge( $vars, $api_vars );
    }

    /**
     * The permalink structure definition for API calls.
     */
    public function add_api_endpoint_rules() {
        add_rewrite_rule( 'api/license-manager/v1/(info|get)/?',
            'index.php?__wp_license_api=$matches[1]', 'top' );

        // If this was the first time, flush rules
        if ( get_option( 'wp-license-manager-rewrite-rules-version' ) != '1.1' ) {
            flush_rewrite_rules();
            update_option( 'wp-license-manager-rewrite-rules-version', '1.1' );
        }
    }

    /**
     * A sniffer function that looks for API calls and passes them to our API handler.
     */
    public function sniff_api_requests() {
        global $wp;
        if ( isset( $wp->query_vars['__wp_license_api'] ) ) {
            $action = $wp->query_vars['__wp_license_api'];
            $this->api->handle_request( $action, $wp->query_vars );

            exit;
        }
    }

}

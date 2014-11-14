<?php

/**
 * A Wp_List_Table implementation for listing licenses.
 *
 * @package     Wp_License_Manager
 * @subpackage  Wp_License_Manager/admin
 *
 * @author      Jarkko Laine <jarkko@jarkkolaine.com>
 */
class Licenses_List_Table extends Wp_License_Manager_List_Table {

    /**
     * The plugin's text domain.
     *
     * @access  private
     * @var     string  The plugin's text domain. Used for localization.
     */
    private $text_domain;

    /**
     * Initializes the WP_List_Table implementation.
     *
     * @param $text_domain  string  The text domain used for localizing the plugin.
     */
    public function __construct( $text_domain ) {
        parent::__construct();

        $this->text_domain = $text_domain;
    }

    /**
     * Defines the database columns shown in the table and a
     * header for each column. The order of the columns in the
     * table define the order in which they are rendered in the list table.
     *
     * @return array    The database columns and their headers for the table.
     */
    public function get_columns() {
        return array(
            'license_key' => __( 'License Key', $this->text_domain ),
            'email'       => __( 'Email', $this->text_domain ),
            'product_id'  => __( 'Product', $this->text_domain ),
            'valid_until' => __( 'Valid Until', $this->text_domain ),
            'created_at'  => __( 'Created', $this->text_domain )
        );
    }

    /**
     * Returns the names of columns that should be hidden from the list table.
     *
     * @return array    The database columns that should not be shown in the table.
     */
    public function get_hidden_columns() {
        return array( 'created_at' );
    }

    /**
     * Returns the columns that can be used for sorting the list table data.
     *
     * @return array    The database columns that can be used for sorting the table.
     */
    public function get_sortable_columns() {
        return array(
            'email' => array( 'email', false ),
            'valid_until' => array( 'valid_until', false )
        );
    }

    /**
     * Custom renderer for the product_id field.
     *
     * @param $item     array   The database row being printed out.
     * @return string   The text or HTML that should be shown for the column.
     */
    function column_product_id( $item ) {
        $product_id = $item['product_id'];

        $product = get_post( $product_id );
        if ( $product ) {
            $url = 'post.php?post=' . $product_id . '&action=edit';
            return '<a href="' . admin_url( $url ) . '">' . $product->post_title . '</a>';
        }

        return __( 'No product', $this->text_domain );
    }

    /**
     * Custom renderer for the valid_until field.
     *
     * @param $item     array   The database row being printed out.
     * @return string   The text or HTML that should be shown for the column.
     */
    function column_valid_until( $item ) {
        $valid_until = $item['valid_until'];

        if ($valid_until == '0000-00-00 00:00:00') {
            return __( 'Forever', $this->text_domain );
        }

        return $valid_until;
    }

    /**
     * Custom renderer for the license_key field.
     *
     * @param $item     array   The database row being printed out.
     * @return string   The text or HTML that should be shown for the column.
     */
    function column_license_key( $item ) {
        // The license key field shows the key and a link for deleting it

        $delete_url = admin_url('admin.php?page=wp-licenses&action=delete&license=' . $item['id'] );
        $delete_url_with_nonce = wp_nonce_url( $delete_url, 'wp-license-manager-delete-license', 'wp-license-manager-delete-license-nonce' );

        $delete_link = '<a href="' . $delete_url_with_nonce . '" class="delete-license">' . __('Delete', $this->text_domain ) . '</a>';

        $actions = array(
            'delete'    => $delete_link,
        );

        $title = '<code>' . $item['license_key'] . '</code>';

        return sprintf('%1$s %2$s', $title, $this->row_actions($actions) );
    }

    /**
     * Default rendering for table columns.
     *
     * @param $item         array   The database row being printed out.
     * @param $column_name  string  The column currently processed.
     * @return string       The text or HTML that should be shown for the column.
     */
    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'email':
            case 'created_at':
                return $item[$column_name];

            default:
                break;
        }

        return '';
    }

    /**
     * Populates the class fields for displaying the list of licenses.
     */
    public function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'product_licenses';

        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable );

        // Pagination
        $licenses_per_page = 20;
        $total_items = $wpdb->get_var( "SELECT COUNT(id) FROM $table_name" );

        $offset = 0;
        if ( isset( $_REQUEST['paged'] ) ) {
            $page = max( 0, intval( $_REQUEST['paged'] ) - 1 );
            $offset = $page * $licenses_per_page;
        }

        $this->set_pagination_args(
            array(
                'total_items' => $total_items,
                'per_page' => $licenses_per_page,
                'total_pages' => ceil( $total_items / $licenses_per_page )
            )
        );

        // Sorting
        $order_by = 'email'; // Default sort key
        if ( isset( $_REQUEST['orderby'] ) ) {
            // If the requested sort key is a valid column, use it for sorting
            if ( in_array( $_REQUEST['orderby'], array_keys( $this->get_sortable_columns() ) ) ) {
                $order_by = $_REQUEST['orderby'];
            }
        }

        $order = 'asc'; // Default sort order
        if ( isset( $_REQUEST['order'] ) ) {
            if ( in_array( $_REQUEST['order'], array( 'asc', 'desc' ) ) ) {
                $order = $_REQUEST['order'];
            }
        }

        // Do the SQL query and populate items
        $this->items = $wpdb->get_results(
            $wpdb->prepare( "SELECT * FROM $table_name ORDER BY $order_by $order LIMIT %d OFFSET %d", $licenses_per_page, $offset ),
            ARRAY_A );
    }

} 
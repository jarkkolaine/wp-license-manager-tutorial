<?php
/**
 * The view for the admin page used for adding a new license.
 *
 * @package    Wp_License_Manager
 * @subpackage Wp_License_Manager/admin/partials
 */
?>
<div class="wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"></div>

    <h2><?php _e( 'Add New License', $this->plugin_name ); ?></h2>
    <p>
        <?php
            $instructions = 'Use this form to manually add a product license. '
                . 'After completing the process, make sure to pass the license key to the customer.';

            _e( $instructions, $this->plugin_name );
        ?>
    </p>

    <form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
        <?php wp_nonce_field( 'wp-license-manager-add-license', 'wp-license-manager-add-license-nonce' ); ?>
        <input type="hidden" name="action" value="license_manager_add_license">

        <table class="form-table">
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="email">
                        <?php _e( 'Email', $this->plugin_name ); ?>
                        <span class="description"><?php _e( '(required)', $this->plugin_name ); ?></span>
                    </label>
                </th>
                <td>
                    <input name="email" type="text" id="email" aria-required="true">
                </td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="email">
                        <?php _e( 'Product', $this->plugin_name ); ?>
                        <span class="description"><?php _e( '(required)', $this->plugin_name ); ?></span>
                    </label>
                </th>
                <td>
                    <select name="product" id="product" aria-required="true">
                        <?php foreach ( $products as $product ) : ?>
                            <option value="<?php echo $product->ID; ?>"><?php echo $product->post_title; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="valid_until">
                        <?php _e( 'Valid until', $this->plugin_name ); ?>
                    </label>
                </th>
                <td>
                    <input name="valid_until" type="text" id="valid_until" aria-required="false" />
                    <p class="description">
                        <?php _e( '(Format: YYYY-MM-DD HH:MM:SS / Leave empty for infinite)', $this->plugin_name );?>
                    </p>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" name="add-license" class="button button-primary"
                   value="<?php _e( 'Add License', $this->plugin_name ); ?>" >
        </p>
    </form>

</div>

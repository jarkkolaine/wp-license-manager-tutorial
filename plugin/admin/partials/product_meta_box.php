<?php
/**
 * The view for the plugin's product meta box. The product meta box is used for
 * entering additional product information (version, file bucket, file name).
 *
 * @package    Wp_License_Manager
 * @subpackage Wp_License_Manager/admin/partials
 */
?>
<p>
    <label for="wp_license_manager_product_version">
        <?php _e( 'Version:', $this->plugin_name ); ?>
    </label>
    <input type="text" id="wp_license_manager_product_version"
           name="wp_license_manager_product_version"
           value="<?php echo esc_attr( $product_meta['version'] ); ?>"
           size="25" >
</p>
<p>
    <label for="wp_license_manager_product_tested">
        <?php _e( 'Tested with WordPress version:', $this->plugin_name ); ?>
    </label>
    <input type="text" id="wp_license_manager_product_tested"
           name="wp_license_manager_product_tested"
           value="<?php echo esc_attr( $product_meta['tested'] ); ?>"
           size="25" >
</p>
<p>
    <label for="wp_license_manager_product_requires">
        <?php _e( 'Requires WordPress version:', $this->plugin_name ); ?>
    </label>
    <input type="text" id="wp_license_manager_product_requires"
           name="wp_license_manager_product_requires"
           value="<?php echo esc_attr( $product_meta['requires'] ); ?>"
           size="25" >
</p>
<p>
    <label for="wp_license_manager_product_updated">
        <?php _e( 'Last updated:', $this->plugin_name ); ?>
    </label>
    <input type="text" id="wp_license_manager_product_updated"
           name="wp_license_manager_product_updated"
           value="<?php echo esc_attr( $product_meta['updated'] ); ?>"
           size="25" >
</p>

<p>
    <label for="wp_license_manager_product_banner_low">
        <?php _e( 'Banner low:', $this->plugin_name ); ?>
    </label>
    <input type="text" id="wp_license_manager_product_banner_low"
           name="wp_license_manager_product_banner_low"
           value="<?php echo esc_attr( $product_meta['banner_low'] ); ?>"
           size="25" >
</p>

<p>
    <label for="wp_license_manager_product_banner_high">
        <?php _e( 'Banner high:', $this->plugin_name ); ?>
    </label>
    <input type="text" id="wp_license_manager_product_banner_high"
           name="wp_license_manager_product_banner_high"
           value="<?php echo esc_attr( $product_meta['banner_high'] ); ?>"
           size="25" >
</p>

<h3>Download</h3>

<p>
    <label for="wp_license_manager_product_bucket">
        <?php _e( 'Amazon S3 Bucket:', $this->plugin_name ); ?>
    </label>
    <input type="text" id="wp_license_manager_product_bucket"
           name="wp_license_manager_product_bucket"
           value="<?php echo esc_attr( $product_meta['file_bucket'] ); ?>"
           size="25" />
</p>
<p>
    <label for="wp_license_manager_product_file_name">
        <?php _e( 'Amazon S3 File Name:', $this->plugin_name ); ?>
    </label>
    <input type="text" id="wp_license_manager_product_file_name"
           name="wp_license_manager_product_file_name"
           value="<?php echo esc_attr( $product_meta['file_name'] ); ?>"
           size="25" />
</p>

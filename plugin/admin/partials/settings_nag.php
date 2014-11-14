<?php
/**
 * The view for the plugin's settings nag..
 *
 * @package    Wp_License_Manager
 * @subpackage Wp_License_Manager/admin/partials
 */
?>

<div class="update-nag">
    <p>
        <?php _e( 'Before you can use WP License Manager, you need to configure some settings.', $this->plugin_name ); ?>
    </p>
    <p>
        <a href="<?php echo admin_url( 'options-general.php?page=wp-license-settings' ); ?>">
            <?php _e( 'Complete the setup now.', $this->plugin_name ); ?>
        </a>
    </p>
</div>

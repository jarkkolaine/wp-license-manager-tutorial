<?php
/**
 * The view for the plugin's options page.
 *
 * @package    Wp_License_Manager
 * @subpackage Wp_License_Manager/admin/partials
 */
?>

<div class="wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"></div>

    <h2>
        <?php _e( 'WP License Manager Settings', $this->plugin_name ); ?>
    </h2>


    <form action='options.php' method='post'>
        <?php
        settings_fields( $settings_group_id );
        do_settings_sections( $settings_group_id );
        submit_button();
        ?>
    </form>
</div>

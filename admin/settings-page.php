<?php

function gpc_detection_settings_page()
{
    add_options_page(
        'GPC Detection Settings',
        'GPC Detection',
        'manage_options',
        'gpc-detection',
        'gpc_detection_render_settings_page'
    );
}

function gpc_detection_register_settings()
{
    register_setting('gpc_detection_settings_group', 'gpc_detection_settings');
    add_settings_section('gpc_detection_main_section', 'Main Settings', null, 'gpc-detection');

    add_settings_field('block_google_analytics', 'Block Google Analytics', 'gpc_detection_render_block_google_analytics', 'gpc-detection', 'gpc_detection_main_section');
    add_settings_field('block_facebook_pixel', 'Block Facebook Pixel', 'gpc_detection_render_block_facebook_pixel', 'gpc-detection', 'gpc_detection_main_section');
}

function gpc_detection_render_block_google_analytics()
{
    $options = get_option('gpc_detection_settings');
?>
    <input type="checkbox" name="gpc_detection_settings[block_google_analytics]" <?php checked($options['block_google_analytics'], 1); ?> value="1">
<?php
}

function gpc_detection_render_block_facebook_pixel()
{
    $options = get_option('gpc_detection_settings');
?>
    <input type="checkbox" name="gpc_detection_settings[block_facebook_pixel]" <?php checked($options['block_facebook_pixel'], 1); ?> value="1">
<?php
}

function gpc_detection_render_settings_page()
{
?>
    <div class="wrap">
        <h1>GPC Detection Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('gpc_detection_settings_group');
            do_settings_sections('gpc-detection');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

add_action('admin_menu', 'gpc_detection_settings_page');
add_action('admin_init', 'gpc_detection_register_settings');

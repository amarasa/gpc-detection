<?php
/*
Plugin Name: GPC Detection
Description: A plugin to detect and handle Global Privacy Control (GPC) signals.
Version: 1.0.3
Plugin URI: https://github.com/amarasa/gpc-detection
Author: Angelo Marasa
*/
/* -------------------------------------------------------------------------------------- */
// Updated
require 'puc/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/amarasa/gpc-detection',
    __FILE__,
    'gpc-detection-plugin'
);

//Set the branch that contains the stable release.
//$myUpdateChecker->setBranch('stable-branch-name');

//Optional: If you're using a private repository, specify the access token like this:
// $myUpdateChecker->setAuthentication('your-token-here');

/* -------------------------------------------------------------------------------------- */


function enqueue_gpc_script()
{
    wp_enqueue_script('gpc-detection', plugin_dir_url(__FILE__) . 'js/gpc-detection.js', array(), null, true);

    $options = get_option('gpc_detection_settings', []);
    wp_localize_script('gpc-detection', 'gpcDetectionSettings', $options);

    // Debugging: Output localized settings in head
    echo '<script>console.log("GPC Detection Settings: ", ' . json_encode($options) . ');</script>';
}
add_action('wp_enqueue_scripts', 'enqueue_gpc_script');

function enqueue_gpc_styles()
{
    wp_enqueue_style('gpc-toggle-switch', plugin_dir_url(__FILE__) . 'css/toggle-switch.css');
}
add_action('admin_enqueue_scripts', 'enqueue_gpc_styles');

// Add settings page
add_action('admin_menu', 'gpc_detection_settings_page');
add_action('admin_init', 'gpc_detection_register_settings');

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
    $block_google_analytics = isset($options['block_google_analytics']) ? $options['block_google_analytics'] : 0;
?>
    <label class="switch">
        <input type="checkbox" class="toggle-checkbox" name="gpc_detection_settings[block_google_analytics]" <?php checked($block_google_analytics, 1); ?> value="1">
        <span class="slider"></span>
    </label>
<?php
}

function gpc_detection_render_block_facebook_pixel()
{
    $options = get_option('gpc_detection_settings');
    $block_facebook_pixel = isset($options['block_facebook_pixel']) ? $options['block_facebook_pixel'] : 0;
?>
    <label class="switch">
        <input type="checkbox" class="toggle-checkbox" name="gpc_detection_settings[block_facebook_pixel]" <?php checked($block_facebook_pixel, 1); ?> value="1">
        <span class="slider"></span>
    </label>
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

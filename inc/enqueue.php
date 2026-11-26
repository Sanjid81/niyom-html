<?php
/**
 * Asset loading.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

/**
 * Version a dist file by its modified time, so browsers pick up new builds.
 */
function niyom_asset_version($file)
{
    $path = NIYOM_DIR . $file;
    return file_exists($path) ? (string) filemtime($path) : wp_get_theme()->get('Version');
}

/**
 * Front-end assets (webpack output in /dist).
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('niyom-app', NIYOM_URI . '/dist/app.css', [], niyom_asset_version('/dist/app.css'));
    wp_enqueue_script('niyom-app', NIYOM_URI . '/dist/app.js', [], niyom_asset_version('/dist/app.js'), true);
});

/**
 * Block editor assets.
 */
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style('niyom-editor', NIYOM_URI . '/admin-editor.css', [], niyom_asset_version('/admin-editor.css'));
});

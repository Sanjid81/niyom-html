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
    // Figma fonts: Inter 400/500 (headings, body) + JetBrains Mono 400/500 (buttons, nav, labels).
    wp_enqueue_style(
        'niyom-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        [],
        null
    );
    wp_enqueue_style('niyom-app', NIYOM_URI . '/dist/app.css', ['niyom-fonts'], niyom_asset_version('/dist/app.css'));
    wp_enqueue_script('niyom-app', NIYOM_URI . '/dist/app.js', [], niyom_asset_version('/dist/app.js'), true);
});

/**
 * Block editor assets.
 */
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style('niyom-editor', NIYOM_URI . '/admin-editor.css', [], niyom_asset_version('/admin-editor.css'));
});

<?php
/**
 * Boot Carbon Fields from Composer (vendor/).
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

add_action('after_setup_theme', function () {
    $autoload = NIYOM_DIR . '/vendor/autoload.php';

    if (!file_exists($autoload)) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p><strong>Niyom:</strong> '
                . esc_html__('Carbon Fields is missing. Run "composer install" in the theme folder.', 'niyom')
                . '</p></div>';
        });
        return;
    }

    require_once $autoload;
    \Carbon_Fields\Carbon_Fields::boot();
});

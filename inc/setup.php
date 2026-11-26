<?php
/**
 * Theme setup: supports, menus, helpers.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

/**
 * Theme supports and menu locations.
 */
function niyom_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('editor-styles');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);

    register_nav_menus([
        'main_menu'   => __('Main Menu', 'niyom'),
        'footer_menu' => __('Footer Menu', 'niyom'),
    ]);
}
add_action('after_setup_theme', 'niyom_setup');

/**
 * Read a Carbon Fields theme option safely (returns '' if Carbon Fields is not loaded).
 *
 * @param string $key Field name.
 * @return mixed
 */
function niyom_option($key)
{
    return function_exists('carbon_get_theme_option') ? carbon_get_theme_option($key) : '';
}

/**
 * Add a submenu toggle button after top-level menu items that have children.
 * Link click opens the page; button click opens the submenu (mobile).
 */
add_filter('walker_nav_menu_start_el', function ($item_output, $item, $depth, $args) {
    if ($depth === 0 && in_array('menu-item-has-children', (array) $item->classes, true)) {
        $item_output .= '<button type="button" class="nav-submenu-toggle" aria-expanded="false" aria-label="' . esc_attr__('Toggle submenu', 'niyom') . '">'
            . '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M13 6L8 11L3 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
            . '</button>';
    }
    return $item_output;
}, 10, 4);

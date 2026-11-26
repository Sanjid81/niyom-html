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
 * Format a Carbon Fields rich_text value for output.
 *
 * Keeps everything the editor can produce (paragraphs, line breaks, links,
 * images, lists, shortcodes) and adds srcset + lazy loading to images.
 *
 * @param string $html Raw field value.
 * @return string Safe HTML, or '' when the field holds no real content.
 */
function niyom_rich_text($html)
{
    if (!niyom_has_content($html)) {
        return '';
    }

    $html = wp_kses_post($html);
    $html = wpautop($html);
    $html = shortcode_unautop($html);
    $html = do_shortcode($html);

    return wp_filter_content_tags($html);
}

/**
 * True when a field value has visible content (text, an image or an embed).
 * Treats editor leftovers like "<p>&nbsp;</p>" as empty.
 *
 * @param mixed $value Field value.
 * @return bool
 */
function niyom_has_content($value)
{
    if (!is_string($value)) {
        return !empty($value);
    }

    if (preg_match('/<(img|iframe|video|audio|svg)\b/i', $value)) {
        return true;
    }

    $text = html_entity_decode(wp_strip_all_tags($value, true), ENT_QUOTES, 'UTF-8');
    $text = str_replace("\xC2\xA0", ' ', $text); // non-breaking spaces.

    return trim($text) !== '';
}

/**
 * Format a short textarea (titles): Enter makes a new line, simple inline tags are kept.
 *
 * @param string $text Raw field value.
 * @return string
 */
function niyom_inline_text($text)
{
    if (!niyom_has_content($text)) {
        return '';
    }

    $allowed = [
        'br'     => [],
        'em'     => [],
        'i'      => [],
        'strong' => [],
        'b'      => [],
        'span'   => ['class' => true],
        'a'      => ['href' => true, 'target' => true, 'rel' => true],
    ];

    return nl2br(wp_kses(trim($text), $allowed), false);
}

/**
 * Print an SVG icon from assets/icons. Its fill follows the text colour (currentColor).
 *
 * @param string $name  File name without .svg.
 * @param string $class Extra class for the <svg>.
 * @return string
 */
function niyom_icon($name, $class = '')
{
    $file = NIYOM_DIR . '/assets/icons/' . sanitize_file_name($name) . '.svg';
    if (!file_exists($file)) {
        return '';
    }

    $svg = file_get_contents($file);
    $svg = preg_replace('/\sfill="(?!none)[^"]*"/i', ' fill="currentColor"', $svg);
    $svg = preg_replace('/\s(style|preserveAspectRatio|overflow)="[^"]*"/i', '', $svg);
    $svg = preg_replace('/\sid="[^"]*"/i', '', $svg);

    return str_replace(
        '<svg ',
        '<svg class="icon ' . esc_attr($class) . '" aria-hidden="true" focusable="false" ',
        $svg
    );
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

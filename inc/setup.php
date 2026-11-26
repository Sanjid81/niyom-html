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

    // Header + footer menus (Appearance › Menus). Other header/footer content: Theme Options.
    register_nav_menus([
        'main_menu'     => __('Header Menu (desktop)', 'niyom'),
        'mobile_menu'   => __('Header Menu (mobile, falls back to desktop)', 'niyom'),
        'footer_menu_1' => __('Footer Column 1 (Services)', 'niyom'),
        'footer_menu_2' => __('Footer Column 2 (Quick Links)', 'niyom'),
        'footer_menu_3' => __('Footer Column 3 (Legal)', 'niyom'),
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
 * Print an SVG icon from assets/icons (subfolders allowed, e.g. "social/x").
 *
 * @param string $name    File name without .svg.
 * @param string $class   Extra class for the <svg>.
 * @param bool   $recolor true: fill/stroke follow the text colour (currentColor).
 *                        false: keep the colours from the file.
 * @return string
 */
function niyom_icon($name, $class = '', $recolor = true)
{
    $name = implode('/', array_map('sanitize_file_name', explode('/', $name)));
    $file = NIYOM_DIR . '/assets/icons/' . $name . '.svg';
    if (!file_exists($file)) {
        return '';
    }

    $svg = file_get_contents($file);

    if ($recolor) {
        $svg = preg_replace('/\s(fill|stroke)="(?!none)[^"]*"/i', ' $1="currentColor"', $svg);
        $svg = preg_replace('/\s(fill|stroke)-opacity="[^"]*"/i', '', $svg);
    }

    $svg = preg_replace('/\s(style|preserveAspectRatio|overflow)="[^"]*"/i', '', $svg);

    // Drop layer ids (avoids duplicate ids) unless the SVG references one, e.g. clip-path="url(#…)".
    if (strpos($svg, 'url(#') === false) {
        $svg = preg_replace('/\sid="[^"]*"/i', '', $svg);
    }

    return str_replace(
        '<svg ',
        '<svg class="icon ' . esc_attr($class) . '" aria-hidden="true" focusable="false" ',
        $svg
    );
}

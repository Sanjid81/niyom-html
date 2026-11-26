<?php
/**
 * Template helpers for header.php and footer.php: logo, menus, links.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

/**
 * Logo: image + text from Theme Options › Header (or › Footer › Logo).
 * Each part is hidden when its field is empty.
 *
 * @param string $context "header" or "footer".
 * @return string
 */
function niyom_logo($context = 'header')
{
    $context = $context === 'footer' ? 'footer' : 'header';
    $image   = niyom_option('niyom_' . $context . '_logo');
    $text    = trim((string) niyom_option('niyom_' . $context . '_logo_text'));

    if (!$image && $text === '') {
        return '';
    }

    $html  = '<a class="brand brand--' . esc_attr($context) . ($text === '' ? ' brand--image-only' : '') . '" href="' . esc_url(home_url('/')) . '" rel="home"';
    $html .= $text === '' ? ' aria-label="' . esc_attr(get_bloginfo('name')) . '">' : '>';

    if ($image) {
        $html .= '<img class="brand-mark" src="' . esc_url($image) . '" alt="">';
    }
    if ($text !== '') {
        $html .= '<span class="brand-text">' . esc_html($text) . '</span>';
    }

    return $html . '</a>';
}

/**
 * Local file path for a theme or uploads URL ('' if the URL is elsewhere).
 *
 * @param string $url File URL.
 * @return string
 */
function niyom_url_to_path($url)
{
    $url     = strtok((string) $url, '?#');
    $uploads = wp_get_upload_dir();
    $map     = [
        NIYOM_URI           => NIYOM_DIR,
        $uploads['baseurl'] => $uploads['basedir'],
    ];

    foreach ($map as $base_url => $base_dir) {
        // Match http and https versions of the same site.
        $base_url = preg_replace('#^https?:#', '', $base_url);
        $relative = preg_replace('#^https?:#', '', $url);
        if (strpos($relative, $base_url . '/') === 0) {
            $path = wp_normalize_path($base_dir . substr($relative, strlen($base_url)));
            $real = realpath($path);
            // Stay inside the allowed folder.
            if ($real && strpos(wp_normalize_path($real), wp_normalize_path(realpath($base_dir))) === 0) {
                return $real;
            }
        }
    }
    return '';
}

/**
 * Remove scripts, event handlers and external references from SVG markup.
 *
 * @param string $svg SVG markup.
 * @return string
 */
function niyom_clean_svg($svg)
{
    $svg = preg_replace('/<\?xml.*?\?>|<!DOCTYPE[^>]*>|<!--.*?-->/is', '', $svg);
    $svg = preg_replace('#<(script|foreignObject|iframe|object|embed|use)\b.*?(</\1>|/>)#is', '', $svg);
    $svg = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $svg);
    $svg = preg_replace('/\s(xlink:)?href\s*=\s*("|\')\s*(javascript|data):[^"\']*\2/i', '', $svg);

    return trim($svg);
}

/**
 * Print an icon from an uploaded/theme file: SVGs are inlined (they follow
 * the text colour), other images use <img>.
 *
 * @param string $url   Icon URL.
 * @param string $class Extra class.
 * @return string
 */
function niyom_media_icon($url, $class = '')
{
    if (!$url) {
        return '';
    }

    $path = strtolower(pathinfo(strtok($url, '?#'), PATHINFO_EXTENSION)) === 'svg' ? niyom_url_to_path($url) : '';

    if ($path) {
        $svg = niyom_clean_svg((string) file_get_contents($path));
        if (strpos($svg, '<svg') !== false) {
            $svg = preg_replace('/\s(fill|stroke)="(?!none)[^"]*"/i', ' $1="currentColor"', $svg);
            $svg = preg_replace('/\s(fill|stroke)-opacity="[^"]*"|\s(style|preserveAspectRatio|overflow)="[^"]*"/i', '', $svg);
            if (strpos($svg, 'url(#') === false) {
                $svg = preg_replace('/\sid="[^"]*"/i', '', $svg);
            }
            return preg_replace('/<svg\b/i', '<svg class="icon ' . esc_attr($class) . '" aria-hidden="true" focusable="false"', $svg, 1);
        }
    }

    return '<img class="icon ' . esc_attr($class) . '" src="' . esc_url($url) . '" alt="" loading="lazy">';
}

/**
 * Allow admins to upload SVG files (for logos and social icons).
 * Output is cleaned by niyom_clean_svg() before it is inlined.
 */
add_filter('upload_mimes', function ($mimes) {
    if (current_user_can('manage_options')) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
});

add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename) {
    if (current_user_can('manage_options') && strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'svg') {
        $data['ext']  = 'svg';
        $data['type'] = 'image/svg+xml';
    }
    return $data;
}, 10, 3);

/**
 * Link attributes for a URL field + "open in new tab" checkbox.
 *
 * @param string $url     Link.
 * @param bool   $new_tab Open in new tab.
 * @return string
 */
function niyom_link_attrs($url, $new_tab = false)
{
    $attrs = 'href="' . esc_url($url) . '"';
    if ($new_tab) {
        $attrs .= ' target="_blank" rel="noopener noreferrer"';
    }
    return $attrs;
}

/**
 * Submenu toggle button: chevron on desktop, plus/minus on mobile (Figma).
 *
 * @return string
 */
function niyom_submenu_toggle()
{
    return '<button type="button" class="nav-submenu-toggle" aria-expanded="false" aria-label="' . esc_attr__('Show submenu', 'niyom') . '">'
        . niyom_icon('chevron-down', 'nav-submenu-toggle-chevron')
        . '<span class="nav-submenu-toggle-pm" aria-hidden="true"></span>'
        . '</button>';
}

/**
 * Add the toggle after top-level header menu items that have children.
 */
add_filter('walker_nav_menu_start_el', function ($item_output, $item, $depth, $args) {
    $location = $args->theme_location ?? '';
    if (
        $depth === 0
        && in_array($location, ['main_menu', 'mobile_menu'], true)
        && in_array('menu-item-has-children', (array) $item->classes, true)
    ) {
        $item_output .= niyom_submenu_toggle();
    }
    return $item_output;
}, 10, 4);

/**
 * Default menu items (Figma), shown until a menu is assigned in Appearance › Menus.
 *
 * @param string $location Menu location.
 * @return array
 */
function niyom_default_menu_items($location)
{
    $services = [
        ['title' => 'Computational Biosciences', 'url' => '/computational-biosciences/'],
        ['title' => 'Research Support', 'url' => '/research-support/'],
        ['title' => 'Scientific Engagement', 'url' => '/scientific-engagement/'],
    ];

    // Footer columns (Figma footer).
    if ($location === 'footer_menu_1') {
        return $services;
    }
    if ($location === 'footer_menu_2') {
        return [
            ['title' => 'About NIYOM', 'url' => '/about/'],
            ['title' => 'Expertise', 'url' => '/expertise/'],
            ['title' => 'Events & Partnerships', 'url' => '/events-and-partnershiphubs/'],
            ['title' => 'Insights', 'url' => '/insights/'],
            ['title' => 'Contact Us', 'url' => '/#contact'],
        ];
    }
    if ($location === 'footer_menu_3') {
        return [
            ['title' => 'Privacy Policy', 'url' => '/privacy-policy/'],
            ['title' => 'Cookie Policy', 'url' => '/cookie-policy/'],
            ['title' => 'Terms & Conditions', 'url' => '/terms-and-conditions/'],
        ];
    }

    $items = [
        ['title' => 'About NIYOM', 'url' => '/about/'],
        ['title' => 'Services', 'url' => '/services-overview/', 'children' => $services],
        ['title' => 'Expertise', 'url' => '/expertise/'],
        ['title' => 'Events & Partnerships', 'url' => '/events-and-partnershiphubs/'],
        ['title' => 'Insights', 'url' => '/insights/'],
    ];

    if ($location === 'mobile_menu') {
        array_unshift($items, ['title' => 'Home', 'url' => '/']);
        $items[] = ['title' => 'Contact Us', 'url' => '/#contact'];
    }

    return $items;
}

/**
 * wp_nav_menu() fallback: prints the default items with WordPress menu classes.
 *
 * @param array $args wp_nav_menu() arguments.
 * @return string|void
 */
function niyom_menu_fallback($args)
{
    $args    = (array) $args;
    $current = untrailingslashit(wp_parse_url(home_url(add_query_arg([])), PHP_URL_PATH) ?: '/');
    $current = $current === '' ? '/' : $current;

    $max_depth = (int) ($args['depth'] ?? 0);

    $render = function ($items, $depth) use (&$render, $current, $max_depth) {
        $html = '';
        foreach ($items as $item) {
            $path    = untrailingslashit($item['url']) ?: '/';
            $classes      = ['menu-item'];
            $has_children = !empty($item['children']) && ($max_depth === 0 || $depth + 1 < $max_depth);
            if ($has_children) {
                $classes[] = 'menu-item-has-children';
            }
            if ($path === $current) {
                $classes[] = 'current-menu-item';
            }

            $html .= '<li class="' . esc_attr(implode(' ', $classes)) . '">';
            $html .= '<a href="' . esc_url(home_url($item['url'])) . '"' . ($path === $current ? ' aria-current="page"' : '') . '>' . esc_html($item['title']) . '</a>';

            if ($has_children) {
                if ($depth === 0) {
                    $html .= niyom_submenu_toggle();
                }
                $html .= '<ul class="sub-menu">' . $render($item['children'], $depth + 1) . '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    };

    $id   = !empty($args['menu_id']) ? ' id="' . esc_attr($args['menu_id']) . '"' : '';
    $html = '<ul' . $id . ' class="' . esc_attr($args['menu_class'] ?? 'menu') . '">'
        . $render(niyom_default_menu_items($args['theme_location'] ?? ''), 0)
        . '</ul>';

    if (!isset($args['echo']) || $args['echo']) {
        echo $html; // Escaped above.
        return;
    }
    return $html;
}

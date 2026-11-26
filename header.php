<?php
/**
 * Site header (Figma: Navigation — desktop 1920 / mobile 360, Mobile expanded menu).
 * Content: Theme Options › Brand + Header; links: Appearance › Menus.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$btn_text    = trim((string) niyom_option('niyom_header_btn_text'));
$btn_link    = trim((string) niyom_option('niyom_header_btn_link'));
$btn_new_tab = (bool) niyom_option('niyom_header_btn_new_tab');
$has_button  = $btn_text !== '' && $btn_link !== '';

// Mobile uses its own menu if assigned, otherwise the main menu.
$mobile_location = has_nav_menu('mobile_menu') || !has_nav_menu('main_menu') ? 'mobile_menu' : 'main_menu';
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <a class="skip-link" href="#main"><?php esc_html_e('Skip to content', 'niyom'); ?></a>

    <header class="site-header" data-site-header>
        <div class="site-header-inner">

            <?php echo niyom_logo('header'); ?>

            <nav class="site-header-nav" aria-label="<?php esc_attr_e('Main menu', 'niyom'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'main_menu',
                    'container'      => false,
                    'menu_class'     => 'site-header-menu',
                    'depth'          => 2,
                    'fallback_cb'    => 'niyom_menu_fallback',
                ]);
                ?>
            </nav>

            <div class="site-header-actions">
                <?php if ($has_button) : ?>
                    <a class="btn btn--primary btn--m site-header-button" <?php echo niyom_link_attrs($btn_link, $btn_new_tab); ?>>
                        <span><?php echo esc_html($btn_text); ?></span>
                        <?php echo niyom_icon('arrow-right', 'btn-arrow btn-arrow--lg'); ?>
                    </a>
                <?php endif; ?>

                <button class="site-header-toggle" type="button"
                    aria-controls="site-mobile-nav"
                    aria-expanded="false"
                    aria-label="<?php esc_attr_e('Open menu', 'niyom'); ?>"
                    data-label-open="<?php esc_attr_e('Open menu', 'niyom'); ?>"
                    data-label-close="<?php esc_attr_e('Close menu', 'niyom'); ?>">
                    <?php echo niyom_icon('menu', 'site-header-toggle-open', false); ?>
                    <?php echo niyom_icon('close', 'site-header-toggle-close', false); ?>
                </button>
            </div>
        </div>

        <!-- Mobile expanded menu -->
        <div class="site-header-panel" id="site-mobile-nav">
            <nav class="site-header-panel-nav" aria-label="<?php esc_attr_e('Mobile menu', 'niyom'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => $mobile_location,
                    'container'      => false,
                    'menu_class'     => 'site-header-mobile-menu',
                    'depth'          => 2,
                    'fallback_cb'    => 'niyom_menu_fallback',
                ]);
                ?>
            </nav>

            <?php if ($has_button) : ?>
                <div class="site-header-panel-cta">
                    <a class="btn btn--primary site-header-panel-button" <?php echo niyom_link_attrs($btn_link, $btn_new_tab); ?>>
                        <span><?php echo esc_html($btn_text); ?></span>
                        <?php echo niyom_icon('arrow-right-sm', 'btn-arrow'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <main id="main" class="site-main">

<?php
/**
 * Site header.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$logo_id     = niyom_option('site_logo');
$button_text = niyom_option('header_button_text');
$button_link = niyom_option('header_button_link');
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

    <header class="site-header">
        <div class="container site-header__inner">

            <a class="site-header__logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                <?php if ($logo_id) : ?>
                    <?php echo wp_get_attachment_image($logo_id, 'medium', false, ['alt' => get_bloginfo('name')]); ?>
                <?php else : ?>
                    <span class="site-header__name"><?php bloginfo('name'); ?></span>
                <?php endif; ?>
            </a>

            <nav class="site-header__nav" id="site-nav" aria-label="<?php esc_attr_e('Main menu', 'niyom'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'main_menu',
                    'container'      => false,
                    'menu_class'     => 'site-header__menu',
                    'fallback_cb'    => false,
                ]);
                ?>

                <?php if ($button_text && $button_link) : ?>
                    <a class="btn btn--primary" href="<?php echo esc_url($button_link); ?>">
                        <?php echo esc_html($button_text); ?>
                    </a>
                <?php endif; ?>
            </nav>

            <button class="site-header__toggle" type="button" aria-controls="site-nav" aria-expanded="false"
                aria-label="<?php esc_attr_e('Toggle menu', 'niyom'); ?>">
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>
    </header>

    <main id="main" class="site-main">

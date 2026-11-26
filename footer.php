<?php
/**
 * Site footer.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$footer_logo = niyom_option('footer_logo');
$tagline     = niyom_option('footer_tagline');
$address     = niyom_option('footer_address');
$email       = niyom_option('footer_email');
$phone       = niyom_option('footer_phone');
$social      = niyom_option('footer_social') ?: [];
$copyright   = niyom_option('footer_copyright') ?: '© {year} ' . get_bloginfo('name') . '. All rights reserved.';
$copyright   = str_replace('{year}', gmdate('Y'), $copyright);
?>
    </main><!-- #main -->

    <footer class="site-footer">
        <div class="container site-footer__top">

            <div class="site-footer__brand">
                <a class="site-footer__logo" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php if ($footer_logo) : ?>
                        <?php echo wp_get_attachment_image($footer_logo, 'medium', false, ['alt' => get_bloginfo('name')]); ?>
                    <?php else : ?>
                        <span class="site-footer__name"><?php bloginfo('name'); ?></span>
                    <?php endif; ?>
                </a>
                <?php if ($tagline) : ?>
                    <p class="site-footer__tagline"><?php echo esc_html($tagline); ?></p>
                <?php endif; ?>
            </div>

            <nav class="site-footer__nav" aria-label="<?php esc_attr_e('Footer menu', 'niyom'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer_menu',
                    'container'      => false,
                    'menu_class'     => 'site-footer__menu',
                    'fallback_cb'    => false,
                    'depth'          => 1,
                ]);
                ?>
            </nav>

            <div class="site-footer__contact">
                <?php if ($address) : ?>
                    <p><?php echo esc_html($address); ?></p>
                <?php endif; ?>
                <?php if ($email) : ?>
                    <p><a href="mailto:<?php echo esc_attr(antispambot($email)); ?>"><?php echo esc_html(antispambot($email)); ?></a></p>
                <?php endif; ?>
                <?php if ($phone) : ?>
                    <p><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></p>
                <?php endif; ?>
                <?php if ($social) : ?>
                    <ul class="site-footer__social">
                        <?php foreach ($social as $link) : ?>
                            <?php if (!empty($link['url'])) : ?>
                                <li><a href="<?php echo esc_url($link['url']); ?>" target="_blank" rel="noopener"><?php echo esc_html($link['label'] ?: $link['url']); ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

        </div>

        <div class="site-footer__bottom">
            <div class="container">
                <p class="site-footer__copyright"><?php echo esc_html($copyright); ?></p>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>

</html>

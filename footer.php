<?php
/**
 * Site footer (Figma: footer — desktop 1920 / mobile 360).
 * Content: Theme Options › Brand + Footer. Empty fields are hidden.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

// Link columns: links from Appearance › Menus (Footer Column 1/2/3), titles from Theme Options.
// A column whose menu has no links is hidden.
$columns = [];
foreach ([1, 2, 3] as $n) {
    $menu = wp_nav_menu([
        'theme_location' => 'footer_menu_' . $n,
        'container'      => false,
        'menu_class'     => 'site-footer-links',
        'menu_id'        => 'footer-links-' . $n,
        'depth'          => 1,
        'fallback_cb'    => 'niyom_menu_fallback',
        'echo'           => false,
    ]);

    if ($menu && strpos($menu, '<li') !== false) {
        $columns[] = [
            'id'    => 'footer-links-' . $n,
            'title' => trim((string) niyom_option('niyom_footer_col' . $n . '_title')),
            'menu'  => $menu,
        ];
    }
}

$copyright = trim((string) niyom_option('niyom_footer_copyright'));
$copyright = str_replace('{year}', wp_date('Y'), $copyright);

$credit_text  = trim((string) niyom_option('niyom_footer_credit_text'));
$credit_label = trim((string) niyom_option('niyom_footer_credit_label'));
$credit_url   = trim((string) niyom_option('niyom_footer_credit_url'));
$has_credit   = $credit_text !== '' || $credit_label !== '';

// Social links: need a URL and an icon or a name.
$social = array_filter((array) niyom_option('niyom_footer_social_links'), function ($item) {
    return trim($item['url'] ?? '') !== '' && (!empty($item['icon']) || trim($item['label'] ?? '') !== '');
});

$logo       = niyom_logo('footer');
$has_top    = $logo || $columns;
$has_bottom = $copyright !== '' || $has_credit || $social;
?>
    </main><!-- #main -->

    <?php if ($has_top || $has_bottom) : ?>
        <footer class="site-footer">
            <div class="site-footer-container">

                <?php if ($has_top) : ?>
                    <div class="site-footer-top">
                        <?php echo $logo; // Escaped in niyom_logo(). ?>

                        <?php if ($columns) : ?>
                            <div class="site-footer-columns<?php echo count($columns) === 3 ? ' site-footer-columns--figma' : ''; ?>"
                                style="--footer-cols: <?php echo (int) count($columns); ?>;">
                                <?php foreach ($columns as $i => $column) :
                                    $list_id = $column['id'];
                                    $open    = $i === 0;
                                    ?>
                                    <div class="site-footer-col<?php echo $open ? ' is-open' : ''; ?><?php echo $column['title'] === '' ? ' site-footer-col--untitled' : ''; ?>" data-footer-col>
                                        <?php if ($column['title'] !== '') : ?>
                                            <h2 class="site-footer-col-title">
                                                <button type="button" class="site-footer-col-toggle"
                                                    aria-controls="<?php echo esc_attr($list_id); ?>"
                                                    aria-expanded="<?php echo $open ? 'true' : 'false'; ?>">
                                                    <span><?php echo esc_html($column['title']); ?></span>
                                                    <span class="site-footer-pm" aria-hidden="true"></span>
                                                </button>
                                            </h2>
                                        <?php endif; ?>

                                        <?php echo $column['menu']; // Built by wp_nav_menu(). ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($has_bottom) : ?>
                    <div class="site-footer-bottom">
                        <?php if ($copyright !== '' || $has_credit) : ?>
                            <div class="site-footer-legal">
                                <?php if ($copyright !== '') : ?>
                                    <p class="site-footer-copyright"><?php echo esc_html($copyright); ?></p>
                                <?php endif; ?>

                                <?php if ($copyright !== '' && $has_credit) : ?>
                                    <span class="site-footer-divider" aria-hidden="true"></span>
                                <?php endif; ?>

                                <?php if ($has_credit) : ?>
                                    <p class="site-footer-credit">
                                        <?php echo esc_html($credit_text); ?>
                                        <?php if ($credit_label !== '') : ?>
                                            <?php if ($credit_url !== '') : ?>
                                                <a href="<?php echo esc_url($credit_url); ?>" target="_blank" rel="noopener"><?php echo esc_html($credit_label); ?></a>
                                            <?php else : ?>
                                                <span><?php echo esc_html($credit_label); ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($social) : ?>
                            <ul class="site-footer-social">
                                <?php foreach ($social as $item) :
                                    $label = trim($item['label'] ?? '');
                                    ?>
                                    <li>
                                        <a <?php echo niyom_link_attrs($item['url'], !empty($item['new_tab'])); ?>
                                            <?php echo $label !== '' ? 'aria-label="' . esc_attr($label) . '"' : ''; ?>>
                                            <?php if (!empty($item['icon'])) : ?>
                                                <?php echo niyom_media_icon($item['icon']); // Cleaned in niyom_clean_svg(). ?>
                                            <?php else : ?>
                                                <span class="site-footer-social-text"><?php echo esc_html($label); ?></span>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </footer>
    <?php endif; ?>

    <?php wp_footer(); ?>
</body>

</html>

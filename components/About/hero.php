<?php
/**
 * About › Hero markup. Fields arrive in $args (see inc/blocks/about-block.php).
 * Each part prints only when its field has content.
 *
 * Also used by the Services, Computational Biosciences, Research Support and
 * Scientific Engagement heroes. Optional extras:
 *   - Buttons under the text: hero_primary_text / _link / _new_tab (teal) and
 *     hero_secondary_text / _link / _new_tab (outline). A button needs text and a link.
 *   - hero_flush_bottom (set in the render callback, not a field): no bottom
 *     padding, for pages where the next section brings its own top spacing.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow   = trim((string) ($args['hero_eyebrow'] ?? ''));
$title     = niyom_inline_text($args['hero_title'] ?? '');
$text      = niyom_rich_text($args['hero_text'] ?? '');
$image     = trim((string) ($args['hero_image'] ?? ''));
$image_alt = trim((string) ($args['hero_image_alt'] ?? ''));

// Optional buttons: [style => fields]. A button needs both text and a link.
$buttons = [];
foreach (['primary', 'secondary'] as $style) {
    $button_text = trim((string) ($args["hero_{$style}_text"] ?? ''));
    $button_link = trim((string) ($args["hero_{$style}_link"] ?? ''));
    if ($button_text !== '' && $button_link !== '') {
        $buttons[] = [
            'style'   => $style,
            'text'    => $button_text,
            'link'    => $button_link,
            'new_tab' => !empty($args["hero_{$style}_new_tab"]),
        ];
    }
}

$has_aside = $text || $buttons;
$has_copy  = $title || $has_aside;

if (!$eyebrow && !$has_copy && !$image) {
    return;
}

$classes = ['about-hero'];
if ($buttons) {
    $classes[] = 'about-hero--has-buttons';
}
if (!empty($args['hero_flush_bottom'])) {
    $classes[] = 'about-hero--flush-bottom';
}
?>

<section class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <div class="about-hero-inner">

        <?php if ($eyebrow || $has_copy) : ?>
            <header class="about-hero-header">
                <?php if ($eyebrow) : ?>
                    <p class="about-hero-eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($has_copy) : ?>
                    <div class="about-hero-copy">
                        <?php if ($title) : ?>
                            <h1 class="about-hero-title"><?php echo $title; // Escaped in niyom_inline_text(). ?></h1>
                        <?php endif; ?>

                        <?php if ($has_aside) : ?>
                            <div class="about-hero-aside">
                                <?php if ($text) : ?>
                                    <div class="about-hero-text rich-text"><?php echo $text; // Escaped in niyom_rich_text(). ?></div>
                                <?php endif; ?>

                                <?php if ($buttons) : ?>
                                    <div class="about-hero-buttons">
                                        <?php foreach ($buttons as $button) : ?>
                                            <a class="btn btn--<?php echo esc_attr($button['style']); ?> about-hero-button" <?php echo niyom_link_attrs($button['link'], $button['new_tab']); ?>>
                                                <span><?php echo esc_html($button['text']); ?></span>
                                                <?php echo niyom_icon('arrow-right', 'btn-arrow btn-arrow--lg'); ?>
                                                <?php echo niyom_icon('arrow-right-sm', 'btn-arrow btn-arrow--sm'); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <?php if ($image) : ?>
            <figure class="about-hero-media">
                <img class="about-hero-image" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($image_alt); ?>" fetchpriority="high" decoding="async">
            </figure>
        <?php endif; ?>

    </div>
</section>

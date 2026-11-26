<?php
/**
 * Insight article › Featured image + caption. $args: post_id.
 * Figma: 03 — Article Image (303:8346). 1680 × 720 image, caption 16px below
 * (Inter Medium 14, Deep Ink 50%). No featured image = no section.
 * Styles: src/styles/components/insights/_single-header.scss
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$post_id = (int) ($args['post_id'] ?? get_the_ID());
if (!has_post_thumbnail($post_id)) {
    return;
}

$caption = trim((string) niyom_insight_meta($post_id, 'insight_image_caption'));
$alt     = trim((string) get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true));
?>

<figure class="article-image">
    <div class="article-image-media">
        <?php echo get_the_post_thumbnail($post_id, 'full', [
            'class'         => 'article-image-img',
            'alt'           => $alt,
            'loading'       => 'eager',
            'fetchpriority' => 'high',
            'sizes'         => '(max-width: 1920px) 100vw, 1680px',
        ]); ?>
    </div>

    <?php if ($caption !== '') : ?>
        <figcaption class="article-image-caption"><?php echo esc_html($caption); ?></figcaption>
    <?php endif; ?>
</figure>

<?php
/**
 * Insights › Featured article. Fields arrive in $args (see inc/blocks/insights-block.php).
 * Figma: Insights › 03 — Featured Article (303:7735). #FAFAFA band: image
 * 1000 × 640 left, meta + H3 title + excerpt + button right (80px gap).
 * The post comes from the block's "Featured insight" field, or the newest
 * insight ($args['featured_id'] is set in the render callback).
 * Styles: src/styles/components/insights/_featured.scss
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$featured = get_post((int) ($args['featured_id'] ?? 0));
if (!$featured) {
    return;
}

$permalink   = get_permalink($featured);
$title       = get_the_title($featured);
$category    = niyom_insight_category_name($featured);
$excerpt     = has_excerpt($featured) ? get_the_excerpt($featured) : wp_trim_words(wp_strip_all_tags(strip_shortcodes($featured->post_content)), 32);
$button_text = trim((string) ($args['featured_button_text'] ?? ''));
$has_image   = has_post_thumbnail($featured);
$heading_id  = wp_unique_id('in-featured-heading-');
?>

<section class="in-featured<?php echo $has_image ? '' : ' in-featured--no-image'; ?>" aria-labelledby="<?php echo esc_attr($heading_id); ?>">
    <div class="in-featured-inner">
        <?php if ($has_image) : ?>
            <a class="in-featured-media" href="<?php echo esc_url($permalink); ?>" tabindex="-1" aria-hidden="true">
                <?php echo get_the_post_thumbnail($featured, 'full', [
                    'class'   => 'in-featured-image',
                    'alt'     => '',
                    'loading' => 'eager',
                    'sizes'   => '(max-width: 992px) 100vw, 53vw',
                ]); ?>
            </a>
        <?php endif; ?>

        <div class="in-featured-body">
            <p class="in-featured-meta">
                <time class="in-featured-date" datetime="<?php echo esc_attr(get_the_date('c', $featured)); ?>"><?php echo esc_html(get_the_date('j M, Y', $featured)); ?></time>
                <?php if ($category !== '') : ?>
                    <span class="in-featured-dot" aria-hidden="true"></span>
                    <span class="in-featured-category"><?php echo esc_html($category); ?></span>
                <?php endif; ?>
            </p>

            <h2 class="in-featured-title heading-three" id="<?php echo esc_attr($heading_id); ?>">
                <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
            </h2>

            <?php if (trim($excerpt) !== '') : ?>
                <p class="in-featured-text"><?php echo esc_html($excerpt); ?></p>
            <?php endif; ?>

            <?php if ($button_text !== '') : ?>
                <a class="btn btn--primary in-featured-button" href="<?php echo esc_url($permalink); ?>"
                    aria-label="<?php echo esc_attr($button_text . ': ' . $title); ?>">
                    <span><?php echo esc_html($button_text); ?></span>
                    <?php echo niyom_icon('arrow-right', 'btn-arrow btn-arrow--lg'); ?>
                    <?php echo niyom_icon('arrow-right-sm', 'btn-arrow btn-arrow--sm'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

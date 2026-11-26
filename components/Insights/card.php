<?php
/**
 * Insights › Card (one <li>). Used by the listing grid, the REST endpoint
 * (niyom/v1/insights) and "Continue reading" on articles.
 * Figma: component "Insights" (91:2055) — image 3:2, "20 Jan, 2026 · CATEGORY",
 * title, KNOW MORE ⟶. Styles: src/styles/components/insights/_card.scss
 *
 * $args: post (WP_Post|int), link_text (string, '' = no link).
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$card_post = get_post($args['post'] ?? 0);
if (!$card_post) {
    return;
}

$permalink = get_permalink($card_post);
$title     = get_the_title($card_post);
$category  = niyom_insight_category_name($card_post);
$link_text = trim((string) ($args['link_text'] ?? ''));
?>

<li class="in-card">
    <?php if (has_post_thumbnail($card_post)) : ?>
        <a class="in-card-media" href="<?php echo esc_url($permalink); ?>" tabindex="-1" aria-hidden="true">
            <?php echo get_the_post_thumbnail($card_post, 'large', [
                'class'   => 'in-card-image',
                'alt'     => '',
                'loading' => 'lazy',
                'sizes'   => '(max-width: 768px) 92vw, (max-width: 1200px) 46vw, 405px',
            ]); ?>
        </a>
    <?php else : ?>
        <span class="in-card-media in-card-media--empty" aria-hidden="true"></span>
    <?php endif; ?>

    <div class="in-card-body">
        <div class="in-card-text">
            <p class="in-card-meta">
                <time class="in-card-date" datetime="<?php echo esc_attr(get_the_date('c', $card_post)); ?>"><?php echo esc_html(get_the_date('j M, Y', $card_post)); ?></time>
                <?php if ($category !== '') : ?>
                    <span class="in-card-dot" aria-hidden="true"></span>
                    <span class="in-card-category"><?php echo esc_html($category); ?></span>
                <?php endif; ?>
            </p>

            <?php if ($title !== '') : ?>
                <h3 class="in-card-title">
                    <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
                </h3>
            <?php endif; ?>
        </div>

        <?php if ($link_text !== '') : ?>
            <a class="link-arrow in-card-link" href="<?php echo esc_url($permalink); ?>"
                aria-label="<?php echo esc_attr($link_text . ': ' . $title); ?>">
                <span><?php echo esc_html($link_text); ?></span>
                <?php echo niyom_icon('arrow-right', 'link-arrow-icon link-arrow-icon--lg'); ?>
                <?php echo niyom_icon('arrow-right-sm', 'link-arrow-icon link-arrow-icon--sm'); ?>
            </a>
        <?php endif; ?>
    </div>
</li>

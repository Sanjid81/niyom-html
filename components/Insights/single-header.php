<?php
/**
 * Insight article › Header. $args: post_id.
 * Figma: 02 — Article Header (303:8327). 1200px column (360px sides at
 * 1920): date · category, H1 (64px), lead text (24px, "Lead text" field
 * or the excerpt).
 * Styles: src/styles/components/insights/_single-header.scss
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$post_id  = (int) ($args['post_id'] ?? get_the_ID());
$category = niyom_insight_category_name($post_id);
$lead     = trim((string) niyom_insight_meta($post_id, 'insight_lead'));
if ($lead === '' && has_excerpt($post_id)) {
    $lead = get_the_excerpt($post_id);
}
?>

<header class="article-header">
    <div class="article-header-inner">
        <p class="article-header-meta">
            <time class="article-header-date" datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>"><?php echo esc_html(get_the_date('j M, Y', $post_id)); ?></time>
            <?php if ($category !== '') : ?>
                <span class="article-header-dot" aria-hidden="true"></span>
                <span class="article-header-category"><?php echo esc_html($category); ?></span>
            <?php endif; ?>
        </p>

        <h1 class="article-header-title"><?php echo esc_html(get_the_title($post_id)); ?></h1>

        <?php if ($lead !== '') : ?>
            <p class="article-header-lead"><?php echo nl2br(esc_html($lead), false); ?></p>
        <?php endif; ?>
    </div>
</header>

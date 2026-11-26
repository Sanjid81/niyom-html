<?php
/**
 * Insight article › About the author. $args: post_id.
 * Figma: 06 — About the Author (303:8436). #FCFBF7 band: 140px round photo,
 * label, name (H4), short bio, ABOUT NIYOM ⟶. Lined up with the article
 * column. Empty name and bio = no section.
 *
 * Fields: Article details › Author. Empty name / bio fall back to the post
 * author's display name / biographical info.
 * Styles: src/styles/components/insights/_single-author.scss
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$post_id   = (int) ($args['post_id'] ?? get_the_ID());
$author_id = (int) get_post_field('post_author', $post_id);
$author    = $author_id ? get_userdata($author_id) : false;

$name = trim((string) niyom_insight_meta($post_id, 'insight_author_name'));
if ($name === '' && $author) {
    $name = $author->display_name;
}
$bio = trim((string) niyom_insight_meta($post_id, 'insight_author_bio'));
if ($bio === '' && $author) {
    $bio = trim((string) get_the_author_meta('description', $author_id));
}

if ($name === '' && $bio === '') {
    return;
}

$label     = trim((string) niyom_option('niyom_insights_author_label'));
$role      = trim((string) niyom_insight_meta($post_id, 'insight_author_role'));
$photo     = trim((string) niyom_insight_meta($post_id, 'insight_author_photo'));
$link_text = trim((string) niyom_insight_meta($post_id, 'insight_author_link_text'));
$link      = trim((string) niyom_insight_meta($post_id, 'insight_author_link'));
$new_tab   = !empty(niyom_insight_meta($post_id, 'insight_author_new_tab'));
?>

<section class="article-author" aria-label="<?php echo esc_attr($label !== '' ? $label : __('About the author', 'niyom')); ?>">
    <div class="article-author-inner">
        <div class="article-author-photo<?php echo $photo !== '' ? '' : ' article-author-photo--empty'; ?>" aria-hidden="true">
            <?php if ($photo !== '') : ?>
                <img class="article-author-image" src="<?php echo esc_url($photo); ?>" alt="" width="140" height="140" loading="lazy" decoding="async">
            <?php endif; ?>
        </div>

        <div class="article-author-body">
            <?php if ($label !== '') : ?>
                <p class="article-author-label"><?php echo esc_html($label); ?></p>
            <?php endif; ?>

            <?php if ($name !== '') : ?>
                <p class="article-author-name"><?php echo esc_html($name); ?></p>
            <?php endif; ?>

            <?php if ($role !== '') : ?>
                <p class="article-author-role"><?php echo esc_html($role); ?></p>
            <?php endif; ?>

            <?php if ($bio !== '') : ?>
                <p class="article-author-bio"><?php echo nl2br(esc_html($bio), false); ?></p>
            <?php endif; ?>

            <?php if ($link_text !== '' && $link !== '') : ?>
                <a class="link-arrow article-author-link" <?php echo niyom_link_attrs($link, $new_tab); ?>>
                    <span><?php echo esc_html($link_text); ?></span>
                    <?php echo niyom_icon('arrow-right', 'link-arrow-icon link-arrow-icon--lg'); ?>
                    <?php echo niyom_icon('arrow-right-sm', 'link-arrow-icon link-arrow-icon--sm'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

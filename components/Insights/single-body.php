<?php
/**
 * Insight article › Body. $args: post_id.
 * Figma: 04 — Article Body (303:8349) + 05 — References (303:8419).
 * Three columns at 1920: "On this page" + share (280) · article (840) ·
 * "Related service" box (320), 120px gaps.
 *
 *   TOC        built from the article's H2 headings (ids added here), plus
 *              "References" when there are references. Sticky on desktop,
 *              a collapsible box on tablet/mobile. JS highlights the
 *              current section (src/scripts/components/insights.js).
 *   Share      Facebook, LinkedIn, X, email (plain share links) + copy link.
 *   Content    the post content (Gutenberg blocks, styled in _single-body.scss),
 *              then Insight Tags as pills and the references list.
 *   Rail       "Related service" box (Article details › Related service).
 *
 * Styles: src/styles/components/insights/_single-body.scss
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$post_id = (int) ($args['post_id'] ?? get_the_ID());

// Article content with ids on its H2s.
$content          = apply_filters('the_content', get_the_content(null, false, $post_id));
[$content, $toc]  = niyom_insight_toc(str_replace(']]>', ']]&gt;', $content));

// References.
$ref_heading = trim((string) niyom_insight_meta($post_id, 'insight_references_heading'));
$references  = [];
foreach ((array) niyom_insight_meta($post_id, 'insight_references') as $row) {
    $text = niyom_inline_text($row['text'] ?? '');
    if ($text) {
        $references[] = $text;
    }
}
if ($references && $ref_heading !== '') {
    $toc[] = ['id' => 'references', 'text' => $ref_heading];
}

// Tags.
$tags = get_the_terms($post_id, 'insight_tag');
$tags = ($tags && !is_wp_error($tags)) ? $tags : [];

// Labels (Insights › Article Settings).
$toc_label   = trim((string) niyom_option('niyom_insights_toc_label'));
$share_label = trim((string) niyom_option('niyom_insights_share_label'));
$show_toc    = $toc_label !== '' && $toc;

// Share links (no third-party scripts).
$url    = get_permalink($post_id);
$title  = get_the_title($post_id);
$shares = [];
if ($share_label !== '') {
    $shares = [
        [
            'icon'  => 'social/facebook',
            'label' => __('Share on Facebook', 'niyom'),
            'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode($url),
        ],
        [
            'icon'  => 'social/linkedin',
            'label' => __('Share on LinkedIn', 'niyom'),
            'url'   => 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode($url),
        ],
        [
            'icon'  => 'social/x',
            'label' => __('Share on X', 'niyom'),
            'url'   => 'https://x.com/intent/tweet?url=' . rawurlencode($url) . '&text=' . rawurlencode($title),
        ],
        [
            'icon'  => 'social/email',
            'label' => __('Share by email', 'niyom'),
            'url'   => 'mailto:?subject=' . rawurlencode($title) . '&body=' . rawurlencode($url),
            'mail'  => true,
        ],
    ];
}

// Related service box.
$service = [
    'label'     => trim((string) niyom_insight_meta($post_id, 'insight_service_label')),
    'title'     => niyom_inline_text(niyom_insight_meta($post_id, 'insight_service_title')),
    'text'      => niyom_inline_text(niyom_insight_meta($post_id, 'insight_service_text')),
    'link_text' => trim((string) niyom_insight_meta($post_id, 'insight_service_link_text')),
    'link'      => trim((string) niyom_insight_meta($post_id, 'insight_service_link')),
    'new_tab'   => !empty(niyom_insight_meta($post_id, 'insight_service_new_tab')),
];
$has_service = (bool) $service['title'];

$uid = wp_unique_id('article-toc-');

$classes = ['article-body'];
if (!$show_toc && !$shares) {
    $classes[] = 'article-body--no-aside';
}
if (!$has_service) {
    $classes[] = 'article-body--no-rail';
}
?>

<div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <div class="article-body-inner">

        <?php if ($show_toc || $shares) : ?>
            <aside class="article-aside" aria-label="<?php esc_attr_e('Article navigation and sharing', 'niyom'); ?>">
                <?php if ($show_toc) : ?>
                    <nav class="article-toc" data-article-toc aria-labelledby="<?php echo esc_attr($uid); ?>-label">
                        <p class="article-toc-label" id="<?php echo esc_attr($uid); ?>-label"><?php echo esc_html($toc_label); ?></p>
                        <button class="article-toc-toggle" type="button" aria-expanded="true" aria-controls="<?php echo esc_attr($uid); ?>-list" hidden>
                            <span><?php echo esc_html($toc_label); ?></span>
                            <?php echo niyom_icon('chevron-down', 'article-toc-chevron'); ?>
                        </button>

                        <ol class="article-toc-list" id="<?php echo esc_attr($uid); ?>-list" role="list">
                            <?php foreach ($toc as $item) : ?>
                                <li class="article-toc-item">
                                    <a class="article-toc-link" href="#<?php echo esc_attr($item['id']); ?>"><?php echo esc_html($item['text']); ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </nav>
                <?php endif; ?>

                <?php if ($shares) : ?>
                    <div class="article-share">
                        <p class="article-share-label"><?php echo esc_html($share_label); ?></p>
                        <ul class="article-share-list" role="list">
                            <?php foreach ($shares as $share) : ?>
                                <li>
                                    <a class="article-share-button" href="<?php echo esc_url($share['url'], ['https', 'mailto']); ?>"
                                        <?php echo empty($share['mail']) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
                                        aria-label="<?php echo esc_attr($share['label']); ?>">
                                        <?php echo niyom_icon($share['icon'], 'article-share-icon'); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <li>
                                <button class="article-share-button article-share-copy" type="button" data-copy-url="<?php echo esc_url($url); ?>"
                                    data-copied-text="<?php esc_attr_e('Link copied', 'niyom'); ?>"
                                    aria-label="<?php esc_attr_e('Copy link', 'niyom'); ?>" hidden>
                                    <?php echo niyom_icon('copy', 'article-share-icon'); ?>
                                </button>
                            </li>
                        </ul>
                        <p class="article-share-status" role="status" aria-live="polite"></p>
                    </div>
                <?php endif; ?>
            </aside>
        <?php endif; ?>

        <div class="article-main">
            <div class="article-content">
                <?php echo $content; // Post content, filtered by WordPress (the_content). ?>
            </div>

            <?php if ($tags) : ?>
                <ul class="article-tags shared-tags" aria-label="<?php esc_attr_e('Tags', 'niyom'); ?>">
                    <?php foreach ($tags as $tag) : ?>
                        <li class="shared-tag"><?php echo esc_html($tag->name); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ($references) : ?>
                <section class="article-references" id="references"<?php echo $ref_heading !== '' ? ' aria-labelledby="' . esc_attr($uid) . '-references"' : ''; ?>>
                    <?php if ($ref_heading !== '') : ?>
                        <h2 class="article-references-heading" id="<?php echo esc_attr($uid); ?>-references"><?php echo esc_html($ref_heading); ?></h2>
                    <?php endif; ?>

                    <ol class="article-references-list" role="list">
                        <?php foreach ($references as $index => $reference) : ?>
                            <li class="article-references-item">
                                <span class="article-references-number">[<?php echo esc_html($index + 1); ?>]</span>
                                <p class="article-references-text"><?php echo $reference; // Escaped in niyom_inline_text(). ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </section>
            <?php endif; ?>
        </div>

        <?php if ($has_service) : ?>
            <aside class="article-rail" aria-label="<?php echo esc_attr($service['label'] !== '' ? $service['label'] : __('Related service', 'niyom')); ?>">
                <div class="article-service">
                    <?php if ($service['label'] !== '') : ?>
                        <p class="article-service-label"><?php echo esc_html($service['label']); ?></p>
                    <?php endif; ?>

                    <p class="article-service-title"><?php echo $service['title']; // Escaped in niyom_inline_text(). ?></p>

                    <?php if ($service['text']) : ?>
                        <p class="article-service-text"><?php echo $service['text']; // Escaped in niyom_inline_text(). ?></p>
                    <?php endif; ?>

                    <?php if ($service['link_text'] !== '' && $service['link'] !== '') : ?>
                        <a class="link-arrow article-service-link" <?php echo niyom_link_attrs($service['link'], $service['new_tab']); ?>>
                            <span><?php echo esc_html($service['link_text']); ?></span>
                            <?php echo niyom_icon('arrow-right', 'link-arrow-icon link-arrow-icon--lg'); ?>
                            <?php echo niyom_icon('arrow-right-sm', 'link-arrow-icon link-arrow-icon--sm'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </aside>
        <?php endif; ?>

    </div>
</div>

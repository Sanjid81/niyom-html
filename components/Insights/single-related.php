<?php
/**
 * Insight article › Continue reading. $args: post_id.
 * Figma: 07 — Related Articles (303:8443). Label + H2 left, ALL INSIGHTS ⟶
 * right; 4 cards (same card as the listing grid): same category first,
 * then the newest insights. Texts: Insights › Article Settings.
 * Styles: src/styles/components/insights/_single-related.scss + _card.scss
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$post_id = (int) ($args['post_id'] ?? get_the_ID());
$posts   = niyom_insight_related($post_id, 4);

if (!$posts) {
    return;
}

$eyebrow   = trim((string) niyom_option('niyom_insights_related_eyebrow'));
$heading   = niyom_inline_text(niyom_option('niyom_insights_related_heading'));
$link_text = trim((string) niyom_option('niyom_insights_related_link_text'));
$link      = trim((string) niyom_option('niyom_insights_related_link'));
$card_link = trim((string) niyom_option('niyom_insights_card_link_text'));

$heading_id = wp_unique_id('article-related-heading-');
?>

<section class="article-related"<?php echo $heading ? ' aria-labelledby="' . esc_attr($heading_id) . '"' : ''; ?>>
    <div class="article-related-inner">

        <?php if ($eyebrow !== '' || $heading || ($link_text !== '' && $link !== '')) : ?>
            <div class="article-related-intro">
                <div class="article-related-titles">
                    <?php if ($eyebrow !== '') : ?>
                        <p class="article-related-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                    <?php endif; ?>

                    <?php if ($heading) : ?>
                        <h2 class="article-related-heading heading-two" id="<?php echo esc_attr($heading_id); ?>"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                    <?php endif; ?>
                </div>

                <?php if ($link_text !== '' && $link !== '') : ?>
                    <a class="link-arrow article-related-link" <?php echo niyom_link_attrs($link); ?>>
                        <span><?php echo esc_html($link_text); ?></span>
                        <?php echo niyom_icon('arrow-right', 'link-arrow-icon link-arrow-icon--lg'); ?>
                        <?php echo niyom_icon('arrow-right-sm', 'link-arrow-icon link-arrow-icon--sm'); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <ul class="in-cards in-cards--scroll" role="list">
            <?php foreach ($posts as $card_post) {
                echo niyom_insight_card($card_post, $card_link); // Escaped in components/Insights/card.php.
            } ?>
        </ul>

    </div>
</section>

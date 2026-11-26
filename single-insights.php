<?php
/**
 * Single Insight article.
 *
 * Figma: "Insight Article Details" (303:8325, 1920 wide). Sections:
 *   Header      → components/Insights/single-header.php   (date · category, H1, lead)
 *   Image       → components/Insights/single-image.php    (featured image + caption)
 *   Body        → components/Insights/single-body.php     ("On this page" + share,
 *                 article content + tags + references, "Related service" box)
 *   Author      → components/Insights/single-author.php
 *   Related     → components/Insights/single-related.php  ("Continue reading", 4 cards)
 *   CTA         → components/Shared/cta-band.php          (Insights › Article Settings)
 *
 * Post fields: inc/insights-meta.php ("Article details" box on each insight).
 * Styles: src/styles/components/insights/_single-*.scss · JS: src/scripts/components/insights.js
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

get_header();

while (have_posts()) :
    the_post();

    $post_id = get_the_ID();
    $args    = ['post_id' => $post_id];
    ?>

    <article class="article" id="post-<?php echo esc_attr($post_id); ?>">
        <?php
        get_template_part('components/Insights/single-header', null, $args);
        get_template_part('components/Insights/single-image', null, $args);
        get_template_part('components/Insights/single-body', null, $args);
        get_template_part('components/Insights/single-author', null, $args);
        ?>
    </article>

    <?php
    get_template_part('components/Insights/single-related', null, $args);

    get_template_part('components/Shared/cta-band', null, [
        'cta_band_eyebrow'           => niyom_option('niyom_insights_cta_eyebrow'),
        'cta_band_heading'           => niyom_option('niyom_insights_cta_heading'),
        'cta_band_image'             => niyom_option('niyom_insights_cta_image'),
        'cta_band_primary_text'      => niyom_option('niyom_insights_cta_primary_text'),
        'cta_band_primary_link'      => niyom_option('niyom_insights_cta_primary_link'),
        'cta_band_primary_new_tab'   => niyom_option('niyom_insights_cta_primary_new_tab'),
        'cta_band_secondary_text'    => niyom_option('niyom_insights_cta_secondary_text'),
        'cta_band_secondary_link'    => niyom_option('niyom_insights_cta_secondary_link'),
        'cta_band_secondary_new_tab' => niyom_option('niyom_insights_cta_secondary_new_tab'),
    ]);
endwhile;

get_footer();

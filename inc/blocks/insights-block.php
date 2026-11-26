<?php
/**
 * Insights page blocks (Carbon Fields), in page order.
 *
 * Pattern for each block:
 *   1. Block::make() here with its fields and default content.
 *   2. Markup in components/Insights/<name>.php, receiving fields as $args.
 *   3. Styles in src/styles/components/insights/_<name>.scss.
 *
 * Sections (prefix "in-"):
 *   Hero        → components/Insights/hero.php        (label, H1, text right)
 *   Featured    → components/Insights/featured.php    (one insight: image + text)
 *   Grid        → components/Insights/grid.php        (category tabs, cards, load more)
 *                 Cards: components/Insights/card.php · REST + helpers: inc/insights-meta.php
 *                 JS: src/scripts/components/insights.js
 *   Newsletter  → components/Insights/newsletter.php  (teal band + CF7 "Newsletter" form)
 *
 * Single articles: single-insights.php (fields in inc/insights-meta.php).
 *
 * Figma: "Insights" (303:7339, 1920 wide). No mobile frame exists in Figma;
 * mobile follows the Home mobile patterns (276:1785).
 *
 * Defaults fill a new block with the Figma content. Any field left empty
 * is not shown on the page.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Block;
use Carbon_Fields\Field;

/**
 * The featured insight: the post picked in the Featured block, or the newest
 * published insight. Remembered for the page so the grid can leave it out.
 *
 * @param array $picked Carbon Fields association value.
 * @return int Post ID (0 when there are no insights).
 */
function niyom_insights_featured_id($picked)
{
    foreach ((array) $picked as $item) {
        $id = (int) ($item['id'] ?? 0);
        if ($id && get_post_status($id) === 'publish' && get_post_type($id) === 'insights') {
            return $id;
        }
    }

    $latest = get_posts([
        'post_type'           => 'insights',
        'post_status'         => 'publish',
        'posts_per_page'      => 1,
        'fields'              => 'ids',
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
    ]);

    return $latest ? (int) $latest[0] : 0;
}

// =====================================================================
// Insights › Hero
// =====================================================================
/**
 * Figma: 02 — Hero (303:7341). Label; H1 left, paragraph right.
 * Markup: components/Insights/hero.php · Styles: src/styles/components/insights/_hero.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_insights_hero', __('Insights Hero', 'niyom'))
        ->set_description(__('Page intro: label, large heading left and a short paragraph right.', 'niyom'))
        ->set_icon('cover-image')
        ->set_keywords([__('insights', 'niyom'), __('hero', 'niyom'), __('intro', 'niyom')])
        ->set_category('niyom-insights', __('Niyom — Insights', 'niyom'), 'lightbulb')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'hero_eyebrow', __('Label', 'niyom'))
                ->set_default_value('Insights')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'hero_title', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Ideas, insights and scientific perspectives.')
                ->set_help_text(__('Main page heading (H1). Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'hero_text', __('Text (right)', 'niyom'))
                ->set_default_value('Notes on research practice, computational methods and responsible interpretation — written by the people doing the work.'),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Insights/hero', null, $fields);
        });
});


// =====================================================================
// Insights › Featured article
// =====================================================================
/**
 * Figma: 03 — Featured Article (303:7735). Image, date · category, title,
 * excerpt and button come from the chosen insight (title, excerpt,
 * featured image, Insight Category). The hidden author row is not built.
 * Markup: components/Insights/featured.php · Styles: src/styles/components/insights/_featured.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_insights_featured', __('Insights Featured Article', 'niyom'))
        ->set_description(__('One insight shown large: image left, date, category, title, excerpt and a button right.', 'niyom'))
        ->set_icon('star-filled')
        ->set_keywords([__('insights', 'niyom'), __('featured', 'niyom'), __('article', 'niyom')])
        ->set_category('niyom-insights', __('Niyom — Insights', 'niyom'), 'lightbulb')
        ->set_mode('both')
        ->add_fields([
            Field::make('association', 'featured_post', __('Featured insight', 'niyom'))
                ->set_types([['type' => 'post', 'post_type' => 'insights']])
                ->set_max(1)
                ->set_help_text(__('Leave empty to feature the newest insight. The featured insight is left out of the grid below.', 'niyom')),

            Field::make('text', 'featured_button_text', __('Button Text', 'niyom'))
                ->set_default_value('Read article')
                ->set_help_text(__('Leave empty to hide the button (the title stays a link).', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            $fields['featured_id'] = niyom_insights_featured_id($fields['featured_post'] ?? []);
            $GLOBALS['niyom_insights_featured_id'] = $fields['featured_id'];
            get_template_part('components/Insights/featured', null, $fields);
        });
});


// =====================================================================
// Insights › Grid (category tabs + cards + load more)
// =====================================================================
/**
 * Figma: Content (303:7752). Label + H2; category tabs; 4-column cards;
 * LOAD MORE INSIGHTS. Cards are the published insights, newest first,
 * without the featured article. Tabs list the Insight Categories that
 * have posts. The hidden "Topic" select in Figma is not built.
 * Markup: components/Insights/grid.php · Styles: src/styles/components/insights/_grid.scss
 * JS: src/scripts/components/insights.js (REST: /wp-json/niyom/v1/insights)
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_insights_grid', __('Insights Grid', 'niyom'))
        ->set_description(__('All insights as cards with category tabs and a "Load more" button.', 'niyom'))
        ->set_icon('grid-view')
        ->set_keywords([__('insights', 'niyom'), __('articles', 'niyom'), __('categories', 'niyom')])
        ->set_category('niyom-insights', __('Niyom — Insights', 'niyom'), 'lightbulb')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'grid_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Insights')
                ->set_help_text(__('Brackets are added automatically: "( INSIGHTS )".', 'niyom')),

            Field::make('textarea', 'grid_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Ideas, Insights, and Scientific perspectives.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('separator', 'grid_sep_cards', __('Tabs & Cards', 'niyom')),

            Field::make('text', 'grid_all_label', __('"All" tab', 'niyom'))
                ->set_width(33)
                ->set_default_value('All')
                ->set_help_text(__('The other tabs are the Insight Categories that have posts. Leave empty to hide the tabs.', 'niyom')),

            Field::make('text', 'grid_per_page', __('Cards per load', 'niyom'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '1')
                ->set_attribute('max', '24')
                ->set_width(33)
                ->set_default_value('8')
                ->set_help_text(__('Cards shown first and added by each "Load more" (1–24).', 'niyom')),

            Field::make('text', 'grid_link_text', __('Card link text', 'niyom'))
                ->set_width(34)
                ->set_default_value('Know more')
                ->set_help_text(__('Leave empty to hide the link (titles stay links).', 'niyom')),

            Field::make('text', 'grid_load_more_text', __('"Load more" button', 'niyom'))
                ->set_width(50)
                ->set_default_value('Load more insights')
                ->set_help_text(__('Shown while more insights are available. Leave empty to show only the first cards.', 'niyom')),

            Field::make('text', 'grid_empty_text', __('Text when a tab has no insights', 'niyom'))
                ->set_width(50)
                ->set_default_value('No insights in this category yet.'),

            Field::make('checkbox', 'grid_exclude_featured', __('Leave out the featured insight', 'niyom'))
                ->set_option_value('yes')
                ->set_default_value(true)
                ->set_help_text(__('The insight shown in the "Insights Featured Article" block above is not repeated here.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            $fields['featured_id'] = !empty($fields['grid_exclude_featured']) ? (int) ($GLOBALS['niyom_insights_featured_id'] ?? 0) : 0;
            get_template_part('components/Insights/grid', null, $fields);
        });
});


// =====================================================================
// Insights › Newsletter band
// =====================================================================
/**
 * Figma: 08 — CTA (303:7563). Teal band: label + heading left, email field
 * + SUBSCRIBE and a note right. The form is Contact Form 7 "Newsletter".
 * Markup: components/Insights/newsletter.php · Styles: src/styles/components/insights/_newsletter.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_insights_newsletter', __('Insights Newsletter', 'niyom'))
        ->set_description(__('Teal band with a heading and the newsletter sign-up form.', 'niyom'))
        ->set_icon('email-alt')
        ->set_keywords([__('newsletter', 'niyom'), __('subscribe', 'niyom'), __('cta', 'niyom')])
        ->set_category('niyom-insights', __('Niyom — Insights', 'niyom'), 'lightbulb')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'newsletter_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Request research support'),

            Field::make('textarea', 'newsletter_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Interested in working together on a study, tool or training?')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('image', 'newsletter_image', __('Background Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/home/expertise-bg.jpg')
                ->set_help_text(__('Faint pattern under the teal colour.', 'niyom')),

            Field::make('text', 'newsletter_form', __('Form Shortcode', 'niyom'))
                ->set_default_value('[contact-form-7 title="Newsletter"]')
                ->set_help_text(__('Contact Form 7 shortcode (Contact › Contact Forms › "Newsletter"). Leave empty to hide the form.', 'niyom')),

            Field::make('text', 'newsletter_note', __('Note under the form', 'niyom'))
                ->set_default_value('One considered email a month. No noise, unsubscribe anytime.'),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Insights/newsletter', null, $fields);
        });
});

<?php
/**
 * Home Insights block (Carbon Fields).
 *
 * Figma: Home — Desktop › Insights (182:677), Home — Mobile › Insights (276:1898).
 * Intro (eyebrow, heading, button) is edited here; the cards are the latest
 * posts of the "insights" post type (title, featured image, date, category).
 *
 * Markup: components/Home/insights.php
 * Styles: src/styles/components/home/insights.scss
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Block;
use Carbon_Fields\Field;

/**
 * Options for the category filter: "All" + every Insight Category.
 *
 * @return array slug => name
 */
function niyom_home_insights_category_options()
{
    $options = ['' => __('All categories', 'niyom')];
    $terms   = get_terms([
        'taxonomy'   => 'insight_category',
        'hide_empty' => false,
    ]);

    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            $options[$term->slug] = $term->name;
        }
    }

    return $options;
}

add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_home_insights', __('Home Insights', 'niyom'))
        ->set_description(__('Section intro + the latest Insights posts as cards.', 'niyom'))
        ->set_icon('lightbulb')
        ->set_keywords([__('insights', 'niyom'), __('posts', 'niyom'), __('articles', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'insights_eyebrow', __('Eyebrow', 'niyom'))
                ->set_default_value('Insights')
                ->set_help_text(__('Shown as "( INSIGHTS )". Leave empty to hide.', 'niyom')),
            Field::make('textarea', 'insights_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Ideas, Insights, and Scientific perspectives.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('text', 'insights_button_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('View All Insights'),
            Field::make('text', 'insights_button_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/insights/'),
            Field::make('checkbox', 'insights_button_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Cards (from Insights posts) ----------
            Field::make('separator', 'insights_sep_posts', __('Cards — latest Insights posts', 'niyom')),

            Field::make('text', 'insights_count', __('Number of posts', 'niyom'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '1')
                ->set_attribute('max', '12')
                ->set_width(33)
                ->set_default_value('4'),
            Field::make('select', 'insights_category', __('Category', 'niyom'))
                ->set_width(33)
                ->add_options('niyom_home_insights_category_options')
                ->set_help_text(__('Show posts from one Insight Category only, or all.', 'niyom')),
            Field::make('text', 'insights_link_text', __('Card Link Text', 'niyom'))
                ->set_width(33)
                ->set_default_value('Know more')
                ->set_help_text(__('Leave empty to hide the link under each card.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/insights', null, $fields);
        });
});

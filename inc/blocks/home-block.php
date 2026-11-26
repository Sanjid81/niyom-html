<?php
/**
 * Home page blocks (Carbon Fields).
 *
 * Pattern for each block:
 *   1. Block::make() here with its fields and default content.
 *   2. Markup in components/Home/<name>.php, receiving fields as $args.
 *   3. Styles in src/styles/components/home/<name>.scss, imported in index.scss.
 *
 * Defaults fill a new block with the Figma content. Any field left empty
 * is not shown on the page.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {

    $category = ['niyom-home', __('Niyom — Home', 'niyom'), 'admin-home'];

    // -----------------------------------------------------------------
    // Home Banner (Figma: Home — Desktop › Banner, Home — Mobile › Hero)
    // -----------------------------------------------------------------
    Block::make('niyom_home_banner', __('Home Banner', 'niyom'))
        ->set_description(__('Full-width hero: background image, large title, text, button and stage labels.', 'niyom'))
        ->set_icon('cover-image')
        ->set_keywords([__('hero', 'niyom'), __('banner', 'niyom'), __('home', 'niyom')])
        ->set_category(...$category)
        ->set_mode('both')
        ->add_fields([
            // Content.
            Field::make('separator', 'banner_sep_content', __('Content', 'niyom')),

            Field::make('textarea', 'banner_title', __('Title', 'niyom'))
                ->set_rows(3)
                ->set_default_value("Biology.\nComputation.\nInsight.")
                ->set_help_text(__('Press Enter for a new line. Leave empty to hide.', 'niyom')),

            Field::make('rich_text', 'banner_text', __('Text', 'niyom'))
                ->set_default_value('NIYOM connects biological research with computational approaches and scientific expertise to support rigorous, reproducible outcomes.')
                ->set_help_text(__('Paragraphs, line breaks, links, lists and images all work. Leave empty to hide.', 'niyom')),

            Field::make('text', 'banner_button_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('Discuss Your Research'),
            Field::make('text', 'banner_button_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/work-with-us/'),
            Field::make('checkbox', 'banner_button_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // Images.
            Field::make('separator', 'banner_sep_images', __('Images', 'niyom')),

            Field::make('image', 'banner_image', __('Background Image', 'niyom'))
                ->set_value_type('url')
                ->set_width(50)
                ->set_default_value(NIYOM_URI . '/assets/images/home/hero-banner.jpg')
                ->set_help_text(__('Wide image, about 2000 × 800 px. Leave empty for a plain dark background.', 'niyom')),
            Field::make('image', 'banner_image_mobile', __('Mobile Image (optional)', 'niyom'))
                ->set_value_type('url')
                ->set_width(50)
                ->set_help_text(__('Used on screens up to 768px wide. If empty, the background image is used.', 'niyom')),

            // Stage labels.
            Field::make('separator', 'banner_sep_stages', __('Stage Labels', 'niyom')),

            Field::make('complex', 'banner_stages', __('Stages', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_max(3)
                ->add_fields([
                    Field::make('text', 'label', __('Label', 'niyom'))->set_width(70),
                    Field::make('checkbox', 'active', __('Highlight', 'niyom'))->set_width(30),
                ])
                ->set_header_template('<%- label ? label : "Stage" %>')
                ->set_default_value([
                    ['label' => '01 — Biological Structure', 'active' => false],
                    ['label' => '02 — Computational Data', 'active' => true],
                    ['label' => '03 — Scientific Insight', 'active' => false],
                ])
                ->set_help_text(__('Up to 3 labels along the bottom of the banner. Remove all to hide the row.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/banner', null, $fields);
        });
});

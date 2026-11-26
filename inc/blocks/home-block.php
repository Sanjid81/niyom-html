<?php
/**
 * Home page blocks (Carbon Fields).
 *
 * Pattern for each block:
 *   1. Block::make() here with its fields and default content.
 *   2. Markup in components/Home/<name>.php, receiving fields as $args.
 *   3. Styles in src/styles/components/home/<name>.scss, imported in index.scss.
 *   4. Behaviour (if any) in src/scripts/components/home-<name>.js.
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

    // -----------------------------------------------------------------
    // Home Banner slider (Figma: Home — Desktop › Banner, Home — Mobile › Hero)
    // Each slide is one tab. The tab label shows along the bottom of the
    // banner and switches slides when clicked.
    // -----------------------------------------------------------------
    Block::make('niyom_home_banner', __('Home Banner', 'niyom'))
        ->set_description(__('Full-width hero slider: each slide has a tab label, image, title, text and button.', 'niyom'))
        ->set_icon('slides')
        ->set_keywords([__('hero', 'niyom'), __('banner', 'niyom'), __('slider', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            Field::make('complex', 'banner_slides', __('Slides', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Slides', 'niyom'),
                    'singular_name' => __('Slide', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'tab_label', __('Tab Label', 'niyom'))
                        ->set_help_text(__('Shown along the bottom of the banner, e.g. "01 — Biological Structure".', 'niyom')),

                    Field::make('image', 'image', __('Background Image', 'niyom'))
                        ->set_value_type('url')
                        ->set_help_text(__('Wide image, about 2000 × 800 px.', 'niyom')),
                    Field::make('image', 'image_mobile', __('Mobile Image (optional)', 'niyom'))
                        ->set_value_type('url')
                        ->set_help_text(__('Used up to 768px wide. If empty, the background image is used.', 'niyom')),

                    Field::make('textarea', 'title', __('Title', 'niyom'))
                        ->set_rows(3)
                        ->set_help_text(__('Press Enter for a new line.', 'niyom')),
                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links, lists and images all work.', 'niyom')),

                    Field::make('text', 'button_text', __('Button Text', 'niyom')),
                    Field::make('text', 'button_link', __('Button Link', 'niyom')),
                    Field::make('checkbox', 'button_new_tab', __('Open in new tab', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'            => 'home-banner-slide-1',
                        '_type'          => '_',
                        'tab_label'      => '01 — Biological Structure',
                        'image'          => NIYOM_URI . '/assets/images/home/hero-biological-structure.jpg',
                        'image_mobile'   => '',
                        'title'          => "Biology.\nComputation.\nInsight.",
                        'text'           => 'NIYOM connects biological research with computational approaches and scientific expertise to support rigorous, reproducible outcomes.',
                        'button_text'    => 'Discuss Your Research',
                        'button_link'    => '/work-with-us/',
                        'button_new_tab' => false,
                    ],
                    [
                        '_id'            => 'home-banner-slide-2',
                        '_type'          => '_',
                        'tab_label'      => '02 — Computational Data',
                        'image'          => NIYOM_URI . '/assets/images/home/hero-computational-data.jpg',
                        'image_mobile'   => '',
                        'title'          => "Biology.\nComputation.\nInsight.",
                        'text'           => 'NIYOM connects biological research with computational approaches and scientific expertise to support rigorous, reproducible outcomes.',
                        'button_text'    => 'Discuss Your Research',
                        'button_link'    => '/work-with-us/',
                        'button_new_tab' => false,
                    ],
                    [
                        '_id'            => 'home-banner-slide-3',
                        '_type'          => '_',
                        'tab_label'      => '03 — Scientific Insight',
                        'image'          => NIYOM_URI . '/assets/images/home/hero-scientific-insight.jpg',
                        'image_mobile'   => '',
                        'title'          => "Biology.\nComputation.\nInsight.",
                        'text'           => 'NIYOM connects biological research with computational approaches and scientific expertise to support rigorous, reproducible outcomes.',
                        'button_text'    => 'Discuss Your Research',
                        'button_link'    => '/work-with-us/',
                        'button_new_tab' => false,
                    ],
                ])
                ->set_help_text(__('Each tab is one slide. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),

            Field::make('separator', 'banner_sep_settings', __('Slider Settings', 'niyom')),

            Field::make('checkbox', 'banner_autoplay', __('Autoplay', 'niyom'))
                ->set_option_value('yes')
                ->set_default_value(true),
            Field::make('text', 'banner_delay', __('Seconds per slide', 'niyom'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '2')
                ->set_attribute('step', '0.5')
                ->set_default_value('6')
                ->set_conditional_logic([['field' => 'banner_autoplay', 'value' => true]]),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/banner', null, $fields);
        });
});

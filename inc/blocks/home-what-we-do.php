<?php
/**
 * Home › What We Do block (Carbon Fields).
 *
 * Figma: Home — Desktop › Content (206:2914), Home — Mobile › Three Disciplines (276:1813).
 * Markup: components/Home/what-we-do.php
 * Styles: src/styles/components/home/what-we-do.scss
 * JS:     src/scripts/components/home-what-we-do.js (stacking polish + mobile accordion)
 *
 * Desktop/tablet: the area cards stack on scroll (sticky). Mobile: accordion.
 * Any field left empty is not shown on the page.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {

    $img = NIYOM_URI . '/assets/images/home/';

    Block::make('niyom_home_what_we_do', __('Home What We Do', 'niyom'))
        ->set_description(__('Section intro and service areas (text + image). Cards stack while scrolling on desktop; accordion on mobile.', 'niyom'))
        ->set_icon('excerpt-view')
        ->set_keywords([__('services', 'niyom'), __('what we do', 'niyom'), __('areas', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'wwd_eyebrow', __('Label', 'niyom'))
                ->set_default_value('What We Do')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically: ( WHAT WE DO ).', 'niyom')),

            Field::make('textarea', 'wwd_title', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value("Three connected areas.\nOne scientific purpose.")
                ->set_help_text(__('Press Enter for a new line (desktop keeps the line breaks).', 'niyom')),

            Field::make('rich_text', 'wwd_intro', __('Intro Text', 'niyom'))
                ->set_default_value('NIYOM’s public expertise catalogue should reflect only methods, tools and domains that can be supported by verified experience and evidence.')
                ->set_help_text(__('Shown to the right of the heading. Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('rich_text', 'wwd_intro_mobile', __('Intro Text on Mobile (optional)', 'niyom'))
                ->set_default_value('Three connected areas of practice, each held to the same standard of rigor, transparency and reproducibility.')
                ->set_help_text(__('Used up to 768px wide. If empty, the intro text above is used.', 'niyom')),

            Field::make('complex', 'wwd_items', __('Areas', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Areas', 'niyom'),
                    'singular_name' => __('Area', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'number', __('Number', 'niyom'))
                        ->set_help_text(__('e.g. 01', 'niyom')),
                    Field::make('text', 'label', __('Label', 'niyom'))
                        ->set_help_text(__('Small teal label next to the number, e.g. Computational Biosciences.', 'niyom')),
                    Field::make('textarea', 'title', __('Title', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Press Enter for a new line.', 'niyom')),
                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links, lists and images all work. On mobile it shows when the item is opened.', 'niyom')),
                    Field::make('text', 'link_text', __('Link Text', 'niyom')),
                    Field::make('text', 'link_url', __('Link URL', 'niyom')),
                    Field::make('checkbox', 'link_new_tab', __('Open in new tab', 'niyom')),
                    Field::make('image', 'image', __('Image', 'niyom'))
                        ->set_value_type('url')
                        ->set_help_text(__('About 1800 × 1056 px (shown 893 × 524 on desktop).', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'          => 'home-wwd-item-1',
                        '_type'        => '_',
                        'number'       => '01',
                        'label'        => 'Computational Biosciences',
                        'title'        => 'Turning complex data into scientific understanding',
                        'text'         => 'Analysis planning, data-readiness review and documented computational workflows that support clear scientific interpretation.',
                        'link_text'    => 'Know more',
                        'link_url'     => '/services/computational-biosciences/',
                        'link_new_tab' => false,
                        'image'        => $img . 'wwd-computational-biosciences.jpg',
                    ],
                    [
                        '_id'          => 'home-wwd-item-2',
                        '_type'        => '_',
                        'number'       => '02',
                        'label'        => 'Research Support',
                        'title'        => 'Strengthening research through every stage',
                        'text'         => 'Modern, responsive, and user-friendly websites designed to engage visitors and drive conversions.',
                        'link_text'    => 'Know more',
                        'link_url'     => '/services/research-support/',
                        'link_new_tab' => false,
                        'image'        => $img . 'wwd-research-support.jpg',
                    ],
                    [
                        '_id'          => 'home-wwd-item-3',
                        '_type'        => '_',
                        'number'       => '03',
                        'label'        => 'Scientific Engagement',
                        'title'        => 'Connecting knowledge, people, and possibilities',
                        'text'         => 'Seminars, knowledge-sharing and institutional partnerships — including curriculum mapping and computational integration for academic programs.',
                        'link_text'    => 'Know more',
                        'link_url'     => '/services/scientific-engagement/',
                        'link_new_tab' => false,
                        'image'        => $img . 'wwd-scientific-engagement.jpg',
                    ],
                ])
                ->set_help_text(__('Each tab is one area. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/what-we-do', null, $fields);
        });
});

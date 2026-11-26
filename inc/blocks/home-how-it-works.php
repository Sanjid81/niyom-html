<?php
/**
 * Home "How NIYOM works" block (Carbon Fields).
 *
 * Figma: Home — Desktop › How NIYOM works (182:568), Home — Mobile › How We Work (276:1847).
 * Intro (eyebrow, heading, button) and a row of step cards: number, line icon,
 * title, text. Step numbers come from the order (01, 02 …) unless a number
 * is typed in.
 *
 * Markup: components/Home/how-it-works.php
 * Styles: src/styles/components/home/how-it-works.scss
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {

    $icons = NIYOM_URI . '/assets/icons/';

    Block::make('niyom_home_how_it_works', __('Home How It Works', 'niyom'))
        ->set_description(__('Intro with a button and numbered step cards (icon, title, text).', 'niyom'))
        ->set_icon('editor-ol')
        ->set_keywords([__('steps', 'niyom'), __('process', 'niyom'), __('how it works', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'steps_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('How NIYOM works')
                ->set_help_text(__('Brackets are added automatically: "( HOW NIYOM WORKS )".', 'niyom')),

            Field::make('textarea', 'steps_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('A clear path from research questions to scientific outcomes.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('text', 'steps_button_text', __('Button Text', 'niyom'))
                ->set_default_value('Start a Project Inquiry')
                ->set_width(40),
            Field::make('text', 'steps_button_link', __('Button Link', 'niyom'))
                ->set_default_value('/work-with-us/')
                ->set_width(40),
            Field::make('checkbox', 'steps_button_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Steps ----------
            Field::make('separator', 'steps_sep_items', __('Steps', 'niyom')),

            Field::make('complex', 'steps_items', __('Steps', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Steps', 'niyom'),
                    'singular_name' => __('Step', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'number', __('Number (optional)', 'niyom'))
                        ->set_help_text(__('Leave empty to number steps automatically: 01, 02, 03 …', 'niyom')),

                    Field::make('image', 'icon', __('Icon', 'niyom'))
                        ->set_value_type('url')
                        ->set_help_text(__('Line icon, 80 × 80. SVG recommended: it is shown in Research Teal.', 'niyom')),

                    Field::make('text', 'title', __('Title', 'niyom')),

                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'    => 'home-step-1',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-share-question.svg',
                        'title'  => 'Share the research question',
                        'text'   => 'Tell us about your objective, research stage, data type and the decision or deliverable you need.',
                    ],
                    [
                        '_id'    => 'home-step-2',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-review-fit.svg',
                        'title'  => 'Review fit and feasibility',
                        'text'   => 'We clarify requirements, limitations, timeline, data readiness and any ethical or governance considerations.',
                    ],
                    [
                        '_id'    => 'home-step-3',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-agree-scope.svg',
                        'title'  => 'Agree scope and deliverables',
                        'text'   => 'Define the work plan, responsibilities, milestones, outputs and communication expectations.',
                    ],
                    [
                        '_id'    => 'home-step-4',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-deliver-handover.svg',
                        'title'  => 'Deliver, explain and hand over',
                        'text'   => 'Receive documented outputs, interpretation support and an agreed handover for continued use.',
                    ],
                ])
                ->set_help_text(__('Each tab is one step. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/how-it-works', null, $fields);
        });
});

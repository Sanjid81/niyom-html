<?php
/**
 * Work With Us Hero block (Carbon Fields).
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {

    $category = ['niyom-home', __('Niyom — Home', 'niyom'), 'admin-home'];

    Block::make('niyom_work_with_us_hero', __('Work With Us Hero', 'niyom'))
        ->set_description(__('Two-column section: large heading on the left, description text on the right.', 'niyom'))
        ->set_icon('editor-textcolor')
        ->set_keywords([__('work', 'niyom'), __('hero', 'niyom'), __('cta', 'niyom')])
        ->set_category(...$category)
        ->set_mode('both')
        ->add_fields([
            Field::make('separator', 'wwu_sep_content', __('Content', 'niyom')),

            Field::make('text', 'wwu_tag', __('Small Tag', 'niyom'))
                ->set_default_value('( WORK WITH US )')
                ->set_help_text(__('Small uppercase text above the title.', 'niyom')),

            Field::make('textarea', 'wwu_title', __('Title', 'niyom'))
                ->set_rows(3)
                ->set_default_value("Every project starts with a\nquestion.")
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('textarea', 'wwu_description', __('Description', 'niyom'))
                ->set_rows(4)
                ->set_default_value('Tell us what you are trying to find out. We will help you decide whether — and how — NIYOM can support it, before any commitment or data changes hands.'),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/work_with_us/work_with_us', null, $fields);
        });


// -----------------------------------------------------------------
    // Work With Us - FAQ Accordion
    // -----------------------------------------------------------------
    Block::make('niyom_work_faq', __('Work FAQ Accordion', 'niyom'))
        ->set_description(__('Two-column section: heading on the left, accordion on the right.', 'niyom'))
        ->set_icon('editor-help')
        ->set_keywords([__('faq', 'niyom'), __('accordion', 'niyom'), __('questions', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            Field::make('separator', 'faq_sep_content', __('Heading', 'niyom')),

            Field::make('text', 'faq_tag', __('Small Tag', 'niyom'))
                ->set_default_value('( FAQS )')
                ->set_help_text(__('Small uppercase text above the title.', 'niyom')),

            Field::make('textarea', 'faq_title', __('Title', 'niyom'))
                ->set_rows(3)
                ->set_default_value("Before you get in\ntouch.")
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('separator', 'faq_sep_items', __('Accordion Items', 'niyom')),

            Field::make('complex', 'faq_items', __('Questions', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->setup_labels([
                    'plural_name'   => __('Questions', 'niyom'),
                    'singular_name' => __('Question', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'question', __('Question', 'niyom'))
                        ->set_required(true),
                    Field::make('rich_text', 'answer', __('Answer', 'niyom'))
                        ->set_required(true),
                ])
                ->set_header_template('<%- question ? question : "Question" %>')
                ->set_default_value([
                    [
                        '_id'      => 'faq-1',
                        '_type'    => '_',
                        'question' => 'What happens after I submit the form?',
                        'answer'   => 'We review your enquiry and reply to arrange a short scoping conversation, or to let you know honestly if NIYOM is not the right fit.',
                    ],
                    [
                        '_id'      => 'faq-2',
                        '_type'    => '_',
                        'question' => 'Is there a cost for the scoping conversation?',
                        'answer'   => 'No, the initial scoping conversation is completely free.',
                    ],
                    [
                        '_id'      => 'faq-3',
                        '_type'    => '_',
                        'question' => 'Can I enquire on behalf of a team or institution?',
                        'answer'   => 'Yes, absolutely. We welcome enquiries from individuals, teams, and institutions.',
                    ],
                    [
                        '_id'      => 'faq-4',
                        '_type'    => '_',
                        'question' => 'Do you sign confidentiality agreements?',
                        'answer'   => 'Yes, we are happy to sign NDAs before discussing sensitive project details.',
                    ],
                    [
                        '_id'      => 'faq-5',
                        '_type'    => '_',
                        'question' => 'Do you work with researchers outside Bangladesh?',
                        'answer'   => 'Yes, we collaborate with researchers globally.',
                    ],
                ])
                ->set_help_text(__('Add your frequently asked questions here.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/work_with_us/work_with_faq', null, $fields);
        });



});


    
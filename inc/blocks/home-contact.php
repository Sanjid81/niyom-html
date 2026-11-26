<?php
/**
 * Home Contact block (Carbon Fields) — "Warm gallery — Research contact".
 *
 * Figma: Home — Desktop (270:1606), Home — Mobile (278:2702).
 * Left: eyebrow, heading, text, portrait image + contact blocks.
 * Right: enquiry form from a Contact Form 7 shortcode.
 * The section has id="contact" so menu links to /#contact land here.
 *
 * Markup: components/Home/contact.php
 * Styles: src/styles/components/home/contact.scss
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_home_contact', __('Home Contact', 'niyom'))
        ->set_description(__('Research invitation with contact details and the enquiry form.', 'niyom'))
        ->set_icon('email-alt')
        ->set_keywords([__('contact', 'niyom'), __('form', 'niyom'), __('enquiry', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            // ---------- Invitation ----------
            Field::make('text', 'contact_eyebrow', __('Eyebrow', 'niyom'))
                ->set_default_value('A conversation starts here')
                ->set_help_text(__('Shown as "( A CONVERSATION STARTS HERE )". Leave empty to hide.', 'niyom')),
            Field::make('textarea', 'contact_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value("Every discovery begins\nwith a question.")
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),
            Field::make('rich_text', 'contact_text', __('Text', 'niyom'))
                ->set_default_value('Tell us where your research is heading, and what stands in the way. Together, we’ll explore the right support for your next step.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('image', 'contact_image', __('Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/home/contact-study-in-light.jpg')
                ->set_help_text(__('Portrait image, about 560 × 620 px. Hidden on mobile (as in the design).', 'niyom')),

            // ---------- Contact blocks ----------
            Field::make('complex', 'contact_blocks', __('Contact Blocks', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Contact Blocks', 'niyom'),
                    'singular_name' => __('Contact Block', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'kicker', __('Label', 'niyom'))
                        ->set_help_text(__('Small uppercase label, e.g. "Office".', 'niyom')),
                    Field::make('rich_text', 'details', __('Details', 'niyom'))
                        ->set_help_text(__('Address, email, phone… Line breaks and links work (e.g. mailto: / tel: links).', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'     => 'home-contact-office',
                        '_type'   => '_',
                        'kicker'  => 'Office',
                        'details' => '<p>270, Tejgaon Industrial<br>Area, Dhaka</p>',
                    ],
                    [
                        '_id'     => 'home-contact-info',
                        '_type'   => '_',
                        'kicker'  => 'Contact info',
                        'details' => '<p><a href="mailto:info@niyom11.com">info@niyom11.com</a><br><a href="tel:+8801930107368">+8801 93 010 73 68</a></p>',
                    ],
                ]),

            // ---------- Form ----------
            Field::make('separator', 'contact_sep_form', __('Enquiry Form', 'niyom')),
            Field::make('text', 'contact_form', __('Form Shortcode', 'niyom'))
                ->set_default_value('[contact-form-7 title="Home – Research Enquiry"]')
                ->set_help_text(__('Contact Form 7 shortcode (Contact › Contact Forms). Leave empty to hide the form panel.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/contact', null, $fields);
        });
});

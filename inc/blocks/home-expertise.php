<?php
/**
 * Home Expertise block (Carbon Fields).
 *
 * Figma: Home — Desktop › Expertise (182:541), Home — Mobile › Expertise (276:1839).
 * Intro (eyebrow, heading, text, button) on Deep Ink with a faint background
 * image, plus a card slider filled from the "Expertise" post type
 * (title, excerpt, featured image, Expertise Tags as chips).
 *
 * Markup: components/Home/expertise.php
 * Styles: src/styles/components/home/expertise.scss
 * Slider: src/scripts/components/home-expertise.js
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_home_expertise', __('Home Expertise', 'niyom'))
        ->set_description(__('Dark section with intro text and a slider of Expertise posts.', 'niyom'))
        ->set_icon('welcome-learn-more')
        ->set_keywords([__('expertise', 'niyom'), __('slider', 'niyom'), __('cards', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            // ---------- Background ----------
            Field::make('image', 'expertise_bg', __('Background Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/home/expertise-bg.jpg')
                ->set_help_text(__('Shown very faintly (7%) over the Deep Ink colour. Wide image, about 2000 × 1150 px.', 'niyom')),

            // ---------- Intro ----------
            Field::make('separator', 'expertise_sep_intro', __('Intro', 'niyom')),

            Field::make('text', 'expertise_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Expertise')
                ->set_help_text(__('Brackets are added automatically: "( EXPERTISE )".', 'niyom')),

            Field::make('textarea', 'expertise_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Methods matter. So does knowing their limits.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('rich_text', 'expertise_text', __('Text', 'niyom'))
                ->set_default_value('A transparent view of the methods, tools and domains we work with — including where each approach applies, and where it does not.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('text', 'expertise_button_text', __('Button Text', 'niyom'))
                ->set_default_value('Explore Our Expertise')
                ->set_width(40),
            Field::make('text', 'expertise_button_link', __('Button Link', 'niyom'))
                ->set_default_value('/expertise/')
                ->set_width(40),
            Field::make('checkbox', 'expertise_button_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Cards ----------
            Field::make('separator', 'expertise_sep_cards', __('Expertise Cards', 'niyom')),

            Field::make('html', 'expertise_cards_info')
                ->set_html('<p>' . esc_html__('Cards come from Expertise posts (title, excerpt, featured image and Expertise Tags). With no published posts the slider is hidden.', 'niyom') . '</p>'),

            Field::make('text', 'expertise_count', __('Number of cards to show', 'niyom'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '1')
                ->set_attribute('max', '24')
                ->set_default_value('6')
                ->set_width(50),

            Field::make('select', 'expertise_order', __('Order', 'niyom'))
                ->set_options([
                    'menu_order' => __('Manual order (Page Attributes › Order)', 'niyom'),
                    'date'       => __('Newest first', 'niyom'),
                ])
                ->set_default_value('menu_order')
                ->set_width(50),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/expertise', null, $fields);
        });
});

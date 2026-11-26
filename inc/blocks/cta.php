<?php
/**
 * CTA / Newsletter Section.
 */
defined('ABSPATH') || exit;
use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {
    $category = ['niyom-common', __('Niyom — Common', 'niyom'), 'admin-generic'];

    Block::make('niyom_cta_newsletter', __('CTA / Newsletter Section', 'niyom'))
        ->set_category(...$category)->set_mode('both')
        ->add_fields([
            Field::make('text', 'cta_tag', 'Small Tag')->set_default_value('( REQUEST RESEARCH SUPPORT )'),
            Field::make('textarea', 'cta_title', 'Title')->set_default_value("Interested in working together on a\nstudy, tool or training?"),
            Field::make('text', 'cta_disclaimer', 'Disclaimer')->set_default_value('One considered email a month. No noise, unsubscribe anytime.'),
            Field::make('image', 'cta_bg_image', 'Background Image')->set_value_type('url')->set_default_value(NIYOM_URI . '/assets/images/home/cta-bg.jpg'),
            Field::make('text', 'cta_button_text', 'Button Text')->set_default_value('SUBSCRIBE'),
        ])
        ->set_render_callback(function ($fields) { get_template_part('components/common/cta', null, $fields); });
});
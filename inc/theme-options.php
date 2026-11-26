<?php
/**
 * Theme Options (Carbon Fields).
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {

    // Main page: global scripts.
    $options = Container::make('theme_options', __('Theme Options', 'niyom'))
        ->add_fields([
            Field::make('header_scripts', 'crb_header_script', __('Header Scripts', 'niyom')),
            Field::make('footer_scripts', 'crb_footer_script', __('Footer Scripts', 'niyom')),
        ]);

    // Header (used by header.php).
    Container::make('theme_options', __('Header Options', 'niyom'))
        ->set_page_parent($options)
        ->add_fields([
            Field::make('image', 'site_logo', __('Site Logo', 'niyom')),
            Field::make('text', 'header_button_text', __('Button Text', 'niyom'))->set_width(50),
            Field::make('text', 'header_button_link', __('Button Link', 'niyom'))->set_width(50),
        ]);

    // Footer (used by footer.php).
    Container::make('theme_options', __('Footer Options', 'niyom'))
        ->set_page_parent($options)
        ->add_fields([
            Field::make('image', 'footer_logo', __('Footer Logo', 'niyom')),
            Field::make('textarea', 'footer_tagline', __('Tagline', 'niyom'))->set_rows(2),
            Field::make('text', 'footer_address', __('Address', 'niyom')),
            Field::make('text', 'footer_email', __('Email', 'niyom')),
            Field::make('text', 'footer_phone', __('Phone', 'niyom')),
            Field::make('complex', 'footer_social', __('Social Links', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->add_fields([
                    Field::make('text', 'label', __('Label', 'niyom'))->set_width(50),
                    Field::make('text', 'url', __('URL', 'niyom'))->set_width(50),
                ])
                ->set_header_template('<%- label %>'),
            Field::make('text', 'footer_copyright', __('Copyright Text', 'niyom'))
                ->set_help_text(__('Use {year} for the current year, e.g. © {year} Niyom. All rights reserved.', 'niyom')),
        ]);
});

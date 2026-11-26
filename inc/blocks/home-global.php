<?php
/**
 * Home › Global Perspective block (Carbon Fields).
 *
 * Figma: Home — Desktop › Global (213:3073), Home — Mobile › Global (290:4840).
 * Markup: components/Home/global.php · Styles: src/styles/components/home/global.scss
 *
 * Left: eyebrow, heading, text, button. Right: globe image, or an optional
 * looping video (the image is then its poster and the reduced-motion fallback).
 * Empty fields are not shown.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_home_global', __('Home Global Perspective', 'niyom'))
        ->set_description(__('Text with a button on the left, globe image or looping video on the right.', 'niyom'))
        ->set_icon('admin-site-alt3')
        ->set_keywords([__('global', 'niyom'), __('globe', 'niyom'), __('collaboration', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([

            // ---------- Text (left column) ----------
            Field::make('text', 'global_eyebrow', __('Eyebrow', 'niyom'))
                ->set_default_value('Global perspective')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'global_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value("Advancing science.\nBeyond borders.")
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('rich_text', 'global_text', __('Text', 'niyom'))
                ->set_default_value('NIYOM brings together computational expertise, research guidance, and scientific engagement with an international perspective, creating opportunities for knowledge exchange and collaboration.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('text', 'global_button_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('Discuss a Collaboration'),
            Field::make('text', 'global_button_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/work-with-us/'),
            Field::make('checkbox', 'global_button_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Media (right column) ----------
            Field::make('separator', 'global_sep_media', __('Media', 'niyom')),

            Field::make('image', 'global_image', __('Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/home/global-globe.jpg')
                ->set_help_text(__('About 1244 × 738 px on a white background. Also the video poster.', 'niyom')),

            Field::make('file', 'global_video', __('Video (optional)', 'niyom'))
                ->set_type(['video'])
                ->set_value_type('url')
                ->set_help_text(__('MP4, same shape as the image. Plays muted on a loop. Visitors who prefer reduced motion see the image instead.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/global', null, $fields);
        });
});

<?php
/**
 * Home › About NIYOM block (Carbon Fields).
 *
 * Figma: Home — Desktop › Content (182:505), Home — Mobile › About NIYOM (276:1809).
 * Markup: components/Home/about.php · Styles: src/styles/components/home/about.scss
 *
 * Any field left empty is not shown on the page.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_home_about', __('Home About', 'niyom'))
        ->set_description(__('Centred intro: label, large statement and button over a faint background image.', 'niyom'))
        ->set_icon('info-outline')
        ->set_keywords([__('about', 'niyom'), __('intro', 'niyom'), __('statement', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            Field::make('image', 'about_bg_image', __('Background Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/home/about-bg.jpg')
                ->set_help_text(__('Shown very faintly (6%) behind the text and faded to white at the bottom. About 2000 × 1333 px. Hidden on mobile (Figma).', 'niyom')),

            Field::make('text', 'about_eyebrow', __('Label', 'niyom'))
                ->set_default_value('About NIYOM')
                ->set_help_text(__('Small label above the text. Brackets are added automatically: ( ABOUT NIYOM ).', 'niyom')),

            Field::make('textarea', 'about_text', __('Statement', 'niyom'))
                ->set_rows(4)
                ->set_default_value('NIYOM connects scientific expertise with researchers worldwide through computational biosciences, research support, and meaningful collaboration.')
                ->set_help_text(__('Large centred text. Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('textarea', 'about_text_mobile', __('Statement on Mobile (optional)', 'niyom'))
                ->set_rows(4)
                ->set_default_value('Rooted in Bangladesh with an international outlook, NIYOM brings computational biosciences, research support and scientific engagement together.')
                ->set_help_text(__('Used up to 768px wide. If empty, the statement above is used.', 'niyom')),

            Field::make('text', 'about_button_text', __('Button Text', 'niyom'))
                ->set_default_value('Discover NIYOM'),
            Field::make('text', 'about_button_link', __('Button Link', 'niyom'))
                ->set_default_value('/about-niyom/'),
            Field::make('checkbox', 'about_button_new_tab', __('Open in new tab', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/about', null, $fields);
        });
});

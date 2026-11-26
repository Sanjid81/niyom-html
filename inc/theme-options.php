<?php
/**
 * Theme Options (Carbon Fields).
 *
 * Every field has a default from the Figma design. Defaults are used until
 * the page is saved; after that, an empty field hides that part of the site.
 * Menus are managed in Appearance › Menus (see inc/template-tags.php).
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {

    $logo_mark = NIYOM_URI . '/assets/images/brand/logo-mark.png';
    $icons     = NIYOM_URI . '/assets/icons/social/';

    // Logo fields, same for header and footer.
    $logo_fields = function ($prefix) use ($logo_mark) {
        return [
            Field::make('image', $prefix . '_logo', __('Logo Image', 'niyom'))
                ->set_value_type('url')
                ->set_width(50)
                ->set_default_value($logo_mark)
                ->set_help_text(__('PNG, JPG or SVG. Shown beside the logo text. Leave empty to show only the text.', 'niyom')),
            Field::make('text', $prefix . '_logo_text', __('Logo Text', 'niyom'))
                ->set_width(50)
                ->set_default_value('NIYOM')
                ->set_help_text(__('Leave empty if your logo image already includes the name.', 'niyom')),
        ];
    };

    // Main page: global scripts.
    $options = Container::make('theme_options', __('Theme Options', 'niyom'))
        ->set_icon('dashicons-admin-generic')
        ->add_fields([
            Field::make('header_scripts', 'crb_header_script', __('Header Scripts', 'niyom')),
            Field::make('footer_scripts', 'crb_footer_script', __('Footer Scripts', 'niyom')),
        ]);

    // Header (header.php).
    Container::make('theme_options', __('Header', 'niyom'))
        ->set_page_parent($options)
        ->add_fields(array_merge($logo_fields('niyom_header'), [
            Field::make('separator', 'niyom_header_sep_button', __('Button', 'niyom')),
            Field::make('html', 'niyom_header_menu_note')
                ->set_html('<p>' . esc_html__('Menu links are set in Appearance › Menus: "Header Menu (desktop)" and "Header Menu (mobile)".', 'niyom') . '</p>'),
            Field::make('text', 'niyom_header_btn_text', __('Button Text', 'niyom'))
                ->set_default_value('Work with us'),
            Field::make('text', 'niyom_header_btn_link', __('Button Link', 'niyom'))
                ->set_default_value('/work-with-us/')
                ->set_help_text(__('The button shows only when text and link are both filled.', 'niyom')),
            Field::make('checkbox', 'niyom_header_btn_new_tab', __('Open in new tab', 'niyom')),
        ]));

    // Footer (footer.php). Column links come from Appearance › Menus.
    Container::make('theme_options', __('Footer', 'niyom'))
        ->set_page_parent($options)
        ->add_tab(__('Logo', 'niyom'), $logo_fields('niyom_footer'))
        ->add_tab(__('Link Columns', 'niyom'), [
            Field::make('html', 'niyom_footer_menu_note')
                ->set_html('<p>' . esc_html__('Links in each column come from Appearance › Menus: "Footer Column 1/2/3". A column with no links is hidden. On mobile each column is an accordion; the first starts open.', 'niyom') . '</p>'),
            Field::make('text', 'niyom_footer_col1_title', __('Column 1 Title', 'niyom'))
                ->set_width(33)
                ->set_default_value('Services'),
            Field::make('text', 'niyom_footer_col2_title', __('Column 2 Title', 'niyom'))
                ->set_width(33)
                ->set_default_value('Quick Links'),
            Field::make('text', 'niyom_footer_col3_title', __('Column 3 Title', 'niyom'))
                ->set_width(33)
                ->set_default_value('Legal'),
        ])
        ->add_tab(__('Bottom Bar', 'niyom'), [
            Field::make('text', 'niyom_footer_copyright', __('Copyright', 'niyom'))
                ->set_default_value('© {year} NIYOM. All rights reserved.')
                ->set_help_text(__('{year} becomes the current year.', 'niyom')),
            Field::make('text', 'niyom_footer_credit_text', __('Credit Text', 'niyom'))
                ->set_width(33)
                ->set_default_value('Site by'),
            Field::make('text', 'niyom_footer_credit_label', __('Credit Link Label', 'niyom'))
                ->set_width(33)
                ->set_default_value('Notionhive'),
            Field::make('text', 'niyom_footer_credit_url', __('Credit Link URL', 'niyom'))
                ->set_width(33)
                ->set_default_value('https://notionhive.com/'),
            Field::make('complex', 'niyom_footer_social_links', __('Social Links', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->add_fields([
                    Field::make('image', 'icon', __('Icon', 'niyom'))
                        ->set_value_type('url')
                        ->set_width(30)
                        ->set_help_text(__('SVG (recommended, takes the footer colours) or PNG, about 18 × 18 px.', 'niyom')),
                    Field::make('text', 'label', __('Name', 'niyom'))
                        ->set_width(30)
                        ->set_help_text(__('E.g. LinkedIn. Read by screen readers.', 'niyom')),
                    Field::make('text', 'url', __('URL', 'niyom'))
                        ->set_width(40),
                    Field::make('checkbox', 'new_tab', __('Open in new tab', 'niyom'))
                        ->set_default_value(true),
                ])
                ->set_header_template('<%- label ? label : "Link " + ($_index + 1) %>')
                ->set_default_value([
                    ['icon' => $icons . 'facebook.svg', 'label' => 'Facebook', 'url' => 'https://www.facebook.com/', 'new_tab' => true],
                    ['icon' => $icons . 'linkedin.svg', 'label' => 'LinkedIn', 'url' => 'https://www.linkedin.com/', 'new_tab' => true],
                    ['icon' => $icons . 'x.svg', 'label' => 'X', 'url' => 'https://x.com/', 'new_tab' => true],
                    ['icon' => $icons . 'youtube.svg', 'label' => 'YouTube', 'url' => 'https://www.youtube.com/', 'new_tab' => true],
                ])
                ->set_help_text(__('A link shows when it has a URL and an icon or name.', 'niyom')),
        ]);
});

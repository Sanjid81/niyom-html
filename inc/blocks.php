<?php
/**
 * Gutenberg blocks (Carbon Fields).
 *
 * Pattern for new blocks:
 *   1. Block::make() here with its fields.
 *   2. Markup in components/<name>.php, receiving fields as $args.
 *   3. Styles in src/styles/components/_<name>.scss, imported in index.scss.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {

    // Banner.
    Block::make(__('Banner', 'niyom'))
        ->add_fields([
            Field::make('image', 'banner_image', __('Background Image', 'niyom')),
            Field::make('text', 'banner_title', __('Title', 'niyom')),
            Field::make('textarea', 'banner_text', __('Text', 'niyom'))->set_rows(3),
            Field::make('text', 'banner_button_text', __('Button Text', 'niyom'))->set_width(50),
            Field::make('text', 'banner_button_link', __('Button Link', 'niyom'))->set_width(50),
        ])
        ->set_icon('cover-image')
        ->set_category('niyom', __('Niyom', 'niyom'), 'layout')
        ->set_render_callback(function ($fields) {
            get_template_part('components/banner', null, $fields);
        });
});

<?php
/**
 * Insights Page Blocks (Carbon Fields).
 */
defined('ABSPATH') || exit;
use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {
    $category = ['niyom-insights', __('Niyom — Insights', 'niyom'), 'admin-post'];

    // 1. Hero
    Block::make('niyom_insights_hero', __('Insights Hero', 'niyom'))
        ->set_category(...$category)->set_mode('both')
        ->add_fields([
            Field::make('text', 'ih_tag', 'Small Tag')->set_default_value('( INSIGHTS )'),
            Field::make('textarea', 'ih_title', 'Title')->set_default_value("Ideas, insights and\nscientific perspectives."),
            Field::make('textarea', 'ih_description', 'Description')->set_default_value('Notes on research practice, computational methods and responsible interpretation — written by the people doing the work.'),
        ])
        ->set_render_callback(function ($fields) { get_template_part('components/insights/hero', null, $fields); });

    // 2. Featured
    Block::make('niyom_insights_featured', __('Insights Featured Post', 'niyom'))
        ->set_category(...$category)->set_mode('both')
        ->add_fields([
            Field::make('image', 'if_image', 'Image')->set_value_type('url')->set_default_value(NIYOM_URI . '/assets/images/home/featured-insight.jpg'),
            Field::make('text', 'if_category', 'Category')->set_default_value('SCIENTIFIC EXPLAINER'),
            Field::make('text', 'if_date', 'Date')->set_default_value('20 Jan, 2026'),
            Field::make('text', 'if_title', 'Title')->set_default_value('Why reproducibility starts before the first line of code'),
            Field::make('textarea', 'if_text', 'Excerpt')->set_default_value('Reproducible analysis is often treated as a coding problem...'),
            Field::make('text', 'if_btn_text', 'Button Text')->set_default_value('READ ARTICLE'),
            Field::make('text', 'if_btn_link', 'Button Link')->set_default_value('#'),
        ])
        ->set_render_callback(function ($fields) { get_template_part('components/insights/featured', null, $fields); });

    // 3. Archive
    Block::make('niyom_insights_archive', __('Insights Archive Grid', 'niyom'))
        ->set_category(...$category)->set_mode('both')
        ->add_fields([
            Field::make('text', 'ia_btn_text', 'Load More Text')->set_default_value('LOAD MORE INSIGHTS'),
        ])
        ->set_render_callback(function ($fields) { get_template_part('components/insights/archive', null, $fields); });
});

<?php
/**
 * Custom post types used on the Home page and their own pages.
 *
 *   expertise  — Expertise cards (image, title, excerpt, tags from "Expertise Tags").
 *   insights   — Insights articles (image, title, excerpt, date, "Insight Categories").
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

add_action('init', function () {

    register_post_type('expertise', [
        'labels' => [
            'name'          => __('Expertise', 'niyom'),
            'singular_name' => __('Expertise', 'niyom'),
            'add_new_item'  => __('Add Expertise', 'niyom'),
            'edit_item'     => __('Edit Expertise', 'niyom'),
            'all_items'     => __('All Expertise', 'niyom'),
        ],
        'public'       => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-welcome-learn-more',
        'menu_position' => 21,
        'show_in_rest' => true,
        'supports'     => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
        'rewrite'      => ['slug' => 'expertise-area'],
    ]);

    register_taxonomy('expertise_tag', 'expertise', [
        'labels'            => [
            'name'          => __('Expertise Tags', 'niyom'),
            'singular_name' => __('Expertise Tag', 'niyom'),
        ],
        'hierarchical'      => false,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'expertise-tag'],
    ]);

    register_post_type('insights', [
        'labels' => [
            'name'          => __('Insights', 'niyom'),
            'singular_name' => __('Insight', 'niyom'),
            'add_new_item'  => __('Add Insight', 'niyom'),
            'edit_item'     => __('Edit Insight', 'niyom'),
            'all_items'     => __('All Insights', 'niyom'),
        ],
        'public'        => true,
        'has_archive'   => false,
        'menu_icon'     => 'dashicons-lightbulb',
        'menu_position' => 22,
        'show_in_rest'  => true,
        'supports'      => ['title', 'editor', 'excerpt', 'thumbnail', 'author'],
        'rewrite'       => ['slug' => 'insight'],
    ]);

    register_taxonomy('insight_category', 'insights', [
        'labels'            => [
            'name'          => __('Insight Categories', 'niyom'),
            'singular_name' => __('Insight Category', 'niyom'),
        ],
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'insight-category'],
    ]);
});

// Flush rewrite rules once after these post types are added or changed.
add_action('init', function () {
    if (get_option('niyom_post_types_version') !== '1') {
        flush_rewrite_rules(false);
        update_option('niyom_post_types_version', '1');
    }
}, 99);

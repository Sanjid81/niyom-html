<?php
/**
 * Insights: article fields, tags, block styles, settings and the
 * "load more" REST endpoint.
 *
 *   Post meta    "Article details" box on every Insight (lead text, image
 *                caption, related service box, references, author).
 *   Taxonomy     insight_tag  — "Insight Tags", shown as pills under the article.
 *   Block styles core/list "Checklist", core/group "Key point box".
 *   Settings     Insights › Article Settings — labels, "Continue reading"
 *                and the teal CTA shown on every article.
 *   REST         GET /wp-json/niyom/v1/insights — card HTML for the
 *                listing page tabs and "Load more" button.
 *
 * Templates: single-insights.php + components/Insights/single-*.php.
 * Listing page blocks: inc/blocks/insights-block.php.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// =====================================================================
// Tags + block styles
// =====================================================================

add_action('init', function () {

    // Own tag list for Insights (kept apart from blog tags). Tags are shown
    // as plain pills, so there are no tag archive pages.
    register_taxonomy('insight_tag', 'insights', [
        'labels'             => [
            'name'          => __('Insight Tags', 'niyom'),
            'singular_name' => __('Insight Tag', 'niyom'),
            'add_new_item'  => __('Add Insight Tag', 'niyom'),
            'search_items'  => __('Search Insight Tags', 'niyom'),
        ],
        'hierarchical'       => false,
        'public'             => false,
        'show_ui'            => true,
        'show_admin_column'  => true,
        'show_in_rest'       => true,
        'show_tagcloud'      => false,
        'rewrite'            => false,
        'query_var'          => false,
    ]);

    // Article body styles (Figma "Insight Article": checklist + key point box).
    if (function_exists('register_block_style')) {
        register_block_style('core/list', [
            'name'  => 'checklist',
            'label' => __('Checklist', 'niyom'),
        ]);
        register_block_style('core/group', [
            'name'  => 'key-point',
            'label' => __('Key point box', 'niyom'),
        ]);
    }
});

// Flush rewrite rules once after the Insights template code is added.
add_action('init', function () {
    if (get_option('niyom_insights_version') !== '1') {
        flush_rewrite_rules(false);
        update_option('niyom_insights_version', '1');
    }
}, 99);

// =====================================================================
// Fields: "Article details" (per post) + "Article Settings" (all posts)
// =====================================================================

add_action('carbon_fields_register_fields', function () {

    Container::make('post_meta', 'niyom_insight_details', __('Article details', 'niyom'))
        ->where('post_type', '=', 'insights')
        ->add_tab(__('Intro', 'niyom'), [
            Field::make('textarea', 'insight_lead', __('Lead text', 'niyom'))
                ->set_rows(3)
                ->set_help_text(__('Large text under the title. Empty = the excerpt is used.', 'niyom')),
            Field::make('text', 'insight_image_caption', __('Image caption', 'niyom'))
                ->set_help_text(__('Small text under the featured image, e.g. the image credit. Empty = no caption.', 'niyom')),
        ])
        ->add_tab(__('Related service', 'niyom'), [
            Field::make('html', 'insight_service_note')
                ->set_html('<p>' . esc_html__('Dark box at the right of the article. Leave the title empty to hide the box.', 'niyom') . '</p>'),
            Field::make('text', 'insight_service_label', __('Label', 'niyom'))
                ->set_default_value('Related service'),
            Field::make('textarea', 'insight_service_title', __('Title', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Study and analysis planning'),
            Field::make('textarea', 'insight_service_text', __('Text', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Plan your analysis alongside your study design.'),
            Field::make('text', 'insight_service_link_text', __('Link text', 'niyom'))
                ->set_width(40)
                ->set_default_value('Learn more'),
            Field::make('text', 'insight_service_link', __('Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/research-support/'),
            Field::make('checkbox', 'insight_service_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),
        ])
        ->add_tab(__('References', 'niyom'), [
            Field::make('text', 'insight_references_heading', __('Heading', 'niyom'))
                ->set_default_value('References')
                ->set_help_text(__('Also added to "On this page".', 'niyom')),
            Field::make('complex', 'insight_references', __('References', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels(['plural_name' => __('References', 'niyom'), 'singular_name' => __('Reference', 'niyom')])
                ->add_fields([
                    Field::make('textarea', 'text', __('Reference', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Author(s). Title. Journal or publisher. Year. DOI or URL. Links (<a href="…">) are allowed.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_help_text(__('Numbered [1], [2] … in this order. No references = no section.', 'niyom')),
        ])
        ->add_tab(__('Author', 'niyom'), [
            Field::make('html', 'insight_author_note')
                ->set_html('<p>' . esc_html__('Empty fields use the post author\'s name and biographical info (Users › Profile).', 'niyom') . '</p>'),
            Field::make('text', 'insight_author_name', __('Name', 'niyom'))->set_width(50),
            Field::make('text', 'insight_author_role', __('Role', 'niyom'))->set_width(50),
            Field::make('image', 'insight_author_photo', __('Photo', 'niyom'))
                ->set_value_type('url')
                ->set_help_text(__('Square image, shown as a 140px circle. Empty = a plain circle.', 'niyom')),
            Field::make('textarea', 'insight_author_bio', __('Biography', 'niyom'))->set_rows(3),
            Field::make('text', 'insight_author_link_text', __('Link text', 'niyom'))
                ->set_width(40)
                ->set_default_value('About NIYOM'),
            Field::make('text', 'insight_author_link', __('Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/about/'),
            Field::make('checkbox', 'insight_author_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),
        ]);

    // Shared by every article (Insights › Article Settings).
    Container::make('theme_options', 'niyom_insights_settings', __('Article Settings', 'niyom'))
        ->set_page_parent('edit.php?post_type=insights')
        ->set_page_file('niyom-insights-settings')
        ->add_tab(__('Labels', 'niyom'), [
            Field::make('text', 'niyom_insights_toc_label', __('"On this page" label', 'niyom'))
                ->set_default_value('On this page')
                ->set_help_text(__('Built from the article\'s H2 headings. Leave empty to hide the list.', 'niyom')),
            Field::make('text', 'niyom_insights_share_label', __('"Share" label', 'niyom'))
                ->set_default_value('Share')
                ->set_help_text(__('Leave empty to hide the share buttons.', 'niyom')),
            Field::make('text', 'niyom_insights_author_label', __('"About the author" label', 'niyom'))
                ->set_default_value('About the author'),
        ])
        ->add_tab(__('Continue reading', 'niyom'), [
            Field::make('text', 'niyom_insights_related_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Related insights'),
            Field::make('textarea', 'niyom_insights_related_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Continue reading.'),
            Field::make('text', 'niyom_insights_related_link_text', __('Link text', 'niyom'))
                ->set_width(50)
                ->set_default_value('All insights'),
            Field::make('text', 'niyom_insights_related_link', __('Link', 'niyom'))
                ->set_width(50)
                ->set_default_value('/insights/'),
            Field::make('text', 'niyom_insights_card_link_text', __('Card link text', 'niyom'))
                ->set_default_value('Know more'),
        ])
        ->add_tab(__('CTA band', 'niyom'), [
            Field::make('text', 'niyom_insights_cta_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Request research support'),
            Field::make('textarea', 'niyom_insights_cta_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Did this raise a question about your own study?')
                ->set_help_text(__('Leave heading, label and buttons empty to hide the band.', 'niyom')),
            Field::make('image', 'niyom_insights_cta_image', __('Background Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/home/expertise-bg.jpg'),
            Field::make('separator', 'niyom_insights_cta_sep_primary', __('Primary Button (white)', 'niyom')),
            Field::make('text', 'niyom_insights_cta_primary_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('Discuss your project'),
            Field::make('text', 'niyom_insights_cta_primary_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/work-with-us/'),
            Field::make('checkbox', 'niyom_insights_cta_primary_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),
            Field::make('separator', 'niyom_insights_cta_sep_secondary', __('Secondary Button (outline)', 'niyom')),
            Field::make('text', 'niyom_insights_cta_secondary_text', __('Button Text', 'niyom'))->set_width(40),
            Field::make('text', 'niyom_insights_cta_secondary_link', __('Button Link', 'niyom'))->set_width(40),
            Field::make('checkbox', 'niyom_insights_cta_secondary_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),
        ]);
});

// =====================================================================
// Helpers
// =====================================================================

/**
 * Read an Insight's Carbon Fields value ('' when Carbon Fields is not loaded).
 *
 * @param int    $post_id Post ID.
 * @param string $key     Field name.
 * @return mixed
 */
function niyom_insight_meta($post_id, $key)
{
    return function_exists('carbon_get_post_meta') ? carbon_get_post_meta($post_id, $key) : '';
}

/**
 * First "Insight Category" name of a post ('' if none).
 *
 * @param int|WP_Post $post Post.
 * @return string
 */
function niyom_insight_category_name($post)
{
    $terms = get_the_terms($post, 'insight_category');
    return ($terms && !is_wp_error($terms)) ? $terms[0]->name : '';
}

/**
 * WP_Query arguments for a page of Insights cards.
 *
 * @param string $category insight_category slug ('' = all).
 * @param int    $page     Page number (1-based).
 * @param int    $per_page Cards per page.
 * @param int[]  $exclude  Post IDs to leave out (e.g. the featured article).
 * @return array
 */
function niyom_insights_query_args($category, $page, $per_page, $exclude = [])
{
    $args = [
        'post_type'           => 'insights',
        'post_status'         => 'publish',
        'posts_per_page'      => max(1, (int) $per_page),
        'paged'               => max(1, (int) $page),
        'ignore_sticky_posts' => true,
        'post__not_in'        => array_values(array_filter(array_map('absint', (array) $exclude))),
    ];

    if ($category !== '') {
        $args['tax_query'] = [[
            'taxonomy' => 'insight_category',
            'field'    => 'slug',
            'terms'    => $category,
        ]];
    }

    return $args;
}

/**
 * One Insight card (components/Insights/card.php) as HTML.
 *
 * @param WP_Post|int $post      Post.
 * @param string      $link_text "Know more" link text ('' = no link).
 * @return string
 */
function niyom_insight_card($post, $link_text = '')
{
    ob_start();
    get_template_part('components/Insights/card', null, [
        'post'      => $post,
        'link_text' => $link_text,
    ]);
    return (string) ob_get_clean();
}

/**
 * Add ids to the article's H2 headings and list them for "On this page".
 *
 * @param string $html Rendered post content.
 * @return array{0: string, 1: array} [content with ids, [ ['id' => …, 'text' => …], … ]]
 */
function niyom_insight_toc($html)
{
    $items = [];
    $used  = ['references' => true]; // Reserved for the references section.

    $html = preg_replace_callback('#<h2(\s[^>]*)?>(.*?)</h2>#is', function ($match) use (&$items, &$used) {
        $attrs = $match[1] ?? '';
        $text  = trim(html_entity_decode(wp_strip_all_tags($match[2]), ENT_QUOTES, 'UTF-8'));

        if ($text === '') {
            return $match[0];
        }

        if (preg_match('/\sid=("|\')([^"\']+)\1/i', $attrs, $id_match)) {
            $id = $id_match[2];
        } else {
            $base = sanitize_title($text) ?: 'section';
            $id   = $base;
            $n    = 2;
            while (isset($used[$id])) {
                $id = $base . '-' . $n++;
            }
            $attrs .= ' id="' . esc_attr($id) . '"';
        }

        $used[$id] = true;
        $items[]   = ['id' => $id, 'text' => $text];

        return '<h2' . $attrs . '>' . $match[2] . '</h2>';
    }, $html);

    return [$html, $items];
}

/**
 * Up to $count other Insights for "Continue reading": same category first,
 * then the latest ones.
 *
 * @param int $post_id Current post.
 * @param int $count   Number of posts.
 * @return WP_Post[]
 */
function niyom_insight_related($post_id, $count = 4)
{
    $posts   = [];
    $exclude = [$post_id];
    $terms   = wp_get_post_terms($post_id, 'insight_category', ['fields' => 'ids']);

    if ($terms && !is_wp_error($terms)) {
        $posts = get_posts([
            'post_type'           => 'insights',
            'post_status'         => 'publish',
            'posts_per_page'      => $count,
            'post__not_in'        => $exclude,
            'ignore_sticky_posts' => true,
            'tax_query'           => [[
                'taxonomy' => 'insight_category',
                'field'    => 'term_id',
                'terms'    => $terms,
            ]],
        ]);
    }

    if (count($posts) < $count) {
        $exclude = array_merge($exclude, wp_list_pluck($posts, 'ID'));
        $posts   = array_merge($posts, get_posts([
            'post_type'           => 'insights',
            'post_status'         => 'publish',
            'posts_per_page'      => $count - count($posts),
            'post__not_in'        => $exclude,
            'ignore_sticky_posts' => true,
        ]));
    }

    return $posts;
}

// =====================================================================
// REST: GET /wp-json/niyom/v1/insights
// =====================================================================
/**
 * Used by the listing page (src/scripts/components/insights.js) for the
 * category tabs and the "Load more" button. Returns rendered card HTML.
 *
 * Params: category (slug, optional), page (1+), per_page (1–24),
 * exclude (post ID, e.g. the featured article), link_text.
 * Response: { html, page, pages, total, shown }
 */
add_action('rest_api_init', function () {
    register_rest_route('niyom/v1', '/insights', [
        'methods'             => WP_REST_Server::READABLE,
        'permission_callback' => '__return_true',
        'args'                => [
            'category'  => ['type' => 'string', 'default' => '', 'sanitize_callback' => function ($value) { return sanitize_title((string) $value); }],
            'page'      => ['type' => 'integer', 'default' => 1, 'minimum' => 1, 'maximum' => 1000],
            'per_page'  => ['type' => 'integer', 'default' => 8, 'minimum' => 1, 'maximum' => 24],
            'exclude'   => ['type' => 'integer', 'default' => 0, 'minimum' => 0],
            'link_text' => ['type' => 'string', 'default' => '', 'sanitize_callback' => function ($value) { return sanitize_text_field((string) $value); }],
        ],
        'callback'            => function (WP_REST_Request $request) {
            $category = (string) $request['category'];
            $page     = (int) $request['page'];
            $per_page = (int) $request['per_page'];

            if ($category !== '' && !term_exists($category, 'insight_category')) {
                return new WP_Error('niyom_insights_category', __('Unknown category.', 'niyom'), ['status' => 404]);
            }

            $query = new WP_Query(niyom_insights_query_args($category, $page, $per_page, [(int) $request['exclude']]));
            $html  = '';
            foreach ($query->posts as $post) {
                $html .= niyom_insight_card($post, mb_substr((string) $request['link_text'], 0, 60));
            }

            return rest_ensure_response([
                'html'  => $html,
                'page'  => $page,
                'pages' => (int) $query->max_num_pages,
                'total' => (int) $query->found_posts,
                'shown' => min((int) $query->found_posts, ($page - 1) * $per_page + count($query->posts)),
            ]);
        },
    ]);
});

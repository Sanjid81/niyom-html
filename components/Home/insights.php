<?php
/**
 * Home Insights section markup. Fields arrive in $args (see inc/blocks/home-insights.php).
 * Cards are the latest "insights" posts; the grid is hidden when there are none.
 * Desktop: 4-column grid. Mobile: horizontal scroll with snap (CSS only).
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow     = trim($args['insights_eyebrow'] ?? '');
$heading     = niyom_inline_text($args['insights_heading'] ?? '');
$button_text = trim($args['insights_button_text'] ?? '');
$button_link = trim($args['insights_button_link'] ?? '');
$new_tab     = !empty($args['insights_button_new_tab']);
$has_button  = $button_text !== '' && $button_link !== '';
$link_text   = trim($args['insights_link_text'] ?? '');
$count       = max(1, min(12, (int) ($args['insights_count'] ?? 4) ?: 4));
$category    = sanitize_title($args['insights_category'] ?? '');

// Latest published Insights posts (optionally one category).
$query_args = [
    'post_type'           => 'insights',
    'post_status'         => 'publish',
    'posts_per_page'      => $count,
    'ignore_sticky_posts' => true,
    'no_found_rows'       => true,
];
if ($category !== '') {
    $query_args['tax_query'] = [[
        'taxonomy' => 'insight_category',
        'field'    => 'slug',
        'terms'    => $category,
    ]];
}
$posts = get_posts($query_args);

if (!$eyebrow && !$heading && !$has_button && !$posts) {
    return;
}

$heading_id = wp_unique_id('home-insights-heading-');
?>

<section class="home-insights"<?php echo $heading ? ' aria-labelledby="' . esc_attr($heading_id) . '"' : ''; ?>>
    <div class="home-insights__inner">

        <?php if ($eyebrow || $heading || $has_button) : ?>
            <div class="home-insights__intro">
                <?php if ($eyebrow || $heading) : ?>
                    <div class="home-insights__titles">
                        <?php if ($eyebrow) : ?>
                            <p class="home-insights__eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                            <h2 class="home-insights__heading heading-two" id="<?php echo esc_attr($heading_id); ?>"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($has_button) : ?>
                    <a class="btn btn--primary home-insights__button" <?php echo niyom_link_attrs($button_link, $new_tab); ?>>
                        <span><?php echo esc_html($button_text); ?></span>
                        <?php echo niyom_icon('arrow-right', 'btn__arrow btn__arrow--lg'); ?>
                        <?php echo niyom_icon('arrow-right-sm', 'btn__arrow btn__arrow--sm'); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($posts) : ?>
            <ul class="home-insights__grid" role="list">
                <?php foreach ($posts as $post_item) :
                    $permalink = get_permalink($post_item);
                    $title     = get_the_title($post_item);
                    $terms     = get_the_terms($post_item, 'insight_category');
                    $term_name = ($terms && !is_wp_error($terms)) ? $terms[0]->name : '';
                    ?>
                    <li class="home-insights__card">
                        <?php if (has_post_thumbnail($post_item)) : ?>
                            <a class="home-insights__media" href="<?php echo esc_url($permalink); ?>" tabindex="-1" aria-hidden="true">
                                <?php echo get_the_post_thumbnail($post_item, 'large', [
                                    'class'   => 'home-insights__image',
                                    'alt'     => '',
                                    'loading' => 'lazy',
                                    'sizes'   => '(max-width: 768px) 260px, (max-width: 1200px) 50vw, 425px',
                                ]); ?>
                            </a>
                        <?php endif; ?>

                        <div class="home-insights__body">
                            <div class="home-insights__text">
                                <p class="home-insights__meta">
                                    <time class="home-insights__date" datetime="<?php echo esc_attr(get_the_date('c', $post_item)); ?>"><?php echo esc_html(get_the_date('j M, Y', $post_item)); ?></time>
                                    <?php if ($term_name) : ?>
                                        <span class="home-insights__dot" aria-hidden="true"></span>
                                        <span class="home-insights__category"><?php echo esc_html($term_name); ?></span>
                                    <?php endif; ?>
                                </p>

                                <?php if ($title !== '') : ?>
                                    <h3 class="home-insights__title lead-text">
                                        <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
                                    </h3>
                                <?php endif; ?>
                            </div>

                            <?php if ($link_text !== '') : ?>
                                <a class="link-arrow home-insights__link" href="<?php echo esc_url($permalink); ?>"
                                    aria-label="<?php echo esc_attr($link_text . ': ' . $title); ?>">
                                    <span><?php echo esc_html($link_text); ?></span>
                                    <?php echo niyom_icon('arrow-right', 'link-arrow__icon link-arrow__icon--lg'); ?>
                                    <?php echo niyom_icon('arrow-right-sm', 'link-arrow__icon link-arrow__icon--sm'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</section>

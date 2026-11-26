<?php
/**
 * Insights › All insights: category tabs, 4-column card grid, "Load more".
 * Fields arrive in $args (see inc/blocks/insights-block.php).
 * Figma: Insights › Content (303:7752). Label + H2; tab row with a teal
 * underline on the active tab; cards (components/Insights/card.php);
 * LOAD MORE INSIGHTS ⟶ centred.
 *
 * Data: real "insights" posts, newest first, without the featured article.
 * Tabs = "All" + every Insight Category that has posts.
 *   No JS:   tabs are links (?category=slug), "Load more" is a link
 *            (?show=2 renders two pages) — both reload the page.
 *   With JS: src/scripts/components/insights.js fetches cards from
 *            /wp-json/niyom/v1/insights (inc/insights-meta.php) instead.
 * Styles: src/styles/components/insights/_grid.scss + _card.scss
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow    = trim((string) ($args['grid_eyebrow'] ?? ''));
$heading    = niyom_inline_text($args['grid_heading'] ?? '');
$all_label  = trim((string) ($args['grid_all_label'] ?? ''));
$per_page   = max(1, min(24, (int) ($args['grid_per_page'] ?? 8) ?: 8));
$more_text  = trim((string) ($args['grid_load_more_text'] ?? ''));
$link_text  = trim((string) ($args['grid_link_text'] ?? ''));
$empty_text = trim((string) ($args['grid_empty_text'] ?? ''));
$exclude    = (int) ($args['featured_id'] ?? 0);

// Category tabs: terms with published posts (a term whose only post is the
// featured article would show an empty tab, so it is skipped).
$terms = get_terms([
    'taxonomy'   => 'insight_category',
    'hide_empty' => true,
    'orderby'    => 'name',
]);
$terms = is_wp_error($terms) ? [] : array_values(array_filter($terms, function ($term) use ($exclude) {
    return !($exclude && $term->count <= 1 && has_term($term->term_id, 'insight_category', $exclude));
}));

// Current tab + number of pages shown (query args, for visitors without JS).
$category = isset($_GET['category']) ? sanitize_title(wp_unslash($_GET['category'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification
if ($category !== '' && !in_array($category, wp_list_pluck($terms, 'slug'), true)) {
    $category = '';
}
$show = isset($_GET['show']) ? max(1, min(10, (int) $_GET['show'])) : 1; // phpcs:ignore WordPress.Security.NonceVerification

$query = new WP_Query(niyom_insights_query_args($category, 1, $per_page * $show, [$exclude]));
$total = (int) $query->found_posts;
$pages = (int) ceil($total / $per_page);
$page  = min($show, max(1, $pages));

if ($eyebrow === '' && !$heading && !$total && !$terms) {
    return;
}

$base_url   = get_permalink() ?: home_url('/insights/');
$section_id = 'all-insights';
$uid        = wp_unique_id('in-grid-');
$panel_id   = $uid . '-panel';
$heading_id = $uid . '-heading';
$show_tabs  = $terms && $all_label !== '';

// Tabs: "All" first, then each category.
$tabs = [];
if ($show_tabs) {
    $tabs[] = ['slug' => '', 'name' => $all_label];
    foreach ($terms as $term) {
        $tabs[] = ['slug' => $term->slug, 'name' => $term->name];
    }
}
$active_tab_id = '';

/* translators: 1: number of insights shown, 2: total number of insights. */
$count_many = __('Showing %1$d of %2$d insights', 'niyom');
/* translators: 1: number of insights shown, 2: total number of insights. */
$count_one  = __('Showing %1$d of %2$d insight', 'niyom');
$more_url   = add_query_arg(array_filter(['category' => $category, 'show' => $page + 1]), $base_url) . '#' . $section_id;
?>

<section class="in-grid" id="<?php echo esc_attr($section_id); ?>" data-in-grid
    data-endpoint="<?php echo esc_url(rest_url('niyom/v1/insights')); ?>"
    data-per-page="<?php echo esc_attr($per_page); ?>"
    data-exclude="<?php echo esc_attr($exclude); ?>"
    data-link-text="<?php echo esc_attr($link_text); ?>"
    data-category="<?php echo esc_attr($category); ?>"
    data-page="<?php echo esc_attr($page); ?>"
    data-pages="<?php echo esc_attr($pages); ?>"
    data-total="<?php echo esc_attr($total); ?>"
    data-count-one="<?php echo esc_attr($count_one); ?>"
    data-count-many="<?php echo esc_attr($count_many); ?>"
    data-loading-text="<?php esc_attr_e('Loading insights…', 'niyom'); ?>"
    <?php echo $heading ? 'aria-labelledby="' . esc_attr($heading_id) . '"' : ''; ?>>
    <div class="in-grid-inner">

        <?php if ($eyebrow !== '' || $heading) : ?>
            <div class="in-grid-intro">
                <?php if ($eyebrow !== '') : ?>
                    <p class="in-grid-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="in-grid-heading heading-two" id="<?php echo esc_attr($heading_id); ?>"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="in-grid-body">
            <?php if ($show_tabs) : ?>
                <div class="in-grid-tabs" role="tablist" aria-label="<?php esc_attr_e('Insight categories', 'niyom'); ?>">
                    <?php foreach ($tabs as $index => $tab) :
                        $tab_id   = $uid . '-tab-' . $index;
                        $selected = $tab['slug'] === $category;
                        if ($selected) {
                            $active_tab_id = $tab_id;
                        }
                        $tab_url = ($tab['slug'] !== '' ? add_query_arg('category', $tab['slug'], $base_url) : $base_url) . '#' . $section_id;
                        ?>
                        <a class="in-grid-tab" role="tab" id="<?php echo esc_attr($tab_id); ?>"
                            href="<?php echo esc_url($tab_url); ?>"
                            data-category="<?php echo esc_attr($tab['slug']); ?>"
                            aria-controls="<?php echo esc_attr($panel_id); ?>"
                            aria-selected="<?php echo $selected ? 'true' : 'false'; ?>"
                            tabindex="<?php echo $selected ? '0' : '-1'; ?>"><?php echo esc_html($tab['name']); ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="in-grid-panel" id="<?php echo esc_attr($panel_id); ?>"
                <?php echo $show_tabs ? 'role="tabpanel" aria-labelledby="' . esc_attr($active_tab_id) . '"' : ''; ?>>
                <ul class="in-cards" role="list"<?php echo $query->posts ? '' : ' hidden'; ?>>
                    <?php foreach ($query->posts as $card_post) {
                        echo niyom_insight_card($card_post, $link_text); // Escaped in components/Insights/card.php.
                    } ?>
                </ul>

                <?php if ($empty_text !== '') : ?>
                    <p class="in-grid-empty"<?php echo $query->posts ? ' hidden' : ''; ?>><?php echo esc_html($empty_text); ?></p>
                <?php endif; ?>
            </div>

            <p class="in-grid-status" role="status" aria-live="polite"></p>

            <?php if ($more_text !== '') : ?>
                <div class="in-grid-more"<?php echo $page < $pages ? '' : ' hidden'; ?>>
                    <a class="btn btn--primary in-grid-more-button" href="<?php echo esc_url($more_url); ?>" rel="nofollow">
                        <span><?php echo esc_html($more_text); ?></span>
                        <?php echo niyom_icon('arrow-right', 'btn-arrow btn-arrow--lg'); ?>
                        <?php echo niyom_icon('arrow-right-sm', 'btn-arrow btn-arrow--sm'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

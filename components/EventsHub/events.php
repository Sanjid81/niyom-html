<?php
/**
 * Events & Partnerships › Events markup. Fields arrive in $args
 * (see inc/blocks/events-hub-block.php).
 * Styles: src/styles/components/events-hub/_events.scss
 * JS: src/scripts/components/events-hub.js
 *
 * Every event is printed. Each card carries its date (data-start / data-end)
 * and filter category (data-category); the script compares the date with
 * the visitor's today for the Upcoming / Past / All tabs and filters by the
 * type pills. Without JavaScript the tabs and pills stay hidden and all
 * events show. When an event has a link, the title link stretches over the
 * whole card; "View details" is then a visual cue only.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow     = trim((string) ($args['events_eyebrow'] ?? ''));
$heading     = niyom_inline_text($args['events_heading'] ?? '');
$tab_labels  = [
    'upcoming' => trim((string) ($args['events_tab_upcoming'] ?? '')),
    'past'     => trim((string) ($args['events_tab_past'] ?? '')),
    'all'      => trim((string) ($args['events_tab_all'] ?? '')),
];
$default_tab = (string) ($args['events_default_tab'] ?? 'upcoming');
$all_types   = trim((string) ($args['events_all_types'] ?? ''));
$empty_text  = trim((string) ($args['events_empty'] ?? ''));
$today       = current_time('Y-m-d');

// Valid Y-m-d date or ''.
$clean_date = static function ($value) {
    $value = trim((string) $value);
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) ? $value : '';
};

// ---------- Events: clean data, skip empty rows ----------
$events     = [];
$categories = []; // slug => label, in first-use order.
foreach ((array) ($args['events_items'] ?? []) as $row) {
    $start = $clean_date($row['date'] ?? '');
    $end   = $clean_date($row['end_date'] ?? '');
    if ($end !== '' && $start !== '' && $end < $start) {
        $end = '';
    }

    $type     = trim((string) ($row['type'] ?? ''));
    $category = trim((string) ($row['category'] ?? ''));
    if ($category === '') {
        $category = $type;
    }
    $category_slug = $category !== '' ? sanitize_title($category) : '';

    $event = [
        'start'     => $start,
        'end'       => $end,
        'day'       => $start ? date_i18n('j', strtotime($start)) : '',
        'month'     => $start ? date_i18n('M Y', strtotime($start)) : '',
        'type'      => $type,
        'category'  => $category_slug,
        'title'     => niyom_inline_text($row['title'] ?? ''),
        'meta'      => trim((string) ($row['meta'] ?? '')),
        'image'     => trim((string) ($row['image'] ?? '')),
        'link'      => trim((string) ($row['link'] ?? '')),
        'link_text' => trim((string) ($row['link_text'] ?? '')),
        'new_tab'   => !empty($row['new_tab']),
    ];

    if (!$event['start'] && !$event['type'] && !$event['title'] && !$event['meta'] && !$event['image']) {
        continue;
    }

    // Server-side status (the script recalculates it with the visitor's date).
    $last            = $end ?: $start;
    $event['status'] = $last === '' ? '' : ($last >= $today ? 'upcoming' : 'past');

    if ($category_slug !== '' && !isset($categories[$category_slug])) {
        $categories[$category_slug] = $category;
    }

    $events[] = $event;
}

$has_intro = $eyebrow !== '' || $heading;

if (!$has_intro && !$events) {
    return;
}

$upcoming_count = count(array_filter($events, static function ($event) {
    return $event['status'] === 'upcoming';
}));

// Tabs need all three labels; pills need the "All types" label and 2+ categories.
$has_tabs  = $events && $tab_labels['upcoming'] !== '' && $tab_labels['past'] !== '' && $tab_labels['all'] !== '';
$has_pills = $events && $all_types !== '' && count($categories) > 1;
$uid       = wp_unique_id('eh-events-');

if (!isset($tab_labels[$default_tab])) {
    $default_tab = 'upcoming';
}
?>

<section class="eh-events"
    data-eh-events
    data-default-tab="<?php echo esc_attr($default_tab); ?>"
    data-count-one="<?php esc_attr_e('Showing 1 event', 'niyom'); ?>"
    data-count-many="<?php /* translators: %d: number of events. */ esc_attr_e('Showing %d events', 'niyom'); ?>">
    <div class="eh-events-inner">

        <?php if ($has_intro) : ?>
            <div class="eh-events-header">
                <?php if ($eyebrow !== '') : ?>
                    <p class="eh-events-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="eh-events-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($events) : ?>
            <div class="eh-events-body">

                <?php if ($has_tabs || $has_pills) : ?>
                    <div class="eh-events-controls" hidden>
                        <?php if ($has_tabs) : ?>
                            <div class="eh-events-tabs" role="tablist" aria-label="<?php esc_attr_e('Event dates', 'niyom'); ?>">
                                <?php foreach ($tab_labels as $key => $label) : ?>
                                    <button type="button"
                                        class="eh-events-tab"
                                        role="tab"
                                        id="<?php echo esc_attr($uid . '-tab-' . $key); ?>"
                                        aria-controls="<?php echo esc_attr($uid . '-list'); ?>"
                                        aria-selected="false"
                                        tabindex="-1"
                                        data-tab="<?php echo esc_attr($key); ?>">
                                        <span><?php echo esc_html($label); ?></span><?php if ($key === 'upcoming') : ?><span class="eh-events-tab-count"> (<?php echo esc_html($upcoming_count); ?>)</span><?php endif; ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($has_pills) : ?>
                            <div class="eh-events-filters" role="group" aria-label="<?php esc_attr_e('Filter by event type', 'niyom'); ?>">
                                <button type="button" class="eh-events-filter" aria-pressed="true" data-category=""><?php echo esc_html($all_types); ?></button>
                                <?php foreach ($categories as $slug => $label) : ?>
                                    <button type="button" class="eh-events-filter" aria-pressed="false" data-category="<?php echo esc_attr($slug); ?>"><?php echo esc_html($label); ?></button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <ul class="eh-events-list" id="<?php echo esc_attr($uid . '-list'); ?>"<?php if ($has_tabs) : ?> role="tabpanel" aria-labelledby="<?php echo esc_attr($uid . '-tab-' . $default_tab); ?>"<?php endif; ?>>
                    <?php foreach ($events as $event) :
                        $has_link     = $event['link'] !== '';
                        // The title carries the card link; without a title, "View details" does.
                        $more_is_link = $has_link && !$event['title'];
                        $classes      = ['eh-events-card'];
                        if ($has_link) {
                            $classes[] = 'is-linked';
                        }
                        if ($event['image']) {
                            $classes[] = 'has-image';
                        }
                        ?>
                        <li class="<?php echo esc_attr(implode(' ', $classes)); ?>"
                            data-start="<?php echo esc_attr($event['start']); ?>"
                            data-end="<?php echo esc_attr($event['end']); ?>"
                            data-status="<?php echo esc_attr($event['status']); ?>"
                            data-category="<?php echo esc_attr($event['category']); ?>">

                            <?php if ($event['image']) : ?>
                                <div class="eh-events-media">
                                    <img class="eh-events-image" src="<?php echo esc_url($event['image']); ?>" alt="" loading="lazy" decoding="async">
                                </div>
                            <?php endif; ?>

                            <div class="eh-events-details">
                                <?php if ($event['start']) : ?>
                                    <time class="eh-events-date" datetime="<?php echo esc_attr($event['start']); ?>">
                                        <span class="eh-events-day"><?php echo esc_html($event['day']); ?></span>
                                        <span class="eh-events-month"><?php echo esc_html($event['month']); ?></span>
                                    </time>
                                <?php endif; ?>

                                <div class="eh-events-content">
                                    <?php if ($event['type']) : ?>
                                        <p class="eh-events-type"><?php echo esc_html($event['type']); ?></p>
                                    <?php endif; ?>

                                    <?php if ($event['title']) : ?>
                                        <h3 class="eh-events-title">
                                            <?php if ($has_link) : ?>
                                                <a class="eh-events-link" <?php echo niyom_link_attrs($event['link'], $event['new_tab']); ?>><?php echo $event['title']; // Escaped in niyom_inline_text(). ?></a>
                                            <?php else : ?>
                                                <?php echo $event['title']; // Escaped in niyom_inline_text(). ?>
                                            <?php endif; ?>
                                        </h3>
                                    <?php endif; ?>

                                    <?php if ($event['meta']) : ?>
                                        <p class="eh-events-meta"><?php echo esc_html($event['meta']); ?></p>
                                    <?php endif; ?>

                                    <?php if ($has_link && $event['link_text']) : ?>
                                        <?php if ($more_is_link) : ?>
                                            <a class="link-arrow eh-events-more eh-events-link" <?php echo niyom_link_attrs($event['link'], $event['new_tab']); ?>>
                                        <?php else : ?>
                                            <span class="link-arrow eh-events-more" aria-hidden="true">
                                        <?php endif; ?>
                                            <span><?php echo esc_html($event['link_text']); ?></span>
                                            <?php echo niyom_icon('arrow-right', 'link-arrow-icon link-arrow-icon--lg'); ?>
                                            <?php echo niyom_icon('arrow-right-sm', 'link-arrow-icon link-arrow-icon--sm'); ?>
                                        <?php echo $more_is_link ? '</a>' : '</span>'; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if ($empty_text !== '') : ?>
                    <p class="eh-events-empty" hidden><?php echo esc_html($empty_text); ?></p>
                <?php endif; ?>

                <p class="eh-events-status" aria-live="polite"></p>
            </div>
        <?php endif; ?>

    </div>
</section>

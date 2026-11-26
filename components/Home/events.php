<?php
/**
 * Home › Events & Partnerships markup. Fields arrive in $args
 * (see inc/blocks/home-events.php). Styles: src/styles/components/home/events.scss
 *
 * Each part prints only when its field has content. When an event has a
 * link, the title link stretches over the whole row, so the row is one
 * click target; "View details" is then a visual cue only.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

// ---------- Intro ----------
$eyebrow     = trim($args['events_eyebrow'] ?? '');
$heading     = niyom_inline_text($args['events_heading'] ?? '');
$text        = niyom_rich_text($args['events_text'] ?? '');
$button_text = trim($args['events_button_text'] ?? '');
$button_link = trim($args['events_button_link'] ?? '');
$button_tab  = !empty($args['events_button_new_tab']);
$has_button  = $button_text !== '' && $button_link !== '';

// ---------- Events: clean data, skip empty rows ----------
$events = [];
foreach ((array) ($args['events_items'] ?? []) as $row) {
    $event = [
        'day'       => trim($row['day'] ?? ''),
        'month'     => trim($row['month'] ?? ''),
        'type'      => trim($row['type'] ?? ''),
        'title'     => niyom_inline_text($row['title'] ?? ''),
        'meta'      => trim($row['meta'] ?? ''),
        'link'      => trim($row['link'] ?? ''),
        'link_text' => trim($row['link_text'] ?? ''),
        'new_tab'   => !empty($row['new_tab']),
        'image'     => trim($row['image'] ?? ''),
    ];

    if ($event['day'] || $event['month'] || $event['type'] || $event['title'] || $event['meta'] || $event['image']) {
        $events[] = $event;
    }
}

$has_intro = $eyebrow || $heading || $text || $has_button;

if (!$has_intro && !$events) {
    return;
}
?>

<section class="home-events">
    <div class="home-events__inner">

        <?php if ($has_intro) : ?>
            <div class="home-events__intro">
                <?php if ($eyebrow || $heading || $text) : ?>
                    <div class="home-events__header">
                        <?php if ($eyebrow) : ?>
                            <p class="home-events__eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                            <h2 class="home-events__heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                        <?php endif; ?>

                        <?php if ($text) : ?>
                            <div class="home-events__text rich-text body-large">
                                <?php echo $text; // Escaped in niyom_rich_text(). ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($has_button) : ?>
                    <a class="btn btn--primary home-events__button" <?php echo niyom_link_attrs($button_link, $button_tab); ?>>
                        <span><?php echo esc_html($button_text); ?></span>
                        <?php echo niyom_icon('arrow-right', 'btn__arrow btn__arrow--lg'); ?>
                        <?php echo niyom_icon('arrow-right-sm', 'btn__arrow btn__arrow--sm'); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($events) : ?>
            <ul class="home-events__list">
                <?php foreach ($events as $event) :
                    $has_link  = $event['link'] !== '';
                    $has_date  = $event['day'] || $event['month'];
                    // The title carries the row link; without a title, "View details" does.
                    $more_is_link = $has_link && !$event['title'];
                    $classes   = ['home-events__item'];
                    if ($has_link) {
                        $classes[] = 'is-linked';
                    }
                    if ($event['image']) {
                        $classes[] = 'has-image';
                    }
                    ?>
                    <li class="<?php echo esc_attr(implode(' ', $classes)); ?>">

                        <?php if ($event['image']) : ?>
                            <div class="home-events__media">
                                <img class="home-events__image"
                                    src="<?php echo esc_url($event['image']); ?>"
                                    alt=""
                                    loading="lazy"
                                    decoding="async">
                            </div>
                        <?php endif; ?>

                        <div class="home-events__details">
                            <?php if ($has_date) : ?>
                                <div class="home-events__date">
                                    <?php if ($event['day']) : ?>
                                        <span class="home-events__day"><?php echo esc_html($event['day']); ?></span>
                                    <?php endif; ?>
                                    <?php if ($event['month']) : ?>
                                        <span class="home-events__month"><?php echo esc_html($event['month']); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <div class="home-events__content">
                                <?php if ($event['type']) : ?>
                                    <p class="home-events__type"><?php echo esc_html($event['type']); ?></p>
                                <?php endif; ?>

                                <?php if ($event['title']) : ?>
                                    <h3 class="home-events__title">
                                        <?php if ($has_link) : ?>
                                            <a class="home-events__link" <?php echo niyom_link_attrs($event['link'], $event['new_tab']); ?>><?php echo $event['title']; // Escaped in niyom_inline_text(). ?></a>
                                        <?php else : ?>
                                            <?php echo $event['title']; // Escaped in niyom_inline_text(). ?>
                                        <?php endif; ?>
                                    </h3>
                                <?php endif; ?>

                                <?php if ($event['meta']) : ?>
                                    <p class="home-events__meta"><?php echo esc_html($event['meta']); ?></p>
                                <?php endif; ?>

                                <?php if ($has_link && $event['link_text']) : ?>
                                    <?php if ($more_is_link) : ?>
                                        <a class="link-arrow home-events__more home-events__link" <?php echo niyom_link_attrs($event['link'], $event['new_tab']); ?>>
                                    <?php else : ?>
                                        <span class="link-arrow home-events__more" aria-hidden="true">
                                    <?php endif; ?>
                                        <span><?php echo esc_html($event['link_text']); ?></span>
                                        <?php echo niyom_icon('arrow-right', 'link-arrow__icon link-arrow__icon--lg'); ?>
                                        <?php echo niyom_icon('arrow-right-sm', 'link-arrow__icon link-arrow__icon--sm'); ?>
                                    <?php echo $more_is_link ? '</a>' : '</span>'; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</section>

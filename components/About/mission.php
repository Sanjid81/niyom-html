<?php
/**
 * About › Brand & Mission markup. Fields arrive in $args (see inc/blocks/about-mission.php).
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim((string) ($args['mission_eyebrow'] ?? ''));
$title   = niyom_inline_text($args['mission_title'] ?? '');
$intro   = niyom_rich_text($args['mission_intro'] ?? '');

// Build clean list items; skip rows with nothing to show.
$items = [];
foreach ((array) ($args['mission_items'] ?? []) as $row) {
    $item = [
        'number' => trim((string) ($row['number'] ?? '')),
        'title'  => niyom_inline_text($row['title'] ?? ''),
        'text'   => niyom_rich_text($row['text'] ?? ''),
    ];

    if ($item['number'] !== '' || $item['title'] || $item['text']) {
        $items[] = $item;
    }
}

$has_head = $eyebrow || $title;
$has_body = $intro || $items;

if (!$has_head && !$has_body) {
    return;
}
?>

<section class="about-mission">
    <div class="about-mission__inner">

        <?php if ($has_head) : ?>
            <header class="about-mission__head">
                <?php if ($eyebrow) : ?>
                    <p class="about-mission__eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($title) : ?>
                    <h2 class="about-mission__title"><?php echo $title; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <?php if ($has_body) : ?>
            <div class="about-mission__body">
                <?php if ($intro) : ?>
                    <div class="about-mission__intro rich-text"><?php echo $intro; // Escaped in niyom_rich_text(). ?></div>
                <?php endif; ?>

                <?php if ($items) : ?>
                    <ol class="about-mission__list">
                        <?php foreach ($items as $item) : ?>
                            <li class="about-mission__item">
                                <?php if ($item['number'] !== '') : ?>
                                    <span class="about-mission__number"><?php echo esc_html($item['number']); ?></span>
                                <?php endif; ?>

                                <?php if ($item['title'] || $item['text']) : ?>
                                    <div class="about-mission__content">
                                        <?php if ($item['title']) : ?>
                                            <h3 class="about-mission__item-title"><?php echo $item['title']; // Escaped in niyom_inline_text(). ?></h3>
                                        <?php endif; ?>

                                        <?php if ($item['text']) : ?>
                                            <div class="about-mission__item-text rich-text"><?php echo $item['text']; // Escaped in niyom_rich_text(). ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

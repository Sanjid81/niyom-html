<?php
/**
 * Research Support › Deliverables and boundaries.
 * Fields arrive in $args (see inc/blocks/research-support-block.php).
 * Styles: src/styles/components/research-support/_deliverables.scss
 *
 * Left: label, heading and a list of deliverables (title, text).
 * Right: Mist box with a label, heading, text and a dashed list.
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

// ---------- Fields ----------
$eyebrow = trim((string) ($args['deliverables_eyebrow'] ?? ''));
$heading = niyom_inline_text($args['deliverables_heading'] ?? '');

$box_label   = trim((string) ($args['boundaries_label'] ?? ''));
$box_heading = niyom_inline_text($args['boundaries_heading'] ?? '');
$box_text    = niyom_rich_text($args['boundaries_text'] ?? '');

// Clean rows; skip empty ones.
$items = [];
foreach ((array) ($args['deliverables_items'] ?? []) as $row) {
    $item = [
        'title' => niyom_inline_text($row['title'] ?? ''),
        'text'  => niyom_rich_text($row['text'] ?? ''),
    ];

    if ($item['title'] || $item['text']) {
        $items[] = $item;
    }
}

$box_items = [];
foreach ((array) ($args['boundaries_items'] ?? []) as $row) {
    $text = niyom_inline_text($row['text'] ?? '');

    if ($text) {
        $box_items[] = $text;
    }
}

$has_intro = $eyebrow !== '' || $heading;
$has_main  = $has_intro || $items;
$has_box   = $box_label !== '' || $box_heading || $box_text || $box_items;

if (!$has_main && !$has_box) {
    return;
}

$uid = wp_unique_id('rs-deliverables-');
?>

<section class="rs-deliverables"
    id="<?php echo esc_attr($uid); ?>"
    <?php echo $heading ? 'aria-labelledby="' . esc_attr($uid . '-title') . '"' : ''; ?>>
    <div class="rs-deliverables-inner">

        <?php if ($has_main) : ?>
            <div class="rs-deliverables-main">
                <?php if ($has_intro) : ?>
                    <div class="rs-deliverables-intro">
                        <?php if ($eyebrow !== '') : ?>
                            <p class="rs-deliverables-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                            <h2 class="rs-deliverables-heading heading-two" id="<?php echo esc_attr($uid . '-title'); ?>"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($items) : ?>
                    <ul class="rs-deliverables-list">
                        <?php foreach ($items as $item) : ?>
                            <li class="rs-deliverables-item">
                                <?php if ($item['title']) : ?>
                                    <h3 class="rs-deliverables-title lead-text"><?php echo $item['title']; // Escaped in niyom_inline_text(). ?></h3>
                                <?php endif; ?>

                                <?php if ($item['text']) : ?>
                                    <div class="rs-deliverables-text rich-text body-medium"><?php echo $item['text']; // Escaped in niyom_rich_text(). ?></div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($has_box) : ?>
            <aside class="rs-boundaries">
                <?php if ($box_label !== '') : ?>
                    <p class="rs-boundaries-label label-small"><?php echo esc_html($box_label); ?></p>
                <?php endif; ?>

                <?php if ($box_heading) : ?>
                    <h3 class="rs-boundaries-heading heading-four"><?php echo $box_heading; // Escaped in niyom_inline_text(). ?></h3>
                <?php endif; ?>

                <?php if ($box_text) : ?>
                    <div class="rs-boundaries-text rich-text body-medium"><?php echo $box_text; // Escaped in niyom_rich_text(). ?></div>
                <?php endif; ?>

                <?php if ($box_items) : ?>
                    <ul class="rs-boundaries-list">
                        <?php foreach ($box_items as $box_item) : ?>
                            <li class="rs-boundaries-item body-medium">
                                <span class="rs-boundaries-dash" aria-hidden="true">—</span>
                                <span class="rs-boundaries-item-text"><?php echo $box_item; // Escaped in niyom_inline_text(). ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </aside>
        <?php endif; ?>

    </div>
</section>

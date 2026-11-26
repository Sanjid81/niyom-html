<?php
/**
 * Expertise › Reproducibility & quality practices. Fields arrive in $args
 * (see inc/blocks/expertise-block.php).
 * Styles: src/styles/components/expertise/_practices.scss
 *
 * Deep Ink section: label + white H2 left, short text right; numbered
 * practices in a row (cyan number, title, text) under thin white rules.
 * Empty numbers are filled in automatically (01, 02 …).
 * Each part prints only when it has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim((string) ($args['practices_eyebrow'] ?? ''));
$heading = niyom_inline_text($args['practices_heading'] ?? '');
$text    = niyom_rich_text($args['practices_text'] ?? '');

// Clean items; skip empty ones. Auto-number by position.
$items = [];
foreach ((array) ($args['practices_items'] ?? []) as $item) {
    $title     = trim((string) ($item['title'] ?? ''));
    $item_text = niyom_rich_text($item['text'] ?? '');
    if ($title === '' && !$item_text) {
        continue;
    }

    $number  = trim((string) ($item['number'] ?? ''));
    $items[] = [
        'number' => $number !== '' ? $number : sprintf('%02d', count($items) + 1),
        'title'  => $title,
        'text'   => $item_text,
    ];
}

$has_copy = $eyebrow !== '' || $heading;

if (!$has_copy && !$text && !$items) {
    return;
}
?>

<section class="ex-practices">
    <div class="ex-practices-inner">

        <?php if ($has_copy || $text) : ?>
            <div class="ex-practices-intro">
                <?php if ($has_copy) : ?>
                    <div class="ex-practices-copy">
                        <?php if ($eyebrow !== '') : ?>
                            <p class="ex-practices-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                            <h2 class="ex-practices-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="ex-practices-text rich-text body-large"><?php echo $text; // Escaped in niyom_rich_text(). ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($items) : ?>
            <ol class="ex-practices-list">
                <?php foreach ($items as $item) : ?>
                    <li class="ex-practices-item">
                        <span class="ex-practices-number" aria-hidden="true"><?php echo esc_html($item['number']); ?></span>

                        <?php if ($item['title'] !== '') : ?>
                            <h3 class="ex-practices-title lead-text"><?php echo esc_html($item['title']); ?></h3>
                        <?php endif; ?>

                        <?php if ($item['text']) : ?>
                            <div class="ex-practices-item-text rich-text body-medium"><?php echo $item['text']; // Escaped in niyom_rich_text(). ?></div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>

    </div>
</section>

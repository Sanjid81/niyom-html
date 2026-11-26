<?php
/**
 * Computational Biosciences › Areas ("Five areas of computational support").
 * Fields arrive in $args (see inc/blocks/computational-biosciences-block.php).
 * Styles: src/styles/components/computational-biosciences/_areas.scss
 *
 * Warm off-white section: label, heading and text on the left; numbered list
 * on the right, each row with a title, text and tag pills.
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim((string) ($args['areas_eyebrow'] ?? ''));
$heading = niyom_inline_text($args['areas_heading'] ?? '');
$text    = niyom_rich_text($args['areas_text'] ?? '');

// Clean rows; skip empty ones. Numbers follow the visible order.
$areas = [];
foreach ((array) ($args['areas_items'] ?? []) as $row) {
    $tags = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($row['tags'] ?? '')))));

    $area = [
        'number' => trim((string) ($row['number'] ?? '')),
        'title'  => niyom_inline_text($row['title'] ?? ''),
        'text'   => niyom_rich_text($row['text'] ?? ''),
        'tags'   => $tags,
    ];

    if ($area['title'] || $area['text'] || $area['tags']) {
        $area['number'] = $area['number'] !== '' ? $area['number'] : sprintf('%02d', count($areas) + 1);
        $areas[]        = $area;
    }
}

$has_intro = $eyebrow !== '' || $heading || $text;

if (!$has_intro && !$areas) {
    return;
}
?>

<section class="cb-areas">
    <div class="cb-areas-inner">

        <?php if ($has_intro) : ?>
            <div class="cb-areas-intro">
                <?php if ($eyebrow !== '') : ?>
                    <p class="cb-areas-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="cb-areas-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="cb-areas-text rich-text body-large"><?php echo $text; // Escaped in niyom_rich_text(). ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($areas) : ?>
            <ol class="cb-areas-list">
                <?php foreach ($areas as $area) : ?>
                    <li class="cb-areas-item">
                        <span class="cb-areas-number"><?php echo esc_html($area['number']); ?></span>

                        <div class="cb-areas-body">
                            <?php if ($area['title']) : ?>
                                <h3 class="cb-areas-title heading-four"><?php echo $area['title']; // Escaped in niyom_inline_text(). ?></h3>
                            <?php endif; ?>

                            <?php if ($area['text']) : ?>
                                <div class="cb-areas-item-text rich-text body-medium"><?php echo $area['text']; // Escaped in niyom_rich_text(). ?></div>
                            <?php endif; ?>

                            <?php if ($area['tags']) : ?>
                                <ul class="cb-areas-tags shared-tags">
                                    <?php foreach ($area['tags'] as $tag) : ?>
                                        <li class="shared-tag"><?php echo esc_html($tag); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>

    </div>
</section>

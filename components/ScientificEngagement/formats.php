<?php
/**
 * Scientific Engagement › Engagement formats. Fields arrive in $args
 * (see inc/blocks/scientific-engagement-block.php).
 * Styles: src/styles/components/scientific-engagement/_formats.scss
 *
 * Warm off-white section: label + heading, then a row of white cards:
 * image, number, title, text and "Suitable for:" tag pills.
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow    = trim((string) ($args['formats_eyebrow'] ?? ''));
$heading    = niyom_inline_text($args['formats_heading'] ?? '');
$tags_label = trim((string) ($args['formats_tags_label'] ?? ''));

// Clean card data; skip cards with nothing to show. Numbers follow the visible order.
$cards = [];
foreach ((array) ($args['formats_items'] ?? []) as $row) {
    $tags = array_filter(array_map('trim', preg_split('/\R/', (string) ($row['tags'] ?? ''))), 'strlen');

    $card = [
        'image'     => trim((string) ($row['image'] ?? '')),
        'image_alt' => trim((string) ($row['image_alt'] ?? '')),
        'number'    => trim((string) ($row['number'] ?? '')),
        'title'     => niyom_inline_text($row['title'] ?? ''),
        'text'      => niyom_rich_text($row['text'] ?? ''),
        'tags'      => $tags,
    ];

    if ($card['image'] || $card['title'] || $card['text'] || $card['tags']) {
        $card['number'] = $card['number'] !== '' ? $card['number'] : sprintf('%02d', count($cards) + 1);
        $cards[]        = $card;
    }
}

$has_intro = $eyebrow !== '' || $heading;

if (!$has_intro && !$cards) {
    return;
}
?>

<section class="se-formats">
    <div class="se-formats-inner">

        <?php if ($has_intro) : ?>
            <div class="se-formats-intro">
                <?php if ($eyebrow !== '') : ?>
                    <p class="se-formats-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="se-formats-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($cards) : ?>
            <ul class="se-formats-grid">
                <?php foreach ($cards as $card) : ?>
                    <li class="se-formats-card">
                        <?php if ($card['image']) : ?>
                            <div class="se-formats-media">
                                <img class="se-formats-image" src="<?php echo esc_url($card['image']); ?>" alt="<?php echo esc_attr($card['image_alt']); ?>" loading="lazy" decoding="async">
                            </div>
                        <?php endif; ?>

                        <div class="se-formats-body">
                            <span class="se-formats-number"><?php echo esc_html($card['number']); ?></span>

                            <?php if ($card['title']) : ?>
                                <h3 class="se-formats-title heading-four"><?php echo $card['title']; // Escaped in niyom_inline_text(). ?></h3>
                            <?php endif; ?>

                            <?php if ($card['text']) : ?>
                                <div class="se-formats-text rich-text body-medium"><?php echo $card['text']; // Escaped in niyom_rich_text(). ?></div>
                            <?php endif; ?>

                            <?php if ($card['tags']) : ?>
                                <div class="se-formats-tags">
                                    <?php if ($tags_label !== '') : ?>
                                        <span class="se-formats-tags-label"><?php echo esc_html($tags_label); ?></span>
                                    <?php endif; ?>
                                    <ul class="se-formats-tag-list shared-tags">
                                        <?php foreach ($card['tags'] as $tag) : ?>
                                            <li class="shared-tag"><?php echo esc_html($tag); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</section>

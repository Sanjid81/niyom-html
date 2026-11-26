<?php
/**
 * About "Research Integrity & Ethics" section. Fields arrive in $args (see inc/blocks/about-integrity.php).
 *
 * Deep Ink section with a faint background image. Left: eyebrow, heading,
 * text. Right: numbered commitments in two columns.
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

// ---------- Fields ----------
$bg      = $args['integrity_bg'] ?? '';
$eyebrow = trim($args['integrity_eyebrow'] ?? '');
$heading = niyom_inline_text($args['integrity_heading'] ?? '');
$text    = niyom_rich_text($args['integrity_text'] ?? '');

// Clean commitment data; skip empty ones. Numbers follow the visible order.
$items = [];
foreach ((array) ($args['integrity_items'] ?? []) as $row) {
    $item = [
        'number' => trim($row['number'] ?? ''),
        'title'  => trim($row['title'] ?? ''),
        'text'   => niyom_rich_text($row['text'] ?? ''),
    ];

    if ($item['title'] !== '' || $item['text']) {
        $item['number'] = $item['number'] !== '' ? $item['number'] : sprintf('%02d', count($items) + 1);
        $items[]        = $item;
    }
}

$has_intro = $eyebrow !== '' || $heading || $text;

if (!$has_intro && !$items) {
    return;
}

$uid = wp_unique_id('about-integrity-');
?>

<section class="about-integrity"
    id="<?php echo esc_attr($uid); ?>"
    <?php echo $heading ? 'aria-labelledby="' . esc_attr($uid . '-title') . '"' : ''; ?>>

    <?php if ($bg) : ?>
        <div class="about-integrity__bg" aria-hidden="true">
            <img class="about-integrity__bg-image" src="<?php echo esc_url($bg); ?>" alt="" loading="lazy" decoding="async">
        </div>
    <?php endif; ?>

    <div class="about-integrity__inner">

        <?php if ($has_intro) : ?>
            <div class="about-integrity__intro">
                <?php if ($eyebrow !== '') : ?>
                    <p class="about-integrity__eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="about-integrity__heading heading-two" id="<?php echo esc_attr($uid . '-title'); ?>"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="about-integrity__text rich-text body-large">
                        <?php echo $text; // Escaped in niyom_rich_text(). ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($items) : ?>
            <ol class="about-integrity__list">
                <?php foreach ($items as $item) : ?>
                    <li class="about-integrity__item">
                        <span class="about-integrity__number"><?php echo esc_html($item['number']); ?></span>

                        <?php if ($item['title'] !== '') : ?>
                            <h3 class="about-integrity__title"><?php echo esc_html($item['title']); ?></h3>
                        <?php endif; ?>

                        <?php if ($item['text']) : ?>
                            <div class="about-integrity__item-text rich-text">
                                <?php echo $item['text']; // Escaped in niyom_rich_text(). ?>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </div>
</section>

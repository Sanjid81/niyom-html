<?php
/**
 * About "Values & Philosophy" section. Fields arrive in $args (see inc/blocks/about-values.php).
 *
 * Intro (eyebrow + heading left, text right) and numbered value cards.
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

// ---------- Fields ----------
$eyebrow = trim($args['values_eyebrow'] ?? '');
$heading = niyom_inline_text($args['values_heading'] ?? '');
$text    = niyom_rich_text($args['values_text'] ?? '');

// Clean card data; skip empty cards. Numbers follow the visible order.
$cards = [];
foreach ((array) ($args['values_items'] ?? []) as $row) {
    $card = [
        'number' => trim($row['number'] ?? ''),
        'title'  => trim($row['title'] ?? ''),
        'text'   => niyom_rich_text($row['text'] ?? ''),
    ];

    if ($card['title'] !== '' || $card['text']) {
        $card['number'] = $card['number'] !== '' ? $card['number'] : sprintf('%02d', count($cards) + 1);
        $cards[]        = $card;
    }
}

$has_intro = $eyebrow !== '' || $heading || $text;

if (!$has_intro && !$cards) {
    return;
}

$uid = wp_unique_id('about-values-');
?>

<section class="about-values"
    id="<?php echo esc_attr($uid); ?>"
    <?php echo $heading ? 'aria-labelledby="' . esc_attr($uid . '-title') . '"' : ''; ?>>
    <div class="about-values__inner">

        <?php if ($has_intro) : ?>
            <div class="about-values__intro">
                <?php if ($eyebrow !== '' || $heading) : ?>
                    <div class="about-values__copy">
                        <?php if ($eyebrow !== '') : ?>
                            <p class="about-values__eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                            <h2 class="about-values__heading heading-two" id="<?php echo esc_attr($uid . '-title'); ?>"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="about-values__text rich-text body-large">
                        <?php echo $text; // Escaped in niyom_rich_text(). ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($cards) : ?>
            <ol class="about-values__grid" style="--cards: <?php echo (int) count($cards); ?>;">
                <?php foreach ($cards as $card) : ?>
                    <li class="about-values__card">
                        <span class="about-values__number"><?php echo esc_html($card['number']); ?></span>

                        <?php if ($card['title'] !== '' || $card['text']) : ?>
                            <div class="about-values__body">
                                <?php if ($card['title'] !== '') : ?>
                                    <h3 class="about-values__title"><?php echo esc_html($card['title']); ?></h3>
                                <?php endif; ?>
                                <?php if ($card['text']) : ?>
                                    <div class="about-values__card-text rich-text">
                                        <?php echo $card['text']; // Escaped in niyom_rich_text(). ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </div>
</section>

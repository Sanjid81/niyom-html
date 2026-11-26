<?php
/**
 * Shared › Cards section (e.g. "What you receive at handover"). Fields arrive in $args.
 * Styles: src/styles/components/shared/_cards.scss · Docs: components/Shared/README.md
 *
 * Intro (label + heading left, paragraph right) and a 3-column grid of Mist
 * cards: icon, title, text. Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim((string) ($args['cards_eyebrow'] ?? ''));
$heading = niyom_inline_text($args['cards_heading'] ?? '');
$text    = niyom_rich_text($args['cards_text'] ?? '');

// Clean card data; skip cards with nothing to show.
$cards = [];
foreach ((array) ($args['cards_items'] ?? []) as $row) {
    $card = [
        'icon'  => trim((string) ($row['icon'] ?? '')),
        'title' => niyom_inline_text($row['title'] ?? ''),
        'text'  => niyom_rich_text($row['text'] ?? ''),
    ];

    if ($card['icon'] || $card['title'] || $card['text']) {
        $cards[] = $card;
    }
}

$has_copy  = $eyebrow !== '' || $heading;
$has_intro = $has_copy || $text;

if (!$has_intro && !$cards) {
    return;
}
?>

<section class="shared-cards">
    <div class="shared-cards-inner">

        <?php if ($has_intro) : ?>
            <div class="shared-cards-intro">
                <?php if ($has_copy) : ?>
                    <div class="shared-cards-copy">
                        <?php if ($eyebrow !== '') : ?>
                            <p class="shared-cards-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                            <h2 class="shared-cards-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="shared-cards-text rich-text body-large"><?php echo $text; // Escaped in niyom_rich_text(). ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($cards) : ?>
            <ul class="shared-cards-grid">
                <?php foreach ($cards as $card) : ?>
                    <li class="shared-cards-item">
                        <?php if ($card['icon']) : ?>
                            <span class="shared-cards-icon" aria-hidden="true">
                                <?php echo niyom_media_icon($card['icon'], 'shared-cards-icon-svg'); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ($card['title'] || $card['text']) : ?>
                            <div class="shared-cards-body">
                                <?php if ($card['title']) : ?>
                                    <h3 class="shared-cards-title lead-text"><?php echo $card['title']; // Escaped in niyom_inline_text(). ?></h3>
                                <?php endif; ?>

                                <?php if ($card['text']) : ?>
                                    <div class="shared-cards-item-text rich-text body-medium"><?php echo $card['text']; // Escaped in niyom_rich_text(). ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</section>

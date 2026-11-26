<?php
/**
 * Scientific Engagement › Resources & event recaps. Fields arrive in $args
 * (see inc/blocks/scientific-engagement-block.php).
 * Styles: src/styles/components/scientific-engagement/_resources.scss
 *
 * Intro (label + heading left, short note right) and a row of cards:
 * image, type · date, title, text and a "Read more" link. When a card has a
 * link, the title link stretches over the whole card.
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim((string) ($args['resources_eyebrow'] ?? ''));
$heading = niyom_inline_text($args['resources_heading'] ?? '');
$text    = niyom_rich_text($args['resources_text'] ?? '');

// Clean card data; skip cards with nothing to show.
$cards = [];
foreach ((array) ($args['resources_items'] ?? []) as $row) {
    $card = [
        'image'     => trim((string) ($row['image'] ?? '')),
        'image_alt' => trim((string) ($row['image_alt'] ?? '')),
        'type'      => trim((string) ($row['type'] ?? '')),
        'date'      => trim((string) ($row['date'] ?? '')),
        'title'     => niyom_inline_text($row['title'] ?? ''),
        'text'      => niyom_rich_text($row['text'] ?? ''),
        'link'      => trim((string) ($row['link'] ?? '')),
        'link_text' => trim((string) ($row['link_text'] ?? '')),
        'new_tab'   => !empty($row['new_tab']),
    ];

    if ($card['image'] || $card['type'] || $card['date'] || $card['title'] || $card['text']) {
        $cards[] = $card;
    }
}

$has_copy  = $eyebrow !== '' || $heading;
$has_intro = $has_copy || $text;

if (!$has_intro && !$cards) {
    return;
}
?>

<section class="se-resources">
    <div class="se-resources-inner">

        <?php if ($has_intro) : ?>
            <div class="se-resources-intro">
                <?php if ($has_copy) : ?>
                    <div class="se-resources-copy">
                        <?php if ($eyebrow !== '') : ?>
                            <p class="se-resources-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                            <h2 class="se-resources-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="se-resources-text rich-text body-medium"><?php echo $text; // Escaped in niyom_rich_text(). ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($cards) : ?>
            <ul class="se-resources-grid">
                <?php foreach ($cards as $card) :
                    $has_link = $card['link'] !== '';
                    // The title carries the card link; without a title, "Read more" does.
                    $more_is_link = $has_link && !$card['title'];
                    ?>
                    <li class="se-resources-card<?php echo $has_link ? ' is-linked' : ''; ?>">
                        <?php if ($card['image']) : ?>
                            <div class="se-resources-media">
                                <img class="se-resources-image" src="<?php echo esc_url($card['image']); ?>" alt="<?php echo esc_attr($card['image_alt']); ?>" loading="lazy" decoding="async">
                            </div>
                        <?php endif; ?>

                        <?php if ($card['type'] || $card['date']) : ?>
                            <p class="se-resources-meta">
                                <?php if ($card['type']) : ?>
                                    <span class="se-resources-type"><?php echo esc_html($card['type']); ?></span>
                                <?php endif; ?>
                                <?php if ($card['date']) : ?>
                                    <span class="se-resources-date"><?php echo esc_html($card['date']); ?></span>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($card['title']) : ?>
                            <h3 class="se-resources-title heading-five">
                                <?php if ($has_link) : ?>
                                    <a class="se-resources-link" <?php echo niyom_link_attrs($card['link'], $card['new_tab']); ?>><?php echo $card['title']; // Escaped in niyom_inline_text(). ?></a>
                                <?php else : ?>
                                    <?php echo $card['title']; // Escaped in niyom_inline_text(). ?>
                                <?php endif; ?>
                            </h3>
                        <?php endif; ?>

                        <?php if ($card['text']) : ?>
                            <div class="se-resources-card-text rich-text body-medium"><?php echo $card['text']; // Escaped in niyom_rich_text(). ?></div>
                        <?php endif; ?>

                        <?php if ($has_link && $card['link_text'] !== '') : ?>
                            <?php if ($more_is_link) : ?>
                                <a class="link-arrow se-resources-more se-resources-link" <?php echo niyom_link_attrs($card['link'], $card['new_tab']); ?>>
                            <?php else : ?>
                                <span class="link-arrow se-resources-more" aria-hidden="true">
                            <?php endif; ?>
                                <span><?php echo esc_html($card['link_text']); ?></span>
                                <?php echo niyom_icon('arrow-right', 'link-arrow-icon link-arrow-icon--lg'); ?>
                                <?php echo niyom_icon('arrow-right-sm', 'link-arrow-icon link-arrow-icon--sm'); ?>
                            <?php echo $more_is_link ? '</a>' : '</span>'; ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</section>

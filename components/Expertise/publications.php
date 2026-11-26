<?php
/**
 * Expertise › Selected publications. Fields arrive in $args
 * (see inc/blocks/expertise-block.php).
 * Styles: src/styles/components/expertise/_publications.scss
 *
 * Label + H2; bordered cards two per row: type label, title, citation line
 * and a "View publication ⟶" link. Without a link the label is shown as
 * plain text (placeholder content). Each part prints only when it has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim((string) ($args['publications_eyebrow'] ?? ''));
$heading = niyom_inline_text($args['publications_heading'] ?? '');

// Clean cards; skip empty ones.
$cards = [];
foreach ((array) ($args['publications_items'] ?? []) as $row) {
    $card = [
        'type'      => trim((string) ($row['type'] ?? '')),
        'title'     => niyom_inline_text($row['title'] ?? ''),
        'meta'      => niyom_inline_text($row['meta'] ?? ''),
        'link'      => trim((string) ($row['link'] ?? '')),
        'link_text' => trim((string) ($row['link_text'] ?? '')),
        'new_tab'   => !empty($row['new_tab']),
    ];

    if ($card['type'] !== '' || $card['title'] || $card['meta']) {
        $cards[] = $card;
    }
}

if ($eyebrow === '' && !$heading && !$cards) {
    return;
}
?>

<section class="ex-publications">
    <div class="ex-publications-inner">

        <?php if ($eyebrow !== '' || $heading) : ?>
            <div class="ex-publications-intro">
                <?php if ($eyebrow !== '') : ?>
                    <p class="ex-publications-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="ex-publications-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($cards) : ?>
            <ul class="ex-publications-grid">
                <?php foreach ($cards as $card) : ?>
                    <li class="ex-publications-card">
                        <?php if ($card['type'] !== '') : ?>
                            <p class="ex-publications-type"><?php echo esc_html($card['type']); ?></p>
                        <?php endif; ?>

                        <?php if ($card['title']) : ?>
                            <h3 class="ex-publications-title heading-five"><?php echo $card['title']; // Escaped in niyom_inline_text(). ?></h3>
                        <?php endif; ?>

                        <?php if ($card['meta']) : ?>
                            <p class="ex-publications-meta body-medium"><?php echo $card['meta']; // Escaped in niyom_inline_text(). ?></p>
                        <?php endif; ?>

                        <?php if ($card['link_text'] !== '') : ?>
                            <?php if ($card['link'] !== '') : ?>
                                <a class="link-arrow ex-publications-link" <?php echo niyom_link_attrs($card['link'], $card['new_tab']); ?>>
                            <?php else : ?>
                                <span class="link-arrow ex-publications-link is-static">
                            <?php endif; ?>
                                <span><?php echo esc_html($card['link_text']); ?></span>
                                <?php echo niyom_icon('arrow-right', 'link-arrow-icon link-arrow-icon--lg'); ?>
                                <?php echo niyom_icon('arrow-right-sm', 'link-arrow-icon link-arrow-icon--sm'); ?>
                            <?php echo $card['link'] !== '' ? '</a>' : '</span>'; ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</section>

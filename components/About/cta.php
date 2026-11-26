<?php
/**
 * About › CTA markup. Fields arrive in $args (see inc/blocks/about-cta.php).
 * Styles: src/styles/components/about/cta.scss
 *
 * Centred eyebrow, heading and up to two buttons (primary, secondary).
 * Each part prints only when it has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim($args['cta_eyebrow'] ?? '');
$heading = niyom_inline_text($args['cta_heading'] ?? '');

// Buttons: [style => fields]. A button needs both text and a link.
$buttons = [];
foreach (['primary', 'secondary'] as $style) {
    $text = trim($args["cta_{$style}_text"] ?? '');
    $link = trim($args["cta_{$style}_link"] ?? '');
    if ($text !== '' && $link !== '') {
        $buttons[] = [
            'style'   => $style,
            'text'    => $text,
            'link'    => $link,
            'new_tab' => !empty($args["cta_{$style}_new_tab"]),
        ];
    }
}

if (!$eyebrow && !$heading && !$buttons) {
    return;
}
?>

<section class="about-cta">
    <div class="about-cta__inner">

        <?php if ($eyebrow) : ?>
            <p class="about-cta__eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
        <?php endif; ?>

        <?php if ($heading) : ?>
            <h2 class="about-cta__heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
        <?php endif; ?>

        <?php if ($buttons) : ?>
            <div class="about-cta__buttons">
                <?php foreach ($buttons as $button) : ?>
                    <a class="btn btn--<?php echo esc_attr($button['style']); ?> about-cta__button" <?php echo niyom_link_attrs($button['link'], $button['new_tab']); ?>>
                        <span><?php echo esc_html($button['text']); ?></span>
                        <?php echo niyom_icon('arrow-right', 'btn__arrow btn__arrow--lg'); ?>
                        <?php echo niyom_icon('arrow-right-sm', 'btn__arrow btn__arrow--sm'); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

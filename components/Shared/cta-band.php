<?php
/**
 * Shared › Teal CTA band. Fields arrive in $args.
 * Styles: src/styles/components/shared/_cta-band.scss · Docs: components/Shared/README.md
 *
 * Research Teal band with a faint network image: label and large white
 * heading on the left, up to two buttons on the right (white "inverse" and
 * white-outline). Each part prints only when it has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim((string) ($args['cta_band_eyebrow'] ?? ''));
$heading = niyom_inline_text($args['cta_band_heading'] ?? '');
$image   = trim((string) ($args['cta_band_image'] ?? ''));

// Buttons: primary = white (inverse), secondary = white outline. Need text + link.
$styles  = ['primary' => 'inverse', 'secondary' => 'inverse-outline'];
$buttons = [];
foreach ($styles as $key => $style) {
    $button_text = trim((string) ($args["cta_band_{$key}_text"] ?? ''));
    $button_link = trim((string) ($args["cta_band_{$key}_link"] ?? ''));
    if ($button_text !== '' && $button_link !== '') {
        $buttons[] = [
            'style'   => $style,
            'text'    => $button_text,
            'link'    => $button_link,
            'new_tab' => !empty($args["cta_band_{$key}_new_tab"]),
        ];
    }
}

if ($eyebrow === '' && !$heading && !$buttons) {
    return;
}
?>

<section class="shared-cta-band">
    <?php if ($image) : ?>
        <img class="shared-cta-band-bg" src="<?php echo esc_url($image); ?>" alt="" loading="lazy" decoding="async">
    <?php endif; ?>

    <div class="shared-cta-band-inner">

        <?php if ($eyebrow !== '' || $heading) : ?>
            <div class="shared-cta-band-copy">
                <?php if ($eyebrow !== '') : ?>
                    <p class="shared-cta-band-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="shared-cta-band-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($buttons) : ?>
            <div class="shared-cta-band-buttons">
                <?php foreach ($buttons as $button) : ?>
                    <a class="btn btn--<?php echo esc_attr($button['style']); ?> shared-cta-band-button" <?php echo niyom_link_attrs($button['link'], $button['new_tab']); ?>>
                        <span><?php echo esc_html($button['text']); ?></span>
                        <?php echo niyom_icon('arrow-right', 'btn-arrow btn-arrow--lg'); ?>
                        <?php echo niyom_icon('arrow-right-sm', 'btn-arrow btn-arrow--sm'); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

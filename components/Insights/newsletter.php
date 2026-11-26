<?php
/**
 * Insights › Newsletter band. Fields arrive in $args (see inc/blocks/insights-block.php).
 * Figma: Insights › 08 — CTA (303:7563, "Subscribe form" variant of the CTA
 * band 303:8294). Teal band with the network image: label + white H2 left,
 * email field + SUBSCRIBE and a small note right.
 * Form: Contact Form 7 "Newsletter" (classes newsletter-form, newsletter-input,
 * newsletter-submit), rendered from the shortcode field.
 * Styles: src/styles/components/insights/_newsletter.scss
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow   = trim((string) ($args['newsletter_eyebrow'] ?? ''));
$heading   = niyom_inline_text($args['newsletter_heading'] ?? '');
$image     = trim((string) ($args['newsletter_image'] ?? ''));
$note      = trim((string) ($args['newsletter_note'] ?? ''));
$shortcode = trim((string) ($args['newsletter_form'] ?? ''));
$form      = $shortcode !== '' ? trim(do_shortcode($shortcode)) : '';

if ($eyebrow === '' && !$heading && !$form) {
    return;
}

$heading_id = wp_unique_id('in-newsletter-heading-');
?>

<section class="in-newsletter"<?php echo $heading ? ' aria-labelledby="' . esc_attr($heading_id) . '"' : ''; ?>>
    <?php if ($image !== '') : ?>
        <img class="in-newsletter-bg" src="<?php echo esc_url($image); ?>" alt="" loading="lazy" decoding="async">
    <?php endif; ?>

    <div class="in-newsletter-inner">
        <?php if ($eyebrow !== '' || $heading) : ?>
            <div class="in-newsletter-copy">
                <?php if ($eyebrow !== '') : ?>
                    <p class="in-newsletter-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="in-newsletter-heading heading-two" id="<?php echo esc_attr($heading_id); ?>"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($form) : ?>
            <div class="in-newsletter-form">
                <?php echo $form; // Contact Form 7 output (escaped by the plugin). ?>

                <?php if ($note !== '') : ?>
                    <p class="in-newsletter-note"><?php echo esc_html($note); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

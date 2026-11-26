<?php
/**
 * Home Contact section ("Warm gallery — Research contact").
 * Fields arrive in $args (see inc/blocks/home-contact.php).
 * Left: invitation + image + contact blocks. Right: Contact Form 7 panel.
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim($args['contact_eyebrow'] ?? '');
$heading = niyom_inline_text($args['contact_heading'] ?? '');
$text    = niyom_rich_text($args['contact_text'] ?? '');
$image   = $args['contact_image'] ?? '';

// Contact blocks: skip rows with nothing to show.
$blocks = [];
foreach ((array) ($args['contact_blocks'] ?? []) as $row) {
    $block = [
        'kicker'  => trim($row['kicker'] ?? ''),
        'details' => niyom_rich_text($row['details'] ?? ''),
    ];
    if ($block['kicker'] !== '' || $block['details']) {
        $blocks[] = $block;
    }
}

// Form: render the shortcode (empty field or empty output = no panel).
$shortcode = trim($args['contact_form'] ?? '');
$form      = $shortcode !== '' ? trim(do_shortcode($shortcode)) : '';

$has_intro   = $eyebrow || $heading || $text;
$has_gallery = $image || $blocks;

if (!$has_intro && !$has_gallery && !$form) {
    return;
}

$heading_id = wp_unique_id('home-contact-heading-');
?>

<section class="home-contact<?php echo $form ? ' home-contact--has-form' : ''; ?>" id="contact"<?php echo $heading ? ' aria-labelledby="' . esc_attr($heading_id) . '"' : ''; ?>>
    <div class="home-contact-inner">

        <?php if ($has_intro || $has_gallery) : ?>
            <div class="home-contact-info">

                <?php if ($has_intro) : ?>
                    <div class="home-contact-intro">
                        <?php if ($eyebrow) : ?>
                            <p class="home-contact-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                            <h2 class="home-contact-heading heading-two" id="<?php echo esc_attr($heading_id); ?>"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                        <?php endif; ?>

                        <?php if ($text) : ?>
                            <div class="home-contact-text rich-text body-large">
                                <?php echo $text; // Escaped in niyom_rich_text(). ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($has_gallery) : ?>
                    <div class="home-contact-gallery<?php echo $image ? ' has-image' : ''; ?>">
                        <?php if ($image) : ?>
                            <div class="home-contact-media">
                                <img class="home-contact-image" src="<?php echo esc_url($image); ?>" alt="" width="280" height="310" loading="lazy" decoding="async">
                            </div>
                        <?php endif; ?>

                        <?php if ($blocks) : ?>
                            <div class="home-contact-blocks">
                                <?php foreach ($blocks as $block) : ?>
                                    <div class="home-contact-block">
                                        <?php if ($block['kicker'] !== '') : ?>
                                            <p class="home-contact-kicker"><?php echo esc_html($block['kicker']); ?></p>
                                        <?php endif; ?>

                                        <?php if ($block['details']) : ?>
                                            <div class="home-contact-details rich-text">
                                                <?php echo $block['details']; // Escaped in niyom_rich_text(). ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        <?php if ($form) : ?>
            <div class="home-contact-form">
                <?php echo $form; // Contact Form 7 output (escaped by the plugin). ?>
            </div>
        <?php endif; ?>

    </div>
</section>

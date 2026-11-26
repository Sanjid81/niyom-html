<?php
/**
 * About › Bangladesh Roots & International Positioning markup. Fields arrive
 * in $args (see inc/blocks/about-roots.php). Styles: src/styles/components/about/roots.scss
 *
 * Image left, text right (image on top on small screens).
 * Each part prints only when it has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$image     = trim($args['roots_image'] ?? '');
$image_alt = trim($args['roots_image_alt'] ?? '');
$eyebrow   = trim($args['roots_eyebrow'] ?? '');
$heading   = niyom_inline_text($args['roots_heading'] ?? '');
$text      = niyom_rich_text($args['roots_text'] ?? '');

// Keep only facts that have a label or a value.
$facts = [];
foreach ((array) ($args['roots_facts'] ?? []) as $fact) {
    $label = trim($fact['label'] ?? '');
    $value = niyom_inline_text($fact['value'] ?? '');
    if ($label !== '' || $value !== '') {
        $facts[] = ['label' => $label, 'value' => $value];
    }
}

$has_content = $eyebrow || $heading || $text || $facts;

if (!$has_content && !$image) {
    return;
}

$classes = ['about-roots'];
if (!$image) {
    $classes[] = 'about-roots--no-image';
}
?>

<section class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <div class="about-roots__inner">

        <?php if ($image) : ?>
            <div class="about-roots__media">
                <img class="about-roots__image"
                    src="<?php echo esc_url($image); ?>"
                    alt="<?php echo esc_attr($image_alt); ?>"
                    loading="lazy"
                    decoding="async">
            </div>
        <?php endif; ?>

        <?php if ($has_content) : ?>
            <div class="about-roots__content">
                <?php if ($eyebrow) : ?>
                    <p class="about-roots__eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="about-roots__heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="about-roots__text rich-text body-large">
                        <?php echo $text; // Escaped in niyom_rich_text(). ?>
                    </div>
                <?php endif; ?>

                <?php if ($facts) : ?>
                    <dl class="about-roots__facts">
                        <?php foreach ($facts as $fact) : ?>
                            <div class="about-roots__fact">
                                <?php if ($fact['label'] !== '') : ?>
                                    <dt class="about-roots__fact-label"><?php echo esc_html($fact['label']); ?></dt>
                                <?php endif; ?>
                                <?php if ($fact['value'] !== '') : ?>
                                    <dd class="about-roots__fact-value"><?php echo $fact['value']; // Escaped in niyom_inline_text(). ?></dd>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

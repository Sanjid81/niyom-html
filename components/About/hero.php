<?php
/**
 * About › Hero markup. Fields arrive in $args (see inc/blocks/about-hero.php).
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow   = trim((string) ($args['hero_eyebrow'] ?? ''));
$title     = niyom_inline_text($args['hero_title'] ?? '');
$text      = niyom_rich_text($args['hero_text'] ?? '');
$image     = trim((string) ($args['hero_image'] ?? ''));
$image_alt = trim((string) ($args['hero_image_alt'] ?? ''));

$has_copy = $title || $text;

if (!$eyebrow && !$has_copy && !$image) {
    return;
}
?>

<section class="about-hero">
    <div class="about-hero__inner">

        <?php if ($eyebrow || $has_copy) : ?>
            <header class="about-hero__header">
                <?php if ($eyebrow) : ?>
                    <p class="about-hero__eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($has_copy) : ?>
                    <div class="about-hero__copy">
                        <?php if ($title) : ?>
                            <h1 class="about-hero__title"><?php echo $title; // Escaped in niyom_inline_text(). ?></h1>
                        <?php endif; ?>

                        <?php if ($text) : ?>
                            <div class="about-hero__text rich-text"><?php echo $text; // Escaped in niyom_rich_text(). ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <?php if ($image) : ?>
            <figure class="about-hero__media">
                <img class="about-hero__image" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($image_alt); ?>" fetchpriority="high" decoding="async">
            </figure>
        <?php endif; ?>

    </div>
</section>

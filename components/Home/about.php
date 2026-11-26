<?php
/**
 * Home › About NIYOM markup. Fields arrive in $args (see inc/blocks/home-about.php).
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$bg          = trim((string) ($args['about_bg_image'] ?? ''));
$eyebrow     = trim((string) ($args['about_eyebrow'] ?? ''));
$text        = niyom_inline_text($args['about_text'] ?? '');
$text_mobile = niyom_inline_text($args['about_text_mobile'] ?? '');
$btn_text    = trim((string) ($args['about_button_text'] ?? ''));
$btn_link    = trim((string) ($args['about_button_link'] ?? ''));
$new_tab     = !empty($args['about_button_new_tab']);
$has_button  = $btn_text !== '' && $btn_link !== '';

// The mobile statement replaces the main one up to 768px; either may stand alone.
$has_text = $text || $text_mobile;

if (!$eyebrow && !$has_text && !$has_button) {
    return;
}
?>

<section class="home-about<?php echo $bg ? ' has-image' : ''; ?>">
    <?php if ($bg) : ?>
        <div class="home-about__bg" aria-hidden="true">
            <img class="home-about__bg-image" src="<?php echo esc_url($bg); ?>" alt="" loading="lazy" decoding="async">
        </div>
    <?php endif; ?>

    <div class="home-about__inner">
        <?php if ($eyebrow) : ?>
            <p class="home-about__eyebrow"><?php echo esc_html($eyebrow); ?></p>
        <?php endif; ?>

        <?php if ($has_text) : ?>
            <?php
            // Desktop + mobile versions: CSS shows one of them.
            $desktop_class = $text_mobile && $text ? ' home-about__text--desktop' : '';
            ?>
            <?php if ($text) : ?>
                <p class="home-about__text<?php echo $desktop_class; ?>"><?php echo $text; // Escaped in niyom_inline_text(). ?></p>
            <?php endif; ?>
            <?php if ($text_mobile) : ?>
                <p class="home-about__text<?php echo $text ? ' home-about__text--mobile' : ''; ?>"><?php echo $text_mobile; // Escaped in niyom_inline_text(). ?></p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($has_button) : ?>
            <a class="btn btn--primary home-about__button" <?php echo niyom_link_attrs($btn_link, $new_tab); ?>>
                <span><?php echo esc_html($btn_text); ?></span>
                <?php echo niyom_icon('arrow-right', 'btn__arrow btn__arrow--lg'); ?>
                <?php echo niyom_icon('arrow-right-sm', 'btn__arrow btn__arrow--sm'); ?>
            </a>
        <?php endif; ?>
    </div>
</section>

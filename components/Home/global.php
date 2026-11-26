<?php
/**
 * Home › Global Perspective markup. Fields arrive in $args
 * (see inc/blocks/home-global.php). Styles: src/styles/components/home/global.scss
 *
 * Media: the image always prints (when set). If a video is set it plays
 * muted on a loop over the image; with "reduce motion" on, CSS hides the
 * video and the image shows instead. Each part prints only when it has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow     = trim($args['global_eyebrow'] ?? '');
$heading     = niyom_inline_text($args['global_heading'] ?? '');
$text        = niyom_rich_text($args['global_text'] ?? '');
$button_text = trim($args['global_button_text'] ?? '');
$button_link = trim($args['global_button_link'] ?? '');
$button_tab  = !empty($args['global_button_new_tab']);
$image       = trim($args['global_image'] ?? '');
$video       = trim($args['global_video'] ?? '');

$has_button  = $button_text !== '' && $button_link !== '';
$has_content = $eyebrow || $heading || $text || $has_button;
$has_media   = $image || $video;

if (!$has_content && !$has_media) {
    return;
}

$classes = ['home-global'];
if (!$has_media) {
    $classes[] = 'home-global--no-media';
}
?>

<section class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <div class="home-global__inner">

        <?php if ($has_content) : ?>
            <div class="home-global__content">
                <?php if ($eyebrow || $heading || $text) : ?>
                    <div class="home-global__header">
                        <?php if ($eyebrow) : ?>
                            <p class="home-global__eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                            <h2 class="home-global__heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                        <?php endif; ?>

                        <?php if ($text) : ?>
                            <div class="home-global__text rich-text body-large">
                                <?php echo $text; // Escaped in niyom_rich_text(). ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($has_button) : ?>
                    <a class="btn btn--primary home-global__button" <?php echo niyom_link_attrs($button_link, $button_tab); ?>>
                        <span><?php echo esc_html($button_text); ?></span>
                        <?php echo niyom_icon('arrow-right', 'btn__arrow btn__arrow--lg'); ?>
                        <?php echo niyom_icon('arrow-right-sm', 'btn__arrow btn__arrow--sm'); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($has_media) : ?>
            <div class="home-global__media<?php echo $video ? ' has-video' : ''; ?>">
                <?php if ($image) : ?>
                    <img class="home-global__image"
                        src="<?php echo esc_url($image); ?>"
                        alt=""
                        loading="lazy"
                        decoding="async">
                <?php endif; ?>

                <?php if ($video) : ?>
                    <video class="home-global__video"
                        src="<?php echo esc_url($video); ?>"
                        <?php if ($image) : ?>poster="<?php echo esc_url($image); ?>"<?php endif; ?>
                        autoplay
                        muted
                        loop
                        playsinline
                        preload="metadata"
                        aria-hidden="true"></video>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

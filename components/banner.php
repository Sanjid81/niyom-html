<?php
/**
 * Banner block markup. Fields arrive in $args (see inc/blocks.php).
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$image_id    = $args['banner_image'] ?? '';
$title       = $args['banner_title'] ?? '';
$text        = $args['banner_text'] ?? '';
$button_text = $args['banner_button_text'] ?? '';
$button_link = $args['banner_button_link'] ?? '';
?>

<section class="banner">
    <?php if ($image_id) : ?>
        <?php echo wp_get_attachment_image($image_id, 'full', false, ['class' => 'banner__image', 'loading' => 'eager']); ?>
    <?php endif; ?>
    <div class="banner__overlay"></div>

    <div class="container banner__content">
        <?php if ($title) : ?>
            <h1 class="banner__title"><?php echo esc_html($title); ?></h1>
        <?php endif; ?>

        <?php if ($text) : ?>
            <p class="banner__text"><?php echo esc_html($text); ?></p>
        <?php endif; ?>

        <?php if ($button_text && $button_link) : ?>
            <a class="btn btn--primary" href="<?php echo esc_url($button_link); ?>">
                <?php echo esc_html($button_text); ?>
            </a>
        <?php endif; ?>
    </div>
</section>

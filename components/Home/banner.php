<?php
/**
 * Home Banner block markup. Fields arrive in $args (see inc/blocks/home-block.php).
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$image        = $args['banner_image'] ?? '';
$image_mobile = $args['banner_image_mobile'] ?? '';
$title        = niyom_inline_text($args['banner_title'] ?? '');
$text         = niyom_rich_text($args['banner_text'] ?? '');
$button_text  = trim($args['banner_button_text'] ?? '');
$button_link  = trim($args['banner_button_link'] ?? '');
$new_tab      = !empty($args['banner_button_new_tab']);

// Keep only stages that have a label.
$stages = array_values(array_filter((array) ($args['banner_stages'] ?? []), function ($stage) {
    return isset($stage['label']) && trim($stage['label']) !== '';
}));

$has_button  = $button_text !== '' && $button_link !== '';
$has_aside   = $text || $has_button;
$has_content = $title || $has_aside || $stages;

// Nothing to show at all.
if (!$has_content && !$image && !$image_mobile) {
    return;
}
?>

<section class="home-banner<?php echo $image || $image_mobile ? ' home-banner--has-image' : ''; ?>">

    <?php if ($image || $image_mobile) : ?>
        <picture class="home-banner__media">
            <?php if ($image_mobile && $image) : ?>
                <source media="(max-width: 768px)" srcset="<?php echo esc_url($image_mobile); ?>">
            <?php endif; ?>
            <img
                class="home-banner__image"
                src="<?php echo esc_url($image ?: $image_mobile); ?>"
                alt=""
                fetchpriority="high"
                decoding="async">
        </picture>
    <?php endif; ?>

    <div class="home-banner__inner">

        <?php if ($title || $has_aside) : ?>
            <div class="home-banner__content">

                <?php if ($title) : ?>
                    <h1 class="home-banner__title display-one"><?php echo $title; // Escaped in niyom_inline_text(). ?></h1>
                <?php endif; ?>

                <?php if ($has_aside) : ?>
                    <div class="home-banner__aside">
                        <?php if ($text) : ?>
                            <div class="home-banner__text rich-text body-large">
                                <?php echo $text; // Escaped in niyom_rich_text(). ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($has_button) : ?>
                            <a class="btn btn--inverse home-banner__button"
                                href="<?php echo esc_url($button_link); ?>"
                                <?php echo $new_tab ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                <span><?php echo esc_html($button_text); ?></span>
                                <?php echo niyom_icon('arrow-right', 'btn__arrow btn__arrow--lg'); ?>
                                <?php echo niyom_icon('arrow-right-sm', 'btn__arrow btn__arrow--sm'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        <?php if ($stages) : ?>
            <ol class="home-banner__stages">
                <?php foreach ($stages as $stage) : ?>
                    <li class="home-banner__stage<?php echo !empty($stage['active']) ? ' is-active' : ''; ?>">
                        <span class="home-banner__stage-label"><?php echo esc_html($stage['label']); ?></span>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>

    </div>
</section>

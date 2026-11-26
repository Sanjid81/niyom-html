<?php
/**
 * Home Banner slider markup. Fields arrive in $args (see inc/blocks/home-block.php).
 * Slider behaviour: src/scripts/components/home-banner.js (Swiper, fade).
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

// Build clean slide data; skip slides with nothing to show.
$slides = [];
foreach ((array) ($args['banner_slides'] ?? []) as $row) {
    $slide = [
        'label'        => trim($row['tab_label'] ?? ''),
        'image'        => $row['image'] ?? '',
        'image_mobile' => $row['image_mobile'] ?? '',
        'title'        => niyom_inline_text($row['title'] ?? ''),
        'text'         => niyom_rich_text($row['text'] ?? ''),
        'button_text'  => trim($row['button_text'] ?? ''),
        'button_link'  => trim($row['button_link'] ?? ''),
        'new_tab'      => !empty($row['button_new_tab']),
    ];

    $slide['has_button'] = $slide['button_text'] !== '' && $slide['button_link'] !== '';

    if ($slide['image'] || $slide['image_mobile'] || $slide['title'] || $slide['text'] || $slide['has_button']) {
        $slides[] = $slide;
    }
}

if (!$slides) {
    return;
}

$count     = count($slides);
$autoplay  = !empty($args['banner_autoplay']);
$delay     = max(2, (float) ($args['banner_delay'] ?? 6));
$has_tabs  = $count > 1;
$uid       = wp_unique_id('home-banner-');
?>

<section class="home-banner<?php echo $has_tabs ? ' home-banner--has-tabs' : ''; ?>"
    id="<?php echo esc_attr($uid); ?>"
    data-home-banner
    data-autoplay="<?php echo $autoplay && $has_tabs ? 'true' : 'false'; ?>"
    data-delay="<?php echo esc_attr($delay * 1000); ?>"
    aria-roledescription="carousel"
    aria-label="<?php esc_attr_e('Featured', 'niyom'); ?>">

    <div class="swiper home-banner-slider">
        <div class="swiper-wrapper">
            <?php foreach ($slides as $i => $slide) :
                $has_image = $slide['image'] || $slide['image_mobile'];
                $has_aside = $slide['text'] || $slide['has_button'];
                $slide_id  = $uid . '-slide-' . ($i + 1);
                ?>
                <div class="swiper-slide home-banner-slide<?php echo $has_image ? ' has-image' : ''; ?><?php echo $i === 0 ? ' is-active' : ''; ?>"
                    id="<?php echo esc_attr($slide_id); ?>"
                    <?php if ($has_tabs) : ?>
                        role="tabpanel"
                        aria-labelledby="<?php echo esc_attr($slide_id . '-tab'); ?>"
                    <?php endif; ?>
                    aria-roledescription="slide"
                    aria-label="<?php echo esc_attr(sprintf(__('%1$d of %2$d', 'niyom'), $i + 1, $count)); ?>">

                    <?php if ($has_image) : ?>
                        <picture class="home-banner-media">
                            <?php if ($slide['image_mobile'] && $slide['image']) : ?>
                                <source media="(max-width: 768px)" srcset="<?php echo esc_url($slide['image_mobile']); ?>">
                            <?php endif; ?>
                            <img
                                class="home-banner-image"
                                src="<?php echo esc_url($slide['image'] ?: $slide['image_mobile']); ?>"
                                alt=""
                                decoding="async"
                                <?php echo $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
                        </picture>
                    <?php endif; ?>

                    <?php if ($slide['title'] || $has_aside) : ?>
                        <div class="home-banner-inner">
                            <div class="home-banner-content">

                                <?php if ($slide['title']) : ?>
                                    <?php $tag = $i === 0 ? 'h1' : 'h2'; ?>
                                    <<?php echo $tag; ?> class="home-banner-title display-one"><?php echo $slide['title']; // Escaped in niyom_inline_text(). ?></<?php echo $tag; ?>>
                                <?php endif; ?>

                                <?php if ($has_aside) : ?>
                                    <div class="home-banner-aside">
                                        <?php if ($slide['text']) : ?>
                                            <div class="home-banner-text rich-text body-large">
                                                <?php echo $slide['text']; // Escaped in niyom_rich_text(). ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($slide['has_button']) : ?>
                                            <a class="btn btn--inverse home-banner-button"
                                                href="<?php echo esc_url($slide['button_link']); ?>"
                                                <?php echo $slide['new_tab'] ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                                <span><?php echo esc_html($slide['button_text']); ?></span>
                                                <?php echo niyom_icon('arrow-right', 'btn-arrow btn-arrow--lg'); ?>
                                                <?php echo niyom_icon('arrow-right-sm', 'btn-arrow btn-arrow--sm'); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($has_tabs) : ?>
        <?php $visible_tabs = min(3, $count); // Tabs shown at once on desktop. ?>
        <div class="home-banner-tabs-wrap">
            <div class="home-banner-tabs-viewport">
            <div class="home-banner-tabs"
                role="tablist"
                aria-label="<?php esc_attr_e('Banner slides', 'niyom'); ?>"
                style="--tabs-visible: <?php echo (int) $visible_tabs; ?>;">
                <?php foreach ($slides as $i => $slide) :
                    $slide_id = $uid . '-slide-' . ($i + 1);
                    $label    = $slide['label'] !== '' ? $slide['label'] : sprintf(__('Slide %d', 'niyom'), $i + 1);
                    $classes  = ['home-banner-tab'];
                    if ($i === 0) {
                        $classes[] = 'is-active';
                        $classes[] = 'is-pos-first';
                    }
                    if ($i === $visible_tabs - 1) {
                        $classes[] = 'is-pos-last';
                    }
                    if ($slide['label'] === '') {
                        $classes[] = 'is-unlabelled';
                    }
                    ?>
                    <button type="button"
                        class="<?php echo esc_attr(implode(' ', $classes)); ?>"
                        id="<?php echo esc_attr($slide_id . '-tab'); ?>"
                        role="tab"
                        aria-controls="<?php echo esc_attr($slide_id); ?>"
                        aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                        tabindex="<?php echo $i === 0 ? '0' : '-1'; ?>"
                        data-slide="<?php echo esc_attr($i); ?>">
                        <span class="home-banner-tab-progress" aria-hidden="true"></span>
                        <span class="home-banner-tab-label"><?php echo esc_html($label); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
            </div>
        </div>
    <?php endif; ?>
</section>

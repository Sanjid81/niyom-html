<?php
/**
 * Home Expertise section. Fields arrive in $args (see inc/blocks/home-expertise.php).
 *
 * Left: eyebrow, heading, text, button. Right: Swiper slider of Expertise
 * posts with a "‹ 01 / 03 ›" pager (src/scripts/components/home-expertise.js).
 * Each part prints only when it has content. No Expertise posts = no slider.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

// ---------- Fields ----------
$bg          = $args['expertise_bg'] ?? '';
$eyebrow     = trim($args['expertise_eyebrow'] ?? '');
$heading     = niyom_inline_text($args['expertise_heading'] ?? '');
$text        = niyom_rich_text($args['expertise_text'] ?? '');
$button_text = trim($args['expertise_button_text'] ?? '');
$button_link = trim($args['expertise_button_link'] ?? '');
$new_tab     = !empty($args['expertise_button_new_tab']);
$has_button  = $button_text !== '' && $button_link !== '';

$count = (int) ($args['expertise_count'] ?? 6);
$count = $count > 0 ? min($count, 24) : 6;
$order = ($args['expertise_order'] ?? 'menu_order') === 'date' ? 'date' : 'menu_order';

// ---------- Cards (Expertise posts) ----------
$cards = get_posts([
    'post_type'        => 'expertise',
    'post_status'      => 'publish',
    'posts_per_page'   => $count,
    'orderby'          => $order === 'date' ? ['date' => 'DESC'] : ['menu_order' => 'ASC', 'date' => 'DESC'],
    'suppress_filters' => false,
]);

$has_intro = $eyebrow !== '' || $heading || $text || $has_button;

if (!$has_intro && !$cards) {
    return;
}

$max_chips = 4; // Chips shown per card; the rest become "+N more" (Figma).
$uid       = wp_unique_id('home-expertise-');
?>

<section class="home-expertise<?php echo $cards ? '' : ' home-expertise--no-cards'; ?>"
    id="<?php echo esc_attr($uid); ?>"
    <?php echo $heading ? 'aria-labelledby="' . esc_attr($uid . '-title') . '"' : ''; ?>
    data-home-expertise>

    <?php if ($bg) : ?>
        <div class="home-expertise-bg" aria-hidden="true">
            <img class="home-expertise-bg-image" src="<?php echo esc_url($bg); ?>" alt="" loading="lazy" decoding="async">
        </div>
    <?php endif; ?>

    <div class="home-expertise-inner">

        <?php if ($has_intro) : ?>
            <div class="home-expertise-intro">
                <?php if ($eyebrow !== '' || $heading || $text) : ?>
                    <div class="home-expertise-copy">
                        <?php if ($eyebrow !== '') : ?>
                            <p class="home-expertise-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                            <h2 class="home-expertise-heading heading-two" id="<?php echo esc_attr($uid . '-title'); ?>"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                        <?php endif; ?>

                        <?php if ($text) : ?>
                            <div class="home-expertise-text rich-text body-large">
                                <?php echo $text; // Escaped in niyom_rich_text(). ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($has_button) : ?>
                    <a class="btn btn--primary home-expertise-button" <?php echo niyom_link_attrs($button_link, $new_tab); ?>>
                        <span><?php echo esc_html($button_text); ?></span>
                        <?php echo niyom_icon('arrow-right', 'btn-arrow btn-arrow--lg'); ?>
                        <?php echo niyom_icon('arrow-right-sm', 'btn-arrow btn-arrow--sm'); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($cards) : ?>
            <div class="home-expertise-slider-wrap">
                <div class="swiper home-expertise-slider"
                    aria-roledescription="carousel"
                    aria-label="<?php echo esc_attr($heading ? wp_strip_all_tags($heading) : __('Expertise', 'niyom')); ?>">
                    <div class="swiper-wrapper">
                        <?php foreach ($cards as $i => $card) :
                            $title   = get_the_title($card);
                            $excerpt = get_the_excerpt($card);
                            $thumb   = get_post_thumbnail_id($card);
                            $terms   = get_the_terms($card, 'expertise_tag');
                            $terms   = is_array($terms) ? $terms : [];
                            $extra   = count($terms) - $max_chips;
                            ?>
                            <article class="swiper-slide home-expertise-card"
                                aria-roledescription="slide"
                                aria-label="<?php echo esc_attr(sprintf(__('%1$d of %2$d', 'niyom'), $i + 1, count($cards))); ?>">

                                <?php if ($thumb) : ?>
                                    <div class="home-expertise-card-media">
                                        <?php echo wp_get_attachment_image($thumb, 'large', false, [
                                            'class'   => 'home-expertise-card-image',
                                            'alt'     => '',
                                            'loading' => 'lazy',
                                            'sizes'   => '(max-width: 768px) 230px, 520px',
                                        ]); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($title !== '' || $excerpt !== '' || $terms) : ?>
                                    <div class="home-expertise-card-body">
                                        <?php if ($title !== '' || $excerpt !== '') : ?>
                                            <div class="home-expertise-card-copy">
                                                <?php if ($title !== '') : ?>
                                                    <h3 class="home-expertise-card-title"><?php echo esc_html($title); ?></h3>
                                                <?php endif; ?>
                                                <?php if ($excerpt !== '') : ?>
                                                    <p class="home-expertise-card-text"><?php echo esc_html(wp_strip_all_tags($excerpt)); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($terms) : ?>
                                            <ul class="home-expertise-chips" aria-label="<?php esc_attr_e('Tags', 'niyom'); ?>">
                                                <?php foreach (array_slice($terms, 0, $max_chips) as $term) : ?>
                                                    <li class="home-expertise-chip"><?php echo esc_html($term->name); ?></li>
                                                <?php endforeach; ?>
                                                <?php if ($extra > 0) : ?>
                                                    <li class="home-expertise-chip home-expertise-chip--more"
                                                        title="<?php echo esc_attr(implode(', ', wp_list_pluck(array_slice($terms, $max_chips), 'name'))); ?>">
                                                        <?php echo esc_html(sprintf(__('+%d more', 'niyom'), $extra)); ?>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php // Pager: ‹ 01 / 03 › — JS counts the slider positions and hides the pager when all cards fit. ?>
                <div class="home-expertise-pager">
                    <button type="button" class="home-expertise-nav home-expertise-nav--prev" aria-label="<?php esc_attr_e('Previous', 'niyom'); ?>">
                        <?php echo niyom_icon('expertise-chevron', 'home-expertise-nav-icon'); ?>
                    </button>
                    <p class="home-expertise-fraction" aria-live="polite" aria-atomic="true">
                        <span class="home-expertise-current">01</span><span class="home-expertise-total"> / <?php echo esc_html(sprintf('%02d', count($cards))); ?></span>
                    </p>
                    <button type="button" class="home-expertise-nav home-expertise-nav--next" aria-label="<?php esc_attr_e('Next', 'niyom'); ?>">
                        <?php echo niyom_icon('expertise-chevron', 'home-expertise-nav-icon'); ?>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

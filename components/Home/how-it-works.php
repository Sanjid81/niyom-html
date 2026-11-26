<?php
/**
 * Home "How NIYOM works" section. Fields arrive in $args (see inc/blocks/home-how-it-works.php).
 *
 * Intro (eyebrow, heading, button) and numbered step cards.
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

// ---------- Fields ----------
$eyebrow     = trim($args['steps_eyebrow'] ?? '');
$heading     = niyom_inline_text($args['steps_heading'] ?? '');
$button_text = trim($args['steps_button_text'] ?? '');
$button_link = trim($args['steps_button_link'] ?? '');
$new_tab     = !empty($args['steps_button_new_tab']);
$has_button  = $button_text !== '' && $button_link !== '';

// Clean step data; skip steps with nothing to show. Numbers follow the visible order.
$steps = [];
foreach ((array) ($args['steps_items'] ?? []) as $row) {
    $step = [
        'number' => trim($row['number'] ?? ''),
        'icon'   => $row['icon'] ?? '',
        'title'  => trim($row['title'] ?? ''),
        'text'   => niyom_rich_text($row['text'] ?? ''),
    ];

    if ($step['icon'] || $step['title'] !== '' || $step['text']) {
        $step['number'] = $step['number'] !== '' ? $step['number'] : sprintf('%02d', count($steps) + 1);
        $steps[]        = $step;
    }
}

$has_intro = $eyebrow !== '' || $heading || $has_button;

if (!$has_intro && !$steps) {
    return;
}

$uid = wp_unique_id('home-steps-');
?>

<section class="home-how-it-works"
    id="<?php echo esc_attr($uid); ?>"
    <?php echo $heading ? 'aria-labelledby="' . esc_attr($uid . '-title') . '"' : ''; ?>>
    <div class="home-how-it-works__inner">

        <?php if ($has_intro) : ?>
            <div class="home-how-it-works__intro">
                <?php if ($eyebrow !== '' || $heading) : ?>
                    <div class="home-how-it-works__copy">
                        <?php if ($eyebrow !== '') : ?>
                            <p class="home-how-it-works__eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                            <h2 class="home-how-it-works__heading heading-two" id="<?php echo esc_attr($uid . '-title'); ?>"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($has_button) : ?>
                    <a class="btn btn--primary home-how-it-works__button" <?php echo niyom_link_attrs($button_link, $new_tab); ?>>
                        <span><?php echo esc_html($button_text); ?></span>
                        <?php echo niyom_icon('arrow-right', 'btn__arrow btn__arrow--lg'); ?>
                        <?php echo niyom_icon('arrow-right-sm', 'btn__arrow btn__arrow--sm'); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($steps) : ?>
            <ol class="home-how-it-works__steps" style="--steps: <?php echo (int) count($steps); ?>;">
                <?php foreach ($steps as $step) : ?>
                    <li class="home-how-it-works__step">
                        <span class="home-how-it-works__number"><?php echo esc_html($step['number']); ?></span>

                        <?php if ($step['icon']) : ?>
                            <span class="home-how-it-works__icon" aria-hidden="true">
                                <?php echo niyom_media_icon($step['icon'], 'home-how-it-works__icon-svg'); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ($step['title'] !== '' || $step['text']) : ?>
                            <div class="home-how-it-works__body">
                                <?php if ($step['title'] !== '') : ?>
                                    <h3 class="home-how-it-works__title"><?php echo esc_html($step['title']); ?></h3>
                                <?php endif; ?>
                                <?php if ($step['text']) : ?>
                                    <div class="home-how-it-works__text rich-text">
                                        <?php echo $step['text']; // Escaped in niyom_rich_text(). ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </div>
</section>

<?php
/**
 * Events & Partnerships › Pilots slider markup. Fields arrive in $args
 * (see inc/blocks/events-hub-block.php).
 * Styles: src/styles/components/events-hub/_pilots.scss
 * JS: src/scripts/components/events-hub.js (Swiper)
 *
 * Mist cards (image left, text right) in a Swiper slider with a
 * "‹ 01 / 03 ›" pager. The script counts slider positions and hides the
 * pager when every card fits. Without JavaScript the cards scroll sideways.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim((string) ($args['pilots_eyebrow'] ?? ''));
$heading = niyom_inline_text($args['pilots_heading'] ?? '');

$pilots = [];
foreach ((array) ($args['pilots_items'] ?? []) as $row) {
    $pilot = [
        'image'     => trim((string) ($row['image'] ?? '')),
        'image_alt' => trim((string) ($row['image_alt'] ?? '')),
        'label'     => trim((string) ($row['label'] ?? '')),
        'title'     => niyom_inline_text($row['title'] ?? ''),
        'text'      => niyom_rich_text($row['text'] ?? ''),
        'note'      => trim((string) ($row['note'] ?? '')),
    ];
    if ($pilot['image'] || $pilot['label'] || $pilot['title'] || $pilot['text'] || $pilot['note']) {
        $pilots[] = $pilot;
    }
}

$has_intro = $eyebrow !== '' || $heading;

if (!$has_intro && !$pilots) {
    return;
}
?>

<section class="eh-pilots" data-eh-pilots>
    <div class="eh-pilots-inner">

        <?php if ($has_intro) : ?>
            <div class="eh-pilots-header">
                <?php if ($eyebrow !== '') : ?>
                    <p class="eh-pilots-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="eh-pilots-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($pilots) : ?>
            <div class="eh-pilots-slider swiper">
                <ul class="eh-pilots-track swiper-wrapper">
                    <?php foreach ($pilots as $pilot) : ?>
                        <li class="eh-pilots-card swiper-slide<?php echo $pilot['image'] ? ' has-image' : ''; ?>">
                            <?php if ($pilot['image']) : ?>
                                <div class="eh-pilots-media">
                                    <img class="eh-pilots-image" src="<?php echo esc_url($pilot['image']); ?>" alt="<?php echo esc_attr($pilot['image_alt']); ?>" loading="lazy" decoding="async">
                                </div>
                            <?php endif; ?>

                            <div class="eh-pilots-body">
                                <?php if ($pilot['label'] || $pilot['title'] || $pilot['text']) : ?>
                                    <div class="eh-pilots-copy">
                                        <?php if ($pilot['label']) : ?>
                                            <p class="eh-pilots-label"><?php echo esc_html($pilot['label']); ?></p>
                                        <?php endif; ?>

                                        <?php if ($pilot['title']) : ?>
                                            <h3 class="eh-pilots-title"><?php echo $pilot['title']; // Escaped in niyom_inline_text(). ?></h3>
                                        <?php endif; ?>

                                        <?php if ($pilot['text']) : ?>
                                            <div class="eh-pilots-text rich-text"><?php echo $pilot['text']; // Escaped in niyom_rich_text(). ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($pilot['note']) : ?>
                                    <p class="eh-pilots-note"><?php echo esc_html($pilot['note']); ?></p>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if (count($pilots) > 1) : ?>
                <div class="eh-pilots-pager" hidden>
                    <button type="button" class="eh-pilots-nav eh-pilots-nav--prev" aria-label="<?php esc_attr_e('Previous', 'niyom'); ?>">
                        <?php echo niyom_icon('expertise-chevron', 'eh-pilots-nav-icon'); ?>
                    </button>
                    <p class="eh-pilots-fraction" aria-hidden="true">
                        <span class="eh-pilots-current">01</span><span class="eh-pilots-total"> / <?php echo esc_html(sprintf('%02d', count($pilots))); ?></span>
                    </p>
                    <button type="button" class="eh-pilots-nav eh-pilots-nav--next" aria-label="<?php esc_attr_e('Next', 'niyom'); ?>">
                        <?php echo niyom_icon('expertise-chevron', 'eh-pilots-nav-icon'); ?>
                    </button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</section>

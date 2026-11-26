<?php
/**
 * Services › Services list markup. Fields arrive in $args (see inc/blocks/services-block.php).
 * Styles: src/styles/components/services/list.scss
 *
 * Intro (eyebrow + heading left, text right) and one row per service:
 * label, title, text, 2-column list of points, "Know more" link, image.
 * Each part prints only when it has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

// ---------- Fields ----------
$eyebrow = trim((string) ($args['list_eyebrow'] ?? ''));
$heading = niyom_inline_text($args['list_heading'] ?? '');
$text    = niyom_rich_text($args['list_text'] ?? '');

// Clean service rows; skip rows with nothing to show.
$items = [];
foreach ((array) ($args['list_items'] ?? []) as $row) {
    // One point per line; blank lines are ignored.
    $points = [];
    foreach (preg_split('/\r\n|\r|\n/', (string) ($row['points'] ?? '')) as $line) {
        $line = niyom_inline_text($line);
        if ($line) {
            $points[] = $line;
        }
    }

    $link_text = trim((string) ($row['link_text'] ?? ''));
    $link      = trim((string) ($row['link'] ?? ''));

    $item = [
        'label'     => trim((string) ($row['label'] ?? '')),
        'title'     => niyom_inline_text($row['title'] ?? ''),
        'text'      => niyom_rich_text($row['text'] ?? ''),
        'points'    => $points,
        'has_link'  => $link_text !== '' && $link !== '',
        'link_text' => $link_text,
        'link'      => $link,
        'new_tab'   => !empty($row['new_tab']),
        'image'     => trim((string) ($row['image'] ?? '')),
        'image_alt' => trim((string) ($row['image_alt'] ?? '')),
    ];

    if ($item['label'] !== '' || $item['title'] || $item['text'] || $points || $item['has_link'] || $item['image']) {
        $items[] = $item;
    }
}

$has_head  = $eyebrow !== '' || $heading;
$has_intro = $has_head || $text;

if (!$has_intro && !$items) {
    return;
}

$uid = wp_unique_id('services-list-');
?>

<section class="services-list"
    id="<?php echo esc_attr($uid); ?>"
    <?php echo $heading ? 'aria-labelledby="' . esc_attr($uid . '-title') . '"' : ''; ?>>
    <div class="services-list-inner">

        <?php if ($has_intro) : ?>
            <div class="services-list-intro">
                <?php if ($has_head) : ?>
                    <div class="services-list-head">
                        <?php if ($eyebrow !== '') : ?>
                            <p class="services-list-eyebrow"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                            <h2 class="services-list-heading" id="<?php echo esc_attr($uid . '-title'); ?>"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="services-list-text rich-text"><?php echo $text; // Escaped in niyom_rich_text(). ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($items) : ?>
            <div class="services-list-items">
                <?php foreach ($items as $item) : ?>
                    <article class="services-list-item<?php echo $item['image'] ? '' : ' services-list-item--no-image'; ?>">

                        <div class="services-list-content">
                            <?php if ($item['label'] !== '' || $item['title']) : ?>
                                <header class="services-list-item-head">
                                    <?php if ($item['label'] !== '') : ?>
                                        <p class="services-list-label"><?php echo esc_html($item['label']); ?></p>
                                    <?php endif; ?>

                                    <?php if ($item['title']) : ?>
                                        <h3 class="services-list-title"><?php echo $item['title']; // Escaped in niyom_inline_text(). ?></h3>
                                    <?php endif; ?>
                                </header>
                            <?php endif; ?>

                            <?php if ($item['text']) : ?>
                                <div class="services-list-desc rich-text"><?php echo $item['text']; // Escaped in niyom_rich_text(). ?></div>
                            <?php endif; ?>

                            <?php if ($item['points']) : ?>
                                <ul class="services-list-points">
                                    <?php foreach ($item['points'] as $point) : ?>
                                        <li class="services-list-point">
                                            <span class="services-list-point-arrow" aria-hidden="true">→</span>
                                            <span class="services-list-point-text"><?php echo $point; // Escaped in niyom_inline_text(). ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if ($item['has_link']) : ?>
                                <a class="link-arrow services-list-link" <?php echo niyom_link_attrs($item['link'], $item['new_tab']); ?>>
                                    <span><?php echo esc_html($item['link_text']); ?></span>
                                    <?php echo niyom_icon('arrow-right', 'link-arrow-icon link-arrow-icon--lg'); ?>
                                    <?php echo niyom_icon('arrow-right-sm', 'link-arrow-icon link-arrow-icon--sm'); ?>
                                </a>
                            <?php endif; ?>
                        </div>

                        <?php if ($item['image']) : ?>
                            <figure class="services-list-media">
                                <img class="services-list-image" src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['image_alt']); ?>" loading="lazy" decoding="async">
                            </figure>
                        <?php endif; ?>

                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php
/**
 * Home › What We Do markup. Fields arrive in $args (see inc/blocks/home-what-we-do.php).
 *
 * Desktop/tablet (> 768px): each area is a sticky card, so the next card
 * slides up over the previous one while scrolling (pure CSS; JS only adds
 * a subtle scale/dim to the covered cards).
 * Mobile (≤ 768px): the same cards become an accordion (JS); without JS
 * every item stays open.
 *
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow      = trim((string) ($args['wwd_eyebrow'] ?? ''));
$title        = niyom_inline_text($args['wwd_title'] ?? '');
$intro        = niyom_rich_text($args['wwd_intro'] ?? '');
$intro_mobile = niyom_rich_text($args['wwd_intro_mobile'] ?? '');

// Build clean item data; skip items with nothing to show.
$items = [];
foreach ((array) ($args['wwd_items'] ?? []) as $row) {
    $item = [
        'number'    => trim((string) ($row['number'] ?? '')),
        'label'     => trim((string) ($row['label'] ?? '')),
        'title'     => niyom_inline_text($row['title'] ?? ''),
        'text'      => niyom_rich_text($row['text'] ?? ''),
        'link_text' => trim((string) ($row['link_text'] ?? '')),
        'link_url'  => trim((string) ($row['link_url'] ?? '')),
        'new_tab'   => !empty($row['link_new_tab']),
        'image'     => trim((string) ($row['image'] ?? '')),
    ];

    $item['has_link'] = $item['link_text'] !== '' && $item['link_url'] !== '';
    $item['has_head'] = $item['number'] !== '' || $item['label'] !== '' || $item['title'];
    $item['has_body'] = $item['text'] || $item['has_link'];

    if ($item['has_head'] || $item['has_body'] || $item['image']) {
        $items[] = $item;
    }
}

$has_intro  = $intro || $intro_mobile;
$has_header = $eyebrow || $title || $has_intro;

if (!$has_header && !$items) {
    return;
}

$uid = wp_unique_id('home-wwd-');
?>

<section class="home-what-we-do" id="<?php echo esc_attr($uid); ?>" data-home-wwd>
    <div class="home-what-we-do-inner">

        <?php if ($has_header) : ?>
            <header class="home-what-we-do-header">
                <?php if ($eyebrow || $title) : ?>
                    <div class="home-what-we-do-heading">
                        <?php if ($eyebrow) : ?>
                            <p class="home-what-we-do-eyebrow"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>
                        <?php if ($title) : ?>
                            <h2 class="home-what-we-do-title heading-two"><?php echo $title; // Escaped in niyom_inline_text(). ?></h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($intro) : ?>
                    <div class="home-what-we-do-intro rich-text body-large<?php echo $intro_mobile ? ' home-what-we-do-intro--desktop' : ''; ?>">
                        <?php echo $intro; // Escaped in niyom_rich_text(). ?>
                    </div>
                <?php endif; ?>
                <?php if ($intro_mobile) : ?>
                    <div class="home-what-we-do-intro rich-text body-large<?php echo $intro ? ' home-what-we-do-intro--mobile' : ''; ?>">
                        <?php echo $intro_mobile; // Escaped in niyom_rich_text(). ?>
                    </div>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <?php if ($items) : ?>
            <ol class="home-what-we-do-list" style="--wwd-count: <?php echo count($items); ?>;" data-wwd-list>
                <?php foreach ($items as $i => $item) :
                    $item_id = $uid . '-item-' . ($i + 1);
                    ?>
                    <li class="home-what-we-do-item<?php echo $i === 0 ? ' is-open' : ''; ?>" style="--wwd-index: <?php echo (int) $i; ?>;" data-wwd-item>
                        <div class="home-what-we-do-card" data-wwd-card>

                            <div class="home-what-we-do-content">
                                <?php if ($item['has_head']) : ?>
                                    <div class="home-what-we-do-head" id="<?php echo esc_attr($item_id . '-head'); ?>" data-wwd-toggle<?php echo $item['has_body'] ? ' aria-controls="' . esc_attr($item_id . '-panel') . '"' : ''; ?>>
                                        <?php if ($item['number'] !== '' || $item['label'] !== '') : ?>
                                            <p class="home-what-we-do-label">
                                                <?php if ($item['number'] !== '') : ?>
                                                    <span class="home-what-we-do-number"><?php echo esc_html($item['number']); ?></span>
                                                <?php endif; ?>
                                                <?php if ($item['label'] !== '') : ?>
                                                    <span><?php echo esc_html($item['label']); ?></span>
                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>

                                        <?php if ($item['title']) : ?>
                                            <h3 class="home-what-we-do-item-title"><?php echo $item['title']; // Escaped in niyom_inline_text(). ?></h3>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($item['has_body']) : ?>
                                    <div class="home-what-we-do-body" id="<?php echo esc_attr($item_id . '-panel'); ?>" data-wwd-panel>
                                        <div class="home-what-we-do-body-inner">
                                            <?php if ($item['text']) : ?>
                                                <div class="home-what-we-do-text rich-text body-large">
                                                    <?php echo $item['text']; // Escaped in niyom_rich_text(). ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($item['has_link']) : ?>
                                                <a class="link-arrow home-what-we-do-link" <?php echo niyom_link_attrs($item['link_url'], $item['new_tab']); ?>>
                                                    <span><?php echo esc_html($item['link_text']); ?></span>
                                                    <?php echo niyom_icon('arrow-right', 'link-arrow-icon link-arrow-icon--lg'); ?>
                                                    <?php echo niyom_icon('arrow-right-sm', 'link-arrow-icon link-arrow-icon--sm'); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($item['image']) : ?>
                                <div class="home-what-we-do-media" data-wwd-media>
                                    <img class="home-what-we-do-image"
                                        src="<?php echo esc_url($item['image']); ?>"
                                        alt=""
                                        loading="lazy"
                                        decoding="async">
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </div>
</section>

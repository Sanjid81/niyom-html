<?php
/**
 * Events & Partnerships › Hero markup. Fields arrive in $args
 * (see inc/blocks/events-hub-block.php). Styles: src/styles/components/events-hub/_hero.scss
 *
 * Label; heading + paragraph left, quick links right (bottom-aligned);
 * wide image below. Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow   = trim((string) ($args['hero_eyebrow'] ?? ''));
$title     = niyom_inline_text($args['hero_title'] ?? '');
$text      = niyom_rich_text($args['hero_text'] ?? '');
$image     = trim((string) ($args['hero_image'] ?? ''));
$image_alt = trim((string) ($args['hero_image_alt'] ?? ''));

// Quick links: skip rows without a title or small text.
$links = [];
foreach ((array) ($args['hero_links'] ?? []) as $row) {
    $link = [
        'title'   => trim((string) ($row['title'] ?? '')),
        'text'    => trim((string) ($row['text'] ?? '')),
        'link'    => trim((string) ($row['link'] ?? '')),
        'new_tab' => !empty($row['new_tab']),
    ];
    if ($link['title'] !== '' || $link['text'] !== '') {
        $links[] = $link;
    }
}

$has_main = $title || $text;
$has_copy = $has_main || $links;

if ($eyebrow === '' && !$has_copy && !$image) {
    return;
}
?>

<section class="eh-hero">
    <div class="eh-hero-inner">

        <?php if ($eyebrow !== '' || $has_copy) : ?>
            <header class="eh-hero-header">
                <?php if ($eyebrow !== '') : ?>
                    <p class="eh-hero-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($has_copy) : ?>
                    <div class="eh-hero-copy">
                        <?php if ($has_main) : ?>
                            <div class="eh-hero-main">
                                <?php if ($title) : ?>
                                    <h1 class="eh-hero-title"><?php echo $title; // Escaped in niyom_inline_text(). ?></h1>
                                <?php endif; ?>

                                <?php if ($text) : ?>
                                    <div class="eh-hero-text rich-text"><?php echo $text; // Escaped in niyom_rich_text(). ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($links) : ?>
                            <ul class="eh-hero-links">
                                <?php foreach ($links as $link) : ?>
                                    <li class="eh-hero-links-item">
                                        <?php if ($link['link'] !== '') : ?>
                                            <a class="eh-hero-link" <?php echo niyom_link_attrs($link['link'], $link['new_tab']); ?>>
                                        <?php else : ?>
                                            <div class="eh-hero-link">
                                        <?php endif; ?>
                                            <span class="eh-hero-link-copy">
                                                <?php if ($link['title'] !== '') : ?>
                                                    <span class="eh-hero-link-title"><?php echo esc_html($link['title']); ?></span>
                                                <?php endif; ?>
                                                <?php if ($link['text'] !== '') : ?>
                                                    <span class="eh-hero-link-text"><?php echo esc_html($link['text']); ?></span>
                                                <?php endif; ?>
                                            </span>
                                            <?php if ($link['link'] !== '') : ?>
                                                <span class="eh-hero-link-arrow" aria-hidden="true">→</span>
                                            <?php endif; ?>
                                        <?php echo $link['link'] !== '' ? '</a>' : '</div>'; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <?php if ($image) : ?>
            <figure class="eh-hero-media">
                <img class="eh-hero-image" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($image_alt); ?>" fetchpriority="high" decoding="async">
            </figure>
        <?php endif; ?>

    </div>
</section>

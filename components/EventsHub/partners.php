<?php
/**
 * Events & Partnerships › Institutional collaborations markup. Fields
 * arrive in $args (see inc/blocks/events-hub-block.php).
 * Styles: src/styles/components/events-hub/_partners.scss
 *
 * Intro left; partner logo tiles right. A tile shows the logo, or the
 * partner name when there is no logo. Each part prints only when it has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim((string) ($args['partners_eyebrow'] ?? ''));
$heading = niyom_inline_text($args['partners_heading'] ?? '');
$text    = niyom_rich_text($args['partners_text'] ?? '');

$partners = [];
foreach ((array) ($args['partners_items'] ?? []) as $row) {
    $partner = [
        'logo'    => trim((string) ($row['logo'] ?? '')),
        'name'    => trim((string) ($row['name'] ?? '')),
        'link'    => trim((string) ($row['link'] ?? '')),
        'new_tab' => !empty($row['new_tab']),
    ];
    if ($partner['logo'] !== '' || $partner['name'] !== '') {
        $partners[] = $partner;
    }
}

$has_intro = $eyebrow !== '' || $heading || $text;

if (!$has_intro && !$partners) {
    return;
}
?>

<section class="eh-partners">
    <div class="eh-partners-inner">

        <?php if ($has_intro) : ?>
            <div class="eh-partners-intro">
                <?php if ($eyebrow !== '') : ?>
                    <p class="eh-partners-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="eh-partners-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="eh-partners-text rich-text body-large"><?php echo $text; // Escaped in niyom_rich_text(). ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($partners) : ?>
            <ul class="eh-partners-grid">
                <?php foreach ($partners as $partner) : ?>
                    <li class="eh-partners-item">
                        <?php if ($partner['link'] !== '') : ?>
                            <a class="eh-partners-tile is-linked" <?php echo niyom_link_attrs($partner['link'], $partner['new_tab']); ?>>
                        <?php else : ?>
                            <div class="eh-partners-tile">
                        <?php endif; ?>

                            <?php if ($partner['logo'] !== '') : ?>
                                <img class="eh-partners-logo" src="<?php echo esc_url($partner['logo']); ?>" alt="<?php echo esc_attr($partner['name']); ?>" loading="lazy" decoding="async">
                            <?php else : ?>
                                <span class="eh-partners-name"><?php echo esc_html($partner['name']); ?></span>
                            <?php endif; ?>

                        <?php echo $partner['link'] !== '' ? '</a>' : '</div>'; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</section>

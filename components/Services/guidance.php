<?php
/**
 * Services › Service Selection Guidance markup. Fields arrive in $args (see inc/blocks/services-block.php).
 * Styles: src/styles/components/services/guidance.scss
 *
 * Intro left (eyebrow, heading, text); table right: "If you are…" /
 * "Start with". A row with a link is one clickable link with an arrow.
 * Each part prints only when it has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

// ---------- Fields ----------
$eyebrow       = trim((string) ($args['guidance_eyebrow'] ?? ''));
$heading       = niyom_inline_text($args['guidance_heading'] ?? '');
$text          = niyom_rich_text($args['guidance_text'] ?? '');
$col_situation = trim((string) ($args['guidance_col_situation'] ?? ''));
$col_service   = trim((string) ($args['guidance_col_service'] ?? ''));

// Clean rows; skip empty ones. Links are kept out of the cell text because
// the whole row becomes the link.
$no_links = ['br' => [], 'em' => [], 'i' => [], 'strong' => [], 'b' => [], 'span' => ['class' => true]];
$rows     = [];
foreach ((array) ($args['guidance_rows'] ?? []) as $row) {
    $item = [
        'situation' => niyom_inline_text($row['situation'] ?? ''),
        'service'   => niyom_inline_text($row['service'] ?? ''),
        'link'      => trim((string) ($row['link'] ?? '')),
        'new_tab'   => !empty($row['new_tab']),
    ];

    if ($item['situation'] || $item['service']) {
        if ($item['link'] !== '') {
            $item['situation'] = wp_kses($item['situation'], $no_links);
            $item['service']   = wp_kses($item['service'], $no_links);
        }
        $rows[] = $item;
    }
}

$has_intro = $eyebrow !== '' || $heading || $text;
$has_head  = $col_situation !== '' || $col_service !== '';

if (!$has_intro && !$rows) {
    return;
}

$uid = wp_unique_id('services-guidance-');
?>

<section class="services-guidance"
    id="<?php echo esc_attr($uid); ?>"
    <?php echo $heading ? 'aria-labelledby="' . esc_attr($uid . '-title') . '"' : ''; ?>>
    <div class="services-guidance-inner<?php echo $rows ? '' : ' services-guidance-inner--intro-only'; ?>">

        <?php if ($has_intro) : ?>
            <div class="services-guidance-intro">
                <?php if ($eyebrow !== '') : ?>
                    <p class="services-guidance-eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="services-guidance-heading" id="<?php echo esc_attr($uid . '-title'); ?>"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="services-guidance-text rich-text"><?php echo $text; // Escaped in niyom_rich_text(). ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($rows) : ?>
            <div class="services-guidance-table">

                <?php if ($has_head) : ?>
                    <!-- Column headings (hidden on mobile, where rows stack). -->
                    <div class="services-guidance-row services-guidance-row--head" aria-hidden="true">
                        <span class="services-guidance-cell services-guidance-cell--situation"><?php echo esc_html($col_situation); ?></span>
                        <span class="services-guidance-cell services-guidance-cell--service"><?php echo esc_html($col_service); ?></span>
                    </div>
                <?php endif; ?>

                <ul class="services-guidance-rows">
                    <?php foreach ($rows as $row) : ?>
                        <li class="services-guidance-item">
                            <?php if ($row['link'] !== '') : ?>
                                <a class="services-guidance-row services-guidance-row--link" <?php echo niyom_link_attrs($row['link'], $row['new_tab']); ?>>
                            <?php else : ?>
                                <div class="services-guidance-row">
                            <?php endif; ?>

                                <?php // Empty cells keep the desktop columns but are hidden when rows stack. ?>
                                <span class="services-guidance-cell services-guidance-cell--situation<?php echo $row['situation'] ? '' : ' is-empty'; ?>"><?php echo $row['situation']; // Escaped in niyom_inline_text(). ?></span>
                                <span class="services-guidance-cell services-guidance-cell--service<?php echo $row['service'] ? '' : ' is-empty'; ?>"><?php echo $row['service']; // Escaped in niyom_inline_text(). ?></span>

                            <?php if ($row['link'] !== '') : ?>
                                    <span class="services-guidance-arrow" aria-hidden="true">→</span>
                                </a>
                            <?php else : ?>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

    </div>
</section>

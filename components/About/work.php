<?php
/**
 * About "Publications, Tools & Contributions" section. Fields arrive in $args (see inc/blocks/about-work.php).
 *
 * Intro (eyebrow, heading) and a table: Type / Title / Venue / year, with a
 * "Download" link when the row has a file. On mobile each row stacks.
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

// ---------- Fields ----------
$eyebrow   = trim($args['work_eyebrow'] ?? '');
$heading   = niyom_inline_text($args['work_heading'] ?? '');
$col_type  = trim($args['work_col_type'] ?? '');
$col_title = trim($args['work_col_title'] ?? '');
$col_venue = trim($args['work_col_venue'] ?? '');
$icon      = $args['work_download_icon'] ?? '';

// Clean row data; skip empty rows.
$rows = [];
foreach ((array) ($args['work_rows'] ?? []) as $row) {
    $item = [
        'type'  => trim($row['type'] ?? ''),
        'title' => niyom_inline_text($row['title'] ?? ''),
        'venue' => trim($row['venue'] ?? ''),
        'file'  => trim($row['file'] ?? ''),
        'label' => trim($row['download_label'] ?? ''),
    ];

    if ($item['label'] === '') {
        $item['label'] = __('Download', 'niyom');
    }

    if ($item['type'] !== '' || $item['title'] || $item['venue'] !== '' || $item['file'] !== '') {
        $rows[] = $item;
    }
}

$has_intro = $eyebrow !== '' || $heading;
$has_head  = $col_type !== '' || $col_title !== '' || $col_venue !== '';

if (!$has_intro && !$rows) {
    return;
}

$uid = wp_unique_id('about-work-');
?>

<section class="about-work"
    id="<?php echo esc_attr($uid); ?>"
    <?php echo $heading ? 'aria-labelledby="' . esc_attr($uid . '-title') . '"' : ''; ?>>
    <div class="about-work-inner">

        <?php if ($has_intro) : ?>
            <div class="about-work-intro">
                <?php if ($eyebrow !== '') : ?>
                    <p class="about-work-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="about-work-heading heading-two" id="<?php echo esc_attr($uid . '-title'); ?>"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($rows) : ?>
            <div class="about-work-table" role="table"<?php echo $heading ? ' aria-labelledby="' . esc_attr($uid . '-title') . '"' : ''; ?>>

                <?php if ($has_head) : ?>
                    <!-- Column headings (hidden on mobile, where rows stack). -->
                    <div class="about-work-row about-work-row--head" role="row">
                        <span class="about-work-cell about-work-cell--type" role="columnheader"><?php echo esc_html($col_type); ?></span>
                        <span class="about-work-cell about-work-cell--title" role="columnheader"><?php echo esc_html($col_title); ?></span>
                        <span class="about-work-cell about-work-cell--venue" role="columnheader"><?php echo esc_html($col_venue); ?></span>
                        <span class="about-work-cell about-work-cell--action" role="columnheader"><span class="about-work-sr"><?php esc_html_e('Download', 'niyom'); ?></span></span>
                    </div>
                <?php endif; ?>

                <?php foreach ($rows as $row) : ?>
                    <?php // Empty cells keep the desktop columns but are hidden when rows stack. ?>
                    <div class="about-work-row" role="row">
                        <span class="about-work-cell about-work-cell--type<?php echo $row['type'] === '' ? ' is-empty' : ''; ?>" role="cell"><?php echo esc_html($row['type']); ?></span>
                        <span class="about-work-cell about-work-cell--title<?php echo $row['title'] ? '' : ' is-empty'; ?>" role="cell"><?php echo $row['title']; // Escaped in niyom_inline_text(). ?></span>
                        <span class="about-work-cell about-work-cell--venue<?php echo $row['venue'] === '' ? ' is-empty' : ''; ?>" role="cell"><?php echo esc_html($row['venue']); ?></span>
                        <span class="about-work-cell about-work-cell--action<?php echo $row['file'] === '' ? ' is-empty' : ''; ?>" role="cell">
                            <?php if ($row['file'] !== '') : ?>
                                <a class="about-work-download" href="<?php echo esc_url($row['file']); ?>" download>
                                    <?php if ($icon) : ?>
                                        <span class="about-work-download-icon" aria-hidden="true"><?php echo niyom_media_icon($icon, 'about-work-download-svg'); ?></span>
                                    <?php endif; ?>
                                    <span class="about-work-download-label"><?php echo esc_html($row['label']); ?></span>
                                </a>
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

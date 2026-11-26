<?php
/**
 * Expertise › Methods & data types. Fields arrive in $args
 * (see inc/blocks/expertise-block.php).
 * Styles: src/styles/components/expertise/_methods.scss
 *
 * Intro (label, H2, text) left; table right with a Deep Ink header row and
 * striped rows. On mobile each row becomes a stacked block and the data
 * column heading is shown above its value (data-label).
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow    = trim((string) ($args['methods_eyebrow'] ?? ''));
$heading    = niyom_inline_text($args['methods_heading'] ?? '');
$text       = niyom_rich_text($args['methods_text'] ?? '');
$col_method = trim((string) ($args['methods_col_method'] ?? ''));
$col_data   = trim((string) ($args['methods_col_data'] ?? ''));

// Clean rows; skip empty ones.
$rows = [];
foreach ((array) ($args['methods_items'] ?? []) as $row) {
    $method = niyom_inline_text($row['method'] ?? '');
    $data   = niyom_inline_text($row['data'] ?? '');
    if ($method || $data) {
        $rows[] = ['method' => $method, 'data' => $data];
    }
}

$has_intro = $eyebrow !== '' || $heading || $text;

if (!$has_intro && !$rows) {
    return;
}
?>

<section class="ex-methods">
    <div class="ex-methods-inner">

        <?php if ($has_intro) : ?>
            <div class="ex-methods-intro">
                <?php if ($eyebrow !== '') : ?>
                    <p class="ex-methods-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="ex-methods-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="ex-methods-text rich-text body-large"><?php echo $text; // Escaped in niyom_rich_text(). ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($rows) : ?>
            <div class="ex-methods-table-wrap">
                <table class="ex-methods-table">
                    <?php if ($col_method !== '' || $col_data !== '') : ?>
                        <thead class="ex-methods-head">
                            <tr>
                                <th class="ex-methods-th" scope="col"><?php echo esc_html($col_method); ?></th>
                                <th class="ex-methods-th" scope="col"><?php echo esc_html($col_data); ?></th>
                            </tr>
                        </thead>
                    <?php endif; ?>

                    <tbody>
                        <?php foreach ($rows as $row) : ?>
                            <tr class="ex-methods-row">
                                <td class="ex-methods-method"><?php echo $row['method']; // Escaped in niyom_inline_text(). ?></td>
                                <td class="ex-methods-data"<?php echo $col_data !== '' ? ' data-label="' . esc_attr($col_data) . '"' : ''; ?>><?php echo $row['data']; // Escaped in niyom_inline_text(). ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</section>

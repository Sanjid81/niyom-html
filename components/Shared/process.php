<?php
/**
 * Shared › Process section (numbered step cards, e.g. "From enquiry to
 * handover in four steps."). Fields arrive in $args.
 * Styles: src/styles/components/shared/_process.scss · Docs: components/Shared/README.md
 *
 * Intro (label + heading left, paragraph right) and a row of white step
 * cards: number, title, text. Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim((string) ($args['process_eyebrow'] ?? ''));
$heading = niyom_inline_text($args['process_heading'] ?? '');
$text    = niyom_rich_text($args['process_text'] ?? '');

// Clean step data; skip empty steps. Numbers follow the visible order.
$steps = [];
foreach ((array) ($args['process_items'] ?? []) as $row) {
    $step = [
        'number' => trim((string) ($row['number'] ?? '')),
        'title'  => niyom_inline_text($row['title'] ?? ''),
        'text'   => niyom_rich_text($row['text'] ?? ''),
    ];

    if ($step['title'] || $step['text']) {
        $step['number'] = $step['number'] !== '' ? $step['number'] : sprintf('%02d', count($steps) + 1);
        $steps[]        = $step;
    }
}

$has_copy  = $eyebrow !== '' || $heading;
$has_intro = $has_copy || $text;

if (!$has_intro && !$steps) {
    return;
}
?>

<section class="shared-process">
    <div class="shared-process-inner">

        <?php if ($has_intro) : ?>
            <div class="shared-process-intro">
                <?php if ($has_copy) : ?>
                    <div class="shared-process-copy">
                        <?php if ($eyebrow !== '') : ?>
                            <p class="shared-process-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                            <h2 class="shared-process-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="shared-process-text rich-text body-large"><?php echo $text; // Escaped in niyom_rich_text(). ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($steps) : ?>
            <ol class="shared-process-steps">
                <?php foreach ($steps as $step) : ?>
                    <li class="shared-process-step">
                        <span class="shared-process-number"><?php echo esc_html($step['number']); ?></span>

                        <div class="shared-process-body">
                            <?php if ($step['title']) : ?>
                                <h3 class="shared-process-title lead-text"><?php echo $step['title']; // Escaped in niyom_inline_text(). ?></h3>
                            <?php endif; ?>

                            <?php if ($step['text']) : ?>
                                <div class="shared-process-step-text rich-text body-medium"><?php echo $step['text']; // Escaped in niyom_rich_text(). ?></div>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>

    </div>
</section>

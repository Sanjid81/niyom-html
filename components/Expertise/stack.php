<?php
/**
 * Expertise › Tools & technologies. Fields arrive in $args
 * (see inc/blocks/expertise-block.php).
 * Styles: src/styles/components/expertise/_stack.scss
 *
 * Label + H2 left; outlined tool pills right (one tool per line in the
 * field). Each part prints only when it has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim((string) ($args['stack_eyebrow'] ?? ''));
$heading = niyom_inline_text($args['stack_heading'] ?? '');
$tools   = array_values(array_filter(array_map('trim', preg_split('/\R/', (string) ($args['stack_items'] ?? '')))));

$has_intro = $eyebrow !== '' || $heading;

if (!$has_intro && !$tools) {
    return;
}
?>

<section class="ex-stack">
    <div class="ex-stack-inner">

        <?php if ($has_intro) : ?>
            <div class="ex-stack-intro">
                <?php if ($eyebrow !== '') : ?>
                    <p class="ex-stack-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="ex-stack-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($tools) : ?>
            <ul class="ex-stack-list">
                <?php foreach ($tools as $tool) : ?>
                    <li class="ex-stack-chip"><?php echo esc_html($tool); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</section>

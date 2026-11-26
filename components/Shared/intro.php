<?php
/**
 * Shared › Intro (overview) section. Fields arrive in $args.
 * Styles: src/styles/components/shared/_intro.scss · Docs: components/Shared/README.md
 *
 * White section: label + large statement (H3) on the left, paragraph on the
 * right, bottom-aligned. Used after the hero on the service pages.
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim((string) ($args['intro_eyebrow'] ?? ''));
$heading = niyom_inline_text($args['intro_heading'] ?? '');
$text    = niyom_rich_text($args['intro_text'] ?? '');

if ($eyebrow === '' && !$heading && !$text) {
    return;
}
?>

<section class="shared-intro">
    <div class="shared-intro-inner">

        <?php if ($eyebrow !== '' || $heading) : ?>
            <div class="shared-intro-copy">
                <?php if ($eyebrow !== '') : ?>
                    <p class="shared-intro-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="shared-intro-heading heading-three"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($text) : ?>
            <div class="shared-intro-text rich-text body-large"><?php echo $text; // Escaped in niyom_rich_text(). ?></div>
        <?php endif; ?>

    </div>
</section>

<?php
/**
 * Insights › Hero. Fields arrive in $args (see inc/blocks/insights-block.php).
 * Figma: Insights › 02 — Hero (303:7341). Label; H1 left (777px), paragraph
 * right (570px), bottom-aligned. The hidden buttons and "Hero facts" row
 * in Figma are not built.
 * Styles: src/styles/components/insights/_hero.scss
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim((string) ($args['hero_eyebrow'] ?? ''));
$title   = niyom_inline_text($args['hero_title'] ?? '');
$text    = niyom_rich_text($args['hero_text'] ?? '');

if ($eyebrow === '' && !$title && !$text) {
    return;
}
?>

<section class="in-hero">
    <div class="in-hero-inner">
        <?php if ($eyebrow !== '') : ?>
            <p class="in-hero-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
        <?php endif; ?>

        <?php if ($title || $text) : ?>
            <div class="in-hero-copy">
                <?php if ($title) : ?>
                    <h1 class="in-hero-title"><?php echo $title; // Escaped in niyom_inline_text(). ?></h1>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="in-hero-text rich-text">
                        <?php echo $text; // Escaped in niyom_rich_text(). ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

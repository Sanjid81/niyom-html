<?php
/**
 * Work With Us Hero block markup. 
 * Fields arrive in $args (see inc/blocks/work_with_us.php).
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$tag         = trim($args['wwu_tag'] ?? '');
$title       = niyom_inline_text($args['wwu_title'] ?? '');
$description = niyom_rich_text($args['wwu_description'] ?? '');

if (!$tag && !$title && !$description) {
    return;
}
?>

<section class="work-with-us-hero">
    <div class="work-with-us-hero__inner">
        
        <div class="work-with-us-hero__content">
            <?php if ($tag) : ?>
                <span class="work-with-us-hero__tag"><?php echo esc_html($tag); ?></span>
            <?php endif; ?>

            <?php if ($title) : ?>
                <h2 class="work-with-us-hero__title display-two"><?php echo $title; // Escaped in niyom_inline_text() ?></h2>
            <?php endif; ?>
        </div>

        <?php if ($description) : ?>
            <div class="work-with-us-hero__aside">
                <div class="work-with-us-hero__text rich-text body-large">
                    <?php echo $description; // Escaped in niyom_rich_text() ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>
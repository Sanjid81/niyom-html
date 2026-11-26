<?php
defined('ABSPATH') || exit;
$tag = $args['ih_tag'] ?? '';
$title = niyom_inline_text($args['ih_title'] ?? '');
$desc = niyom_rich_text($args['ih_description'] ?? '');
if (!$title) return;
?>
<section class="insights-hero">
    <div class="insights-hero__inner">
        <div class="insights-hero__content">
            <?php if($tag): ?><span class="insights-hero__tag"><?php echo esc_html($tag); ?></span><?php endif; ?>
            <h1 class="insights-hero__title"><?php echo $title; ?></h1>
        </div>
        <?php if($desc): ?>
        <div class="insights-hero__aside">
            <div class="insights-hero__text rich-text"><?php echo $desc; ?></div>
        </div>
        <?php endif; ?>
    </div>
</section>
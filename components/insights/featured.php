<?php
defined('ABSPATH') || exit;
$image = $args['if_image'] ?? '';
$cat = $args['if_category'] ?? '';
$date = $args['if_date'] ?? '';
$title = $args['if_title'] ?? '';
$text = niyom_rich_text($args['if_text'] ?? '');
$btn_text = $args['if_btn_text'] ?? '';
$btn_link = $args['if_btn_link'] ?? '#';
if (!$title) return;
?>
<section class="insights-featured">
    <div class="insights-featured__inner">
        <?php if($image): ?>
        <div class="insights-featured__media"><img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>"></div>
        <?php endif; ?>
        <div class="insights-featured__content">
            <div class="insights-featured__meta">
                <span><?php echo esc_html($date); ?></span>
                <span><?php echo esc_html($cat); ?></span>
            </div>
            <h2 class="insights-featured__title"><?php echo esc_html($title); ?></h2>
            <div class="insights-featured__text rich-text"><?php echo $text; ?></div>
            <?php if($btn_text): ?>
            <a href="<?php echo esc_url($btn_link); ?>" class="btn btn--primary">
                <span><?php echo esc_html($btn_text); ?></span>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M1 8H15M15 8L8 1M15 8L8 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>
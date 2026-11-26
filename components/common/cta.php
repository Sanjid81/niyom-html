<?php
defined('ABSPATH') || exit;
$tag = trim($args['cta_tag'] ?? '');
$title = niyom_inline_text($args['cta_title'] ?? '');
$disclaimer = trim($args['cta_disclaimer'] ?? '');
$bg_image = $args['cta_bg_image'] ?? '';
$button_text = trim($args['cta_button_text'] ?? 'SUBSCRIBE');
if (!$title) return;
?>
<section class="cta-newsletter" <?php echo $bg_image ? 'style="background-image: url(' . esc_url($bg_image) . ');"' : ''; ?>>
    <div class="cta-newsletter__inner">
        <div class="cta-newsletter__content">
            <?php if ($tag) : ?><span class="cta-newsletter__tag"><?php echo esc_html($tag); ?></span><?php endif; ?>
            <h2 class="cta-newsletter__title"><?php echo $title; ?></h2>
        </div>
        <div class="cta-newsletter__form-wrap">
            <form class="cta-newsletter__form" action="#" method="POST">
                <input type="email" name="email" placeholder="Your email address" required>
                <button type="submit"><span><?php echo esc_html($button_text); ?></span> <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M1 8H15M15 8L8 1M15 8L8 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
            </form>
            <?php if ($disclaimer) : ?><p class="cta-newsletter__disclaimer"><?php echo esc_html($disclaimer); ?></p><?php endif; ?>
        </div>
    </div>
</section>
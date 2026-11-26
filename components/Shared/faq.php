<?php
/**
 * Shared › FAQ accordion. Fields arrive in $args.
 * Styles: src/styles/components/shared/_faq.scss
 * Script: src/scripts/components/shared-faq.js (open / close)
 * Docs:   components/Shared/README.md
 *
 * Label + heading on the left, question list on the right. Without JavaScript
 * every answer stays visible. Each part prints only when it has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow    = trim((string) ($args['faq_eyebrow'] ?? ''));
$heading    = niyom_inline_text($args['faq_heading'] ?? '');
$open_first = !empty($args['faq_open_first']);

// Clean rows; a row needs a question.
$items = [];
foreach ((array) ($args['faq_items'] ?? []) as $row) {
    $question = niyom_inline_text($row['question'] ?? '');
    if ($question) {
        $items[] = [
            'question' => $question,
            'answer'   => niyom_rich_text($row['answer'] ?? ''),
        ];
    }
}

if ($eyebrow === '' && !$heading && !$items) {
    return;
}

$uid  = wp_unique_id('shared-faq-');
$plus = niyom_media_icon(NIYOM_URI . '/assets/icons/faq-plus.svg', 'shared-faq-icon-svg');
?>

<section class="shared-faq" id="<?php echo esc_attr($uid); ?>" data-shared-faq>
    <div class="shared-faq-inner">

        <?php if ($eyebrow !== '' || $heading) : ?>
            <div class="shared-faq-copy">
                <?php if ($eyebrow !== '') : ?>
                    <p class="shared-faq-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="shared-faq-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($items) : ?>
            <div class="shared-faq-list">
                <?php foreach ($items as $i => $item) :
                    $item_id = $uid . '-' . ($i + 1);
                    $is_open = $open_first && $i === 0;
                    ?>
                    <div class="shared-faq-item<?php echo $is_open ? ' is-open' : ''; ?>">
                        <h3 class="shared-faq-question">
                            <?php if ($item['answer']) : ?>
                                <button type="button"
                                    class="shared-faq-toggle"
                                    id="<?php echo esc_attr($item_id . '-q'); ?>"
                                    aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                                    aria-controls="<?php echo esc_attr($item_id . '-a'); ?>">
                                    <span class="shared-faq-question-text lead-text"><?php echo $item['question']; // Escaped in niyom_inline_text(). ?></span>
                                    <span class="shared-faq-icon" aria-hidden="true"><?php echo $plus; // Theme SVG, cleaned in niyom_media_icon(). ?></span>
                                </button>
                            <?php else : ?>
                                <span class="shared-faq-toggle">
                                    <span class="shared-faq-question-text lead-text"><?php echo $item['question']; // Escaped in niyom_inline_text(). ?></span>
                                </span>
                            <?php endif; ?>
                        </h3>

                        <?php if ($item['answer']) : ?>
                            <div class="shared-faq-answer"
                                id="<?php echo esc_attr($item_id . '-a'); ?>"
                                role="region"
                                aria-labelledby="<?php echo esc_attr($item_id . '-q'); ?>">
                                <div class="shared-faq-answer-inner">
                                    <div class="shared-faq-answer-text rich-text body-medium"><?php echo $item['answer']; // Escaped in niyom_rich_text(). ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

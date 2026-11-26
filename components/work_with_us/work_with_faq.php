<?php
/**
 * Work FAQ Accordion block markup. 
 * Fields arrive in $args (see inc/blocks/work_with_us.php).
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$tag   = trim($args['faq_tag'] ?? '');
$title = niyom_inline_text($args['faq_title'] ?? '');
$items = (array) ($args['faq_items'] ?? []);

if (!$tag && !$title && !$items) {
    return;
}
?>

<section class="work-with-faq">
    <div class="work-with-faq__inner">
        
        <!-- Left Column: Heading -->
        <div class="work-with-faq__content">
            <?php if ($tag) : ?>
                <span class="work-with-faq__tag"><?php echo esc_html($tag); ?></span>
            <?php endif; ?>

            <?php if ($title) : ?>
                <h2 class="work-with-faq__title"><?php echo $title; ?></h2>
            <?php endif; ?>
        </div>

        <!-- Right Column: Accordion -->
        <?php if ($items) : ?>
            <div class="work-with-faq__accordion">
                <?php foreach ($items as $item) : 
                    $question = trim($item['question'] ?? '');
                    $answer   = niyom_rich_text($item['answer'] ?? '');
                    
                    if (!$question || !$answer) continue;
                    ?>
                    
                    <details class="work-with-faq__item">
                        <summary class="work-with-faq__question">
                            <span class="work-with-faq__question-text"><?php echo esc_html($question); ?></span>
                            <span class="work-with-faq__icon" aria-hidden="true">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 1V15M1 8H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </summary>
                        <div class="work-with-faq__answer rich-text">
                            <?php echo $answer; ?>
                        </div>
                    </details>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
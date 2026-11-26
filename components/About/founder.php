<?php
/**
 * About › Founder Profile markup. Fields arrive in $args (see inc/blocks/about-founder.php).
 * Each part prints only when its field has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$image     = trim((string) ($args['founder_image'] ?? ''));
$image_alt = trim((string) ($args['founder_image_alt'] ?? ''));
$name      = trim((string) ($args['founder_name'] ?? ''));
$role      = trim((string) ($args['founder_role'] ?? ''));
$lead      = niyom_inline_text($args['founder_lead'] ?? '');
$bio       = niyom_rich_text($args['founder_bio'] ?? '');

// Profile detail rows; a row prints when it has a label or a value.
$details = [];
foreach ((array) ($args['founder_details'] ?? []) as $row) {
    $detail = [
        'label' => trim((string) ($row['label'] ?? '')),
        'value' => niyom_rich_text($row['value'] ?? ''),
    ];

    if ($detail['label'] !== '' || $detail['value']) {
        $details[] = $detail;
    }
}

$has_name    = $name !== '' || $role !== '';
$has_content = $has_name || $lead || $bio || $details;

if (!$image && !$has_content) {
    return;
}
?>

<section class="about-founder<?php echo $image ? '' : ' about-founder--no-image'; ?>">
    <div class="about-founder__inner">

        <?php if ($image) : ?>
            <figure class="about-founder__media">
                <img class="about-founder__image" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($image_alt); ?>" loading="lazy" decoding="async">
            </figure>
        <?php endif; ?>

        <?php if ($has_content) : ?>
            <div class="about-founder__content">

                <?php if ($has_name) : ?>
                    <header class="about-founder__head">
                        <?php if ($name !== '') : ?>
                            <h2 class="about-founder__name"><?php echo esc_html($name); ?></h2>
                        <?php endif; ?>

                        <?php if ($role !== '') : ?>
                            <p class="about-founder__role"><?php echo esc_html($role); ?></p>
                        <?php endif; ?>
                    </header>
                <?php endif; ?>

                <?php if ($lead) : ?>
                    <p class="about-founder__lead"><?php echo $lead; // Escaped in niyom_inline_text(). ?></p>
                <?php endif; ?>

                <?php if ($bio) : ?>
                    <div class="about-founder__bio rich-text"><?php echo $bio; // Escaped in niyom_rich_text(). ?></div>
                <?php endif; ?>

                <?php if ($details) : ?>
                    <dl class="about-founder__details">
                        <?php foreach ($details as $detail) : ?>
                            <div class="about-founder__row">
                                <dt class="about-founder__label"><?php echo esc_html($detail['label']); ?></dt>
                                <dd class="about-founder__value rich-text"><?php echo $detail['value']; // Escaped in niyom_rich_text(). ?></dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                <?php endif; ?>

            </div>
        <?php endif; ?>

    </div>
</section>

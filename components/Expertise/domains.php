<?php
/**
 * Expertise › Scientific domains & specialisations. Fields arrive in $args
 * (see inc/blocks/expertise-block.php).
 * Styles: src/styles/components/expertise/_domains.scss
 *
 * Intro (label, H2, text) left; groups right, each a teal label over tag
 * pills (shared .shared-tags / .shared-tag, sized up for this section).
 * Tags are entered one per line. Each part prints only when it has content.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

$eyebrow = trim((string) ($args['domains_eyebrow'] ?? ''));
$heading = niyom_inline_text($args['domains_heading'] ?? '');
$text    = niyom_rich_text($args['domains_text'] ?? '');

// Clean groups: one tag per line; skip groups without tags.
$groups = [];
foreach ((array) ($args['domains_groups'] ?? []) as $group) {
    $tags = array_values(array_filter(array_map('trim', preg_split('/\R/', (string) ($group['tags'] ?? '')))));
    if ($tags) {
        $groups[] = [
            'title' => trim((string) ($group['title'] ?? '')),
            'tags'  => $tags,
        ];
    }
}

$has_intro = $eyebrow !== '' || $heading || $text;

if (!$has_intro && !$groups) {
    return;
}
?>

<section class="ex-domains">
    <div class="ex-domains-inner">

        <?php if ($has_intro) : ?>
            <div class="ex-domains-intro">
                <?php if ($eyebrow !== '') : ?>
                    <p class="ex-domains-eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="ex-domains-heading heading-two"><?php echo $heading; // Escaped in niyom_inline_text(). ?></h2>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="ex-domains-text rich-text body-large"><?php echo $text; // Escaped in niyom_rich_text(). ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($groups) : ?>
            <div class="ex-domains-groups">
                <?php foreach ($groups as $group) : ?>
                    <div class="ex-domains-group">
                        <?php if ($group['title'] !== '') : ?>
                            <h3 class="ex-domains-label"><?php echo esc_html($group['title']); ?></h3>
                        <?php endif; ?>

                        <ul class="shared-tags ex-domains-tags">
                            <?php foreach ($group['tags'] as $tag) : ?>
                                <li class="shared-tag ex-domains-tag"><?php echo esc_html($tag); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

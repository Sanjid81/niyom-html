<?php
/**
 * Fallback template: blog, archives, search, single posts.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

get_header();
?>

<div class="container site-content">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('entry'); ?>>
                <?php if (is_singular()) : ?>
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <div class="entry-content"><?php the_content(); ?></div>
                <?php else : ?>
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="entry-content"><?php the_excerpt(); ?></div>
                <?php endif; ?>
            </article>
        <?php endwhile; ?>

        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <p><?php esc_html_e('Nothing found.', 'niyom'); ?></p>
    <?php endif; ?>
</div>

<?php
get_footer();

<?php
/**
 * Default page template. Pages are built from blocks, so content runs full width.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

get_header();

while (have_posts()) :
    the_post();
    the_content();
endwhile;

get_footer();

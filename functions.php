<?php
/**
 * Niyom theme bootstrap.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

define('NIYOM_DIR', get_template_directory());
define('NIYOM_URI', get_template_directory_uri());

require_once NIYOM_DIR . '/inc/setup.php';         // Theme supports, menus, helpers.
require_once NIYOM_DIR . '/inc/template-tags.php'; // Logo, menus, links for header/footer.
require_once NIYOM_DIR . '/inc/post-types.php';    // Expertise + Insights post types.
require_once NIYOM_DIR . '/inc/insights-meta.php'; // Insights: article fields, tags, block styles, REST.
require_once NIYOM_DIR . '/inc/enqueue.php';       // Front-end + editor assets.
require_once NIYOM_DIR . '/inc/carbon-fields.php'; // Carbon Fields boot.
require_once NIYOM_DIR . '/inc/theme-options.php'; // Theme Options pages.

// Gutenberg blocks (Carbon Fields): every file in inc/blocks/ is loaded.
foreach (glob(NIYOM_DIR . '/inc/blocks/*.php') as $niyom_block_file) {
    require_once $niyom_block_file;
}

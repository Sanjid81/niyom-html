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
require_once NIYOM_DIR . '/inc/enqueue.php';       // Front-end + editor assets.
require_once NIYOM_DIR . '/inc/carbon-fields.php'; // Carbon Fields boot.
require_once NIYOM_DIR . '/inc/theme-options.php'; // Theme Options pages.
require_once NIYOM_DIR . '/inc/blocks.php';        // Gutenberg blocks.

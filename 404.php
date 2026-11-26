<?php
/**
 * 404 page.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

get_header();
?>

<div class="container site-content site-content--center">
    <h1><?php esc_html_e('Page not found', 'niyom'); ?></h1>
    <p><?php esc_html_e('The page you are looking for does not exist.', 'niyom'); ?></p>
    <a class="btn btn--primary" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Back to home', 'niyom'); ?></a>
</div>

<?php
get_footer();

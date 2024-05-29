<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

$springoo_view   = springoo_get_current_screen();
$springoo_layout = springoo_get_content_layout( $springoo_view );

?>
<div class="springoo-entry-content format-status">
	<div class="post-content-wrap">
		<?php
		get_template_part('partials/content', 'sticky');
		springoo_show_taxonomy();
		springoo_show_entry_meta();
		springoo_show_entry_content();
		springoo_show_entry_footer();
		?>
	</div><!-- .post-content-wrap -->
</div><!-- .springoo-entry-content -->

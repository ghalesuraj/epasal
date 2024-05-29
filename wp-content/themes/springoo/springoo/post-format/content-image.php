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
$image           = get_media_embedded_in_content( apply_filters( 'the_content', get_the_content() ), array( 'image' ) );

if ( is_single() ) {
	get_template_part( 'post-format/entry-content' );
}
?>
<div class="springoo-entry-content format-image">
	<?php springoo_post_format_get_post_image( $springoo_layout ); ?>

	<div class="post-content-wrap">
		<?php
		if ( ! has_post_thumbnail() && ! has_block('core/image', get_the_content() ) && empty( $image ) ) {
			get_template_part( 'partials/content', 'sticky' );
		}
		springoo_show_taxonomy();
		springoo_show_entry_header();
		springoo_show_entry_meta();
		springoo_show_entry_content();
		springoo_show_entry_footer();
		?>
	</div><!-- .post-content-wrap -->
</div><!-- .springoo-entry-content -->

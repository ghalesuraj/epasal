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

do_action( 'springoo_before_content_loop', $springoo_layout, $springoo_view );

/* Start the Loop */
while ( have_posts() ) {
	the_post();

	$format = get_post_format();
	$format = $format ? $format : 'standard';

	do_action( 'springoo_before_content_loop_layout', $springoo_layout, $springoo_view, $format );

	?>
	<article <?php post_class( 'springoo-post' ); ?> id="post-<?php the_ID(); ?>">
		<?php get_template_part( 'post-format/content', $format ); ?>
	</article><!-- #post-## -->
	<?php

	do_action( 'springoo_after_content_loop_layout', $springoo_layout, $springoo_view, $format );
}

do_action( 'springoo_after_content_loop', $springoo_layout, $springoo_view );

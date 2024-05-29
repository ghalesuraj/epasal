<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}
do_action( 'springoo_before_footer' );

$color_scheme = springoo_get_post_layout_options( 'footer_color_scheme' );
?>
	<footer id="colophon" class="footer_<?php echo esc_attr( $color_scheme ); ?>" role="contentinfo">
	<?php
		/**
		 * Render Footer Widgets.
		 *
		 * @hooked springoo_footer_newsletter 40
		 * @hooked springoo_render_footer_main 50
		 */
		do_action( 'springoo_footer_contents' );

		/**
		 * Render Secondary Footer
		 *
		 * @hooked springoo_render_secondary_footer
		 */
		do_action( 'springoo_footer_secondary_contents' );

		/**
		 * Render Footer Credit Section.
		 *
		 * @hooked springoo_render_footer_credits 10
		 */
		do_action( 'springoo_footer_bottom_contents' );
	?>
	</footer><!-- #colophon -->
	<?php do_action( 'springoo_after_footer' ); ?>
	</div><!-- #page -->
<?php
do_action( 'springoo_after_page' );
wp_footer();
?>
</body>
</html>

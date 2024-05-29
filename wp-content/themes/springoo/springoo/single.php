<?php
/**
 * The template for displaying all single posts.
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

do_action( 'springoo_single_option' );

$springoo_view     = springoo_get_current_screen();
$content_container = springoo_get_post_layout_options( 'content_container' );

if ( 'default' === $content_container ) {
	$classes = apply_filters( 'springoo_content_container_class', springoo_get_mod( 'layout_global_content_layout' ) );
} elseif ( 'none' === $content_container ) {
	$classes = '';
} else {
	$classes = apply_filters( 'springoo_content_container_class', $content_container );
}

get_header();

?>
	<div id="content">
		<?php springoo_title_bar( $springoo_view ); ?>
		<div <?php springoo_get_content_class(); ?>>
			<div class="<?php echo esc_attr( $classes ); ?>">
				<div class="row">
					<?php springoo_try_sidebar( $springoo_view, 'left' ); ?>
					<div id="primary" <?php springoo_main_class( 'single' ); ?>>
						<main id="main" class="site-main" role="main">
							<?php
							while ( have_posts() ) {
								the_post();
								get_template_part( 'content', 'single' );
								// If comments are open or we have at least one comment, load up the comment template.
								if ( comments_open() || get_comments_number() ) {
									comments_template();
								}
							} // end of the loop.
							?>
						</main><!-- #main -->
					</div><!-- #primary -->
					<?php springoo_try_sidebar( $springoo_view, 'right' ); ?>
				</div><!-- .row -->
			</div><!-- .container -->
		</div><!-- .springoo-row -->
	</div><!-- #content -->
<?php
get_footer();

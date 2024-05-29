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

$springoo_view     = springoo_get_current_screen();
$content_container = springoo_get_post_layout_options( 'content_container' );

if ( 'default' === $content_container ) {
	$classes = apply_filters( 'springoo_content_container_class', springoo_get_mod( 'layout_global_content_layout' ) );
} elseif ( 'none' === $content_container ) {
	$classes = '';
} else {
	$classes = apply_filters( 'springoo_content_container_class', $content_container );
}
?>

<div id="content">
	<?php springoo_title_bar( $springoo_view ); ?>

	<?php get_template_part( 'partials/content', 'featured' ); ?>

	<div <?php springoo_get_content_class(); ?>>
		<div class="<?php echo esc_attr( $classes ); ?>">
			<div class="row">

				<?php springoo_try_sidebar( $springoo_view, 'left' ); ?>

				<div id="primary" <?php springoo_main_class(); ?>>
					<?php
					if ( is_author() ) {
						springoo_post_author_info();
					}
					?>
					<main id="main" class="site-main" role="main">

						<?php if ( have_posts() ) : ?>
							<?php get_template_part( 'partials/content', 'loop' ); ?>
							<?php springoo_pagination(); ?>
						<?php else : ?>
							<?php get_template_part( 'content', 'none' ); ?>

						<?php endif; ?>

					</main>
					<!-- #main -->
				</div>
				<!-- #primary -->

				<?php springoo_try_sidebar( $springoo_view, 'right' ); ?>

			</div>
			<!-- .row -->
		</div>
		<!-- .container -->
	</div>
	<!-- .springoo-row -->
</div><!-- #content -->

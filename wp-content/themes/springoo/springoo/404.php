<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

get_header();

$image_404         = springoo_get_mod( 'layout_404_image' );
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
		<?php
		if ( ! empty( springoo_get_mod( 'layout_404_image' ) ) ) { ?>
			<div class="error-page-img">
				<img src="<?php echo esc_url( springoo_get_mod( 'layout_404_image' ) ); ?>" alt="<?php esc_attr_e( '404', 'springoo' ); ?>">
			</div><!-- .error-page-img -->
		<?php } ?>
		<div class="springoo-row site-content">

			<div
				class="<?php echo esc_attr( $classes ); ?>">
				<div class="row">
					<div id="primary" class="content-area col-md-12">
						<main id="main" class="site-main" role="main">
							<section class="error-404 not-found">
								<header class="entry-header">
									<h3 class="page-title"><?php echo wp_kses_post( springoo_get_mod( 'layout_404_page_title' ) ); ?></h3>
								</header><!-- .entry-header -->
								<div class="entry-content">
									<p class="page-content"><?php echo esc_html( springoo_get_mod( 'layout_404_page_description' ) ); ?></p>
								</div><!-- .entry-content -->
								<footer>
									<a href="<?php echo esc_url( springoo_get_mod( 'layout_404_page_button_link' ) ); ?>" class="btn btn-primary error-btn"><?php echo esc_html( springoo_get_mod( 'layout_404_page_button_text' ) ); ?></a>
								</footer>
							</section><!-- .error-404 -->

							<?php if ( is_active_sidebar( '404-page-widget' ) ) { ?>
								<div class="separator"></div>
								<section class="widget-area">
									<?php dynamic_sidebar( '404-page-widget' ); ?>
								</section><!-- .widget-area -->
							<?php } ?>
						</main><!-- #main -->
					</div><!-- #primary -->
				</div><!-- .row -->
			</div><!-- .container -->
		</div>
	</div><!-- #content -->
<?php
get_footer();

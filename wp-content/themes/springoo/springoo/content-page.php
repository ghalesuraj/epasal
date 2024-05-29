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

$springoo_view = springoo_get_current_screen();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'springoo-post' ); ?>>
	<div class="page-body-wrap">
		<header class="entry-header">
			<?php if ( ! springoo_get_mod( 'layout_' . $springoo_view . '_ft-img-hide' ) && has_post_thumbnail() ) : ?>
				<div class="post-image effect slide-top" style="<?php echo esc_attr( springoo_featured_image_width( $springoo_view ) ); ?>">
					<?php springoo_the_post_thumbnail( 'blog-normal', [ 'wrapper' => false ] ); ?>
					<div class="overlay">
						<div class="caption">
							<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'View more', 'springoo' ); ?></a>
						</div>
						<a href="<?php the_permalink(); ?>" class="expand"><i class="fa fa-plus"></i></a>
					</div>
				</div>
			<?php endif; ?>
			<?php springoo_the_title(); ?>
		</header><!-- .entry-header -->
		<div class="entry-content">
			<?php the_content(); ?>
			<?php springoo_link_pages(); ?>
		</div><!-- .entry-content -->
	</div><!-- .page-body-wrap -->
	<footer class="entry-footer single-edit clearfix">
		<?php springoo_edit_post_link(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

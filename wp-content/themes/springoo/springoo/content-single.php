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
	<div class="post-body-wrap">
		<header class="entry-header">
			<?php get_template_part( 'partials/content', 'sticky' ); ?>
			<?php springoo_the_post_thumbnail( 'blog-normal', [ 'link' => false ] ); ?>
			<?php springoo_post_taxonomy( $springoo_view ); ?>
			<?php springoo_the_title(); ?>
			<?php if ( 'post' === get_post_type() ) : ?>
				<div class="entry-meta">
					<?php springoo_post_meta(); ?>
					<?php springoo_post_reading_duration(); ?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->
		<div class="entry-content">
			<?php the_content(); ?>
			<?php springoo_link_pages(); ?>
		</div><!-- .entry-content -->
	</div><!-- .post-body-wrap -->
	<footer class="entry-footer">
		<?php do_action('springoo_content_single_before_entry_footer_content'); ?>
		<?php springoo_post_single_navigation(); ?>
		<?php springoo_post_author_info(); ?>
		<?php springoo_related_post(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

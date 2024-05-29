<?php
/**
 * The Template for displaying all single posts.
 *
 * @package dokan
 * @package dokan - 2014 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info   = $store_user->get_shop_info();
$map_location = $store_user->get_location();
$layout       = get_theme_mod( 'store_layout', 'left' );

get_header( 'shop' );

if ( function_exists( 'yoast_breadcrumb' ) ) {
	yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
}
?>
<?php do_action( 'woocommerce_before_main_content' ); ?>

<div class="dokan-store-wrap layout-<?php echo esc_attr( $layout ); ?>">

	<?php if ( 'left' === $layout ) {
		dokan_get_template_part(
			'store', 'sidebar', [
				'store_user'   => $store_user,
				'store_info'   => $store_info,
				'map_location' => $map_location,
			]
		);
	} ?>

	<div id="dokan-primary" class="dokan-single-store">
		<div id="dokan-content" class="store-page-wrap woocommerce" role="main">
			<?php dokan_get_template_part( 'store-header' );
			do_action( 'dokan_store_profile_frame_after', $store_user->data, $store_info );
			if ( have_posts() ) { ?>
				<div class="seller-items">
					<?php woocommerce_product_loop_start();
					while ( have_posts() ) {
						the_post();
						wc_get_template_part( 'content', 'product' );
					} // end of the loop.
					woocommerce_product_loop_end(); ?>
				</div>
				<?php springoo_dokan_content_nav( 'nav-below' );
			} else { ?>
				<p class="dokan-info"><?php esc_html_e( 'No products were found of this vendor!', 'springoo' ); ?></p>
			<?php } ?>
		</div>

	</div><!-- .dokan-single-store -->

	<?php if ( 'right' === $layout ) {
		dokan_get_template_part(
			'store', 'sidebar', [
				'store_user'   => $store_user,
				'store_info'   => $store_info,
				'map_location' => $map_location,
			]
		);
	} ?>

</div><!-- .dokan-store-wrap -->

<?php do_action( 'woocommerce_after_main_content' ); ?>

<?php get_footer( 'shop' ); ?>

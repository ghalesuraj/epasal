<?php
/**
 * YITH WooCommerce Wishlist Hook helper.
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! function_exists( 'springoo_wishlist_positions' ) ) {
	/**
	 * Springoo wishlist positions
	 *
	 * @param $positions
	 *
	 * @return array
	 */
	function springoo_wishlist_positions( $positions ) {

		$positions['add-to-cart']       = array(
			'hook'     => 'springoo_product_actions',
			'priority' => 15,
		);
		$positions['after_add_to_cart'] = array(
			'hook'     => 'springoo_product_actions',
			'priority' => 15,
		);

		return $positions;
	}
}

if ( ! function_exists( 'springoo_wishlist_loop_positions' ) ) {
	/**
	 * Springoo Add to wishlist loop positions
	 *
	 * @param $positions
	 *
	 * @return mixed
	 */
	function springoo_wishlist_loop_positions( $positions ) {
		$positions['before_image']       = array(
			'hook'     => 'springoo_shop_actions',
			'priority' => 5,
		);
		$positions['before_add_to_cart'] = array(
			'hook'     => 'springoo_after_shop_title',
			'priority' => 24,
		);
		$positions['after_add_to_cart']  = array(
			'hook'     => 'springoo_after_shop_title',
			'priority' => 26,
		);
		return $positions;
	}
}

if ( ! function_exists( 'springoo_wishlist_loop_options' ) ) {
	/**
	 * Springoo add to wishlist loop options
	 *
	 * @param $options
	 *
	 * @return array
	 */
	function springoo_wishlist_loop_options( $options ) {
		$options['add_to_wishlist']['loop_position']['default'] = 'before_image';
		return $options;
	}
}

if ( ! function_exists( 'springoo_add_wishlist_endpoint' ) ) {
	// Create an endpoint with add_rewrite_function
	function springoo_add_wishlist_endpoint() {
		add_rewrite_endpoint( 'my-wishlist', EP_PAGES );
	}
}

if ( ! function_exists( 'springoo_wishlist_query_vars' ) ) {
	// Create query_var
	function springoo_wishlist_query_vars( $vars ) {
		$vars[] = 'my-wishlist';

		return $vars;
	}
}

if ( ! function_exists( 'springoo_woocommerce_menu_order' ) ) {

	// Arrange Menu items
	function springoo_woocommerce_menu_order( $menu_links ) {
		if ( defined( 'YITH_WCWL' ) ) {
			$menu_links['my-wishlist'] = __( 'Wishlists', 'springoo' );
		}
		return $menu_links;
	}
}

if ( ! function_exists( 'springoo_show_wishlist' ) ) {

	if ( ! defined( 'YITH_WCWL' ) ) {
		return;
	}
	// Show data in frontend
	function springoo_show_wishlist() {
		echo do_shortcode( '[yith_wcwl_wishlist]' );
	}
}

if ( ! function_exists( 'springoo_yith_icon_extend' ) ) {
	/**
	 * Yith icon extension with springoo icons
	 *
	 * @param $icons
	 *
	 * @return mixed
	 */
	function springoo_yith_icon_extend( $icons ) {
		global $wp_filesystem;

		require_once ( ABSPATH . '/wp-admin/includes/file.php' ); //phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		WP_Filesystem();

		$springoo_icons = json_decode( $wp_filesystem->get_contents( SPRINGOO_THEME_DIR . 'inc/icon/springoo-icons.json' ), true );
		$themify_icons  = json_decode( $wp_filesystem->get_contents( SPRINGOO_THEME_DIR . 'inc/icon/themify-icons.json' ), true );

		return array_merge( $springoo_icons, $themify_icons, $icons );
	}
}

if ( ! function_exists( 'springoo_yith_browse_wcwl_label' ) ) {
	/**
	 * Yith Wishlist label text
	 *
	 * @return string
	 */
	function springoo_yith_browse_wcwl_label() {
		return '<span class="springoo-tooltip">' . get_option( 'yith_wcwl_browse_wishlist_text' ) . '</span>';
	}
}




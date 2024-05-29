<?php
/**
 * YITH WooCommerce Quick View hook helper
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! function_exists( 'springoo_yith_quick_view_button_html' ) ) {
	/**
	 * Springoo quick view button html
	 *
	 * @return string
	 */
	function springoo_yith_quick_view_button_html() {
		global $product;
		return '<a href="#" class="button yith-wcqv-button" data-product_id="' . esc_attr( $product->get_id() ) . '"><i class="si si-bold-quick-view"></i><span class="springoo-tooltip">' . esc_html ( get_option( 'yith-wcqv-button-label' ) ) . '</span></a>';
	}
}

if ( ! function_exists( 'springoo_quick_view_button' ) ) {
	/**
	 * Quick View Button
	 *
	 * @return void
	 */
	function springoo_quick_view_button() {
		remove_action( 'woocommerce_after_shop_loop_item', array( YITH_WCQV_Frontend(), 'yith_add_quick_view_button' ), 15 );
		add_action( 'springoo_shop_actions', array( YITH_WCQV_Frontend(), 'yith_add_quick_view_button' ), 10 );
	}
}

if ( ! function_exists( 'springoo_handle_quick_view_button_hooks' ) ) {
	function springoo_handle_quick_view_button_hooks() {
		if ( ! class_exists( 'YITH_WCQV' ) ) {
			return;
		}
		// remove quick view button after product Name
		remove_action( 'yith_wcwl_table_after_product_name', array( YITH_WCQV_Frontend(), 'add_quick_view_button_wishlist' ), 15 );
		// Add Quick View Button before add to cart
		add_action( 'yith_wcwl_table_product_before_add_to_cart', array( YITH_WCQV_Frontend(), 'add_quick_view_button_wishlist' ), 15);
	}
}


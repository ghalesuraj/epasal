<?php
/**
 * YITH WooCommerce Wishlist hooks
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}


add_filter( 'yith_wcwl_positions', 'springoo_wishlist_positions' );
add_filter( 'yith_wcwl_loop_positions', 'springoo_wishlist_loop_positions' );
add_filter( 'yith_wcwl_add_to_wishlist_options', 'springoo_wishlist_loop_options' );

add_filter('yith_wcwl_plugin_icons', 'springoo_yith_icon_extend' );
add_action('woocommerce_account_my-wishlist_endpoint', 'springoo_show_wishlist');
add_filter('woocommerce_account_menu_items', 'springoo_woocommerce_menu_order');
add_filter('query_vars', 'springoo_wishlist_query_vars');
add_action('init', 'springoo_add_wishlist_endpoint');

add_filter( 'yith_wcwl_browse_wishlist_label', 'springoo_yith_browse_wcwl_label' );

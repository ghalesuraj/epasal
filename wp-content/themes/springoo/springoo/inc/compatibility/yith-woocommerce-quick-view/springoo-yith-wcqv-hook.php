<?php
/**
 * YITH WooCommerce Quick View hooks
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

add_filter( 'yith_add_quick_view_button_html', 'springoo_yith_quick_view_button_html' );
add_action( 'init', 'springoo_quick_view_button' );

if ( defined( 'YITH_WCWL' ) ) {
	add_action( 'init', 'springoo_handle_quick_view_button_hooks' );
}

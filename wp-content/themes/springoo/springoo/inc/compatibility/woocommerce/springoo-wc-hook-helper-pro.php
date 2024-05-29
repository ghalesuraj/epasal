<?php
/**
 * WooCommerce Customizer Pro Settings
 *
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * @param $classes
 *
 * @return string
 */
function springoo_product_grid_class( $classes ) {
	$classes[] = 'custom gap-20';
	return $classes;
}

/**
 * Springoo Add to cart Button default classes
 *
 * @param $classes
 *
 * @return mixed
 */
function springoo_product_add_to_cart_btn_styles( $classes ) {
	$classes[] = 'fill-primary';
	return $classes;
}

/**
 * Springoo Add to cart text icon classes
 *
 * @param $classes
 *
 * @return mixed
 */
function springoo_product_show_button_text_icon( $classes ) {
	$classes[] = 'has-text';
	return $classes;
}



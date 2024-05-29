<?php
/**
 * @package Springoo
 * @author  ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Contains methods for adding Layout Customization Panel and all settings under it
 *
 */
class Springoo_Pro_Woocommerce_Products {

	public function __construct() {
		add_filter( 'springoo_product_loop_classes',  array( $this, 'springoo_pro_product_gap' ) );
		add_filter( 'after_setup_theme', array( $this, 'springoo_pro_add_to_cart_btn' ) );
		add_filter( 'springoo_product_btn_classes', array( $this, 'springoo_pro_add_cart_button_style' ) );
		add_filter( 'springoo_product_btn_classes', array( $this, 'springoo_pro_show_button_text_icon' ) );
		add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'springoo_pro_add_to_cart_btn') );
	}

	/**
	 * WooCommerce Product Gap
	 *
	 * @param $classes
	 * @return string
	 */
	public function springoo_pro_product_gap( $classes ) {
		$grid_gap = springoo_get_mod( 'woocommerce_shop_archive_grid_gap' );

		if( false === $grid_gap || 'no-gap' === $grid_gap ) {
			$classes[] = 'no-gap';
		}else {
			$classes[] = 'has-grid-gap';
		}
		return $classes;
	}

	/**
	 * WooCommerce Button text change
	 *
	 * @return string|void
	 */
	public function springoo_pro_add_to_cart_btn() {
		$btn_text = springoo_get_mod('woocommerce_shop_archive_btn_text', 'Add to Cart');

		if ( $btn_text ) {
			return esc_html__( $btn_text, 'springoo-pro' );
		}
	}

	/**
	 * Add to Cart Button styles
	 *
	 * @param $classes
	 * @return mixed
	 */
	public function springoo_pro_add_cart_button_style( $classes ){
		$link_style = springoo_get_mod( 'woocommerce_shop_archive_btn_type' );
		$link_style = $link_style ? $link_style : 'fill-primary';
		$classes[] = $link_style;

		return $classes;
	}

	/**
	 * Add to Cart button text icon
	 *
	 * @param $classes
	 * @return mixed
	 */
	public function springoo_pro_show_button_text_icon( $classes ) {

		$show_text = springoo_get_mod( 'woocommerce_shop_archive_show_btn_text' );
		if ( false === $show_text || 1 === (int) $show_text ) {
			$classes[] = 'has-text';
		}

		$show_icon = springoo_get_mod( 'woocommerce_shop_archive_show_btn_icon' );
		if ( 1 === (int) $show_icon ) {
			$classes[] = 'has-icon';
		}

		return $classes;
	}
}

new Springoo_Pro_Woocommerce_Products;

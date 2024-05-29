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
 * WooCommerce Single Product Gallery Class
 */
class Springoo_Pro_Woocommerce_Product_Gallery {

	public function __construct() {
		add_action( 'wp_head', [ $this, 'init_single_product_hooks' ], 99 );
	}

	/**
	 * Initiate Single Product Hooks
	 *
	 * @return void
	 */
	public function init_single_product_hooks() {

		if ( ! is_product() ) {
			return;
		}

		$gallery_style = springoo_get_mod( 'woocommerce_single_gallery_style' );

		$product = wc_get_product( get_the_ID() );
		$attachment_ids = $product->get_gallery_image_ids();

		if ( $attachment_ids && 'grid' === $gallery_style ) {
			remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
			add_action( 'woocommerce_before_single_product_summary', [ $this, 'product_gallery' ], 30 );
		} else {
			add_filter( 'woocommerce_single_product_image_gallery_classes', [ $this, 'product_gallery_style_class' ] );
		}
	}

	/**
	 * WooCommerce Gallery Class for multiple styles
	 *
	 * @param $classes
	 *
	 * @return mixed
	 */
	public function product_gallery_style_class( $classes ) {
		$gallery_style = springoo_get_mod( 'woocommerce_single_gallery_style' );

		if ( 'left' === $gallery_style ) {
			$classes[] = 'springoo-single-product-gallery-left';
		} elseif ( 'right' === $gallery_style ) {
			$classes[] = 'springoo-single-product-gallery-right';
		} else {
			$classes[] = 'springoo-single-product-gallery-default';
		}

		return $classes;
	}

	/**
	 * WooCommerce Single Product Gallery block styles
	 *
	 * @return void
	 */
	public function product_gallery(){
		if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
			return;
		}

		global $product;

		$post_thumbnail_id = $product->get_image_id();
		$attachment_ids    = $product->get_gallery_image_ids();

		if ( $post_thumbnail_id ) {
			$html = wc_get_gallery_image_html( $post_thumbnail_id, true );
		} else {
			$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
			$html .= '</div>';
		}

		echo "<div class='springoo-single-product-gallery'>";
		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
		if ( $attachment_ids && $product->get_image_id() ) {
			foreach ( $attachment_ids as $attachment_id ) {
				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id ), $attachment_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
			}
		}
		echo "</div>";
	}
}
new Springoo_Pro_Woocommerce_Product_Gallery;
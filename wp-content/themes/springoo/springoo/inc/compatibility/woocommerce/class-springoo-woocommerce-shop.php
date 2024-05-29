<?php
/**
 * Springoo WooCommerce Loop Override.
 *
 * @package Springoo
 * @author ThemeRox
 * @since 1.3.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! class_exists( 'Springoo_WooCommerce_Product_Loop' ) ) {
	class Springoo_WooCommerce_Shop {

		/**
		 * Springoo_WooCommerce_Product_Loop constructor.
		 *
		 * @return void
		 */
		public function __construct() {

			remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open' );
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title' );
			remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title' );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price' );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating' );

			// Product Classes.
			add_filter( 'woocommerce_post_class', array( $this, 'woocommerce_product_classes' ) );

			// Product Before & after loop.
			add_action( 'woocommerce_before_shop_loop_item', array( $this, 'before_products_loop' ) );
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'after_products_loop' ) );

			// Product loop.
			add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'before_shop_title' ) );
			add_action( 'woocommerce_shop_loop_item_title', array( $this, 'shop_title' ) );
			add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'after_shop_title' ) );

		}

		/**
		* WooCommerce Product Classes
		*
		* @param $class
		*
		* @return mixed
		*/
		public function woocommerce_product_classes( $class ) {
			$class[] = esc_attr( springoo_product_classes() );
			return $class;
		}

		/**
		* Springoo Before Products loop
		*
		* @return void
		*/
		public function before_products_loop() {
			echo '<div class="springoo-product-item-inner">';
		}

		/**
		* Springoo After Products loop
		*
		* @return void
		*/
		public function after_products_loop() {
			echo '</div>';
		}

		/**
		* WooCommerce before Shop title
		*
		* @return void
		*/
		public function before_shop_title() {
			/**
			 * Hook: springoo_shop_header.
			 *
			 * @hooked springoo_shop_header_open - 5
			 * @hooked springoo_shop_labels - 10
			 * @hooked springoo_shop_actions - 15
			 * @hooked springoo_shop_thumbnails - 20
			 * @hooked springoo_shop_header_close - 25
			 * @hooked springoo_shop_content_open - 30
			 * @hooked springoo_shop_category - 35
			 */
			do_action( 'springoo_before_shop_title' );
		}

		/**
		 * WooCommerce Shop title
		 *
		 * @return void
		 */
		public function shop_title() {
			/**
			 * Hook: springoo_shop_content.
			 *
			 * @hooked springoo_shop_title - 5
			 */
			do_action( 'springoo_shop_title' );
		}

		/**
		 * WooCommerce after Shop title
		 *
		 * @return void
		 */
		public function after_shop_title() {
			/**
			 * Hook: springoo_shop_header.
			 *
			 * @hooked springoo_shop_price - 5
			 * @hooked springoo_shop_rating - 10
			 * @hooked springoo_shop_content_close - 15
			 * @hooked springoo_shop_footer_open - 20
			 * @hooked springoo_shop_cart_btn - 25
			 * @hooked springoo_shop_footer_close - 30
			 */
			do_action( 'springoo_after_shop_title' );
		}
	}
	new Springoo_WooCommerce_Shop();
}

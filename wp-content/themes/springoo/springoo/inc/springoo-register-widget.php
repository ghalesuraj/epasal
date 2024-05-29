<?php
/**
 * Sidebar Config.
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! function_exists( 'springoo_widgets_init' ) ) :

	/**
	 * Registering widget / sidebar for the theme
	 *
	 * @return void
	 */
	function springoo_widgets_init() {

		// register Main sidebar widgets.
		register_sidebar(
			array(
				'name'          => __( 'Main Sidebar', 'springoo' ),
				'id'            => 'main-sidebar',
				'description'   => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s sidebar-widget clearfix">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Shop Sidebar', 'springoo' ),
				'id'            => 'shop-sidebar',
				'description'   => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s sidebar-widget clearfix">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Single Product Sidebar', 'springoo' ),
				'id'            => 'single-product-sidebar',
				'description'   => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s sidebar-widget clearfix">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Product Archive Banner Widget', 'springoo' ),
				'id'            => 'product-archive-banner-widget',
				'description'   => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s sidebar-widget clearfix">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Cart Sidebar', 'springoo' ),
				'id'            => 'cart-sidebar',
				'description'   => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s sidebar-widget clearfix">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Forum Sidebar', 'springoo' ),
				'id'            => 'forum-sidebar',
				'description'   => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s sidebar-widget clearfix">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);

		//404 page widgets
		register_sidebar(
			array(
				'name'          => __( '404 Page Widget', 'springoo' ),
				'id'            => '404-page-widget',
				'before_title'  => '<h5 class="widget-title 404-widget-title">',
				'after_title'   => '</h5>',
				'before_widget' => '<aside id="%1$s" class="widget %2$s sidebar-widget clearfix">',
				'after_widget'  => '</aside>',
			)
		);

		// no products found page widgets
		register_sidebar(
			array(
				'name'          => __( 'No Products Found Widget', 'springoo' ),
				'id'            => 'no-products-found-widget',
				'before_title'  => '<h5 class="widget-title no-products-found-widget-title">',
				'after_title'   => '</h5>',
				'before_widget' => '<aside id="%1$s" class="widget %2$s sidebar-widget clearfix">',
				'after_widget'  => '</aside>',
			)
		);


		// Register Footer Widgets.
		register_sidebar(
			array(
				'name'          => __( 'Footer Widget', 'springoo' ),
				'id'            => 'footer-widget',
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => "</h3>\n",
			)
		);

		// Register Footer Widgets.
		register_sidebar(
			array(
				'name'          => __( 'Footer Bottom Widget', 'springoo' ),
				'id'            => 'footer-bottom-widget',
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => "</h3>\n",
			)
		);
	}

endif;

add_action( 'widgets_init', 'springoo_widgets_init' );

<?php
/**
 * Register required plugin with tgm so user can be notifies which plugin need to install for the perfect output of the theme.
 *
 * @package Ayyash
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Hook into TGMPA and add recommended plugins
 *
 * @return void
 */
function springoo_recommended_plugins() {
	$plugins = apply_filters(
		'springoo_recommended_plugins',
		array(
			array(
				'slug'     => 'elementor',
				'name'     => __( 'Elementor', 'springoo' ),
				'source'   => 'https://wordpress.org/plugins/elementor/',
				'required' => true,
			),
			array(
				'slug'     => 'woocommerce',
				'name'     => __( 'WooCommerce', 'springoo' ),
				'source'   => 'https://wordpress.org/plugins/woocommerce/',
				'required' => true,
			),
			array(
				'slug'     => 'contact-form-7',
				'name'     => __( 'Contact Form 7', 'springoo' ),
				'source'   => 'https://wordpress.org/plugins/contact-form-7/',
				'required' => true,
			),
			array(
				'slug'     => 'mailchimp-for-wp',
				'name'     => __( 'MC4WP: Mailchimp for WordPress', 'springoo' ),
				'source'   => 'https://wordpress.org/plugins/mailchimp-for-wp/',
				'required' => true,
			),
			array(
				'slug'     => 'woo-variation-swatches',
				'name'     => __( 'Variation Swatches for WooCommerce', 'springoo' ),
				'source'   => 'https://wordpress.org/plugins/woo-variation-swatches/',
				'required' => true,
			),
			array(
				'slug'     => 'yith-woocommerce-wishlist',
				'name'     => __( 'YITH WooCommerce Wishlist', 'springoo' ),
				'source'   => 'https://wordpress.org/plugins/yith-woocommerce-wishlist/',
				'required' => true,
			),
			array(
				'slug'     => 'yith-woocommerce-quick-view',
				'name'     => __( 'YITH WooCommerce Quick View', 'springoo' ),
				'source'   => 'https://wordpress.org/plugins/yith-woocommerce-quick-view/',
				'required' => true,
			),
			array(
				'slug'     => 'yith-woocommerce-compare',
				'name'     => __( 'YITH WooCommerce Compare', 'springoo' ),
				'source'   => 'https://wordpress.org/plugins/yith-woocommerce-compare/',
				'required' => true,
			),
			array(
				'slug'     => 'one-click-demo-import',
				'name'     => __( 'One Click Demo Import', 'springoo' ),
				'source'   => 'https://wordpress.org/plugins/one-click-demo-import/',
				'required' => true,
			),
			array(
				'slug'     => 'popup-maker',
				'name'     => __( 'Popup Maker – Popup for opt-ins, lead gen, & more', 'springoo' ),
				'source'   => 'https://wordpress.org/plugins/popup-maker/',
				'required' => true,
			),
			array(
				'slug'     => 'woocommerce-currency-switcher',
				'name'     => __( 'FOX – Currency Switcher Professional for WooCommerce', 'springoo' ),
				'source'   => 'https://wordpress.org/plugins/woocommerce-currency-switcher/',
				'required' => false,
			),
			array(
				'slug'     => 'polylang',
				'name'     => __( 'Polylang', 'springoo' ),
				'source'   => 'https://wordpress.org/plugins/polylang/',
				'required' => false,
			),
			array(
				'slug'     => 'safe-svg',
				'name'     => __( 'Safe SVG', 'springoo' ),
				'source'   => 'https://wordpress.org/plugins/safe-svg/',
				'required' => false,
			),
			array(
				'slug'     => 'springoo-pro',
				'name'     => __( 'Springoo Pro', 'springoo' ),
				'source'   => 'https://s3.amazonaws.com/themerox.com/springoo/plugins/springoo-pro.zip',
				'required' => true,
			),
			array(
				'slug'     => 'ayyash-addons',
				'name'     => __( 'Ayyash Addons', 'springoo' ),
				'source'   => 'https://s3.amazonaws.com/themerox.com/springoo/plugins/ayyash-addons.zip',
				'required' => true,
			),
			array(
				'slug'     => 'ayyash-addons-pro',
				'name'     => __( 'Ayyash Addons Pro', 'springoo' ),
				'source'   => 'https://s3.amazonaws.com/themerox.com/springoo/plugins/ayyash-addons-pro.zip',
				'required' => true,
			),
			array(
				'slug'     => 'revslider',
				'name'     => __( 'Slider Revolution', 'springoo' ),
				'source'   => 'https://s3.amazonaws.com/themerox.com/springoo/plugins/revslider.zip',
				'required' => true,
			),
		)
	);

	return $plugins;
}

/**
 * Hook into TGMPA and add recommended plugins
 *
 * @return void
 */

function springoo_tgmpa_plugins() {
	$plugins = springoo_recommended_plugins();
	tgmpa( $plugins ); // @phpstan-ignore-line
}

add_action( 'tgmpa_register', 'springoo_tgmpa_plugins' );

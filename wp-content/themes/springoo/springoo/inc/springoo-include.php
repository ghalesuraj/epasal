<?php
/**
 * Load Core Files.
 *
 * @package Springoo
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/** @define "SPRINGOO_THEME_DIR" "./../" */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

// phpcs:disable WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

// Load customizer.
require SPRINGOO_THEME_DIR . 'inc/customizer/class-springoo-customize.php';

// Load core theme files.
require SPRINGOO_THEME_DIR . 'inc/springoo-tags.php';
require SPRINGOO_THEME_DIR . 'inc/springoo-helper-functions.php';
require SPRINGOO_THEME_DIR . 'inc/springoo-hooks.php';
require SPRINGOO_THEME_DIR . 'inc/springoo-hook-helper.php';
require SPRINGOO_THEME_DIR . 'inc/springoo-register-widget.php';
require SPRINGOO_THEME_DIR . 'inc/class-springoo-breadcrumb.php';

// load springoo widgets
require SPRINGOO_THEME_DIR . 'inc/springoo-widgets.php';

// Springoo Includes
require SPRINGOO_THEME_DIR . 'inc/class-tgm-plugin-activation.php';
require SPRINGOO_THEME_DIR . 'inc/springoo-recommend-plugins.php';

if ( ! class_exists( 'Springoo_Pro' ) ) {
	require SPRINGOO_THEME_DIR . 'inc/compatibility/woocommerce/springoo-wc-hook-helper-pro.php';
}
// Jetpack Compatibility
if ( class_exists( 'Jetpack' ) ) {
	require SPRINGOO_THEME_DIR . 'inc/compatibility/jetpack/jetpack.php';
}

// Elementor Related Options
if ( class_exists( 'Elementor\Plugin' ) ) {
	require SPRINGOO_THEME_DIR . 'inc/compatibility/elementor/class-springoo-global-color.php';
	require SPRINGOO_THEME_DIR . 'inc/compatibility/elementor/class-springoo-global-typography.php';
	require SPRINGOO_THEME_DIR . 'inc/compatibility/elementor/class-icons-library.php';
}

// Dokan
if ( class_exists( 'WeDevs_Dokan' ) ) {
	require SPRINGOO_THEME_DIR . 'inc/compatibility/dokan/springoo-dokan-hook-helper.php';
	require SPRINGOO_THEME_DIR . 'inc/compatibility/dokan/springoo-dokan-hook.php';
}

// Contact Form 7
if ( defined( 'WPCF7_PLUGIN' ) ) {
	require SPRINGOO_THEME_DIR . 'inc/compatibility/contact-form-7/springoo-cf7-hook-helper.php';
	require SPRINGOO_THEME_DIR . 'inc/compatibility/contact-form-7/springoo-cf7-hook.php';
}

// WooCommerce & Related Addons.
if ( class_exists( 'WooCommerce' ) ) {
	require SPRINGOO_THEME_DIR . 'inc/compatibility/woocommerce/class-springoo-review-ajax.php';
	require SPRINGOO_THEME_DIR . 'inc/compatibility/woocommerce/class-springoo-woocommerce-shop.php';
	require SPRINGOO_THEME_DIR . 'inc/compatibility/woocommerce/springoo-wc-helper.php';
	require SPRINGOO_THEME_DIR . 'inc/compatibility/woocommerce/springoo-wc-hook-helper.php';
	require SPRINGOO_THEME_DIR . 'inc/compatibility/woocommerce/springoo-wc-hook.php';
}

// YITH WooCommerce Wishlist
if ( class_exists( 'WooCommerce' ) && defined( 'YITH_WCWL' ) ) {
	require SPRINGOO_THEME_DIR . 'inc/compatibility/yith-woocommerce-wishlist/springoo-yith-wcwl-helper.php';
	require SPRINGOO_THEME_DIR . 'inc/compatibility/yith-woocommerce-wishlist/springoo-yith-wcwl-hook-helper.php';
	require SPRINGOO_THEME_DIR . 'inc/compatibility/yith-woocommerce-wishlist/springoo-yith-wcwl-hook.php';
}

// YITH WooCommerce Compare
if ( class_exists( 'WooCommerce' ) && defined( 'YITH_WOOCOMPARE' ) ) {
	require SPRINGOO_THEME_DIR . 'inc/compatibility/yith-woocommerce-compare/springoo-yith-compare-hook-helper.php';
	require SPRINGOO_THEME_DIR . 'inc/compatibility/yith-woocommerce-compare/springoo-yith-compare-hook.php';
}

// YITH WooCommerce Quick View
if ( class_exists( 'WooCommerce' ) && defined( 'YITH_WCQV' ) ) {
	require SPRINGOO_THEME_DIR . 'inc/compatibility/yith-woocommerce-quick-view/springoo-yith-wcqv-hook-helper.php';
	require SPRINGOO_THEME_DIR . 'inc/compatibility/yith-woocommerce-quick-view/springoo-yith-wcqv-hook.php';
}

// Mega Menu Extension.
require SPRINGOO_THEME_DIR . 'inc/menu/menu.php';

// One Click Demo installer
if ( class_exists( 'OCDI_Plugin' ) ) {
	require SPRINGOO_THEME_DIR . 'inc/springoo-ocdi-settings.php';
}

function springoo_asset_path( $path = '' ) {
	return SPRINGOO_THEME_DIR . 'assets/' . ltrim( rtrim( $path, '/\\' ), '/\\' );
}

function springoo_asset_url( $path = '' ) {
	return SPRINGOO_THEME_URI . 'assets/' . ltrim( rtrim( $path, '/\\' ), '/\\' );
}

function springoo_asset_version( $path ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		$asset = springoo_asset_path( $path );

		if ( file_exists( $asset ) ) {
			return filemtime( $asset );
		}
	}

	return SPRINGOO_THEME_VERSION;
}

if ( ! function_exists( 'springoo_scripts' ) ) {
	/**
	 * Enqueue scripts and styles.
	 *
	 * @return void
	 */
	function springoo_scripts() {

		// Enqueue required styles.
		wp_enqueue_style( 'springoo-icons', springoo_asset_url( 'dist/css/springoo-icons.css' ), [], springoo_asset_version( 'plugins/css/springoo-icons.css' ) );
		wp_enqueue_style( 'springoo-bootstrap', springoo_asset_url( 'dist/css/bootstrap.css' ), [], springoo_asset_version( 'dist/css/bootstrap.css' ) );

		wp_enqueue_style( 'springoo-themify-icons', springoo_asset_url( 'plugins/themify-icons/themify-icons.css' ), [], springoo_asset_version( 'plugins/themify-icons/themify-icons.css' ) );
		wp_enqueue_style( 'springoo-fontawesome-icons', springoo_asset_url( 'plugins/fontawesome/css/fontawesome.min.css' ), [], springoo_asset_version( 'plugins/fontawesome/css/fontawesome.min.css' ) );
		wp_enqueue_style( 'springoo-plyr', springoo_asset_url( 'plugins/plyr/plyr.min.css' ), [], springoo_asset_version( 'plugins/plyr/plyr.min.css' ) );
		wp_enqueue_style( 'springoo-slick-slider', springoo_asset_url( 'plugins/slick/slick.css' ), [], springoo_asset_version( 'plugins/slick/slick.css' ) );
		wp_enqueue_style( 'springoo-main', springoo_asset_url( 'dist/css/springoo_main.css' ), [], springoo_asset_version( 'dist/css/springoo_main.css' ) );

		if ( class_exists( 'WeDevs_Dokan' ) ) {
			wp_enqueue_style( 'springoo-dokan', springoo_asset_url( 'dist/css/dokan.css' ), [], springoo_asset_version( 'dist/css/dokan.css' ) );
		}

		if ( 1 === (int) springoo_get_mod( 'preload_enable' ) ) {
			wp_enqueue_style( 'springoo-animate-css', springoo_asset_url( 'dist/css/animate.css' ), [], springoo_asset_version( 'dist/css/animate.css' ) );
		}

		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_style( 'springoo-woocommerce', springoo_asset_url( 'dist/css/woocommerce.css' ), [], springoo_asset_version( 'dist/css/woocommerce.css' ) );
		}

		if ( class_exists( 'WPCF7_ContactForm' ) ) {
			wp_enqueue_style( 'springoo-wpcf7', springoo_asset_url( 'dist/css/wpcf7.css' ), [], springoo_asset_version( 'dist/css/wpcf7.css' ) );
		}


		// Enqueue required scripts.
		wp_enqueue_script( 'springoo-jquery-easing', springoo_asset_url( 'plugins/jquery-easing/jquery.easing.min.js' ), [ 'jquery' ], springoo_asset_version( 'plugins/jquery-easing/jquery.easing.min.js' ), true );

		if ( springoo_get_mod( 'smoothscroll' ) ) {
			wp_enqueue_script( 'springoo-smoothscroll', springoo_asset_url( 'plugins/smoothscroll/SmoothScroll.min.js' ), [], springoo_asset_version( 'plugins/smoothscroll/SmoothScroll.min.js' ), true );
		}

		wp_enqueue_script( 'jquery-masonry' );

		wp_enqueue_script( 'springoo-lazyLoad', springoo_asset_url( 'plugins/lazyLoad/jquery.lazy.min.js' ), [ 'jquery' ], springoo_asset_version( 'plugins/lazyLoad/jquery.lazy.min.js' ), true );
		wp_enqueue_script( 'springoo-plyr', springoo_asset_url( 'plugins/plyr/plyr.min.js' ), [], springoo_asset_version( 'plugins/plyr/plyr.min.js' ), true );

		wp_enqueue_script( 'springoo-waypoints', springoo_asset_url( 'plugins/waypoints/waypoints.min.js' ), [ 'jquery' ], springoo_asset_version( 'plugins/waypoints/waypoints.min.js' ), true );
		//Countdown Timer
		wp_enqueue_script( 'springoo-psgTimer', springoo_asset_url( 'plugins/psgtimer/jquery.psgTimer.js' ), [ 'jquery' ], springoo_asset_version( 'plugins/psgtimer/jquery.psgTimer.js' ), true );
		// Slick Slider
		wp_enqueue_script( 'springoo-slick-slider', springoo_asset_url( 'plugins/slick/slick.min.js' ), [ 'jquery' ], springoo_asset_version( 'plugins/slick/slick.min.js' ), true );

		wp_enqueue_script( 'superfish', springoo_asset_url( 'dist/js/superfish.js' ), [
			'jquery',
			'hoverIntent',
		], springoo_asset_version( 'dist/js/superfish.js' ), true );

		wp_enqueue_script( 'springoo-main', springoo_asset_url( 'dist/js/springoo.js' ), [
			'superfish',
			'jquery',
			'wp-util',
			'underscore',
		], springoo_asset_version( 'dist/js/springoo.js' ), true );

		// YITH  helper.
		if ( class_exists( 'YITH_WCQV' ) || class_exists( 'YITH_Woocompare' ) || defined( 'YITH_WCWL' ) ) {
			wp_enqueue_script( 'springoo-yith-frontend-helper', springoo_asset_url( 'dist/js/yith-frontend-helper.js' ), [ 'jquery' ], springoo_asset_version( 'dist/js/yith-frontend-helper.js' ), true );
		}

		$sticky_viewport = springoo_get_mod( 'layout_header_menu_mobileshow' );

		switch ( $sticky_viewport ) {
			case 'sm':
				$sticky_viewport_size = '576';
				break;
			case 'md':
				$sticky_viewport_size = '991';
				break;
			case 'lg':
			case 'xl':
				$sticky_viewport_size = '1201';
				break;
			default:
				$sticky_viewport_size = '768';
				break;
		}

		wp_localize_script(
			'springoo-main',
			'springoo_ajax',
			[
				'ajaxurl'                => admin_url( 'admin-ajax.php' ),
				'siteurl'                => home_url(),
				'error'                  => esc_html__( 'Error!', 'springoo' ),
				'asseturl'               => springoo_asset_url( 'dist' ),
				'nonce'                  => wp_create_nonce( 'springoo-nonce' ),
				'viewport'               => $sticky_viewport_size,
				'sticky'                 => springoo_get_mod( 'layout_header_sticky' ),
				'header'                 => springoo_get_mod( 'layout_header_sticky_height' ),
				'accent'                 => ( springoo_get_mod( 'skin' ) !== 'default' ) ? springoo_get_mod( 'accent_color' ) : '#428bca',
				'no_smoothscroll'        => ( springoo_get_mod( 'smoothscroll' ) ) ? '1' : '0',
				'post_id'                => get_the_ID(),
				'fb_app_id'              => springoo_get_mod( 'woocommerce_share_product_fb_share' ),
				'twitter_user'           => springoo_get_mod( 'woocommerce_share_product_twitter_share' ),
				'i18n'                   => [
					'click_to_upload' => esc_html__( 'Click here to upload', 'springoo' ),
					'cancel'          => esc_html__( 'Cancel', 'springoo' ),
					'submit'          => esc_html__( 'Submit', 'springoo' ),
					'success'         => esc_html__( 'Success', 'springoo' ),
					'warning'         => esc_html__( 'Warning', 'springoo' ),
					'error'           => esc_html__( 'Error', 'springoo' ),
					'e404'            => esc_html__( 'Requested Resource Not Found!', 'springoo' ),
					'are_you_sure'    => esc_html__( 'Are You Sure?', 'springoo' ),
					'min_3'           => esc_html__( 'Please type Minimum 3 character!', 'springoo' ),
					'wait'            => esc_html__( 'Please wait...', 'springoo' ),
					'wrong'           => esc_html__( 'Something went wrong. Please try after sometime.', 'springoo' ),
				],
				'review_image_number'    => get_option( 'woocommerce_review_image_number', 5 ),
				'review_image_file_size' => get_option( 'woocommerce_review_image_file_size', 2 ),
			]
		);
	}
}

add_action( 'wp_enqueue_scripts', 'springoo_scripts' );


if ( ! function_exists( 'springoo_admin_scripts' ) ) {
	/**
	 * Enqueue Admin scripts and styles.
	 *
	 * @param string $hook admin page hook.
	 *
	 * @return void
	 */
	function springoo_admin_scripts() {
		wp_enqueue_style( 'springoo-icons', springoo_asset_url( 'dist/css/springoo-icons.css' ), [], springoo_asset_version( 'dist/css/springoo-icons.css' ) );

		wp_enqueue_style( 'springoo-themify-icons', springoo_asset_url( 'plugins/themify-icons/themify-icons.css' ), [], springoo_asset_version( 'plugins/themify-icons/themify-icons.css' ) );
		wp_enqueue_style( 'springoo-fontawesome-icons', springoo_asset_url( 'plugins/fontawesome/css/fontawesome.min.css' ), [], springoo_asset_version( 'plugins/fontawesome/css/fontawesome.min.css' ) );

		if ( ! is_customize_preview() ) {
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
		}

		// Admin & Customizer.
		wp_enqueue_style( 'springoo-admin', springoo_asset_url( 'dist/css/admin.css' ), [], springoo_asset_version( 'dist/css/admin.css' ) );

		// Mega Menu Editor.
		wp_enqueue_style( 'springoo-mega-menu', springoo_asset_url( 'dist/css/edit-mega-menu.css' ), [], springoo_asset_version( 'dist/css/edit-mega-menu.css' ) );
		wp_enqueue_script( 'springoo-mega-menu', springoo_asset_url( 'dist/js/edit-mega-menu.js' ), [
			'jquery',
			'jquery-ui-dialog',
		], springoo_asset_version( 'dist/js/edit-mega-menu.js' ), true );
		wp_enqueue_script( 'wp-codemirror' );

		// YITH  helper.
		wp_enqueue_script( 'springoo-yith-admin-helper', SPRINGOO_THEME_URI . 'assets/dist/js/yith-admin-helper.js', [
			'jquery',
			'yith-wcwl-admin',
		], SPRINGOO_THEME_VERSION, true );

		// Enqueue required scripts.
		wp_enqueue_script( 'springoo-admin', springoo_asset_url( 'dist/js/admin.js' ), [], springoo_asset_version( 'dist/js/admin.js' ), true );
	}
}

add_action( 'admin_enqueue_scripts', 'springoo_admin_scripts' );

// End of file springoo-include.php

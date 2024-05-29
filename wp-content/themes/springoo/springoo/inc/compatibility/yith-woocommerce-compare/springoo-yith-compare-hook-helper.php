<?php
/**
 * YITH WooCommerce Compare Hook helper.
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! function_exists( 'springoo_add_compare_link' ) ) {
	/**
	 * @param int|false $product_id
	 * @param array $args
	 *
	 * @return void
	 */
	function springoo_add_compare_link( $product_id = false, $args = array() ) {
		global $yith_woocompare;
		extract( $args ); // phpcs:ignore

		if ( ! $product_id ) {
			global $product;
			$product_id = ! is_null( $product ) ? $product->get_id() : 0;
		}

		// Return if product doesn't exist.
		if ( empty( $product_id ) || apply_filters( 'yith_woocompare_remove_compare_link_by_cat', false, $product_id ) ) {
			return;
		}

		$is_button = ! isset( $button_or_link ) || ! $button_or_link ? get_option( 'yith_woocompare_is_button', 'button' ) : $button_or_link;

		if ( ! isset( $button_text ) || 'default' === $button_text ) {
			$button_text  = get_option( 'yith_woocompare_button_text', __( 'Compare', 'springoo' ) );
			$compare_icon = get_option( 'springoo_yith_woocompare_compare_icon', 'si-thin-compare' );
			do_action( 'wpml_register_single_string', 'Plugins', 'plugin_yit_compare_button_text', $button_text );
			$button_text = apply_filters( 'wpml_translate_single_string', $button_text, 'Plugins', 'plugin_yit_compare_button_text' );
		}

		printf( '<div class="yith-wcwl-compare springoo-compare-btn"><a href="%s" class="%s" data-product_id="%d" rel="nofollow"><i class="si %s" aria-hidden="true"></i><span class="springoo-tooltip">%s</span></a></div>', $yith_woocompare->obj->add_product_url( $product_id ), 'compare' . ( 'button' === $is_button ? ' button' : '' ), $product_id, $compare_icon, $button_text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'springoo_handle_yith_compare_hooks' ) ) {
	/**
	 *  @return void
	 */
	function springoo_handle_yith_compare_hooks() {
		/** @var YITH_Woocompare $yith_woocompare */
		global $yith_woocompare;


		// Bail if yith compare not enabled.
		if ( ! isset( $yith_woocompare ) ) { // @phpstan-ignore-line
			return;
		}

		if ( ! $yith_woocompare->is_frontend() ) {
			return;
		}

		/**
		 * APPLY_FILTERS: yith compare positions
		 *
		 * Filter the array of positions where to display the 'Add to wishlist' button in the product page.
		 *
		 * @param array $positions Array of positions
		 *
		 * @return array
		 */
		$positions = apply_filters(
			'yith_compare_positions',
			array(
				'after_add_to_cart' => array(
					'hook'     => 'springoo_product_actions',
					'priority' => 20,
				),
				'add-to-cart'       => array(
					'hook'     => 'springoo_product_actions',
					'priority' => 20,
				),
				'thumbnails'        => array(
					'hook'     => 'woocommerce_product_thumbnails',
					'priority' => 21,
				),
				'summary'           => array(
					'hook'     => 'woocommerce_after_single_product_summary',
					'priority' => 11,
				),
			)
		);

		// Add the link "Add to Compare".
		$position = get_option('springoo_yith_woocompare_position', 'add-to-cart');

		if ( 'yes' === get_option( 'yith_woocompare_compare_button_in_product_page', 'yes' ) ) {
			remove_action( 'woocommerce_single_product_summary', [ $yith_woocompare->obj, 'add_compare_link' ], 35 ); // @phpstan-ignore-line

			if ( isset( $positions[ $position ] ) ) {
				add_action( $positions[ $position ]['hook'], 'springoo_add_compare_link', $positions[ $position ]['priority'] );
			}
		}

		if ( 'yes' === get_option( 'yith_woocompare_compare_button_in_products_list', 'no' ) ) {
			remove_action( 'woocommerce_after_shop_loop_item', [ $yith_woocompare->obj, 'add_compare_link' ], 20 ); // @phpstan-ignore-line
			add_action( 'springoo_shop_actions', 'springoo_add_compare_link', 15 );
		}
	}
}

if ( ! function_exists( 'springoo_yith_compare_icons' ) ) {
	/**
	 * Return array of available icons
	 *
	 * @param string $none_label   Label to use for none option.
	 * @param string $custom_label Label to use for custom option.
	 *
	 * @return array Array of available icons, in class => name format
	 */
	function springoo_yith_compare_icons( $none_label = '', $custom_label = '' ) {
		global $wp_filesystem;

		require_once ( ABSPATH . '/wp-admin/includes/file.php' ); //phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		WP_Filesystem();

		$springoo_icons = json_decode( $wp_filesystem->get_contents( SPRINGOO_THEME_DIR . 'inc/icon/springoo-icons.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$themify_icons  = json_decode( $wp_filesystem->get_contents( SPRINGOO_THEME_DIR . 'inc/icon/themify-icons.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		$icons = array_merge( $springoo_icons, $themify_icons );

		$icons['none']   = $none_label ? $none_label : __( 'None', 'springoo' );
		$icons['custom'] = $custom_label ? $custom_label : __( 'Custom', 'springoo' );

		/**
		 * APPLY_FILTERS: springoo_yith_compare_icons
		 *
		 * Filter the icons used in the plugin.
		 *
		 * @param array  $icons        Icons
		 * @param string $none_label   Label to use for none option
		 * @param string $custom_label Label to use for custom option
		 *
		 * @return array
		 */
		return apply_filters( 'springoo_yith_compare_icons', $icons, $none_label, $custom_label );
	}
}

if ( ! function_exists( 'springoo_yith_woocompare_admin_tab' ) ) {
	/**
	 * Springoo YITH Compare custom tab
	 *
	 * @param $admin_tabs
	 *
	 * @return mixed
	 */
	function springoo_yith_woocompare_admin_tab( $admin_tabs ) {
		$springoo_admin_tab = array(
			'springoo_settings' => __( 'Springoo Settings', 'springoo' ),
		);
		return array_merge( $admin_tabs, $springoo_admin_tab );
	}
}

if ( ! function_exists( 'springoo_yith_compare_position' ) ) {
	/**
	 * Yith Compare Position Settings
	 *
	 * @param $settings
	 *
	 * @return array
	 */
	function springoo_yith_compare_position( $settings ) {

		$settings['springoo_settings'][] = array(
			'name' => __( 'Springoo Settings', 'springoo' ),
			'type' => 'title',
			'desc' => '',
			'id'   => 'springoo_yith_woocompare',
		);
		$settings['springoo_settings'][] = array(
			'name'     => __( 'Springoo single product position', 'springoo' ),
			'desc_tip' => __( 'Choose if you want to change compare button location for Product summary area.', 'springoo' ),
			'id'       => 'springoo_yith_woocompare_position',
			'default'  => 'add-to-cart',
			'type'     => 'select',
			'class'    => 'wc-enhanced-select',
			'options'  => array(
				'add-to-cart' => __( 'After "Add to cart"', 'springoo' ),
				'thumbnails'  => __( 'After thumbnails', 'springoo' ),
				'summary'     => __( 'After summary', 'springoo' ),
			),
		);

		$settings['springoo_settings'][] = array(
			'name'      => __( '"Compare" icon', 'springoo' ),
			'desc'      => __( 'Select an icon for Compare button', 'springoo' ),
			'id'        => 'springoo_yith_woocompare_compare_icon',
			'default'   => 'si-thin-compare',
			'type'      => 'yith-field',
			'yith-type' => 'select',
			'class'     => 'icon-select',
			'options'   => springoo_yith_compare_icons(),
		);

		$settings['springoo_settings'][] = array(
			'type' => 'sectionend',
			'id'   => 'springoo_yith_woocompare_end',
		);

		return $settings;

	}
}

if ( ! function_exists( 'springoo_yith_woocompare_localize_array' ) ) {
	/**
	 * Yith Compare localize Settings
	 *
	 * @param $array
	 *
	 * @return array
	 */
	function springoo_yith_woocompare_localize_array( $array ) {
		$compare_icon = get_option( 'springoo_yith_woocompare_compare_icon', 'ai-compare' );

		if ( is_shop() ) {
			$array['button_text'] = "<i class=\"fa {$compare_icon}\"></i><span class='springoo-tooltip'>" . get_option( 'yith_woocompare_button_text', __( 'Compare', 'springoo' ) ) . '</span>';
		} else {
			$array['button_text'] = "<i class=\"fa {$compare_icon}\"></i>" . get_option( 'yith_woocompare_button_text', __( 'Compare', 'springoo' ) );
		}

		return $array;
	}
}


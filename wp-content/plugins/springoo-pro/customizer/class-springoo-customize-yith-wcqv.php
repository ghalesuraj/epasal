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
class Springoo_Customize_YITH_WCQV {
	/**
	 * Register panel and add settings.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer Instance.
	 *
	 * @return void
	 */
	public static function register( $wp_customize ) {

		// Shop & Archive page.
		$section_id = 'woocommerce_shop_archive';

		$setting_id = 'yith-wcqv-button-label';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'option',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Yith Quick View Button text', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);
	}
}

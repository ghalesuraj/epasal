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
class Springoo_Customize_WooCommerce_Pro_Single {

	/**
	 * Register panel and add settings.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer Instance.
	 *
	 * @return void
	 */
	public static function register( $wp_customize ) {

		// Woocommerce Single.
		$section_id = 'woocommerce_single';

		// Shop single Add to cart button.
		$setting_id = $section_id . '_gallery_style_label';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'              => 'helptext',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'   => __( 'Product Gallery ( Pro )', 'springoo' ),
					'section' => $section_id,
					'type'    => 'helptext',
				)
			)
		);

		$setting_id = $section_id . '_gallery_style';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Product Gallery Styles', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);


		// Summery
		$setting_id = $section_id . 'summery';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'              => 'helptext',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'   => __( 'Product Summery ( Pro )', 'springoo' ),
					'section' => $section_id,
					'type'    => 'helptext',
				)
			)
		);

		$setting_id = $section_id . '_summery_sticky_enable';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Enable Sticky Summery?', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		// Shop single Add to cart button.
		$setting_id = $section_id . '_cart_btn_label';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'              => 'helptext',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'   => __( 'Add to Cart Button settings ( Pro )', 'springoo' ),
					'section' => $section_id,
					'type'    => 'helptext',
				)
			)
		);

		$setting_id = $section_id . '_cart_btn_type';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Add to Cart Button Type', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = $section_id . '_cart_btn_radius';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new Springoo_Customizer_Range_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Add to Cart Button Radius', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Shop single buy button.
		$setting_id = $section_id . '_buy_btn_label';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'              => 'helptext',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'   => __( 'Buy now button settings ( Pro )', 'springoo' ),
					'section' => $section_id,
					'type'    => 'helptext',
				)
			)
		);

		$setting_id = $section_id . '_buy_btn_enable';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Show Buy now button?', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = $section_id . '_buy_btn_type';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Buy Button Type', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = $section_id . '_buy_btn_radius';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new Springoo_Customizer_Range_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Buy now Button Radius', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);


		// Shop Single notice.
		$setting_id = $section_id . '_store_info';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'              => 'helptext',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'   => __( 'Store notice', 'springoo' ),
					'section' => $section_id,
					'type'    => 'helptext',
				)
			)
		);

		$setting_id = $section_id . '_store_info_enable';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Show Store Info', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = $section_id . '_store_info_label';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Store Info Label', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = $section_id . '_store_info_content';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_textarea_field',
			)
		);
		$wp_customize->add_control(
			new Springoo_Textarea_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Store Info', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
					'description' => __( 'For multiple notices use comma to separate ', 'springoo' ),
				)
			)
		);


		// Shop Single Stock Label.
		$setting_id = $section_id . '_stock_label';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'              => 'helptext',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Product Stock ', 'springoo-pro' ),
					'section'     => $section_id,
					'type'        => 'helptext',
				)
			)
		);

		//Shop single stock
		$setting_id = $section_id . '_stock';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Product Stock', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		//Shop Single Stock Position
		$setting_id = $section_id . '_stock_position';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Stock Position', 'springoo-pro' ),
					'description' => __( 'Stock Position Will Work When product stock has selected "Custom"!', 'springoo-pro' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
					'type'        => 'select',
					'choices'     => springoo_get_choices( $setting_id ),
				)
			)
		);
	}
}

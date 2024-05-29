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
class Springoo_Customize_WooCommerce_Pro {

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

		/** Product Settings */
		$setting_id = 'woocommerce_shop_archive_grid_settings_separator';

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
					'label'       => __( 'Product Settings ', 'springoo-pro' ),
					'section'     => $section_id,
					'type'        => 'helptext',
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_show_product_price';

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
					'label'    => __( 'Show Product Price?', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_show_product_rating';

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
					'label'    => __( 'Show Product Rating?', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_show_product_rating_count';

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
					'label'    => __( 'Show Product Rating Count?', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_show_product_category';

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
					'label'    => __( 'Show Product Category?', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_show_product_stock';

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
					'label'    => __( 'Show Product Stock?', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		/**
		 * Grid Gap Settings
		 */
		$setting_id = 'woocommerce_shop_archive_grid_gap_section_separator';

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
					'label'       => __( 'Grid Gap Settings ', 'springoo-pro' ),
					'section'     => $section_id,
					'type'        => 'helptext',
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_grid_gap';

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
					'label'    => __( 'Grid Gap', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_grid_gap_size';

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
					'label'    => __( 'Grid Gap', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		/**
		 * Product Action Settings
		 */

		$setting_id = 'woocommerce_shop_archive_action_section_separator';

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
					'label'       => __( 'Product Action Settings ', 'springoo-pro' ),
					'section'     => $section_id,
					'type'        => 'helptext',
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_action_pos';

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
					'label'    => __( 'Product Action Position', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_action_style';

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
					'label'    => __( 'Product Action Style', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		/**
		 * Product Label Settings.
		 */
		$setting_id = 'woocommerce_shop_archive_label_section_separator';

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
					'label'       => __( 'Product Label Settings ', 'springoo-pro' ),
					'section'     => $section_id,
					'type'        => 'helptext',
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_label_type';

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
					'label'    => __( 'Product Label Type', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_label_style';

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
					'label'    => __( 'Product Label Style', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_label_top_position';

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
					'label'    => __( 'Product Label Top Position', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_label_left_position';

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
					'label'    => __( 'Product Label Left Position', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_show_discount';

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
					'label'    => __( 'Show Discount badge', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_show_featured';

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
					'label'    => __( 'Show Featured badge', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_show_stock';

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
					'label'    => __( 'Show Stock badge', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'woocommerce_shop_archive_show_product_badge';

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
					'label'    => __( 'Show Product badge', 'springoo-pro' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

	}
}

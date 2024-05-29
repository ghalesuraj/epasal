<?php
/**
 * @package Springoo
 * @author  Springoo
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Contains methods for adding Layout Customization Panel and all settings under it
 *
 * @since Pxlrtheme 1.0
 */
class Springoo_Customize_WPCF7 {

	/**
	 * Register panel and add settings.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer Instance.
	 *
	 * @return void
	 */
	public static function register( $wp_customize ) {

		// Woocommerce General.
		$section_id = 'wpcf7';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Contact form 7', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
			)
		);

		// Form Columns
		$setting_id = 'layout_wpcf7_columns';

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
					'label'    => __( 'Form Columns', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Form Top Padding
		$setting_id = 'layout_wpcf7_top_padding';

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
					'label'    => __( 'Form Top Padding', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Form Bottom Padding
		$setting_id = 'layout_wpcf7_bottom_padding';

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
					'label'    => __( 'Form Bottom Padding', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Form Left Padding
		$setting_id = 'layout_wpcf7_left_padding';

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
					'label'    => __( 'Form Left Padding', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Form Right Padding
		$setting_id = 'layout_wpcf7_right_padding';

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
					'label'    => __( 'Form Right Padding', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Form Border Radius
		$setting_id = 'layout_wpcf7_border_radius';

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
					'label'    => __( 'Form Border Radius', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Form Gap
		$setting_id = 'layout_wpcf7_gap';

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
					'label'    => __( 'Form gap', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Textarea Height
		$setting_id = 'layout_wpcf7_textarea_height';

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
					'label'    => __( 'Textarea Height', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Button Position
		$setting_id = 'layout_wpcf7_btn_position';

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
					'label'    => __( 'Button Position', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// footer scroll to top
		$setting_id = 'layout_wpcf7_inline_acceptance_btn';

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
					'label'    => __( 'Inline Acceptance and Button', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);
	}
}

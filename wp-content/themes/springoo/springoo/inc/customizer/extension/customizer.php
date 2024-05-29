<?php
/**
 * Main Customizer File.
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

add_action( 'customize_register', 'springoo_components_customize_register' );

/**
 * Register color picker & multi color-picker control.
 *
 * @param WP_Customize_Manager $wp_customize
 *
 * @return void
 */
function springoo_components_customize_register( $wp_customize ) {

	/** @define "SPRINGOO_THEME_DIR" "./../../../" */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
	// phpcs:disable WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	// Include the Alpha Color Picker.
	require_once SPRINGOO_THEME_DIR . 'inc/customizer/extension/alpha-color-picker/class-springoo-customize-alpha-color-control.php';

	// Include the Multi Color Picker.
	require_once SPRINGOO_THEME_DIR . 'inc/customizer/extension/multi-color-picker/class-springoo-customize-multi-color-control.php';
	// phpcs:enable

	// Add our test section.
	$wp_customize->add_section(
		'components_test_section',
		array(
			'title'      => __( 'Components Test', 'springoo' ),
			'priority'   => 999,
			'capability' => 'edit_theme_options',
		)
	);

	/*
	 * Define a default palette that we'll use for some of the colors.
	 *
	 * We could certainly define a separate palette for each color also.
	 */
	$palette = [
		'rgba(255, 0, 0, 0.7)',
		'rgba(54, 0, 170, 0.8)',
		'#FFCC00',
		'rgba( 20, 20, 20, 0.8 )',
		'#00CC77',
	];

	/**
	 * Alpha Color Picker Settings
	 */

	// Alpha Color Picker Test Setting.
	$wp_customize->add_setting(
		'components_alpha_color_test_setting',
		[
			'default'           => '#0099CC',
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'maybe_hash_hex_color',
		]
	);

	// Alpha Color Picker Test Control.
	$wp_customize->add_control(
		new Springoo_Customize_Alpha_Color_Control(
			$wp_customize,
			'components_alpha_color_test_control',
			[
				'label'    => __( 'Alpha Color Picker Test', 'springoo' ),
				'section'  => 'components_test_section',
				'settings' => 'components_alpha_color_test_setting',
				'palette'  => [
					'#3FADD7',
					'rgba(0,0,0,0.5)',
					'#666666',
					'#F5f5f5',
					'#333333',
					'#404040',
					'#2B4267',
				],
			]
		)
	);

	// Alpha Color Picker Test Setting.
	$wp_customize->add_setting(
		'components_alpha_color_test_setting_two',
		[
			'default'           => 'rgba( 120, 50, 70, 0.6 )',
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'maybe_hash_hex_color',
		]
	);

	// Alpha Color Picker Test Control.
	$wp_customize->add_control(
		new Springoo_Customize_Alpha_Color_Control(
			$wp_customize,
			'components_alpha_color_test_control_two',
			[
				'label'        => __( 'Alpha Color Picker Test', 'springoo' ),
				'section'      => 'components_test_section',
				'settings'     => 'components_alpha_color_test_setting_two',
				'show_opacity' => true,
				'palette'      => [
					'rgb(150,50,220)',
					'rgba(50,50,50,0.8)',
					'rgba( 255, 255, 255, 0.2 )',
					'rgba(20, 80, 100, 0.3)',
					'#00CC99',
				],
			]
		)
	);

	// Alpha Color Picker Test Setting.
	$wp_customize->add_setting(
		'components_alpha_color_test_setting_three',
		array(
			'default'           => 'rgba(0,0,0,0)',
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'maybe_hash_hex_color',
		)
	);

	// Alpha Color Picker Test Control.
	$wp_customize->add_control(
		new Springoo_Customize_Alpha_Color_Control(
			$wp_customize,
			'components_alpha_color_test_control_three',
			[
				'label'        => __( 'Alpha Color Picker Test', 'springoo' ),
				'section'      => 'components_test_section',
				'settings'     => 'components_alpha_color_test_setting_three',
				'show_opacity' => 'true',
				'palette'      => true,
			]
		)
	);

	/*
	 * Multi Color Picker Settings
	 */

	/*
	 * Define our color settings under the group "Background Colors".
	 *
	 * This is one of the arrays that we'll pass to our helper function to
	 * register each setting and group them under a single control.
	 */
	$bg_colors = [
		'body_bg'    => [
			'label'   => __( 'Body Background', 'springoo' ),
			'default' => 'rgba(255, 0, 0, 0.7)',
			'palette' => $palette, // This could also be true or false.
		],
		'header_bg'  => [
			'label'   => __( 'Header Background', 'springoo' ),
			'default' => 'rgba(54, 0, 170, 0.8)',
			'palette' => $palette,
		],
		'sidebar_bg' => [
			'label'   => __( 'Sidebar Background', 'springoo' ),
			'default' => '#FFCC00',
			'palette' => $palette,
		],
		'article_bg' => [
			'label'   => __( 'Article Background', 'springoo' ),
			'default' => 'rgba( 20, 20, 20, 0.8 )',
			'palette' => $palette,
		],
		'footer_bg'  => [
			'label'   => __( 'Footer Background', 'springoo' ),
			'default' => '#00CC77',
			'palette' => $palette,
		],
	];

	/*
	 * Set up the array of standard control data.
	 *
	 * This could also have an active_callback, a sanitize_callback, etc.
	 */
	$bg_colors_control_data = [
		'label'       => __( 'Background Colors', 'springoo' ),
		'description' => __( 'This is the optional control description.', 'springoo' ),
		'section'     => 'components_test_section',
	];

	/*
	 * Use the helper function to register the group of settings and associate them with
	 * a single Multi Color Picker control.
	 */
	springoo_components_register_color_group(
		$wp_customize,
		'components_background_colors',
		$bg_colors,
		$bg_colors_control_data,
		$palette,
		'postMessage'
	);

	/*
	 * Define our color settings under the group "Text Colors".
	 */
	$text_colors = [
		'body_text'      => [
			'label'   => __( 'Body Text', 'springoo' ),
			'default' => '#444444',
		],
		'heading_text'   => [
			'label'   => __( 'Headings', 'springoo' ),
			'default' => '#8866AA',
		],
		'footer_text'    => [
			'label'   => __( 'Footer Text', 'springoo' ),
			'default' => '#CCCCCC',
		],
		'paragraph_text' => [
			'label'   => __( 'Paragraph Text', 'springoo' ),
			'default' => '#222222',
		],
		'link_text'      => [
			'label'   => __( 'Link Text', 'springoo' ),
			'default' => 'rgba( 120, 40, 88, 0.7 )',
		],
	];

	/*
	 * Set up the array of standard control data.
	 *
	 * This could also have an active_callback, a sanitize_callback, etc.
	 */
	$text_colors_control_data = array(
		'label'   => __( 'Text Colors', 'springoo' ),
		'section' => 'components_test_section',
	);

	/*
	 * Use the helper function to register the group of settings and associate them with
	 * a single Multi Color Picker control.
	 */
	springoo_components_register_color_group(
		$wp_customize,
		'components_text_colors',
		$text_colors,
		$text_colors_control_data,
		$palette,
		'postMessage'
	);
}

/**
 * Helper function for registering a group of color settings.
 *
 * @param  WP_Customize_Manager $wp_customize      The main Customizer object.
 * @param  string $option_name       The shared option name to use for the settings.
 * @param  array  $color_settings    The array of color settings data.
 * @param  array  $control_data      The data to pass to the control.
 * @param  array|string  $fallback_palette  An array of fallback palette colors to use if a palette is not included in $color_settings. (optional)
 * @param  string $transport         The transport method for the setting group.
 *
 * @return void
 */
function springoo_components_register_color_group( $wp_customize, $option_name, $color_settings = array(), $control_data = array(), $fallback_palette = 'true', $transport = 'refresh' ) {

	$settings   = array();
	$color_data = array();

	/*
	 * Loop over the colors array and register each setting while also building
	 * the color_settings and color_data arrays that we'll send to the control.
	 */
	foreach ( $color_settings as $setting_name => $setting_data ) {
		// For this example we'll store all of our colors in a single settings array,
		// which requires using the setting type "option". We could also use the
		// setting type "theme_mod" by giving each setting its own unique option key.
		$color_setting_id = $option_name . "[{$setting_name}]";

		// Make default, palette, and show_opacity optional by providing fallbacks here.
		$setting_data['default']      = ( isset( $setting_data['default'] ) ) ? $setting_data['default'] : '#000000';
		$setting_data['palette']      = ( isset( $setting_data['palette'] ) ) ? $setting_data['palette'] : $fallback_palette;
		$setting_data['show_opacity'] = ( isset( $setting_data['show_opacity'] ) ) ? $setting_data['show_opacity'] : 'true';

		// Register the current setting.
		// This still needs a proper sanitize_callback.
		$wp_customize->add_setting(
			$color_setting_id,
			array(
				'default'           => $setting_data['default'],
				'type'              => 'option',
				'capability'        => 'edit_theme_options', // Modify this as needed.
				'transport'         => $transport, // postMessage or refresh.
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		// Build the simple array that contains only the color setting names.
		// We'll pass this as the "settings" value to our control.
		$settings[] = $color_setting_id;

		// Build the more advanced color_data array that contains all the extra information
		// we need for each color setting. We'll pass this to our control.
		$color_data[ $color_setting_id ] = array(
			'label'        => $setting_data['label'],
			'default'      => $setting_data['default'],
			'show_opacity' => $setting_data['show_opacity'],
			'palette'      => $setting_data['palette'],
		);
	}

	/**
	 * Add our arrays to $control_data
	 */
	$control_data['settings']   = $settings;
	$control_data['color_data'] = $color_data;

	/**
	 * Register the Multi Color Control.
	 */
	$wp_customize->add_control( new Springoo_Customize_Multi_Color_Control( $wp_customize, $option_name, $control_data ) );

}

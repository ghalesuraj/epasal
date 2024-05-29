<?php
/**
 * Contains methods for adding Background Images Customization Panel and all settings under it
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * @class Springoo_Customize_Background_Images
 */
class Springoo_Customize_Background_Images {

	const PANEL_ID = 'bg_image';

	/**
	 * Register panel and add settings.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer Instance.
	 *
	 * @return void
	 */
	public static function register( $wp_customize ) {

		// Change panel for Site Title & Tagline Section
		$wp_customize->remove_section( 'background_image' );

		$wp_customize->add_panel(
			self::PANEL_ID,
			array(
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => __( 'Background Images', 'springoo' ),
				'description'    => '',
			)
		);

		/********************************
		 * // Background Images Section
		 */

		$section_id = 'bg_image_global';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Global', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'bg_image_global_h1';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Site', 'springoo' ),
				)
			)
		);

		$setting_id = 'bg_image_global_site';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Background Image', 'springoo' ),
					'description' => __( 'This background image will be applied only if <code>Boxed</code> layout is selected.', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);
	}

}

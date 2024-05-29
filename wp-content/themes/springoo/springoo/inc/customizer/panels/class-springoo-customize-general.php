<?php
/**
 * Contains methods for adding General Customization Panel and all settings under it
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
 * Class Springoo_Customize_General
 */
class Springoo_Customize_General {

	const PANEL_ID = 'general';

	/**
	 * Register panel and add settings.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer Instance.
	 *
	 * @return void
	 */
	public static function register( $wp_customize ) {

		$wp_customize->add_panel(
			self::PANEL_ID,
			array(
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => __( 'General', 'springoo' ),
				'description'    => '',
			)
		);

		self::title_tagline( $wp_customize );
		self::social_profiles( $wp_customize );
		self::sticky( $wp_customize );
		self::s_config( $wp_customize );
		self::preloader( $wp_customize );
	}

	protected static function title_tagline( $wp_customize ) {
		// Title & Tagline Section.

		$section_id = 'title_tagline';

		// Change panel for Site Title & Tagline Section.
		$site_title = $wp_customize->get_section( $section_id );

		if ( $site_title instanceof WP_Customize_Section ) {
			$site_title->panel = self::PANEL_ID;
		}

		$setting_id = 'retina_logo';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'theme_supports'    => array( 'custom-logo' ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);

		$custom_logo_args = get_theme_support( 'custom-logo' );

		$wp_customize->add_control(
			new WP_Customize_Cropped_Image_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'         => __( 'Retina Logo', 'springoo' ),
					'description'   => __( 'Will be visible only on devices with screen width more than 2500px', 'springoo' ),
					'section'       => $section_id,
					'settings'      => $setting_id,
					'priority'      => 9,
					'height'        => isset( $custom_logo_args[0]['height'] ) ? $custom_logo_args[0]['height'] * 2 : null,
					'width'         => isset( $custom_logo_args[0]['width'] ) ? $custom_logo_args[0]['width'] * 2 : null,
					'flex_height'   => isset( $custom_logo_args[0]['flex-height'] ) ? $custom_logo_args[0]['flex-height'] : null,
					'flex_width'    => isset( $custom_logo_args[0]['flex-width'] ) ? $custom_logo_args[0]['flex-width'] : null,
					'button_labels' => [
						'select'       => __( 'Select logo', 'springoo' ),
						'change'       => __( 'Change logo', 'springoo' ),
						'remove'       => __( 'Remove', 'springoo' ),
						'default'      => __( 'Default', 'springoo' ),
						'placeholder'  => __( 'No logo selected', 'springoo' ),
						'frame_title'  => __( 'Select logo', 'springoo' ),
						'frame_button' => __( 'Choose logo', 'springoo' ),
					],
				)
			)
		);

		$setting_id = 'logo_width';

		$wp_customize->add_setting(
			$setting_id,
			[
				'theme_supports'    => [ 'custom-logo' ],
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			]
		);

		$wp_customize->add_control( new WP_Customize_Control(
			$wp_customize,
			$setting_id,
			array(
				'label'    => __( 'Logo Width', 'springoo' ),
				'section'  => $section_id,
				'settings' => $setting_id,
				'type'     => 'number',
				'priority' => 10,
			)
		) );

		// Change priority for Site Title.
		$blogname = $wp_customize->get_control( 'blogname' );
		if ( $blogname instanceof WP_Customize_Control ) {
			$blogname->priority = 15;
		}

		// Change priority for Site Tagline.
		$blogdescription = $wp_customize->get_control( 'blogdescription' );
		if ( $blogdescription instanceof WP_Customize_Control ) {
			$blogdescription->priority = 17;
		}

		$setting_id = 'title_tagline_hide_title';

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
					'label'    => __( 'Hide Title', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
					'priority' => 16,
				)
			)
		);

		$setting_id = 'title_tagline_hide_tagline';

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
					'label'    => __( 'Hide Tagline', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
					'priority' => 18,
				)
			)
		);

		// Change panel for Static Front Page Section.
		$front_page = $wp_customize->get_section( 'static_front_page' );
		if ( $front_page instanceof WP_Customize_Section ) {
			$front_page->panel = self::PANEL_ID;
		}
	}

	protected static function sticky( $wp_customize ) {
		/******************************
		 * // Sticky Posts Section
		 */

		$section_id = 'sticky';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Sticky Posts', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'sticky_label';

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
					'label'    => __( 'Sticky Label', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);
	}

	protected static function social_profiles( $wp_customize ) {
		$section_id = 'social_profiles';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => 'Social Profiles',
				'priority'   => 34,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'social_profiles';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_social_profiles',
			)
		);

		$wp_customize->add_control(
			new Springoo_Social_Profile_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Social Profiles', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);
	}

	protected static function s_config( $wp_customize ) {
		/******************************
		 * // General Configuration Section
		 */

		$section_id = 's_config';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Site Configuration', 'springoo' ),
				'priority'   => 99,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 's_config_lazyload_enable';

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
					'label'       => __( 'Enable Lazy Loading', 'springoo' ),
					'description' => __( 'It will increase your site performence', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
					'type'        => 'checkbox',
					'priority'    => 16,
				)
			)
		);
	}

	protected static function preloader( $wp_customize ) {
		$section_id = 'preloader';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Preloader Settings', 'springoo' ),
				'priority'   => 99,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'preload_enable';
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
					'label'    => __( 'Enable Preloader', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'preload_animation_type';
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
					'label'    => __( 'Animation type', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'preload_animation_speed';
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
					'label'    => __( 'Animation Speed', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'preload_text_label';
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
					'label'   => __( 'Preloader Text Settings', 'springoo' ),
					'section' => $section_id,
					'type'    => 'helptext',
				)
			)
		);

		$setting_id = 'preload_text_enable';
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
					'label'    => __( 'Enable Preloader text', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'preload_text';
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
					'label'    => __( 'Preloader Text', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'preload_text_size';
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
					'label'    => __( 'Preloader text size', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'preload_img_label';
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
					'label'   => __( 'Preloader Image Settings', 'springoo' ),
					'section' => $section_id,
					'type'    => 'helptext',
				)
			)
		);

		$setting_id = 'preload_img_enable';
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
					'label'    => __( 'Enable Preloader Image', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'preload_img';
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
					'label'    => __( 'Preloader Image', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'preload_img_size';
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
					'label'       => __( 'Preloader Image size', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
					'choices'     => springoo_get_choices( $setting_id ),
					'description' => __( 'Enter Image size in px for width. Height will be auto with width', 'springoo' ),
				)
			)
		);
	}
}

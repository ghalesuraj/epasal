<?php
/**
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}


/**
 * Contains methods for customizing the theme customization screen.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since Pxlrtheme 1.0
 */
class Springoo_Customize_Typography {

	const PANEL_ID = 'typography';

	/**
	 * Register panel and add settings.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer Instance.
	 *
	 * @return void
	 */
	public static function register( $wp_customize ) {

		$wp_customize->remove_section( 'colors' );

		$wp_customize->add_panel(
			self::PANEL_ID,
			array(
				'priority'       => 10,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => __( 'Typography', 'springoo' ),
				'description'    => '',
			)
		);

		self::typography_global( $wp_customize );
		self::typography_site( $wp_customize );
		self::typography_heading( $wp_customize );
		self::typography_menu( $wp_customize );
		self::typography_mega_menu( $wp_customize );
		self::typography_sidebar( $wp_customize );
		self::typography_footer( $wp_customize );

	}

	protected static function typography_global( $wp_customize ) {
		$section_id = 'typography';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Global', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = $section_id . '_global_scale';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id . '_heading',
				[
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Type Scale', 'springoo' ),
				]
			)
		);

		$wp_customize->add_setting(
			$setting_id,
			[
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_attr',
			]
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				[
					'label'       => '',
					'section'     => $section_id,
					'settings'    => $setting_id,
					'description' => sprintf(
						/* translators: %1$s: Type-scale doc link tag open %2$s: link tag close */
						__( 'Typographic (or type) scale for balanced and friendly font sizing based on base (global text or body) font-size. %1$sRead more%2$s', 'springoo' ),
						'<a href="https://docs.springoo.com/docs/springoo/typography/" target="_blank" rel="noopener">',
						'</a>'
					),
					'type'        => 'select',
					'choices'     => [
						'1.067' => __( 'Minor Second', 'springoo' ),
						'1.125' => __( 'Major Second', 'springoo' ),
						'1.2'   => __( 'Minor Third', 'springoo' ),
						'1.25'  => __( 'Major Third', 'springoo' ),
						'1.333' => __( 'Perfect Fourth', 'springoo' ),
						'1.414' => __( 'Augmented Fourth', 'springoo' ),
						'1.5'   => __( 'Perfect Fifth', 'springoo' ),
						'1.618' => __( 'Golden Ratio', 'springoo' ),
					],
				]
			)
		);

		/* Global */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'Body', 'springoo' ), 'global' );

		springoo_generate_font_control( $wp_customize, $section_id, __( 'Headings', 'springoo' ), 'heading', 'size' );

		/* Extra font size */
		//heading
		$setting_id = $section_id . '_extra_font_size';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id . '_heading',
				[
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Extra Font Size', 'springoo' ),
				]
			)
		);

		$wp_customize->add_setting(
			$setting_id,
			[
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_attr',
			]
		);

		//x-large
		$setting_id = $section_id . '_x_large_font_size';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'X-Large Font Size (px)', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		//large
		$setting_id = $section_id . '_large_font_size';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Large Font Size (px)', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		//medium
		$setting_id = $section_id . '_medium_font_size';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Medium Font Size (px)', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		//small
		$setting_id = $section_id . '_small_font_size';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Small Font Size (px)', 'springoo' ),
					'description' => __( 'Small and Smaller font sizes are the same', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		//x-small
		$setting_id = $section_id . '_x_small_font_size';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'X-Small Font Size (px)', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);


		//xx-small
		$setting_id = $section_id . '_xx_small_font_size';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'XX-Small Font Size (px)', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);
	}

	protected static function typography_site_title( $wp_customize ) {
		$section_id = 'typography_site_title';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Site Title', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		/* Site Title */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'Site Title', 'springoo' ), '', true );
	}

	protected static function typography_heading( $wp_customize ) {
		$section_id = 'typography_heading';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Text Headings', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		/* H1 */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'H1', 'springoo' ), 'h1' );

		/* H2 */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'H2', 'springoo' ), 'h2' );

		/* H3 */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'H3', 'springoo' ), 'h3' );

		/* H4 */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'H4', 'springoo' ), 'h4' );

		/* H5 */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'H5', 'springoo' ), 'h5' );

		/* H6 */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'H6', 'springoo' ), 'h6' );
	}

	protected static function typography_menu( $wp_customize ) {

		$section_id = 'typography_menu';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Main Menu', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		/* Menu Items */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'Menu Items', 'springoo' ), null, 'line_height' );

		/* Sub Menu Items */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'Sub Menu Items', 'springoo' ), 'sub' );

		/* Mobile Menu Items */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'Mobile Menu Items', 'springoo' ), 'mobile' );
	}

	protected static function typography_mega_menu( $wp_customize ) {

		$section_id = 'typography_mega_menu';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Mega Menu', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);
		/* Menu Title */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'Mega Menu Title', 'springoo' ), null );

		/* Menu Items */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'Mega Menu Items', 'springoo' ), 'sub' );

	}

	protected static function typography_site( $wp_customize ) {

		$section_id = 'typography_site';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Site Title & Tagline', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		/* Site Title */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'Site Title', 'springoo' ), 'title' );

		/* Site Tagline */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'Site Tagline', 'springoo' ), 'tagline' );

	}

	protected static function typography_sidebar( $wp_customize ) {

		$section_id = 'typography_sidebar';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Sidebar', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		/* Site Title */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'Widget Title', 'springoo' ), 'title' );

		/* Site Tagline */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'Widget Body', 'springoo' ), 'body' );

	}

	protected static function typography_footer( $wp_customize ) {

		$section_id = 'typography_footer';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Footer', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		/* Widget Title */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'Widget Title', 'springoo' ), 'title' );

		/* Widget Body */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'Widget Body', 'springoo' ), 'body' );

		/* Secondary Footer */
		springoo_generate_font_control( $wp_customize, $section_id, __( 'Footer Text', 'springoo' ), 'text' );

	}
}

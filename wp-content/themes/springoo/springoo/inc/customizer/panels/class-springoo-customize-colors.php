<?php
/**
 * Contains methods for adding Colors Customization Panel and all settings under it
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
 * @class Springoo_Customize_Colors
 */
class Springoo_Customize_Colors {

	const PANEL_ID = 'colors';

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
				'title'          => __( 'Colors', 'springoo' ),
				'description'    => '',
			)
		);

		self::register_colors_global( $wp_customize );
		self::register_colors_header( $wp_customize );
		self::register_colors_header_top( $wp_customize );
		self::register_colors_header_bottom( $wp_customize );
		self::register_colors_menu( $wp_customize );
		self::register_colors_mega_menu( $wp_customize );
		self::register_colors_title( $wp_customize );
		self::register_colors_footer_main( $wp_customize );
		self::register_colors_footer_secondary( $wp_customize );
		self::register_colors_footer_copyright( $wp_customize );
	}

	/**
	 * Global Section.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	protected static function register_colors_global( $wp_customize ) {
		$section_id = 'colors_global';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Global', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'colors_global_accent';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Primary Color', 'springoo' ),
					'description' => __( 'Used for Links & Buttons', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'colors_global_accent_shade';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Secondary Color', 'springoo' ),
					'description' => __( 'Used for Links & Buttons hover state', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'colors_global_alt';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Form Elements Background Color', 'springoo' ),
					'description' => __( 'Used for Form Elements', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'colors_global_alt_text';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Form Elements Text Color', 'springoo' ),
					'description' => __( 'Used for Form Elements', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'colors_global_border';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Border Color', 'springoo' ),
					'description' => __( 'Used for Borders for all content elements', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'colors_global_heading';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Heading Color', 'springoo' ),
					'description' => __( 'Used for headings - h1 to h6', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'colors_global_text';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Text Color', 'springoo' ),
					'description' => __( 'Used for content text', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'colors_global_site_bg';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Site Background Color', 'springoo' ),
					'description' => __( 'Used for background color of the site, It will apply if site layout Boxed selected.', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'colors_global_content_bg';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Content Background Color', 'springoo' ),
					'description' => __( 'Used for background color of the site content', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'colors_global_sticky_bg';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Sticky Post Background Color', 'springoo' ),
					'description' => __( 'Used for background color of the sticky post archive layout', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'colors_global_gradient';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Gradient Stop Color', 'springoo' ),
					'description' => __( 'Used for gradient stop color that extends primary or secondary color', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);
	}

	/**
	 * Header Section.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	protected static function register_colors_header( $wp_customize ) {
		$section_id = 'colors_header';

		$setting_id = 'colors_header_main';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Header', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Header Main', 'springoo' ),
				)
			)
		);

		$setting_id = 'colors_header_main_bg';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'        => __( 'Background Color', 'springoo' ),
					'description'  => __( 'Header background color', 'springoo' ),
					'section'      => $section_id,
					'settings'     => $setting_id,
					'show_opacity' => true, // Optional.
				)
			)
		);
	}

	/**
	 * Header Section Top.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	protected static function register_colors_header_top( $wp_customize ) {
		/****************
		 * Secondary Header Section
		 */
		$section_id = 'colors_header';

		$setting_id = 'colors_header_second';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Header Top', 'springoo' ),
				)
			)
		);

		$setting_id = 'colors_header_top_bg';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Background Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

	}

	/**
	 * Header Section Bottom.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	protected static function register_colors_header_bottom( $wp_customize ) {
		/****************
		 * Bottom Header Section
		 */
		$section_id = 'colors_header';

		$setting_id = 'colors_header_bottom';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Header Bottom', 'springoo' ),
				)
			)
		);

		$setting_id = 'colors_header_bottom_bg';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Background Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

	}

	/**
	 * Main Menu Section.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	protected static function register_colors_menu( $wp_customize ) {
		/**
		 * Main Menu Section
		 */
		$section_id = 'colors_menu';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Main Menu', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'colors_menu_menu_h';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Menu Items', 'springoo' ),
				)
			)
		);

		$setting_id = 'colors_menu_text';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Text Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_menu_hover';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Text Hover/Focus Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_menu_menu_sub_h';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Sub Menu Items', 'springoo' ),
				)
			)
		);

		$setting_id = 'colors_menu_sub_bg';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Background Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_menu_sub_border';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Border Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_menu_sub_text';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Text Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_menu_sub_hover';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Hover/Focus Text Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_menu_sub_hover_bg';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Hover/Focus Background Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		/**
		 * Mobile Menu Colors
		 */

		$setting_id = 'colors_menu_mob_sub_h';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Mobile Menu', 'springoo' ),
				)
			)
		);

		$setting_id = 'colors_menu_mob_bg';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Menu Background Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_menu_color';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Menu Item Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_menu_mob_hover';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Hover/Focus Text Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_menu_mob_border';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Menu Item Border Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);
	}

	/**
	 * Mega Menu Section.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	protected static function register_colors_mega_menu( $wp_customize ) {
		/**
		 * Mega Menu Section
		 */
		$section_id = 'colors_mega_menu';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Mega Menu', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'colors_mega_menu_bg';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Background Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_mega_title_menu_h';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Menu Title', 'springoo' ),
				)
			)
		);

		$setting_id = 'colors_mega_menu_title';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Text Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_mega_menu_title_hover';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Text Hover/Focus Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_mega_menu_item_h';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Mega Menu Items', 'springoo' ),
				)
			)
		);

		$setting_id = 'colors_mega_menu_item';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Text Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_mega_menu_item_hover';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Hover/Focus Text Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

	}

	/**
	 * Breadcrumb Section Section.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	protected static function register_colors_title( $wp_customize ) {
		$section_id = 'colors_title';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Breadcrumb Section', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'colors_title_bg1';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Background Color1', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_title_bg2';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Background Color2', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_title_text';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Text Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);
	}

	/**
	 * Footer Main.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	protected static function register_colors_footer_main( $wp_customize ) {

		$section_id = 'colors_footer_main';
		$wp_customize->add_section(
			$section_id,
			[
				'title'      => __( 'Footer Main / Footer Widget Area', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			]
		);

		$setting_id = 'colors_footer_main_heading';
		// Section Heading
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Footer Main / Footer Widget Area', 'springoo' ),
				)
			)
		);

		$setting_id = 'colors_footer_main_bg';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Background Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_footer_main_heading';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Heading Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_footer_main_text';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Text Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_footer_main_link';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Link Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_footer_main_link_hover';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Link Hover Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);
	}

	/**
	 * Footer seocondary.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	protected static function register_colors_footer_secondary( $wp_customize ) {

		$section_id = 'colors_footer_second';
		$wp_customize->add_section(
			$section_id,
			[
				'title'      => __( 'Footer Secondary', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			]
		);

		$setting_id = 'colors_footer_sc_heading';
		// Section Heading
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Footer Secondary', 'springoo' ),
				)
			)
		);

		$setting_id = 'colors_footer_sc_bg';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Background Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_footer_sc_link';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Link Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_footer_sc_link_hover';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Link Hover Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

	}

	/**
	 * Footer copyright and credits.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	protected static function register_colors_footer_copyright( $wp_customize ) {

		$section_id = 'colors_footer_copyright';
		$wp_customize->add_section(
			$section_id,
			[
				'title'      => __( 'Footer Copyright', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			]
		);

		$setting_id = 'colors_footer_copyright_heading';
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Footer Copyright', 'springoo' ),
				)
			)
		);

		$setting_id = 'colors_footer_copyright_bg';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Background Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_footer_copyright_text';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Text Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_footer_copyright_link';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Link Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		$setting_id = 'colors_footer_copyright_link_hover';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Link Hover Color', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);
	}

}

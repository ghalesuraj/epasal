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
 * Contains methods for adding Layout Customization Panel and all settings under it
 *
 * @since Pxlrtheme 1.0
 */
class Springoo_Customize_Layout {

	const PANEL_ID = 'layout';

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
				'title'          => 'Layout',
			)
		);

		self::global_layout( $wp_customize );
		self::header_layout( $wp_customize );
		self::title_layout( $wp_customize );
		self::layout_blog( $wp_customize );
		self::layout_404( $wp_customize );
		self::layout_archive( $wp_customize );
		self::layout_search( $wp_customize );
		self::layout_post( $wp_customize );
		self::layout_page( $wp_customize );
		self::layout_footer( $wp_customize );
	}

	protected static function global_layout( $wp_customize ) {
		/*******************
		 * // Global Section
		 */

		$section_id = 'layout_global';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Global', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		// Site Layout
		$setting_id = 'layout_global_site';

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
					'label'    => __( 'Site Layout', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Site Layout
		$setting_id = 'layout_global_content_layout';

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
					'label'    => __( 'Container Layout', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_global_content_layout_max_width';

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
					'label'       => __( 'Container Max Width', 'springoo' ),
					'description' => __( 'Max width applicable only container-fluid case for Container Layout', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		// Content Width
		$setting_id = 'layout_global_content_grid';

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
					'label'    => __( 'Content Width', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Sidebar Width
		$setting_id = 'layout_global_sidebar_grid';

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
					'label'    => __( 'Sidebar Width', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);
	}

	protected static function header_layout( $wp_customize ) {
		/******************
		 * // Header Section
		 */

		$section_id = 'layout_header';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Header', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		/*
		----------------------------------------------------------------------
		 * header Topbar/ Secondary header
		 *----------------------------------------------------------------------*/

		$setting_id = 'layout_header_top_bar_section_separator';

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
					'label'       => __( 'Header Top Bar', 'springoo' ),
					'section'     => $section_id,
					'type'        => 'helptext',
					'description' => __( 'Change Header Top Bar Related Settings', 'springoo' ),
				)
			)
		);

		// Show Header Top
		$setting_id = 'layout_header_top';

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
					'label'    => __( 'Show Header Top', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		// Header discount Text
		$setting_id = 'layout_header_top_discount';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			new Springoo_Textarea_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Header Top Discount Text', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		// Header discount email Text
		$setting_id = 'layout_header_top_email';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_email',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Header Top Email Text', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		/*
		----------------------------------------------------------------------
		 * End of header Top
		 *----------------------------------------------------------------------*/

		/*
		----------------------------------------------------------------------
		 * Start Main Header Configuration
		 *----------------------------------------------------------------------*/
		$setting_id = 'layout_header_main_section_separator';

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
					'label'       => __( 'Header Main Settings', 'springoo' ),
					'section'     => $section_id,
					'type'        => 'helptext',
					'description' => __( 'Change Header Main settings and related configuration', 'springoo' ),
				)
			)
		);

		// Mobile Menu show grid section
		$setting_id = 'layout_header_menu_mobileshow';

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
					'label'    => __( 'Show Mobile Menu / Hide Main Menu', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Mobile Menu Style
		$setting_id = 'layout_header_mobile_menu_style';

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
					'label'    => __( 'Mobile Menu Style', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Show mobile backdrop background
		$setting_id = 'layout_header_mobile_backdrop_bg';

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
					'label'    => __( 'Show mobile backdrop background', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		/*
		----------------------------------------------------------------------
		 * Header bottom Settings
		 *----------------------------------------------------------------------*/

		$setting_id = 'layout_header_header_bottom_section_separator';

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
					'label'       => __( 'Header Bottom Settings', 'springoo' ),
					'section'     => $section_id,
					'type'        => 'helptext',
					'description' => __( 'Change header bottom related settings', 'springoo' ),
				)
			)
		);

		// Main Submenu Alignment
		$setting_id = 'layout_header_submenu_alignment';

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
					'label'    => __( 'Main Submenu Alignment', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Submenu Width
		$setting_id = 'layout_header_submenu_width';

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
					'label'    => __( 'Submenu Width', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		/*
		----------------------------------------------------------------------
		 * Sticky Settings
		 *----------------------------------------------------------------------*/

		// Sticky Section Separator

		$setting_id = 'layout_header_header_sticky_section_separator';

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
					'label'       => __( 'Sticky Header Settings', 'springoo' ),
					'section'     => $section_id,
					'type'        => 'helptext',
					'description' => __( 'Change sticky header related settings', 'springoo' ),
				)
			)
		);


		// Enable Sticky Header
		$setting_id = 'layout_header_sticky';

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
					'label'    => __( 'Enable Sticky Header', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		// Enable Search
		$setting_id = 'layout_header_mini_search';

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
					'label'    => __( 'Enable Search', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		// Enable Wishlist
		$setting_id = 'layout_header_wishlist';

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
					'label'    => __( 'Enable Wishlist Icon', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		// Enable Mini Cart
		$setting_id = 'layout_header_show_mini_cart';

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
					'label'    => __( 'Enable Cart Icon', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		// Enable User login Icon
		$setting_id = 'layout_header_login_user_icon';

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
					'label'    => __( 'Enable Login button', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);


		/*
		----------------------------------------------------------------------
		 * End of header Settings
		 *----------------------------------------------------------------------*/
	}

	protected static function title_layout( $wp_customize ) {
		/******************
		 * // Title Bar Section
		 */

		$section_id = 'title_bar';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Breadcrumb', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		/*
		----------------------------------------------------------------------
		 * Title Bar Settings
		 *----------------------------------------------------------------------*/

		$setting_id = 'title_bar_h2';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Breadcrumb Section', 'springoo' ),
				)
			)
		);

		$setting_id = 'title_bar_title_container';

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
					'label'    => __( 'Background Image', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		// Show Secondary Header
		$setting_id = 'layout_enable_title_breadcrumb';

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
					'label'    => __( 'Show title on breadcrumb', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);
	}

	protected static function layout_blog( $wp_customize ) {
		/******************************************
		 * // Blog (Posts Page) Section
		 */

		$section_id = 'layout_blog';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Blog (Posts Page)', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'layout_blog_sidebar';

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
					'label'    => __( 'Sidebar Position', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_blog_style';

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
					'label'    => __( 'Style', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_blog_title-bar';

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
					'label'    => __( 'Show Breadcrumb ?', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_blog_excerpt_length';

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
					'label'       => __( 'Excerpt Length', 'springoo' ),
					'description' => __( 'Number or word you want to show on archive content', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'layout_blog_meta';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Post Meta', 'springoo' ),
				)
			)
		);

		$setting_id = 'layout_blog_meta-tags';

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
					'label'    => __( 'Show Tags', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'layout_blog_meta-cats';

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
					'label'    => __( 'Show Categories', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		if ( springoo_is_active_plugin( 'regenerate-thumbnails' ) ) {
			$desc = sprintf(
			/* translators: %s: Regenerate Thumbnail Plugin Page Link */
				__( 'After changing any of the post thumbnail sizes please use <a href="%s" target="_blank"><b>Regenerate Thumbnail</b></a> plugin to resize (regenerate) thumbnails.', 'springoo' ),
				esc_url( admin_url( 'tools.php?page=regenerate-thumbnails' ) )
			);
		} else {
			$desc = sprintf(
			/* translators: %s: Regenerate Thumbnail Plugin Installation Link */
				__( 'After changing any of the post thumbnail sizes please install and use <a href="%s" target="_blank"><b>Regenerate Thumbnail</b></a> plugin to resize (regenerate) thumbnails.', 'springoo' ),
				admin_url( 'themes.php?page=tgmpa-install-plugins&plugin_status=install' )
			);
		}

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section'     => $section_id,
					'type'        => 'helptext',
					'label'       => __( 'Post Thumbnail Sizes', 'springoo' ),
					'description' => $desc,
				)
			)
		);

		$setting_id = 'layout_blog_normal_image';

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
					'label'       => __( 'Blog Normal Image Size', 'springoo' ),
					'description' => __( 'Single Blog / Blog Normal Layout Image Size ( width,height )', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'layout_blog_medium_image';

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
					'label'       => __( 'Blog Medium Image Size', 'springoo' ),
					'description' => __( 'Blog Medium Layout Image Size ( width,height ).', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'layout_blog_grid_image';

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
					'label'       => __( 'Blog Grid Image Size', 'springoo' ),
					'description' => __( 'Blog Grid layout Image Size ( width,height )', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'layout_blog_grid_column';

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
					'label'       => __( 'Blog Grid Column', 'springoo' ),
					'description' => __( 'Blog Grid layout Column', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
					'type'        => 'select',
					'choices'     => array(
						'2' => __( '2 Column', 'springoo' ),
						'3' => __( '3 Column', 'springoo' ),
						'4' => __( '4 Column', 'springoo' ),
					),
				)
			)
		);
	}

	/**
	 * @param $wp_customize
	 * 404 Page Layout
	 * @return void
	 */
	protected static function layout_404( $wp_customize ) {
		/******************************************
		 * // 404 Page Section
		 */

		$section_id = 'layout_404';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( '404 Page', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'layout_404_image';
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
					'label'       => __( '404 Page Image', 'springoo' ),
					'description' => __( 'Upload 404 Page Image.', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'layout_404_page_title';

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
					'label'       => __( 'Title', 'springoo' ),
					'description' => __( 'Example: This page is outside of the universe', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'layout_404_page_description';

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
					'label'       => __( 'Description', 'springoo' ),
					'description' => __( 'Example: The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'layout_404_page_button_text';

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
					'label'       => __( 'Button Text', 'springoo' ),
					'description' => __( 'Example: Go to Home', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'layout_404_page_button_link';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Button Link', 'springoo' ),
					'description' => __( 'Example: https://example.com', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);


	}

	protected static function layout_archive( $wp_customize ) {
		/******************************************
		 * // Archives Section
		 */

		$section_id = 'layout_archive';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Archives', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'layout_archive_sidebar';

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
					'label'    => __( 'Sidebar Position', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_archive_style';

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
					'label'    => __( 'Style', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_archive_title-bar';

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
					'label'    => __( 'Show Breadcrumb ?', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_archive_meta';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Post Meta', 'springoo' ),
				)
			)
		);

		$setting_id = 'layout_archive_meta-tags';

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
					'label'    => __( 'Show Tags', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);
	}

	protected static function layout_search( $wp_customize ) {
		/******************************************
		 * // Search Results Section
		 */

		$section_id = 'layout_search';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Search Results', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'layout_search_sidebar';

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
					'label'    => __( 'Sidebar Position', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_search_style';

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
					'label'    => __( 'Style', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_search_title-bar';

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
					'label'    => __( 'Show Breadcrumb ?', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_search_meta';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Post Meta', 'springoo' ),
				)
			)
		);

		$setting_id = 'layout_search_meta-cats';

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
					'label'    => __( 'Show Categories', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'layout_search_meta-tags';

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
					'label'    => __( 'Show Tags', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);
	}

	protected static function layout_post( $wp_customize ) {
		/******************************************
		 * // Single Post
		 */

		$section_id = 'layout_post';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Single Post', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'layout_post_sidebar';

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
					'label'    => __( 'Sidebar Position', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_post_title-bar';

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
					'label'    => __( 'Show Breadcrumb ?', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_post_ft-img-hide';

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
					'label'    => __( 'Hide Featured Image', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'layout_post_ft-img-enlarge';

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
					'label'       => __( 'Enlarge Featured Image', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
					'type'        => 'checkbox',
					'description' => __( 'Enalrge the featured image width to the 100% width of the view port/window', 'springoo' ),
				)
			)
		);

		$setting_id = 'layout_post_content_align';

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
					'label'    => __( 'Content Alignment', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_post_title';

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
					'label'    => __( 'Show Post Title ?', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_related_post';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Related Posts', 'springoo' ),
				)
			)
		);

		$setting_id = 'layout_show_related_post';

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
					'label'    => __( 'Show Related Posts', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		// Related Post Number
		$setting_id = 'layout_related_post_number';

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
					'label'    => __( 'Post Number', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		//  show related post column
		$setting_id = 'layout_related_post_column';

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
					'label'    => __( 'Post Column', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_enable_related_post_slider';

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
					'label'    => __( 'Enable Slider', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'layout_related_post_slider_enable_arrow';

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
					'label'    => __( 'Enable Slider Arrow', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'layout_related_post_slider_enable_dots';

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
					'label'    => __( 'Enable Slider Dots', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'layout_related_post_slider_enable_autoplay';

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
					'label'    => __( 'Enable Slider Autoplay', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);


		$setting_id = 'layout_post_meta';

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Post Meta', 'springoo' ),
				)
			)
		);

		$setting_id = 'layout_post_meta-cats';

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
					'label'    => __( 'Show Categories', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'layout_post_meta-tags';

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
					'label'    => __( 'Show Tags', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);
	}

	protected static function layout_page( $wp_customize ) {
		/******************************************
		 * // Single Page
		 */

		$section_id = 'layout_page';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Single Page', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'layout_page_sidebar';

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
					'label'    => __( 'Sidebar Position', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_page_title-bar';

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
					'label'    => __( 'Show Breadcrumb ?', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_page_ft-img-hide';

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
					'label'    => __( 'Hide Featured Image', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		$setting_id = 'layout_page_ft-img-enlarge';

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
					'label'       => __( 'Enlarge Featured Image', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
					'type'        => 'checkbox',
					'description' => __( 'Enalrge the featured image width to the 100% width of the view port/window', 'springoo' ),
				)
			)
		);

		$setting_id = 'layout_page_content_align';

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
					'label'    => __( 'Content Alignment', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_page_title';

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
					'label'    => __( 'Show Page Title ?', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		/*
		----------------------------------------------------------------------
		 * End Of Single Page
		 *----------------------------------------------------------------------*/
	}

	protected static function layout_footer( $wp_customize ) {
		/******************
		 * // Footer Section
		 */

		$section_id = 'layout_footer';
		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Footer', 'springoo' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options',
				'panel'      => self::PANEL_ID,
			)
		);

		$setting_id = 'layout_footer_settings';
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Footer Settings', 'springoo' ),
				)
			)
		);

		// == Footer Newsletter == //
		$setting_id = 'layout_footer_newsletter_settings';
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Newsletter', 'springoo' ),
				)
			)
		);

		// Footer Newsletter Shortcode.
		$setting_id = 'layout_footer_newsletter';
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
					'label'    => __( 'Footer Newsletter Shortcode', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		// == Footer Services == //
		$setting_id = 'layout_footer_services_settings';
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Services', 'springoo' ),
				)
			)
		);

		// Footer Services Shortcode.
		$setting_id = 'layout_footer_services';
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
					'label'    => __( 'Footer Services Shortcode', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		// == main footer / footer widget area == //
		$setting_id = 'layout_main_footer_settings';
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Main Footer / Footer Widget Area', 'springoo' ),
				)
			)
		);

		//top padding
		$setting_id = 'layout_footer_main_top_padding';
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
					'label'    => __( 'Main footer Top padding', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// bottom padding
		$setting_id = 'layout_footer_main_bottom_padding';
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
					'label'    => __( 'Main footer bottom padding', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// == footer bottom / secondary footer area == //
		$setting_id = 'layout_bottom_footer_settings';
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Footer Bottom / Secondary footer', 'springoo' ),
				)
			)
		);

		// Footer Secondary top padding
		$setting_id = 'layout_footer_bottom_top_padding';
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
					'label'    => __( 'Secondary footer top padding', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Footer Secondary bottom padding
		$setting_id = 'layout_footer_bottom_bottom_padding';
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
					'label'    => __( 'Secondary footer bottom padding', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = 'layout_footer_apple_store_link';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Apple Store Link', 'springoo' ),
					'description' => __( 'Example: https://example.com', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);

		$setting_id = 'layout_footer_google_play_store_link';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Google play Store Link', 'springoo' ),
					'description' => __( 'Example: https://example.com', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
				)
			)
		);


		// == footer copy right and credit  == //
		$setting_id = 'layout_footer_credits_settings';
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => __( 'Copyright and Credits', 'springoo' ),
				)
			)
		);

		// Copyright top padding
		$setting_id = 'layout_footer_credits_top_padding';
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
					'label'    => __( 'Copyright top padding', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Copyright bottom padding
		$setting_id = 'layout_footer_credits_bottom_padding';
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
					'label'    => __( 'Copyright bottom padding', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		//payment gateway image
		$setting_id = 'layout_footer_payment_gateway';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Payment Gateway', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		// Footer copyright Text.
		$setting_id = 'layout_footer_footer_text';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			new Springoo_Textarea_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Footer Copyright Text', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

	}

}

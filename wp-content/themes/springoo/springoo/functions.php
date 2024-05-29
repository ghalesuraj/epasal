<?php
/**
 * Theme functions and definitions.
 *
 * @package Springoo
 * @author ThemeRox
 * @version 1.0.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

define( 'SPRINGOO_THEME_VERSION', '1.0.4' );

/** @define "SPRINGOO_THEME_DIR" "./" */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
define( 'SPRINGOO_THEME_DIR', untrailingslashit( get_template_directory() ) . '/' );
define( 'SPRINGOO_THEME_URI', untrailingslashit( get_template_directory_uri() ) . '/' );

/**
 * Loading Core File and Script Loader.
 */
require SPRINGOO_THEME_DIR . 'inc/springoo-include.php';

/**
 * Content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	/* pixels */
	$content_width = 870; // phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedVariableFound
}

if ( ! function_exists( 'springoo_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @return void
	 */
	function springoo_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Pxlr-theme, use a find and replace
		 * to change 'springoo' to the name of your theme in all the template files
		*/
		if ( ! load_theme_textdomain( 'springoo', get_stylesheet_directory() . '/languages' ) ) {
			load_theme_textdomain( 'springoo', get_template_directory() . '/languages' );
		}

		if ( get_theme_mod( 'woocommerce_general_enable_product_zoom' ) ) {
			add_theme_support( 'wc-product-gallery-zoom' );
		}

		if ( get_theme_mod( 'woocommerce_general_enable_product_lightbox' ) ) {
			add_theme_support( 'wc-product-gallery-lightbox' );
		}

		if ( get_theme_mod( 'woocommerce_general_enable_product_gallery_slider' ) ) {
			add_theme_support( 'wc-product-gallery-slider' );
		}

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		*/
		add_theme_support( 'title-tag' );

		/**
		 * Add post-formats support.
		 */
		add_theme_support(
			'post-formats',
			[
				'link',
				'aside',
				'gallery',
				'image',
				'quote',
				'status',
				'video',
				'audio',
				'chat',
			]
		);

		/**
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * Enable Springoo Studio support
		 */
		add_theme_support( 'ayyash-studio' );

		/**
		 * Images Sizes
		 */
		add_image_size( 'springoo-blog-grid', 540, 420, true );
		add_image_size( 'springoo-gallery', 420, 420, true );
		add_image_size( 'springoo-vertical', 300, 580, true );
		add_image_size( 'springoo-starter', 330, 250, true );
		add_image_size( 'springoo-shop-image', 306, 450, true );
		add_image_size( 'springoo-search-image', 216, 286, true );
		add_image_size( 'springoo-deals-image', 258, 379, true );

		add_image_size( 'springoo-hotspot-thumb', 80, 86, [ 'center', 'top' ] );
		add_image_size( 'springoo-wc-thumbnail', 306, 450, [ 'center', 'top' ] );
		add_image_size( 'springoo-review-thumb', 90, 90, true );

		$blog_normal = springoo_get_mod( 'layout_blog_normal_image' );
		$blog_medium = springoo_get_mod( 'layout_blog_medium_image' );
		$blog_grid   = springoo_get_mod( 'layout_blog_grid_image' );

		if ( $blog_normal ) {
			$blog_normal = explode( ',', $blog_normal );
			$blog_normal = array_map( 'absint', $blog_normal );
			add_image_size( 'blog-normal', $blog_normal[0], $blog_normal[1], true );
		}

		if ( $blog_medium ) {
			$blog_medium = explode( ',', $blog_medium );
			$blog_medium = array_map( 'absint', $blog_medium );
			add_image_size( 'blog-medium', $blog_medium[0], $blog_medium[1], true );
		}

		if ( $blog_grid ) {
			$blog_grid = explode( ',', $blog_grid );
			$blog_grid = array_map( 'absint', $blog_grid );
			add_image_size( 'blog-grid', $blog_grid[0], $blog_grid[1], true );
		}

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			[
				'primary'          => __( 'Primary Menu', 'springoo' ),
				'mobile'           => __( 'Mobile Menu ( Select when mobile menu is different )(Mobile primary menu)', 'springoo' ),
				'header_top_left'  => __( 'Header Top Left Menu', 'springoo' ),
				'header_top_right' => __( 'Header Top Right Menu', 'springoo' ),
				'footer_menu'      => __( 'Footer Menu ', 'springoo' ),
			]
		);

		/**
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', [
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		] );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for color palette
		add_theme_support( 'editor-color-palette', array(
			array(
				'name'  => esc_attr__( 'Primary', 'springoo' ),
				'slug'  => 'springoo-primary',
				'color' => get_theme_mod( 'colors_global_accent' ) ? get_theme_mod( 'colors_global_accent' ) : '#064AF3',
			),
			array(
				'name'  => esc_attr__( 'Secondary', 'springoo' ),
				'slug'  => 'springoo-secondary',
				'color' => get_theme_mod( 'colors_global_accent_shade' ) ? get_theme_mod( 'colors_global_accent_shade' ) : '#08A3EE',
			),
			array(
				'name'  => esc_attr__( 'Heading', 'springoo' ),
				'slug'  => 'springoo-heading',
				'color' => get_theme_mod( 'colors_global_heading' ) ? get_theme_mod( 'colors_global_heading' ) : '#101318',
			),
			array(
				'name'  => esc_attr__( 'Global', 'springoo' ),
				'slug'  => 'springoo-global',
				'color' => get_theme_mod( 'colors_global_text' ) ? get_theme_mod( 'colors_global_text' ) : '#DEE1E7',
			),
			array(
				'name'  => esc_attr__( 'Light Background', 'springoo' ),
				'slug'  => 'springoo-light-bg',
				'color' => get_theme_mod( 'colors_global_content_bg' ) ? get_theme_mod( 'colors_global_content_bg' ) : '#f9f9f9',
			),
		) );

		// Add theme support for editor typography
		add_theme_support( 'editor-font-sizes', array(
			array(
				'name' => esc_attr__( 'Heading One ', 'springoo' ),
				'size' => get_theme_mod( 'typography_heading_h1_font_size' ) ? get_theme_mod( 'typography_heading_h1_font_size' ) : '50',
				//typography_heading_h1_font_size
				'slug' => 'springoo-heading-1',
			),
			array(
				'name' => esc_attr__( 'Heading Two ', 'springoo' ),
				'size' => get_theme_mod( 'typography_heading_h2_font_size' ) ? get_theme_mod( 'typography_heading_h2_font_size' ) : 40,
				'slug' => 'springoo-heading-2',
			),
			array(
				'name' => esc_attr__( 'Heading Three ', 'springoo' ),
				'size' => get_theme_mod( 'typography_heading_h3_font_size' ) ? get_theme_mod( 'typography_heading_h3_font_size' ) : 36,
				'slug' => 'springoo-heading-3',
			),
			array(
				'name' => esc_attr__( 'Heading Four ', 'springoo' ),
				'size' => get_theme_mod( 'typography_heading_h4_font_size' ) ? get_theme_mod( 'typography_heading_h4_font_size' ) : 28,
				'slug' => 'springoo-heading-4',
			),
			array(
				'name' => esc_attr__( 'Heading Five ', 'springoo' ),
				'size' => get_theme_mod( 'typography_heading_h5_font_size' ) ? get_theme_mod( 'typography_heading_h5_font_size' ) : 20,
				'slug' => 'springoo-heading-5',
			),
			array(
				'name' => esc_attr__( 'Heading Six ', 'springoo' ),
				'size' => get_theme_mod( 'typography_heading_h6_font_size' ) ? get_theme_mod( 'typography_heading_h6_font_size' ) : 18,
				'slug' => 'springoo-heading-6',
			),
		) );


		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'springoo_custom_background_args', [
			'default-color' => 'ffffff',
			'default-image' => '',
		] ) );

		add_theme_support( 'custom-logo', [
			'height'               => 100,
			'width'                => 400,
			'flex-height'          => true,
			'flex-width'           => true,
			'header-text'          => [ 'site-title', 'site-description' ],
			'unlink-homepage-logo' => false,
		] );

		// Set up the WordPress core custom header feature.
		add_theme_support( 'custom-header', [
			'wp-head-callback'       => '',
			'admin-head-callback'    => '',
			'admin-preview-callback' => '',
		] );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Add support for custom line height controls.
		add_theme_support( 'custom-line-height' );

		// Add support for experimental link color control.
		add_theme_support( 'experimental-link-color' );

		// Add support for experimental cover block spacing.
		add_theme_support( 'custom-spacing' );
	}
}

if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Fire the wp_body_open action.
	 * Back compact. for 5.0.0
	 *
	 * @return void
	 */
	function wp_body_open() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedFunctionFound
		do_action( 'wp_body_open' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound
	}
}

add_action( 'after_setup_theme', 'springoo_setup' );

if ( ! function_exists( 'springoo_extensions_flying_cart' ) ) {

	/**
	 * /**
	 * Enable Flying cart from theme.
	 *
	 * @return bool
	 */
	function springoo_extensions_flying_cart() {
		return (bool) springoo_get_mod( 'woocommerce_general_enable_product_flying_cart' );
	}
}

add_filter( 'springoo_extensions_flying_cart', 'springoo_extensions_flying_cart' );

if ( ! function_exists( 'springoo_enqueue_comments_reply' ) ) {
	/**
	 * Enqueue Comments Reply into footer (comment-reply.js)
	 *
	 * @return void
	 */
	function springoo_enqueue_comments_reply() {
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply', 'wp-includes/js/comment-reply', [], '1.0.0', true );
		}
	}
}

add_action( 'comment_form_before', 'springoo_enqueue_comments_reply' );

//Rearrange comment form fields,
if ( ! function_exists( 'springoo_move_comment_field' ) ) {
	/**
	 * Rearrange comment field
	 *
	 * @return array
	 */
	function springoo_move_comment_field( $fields ) {

		if ( ! is_singular( 'product' ) ) {

			uksort( $fields, function ( $a, $b ) {
				$priority   = array(
					'author'  => 10,
					'email'   => 20,
					'website' => 30,
					'subject' => 40,
					'comment' => 50,
					'cookies' => 60,
				);
				$a_priority = $priority[ $a ] ? $priority[ $a ] : 70;
				$b_priority = $priority[ $b ] ? $priority[ $b ] : 70;

				if ( $a_priority === $b_priority ) {
					return 70;
				}

				return ( $a_priority < $b_priority ) ? - 1 : 1;
			} );
		}

		return $fields;
	}
}
add_filter( 'comment_form_fields', 'springoo_move_comment_field' );

/**
 * ----------------------------------------------------------------------
 * Advice for theme developer read carefully
 * If you are developing theme using springoo Starter theme make sure
 * Not to write any new theme function
 * (The theme you are going to release using our starter framework)
 * Anywhere , Just use inc/springoo-theme-extras.php which we created
 * for developer who is using springoo for theme development . By maintaining
 * this advice , you can update base framework easily. Because we will not
 * write anything in the file.
 * ----------------------------------------------------------------------
 */

// End of file functions.php.

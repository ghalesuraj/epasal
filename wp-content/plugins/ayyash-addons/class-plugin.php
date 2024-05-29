<?php
/**
 * Include files for Elementor widgets
 *
 * @package AyyashAddons
 */

namespace AyyashAddons;

use AyyashAddons\Controls\Ayyash_Addons_Control_Styles;
use AyyashAddons\Controls\Ayyash_Addons_Query_Builder;
use Elementor\Plugin as ElementorPlugin;

/**
 * Class Plugin
 *
 * Main Plugin class
 *
 * @since 1.0.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	protected $settings;

	private static $is_script_debug;

	private static $scripts = array();

	private static $styles = array();

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Plugin An instance of the class.
	 * @since 1.2.0
	 * @access public
	 *
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {

			self::$_instance = new self();

			/**
			 * AYYASH_ADDONS Loaded.
			 *
			 * Fires when AyyashAddons was fully loaded and instantiated.
			 *
			 * @since 1.0.0
			 */
			do_action( 'ayyash_addons/loaded' );

		}

		return self::$_instance;
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		// Preload Settings.
		$this->get_settings();

		// Add font group
		add_filter( 'elementor/fonts/groups', [ __CLASS__, 'add_font_group' ] );

		// Add additional fonts
		add_filter( 'elementor/fonts/additional_fonts', [ __CLASS__, 'add_additional_fonts' ] );

		/**
		 * Widget assets has to be registered before elementor preview calls the wp_enqueue_scripts...
		 *
		 * elementor/preview/enqueue_styles
		 * elementor/frontend/after_register_scripts
		 * elementor/frontend/after_enqueue_styles
		 *
		 * @see \Elementor\Preview::init
		 * @see \Elementor\Element_Base::get_script_depends()
		 * @see \Elementor\Element_Base::get_style_depends()
		 */

		// Register widget styles.
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'widget_styles' ], 8 );

		// Register widget scripts.
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'widget_scripts' ], 8 );

		//add_action( 'elementor/preview/enqueue_styles', [ __CLASS__, 'preview_style' ], PHP_INT_MIN );
		add_action( 'elementor/editor/after_enqueue_scripts', [ __CLASS__, 'editor_scripts' ], PHP_INT_MIN );
		//add_action( 'elementor/editor/after_enqueue_styles', [ __CLASS__, 'preview_style' ], PHP_INT_MIN );
		//add_action( 'elementor/editor/after_enqueue_scripts', [ __CLASS__, 'preview_script' ], PHP_INT_MIN );

		// Register controls & widgets.
		spl_autoload_register( [ __CLASS__, 'autoload' ] );

		// Register controls.
		add_action( 'elementor/controls/register', [ __CLASS__, 'register_controls' ] );

		// Register widgets.
		add_action( 'elementor/widgets/register', [ __CLASS__, 'register_active_widgets' ] );

	}

	private static function autoload( $class_name ) {
		if ( 0 !== strpos( $class_name, __NAMESPACE__ ) ) {
			return;
		}

		if ( false !== strpos( $class_name, 'AyyashPluginsServices' ) ) {
			return;
		}

		$helpers       = [];
		$_class_name   = str_replace( __NAMESPACE__, '', $class_name );
		$_class_name   = ltrim( $_class_name, '\\' );
		$_class_name   = strtolower( $_class_name );
		$_class_name   = str_replace( [ '_' ], '-', $_class_name );
		$_class_name   = explode( '\\', $_class_name );
		$_class_name[] = 'class-' . array_pop( $_class_name );
		$_class_name   = implode( '/', $_class_name );
		$file          = AYYASH_ADDONS_PATH . 'includes/' . $_class_name . '.php';

		if ( ! file_exists( $file ) ) {
			$file = str_replace( 'class-', 'trait-', $file );
		}

		if ( ! file_exists( $file ) ) {
			if ( false !== strpos( $class_name, 'AyyashAddons\Controls\Fields' ) ) {
				$_class_name = str_replace( 'AyyashAddons\Controls\Fields\\', '', $class_name );
				$_class_name = strtolower( $_class_name );
				$file        = AYYASH_ADDONS_PATH . 'controls/fields/class-' . str_replace( [ '_' ], '-', $_class_name ) . '.php';
			} elseif ( false !== strpos( $class_name, 'AyyashAddons\Controls' ) ) {
				$_class_name = str_replace( 'AyyashAddons\Controls\\', '', $class_name );
				$_class_name = strtolower( $_class_name );
				$file        = AYYASH_ADDONS_PATH . 'controls/class-' . str_replace( [ '_' ], '-', $_class_name ) . '.php';
			} elseif ( false !== strpos( $class_name, 'AyyashAddons\Widgets\Posts' ) ) {
				$_folder_name = str_replace( 'AyyashAddons\Widgets', '', $class_name );
				$_folder_name = explode( '\\', $_folder_name );
				$_class_name  = end( $_folder_name );

				array_pop( $_folder_name );
				$_folder_name = array_map( 'strtolower', $_folder_name );
				$_folder_name = implode( '/', $_folder_name );

				$_class_name = strtolower( $_class_name );
				$file        = AYYASH_ADDONS_PATH . 'widgets/' . $_folder_name . '/' . str_replace( [ '_' ], '-', $_class_name ) . '.php';

			} elseif ( false !== strpos( $class_name, 'AyyashAddons\Widgets' ) ) {
				$_class_name = str_replace( 'AyyashAddons\Widgets\AyyashAddons_Style_', '', $class_name );
				$_class_name = strtolower( $_class_name );
				$_class_name = str_replace( [ '_' ], '-', $_class_name );
				$file        = AYYASH_ADDONS_PATH . 'widgets/' . $_class_name . '/class-ayyash-addons-style-' . $_class_name . '.php';
				$helper      = AYYASH_ADDONS_PATH . 'widgets/' . $_class_name . '/' . $_class_name . '.php';

				if ( file_exists( $helper ) ) {
					$helpers[] = $helper;
				}

				unset( $helper );
			}
		}

		// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_print_r,WordPress.PHP.DevelopmentFunctions.error_log_error_log
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			if ( defined( 'AYYASH_ADDONS_DEV' ) && AYYASH_ADDONS_DEV && ! file_exists( $file ) ) {
				$data = print_r(
					[
						'symbol_name' => $class_name,
						'namespace'   => __NAMESPACE__,
						'path'        => $file,
						'c'           => $_class_name,
						'helpers'     => $helpers,
					],
					true
				);
				error_log( 'Failed to load file.' . PHP_EOL . $data );
			}
		}
		// phpcs:enable

		// We can read it
		if ( $file && file_exists( $file ) ) {
			// Load Helper First.
			if ( ! empty( $helpers ) ) {
				foreach ( $helpers as $helper ) {
					include_once $helper;
				}
			}


			// Load it.
			include_once $file;

		}
	}

	/**
	 * Function widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public static function widget_scripts() {

		//@TODO Use appropriate version for 3rd-party assets.
		$scripts = [
			'swiper-slider'               => [
				'src'     => '/assets/dist/js/libraries/swiper-bundle',
				'deps'    => [ 'jquery' ],
				'version' => AYYASH_ADDONS_VERSION,
			],
			'jquery-psgTimer'             => [
				'src'     => '/assets/dist/js/libraries/jquery.psgTimer',
				'deps'    => [ 'jquery' ],
				'version' => AYYASH_ADDONS_VERSION,
			],
			'ayyash-addons-query-builder' => [
				'src'     => '/assets/dist/js/libraries/query-builder',
				'deps'    => [ 'jquery' ],
				'version' => AYYASH_ADDONS_VERSION,
			],

			'jquery.beefup'               => [
				'src'     => '/assets/dist/js/libraries/jquery.beefup',
				'deps'    => [ 'jquery' ],
				'version' => AYYASH_ADDONS_VERSION,
			],
			'jquery.fancybox'             => [
				'src'     => '/assets/dist/js/libraries/jquery.fancybox',
				'deps'    => [ 'jquery' ],
				'version' => AYYASH_ADDONS_VERSION,
			],

			'ayyash-addons-core'          => [
				'src'     => '/assets/dist/js/ayyash-addons-core',
				'deps'    => [ 'jquery', 'wp-util', 'swiper-slider' ],
				'version' => AYYASH_ADDONS_VERSION,
			],

			'ayyash-addons-bootstrap'     => [
				'src'     => '/assets/dist/js/bootstrap',
				'deps'    => [],
				'version' => AYYASH_ADDONS_VERSION,
			],

			'sifter'                      => [
				'src'     => '/assets/dist/js/libraries/sifter',
				'deps'    => [],
				'version' => AYYASH_ADDONS_VERSION,
			],

			'isotope'                     => [
				'src'     => '/assets/dist/js/libraries/isotope.pkgd',
				'deps'    => [ 'jquery' ],
				'version' => '3.0.6',
			],
		];

		foreach ( $scripts as $name => $props ) {

			self::register_script( $name, $props['src'], $props['deps'], $props['version'] );
		}

		$data = apply_filters( 'ayyash-addons/js/data', [
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'_wpnonce' => wp_create_nonce( 'ayyash-addons-frontend' ),
			'i18n'     => [
				'monthly'      => esc_html__( 'Monthly', 'ayyash-addons' ),
				'annually'     => esc_html__( 'Annually', 'ayyash-addons' ),
				'or'           => esc_html__( 'Or', 'ayyash-addons' ),
				'okay'         => esc_html__( 'Okay', 'ayyash-addons' ),
				'cancel'       => esc_html__( 'Cancel', 'ayyash-addons' ),
				'submit'       => esc_html__( 'Submit', 'ayyash-addons' ),
				'success'      => esc_html__( 'Success', 'ayyash-addons' ),
				'warning'      => esc_html__( 'Warning', 'ayyash-addons' ),
				'error'        => esc_html__( 'Error', 'ayyash-addons' ),
				'e404'         => esc_html__( 'Requested Resource Not Found!', 'ayyash-addons' ),
				'are_you_sure' => esc_html__( 'Are You Sure?', 'ayyash-addons' ),
				'min_3'        => esc_html__( 'Please type Minimum 3 character!', 'ayyash-addons' ),
				'wait'         => esc_html__( 'Please wait...', 'ayyash-addons' ),
				'wrong'        => esc_html__( 'Something went wrong. Please try after sometime.', 'ayyash-addons' ),
			],
		] );

		wp_localize_script( 'ayyash-addons-core', 'AYYASH_ADDONS_JS', $data );

		wp_enqueue_script( 'ayyash-addons-core' );

		$widget_scripts = [
			'countdown',
			'heading',
			'blog',
			'vertical-menu',
			'product-search',
			'wishlist',
			'product-slider',
			'faq',
			'portfolio-grid',
		];

		foreach ( $widget_scripts as $script ) {
			self::register_script( 'ayyash-addons-' . $script, '/assets/dist/js/widgets/' . $script, [] );
		}
	}


	/**
	 * Function widget_styles
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public static function widget_styles() {

		$register_styles = [
			'ayyash-addons'           => [
				'src'     => '/assets/dist/css/ayyash-addons',
				'deps'    => [],
				'version' => AYYASH_ADDONS_VERSION,
				'has_rtl' => true,
			],
			'psgTimer'                => [
				'src'     => '/assets/dist/css/library/psgTimer',
				'deps'    => [],
				'version' => AYYASH_ADDONS_VERSION,
				'has_rtl' => true,
			],
			'ayyash-addons-bootstrap' => [
				'src'     => '/assets/dist/css/bootstrap',
				'deps'    => [],
				'version' => '5.3.0',
				'has_rtl' => true,
			],
		];

		foreach ( $register_styles as $name => $props ) {
			self::register_style( $name, $props['src'], $props['deps'], $props['version'], 'all', $props['has_rtl'] );
		}
		self::enqueue_style( 'ayyash-addons' );


		$widget_styles = [
			'countdown',
			'mini-cart',
			'heading',
			'search',
			'blog',
			'vertical-menu',
			'wishlist',
			'product-brand',
			'product-brand-v2',
			'product-slider',
			'product-categories',
			'logo-carousel',
			'portfolio-carousel',
			'portfolio-grid',
			'dual-button',
			'team',
			'blog-slider',
			'tab',
			'faq',
		];

		foreach ( $widget_styles as $style ) {
			self::register_style( 'ayyash-addons-' . $style, '/assets/dist/css/widgets/' . $style, [], AYYASH_ADDONS_VERSION, 'all', true );
		}
	}

	public static function preview_style() {
		self::enqueue_style( 'ayyash-addons-preview', '/assets/dist/css/preview' );
	}

	public static function preview_script() {
		self::enqueue_script(
			'ayyash-addons-preview',
			'/assets/dist/js/template-library.js',
			[
				'jquery',
				//	'elementor-editor',
			],
			AYYASH_ADDONS_VERSION,
			false
		);

	}

	public static function editor_scripts() {

		self::enqueue_style( 'ayyash-addons-editor', 'assets/dist/css/editor.css', [], AYYASH_ADDONS_VERSION, 'all', true );

		self::enqueue_script( 'ayyash-addons-editor', 'assets/dist/js/editor.js', [ 'elementor-editor' ] );

		wp_localize_script(
			'ayyash-addons-editor',
			'Ayyash_Addons_Editor_Config',
			[
				'has_pro'     => ayyash_addons_has_pro(),
				'i18n'        => [
					'ayyash_addons_pro' => esc_html__( 'Ayyash Addons Pro', 'ayyash-addons' ),
					/* translators: 1. Promo Widget Name */
					'promo.header'      => esc_html__( '%s Widget', 'ayyash-addons' ),
					/* translators: 1. Promo Widget Name */
					'promo.body'        => esc_html__( 'Use %s widget and a lot more exciting features and widgets to make your sites faster and better.', 'ayyash-addons' ),
				],
				'pro_widgets' => self::instance()->register_pro_widgets(),
				'mdv'         => apply_filters( 'ayyash_addons/controller/multiple_default_value', [] ),
			]
		);

	}

	public static function add_font_group( $font_groups ) {
		$font_groups['custom_fonts'] = esc_html__( 'Custom Fonts', 'ayyash-addons' );

		return $font_groups;
	}

	public static function add_additional_fonts( $additional_fonts ) {
		$theme_options = get_option( 'trix_theme_option' );
		for ( $i = 1; $i <= 50; $i ++ ) {
			if ( ! empty( $theme_options[ 'webfontName' . $i ] ) ) {
				$additional_fonts[] = $theme_options[ 'webfontName' . $i ];
			}
		}

		foreach ( $additional_fonts as $value ) {
			$additional_fonts[ $value ] = 'custom_fonts';
		}

		return $additional_fonts;
	}

	/**
	 * Register a script for use.
	 *
	 * @param string $handle Name of the script. Should be unique.
	 * @param string $path Full URL of the script, or path of the script relative to the WordPress root directory.
	 * @param string|string[] $deps An array of registered script handles this script depends on.
	 * @param string $version String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param boolean $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
	 *
	 * @uses   wp_register_script()
	 */
	public static function register_script( $handle, $path, $deps = [ 'jquery' ], $version = AYYASH_ADDONS_VERSION, $in_footer = true ) {

		if ( false === strpos( $path, '.js' ) ) {
			$path .= '.js';
		}

		if ( false === strpos( $path, 'http' ) ) {
			$path = self::plugin_url( $path );
		}

		$registered = wp_register_script( $handle, $path, $deps, self::asset_version( $path, $version ), $in_footer );

		if ( $registered ) {
			self::$scripts[] = $handle;
		}

	}

	/**
	 * Register and enqueue a script for use.
	 *
	 * @param string $handle Name of the script. Should be unique.
	 * @param string $path Full URL of the script, or path of the script relative to the WordPress root directory.
	 * @param string|string[] $deps An array of registered script handles this script depends on.
	 * @param string $version String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param boolean $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
	 *
	 * @uses   wp_enqueue_script()
	 */
	public static function enqueue_script( $handle, $path = '', $deps = [ 'jquery' ], $version = AYYASH_ADDONS_VERSION, $in_footer = true ) {
		if ( ! in_array( $handle, self::$scripts, true ) && $path ) {
			self::register_script( $handle, $path, $deps, $version, $in_footer );
		}
		wp_enqueue_script( $handle );
	}

	/**
	 * Register a style for use.
	 *
	 * @param string $handle Name of the stylesheet. Should be unique.
	 * @param string $path Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
	 * @param string[] $deps An array of registered stylesheet handles this stylesheet depends on.
	 * @param string $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param string $media The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
	 * @param boolean $has_rtl If has RTL version to load too.
	 *
	 * @uses   wp_register_style()
	 */
	public static function register_style( $handle, $path, $deps = [], $version = AYYASH_ADDONS_VERSION, $media = 'all', $has_rtl = false ) {

		if ( false === strpos( $path, '.css' ) ) {
			$path .= '.css';
		}

		if ( false === strpos( $path, 'http' ) ) {
			$path = self::plugin_url( $path );
		}

		$registered = wp_register_style( $handle, $path, $deps, self::asset_version( $path, $version ), $media );

		if ( $registered ) {
			self::$styles[] = $handle;

			if ( $has_rtl ) {
				wp_style_add_data( $handle, 'rtl', 'replace' );
			}
		}

	}

	/**
	 * Register and enqueue a styles for use.
	 *
	 * @param string $handle Name of the stylesheet. Should be unique.
	 * @param string $path Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
	 * @param string[] $deps An array of registered stylesheet handles this stylesheet depends on.
	 * @param string $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param string $media The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
	 * @param boolean $has_rtl If has RTL version to load too.
	 *
	 * @uses   wp_enqueue_style()
	 */
	public static function enqueue_style( $handle, $path = '', $deps = [], $version = AYYASH_ADDONS_VERSION, $media = 'all', $has_rtl = false ) {
		if ( ! in_array( $handle, self::$styles, true ) && $path ) {
			self::register_style( $handle, $path, $deps, $version, $media, $has_rtl );
		}
		wp_enqueue_style( $handle );
	}

	/**
	 * Register Controls
	 *
	 * Include Controls Files
	 *
	 * Register new Elementor Controls.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public static function register_controls() {
		$controls_manager = ElementorPlugin::instance()->controls_manager;

		$controls_manager->register( new Ayyash_Addons_Control_Styles() );
		$controls_manager->register( new Ayyash_Addons_Query_Builder() );
	}

	/**
	 * Get Widgets List.
	 *
	 * @return array
	 */
	public static function get_widgets() {

		return apply_filters( 'ayyash-addons/widgets', [
			'woocommerce-product-categories'      => [
				'label'       => __( 'Product Categories', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'woocommerce-product'                 => [
				'label'       => __( 'Woocommerce Product', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'woocommerce-product-list'            => [
				'label'       => __( 'Product List', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'woocommerce-product-deal'            => [
				'label'       => __( 'Product Deal', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'woocommerce-testimonial-carousel'    => [
				'label'       => __( 'Testimonial Carousel', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'woocommerce-testimonial-carousel-v2' => [
				'label'       => __( 'Testimonial Carousel v2', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'woocommerce-testimonial-carousel-v3' => [
				'label'       => __( 'Testimonial Carousel v3', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'woocommerce-product-hotspots'        => [
				'label'       => __( 'Product Hotspots', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'countdown'                           => [
				'label'       => __( 'CountDown', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'mini-cart'                           => [
				'label'       => __( 'Mini Cart', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'search'                              => [
				'label'       => __( 'Product Search', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],


			'blog'                                => [
				'label'       => __( 'Blog', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'wishlist'                            => [
				'label'       => __( 'Wishlist', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'heading'                             => [
				'label'       => __( 'Heading', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'woocommerce-attribute-filter'        => [
				'label'       => __( 'Attribute Filter', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'product-brand'                       => [
				'label'       => __( 'Product Brand', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'product-brand-v2'                    => [
				'label'       => __( 'Product Brand v2', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'product-categories'                  => [
				'label'       => __( 'Product Categories', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'vertical-menu'                       => [
				'label'       => __( 'Vertical Menu', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'woocommerce-product-slider'          => [
				'label'       => __( 'Product Slider', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'woocommerce-product-slider-v2'       => [
				'label'       => __( 'Product Slider V2', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'woocommerce-product-slider-v3'       => [
				'label'       => __( 'Product Slider V3', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'shortcode-slider'                    => [
				'label'       => __( 'Shortcode Slider', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'woocommerce-product-summery'         => [
				'label'       => __( 'Product Summery', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'woocommerce-sales-products'          => [
				'label'       => __( 'Woocommerce Sales Product', 'ayyash-addons' ),
				'is_pro'      => true,
				'is_new'      => false,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'logo-carousel'                       => [
				'label'       => __( 'Logo Carousel', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'dual-button'                         => [
				'label'       => __( 'Dual Button', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],


			'team'                                => [
				'label'       => __( 'Team', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'blog-slider'                         => [
				'label'       => __( 'Blog Slider', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'tab'                                 => [
				'label'       => __( 'Tabs', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'faq'                                 => [
				'label'       => __( 'FAQs', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'portfolio-carousel'                  => [
				'label'       => __( 'Portfolio Carousel', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],

			'portfolio-grid'                      => [
				'label'       => __( 'Portfolio Grid', 'ayyash-addons' ),
				'is_pro'      => false,
				'is_new'      => true,
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],


		] );
	}

	/**
	 * Get All Settings.
	 * @return array
	 */
	public function get_settings() {
		if ( null === $this->settings ) {
			$this->settings = get_option( 'ayyash_addons_settings', [] );
		}

		return null === $this->settings ? [] : $this->settings;
	}

	/**
	 * Get widget settings.
	 *
	 * @return array
	 */
	public function get_widgets_settings() {
		return isset( $this->settings['widgets'] ) && is_array( $this->settings['widgets'] ) ? $this->settings['widgets'] : [];
	}

	/**
	 * @return void
	 */
	public static function register_active_widgets() {
		$widgets_option = self::$_instance->get_widgets_settings();
		foreach ( self::get_widgets() as $slug => $data ) {

			// If upcoming or pro don't register.
			// Pro widgets will get registered by Pro Version.
			if ( $data['is_upcoming'] || $data['is_pro'] ) {
				continue;
			}

			if ( isset( $widgets_option[ $slug ] ) ) {
				$is_active = 'on' === $widgets_option[ $slug ];
			} else {
				$is_active = $data['is_active'];
			}

			$is_skin = isset( $data['is_skin'] ) ? $data['is_skin'] : false;

			if ( $is_active ) {

				if ( $is_skin ) {

					$class = explode( '-', $slug );
					$class = array_map( 'ucfirst', $class );
					$class = implode( '_', $class );
					$class = "AyyashAddons\\Widgets\\Posts\\" . $class;
					ElementorPlugin::instance()->widgets_manager->register( new $class() );

				} else {
					$class = explode( '-', $slug );
					$class = array_map( 'ucfirst', $class );
					$class = implode( '_', $class );
					$class = "AyyashAddons\\Widgets\\AyyashAddons_Style_" . $class;
					ElementorPlugin::instance()->widgets_manager->register( new $class() );
				}
			}
		}
	}


	/**
	 * @return array|array[]
	 */
	private function register_pro_widgets() {
		if ( ayyash_addons_has_pro() ) {
			return [];
		}

		return [

			[
				'name'  => 'ayyash-woocommerce-product',
				'title' => __( 'Woocommerce Product', 'ayyash-addons' ),
				'icon'  => 'eicon-products',
			],
		];
	}

	/**
	 * Get Asset string for static assets
	 *
	 * @param string $file file path.
	 * @param string $version original version.
	 *
	 * @return string
	 */
	public static function asset_version( $file, $version = AYYASH_ADDONS_VERSION ) {
		if ( self::is_debug() ) {
			if ( false === strpos( $file, AYYASH_ADDONS_PATH ) ) {
				$file = self::plugin_file( $file );
			}

			return file_exists( $file ) ? (string) filemtime( $file ) : time();
		}

		return $version;
	}

	/**
	 * Get full path for file relative to plugin directory.
	 *
	 * @param string $path File or path to resolve.
	 *
	 * @return string
	 */
	public static function plugin_file( $path ) {
		$path = ltrim( $path, '/\\' );
		$path = untrailingslashit( $path );

		return AYYASH_ADDONS_PATH . $path;
	}

	/**
	 * Get full url for file relative to this plugin directory.
	 *
	 * @param string $path Name of the file or directory to get the url for.
	 *
	 * @return string
	 */
	public static function plugin_url( $path ) {
		$path = ltrim( $path, '/\\' );
		$path = untrailingslashit( $path );

		return plugins_url( $path, AYYASH_ADDONS_FILE );
	}

	/**
	 * Check if WP Debug is enabled.
	 *
	 * @return bool
	 */
	public static function is_debug() {
		return apply_filters( 'ayyash_addons_debug_mode', ( defined( 'WP_DEBUG' ) && WP_DEBUG ) );
	}

	public static function is_dev() {
		return ( defined( 'WP_DEBUG' ) && WP_DEBUG ) && defined( 'AYYASH_ADDONS_DEV' ) && AYYASH_ADDONS_DEV;
	}


	public static function is_template_debug() {
		return self::is_dev() && defined( 'AYYASH_TEMPLATE_DEBUG' ) && AYYASH_TEMPLATE_DEBUG;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'ayyash-addons' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'ayyash-addons' ), '1.0.0' );
	}
}

// Instantiate Plugin Class.
Plugin::instance();
// End of file class-plugin.php

<?php
/**
 * Include files for Elementor widgets
 *
 * @package AyyashAddonsPro
 */

namespace AyyashAddonsPro;

use Elementor\Plugin as ElementorPlugin;
use AyyashAddons\Plugin as AyyashAddonsPlugin;


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
	private static $_instance;

	/**
	 * @var AyyashAddonsPlugin
	 */
	protected $base;

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
			 * AYYASH_ADDONS Pro Loaded.
			 *
			 * Fires when AyyashAddonsPro was fully loaded and instantiated.
			 *
			 * @since 1.0.0
			 */
			do_action( 'ayyash_addons/pro/loaded' );

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

		// Load helper functions.
		require_once AYYASH_ADDONS_PRO_PATH . 'includes/class-ayyash-addons-pro-services.php';
		require_once AYYASH_ADDONS_PRO_PATH . 'includes/helper.php';

		Ayyash_Addons_Pro_Services::get_instance();

		$this->base = AyyashAddonsPlugin::instance();

		$this->base->get_settings();

		// Register widget styles.
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'widget_styles' ], 8 );

		// Register widget scripts.
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'widget_scripts' ], 8 );

		spl_autoload_register( [ __CLASS__, 'autoload' ] );

		add_action( 'elementor/widgets/register', [ $this, 'register_active_widgets' ] );

	}

	private static function autoload( $class_name ) {
		if ( 0 !== strpos( $class_name, __NAMESPACE__ ) ) {
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
		$file          = AYYASH_ADDONS_PRO_PATH . 'includes/' . $_class_name . '.php';

		if ( ! file_exists( $file ) ) {
			$file = str_replace( 'class-', 'trait-', $file );
		}

		if ( ! file_exists( $file ) ) {
			if ( false !== strpos( $class_name, 'AyyashAddonsPro\Controls\Fields' ) ) {
				$_class_name = str_replace( 'AyyashAddonsPro\Controls\Fields\\', '', $class_name );
				$_class_name = strtolower( $_class_name );
				$file        = AYYASH_ADDONS_PRO_PATH . 'controls/fields/class-' . str_replace( [ '_' ], '-', $_class_name ) . '.php';
			} elseif ( false !== strpos( $class_name, 'AyyashAddonsPro\Controls' ) ) {
				$_class_name = str_replace( 'AyyashAddonsPro\Controls\\', '', $class_name );
				$_class_name = strtolower( $_class_name );
				$file        = AYYASH_ADDONS_PRO_PATH . 'controls/class-' . str_replace( [ '_' ], '-', $_class_name ) . '.php';
			} elseif ( false !== strpos( $class_name, 'AyyashAddonsPro\Widgets\Posts' ) ) {
				$_folder_name = str_replace( 'AyyashAddonsPro\Widgets', '', $class_name );
				$_folder_name = explode( '\\', $_folder_name );
				$_class_name  = end( $_folder_name );

				array_pop( $_folder_name );
				$_folder_name = array_map( 'strtolower', $_folder_name );
				$_folder_name = implode( '/', $_folder_name );

				$_class_name = strtolower( $_class_name );
				$file        = AYYASH_ADDONS_PRO_PATH . 'widgets/' . $_folder_name . '/' . str_replace( [ '_' ], '-', $_class_name ) . '.php';

			} elseif ( false !== strpos( $class_name, 'AyyashAddonsPro\Widgets' ) ) {
				$_class_name = str_replace( 'AyyashAddonsPro\Widgets\AyyashAddons_Style_', '', $class_name );
				$_class_name = strtolower( $_class_name );
				$_class_name = str_replace( [ '_' ], '-', $_class_name );
				$file        = AYYASH_ADDONS_PRO_PATH . 'widgets/' . $_class_name . '/class-ayyash-addons-style-' . $_class_name . '.php';
				$helper      = AYYASH_ADDONS_PRO_PATH . 'widgets/' . $_class_name . '/' . $_class_name . '.php';

				if ( file_exists( $helper ) ) {
					$helpers[] = $helper;
				}

				unset( $helper );
			}
		}

		// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_print_r,WordPress.PHP.DevelopmentFunctions.error_log_error_log
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			if ( defined( 'AYYASH_ADDONS_PRO_DEV' ) && AYYASH_ADDONS_PRO_DEV && ! file_exists( $file ) ) {
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

	/* Get Widgets List.
	*
	* @return array
	*/
	public function get_widgets() {
		$widgets = version_compare( AYYASH_ADDONS_VERSION, '1.0.0', '<' ) ?
			$this->base->get_widgets() : AyyashAddonsPlugin::get_widgets();

		return apply_filters(
			'ayyash_addons/pro/widgets',
			array_filter(
				$widgets,
				function ( $widget ) {
					return $widget['is_pro'];
				}
			)
		);
	}

	/**
	 * Get All Settings.
	 * @return array
	 */
	public function get_settings() {
		return $this->base->get_settings();
	}

	/**
	 * Get widget settings.
	 *
	 * @return array
	 */
	public function get_widgets_settings() {
		return $this->base->get_widgets_settings();
	}

	/**
	 * Autoload active widgets.
	 *
	 * @return void
	 */
	public function register_active_widgets() {
		$widgets_option = $this->get_widgets_settings();
		foreach ( $this->get_widgets() as $key => $val ) {

			if ( $val['is_upcoming'] || ! $val['is_pro'] ) {
				continue;
			}

			$is_active = $val['is_active'];
			if ( isset( $widgets_option[ $key ] ) ) {
				$is_active = 'on' === $widgets_option[ $key ];
			}

			if ( $is_active ) {
				$class = explode( '-', $key );
				$class = array_map( 'ucfirst', $class );
				$class = implode( '_', $class ) . '';
				$class = "AyyashAddonsPro\\Widgets\\AyyashAddons_Style_" . $class;
				ElementorPlugin::instance()->widgets_manager->register( new $class() );
			}
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
			'ayyash-addons-pro-countdown' => [
				'src'     => '/assets/dist/js/libraries/jquery.countdown',
				'deps'    => [],
				'version' => AYYASH_ADDONS_PRO_VERSION,
			],
		];

		foreach ( $scripts as $name => $props ) {
			self::register_script( $name, $props['src'], $props['deps'], $props['version'] );
		}

		$data = apply_filters( 'ayyash-addons/js/data', [
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'_wpnonce' => wp_create_nonce( 'ayyash-addons-frontend' ),
			'i18n'     => [
				'monthly'      => esc_html__( 'Monthly', 'ayyash-addons-pro' ),
				'annually'     => esc_html__( 'Annually', 'ayyash-addons-pro' ),
				'or'           => esc_html__( 'Or', 'ayyash-addons-pro' ),
				'okay'         => esc_html__( 'Okay', 'ayyash-addons-pro' ),
				'cancel'       => esc_html__( 'Cancel', 'ayyash-addons-pro' ),
				'submit'       => esc_html__( 'Submit', 'ayyash-addons-pro' ),
				'success'      => esc_html__( 'Success', 'ayyash-addons-pro' ),
				'warning'      => esc_html__( 'Warning', 'ayyash-addons-pro' ),
				'error'        => esc_html__( 'Error', 'ayyash-addons-pro' ),
				'e404'         => esc_html__( 'Requested Resource Not Found!', 'ayyash-addons-pro' ),
				'are_you_sure' => esc_html__( 'Are You Sure?', 'ayyash-addons-pro' ),
			],
		] );

		wp_localize_script( 'ayyash-addons-core', 'AYYASH_ADDONS_PRO_JS', $data );

		wp_enqueue_script( 'ayyash-addons-core' );

		$widget_scripts = [
			'woocommerce-attr-filter',
			'woocommerce-product-slider-v2',
			'woocommerce-sales-products',
			'woocommerce-product-hotspots',
			'shortcode-slider',
			'woocommerce-product-slider-v3',
			'woocommerce-testimonial-carousel-v2',
		];

		foreach ( $widget_scripts as $script ) {
			self::register_script( 'ayyash-addons-pro-' . $script, '/assets/dist/js/widgets/' . $script, [] );
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
			'ayyash-addons-pro' => [
				'src'     => '/assets/dist/css/ayyash-addons-pro',
				'deps'    => [],
				'version' => AYYASH_ADDONS_PRO_VERSION,
				'has_rtl' => true,
			],
		];

		foreach ( $register_styles as $name => $props ) {
			self::register_style( $name, $props['src'], $props['deps'], $props['version'], 'all', $props['has_rtl'] );
		}
		self::enqueue_style( 'ayyash-addons-pro' );


		$widget_styles = [
			'woocommerce-product',
			'woocommerce-product-list',
			'woocommerce-product-deal',
			'woocommerce-product-cat',
			'woocommerce-testimonial-carousel',
			'woocommerce-testimonial-carousel-v2',
			'woocommerce-testimonial-carousel-v3',
			'woocommerce-product-search',
			'woocommerce-attr-filter',
			'woocommerce-product-slider',
			'woocommerce-product-slider-v2',
			'woocommerce-sales-products',
			'woocommerce-product-hotspots',
			'shortcode-slider',
			'woocommerce-product-slider-v3',
			'woocommerce-product-summery',
		];

		foreach ( $widget_styles as  $style ) {
			self::register_style( 'ayyash-addons-pro-' . $style, '/assets/dist/css/widgets/' . $style, [], AYYASH_ADDONS_PRO_VERSION, 'all', true );
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
//				'elementor-editor',
			],
			AYYASH_ADDONS_PRO_VERSION,
			false
		);

	}

	public static function editor_scripts() {

		self::enqueue_style( 'ayyash-addons-editor', 'assets/dist/css/editor.css', [], AYYASH_ADDONS_PRO_VERSION, 'all', true );

		self::enqueue_script( 'ayyash-addons-editor', 'assets/dist/js/editor.js', [ 'elementor-editor' ] );

	}

	public static function add_font_group( $font_groups ) {
		$font_groups['custom_fonts'] = esc_html__( 'Custom Fonts', 'ayyash-addons-pro' );

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
	public static function register_script( $handle, $path, $deps = [ 'jquery' ], $version = AYYASH_ADDONS_PRO_VERSION, $in_footer = true ) {

		if ( false === strpos( $path, '.js' ) ) {
			$path .= '.js';
		}

		if ( false === strpos( $path, 'http' ) ) {
			$path = self::plugin_url( $path );
		}

		$registered = wp_register_script( $handle, $path, $deps, AyyashAddonsPlugin::asset_version( $path, $version ), $in_footer );

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
	public static function enqueue_script( $handle, $path = '', $deps = [ 'jquery' ], $version = AYYASH_ADDONS_PRO_VERSION, $in_footer = true ) {
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
	public static function register_style( $handle, $path, $deps = [], $version = AYYASH_ADDONS_PRO_VERSION, $media = 'all', $has_rtl = false ) {

		if ( false === strpos( $path, '.css' ) ) {
			$path .= '.css';
		}

		if ( false === strpos( $path, 'http' ) ) {
			$path = self::plugin_url( $path );
		}

		$registered = wp_register_style( $handle, $path, $deps, AyyashAddonsPlugin::asset_version( $path, $version ), $media );

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
	public static function enqueue_style( $handle, $path = '', $deps = [], $version = AYYASH_ADDONS_PRO_VERSION, $media = 'all', $has_rtl = false ) {
		if ( ! in_array( $handle, self::$styles, true ) && $path ) {
			self::register_style( $handle, $path, $deps, $version, $media, $has_rtl );
		}
		wp_enqueue_style( $handle );
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

		return AYYASH_ADDONS_PRO_PATH . $path;
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

		return plugins_url( $path, AYYASH_ADDONS_PRO_FILE );
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
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'ayyash-addons-pro' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'ayyash-addons-pro' ), '1.0.0' );
	}
}

// Instantiate Plugin Class.
Plugin::instance();
// End of file class-plugin.php

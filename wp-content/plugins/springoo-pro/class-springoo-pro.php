<?php
/**
 * The Companion.
 *
 * @package springoo/core
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}


/** @define "SPRINGOO_PRO_PATH" "./" */

if ( ! class_exists( 'Springoo_Pro' ) ) {
	/**
	 * Class Springoo_Pro
	 */
	class Springoo_Pro {

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		public $version = '1.0.0';

		/**
		 * The plugin url
		 *
		 * @var string
		 */
		public $plugin_url;

		/**
		 * The plugin path
		 *
		 * @var string
		 */
		public $plugin_path;

		/**
		 * The theme directory path
		 *
		 * @var string
		 */
		public $theme_dir_path;

		/**
		 * Singleton class instance.
		 *
		 * @var Springoo_Pro
		 */
		protected static $instance;

		/**
		 * Create & return singleton instance of this class.
		 *
		 * @return Springoo_Pro
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Springoo_Pro constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			$this->init();
		}

		/**
		 * Initialize the plugin
		 *
		 * @return void
		 */
		private function init() {

			add_action( 'plugins_loaded', array( $this, 'file_includes' ) );

			// Localize our plugin.
			add_action( 'init', array( $this, 'localization_setup' ) );

			// Loads frontend scripts and styles.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			register_activation_hook( __FILE__, array( $this, 'activate' ) );

			add_action( 'widgets_init', array( $this, 'register_widgets' ) );

			// After theme loaded.
			add_action( 'after_setup_theme', array( $this, 'after_theme_setup_file_includes' ) );

		}

		/**
		 * Register Widgets.
		 *
		 * @return void
		 */
		public function register_widgets() {

			include_once SPRINGOO_PRO_PATH . 'widgets/widget-social-share.php'; // phpcs:ignore WPThemeReview
			include_once SPRINGOO_PRO_PATH . 'widgets/widget-color-switch.php'; // phpcs:ignore WPThemeReview

			register_widget( 'Springoo_Widgets_social_share' );
			register_widget( 'Posterlaab_Color_Attribute_Widget' );

		}


		/**
		 * The plugin activation function
		 *
		 * @return void
		 */
		public function activate() {
		}

		/**
		 * Load the required files
		 *
		 * @return void
		 */
		public function file_includes() {

			/**
			 * Action to signal that Springoo PRO  has finished loading.
			 *
			 * @since 3.6.0
			 */
			do_action( 'springoo_pro_loaded' );

			$author = wp_get_theme()->get( 'Author' );

			if ( in_array( $author, [
				'ThemeRox',
			] ) ) {
				include_once SPRINGOO_PRO_PATH . 'widgets/widget-attr.php';
				include_once SPRINGOO_PRO_PATH . 'inc/springoo-pro-hook-helper.php';
				include_once SPRINGOO_PRO_PATH . 'inc/springoo-pro-hooks.php';

				if ( class_exists( 'WooCommerce' ) ) {
					/**
					 * WooCommerce Products(shop) & Single product section
					 */
					include_once SPRINGOO_PRO_PATH . 'woocommerce-product/class-springoo-wc-products.php';
					include_once SPRINGOO_PRO_PATH . 'woocommerce-product/class-springoo-wc-single-product.php';
					include_once SPRINGOO_PRO_PATH . 'woocommerce-cart-tab/woocommerce-dynamic-cart.php';
					include_once SPRINGOO_PRO_PATH . 'woocommerce-product-gallery/class-woocommerce-product-gallery.php';
					include_once SPRINGOO_PRO_PATH . 'woocommerce-product/class-springoo-wc-single-product-visitor-count.php';
				}
				if ( ! class_exists( 'CSF' ) ) {
					require_once SPRINGOO_PRO_PATH . 'cf/codestar-framework.php';
					require_once SPRINGOO_PRO_PATH . 'meta-box/theme-page-post-metabox.php';
					require_once SPRINGOO_PRO_PATH . 'meta-box/springoo-register-metabox.php';
				}
			} else {
				add_action( 'admin_notices', array( $this, 'admin_notice_error' ) );
			}
			// phpcs:enable
		}

		/**
		 * Load the required files after theme setup
		 *
		 * @return void
		 */
		public function after_theme_setup_file_includes() {
			$author = wp_get_theme()->get( 'Author' );
			if ( in_array(
				$author,
				array(
					'ThemeRox',
					'Themerox',
					'themerox',
					'springoo',
					'springoo',
					'springoo',
				)
			) ) {
				include_once SPRINGOO_PRO_PATH . 'customizer/class-springoo-customize-pro.php';
			}
		}

		/**
		 * Initialize plugin for localization
		 *
		 * @uses load_plugin_textdomain()
		 */
		public function localization_setup() {
			load_plugin_textdomain( 'springoo-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Enqueue admin scripts
		 *
		 * Allows plugin assets to be loaded.
		 *
		 * @uses wp_enqueue_script()
		 * @uses wp_enqueue_style
		 */
		public function enqueue_scripts() {

			/**
			 * All styles goes here
			 */
			// wp_enqueue_style( 'springoo-pro-style', plugins_url( 'assets/css/frontend.css', __FILE__ ), $this->version, date( 'Ymd' ) );

			/**
			 * All scripts goes here
			 */
			// wp_enqueue_script( 'springoo-pro-scripts', plugins_url( 'assets/js/frontend.js', __FILE__ ), array( 'jquery' ), $this->version, true );
		}

		/**
		 * Get the plugin url.
		 *
		 * @return string
		 */
		public function plugin_url() {
			if ( $this->plugin_url ) {
				$this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
			}

			return $this->plugin_url;
		}


		/**
		 * Get the plugin path.
		 *
		 * @return string
		 */
		public function plugin_path() {
			if ( ! $this->plugin_path ) {
				$this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
			}

			return $this->plugin_path;
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return $this->plugin_path() . '/templates/';
		}

		/**
		 * Admin notice if current theme is not supported by this plugin.
		 *
		 * @return void;
		 */
		public function admin_notice_error() {
			printf(
				'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
				esc_html__( '“Springoo Pro” Plugin is enabled but not effective. It requires “springoo” theme in order to work.', 'springoo-pro' )
			);
		}
	}
}
// End of file class-springoo-pro.php.
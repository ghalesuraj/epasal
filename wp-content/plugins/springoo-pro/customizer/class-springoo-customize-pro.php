<?php
/**
 * @package springoo
 * @author  ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Required default values
 */
require_once SPRINGOO_PRO_PATH. 'customizer/defaults.php';

/**
 * Springoo_Customize_Pro class
 */
if ( class_exists('Springoo_Customize') ) {
	class Springoo_Customize_Pro {

		/**
		 * Hold an instance of the class.
		 *
		 * @var $instance
		 */
		private static $instance;

		/**
		 * Springoo_Customize constructor.
		 *
		 * @return void
		 */
		public function __construct(){

			add_action( 'customize_register', [ __CLASS__, 'register' ] );

			add_action( 'wp_head', [ __CLASS__, 'generate_css' ], 100 );
		}

		/**
		 * Returns an instance of the Springoo_Customize class, creates one if an instance doesn't exist.
		 * Implements Singleton pattern
		 *
		 * @return Springoo_Customize_Pro
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) { // @phpstan-ignore-line
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * This hooks into 'customize_register' (available as of WP 3.4) and allows
		 * you to add new sections and controls to the Theme Customize screen.
		 *
		 * Note: To enable instant preview, we have to actually write a bit of custom
		 * javascript. See live_preview() for more.
		 *
		 * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
		 *
		 * @param $wp_customize
		 *
		 * @return void
		 */
		public static function register($wp_customize){
			if ( class_exists('WooCommerce') ) {
				require_once SPRINGOO_PRO_PATH . 'customizer/class-springoo-customize-woocommerce-pro.php';
				require_once SPRINGOO_PRO_PATH . 'customizer/class-springoo-customize-woocommerce-pro-single.php';
				Springoo_Customize_WooCommerce_Pro::register($wp_customize);
				Springoo_Customize_WooCommerce_Pro_Single::register($wp_customize);
			}

			if ( class_exists( 'WooCommerce' ) && defined( 'YITH_WCWL' ) ) {
				require_once SPRINGOO_PRO_PATH . 'customizer/class-springoo-customize-yith-wcwl.php';
				Springoo_Customize_YITH_WCWL::register($wp_customize);
			}

			if ( class_exists( 'YITH_WCQV' ) ) {
				require_once SPRINGOO_PRO_PATH . 'customizer/class-springoo-customize-yith-wcqv.php';
				Springoo_Customize_YITH_WCQV::register($wp_customize);
			}

			if ( class_exists( 'YITH_Woocompare' ) ) {
				require_once SPRINGOO_PRO_PATH . 'customizer/class-springoo-customize-yith-compare.php';
				Springoo_Customize_YITH_Compare::register($wp_customize);
			}
		}

		/**
		 * Prints Customizer Generated CSS & CSS Variables.
		 *
		 * @return void
		 */
		public static function generate_css(){
			$css = '';

			//WooCommerce grid gap size
			$mod = springoo_get_mod('woocommerce_shop_archive_grid_gap_size');
			if ($mod) {
				$css .= '.woocommerce ul.products.has-grid-gap { gap: ' . $mod . 'px; }';
			}

			//WooCommerce Product Label Top Position
			$mod = springoo_get_mod( 'woocommerce_shop_archive_label_top_position' );
			if ( $mod ) {
				$css .= '.woocommerce .products ul .springoo-product-labels, .woocommerce ul.products .springoo-product-labels{ top: '. $mod .'px; }';
			}

			//WooCommerce Product Label Left Position
			$mod = springoo_get_mod( 'woocommerce_shop_archive_label_left_position' );
			if ( $mod ) {
				$css .= '.woocommerce .products ul .springoo-product-labels, .woocommerce ul.products .springoo-product-labels{ left: '. $mod .'px; }';
			}

			// Print Css.
			if ( ! empty( $css ) ) {
				?>
				<style id="springoo-pro-css"><?php echo $css; // phpcs:ignore ?></style><?php
			}
		}

	}
	Springoo_Customize_Pro::get_instance();
}
// End of file class-springoo-customize-pro.php.

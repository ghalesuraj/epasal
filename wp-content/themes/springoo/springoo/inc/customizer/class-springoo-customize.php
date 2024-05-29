<?php
/**
 * Customizer Bootstrap.
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/** @define "SPRINGOO_THEME_DIR" "./../../" */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
/** @define "$springoo_customizer_path" "./../../inc/customizer" */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
$springoo_customizer_path = SPRINGOO_THEME_DIR . 'inc/customizer/';

// phpcs:disable WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require $springoo_customizer_path . 'defaults.php';
require $springoo_customizer_path . 'helpers.php';
require $springoo_customizer_path . 'google-fonts.php';
// phpcs:enable

if ( ! class_exists( 'Springoo_Customize' ) ) {

	/**
	 * Contains methods for customizing the theme customization screen.
	 *
	 * @link http://codex.wordpress.org/Theme_Customization_API
	 * @since MyTheme 1.0
	 */
	class Springoo_Customize {

		/**
		 * Hold an instance of the class.
		 *
		 * @var Springoo_Customize
		 */
		private static $instance;

		/**
		 * Springoo_Customize constructor.
		 *
		 * @return void
		 */
		public function __construct() {

			// Set up the Theme Customizer settings and controls...

			add_action( 'customize_register', [ __CLASS__, 'register' ] );

			/**
			 * Compress customizer css.
			 *
			 * @TODO use wp_get_custom_css hook to filter customizer's additional (user) css and compress for optimal output.
			 */

			// enqueue required fonts.
			add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_fonts' ] );

			/**
			 * Output custom CSS to live site before custom-css.
			 *
			 * @see wp_custom_css_cb
			 *
			 * customizer custom css hooked with 101 on wp_head.
			 */
			add_action( 'wp_head', [ __CLASS__, 'generate_css' ], 100 );

			//print customizer variable in editor mode
			add_action('enqueue_block_editor_assets', [ __CLASS__, 'enqueue_fonts' ] ); // @todo adding customizer google font in editor
			add_action('admin_print_styles', [ __CLASS__, 'generate_css' ], 100 );// @todo adding customizer variable in editor

			// Enqueue live preview javascript in Theme Customizer admin screen.
			// Add live_preview to customize_preview_init action.
			add_action( 'customize_controls_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
		}

		/**
		 * Returns an instance of the Springoo_Customize class, creates one if an instance doesn't exist.
		 * Implements Singleton pattern
		 *
		 * @return Springoo_Customize
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
		 * @param WP_Customize_Manager $wp_customize The customize manager.
		 *
		 * @return void
		 */
		public static function register( $wp_customize ) {
			global $springoo_customizer_path;

			// phpcs:disable WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			// Load all custom controls.
			require $springoo_customizer_path . 'custom-controls/class-springoo-multi-select-control.php';
			require $springoo_customizer_path . 'custom-controls/class-springoo-google-fonts-custom-control.php';
			require $springoo_customizer_path . 'custom-controls/class-springoo-textarea-control.php';
			require $springoo_customizer_path . 'custom-controls/class-springoo-customize-misc-control.php';
			require $springoo_customizer_path . 'custom-controls/class-springoo-multiple-checkbox-control.php';
			require $springoo_customizer_path . 'custom-controls/class-springoo-customizer-range-control.php';
			require $springoo_customizer_path . 'custom-controls/class-springoo-social-profile-control.php';

			// Load all Customizer Panels.
			require $springoo_customizer_path . 'panels/class-springoo-customize-general.php';
			require $springoo_customizer_path . 'panels/class-springoo-customize-layout.php';
			require $springoo_customizer_path . 'panels/class-springoo-customize-background-images.php';
			require $springoo_customizer_path . 'panels/class-springoo-customize-colors.php';
			require $springoo_customizer_path . 'panels/class-springoo-customize-typography.php';


			Springoo_Customize_General::register( $wp_customize );
			Springoo_Customize_Layout::register( $wp_customize );
			Springoo_Customize_Background_Images::register( $wp_customize );
			Springoo_Customize_Colors::register( $wp_customize );
			Springoo_Customize_Typography::register( $wp_customize );

			if ( class_exists( 'WooCommerce' ) ) {
				require $springoo_customizer_path . 'panels/class-springoo-customize-woocommerce.php';

				Springoo_Customize_WooCommerce::register( $wp_customize );
			}

			if ( class_exists( 'WPCF7_ContactForm' ) ) {
				require $springoo_customizer_path . 'panels/class-springoo-customize-wpcf7.php';

				Springoo_Customize_WPCF7::register( $wp_customize );
			}

			if ( class_exists('WeDevs_Dokan') ) {
				require $springoo_customizer_path . 'panels/class-springoo-customize-dokan.php';

				Springoo_Customize_Dokan::register( $wp_customize );
			}

			// phpcs:enable
		}

		/**
		 * This outputs the javascript needed to automate the live settings preview.
		 * Also keep in mind that this function isn't necessary unless your settings
		 * are using 'transport'=>'postMessage' instead of the default 'transport'
		 * => 'refresh'
		 *
		 * @return void
		 */
		public static function live_preview() {
			/* Live Preview using Javascript and postMessage Transport */
		}

		/**
		 * Enqueue customizer scripts.
		 *
		 * @return void
		 */
		public static function enqueue_scripts() {


			wp_enqueue_script( 'chosen', SPRINGOO_THEME_URI . '/assets/plugins/chosen/chosen.jquery.min.js', [ 'jquery' ], SPRINGOO_THEME_VERSION, true );
			wp_enqueue_script( 'springoo-customizer', SPRINGOO_THEME_URI . 'assets/dist/js/customizer.js', [ 'jquery', 'wp-color-picker' ], SPRINGOO_THEME_VERSION, true );
			wp_localize_script( 'springoo-customizer', 'springooCustomizerFontsL10n', springoo_get_all_fonts() );

			wp_enqueue_style( 'chosen', SPRINGOO_THEME_URI . '/assets/plugins/chosen/chosen.min.css', [], SPRINGOO_THEME_VERSION );
			wp_enqueue_style( 'springoo-customizer', SPRINGOO_THEME_URI . 'assets/dist/css/customizer.css', [], SPRINGOO_THEME_VERSION );

		}

		public static function preconnect_google_font_api( $urls, $relation_type ) {
			if ( 'preconnect' === $relation_type ) {
				$urls[] = 'https://fonts.googleapis.com';
				$urls[] = [
					'href'        => 'https://fonts.gstatic.com',
					'crossorigin' => true,
				];
			}

			return $urls;
		}

		/**
		 *
		 * @param string $context for which display prop is being used. E.G. body, logo, etc.
		 *
		 * @return false|string
		 */
		public static function get_google_font_display( $context = 'body' ) {
			$display = apply_filters( 'springoo_google_font_display', 'swap', $context );
			// https://www.w3.org/TR/css-fonts-4/#font-display-desc

			$display_values = [
				'auto'     => true,
				'block'    => true,
				'swap'     => true,
				'fallback' => true,
				'optional' => true,
			];
			// https://stackoverflow.com/questions/13483219/what-is-faster-in-array-or-isset

			if ( $display && isset( $display_values[ $display ] ) ) {
				return $display;
			}

			return false;
		}

		public static function get_google_font_variants_string( $font_family ) {
			$google_fonts = springoo_get_google_fonts();
			if ( ! isset( $google_fonts[ $font_family ] ) ) {
				return false;
			}

			$variants_list = $google_fonts[ $font_family ]['variants'];
			$variants      = maybe_serialize( $variants_list );
			$has_ital      = false !== strpos( $variants, 'regular' ) && false !== strpos( $variants, 'italic' );
			$variants      = $has_ital ? 'ital,wght@' : 'wght@';
			$exclude       = apply_filters( 'springoo_google_font_exclude_variants', null, $font_family, $variants_list );
			$variants_data = array_map( function ( $variant ) use ( $has_ital, $exclude ) {
				if ( 'regular' === $variant || 'italic' === $variant ) {
					$variant = 400;
				}
				$variant = intval( $variant );
				if ( is_array( $exclude ) && in_array( $variant, $exclude ) ) {
					return null;
				}

				return ( $has_ital ? '0,' : '' ) . $variant;
			}, $variants_list );

			$variants_data = array_filter( $variants_data );
			$variants_data = array_unique( $variants_data );
			$variants_data = implode( ';', $variants_data );
			$variants     .= $variants_data;

			if ( $has_ital ) {
				$variants .= ';';
				$variants .= str_replace( '0,', '1,', $variants_data );
			}

			return apply_filters( 'springoo_google_font_variants_string', $variants, $font_family, $variants_list );
		}

		public static function generate_google_font_url() {
			$google_fonts   = [];
			$defaults       = array_keys( springoo_get_standard_fonts() );
			$fonts_settings = [
				springoo_get_mod( 'typography_global_font_family' ),
				springoo_get_mod( 'typography_heading_font_family' ),
				springoo_get_mod( 'typography_heading_h1_font_family' ),
				springoo_get_mod( 'typography_heading_h2_font_family' ),
				springoo_get_mod( 'typography_heading_h3_font_family' ),
				springoo_get_mod( 'typography_heading_h4_font_family' ),
				springoo_get_mod( 'typography_heading_h5_font_family' ),
				springoo_get_mod( 'typography_heading_h6_font_family' ),
				springoo_get_mod( 'typography_menu_font_family' ),
				springoo_get_mod( 'typography_menu_sub_font_family' ),
				springoo_get_mod( 'typography_menu_mobile_font_family' ),
				springoo_get_mod( 'typography_sidebar_title_font_family' ),
				springoo_get_mod( 'typography_sidebar_body_font_family' ),
				springoo_get_mod( 'typography_footer_title_font_family' ),
				springoo_get_mod( 'typography_footer_body_font_family' ),
				springoo_get_mod( 'typography_footer_text_font_family' ),

				/**
				 * @see Springoo_Customize::enqueue_fonts()
				 */
				springoo_get_mod( 'typography_site_title_font_family' ),
				springoo_get_mod( 'typography_site_tagline_font_family' ),
			];

			foreach ( $fonts_settings as $font_family ) {
				if ( in_array( $font_family, [ 'default', 'Default' ], true ) ) {
					continue;
				}
				if ( ! empty( $font_family ) && ! in_array( $font_family, $defaults ) ) {
					$variants = self::get_google_font_variants_string( $font_family );
					if ( ! isset( $google_fonts[ $font_family ] ) ) {
						$google_fonts[ $font_family ] = $font_family . ':' . $variants;
					}
				}
			}

			if ( ! empty( $google_fonts ) ) {

				$google_fonts = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $google_fonts );
				$display      = self::get_google_font_display();
				if ( $display ) {
					$google_fonts .= '&display=' . $display;
				}

				return apply_filters( 'springoo_google_font_url', str_replace( [ ' ' ], '+', $google_fonts ) );
			}

			return false;
		}

		/**
		 * Enqueue fonts.
		 *
		 * @return void
		 */
		public static function enqueue_fonts() {

			if ( false === apply_filters( 'springoo_enqueue_google_fonts', true ) ) {
				return;
			}

			add_filter( 'wp_resource_hints', [ __CLASS__, 'preconnect_google_font_api' ], 10, 2 );

			// @TODO if not title_tagline_hide_title load specific google font for title & tagline with text param ...
			// @see https://developers.google.com/fonts/docs/css2#optimizing_your_font_requests

			$font_api = self::generate_google_font_url();
			if ( $font_api ) {
				wp_enqueue_style( 'springoo_google-fonts', $font_api, [], null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			}
		}

		/**
		 * Prints Customizer Generated CSS & CSS Variables.
		 *
		 * @return void
		 */
		public static function generate_css() {
			// phpcs:disable WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			$mappings = require SPRINGOO_THEME_DIR . 'inc/customizer/css-variable-mapping.php';
			$bg       = [
				'bg_image_global_site'      => '.boxed',
				'title_bar_title_container' => '#springoo-breadcrumb-area',
				'footer_top_bg_img'         => '.springoo-footer-top',
			];
			// phpcs:enable

			$css  = '';
			$vars = '';

			// Font Family
			$fallback_fonts = '-apple-system, BlinkMacSystemFont, "Segoe UI", "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"';

			// Type scale.
			$type_scale     = abs( floatval( get_theme_mod( 'typography_global_scale' ) ) );
			$base_font_size = absint( get_theme_mod( 'typography_global_font_size' ) );
			if ( ! $base_font_size ) {
				$base_font_size = 16;
			}

			// See scss/partials/_variables.scss, scss/partials/_root.scss, scss/partials/_type-scale.scss
			$scale_base = ( $base_font_size / 16 );

			if ( 16 !== $base_font_size ) {
				$css  .= "html{font-size:{$base_font_size}px}";
				$vars .= "--springoo-h6-font-size:{$scale_base}rem;";
			}

			if ( $type_scale && 1.125 !== $type_scale ) {
				$size = $scale_base;
				foreach ( [ 'h5', 'h4', 'h3', 'h2', 'h1' ] as $tag ) {
					$size  = $size * $type_scale;
					$vars .= "--springoo-{$tag}-font-size:" . round( $size, 3 ) . 'rem;';
				}
			}

			foreach ( $bg as $mod => $selector ) {
				$css .= self::background_image_mod( $mod, $selector );
			}

			/* Container Fluid Max Width */
			$mod = springoo_get_mod( 'layout_global_content_layout_max_width' );
			if ( $mod ) {
				$css .= '.container-fluid{max-width:' . absint( $mod ) . 'px}';
			}

			/* Logo Max Width */
			$mod = springoo_get_mod( 'logo_width' );
			if ( $mod ) {
				$css .= '.custom-logo-link img,.custom-logo-link svg{max-width:' . absint( $mod ) . 'px;width:' . absint( $mod ) . 'px;}';
			}


			/* Contact Form 7 Button */
			$mod  = springoo_get_mod( 'layout_wpcf7_btn_position' );
			$css .= '.wpcf7-form .wpcf7-submit-wrap {text-align:' . $mod . ';}';

			if ( 'left' === $mod ) {
				$spinner_pos = 'right';
			} else {
				$spinner_pos = 'left';
			}
			$css .= '.wpcf7-form .wpcf7-submit-wrap .wpcf7-spinner {' . $spinner_pos . ':-36px;}';

			/* Single Product buy now btn radius */
			$mod = springoo_get_mod( 'woocommerce_single_buy_btn_radius' );
			if ( $mod ) {
				$css .= '.woocommerce div.product .button.springoo-single-buy-now { border-radius:' . $mod . 'px; }';
			}
			/* Single Product add to cart btn type */
			$mod = springoo_get_mod( 'woocommerce_single_cart_btn_type' );
			if ( 'outlined' === $mod ) {
				$css .= '.woocommerce div.product .button.single_add_to_cart_button { background: transparent; color: var( --springoo-color-primary ); border: 1px solid var( --springoo-color-primary ); }';
			}
			/* Single Product add to cart btn radius */
			$mod = springoo_get_mod( 'woocommerce_single_cart_btn_radius' );
			if ( $mod ) {
				$css .= '.woocommerce div.product .button.single_add_to_cart_button { border-radius:' . $mod . 'px; }';
			}


			// Print Css.
			if ( ! empty( $css ) ) {
				?>
				<style id="springoo-css"><?php echo strip_tags( $css ); // phpcs:ignore ?></style><?php
			}

			foreach ( $mappings as $k => $v ) {
				$mod = springoo_get_mod( $k );
				if ( is_array( $mod ) ) {
					// @XXX in some case font_variant return's array (maybe for old installation/not selected).
					$mod = reset( $mod );
				}

				$mod = esc_attr( (string) $mod );

				if ( $mod && 'default' !== strtolower( $mod ) ) {
					// font family.
					if ( false !== strpos( $k, 'font_family' ) ) {
						$vars .= "$v:\"{$mod}\"";
						if ( ! in_array( $mod, [ 'Monospaced', 'Serif', 'Sans Serif' ] ) ) {
							$vars .= ',' . $fallback_fonts;
						}
						$vars .= ';';
						continue;
					}

					// font weight & style.
					if ( false !== strpos( $k, 'font_variant' ) ) {
						$style = str_replace( 'font-weight', 'font-style', $v );

						if ( in_array( $mod, [ 'regular', 'italic' ] ) ) {
							$vars .= "$v:400;";
							if ( 'italic' === $mod ) {
								$vars .= "$style:italic;";
							}

							continue;
						}

						if ( strpos( $mod, 'regular' ) || strpos( $mod, 'italic' ) ) {
							$vars .= $v . ':' . str_replace( [ 'regular', 'italic' ], '', $mod ) . ';';
							if ( strpos( $mod, 'italic' ) ) {
								$vars .= "$style:italic;";
							}
							continue;
						}
					}

					$vars .= "$v:{$mod}";

					if ( strpos( $k, '_main_menu_width' ) || strpos( $k, '_button_width' ) ) {
						$vars .= '%';
					} elseif (
						( false !== strpos( $k, 'layout_' ) && ! ( false !== strpos( $k, 'align' ) ) ) ||
						strpos( $k, 'font_size' ) ||
						strpos( $k, 'letter_spacing' ) ||
						strpos( $k, 'word_spacing' ) ||
						false !== strpos( $k, '_logo_width' )
					) {
						$vars .= 'px';
					}

					$vars .= ';';
				}
			}

			if ( ! empty( $vars ) ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					$vars = str_replace( [ ';' ], ';' . PHP_EOL . "\t", $vars );
				}
				?><style id="springoo-css-vars">:root {<?php echo strip_tags( $vars ); // phpcs:ignore ?>}</style><?php
			}
		}

		/**
		 * @param string $mod
		 * @param string $selector
		 *
		 * @return string
		 */
		protected static function background_image_mod( $mod, $selector ) {
			$mod = springoo_get_mod( $mod );
			if ( $mod ) {
				return sprintf( '%s{background-image:url(%s)}', $selector, esc_url( $mod ) );
			}

			return '';
		}
	}
}

Springoo_Customize::get_instance();
// End of file class-springoo-customize.php.

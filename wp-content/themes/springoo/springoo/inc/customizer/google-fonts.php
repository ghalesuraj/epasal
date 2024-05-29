<?php
/**
 * Google Fonts Helper.
 *
 * @package Springoo
 * @author ThemeRox
 * @version 1.0.2
 * @since Springoo 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! function_exists( 'springoo_get_all_fonts' ) ) :
	/**
	 * Returns an Array of all the Available Font Options
	 *
	 * @param bool $show_header
	 * @return array
	 */
	function springoo_get_all_fonts( $show_header = true ) {

		if ( $show_header ) {
			return array_merge(
				[
					'------- Standard Fonts -------' => [
						'disabled' => true,
						'variants' => [],
					],
				],
				springoo_get_standard_fonts(),
				[
					'------- Google Fonts -------' => [
						'disabled' => true,
						'variants' => [],
					],
				],
				springoo_get_google_fonts()
			);
		}

		return array_merge(
			springoo_get_standard_fonts(),
			springoo_get_google_fonts()
		);
	}
endif;

if ( ! function_exists( 'springoo_get_standard_fonts' ) ) :
	/**
	 * Returns an Array of all the Available Standard Fonts
	 *
	 * @return array
	 */
	function springoo_get_standard_fonts() {
		return apply_filters( 'springoo_get_standard_fonts', [
			__( 'Default', 'springoo' ) => [
				'variants' => [
					'100',
					'200',
					'300',
					'regular',
					'500',
					'600',
					'700',
					'800',
					'900',
				],
			],
			'Serif'                     => [ 'variants' => [ 'regular' ] ],
			'Sans Serif'                => [ 'variants' => [ 'regular' ] ],
			'Monospaced'                => [ 'variants' => [ 'regular' ] ],
		] );
	}
endif;

if ( ! function_exists( 'springoo_get_google_fonts' ) ) :
	/**
	 * Returns an Array of all the Available Google Fonts
	 *
	 * @return array
	 */

	function springoo_get_google_fonts() {
		static $fonts;
		global $wp_filesystem;

		require_once ( ABSPATH . '/wp-admin/includes/file.php' ); //phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		WP_Filesystem();

		if ( null === $fonts ) {
			$fonts = $wp_filesystem->get_contents( SPRINGOO_THEME_DIR . 'assets/fonts.json' );
			if ( $fonts ) {
				$fonts = json_decode( $fonts, true );
			}
		}

		return $fonts ? $fonts : [];
	}
endif;

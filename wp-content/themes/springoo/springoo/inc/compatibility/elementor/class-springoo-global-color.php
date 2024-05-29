<?php
/**
 * Global Colors.
 *
 * @package Springoo
 * @author ThemeRox
 * @since 1.1.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! class_exists( 'Springoo_Global_Color' ) ) {
	class Springoo_Global_Color {

		/**
		 * Springoo_Global_Color constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'rest_request_after_callbacks', array( $this, 'elementor_add_theme_colors' ), 999, 3 );
			add_filter( 'rest_request_after_callbacks', array( $this, 'display_global_colors_front_end' ), 999, 3 );
		}


		/**
		 * Springoo Palettes
		 *
		 * @return array
		 */
		public function global_palette() {
			return array(
				'id'    => array(
					'springoo_ac',
					'springoo_ahc',
					'springoo_hc',
					'springoo_tc',
					'springoo_cbc',
					'springoo_sbc',
					'springoo_boc',
					'springoo_credit',
					'springoo_credit_bg',
					'springoo_gradient',
				),
				'title' => array(
					__( 'Springoo Accent', 'springoo' ),
					__( 'Springoo Accent Hover', 'springoo' ),
					__( 'Springoo Header', 'springoo' ),
					__( 'Springoo Text', 'springoo' ),
					__( 'Springoo Content', 'springoo' ),
					__( 'Springoo Site Background', 'springoo' ),
					__( 'Springoo Border', 'springoo' ),
					__( 'Springoo Credit', 'springoo' ),
					__( 'Springoo Credit Background', 'springoo' ),
					__( 'Springoo Gradient', 'springoo' ),
				),
				'color' => array(
					get_theme_mod( 'colors_global_accent' ) ? get_theme_mod( 'colors_global_accent' ) : '#2626f3',
					get_theme_mod( 'colors_global_accent_shade' ) ? get_theme_mod( 'colors_global_accent_shade' ) : '#0000f2',
					get_theme_mod( 'colors_global_heading' ) ? get_theme_mod( 'colors_global_heading' ) : '#222222',
					get_theme_mod( 'colors_global_text' ) ? get_theme_mod( 'colors_global_text' ) : '#333333',
					get_theme_mod( 'colors_global_content_bg' ) ? get_theme_mod( 'colors_global_content_bg' ) : '#f9f9f9',
					get_theme_mod( 'colors_global_site_bg' ) ? get_theme_mod( 'colors_global_site_bg' ) : '',
					get_theme_mod( 'colors_global_border' ) ? get_theme_mod( 'colors_global_border' ) : '',
					get_theme_mod( 'colors_footer_sc_text' ) ? get_theme_mod( 'colors_footer_sc_text' ) : '',
					get_theme_mod( 'colors_footer_sc_bg' ) ? get_theme_mod( 'colors_footer_sc_bg' ) : '',
					get_theme_mod( 'colors_global_gradient' ) ? get_theme_mod( 'colors_global_gradient' ) : '',
				),
			);
		}

		/**
		 * Theme Colors for elementor
		 *
		 * @param $response
		 * @param $handler
		 * @param $request
		 *
		 * @return mixed
		 * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed
		 */
		public function elementor_add_theme_colors( $response, $handler, $request ) {
			$route = $request->get_route();
			if ( '/elementor/v1/globals' != $route ) {
				return $response;
			}

			$global_palette       = self::global_palette();
			$global_palette_color = $global_palette['color'];
			$data                 = $response->get_data();
			$slugs                = $global_palette['id'];
			$labels               = $global_palette['title'];

			foreach ( $global_palette_color as $key => $color ) {
				$id    = $slugs[ $key ] ? $slugs[ $key ] : '';
				$label = $labels[ $key ] ? $labels[ $key ] : '';

				if ( ! $id || ! $label || ! $color ) {
					continue;
				}

				$data['colors'][ $id ] = [
					'id'    => esc_attr( $id ),
					'title' => $label,
					'value' => $color,
				];
			}

			$response->set_data( $data );

			return $response;
		}

		/**
		 * Display Colors in frontend
		 *
		 * @param $response
		 * @param $handler
		 * @param $request
		 *
		 * @return mixed|WP_Error|WP_HTTP_Response|WP_REST_Response
		 * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed
		 */
		public function display_global_colors_front_end( $response, $handler, $request ) {
			$route = $request->get_route();

			if ( 0 !== strpos( $route, '/elementor/v1/globals' ) ) {
				return $response;
			}

			$slug_map       = array();
			$global_palette = self::global_palette();
			$palette_slugs  = $global_palette['id'];

			foreach ( $palette_slugs as $key => $slug ) {
				$slug_map[ $slug ] = $key;
			}

			$rest_id = substr( $route, strrpos( $route, '/' ) + 1 );

			if ( ! in_array( $rest_id, array_keys( $slug_map ), true ) ) {
				return $response;
			}

			$colors = $global_palette['color'];
			return rest_ensure_response(
				array(
					'id'    => esc_attr( $rest_id ),
					'title' => 'var(--' . esc_html( $slug_map[ $rest_id ] ),
					'value' => $colors[ $slug_map[ $rest_id ] ],
				)
			);
		}
	}
	new Springoo_Global_Color();
}



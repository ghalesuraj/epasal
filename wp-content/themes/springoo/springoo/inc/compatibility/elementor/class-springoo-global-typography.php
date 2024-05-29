<?php
/**
 * Global Typography.
 *
 * @package Springoo
 * @author ThemeRox
 * @since 1.1.8.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! class_exists( 'Springoo_Global_Typography' ) ) {
	class Springoo_Global_Typography {

		/**
		 * Springoo_Global_Typography constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'rest_request_after_callbacks', array( $this, 'elementor_add_theme_typography' ), 999, 3 );
			add_filter( 'rest_request_after_callbacks', array( $this, 'display_global_typo_front_end' ), 999, 3 );
		}

		/**
		 * Springoo Typography
		 *
		 * @return array
		 */
		public function global_typography() {
			return [
				'id'    => array(
					'springoo_h1',
					'springoo_h2',
					'springoo_h3',
					'springoo_h4',
					'springoo_h5',
					'springoo_h6',
					'springoo_text',
				),
				'title' => array(
					__( 'Springoo Heading 1', 'springoo' ),
					__( 'Springoo Heading 2', 'springoo' ),
					__( 'Springoo Heading 3', 'springoo' ),
					__( 'Springoo Heading 4', 'springoo' ),
					__( 'Springoo Heading 5', 'springoo' ),
					__( 'Springoo Heading 6', 'springoo' ),
					__( 'Springoo Text', 'springoo' ),
				),
				'value' => array(
					array(
						'typography_font_family' => get_theme_mod( 'typography_heading_h1_font_family' ) === 'Default' ? get_theme_mod( 'typography_heading_font_family' ) : get_theme_mod( 'typography_heading_h1_font_family' ),
						'typography_font_weight' => get_theme_mod( 'typography_heading_h1_font_variant' ) ? get_theme_mod( 'typography_heading_h1_font_variant' ) : get_theme_mod( 'typography_heading_font_variant' ),
						'typography_typography'  => 'custom',
					),
					array(
						'typography_font_family' => get_theme_mod( 'typography_heading_h2_font_family' ) === 'Default' ? get_theme_mod( 'typography_heading_font_family' ) : get_theme_mod( 'typography_heading_h2_font_family' ),
						'typography_font_weight' => get_theme_mod( 'typography_heading_h2_font_variant' ) ? get_theme_mod( 'typography_heading_h2_font_variant' ) : get_theme_mod( 'typography_heading_font_variant' ),
						'typography_typography'  => 'custom',
					),
					array(
						'typography_font_family' => get_theme_mod( 'typography_heading_h3_font_family' ) === 'Default' ? get_theme_mod( 'typography_heading_font_family' ) : get_theme_mod( 'typography_heading_h3_font_family' ),
						'typography_font_weight' => get_theme_mod( 'typography_heading_h3_font_variant' ) ? get_theme_mod( 'typography_heading_h3_font_variant' ) : get_theme_mod( 'typography_heading_font_variant' ),
						'typography_typography'  => 'custom',
					),
					array(
						'typography_font_family' => get_theme_mod( 'typography_heading_h4_font_family' ) === 'Default' ? get_theme_mod( 'typography_heading_font_family' ) : get_theme_mod( 'typography_heading_h4_font_family' ),
						'typography_font_weight' => get_theme_mod( 'typography_heading_h4_font_variant' ) ? get_theme_mod( 'typography_heading_h4_font_variant' ) : get_theme_mod( 'typography_heading_font_variant' ),
						'typography_typography'  => 'custom',
					),
					array(
						'typography_font_family' => get_theme_mod( 'typography_heading_h5_font_family' ) === 'Default' ? get_theme_mod( 'typography_heading_font_family' ) : get_theme_mod( 'typography_heading_h5_font_family' ),
						'typography_font_weight' => get_theme_mod( 'typography_heading_h5_font_variant' ) ? get_theme_mod( 'typography_heading_h5_font_variant' ) : get_theme_mod( 'typography_heading_font_variant' ),
						'typography_typography'  => 'custom',
					),
					array(
						'typography_font_family' => get_theme_mod( 'typography_heading_h6_font_family' ) === 'Default' ? get_theme_mod( 'typography_heading_font_family' ) : get_theme_mod( 'typography_heading_h6_font_family' ),
						'typography_font_weight' => get_theme_mod( 'typography_heading_h6_font_variant' ) ? get_theme_mod( 'typography_heading_h6_font_variant' ) : get_theme_mod( 'typography_heading_font_variant' ),
						'typography_typography'  => 'custom',
					),
					array(
						'typography_font_family' => get_theme_mod( 'typography_global_font_family' ),
						'typography_font_weight' => get_theme_mod( 'typography_global_font_variant' ) ? get_theme_mod( 'typography_global_font_variant' ) : 'regular',
						'typography_typography'  => 'custom',
					),
				),
			];
		}

		/**
		 * Theme Typography for elementor
		 *
		 * @param $response
		 * @param $handler
		 * @param $request
		 *
		 * @return mixed
		 * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed
		 */
		public function elementor_add_theme_typography( $response, $handler, $request ) {
			$route = $request->get_route();
			if ( '/elementor/v1/globals' != $route ) {
				return $response;
			}

			$global_typo       = self::global_typography();
			$global_typo_value = $global_typo['value'];
			$data              = $response->get_data();
			$slugs             = $global_typo['id'];
			$labels            = $global_typo['title'];

			foreach ( $global_typo_value as $key => $typography ) {
				$id    = $slugs[ $key ] ? $slugs[ $key ] : '';
				$label = $labels[ $key ] ? $labels[ $key ] : '';

				if ( ! $id || ! $label || ! $typography ) {
					continue;
				}

				$data['typography'][ $id ] = array(
					'id'    => esc_attr( $id ),
					'title' => $label,
					'value' => $typography,
				);
			}

			$response->set_data( $data );

			return $response;
		}

		/**
		 * Display Typography in frontend
		 *
		 * @param $response
		 * @param $handler
		 * @param $request
		 *
		 * @return mixed|WP_Error|WP_HTTP_Response|WP_REST_Response
		 * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed
		 */
		public function display_global_typo_front_end( $response, $handler, $request ) {
			$route = $request->get_route();

			if ( 0 !== strpos( $route, '/elementor/v1/globals' ) ) {
				return $response;
			}

			$slug_map         = array();
			$global_typo      = self::global_typography();
			$typography_slugs = $global_typo['id'];

			foreach ( $typography_slugs as $key => $slug ) {
				$slug_map[ $slug ] = $key;
			}

			$rest_id = substr( $route, strrpos( $route, '/' ) + 1 );

			if ( ! in_array( $rest_id, array_keys( $slug_map ), true ) ) {
				return $response;
			}

			$typo = $global_typo['value'];
			return rest_ensure_response(
				array(
					'id'    => esc_attr( $rest_id ),
					'title' => esc_html( $slug_map[ $rest_id ] ),
					'value' => $typo[ $slug_map[ $rest_id ] ],
				)
			);
		}
	}

	new Springoo_Global_Typography();
}


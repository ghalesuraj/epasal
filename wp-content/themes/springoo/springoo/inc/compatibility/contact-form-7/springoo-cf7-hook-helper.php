<?php
/**
 * Contact Form 7 hook helper
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}


if ( ! function_exists('springoo_cf7_button') ) {
	function springoo_cf7_button() {
		wpcf7_add_form_tag('submit', 'springoo_cf7_button_handler');
	}
}

if ( ! function_exists('springoo_cf7_button_handler') ) {
	function springoo_cf7_button_handler( $tag ) {
		$tag   = new WPCF7_FormTag( $tag );
		$value = isset( $tag->values[0] ) ? $tag->values[0] : '';
		if ( empty( $value ) ) {
			$value = esc_html__( 'Send Message', 'springoo' );
		}
		$html = sprintf( '<span class="wpcf7-form-control-wrap wpcf7-submit-wrap"><button class="wpcf7-form-btn-control wpcf7-submit"><span class="has-spinner">%s</span></button></span>', $value );

		return $html;
	}
}

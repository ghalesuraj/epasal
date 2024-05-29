<?php
/**
 * Contact Form 7 Hooks
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

remove_action('wpcf7_init', 'wpcf7_add_form_tag_submit');
add_action('wpcf7_init', 'springoo_cf7_button');

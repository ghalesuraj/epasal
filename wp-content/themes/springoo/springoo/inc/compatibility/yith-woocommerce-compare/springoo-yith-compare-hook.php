<?php
/**
 * YITH compare hooks
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

add_filter( 'yith_woocompare_admin_tabs', 'springoo_yith_woocompare_admin_tab' );
add_filter( 'yith_woocompare_general_settings', 'springoo_yith_compare_position' );
add_filter( 'yith_woocompare_main_script_localize_array', 'springoo_yith_woocompare_localize_array' );

add_action( 'init', 'springoo_handle_yith_compare_hooks', 20 );


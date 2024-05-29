<?php
/**
 * Dokan Hooks
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * ----------------------------------------------------------------------
 * Declaration of all dokan hook
 * ----------------------------------------------------------------------*/
add_filter( 'dokan_store_listing_per_page', 'springoo_store_per_page' );
add_filter( 'dokan_store_listing_per_page', 'springoo_store_columns' );
add_filter( 'dokan_store_list_args', 'springoo_store_columns' );
add_action( 'dokan_store_profile_frame_after', 'springoo_store_products_title', 5 );
add_filter( 'dokan_profile_social_fields', 'springoo_get_social_profile_fields', 10 );
add_filter( 'body_class', 'springoo_dokan_pages' );

add_action('wp_ajax_springoo_dokan_vendor_list', 'springoo_dokan_vendor_list');
add_action('wp_ajax_nopriv_springoo_dokan_vendor_list', 'springoo_dokan_vendor_list'); // For non-logged in users


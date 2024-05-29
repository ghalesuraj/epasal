<?php
/**
 * Sets up the default filters and actions for most of the ABSP hooks.
 *
 * Not all of the default hooks are found in default-filters.php
 *
 * @package AyyashAddons
 * @author Kudratullah <mhamudul.hk@gmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

add_filter( 'plugins_api_result', 'ayyash_addons_plugin_api_results_dependency_filter', 10, 3 );

add_filter( 'woocommerce_add_to_cart_fragments', 'ayyash_addons_mini_cart_fragments' );

add_filter( 'yith_woocompare_main_script_localize_array', 'ayyash_addons_yith_woocompare_localize_array', 50 );

//For Portfolio Like
add_action('wp_ajax_update_likes', 'update_likes');
add_action('wp_ajax_nopriv_update_likes', 'update_likes');



// End of file default-filters.php.

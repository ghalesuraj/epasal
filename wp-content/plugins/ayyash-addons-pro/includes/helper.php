<?php
/**
 *
 *
 * @package Package
 * @author Name <email>
 * @version
 * @since
 * @license
 */

use AyyashAddonsPro\Ayyash_Addons_Pro_Services;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Plugin License Data
 *
 * @return array
 */
function ayyash_addons_pro_license_data() {
	return Ayyash_Addons_Pro_Services::get_instance()->get_license_data();
}

function get_wc_attr_filter_dependency() {


	$attributes = array(
		'next'           => $_POST['next'],
		'selected'       => $_POST['attr'],
		'selected_value' => $_POST['value'],
	);
	$attributes = array_map( 'sanitize_text_field', $attributes );

	$args = array(
		'post_type' => 'product',
		'tax_query' => array(
			array(
				'taxonomy' => 'pa_' . $attributes['selected'],
				'field'    => 'slug',
				'terms'    => $attributes['selected_value'],
			),
		),
	);

	$query          = new WP_Query( $args );
	$query_post_ids = wp_list_pluck( $query->posts, 'ID' );
	$options        = array();
	foreach ( $query_post_ids as $product_id ) {
		$terms = get_the_terms( $product_id, 'pa_' . $attributes['next'] );
//		$terms = wp_list_pluck( $terms, 'slug' );
		if ( $terms ) {
			foreach ( $terms as $term ) {
				$options[] = array(
					'value' => $term->slug,
					'text'  => $term->name,
				);
			}
		}
		$options = array_unique( $options, SORT_REGULAR );
	}
	$options = wp_json_encode( $options );
	wp_send_json_success( $options );
}

add_action( 'wp_ajax_get_wc_attr_filter_dependency', 'get_wc_attr_filter_dependency' );
add_action( 'wp_ajax_nopriv_get_wc_attr_filter_dependency', 'get_wc_attr_filter_dependency' );


// End of file helper.php.

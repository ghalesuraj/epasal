<?php

function ayyash_addons_get_terms_callback() {
	if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), AYYASH_ADDONS_FILE ) ) {



		if ( isset( $_POST['taxonomy'] ) && ! empty( $_POST['taxonomy'] ) ) {
			$taxonomy = sanitize_text_field( $_POST['taxonomy'] );

			$terms = get_terms( [
				'taxonomy' => $taxonomy,
				'name'     => '',
				'slug'     => '',
			] );

			wp_send_json_success( [ 'message' => $terms ] );
			die();
		}

		wp_send_json_error( [ 'message' => esc_html__( 'Invalid taxonomy.', 'ayyash-addons' ) ] );
		die();

	}

	wp_send_json_error( [ 'message' => esc_html__( 'Nonce not verified.', 'ayyash-addons' ) ] );
	die();
}

function ayyash_addons_get_post_type_taxonomies() {
	check_ajax_referer( AYYASH_ADDONS_FILE, 'nonce' );

	$data = [];
	if ( isset( $_REQUEST['post_type'] ) && post_type_exists( $_REQUEST['post_type'] ) ) { // phpcs:ignore
		$taxonomies = get_object_taxonomies( sanitize_text_field( $_REQUEST['post_type'] ), 'objects' );
		foreach ( $taxonomies as $taxonomy ) {
			$data[ $taxonomy->name ] = $taxonomy->label;
		}
	}

	wp_send_json_success( $data );
}
// phpcs:disable
function ayyash_addons_get_taxonomy_terms() {
	check_ajax_referer( AYYASH_ADDONS_FILE, 'nonce' );

	$data = [];
	if ( isset( $_REQUEST['taxonomy'] ) && ! empty( $_REQUEST['taxonomy'] ) ) {

		$taxonomies = ayyash_addons_clean( $_REQUEST['taxonomy'] );

		if ( ! is_array( $taxonomies ) ) {
			$taxonomies = [ $taxonomies ];
		}

		foreach ( $taxonomies as $taxonomy ) {
			$taxonomy = get_taxonomy( $taxonomy );
			if ( ! $taxonomy ) {
				continue;
			}

			$terms = get_terms( [
				'taxonomy'   => $taxonomy->name,
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => true,
			] );

			$term_data = [];
			if ( is_wp_error( $terms ) || empty( $terms ) ) {
				$term_data[] = [
					'label' => esc_html__( 'No Terms', 'ayyash-addons' ),
					'value' => '',
				];
			} else {
				foreach ( $terms as $term ) {
					$term_data[] = [
						'label' => esc_html( $term->name ),
						'value' => $term->slug,
					];
				}
			}

			$data[] = [
				'slug'  => $taxonomy->name,
				'label' => esc_html( $taxonomy->label ),
				'terms' => $term_data,
			];
		}
	}

	wp_send_json_success( $data );
}
// phpcs:enable
add_action( 'wp_ajax_ayyash_addons_get_terms', 'ayyash_addons_get_terms_callback' );
add_action( 'wp_ajax_ayyash_addons_get_post_type_taxonomies', 'ayyash_addons_get_post_type_taxonomies' );
add_action( 'wp_ajax_ayyash_addons_get_taxonomy_terms', 'ayyash_addons_get_taxonomy_terms' );

function ayyash_addons_search_product() {
	check_ajax_referer( 'ayyash-addons-frontend', 'nonce' );

	if ( function_exists( 'wc_get_products' ) ) {
		$keywords = ! empty( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : false;
		$category = ! empty( $_REQUEST['product_cat'] ) ? sanitize_text_field( $_REQUEST['product_cat'] ) : [];

		if ( $keywords && strlen( $keywords ) >= 3 ) {
			$products = wc_get_products( [
				's'        => $keywords,
				'category' => json_decode( stripslashes( $category ) ),
			] );

			if ( empty( $products ) ) {
				wp_send_json_error( __( 'No Match Found.', 'ayyash-addons' ) );
			}

			$data = [];
			/**
			 * @type WC_Product[] $products
			 */
			foreach ( $products as $product ) {

				$_data = [
					'id'          => $product->get_id(),
					'title'       => $product->get_title(),
					'link'        => get_permalink( $product->get_id() ),
					'thumbnail'   => $product->get_image( 'thumbnail' ),
					'price'       => $product->get_price_html(),
					'cart_btn'    => $product->add_to_cart_url(),
					'type'        => $product->get_type(),
					'rating'      => null,
					'product_cat' => json_decode( stripslashes( $category ) ),
				];

				if ( wc_review_ratings_enabled() ) {
					$_data['rating'] = wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() );
				}

				$data[] = $_data;
			}

			wp_send_json_success( $data );
		}

		wp_send_json_error( __( 'Please type Minimum 3 character!', 'ayyash-addons' ) );
	} else {
		wp_send_json_error( __( 'WooCommerce is not installed/activated.', 'ayyash-addons' ) );
	}

	wp_send_json_error( __( 'Invalid Request', 'ayyash-addons' ) );
}

add_action( 'wp_ajax_ayyash_addons_search_product', 'ayyash_addons_search_product' );
add_action( 'wp_ajax_nopriv_ayyash_addons_search_product', 'ayyash_addons_search_product' );

// End of file ajax-handler.php.

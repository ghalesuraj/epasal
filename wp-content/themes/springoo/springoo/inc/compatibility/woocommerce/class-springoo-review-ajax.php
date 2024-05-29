<?php
/**
 * Springoo Review  Ajax.
 *
 * @package Springoo
 * @author ThemeRox
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! class_exists( 'Springoo_Review_Ajax' ) ) {

	class Springoo_Review_Ajax {

		public function __construct() {
			add_action( 'wp_ajax_springoo_sort_reviews', [ $this, 'sort_reviews' ] );
			add_action( 'wp_ajax_nopriv_springoo_sort_reviews', [ $this, 'sort_reviews' ] );
			add_action( 'wp_ajax_springoo_filter_reviews', [ $this, 'filter_reviews' ] );
			add_action( 'wp_ajax_nopriv_springoo_filter_reviews', [ $this, 'filter_reviews' ] );

			add_action( 'wp_ajax_springoo_review_feedback', [ $this, 'review_feedback_registered' ] );
			add_action( 'wp_ajax_nopriv_springoo_review_feedback', [ $this, 'review_feedback_registered' ] );
		}

		/**
		 * Sort Reviews
		 *
		 * @return void
		 */
		public static function sort_reviews() {

			if ( ! isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'springoo-nonce' ) ) {
				exit( 'No naughty business please' );
			}
			if ( empty( $_POST['productID'] ) || ! isset( $_POST['sort'] ) ) {
				exit();
			}

			$product_id = sanitize_text_field( $_POST['productID'] );
			$sort       = sanitize_text_field( $_POST['sort'] );

			$reviews_html = wp_list_comments( apply_filters(
				'woocommerce_product_review_list_args',
				array(
					'callback'          => 'woocommerce_comments',
					'reverse_top_level' => false,
					'page'              => 1,
					'echo'              => false,
				) ),
				self::get_reviews( $product_id, $sort )
			);

			wp_send_json( $reviews_html );
		}

		/**
		 * Filter Reviews
		 *
		 * @return void
		 */
		public static function filter_reviews() {

			if ( ! isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'springoo-nonce' ) ) {
				exit( 'No naughty business please' );
			}
			if ( empty( $_POST['productID'] ) || ! isset( $_POST['filter'] ) ) {
				exit();
			}

			$product_id = sanitize_text_field( $_POST['productID'] );
			$filter     = sanitize_text_field( $_POST['filter'] );

			$reviews_html = wp_list_comments( apply_filters(
				'woocommerce_product_review_list_args',
				array(
					'callback'          => 'woocommerce_comments',
					'reverse_top_level' => false,
					'page'              => 1,
					'echo'              => false,
				) ),
				self::get_reviews( $product_id, false, $filter )
			);

			wp_send_json( $reviews_html );
		}

		/**
		 * Get Single Product Reviews
		 *
		 * @return array | null
		 */
		public static function get_reviews( $product_id, $sort = false, $filter = false ) {

			$args = [
				'post_id' => $product_id,
				'status'  => 'approve',
				'orderby' => 'comment_date_gmt',
				'order'   => 'ASC',
			];

			//recent
			if ( $sort ) {
				if ( 'recent' === $sort ) {
					$args['orderby'] = 'comment_date_gmt';
					$args['order']   = 'DESC';
				}

				//rating low and high
				if ( 'ratinglow' === $sort || 'ratinghigh' === $sort ) {
					$args['meta_query'] = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						[
							'key'     => 'rating',
							'type'    => 'NUMERIC',
							'compare' => 'EXISTS',
						],
					];
					$args['orderby']    = [
						'meta_value_num',
						'comment_date_gmt',
					];
					$args['order']      = 'ratinglow' === $sort ? 'ASC' : 'DESC';
				}
			}

			//filter
			if ( $filter ) {
				$args['meta_query']['relation'] = 'AND';
				if ( 1 <= $filter && 5 >= $filter ) {
					$args['meta_query'][] = array(
						'key'     => 'rating',
						'compare' => '=',
						'value'   => $filter,
						'type'    => 'NUMERIC',
					);
				} else {
					$args['meta_query'][] = array(
						'key'     => 'rating',
						'compare' => 'EXISTS',
						'type'    => 'NUMERIC',
					);
				}
			}

			return get_comments( $args );
		}

		/**
		 * Product Reviews Feedback Registered
		 *
		 * @return void
		 */
		public static function review_feedback_registered() {

			if ( ! isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'springoo-nonce' ) ) {
				exit( 'No naughty business please' );
			}

			if ( empty( $_POST['reviewID'] ) ) {
				exit();
			}

			$review_id         = intval( $_POST['reviewID'] );
			$positive_feedback = isset(  $_POST['feedbackYes'] ) ? intval( sanitize_text_field( $_POST['feedbackYes'] ) ) : '';
			$negetive_feedback = isset(  $_POST['feedbackNo'] ) ? intval( sanitize_text_field( $_POST['feedbackNo'] ) ) : '';

			$registered_feedback_yes_users = get_comment_meta( $review_id, 'springoo_review_feedback_yes', true );
			$registered_feedback_no_users  = get_comment_meta( $review_id, 'springoo_review_feedback_no', true );
			$current_user                  = get_current_user_id();

			// check if this registered user has already feedback yes user this review
			if ( ! empty( $registered_feedback_yes_users ) ) {
				if ( ! is_array( $registered_feedback_yes_users ) ) {
					$registered_feedback_yes_users = array();
				}
			} else {
				$registered_feedback_yes_users = array();
			}

			// check if this registered user has already feedback no user this review
			if ( ! empty( $registered_feedback_no_users ) ) {
				if ( ! is_array( $registered_feedback_no_users ) ) {
					$registered_feedback_no_users = array();
				}
			} else {
				$registered_feedback_no_users = array();
			}

			//feedback yes
			if ( ! empty( $positive_feedback ) ) {

				if ( in_array( $current_user, $registered_feedback_yes_users ) ) {
					return;
				}

				//remove feedback unlike user
				if ( in_array( $current_user, $registered_feedback_no_users ) ) {
					if ( ( $key = array_search( $current_user, $registered_feedback_no_users ) ) !== false ) {
						unset( $registered_feedback_no_users[ $key ] );
					}
					update_comment_meta( $review_id, 'springoo_review_feedback_no', $registered_feedback_no_users );
				}


				$registered_feedback_yes_users[] = $current_user;
				update_comment_meta( $review_id, 'springoo_review_feedback_yes', $registered_feedback_yes_users );
			}

			//feedback no
			if ( ! empty( $negetive_feedback ) ) {
				if ( in_array( $current_user, $registered_feedback_no_users ) ) {
					return;
				}

				//remove feedback like user
				if ( in_array( $current_user, $registered_feedback_yes_users ) ) {

					if ( ( $key = array_search( $current_user, $registered_feedback_yes_users ) ) !== false ) {
						unset( $registered_feedback_yes_users[ $key ] );
					}
					update_comment_meta( $review_id, 'springoo_review_feedback_yes', $registered_feedback_yes_users );
				}

				$registered_feedback_no_users[] = $current_user;
				update_comment_meta( $review_id, 'springoo_review_feedback_no', $registered_feedback_no_users );
			}

			wp_send_json( [ $registered_feedback_yes_users, $registered_feedback_no_users ] );
		}

	}

	new Springoo_Review_Ajax();
}

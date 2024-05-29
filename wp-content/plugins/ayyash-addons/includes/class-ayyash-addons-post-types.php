<?php
/**
 * Initialize AyyashAddonsPostTypes
 *
 * @version 1.0.0
 * @package AyyashAddons
 */

namespace AyyashAddons\AyyashAddonsPostTypes;

class Ayyash_Addons_Post_Types {

	/**
	 * Initialization
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_post_types' ], 5 );
		add_action( 'init', [ __CLASS__, 'register_taxonomies' ], 5 );
		add_action( 'ayyash_addons/flush_rewrite_rules', [ __CLASS__, 'flush_rewrite_rules' ] );
	}


	/**
	 *  Register FAQ Post Type
	 */
	public static function register_post_types() {
		if ( ! is_blog_installed() ) {
			return;
		}

		do_action( 'ayyash_addons/before/register_post_type' );

		if ( ! post_type_exists( 'faq' ) ) {
			$args = [
				'public'       => true,
				'label'        => __( 'FAQs', 'ayyash-addons' ),
				'show_in_rest' => true,
				'supports'     => [ 'title', 'editor', 'thumbnail' ],
				'taxonomies'   => [ 'faq_category', 'faq_tag' ],
			];
			register_post_type( 'faq', $args );
		}

		if ( ! post_type_exists( 'portfolio' ) ) {
			$args = [
				'public'       => true,
				'label'        => __( 'Portfolios', 'ayyash-addons' ),
				'show_in_rest' => true,
				'supports'     => [ 'title', 'editor', 'thumbnail' ],
				'taxonomies'   => [ 'portfolio_category' ],
			];
			register_post_type( 'portfolio', $args );
		}

		do_action( 'ayyash_addons/after/register_post_type' );
	}

	/**
	 *  Register FAQ Taxonomies
	 */
	public static function register_taxonomies() {
		if ( ! is_blog_installed() ) {
			return;
		}

		do_action('ayyash_addons/before/register_taxonomies');

		if ( ! taxonomy_exists( 'portfolio_cat' ) ) {

			$args = [
				'hierarchical' => true,
				'labels'       => [
					'name'          => _x( 'Portfolio Categories', 'taxonomy general name', 'ayyash-addons' ),
					'singular_name' => _x( 'Portfolio Categories', 'taxonomy singular name', 'ayyash-addons' ),
				],
				'show_in_rest' => true,
			];

			register_taxonomy( 'portfolio_cat', 'portfolio', $args );
		}

		if ( ! taxonomy_exists( 'faq_category' ) ) {

			$args = [
				'hierarchical' => true,
				'labels'       => [
					'name'          => _x( 'FAQs Categories', 'taxonomy general name', 'ayyash-addons' ),
					'singular_name' => _x( 'FAQs Categories', 'taxonomy singular name', 'ayyash-addons' ),
				],
				'show_in_rest' => true,
			];

			register_taxonomy( 'faq_category', 'faq', $args );
		}

		if ( ! taxonomy_exists( 'faq_tag' ) ) {
			$args = [
				'hierarchical'          => false,
				'labels'                => [
					'name'          => _x( 'FAQs Tags', 'taxonomy general name', 'ayyash-addons' ),
					'singular_name' => _x( 'FAQs Tag', 'taxonomy singular name', 'ayyash-addons' ),
				],
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'show_in_rest'          => true,
			];

			register_taxonomy( 'faq_tag', 'faq', $args );
		}

		do_action('ayyash_addons/after/register_taxonomies');
	}

	/**
	 * Flush Rewrite Rules
	 */
	public static function flush_rewrite_rules() {
		flush_rewrite_rules(); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules
	}

}

Ayyash_Addons_Post_Types::init();

// End of file class-ayyash-addons-post-types.php

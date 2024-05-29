<?php
/**
 * Ayyash Addons Query Builder
 *
 */

namespace AyyashAddons\Controls;

use AyyashAddons\Plugin;
use Elementor\Control_Base_Multiple;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Ayyash_Addons_Query_Builder extends Control_Base_Multiple {

	const TYPE = 'ayyash_addons_query_builder';


	/**
	 * Check if ayyash_addons has pro version.
	 *
	 *
	 *
	 * @access protected
	 * @static
	 *
	 * @var string
	 */
	protected static $has_pro = false;

	protected static $post_types;

	/**
	 * Check if ayyash_addons has pro version.
	 *
	 *
	 *
	 * @access protected
	 * @static
	 *
	 * @var string
	 */
	protected static $taxonomies = [];
	/**
	 *
	 * All category names.
	 *
	 * @access protected
	 * @static
	 *
	 * @var array.
	 */
	protected static $category_names = [];

	/**
	 *
	 * All category names.
	 *
	 * @access protected
	 * @static
	 *
	 * @var array.
	 */
	protected static $tag_names = [];

	/**
	 *
	 * All author names.
	 *
	 * @access protected
	 * @static
	 *
	 * @var array.
	 */
	protected static $author_names = [];

	protected static $taxonomy = 'category';

	protected static $post_type = 'post';

	public function get_type() {
		return self::TYPE;
	}

	/**
	 * Constructor function
	 */
	public function __construct() {
		parent::__construct();
		self::$has_pro = ayyash_addons_has_pro();
	}

	public static function set_post_type( $post ) {
		self::$post_type = $post;
	}

	public static function get_post_type() {
		return self::$post_type;
	}

	/**
	 *
	 * @return string[]
	 */
	private function get_post_types() {
		global $wp_post_types;
		foreach ( $wp_post_types as $post_type => $object ) {
			if ( $object->show_in_nav_menus ) {
				self::$post_types[ $post_type ] = $object->label;
			}
		}

		return apply_filters( 'ayyash_addons_get_post_types', self::$post_types );
	}


	/**
	 * @return array
	 */
	private function get_authors( $args = [] ) {
		if ( empty( $args ) ) {
			$args = [
				'role__not_in' => apply_filters( 'ayyash_addons_exclude_roles', [ 'subscriber', 'customer' ] ),
				'orderby'      => 'display_name',
				'order'        => 'ASC',
			];
		}

		$authors = get_users( $args );

		foreach ( $authors as $author ) {
			self::$author_names[ $author->ID ] = $author->display_name;
		}

		return apply_filters( 'ayyash_addons/controller/get_authors', self::$author_names );
	}


	protected function get_default_settings(): array {

		$default_settings = [
			'is_pro'            => ayyash_addons_has_pro(),
			'post_types'        => $this->get_post_types(),
			'authors'           => $this->get_authors(),
			'disable_post_type' => false,
			'orderby_defaults'  => [
				'ID'            => 'ID',
				'title'         => __( 'Title', 'ayyash-addons' ),
				'date'          => __( 'Date', 'ayyash-addons' ),
				'author'        => __( 'Author', 'ayyash-addons' ),
				'name'          => __( 'Slug', 'ayyash-addons' ),
				'type'          => __( 'Post Type', 'ayyash-addons' ),
				'modified'      => __( 'Last modified', 'ayyash-addons' ),
				'rand'          => __( 'Random order', 'ayyash-addons' ),
				'comment_count' => __( 'Random order', 'ayyash-addons' ),
			],
			'post_statuses'     => [
				'publish' => __( 'Published', 'ayyash-addons' ),
				'inherit' => __( 'Inherit', 'ayyash-addons' ),
				'private' => __( 'Private', 'ayyash-addons' ),
				'pending' => __( 'Pending', 'ayyash-addons' ),
			],
			'sort_orders'       => [
				'ASC'  => __( 'ASC', 'ayyash-addons' ),
				'DESC' => __( 'DESC', 'ayyash-addons' ),
			],
			/*'meta_keys'        => $keys,*/
			'taxonomies'        => null,
			'terms_data'        => null,
		];

		$default_settings = array_merge( $default_settings, self::get_default_query() );

		return [
			'settings' => apply_filters( 'ayyash_addons/controller/default_settings', $default_settings ),
		];
	}

	public static function get_default_query() {
		return [
			's'                   => '',
			'post_type'           => self::get_post_type(),
			'post_status'         => [ 'publish' ],
			'taxonomy'            => [],
			// terms...
			'post__in'            => [],
			'ignore_sticky_posts' => false,
			'post__not_in'        => [],
			'posts_per_page'      => get_option( 'posts_per_page', 12 ),
			'offset'              => 0,
			'author'              => [ 'any' ],
			'orderby'             => 'date',
			'order'               => 'DESC',
			'date_query_after'    => [], // start
			'date_query_before'   => [], // end
			'taxonomy_relation'   => 'AND',
			/*'meta_query'    => [
				[]
			],*/
		];
	}

	public static function build_query( $args ) {
		$args = wp_parse_args( $args, self::get_default_query() );

		/*$s = [
			's' => '',
			'post_type' => 'post',
			'post_status' => [ 'publish' ],
			'post__in' => '',
			'post__not_in' => '',
			'posts_per_page' => '',
			'offset',
			'author',
			'orderby',
			'order',
			'date_query_after',
			'date_query_before',
		];*/


		if ( ! empty( $args['taxonomy'] ) ) {
			if ( ! is_array( $args['taxonomy'] ) ) {
				$args['taxonomy'] = [ $args['taxonomy'] ];
			}

			$relation = ! empty( $args['taxonomy_relation'] ) && in_array( $args['taxonomy_relation'], [ 'AND', 'OR' ] ) ? $args['taxonomy_relation'] : 'AND';

			$tax_query = [ 'relation' => $relation ];

			foreach ( $args['taxonomy'] as $tax ) {
				if ( isset( $args[ 'terms_' . $tax ] ) ) {
					$relation = 'term_relation_' . $tax;
					$term_q = [
						'taxonomy' => $tax,
						'terms'    => $args[ 'terms_' . $tax ],
						'field'    => 'slug',
					];
					if ( ! empty( $args[ $relation ] ) ) {
						$relation = in_array( $relation, [ 'AND', 'OR' ] ) ? $relation : 'AND';
						$tax_query[] = [ 'relation' => $relation ] + [ $term_q ];
					} else {
						$tax_query[] = $term_q;
					}
				}
			}

			$args['tax_query'] = $tax_query; //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}

		$date_after  = false;
		$date_before = false;

		if ( isset( $args['date_query_after'] ) && $args['date_query_after'] ) {
			$date_after = $args['date_query_after'] . ' 00:00:00';
		}

		if ( isset( $args['date_query_before'] ) && $args['date_query_before'] ) {
			$date_before = $args['date_query_before'] . ' 23:59:59';
		}

		if ( $date_after ) {
			if ( $date_before ) {
				$args['date_query'] = [
					'after'  => $date_after,
					'before' => $date_before,
				];
			} else {
				$args['date_query'] = [
					'year'  => gmdate( 'Y', $date_after ),
					'month' => gmdate( 'm', $date_after ),
					'day'   => gmdate( 'd', $date_after ),
				];
			}
		}

		if ( isset( $args['author'] ) && $args['author'] ) {
			if ( is_array( $args['author'] ) ) {
				$args['author'] = array_flip( $args['author'] );
				if ( isset( $args['author']['any'] ) ) {
					unset( $args['author'] );
				} else {
					$args['author'] = array_flip( $args['author'] );
					if ( count( $args['author'] ) === 1 ) {
						$args['author'] = $args['author'][0];
					} else {
						$args['author__in'] = self::id_helper( $args['author'] );
						unset( $args['author'] );
					}
				}
			} else {
				if ( 'any' === $args['author'] ) {
					unset( $args['author'] );
				}
			}
		}

		if ( isset( $args['post__in'] ) && $args['post__in'] ) {
			$args['post__in'] = self::id_helper( $args['post__in'] );
			if ( ! $args['post__in'] ) {
				unset( $args['post__in'] );
			}
		}

		if ( isset( $args['post__not_in'] ) && $args['post__not_in'] ) {
			$args['post__not_in'] = self::id_helper( $args['post__not_in'] );
			if ( ! $args['post__not_in'] ) {
				unset( $args['post__not_in'] );
			}
		}

		return $args;
	}

	protected static function id_helper( $input ) {
		if ( ! is_array( $input ) ) {
			$input = array_map( 'absint', explode( ',', $input ) );
		}

		$ids = array_filter( $input );

		if ( empty( $ids ) ) {
			return false;
		}

		return array_unique( $ids );
	}

	public function enqueue() {

		Plugin::enqueue_script( 'sweetalert2', 'assets/dist/js/libraries/sweetalert2.all', [], AYYASH_ADDONS_VERSION, true );
		Plugin::enqueue_style( 'elementor-select2' );
		Plugin::enqueue_script( 'elementor-select2' );
		Plugin::enqueue_script( 'ayyash_addons-select2', '/assets/dist/js/AyyashAddonsSelect2', [ 'jquery-elementor-select2' ], AYYASH_ADDONS_VERSION, true );
		Plugin::enqueue_script( 'ayyash_addons-query-builder', '/assets/dist/js/query-builder', [
			'jquery',
			'sweetalert2',
			'ayyash_addons-select2',
			'wp-util',
		], AYYASH_ADDONS_VERSION, true );

		wp_localize_script( 'ayyash_addons-query-builder', 'AyyashAddonsQueryBuilderConfig', [
			'title'       => __( 'Query Builder', 'ayyash-addons' ),
			'select_tax'  => __( 'Select Taxonomies', 'ayyash-addons' ),
			'select_term' => __( 'Select Terms', 'ayyash-addons' ),
			'nonce'       => wp_create_nonce( AYYASH_ADDONS_FILE ),
			'ajaxurl'     => admin_url( 'admin-ajax.php' ),
		] );
	}

	public function content_template() {
		$_uid = $this->get_control_uid();
		?>
		<# window.query_data = data; #>
		<# var settings = data.settings; #>
		<# var values = data.controlValue; #>

		<style>
			.elementor-control-type-ayyash_addons_query_builder .elementor-control-content.elementor-label-inline {
				padding: 3px 0;
			}
		</style>
		<# if( ! settings.is_pro ) { #>
		<style>
			.elementor-control-type-ayyash_addons_query_builder .call-to-action {
				padding: 20px 0 0 0;
			}

			.elementor-control-type-ayyash_addons_query_builder .call-to-action .elementor-control-field {
				font-style: italic;
				display: block;
				color: black;
				border-top: 1px solid #dbdbdb;
				padding-top: 10px;
			}

			.elementor-control-type-ayyash_addons_query_builder .call-to-action a {
				color: #0095ff;
			}
		</style>
		<# } #>

		<!-- search  -->
		<div class="elementor-control elementor-control-content elementor-group-control-s elementor-label-inline">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-s" class="elementor-control-title"><?php esc_html_e( 'Search', 'ayyash-addons' ); ?></label>
					<div class="elementor-control-input-wrapper elementor-control-unit-5">
						<input name="s" id="<?php echo esc_attr( $_uid ); ?>-s" type="text" data-setting="s" placeholder="<?php esc_attr__( 'Search...', 'ayyash-addons' ); ?>">
					</div>
				</div>
				<div class="elementor-control-field-description"><?php esc_html_e( 'Search post', 'ayyash-addons' ) ?></div>
			</div>
		</div>

		<!-- post type -->
		<# if ( values.disable_post_type ) { #>
		<input type="hidden" name="post_type" data-setting="post_type" value="{{ values.post_type }}" id="<?php echo esc_attr( $_uid ); ?>-post-type">
		<# } else { #>
		<div class="elementor-control elementor-control-separator-default elementor-control-content elementor-group-control-post-type elementor-label-inline">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-post-type" class="elementor-control-title"><?php esc_html_e( 'Source', 'ayyash-addons' ); ?></label>
					<div class="elementor-control-input-wrapper elementor-control-unit-5">
						<select name="post_type" data-setting="post_type" id="<?php echo esc_attr( $_uid ); ?>-post-type">
							<option value="" disabled><?php esc_html_e( 'Select Source', 'ayyash-addons' ); ?></option>
							<# _.each( settings.post_types, function( option_title, option_value ) { #>
								<# var selected = values.post_type === option_value ? ' selected' : ''; #>
								<option value="{{ option_value }}"{{ selected }}>{{{ option_title }}}</option>
							<# } ); #>
						</select>
					</div>
				</div>
			</div>
		</div>
		<# } #>

		<!-- post status -->
		<div class="elementor-control elementor-control-separator-default elementor-control-content elementor-control-post_status elementor-label-inline">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-post_status" class="elementor-control-title"><?php esc_html_e( 'Status', 'ayyash-addons' ); ?></label>
					<div class="elementor-control-input-wrapper elementor-control-unit-5">
						<select class="elementor-select2" type="select2" data-setting="post_status" name="post_status" id="<?php echo esc_attr( $_uid ); ?>-post_status" <?php echo self::$has_pro ? 'multiple' : '' ?> >
							<option value="" disabled><?php esc_html_e( 'Select Post Status', 'ayyash-addons' ); ?></option>
							<# _.each( settings.post_statuses, function( option_title, option_value ) { #>
							<option value="{{ option_value }}">{{{ option_title }}}</option>
							<# } ); #>
						</select>
					</div>
				</div>
			</div>
		</div>

		<!-- taxonomy -->
		<div class="elementor-control elementor-control-separator-default elementor-control-content elementor-control-taxonomy elementor-label-inline">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-taxonomy" class="elementor-control-title"><?php esc_html_e( 'Taxonomies', 'ayyash-addons' ); ?></label>
					<div class="elementor-control-input-wrapper elementor-control-unit-5">
						<select class="elementor-select2" type="select2" id="<?php echo esc_attr( $_uid ); ?>-taxonomy" name="taxonomy" data-setting="taxonomy" <?php echo self::$has_pro ? 'multiple' : '' ?>>
							<option value="0" disabled><?php esc_html_e( 'Select Taxonomies', 'ayyash-addons' ); ?></option>
							<# _.each( settings.taxonomies, function( option_title, option_value ) { #>
							<option value="{{ option_value }}">{{{ option_title }}}</option>
							<# } ); #>
						</select>
					</div>
				</div>
				<div class="elementor-control-field-description"><?php esc_html_e( 'Select Taxonomy', 'ayyash-addons' ) ?></div>
			</div>
		</div>

		<!-- relation -->

		<# if ( values.taxonomy && Array.isArray(values.taxonomy) && values.taxonomy.length > 1) { #>
		<div class="elementor-control  elementor-control-content elementor-control-taxonomy_relation elementor-label-inline elementor-control-separator-before">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-taxonomy_relation" class="elementor-control-title"><?php esc_html_e( 'Taxonomy Relation', 'ayyash-addons') ?></label>
					<div class="elementor-control-input-wrapper elementor-control-unit-5">
						<select data-setting="taxonomy_relation" name="taxonomy_relation" id="<?php echo esc_attr( $_uid ); ?>-taxonomy_relation">
							<option value="" disabled><?php esc_html_e( 'Select Taxonomy Relation', 'ayyash-addons' ); ?></option>
							<# _.each( ['AND', 'OR'], function( option_title, option_value ) { #>
							<# var selected = values.taxonomy_relation && values.taxonomy_relation === option_value ? 'selected' : '';
							#>
							<option value="{{ option_title }}" {{selected}}>{{{ option_title }}}</option>
							<# } ); #>
						</select>
					</div>
				</div>
			</div>
		</div>
		<# } #>

		<!-- terms -->

		<# if ( settings.terms_data && settings.terms_data.length ) { #>
		<# _.each( settings.terms_data, function( term_data ) { #>
		<div class="elementor-control elementor-control-separator-default elementor-control-content elementor-control-terms elementor-label-inline">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-{{ term_data.slug }}-terms" class="elementor-control-title">{{ term_data.label }}</label>
					<div class="elementor-control-input-wrapper elementor-control-unit-5">
						<select class="elementor-select2" type="select2" name="{{ term_data.slug }}-terms" data-setting="terms_{{ term_data.slug }}" id="<?php echo esc_attr( $_uid ); ?>-{{ term_data.slug }}-terms" multiple>
							<option value="0" disabled><?php esc_html_e( 'Select Term', 'ayyash-addons' ); ?></option>
							<# _.each( term_data.terms, function( term ) { #>
							<# var selected = values['terms_'+term_data.slug] && values['terms_'+term_data.slug].includes( term.value ) ? 'selected' : ''; #>
							<option value="{{ term.value }}" {{selected}}>{{{ term.label }}}</option>
							<# } ); #>
						</select>

						<div class="term-relation">
							<label for="<?php echo esc_attr( $_uid ); ?>-{{ term_data.slug }}-relation" class="elementor-control-title"><?php esc_html_e( 'Term Relation', 'ayyash-addons' ); ?></label>
							<div class="elementor-control-input-wrapper elementor-control-unit-5">
								<select name="{{ term_data.slug }}-relation" data-setting="term_relation_{{ term_data.slug }}" id="<?php echo esc_attr( $_uid ); ?>-{{ term_data.slug }}-relation">
									<option value="" disabled><?php esc_html_e( 'Select Term Relation', 'ayyash-addons' ); ?></option>
									<# _.each( ['AND', 'OR'], function( option_title, option_value ) { #>
									<# var selected = values['term_relation_'+term_data.slug] && values['term_relation_'+term_data.slug].includes( term.value ) ? 'selected' : ''; #>
									<option value="{{ option_title }}" {{selected}}>{{{ option_title }}}</option>
									<# } ); #>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<# } ); #>
		<# } #>
		<!-- post in -->
		<div class="elementor-control elementor-control-separator-default elementor-control-content elementor-control-post__in elementor-label-inline">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-post__in" class="elementor-control-title"><?php esc_html_e( 'Post By ID', 'ayyash-addons' ); ?></label>
					<div class="elementor-control-input-wrapper elementor-control-unit-5 elementor-control-dynamic-switcher-wrapper">
						<input name="post__in" id="<?php echo esc_attr( $_uid ); ?>-post__in" value="{{ settings.post__in }}" type="text" data-setting="post__in" placeholder="1,2,3,4">
					</div>
				</div>
				<div class="elementor-control-field-description"><?php esc_html_e( 'Paste ID separated with comma.', 'ayyash-addons' ); ?></div>
			</div>
		</div>
		<!-- post not in -->
		<div class="elementor-control elementor-control-separator-default elementor-control-content elementor-control-post__not_in elementor-label-inline">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-post__not_in" class="elementor-control-title"><?php esc_html_e( 'Exclude', 'ayyash-addons' ); ?></label>
					<div class="elementor-control-input-wrapper elementor-control-unit-5 elementor-control-dynamic-switcher-wrapper">
						<input name="post__not_in" id="<?php echo esc_attr( $_uid ); ?>-post__not_in" value="{{ settings.post__not_in }}" type="text" data-setting="post__not_in" placeholder="1,2,3,4">
					</div>
				</div>
				<div class="elementor-control-field-description"><?php esc_html_e( 'Post ID separated with comma.', 'ayyash-addons' ); ?></div>
			</div>
		</div>
		<!-- post per page -->
		<div class="elementor-control elementor-control-separator-default elementor-control-content elementor-control-posts_per_page elementor-label-inline">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-posts_per_page" class="elementor-control-title"><?php esc_html_e( 'Post Per Page', 'ayyash-addons' ); ?></label>
					<div class="elementor-control-input-wrapper elementor-control-dynamic-switcher-wrapper">
						<input name="posts_per_page" id="<?php echo esc_attr( $_uid ); ?>-posts_per_page" value="{{ settings.posts_per_page }}" type="number" data-setting="posts_per_page" placeholder="<?php echo esc_attr( get_option( 'posts_per_page', 12 ) ); ?>">
					</div>
				</div>
			</div>
		</div>
		<!-- offset -->
		<div class="elementor-control elementor-control-separator-default elementor-control-content elementor-control-offset elementor-label-inline">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-offset" class="elementor-control-title"><?php esc_html_e( 'Offset', 'ayyash-addons' ); ?></label>
					<div class="elementor-control-input-wrapper elementor-control-dynamic-switcher-wrapper">
						<input name="offset" id="<?php echo esc_attr( $_uid ); ?>-offset" value="{{ settings.offset }}" type="number" data-setting="offset" placeholder="10">
					</div>
				</div>
			</div>
		</div>
		<!-- Author -->
		<div class="elementor-control elementor-control-separator-default elementor-control-content elementor-control-author elementor-label-inline">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-author" class="elementor-control-title"><?php esc_html_e( 'Author', 'ayyash-addons' ); ?></label>
					<div class="elementor-control-input-wrapper elementor-control-unit-5">
						<select class="elementor-select2" type="select2" data-setting="author" name="author" id="<?php echo esc_attr( $_uid ); ?>-author" <?php echo self::$has_pro ? 'multiple' : '' ?> >
							option value="any" disabled><?php esc_html_e( 'Any Author', 'ayyash-addons' ); ?></option>
							<# _.each( settings.authors, function( option_title, option_value ) { #>
							<# var selected = values.author && values.author.includes( option_value ) ? 'selected' : ''; #>
							<option value="{{ option_value }}" {{selected}}>{{{ option_title }}}</option>
							<# } ); #>
						</select>
					</div>
				</div>
			</div>
		</div>
		<!-- orderby -->
		<div class="elementor-control elementor-control-separator-default elementor-control-content elementor-control-orderby elementor-label-inline">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-orderby" class="elementor-control-title"><?php esc_html_e( 'Order By', 'ayyash-addons' ); ?></label>
					<div class="elementor-control-input-wrapper elementor-control-unit-5">
						<select data-setting="orderby" name="orderby" id="<?php echo esc_attr( $_uid ); ?>-orderby">
							<option value="0" disabled><?php esc_html_e( 'Order By', 'ayyash-addons' ); ?></option>
							<# _.each( settings.orderby_defaults, function( option_title, option_value ) { #>
							<# var selected = values.orderby && values.orderby === option_value ? 'selected' : ''; #>
							<# selected = selected ? selected : ( 'date' === option_value ? 'selected' : '' ) ; #>
							<option value="{{ option_value }}" {{selected}}>{{{ option_title }}}</option>
							<# } ); #>
						</select>
					</div>
				</div>
			</div>
		</div>
		<!-- order -->
		<div class="elementor-control elementor-control-separator-default elementor-control-content elementor-control-order elementor-label-inline">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-order" class="elementor-control-title"><?php esc_html_e( 'Sort Order', 'ayyash-addons' ); ?></label>
					<div class="elementor-control-input-wrapper elementor-control-unit-5">
						<select name="order" id="<?php echo esc_attr( $_uid ); ?>-order" value="{{ settings.order }}" data-setting="order">
							<# _.each( settings.sort_orders, function( option_title, option_value ) { #>
							<# var selected = values.order && values.order === option_value ? 'selected' : ''; #>
							<# selected = selected ? selected : ( 'ASC' === option_value ? 'selected' : '' ) ; #>
							<option value="{{ option_value }}" {{selected}}>{{{ option_title }}}</option>
							<# } ); #>
						</select>
					</div>
				</div>
			</div>
		</div>
		<# if( settings.is_pro ) { #>
		<!-- date after -->
		<div class="elementor-control elementor-control-separator-default elementor-control-content elementor-control-date_query_after elementor-label-inline">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-date_query_after" class="elementor-control-title"><?php esc_html_e( 'Date From', 'ayyash-addons' ); ?></label>
					<div class="elementor-control-input-wrapper">
						<input name="date_query_before" id="<?php echo esc_attr( $_uid ); ?>-date_query_after" placeholder="<?php esc_attr_e( 'Date From', 'ayyash-addons' ); ?>" type="date" data-setting="date_query_after">
					</div>
				</div>
			</div>
		</div>
		<!-- date before -->
		<div class="elementor-control elementor-control-separator-default elementor-control-content elementor-control-date_query_before elementor-label-inline">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<label for="<?php echo esc_attr( $_uid ); ?>-date_query_before" class="elementor-control-title"><?php esc_html_e( 'Date To', 'ayyash-addons' ); ?></label>
					<div class="elementor-control-input-wrapper">
						<input name="date_query_before" id="<?php echo esc_attr( $_uid ); ?>-date_query_before" placeholder="<?php esc_attr_e( 'Date To', 'ayyash-addons' ); ?>" type="date" data-setting="date_query_before">
					</div>
				</div>
			</div>
		</div>
		<# } #>
		<# if( ! settings.is_pro ) { #>
		<div class="elementor-control elementor-control-separator-default elementor-control-content call-to-action ">
			<div class="elementor-control-content">
				<div class="elementor-control-field">
					<?php echo $this->call_to_action(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>
		<# } #>
		<?php
	}

	private function call_to_action() {
		return sprintf(
		/* translators: 1: pro version link  */
			esc_html__( 'Buy pro version for advance query. Like multiple taxonomy, author and getting posts base on date etc. Get pro version %1$s .', 'ayyash-addons' ),
			'<a target="_blank" href="https://wp-ayyash.com/wordpress-plugins/ayyash-addons/pricing/" >' . esc_html__( 'here', 'ayyash-addons' ) . '</a>'
		);
	}
}

// End of file class-ayyash_addons-control-query-builder.php.

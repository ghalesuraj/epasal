<?php
/**
 * Dokan hook helper
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! function_exists( 'springoo_store_per_page' ) ) {
	/**
	 * Show vendor per page
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	function springoo_store_per_page( $args ) {
		$per_page         = (int) springoo_get_mod( 'dokan_template_store_per_page' );
		$args['per_page'] = apply_filters( 'springoo_dokan_vendor_per_page', $per_page );

		return $args;
	}
}

if ( ! function_exists( 'springoo_store_columns' ) ) {
	/**
	 * Vendor Column On vendor list page
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	function springoo_store_columns( $args ) {
		$column          = (int) springoo_get_mod( 'dokan_template_store_columns' );
		$args['per_row'] = apply_filters( 'springoo_dokan_vendor_column', $column );

		return $args;
	}
}

if ( ! function_exists( 'springoo_store_products_title' ) ) {
	/**
	 * @return void
	 */
	function springoo_store_products_title() {
		?><h5 class="products-heading"><?php esc_html_e( 'Products', 'springoo' ); ?></h5><?php
	}
}

if ( ! function_exists( 'springoo_get_social_profile_fields' ) ) {
	/**
	 * Change Vendor social Icons
	 *
	 * @return array[]
	 */
	function springoo_get_social_profile_fields() {
		$fields = [
			'fb'        => [
				'icon'  => 'facebook-f',
				'title' => __( 'Facebook', 'springoo' ),
			],
			'twitter'   => [
				'icon'  => 'twitter',
				'title' => __( 'Twitter', 'springoo' ),
			],
			'pinterest' => [
				'icon'  => 'pinterest-p',
				'title' => __( 'Pinterest', 'springoo' ),
			],
			'linkedin'  => [
				'icon'  => 'linkedin-in',
				'title' => __( 'LinkedIn', 'springoo' ),
			],
			'youtube'   => [
				'icon'  => 'youtube',
				'title' => __( 'Youtube', 'springoo' ),
			],
			'instagram' => [
				'icon'  => 'instagram',
				'title' => __( 'Instagram', 'springoo' ),
			],
			'flickr'    => [
				'icon'  => 'flickr',
				'title' => __( 'Flickr', 'springoo' ),
			],
		];

		return $fields;
	}
}

if ( ! function_exists( 'springoo_dokan_vendor_list' ) ) {
	function springoo_dokan_vendor_list() {

		if ( ! class_exists( WeDevs_Dokan::class ) ) {
			return;
		}

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'springoo-nonce' ) ) {
			die( 'Permission error.' );
		}

		$dokan_seller_search = '';

		if ( isset( $_REQUEST['nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_REQUEST['nonce'] ) ), 'springoo-nonce' ) ) {
			$dokan_seller_search = isset( $_REQUEST['search'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['search'] ) ) : $dokan_seller_search;
			$featured            = ( isset( $_REQUEST['featured'] ) && ( 1 == $_REQUEST['featured'] ) ) ? 'yes' : 'no';
			$open_now            = ( isset( $_REQUEST['open_now'] ) && ( 1 == $_REQUEST['open_now'] ) ) ? 'yes' : 'no';
			$rating              = ( isset( $_REQUEST['rating'] ) ) ?? sanitize_text_field( wp_unslash( $_REQUEST['rating'] ) );
		}

		$seller_args = array(
			'number' => - 1,
			'order'  => 'DESC',
		);

		// if search is enabled, perform a search

		if ( ! empty( $dokan_seller_search ) ) {
			$seller_args['meta_query'] = [ // phpcs:ignore
				[
					'key'     => 'dokan_store_name',
					'value'   => $dokan_seller_search,
					'compare' => 'LIKE',
				],
			];
		}

		if ( 'yes' === $featured ) {
			$seller_args['featured'] = 'yes';
		}

		if ( 'yes' === $open_now ) {
			$seller_args['open_now'] = 'yes';
		}

		if ( ! empty( $rating ) ) {
			$seller_args['rating'] = intval( $rating );
		}

		$sellers       = dokan_get_sellers( $seller_args );
		$template_args = apply_filters(
			'dokan_store_list_args', [
				'sellers'             => $sellers,
				'search'              => 'yes',
				'dokan_seller_search' => $dokan_seller_search,
			]
		);
		dokan_get_template_part( 'store-lists', false, $template_args );
		die;
	}
}

if ( ! function_exists( 'springoo_dokan_page_navi' ) ) {
	function springoo_dokan_page_navi( $before, $after, $wp_query ) {
		if ( ! ( $wp_query instanceof WP_Query ) ) {
			return;
		}

		$posts_per_page = intval( get_query_var( 'posts_per_page' ) );
		$paged          = intval( get_query_var( 'paged' ) );
		$numposts       = $wp_query->found_posts;
		$max_page       = $wp_query->max_num_pages;

		if ( $numposts <= $posts_per_page ) {
			return;
		}

		//phpcs:disable
		if ( empty( $paged ) || $paged === 0 ) {
			$paged = 1;
		}
		//phpcs:disable

		$pages_to_show         = 7;
		$pages_to_show_minus_1 = $pages_to_show - 1;
		$half_page_start       = floor( $pages_to_show_minus_1 / 2 );
		$half_page_end         = ceil( $pages_to_show_minus_1 / 2 );
		$start_page            = $paged - $half_page_start;

		if ( $start_page <= 0 ) {
			$start_page = 1;
		}

		$end_page = $paged + $half_page_end;

		if ( ( $end_page - $start_page ) !== $pages_to_show_minus_1 ) {
			$end_page = $start_page + $pages_to_show_minus_1;
		}

		if ( $end_page > $max_page ) {
			$start_page = $max_page - $pages_to_show_minus_1;
			$end_page   = $max_page;
		}

		if ( $start_page <= 0 ) {
			$start_page = 1;
		}

		echo wp_kses_post( $before ) . '<div class="dokan-pagination-container"><ul class="dokan-pagination">';
		if ( $paged > 1 ) {
			$first_page_text = '&larr;';
			echo '<li class="prev"><a href="' . esc_url( get_pagenum_link() ) . '" title="First">' . esc_html( $first_page_text ) . '</a></li>';
		}

		for ( $i = $start_page; $i <= $end_page; $i ++ ) {
			if ( (int) $i === $paged ) {
				echo '<li class="active"><a href="#">' . esc_html( $i ) . '</a></li>';
			} else {
				echo '<li><a href="' . esc_url( get_pagenum_link( $i ) ) . '">' . esc_html( number_format_i18n( $i ) ) . '</a></li>';
			}
		}

		if ( (int) $paged < $max_page ) {
			$last_page_text = '&rarr;';
			echo '<li class="next"><a href="' . esc_url( get_pagenum_link( $max_page ) ) . '" title="Last">' . esc_html( $last_page_text ) . '</a></li>';
		}

		echo '</ul></div>' . wp_kses_post( $after );
	}
}

if ( ! function_exists( 'springoo_dokan_content_nav' ) ) {
	/**
	 * Display navigation to next/previous pages when applicable
	 */
	function springoo_dokan_content_nav( $nav_id, $query = null ) {
		global $wp_query, $post;

		if ( $query ) {
			$wp_query = $query; //phpcs:ignore
		}

		// Don't print empty markup on single pages if there's nowhere to navigate.
		if ( is_single() ) {
			$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
			$next = get_adjacent_post( false, '', false );

			if ( ! $next && ! $previous ) {
				return;
			}
		}

		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) ) {
			return;
		}

		$nav_class = 'site-navigation paging-navigation';
		if ( is_single() ) {
			$nav_class = 'site-navigation post-navigation';
		}
		?>
        <nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo esc_attr( $nav_class ); ?>">

            <ul class="pager">
				<?php if ( is_single() ) : // navigation links for single posts ?>



				<?php endif; ?>
            </ul>


			<?php if ( $wp_query->max_num_pages > 1 && ( dokan_is_store_page() || is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
				<?php springoo_dokan_page_navi( '', '', $wp_query ); ?>
			<?php endif; ?>

        </nav>
		<?php
	}
}

if ( ! function_exists( 'springoo_dokan_pages' ) ) {
	/**
	 * @param $classes
	 *
	 * @return string
	 */
	function springoo_dokan_pages( $classes) {
		$pages = get_option( 'dokan_pages' );
		$pages =array_values($pages);

		if ( in_array( get_the_ID(), array_values( $pages ) ) ) {
			$classes[] = 'springoo-is-dokan-page';
			return $classes;
		} else{
			return $classes;
		}

	}
}

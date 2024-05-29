<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/dokan/store-lists-filter.php
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package Dokan/Templates
 * @version 2.9.30
 */

defined( 'ABSPATH' ) || exit; ?>

<?php do_action( 'dokan_before_store_lists_filter', $stores ); ?>

<div id="dokan-store-listing-filter-wrap">
	<?php do_action( 'dokan_before_store_lists_filter_left', $stores ); ?>
	<div class="left">
		<div class="filter-item">
			<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M10.6875 0C9.33346 0 8.19475 0.970543 7.93213 2.25H0.5625C0.251842 2.25 0 2.50184 0 2.8125C0 3.12316 0.251842 3.375 0.5625 3.375H7.93213C8.19475 4.65446 9.33346 5.625 10.6875 5.625C12.0415 5.625 13.1802 4.65446 13.4429 3.375H15.1875C15.4982 3.375 15.75 3.12316 15.75 2.8125C15.75 2.50184 15.4982 2.25 15.1875 2.25H13.4429C13.1802 0.970543 12.0415 0 10.6875 0ZM10.6875 1.125C11.6261 1.125 12.375 1.87387 12.375 2.8125C12.375 3.75113 11.6261 4.5 10.6875 4.5C9.74887 4.5 9 3.75113 9 2.8125C9 1.87387 9.74887 1.125 10.6875 1.125Z" fill="var(--springoo-color-heading)"/>
				<path fill-rule="evenodd" clip-rule="evenodd" d="M5.0625 5.625C3.70846 5.625 2.56975 6.59554 2.30713 7.875H0.5625C0.251842 7.875 0 8.12684 0 8.4375C0 8.74816 0.251842 9 0.5625 9H2.30713C2.56975 10.2795 3.70846 11.25 5.0625 11.25C6.41654 11.25 7.55525 10.2795 7.81787 9H15.1875C15.4982 9 15.75 8.74816 15.75 8.4375C15.75 8.12684 15.4982 7.875 15.1875 7.875H7.81787C7.55525 6.59554 6.41654 5.625 5.0625 5.625ZM5.0625 6.75C6.00113 6.75 6.75 7.49887 6.75 8.4375C6.75 9.37613 6.00113 10.125 5.0625 10.125C4.12387 10.125 3.375 9.37613 3.375 8.4375C3.375 7.49887 4.12387 6.75 5.0625 6.75Z" fill="var(--springoo-color-heading)"/>
			</svg>
			<button class="dokan-store-list-filter-button dokan-btn dokan-btn-theme">
				<?php esc_html_e( 'Filter', 'springoo' ); ?>
			</button>
		</div>

		<p class="item store-count">
			<?php
			$store_per_page = (int) springoo_get_mod( 'dokan_template_store_per_page' );
			$current        = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			// phpcs:disable WordPress.Security
			if ( 1 === intval( $number_of_store ) ) {
				_e( 'Showing the single result', 'springoo' );
			} elseif ( $number_of_store <= $store_per_page || - 1 === $store_per_page ) {
				/* translators: %d: total results */
				printf( _n( 'Showing all %d result', 'Showing all %d results', $number_of_store, 'springoo' ), $number_of_store );
			} else {
				$first = ( $store_per_page * $current ) - $store_per_page + 1;
				$last  = min( $number_of_store, $store_per_page * $current );
				/* translators: 1: first result 2: last result 3: total results */
				printf( _nx( 'Showing %1$d&ndash;%2$d of %3$d result', 'Showing %1$d&ndash;%2$d of %3$d results', $number_of_store, 'with first and last result', 'springoo' ), $first, $last, $number_of_store );
			}
			// phpcs:enable WordPress.Security
			?>
		</p>
	</div>

	<?php do_action( 'dokan_before_store_lists_filter_right', $stores ); ?>
	<div class="right">
		<form name="stores_sorting" class="sort-by item" method="get">
			<label><?php esc_html_e( 'Sort by', 'springoo' ); ?>:</label>

			<select name="stores_orderby" id="stores_orderby" aria-label="<?php esc_attr__( 'Sort by', 'springoo' ); ?>">
				<?php
				//phpcs:disable
				foreach ( $sort_filters as $key => $filter ) {
					$optoins = "<option value='${key}'" . selected( $sort_by, $key, false ) . ">${filter}</option>";
					printf( $optoins );
				}
				//phpcs:enable
				?>
			</select>
		</form>

		<div class="toggle-view item">
			<span class="grid-icon" data-view="grid-view">
				<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect width="3.8" height="3.8" rx="1.9" fill="#10143E"/>
					<rect x="7" width="3.8" height="3.8" rx="1.9" fill="#10143E"/>
					<rect y="7" width="3.8" height="3.8" rx="1.9" fill="#10143E"/>
					<rect x="7" y="7" width="3.8" height="3.8" rx="1.9" fill="#10143E"/>
					<rect y="14" width="3.8" height="3.8" rx="1.9" fill="#10143E"/>
					<rect x="7" y="14" width="3.8" height="3.8" rx="1.9" fill="#10143E"/>
					<rect x="14" width="3.8" height="3.8" rx="1.9" fill="#10143E"/>
					<rect x="14" y="7" width="3.8" height="3.8" rx="1.9" fill="#10143E"/>
					<rect x="14" y="14" width="3.8" height="3.8" rx="1.9" fill="#10143E"/>
				</svg>
			</span>
			<span class="list-icon" data-view="list-view">
				<svg width="21" height="14" viewBox="0 0 21 14" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect x="0.199219" width="20" height="1.6" rx="0.8" fill="#9596A3"/>
					<rect x="0.199219" y="6" width="20" height="1.6" rx="0.8" fill="#9596A3"/>
					<rect x="0.199219" y="12" width="20" height="1.6" rx="0.8" fill="#9596A3"/>
				</svg>
			</span>
		</div>
	</div>
</div>

<?php do_action( 'dokan_before_store_lists_filter_form', $stores ); ?>

<form role="store-list-filter" method="get" name="dokan_store_lists_filter_form" id="dokan-store-listing-filter-form-wrap" class="<?php echo esc_attr( ( class_exists( 'Dokan_Pro' ) ) ? 'have-pro' : '' ); ?>" style="display: none">
	<div class="dokan-store-lists-input-wrap">
		<?php
		do_action( 'dokan_before_store_lists_filter_search', $stores );

		if ( apply_filters( 'dokan_load_store_lists_filter_search_bar', true ) ) :
			?>
			<div class="store-search grid-item">
				<input type="search" class="store-search-input" name="dokan_seller_search" placeholder="<?php esc_attr_e( 'Search Vendors', 'springoo' ); ?>">
			</div>
		<?php
		endif;
		?>
		<?php do_action( 'dokan_after_store_lists_filter_apply_button', $stores ); ?>
		<?php wp_nonce_field( 'dokan_store_lists_filter_nonce', '_store_filter_nonce', false ); ?>
	</div>

	<?php do_action( 'dokan_before_store_lists_filter_apply_button', $stores ); ?>
</form>

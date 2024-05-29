<?php
/**
 * WC Hook Helpers.
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! function_exists( 'springoo_woocommerce_init' ) ) :
	/**
	 * Add theme support and remove default action hooks so we can replace them with our own.
	 *
	 * @return void
	 */
	function springoo_woocommerce_init() {
		add_theme_support( 'woocommerce' );
		/**
		 * ----------------------------------------------------------------------
		 * Enable Product Zoom
		 * ----------------------------------------------------------------------*/
		if ( springoo_get_mod( 'woocommerce_general_enable_product_zoom' ) ) {
			add_theme_support( 'wc-product-gallery-zoom' );
		} else {
			remove_theme_support( 'wc-product-gallery-zoom' );
		}

		/**
		 * ----------------------------------------------------------------------
		 * Enable Product Lightbox
		 *----------------------------------------------------------------------*/
		if ( springoo_get_mod( 'woocommerce_general_enable_product_lightbox' ) ) {
			add_theme_support( 'wc-product-gallery-lightbox' );
		} else {
			remove_theme_support( 'wc-product-gallery-lightbox' );
		}

		/**
		 * ----------------------------------------------------------------------
		 * Enable Product Gallery Slider
		 * ----------------------------------------------------------------------*/
		if ( springoo_get_mod( 'woocommerce_general_enable_product_gallery_slider' ) ) {
			add_theme_support( 'wc-product-gallery-slider' );
		} else {
			remove_theme_support( 'wc-product-gallery-slider' );
		}

		// remove default content wrapper.
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper' );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end' );

	}
endif;

if ( ! function_exists( 'springoo_loop_columns' ) ) {
	/**
	 * Override theme default specification for product # per row
	 *
	 * @param int $columns
	 *
	 * @return int
	 */
	function springoo_loop_columns( $columns ) {

		$requested = isset( $_GET['columns'] ) ? sanitize_text_field( $_GET['columns'] ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $requested && in_array( $requested, [ '2', '3', '4' ] ) ) {
			return $requested;
		} else {
			$loop_columns = absint( springoo_get_mod( 'woocommerce_shop_archive_column' ) );

			if ( $loop_columns ) {
				return $loop_columns;
			}
		}

		return $columns;
	}
}

if ( ! function_exists( 'springoo_woocommerce_before_main_content' ) ) {
	/**
	 * Add markup layout before main WooCommerce content.
	 *
	 * @return void
	 */
	function springoo_woocommerce_before_main_content() {
		$view              = springoo_get_current_screen();
		$content_container = springoo_get_post_layout_options( 'content_container' );

		if ( $content_container ) {
			if ( 'default' === $content_container ) {
				$classes = apply_filters( 'springoo_content_container_class', springoo_get_mod( 'layout_global_content_layout' ) );
			} elseif ( 'none' === $content_container ) {
				$classes = '';
			} else {
				$classes = apply_filters( 'springoo_content_container_class', $content_container );
			}
		}
		?>
	<div id="content">
		<?php springoo_title_bar( $view ); ?>
		<?php do_action( 'springoo_woocommerce_before_main_content_after_title' ); ?>
		<div <?php springoo_get_content_class(); ?>>
			<div class="<?php echo esc_attr( $classes ); ?>">
				<div class="row">
					<?php
					springoo_try_sidebar( $view, 'left' );
					?>
					<div id="primary" <?php springoo_main_class(); ?>>
						<main id="main" class="site-main" role="main">
							<?php
	}
}

if ( ! function_exists( 'springoo_woocommerce_after_main_content' ) ) :
/**
 * End of the main content wrapper
 *
 * @return void
 */
	function springoo_woocommerce_after_main_content() {
							// End content wrapper.
		?>
						</main><!-- #main -->
					</div><!-- #primary -->
					<?php
					springoo_try_sidebar( springoo_get_current_screen(), 'right' );
					?>
				</div><!-- .row -->
			</div><!-- .container -->
		</div>
	</div><!-- #content -->
			<?php

	}
endif;

if ( ! function_exists( 'springoo_loop_shop_per_page' ) ) {
	/**
	 * Show number of products Per Page
	 *
	 * @param int $product_number
	 *
	 * @return int
	 */
	function springoo_loop_shop_per_page( $product_number ) {
		$per_page = (int) springoo_get_mod( 'woocommerce_shop_archive_per_page' );
		if ( $per_page < 0 || $per_page > 0 ) {
			return $per_page;
		}

		return $product_number;
	}
}

if ( ! function_exists( 'springoo_wc_hide_page_title' ) ) {
	/**
	 * Show Title
	 *
	 * @return bool
	 */
	function springoo_wc_hide_page_title() {
		return springoo_get_mod( 'woocommerce_shop_archive_title' ) == 1;
	}
}

if ( ! function_exists( 'springoo_remove_wc_breadcrumbs' ) ) {
	/**
	 * Remove Shop Page Breadcrumbs as we have default
	 *
	 * @return void
	 */
	function springoo_remove_wc_breadcrumbs() {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	}
}

if ( ! function_exists( 'springoo_wc_remove_result_count' ) ) {
	/**
	 * Remove Shop Filter
	 *
	 * @return void
	 */
	function springoo_wc_remove_result_count() {
		if ( springoo_get_mod( 'woocommerce_shop_archive_result_count' ) == 0 ) {
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		} else {
			add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		}
	}
}

if ( ! function_exists( 'springoo_wc_remove_archive_short' ) ) {
	/**
	 * Remove Shop Page Filter
	 *
	 * @return void
	 */
	function springoo_wc_remove_archive_short() {
		if ( springoo_get_mod( 'woocommerce_shop_archive_sort' ) == 0 ) {
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		} else {
			add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		}
	}
}

if ( ! function_exists( 'springoo_shop_header_open' ) ) {
	/**
	 * Springoo Shop header open
	 *
	 * @return void
	 */
	function springoo_shop_header_open() {
		echo '<div class="product-header">';
	}
}

if ( ! function_exists( 'springoo_get_product_discount' ) ) {
	/**
	 * Display Product Discount Tag.
	 *
	 * @param int $product_id Product ID.
	 * @param int $precision Numeric round precision.
	 *
	 * @return void
	 */
	function springoo_get_product_discount( $product_id = null, $precision = 0 ) {
		if ( 1 !== (int) springoo_get_mod( 'woocommerce_shop_archive_show_discount' ) ) {
			return;
		}

		$product_id = ! $product_id ? get_the_ID() : $product_id;
		$product    = wc_get_product( $product_id );
		$discount   = 0;

		$suffix = apply_filters( 'springoo_get_product_discount_suffix', springoo_get_mod( 'woocommerce_general_discount_label_text_suffix' ) );
		$prefix = apply_filters( 'springoo_get_product_discount_prefix', springoo_get_mod( 'woocommerce_general_discount_label_text_prefix' ) );

		if ( $product instanceof WC_Product_Variable ) {
			// For variable products, calculate discount based on variations
			$variations = $product->get_available_variations();

			if ( ! empty( $variations ) ) {
				$sale_price_sum = 0;

				foreach ( $variations as $variation ) {
					$variation_product = wc_get_product( $variation['variation_id'] );
					$sale_price        = $variation_product->get_sale_price();
					$regular_price     = $variation_product->get_regular_price();
					$sale_price_sum   += (float) $variation_product->get_sale_price();
				}

				if ( 0 >= $sale_price_sum ) {
					return;
				} else {
					if ( ( 0 < $sale_price ) && ( $regular_price > $sale_price ) ) {
						$discount = 'sale';
					}
				}
			}
		} elseif ( $product instanceof WC_Product ) {
			// Handle the discount calculation for simple products here
			$sale_price    = $product->get_sale_price();
			$regular_price = $product->get_regular_price();

			if ( ! empty( $sale_price ) && $regular_price > $sale_price ) {
				$discount = ( (float) $regular_price - (float) $sale_price ) / (float) $regular_price * 100;
				$discount = round( $discount, $precision );
			}
		}

		if ( class_exists( 'Springoo_Pro' ) ) {
			$product_label_type = springoo_get_mod( 'woocommerce_shop_archive_label_type' );
		} else {
			$product_label_type = '';
		}

		if ( 'sale' == $discount ) {
			echo '<span class="product-badge product-discount ' . esc_attr( $product_label_type ? $product_label_type : 'fill' ) . '">' . esc_html__( 'sale', 'springoo' ) . '</span>';
		}

		if ( $discount > 0 ) {
			printf(
				'<span class="product-badge product-discount %s">%4$s%2$s%3$s</span>',
				esc_attr( $product_label_type ? $product_label_type : 'fill' ),
				(float) $discount,
				esc_html( $suffix ),
				esc_html( $prefix )
			);
		}
	}
}

if ( ! function_exists( 'springoo_get_featured_badge' ) ) {
	/**
	 * Display Product Discount Tag.
	 *
	 * @param int $product_id Product ID.
	 *
	 * @return void
	 */
	function springoo_get_featured_badge( $product_id = null ) {

		if ( 1 !== (int) springoo_get_mod( 'woocommerce_shop_archive_show_featured' ) ) {
			return;
		}

		$product_id  = ! $product_id ? get_the_ID() : $product_id;
		$product     = wc_get_product( $product_id );
		$is_featured = false;

		if ( $product instanceof WC_Product ) {
			$is_featured = $product->is_featured();
		}

		if ( class_exists( 'Springoo_Pro' ) ) {
			$product_label_type = springoo_get_mod( 'woocommerce_shop_archive_label_type' );
		} else {
			$product_label_type = '';
		}

		if ( $is_featured ) {
			printf(
				'<span class="product-badge product-featured %s">Featured</span>',
				esc_attr( $product_label_type ? $product_label_type : 'fill' )
			);
		}
	}
}

if ( ! function_exists( 'springoo_get_product_badge' ) ) {

	/**
	 * Get product badge.
	 *
	 * @param int $product_id Product ID.
	 * @param array $check check.
	 *
	 * @return void
	 */
	function springoo_get_product_badge( $product_id = null, $check = array() ) {

		if ( 1 !== (int) springoo_get_mod( 'woocommerce_shop_archive_show_product_badge' ) ) {
			return;
		}

		$product_id = ! $product_id ? get_the_ID() : $product_id;
		$product    = wc_get_product( $product_id );
		$check      = array( 'hot', 'sale', 'trending' ); // @TODO check generating badge from product tag is necessary.

		if ( ! $product ) {
			return;
		}

		$product_tags = array_map(
			function ( $tag_id ) use ( $check ) {
				$term = get_term_by( 'id', $tag_id, 'product_tag' );

				// @phpstan-ignore-next-line
				if ( ! $term || is_wp_error( $term ) ) {
					return null;
				}

				/**
				 * @var WP_Term $term
				 */
				if ( ! empty( $check ) && ! in_array( $term->slug, $check ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
					return null;
				}

				if ( class_exists( 'Springoo_Pro' ) ) {
					$product_label_type = springoo_get_mod( 'woocommerce_shop_archive_label_type' );
				} else {
					$product_label_type = '';
				}

				return sprintf(
					'<span class="product-badge badge-%s %s">%s</span>',
					esc_attr( $term->slug ),
					esc_attr( $product_label_type ? $product_label_type : 'fill' ),
					esc_html( $term->name )
				);
			},
			$product->get_tag_ids()
		);

		$product_tags = array_filter( $product_tags );

		if ( ! empty( $product_tags ) ) {
			echo implode( '', $product_tags ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

if ( ! function_exists( 'springoo_shop_labels' ) ) {
	/**
	 * Shop labels
	 *
	 * @return void
	 */
	function springoo_shop_labels() {

		$product_label_style = springoo_get_mod( 'woocommerce_shop_archive_label_style' );

		if ( $product_label_style ) {
			$class = $product_label_style;
		} else {
			$class = 'block';
		}
		?>
		<div class="springoo-product-labels <?php echo esc_attr( $class ); ?>">

			<?php
			/**
			 * Hook: springoo_shop_labels
			 */
			do_action( 'springoo_shop_labels' );
			?>
		</div><!-- end .springoo-product-labels -->
		<?php
	}
}

if ( ! function_exists( 'springoo_shop_thumbnails' ) ) {
	/**
	 * Shop thumbnails
	 *
	 * @return void
	 */
	function springoo_shop_thumbnails() {
		?>
		<div class="springoo-product-thumbnail">
			<?php
			springoo_the_post_thumbnail(
				'springoo-shop-image',
				array(
					'wrapper'   => false,
					'auto_size' => true,
				)
			);
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_shop_actions' ) ) {
	/**
	 * Shop Actions
	 *
	 * @return void
	 */
	function springoo_shop_actions() {
		$action_pos  = springoo_get_mod( 'woocommerce_shop_archive_action_pos' );
		$action_type = springoo_get_mod( 'woocommerce_shop_archive_action_style' );

		if ( class_exists( 'Springoo_Pro' ) ) {
			$classes = array();

			if ( $action_pos ) {
				$classes[] = $action_pos;
			} else {
				$classes[] = 'right';
			}

			if ( 'rounded' === $action_type ) {
				$classes[] = 'icon-rounded';
			}

			$classes = implode( ' ', $classes );
		} else {
			$classes = 'icon-rounded right';
		}

		?>
		<div class="springoo-product-actions <?php echo esc_attr( $classes ); ?>">
			<?php

			/**
			 * Hook: springoo_shop_actions
			 */
			do_action( 'springoo_shop_actions' );
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_shop_header_close' ) ) {
	/**
	 * Shop header close
	 *
	 * @return void
	 */
	function springoo_shop_header_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'springoo_shop_content_open' ) ) {
	function springoo_shop_content_open() {
		/**
		 * Shop content open
		 */
		echo '<div class="product-content">';
	}
}

if ( ! function_exists( 'springoo_shop_category' ) ) {
	/**
	 * Shop category
	 *
	 * @return void
	 */
	function springoo_shop_category() {
		if ( 1 !== (int) springoo_get_mod( 'woocommerce_shop_archive_show_product_category' ) ) {
			return;
		}

		global $product;
		$terms = get_the_terms( $product->get_id(), 'product_cat' );
		if ( ! empty( $terms ) ) {
			echo '<ul class="springoo-product-category">';
			foreach ( $terms as $_term ) {
				$term_link = get_term_link( $_term );
				if ( ! $term_link || is_wp_error( $term_link ) ) {
					continue;
				}
				echo '<li><a href="' . esc_url( $term_link ) . '">' . esc_html( $_term->name ) . '</a></li>';
			}
			echo '</ul>';
		}
	}
}

if ( ! function_exists( 'springoo_shop_title' ) ) {
	/**
	 * Shop title
	 *
	 * @return void
	 */
	function springoo_shop_title() {
		if ( ! empty( get_the_title() ) ) {
			echo sprintf( '<h3 class="springoo-product-title"><a href="%s">%s</a></h3>', esc_url( get_the_permalink() ), esc_html( get_the_title() ) );
		}
	}
}

if ( ! function_exists( 'springoo_shop_price' ) ) {
	/**
	 * Shop price
	 *
	 * @return void
	 */
	function springoo_shop_price() {

		if ( 1 !== (int) springoo_get_mod( 'woocommerce_shop_archive_show_product_price' ) ) {
			return;
		}

		global $product;
		?>
		<div class="springoo-product-price-wrapper inline">
			<?php echo wp_kses_post( $product->get_price_html() ); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_shop_rating' ) ) {
	/**
	 * Shop rating
	 *
	 * @return void
	 */
	function springoo_shop_rating() {

		if ( ! class_exists( 'woocommerce' ) ) {
			return;
		}

		if ( class_exists( 'Springoo_Pro' ) ) {
			if ( 1 !== (int) springoo_get_mod( 'woocommerce_shop_archive_show_product_rating' ) ) {
				return;
			}
		}

		global $product;

		if ( ! wc_review_ratings_enabled() ) {
			return;
		}

		$rating_count = $product->get_rating_count();
		$review_count = $product->get_review_count();
		$average      = $product->get_average_rating();

		if ( $rating_count > 0 ) {
			?>

			<div class="springoo-product-rating">
				<?php echo wp_kses_post( wc_get_rating_html( $average, $rating_count ) ); ?>

				<?php //phpcs:disable ?>
                <span class="woocommerce-review-label">
					(<?php printf( _n( '%s review', '%s reviews', $review_count, 'springoo' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>)
				</span>
				<?php // phpcs:enable ?>

			</div>

			<?php
		}
	}
}

if ( ! function_exists( 'springoo_shop_content_close' ) ) {
	/**
	 * Shop content close
	 *
	 * @return void
	 */
	function springoo_shop_content_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'springoo_product_footer_classes' ) ) {
	/**
	 * Shop footer classes
	 *
	 * @return string
	 */
	function springoo_product_footer_classes() {
		$classes[] = 'product-footer';
		$classes   = apply_filters( 'springoo_product_footer_classes', $classes );
		$classes   = array_unique( array_filter( $classes ) );

		return implode( ' ', $classes );
	}
}

if ( ! function_exists( 'springoo_shop_footer_open' ) ) {
	/**
	 * Shop footer open
	 *
	 * @return void
	 */
	function springoo_shop_footer_open() {
		echo '<div class="' . esc_attr( springoo_product_footer_classes() ) . '">';
	}
}

if ( ! function_exists( 'springoo_shop_footer_cart_wishlist_btn' ) ) {
	/**
	 * Check if cart or wishlist button exist in footer
	 *
	 * @return string
	 */
	function springoo_shop_footer_cart_wishlist_btn( $classes ) {
		// Check if wishlist button is available in product footer
		if ( 'before_image' !== get_option( 'yith_wcwl_loop_position' ) && defined( 'YITH_WCWL' ) && 'yes' === get_option( 'yith_wcwl_show_on_loop' ) ) {
			$classes[] = 'footer-wishlist-btn';
		}

		// Check if cart button available in product footer @TODO
		// $cart_btn_position = springoo_get_mod( 'woocommerce_shop_archive_btn_position' );
		// if ( 'in-footer' === $cart_btn_position ) {
		$classes[] = 'footer-cart-btn';

		// }

		return $classes;
	}
}

if ( ! function_exists( 'springoo_shop_cart_btn' ) ) {
	function springoo_shop_cart_btn() {
		?>
		<div class="<?php echo esc_attr( springoo_product_btn_classes() ); ?>">
			<?php woocommerce_template_loop_add_to_cart(); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_shop_footer_close' ) ) {
	function springoo_shop_footer_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'springoo_mini_cart' ) ) {
	/**
	 * If theme option have enable search it will show search after menu item.
	 *
	 * @param string $items The HTML list content for the menu items.
	 * @param stdClass $args An object containing wp_nav_menu() arguments.
	 *
	 * @return string
	 */
	function springoo_mini_cart() {
		if ( springoo_get_mod( 'layout_header_show_mini_cart' ) ) {
			?>

			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-count">
				<i class="si-thin-shopping-bag" aria-hidden="true"><span
							class="count-badge"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span></i>
				<span class="title screen-reader-text"><?php _e( 'Cart', 'springoo' ); ?></span>
			</a>
			<?php
		}
	}
}

if ( ! function_exists( 'springoo_wc_change_number_usell_products' ) ) {
	/**
	 * WC UpSell display args.
	 *
	 * @param array $args Args.
	 *
	 * @return array
	 */
	function springoo_wc_upsell_display_args( $args ) {

		if ( is_product() ) {
			$args['posts_per_page'] = ( springoo_get_mod( 'woocommerce_single_upsells_count' ) ) ? springoo_get_mod( 'woocommerce_single_upsells_count' ) : 2;
		} elseif ( is_cart() ) {
			$args['posts_per_page'] = ( springoo_get_mod( 'woocommerce_cart_upsells_count' ) ) ? springoo_get_mod( 'woocommerce_cart_upsells_count' ) : 2;
		} else {
			$args['posts_per_page'] = 2;
		}

		return $args;
	}
}

if ( ! function_exists( 'springoo_wc_change_column_number_usell_products' ) ) {
	/**
	 * WC UpSell display args.
	 *
	 * @param array $args Args.
	 *
	 * @return array
	 */
	function springoo_wc_upsell_display_column_args( $args ) {

		if ( is_product() ) {
			$args['columns'] = springoo_get_mod( 'woocommerce_single_upsells_columns' );
		} elseif ( is_cart() ) {
			$args['columns'] = springoo_get_mod( 'woocommerce_cart_upsells_columns' );
		} else {
			$args['columns'] = 5;
		}

		return $args;
	}
}

if ( ! function_exists( 'springoo_wc_related_products_limit' ) ) {
	/**
	 * Related product limit.
	 *
	 * @return array
	 */
	function springoo_wc_related_products_limit() {

		if ( is_product() ) {
			$args['posts_per_page'] = ( springoo_get_mod( 'woocommerce_single_related_item_count' ) ) ? springoo_get_mod( 'woocommerce_single_related_item_count' ) : 4;
		} else {
			$args['posts_per_page'] = 4;
		}

		return $args;
	}
}

if ( ! function_exists( 'springoo_cart_cross_sell_total' ) ) {
	/**
	 * Cross Sell Count
	 *
	 * @return int
	 */
	function springoo_cart_cross_sell_total() {
		return absint( springoo_get_mod( 'woocommerce_cart_cross_sell_count' ) );
	}
}

if ( ! function_exists( 'springoo_stock_catalog' ) ) {
	/**
	 * Show product is in stock or out of stock.
	 *
	 * @return void
	 */
	function springoo_stock_catalog() {
		global $product;
		if ( $product->is_in_stock() ) {
			/* translators: %s: Stock Quantity */
			echo ' <span class="stock-tag" data-bs-toggle="tooltip" data-bs-placement="bottom" title="' . sprintf( esc_attr__( 'Stock: %s', 'springoo' ), esc_attr( $product->get_stock_quantity() ) ) . '">' . sprintf( esc_html__( '%s in stock', 'springoo' ), esc_html( $product->get_stock_quantity() ) ) . '</span>';
		} else {
			/* translators: %s: Stock Quantity */
			echo ' <span class="stock-tag" data-bs-toggle="tooltip" data-bs-placement="bottom" title="' . sprintf( esc_attr__( 'Stock: %s', 'springoo' ), esc_attr( $product->get_stock_quantity() ) ) . '">' . esc_html__( 'out of stock', 'springoo' ) . '</span>';
		}
	}
}

if ( ! function_exists( 'springoo_woocommerce_new_badge' ) ) {
	/**
	 * Show badge new if product is new.
	 *
	 * @return void
	 */
	function springoo_woocommerce_new_badge() {
		global $product;
		$postdate = get_the_time( 'Y-m-d', $product->get_id() ); // Post date.
		if ( $postdate ) {
			// @phpstan-ignore-next-line
			$postdate = strtotime( $postdate ); // Timestamped post date.
			$newness  = 10;   // Newness in days as defined by option.
			if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdate ) { // If the product was published within the newness time frame display the new badge.
				echo '<div class="label-badge new"><span>' . esc_html__( 'New', 'springoo' ) . '</span></div>';
			}
		}
	}
}

if ( ! function_exists( 'springoo_sale_countdown_timer' ) ) {
	/**
	 * Sale Countdown timer
	 *
	 * @return void
	 */
	function springoo_sale_countdown_timer() {
		global $product;

		$sale_date = $product->get_date_on_sale_to();

		if ( ! $sale_date ) {
			return;
		}

		echo '<div class="springoo-countdown offer-time" data-date="' . esc_attr( $sale_date->date( 'Y/m/d' ) ) . '"></div>';
	}
}


if ( ! function_exists( 'springoo_display_sold_out_loop_woocommerce' ) ) {
	/**
	 * Sold_out hook added
	 *
	 * @return void
	 */
	function springoo_display_sold_out_loop_woocommerce() {

		if ( 1 !== (int) springoo_get_mod( 'woocommerce_shop_archive_show_stock' ) && is_shop() ) {
			return;
		}
		global $product;

		if ( class_exists( 'Springoo_Pro' ) ) {
			$product_label_type = springoo_get_mod( 'woocommerce_shop_archive_label_type' );
		} else {
			$product_label_type = '';
		}

		if ( ! $product->is_in_stock() ) {
			printf(
				'<span class="product-badge soldout %s">%s</span>',
				esc_attr( $product_label_type ? $product_label_type : 'fill' ),
				esc_html__( 'Out Of Stock', 'springoo' )
			);
		}
	}
}

if ( ! function_exists( 'springoo_filter_product_rating_html' ) ) {
	/**
	 * Filter Product rating
	 *
	 * @param string $rating_html rating html.
	 *
	 * @return string
	 */
	function springoo_filter_product_rating_html( $rating_html ) {

		global $springoo_products;

		if ( ! $springoo_products ) {
			return $rating_html;
		}
		if ( isset( $springoo_products['display_rating'] ) && 'yes' == $springoo_products['display_rating'] ) {
			return $rating_html;
		}

		return '';
	}
}

if ( ! function_exists( 'springoo_change_number_related_products' ) ) {
	/**
	 * Related Product Column Limit
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	function springoo_change_number_related_products( $args ) {

		$args['columns'] = absint( springoo_get_mod( 'woocommerce_single_related_product_column' ) ); // # of columns per row

		return $args;
	}
}

if ( ! function_exists( 'springoo_wc_gallery_thumb_size' ) ) {
	/**
	 * Product Gallery Thumb Image Size Resizing.
	 *
	 * @return array
	 */
	function springoo_wc_gallery_thumb_size() {
		return array(
			'width'  => 145,
			'height' => 210,
			'crop'   => 1,
		);
	}
}

if ( ! function_exists( 'springoo_variation_check' ) ) {
	/**
	 * Check Product Variation.
	 *
	 * @param bool $active Activate.
	 * @param WC_Product_Variation $variation Variation.
	 *
	 * @return bool
	 */
	function springoo_variation_check( $active, $variation ) {
		if ( ! $variation->is_in_stock() && ! $variation->backorders_allowed() ) {
			return false;
		}

		return $active;
	}
}

if ( ! function_exists( 'springoo_product_loop_start' ) ) {
	/**
	 * WooCommerce loop start
	 *
	 * @return void
	 */
	function springoo_product_loop_start() {
		echo sprintf( '<ul class="%s" >', esc_attr( springoo_product_loop_classes() ) );
	}
}

if ( ! function_exists( 'springoo_product_loop_classes' ) ) {
	/**
	 * Springoo Cart Button Classes
	 *
	 * @return string
	 */
	function springoo_product_loop_classes() {
		$classes[] = 'products springoo-products columns-' . esc_attr( wc_get_loop_prop( 'columns' ) );
		$classes   = apply_filters( 'springoo_product_loop_classes', $classes );
		$classes   = array_unique( array_filter( $classes ) );

		return implode( ' ', $classes );
	}
}

if ( ! function_exists( 'springoo_product_classes' ) ) {
	/**
	 * Springoo Cart Button Classes
	 *
	 * @return string
	 */
	function springoo_product_classes() {
		$classes[] = 'springoo-product-item';
		$classes[] = 'hover-grow-up';
		$classes[] = 'hover-actions';
		$classes[] = 'hover-button';
		$classes[] = 'springoo-cart-button';
		$classes   = apply_filters( 'springoo_product_classes', $classes );
		$classes   = array_unique( array_filter( $classes ) );

		return implode( ' ', $classes );
	}
}

if ( ! function_exists( 'product_cat_item_class' ) ) {
	/**
	 * Springoo Product category item class
	 *
	 * @return string
	 */
	function product_cat_item_class( $classes ) {
		$classes[] = 'springoo-product-item';

		return $classes;
	}
}

if ( ! function_exists( 'springoo_product_btn_classes' ) ) {
	/**
	 * Springoo Cart Button Classes
	 *
	 * @return string
	 */
	function springoo_product_btn_classes() {
		$classes[] = 'springoo-cart-button';
		$classes   = apply_filters( 'springoo_product_btn_classes', $classes );
		$classes   = array_unique( array_filter( $classes ) );

		return implode( ' ', $classes );
	}
}

if ( ! function_exists( 'springoo_single_product_actions' ) ) {
	/**
	 * Product Actions
	 *
	 * @return void
	 */
	function springoo_single_product_actions() {
		?>
		<div class="springoo-product-single-actions d-flex align-items-center">
			<?php
			/**
			 * Hook springoo_product_actions.
			 */
			do_action( 'springoo_product_actions' );
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_stock_management_initial_stock' ) ) {
	/**
	 * Initial Stock Quantity for stock count progress
	 */
	function springoo_stock_management_initial_stock() {
		woocommerce_wp_text_input(
			array(
				'label'         => 'Product Initial Stock',
				'placeholder'   => 'Product Initial Stock',
				'wrapper_class' => '',
				'value'         => get_post_meta( get_the_ID(), '_initial_stock_quantity', true ),
				'id'            => '_initial_stock_quantity',
				'name'          => '_initial_stock_quantity',
				'type'          => 'number',
				'desc_tip'      => true,
				'description'   => 'Product Initial Stock Quantity. It will show progress bar with quantity stock',
			)
		);
	}
}

if ( ! function_exists( 'springoo_stock_management_initial_stock_save' ) ) {
	/**
	 * Initial Stock Quantity for stock count progress save
	 */
	function springoo_stock_management_initial_stock_save() {
		global $post_id;
		if ( ! isset( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( wp_unslash( sanitize_text_field( $_POST['woocommerce_meta_nonce'] ) ), 'woocommerce_save_data' ) ) {
			return;
		}

		$initial_stock = isset( $_POST['_initial_stock_quantity'] ) ? sanitize_text_field( $_POST['_initial_stock_quantity'] ) : '';

		update_post_meta( $post_id, '_initial_stock_quantity', wc_clean( wp_unslash( $initial_stock ) ) );
	}
}

if ( ! function_exists( 'springoo_stock_management_variable_initial_stock' ) ) {
	/**
	 * Initial Stock Quantity for stock count progress for variable product
	 *
	 * @param $loop
	 * @param $variation_data
	 * @param $post_object
	 *
	 * @return void
	 */
	function springoo_stock_management_variable_initial_stock( $loop, $variation_data, $post_object ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed
		if ( 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
			woocommerce_wp_text_input(
				array(
					'label'         => 'Product Initial Stock',
					'placeholder'   => 'Product Initial Stock',
					'wrapper_class' => 'show_if_variation_manage_stock form-row',
					'value'         => get_post_meta( $post_object->ID, '_initial_stock_quantity', true ),
					'id'            => "_initial_stock_quantity_{$loop}",
					'name'          => "_initial_stock_quantity_{$loop}",
					'type'          => 'number',
					'desc_tip'      => true,
					'description'   => 'Product Initial Stock Quantity. It will show progress bar with quantity stock',
				)
			);
		}
	}
}

if ( ! function_exists( 'springoo_stock_management_variable_initial_stock_save' ) ) {
	function springoo_stock_management_variable_initial_stock_save( $variation_id, $i ) {
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$initial_stock = isset( $_POST[ '_initial_stock_quantity_' . $i ] ) ? sanitize_text_field( $_POST[ '_initial_stock_quantity_' . $i ] ) : '';
		update_post_meta( $variation_id, '_initial_stock_quantity', $initial_stock );
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}
}

if ( ! function_exists( 'springoo_single_product_stock_count' ) ) {
	/**
	 * Product Summary Stock Count
	 *
	 * @return void
	 */
	function springoo_single_product_stock_count() {
		global $product;

		if ( ! $product->get_manage_stock() ) {
			return;
		}

		if ( $product->get_manage_stock() ) {
			$product_stock         = $product->get_stock_quantity();
			$initial_product_stock = intval( get_post_meta( $product->get_id(), '_initial_stock_quantity', true ) );
		} else {
			if ( 'variable' === $product->get_type() ) {
				echo wp_kses_post( '<div class="springoo-stock-count-wrap"></div>' );

				return;
			}
		}
		?>
		<?php // phpcs:disable ?>
        <div class="springoo-stock-count-wrap">
            <div class="springoo-stock-count-content">
				<?php
				/* translators: Product Stock */
				printf( wp_kses_post( _n( 'Only <span class="stock-number">%s item</span> left in stock!', 'Only <span class="stock-number">%s items</span> left in stock!', $product_stock, 'springoo' ) ), esc_html( $product_stock ) );
				?>
            </div><!-- end .springoo-stock-count-content -->
			<?php if ( $initial_product_stock ) : ?>
                <div class="springoo-stock-count-progress">
                    <div class="progress">
                        <div class="progress-bar" id="springoo-progress-bar" role="progressbar" style="width:100%"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div><!-- end .springoo-stock-count-progress -->
			<?php endif; ?>
        </div><!-- end .springoo-stock-count-wrap -->

		<?php
		if ( $initial_product_stock ) {
			$percentage = round( ( $product_stock / $initial_product_stock ) * 100 );
			?>
            <script>
              (function($) {
                $('#springoo-progress-bar').animate({
                  width: "<?php echo esc_attr( $percentage ); ?>%",
                }, 1000);
              })(jQuery);
            </script>
		<?php }
		// phpcs:enable
	}
}

if ( ! function_exists( 'springoo_my_account_content_title' ) ) {
	/**
	 * Function Name        : springoo_my_account_content_title
	 * Function Hooked      : woocommerce_before_account_orders,
	 *                        woocommerce_before_account_orders,
	 *                        woocommerce_before_account_downloads,
	 *                        woocommerce_before_edit_account_form,
	 *                        woocommerce_before_edit_my_address
	 * Function return Type : html
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_my_account_content_title() {
		if ( is_wc_endpoint_url( 'Dashboard' ) && in_the_loop() ) {
			?>
			<h4><?php esc_html_e( 'Dashboard', 'springoo' ); ?></h4>
			<?php
		} elseif ( is_wc_endpoint_url( 'orders' ) && in_the_loop() ) {
			?>
			<h4><?php esc_html_e( 'Orders', 'springoo' ); ?></h4>
			<?php
		} elseif ( is_wc_endpoint_url( 'downloads' ) && in_the_loop() ) {
			?>
			<h4><?php esc_html_e( 'Downloads', 'springoo' ); ?></h4>
			<?php
		} elseif ( is_wc_endpoint_url( 'edit-account' ) && in_the_loop() ) {
			?>
			<h4><?php esc_html_e( 'Account Details', 'springoo' ); ?></h4>
			<?php
		} elseif ( is_wc_endpoint_url( 'edit-address' ) && in_the_loop() ) {
			?>
			<h4><?php esc_html_e( 'Addresses', 'springoo' ); ?></h4>
			<?php
		} else {
			?>
			<h4><?php esc_html_e( 'Dashboard', 'springoo' ); ?></h4>
			<?php
		}
	}
}

if ( ! function_exists( 'springoo_remove_my_account_menu_items' ) ) {
	function springoo_remove_my_account_menu_items( $items ) {

		// Remove 'customer-logout' key / label pair from original $items array
		if ( array_key_exists( 'customer-logout', $items ) ) {
			unset( $items['customer-logout'] );
		}

		return $items;
	}
}

if ( ! function_exists( 'woocommerce_download_button_label' ) ) {
	/**
	 * Change Woocommerce Download Button label
	 */

	function woocommerce_download_button_label( $downloads ) {
		foreach ( $downloads as &$download ) {
			$download['download_name'] = __( 'Download', 'springoo' );
		}

		return $downloads;
	}
}

if ( ! function_exists( 'springoo_add_cart_link' ) ) {


	function springoo_add_cart_link() {

		global $product;

		if ( 'springoo_shop_actions' === current_action() ) {
			echo sprintf(
				'<a href="%1$s" data-type="%2$s" data-quantity="%3$s"  class="cart-btn %4$s" %5$s><i class="si si-thin-shopping"></i><span class="springoo-tooltip">%6$s</span><span class="sr-only">%6$s</span></a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( $product->get_type() ? $product->get_type() : 0 ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '', //phpcs:ignore
				esc_html( $product->add_to_cart_text() )
			);
		} else {
			echo sprintf(
				'<a href="%1$s" data-type="%2$s" data-quantity="%3$s"  class="cart-btn %4$s" %5$s><i class="si-thin-shopping-bag" aria-hidden="true"></i> %6$s</a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( $product->get_type() ? $product->get_type() : 0 ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '', //phpcs:ignore
				esc_html( $product->add_to_cart_text() )
			);
		}

	}
}

if ( ! function_exists( 'springoo_cart_cross_sell_display' ) ) {
	/**
	 * Remove woocommerce_cross_sell_display and display it after form
	 *
	 * @return void
	 */
	function springoo_cart_cross_sell_display() {
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
		add_action( 'springoo_after_cart_form', 'woocommerce_cross_sell_display' );
	}
}

// new added
if ( ! function_exists( 'springoo_reviews_summery_display' ) ) {
	/**
	 * Reviews Summery Display
	 *
	 * @param $product_id
	 *
	 * @return false|void
	 */
	function springoo_reviews_summery_display( $product_id ) {
		?>
		<div class="springoo-reviews-summery">
			<?php springoo_reviews_summery( $product_id ); ?>
			<?php springoo_reviews_summery_details( $product_id ); ?>
			<?php springoo_reviews_summery_note(); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_reviews_sort_filter_display' ) ) {
	/**
	 * Reviews Sort, Filter And write review Button Display
	 *
	 * @param $product_id
	 *
	 * @return false|void
	 */
	function springoo_reviews_sort_filter_display( $product_id ) {
		$product = wc_get_product( $product_id );
		?>
		<div class="springoo-reviews-sort-filter">
			<span class="reviews-sort-filter__title">
				<?php
				$count                     = $product->get_review_count();
				$reviews_sort_filter_title = esc_html( _n( 'Product review', 'Product reviews', $count, 'springoo' ) );
				echo apply_filters( 'springoo_reviews_sort_filter_title', $reviews_sort_filter_title, $count ); // phpcs:ignore
				?>
			</span>
			<div class="revers-sort-filter--right">
				<div class="sort-wrap">
					<i class="si si-thin-frame" aria-hidden="true"></i>
					<span><?php echo esc_html__( 'Sort:', 'springoo' ); ?></span>
					<select name="springoo_reviews_sort" id="springoo_reviews_sort" <?php echo ! springoo_count_ratings( $product_id, 0 ) ? esc_attr( 'disabled' ) : ''; ?>>
						<option value="relevance"><?php echo esc_html__( 'Relevance', 'springoo' ); ?></option>
						<option value="recent"><?php echo esc_html__( 'Recent', 'springoo' ); ?></option>
						<option value="ratinghigh"><?php echo esc_html__( 'High Rating', 'springoo' ); ?></option>
						<option value="ratinglow"><?php echo esc_html__( 'Low Rating', 'springoo' ); ?></option>
					</select>
				</div>
				<div class="filter-wrap">
					<i class="si si-thin-layer" aria-hidden="true"></i>
					<span><?php echo esc_html__( 'Filter:', 'springoo' ); ?></span>
					<select name="springoo_reviews_filter" id="springoo_reviews_filter" <?php echo ! springoo_count_ratings( $product_id, 0 ) ? esc_attr( 'disabled' ) : ''; ?>>
						<option value="-1">
							<?php echo esc_html__( 'All Stars ', 'springoo' ); ?>
						</option>
						<option value="5" <?php echo ! springoo_count_ratings( $product_id, 5 ) ? esc_attr( 'disabled' ) : ''; ?> >
							<?php echo esc_html__( '5 Star ', 'springoo' ); ?>
						</option>
						<option value="4" <?php echo ! springoo_count_ratings( $product_id, 4 ) ? esc_attr( 'disabled' ) : ''; ?>>
							<?php echo esc_html__( '4 Star', 'springoo' ); ?>
						</option>
						<option value="3" <?php echo ! springoo_count_ratings( $product_id, 3 ) ? esc_attr( 'disabled' ) : ''; ?>>
							<?php echo esc_html__( '3 Star', 'springoo' ); ?>
						</option>
						<option value="2" <?php echo ! springoo_count_ratings( $product_id, 2 ) ? esc_attr( 'disabled' ) : ''; ?>>
							<?php echo esc_html__( '2 Star', 'springoo' ); ?>
						</option>
						<option value="1" <?php echo ! springoo_count_ratings( $product_id, 1 ) ? esc_attr( 'disabled' ) : ''; ?>>
							<?php echo esc_html__( '1 Star', 'springoo' ); ?>
						</option>
					</select>
				</div>
				<button type="button" class="springoo-review-btn" data-bs-toggle="modal" data-bs-target="#review_dialog"><?php echo esc_html__( 'Write a Review', 'springoo' ); ?></button>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_review_feedback_display' ) ) {
	/**
	 * Reviews feedback
	 *
	 * @return void
	 */
	function springoo_review_feedback_display() {
		global $comment;
		$feedback_yes = 0;
		$feedback_no  = 0;
		if ( get_comment_meta( $comment->comment_ID, 'springoo_review_feedback_yes', true ) ) {
			$feedback_yes = count( get_comment_meta( $comment->comment_ID, 'springoo_review_feedback_yes', true ) );
		}
		if ( get_comment_meta( $comment->comment_ID, 'springoo_review_feedback_no', true ) ) {
			$feedback_no = count( get_comment_meta( $comment->comment_ID, 'springoo_review_feedback_no', true ) );
		}
		?>
		<div class="springoo-reviews-feedback" data-id="<?php echo esc_attr( $comment->comment_ID ); ?>">
			<div class="feedback__title"><?php echo esc_html__( 'Helpful?', 'springoo' ); ?></div>
			<div class="feedback__yes springoo-feedback">
				<i class="si si-thin-like" aria-hidden="true"></i>
				<span class="feedback-prefix"><?php echo esc_html__( 'Yes', 'springoo' ); ?></span>
				<span class="feedback-count count-like">(<?php echo esc_html( $feedback_yes ); ?>)</span>
			</div>
			<div class="feedback__no springoo-feedback">
				<i class="si si-thin-dislike" aria-hidden="true"></i>
				<span class="feedback-prefix"><?php echo esc_html__( 'No', 'springoo' ); ?></span>
				<span class="feedback-count count-unlike">(<?php echo esc_html( $feedback_no ); ?>)</span>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_reviews_meta_fields' ) ) {
	/**
	 * Add Springoo Reviews Meta Fields
	 *
	 * @return void
	 */
	function springoo_reviews_meta_fields() {

		if ( ! class_exists( 'Springoo_Pro' ) ) {
			return;
		}

		if ( class_exists( 'CSF' ) ) {
			// Set a unique slug-like ID
			$prefix = 'review_options';

			CSF::createCommentMetabox(
				$prefix,
				array(
					'title'     => __( 'Review Options', 'springoo' ),
					'data_type' => 'unserialize',
					'priority'  => 'default',
				)
			);

			CSF::createSection(
				$prefix,
				array(
					'title'  => '',
					'fields' => array(
						array(
							'id'    => 'pl-short-desc',
							'type'  => 'text',
							'title' => __( 'Short Description', 'springoo' ),
						),
						array(
							'id'          => 'pl-gallery',
							'type'        => 'gallery',
							'title'       => __( 'Gallery', 'springoo' ),
							'add_title'   => __( 'Add Images', 'springoo' ),
							'edit_title'  => __( 'Edit Images', 'springoo' ),
							'clear_title' => __( 'Remove Images', 'springoo' ),
						),
					),
				)
			);
		}
	}
}

if ( ! function_exists( 'springoo_reviews_title' ) ) {
	/**
	 * Springoo Reviews Title
	 *
	 * @param $comment
	 *
	 * @return void
	 */
	function springoo_reviews_title( $comment ) {

		$meta = get_comment_meta( $comment->comment_ID, 'pl-short-desc', true );
		if ( empty( $meta ) ) {
			return;
		}
		?>
		<h6 class="single-reviews__title">
			<?php echo wp_kses_post( $meta ); ?>
		</h6>
		<?php
	}
}

if ( ! function_exists( 'springoo_reviews_gallery' ) ) {
	/**
	 * Springoo Reviews Gallery
	 *
	 * @param $comment
	 *
	 * @return void
	 */
	function springoo_reviews_gallery( $comment ) {
		$review_title   = get_comment_meta( $comment->comment_ID, 'pl-short-desc', true );
		$attachment_ids = get_comment_meta( $comment->comment_ID, 'pl-gallery', true );
		if ( empty( $attachment_ids ) ) {
			return;
		}
		$attachment_ids = explode( ',', $attachment_ids );
		?>
		<div class="single-reviews__gallery d-flex">
			<?php
			foreach ( $attachment_ids as $i => $attachment_id ) {
				$image = wp_get_attachment_image_src( $attachment_id, 'full', false );
				if ( ! $image ) {
					continue;
				}

				$html = wp_get_attachment_image(
					$attachment_id,
					'springoo-review-thumb',
					false,
					array(
						'loading' => 'loading',
						/* translators:  %1$s review title, %2$d gallery image count */
						'alt'     => sprintf( __( '%1$s Gallery (Item %2$d)', 'springoo' ), $review_title, $i ),
						'class'   => 'review-gallery__image',
					)
				);

				if ( ! $html ) {
					continue;
				}
				?>
				<a class="review-gallery__item" href="<?php echo esc_url( $image[0] ); ?>" data-lightbox="gallery-<?php echo esc_attr( $comment->comment_ID ); ?>" target="_blank"> <?php echo wp_kses_post( $html ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped ?></a>
				<?php
			}
			?>
		</div>
		<?php
	}
}

function springoo_review_nonce( $post_id ) {
	wp_nonce_field( 'springoo-comment_' . $post_id, 'springoo_wpnonce', false );
}

if ( ! function_exists( 'springoo_save_reviews' ) ) {
	/**
	 * Springoo Save Reviews
	 *
	 * @param int $comment_id
	 *
	 * @return void
	 */
	function springoo_save_reviews( $comment_id ) {
		if (
			! isset( $_POST['springoo_wpnonce'], $_POST['comment_post_ID'] ) ||
			! wp_verify_nonce( sanitize_text_field( $_POST['springoo_wpnonce'] ), 'springoo-comment_' . absint( $_POST['comment_post_ID'] ) ) ||
			'product' !== get_post_type( absint( $_POST['comment_post_ID'] ) )
		) {
			return;
		}

		$product_id = absint( $_POST['comment_post_ID'] );

		if ( ! empty( $_POST['comment_title'] ) ) {
			add_comment_meta( $comment_id, 'pl-short-desc', sanitize_text_field( $_POST['comment_title'] ), true );
		}

		if ( isset( $_FILES['review_gallery']['name'] ) ) {

			if ( ! function_exists( 'media_handle_upload' ) ) {
				// These files need to be included as dependencies when on the front end.

				// phpcs:disable WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once ABSPATH . 'wp-admin/includes/image.php';
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/media.php';
				// phpcs:enable
			}

			// Multi File upload sanitizing in next for loop
			$files          = $_FILES['review_gallery']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$attachment_ids = array();
			$allowed_types  = apply_filters(
				'springoo_review_gallery_allowed_file_types',
				array(
					'jpg|jpeg|jpe' => 'image/jpeg',
					'gif'          => 'image/gif',
					'png'          => 'image/png',
				)
			);

			foreach ( $files['name'] as $idx => $name ) {
				// more than 5 image not allowed
				if ( $idx > get_option( 'woocommerce_review_image_number', 5 ) ) {
					break;
				}

				// if single file more than 2000 KB
				if ( $files['size'][ $idx ] > 1024 * 1024 * get_option( 'woocommerce_review_image_file_size', 2 ) ) {
					continue;
				}

				if ( $files['name'][ $idx ] ) {

					$file_info = wp_check_filetype( basename( $files['name'][ $idx ] ), $allowed_types );

					if ( ! $file_info['ext'] || ! $file_info['type'] ) {
						continue;
					}

					$_FILES['review_gallery_item'] = array(
						'name'     => $files['name'][ $idx ],
						'type'     => $files['type'][ $idx ],
						'tmp_name' => $files['tmp_name'][ $idx ],
						'error'    => $files['error'][ $idx ],
						'size'     => $files['size'][ $idx ],
					);

					$attachment_id = media_handle_upload( 'review_gallery_item', $product_id );

					if ( ! is_wp_error( $attachment_id ) ) {
						$attachment_ids[] = $attachment_id;
					}
				}
			}

			if ( ! empty( $attachment_ids ) ) {
				// csf meta compatibility, save as comma separated value.
				add_comment_meta( $comment_id, 'pl-gallery', implode( ',', $attachment_ids ), true );
			}
		}
	}
}

if ( ! function_exists( 'springoo_wc_show_product_per_page' ) ) {
	function springoo_wc_show_product_per_page() {
		$per_page = filter_input( INPUT_GET, 'per-page', FILTER_SANITIZE_NUMBER_INT );

		$per_pages_options = array(
			''    => 'Posts Per Page',
			'12'  => '12',
			'24'  => '24',
			'48'  => '48',
			'96'  => '96',
			'192' => '192',
			'384' => '384',
			'-1'  => 'All',
		);

		echo '<div class="woocommerce-perpage woocommerce-ordering">';
		echo '<select onchange="if (this.value) window.location.href=this.value">';

		foreach ( $per_pages_options as $value => $label ) {
			/* translators: %1$s selected item, %2$s Item values, %3$s value name */
			printf( '<option %1$s value="%2$s">%3$s</option>', esc_attr( selected( $per_page, $value ) ), esc_attr( add_query_arg( array( 'per-page' => $value ), '' ) ), esc_html( $label ) );
		}

		echo '</select>';
		echo '</div>';
	}
}

if ( ! function_exists( 'springoo_wc_product_archive_filter_header' ) ) {
	function springoo_wc_product_archive_filter_header() {
		?>
		<div class="woocommerce-products-header__filter">
			<div class="woocommerce-products-header__filter-label">
				<?php echo esc_html__( 'Show:', 'springoo' ); ?>
			</div>
			<?php springoo_wc_show_product_per_page(); ?>
			<?php woocommerce_catalog_ordering(); ?>
			<?php springoo_shop_views(); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_wc_product_specification_tab' ) ) {
	function springoo_wc_product_specification_tab( $tabs ) {

		// Adds the new tab
		$specifications = get_post_meta( get_the_ID(), 'springoo_product_specification_repeater', true );
		if ( empty( $specifications ) ) {
			return $tabs;
		}
		$tabs['specification_tab'] = array(
			'title'    => __( 'Specification', 'springoo' ),
			'priority' => 40,
			'callback' => 'springoo_wc_product_specification_tab_content',
		);

		return $tabs;

	}
}

if ( ! function_exists( 'springoo_wc_product_specification_tab_content' ) ) {
	function springoo_wc_product_specification_tab_content() {
		?>
		<div id="specification" class="woocommerce-specification table-responsive">
			<table class="table specification">

				<?php
				$specifications = get_post_meta( get_the_ID(), 'springoo_product_specification_repeater', true );
				if ( ! empty( $specifications ) ) {
					foreach ( $specifications as $specification => $value ) {
						?>
						<tr>
							<th style="width:45%"><?php echo esc_html( $value['springoo_product_specification_title'] ); ?></th>
							<td style="width:55%"><?php echo esc_html( $value['springoo_product_specification_value'] ); ?></td>
						</tr>
						<?php
					}
				}
				?>
			</table>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_product_archive_banner' ) ) {
	/**
	 * Archive Banner
	 *
	 * @return void
	 * @since  3.1.0
	 */
	function springoo_product_archive_banner() {

		if ( ! is_product() && ! is_search() ) {
			if ( is_active_sidebar( 'product-archive-banner-widget' ) ) {
				?>
				<section class="widget-area">
					<div class="<?php echo esc_attr( apply_filters( 'springoo_product_archive_container_class', springoo_get_mod( 'layout_global_content_layout' ) ) ); ?>">
						<?php dynamic_sidebar( 'product-archive-banner-widget' ); ?>
					</div>
				</section><!-- .widget-area -->
				<?php
			}
		}
	}
}

if ( ! function_exists( 'springoo_product_archive_header' ) ) {
	/**
	 * Display the shop page title and description.
	 *
	 * @return void
	 * @since  3.1.0
	 */
	function springoo_product_archive_header() {
		if ( ! is_product() ) {
			?>
			<section class="woocommerce-products-header-wrapper">
				<div class="<?php echo esc_attr( springoo_get_mod( 'layout_global_content_layout' ) ); ?>">
					<header class="woocommerce-products-header m-0">
						<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
							<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
						<?php endif; ?>

						<?php
						/**
						 * Hook: woocommerce_archive_description.
						 *
						 * @hooked woocommerce_taxonomy_archive_description - 10
						 * @hooked woocommerce_product_archive_description - 10
						 */
						do_action( 'woocommerce_archive_description' );
						?>

						<a href="#" id="springoo-filter-toggle" class="d-block d-md-none shop-filter" role="button" aria-controls="filter-toggle">
							<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M10.6875 0C9.33346 0 8.19475 0.970543 7.93213 2.25H0.5625C0.251842 2.25 0 2.50184 0 2.8125C0 3.12316 0.251842 3.375 0.5625 3.375H7.93213C8.19475 4.65446 9.33346 5.625 10.6875 5.625C12.0415 5.625 13.1802 4.65446 13.4429 3.375H15.1875C15.4982 3.375 15.75 3.12316 15.75 2.8125C15.75 2.50184 15.4982 2.25 15.1875 2.25H13.4429C13.1802 0.970543 12.0415 0 10.6875 0ZM10.6875 1.125C11.6261 1.125 12.375 1.87387 12.375 2.8125C12.375 3.75113 11.6261 4.5 10.6875 4.5C9.74887 4.5 9 3.75113 9 2.8125C9 1.87387 9.74887 1.125 10.6875 1.125Z" fill="var(--springoo-color-heading)"></path>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M5.0625 5.625C3.70846 5.625 2.56975 6.59554 2.30713 7.875H0.5625C0.251842 7.875 0 8.12684 0 8.4375C0 8.74816 0.251842 9 0.5625 9H2.30713C2.56975 10.2795 3.70846 11.25 5.0625 11.25C6.41654 11.25 7.55525 10.2795 7.81787 9H15.1875C15.4982 9 15.75 8.74816 15.75 8.4375C15.75 8.12684 15.4982 7.875 15.1875 7.875H7.81787C7.55525 6.59554 6.41654 5.625 5.0625 5.625ZM5.0625 6.75C6.00113 6.75 6.75 7.49887 6.75 8.4375C6.75 9.37613 6.00113 10.125 5.0625 10.125C4.12387 10.125 3.375 9.37613 3.375 8.4375C3.375 7.49887 4.12387 6.75 5.0625 6.75Z" fill="var(--springoo-color-heading)"></path>
							</svg>
							<span><?php esc_html_e( 'Filter', 'springoo' ); ?></span>
						</a>
					</header>
				</div>
			</section>
			<?php
		}
	}
}

if ( ! function_exists( 'springoo_loop_stock_message' ) ) {
	/**
	 * Product Loop stock status
	 *
	 * @return void
	 */
	function springoo_loop_stock_message() {
		global $product;
		echo wc_get_stock_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'springoo_wc_stock_message' ) ) {
	/**
	 * @param $availability
	 * @param $product
	 *
	 * @return void
	 */
	function springoo_wc_stock_message( $availability, $product ) {
		if ( 'instock' === $product->get_stock_status() ) {
			$availability = 'In Stock';
		}

		return $availability;
	}
}

if ( ! function_exists( 'springoo_wc_product_share' ) ) {
	/**
	 * Product share button
	 */
	function springoo_wc_product_share() {
		global $product;
		$product_id    = $product->get_id();
		$product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
		?>
		<button type="button" class="product-share" data-url="<?php echo esc_url( get_permalink( $product_id ) ); ?>" data-title="<?php echo esc_attr( get_the_title( $product_id ) ); ?>" data-image="<?php echo esc_url( $product_image[0] ); ?>"><i class="si si-thin-share"></i><?php echo esc_html__( 'Share', 'springoo' ); ?></button>
		<?php

	}
}

if ( ! function_exists( 'woocommerce_countdown_timer' ) ) {
	/**
	 *
	 * WooCommerce Countdown Timer
	 */
	function woocommerce_countdown_timer() {
		global $product;

		if ( $product->is_type( 'grouped' ) || $product->is_type( 'variable' ) ) {
			return;
		}

		if ( ! $product->is_on_sale() ) {
			return;
		}

		if ( ! $product->get_date_on_sale_to() || $product->get_date_on_sale_to()->getTimestamp() < time() ) {
			return;
		}

		$sale_end_date      = $product->get_date_on_sale_to();
		$end_time_zone      = date( 'T', time() );
		$end_time           = $sale_end_date->getTimestamp();
		$end_time_title     = date( 'F j, Y', $end_time );
		$end_time_countdown = date( 'd.m.Y H:i', $end_time ) . ' ' . $end_time_zone;
		?>
		<div class="countdown-timer has-border">
			<div class="countdown-timer__title">
				<?php
				/* translators: %s Sales end date */
				printf( esc_html__( 'Limited time offer. The deal will expire on %s HURRY UP!', 'springoo' ), '<span class="sale-date"><strong>' . esc_html( $product->get_date_on_sale_to()->format( 'F j, Y' ) ) . '</strong></span>' );
				?>
			</div>
			<div class="countdown-timer__countdown d-flex align-items-center">
				<div class="springoo-sales-countdown" data-timer-end="<?php echo esc_attr( $product->get_date_on_sale_to()->format( 'd.m.Y H:i T' ) ); ?>" data-label-placement="after" data-label-days="d" data-label-hours="h" data-label-minutes="m" data-label-seconds="s"></div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woocommerce_countdown_timer_variable' ) ) {
	/**
	 * Countdown timer
	 *
	 * @return void
	 */
	function woocommerce_countdown_timer_variable() {
		global $product;
		if ( ! $product->is_on_sale() || ! $product->is_type( 'variable' ) ) {
			return;
		}

		$now      = time();
		$min_time = 0;
		foreach ( $product->get_children() as $variation_id ) {

			$sale_end_date = intval( get_post_meta( $variation_id, '_sale_price_dates_to', true ) );

			if ( ! $sale_end_date || $sale_end_date <= $now ) {
				continue;
			}

			if ( ! $min_time ) {
				$min_time = $sale_end_date;
			} else {
				if ( $min_time > $sale_end_date ) {
					$min_time = $sale_end_date;
				}
			}
		}
		if ( 0 >= $min_time ) {
			return;
		}
		?>
		<div class="countdown-timer <?php echo esc_attr( $min_time ? 'has-border' : '' ); ?>">
			<div class="countdown-timer__title">
				<?php
				/* translators: %s Sales end date */
				printf( esc_html__( 'Limited time offer. The deal will expire on %s HURRY UP!', 'springoo' ), '<span class="sale-date"><strong>' . esc_html( date( 'F j, Y', $min_time ) ) . '</strong></span>' );
				?>
			</div>
			<div class="countdown-timer__countdown d-flex align-items-center">
				<div class="springoo-sales-countdown" data-timer-end="<?php echo esc_attr( date( 'd.m.Y H:i T', $min_time ) ); ?>" data-label-placement="after" data-label-days="d" data-label-hours="h" data-label-minutes="m" data-label-seconds="s"></div>
			</div>
		</div>
		<?php
	}
}


if ( ! function_exists( 'woocommerce_product_loop_title' ) ) {
	/**
	 * Springoo Woocommerce Product Title
	 */
	function woocommerce_product_loop_title() {
		?>
		<a href="<?php echo esc_url( get_the_permalink() ); ?>"><span><?php esc_html( the_title() ); ?></span></a>
		<?php
	}
}

if ( ! function_exists( 'springoo_wc_default_product_tabs' ) ) {
	/**
	 * Springoo Woocommerce Product Tabs
	 *
	 * @param array $tabs
	 *
	 * @return array
	 */
	function springoo_wc_default_product_tabs( $tabs = array() ) {
		global $product;
		// Reviews tab - shows comments.
		if ( comments_open() ) {
			/* translators: %s Review Count */
			$tabs['reviews']['title'] = sprintf( __( 'Customer Reviews (%d)', 'springoo' ), $product->get_review_count() );
		}

		return $tabs;
	}
}

if ( ! function_exists( 'springoo_wc_available_variation' ) ) {
	/**
	 * Springoo Woocommerce Available Variation
	 *
	 * @param array $args
	 * @param WC_Product $product
	 * @param WC_Product_Variation $variation
	 *
	 * @return array
	 */
	function springoo_wc_available_variation( $args, $product, $variation ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed
		$variation_id  = $variation->get_id();
		$sale_end_date = get_post_meta( $variation_id, '_sale_price_dates_to', true );
		$sale_price    = $variation->get_sale_price();
		$regular_price = $variation->get_regular_price();
		$prefix        = springoo_get_mod( 'woocommerce_general_discount_label_text_prefix' );
		$suffix        = springoo_get_mod( 'woocommerce_general_discount_label_text_suffix' );
		// Discount percentage
		if ( ! empty( $sale_price ) && $regular_price > $sale_price ) {
			$discount         = ( (float) $regular_price - (float) $sale_price ) / (float) $regular_price * 100;
			$discount         = round( $discount, 0 );
			$args['discount'] = $prefix . $discount . $suffix;
		} else {
			$args['discount'] = '';
		}

		$args['product_id']               = $product->get_id();
		$args['end_time_title']           = ( $sale_end_date ) ? date( 'F j, Y', $sale_end_date ) : '';
		$args['end_time_countdown']       = ( $sale_end_date ) ? date( 'd.m.Y H:i', $sale_end_date ) . ' ' . date( 'T', $sale_end_date ) : '';
		$args['is_manage_stock']          = $variation->get_manage_stock();
		$args['is_variable_manage_stock'] = $product->get_manage_stock();
		$args['initial_stock']            = intval( get_post_meta( $variation_id, '_initial_stock_quantity', true ) );
		$args['total_stock']              = $variation->get_stock_quantity();

		return $args;
	}
}

if ( ! function_exists( 'springoo_filter_shop' ) ) {
	/**
	 * Function Name        : springoo_filter_shop
	 * Function Hooked      : springoo_filter_shop
	 * Function return Type : html
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_filter_shop() {
		if ( is_shop() || is_product_category() ) {
			?>
			<div id="filter-toggle">
				<div class="container">
					<div class="d-flex align-items-center justify-content-between py-3">
						<a href="#" id="springoo-filter-close" role="button" aria-controls="filter-toggle">
							<i class="si si-thin-close" aria-hidden="true"></i>
							<span class="sr-only"><?php esc_html_e( 'Close Filter Toggle', 'springoo' ); ?></span>
						</a>
					</div>
					<div class="widget-area main-sidebar">
						<?php dynamic_sidebar( 'shop-sidebar' ); ?>
					</div>
				</div>
			</div><!-- /filter-toggle -->
			<?php
		}
	}
}

if ( ! function_exists( 'springoo_filter_backdrop_overlay' ) ) {
	/**
	 * Filter toggle overlay
	 *
	 * @return void
	 */
	function springoo_filter_backdrop_overlay() {
		if ( is_shop() || is_product_category() && springoo_get_mod( 'layout_header_mobile_backdrop_bg' ) ) {
			echo "<div class='springoo-filter-overlay'></div>";
		}
	}
}

/**
 * Springoo product search ajax
 */
function springoo_search_product() {
	check_ajax_referer( 'springoo-nonce', 'nonce' );

	$keywords = ! empty( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
	$category = ! empty( $_REQUEST['cat'] ) ? sanitize_text_field( $_REQUEST['cat'] ) : '';

	if ( strlen( $keywords ) >= 3 ) {
		$products = wc_get_products(
			array(
				's'        => $keywords,
				'category' => $category,
			)
		);

		if ( empty( $products ) ) {
			wp_send_json_error( __( 'No Match Found.', 'springoo' ) );
		}

		$data = array();
		/**
		 * @type WC_Product[] $products
		 */
		foreach ( $products as $product ) {

			$_data = array(
				'id'          => $product->get_id(),
				'title'       => $product->get_title(),
				'link'        => get_permalink( $product->get_id() ),
				'thumbnail'   => $product->get_image( 'springoo-search-image' ),
				'price'       => $product->get_price_html(),
				'cart_btn'    => $product->add_to_cart_url(),
				'type'        => $product->get_type(),
				'rating'      => null,
				'product_cat' => json_decode( stripslashes( $category ) ),
			);

			if ( wc_review_ratings_enabled() ) {

				$rating_count = $product->get_rating_count();
				$review_count = $product->get_review_count();
				$average      = $product->get_average_rating();

				if ( $rating_count > 0 ) {
					$_data['rating'] .= '<div class="springoo-product-rating">';
					$_data['rating'] .= wp_kses_post( wc_get_rating_html( $average, $rating_count ) );
					$_data['rating'] .= '<span class="woocommerce-review-label">';
					/* translators: %s: Review Count */
					$_data['rating'] .= '(' . sprintf( _n( '%s review', '%s reviews', $review_count, 'springoo' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ) . ')';
					$_data['rating'] .= '</span></div>';
				}
			}

			$data[] = $_data;
		}

		wp_send_json_success( $data );
	}
}

function custom_wc_page_title( $page_title ) {
	if ( is_search() ) {
		/* translators: Product search result title */
		$page_title = sprintf( __( 'Search results for: &ldquo;%s&rdquo;', 'springoo' ), get_search_query() );
	}

	return $page_title;
}

if ( ! function_exists( 'springoo_product_loop_gallery' ) ) {
	function springoo_product_loop_gallery() {
		global $product;
		$attachment_ids = $product->get_gallery_image_ids();
		$gallery_class  = ( ! empty( $attachment_ids ) ) ? 'springoo-product-gallery' : '';

		?>
		<div class="springoo-product-thumbnail <?php echo esc_attr( $gallery_class ); ?>">

			<?php
			if ( empty( $attachment_ids ) ) {
				springoo_the_post_thumbnail(
					'springoo-shop-image',
					array(
						'wrapper'   => false,
						'auto_size' => true,
					)
				);
			} else {
				$uid       = wp_unique_id( 'gallery-slider-id-' ) . uniqid( time() );
				$no_slides = 0;
				?>
				<div id="<?php echo esc_attr( $uid ); ?>" class="carousel slide" data-bs-ride="false" data-bs-interval="false" data-bs-touch="true">
					<div class="carousel-inner">
						<div class="carousel-item active">
							<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_post_thumbnail( $product->get_id() ); ?></a>
						</div>
						<?php
						foreach ( $attachment_ids as $attachment_id ) {
							$no_slides ++; ?>
							<div class="carousel-item ">
								<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo wp_get_attachment_image( $attachment_id, 'full' ); ?></a>
							</div>
						<?php } ?>
					</div>
					<button class="carousel-control-prev" type="button" data-bs-target="#<?php echo esc_attr( $uid ); ?>" data-bs-slide="prev">
						<i class="si si-thin-arrow-left" aria-hidden="true"></i>
						<span class="visually-hidden">Previous</span>
					</button>
					<button class="carousel-control-next" type="button" data-bs-target="#<?php echo esc_attr( $uid ); ?>" data-bs-slide="next">
						<i class="si si-thin-arrow-right" aria-hidden="true"></i>
						<span class="visually-hidden">Next</span>
					</button>
				</div>
			<?php } ?></div>
		<?php
	}
}


if ( ! function_exists( 'springoo_custom_product_get_image' ) ) {
	/**
	 * @param $image
	 * @param $product
	 *
	 * @return string
	 */
	function springoo_custom_product_get_image( $image, $product ) {
		// Modify the image or replace it with a custom image
		// Example: Change the image size to 'thumbnail'
		$image = wp_get_attachment_image( $product->get_image_id(), 'springoo-wc-thumbnail' );

		return $image;
	}
}

// End of file springoo -woocommerce-hook-helper.php

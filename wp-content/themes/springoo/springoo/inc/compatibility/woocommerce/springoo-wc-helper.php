<?php
/**
 * WooCommerce Integrations.
 *
 * @package Springoo
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! function_exists( 'springoo_wc_color_variation_attribute_options' ) ) :
	/**
	 * Color Variation Attribute Options
	 *
	 * @param array $args
	 *
	 * @return void
	 */
	function springoo_wc_color_variation_attribute_options( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'options'          => false,
				'attribute'        => false,
				'product'          => false,
				'selected'         => false,
				'name'             => '',
				'id'               => '',
				'class'            => '',
				'show_option_none' => esc_html__( 'Choose an option', 'springoo' ),
			)
		);

		$options   = $args['options'];
		$product   = $args['product'];
		$attribute = $args['attribute'];
		$name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
		$id        = $args['id'] ? $args['id'] : sanitize_title( $attribute ) . $product->get_id();
		$class     = $args['class'];

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . ' hide" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';

		if ( $args['show_option_none'] ) {
			echo '<option value="">' . esc_html( $args['show_option_none'] ) . '</option>';
		}

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				// Get terms if this is a taxonomy - ordered. We need the names too.
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options ) ) {
						?>
						<option
							value="<?php esc_attr( $term->slug ); ?>"<?php selected( sanitize_title( $args['selected'] ), $term->slug ); ?>><?php echo apply_filters( 'woocommerce_variation_option_name', $term->name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></option>
						<?php
					}
				}
			}
		}

		echo '</select>';

		echo '<ul class="list-inline variable-items-wrapper color-variable-wrapper color-variation">';
		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options ) ) {
						$get_term_meta  = get_term_meta( $term->term_id, 'color', true );
						$selected_class = ( sanitize_title( $args['selected'] ) == $term->slug ) ? 'selected' : '';
						if ( ! empty( $get_term_meta ) ) {
							?>
							<li data-placement="top"
								class="variable-item color-variable-item color-variable-item-<?php echo esc_attr( $term->slug ); ?> <?php echo esc_attr( $selected_class ); ?>"
								data-value="<?php echo esc_attr( $term->slug ); ?>">
								<span class="v-color <?php echo esc_attr( $term->slug ); ?> " data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php /* translators: %s: Size Attribute Name for tooltip. */printf( esc_html__( 'Color: %s', 'springoo' ), esc_html( $term->name ) ); ?>" tyle="background-color:<?php echo esc_attr( $get_term_meta ); ?>;"></span>
							</li>
						<?php } else { ?>
							<li data-placement="top"
								class="variable-item color-variable-item color-variable-item-<?php echo esc_attr( $term->slug ); ?> <?php echo esc_attr( $selected_class ); ?>"
								data-value="<?php echo esc_attr( $term->slug ); ?>">
								<span class="v-color <?php echo esc_attr( $term->slug ); ?> " data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php /* translators: %s: Size Attribute Name for tooltip. */printf( esc_html__( 'Color: %s', 'springoo' ), esc_html( $term->name ) ); ?>" style="background-color:<?php echo esc_attr( $term->slug ); ?>;"></span>
							</li>
							<?php
						}
					}
				}
			}
		}
		echo '</ul>';
	}

endif;

if ( ! function_exists( 'springoo_wc_size_variation_attribute_options' ) ) :
	/**
	 * Image Variation Attribute Options
	 *
	 * @param array $args
	 *
	 * @return void
	 */
	function springoo_wc_size_variation_attribute_options( $args = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'options'          => false,
				'attribute'        => false,
				'product'          => false,
				'selected'         => false,
				'name'             => '',
				'id'               => '',
				'class'            => '',
				'show_option_none' => esc_html__( 'Choose an option', 'springoo' ),
			)
		);

		$options   = $args['options'];
		$product   = $args['product'];
		$attribute = $args['attribute'];
		$name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
		$id        = $args['id'] ? $args['id'] : sanitize_title( $attribute ) . $product->get_id();
		$class     = $args['class'];

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . ' hide" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';

		if ( $args['show_option_none'] ) {
			echo '<option value="">' . esc_html( $args['show_option_none'] ) . '</option>';
		}

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				// Get terms if this is a taxonomy - ordered. We need the names too.
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options ) ) {
						?>
						<option
							value="<?php esc_attr( $term->slug ); ?>"<?php selected( sanitize_title( $args['selected'] ), $term->slug ); ?>><?php echo apply_filters( 'woocommerce_variation_option_name', $term->name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></option>
						<?php
					}
				}
			}
		}

		echo '</select>';

		echo '<ul class="list-inline variable-items-wrapper image-variable-wrapper size-variation">';
		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options ) ) {
						$term_meta      = get_term_meta( $term->term_id, 'size', true );
						$selected_class = ( sanitize_title( $args['selected'] ) == $term->slug ) ? 'selected' : '';
						if ( ! empty( $term_meta ) ) {
							?>
							<li>
								<span class="v-size" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Size: S">S</span>
							</li>
							<li class="variable-item image-variable-item image-variable-item-<?php echo esc_attr( $term->slug ); ?> <?php echo esc_attr( $selected_class ); ?>"
								data-value="<?php echo esc_attr( $term->slug ); ?>">
								<span class="v-size" data-bs-toggle="tooltip" data-bs-placement="bottom" title="
								<?php
								/* translators: %s: Size Attribute Name for tooltip. */
								printf( esc_html__( 'Size: %s', 'springoo' ), esc_html( $term->name ) );
								?>
								">
								<img alt="<?php echo esc_attr( $term->name ); ?>" src="<?php echo esc_url( $term_meta['url'] ); ?>">
								</span>
							</li>
						<?php } else { ?>
							<li class="variable-item image-variable-item image-variable-item-<?php echo esc_attr( $term->slug ); ?> <?php echo esc_attr( $selected_class ); ?>"
								data-value="<?php echo esc_attr( $term->slug ); ?>">
								<span class="v-size" data-bs-toggle="tooltip" data-bs-placement="bottom" title="
								<?php
								/* translators: %s: Size Attribute Name for tooltip. */
								printf( esc_html__( 'Size: %s', 'springoo' ), esc_html( $term->name ) );
								?>
								"><?php echo esc_html( $term->name ); ?></span>
							</li>
							<?php
						}
					}
				}
			}
		}
		echo '</ul>';
	}
endif;
if ( ! function_exists( 'springoo_shop_views' ) ) {
	function springoo_shop_views() {
		$columns = absint( springoo_get_mod( 'woocommerce_shop_archive_column' ) );
		?>
		<div class="springoo_shop_views">
			<a href="javascript:void(0);" class="springoo-change-layout <?php echo 2 === $columns ? esc_attr(' active') : ''; ?>" data-columns="2">
				<i class="si si-thin-2-column" aria-hidden="true"></i>
			</a>
			<a href="javascript:void(0);" class="springoo-change-layout <?php echo 3 === $columns ? esc_attr(' active') : ''; ?>" data-columns="3">
				<i class="si si-thin-3-column" aria-hidden="true"></i>
			</a>
			<a href="javascript:void(0);" class="springoo-change-layout <?php echo 4 === $columns ? esc_attr(' active') : ''; ?>" data-columns="4">
				<i class="si si-thin-4-column" aria-hidden="true"></i>
			</a>
		</div>
		<?php
	}
}
//single product reviews
if ( ! function_exists( 'springoo_count_ratings' ) ) {
	/**
	 * Springoo product reviews count
	 *
	 * @param $product_id
	 * @param $rating
	 *
	 * @return int|int[]|WP_Comment[]
	 */
	function springoo_count_ratings( $product_id, $rating ) {
		//will add language support
		$post_in = $product_id;

		$args = array(
			'post__in'     => $post_in,
			'post_status'  => 'publish',
			'status'       => 'approve',
			'parent'       => 0,
			'count'        => true,
			'type__not_in' => 'cr_qna',
		);
		if ( 0 === $rating ) {
			$args['meta_query'][] = array(
				'key'     => 'rating',
				'value'   => 0,
				'compare' => '>',
				'type'    => 'numeric',
			);
		} elseif ( $rating > 0 ) {
			$args['meta_query'][] = array(
				'key'     => 'rating',
				'value'   => $rating,
				'compare' => '=',
				'type'    => 'numeric',
			);
		}

		return get_comments( $args );
	}
}

if ( ! function_exists( 'springoo_reviews_summery' ) ) {
	/**
	 * Springoo Reviews Summery
	 *
	 * @param $product_id
	 *
	 * @return void
	 */
	function springoo_reviews_summery( $product_id ) {
		$average = 0;
		$product = wc_get_product( $product_id );
		if ( $product ) {
			$average = $product->get_average_rating();
		}
		$all = springoo_count_ratings( $product_id, 0 );
		?>
		<div class="reviews__summery">
			<div class="rating-count">
				<?php
				/* translators: %s Review Count. */
				printf( esc_html( _n( 'Customer Review (%s)', 'Customer Reviews (%s)', $all, 'springoo' ) ), esc_html( number_format_i18n( $all ) ) );
				?>
			</div>
			<div class="average-rating">
				<span class="average">
					<?php echo esc_html( number_format_i18n( $average, 1 ) ); ?>
				</span>
				<span class="total">
					<?php echo '/' . esc_html( $all ); ?>
				</span>
			</div>
			<div class="rating-stars">
				<?php echo wc_get_rating_html( $average ); // phpcs:ignore. ?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_reviews_summery_details' ) ) {
	/**
	 * Springoo Reviews Summery Details
	 *
	 * @param $product_id
	 *
	 * @return void
	 */
	function springoo_reviews_summery_details( $product_id ) {
		$all = springoo_count_ratings( $product_id, 0 );
		?>
		<div class="reviews-summery__details">
			<ul>
				<?php
				for ( $i = 5; $i >= 1; $i -- ) {
					$percent = 0;
					if ( $all > 0 ) {
						$star    = (float) springoo_count_ratings( $product_id, $i );
						$percent = floor( $star / $all * 100 );
					}
					?>
					<li>
						<div class="progress-title">
							<?php echo wc_get_rating_html( $i ); // phpcs:ignore. ?>
						</div>
						<div class="progress-wrap">
							<div class="progress" style="width:<?php echo esc_attr( $percent ) . '%'; ?>"></div>
						</div>
						<div class="percent">
							<?php echo esc_html( $percent ) . '%'; ?>
						</div>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_reviews_summery_note' ) ) {
	/**
	 * Springoo Reviews Summery note
	 *
	 * @return void
	 */
	function springoo_reviews_summery_note() {
		if ( 0 === springoo_get_mod( 'woocommerce_single_reviews_note_enable' ) ) {
			return;
		}
		$note_label = springoo_get_mod( 'woocommerce_single_reviews_note_label' );
		$note       = springoo_get_mod( 'woocommerce_single_reviews_note_content' );
		?>
		<div class="reviews-summery__note">

			<?php
			if ( $note_label ) {
				echo '<p><span class="prefix">' . esc_html__( 'Note: ', 'springoo' ) . '</span>' . esc_html( $note_label ) . '</p>';
			}

			if ( $note ) {
				$notes = explode( ',', $note );

				echo '<ul>';
				foreach ( $notes as $single_note ) {
					echo '<li>' . esc_html( $single_note ) . '</li>';
				}
				echo '</ul>';
			}
			?>
		</div>
		<?php
	}
}


if ( ! function_exists( 'springoo_change_products_query_for_page' ) ) {

	/**
	 * Change products query for page
	 *
	 * @param object $query
	 *
	 * @return void
	 */

	function springoo_change_products_query_for_page( $query ) {

		$per_page = filter_input( INPUT_GET, 'per-page', FILTER_SANITIZE_NUMBER_INT );

		if ( empty( $per_page ) ) {
			$per_page = springoo_get_mod( 'woocommerce_shop_archive_per_page' );
		}
		if ( $query->is_main_query() && ! is_admin() && is_post_type_archive( 'product' ) ) :
			$query->set( 'posts_per_page', $per_page );
		endif;
	}
}

if ( ! function_exists( 'springoo_review_image_settings' ) ) {
	/**
	 * Review Image Settings
	 *
	 * @param $settings
	 *
	 * @return array
	 */
	function springoo_review_image_settings( $settings ) {

		$settings[] = array(
			'title' => '',
			'type'  => 'title',
			'desc'  => '',
			'id'    => 'product_rating_image_options',
		);
		$settings[] = array(
			'title'           => __( 'Enable Review Gallery', 'springoo' ),
			'desc'            => __( 'Allow customer to upload images with their review (comments).', 'springoo' ),
			'id'              => 'woocommerce_enable_review_image',
			'default'         => 'yes',
			'type'            => 'checkbox',
			'checkboxgroup'   => 'start',
			'show_if_checked' => 'option',
		);
		$settings[] = array(
			'desc'            => __( 'Allow only verified customers to upload image.', 'springoo' ),
			'id'              => 'woocommerce_review_image_allow_if_verified',
			'default'         => 'no',
			'type'            => 'checkbox',
			'checkboxgroup'   => '',
			'show_if_checked' => 'yes',
		);

		$settings[] = array(
			'desc'            => __( 'Allow only logged in customers to upload image.', 'springoo' ),
			'id'              => 'woocommerce_review_image_allow_if_logged_in',
			'default'         => 'no',
			'type'            => 'checkbox',
			'checkboxgroup'   => 'end',
			'show_if_checked' => 'yes',
		);
		$settings[] = array(
			'title'           => __( 'Maximum number of Images', 'springoo' ),
			'desc'            => __( 'Number of images customer allowed to upload in each review.', 'springoo' ),
			'id'              => 'woocommerce_review_image_number',
			'default'         => 5,
			'type'            => 'number',
			'show_if_checked' => 'yes',
			'autoload'        => false,
			'desc_tip'        => true,
		);
		$settings[] = array(
			'title'           => __( 'Maximum file size (MB)', 'springoo' ),
			'desc'            => __( 'Maximum size allowed per image. File size in MB.', 'springoo' ),
			'id'              => 'woocommerce_review_image_file_size',
			'default'         => 2,
			'type'            => 'number',
			'show_if_checked' => 'yes',
			'autoload'        => false,
			'desc_tip'        => true,
		);
		$settings[] = array(
			'type' => 'sectionend',
			'id'   => 'product_rating_image_options',
		);

		return $settings;
	}
}

if ( ! function_exists('springoo_product_search') ) {
	/**
	 * @render Product Search
	 */
	function springoo_product_search() {
		$uid = wp_unique_id('springoo-product-search-');
		?>
		<form class="springoo-product-search woocommerce-product-search" data-uid="<?php echo esc_attr($uid); ?>" action="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" method="get" autocomplete="off" role="search">
			<fieldset>
				<label for="<?php echo esc_attr($uid); ?>-keyword" class="screen-reader-text"><?php echo esc_html__('Search', 'springoo'); ?></label>
				<div class="search-wrapper">
					<input name="s" id="<?php echo esc_attr($uid); ?>-keyword" class="springoo-search-input" type="text" placeholder="<?php echo esc_attr__('Search for products...', 'springoo'); ?>" autocomplete="off" aria-label="<?php echo esc_attr__('Search', 'springoo'); ?>" value="<?php echo get_search_query(); ?>">
					<input type="hidden" name="post_type" value="product">
					<button type="submit">
						<i class="si si-thin-search-normal" aria-hidden="true"></i>
						<span class="sr-only"><?php _e('Search', 'springoo'); ?></span>
					</button>
				</div>
				<div class="search_result woocommerce" style="display:none">
					<ul class="search_result_inner products columns-5 has-grid-gap"></ul>
				</div>
				<script type="text/html" id="tmpl-<?php echo esc_attr($uid); ?>-search-result">
					<li class="single-product product product-{{ data.id }}">
						<a href="{{ data.link }}">
							<div class="thumbnail">{{{ data.thumbnail }}}</div> <?php // phpcs:ignore WordPressVIPMinimum.Security.Mustache.OutputNotation ?>
							<div class="summery">
								<div class="product-content">
									<h4 class="product-title">{{ data.title }}</h4> <?php // phpcs:ignore WordPressVIPMinimum.Security.Mustache.OutputNotation ?>
									<# if ( data.rating ) { #>
									<div class="springoo-product-rating">{{{ data.rating }}}</div> <?php // phpcs:ignore WordPressVIPMinimum.Security.Mustache.OutputNotation ?>
									<# } #>
									<div class="springoo-product-price-wrapper inline">{{{ data.price }}}</div> <?php // phpcs:ignore WordPressVIPMinimum.Security.Mustache.OutputNotation ?>
								</div>
							</div>
						</a>
					</li>
				</script>
			</fieldset>
		</form>
		<?php
	}
}

if ( ! function_exists(  'springoo_product_category' ) ) {
	/**
	 * Product Category select option
	 *
	 * @return void
	 */
	function springoo_product_category() {
		$args           = array(
			'taxonomy'     => 'product_cat',
			'orderby'      => 'name',
			'hierarchical' => 1,
			'hide_empty'   => 0,
		);
		$all_categories = get_categories( $args );

		echo '<select class="product-category" name="product_category" aria-label="' . esc_attr__( 'Product category', 'springoo' ) . '">';
		echo '<option value="" selected="selected">' . esc_html__( 'All categories', 'springoo' ) . '</option>';

		foreach ( $all_categories as $category ) {
			echo '<option value="' . esc_attr( $category->slug ) . '">' . esc_html( $category->name ) . '</option>';
		}

		echo '</select>';
	}
}

function springoo_product_share_content() {
	?>
	<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: none">
		<defs>
			<symbol id="product-share-embed" viewBox="0 0 36 36" preserveAspectRatio="xMidYMid meet" focusable="false">
				<g>
					<circle cx="18" cy="18" r="17.5" stroke="#E7E7E7" fill="#F4F4F4" stroke-width=".5"></circle>
					<path d="m21.41,23.29l-0.71,-0.71l4.59,-4.58l-4.59,-4.59l0.71,-0.71l5.3,5.3l-5.3,5.29zm-6.12,-0.7l-4.58,-4.59l4.59,-4.59l-0.71,-0.7l-5.3,5.29l5.29,5.29l0.71,-0.7z" fill="#606060"></path>
				</g>
			</symbol>
			<symbol id="product-share-whatsapp" viewBox="0 0 60 60" preserveAspectRatio="xMidYMid meet" focusable="false" >
				<g>
					<g fill="none" fill-rule="evenodd">
						<circle cx="30" cy="30" r="30" fill="#25D366"></circle>
						<path d="M39.7746 19.3513C37.0512 16.5467 33.42 15 29.5578 15C21.6022 15 15.1155 21.6629 15.1155 29.8725C15.1155 32.4901 15.7758 35.0567 17.0467 37.3003L15 45L22.6585 42.9263C24.7712 44.1161 27.148 44.728 29.5578 44.728C37.5134 44.728 44 38.0652 44 29.8555C44 25.8952 42.498 22.1558 39.7746 19.3513ZM29.5578 42.2295C27.3956 42.2295 25.2829 41.6346 23.4508 40.5127L23.0051 40.2408L18.4661 41.4646L19.671 36.9093L19.3904 36.4334C18.1855 34.4618 17.5583 32.1841 17.5583 29.8555C17.5583 23.0397 22.9556 17.4986 29.5743 17.4986C32.7763 17.4986 35.7968 18.7904 38.0581 21.119C40.3193 23.4476 41.5737 26.5581 41.5737 29.8555C41.5572 36.6884 36.1764 42.2295 29.5578 42.2295ZM36.1434 32.966C35.7803 32.779 34.0142 31.8782 33.6841 31.7592C33.354 31.6402 33.1064 31.5722 32.8754 31.9462C32.6278 32.3201 31.9511 33.153 31.7365 33.4079C31.5219 33.6629 31.3238 33.6799 30.9607 33.4929C30.5976 33.306 29.4422 32.915 28.0558 31.6572C26.9829 30.6714 26.2567 29.4476 26.0421 29.0907C25.8275 28.7167 26.0256 28.5127 26.2072 28.3258C26.3722 28.1558 26.5703 27.8839 26.7518 27.6799C26.9334 27.4589 26.9994 27.3059 27.115 27.068C27.2305 26.813 27.181 26.6091 27.082 26.4221C26.9994 26.2351 26.2732 24.3994 25.9761 23.6686C25.679 22.9377 25.3819 23.0397 25.1673 23.0227C24.9528 23.0057 24.7217 23.0057 24.4741 23.0057C24.2265 23.0057 23.8469 23.0907 23.5168 23.4646C23.1867 23.8385 22.2459 24.7394 22.2459 26.5581C22.2459 28.3938 23.5333 30.1445 23.7149 30.3994C23.8964 30.6544 26.2567 34.3938 29.8714 36.0085C30.7297 36.3994 31.4064 36.6204 31.9345 36.7904C32.7928 37.0793 33.5851 37.0283 34.2123 36.9433C34.9055 36.8414 36.3415 36.0425 36.6551 35.1756C36.9522 34.3088 36.9522 33.5609 36.8697 33.4079C36.7541 33.255 36.5065 33.153 36.1434 32.966Z" fill="white"></path>
					</g>
				</g>
			</symbol>
			<symbol id="product-share-fb" viewBox="0 0 60 60" preserveAspectRatio="xMidYMid meet" focusable="false">
				<g>
					<g fill="none" fill-rule="evenodd">
						<path d="M28.4863253 59.9692983c-6.6364044-.569063-11.5630204-2.3269561-16.3219736-5.8239327C4.44376366 48.4721168 3e-7 39.6467924 3e-7 29.9869344c0-14.8753747 10.506778-27.18854591 25.2744118-29.61975392 6.0281072-.9924119 12.7038532.04926445 18.2879399 2.85362966C57.1386273 10.0389054 63.3436516 25.7618627 58.2050229 40.3239688 54.677067 50.3216743 45.4153135 57.9417536 34.81395 59.5689067c-2.0856252.3201125-5.0651487.5086456-6.3276247.4003916z" fill="#3B5998" fill-rule="nonzero"></path>
						<path d="M25.7305108 45h5.4583577V30.0073333h4.0947673l.8098295-4.6846666h-4.9045968V21.928c0-1.0943333.7076019-2.2433333 1.7188899-2.2433333h2.7874519V15h-3.4161354v.021c-5.3451414.194-6.4433395 3.2896667-6.5385744 6.5413333h-.0099897v3.7603334H23v4.6846666h2.7305108V45z" fill="#FFF"></path>
					</g>
				</g>
			</symbol>
			<symbol id="product-share-twitter" viewBox="0 0 60 60" preserveAspectRatio="xMidYMid meet" focusable="false">
				<g>
					<g fill="none" fill-rule="evenodd">
						<path d="M28.486325 59.969298c-6.636404-.569063-11.56302-2.326956-16.321973-5.823932C4.443764 48.472116 0 39.646792 0 29.986934 0 15.11156 10.506778 2.798388 25.274412.36718c6.028107-.992411 12.703853.049265 18.28794 2.85363 13.576275 6.818095 19.7813 22.541053 14.64267 37.103159-3.527955 9.997705-12.789708 17.617785-23.391072 19.244938-2.085625.320112-5.065149.508645-6.327625.400391z" fill="#1DA1F2" fill-rule="nonzero"></path>
						<path d="M45.089067 17.577067c-.929778.595555-3.064534 1.460977-4.117334 1.460977v.001778C39.7696 17.784 38.077156 17 36.200178 17c-3.645511 0-6.6016 2.956089-6.6016 6.600178 0 .50631.058666 1.000178.16711 1.473778h-.001066c-4.945066-.129778-10.353422-2.608356-13.609244-6.85049-2.001778 3.46489-.269511 7.3184 2.002133 8.72249-.7776.058666-2.209067-.0896-2.882844-.747023-.045156 2.299734 1.060622 5.346845 5.092622 6.452267-.776533.417778-2.151111.297956-2.7488.209067.209778 1.941333 2.928355 4.479289 5.901155 4.479289C22.46009 38.565156 18.4736 40.788089 14 40.080889 17.038222 41.929422 20.5792 43 24.327111 43c10.650667 0 18.921956-8.631822 18.4768-19.280356-.001778-.011733-.001778-.023466-.002844-.036266.001066-.027378.002844-.054756.002844-.0832 0-.033067-.002844-.064356-.003911-.096356.9696-.66311 2.270578-1.836089 3.2-3.37991-.539022.296888-2.156089.891377-3.6608 1.038932.965689-.521244 2.396444-2.228266 2.749867-3.585777" fill="#FFF"></path>
					</g>
				</g>
			</symbol>
			<symbol id="product-share-email" viewBox="0 0 60 60" preserveAspectRatio="xMidYMid meet" focusable="false">
				<g>
					<g fill-rule="nonzero" fill="none">
						<path d="M28.4863253 59.9692983c-6.6364044-.569063-11.5630204-2.3269561-16.3219736-5.8239327C4.44376366 48.4721168 3e-7 39.6467924 3e-7 29.9869344c0-14.8753747 10.506778-27.18854591 25.2744118-29.61975392 6.0281072-.9924119 12.7038532.04926445 18.2879399 2.85362966C57.1386273 10.0389054 63.3436516 25.7618627 58.2050229 40.3239688 54.677067 50.3216743 45.4153135 57.9417536 34.81395 59.5689067c-2.0856252.3201125-5.0651487.5086456-6.3276247.4003916z" fill="#888"></path>
						<path d="M40.531502 19.160814h-22c-1.74 0-2.986 1.2375-3 3v16c0 1.7625 1.26 3 3 3h22c1.74 0 3-1.2375 3-3v-16c0-1.7625-1.26-3-3-3zm0 6l-11 7-11-7v-3l11 7 11-7v3z" fill="#FFF"></path>
					</g>
				</g>
			</symbol>
			<symbol id="product-share-kakao" viewBox="0 0 60 60" preserveAspectRatio="xMidYMid meet" focusable="false">
				<g>
					<g fill-rule="nonzero" fill="none">
						<path d="M28.486325 59.9692983c-6.6364044-.569063-11.5630204-2.3269562-16.3219736-5.8239328C4.44376336 48.4721167 0 39.6467923 0 29.9869344 0 15.1115596 10.506778 2.79838844 25.2744118.36718043 31.302519-.62523147 37.978265.41644488 43.5623517 3.2208101 57.138627 10.0389054 63.3436513 25.7618627 58.2050226 40.3239688c-3.5279559 9.9977054-12.7897094 17.6177847-23.3910729 19.2449379-2.0856252.3201125-5.0651487.5086455-6.3276247.4003916z" fill="#FFE812"></path>
						<path d="M30.5 14C19.730375 14 11 20.69445394 11 28.952339c0 5.3388968 3.649875 10.0235376 9.14025 12.6688251-.2986875 1.0018068-1.9194375 6.4448229-1.9839375 6.8724233 0 0-.0388125.3212929.175125.4438292.2139375.1225362.4655625.0273518.4655625.0273518.6135-.0833319 7.1143125-4.5241766 8.2395-5.2953162 1.1240625.1548115 2.2815.2352259 3.4635.2352259 10.769625 0 19.5-6.6942716 19.5-14.9523391C50 20.69445394 41.269625 14 30.5 14z" fill="#000"></path>
						<path d="M20.11212489 33c-.64033041 0-1.16107056-.4353882-1.16107056-.9707294v-6.0386824h-1.81165709C16.51106456 25.9905882 16 25.5440188 16 24.9952941S16.51125807 24 17.13939724 24h5.94545526c.6283327 0 1.1393973.4465694 1.1393973.9952941s-.5112581.9952941-1.1393973.9952941h-1.8116571v6.0386824c0 .5353412-.5207401.9707294-1.16107051.9707294zm10.18104071-.0132141c-.4841664 0-.8545479-.1721224-.9662042-.4489412l-.5749235-1.3176847-3.5404911-.0001694-.5753105 1.3185318c-.1112692.2763105-.4814572.4482635-.9656237.4482635-.2546749.0002283-.5064123-.0476164-.7380538-.140273-.3200685-.1292611-.6277522-.484687-.2751737-1.4433882l2.7772807-6.3996988c.1956404-.48672.789915-.9881788 1.546159-1.0032565.7583726.0149082 1.3526472.5165365 1.5486746 1.004273l2.7761197 6.3968188c.3533525.9609035.0456688 1.3164988-.2743997 1.4454212-.2316966.0924919-.4834067.1402736-.7380538.1401035-.0001935 0 0 0 0 0zm-2.1516573-3.5671341l-1.1597159-2.8842353-1.159716 2.8842353h2.3194319zm5.0326604 3.4321129c-.6136258 0-1.1126927-.4181082-1.1126927-.9317647v-6.9035294c0-.5605835.5317704-1.0164706 1.1852596-1.0164706s1.1852595.4558871 1.1852595 1.0164706v5.9717647H36.89927c.6136258 0 1.1126926.4181082 1.1126926.9317647s-.4990668.9317647-1.1126926.9317647h-3.7251013zm6.4505209.1350212c-.6403304 0-1.1610705-.4558871-1.1610705-1.0164706v-6.9538447c0-.5605835.5207401-1.0164706 1.1610705-1.0164706.6403305 0 1.1610706.4558871 1.1610706 1.0164706v2.1847341l3.2393869-2.8359529c.1666136-.1458636.395538-.2261647.6440071-.2261647.2898806 0 .5809223.10944.7990101.3001976.2033808.1778824.3247127.4067577.3413547.6444424.0168355.2397176-.0743085.4594447-.2562096.6188611l-2.6458863 2.3160283 2.8579752 3.3147106c.1863887.2147949.2666819.4860225.2229256.7530353-.0418059.2671791-.2040382.5085898-.4504954.6703623-.2007827.1336077-.4461848.2056972-.698384.2051577-.3648049.0014863-.7088533-.1483913-.9275018-.4040471l-2.722904-3.1585129-.4028915.3527152v2.2177695c-.0007462.5613249-.5202804 1.016232-1.1614576 1.0169788z" fill="#FFE812"></path>
					</g>
				</g>
			</symbol>
			<symbol id="product-share-reddit" viewBox="0 0 60 60" preserveAspectRatio="xMidYMid meet" focusable="false">
				<g>
					<g fill-rule="nonzero" fill="none">
						<path d="M28.4863253 59.9692983c-6.6364044-.569063-11.5630204-2.3269561-16.3219736-5.8239327C4.44376366 48.4721168 3e-7 39.6467924 3e-7 29.9869344c0-14.8753747 10.506778-27.18854591 25.2744118-29.61975392 6.0281072-.9924119 12.7038532.04926445 18.2879399 2.85362966C57.1386273 10.0389054 63.3436516 25.7618627 58.2050229 40.3239688 54.677067 50.3216743 45.4153135 57.9417536 34.81395 59.5689067c-2.0856252.3201125-5.0651487.5086456-6.3276247.4003916z" fill="#FF4500"></path>
						<path d="M34.1335847 43.9991814c1.6336774-.3831682 2.81654-.7939438 3.9781753-1.3815065 3.6153903-1.8286959 5.8788354-4.8645264 5.8788354-7.8849481 0-.9131088.0196207-.9556355.780668-1.6923265.6040409-.5847092.8404012-.962515 1.044679-1.6698428.2433185-.8425206.2441028-.9826228.0100139-1.7878614-.6566532-2.2588075-3.0054252-3.2764371-5.1075029-2.212882l-.8124519.4110627-.837264-.5151716c-1.6101652-.9907471-4.473974-1.96108051-6.3205111-2.14155924-.5059537-.04945042-1.143803-.1235464-1.4174535-.16465815l-.4975382-.07474783.2136595-1.06953332c.1175125-.5882423.3623778-1.7491883.5441475-2.57987956.181767-.83069153.4095191-1.95749071.5061138-2.50399817.1396137-.78991765.2348596-.99365031.4645331-.99365031.1589005 0 1.2955101.21462853 2.5257988.4769522 1.2302915.26232367 2.2622022.47695193 2.293134.47695193.0309345 0 .1003234.23497925.154203.52217616.1363137.72661143.4902897 1.17780487 1.2134714 1.54674469 1.7356128.8854428 3.6891485-.29431302 3.6891485-2.22790474 0-2.3956262-2.9538443-3.44186625-4.4550019-1.57794377-.2901205.36022881-.4356746.42625561-.7154284.32453216-.1931649-.07023833-1.5202546-.3669593-2.9490951-.65938053-1.8923676-.38728585-2.6686067-.48706487-2.8582979-.36740978-.2896185.1826864-.2522224.047706-1.0501419 3.79055007-.9490696 4.45187338-1.0064011 4.70400786-1.0882029 4.78580828-.0432388.04324046-.729551.14596475-1.5251367.22827402-2.413687.24971784-5.06621906 1.10194849-6.8544721 2.20227189l-.8268564.5087695-.81204644-.4046587c-2.11972653-1.0563058-4.47243958-.0382468-5.1287215 2.2192841-.23408885.8052386-.23330375.9453408.0100166 1.7878614.20427624.7073278.44063816 1.0851336 1.04467744 1.6698428.76105187.736691.78066878.7792177.78066878 1.6923265 0 4.172347 4.28816886 8.1540991 10.01599352 9.3002929 1.8975637.3797217 2.0263168.3894072 4.4515526.3349143 1.5849893-.0356103 2.7274992-.1508298 3.6566327-.3687526zm-6.6864685-3.0300366c-1.3154638-.2961613-2.8032079-.9569232-3.2391341-1.4386156-.34760429-.384099-.198986-.9659493.246724-.9659493.1736296 0 .5801788.1805458.9034441.4012095 2.1828306 1.4900284 7.1085991 1.484628 9.2987021-.0101767.7054363-.4814861 1.2827246-.468194 1.3538857.0311679.0334854.234998-.1164569.4687666-.4771278.7438635-1.7511846 1.3356924-5.2646974 1.8738181-8.086494 1.2385197v-.000019zm-4.01196195-5.9075609c-.81902983-.4443886-1.22403999-1.1532958-1.22477787-2.143771-.000787-1.135302.52653614-1.8699603 1.59778946-2.2257735.76240766-.253232.85344696-.2532238 1.51689516.0001492 1.785415.6818561 2.1507834 2.909296.6724678 4.0996229-.7705198.6204159-1.7299516.7214286-2.56237455.2697724zm11.09588945.0732995c-.7590038-.3947507-1.1832989-.8746601-1.3628596-1.5414995-.3321829-1.2336253.2931784-2.4377233 1.5067529-2.9011953.6634485-.253373.754488-.2533812 1.5168976-.0001492 1.0712525.3558132 1.5986343 1.0904715 1.5977903 2.2257735-.0008141 1.0085002-.4138259 1.7116813-1.2587076 2.1427072-.7599699.3877084-1.3548626.4098285-1.9998736.0743633z" fill="#FDFDFD"></path>
					</g>
				</g>
			</symbol>
			<symbol id="product-share-vk" viewBox="0 0 60 60" preserveAspectRatio="xMidYMid meet" focusable="false">
				<g>
					<g fill="none" fill-rule="evenodd">
						<circle fill="#4680C2" fill-rule="nonzero" cx="30" cy="30" r="30"></circle>
						<path d="M49.06121 21.60996c.28897-.90818 0-1.56868-1.27972-1.56868h-4.25195c-1.07331 0-1.56869.57794-1.85765 1.19715 0 0-2.1879 5.28399-5.2427 8.71032-.99076.99075-1.44485 1.321-1.9815 1.321-.28897 0-.6605-.33025-.6605-1.23843v-8.46264c0-1.0733-.33025-1.56868-1.23844-1.56868h-6.68754c-.6605 0-1.07331.49537-1.07331.99075 0 1.03203 1.5274 1.27971 1.69253 4.1694v6.27473c0 1.36227-.24769 1.60996-.78434 1.60996-1.44484 0-4.95374-5.32527-7.05908-11.3936C18.2242 20.4541 17.8114 20 16.73808 20h-4.29324C11.20641 20 11 20.57794 11 21.19715c0 1.1146 1.44484 6.72883 6.72883 14.15943 3.5089 5.07758 8.50391 7.80214 13.00355 7.80214 2.72456 0 3.0548-.61922 3.0548-1.65125v-3.83914c0-1.23844.2477-1.44484 1.1146-1.44484.61922 0 1.7338.33025 4.25196 2.76583C42.04342 41.879 42.53879 43.2 44.14875 43.2h4.25196c1.23844 0 1.81637-.61922 1.48612-1.81637-.37153-1.19715-1.77509-2.93096-3.59146-4.99502-.99074-1.15587-2.47686-2.43559-2.93096-3.0548-.61921-.82562-.45409-1.15587 0-1.89893-.04128 0 5.16015-7.34805 5.6968-9.82492" fill="#FFFFFF"></path>
					</g>
				</g>
			</symbol>
			<symbol id="product-share-ok" viewBox="0 0 60 60" preserveAspectRatio="xMidYMid meet" focusable="false">
				<g>
					<g fill="none" fill-rule="evenodd">
						<circle fill="#F7931F" cx="30" cy="30" r="30"></circle>
						<path d="M30.02666667 16.01333333c3.71666663.01 6.70333333 3.05 6.68333333 6.81333334C36.69 26.5 33.6566667 29.4766667 29.94333333 29.4633333c-3.67666666-.0133333-6.69-3.06-6.66666666-6.73999997.02333333-3.72333333 3.04-6.72 6.75-6.71zm-.01 10.01666667C31.84 26.0233333 33.2933333 24.56 33.2866667 22.73333333 33.28 20.90666667 31.82333333 19.45 30 19.45c-1.84-.00333333-3.31 1.48-3.29333333 3.32333333.01333333 1.82 1.48333333 3.26333337 3.31 3.25666667zM37.56 32.1066667C36.7366667 32.95 35.7466667 33.5633333 34.65 33.99c-1.04.4033333-2.1766667.6066667-3.30666667.74.17.1866667.25.2766667.35666667.3833333 1.53 1.5366667 3.0666667 3.07 4.5933333 4.61.52.5266667.63 1.1766667.3433334 1.7866667-.3133334.67-1.0133334 1.1066667-1.7 1.06-.4366667-.03-.7733334-.2466667-1.0766667-.55-1.1566667-1.1633333-2.33333333-2.3033333-3.46333333-3.4866667-.33-.3433333-.48666667-.28-.78.02-1.16333334 1.1966667-2.34 2.3733334-3.53 3.5433334-.53333334.5266666-1.16666667.62-1.78666667.32-.65666667-.32-1.07666667-.99-1.04333333-1.6633334.02333333-.4566666.24666666-.8033333.56-1.1166666C25.33 38.1233333 26.84 36.61 28.35333333 35.0933333c.1-.1.19333334-.2066666.34-.3633333-2.06-.2166667-3.91666666-.7233333-5.50666666-1.9666667-.19666667-.1533333-.4-.3033333-.58333334-.4766666-.69666666-.67-.76666666-1.4333334-.21333333-2.2233334.47-.6733333 1.26333333-.8566666 2.08333333-.47.16.0733334.31.17.45666667.27 2.96333333 2.0366667 7.03666667 2.0933334 10.01.0933334.2933333-.2233334.61-.41.9766667-.5033334.71-.1833333 1.37.0766667 1.7533333.7.4333333.71.4266667 1.4-.11 1.9533334z" fill="#FFF" fill-rule="nonzero"></path>
					</g>
				</g>
			</symbol>
			<symbol id="product-share-pinterest" viewBox="0 0 60 60" preserveAspectRatio="xMidYMid meet" focusable="false">
				<g>
					<g fill="none" fill-rule="evenodd">
						<path d="M28.4863253 59.9692983c-6.6364044-.569063-11.5630204-2.3269561-16.3219736-5.8239327C4.44376366 48.4721168 3e-7 39.6467924 3e-7 29.9869344c0-14.8753747 10.506778-27.18854591 25.2744118-29.61975392 6.0281072-.9924119 12.7038532.04926445 18.2879399 2.85362966C57.1386273 10.0389054 63.3436516 25.7618627 58.2050229 40.3239688 54.677067 50.3216743 45.4153135 57.9417536 34.81395 59.5689067c-2.0856252.3201125-5.0651487.5086456-6.3276247.4003916z" fill="#BD081C" fill-rule="nonzero"></path>
						<path d="M30 14c-8.8359111 0-16 7.1714944-16 16.0165392 0 6.5927634 3.9804444 12.254788 9.6650667 14.7135047-.0408889-1.1165307-.0010667-2.448039.2812444-3.6553302.3104-1.3030344 2.0682667-8.7589113 2.0682667-8.7589113s-.5134222-1.02755-.5134222-2.5444942c0-2.3797018 1.3806222-4.160741 3.0965333-4.160741 1.4609778 0 2.1671111 1.1005142 2.1671111 2.41565 0 1.4692506-.9365333 3.6667197-1.4200889 5.7022439-.4003555 1.7048716.8576 3.0968868 2.5372445 3.0968868 3.04 0 5.0876444-3.9112388 5.0876444-8.547849 0-3.5211471-2.3669333-6.159605-6.6794667-6.159605-4.8693333 0-7.9072 3.6364662-7.9072 7.6993283 0 1.3998456.4138667 2.3889558 1.0613334 3.1524109.2968889.3512961.3370666.4929535.2286222.897638-.0782222.2939925-.2510222 1.0058387-.3253333 1.2902212-.1063112.4068201-.4373334.5495453-.8046223.4000576-2.2421333-.9150783-3.2888889-3.3748628-3.2888889-6.1393174 0-4.5672052 3.8474667-10.042726 11.4766223-10.042726 6.1326222 0 10.1667555 4.4422761 10.1667555 9.2070185 0 6.3090928-3.5029333 11.0179553-8.6641778 11.0179553-1.7340444 0-3.3660444-.9385692-3.9217777-2.0020674 0 0-.9354667 3.7001765-1.1306667 4.4177174-.3370667 1.2211722-.9863111 2.4412765-1.5879111 3.4019129 1.3984.4000576 2.8768.6150352 4.4071111.6150352 8.8359111 0 16-7.1714944 16-16.0165392S38.8359111 14 30 14" fill="#FFF"></path>
					</g>
				</g>
			</symbol>
			<symbol id="product-share-tumblr" viewBox="0 0 60 60" preserveAspectRatio="xMidYMid meet" focusable="false">
				<g>
					<g fill="none" fill-rule="evenodd">
						<path d="M28.486325 59.969298c-6.636404-.569063-11.56302-2.326956-16.321973-5.823932C4.443764 48.472116 0 39.646792 0 29.986934 0 15.11156 10.506778 2.798388 25.274412.36718c6.028107-.992411 12.703853.049265 18.28794 2.85363 13.576275 6.818095 19.7813 22.541053 14.64267 37.103159-3.527955 9.997705-12.789708 17.617785-23.391072 19.244938-2.085625.320112-5.065149.508645-6.327625.400391z" fill="#35465C" fill-rule="nonzero"></path>
						<path d="M25.96539 14c0 6.948267-5.96539 8.206933-5.96539 8.206933v4.750934h4.023219v11.788089C24.023219 42.70791 27.676687 46 32.121159 46c4.444828 0 6.882486-1.768533 6.882486-1.768533v-5.240178s-1.341547 1.7664-4.08147 1.7664c-2.739568 0-3.924832-2.132622-3.924832-3.778133v-9.992178h7.00325v-5.025422h-7.00325V14H25.96539z" fill="#FFF"></path>
					</g>
				</g>
			</symbol>
			<symbol id="product-share-linkedin" viewBox="0 0 60 60" preserveAspectRatio="xMidYMid meet" focusable="false">
				<g>
					<g fill="none" fill-rule="evenodd">
						<path d="M28.4863253 59.9692983c-6.6364044-.569063-11.5630204-2.3269561-16.3219736-5.8239327C4.44376366 48.4721168 3e-7 39.6467924 3e-7 29.9869344c0-14.8753747 10.506778-27.18854591 25.2744118-29.61975392 6.0281072-.9924119 12.7038532.04926445 18.2879399 2.85362966C57.1386273 10.0389054 63.3436516 25.7618627 58.2050229 40.3239688 54.677067 50.3216743 45.4153135 57.9417536 34.81395 59.5689067c-2.0856252.3201125-5.0651487.5086456-6.3276247.4003916z" fill="#0077B5" fill-rule="nonzero"></path>
						<g fill="#FFF">
							<path d="M17.88024691 22.0816337c2.14182716 0 3.87817284-1.58346229 3.87817284-3.53891365C21.75841975 16.58553851 20.02207407 15 17.88024691 15 15.73634568 15 14 16.58553851 14 18.54272005c0 1.95545136 1.73634568 3.53891365 3.88024691 3.53891365M14.88888889 44.8468474h6.95851852V24.77777778h-6.95851852zM31.6137778 33.6848316c0-2.3014877 1.0888889-4.552108 3.6925432-4.552108 2.6036543 0 3.2438518 2.2506203 3.2438518 4.4970883v10.960701h6.9274074V33.1816948c0-7.9263084-4.6853333-9.29280591-7.5676049-9.29280591-2.8798518 0-4.4682469.9740923-6.2961975 3.33440621v-2.70185178h-6.9471111V44.5905129h6.9471111V33.6848316z"></path>
						</g>
					</g>
				</g>
			</symbol>
			<symbol id="product-share-mix" viewBox="0 0 60 60" preserveAspectRatio="xMidYMid meet" focusable="false">
				<g>
					<g fill="none" fill-rule="evenodd">
						<g fill-rule="nonzero">
							<circle fill="#FF8226" cx="30" cy="30" r="30"></circle>
							<g transform="translate(16.000000, 16.000000)" fill="#FFFFFF">
								<path d="M0,15.2225809 L0,25.1910701 C0,26.7287731 1.26040471,27.9752991 2.81528027,27.9752991 C4.37015582,27.9752991 5.63056053,26.7287731 5.63056053,25.1910701 L5.63056053,9.9538317 C5.46855813,12.8905403 3.00985687,15.2225809 0,15.2225809" opacity="0.299999982"></path>
								<path d="M27.7743298,0 C21.648207,0 16.6819432,4.91150508 16.6819432,10.9702166 L16.6819432,14.3849279 C16.6819432,12.8472249 17.942565,11.6006989 19.4974406,11.6006989 C21.052099,11.6006989 22.3125037,12.8472249 22.3125037,14.3849279 L22.3125037,15.8020911 C22.3125037,17.339794 23.5731256,18.5863201 25.1280011,18.5863201 C26.6828767,18.5863201 27.9432814,17.339794 27.9432814,15.8020911 L27.9432814,0.00218765537 C27.8870366,0.00131259322 27.8307918,0 27.7743298,0" opacity="0.299999982"></path>
								<path d="M0,15.2860232 C3.00985687,15.2860232 5.46855813,12.9539825 5.63056053,10.017274 L5.63056053,7.4054322 C5.63056053,7.33367711 5.63425227,7.26279707 5.63968131,7.19257334 C5.74956497,5.75418993 6.96349714,4.62098444 8.4458408,4.62098444 C10.0007164,4.62098444 11.2611211,5.86751048 11.2611211,7.4054322 L11.2611211,18.2990809 C11.2615554,19.8363463 12.5217429,21.0826536 14.0764013,21.0826536 C15.6310597,21.0826536 16.8912473,19.8365651 16.8918988,18.2992997 L16.8918988,10.9702166 C16.8918988,4.91150508 21.8579454,0 27.9840683,0 L0,0 L0,15.2860232 Z" opacity="0.699999988"></path>
								<path d="M0,15.2225809 L0,25.1910701 C0,26.7287731 1.26040471,27.9752991 2.81528027,27.9752991 C4.37015582,27.9752991 5.63056053,26.7287731 5.63056053,25.1910701 L5.63056053,9.9538317 C5.46855813,12.8905403 3.00985687,15.2225809 0,15.2225809" opacity="0.5"></path>
								<path d="M27.7743298,0 C21.648207,0 16.6819432,4.91150508 16.6819432,10.9702166 L16.6819432,14.3849279 C16.6819432,12.8472249 17.942565,11.6006989 19.4974406,11.6006989 C21.052099,11.6006989 22.3125037,12.8472249 22.3125037,14.3849279 L22.3125037,15.8020911 C22.3125037,17.339794 23.5731256,18.5863201 25.1280011,18.5863201 C26.6828767,18.5863201 27.9432814,17.339794 27.9432814,15.8020911 L27.9432814,0.00218765537 C27.8870366,0.00131259322 27.8307918,0 27.7743298,0" opacity="0.5"></path>
								<path d="M0,15.2860232 C3.00985687,15.2860232 5.46855813,12.9539825 5.63056053,10.017274 L5.63056053,7.4054322 C5.63056053,7.33367711 5.63425227,7.26279707 5.63968131,7.19257334 C5.74956497,5.75418993 6.96349714,4.62098444 8.4458408,4.62098444 C10.0007164,4.62098444 11.2611211,5.86751048 11.2611211,7.4054322 L11.2611211,18.2990809 C11.2615554,19.8363463 12.5217429,21.0826536 14.0764013,21.0826536 C15.6310597,21.0826536 16.8912473,19.8365651 16.8918988,18.2992997 L16.8918988,10.9702166 C16.8918988,4.91150508 21.8579454,0 27.9840683,0 L0,0 L0,15.2860232 Z"></path>
							</g>
						</g>
					</g>
				</g>
			</symbol>
		</defs>
	</svg>
	<?php
	$share_opts   = [
		'whatsapp'  => [
			'label'   => 'WhatsApp',
			'handler' => 'https://wa.me/?text=%url%',
		],
		'fb'        => [
			'label'   => 'Facebook',
			'handler' => 'https://www.facebook.com/dialog/share?app_id=%fb_app_id%&href=%url%&display=popup',
		],
		'twitter'   => [
			'label'   => 'Twitter',
			'handler' => 'https://twitter.com/intent/tweet?url=%url%&text=%title%&via=%twitter_user%&related=%hash_tags%',
		],
		'email'     => [
			'label'   => 'Email',
			'handler' => 'mailto:?body=%url%',
		],
		'kakao'     => [
			'label'   => 'KakaoTalk',
			'handler' => 'https://story.kakao.com/share?url=%url%',
		],
		'reddit'    => [
			'label'   => 'Reddit',
			'handler' => 'https://reddit.com/submit?url=%url%&title=%title%',
		],
		'vk'        => [
			'label'   => 'VK',
			'handler' => 'https://vkontakte.ru/share.php?url=%url%',
		],
		'ok'        => [
			'label'   => 'OK',
			'handler' => 'https://connect.ok.ru/offer?url=%url%&title=%title%',
		],
		'pinterest' => [
			'label'   => 'Pinterest',
			'handler' => 'https://pinterest.com/pin/create/button/?url=%url%&description=%title%&is_video=false&media=%featured_image%',
		],
		'tumblr'    => [
			'label'   => 'Tumblr',
			'handler' => 'https://www.tumblr.com/share/video?embed=%url%&caption=%title%',
		],
		'linkedin'  => [
			'label'   => 'LinkedIn',
			'handler' => 'https://www.linkedin.com/shareArticle?url=%url%&title=%title%&summary=%excerpt%&source=%linkedin_id%',
		],
		'mix'       => [
			'label'   => 'Mix',
			'handler' => 'https://mix.com/add?url=%url%',
		],
	];
	$searches     = [
		'%url%',
		'%title%',
		'%fb_app_id%',
		'%twitter_user%',
		'%hash_tags%',
		'%featured_image%',
		'%blogger_id%',
		'%embed_code%',
		'%excerpt%',
		'%linkedin_id%',
		'%unknown%',
	];
	$replacements = [
		//phpcs:disable WordPressVIPMinimum.Security.Mustache.OutputNotation
		'{{{data.url}}}',
		'{{{data.title}}}',
		'{{{springoo_ajax.fb_app_id}}}',
		'{{{springoo_ajax.twitter_user}}}',
		'{{{data.hash_tags}}}',
		'{{{data.featured_image}}}',
		'{{{data.blogger_id}}}',
		'{{{data.embed_code}}}',
		'{{{data.excerpt}}}',
		'{{{data.linkedin_id}}}',
		'{{{data.unknown}}}',
		//phpcs:enable WordPressVIPMinimum.Security.Mustache.OutputNotation
	];
	?>
	<script type="text/html" id="tmpl-product-share">
		<div class="share-area">
			<?php foreach ( $share_opts as $share_opt => $value ) { ?>
				<button type="button" data-type="<?php echo esc_attr($share_opt); ?>" class="share-btn" data-url="<?php echo esc_url(str_replace($searches, $replacements, $value['handler'])); ?>">
						<span class="product-share-icon-wrap" aria-hidden="true">
						<svg viewBox="0 0 60 60" class="product-share-icon"><use href="#product-share-<?php echo esc_attr($share_opt); ?>" xlink:href="#product-share-<?php echo esc_attr($share_opt); ?>"></use></svg>
					</span>
					<span class="product-share-btn-title"><?php echo esc_html($value['label']); ?></span>
				</button>
			<?php } ?>
		</div>
		<div class="share-input-area">
			<label for="share-url" class="sr-only"></label>
			<input id="share-url" type="text" value="{{{data.url}}}" readonly> <?php //phpcs:ignore WordPressVIPMinimum.Security.Mustache.OutputNotation ?>
			<input data-copy="#share-url" type="button" value="copy">
		</div>

	</script>
	<div class="modal fade product-share-modal" id="product-share" tabindex="-1" aria-labelledby="Product Share" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="shareModalLabel"><?php echo esc_html__('Share', 'springoo'); ?> </h5>
					<button type="button" class="close-btn" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Close" data-bs-dismiss="modal" aria-label="Close"><i class="si-thin-close"></i></button>
				</div>
				<div class="modal-body product-share-content"></div>
			</div>
		</div>
	</div>
	<?php
}

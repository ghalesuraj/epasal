<?php

class Springoo_WC_Single_Product {
	/**
	 * Springoo Single Product Constructor
	 */
	public function __construct() {

		// Springoo Single Product Buy Button
		add_action( 'woocommerce_after_add_to_cart_button', [ $this, 'springoo_product_buy_btn' ] );
		// Springoo Single Product Buy Button redirect
		add_action( 'wp_loaded', [ $this, 'springoo_product_buy_btn_redirect' ] );
		// Springoo Single product info
        add_action( 'woocommerce_single_product_summary', [ $this, 'springoo_store_info' ] , 100 );
		add_filter( 'springoo_single_product_summery_classes', [ $this, 'product_summery_wrapper_classes' ] );

		add_shortcode('single_product_add_to_cart_form', [$this, 'springoo_single_product_add_to_cart_form'] );
		add_shortcode('single_product_rating', [$this, 'springoo_single_product_rating'] );
	}

	/**
	 * WooCommerce single product Summery Class
	 *
	 * @param $classes
	 *
	 * @return mixed
	 */
	public function product_summery_wrapper_classes( $classes ) {

		if ( 1 === springoo_get_mod( 'woocommerce_single_summery_sticky_enable' ) ) {
			$classes [] = 'sticky-summery';
		}

		return $classes;
	}

	/**
	 * Springoo Product
	 *
	 * @return void
	 */
	public function springoo_product_buy_btn() {
		global $product;

		if ( ! is_product() ){
			return;
		}

		if ( 'grouped' === $product->get_type() || 'external' === $product->get_type() ) {
			return;
		}

		if ( 0 === springoo_get_mod( 'woocommerce_single_buy_btn_enable' ) ) {
			return;
		}

		if ( 'outlined' === springoo_get_mod( 'woocommerce_single_buy_btn_type' ) ) {
			$btn_class = 'btn-outlined';
		} else {
			$btn_class = 'btn-filled'  ;
		}

		$product_id = $product->get_id();

		echo sprintf(
			"<button type='submit' name='springoo_single_buy_now' class='springoo-single-buy-now button %s' value='%d'>%s</button>",
			esc_attr( $btn_class . ' '  . wc_wp_theme_get_element_class_name( 'button' ) ),
			esc_attr( $product_id ),
			esc_html__('Buy now', 'springoo')
		);
	}
	
	/**
	 * Redirect Buy now button
	 *
	 * @return void
	 * @throws Exception
	 */
	public function springoo_product_buy_btn_redirect() {
		// Make sure WC is installed, and springoo_single_buy_now query arg exists, and contains at least one comma.

		if ( isset($_REQUEST['springoo_single_buy_now'] ) ) {
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['springoo_single_buy_now'] ) ) );
		} else {
			return;
		}

		WC()->cart->empty_cart();

		$product_id = absint( $_REQUEST['springoo_single_buy_now'] );
		$quantity   = absint( isset( $_REQUEST['quantity'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['quantity'] ) ) : '' );


		if ( isset( $_REQUEST['variation_id'] ) ) {
			$variation_id = absint( $_REQUEST['variation_id'] );
			WC()->cart->add_to_cart( $product_id, $quantity, $variation_id );
		} else {
			WC()->cart->add_to_cart( $product_id, $quantity );
		}

		wp_safe_redirect( wc_get_checkout_url() );
		exit;

	}

	public function springoo_store_info() {
		if ( 0 === springoo_get_mod( 'woocommerce_single_store_info_enable' ) || ( defined( 'SPRINGOO_QUICK_VIEWING' ) && SPRINGOO_QUICK_VIEWING ) ) {
			return;
		}
		?>
		<div class="springoo-store-notices">
			<?php
			$notice_label = springoo_get_mod( 'woocommerce_single_store_info_label' );
			$notices      = springoo_get_mod( 'woocommerce_single_store_info_content' );


			if ( $notice_label ) {
				echo '<h6>' . esc_html( $notice_label ) . '</h6>';
			}

			if ( $notices ) {
				$notices = explode(',', $notices );

				echo '<ul>';
				foreach ( $notices as $notice ) {
					echo '<li>' . esc_html( $notice ) . '</li>';
				}
				echo '</ul>';
			}

			?>
		</div><!-- end .springoo-store-notices -->
		<?php
	}

	/**
	 * Return "Single Add to Cart Form".
	 *
	 * @param array  $atts Array of parameters for the shortcode.
	 * @param string $content Shortcode content (usually empty).
	 *
	 * @return string|void Template of the shortcode.
	 *
	 * @since 1.0.0
	 */
	public function springoo_single_product_add_to_cart_form( $atts, $content = null ) {

		// Extract shortcode attributes
		$atts = shortcode_atts(
			array(
				'id' => 0,
			),
			$atts, 'single_product_add_to_cart_form'
		);

		return $this->springoo_get_cart_form( absint( $atts['id'] ) );
	}

	/**
	 * Return "Single Add to Cart Form".
	 *
	 * @param int  $id
	 *
	 * @return string|void Template of the cart form.
	 *
	 * @since 1.0.0
	 */
	public function springoo_get_cart_form( $id ) {

		if ( ! $id ) {
			return;
		}

		// Set the main wp query for the product.
		global $product, $post;
		$_post    = $post;
		$_product = $product;
		$post     = get_post( $id );
		setup_postdata( $post );
		$product = wc_get_product( $id );

		ob_start();

		if ( $product ) {
			add_action( 'springoo_single_product_summary', 'woocommerce_template_single_add_to_cart', 40 );
			?>
			<div class="woocommerce">
				<div class="product">
					<?php do_action( 'springoo_single_product_summary' ); ?>
				</div>
			</div>
			<?php
			remove_action( 'springoo_single_product_summary', 'woocommerce_template_single_add_to_cart', 40 );
		}
		$product = $_product;
		$post    = $_post;
		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * Add to wishlist button "Add to Wishlist" shortcode
	 *
	 * @return void
	 */
	public  function  add_to_wishlist_button() {
		if ( ! defined( 'YITH_WCWL' ) ){
			return;
		}
		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
	}


	/**
	 * Return "Single Product Rating".
	 *
	 * @param array  $atts Array of parameters for the shortcode.
	 * @param string $content Shortcode content (usually empty).
	 *
	 * @return string|void Template of the shortcode.
	 *
	 * @since 1.0.0
	 */
	public function springoo_single_product_rating( $atts, $content = null ) {

		// Extract shortcode attributes
		$atts = shortcode_atts(
			array(
				'id'   => 0,
				'text' => 'yes',
			),
			$atts, 'single_product_rating'
		);

		if ( ! $atts['id'] ){
			return;
		}
		$id = absint( $atts['id'] );

		// Set the main wp query for the product.
		global $product, $post;
		$_post    = $post;
		$_product = $product;
		$post     = get_post( $id );
		setup_postdata( $post );
		$product = wc_get_product( $id );

		if ( ! wc_review_ratings_enabled() ) {
			return;
		}

		ob_start();

		$rating_count = $product->get_rating_count();
		$review_count = $product->get_review_count();
		$average      = $product->get_average_rating();

		if ( $rating_count > 0 ) {
			?>
			<div class="woocommerce">
				<div class="product">
					<div class="springoo-product-rating">
				<?php
				echo wp_kses_post( wc_get_rating_html( $average, $rating_count ) );
				if ( 'yes' === $atts['text'] ){
					?>
					<span class="woocommerce-review-label">
					(<?php
						printf(
							_n( '%s Review', '%s Reviews', $review_count, 'springoo' ),
							'<span class="count">' . esc_html( $review_count ) . '</span>'
						);
					?>)
					</span>
					<?php
				}
				?>
			</div>
				</div>
			</div>
			<?php
		}
		$product = $_product;
		$post    = $_post;
		wp_reset_postdata();
		return ob_get_clean();
	}
}

new Springoo_WC_Single_Product;

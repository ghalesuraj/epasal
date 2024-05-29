<?php
/**
 * Springoo Flying Cart.
 */

if ( ! function_exists( 'woocommerce_cart_tab_button' ) ) {

	/**
	 * Displays the number of items in the cart with an icon
	 *
	 * @return void
	 */
	function woocommerce_cart_tab_button() {
		global $woocommerce;

		$empty = 'woocommerce-cart-tab--empty';

		if ( intval( $woocommerce->cart->get_cart_contents_count() > 0 ) ) {
			$empty = 'woocommerce-cart-tab--has-contents';
		}
		?>
		<div class="woocommerce-cart-tab <?php echo esc_attr( $empty ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 86 104.5" class="woocommerce-cart-tab__icon">
				<path class="woocommerce-cart-tab__icon-bag" d="M67.2,26.7C64.6,11.5,54.8,0.2,43.1,0.2C31.4,0.2,21.6,11.5,19,26.7H0.1v77.6h86V26.7H67.2z M43.1,4.2 c9.6,0,17.7,9.6,20,22.6H23C25.4,13.8,33.5,4.2,43.1,4.2z M82.1,100.4h-78V30.7h14.4c-0.1,1.3-0.2,2.6-0.2,3.9c0,1.1,0,2.2,0.1,3.3 c-0.8,0.6-1.4,1.6-1.4,2.8c0,1.9,1.6,3.5,3.5,3.5s3.5-1.6,3.5-3.5c0-1.2-0.6-2.3-1.6-2.9c-0.1-1-0.1-2-0.1-3.1 c0-1.3,0.1-2.6,0.2-3.9h41.2c0.1,1.3,0.2,2.6,0.2,3.9c0,1,0,2.1-0.1,3.1c-1,0.6-1.6,1.7-1.6,2.9c0,1.9,1.6,3.5,3.5,3.5 c1.9,0,3.5-1.6,3.5-3.5c0-1.1-0.5-2.1-1.4-2.8c0.1-1.1,0.1-2.2,0.1-3.3c0-1.3-0.1-2.6-0.2-3.9h14.4V100.4z"/>
			</svg>
			<?php
				echo '<span class="woocommerce-cart-tab__contents">' . intval( $woocommerce->cart->get_cart_contents_count() ) . '</span>';
			?>

			<script type="text/javascript">
				jQuery('.woocommerce-cart-tab').click(function () {
					jQuery('.woocommerce-cart-tab-container').toggleClass('woocommerce-cart-tab-container--visible');
					jQuery('body').toggleClass('woocommerce-cart-tab-is-visible');
				});
			</script>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woocommerce_cart_tab' ) ) {
	/**
	 * Display the cart tab / widget
	 *
	 * @return void
	 */
	function woocommerce_cart_tab() {
		if ( get_option( 'wc_ct_horizontal_position' ) ) {
			$position = get_option( 'wc_ct_horizontal_position' );
		} else {
			$position = get_theme_mod( 'woocommerce_cart_tab_position', 'right' );
		}

		if ( ! is_cart() && ! is_checkout() ) {
			echo '<div class="woocommerce-cart-tab-container woocommerce-cart-tab-container--' . esc_attr( $position ) . '">';

			do_action( 'springoo_before_flying_cart_widget' );

			the_widget( 'WC_Widget_Cart', 'title=' . __( 'Your Cart', 'springoo-pro' ) );

			do_action( 'springoo_after_flying_cart_widget' );

			echo '</div>';
		}
	}
}


function woocommerce_cart_tab_sanitize_choices( $input, $setting ) {
	// Ensure input is a slug.
	$input = sanitize_key( $input );

	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}


if ( ! class_exists( 'WooCommerce_Cart_Tab_Frontend' ) ) :

	/**
	 * Cart tab frontend class
	 */
	class WooCommerce_Cart_Tab_Frontend {

		/**
		 * Setup class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', [ $this, 'setup_styles' ], 999 );

			if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
				add_filter( 'add_to_cart_fragments', [ $this, 'woocommerce_cart_tab_add_to_cart_fragment' ] );
			} else {
				add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'woocommerce_cart_tab_add_to_cart_fragment' ] );
			}

			add_action( 'wp_footer', 'woocommerce_cart_tab' );
		}

		/**
		 * Styles
		 *
		 * @return void
		 */
		public function setup_styles() {
			if ( ! is_cart() && ! is_checkout() ) {

				if ( 'storefront' == get_option( 'template' ) ) {
					wp_enqueue_style( 'cart-tab-styles-storefront',  SPRINGOO_PRO_URL . 'assets/dist/css/cart-tab-storefront.css', [], SPRINGOO_PRO_VERSION );
				} else {
					wp_enqueue_style( 'cart-tab-styles', SPRINGOO_PRO_URL .  'assets/dist/css/cart-tab.css', [], SPRINGOO_PRO_VERSION );
				}

				wp_enqueue_script( 'cart-tab-script', SPRINGOO_PRO_URL . 'assets/dist/js/cart-tab.js', [ 'jquery' ], SPRINGOO_PRO_VERSION, true );
			}
		}

		public function woocommerce_cart_tab_add_to_cart_fragment( $fragments ) {
			ob_start();
			woocommerce_cart_tab_button();
			$fragments['.woocommerce-cart-tab'] = ob_get_clean();

			return $fragments;
		}
	}

endif;

/**
 * WC Cart Tab (Flying Cart).
 */
function woocommerce_cart_tab_frontend() {
	if ( apply_filters( 'springoo_extensions_flying_cart', true ) ) {
		new WooCommerce_Cart_Tab_Frontend();
	}
}

//add_action( 'init', 'woocommerce_cart_tab_frontend' );


add_action( 'springoo_before_flying_cart_widget', 'woocommerce_cart_tab_button' );
function warp_ajax_product_remove() {
	// Get mini cart

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$prod_id  = isset( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$prod_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( $cart_item['product_id'] == $prod_id && $cart_item_key == $prod_key ) {
			WC()->cart->remove_cart_item( $cart_item_key );
		}
	}

	ob_start();
	woocommerce_cart_tab_button();
	$trigger = ob_get_clean();

	ob_start();
	WC()->cart->calculate_totals();
	WC()->cart->maybe_set_cart_cookies();

	woocommerce_mini_cart();

	$mini_cart = ob_get_clean();

	// Fragments and mini cart are returned
	$data = array(
		'fragments' => apply_filters(
			'woocommerce_add_to_cart_fragments',
			[
				'.woocommerce-cart-tab'            => $trigger,
				'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
			]
		),
		'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( wp_json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() ),
	);

	wp_send_json( $data );

	die();
}

add_action( 'wp_ajax_product_remove', 'warp_ajax_product_remove' );
add_action( 'wp_ajax_nopriv_product_remove', 'warp_ajax_product_remove' );


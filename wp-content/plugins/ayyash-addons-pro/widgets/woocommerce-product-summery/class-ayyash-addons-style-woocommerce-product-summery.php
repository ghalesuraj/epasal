<?php

namespace AyyashAddonsPro\Widgets;

use AyyashAddonsPro\Ayyash_Pro_Widget;
use Elementor\Controls_Manager;
use AyyashAddons\Controls\Ayyash_Addons_Query_Builder;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use WP_Query;
use YITH_WCQV_Frontend;
use YITH_WCWL_Shortcode;
use YITH_Woocompare_Frontend;
use function Sodium\add;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class AyyashAddons_Style_Woocommerce_Product_Summery extends Ayyash_Pro_Widget {


	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since  1.1.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'ayyash-woocommerce-product-summery';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since  1.1.0
	 *
	 * @access public
	 */
	public function get_title() {
		return __( 'Product Summery', 'ayyash-addons-pro' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.1.0
	 *
	 * @access public
	 */
	public function get_icon() {
		return 'ayyash-addons eicon-products';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [ 'ayyash-addons-pro-woocommerce-product-summery' ];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [];
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since  1.1.0
	 *
	 * @access public
	 */
	public function get_categories() {
		return [ 'ayyash-pro-widgets' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize
	 * the widget settings.
	 *
	 * @since  1.1.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_product_query',
			[
				'label' => esc_html__( 'Product', 'ayyash-addons-pro' ),
			]
		);

		if ( ! ayyash_addons_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->plugin_dependency_alert(
				[
					'plugins' => [
						[
							'path' => 'woocommerce/woocommerce.php',
							'name' => __( 'WooCommerce', 'ayyash-addons-pro' ),
							'slug' => 'woocommerce',
						],
					],
				]
			);

			$this->end_controls_section();

			return;
		}

		$this->add_control(
			'product_id',
			[
				'label' => __( 'Product ID', 'ayyash-addons-pro' ),
				'type'  => Controls_Manager::TEXT,
			]
		);
		$this->end_controls_section();

		/*
		 * Fires after controllers are registered.
		 *
		 * @param AyyashAddons_Style_Woocommerce_Product $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			printf( '<div class="ayyash-addons-alert alert-warning">%s</div>', esc_html( 'Please Install/Activate Woocommerce Plugin.' ) );

			return;
		}

		$settings = $this->get_settings_for_display();

		if ( ! ayyash_addons_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}
		if ( ! intval( $settings['product_id'] ) ) {
			return;
		}

		// Set the main wp query for the product.
		global $product, $post;
		$_post    = $post;
		$_product = $product;
		$post     = get_post( $settings['product_id'] );
		setup_postdata( $post );

		$product = wc_get_product( $settings['product_id'] );
		if ( $product ) {
			$this->init_hooks();
			?>
			<div
				class="ayyash-addons-wrapper ayyash-addons-<?php echo str_replace( 'ayyash-', '', $this->get_name() ); // phpcs:ignore
				?>">
				<div class="ayyash-addons-wrapper-inside woocommerce">
					<div class="product">
						<div id="product-<?php the_ID(); ?>" <?php post_class( 'product' ); ?>>
							<div class="summary entry-summary">
								<div class="summary-content">
									<?php do_action( 'ayyash_addons_single_product_summary' ); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$this->removed_hooks();
		}
		$product = $_product;
		$post    = $_post;
		wp_reset_postdata();

	}


	/**
	 * Initialize all hooks
	 */
	protected function init_hooks() {
		add_action('ayyash_addons_single_product_summary', 'woocommerce_template_single_title', 5);
		add_action('ayyash_addons_single_product_summary', 'woocommerce_template_single_rating', 10);
		add_action('ayyash_addons_single_product_summary', 'woocommerce_template_single_excerpt', 20);
		add_action('ayyash_addons_single_product_summary', 'woocommerce_template_single_price', 30);
		add_action('ayyash_addons_single_product_summary', 'woocommerce_template_single_add_to_cart', 40);
		add_action( 'woocommerce_after_add_to_cart_button', [ $this, 'add_to_wishlist_button' ] );
	}

	/**
	 * Removed all hooks
	 */
	protected function removed_hooks() {
		remove_action('ayyash_addons_single_product_summary', 'woocommerce_template_single_title', 5);
		remove_action('ayyash_addons_single_product_summary', 'woocommerce_template_single_rating', 10);
		remove_action('ayyash_addons_single_product_summary', 'woocommerce_template_single_excerpt', 20);
		remove_action('ayyash_addons_single_product_summary', 'woocommerce_template_single_price', 30);
		remove_action('ayyash_addons_single_product_summary', 'woocommerce_template_single_add_to_cart', 40);
		remove_action( 'woocommerce_after_add_to_cart_button', [ $this, 'add_to_wishlist_button' ] );
	}

	/**
	 * Add to wishlist button "Add to Wishlist" shortcode
	 *
	 * @return void
	 */
	public  function add_to_wishlist_button() {
		if ( ! defined( 'YITH_WCWL' ) ) {
			return;
		}
		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
	}

}

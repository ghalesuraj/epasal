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
use WooProductPosterMockup\Frontend\Frontend;
use WPB_GQB_WooCommerce_Handler;
use YITH_WCQV_Frontend;
use YITH_WCWL_Shortcode;
use YITH_Woocompare_Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class AyyashAddons_Style_Woocommerce_Product extends Ayyash_Pro_Widget {

	protected $wishlist_add_label;

	protected $wishlist_remove_label;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since  1.1.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'ayyash-woocommerce-product';
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
		return __( 'Woocommerce Product Pro', 'ayyash-addons-pro' );
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
		return [ 'ayyash-addons-pro-woocommerce-product' ];
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
				'label' => esc_html__( 'Product Query', 'ayyash-addons-pro' ),
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

		$this->plugin_dependency_alert(
			[
				[
					'plugins' => [
						[
							'path' => 'yith-woocommerce-quick-view/init.php',
							'name' => __( 'YITH WooCommerce Quick View', 'ayyash-addons-pro' ),
							'slug' => 'yith-woocommerce-quick-view',
						],
						[
							'path' => 'yith-woocommerce-wishlist/init.php',
							'name' => __( 'YITH WooCommerce Wishlist', 'ayyash-addons-pro' ),
							'slug' => 'yith-woocommerce-wishlist',
						],
						[
							'path' => 'yith-woocommerce-compare/init.php',
							'name' => __( 'YITH WooCommerce Compare', 'ayyash-addons-pro' ),
							'slug' => 'yith-woocommerce-compare',
						],
					],
				],
			]
		);

		$this->add_control(
			'query_builder',
			[
				'label'   => __( 'Product Query', 'ayyash-addons-pro' ),
				'type'    => 'ayyash_addons_query_builder',
				'default' => [
					'post_type'         => 'product',
					'disable_post_type' => true,
				],
			]
		);
		$this->end_controls_section();

		// Product grid product settings controller sections
		$this->start_controls_section(
			'section_product_settings',
			[
				'label' => esc_html__( 'Product Settings', 'ayyash-addons-pro' ),
			]
		);

		$this->add_control(
			'woocommerce_product_title_length',
			[
				'label' => esc_html__( 'Product Title Length', 'ayyash-addons-pro' ),
				'type'  => Controls_Manager::NUMBER,
			]
		);
		$this->add_responsive_control(
			'woocommerce_product_column',
			[
				'label'     => esc_html__( 'Product Grid Column', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4',
				'options'   => [
					'1' => esc_html__( '1', 'ayyash-addons-pro' ),
					'2' => esc_html__( '2', 'ayyash-addons-pro' ),
					'3' => esc_html__( '3', 'ayyash-addons-pro' ),
					'4' => esc_html__( '4', 'ayyash-addons-pro' ),
					'5' => esc_html__( '5', 'ayyash-addons-pro' ),
					'6' => esc_html__( '6', 'ayyash-addons-pro' ),
				],
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-products.ayyash-addons-column ' => 'grid-template-columns: repeat({{VALUE}},1fr);' ],
			]
		);
		$this->add_control(
			'woocommerce_product_column_gap_custom',
			[
				'label'   => esc_html__( 'Grid Gap', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'no_gap',
				'options' => [
					'no_gap'     => esc_html__( 'No Gap', 'ayyash-addons-pro' ),
					'custom_gap' => esc_html__( 'Custom', 'ayyash-addons-pro' ),
				],
			]
		);
		$this->add_responsive_control(
			'woocommerce_product_column_gap',
			[
				'label'      => esc_html__( 'Product Grid Gap', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [
					'px',
					'%',
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 24,
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-products.has-grid-gap' => 'gap: {{SIZE}}{{UNIT}};' ],
				'condition'  => [ 'woocommerce_product_column_gap_custom' => 'custom_gap' ],
			]
		);
		$this->add_control(
			'wc_product_content_align',
			[
				'label'   => esc_html__( 'Content Alignment', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'content-left'   => [
						'title' => esc_html__( 'Left', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'content-center' => [
						'title' => esc_html__( 'Center', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'content-right'  => [
						'title' => esc_html__( 'Right', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
			]
		);
		$this->add_control(
			'woocommerce_product_image_link',
			[
				'label'        => esc_html__( 'Product Image Clickable?', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);
		$this->add_control(
			'woocommerce_product_price',
			[
				'label'        => esc_html__( 'Product Price?', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);
		$this->add_control(
			'woocommerce_product_rating',
			[
				'label'        => esc_html__( 'Product Rating?', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ayyash-addons-pro' ),
				'label_off'    => esc_html__( 'No', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'woocommerce_product_rating_count',
			[
				'label'        => esc_html__( 'Product Rating Count?', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ayyash-addons-pro' ),
				'label_off'    => esc_html__( 'No', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [ 'woocommerce_product_rating' => 'yes' ],
			]
		);

		$this->add_control(
			'woocommerce_product_category',
			[
				'label'        => esc_html__( 'Product Category?', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'woocommerce_product_stock',
			[
				'label'        => esc_html__( 'Product Stock?', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'after',
			]
		);

		// Add to cart button controller
		$this->add_control(
			'cat_btn_heading',
			[
				'label'     => esc_html__( 'Button Settings', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'enable_add_to_cart',
			[
				'label'        => esc_html__( 'Enable Add To Cart Button ?', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'cart_button_position',
			[
				'label'     => esc_html__( 'Button Position', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'footer',
				'options'   => [
					'footer'      => esc_html__( 'In Footer', 'ayyash-addons-pro' ),
					'header'      => esc_html__( 'In Header', 'ayyash-addons-pro' ),
					'after_price' => esc_html__( 'After Price', 'ayyash-addons-pro' ),
					'actions'     => esc_html__( 'In Action', 'ayyash-addons-pro' ),
				],
				'condition' => [ 'enable_add_to_cart' => 'yes' ],
			]
		);
		$this->add_control(
			'cart_button_style',
			[
				'label'     => esc_html__( 'Button Style', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'inline',
				'options'   => [
					'block'  => esc_html__( 'Block', 'ayyash-addons-pro' ),
					'inline' => esc_html__( 'Inline', 'ayyash-addons-pro' ),
				],
				'condition' => [ 'cart_button_position' => 'after_price' ],
			]
		);
		$this->add_control(
			'cart_button_width',
			[
				'label'      => esc_html__( 'Width', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [
					'px',
					'%',
				],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-cart-btn .ayyash-addons-btn, .ayyash-cart-btn .added_to_cart' => 'width: {{SIZE}}{{UNIT}};' ],
				'condition'  => [ 'enable_add_to_cart' => 'yes' ],
			]
		);
		$this->add_control(
			'cart_button_height',
			[
				'label'      => esc_html__( 'Height', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [
					'px',
					'%',
				],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-cart-btn .ayyash-addons-btn, .ayyash-cart-btn .added_to_cart' => 'height: {{SIZE}}{{UNIT}};' ],
				'condition'  => [ 'enable_add_to_cart' => 'yes' ],
			]
		);
		$this->add_control(
			'cart_button_bottom',
			[
				'label'      => esc_html__( 'Bottom', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [
					'px',
					'%',
				],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .product-header .ayyash-cart-btn' => 'bottom: {{SIZE}}{{UNIT}};' ],
				'condition'  => [ 'enable_add_to_cart' => 'yes' ],
			]
		);
		$this->add_control(
			'enable_add_to_cart_icon',
			[
				'label'        => esc_html__( 'Enable Icon', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [ 'enable_add_to_cart' => 'yes' ],
			]
		);
		$this->add_control(
			'enable_add_to_cart_text',
			[
				'label'        => esc_html__( 'Enable Text', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [ 'enable_add_to_cart' => 'yes' ],
			]
		);
		$this->add_control(
			'wc_product_button_align',
			[
				'label'       => esc_html__( 'Button Alignment', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'description' => esc_html__( 'Enable when a width is define', 'ayyash-addons-pro' ),
				'options'     => [
					'left'   => [
						'title' => esc_html__( 'Left', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'toggle'      => true,
				'selectors'   => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .product-header .ayyash-cart-btn' => 'text-align: {{VALUE}};',
				],
				'condition'   => [ 'enable_add_to_cart' => 'yes' ],
			]
		);

		// Labels controller
		$this->add_control(
			'labels_heading',
			[
				'label'     => esc_html__( 'Labels Settings', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_type_style',
			[
				'label'   => esc_html__( 'Label Style', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'block'  => esc_html__( 'block', 'ayyash-addons-pro' ),
					'inline' => esc_html__( 'inline', 'ayyash-addons-pro' ),
				],
			]
		);
		$this->add_control(
			'labels_position_top',
			[
				'label'      => esc_html__( 'Top', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => '0',
				],
				'size_units' => [
					'px',
					'vh',
				],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [ '{{WRAPPER}} .product-labels' => 'top: {{SIZE}}{{UNIT}};' ],
			]
		);
		$this->add_control(
			'labels_position_left',
			[
				'label'      => esc_html__( 'Left', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => '0',
				],
				'size_units' => [
					'px',
					'vh',
				],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [ '{{WRAPPER}} .product-labels' => 'left: {{SIZE}}{{UNIT}};' ],
			]
		);
		$this->add_control(
			'woocommerce_product_discount_label',
			[
				'label'        => esc_html__( 'Enable Discount', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'discount_label_suffix',
			[
				'label'       => esc_html__( 'Sale Suffix Text', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '%', 'ayyash-addons-pro' ),
				'default'     => esc_html__( '%', 'ayyash-addons-pro' ),
				'condition'   => [ 'woocommerce_product_discount_label' => 'yes' ],
			]
		);

		$this->add_control(
			'discount_label_prefix',
			[
				'label'       => esc_html__( 'Sale Prefix Text', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Sale', 'ayyash-addons-pro' ),
				'default'     => esc_html__( '-', 'ayyash-addons-pro' ),
				'condition'   => [ 'woocommerce_product_discount_label' => 'yes' ],
			]
		);

		$this->add_control(
			'woocommerce_product_featured_label',
			[
				'label'        => esc_html__( 'Enable Featured', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		// Product rating settings
		$this->add_control(
			'rating_settings',
			[
				'label'     => esc_html__( 'Product Rating Settings', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'product_rating_position',
			[
				'label'   => esc_html__( 'Rating Position', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'content',
				'options' => [
					'header_before' => esc_html__( 'Before Header', 'ayyash-addons-pro' ),
					'header_after'  => esc_html__( 'After Header', 'ayyash-addons-pro' ),
					'content'       => esc_html__( 'Bottom', 'ayyash-addons-pro' ),
					'after_price'   => esc_html__( 'After Price', 'ayyash-addons-pro' ),
				],
			]
		);

		$this->add_control(
			'rating_label',
			[
				'label'        => esc_html__( 'Rating Label ?', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'rating_label_text',
			[
				'label'     => esc_html__( 'Rating Label Text', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Reviews', 'ayyash-addons-pro' ),
				'condition' => [ 'rating_label' => 'yes' ],
			]
		);

		// Actions controller
		if ( ayyash_addons_is_plugin_active( 'yith-woocommerce-wishlist/init.php' ) || ayyash_addons_is_plugin_active( 'yith-woocommerce-quick-view/init.php' ) ) {
			$this->add_control(
				'actions_heading',
				[
					'label'       => esc_html__( 'Actions Settings', 'ayyash-addons-pro' ),
					'type'        => Controls_Manager::HEADING,
					'description' => 'Wishlist, Quick View, Compare Button Controlls',
					'show_label'  => 'true',
					'label_block' => 'true',
					'separator'   => 'before',
				]
			);
			$this->add_control(
				'actions_position',
				[
					'label'   => esc_html__( 'Action Position', 'ayyash-addons-pro' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'right',
					'options' => [
						'bottom' => esc_html__( 'Bottom', 'ayyash-addons-pro' ),
						'right'  => esc_html__( 'Right', 'ayyash-addons-pro' ),
					],
				]
			);
			$this->add_control(
				'actions_position_bottom',
				[
					'label'      => esc_html__( 'Bottom', 'ayyash-addons-pro' ),
					'type'       => Controls_Manager::SLIDER,
					'default'    => [
						'unit' => 'px',
						'size' => '0',
					],
					'size_units' => [
						'px',
						'vh',
					],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 500,
						],
						'vh' => [
							'min' => 1,
							'max' => 100,
						],
					],
					'selectors'  => [ '{{WRAPPER}} .product-actions.bottom' => 'bottom: {{SIZE}}{{UNIT}};' ],
					'condition'  => [ 'actions_position' => 'bottom' ],
				]
			);
			$this->add_control(
				'actions_position_top',
				[
					'label'      => esc_html__( 'Top', 'ayyash-addons-pro' ),
					'type'       => Controls_Manager::SLIDER,
					'default'    => [
						'unit' => 'px',
						'size' => '0',
					],
					'size_units' => [
						'px',
						'vh',
					],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 500,
						],
						'vh' => [
							'min' => 1,
							'max' => 100,
						],
					],
					'selectors'  => [ '{{WRAPPER}} .product-actions.right' => 'top: {{SIZE}}{{UNIT}};' ],
					'condition'  => [ 'actions_position' => 'right' ],
				]
			);
			$this->add_control(
				'actions_position_right',
				[
					'label'      => esc_html__( 'Right', 'ayyash-addons-pro' ),
					'type'       => Controls_Manager::SLIDER,
					'default'    => [
						'unit' => 'px',
						'size' => '0',
					],
					'size_units' => [
						'px',
						'vh',
					],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 500,
						],
						'vh' => [
							'min' => 1,
							'max' => 100,
						],
					],
					'selectors'  => [ '{{WRAPPER}} .product-actions.right' => 'right: {{SIZE}}{{UNIT}};' ],
					'condition'  => [ 'actions_position' => 'right' ],
				]
			);
			$this->add_control(
				'actions_style',
				[
					'label'   => esc_html__( 'Action Style', 'ayyash-addons-pro' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'round',
					'options' => [
						'round'  => esc_html__( 'Round', 'ayyash-addons-pro' ),
						'normal' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
					],
				]
			);
		}
		$enable_wishlist = get_option( 'yith_wcwl_show_on_loop' );
		if ( ayyash_addons_is_plugin_active( 'yith-woocommerce-wishlist/init.php' ) && 'yes' === $enable_wishlist ) {
			$this->add_control(
				'enable_wishlist',
				[
					'label'        => esc_html__( 'Enable Wishlist', 'ayyash-addons-pro' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => 'yes',
				]
			);

			$this->add_control(
				'wishlist_add_label',
				[
					'label'     => esc_html__( 'Add To Wishlist Label', 'ayyash-addons-pro' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Add to wishlist', 'ayyash-addons-pro' ),
					'condition' => [ 'enable_wishlist' => 'yes' ],
				]
			);
			$this->add_control(
				'wishlist_remove_label',
				[
					'label'     => esc_html__( 'Remove From Wishlist Label', 'ayyash-addons-pro' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Remove from list', 'ayyash-addons-pro' ),
					'separator' => 'after',
					'condition' => [ 'enable_wishlist' => 'yes' ],
				]
			);
		}
		$enable_qv = get_option( 'yith-wcqv-enable' );
		if ( ayyash_addons_is_plugin_active( 'yith-woocommerce-quick-view/init.php' ) && 'yes' === $enable_qv ) {
			if ( ! class_exists( 'YITH_WCQV' ) || ! class_exists( 'YITH_WCQV_Frontend' ) ) {
				$label = __( 'Quick View', 'ayyash-addons-pro' );
			} else {
				$label = YITH_WCQV_Frontend::get_instance()->get_button_label();
			}
			$this->add_control(
				'enable_quick_view',
				[
					'label'        => esc_html__( 'Show Quick view?', 'ayyash-addons-pro' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => 'yes',
				]
			);
			$this->add_control(
				'quick_view_label',
				[
					'label'     => esc_html__( 'Quick View Label', 'ayyash-addons-pro' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => $label,
					'separator' => 'after',
					'condition' => [ 'enable_quick_view' => 'yes' ],
				]
			);
		}
		$enable_compare = get_option( 'yith_woocompare_compare_button_in_products_list' );
		if ( ayyash_addons_is_plugin_active( 'yith-woocommerce-compare/init.php' ) && 'yes' === $enable_compare ) {
			$this->add_control(
				'enable_compare',
				[
					'label'        => esc_html__( 'Show compare?', 'ayyash-addons-pro' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => 'yes',
				]
			);
		}

		// Hover controller
		$this->add_control(
			'hover_heading',
			[
				'label'     => esc_html__( 'Mouseover Item Settings', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'select_hover_items',
			[
				'label'       => esc_html__( 'Select Mouseover Items', 'ayyash-addons-pro' ),
				'description' => esc_html__( 'selected items will be hidden and on mouseover items will be shown or displayed', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => [
					'actions'  => esc_html__( 'Actions', 'ayyash-addons-pro' ),
					'button'   => esc_html__( 'Button', 'ayyash-addons-pro' ),
					'labels'   => esc_html__( 'Labels', 'ayyash-addons-pro' ),
					'wishlist' => esc_html__( 'Wishlist Visiable', 'ayyash-addons-pro' ),
					'quickv'   => esc_html__( 'Quick View Visiable', 'ayyash-addons-pro' ),
					'compare'  => esc_html__( 'Compare Visiable', 'ayyash-addons-pro' ),
				],
				'default'     => [
					'actions',
					'button',
				],
			]
		);
		$this->add_control(
			'product_hover_grow',
			[
				'label'        => esc_html__( 'Enable Grow Up', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Normally items are normal in height and on mouseover, the item will grow in height', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->end_controls_section();

		$this->__product_body_style();
		$this->__product_thumbnail();
		$this->__product_title();
		$this->__product_price_style();
		$this->__product_add_to_cart_style();
		$this->__product_rating_style();
		$this->__product_category_style();
		$this->__product_label_style();
		$this->__product_stock();
		$this->__product_hover_actions();

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

	protected function __product_add_to_cart_style() {
		// ==Product Button==
		$this->start_controls_section(
			'wc_product_button_settings',
			[
				'label'     => esc_html__( 'Add To Cart', 'ayyash-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'enable_add_to_cart' => 'yes' ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_product_btn_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product  .ayyash-addons-btn, {{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-cart-btn .ayyash-addons-btn, {{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-cart-btn .added_to_cart',
			]
		);

		$this->start_controls_tabs( 'wc_product_btn_tabs' );

		// Normal State Tab
		$this->start_controls_tab(
			'wc_product_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);

		$this->add_control(
			'wc_product_btn_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-cart-btn .ayyash-addons-btn' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-cart-btn .added_to_cart'     => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'wc_product_btn_background',
				'label'          => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'types'          => [
					'classic',
					'gradient',
				],
				'exclude'        => [ 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'selector'       => '{{WRAPPER}} .ayyash-addons-woocommerce-product  .ayyash-cart-btn .ayyash-addons-btn, {{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-cart-btn .added_to_cart',
				'fields_options' => [
					'background' => [ 'default' => 'classic' ],
				],
			]
		);

		$this->end_controls_tab();

		// Hover State Tab
		$this->start_controls_tab(
			'wc_product_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]
		);

		$this->add_control(
			'wc_product_btn_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .ayyash-addons-woocommerce-product .ayyash-addons-btn:hover, {{WRAPPER}} .ayyash-cart-btn  .ayyash-addons-btn:focus, {{WRAPPER}} .ayyash-cart-btn  .ayyash-addons-btn:focus-visible'                                     => 'color: {{VALUE}};',
					'{{WRAPPER}}  .ayyash-addons-woocommerce-product .ayyash-cart-btn  .ayyash-addons-btn:hover, {{WRAPPER}} .ayyash-cart-btn  .ayyash-addons-btn:focus, {{WRAPPER}} .ayyash-cart-btn  .ayyash-addons-btn:focus-visible'                   => 'color: {{VALUE}};',
					'{{WRAPPER}}  .ayyash-addons-woocommerce-product .ayyash-cart-btn  .added_to_cart:hover, {{WRAPPER}} .ayyash-cart-btn  added_to_cart:focus,{{WRAPPER}} .ayyash-cart-btn  added_to_cart:focus-visible '                                 => 'color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-cart-btn   .ayyash-addons-btn:hover svg, {{WRAPPER}} .ayyash-addons-btn:focus svg, {{WRAPPER}} .ayyash-addons-btn:focus svg, {{WRAPPER}} .ayyash-addons-btn:focus-visible svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-cart-btn  .ayyash-addons-btn:hover svg, {{WRAPPER}}  .ayyash-cart-btn  .ayyash-addons-btn:focus svg, {{WRAPPER}}  .ayyash-cart-btn  .ayyash-addons-btn:focus-visible svg'      => 'fill: {{VALUE}};',
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-cart-btn  .added_to_cart:hover svg, {{WRAPPER}}  .ayyash-cart-btn  .added_to_cart:focus svg, {{WRAPPER}}  .ayyash-cart-btn  .added_to_cart:focus-visible svg'                  => 'fill: {{VALUE}};',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'wc_product_btn_background_hover',
				'label'          => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'types'          => [
					'classic',
					'gradient',
				],
				'exclude'        => [ 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'selector'       => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-btn:hover, {{WRAPPER}} .ayyash-cart-btn .ayyash-addons-btn:focus, {{WRAPPER}} .ayyash-cart-btn .ayyash-addons-btn:hover, {{WRAPPER}} .ayyash-cart-btn .added_to_cart:hover',
				'fields_options' => [
					'background' => [ 'default' => 'classic' ],
				],
			]
		);

		$this->add_control(
			'wc_product_btn_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [ 'border_border!' => '' ],
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-btn:hover, {{WRAPPER}} .ayyash-addons-btn:focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-cart-btn .ayyash-addons-btn:hover, {{WRAPPER}} .ayyash-cart-btn .ayyash-addons-btn:focus'  => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-cart-btn .added_to_cart:hover, {{WRAPPER}} .ayyash-cart-btn .added_to_cart:focus'          => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'wc_product_btn_border',
				'selector'  => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-btn , {{WRAPPER}} .ayyash-cart-btn .ayyash-addons-btn, {{WRAPPER}} .ayyash-cart-btn .added_to_cart',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'wc_product_btn_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'%',
					'em',
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-cart-btn .ayyash-addons-btn'                   => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-cart-btn .added_to_cart'                       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'wc_product_btn_box_shadow',
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-btn, {{WRAPPER}} .ayyash-cart-btn .ayyash-addons-btn, {{WRAPPER}} .ayyash-cart-btn .added_to_cart',
			]
		);

		$this->add_responsive_control(
			'wc_product_btn_text_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'%',
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-cart-btn .ayyash-addons-btn'                   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-cart-btn .added_to_cart'                       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);
		$this->end_controls_section();
	}

	protected function __product_body_style() {
		// Product body
		$this->start_controls_section(
			'wc_product_body_settings',
			[
				'label' => esc_html__( 'Product Body', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'wc_product_section_tabs' );

		// Normal State Tab
		$this->start_controls_tab(
			'wc_product_body_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'wc_product_body_background',
				'label'          => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'label_block'    => true,
				'types'          => [
					'classic',
					'gradient',
				],
				'fields_options' => [
					'background' => [ 'label' => 'Background' ],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-item-inner',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'wc_product_body_border_custom_gap',
				'label'          => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-item-inner',
				'fields_options' => [
					'border' => [ 'default' => 'solid' ],
					'width'  => [
						'default' => [
							'left'     => 1,
							'right'    => 1,
							'top'      => 1,
							'bottom'   => 1,
							'inLinked' => false,
						],
					],
					'color'  => [ 'default' => '#E4E4E4' ],
				],
				'condition'      => [ 'woocommerce_product_column_gap_custom' => 'custom_gap' ],
			]
		);

		$this->add_control(
			'wc_product_body_border_radius_no_gap',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'%',
					'em',
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-product-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
				'condition'  => [ 'woocommerce_product_column_gap_custom' => 'custom_gap' ],
			]
		);

		$this->add_control(
			'wc_product_body_border_no_gap_color',
			[
				'label'     => esc_html__( 'Border Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-products.no-gap .ayyash-addons-product-item .ayyash-addons-product-item-inner' => 'border-color: {{VALUE}} !important; ' ],
				'condition' => [ 'woocommerce_product_column_gap_custom' => 'no_gap' ],

			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'wc_product_body_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-item-inner',
			]
		);

		$this->add_responsive_control(
			'wc_product_body_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'%',
				],
				'default'    => [
					'top'      => '30',
					'right'    => '30',
					'bottom'   => '15',
					'left'     => '30',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);

		$this->end_controls_tab();

		// Hover State Tab
		$this->start_controls_tab(
			'wc_product_body_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'wc_product_body_background_hover',
				'label'          => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'label_block'    => true,
				'types'          => [
					'classic',
					'gradient',
				],
				'fields_options' => [
					'background' => [ 'label' => 'Background' ],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-item:hover .ayyash-addons-product-item-inner',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'wc_product_body_border_hover',
				'label'          => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-item:hover .ayyash-addons-product-item-inner , {{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-item:focus-visible .ayyash-addons-product-item-inner',
				'fields_options' => [
					'border' => [ 'default' => 'none' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'           => 'wc_product_body_box_shadow_hover',
				'label'          => esc_html__( 'Box Shadow', 'ayyash-addons-pro' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-item:hover .ayyash-addons-product-item-inner, {{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-item:focus-visible .ayyash-addons-product-item-inner',
				'fields_options' => [
					'box_shadow_type' => [ 'default' => '' ],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __product_category_style() {
		$this->start_controls_section(
			'wc_product_category_settings',
			[
				'label'     => esc_html__( 'Product Category', 'ayyash-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'woocommerce_product_category' => 'yes' ],
			]
		);

		$this->add_control(
			'wc_product_category_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-category a' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_product_category_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-category a',
			]
		);

		$this->add_responsive_control(
			'wc_product_category_bottom_space',
			[
				'label'     => esc_html__( 'Spacing', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-category' => 'margin-bottom: {{SIZE}}{{UNIT}};' ],
			]
		);

		$this->end_controls_section();
	}

	protected function __product_label_style() {
		// ==Product Price==
		$this->start_controls_section(
			'wc_product_labels_settings',
			[
				'label' => esc_html__( 'Product Labels', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'wc_product_labels_tabs' );
		// Discount price  Tab
		$this->start_controls_tab(
			'wc_product_discount_tab',
			[
				'label' => esc_html__( 'Discount', 'ayyash-addons-pro' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_product_discount_typography',
				'label'    => esc_html__( 'Typography', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-discount',
			]
		);
		$this->add_control(
			'wc_product_discount_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-discount' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'wc_product_discount_background',
				'label'          => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'label_block'    => true,
				'types'          => [
					'classic',
					'gradient',
				],
				'fields_options' => [
					'background' => [ 'label' => 'Background' ],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-discount',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wc_product_discount_border',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-discount',
			]
		);
		$this->add_control(
			'wc_product_discount_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'%',
					'em',
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-discount' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);
		$this->add_responsive_control(
			'wc_product_discount_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'%',
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-discount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);
		$this->add_responsive_control(
			'wc_product_discount_margin',
			[
				'label'      => esc_html__( 'Margin', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'%',
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-discount' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);
		$this->end_controls_tab();
		// Featured tab
		$this->start_controls_tab(
			'wc_product_featured_tab',
			[
				'label' => esc_html__( 'Featured', 'ayyash-addons-pro' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_product_featured_typography',
				'label'    => esc_html__( 'Typography', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-featured',
			]
		);
		$this->add_control(
			'wc_product_featured_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-featured' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'wc_product_featured_background',
				'label'          => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'label_block'    => true,
				'types'          => [
					'classic',
					'gradient',
				],
				'fields_options' => [
					'background' => [ 'label' => 'Background' ],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-featured',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wc_product_featured_border',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-featured',
			]
		);
		$this->add_control(
			'wc_product_featured_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'%',
					'em',
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-featured' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);
		$this->add_responsive_control(
			'wc_product_featured__padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'%',
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-featured' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);
		$this->add_responsive_control(
			'wc_product_featured__margin',
			[
				'label'      => esc_html__( 'Margin', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'%',
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-featured' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function __product_price_style() {
		// ==Product Price==
		$this->start_controls_section(
			'wc_product_price_settings',
			[
				'label'     => esc_html__( 'Product Price', 'ayyash-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'woocommerce_product_price' => 'yes' ],
			]
		);
		$this->start_controls_tabs( 'wc_product_price_tabs' );
		// Regular price  Tab
		$this->start_controls_tab(
			'wc_product_regular_price_tab',
			[
				'label' => esc_html__( 'Regular price', 'ayyash-addons-pro' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_product_regular_price_typography',
				'label'    => esc_html__( 'Typography', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-price .woocommerce-Price-amount bdi,
						{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-price ins .woocommerce-Price-amount bdi
						',
			]
		);
		$this->add_control(
			'wc_product_regular_price_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'
			{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-price .woocommerce-Price-amount bdi,
			{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-price ins .woocommerce-Price-amount bdi
            ' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		// Sale Price tab
		$this->start_controls_tab(
			'wc_product_sale_price_tab',
			[
				'label' => esc_html__( 'Sale price', 'ayyash-addons-pro' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_product_sale_price_typography',
				'label'    => esc_html__( 'Typography', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-price del .woocommerce-Price-amount bdi',
			]
		);
		$this->add_control(
			'wc_product_sale_price_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-price del .woocommerce-Price-amount bdi' => 'color: {{VALUE}};' ],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'wc_product_price_bottom_space',
			[
				'label'      => esc_html__( 'Spacing', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'rem',
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);
		$this->end_controls_section();
	}

	protected function __product_rating_style() {
		$this->start_controls_section(
			'wc_product_rating_settings',
			[
				'label'     => esc_html__( 'Product Rating', 'ayyash-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'woocommerce_product_rating' => 'yes' ],
			]
		);
		$this->add_control(
			'wc_product_rating_color',
			[
				'label'     => esc_html__( 'Rating Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-rating .star-rating span:before' => 'color: {{VALUE}};' ],
			]
		);

		$this->add_control(
			'wc_product_rating_count_color',
			[
				'label'     => esc_html__( 'Count Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-rating' => 'color: {{VALUE}};' ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-rating',
			]
		);

		$this->add_responsive_control(
			'wc_product_rating_space',
			[
				'label'      => esc_html__( 'Spacing', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'rem',
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);

		$this->end_controls_section();
	}

	protected function __product_thumbnail() {
		$this->start_controls_section(
			'wc_product_thumbnail_settings',
			[
				'label' => esc_html__( 'Product Thumbnail', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'           => 'wc_product_thumbnail_size',
				'separator'      => 'after',
				'exclude'        => [ 'custom' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'default'        => 'woocommerce_thumbnail',
				'style_transfer' => true,
			]
		);

		$this->add_responsive_control(
			'wc_product_thumbnail_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'%',
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .product-thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);

		$this->start_controls_tabs( 'wc_product_thumbnail_tabs' );

		// Normal State Tab
		$this->start_controls_tab(
			'wc_product_thumbnail_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'wc_product_thumbnail_background',
				'label'          => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'label_block'    => true,
				'types'          => [
					'classic',
					'gradient',
				],
				'fields_options' => [
					'background' => [ 'label' => 'Background' ],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-woocommerce-product .product-thumbnail',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wc_product_thumbnail_border',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .product-thumbnail',
			]
		);

		$this->add_control(
			'wc_product_thumbnail_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'%',
					'em',
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .product-thumbnail, {{WRAPPER}} .ayyash-addons-woocommerce-product .product-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);

		$this->add_responsive_control(
			'wc_product_thumbnail_height',
			[
				'label'          => esc_html__( 'Height', 'ayyash-addons-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [ 'unit' => 'px' ],
				'tablet_default' => [ 'unit' => 'px' ],
				'mobile_default' => [ 'unit' => 'px' ],
				'size_units'     => [
					'px',
					'vh',
				],
				'range'          => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'      => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .product-thumbnail' => 'height: {{SIZE}}{{UNIT}};' ],
			]
		);

		$this->end_controls_tab();

		// Hover State Tab
		$this->start_controls_tab(
			'wc_product_thumbnail_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'wc_product_thumbnail_background_hover',
				'label'          => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'label_block'    => true,
				'types'          => [
					'classic',
					'gradient',
				],
				'fields_options' => [
					'background' => [ 'label' => 'Background' ],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-woocommerce-product .product-thumbnail:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wc_product_thumbnail_border_hover',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .product-thumbnail:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __product_title() {
		$this->start_controls_section(
			'wc_product_title_settings',
			[
				'label' => esc_html__( 'Product Title', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'wc_product_title_tabs' );

		// Normal State Tab
		$this->start_controls_tab(
			'wc_product_title_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'wc_product_title_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product h3.ayyash-addons-heading-title a' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_product_title_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product h3.ayyash-addons-heading-title a',
			]
		);
		$this->end_controls_tab();

		// Hover State Tab
		$this->start_controls_tab(
			'wc_product_title_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'wc_product_title_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product h3.ayyash-addons-heading-title a:hover' => 'color: {{VALUE}};' ],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'wc_product_title_bottom_space',
			[
				'label'      => esc_html__( 'Spacing', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'rem',
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product h3.ayyash-addons-heading-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);

		$this->end_controls_section();
	}

	protected function __product_stock() {
		$this->start_controls_section(
			'wc_product_stock_settings',
			[
				'label' => esc_html__( 'Product Stock', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_product_stock_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .stock ',
			]
		);

		$this->add_control(
			'wc_product_in_stock_text_color',
			[
				'label'     => esc_html__( 'In Stock Text Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .stock.in-stock'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .stock.in-stock:before' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wc_product_out_stock_text_color',
			[
				'label'     => esc_html__( 'Out Stock Text Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .stock.out-of-stock'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .stock.out-of-stock:before' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wc_product_back_order_text_color',
			[
				'label'     => esc_html__( 'On Back Order Text Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .stock.on-back-order'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .stock.on-back-order:before' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'wc_product_stock_text_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'%',
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .stock' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'woocommerce__stock_dot_position',
			[
				'label'      => esc_html__( 'Dot Position', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [
					'px',
					'%',
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-woocommerce-product .stock:before' => 'left: {{SIZE}}{{UNIT}};' ],
			]
		);

		$this->add_control(
			'woocommerce__stock_position',
			[
				'label'   => esc_html__( 'Stock Position', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'after_thumbnail',
				'options' => [
					'after_thumbnail' => esc_html__( 'After Thumbnail', 'ayyash-addons-pro' ),
					'after_price'     => esc_html__( 'After Price', 'ayyash-addons-pro' ),
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __product_hover_actions() {
		$this->start_controls_section(
			'product_actions_settings',
			[
				'label' => 'Product Actions',
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'product_actions_tabs' );

		// Normal State Tab
		$this->start_controls_tab(
			'product_actions_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);

		$this->add_control(
			'product_actions_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcwl-add-to-wishlist,
					{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcqv-button,
					{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .compare-button' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_actions_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcwl-add-to-wishlist a i,
					{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcqv-button i,
					{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .compare-button a i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'product_actions_border',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcwl-add-to-wishlist,
							   {{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcqv-button,
							   {{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .compare-button',
			]
		);

		$this->add_control(
			'product_actions_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'%',
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcwl-add-to-wishlist,
				 				   {{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcqv-button,
								   {{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .compare-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'product_actions_box_shadow',
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcwl-add-to-wishlist,
							   {{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcqv-button,
							   {{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .compare-button',
			]
		);

		$this->end_controls_tab();

		// Hover State Tab
		$this->start_controls_tab(
			'product_actions_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]

		);

		$this->add_control(
			'product_actions_bg_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcwl-add-to-wishlist:hover,
					{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcwl-add-to-wishlist:focus-within,
					{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcqv-button:hover,
					{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcqv-button:focus-visible,
					{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .compare-button:focus-within,
					{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .compare-button:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_actions_icon_color_hover',
			[
				'label'     => esc_html__( 'Icon Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcwl-add-to-wishlist:hover a i,
					{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcwl-add-to-wishlist:focus-within a i,
					{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcqv-button:hover i,
					{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcqv-button:focus-visible i,
					{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .compare-button:focus-within a i,
					{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .compare-button:hover a i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'product_actions_border_hover',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcwl-add-to-wishlist:hover,
								{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcwl-add-to-wishlist:focus-within,
							   {{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcqv-button:hover,
							   {{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcqv-button:focus-visible,
							   {{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .compare-button:focus-within,
							   {{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .compare-button:hover',
			]
		);

		$this->add_control(
			'product_actions_border_radius_hover',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'%',
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcwl-add-to-wishlist:hover,
									{{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcwl-add-to-wishlist:focus-visible,
				 				   {{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcqv-button:hover,
				 				   {{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .yith-wcqv-button:focus-visible,
				 				   {{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .compare-button:focus-visible,
								   {{WRAPPER}} .ayyash-addons-woocommerce-product .product-actions.ayyash-addons-rounded .compare-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'tooltip_height',
			[
				'label'      => esc_html__( 'Height', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 36,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-item .ayyash-tooltip'                                    => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-item .product-actions .yith-wcwl-add-to-wishlist a span' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .ayyash-addons-woocommerce-product .ayyash-addons-product-item .themeoo-tooltip'                                   => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
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

	protected function get_products( $queries ) {
		$args = Ayyash_Addons_Query_Builder::build_query( $queries );

		return get_posts( $args );
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

		$this->init_hooks();

		if ( ! ayyash_addons_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}
		?>
		<div
			class="ayyash-addons-wrapper ayyash-addons-<?php echo str_replace( 'ayyash-', '', $this->get_name() ); // phpcs:ignore
			?>">
			<div class="ayyash-addons-wrapper-inside">
				<div
					class="ayyash-addons-products ayyash-addons-column <?php echo ( 'custom_gap' === $settings['woocommerce_product_column_gap_custom'] ) ? ' has-grid-gap' : ' no-gap'; ?> <?php echo ( $settings['wc_product_content_align'] ) ? esc_attr( $settings['wc_product_content_align'] ) : ''; ?>">
					<?php
					/**
					 * ayyash_addons_woocommerce_products hook.
					 *
					 * @hooked woocommerce_single_product_summary
					 */
					do_action( 'ayyash_addons_woocommerce_products' );
					?>
				</div>
			</div>
		</div>

		<?php
		$this->removed_hooks();
	}

	/**
	 * Initialize all hooks
	 */
	protected function init_hooks() {
		add_action( 'ayyash_addons_woocommerce_products', [ $this, 'render_products_template' ] );
		add_action( 'ayyash_addons_single_product_summary', [ $this, 'ayyash_addons_single_product_header' ], 10, 2 );
		add_action( 'ayyash_addons_single_product_summary', [ $this, 'ayyash_addons_single_product_content' ], 10, 2 );
		add_action( 'ayyash_addons_single_product_summary', [ $this, 'ayyash_addons_single_product_footer' ], 10, 2 );

		// header hooks
		add_action( 'ayyash_addons_single_product_header', [ $this, 'render_product_image' ], 10, 2 );
		add_action( 'ayyash_addons_single_product_header', [ $this, 'render_product_actions' ], 10, 2 );
		add_action( 'ayyash_addons_single_product_header', [ $this, 'render_product_labels' ], 10, 2 );

		// content hooks
//		add_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_stock_text' ], 5, 2 );
		add_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_categories' ], 10, 2 );
		add_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_title' ], 15, 2 );
		add_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_price' ], 20, 2 );

		// Actions hooks
		add_action( 'ayyash_addons_single_product_actions', [ $this, 'render_wishlist' ], 15, 2 );
		add_action( 'ayyash_addons_single_product_actions', [ $this, 'render_quick_view_button' ], 20, 2 );
		add_action( 'ayyash_addons_single_product_actions', [ $this, 'render_compare_button' ], 25, 2 );

		$settings = $this->get_settings_for_display();
		if ( isset( $settings['cart_button_position'] ) ) {
			$button_position = ( $settings['cart_button_position'] ) ? ( $settings['cart_button_position'] ) : '';
			$button_hook     = 'ayyash_addons_single_product_' . $button_position;
			if ( 'actions' == $button_position ) {
				add_action( $button_hook, [
					$this,
					'render_add_to_cart',
				], 50, 2 );
			} else {
				add_action( $button_hook, [ $this, 'render_add_to_cart' ] );
			}
		}

		// product stock
		if ( isset( $settings['woocommerce__stock_position'] ) ) {
			$stock_position = ( $settings['woocommerce__stock_position'] ) ? ( $settings['woocommerce__stock_position'] ) : '';
			$stock_hook     = 'ayyash_addons_single_product_' . $stock_position;
			if ( 'after_price' == $stock_position ) {

				add_action( $stock_hook, [ $this, 'render_product_stock_text' ], 25, 2 );
			} else {
				add_action( $stock_hook, [ $this, 'render_product_stock_text' ], 5, 2 );
			}
		}

		// product rating
		if ( isset( $settings['product_rating_position'] ) ) {
			$rating_position = $settings['product_rating_position'];
			$position_hook   = 'ayyash_addons_single_product_' . $rating_position;
			add_action( $position_hook, [ $this, 'render_product_rating' ], 15, 2 );
		}

		if ( class_exists( 'WPB_Get_Quote_Button' ) ) {
			add_action( 'ayyash_addons_single_product_content_after', [ $this, 'render_product_get_quote_btn' ] );
		}
	}

	/**
	 * Removed all hooks
	 */
	protected function removed_hooks() {
		remove_action( 'ayyash_addons_woocommerce_products', [ $this, 'render_products_template' ] );
		remove_action( 'ayyash_addons_single_product_summary', [ $this, 'ayyash_addons_single_product_header' ] );
		remove_action( 'ayyash_addons_single_product_summary', [ $this, 'ayyash_addons_single_product_content' ] );
		remove_action( 'ayyash_addons_single_product_summary', [ $this, 'ayyash_addons_single_product_footer' ] );

		// header hooks
		remove_action( 'ayyash_addons_single_product_header', [ $this, 'render_product_image' ] );
		remove_action( 'ayyash_addons_single_product_header', [ $this, 'render_product_actions' ] );
		remove_action( 'ayyash_addons_single_product_header', [ $this, 'render_product_labels' ] );

		// content hooks
//		remove_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_stock_text' ], 5 );
		remove_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_categories' ], 10 );
		remove_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_title' ], 15 );
		remove_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_price' ], 20 );

		// Actions hooks
		remove_action( 'ayyash_addons_single_product_actions', [ $this, 'render_wishlist' ], 15 );
		remove_action( 'ayyash_addons_single_product_actions', [ $this, 'render_quick_view_button' ], 20 );
		remove_action( 'ayyash_addons_single_product_actions', [ $this, 'render_compare_button' ], 25 );

		$settings = $this->get_settings_for_display();
		if ( isset( $settings['cart_button_position'] ) ) {
			$button_position = ( $settings['cart_button_position'] ) ? ( $settings['cart_button_position'] ) : '';
			$button_hook     = 'ayyash_addons_single_product_' . $button_position;
			if ( 'actions' == $button_position ) {
				remove_action( $button_hook, [ $this, 'render_add_to_cart' ], 50, 2 );
			} else {
				remove_action( $button_hook, [ $this, 'render_add_to_cart' ] );
			}
		}

		// product stock
		if ( isset( $settings['woocommerce__stock_position'] ) ) {
			$stock_position = ( $settings['woocommerce__stock_position'] ) ? ( $settings['woocommerce__stock_position'] ) : '';
			$stock_hook     = 'ayyash_addons_single_product_' . $stock_position;
			if ( 'after_price' == $stock_position ) {
				remove_action( $stock_hook, [ $this, 'render_product_stock_text' ], 25, 2 );
			} else {
				remove_action( $stock_hook, [ $this, 'render_product_stock_text' ], 5 );
			}
		}

		// product rating
		if ( isset( $settings['product_rating_position'] ) ) {
			$rating_position = $settings['product_rating_position'];
			$position_hook   = 'ayyash_addons_single_product_' . $rating_position;
			remove_action( $position_hook, [ $this, 'render_product_rating' ], 15 );
		}

		if ( class_exists( 'WPB_Get_Quote_Button' ) ) {
			remove_action( 'ayyash_addons_single_product_content_after', [ $this, 'render_product_get_quote_btn' ] );
		}
	}

	public function render_product_actions( $post_id, $settings ) {
		if ( ( ( isset( $settings['enable_wishlist'] ) && 'yes' === $settings['enable_wishlist'] ) || ( isset( $settings['enable_compare'] ) && 'yes' === $settings['enable_compare'] ) || ( isset( $settings['enable_quick_view'] ) && 'yes' === $settings['enable_quick_view'] ) ) ) {
			$actions_style    = ( 'round' === $settings['actions_style'] ) ? 'ayyash-addons-rounded' : '';
			$actions_position = ( $settings['actions_position'] ) ? $settings['actions_position'] : '';
			echo '<div class="product-actions ' . esc_attr( $actions_style ) . ' ' . esc_attr( $actions_position ) . '">';
			do_action( 'ayyash_addons_single_product_actions', $post_id, $settings );
			echo '</div>';
		}
	}

	public function render_product_labels( $post_id, $settings ) {
		$label_type_style = ! empty( $settings['label_type_style'] ) ? $settings['label_type_style'] : '';
		if ( 'yes' === $settings['woocommerce_product_discount_label'] || 'yes' === $settings['woocommerce_product_featured_label'] ) {
			?>
			<div
				class="product-labels <?php echo esc_attr( $label_type_style ); ?>">
				<?php
				if ( 'yes' === $settings['woocommerce_product_discount_label'] ) {
					$this->render_product_discount_label();
				}

				if ( 'yes' === $settings['woocommerce_product_featured_label'] ) {
					$this->render_is_featured();
				}
				?>
			</div>

			<?php
		}
	}

	/**
	 * hook ayyash_addons_single_product_header
	 *
	 * @return void
	 * @hooked render_product_image
	 * @hooked render_product_actions
	 * @hooked render_product_labels
	 */
	public function ayyash_addons_single_product_header( $post_id, $settings ) {
		if ( has_action( 'ayyash_addons_single_product_header' ) ) :
			?>
			<div class="product-header">
				<?php do_action( 'ayyash_addons_single_product_header', $post_id, $settings ); ?>
			</div>

		<?php
		endif;
	}

	/**
	 * hook ayyash_addons_single_product_content
	 *
	 * @return void
	 * @hooked render_product_stock_text
	 * @hooked render_product_categories
	 * @hooked render_product_title
	 * @hooked render_product_price
	 * @hooked render_product_rating
	 */
	public function ayyash_addons_single_product_content( $post_id, $settings ) {
		if ( has_action( 'ayyash_addons_single_product_content' ) ) :
			?>
			<?php do_action( 'ayyash_addons_single_product_content_before' ); ?>
			<div class="product-content">
				<?php do_action( 'ayyash_addons_single_product_content', $post_id, $settings ); ?>
			</div>
			<?php do_action( 'ayyash_addons_single_product_content_after' ); ?>
		<?php
		endif;
	}

	/**
	 * hook ayyash_addons_single_product_footer
	 *
	 * @return void
	 * @hooked render_product_categories
	 * @hooked render_product_title
	 * @hooked render_product_price
	 * @hooked render_product_rating
	 */
	public function ayyash_addons_single_product_footer( $post_id, $settings ) {
		if ( has_action( 'ayyash_addons_single_product_footer' ) ) :
			?>
			<div class="product-footer">
				<?php do_action( 'ayyash_addons_single_product_footer', $post_id, $settings ); ?>
			</div>

		<?php
		endif;
	}

	/**
	 *  Render Products Template
	 *
	 * @return void
	 */
	public function render_products_template() {
		$settings             = $this->get_settings_for_display();
		$hover_grow           = ( 'yes' === $settings['product_hover_grow'] ) ? 'hover-grow-up' : '';
		$hover_classes_prefix = substr_replace( $settings['select_hover_items'], 'hover-', 0, 0 );
		$hover_classes        = implode( ' ', (array) $hover_classes_prefix );

		$args     = Ayyash_Addons_Query_Builder::build_query( $settings['query_builder'] );
		$products = new \WP_Query( $args );

		if ( $products->have_posts() ) {
			while ( $products->have_posts() ) {
				$products->the_post();
				?>
				<div
					class="ayyash-addons-product-item <?php echo esc_attr( $hover_grow . ' ' . $hover_classes ); ?>">
					<div class="ayyash-addons-product-item-inner">

						<?php
						/**
						 * Hook: ayyash_addons_single_product_summary.
						 *
						 * @hooked ayyash_addons_single_product_header - 5
						 * @hooked ayyash_addons_single_product_content - 10
						 * @hooked ayyash_addons_single_product_footer - 15
						 */
						do_action( 'ayyash_addons_single_product_summary', get_the_ID(), $settings );
						?>
					</div>
				</div>

				<?php
			}

			wp_reset_postdata();
		}
	}

	/**
	 * @return void
	 */
	public function render_quick_view_button() {
		$settings = $this->get_settings_for_display();
		global $product;
		$enable_qv = get_option( 'yith-wcqv-enable' );
		if ( isset( $settings['enable_quick_view'] ) && 'yes' === $settings['enable_quick_view'] && 'yes' === $enable_qv ) {
			if ( ayyash_addons_is_plugin_active( 'yith-woocommerce-quick-view/init.php' ) ) {

				if ( ! class_exists( 'YITH_WCQV' ) || ! class_exists( 'YITH_WCQV_Frontend' ) ) {
					return;
				}

				if ( ! empty( $settings['quick_view_label'] ) ) {
					$label = esc_html( $settings['quick_view_label'] );
				} else {
					$label = YITH_WCQV_Frontend::get_instance()->get_button_label();
				}

				$button = '<a href="#" class="ayyash-addons-quick-view-btn yith-wcqv-button" data-product_id="' . esc_attr( get_the_ID() ) . '"><i aria-hidden="true" class="fa fa-eye"></i><span class="product-grid-item-btn-tooltip themeoo-tooltip">' . $label . '</span></a>';
				echo $button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

	/**
	 * @param $label
	 *
	 * @return mixed
	 */
	public function ayyash_yith_wcwl_button_label( $label ) {
		if ( $this->wishlist_add_label ) {
			return $this->wishlist_add_label;
		}

		return $label;
	}

	/**
	 * @param $label
	 *
	 * @return mixed
	 */
	public function yith_wcwl_remove_from_wishlist_label( $label ) {
		if ( $this->wishlist_remove_label ) {
			return $this->wishlist_remove_label;
		}

		return $label;
	}

	/**
	 * @param $before
	 * @param $after
	 *
	 * @return void
	 */
	public function render_wishlist() {
		$before   = '';
		$after    = '';
		$settings = $this->get_settings_for_display();
		global $product;
		$enable_wishlist = get_option( 'yith_wcwl_show_on_loop' );
		if ( isset( $settings['enable_wishlist'] ) && 'yes' === $settings['enable_wishlist'] && 'yes' === $enable_wishlist ) {
			if ( ayyash_addons_is_plugin_active( 'yith-woocommerce-wishlist/init.php' ) ) {
				// woocommerce-product-item-wishlist-btn
				$this->wishlist_add_label    = ! empty( $settings['wishlist_add_label'] ) ? $settings['wishlist_add_label'] : false;
				$this->wishlist_remove_label = ! empty( $settings['wishlist_remove_label'] ) ? $settings['wishlist_remove_label'] : false;

				if ( $this->wishlist_add_label ) {
					add_filter( 'yith_wcwl_button_label', [
						$this,
						'ayyash_yith_wcwl_button_label',
					] );
				}

				if ( $this->wishlist_remove_label ) {
					add_filter(
						'yith_wcwl_remove_from_wishlist_label',
						[
							$this,
							'yith_wcwl_remove_from_wishlist_label',
						]
					);
				}

				wp_kses_post_e( $before );

				echo YITH_WCWL_Shortcode::add_to_wishlist( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					[
						'is_single'  => false,
						'product_id' => $product->get_id(),
					]
				);

				wp_kses_post_e( $after );

				remove_filter( 'yith_wcwl_button_label', [
					$this,
					'ayyash_yith_wcwl_button_label',
				] );
				remove_filter(
					'yith_wcwl_remove_from_wishlist_label',
					[
						$this,
						'yith_wcwl_remove_from_wishlist_label',
					]
				);
			}
		}

	}

	/**
	 * @return void
	 */
	public function render_compare_button( $product_id = false, $args = [] ) {
		$settings       = $this->get_settings_for_display();
		$enable_compare = get_option( 'yith_woocompare_compare_button_in_products_list' );
		if ( isset( $settings['enable_compare'] ) && 'yes' === $settings['enable_compare'] && 'yes' === $enable_compare ) {
			if ( ayyash_addons_is_plugin_active( 'yith-woocommerce-compare/init.php' ) ) {
				global $yith_woocompare;


				if ( ! $yith_woocompare->obj instanceof YITH_Woocompare_Frontend ) {
					return;
				}

				if ( ! $product_id ) {
					global $product;
					$product_id = ! is_null( $product ) ? $product->get_id() : 0;
				}

				// Return if product doesn't exist.
				if ( empty( $product_id ) || apply_filters( 'yith_woocompare_remove_compare_link_by_cat', false, $product_id ) ) {
					return;
				}

				$is_button = ! isset( $button_or_link ) || ! $button_or_link ? get_option( 'yith_woocompare_is_button', 'button' ) : $button_or_link;

				if ( ! isset( $button_text ) || 'default' === $button_text ) {
					$button_text = get_option( 'yith_woocompare_button_text', __( 'Compare', 'ayyash-addons-pro' ) );
					$author      = wp_get_theme()->get( 'Author' );
					if ( in_array( $author, [
						'ThemeRox',
						'Themerox',
						'themerox',
						'ayyash',
						'ayyash',
						'ayyash',
					] ) ) {
						$theme_text_domain = wp_get_theme()->get( 'TextDomain' );
						$compare_icon      = get_option( $theme_text_domain . '_yith_woocompare_compare_icon', 'ai-compare' );
					} else {
						$compare_icon = 'ayyash-ai-compare';
					}

					do_action( 'wpml_register_single_string', 'Plugins', 'plugin_yit_compare_button_text', $button_text );
					$button_text = apply_filters( 'wpml_translate_single_string', $button_text, 'Plugins', 'plugin_yit_compare_button_text' );
				}

				if ( is_product() ) {
					printf( '<div class="product compare-button ayyash-addons-compare-btn"><a href="%s" class="%s" data-product_id="%d" rel="nofollow"><i class="fa %s" aria-hidden="true"></i>%s</a></div>', $yith_woocompare->obj->add_product_url( $product_id ), 'compare' . ( 'button' === $is_button ? ' button' : '' ), $product_id, $compare_icon, $button_text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					printf( '<div class="product compare-button ayyash-addons-compare-btn"><a href="%s" class="%s" data-product_id="%d" rel="nofollow"><i class="fa %s" aria-hidden="true"></i><span class="themeoo-tooltip">%s</span></a></div>', $yith_woocompare->obj->add_product_url( $product_id ), 'compare' . ( 'button' === $is_button ? ' button' : '' ), $product_id, $compare_icon, $button_text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
		}

	}

	/**
	 * @return void
	 */
	public function render_product_image( $post_id, $settings ) {
		global $product;
		$setting_key              = 'wc_product_thumbnail_size';
		$settings[ $setting_key ] = [
			'id' => $product->get_image_id(),
		];
		$thumbnail_style          = '';

		if ( array_key_exists( 'hv_thumb', $settings ) && 'yes' === $settings['hv_thumb'] ) {
			$thumbnail_style = 'orientation_' . Frontend::get_layout( $product );
		}

		?>
		<div class="product-thumbnail <?php echo esc_attr( $thumbnail_style ); ?>">
			<?php if ( 'yes' == $settings['woocommerce_product_image_link'] ) { ?>
				<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
					<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key ) ); ?>
				</a>

				<?php
			} else {
				echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key ) );
			}
			?>
		</div>
		<?php do_action( 'ayyash_addons_single_product_after_thumbnail', $post_id, $settings ); ?>

		<?php
	}

	/**
	 * @return void
	 */
	public function render_product_categories( $post_id, $settings ) {
		if ( isset( $settings['woocommerce_product_category'] ) && 'yes' === $settings['woocommerce_product_category'] ) {
			$terms = get_the_terms( get_the_ID(), 'product_cat' );
			if ( ! empty( $terms ) ) {
				echo '<ul class="ayyash-addons-product-category">';
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

	/**
	 * @return void
	 * $post_id int
	 * $settings array
	 */
	public function render_product_title( $post_id, $settings ) {
		do_action( 'ayyash_addons_single_product_header_before', $post_id, $settings );
		echo '<h3 class="ayyash-addons-heading-title">';
		echo '<a href="' . esc_url( get_permalink() ) . '">';
		if ( empty( $settings['woocommerce_product_title_length'] ) ) {
			echo get_the_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo esc_html( wp_trim_words( get_the_title(), $settings['woocommerce_product_title_length'], '' ) );
		}

		echo '</a></h3>';
		do_action( 'ayyash_addons_single_product_header_after', $post_id, $settings );
	}

	/**
	 * @return void
	 */
	public function render_product_price( $post_id, $settings ) {
		global $product;
		if ( isset( $settings['woocommerce_product_price'] ) && 'yes' === $settings['woocommerce_product_price'] ) {
			?>
			<div
				class="ayyash-addons-product-price-wrapper <?php echo ( 'inline' === $settings['cart_button_style'] ) ? ' inline' : ' '; ?>">
				<div class="ayyash-addons-product-price">
					<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
				<?php do_action( 'ayyash_addons_single_product_after_price', $post_id, $settings ); ?>
			</div>

			<?php
		}
	}

	/**
	 * @return void
	 */
	public function render_product_rating( $post_id, $settings ) {
		$show_product_rating       = isset( $settings['woocommerce_product_rating'] ) && 'yes' === $settings['woocommerce_product_rating'];
		$show_product_rating_count = isset( $settings['woocommerce_product_rating_count'] ) && 'yes' === $settings['woocommerce_product_rating_count'];
		$rating_label              = ( ! empty( $settings['rating_label_text'] ) && 'yes' === $settings['rating_label'] ) ? ' ' . $settings['rating_label_text'] : '';
		global $product;
		if ( $show_product_rating ) {
			$rating_count = $product->get_rating_count();
			$review_count = $product->get_review_count();
			$average      = $product->get_average_rating();
			if ( $show_product_rating_count ) {
				if ( $rating_count ) {
					?>
					<div class="ayyash-addons-product-rating">
						<?php echo wc_get_rating_html( $average, $review_count ); // phpcs:ignore?>
						<?php echo '<span class="count">(' . esc_html( $review_count . $rating_label ) . ') </span>'; ?>
					</div>

					<?php
				}
			} else {
				if ( $rating_count ) {
					?>
					<div class="ayyash-addons-product-rating">
						<?php echo wc_get_rating_html( $average, $review_count ); // phpcs:ignore?>
					</div>

					<?php
				}
			}
		}
	}

	/**
	 * @return void
	 */
	public function render_product_discount_label() {
		$settings = $this->get_settings_for_display();

		global $product;
		if ( ! $product->is_on_sale() ) {
			return;
		}

		$precision = 0;
		$discount  = 0;

		$sale_price    = $product->get_sale_price();
		$regular_price = $product->get_regular_price();

		$suffix = ( ! empty( $settings['discount_label_suffix'] ) ) ? $settings['discount_label_suffix'] : ' ';
		$prefix = ( ! empty( $settings['discount_label_prefix'] ) ) ? $settings['discount_label_prefix'] : ' ';

		if ( ! empty( $sale_price ) && $regular_price > $sale_price ) {
			$discount = ( ( ( (float) $regular_price - (float) $sale_price ) / (float) $regular_price ) * 100 );
			$discount = round( $discount, $precision );
		}

		if ( $discount > 0 ) {
			printf( '<span class="ayyash-addons-discount fill">%s%s%s</span>', esc_html( $prefix ), (float) $discount, esc_html( $suffix ) );
		}
	}

	/**
	 * @return void
	 */
	public function render_is_featured() {
		$settings = $this->get_settings_for_display();
		global $product;
		if ( $product->is_featured() ) {
			printf( '<span class="ayyash-addons-featured fill">%s</span>', esc_html__( 'featured', 'ayyash-addons-pro' ) );
		}
	}

	/**
	 * @return void
	 */
	public function render_add_to_cart() {
		$settings                = $this->get_settings_for_display();
		$enable_add_to_cart_btn  = $settings['enable_add_to_cart'];
		$enable_add_to_cart_icon = ( isset( $settings['enable_add_to_cart_icon'] ) && 'yes' === $settings['enable_add_to_cart_icon'] ) ? 'has-icon' : '';
		$enable_add_to_cart_text = ( isset( $settings['enable_add_to_cart_text'] ) && 'yes' === $settings['enable_add_to_cart_text'] ) ? 'has-text' : '';

		if ( 'yes' === $enable_add_to_cart_btn ) {
			?>
			<div
				class="ayyash-cart-btn <?php echo esc_attr( $enable_add_to_cart_icon . ' ' . $enable_add_to_cart_text ); ?>">
				<?php ayyash_addons_wc_loop_add_to_cart(); ?>
			</div>

			<?php
		}
	}

	/**
	 * @return void
	 */
	public function render_product_stock_text( $post_id, $settings ) {
		if ( isset( $settings['woocommerce_product_stock'] ) && 'yes' === $settings['woocommerce_product_stock'] ) {
			$stock_status = get_post_meta( $post_id, '_stock_status', true );
			$stock        = get_post_meta( $post_id, '_stock', true );

			// Check stock status, adjust the message accordingly
			switch ( $stock_status ) {
				case 'instock':
					echo '<div class="stock in-stock">' . esc_html__( 'In Stock ', 'ayyash-addons-pro' ) . esc_html( $stock ) . '</div>';
					break;

				case 'outofstock':
					echo '<div class="stock out-of-stock">' . esc_html__( 'Out of Stock ', 'ayyash-addons-pro' ) . '</div>';
					break;

				case 'onbackorder':
					echo '<div class="stock on-back-order">' . esc_html__( 'Available after 4-7 days', 'ayyash-addons-pro' ) . '</div>';
					break;
			}
		}


	}

	public function render_product_get_quote_btn() {
		if ( class_exists( 'WPB_Get_Quote_Button' ) ) {

			$get_options         = get_option( 'woo_settings' );
			$loop_show           = $get_options['woo_loop_show_quote_form'];
			$btn_position        = $get_options['wpb_gqb_btn_position'];
			$loop_position       = ( 'after_cart' == $btn_position ? 10 : 6 );
			$woocommerce_handler = new WPB_GQB_WooCommerce_Handler();
			if ( 'on' === $loop_show ) {

				remove_action( apply_filters( 'wpb_gqb_woo_loop_position', 'woocommerce_after_shop_loop_item' ), array(
					$this,
					'woo_add_contact_form_button',
				), apply_filters( 'wpb_gqb_woo_loop_priority', $loop_position ) );

				echo '<div class="mt-2 mb-2 ayyash-addons-quote-btn">';
				$woocommerce_handler->woo_add_contact_form_button();
				echo '</div>';
			}
		}
	}

}

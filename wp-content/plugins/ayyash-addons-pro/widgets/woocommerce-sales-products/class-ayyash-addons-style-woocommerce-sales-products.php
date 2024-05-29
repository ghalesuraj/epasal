<?php

namespace AyyashAddonsPro\Widgets;

use AyyashAddons\Controls\Ayyash_Addons_Query_Builder;
use AyyashAddonsPro\Ayyash_Pro_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class AyyashAddons_Style_Woocommerce_Sales_Products extends Ayyash_Pro_Widget {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ayyash-woocommerce-sales-products';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Woocommerce Sales Products', 'ayyash-addons-pro' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'ayyash-addons eicon-product-upsell';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-pro-woocommerce-sales-products',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'ayyash-addons-pro-countdown',
			'ayyash-addons-pro-woocommerce-sales-products',
		);
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
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'ayyash-pro-widgets' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function register_controls() {

		// Start Product Query Section
		$this->start_controls_section(
			'section_product_query',
			array(
				'label' => esc_html__( 'Product Query', 'ayyash-addons-pro' ),
			)
		);

		$this->add_control( 'query_builder', [
			'label'   => __( 'Product Query', 'ayyash-addons-pro' ),
			'type'    => 'ayyash_addons_query_builder',
			'default' => [
				'post_type'         => 'product',
				'disable_post_type' => true,
				//'disable_date_query_after'  => true,
				//'disable_date_query_before' => true,
			],
		] );

		$this->end_controls_section();
		// End of Product Query Section

		// Start Product Settings Section
		$this->start_controls_section(
			'section_product_setting',
			array(
				'label' => esc_html__( 'Product Settings', 'ayyash-addons-pro' ),
			)
		);

		$this->add_control(
			'woocommerce_product_title_length',
			[
				'label' => esc_html__( 'Product Title Length', 'ayyash-addons-pro' ),
				'type'  => Controls_Manager::NUMBER,
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
				'condition'    => [
					'woocommerce_product_rating' => 'yes',
				],
			]
		);

		$this->add_control(
			'woocommerce_product_stock',
			[
				'label'        => esc_html__( 'Product Stock?', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->end_controls_section();
		// End of Product Settings Section

		// Start Style Section
		$this->_product_thumbnail();
		$this->_product_title();
		$this->_product_rating();
		$this->_product_short_desc();
		$this->_product_price();
		$this->_product_stock();
		// End of Style Section


	}

	protected function _product_thumbnail() {
		$this->start_controls_section(
			'wc_sales_product_thumbnail_settings',
			[
				'label' => esc_html__( 'Product Thumbnail', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'           => 'wc_sales_product_thumbnail_size',
				'exclude'        => [ 'custom' ],
				'default'        => 'woocommerce_thumbnail',
				'style_transfer' => true,
			]
		);

		$this->start_controls_tabs( 'wc_product_thumbnail_tabs' );

		// Normal State Tab
		$this->start_controls_tab(
			'wc_sales_product_thumbnail_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'wc_sales_product_thumbnail_background',
				'label'          => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'label_block'    => true,
				'types'          => [
					'classic',
					'gradient',
				],
				'fields_options' => [
					'background' => [ 'label' => 'Background' ],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-sales-product-image',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wc_sales_product_thumbnail_border',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-sales-product-image',
			]
		);

		$this->add_control(
			'wc_sales_product_thumbnail_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'%',
					'em',
				],
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-sales-product-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);


		$this->end_controls_tab();

		// Hover State Tab
		$this->start_controls_tab(
			'wc_sales_product_thumbnail_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'wc_sales_product_thumbnail_background_hover',
				'label'          => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'label_block'    => true,
				'types'          => [
					'classic',
					'gradient',
				],
				'fields_options' => [
					'background' => [ 'label' => 'Background' ],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-sales-product-image:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wc_sales_product_thumbnail_border_hover',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-sales-product-image:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		//height
		$this->add_responsive_control(
			'wc_sales_product_thumbnail_height',
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
				'selectors'      => [ '{{WRAPPER}} .ayyash-addons-sales-product-image' => 'height: {{SIZE}}{{UNIT}};' ],
			]
		);

		//width
		$this->add_responsive_control(
			'wc_sales_product_thumbnail_width',
			[
				'label'          => esc_html__( 'Width', 'ayyash-addons-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'unit' => 'px',
					'size' => 132,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 132,
				],
				'mobile_default' => [
					'unit' => '%',
					'size' => 100,
				],
				'size_units'     => [ 'px', 'vh', '%' ],
				'range'          => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .ayyash-addons-sales-products-header' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function _product_title() {
		$this->start_controls_section(
			'wc_sales_product_title_settings',
			[
				'label' => esc_html__( 'Product Title', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'wc_sales_product_title_tabs' );

		// Normal State Tab
		$this->start_controls_tab(
			'wc_sales_product_title_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'wc_sales_product_title_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-heading-title a' => 'color: {{VALUE}};' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_sales_product_title_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-heading-title a',
			]
		);
		$this->end_controls_tab();

		// Hover State Tab
		$this->start_controls_tab(
			'wc_sales_product_title_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'wc_sales_product_title_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-heading-title a:hover' => 'color: {{VALUE}};' ],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'sales_product_title_gap',
			[
				'label'      => __( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit'   => 'px',
					'top'    => 0,
					'right'  => 0,
					'bottom' => 10,
					'left'   => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-heading-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function _product_rating() {
		$this->start_controls_section(
			'wc_sales_product_rating_settings',
			[
				'label' => esc_html__( 'Product Rating', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'product_rating_gap',
			[
				'label'      => __( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit'   => 'px',
					'top'    => 0,
					'right'  => 0,
					'bottom' => 18,
					'left'   => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-rating' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function _product_short_desc() {
		$this->start_controls_section(
			'wc_sales_product_short_desc_settings',
			[
				'label' => esc_html__( 'Product Short Description', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wc_sales_product_short_desc_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-product-short-description p' => 'color: {{VALUE}};' ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_sales_product_short_desc_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-product-short-description p',
			]
		);

		$this->add_control(
			'sales_product_short_desc_gap',
			[
				'label'      => __( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit'   => 'px',
					'top'    => 0,
					'right'  => 0,
					'bottom' => 24,
					'left'   => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-short-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function _product_price() {
		$this->start_controls_section(
			'wc_sales_product_price_settings',
			[
				'label' => esc_html__( 'Product Price', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'wc_sales_product_price_tabs' );
		// Regular price  Tab
		$this->start_controls_tab(
			'wc_sales_product_regular_price_tab',
			[
				'label' => esc_html__( 'Regular price', 'ayyash-addons-pro' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_sales_product_regular_price_typography',
				'label'    => esc_html__( 'Typography', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-product-price .woocommerce-Price-amount bdi,
						{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-product-price ins .woocommerce-Price-amount bdi
						',
			]
		);
		$this->add_control(
			'wc_sales_product_regular_price_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'
			{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-product-price ,
			{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-product-price .woocommerce-Price-amount bdi,
			{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-product-price ins .woocommerce-Price-amount bdi
            ' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		// Sale Price tab
		$this->start_controls_tab(
			'wc_sales_product_sale_price_tab',
			[
				'label' => esc_html__( 'Sale price', 'ayyash-addons-pro' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_sales_product_sale_price_typography',
				'label'    => esc_html__( 'Typography', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-product-price del .woocommerce-Price-amount bdi',
			]
		);
		$this->add_control(
			'wc_sales_product_sale_price_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-sales-product-wrapper .ayyash-addons-product-price del .woocommerce-Price-amount bdi' => 'color: {{VALUE}};' ],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'product_price_gap',
			[
				'label'      => __( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit'   => 'px',
					'top'    => 0,
					'right'  => 0,
					'bottom' => 6,
					'left'   => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function _product_stock() {
		$this->start_controls_section(
			'wc_product_stock_settings',
			[
				'label' => esc_html__( 'Product Stock', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'product_stock_gap',
			[
				'label'      => __( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit'   => 'px',
					'top'    => 0,
					'right'  => 0,
					'bottom' => 24,
					'left'   => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-sales-product-wrapper .stock' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}


	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			printf( '<div class="ayyash-addons-alert alert-warning">%s</div>', __( 'Please Install/Activate Woocommerce Plugin.', 'ayyash-addons-pro' ) );

			return;
		}

		$settings = $this->get_settings_for_display();

		$this->init_hooks();

		?>

		<div class="ayyash-addons-sales-product-wrapper ayyash-addons-<?php echo str_replace( 'ayyash-', '', $this->get_name() ); // phpcs:ignore ?>">
			<div class="ayyash-addons-sales-product-wrapper-inside">
				<?php
				do_action( 'ayyash_addons_sales_products' );
				?>
			</div>
		</div>

		<?php

		$this->removed_hooks();

	}

	/**
	 * Initialize all hooks
	 */
	protected function init_hooks() {
		add_action( 'ayyash_addons_sales_products', [ $this, 'render_sales_products_template' ] );

		add_action( 'ayyash_addons_sales_products_summary', [ $this, 'render_sales_wrapper_header' ], 10, 2 );
		add_action( 'ayyash_addons_sales_products_summary', [ $this, 'render_sales_wrapper_content' ], 10, 2 );

		add_action( 'ayyash_addons_sales_products_header', [ $this, 'render_sales_products_image' ], 10, 2 );
		add_action( 'ayyash_addons_sales_products_content', [ $this, 'render_sales_products_title' ], 10, 2 );
		add_action( 'ayyash_addons_sales_products_content', [ $this, 'render_sales_product_rating' ], 10, 2 );
		add_action( 'ayyash_addons_sales_products_content', [ $this, 'render_sales_product_short_desc' ] );
		add_action( 'ayyash_addons_sales_products_content', [ $this, 'render_sales_product_price' ], 10, 2 );
		add_action( 'ayyash_addons_sales_products_content', [ $this, 'render_sales_product_stock' ], 10, 2 );
		add_action( 'ayyash_addons_sales_products_content', [ $this, 'render_sales_simple_product_countdown' ] );
		add_action( 'ayyash_addons_sales_products_content', [ $this, 'render_sales_variable_product_countdown' ] );

	}

	/**
	 * Removed all hooks
	 */
	protected function removed_hooks() {
		remove_action( 'ayyash_addons_sales_products', [ $this, 'render_sales_products_template' ] );

		remove_action( 'ayyash_addons_sales_products_summary', [ $this, 'render_sales_wrapper_content' ], 10, 2 );
		remove_action( 'ayyash_addons_sales_products_summary', [ $this, 'render_sales_wrapper_header' ], 10, 2 );

		remove_action( 'ayyash_addons_sales_products_header', [ $this, 'render_sales_products_image' ] );
		remove_action( 'ayyash_addons_sales_products_content', [ $this, 'render_sales_products_title' ], 10, 2 );
		remove_action( 'ayyash_addons_sales_products_content', [ $this, 'render_sales_product_rating' ], 10, 2 );
		remove_action( 'ayyash_addons_sales_products_content', [ $this, 'render_sales_product_price' ], 10, 2 );
		remove_action( 'ayyash_addons_sales_products_content', [ $this, 'render_sales_product_short_desc' ] );
		remove_action( 'ayyash_addons_sales_products_content', [ $this, 'render_sales_simple_product_countdown' ] );
		remove_action( 'ayyash_addons_sales_products_content', [ $this, 'render_sales_variable_product_countdown' ] );
	}

	/**
	 * hook ayyash_addons_single_product_header
	 * @return void
	 * @hooked render_product_image
	 */
	public function render_sales_wrapper_header( $post_id, $settings ) {
		?>
		<div class="ayyash-addons-sales-products-header">
			<?php do_action( 'ayyash_addons_sales_products_header', $post_id, $settings ); ?>
		</div>
		<?php
	}

	/**
	 * hook ayyash_addons_single_product_content
	 * @return void
	 * @hooked render_product_title
	 * @hooked render_product_price
	 * @hooked render_product_rating
	 */
	public function render_sales_wrapper_content( $post_id, $settings ) {
		?>
		<div class="ayyash-addons-sales-products-content">
			<?php do_action( 'ayyash_addons_sales_products_content', $post_id, $settings ); ?>
		</div>
		<?php
	}

	/**
	 *  Render Products Template
	 * @return void
	 */
	public function render_sales_products_template() {
		$settings = $this->get_settings_for_display();
		$args     = Ayyash_Addons_Query_Builder::build_query( $settings['query_builder'] );

		$sales_products = new \WP_Query( $args );

		if ( $sales_products->have_posts() ) {
			while ( $sales_products->have_posts() ) {
				$sales_products->the_post();
				?>
				<div class="ayyash-addons-sales-products-item">
					<div class="ayyash-addons-sales-product-item-inner">
						<?php
						/**
						 * Hook: ayyash_addons_sales_products_summary.
						 *
						 * @hooked ayyash_addons_single_product_header - 5
						 * @hooked ayyash_addons_single_product_content - 10
						 * @hooked ayyash_addons_single_product_footer - 15
						 */
						do_action( 'ayyash_addons_sales_products_summary', get_the_ID(), $settings );
						?>
					</div>
				</div>
				<?php
			}
			wp_reset_postdata();
		}

	}

	public function render_sales_products_image( $post_id, $settings ) {
		global $product;
		$setting_key              = 'wc_sales_product_thumbnail_size';
		$settings[ $setting_key ] = [
			'id' => $product->get_image_id(),
		];
		?>
		<div class="ayyash-addons-sales-product-image">
			<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key ) ); ?>
		</div>
		<?php
	}

	public function render_sales_products_title( $post_id, $settings ) {
		do_action( 'ayyash_addons_sales_title_before', $post_id, $settings );
		echo '<h3 class="ayyash-addons-heading-title">';
		echo '<a href="' . esc_url( get_permalink() ) . '">';
		if ( empty( $settings['woocommerce_product_title_length'] ) ) {
			echo get_the_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo esc_html( wp_trim_words( get_the_title(), $settings['woocommerce_product_title_length'], '' ) );
		}
		echo '</a></h3>';
		do_action( 'ayyash_addons_sales_title_after', $post_id, $settings );
	}

	public function render_sales_product_rating( $post_id, $settings ) {
		$show_product_rating       = isset( $settings['woocommerce_product_rating'] ) && 'yes' === $settings['woocommerce_product_rating'];
		$show_product_rating_count = isset( $settings['woocommerce_product_rating_count'] ) && 'yes' === $settings['woocommerce_product_rating_count'];

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
						<?php
						printf( '<span class="count">(%1$s %2$s)</span>',
							esc_html( $review_count ),
							esc_html( _n( 'review', 'reviews', $review_count, 'ayyash-addons-pro' ) )
						);
						?>
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

	public function render_sales_product_short_desc() {
		global $product;
		$short_desc = wp_trim_words( $product->get_short_description(), 10 );
		?>
		<div class="ayyash-addons-product-short-description">
			<p><?php echo $short_desc; ?></p>
		</div>
		<?php
	}

	public function render_sales_product_price( $post_id, $settings ) {
		global $product;
		if ( isset( $settings['woocommerce_product_price'] ) && 'yes' === $settings['woocommerce_product_price'] ) {
			?>
			<div class="ayyash-addons-product-price">
				<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput  ?>
			</div>
			<?php
		}
	}

	public function render_sales_product_stock( $post_id, $settings ) {
		if ( isset ( $settings['woocommerce_product_stock'] ) && 'yes' === $settings['woocommerce_product_stock'] ) {
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

	public function render_sales_simple_product_countdown() {
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
		$end_time_countdown = date( 'Y/m/d H:i:s', $end_time ) . ' ' . $end_time_zone;

		?>
		<div class="sales-product-countdown" id="sales_product_countdown" data-end-date="<?php echo esc_attr( $end_time_countdown ); ?>"></div>
		<?php
	}

	public function render_sales_variable_product_countdown() {
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

		$end_time = date( 'Y/m/d H:i:s T', $min_time );
		?>
		<div class="sales-product-wrapper">
			<div class="sales-product-title"><?php esc_html_e('Offer end: ','ayyash-addons-pro'); ?></div>
			<div class="sales-product-countdown" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" id="sales_product_countdown" data-end-date="<?php echo esc_attr( $end_time ); ?>"></div>
		</div>
		<?php
	}

}


<?php

namespace AyyashAddonsPro\Widgets;

use AyyashAddonsPro\Ayyash_Pro_Widget;
use Elementor\Controls_Manager;
use AyyashAddons\Controls\Ayyash_Addons_Query_Builder;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class AyyashAddons_Style_Woocommerce_Product_deal extends Ayyash_Pro_Widget {

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
		return 'ayyash-woocommerce-product-deal';
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
		return __( 'Woocommerce Product Deals', 'ayyash-addons-pro' );
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
		return 'ayyash-addons eicon-product-price';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-pro-woocommerce-product-deal',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array();
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
			],
		] );
		$this->end_controls_section();

		//Product grid product settings controller sections
		$this->start_controls_section(
			'section_product_settings',
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
		$this->end_controls_section();

		$this->__product_body_style();
		$this->__product_thumbnail();
		$this->__product_title();
		$this->__product_price_style();
		$this->__product_rating_style();

	}

	protected function __product_body_style() {
		//Product body
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
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Background',
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-item-inner',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wc_product_body_border',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-product-item-inner',
			]
		);

		$this->add_control(
			'wc_product_body_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wc_product_body_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'wc_product_body_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-product-item-inner',
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
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Background',
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-item:hover .ayyash-addons-product-item-inner',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wc_product_body_border_hover',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-product-item:hover .ayyash-addons-product-item-inner',
			]
		);

		$this->add_control(
			'wc_product_body_border_radius_hover',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-item:hover .ayyash-addons-product-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wc_product_body_padding_hover',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-item:hover .ayyash-addons-product-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'           => 'wc_product_body_box_shadow_hover',
				'label'          => esc_html__( 'Box Shadow', 'ayyash-addons-pro' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-item:hover .ayyash-addons-product-item-inner',
				'fields_options' =>
					[
						'box_shadow_type' =>
							[
								'default' => 'yes',
							],
						'box_shadow'      => [
							'default' =>
								[
									'horizontal' => 0,
									'vertical'   => 10,
									'blur'       => 14,
									'spread'     => 0,
									'color'      => 'rgba(16, 19, 24, 0.13)',
								],
						],
					],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __product_price_style() {
		//==Product Price==
		$this->start_controls_section(
			'wc_product_price_settings',
			[
				'label'     => esc_html__( 'Product Price', 'ayyash-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'woocommerce_product_price' => 'yes',
				],
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
				'selector' => '{{WRAPPER}} .ayyash-addons-product-price .woocommerce-Price-amount bdi,
						{{WRAPPER}} .ayyash-addons-product-price ins .woocommerce-Price-amount bdi
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
			{{WRAPPER}} .ayyash-addons-product-price .woocommerce-Price-amount bdi,
			{{WRAPPER}} .ayyash-addons-product-price ins .woocommerce-Price-amount bdi
            ' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		//Sale Price tab
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
				'selector' => '{{WRAPPER}} .ayyash-addons-product-price del .woocommerce-Price-amount bdi',
			]
		);
		$this->add_control(
			'wc_product_sale_price_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-price del .woocommerce-Price-amount bdi' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'wc_product_price_bottom_space',
			[
				'label'     => esc_html__( 'Spacing', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
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
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-rating .star-rating span:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wc_product_rating_count_color',
			[
				'label'     => esc_html__( 'Count Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-rating' => 'color: {{VALUE}};',
				],
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
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Background',
					],
				],
				'selector'       => '{{WRAPPER}} .product-thumbnail',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wc_product_thumbnail_border',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .product-thumbnail',
			]
		);

		$this->add_control(
			'wc_product_thumbnail_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .product-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wc_product_thumbnail_height',
			[
				'label'          => esc_html__( 'Height', 'ayyash-addons-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units'     => [ 'px', 'vh' ],
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
				'selectors'      => [
					'{{WRAPPER}} .product-thumbnail' => 'height: {{SIZE}}{{UNIT}};',
				],
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
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Background',
					],
				],
				'selector'       => '{{WRAPPER}} .product-thumbnail:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wc_product_thumbnail_border_hover',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .product-thumbnail:hover',
			]
		);

		$this->add_control(
			'wc_product_thumbnail_border_radius_hover',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .product-thumbnail:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
				'selectors' => [
					'{{WRAPPER}} h3.ayyash-addons-heading-title a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_product_title_typography',
				'selector' => '{{WRAPPER}} h3.ayyash-addons-heading-title a',
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
				'selectors' => [
					'{{WRAPPER}} h3.ayyash-addons-heading-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'wc_product_title_bottom_space',
			[
				'label'     => esc_html__( 'Spacing', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} h3.ayyash-addons-heading-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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

		if ( ! ayyash_addons_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}
		?>
		<div
			class="ayyash-addons-wrapper ayyash-addons-<?php echo str_replace( 'ayyash-', '', $this->get_name() ); // phpcs:ignore ?>">
			<div class="ayyash-addons-wrapper-inside">

				<!-- ayyash-addons-woocommerce-header -->
				<div
					class="ayyash-addons-products <?php echo ( $settings['wc_product_content_align'] ) ? esc_attr( $settings['wc_product_content_align'] ) : ''; ?>">
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
		add_action( 'ayyash_addons_single_product_summary', [ $this, 'ayyash_addons_single_product_header' ] );
		add_action( 'ayyash_addons_single_product_summary', [ $this, 'ayyash_addons_single_product_content' ] );

		//header hooks
		add_action( 'ayyash_addons_single_product_header', [ $this, 'render_product_image' ] );

		//content hooks
		add_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_title' ], 10 );
		add_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_price' ], 15 );
		add_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_rating' ], 15 );

	}

	/**
	 * Removed all hooks
	 */
	protected function removed_hooks() {
		remove_action( 'ayyash_addons_woocommerce_products', [ $this, 'render_products_template' ] );
		remove_action( 'ayyash_addons_single_product_summary', [ $this, 'ayyash_addons_single_product_header' ] );
		remove_action( 'ayyash_addons_single_product_summary', [ $this, 'ayyash_addons_single_product_content' ] );

		//header hooks
		remove_action( 'ayyash_addons_single_product_header', [ $this, 'render_product_image' ] );

		//content hooks
		remove_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_title' ], 10 );
		remove_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_price' ], 15 );
		remove_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_rating' ], 15 );

	}

	/**
	 * hook ayyash_addons_single_product_header
	 * @return void
	 * @hooked render_product_image
	 */
	public function ayyash_addons_single_product_header() {
		if ( has_action( 'ayyash_addons_single_product_header' ) ) :
			?>
			<div class="product-header">
				<?php do_action( 'ayyash_addons_single_product_header' ); ?>
			</div>
		<?php
		endif;
	}

	/**
	 * hook ayyash_addons_single_product_content
	 * @return void
	 * @hooked render_product_title
	 * @hooked render_product_price
	 * @hooked render_product_rating
	 */
	public function ayyash_addons_single_product_content() {
		if ( has_action( 'ayyash_addons_single_product_content' ) ) :
			?>
			<div class="product-content">
				<?php do_action( 'ayyash_addons_single_product_content' ); ?>
			</div>
		<?php
		endif;
	}

	/**
	 *  Render Products Template
	 * @return void
	 */
	public function render_products_template() {
		$settings = $this->get_settings_for_display();
		$args     = Ayyash_Addons_Query_Builder::build_query( $settings['query_builder'] );
		$products = new \WP_Query( $args );
		?>
		<div class="ayyash-addons-columns-wrapper">
			<?php if ( $products->have_posts() ) {
				while ( $products->have_posts() ) {
					$products->the_post();
					?>
					<div class="ayyash-addons-product-item">
							<div class="ayyash-addons-product-item-inner">
								<?php do_action( 'ayyash_addons_single_product_summary' ); ?>
							</div>
					</div>
					<?php
				}
				wp_reset_postdata();
			}
			?>
		</div>
		<?php
	}

	/**
	 * @return void
	 */
	public function render_product_image() {
		$settings = $this->get_settings_for_display();
		global $product;
		?>
		<div class="product-thumbnail">
			<?php
			echo $product->get_image(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</div>
		<?php
	}

	/**
	 * @return void
	 */
	public function render_product_title() {
		$settings = $this->get_settings_for_display();
		echo '<h3 class="ayyash-addons-heading-title">';
		echo '<a href="' . esc_url( get_permalink() ) . '">';
		if ( empty( $settings['woocommerce_product_title_length'] ) ) {
			echo get_the_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo esc_html( wp_trim_words( get_the_title(), $settings['woocommerce_product_title_length'], '' ) );
		}
		echo '</a></h3>';
	}

	/**
	 * @return void
	 */
	public function render_product_price() {
		$settings = $this->get_settings_for_display();
		global $product;
		if ( isset( $settings['woocommerce_product_price'] ) && 'yes' === $settings['woocommerce_product_price'] ) {
			?>
			<div
				class="ayyash-addons-product-price-wrapper">
				<div class="ayyash-addons-product-price">
					<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput  ?>
				</div>
				<?php do_action( 'ayyash_addons_single_product_after_price' ); ?>
			</div>
			<?php
		}
	}

	/**
	 * @return void
	 */
	public function render_product_rating() {
		$settings                  = $this->get_settings_for_display();
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
						<?php echo '<span class="count">(' . esc_html( $review_count ) . ')</span>'; ?>
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
}

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
use YITH_WCWL_Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class AyyashAddons_Style_Woocommerce_Product_List extends Ayyash_Pro_Widget {

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
		return 'ayyash-woocommerce-product-list';
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
		return __( 'Product List', 'ayyash-addons-pro' );
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
		return 'ayyash-addons eicon-post-list';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-pro-woocommerce-product-list',
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

		/**
		 * Fires after controllers are registered.
		 *
		 * @param AyyashAddons_Style_Woocommerce_Product $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

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
		$this->add_responsive_control(
			'woocommerce_product_column',
			[
				'label'     => esc_html__( 'Grid Column', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => [
					'1' => esc_html__( '1', 'ayyash-addons-pro' ),
					'2' => esc_html__( '2', 'ayyash-addons-pro' ),
					'3' => esc_html__( '3', 'ayyash-addons-pro' ),
					'4' => esc_html__( '4', 'ayyash-addons-pro' ),
					'5' => esc_html__( '5', 'ayyash-addons-pro' ),
					'6' => esc_html__( '6', 'ayyash-addons-pro' ),
				],
				'selectors' => [ '{{WRAPPER}} .ayyash-addons-products' => 'grid-template-columns: repeat({{VALUE}},1fr);' ],
			]
		);
		$this->add_responsive_control(
			'woocommerce_product_column_gap',
			[
				'label'      => esc_html__( 'Grid Gap', 'ayyash-addons-pro' ),
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
				'selectors'  => [ '{{WRAPPER}} .ayyash-addons-products' => 'gap: {{SIZE}}{{UNIT}};' ],
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
		// Product rating settings
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
			'rating_settings',
			[
				'label'     => esc_html__( 'Product Rating Settings', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'woocommerce_product_rating' => 'yes',
				],
			]
		);
		$this->add_control(
			'product_rating_position',
			[
				'label'     => esc_html__( 'Rating Position', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'title_before',
				'options'   => [
					'title_before' => esc_html__( 'Before Title', 'ayyash-addons-pro' ),
					'title_after'  => esc_html__( 'After Title', 'ayyash-addons-pro' ),
					'after_price'  => esc_html__( 'After Price', 'ayyash-addons-pro' ),
				],
				'condition' => [
					'woocommerce_product_rating' => 'yes',
				],
			]
		);
		$this->add_control(
			'rating_label_text',
			[
				'label'     => esc_html__( 'Rating Label Text', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Reviews', 'ayyash-addons-pro' ),
				'condition' => [
					'woocommerce_product_rating'       => 'yes',
					'woocommerce_product_rating_count' => 'yes',
				],
			]
		);

		//wishlist
		$enable_wishlist = get_option( 'yith_wcwl_show_on_loop' );
		if ( ayyash_addons_is_plugin_active( 'yith-woocommerce-wishlist/init.php' ) && 'yes' === $enable_wishlist ) {
			$this->add_control(
				'enable_wishlist',
				[
					'label'        => esc_html__( 'Enable Wishlist', 'ayyash-addons-pro' ),
					'type'         => Controls_Manager::SWITCHER,
					'separator'    => 'before',
					'return_value' => 'yes',
					'default'      => 'yes',
				]
			);
		}

		$this->end_controls_section();

		$this->__product_body_style();
		$this->__product_thumbnail();
		$this->__product_title();
		$this->__product_price_style();
		$this->__product_rating_style();

		/**
		 * Fires after controllers are registered.
		 *
		 * @param AyyashAddons_Style_Woocommerce_Product $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */

		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );
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
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-item ',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'wc_product_body_border',
				'label'          => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width'  => [
						'default' => [
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
							'isLinked' => true,
						],
					],
					'color'  => [
						'default' => '#EBEBEB',
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-item ',
			]
		);

		$this->add_control(
			'wc_product_body_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-item ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .ayyash-addons-product-item ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'wc_product_body_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-product-item ',
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
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-item:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wc_product_body_border_hover',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-product-item:hover',
			]
		);

		$this->add_control(
			'wc_product_body_border_radius_hover',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'           => 'wc_product_body_box_shadow_hover',
				'label'          => esc_html__( 'Box Shadow', 'ayyash-addons-pro' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-item:hover',
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
				'label'     => esc_html__( 'Label Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-rating' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'rating_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-product-rating',
			]
		);
		$this->add_responsive_control(
			'wc_product_rating_bottom_space',
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
					'{{WRAPPER}} .ayyash-addons-product-rating' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'           => 'wc_product_thumbnail_size',
				'separator'      => 'after',
				'exclude'        => [ 'custom' ],
				// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'default'        => 'woocommerce_thumbnail',
				'style_transfer' => true,
			]
		);

		$this->add_responsive_control(
			'wc_product_thumbnail_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'    => '10',
					'right'  => '10',
					'bottom' => '10',
					'left'   => '10',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .product-thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		//thumbnail gap
		$this->add_responsive_control(
			'wc_product_thumbnail_gap',
			[
				'label'      => esc_html__( 'Gap', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'size_units' => [ 'px', 'vh' ],
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
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-item' => 'gap: {{SIZE}}{{UNIT}};',
				],
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

		//height
		$this->add_responsive_control(
			'wc_product_thumbnail_height',
			[
				'label'          => esc_html__( 'Height', 'ayyash-addons-pro' ),
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
					'unit' => 'px',
					'size' => 152,
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

		//width
		$this->add_responsive_control(
			'wc_product_thumbnail_width',
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
					'{{WRAPPER}} .product-header' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

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
					'{{WRAPPER}} h3.ayyash-addons-product-title a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_product_title_typography',
				'selector' => '{{WRAPPER}} h3.ayyash-addons-product-title a',
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
					'{{WRAPPER}} h3.ayyash-addons-product-title a:hover' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} h3.ayyash-addons-product-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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

		$this->init_hooks( $settings );

		if ( ! ayyash_addons_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}
		?>
		<div class="ayyash-addons-wrapper ayyash-addons-<?php echo str_replace( 'ayyash-', '', $this->get_name() ); // phpcs:ignore ?>">
			<div class="ayyash-addons-wrapper-inside">

				<div class="ayyash-addons-products <?php echo ( $settings['wc_product_content_align'] ) ? esc_attr( $settings['wc_product_content_align'] ) : ''; ?>">
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
		$this->removed_hooks( $settings );
	}

	/**
	 * Initialize all hooks
	 */
	protected function init_hooks( $settings ) {

		add_action( 'ayyash_addons_woocommerce_products', [ $this, 'render_products_template' ] );
		add_action( 'ayyash_addons_single_product_summary', [ $this, 'ayyash_addons_single_product_header' ], 10, 2 );
		add_action( 'ayyash_addons_single_product_summary', [ $this, 'ayyash_addons_single_product_content' ], 10, 2 );

		//header hooks
		add_action( 'ayyash_addons_single_product_header', [ $this, 'render_product_image' ], 10, 2 );
		add_action( 'ayyash_addons_single_product_header', [ $this, 'render_product_actions' ], 10, 2 );

		//content hooks
		add_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_title' ], 10, 2 );
		add_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_price' ], 15, 2 );

		// Actions hooks
		add_action( 'ayyash_addons_single_product_actions', [ $this, 'render_wishlist' ], 15, 2 );

		// product rating
		if ( isset( $settings['product_rating_position'] ) ) {
			$rating_position = $settings['product_rating_position'];
			$position_hook   = 'ayyash_addons_single_product_' . $rating_position;
			add_action( $position_hook, [ $this, 'render_product_rating' ], 15, 2 );
		}

	}

	/**
	 * Removed all hooks
	 */
	protected function removed_hooks( $settings ) {
		remove_action( 'ayyash_addons_woocommerce_products', [ $this, 'render_products_template' ] );
		remove_action( 'ayyash_addons_single_product_summary', [ $this, 'ayyash_addons_single_product_header' ] );
		remove_action( 'ayyash_addons_single_product_summary', [ $this, 'ayyash_addons_single_product_content' ] );

		//header hooks
		remove_action( 'ayyash_addons_single_product_header', [ $this, 'render_product_image' ] );
		remove_action( 'ayyash_addons_single_product_header', [ $this, 'render_product_actions' ] );

		//content hooks
		remove_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_title' ], 10 );
		remove_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_price' ], 15 );
		remove_action( 'ayyash_addons_single_product_content', [ $this, 'render_product_rating' ], 15 );

		// Actions hooks
		remove_action( 'ayyash_addons_single_product_actions', [ $this, 'render_wishlist' ], 15 );

		// product rating
		if ( isset( $settings['product_rating_position'] ) ) {
			$rating_position = $settings['product_rating_position'];
			$position_hook   = 'ayyash_addons_single_product_' . $rating_position;
			remove_action( $position_hook, [ $this, 'render_product_rating' ], 15 );
		}

	}

	/**
	 * hook ayyash_addons_single_product_header
	 * @return void
	 * @hooked render_product_actions
	 */
	public function render_product_actions( $post_id, $settings ) {
		echo '<div class="product-actions ayyash-addons-rounded">';
		do_action( 'ayyash_addons_single_product_actions', $post_id, $settings );
		echo '</div>';
	}

	/**
	 * hook ayyash_addons_single_product_header
	 * @return void
	 * @hooked render_product_image
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
	 * @return void
	 * @hooked render_product_title
	 * @hooked render_product_price
	 * @hooked render_product_rating
	 */
	public function ayyash_addons_single_product_content( $post_id, $settings ) {
		if ( has_action( 'ayyash_addons_single_product_content' ) ) :
			?>
			<div class="product-content">
				<?php do_action( 'ayyash_addons_single_product_content', $post_id, $settings ); ?>
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

		if ( $products->have_posts() ) {
			while ( $products->have_posts() ) :
				$products->the_post();
				?>
				<div class="ayyash-addons-product-item">
					<?php do_action( 'ayyash_addons_single_product_summary', get_the_ID(), $settings  ); ?>
				</div>
			<?php
			endwhile;
			wp_reset_postdata();
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
		$thumbnail_style = '';

		if ( array_key_exists( 'hv_thumb', $settings ) && 'yes' === $settings['hv_thumb'] ) {
			$thumbnail_style = 'orientation_' . Frontend::get_layout( $product );
		}
		?>
		<div class="product-thumbnail  <?php echo esc_attr( $thumbnail_style ); ?>">
			<?php
			echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key ) );
			?>
		</div>
		<?php
	}

	/**
	 * @return void
	 */
	public function render_product_title( $post_id, $settings ) {
		do_action( 'ayyash_addons_single_product_title_before', $post_id, $settings );
		echo '<h3 class="ayyash-addons-product-title">';
		echo '<a href="' . esc_url( get_permalink() ) . '">';
		if ( empty( $settings['woocommerce_product_title_length'] ) ) {
			echo get_the_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo esc_html( wp_trim_words( get_the_title(), $settings['woocommerce_product_title_length'], '' ) );
		}
		echo '</a></h3>';
		do_action( 'ayyash_addons_single_product_title_after', $post_id, $settings );
	}

	/**
	 * @return void
	 */
	public function render_product_price( $post_id, $settings ) {
		global $product;
		if ( isset( $settings['woocommerce_product_price'] ) && 'yes' === $settings['woocommerce_product_price'] ) {
			?>
			<div class="ayyash-addons-product-price-wrapper">
				<div class="ayyash-addons-product-price">
					<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput  ?>
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
		$rating_label              = ! empty( $settings['rating_label_text'] ) ? ' ' . $settings['rating_label_text'] : '';
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
						<?php echo '<span class="count">(' . esc_html( $review_count . $rating_label ) . ')</span>'; ?>
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
	 * @param $before
	 * @param $after
	 *
	 * @return void
	 */
	public function render_wishlist( $post_id, $settings ) {
		$before   = '';
		$after    = '';
		global $product;
		$enable_wishlist = get_option( 'yith_wcwl_show_on_loop' );
		if ( isset( $settings['enable_wishlist'] ) && 'yes' === $settings['enable_wishlist'] && 'yes' === $enable_wishlist ) {
			if ( ayyash_addons_is_plugin_active( 'yith-woocommerce-wishlist/init.php' ) ) {

				wp_kses_post_e( $before );

				echo YITH_WCWL_Shortcode::add_to_wishlist(
					[ // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'is_single'  => false,
						'product_id' => $product->get_id(),
					]
				);
				wp_kses_post_e( $after );

			}
		}

	}
}

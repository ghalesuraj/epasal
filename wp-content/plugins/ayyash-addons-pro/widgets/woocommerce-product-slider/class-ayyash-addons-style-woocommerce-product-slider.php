<?php

namespace AyyashAddonsPro\Widgets;

use AyyashAddons\Ayyash_Addons_Slider_Controller;
use AyyashAddonsPro\Ayyash_Pro_Widget;
use Elementor\Controls_Manager;
use AyyashAddons\Controls\Ayyash_Addons_Query_Builder;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class AyyashAddons_Style_Woocommerce_Product_Slider extends Ayyash_Pro_Widget {

	use Ayyash_Addons_Slider_Controller;

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
		return 'ayyash-woocommerce-product-slider';
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
		return __( 'Woocommerce Product Slider', 'ayyash-addons-pro' );
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
		return 'eicon-media-carousel';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-pro-woocommerce-product-slider',
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
			'section_heading',
			array(
				'label' => esc_html__( 'Heading', 'ayyash-addons-pro' ),
			)
		);

		$this->add_control(
			'section_title_switcher',
			[
				'label'        => esc_html__( 'Enable Section Title ?', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'section_title',
			[
				'label'       => esc_html__( 'Title', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Most Popular', 'ayyash-addons-pro' ),
				'placeholder' => esc_html__( 'Type your title here', 'ayyash-addons-pro' ),
				'condition'   => [
					'section_title_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'section_title_tag',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default'   => 'h5',
				'condition' => [
					'section_title_switcher' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_query',
			array(
				'label' => esc_html__( 'Product Query', 'ayyash-addons-pro' ),
			)
		);

		if ( ! ayyash_addons_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->plugin_dependency_alert( [
				'plugins' => [
					[
						'path' => 'woocommerce/woocommerce.php',
						'name' => __( 'WooCommerce', 'ayyash-addons-pro' ),
						'slug' => 'woocommerce',
					],
				],
			] );

			$this->end_controls_section();

			return;
		}

		$this->add_control( 'query_builder', [
			'label'   => __( 'Query', 'ayyash-addons-pro' ),
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
			'woocommerce_product_content_align',
			[
				'label'     => esc_html__( 'Content Alignment', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
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
				'selectors' => [
					'{{SELECTOR}} .ayyash-addons-product-content ' => 'text-align: {{VALUE}};',
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
				'condition'    => [
					'woocommerce_product_rating' => 'yes',
				],
			]
		);
		$this->end_controls_section();

		//slider
		$this->render_slider_controller([
			'vertical_slider'      => true,
			'navigation'           => 'arrows',
			'slider_style_section' => [
				'condition' => [
					'navigation' => [ 'arrows' ],
				],
			],
		] );

		$this->__product_body_style();
		$this->__product_item();
		$this->__heading();
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
				'label' => esc_html__( 'Body', 'ayyash-addons-pro' ),
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
				'selector'       => '{{WRAPPER}} .ayyash-addons-woocommerce-product-slider',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'wc_product_body_border_custom_gap',
				'label'          => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-woocommerce-product-slider',
				'fields_options' => [
					'border' =>
						[
							'default' => 'solid',
						],
					'width'  => [
						'default' =>
							[
								'left'     => 1,
								'right'    => 1,
								'top'      => 1,
								'bottom'   => 1,
								'inLinked' => false,
							],
					],
					'color'  => [
						'default' => '#E4E4E4',
					],
				],
			]
		);

		$this->add_control(
			'wc_product_body_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default'    => [
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'wc_product_body_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product-slider',
			]
		);

		$this->add_responsive_control(
			'wc_product_body_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '15',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
				'selector'       => '{{WRAPPER}} .ayyash-addons-woocommerce-product-slider:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'wc_product_body_border_hover',
				'label'          => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-woocommerce-product-slider:hover ,{{WRAPPER}} .ayyash-addons-woocommerce-product-slider:focus-within',
				'fields_options' => [
					'border' =>
						[
							'default' => 'solid',
						],
					'width'  => [
						'default' =>
							[
								'left'     => 1,
								'right'    => 1,
								'top'      => 1,
								'bottom'   => 1,
								'inLinked' => false,
							],
					],
					'color'  => [
						'default' => '#E4E4E4',
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'           => 'wc_product_body_box_shadow_hover',
				'label'          => esc_html__( 'Box Shadow', 'ayyash-addons-pro' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-woocommerce-product-slider:hover',
				'fields_options' =>
					[
						'box_shadow_type' =>
							[
								'default' => '',
							],
					],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __product_item() {
		//Product item
		$this->start_controls_section(
			'wc_product_item_settings',
			[
				'label' => esc_html__( 'Items', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wc_product_item_separator',
			[
				'label'        => esc_html__( 'Separator?', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'prefix_class' => 'product-separator-',
			]
		);

		$this->add_control(
			'wc_product_item_separator_style',
			[
				'label'     => esc_html__( 'Border Style', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''       => esc_html__( 'Default', 'ayyash-addons-pro' ),
					'none'   => esc_html__( 'None', 'ayyash-addons-pro' ),
					'solid'  => esc_html_x( 'Solid', 'Border Control', 'ayyash-addons-pro' ),
					'double' => esc_html_x( 'Double', 'Border Control', 'ayyash-addons-pro' ),
					'dotted' => esc_html_x( 'Dotted', 'Border Control', 'ayyash-addons-pro' ),
					'dashed' => esc_html_x( 'Dashed', 'Border Control', 'ayyash-addons-pro' ),
					'groove' => esc_html_x( 'Groove', 'Border Control', 'ayyash-addons-pro' ),
				],
				'selectors' => [
					'{{SELECTOR}} .ayyash-addons-product-item-inner:not(:last-child) ' => 'border-bottom-style: {{VALUE}};',
				],
				'condition' => [
					'wc_product_item_separator' => 'yes',
				],
			]
		);

		$this->add_control(
			'wc_product_item_separator_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-item-inner:not(:last-child) ' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'wc_product_item_separator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'wc_product_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '15',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-product-item-inner:not(:last-child) ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wc_product_item_margin',
			[
				'label'      => esc_html__( 'Margin', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '15',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-product-item-inner:not(:last-child) ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __product_thumbnail() {
		$this->start_controls_section(
			'wc_product_thumbnail_settings',
			[
				'label' => esc_html__( 'Thumbnail', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wc_product_thumbnail_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default'    => [
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .product-thumbnail .product-thumbnail_link ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'         => 'thumbnail_size',
				'default'      => 'medium',
				'exclude'      => [ 'custom' ],
				// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'prefix_class' => 'product-thumbnail-size-',
			]
		);

		$this->add_responsive_control(
			'thumb_ratio',
			[
				'label'      => esc_html__( 'Image Ratio', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'default'    => [
					'size' => 95,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 500,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .product-thumbnail_link ' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label'      => esc_html__( 'Image Width', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 600,
					],
				],
				'default'    => [
					'size' => 75,
					'unit' => 'px',
				],
				'size_units' => [ '%', 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .product-thumbnail ' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __heading() {
		$this->start_controls_section(
			'wc_product_heading',
			[
				'label'     => esc_html__( 'Heading', 'ayyash-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'section_title_switcher' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-sec-title',
			]
		);

		$this->add_control(
			'wc_product_heading_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-sec-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'wc_product_sec_title_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '20',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-sec-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __product_title() {
		$this->start_controls_section(
			'wc_product_title_settings',
			[
				'label' => esc_html__( 'Title', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'wc_product_title_normal_tabs' );

		// Normal State Tab
		$this->start_controls_tab(
			'wc_product_title_normal_tab',
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
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-heading-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_product_title_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-heading-title a',
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
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-heading-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'wc_product_title_bottom_space',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '10',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-heading-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __product_price_style() {
		//==Product Price==
		$this->start_controls_section(
			'wc_product_price_settings',
			[
				'label'     => esc_html__( 'Price', 'ayyash-addons-pro' ),
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
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-product-price .woocommerce-Price-amount bdi,
						{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-product-price ins .woocommerce-Price-amount bdi
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
			{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-product-price .woocommerce-Price-amount bdi,
			{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-product-price ins .woocommerce-Price-amount bdi
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
				'selector' => '{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-product-price del .woocommerce-Price-amount bdi',
			]
		);

		$this->add_control(
			'wc_product_sale_price_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-product-price del .woocommerce-Price-amount bdi' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'wc_product_price_bottom_space',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-product-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __product_rating_style() {
		$this->start_controls_section(
			'wc_product_rating_settings',
			[
				'label'     => esc_html__( 'Rating', 'ayyash-addons-pro' ),
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
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-product-rating .star-rating span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wc_product_rating_count_color',
			[
				'label'     => esc_html__( 'Count Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-product-rating' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'wc_product_rating_bottom_space',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '10',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-woocommerce-product-slider .ayyash-addons-product-rating' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$settings = $this->get_settings_for_display();

		$swiper_class = Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';

		$this->add_render_attribute( [
			'ayyash_addons_slider'         => $this->get_slider_attributes( $settings ),
			'ayyash_addons_slider_wrapper' => [
				'class' => 'ayyash-addons-swiper-wrapper ' . $swiper_class,
			],

		] );

		if ( ! ayyash_addons_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}

		$args     = Ayyash_Addons_Query_Builder::build_query( $settings['query_builder'] );
		$args['fields'] = 'ids';
		$products = new \WP_Query( $args );
		$products = $products->get_posts();

		$has_vertical_slide = $settings['vertical_slide'] ?? false;
		$per_column_items = (int)$settings['per_column_items'];
		$has_vertical_slide = $has_vertical_slide && 'yes' === $has_vertical_slide;

		if ( $has_vertical_slide ) {
			$products = array_chunk( $products, $per_column_items );
		}
		?>

		<div    class="ayyash-addons-wrapper ayyash-addons-<?php echo str_replace( 'ayyash-', '', $this->get_name() ); // phpcs:ignore ?>">
		<?php if ( $settings['section_title'] ) { ?>
			<div class="ayyash-addons-sec-heading">
				<<?php Utils::print_validated_html_tag( $settings['section_title_tag'] ); ?> class="ayyash-addons-sec-title">
				<?php echo $settings['section_title']; ?>
				</<?php Utils::print_validated_html_tag( $settings['section_title_tag'] ); ?>>
			</div>
		<?php } ?>

			<div <?php $this->print_render_attribute_string( 'ayyash_addons_slider_wrapper' );  $this->print_render_attribute_string( 'ayyash_addons_slider' ); ?>>
				<div class="swiper-wrapper">
					<?php
					foreach ( $products as $i => $item ) {
						if ( $has_vertical_slide ) {
							?>
							<div class="ayyash-addons-product-item swiper-slide slide-column column-<?php echo esc_attr( $i ); ?>">
								<?php
								foreach ( $item as $_item ) {
									$this->render_product( $_item, $settings );
								}
								?>
							</div>
							<?php
						} else {
							?>
							<div class="ayyash-addons-product-item swiper-slide">
								<?php $this->render_product( $item, $settings ); ?>
							</div>
							<?php
						}
					}
					wp_reset_postdata();
					?>
				</div>
			</div>
		<?php $this->slider_nav( $settings ); ?>
		</div>
		<?php
	}

	/**
	 *  Render Products Template
	 * @return void
	 */
	public function render_product( $post, $settings ) {
		$product = wc_get_product( $post );
		?>
		<div class="ayyash-addons-product-item-inner">
			<?php
			$this->render_product_image( $product, $settings );
			?>
			<div class="ayyash-addons-product-content">
				<?php
				$this->render_product_rating( $product, $settings );
				$this->render_product_title( $product, $settings );
				$this->render_product_price( $product, $settings );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * @return void
	 */
	public function render_product_image( $product, $settings ) {
		$setting_key              = 'thumbnail_size';
		$settings[ $setting_key ] = [
			'id' => $product->get_image_id(),
		];
		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		?>
		<div class="product-thumbnail">
			<?php
			if ( isset( $settings['woocommerce_product_image_link'] ) && 'yes' == $settings['woocommerce_product_image_link'] ) {
				echo '<a class="product-thumbnail_link" href="' . esc_url( $product->get_permalink() ) . '">';
				echo wp_kses_post( $thumbnail_html );
				echo '</a>';
			} else {
				echo wp_kses_post( $thumbnail_html );
			}
			?>
		</div>
		<?php
	}

	/**
	 * @return void
	 */
	public function render_product_title( $product, $settings ) {
		echo '<h3 class="ayyash-addons-heading-title">';
		echo '<a href="' . esc_url( $product->get_permalink() ) . '">';
		if ( empty( $settings['woocommerce_product_title_length'] ) ) {
			echo $product->get_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo esc_html( wp_trim_words( $product->get_title(), $settings['woocommerce_product_title_length'], '' ) );
		}
		echo '</a></h3>';
	}

	/**
	 * @return void
	 */
	public function render_product_price( $product, $settings ) {
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
	public function render_product_rating( $product, $settings ) {
		$show_product_rating       = isset( $settings['woocommerce_product_rating'] ) && 'yes' === $settings['woocommerce_product_rating'];
		$show_product_rating_count = isset( $settings['woocommerce_product_rating_count'] ) && 'yes' === $settings['woocommerce_product_rating_count'];
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
							printf('<span class="count">(%1$s %2$s)</span>',
							esc_html($review_count),
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

}

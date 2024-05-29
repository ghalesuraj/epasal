<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 */
class AyyashAddons_Style_Search extends Ayyash_Widget {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 *
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ayyash-search';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 *
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Product Search', 'ayyash-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 *
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'ayyash-addons eicon-search';
	}

	public function get_keywords() {
		return [ 'search', 'input', 'product' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [ 'ayyash-addons-search' ];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [
			'wp-util',
			'ayyash-addons-product-search',
		];
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
	 *
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'ayyash-widgets' );
	}

	// register controls
	protected function register_controls() {

		// search content
		$this->start_controls_section(
			'section_search',
			[
				'label' => esc_html__( 'Search', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'search_by_category',
			[
				'label'     => __( 'Show Category', 'ayyash-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'ayyash-addons' ),
				'label_off' => __( 'No', 'ayyash-addons' ),
				'default'   => 'no',
			]
		);
		$this->add_control(
			'search_label',
			[
				'label'     => __( 'Show Label', 'ayyash-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'ayyash-addons' ),
				'label_off' => __( 'No', 'ayyash-addons' ),
				'default'   => 'no',
			]
		);
		$this->add_control(
			'search_label_text',
			[
				'label'     => __( 'Label', 'ayyash-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Search',
				'condition' => [
					'search_label' => 'yes',
				],
			]
		);
		$this->add_control(
			'search_placeholder',
			[
				'label'   => __( 'Placeholder', 'ayyash-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Search for products...',
			]
		);
		$this->add_control(
			'search_button',
			[
				'label'     => __( 'Show Button?', 'ayyash-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'ayyash-addons' ),
				'label_off' => __( 'No', 'ayyash-addons' ),
				'default'   => 'no',
			]
		);
		$this->add_control(
			'search_button_icons',
			[
				'label'     => __( 'Button Icon', 'ayyash-addons' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-search',
					'library' => 'solid',
				],
				'selector'  => '.ayyash-addons-search button',
				'condition' => [
					'search_button' => 'yes',
				],
			]
		);
		$this->end_controls_section();

		// style controls
		$this->__search_field_style();
		$this->__search_label_style();
		$this->__search_category_style();
		$this->__search_button_style();
		$this->__products_wrap_style();
		$this->__product_item_style();
		$this->__thumbnail_style();
		$this->__title_style();
		$this->__price_style();
		$this->__rating_style();
	}

	//search field style
	protected function __search_field_style() {
		$this->start_controls_section(
			'section_style_search',
			[
				'label' => esc_html__( 'Search Field', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'search_field_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-search-input',
			]
		);
		$this->add_control(
			'search_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-search-input' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'placeholder_color',
			[
				'label'     => esc_html__( 'Placeholder Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-search-input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'search_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'search_border',
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
							'isLinked' => false,
						],
					],
					'color'  => [
						'default' => '#E8E8E8',
					],
				],
				'selector'       => '{{WRAPPER}}  .search-wrapper',
			]
		);
		$this->add_control(
			'search_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}}  .search-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'search_padding',
			[
				'label'      => esc_html__( 'Wrapper Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .search-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'search_form_height',
			[
				'label'      => esc_html__( 'Height', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => '50',
					'unit' => 'px',
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
					'{{WRAPPER}} .search-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'search_input_padding',
			[
				'label'      => esc_html__( 'Input Field Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => '10',
					'right'    => '20',
					'bottom'   => '10',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-search-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	//search label style
	protected function __search_category_style() {
		$this->start_controls_section(
			'section_style_search_category',
			[
				'label'     => esc_html__( 'Search Category', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'search_by_category' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'search_category_typography',
				'selector' => '{{WRAPPER}} .product-category',
			]
		);
		$this->add_control(
			'search_category_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-category' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'search_category_width',
			[
				'label'      => esc_html__( 'Width', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', '%' ],
				'range'      => [
					'px'  => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
					],
					'%'   => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'size' => '200',
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .product-category' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'show_category_separator',
			[
				'label'        => esc_html__( 'Show Separator', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'category-separator-',
			]
		);
		$this->add_control(
			'separation_color',
			[
				'label'     => esc_html__( 'Separator Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} select.product-category' => 'border-color: {{VALUE}}',
				],
				'condition' => [ 'show_category_separator' => 'yes' ],
			]
		);
		$this->add_control(
			'category_align',
			[
				'label'        => esc_html__( 'Alignment', 'ayyash-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'  => [
						'title' => esc_html__( 'Left', 'ayyash-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'ayyash-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'      => 'left',
				'toggle'       => true,
				'prefix_class' => 'category-alignment-',
			]
		);
		$this->end_controls_section();
	}

	//search label style
	protected function __search_label_style() {
		$this->start_controls_section(
			'section_style_search_label',
			[
				'label'     => esc_html__( 'Search Label', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'search_label' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'search_label_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-search-label',
			]
		);
		$this->add_control(
			'search_label_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-search-label' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'search_label_spacing',
			[
				'label'      => esc_html__( 'Width', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
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
					'size' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-search-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	//search button style
	protected function __search_button_style() {
		$this->start_controls_section(
			'section_style_search_button',
			[
				'label'     => esc_html__( 'Search Button', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'search_button' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'search_btn_width',
			[
				'label'      => esc_html__( 'Width', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', '%' ],
				'range'      => [
					'px'  => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
					],
					'%'   => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'size' => '100',
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-search button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'search_btn_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', '%' ],
				'range'      => [
					'px'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
					],
					'%'   => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'size' => '16',
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-search button i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'search_btn_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}}  .ayyash-addons-search button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'section_style_button_tabs' );
		$this->start_controls_tab(
			'section_style_button_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'search_btn_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#161616',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-search button' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'search_btn_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-search button' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'section_style_button_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'search_button_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-search button:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'search_button_background_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-search button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	//products wrapper style controls
	protected function __products_wrap_style() {
		$this->start_controls_section(
			'section_style_search_result',
			[
				'label' => esc_html__( 'Product Body', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'search_result_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => '10',
					'right'    => '10',
					'bottom'   => '10',
					'left'     => '10',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}}  .ayyash-addons-search .search_result_inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(), [
				'name'     => 'search_result_box_shadow',
				'selector' => '{{WRAPPER}} .ayyash-addons-search .search_result_inner',
			]
		);
		$this->add_responsive_control(
			'search_result_top_spacing',
			[
				'label'      => esc_html__( 'Top Spacing', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
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
					'size' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-search .search_result_inner' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	//product item style controls
	protected function __product_item_style() {
		$this->start_controls_section(
			'section_style_search_items',
			[
				'label' => esc_html__( 'Product Items', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'section_style_search_items_tabs' );
		$this->start_controls_tab(
			'section_style_search_items_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'search_items_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-search .search_result_inner li' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label'    => __( 'Border', 'ayyash-addons' ),
				'name'     => 'search_items_border',
				'selector' => '{{WRAPPER}} .ayyash-addons-search .search_result_inner li',
			]
		);
		$this->add_responsive_control(
			'search_items_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => '10',
					'right'    => '10',
					'bottom'   => '10',
					'left'     => '10',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-search .search_result_inner li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'section_style_search_items_normal_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'search_items_background_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-search .search_result_inner li:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	//thumbnail style controls
	protected function __thumbnail_style() {
		$this->start_controls_section(
			'search_result_thumbnail',
			[
				'label' => esc_html__( 'Product Thumbnail', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'search_thumbnail_width',
			[
				'label'          => esc_html__( 'Width', 'ayyash-addons' ),
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
					'{{WRAPPER}} .ayyash-addons-search .thumbnail img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'search_thumbnail_height',
			[
				'label'          => esc_html__( 'Height', 'ayyash-addons' ),
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
					'{{WRAPPER}} .ayyash-addons-search .thumbnail img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'search_thumbnail_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
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
					'size' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-search .thumbnail' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	//title style controls
	protected function __title_style() {
		$this->start_controls_section(
			'section_style_search_result_title',
			[
				'label' => esc_html__( 'Product Title', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'section_style_search_result_title_tabs' );
		$this->start_controls_tab(
			'section_style_search_result_title_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'search_result_title_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-search .product-title',
			]
		);
		$this->add_control(
			'search_result_title_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-search .product-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'search_result_title_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons' ),
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
					'{{WRAPPER}} .ayyash-addons-search .product-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'section_style_search_result_title_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'search_result_title_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-search .product-title:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	//price style controls
	protected function __price_style() {
		$this->start_controls_section(
			'product_price',
			[
				'label' => esc_html__( 'Product Price', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_price_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-search .woocommerce-Price-amount bdi',
			]
		);
		$this->add_control(
			'product_price_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-search .woocommerce-Price-amount bdi' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'product_price_color_hover',
			[
				'label'     => esc_html__( 'Hover Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-search .woocommerce-Price-amount bdi:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'price_style',
			[
				'label'        => esc_html__( 'Price Inline', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Inline', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'block', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'prefix_class' => 'price-inline-',
			]
		);
		$this->end_controls_section();
	}

	//rating style controls
	protected function __rating_style() {
		$this->start_controls_section(
			'product_rating',
			[
				'label' => esc_html__( 'Product Rating', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'product_rating_color',
			[
				'label'     => esc_html__( 'Rating Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-search .woocommerce .star-rating span' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'rating_style',
			[
				'label'        => esc_html__( 'Rating Inline', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Inline', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'block', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'prefix_class' => 'rating-inline-',
			]
		);
		$this->add_control(
			'product_rating_spacing',
			[
				'label'      => esc_html__( 'Top Spacing', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
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
					'size' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .search_result_inner .ayyash-rating' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'rating_align',
			[
				'label'        => esc_html__( 'Alignment', 'ayyash-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'  => [
						'title' => esc_html__( 'Left', 'ayyash-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'ayyash-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'      => 'right',
				'toggle'       => true,
				'prefix_class' => 'rating-alignment-',
				'condition'    => [ 'rating_style' => 'yes' ],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * @render Product Search
	 */
	protected function render() {
		$settings              = $this->get_settings_for_display();
		$uid                   = wp_unique_id( 'ayyash-addons-search-' );
		?>
		<form class="ayyash-addons-search" data-uid="<?php echo esc_attr( $uid ); ?>"  action="<?php echo esc_url( home_url() ) ?>" method="get" autocomplete="off" role="search">
			<fieldset>
				<label for="<?php echo esc_attr( $uid ); ?>-keyword" class="ayyash-addons-search-label <?php echo 'yes' !== $settings['search_label'] ? esc_attr( 'screen-reader-text' ) : ''; ?>">
					<?php echo $settings['search_label_text'] ? esc_html( $settings['search_label_text'] ) : esc_html__( 'Search', 'ayyash-addons' ); ?>
				</label>
				<div class="search-wrapper">
					<?php $this->render_product_category( $settings ); ?>

					<input name="s" id="<?php echo esc_attr( $uid ); ?>-keyword"  class="ayyash-addons-search-input" type="text" placeholder="<?php echo esc_attr( $settings['search_placeholder'] ); ?>" autocomplete="off" aria-label="<?php echo esc_attr__('search', 'ayyash-addons'); ?>">
					<input type="hidden" name="post_type" value="product">
					<?php wp_nonce_field( 'ayyash-addons-frontend'); ?>
					<?php if ( 'yes' === $settings['search_button'] ) { ?>
					<button type="submit"><?php Icons_Manager::render_icon( $settings['search_button_icons'], [ 'aria-hidden' => 'true' ] ); ?>
						<span class="sr-only"><?php _e( 'Search', 'ayyash-addons' ); ?></span>
					</button>
					<?php } ?>
				</div>
				<div class="search_result woocommerce" style="display:none">
					<ul class="search_result_inner products"></ul>
				</div>
				<script type="text/html" id="tmpl-<?php echo esc_attr( $uid ); ?>-search-result">
					<li class="product product-{{ data.id }}">
						<a href="{{ data.link }}">
							<div class="thumbnail">{{{ data.thumbnail }}}</div>
							<div class="summery">
								<div class="content">
									<h4 class="product-title">{{ data.title }}</h4>
									<div class="price">{{{ data.price }}}</div>
								</div>
								<# if ( data.rating ) { #>
								<div class="ayyash-rating">{{{ data.rating }}}</div>
								<# } #>
							</div>
						</a>
					</li>
				</script>
			</fieldset>
		</form>
		<?php
	}

	protected function render_product_category( $settings ) {
		if ( 'yes' !== $settings['search_by_category'] ) {
			return;
		}
		$args           = array(
			'taxonomy'     => 'product_cat',
			'orderby'      => 'name',
			'hierarchical' => 1,
			'hide_empty'   => 0,
		);
		$all_categories = get_categories( $args );

		echo '<select class="product-category" name="product_category" aria-label="' . esc_attr__( 'Product category', 'ayyash-addons' ) . '">';
		echo '<option value="0" selected="selected">' . esc_html__( 'All categories', 'ayyash-addons' ) . '</option>';

		foreach ( $all_categories as $category ) {
			echo '<option value="' . esc_attr( $category->slug ) . '" >' . esc_html( $category->name ) . '</option>';
		}

		echo '</select>';
	}
}

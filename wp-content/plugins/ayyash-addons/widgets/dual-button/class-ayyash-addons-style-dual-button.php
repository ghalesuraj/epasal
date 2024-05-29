<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AyyashAddons_Style_Dual_Button extends Ayyash_Widget {

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
		return 'ayyash-dual-button';
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
		return __( 'Dual Button', 'ayyash-addons' );
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
		return 'ayyash-addons eicon-button';
	}

	public function get_keywords() {
		return [ 'button', 'multi-button', 'multi', 'dual' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-dual-button',
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
	 *
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'ayyash-widgets' );
	}

	protected function register_controls() {
		$this->button_section();
		$this->__style_controller();
	}

	protected function button_section() {
		$this->start_controls_section(
			'ayyash_addons_dual_button_section',
			[
				'label' => __( 'Dual Button', 'ayyash-addons' ),
			]
		);

		$this->start_controls_tabs( 'ayyash_addons_dual_button_primary_tabs' );

		$this->start_controls_tab(
			'ayyash_addons_dual_button_primary',
			[
				'label' => __( 'Primary', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_html_tag',
			[
				'label'     => __( 'Button HTML Tag', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'button' => 'Button',
					'a'      => 'a',
				],
				'default'   => 'a',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_text',
			[
				'label'       => __( 'Button Text', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Click here', 'ayyash-addons' ),
				'placeholder' => __( 'Click here', 'ayyash-addons' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_link',
			[
				'label'         => __( 'Link', 'ayyash-addons' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'ayyash-addons' ),
				'show_external' => true,
				'dynamic'       => [
					'active' => true,
				],
				'condition'     => [
					'ayyash_addons_dual_button_html_tag!' => 'button',
				],
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_icons_switch',
			[
				'label'     => __( 'Add icon? ', 'ayyash-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Yes', 'ayyash-addons' ),
				'label_off' => __( 'No', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_icons',
			[
				'label'       => __( 'Icon', 'ayyash-addons' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value' => '',
				],
				'label_block' => true,
				'condition'   => [
					'ayyash_addons_dual_button_icons_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_icon_align',
			[
				'label'     => __( 'Icon Position', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'left'  => __( 'Before', 'ayyash-addons' ),
					'right' => __( 'After', 'ayyash-addons' ),
				],
				'default'   => 'right',
				'condition' => [
					'ayyash_addons_dual_button_icons_switch' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ayyash_addons_dual_button_connector',
			[
				'label' => __( 'Connector', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_connector_switch',
			[
				'label'          => __( 'Hide Connector?', 'ayyash-addons' ),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => __( 'Yes', 'ayyash-addons' ),
				'label_off'      => __( 'No', 'ayyash-addons' ),
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_connector_radius',
			[
				'label'   => __( 'Button Radius', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'none'       => 'None',
					'round'      => 'Round',
					'rounded'    => 'Rounded',
					'round-full' => 'Full',
				],
				'default' => 'round-full',
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_connector_type',
			[
				'label'     => __( 'Connector Type', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'text' => __( 'Text', 'ayyash-addons' ),
					'icon' => __( 'Icon', 'ayyash-addons' ),
				],
				'default'   => 'text',
				'condition' => [
					'ayyash_addons_dual_button_connector_switch!' => 'yes',
				],
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_connector_text',
			[
				'label'       => __( 'Button Text', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Or', 'ayyash-addons' ),
				'placeholder' => __( 'Text Here', 'ayyash-addons' ),
				'dynamic'     => [
					'active' => true,
				],
				'condition'   => [
					'ayyash_addons_dual_button_connector_type'    => 'text',
					'ayyash_addons_dual_button_connector_switch!' => 'yes',
				],
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_connector_icon',
			[
				'label'       => __( 'Icon', 'ayyash-addons' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value' => '',
				],
				'label_block' => true,
				'condition'   => [
					'ayyash_addons_dual_button_connector_type'    => 'icon',
					'ayyash_addons_dual_button_connector_switch!' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ayyash_addons_dual_button_secondary',
			[
				'label' => __( 'Secondary', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_html_tag_secondary',
			[
				'label'     => __( 'Button HTML Tag', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'button' => 'Button',
					'a'      => 'a',
				],
				'default'   => 'a',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_text_secondary',
			[
				'label'       => __( 'Button Text', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Click here', 'ayyash-addons' ),
				'placeholder' => __( 'Click here', 'ayyash-addons' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_link_secondary',
			[
				'label'         => __( 'Link', 'ayyash-addons' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'ayyash-addons' ),
				'show_external' => true,
				'dynamic'       => [
					'active' => true,
				],
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'condition'     => [
					'ayyash_addons_dual_button_html_tag!' => 'button',
				],
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_icons_switch_secondary',
			[
				'label'     => __( 'Add icon? ', 'ayyash-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Yes', 'ayyash-addons' ),
				'label_off' => __( 'No', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_icons_secondary',
			[
				'label'       => __( 'Icon', 'ayyash-addons' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value' => '',
				],
				'label_block' => true,
				'condition'   => [
					'ayyash_addons_dual_button_icons_switch_secondary' => 'yes',
				],
			]
		);

		$this->add_control(
			'ayyash_addons_dual_button_icon_align_secondary',
			[
				'label'     => __( 'Icon Position', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'left'  => __( 'Before', 'ayyash-addons' ),
					'right' => __( 'After', 'ayyash-addons' ),
				],
				'default'   => 'right',
				'condition' => [
					'ayyash_addons_dual_button_icons_switch_secondary' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'ayyash_addons_dual_btn_space',
			[
				'label'      => esc_html__( 'Button Space Between', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
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
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary'   => 'margin-right: calc({{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2);',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'ayyash_addons_dual_button_align',
			[
				'label'     => __( 'Alignment', 'ayyash-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'ayyash-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'ayyash-addons' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'ayyash-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-dual-button' => 'text-align: {{VALUE}};',
				],
				'default'   => 'left',
			]
		);

		$this->add_responsive_control(
			'ayyash_addons_dual_button_layout',
			[
				'label'        => __( 'Button Layout', 'ayyash-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'row'    => [
						'title' => __( 'Row', 'ayyash-addons' ),
						'icon'  => 'eicon-h-align-left',
					],
					'column' => [
						'title' => __( 'Column', 'ayyash-addons' ),
						'icon'  => 'eicon-v-align-top',
					],
				],
				'default'      => 'row',
				'prefix_class' => 'ayyash-addons-dual-button%s-layout-',
				'selectors'    => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-dual-button-container' => 'flex-direction: {{ayyash_addons_dual_button_layout.VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __style_controller() {

		$this->start_controls_section(
			'ayyash_addons_dual_btn_common',
			[
				'label' => __( 'Settings', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), [
				'name'     => 'ayyash_addons_dual_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button',
			]
		);

		$this->add_responsive_control(
			'ayyash_addons_dual_button_padding',
			[
				'label'      => __( 'Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();
		$this->__style_primary_btn();

		$this->__style_secondary_btn();

		$this->start_controls_section(
			'ayyash_addons_dual_btn_connector',
			[
				'label'     => __( 'Connector', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'ayyash_addons_dual_button_connector_switch!' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), [
				'name'     => 'ayyash_addons_dual_btn_connector_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-btn-connector',
			]
		);

		$this->add_responsive_control(
			'ayyash_addons_dual_btn_connector_width',
			[
				'label'      => esc_html__( 'Width', 'ayyash-addons' ),
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
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-btn-connector' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ayyash_addons_dual_btn_connector_height',
			[
				'label'      => esc_html__( 'Height', 'ayyash-addons' ),
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
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-btn-connector' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'ayyash_addons_dual_btn_connector_background',
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color', 'ayyash-addons' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-btn-connector',
				'default'        => '',
			]
		);

		$this->add_control(
			'ayyash_addons_dual_btn_connector_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-btn-connector' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label'    => __( 'Border', 'ayyash-addons' ),
				'name'     => 'ayyash_addons_dual_btn_connector_border',
				'selector' => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-btn-connector',
			]
		);

		$this->add_responsive_control(
			'ayyash_addons_dual_btn_connector_border_radius',
			[
				'label'      => __( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-btn-connector' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_box_Shadow::get_type(), [
				'name'     => 'ayyash_addons_dual_btn_connector_shadow',
				'selector' => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-btn-connector',
			]
		);

		$this->add_responsive_control(
			'ayyash_addons_dual_btn_connector_padding',
			[
				'label'      => __( 'Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-btn-connector' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


	}

	protected function __style_primary_btn() {

		$this->start_controls_section(
			'ayyash_addons_dual',
			[
				'label' => __( 'Primary Button', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'ayyash_addons_dual_tabs' );

		$this->start_controls_tab(
			'ayyash_addons_dual_tabs_normal',
			[
				'label' => __( 'Normal', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'ayyash_addons_dual_background',
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color', 'ayyash-addons' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary',
				'default'        => '',
			]
		);

		$this->add_control(
			'ayyash_addons_dual_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label'    => __( 'Border', 'ayyash-addons' ),
				'name'     => 'ayyash_addons_dual_border',
				'selector' => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary',
			]
		);

		$this->add_responsive_control(
			'ayyash_addons_dual_border_radius',
			[
				'label'      => __( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_box_Shadow::get_type(), [
				'name'     => 'ayyash_addons_dual_shadow',
				'selector' => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary',
			]
		);

		$this->add_responsive_control(
			'ayyash_addons_dual_padding',
			[
				'label'      => __( 'Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ayyash_addons_dual_hover',
			[
				'label' => __( 'Hover', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'ayyash_addons_dual_background_hover',
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color', 'ayyash-addons' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary:hover',
				'default'        => '',
			]
		);

		$this->add_control(
			'ayyash_addons_dual_hover_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label'    => __( 'Border', 'ayyash-addons' ),
				'name'     => 'ayyash_addons_dual_border_hover',
				'selector' => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary:hover',
			]
		);

		$this->add_group_control(
			Group_Control_box_Shadow::get_type(), [
				'name'     => 'ayyash_addons_dual_shadow_hover',
				'selector' => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'icon_area_heading',
			[
				'label'     => esc_html__( 'Icon Control', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), [
				'name'     => 'ayyash_addons_dual_icon_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary i',
			]
		);

		$this->add_responsive_control(
			'ayyash_addons_dual_icon_gap',
			[
				'label'      => esc_html__( 'Icon Gap', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'ayyash_addons_dual_icon_tabs' );

		$this->start_controls_tab(
			'ayyash_addons_dual_icon_tabs_normal',
			[
				'label' => __( 'Normal', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'ayyash_addons_dual_icon_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ayyash_addons_ayyash_addons_dual_icon_hover',
			[
				'label' => __( 'Hover', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'ayyash_addons_dual_icon_hover_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-primary:hover i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __style_secondary_btn() {
		$this->start_controls_section(
			'ayyash_addons_secondary_dual_btn',
			[
				'label' => __( 'Secondary Button', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'ayyash_addons_secondary_dual_tabs' );

		$this->start_controls_tab(
			'ayyash_addons_secondary_dual_tabs_normal',
			[
				'label' => __( 'Normal', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'ayyash_addons_secondary_dual_background',
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color', 'ayyash-addons' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary',
				'default'        => '',
			]
		);

		$this->add_control(
			'ayyash_addons_secondary_dual_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label'    => __( 'Border', 'ayyash-addons' ),
				'name'     => 'ayyash_addons_secondary_dual_border',
				'selector' => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary',
			]
		);

		$this->add_responsive_control(
			'ayyash_addons_secondary_dual_border_radius',
			[
				'label'      => __( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_box_Shadow::get_type(), [
				'name'     => 'ayyash_addons_secondary_dual_shadow',
				'selector' => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary',
			]
		);

		$this->add_responsive_control(
			'ayyash_addons_secondary_dual_padding',
			[
				'label'      => __( 'Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ayyash_addons_secondary_dual_hover',
			[
				'label' => __( 'Hover', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'ayyash_addons_secondary_dual_background_hover',
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color', 'ayyash-addons' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary:hover',
				'default'        => '',
			]
		);

		$this->add_control(
			'ayyash_addons_secondary_dual_hover_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label'    => __( 'Border', 'ayyash-addons' ),
				'name'     => 'ayyash_addons_secondary_dual_border_hover',
				'selector' => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary:hover',
			]
		);

		$this->add_group_control(
			Group_Control_box_Shadow::get_type(), [
				'name'     => 'ayyash_addons_secondary_dual_shadow_hover',
				'selector' => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'icon_area_heading_two',
			[
				'label'     => esc_html__( 'Icon Control', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), [
				'name'     => 'ayyash_addons_secondary_dual_icon_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary i',
			]
		);

		$this->add_responsive_control(
			'ayyash_addons_secondary_dual_icon_gap',
			[
				'label'      => esc_html__( 'Icon Gap', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'ayyash_addons_secondary_dual_icon_tabs' );

		$this->start_controls_tab(
			'ayyash_addons_secondary_dual_icon_tabs_normal',
			[
				'label' => __( 'Normal', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'ayyash_addons_secondary_dual_icon_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ayyash_addons_secondary_dual_icon_hover',
			[
				'label' => __( 'Hover', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'ayyash_addons_secondary_dual_icon_hover_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-dual-button .ayyash-addons-button.ayyash-addons-btn-secondary:hover i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		?>
		<div class="ayyash-addons-wrapper ayyash-addons-widget">
			<div
				class="ayyash-addons-dual-button ayyash-addons-btn-<?php echo esc_attr( $settings['ayyash_addons_dual_button_align'] ); ?>">
				<div class="ayyash-addons-dual-button">
					<div
						class="ayyash-addons-dual-button-container ayyash-addons-btn-layout-<?php echo esc_attr( $settings['ayyash_addons_dual_button_layout'] ); ?>">
						<?php $this->button_primary( $settings, 'ayyash-addons-button ' ); ?>
						<?php $this->button_secondary( $settings, 'ayyash-addons-button ' ); ?>
					</div>
				</div>
			</div>
		</div><!-- end .ayyash-addons-wrapper -->
		<?php
	}

	protected function button_primary( $settings, $class_name ) {
		$this->add_render_attribute( [
			'ayyash_addons_dual_btn_link_option' => [
				'target' => isset( $settings['ayyash_addons_button_link']['is_external'] ) ? '_blank' : '',
				'rel'    => isset( $settings['ayyash_addons_button_link']['nofollow'] ) ? 'nofollow' : '',
			],
		] );

		$this->add_render_attribute( 'ayyash_addons_dual_btn_attr', 'class', 'ayyash-addons-button ayyash-addons-btn-primary' );
		$this->add_render_attribute( 'ayyash_addons_dual_btn_attr', 'class', $class_name );

		$href = ( isset( $settings['ayyash_addons_dual_button_link']['url'] ) ) ? $settings['ayyash_addons_dual_button_link']['url'] : '';
		?>
		<div class="ayyash-addons-dual-button">
			<?php if ( 'button' === $settings['ayyash_addons_dual_button_html_tag'] ) { ?>
				<button <?php $this->print_render_attribute_string( 'ayyash_addons_dual_btn_attr' ); ?> type="button">
					<?php
					if ( 'left' === $settings['ayyash_addons_dual_button_icon_align'] ) {
						if ( 'yes' === $settings['ayyash_addons_dual_button_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['ayyash_addons_dual_button_icons'], [ 'aria-hidden' => 'true' ] );
						}
						echo esc_html( $settings['ayyash_addons_dual_button_text'] );
					} else {
						echo esc_html( $settings['ayyash_addons_dual_button_text'] );
						if ( 'yes' === $settings['ayyash_addons_dual_button_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['ayyash_addons_dual_button_icons'], [ 'aria-hidden' => 'true' ] );
						}
					}
					?>
				</button>
			<?php } else { ?>
				<a href="<?php echo esc_url( $href ); ?>" <?php $this->print_render_attribute_string( 'ayyash_addons_dual_btn_attr' ); ?> <?php $this->print_render_attribute_string( 'ayyash_addons_dual_btn_link_option' ); ?> role="button">
					<?php
					if ( 'left' === $settings['ayyash_addons_dual_button_icon_align'] ) {
						if ( 'yes' === $settings['ayyash_addons_dual_button_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['ayyash_addons_dual_button_icons'], [ 'aria-hidden' => 'true' ] );
						}
						echo esc_html( $settings['ayyash_addons_dual_button_text'] );
					} else {
						echo esc_html( $settings['ayyash_addons_dual_button_text'] );
						if ( 'yes' === $settings['ayyash_addons_dual_button_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['ayyash_addons_dual_button_icons'], [ 'aria-hidden' => 'true' ] );
						}
					}
					?>
				</a>
			<?php }
			$this->button_connector( $settings ); ?>
		</div>
		<?php
	}

	protected function button_secondary( $settings, $class_name ) {
		$this->add_render_attribute( [
			'ayyash_addons_secondary_dual_btn_link_option' => [
				'target' => isset( [ 'ayyash_addons_dual_button_link_secondary' ]['is_external'] ) ? '_blank' : '',
				'rel'    => isset( [ 'ayyash_addons_dual_button_link_secondary' ]['nofollow'] ) ? 'nofollow' : '',
			],
		] );

		$this->add_render_attribute( 'ayyash_addons_secondary_dual_btn_attr', 'class', 'ayyash-addons-button ayyash-addons-btn-secondary' );
		$this->add_render_attribute( 'ayyash_addons_secondary_dual_btn_attr', 'class', $class_name );

		$href = ( isset( $settings['ayyash_addons_dual_button_link_secondary']['url'] ) ) ? $settings['ayyash_addons_dual_button_link_secondary']['url'] : '';

		?>
		<div class="ayyash-addons-dual-button">
			<?php if ( 'button' === $settings['ayyash_addons_dual_button_html_tag_secondary'] ) { ?>
				<button <?php $this->print_render_attribute_string( 'ayyash_addons_secondary_dual_btn_attr' ); ?>
					type="button">
					<?php
					if ( 'left' === $settings['ayyash_addons_dual_button_icon_align_secondary'] ) {
						if ( 'yes' === $settings['ayyash_addons_dual_button_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['ayyash_addons_dual_button_icons_secondary'], [ 'aria-hidden' => 'true' ] );
						}
						echo esc_html( $settings['ayyash_addons_dual_button_text_secondary'] );
					} else {
						echo esc_html( $settings['ayyash_addons_dual_button_text_secondary'] );
						if ( 'yes' === $settings['ayyash_addons_dual_button_icons_switch_secondary'] ) {
							Icons_Manager::render_icon( $settings['ayyash_addons_dual_button_icons_secondary'], [ 'aria-hidden' => 'true' ] );
						}
					}
					?>
				</button>
			<?php } else { ?>
				<a href="<?php echo esc_url( $href ); ?>" <?php $this->print_render_attribute_string( 'ayyash_addons_secondary_dual_btn_attr' ); ?><?php $this->print_render_attribute_string( 'ayyash_addons_dual_btn_link_option_secondary' ); ?> role="button">
					<?php
					if ( 'left' === $settings['ayyash_addons_dual_button_icon_align_secondary'] ) {
						if ( 'yes' === $settings['ayyash_addons_dual_button_icons_switch_secondary'] ) {
							Icons_Manager::render_icon( $settings['ayyash_addons_dual_button_icons_secondary'], [ 'aria-hidden' => 'true' ] );
						}
						echo esc_html( $settings['ayyash_addons_dual_button_text_secondary'] );
					} else {
						echo esc_html( $settings['ayyash_addons_dual_button_text_secondary'] );
						if ( 'yes' === $settings['ayyash_addons_dual_button_icons_switch_secondary'] ) {
							Icons_Manager::render_icon( $settings['ayyash_addons_dual_button_icons_secondary'], [ 'aria-hidden' => 'true' ] );
						}
					}
					?>
				</a>
			<?php } ?>
		</div>
		<?php
	}

	protected function button_connector( $settings ) {
		if ( 'yes' !== $settings['ayyash_addons_dual_button_connector_switch'] ) {
			if ( 'icon' === $settings['ayyash_addons_dual_button_connector_type'] ) { ?>
				<span
					class="ayyash-addons-btn-connector ayyash-addons-connector-<?php echo esc_attr( $settings['ayyash_addons_dual_button_connector_type'] ); ?> ayyash-addons-btn-<?php echo esc_attr( $settings['ayyash_addons_dual_button_connector_radius'] ); ?>">
					<?php Icons_Manager::render_icon( $settings['ayyash_addons_dual_button_connector_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
			<?php } else { ?>
				<span
					class="ayyash-addons-btn-connector ayyash-addons-connector-<?php echo esc_attr( $settings['ayyash_addons_dual_button_connector_type'] ); ?> ayyash-addons-btn-<?php echo esc_attr( $settings['ayyash_addons_dual_button_connector_radius'] ); ?>">
					<?php echo esc_html( $settings['ayyash_addons_dual_button_connector_text'] ); ?>
				</span>
			<?php }
		}
	}

}

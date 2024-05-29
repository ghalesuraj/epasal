<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use Exception;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 */
class AyyashAddons_Style_Heading extends Ayyash_Widget {

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
		return 'ayyash-heading';
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
		return __( 'Heading', 'ayyash-addons' );
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
		return 'ayyash-addons eicon-heading';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-heading',
			'psgTimer',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'ayyash-addons-heading',
			'jquery-psgTimer',
		);
	}

	public function get_keywords() {
		return [ 'heading', 'title' ];
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
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'ayyash-widgets' );
	}

	protected function register_controls() {

		// heading controls
		$this->start_controls_section(
			'ayyash_addons_title_section',
			[
				'label' => __( 'Section Heading', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'widget_title',
			[
				'label'       => esc_html__( 'Section Title', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Featured Products', 'ayyash-addons' ),
				'placeholder' => esc_html__( 'Type your title', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'view_all_btn',
			[
				'label'   => esc_html__( 'Button Label', 'ayyash-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'View All', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'btn_link_switcher',
			[
				'label'        => esc_html__( 'Button Link Or Unlink', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Link', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'Unlink', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'view_all_btn_icon',
			[
				'label'        => esc_html__( 'Button Icon', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'btn_link_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'veiw_all_btn_link',
			[
				'label'       => esc_html__( 'Button Link', 'ayyash-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'ayyash-addons' ),
				'options'     => [ 'url', 'is_external', 'nofollow' ],
				'default'     => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'label_block' => true,
				'condition'   => [
					'btn_link_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_title_separator',
			[
				'label'        => esc_html__( 'Separator', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_heading_countdown',
			[
				'label'        => esc_html__( 'Enable Countdown ?', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'No', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_responsive_control(
			'heading_margin',
			[
				'label'      => esc_html__( 'Margin', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}  .ayyash-addons-heading-wrapper .ayyash-addons-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// countdown controls
		$this->start_controls_section(
			'section_countdown',
			[
				'label'     => esc_html__( 'Countdown', 'ayyash-addons' ),
				'condition' => [
					'show_heading_countdown' => 'yes',
				],
			]

		);

		$this->add_control(
			'countdown_align',
			[
				'label'     => esc_html__( 'Countdown Alignment', 'ayyash-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'ayyash-addons' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'ayyash-addons' ),
						'icon'  => 'eicon-h-align-center',
					],
					'flex-end'   => [
						'title' => esc_html__( 'Right', 'ayyash-addons' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'   => 'flex-end',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-countdown.psgTimer ' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'due_date',
			[
				'label'       => esc_html__( 'Due Date', 'ayyash-addons' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => gmdate( 'j.n.Y G:i ', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				/* translators: %s: Time zone. */
				'description' => sprintf( esc_html__( 'Date set according to your timezone: %s.', 'ayyash-addons' ), Utils::get_timezone_string() ),
			]
		);

		$this->add_control(
			'custom_separator',
			[
				'label'     => esc_html__( 'Separator', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					':' => esc_html__( ': (Colon)', 'ayyash-addons' ),
					'/' => esc_html__( '/ (Slash)', 'ayyash-addons' ),
					'-' => esc_html__( '- (Dash)', 'ayyash-addons' ),
					'.' => esc_html__( '. (Dot)', 'ayyash-addons' ),
				],
				'default'   => ':',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-countdown .psgTimer_numbers > div:after'             => 'content:"{{VALUE}}" !important',
					'{{WRAPPER}} .ayyash-addons-countdown .psgTimer_numbers > div:last-child:after ' => 'content:none !important',
				],
			]
		);

		$this->add_responsive_control(
			'countdown_margin',
			[
				'label'      => esc_html__( 'Margin', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}  .ayyash-addons-countdown.psgTimer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label'     => esc_html__( 'Show Label', 'ayyash-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'ayyash-addons' ),
				'label_off' => esc_html__( 'Hide', 'ayyash-addons' ),
				'default'   => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_placement',
			[
				'label'     => esc_html__( 'Label Placement', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'before' => esc_html__( 'Before', 'ayyash-addons' ),
					'after'  => esc_html__( 'After', 'ayyash-addons' ),
				],
				'default'   => 'after',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_days',
			[
				'label'       => esc_html__( 'Days', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'd', 'ayyash-addons' ),
				'placeholder' => esc_html__( 'Days', 'ayyash-addons' ),
				'condition'   => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_hours',
			[
				'label'       => esc_html__( 'Hours', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'h', 'ayyash-addons' ),
				'placeholder' => esc_html__( 'Hours', 'ayyash-addons' ),
				'condition'   => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_minutes',
			[
				'label'       => esc_html__( 'Minutes', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'm', 'ayyash-addons' ),
				'placeholder' => esc_html__( 'Minutes', 'ayyash-addons' ),
				'condition'   => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_seconds',
			[
				'label'       => esc_html__( 'Seconds', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 's', 'ayyash-addons' ),
				'placeholder' => esc_html__( 'Seconds', 'ayyash-addons' ),
				'condition'   => [
					'show_labels!' => '',
				],
			]
		);

		$this->end_controls_section();

		// heading styles
		$this->__section_header();

		// countdown styles
		$this->__heading_countdown();
	}

	protected function __section_header() {
		$this->start_controls_section(
			'section_header_settings',
			[
				'label' => esc_html__( 'Section Header', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'heading_title',
			[
				'label' => esc_html__( 'Title', 'ayyash-addons' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'section_heading_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-heading-wrapper .ayyash-addons-heading-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'section_heading_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-heading-wrapper .ayyash-addons-heading-title',
			]
		);
		$this->add_control(
			'view_btn',
			[
				'label'     => esc_html__( 'View Button', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'view_btn_typography',
				'selector'  => '{{WRAPPER}} .ayyash-addons-btn-view',
				'separator' => 'before',
			]
		);
		$this->start_controls_tabs( 'view_btn_tabs' );
// Normal State Tab
		$this->start_controls_tab(
			'view_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'view_btn_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-heading-wrapper .ayyash-addons-btn-view' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-addons-heading-wrapper .ayyash-addons-btn-view svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'view_btn_background',
				'label'          => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block'    => true,
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Background',
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-heading-wrapper .ayyash-addons-btn-view',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'view_btn_border',
				'label'    => esc_html__( 'Border', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-heading-wrapper .ayyash-addons-btn-view',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'view_btn_shadow',
				'label'    => esc_html__( 'Box Shadow', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-heading-wrapper .ayyash-addons-btn-view',
			]
		);
		$this->add_responsive_control(
			'view_btn_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-heading-wrapper .ayyash-addons-btn-view' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
// Hover State Tab
		$this->start_controls_tab(
			'section_header_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'view_btn_hover_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-heading-wrapper .ayyash-addons-btn-view:hover'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-addons-heading-wrapper .ayyash-addons-btn-view:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'view_btn_background_hover',
				'label'          => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block'    => true,
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Background',
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-heading-wrapper .ayyash-addons-btn-view:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'view_border_hover',
				'label'    => esc_html__( 'Border', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-heading-wrapper .ayyash-addons-btn-view:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'view_box_shadow_hover',
				'label'    => esc_html__( 'Box Shadow', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-heading-wrapper .ayyash-addons-btn-view:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'separator_title',
			[
				'label'     => esc_html__( 'Heading Separator', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'section_separator_color',
			[
				'label'     => esc_html__( 'Top Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-heading-wrapper .has-section-separator:after' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'section_separator_btm_color',
			[
				'label'     => esc_html__( 'Bottom Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-heading-wrapper .has-section-separator' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __heading_countdown() {
		$this->start_controls_section(
			'section_wrapper_style',
			[
				'label'     => esc_html__( 'Wrapper', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_heading_countdown' => 'yes',
				],
			]
		);

		$this->add_control(
			'wrapper_width',
			[
				'label'      => esc_html__( 'Wrapper Width', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-heading-wrapper .psgTimer_numbers > div ' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'wrapper_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-countdown.psgTimer .psgTimer_numbers' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wrapper_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}}  .ayyash-addons-countdown.psgTimer .psgTimer_numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'wrapper_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}}  .ayyash-addons-countdown.psgTimer .psgTimer_numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_digit_style',
			[
				'label'     => esc_html__( 'Digit', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_heading_countdown' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'digit_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-countdown .psgTimer_numbers .psgTimer_unit .number',
			]
		);

		$this->add_control(
			'digit_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-countdown .psgTimer_numbers .psgTimer_unit .number' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'digit_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-countdown .psgTimer_numbers .psgTimer_unit .number' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'digit_border',
				'selector'  => '{{WRAPPER}}  .ayyash-addons-countdown .psgTimer_numbers .psgTimer_unit .number',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'digit_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}}  .ayyash-addons-countdown .psgTimer_numbers .psgTimer_unit .number' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'digit_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}  .ayyash-addons-countdown .psgTimer_numbers .psgTimer_unit .number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'digit_margin',
			[
				'label'      => esc_html__( 'Margin', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}  .ayyash-addons-countdown .psgTimer_numbers .psgTimer_unit .number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_Label_style',
			[
				'label'     => esc_html__( 'Label', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_heading_countdown' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label',
			]
		);

		$this->add_control(
			'label_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_align',
			[
				'label'     => esc_html__( 'Alignment', 'ayyash-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'ayyash-addons' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'ayyash-addons' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'flex-end'   => [
						'title' => esc_html__( 'Bottom', 'ayyash-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label' => 'align-self: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'label_border',
				'selector'  => '{{WRAPPER}}  .ayyash-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}}  .ayyash-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'label_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}  .ayyash-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_separator_style',
			[
				'label'     => esc_html__( 'Countdown Separator', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_heading_countdown' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'separator_typography',
				// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'exclude'  => [
					'font_family',
					'text_decoration',
					'text_transform',
					'font_style',
					'letter_spacing',
					'word_spacing',
					'font_weight',
				],
				'selector' => '{{WRAPPER}} .ayyash-addons-countdown .psgTimer_numbers > div:after',
			]
		);

		$this->add_control(
			'separator_text_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-countdown .psgTimer_numbers > div:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'separator_icon_right',
			[
				'label'      => esc_html__( 'Right', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => -10,
						'max'  => 30,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-countdown .psgTimer_numbers > div:after' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @throws Exception
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		$end_time = new \DateTime( $settings['due_date'] );
		$end_time = $end_time->format( 'd.m.Y H:i' ) . ' ' . Utils::get_timezone_string();

		$attributes = [
			'class'                => 'ayyash-addons-countdown',
			'data-timer-end'       => $end_time,
			'data-label-placement' => $settings['label_placement'] ?? 'auto',
		];

		if ( $settings['show_labels'] ) {
			$day     = $settings['label_days'] ?? '';
			$hour    = $settings['label_hours'] ?? '';
			$minutes = $settings['label_minutes'] ?? '';
			$seconds = $settings['label_seconds'] ?? '';

			$attributes = array_merge( $attributes, [
				'data-label-days'    => $day,
				'data-label-hours'   => $hour,
				'data-label-minutes' => $minutes,
				'data-label-seconds' => $seconds,
			] );
		}

		$this->add_render_attribute( 'counter-wrapper', $attributes );

		?>
		<div class="ayyash-addons-heading-wrapper">
			<?php if ( ( $settings['widget_title'] ) || ( $settings['view_all_btn'] ) || 'yes' == ( $settings['show_heading_countdown'] ) ) : ?>
				<div class="ayyash-addons-header<?php echo ( 'yes' == $settings['show_title_separator'] ) ? ' has-section-separator' : '' ?>  ">
					<?php if ( $settings['widget_title'] ) : ?>
						<div class="ayyash-addons-heading <?php echo ( 'yes' == $settings['show_heading_countdown'] ) ? ' has-countdown' : '' ?>  ">
							<h2 class="ayyash-addons-heading-title"><?php echo esc_html( $settings['widget_title'] ); ?></h2>
						</div>
					<?php endif; ?>
					<?php if ( 'yes' == $settings['show_heading_countdown'] ) { ?>
						<div <?php $this->print_render_attribute_string( 'counter-wrapper' ); ?>></div>
					<?php } ?>
					<?php if ( 'yes' == $settings['btn_link_switcher'] ) :?>
						<div class="ayyash-addons-buttons">
							<a href="<?php echo esc_url( $settings['veiw_all_btn_link']['url'] ); ?>" class="ayyash-addons-btn-view">
								<?php echo esc_html( $settings['view_all_btn'] ); ?>
								<?php if ( 'yes' === $settings['view_all_btn_icon'] ) { ?>
									<svg width="11" height="10" viewBox="0 0 11 10" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M10.6523 5.21383C10.9559 4.91024 10.9559 4.41721 10.6523 4.11362L6.76638 0.227691C6.46279 -0.0758971 5.96976 -0.0758971 5.66617 0.227691C5.36258 0.53128 5.36258 1.02431 5.66617 1.3279L8.22846 3.88775H0.777186C0.347305 3.88775 0 4.23506 0 4.66494C0 5.09482 0.347305 5.44212 0.777186 5.44212H8.22603L5.6686 8.00198C5.36501 8.30557 5.36501 8.7986 5.6686 9.10219C5.97219 9.40577 6.46522 9.40577 6.76881 9.10219L10.6547 5.21626L10.6523 5.21383Z"/>
									</svg>
								<?php } ?>
							</a>
						<?php elseif ( $settings['view_all_btn'] ) : ?>
							<p class="ayyash-addons-text-btn-view">
								<?php echo esc_html( $settings['view_all_btn'] ); ?>
							</p>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

}

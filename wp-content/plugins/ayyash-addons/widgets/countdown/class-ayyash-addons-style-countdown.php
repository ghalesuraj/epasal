<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Exception;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 */
class AyyashAddons_Style_Countdown extends Ayyash_Widget {

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
		return 'ayyash-countdown';
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
		return __( 'Count Down', 'ayyash-addons' );
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
		return 'ayyash-addons eicon-countdown';
	}

	public function get_keywords() {
		return [ 'countdown', 'number', 'timer', 'time', 'date' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-countdown',
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
			'ayyash-addons-countdown',
			'jquery-psgTimer',
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
	 *
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'ayyash-widgets' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_countdown',
			[
				'label' => esc_html__( 'Countdown', 'ayyash-addons' ),
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
					''  => esc_html__( 'none', 'ayyash-addons' ),
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
			'countdown_alignment',
			[
				'label'          => esc_html__( 'Alignment', 'ayyash-addons' ),
				'type'           => Controls_Manager::CHOOSE,
				'default'        => 'center',
				'options'        => [
					'left'   => [
						'title' => esc_html__( 'Left', 'ayyash-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ayyash-addons' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'ayyash-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .ayyash-addons-countdown.psgTimer ' => 'justify-content: {{VALUE}}',
				],
				'style_transfer' => true,
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
					'before'  => esc_html__( 'Before', 'ayyash-addons' ),
					'after'   => esc_html__( 'After', 'ayyash-addons' ),
					'outside' => esc_html__( 'Outside', 'ayyash-addons' ),
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

		$this->start_controls_section(
			'section_wrapper_style',
			[
				'label' => esc_html__( 'Wrapper', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'wrapper_width',
			[
				'label'      => esc_html__( 'Wrapper Width', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 500,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-countdown.psgTimer .psgTimer_numbers > div ' => 'width: {{SIZE}}{{UNIT}};',
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
				'label' => esc_html__( 'Digit', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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

		$this->end_controls_section();

		$this->start_controls_section(
			'section_Label_style',
			[
				'label' => esc_html__( 'Label', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
				'label' => esc_html__( 'Separator', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
					'line_height',
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
			'separator_countdown_right',
			[
				'label'      => esc_html__( 'Right', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => -100,
						'max'  => 100,
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

		$this->add_render_attribute( 'div', $attributes );
		?>


		<div <?php $this->print_render_attribute_string( 'div' ); ?>></div>
		<?php
	}


}

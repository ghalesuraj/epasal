<?php
/**
 * Swiper Slider Renderer For Controllers
 *
 * @package AyyashAddons
 * @author  Squiz Pty Ltd <products@squiz.net>
 * @version
 * @since
 * @license
 */

namespace AyyashAddons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

trait Ayyash_Addons_Slider_Controller {

	/**
	 * Render Slider Controls.
	 *
	 * @param array $options
	 */
	protected function render_slider_controller( array $options = [] ) {
		$defaults = [
			'settings_section'           => [
				'condition'  => [],
				'conditions' => [],
			],
			'slider_style_section'       => [
				'condition' => [
					'navigation' => [
						'dots',
						'arrows',
						'both',
					],
				],
			],

			'slides_to_show'             => [
				'min'         => 1,
				'max'         => 8,
				'default'     => 3,
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE_EXTRA => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET_EXTRA => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_LAPTOP => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_DESKTOP => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_WIDESCREEN => [
						'required' => false,
						'default'  => 2,
					],
				],
			],
			'slides_to_scroll'           => [
				'min'         => 1,
				'max'         => 8,
				'default'     => 1,
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE_EXTRA => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET_EXTRA => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_LAPTOP => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_DESKTOP => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_WIDESCREEN => [
						'required' => false,
						'default'  => 1,
					],
				],
			],
			'vertical_slider'            => 'false',
			'autoplay'                   => 'yes',
			'slides_center'              => 'false',
			'slides_loop'                => 'true',
			'mousewheel'                 => 'false',
			'autoplay_delay'             => [
				'min'     => '',
				'max'     => '',
				'default' => 5000,
			],
			'spacing_between'            => [
				'min'         => '',
				'max'         => '',
				'default'     => 20,
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE_EXTRA => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET_EXTRA => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_LAPTOP => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_DESKTOP => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_WIDESCREEN => [
						'required' => false,
						'default'  => 1,
					],
				],
			],
			'direction'                  => 'ltr',
			'effect'                     => 'slide',
			'navigation'                 => 'dots',
			// style
			'arrows_position'            => 'inside',
			'arrow_size'                 => [
				'range'     => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [ '{{WRAPPER}} .elementor-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};' ],
			],
			'arrow_radius'               => [
				'range'     => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [ '{{WRAPPER}} .elementor-swiper-button' => 'border-radius: {{Size}}{{UNIT}};' ],
			],
			'arrows_color'               => [
				'default'   => '',
				'selectors' => [ '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'color: {{VALUE}} !important;' ],
			],
			'arrows_color_hover'         => [
				'default'   => '',
				'selectors' => [ '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next:hover' => 'color: {{VALUE}} !important;' ],
			],
			'dots_position_prefix_class' => '',
			'dots_color'                 => [
				'default'   => '',
				'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};opacity:1' ],
			],
			'dots_active_color'          => [
				'default'   => '',
				'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background: {{VALUE}};' ],
			],
			'dots_size'                  => [
				'range'     => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ],
			],
		];

		$options = ayyash_addons_parse_args_recursive( $options, $defaults );

		$this->start_controls_section(
			'slider_settings_section',
			[
				'label'      => __( 'Slider Options', 'ayyash-addons' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'condition'  => $options['settings_section']['condition'],
				'conditions' => $options['settings_section']['conditions'],
			]
		);
		$this->add_responsive_control(
			'slides_to_show',
			[
				'label'       => __( 'Slides To Show', 'ayyash-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => $options['slides_to_show']['min'],
				'max'         => $options['slides_to_show']['max'],
				'default'     => $options['slides_to_show']['default'],
				'required'    => true,
				'device_args' => $options['slides_to_show']['device_args'],
			]
		);
		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'label'       => __( 'Slides to Scroll', 'ayyash-addons' ),
				'description' => __( 'Set how many slides are scrolled per swipe.', 'ayyash-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => $options['slides_to_scroll']['min'],
				'max'         => $options['slides_to_scroll']['max'],
				'default'     => $options['slides_to_scroll']['default'],
				'required'    => true,
				'device_args' => $options['slides_to_scroll']['device_args'],
			]
		);

		// Vertical slide support
		if ( true === $options['vertical_slider'] ) {
			$this->add_control(
				'vertical_slide',
				[
					'label'   => __( 'Enable vertical slide ?', 'ayyash-addons' ),
					'type'    => Controls_Manager::SWITCHER,
					'default' => 'no',
				]
			);

			$this->add_control(
				'per_column_items',
				[
					'label'       => __( 'Per column items', 'ayyash-addons' ),
					'type'        => Controls_Manager::NUMBER,
					'min'         => 1,
					'max'         => 15,
					'default'     => 1,
					'description' => __( 'When enable vertical slider, slide to show work like Column and per column items work like an single item', 'ayyash-addons' ),
					'condition'   => [ 'vertical_slide' => 'yes' ],
				]
			);
		}

		$this->add_control(
			'autoplay',
			[
				'label'   => __( 'Autoplay', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $options['autoplay'],
				'options' => [
					'yes' => __( 'Yes', 'ayyash-addons' ),
					'no'  => __( 'No', 'ayyash-addons' ),
				],
			]
		);
		$this->add_control(
			'slides_center',
			[
				'label'   => __( 'Center Mode', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $options['slides_center'],
				'options' => [
					'true'  => __( 'Yes', 'ayyash-addons' ),
					'false' => __( 'No', 'ayyash-addons' ),
				],
			]
		);
		$this->add_control(
			'slides_loop',
			[
				'label'   => __( 'Loop', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $options['slides_loop'],
				'options' => [
					'true'  => __( 'Yes', 'ayyash-addons' ),
					'false' => __( 'No', 'ayyash-addons' ),
				],
			]
		);
		$this->add_control(
			'mousewheel',
			[
				'label'   => __( 'Mousewheel', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $options['mousewheel'],
				'options' => [
					'true'  => __( 'Yes', 'ayyash-addons' ),
					'false' => __( 'No', 'ayyash-addons' ),
				],
			]
		);
		$this->add_control(
			'autoplay_delay',
			[
				'label'     => __( 'Autoplay Speed (ms)', 'ayyash-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => $options['autoplay_delay']['min'],
				'max'       => $options['autoplay_delay']['max'],
				'default'   => $options['autoplay_delay']['default'],
				'condition' => [ 'autoplay' => 'yes' ],
			]
		);
		$this->add_responsive_control(
			'spacing_between',
			[
				'label'   => __( 'Distance between slides in px', 'ayyash-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => $options['spacing_between']['min'],
				'max'     => $options['spacing_between']['max'],
				'default' => $options['spacing_between']['default'],
			]
		);
		$this->add_control(
			'direction',
			[
				'label'   => __( 'Direction', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $options['direction'],
				'options' => [
					'ltr' => __( 'Left', 'ayyash-addons' ),
					'rtl' => __( 'Right', 'ayyash-addons' ),
				],
			]
		);
		$this->add_control(
			'effect',
			[
				'label'       => __( 'Effect', 'ayyash-addons' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => $options['effect'],
				'options'     => [
					'slide' => __( 'Slide', 'ayyash-addons' ),
					'fade'  => __( 'Fade', 'ayyash-addons' ),
				],
				'description' => __( 'Fade effect works when "Slides to Show" is 1', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'navigation',
			[
				'label'   => __( 'Navigation', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $options['navigation'],
				'options' => [
					'both'   => __( 'Arrows and Dots', 'ayyash-addons' ),
					'arrows' => __( 'Arrows', 'ayyash-addons' ),
					'dots'   => __( 'Dots', 'ayyash-addons' ),
					'none'   => __( 'None', 'ayyash-addons' ),
				],
			]
		);
		$this->end_controls_section();

		// style navigation
		$this->start_controls_section(
			'slider_style_section',
			[
				'label'     => esc_html__( 'Slider', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => $options['slider_style_section']['condition'],
			]
		);
		$this->add_control(
			'slider_nav_style',
			[
				'label'     => esc_html__( 'Slider Navigation', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->add_control(
			'navigation_prev_icon',
			[
				'label'            => __( 'Previous Icon', 'ayyash-addons' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'fa4compatibility' => 'ayyash-addons',
				'default'          => [
					'value'   => 'fas fa-chevron-left',
					'library' => 'solid',
				],
				'recommended'      => [
					'fa-solid'   => [
						'chevron-left',
						'angle-left',
						'angle-left',
						'angle-double-left',
						'caret-left',
						'caret-square-left',
					],
					'fa-regular' => [ 'caret-square-up' ],
				],
				'skin'             => 'inline',
				'condition'        => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->add_control(
			'navigation_next_icon',
			[
				'label'            => __( 'Next Icon', 'ayyash-addons' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'fa4compatibility' => 'ayyash-addons',
				'default'          => [
					'value'   => 'fas fa-chevron-right',
					'library' => 'solid',
				],
				'recommended'      => [
					'fa-solid'   => [
						'chevron-right',
						'angle-right',
						'angle-double-right',
						'caret-right',
						'caret-square-right',
					],
					'fa-regular' => [ 'caret-square-right' ],
				],
				'skin'             => 'inline',
				'condition'        => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->add_control(
			'arrows_position',
			[
				'label'        => __( 'Arrows', 'ayyash-addons' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => $options['arrows_position'],
				'options'      => [
					'inside'  => __( 'Inside', 'ayyash-addons' ),
					'outside' => __( 'Outside', 'ayyash-addons' ),
				],
				'prefix_class' => 'elementor-arrows-position-',
				'condition'    => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->add_control(
			'arrow_size',
			[
				'label'     => __( 'Arrow Size', 'ayyash-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => $options['arrow_size']['range'],
				'selectors' => $options['arrow_size']['selectors'],
				'condition' => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->add_responsive_control(
			'slider_navigation_button_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'%',
				],
				'selectors'  => [ '{{WRAPPER}} .elementor-swiper-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
				'condition'  => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->start_controls_tabs( 'navigation_button_style' );
		// normal
		$this->start_controls_tab(
			'navigation_button_normal',
			[
				'label'     => esc_html__( 'Normal', 'ayyash-addons' ),
				'condition' => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'slider_navigation_background',
				'label'     => esc_html__( 'Navigation Background', 'ayyash-addons' ),
				'types'     => [
					'classic',
					'gradient',
				],
				'selector'  => '{{WRAPPER}} .elementor-swiper-button ',
				'condition' => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->add_control(
			'slider_navigation_button_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#161616',
				'selectors' => [ '{{WRAPPER}} .elementor-swiper-button i' => 'color: {{VALUE}} !important;' ],
				'condition' => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'slider_navigation_border',
				'selector'  => '{{WRAPPER}} .elementor-swiper-button ',
				'condition' => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->add_control(
			'arrow_border_radius',
			[
				'label'     => __( 'Border Radius', 'ayyash-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => $options['arrow_radius']['range'],
				'selectors' => $options['arrow_radius']['selectors'],
				'condition' => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->end_controls_tab();
		// hover
		$this->start_controls_tab(
			'nav_button_hover',
			[
				'label'     => __( 'Hover', 'ayyash-addons' ),
				'condition' => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'nav_hover_background',
				'label'     => esc_html__( 'Background', 'ayyash-addons' ),
				'types'     => [
					'classic',
					'gradient',
				],
				'selector'  => '{{WRAPPER}} .elementor-swiper-button:hover',
				'condition' => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->add_control(
			'nav_button_hover_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .elementor-swiper-button:hover i' => 'color: {{VALUE}} !important;' ],
				'condition' => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->add_control(
			'nav_button_border_hover_color',
			[
				'label'     => esc_html__( 'Border Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .elementor-swiper-button:hover' => 'border-color: {{VALUE}} !important;' ],
				'condition' => [
					'navigation' => [
						'arrows',
						'both',
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// style dots
		$this->add_control(
			'slider_dots_style',
			[
				'label'     => esc_html__( 'Slider Dots', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'navigation' => [
						'dots',
						'both',
					],
				],
			]
		);
		$this->add_responsive_control(
			'dot_top_gaps',
			[
				'label'      => esc_html__( 'Top Gap', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors'  => [ '{{WRAPPER}} .swiper-pagination.swiper-pagination-bullets' => 'margin-top: {{SIZE}}{{UNIT}} !important;' ],
				'condition'  => [
					'navigation' => [
						'dots',
						'both',
					],
				],
			]
		);
		$this->add_control(
			'dot_between_gaps',
			[
				'label'      => esc_html__( 'Between Gap', 'ayyash-addons' ),
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
					'size' => 5,
				],
				'selectors'  => [ '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}} !important;' ],
				'condition'  => [
					'navigation' => [
						'dots',
						'both',
					],
				],
			]
		);
		$this->start_controls_tabs( 'dot_button_style' );
		$this->start_controls_tab(
			'dot_button_normal',
			[
				'label'     => esc_html__( 'Normal', 'ayyash-addons' ),
				'condition' => [
					'navigation' => [
						'dots',
						'both',
					],
				],
			]
		);
		$this->add_control(
			'dots_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $options['dots_color']['default'],
				'selectors' => $options['dots_color']['selectors'],
				'condition' => [
					'navigation' => [
						'dots',
						'both',
					],
				],
			]
		);
		$this->add_control(
			'dots_size',
			[
				'label'     => __( 'Size', 'ayyash-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => $options['dots_size']['range'],
				'selectors' => $options['dots_size']['selectors'],
				'condition' => [
					'navigation' => [
						'dots',
						'both',
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'dot_button_active',
			[
				'label'     => esc_html__( 'Active/Hover', 'ayyash-addons' ),
				'condition' => [
					'navigation' => [
						'dots',
						'both',
					],
				],
			]
		);
		$this->add_control(
			'dots_active_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $options['dots_active_color']['default'],
				'selectors' => $options['dots_active_color']['selectors'],
				'condition' => [
					'navigation' => [
						'dots',
						'both',
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

	}

	protected $slider_dot;

	protected $slider_next;

	protected $slider_prev;


	/**
	 * @param array $settings
	 * @param int $spacing_between
	 *
	 * phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
	 * @return array
	 */
	protected function get_slider_attributes( array $settings = [], int $spacing_between = 0 ): array {
		if ( empty( $settings ) ) {
			$settings = $this->get_settings_for_display();
		}

		$unique_id = uniqid( time() );

		$this->slider_dot  = wp_unique_id( 'nav-dot-' ) . '-' . $unique_id;
		$this->slider_next = wp_unique_id( 'nav-next-' ) . '-' . $unique_id;
		$this->slider_prev = wp_unique_id( 'nav-prev-' ) . '-' . $unique_id;

		return array_merge(
			[
				'data-slides-center'    => esc_attr( $settings['slides_center'] ),
				'data-autoplay'         => esc_attr( $settings['autoplay'] ),
				'data-autoplay-delay'   => esc_attr( $settings['autoplay_delay'] ),
				'data-effect'           => esc_attr( $settings['effect'] ),
				'data-slides-loop'      => esc_attr( $settings['slides_loop'] ),
				'data-mousewheel'       => esc_attr( $settings['mousewheel'] ),
				'dir'                   => esc_attr( $settings['direction'] ),
				'data-slides-dot'       => esc_attr( $this->slider_dot ),
				'data-slides-next'      => esc_attr( $this->slider_next ),
				'data-slides-prev'      => esc_attr( $this->slider_prev ),
				'data-slides-per-view'  => esc_attr( $settings['slides_to_show'] ),
				'data-slides-per-group' => esc_attr( $settings['slides_to_scroll'] ),
				'data-space-between'    => esc_attr( $settings['spacing_between'] ),
			],
			ayyash_addons_extract_screen_settings( $settings, [
				'key'        => 'slides_to_show',
				'key_prefix' => 'data-slides-per-view-',
			] ),
			ayyash_addons_extract_screen_settings( $settings, [
				'key'        => 'slides_to_scroll',
				'key_prefix' => 'data-slides-per-group-',
			] ),
			ayyash_addons_extract_screen_settings( $settings, [
				'key'        => 'spacing_between',
				'key_prefix' => 'data-space-between-',
			] )
		);

	}


	public function print_slider_inline_css( $settings = [], $maybe_print = false ) {
		if ( ! $maybe_print ) {
			return;
		}
		echo ' style="' . esc_attr( $this->get_slider_inline_css( $settings ) ) . '"';
	}

	protected function get_slider_inline_css( $settings = [] ) {
		if ( empty( $settings ) ) {
			$settings = $this->get_settings_for_display();
		}

		$vars = '--slides-per-view:' . $settings['slides_to_show'] . ';--space-between:' . $settings['spacing_between'] . 'px;';
		$vars .= ayyash_addons_extract_screen_settings( $settings, [
			'key'          => 'slides_to_show',
			'key_prefix'   => '--slides-per-view-',
			'value_prefix' => ':',
			'value_suffix' => ';',
			'output'       => 'string',
		] );
		$vars .= ayyash_addons_extract_screen_settings( $settings, [
			'key'          => 'spacing_between',
			'key_prefix'   => '--space-between-',
			'value_prefix' => ':',
			'value_suffix' => 'px;',
			'output'       => 'string',
		] );

		return $vars;
	}


	/**
	 * Slider Nav.
	 *
	 * @param array $settings settings
	 */
	protected function slider_nav( array $settings = [] ) {
		if ( empty( $settings ) ) {
			$settings = $this->get_settings_for_display();
		}

		if ( in_array( $settings['navigation'], [ 'dots', 'both' ], true ) ) {
			?>
			<div class="swiper-pagination  <?php echo esc_attr( $this->slider_dot ); ?>"></div>
			<?php
		}

		if ( in_array( $settings['navigation'], [ 'arrows', 'both' ], true ) ) {
			?>
			<div class="ayyash-addons-slider-nav">
				<div
					class="elementor-swiper-button elementor-swiper-button-prev ayyash-addons-nav-prev <?php echo esc_attr( $this->slider_prev ); ?>">
					<?php Icons_Manager::render_icon( $settings['navigation_prev_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					<span class="elementor-screen-only"><?php esc_html_e( 'Previous', 'ayyash-addons' ); ?></span>
				</div>
				<div
					class="elementor-swiper-button elementor-swiper-button-next ayyash-addons-nav-next <?php echo esc_attr( $this->slider_next ); ?>">
					<?php Icons_Manager::render_icon( $settings['navigation_next_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					<span class="elementor-screen-only"><?php esc_html_e( 'Next', 'ayyash-addons' ); ?></span>
				</div>
			</div>
			<?php
		}

	}


}

// End of file trait-ayyash-addons-slider-controller.php.

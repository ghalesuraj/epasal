<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Utils;
use ElementorPro\Core\Utils as ProUtils;
use ElementorPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 */
class AyyashAddons_Style_Vertical_menu extends Ayyash_Widget {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ayyash-vertical-menu';
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
		return __( 'Vertical Menu', 'ayyash-addons' );
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
		return 'ayyash-addons eicon-menu-bar';
	}

	public function get_keywords() {
		return [ 'menu', 'navigation', 'vertical', 'nav' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-vertical-menu',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'ayyash-addons-vertical-menu',
			'superfish',
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

	// register controls
	protected function register_controls() {
		//content controller
		$this->register_menu_content_controls();

		//style controller
		$this->__register_menu_style();
		$this->__register_menu_item_style();
		$this->__register_mega_menu_style();
		$this->__register_menu_hamburger_style();

	}

	// layout content controls
	protected function register_menu_content_controls() {
		$this->start_controls_section(
			'section_menu_content',
			array(
				'label' => esc_html__( 'Menu', 'ayyash-addons' ),
			)
		);

		$current_theme = wp_get_theme();
		if ( 'Ayyash' !== $current_theme->get( 'Name' ) ) {
			$this->add_control(
				'ayyash_theme',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s Nav menu URL */
					'raw'             => sprintf( __( 'Some features may not work with current theme. For full compatibility please install <a href="%s" target="_blank">Ayyash</a> Theme.', 'ayyash-addons' ), admin_url( 'theme-install.php?search=ayyash' ) ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		if ( has_nav_menu( 'vertical_menu' ) ) {
			$this->add_control(
				'menu',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s Nav menu URL */
					'raw'             => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'ayyash-addons' ), admin_url( 'nav-menus.php' ) ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s Nav menu URL */
					'raw'             => sprintf( __( '<strong>There is no vertical menu.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'ayyash-addons' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}
		$this->add_control(
			'menu_collapse',
			[
				'label'        => esc_html__( 'Menu Collapse On Load?', 'ayyash-addons' ),
				'description'  => esc_html__( 'Menu will be collapsed on initial page load', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'prefix_class' => 'menu-collapse-',
			]
		);
		$this->add_control(
			'menu-collapse-page',
			[
				'label'     => esc_html__( 'Menu Collapse Page', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'all-page',
				'options'   => [
					'all-page'           => esc_html__( 'All Pages', 'ayyash-addons' ),
					'without-front-page' => esc_html__( 'Without Front page', 'ayyash-addons' ),
				],
				'condition' => [ 'menu_collapse' => 'yes' ],
			]
		);
		$this->add_control(
			'show_menu_indicator',
			[
				'label'        => esc_html__( 'Menu Indicator?', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'show-menu-indicator-',
			]
		);
		$this->add_control(
			'hamburger_menu_align',
			[
				'label'     => __( 'Menu Items Align', 'ayyash-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start'    => [
						'title' => __( 'Left', 'ayyash-addons' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'        => [
						'title' => __( 'Center', 'ayyash-addons' ),
						'icon'  => 'eicon-h-align-center',
					],
					'flex-end'      => [
						'title' => __( 'Right', 'ayyash-addons' ),
						'icon'  => 'eicon-h-align-right',
					],
					'space-between' => [
						'title' => __( 'Justify', 'ayyash-addons' ),
						'icon'  => 'eicon-h-align-stretch',
					],
				],
				'default'   => 'space-between',
				'selectors' => [
					'{{WRAPPER}} .ayyash-menu a, {{WRAPPER}} .ayyash-mega-menu > a'   => 'justify-content: {{VALUE}};',
				],
			]
		);
		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			$this->add_control(
				'hamburger_icon',
				[
					'label'       => __( 'Menu Icon', 'ayyash-addons' ),
					'type'        => Controls_Manager::ICONS,
					'label_block' => 'true',
					'default'     => [
						'value'   => 'fas fa-align-justify',
						'library' => 'fa-solid',
					],
				]
			);
		} else {
			$this->add_control(
				'hamburger_icon',
				[
					'label'       => __( 'Icon', 'ayyash-addons' ),
					'type'        => Controls_Manager::ICON,
					'label_block' => 'true',
					'default'     => 'fa fa-align-justify',
				]
			);
		}

		$this->add_control(
			'vertical_menu_title',
			[
				'label'       => esc_html__( 'Title', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Menu', 'ayyash-addons' ),
				'placeholder' => esc_html__( 'Type your title here', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'show_menu_title_indicator',
			[
				'label'        => esc_html__( 'Menu Dropdown Icon?', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'show-menu-title-indicator-',
			]
		);

		$this->end_controls_section();

	}

	// layout menu style controls
	protected function __register_menu_style() {
		$this->start_controls_section(
			'section_menu_style',
			[
				'label' => esc_html__( 'Menu', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'menu_width',
			[
				'label'     => esc_html__( 'Width', 'ayyash-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 300,
				],
				'selectors' => [
					'{{WRAPPER}} .vertical-nav, {{WRAPPER}} .vertical-nav .ayyash-menu .sub-menu' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'menu_top_spacing',
			[
				'label'     => esc_html__( 'Top Space', 'ayyash-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .vertical-nav' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'menu_border',
				'selector' => '{{WRAPPER}} .vertical-nav, {{WRAPPER}} .vertical-nav .sub-menu',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'           => 'menu_box_shadow',
				'selector'       => '{{WRAPPER}} .vertical-nav, {{WRAPPER}} .vertical-nav .sub-menu',
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
									'vertical'   => 8,
									'blur'       => 16,
									'spread'     => 0,
									'color'      => 'rgba(11, 39, 72, 0.1)',
								],
						],
					],
			]
		);
		$this->end_controls_section();
	}

	// layout menu item style controls
	protected function __register_menu_item_style() {
		$this->start_controls_section(
			'section_menu_item_style',
			[
				'label' => esc_html__( 'Menu Item', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'menu_item_typography',
				'selector' => '{{WRAPPER}} .ayyash-menu a, {{WRAPPER}} .ayyash-mega-menu > a',
			]
		);
		$this->add_control(
			'menu_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'    => '10',
					'right'  => '20',
					'bottom' => '10',
					'left'   => '20',
					'unit'   => 'px',
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-menu a'        => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-mega-menu > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs(
			'menu_item_style_tabs'
		);
		$this->start_controls_tab(
			'menu_item_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'menu_item_text_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0B2748',
				'selectors' => [
					'{{WRAPPER}} .ayyash-menu a'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-mega-menu > a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'menu_item_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ayyash-menu a, {{WRAPPER}} .ayyash-mega-menu > a',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'menu_item_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'menu_item_text_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1849C8',
				'selectors' => [
					'{{WRAPPER}} .ayyash-menu a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-mega-menu > a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'menu_item_background_hover',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ayyash-menu a:hover, {{WRAPPER}} .ayyash-mega-menu > a:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		//divider
		$this->add_control(
			'heading_dropdown_divider',
			[
				'label'     => __( 'Divider', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'menu_item_divider_border',
			[
				'label'       => __( 'Border Style', 'ayyash-addons' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'solid',
				'label_block' => false,
				'options'     => [
					'none'   => __( 'None', 'ayyash-addons' ),
					'solid'  => __( 'Solid', 'ayyash-addons' ),
					'double' => __( 'Double', 'ayyash-addons' ),
					'dotted' => __( 'Dotted', 'ayyash-addons' ),
					'dashed' => __( 'Dashed', 'ayyash-addons' ),
				],
				'selectors'   => [
					'{{WRAPPER}} .vertical-navigation > li:not(:last-child) > a' => 'border-bottom-style: {{VALUE}}',
					'{{WRAPPER}} .ayyash-menu .sub-menu li:not(:last-child) a' => 'border-bottom-style: {{VALUE}}',
					'{{WRAPPER}} .ayyash-menu .sub-menu .sub-menu li:not(:last-child) a' => 'border-bottom-style: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'menu_item_divider_border_color',
			[
				'label'     => __( 'Border Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c4c4c4',
				'selectors' => [
					'{{WRAPPER}} .vertical-navigation > li:not(:last-child) > a' => 'border-bottom-color: {{VALUE}}',
					'{{WRAPPER}} .ayyash-menu .sub-menu li:not(:last-child) a' => 'border-bottom-color: {{VALUE}}',
					'{{WRAPPER}} .ayyash-menu .sub-menu .sub-menu li:not(:last-child) a' => 'border-bottom-color: {{VALUE}}',
				],
				'condition' => [
					'menu_item_divider_border!' => 'none',
				],
			]
		);
		$this->add_control(
			'menu_item_dropdown_divider_width',
			[
				'label'     => __( 'Border Width', 'ayyash-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'default'   => [
					'size' => '1',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .vertical-navigation > li:not(:last-child) > a' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .ayyash-menu .sub-menu li:not(:last-child) a' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .ayyash-menu .sub-menu .sub-menu li:not(:last-child) a' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'menu_item_divider_border!' => 'none',
				],
			]
		);
		$this->end_controls_section();
	}

	// layout mega menu style controls
	protected function __register_mega_menu_style() {
		$current_theme = wp_get_theme();
		if ( 'Ayyash' !== $current_theme->get( 'Name' ) ) {
			return;
		}
		$this->start_controls_section(
			'section_mega_menu_style',
			[
				'label' => esc_html__( 'Mega Menu', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'mega_menu_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'    => '30',
					'right'  => '30',
					'bottom' => '30',
					'left'   => '30',
					'unit'   => 'px',
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-mega-menu > ul.sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'mega_menu_heading',
			[
				'label'     => esc_html__( 'Heading', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mega_menu_title_typography',
				'selector' => '{{WRAPPER}} .ayyash-mega-menu >ul.sub-menu .ayyash-title.ayyash-link',
			]
		);
		$this->add_control(
			'mega_menu_title_bottom_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'size' => 15,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-mega-menu >ul.sub-menu .ayyash-title.ayyash-link' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs(
			'mega_menu_title_style_tabs'
		);
		$this->start_controls_tab(
			'mega_menu_title_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'mega_menu_title_text_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'default'   => '#1849C8',
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-mega-menu >ul.sub-menu .ayyash-title.ayyash-link ' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'mega_menu_title_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'mega_menu_title_text_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-mega-menu >ul.sub-menu .ayyash-title.ayyash-link:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		//mega menu items
		$this->add_control(
			'mega_menu_items',
			[
				'label'     => esc_html__( 'Mega Menu Items', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mega_menu_item_typography',
				'selector' => '{{WRAPPER}} .ayyash-mega-menu >ul.sub-menu .ayyash-link',
			]
		);
		$this->add_control(
			'menu_item_bottom_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'{{WRAPPER}} .ayyash-mega-menu >ul.sub-menu .ayyash-link' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs(
			'mega_menu_item_style_tabs'
		);
		$this->start_controls_tab(
			'mega_menu_item_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'mega_menu_item_text_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'default'   => '#0B2748',
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-mega-menu >ul.sub-menu .ayyash-link ' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'mega_menu_item_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'mega_menu_item_text_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'default'   => '#1849C8',
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-mega-menu >ul.sub-menu .ayyash-link:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	// layout menu hamburger style controls
	protected function __register_menu_hamburger_style() {
		$this->start_controls_section(
			'section_menu_hamburger_style',
			[
				'label' => esc_html__( 'Menu Hamburger', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'menu_hamburger_padding',
			[
				'label'      => esc_html__( 'Margin', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default'    => [
					'top'    => '10',
					'right'  => '15',
					'bottom' => '10',
					'left'   => '15',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} #vertical-menu-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'menu_hamburger_size',
			[
				'label'     => esc_html__( 'Icon Size', 'ayyash-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} #vertical-menu-toggle' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Title Typography', 'ayyash-addons' ),
				'name'     => 'menu_hamburger_title_typography',
				'exclude'  => [ 'line_height' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'selector' => '{{WRAPPER}} #vertical-menu-toggle .text',
			]
		);
		$this->start_controls_tabs(
			'menu_hamburger_style_tabs'
		);
		$this->start_controls_tab(
			'menu_hamburger_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'menu_hamburger_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0B2748',
				'selectors' => [
					'{{WRAPPER}} #vertical-menu-toggle' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'menu_hamburger_background',
				'types'    => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} #vertical-menu-toggle, {{WRAPPER}} #vertical-menu-toggle:after',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'menu_hamburger_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'menu_hamburger_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1849C8',
				'selectors' => [
					'{{WRAPPER}} #vertical-menu-toggle:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'menu_hamburger_background_hover',
				'types'    => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} #vertical-menu-toggle:hover, {{WRAPPER}} #vertical-menu-toggle:hover:after',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render_menu( $settings ) {
		$menu_collapse = '';
		if ( is_front_page() && $settings['menu-collapse-page'] ) {
			$menu_collapse = 'menu-collapse-' . $settings['menu-collapse-page'];
		}
		?>
		<a href="#" id="vertical-menu-toggle" role="button" aria-controls="vertical-nav">
			<?php Icons_Manager::render_icon( $settings['hamburger_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			<span class="text"><?php if ( $settings['vertical_menu_title'] ) echo  esc_html( $settings['vertical_menu_title'] ); ?></span>
		</a>

		<div id="vertical-nav" class="vertical-nav <?php if ( ! empty( $menu_collapse ) ) echo esc_attr($menu_collapse); ?>" role="navigation">
			<?php
			if ( has_nav_menu( 'vertical_menu' ) ) {
				wp_nav_menu( array( 'theme_location' => 'vertical_menu' ) );
			} else {
				?>
				<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" class="ayyash-sticky-item"><?php esc_html_e( 'No Vertical Menu Assigned , Please Add', 'ayyash-addons' ); ?></a>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * @render Menu
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="ayyash-addons-wrapper ayyash-addons-<?php echo str_replace( 'ayyash-', '', $this->get_name() ); // phpcs:ignore ?>">
			<?php $this->render_menu( $settings ); ?>
		</div>
		<?php
	}
}

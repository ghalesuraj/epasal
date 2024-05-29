<?php

namespace AyyashAddonsPro\Widgets;

use AyyashAddonsPro\Ayyash_Pro_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class AyyashAddons_Style_Woocommerce_Attribute_Filter extends Ayyash_Pro_Widget {


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
		return 'ayyash-woocommerce-attribute-filter';
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
		return __( 'Woocommerce Attribute Filter', 'ayyash-addons-pro' );
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
		return 'ayyash-addons eicon-search';
	}

	public function get_categories() {
		return array( 'ayyash-pro-widgets' );
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-pro-woocommerce-attr-filter',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'ayyash-addons-pro-woocommerce-attr-filter' );
	}

	protected function register_controls() {
		$this->add_fields();
		$this->button();
		$this->__input_style();
		$this->__button_style();
	}


	protected function add_fields() {

		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$field_type = [ '' => __( 'Type', 'ayyash-addons-pro' ) ];

		foreach ( wc_get_attribute_taxonomies() as $tax ) {
			$field_type [ $tax->attribute_name ] = __( $tax->attribute_label, 'ayyash-addons-pro' );
		}

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'filed_alignment',
			[
				'label'     => __( 'Alignment', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => __( 'Left', 'ayyash-addons-pro' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'ayyash-addons-pro' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'ayyash-addons-pro' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => 'flex-start',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'select_type',
			[
				'label'   => esc_html__( 'Search Type', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'vertical'   => esc_html__( 'Vertical', 'ayyash-addons-pro' ),
					'horizontal' => esc_html__( 'Horizontal', 'ayyash-addons-pro' ),
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'field_type',
			[
				'label'       => esc_html__( 'Type', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => $field_type,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'field_input_type',
			[
				'label'   => esc_html__( 'Field Type', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'select',
			]
		);


		$repeater->add_control(
			'field_placeholder',
			[
				'label'      => esc_html__( 'Placeholder', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::TEXT,
				'default'    => esc_html__( 'Select', 'ayyash-addons-pro' ),
				'show_label' => true,
			]
		);

		$repeater->add_control(
			'field_class',
			[
				'label'      => esc_html__( 'Class', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::TEXT,
				'default'    => esc_html__( 'Class', 'ayyash-addons-pro' ),
				'show_label' => true,
			]
		);

		$this->add_control(
			'field',
			[
				'label'       => esc_html__( 'Add Fields', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),

				'title_field' => '{{{ field_type }}}',
			]
		);

		$this->end_controls_section();
	}

	protected function button() {
		$this->start_controls_section(
			'button_section',
			[
				'label' => esc_html__( 'Button', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => esc_html__( 'Button Text', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Search', 'ayyash-addons-pro' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label'            => esc_html__( 'Button Icon', 'ayyash-addons-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'label_block'      => false,
				'skin'             => 'inline',
				'default'          => [
					'value'   => 'fas fa-search',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'label'     => esc_html__( 'Icon Position', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'  => esc_html__( 'Before', 'ayyash-addons-pro' ),
					'right' => esc_html__( 'After', 'ayyash-addons-pro' ),
				],
				'condition' => [
					'button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label'      => esc_html__( 'Icon Spacing', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'max' => 50,
					],
				],
				'condition'  => [
					'button_icon[value]!' => '',
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button .align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button .align-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __input_style() {
		$this->start_controls_section(
			'input_style',
			[
				'label' => esc_html__( 'Input', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'input_width',
			[
				'label'      => esc_html__( 'Width', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 200,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select',
			]
		);

		$this->start_controls_tabs( 'tabs_input_style' );

		$this->start_controls_tab(
			'input_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);

		$this->add_control(
			'input_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'input_background',
				'label'    => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'input_border',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select',
			]
		);

		$this->add_control(
			'input_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'input_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]
		);

		$this->add_control(
			'input_hover_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'input_hover_background',
				'label'    => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'input_hover_border',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select:hover',
			]
		);

		$this->add_control(
			'input_hover_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'input_disabled',
			[
				'label' => esc_html__( 'Disabled', 'ayyash-addons-pro' ),
			]
		);

		$this->add_control(
			'input_disabled_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select:disabled' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'input_disabled_background',
				'label'    => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select:disabled',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'input_disabled_border',
				'label'    => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select:disabled',
			]
		);

		$this->add_control(
			'input_disabled_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select:disabled' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'input_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_margin',
			[
				'label'      => esc_html__( 'Margin', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();
	}

	protected function __button_style() {
		$this->start_controls_section(
			'section_button',
			array(
				'label' => esc_html__( 'Button', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);

		$this->add_control(
			'button_alignment',
			[
				'label'     => esc_html__( 'Alignment', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'   => [
						'title' => esc_html__( 'Right', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'flex-start',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button' => 'align-self: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'submit_width_button',
			[
				'label'          => __( 'Width', 'ayyash-addons-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => [ 'px', '%' ],
				],
				'mobile_default' => [
					'unit' => [ 'px', '%' ],
				],
				'size_units'     => [ 'px', '%' ],
				'range'          => [
					'px' => [
						'min' => 150,
						'max' => 350,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_TYPOGRAPHY::get_type(),
			[
				'label'    => esc_html__( 'Typography', 'ayyash-addons-pro' ),
				'name'     => 'button_typo',
				'selector' => '{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button',
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button'                       => 'color: {{VALUE}}',
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button .button-icon'          => 'color: {{VALUE}}',
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button .button-icon svg'      => 'fill: {{VALUE}}',
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button .button-icon svg path' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'button_bg_color',
				'fields_options' => [
					'background' => [
						'label' => __( 'Button Background Color', 'ayyash-addons-pro' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button',
				'default'        => '#fc5a34',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'button_border',
				'label'    => __( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label'      => __( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => __( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'    => 10,
					'right'  => 50,
					'left'   => 50,
					'bottom' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label'      => __( 'Margin', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fc5a34',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button:hover'                       => 'color: {{VALUE}}',
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button:hover .button-icon'          => 'color: {{VALUE}}',
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button:hover .button-icon svg'      => 'fill: {{VALUE}}',
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button:hover .button-icon svg path' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'button_hover_bg_color',
				'fields_options' => [
					'background' => [
						'label' => __( 'Button Background Color', 'ayyash-addons-pro' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button:hover',
				'default'        => '#ffffff',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'button_hover_border',
				'label'    => __( 'Border', 'ayyash-addons-pro' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button:hover',
			]
		);

		$this->add_responsive_control(
			'button_hover_border_radius',
			[
				'label'      => __( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-wc-attr-filter-wrap form  .submit_button button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			printf( '<div class="ayyash-addons-alert alert-warning">%s</div>', __( 'Please Install/Activate Woocommerce Plugin.', 'ayyash-addons-pro' ) );

			return;
		}

		$settings = $this->get_settings_for_display();

		$uid = $this->get_id();

		$this->add_render_attribute(
			'wc_attr_filter',
			[
				'class' => [
					'ayyash-addons-wc-attr-filter-wrap',
					'layout-' . $settings['select_type'],
				],
			]
		);


		?>
		<div <?php $this->print_render_attribute_string( 'wc_attr_filter' ) ?>>
			<form action="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" method="get">
				<?php
				$count = 1;
				foreach ( $settings['field'] as $input ) {

					if ( 'input' == $input['field_input_type'] ) {
						$this->render_input( $input );
					}


					if ( 'select' == $input['field_input_type'] ) {
						$this->render_select( $input, $count, $uid );
					}
					$count ++;
				}
				if ( count( $settings['field'] ) > 0 ) {
					$this->render_submit_btn( $settings );
				}
				?>
			</form>
		</div>

		<?php
	}

	protected function render_input( $settings ) {
		?>
		<input type="text" id="filter-<?php esc_attr_e( $settings['field_type'] , 'ayyash-addons-pro'); ?>"
			   placeholder="<?php echo esc_html( $settings['field_placeholder'] ); ?>"
			   name="<?php echo esc_html( $settings['field_type'] ); ?>">
		<?php
	}

	protected function render_select( $settings, $count, $uid ) {
		?>

		<select name="filter_<?php esc_attr_e( $settings['field_type'] ); ?>"
				class="<?php esc_attr_e( $settings['field_class'] ) ?> filter-<?php esc_attr_e( $settings['field_type'] ); ?>"
				id="filter-<?php esc_attr_e( $settings['field_type'] ); ?>-<?php esc_attr_e( $uid ); ?>"
				data-attr-name="<?php esc_attr_e( $settings['field_type'] ); ?>" <?php echo ( 1 < $count ) ? 'disabled' : ''; ?>>
			<option value=""><?php esc_html_e( $settings['field_placeholder'] ); ?></option>
			<?php $terms = get_terms( array( 'taxonomy' => 'pa_' . $settings['field_type'] ) );
			foreach ( $terms as $attr ) {
				?>
				<option value="<?php esc_attr_e( $attr->slug ) ?>"><?php esc_html_e( $attr->name ) ?></option>
				<?php
			}
			?>
		</select>
		<?php
	}

	protected function render_submit_btn( $settings ) {

		$migrated = isset( $settings['__fa4_migrated']['button_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		$this->add_render_attribute(
			'icon-align',
			[
				'class' => [
					'button-icon',
					'align-icon-' . $settings['button_icon_align'],
				],
			]
		);
		?>
		<div class="submit_button">
			<button type="submit">
				<?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['button_icon']['value'] ) ) : ?>
					<span <?php $this->print_render_attribute_string( 'icon-align' ); ?>>
				<?php if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] );
				else : ?>
					<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
				<?php endif; ?>
					</span>
				<?php endif; ?>
				<span class="button-text"><?php esc_html_e( $settings['button_text'] ); ?></span>
			</button>
		</div>
		<?php
	}
}

<?php

namespace AyyashAddonsPro\Widgets;

use AyyashAddonsPro\Ayyash_Pro_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'woocommerce' ) && ! class_exists( 'Pektsekye_Ymm' ) ) {
	return;
}

/**
 * @since 1.1.0
 */
class AyyashAddons_Style_Woocommerce_Product_Search extends Ayyash_Pro_Widget {

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
		return 'ayyash-woocommerce-product-search';
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
		return __( 'Product Search V2', 'ayyash-addons-pro' );
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

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-pro-woocommerce-product-search',
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

		$this->start_controls_section(
			'section_stype',
			array(
				'label' => esc_html__( 'Search Type', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'select_type',
			array(
				'label'   => esc_html__( 'Search Type', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'vertical'   => esc_html__( 'Vertical', 'ayyash-addons-pro' ),
					'horizontal' => esc_html__( 'Horizontal', 'ayyash-addons-pro' ),
				),
			)
		);

		$this->add_control(
			'select_garage',
			array(
				'label'   => esc_html__( 'Garage feature', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '0',
				'options' => array(
					'1' => esc_html__( 'Enable', 'ayyash-addons-pro' ),
					'0' => esc_html__( 'Disable', 'ayyash-addons-pro' ),
				),
			)
		);

		$this->add_control(
			'garage_alignment',
			[
				'label'     => esc_html__( 'Garage Alignment', 'ayyash-addons-pro' ),
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
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-garage' => 'text-align:{{value}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'background',
				'label'     => __( 'Background', 'ayyash-addons-pro' ),
				'types'     => array( 'classic', 'gradient', 'video' ),
				'selector'  => '{{WRAPPER}} .ayyash-addons-form-search--wrapper',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ayyash-addons-form-search--wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => __( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'top'    => 30,
					'right'  => 20,
					'left'   => 20,
					'bottom' => 30,
				),
				'selectors'  => array(
					'{{WRAPPER}} .ayyash-addons-form-search--wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_input',
			array(
				'label' => esc_html__( 'Input', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'input_alignment',
			[
				'label'     => esc_html__( 'Alignment', 'ayyash-addons-pro' ),
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
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .block-content' => 'text-align:{{value}}',
				],
			]
		);

		$this->add_responsive_control(
			'width_select',
			array(
				'label'          => __( 'Width', 'ayyash-addons-pro' ) . ' (px)',
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array(
						'min' => 150,
						'max' => 350,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box select.ymm-select'                                                                      => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content .ymm-extra .ymm-category-select'                                         => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content .ymm-extra .ymm-search table .ymm-td-input .input-text.ymm-search-field' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'input_color',
			array(
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#394e79',
				'selectors' => array(
					'{{WRAPPER}} .ayyash-addons-form-search--wrapper select' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'input_background',
				'label'    => __( 'Background', 'ayyash-addons-pro' ),
				'types'    => array( 'classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-form-search--wrapper select',
			)
		);

		$this->add_control(
			'input_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e7e7e7',
				'selectors' => array(
					'{{WRAPPER}} .ayyash-addons-form-search--wrapper select' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content select.ymm-select' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'input_border_radius',
			array(
				'label'      => __( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ayyash-addons-form-search--wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'input_padding',
			array(
				'label'      => __( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ayyash-addons-form-search--wrapper select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'input_margin',
			array(
				'label'      => __( 'Margin', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ayyash-addons-form-search--wrapper select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

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

		$this->add_responsive_control(
			'width_button',
			array(
				'label'          => __( 'Width', 'ayyash-addons-pro' ) . ' (px)',
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array(
						'min' => 150,
						'max' => 350,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .ymm-submit-any-selection'                                          => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content .ymm-extra .ymm-search table .ymm-td-button .button' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_TYPOGRAPHY::get_type(),
			array(
				'label'    => esc_html__( 'Typography', 'ayyash-addons-pro' ),
				'name'     => 'button_typo',
				'selector' => '{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content .ymm-submit-any-selection span',
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content .ymm-submit-any-selection span'                                => 'color: {{VALUE}}',
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content .ymm-extra .ymm-search table .ymm-td-button .button span span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fc5a34',
				'selectors' => array(
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content .ymm-submit-any-selection'                           => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content .ymm-extra .ymm-search table .ymm-td-button .button' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .ymm-submit-any-selection'                                          => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content .ymm-extra .ymm-search table .ymm-td-button .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'top'    => 10,
					'right'  => 50,
					'left'   => 50,
					'bottom' => 10,
				),
				'selectors'  => array(
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .ymm-submit-any-selection'                                          => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content .ymm-extra .ymm-search table .ymm-td-button .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
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
			array(
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fc5a34',
				'selectors' => array(
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content .ymm-submit-any-selection:hover span'                                => 'color: {{VALUE}}',
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content .ymm-extra .ymm-search table .ymm-td-button .button:hover span span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content .ymm-submit-any-selection:hover'                           => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .ayyash-addons-widget-search .ayyash-addons-form-search--wrapper .ayyash-addons-form-search-action .ymm-selector.ymm-box .block-content .ymm-extra .ymm-search table .ymm-td-button .button:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

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
		$garage   = $settings['select_garage'];
		?>
		<div class="ayyash-addons-widget-search ayyash-addons-form-search-product">
			<div class="ayyash-addons-form-search--wrapper" >
				<div class="ayyash-addons-form-search-action <?php echo esc_attr( $settings['select_type'] ); ?>">
					<?php echo do_shortcode( '[ymm_selector  garage = ' . $garage . ']' ); ?>
				</div>
			</div>
		</div>
		<?php

	}

}

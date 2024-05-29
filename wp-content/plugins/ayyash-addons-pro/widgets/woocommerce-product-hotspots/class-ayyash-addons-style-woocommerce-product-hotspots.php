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
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class AyyashAddons_Style_Woocommerce_Product_Hotspots extends Ayyash_Pro_Widget {

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
		return 'ayyash-woocommerce-product-hotspots';
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
		return __( 'Woocommerce Product Hotspots', 'ayyash-addons-pro' );
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
		return 'ayyash-addons eicon-hotspot';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-pro-woocommerce-product-hotspots',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'ayyash-addons-pro-woocommerce-product-hotspots',
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

		// section controller
		$this->start_controls_section(
			'general_section_settings',
			array(
				'label' => esc_html__( 'Hotspots Image', 'ayyash-addons-pro' ),
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

			return;
		}

		$this->add_control(
			'product_hotspot_image',
			[
				'label'   => esc_html__( 'Choose Image', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'product_hotspot_image_size',
				// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `testimonial_image_size` and `testimonial_image_custom_dimension`.
				'default' => 'full',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_section_settings',
			array(
				'label' => esc_html__( 'Items', 'ayyash-addons-pro' ),
			)
		);

		$repeater = new repeater();

		$repeater->start_controls_tabs( 'hotspot_tab' );

		$repeater->start_controls_tab(
			'content_tab',
			[
				'label' => esc_html__( 'Content', 'ayyash-addons-pro' ),
			]
		);

		$repeater->add_control(
			'product_hotspot_icon',
			[
				'label'   => __( 'Icon', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-plus-circle',
					'library' => 'solid',
				],
			]
		);

		$repeater->add_control(
			'product_hotspot_id',
			[
				'label'       => __( 'Product ID', 'ayyash-addons-pro' ),
				'description' => __( 'Enter a Product ID', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'woocommerce_hotspot_product_title_length',
			[
				'label' => esc_html__( 'Product Title Length', 'ayyash-addons-pro' ),
				'type'  => Controls_Manager::NUMBER,
			]
		);

		$repeater->add_control(
			'hotspot_dropdown_side',
			[
				'label'       => esc_html__( 'Dropdown side', 'ayyash-addons-pro' ),
				'description' => esc_html__( 'Show the content on left or right side, top or bottom.', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'left'   => esc_html__( 'Left', 'ayyash-addons-pro' ),
					'right'  => esc_html__( 'Right', 'ayyash-addons-pro' ),
					'top'    => esc_html__( 'Top', 'ayyash-addons-pro' ),
					'bottom' => esc_html__( 'Bottom', 'ayyash-addons-pro' ),
				],
				'default'     => 'left',
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'postition_tab',
			[
				'label' => esc_html__( 'Position', 'ayyash-addons-pro' ),
			]
		);

		$repeater->add_responsive_control(
			'hotspot_position_horizontal',
			[
				'label'     => esc_html__( 'Horizontal position (%)', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 50,
				],
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.ayyash-addons-product-hotspot__content' => 'left: {{SIZE}}%;',
				],
			]
		);

		$repeater->add_responsive_control(
			'hotspot_position_vertical',
			[
				'label'     => esc_html__( 'Vertical position (%)', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 50,
				],
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.ayyash-addons-product-hotspot__content' => 'top: {{SIZE}}%;',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'hotspots_list',
			[
				'label'  => __( 'Hotspots', 'ayyash-addons-pro' ),
				'type'   => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			]
		);

		$this->end_controls_section();

		//style controller
		$this->start_controls_section(
			'hotspot_style_section',
			[
				'label' => __( 'Hotspots Styles', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->__thumbnail();
		$this->__hotspot_icon();
		$this->__hover_action();

		$this->end_controls_section();
	}

	protected function __thumbnail() {
		$this->add_control(
			'hotspot_thumb_style',
			[
				'label'     => __( 'Image', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'iamge_width',
			[
				'label'      => esc_html__( 'Width', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-hotspot__image'   => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hotspot_thumb_border_radius',
			[
				'label'      => __( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default'    => [
					'unit'   => 'px',
					'top'    => 12,
					'right'  => 12,
					'bottom' => 12,
					'left'   => 12,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-hotspot__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	protected function __hotspot_icon() {
		$this->add_control(
			'hotspot_icon_style',
			[
				'label' => __( 'Icon', 'ayyash-addons-pro' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->start_controls_tabs( 'hotspot_color_tab' );
		//Normal state
		$this->start_controls_tab(
			'normal_style',
			[
				'label' => __( 'Normal', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'hotspot_icon_color_normal',
			[
				'label'     => __( 'Icon Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-hotspot-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		// Hover state
		$this->start_controls_tab(
			'hover_style',
			[
				'label' => __( 'Hover', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'hotspot_icon_color_hover',
			[
				'label'     => __( 'Icon Color Hover', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-hotspot-icon i:hover'   => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-addons-product-hotspot-icon svg:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-hotspot-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-addons-product-hotspot-icon svg' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	protected function __hover_action() {
		$this->add_control(
			'hotspot_hover_style',
			[
				'label'     => __( 'Action', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'action',
			[
				'label'       => esc_html__( 'Hotspot action', 'ayyash-addons-pro' ),
				'description' => esc_html__( 'Open hotspot content on click or hover', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'hover' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
					'click' => esc_html__( 'Click', 'ayyash-addons-pro' ),
				],
				'default'     => 'hover',
			]
		);
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

		if ( ! ayyash_addons_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}

		$is_preview = $this->is_preview_mode();
		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'product_hotspot_image_size', 'product_hotspot_image' );
		?>
		<div
			class="ayyash-addons-wrapper ayyash-addons-<?php echo str_replace( 'ayyash-', '', $this->get_name() ); // phpcs:ignore ?>">
			<div
				class="ayyash-addons-product-hotspot-wrapper hotspot-action-<?php echo esc_attr( $settings['action'] ); ?>">
				<div class="ayyash-addons-product-hotspot-wrapper-inside">
					<div class="ayyash-addons-product-hotspot__image"><?php echo wp_kses_post( $thumbnail_html ); ?></div>
					<?php
					foreach ( $settings['hotspots_list'] as $list ) {
						if ( empty ( $list['product_hotspot_icon'] ) ) {
							continue;
						}

						$product = wc_get_product( absint( $list['product_hotspot_id'] ) );

						if ( ! $product && ! $is_preview ) {
							continue;
						}
						?>
						<div class="ayyash-addons-product-hotspot__content elementor-repeater-item-<?php echo esc_attr( $list['_id'] ); ?>">
							<div class="ayyash-addons-product-hotspot-icon">
								<?php Icons_Manager::render_icon( $list['product_hotspot_icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</div>
							<div class="ayyash-addons-product-hotspot__hover hotspot-dropdown-<?php echo esc_attr( $list['hotspot_dropdown_side'] ); ?>">
								<?php $this->render_hotspot_product_hover( $product, $list ); ?>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}

	protected function render_hotspot_product_hover( $product, $list ) {
		if ( $product ) {
			?>
			<div class="ayyash-addons-hotspot-product-hover-image"><?php echo wp_kses_post( $product->get_image('springoo-hotspot-thumb') ); ?></div>
			<div class="ayyash-addons-hotspot-product-hover-content">
				<p><?php if ( empty( $list['woocommerce_hotspot_product_title_length'] ) ) {
						echo esc_html( $product->get_title() );
					} else {
						echo esc_html( wp_trim_words( $product->get_title(), $list['woocommerce_hotspot_product_title_length'], '' ) );
					} ?></p>
				<div class="ayyash-addons-hotspot-product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
			</div>
			<?php
		} else {
			// For Editor Preview.
			?>
			<div class="ayyash-addons-product-not-found">
				<h6><?php esc_html_e( 'No Product Found !', 'ayyash-addons-pro' ); ?></h6>
			</div>
			<?php
		}
	}
}

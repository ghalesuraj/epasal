<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Addons_Slider_Controller;
use AyyashAddons\Ayyash_Widget;
use AyyashAddonsPro\Widgets\AyyashAddons_Style_Woocommerce_Product;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Plugin;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 */
class AyyashAddons_Style_Product_Brand extends Ayyash_Widget {

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
		return 'ayyash-product-brand';
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
		return __( 'Product Brand', 'ayyash-addons' );
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
		return 'ayyash-addons eicon-product-meta';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-product-brand',
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
		return array( 'ayyash-widgets' );
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

		/**
		 * Fires after controllers are registered.
		 *
		 * @param AyyashAddons_Style_Woocommerce_Product $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );
		//query section
		$this->start_controls_section(
			'section_query',
			array(
				'label' => esc_html__( 'Query', 'ayyash-addons' ),
			)
		);
		if ( ! ayyash_addons_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->plugin_dependency_alert( [
				'plugins' => [
					[
						'path' => 'woocommerce/woocommerce.php',
						'name' => __( 'WooCommerce', 'ayyash-addons' ),
						'slug' => 'woocommerce',
					],
				],
			] );

			$this->end_controls_section();

			return;
		}
		if ( ! ayyash_addons_is_plugin_active( 'perfect-woocommerce-brands/perfect-woocommerce-brands.php' ) ) {
			$this->plugin_dependency_alert( [
				'plugins' => [
					[
						'path' => 'perfect-woocommerce-brands/perfect-woocommerce-brands.php',
						'name' => __( 'Perfect Brands for WooCommerce', 'ayyash-addons' ),
						'slug' => 'perfect-woocommerce-brands',
					],
				],
			] );

			$this->end_controls_section();

			return;
		}
		$this->add_control(
			'brands',
			[
				'label'   => esc_html__( 'Brands', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'all'    => esc_html__( 'All', 'ayyash-addons' ),
					'custom' => esc_html__( 'Custom', 'ayyash-addons' ),
				],
				'default' => 'all',
			]
		);
		$brands = $this->get_available_brands();
		$this->add_control(
			'custom_brands',
			[
				'label'       => esc_html__( 'Select Brands', 'ayyash-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $brands,
				'condition'   => [ 'brands' => 'custom' ],
			]
		);
		$this->add_control(
			'product_brand_number',
			[
				'label'     => esc_html__( 'Number', 'ayyash-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '',
				'condition' => [ 'brands' => 'all' ],
			]
		);
		$this->add_control(
			'product_brand_order',
			[
				'label'   => esc_html__( 'Order', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => [
					'ASC'  => esc_html__( 'ASC', 'ayyash-addons' ),
					'DESC' => esc_html__( 'DESC', 'ayyash-addons' ),
				],
			]
		);
		$this->add_control(
			'product_brand_orderby',
			[
				'label'   => esc_html__( 'Order By', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => [
					'name' => esc_html__( 'name', 'ayyash-addons' ),
					'ID'   => esc_html__( 'ID', 'ayyash-addons' ),
				],
			]
		);
		$this->end_controls_section();

		//settings controller sections
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'ayyash-addons' ),
			)
		);
		$this->add_control(
			'product_brand_slider',
			[
				'label'        => esc_html__( 'Slider', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'No', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_responsive_control(
			'product_brand_column',
			[
				'label'          => esc_html__( 'Columns', 'ayyash-addons' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'selectors'      => [
					'{{WRAPPER}} .ayyash-addons-column' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
				'condition'      => [
					'product_brand_slider!' => 'yes',
				],
			]

		);
		$this->add_responsive_control(
			'product_brand_column_gap',
			[
				'label'      => esc_html__( 'Gap', 'ayyash-addons' ),
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
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-brand-wrapper.custom-gap' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'product_brand_slider!' => 'yes',
				],
			]
		);
		$this->add_control(
			'wc_content_align',
			[
				'label'     => esc_html__( 'Content Alignment', 'ayyash-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
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
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-brand__content' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'content_left_right_padding',
			[
				'label'       => esc_html__( 'Content Left Right Padding', 'ayyash-addons' ),
				'description' => esc_html__( 'If you want to full width image use this setting for adjusting content padding', 'ayyash-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', '%' ],
				'range'       => [
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
				'separator'   => 'after',
				'default'     => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'   => [
					'{{WRAPPER}} .ayyash-addons-product-brand__content' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'show_brand_logo',
			[
				'label'        => esc_html__( 'Show Brand Logo', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'show_brand_banner',
			[
				'label'        => esc_html__( 'Show Brand Banner', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_responsive_control(
			'brand_banner_height',
			[
				'label'      => esc_html__( 'Banner height', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'default'    => [
					'size' => 180,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-brand__banner' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'show_brand_banner' => 'yes' ],
			]
		);
		$this->add_control(
			'show_title',
			[
				'label'        => esc_html__( 'Show Title', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'title_tag',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'ayyash-addons' ),
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
				'default'   => 'h3',
				'condition' => [ 'show_title' => 'yes' ],
			]
		);
		$this->add_control(
			'show_brand_count',
			[
				'label'        => esc_html__( 'Show Count', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'brand_count_text',
			[
				'label'       => esc_html__( 'Count Text', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Products', 'ayyash-addons' ),
				'placeholder' => esc_html__( 'Type your text here', 'ayyash-addons' ),
				'condition'   => [ 'show_brand_count' => 'yes' ],
			]
		);
		$this->end_controls_section();

		//style controller
		$this->__item_body_style();
		$this->__brand_logo_style();
		$this->__brand_title_style();
		$this->__brand_count_style();

		//slider
		$this->render_slider_controller( [
			'settings_section'     => [ 'condition' => [ 'product_brand_slider' => 'yes' ] ],
			'slider_style_section' => [ 'condition' => [ 'product_brand_slider' => 'yes' ] ],
		] );

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

	private function get_available_brands() {
		$categories = get_terms( [
			'taxonomy'     => 'pwb-brand',
			'show_count'   => 0,
			'pad_counts'   => 0,
			'hierarchical' => 1,
			'hide_empty'   => 0,
		] );

		$options = [];

		foreach ( $categories as $cat ) {
			$options[ $cat->term_id ] = $cat->name;
		}

		return $options;
	}

	public function ayyash_addons_taxonomy_thumbnail( $category, $thumbnail_key ) {
		$small_thumbnail_size = apply_filters( 'ayyash_taxonomy_thumbnail_size', 'woocommerce_thumbnail' );
		$dimensions           = wc_get_image_size( $small_thumbnail_size );
		$thumbnail_id         = get_term_meta( $category->term_id, $thumbnail_key, true );

		if ( $thumbnail_id ) {
			$image        = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size );
			$image        = $image[0];
			$image_srcset = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $thumbnail_id, $small_thumbnail_size ) : false;
			$image_sizes  = function_exists( 'wp_get_attachment_image_sizes' ) ? wp_get_attachment_image_sizes( $thumbnail_id, $small_thumbnail_size ) : false;
		} else {
			$image        = wc_placeholder_img_src();
			$image_srcset = false;
			$image_sizes  = false;
		}

		if ( $image ) {
			// Prevent esc_url from breaking spaces in urls for image embeds.
			// Ref: https://core.trac.wordpress.org/ticket/23605.
			$image = str_replace( ' ', '%20', $image );

			// Add responsive image markup if available.
			if ( $image_srcset && $image_sizes ) {
				echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" srcset="' . esc_attr( $image_srcset ) . '" sizes="' . esc_attr( $image_sizes ) . '" />';
			} else {
				echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
			}
		}
	}

	protected function __item_body_style() {
		$this->start_controls_section(
			'brand_item_body_settings',
			[
				'label' => esc_html__( 'Item Body', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'brand_item_section_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'wc_item_body_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'        => 'brand_item_body_background',
				'label'       => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block' => true,
				'types'       => [ 'classic', 'gradient' ],
				'selector'    => '{{WRAPPER}} .ayyash-addons-product-brand-item',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'brand_item_body_border',
				'label'          => esc_html__( 'Border', 'ayyash-addons' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-brand-item',
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
				'name'           => 'brand_item_body_box_shadow',
				'label'          => esc_html__( 'Box Shadow', 'ayyash-addons' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-brand-item',
				'fields_options' => [
					'box_shadow_type' =>
						[
							'default' => 'yes',
						],
					'box_shadow'      => [
						'default' =>
							[
								'horizontal' => 0,
								'vertical'   => 10,
								'blur'       => 10,
								'spread'     => 0,
								'color'      => 'rgba(22,22,22,0.03)',
							],
					],
				],
				'condition'      => [
					'product_brand_slider!' => 'yes',
				],
			]
		);
		$this->end_controls_tab();

		// Hover State Tab
		$this->start_controls_tab(
			'brand_item_body_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'brand_item_body_background_hover',
				'label'          => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block'    => true,
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Background',
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-brand-item:hover',
			]
		);
		$this->add_control(
			'brand_item_body_border_hover',
			[
				'label'     => esc_html__( 'Border Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-brand-item:hover' => 'border-color: {{VALUE}} !important;',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'           => 'brand_item_body_box_shadow_hover',
				'label'          => esc_html__( 'Box Shadow', 'ayyash-addons' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-brand-item:hover',
				'fields_options' => [
					'box_shadow_type' =>
						[
							'default' => 'yes',
						],
					'box_shadow'      => [
						'default' =>
							[
								'horizontal' => 0,
								'vertical'   => 10,
								'blur'       => 10,
								'spread'     => 0,
								'color'      => 'rgba(22,22,22,0.03)',
							],
					],
				],
				'condition'      => [
					'product_brand_slider!' => 'yes',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'brand_item_body_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons' ),
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
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-brand-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'brand_item_body_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'    => '10',
					'right'  => '10',
					'bottom' => '10',
					'left'   => '10',
					'unit'   => 'px',
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-brand-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}
	protected function __brand_logo_style() {
		$this->start_controls_section(
			'brand_logo_settings',
			[
				'label'     => esc_html__( 'Brand logo', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_brand_logo' => 'yes' ],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'brand_logo_background',
				'label'          => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block'    => true,
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Background',
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-brand__logo',
			]
		);
		$this->add_responsive_control(
			'brand_logo_size',
			[
				'label'      => esc_html__( 'Brand Size', 'ayyash-addons' ),
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
				'default'    => [
					'unit' => 'px',
					'size' => 80,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-brand__logo' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'brand_logo_border',
				'label'          => esc_html__( 'Border', 'ayyash-addons' ),
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
				'selector'       => '{{WRAPPER}} .ayyash-addons-product-brand__logo',
			]
		);
		$this->add_control(
			'brand_logo_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'    => '100',
					'right'  => '100',
					'bottom' => '100',
					'left'   => '100',
					'unit'   => 'px',
				],
				'size_units' => [ 'px', '%', 'em' ],
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-brand__logo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'brand_logo_position_x',
			[
				'label'      => esc_html__( 'Position X', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'default'    => [
					'size' => '39',
					'unit' => '%',
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-brand__logo' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'brand_logo_position_y',
			[
				'label'      => esc_html__( 'Position Y', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'default'    => [
					'size' => '135',
					'unit' => 'px',
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-brand__logo' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}
	protected function __brand_title_style() {
		$this->start_controls_section(
			'brand_title_settings',
			[
				'label'     => esc_html__( 'Brand Title', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_title' => 'yes' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'brand_title_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-product-brand__title a',
			]
		);
		$this->add_responsive_control(
			'brand_title_top_space',
			[
				'label'     => esc_html__( 'Top Spacing', 'ayyash-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '50',
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-brand__title' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'brand_title_bottom_space',
			[
				'label'     => esc_html__( 'Bottom Spacing', 'ayyash-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '10',
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-brand__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'brand_title_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'brand_title_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'brand_title_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-brand__title a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab(
			'brand_title_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'brand_title_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-brand__title a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}
	protected function __brand_count_style() {
		$this->start_controls_section(
			'brand_count_settings',
			[
				'label'     => esc_html__( 'Brand Count', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_brand_count' => 'yes' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'brand_count_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-product-brand__count ',
			]
		);

		$this->add_control(
			'brand_count_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-brand__count' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'brand_count_bottom_space',
			[
				'label'     => esc_html__( 'Spacing', 'ayyash-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '10',
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-brand__count' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render_product_brand_wrapper_header( $settings ) {
		?>
		<div <?php
		$this->print_render_attribute_string( 'ayyash_addons_wrap' );

		if ( 'yes' === $settings['product_brand_slider'] ) {
			$this->print_render_attribute_string( 'ayyash_addons_slider' );
			$this->print_slider_inline_css( $settings );
		}
		?>>

		<?php if ( 'yes' === $settings['product_brand_slider'] ) { ?>
			<div class="swiper-wrapper">
		<?php }
	}
	protected function render_product_brand_wrapper_footer( $settings ) {
		if ( 'yes' === $settings['product_brand_slider'] ) { ?>
			</div>
		<?php } ?>

		</div>
		<?php

		if ( 'yes' === $settings['product_brand_slider'] ) {
			$this->slider_nav( $settings );
		}
	}
	protected function render_product_brand( $settings, $product_brand ) {
		$this->render_product_brand_item_header();
		$this->render_brand_banner( $settings, $product_brand );
		$this->render_brand_logo( $settings, $product_brand );
		$this->render_content_header();
		$this->render_title( $settings, $product_brand );
		$this->render_brand_count( $settings, $product_brand);
		$this->render_content_footer();
		$this->render_product_brand_item_footer();
	}

	protected function render_product_brand_item_header() {
		?><div  <?php $this->print_render_attribute_string( 'ayyash_addons_item' ); ?> ><?php
	}

	protected function render_product_brand_item_footer() {
		?></div><?php
	}

	protected function render_brand_logo( $settings, $product_brand ) {
		if ( 'yes' !== $settings['show_brand_logo'] ) {
			return;
		}
		?>
		<div class="ayyash-addons-product-brand__logo">
			<?php $this->ayyash_addons_taxonomy_thumbnail( $product_brand, 'pwb_brand_image' ); ?>
		</div>
		<?php
	}

	protected function render_brand_banner( $settings, $product_brand ) {
		if ( 'yes' !== $settings['show_brand_banner'] ) {
			return;
		}
		?>
		<div class="ayyash-addons-product-brand__banner">
			<?php $this->ayyash_addons_taxonomy_thumbnail( $product_brand, 'pwb_brand_banner' ); ?>
		</div>
		<?php
	}

	protected function render_content_header() {
		?><div class="ayyash-addons-product-brand__content"><?php
	}

	protected function render_content_footer() {
		?></div><?php
	}

	protected function render_title( $settings, $product_brand ) {
		if ( 'yes' !== $settings['show_title'] ) {
			return;
		}
		$tag       = $settings['title_tag'];
		$term_link = get_term_link( $product_brand->term_id );
		?>
		<<?php Utils::print_validated_html_tag( $tag ); ?> class="ayyash-addons-product-brand__title">
		<?php if ( ! is_wp_error( $term_link ) || ! empty( $term_link ) ) { ?>
			<a href="<?php echo esc_url( $term_link ); ?>">
				<?php echo esc_html( $product_brand->name ); ?>
			</a>
		<?php } ?>
		</<?php Utils::print_validated_html_tag( $tag ); ?>>
		<?php
	}

	protected function render_brand_count( $settings, $product_brand ) {
		if ( 'yes' !== $settings['show_brand_count'] ) {
			return;
		}
		$count_text = $settings['brand_count_text'];
		printf(
			'<p class="ayyash-addons-product-brand__count">(%1$s %2$s)</p>',
			esc_html( $product_brand->count ),
			esc_html( $count_text )
		);
	}

	protected function render_brand_sales_count( $settings, $product_brand ) {
		if ( 'yes' !== $settings['show_sales_count'] ) {
			return;
		}
		printf(
			'<p class="ayyash-addons-product-brand-sales__count">(%1$s %2$s)</p>',
			esc_html( $product_brand->count ),
			esc_html__( 'Sales', 'ayyash-addons' )
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

		$orderby = $settings['product_brand_orderby'];

		$term_taxonomy_ids = '';
		if ( $settings['custom_brands'] ) {
			$term_taxonomy_ids = $settings['custom_brands'];
		}

		$args           = array(
			'number'           => $settings['product_brand_number'],
			'taxonomy'         => 'pwb-brand',
			'term_taxonomy_id' => $term_taxonomy_ids,
			'orderby'          => $orderby,
			'order'            => $settings['product_brand_order'],
			'show_count'       => 0,
			'pad_counts'       => 0,
			'hierarchical'     => 1,
			'title_li'         => '',
			'hide_empty'       => 0,
		);
		$product_brands = get_terms( $args );

		$swiper_class = Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';


		$this->add_render_attribute( [ 'ayyash_addons_slider' => $this->get_slider_attributes( $settings ) ] );
		$wrapper_class = 'ayyash-addons-product-brand-wrapper';
		$item_class    = 'ayyash-addons-product-brand-item';

		if ( 'yes' === $settings['product_brand_slider'] ) {
			$item_class    .= ' swiper-slide';
			$wrapper_class .= ' ayyash-addons-swiper-wrapper swiper-container ' . $swiper_class;
		} else {
			$wrapper_class .= ' ayyash-addons-column ' . 'custom-gap';
		}

		$this->add_render_attribute( [
			'ayyash_addons_wrap' => [
				'class' => $wrapper_class,
			],
		] );
		$this->add_render_attribute( [
			'ayyash_addons_item' => [
				'class' => $item_class,
			],
		] );

		if ( ! ayyash_addons_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}
		if ( ! ayyash_addons_is_plugin_active( 'perfect-woocommerce-brands/perfect-woocommerce-brands.php' ) ) {
			return;
		}
		?>
		<div
			class="ayyash-addons-wrapper ayyash-addons-<?php echo str_replace( 'ayyash-', '', $this->get_name() ); // phpcs:ignore ?>">
			<?php
			$this->render_product_brand_wrapper_header( $settings );
			foreach ( $product_brands as $product_brand ) {
				$this->render_product_brand( $settings, $product_brand );
			}
			$this->render_product_brand_wrapper_footer( $settings );
			?>
		</div>
		<?php
	}
}

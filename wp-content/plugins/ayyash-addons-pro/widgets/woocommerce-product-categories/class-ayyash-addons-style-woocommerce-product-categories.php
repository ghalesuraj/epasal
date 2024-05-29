<?php

namespace AyyashAddonsPro\Widgets;

use AyyashAddons\Ayyash_Addons_Slider_Controller;
use AyyashAddonsPro\Ayyash_Pro_Widget;
use Elementor\Controls_Manager;
use AyyashAddons\Controls\Ayyash_Addons_Query_Builder;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use WP_Query;
use YITH_WCQV_Frontend;
use YITH_WCWL_Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class AyyashAddons_Style_Woocommerce_Product_Categories extends Ayyash_Pro_Widget {

	use Ayyash_Addons_Slider_Controller;

	protected $wishlist_add_label;

	protected $wishlist_remove_label;

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
		return 'ayyash-woocommerce-product-categories';
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
		return __( 'Product Categories', 'ayyash-addons-pro' );
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
		return 'ayyash-addons eicon-product-categories';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-pro-woocommerce-product-cat',
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

	private function get_available_top_categories() {
		$categories = get_categories( [
			'taxonomy'     => 'product_cat',
			'show_count'   => 0,
			'pad_counts'   => 0,
			'hierarchical' => 1,
			'parent'       => 0,
			'hide_empty'   => 0,
		] );

		$options = [];

		foreach ( $categories as $cat ) {
			$options[ $cat->slug ] = $cat->name;
		}

		return $options;
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


		$this->start_controls_section(
			'woocommerce_product_title_section',
			[
				'label' => __( 'Section Header', 'ayyash-addons-pro' ),
			]
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

			$this->end_controls_section();

			return;
		}

		$this->add_control(
			'show_header',
			[
				'label'        => esc_html__( 'Show Header', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons-pro' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'widget_title',
			[
				'label'       => esc_html__( 'Section Title', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => esc_html__( 'Top Categories', 'ayyash-addons-pro' ),
				'placeholder' => esc_html__( 'Type your title', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'show_title_separator',
			[
				'label'        => esc_html__( 'Separator', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons-pro' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'show_sub_title',
			[
				'label'        => esc_html__( 'Show Sub Title', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons-pro' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'show_button',
			[
				'label'        => esc_html__( 'Show Button', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'ayyash-addons-pro'),
				'label_off'    => esc_html__('Hide', 'ayyash-addons-pro'),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);
		$this->add_control(
			'show_btn_text',
			[
				'label'       => esc_html__( 'Button Text', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'View All', 'ayyash-addons-pro' ),
				'placeholder' => esc_html__( 'Type your title here', 'ayyash-addons-pro' ),
				'condition'   => [ 'show_button' => 'yes' ],
			]
		);
		$this->add_control(
			'show_btn_link',
			[
				'label'       => esc_html__( 'Button Link', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'ayyash-addons-pro' ),
				'options'     => [ 'url', 'is_external', 'nofollow' ],
				'default'     => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'label_block' => true,
				'condition'   => [ 'show_button' => 'yes' ],
			]
		);
		$this->end_controls_section();

		//query section
		$this->start_controls_section(
			'section_query',
			array(
				'label' => esc_html__( 'Query', 'ayyash-addons-pro' ),
			)
		);
		$this->add_control(
			'categories',
			[
				'label'   => esc_html__( 'Categories', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'all'    => esc_html__( 'All', 'ayyash-addons-pro' ),
					'custom' => esc_html__( 'Custom', 'ayyash-addons-pro' ),
				],
				'default' => 'all',
			]
		);
		$cats = $this->get_available_top_categories();
		$this->add_control(
			'custom_categories',
			[
				'label'       => esc_html__( 'Select Categories', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $cats,
				'condition'   => [ 'categories' => 'custom' ],
			]
		);
		$this->add_control(
			'wc_cat_number',
			[
				'label'     => esc_html__( 'Number', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '',
				'condition' => [ 'categories' => 'all' ],
			]
		);
		$this->add_control(
			'wc_cat_order',
			[
				'label'   => esc_html__( 'Order', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => [
					'ASC'  => esc_html__( 'ASC', 'ayyash-addons-pro' ),
					'DESC' => esc_html__( 'DESC', 'ayyash-addons-pro' ),
				],
			]
		);
		$this->add_control(
			'wc_cat_orderby',
			[
				'label'   => esc_html__( 'Order By', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => [
					'name' => esc_html__( 'name', 'ayyash-addons-pro' ),
					'ID'   => esc_html__( 'ID', 'ayyash-addons-pro' ),
				],
			]
		);
		$this->end_controls_section();

		//settings controller sections
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'ayyash-addons-pro' ),
			)
		);
		$this->add_control(
			'wc_cat_slider',
			[
				'label'        => esc_html__( 'Slider', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ayyash-addons-pro' ),
				'label_off'    => esc_html__( 'No', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'wc_cat_column',
			[
				'label'     => esc_html__( 'Grid Column', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4',
				'options'   => [
					'1' => esc_html__( '1', 'ayyash-addons-pro' ),
					'2' => esc_html__( '2', 'ayyash-addons-pro' ),
					'3' => esc_html__( '3', 'ayyash-addons-pro' ),
					'4' => esc_html__( '4', 'ayyash-addons-pro' ),
					'5' => esc_html__( '5', 'ayyash-addons-pro' ),
					'6' => esc_html__( '6', 'ayyash-addons-pro' ),
				],
				'condition' => [
					'wc_cat_slider!' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'wc_cat_column_gap',
			[
				'label'      => esc_html__( 'Gap', 'ayyash-addons-pro' ),
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
					'{{WRAPPER}} .ayyash-addons-wc-cat-wrapper.custom-gap' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'wc_cat_slider!' => 'yes',
				],
			]
		);
		$this->add_control(
			'wc_content_align',
			[
				'label'     => esc_html__( 'Content Alignment', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'content-left',
				'separator' => 'after',
				'options'   => [
					'content-left'   => [
						'title' => esc_html__( 'Left', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'content-center' => [
						'title' => esc_html__( 'Center', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'content-right'  => [
						'title' => esc_html__( 'Right', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
			]
		);
		$this->add_control(
			'show_thumb',
			[
				'label'        => esc_html__( 'Show Thumbnail', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons-pro' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_responsive_control(
			'wc_cat_thumb_size',
			[
				'label'      => esc_html__( 'Thumbnail Size', 'ayyash-addons-pro' ),
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
					'{{WRAPPER}} .ayyash-addons-wc-cat__thumbnail' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'show_thumb' => 'yes',
				],
			]
		);
		$this->add_control(
			'show_title',
			[
				'label'        => esc_html__( 'Show Title', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons-pro' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'show_category_count',
			[
				'label'        => esc_html__( 'Show Count', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons-pro' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'category_count_text',
			[
				'label'       => esc_html__( 'Count Text', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Items', 'ayyash-addons-pro' ),
				'placeholder' => esc_html__( 'Type your text here', 'ayyash-addons-pro' ),
				'condition'   => [ 'show_category_count' => 'yes' ],
			]
		);
		$this->add_control(
			'show_subcategory',
			[
				'label'        => esc_html__( 'Show Subcategory', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons-pro' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'show_view_all',
			[
				'label'        => esc_html__( 'Show View All', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons-pro' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'view_btn_text',
			[
				'label'       => esc_html__( 'Button Text', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'View All', 'ayyash-addons-pro' ),
				'placeholder' => esc_html__( 'Type your title here', 'ayyash-addons-pro' ),
				'condition'   => [ 'show_view_all' => 'yes' ],
			]
		);
		$this->add_control(
			'view_icon',
			[
				'label'     => esc_html__( 'Icon', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-long-arrow-alt-right',
					'library' => 'solid',
				],
				'condition' => [ 'show_view_all' => 'yes' ],
			]
		);
		$this->end_controls_section();

		//style controller
		$this->__section_header();
		$this->__item_body_style();
		$this->__thumbnail();
		$this->__title();
		$this->__category_count();
		$this->__sub_category();
		$this->__view_all_btn();

		//slider
		$this->render_slider_controller( [
			'settings_section'     => [ 'condition' => [ 'wc_cat_slider' => 'yes' ] ],
			'slider_style_section' => [ 'condition' => [ 'wc_cat_slider' => 'yes' ] ],
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

	protected function __section_header() {
		$this->start_controls_section(
			'wc_section_header_settings',
			[
				'label'     => esc_html__( 'Section Header', 'ayyash-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_header' => 'yes' ],
			]
		);
		$this->add_responsive_control(
			'wc_product_thumbnail_height',
			[
				'label'      => esc_html__( 'Spacing', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => '30',
					'unit' => 'px',
				],
				'size_units' => [ 'px', 'rem', 'em' ],
				'range'      => [
					'px'  => [
						'min' => 1,
						'max' => 100,
					],
					'rem' => [
						'min' => 1,
						'max' => 10,
					],
					'em'  => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'wc_heading_title',
			[
				'label' => esc_html__( 'Title', 'ayyash-addons-pro' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'wc_section_heading_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-heading-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_section_heading_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-heading-title',
			]
		);
		$this->add_control(
			'wc_separator_title',
			[
				'label'     => esc_html__( 'Separator', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'wc_section_separator_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .has-section-separator:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wc_button_title',
			[
				'label'     => esc_html__( 'Button', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'wc_btn_top_space',
			[
				'label'     => esc_html__( 'Top Spacing', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'   => [
					'size' => '20',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .header-view-all_btn' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( '_btn_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'wc_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'wc_btn_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#372D2C',
				'selectors' => [
					'{{WRAPPER}} .header-view-all_btn' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_btn_typography',
				'selector' => '{{WRAPPER}} .header-view-all_btn',
			]
		);
		$this->add_control(
			'wc_btn_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FCF6F6',
				'selectors' => [
					'{{WRAPPER}} .header-view-all_btn' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'wc_btn_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'separator'  => 'before',
				'default'    => [
					'top'      => '14',
					'right'    => '30',
					'bottom'   => '14',
					'left'     => '30',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .header-view-all_btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'wc_btn_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'    => '100',
					'right'  => '100',
					'bottom' => '100',
					'left'   => '100',
					'unit'   => 'px',
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .header-view-all_btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab(
			'wc_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'wc_btn_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .header-view-all_btn:hover'     => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'wc_btn_bg_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FCF6F6',
				'selectors' => [
					'{{WRAPPER}} .header-view-all_btn:hover'     => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function __item_body_style() {
		//Product body
		$this->start_controls_section(
			'wc_item_body_settings',
			[
				'label' => esc_html__( 'Item Body', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'wc_item_section_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'wc_item_body_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'        => 'wc_item_body_background',
				'label'       => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'label_block' => true,
				'types'       => [ 'classic', 'gradient' ],
				'selector'    => '{{WRAPPER}} .ayyash-addons-wc-cat-item',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'wc_item_body_border',
				'label'          => esc_html__( 'Border', 'ayyash-addons-pro' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-wc-cat-item',
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
				'name'           => 'wc_item_body_box_shadow',
				'label'          => esc_html__( 'Box Shadow', 'ayyash-addons-pro' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-wc-cat-item',
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
					'wc_cat_slider!' => 'yes',
				],
			]
		);
		$this->end_controls_tab();

		// Hover State Tab
		$this->start_controls_tab(
			'wc_item_body_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'wc_item_body_background_hover',
				'label'          => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'label_block'    => true,
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Background',
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-wc-cat-item:hover',
			]
		);
		$this->add_control(
			'wc_item_body_border_hover',
			[
				'label'     => esc_html__( 'Border Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat-item:hover' => 'border-color: {{VALUE}} !important;',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'           => 'wc_item_body_box_shadow_hover',
				'label'          => esc_html__( 'Box Shadow', 'ayyash-addons-pro' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-wc-cat-item:hover',
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
					'wc_cat_slider!' => 'yes',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'wc_item_body_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'separator'  => 'before',
				'default'    => [
					'top'      => '15',
					'right'    => '15',
					'bottom'   => '15',
					'left'     => '15',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-wc-cat-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'wc_item_body_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
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
					'{{WRAPPER}} .ayyash-addons-wc-cat-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function __thumbnail() {
		$this->start_controls_section(
			'wc_thumbnail_settings',
			[
				'label'     => esc_html__( 'Thumbnail', 'ayyash-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_thumb' => 'yes' ],
			]
		);
		$this->add_control(
			'show_thumb_scale',
			[
				'label'        => esc_html__( 'Zoom Image ?', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ayyash-addons-pro' ),
				'label_off'    => esc_html__( 'Hide', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);
		$this->add_control(
			'wc_thumbnail_scale',
			[
				'label'     => esc_html__( 'Scale', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 2,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__thumbnail img' => 'transform: scale({{SIZE}})',
				],
				'condition' => [ 'show_thumb_scale' => 'yes' ],
			]
		);
		$this->start_controls_tabs( 'wc_thumbnail_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'wc_thumbnail_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'wc_thumbnail_background',
				'label'          => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'label_block'    => true,
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Background',
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-wc-cat__thumbnail',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'wc_thumbnail_border',
				'label'          => esc_html__( 'Border', 'ayyash-addons-pro' ),
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
				'selector'       => '{{WRAPPER}} .ayyash-addons-wc-cat__thumbnail',
			]
		);
		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab(
			'wc_thumbnail_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'wc_thumbnail_background_hover',
				'label'          => esc_html__( 'Background', 'ayyash-addons-pro' ),
				'label_block'    => true,
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Background',
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-wc-cat__thumbnail:hover',
			]
		);
		$this->add_control(
			'wc_thumbnail_border_hover',
			[
				'label'     => esc_html__( 'Border Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__thumbnail:hover' => 'border-color: {{VALUE}} !important;',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'wc_thumbnail_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'    => '10',
					'right'  => '10',
					'bottom' => '10',
					'left'   => '10',
					'unit'   => 'px',
				],
				'size_units' => [ 'px', '%', 'em' ],
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'wc_thumbnail_bottom_space',
			[
				'label'     => esc_html__( 'Spacing', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '15',
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function __title() {
		$this->start_controls_section(
			'wc_title_settings',
			[
				'label'     => esc_html__( 'Title', 'ayyash-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_title' => 'yes' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_title_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-wc-cat__title',
			]
		);
		$this->add_responsive_control(
			'wc_title_bottom_space',
			[
				'label'     => esc_html__( 'Spacing', 'ayyash-addons-pro' ),
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
					'{{WRAPPER}} .ayyash-addons-wc-cat__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'wc_title_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'wc_title_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'wc_title_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__title a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab(
			'wc_title_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'wc_title_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__title a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function __category_count() {
		$this->start_controls_section(
			'wc_cat_count_settings',
			[
				'label'     => esc_html__( 'Category Count', 'ayyash-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_category_count' => 'yes' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_category_count_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-wc-cat__count',
			]
		);
		$this->add_control(
			'wc_cat_count_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__count' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'wc_cat_count_bottom_space',
			[
				'label'     => esc_html__( 'Spacing', 'ayyash-addons-pro' ),
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
					'{{WRAPPER}} .ayyash-addons-wc-cat__count' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function __sub_category() {
		$this->start_controls_section(
			'wc_sub_category_settings',
			[
				'label'     => esc_html__( 'Sub Category', 'ayyash-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_subcategory' => 'yes' ],
			]
		);
		$this->add_control(
			'sub_cat_icon',
			[
				'label'            => __( 'Icon', 'ayyash-addons-pro' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'fa4compatibility' => 'ayyash-addons',
				'default'          => [
					'value'   => 'far fa-dot-circle',
					'library' => 'solid',
				],
				'skin'             => 'inline',
			]
		);
		$this->add_responsive_control(
			'sub_cat_icon_size',
			[
				'label'     => esc_html__( 'Icon Size', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'   => [
					'size' => '10',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__sub-categories li a i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-addons-wc-cat__sub-categories li a svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'sub_cat_icon_right_space',
			[
				'label'     => esc_html__( 'Icon Spacing', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'   => [
					'size' => '5',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__sub-categories li a i'   => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-addons-wc-cat__sub-categories li a svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'wc_sub_category_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'wc_sub_category_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'wc_sub_category_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#5E626D',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__sub-categories a, .ayyash-addons-wc-cat__sub-categories li' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_sub_category_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-wc-cat__sub-categories a, .ayyash-addons-wc-cat__sub-categories li',
			]
		);
		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab(
			'wc_sub_category_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'wc_sub_category_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__sub-categories a:hover, .ayyash-addons-wc-cat__sub-categories li:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'wc_sub_category_bottom_space',
			[
				'label'     => esc_html__( 'Spacing', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'   => [
					'size' => '5',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__sub-categories li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function __view_all_btn() {
		$this->start_controls_section(
			'wc_view_btn_settings',
			[
				'label'     => esc_html__( 'View Button', 'ayyash-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_view_all' => 'yes' ],
			]
		);
		$this->add_responsive_control(
			'wc_view_btn_top_space',
			[
				'label'     => esc_html__( 'Top Spacing', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'   => [
					'size' => '5',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__view-btn' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'wc_view_btn_icon_space',
			[
				'label'     => esc_html__( 'Icon Spacing', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__view-btn' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'wc_view_btn_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'wc_view_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'wc_view_btn_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#5E626D',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__view-btn'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-addons-wc-cat__view-btn svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_view_btn_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-wc-cat__view-btn',
			]
		);
		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab(
			'wc_view_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'wc_view_btn_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-wc-cat__view-btn:hover'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-addons-wc-cat__view-btn:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function render_widget_header( $settings ) {

		if ( 'yes' !== $settings['show_header'] ) {
			return;
		}
		?>
		<div
			class="ayyash-addons-header<?php echo ( 'yes' == $settings['show_title_separator'] ) ? ' has-section-separator' : '' ?>">
			<?php if ( $settings['widget_title'] ) : ?>
				<h2 class="ayyash-addons-heading-title"><?php echo esc_html( $settings['widget_title'] ); ?></h2>
			<?php endif; ?>

			<?php
			if ( 'yes' === $settings ['show_sub_title'] ) {
				$products = new WP_Query( [
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'posts_per_page' => - 1,
				] );

				printf(
					'<div class="total"><span>%1$s categories</span> belonging to a total of <span>%2$s Products</span></div>',
					esc_html( count( get_categories( [
						'taxonomy'   => 'product_cat',
						'hide_empty' => 0,
					] ) ) ),
					esc_html( $products->found_posts )
				);
			}
			?>

			<?php
			if ( 'yes' === $settings['show_button'] ) { ?>
				<a href="<?php echo esc_url( $settings['show_btn_link']['url'] ); ?>" class="header-view-all_btn">
					<?php echo esc_html( $settings['show_btn_text'] ); ?>
				</a>
				<?php
			}
			?>

		</div>
		<?php
	}

	protected function render_category_wrapper_header( $settings ) {
		?>
		<div <?php
		$this->print_render_attribute_string( 'ayyash_addons_wrap' );
		if ( 'yes' === $settings['wc_cat_slider'] ) {
			$this->print_render_attribute_string( 'ayyash_addons_slider' );
			$this->print_slider_inline_css( $settings );
		}
		?>>
		<?php if ( 'yes' === $settings['wc_cat_slider'] ) { ?>
			<div class="swiper-wrapper">
		<?php }
	}

	protected function render_category_wrapper_footer( $settings ) {
		if ( 'yes' === $settings['wc_cat_slider'] ) { ?>
			</div>
		<?php } ?>

		</div>
		<?php

		if ( 'yes' === $settings['wc_cat_slider'] ) {
			$this->slider_nav( $settings );
		}
	}

	protected function render_category( $settings, $category, $orderby ) {
		$this->render_category_item_header();
		$this->render_thumbnail( $settings, $category );
		$this->render_content_header();
		$this->render_title( $settings, $category );
		$this->render_category_count( $settings, $category );
		$this->render_sub_categories( $settings, $category, $orderby );
		$this->render_view_all( $settings, $category );
		$this->render_content_footer();
		$this->render_category_item_footer();
	}

	protected function render_category_item_header() {

		?><div  <?php $this->print_render_attribute_string( 'ayyash_addons_item' ); ?> ><?php
	}

	protected function render_category_item_footer() {
		?></div><?php
	}

	protected function render_thumbnail( $settings, $category ) {
		if ( 'yes' !== $settings['show_thumb'] ) {
			return;
		}
		?>
		<div class="ayyash-addons-wc-cat__thumbnail">
			<a class="ayyash-addons-wc-cat-thumbnail__link"
			   href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
				<?php woocommerce_subcategory_thumbnail( $category ); ?>
			</a>
		</div>
		<?php
	}

	protected function render_content_header() {
		?><div class="ayyash-addons-wc-cat__content"><?php
	}

	protected function render_content_footer() {
		?></div><?php
	}

	protected function render_title( $settings, $category ) {

		if ( 'yes' !== $settings['show_title'] ) {
			return;
		}
		printf(
			'<h3 class="ayyash-addons-wc-cat__title"><a href="%1$s">%2$s</a></h3>',
			esc_url( get_category_link( $category->term_id ) ),
			esc_html( $category->name )
		);
	}

	protected function render_category_count( $settings, $category ) {
		if ( 'yes' !== $settings['show_category_count'] ) {
			return;
		}
		if ( empty( $settings['category_count_text'] ) ) {
			printf(
				'<p class="ayyash-addons-wc-cat__count">%1$s</p>',
				esc_html( $category->category_count )
			);
		} else {
			printf(
				'<p class="ayyash-addons-wc-cat__count">(%1$s %2$s)</p>',
				esc_html( $category->category_count ),
				esc_html__( $settings['category_count_text'], 'ayyash-addons-pro' )

			);
		}
	}

	protected function render_sub_categories( $settings, $category, $orderby ) {
		if ( 'yes' !== $settings['show_subcategory'] ) {
			return;
		}
		$args2    = array(
			'taxonomy'     => 'product_cat',
			'child_of'     => 0,
			'parent'       => $category->term_id,
			'orderby'      => $orderby,
			'show_count'   => 0,
			'pad_counts'   => 0,
			'hierarchical' => 1,
			'title_li'     => '',
			'hide_empty'   => 0,
		);
		$sub_cats = get_categories( $args2 );

		if ( empty( $sub_cats ) ) {
			return;
		}
		?>
		<ul class="ayyash-addons-wc-cat__sub-categories">
			<?php foreach ( $sub_cats as $sub_cat ) { ?>
				<li>
					<a href="<?php echo esc_url( get_category_link( $sub_cat->term_id ) ); ?>">
						<?php
						Icons_Manager::render_icon( $settings['sub_cat_icon'], [ 'aria-hidden' => 'true' ] );
						echo esc_html( $sub_cat->name );
						?>
					</a>
				</li>
			<?php } ?>
		</ul>
		<?php
	}

	protected function render_view_all( $settings, $category ) {
		if ( 'yes' !== $settings['show_view_all'] ) {
			return;
		}
		?>
		<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
		   class="ayyash-addons-wc-cat__view-btn">
			<?php
			echo esc_html( $settings['view_btn_text'] );
			Icons_Manager::render_icon( $settings['view_icon'], [ 'aria-hidden' => 'true' ] );
			?>
		</a>
		<?php
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

		$orderby = $settings['wc_cat_orderby'];

		$term_taxonomy_slug = '';
		if ( $settings['custom_categories'] ) {
			$term_taxonomy_slug = $settings['custom_categories'];
		}

		$args                 = array(
			'number'       => $settings['wc_cat_number'],
			'taxonomy'     => 'product_cat',
			'slug'         => $term_taxonomy_slug,
			'orderby'      => $orderby,
			'order'        => $settings['wc_cat_order'],
			'show_count'   => 0,
			'pad_counts'   => 0,
			'hierarchical' => 1,
			'title_li'     => '',
			'parent'       => 0, //Get only top level categories
			'hide_empty'   => 0,
		);
		$top_label_categories = get_categories( $args );

		$swiper_class = Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';


		$this->add_render_attribute( [ 'ayyash_addons_slider' => $this->get_slider_attributes( $settings ) ] );
		$wrapper_class = 'ayyash-addons-wc-cat-wrapper';
		$item_class    = 'ayyash-addons-wc-cat-item' . ' ' . $settings['wc_content_align'];

		if ( 'yes' === $settings['wc_cat_slider'] ) {
			$item_class    .= ' swiper-slide';
			$wrapper_class .= ' ayyash-addons-swiper-wrapper ' . $swiper_class;
		} else {
			$wrapper_class .= ' ayyash-addons-column-' . $settings['wc_cat_column'] . ' ' . 'custom-gap';
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
		?>
		<div
			class="ayyash-addons-wrapper ayyash-addons-<?php echo str_replace( 'ayyash-', '', $this->get_name() ); // phpcs:ignore ?>">
			<?php
			$this->render_widget_header( $settings );

			$this->render_category_wrapper_header( $settings );
			foreach ( $top_label_categories as $category ) {
				$this->render_category( $settings, $category, $orderby );

			}

			$this->render_category_wrapper_footer( $settings );

			?>
		</div>
		<?php
	}

}

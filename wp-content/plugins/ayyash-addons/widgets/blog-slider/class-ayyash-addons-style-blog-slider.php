<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Addons_Slider_Controller;
use AyyashAddons\Ayyash_Widget;
use AyyashAddons\Controls\Ayyash_Addons_Query_Builder;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Utils;
use Exception;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 */
class AyyashAddons_Style_Blog_Slider extends Ayyash_Widget {

	use Ayyash_Addons_Slider_Controller;

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
		return 'ayyash-blog-slider';
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
		return __( 'Blog Slider', 'ayyash-addons' );
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
		return 'ayyash-addons eicon-nested-carousel';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-blog-slider',
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

	public function get_keywords() {
		return [ 'Carousel', 'Blog carousel', 'Blog', 'blog-slider', 'blog slider' ];
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
		//query
		$this->start_controls_section(
			'post_query',
			array(
				'label' => esc_html__( 'Content Source', 'ayyash-addons' ),
			)
		);
		$this->add_control( 'query_builder', [
			'label'   => __( 'Source', 'ayyash-addons' ),
			'type'    => 'ayyash_addons_query_builder',
			'default' => [
				'post_type'         => 'post',
				'post_per_page'     => '4',
				'disable_post_type' => true,
			],
		] );
		$this->end_controls_section();

		//content
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Content', 'ayyash-addons' ),
			)
		);

		$this->add_control(
			'show_thumb',
			[
				'label'        => __( 'Show Thumbnail', 'ayyash-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'ayyash-addons' ),
				'label_off'    => __( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'        => __( 'Show Title', 'ayyash-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'ayyash-addons' ),
				'label_off'    => __( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label'        => __( 'Show Excerpt', 'ayyash-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'ayyash-addons' ),
				'label_off'    => __( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_meta',
			[
				'label'        => __( 'Show Meta', 'ayyash-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'ayyash-addons' ),
				'label_off'    => __( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_read_more',
			[
				'label'        => __( 'Show Read More', 'ayyash-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'ayyash-addons' ),
				'label_off'    => __( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label'       => __( 'Read More Text', 'ayyash-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Read More', 'ayyash-addons' ),
				'placeholder' => __( 'Type your title here', 'ayyash-addons' ),
				'condition'   => [
					'show_read_more' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_author',
			[
				'label'        => __( 'Show Author', 'ayyash-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'ayyash-addons' ),
				'label_off'    => __( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_date',
			[
				'label'        => __( 'Show Date', 'ayyash-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'ayyash-addons' ),
				'label_off'    => __( 'Hide', 'ayyash-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

		//title
		$this->start_controls_section( 'title_area', [
			'label'     => __( 'Title Settings', 'ayyash-addons' ),
			'condition' => [ 'show_title' => 'yes' ],
		] );
		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'Title HTML Tag', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
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
				'default' => 'h3',
			]
		);
		$this->end_controls_section();

		//thumbnail
		$this->start_controls_section( 'thumb_area', [
			'label'     => __( 'Thumbnail Settings', 'ayyash-addons' ),
			'condition' => [ 'show_thumb' => 'yes' ],
		] );
		$this->add_control(
			'thumbnail',
			[
				'label'        => esc_html__( 'Image Position', 'ayyash-addons' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'top',
				'options'      => [
					'top'   => esc_html__( 'Top', 'ayyash-addons' ),
					'left'  => esc_html__( 'Left', 'ayyash-addons' ),
					'right' => esc_html__( 'Right', 'ayyash-addons' ),
				],
				'prefix_class' => 'ayyash-addons-posts--thumbnail-',
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'         => 'thumbnail_size',
				'default'      => 'medium',
				'exclude'      => [ 'custom' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'prefix_class' => 'ayyash-addons-posts--thumbnail-size-',
			]
		);
		$this->add_responsive_control(
			'item_ratio',
			[
				'label'          => esc_html__( 'Image Ratio', 'ayyash-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'size' => 0.66,
				],
				'tablet_default' => [
					'size' => '',
				],
				'mobile_default' => [
					'size' => 0.5,
				],
				'range'          => [
					'px' => [
						'min'  => 0.1,
						'max'  => 2,
						'step' => 0.01,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .ayyash-addons-post__thumbnail__link ' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				],
			]
		);
		$this->add_responsive_control(
			'image_width',
			[
				'label'          => esc_html__( 'Image Width', 'ayyash-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => [
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 600,
					],
				],
				'default'        => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'size_units'     => [ '%', 'px' ],
				'selectors'      => [
					'{{WRAPPER}} .ayyash-addons-post__thumbnail' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		//excerpt
		$this->start_controls_section( 'excerpt_area', [
			'label'     => __( 'Excerpt Settings', 'ayyash-addons' ),
			'condition' => [ 'show_excerpt' => 'yes' ],
		] );
		$this->add_control(
			'excerpt_length',
			[
				'label'   => esc_html__( 'Excerpt Length', 'ayyash-addons' ),
				'type'    => Controls_Manager::NUMBER,
				/** This filter is documented in wp-includes/formatting.php */
				'default' => apply_filters( 'excerpt_length', 25 ),
			]
		);
		$this->end_controls_section();
		$this->render_slider_controller( [
			'vertical_slider' => false,
			'navigation'      => 'arrow',
		] );

		$this->__title_style();
		$this->__excerpt_style();
		$this->__read_more_btn();
		$this->__meta_data_style();
		$this->__nav_style();
	}

	// title style controls
	protected function __title_style() {
		$this->start_controls_section(
			'title_style',
			[
				'label'     => esc_html__( 'Title', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_title' => 'yes' ],
			]
		);
		$this->start_controls_tabs( 'title_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'title_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => __( 'Typography', 'ayyash-addons' ),
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-post__title a',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#161616',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-post__title a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab(
			'title_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'title_color_hover',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#08A3EE',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-post__title a:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'title_top_space',
			[
				'label'     => esc_html__( 'Top Spacing', 'ayyash-addons' ),
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
					'{{WRAPPER}} .ayyash-addons-post__title ' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_bottom_space',
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
					'{{WRAPPER}} .ayyash-addons-post__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	// excerpt style controls
	protected function __excerpt_style() {
		$this->start_controls_section(
			'excerpt_style',
			[
				'label'     => esc_html__( 'Excerpt', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_excerpt' => 'yes' ],
			]
		);
		$this->add_control(
			'excerpt_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-post__excerpt p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => __( 'Typography', 'ayyash-addons' ),
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-post__excerpt p',
			]
		);
		$this->add_responsive_control(
			'excerpt_top_space',
			[
				'label'     => esc_html__( 'Top Spacing', 'ayyash-addons' ),
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
					'{{WRAPPER}} .ayyash-addons-post__excerpt p' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'excerpt_bottom_space',
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
					'{{WRAPPER}} .ayyash-addons-post__excerpt p' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	// read more style controls
	protected function __read_more_btn() {
		$this->start_controls_section(
			'read_more_style',
			[
				'label'     => esc_html__( 'Read More', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_read_more' => 'yes' ],
			]
		);
		$this->start_controls_tabs( 'read_more_tabs' );
		$this->add_control(
			'readmore_icon',
			[
				'label'            => __( 'Icon', 'ayyash-addons' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'fa4compatibility' => 'ayyash-addons',
				'default'          => [
					'value'   => 'fas fa-angle-right',
					'library' => 'solid',
				],
				'skin'             => 'inline',
			]
		);
		// Normal State Tab
		$this->start_controls_tab(
			'read_more_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'        => 'readmore_background',
				'label'       => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block' => true,
				'types'       => [ 'classic', 'gradient' ],
				'selector'    => '{{WRAPPER}} .ayyash-addons-post__read-more',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'read_more_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-post__read-more',
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#064af3',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-post__read-more'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-addons-post__read-more svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'readmore_border',
				'label'    => esc_html__( 'Border', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-post__read-more',
			]
		);

		$this->add_control(
			'readmore_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-post__read-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-post__read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab(
			'rea_more_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'        => 'readmore_background_hover',
				'label'       => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block' => true,
				'types'       => [ 'classic', 'gradient' ],
				'selector'    => '{{WRAPPER}} .ayyash-addons-post__read-more:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'readmore_border_hover',
				'label'    => esc_html__( 'Border', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-post__read-more:hover',
			]
		);
		$this->add_control(
			'read_more_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-post__read-more:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ayyash-addons-post__read-more svg'   => 'fill: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	// meta data style controls
	protected function __meta_data_style() {
		$this->start_controls_section(
			'meta_data_style',
			[
				'label'      => esc_html__( 'Meta Data', 'ayyash-addons' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'show_thumb',
							'operator' => '===',
							'value'    => 'yes',
						],
						[
							'name'     => 'show_title',
							'operator' => '===',
							'value'    => 'yes',
						],
						[
							'name'     => 'show_excerpt',
							'operator' => '===',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		//Date
		$this->add_control(
			'date_heading',
			[
				'label'     => esc_html__( 'Date', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'date_prefix',
			[
				'label'   => esc_html__( 'Prefix', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'none' => esc_html__( 'None', 'ayyash-addons' ),
					'text' => esc_html__( 'Text', 'ayyash-addons' ),
					'icon' => esc_html__( 'Icon', 'ayyash-addons' ),
				],
			]
		);
		$this->add_control(
			'date_prefix_text',
			[
				'label'       => esc_html__( 'Prefix Text', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Post Date:', 'ayyash-addons' ),
				'placeholder' => esc_html__( 'Type your title here', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'date_icon',
			[
				'label'            => __( 'Icon', 'ayyash-addons' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'fa4compatibility' => 'ayyash-addons',
				'default'          => [
					'value'   => 'far fa-calendar-alt',
					'library' => 'solid',
				],
				'skin'             => 'inline',
			]
		);
		$this->add_control(
			'date_prefix_color',
			[
				'label'     => esc_html__( 'Prefix Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .meta_prefix_text.date_prefix_text, {{WRAPPER}} .ayyash-addons-post-date i' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'date_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#5E626D',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-post-date' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'        => 'date_background',
				'label'       => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block' => true,
				'types'       => [ 'classic', 'gradient' ],
				'selector'    => '{{WRAPPER}} .ayyash-addons-post__meta.thumb_meta .ayyash-addons-post-date',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-post-date',
			]
		);

		//Category
		$this->add_control(
			'category_heading',
			[
				'label'     => esc_html__( 'Category', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'category_icon',
			[
				'label'            => __( 'Icon', 'ayyash-addons' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'fa4compatibility' => 'ayyash-addons',
				'default'          => [
					'value'   => 'far fa-flag',
					'library' => 'solid',
				],
				'skin'             => 'inline',
			]
		);
		$this->start_controls_tabs( 'category_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'category_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_control(
			'category_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#064af3',
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-post-category a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'        => 'category_background',
				'label'       => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block' => true,
				'types'       => [ 'classic', 'gradient' ],
				'selector'    => '{{WRAPPER}} .ayyash-addons-post__meta.thumb_meta .ayyash-addons-post-category',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'category_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-post-category',
			]
		);
		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab(
			'category_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'        => 'category_background_hover',
				'label'       => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block' => true,
				'types'       => [ 'classic', 'gradient' ],
				'selector'    => '{{WRAPPER}} .ayyash-addons-post__meta.thumb_meta .ayyash-addons-post-category:hover',
			]
		);
		$this->add_control(
			'category_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-post-category a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'category_border',
				'label'    => esc_html__( 'Border', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-post-category',
			]
		);

		$this->add_control(
			'category_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-post-category' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __nav_style() {
		$this->start_controls_section(
			'blog-slider-style-section', [
				'label' => __( 'Nav Style', 'ayyash-addons' ),
				"tab"   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'blog-slider-left-arrow',
			[
				'label'      => __( 'Left Arrow', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .elementor-swiper-button-prev.ayyash-addons-nav-prev' => 'left: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'blog-slider-right-arrow',
			[
				'label'      => __( 'Right Arrow', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .elementor-swiper-button-next.ayyash-addons-nav-next' => 'right: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_section();
	}


	protected function render_post_header() {
		?>
		<article <?php post_class( [ 'ayyash-addons-blog-item ayyash-addons-blog-post-item' ] ); ?>>
		<?php
	}

	protected function render_post_footer() {
		?>
		</article>
		<?php
	}

	protected function render_content_header() {
		?>
		<div class="ayyash-addons-blog-item-content">
		<?php
		$this->render_category();
	}

	protected function render_content_footer() {
		?>
		</div>
		<?php
	}

	protected function render_thumbnail( $settings ) {
		if ( 'yes' !== $settings['show_thumb'] ) {
			return;
		}

		if ( 'none' === $settings['thumbnail'] && ! Plugin::instance()->editor->is_edit_mode() ) {
			return;
		}

		$setting_key              = 'thumbnail_size';
		$settings[ $setting_key ] = [
			'id' => get_post_thumbnail_id(),
		];

		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );
		if ( empty( $thumbnail_html ) ) {
			?>
			<img class="placeholder-image" src="<?php ayyash_addons_plugin_url( 'assets/images/placeholder.png' ); ?>" alt="<?php esc_attr_e( 'Placeholder Image', 'ayyash-addons' ); ?>">
			<?php
		} else {
			?>
			<div class="ayyash-addons-blog-item-image">
				<a class="ayyash-addons-blog-item-link" href="<?php echo esc_url( $this->current_permalink ); ?>">
					<?php echo wp_kses_post( $thumbnail_html ); ?>
				</a>
			</div>
			<?php
		}
	}

	protected function render_title( $settings ) {
		if ( 'yes' !== $settings['show_title'] ) {
			return;
		}

		$tag = $settings['title_tag'];

		?>
		<<?php Utils::print_validated_html_tag( $tag ); ?> class="ayyash-addons-post__title">
		<a href="<?php echo esc_url( $this->current_permalink ); ?>">
			<?php the_title(); ?>
		</a>
		</<?php Utils::print_validated_html_tag( $tag ); ?>>
		<?php
	}

	protected function render_excerpt( $settings ) {

		add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
		add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );

		if ( 'yes' !== $settings['show_excerpt'] ) {
			return;
		}

		add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
		add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );


		?>
		<div class="ayyash-addons-post__excerpt">
			<?php
			global $post;

			// Force the manually-generated Excerpt length as well if the user chose to enable 'apply_to_custom_excerpt'.
			if ( empty( $post->post_excerpt ) ) {
				$max_length = (int) $settings['excerpt_length'];
				$excerpt    = apply_filters( 'the_excerpt', get_the_excerpt() );
				$excerpt    = $this->trim_words( $excerpt, $max_length );
				echo wp_kses_post( $excerpt );
			} else {
				the_excerpt();
			}
			?>
		</div>
		<?php


		remove_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
		remove_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
	}

	protected function render_read_more( $settings ) {
		$settings = $this->get_settings_for_display();

		if ( 'yes' !== $settings['show_read_more'] ) {
			return;
		}

		$read_more = $settings['read_more_text'];
		?>
		<a class="ayyash-addons-post__read-more blog-slider" href="<?php echo esc_url( $this->current_permalink ); ?>">
			<?php echo wp_kses_post( $read_more ); ?>
			<?php Icons_Manager::render_icon( $settings['readmore_icon'], [ 'aria-hidden' => 'true' ] ); ?>
		</a>
		<?php
	}

	protected function render_author() {
		?>
		<span class="ayyash-addons-post__author">
			<?php $this->render_author_prefix(); ?>
			<?php echo wp_kses_post( get_the_author_posts_link() ); ?>
		</span>
		<?php
	}


	protected function render_author_prefix() {
		$settings = $this->get_settings_for_display();
		if ( 'none' === $settings['author_prefix'] ) {
			return;
		}
		if ( 'text' === $settings['author_prefix'] ) {
			?>
			<span class="meta_prefix_text author_prefix_text">
				<?php echo esc_html( $settings['author_prefix_text'] ); ?>
			</span>
			<?php
		} else {
			?>
			<span class="ayyash-author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 30, '', get_the_author_meta( 'display_name' ) ); ?>
			</span>
			<?php
		}
	}

	protected function render_date( $type = 'publish' ) {
		$settings = $this->get_settings_for_display();
		?>
		<span class="ayyash-addons-blog-item-date">
			<?php
			if ( 'icon' === $settings['date_prefix'] ) {
				Icons_Manager::render_icon( $settings['date_icon'], [ 'aria-hidden' => 'true' ] );
			} elseif ( 'text' === $settings['date_prefix'] ) {
				?>
				<span class="meta_prefix_text date_prefix_text">
					<?php echo esc_html( $settings['date_prefix_text'] ) ?>
				</span>
				<?php
			}
			?>

			<?php
			switch ( $type ) :
				case 'modified':
					$date = get_the_modified_date();
					break;
				default:
					$date = get_the_date();
			endswitch;
			/** This filter is documented in wp-includes/general-template.php */
			// PHPCS - The date is safe.
			echo apply_filters( 'the_date', $date, get_option( 'date_format' ), '', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</span>
		<?php
	}

	protected function render_category() {
		$settings = $this->get_settings_for_display();
		$terms    = get_the_terms( get_the_ID(), 'category' );
		if ( empty( $terms[0] ) ) {
			return;
		}

		// Get the ID of a given category
		$category_id = get_cat_ID( $terms[0]->name );
		// Get the URL of this category
		$category_link = get_category_link( $category_id );
		?>
		<span class="ayyash-addons-post-category blog-slider">
			<a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo esc_attr( $terms[0]->name ); ?>">
				<?php Icons_Manager::render_icon( $settings['category_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				<?php echo esc_html( $terms[0]->name ); ?>
			</a>
		</span>
		<?php
	}

	protected function render_tag() {
		$settings = $this->get_settings_for_display();
		$terms    = get_the_terms( get_the_ID(), 'post_tag' );
		if ( empty( $terms[0] ) ) {
			return;
		}

		// Get the URL of this category
		$tag_link = get_tag_link( $terms[0] );
		?>
		<span class="ayyash-addons-post-tag">
			<a href="<?php echo esc_url( $tag_link ); ?>" title="<?php echo esc_attr( $terms[0]->name ); ?>">
				<?php Icons_Manager::render_icon( $settings['tag_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				<?php echo esc_html( $terms[0]->name ); ?>
			</a>
		</span>
		<?php
	}

	protected function render_post( $settings ) {
		$this->render_post_header( $settings );
		$this->render_thumbnail( $settings );
		$this->render_date();
		$this->render_content_header();
		$this->render_title( $settings );
		$this->render_excerpt( $settings );
		$this->render_read_more( $settings );
		$this->render_content_footer();
		$this->render_post_footer( $settings );
	}

	public function filter_excerpt_length() {
		$settings = $this->get_settings_for_display();

		return (int) $settings['excerpt_length'];
	}

	public function filter_excerpt_more() {
		return '';
	}

	/**
	 * Remove words from a sentence.
	 *
	 * @param string $text
	 * @param integer $length
	 *
	 * @return string
	 */
	public static function trim_words( $text, $length ) {
		if ( $length && str_word_count( $text ) > $length ) {
			$text = explode( ' ', $text, $length + 1 );
			unset( $text[ $length ] );
			$text = implode( ' ', $text );
		}

		return $text;
	}

	/**
	 * @throws Exception
	 */
	protected function render() {

		$settings     = $this->get_settings_for_display();
		$swiper_class = Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';

		$this->add_render_attribute( [
			'ayyash_addons_blog_slider' => [
				'class' => 'ayyash-addons-swiper-wrapper ' . $swiper_class,
			],
		] );

		$this->add_render_attribute( [ 'ayyash_addons_blog_slider' => $this->get_slider_attributes( $settings ) ] );

		$args  = Ayyash_Addons_Query_Builder::build_query( $settings['query_builder'] );
		$posts = new \WP_Query( $args );
		?>
		<div class="ayyash-addons-blog-slider">
			<div <?php $this->print_render_attribute_string( 'ayyash_addons_blog_slider' ); ?>>
				<div class="swiper-wrapper">
					<?php if ( $posts->have_posts() ) { ?>

						<?php while ( $posts->have_posts() ) {
							$posts->the_post();
							$this->current_permalink = get_permalink();
							?>
							<div class="swiper-slide">
								<div class="ayyash-addons-blog-slider-item">
									<?php $this->render_post( $settings ); ?>
								</div>
							</div>
						<?php }
					} else {
						_e( 'Sorry, no posts were found.', 'ayyash-addons' );
					} ?>
				</div>
			</div>
		</div>
		<!--Slider Nav-->
		<?php $this->slider_nav(); ?>
		<?php
		wp_reset_postdata();
	}

}

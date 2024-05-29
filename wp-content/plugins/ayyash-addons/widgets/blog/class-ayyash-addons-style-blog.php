<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Widget;
use AyyashAddons\Controls\Ayyash_Addons_Query_Builder;
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
class AyyashAddons_Style_Blog extends Ayyash_Widget {

	/**
	 * @var string Save current permalink to avoid conflict with plugins the filters the permalink during the post render.
	 */
	protected $current_permalink;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ayyash-blog';
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
		return __( 'Blog', 'ayyash-addons' );
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
		return 'ayyash-addons eicon-post-list';
	}

	public function get_keywords() {
		return [ 'blog', 'post' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-blog',
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

		//Layout
		$this->start_controls_section(
			'layout',
			[ 'label' => esc_html__( 'Layout', 'ayyash-addons' ) ]
		);
		$this->add_control( 'show_thumb', [
			'label'   => __( 'Show Thumbnail', 'ayyash-addons' ),
			'default' => 'yes',
			'type'    => Controls_Manager::SWITCHER,
		] );
		$this->add_control( 'show_title', [
			'label'   => __( 'Show Title', 'ayyash-addons' ),
			'default' => 'yes',
			'type'    => Controls_Manager::SWITCHER,
		] );
		$this->add_control( 'show_excerpt', [
			'label'   => __( 'Show Excerpt', 'ayyash-addons' ),
			'default' => 'yes',
			'type'    => Controls_Manager::SWITCHER,
		] );
		$this->add_control( 'show_read_more', [
			'label'   => __( 'Show Read More', 'ayyash-addons' ),
			'default' => 'yes',
			'type'    => Controls_Manager::SWITCHER,
		] );
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
		$this->__register_meta_data_controls(
			'thumb',
			[
				'metas'          => [
					'author'   => __( 'Author', 'ayyash-addons' ),
					'date'     => __( 'Date', 'ayyash-addons' ),
					'comment'  => __( 'Comment', 'ayyash-addons' ),
					'category' => __( 'Category', 'ayyash-addons' ),
					'tag'      => __( 'Tag', 'ayyash-addons' ),
				],
				'meta_placement' => [
					'after_blog_item_thumbnail'   => __( 'After Thumbnail', 'ayyash-addons' ),
					'before_blog_item_thumbnail'  => __( 'Before Thumbnail', 'ayyash-addons' ),
					'overlay_blog_item_thumbnail' => __( 'Overlay Thumbnail', 'ayyash-addons' ),
				],
				'separator'      => '',
			],
			[
				'metas'          => [ 'category' ],
				'meta_placement' => 'overlay_blog_item_thumbnail',
				'separator'      => '',
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
		$this->__register_meta_data_controls(
			'title',
			[
				'metas'          => [
					'author'   => __( 'Author', 'ayyash-addons' ),
					'date'     => __( 'Date', 'ayyash-addons' ),
					'comment'  => __( 'Comment', 'ayyash-addons' ),
					'category' => __( 'Category', 'ayyash-addons' ),
					'tag'      => __( 'Tag', 'ayyash-addons' ),
				],
				'meta_placement' => [
					'after_blog_item_title'  => __( 'After Title', 'ayyash-addons' ),
					'before_blog_item_title' => __( 'Before Title', 'ayyash-addons' ),
				],
				'separator'      => '///',
			],
			[
				'metas'          => [ 'date', 'comment' ],
				'meta_placement' => 'before_blog_item_title',
				'separator'      => '///',
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
		$this->__register_meta_data_controls(
			'excerpt',
			[
				'metas'          => [
					'author'   => __( 'Author', 'ayyash-addons' ),
					'date'     => __( 'Date', 'ayyash-addons' ),
					'comment'  => __( 'Comment', 'ayyash-addons' ),
					'category' => __( 'Category', 'ayyash-addons' ),
					'tag'      => __( 'Tag', 'ayyash-addons' ),
				],
				'meta_placement' => [
					'after_blog_item_excerpt'  => __( 'After Excerpt', 'ayyash-addons' ),
					'before_blog_item_excerpt' => __( 'Before Excerpt', 'ayyash-addons' ),
				],
				'separator'      => '///',
			],
			[
				'metas'          => [],
				'meta_placement' => 'after_blog_item_excerpt',
				'separator'      => '///',
			]
		);
		$this->end_controls_section();

		//read more
		$this->start_controls_section( 'read_more_area', [
			'label'     => __( 'Read More Settings', 'ayyash-addons' ),
			'condition' => [ 'show_read_more' => 'yes' ],
		] );
		$this->add_control(
			'read_more_text',
			[
				'label'   => esc_html__( 'Read More Text', 'ayyash-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More »', 'ayyash-addons' ),
			]
		);
		$this->end_controls_section();

		//style controller
		$this->__layout_style();
		$this->__item_body_style();
		$this->__feature_thumbnail_style();
		$this->__title_style();
		$this->__excerpt_style();
		$this->__read_more_btn();
		$this->__meta_data_style();

	}

	//meta data controls
	protected function __register_meta_data_controls( string $prefix, array $options = [], array $defaults = [] ) {
		$options  = wp_parse_args( $options, [
			'metas'          => [],
			'meta_placement' => [],
			'separator'      => '',
		] );
		$defaults = wp_parse_args( $defaults, [
			'metas'          => [],
			'meta_placement' => '',
			'separator'      => '',
		] );
		$this->add_control(
			$prefix . '_metas',
			[
				'label'       => __( 'Meta Data', 'ayyash-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $options['metas'],
				'default'     => $defaults['metas'],
				'separator'   => 'before',
			]
		);
		$this->add_control(
			$prefix . '_meta_placement',
			[
				'label'       => __( 'Meta Placement', 'ayyash-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'default'     => $defaults['meta_placement'],
				'options'     => $options['meta_placement'],
				'condition'   => [ $prefix . '_metas!' => [] ],
			]
		);
		$this->add_control(
			$prefix . '_separator',
			[
				'label'     => __( 'Separator', 'ayyash-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $defaults['separator'],
				'selectors' => [
					'{{WRAPPER}}' . ' .ayyash-addons-post__meta.' . $prefix . '_meta' . ' span + span:before' => 'content: "{{VALUE}}"',
				],
				'condition' => [ $prefix . '_metas!' => [] ],
			]
		);
	}

	// layout style controls
	protected function __layout_style() {
		$this->start_controls_section(
			'layout_style',
			[
				'label' => esc_html__( 'Layout', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'columns',
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
			]
		);
		$this->add_responsive_control(
			'column_gap',
			[
				'label'     => esc_html__( 'Columns Gap', 'ayyash-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 20,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .custom-gap' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_responsive_control(
			'row_gap',
			[
				'label'     => esc_html__( 'Rows Gap', 'ayyash-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 20,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .custom-gap' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_control(
			'alignment',
			[
				'label'     => esc_html__( 'Alignment', 'ayyash-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
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
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-post-item' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
	}

	// body style controls
	protected function __item_body_style() {
		$this->start_controls_section(
			'item_body_style',
			[
				'label' => esc_html__( 'Item Body', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'item_section_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'item_body_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'        => 'item_body_background',
				'label'       => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block' => true,
				'types'       => [ 'classic', 'gradient' ],
				'selector'    => '{{WRAPPER}} .ayyash-addons-post-item',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'item_body_border',
				'label'          => esc_html__( 'Border', 'ayyash-addons' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-post-item',
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
				'name'           => 'item_body_box_shadow',
				'label'          => esc_html__( 'Box Shadow', 'ayyash-addons' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-post-item',
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
			]
		);
		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab(
			'item_body_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'item_body_background_hover',
				'label'          => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block'    => true,
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Background',
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-post-item:hover',
			]
		);
		$this->add_control(
			'item_body_border_hover',
			[
				'label'     => esc_html__( 'Border Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-post-item:hover' => 'border-color: {{VALUE}} !important;',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'           => 'item_body_box_shadow_hover',
				'label'          => esc_html__( 'Box Shadow', 'ayyash-addons' ),
				'selector'       => '{{WRAPPER}} .ayyash-addons-post-item:hover',
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
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'item_body_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons' ),
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
					'{{WRAPPER}} .ayyash-addons-post-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'item_body_border_radius',
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
					'{{WRAPPER}} .ayyash-addons-post-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	// feature img style controls
	protected function __feature_thumbnail_style() {
		$this->start_controls_section(
			'thumbnail_style',
			[
				'label'     => esc_html__( 'Thumbnail', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_thumb' => 'yes' ],
			]
		);
		$this->start_controls_tabs( 'thumbnail_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'thumbnail_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'thumbnail_background',
				'label'          => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block'    => true,
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Background',
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-post__thumbnail__link',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'thumbnail_border',
				'label'          => esc_html__( 'tBorder', 'ayyash-addons' ),
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
				'selector'       => '{{WRAPPER}} .ayyash-addons-post__thumbnail__link',
			]
		);
		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab(
			'thumbnail_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'thumbnail_background_hover',
				'label'          => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block'    => true,
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Background',
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-post__thumbnail__link:hover',
			]
		);
		$this->add_control(
			'thumbnail_border_hover',
			[
				'label'     => esc_html__( 'Border Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-post__thumbnail__link:hover' => 'border-color: {{VALUE}} !important;',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'thumbnail_border_radius',
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
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-post__thumbnail__link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'thumbnail_bottom_space',
			[
				'label'     => esc_html__( 'Spacing', 'ayyash-addons' ),
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
					'{{WRAPPER}} .ayyash-addons-post-item' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
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
		//General
		$this->add_control(
			'general_heading',
			[
				'label'     => esc_html__( 'Common settings for thumbnail metas', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [ 'thumb_meta_placement' => 'overlay_blog_item_thumbnail' ],
			]
		);
		$this->add_control(
			'meta_position_x',
			[
				'label'       => esc_html__( 'Position X', 'ayyash-addons' ),
				'description' => esc_html__( 'All General setting will work only for overlay thumbnail metas', 'ayyash-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', '%' ],
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'     => [
					'unit' => '%',
					'size' => 0,
				],
				'selectors'   => [
					'{{WRAPPER}} .ayyash-addons-post__meta.thumb_meta' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [ 'thumb_meta_placement' => 'overlay_blog_item_thumbnail' ],
			]
		);
		$this->add_control(
			'meta_position_y',
			[
				'label'       => esc_html__( 'Position Y', 'ayyash-addons' ),
				'description' => esc_html__( 'All General setting will work only for overlay thumbnail metas', 'ayyash-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', '%' ],
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'     => [
					'unit' => '%',
					'size' => 0,
				],
				'selectors'   => [
					'{{WRAPPER}} .ayyash-addons-post__meta.thumb_meta' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [ 'thumb_meta_placement' => 'overlay_blog_item_thumbnail' ],
			]
		);
		$this->add_responsive_control(
			'metas_padding',
			[
				'label'       => esc_html__( 'Padding', 'ayyash-addons' ),
				'description' => esc_html__( 'All General setting will work only for overlay thumbnail metas', 'ayyash-addons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'default'     => [
					'top'      => '4',
					'right'    => '8',
					'bottom'   => '4',
					'left'     => '8',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'   => [
					'{{WRAPPER}} .ayyash-addons-post__meta.thumb_meta > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'   => [ 'thumb_meta_placement' => 'overlay_blog_item_thumbnail' ],
			]
		);
		$this->add_control(
			'metas_border_radius',
			[
				'label'       => esc_html__( 'Border Radius', 'ayyash-addons' ),
				'description' => esc_html__( 'All General setting will work only for overlay thumbnail metas', 'ayyash-addons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'default'     => [
					'top'    => '10',
					'right'  => '10',
					'bottom' => '10',
					'left'   => '10',
					'unit'   => 'px',
				],
				'size_units'  => [ 'px', '%', 'em' ],
				'selectors'   => [
					'{{WRAPPER}} .ayyash-addons-post__meta.thumb_meta > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'   => [ 'thumb_meta_placement' => 'overlay_blog_item_thumbnail' ],
			]
		);

		//Author
		$this->add_control(
			'author_heading',
			[
				'label'      => esc_html__( 'Author', 'ayyash-addons' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
					],
				],
			]
		);
		$this->add_control(
			'author_prefix',
			[
				'label'      => esc_html__( 'Prefix', 'ayyash-addons' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'author_avatar',
				'options'    => [
					'none'          => esc_html__( 'None', 'ayyash-addons' ),
					'text'          => esc_html__( 'Text', 'ayyash-addons' ),
					'author_avatar' => esc_html__( 'Author Avatar', 'ayyash-addons' ),
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
					],
				],
			]
		);
		$this->add_control(
			'author_prefix_text',
			[
				'label'       => esc_html__( 'Prefix Text', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Author:', 'ayyash-addons' ),
				'placeholder' => esc_html__( 'Type your title here', 'ayyash-addons' ),
				'condition'   => [ 'author_prefix' => 'text' ],
			]
		);
		$this->add_control(
			'author_prefix_text_color',
			[
				'label'     => esc_html__( 'Prefix Text Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .meta_prefix_text.author_prefix_text' => 'color: {{VALUE}}',
				],
				'condition' => [ 'author_prefix' => 'text' ],
			]
		);
		$this->start_controls_tabs( 'author_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'author_normal',
			[
				'label'      => esc_html__( 'Normal', 'ayyash-addons' ),
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'        => 'author_background',
				'label'       => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block' => true,
				'types'       => [ 'classic', 'gradient' ],
				'selector'    => '{{WRAPPER}} .ayyash-addons-post__meta.thumb_meta .ayyash-addons-post__author',
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'thumb_meta_placement',
							'operator' => '===',
							'value'    => 'overlay_blog_item_thumbnail',
						],

					],
				],
			]
		);
		$this->add_control(
			'author_color',
			[
				'label'      => esc_html__( 'Color', 'ayyash-addons' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#064af3',
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-post__author a' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'       => 'author_typography',
				'selector'   => '{{WRAPPER}} .ayyash-addons-post__author',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
					],
				],
			]
		);
		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab(
			'author_hover',
			[
				'label'      => esc_html__( 'Hover', 'ayyash-addons' ),
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
					],
				],
			]
		);
		$this->add_control(
			'author_color_hover',
			[
				'label'      => esc_html__( 'Color', 'ayyash-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-post__author a:hover' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'        => 'author_background_hover',
				'label'       => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block' => true,
				'types'       => [ 'classic', 'gradient' ],
				'selector'    => '{{WRAPPER}} .ayyash-addons-post__meta.thumb_meta .ayyash-addons-post__author:hover',
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'author',
						],
						[
							'name'     => 'thumb_meta_placement',
							'operator' => '===',
							'value'    => 'overlay_blog_item_thumbnail',
						],

					],
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		//Date
		$this->add_control(
			'date_heading',
			[
				'label'      => esc_html__( 'Date', 'ayyash-addons' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'after',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
					],
				],
			]
		);
		$this->add_control(
			'date_prefix',
			[
				'label'      => esc_html__( 'Prefix', 'ayyash-addons' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'icon',
				'options'    => [
					'none' => esc_html__( 'None', 'ayyash-addons' ),
					'text' => esc_html__( 'Text', 'ayyash-addons' ),
					'icon' => esc_html__( 'Icon', 'ayyash-addons' ),
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
					],
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
				'conditions'  => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
					],
				],
				'condition'   => [ 'date_prefix' => 'text' ],
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
				'conditions'       => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
					],
				],
				'condition'        => [ 'date_prefix' => 'icon' ],
			]
		);
		$this->add_control(
			'date_prefix_color',
			[
				'label'      => esc_html__( 'Prefix Color', 'ayyash-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .meta_prefix_text.date_prefix_text, {{WRAPPER}} .ayyash-addons-post-date i' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
					],
				],
				'condition'  => [ 'date_prefix!' => 'none' ],
			]
		);
		$this->add_control(
			'date_color',
			[
				'label'      => esc_html__( 'Color', 'ayyash-addons' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#5E626D',
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-post-date' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
					],
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
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'thumb_meta_placement',
							'operator' => '===',
							'value'    => 'overlay_blog_item_thumbnail',
						],

					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'       => 'date_typography',
				'selector'   => '{{WRAPPER}} .ayyash-addons-post-date',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'date',
						],
					],
				],
			]
		);

		//Comment
		$this->add_control(
			'comment_heading',
			[
				'label'      => esc_html__( 'Comment', 'ayyash-addons' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'after',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
					],
				],
			]
		);
		$this->add_control(
			'comment_prefix',
			[
				'label'      => esc_html__( 'Prefix', 'ayyash-addons' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'icon',
				'options'    => [
					'none' => esc_html__( 'None', 'ayyash-addons' ),
					'text' => esc_html__( 'Text', 'ayyash-addons' ),
					'icon' => esc_html__( 'Icon', 'ayyash-addons' ),
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
					],
				],
			]
		);
		$this->add_control(
			'comment_prefix_text',
			[
				'label'       => esc_html__( 'Prefix Text', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Comments:', 'ayyash-addons' ),
				'placeholder' => esc_html__( 'Type your title here', 'ayyash-addons' ),
				'conditions'  => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
					],
				],
				'condition'   => [ 'comment_prefix' => 'text' ],
			]
		);
		$this->add_control(
			'comment_icon',
			[
				'label'            => esc_html__( 'Icon', 'ayyash-addons' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'fa4compatibility' => 'ayyash-addons',
				'default'          => [
					'value'   => 'far fa-comment',
					'library' => 'solid',
				],
				'skin'             => 'inline',
				'conditions'       => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
					],
				],
				'condition'        => [ 'comment_prefix' => 'icon' ],
			]
		);
		$this->add_control(
			'comment_prefix_color',
			[
				'label'      => esc_html__( 'Prefix Color', 'ayyash-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .meta_prefix_text.comment_prefix_text, {{WRAPPER}} .ayyash-addons-post-comment i' => 'color: {{VALUE}}',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
					],
				],
				'condition'  => [ 'comment_prefix!' => 'none' ],
			]
		);
		$this->add_control(
			'comment_color',
			[
				'label'      => esc_html__( 'Color', 'ayyash-addons' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#5E626D',
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-post-comment' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'        => 'comment_background',
				'label'       => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block' => true,
				'types'       => [ 'classic', 'gradient' ],
				'selector'    => '{{WRAPPER}} .ayyash-addons-post__meta.thumb_meta .ayyash-addons-post-comment',
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'thumb_meta_placement',
							'operator' => '===',
							'value'    => 'overlay_blog_item_thumbnail',
						],
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'       => 'comment_typography',
				'selector'   => '{{WRAPPER}} .ayyash-addons-post__meta .ayyash-addons-post-comment',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'comment',
						],
					],
				],
			]
		);

		//Category
		$this->add_control(
			'category_heading',
			[
				'label'      => esc_html__( 'Category', 'ayyash-addons' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'after',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
					],
				],
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
				'conditions'       => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
					],
				],
			]
		);
		$this->start_controls_tabs( 'category_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'category_normal',
			[
				'label'      => esc_html__( 'Normal', 'ayyash-addons' ),
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
					],
				],
			]
		);
		$this->add_control(
			'category_color',
			[
				'label'      => esc_html__( 'Color', 'ayyash-addons' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#064af3',
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-post-category a' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
					],
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
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'thumb_meta_placement',
							'operator' => '===',
							'value'    => 'overlay_blog_item_thumbnail',
						],

					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'       => 'category_typography',
				'selector'   => '{{WRAPPER}} .ayyash-addons-post-category',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
					],
				],
			]
		);
		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab(
			'category_hover',
			[
				'label'      => esc_html__( 'Hover', 'ayyash-addons' ),
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
					],
				],
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
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'thumb_meta_placement',
							'operator' => '===',
							'value'    => 'overlay_blog_item_thumbnail',
						],

					],
				],
			]
		);
		$this->add_control(
			'category_color_hover',
			[
				'label'      => esc_html__( 'Color', 'ayyash-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-post-category a:hover' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'category',
						],
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		//Tag
		$this->add_control(
			'tag_heading',
			[
				'label'      => esc_html__( 'Tag', 'ayyash-addons' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'after',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
					],
				],
			]
		);
		$this->add_control(
			'tag_icon',
			[
				'label'            => __( 'Icon', 'ayyash-addons' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'fa4compatibility' => 'ayyash-addons',
				'default'          => [
					'value'   => 'far fa-bookmark',
					'library' => 'solid',
				],
				'skin'             => 'inline',
				'conditions'       => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
					],
				],
			]
		);
		$this->start_controls_tabs( 'tag_tabs' );
		// Normal State Tab
		$this->start_controls_tab(
			'tag_normal',
			[
				'label'      => esc_html__( 'Normal', 'ayyash-addons' ),
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'        => 'tag_background',
				'label'       => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block' => true,
				'types'       => [ 'classic', 'gradient' ],
				'selector'    => '{{WRAPPER}} .ayyash-addons-post__meta.thumb_meta .ayyash-addons-post-tag',
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'thumb_meta_placement',
							'operator' => '===',
							'value'    => 'overlay_blog_item_thumbnail',
						],

					],
				],
			]
		);
		$this->add_control(
			'tag_color',
			[
				'label'      => esc_html__( 'Color', 'ayyash-addons' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#064af3',
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-post-tag a' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'       => 'tag_typography',
				'selector'   => '{{WRAPPER}} .ayyash-addons-post-tag',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
					],
				],
			]
		);
		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab(
			'tag_hover',
			[
				'label'      => esc_html__( 'Hover', 'ayyash-addons' ),
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'        => 'tag_background_hover',
				'label'       => esc_html__( 'Background', 'ayyash-addons' ),
				'label_block' => true,
				'types'       => [ 'classic', 'gradient' ],
				'selector'    => '{{WRAPPER}} .ayyash-addons-post__meta.thumb_meta .ayyash-addons-post-tag:hover',
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'thumb_meta_placement',
							'operator' => '===',
							'value'    => 'overlay_blog_item_thumbnail',
						],

					],
				],
			]
		);
		$this->add_control(
			'tag_color_hover',
			[
				'label'      => esc_html__( 'Color', 'ayyash-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-post-tag a:hover' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'thumb_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'title_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
						[
							'name'     => 'excerpt_metas',
							'operator' => 'contains',
							'value'    => 'tag',
						],
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render_post_header() {
		?>
		<article <?php post_class( [ 'ayyash-addons-post ayyash-addons-post-item' ] ); ?>>
		<?php
	}

	protected function render_post_footer() {
		?>
		</article>
		<?php
	}

	protected function render_content_header() {
		?>
		<div class="ayyash-addons-post__content">
		<?php
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

		if ( 'none' === $settings['thumbnail'] && ! Plugin::elementor()->editor->is_edit_mode() ) {
			return;
		}

		$setting_key              = 'thumbnail_size';
		$settings[ $setting_key ] = [
			'id' => get_post_thumbnail_id(),
		];

		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );
		if ( empty( $thumbnail_html ) ) {
			return;
		}

		/**
		 * Hook: ayyash_addons_before_blog_item_thumbnail.
		 */
		do_action( 'ayyash_addons_before_blog_item_thumbnail', [
			$settings['thumb_metas'],
			$settings['thumb_meta_placement'],
		] );
		?>
		<div class="ayyash-addons-post__thumbnail">
			<a class="ayyash-addons-post__thumbnail__link" href="<?php echo esc_url( $this->current_permalink ); ?>">
				<?php echo wp_kses_post( $thumbnail_html ); ?></a>
			<?php
			/**
			 * Hook: ayyash_addons_overlay_blog_item_thumbnail.
			 */
			do_action( 'ayyash_addons_overlay_blog_item_thumbnail', [
				$settings['thumb_metas'],
				$settings['thumb_meta_placement'],
			] );
			?>
		</div>
		<?php

		/**
		 * Hook: ayyash_addons_after_blog_item_thumbnail.
		 */
		do_action( 'ayyash_addons_after_blog_item_thumbnail', [
			$settings['thumb_metas'],
			$settings['thumb_meta_placement'],
		] );
	}

	protected function render_title( $settings ) {
		if ( 'yes' !== $settings['show_title'] ) {
			return;
		}

		/**
		 * Hook: ayyash_addons_before_blog_item_title.
		 */
		do_action( 'ayyash_addons_before_blog_item_title', [
			$settings['title_metas'],
			$settings['title_meta_placement'],
		] );

		$tag = $settings['title_tag'];

		?>
		<<?php Utils::print_validated_html_tag( $tag ); ?> class="ayyash-addons-post__title">
		<a href="<?php echo esc_url( $this->current_permalink ); ?>">
			<?php the_title(); ?>
		</a>
		</<?php Utils::print_validated_html_tag( $tag ); ?>>
		<?php

		/**
		 * Hook: ayyash_addons_after_blog_item_title.
		 */
		do_action( 'ayyash_addons_after_blog_item_title', [
			$settings['title_metas'],
			$settings['title_meta_placement'],
		] );
	}

	protected function render_excerpt( $settings ) {

		add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
		add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );

		if ( 'yes' !== $settings['show_excerpt'] ) {
			return;
		}

		add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
		add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );

		/**
		 * Hook: ayyash_addons_before_blog_item_excerpt.
		 */
		do_action( 'ayyash_addons_before_blog_item_excerpt', [
			$settings['excerpt_metas'],
			$settings['excerpt_meta_placement'],
		] );
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
		/**
		 * Hook: ayyash_addons_after_blog_item_excerpt.
		 */
		do_action( 'ayyash_addons_after_blog_item_excerpt', [
			$settings['excerpt_metas'],
			$settings['excerpt_meta_placement'],
		] );

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
		<a class="ayyash-addons-post__read-more" href="<?php echo esc_url( $this->current_permalink ); ?>">
			<?php echo wp_kses_post( $read_more ); ?>
		</a>
		<?php
	}

	public function render_meta_data( $metas = [] ) {

		if ( strpos( $metas[1], "thumbnail" ) !== false ) {
			$meta_class = 'thumb_meta';
		} elseif ( strpos( $metas[1], "title" ) !== false ) {
			$meta_class = 'title_meta';
		} elseif ( strpos( $metas[1], "excerpt" ) !== false ) {
			$meta_class = 'excerpt_meta';
		} else {
			$meta_class = '';
		}
		?>
		<div class="ayyash-addons-post__meta <?php echo esc_attr( $meta_class ) ?>">
			<?php
			if ( in_array( 'author', $metas[0] ) ) {
				$this->render_author();
			}
			if ( in_array( 'date', $metas[0] ) ) {
				$this->render_date();
			}
			if ( in_array( 'comment', $metas[0] ) ) {
				$this->render_comments();
			}
			if ( in_array( 'category', $metas[0] ) ) {
				$this->render_category();
			}
			if ( in_array( 'tag', $metas[0] ) ) {
				$this->render_tag();
			}
			?>
		</div>
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

	protected function render_comments() {
		$settings = $this->get_settings_for_display();
		?>
		<span class="ayyash-addons-post-comment">
			<?php
			if ( 'icon' === $settings['comment_prefix'] ) {
				Icons_Manager::render_icon( $settings['comment_icon'], [ 'aria-hidden' => 'true' ] );
			} elseif ( 'text' === $settings['comment_prefix'] ) {
				?>
				<span class="meta_prefix_text comment_prefix_text"><?php echo esc_html( $settings['comment_prefix_text'] ) ?></span>
				<?php
			}
			?>
			<?php comments_number(); ?>
		</span>
		<?php
	}

	protected function render_date( $type = 'publish' ) {
		$settings = $this->get_settings_for_display();
		?>
		<span class="ayyash-addons-post-date">
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
		<span class="ayyash-addons-post-category">
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
		$this->init_hooks();
		$this->render_post_header( $settings );
		$this->render_thumbnail( $settings );
		$this->render_content_header();
		$this->render_title( $settings );
		$this->render_excerpt( $settings );
		$this->render_read_more( $settings );
		$this->render_content_footer();
		$this->render_post_footer( $settings );
		$this->removed_hooks();
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
	 * Initialize hooks
	 */
	protected function init_hooks() {
		$settings = $this->get_settings_for_display();
		$prefix   = 'ayyash_addons_';

		if ( $settings['thumb_meta_placement'] ) {
			add_action( $prefix . $settings['thumb_meta_placement'], [ $this, 'render_meta_data' ] );
		}
		if ( $settings['title_meta_placement'] ) {
			add_action( $prefix . $settings['title_meta_placement'], [ $this, 'render_meta_data' ] );
		}
		if ( $settings['excerpt_meta_placement'] ) {
			add_action( $prefix . $settings['excerpt_meta_placement'], [ $this, 'render_meta_data' ] );
		}
	}

	/**
	 * Removed hooks
	 */
	protected function removed_hooks() {
		$settings = $this->get_settings_for_display();
		$prefix   = 'ayyash_addons_';

		if ( $settings['thumb_meta_placement'] ) {
			remove_action( $prefix . $settings['thumb_meta_placement'], [ $this, 'render_meta_data' ] );
		}
		if ( $settings['title_meta_placement'] ) {
			remove_action( $prefix . $settings['title_meta_placement'], [ $this, 'render_meta_data' ] );
		}
		if ( $settings['excerpt_meta_placement'] ) {
			remove_action( $prefix . $settings['excerpt_meta_placement'], [ $this, 'render_meta_data' ] );
		}
	}

	/**
	 * @render blog
	 */
	protected function render() {
		$settings      = $this->get_settings_for_display();
		$wrapper_class = 'ayyash-addons-post-wrapper' . ' ayyash-addons-column' . ' custom-gap';
		$this->add_render_attribute( [
			'ayyash_addons_wrap' => [
				'class' => $wrapper_class,
			],
		] );

		$args  = Ayyash_Addons_Query_Builder::build_query( $settings['query_builder'] );
		$posts = new \WP_Query( $args );

		?>
		<div
			class="ayyash-addons-wrapper ayyash-addons-<?php echo str_replace( 'ayyash-', '', $this->get_name() ); // phpcs:ignore ?>">
				<?php
				if ( $posts->have_posts() ) {
					?>
					<div <?php $this->print_render_attribute_string( 'ayyash_addons_wrap' ); ?>><?php
					while ( $posts->have_posts() ) {
						$posts->the_post();

						$this->current_permalink = get_permalink();
						$this->render_post( $settings );
					}
					?></div><?php
				} else {
					_e( 'Sorry, no posts were found.', 'ayyash-addons' );
				}
				?>
		</div>
		<?php
		wp_reset_postdata();
	}
}

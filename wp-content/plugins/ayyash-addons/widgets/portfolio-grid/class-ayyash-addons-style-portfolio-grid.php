<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Exception;
use WP_Post;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 */
class AyyashAddons_Style_Portfolio_Grid extends Ayyash_Widget {


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
		return 'ayyash-addons-portfolio-grid';
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
		return __( 'Portfolio Grid', 'ayyash-addons' );
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
		return 'ayyash-addons eicon-gallery-grid';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-portfolio-grid',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'ayyash-addons-portfolio-grid',
			'isotope',
		);
	}

	public function get_keywords() {
		return [ 'grid', 'porfolio grid', 'grid', 'portfolio-grid', 'portfolio grid' ];
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
		// Query Section
		$this->start_controls_section(
			'portfolio-grid-content',
			[
				'label' => __( 'Query Section', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'portfolio_grid_number_of_posts',
			[
				'label'        => esc_html__( 'Posts Count', 'ayyash-addons' ),
				'type'         => Controls_Manager::NUMBER,
				'min'          => 1,
				'max'          => 200,
				'step'         => 1,
				'default'      => 8,
				'descriptions' => esc_html__( 'If You need to show all post to input "-1"', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'portfolio_grid_select_portfolio_post',
			[
				'label'    => esc_html__( 'Select Category', 'ayyash-addons' ),
				'type'     => Controls_Manager::SELECT,
				'multiple' => true,
				'options'  => [
					'select_post' => esc_html__( 'Select Post', 'ayyash-addons' ),
					'category'    => esc_html__( 'Category Post', 'ayyash-addons' ),
				],
				'default'  => esc_html__( 'select_post', 'ayyash-addons' ),
			]
		);

		$all_terms = get_terms( 'portfolio_cat', [
			'hide_empty' => false,
		] );

		$args = [
			'post_type'      => 'portfolio',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		];

		// we get an array of posts objects
		$posts = get_posts( $args );

		$portfolio_terms = [];
		$portfolio_post  = [];

		foreach ( (array) $all_terms as $single_terms ) {
			$portfolio_terms[ $single_terms->slug . '|' . $single_terms->name ] = $single_terms->name;
		}

		foreach ( (array) $posts as $single_post ) {
			$portfolio_post[ $single_post->ID . '|' . $single_post->post_title ] = $single_post->post_title;
		}

		$this->add_control(
			'portfolio_grid_category_post',
			[
				'label'     => esc_html__( 'Select Category', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $portfolio_terms,
				'condition' => [
					'portfolio_grid_select_portfolio_post' => [ 'category' ],
				],
			]
		);

		$this->add_control(
			'portfolio_select_post',
			[
				'label'     => esc_html__( 'Select Post', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $portfolio_post,
				'condition' => [
					'portfolio_grid_select_portfolio_post' => [ 'select_post' ],
				],
			]
		);

		$this->add_control(
			'portfolio_grid_posts_order_by',
			[
				'label'   => esc_html__( 'Order by', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'date'          => esc_html__( 'Date', 'ayyash-addons' ),
					'title'         => esc_html__( 'Title', 'ayyash-addons' ),
					'author'        => esc_html__( 'Author', 'ayyash-addons' ),
					'modified'      => esc_html__( 'Modified', 'ayyash-addons' ),
					'comment_count' => esc_html__( 'Comments', 'ayyash-addons' ),
				],
				'default' => 'date',
			]
		);

		$this->add_control(
			'portfolio_grid_posts_sort',
			[
				'label'   => esc_html__( 'Order', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'ASC'  => esc_html__( 'ASC', 'ayyash-addons' ),
					'DESC' => esc_html__( 'DESC', 'ayyash-addons' ),
				],
				'default' => 'DESC',
			]
		);

		$this->end_controls_section();

		// Portfolio Layout Section
		$this->start_controls_section(
			'portfolio_grid_layout_section',
			[
				'label' => __( 'Portfolio Grid Layout', 'ayyash-addons' ),
			]
		);

		$this->add_responsive_control(
			'portfolio_grid_column',
			[
				'label'     => esc_html__( 'Portfolio Grid Column', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4',
				'options'   => [
					'1' => esc_html__( '1', 'ayyash-addons' ),
					'2' => esc_html__( '2', 'ayyash-addons' ),
					'3' => esc_html__( '3', 'ayyash-addons' ),
					'4' => esc_html__( '4', 'ayyash-addons' ),
					'5' => esc_html__( '5', 'ayyash-addons' ),
					'6' => esc_html__( '6', 'ayyash-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .filter-item' => 'width: calc( 100% / {{VALUE}} );',
				],
			]
		);

		$this->end_controls_section();

		// Portfolio Filter Section
		$this->start_controls_section(
			'filter_layout_section',
			array(
				'label' => __( 'Portfolio Filter', 'ayyash-addons' ),
			)
		);

		$this->add_control(
			'enable_filter_menu',
			[
				'label'        => esc_html__( 'Enable Filter Menu', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => esc_html__( 'Yes', 'ayyash-addons' ),
					'no'  => esc_html__( 'No', 'ayyash-addons' ),
				],
				'default'      => 'yes',
				'descriptions' => esc_html__( 'Enable to display filter menu.', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'show_all_filter',
			[
				'label'        => esc_html__( 'Show "All" Filter', 'ayyash-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => esc_html__( 'Yes', 'ayyash-addons' ),
					'no'  => esc_html__( 'No', 'ayyash-addons' ),
				],
				'default'      => 'yes',
				'descriptions' => esc_html__( 'Enable to display "All" filter in filter menu.', 'ayyash-addons' ),
				'condition'    => [
					'enable_filter_menu' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_text',
			[
				'label'     => esc_html__( 'Filter Text', 'ayyash-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'All',
				'condition' => [
					'show_all_filter'    => 'yes',
					'enable_filter_menu' => 'yes',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'filter_animation',
			[
				'label'   => esc_html__( 'Portfolio Animation', 'ayyash-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '0.5',
			]
		);

		$this->add_control(
			'filter_delay',
			[
				'label'   => esc_html__( 'Portfolio Delay', 'ayyash-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '50',
			]
		);

		$this->add_control(
			'filter_delay_mode',
			[
				'label'   => esc_html__( 'Portfolio Delay Mode', 'ayyash-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'progressive' => esc_html__( 'Progressive', 'ayyash-addons' ),
					'alternate'   => esc_html__( 'Alternate', 'ayyash-addons' ),
				],
				'default' => 'progressive',
			]
		);

		$this->end_controls_section();

		$this->__style_controller();

	}

	protected function __style_controller() {

		$this->start_controls_section(
			'portfolio_filter_seven',
			[
				'label'     => esc_html__( 'Filter Style', 'ayyash-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_filter_menu' => [ 'yes' ],
				],
			]
		);

		$this->start_controls_tabs( 'portfolio_filter_tabs' );

		$this->start_controls_tab(
			'filter_tabs_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'portfolio_filter_background',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Filter Background Color', 'ayyash-addons' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .ayyash-addons-portfolio-button button',
				'default'        => '',
			]
		);

		$this->add_control(
			'portfolio_filter_color',
			[
				'label'     => esc_html__( 'Filter Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-portfolio-button button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label'    => esc_html__( 'Border', 'ayyash-addons' ),
				'name'     => 'portfolio_filter_border',
				'selector' => '{{WRAPPER}} .ayyash-addons-portfolio-button button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Typography', 'ayyash-addons' ),
				'name'     => 'portfolio_filter_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-portfolio-button button',
			]
		);

		$this->add_responsive_control(
			'portfolio_filter_padding',
			[
				'label'      => esc_html__( 'Portfolio Filter Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-portfolio-button button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'filter_tabs_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'portfolio_filter_background_hover',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Filter Background Color', 'ayyash-addons' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .ayyash-addons-portfolio-button button:hover ',
				'default'        => '',
			]
		);

		$this->add_control(
			'portfolio_filter_color_hover',
			[
				'label'     => esc_html__( 'Filter Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-portfolio-button button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label'    => esc_html__( 'Border', 'ayyash-addons' ),
				'name'     => 'portfolio_filter_border_hover',
				'selector' => '{{WRAPPER}} .ayyash-addons-portfolio-button button:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'filter_tabs_active',
			[
				'label' => esc_html__( 'Active', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'portfolio_filter_background_active',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Filter Background Color', 'ayyash-addons' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .ayyash-addons-portfolio-button button.is-active',
				'default'        => '',
			]
		);

		$this->add_control(
			'portfolio_filter_color_active',
			[
				'label'     => esc_html__( 'Filter Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-portfolio-button button.is-active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label'    => esc_html__( 'Border', 'ayyash-addons' ),
				'name'     => 'portfolio_filter_border_active',
				'selector' => '{{WRAPPER}} .ayyash-addons-portfolio-button button.is-active',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_title_seven',
			[
				'label' => esc_html__( 'Title Style', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Typography', 'ayyash-addons' ),
				'name'     => 'portfolio_title_style_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-portfolio-title a',
			]
		);

		$this->add_control(
			'portfolio_title_style_color',
			[
				'label'     => esc_html__( 'Title Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-portfolio-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_category_seven',
			[
				'label' => esc_html__( 'Category Style', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Typography', 'ayyash-addons' ),
				'name'     => 'portfolio_category_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-portfolio-category a ',
			]
		);

		$this->add_control(
			'portfolio_category_color',
			[
				'label'     => esc_html__( 'Category Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-portfolio-category a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label'    => esc_html__( 'Border', 'ayyash-addons' ),
				'name'     => 'portfolio_category_border',
				'selector' => '{{WRAPPER}} .ayyash-addons-portfolio-category a',
			]
		);

		$this->add_responsive_control(
			'portfolio_category_radius',
			[
				'label'      => esc_html__( 'Category Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-portfolio-category a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'portfolio_category_padding',
			[
				'label'      => esc_html__( 'Portfolio Category Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-portfolio-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_overlay_seven',
			[
				'label' => esc_html__( 'Overlay Style', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'portfolio_overlay_background',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Category Background Color', 'ayyash-addons' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .ayyash-addons-portfolio-item.style-five::before',
				'default'        => '',
			]
		);

		$this->end_controls_section();


	}

	/**
	 * @throws Exception
	 */
	protected function render() {
		$settings          = $this->get_settings_for_display();
		$controls_selector = wp_unique_id( 'filter-container-' );

		$this->add_render_attribute( [
			'ayyash_addons_portfolio_grid' => [
				'class' => 'ayyash-addons-portfolio-grid-wrapper ',
			],
		] );
		$this->add_render_attribute( [
			'portfolio_filter' => [
				'class'                   => 'filters-group ayyash-addons-portfolio-button d-flex justify-content-md-center mt-30',
				'data-controls-selector'  => '.' . $controls_selector,
				'data-animation-duration' => esc_attr( $settings['filter_animation'] ),
				'data-delay'              => esc_attr( $settings['filter_delay'] ),
				'data-gutter-pixels'      => '30',
				'data-delay-mode'         => esc_attr( $settings['filter_delay_mode'] ),
			],
		] );

		$this->add_render_attribute( [
			'portfolio_container' => [
				'class' => 'filter-container ayyash-addons-grid mt-20',
			],
		] );

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( [
				'portfolio_container' => [
					'class' => 'portfolio-isotope-' . $this->get_id(),
				],
			] );
		}

		?>
		<div <?php $this->print_render_attribute_string( 'ayyash_addons_portfolio_grid' ); ?>>
			<div <?php $this->print_render_attribute_string( 'portfolio_filter' ); ?>>
				<?php $terms = $this->get_filter_menu( $settings, $controls_selector ); ?>
			</div>
			<div <?php $this->print_render_attribute_string( 'portfolio_container' ); ?>>
				<?php
				$posts = $this->get_posts( $settings, $terms );
				foreach ( $posts as $post ) {
					$this->render_single_item( $settings, $post );
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
		<?php
		if ( Plugin::$instance->editor->is_edit_mode() ) :
			printf( '<script>jQuery(".portfolio-isotope-%s").isotope();</script>', $this->get_id() ); // phpcs:ignore
		endif;
	}

	/**
	 * Render Single Slide
	 *
	 * @param $settings
	 * @param $post
	 */
	protected function render_single_item( $settings, $post ) {
		$thumbnail_url   = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', true );
		$portfolio_terms = get_the_terms( $post->ID, 'portfolio_cat' );
		$project_likes   = get_post_meta( $post->ID, 'ayyash_addons_portfolio_likes', true );
		if ( $portfolio_terms && ! is_wp_error( $portfolio_terms ) ) {
			$portfolio_terms = array_map( function ( $value ) {
				return $value->slug;
			}, $portfolio_terms );
			$portfolio_terms = implode( ' ', $portfolio_terms );
		} else {
			$portfolio_terms = '';
		}
		?>
		<div class="filter-item ayyash-addons-grid-item <?php echo esc_attr( $portfolio_terms ); ?>" data-category="<?php echo esc_attr( $portfolio_terms ); ?>">
			<div class="ayyash-addons-portfolio-item single-item">
				<div class="ayyash-addons-portfolio-img">
					<a data-fancybox="gallery" href="<?php echo esc_url( $thumbnail_url[0] ); ?>">
						<img src="<?php echo esc_url( $thumbnail_url[0] ); ?>" alt="<?php echo esc_attr( ( $post->post_title ) ? $post->post_title : '' ) ?>">
					</a>
				</div>
				<div class="ayyash-addons-portfolio-content d-flex justify-content-between">
					<div class="ayyash-addons-portfolio-content-meta">
						<h3 class="ayyash-addons-portfolio-title">
							<a href="<?php echo esc_url( get_the_permalink( $post->ID ) ); ?>"><?php echo esc_html( $post->post_title ); ?></a>
						</h3>
						<span class="ayyash-addons-portfolio-category"><?php $this->get_all_terms_link( $settings, $post, 'portfolio_cat' ) ?></span>
					</div>
					<div
						class="ayyash-addons-portfolio-like-button <?php echo ( is_user_liked( $post->ID ) ) ? 'liked' : ''; ?>"
						data-post_id="<?php echo esc_attr( $post->ID ); ?>">
						<span class="like-icon"><i class="fa fa-heart-o"></i></span>
						<span class="like-count"><?php if ( empty( $project_likes ) ) {
								echo 0;
							} else {
								echo esc_html( $project_likes );
							} ?></span>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Retrieve all post
	 *
	 * @param array $settings
	 * @param array $term_slugs
	 *
	 * @return WP_Post[]
	 */
	protected function get_posts( $settings, $term_slugs = [] ) {
		$args = [
			'post_type'      => 'portfolio',
			'post_status'    => 'publish',
			'posts_per_page' => $settings['portfolio_grid_number_of_posts'],
			'orderby'        => $settings['portfolio_grid_posts_order_by'],
			'order'          => $settings['portfolio_grid_posts_sort'],
		];

		if ( 'select_post' == $settings['portfolio_grid_select_portfolio_post'] ) {
			if ( is_array( $settings['portfolio_select_post'] ) ) {
				$args['post__in'] = array_map( 'absint', $settings['portfolio_select_post'] );
			}
		} else {
			if ( ! empty( $term_slugs ) ) {
				$args['tax_query'] = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					[
						'taxonomy' => 'portfolio_cat',
						'field'    => 'slug',
						'terms'    => $term_slugs,
					],
				];
			}
		}

		return get_posts( $args );
	}

	/**
	 * Get All Categories with link
	 *
	 * @param $settings
	 * @param $post
	 * @param $taxonomy
	 */
	protected function get_all_terms_link( $settings, $post, $taxonomy ) {
		$terms = get_the_terms( $post->ID, $taxonomy );

		if ( $terms && ! is_wp_error( $terms ) ) {
			$term_links = array();
			foreach ( $terms as $term ) {
				$name          = $term->name;
				$slug          = $term->slug;
				$get_term_link = get_term_link( $slug, $taxonomy );
				if ( $get_term_link && ! is_wp_error( $get_term_link ) ) {
					$link = esc_url( $get_term_link );
				}
				$term_links[] = '<li><a href="' . $link . '">' . wp_kses_post( $name ) . '</a></li>';
			}
			$all_terms = join( ', ', $term_links );
			echo '<ul class="terms terms-' . esc_attr( $slug ) . '">' . wp_kses_post( $all_terms ) . '</ul>';
		}
	}

	/**
	 * @param array $settings
	 * @param string $controls_selector
	 *
	 * @return array
	 */
	protected function get_filter_menu( $settings = [], $controls_selector = '' ) {
		if ( empty( $settings ) ) {
			$settings = $this->get_settings_for_display();
		}
		$term_slugs = [];
		if ( 'category' === $settings['portfolio_grid_select_portfolio_post'] && ! empty( $settings['portfolio_grid_category_post'] ) ) {
			$terms = ! is_array( $settings['portfolio_grid_category_post'] ) ? [] : $settings['portfolio_grid_category_post'];
		} else {
			$args = [
				'taxonomy'   => 'portfolio_cat',
				'hide_empty' => true,
				'object_ids' => null,
			];

			$terms = get_terms( $args );
		}
		$enable_filter = ( 'yes' === $settings['enable_filter_menu'] );
		if ( 'yes' === $settings['show_all_filter'] ) { ?>
			<button class="is-active <?php echo esc_attr( $controls_selector ); ?>"
					data-filter="all"><?php echo esc_html( $settings['filter_text'] ); ?></button>
			<?php
		}
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( is_string( $term ) ) {
					$term_data = explode( '|', $term );
					if ( 2 !== count( $term_data ) ) {
						continue;
					}
					list( $slug, $label ) = $term_data;
				} else {
					$slug  = $term->slug;
					$label = $term->name;
				}
				$term_slugs[] = $slug;
				if ( $enable_filter ) {
					echo '<button class="' . esc_attr( $controls_selector ) . '" data-filter="' . esc_attr( $slug ) . '">' . esc_html( $label ) . '</button>';
				}
			}
		}

		return $term_slugs;
	}
}





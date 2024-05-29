<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Addons_Slider_Controller;
use AyyashAddons\Ayyash_Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
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
class AyyashAddons_Style_Portfolio_Carousel extends Ayyash_Widget {

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
		return 'ayyash-portfolio-carousel';
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
		return __( 'Portfolio Carousel', 'ayyash-addons' );
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
			'ayyash-addons-portfolio-carousel',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'ayyash-addons-portfolio-carousel',
		);
	}

	public function get_keywords() {
		return [ 'carousel', 'porfolio carousel', 'portfolio', 'portfolio-slider', 'portfolio slider' ];
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
		$this->start_controls_section(
			'portfolio-carousel-content',
			[
				'label' => __( 'Query Section', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'number_of_posts',
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
			'select_portfolio_post',
			[
				'label'    => esc_html__( 'Select Category', 'ayyash-addons' ),
				'type'     => Controls_Manager::SELECT,
				'multiple' => true,
				'options'  => [
					'recent_post' => esc_html__( 'Recent Post', 'ayyash-addons' ),
					'category'    => esc_html__( 'Category Post', 'ayyash-addons' ),
				],
				'default'  => esc_html__( 'recent_post', 'ayyash-addons' ),
			]
		);

		$all_terms = get_terms( 'portfolio_cat', [ 'hide_empty' => true ] );

		$args = [
			'post_type'      => 'portfolio',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		];

		// we get an array of posts objects
		$faq_terms = [];

		foreach ( (array) $all_terms as $single_terms ) {
			$faq_terms[ $single_terms->slug . '|' . $single_terms->name ] = $single_terms->name;
		}

		$this->add_control(
			'portfolio_category_post',
			[
				'label'     => esc_html__( 'Select Category', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $faq_terms,
				'condition' => [
					'select_portfolio_post' => [ 'category' ],
				],
			]
		);

		$this->add_control(
			'portfolio_posts_order_by',
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
			'portfolio_posts_sort',
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
		$this->render_slider_controller();

		$this->__style_controller();

	}

	protected function __style_controller() {

		$this->start_controls_section(
			'poerfolio-slider-style-section', [
				'label' => __( 'Portfolio Slider Style', 'ayyash-addons' ),
				"tab"   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'portfolio-slider-loges-gap',
			[
				'label'      => __( 'Logo Gap', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-portfolio-slider .swiper-wrapper  ' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'portfolio-slider-left-arrow',
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
			'portfolio-slider-right-arrow',
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

		$this->start_controls_tabs(
			'portfolio-slider-style-tabs'
		);

		$this->start_controls_tab(
			'portfolio-slider-style-normal',
			[
				'label' => __( 'Normal', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label'    => __( 'Background', 'ayyash-addons' ),
				'name'     => 'portfolio-slider-background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ayyash-addons-portfolio-slider .ayyash-addons-project-item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'portfolio-slider-border',
				'label'    => __( 'Border', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-portfolio-slider .ayyash-addons-project-item',
			]
		);

		$this->add_control(
			'portfolio-slider-filter',
			[
				'label'        => __( 'Filter Effect', 'ayyash-addons' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'no',
				'options'      => [
					'yes' => __( 'Yes', 'ayyash-addons' ),
					'no'  => __( 'No', 'ayyash-addons' ),
				],
				'prefix_class' => 'gray-filter-',
			]
		);

		$this->add_control(
			'portfolio-slider-filter-list',
			[
				'label'     => __( 'Filter Effect', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'no',
				'options'   => [
					'brightness' => __( 'Brightness', 'ayyash-addons' ),
					'contrast'   => __( 'Contrast', 'ayyash-addons' ),
					'grayscale'  => __( 'Grayscale', 'ayyash-addons' ),
					'invert'     => __( 'Invert', 'ayyash-addons' ),
					'opacity'    => __( 'Opacity', 'ayyash-addons' ),
					'saturate'   => __( 'saturate', 'ayyash-addons' ),
					'sepia'      => __( 'Sepia', 'ayyash-addons' ),
				],
				'condition' => [ 'portfolio-slider-filter' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'portfolio-slider-filter-value',
			[
				'label'      => __( 'Filter Value', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'condition'  => [ 'portfolio-slider-filter' => 'yes' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-portfolio-slider .ayyash-addons-project-item img' => 'filter: {{portfolio-slider-filter-list.VALUE}}({{SIZE}}%);',
				],
			]
		);

		$this->add_control(
			'portfolio-slider-border-radius',
			[
				'label'      => __( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-portfolio-slider .ayyash-addons-project-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'portfolio-slider-box-shadow',
				'label'    => __( 'Box Shadow', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-portfolio-slider .ayyash-addons-project-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'portfolio-slider-style-hover',
			[
				'label' => __( 'Hover', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label'    => __( 'Background', 'ayyash-addons' ),
				'name'     => 'portfolio-slider-background-hover',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ayyash-addons-portfolio-slider .ayyash-addons-project-item:hover ',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'portfolio-slider-border-hover',
				'label'    => __( 'Border', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-portfolio-slider .ayyash-addons-project-item:hover',
			]
		);


		$this->add_control(
			'portfolio-slider-filter-hover',
			[
				'label'        => __( 'Filter Effect', 'ayyash-addons' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'no',
				'options'      => [
					'yes' => __( 'Yes', 'ayyash-addons' ),
					'no'  => __( 'No', 'ayyash-addons' ),
				],
				'prefix_class' => 'gray-filter-hover-',

			]
		);

		$this->add_control(
			'portfolio-slider-filter-list-hover',
			[
				'label'     => __( 'Filter Effect', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'no',
				'options'   => [
					'brightness' => __( 'Brightness', 'ayyash-addons' ),
					'contrast'   => __( 'Contrast', 'ayyash-addons' ),
					'grayscale'  => __( 'Grayscale', 'ayyash-addons' ),
					'invert'     => __( 'Invert', 'ayyash-addons' ),
					'opacity'    => __( 'Opacity', 'ayyash-addons' ),
					'saturate'   => __( 'saturate', 'ayyash-addons' ),
					'sepia'      => __( 'Sepia', 'ayyash-addons' ),
				],
				'condition' => [ 'portfolio-slider-filter-hover' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'portfolio-slider-filter-value-hover',
			[
				'label'      => __( 'Filter Value', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'condition'  => [ 'portfolio-slider-filter-hover' => 'yes' ],
				'selectors'  => [
					'{{WRAPPER}}  .image-grid .ayyash-addons-project-item:hover img'                    => 'filter: {{logo-carousel-filter-list-hover.VALUE}}({{SIZE}}%);',
					'{{WRAPPER}} .ayyash-addons-portfolio-slider .ayyash-addons-project-item:hover img' => 'filter: {{logo-carousel-filter-list.VALUE}}({{SIZE}}%);',
				],
			]
		);

		$this->add_control(
			'portfolio-slider-border-radius-hover',
			[
				'label'      => __( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}  .ayyash-addons-portfolio-slider .ayyash-addons-project-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'portfolio-slider-box-shadow-hover',
				'label'    => __( 'Box Shadow', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-portfolio-slider .ayyash-addons-project-item:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

	}

	/**
	 * @throws Exception
	 */
	protected function render() {

		$settings     = $this->get_settings_for_display();
		$swiper_class = Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';

		$this->add_render_attribute( [
			'ayyash_addons_slider' => [
				'class' => 'ayyash-addons-swiper-wrapper ' . $swiper_class,
			],
		] );

		$this->add_render_attribute( [ 'ayyash_addons_slider' => $this->get_slider_attributes( $settings ) ] );

		?>
		<div class="ayyash-addons-portfolio-slider">
			<div <?php $this->print_render_attribute_string( 'ayyash_addons_slider' ); ?>>
				<div class="swiper-wrapper">
					<?php
					$posts = $this->get_posts( $settings );

					foreach ( $posts as $post ) {
						$this->render_single_slide( $settings, $post );
					}
					wp_reset_postdata();

					?>
				</div>
			</div>
			<?php $this->slider_nav( $settings ); ?>
		</div>

		<?php
	}

	/**
	 * Render Single Slide
	 *
	 * @param $settings
	 */
	protected function render_single_slide( $settings, $post ) {
		$thumbnail_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', true );

		?>
		<div class="swiper-slide ayyash-addons-project-item">
			<a href="<?php echo esc_url( $thumbnail_url[0] ); ?>" data-fancybox="gallery" class="ayyash-addons-project-image">
				<img src="<?php echo esc_url( $thumbnail_url[0] ); ?>" alt="<?php esc_attr_e( 'Project Image', 'ayyash-addons' ); ?>">
				<span class="ayyash-addons-project-overlay"></span>
			</a>
			<div class="ayyash-addons-project-content">
				<h5><a href="<?php echo esc_url( get_the_permalink( $post->ID ) ); ?>" data-fancybox="gallery" class="ayyash-addons-project-title"><?php echo esc_html( $post->post_title ); ?></a></h5>
				<?php $this->get_all_terms_link( $settings ) ?>
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
			'posts_per_page' => $settings['number_of_posts'],
			'orderby'        => $settings['portfolio_posts_order_by'],
			'order'          => $settings['portfolio_posts_sort'],
		];

		if ( 'select_post' == $settings['select_portfolio_post'] && ! empty( $settings['portfolio_select_post'] ) ) {
			$args['post__in'] = array_map( 'absint', $settings['portfolio_select_post'] );
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
	 * Retrieved Recent Post or Category Post
	 *
	 * @param $settings
	 *
	 * @return array|int[]|string|string[]|\WP_Error|\WP_Term[]
	 */
	protected function get_custom_term( $settings ) {
		if ( 'category' === $settings['select_portfolio_post'] && ! empty( $settings['portfolio_category_post'] ) ) {
			$terms = ! is_array( $settings['portfolio_category_post'] ) ? [] : $settings['portfolio_category_post'];
		} elseif ( 'recent_post' == $settings['select_portfolio_post'] ) {
			$recent_posts = get_posts( [
				'fields'      => 'ids',
				'post_type'   => 'portfolio',
				'orderby'     => 'post_date',
				'order'       => 'DESC',
				'numberposts' => $settings['number_of_posts'], // Number of recent posts thumbnails to display
				'post_status' => 'publish', // Show only the published posts
			] );
			$args         = [
				'taxonomy'   => 'portfolio_cat',
				'hide_empty' => true,
				'object_ids' => $recent_posts,
			];
			$terms        = get_terms( $args );
		} else {
			$args = [
				'taxonomy'   => 'portfolio_cat',
				'hide_empty' => true,
				'object_ids' => null,
			];
			if ( ! empty( $settings['portfolio_select_post'] ) && is_array( $settings['portfolio_select_post'] ) ) {
				$args['object_ids'] = array_map( 'absint', $settings['portfolio_select_post'] );
			}

			$terms = get_terms( $args );
		}

		return $terms;

	}

	/**
	 * Get All Categories with link
	 *
	 * @param $settings
	 */
	protected function get_all_terms_link( $settings ) {
		$terms = $this->get_custom_term( $settings );

		if ( $terms && ! is_wp_error( $terms ) ) {
			$termlink = [];
			foreach ( $terms as $term ) {
				$term_link = get_term_link( $term );

				if ( is_wp_error( $term_link ) ) {
					continue;
				}

				$termlink[] = '<a href="' . esc_url( $term_link ) . '" class="ayyash-addons-project-category">' . esc_html( $term->name ) . '</a>';
			}
			$termlink = implode( ', ', $termlink );
			echo wp_kses_post( $termlink );
		}

	}

}





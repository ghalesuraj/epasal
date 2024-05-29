<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Addons_Accordion_Controller;
use AyyashAddons\Ayyash_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AyyashAddons_Style_Faq extends Ayyash_Widget {

	use Ayyash_Addons_Accordion_Controller;

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
		return 'ayyash-addons-faq';
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
		return __( 'FAQ', 'ayyash-addons' );
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
		return 'ayyash-addons eicon-help-o';
	}

	public function get_keywords() {
		return [ 'faq', 'question', 'ask' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-faq',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'sifter',
			'jquery.beefup',
			'ayyash-addons-faq',
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

	protected function register_controls() {

		$this->start_controls_section(
			'section_faq',
			array(
				'label' => __( 'Faq', 'ayyash-addons' ),
			)
		);

		$this->add_control(
			'faq_heading',
			[
				'label'       => esc_html__( 'Faq Heading', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'SUBJECTS', 'ayyash-addons' ),
			]
		);

		$this->end_controls_section();

		$this->render_accordion_control();

		$this->start_controls_section(
			'query_section',
			array(
				'label' => __( 'Query Section', 'ayyash-addons' ),
			)
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
			'select_faq_post',
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

		$all_terms = get_terms( 'faq_category', [ 'hide_empty' => true ] );

		$args = [
			'post_type'      => 'faq',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		];

		// we get an array of posts objects
		$faq_terms = [];

		foreach ( (array) $all_terms as $single_terms ) {
			$faq_terms[ $single_terms->slug . '|' . $single_terms->name ] = $single_terms->name;
		}

		$this->add_control(
			'faq_category_post',
			[
				'label'     => esc_html__( 'Select Category', 'ayyash-addons' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $faq_terms,
				'condition' => [
					'select_faq_post' => [ 'category' ],
				],
			]
		);

		$this->add_control(
			'faq_posts_offset',
			[
				'label'   => esc_html__( 'Offset', 'ayyash-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 20,
				'default' => 0,
			]
		);

		$this->add_control(
			'faq_posts_order_by',
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
			'faq_posts_sort',
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


		$this->__style_controller();
	}

	protected function __style_controller() {

		$this->start_controls_section(
			'section_style_faq_general',
			[
				'label' => esc_html__( 'General FAQ Style', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'faq_general_style' );

		$this->start_controls_tab(
			'faq_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label'      => esc_html__( 'Width', 'ayyash-addons' ),
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
					'{{WRAPPER}} .faq .content-entry' => 'max-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .faq-item-six .content-entry .accordion-right-content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'section_style_faq_body_background',
				'label'    => esc_html__( 'Background', 'ayyash-addons' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .faq .content-entry, {{WRAPPER}} .content-entry .accordion-right-content',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .faq .content-entry, {{WRAPPER}} .content-entry .accordion-right-content',
				'default'  =>
					[
						'horizontal' => 2,
						'vertical'   => 3,
						'blur'       => 2,
						'spread'     => 2,
						'color'      => 'rgba(0,0,0,0.05)',
					],

			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'section_style_faq_general_border',
				'label'    => esc_html__( 'FAQ Border', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .faq .content-entry, {{WRAPPER}} .content-entry .accordion-right-content',
			)
		);

		$this->add_responsive_control(
			'body_section_border_radius',
			[
				'label'      => esc_html__( 'FAQ Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .faq .content-entry' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .content-entry .accordion-right-content .collapse-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'body_section_padding',
			[
				'label'      => esc_html__( 'FAQ Section Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .faq .content-entry' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .content-entry .accordion-right-content .collapse-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'faq_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'faq_hover_background',
				'label'    => esc_html__( 'Background', 'ayyash-addons' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .faq .content-entry:hover, {{WRAPPER}} .content-entry:hover .accordion-right-content .collapse-body',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .faq .content-entry:hover, {{WRAPPER}} .content-entry:hover .accordion-right-content .collapse-body',
				'default'  =>
					[
						'horizontal' => 2,
						'vertical'   => 3,
						'blur'       => 2,
						'spread'     => 2,
						'color'      => 'rgba(0,0,0,0.05)',
					],

			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'faq_border_hover',
				'label'    => esc_html__( 'FAQs Border', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .faq .content-entry:hover, {{WRAPPER}} .content-entry:hover .accordion-right-content .collapse-body',
			]
		);

		$this->add_responsive_control(
			'hover_border_radius',
			[
				'label'      => esc_html__( 'FAQs Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .faq .content-entry:hover, {{WRAPPER}} .content-entry:hover .accordion-right-content .collapse-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'faq_title_style',
			array(
				'label' => esc_html__( 'FAQs Title', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'faq_title_style_tab' );

		$this->start_controls_tab(
			'faq_title_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Typography', 'ayyash-addons' ),
				'name'     => 'accordion_title_typography',
				'selector' => '{{WRAPPER}} .faq .collapse-head button, {{WRAPPER}} .content-entry .accordion-right-content .collapse-head button',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'faq_title_bg',
				'label'    => esc_html__( 'Background', 'ayyash-addons' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .faq .collapse-head button, {{WRAPPER}} .content-entry .accordion-right-content .collapse-head button',
			]
		);

		$this->add_control(
			'faq_title_color',
			[
				'label'     => esc_html__( 'FAQs Title Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .faq .collapse-head button, {{WRAPPER}} .content-entry .accordion-right-content .collapse-head button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'faq_title_padding',
			[
				'label'      => esc_html__( 'FAQs Title Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .faq .collapse-head button, {{WRAPPER}} .content-entry .accordion-right-content .collapse-head button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'faq_title_hover',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'faq_title_bg_hover',
				'label'    => esc_html__( 'Background', 'ayyash-addons' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .faq .collapse-head:hover button, {{WRAPPER}} .content-entry .accordion-right-content .collapse-head:hover button',
			]
		);

		$this->add_control(
			'faq_title_color_hover',
			[
				'label'     => esc_html__( 'FAQs Title Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .faq .collapse-head:hover button'                                    => 'color: {{VALUE}}',
					'{{WRAPPER}} .content-entry .accordion-right-content .collapse-head:hover button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'faq_title_active',
			[
				'label' => esc_html__( 'Active', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'faq_title_bg_active',
				'label'    => esc_html__( 'Background', 'ayyash-addons' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .content-entry.is-open .collapse-head button, {{WRAPPER}} .faq .content-entry.is-open .accordion-right-content .collapse-head button',
			]
		);

		$this->add_control(
			'faq_title_color_active',
			[
				'label'     => esc_html__( 'FAQs Title Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .faq .content-entry.is-open .collapse-head button'                     => 'color: {{VALUE}}',
					'{{WRAPPER}} .content-entry.is-open .accordion-right-content .collapse-head button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'faq_content_style',
			[
				'label' => esc_html__( 'FAQs Content', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Typography', 'ayyash-addons' ),
				'name'     => 'faq_content_typography',
				'selector' => '{{WRAPPER}} .faq .collapse-body, {{WRAPPER}} .accordion-right-content .collapse-body',
			]
		);

		$this->add_control(
			'faq_content_color',
			[
				'label'     => esc_html__( 'FAQs Content', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .faq .collapse-body' => 'color: {{VALUE}}',
					'{{WRAPPER}} .content-entry .accordion-right-content .collapse-body' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'faq_content_padding',
			[
				'label'      => esc_html__( 'FAQs Content Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .faq .collapse-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .content-entry .accordion-right-content .collapse-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'faq_icon_style',
			[
				'label' => esc_html__( 'FAQs Icon', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'faq_icon_style_tab' );

		$this->start_controls_tab(
			'faq_icon_normal',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'faq_icon_color',
			[
				'label'     => esc_html__( 'Accordion Icon Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .faq .content-entry i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'faq_icon_hover',
			[
				'label' => esc_html__( 'Hover & Active', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'faq_icon_color_hover',
			[
				'label'     => esc_html__( 'Accordion Icon Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .faq .content-entry:hover i'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .faq .content-entry.is-open i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$uid      = wp_unique_id( 'ayyash-addons-faq-' );

		$this->add_render_attribute( [
			'ayyash-addons-faq-item-wrap' => [
				'class' => 'faq ayyash-addons-faq-' . $this->get_icon_alignment( $settings ),
			],
		] );
		$this->add_render_attribute( [
			'ayyash-addons-faq-item' => [
				'class'               => 'content-entry accordion',
				'data-beefup-options' => $this->get_accordion_attributes( $settings ),
			],
		] );
		?>
		<div class="ayyash-addons-wrapper ayyash-addons-widget">
			<div class="ayyash-addons-wrapper-inside">
				<div class="ayyash-addons-wrapper-content">
					<!-- absp-faq -->
					<div class="ayyash-addons-faq element-<?php echo esc_attr( $uid ); ?>">
						<section class="faq-items-wrap">
							<?php
							$this->render_tabs( $uid, $settings );
							//$this->render_faq_schema( $settings['faq'], 'faq_title', 'faq_content', $settings );
							?>
						</section>
					</div>
					<!-- absp-faq -->
				</div>
			</div>
		</div>
		<?php
	}

	protected function render_tab_navs( $uid, $settings ) {
		?>
		<div class="faq-left">
			<h2 class="faq-heading-text"><?php echo esc_html( $settings['faq_heading'] ); ?></h2>
			<?php $term_slugs = $this->faq_tab_menu( $uid, $settings ); ?>
		</div>
		<?php
		return $term_slugs;
	}

	protected function render_faq_content( $settings ) {
		?>
		<div <?php $this->print_render_attribute_string( 'ayyash-addons-faq-item-wrap' ); ?>>
			<article <?php $this->print_render_attribute_string( 'ayyash-addons-faq-item' ); ?>>
				<h4 class="collapse-head">
					<button type="button" aria-expanded="false">
						<?php the_title(); ?>
						<?php $this->faq_icon( $settings ); ?>
					</button>
				</h4>
				<div class="collapse-body">
					<?php the_content(); ?>
				</div>
			</article>
		</div>
		<?php
	}

	protected function render_search_form( $uid ) {
		?>
		<form class="search-form">
			<label class="sr-only" for="<?php echo esc_attr( $uid . '_search' ); ?>"><?php esc_html_e( 'Search FAQ', 'ayyash-addons' ); ?></label>
			<input class="faqsearch" id="<?php echo esc_attr( $uid . '_search' ); ?>" type="text" placeholder="<?php esc_attr_e( 'Search..', 'ayyash-addons' ); ?>">
			<button type="submit">
				<i class="fas fa-search" aria-hidden="true"></i>
				<span class="sr-only"><?php esc_html_e( 'Search', 'ayyash-addons' ); ?></span>
			</button>
		</form>
		<?php
	}

	protected function render_tabs( $uid, $settings ) {
		$term_slugs = $this->render_tab_navs( $uid, $settings ); ?>
		<div class="tab-content">
			<?php $this->render_search_form( $uid ); ?>
			<?php foreach ( $term_slugs as $index => $term_slug ) { ?>
				<div class="faq-tab-item <?php echo 0 == $index ? 'is-open' : ''; ?>" id="<?php echo esc_attr( $uid . $term_slug ); ?>">
					<h4 class="tab-head" style="display: none !important;"><?php ayyash_addons_render_title( $term_slug ); ?></h4>
					<div class="tab-body">
						<?php
						$posts = $this->get_posts( $settings, $term_slug );
						foreach ( $posts as $post ) {
							setup_postdata( $post );
							$GLOBALS['post'] = $post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

							$aria_expanded = $this->handle_expend_first( 'absp-faq-item', $settings, $aria_expanded );

							$this->render_faq_content( $settings );
						}
						wp_reset_postdata();
						//$this->render_faq_schema( $posts, '', '', $settings );
						?>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
	}

	protected function faq_tab_menu( $unique_id, $settings = [] ) {
		$term_slugs = [];
		if ( 'category' === $settings['select_faq_post'] && ! empty( $settings['faq_category_post'] ) ) {
			$terms = ! is_array( $settings['faq_category_post'] ) ? [] : $settings['faq_category_post'];
		} elseif ( 'recent_post' == $settings['select_faq_post'] ) {
			$recent_posts = get_posts( [
				'fields'      => 'ids',
				'post_type'   => 'faq',
				'orderby'     => 'post_date',
				'order'       => 'DESC',
				'numberposts' => $settings['number_of_posts'], // Number of recent posts thumbnails to display
				'post_status' => 'publish', // Show only the published posts
			] );
			$args         = [
				'taxonomy'   => 'faq_category',
				'hide_empty' => true,
				'object_ids' => $recent_posts,
			];
			$terms        = get_terms( $args );
		} else {
			$args = [
				'taxonomy'   => 'faq_category',
				'hide_empty' => true,
				'object_ids' => null,
			];
			if ( ! empty( $settings['faq_select_post'] ) && is_array( $settings['faq_select_post'] ) ) {
				$args['object_ids'] = array_map( 'absint', $settings['faq_select_post'] );
			}

			$terms = get_terms( $args );
		}

		if ( $terms && ! is_wp_error( $terms ) ) {
			echo '<div class="scoll-tab"><ul class="nav-tab">';
			foreach ( $terms as $index => $term ) {
				$is_open = ! $index ? 'is-open' : '';
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
				echo '<li class="' . esc_attr( $is_open ) . '"><a href="#' . esc_attr( $unique_id . $slug ) . '" class="faq-cat-' . esc_attr( $slug ) . '">' . esc_html( $label ) . '</a></li>';
			}
			echo '</ul></div>';
		}

		return $term_slugs;
	}

	/**
	 * @param array $settings
	 * @param array $term_slugs
	 *
	 * @return WP_Post[]
	 */
	protected function get_posts( $settings, $term_slugs = [] ) {
		$args = [
			'post_type'      => 'faq',
			'post_status'    => 'publish',
			'posts_per_page' => $settings['number_of_posts'],
			'offset'         => $settings['faq_posts_offset'],
			'orderby'        => $settings['faq_posts_order_by'],
			'order'          => $settings['faq_posts_sort'],
		];

		if ( 'select_post' == $settings['select_faq_post'] && ! empty( $settings['faq_select_post'] ) ) {
			$args['post__in'] = array_map( 'absint', $settings['faq_select_post'] );
		} else {
			if ( ! empty( $term_slugs ) ) {
				$args['tax_query'] = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					[
						'taxonomy' => 'faq_category',
						'field'    => 'slug',
						'terms'    => $term_slugs,
					],
				];
			}
		}

		return get_posts( $args );
	}

	/**
	 * @param array $settings
	 *
	 * @return void
	 *
	 */
	protected function faq_icon( $settings = [] ) {

		if ( ! $this->maybe_render_icons( $settings ) ) {
			return;
		}
		?>
		<div class="faq-icon-closed"><?php $this->render_icon_collapsed( $settings ); ?></div>
		<div class="faq-icon-opened"><?php $this->render_icon_active( $settings ); ?></div>
		<?php
	}


}

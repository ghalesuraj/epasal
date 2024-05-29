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


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 */
class AyyashAddons_Style_Logo_Carousel extends Ayyash_Widget {

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
		return 'ayyash-logo-carousel';
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
		return __( 'Logo Carousel', 'ayyash-addons' );
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
			'ayyash-addons-logo-carousel',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'ayyash-addons-logo-carousel',
		);
	}

	public function get_keywords() {
		return [ 'Carousel', 'logo carousel', 'logo', 'logo-slider', 'logo slider' ];
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
			'logo-carousel-content',
			[
				'label' => __( 'Add Logos', 'ayyash-addons' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'logos',
			[
				'label' => __( 'Add Logo', 'ayyash-addons' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$repeater->add_control(
			'logo_link',
			[
				'label'       => esc_html__( 'Link', 'ayyash-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'ayyash-addons' ),
				'options'     => [ 'url', 'is_external', 'nofollow' ],
				'label_block' => true,
			]
		);

		$this->add_control(
			'carousel_logos',
			[
				'label'  => __( 'Add Logos', 'ayyash-addons' ),
				'type'   => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			]
		);
		$this->end_controls_section();
		$this->render_slider_controller();

		$this->__style_controller();

	}

	protected function __style_controller() {

		$this->start_controls_section(
			'logo-carousel-style-section', [
				'label' => __( 'Logo Carousel Style', 'ayyash-addons' ),
				"tab"   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'logo-carousel-loges-gap',
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
					'{{WRAPPER}} .ayyash-addons-logo-slider .swiper-wrapper  ' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo-carousel-loges-width',
			[
				'label'      => __( 'Logo Width', 'ayyash-addons' ),
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
					'{{WRAPPER}} .ayyash-addons-logo-slider  .ayyash-addons-logo-carousel-item  ' => 'width: {{SIZE}}{{UNIT}}; !important',
				],
			]
		);

		$this->add_responsive_control(
			'logo-carousel-loges-height',
			[
				'label'      => __( 'Logo Height', 'ayyash-addons' ),
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
					'{{WRAPPER}} .ayyash-addons-logo-slider  .ayyash-addons-logo-carousel-item ' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'logo-carousel-loges-left-arrow',
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
			'logo-carousel-loges-right-arrow',
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
			'logo-carousel-style-tabs'
		);

		$this->start_controls_tab(
			'logo-carousel-style-normal',
			[
				'label' => __( 'Normal', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label'    => __( 'Background', 'ayyash-addons' ),
				'name'     => 'logo-carousel-background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ayyash-addons-logo-slider .ayyash-addons-logo-carousel-item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'logo-carousel-border',
				'label'    => __( 'Border', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-logo-slider .ayyash-addons-logo-carousel-item',
			]
		);

		$this->add_control(
			'logo-carousel-filter',
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
			'logo-carousel-filter-list',
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
				'condition' => [ 'logo-carousel-filter' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'logo-carousel-filter-value',
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
				'condition'  => [ 'logo-carousel-filter' => 'yes' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-logo-slider .ayyash-addons-logo-carousel-item img' => 'filter: {{logo-carousel-filter-list.VALUE}}({{SIZE}}%);',
				],
			]
		);

		$this->add_control(
			'logo-carousel-border-radius',
			[
				'label'      => __( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-logo-slider .ayyash-addons-logo-carousel-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'logo-carousel-box-shadow',
				'label'    => __( 'Box Shadow', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-logo-slider .ayyash-addons-logo-carousel-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'logo-carousel-style-hover',
			[
				'label' => __( 'Hover', 'ayyash-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label'    => __( 'Background', 'ayyash-addons' ),
				'name'     => 'logo-carousel-background-hover',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ayyash-addons-logo-slider .ayyash-addons-logo-carousel-item:hover ',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'logo-carousel-border-hover',
				'label'    => __( 'Border', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-logo-slider .ayyash-addons-logo-carousel-item:hover',
			]
		);


		$this->add_control(
			'logo-carousel-filter-hover',
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
			'logo-carousel-filter-list-hover',
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
				'condition' => [ 'logo-carousel-filter-hover' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'logo-carousel-filter-value-hover',
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
				'condition'  => [ 'logo-carousel-filter-hover' => 'yes' ],
				'selectors'  => [
					'{{WRAPPER}}  .image-grid .ayyash-addons-logo-carousel-item:hover img'               => 'filter: {{logo-carousel-filter-list-hover.VALUE}}({{SIZE}}%);',
					'{{WRAPPER}} .ayyash-addons-logo-slider .ayyash-addons-logo-carousel-item:hover img' => 'filter: {{logo-carousel-filter-list.VALUE}}({{SIZE}}%);',
				],
			]
		);

		$this->add_control(
			'logo-carousel-border-radius-hover',
			[
				'label'      => __( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}  .ayyash-addons-logo-slider .ayyash-addons-logo-carousel-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'logo-carousel-box-shadow-hover',
				'label'    => __( 'Box Shadow', 'ayyash-addons' ),
				'selector' => '{{WRAPPER}} .ayyash-addons-logo-slider .ayyash-addons-logo-carousel-item:hover',
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
		<div class="ayyash-addons-logo-slider">
			<div <?php $this->print_render_attribute_string( 'ayyash_addons_slider' ); ?>>
				<div class="swiper-wrapper">
					<?php
					foreach ( $settings['carousel_logos'] as $logo ) {
						if ( ! empty( $logo['logo_link']['url'] ) ) {
							$this->add_link_attributes( 'website_link', $logo['logo_link'] );
						}
						?>
						<div class="swiper-slide ayyash-addons-logo-carousel-item">
							<?php
							if ( ! empty( $logo['logo_link']['url'] ) ) {
								echo '<a ' . $this->get_render_attribute_string( 'website_link' ) . ' >'; //phpcs:ignore
							} ?>

							<img src="<?php echo esc_url( $logo['logos']['url'] ); ?>" alt="<?php echo esc_attr( ( isset( $logo['logos']['alt'] ) ) ? $logo['logos']['alt'] : get_the_title( $logo['logos']['id'] ) ) ?>">

							<?php if ( ! empty( $logo['logo_link']['url'] ) ) {
								echo '</a>';
							} ?>

						</div>
					<?php } ?>
				</div>
			</div>
			<?php $this->slider_nav( $settings ); ?>
		</div>

		<?php
	}

}

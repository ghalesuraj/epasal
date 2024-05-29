<?php

namespace AyyashAddonsPro\Widgets;

use AyyashAddonsPro\Ayyash_Pro_Widget;
use Elementor\Controls_Manager;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class AyyashAddons_Style_Shortcode_Slider extends Ayyash_Pro_Widget {

	protected $slider_dot;

	protected $slider_next;

	protected $slider_prev;

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
		return 'ayyash-shortcode-slider';
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
		return __( 'Shortcode Slider', 'ayyash-addons-pro' );
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
		return 'ayyash-addons eicon-testimonial-carousel';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-pro-shortcode-slider',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'ayyash-addons-pro-shortcode-slider',
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
			'slides_section',
			[
				'label' => __( 'Slides', 'ayyash-addons-pro' ),
			]
		);

		//content controls
		$repeater = new repeater();
		$repeater->add_control(
			'slide_shortcode',
			[
				'label'       => __( 'Title', 'ayyash-addons-pro' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( '[gallery id="123" size="medium"]', 'ayyash-addons-pro' ),
			]
		);
		$this->add_control(
			'slide_list',
			[
				'label'       => __( 'Slides', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ slide_shortcode }}}',
			]
		);
		$this->end_controls_section();

		//slider
		$this->render_slider_controller();

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

		$swiper_class = Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';

		$this->add_render_attribute( [
			'ayyash_addons_slider'         => $this->get_slider_attributes( $settings ),
			'ayyash_addons_slider_wrapper' => [
				'class' => 'ayyash-addons-shortcode-slider-wrapper-inside ayyash-addons-swiper-wrapper ' . $swiper_class,
			],

		] );

		?>
		<style>
			.elementor-widget-ayyash-shortcode-slider .slider-preloader {
				background-color: #fff;
				position: absolute;
				left: 0;
				top: 0;
				height: 100%;
				width: 100%;
				display: flex;
				justify-content: center;
				align-items: center;
				z-index: 9;
			}
			.elementor-widget-ayyash-shortcode-slider .slider-preloader .preloader-content{
				font-size: 24px;
				margin:auto;
			}
		</style>
		<div class="slider-preloader">
			<div class="preloader-content"><?php esc_html_e(' Loading...', 'ayyash-addons-pro'); ?></div>
		</div>
		<div class="ayyash-addons-shortcode-slider-wrapper ayyash-addons-<?php echo str_replace( 'ayyash-', '', $this->get_name() ); // phpcs:ignore ?>">
			<?php
			$this->render_slider_wrapper_header();
			foreach ( $settings['slide_list'] as $item ) {
				$this->render_slide( $item, $settings );
			}
			$this->render_slider_wrapper_footer();
			?>
		</div>
		<?php
	}

	//slider wrapper
	protected function render_slider_wrapper_header() {
		?>
		<div <?php $this->print_render_attribute_string( 'ayyash_addons_slider_wrapper' );
			$this->print_render_attribute_string( 'ayyash_addons_slider' );
			?> >
			<div class="swiper-wrapper">
		<?php
	}
	protected function render_slider_wrapper_footer() { ?>
			</div>
		</div>
		<?php
	}
	//single slide
	protected function render_slide( $item, $settings ) {
		$this->render_slide_header();
		$this->render_shortcode( $item['slide_shortcode'] );
		$this->render_slide_footer();
	}

	protected function render_slide_header() {
		?>
		<div class="<?php echo esc_attr( 'ayyash-shortcode-slide__item swiper-slide' ); ?>" >
		<?php
	}
	protected function render_slide_footer() {
		?></div><?php
	}

	protected function render_shortcode( $shortcode ) {
		echo do_shortcode( shortcode_unautop( $shortcode ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	protected function render_slide_count( $settings ) {
		?>
		<span class="slides-count">
			<span class="active-slide-index"><?php echo esc_html__('1', 'ayyash-addons-pro'); ?></span>
			/ <?php echo esc_html( count( $settings['slide_list'] ) );  //phpcs:ignore  ?>
		</span>
		<?php
	}

	//slider settings
	/**
	 * Render Slider Controls.
	 *
	 * @param array $options
	 */
	protected function render_slider_controller( array $options = [] ) {
		$defaults = [
			'settings_section'           => [
				'condition'  => [],
				'conditions' => [],
			],
			'slider_style_section'       => [
				'condition' => [
					'navigation' => [
						'dots',
						'arrows',
						'both',
					],
				],
			],

			'slides_to_show'             => [
				'min'         => 1,
				'max'         => 8,
				'default'     => 3,
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE_EXTRA => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET_EXTRA => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_LAPTOP => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_DESKTOP => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_WIDESCREEN => [
						'required' => false,
						'default'  => 2,
					],
				],
			],
			'slides_to_scroll'           => [
				'min'         => 1,
				'max'         => 8,
				'default'     => 1,
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE_EXTRA => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET_EXTRA => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_LAPTOP => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_DESKTOP => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_WIDESCREEN => [
						'required' => false,
						'default'  => 1,
					],
				],
			],
			'vertical_slider'            => 'false',
			'autoplay'                   => 'yes',
			'slides_center'              => 'false',
			'slides_loop'                => 'true',
			'mousewheel'                 => 'false',
			'autoplay_delay'             => [
				'min'     => '',
				'max'     => '',
				'default' => 5000,
			],
			'spacing_between'            => [
				'min'         => '',
				'max'         => '',
				'default'     => 20,
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE_EXTRA => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET_EXTRA => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_LAPTOP => [
						'required' => false,
						'default'  => 1,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_DESKTOP => [
						'required' => false,
						'default'  => 2,
					],
					Breakpoints_Manager::BREAKPOINT_KEY_WIDESCREEN => [
						'required' => false,
						'default'  => 1,
					],
				],
			],
			'direction'                  => 'ltr',
			'effect'                     => 'slide',

			// style
			'arrows_position'            => 'inside',
			'arrow_size'                 => [
				'range'     => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [ '{{WRAPPER}} .elementor-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};' ],
			],
			'arrow_radius'               => [
				'range'     => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [ '{{WRAPPER}} .elementor-swiper-button' => 'border-radius: {{Size}}{{UNIT}};' ],
			],
			'arrows_color'               => [
				'default'   => '',
				'selectors' => [ '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'color: {{VALUE}} !important;' ],
			],
			'arrows_color_hover'         => [
				'default'   => '',
				'selectors' => [ '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next:hover' => 'color: {{VALUE}} !important;' ],
			],
			'dots_position_prefix_class' => '',
			'dots_color'                 => [
				'default'   => '',
				'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};opacity:1' ],
			],
			'dots_active_color'          => [
				'default'   => '',
				'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background: {{VALUE}};' ],
			],
			'dots_size'                  => [
				'range'     => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ],
			],
		];

		$options = ayyash_addons_parse_args_recursive( $options, $defaults );

		$this->start_controls_section(
			'slider_settings_section',
			[
				'label'      => __( 'Slider Options', 'ayyash-addons-pro' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'condition'  => $options['settings_section']['condition'],
				'conditions' => $options['settings_section']['conditions'],
			]
		);
		$this->add_responsive_control(
			'slides_to_show',
			[
				'label'       => __( 'Slides To Show', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => $options['slides_to_show']['min'],
				'max'         => $options['slides_to_show']['max'],
				'default'     => $options['slides_to_show']['default'],
				'required'    => true,
				'device_args' => $options['slides_to_show']['device_args'],
			]
		);
		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'label'       => __( 'Slides to Scroll', 'ayyash-addons-pro' ),
				'description' => __( 'Set how many slides are scrolled per swipe.', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => $options['slides_to_scroll']['min'],
				'max'         => $options['slides_to_scroll']['max'],
				'default'     => $options['slides_to_scroll']['default'],
				'required'    => true,
				'device_args' => $options['slides_to_scroll']['device_args'],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'   => __( 'Autoplay', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $options['autoplay'],
				'options' => [
					'yes' => __( 'Yes', 'ayyash-addons-pro' ),
					'no'  => __( 'No', 'ayyash-addons-pro' ),
				],
			]
		);
		$this->add_control(
			'slides_center',
			[
				'label'   => __( 'Center Mode', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $options['slides_center'],
				'options' => [
					'true'  => __( 'Yes', 'ayyash-addons-pro' ),
					'false' => __( 'No', 'ayyash-addons-pro' ),
				],
			]
		);
		$this->add_control(
			'slides_loop',
			[
				'label'   => __( 'Loop', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $options['slides_loop'],
				'options' => [
					'true'  => __( 'Yes', 'ayyash-addons-pro' ),
					'false' => __( 'No', 'ayyash-addons-pro' ),
				],
			]
		);
		$this->add_control(
			'mousewheel',
			[
				'label'   => __( 'Mousewheel', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $options['mousewheel'],
				'options' => [
					'true'  => __( 'Yes', 'ayyash-addons-pro' ),
					'false' => __( 'No', 'ayyash-addons-pro' ),
				],
			]
		);
		$this->add_control(
			'autoplay_delay',
			[
				'label'     => __( 'Autoplay Speed (ms)', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => $options['autoplay_delay']['min'],
				'max'       => $options['autoplay_delay']['max'],
				'default'   => $options['autoplay_delay']['default'],
				'condition' => [ 'autoplay' => 'yes' ],
			]
		);
		$this->add_responsive_control(
			'spacing_between',
			[
				'label'   => __( 'Distance between slides in px', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => $options['spacing_between']['min'],
				'max'     => $options['spacing_between']['max'],
				'default' => $options['spacing_between']['default'],
			]
		);
		$this->add_control(
			'direction',
			[
				'label'   => __( 'Direction', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $options['direction'],
				'options' => [
					'ltr' => __( 'Left', 'ayyash-addons-pro' ),
					'rtl' => __( 'Right', 'ayyash-addons-pro' ),
				],
			]
		);
		$this->add_control(
			'effect',
			[
				'label'       => __( 'Effect', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => $options['effect'],
				'options'     => [
					'slide' => __( 'Slide', 'ayyash-addons-pro' ),
					'fade'  => __( 'Fade', 'ayyash-addons-pro' ),
				],
				'description' => __( 'Fade effect works when "Slides to Show" is 1', 'ayyash-addons-pro' ),
			]
		);
		$this->end_controls_section();

		// style navigation

	}

	/**
	 * Render Slider attributes.
	 *
	 * @param array $options
	 */
	protected function get_slider_attributes( $settings = [], $spacing_between = 0 ): array {
		if ( empty( $settings ) ) {
			$settings = $this->get_settings_for_display();
		}

		$unique_id = uniqid( time() );

		$this->slider_dot  = wp_unique_id( 'nav-dot-' ) . '-' . $unique_id;
		$this->slider_next = wp_unique_id( 'nav-next-' ) . '-' . $unique_id;
		$this->slider_prev = wp_unique_id( 'nav-prev-' ) . '-' . $unique_id;

		return array_merge(
			[
				'data-slides-center'    => esc_attr( $settings['slides_center'] ),
				'data-autoplay'         => esc_attr( $settings['autoplay'] ),
				'data-autoplay-delay'   => esc_attr( $settings['autoplay_delay'] ),
				'data-effect'           => esc_attr( $settings['effect'] ),
				'data-slides-loop'      => esc_attr( $settings['slides_loop'] ),
				'data-mousewheel'       => esc_attr( $settings['mousewheel'] ),
				'dir'                   => esc_attr( $settings['direction'] ),
				'data-slides-dot'       => esc_attr( $this->slider_dot ),
				'data-slides-next'      => esc_attr( $this->slider_next ),
				'data-slides-prev'      => esc_attr( $this->slider_prev ),
				'data-slides-per-view'  => esc_attr( $settings['slides_to_show'] ),
				'data-slides-per-group' => esc_attr( $settings['slides_to_scroll'] ),
				'data-space-between'    => esc_attr( $settings['spacing_between'] ),
			],
			ayyash_addons_extract_screen_settings( $settings, [
				'key'        => 'slides_to_show',
				'key_prefix' => 'data-slides-per-view-',
			] ),
			ayyash_addons_extract_screen_settings( $settings, [
				'key'        => 'slides_to_scroll',
				'key_prefix' => 'data-slides-per-group-',
			] ),
			ayyash_addons_extract_screen_settings( $settings, [
				'key'        => 'spacing_between',
				'key_prefix' => 'data-space-between-',
			] )
		);

	}

	/**
	 * Slider Nav.
	 *
	 * @param array $settings settings
	 */
	protected function slider_pagination( array $settings = [] ) {
		if ( empty( $settings ) ) {
			$settings = $this->get_settings_for_display();
		}
		?>
		<div class="slider-scroll-pagination">
			<div class="swiper-scrollbar"></div>
			<div class="swiper-pagination <?php echo esc_attr( $this->slider_dot ); ?>"></div>
		</div>
		<?php

	}

}

<?php

namespace AyyashAddonsPro\Widgets;

use AyyashAddons\Ayyash_Addons_Slider_Controller;
use AyyashAddonsPro\Ayyash_Pro_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class AyyashAddons_Style_Woocommerce_Testimonial_Carousel_V2 extends Ayyash_Pro_Widget {

	//use Ayyash_Addons_Slider_Controller;

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
		return 'ayyash-woocommerce-testimonial-carousel-v2';
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
		return __( 'Testimonial V2', 'ayyash-addons-pro' );
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
			'ayyash-addons-pro-woocommerce-testimonial-carousel-v2',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'ayyash-addons-pro-woocommerce-testimonial-carousel-v2',
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

		$this->start_controls_section(
			'testimonial_section',
			[
				'label' => __( 'Testimonial', 'ayyash-addons-pro' ),
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

		//content controls
		$this->add_responsive_control(
			'testimonial_alignment',
			[
				'label'          => esc_html__( 'Alignment', 'ayyash-addons-pro' ),
				'type'           => Controls_Manager::CHOOSE,
				'default'        => 'center',
				'options'        => [
					'left'   => [
						'title' => esc_html__( 'Left', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'ayyash-addons-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .ayyash-addons-testimonial__item' => 'text-align: {{VALUE}}',
				],
				'style_transfer' => true,
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'testimonial_image_size',
				// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `testimonial_image_size` and `testimonial_image_custom_dimension`.
				'default' => 'full',
			]
		);
		$this->add_control(
			'testimonial_icon',
			[
				'label'   => __( 'Icon', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-quote-left',
					'library' => 'solid',
				],
			]
		);

		$repeater = new repeater();
		$repeater->add_control(
			'testimonial_image',
			[
				'label'   => __( 'Choose Image', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$repeater->add_control(
			'testimonial_desc',
			[
				'label'       => __( 'Description', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Add description text here', 'ayyash-addons-pro' ),
			]
		);
		$repeater->add_control(
			'testimonial_name',
			[
				'label'       => __( 'Name', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Add heading text here', 'ayyash-addons-pro' ),
			]
		);

		$this->add_control(
			'testimonial_list',
			[
				'label'       => __( 'Testimonials', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'separator'   => 'before',
				'default'     => [
					[
						'testimonial_name' => __( 'Rita Ford', 'ayyash-addons-pro' ),
						'testimonial_desc' => __( 'I used these to replace my original & aging Honda push pins. These fit snugly and secure well. They are tight so on removing I sprayed them with wd40, reinstalling them they retained their integrity', 'ayyash-addons-pro' ),
					],
					[
						'testimonial_name' => __( 'Rima Ford', 'ayyash-addons-pro' ),
						'testimonial_desc' => __( 'I used these to replace my original & aging Honda push pins. These fit snugly and secure well. They are tight so on removing I sprayed them with wd40, reinstalling them they retained their integrity', 'ayyash-addons-pro' ),
					],
					[
						'testimonial_name' => __( 'Asmeer', 'ayyash-addons-pro' ),
						'testimonial_desc' => __( 'I used these to replace my original & aging Honda push pins. These fit snugly and secure well. They are tight so on removing I sprayed them with wd40, reinstalling them they retained their integrity', 'ayyash-addons-pro' ),
					],
				],
				'title_field' => '{{{ testimonial_name }}}',
			]
		);

		$this->end_controls_section();

		//style controller
		$this->start_controls_section(
			'testimonial_style_section',
			[
				'label' => __( 'Testimonials', 'ayyash-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->__thumbnail();
		$this->__icon();
		$this->__title();
		$this->__content();
		$this->end_controls_section();

		// slider controller
		$this->start_controls_section(
			'section_slider_setting',
			[
				'label' => __( 'Slider Options', 'ayyash-addons-pro' ),
				'type'  => Controls_Manager::SECTION,
			]
		);

		$this->add_responsive_control(
			'thumb_slides_per_view',
			[
				'label'              => __( 'Per View', 'ayyash-addons-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 7,
				'tablet_default'     => 3,
				'mobile_default'     => 1,
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'thumb_space_between',
			[
				'label'              => __( 'Space Between Thumbs', 'ayyash-addons-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 10,
				'tablet_default'     => 10,
				'mobile_default'     => 5,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'slider_speed',
			[
				'label'       => __( 'Speed', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 500,
				'description' => __( 'Duration of transition between slides (in ms)', 'ayyash-addons-pro' ),
				'range'       => [
					'px' => [
						'min'  => 1000,
						'max'  => 10000,
						'step' => 500,
					],
				],
			]
		);

		$this->add_control(
			'slider_autoplay',
			[
				'label'     => __( 'Autoplay', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Yes', 'ayyash-addons-pro' ),
				'label_off' => __( 'No', 'ayyash-addons-pro' ),
			]
		);

		$this->add_control(
			'slider_autoplay_duration',
			[
				'label'       => __( 'Duration', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 5000,
				'description' => __( 'Delay between transitions (in ms)', 'ayyash-addons-pro' ),
				'range'       => [
					'px' => [
						'min'  => 300,
						'max'  => 3000,
						'step' => 300,
					],
				],
				'condition'   => [
					'slider_autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_direction',
			[
				'label'   => __( 'Direction', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ltr',
				'options' => [
					'ltr' => __( 'Left', 'ayyash-addons-pro' ),
					'rtl' => __( 'Right', 'ayyash-addons-pro' ),
				],
			]
		);

		$this->add_responsive_control(
			'slider_space_between',
			[
				'label'              => __( 'Space Between Slides', 'ayyash-addons-pro' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => [
					'size' => 10,
				],
				'tablet_default'     => [
					'size' => 15,
				],
				'mobile_default'     => [
					'size' => 10,
				],
				'range'              => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 5,
					],
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'slider_loop',
			[
				'label'     => __( 'Loop', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Yes', 'ayyash-addons-pro' ),
				'label_off' => __( 'No', 'ayyash-addons-pro' ),
				'separator' => 'after',
			]
		);

		$this->add_control(
			'slider_navigation',
			[
				'label'        => __( 'Arrows', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'ayyash-addons-pro' ),
				'label_off'    => __( 'No', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
			]
		);

		$this->add_responsive_control(
			'navigation_icon_font_size',
			[
				'label'          => __( 'Icons Font Size', 'ayyash-addons-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'size' => 25,
				],
				'tablet_default' => [
					'size' => 25,
				],
				'mobile_default' => [
					'size' => 25,
				],
				'range'          => [
					'px' => [
						'min'  => 0,
						'max'  => 400,
						'step' => 1,
					],
				],
				'condition'      => [
					'slider_navigation' => 'yes',
				],
				'selectors'      => [
					'{{WRAPPER}} .ayyash-addon-button i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'navigation_icon_t_space',
			[
				'label'          => __( 'Icons Top', 'ayyash-addons-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'size' => 33,
				],
				'tablet_default' => [
					'size' => 33,
				],
				'mobile_default' => [
					'size' => 33,
				],
				'range'          => [
					'px' => [
						'min'  => 0,
						'max'  => 400,
						'step' => 1,
					],
				],
				'condition'      => [
					'slider_navigation' => 'yes',
				],
				'selectors'      => [
					'{{WRAPPER}} .ayyash-addon-button' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'navigation_icon_left',
			[
				'label'            => __( 'Icon Prev', 'ayyash-addons-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => [
					'value'   => 'fa fa-angle-left',
					'library' => 'fa-solid',
				],
				'condition'        => [
					'slider_navigation' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'navigation_icon_left_space',
			[
				'label'          => __( 'Icon Prev Space', 'ayyash-addons-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'size' => -100,
				],
				'tablet_default' => [
					'size' => -10,
				],
				'mobile_default' => [
					'size' => 10,
				],
				'range'          => [
					'px' => [
						'min'  => -400,
						'max'  => 400,
						'step' => 1,
					],
				],
				'condition'      => [
					'slider_navigation' => 'yes',
				],
				'selectors'      => [
					'{{WRAPPER}} .ayyash-addons-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'navigation_icon_right',
			[
				'label'            => __( 'Icon Next', 'ayyash-addons-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => [
					'value'   => 'fa fa-angle-right',
					'library' => 'fa-solid',
				],
				'condition'        => [
					'slider_navigation' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'navigation_icon_right_space',
			[
				'label'          => __( 'Icon Next Space', 'ayyash-addons-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'size' => -100,
				],
				'tablet_default' => [
					'size' => -10,
				],
				'mobile_default' => [
					'size' => 10,
				],
				'range'          => [
					'px' => [
						'min'  => -400,
						'max'  => 400,
						'step' => 1,
					],
				],
				'condition'      => [
					'slider_navigation' => 'yes',
				],
				'selectors'      => [
					'{{WRAPPER}} .ayyash-addons-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'slider_keyboard',
			[
				'label'        => __( 'Keyboard Control', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'ayyash-addons-pro' ),
				'label_off'    => __( 'No', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	protected function __thumbnail() {
		$this->add_control(
			'thumb_style',
			[
				'label'     => __( 'Image', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'thumb_size',
			[
				'label'      => __( 'Image Size', 'ayyash-addons-pro' ),
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
					'size' => 90,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-testimonial__thumbnail img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_control(
			'thumb_border_radius',
			[
				'label'      => __( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-testimonial__thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'thumb_gap',
			[
				'label'      => __( 'Spacing', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit'   => 'px',
					'top'    => 0,
					'bottom' => 22,
					'left'   => 0,
					'right'  => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-testimonial__thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	protected function __title() {
		$this->add_control(
			'title_style',
			[
				'label'     => __( 'Title', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-testimonial__title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-testimonial__title',
			]
		);
		$this->add_control(
			'title_gap',
			[
				'label'      => __( 'Spacing', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-testimonial__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	protected function __content() {
		$this->add_control(
			'content_style',
			[
				'label'     => __( 'Content', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-testimonial__content p' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-testimonial__content p',
			]
		);
	}

	protected function __icon() {
		$this->add_control(
			'icon_style',
			[
				'label'     => __( 'Icon', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-testimonial__content i'        => 'color: {{VALUE}}',
					'{{WRAPPER}} .ayyash-addons-testimonial__content svg path' => 'fill: {{VALUE}} !important',
				],
			]
		);
		$this->add_control(
			'icon_size',
			[
				'label'      => __( 'Icon Size', 'ayyash-addons-pro' ),
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
					'size' => 66,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-testimonial__content i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-addons-testimonial__content svg' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_gap',
			[
				'label'      => __( 'Spacing', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit'   => 'px',
					'top'    => 15,
					'left'   => 0,
					'bottom' => 0,
					'right'  => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-testimonial__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
		if ( ! class_exists( 'WooCommerce' ) ) {
			printf( '<div class="ayyash-addons-alert alert-warning">%s</div>', esc_html__( 'Please Install/Activate Woocommerce Plugin.', 'ayyash-addons-pro' ) );

			return;
		}
		$settings = $this->get_settings_for_display();

		$swiper_class = Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';

		$item_class            = 'ayyash-addons-testimonial__item swiper-slide';
		$wrapper_class         = 'ayyash-addons-wc-ttc-wrapper-inside ayyash-addons-slider-header-init ' . $swiper_class;
		$wrapper_class_content = 'ayyash-addons-content-wrapper-inside ayyash-addons-slider-content-init ' . $swiper_class;

		$thumb_navigation   = $settings['slider_navigation'];
		$slides_per_view['desktop'] = $settings['thumb_slides_per_view'] !== '' ? $settings['thumb_slides_per_view'] : 1;
		$slides_per_view['tablet']  = $settings['thumb_slides_per_view_tablet'] !== '' ? $settings['thumb_slides_per_view_tablet'] : 1;
		$slides_per_view['mobile']  = $settings['thumb_slides_per_view_mobile'] !== '' ? $settings['thumb_slides_per_view_mobile'] : 1;

		$space_between['desktop'] = $settings['thumb_space_between'] !== '' ? $settings['thumb_space_between'] : 5;
		$space_between['tablet']  = $settings['thumb_space_between_tablet'] !== '' ? $settings['thumb_space_between_tablet'] : 15;
		$space_between['mobile']  = $settings['thumb_space_between_mobile'] !== '' ? $settings['thumb_space_between_mobile'] : 10;

		$slider_data = $this->get_swiper_settings( $settings );


		$this->add_render_attribute( [
			'ayyash_addons_wrap' => [
				'class' => $wrapper_class,
			],
		] );

		$this->add_render_attribute( [
			'testimonial_item' => [
				'class' => $item_class,
			],
		] );

		$this->add_render_attribute( [
			'content_wrap' => [
				'class' => $wrapper_class_content,
			],
		] );

		$this->add_render_attribute([
			'ayyash-testimonial-wrapper' => [
				'class'                => 'ayyash-testimonial-wrapper ayyash-addons-' . str_replace( 'ayyash-', '', $this->get_name() ),
				'data-swiper-settings' => wp_json_encode( $slider_data ),
				'data-id'              => $this->get_id(),
			],
		]);


		if ( ! empty( $slides_per_view ) ) {
			$this->add_render_attribute( 'ayyash-testimonial-wrapper', 'data-slides-per-view', wp_json_encode( $slides_per_view, JSON_NUMERIC_CHECK ) );
		}

		if ( ! empty( $space_between ) ) {
			$this->add_render_attribute( 'ayyash-testimonial-wrapper', 'data-space', wp_json_encode( $space_between, JSON_NUMERIC_CHECK ) );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'ayyash-testimonial-wrapper' ); ?> >
			<div class="ayyash-addons-wc-ttc-wrapper">
				<?php
				$this->render_testimonial_image_wrapper_header( $settings );
				foreach ( $settings['testimonial_list'] as $list ) {
					$this->render_testimonial_slide( $list, $settings );
				}
				$this->render_testimonial_image_wrapper_footer( $settings );
				?>
			</div>
			<?php if ( $thumb_navigation === 'yes' ) { ?>
				<div class="ayyash-addons-slider-nav">
					<div class = "ayyash-addon-button ayyash-addons-button-prev">
						<?php Icons_Manager::render_icon( $settings['navigation_icon_left'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>

					<div class = "ayyash-addon-button ayyash-addons-button-next">
						<?php Icons_Manager::render_icon( $settings['navigation_icon_right'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
	}

	protected function render_testimonial_image_wrapper_header( $settings ) {
		?>
		<div <?php $this->print_render_attribute_string( 'ayyash_addons_wrap' );
		?>>
		<div class="swiper-wrapper">
		<?php
	}

	protected function render_testimonial_image_wrapper_footer( $settings ) { ?>
		</div>
		</div>
		<?php
	}

	protected function render_testimonial_content_wrapper_header( $settings ) {
		?>
		<div <?php $this->print_render_attribute_string( 'content_wrap' );
		?>>
		<div class="swiper-wrapper">
		<?php
	}

	protected function render_testimonial_content_wrapper_footer( $settings ) {
		?>
		</div>
		</div>
		<?php
	}

	protected function render_testimonial_slide( $testimonial, $settings ) {
		$this->render_testimonial_header( $settings );
		$this->render_thumbnail( $testimonial['testimonial_image'], $settings );
		$this->render_content_header();
		$this->render_testimonial_icon( $testimonial );
		$this->render_title( $testimonial['testimonial_name'] );
		$this->render_testimonial_content( $testimonial['testimonial_desc'] );
		$this->render_content_footer();
		$this->render_testimonial_footer();
	}

	protected function render_thumbnail( $thumbnail, $settings ) {

		if ( empty( $thumbnail ) ) {
			return;
		}

		$setting_key              = 'testimonial_image_size';
		$settings[ $setting_key ] = [
			'id' => $thumbnail['id'],
		];

		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );
		if ( empty( $thumbnail_html ) ) {
			$thumbnail_html = '<img src="' . esc_url( $thumbnail['url'] ) . '"  alt="image"/>';
		}

		?>
		<div class="ayyash-addons-testimonial__thumbnail">
			<?php echo wp_kses_post( $thumbnail_html ); ?>
		</div>
		<?php

	}

	protected function render_testimonial_icon() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="ayyash-addons-testimonial__icon">
			<?php Icons_Manager::render_icon( $settings['testimonial_icon'], [ 'aria-hidden' => 'true' ] ); ?>
		</div>
		<?php
	}

	protected function render_testimonial_content( $content ) {
		if ( empty( $content ) ) {
			return;
		}
		?>
		<p><?php echo esc_html( $content ) ?></p>
		<?php
	}

	protected function render_title( $title ) {

		if ( empty( $title ) ) {
			return;
		}
		?>
		<div class="ayyash-addons-testimonial__title"><?php echo esc_html( $title ); ?></div>
		<?php
	}

	protected function render_content_header() {
		?><div class="ayyash-addons-testimonial__content"><?php
	}

	protected function render_content_footer() {
		?></div><?php
	}

	protected function render_testimonial_header() {

		?><div  <?php $this->print_render_attribute_string( 'testimonial_item' ); ?> ><?php
	}

	protected function render_testimonial_footer() {
		?></div><?php
	}

	public function get_swiper_settings( $settings ) {

		$slider_data['speed'] = $settings['slider_speed'];

		if ( $settings['slider_autoplay'] === 'yes' ) {
			$slider_data['autoplay']['duration'] = $settings['slider_autoplay_duration'];

			if ( $settings['slider_direction'] === 'rtl' ) {
				$slider_data['autoplay']['reverseDirection'] = true;
			}
			if ( $settings['slider_direction'] === 'ltr' ) {
				$slider_data['autoplay']['reverseDirection'] = false;
			}

			$slider_data['autoplay']['slider_direction'] = $settings['slider_direction'];

		} else {
			$swiper_data['autoplay'] = false;
		}

		$slider_data['spaceBetween']['desktop'] = $settings['slider_space_between']['size'];
		$slider_data['spaceBetween']['tablet']  = $settings['slider_space_between_tablet']['size'];
		$slider_data['spaceBetween']['mobile']  = $settings['slider_space_between_mobile']['size'];

		if ( ! empty( $settings['slider_loop'] ) ) {
			$slider_data['loop'] = $settings['slider_loop'];
		}

		if ( ! empty( $settings['slider_navigation'] ) ) {
			$slider_data['navigation'] = $settings['slider_navigation'];
		}

		$settings['slider_keyboard'] === 'yes' ? $slider_data['keyboard'] = true : $slider_data['keyboard'] = false;

		return $slider_data;
	}


}

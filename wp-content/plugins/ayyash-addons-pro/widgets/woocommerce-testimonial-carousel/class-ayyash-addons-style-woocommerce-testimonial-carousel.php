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
class AyyashAddons_Style_Woocommerce_Testimonial_Carousel extends Ayyash_Pro_Widget {

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
		return 'ayyash-woocommerce-testimonial-carousel';
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
		return __( 'Testimonial', 'ayyash-addons-pro' );
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
			'ayyash-addons-pro-woocommerce-testimonial-carousel',
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
		$this->add_control(
			'testimonial_image_position',
			[
				'label'        => esc_html__( 'Image Position', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'left',
				'options'      => [
					'left'   => esc_html__( 'Left', 'ayyash-addons-pro' ),
					'top'    => esc_html__( 'Top', 'ayyash-addons-pro' ),
					'bottom' => esc_html__( 'Bottom', 'ayyash-addons-pro' ),
					'right'  => esc_html__( 'Right', 'ayyash-addons-pro' ),
				],
				'prefix_class' => 'ayyash-testimonial-image-',
			]
		);
		$this->add_responsive_control(
			'testimonial_alignment',
			[
				'label'          => esc_html__( 'Alignment', 'ayyash-addons-pro' ),
				'type'           => Controls_Manager::CHOOSE,
				'default'        => 'left',
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
					'{{WRAPPER}} .ayyash-testimonial__item' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .testimonial-rating ' => 'justify-content: {{VALUE}}',
				],
				'style_transfer' => true,
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'testimonial_image_size', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `testimonial_image_size` and `testimonial_image_custom_dimension`.
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
			'testimonial_rating',
			[
				'label'   => __( 'Rating', 'ayyash-addons-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 5,
				'step'    => 1,
				'default' => 5,
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
		$repeater->add_control(
			'testimonial_desig',
			[
				'label'       => __( 'Designation', 'ayyash-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Software Engineer', 'ayyash-addons-pro' ),
				'placeholder' => __( 'Add designation here', 'ayyash-addons-pro' ),
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
				],
				'title_field' => '{{{ testimonial_name }}}',
			]
		);
		$this->add_control(
			'enable_testimonial_slider',
			[
				'label'        => esc_html__( 'Enable Slider?', 'ayyash-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'yes'          => esc_html__( 'Yes', 'ayyash-addons-pro' ),
				'no'           => esc_html__( 'No', 'ayyash-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);
		$this->add_control(
			'testimonial_column',
			[
				'label'     => __( 'Column', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'1' => __( '1', 'ayyash-addons-pro' ),
					'2' => __( '2', 'ayyash-addons-pro' ),
					'3' => __( '3', 'ayyash-addons-pro' ),
					'4' => __( '4', 'ayyash-addons-pro' ),
					'5' => __( '5', 'ayyash-addons-pro' ),
					'6' => __( '6', 'ayyash-addons-pro' ),
				],
				'default'   => '2',
				'condition' => [ 'enable_testimonial_slider!' => 'yes' ],
			]
		);
		$this->add_control(
			'testimonial_gap',
			[
				'label'      => __( 'Gap', 'ayyash-addons-pro' ),
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
					'size' => 25,
				],
				'condition'  => [ 'enable_testimonial_slider!' => 'yes' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-wc-ttc-wrapper-inside.testimonial_gap' => 'gap: {{SIZE}}{{UNIT}};',
				],
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
		$this->__content();
		$this->__rating();
		$this->__title();
		$this->__designation();
		$this->end_controls_section();

		//slider
		$this->render_slider_controller( [
			'settings_section'     => [ 'condition' => [ 'enable_testimonial_slider' => 'yes' ] ],
			'slider_style_section' => [ 'condition' => [ 'enable_testimonial_slider' => 'yes' ] ],
			'slides_to_show'       => [
				'default' => 1,
			],
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
				'size_units' => [ 'px', '%', 'custom' ],
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
					'size' => 224,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash_testimonial_thumbnail img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_control(
			'thumb_border_radius',
			[
				'label'      => __( 'Border Radius', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash_testimonial_thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'thumb_gap',
			[
				'label'      => __( 'Spacing', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
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
					'size' => 30,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-testimonial__item' => 'gap: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .ayyash_testimonial_title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .ayyash_testimonial_title',
			]
		);
		$this->add_control(
			'title_gap',
			[
				'label'      => __( 'Spacing', 'ayyash-addons-pro' ),
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
					'{{WRAPPER}} .ayyash_testimonial_title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .ayyash_testimonial__content p' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .ayyash_testimonial__content p',
			]
		);
		$this->add_control(
			'content_gap',
			[
				'label'      => __( 'Spacing', 'ayyash-addons-pro' ),
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
					'size' => 25,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash_testimonial__content p' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	protected function __designation() {
		$this->add_control(
			'designation_style',
			[
				'label'     => __( 'Designation', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'designation_color',
			[
				'label'     => __( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash_testimonial_desig' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'designation_typography',
				'selector' => '{{WRAPPER}} .ayyash_testimonial_desig',
			]
		);
		$this->add_control(
			'designation_gap',
			[
				'label'      => __( 'Spacing', 'ayyash-addons-pro' ),
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
					'{{WRAPPER}} .ayyash_testimonial_desig' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	protected function __rating() {
		$this->add_control(
			'rating_style',
			[
				'label'     => __( 'Rating', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'rating_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .testimonial-rating' => '--rating-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'rating_size',
			[
				'label'      => __( 'Rating Size', 'ayyash-addons-pro' ),
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
					'size' => 14,
				],
				'selectors'  => [
					'{{WRAPPER}} .testimonial-rating .rating i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'rating_gap',
			[
				'label'      => __( 'Space Between', 'ayyash-addons-pro' ),
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
					'size' => 2,
				],
				'selectors'  => [
					'{{WRAPPER}} .testimonial-rating' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'rating_bottom_gap',
			[
				'label'      => __( 'Spacing', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit' => 'px',
					'size' => 15,
				],
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
					'{{WRAPPER}} .testimonial-rating' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
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
					'{{WRAPPER}} .ayyash_testimonial__content i' => 'color: {{VALUE}}',
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
					'size' => 23,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash_testimonial__content i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_gap',
			[
				'label'      => __( 'Spacing', 'ayyash-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit' => 'px',
					'size' => 15,
				],
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
					'{{WRAPPER}} .testimonial-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
			printf( '<div class="ayyash-addons-alert alert-warning">%s</div>', __( 'Please Install/Activate Woocommerce Plugin.', 'ayyash-addons-pro' ) );

			return;
		}
		$settings = $this->get_settings_for_display();

		$swiper_class = Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';

		$this->add_render_attribute( [
			'ayyash_addons_slider'         => $this->get_slider_attributes( $settings ),
			'ayyash_addons_slider_wrapper' => [
				'class' => 'ayyash-addons-swiper-wrapper ' . $swiper_class,
			],

		] );

		$item_class    = 'ayyash-testimonial__item';
		$wrapper_class = 'ayyash-addons-wc-ttc-wrapper-inside';

		if ( 'yes' === $settings['enable_testimonial_slider'] ) {
			$item_class    .= ' swiper-slide';
			$wrapper_class .= ' ayyash-addons-swiper-wrapper ' . $swiper_class;
		} else {
			$wrapper_class .= ' ayyash-addons-column-' . $settings['testimonial_column'] . ' testimonial_gap';
		}

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

		?>
		<div
			class="ayyash-addons-wc-ttc-wrapper ayyash-addons-<?php echo str_replace( 'ayyash-', '', $this->get_name() ); // phpcs:ignore ?>">
			<?php
			$this->render_testimonial_wrapper_header( $settings );
			foreach ( $settings['testimonial_list'] as $list ) {
				$this->render_testimonial( $list, $settings );
			}
			$this->render_testimonial_wrapper_footer( $settings );
			?>
		</div>
		<?php
	}

	protected function render_testimonial_wrapper_header( $settings ) {
		?>
		<div <?php  $this->print_render_attribute_string( 'ayyash_addons_wrap' );

		if ( 'yes' === $settings['enable_testimonial_slider'] ) {
			$this->print_render_attribute_string( 'ayyash_addons_slider' );
		//	$this->print_slider_inline_css( $settings );
		}
		?>>
		<?php if ( 'yes' === $settings['enable_testimonial_slider'] ) { ?>
			<div class="swiper-wrapper">
		<?php } ?>
		<?php
	}

	protected function render_testimonial_wrapper_footer( $settings ) {
		if ( 'yes' === $settings['enable_testimonial_slider'] ) { ?>
			</div>
		<?php } ?>

		</div>
		<?php

		if ( 'yes' === $settings['enable_testimonial_slider'] ) {
			$this->slider_nav( $settings );
		}
	}

	protected function render_testimonial( $testimonial, $settings ) {
		$this->render_testimonial_header( $settings );
		$this->render_thumbnail( $testimonial['testimonial_image'], $settings );
		$this->render_content_header();
		$this->render_testimonial_icon( $testimonial );
		$this->render_testimonial_content( $testimonial['testimonial_desc'] );
		$this->render_rating( $testimonial['testimonial_rating'] );
		$this->render_title( $testimonial['testimonial_name'] );
		$this->render_designation( $testimonial['testimonial_desig'] );
		$this->render_content_footer();
		$this->render_testimonial_footer();

	}

	protected function render_thumbnail( $thumbnail, $settings ) {

		if ( empty( $thumbnail ) ) {
			return;
		}
		if ( 'none' === $settings['testimonial_image_position'] && ! Plugin::elementor()->editor->is_edit_mode() ) {
			return;
		}
		$setting_key              = 'testimonial_image_size';
		$settings[ $setting_key ] = [
			'id' => $thumbnail['id'],
		];

		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );
		if ( empty( $thumbnail_html ) ) {
			$thumbnail_html = '<img src="' . esc_url( $thumbnail['url'] ) . '"  alt="test"/>';
		}

		?>
		<div class="ayyash_testimonial_thumbnail">
			<?php echo wp_kses_post( $thumbnail_html ); ?>
		</div>
		<?php

	}

	protected function render_testimonial_icon() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="testimonial-icon">
			<?php Icons_Manager::render_icon( $settings['testimonial_icon'], [ 'aria-hidden' => 'true' ] ); ?>
		</div>
		<?php
	}

	protected function render_rating( $rating ) {
		?>
		<div class="testimonial-rating <?php echo esc_attr( ' rating-' . $rating ); ?>">
			<span class="rating"><i class="fas fa-star" aria-hidden="true"></i></span>
			<span class="rating"><i class="fas fa-star" aria-hidden="true"></i></span>
			<span class="rating"><i class="fas fa-star" aria-hidden="true"></i></span>
			<span class="rating"><i class="fas fa-star" aria-hidden="true"></i></span>
			<span class="rating"><i class="fas fa-star" aria-hidden="true"></i></span>
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
		<div class="ayyash_testimonial_title"><?php echo esc_html( $title ); ?></div>
		<?php
	}

	protected function render_designation( $designation ) {
		if ( empty( $designation ) ) {
			return;
		}
		?>
		<div class="ayyash_testimonial_desig"><?php echo esc_html( $designation ); ?></div>
		<?php
	}

	protected function render_content_header() {
		?><div class="ayyash_testimonial__content"><?php
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


}

<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 */
class AyyashAddons_Style_Product_Categories extends Ayyash_Widget {

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
		return 'ayyash-product-categories';
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
		return __( 'Product Categories V2', 'ayyash-addons' );
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
			'ayyash-addons-product-categories',
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
		return array( 'ayyash-widgets' );
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

		// section controller
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Categories', 'ayyash-addons' ),
			)
		);

		if ( ! ayyash_addons_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->plugin_dependency_alert( [
				'plugins' => [
					[
						'path' => 'woocommerce/woocommerce.php',
						'name' => __( 'WooCommerce', 'ayyash-addons' ),
						'slug' => 'woocommerce',
					],
				],
			] );

			return;
		}

		$this->add_control(
			'category_image',
			[
				'label'   => esc_html__( 'Choose Image', 'ayyash-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'category_image_size',
				// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `testimonial_image_size` and `testimonial_image_custom_dimension`.
				'default' => 'full',
			]
		);

		$this->add_control(
			'category_title',
			[
				'label'       => esc_html__( 'Category Title', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Men Collections', 'ayyash-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'category_title_tag',
			[
				'label'   => esc_html__( 'HTML Tag', 'ayyash-addons' ),
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
				'default' => 'h6',
			]
		);

		$this->add_control(
			'category_subtitle',
			[
				'label'       => esc_html__( 'Category Sub Title', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'GET 70% OFF', 'ayyash-addons' ),
				'label_block' => true,
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'category_subtitle_tag',
			[
				'label'   => esc_html__( ' HTML Tag', 'ayyash-addons' ),
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
				'default' => 'p',
			]
		);

		$this->add_control(
			'category_hover_text',
			[
				'label'       => esc_html__( 'Category Hover Text', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'View Collections', 'ayyash-addons' ),
				'label_block' => true,
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'cat_hover_icon',
			[
				'label'   => __( 'Icon', 'ayyash-addons' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-quote-left',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'category_hover_link',
			[
				'label'       => esc_html__( 'Link', 'ayyash-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'ayyash-addons' ),
				'options'     => [ 'url', 'is_external' ],
				'default'     => [
					'url' => '#',
				],
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		//style controller
		$this->start_controls_section(
			'categories_style_section',
			[
				'label' => __( 'Categories Styles', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->__thumbnail();
		$this->__title();
		$this->__subtitle();
		$this->__hovertitle();

		$this->end_controls_section();
	}

	protected function __thumbnail() {
		$this->add_control(
			'cat_thumb_style',
			[
				'label'     => __( 'Image', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'cat_thumb_border_radius',
			[
				'label'      => __( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default'    => [
					'unit'   => 'px',
					'top'    => 12,
					'right'  => 12,
					'bottom' => 12,
					'left'   => 12,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-categories__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	protected function __title() {
		$this->add_control(
			'cat_title_style',
			[
				'label'     => __( 'Title', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'cat_title_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-categories_title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'cat_title_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-product-categories_title',
			]
		);
		$this->add_control(
			'cat_title_gap',
			[
				'label'      => __( 'Spacing', 'ayyash-addons' ),
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
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-categories_title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	protected function __subtitle() {
		$this->add_control(
			'cat_subtitle_style',
			[
				'label'     => __( 'Sub Title', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'cat_subtitle_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-categories_subtitle' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'cat_subtitle_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-product-categories_subtitle',
			]
		);
		$this->add_control(
			'cat_subtitle_gap',
			[
				'label'      => __( 'Spacing', 'ayyash-addons' ),
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
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-product-categories_subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	protected function __hovertitle() {
		$this->add_control(
			'cat_hovertitle_style',
			[
				'label'     => __( 'Hover Title', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'cat_hovertitle_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-product-categories__hover a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'cat_hovertitle_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-product-categories__hover a',
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
		$settings = $this->get_settings_for_display();

		if ( ! ayyash_addons_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}

		?>
		<div
			class="ayyash-addons-wrapper ayyash-addons-<?php echo str_replace( 'ayyash-', '', $this->get_name() ); // phpcs:ignore ?>">
			<?php
			$this->render_product_cats_wrapper_header( $settings );
			$this->render_product_cats_wrapper( $settings );
			$this->render_product_cats_wrapper_footer( $settings );
			?>
		</div>
		<?php
	}

	protected function render_product_cats_wrapper_header() {
		?>
		<div class="ayyash-addons-product-categories-wrapper">

		<?php
	}

	protected function render_product_cats_wrapper_footer() {
		?>
		</div>

		<?php
	}

	protected function render_product_cats_wrapper( $settings ) {
		$this->render_product_cats_header();
		$this->render_product_cats_image( $settings );
		$this->render_product_cats_content_header();
		$this->render_product_cats_normal_header();
		$this->render_product_cats_subtitle( $settings );
		$this->render_product_cats_title( $settings );
		$this->render_product_cats_normal_footer();
		$this->render_product_cats_hover( $settings );
		$this->render_product_cats_content_footer();
		$this->render_product_cats_footer();
	}

	protected function render_product_cats_header() {
		?><div class="ayyash-addons-product-categories-wrapper-inside"><?php
	}

	protected function render_product_cats_footer() {
		?></div><?php
	}


	protected function render_product_cats_image( $settings ) {
		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'category_image_size', 'category_image' );
		?>
		<div class="ayyash-addons-product-categories__image">
			<?php echo wp_kses_post( $thumbnail_html ); ?>
		</div>
		<?php
	}

	protected function render_product_cats_content_header() {
		?><div class="ayyash-addons-product-categories__content"><?php
	}

	protected function render_product_cats_content_footer() {
		?></div><?php
	}

	protected function render_product_cats_normal_header() {
		?><div class="ayyash-addons-product-categories__normal"><?php
	}

	protected function render_product_cats_normal_footer() {
		?></div><?php
	}

	protected function render_product_cats_title( $settings ) {
		if ( empty( $settings['category_title_tag'] ) ) {
			return;
		}
		$title_tag = $settings['category_title_tag'];

		?>
		<<?php Utils::print_validated_html_tag( $title_tag ); ?> class="ayyash-addons-product-categories_title">
		<?php echo esc_html( $settings['category_title'] ); ?>
		</<?php Utils::print_validated_html_tag( $title_tag ); ?> >
		<?php
	}

	protected function render_product_cats_subtitle( $settings ) {
		if ( empty ( $settings['category_subtitle_tag'] ) ) {
			return;
		}
		$subtitle_tag = $settings['category_subtitle_tag'];

		?>
		<<?php Utils::print_validated_html_tag( $subtitle_tag ); ?> class="ayyash-addons-product-categories_subtitle">
		<?php echo esc_html( $settings['category_subtitle'] ); ?>
		</<?php Utils::print_validated_html_tag( $subtitle_tag ); ?>>
		<?php
	}

	protected function render_product_cats_hover( $settings ) {
		if ( empty( $settings['category_hover_text'] ) ) {
			return;
		}
		$hover_text = $settings['category_hover_text'];
		$hover_link = $settings['category_hover_link']['url'];

		?>

		<div class="ayyash-addons-product-categories__hover">
			<a href="<?php echo esc_url( $hover_link ); ?>">
				<?php echo esc_html( $hover_text ); ?>
				<?php Icons_Manager::render_icon( $settings['cat_hover_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			</a>

		</div>

		<?php
	}

}

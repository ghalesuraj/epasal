<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 */
class AyyashAddons_Style_Wishlist extends Ayyash_Widget {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ayyash-wishlist';
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
		return __( 'WishList', 'ayyash-addons' );
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
		return 'ayyash-addons eicon-heart-o';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'ayyash-addons-wishlist',
		];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'ayyash-addons-wishlist',
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
		return [ 'ayyash-widgets' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_cart_icon',
			[
				'label' => __( 'Wishlist', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'wishlist_icon',
			[
				'label'            => __( 'Wishlist Icon', 'ayyash-addons' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'old_wishlist_icon',
				'default'          => [
					'value'   => 'fas fa-heart',
					'library' => 'solid',
				],
				'recommended'      => [
					'fa-solid'   => [
						'heart',
					],
					'fa-regular' => [
						'heart',
					],
				],
			]
		);

		$this->add_control(
			'wishlist_align',
			[
				'label'     => esc_html__( 'Alignment', 'ayyash-addons' ),
				'type'      => Controls_Manager::CHOOSE,
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
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .ayyash-wishlist-icon-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->__wishlist_style();

		$this->__cart_badge_style();

	}

	protected function __wishlist_style() {

		$this->start_controls_section(
			'wc_product_wishlist_icon_settings',
			[
				'label' => esc_html__( 'Wishlist', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label'      => esc_html__( 'Size', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 5,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-wishlist-icon-wrapper i, {{WRAPPER}} .ayyash-wishlist-icon-wrapper a' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			] );

		$this->start_controls_tabs( 'wc_wishlist_icon_tabs' );
		$this->start_controls_tab(
			'wc_wishlist_icon_tab',
			[
				'label' => esc_html__( 'Normal', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'wc_wishlist_icon_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-wishlist-icon-wrapper i' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'wc_wishlist_icon_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'wc_wishlist_icon_hover_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-wishlist-icon-wrapper:hover i' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __cart_badge_style() {
		$this->start_controls_section(
			'wc_wishlist_badge_style',
			[
				'label' => __( 'Count', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wc_wishlist_count_bg_color',
			[
				'label'     => __('Background','ayyash-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-wishlist-icon-wrapper .items-count' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'wc_wishlist_count_text_color',
			[
				'label'     => __('Text Color','ayyash-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-wishlist-icon-wrapper .items-count' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'wc_wishlist_badge_border',
				'selector'  => '{{WRAPPER}} .ayyash-wishlist-icon-wrapper .items-count',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'wc_wishlist_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'separator'  => 'after',
				'selectors'  => [
					'{{WRAPPER}} .ayyash-wishlist-icon-wrapper .items-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_wishlist_badge_typography',
				'selector' => '{{WRAPPER}} .ayyash-wishlist-icon-wrapper .items-count',
			]
		);

		$this->add_control(
			'wc_mini_width',
			[
				'label'      => __( 'Size', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 24,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-wishlist-icon-wrapper .items-count' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			] );

		$this->add_control(
			'wc_wishlist_top_alignment',
			[
				'label'      => __( 'Top', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => -9,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-wishlist-icon-wrapper .items-count' => 'top: {{SIZE}}{{UNIT}};',
				],
			] );

		$this->add_control(
			'wc_wishlist_right_alignment',
			[
				'label'      => __( 'Right', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => -14,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-wishlist-icon-wrapper .items-count' => 'right: {{SIZE}}{{UNIT}};',
				],
			] );

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 *
	 *
	 * @access protected
	 */
	protected function render() {
		if ( ! defined( 'YITH_WCWL' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( [
			'ayyash-wishlist-icon-wrapper' => [
				'class' => 'ayyash-wishlist-icon-wrapper ',
			],
		] );
		?>
		<div <?php $this->print_render_attribute_string( 'ayyash-wishlist-icon-wrapper' ); ?>>
			<?php $this->ayyash_addons_yith_wcwl_get_items_count() ?>
		</div>
		<?php
	}

	public function ayyash_addons_yith_wcwl_get_items_count() {
		$settings = $this->get_settings_for_display();


		// Check if its already migrated
		$migrated = isset( $settings['__fa4_migrated']['wishlist_icon'] );
		// Check if its a new widget without previously selected icon using the old Icon control
		$is_new = empty( $settings['old_wishlist_icon'] );
		if ( defined( 'YITH_WCWL' ) ) {
			?>
			<a href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>">
				<?php if ( $is_new || $migrated ) {
					Icons_Manager::render_icon( $settings['wishlist_icon'], [ 'aria-hidden' => 'true' ] );
				} else {
					?>
					<i class="<?php echo esc_attr( $settings['old_wishlist_icon'] ) ?>" aria-hidden="true"></i>
					<?php
				} ?>
				<span class="items-count">
					<?php echo esc_html( yith_wcwl_count_all_products() ); ?></i>
					</span>
			</a>
			<?php
		}
	}

}

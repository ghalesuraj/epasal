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
class AyyashAddons_Style_Mini_Cart extends Ayyash_Widget {

	public function __construct( $data = [], $args = null ) { //phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		parent::__construct( $data, $args );
	}

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ayyash-mini-cart';
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
		return __( 'Mini Cart', 'ayyash-addons' );
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
		return 'ayyash-addons eicon-cart';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'ayyash-addons-mini-cart',
		];
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
				'label' => __( 'Cart', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'cart_icons',
			[
				'label'            => __( 'Cart Icon', 'ayyash-addons' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'old_cart_icon',
				'recommended'      => [
					'fa-solid' => [
						'cart-plus',
						'shopping-cart',
					],
				],
				'default'          => [
					'value'   => 'fas fa-shopping-cart',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'mini_cart_align',
			[
				'label'     => __( 'Alignment', 'ayyash-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'ayyash-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'ayyash-addons' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'ayyash-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .ayyash-mini-cart-icon-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);


		$this->end_controls_section();

		$this->__cart_icon_style();
		$this->__cart_badge_style();

	}

	protected function __cart_icon_style() {

		$this->start_controls_section(
			'wc_product_mini_cart_icon_settings',
			[
				'label' => __( 'Cart', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_size',
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
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-mini-cart-icon-wrapper i, {{WRAPPER}} .ayyash-mini-cart-icon-wrapper a' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			] );

		$this->start_controls_tabs( 'wc_mini_cart_icon_tabs' );
		$this->start_controls_tab(
			'wc_mini_cart_icon_tab',
			[
				'label' => __( 'Normal', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'wc_mini_cart_icon_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-mini-cart-icon-wrapper i' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'wc_mini_cart_icon_hover_tab',
			[
				'label' => __( 'Hover', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'wc_mini_cart_icon_hover_color',
			[
				'label'     => __( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-mini-cart-icon-wrapper:hover i' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __cart_badge_style() {
		$this->start_controls_section(
			'wc_mini_cart_badge_style',
			[
				'label' => __( 'Badge', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wc_mini_cart_bage_bg_color',
			[
				'label'     => __('Background','ayyash-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-mini-cart-count .badge' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'wc_mini_cart_bage_text_color',
			[
				'label'     => __('Text Color','ayyash-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-mini-cart-count .badge' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'wc_mini_cart_badge_border',
				'selector'  => '{{WRAPPER}} .ayyash-addons-mini-cart-count .badge',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'wc_mini_cart_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'separator'  => 'after',
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-mini-cart-count .badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wc_mini_cart_badge_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-mini-cart-count .badge',
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
					'{{WRAPPER}} .ayyash-addons-mini-cart-count .badge' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			] );

		$this->add_control(
			'wc_mini_cart_top_alignment',
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
					'{{WRAPPER}} .ayyash-addons-mini-cart-count .badge' => 'top: {{SIZE}}{{UNIT}};',
				],
			] );

		$this->add_control(
			'wc_mini_cart_right_alignment',
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
					'{{WRAPPER}} .ayyash-addons-mini-cart-count .badge' => 'right: {{SIZE}}{{UNIT}};',
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
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( [
			'ayyash-mini-cart-icon-wrapper' => [
				'class' => 'ayyash-mini-cart-icon-wrapper ayyash-addons-mini-cart-count',
			],
			'mini-cart-badge'               => [
				'class' => 'badge',
			],
		] );

		// Check if its already migrated
		$migrated = isset( $settings['__fa4_migrated']['cart_icons'] );
		// Check if its a new widget without previously selected icon using the old Icon control
		$is_new = empty( $settings['old_cart_icon'] );

		?>
		<div <?php $this->print_render_attribute_string( 'ayyash-mini-cart-icon-wrapper' ); ?>>
			<?php
			if ( function_exists( 'wc_load_cart' ) ) {
				if ( ! WC()->cart ) {
					wc_load_cart();
				}
				?>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
					<?php if ( $is_new || $migrated ) {
						Icons_Manager::render_icon( $settings['cart_icons'], [ 'aria-hidden' => 'true' ] );
					} else {
						?>
						<i class="<?php echo esc_attr( $settings['old_cart_icon'] ) ?>" aria-hidden="true"></i>
						<?php
					} ?>
					<span <?php $this->print_render_attribute_string( 'mini-cart-badge' ); ?>><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
				</a>
				<?php
			}
			?>
		</div>
		<?php
	}
}

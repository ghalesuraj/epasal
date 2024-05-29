<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AyyashAddons_Style_Team extends Ayyash_Widget {

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
		return 'ayyash-team';
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
		return __( 'Team', 'ayyash-addons' );
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
		return 'ayyash-addons eicon-user-circle-o';
	}

	public function get_keywords() {
		return [ 'team', 'member', 'user' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'ayyash-addons-team',
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
			'section_team_member',
			array(
				'label' => __( 'Team Member', 'ayyash-addons' ),
			)
		);

		$this->add_control(
			'team_member_image',
			array(
				'label'   => esc_html__( 'Team Member Image', 'ayyash-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			)
		);

		$this->add_control(
			'team_member_name',
			[
				'label'       => esc_html__( 'Name', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Linda Willis', 'ayyash-addons' ),
				'placeholder' => __( 'Type your Name', 'ayyash-addons' ),
			]
		);

		$this->add_control(
			'team_member_designation',
			[
				'label'   => esc_html__( 'Designation', 'ayyash-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'UI DESIGNER',
			]
		);

		$this->end_controls_section();

//Add Social Profile Control
		$this->start_controls_section(
			'team_member_social_profile',
			array(
				'label' => __( 'Social Profile', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			) );

		$repeater = new Repeater();

		$repeater->add_control(
			'team_member_social_icon',
			array(
				'label'            => esc_html__( 'Select Icon', 'ayyash-addons' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'ayyash-addons',
			)
		);

		$repeater->add_control(
			'team_member_social_icon_url',
			array(
				'label'       => esc_html__( 'Type Url', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Type your social profile link', 'ayyash-addons' ),
				'default'     => '#',
			)
		);

		$repeater->start_controls_tabs(
			'team_social'
		);

		$repeater->start_controls_tab(
			'team_social_normal_tab',
			[
				'label' => __( 'Normal', 'ayyash-addons' ),
			]
		);

		$repeater->add_control(
			'team_member_social_icon_color',
			array(
				'label'     => esc_html__( 'Select Icon Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-team-item .ayyash-addons-team-social-link ul {{CURRENT_ITEM}} a' => 'color:{{VALUE}}',
				],

			)
		);


		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'team_social_hover_tab',
			[
				'label' => __( 'Hover', 'ayyash-addons' ),
			]
		);

		$repeater->add_control(
			'team_member_social_icon_color_hover',
			array(
				'label'     => esc_html__( 'Icon Hover Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-team-item .ayyash-addons-team-social-link ul {{CURRENT_ITEM}} a:hover' => 'color:{{VALUE}}',
				],

			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'team_member_social_media',
			array(
				'label'       => esc_html__( 'Social Media', 'ayyash-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ elementor.helpers.renderIcon( this, team_member_social_icon, { "aria-hidden": "true" }, "i", "panel" ) }}} {{{team_member_social_icon.value}}}',
			)
		);

		$this->end_controls_section();

		$this->__style_controller();


	}

	protected function __style_controller() {
		$this->start_controls_section(
			'team_member_settings',
			[
				'label' => __( 'Settings', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'team_background',
				'types'    => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .ayyash-addons-team-item',
			]
		);

		// phpcs:disable
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'team_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-team-item, {{WRAPPER}} .ayyash-addons-team-item h3',
				'exclude'  => [ 'font_size' ], //phpcs:ignore (WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude)
			]
		);
		// phpcs:enable


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'team_box_shadow',
				'selector' => '{{WRAPPER}} .ayyash-addons-team-item',
			]
		);

		$this->add_responsive_control(
			'team_padding',
			[
				'label'      => esc_html__( 'Padding', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-team-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'team_margin',
			[
				'label'      => esc_html__( 'margin', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-team-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'team_name_section',
			[
				'label'     => esc_html__( 'Name', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'team_member_name_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-team-item .ayyash-addons-member-name',
			]
		);


		$this->start_controls_tabs(
			'team_member_name_tabs'
		);

		$this->start_controls_tab(
			'team_member_name_normal_tab',
			[
				'label' => 'Normal',
			]
		);

		$this->add_control(
			'team_member_name_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-team-item .ayyash-addons-member-name' => 'color: {{VALUE}}',
				],
			]
		);


		$this->end_controls_tab();
		$this->start_controls_tab(
			'team_member_name_hover_tab',
			[
				'label' => 'Hover',
			]
		);

		$this->add_control(
			'team_member_name_color_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-team-item:hover .ayyash-addons-member-name' => 'color: {{VALUE}}',
				],
			]
		);


		$this->end_controls_tab();
		$this->end_controls_tabs();


		$this->add_control(
			'team_designation_section',
			[
				'label'     => esc_html__( 'Designation', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'team_member_designation_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-team-item .designation',
			]
		);

		$this->start_controls_tabs(
			'team_member_designation_tabs'
		);

		$this->start_controls_tab(
			'team_member_designation_normal_tab',
			[
				'label' => 'Normal',
			]
		);

		$this->add_control(
			'team_member_designation_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-team-item .designation' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'team_member_designation_hover_tab',
			[
				'label' => 'Hover',
			]
		);

		$this->add_control(
			'team_member_designation_color_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-team-item:hover .designation' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'team_social_section',
			[
				'label'     => esc_html__( 'Social Icon', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'team_social_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'ayyash-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
					'{{WRAPPER}} .ayyash-addons-team-item .ayyash-addons-team-social-link ul li a' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'team_member_social_normal_tabs'
		);
		$this->start_controls_tab(
			'team_member_social_normal_tab',
			[
				'label' => 'Normal',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'team_social_bg',
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Social Area Background', 'ayyash-addons' ),
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-team-item .ayyash-addons-team-social-link ul',
			]
		);

		$this->add_control(
			'team_social_icon_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-team-item .ayyash-addons-team-social-link ul li a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'team_member_social_hover_tab',
			[
				'label' => 'Hover',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'team_social_bg_hover',
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Social Area Background', 'ayyash-addons' ),
					],
				],
				'selector'       => '{{WRAPPER}} .ayyash-addons-team-item .ayyash-addons-team-social-link ul::before',
			]
		);

		$this->add_control(
			'team_social_icon_color_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-team-item .ayyash-addons-team-social-link ul li:hover a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'team_social_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ayyash-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .ayyash-addons-team-item .ayyash-addons-team-social-link ul'         => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ayyash-addons-team-item .ayyash-addons-team-social-link ul::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'team_member_name', 'basic' );
		$this->add_render_attribute( 'team_member_name', 'class', 'ayyash-addons-member-name' );

		$this->add_inline_editing_attributes( 'team_member_designation', 'basic' );
		$this->add_render_attribute( 'team_member_designation', 'class', 'designation' );

		?>
		<div class="ayyash-addons-team-item">
			<span <?php $this->print_render_attribute_string( 'team_member_designation' ); ?> class="designation"><?php echo esc_html( $settings['team_member_designation'] ); ?></span>
			<div class="ayyash-addons-team-image">
				<img src="<?php echo esc_url( $settings['team_member_image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['team_member_name'] ) ?>">
			</div>
			<h3 <?php $this->print_render_attribute_string( 'team_member_name' ); ?>
				class="ayyash-addons-member-name"><?php echo esc_html( $settings['team_member_name'] ) ?></h3>

			<?php if ( $settings['team_member_social_media'] ) : ?>
				<div class="ayyash-addons-team-social-link">
					<ul>
						<?php foreach ( $settings['team_member_social_media'] as $social_media ) : ?>
							<li class="elementor-repeater-item-<?php echo esc_attr( $social_media['_id'] ); ?> ">
								<a href="<?php echo esc_url( $social_media['team_member_social_icon_url'] ) ?>">
									<i class="<?php echo esc_attr( $social_media['team_member_social_icon']['value'] ); ?>" aria-hidden="true"></i>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

}

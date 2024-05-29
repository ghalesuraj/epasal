<?php

namespace AyyashAddons\Widgets;

use AyyashAddons\Ayyash_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AyyashAddons_Style_Tab extends Ayyash_Widget {

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
		return 'ayyash-tab';
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
		return __( 'Tabs', 'ayyash-addons' );
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
		return 'ayyash-addons eicon-tabs';
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
			'ayyash-addons-tab',
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
			'section_tab',
			array(
				'label' => __( 'Tabs', 'ayyash-addons' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tabs_title',
			[
				'label'       => esc_html__( 'Title', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Title', 'ayyash-addons' ),
				'placeholder' => esc_html__( 'Title', 'ayyash-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'tabs_sub_title',
			[
				'label'       => esc_html__( 'Sub Title', 'ayyash-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Sub Title', 'ayyash-addons' ),
				'placeholder' => esc_html__( 'Sub Title', 'ayyash-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'tabs_content',
			[
				'label'       => esc_html__( 'Content', 'ayyash-addons' ),
				'default'     => esc_html__( 'Content', 'ayyash-addons' ),
				'placeholder' => esc_html__( 'Content', 'ayyash-addons' ),
				'description' => esc_html__( 'Use (text-column-1, text-column-2, text-column-3) class For Text Column', 'ayyash-addons' ),
				'type'        => Controls_Manager::WYSIWYG,
			]
		);


		$this->add_control(
			'tabs',
			[
				'label'       => esc_html__( 'Tab Items', 'ayyash-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'tabs_title'     => esc_html__( '1. General', 'ayyash-addons' ),
						'tabs_sub_title' => esc_html__( 'Dolor in hendrerit in vulputate velit esse molestie consequat.', 'ayyash-addons' ),
						'tabs_content'   => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adip iscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lob ortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, cons ectetuer adipiscing elit, sed diam nonummy nibh euis mod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo conse quat. Lorem ipsum dolor sit amet, consectetuer adip iscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis', 'ayyash-addons' ),
					],
					[
						'tabs_title'     => esc_html__( '2. Terms of service', 'ayyash-addons' ),
						'tabs_sub_title' => esc_html__( 'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat,', 'ayyash-addons' ),
						'tabs_content'   => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adip iscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lob ortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, cons ectetuer adipiscing elit, sed diam nonummy nibh euis mod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo conse quat. Lorem ipsum dolor sit amet, consectetuer adip iscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis', 'ayyash-addons' ),
					],
				],
				'title_field' => '{{{ tabs_title }}}',
			]
		);

		$this->end_controls_section();

		$this->__style_controller();

	}

	protected function __style_controller() {
		$this->start_controls_section(
			'tab_settings',
			[
				'label' => __( 'Settings', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label'     => esc_html__( 'Title', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-terms-and-condition-sidebar .nav-link h3' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label'     => esc_html__( 'Active Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-terms-and-condition-sidebar .nav-link.active h3'         => 'color: {{VALUE}}',
					'{{WRAPPER}} .ayyash-addons-terms-and-condition-sidebar .nav-link.active h3::before' => 'border-color: transparent transparent transparent {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-terms-and-condition-sidebar .nav-link h3',
			]
		);

		$this->add_control(
			'subtitle_heading',
			[
				'label'     => esc_html__( 'Sub Title', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sub_title_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-terms-and-condition-sidebar .nav-link span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-terms-and-condition-sidebar .nav-link span',
			]
		);

		$this->add_control(
			'heading_content',
			[
				'label'     => esc_html__( 'Content', 'ayyash-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => esc_html__( 'Color', 'ayyash-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ayyash-addons-terms-and-condition-tab .tab-pane' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .ayyash-addons-terms-and-condition-tab .tab-pane',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$tabs     = $settings['tabs'];

		?>
		<!-- Terms And Condition Content -->
		<div class="ayyash-addons-terms-and-condition-content">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<div class="ayyash-addons-terms-and-condition-sidebar">
							<div class="nav flex-md-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
								<?php
								$count = 0;
								foreach ( $tabs as $items ) {
									$count ++;
									?>

									<button class="nav-link <?php echo ( 1 === $count ) ? 'active' : ' '; ?>"
											id="v-pills-general-tab" data-bs-toggle="pill"
											data-bs-target="#tab-<?php echo esc_attr( $items['_id'] ); ?>" type="button"
											role="tab"
											aria-controls="v-pills-tab" aria-selected="true">
										<h3><?php echo esc_html( $items['tabs_title'] ); ?></h3>
										<span><?php echo esc_html( $items['tabs_sub_title'] ); ?></span>
									</button>

								<?php } ?>
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="tab-content ayyash-addons-terms-and-condition-tab" id="v-pills-tabContent">
							<?php
							$count = 0;
							foreach ( $tabs as $items ) {
								$count ++;
								?>
								<div class="tab-pane fade <?php echo ( 1 === $count ) ? 'show active' : ' '; ?>" id="tab-<?php echo esc_attr( $items['_id'] ); ?>" role="tabpanel" aria-labelledby="v-pills-tab-content">
									<?php echo wp_kses_post( $items['tabs_content'] ); ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

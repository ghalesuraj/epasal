<?php

namespace AyyashAddons\Extension;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Dynamic_CSS;
use Elementor\Plugin;


defined( 'ABSPATH' ) || die();

add_action( 'elementor/element/after_section_end', function ( $section, $section_id ) {

	if ( 'section_advanced' === $section_id || '_section_style' === $section_id ) {
		#Start Custom Settings Section
		$section->start_controls_section(
			'ayyash_custom_css_section',
			[
				'label' => __( 'Ayyash Custom CSS', 'ayyash-addons' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$section->add_control(
			'ayyash_custom_css',
			[
				'type'        => Controls_Manager::CODE,
				'label'       => esc_html__( 'Ayyash Custom CSS', 'ayyash-addons' ),
				'language'    => 'css',
				'render_type' => 'ui',
				'show_label'  => false,
				'separator'   => 'none',
			]
		);

		$section->add_control(
			'ayyash_custom_css_description',
			[
				'raw'             => sprintf(
				/* translators: 1: Break line tag. */
					esc_html__( 'Use "selector" to target wrapper element. Examples:%1$sselector {color: red;} // For main element%1$sselector .child-element {margin: 10px;} // For child element%1$s.my-class {text-align: center;} // Or use any custom selector', 'ayyash-addons' ),
					'<br>'
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		#End Custom Settings Section
		$section->end_controls_section();
	}
}, 10, 2 );


add_action( 'elementor/element/parse_css', function ( $post_css, $element ) {
	if ( $post_css instanceof Dynamic_CSS ) {
		return;
	}

	$element_settings = $element->get_settings();

	if ( empty( $element_settings['ayyash_custom_css'] ) || is_null( $element_settings['ayyash_custom_css'] ) ) {
		return;
	}

	$css = trim( $element_settings['ayyash_custom_css'] );

	if ( empty( $css ) ) {
		return;
	}
	$css = str_replace( 'selector', $post_css->get_element_unique_selector( $element ), $css );

	// Add a css comment
	$css = sprintf( '/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector() ) . $css . '/* End custom CSS */';

	$post_css->get_stylesheet()->add_raw_css( $css );
}, 10, 2 );


add_action( 'elementor/css-file/post/parse', function ( $post_css ) {
	$document   = Plugin::$instance->documents->get( $post_css->get_post_id() );
	$custom_css = $document->get_settings( 'ayyash_custom_css' );

	if ( empty( $custom_css ) || is_null( $custom_css ) ) {
		return;
	}
	$custom_css = trim( $custom_css );
	$custom_css = str_replace( 'selector', $document->get_css_wrapper_selector(), $custom_css );

	// Add a css comment
	$custom_css = '/* Start custom CSS for page-settings */' . $custom_css . '/* End custom CSS */';

	$post_css->get_stylesheet()->add_raw_css( $custom_css );
}, 10, 1 );


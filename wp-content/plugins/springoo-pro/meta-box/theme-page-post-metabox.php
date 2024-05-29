<?php
/**
 * Admin feature for Custom Meta Box
 *
 * @author ThemeRox
 * @category PostMetaBox
 * @package springoo\Core
 * -------------------------------------------------------------*/

if ( ! defined('ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

//
// Metabox of the PAGE.
// Set a unique slug-like ID.
//

if ( ! function_exists( 'springoo_get_all_sidebars' ) ) {
	/**
	 * Get All Registered sidebar names.
	 *
	 * @return array
	 */
	function springoo_get_all_sidebars() {
		global $wp_registered_sidebars;
			$sidebar_list = [ 'default' => __( 'Default', 'springoo-pro' ) ];
		foreach ( $wp_registered_sidebars as $sidebar ) {
			$sidebar_list[ $sidebar['id'] ] = $sidebar['name'];
		}
		return $sidebar_list;
	}
}

// admin_init hook to display the data on admin side.

add_action( 'init', function () {
	// Settings Defaults.
	$defaults = apply_filters(
		'springoo_page_post_layout_defaults',
		[
			'springoo_display_header_top'       => 'default',
			'springoo_display_header'           => 'default',
			'springoo_display_header_bottom'    => 'default',
			'springoo_secondary_header'         => 'default',
			'springoo_section_height'           => 0,
			'springoo_site_layout'              => 'default',
			'springoo_header_container'         => 'default',
			'springoo_content_container'        => 'default',
			'springoo_footer_container'         => 'default',
			'springoo_titlebar'                 => 'default',
			'springoo_page_title'               => 'default',
			'springoo_content_layout'           => 'default',
			'springoo_sidebar'                  => 'default',
			'springoo_display_main_footer'      => 'default',
			'springoo_display_secondary_footer' => 'default',
			'springoo_display_credit'           => 'default',
		]
	);
	// Prefix.
	$springoo_prefix = 'springoo';
	CSF::createMetabox(
		$springoo_prefix, array(
			'title'         => 'Page Settings',
			'post_type'     => array( 'page', 'post' ),
			'show_restore'  => true,
			'save_defaults' => true,
			'ajax_save'     => true,
		)
	);
	CSF::createSection(
		$springoo_prefix, array(
			'title'  => 'General',
			'icon'   => 'fa fa-cogs',
			'fields' => array(
				array(
					'id'      => "{$springoo_prefix}_display_header_top",
					'type'    => 'select',
					'title'   => esc_html__( 'Header Top', 'posterlaab-pro' ),
					'desc'    => esc_html__( 'Enable or disable this element on this page or post', 'posterlaab-pro' ),
					'options' => array(
						'default' => esc_html__( 'Default', 'posterlaab-pro' ),
						'enable'  => esc_html__( 'Enable', 'posterlaab-pro' ),
						'disable' => esc_html__( 'Disable', 'posterlaab-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_display_header_top" ],
				),
				array(
					'id'      => "{$springoo_prefix}_display_header",
					'type'    => 'select',
					'title'   => esc_html__( 'Main Header', 'springoo-pro' ),
					'desc'    => esc_html__( 'Enable or disable this element on this page or post.', 'springoo-pro' ),
					'options' => array(
						'default' => esc_html__( 'Default', 'springoo-pro' ),
						'enable'  => esc_html__( 'Enable', 'springoo-pro' ),
						'disable' => esc_html__( 'Disable', 'springoo-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_display_header" ],
				),
				array(
					'id'      => "{$springoo_prefix}_display_header_bottom",
					'type'    => 'select',
					'title'   => esc_html__( 'Header Bottom', 'posterlaab-pro' ),
					'desc'    => esc_html__( 'Enable or disable this element on this page or post', 'posterlaab-pro' ),
					'options' => array(
						'default' => esc_html__( 'Default', 'posterlaab-pro' ),
						'enable'  => esc_html__( 'Enable', 'posterlaab-pro' ),
						'disable' => esc_html__( 'Disable', 'posterlaab-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_display_header_bottom" ],
				),
				array(
					'id'      => "{$springoo_prefix}_secondary_header",
					'type'    => 'select',
					'title'   => esc_html__( 'Secondary Header', 'springoo-pro' ),
					'desc'    => esc_html__( 'Enable or disable this element on this page or post', 'springoo-pro' ),
					'options' => array(
						'default' => esc_html__( 'Default', 'springoo-pro' ),
						'enable'  => esc_html__( 'Enable', 'springoo-pro' ),
						'disable' => esc_html__( 'Disable', 'springoo-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_secondary_header" ],
				),
				array(
					'id'      => "{$springoo_prefix}_section_height",
					'type'    => 'select',
					'title'   => esc_html__( 'Section Height', 'springoo-pro' ),
					'desc'    => esc_html__( 'This option should only be used in very specific cases since there is a global setting available in the Customizer.', 'springoo-pro' ),
					'options' => array(
						0 => esc_html__( 'Default', 'springoo-pro' ),
						1 => esc_html__( 'Top None', 'springoo-pro' ),
						2 => esc_html__( 'Bottom None', 'springoo-pro' ),
						3 => esc_html__( 'Top Bottom None', 'springoo-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_section_height" ],
				),
				array(
					'id'      => "{$springoo_prefix}_site_layout",
					'type'    => 'select',
					'title'   => esc_html__( 'Site Layout', 'springoo-pro' ),
					'desc'    => esc_html__( 'This option should only be used in very specific cases since there is a global setting available in the Customizer.', 'springoo-pro' ),
					'options' => array(
						'default' => esc_html__( 'Default', 'springoo-pro' ),
						'wide'    => esc_html__( 'Full-Width', 'springoo-pro' ),
						'boxed'   => esc_html__( 'Boxed', 'springoo-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_site_layout" ],
				),
				array(
					'id'      => "{$springoo_prefix}_header_container",
					'type'    => 'select',
					'title'   => esc_html__( 'Header Container', 'springoo-pro' ),
					'desc'    => esc_html__( 'This option should only be used in very specific cases since there is a global setting available in the Customizer.', 'springoo-pro' ),
					'options' => array(
						'default'         => esc_html__( 'Default', 'springoo-pro' ),
						'container'       => esc_html__( 'Container', 'springoo-pro' ),
						'Container-fluid' => esc_html__( 'Container Fluid', 'springoo-pro' ),
						'none'            => esc_html__( 'None', 'springoo-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_header_container" ],
				),
				array(
					'id'      => "{$springoo_prefix}_content_container",
					'type'    => 'select',
					'title'   => esc_html__( 'Content Container', 'springoo-pro' ),
					'desc'    => esc_html__( 'This option should only be used in very specific cases since there is a global setting available in the Customizer.', 'springoo-pro' ),
					'options' => array(
						'default'         => esc_html__( 'Default', 'springoo-pro' ),
						'container'       => esc_html__( 'Container', 'springoo-pro' ),
						'Container-fluid' => esc_html__( 'Container Fluid', 'springoo-pro' ),
						'none'            => esc_html__( 'None', 'springoo-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_content_container" ],
				),
				array(
					'id'      => "{$springoo_prefix}_footer_container",
					'type'    => 'select',
					'title'   => esc_html__( 'Footer Container', 'springoo-pro' ),
					'desc'    => esc_html__( 'This option should only be used in very specific cases since there is a global setting available in the Customizer.', 'springoo-pro' ),
					'options' => array(
						'default'         => esc_html__( 'Default', 'springoo-pro' ),
						'container'       => esc_html__( 'Container', 'springoo-pro' ),
						'Container-fluid' => esc_html__( 'Container Fluid', 'springoo-pro' ),
						'none'            => esc_html__( 'None', 'springoo-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_content_container" ],
				),
				array(
					'id'      => "{$springoo_prefix}_titlebar",
					'type'    => 'select',
					'title'   => esc_html__( 'Title Bar', 'springoo-pro' ),
					'desc'    => esc_html__( 'Enable or Disable Title Bar for this page or post.', 'springoo-pro' ),
					'options' => array(
						'default' => esc_html__( 'Default', 'springoo-pro' ),
						'enable'  => esc_html__( 'Enable', 'springoo-pro' ),
						'disable' => esc_html__( 'Disable', 'springoo-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_titlebar" ],
				),
				array(
					'id'      => "{$springoo_prefix}_page_title",
					'type'    => 'select',
					'title'   => esc_html__( 'Page Title', 'springoo-pro' ),
					'desc'    => esc_html__( 'Enable or Disable Page Title for this page or post.', 'springoo-pro' ),
					'options' => array(
						'default' => esc_html__( 'Default', 'springoo-pro' ),
						'enable'  => esc_html__( 'Enable', 'springoo-pro' ),
						'disable' => esc_html__( 'Disable', 'springoo-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_page_title" ],
				),
				array(
					'id'      => "{$springoo_prefix}_content_layout",
					'type'    => 'select',
					'title'   => esc_html__( 'Content Layout', 'springoo-pro' ),
					'desc'    => esc_html__( 'Select your custom layout for this page or post content.', 'springoo-pro' ),
					'options' => array(
						'default' => esc_html__( 'Default', 'springoo-pro' ),
						'right'   => esc_html__( 'Right Sidebar', 'springoo-pro' ),
						'left'    => esc_html__( 'Left Sidebar', 'springoo-pro' ),
						'none'    => esc_html__( 'No Sidebar', 'springoo-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_content_layout" ],
				),
				array(
					'id'      => "{$springoo_prefix}_sidebar",
					'type'    => 'select',
					'title'   => esc_html__( 'Sidebars', 'springoo-pro' ),
					'desc'    => esc_html__( 'Select your a custom sidebar for this page or post.', 'springoo-pro' ),
					'options' => springoo_get_all_sidebars(),
					'default' => $defaults[ "{$springoo_prefix}_sidebar" ],
				),
				array(
					'id'      => "{$springoo_prefix}_display_main_footer",
					'type'    => 'select',
					'title'   => esc_html__( 'Main Footer', 'springoo-pro' ),
					'desc'    => esc_html__( 'Enable or disable this element on this page or post.', 'springoo-pro' ),
					'options' => array(
						'default' => esc_html__( 'Default', 'springoo-pro' ),
						'enable'  => esc_html__( 'Enable', 'springoo-pro' ),
						'disable' => esc_html__( 'Disable', 'springoo-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_display_main_footer" ],
				),
				array(
					'id'      => "{$springoo_prefix}_display_secondary_footer",
					'type'    => 'select',
					'title'   => esc_html__( 'Secondary Footer', 'springoo-pro' ),
					'desc'    => esc_html__( 'Enable or disable this element on this page or post.', 'springoo-pro' ),
					'options' => array(
						'default' => esc_html__( 'Default', 'springoo-pro' ),
						'enable'  => esc_html__( 'Enable', 'springoo-pro' ),
						'disable' => esc_html__( 'Disable', 'springoo-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_display_secondary_footer" ],
				),
				array(
					'id'      => "{$springoo_prefix}_display_credit",
					'type'    => 'select',
					'title'   => esc_html__( 'Credit Section', 'springoo-pro' ),
					'desc'    => esc_html__( 'Enable or disable this element on this page or post.', 'springoo-pro' ),
					'options' => array(
						'default' => esc_html__( 'Default', 'springoo-pro' ),
						'enable'  => esc_html__( 'Enable', 'springoo-pro' ),
						'disable' => esc_html__( 'Disable', 'springoo-pro' ),
					),
					'default' => $defaults[ "{$springoo_prefix}_display_credit" ],
				),
			),
		)
	);
	CSF::createSection(
		$springoo_prefix, array(
			'title'       => 'Backup',
			'icon'        => 'fa fa-shield',
			'description' => __( 'Visit documentation for more details on this field: <a href="https://codestarframework.com/documentation/#/fields?id=backup" target="_blank">Field: backup</a>', 'springoo-pro'),
			'fields'      => [ [ 'type' => 'backup' ] ],
		)
	);
}, 9 );

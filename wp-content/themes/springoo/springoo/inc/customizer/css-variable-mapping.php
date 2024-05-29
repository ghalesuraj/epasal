<?php
/**
 * CSS Variable Mapping.
 *
 * @package Springoo
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

return [
	'colors_global_accent'                    => '--springoo-color-primary',
	'colors_global_accent_shade'              => '--springoo-color-secondary',
	'colors_global_heading'                   => '--springoo-color-heading',
	'colors_global_text'                      => '--springoo-color-global',
	'colors_global_content_bg'                => '--springoo-color-content-bg',
	'colors_global_site_bg'                   => '--springoo-color-site-bg',
	'colors_global_border'                    => '--springoo-color-border',
	'colors_global_gradient'                  => '--springoo-color-gradient',

	'colors_global_alt'                       => '--springoo-color-form-bg',
	'colors_global_alt_text'                  => '--springoo-color-form-text',

	'colors_global_sticky_bg'                 => '--springoo-color-sticky-bg',
	'colors_header_main_bg'                   => '--springoo-color-header-bg',
	'colors_header_top_bg'                    => '--springoo-color-header-top-bg',
	'colors_header_bottom_bg'                 => '--springoo-color-header-bottom-bg',

	'colors_menu_text'                        => '--springoo-color-main-menu',
	'colors_menu_hover'                       => '--springoo-color-main-menu-hover',

	'colors_menu_sub_bg'                      => '--springoo-color-sub-menu-bg',
	'colors_menu_sub_border'                  => '--springoo-color-sub-menu-border',
	'colors_menu_sub_text'                    => '--springoo-color-sub-menu',
	'colors_menu_sub_hover'                   => '--springoo-color-sub-menu-hover',
	'colors_menu_sub_hover_bg'                => '--springoo-color-sub-menu-bg-hover',
	'colors_menu_mob_bg'                      => '--springoo-color-mobile-menu-bg',
	'colors_menu_color'                       => '--springoo-color-mobile-menu',
	'colors_menu_mob_hover'                   => '--springoo-color-mobile-menu-hover',
	'colors_menu_mob_hover_bg'                => '--springoo-color-mobile-menu-bg-hover',
	'colors_menu_mob_border'                  => '--springoo-color-mobile-menu-border',

	'colors_mega_menu_bg'                     => '--springoo-color-mega-menu-bg',
	'colors_mega_menu_title'                  => '--springoo-color-mega-menu-column-title',
	'colors_mega_menu_title_hover'            => '--springoo-color-mega-menu-column-title-hover',
	'colors_mega_menu_item'                   => '--springoo-color-mega-menu-item',
	'colors_mega_menu_item_hover'             => '--springoo-color-mega-menu-item-hover',

	'colors_title_bg1'                        => '--springoo-color-breadcrumb-bg1',
	'colors_title_bg2'                        => '--springoo-color-breadcrumb-bg2',
	'colors_title_text'                       => '--springoo-color-breadcrumb',

	// Footer Main
	'colors_footer_main_bg'                   => '--springoo-color-footer-bg',
	'colors_footer_main_heading'              => '--springoo-color-footer-heading',
	'colors_footer_main_text'                 => '--springoo-color-footer',
	'colors_footer_main_link'                 => '--springoo-color-footer-link',
	'colors_footer_main_link_hover'           => '--springoo-color-footer-link-hover',

	// Footer Secondary
	'colors_footer_sc_bg'                     => '--springoo-color-secondary-footer-bg',
	'colors_footer_sc_link'                   => '--springoo-color-secondary-footer-link',
	'colors_footer_sc_link_hover'             => '--springoo-color-secondary-footer-link-hover',
	'colors_footer_sc_border_top'             => '--springoo-color-secondary-footer-border-color',

	// Footer Copyright
	'colors_footer_copyright_bg'              => '--springoo-color-copyright-bg',
	'colors_footer_copyright_text'            => '--springoo-color-copyright',
	'colors_footer_copyright_link'            => '--springoo-color-footer-copyright-link',
	'colors_footer_copyright_link_hover'      => '--springoo-color-footer-copyright-link-hover',
	'colors_footer_copyright_border_top'      => '--springoo-color-footer-copyright-border-color',

	// Layout
	'layout_header_sticky_height'             => '--springoo-header-sticky-height',
	'layout_header_sticky_menu_height'        => '--springoo-header-sticky-menu-height',
	'layout_header_submenu_width'             => '--springoo-header-submenu-width',
	'layout_header_submenu_top_padding'       => '--springoo-header-submenu-padding-top',
	'layout_header_submenu_bottom_padding'    => '--springoo-header-submenu-padding-bottom',
	'layout_header_submenu_left_padding'      => '--springoo-header-submenu-padding-left',
	'layout_header_submenu_right_padding'     => '--springoo-header-submenu-padding-right',
	'layout_header_menu_left_padding'         => '--springoo-header-menu-padding-left',
	'layout_header_menu_right_padding'        => '--springoo-header-menu-padding-right',
	'layout_header_mega_menu_bottom_spacing'  => '--springoo-header-mega-menu-spacing-bottom',
	'layout_transparent_header_top_margin'    => '--springoo-header-transparent-margin-top',
	'layout_footer_main_top_padding'          => '--springoo-footer-main-padding-top',
	'layout_footer_main_bottom_padding'       => '--springoo-footer-main-padding-bottom',
	'layout_footer_bottom_top_padding'        => '--springoo-footer-bottom-padding-top',
	'layout_footer_bottom_bottom_padding'     => '--springoo-footer-bottom-padding-bottom',
	'layout_footer_credits_top_padding'       => '--springoo-footer-credits-padding-top',
	'layout_footer_credits_bottom_padding'    => '--springoo-footer-credits-padding-bottom',

	// Layout align
	'layout_header_menu_alignment'            => '--springoo-header-menu-alignment',
	'layout_header_submenu_alignment'         => '--springoo-header-submenu-alignment',
	'layout_post_content_align'               => '--springoo-post-content-alignment',
	'layout_page_content_align'               => '--springoo-page-content-alignment',

	// Typo Global.
	'typography_global_font_family'           => '--springoo-global-font-family',
	'typography_global_font_variant'          => '--springoo-global-font-weight',
	'typography_global_font_size'             => '--springoo-global-font-size',
	// TODO old variable --springoo-font-size-global
	'typography_global_line_height'           => '--springoo-global-line-height',
	'typography_global_text_transform'        => '--springoo-global-text-transform',
	'typography_global_letter_spacing'        => '--springoo-global-letter-spacing',
	'typography_global_word_spacing'          => '--springoo-global-word-spacing',

	// Typo Headings.
	'typography_heading_font_family'          => '--springoo-heading-font-family',
	'typography_heading_font_variant'         => '--springoo-heading-font-weight',
	'typography_heading_font_size'            => '--springoo-heading-font-size',
	'typography_heading_line_height'          => '--springoo-heading-line-height',
	'typography_heading_text_transform'       => '--springoo-heading-text-transform',
	'typography_heading_letter_spacing'       => '--springoo-heading-letter-spacing',
	'typography_heading_word_spacing'         => '--springoo-heading-word-spacing',

	'typography_heading_h1_font_family'       => '--springoo-h1-font-family',
	'typography_heading_h1_font_variant'      => '--springoo-h1-font-weight',
	'typography_heading_h1_font_size'         => '--springoo-h1-font-size',
	'typography_heading_h1_line_height'       => '--springoo-h1-line-height',
	'typography_heading_h1_text_transform'    => '--springoo-h1-text-decoration',
	'typography_heading_h1_letter_spacing'    => '--springoo-h1-letter-spacing',
	'typography_heading_h1_word_spacing'      => '--springoo-h1-word-spacing',

	'typography_heading_h2_font_family'       => '--springoo-h2-font-family',
	'typography_heading_h2_font_variant'      => '--springoo-h2-font-weight',
	'typography_heading_h2_font_size'         => '--springoo-h2-font-size',
	'typography_heading_h2_line_height'       => '--springoo-h2-line-height',
	'typography_heading_h2_text_transform'    => '--springoo-h2-text-decoration',
	'typography_heading_h2_letter_spacing'    => '--springoo-h2-letter-spacing',
	'typography_heading_h2_word_spacing'      => '--springoo-h2-word-spacing',

	'typography_heading_h3_font_family'       => '--springoo-h3-font-family',
	'typography_heading_h3_font_variant'      => '--springoo-h3-font-weight',
	'typography_heading_h3_font_size'         => '--springoo-h3-font-size',
	'typography_heading_h3_line_height'       => '--springoo-h3-line-height',
	'typography_heading_h3_text_transform'    => '--springoo-h3-text-decoration',
	'typography_heading_h3_letter_spacing'    => '--springoo-h3-letter-spacing',
	'typography_heading_h3_word_spacing'      => '--springoo-h3-word-spacing',

	'typography_heading_h4_font_family'       => '--springoo-h4-font-family',
	'typography_heading_h4_font_variant'      => '--springoo-h4-font-weight',
	'typography_heading_h4_font_size'         => '--springoo-h4-font-size',
	'typography_heading_h4_line_height'       => '--springoo-h4-line-height',
	'typography_heading_h4_text_transform'    => '--springoo-h4-text-decoration',
	'typography_heading_h4_letter_spacing'    => '--springoo-h4-letter-spacing',
	'typography_heading_h4_word_spacing'      => '--springoo-h4-word-spacing',

	'typography_heading_h5_font_family'       => '--springoo-h5-font-family',
	'typography_heading_h5_font_variant'      => '--springoo-h5-font-weight',
	'typography_heading_h5_font_size'         => '--springoo-h5-font-size',
	'typography_heading_h5_line_height'       => '--springoo-h5-line-height',
	'typography_heading_h5_text_transform'    => '--springoo-h5-text-decoration',
	'typography_heading_h5_letter_spacing'    => '--springoo-h5-letter-spacing',
	'typography_heading_h5_word_spacing'      => '--springoo-h5-word-spacing',

	'typography_heading_h6_font_family'       => '--springoo-h6-font-family',
	'typography_heading_h6_font_variant'      => '--springoo-h6-font-weight',
	'typography_heading_h6_font_size'         => '--springoo-h6-font-size',
	'typography_heading_h6_line_height'       => '--springoo-h6-line-height',
	'typography_heading_h6_text_transform'    => '--springoo-h6-text-decoration',
	'typography_heading_h6_letter_spacing'    => '--springoo-h6-letter-spacing',
	'typography_heading_h6_word_spacing'      => '--springoo-h6-word-spacing',

	'typography_menu_font_family'             => '--springoo-menu-font-family',
	'typography_menu_font_variant'            => '--springoo-menu-font-weight',
	'typography_menu_font_size'               => '--springoo-menu-font-size',
	'typography_menu_text_transform'          => '--springoo-menu-text-transform',
	'typography_menu_letter_spacing'          => '--springoo-menu-letter-spacing',
	'typography_menu_word_spacing'            => '--springoo-menu-word-spacing',

	'typography_menu_sub_font_family'         => '--springoo-sub-menu-font-family',
	'typography_menu_sub_font_variant'        => '--springoo-sub-menu-font-weight',
	'typography_menu_sub_font_size'           => '--springoo-sub-menu-font-size',
	'typography_menu_sub_line_height'         => '--springoo-sub-menu-line-height',
	'typography_menu_sub_text_transform'      => '--springoo-sub-menu-text-transform',
	'typography_menu_sub_letter_spacing'      => '--springoo-sub-menu-letter-spacing',
	'typography_menu_sub_word_spacing'        => '--springoo-sub-menu-word-spacing',

	'typography_menu_mobile_font_family'      => '--springoo-mobile-menu-font-family',
	'typography_menu_mobile_font_variant'     => '--springoo-mobile-menu-font-weight',
	'typography_menu_mobile_font_size'        => '--springoo-mobile-menu-font-size',
	'typography_menu_mobile_line_height'      => '--springoo-mobile-menu-line-height',
	'typography_menu_mobile_text_transform'   => '--springoo-mobile-menu-text-transform',
	'typography_menu_mobile_letter_spacing'   => '--springoo-mobile-menu-letter-spacing',
	'typography_menu_mobile_word_spacing'     => '--springoo-mobile-menu-word-spacing',

	'typography_site_title_font_family'       => '--springoo-site-title-font-family',
	'typography_site_title_font_variant'      => '--springoo-site-title-font-weight',
	'typography_site_title_font_size'         => '--springoo-site-title-font-size',
	'typography_site_title_line_height'       => '--springoo-site-title-line-height',
	'typography_site_title_text_transform'    => '--springoo-site-title-text-transform',
	'typography_site_title_letter_spacing'    => '--springoo-site-title-letter-spacing',
	'typography_site_title_word_spacing'      => '--springoo-site-title-word-spacing',

	'typography_site_tagline_font_family'     => '--springoo-site-tag-font-family',
	'typography_site_tagline_font_variant'    => '--springoo-site-tag-font-weight',
	'typography_site_tagline_font_size'       => '--springoo-site-tag-font-size',
	'typography_site_tagline_line_height'     => '--springoo-site-tag-line-height',
	'typography_site_tagline_text_transform'  => '--springoo-site-tag-text-transform',
	'typography_site_tagline_letter_spacing'  => '--springoo-site-tag-letter-spacing',
	'typography_site_tagline_word_spacing'    => '--springoo-site-tag-word-spacing',

	'typography_sidebar_title_font_family'    => '--springoo-sidebar-widget-title-font-family',
	'typography_sidebar_title_font_variant'   => '--springoo-sidebar-widget-title-font-weight',
	'typography_sidebar_title_font_size'      => '--springoo-sidebar-widget-title-font-size',
	'typography_sidebar_title_line_height'    => '--springoo-sidebar-widget-title-line-height',
	'typography_sidebar_title_text_transform' => '--springoo-sidebar-widget-title-text-transform',
	'typography_sidebar_title_letter_spacing' => '--springoo-sidebar-widget-title-letter-spacing',
	'typography_sidebar_title_word_spacing'   => '--springoo-sidebar-widget-title-word-spacing',

	'typography_sidebar_body_font_family'     => '--springoo-sidebar-widget-body-font-family',
	'typography_sidebar_body_font_variant'    => '--springoo-sidebar-widget-body-font-weight',
	'typography_sidebar_body_font_size'       => '--springoo-sidebar-widget-body-font-size',
	'typography_sidebar_body_line_height'     => '--springoo-sidebar-widget-body-line-height',
	'typography_sidebar_body_text_transform'  => '--springoo-sidebar-widget-body-text-transform',
	'typography_sidebar_body_letter_spacing'  => '--springoo-sidebar-widget-body-letter-spacing',
	'typography_sidebar_body_word_spacing'    => '--springoo-sidebar-widget-body-word-spacing',

	'typography_footer_title_font_family'     => '--springoo-footer-widget-title-font-family',
	'typography_footer_title_font_variant'    => '--springoo-footer-widget-title-font-weight',
	'typography_footer_title_font_size'       => '--springoo-footer-widget-title-font-size',
	'typography_footer_title_line_height'     => '--springoo-footer-widget-title-line-height',
	'typography_footer_title_text_transform'  => '--springoo-footer-widget-title-text-transform',
	'typography_footer_title_letter_spacing'  => '--springoo-footer-widget-title-letter-spacing',
	'typography_footer_title_word_spacing'    => '--springoo-footer-widget-title-word-spacing',

	'typography_footer_body_font_family'      => '--springoo-footer-widget-body-font-family',
	'typography_footer_body_font_variant'     => '--springoo-footer-widget-body-font-weight',
	'typography_footer_body_font_size'        => '--springoo-footer-widget-body-font-size',
	'typography_footer_body_line_height'      => '--springoo-footer-widget-body-line-height',
	'typography_footer_body_text_transform'   => '--springoo-footer-widget-body-text-transform',
	'typography_footer_body_letter_spacing'   => '--springoo-footer-widget-body-letter-spacing',
	'typography_footer_body_word_spacing'     => '--springoo-footer-widget-body-word-spacing',

	'typography_footer_text_font_family'      => '--springoo-secondary-footer-font-family',
	'typography_footer_text_font_variant'     => '--springoo-secondary-footer-font-weight',
	'typography_footer_text_font_size'        => '--springoo-secondary-footer-font-size',
	'typography_footer_text_line_height'      => '--springoo-secondary-footer-line-height',
	'typography_footer_text_text_transform'   => '--springoo-secondary-footer-text-transform',
	'typography_footer_text_letter_spacing'   => '--springoo-secondary-footer-letter-spacing',
	'typography_footer_text_word_spacing'     => '--springoo-secondary-footer-word-spacing',

	//extra font size
	'typography_x_large_font_size'            => '--springoo-x-large-font-size',
	'typography_large_font_size'              => '--springoo-large-font-size',
	'typography_medium_font_size'             => '--springoo-medium-font-size',
	'typography_small_font_size'              => '--springoo-small-font-size',
	'typography_x_small_font_size'            => '--springoo-x-small-font-size',
	'typography_xx_small_font_size'           => '--springoo-xx-small-font-size',


	'layout_wpcf7_top_padding'                => '--springoo-wpcf7-padding-top',
	'layout_wpcf7_bottom_padding'             => '--springoo-wpcf7-padding-bottom',
	'layout_wpcf7_left_padding'               => '--springoo-wpcf7-padding-left',
	'layout_wpcf7_right_padding'              => '--springoo-wpcf7-padding-right',
	'layout_wpcf7_border_radius'              => '--springoo-wpcf7-border-radius',
	'layout_wpcf7_gap'                        => '--springoo-wpcf7-gap',
	'layout_wpcf7_textarea_height'            => '--springoo-wpcf7-textarea-height',
];

// End of file css-variable-mapping.php.

<?php
/**
 * Default Hooks.
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

add_action( 'wp_head', 'springoo_pingback_header', 3 );
add_action( 'wp_footer', 'springoo_supports_js', - 1 );
add_action( 'wp_body_open', 'springoo_preloader', -2 );
add_filter( 'body_class', 'springoo_body_classes' );
add_filter( 'post_class', 'springoo_post_classes' );
add_filter( 'wp_kses_allowed_html', 'springoo_allow_tags' );
add_action( 'springoo_before_content_loop_layout', 'springoo_grid_loop_layout_start' );
add_action( 'springoo_after_content_loop_layout', 'springoo_grid_loop_layout_end' );
add_filter( 'excerpt_length', 'springoo_custom_excerpt_length', 999 );
add_action( 'comment_form_before_fields', 'springoo_commentfields_rowtag' );
add_action( 'comment_form_after_fields', 'springoo_commentfields_rowtag_end' );
add_action( 'cancel_comment_reply_link', 'springoo_cancel_comment_reply_link' );
add_action( 'edit_category', 'springoo_category_transient_flusher' );
add_action( 'save_post', 'springoo_category_transient_flusher' );
add_filter('nav_menu_css_class', 'springoo_nav_menu_depth_class', 10, 4);
add_action( 'wp_body_open', 'springoo_mobile_backdrop_overlay' );

/**
 * ----------------------------------------------------------------------
 * Declaration of all header hook
 * ----------------------------------------------------------------------*/
add_action( 'wp_body_open', 'springoo_skip_links', - 100 );
add_action( 'springoo_header_top', 'springoo_header_top' );
add_action( 'springoo_header_main', 'springoo_header_main' );
add_action( 'springoo_header_bottom', 'springoo_header_bottom' );
add_action( 'springoo_header_mobile_menu', 'springoo_mobile_menu' );

/**
 * ----------------------------------------------------------------------
 * Single Page/Post Option hook
 * ----------------------------------------------------------------------*/
add_action( 'springoo_single_option', 'springoo_single_secondary_header' );
add_action( 'springoo_single_option', 'springoo_single_master_header' );

/**
 * ----------------------------------------------------------------------
 * Declaration of all footer hook
 * ----------------------------------------------------------------------*/
add_action( 'springoo_before_footer', 'springoo_footer_newsletter', 20 );
add_action( 'springoo_footer_contents', 'springoo_footer_services', 40 );
add_action( 'springoo_footer_contents', 'springoo_render_footer_main', 50 );

// Secondary footer.
add_action( 'springoo_footer_secondary_contents', 'springoo_render_secondary_footer' );
add_action( 'springoo_render_secondary_footer', 'springoo_render_footer_bottom_menu', 10 );
add_action( 'springoo_render_secondary_footer', 'springoo_render_footer_download_app', 20 );

// Credit Section
add_action( 'springoo_footer_bottom_contents', 'springoo_render_footer_credits', 30 );
add_action( 'wp_footer', 'springoo_render_go_to_top', - 1 );

// Disable mediaelement and use Plyr for audio & video shortcode.
add_filter( 'wp_audio_shortcode_library', 'springoo_disable_mediaelement' );
add_filter( 'wp_video_shortcode_library', 'springoo_disable_mediaelement' );
add_filter( 'shortcode_atts_gallery', 'springoo_set_gallery_thumb_size' );

// Exclude Product from search result page
add_action( 'pre_get_posts', 'springoo_blog_search' );

add_action( 'wp_footer', 'header_search_form' );




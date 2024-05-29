<?php
/**
 * To remove core action with hook after_setup_theme
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}


add_action( 'after_setup_theme', 'springoo_woocommerce_init' );
add_filter( 'init', 'springoo_remove_wc_breadcrumbs' );
add_filter( 'loop_shop_columns', 'springoo_loop_columns', 1 );
add_filter( 'loop_shop_per_page', 'springoo_loop_shop_per_page', 20 );
add_filter( 'woocommerce_show_page_title', 'springoo_wc_hide_page_title' );
add_action( 'woocommerce_before_shop_loop', 'springoo_wc_remove_result_count' );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_archive_description', 'springoo_wc_product_archive_filter_header', 30 );
add_filter( 'woocommerce_upsell_display_args', 'springoo_wc_upsell_display_args' );
add_filter( 'woocommerce_upsell_display_args', 'springoo_wc_upsell_display_column_args' );
add_filter( 'woocommerce_output_related_products_args', 'springoo_wc_related_products_limit' );
add_filter( 'woocommerce_cross_sells_total', 'springoo_cart_cross_sell_total' );
add_filter( 'woocommerce_output_related_products_args', 'springoo_change_number_related_products', 9999 );
add_filter( 'woocommerce_get_image_size_gallery_thumbnail', 'springoo_wc_gallery_thumb_size' );
add_action( 'init', 'springoo_cart_cross_sell_display' );

add_filter('woocommerce_product_get_image', 'springoo_custom_product_get_image', 10, 4);

add_action( 'springoo_woocommerce_before_main_content_after_title', 'springoo_product_archive_banner' );
add_action( 'springoo_woocommerce_before_main_content_after_title', 'springoo_product_archive_header' );
add_action( 'wp_body_open', 'springoo_filter_backdrop_overlay' );
add_action( 'springoo_header_mobile_menu', 'springoo_filter_shop' );

/**
 * ----------------------------------------------------------------------
 * Theme Hook for the layout
 *----------------------------------------------------------------------*/
// before after markup for theme.
add_action( 'woocommerce_before_main_content', 'springoo_woocommerce_before_main_content' );
add_action( 'woocommerce_after_main_content', 'springoo_woocommerce_after_main_content' );
add_action( 'springoo_woocommerce_show_product_loop_before_product_body', 'woocommerce_show_product_loop_sale_flash', 11 );
add_filter( 'springoo_woocommerce_show_stock_product_loop_before_product_body', 'springoo_woocommerce_new_badge', 100 );
add_action( 'woocommerce_before_sale_coundown', 'springoo_sale_countdown_timer', 13 );
add_filter( 'woocommerce_product_get_rating_html', 'springoo_filter_product_rating_html', 10, 1 );
add_filter( 'woocommerce_variation_is_active', 'springoo_variation_check', 10, 2 );
add_filter( 'product_cat_class', 'product_cat_item_class' );
add_filter( 'woocommerce_product_loop_start', 'springoo_product_loop_start' );
add_filter( 'woocommerce_account_menu_items', 'springoo_remove_my_account_menu_items', 100, 1 );

//Single product.
add_filter( 'woocommerce_sale_flash', '__return_null' );
add_action( 'woocommerce_product_options_stock_fields', 'springoo_stock_management_initial_stock' );
add_action( 'woocommerce_process_product_meta', 'springoo_stock_management_initial_stock_save' );
add_action( 'woocommerce_variation_options_inventory', 'springoo_stock_management_variable_initial_stock', 20, 3 );
add_action( 'woocommerce_save_product_variation', 'springoo_stock_management_variable_initial_stock_save', 20, 2 );
add_action( 'woocommerce_before_single_product_summary', 'springoo_shop_labels', 11 );
add_action( 'woocommerce_single_product_summary', 'springoo_single_product_actions', 31 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 15 );
add_action( 'woocommerce_single_product_summary', 'springoo_single_product_stock_count', 21 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_countdown_timer', 25 );
add_action( 'woocommerce_before_variations_form', 'woocommerce_countdown_timer_variable', 25 );
add_action( 'woocommerce_available_variation', 'springoo_wc_available_variation', '',  3 );

//Single product.
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

//single product reviews
add_action( 'init', 'springoo_reviews_meta_fields', - 1 );
add_action( 'comment_form', 'springoo_review_nonce' );
add_action( 'comment_post', 'springoo_save_reviews', 10 );
add_filter( 'woocommerce_product_settings', 'springoo_review_image_settings' );

add_action( 'springoo_reviews_summery', 'springoo_reviews_summery_display', 10, 1 );
add_action( 'springoo_reviews_sort_filter', 'springoo_reviews_sort_filter_display', 10, 1 );

add_filter( 'woocommerce_review_gravatar_size', function () {
	return 55;
} );

add_action( 'woocommerce_review_before_comment_text', 'springoo_reviews_title', 10, 1 );
add_action( 'woocommerce_review_after_comment_text', 'springoo_reviews_gallery', 10, 1 );
add_action( 'woocommerce_review_after_comment_text', 'springoo_review_feedback_display', 15 );
add_filter( 'woocommerce_product_tabs', 'springoo_wc_default_product_tabs' );



/**
 * ----------------------------------------------------------------------
 * My Account Content Title hook
 * ----------------------------------------------------------------------*/
add_action( 'woocommerce_before_my_account', 'springoo_my_account_content_title' );
add_action( 'woocommerce_before_account_orders', 'springoo_my_account_content_title' );
add_action( 'woocommerce_before_account_downloads', 'springoo_my_account_content_title' );
add_action( 'woocommerce_before_edit_account_form', 'springoo_my_account_content_title' );
add_action( 'woocommerce_before_edit_my_address', 'springoo_my_account_content_title' );
add_filter( 'woocommerce_customer_get_downloadable_products', 'woocommerce_download_button_label' );
add_filter( 'woocommerce_order_get_downloadable_items', 'woocommerce_download_button_label' );

/** ----------------------------------------------------------------------
 * Springoo Pro for WooCommerce
 * ----------------------------------------------------------------------*/
if ( ! class_exists( 'Springoo_Pro' ) ) {
	add_filter( 'springoo_product_loop_classes', 'springoo_product_grid_class' );
	add_filter( 'springoo_product_btn_classes', 'springoo_product_add_to_cart_btn_styles' );
	add_filter( 'springoo_product_btn_classes', 'springoo_product_show_button_text_icon' );
}

/** ----------------------------------------------------------------------
 * Product labels hook
 * ----------------------------------------------------------------------*/
add_action( 'springoo_shop_labels', 'springoo_get_product_discount', 5 );
add_action( 'springoo_shop_labels', 'springoo_get_featured_badge', 10 );
add_action( 'springoo_shop_labels', 'springoo_get_product_badge', 15 );

/** ----------------------------------------------------------------------
 * Product before title, title, after title hooks
 * ----------------------------------------------------------------------*/
add_action( 'springoo_before_shop_title', 'springoo_shop_header_open', 5 );
add_action( 'springoo_before_shop_title', 'springoo_shop_labels', 10 );
add_action( 'springoo_before_shop_title', 'springoo_shop_actions', 15 );
add_action( 'springoo_before_shop_title', 'springoo_product_loop_gallery', 20 );
add_action( 'springoo_before_shop_title', 'springoo_add_cart_link', 25 );
add_action( 'springoo_before_shop_title', 'springoo_shop_header_close', 30 );
add_action( 'springoo_before_shop_title', 'springoo_shop_content_open', 35 );

add_action( 'springoo_shop_title', 'springoo_shop_title', 10 );

add_action( 'springoo_after_shop_title', 'springoo_shop_price', 15 );
add_action( 'springoo_after_shop_title', 'springoo_shop_rating', 5 );
add_action( 'springoo_after_shop_title', 'springoo_shop_content_close', 20 );
add_action( 'springoo_after_shop_title', 'springoo_loop_stock_message', 30 );

/** ----------------------------------------------------------------------
 * Product after title hooks
 * ----------------------------------------------------------------------*/

add_filter( 'springoo_product_classes', 'springoo_shop_footer_cart_wishlist_btn' );

add_action( 'pre_get_posts', 'springoo_change_products_query_for_page' );

add_filter( 'woocommerce_product_tabs', 'springoo_wc_product_specification_tab' );

add_filter( 'woocommerce_get_availability_text', 'springoo_wc_stock_message', 10, 2 );

add_filter( 'woocommerce_page_title', 'custom_wc_page_title' );

add_action( 'wp_footer', 'springoo_product_share_content' );

add_action( 'wp_ajax_springoo_search_product', 'springoo_search_product' );
add_action( 'wp_ajax_nopriv_springoo_search_product', 'springoo_search_product' );

// End of file springoo-woocommerce-hook.php

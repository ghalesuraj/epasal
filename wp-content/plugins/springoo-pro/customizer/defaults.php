<?php
// Springoo Pro Default Options
if (class_exists('Springoo_Pro')) {

	global $springoo_defaults;

	// Default Values.
	$springoo_defaults['woocommerce_shop_archive_show_product_price'] = 1;
	$springoo_defaults['woocommerce_shop_archive_show_product_rating'] = 1;
	$springoo_defaults['woocommerce_shop_archive_show_product_rating_count'] = 0;
	$springoo_defaults['woocommerce_shop_archive_show_product_category'] = 1;
	$springoo_defaults['woocommerce_shop_archive_show_product_stock'] = 1;

	$springoo_defaults['woocommerce_shop_archive_grid_gap'] = 'custom';
	$springoo_defaults['woocommerce_shop_archive_grid_gap_size'] = 24;

	$springoo_defaults['woocommerce_shop_archive_action_pos'] = 'right';
	$springoo_defaults['woocommerce_shop_archive_action_style'] = 'rounded';

	$springoo_defaults['woocommerce_shop_archive_label_type'] = 'fill';
	$springoo_defaults['woocommerce_shop_archive_label_style'] = 'block';
	$springoo_defaults['woocommerce_shop_archive_label_top_position'] = 0;
	$springoo_defaults['woocommerce_shop_archive_label_left_position'] = 0;
	$springoo_defaults['woocommerce_shop_archive_show_discount'] = 1;
	$springoo_defaults['woocommerce_shop_archive_show_featured'] = 1;
	$springoo_defaults['woocommerce_shop_archive_show_stock'] = 1;
	$springoo_defaults['woocommerce_shop_archive_show_product_badge'] = 1;

	$springoo_defaults['woocommerce_shop_archive_btn_type'] = 'fill-primary';
	$springoo_defaults['woocommerce_shop_archive_btn_position'] = 'in-footer';
	$springoo_defaults['woocommerce_shop_archive_btn_text'] = 'Add to Cart';
	$springoo_defaults['woocommerce_shop_archive_show_btn_text'] = 1;
	$springoo_defaults['woocommerce_shop_archive_show_btn_icon'] = 0;

	//woocommerce single
	$springoo_defaults['woocommerce_single_gallery_style'] = 'default';
	$springoo_defaults['woocommerce_single_summery_sticky_enable'] = 1;
	$springoo_defaults['woocommerce_single_stock'] = 'default';
	$springoo_defaults['woocommerce_single_stock_position'] = 'after_price';
	$springoo_defaults['woocommerce_single_store_info_enable'] = 0;
	$springoo_defaults['woocommerce_single_cart_btn_type'] = 'filled';
	$springoo_defaults['woocommerce_single_cart_btn_radius'] = 12;
	$springoo_defaults['woocommerce_single_cart_btn_enable'] = 0;
	$springoo_defaults['woocommerce_single_buy_btn_type'] = 'filled';
	$springoo_defaults['woocommerce_single_buy_btn_radius'] = 12;

	// Choices.
	$springoo_defaults['choices']['woocommerce_shop_archive_grid_gap'] = [
		'no-gap' => 'No Gap',
		'custom' => 'Custom',
	];
	$springoo_defaults['choices']['woocommerce_shop_archive_grid_gap_size'] = [
		'min'  => 0,
		'max'  => 100,
		'step' => 1,
	];

	$springoo_defaults['choices']['woocommerce_shop_archive_action_pos'] = [
		'right'  => 'Right',
		'bottom' => 'Bottom',
	];

	$springoo_defaults['choices']['woocommerce_shop_archive_action_style'] = [
		'rounded' => 'Rounded',
		'normal'  => 'Normal',
	];

	$springoo_defaults['choices']['woocommerce_shop_archive_label_type'] = [
		'fill'    => 'Filled',
		'outline' => 'Outline',
	];
	$springoo_defaults['choices']['woocommerce_shop_archive_label_style'] = [
		'block'  => 'Block',
		'inline' => 'Inline',
	];
	$springoo_defaults['choices']['woocommerce_shop_archive_label_top_position'] = [
		'min'  => 0,
		'max'  => 100,
		'step' => 1,
	];
	$springoo_defaults['choices']['woocommerce_shop_archive_label_left_position'] = [
		'min'  => 0,
		'max'  => 100,
		'step' => 1,
	];
	$springoo_defaults['choices']['woocommerce_shop_archive_btn_type'] = [
		'outline'         => __( 'Outline', 'springoo-pro' ),
		'fill'            => __( 'Fill', 'springoo-pro' ),
		'outline-primary' => __( 'Outline Primary', 'springoo-pro' ),
		'fill-primary'    => __( 'Fill Primary', 'springoo-pro' ),
	];
	$springoo_defaults['choices']['woocommerce_shop_archive_btn_position'] = [
		'in-header'   => __( 'In Header', 'springoo-pro' ),
		'in-footer'   => __( 'In Footer', 'springoo-pro' ),
		'after-price' => __( 'After Price', 'springoo-pro' ),
	];

	//woocommerce single
	$springoo_defaults['choices']['woocommerce_single_gallery_style'] = [
		'default'  => __( 'Default', 'springoo-pro' ),
		'left' => __( 'Left Thumbnail', 'springoo-pro' ),
		'right' => __( 'Right Thumbnail', 'springoo-pro' ),
		'grid' => __( 'Grid', 'springoo-pro' ),
	];
	$springoo_defaults['choices']['woocommerce_single_stock'] = [
		'default'  => __( 'Default', 'springoo-pro' ),
		'Custom' => __( 'Custom', 'springoo-pro' ),
	];

	$springoo_defaults['choices']['woocommerce_single_stock_position'] = apply_filters( 'springoo_pro_stock_positions_setting', [
		'before_title' => __( 'Before Title', 'springoo-pro' ),
		'after_title' => __( 'After Title', 'springoo-pro' ),
		'after_price'  => __( 'After Price', 'springoo-pro' ),
		'after_add_to_cart'  => __( 'After add to cart', 'springoo-pro' ),
	]);

	$springoo_defaults['choices']['woocommerce_single_cart_btn_type']   = [
		'outlined' => __( 'Outlined', 'springoo' ),
		'filled'   => __( 'Filled', 'springoo' ),
	];
	$springoo_defaults['choices']['woocommerce_single_cart_btn_radius'] = [
		'min'  => 0,
		'max'  => 100,
		'step' => 1,
	];
	$springoo_defaults['choices']['woocommerce_single_buy_btn_type']    = [
		'outlined' => __( 'Outlined', 'springoo' ),
		'filled'   => __( 'Filled', 'springoo' ),
	];
	$springoo_defaults['choices']['woocommerce_single_buy_btn_radius']  = [
		'min'  => 0,
		'max'  => 100,
		'step' => 1,
	];
}

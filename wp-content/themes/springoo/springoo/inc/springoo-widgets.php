<?php
/**
 * Register Custom Block Category
 */
function springoo_block_category( $block_categories ) {

	$springoo_blocks[] = array(
		'slug'  => 'springoo',
		'title' => __( 'Springoo', 'springoo' ),
		'icon'  => null,
	);
	return array_merge( $springoo_blocks, $block_categories );
}
add_filter( 'block_categories_all', 'springoo_block_category', 10, 2 );

/**
}
add_filter( 'block_categories_all', 'springoo_block_category', 10, 2 );

/**
 * Gutenberg block
 *
 * @return void
 */
function springoo_gutenberg_widgets() {
	$widgets = array(
		'info',
		'app',
		'address',
		'column',
	);

	wp_register_style( 'springoo-blocks-css',  springoo_asset_url( '/dist/css/widgets.css'), array(), SPRINGOO_THEME_VERSION );

	foreach ( $widgets as $widget ) {
		// Register Block script
		$widget_file_name = str_replace('-', '_', $widget);
		wp_register_script( "springoo-{$widget}-block-js",  springoo_asset_url( "/dist/js/$widget_file_name.js" ), array( 'wp-widgets', 'wp-components', 'wp-element' ), SPRINGOO_THEME_VERSION, 'false');

		//Load scripts and styles for specific block
		register_block_type("springoo/{$widget}", array( //phpcs:ignore
			'editor_script' => "springoo-{$widget}-block-js",
			'editor_style'  => 'springoo-blocks-css',
		) );
	}

}
add_action( 'init', 'springoo_gutenberg_widgets' );

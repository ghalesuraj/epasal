<?php
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * One Click Demo import required and recommended plugins
 *
 * @param $plugins
 *
 * @return array
 */
function springoo_ocdi_register_plugins( $plugins ) {
	$theme_plugins = springoo_recommended_plugins();

	return array_merge( $plugins, $theme_plugins );
}
add_filter( 'ocdi/register_plugins', 'springoo_ocdi_register_plugins' );


/**
 * One click demo import data
 *
 * @return array[]
 */
function springoo_ocdi_import_files() {
	return [
		[
			'import_file_name'           => 'Springoo V1',
			'categories'                 => [],
			'import_file_url'            => SPRINGOO_THEME_URI . 'assets/sample-data/demo.xml',
			'import_widget_file_url'     => SPRINGOO_THEME_URI . 'assets/sample-data/widgets.wie',
			'import_customizer_file_url' => SPRINGOO_THEME_URI . 'assets/sample-data/customizer.dat',
			'import_preview_image_url'   => 'https://s3.amazonaws.com/themerox.com/springoo/docs/homepage1.jpg',
			'preview_url'                => 'https://demo.themerox.com/springoo',
		],
		[
			'import_file_name'           => 'Springoo V2',
			'categories'                 => [],
			'import_file_url'            => SPRINGOO_THEME_URI . 'assets/sample-data/demo.xml',
			'import_widget_file_url'     => SPRINGOO_THEME_URI . 'assets/sample-data/widgets.wie',
			'import_customizer_file_url' => SPRINGOO_THEME_URI . 'assets/sample-data/customizer.dat',
			'import_preview_image_url'   => 'https://s3.amazonaws.com/themerox.com/springoo/docs/homepage2.jpg',
			'preview_url'                => 'https://demo.themerox.com/springoo/home-2',
		],
		[
			'import_file_name'           => 'Springoo V3',
			'categories'                 => [],
			'import_file_url'            => SPRINGOO_THEME_URI . 'assets/sample-data/demo.xml',
			'import_widget_file_url'     => SPRINGOO_THEME_URI . 'assets/sample-data/widgets.wie',
			'import_customizer_file_url' => SPRINGOO_THEME_URI . 'assets/sample-data/customizer.dat',
			'import_preview_image_url'   => 'https://s3.amazonaws.com/themerox.com/springoo/docs/homepage3.jpg',
			'preview_url'                => 'https://demo.themerox.com/springoo/home-3',
		],
	];
}
add_filter( 'ocdi/import_files', 'springoo_ocdi_import_files' );

/**
 * Setting this for demo
 *
 * @return void
 */
function ocdi_after_import_setup( $selected_import ) {

	$main_menu            = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
	$header_top_left_menu = get_term_by( 'name', 'Header Top Left Menu', 'nav_menu' );
	$footer_menu          = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations',
		array(
			'primary'         => $main_menu->term_id,
			'header_top_left' => $header_top_left_menu->term_id,
			'footer_menu'     => $footer_menu->term_id,
		)
	);


	// Assign front page and posts page (blog page).
	if ( 'Springoo V2' === $selected_import['import_file_name'] ) {
		$home = 'Home 2';
	} elseif ( 'Springoo V3' === $selected_import['import_file_name'] ) {
		$home = 'Home 3';
	} else {
		$home = 'Home';
	}
	$front_page = get_posts(
		array(
			'post_type' => 'page',
			'title'     => $home,
		)
	);

	if ( ! empty( $front_page ) ) {
		$front_page = $front_page[0];
	} else {
		$front_page = null;
	}
	update_option( 'page_on_front', $front_page->ID );

	$posts_page = get_posts(
		array(
			'post_type' => 'page',
			'title'     => 'Blog',
		)
	);

	if ( ! empty( $posts_page ) ) {
		$posts_page = $posts_page[0];
	} else {
		$posts_page = null;
	}
	update_option( 'page_for_posts', $posts_page->ID );

	// Delete Home pages
	if ( 'Springoo V2' === $selected_import['import_file_name'] ) {
		$delete_pages = [ 'Home', 'Home 3' ];
	} elseif ( 'Springoo V3' === $selected_import['import_file_name'] ) {
		$delete_pages = [ 'Home', 'Home 3' ];
	} else {
		$delete_pages = [ 'Home 2', 'Home 3' ];
	}

	foreach ( $delete_pages as $page_titles ) {
		$delete_page = get_posts(
			array(
				'post_type' => 'post',
				'title'     => $page_titles,
			)
		);

		if ( ! empty( $delete_page ) ) {
			$delete_page = $delete_page[0];
		} else {
			$delete_page = null;
		}

		if ( $delete_page && current_user_can( 'delete_post', $delete_page->ID ) ) {
			wp_delete_post( $delete_page->ID );
		}
	}

	// Delete Hello world post
	$hello_post = get_posts(
		array(
			'post_type' => 'post',
			'title'     => 'Hello world!',
		)
	);

	if ( ! empty( $hello_post ) ) {
		$hello_post = $hello_post[0];
	} else {
		$hello_post = null;
	}

	if ( $hello_post && current_user_can( 'delete_post', $hello_post->ID ) ) {
		wp_delete_post( $hello_post->ID );
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'permalink_structure', '/%postname%/' );
	update_option( 'blogname', 'Springoo' );
	update_option( 'blogdescription', 'Fashion Store' );


	//Import Revolution Slider
	if ( class_exists( 'RevSlider' ) ) {
		if ( 'Springoo V2' === $selected_import['import_file_name'] ) {
			$slider_array = array(
				SPRINGOO_THEME_DIR . 'assets/sample-data/slider-2.zip',
			);
		} elseif ( 'Springoo V3' === $selected_import['import_file_name'] ) {
			$slider_array = array(
				SPRINGOO_THEME_DIR . 'assets/sample-data/slider-3.zip',
				SPRINGOO_THEME_DIR . 'assets/sample-data/home-3-slider-1.zip',
			);
		} else {
			$slider_array = array(
				SPRINGOO_THEME_DIR . 'assets/sample-data/slider-5.zip',
			);
		}

		$slider = new RevSlider();

		foreach ( $slider_array as $filepath ) {
			$slider->importSliderFromPost( true, true, $filepath );
		}
		echo esc_html__( 'Slider processed', 'springoo' );
	}

	// Update woocompare icon
	if ( defined( 'YITH_WOOCOMPARE' ) ) {
		update_option( 'springoo_yith_woocompare_compare_icon', 'si-thin-repeat' );
	}

	// Remove demo widget from main sidebar
	$sidebar = get_option( 'sidebars_widgets' );

	if ( $sidebar ) {

		if ( 'block-2' === $sidebar['main-sidebar'][0] ) {
			unset( $sidebar['main-sidebar'][0] );
		}

		if ( 'block-3' === $sidebar['main-sidebar'][1] ) {
			unset( $sidebar['main-sidebar'][1] );
		}

		if ( 'block-4' === $sidebar['main-sidebar'][2] ) {
			unset( $sidebar['main-sidebar'][2] );
		}
	}

	update_option( 'sidebars_widgets', $sidebar, true );

	// Import Variation swatches Plugin data
	if ( class_exists( 'Woo_Variation_Swatches' ) ) {
		global $wp_filesystem;

		require_once ( ABSPATH . '/wp-admin/includes/file.php' ); //phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		WP_Filesystem();

		$swatches = $wp_filesystem->get_contents( SPRINGOO_THEME_DIR . 'assets/sample-data/swatches_data.json' );
		if ( $swatches ) {
			$swatches = json_decode( $swatches, true );

			if ( get_option( 'woo_variation_swatches' ) ) {
				update_option( 'woo_variation_swatches', $swatches );
			} else {
				add_option( 'woo_variation_swatches', $swatches );
			}
		}

		// Set Color attribute default color type
		$color_attribute = wc_attribute_taxonomy_id_by_name( 'color' );
		if ( $color_attribute ) {
			wc_update_attribute( $color_attribute, array( 'type' => 'color' ) );
		}
	}

	// Import YITH Wishlist data
	if ( defined( 'YITH_WCWL' ) ) {
		// show wishlist on loop
		if ( get_option( 'yith_wcwl_show_on_loop' ) ) {
			update_option( 'yith_wcwl_show_on_loop', 'yes' );
		} else {
			add_option( 'yith_wcwl_show_on_loop', 'yes' );
		}

		// show wishlist loop position
		if ( get_option( 'yith_wcwl_loop_position' ) ) {
			update_option( 'yith_wcwl_loop_position', 'before_image' );
		} else {
			add_option( 'yith_wcwl_loop_position', 'before_image' );
		}
	}

	// Import YITH WOOCOMPARE
	if ( defined( 'YITH_WOOCOMPARE' ) ) {
		// show woocompare icon on product list
		if ( get_option( 'yith_woocompare_compare_button_in_products_list' ) ) {
			update_option( 'yith_woocompare_compare_button_in_products_list', 'yes' );
		} else {
			add_option( 'yith_woocompare_compare_button_in_products_list', 'yes' );
		}
	}

}
//ocdi/after_content_import_execution
add_action( 'pt-ocdi/after_import', 'ocdi_after_import_setup' );

function after_content_import_execution() {
	if ( class_exists( 'Elementor\Plugin' ) ) {
		// Update Elementor Theme Kit Option.
		$query = get_posts( [
			'post_type'   => 'elementor_library',
			'post_status' => 'publish',
			'numberposts' => 1,
			'meta_query'  => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				[
					'key'   => '_springoo_current_kit',
					'value' => '1',
				],
				[
					'key'   => '_elementor_template_type',
					'value' => 'kit',
				],
			],
		] );

		if ( ! empty( $query ) && isset( $query[0] ) && isset( $query[0]->ID ) ) {
			update_option( 'elementor_active_kit', $query[0]->ID );
		}
	}

}
add_action( 'ocdi/after_content_import_execution', 'after_content_import_execution' );

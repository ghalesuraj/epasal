<?php
defined( 'ABSPATH' ) || die();

class Icons_Library {

	public static function init() {
		add_filter( 'elementor/icons_manager/native', [ __CLASS__, 'add_icons_tab' ] );
	}

	public static function add_icons_tab( $tabs ) {
		$theme_name                     = str_replace( ' ', '-', trim( strtolower( wp_get_theme()->get( 'Name' ) ) ) );
		$tabs[ $theme_name . '-icons' ] = [
			'name'          => 'fa-' . $theme_name . '-icons',
			'label'         => ucwords( $theme_name ) . ' Icons',
			'url'           => SPRINGOO_THEME_URI . '/assets/dist/css/springoo-icons.css',
			'enqueue'       => [ SPRINGOO_THEME_URI . '/assets/dist/css/springoo-icons.css' ],
			'prefix'        => 'si-',
			'displayPrefix' => 'si',
			'labelIcon'     => 'si si-bold-springoo-logo',
			'ver'           => SPRINGOO_THEME_VERSION,
			'fetchJson'     => SPRINGOO_THEME_URI . 'assets/' . $theme_name . '-icons-elementor.json?v=' . SPRINGOO_THEME_VERSION,
			'native'        => true,
		];

		return $tabs;
	}
}

Icons_Library::init();

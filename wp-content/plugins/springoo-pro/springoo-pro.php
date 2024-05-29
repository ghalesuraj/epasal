<?php
/**
 * Plugin Name: Springoo PRO
 * Plugin URI: 
 * Description: This is a companion plugin for Springoo PRO version.
 * Version: 1.0.0
 * Author: 
 * Author URI: 
 * License: GPL2
 * Text Domain: springoo-pro
 * Domain Path: /languages/
 *
 * Requires PHP: 5.6.0
 * Requires at least: 5.0
 * Tested up to: 5.8.0
 * WC requires at least: 5.6.0
 * WC tested up to: 5.8.0
 *
 * @package springoo-pro
 * @version 1.0.0
 */

/**
 * Copyright (c) 2020 springoo
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// Don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! defined( 'SPRINGOO_PRO_VERSION' ) ) {
	define( 'SPRINGOO_PRO_VERSION', '1.0.0' );
}

if ( ! defined( 'SPRINGOO_PRO_PATH' ) ) {
	/** @define "SPRINGOO_PRO_PATH" "./" */
	define( 'SPRINGOO_PRO_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'SPRINGOO_PRO_FILE' ) ) {
	/**
	 * Plugin File Ref.
	 *
	 * @var string
	 */
	define( 'SPRINGOO_PRO_FILE', __FILE__ );
}

if ( ! defined( 'SPRINGOO_PRO_URL' ) ) {
	define( 'SPRINGOO_PRO_URL', plugin_dir_url( __FILE__ )  );
}

if ( ! class_exists( 'Springoo_Pro', false ) ) {
	require_once SPRINGOO_PRO_PATH . 'class-springoo-pro.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
}

/**
 * Initialize the plugin
 *
 * @return Springoo_Pro
 */
function springoo_pro() {
	return Springoo_Pro::get_instance();
}

// Kick it off.
springoo_pro();
// End of file springoo-pro.php.

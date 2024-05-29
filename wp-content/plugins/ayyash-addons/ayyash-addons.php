<?php
/**
 * Addon elements for Elementor Page Builder
 *
 * Plugin Name: Ayyash Addons
 * Description: Addon elements for Elementor Page Builder & Gutenberg.
 * Plugin URI: https://wpayyash.com/plugins/ayyash-addons/
 * Version: 1.0.5
 * Author: ThemeRox
 * Author URI: https://themerox.com
 * Text Domain: ayyash-addons
 * Domain Path: /languages/
 *
 * [PHP]
 * Requires PHP: 7.4
 *
 * [WP]
 * Requires at least: 5.2
 * Tested up to: 6.3
 *
 * [Elementor]
 * Elementor requires at least: 3.2.5
 * Elementor tested up to: 3.16
 *
 * [WC]
 * WC requires at least: 5.9
 * WC tested up to: 8.1
 *
 * @package ayyash-addons
 */

namespace AyyashAddons;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! defined( 'AYYASH_ADDONS_VERSION' ) ) {
	/**
	 * Plugin Version.
	 */
	define( 'AYYASH_ADDONS_VERSION', '1.0.5' );
}
if ( ! defined( 'AYYASH_ADDONS_FILE' ) ) {
	/**
	 * Plugin File Ref.
	 */
	define( 'AYYASH_ADDONS_FILE', __FILE__ );
}
if ( ! defined( 'AYYASH_ADDONS_BASE' ) ) {
	/**
	 * Plugin Base Name.
	 */
	define( 'AYYASH_ADDONS_BASE', plugin_basename( AYYASH_ADDONS_FILE ) );
}
if ( ! defined( 'AYYASH_ADDONS_PATH' ) ) {
	/** @define "AYYASH_ADDONS_PATH" "./" */
	/**
	 * Plugin Dir Ref.
	 */
	define( 'AYYASH_ADDONS_PATH', plugin_dir_path( AYYASH_ADDONS_FILE ) );
}
if ( ! defined( 'AYYASH_ADDONS_URL' ) ) {
	/**
	 * Plugin URL.
	 */
	define( 'AYYASH_ADDONS_URL', plugin_dir_url( AYYASH_ADDONS_FILE ) );
}
if ( ! defined( 'AYYASH_ADDONS_WIDGETS_PATH' ) ) {
	/** @define "AYYASH_ADDONS_WIDGETS_PATH" "./widgets/" */
	/**
	 * Widgets Dir Ref.
	 */
	define( 'AYYASH_ADDONS_WIDGETS_PATH', AYYASH_ADDONS_PATH . 'widgets/' );
}
if ( ! class_exists( 'AyyashAddons\Ayyash_Addons', false ) ) {
	require_once AYYASH_ADDONS_PATH . 'class-ayyash-addons.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
}



/**
 * Initialize the plugin
 *
 * @return Ayyash_Addons|null
 */
function ayyash_addons() {
	return Ayyash_Addons::instance();
}

// Kick it off.
ayyash_addons();

// End of file ayyash-addons.php.

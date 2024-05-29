<?php
/**
 * Addon elements for Elementor Page Builder & Gutenberg.
 *
 * Plugin Name: Ayyash Addons Pro
 * Description: Addon elements for Elementor Page Builder & Gutenberg.
 * Plugin URI: https://wpayyash.com/plugins/ayyash-addons/
 * Version: 1.0.5
 * Author: ThemeRox
 * Author URI: https://themerox.com
 * Text Domain: ayyash-addons-pro
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
 * @package ayyash-addons-pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! defined( 'AYYASH_ADDONS_PRO_VERSION' ) ) {
	/**
	 * Plugin Version.
	 */
	define( 'AYYASH_ADDONS_PRO_VERSION', '1.0.5' );
}
if ( ! defined( 'AYYASH_ADDONS_PRO_FILE' ) ) {
	/**
	 * Plugin File Ref.
	 */
	define( 'AYYASH_ADDONS_PRO_FILE', __FILE__ );
}
if ( ! defined( 'AYYASH_ADDONS_PRO_BASE' ) ) {
	/**
	 * Plugin Base Name.
	 */
	define( 'AYYASH_ADDONS_PRO_BASE', plugin_basename( AYYASH_ADDONS_PRO_FILE ) );
}
if ( ! defined( 'AYYASH_ADDONS_PRO_PATH' ) ) {
	/** @define "AYYASH_ADDONS_PRO_PATH" "./" */
	/**
	 * Plugin Dir Ref.
	 */
	define( 'AYYASH_ADDONS_PRO_PATH', plugin_dir_path( AYYASH_ADDONS_PRO_FILE ) );
}
if ( ! defined( 'AYYASH_ADDONS_PRO_URL' ) ) {
	/**
	 * Plugin URL.
	 */
	define( 'AYYASH_ADDONS_PRO_URL', plugin_dir_url( AYYASH_ADDONS_PRO_FILE ) );
}
if ( ! defined( 'AYYASH_ADDONS_PRO_WIDGETS_PATH' ) ) {
	/** @define "AYYASH_ADDONS_PRO_WIDGETS_PATH" "./widgets/" */
	/**
	 * Widgets Dir Ref.
	 */
	define( 'AYYASH_ADDONS_PRO_WIDGETS_PATH', AYYASH_ADDONS_PRO_PATH . 'widgets/' );
}

function ayyash_addons_pro_init() {
	if ( ! class_exists( '\AyyashAddonsPro\Plugin', false ) ) {
		require_once AYYASH_ADDONS_PRO_PATH . 'class-plugin.php';
	}
}

function ayyash_addons_pro_missing_main_plugin() {
	if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	$message = sprintf(
	/* translators: 1: Plugin name 2: Ayyash Addons 3. Anchor Tag Opening 4. Anchor Tag Closing */
		esc_html__( '"%1$s" requires "%2$s" to be installed and activated. You can install and activate %2$s from %3$shere%4$s.', 'ayyash-addons-pro' ),
		'<strong>' . esc_html__( 'Ayyash Addons Pro', 'ayyash-addons-pro' ) . '</strong>',
		'<strong>' . esc_html__( 'Ayyash Addons', 'ayyash-addons-pro' ) . '</strong>',
		'<a href="https://wordpress.org/plugins/ayyash-addons/">',
		'</a>'
	);

	// @XXX We might need to check the version of the free plugin in certain cases.

	printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


/**
 * Run the plugin.
 *
 * @return void
 */
function ayyash_addons_pro() {

	load_plugin_textdomain( 'ayyash-addons-pro', false, AYYASH_ADDONS_PRO_PATH . '/languages/' );

	add_action( 'ayyash_addons/loaded', 'ayyash_addons_pro_init' );

	if ( ! is_plugin_active( 'ayyash-addons/ayyash-addons.php' ) ) {
		add_action( 'admin_notices', 'ayyash_addons_pro_missing_main_plugin' );
	}
}


ayyash_addons_pro();

// End of file ayyash-addons-pro.php.

<?php
/**
 * The header.
 *
 * Displays all of the <head> section and everything up till end of </header>
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'default-header' ); ?>>
<?php
wp_body_open();
/**
 * Custom action before page
 */
do_action( 'springoo_before_page' );
?>
<div id="page" class="hfeed site">
	<header>
		<?php

		/**
		 * Custom section before main header
		 */
		do_action( 'springoo_before_header' );

		/**
		 * Hooked springoo_secondary_head
		 */
		do_action( 'springoo_header_top' );

		/**
		 * Hooked springoo_master_head
		 */
		do_action( 'springoo_header_main' );



		/**
		 * Mobile Menu
		 */

		do_action( 'springoo_header_mobile_menu' );

		/**
		 * Custom action after main header
		 */

		do_action( 'springoo_after_header' );
		?>
	</header>

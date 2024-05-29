<?php
/**
 * The template for displaying search results pages.
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

$springoo_layout = springoo_get_mod( 'layout_search_style' );

get_header();

get_template_part( 'partials/content', $springoo_layout );

get_footer();

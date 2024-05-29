<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

$springoo_sticky_label = springoo_get_mod( 'sticky_label' );

if ( is_sticky() && $springoo_sticky_label ) : ?>
	<div class="sticky-label"><span class="sticky-post-label"><?php echo esc_html( wp_strip_all_tags( $springoo_sticky_label ) ); ?></span></div>
	<?php
endif;

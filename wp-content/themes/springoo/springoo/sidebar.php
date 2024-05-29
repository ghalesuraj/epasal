<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! is_active_sidebar( 'main-sidebar' ) ) {
	return;
}

$springoo_sidebar_grid = springoo_get_mod( 'layout_global_sidebar_grid' );

?>
<div id="secondary" class="widget-area main-sidebar col-md-<?php echo esc_attr( $springoo_sidebar_grid ); ?>" role="complementary">
	<?php dynamic_sidebar( 'main-sidebar' ); ?>
</div><!-- #secondary -->

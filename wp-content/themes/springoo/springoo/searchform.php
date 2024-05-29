<?php
/**
 * The template for displaying search form.
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( class_exists('woocommerce') ) {
	$placeholder = 'Search for Products...';
} else {
	$placeholder = 'Search for Posts...';
}
?>
<form class="search" action="<?php echo esc_url( home_url() ); ?>" method="get">
	<fieldset>
		<div class="text">
			<label for="s" class="screen-reader-text"><?php esc_html_e( 'Search', 'springoo' ); ?></label>
			<input name="s" id="s" type="text" class="search-field" placeholder="<?php echo esc_attr( $placeholder ); ?>" value="<?php echo get_search_query(); ?>" />
			<button class="search-button" value="<?php echo esc_attr_x( 'Search', 'submit button', 'springoo' ); ?>"><i class="si si-thin-search-2" aria-hidden="true"></i><span class="sr-only"><?php esc_html_e( 'Search', 'springoo' ); ?></span></button>
		</div>
	</fieldset>
</form>


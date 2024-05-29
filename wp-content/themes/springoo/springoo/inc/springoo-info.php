<?php
/**
 * Theme info page
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Add the theme page.
 *
 * @return void
 */
function springoo_add_theme_info() {
	add_theme_page(
		__( 'Springoo Theme Info', 'springoo' ),
		__( 'Springoo Info', 'springoo' ),
		'manage_options',
		'springoo-theme-info',
		'springoo_info_page'
	);
}

add_action( 'admin_menu', 'springoo_add_theme_info' );

/**
 * Springoo Info page
 *
 * @return void
 */
function springoo_info_page() {
	?>
	<div class="info-container">
		<h2 class="info-title"><?php esc_html_e( 'Springoo Theme Information', 'springoo' ); ?></h2>
		<div class="info-block">
			<a href="https://demo.springoo.com/springoo/" target="_blank">
				<div class="dashicons dashicons-desktop info-icon"></div>
				<p class="info-text"><?php esc_html_e( 'Theme Demo', 'springoo' ); ?></p>
			</a>
		</div>
		<div class="info-block">
			<a href="https://docs.springoo.com/themes/springoo/" target="_blank">
				<div class="dashicons dashicons-book-alt info-icon"></div>
				<p class="info-text"><?php esc_html_e( 'Theme Documentation', 'springoo' ); ?></p>
			</a>
		</div>
	</div>
	<?php
}

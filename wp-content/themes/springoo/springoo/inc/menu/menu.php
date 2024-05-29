<?php
/**
 * Mega Menu
 *
 * @package Springoo/MegaMenu
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/** @define "SPRINGOO_THEME_DIR" "./../../" */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

// phpcs:disable WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require SPRINGOO_THEME_DIR . 'inc/menu/class-springoo-walker-nav-menu-edit-custom.php';
require SPRINGOO_THEME_DIR . 'inc/menu/class-springoo-walker-nav-menu-custom.php';

require SPRINGOO_THEME_DIR . 'inc/menu/class-springoo-megamenu-api.php';
require SPRINGOO_THEME_DIR . 'inc/menu/class-springoo-icons-dialog.php';
// phpcs:enable

// Menu Helper Functions.

if ( ! function_exists( 'springoo_site_menu' ) ) {
	/**
	 * Site menu.
	 *
	 * @return void
	 */
	function springoo_site_menu() {
		?>
		<div id="site-nav" role="navigation" class="d-lg-block d-none">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array( 'theme_location' => 'primary' ) );
			} else {
				?>
				<div class="menu-not-assigned text-center"><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" class="springoo-sticky-item"><?php esc_html_e( 'No Menu Assigned , Please Add', 'springoo' ); ?></a></div>
			<?php } ?>
		</div>
		<?php
	}
}

// End of file menu.php

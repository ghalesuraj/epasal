<?php
/**
 * Icon Chooser Idalog.
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
 * Class Springoo_Icons_Dialog
 */
class Springoo_Icons_Dialog {

	/**
	 * Springoo_Icons_Dialog constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_footer', array( $this, 'icon_dialog' ), 99 );
		add_action( 'wp_ajax_springoo-icons', array( $this, 'icon_generator' ), 99 );
	}

	/**
	 *
	 * Icon Generator
	 *
	 * @return void
	 */
	public function icon_generator() {
		global $wp_filesystem;

		require_once ( ABSPATH . '/wp-admin/includes/file.php' ); //phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		WP_Filesystem();

		if ( is_readable( SPRINGOO_THEME_DIR . 'inc/icon/springoo-icons.json' ) && is_readable( SPRINGOO_THEME_DIR . 'inc/icon/themify-icons.json' ) ) {

			$neoteric_icons = json_decode( $wp_filesystem->get_contents( SPRINGOO_THEME_DIR . 'inc/icon/springoo-icons.json' ), true );
			$themify_icons  = json_decode( $wp_filesystem->get_contents( SPRINGOO_THEME_DIR . 'inc/icon/themify-icons.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

			$icons = array_merge( $neoteric_icons, $themify_icons );

			if ( ! empty( $icons ) ) {
				foreach ( $icons as $icon_cls => $icon ) {
					$label = ucwords( str_replace( '-', ' ', $icon ) );
					printf(
						'<a href="#" class="pick-icon" data-name="%1$s" title="%2$s" aria-label="%3$s"><span class="%1$s" aria-hidden="true"></span></a>',
						esc_attr( $icon_cls ),
						esc_attr( $label ),
						sprintf(
						/* translators: 1. Icon Name */
							esc_attr__( 'Select “%s“ Icon', 'springoo' ),
							esc_attr( $label )
						)
					);
				}
			}
		}
		die();
	}

	/**
	 *
	 * Icon Generator
	 *
	 * @return void
	 */
	public function icon_dialog() {
		?>
		<div id="icon-dialog" title="Icon Manager">
			<div id="dialog-header-wrap">
				<label for="icon-search" class="screen-reader-text"><?php esc_html_e( 'Search a icon...', 'springoo' ); ?></label>
				<input type="text" name="" id="icon-search" placeholder="<?php esc_attr_e( 'Search a icon...', 'springoo' ); ?>" value="">
			</div>
			<div id="dialog-shadow-up"></div>
			<div id="icon-load"></div>
			<div id="dialog-insert-button">
				<div id="dialog-shadow-down"></div>
				<a href="#" id="icon-insert" class="button button-primary button-large"><?php esc_html_e( 'Use this icon', 'springoo' ); ?></a>
			</div>
		</div>
		<div id="shortcode-overlay"></div>
		<?php
	}
}

new Springoo_Icons_Dialog();

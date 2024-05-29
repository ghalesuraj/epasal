<?php
/**
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}


if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Springoo_Customize_Misc_Control' ) ) :

	/**
	 * Class Springoo_Customize_Misc_Control
	 *
	 * Control for adding arbitrary HTML to a Customizer section.
	 *
	 * @since 1.0.0.
	 */

	class Springoo_Customize_Misc_Control extends WP_Customize_Control {

		/**
		 * The current setting name.
		 *
		 * @var   string    The current setting name.
		 */
		public $settings = 'blogname';

		/**
		 * The current setting description.
		 *
		 * @var   string    The current setting description.
		 */
		public $description = '';

		/**
		 * The current setting group.
		 *
		 * @var   string    The current setting group.
		 */
		public $group = '';

		/**
		 * Render the description and title for the section.
		 *
		 * Prints arbitrary HTML to a customizer section. This provides useful hints for how to properly set some custom
		 * options for optimal performance for the option.
		 *
		 * @return void
		 */
		public function render_content() {
			switch ( $this->type ) {
				default:
				case 'text':
					echo '<p class="description">' . wp_kses_post( $this->description ) . '</p>';
					break;

				case 'heading':
					echo '<span class="customize-control-title section-title">' . wp_kses_post( $this->label ) . '</span>';
					break;

				case 'line':
					echo '<hr />';
					break;

				case 'helptext':
					echo '<h3 class="section-separator-title">' . wp_kses_post( $this->label ) . '</h3>';
					echo '<p class="description">' . wp_kses_post( $this->description ) . '</p>';
					echo '<hr />';
					break;
			}
		}
	}
endif;


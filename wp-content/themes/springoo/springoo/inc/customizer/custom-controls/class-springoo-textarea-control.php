<?php
/**
 * Customize for textarea, extend the WP customizer
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * Class Springoo_Textarea_Control
 */
class Springoo_Textarea_Control extends WP_Customize_Control {

	/**
	 * Render the control's content.
	 *
	 * Allows the content to be overriden without having to rewrite the wrapper.
	 *
	 * @return  void
	 */
	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea class="large-text" cols="20" rows="5" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		</label>
		<?php
	}

}

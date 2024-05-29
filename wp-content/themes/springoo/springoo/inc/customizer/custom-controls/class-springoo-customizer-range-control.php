<?php
/**
 * Customize for Range Slider, extend the WP customizer
 *
 * @package Springoo/Customizer
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
 * Class Springoo_Customizer_Range_Control
 */
class Springoo_Customizer_Range_Control extends WP_Customize_Control {

	/**
	 * Type.
	 *
	 * @var string
	 */
	public $type = 'range';

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<input <?php $this->link(); ?> name="<?php echo esc_attr( $this->label ); ?>" type="range" min="<?php echo esc_attr( $this->choices['min'] ); ?>" max="<?php echo esc_attr( $this->choices['max'] ); ?>" step="<?php echo esc_attr( $this->choices['step'] ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="springoo-range">
			<input type="text" data-name="<?php echo esc_attr( $this->label ); ?>" class="springoo-range-output" value="<?php echo esc_attr( $this->value() ); ?>" disabled>
			<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
		</label>
		<?php
	}
}

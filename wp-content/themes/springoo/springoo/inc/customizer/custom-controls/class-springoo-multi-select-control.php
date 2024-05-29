<?php
/**
 * The template used for displaying page content in google font control
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

if ( ! class_exists( 'Springoo_Multi_Select_Control' ) ) :
	/**
	 * A class to create a dropdown for all google fonts
	 */
	class Springoo_Multi_Select_Control extends WP_Customize_Control {

		/**
		 * Render the content of the category dropdown.
		 *
		 * @return void
		 */
		public function render_content() {
			if ( empty( $this->choices ) ) {
				return;
			}

			$selected         = $this->value();
			$input_id         = '_customize-input-' . $this->id;
			$description_id   = '_customize-description-' . $this->id;
			$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';

			if ( ! is_array( $selected ) ) {
				// back-compt.
				$selected = empty( $selected ) ? [] : [ $selected ];
			}


			?>
			<?php if ( ! empty( $this->label ) ) : ?>
				<label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>

			<select id="<?php echo esc_attr( $input_id ); ?>" <?php echo esc_attr($describedby_attr); ?> <?php $this->link(); ?> multiple>
				<?php
				foreach ( $this->choices as $value => $label ) {
					echo '<option value="' . esc_attr( $value ) . '"' . selected( in_array( $value, $selected ), true, false ) . '>' . esc_html ( $label ) . '</option>';
				}
				?>
			</select>
			<?php
		}

	}
endif;


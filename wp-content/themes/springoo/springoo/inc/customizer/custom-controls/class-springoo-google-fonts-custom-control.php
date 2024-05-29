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

if ( ! class_exists( 'Springoo_Google_Fonts_Custom_Control' ) ) :
	/**
	 * A class to create a dropdown for all google fonts
	 */
	class Springoo_Google_Fonts_Custom_Control extends WP_Customize_Control {

		/**
		 * Render the content of the category dropdown.
		 *
		 * @return void
		 */
		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<select class="chosen-select" <?php $this->link(); ?>></select>
			</label>
			<?php
		}

	}
endif;

if ( ! function_exists( 'springoo_generate_font_control' ) ) :
	/**
	 * Adds all the required Font Controls (Family, variant, size etc) to the WP_Customize object
	 *
	 * @param WP_Customize_Manager $wp_customize customization.
	 * @param string $section_id section id.
	 * @param string $group_label Group label.
	 * @param string $group_id Group ID.
	 * @param bool $use_section_id Use section ID.
	 * @param array $exclude exclude array.
	 *
	 * @return void
	 */
	function springoo_generate_font_control( &$wp_customize, $section_id, $group_label, $group_id = null, $exclude = null ) {

		$font_setting_id = $group_id ? $section_id . '_' . $group_id : $section_id;

		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$font_setting_id,
				[
					'section' => $section_id,
					'type'    => 'heading',
					'label'   => $group_label,
				]
			)
		);

		if ( ! $exclude ) {
			$exclude = [];
		}

		if ( ! is_array( $exclude ) ) {
			$exclude = [ $exclude ];
		}

		if ( ! in_array( 'family', $exclude ) ) :
			$setting_id = $font_setting_id . '_font_family';

			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => springoo_get_default( $setting_id ),
					'type'              => 'theme_mod',
					'sanitize_callback' => 'esc_attr',
				)
			);

			$wp_customize->add_control(
				new Springoo_Google_Fonts_Custom_Control(
					$wp_customize,
					$setting_id,
					array(
						'label'    => 'Font Family',
						'section'  => $section_id,
						'settings' => $setting_id,
					)
				)
			);
		endif;

		if ( ! in_array( 'variant', $exclude ) ) :
			$setting_id = $font_setting_id . '_font_variant';
			$values     = springoo_get_default( $setting_id );

			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => $values,
					'type'              => 'theme_mod',
					'sanitize_callback' => 'esc_attr',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					$setting_id,
					[
						'label'       => __( 'Font Variant', 'springoo' ),
						'description' => __( 'Different variants of the font, provides control over font-weight and italics', 'springoo' ),
						'section'     => $section_id,
						'settings'    => $setting_id,
						'type'        => 'select',
						'choices'     => [
							'regular' => 'Regular',
						],
					]
				)
			);
		endif;

		if ( ! in_array( 'size', $exclude ) ) :
			$setting_id = $font_setting_id . '_font_size';

			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => springoo_get_default( $setting_id ),
					'type'              => 'theme_mod',
					'sanitize_callback' => 'esc_attr',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					$setting_id,
					array(
						'label'    => __( 'Font Size (px)', 'springoo' ),
						'section'  => $section_id,
						'settings' => $setting_id,
					)
				)
			);
		endif;

		if ( ! in_array( 'line_height', $exclude ) ) :
			$setting_id = $font_setting_id . '_line_height';

			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => springoo_get_default( $setting_id ),
					'type'              => 'theme_mod',
					'sanitize_callback' => 'esc_attr',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					$setting_id,
					array(
						'label'    => __( 'Line Height (em)', 'springoo' ),
						'section'  => $section_id,
						'settings' => $setting_id,
					)
				)
			);
		endif;

		if ( ! in_array( 'text_transform', $exclude ) ) :
			$setting_id = $font_setting_id . '_text_transform';

			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => springoo_get_default( $setting_id ),
					'type'              => 'theme_mod',
					'sanitize_callback' => 'esc_attr',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					$setting_id,
					array(
						'label'    => __( 'Text Transform', 'springoo' ),
						'section'  => $section_id,
						'settings' => $setting_id,
						'type'     => 'select',
						'choices'  => array(
							'none'       => __( 'None', 'springoo' ),
							'capitalize' => __( 'Capitalize', 'springoo' ),
							'uppercase'  => __( 'Uppercase', 'springoo' ),
							'lowercase'  => __( 'Lowercase', 'springoo' ),
						),
					)
				)
			);
		endif;

		if ( ! in_array( 'letter_spacing', $exclude ) ) :
			$setting_id = $font_setting_id . '_letter_spacing';

			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => springoo_get_default( $setting_id ),
					'type'              => 'theme_mod',
					'sanitize_callback' => 'esc_attr',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					$setting_id,
					array(
						'label'    => __( 'Letter Spacing (px)', 'springoo' ),
						'section'  => $section_id,
						'settings' => $setting_id,
					)
				)
			);
		endif;

		if ( ! in_array( 'word_spacing', $exclude ) ) :
			$setting_id = $font_setting_id . '_word_spacing';

			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => springoo_get_default( $setting_id ),
					'type'              => 'theme_mod',
					'sanitize_callback' => 'esc_attr',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					$setting_id,
					array(
						'label'    => __( 'Word Spacing (px)', 'springoo' ),
						'section'  => $section_id,
						'settings' => $setting_id,
					)
				)
			);
		endif;
	}
endif;

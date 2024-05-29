<?php
/**
 * Customizer Helper Functions.
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! function_exists( 'springoo_get_default' ) ) :

	/**
	 * Get default value for a theme option.
	 *
	 * @param string $name mod name.
	 *
	 * @return string String with default value.
	 */
	function springoo_get_default( $name ) {
		global $springoo_defaults;

		if ( $springoo_defaults && $name && isset( $springoo_defaults[ $name ] ) ) {
			return $springoo_defaults[ $name ];
		}

		return '';
	}
endif;

if ( ! function_exists( 'springoo_get_choices' ) ) :

	/**
	 * Get all Choices/Options for a dropdown.
	 *
	 * @param string $name mod name.
	 *
	 * @return array Array with all options.
	 */
	function springoo_get_choices( $name ) {
		global $springoo_defaults;

		if ( array_key_exists( $name, $springoo_defaults['choices'] ) ) {
			return $springoo_defaults['choices'][ $name ];
		}

		return [];
	}
endif;

if ( ! function_exists( 'springoo_get_default_mod' ) ) :

	/**
	 * Get the mod value or default value if mod is not set.
	 *
	 * @param string $name mod name.
	 * @param array $mods mods to check before check default mods.
	 *
	 * @return string Mod value.
	 */
	function springoo_get_default_mod( $name, $mods ) {
		global $springoo_defaults;

		if ( array_key_exists( $name, $mods ) && '' !== $mods[ $name ] ) {
			return $mods[ $name ];
		} elseif ( array_key_exists( $name, $springoo_defaults ) ) {
			return $springoo_defaults[ $name ];
		}

		return '';
	}
endif;

if ( ! function_exists( 'springoo_get_default_mods' ) ) :

	/**
	 * Get all Default Springoo Mod Options and Values.
	 *
	 * @return array    Array with key as option names and value as option values.
	 */
	function springoo_get_default_mods() {
		global $springoo_defaults;

		return $springoo_defaults;
	}
endif;

if ( ! function_exists( 'springoo_get_mods' ) ) :

	/**
	 * Returns all mods set by the user, returns the default values if any mod is not set.
	 *
	 * @return array
	 */
	function springoo_get_mods() {
		/**
		 * @var false|array $mods
		 */
		$mods = get_theme_mods();
		if ( ! $mods ) {
			$mods = [];
		}

		return array_merge( springoo_get_default_mods(), $mods );
	}
endif;

if ( ! function_exists( 'springoo_get_mod' ) ) :

	/**
	 * Wrapper for WordPress 'get_theme_mod' function.
	 *
	 * @param string $name mod name.
	 *
	 * @return mixed The string with the mod value.
	 */
	function springoo_get_mod( $name ) {
		return get_theme_mod( $name, springoo_get_default( $name ) );
	}
endif;

if ( ! function_exists( 'springoo_sanitize_float' ) ) :

	/**
	 * Sanitize function for WP_Customize setting to sanitize float values.
	 *
	 * @param mixed $value value to sanitize.
	 *
	 * @return float
	 */
	function springoo_sanitize_float( $value ) {
		return floatval( $value );
	}
endif;

if ( ! function_exists( 'springoo_sanitize_choice' ) ) :

	/**
	 * Sanitize function for WP_Customize setting to sanitize select.
	 *
	 * @param string|int $value choice value.
	 * @param string|object $setting mod name.
	 *
	 * @return string|int
	 */
	function springoo_sanitize_choice( $value, $setting ) {
		if ( is_object( $setting ) && isset( $setting->id ) ) {
			$setting = $setting->id;
		}

		$options = springoo_get_choices( $setting );

		if ( ! in_array( $value, array_keys( $options ) ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$value = springoo_get_default( $setting );
		}

		return $value;
	}
endif;

if ( ! function_exists( 'springoo_sanitize_font_family' ) ) :

	/**
	 * Sanitize function for WP_Customize setting to sanitize Font Family.
	 *
	 * @param string $value font_family.
	 * @param string|object $setting font_family mod name.
	 *
	 * @return string
	 */
	function springoo_sanitize_font_family( $value, $setting ) {

		if ( is_object( $setting ) && isset( $setting->id ) ) {
			$setting = $setting->id;
		}

		if ( ! is_string( $value ) || '' === $value ) {
			return '';
		} elseif ( ! in_array( $value, array_keys( springoo_get_all_fonts( false ) ) ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$value = springoo_get_default( $setting );
		}

		return $value;
	}
endif;

if ( ! function_exists( 'springoo_sanitize_font_variant' ) ) :

	/**
	 * Sanitize function for WP_Customize setting to sanitize Font Family Variant.
	 *
	 * @param string $value Font Variant.
	 *
	 * @return string
	 */
	function springoo_sanitize_font_variant( $value ) {

		$options = [
			'100',
			'100italic',
			'200',
			'200italic',
			'300',
			'300italic',
			'500',
			'500italic',
			'600',
			'600italic',
			'700',
			'700italic',
			'800',
			'800italic',
			'900',
			'900italic',
			'italic',
			'regular',
			'thin',
			'thin-italic',
			'bold',
			'bold-italic',
			'medium',
			'medium-italic',
			'extra-light',
			'extra-light-italic',
			'light',
			'light-italic',
			'serif',
			'serif-italic',
		];

		if ( ! is_string( $value ) || '' === $value ) {
			return 'regular';
		} elseif ( in_array( $value, $options, true ) ) {
			return $value;
		}

		return 'regular';
	}
endif;

if ( ! function_exists( 'springoo_sanitize_font_variant_array' ) ) {
	function springoo_sanitize_font_variant_array( $variants = [] ) {

		if ( ! is_array( $variants ) ) {
			return springoo_sanitize_font_variant( $variants );
		}

		foreach ( $variants as &$variant ) {
			$variant = springoo_sanitize_font_variant( $variant );
		}

		return $variants;
	}
}

if ( ! function_exists( 'springoo_sanitize_array_of_strings' ) ) {

	function springoo_sanitize_array_of_strings( $array ) {
		return array_map( 'sanitize_text_field', $array );
	}
}


if ( ! function_exists( 'springoo_sanitize_font_text_transform' ) ) :

	/**
	 * Sanitize function for WP_Customize setting to sanitize Font Text Transform.
	 *
	 * @param string $value Text transform.
	 *
	 * @return string
	 */
	function springoo_sanitize_font_text_transform( $value ) {

		$options = [ 'none', 'uppercase', 'lowercase' ];

		if ( ! is_string( $value ) || '' === $value ) {
			return 'none';
		} elseif ( in_array( $value, $options, true ) ) {
			return $value;
		}

		return 'none';
	}
endif;

if ( ! function_exists( 'springoo_sanitize_font_subsets' ) ) :

	/**
	 * Sanitize function for WP_Customize setting to sanitize Font Subsets.
	 *
	 * @param string|string[] $values Font subsets.
	 *
	 * @return string[]
	 */
	function springoo_sanitize_font_subsets( $values ) {

		$multi_values = ! is_array( $values ) ? explode( ',', $values ) : $values;

		return ! empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : [];
	}
endif;

if ( ! function_exists( 'springoo_get_color_mods' ) ) :

	/**
	 * Determine if a mod is a color mod
	 *
	 * @param string $mod mod name.
	 *
	 * @return bool
	 */
	function springoo_get_color_mods( $mod ) {
		return 0 === strpos( $mod, 'colors_' );
	}
endif;

if ( ! function_exists( 'springoo_get_font_mods' ) ) :

	/**
	 * Determine if a mod is a typography mod
	 *
	 * @param string $mod mod name.
	 *
	 * @return bool
	 */
	function springoo_get_font_mods( $mod ) {
		return 0 === strpos( $mod, 'typography_' );
	}
endif;

if ( ! function_exists( 'springoo_is_font_family' ) ) :

	/**
	 * Checks if a given mod is Font Family
	 *
	 * @param string $mod mod name.
	 *
	 * @return bool
	 */
	function springoo_is_font_family( $mod ) {
		return springoo_string_ends_with( $mod, 'font_family' ) || springoo_string_ends_with( $mod, 'font_variant' );
	}
endif;

if ( ! function_exists( 'springoo_string_ends_with' ) ) :

	/**
	 * Determine if a string ends with a particulr value
	 *
	 * @param string $whole the full string.
	 * @param string $end part to check.
	 *
	 * @return bool
	 */
	function springoo_string_ends_with( $whole, $end ) {
		return strpos( $whole, $end ) !== false && strpos( $whole, $end, strlen( $whole ) - strlen( $end ) ) !== false;
	}
endif;

if ( ! function_exists( 'springoo_font_settings' ) ) :

	/**
	 * Prints the Font styles for a given section
	 *
	 * @param string $section mod section id.
	 *
	 * @return void CSS Font styles.
	 */
	function springoo_font_settings( $section ) {
		$global_line_height = springoo_get_mod( 'typography_global_line_height' );
		if ( ! $global_line_height ) {
			$global_line_height = 1.5;
		}
		$line_height  = ! springoo_get_mod( $section . '_line_height' ) ? $global_line_height : springoo_get_mod( $section . '_line_height' );
		$font_variant = springoo_get_mod( $section . '_font_variant' );
		$font_weight  = 'regular' === $font_variant ? 'normal' : preg_replace( '/[^0-9]/', '', $font_variant );
		?>
		font:
		<?php
		printf(
			'%spx/%s "%s", -apple-system,BlinkMacSystemFont,"Segoe UI","Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"',
			esc_attr( springoo_get_mod( $section . '_font_size' ) ),
			esc_attr( $line_height ), // @phpstan-ignore-line
			esc_attr( springoo_get_mod( $section . '_font_family' ) )
		);
		?>
		;
		font-weight: <?php echo esc_attr( $font_weight ); // @phpstan-ignore-line ?>;
		font-style: <?php echo strpos( $font_variant, 'italic' ) !== false ? 'italic' : 'normal'; ?>;
		text-transform: <?php echo esc_attr( springoo_get_mod( $section . '_text_transform' ) ); ?>;
		letter-spacing: <?php echo esc_attr( springoo_get_mod( $section . '_letter_spacing' ) ); ?>px;
		word-spacing: <?php echo esc_attr( springoo_get_mod( $section . '_word_spacing' ) ); ?>px;
		<?php
	}
endif;

if ( ! function_exists( 'springoo_sanitize_social_profiles' ) ) {
	/**
	 * Sanitize profiles control data.
	 *
	 * @param array|string $profiles JSON String or array of profile.
	 *
	 * @return array
	 */
	function springoo_sanitize_social_profiles( $profiles ) {
		$profiles = is_array( $profiles ) ? $profiles : json_decode( $profiles, true );
		$temp     = [];
		foreach ( $profiles as $profile ) {
			$temp[] = springoo_sanitize_social_profile( $profile );
		}

		return array_filter( $temp );
	}
}

if ( ! function_exists( 'springoo_sanitize_social_profile' ) ) {
	/**
	 * Sanitize single profile data.
	 *
	 * @param array $args Profile data.
	 *
	 * @return array|bool
	 */
	function springoo_sanitize_social_profile( $args ) {
		$args    = wp_parse_args(
			$args,
			[
				'label' => '',
				'url'   => '',
				'icon'  => '',
			]
		);
		$profile = [
			'label' => sanitize_text_field( $args['label'] ),
			'url'   => esc_url_raw( $args['url'] ),
			'icon'  => sanitize_text_field( $args['icon'] ),
		];

		if ( $profile['label'] && $profile['url'] && $profile['icon'] ) {
			return $profile;
		}

		return false;
	}
}

/**
 * Determines whether a plugin is active.
 *
 * @param string $plugin Path to the plugin file relative to the plugins directory.
 * @return bool True, if in the active plugins list. False, not in the list.
 */
function springoo_is_active_plugin( $plugin ) {

	if ( in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ) {
		return true;
	}

	if ( ! is_multisite() ) {
		return false;
	}

	$plugins = get_site_option( 'active_sitewide_plugins' );
	if ( isset( $plugins[ $plugin ] ) ) {
		return true;
	}

	return false;
}

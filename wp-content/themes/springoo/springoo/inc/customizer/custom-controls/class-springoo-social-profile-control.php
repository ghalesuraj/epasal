<?php
/**
 * Customize for Range Slider, extend the WP customizer
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}


class Springoo_Social_Profile_Control extends WP_Customize_Control {

	/**
	 * The type of control being rendered.
	 *
	 * @var string
	 */
	public $type = 'social_profiles';

	/**
	 * Ayyash_Social_Profile_Control constructor.
	 *
	 * @param WP_Customize_Manager $manager
	 * @param string $id
	 * @param array $args
	 *
	 * @return void
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		add_filter( "customize_sanitize_js_{$this->id}", array( $this, 'to_js_value' ) );
	}

	/**
	 * To json val.
	 *
	 * @param array $value values
	 *
	 * @return false|string
	 */
	public function to_js_value( $value ) {
		return wp_json_encode( $value );
	}

	/**
	 * Render the control in the customizer
	 *
	 * @return void
	 */
	public function render_content() {
		?>
		<div class="social_profiles <?php echo esc_attr( $this->id ); ?>_social_profiles" data-control-id="<?php echo esc_attr( $this->id ); ?>">
			<?php if ( ! empty( $this->label ) ) { ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php } ?>
			<br class="clear">
			<div class="social_profiles_wrap"></div>
			<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); ?>>
			<br class="clear">
			<button class="button <?php echo esc_attr( $this->id ); ?>profile-add profile-add" type="button"><?php esc_html_e( 'Add Profile', 'springoo' ); ?></button>
			<script type="text/html" id="tmpl-<?php echo esc_attr( $this->id ); ?>-social-profile"><?php $this->render_profile(); ?></script>
		</div>
		<?php
	}

	/**
	 * Render Template for a single item.
	 *
	 * @return void
	 */
	protected function render_profile() {
		?>
		<# var index = data.index + 1; #>
		<div class="social-profile">
			<div class="field-title">
				<span class="<?php echo esc_attr( $this->id ); ?>profile-move profile-move"><i class="ti-view-list" aria-hidden="true"></i></span>
				<h3>
				<?php
					printf(
						/* translators: %s: Profile Numeric Index (order). */
						esc_html_x( '#Profile %s', 'customizer repeated field title', 'springoo' ),
						'{{ index }}'
					);
				?>
					</h3>
				<a class="<?php echo esc_attr( $this->id ); ?>profile-remove profile-remove" aria-label="<?php esc_attr_e( 'Remove Item', 'springoo' ); ?>"><i class="ti-trash" aria-hidden="true"></i></a>
			</div>
			<div class="fields">
				<input type="text" value="{{ data.label }}" placeholder="<?php esc_attr_e( 'Profile Label', 'springoo' ); ?>" class="<?php echo esc_attr( $this->id ); ?>-trigger label">
				<input type="url"  value="{{ data.url }}" placeholder="<?php esc_attr_e( 'Profile URL', 'springoo' ); ?>" class="<?php echo esc_attr( $this->id ); ?>-trigger url">
				<input type="text" value="{{ data.icon }}" placeholder="<?php esc_attr_e( 'Profile Icon', 'springoo' ); ?>" class="<?php echo esc_attr( $this->id ); ?>-trigger icon">
			</div>
		</div>
		<?php
	}
}

<?php
/**
 *
 * CSFramework Mega Menu API
 *
 * @package Springoo
 * @author  Springoo
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class Springoo_Megamenu_API
 */
class Springoo_Megamenu_API {

	/**
	 * Field list.
	 *
	 * @var array
	 */
	public $extra_fields = [
		'highlight',
		'highlight_type',
		'icon',
		'mega',
		'mega_width',
		'alignment',
		'mega_custom_width',
		'column_title',
		'column_title_link',
		'column_width',
		'content',
	];

	private $highlights;
	private $mega_width;
	private $column_width;

	/**
	 * Springoo_Megamenu_API constructor.
	 */
	public function __construct() {

		$this->highlights   = apply_filters( 'springoo_megamenu_highlight_types', [
			'info'    => __( 'Info', 'springoo' ),
			'success' => __( 'Success', 'springoo' ),
			'warning' => __( 'Warning', 'springoo' ),
			'danger'  => __( 'Danger', 'springoo' ),
		] );
		$this->mega_width   = [
			'natural' => __( 'Natural Width', 'springoo' ),
			'custom'  => __( 'Custom Width', 'springoo' ),
		];
		$this->column_width = apply_filters( 'springoo_megamenu_column_classes', [
			'col-md-1'  => __( '1 Col', 'springoo' ),
			'col-md-2'  => __( '2 Col', 'springoo' ),
			'col-md-3'  => __( '3 Col', 'springoo' ),
			'col-md-4'  => __( '4 Col', 'springoo' ),
			'col-md-5'  => __( '5 Col', 'springoo' ),
			'col-md-6'  => __( '6 Col (half)', 'springoo' ),
			'col-md-7'  => __( '7 Col', 'springoo' ),
			'col-md-8'  => __( '8 Col', 'springoo' ),
			'col-md-9'  => __( '9 Col', 'springoo' ),
			'col-md-10' => __( '10 Col', 'springoo' ),
			'col-md-11' => __( '11 Col', 'springoo' ),
			'col-md-12' => __( '12 Col (full-width)', 'springoo' ),
		] );

		add_filter( 'wp_nav_menu_args', array( $this, 'wp_nav_menu_args' ), 99 );
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'wp_edit_nav_menu_walker' ), 10 );
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'wp_setup_nav_menu_item' ), 10 );

		add_action( 'wp_update_nav_menu_item', array( $this, 'wp_update_nav_menu_item' ), 10, 3 );
		add_action( 'springoo_mega_menu_fields', array( $this, 'springoo_mega_menu_fields' ), 10, 2 );
		add_action( 'springoo_mega_menu_labels', array( $this, 'springoo_mega_menu_labels' ) );

	}

	/**
	 * Menu Menu Fields.
	 *
	 * @param int    $item_id item id.
	 * @param object $item    item.
	 *
	 * @return void
	 */
	public function springoo_mega_menu_fields( $item_id, $item ) {
		$hidden = ( empty( $item->icon ) ) ? ' hidden' : '';
		?>
		<p class="field-highlight description description-thin">
			<label for="edit-menu-item-highlight-<?php echo esc_attr( $item_id ); ?>">
				<?php esc_html_e( 'Highlight', 'springoo' ); ?><br>
				<input type="text" id="edit-menu-item-highlight-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-highlight" name="menu-item-highlight[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->highlight ); ?>">
			</label>
		</p>
		<p class="field-highlight-type description description-thin">
			<label for="edit-menu-item-highlight-type-<?php echo esc_attr( $item_id ); ?>">
				<?php esc_html_e( 'Highlight Type', 'springoo' ); ?><br>
				<select id="edit-menu-item-highlight-type-<?php echo esc_attr( $item_id ); ?>" name="menu-item-highlight_type[<?php echo esc_attr( $item_id ); ?>]">
					<option value=""><?php esc_html_e( 'Default', 'springoo' ); ?></option>
					<?php foreach ( $this->highlights as $k => $highlight ) { ?>
						<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $k, $item->highlight_type ); ?>><?php echo esc_html( $highlight ); ?></option>
					<?php } ?>
				</select>
			</label>
		</p>
		<div class="field-icon description description-wide">
			<div class="springoo_field springoo_field_icon">
				<div class="springoo-icon-select">
					<span class="icon-preview<?php echo esc_attr( $hidden ); ?>" data-label="<?php
					/* translators: 1. Icon Name */
					esc_attr_e( 'Icon “%s“', 'springoo' );
					?>">
						<?php
						printf(
							'<span class="springoo-icon %1$s" aria-label="%2$s"></span>',
							esc_attr( $item->icon ),
							sprintf(
								/* translators: 1. Icon Name */
								esc_attr__( 'Icon “%s“', 'springoo' ),
								esc_attr( ucfirst( str_replace( 'ti-', '', $item->icon ) ) )
							)
						);
						?>
					</span>
					<button class="button button-primary icon-add" data-icon="<?php echo esc_attr( $item->icon ); ?>"><?php esc_html_e( 'Add Icon', 'springoo' ); ?></button>
					<button class="button springoo-button-remove icon-remove<?php echo esc_attr( $hidden ); ?>"><?php esc_html_e( 'Remove Icon', 'springoo' ); ?></button>
					<input type="hidden" name="menu-item-icon[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->icon ); ?>" class="widefat code edit-menu-item-icon icon-value">
				</div>
			</div>
		</div>
		<div class="springoo-mega-menu">
			<div class="field-mega">
				<label for="edit-menu-item-mega-<?php echo esc_attr( $item_id ); ?>">
					<input type="checkbox" class="is-mega" id="edit-menu-item-mega-<?php echo esc_attr( $item_id ); ?>" value="mega" name="menu-item-mega[<?php echo esc_attr( $item_id ); ?>]"<?php checked( $item->mega, 'mega' ); ?> />
					<?php esc_html_e( 'Mega Menu', 'springoo' ); ?>
				</label>
			</div>
			<div class="field-mega-width">
				<label for="edit-menu-item-mega_width-<?php echo esc_attr( $item_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Menu Item Width', 'springoo' ); ?></label>
				<select id="edit-menu-item-mega_width-<?php echo esc_attr( $item_id ); ?>" name="menu-item-mega_width[<?php echo esc_attr( $item_id ); ?>]" class="is-width">
					<option value="full"><?php esc_html_e( 'Full Width', 'springoo' ); ?></option>
					<?php foreach ( $this->mega_width as $key => $value ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $item->mega_width ); ?>><?php echo esc_html( $value ); ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="mega-depend-width hidden">
				<p class="description">
					<label for="edit-menu-item-mega_custom_width<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Custom Mega Menu Width (without px)', 'springoo' ); ?><br>
						<input type="text" id="edit-menu-item-mega_custom_width<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-mega_custom_width" name="menu-item-mega_custom_width[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->mega_custom_width ); ?>"/>
					</label>
				</p>
			</div>
			<div class="mega-depend-position hidden">
				<p class="description">
					<label for="edit-menu-item-alignment<?php echo esc_attr( $item_id ); ?>">
						<input type="checkbox" id="edit-menu-item-alignment<?php echo esc_attr( $item_id ); ?>" value="1" name="menu-item-alignment[<?php echo esc_attr( $item_id ); ?>]"<?php checked( $item->alignment, 1 ); ?>>
						<?php esc_html_e( 'Align menu to right side (optional)', 'springoo' ); ?>
					</label>
				</p>
			</div>
			<div class="clear"></div>
		</div>
		<div class="springoo-mega-column">
			<p class="field-column description description-thin">
				<label for="edit-menu-item-column-title-<?php echo esc_attr( $item_id ); ?>">
					<input type="checkbox" id="edit-menu-item-column-title-<?php echo esc_attr( $item_id ); ?>" value="1" name="menu-item-column_title[<?php echo esc_attr( $item_id ); ?>]"<?php checked( $item->column_title, 1 ); ?>>
					<?php esc_html_e( 'Disable Title', 'springoo' ); ?>
				</label>
			</p>
			<p class="field-column description description-thin springoo-last">
				<label for="edit-menu-item-column-title-link-<?php echo esc_attr( $item_id ); ?>">
					<input type="checkbox" id="edit-menu-item-column-title-link-<?php echo esc_attr( $item_id ); ?>" value="1" name="menu-item-column_title_link[<?php echo esc_attr( $item_id ); ?>]"<?php checked( $item->column_title_link, 1 ); ?>>
					<?php esc_html_e( 'Disable Title Link', 'springoo' ); ?>
				</label>
			</p>
			<p class="field-column description">
				<label for="edit-menu-item-column_width-<?php echo esc_attr( $item_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Menu Column Width', 'springoo' ); ?></label>
				<select id="edit-menu-item-column_width-<?php echo esc_attr( $item_id ); ?>" name="menu-item-column_width[<?php echo esc_attr( $item_id ); ?>]">
					<option value=""><?php esc_html_e( 'Custom column width (optional)', 'springoo' ); ?></option>
					<?php foreach ( $this->column_width as $key => $value ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $item->column_width ); ?>><?php echo esc_html( $value ); ?></option>
					<?php } ?>
				</select>
			</p>
			<div class="clear"></div>
		</div>
		<p class="field-content description description-wide">
			<label for="edit-menu-item-content-<?php echo esc_attr( $item_id ); ?>">
				<?php esc_html_e( 'Description ( and can be used any shortcode )', 'springoo' ); ?><br>
				<textarea id="edit-menu-item-content-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-content" rows="3" cols="20" name="menu-item-content[<?php echo esc_attr( $item_id ); ?>]"><?php echo wp_kses_post( $item->content ); ?></textarea>
			</label>
		</p>
		<div class="clear"></div>
		<?php
	}

	/**
	 * Menu labels.
	 *
	 * @return void
	 */
	public function springoo_mega_menu_labels() {
		$out  = '<span class="item-mega"><span class="springoo-label springoo-label-primary">' . esc_html__( 'Mega Menu', 'springoo' ) . '</span></span>';
		$out .= '<span class="item-mega-column"><span class="springoo-label springoo-label-success">' . esc_html__( 'Column', 'springoo' ) . '</span></span>';
		echo wp_kses_post( $out );

	}

	/**
	 *
	 * Custom Menu Args
	 *
	 * @param array $args menu args.
	 *
	 * @return array
	 */
	public function wp_nav_menu_args( $args ) {

		$location = $args['theme_location'];

		if ( ( 'primary' == $location || 'right' == $location ) && ! isset( $args['mobile'] ) ) {
			$args['container']  = false;
			$args['menu_class'] = 'main-navigation springoo-sf-menu';
			$args['walker']     = new Springoo_Walker_Nav_Menu_Custom();
		} elseif ( ( 'header_main_right' == $location || 'right' == $location ) && ! isset( $args['mobile'] ) ) {
			$args['container']  = false;
			$args['menu_class'] = 'header_bottom_right springoo-sf-menu';
			$args['walker']     = new Springoo_Walker_Nav_Menu_Custom();
		} elseif ( ( 'vertical_menu' == $location || 'right' == $location ) && ! isset( $args['mobile'] ) ) {
			$args['container']  = false;
			$args['menu_class'] = 'vertical-navigation springoo-sf-menu';
			$args['walker']     = new Springoo_Walker_Nav_Menu_Custom();
		} elseif ( ( isset( $args['mobile'] ) || 'mobile' == $location ) || ( isset( $args['mobile_category'] ) || 'mobile_category' == $location ) ) {
			$args['after'] = '<button class="springoo-dropdown-plus" aria-label="' . esc_attr__( 'Show Submenus', 'springoo' ) . '"><i class="si si-thin-arrow-down" aria-hidden="true"></i></button>';
		}

		return $args;
	}

	/**
	 *
	 * Custom Nav Menu Edit.
	 *
	 * @return string
	 */
	public function wp_edit_nav_menu_walker() {
		return 'Springoo_Walker_Nav_Menu_Edit_Custom';
	}

	/**
	 * Save Custom Fields.
	 *
	 * @param object $item item.
	 *
	 * @return object
	 */
	public function wp_setup_nav_menu_item( $item ) {

		foreach ( $this->extra_fields as $key ) {
			$item->$key = isset( $item->ID ) ? get_post_meta( $item->ID, '_menu_item_' . $key, true ) : '';
		}

		return $item;
	}

	/**
	 * Update Custom Fields.
	 *
	 * @param int   $menu_id Menu id.
	 * @param int   $db_id   Menu item db id.
	 * @param array $args    Menu args.
	 *
	 * @return void
	 */
	public function wp_update_nav_menu_item( $menu_id, $db_id, $args ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed,Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed
		$req = wp_unslash( $_REQUEST ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		foreach ( $this->extra_fields as $key ) {
			$_key  = 'menu-item-' . $key;
			$value = ( isset( $req[ $_key ][ $db_id ] ) ) ? sanitize_text_field( $req[ $_key ][ $db_id ] ) : '';
			update_post_meta( $db_id, '_menu_item_' . $key, $value );
		}
	}
}

new Springoo_Megamenu_API();

<?php
/**
 *
 * Main Menu Walker Edit.
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! class_exists( 'Springoo_Walker_Nav_Menu_Edit_Custom' ) ) {
	/**
	 * Class Springoo_Walker_Nav_Menu_Edit_Custom
	 */
	class Springoo_Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu {

		/**
		 * Start level.
		 *
		 * @param string $output output.
		 * @param int    $depth  depth.
		 * @param array  $args    args.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
		}

		/**
		 * End level.
		 *
		 * @param string $output output.
		 * @param int    $depth  depth.
		 * @param array  $args    args.
		 */
		public function end_lvl( &$output, $depth = 0, $args = array() ) {
		}

		/**
		 * Start Element.
		 *
		 * @param string  $output output.
		 * @param WP_Post $item   item.
		 * @param int     $depth  depth.
		 * @param array   $args   args.
		 * @param int     $id     nav item id.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassAfterLastUsed
			global $_wp_nav_menu_max_depth;
			$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

			ob_start();
			$item_id      = esc_attr( $item->ID );
			$removed_args = [ 'action', 'customlink-tab', 'edit-menu-item', 'menu-item', 'page-tab', '_wpnonce' ];

			$original_title = '';
			if ( 'taxonomy' == $item->type ) {
				$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
				if ( is_wp_error( $original_title ) ) {
					$original_title = false;
				}
			} elseif ( 'post_type' == $item->type ) {
				$original_object = get_post( $item->object_id );
				$original_title  = get_the_title( $original_object->ID );
			}

			$classes = [
				'menu-item menu-item-depth-' . $depth,
				'menu-item-' . esc_attr( $item->object ),
				'menu-item-edit-' . (
					( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive' // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				),
			];

			$title = $item->title;

			if ( ! empty( $item->_invalid ) ) {
				$classes[] = 'menu-item-invalid';
				$title     = sprintf( '%s (Invalid)', $item->title );
			} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
				$classes[] = 'pending';
				$title     = sprintf( '%s (Pending)', $item->title );
			}

			$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

			$classes   = implode( ' ', $classes );
			$move_up   = wp_nonce_url(
				add_query_arg(
					array(
						'action'    => 'move-up-menu-item',
						'menu-item' => $item_id,
					),
					remove_query_arg(
						$removed_args,
						admin_url( 'nav-menus.php' )
					)
				),
				'move-menu_item'
			);
			$move_down = wp_nonce_url(
				add_query_arg(
					array(
						'action'    => 'move-down-menu-item',
						'menu-item' => $item_id,
					),
					remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
				),
				'move-menu_item'
			);

			if ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$edit_item = admin_url( 'nav-menus.php' );
			} else {
				$edit_item = esc_url( add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) ) );
			}
			?>
		<li id="menu-item-<?php echo esc_attr( $item_id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
			<div class="menu-item-bar">
				<div class="menu-item-handle">
					<span class="item-title">
						<span class="menu-item-title"><?php echo esc_html( $title ); ?></span>
						<span class="is-submenu" <?php echo ! $depth ? 'style="display: none;"' : ''; ?>><?php esc_html_e( 'Sub Item', 'springoo' ); ?></span>
					</span>
					<span class="item-controls">
						<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
						<!-- Mega Labels Action -->
						<?php do_action( 'springoo_mega_menu_labels' ); ?>
						<!-- /Mega Labels Action -->
						<span class="item-order hide-if-js">
							<a href="<?php echo esc_url( $move_up ); ?>" class="item-move-up">
								<abbr title="<?php esc_attr_e( 'Move up', 'springoo' ); ?>">&#8593;</abbr>
							</a>
							<span class="separator">&nbsp;|&nbsp;</span>
							<a href="<?php echo esc_url( $move_down ); ?>" class="item-move-down">
								<abbr title="<?php esc_attr_e( 'Move down', 'springoo' ); ?>">&#8595;</abbr>
							</a>
						</span>
						<a class="item-edit" id="edit-<?php echo esc_attr( $item_id ); ?>" href="<?php echo esc_url( $edit_item ); ?>" title="<?php esc_attr_e( 'Edit Menu Item', 'springoo' ); ?>">
							<span class="screen-reader-text"><?php esc_html_e( 'Edit Menu Item', 'springoo' ); ?></span>
						</a>
					</span>
				</div>
			</div>
			<div class="menu-item-settings" id="menu-item-settings-<?php echo esc_attr( $item_id ); ?>">
				<?php if ( 'custom' == $item->type ) : ?>
					<p class="field-url description description-wide">
						<label for="edit-menu-item-url-<?php echo esc_attr( $item_id ); ?>">
							<?php esc_html_e( 'URL', 'springoo' ); ?><br>
							<input type="text" id="edit-menu-item-url-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
						</label>
					</p>
				<?php endif; ?>
				<p class="description description-thin">
					<label for="edit-menu-item-title-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Navigation Label', 'springoo' ); ?><br>
						<input type="text" id="edit-menu-item-title-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
					</label>
				</p>
				<p class="description description-thin">
					<label for="edit-menu-item-attr-title-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Title Attribute', 'springoo' ); ?><br>
						<input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
					</label>
				</p>
				<p class="field-link-target description">
					<label for="edit-menu-item-target-<?php echo esc_attr( $item_id ); ?>">
						<input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr( $item_id ); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr( $item_id ); ?>]"<?php checked( $item->target, '_blank' ); ?> />
						<?php esc_html_e( 'Open link in a new window/tab', 'springoo' ); ?>
					</label>
				</p>
				<p class="field-css-classes description description-thin">
					<label for="edit-menu-item-classes-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'CSS Classes (optional)', 'springoo' ); ?><br>
						<input type="text" id="edit-menu-item-classes-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( implode( ' ', $item->classes ) ); ?>" />
					</label>
				</p>
				<p class="field-xfn description description-thin">
					<label for="edit-menu-item-xfn-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Link Relationship (XFN)', 'springoo' ); ?><br>
						<input type="text" id="edit-menu-item-xfn-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>"/>
					</label>
				</p>
				<!-- Mega Menu Fields-->
				<?php do_action( 'springoo_mega_menu_fields', $item_id, $item );
				/**
				 * Fires just before the move buttons of a nav menu item in the menu editor.
				 *
				 * @since 5.4.0
				 *
				 * @param string        $item_id           Menu item ID as a numeric string.
				 * @param WP_Post       $menu_item         Menu item data object.
				 * @param int           $depth             Depth of menu item. Used for padding.
				 * @param stdClass|null $args              An object of menu item arguments.
				 * @param int           $id Nav menu ID.
				 */
				do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args, $id );
				?>
				<!-- /Mega Menu Fields-->
				<fieldset class="field-move hide-if-no-js description description-wide">
					<span class="field-move-visual-label" aria-hidden="true"><?php esc_html_e( 'Move', 'springoo' ); ?></span>
					<button type="button" class="button-link menus-move menus-move-up" data-dir="up"><?php esc_html_e( 'Up one', 'springoo' ); ?></button>
					<button type="button" class="button-link menus-move menus-move-down" data-dir="down"><?php esc_html_e( 'Down one', 'springoo' ); ?></button>
					<button type="button" class="button-link menus-move menus-move-left" data-dir="left"></button>
					<button type="button" class="button-link menus-move menus-move-right" data-dir="right"></button>
					<button type="button" class="button-link menus-move menus-move-top" data-dir="top"><?php esc_html_e( 'To the top', 'springoo' ); ?></button>
				</fieldset>
				<?php
				// Nav Menu Roles Plugin conflict fixing here.
				if ( class_exists( 'Nav_Menu_Roles' ) ) {
					do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound
				}
				?>

				<div class="menu-item-actions description-wide submitbox">
					<?php if ( 'custom' != $item->type && false !== $original_title ) : ?>
						<p class="link-to-original">
						<?php
							printf(
								/* Translators: Link to original Item. */
								esc_html__( 'Original: %s', 'springoo' ),
								'<a href="' . esc_url( $item->url ) . '">' . esc_html( $original_title ) . '</a>'
							);
						?>
							</p>
					<?php endif; ?>
					<?php
					printf(
						'<a class="item-delete submitdelete deletion" id="delete-%s" href="%s">%s</a>',
						esc_attr( $item_id ),
						esc_url(
							wp_nonce_url(
								add_query_arg(
									[
										'action'    => 'delete-menu-item',
										'menu-item' => $item_id,
									],
									admin_url( 'nav-menus.php' ) ),
								'delete-menu_item_' . $item_id
							)
						),
						esc_html__( 'Remove', 'springoo' )
					); ?>
					<span class="meta-sep hide-if-no-js"> | </span>
					<a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo esc_attr( $item_id ); ?>" href="
						<?php
						echo esc_url(
							add_query_arg(
								array(
									'edit-menu-item' => $item_id,
									'cancel'         => time(),
								),
								admin_url( 'nav-menus.php' )
							)
						);
						?>
					#menu-item-settings-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Cancel', 'springoo' ); ?></a>
				</div>

				<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item_id ); ?>">
				<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>">
				<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->object ); ?>">
				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>">
				<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>">
				<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->type ); ?>">
				<div class="clear"></div>
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
			<?php
			$output .= ob_get_clean();
		}
	}
}

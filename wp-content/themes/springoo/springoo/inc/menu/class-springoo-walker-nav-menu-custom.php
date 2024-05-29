<?php
/**
 *
 * Main Menu Walker
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! class_exists( 'Springoo_Walker_Nav_Menu_Custom' ) ) {
	/**
	 * Copied from WordPress 3.7 Core
	 */
	class Springoo_Walker_Nav_Menu_Custom extends Walker_Nav_Menu {

		/**
		 * Child item count.
		 *
		 * @var int
		 */
		public $child_count = 0;

		/**
		 * Has Custom Width.
		 *
		 * @var bool
		 */
		public $is_custom_width = false;

		/**
		 * Menu Type.
		 *
		 * @var array
		 */
		public $menu_type = array();

		/**
		 * Generate start level item.
		 *
		 * @param string $output output.
		 * @param int    $depth  depth.
		 * @param array  $args   args.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {

			$style   = ( $this->is_custom_width ) ? ' style="width: ' . $this->is_custom_width . 'px"' : '';
			$indent  = str_repeat( "\t", $depth );
			$output .= "\n$indent<ul class=\"sub-menu\"" . $style . ">\n";

		}

		/**
		 * Generate End level item.
		 *
		 * @param string $output output.
		 * @param int    $depth  depth.
		 * @param array  $args   args.
		 */
		public function end_lvl( &$output, $depth = 0, $args = array() ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassAfterLastUsed

			$indent  = str_repeat( "\t", $depth );
			$output .= "$indent</ul>\n";

		}

		/**
		 * Start Element.
		 *
		 * @param string       $output output.
		 * @param WP_Post      $item   item.
		 * @param int          $depth  depth.
		 * @param array|object $args   args.
		 * @param int          $id     id.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

			$args        = (object) $args;
			$class_names = '';
			$value       = '';
			$indent      = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			$classes     = empty( $item->classes ) ? array() : $item->classes;
			$classes[]   = 'menu-item-' . $item->ID;

			if ( 0 == $depth ) {
				if ( ! empty( $item->mega ) ) {
					$classes[] = 'springoo-mega-menu mega-menu';
					$classes[] = 'width-' . $item->mega_width;
					if ( 'full' !== $item->mega_width && ! empty( $item->alignment ) ) {
						$classes[] = 'menu-align-right';
					}
				} else {
					$classes[] = 'springoo-menu';
				}
			}

			// adding bootstrap col if parent item is mega!
			$classes['col'] = ( ! empty( $bs_col ) ) ? $bs_col : ''; // @XXX ?! ref to $bs_col not found

			// adding force custom bootstrap col.
			$classes['col'] = ( 1 == $depth && ! empty( $item->column_width ) ) ? $item->column_width : $classes['col'];

			$classes     = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound
			$class_names = implode( ' ', $classes );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound

			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names . '>';

			$atts           = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
			$atts['href']   = ! empty( $item->url ) ? $item->url : '';

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output = $args->before;

			// if column title not disable.
			if ( empty( $item->column_title ) ) {
				// if column title link not disable.
				if ( empty( $item->column_title_link ) ) {
					$is_sticky_item = ( 0 == $depth ) ? ' springoo-sticky-item' : '';
					$is_mega_column = ( isset( $this->menu_type[ $item->menu_item_parent ] ) ) ? ' springoo-title' : '';

					$item_output .= '<a' . $attributes . ' class="menu-link menu-link-depth-' . $depth . $is_sticky_item . $is_mega_column . '">';
				} elseif ( 1 == $depth && ! empty( $item->column_title_link ) ) {
					$item_output .= '<a class="menu-link springoo-title springoo-column-title">' . $item->colum_title;
				}

				// adding icon.
				$item_output .= ( ! empty( $item->icon ) ) ? '<i class="springoo-icon ' . esc_attr( $item->icon ) . '"></i>' : '';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound

				if ( ! empty( $item->highlight ) ) {
					$highlight    = ( ! empty( $item->highlight_type ) ) ? $item->highlight_type : 'default';
					$item_output .= '<span class="springoo-label springoo-label-' . $highlight . '">' . $item->highlight . '</span>';
				}

				// adding custom content.
				$item_output .= ( ! empty( $item->content ) ) ? '<span class="springoo-content">' . do_shortcode( $item->content ) . '</span>' : '';

				// if column title link not disable.
				if ( empty( $item->column_title_link ) || ( 1 == $depth && ! empty( $item->column_title_link ) ) ) {
					$item_output .= '</a>';
				}
			}

			// adding force custom content.
			if ( ! empty( $item->column_title ) ) {
				$item_output .= ( ! empty( $item->content ) ) ? '<div class="springoo-full-content">' . do_shortcode( $item->content ) . '</div>' : '';
			}

			$item_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound
		}

		/**
		 * Traverses elements to create list from elements.
		 *
		 * This function is designed to enhance Walker::display_element() to
		 * display children of higher nesting levels than selected inline on
		 * the highest depth level displayed. This prevents them being orphaned
		 * at the end of the comment list.
		 *
		 * Example: max_depth = 2, with 5 levels of nested content.
		 *     1
		 *      1.1
		 *        1.1.1
		 *        1.1.1.1
		 *        1.1.1.1.1
		 *        1.1.2
		 *        1.1.2.1
		 *     2
		 *      2.2
		 *
		 * @see Walker::display_element()
		 *
		 * @param WP_Comment $element           Comment data object.
		 * @param array      $children_elements List of elements to continue traversing. Passed by reference.
		 * @param int        $max_depth         Max depth to traverse.
		 * @param int        $depth             Depth of the current element.
		 * @param array      $args              An array of arguments.
		 * @param string     $output            Used to append additional content. Passed by reference.
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {

			if ( ! empty( $element->mega ) ) {
				$this->child_count               = ( isset( $children_elements[ $element->ID ] ) ) ? count( $children_elements[ $element->ID ] ) : 0;
				$this->menu_type[ $element->ID ] = true;
			}

			if ( 0 == $depth && ! empty( $element->mega ) && 'custom' == $element->mega_width ) {
				$this->is_custom_width = $element->mega_custom_width;
			} else {
				$this->is_custom_width = false;
			}

			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}

	}
}

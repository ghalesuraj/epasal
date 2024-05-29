<?php
/**
 * Hook Helper.
 *
 * @author ThemeRox
 * @category HelperFunctions\HookHelper
 * @package Springoo\Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! function_exists( 'springoo_preloader' ) ) {
	function springoo_preloader() {

		if ( 0 === (int) springoo_get_mod( 'preload_enable' ) ) {
			return;
		}

		if ( class_exists( 'Elementor\Plugin' ) && ( Elementor\Plugin::$instance->preview->is_preview_mode() || Elementor\Plugin::$instance->editor->is_edit_mode() ) ) { //phpcs:ignore
			return;
		}

		global $revslider_is_preview_mode;
		if ( class_exists( 'RevSliderAdmin' ) && $revslider_is_preview_mode ) {
			return;
		}

		$animation_type  = springoo_get_mod( 'preload_animation_type' );
		$animation_speed = springoo_get_mod( 'preload_animation_speed' );
		$animation_cls   = 'animate__animated animate__' . $animation_type . ' animate__infinite';
		$preloader_img   = springoo_get_mod( 'preload_img' );
		$text_size       = springoo_get_mod( 'preload_text_size' );
		$img_size        = springoo_get_mod( 'preload_img_size' );

		if ( $animation_speed ) {
			$animation_cls .= ' animate__' . $animation_speed;
		}

		?>
		<div id="springoo-preloader" style="position: fixed;top: 0;bottom: 0;left: 0;right: 0;z-index: 9999999999;background: #ffffff;">
			<div class="springoo-preloader__content" style="position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);text-align: center;">
				<div class="<?php echo 'none' !== $animation_type ? esc_attr( $animation_cls ) : ''; ?>">
					<?php
					if ( $preloader_img && 1 === (int) springoo_get_mod( 'preload_img_enable' ) ) {
						echo '<img src="' . esc_url( $preloader_img ) . '" alt="Springoo preloader" width="' . esc_attr( $img_size ) . 'px" height="auto" />';
					}

					if ( 1 === (int) springoo_get_mod( 'preload_text_enable' ) ) {
						echo '<p style="font-size:' . esc_attr( $text_size ) . 'px; margin: 0;">' . esc_html( springoo_get_mod( 'preload_text' ) ) . '</p>';
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_body_classes' ) ) {
	/**
	 * Body classes.
	 *
	 * @param string[] $classes body class list.
	 *
	 * @return array
	 */
	function springoo_body_classes( $classes ) {

		// Apply single page/post layout.
		$site_layout = springoo_get_post_layout_options( 'site_layout' );
		if ( 'default' === $site_layout ) {
			$site_layout = springoo_get_mod( 'layout_global_site' );
		}
		//Add mobile menu style class
		if ( springoo_get_mod( 'layout_header_mobile_menu_style' ) ) {
			$mobile_menu = springoo_get_mod( 'layout_header_mobile_menu_style' );
			$classes[]   = $mobile_menu;
		}
		// Adds `singular` to singular pages, and `hfeed` to all other pages.
		$classes[] = is_singular() ? 'singular' : 'hfeed';
		$classes[] = 'theme-springoo';
		$classes[] = 'boxed' === $site_layout ? 'boxed' : 'wide';

		// Adds a class of group-blog to blog with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}

		return $classes;
	}
}

if ( ! function_exists( 'springoo_post_classes' ) ) {
	/**
	 * Post class.
	 *
	 * @param string[] $classes post class list.
	 *
	 * @return array
	 */
	function springoo_post_classes( $classes ) {
		$layout = springoo_get_content_layout( springoo_get_current_screen() );
		if ( ! is_singular() ) {
			if ( $layout ) {
				$classes[] = 'post-' . $layout;
			}
		} else {
			if ( 'post' === get_post_type() ) {
				$classes[] = 'post-normal post-single';
			}
		}

		return $classes;
	}
}

if ( ! function_exists( 'springoo_allow_tags' ) ) {
	/**
	 * Springoo Allow tags
	 *
	 * @param array $allowed_tags
	 *
	 * @return array
	 */
	function springoo_allow_tags( $allowed_tags ) {
		$allowed_tags['noscript'] = [];

		return $allowed_tags;
	}
}

if ( ! function_exists( 'springoo_custom_excerpt_length' ) ) {
	/**
	 * Override the_excerpt.
	 *
	 * @param int $length length.
	 *
	 * @return int
	 */
	function springoo_custom_excerpt_length( $length ) {
		$excerpt_length = absint( springoo_get_mod( 'layout_blog_excerpt_length' ) );
		if ( $excerpt_length ) {
			return $excerpt_length;
		}

		return $length;
	}
}

if ( ! function_exists( 'springoo_category_transient_flusher' ) ) {
	/**
	 * Flush out the transients used in springoo_categorized_blog.
	 *
	 * @return void
	 */
	function springoo_category_transient_flusher() {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Like, beat it. Dig?
		delete_transient( 'springoo_category_count' );
	}
}

if ( ! function_exists( 'springoo_skip_links' ) ) {
	/**
	 * Function Name        : springoo_skip_links
	 * Function Hooked      : springoo_header_skip_links
	 * Function return Type : html
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_skip_links() {
		?>
		<div class="skippy visually-hidden-focusable overflow-hidden">
			<div class="<?php echo esc_attr( springoo_get_mod( 'layout_global_content_layout' ) ); ?>">
				<a class="skip-link d-inline-flex p-2 ps-0 me-1" href="#content"><?php esc_html_e( 'Skip to main content', 'springoo' ); ?></a>
				<a class="d-none d-md-inline-flex p-2" href="#site-nav"><?php esc_html_e( 'Skip to main menu', 'springoo' ); ?></a>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_nav_menu_depth_class' ) ) {
	/**
	 * Nav menu Depth Class
	 *
	 * @return array
	 */
	function springoo_nav_menu_depth_class( $classes, $item, $args, $depth ) { //phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed
		$classes[] = 'springoo-depth-' . $depth;
		$classes[] = 'depth-' . $depth;

		return $classes;
	}
}

// header top.
if ( ! function_exists( 'springoo_header_top' ) ) {
	/**
	 * Function Name        : springoo_header_top
	 * Function Hooked      : springoo_header_top
	 * Function return Type : html
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_header_top() {
		$header_container = springoo_get_post_layout_options( 'header_container' );
		$header_top       = springoo_get_post_layout_options( 'display_header_top' );

		if ( 'default' === $header_container ) {
			$header_container_cls = apply_filters( 'springoo_header_container_class', springoo_get_mod( 'layout_global_content_layout' ) );
		} elseif ( 'none' === $header_container ) {
			$header_container_cls = '';
		} else {
			$header_container_cls = apply_filters( 'springoo_header_container_class', $header_container );
		}

		if ( 'disable' === $header_top ) {
			return;
		}

		if ( springoo_get_mod( 'layout_header_top' ) ) {
			?>
			<div id="header-top" class="header-top">
				<div class="<?php echo esc_attr( $header_container_cls ); ?>">
					<div class="row">
						<?php
						springoo_header_top_left_menu();
						springoo_header_top_discount_text();
						springoo_header_top_right_menu();
						?>
					</div>
				</div>
			</div>
			<?php
		}
	}
}

//header main
if ( ! function_exists( 'springoo_header_main' ) ) {
	/**
	 * Function Name        : springoo_header_main
	 * Function Hooked      : springoo_header_main
	 * Function return Type : html
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_header_main() {
		$header_container = springoo_get_post_layout_options( 'header_container' );
		$main_header      = springoo_get_post_layout_options( 'display_header' );

		if ( 'default' === $header_container ) {
			$header_container_cls = apply_filters( 'springoo_header_container_class', springoo_get_mod( 'layout_global_content_layout' ) );
		} elseif ( 'none' === $header_container ) {
			$header_container_cls = '';
		} else {
			$header_container_cls = apply_filters( 'springoo_header_container_class', $header_container );
		}

		if ( 'disable' === $main_header ) {
			return;
		}
		?>
		<div id="masthead" class="main-header <?php echo esc_attr( springoo_transparent_background() ) . esc_attr( get_theme_mod( 'layout_header_bottom_border' ) === 1 ? ' header-border-bottom' : '' ); ?> " role="banner">
			<div class="<?php echo esc_attr( $header_container_cls ); ?>">
				<div class="masthead-wrap">
					<?php
					springoo_site_branding();
					springoo_site_menu();
					springoo_header_main_actions();
					springoo_mobile_icon();
					?>
				</div>
			</div>
		</div>
		<?php
	}
}

// header bottom
if ( ! function_exists( 'springoo_header_bottom' ) ) {
	/**
	 * Function Name        : springoo_header_bottom
	 * Function Hooked      : springoo_header_bottom
	 * Function return Type : html
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_header_bottom() {
		$mobile_grid      = springoo_get_mod( 'layout_header_menu_mobileshow' );
		$header_container = springoo_get_post_layout_options( 'header_container' );
		$header_bottom    = springoo_get_post_layout_options( 'display_header_bottom' );

		if ( 'default' === $header_container ) {
			$header_container_cls = apply_filters( 'springoo_header_container_class', springoo_get_mod( 'layout_global_content_layout' ) );
		} elseif ( 'none' === $header_container ) {
			$header_container_cls = '';
		} else {
			$header_container_cls = apply_filters( 'springoo_header_container_class', $header_container );
		}

		if ( 'disable' === $header_bottom ) {
			return;
		}

		switch ( $mobile_grid ) {
			case 'sm':
				$mobile_grid = 'd-none d-md-flex';
				break;
			case 'md':
				$mobile_grid = ' d-none d-lg-flex';
				break;
			case 'lg':
				$mobile_grid = ' d-none d-xl-flex';
				break;
			case 'xl':
				$mobile_grid = ' d-none';
				break;
			default:
				$mobile_grid = ' d-none d-md-flex';
		}
		?>
		<div id="header-bottom" class="header-bottom <?php echo esc_attr( $mobile_grid ); ?>">
			<div class="<?php echo esc_attr( $header_container_cls ); ?>">
				<div class="header-bottom-wrap d-flex align-items-center justify-content-between">
					<?php
					springoo_vertical_menu();
					springoo_site_menu();
					?>
				</div>
			</div>
		</div>
		<?php
	}
}

// Mobile Menu.
if ( ! function_exists( 'springoo_mobile_menu' ) ) {
	/**
	 * Function Name        : springoo_mobile_menu
	 * Function Hooked      : springoo_mobile_menu
	 * Function return Type : html
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_mobile_menu() {
		$mobile_menu_view = springoo_get_mod( 'layout_header_menu_mobileshow' );

		switch ( $mobile_menu_view ) {
			case 'sm':
				$mobile_menu_view = ' d-flex flex-column d-md-none';
				break;
			case 'lg':
				$mobile_menu_view = ' d-flex flex-column d-xl-none';
				break;
			case 'xl':
				$mobile_menu_view = ' d-flex flex-column';
				break;
			case 'md':
			default:
				$mobile_menu_view = ' d-flex flex-column d-lg-none';
				break;
		}
		$uid = wp_unique_id( 'search-' );
		?>
		<div id="navigation-mobile">
			<div class="mobile-container <?php echo esc_attr( $mobile_menu_view ); ?>">
				<div class="mobile-menu-header">
					<div class="d-flex align-items-center justify-content-between pt-3">
						<?php springoo_site_branding(); ?>
						<a href="#" id="springoo-mobile-close" role="button" aria-controls="navigation-mobile">
							<i class="si si-thin-close" aria-hidden="true"></i>
							<span class="sr-only"><?php esc_html_e( 'Close Mobile Menu', 'springoo' ); ?></span>
						</a>
					</div>
					<?php springoo_header_search(); ?>
				</div>
				<?php
				springoo_get_mobile_menu();
				springoo_mobile_account();
				springoo_social_icons();
				?>

				<div class="footer-credit text-center">
					<div class="copyright"><?php echo wp_kses_post( springoo_get_mod( 'layout_footer_footer_text' ) ); ?></div>
					<!-- .copyright -->
					<div class="powered-by">
						<?php
						if ( ! springoo_get_mod( 'layout_footer_footer_text' ) ) {
							printf(
							/* translators: %1$s: WordPress, %2$s: heart icon, %3$s: Springoo  */
								esc_html__( 'Proudly powered by %1$s. Made with ❤️ by %2$s', 'springoo' ),
								'<a href="' . esc_url( __( 'https://wordpress.org/', 'springoo' ) ) . '" rel="noopener" target="_blank">WordPress</a>',
								'<a href="' . esc_url( __( 'https://themerox.com/', 'springoo' ) ) . '" rel="noopener" target="_blank">ThemeRox</a>'
							);
						}
						?>
					</div><!-- .made-with -->
				</div>

			</div>
		</div><!-- /navigation-mobile -->
		<?php
	}
}

// Mobile menu backdrop
if ( ! function_exists( 'springoo_mobile_backdrop_overlay' ) ) {
	/**
	 * Mobile menu toggle overlay
	 *
	 * @return void
	 */
	function springoo_mobile_backdrop_overlay() {
		if ( springoo_get_mod( 'layout_header_mobile_backdrop_bg' ) ) {
			echo "<div class='springoo-menu-overlay'></div>";
		}
	}
}

// Comment

if ( ! function_exists( 'springoo_commentfields_rowtag' ) ) {
	/**
	 * Adds the Proper opening markup for comment filed.
	 *
	 * @return void
	 */
	function springoo_commentfields_rowtag() {
		echo '<div class="row">';
	}
}

if ( ! function_exists( 'springoo_commentfields_rowtag_end' ) ) {
	/**
	 * Adds the Proper closing markup for comment filed.
	 *
	 * @return void
	 */
	function springoo_commentfields_rowtag_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'springoo_cancel_comment_reply_link' ) ) {
	/**
	 * Springoo Comment Reply link
	 *
	 * @param string $link
	 *
	 * @return string
	 */
	function springoo_cancel_comment_reply_link( $link ) {
		$tip = '<a data-bs-toggle="tooltip" data-bs-placement="bottom" title="' . esc_attr__( 'Cancel reply', 'springoo' ) . '" ';

		return str_replace( '<a ', $tip, $link );
	}
}

/**
 * ----------------------------------------------------------------------
 * Page Option related Function
 *----------------------------------------------------------------------*/

// Secondary Header Enable Disable.

if ( ! function_exists( 'springoo_single_secondary_header' ) ) {
	/**
	 * Function Name        : springoo_single_secondary_header
	 * Description          : Remove secondary header for single page or post
	 * Function Hooked      : springoo_single_option
	 * Function return Type : markup for secondary head or remove action
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_single_secondary_header() {
		$secondary_header = springoo_get_post_layout_options( 'secondary_header' );
		if ( 'default' !== $secondary_header ) {
			if ( 'enable' === $secondary_header ) {
				add_action( 'springoo_header_secondary_header', 'springoo_secondary_head' );
			} else {
				remove_action( 'springoo_header_secondary_header', 'springoo_secondary_head' );
			}
		}
	}
}

if ( ! function_exists( 'springoo_single_master_header' ) ) {
	/**
	 * Function Name        : springoo_single_master_header
	 * Description          : Enable / Disable master head for single page
	 * Function Hooked      : springoo_single_option
	 * Function return Type : html header markup
	 * Parameter            : post or page id
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_single_master_header() {
		$header_option = springoo_get_post_layout_options( 'display_header' );
		if ( 'disable' === $header_option ) {
			remove_action( 'springoo_header_master_header', 'springoo_master_head' );
		}

	}
}

if ( ! function_exists( 'springoo_grid_loop_layout_start' ) ) {
	/**
	 * Springoo Grid Layout Loop Start
	 *
	 * @param string $springoo_layout
	 *
	 * @return void
	 */
	function springoo_grid_loop_layout_start( $springoo_layout ) {
		if ( 'grid' == $springoo_layout ) {
			?>
	<div class="post-grid-wrap grid-column-<?php echo esc_attr( springoo_blog_grid_column() ); ?>">
		<?php
		}
	}
}

if ( ! function_exists( 'springoo_grid_loop_layout_end' ) ) {
	/**
	 * Springoo Grid Layout Loop End
	 *
	 * @param string $springoo_layout
	 *
	 * @return void
	 */
	function springoo_grid_loop_layout_end( $springoo_layout ) {
		if ( 'grid' == $springoo_layout ) {
			?>
			</div><!-- .post-grid-wrap -->
			<?php
		}
	}
}

if ( ! function_exists( 'springoo_render_footer_main' ) ) {
	/**
	 * Function Name        : springoo_render_footer_main
	 * Function Hooked      : springoo_footer_bottom_widget
	 * Function return Type : html
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_render_footer_main() {
		$main_footer_option = springoo_get_post_layout_options( 'display_main_footer' );
		$footer_container   = springoo_get_post_layout_options( 'footer_container' );

		if ( $footer_container ) {
			if ( 'default' === $footer_container ) {
				$footer_main_container_cls = apply_filters( 'springoo_footer_main_container_class', springoo_get_mod( 'layout_global_content_layout' ) );
			} elseif ( 'none' === $footer_container ) {
				$footer_main_container_cls = '';
			} else {
				$footer_main_container_cls = apply_filters( 'springoo_footer_main_container_class', $footer_container );
			}
		}

		if ( is_page_template( 'template-starter.php' ) ) {
			return;
		}

		if ( ! is_active_sidebar( 'footer-widget' ) ) {
			return;
		}

		if ( 'disable' === $main_footer_option ) {
			return;
		}
		?>

		<div class="springoo-footer-main">
			<?php
			/**
			 * Render Footer before Content.
			 *
			 * @hooked springoo_before_footer_container
			 */
			do_action( 'springoo_before_footer_container' );
			?>
			<div class="<?php echo esc_attr( $footer_main_container_cls ); ?>">
					<div class="row">
						<?php dynamic_sidebar( 'footer-widget' ); ?>
					</div>
				</div>
			<?php
				/**
				 * Render Footer after Content.
				 *
				 * @hooked springoo_after_footer_container
				 */
				do_action( 'springoo_after_footer_container' ); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_footer_services' ) ) {
	function springoo_footer_services() {
		$services = springoo_get_post_layout_options( 'footer_services' );

		if ( empty( springoo_get_mod( 'layout_footer_services' ) ) && empty( $services ) ) {
			return;
		}

		if ( empty( $services ) ) {
			echo do_shortcode( wp_kses_post( springoo_get_mod( 'layout_footer_services' ) ) );
		} else {
			echo do_shortcode( wp_kses_post( $services ) );
		}
	}
}

if ( ! function_exists( 'springoo_footer_newsletter' ) ) {
	function springoo_footer_newsletter() {
		$newsletter = springoo_get_post_layout_options( 'footer_newsletter' );

		if ( empty( springoo_get_mod( 'layout_footer_newsletter' ) ) && empty( $newsletter ) ) {
			return;
		}

		if ( empty( $newsletter ) ) {
			echo do_shortcode( wp_kses_post( springoo_get_mod( 'layout_footer_newsletter' ) ) );
		} else {
			echo do_shortcode( wp_kses_post( $newsletter ) );
		}
	}
}

if ( ! function_exists( 'springoo_render_secondary_footer' ) ) {
	/**
	 * Function Name        : springoo_render_footer_bottom_content
	 * Function Hooked      : springoo_footer_bottom_contents
	 * Function return Type : html
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_render_secondary_footer() {
		$secondary_footer_option = springoo_get_post_layout_options( 'display_secondary_footer' );
		$footer_container        = springoo_get_post_layout_options( 'footer_container' );

		if ( $footer_container ) {
			if ( 'default' === $footer_container ) {
				$footer_secondary_container_cls = springoo_get_mod( 'layout_global_content_layout' );
			} elseif ( 'none' === $footer_container ) {
				$footer_secondary_container_cls = '';
			} else {
				$footer_secondary_container_cls = $footer_container;
			}
		}

		if ( 'disable' === $secondary_footer_option ) {
			return;
		}
		?>
		<div class="springoo-secondary-footer">

				<div class="<?php echo esc_attr( $footer_secondary_container_cls ); ?>">
					<div class="springoo-secondary-footer-wrap">
						<?php
						/**
						 * Secondary Footer Contents
						 *
						 * @hooked springoo_render_footer_menu 10
						 * @hooked springoo_footer_bottom_contents 20
						 */
						do_action( 'springoo_render_secondary_footer' );
						?>
					</div><!-- end .row -->
				</div><!-- end .container -->

		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_render_footer_bottom_menu' ) ) {
	/**
	 * Function Name        : springoo_render_footer_bottom_menu
	 * Function Hooked      : springoo_render_secondary_footer
	 * Function return Type : html
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_render_footer_bottom_menu() {
		?>
			<?php
			wp_nav_menu( [
				'menu_class'     => 'footer-menu',
				'menu_id'        => 'footer-menu',
				'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'theme_location' => 'footer_menu',
			] );
			?>
		<?php
	}
}

if ( ! function_exists( 'springoo_render_footer_download_app' ) ) {
	/**
	 * Function Name        : springoo_render_footer_download_app
	 * Function Hooked      : springoo_render_secondary_footer
	 * Function return Type : html
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_render_footer_download_app() {
		?>
			<div class="springoo-footer-download-app">
				<ul>
					<li>
						<a href="<?php echo esc_url( springoo_get_mod( 'layout_footer_apple_store_link' ) ); ?>">
							<img src="data:image/svg+xml,%3Csvg width='121' height='35' viewBox='0 0 121 35' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M119.594 30.735C119.594 32.5864 118.063 34.0862 116.169 34.0862H4.09706C2.20432 34.0862 0.667969 32.5864 0.667969 30.735V4.01035C0.667969 2.15976 2.20432 0.654785 4.09706 0.654785H116.168C118.063 0.654785 119.593 2.15976 119.593 4.01035L119.594 30.735Z' fill='white'/%3E%3Cpath d='M115.808 0.695715C117.87 0.695715 119.548 2.33186 119.548 4.34258V30.3994C119.548 32.4101 117.87 34.0463 115.808 34.0463H4.45416C2.39188 34.0463 0.713778 32.4101 0.713778 30.3994V4.34258C0.713778 2.33186 2.39188 0.695715 4.45416 0.695715H115.808ZM115.808 -0.000218336H4.45416C2.00548 -0.000218336 0 1.95513 0 4.34258V30.3994C0 32.7868 2.00548 34.7422 4.45416 34.7422H115.808C118.257 34.7422 120.262 32.7868 120.262 30.3994V4.34258C120.262 1.95513 118.257 -0.000218336 115.808 -0.000218336Z' fill='white'/%3E%3Cpath d='M26.839 17.1841C26.8132 14.3848 29.1899 13.0229 29.2986 12.9595C27.9526 11.046 25.8662 10.7846 25.1331 10.7637C23.3808 10.584 21.6811 11.786 20.7885 11.786C19.8781 11.786 18.5035 10.7811 17.0221 10.8107C15.1157 10.8393 13.3323 11.9155 12.3541 13.5866C10.3355 16.9939 11.841 22.0012 13.775 24.7554C14.7424 26.1043 15.8729 27.6103 17.3526 27.5574C18.8002 27.4992 19.3409 26.6575 21.0878 26.6575C22.8187 26.6575 23.3265 27.5574 24.8356 27.5235C26.3892 27.4992 27.3673 26.1685 28.3009 24.8075C29.4189 23.2615 29.8679 21.7389 29.8857 21.6607C29.8492 21.6485 26.8684 20.5394 26.839 17.1841Z' fill='%23252525'/%3E%3Cpath d='M23.9884 8.95193C24.767 8.0026 25.2997 6.71105 25.1518 5.40039C24.0249 5.44903 22.6156 6.16038 21.8041 7.08887C21.0861 7.90705 20.4447 9.24811 20.6103 10.5093C21.8762 10.6013 23.1759 9.8865 23.9884 8.95193Z' fill='%23252525'/%3E%3Cpath d='M47.7888 27.364H45.7657L44.6575 23.9688H40.8055L39.7499 27.364H37.7803L41.5966 15.8052H43.9537L47.7888 27.364ZM44.3234 22.5443L43.3212 19.5261C43.2152 19.2177 43.0166 18.4916 42.7235 17.3486H42.6879C42.5712 17.8402 42.3832 18.5663 42.1249 19.5261L41.1405 22.5443H44.3234Z' fill='%23252525'/%3E%3Cpath d='M57.6033 23.0939C57.6033 24.5114 57.2104 25.6318 56.4247 26.4543C55.7209 27.1865 54.847 27.5522 53.8039 27.5522C52.6779 27.5522 51.869 27.1579 51.3764 26.3692H51.3407V30.7598H49.4415V21.7728C49.4415 20.8817 49.4174 19.9671 49.3711 19.029H51.0414L51.1474 20.3501H51.183C51.8164 19.3547 52.7776 18.8579 54.0676 18.8579C55.076 18.8579 55.9178 19.2462 56.5913 20.0235C57.2665 20.8017 57.6033 21.8249 57.6033 23.0939ZM55.6684 23.1616C55.6684 22.3504 55.4813 21.6816 55.1054 21.1553C54.6947 20.6063 54.1433 20.3319 53.452 20.3319C52.9834 20.3319 52.5576 20.4847 52.1772 20.7861C51.7959 21.0901 51.5465 21.487 51.4298 21.9786C51.371 22.2079 51.3416 22.3956 51.3416 22.5432V23.9329C51.3416 24.5392 51.5323 25.0507 51.9135 25.4685C52.2948 25.8863 52.7901 26.0948 53.3994 26.0948C54.1148 26.0948 54.6715 25.8255 55.0697 25.2887C55.4688 24.7511 55.6684 24.0423 55.6684 23.1616Z' fill='%23252525'/%3E%3Cpath d='M67.4354 23.0939C67.4354 24.5114 67.0425 25.6318 66.2559 26.4543C65.5531 27.1865 64.6791 27.5522 63.636 27.5522C62.51 27.5522 61.7011 27.1579 61.2094 26.3692H61.1737V30.7598H59.2745V21.7728C59.2745 20.8817 59.2504 19.9671 59.2041 19.029H60.8744L60.9804 20.3501H61.016C61.6485 19.3547 62.6098 18.8579 63.9006 18.8579C64.9081 18.8579 65.7499 19.2462 66.4252 20.0235C67.0978 20.8017 67.4354 21.8249 67.4354 23.0939ZM65.5005 23.1616C65.5005 22.3504 65.3125 21.6816 64.9366 21.1553C64.5259 20.6063 63.9763 20.3319 63.2841 20.3319C62.8146 20.3319 62.3897 20.4847 62.0084 20.7861C61.6272 21.0901 61.3786 21.487 61.2619 21.9786C61.204 22.2079 61.1737 22.3956 61.1737 22.5432V23.9329C61.1737 24.5392 61.3644 25.0507 61.7439 25.4685C62.1251 25.8854 62.6204 26.0948 63.2316 26.0948C63.9469 26.0948 64.5037 25.8255 64.9019 25.2887C65.301 24.7511 65.5005 24.0423 65.5005 23.1616Z' fill='%23252525'/%3E%3Cpath d='M78.4279 24.1224C78.4279 25.1057 78.0778 25.9056 77.375 26.5231C76.6026 27.198 75.5274 27.535 74.1457 27.535C72.8701 27.535 71.8474 27.2953 71.0732 26.815L71.5133 25.2716C72.3471 25.7632 73.262 26.0098 74.2589 26.0098C74.9742 26.0098 75.531 25.8517 75.9309 25.5373C76.3291 25.2229 76.5278 24.8008 76.5278 24.2744C76.5278 23.8054 76.3639 23.4102 76.0352 23.0897C75.7082 22.7692 75.1622 22.4713 74.3996 22.196C72.324 21.4412 71.287 20.3355 71.287 18.8816C71.287 17.9313 71.6505 17.1522 72.3783 16.546C73.1034 15.9389 74.0709 15.6357 75.2806 15.6357C76.3594 15.6357 77.2556 15.819 77.9709 16.1847L77.4961 17.6942C76.828 17.3399 76.0726 17.1627 75.2272 17.1627C74.5591 17.1627 74.037 17.3234 73.6629 17.643C73.3466 17.9287 73.1881 18.277 73.1881 18.6896C73.1881 19.1465 73.3689 19.5243 73.7324 19.8213C74.0486 20.0958 74.6232 20.3928 75.457 20.7133C76.477 21.1138 77.2262 21.5819 77.7082 22.1187C78.1883 22.6537 78.4279 23.3234 78.4279 24.1224Z' fill='%23252525'/%3E%3Cpath d='M84.707 20.4181H82.6135V24.4647C82.6135 25.494 82.9823 26.0081 83.7217 26.0081C84.0611 26.0081 84.3426 25.9795 84.5653 25.9222L84.6179 27.3284C84.2438 27.4647 83.7511 27.5333 83.1409 27.5333C82.3908 27.5333 81.8047 27.3101 81.3815 26.8645C80.9602 26.4181 80.7481 25.6694 80.7481 24.6176V20.4163H79.501V19.0267H80.7481V17.5006L82.6135 16.9517V19.0267H84.707V20.4181Z' fill='%23252525'/%3E%3Cpath d='M94.1532 23.1283C94.1532 24.4095 93.7773 25.4613 93.0272 26.2838C92.2406 27.1307 91.1965 27.5528 89.895 27.5528C88.6407 27.5528 87.6421 27.1472 86.8974 26.3359C86.1526 25.5247 85.7803 24.5007 85.7803 23.2664C85.7803 21.9749 86.1633 20.917 86.9321 20.0945C87.6991 19.2711 88.7343 18.8594 90.0358 18.8594C91.2901 18.8594 92.2994 19.265 93.061 20.0771C93.7897 20.8649 94.1532 21.882 94.1532 23.1283ZM92.1827 23.1883C92.1827 22.4196 92.0143 21.7604 91.6731 21.2106C91.2749 20.5453 90.7057 20.2135 89.9681 20.2135C89.2046 20.2135 88.6247 20.5461 88.2265 21.2106C87.8853 21.7612 87.7169 22.4309 87.7169 23.223C87.7169 23.9917 87.8853 24.6509 88.2265 25.1999C88.6372 25.8652 89.2109 26.197 89.9511 26.197C90.6763 26.197 91.2455 25.8582 91.6562 25.1825C92.0063 24.6223 92.1827 23.9561 92.1827 23.1883Z' fill='%23252525'/%3E%3Cpath d='M100.326 20.6576C100.139 20.6237 99.9381 20.6063 99.7278 20.6063C99.0597 20.6063 98.543 20.8521 98.1796 21.3446C97.8633 21.7789 97.7048 22.3278 97.7048 22.9905V27.3637H95.8064L95.8242 21.6538C95.8242 20.6932 95.8001 19.8185 95.7529 19.0299H97.4072L97.4767 20.6246H97.5293C97.7297 20.0765 98.0459 19.6353 98.4789 19.3044C98.902 19.0064 99.359 18.8579 99.8517 18.8579C100.027 18.8579 100.186 18.8701 100.326 18.8918V20.6576Z' fill='%23252525'/%3E%3Cpath d='M108.821 22.8025C108.821 23.1343 108.798 23.414 108.751 23.6424H103.053C103.076 24.4658 103.351 25.0955 103.88 25.5298C104.36 25.9181 104.981 26.1126 105.744 26.1126C106.587 26.1126 107.357 25.9815 108.049 25.7183L108.347 27.0038C107.538 27.3477 106.583 27.5188 105.481 27.5188C104.155 27.5188 103.115 27.1384 102.358 26.3784C101.602 25.6184 101.224 24.5979 101.224 23.3176C101.224 22.0608 101.576 21.0142 102.28 20.1795C103.018 19.2883 104.015 18.8428 105.269 18.8428C106.501 18.8428 107.434 19.2883 108.067 20.1795C108.569 20.8874 108.821 21.7629 108.821 22.8025ZM107.01 22.3222C107.022 21.7733 106.898 21.2991 106.641 20.8987C106.312 20.3836 105.807 20.1265 105.127 20.1265C104.506 20.1265 104.001 20.3775 103.616 20.8813C103.299 21.2817 103.111 21.762 103.053 22.3214H107.01V22.3222Z' fill='%23252525'/%3E%3Cpath d='M43.6955 8.69208C43.6955 9.71438 43.3811 10.4839 42.753 11.0007C42.1713 11.4776 41.3446 11.7164 40.2738 11.7164C39.7429 11.7164 39.2886 11.6938 38.9082 11.6487V6.06295C39.4044 5.98478 39.9389 5.94482 40.5162 5.94482C41.5362 5.94482 42.3049 6.1611 42.8234 6.59364C43.4042 7.08264 43.6955 7.78183 43.6955 8.69208ZM42.7112 8.71727C42.7112 8.05456 42.5312 7.54645 42.1713 7.19208C41.8114 6.83857 41.2858 6.66139 40.5937 6.66139C40.2997 6.66139 40.0494 6.68049 39.8418 6.72045V10.9668C39.9567 10.9842 40.1669 10.992 40.4725 10.992C41.187 10.992 41.7384 10.7983 42.1268 10.411C42.5152 10.0236 42.7112 9.45902 42.7112 8.71727Z' fill='%23252525'/%3E%3Cpath d='M48.9147 9.58596C48.9147 10.2157 48.7303 10.7316 48.3615 11.1363C47.9749 11.5524 47.4627 11.76 46.8231 11.76C46.2066 11.76 45.7158 11.5611 45.3496 11.1615C44.9844 10.7629 44.8018 10.26 44.8018 9.65371C44.8018 9.01966 44.9897 8.49939 45.3674 8.09551C45.7451 7.69163 46.2529 7.48926 46.8925 7.48926C47.509 7.48926 48.0043 7.68816 48.3793 8.08683C48.7357 8.4742 48.9147 8.9745 48.9147 9.58596ZM47.9464 9.61549C47.9464 9.23767 47.8627 8.9137 47.6961 8.64357C47.5001 8.317 47.2213 8.15371 46.8587 8.15371C46.4837 8.15371 46.1986 8.317 46.0026 8.64357C45.8351 8.9137 45.7523 9.24288 45.7523 9.632C45.7523 10.0098 45.836 10.3338 46.0026 10.6039C46.2048 10.9305 46.4863 11.0938 46.8498 11.0938C47.2061 11.0938 47.4858 10.9279 47.6872 10.5952C47.86 10.3199 47.9464 9.99332 47.9464 9.61549Z' fill='%23252525'/%3E%3Cpath d='M55.9135 7.57324L54.5995 11.6676H53.7443L53.2 9.88969C53.0619 9.44586 52.9497 9.00463 52.8624 8.56688H52.8455C52.7644 9.01679 52.6521 9.45715 52.5078 9.88969L51.9297 11.6676H51.0647L49.8291 7.57324H50.7885L51.2633 9.51969C51.3783 9.98002 51.4727 10.4186 51.5484 10.8338H51.5653C51.6348 10.4916 51.7497 10.0556 51.9119 9.52837L52.5078 7.57411H53.2686L53.8396 9.48668C53.9777 9.9531 54.0899 10.4021 54.1764 10.8347H54.2022C54.2654 10.4134 54.3608 9.96439 54.4873 9.48668L54.9968 7.57411H55.9135V7.57324Z' fill='%23252525'/%3E%3Cpath d='M60.7532 11.6672H59.8196V9.32208C59.8196 8.59944 59.5381 8.23812 58.9733 8.23812C58.6962 8.23812 58.4726 8.33714 58.2989 8.53604C58.127 8.73494 58.0397 8.96945 58.0397 9.23783V11.6663H57.1061V8.74275C57.1061 8.38317 57.0945 7.99319 57.0723 7.57107H57.8927L57.9364 8.2112H57.9622C58.0709 8.0123 58.233 7.84814 58.4459 7.71699C58.6989 7.56412 58.9822 7.48682 59.2922 7.48682C59.6842 7.48682 60.0102 7.61015 60.2694 7.85769C60.5919 8.16082 60.7532 8.61334 60.7532 9.21438V11.6672Z' fill='%23252525'/%3E%3Cpath d='M63.3272 11.6669H62.3945V5.69385H63.3272V11.6669Z' fill='%23252525'/%3E%3Cpath d='M68.8239 9.58596C68.8239 10.2157 68.6395 10.7316 68.2707 11.1363C67.8841 11.5524 67.371 11.76 66.7322 11.76C66.1149 11.76 65.624 11.5611 65.2588 11.1615C64.8936 10.7629 64.7109 10.26 64.7109 9.65371C64.7109 9.01966 64.8989 8.49939 65.2766 8.09551C65.6543 7.69163 66.1621 7.48926 66.8008 7.48926C67.4182 7.48926 67.9126 7.68816 68.2885 8.08683C68.6448 8.4742 68.8239 8.9745 68.8239 9.58596ZM67.8547 9.61549C67.8547 9.23767 67.7709 8.9137 67.6044 8.64357C67.4093 8.317 67.1295 8.15371 66.7679 8.15371C66.3919 8.15371 66.1069 8.317 65.9118 8.64357C65.7443 8.9137 65.6615 9.24288 65.6615 9.632C65.6615 10.0098 65.7452 10.3338 65.9118 10.6039C66.114 10.9305 66.3955 11.0938 66.759 11.0938C67.1153 11.0938 67.3941 10.9279 67.5954 10.5952C67.7692 10.3199 67.8547 9.99332 67.8547 9.61549Z' fill='%23252525'/%3E%3Cpath d='M73.342 11.667H72.5038L72.4343 11.1954H72.4085C72.1216 11.5715 71.7127 11.76 71.1818 11.76C70.7854 11.76 70.4647 11.6358 70.2233 11.3891C70.0041 11.165 69.8945 10.8862 69.8945 10.5553C69.8945 10.055 70.1083 9.67369 70.5386 9.40964C70.968 9.1456 71.572 9.01619 72.3497 9.02227V8.94583C72.3497 8.40646 72.0592 8.1372 71.4775 8.1372C71.0633 8.1372 70.6981 8.23883 70.3827 8.44033L70.193 7.84276C70.5831 7.60738 71.0651 7.48926 71.6334 7.48926C72.7309 7.48926 73.2815 8.05382 73.2815 9.18295V10.6908C73.2815 11.0999 73.302 11.4256 73.342 11.667ZM72.3728 10.26V9.62852C71.343 9.61115 70.8281 9.88648 70.8281 10.4537C70.8281 10.6673 70.8869 10.8271 71.0072 10.934C71.1274 11.0408 71.2807 11.0938 71.4633 11.0938C71.6682 11.0938 71.8597 11.0304 72.0343 10.9044C72.2098 10.7776 72.3176 10.6169 72.3577 10.4198C72.3675 10.3755 72.3728 10.3216 72.3728 10.26Z' fill='%23252525'/%3E%3Cpath d='M78.6474 11.6669H77.8189L77.7753 11.0094H77.7494C77.4849 11.5097 77.0341 11.7599 76.4007 11.7599C75.8947 11.7599 75.4734 11.5662 75.1393 11.1788C74.8053 10.7914 74.6387 10.2885 74.6387 9.67098C74.6387 9.00827 74.8195 8.4715 75.183 8.06154C75.5348 7.67938 75.966 7.48829 76.4791 7.48829C77.043 7.48829 77.4377 7.6733 77.6621 8.04417H77.68V5.69385H78.6144V10.5639C78.6144 10.9625 78.6251 11.3299 78.6474 11.6669ZM77.68 9.94024V9.25755C77.68 9.13943 77.6711 9.04388 77.6541 8.97093C77.6016 8.75205 77.4884 8.56791 77.3165 8.41939C77.1428 8.27087 76.9334 8.19617 76.692 8.19617C76.3437 8.19617 76.0711 8.3308 75.8707 8.60092C75.672 8.87104 75.5714 9.21586 75.5714 9.63711C75.5714 10.0419 75.6667 10.3702 75.8582 10.6229C76.0604 10.8922 76.333 11.0268 76.6742 11.0268C76.9807 11.0268 77.2256 10.9148 77.4118 10.6898C77.5918 10.4822 77.68 10.2321 77.68 9.94024Z' fill='%23252525'/%3E%3Cpath d='M86.6315 9.58596C86.6315 10.2157 86.4471 10.7316 86.0783 11.1363C85.6917 11.5524 85.1804 11.76 84.5399 11.76C83.9243 11.76 83.4334 11.5611 83.0664 11.1615C82.7012 10.7629 82.5186 10.26 82.5186 9.65371C82.5186 9.01966 82.7065 8.49939 83.0842 8.09551C83.4619 7.69163 83.9697 7.48926 84.6102 7.48926C85.2258 7.48926 85.722 7.68816 86.0961 8.08683C86.4525 8.4742 86.6315 8.9745 86.6315 9.58596ZM85.6641 9.61549C85.6641 9.23767 85.5803 8.9137 85.4138 8.64357C85.2169 8.317 84.9389 8.15371 84.5755 8.15371C84.2013 8.15371 83.9163 8.317 83.7194 8.64357C83.5519 8.9137 83.4691 9.24288 83.4691 9.632C83.4691 10.0098 83.5528 10.3338 83.7194 10.6039C83.9216 10.9305 84.2031 11.0938 84.5666 11.0938C84.9229 11.0938 85.2035 10.9279 85.4048 10.5952C85.5768 10.3199 85.6641 9.99332 85.6641 9.61549Z' fill='%23252525'/%3E%3Cpath d='M91.6517 11.6672H90.719V9.32208C90.719 8.59944 90.4375 8.23812 89.8718 8.23812C89.5948 8.23812 89.3712 8.33714 89.1984 8.53604C89.0255 8.73494 88.9391 8.96945 88.9391 9.23783V11.6663H88.0046V8.74275C88.0046 8.38317 87.994 7.99319 87.9717 7.57107H88.7912L88.8349 8.2112H88.8607C88.9703 8.0123 89.1324 7.84814 89.3444 7.71699C89.5983 7.56412 89.8807 7.48682 90.1916 7.48682C90.5827 7.48682 90.9087 7.61015 91.168 7.85769C91.4913 8.16082 91.6517 8.61334 91.6517 9.21438V11.6672Z' fill='%23252525'/%3E%3Cpath d='M97.9345 8.25473H96.9065V10.2437C96.9065 10.7492 97.0891 11.002 97.4508 11.002C97.6183 11.002 97.7572 10.9881 97.8668 10.9594L97.8909 11.6499C97.7065 11.7177 97.4642 11.7516 97.1657 11.7516C96.7969 11.7516 96.5101 11.6421 96.3025 11.4232C96.0941 11.2044 95.9907 10.8361 95.9907 10.3193V8.25473H95.377V7.57291H95.9907V6.82248L96.9056 6.55322V7.57204H97.9336V8.25473H97.9345Z' fill='%23252525'/%3E%3Cpath d='M102.877 11.6669H101.942V9.33919C101.942 8.60526 101.661 8.23786 101.097 8.23786C100.664 8.23786 100.368 8.45066 100.206 8.87625C100.178 8.96571 100.162 9.07515 100.162 9.2037V11.6661H99.2295V5.69385H100.162V8.16143H100.18C100.474 7.71238 100.895 7.48829 101.441 7.48829C101.828 7.48829 102.148 7.61163 102.402 7.85917C102.718 8.16751 102.877 8.62611 102.877 9.23236V11.6669Z' fill='%23252525'/%3E%3Cpath d='M107.976 9.42615C107.976 9.58944 107.963 9.72667 107.941 9.83872H105.141C105.154 10.2435 105.287 10.5518 105.546 10.7655C105.783 10.9566 106.089 11.0521 106.463 11.0521C106.877 11.0521 107.255 10.9878 107.595 10.8584L107.741 11.4907C107.343 11.6592 106.875 11.7435 106.332 11.7435C105.682 11.7435 105.17 11.5567 104.799 11.1832C104.427 10.8098 104.242 10.3086 104.242 9.67977C104.242 9.06222 104.414 8.54804 104.761 8.13808C105.122 7.70032 105.611 7.48145 106.229 7.48145C106.833 7.48145 107.292 7.70032 107.602 8.13808C107.852 8.4855 107.976 8.91544 107.976 9.42615ZM107.085 9.19077C107.092 8.92065 107.03 8.68788 106.904 8.49158C106.742 8.23883 106.495 8.11202 106.161 8.11202C105.856 8.11202 105.608 8.23536 105.418 8.4829C105.263 8.68006 105.171 8.91544 105.141 9.19077H107.085Z' fill='%23252525'/%3E%3C/svg%3E%0A" alt="appstore" class="dark">
							<img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='121' height='35' viewBox='0 0 121 35' fill='none'%3E%3Cpath d='M119.594 30.7357C119.594 32.5872 118.063 34.0869 116.169 34.0869H4.09706C2.20432 34.0869 0.667969 32.5872 0.667969 30.7357V4.01108C0.667969 2.16049 2.20432 0.655518 4.09706 0.655518H116.168C118.063 0.655518 119.593 2.16049 119.593 4.01108L119.594 30.7357Z' fill='%23222222'/%3E%3Cpath d='M115.808 0.696448C117.87 0.696448 119.548 2.3326 119.548 4.34331V30.4001C119.548 32.4108 117.87 34.047 115.808 34.047H4.45416C2.39188 34.047 0.713778 32.4108 0.713778 30.4001V4.34331C0.713778 2.3326 2.39188 0.696448 4.45416 0.696448H115.808ZM115.808 0.000514086H4.45416C2.00548 0.000514086 0 1.95586 0 4.34331V30.4001C0 32.7876 2.00548 34.7429 4.45416 34.7429H115.808C118.257 34.7429 120.262 32.7876 120.262 30.4001V4.34331C120.262 1.95586 118.257 0.000514086 115.808 0.000514086Z' fill='%23222222'/%3E%3Cpath d='M26.839 17.1841C26.8132 14.3848 29.1899 13.0229 29.2986 12.9595C27.9526 11.046 25.8662 10.7846 25.1331 10.7637C23.3808 10.584 21.6811 11.786 20.7885 11.786C19.8781 11.786 18.5035 10.7811 17.0221 10.8107C15.1157 10.8393 13.3323 11.9155 12.3541 13.5866C10.3355 16.9939 11.841 22.0012 13.775 24.7554C14.7424 26.1043 15.8729 27.6103 17.3526 27.5574C18.8002 27.4992 19.3409 26.6575 21.0878 26.6575C22.8187 26.6575 23.3265 27.5574 24.8356 27.5235C26.3892 27.4992 27.3673 26.1685 28.3009 24.8075C29.4189 23.2615 29.8679 21.7389 29.8857 21.6607C29.8492 21.6485 26.8684 20.5394 26.839 17.1841Z' fill='white'/%3E%3Cpath d='M23.9884 8.95193C24.767 8.0026 25.2997 6.71105 25.1518 5.40039C24.0249 5.44903 22.6156 6.16038 21.8041 7.08887C21.0861 7.90705 20.4447 9.24811 20.6103 10.5093C21.8762 10.6013 23.1759 9.8865 23.9884 8.95193Z' fill='white'/%3E%3Cpath d='M47.7888 27.3647H45.7657L44.6575 23.9695H40.8055L39.7499 27.3647H37.7803L41.5966 15.8059H43.9537L47.7888 27.3647ZM44.3234 22.5451L43.3212 19.5268C43.2152 19.2185 43.0166 18.4924 42.7235 17.3493H42.6879C42.5712 17.8409 42.3832 18.5671 42.1249 19.5268L41.1405 22.5451H44.3234Z' fill='white'/%3E%3Cpath d='M57.6028 23.0944C57.6028 24.5119 57.2099 25.6323 56.4242 26.4548C55.7204 27.187 54.8465 27.5527 53.8034 27.5527C52.6774 27.5527 51.8685 27.1584 51.3759 26.3697H51.3402V30.7603H49.441V21.7733C49.441 20.8821 49.4169 19.9676 49.3706 19.0295H51.0409L51.1469 20.3506H51.1826C51.8159 19.3552 52.7771 18.8584 54.0671 18.8584C55.0755 18.8584 55.9173 19.2466 56.5908 20.024C57.266 20.8022 57.6028 21.8254 57.6028 23.0944ZM55.6679 23.1621C55.6679 22.3509 55.4808 21.6821 55.1049 21.1557C54.6942 20.6068 54.1428 20.3323 53.4515 20.3323C52.9829 20.3323 52.5571 20.4852 52.1767 20.7866C51.7954 21.0906 51.546 21.4875 51.4293 21.9791C51.3705 22.2084 51.3411 22.396 51.3411 22.5437V23.9334C51.3411 24.5397 51.5318 25.0512 51.913 25.469C52.2943 25.8868 52.7896 26.0952 53.3989 26.0952C54.1143 26.0952 54.6711 25.826 55.0693 25.2892C55.4683 24.7516 55.6679 24.0428 55.6679 23.1621Z' fill='white'/%3E%3Cpath d='M67.4354 23.0944C67.4354 24.5119 67.0425 25.6323 66.2559 26.4548C65.5531 27.187 64.6791 27.5527 63.636 27.5527C62.51 27.5527 61.7011 27.1584 61.2094 26.3697H61.1737V30.7603H59.2745V21.7733C59.2745 20.8821 59.2504 19.9676 59.2041 19.0295H60.8744L60.9804 20.3506H61.016C61.6485 19.3552 62.6098 18.8584 63.9006 18.8584C64.9081 18.8584 65.7499 19.2466 66.4252 20.024C67.0978 20.8022 67.4354 21.8254 67.4354 23.0944ZM65.5005 23.1621C65.5005 22.3509 65.3125 21.6821 64.9366 21.1557C64.5259 20.6068 63.9763 20.3323 63.2841 20.3323C62.8146 20.3323 62.3897 20.4852 62.0084 20.7866C61.6272 21.0906 61.3786 21.4875 61.2619 21.9791C61.204 22.2084 61.1737 22.396 61.1737 22.5437V23.9334C61.1737 24.5397 61.3644 25.0512 61.7439 25.469C62.1251 25.8859 62.6204 26.0952 63.2316 26.0952C63.9469 26.0952 64.5037 25.826 64.9019 25.2892C65.301 24.7516 65.5005 24.0428 65.5005 23.1621Z' fill='white'/%3E%3Cpath d='M78.4279 24.1229C78.4279 25.1061 78.0778 25.9061 77.375 26.5236C76.6026 27.1985 75.5274 27.5355 74.1457 27.5355C72.8701 27.5355 71.8474 27.2958 71.0732 26.8155L71.5133 25.272C72.3471 25.7636 73.262 26.0103 74.2589 26.0103C74.9742 26.0103 75.531 25.8522 75.9309 25.5378C76.3291 25.2234 76.5278 24.8013 76.5278 24.2749C76.5278 23.8059 76.3639 23.4107 76.0352 23.0902C75.7082 22.7697 75.1622 22.4718 74.3996 22.1965C72.324 21.4417 71.287 20.336 71.287 18.882C71.287 17.9318 71.6505 17.1527 72.3783 16.5465C73.1034 15.9394 74.0709 15.6362 75.2806 15.6362C76.3594 15.6362 77.2556 15.8195 77.9709 16.1852L77.4961 17.6947C76.828 17.3403 76.0726 17.1632 75.2272 17.1632C74.5591 17.1632 74.037 17.3238 73.6629 17.6435C73.3466 17.9292 73.1881 18.2775 73.1881 18.6901C73.1881 19.147 73.3689 19.5248 73.7324 19.8218C74.0486 20.0963 74.6232 20.3933 75.457 20.7138C76.477 21.1142 77.2262 21.5824 77.7082 22.1192C78.1883 22.6542 78.4279 23.3239 78.4279 24.1229Z' fill='white'/%3E%3Cpath d='M84.7075 20.4186H82.614V24.4652C82.614 25.4944 82.9828 26.0086 83.7222 26.0086C84.0616 26.0086 84.3431 25.98 84.5658 25.9226L84.6184 27.3288C84.2442 27.4652 83.7516 27.5338 83.1414 27.5338C82.3913 27.5338 81.8052 27.3106 81.382 26.865C80.9606 26.4186 80.7486 25.6699 80.7486 24.6181V20.4168H79.5015V19.0271H80.7486V17.5011L82.614 16.9521V19.0271H84.7075V20.4186Z' fill='white'/%3E%3Cpath d='M94.1527 23.1288C94.1527 24.41 93.7768 25.4618 93.0267 26.2843C92.2401 27.1312 91.196 27.5533 89.8945 27.5533C88.6402 27.5533 87.6416 27.1477 86.8969 26.3364C86.1522 25.5252 85.7798 24.5012 85.7798 23.2669C85.7798 21.9754 86.1628 20.9175 86.9316 20.095C87.6986 19.2716 88.7338 18.8599 90.0353 18.8599C91.2896 18.8599 92.2989 19.2655 93.0605 20.0776C93.7892 20.8654 94.1527 21.8825 94.1527 23.1288ZM92.1822 23.1888C92.1822 22.4201 92.0138 21.7609 91.6726 21.2111C91.2744 20.5457 90.7052 20.2139 89.9676 20.2139C89.2041 20.2139 88.6242 20.5466 88.226 21.2111C87.8848 21.7617 87.7165 22.4314 87.7165 23.2235C87.7165 23.9922 87.8848 24.6514 88.226 25.2004C88.6367 25.8657 89.2104 26.1975 89.9507 26.1975C90.6758 26.1975 91.245 25.8587 91.6557 25.183C92.0058 24.6228 92.1822 23.9566 92.1822 23.1888Z' fill='white'/%3E%3Cpath d='M100.326 20.6581C100.139 20.6242 99.9381 20.6068 99.7278 20.6068C99.0597 20.6068 98.543 20.8526 98.1796 21.3451C97.8633 21.7794 97.7048 22.3283 97.7048 22.991V27.3642H95.8064L95.8242 21.6543C95.8242 20.6937 95.8001 19.819 95.7529 19.0304H97.4072L97.4767 20.6251H97.5293C97.7297 20.077 98.0459 19.6358 98.4789 19.3048C98.902 19.0069 99.359 18.8584 99.8517 18.8584C100.027 18.8584 100.186 18.8706 100.326 18.8923V20.6581Z' fill='white'/%3E%3Cpath d='M108.82 22.803C108.82 23.1348 108.798 23.4145 108.751 23.6429H103.053C103.075 24.4663 103.35 25.096 103.88 25.5303C104.36 25.9186 104.981 26.1131 105.743 26.1131C106.587 26.1131 107.357 25.982 108.049 25.7188L108.346 27.0043C107.537 27.3482 106.582 27.5193 105.48 27.5193C104.155 27.5193 103.114 27.1389 102.357 26.3789C101.602 25.6189 101.223 24.5983 101.223 23.3181C101.223 22.0613 101.575 21.0147 102.28 20.18C103.017 19.2888 104.014 18.8433 105.268 18.8433C106.5 18.8433 107.433 19.2888 108.066 20.18C108.568 20.8879 108.82 21.7634 108.82 22.803ZM107.009 22.3227C107.022 21.7738 106.898 21.2995 106.64 20.8991C106.312 20.3841 105.806 20.127 105.127 20.127C104.506 20.127 104.001 20.378 103.615 20.8818C103.299 21.2822 103.111 21.7625 103.053 22.3218H107.009V22.3227Z' fill='white'/%3E%3Cpath d='M43.695 8.69208C43.695 9.71438 43.3806 10.4839 42.7525 11.0007C42.1708 11.4776 41.3441 11.7164 40.2734 11.7164C39.7424 11.7164 39.2881 11.6938 38.9077 11.6487V6.06295C39.4039 5.98478 39.9384 5.94482 40.5157 5.94482C41.5357 5.94482 42.3045 6.1611 42.8229 6.59364C43.4037 7.08264 43.695 7.78183 43.695 8.69208ZM42.7107 8.71727C42.7107 8.05456 42.5307 7.54645 42.1708 7.19208C41.8109 6.83857 41.2853 6.66139 40.5932 6.66139C40.2992 6.66139 40.0489 6.68049 39.8413 6.72045V10.9668C39.9562 10.9842 40.1665 10.992 40.472 10.992C41.1865 10.992 41.7379 10.7983 42.1263 10.411C42.5147 10.0236 42.7107 9.45902 42.7107 8.71727Z' fill='white'/%3E%3Cpath d='M48.9147 9.58645C48.9147 10.2162 48.7303 10.7321 48.3615 11.1368C47.9749 11.5529 47.4627 11.7605 46.8231 11.7605C46.2066 11.7605 45.7158 11.5616 45.3496 11.162C44.9844 10.7633 44.8018 10.2605 44.8018 9.6542C44.8018 9.02015 44.9897 8.49988 45.3674 8.096C45.7451 7.69212 46.2529 7.48975 46.8925 7.48975C47.509 7.48975 48.0043 7.68865 48.3793 8.08732C48.7357 8.47469 48.9147 8.97498 48.9147 9.58645ZM47.9464 9.61598C47.9464 9.23816 47.8627 8.91418 47.6961 8.64406C47.5001 8.31748 47.2213 8.15419 46.8587 8.15419C46.4837 8.15419 46.1986 8.31748 46.0026 8.64406C45.8351 8.91418 45.7523 9.24337 45.7523 9.63248C45.7523 10.0103 45.836 10.3343 46.0026 10.6044C46.2048 10.931 46.4863 11.0943 46.8498 11.0943C47.2061 11.0943 47.4858 10.9284 47.6872 10.5957C47.86 10.3204 47.9464 9.9938 47.9464 9.61598Z' fill='white'/%3E%3Cpath d='M55.913 7.57349L54.599 11.6679H53.7438L53.1995 9.88994C53.0614 9.4461 52.9492 9.00487 52.8619 8.56712H52.845C52.7639 9.01703 52.6517 9.45739 52.5073 9.88994L51.9292 11.6679H51.0642L49.8286 7.57349H50.788L51.2628 9.51993C51.3778 9.98027 51.4722 10.4189 51.5479 10.8341H51.5648C51.6343 10.4918 51.7492 10.0558 51.9114 9.52862L52.5073 7.57436H53.2681L53.8391 9.48692C53.9772 9.95334 54.0895 10.4024 54.1759 10.8349H54.2017C54.2649 10.4137 54.3603 9.96463 54.4868 9.48692L54.9963 7.57436H55.913V7.57349Z' fill='white'/%3E%3Cpath d='M60.7527 11.6672H59.8191V9.32208C59.8191 8.59944 59.5376 8.23812 58.9728 8.23812C58.6958 8.23812 58.4722 8.33714 58.2984 8.53604C58.1265 8.73494 58.0392 8.96945 58.0392 9.23783V11.6663H57.1056V8.74275C57.1056 8.38317 57.094 7.99319 57.0718 7.57107H57.8922L57.9359 8.2112H57.9617C58.0704 8.0123 58.2325 7.84814 58.4454 7.71699C58.6984 7.56412 58.9817 7.48682 59.2917 7.48682C59.6837 7.48682 60.0097 7.61015 60.269 7.85769C60.5914 8.16082 60.7527 8.61334 60.7527 9.21438V11.6672Z' fill='white'/%3E%3Cpath d='M63.3272 11.6669H62.3945V5.69385H63.3272V11.6669Z' fill='white'/%3E%3Cpath d='M68.8239 9.58645C68.8239 10.2162 68.6395 10.7321 68.2707 11.1368C67.8841 11.5529 67.371 11.7605 66.7322 11.7605C66.1149 11.7605 65.624 11.5616 65.2588 11.162C64.8936 10.7633 64.7109 10.2605 64.7109 9.6542C64.7109 9.02015 64.8989 8.49988 65.2766 8.096C65.6543 7.69212 66.1621 7.48975 66.8008 7.48975C67.4182 7.48975 67.9126 7.68865 68.2885 8.08732C68.6448 8.47469 68.8239 8.97498 68.8239 9.58645ZM67.8547 9.61598C67.8547 9.23816 67.7709 8.91418 67.6044 8.64406C67.4093 8.31748 67.1295 8.15419 66.7679 8.15419C66.3919 8.15419 66.1069 8.31748 65.9118 8.64406C65.7443 8.91418 65.6615 9.24337 65.6615 9.63248C65.6615 10.0103 65.7452 10.3343 65.9118 10.6044C66.114 10.931 66.3955 11.0943 66.759 11.0943C67.1153 11.0943 67.3941 10.9284 67.5954 10.5957C67.7692 10.3204 67.8547 9.9938 67.8547 9.61598Z' fill='white'/%3E%3Cpath d='M73.342 11.6675H72.5038L72.4343 11.1959H72.4085C72.1216 11.572 71.7127 11.7605 71.1818 11.7605C70.7854 11.7605 70.4647 11.6363 70.2233 11.3896C70.0041 11.1655 69.8945 10.8867 69.8945 10.5558C69.8945 10.0555 70.1083 9.67417 70.5386 9.41013C70.968 9.14609 71.572 9.01667 72.3497 9.02275V8.94632C72.3497 8.40695 72.0592 8.13769 71.4775 8.13769C71.0633 8.13769 70.6981 8.23931 70.3827 8.44082L70.193 7.84325C70.5831 7.60787 71.0651 7.48975 71.6334 7.48975C72.7309 7.48975 73.2815 8.05431 73.2815 9.18344V10.6913C73.2815 11.1003 73.302 11.4261 73.342 11.6675ZM72.3728 10.2605V9.62901C71.343 9.61164 70.8281 9.88697 70.8281 10.4541C70.8281 10.6678 70.8869 10.8276 71.0072 10.9345C71.1274 11.0413 71.2807 11.0943 71.4633 11.0943C71.6682 11.0943 71.8597 11.0309 72.0343 10.9049C72.2098 10.7781 72.3176 10.6174 72.3577 10.4203C72.3675 10.376 72.3728 10.3221 72.3728 10.2605Z' fill='white'/%3E%3Cpath d='M78.6469 11.6669H77.8184L77.7748 11.0094H77.749C77.4844 11.5097 77.0336 11.7599 76.4002 11.7599C75.8943 11.7599 75.4729 11.5662 75.1388 11.1788C74.8048 10.7914 74.6382 10.2885 74.6382 9.67098C74.6382 9.00827 74.819 8.4715 75.1825 8.06154C75.5344 7.67938 75.9655 7.48829 76.4786 7.48829C77.0425 7.48829 77.4372 7.6733 77.6617 8.04417H77.6795V5.69385H78.614V10.5639C78.614 10.9625 78.6246 11.3299 78.6469 11.6669ZM77.6795 9.94024V9.25755C77.6795 9.13943 77.6706 9.04388 77.6536 8.97093C77.6011 8.75205 77.4879 8.56791 77.316 8.41939C77.1423 8.27087 76.933 8.19617 76.6915 8.19617C76.3432 8.19617 76.0706 8.3308 75.8702 8.60092C75.6715 8.87104 75.5709 9.21586 75.5709 9.63711C75.5709 10.0419 75.6662 10.3702 75.8577 10.6229C76.0599 10.8922 76.3325 11.0268 76.6737 11.0268C76.9802 11.0268 77.2252 10.9148 77.4113 10.6898C77.5913 10.4822 77.6795 10.2321 77.6795 9.94024Z' fill='white'/%3E%3Cpath d='M86.6315 9.58645C86.6315 10.2162 86.4471 10.7321 86.0783 11.1368C85.6917 11.5529 85.1804 11.7605 84.5399 11.7605C83.9243 11.7605 83.4334 11.5616 83.0664 11.162C82.7012 10.7633 82.5186 10.2605 82.5186 9.6542C82.5186 9.02015 82.7065 8.49988 83.0842 8.096C83.4619 7.69212 83.9697 7.48975 84.6102 7.48975C85.2258 7.48975 85.722 7.68865 86.0961 8.08732C86.4525 8.47469 86.6315 8.97498 86.6315 9.58645ZM85.6641 9.61598C85.6641 9.23816 85.5803 8.91418 85.4138 8.64406C85.2169 8.31748 84.9389 8.15419 84.5755 8.15419C84.2013 8.15419 83.9163 8.31748 83.7194 8.64406C83.5519 8.91418 83.4691 9.24337 83.4691 9.63248C83.4691 10.0103 83.5528 10.3343 83.7194 10.6044C83.9216 10.931 84.2031 11.0943 84.5666 11.0943C84.9229 11.0943 85.2035 10.9284 85.4048 10.5957C85.5768 10.3204 85.6641 9.9938 85.6641 9.61598Z' fill='white'/%3E%3Cpath d='M91.6512 11.6672H90.7185V9.32208C90.7185 8.59944 90.437 8.23812 89.8713 8.23812C89.5943 8.23812 89.3707 8.33714 89.1979 8.53604C89.025 8.73494 88.9386 8.96945 88.9386 9.23783V11.6663H88.0042V8.74275C88.0042 8.38317 87.9935 7.99319 87.9712 7.57107H88.7908L88.8344 8.2112H88.8602C88.9698 8.0123 89.1319 7.84814 89.344 7.71699C89.5978 7.56412 89.8802 7.48682 90.1911 7.48682C90.5822 7.48682 90.9083 7.61015 91.1675 7.85769C91.4909 8.16082 91.6512 8.61334 91.6512 9.21438V11.6672Z' fill='white'/%3E%3Cpath d='M97.9345 8.25522H96.9065V10.2442C96.9065 10.7497 97.0891 11.0025 97.4508 11.0025C97.6183 11.0025 97.7572 10.9886 97.8668 10.9599L97.8909 11.6504C97.7065 11.7182 97.4642 11.752 97.1657 11.752C96.7969 11.752 96.5101 11.6426 96.3025 11.4237C96.0941 11.2049 95.9907 10.8366 95.9907 10.3198V8.25522H95.377V7.5734H95.9907V6.82296L96.9056 6.55371V7.57253H97.9336V8.25522H97.9345Z' fill='white'/%3E%3Cpath d='M102.877 11.6669H101.942V9.33919C101.942 8.60526 101.661 8.23786 101.097 8.23786C100.664 8.23786 100.368 8.45066 100.206 8.87625C100.178 8.96571 100.162 9.07515 100.162 9.2037V11.6661H99.2295V5.69385H100.162V8.16143H100.18C100.474 7.71238 100.895 7.48829 101.441 7.48829C101.828 7.48829 102.148 7.61163 102.402 7.85917C102.718 8.16751 102.877 8.62611 102.877 9.23236V11.6669Z' fill='white'/%3E%3Cpath d='M107.975 9.42664C107.975 9.58993 107.962 9.72716 107.94 9.83921H105.14C105.153 10.244 105.286 10.5523 105.545 10.766C105.782 10.957 106.088 11.0526 106.462 11.0526C106.876 11.0526 107.254 10.9883 107.594 10.8589L107.74 11.4912C107.342 11.6597 106.874 11.744 106.331 11.744C105.681 11.744 105.169 11.5572 104.798 11.1837C104.426 10.8103 104.241 10.3091 104.241 9.68026C104.241 9.06271 104.413 8.54852 104.76 8.13856C105.121 7.70081 105.61 7.48193 106.228 7.48193C106.832 7.48193 107.291 7.70081 107.601 8.13856C107.851 8.48599 107.975 8.91593 107.975 9.42664ZM107.084 9.19126C107.091 8.92114 107.03 8.68836 106.903 8.49207C106.741 8.23932 106.494 8.11251 106.16 8.11251C105.855 8.11251 105.607 8.23584 105.417 8.48338C105.262 8.68055 105.17 8.91593 105.14 9.19126H107.084Z' fill='white'/%3E%3C/svg%3E" alt="appstore" class="light">
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( springoo_get_mod( 'layout_footer_google_play_store_link' ) ); ?>">
							<img src="data:image/svg+xml,%3Csvg width='121' height='35' viewBox='0 0 121 35' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M116.552 34.7422H5.35135C2.90605 34.7422 0.90332 32.7868 0.90332 30.3994V4.3426C0.90332 1.95514 2.90605 -0.000201801 5.35135 -0.000201801H116.552C118.997 -0.000201801 121 1.95514 121 4.3426V30.3994C121 32.7868 118.997 34.7422 116.552 34.7422Z' fill='white'/%3E%3Cpath d='M116.552 0.695729C118.612 0.695729 120.287 2.33188 120.287 4.3426V30.3994C120.287 32.4101 118.612 34.0463 116.552 34.0463H5.35135C3.29191 34.0463 1.61612 32.4101 1.61612 30.3994V4.3426C1.61612 2.33188 3.29191 0.695729 5.35135 0.695729H116.552ZM116.552 -0.000201801H5.35135C2.90605 -0.000201801 0.90332 1.95514 0.90332 4.3426V30.3994C0.90332 32.7868 2.90605 34.7422 5.35135 34.7422H116.552C118.997 34.7422 121 32.7868 121 30.3994V4.3426C121 1.95514 118.997 -0.000201801 116.552 -0.000201801Z' fill='white'/%3E%3Cpath d='M43.0859 8.89754C43.0859 9.62279 42.8635 10.2036 42.4254 10.6368C41.9216 11.1504 41.2655 11.4088 40.4616 11.4088C39.6932 11.4088 39.0371 11.146 38.4989 10.6281C37.9595 10.1027 37.6904 9.45776 37.6904 8.68583C37.6904 7.91389 37.9595 7.26899 38.4989 6.74785C39.0371 6.22563 39.6932 5.96289 40.4616 5.96289C40.8441 5.96289 41.2088 6.03998 41.5569 6.18329C41.9038 6.32769 42.1863 6.52311 42.3909 6.76414L41.9261 7.2223C41.5691 6.81082 41.0832 6.6078 40.4616 6.6078C39.9011 6.6078 39.4152 6.79888 39.0026 7.1843C38.5945 7.57081 38.3899 8.07132 38.3899 8.68583C38.3899 9.30033 38.5945 9.80518 39.0026 10.1917C39.4152 10.5728 39.9011 10.7682 40.4616 10.7682C41.0565 10.7682 41.5569 10.5728 41.9516 10.1873C42.2119 9.93221 42.3598 9.58044 42.3987 9.13096H40.4616V8.50343H43.0459C43.077 8.63914 43.0859 8.77051 43.0859 8.89754Z' fill='%23252525'/%3E%3Cpath d='M47.186 6.72053H44.7585V8.3708H46.9469V8.99833H44.7585V10.6486H47.186V11.2881H44.0713V6.08105H47.186V6.72053Z' fill='%23252525'/%3E%3Cpath d='M50.0795 11.2881H49.3923V6.72053H47.9033V6.08105H51.5696V6.72053H50.0795V11.2881Z' fill='%23252525'/%3E%3Cpath d='M54.2227 11.2881V6.08105H54.9088V11.2881H54.2227Z' fill='%23252525'/%3E%3Cpath d='M57.9506 11.2881H57.2689V6.72053H55.7744V6.08105H59.4451V6.72053H57.9506V11.2881Z' fill='%23252525'/%3E%3Cpath d='M66.386 10.6195C65.86 11.146 65.2084 11.4088 64.4311 11.4088C63.6494 11.4088 62.9977 11.146 62.4717 10.6195C61.9469 10.094 61.6855 9.44907 61.6855 8.68583C61.6855 7.92258 61.9469 7.27767 62.4717 6.75219C62.9977 6.22563 63.6494 5.96289 64.4311 5.96289C65.2039 5.96289 65.8556 6.22563 66.3816 6.75654C66.9109 7.28636 67.1722 7.92692 67.1722 8.68583C67.1722 9.44907 66.9109 10.094 66.386 10.6195ZM62.9799 10.183C63.3758 10.5728 63.8573 10.7682 64.4311 10.7682C65.0004 10.7682 65.4864 10.5728 65.8778 10.183C66.2726 9.79324 66.4727 9.29273 66.4727 8.68583C66.4727 8.07892 66.2726 7.57841 65.8778 7.18865C65.4864 6.79888 65.0004 6.60345 64.4311 6.60345C63.8573 6.60345 63.3758 6.79888 62.9799 7.18865C62.5852 7.57841 62.385 8.07892 62.385 8.68583C62.385 9.29273 62.5852 9.79324 62.9799 10.183Z' fill='%23252525'/%3E%3Cpath d='M68.1367 11.2881V6.08105H68.9707L71.5639 10.1307H71.594L71.5639 9.1297V6.08105H72.25V11.2881H71.5339L68.8184 7.03864H68.7884L68.8184 8.044V11.2881H68.1367Z' fill='%23252525'/%3E%3Cpath d='M61.517 18.8922C59.4275 18.8922 57.7206 20.4447 57.7206 22.5868C57.7206 24.7115 59.4275 26.2804 61.517 26.2804C63.6109 26.2804 65.3178 24.7115 65.3178 22.5868C65.3178 20.4447 63.6109 18.8922 61.517 18.8922ZM61.517 24.8255C60.3705 24.8255 59.3841 23.9016 59.3841 22.5868C59.3841 21.2547 60.3705 20.347 61.517 20.347C62.6634 20.347 63.6542 21.2547 63.6542 22.5868C63.6542 23.9016 62.6634 24.8255 61.517 24.8255ZM53.2336 18.8922C51.1397 18.8922 49.4372 20.4447 49.4372 22.5868C49.4372 24.7115 51.1397 26.2804 53.2336 26.2804C55.3264 26.2804 57.03 24.7115 57.03 22.5868C57.03 20.4447 55.3264 18.8922 53.2336 18.8922ZM53.2336 24.8255C52.086 24.8255 51.0963 23.9016 51.0963 22.5868C51.0963 21.2547 52.086 20.347 53.2336 20.347C54.3801 20.347 55.3665 21.2547 55.3665 22.5868C55.3665 23.9016 54.3801 24.8255 53.2336 24.8255ZM43.3768 20.0246V21.5945H47.2165C47.1042 22.4717 46.804 23.1166 46.3436 23.5661C45.7832 24.109 44.9102 24.7115 43.3768 24.7115C41.0138 24.7115 39.1634 22.8495 39.1634 20.5424C39.1634 18.2353 41.0138 16.3733 43.3768 16.3733C44.6545 16.3733 45.5841 16.8608 46.2702 17.4927L47.4034 16.3864C46.4437 15.4918 45.1671 14.8045 43.3768 14.8045C40.1364 14.8045 37.4131 17.3787 37.4131 20.5424C37.4131 23.7062 40.1364 26.2804 43.3768 26.2804C45.1282 26.2804 46.4437 25.7201 47.4779 24.6692C48.5376 23.6345 48.8679 22.1797 48.8679 21.0049C48.8679 20.6401 48.8367 20.3047 48.7811 20.0246H43.3768ZM83.6826 21.2416C83.3701 20.4154 82.406 18.8922 80.4422 18.8922C78.4962 18.8922 76.876 20.3893 76.876 22.5868C76.876 24.6561 78.4795 26.2804 80.629 26.2804C82.3671 26.2804 83.3701 25.2457 83.7827 24.6431L82.4928 23.8039C82.0624 24.4184 81.4764 24.8255 80.629 24.8255C79.7873 24.8255 79.1834 24.4488 78.7965 23.7062L83.8572 21.6618L83.6826 21.2416ZM78.5229 22.4717C78.4795 21.0473 79.656 20.3177 80.4989 20.3177C81.1595 20.3177 81.7199 20.6401 81.9067 21.1016L78.5229 22.4717ZM74.4096 26.0556H76.0731V15.1986H74.4096V26.0556ZM71.6852 19.7151H71.6296C71.2559 19.283 70.5431 18.8922 69.6402 18.8922C67.7453 18.8922 66.0128 20.5164 66.0128 22.5987C66.0128 24.6692 67.7453 26.2804 69.6402 26.2804C70.5431 26.2804 71.2559 25.8862 71.6296 25.4411H71.6852V25.9709C71.6852 27.3834 70.9123 28.1423 69.6658 28.1423C68.6494 28.1423 68.0189 27.4258 67.7587 26.8232L66.3119 27.4127C66.7289 28.392 67.8332 29.5972 69.6658 29.5972C71.6162 29.5972 73.262 28.4767 73.262 25.7505V19.1169H71.6852V19.7151ZM69.7825 24.8255C68.636 24.8255 67.6764 23.8886 67.6764 22.5987C67.6764 21.297 68.636 20.347 69.7825 20.347C70.9123 20.347 71.803 21.297 71.803 22.5987C71.803 23.8886 70.9123 24.8255 69.7825 24.8255ZM91.4711 15.1986H87.4924V26.0556H89.1515V21.9419H91.4711C93.3137 21.9419 95.1207 20.6401 95.1207 18.5697C95.1207 16.5004 93.3093 15.1986 91.4711 15.1986ZM91.5145 20.4317H89.1515V16.7088H91.5145C92.7533 16.7088 93.4605 17.7131 93.4605 18.5697C93.4605 19.41 92.7533 20.4317 91.5145 20.4317ZM101.771 18.8715C100.572 18.8715 99.3252 19.3883 98.8126 20.5337L100.285 21.1363C100.602 20.5337 101.185 20.3383 101.801 20.3383C102.661 20.3383 103.534 20.8432 103.548 21.7345V21.8485C103.247 21.6792 102.605 21.4284 101.814 21.4284C100.228 21.4284 98.6124 22.2806 98.6124 23.8712C98.6124 25.326 99.9113 26.263 101.371 26.263C102.488 26.263 103.104 25.7712 103.491 25.199H103.548V26.0382H105.15V21.8735C105.15 19.9486 103.678 18.8715 101.771 18.8715ZM101.57 24.8212C101.028 24.8212 100.272 24.5584 100.272 23.9016C100.272 23.0613 101.215 22.7388 102.031 22.7388C102.761 22.7388 103.104 22.8962 103.548 23.1036C103.417 24.109 102.531 24.8212 101.57 24.8212ZM110.988 19.1093L109.081 23.8158H109.024L107.053 19.1093H105.263L108.226 25.6865L106.535 29.3464H108.269L112.834 19.1093H110.988ZM96.0326 26.0556H97.6961V15.1986H96.0326V26.0556Z' fill='%23252525'/%3E%3Cpath d='M10.186 6.54717C9.92467 6.81426 9.77344 7.23008 9.77344 7.76858V26.9768C9.77344 27.5153 9.92467 27.9311 10.186 28.1982L10.2505 28.2568L21.2749 17.4975V17.2435L10.2505 6.4842L10.186 6.54717Z' fill='url(%23paint0_linear_72_600)'/%3E%3Cpath d='M24.9461 21.0854L21.2754 17.4972V17.2432L24.9506 13.6549L25.0329 13.7016L29.3853 16.1195C30.6274 16.8056 30.6274 17.9347 29.3853 18.6253L25.0329 21.0388L24.9461 21.0854Z' fill='url(%23paint1_linear_72_600)'/%3E%3Cpath d='M25.0329 21.0387L21.2755 17.3701L10.1865 28.1978C10.5991 28.6212 11.2718 28.6722 12.0369 28.2488L25.0329 21.0387Z' fill='url(%23paint2_linear_72_600)'/%3E%3Cpath d='M25.0329 13.703L12.0369 6.49287C11.2718 6.07379 10.5991 6.12481 10.1865 6.54824L21.2755 17.3716L25.0329 13.703Z' fill='url(%23paint3_linear_72_600)'/%3E%3Cdefs%3E%3ClinearGradient id='paint0_linear_72_600' x1='20.2961' y1='27.1768' x2='5.72375' y2='12.2514' gradientUnits='userSpaceOnUse'%3E%3Cstop stop-color='%2300A0FF'/%3E%3Cstop offset='0.0066' stop-color='%2300A1FF'/%3E%3Cstop offset='0.2601' stop-color='%2300BEFF'/%3E%3Cstop offset='0.5122' stop-color='%2300D2FF'/%3E%3Cstop offset='0.7604' stop-color='%2300DFFF'/%3E%3Cstop offset='1' stop-color='%2300E3FF'/%3E%3C/linearGradient%3E%3ClinearGradient id='paint1_linear_72_600' x1='31.0027' y1='17.369' x2='9.47699' y2='17.369' gradientUnits='userSpaceOnUse'%3E%3Cstop stop-color='%23FFE000'/%3E%3Cstop offset='0.4087' stop-color='%23FFBD00'/%3E%3Cstop offset='0.7754' stop-color='%23FFA500'/%3E%3Cstop offset='1' stop-color='%23FF9C00'/%3E%3C/linearGradient%3E%3ClinearGradient id='paint2_linear_72_600' x1='22.9897' y1='15.3758' x2='3.22845' y2='-4.86436' gradientUnits='userSpaceOnUse'%3E%3Cstop stop-color='%23FF3A44'/%3E%3Cstop offset='1' stop-color='%23C31162'/%3E%3C/linearGradient%3E%3ClinearGradient id='paint3_linear_72_600' x1='7.39521' y1='34.5897' x2='16.2195' y2='25.5517' gradientUnits='userSpaceOnUse'%3E%3Cstop stop-color='%2332A071'/%3E%3Cstop offset='0.0685' stop-color='%232DA771'/%3E%3Cstop offset='0.4762' stop-color='%2315CF74'/%3E%3Cstop offset='0.8009' stop-color='%2306E775'/%3E%3Cstop offset='1' stop-color='%2300F076'/%3E%3C/linearGradient%3E%3C/defs%3E%3C/svg%3E%0A" alt="googleplay" class="dark">
							<img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='121' height='35' viewBox='0 0 121 35' fill='none'%3E%3Cpath d='M116.552 34.7427H5.35135C2.90605 34.7427 0.90332 32.7873 0.90332 30.3999V4.34309C0.90332 1.95563 2.90605 0.00028648 5.35135 0.00028648H116.552C118.997 0.00028648 121 1.95563 121 4.34309V30.3999C121 32.7873 118.997 34.7427 116.552 34.7427Z' fill='%23222222'/%3E%3Cpath d='M116.552 0.696218C118.612 0.696218 120.287 2.33237 120.287 4.34309V30.3999C120.287 32.4106 118.612 34.0467 116.552 34.0467H5.35135C3.29191 34.0467 1.61612 32.4106 1.61612 30.3999V4.34309C1.61612 2.33237 3.29191 0.696218 5.35135 0.696218H116.552ZM116.552 0.00028648H5.35135C2.90605 0.00028648 0.90332 1.95563 0.90332 4.34309V30.3999C0.90332 32.7873 2.90605 34.7427 5.35135 34.7427H116.552C118.997 34.7427 121 32.7873 121 30.3999V4.34309C121 1.95563 118.997 0.00028648 116.552 0.00028648Z' fill='%23222222'/%3E%3Cpath d='M43.0864 8.89778C43.0864 9.62303 42.864 10.2039 42.4259 10.6371C41.9221 11.1506 41.266 11.409 40.462 11.409C39.6936 11.409 39.0376 11.1463 38.4993 10.6284C37.96 10.1029 37.6909 9.458 37.6909 8.68607C37.6909 7.91414 37.96 7.26923 38.4993 6.7481C39.0376 6.22587 39.6936 5.96313 40.462 5.96313C40.8446 5.96313 41.2093 6.04022 41.5574 6.18353C41.9043 6.32793 42.1868 6.52336 42.3914 6.76438L41.9266 7.22255C41.5696 6.81107 41.0837 6.60804 40.462 6.60804C39.9016 6.60804 39.4156 6.79912 39.0031 7.18455C38.595 7.57106 38.3904 8.07156 38.3904 8.68607C38.3904 9.30058 38.595 9.80543 39.0031 10.1919C39.4156 10.573 39.9016 10.7684 40.462 10.7684C41.057 10.7684 41.5574 10.573 41.9521 10.1876C42.2123 9.93245 42.3602 9.58069 42.3992 9.13121H40.462V8.50367H43.0463C43.0775 8.63939 43.0864 8.77076 43.0864 8.89778Z' fill='white'/%3E%3Cpath d='M47.186 6.72102H44.7585V8.37128H46.9469V8.99882H44.7585V10.6491H47.186V11.2886H44.0713V6.08154H47.186V6.72102Z' fill='white'/%3E%3Cpath d='M50.0795 11.2886H49.3923V6.72102H47.9033V6.08154H51.5696V6.72102H50.0795V11.2886Z' fill='white'/%3E%3Cpath d='M54.2231 11.2886V6.08154H54.9093V11.2886H54.2231Z' fill='white'/%3E%3Cpath d='M57.9506 11.2886H57.2689V6.72102H55.7744V6.08154H59.4451V6.72102H57.9506V11.2886Z' fill='white'/%3E%3Cpath d='M66.386 10.6197C65.86 11.1463 65.2084 11.409 64.4311 11.409C63.6494 11.409 62.9977 11.1463 62.4717 10.6197C61.9469 10.0942 61.6855 9.44932 61.6855 8.68607C61.6855 7.92282 61.9469 7.27792 62.4717 6.75244C62.9977 6.22587 63.6494 5.96313 64.4311 5.96313C65.2039 5.96313 65.8556 6.22587 66.3816 6.75678C66.9109 7.2866 67.1722 7.92717 67.1722 8.68607C67.1722 9.44932 66.9109 10.0942 66.386 10.6197ZM62.9799 10.1833C63.3758 10.573 63.8573 10.7684 64.4311 10.7684C65.0004 10.7684 65.4864 10.573 65.8778 10.1833C66.2726 9.79348 66.4727 9.29298 66.4727 8.68607C66.4727 8.07916 66.2726 7.57866 65.8778 7.18889C65.4864 6.79912 65.0004 6.6037 64.4311 6.6037C63.8573 6.6037 63.3758 6.79912 62.9799 7.18889C62.5852 7.57866 62.385 8.07916 62.385 8.68607C62.385 9.29298 62.5852 9.79348 62.9799 10.1833Z' fill='white'/%3E%3Cpath d='M68.1367 11.2886V6.08154H68.9707L71.5639 10.1312H71.594L71.5639 9.13019V6.08154H72.25V11.2886H71.5339L68.8184 7.03913H68.7884L68.8184 8.04449V11.2886H68.1367Z' fill='white'/%3E%3Cpath d='M61.5165 18.8929C59.427 18.8929 57.7201 20.4455 57.7201 22.5875C57.7201 24.7123 59.427 26.2811 61.5165 26.2811C63.6104 26.2811 65.3173 24.7123 65.3173 22.5875C65.3173 20.4455 63.6104 18.8929 61.5165 18.8929ZM61.5165 24.8263C60.37 24.8263 59.3836 23.9023 59.3836 22.5875C59.3836 21.2554 60.37 20.3477 61.5165 20.3477C62.663 20.3477 63.6538 21.2554 63.6538 22.5875C63.6538 23.9023 62.663 24.8263 61.5165 24.8263ZM53.2331 18.8929C51.1392 18.8929 49.4367 20.4455 49.4367 22.5875C49.4367 24.7123 51.1392 26.2811 53.2331 26.2811C55.3259 26.2811 57.0295 24.7123 57.0295 22.5875C57.0295 20.4455 55.3259 18.8929 53.2331 18.8929ZM53.2331 24.8263C52.0855 24.8263 51.0959 23.9023 51.0959 22.5875C51.0959 21.2554 52.0855 20.3477 53.2331 20.3477C54.3796 20.3477 55.366 21.2554 55.366 22.5875C55.366 23.9023 54.3796 24.8263 53.2331 24.8263ZM43.3763 20.0253V21.5952H47.2161C47.1037 22.4725 46.8035 23.1174 46.3431 23.5668C45.7827 24.1097 44.9098 24.7123 43.3763 24.7123C41.0133 24.7123 39.1629 22.8503 39.1629 20.5432C39.1629 18.2361 41.0133 16.3741 43.3763 16.3741C44.654 16.3741 45.5836 16.8616 46.2697 17.4934L47.4029 16.3871C46.4432 15.4925 45.1666 14.8052 43.3763 14.8052C40.1359 14.8052 37.4126 17.3794 37.4126 20.5432C37.4126 23.7069 40.1359 26.2811 43.3763 26.2811C45.1277 26.2811 46.4432 25.7209 47.4774 24.6699C48.5371 23.6352 48.8674 22.1804 48.8674 21.0057C48.8674 20.6409 48.8363 20.3054 48.7807 20.0253H43.3763ZM83.6821 21.2424C83.3697 20.4161 82.4055 18.8929 80.4417 18.8929C78.4957 18.8929 76.8755 20.3901 76.8755 22.5875C76.8755 24.6569 78.479 26.2811 80.6286 26.2811C82.3666 26.2811 83.3697 25.2464 83.7822 24.6439L82.4923 23.8046C82.0619 24.4191 81.4759 24.8263 80.6286 24.8263C79.7868 24.8263 79.1829 24.4495 78.796 23.7069L83.8567 21.6625L83.6821 21.2424ZM78.5224 22.4725C78.479 21.048 79.6556 20.3184 80.4985 20.3184C81.159 20.3184 81.7194 20.6409 81.9063 21.1023L78.5224 22.4725ZM74.4091 26.0563H76.0727V15.1994H74.4091V26.0563ZM71.6847 19.7159H71.6291C71.2554 19.2838 70.5427 18.8929 69.6397 18.8929C67.7448 18.8929 66.0123 20.5171 66.0123 22.5995C66.0123 24.6699 67.7448 26.2811 69.6397 26.2811C70.5427 26.2811 71.2554 25.887 71.6291 25.4418H71.6847V25.9717C71.6847 27.3842 70.9118 28.1431 69.6653 28.1431C68.6489 28.1431 68.0184 27.4265 67.7582 26.8239L66.3115 27.4135C66.7285 28.3928 67.8327 29.5979 69.6653 29.5979C71.6157 29.5979 73.2615 28.4775 73.2615 25.7513V19.1176H71.6847V19.7159ZM69.782 24.8263C68.6356 24.8263 67.6759 23.8893 67.6759 22.5995C67.6759 21.2977 68.6356 20.3477 69.782 20.3477C70.9118 20.3477 71.8026 21.2977 71.8026 22.5995C71.8026 23.8893 70.9118 24.8263 69.782 24.8263ZM91.4706 15.1994H87.4919V26.0563H89.151V21.9426H91.4706C93.3132 21.9426 95.1202 20.6409 95.1202 18.5704C95.1202 16.5011 93.3088 15.1994 91.4706 15.1994ZM91.514 20.4324H89.151V16.7096H91.514C92.7528 16.7096 93.46 17.7138 93.46 18.5704C93.46 19.4108 92.7528 20.4324 91.514 20.4324ZM101.77 18.8723C100.571 18.8723 99.3247 19.3891 98.8121 20.5345L100.284 21.137C100.601 20.5345 101.184 20.3391 101.8 20.3391C102.661 20.3391 103.534 20.8439 103.547 21.7353V21.8493C103.247 21.6799 102.604 21.4291 101.813 21.4291C100.228 21.4291 98.6119 22.2814 98.6119 23.8719C98.6119 25.3268 99.9108 26.2637 101.371 26.2637C102.487 26.2637 103.103 25.7719 103.49 25.1997H103.547V26.039H105.149V21.8742C105.149 19.9493 103.677 18.8723 101.77 18.8723ZM101.57 24.8219C101.027 24.8219 100.271 24.5592 100.271 23.9023C100.271 23.062 101.214 22.7395 102.03 22.7395C102.761 22.7395 103.103 22.897 103.547 23.1043C103.417 24.1097 102.531 24.8219 101.57 24.8219ZM110.987 19.11L109.08 23.8166H109.024L107.052 19.11H105.263L108.225 25.6872L106.535 29.3471H108.269L112.833 19.11H110.987ZM96.0321 26.0563H97.6957V15.1994H96.0321V26.0563Z' fill='white'/%3E%3Cpath d='M10.1865 6.54766C9.92516 6.81474 9.77393 7.23057 9.77393 7.76907V26.9773C9.77393 27.5158 9.92516 27.9316 10.1865 28.1987L10.251 28.2573L21.2754 17.498V17.244L10.251 6.48469L10.1865 6.54766Z' fill='url(%23paint0_linear_301_20116)'/%3E%3Cpath d='M24.9461 21.0859L21.2754 17.4977V17.2436L24.9506 13.6554L25.0329 13.7021L29.3853 16.1199C30.6274 16.8061 30.6274 17.9352 29.3853 18.6257L25.0329 21.0393L24.9461 21.0859Z' fill='url(%23paint1_linear_301_20116)'/%3E%3Cpath d='M25.0329 21.0391L21.2755 17.3706L10.1865 28.1982C10.5991 28.6217 11.2718 28.6727 12.0369 28.2493L25.0329 21.0391Z' fill='url(%23paint2_linear_301_20116)'/%3E%3Cpath d='M25.0329 13.7032L12.0369 6.49311C11.2718 6.07403 10.5991 6.12506 10.1865 6.54848L21.2755 17.3718L25.0329 13.7032Z' fill='url(%23paint3_linear_301_20116)'/%3E%3Cdefs%3E%3ClinearGradient id='paint0_linear_301_20116' x1='20.2966' y1='27.1773' x2='5.72424' y2='12.2519' gradientUnits='userSpaceOnUse'%3E%3Cstop stop-color='%2300A0FF'/%3E%3Cstop offset='0.0066' stop-color='%2300A1FF'/%3E%3Cstop offset='0.2601' stop-color='%2300BEFF'/%3E%3Cstop offset='0.5122' stop-color='%2300D2FF'/%3E%3Cstop offset='0.7604' stop-color='%2300DFFF'/%3E%3Cstop offset='1' stop-color='%2300E3FF'/%3E%3C/linearGradient%3E%3ClinearGradient id='paint1_linear_301_20116' x1='31.0027' y1='17.3695' x2='9.47699' y2='17.3695' gradientUnits='userSpaceOnUse'%3E%3Cstop stop-color='%23FFE000'/%3E%3Cstop offset='0.4087' stop-color='%23FFBD00'/%3E%3Cstop offset='0.7754' stop-color='%23FFA500'/%3E%3Cstop offset='1' stop-color='%23FF9C00'/%3E%3C/linearGradient%3E%3ClinearGradient id='paint2_linear_301_20116' x1='22.9897' y1='15.3763' x2='3.22845' y2='-4.86387' gradientUnits='userSpaceOnUse'%3E%3Cstop stop-color='%23FF3A44'/%3E%3Cstop offset='1' stop-color='%23C31162'/%3E%3C/linearGradient%3E%3ClinearGradient id='paint3_linear_301_20116' x1='7.39521' y1='34.59' x2='16.2195' y2='25.5519' gradientUnits='userSpaceOnUse'%3E%3Cstop stop-color='%2332A071'/%3E%3Cstop offset='0.0685' stop-color='%232DA771'/%3E%3Cstop offset='0.4762' stop-color='%2315CF74'/%3E%3Cstop offset='0.8009' stop-color='%2306E775'/%3E%3Cstop offset='1' stop-color='%2300F076'/%3E%3C/linearGradient%3E%3C/defs%3E%3C/svg%3E" alt="googleplay" class="light">
						</a>
					</li>
				</ul>
			</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_render_footer_credits' ) ) {

	/**
	 * Function Name        : springoo_render_footer_credits
	 * Function Hooked      : springoo_footer_bottom_contents
	 * Function return Type : html
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_render_footer_credits() {
		$credit_option    = springoo_get_post_layout_options( 'display_credit' );
		$footer_container = springoo_get_post_layout_options( 'footer_container' );

		if ( $footer_container ) {
			if ( 'default' === $footer_container ) {
				$footer_credit_container_cls = springoo_get_mod( 'layout_global_content_layout' );
			} elseif ( 'none' === $footer_container ) {
				$footer_credit_container_cls = '';
			} else {
				$footer_credit_container_cls = $footer_container;
			}
		}

		if ( 'disable' === $credit_option ) {
			return;
		}
		?>
		<div class="springoo-footer-copyright">
				<div class="<?php echo esc_attr( $footer_credit_container_cls ); ?>">
					<div class="row">
						<div class="col-md-12">
							<div class="footer-copyright-wrap">
								<?php if ( ! empty( springoo_get_mod( 'layout_footer_payment_gateway' ) ) ) { ?>
									<div class="payment-gateway">
										<img src="<?php echo esc_url( springoo_get_mod( 'layout_footer_payment_gateway' ) ); ?>" alt="<?php esc_attr_e('Payment gateway', 'springoo'); ?>">
									</div>
								<?php } ?>

								<?php if ( ! empty( springoo_get_mod( 'layout_footer_footer_text' ) ) ) { ?>
									<div class="copyright"><?php echo wp_kses_post( springoo_get_mod( 'layout_footer_footer_text' ) ); ?></div><!-- .copyright -->
								<?php } else { ?>
									<div class="powered-by">
										<?php
										printf(
										/* translators: %1$s: WordPress, %2$s: heart icon, %3$s: Springoo  */
											esc_html__( 'Proudly powered by %1$s. Made with ❤️ by %2$s', 'springoo' ),
											'<a href="' . esc_url( __( 'https://wordpress.org/', 'springoo' ) ) . '" rel="noopener" target="_blank">WordPress</a>',
											'<a href="' . esc_url( __( 'https://themerox.com/', 'springoo' ) ) . '" rel="noopener" target="_blank">ThemeRox</a>'
										);
										?>
									</div><!-- .made-with -->
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_render_go_to_top' ) ) {
	/**
	 * Function Name        : springoo_go_to_top
	 * Function Hooked      : springoo_footer_go_to_top
	 * Function return Type : html
	 * Function Since       : 1.0.0
	 *
	 * @return void
	 */
	function springoo_render_go_to_top() {
		if ( springoo_get_mod( 'layout_footer_scroll_to_top' ) === 1 ) {
			?>
			<a href="#" class="cd-top"><i class="ti-angle-up"></i><span
					class="sr-only"><?php esc_html_e( 'Goto Top', 'springoo' ); ?></span></a>
			<?php
		}
	}
}

if ( ! function_exists( 'springoo_disable_mediaelement' ) ) {
	/**
	 * Disable WP MediaElement JS in frontend (shortcodes)
	 *
	 * @return string
	 */
	function springoo_disable_mediaelement() {
		// returning anything other then mediaelement do the job.
		return 'plyr'; // use plyr
	}
}

if ( ! function_exists( 'springoo_set_gallery_thumb_size' ) ) {
	/**
	 * Filter thumbnail size for gallery images (archive page)
	 *
	 * @param array $atts
	 *
	 * @return array
	 */
	function springoo_set_gallery_thumb_size( $atts ) {
		if ( ! is_admin() && ! is_singular() ) {

			$springoo_layout = springoo_get_content_layout();
			if ( 'normal' === $springoo_layout ) {
				$atts['size'] = 'post-thumbnails';
			} else {
				$atts['size'] = 'springoo-gallery';
			}
		}

		return $atts;
	}
}

if ( ! function_exists( 'springoo_pingback_header' ) ) {
	/**
	 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
	 *
	 * @return void
	 * @since Twenty Twenty-One 1.0
	 */
	function springoo_pingback_header() {
		if ( is_singular() && pings_open() ) {
			?>
			<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
			<?php
		}
	}
}

if ( ! function_exists( 'springoo_supports_js' ) ) {
	/**
	 * Remove the `no-js` class from body if JS is supported.
	 *
	 * @return void
	 * @since Twenty Twenty-One 1.0
	 */
	function springoo_supports_js() {
		?>
		<script>document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/, '') +
				' js'</script>
		<?php
	}
}

if ( ! function_exists( 'springoo_mini_search' ) ) {
	/**
	 * @return void
	 */
	function springoo_mini_search() {
		if ( 1 == springoo_get_mod( 'layout_header_mini_search' ) ) {
			?>
			<div class="springoo-search-wrapper">
				<a href="javascript:void(0);" class="springoo-mini-search-icon">
					<i class="si si-thin-search-2" aria-hidden="true"></i>
				</a>
			</div>
			<?php
		}
	}
}

/**
 * @return void
 */
function header_search_form() {
	?>
	<div class="springoo-search-wrap">
		<a href="javascript:void(0);" title="Close search" class="springoo-close-search springoo-stclose" rel="nofollow"><i class="si-thin-close" aria-hidden="true"></i></a>
		<div class="springoo-search-form container">
			<?php springoo_header_search(); ?>
		</div>
	</div>
	<?php
}

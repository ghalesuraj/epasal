<?php
/**
 * Core Function for theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @author ThemeRox
 * @category HelperFunctions
 * @package Springoo\Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * @global string $springoo_get_current_screen Springoo Current Screen Name
 * @global array $springoo_loaded_sidebar_position Springoo Loaded Sidebar posrition for screens
 * @global array $springoo_loaded_layout_options Springoo Loaded loaded layout options for posts/pages/cpt etc.
 */
global
$springoo_screen,
$springoo_loaded_sidebar_position,
$springoo_loaded_layout_options;

if ( ! function_exists( 'springoo_class_attribute_helper' ) ) {
	/**
	 * Combine base classes with extra.
	 *
	 * @param string[] $base base class names.
	 * @param string|string[] $extra Space-separated string or array of extra class names to add to the class list.
	 *
	 * @return array
	 */
	function springoo_class_attribute_helper( $base, $extra ) {
		if ( ! empty( $extra ) ) {
			if ( ! is_array( $extra ) ) {
				$extra = preg_split( '#\s+#', $extra );
			}
			$base = array_merge( $base, $extra ); // @phpstan-ignore-line
		} else {
			$extra = array();
		}

		return array( array_map( 'esc_attr', $base ), $extra );
	}
}

if ( ! function_exists( 'springoo_is_not_default_val' ) ) {
	/**
	 * Check input value is not eqeqeq to 'default' && not empty.
	 *
	 * @param mixed $input input value to check.
	 *
	 * @return bool
	 */
	function springoo_is_not_default_val( $input ) {
		return ( ! empty( $input ) && 'default' !== $input );
	}
}

if ( ! function_exists( 'springoo_get_post_layout_options' ) ) {
	/**
	 * Get Post Settings.
	 *
	 * @param string $option Option to get.
	 * @param int|WP_Post|null $post Post id. Default null. If not set will use the current post id.
	 *
	 * @return array|string|int|false
	 */
	function springoo_get_post_layout_options( $option = '', $post = null ) {
		global $springoo_loaded_layout_options;

		$post = get_post( $post );

		if ( null === $springoo_loaded_layout_options ) {
			$springoo_loaded_layout_options = array();
		}

		$option   = strtolower( $option );
		$defaults = apply_filters(
			'springoo_page_post_layout_defaults',
			array(
				'springoo_display_header_top'       => 'default',
				'springoo_display_header'           => 'default',
				'springoo_display_header_bottom'    => 'default',
				'springoo_secondary_header'         => 'default',
				'springoo_section_height'           => 0,
				'springoo_site_layout'              => 'default',
				'springoo_header_container'         => 'default',
				'springoo_content_container'        => 'default',
				'springoo_footer_container'         => 'default',
				'springoo_titlebar'                 => 'default',
				'springoo_page_title'               => 'default',
				'springoo_content_layout'           => 'default',
				'springoo_sidebar'                  => 'default',
				'springoo_footer_newsletter'        => '',
				'springoo_footer_services'          => '',
				'springoo_display_main_footer'      => 'default',
				'springoo_display_secondary_footer' => 'default',
				'springoo_display_credit'           => 'default',
				'springoo_footer_color_scheme'      => 'default',
			)
		);

		// fx option name.
		if ( ! $option ) {
			$option = 'all';
		} else {
			$option = str_replace( 'springoo_', '', $option );
		}

		if ( $post ) {

			if ( 'all' !== $option && has_filter( "springoo_pre_get_post_{$option}_option" ) ) {
				// Eg. springoo_pre_get_post_content_layout_option
				// Eg. springoo_pre_get_post_display_header_option
				return apply_filters( "springoo_pre_get_post_{$option}_option", $post );
			}

			if ( ! isset( $springoo_loaded_layout_options[ $post->ID ] ) ) {
				$settings = (array) get_post_meta( $post->ID, 'springoo', true );
				$settings = array_filter( $settings );
				$settings = wp_parse_args( $settings, $defaults );
				$settings = apply_filters( 'springoo_post_layout_options', $settings, $post );
				// Store into cache.
				$springoo_loaded_layout_options[ $post->ID ] = $settings;
			} else {
				// Read from cache.
				$settings = $springoo_loaded_layout_options[ $post->ID ];
			}
		} else {
			// This function can be called from any archive pages...
			$settings = apply_filters( 'springoo_post_layout_options', $defaults, null );
		}

		if ( 'all' === $option ) {
			return $settings;
		}

		if ( isset( $settings[ $option ] ) ) {
			return $settings[ $option ];
		}

		if ( isset( $settings[ 'springoo_' . $option ] ) ) {
			return $settings[ 'springoo_' . $option ];
		}

		_doing_it_wrong(
			__FUNCTION__,
			sprintf(
			/* translators: %s: Option name. */
				esc_html__( 'Option %s is not registered within current theme', 'springoo' ),
				'<code>' . esc_html( $option ) . '</code>'
			),
			'1.0.0'
		);

		return false;
	}
}

if ( ! function_exists( 'springoo_get_current_screen' ) ) {
	/**
	 * Determine the current view.
	 *
	 * @return string The string representing the current view.
	 */
	function springoo_get_current_screen() {
		global $springoo_screen;

		if ( isset( $springoo_screen ) ) {
			return $springoo_screen;
		}

		// Post types.
		$post_types   = get_post_types(
			array(
				'public'   => true,
				'_builtin' => false,
			)
		);
		$post_types[] = 'post';

		// Post parent.
		$parent_post_type = '';
		if ( is_attachment() ) {
			$attachment       = get_post();
			$parent_post_type = $attachment ? get_post_type( $attachment->post_parent ) : '';
		}

		$springoo_screen = 'post';

		if ( is_home() ) {
			$springoo_screen = 'blog'; // Blog.
		} elseif ( is_archive() && ! is_post_type_archive( 'product' ) ) {
			$springoo_screen = 'archive'; // Archives.
		} elseif ( is_search() ) {
			$springoo_screen = 'search'; // Search results.
			// @phpstan-ignore-next-line
		} elseif ( is_singular( $post_types ) || ( is_attachment() && in_array( $parent_post_type, $post_types, true ) ) ) {
			$springoo_screen = 'post'; // Posts and public custom post types.
		} elseif ( is_page() || ( is_attachment() && 'page' === $parent_post_type ) ) {
			$springoo_screen = 'page'; // Pages.
		}

		return apply_filters( 'springoo_current_screen', $springoo_screen );
	}
}

if ( ! function_exists( 'springoo_get_sidebar_position' ) ) {
	/**
	 * Get Sidebar Position.
	 *
	 * @param string $screen current view.
	 *
	 * @return string
	 */
	function springoo_get_sidebar_position( $screen = '' ) {
		global $springoo_loaded_sidebar_position;

		if ( ! $screen ) {
			$screen = springoo_get_current_screen();
		}

		if ( null === $springoo_loaded_sidebar_position ) {
			$springoo_loaded_sidebar_position = array();
		}

		if ( isset( $springoo_loaded_sidebar_position[ $screen ] ) ) {
			return $springoo_loaded_sidebar_position[ $screen ];
		}

		if ( class_exists( 'WooCommerce' ) && ( is_post_type_archive( 'product' ) ) ) {
			$position = springoo_get_mod( 'woocommerce_shop_archive_layout' );
		} elseif ( class_exists( 'WooCommerce' ) && is_product() ) {
			$position = springoo_get_mod( 'woocommerce_single_layout' );
		} elseif ( class_exists( 'WooCommerce' ) && is_cart() ) {
			$position = springoo_get_mod( 'woocommerce_cart_sidebar_layout' );
		} elseif ( class_exists( 'WooCommerce' ) && is_checkout() ) {
			$position = springoo_get_mod( 'woocommerce_checkout_sidebar_layout' );
		} elseif ( class_exists( 'WooCommerce' ) && is_account_page() ) {
			$position = springoo_get_mod( 'woocommerce_myaccount_sidebar_layout' );
		} elseif ( class_exists( 'WooCommerce' ) && is_product_category() ) {
			$position = springoo_get_mod( 'woocommerce_product_catalog_sidebar_layout' );
		} elseif ( class_exists( 'WooCommerce' ) && is_product_category() ) {
			$position = springoo_get_mod( 'woocommerce_taxonomy_archive_cat_layout' );
		} elseif ( class_exists( 'WooCommerce' ) && is_product_tag() ) {
			$position = springoo_get_mod( 'woocommerce_taxonomy_archive_tag_layout' );
		} elseif ( class_exists( 'WeDevs_Dokan' ) && dokan_is_store_page() ) {
			$position = 'none';
		} else {
			/**
			 * @var string $single_sidebar_position
			 */
			$single_sidebar_position = springoo_get_post_layout_options( 'content_layout' );
			if ( 'default' !== $single_sidebar_position && $single_sidebar_position ) {
				$position = $single_sidebar_position;
			} else {
				$position = springoo_get_mod( 'layout_' . $screen . '_sidebar' );
				// In case current screen is modified and not found in default values.
				if ( ! $position ) {
					$position = 'none';
				}
			}
		}

		$springoo_loaded_sidebar_position[ $screen ] = apply_filters( 'springoo_sidebar_position', $position, $screen );

		return $springoo_loaded_sidebar_position[ $screen ];
	}
}

if ( ! function_exists( 'springoo_layzload_enabled' ) ) {
	/**
	 * Checks if lazy-loading images is enabled.
	 *
	 * @return bool
	 */
	function springoo_layzload_enabled() {
		return (bool) springoo_get_mod( 's_config_lazyload_enable' );
	}
}

if ( ! function_exists( 'springoo_maybe_lazyload_post_thumbnail' ) ) {
	/**
	 * Springoo lazyload post thumbnail
	 *
	 * @return mixed|void
	 */
	function springoo_maybe_lazyload_post_thumbnail() {
		return apply_filters(
			'springoo_lazyload_post_thumbnail',
			(
				springoo_layzload_enabled() &&
				! is_admin() &&
				! wp_doing_ajax()
			)
		);
	}
}

if ( ! function_exists( 'springoo_header_search' ) ) {
	/**
	 * Function springoo_header_search
	 *
	 * @return void
	 */
	function springoo_header_search() {
		?>
		<div class="springoo-header-search">
			<?php
			if ( class_exists( 'WooCommerce' ) ) {
				springoo_product_search();
			} else {
				get_search_form( array( 'echo' => true ) );
			}
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_social_icons' ) ) {
	/**
	 * Prints Social Icons HTML markup
	 *
	 * @return void
	 */
	function springoo_social_icons() {

		$profiles = (array) springoo_get_mod( 'social_profiles' );
		$defaults = array(
			'label' => '',
			'url'   => '',
			'icon'  => '',
		);
		?>
		<ul class="springoo-social">
		<?php
		foreach ( $profiles as $profile ) {
			$profile = wp_parse_args( $profile, $defaults );
			if ( ! $profile['label'] || ! $profile['url'] || ! $profile['icon'] ) {
				continue;
			}

			$attributes = '';
			// don't open hash tag, tel or mailto in new tab.
			if ( false !== strpos( $profile['url'], 'http' ) ) {
				$attributes .= ' rel="noopener"';
				$attributes .= ' target="_blank"';
			}
			/** @noinspection HtmlUnknownTarget */
			/** @noinspection HtmlUnknownAttribute */
			printf(
				'<li><a data-bs-toggle="tooltip" data-bs-placement="bottom" title="%1$s" data-original-title="%1$s" class="social social-%5$s" href="%2$s" %4$s><i class="%3$s" aria-hidden="false"></i><span class="sr-only">%1$s</span></a></li>',
				esc_attr( $profile['label'] ),
				esc_url( $profile['url'] ),
				esc_attr( $profile['icon'] ),
				esc_attr( $attributes ),
				esc_attr( strtolower( $profile['label'] ) )
			);
		}
		?></ul><?php
	}
}

if ( ! function_exists( 'springoo_mobile_icon' ) ) {
	/**
	 * Mobile Menu Icon.
	 *
	 * @return void
	 */
	function springoo_mobile_icon() {
		$mobile_icon_grid = springoo_get_mod( 'layout_header_menu_mobileshow' );

		switch ( $mobile_icon_grid ) {
			case 'sm':
				$mobile_icon_grid = ' d-block d-md-none';
				break;
			case 'md':
				$mobile_icon_grid = ' d-block d-lg-none';
				break;
			case 'lg':
				$mobile_icon_grid = ' d-block d-xl-none';
				break;
			case 'xl':
				$mobile_icon_grid = ' d-block';
				break;
			default:
				$mobile_icon_grid = ' d-block d-xl-none';
		}
		?>
		<a href="#" id="springoo-mobile-toggle" class="<?php echo esc_attr( $mobile_icon_grid ); ?>" role="button" aria-controls="navigation-mobile">
			<i class="si si-thin-menu" aria-hidden="true"></i>
			<span class="sr-only"><?php esc_html_e( 'Mobile Menu', 'springoo' ); ?></span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'springoo_get_mobile_menu' ) ) {
	/**
	 * Get Mobile Menu
	 *
	 * @return void
	 */
	function springoo_get_mobile_menu() {
		?>

		<div class="tab-content" id="menuTabContent">
			<div class="tab-pane fade show active" id="primary-tab-pane" role="tabpanel" aria-labelledby="primary-tab" tabindex="0">
				<?php
				if ( has_nav_menu( 'mobile' ) ) {
					wp_nav_menu( array( 'theme_location' => 'mobile' ) );
				} else {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'mobile'         => true,
						)
					);
				}
				?>
			</div>

		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_mobile_account' ) ) {
	function springoo_mobile_account() {
		$login_url = ( class_exists( 'woocommerce' ) ) ? get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) : ( ( is_user_logged_in() ) ? get_dashboard_url() : wp_login_url() );

		?>
		<div class="springoo-mobile-menu-account ">
			<?php if ( is_user_logged_in() ) { ?>
				<a href="<?php echo esc_url( $login_url ); ?>" class="springoo-button"><?php echo esc_html__( 'My Account', 'springoo' ); ?></a>
			<?php } else {
				if ( ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) && class_exists( 'woocommerce' ) ) || '1' === get_option( 'users_can_register' ) ) { ?>
					<a href="<?php echo esc_url( $login_url ); ?>" class="springoo-button dark"><?php echo esc_html__( 'Sign Up', 'springoo' ); ?></a>
				<?php } ?>
				<a href="<?php echo esc_url( $login_url ); ?>" class="springoo-button"><?php echo esc_html__( 'Log In', 'springoo' ); ?></a>
			<?php } ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_header_user_account_info' ) ) {
	/**
	 * Right Nav Panel.
	 *
	 * @return void
	 */
	function springoo_header_user_account_info() {
		$stat  = is_user_logged_in();
		$label = $stat ? __( 'My Account', 'springoo' ) : __( 'Sign Up / Log In', 'springoo' );
		$title = $stat ? __( 'My Account', 'springoo' ) : __( 'Login', 'springoo' );
		/** @noinspection HtmlUnknownTarget */
		printf(
			'<a class="springoo-signup hidden-xs" href="%s" title="%s"> <i class="ti-user" aria-hidden="true"></i> %s</a>',
			esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ), // @phpstan-ignore-line
			esc_attr( $title ),
			esc_html( $label )
		);
	}
}

if ( ! function_exists( 'springoo_wp_page_menu' ) ) {

	/**
	 * Display or retrieve list of pages with optional home link.
	 *
	 * This function is copied from WP wp_page_menu function to add an extra class as
	 * the default WP function has no inbuilt way to achieve this without using hacks
	 *
	 * @param array $args args.
	 *
	 * @return string html menu
	 */
	function springoo_wp_page_menu( $args = array() ) {
		$defaults = array(
			'sort_column' => 'menu_order, post_title',
			'menu_class'  => 'menu',
			'echo'        => true,
			'link_before' => '',
			'link_after'  => '',
		);
		$args     = wp_parse_args( $args, $defaults );

		/**
		 * Filter the arguments used to generate a page-based menu.
		 *
		 * @param array $args An array of page menu arguments.
		 *
		 * @see wp_page_menu()
		 */
		$args = apply_filters( 'wp_page_menu_args', $args ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound

		$menu = '';

		$list_args = $args;

		// Show Home in the menu.
		if ( ! empty( $args['show_home'] ) ) {
			if ( true === $args['show_home'] || '1' === $args['show_home'] || 1 === $args['show_home'] ) {
				$text = __( 'Home', 'springoo' );
			} else {
				$text = $args['show_home'];
			}
			$class = '';
			if ( is_front_page() && ! is_paged() ) {
				$class = 'class="current_page_item"';
			}
			$menu .= '<li ' . $class . '><a href="' . home_url( '/' ) . '">' . $args['link_before'] . $text . $args['link_after'] . '</a></li>';

			// If the front page is a page, add it to the exclude list.
			if ( 'page' === get_option( 'show_on_front' ) ) {
				if ( ! empty( $list_args['exclude'] ) ) {
					$list_args['exclude'] .= ',';
				} else {
					$list_args['exclude'] = ''; // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				}
				$list_args['exclude'] .= get_option( 'page_on_front' );
			}
		}

		$list_args['echo']     = false;
		$list_args['depth']    = 1;
		$list_args['title_li'] = '';
		$menu                 .= str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages( $list_args ) ); // @phpstan-ignore-line

		if ( $menu ) {
			$menu = '<ul class="nav navbar-nav">' . $menu . '</ul>';
		}

		$menu = '<div class="' . esc_attr( $args['menu_class'] ) . '">' . $menu . "</div>\n";

		/**
		 * Filter the HTML output of a page-based menu.
		 *
		 * @param string $menu The HTML output.
		 * @param array $args An array of arguments.
		 *
		 * @see wp_page_menu()
		 */
		$menu = apply_filters( 'wp_page_menu', $menu, $args ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound

		if ( $args['echo'] ) {
			echo wp_kses_post( $menu ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		return $menu;
	}
}

if ( class_exists( 'WooCommerce' ) ) {
	/**
	 * CPT notice.
	 *
	 * @return void
	 */
	function springoo_show_cpt_archive_notice() {
		global $post;
		$shop_page_id = wc_get_page_id( 'shop' );
		if ( $post && absint( $shop_page_id ) === absint( $post->ID ) ) {
			?>
			<div class="notice notice-warning">
				<p><?php esc_html_e( 'If you are in shop page which is selected as WooCommerce archive page then page option will not work. You have to select settings from Customizer', 'springoo' ); ?></p>
			</div>
			<?php
		}
	}

	add_action( 'admin_notices', 'springoo_show_cpt_archive_notice', 100 );
}

if ( ! function_exists( 'springoo_wpcf7_form_class_attr' ) ) {
	/**
	 * Contact Form 7 wpcf7_form_class_attr callback
	 *
	 * @param $class
	 *
	 * @return string
	 */
	function springoo_wpcf7_form_class_attr( $class ) {
		$class .= ' springoo-' . springoo_get_mod( 'layout_wpcf7_columns' );

		$inline_acceptance_btn = springoo_get_mod( 'layout_wpcf7_inline_acceptance_btn' );

		if ( $inline_acceptance_btn ) {
			$class .= ' springoo-inline-acceptance-btn';
		}

		return $class;
	}

	add_filter( 'wpcf7_form_class_attr', 'springoo_wpcf7_form_class_attr', 10, 1 );
}

/**
 * Get Layout for a view.
 *
 * @param string $view View.
 *
 * @return string
 */
function springoo_get_content_layout( $view = '' ) {
	if ( '' === $view ) {
		$view = springoo_get_current_screen();
	}

	$layout = wp_cache_get( 'layout_' . $view . '_style' );

	if ( false === $layout ) {
		$layout = springoo_get_mod( 'layout_' . $view . '_style' );
		wp_cache_set( 'layout_' . $view . '_style', $layout );
	}

	return apply_filters( 'springoo_content_layout', $layout, $view );
}

/**
 * Get post format content class for layout.
 *
 * @param string $layout
 * @param string $class
 *
 * @return void
 */
function springoo_get_post_format_classes( $layout = 'normal', $class = '' ) {

	$map = [ 'grid', 'normal', 'medium' ];

	if ( ! in_array( $layout, $map, true ) ) {
		$layout = 'normal';
	}

	$format  = get_post_format();
	$classes = [
		'blog-' . $layout,
		'post-' . $format,
		'entry-' . $format . '-post-format',
		$format,
	];

	if ( 'medium' === $layout ) {
		$classes[] = 'medium-left';
	}

	if ( 'gallery' === $format ) {
		$classes[] = 'carousel';
		$classes[] = 'slide';
		$classes[] = 'blog-gallery-slider';
	}

	// Animations
	$classes[] = 'effect';
	$classes[] = 'slide-top';

	if ( ! empty( $class ) ) {
		if ( is_string( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}

		if ( is_array( $class ) ) {
			$classes = array_merge( $classes, $class );
		}
	}

	if ( ! is_array( $class ) ) {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	/**
	 * Filters the list of CSS post-format class names for the current post in the loop.
	 *
	 * @param string[] $classes An array of body class names.
	 * @param string[] $class An array of additional class names added to the body.
	 */
	$classes = apply_filters( 'springoo_post_format_class', $classes, $class );

	$classes = array_map( 'esc_attr', $classes );

	echo 'class="' . esc_attr( implode( ' ', array_unique( $classes ) ) ) . '"';
}

/**
 * Render oEmbed content.
 *
 * @param string $src Link to render.
 * @param bool $echo display or return content.
 *                       Default is true (display)
 *
 * @return void|string|false|true return embed html or false, true if displayed.
 *
 * @see WP_Embed::shortcode()
 * WP Embed uses current (global) post's id (meta) to cache the oEmbed api responses.
 */
function springoo_render_embedded( $src, $echo = true ) {
	global $wp_embed;

	if ( ! strpos( $src, '://' ) ) {
		$src = ( is_ssl() ? 'http' : 'https' ) . '://' . $src;
	}

	$src = esc_url_raw( $src );
	if ( ! $src ) {
		return;
	}

	$embedded = $wp_embed->run_shortcode( '[embed]' . $src . '[/embed]' );

	$embedded = apply_filters( 'springoo_render_embedded', $embedded, $src );

	if ( ! $echo ) {
		return $embedded;
	}

	if ( $embedded ) {
		echo wp_kses_post( $embedded );

		return true;
	}

	return false;
}

/**
 * Gets the first instance of a block in the content, and then break away.
 *
 * Scrapped from Twenty Twenty-One 1.0
 *
 * @param string $block_name The full block type name, or a partial match.
 *                                Example: `core/image`, `core-embed/*`.
 * @param string|null $content The content to search in. Use null for get_the_content().
 * @param int $instances How many instances of the block will be printed (max). Defaults to 1.
 *
 * @return bool Returns true if a block was located & printed, otherwise false.
 */
function springoo_get_first_instance_of_block( $block_name, $content = null, $instances = 1 ) {

	if ( ! $content ) {
		$content = get_the_content();
	}

	// Parse blocks in the content.
	$blocks = parse_blocks( $content );

	$instances_count = 0;
	$blocks_content  = '';

	// Loop blocks.
	foreach ( $blocks as $block ) {

		// Sanity check.
		if ( ! isset( $block['blockName'] ) ) {
			continue;
		}

		// Check if this the block matches the $block_name.
		// If the block ends with *, try to match the first portion.
		/** @noinspection PhpLanguageLevelInspection */
		if ( '*' === $block_name[- 1] ) {
			$is_matching_block = 0 === strpos( $block['blockName'], rtrim( $block_name, '*' ) );
		} else {
			$is_matching_block = $block_name === $block['blockName'];
		}

		if ( $is_matching_block ) {

			// Add the block HTML.
			$blocks_content .= render_block( $block );

			// Increment count.
			$instances_count ++;

			// Break the loop if the $instances count was reached.
			if ( $instances_count >= $instances ) {
				break;
			}
		}
	}

	if ( $blocks_content ) {
		return apply_filters( 'the_content', $blocks_content ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	return false;
}

/**
 * Print the first instance of a block in the content, and then break away.
 *
 * Scrapped from Twenty Twenty-One 1.0
 *
 * @param string $block_name The full block type name, or a partial match.
 *                                Example: `core/image`, `core-embed/*`.
 * @param string|null $content The content to search in. Use null for get_the_content().
 * @param int $instances How many instances of the block will be printed (max). Defaults to 1.
 *
 * @return void
 */
function springoo_print_first_instance_of_block( $block_name, $content = null, $instances = 1 ) {
	echo springoo_get_first_instance_of_block( $block_name, $content, $instances ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

if ( ! function_exists( 'springoo_header_top_left_menu' ) ) {
	/**
	 * Site header top menu.
	 *
	 * @return void
	 */
	function springoo_header_top_left_menu() {
		if ( has_nav_menu( 'header_top_left' ) ) {
			wp_nav_menu(
				array(
					'theme_location'  => 'header_top_left',
					'menu_class'      => 'header_top_nav_menu springoo-sf-menu',
					'container_class' => 'col-md-6 col-lg-4',
				)
			);
		}
	}
}

if ( ! function_exists( 'springoo_header_top_right_menu' ) ) {
	/**
	 * Site header top menu.
	 *
	 * @return void
	 */
	function springoo_header_top_right_menu() {
		?>
		<div class="col-md-6 col-lg-4 d-flex justify-content-end align-items-center springoo-header-top-right">
			<!-- header top email-->
			<?php if ( ! empty( springoo_get_mod( 'layout_header_top_email' ) ) ) { ?>
				<div class="springoo_header_top_email">
					<a href="<?php echo esc_url( 'mailto:' . springoo_get_mod( 'layout_header_top_email' ) ); ?>">
						<i class="si si-thin-envelope" aria-hidden="true"></i>
						<?php echo esc_html( springoo_get_mod( 'layout_header_top_email' ) ); ?>
					</a>
				</div>
				<?php
			} ?>

			<div class="springoo-header-lang-currency-wrapper">
			<?php
			if ( has_nav_menu( 'header_top_right' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'header_top_right',
						'menu_class'     => 'header_top_nav_menu menu-right springoo-sf-menu',
						'container'      => false,
					)
				);
			}
			//currency switcher
			if ( class_exists( 'WOOCS' ) ) {
				the_widget( 'WOOCS_SELECTOR' );
			}
			?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_header_top_discount_text' ) ) {
	/**
	 * Header top dicount text.
	 *
	 * @return void
	 */
	function springoo_header_top_discount_text() {

		if ( empty( springoo_get_mod( 'layout_header_top_discount' ) ) ) {
			return;
		}
		$text_alignment = 'text-center';
		if ( has_nav_menu( 'header_top_left' ) && ! has_nav_menu( 'header_top_right' ) ) {
			$text_alignment .= ' text-lg-end';
		} elseif ( ! has_nav_menu( 'header_top_left' ) && has_nav_menu( 'header_top_right' ) ) {
			$text_alignment .= ' text-lg-start';
		} elseif ( ! has_nav_menu( 'header_top_left' ) && ! has_nav_menu( 'header_top_right' ) ) {
			$text_alignment .= ' text-lg-start';
		} else {
			$text_alignment .= ' text-lg-center';
		}

		?>
		<div class="col-md-6 col-lg-4 springoo_header_top_discount <?php echo esc_attr( $text_alignment ); ?>">
			<?php echo wp_kses_post( springoo_get_mod( 'layout_header_top_discount' ) ); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_header_main_actions' ) ) {
	/**
	 * Site main header actions
	 *
	 * @return void
	 */
	function springoo_header_main_actions() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		?>
		<div class="springoo_header_main_actions d-flex align-items-center">
			<?php
			springoo_mini_search();
			if ( defined( 'YITH_WCWL' ) && 1 == springoo_get_mod( 'layout_header_wishlist' ) ) {
				yith_wcwl_get_items_count();
			}
			// Show mini-cart if WooCommerce Exist
			springoo_mini_cart();
			if ( 1 == springoo_get_mod( 'layout_header_login_user_icon' ) ) {
				if ( is_user_logged_in() ) { ?>
					<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="user">
						<i class="si-thin-user" aria-hidden="true"></i>
						<span class="title screen-reader-text"><?php _e( 'My Account', 'springoo' ); ?></span>
					</a>
				<?php } else { ?>
					<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="user">
						<i class="si-thin-user" aria-hidden="true"></i>
						<span class="title screen-reader-text"><?php _e( 'Sign In', 'springoo' ); ?></span>
					</a>
				<?php }
			} ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_vertical_menu' ) ) {
	/**
	 * Site main header actions
	 *
	 * @return void
	 */
	function springoo_vertical_menu() {
		?>
		<div class="vertical-navigation-wrap">
			<a href="#" id="vertical-menu-toggle" role="button" aria-controls="vertical-nav">
				<i class="si si-thin-menu" aria-hidden="true"></i>
				<span class="text"><?php esc_html_e( 'Browse All Categories ', 'springoo' ); ?></span>
			</a>

			<div id="vertical-nav" class="vertical-nav" role="navigation">
				<?php
				if ( has_nav_menu( 'vertical_menu' ) ) {
					wp_nav_menu( array( 'theme_location' => 'vertical_menu' ) );
				} else {
					?>
					<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" class="ayyash-sticky-item"><?php esc_html_e( 'No Vertical Menu Assigned , Please Add', 'springoo' ); ?></a>
				<?php } ?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_ic_sanitize_image' ) ) {
	/**
	 * Validation: image
	 * Control: text, WP_Customize_Image_Control
	 *
	 * @uses    wp_check_filetype()        https://developer.wordpress.org/reference/functions/wp_check_filetype/
	 * @uses    in_array()                http://php.net/manual/en/function.in-array.php
	 */
	function springoo_ic_sanitize_image( $file, $setting ) {
		$mimes = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'bmp'          => 'image/bmp',
			'tif|tiff'     => 'image/tiff',
			'ico'          => 'image/x-icon',
			'svg'          => 'image/svg+xml',
		);

		//check file type from file name
		$file_ext = wp_check_filetype( $file, $mimes );

		//if file has a valid mime type return it, otherwise return default
		return ( $file_ext['ext'] ? $file : $setting->default );
	}
}

if ( ! function_exists( 'springoo_blog_grid_column' ) ) {
	function springoo_blog_grid_column() {
		$blog_grid_column = springoo_get_mod( 'layout_blog_grid_column' );

		return apply_filters( 'blog_grid_column', $blog_grid_column );
	}
}

if ( ! function_exists( 'springoo_transparent_background' ) ) {

	/**
	 * Header Transparent background
	 *
	 * @return false|string|void
	 */
	function springoo_transparent_background() {
		$enable_background_transparency = springoo_get_mod( 'layout_header_transparency_positioning' );
		$header_transparent_cls         = 'transparent-header';

		$excluded_pages_list = springoo_get_mod( 'layout_header_transparency_exclude_pages' );
		$excluded_pages      = explode( ',', str_replace( ' ', '', $excluded_pages_list ) );

		// Enables transparent header for starter content
		if ( is_page_template( 'template-starter.php' ) ) {
			return $header_transparent_cls;
		}

		// Enable transparent header based on customizer settings
		if ( $enable_background_transparency ) {
			if ( is_front_page() && ! springoo_get_mod( 'layout_header_transparency_front_page' ) ) {
				return $header_transparent_cls;
			} elseif ( is_home() && ! springoo_get_mod( 'layout_header_transparency_disable_posts' ) ) {
				return $header_transparent_cls;
			} elseif ( is_page() && ! springoo_get_mod( 'layout_header_transparency_disable_pages' ) ) {
				return $header_transparent_cls;
			} elseif ( is_archive() && ! springoo_get_mod( 'layout_header_transparency_disable_archives' ) ) {
				return $header_transparent_cls;
			} elseif ( is_search() && ! springoo_get_mod( 'layout_header_transparency_disable_search' ) ) {
				return $header_transparent_cls;
			} elseif ( is_404() && ! springoo_get_mod( 'layout_header_transparency_disable_404' ) ) {
				return $header_transparent_cls;
			} elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() && ! springoo_get_mod( 'layout_header_transparency_disable_products' ) ) {
				return $header_transparent_cls;
			} elseif ( ! empty( $excluded_pages_list ) ) {
				if ( is_page( $excluded_pages ) ) {
					return $header_transparent_cls;
				}
			} else {
				return false;
			}
		}
	}
}

if ( ! function_exists( 'springoo_filter_attachment_image_attributes' ) ) {
	/**
	 * Filters the list of attachment image attributes.
	 *
	 * @param string[] $attr Array of attribute values for the image markup, keyed by attribute name.
	 *
	 * @return string[]
	 * @see wp_get_attachment_image()
	 */
	function springoo_filter_attachment_image_attributes( $attr ) {

		if ( ! springoo_maybe_lazyload_post_thumbnail() ) {
			return $attr;
		}

		// Data Src.
		$attr['data-src'] = $attr['src'];

		// Setting 1px as default placeholder gif image.
		$attr['src'] = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';

		/**
		 * @TODO Make a new image size springoo_lazy_preview with soft crop.
		 *       get the src for springoo_lazy_preview size & set as src for the img, and set some blur effect with css.
		 *       it also needs to be tested with loading="lazy" attribute.
		 */

		// Data Src Set.
		if ( isset( $attr['srcset'] ) ) {
			$attr['data-srcset'] = $attr['srcset'];
			unset( $attr['srcset'] );
		}

		// Lazy & no-js support classes.
		$attr['class'] .= ' lazy hide-if-no-js';

		// support for bootstrap image responsive class for wc loop.
		if ( ! empty( $attr['auto_size'] ) ) {
			unset( $attr['auto_size'] );
			$attr['class'] .= ' img-responsive';
		}

		return $attr;
	}
}

if ( ! function_exists( 'springoo_blog_search' ) ) {
	/**
	 * Exclude product from search result page
	 *
	 * @see https://github.com/WordPress/wordpress-develop/blob/8a6afdd52e572ded7aa350f0d24f40da799596e9/src/wp-includes/class-wp-query.php#L2526-L2534
	 * @var \WP_Query $wp_query
	 */

	function springoo_blog_search( $wp_query ) {

		if ( is_admin() || ! $wp_query->is_search() || ! $wp_query->is_main_query() ) {
			return;
		}

		$post_types = $wp_query->get( 'post_type' );

		if ( 'any' === $post_types || empty( $post_types ) ) {
			$in_search_post_types = get_post_types( array( 'exclude_from_search' => false ) );

			if ( empty( $in_search_post_types ) ) {
				return;
			}

			if ( isset( $in_search_post_types['product'] ) ) {
				unset( $in_search_post_types['product'] );
			}

			$wp_query->set( 'post_type', array_values( $in_search_post_types ) );
		}
	}
}

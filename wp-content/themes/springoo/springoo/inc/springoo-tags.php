<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some functionality here could be replaced by core features.
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! function_exists( 'springoo_get_custom_logo' ) ) {
	/**
	 * Returns a custom logo, linked to home unless the theme supports removing the link on the home page.
	 *
	 * @param int $blog_id Optional. ID of the blog in question. Default is the ID of the current blog.
	 *
	 * @return string Custom logo markup.
	 *@since 5.5.1 Disabled lazy-loading by default.
	 *
	 * @since 4.5.0
	 * @since 5.5.0 Added option to remove the link on the home page with `unlink-homepage-logo` theme support
	 *              for the `custom-logo` theme feature.
	 */
	function springoo_get_custom_logo( $blog_id = 0 ) {
		global $wp_filesystem;
		require_once ( ABSPATH . '/wp-admin/includes/file.php' ); //phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		WP_Filesystem();

		$html          = '';
		$switched_blog = false;

		if ( is_multisite() && ! empty( $blog_id ) && get_current_blog_id() !== (int) $blog_id ) {
			switch_to_blog( $blog_id );
			$switched_blog = true;
		}

		$custom_logo_id = get_theme_mod( 'custom_logo' );

		$unlink_homepage_logo = (bool) get_theme_support( 'custom-logo', 'unlink-homepage-logo' );

		// We have a logo. Logo is go.
		if ( $custom_logo_id ) {

			if ( 'image/svg+xml' === get_post_mime_type( $custom_logo_id ) ) {
				$file      = get_attached_file( $custom_logo_id );
				$svg_image = $wp_filesystem->get_contents( $file ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown

				if ( $unlink_homepage_logo && is_front_page() && ! is_paged() ) {
					// If on the home page, don't link the logo to home.
					$html = sprintf( '<div class="logo"><span class="custom-logo-link">%1$s</span></div>', $svg_image );
				} else {
					$aria_current = is_front_page() && ! is_paged() ? ' aria-current="page"' : '';

					$html = sprintf(
						'<div class="logo"><a href="%1$s" class="custom-logo-link" rel="home"%2$s>%3$s<span class="sr-only">%4$s</span></span></a></div>',
						esc_url( home_url( '/' ) ),
						$aria_current,
						$svg_image,
						get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : esc_html__( 'logo', 'springoo' )
					);
				}
			} else {
				$retina_logo_id   = get_theme_mod( 'retina_logo' );
				$custom_logo_attr = [
					'class'   => 'custom-logo normal',
					'loading' => false,
				];
				$retina_logo_attr = [
					'class'   => 'custom-logo retina',
					'loading' => false,
				];

				if ( $unlink_homepage_logo && is_front_page() && ! is_paged() ) {
					/*
					 * If on the home page, set the logo alt attribute to an empty string,
					 * as the image is decorative and doesn't need its purpose to be described.
					 */
					$custom_logo_attr['alt'] = '';
					$retina_logo_attr['alt'] = '';
				} else {
					/*
					 * If the logo alt attribute is empty, get the site title and explicitly pass it
					 * to the attributes used by wp_get_attachment_image().
					 */
					$image_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
					if ( empty( $image_alt ) ) {
						$custom_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
						$retina_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
					}
				}

				/**
				 * Filters the list of custom logo image attributes.
				 *
				 * @param array $custom_logo_attr Custom logo image attributes.
				 * @param int   $custom_logo_id   Custom logo attachment ID.
				 * @param int   $blog_id          ID of the blog to get the custom logo for.
				 *
				 *@since 5.5.0
				 */
				$custom_logo_attr = apply_filters( 'get_custom_logo_image_attributes', $custom_logo_attr, $custom_logo_id, $blog_id );
				$retina_logo_attr = apply_filters( 'get_retina_logo_image_attributes', $retina_logo_attr, $retina_logo_id, $blog_id );

				/*
				 * If the alt attribute is not empty, there's no need to explicitly pass it
				 * because wp_get_attachment_image() already adds the alt attribute.
				 */
				$image  = wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr );
				$retina = wp_get_attachment_image( $retina_logo_id, 'full', false, $retina_logo_attr );
				$image .= $retina ? $retina : wp_get_attachment_image( $custom_logo_id, 'full', false, $retina_logo_attr );

				if ( $unlink_homepage_logo && is_front_page() && ! is_paged() ) {
					// If on the home page, don't link the logo to home.
					$html = sprintf( '<div class="logo"><span class="custom-logo-link">%1$s</span></div>', $image );
				} else {
					$aria_current = is_front_page() && ! is_paged() ? ' aria-current="page"' : '';

					$html = sprintf(
						'<div class="logo"><a href="%1$s" class="custom-logo-link" rel="home"%2$s>%3$s</a></div>',
						esc_url( home_url( '/' ) ),
						$aria_current,
						$image
					);
				}
			}
		} elseif ( is_customize_preview() ) {
			// If no logo is set but we're in the Customizer, leave a placeholder (needed for the live preview).
			$html = sprintf(
				'<div class="logo" style="display:none;"><a href="%1$s" class="custom-logo-link"><img class="custom-logo" alt="" /></a></div>',
				esc_url( home_url( '/' ) )
			);
		}

		if ( $switched_blog ) {
			restore_current_blog();
		}
		/**
		 * Filters the custom logo output.
		 *
		 * @param string $html    Custom logo HTML output.
		 * @param int    $blog_id ID of the blog to get the custom logo for.
		 *
		 *@since 4.5.0
		 * @since 4.6.0 Added the `$blog_id` parameter.
		 */
		return apply_filters( 'get_custom_logo', $html, $blog_id );
	}
}

if ( ! function_exists( 'springoo_site_branding' ) ) :

	/**
	 * Site Branding (Logo)
	 *
	 * @return void
	 */
	function springoo_site_branding() {
		$logo         = springoo_get_custom_logo();
		$description  = get_bloginfo( 'description', 'display' );
		$blog_info    = get_bloginfo( 'name' );
		$hide_title   = springoo_get_mod( 'title_tagline_hide_title' );
		$header_class = ! $hide_title ? 'site-title' : 'screen-reader-text';
		$is_home      = ( is_front_page() || is_home() ) && ! is_page();

		?>
		<div class="site-branding">
			<?php if ( $logo ) {
				$kses_defaults = wp_kses_allowed_html( 'post' );

				$svg_args = array(
					'svg'              => array(
						'class'           => true,
						'aria-hidden'     => true,
						'aria-labelledby' => true,
						'role'            => true,
						'xmlns'           => true,
						'width'           => true,
						'height'          => true,
						'viewbox'         => true, // <= Must be lower case!
					),
					'title'            => array( 'title' => true ),
					'animate'          => array(
						'attributeName' => true,
						'values'        => true,
						'dur'           => true,
						'repeatCount'   => true,
					),
					'animateMotion'    => array(
						'path'        => true,
						'dur'         => true,
						'repeatCount' => true,
					),
					'animateTransform' => array(
						'attributeName' => true,
						'attributeType' => true,
						'type'          => true,
						'from'          => true,
						'to'            => true,
						'dur'           => true,
						'repeatCount'   => true,
					),
					'path'             => array(
						'd'      => true,
						'fill'   => true,
						'stroke' => true,
					),
					'circle'           => array(
						'cx' => true,
						'c'  => true,
						'r'  => true,
					),
					'clipPath'         => array(
						'id' => true,
					),
					'defs'             => true,
					'desc'             => true,
					'ellipse'          => array(
						'cx' => true,
						'cy' => true,
						'rx' => true,
						'ry' => true,
					),
					'g'                => array(
						'fill'         => true,
						'stroke'       => true,
						'stroke-width' => true,
					),
					'line'             => array(
						'x1'     => true,
						'y1'     => true,
						'x2'     => true,
						'y2'     => true,
						'stroke' => true,
					),
					'polygon'          => array(
						'points' => true,
						'fill'   => true,
						'stroke' => true,
					),
					'polyline'         => array(
						'points' => true,
						'fill'   => true,
						'stroke' => true,
					),
					'rect'             => array(
						'x'      => true,
						'y'      => true,
						'width'  => true,
						'height' => true,
						'rx'     => true,
						'ry'     => true,
						'fill'   => true,
					),
					'lineargradient'   => array(
						'id'            => true,
						'x1'            => true,
						'y1'            => true,
						'x2'            => true,
						'y2'            => true,
						'gradientunits' => true,
					),
					'stop'             => array(
						'offset'     => true,
						'stop-color' => true,
					),
				);

				$allowed_tags = array_merge( $kses_defaults, $svg_args );
				echo wp_kses( $logo, $allowed_tags ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php }
			if ( $blog_info ) { ?>
				<?php if ( is_front_page() && ! is_paged() ) { ?>
					<h1 class="<?php echo esc_attr( $header_class ); ?>"><?php echo esc_html( $blog_info ); ?></h1>
				<?php } elseif ( is_front_page() || is_home() ) { ?>
					<h1 class="<?php echo esc_attr( $header_class ); ?>">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $blog_info ); ?></a>
					</h1>
				<?php } else { ?>
					<p class="<?php echo esc_attr( $header_class ); ?> faux-heading">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $blog_info ); ?></a>
					</p>
				<?php } ?>
			<?php }
			if ( $description && ! springoo_get_mod( 'title_tagline_hide_tagline' ) ) { ?>
				<span class="site-description"><?php echo esc_html( $description ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<?php } ?>
		</div><!-- .site-branding -->
		<?php
	}

endif;

if ( ! function_exists( 'springoo_post_reading_duration' ) ) {
	/**
	 * Springoo Post reading duration
	 *
	 * @return void
	 */
	function springoo_post_reading_duration() {
		$text        = trim( wp_strip_all_tags( get_the_content() ) );
		$word_number = substr_count("$text ", ' ');
		$seconds     = $word_number / 3.9;
		$minutes     = (int) round($seconds / 60);

		/* translators: %s: Reading Duration in Minutes. */
		$duration = sprintf( _nx( '%s Min Read', '%s Mins Read',  $minutes, 'Post Reading Duration', 'springoo' ), number_format_i18n( $minutes ) );
		printf('<p class="springoo-post-reading-duration">%s</p>', esc_html( $duration ) );
	}
}


if ( ! function_exists( 'springoo_post_meta' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time, author & comments.
	 *
	 * @return void
	 */
	function springoo_post_meta( $post_id = '' ) {
		?>
		<a class="post-date-link" href="<?php the_permalink(); ?>">
			<time class="post-date updated"><?php the_time( get_option( 'date_format' ) ); ?></time>
		</a>
		<?php

		$post_title = get_the_title( $post_id );
		$number     = get_comments_number( $post_id );
		comments_popup_link(
			/* translators: %s: Post title. */
			sprintf( __( 'No Comments<span class="sr-only"> on “%s”</span>', 'springoo' ), $post_title ),
			/* translators: %s: Post title. */
			sprintf( __( '1 Comment<span class="sr-only"> on “%s”</span>', 'springoo' ), $post_title ),
			/* translators: %s: Post title. */
			sprintf( __( '%% Comments<span class="screen-reader-text"> on “%s”</span>', 'springoo' ), $post_title ),
			'post-comment-stat'
		);
	}
endif;

if ( ! function_exists( 'springoo_posted_on' ) ) :


	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @return void
	 */
	function springoo_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		// @phpstan-ignore-next-line
		$time_string = sprintf( $time_string, esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ), esc_attr( get_the_modified_date( 'c' ) ), esc_html( get_the_modified_date() ) );
		?>
		<span class="posted-on">
			<?php
			printf(
				/* translators: %s: Post Created Time (with permalink to the post) */
				esc_html_x( 'Posted on %s', 'post date', 'springoo' ),
				// @phpstan-ignore-next-line
				'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			?>
		</span>
		<span class="byline">
			<?php
			printf(
				/* translators: %s: Post Author Name (linked to author page). */
				esc_html_x( 'by %s', 'post author', 'springoo' ),
				// @phpstan-ignore-next-line
				'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
			);
			?>
		</span>
		<?php

	}
endif;

if ( ! function_exists( 'springoo_post_read_more' ) ) :


	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 *
	 * @return void
	 */
	function springoo_post_read_more() {
		?>
		<div class="read-more">
			<a href="<?php the_permalink(); ?>">
				<span class="sr-only"><?php the_title(); ?></span>
				<span><?php esc_html_e( 'Read More', 'springoo' ); ?></span>
			</a>
		</div>
		<?php

	}
endif;

if ( ! function_exists( 'springoo_categorized_blog' ) ) :
	/**
	 * Returns true if a blog has more than 1 category.
	 *
	 * @return bool
	 */
	function springoo_categorized_blog() {
		$all_the_cool_cats = get_transient( 'springoo_category_count' );
		if ( false === $all_the_cool_cats ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories(
				[
					'fields'     => 'ids',
					'hide_empty' => 1, // We only need to know if there is more than one category.
					'number'     => 2,
				]
			);

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'springoo_category_count', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so springoo_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so springoo_categorized_blog should return false.
			return false;
		}
	}
endif;

if ( ! function_exists( 'springoo_post_taxonomy' ) ) :

	/**
	 * Shim for `springoo_post_taxonomy()`.
	 *
	 * Display category, tag, or term description.
	 *
	 * @param string $view view.
	 *
	 * @return void
	 */
	function springoo_post_taxonomy( $view ) {
		// Tags.
		springoo_post_tags( $view );

		// Categories.
		springoo_post_categories( $view );
	}
endif;

if ( ! function_exists( 'springoo_post_tags' ) ) :

	/**
	 * Shim for `springoo_post_tags()`.
	 *
	 * Display tags
	 *
	 * @param string $view view.
	 *
	 * @return void
	 */
	function springoo_post_tags() {
		$tag_list = get_the_tag_list('', ',');
		if ( ! $tag_list ) {
			return;
		}
		echo sprintf('<div class="post-tags-wrap">%s<div class="post-tags">%s</div></div>', '<i class="si-thin-tag" aria-hidden="true"></i>', $tag_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped*/
	}
endif;

if ( ! function_exists( 'springoo_post_categories' ) ) :

	/**
	 * Shim for `springoo_post_categories()`.
	 *
	 * Display categories.
	 *
	 * @param string $view view.
	 *
	 * @return void
	 */
	function springoo_post_categories() {
		global $post;
		$cat_list = get_the_category_list( ',&nbsp' );

		if ( class_exists( 'WooCommerce' ) && 'product' == $post->post_type ) {
			$prod_cat = wc_get_product_category_list( get_the_id(), ',&nbsp');

			if ( ! $prod_cat ) {
				return;
			}
			echo sprintf('<div class="post-categories-wrap">%s%s</div>', '<i class="si si-thin-category"></i>',  $prod_cat); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped*/
		} else {
			if ( ! $cat_list ) {
				return;
			}
			echo sprintf('<div class="post-categories-wrap">%s%s</div>', '<i class="si si-thin-category"></i>', $cat_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped*/
		}
	}
endif;

if ( ! function_exists( 'springoo_post_single_navigation' ) ) :

	/**
	 * Shim for `the_archive_description()`.
	 *
	 * Display category, tag, or term description.
	 *
	 * @return void
	 */
	function springoo_post_single_navigation() {
		?>
		<div class="springoo-single-post-pagination d-flex flex-wrap justify-content-between">
			<div class="previous"><?php previous_post_link( '%link', '<div class="text"><i class="si si-thin-arrow-left" aria-hidden="true"></i>' . esc_html__( 'Previous Post', 'springoo' ) . '</div><div class="pagination-post-title">%title</div>' ); ?></div>
			<div class="next"><?php next_post_link( '%link', '<div class="text">' . esc_html__( 'Next Post', 'springoo' ) . '<i class="si si-thin-arrow-right" aria-hidden="true"></i></div><div class="pagination-post-title">%title</div>' ); ?></div>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'springoo_post_author_info' ) ) :


	/**
	 * Shim for `springoo_post_author_info()`.
	 *
	 * Display category, tag, or term description.
	 *
	 * @return void
	 */
	function springoo_post_author_info() {

		if ( ! post_type_supports( get_post_type(), 'author' ) ) { // @phpstan-ignore-line
			return;
		}

		$has_avatar = get_option( 'show_avatars' );

		$classes = 'springoo-author-bio';
		if ( $has_avatar ) {
			$classes .= ' show-avatars';
		}

		if ( is_author() ) {
			$classes .= ' archive-author';
			$size     = apply_filters( 'springoo_author_archive_avatar_size', 200 );
		} else {
			$size = apply_filters( 'springoo_author_bio_avatar_size', 90 );
		}

		?>
		<div id="about-author" class="<?php echo esc_attr( $classes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
			<?php if ( $has_avatar ) { ?>
			<div class="avatar-wrap"><?php echo get_avatar( get_the_author_meta( 'email' ), $size, '', get_the_author() ); // @phpstan-ignore-line ?></div>
			<?php } ?>
			<div class="author-content">

				<?php if ( is_author() ) { ?>
					<h1 class="author-title"><?php the_author(); ?></h1>
				<?php } else { ?>
					<h5 class="author-title"><?php the_author_posts_link(); ?></h5>
				<?php } ?>

				<div class="author-description author-bio"><?php the_author_meta( 'description' ); ?></div>
				<?php
					$author_id             = get_the_author_meta('ID');
					$author_fb_link        = get_the_author_meta('springoo_facebook_url', $author_id );
					$author_insta_link     = get_the_author_meta('springoo_instagram_url', $author_id );
					$author_twitter_link   = get_the_author_meta('springoo_twitter_url', $author_id );
					$author_pinterest_link = get_the_author_meta('springoo_pinterest_url', $author_id );

				if ( ! empty ( $author_fb_link ) || ! empty( $author_insta_link ) || ! empty( $author_twitter_link ) || ! empty( $author_pinterest_link ) ) { ?>
					<div class="author-social-profile">
					<?php
					if ( ! empty ( $author_fb_link ) ) {
						printf( '<a data-bs-toggle="tooltip" data-bs-placement="bottom" title="%1$s" data-original-title="%1$s" class="social-icon" href="%2$s"><i class="si si-thin-facebook" aria-hidden="false"></i><span class="sr-only">%1$s</span></a>', esc_attr__('Facebook', 'springoo'), esc_url( $author_fb_link ) );
					}

					if ( ! empty( $author_insta_link ) ) {
						printf( '<a data-bs-toggle="tooltip" data-bs-placement="bottom" title="%1$s" data-original-title="%1$s" class="social-icon" href="%2$s"><i class="si si-thin-instagram" aria-hidden="false"></i><span class="sr-only">%1$s</span></a>', esc_attr__('Instagram', 'springoo'), esc_url( $author_insta_link ) );
					}

					if ( ! empty( $author_twitter_link ) ) {
						printf( '<a data-bs-toggle="tooltip" data-bs-placement="bottom" title="%1$s" data-original-title="%1$s" class="social-icon" href="%2$s"><i class="si si-thin-twitter" aria-hidden="false"></i><span class="sr-only">%1$s</span></a>', esc_attr__('Twitter', 'springoo'), esc_url( $author_twitter_link ) );
					}

					if ( ! empty( $author_pinterest_link ) ) {
						printf( '<a data-bs-toggle="tooltip" data-bs-placement="bottom" title="%1$s" data-original-title="%1$s" class="social-icon" href="%2$s"><i class="si si-thin-pinterest" aria-hidden="false"></i><span class="sr-only">%1$s</span></a>', esc_attr__('Pinterest', 'springoo'), esc_url( $author_pinterest_link ) );
					}
					?>
				</div><!-- author-social -->
				<?php } ?>
			</div>
		</div>
		<?php
		if ( is_author() ) {
			?>
			<div class="row">
				<div class="col-12">
					<p class="author_post_title">
					<?php
					/* translators: %s Author Name */
					printf( esc_html__( "%s's Latest posts", 'springoo' ), get_the_author() );
					?>
					</p>
				</div>
			</div>
			<?php
		}
	}

endif;

if ( ! function_exists( 'springoo_try_sidebar' ) ) :


	/**
	 * Displays sidebar if the @position matches the @view sidebar position
	 *
	 * @param string $view view.
	 * @param string $position position.
	 *
	 * @return void
	 */
	function springoo_try_sidebar( $view, $position ) {
		/** @var string $single_sidebar */
		$single_sidebar  = springoo_get_post_layout_options( 'sidebar' );
		$sidebar_grid    = springoo_get_mod( 'layout_global_sidebar_grid' );
		$_position       = springoo_get_sidebar_position( $view );
		$sidebar_classes = 'widget-area main-sidebar sidebar-' . $view . ' col-md-' . $sidebar_grid . ' sidebar-' . $_position;
		$sidebar_classes = apply_filters( 'springoo_sidebar_classes', $sidebar_classes, $position, $view );

		if ( $_position === $position ) {
			if ( 'default' !== $single_sidebar ) {
				?>
				<div id="secondary" class="<?php echo esc_attr( $sidebar_classes ); ?>" role="complementary">
					<?php dynamic_sidebar( $single_sidebar ); ?>
				</div><!-- #secondary -->
				<?php
			} elseif ( class_exists( 'WooCommerce' ) && is_post_type_archive( 'product' ) ) {
				$woocommerce_shop_archive_sidebar = springoo_get_mod( 'woocommerce_shop_archive_sidebar' );
				if ( springoo_is_not_default_val( $woocommerce_shop_archive_sidebar ) ) {
					?>
					<div id="secondary" class="<?php echo esc_attr( $sidebar_classes ); ?>" role="complementary">
						<?php dynamic_sidebar( $woocommerce_shop_archive_sidebar ); ?>
					</div><!-- #secondary -->
					<?php
				} else {
					get_sidebar();
				}
			} elseif ( class_exists( 'WooCommerce' ) && is_product() ) {
				$woocommerce_single_sidebar = springoo_get_mod( 'woocommerce_single_sidebar' );
				if ( springoo_is_not_default_val( $woocommerce_single_sidebar ) ) {
					?>
					<div id="secondary" class="<?php echo esc_attr( $sidebar_classes ); ?>" role="complementary">
						<?php dynamic_sidebar( $woocommerce_single_sidebar ); ?>
					</div><!-- #secondary -->
					<?php
				} elseif ( springoo_is_not_default_val( $single_sidebar ) ) {
					?>
					<div id="secondary" class="<?php echo esc_attr( $sidebar_classes ); ?>" role="complementary">
						<?php dynamic_sidebar( $single_sidebar ); ?>
					</div><!-- #secondary -->
					<?php
				} else {
					get_sidebar();
				}
			} elseif ( class_exists( 'WooCommerce' ) && is_cart() ) {
				$woocommerce_cart_sidebar = springoo_get_mod( 'woocommerce_cart_sidebar' );
				if ( springoo_is_not_default_val( $woocommerce_cart_sidebar ) ) {
					?>
					<div id="secondary" class="<?php echo esc_attr( $sidebar_classes ); ?>" role="complementary">
						<?php dynamic_sidebar( $woocommerce_cart_sidebar ); ?>
					</div><!-- #secondary -->
					<?php
				} elseif ( springoo_is_not_default_val( $single_sidebar ) ) {
					?>
					<div id="secondary" class="<?php echo esc_attr( $sidebar_classes ); ?>" role="complementary">
						<?php dynamic_sidebar( $single_sidebar ); ?>
					</div><!-- #secondary -->
					<?php
				} else {
					get_sidebar();
				}
			} elseif ( class_exists( 'WooCommerce' ) && is_checkout() ) {
				$woocommerce_checkout_sidebar = springoo_get_mod( 'woocommerce_checkout_sidebar' );
				if ( springoo_is_not_default_val( $woocommerce_checkout_sidebar ) ) {
					?>
					<div id="secondary" class="<?php echo esc_attr( $sidebar_classes ); ?>" role="complementary">
						<?php dynamic_sidebar( $woocommerce_checkout_sidebar ); ?>
					</div><!-- #secondary -->
					<?php
				} elseif ( springoo_is_not_default_val( $single_sidebar ) ) {
					?>
					<div id="secondary" class="<?php echo esc_attr( $sidebar_classes ); ?>" role="complementary">
						<?php dynamic_sidebar( $single_sidebar ); ?>
					</div><!-- #secondary -->
					<?php
				} else {
					get_sidebar();
				}
			} elseif ( class_exists( 'WooCommerce' ) && is_account_page() ) {
				$woocommerce_myaccount_sidebar = springoo_get_mod( 'woocommerce_myaccount_sidebar' );
				if ( springoo_is_not_default_val( $woocommerce_myaccount_sidebar ) ) {
					?>
					<div id="secondary" class="<?php echo esc_attr( $sidebar_classes ); ?>" role="complementary">
						<?php dynamic_sidebar( $woocommerce_myaccount_sidebar ); ?>
					</div><!-- #secondary -->
					<?php
				} elseif ( springoo_is_not_default_val( $single_sidebar ) ) {
					?>
					<div id="secondary" class="<?php echo esc_attr( $sidebar_classes ); ?>" role="complementary">
						<?php dynamic_sidebar( $single_sidebar ); ?>
					</div><!-- #secondary -->
					<?php
				} else {
					get_sidebar();
				}
			} elseif ( class_exists( 'WooCommerce' ) && is_product_category() ) {
				$woocommerce_product_catalog_sidebar = springoo_get_mod( 'woocommerce_product_catalog_sidebar' );
				if ( springoo_is_not_default_val( $woocommerce_product_catalog_sidebar ) ) {
					?>
					<div id="secondary" class="<?php echo esc_attr( $sidebar_classes ); ?>" role="complementary">
						<?php dynamic_sidebar( $woocommerce_product_catalog_sidebar ); ?>
					</div><!-- #secondary -->
					<?php
				} elseif ( springoo_is_not_default_val( $single_sidebar ) ) {
					?>
					<div id="secondary" class="<?php echo esc_attr( $sidebar_classes ); ?>" role="complementary">
						<?php dynamic_sidebar( $single_sidebar ); ?>
					</div><!-- #secondary -->
					<?php
				} else {
					get_sidebar();
				}
			} else {
				// Default ..
				get_sidebar();
			}
		} else {
			// Remove WC Sidebars if requested position not matched with stored settings.
			if ( class_exists( 'WooCommerce', false ) ) {
				if ( is_post_type_archive( 'product' ) || is_product_taxonomy() || is_account_page() || is_checkout() || is_cart() ) {
					remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );
				} elseif ( is_product() ) {
					remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );
				}
			}
		}
	}

endif;

if ( ! function_exists( 'springoo_get_content_class' ) ) {
	/**
	 * Displays the class names for the main content element.
	 *
	 * @param string|string[] $class Space-separated string or array of class names to add to the class list.
	 *
	 * @return void
	 */
	function springoo_get_content_class( $class = '' ) {

		$classes = array(
			'springoo-row',
			'site-content',
		);

		$section_height = (int) springoo_get_post_layout_options( 'section_height' );

		if ( 1 === $section_height ) {
			$classes[] = 'pt-0';
		} elseif ( 2 === $section_height ) {
			$classes[] = 'pb-0';
		} elseif ( 3 === $section_height ) {
			$classes[] = 'pt-0 pb-0';
		}

		list( $classes, $class ) = springoo_class_attribute_helper( $classes, $class );

		/**
		 * Filters the list of CSS content wrapper class names for the current post or page.
		 *
		 * @param string[] $classes An array of body class names.
		 * @param string[] $class   An array of additional class names added to the body.
		 */
		$classes = apply_filters( 'springoo_content_class', $classes, $class );

		$classes = array_unique( $classes );

		// Separates class names with a single space, collates class names for body element.
		echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
	}
}

if ( ! function_exists( 'springoo_get_footer_container_class' ) ) {
	/**
	 * Displays the class names for the main content element.
	 *
	 * @param string|string[] $class Space-separated string or array of class names to add to the class list.
	 *
	 * @return void
	 */
	function springoo_get_footer_container_class( $class = '' ) {

		$classes = array( springoo_get_mod( 'layout_global_content_layout' ) );

		list( $classes, $class ) = springoo_class_attribute_helper( $classes, $class );

		/**
		 * Filters the list of CSS footer container wrapper class names for the current post or page.
		 *
		 * @param string[] $classes An array of body class names.
		 * @param string[] $class   An array of additional class names added to the body.
		 */
		$classes = apply_filters( 'springoo_footer_container_class', $classes, $class );

		$classes = array_unique( $classes );

		// Separates class names with a single space, collates class names for body element.
		echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
	}
}

if ( ! function_exists( 'springoo_main_class' ) ) :

	/**
	 * Prints the appropriate Bootstrap class for the main content area
	 *
	 * @param string|string[] $class Space-separated string or array of class names to add to the class list.
	 *
	 * @return void
	 */
	function springoo_main_class( $class = '' ) {

		$content_grid = springoo_get_mod( 'layout_global_content_grid' );
		$position     = springoo_get_sidebar_position();
		$classes      = [ 'content-area' ];

		if ( function_exists( 'is_product_category' ) && is_product_category() ) {
			$classes[] = 'col-md-12';
		} elseif ( function_exists( 'is_product_tag' ) && is_product_tag() ) {
			$classes[] = 'col-md-12';
		} else {
			if ( 'none' === $position ) {
				$classes[] = 'col-md-12';
			} else {

				if ( $content_grid < 1 || $content_grid > 12 ) {
					$content_grid = springoo_get_default( 'layout_global_content_grid' );
				}

				$classes[] = 'col-md-' . absint( $content_grid );
			}
		}

		list( $classes, $class ) = springoo_class_attribute_helper( $classes, $class );

		/**
		 * Filters the list of CSS main (primary div) names for the current post or page.
		 *
		 * @param string[] $classes An array of body class names.
		 * @param string[] $class   An array of additional class names added to the body.
		 */
		$classes = apply_filters( 'springoo_main_class', $classes, $class );

		$classes = array_unique( $classes );

		// Separates class names with a single space, collates class names for body element.
		echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
	}

endif;

if ( ! function_exists( 'springoo_title_bar' ) ) :

	/**
	 * Prints the Title Bar Container
	 *
	 * @param string $view view.
	 *
	 * @return void
	 */
	function springoo_title_bar( $view ) {

		// Removes title bar from starter content file
		if ( is_page_template( 'template-starter.php' ) ) {
			return;
		}

		$title_bar        = (int) springoo_get_mod( 'layout_' . $view . '_title-bar' );
		$show_title       = (int) springoo_get_mod( 'layout_enable_title_breadcrumb' );
		$single_title_bar = springoo_get_post_layout_options( 'titlebar' );
		$container_class  = apply_filters( 'springoo_content_container_class', springoo_get_mod( 'layout_global_content_layout' ) );
		$has_wc           = class_exists( 'WooCommerce', false );


		if ( $has_wc && is_post_type_archive( 'product' ) ) {
			$maye_show_titlebar = springoo_get_mod( 'woocommerce_shop_archive_title-bar' );
		} elseif ( $has_wc && is_cart() ) {
			$maye_show_titlebar = ( 1 === (int) springoo_get_mod( 'woocommerce_cart_title-bar' ) && ( 'default' === $single_title_bar ) || 'enable' === $single_title_bar );
		} elseif ( $has_wc && is_checkout() ) {
			$maye_show_titlebar = ( 1 === (int) springoo_get_mod( 'woocommerce_checkout_title-bar' ) && ( 'default' === $single_title_bar ) || 'enable' === $single_title_bar );
		} elseif ( $has_wc && is_account_page() ) {
			$maye_show_titlebar = ( 1 === (int) springoo_get_mod( 'woocommerce_myaccount_title-bar' ) && ( 'default' === $single_title_bar ) || 'enable' === $single_title_bar );
		} elseif ( $has_wc && is_product_category() ) {
			$maye_show_titlebar = (int) springoo_get_mod( 'woocommerce_product_catalog_title-bar' ) === 1;
		} elseif ( is_front_page() == true ) {
			$maye_show_titlebar = ( 'default' === $single_title_bar ) || 'enable' === $single_title_bar;
		} elseif ( is_home() ) {
			$maye_show_titlebar = 1 === $title_bar;
		} else {
			$maye_show_titlebar = ( $title_bar && 'default' === $single_title_bar ) || 'enable' === $single_title_bar;
		}

		if ( apply_filters( 'springoo_show_title_bar', $maye_show_titlebar ) ) {
			?>
			<div class="springoo-breadcrumb-area" id="springoo-breadcrumb-area">
				<div class="<?php echo esc_attr( $container_class ); ?>">
					<div class="breadcrumb-wrap d-flex flex-wrap justify-content-between">
						<?php if ( $show_title ) { ?>
							<h3 class="title"><?php springoo_page_title(); ?></h3>
						<?php } ?>
						<?php springoo_breadcrumb(); ?>
					</div>
				</div>
			</div>
			<?php
		}
	}

endif;

if ( ! function_exists( 'springoo_page_title' ) ) :


	/**
	 * Prints the Page Title inside the Title Container
	 *
	 * @return void
	 */
	function springoo_page_title() {
		if ( ( function_exists( 'is_woocommerce' ) && is_woocommerce() && is_product() ) || ( function_exists( 'is_bbpress' ) && is_bbpress() ) ) { // @phpstan-ignore-line
			the_title();
		} elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
			woocommerce_page_title();
		} elseif ( is_archive() ) {
			the_archive_title();
		} elseif ( is_home() ) {
			bloginfo( 'name' );
		} elseif ( is_search() ) {
			printf(
				/* Translators: %s is the search query. */
				esc_html__( 'Search results for: %s', 'springoo' ),
				get_search_query()
			);
		} else {
			the_title();
		}
	}

endif;

if ( ! function_exists( 'springoo_comments' ) ) :
	/**
	 * Prints the Comments for a page or post
	 *
	 * @param WP_Comment $comment comment.
	 * @param array $args argument.
	 * @param int $depth depth.
	 *
	 * @return void
	 */
	function springoo_comments( $comment, $args, $depth ) {
		if ( in_array( get_comment_type(), [ 'pingback', 'trackback' ], true ) ) {
			?>
			<li class="pingback" id="comment-<?php comment_ID(); ?>">
				<article <?php comment_class( 'clearfix' ); ?>>
					<div class="comment-meta">
						<?php esc_html_e( 'Pingback:', 'springoo' ); ?>
						<?php edit_comment_link( '<i class="ti-pencil-alt" aria-hidden="true"></i>' . __( 'Edit', 'springoo' ), '<span class="edit-link">', '</span>' ); ?>
					</div>
					<div class="comment-content">
						<?php comment_author_link(); ?>
					</div>
				</article>
			</li>
			<?php
		} elseif ( get_comment_type() === 'comment' ) {
			$reply_link_args = array_merge(
				$args,
				[
					'depth'      => $depth,
					'max_depth'  => $args['max_depth'],
					'reply_text' => '<i class="si si-thin-reply" aria-hidden="true"></i>' . esc_html__( 'Reply', 'springoo' ),
					'login_text' => '<i class="si si-thin-reply" aria-hidden="true"></i>' . esc_html__( 'Log in to Reply', 'springoo' ),
				]
			);
			?>
			<li id="comment-<?php comment_ID(); ?>">
				<article <?php comment_class( 'clearfix' ); ?>>
					<div class="avatar"><?php echo get_avatar( $comment, 70 ); ?></div>
					<div class="comment-meta">
						<?php
						$author_url = get_comment_author_url();
						if ( empty( $author_url ) || false !== strpos( $author_url, 'http' ) ) {
							?>
							<h5 class="comment-author"><?php comment_author(); ?></h5>
						<?php } else { ?>
							<i class="ti-user" aria-hidden="true"></i>
							<a class="comment-author" href="<?php echo esc_url( $author_url ); ?>"><?php comment_author(); ?></a>
						<?php } ?>
						<span class="comment-date post-date"><?php comment_date(); ?> <?php esc_html_e( 'at', 'springoo' ); ?> <?php comment_time(); ?></span>
						<span class="comment-reply"><?php comment_reply_link( $reply_link_args ); ?></span>
					</div>
					<div class="comment-content">
						<?php if ( '0' === $comment->comment_approved ) : ?>
							<p><?php esc_html_e( 'Your comment is awaiting moderation', 'springoo' ); ?></p>
						<?php endif; ?>
						<?php comment_text(); ?>
					</div>
				</article>
			</li>
			<?php
		}
	}
endif;

if ( ! function_exists( 'springoo_pagination' ) ) :


	/**
	 * Prints pagination HTML required by the theme
	 *
	 * @param string|array $args Optional. Array or string of arguments for generating paginated links for archives.
	 *
	 * @return void
	 *@see paginate_links() for detailed arguments
	 */
	function springoo_pagination( $args = '' ) {
		$args = wp_parse_args(
			$args,
			[
				'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
				'next_text' => is_rtl() ? '&larr;' : '&rarr;',
				'type'      => 'list',
			]
		);

		// We need it to be array, always...
		$args['type'] = 'array';

		// Generate the pagination links;
		$pages = paginate_links( $args );

		if ( is_array( $pages ) ) {
			?>
			<div class="springoo-pagination center">
				<ul class="pagination page-numbers">
					<?php echo '<li>' . implode( '</li><li>', $pages ) . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</ul>
			</div>
			<?php
		}
	}
endif;

if ( ! function_exists( 'springoo_featured_image_width' ) ) :

	/**
	 * Prints CSS width style for featured image
	 *
	 * @param string $view
	 * @param WP_Post|int $post
	 *
	 * @return string
	 */
	function springoo_featured_image_width( $view, $post = null ) {
		$post  = get_post( $post );
		$width = '';

		if ( $post && ! springoo_get_mod( 'layout_' . $view . '_ft-img-enlarge' ) && ! springoo_get_mod( 'layout_' . $view . '_ft-img-hide' ) && has_post_thumbnail() ) {
			$thumb_id    = get_post_thumbnail_id( $post->ID );
			$thumb_sizes = $thumb_id ? wp_get_attachment_metadata( $thumb_id, true ) : false;
			if ( $thumb_sizes ) {
				$width = floatval( $thumb_sizes['width'] );
				$width = $width >= 1140 ? 1140 : $width;
				$width = "width:{$width}px;max-width:100%";
			}
		}

		return $width;
	}
endif;

if ( ! function_exists( 'springoo_the_title' ) ) {
	/**
	 * Display Post Title.
	 *
	 * @return void
	 */
	function springoo_the_title() {
		$springoo_page_title = springoo_get_post_layout_options( 'page_title' );
		$springoo_view       = springoo_get_current_screen();
		$show_title          = 'enable' === $springoo_page_title || ( 'default' === $springoo_page_title && (int) springoo_get_mod( 'layout_' . $springoo_view . '_title' ) );

		if ( apply_filters( 'springoo_show_page_title', $show_title ) ) {
			if ( is_singular() ) {
				?>
				<h1 class="post-title entry-title"><?php the_title(); ?></h1>
			<?php
			} else {
				?>
				<h2 class="post-title entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php
			}
		}
	}
}

if ( ! function_exists( 'springoo_the_post_thumbnail' ) ) {
	/**
	 * Renders the post thumbnail.
	 *
	 * @param string|int[] $size Optional. Image size. Accepts any registered image size name, or an array of
	 *                           width and height values in pixels (in that order). Default 'post-thumbnail'.
	 * @param string|array $attr Optional. Query string or array of attributes. Default empty.
	 * @param int|WP_Post  $post Optional. Default Current Post.
	 *
	 * @return void
	 */
	function springoo_the_post_thumbnail( $size = 'post-thumbnail', $attr = '', $post = null ) {

		$springoo_view = springoo_get_current_screen();

		if ( springoo_get_mod( 'layout_' . $springoo_view . '_ft-img-hide' ) ) {
			return;
		}

		$springoo_layout = springoo_get_content_layout( $springoo_view );

		$the_post  = get_post( $post );
		$is_single = is_singular( $the_post->post_type );   // @phpstan-ignore-line
		$has_thumb = has_post_thumbnail( $the_post );

		do_action( 'springoo_' . $springoo_view . '_before_feature_image', $is_single, $has_thumb );

		if ( $has_thumb ) {

			$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
			$caption      = wp_get_attachment_caption(  $thumbnail_id );

			if ( $caption && 'image' === get_post_format() ) {
				if ( $is_single ) {
					printf( '<figure class="wp-block-image">%s<figcaption class="wp-caption-text">%s</figcaption></figure>', get_the_post_thumbnail( $the_post, $size, $attr ), wp_kses_post( $caption ) );
				} else {
					printf( '<a href="%s"><figure class="wp-block-image">%s<figcaption class="wp-caption-text">%s</figcaption></figure></a>', esc_url( get_the_permalink() ), get_the_post_thumbnail( $the_post, $size, $attr ), wp_kses_post( $caption ) );
				}
			} else {
				$_attr = [
					'alt'             => get_the_title( $the_post ), // @phpstan-ignore-line
					'link'            => true,
					'link_classes'    => '',
					'wrapper'         => true,
					'wrapper_classes' => 'image-wrap',
					'extra_classes'   => '',
					'lazy_loading'    => true,
				];

				$attr   = wp_parse_args( $attr, $_attr );
				$format = '%s';
				$lazy   = $attr['lazy_loading'];

				// Permalink.
				if ( $attr['link'] ) {
					$format = '<a href="' . get_the_permalink( $the_post ) . '">%s</a>'; // @phpstan-ignore-line
				}

				// Post Image Wrapper.
				if ( $attr['wrapper'] ) {

					if ( ! empty( $attr['extra_classes'] ) ) {
						$attr['wrapper_classes'] .= ' ' . trim( $attr['extra_classes'] );
					}

					if ( 'medium' === $springoo_layout ) {
						$attr['wrapper_classes'] .= ' medium-left';
					}

					$wrap_attrs = $attr['wrapper_classes'] . ' ' . ( $is_single ? 'single-featured' : 'loop-featured' );
					$wrap_attrs = 'class="' . esc_attr( $attr['wrapper_classes'] ) . '"';
					$wrap_attrs = $wrap_attrs . ( $is_single ? ' style="' . springoo_featured_image_width( $springoo_view, $the_post ) . '"' : '' );
					$wrap_attrs = str_replace( [ '%' ], '%%', $wrap_attrs );
					$format     = '<div ' . $wrap_attrs . '>' . $format . '</div>';
				}

				unset(
					$attr['link'],
					$attr['wrapper'],
					$attr['link_classes'],
					$attr['extra_classes'],
					$attr['wrapper_classes']
				);

				if ( ! $lazy ) {
					add_filter( 'springoo_lazyload_post_thumbnail', '__return_false' );
				}

				// Print the thumbnail.
				printf( $format, get_the_post_thumbnail( $the_post, $size, $attr ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		do_action( 'springoo_' . $springoo_view . '_after_feature_image', $is_single, $has_thumb );
	}
}

if ( ! function_exists( 'springoo_edit_post_link' ) ) {
	/**
	 * Display Post Title.
	 *
	 * @return void
	 */
	function springoo_edit_post_link() {
		edit_post_link(
			'<i class="ti-pencil-alt" aria-hidden="true"></i> ' . esc_html__( 'Edit', 'springoo' ),
			'<span class="edit-link">',
			'</span>'
		);
	}
}

if ( ! function_exists( 'springoo_link_pages' ) ) {
	/**
	 * The formatted output of a list of pages.
	 *
	 * @param string|array $args Optional. Array or string of default arguments.
	 *
	 * @return string
	 *@see wp_link_pages() for detaild arguments.
	 */
	function springoo_link_pages( $args = '' ) {
		$defaults = array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'springoo' ),
			'after'  => '</div>',
		);

		$parsed_args = wp_parse_args( $args, $defaults );

		return wp_link_pages( $parsed_args );
	}
}

if ( ! function_exists( 'springoo_post_format_get_post_audio' ) ) {
	/**
	 * Output Audio Player for post-format-audio
	 *
	 * @param string $layout Layout.
	 * @param WP_Post|null|int $post Current post.
	 *
	 * @return void
	 */
	function springoo_post_format_get_post_audio( $layout = '', $post = null ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return;
		}

		if ( ! $layout ) {
			$layout = springoo_get_content_layout();
		}

		$type    = get_post_meta( $post->ID, 'springoo_audio_source', true );
		$source  = get_post_meta( $post->ID, 'springoo_audio_' . $type, true );
		$mime    = 'hosted' === $type ? get_post_mime_type( $source['id'] ) : false;
		$content = get_the_content( null, false, $post );

		if ( 'embedded' === $type && ! empty( $source ) ) {
			/**
			 * @var string|false $found
			 */
			$found = springoo_render_embedded( $source, false );
			if ( $found ) {
				?>
				<div <?php springoo_get_post_format_classes( $layout ); ?>>
					<?php get_template_part( 'partials/content', 'sticky' ); ?>
					<?php
					echo wp_kses(
						$found,
						[
							'iframe' => [
								'title'           => [],
								'width'           => [],
								'height'          => [],
								'src'             => [],
								'frameborder'     => [],
								'loading'         => [],
								'allow'           => [],
								'allowfullscreen' => [],
							],
						]
					);
					?>
				</div>
				<?php
			}
		} elseif ( 'hosted' === $type && ! empty( $source ) && $mime ) {
			$found = true;
			?>
			<div <?php springoo_get_post_format_classes( $layout ); ?>>
				<?php get_template_part( 'partials/content', 'sticky' ); ?>
				<audio class="player" controls>
					<source src="<?php echo esc_url( $source['url'] ); ?>" type="<?php echo esc_attr( $mime ); ?>">
				</audio>
			</div>
			<?php
		} else {
			if ( has_block( 'core/embed', $content ) ) {
				$found = springoo_get_first_instance_of_block( 'core/embed', $content );
			} elseif ( has_block( 'core/audio', $content ) ) {
				$found = springoo_get_first_instance_of_block( 'core/audio', $content );
			} else {
				$found = springoo_get_first_instance_of_block( 'core-embed/*', $content );
			}

			if ( ! $found ) {
				// fallback for Non-Gutenberg Users.
				if ( preg_match_all( '/(\[audio.+(?:\.mp3|\.ogg).*\])/m', $content, $matches ) ) {
					$found = do_shortcode( $matches[0][0] );
				} elseif ( preg_match_all( '/(\[playlist.*\])/m', $content, $matches ) ) {
					$found = do_shortcode( $matches[0][0] );
				} else {
					// check pre historic (classic) editor link based embed
					$embedded_contents = get_media_embedded_in_content( apply_filters( 'the_content', $content ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound
					if ( ! empty( $embedded_contents ) ) {
						$found = $embedded_contents[0];
					}
				}
			}

			if ( $found ) {
				?>
				<div <?php springoo_get_post_format_classes( $layout ); ?>>
					<?php get_template_part( 'partials/content', 'sticky' ); ?>
					<?php echo wp_kses_post( $found ); ?>
				</div>
				<?php
			}
		}

		if ( ! $found ) {
			?>
			<div class="alert alert-warning">
				<strong><?php esc_html_e( 'Oops! Something went wrong.', 'springoo' ); ?></strong>
				<?php esc_html_e( 'No audio source detected.', 'springoo' ); ?>
			</div>
			<?php
		}

	}
}

if ( ! function_exists( 'springoo_post_format_get_post_gallery' ) ) {
	/**
	 * Output Gallery Carousel for post-format-gallery
	 *
	 * @param WP_Post|null|int $post Current post.
	 * @param string $layout Layout.
	 *
	 * @return void
	 */
	function springoo_post_format_get_post_gallery( $post = null, $layout = '' ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return;
		}

		if ( ! $layout ) {
			$layout = springoo_get_content_layout();
		}


		$gallery   = get_post_meta( $post->ID, 'springoo_gallery', true );
		$gallery   = wp_parse_id_list( $gallery );
		$slider_id = 'blog-gallery-slider' . $post->ID;

		if ( ! empty( $gallery ) ) {
			?>
			<div id="<?php echo esc_attr( $slider_id ); ?>" <?php springoo_get_post_format_classes( $layout ); ?> data-bs-ride="carousel">
				<?php get_template_part('partials/content', 'sticky'); ?>
				<!-- Wrapper for slides -->
				<div class="carousel-inner">
					<?php foreach ( $gallery as $idx => $slide ) { ?>
						<div class="carousel-item <?php echo ! $idx ? 'active' : ''; ?>">
							<?php echo wp_get_attachment_image( $slide, 'springoo-gallery' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php } ?>
				</div>
				<!-- Controls -->
				<button  class="carousel-control-prev carousel-left" aria-label="<?php esc_attr_e( 'Previous Slide', 'springoo' ); ?>" data-bs-target="#<?php echo esc_attr( $slider_id ); ?>" data-bs-slide="prev">
					<i class="ti-angle-left" aria-hidden="true"></i>
				</button >
				<button class="carousel-control-next carousel-right" aria-label="<?php esc_attr_e( 'Next Slide', 'springoo' ); ?>" data-bs-target="#<?php echo esc_attr( $slider_id ); ?>" data-bs-slide="next">
					<i class="ti-angle-right" aria-hidden="true"></i>
				</button>
			</div>
			<?php
		} else {
			$content = get_the_content( null, false, $post );
			if ( has_block( 'core/gallery', $content ) ) {
				?>
				<div <?php springoo_get_post_format_classes( $layout ); ?>>
					<?php get_template_part('partials/content', 'sticky'); ?>
					<?php springoo_print_first_instance_of_block( 'core/gallery', $content ); ?>
				</div>
				<?php
			} else {
				/**
				 * @var array $gallery
				 */
				$gallery   = get_post_gallery( $post->ID, false );
				$image_alt = sprintf(
					/* translators: 1: Post title, 2. Image Number. */
					__( '%1$s Gallery Image %2$d', 'springoo' ),
					get_the_title( $post ),
					'#1' // @phpstan-ignore-line
				);
				if ( ! empty( $gallery ) && isset( $gallery['src'] ) && is_array( $gallery['src'] ) && ! empty( $gallery['src'] ) ) {
					?>
					<div id="<?php echo esc_attr( $slider_id ); ?>" <?php springoo_get_post_format_classes( $layout ); ?> data-bs-ride="carousel">
						<?php get_template_part('partials/content', 'sticky'); ?>
						<!-- Wrapper for slides -->
						<div class="carousel-inner">
							<?php foreach ( $gallery['src'] as $idx => $slide ) { ?>
								<div class="carousel-item <?php echo ! $idx ? 'active' : ''; ?>">
									<img class="img-responsive" src="<?php echo esc_url( $slide ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
								</div>
							<?php } ?>
						</div>
						<!-- Controls -->
						<button  class="carousel-control-prev carousel-left" aria-label="<?php esc_attr_e( 'Previous Slide', 'springoo' ); ?>" data-bs-target="#<?php echo esc_attr( $slider_id ); ?>" data-bs-slide="prev">
							<i class="ti-angle-left" aria-hidden="true"></i>
						</button >
						<button class="carousel-control-next carousel-right" aria-label="<?php esc_attr_e( 'Next Slide', 'springoo' ); ?>" data-bs-target="#<?php echo esc_attr( $slider_id ); ?>" data-bs-slide="next">
							<i class="ti-angle-right" aria-hidden="true"></i>
						</button>
					</div>
					<?php
				}
			}
		}

	}
}

if ( ! function_exists( 'springoo_post_format_get_post_image' ) ) {
	/**
	 * Output Post Image for post-format-image
	 *
	 * @param string $layout Layout.
	 * @param WP_Post|null|int $post Current post.
	 *
	 * @return void
	 */
	function springoo_post_format_get_post_image( $layout = '', $post = null ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return;
		}

		if ( ! $layout ) {
			$layout = springoo_get_content_layout();
		}

		if ( has_post_thumbnail( $post ) ) {
			?>
			<div <?php springoo_get_post_format_classes( $layout ); ?>>
				<?php get_template_part( 'partials/content', 'sticky' ); ?>
				<?php springoo_the_post_thumbnail( 'blog-' . $layout, [ 'wrapper' => false ], $post ); ?>
			</div>
			<?php
		} else {
			$content = get_the_content( null, false, $post );
			if ( ! post_password_required( $post ) && ! is_attachment( $post->ID ) && has_block( 'core/image', $content ) ) {
				?>
				<div <?php springoo_get_post_format_classes( $layout ); ?>>
					<?php get_template_part( 'partials/content', 'sticky' ); ?>
					<?php springoo_print_first_instance_of_block( 'core/image', $content ); ?>
				</div>
				<?php
			} else {
				$image = get_media_embedded_in_content( apply_filters( 'the_content', $content ), array( 'image' ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound
				if ( ! empty( $image ) ) {
					?>
					<div <?php springoo_get_post_format_classes( $layout ); ?>>
						<?php get_template_part( 'partials/content', 'sticky' ); ?>
						<?php
						echo wp_kses(
							$image[0],
							[
								'img' => [
									'width'           => [],
									'height'          => [],
									'src'             => [],
									'alt'             => [],
									'frameborder'     => [],
									'allowfullscreen' => [],
								],
							]
						);
						?>
					</div>
					<?php
				}
			}
		}

	}
}

if ( ! function_exists( 'springoo_post_format_get_post_link' ) ) {
	/**
	 * Output Link for post-format-link
	 *
	 * @param string $layout layout.
	 * @param WP_Post|null|int $post Current post.
	 *
	 * @return void
	 */
	function springoo_post_format_get_post_link( $layout = '', $post = null ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return;
		}

		if ( ! $layout ) {
			$layout = springoo_get_content_layout();
		}

		$link = get_post_meta( $post->ID, 'springoo_link_url', true );
		if ( ! empty( $link ) ) {
			?>
			<div <?php springoo_get_post_format_classes( $layout ); ?>>
				<?php get_template_part( 'partials/content', 'sticky' ); ?>
				<div class="entry-content">
					<a href="<?php echo esc_url( $link ); ?>" target="_blank"><?php echo esc_html( $link ); ?></a>
				</div>
			</div>
			<?php
		} elseif ( has_block( 'core/paragraph' ) ) {
			?>
			<div <?php springoo_get_post_format_classes( $layout ); ?>>
				<?php get_template_part( 'partials/content', 'sticky' ); ?>
				<?php springoo_print_first_instance_of_block( 'core/paragraph' ); ?>
			</div>
			<?php
		}

		if ( 'grid' === $layout ) {
			get_template_part( 'partials/content', 'sticky' );
		}
	}
}

if ( ! function_exists( 'springoo_post_format_get_post_quote' ) ) {
	/**
	 * Output Quote for post-format-quote
	 *
	 * @param string $layout Layout.
	 * @param WP_Post|null|int $post Current post.
	 *
	 * @return void
	 */
	function springoo_post_format_get_post_quote( $layout = '', $post = null ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return;
		}

		if ( ! $layout ) {
			$layout = springoo_get_content_layout();
		}

		$content = get_the_content( null, false, $post );
		$quote   = get_post_meta( $post->ID, 'springoo_quote_text', true );
		$author  = get_post_meta( $post->ID, 'springoo_quote_author', true );

		if ( ! empty( $quote ) ) {
			?>
			<div <?php springoo_get_post_format_classes( $layout ); ?>>
				<?php get_template_part( 'partials/content', 'sticky' ); ?>
				<div class="entry-content">
					<blockquote>
						<p><?php echo esc_html( $quote ); ?></p>
						<?php if ( $author ) { ?>
						<small><?php echo esc_html( $author ); ?></small>
						<?php } ?>
					</blockquote>
				</div>
			</div>
			<?php
		} elseif ( has_block( 'core/quote', $content ) ) {
			?>
			<div <?php springoo_get_post_format_classes( $layout ); ?>>
				<?php get_template_part( 'partials/content', 'sticky' ); ?>
				<?php springoo_print_first_instance_of_block( 'core/quote', $content ); ?>
			</div>
			<?php
		} elseif ( has_block( 'core/pullquote', $content ) ) {
			?>
			<div <?php springoo_get_post_format_classes( $layout ); ?>>
				<?php get_template_part( 'partials/content', 'sticky' ); ?>
				<?php springoo_print_first_instance_of_block( 'core/pullquote', $content ); ?>
			</div>
			<?php
		} else {
			?>
			<div <?php springoo_get_post_format_classes( $layout ); ?>>
				<?php get_template_part( 'partials/content', 'sticky' ); ?>
				<?php echo apply_filters( 'the_excerpt', the_content( $post ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound,WordPress.Security.EscapeOutput ?>
			</div>
			<?php
		}

	}
}

if ( ! function_exists( 'springoo_post_format_get_post_chat' ) ) {
	/**
	 * Output Quote for post-format-chat
	 *
	 * @param string $layout Layout.
	 * @param WP_Post|null|int $post Current post.
	 *
	 * @return void
	 */
	function springoo_post_format_get_post_chat( $layout = '', $post = null ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return;
		}

		if ( ! $layout ) {
			$layout = springoo_get_content_layout();
		}

		?>
		<div <?php springoo_get_post_format_classes( $layout ); ?>>
			<?php
			get_template_part( 'partials/content', 'sticky' );

			if ( has_block( 'core/paragraph', get_the_content() ) ) {
				springoo_print_first_instance_of_block( 'core/paragraph', get_the_content(), 4 );
			}
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'springoo_post_format_get_post_video' ) ) {
	/**
	 * Output Video Player for post-format-video
	 *
	 * @param string $layout Content layout.
	 * @param WP_Post|null|int $post Current post.
	 *
	 * @return void
	 */
	function springoo_post_format_get_post_video( $layout = '', $post = null ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return;
		}

		if ( ! $layout ) {
			$layout = springoo_get_content_layout();
		}

		$content  = get_the_content( null, false, $post );
		$type     = get_post_meta( $post->ID, 'springoo_video_source', true );
		$source   = get_post_meta( $post->ID, 'springoo_video_' . $type, true );
		$duration = get_post_meta( $post->ID, 'springoo_video_durations', true );
		$mime     = 'hosted' === $type && isset( $source['id'] ) ? get_post_mime_type( $source['id'] ) : false;

		if ( 'embedded' === $type && ! empty( $source ) ) {
			/**
			 * @var string|false $found
			 */
			$found = springoo_render_embedded( $source, false );
			if ( $found ) {
				?>
				<div <?php springoo_get_post_format_classes( $layout ); ?>>
					<?php get_template_part( 'partials/content', 'sticky' ); ?>
					<?php
					echo wp_kses(
						$found,
						[
							'iframe' => [
								'title'           => [],
								'width'           => [],
								'height'          => [],
								'src'             => [],
								'frameborder'     => [],
								'loading'         => [],
								'allow'           => [],
								'allowfullscreen' => [],
							],
						]
					);
					?>
				</div>
				<?php
			}
		} elseif ( 'hosted' === $type && ! empty( $source ) && $mime ) {
			$found = true;
			?>
			<div <?php springoo_get_post_format_classes( $layout ); ?> >
				<?php get_template_part( 'partials/content', 'sticky' ); ?>
				<video class="player" controls data-plyr-config='{ "duration": "<?php echo esc_attr( $duration ); ?>" }'>
					<source src="<?php echo esc_url( $source['url'] ); ?>" type="<?php echo esc_attr( $mime ); ?>">
				</video>
			</div>
			<?php
		} else {
			if ( has_block( 'core/embed', $content ) ) {
				$found = springoo_get_first_instance_of_block( 'core/embed', $content );
			} elseif ( has_block( 'core/video', $content ) ) {
				$found = springoo_get_first_instance_of_block( 'core/video', $content );
			} else {
				$found = springoo_get_first_instance_of_block( 'core-embed/*', $content );
			}

			if ( ! $found ) {
				// fallback for Non-Gutenberg Users.
				if ( preg_match_all( '/(\[video.+(?:\.mp4|\.ogv|\.webem|\.mkv).*\])/m', $content, $matches ) ) {
					$found = do_shortcode( $matches[0][0] );
				} elseif ( preg_match_all( '/(\[playlist.*\])/m', $content, $matches ) ) {
					$found = do_shortcode( $matches[0][0] );
				} else {
					// check pre historic (classic) editor link based embed
					$embedded_contents = get_media_embedded_in_content( apply_filters( 'the_content', $content ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound,WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound
					if ( ! empty( $embedded_contents ) ) {
						$found = $embedded_contents[0];
					}
				}
			}

			if ( $found ) {
				?>
				<div <?php springoo_get_post_format_classes( $layout ); ?>>
					<?php get_template_part( 'partials/content', 'sticky' ); ?>
					<?php echo wp_kses_post( $found ); ?>
				</div>
				<?php
			}
		}

		if ( ! $found ) {
			?>
			<div class="alert alert-warning">
				<strong><?php esc_html_e( 'Oops! Video Source Not Found', 'springoo' ); ?></strong>
				<?php esc_html_e( 'Seems You did not selected any video or source type in your post area', 'springoo' ); ?>
			</div>
			<?php
		}

	}
}

if ( ! function_exists( 'springoo_related_post' ) ) {
	/**
	 * Display related post.
	 *
	 * @return void
	 */
	function springoo_related_post() {

		// Post Tags
		global $post;
		$tags = wp_get_post_tags( $post->ID );

		// Post tags term ids
		$tag_ids = array();
		foreach ( $tags as $tag ) {
			$tag_ids[] = $tag->term_id;
		}

		// Check if tag is empty
		if ( empty( $tag_ids ) ) {
			return;
		}

		//necessary variables
		$show_related_post = springoo_get_mod('layout_show_related_post');
		$slider            = springoo_get_mod('layout_enable_related_post_slider');
		$number_of_post    = springoo_get_mod('layout_related_post_number');
		$column            = springoo_get_mod('layout_related_post_column');

		// Prepare post query
		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => $number_of_post,
			'post__not_in'   => array( $post->ID ),
			'tag__in'        => $tag_ids,
		);

		$related_posts = new WP_Query( $args );

		//set columns if found 1 post
		if ( 1 === $related_posts->found_posts ) {
			$column = 1;
		}

		// Default classes
		$wrapper_class = 'springoo-related-post-wrap springoo-slider-active';

		// Prepare Slider Settings
		if ( $slider && 1 < $related_posts->found_posts ) {
			// variable come from customizer
			$slick_arrows   = springoo_get_mod('layout_related_post_slider_enable_arrow') ? 'true' : 'false';
			$slick_dots     = springoo_get_mod('layout_related_post_slider_enable_dots') ? 'true' : 'false';
			$slick_autoplay = springoo_get_mod('layout_related_post_slider_enable_autoplay') ? 'true' : 'false';

			$slick_data = '{"slidesToShow":' . $column . ',"arrows":' . $slick_arrows . ',"dots":' . $slick_dots . ',"autoplay":' . $slick_autoplay . ',"responsive":[{"breakpoint":768,"settings":{"slidesToShow":1}}]}';
		} else {
			$wrapper_class = 'springoo-related-post-wrap column-' . $column;
		}

		if ( $related_posts->have_posts() && $show_related_post ) {
			?>
			<div class="springoo-related-post">
			<div class="springoo-section-heading related-post-heading">
				<h2><?php esc_html_e('You May Also Like ', 'springoo'); ?></h2>
			</div><!-- related-post-heading -->
			<div class="<?php echo esc_attr( $wrapper_class ); ?>" <?php echo esc_attr( $slider ) && 1 < $related_posts->found_posts ? 'data-slick = ' . esc_attr( $slick_data ) : ''; ?>>
			<?php
			while ( $related_posts->have_posts() ) {
				$related_posts->the_post();
				?>
					<div class="related-post-item">

						<?php if ( has_post_thumbnail() ) : ?>
							<div class="related-post-featured-image">
								<a href="<?php echo esc_url( get_permalink() ); ?>">
									<?php echo get_the_post_thumbnail(); ?>
								</a>
							</div>
						<?php endif; ?>

						<h5 class="related-post-title">
							<a href="<?php echo esc_url( get_permalink() ); ?>">
								<?php the_title(); ?>
							</a>
						</h5>

						<div class="related-post-meta">
							<?php springoo_post_meta(); ?>
						</div>

					</div><!-- end .related-post-item -->
				<?php
			}
			?></div><?php
		}
		?>

		<?php
		/* Restore original Post Data */
		wp_reset_postdata();
	}
}

if ( ! function_exists( 'springoo_footer_widget_column_class') ) {
	/**
	 * Footer Widget column change
	 *
	 * @param $params
	 *
	 * @return array
	 */
	function springoo_footer_widget_column_class( $params ) {
		if ( 'footer-widget' === $params[0]['id'] ) {

			$column_xl           = (int) springoo_get_mod( 'layout_footer_widget_column' );
			$column_class_before = 'col-md-6 col-xm-12';
			$column_class_after  = $column_class_before . ' col-xl-' . $column_xl;

			$params[0]['before_widget'] = str_replace( $column_class_before, $column_class_after, $params[0]['before_widget']);
		}
		return $params;
	}

	add_filter('dynamic_sidebar_params', 'springoo_footer_widget_column_class');
}

if ( ! function_exists( 'springoo_header_banner' ) ) {
	function springoo_header_banner() {
		if ( get_custom_header() ) {
			?>
			<div role="banner" class="springoo-banner">
				<img src="<?php header_image(); ?>" alt="Springoo Banner" height="<?php echo esc_attr( get_custom_header()->height ); ?>" width="<?php echo esc_attr( get_custom_header()->width); ?>" />
			</div><!-- end .gossip-banner-->
			<?php
		}
	}
}

if ( ! function_exists('springoo_tag_cloud_font_size_remove') ) {
	/**
	* Tag Cloud Font Size
	 *
	* @param $return
	*
	* @return array|string|string[]
	 */
	function springoo_tag_cloud_font_size_remove( $return ) {
		//@TODO random font size enable/disable option
		return preg_replace('/(style=("|\Z)(.*?)("|\Z))/m', '', $return);
	}
	add_filter( 'wp_tag_cloud', 'springoo_tag_cloud_font_size_remove');
}

if ( ! function_exists( 'springoo_show_taxonomy' ) ) {
	/**
	 * Show Post Taxonomy
	 *
	 * @return void
	 */
	function springoo_show_taxonomy() {
		$springoo_view = springoo_get_current_screen();
		?>
		<div class="post-taxonomy">
			<?php springoo_post_taxonomy( $springoo_view ); ?>
		</div><!-- .post-taxonomy -->
		<?php
	}
}

if ( ! function_exists( 'springoo_show_entry_header' ) ) {
	/**
	 * Show Post Entry Header
	 *
	 * @return void
	 */
	function springoo_show_entry_header() {
		$springoo_view   = springoo_get_current_screen();
		$springoo_layout = springoo_get_content_layout( $springoo_view );

		?>
		<div class="entry-header">
			<h2 class="post-title entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php echo ( ! function_exists( get_the_title() ) && 'normal' === $springoo_layout ) ? '<div class="extra-class-title"></div>' : ''; ?>
		</div><!-- .entry-header  -->
		<?php
	}
}

if ( ! function_exists( 'springoo_show_entry_content' ) ) {
	/**
	 * Show Post Content
	 *
	 * @return void
	 */
	function springoo_show_entry_content() {
		$springoo_view   = springoo_get_current_screen();
		$springoo_layout = springoo_get_content_layout( $springoo_view );
		$format          = get_post_format();

		if ( ( 'normal' || 'grid' === $springoo_layout ) && 'image' === $format ) { //@TODO should not hide on grid. if need hide it then.
			return;
		}
		?>
		<div class="entry-content">
			<?php
			// Content
			if ( 'aside' === $format || 'link' === $format ) {
				the_content();
			} else {
				the_excerpt();
			}

			// @TODO if there is content should show link pages
			springoo_link_pages();
			?>
		</div><!-- end .entry-content -->
		<?php
	}
}

if ( ! function_exists( 'springoo_show_entry_meta' ) ) {
	/**
	 * Show Post Entry Meta
	 *
	 * @return void
	 */
	function springoo_show_entry_meta() {
		if ( 'post' == get_post_type() ) { ?>
			<div class="entry-meta">
				<?php springoo_post_meta(); ?>
			</div><!-- .entry-meta -->
		<?php
		}
	}
}

if ( ! function_exists( 'springoo_show_entry_footer' ) ) {

	/**
	 * Show Read More Button
	 *
	 * @return void
	 */
	function springoo_show_entry_footer() {
		$springoo_view   = springoo_get_current_screen();
		$springoo_layout = springoo_get_content_layout( $springoo_view );
		$format          = get_post_format();

		if ( 'normal' === $springoo_layout && 'image' === $format ) {
			return;
		} elseif ( 'grid' === $springoo_layout ) {
			return;
		}
		?>
		<div class="entry-footer">
			<?php springoo_post_read_more(); ?>
		</div><!-- end .entry-footer -->
		<?php
	}
}



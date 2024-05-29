<?php
/**
 * Displayed when no products are found matching the current query
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/no-products-found.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="no-products-found-body">
	<section class="no-products-found">
		<header class="entry-header">
			<p class="page-title">
				<?php
					/* translators: %s search keyword */
					printf( esc_html__( 'We are sorry, we could not find anything for &ldquo;%s&rdquo;', 'springoo' ), get_search_query() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped*/
				?>
			</p>
		</header><!-- .entry-header -->
		<div class="entry-content">
			<h6 class="page-content"><?php esc_html_e( 'Check spelling or try something new', 'springoo' ); ?> </h6>
		</div><!-- .entry-content -->
	</section><!-- .no-products-found -->

	<?php if ( is_active_sidebar( 'no-products-found-widget' ) ) { ?>
		<section class="widget-area">
			<?php dynamic_sidebar( 'no-products-found-widget' ); ?>
		</section><!-- .widget-area -->
	<?php } ?>

</div>

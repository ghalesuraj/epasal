<?php
/**
 * Single Product Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;

if ( ! wc_review_ratings_enabled() ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();
?>
<?php if ( ( $rating_count > 0 ) || ( wc_product_sku_enabled() && ! empty( $product->get_sku() ) ) ) { ?>
	<div class="springoo-meta-area">
		<?php if ( $rating_count > 0 ) { ?>

			<span class="woocommerce-product-rating">
			<?php echo wc_get_rating_html( $average, $rating_count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( comments_open() ) : ?>
					<?php //phpcs:disable ?>
					<a href="#reviews" class="woocommerce-review-link"
					   rel="nofollow">(<?php /* Translators: %s is the review number. */ printf( _n( '%s Review', '%s Reviews', $review_count, 'springoo' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>
					)</a>
					<?php // phpcs:enable ?>
				<?php endif ?>
		</span>

		<?php } ?>
		<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) :
			?>
			<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'springoo' ); ?>
				<span class="sku">
					<?php echo esc_html( $product->get_sku() ); ?>
				</span>
			</span>
		<?php endif; ?>
	</div>
<?php }



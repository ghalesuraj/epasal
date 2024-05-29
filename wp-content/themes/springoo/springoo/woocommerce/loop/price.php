<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

global $product, $springoo_products;

$display_price = isset( $springoo_products['display_price'] ) && 'yes' == $springoo_products['display_price'];

if ( ! $display_price ) {
	return;
}

$price_html = $product->get_price_html()

?>

<?php if ( $price_html ) : ?>
	<span class="price cart-price">
		<?php echo wp_kses_post( $price_html ); ?>
	</span>
<?php endif; ?>
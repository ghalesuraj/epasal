<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'shipping' => __( 'Shipping address', 'springoo' ),
			'billing'  => __( 'Billing address', 'springoo' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Billing address', 'springoo' ),
		),
		$customer_id
	);
}

$i = 1;

do_action('woocommerce_before_edit_my_address');

?>

<p class="woocommerce-myaccount-myaddress-description">
	<?php echo apply_filters( 'woocommerce_my_account_my_address_description', esc_html__( 'The following addresses will be used on the checkout page by default.', 'springoo' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</p>

<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
	<div class="woocommerce-Addresses addresses">
<?php endif; ?>

<?php foreach ( $get_addresses as $name => $address_title ) : ?>
	<?php
		$address = wc_get_account_formatted_address( $name );
	?>

	<div class="woocommerce-Address">
		<div class="woocommerce-address-icon woocommerce-address-icon-<?php echo esc_attr( $i ); ?>"></div>
		<div class="woocommerce-address-content">
			<header class="woocommerce-Address-title title">
				<h6><?php echo esc_html( $address_title ); ?></h6>
			</header>
			<address>
				<?php
				if ( $address ) {
					echo wp_kses_post( $address );
				} else {
					esc_html_e( 'You have not set up this type of address yet.', 'springoo' );
				}
				?>
			</address>
			<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="edit">
				<?php
				if ( $address ) {
					esc_html__( 'Edit', 'springoo' );
				} else {
					esc_html__( 'Add', 'springoo' );
				}
				?>
			</a>
		</div>
	</div>

<?php $i++;
endforeach; ?>

<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
	</div>
	<?php
endif;

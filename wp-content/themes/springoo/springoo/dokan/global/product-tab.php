<?php
/**
 * Dokan Seller Single product tab Template
 *
 * @since   2.4
 *
 * @package springoo
 */

$vendor_id  = get_post_field( 'post_author', get_the_id() );
$store_user = dokan()->vendor->get( $vendor_id );
?>

<table>
	<tbody>
	<?php do_action( 'dokan_product_seller_tab_start', $author, $store_info ); ?>
		<tr class="store-author">
			<td colspan="2" class="details">
				<div class="author-img">
					<img src="<?php echo esc_url( $store_user->get_avatar() ); ?>" alt="<?php echo esc_attr( $store_info['store_name'] ); ?>">
				</div>
				<div class="author-info">
					<div class="store-name"><?php echo esc_html( $store_info['store_name'] ); ?></div>
					<?php if ( ( dokan()->is_pro_exists() && dokan_pro()->module->is_active( 'store_reviews' ) ) || ( 'yes' === get_option( 'woocommerce_enable_reviews' ) ) ) : ?>
					<div class="store-rating"><?php echo wp_kses_post( dokan_get_readable_seller_rating( $author->ID ) ); ?></div>
					<?php endif; ?>
				</div>
			</td>
		</tr>

	<?php if ( ! empty( $store_info['store_name'] ) ) : ?>
		<tr class="store-name">
			<td style="width: 45%;"><?php esc_html_e( 'Store Name:', 'springoo' ); ?></td>
			<td style="width: 55%;" class="details">
				<?php echo esc_html( $store_info['store_name'] ); ?>
			</td>
		</tr>
	<?php endif; ?>
	<tr class="seller-name">
		<td style="width: 45%;">
			<?php esc_html_e( 'Vendor:', 'springoo' ); ?>
		</td>

		<td style="width: 55%;" class="details">
			<?php printf( '%1$s %2$s', esc_html( $store_user->get_first_name() ), esc_html( $store_user->get_last_name() ) ); ?>
		</td>
	</tr>
	<?php if ( ! dokan_is_vendor_info_hidden( 'address' ) && ! empty( $store_info['address'] ) ) { ?>
		<tr class="store-address">
			<td style="width: 45%;"><?php esc_html_e( 'Address:', 'springoo' ); ?></td>
			<td style="width: 55%;" class="details">
				<?php echo wp_kses_post( dokan_get_seller_short_address( $author->ID, false ) ); ?>
			</td>
		</tr>
	<?php } ?>

	<?php if ( ! dokan_is_vendor_info_hidden( 'email' ) && $store_user->show_email() ) { ?>
	<tr class="seller-email">
		<td style="width: 45%;">
			<?php esc_html_e( 'Email:', 'springoo' ); ?>
		</td>
		<td style="width: 55%;" class="details">
			<a href="mailto:<?php echo esc_attr( antispambot( $store_user->get_email() ) ); ?>"><?php echo esc_html( antispambot( $store_user->get_email() ) ); ?></a>
		</td>
	</tr>
	<?php } ?>
	<?php if ( ! dokan_is_vendor_info_hidden( 'phone' ) && ! empty( $store_info['phone'] ) ) { ?>
	<tr class="seller-phone">
		<td style="width: 45%;">
			<?php esc_html_e( 'Mobile:', 'springoo' ); ?>
		</td>

		<td style="width: 55%;" class="details">
			<?php echo esc_html( $store_info['phone'] ); ?>
		</td>
	</tr>
	<?php } ?>
	<tr class="seller-button">
		<td colspan="2" class="store-visit">
			<?php printf( '<a href="%1$s">%2$s %3$s</a>', esc_url( dokan_get_store_url( $author->ID ) ), esc_html__( 'Visit Store', 'springoo' ), '<i class="si si-thin-arrow-long-right"></i>' ); ?>
		</td>
	</tr>

	<?php do_action( 'dokan_product_seller_tab_end', $author, $store_info ); ?>
	</tbody>
</table>

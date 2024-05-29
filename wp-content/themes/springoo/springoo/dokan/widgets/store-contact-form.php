<?php
/**
 * Dokan Store Contact Form widget Template
 *
 * @since   2.4
 *
 * @package dokan
 */

$dokan_listing_page = get_option( 'dokan_pages' );
if ( is_page( $dokan_listing_page['store_listing'] ) ) {
	?>

	<form id="dokan-form-contact-seller" action="" method="post" class="seller-form clearfix">
		<div class="ajax-response"></div>
		<ul>
			<li class="dokan-form-group">
				<label for="name"><?php esc_html_e('Your Name *', 'springoo'); ?></label>
				<input id="name" type="text" name="name" value="<?php echo esc_attr( $username ); ?>" placeholder="<?php esc_attr_e( 'Your Name', 'springoo' ); ?>" class="dokan-form-control" minlength="5" required="required">
			</li>
			<li class="dokan-form-group">
				<label for="name"><?php esc_html_e('Your Email *', 'springoo'); ?></label>
				<input type="email" name="email" value="<?php echo esc_attr( $email ); ?>" placeholder="<?php esc_attr_e( 'you@example.com', 'springoo' ); ?>" class="dokan-form-control" required="required">
			</li>
			<li class="dokan-form-group">
				<label for="name"><?php esc_html_e('Your Inquiry *', 'springoo'); ?></label>
				<textarea name="message" maxlength="1000" cols="25" rows="6" value="" placeholder="<?php esc_attr_e( 'Type your messsage...', 'springoo' ); ?>" class="dokan-form-control" required="required"></textarea>
			</li>
		</ul>

		<?php do_action( 'dokan_contact_form', $seller_id ); ?>

		<?php wp_nonce_field( 'dokan_contact_seller', 'dokan_contact_seller_nonce' ); ?>
		<input type="hidden" name="dokan_recaptcha_token" class="dokan_recaptcha_token">
		<input type="hidden" name="seller_id" value="<?php echo esc_attr( $seller_id ); ?>">
		<input type="hidden" name="action" value="dokan_contact_seller">
		<input type="submit" name="store_message_send" value="<?php esc_attr_e( 'Send Inquiry', 'springoo' ); ?>" class="dokan-right dokan-btn dokan-btn-theme">
	</form>


<?php } else { ?>
	<form id="dokan-form-contact-seller" action="" method="post" class="seller-form clearfix">
		<div class="ajax-response"></div>
		<ul>
			<li class="dokan-form-group">
				<input type="text" name="name" value="<?php echo esc_attr( $username ); ?>" placeholder="<?php esc_attr_e( 'Your Name', 'springoo' ); ?>" class="dokan-form-control" minlength="5" required="required">
			</li>
			<li class="dokan-form-group">
				<input type="email" name="email" value="<?php echo esc_attr( $email ); ?>" placeholder="<?php esc_attr_e( 'you@example.com', 'springoo' ); ?>" class="dokan-form-control" required="required">
			</li>
			<li class="dokan-form-group">
				<textarea name="message" maxlength="1000" cols="25" rows="6" value="" placeholder="<?php esc_attr_e( 'Type your messsage...', 'springoo' ); ?>" class="dokan-form-control" required="required"></textarea>
			</li>
		</ul>

		<?php do_action( 'dokan_contact_form', $seller_id ); ?>

		<?php wp_nonce_field( 'dokan_contact_seller', 'dokan_contact_seller_nonce' ); ?>
		<input type="hidden" name="dokan_recaptcha_token" class="dokan_recaptcha_token">
		<input type="hidden" name="seller_id" value="<?php echo esc_attr( $seller_id ); ?>">
		<input type="hidden" name="action" value="dokan_contact_seller">
		<input type="submit" name="store_message_send" value="<?php esc_attr_e( 'Send Message', 'springoo' ); ?>" class="dokan-right dokan-btn dokan-btn-theme">
	</form>
<?php } ?>

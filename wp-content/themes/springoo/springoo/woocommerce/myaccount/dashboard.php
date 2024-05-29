<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);

/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action( 'woocommerce_before_my_account' );

?>

<div class="woocommerce-my-account-dashboard-wrapper">
	<div class="woocommerce-my-account-dashboard">
		<div class="woocommerce-my-account-dashboard-inner">
			<div class="woocommerce-my-account-dashboard-icon"></div>
			<div class="woocommerce-my-account-dashboard-content">
				<h6><?php esc_html_e('My Account', 'springoo'); ?></h6>
				<span><?php esc_html_e('Edit your name or change your password', 'springoo'); ?></span>
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>"><?php esc_html_e('View Details', 'springoo'); ?></a>
			</div>
		</div>
	</div>

	<div class="woocommerce-my-account-dashboard">
		<div class="woocommerce-my-account-dashboard-inner">
			<div class="woocommerce-my-account-dashboard-icon icon-shipping"></div>
			<div class="woocommerce-my-account-dashboard-content">
				<h6><?php esc_html_e('Shipping Address', 'springoo'); ?></h6>
				<span><?php esc_html_e('Edit your shipping address anytime ', 'springoo'); ?></span>
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>"><?php esc_html_e('View Details', 'springoo'); ?></a>
			</div>
		</div>
	</div>

	<div class="woocommerce-my-account-dashboard">
		<div class="woocommerce-my-account-dashboard-inner">
			<div class="woocommerce-my-account-dashboard-icon icon-billing"></div>
			<div class="woocommerce-my-account-dashboard-content">
				<h6><?php esc_html_e('Billing Address', 'springoo'); ?></h6>
				<span><?php esc_html_e('Edit your billing address anytime ', 'springoo'); ?></span>
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>"><?php esc_html_e('View Details', 'springoo'); ?></a>
			</div>
		</div>
	</div>
</div>
<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */

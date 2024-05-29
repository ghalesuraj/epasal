<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>
<div class="myaccount-login-signup-wrapper">
	<?php echo 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ? '<div class="tab-content woocommerce-tab-content" id="myTabContent">' : ''; ?>
		<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
			<div class="woocommerce-login-signup-tabs">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true"><?php esc_html_e( 'Log in', 'springoo' ); ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="signin-tab" data-bs-toggle="tab" data-bs-target="#signin" type="button" role="tab" aria-controls="signin" aria-selected="false"><?php esc_html_e( 'Sign up', 'springoo' ); ?></button>
					</li>
				</ul>
			</div>
		<?php endif; ?>

		<?php echo 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ? '<div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">' : ''; ?>
			<form class="woocommerce-form woocommerce-form-login login" method="post">

				<?php
				$signin_image = get_theme_mod('woocommerce_myaccount_sign_in_image');
				if ( empty( $signin_image ) ) {
					$image_class = 'woocommerce-fullwidth';
				} else {
					$image_class = 'woocommerce-login-form-fields';
				}
				?>
				<div class="<?php echo esc_attr( $image_class ); ?>">
					<h3><?php esc_html_e( 'Login to your account', 'springoo' ); ?></h3>

					<?php do_action( 'woocommerce_login_form_start' ); ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="username"><?php esc_html_e( 'Username or email address', 'springoo' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" placeholder="Username or email address" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
					</p>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="password"><?php esc_html_e( 'Password', 'springoo' ); ?>&nbsp;<span class="required">*</span></label>
						<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" placeholder="Password" />
					</p>

					<?php do_action( 'woocommerce_login_form' ); ?>

					<p class="form-row form-checkbox">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'springoo' ); ?></span>
						</label>
					</p>
					<p class="woocommerce-LostPassword lost_password">
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'springoo' ); ?></a>
					</p>

					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Login', 'springoo' ); ?>"><?php esc_html_e( 'Login', 'springoo' ); ?></button>

					<?php do_action( 'woocommerce_login_form_end' ); ?>
				</div>

				<?php
				if ( $signin_image ) {
					?>
						<div class="woocommerce-myaccount-login-image">
							<img src="<?php echo esc_url( $signin_image ); ?>" alt="login">
						</div>
						<?php
				}
				?>

			</form>
		<?php echo 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ? '</div>' : ''; ?>

		<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
			<div class="tab-pane fade" id="signin" role="tabpanel" aria-labelledby="signin-tab">
				<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

					<?php
					$signup_image = get_theme_mod('woocommerce_myaccount_sign_up_image');
					if ( empty( $signup_image ) ) {
						$image_class = 'woocommerce-fullwidth';
					} else {
						$image_class = 'woocommerce-login-form-fields';
					}
					?>
					<div class="<?php echo esc_attr( $image_class ); ?>">
						<?php do_action( 'woocommerce_register_form_start' ); ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="reg_username"><?php esc_html_e( 'Username', 'springoo' ); ?>&nbsp;<span class="required">*</span></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" placeholder="Username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
						</p>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="reg_email"><?php esc_html_e( 'Email address', 'springoo' ); ?>&nbsp;<span class="required">*</span></label>
							<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" placeholder="Email address" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
						</p>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="reg_password"><?php esc_html_e( 'Password', 'springoo' ); ?>&nbsp;<span class="required">*</span></label>
							<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" placeholder="Password" autocomplete="new-password" />
						</p>

						<?php do_action( 'woocommerce_register_form' ); ?>

						<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
						<button type="submit" class="woocommerce-Button woocommerce-button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="signup" value="<?php esc_attr_e( 'Signup', 'springoo' ); ?>"><?php esc_html_e( 'Signup', 'springoo' ); ?></button>

						<?php do_action( 'woocommerce_register_form_end' ); ?>
					</div>

					<?php
					if ( $signup_image ) {
						?>
						<div class="woocommerce-myaccount-login-image">
							<img src="<?php echo esc_url( $signup_image ); ?>" alt="login">
						</div>
						<?php
					}
					?>

				</form>
			</div>
		<?php endif; ?>
	<?php echo 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ? '</div>' : ''; ?>
</div>


<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

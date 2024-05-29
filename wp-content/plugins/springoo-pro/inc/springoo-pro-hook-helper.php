<?php
/**
 * The Companion hooks helper.
 *
 * @package springoo/core
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! function_exists( 'springoo_extra_user_profile_fields' ) ) {
	/**
	 * Add Social Icon link in profile.php
	 */
	function springoo_extra_user_profile_fields( $user ) {
		?>
		<h3><?php esc_html_e( 'Social Link', 'springoo' ); ?></h3>

		<table class="form-table">
			<tr>
				<th><label for="springoo_facebook_url"><?php esc_html_e( 'Facebook URL', 'springoo' ); ?></label>
				</th>
				<td>
					<input type="text" name="springoo_facebook_url" id="springoo_facebook_url" value="<?php echo esc_attr( get_the_author_meta( 'springoo_facebook_url', $user->ID ) ); ?>" class="regular-text"/><br/>
					<span
						class="description"><?php esc_html_e( 'Enter Your Facebook Profile Link', 'springoo' ); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for="springoo_instagram_url"><?php esc_html_e( 'Instagram URL', 'springoo' ); ?></label>
				</th>
				<td>
					<input type="text" name="springoo_instagram_url" id="springoo_instagram_url" value="<?php echo esc_attr( get_the_author_meta( 'springoo_instagram_url', $user->ID ) ); ?>" class="regular-text"/><br/>
					<span
						class="description"><?php esc_html_e( 'Enter Your Instagram Profile Link', 'springoo' ); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for="springoo_twitter_url"><?php esc_html_e( 'Twitter URL', 'springoo' ); ?></label></th>
				<td>
					<input type="text" name="springoo_twitter_url" id="springoo_twitter_url" value="<?php echo esc_attr( get_the_author_meta( 'springoo_twitter_url', $user->ID ) ); ?>" class="regular-text"/><br/>
					<span
						class="description"><?php esc_html_e( 'Enter Your Twitter Profile Link', 'springoo' ); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for="springoo_pinterest_url"><?php esc_html_e( 'Pinterest URL', 'springoo' ); ?></label>
				</th>
				<td>
					<input type="text" name="springoo_pinterest_url" id="springoo_pinterest_url" value="<?php echo esc_attr( get_the_author_meta( 'springoo_pinterest_url', $user->ID ) ); ?>" class="regular-text"/><br/>
					<span
						class="description"><?php esc_html_e( 'Enter Your Pinterest Profile Link', 'springoo' ); ?></span>
				</td>
			</tr>

		</table>
	<?php }
}

if ( ! function_exists( 'springoo_save_extra_user_profile_fields' ) ) {
	/**
	 * Save user profile fields
	 *
	 * @param $user_id
	 *
	 * @return false|void
	 */
	function springoo_save_extra_user_profile_fields( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		if ( ! isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ), 'update-user_' . $user_id ) ) {
			return;
		}

		$facebook_url  = isset( $_POST['springoo_facebook_url'] ) ? sanitize_text_field(  $_POST['springoo_facebook_url']  ) : '';
		$instagram_url = isset( $_POST['springoo_instagram_url'] ) ? sanitize_text_field( $_POST['springoo_instagram_url']  ) : '';
		$twitter_url   = isset( $_POST['springoo_twitter_url'] ) ? sanitize_text_field( $_POST['springoo_twitter_url']  ) : '';
		$pinterest_url = isset( $_POST['springoo_pinterest_url'] ) ? sanitize_text_field( $_POST['springoo_pinterest_url'] ) : '';

		update_user_meta( $user_id, 'springoo_facebook_url', $facebook_url );
		update_user_meta( $user_id, 'springoo_instagram_url', $instagram_url );
		update_user_meta( $user_id, 'springoo_twitter_url', $twitter_url );
		update_user_meta( $user_id, 'springoo_pinterest_url', $pinterest_url );
	}
}

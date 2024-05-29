<?php
/**
 * Dashboard Main Layout
 *
 * @package ABSP
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

?>
	<div class="ayyash-addons-settings-panel integrations">
		<div class="ayyash-addons-settings-panel__body">
			<form action="" class="ayyash-addons-integration-settings" id="ayyash-addons-integration-settings" method="post">
			<div class="ayyash-addons-admin-options">
				<?php self::render_option_fields( 'integrations' ); ?>
				<div class="clear"></div>
				<div class="submit-div">
					<button type="submit" class="btn-gr ayyash-addons-admin--save"><?php esc_html_e('SAVE SETTINGS', 'ayyash-addons'); ?></button>
				</div>
			</div>
			</form>
		</div>
	</div>
<?php
// End of file dashboard-tab-integrations.php.

<?php
/**
 * Dashboard Main Layout
 *
 * @package Ayyash Addons
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}
?>
<div class="wrap ayyash-addons-dashboard">
	<div class="dashboard-header">
		<div class="page-title">
			<h1 class="wp-heading-inline">
				<span><?php esc_html_e( 'Welcome to', 'ayyash-addons' ); ?></span>
				<span class="ayyash-addons-plugin-name"><?php esc_html_e( 'Ayyash Addons', 'ayyash-addons' ); ?></span>
			</h1>
			<span class="version"><?php
				/* translators: 1. Plugin Version Number */
				printf( esc_html__( 'Version %s', 'ayyash-addons' ), esc_html( AYYASH_ADDONS_VERSION ) );
				?></span>
		</div>
		<div class="ayyash-addons-branding">
			<img src="<?php ayyash_addons_plugin_url('assets/images/logo.png' ); ?>" alt="">
		</div>
	</div>
	<hr class="wp-header-end">
	<div class="ayyash-addons-dashboard--wrap">
		<div class="ayyash-addons-dashboard-tabs" role="tablist">
			<div class="ayyash-addons-dashboard-tabs__nav">
				<?php
				foreach ( self::get_registered_tabs() as $slug => $data ) {
					$slug = esc_attr( strtolower( $slug ) );
					$class = 'ayyash-addons-dashboard-tabs__nav-item ayyash-addons-dashboard-tabs__nav-item--' . $slug;

					if ( empty( $data['renderer'] ) || ! is_callable( $data['renderer'] ) ) {
						$class .= ' nav-item-is--link';
					}

					if ( ! empty( $data['href'] ) ) {
						$href = esc_url( $data['href'] );
					} else {
						$href = '#' . $slug;
					}

					$tab_title = isset( $data['tab_title'] ) ? $data['tab_title'] : '';
					$tab_title = ! $tab_title && isset( $data['page_title'] ) ? $data['page_title'] : $tab_title;
					$tab_title = ! $tab_title && isset( $data['menu_title'] ) ? $data['menu_title'] : $tab_title;

					printf(
						'<a href="%1$s" aria-controls="tab-content-%2$s" id="tab-nav-%2$s" class="%3$s" role="tab">%4$s</a>',
						esc_url( $href ),
						esc_attr( $slug ),
						esc_attr( $class ),
						esc_html( $tab_title )
					);
				}
				?>
				<button style="display: none;" class="ayyash-addons-dashboard-tabs__nav-btn ayyash-addons-dashboard-btn ayyash-addons-dashboard-btn--lg ayyash-addons-dashboard-btn--save" type="submit"><?php esc_html_e( 'SAVE SETTINGS', 'ayyash-addons' ); ?></button>
			</div>
			<div class="clear"></div>
			<div class="ayyash-addons-dashboard-tabs__content">
				<?php
				foreach ( self::get_registered_tabs() as $slug => $data ) {
					if ( empty( $data['renderer'] ) || ! is_callable( $data['renderer'] ) ) {
						continue;
					}

					$class = 'ayyash-addons-dashboard-tabs__content-item';

					$slug = esc_attr( strtolower( $slug ) );
					?>
					<div class="<?php echo esc_attr( $class ); ?>" id="tab-content-<?php echo esc_attr( $slug ); ?>" role="tabpanel" aria-labelledby="tab-nav-<?php echo esc_attr( $slug ); ?>">
						<?php call_user_func( $data['renderer'], $slug, $data ); ?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<?php
// End of file dashboard.php.

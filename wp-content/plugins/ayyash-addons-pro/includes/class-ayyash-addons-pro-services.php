<?php
/**
 * Initialize Ayyash Addons Services
 *
 * @version 1.0.0
 * @package AyyashAddons
 */

namespace AyyashAddonsPro;

use AyyashAddons\AyyashPluginsServices\Ayyash_Addons_Services;
use AyyashAddons\AyyashPluginsServices\Client;
use AyyashAddons\AyyashPluginsServices\Insights;
use AyyashAddons\AyyashPluginsServices\Updater;
use AyyashAddons\AyyashPluginsServices\Promotions;
use AyyashAddons\AyyashPluginsServices\License;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Ayyash_Addons_Pro_Services {

	/**
	 * Singleton instance
	 *
	 * @var Ayyash_Addons_Pro_Services
	 */
	protected static $instance;

	/**
	 * @var Client
	 */
	protected $client = null;

	/**
	 * @var Insights
	 */
	protected $insights = null;

	/**
	 * Promotions Class Instance
	 *
	 * @var Promotions
	 */
	public $promotion = null;

	/**
	 * Plugin License Manager
	 *
	 * @var License
	 */
	protected $license = null;

	/**
	 * Plugin Updater
	 *
	 * @var Updater
	 */
	protected $updater = null;

	/**
	 * Initialize
	 *
	 * @return Ayyash_Addons_Pro_Services
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function __construct() {
		/** @define "AYYASH_ADDONS_PATH" "./../../ayyash-addons/" */
		if ( ! class_exists( 'Client' ) ) {
			require_once AYYASH_ADDONS_PATH . 'includes/ayyash-plugins-services/class-client.php';
		}

		// Load The Client.
		$this->client = new Client(
			'82b76de6-5d76-4657-8d5f-e45ad1dbc6e0',
			__( 'Ayyash Addons Pro', 'ayyash-addons-pro' ),
			AYYASH_ADDONS_PRO_FILE,
			133
		);

		// @TODO Add method to check if there's update available for the free version.
		//		 Prompt the user for updating free version.

		$this->client->allow_local_request();

		// License & Updater should be executed only in pro-versions.
		$this->license = $this->client->license();

		/**
		 * @TODO filter library opts and set license validity states.
		 *         So library can check itself and show license warning even before sending download request.
		 */
		$this->license
			->set_activation_required_message(
				sprintf(
				/* translators: %s: Plugin Name */
					__( 'Active %s license to get professional support, <code>pro templates</code> and automatic update from your WordPress dashboard.', 'ayyash-addons-pro' ),
					'<strong>' . esc_html( $this->client->getName() ) . '</strong>'
				)
			)
			->set_header_icon( Plugin::plugin_url( 'assets/images/license-header.svg' ) )
			->set_product_data( [
				'yearly'   => [
					'label'    => __( 'Yearly', 'ayyash-addons-pro' ),
					'products' => [
						145 => __( 'Starter (Single Site)', 'ayyash-addons-pro' ),
						144 => __( 'Professional (Five Sites)', 'ayyash-addons-pro' ),
						143 => __( 'Business (Fifty Sites)', 'ayyash-addons-pro' ),
					],
				],
				'lifetime' => [
					'label'    => __( 'Lifetime', 'ayyash-addons-pro' ),
					'products' => [
						163 => __( 'Starter (Single Site)', 'ayyash-addons-pro' ),
						162 => __( 'Professional (Five Sites)', 'ayyash-addons-pro' ),
						161 => __( 'Business (Fifty Sites)', 'ayyash-addons-pro' ),
					],
				],
			] )
			->set_menu_args(
				[
					'menu_title'  => __( 'License', 'ayyash-addons-pro' ),
					'menu_slug'   => 'ayyash_addons',
					'type'        => 'submenu/tab',
					'parent_slug' => 'ayyash_addons',
				]
			)
			->remove_header()
			->set_page_url( admin_url( 'admin.php?page=ayyash_addons#license' ) )
			->init();

		$this->init_hooks();

		// Initialize Services.
//		$this->client->insights()
//					 ->hide_notice() // Free version will handle the tracking.
//					 ->init(); // We just need the uninstallation tracking here.
		$this->client->insights()->hide_notice()->init();

		// Pro Plugin Updater.
		require_once 'class-updater.php';
//		$this->client->updater()->init();
		$updater = new \AyyashAddonsPro\Updater( $this->client, $this->license );
		$updater->init();
	}

	/**
	 * Initialize Insights
	 *
	 * @return void
	 */
	private function init_hooks() {

		$slug = $this->client->getSlug();

		// Add dashboard license tab
		add_filter( 'ayyash_addons/dashboard/tabs', [ $this, 'add_license_tab' ] );

		// Filter updater api data.
		add_filter( "ayyash_addons_service_api_{$slug}_plugin_api_info", [ $this, 'plugin_api_info' ], 10, 2 );

		// Insights
		add_filter( "ayyash_addons_service_api_{$slug}_Support_Ticket_Recipient_Email", [ $this, 'support_email' ] );
		add_filter( "ayyash_addons_service_api_{$slug}_Support_Ticket_Email_Template", [
			$this,
			'support_ticket_template',
		], 10 );
		add_filter( "ayyash_addons_service_api_{$slug}_Support_Request_Ajax_Success_Response", [
			$this,
			'support_response',
		], 10 );
		add_filter( "ayyash_addons_service_api_{$slug}_Support_Request_Ajax_Error_Response", [
			$this,
			'support_error_response',
		], 10 );
		add_filter( "ayyash_addons_service_api_{$slug}_Support_Page_URL", [ $this, 'support_url' ] );
	}

	public function add_license_tab( $tabs ) {

		$tabs['license'] = [
			'menu_title' => esc_html__( 'License', 'ayyash-addons-pro' ),
			'page_title' => esc_html__( 'License', 'ayyash-addons-pro' ),
			'renderer'   => [ $this, 'render_license_tab' ],
			'position'   => 100,
		];

		return $tabs;
	}

	public function render_license_tab() {
		$this->license->__admin_notices();
		?>
		<div class="ayyash-addons-settings-panel license">
			<style>.ayyash-addons-srv-license-settings {
					margin: 0 !important;
					padding: 35px;
				}

				.ayyash-addons-srv-license-details p {
					font-size: inherit !important;
				}</style>
			<div class="ayyash-addons-settings-panel__body"><?php $this->license->render_license_page(); ?></div>
			<div class="ayyash-addons-settings-panel__footer"></div>
		</div>
		<?php
	}

	/**
	 * Housekeeping
	 *
	 * Add Missing Info for plugin details after fetching through api
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public function plugin_api_info( $data, $action ) {

		if ( ! isset( $data['banners_rtl'] ) ) {
			$data['banners_rtl'] = [];
		}

		if ( ! isset( $data['banners'] ) ) {
			$data['banners'] = [
				'low'  => 'https://ps.w.org/ayyash-addons/assets/banner-772x250.jpg?rev=2577388',
				'high' => 'https://ps.w.org/ayyash-addons/assets/banner-1544x500.jpg?rev=2577380',
			];
		}

		if ( 'plugin_information' !== $action ) {
			$data['icons'] = [
				'2x' => 'https://ps.w.org/ayyash-addons/assets/icon-256x256.png?rev=2577380',
				'1x' => 'https://ps.w.org/ayyash-addons/assets/icon-256x256.png?rev=2577380',
				//'svg'  => '',
			];

			if ( ! isset( $data['requires'] ) ) {
				$data['requires'] = 5.2;
			}

			if ( ! isset( $data['requires'] ) ) {
				$data['tested'] = 5.8;
			}

			if ( ! isset( $data['requires_php'] ) ) {
				$data['requires_php'] = 5.6;
			}

			return $data;
		}

		// Format WC-AM data.

		$data['author'] = '<a href="https://absoluteplugins.com/">AbsolutePlugins</a>';

		$data['author_profile'] = 'https://absoluteplugins.com/';

		if ( ! isset( $data['download_link'] ) && isset( $data['package'] ) ) {
			$data['download_link'] = $data['package'];
		}

		if ( ! isset( $data['contributors'] ) ) {
			$data['contributors'] = [
				'absoluteplugins' => [
					'profile'      => 'https://absoluteplugins.com/',
					'avatar'       => 'https://secure.gravatar.com/avatar/e35a2269cad750741d0fe58ed2db6c9b?s=200&d=mm&r=g',
					'display_name' => 'AbsolutePlugins',
				],
				'niamul'          => [
					'profile'      => 'https://profiles.wordpress.org/niamul/',
					'avatar'       => 'https://secure.gravatar.com/avatar/2d42d046b3756b100ac49de2cd9adee3?s=200&d=mm&r=g',
					'display_name' => 'Niamul',
				],
				'mhamudul_hk'     => [
					'profile'      => 'https://profiles.wordpress.org/mhamudul_hk/',
					'avatar'       => 'https://secure.gravatar.com/avatar/b6855e93cf0a33f7aef5f4464b8f85eb?s=200&d=mm&r=g',
					'display_name' => 'Kudratullah',
				],
			];
		}

		$sections = [ 'description', 'installation', 'faq', 'changelog', 'screenshots', 'reviews', 'other_notes' ];

		foreach ( $sections as $section ) {
			if ( isset( $data['sections'][ $section ] ) && empty( $data['sections'][ $section ] ) ) {
				unset( $data['sections'][ $section ] );
			}
		}

		return $data;
	}

	protected function enable_debug_mode() {
		add_filter( 'ayyash_addons_service_api_is_debugging', '__return_true' );
		add_filter( 'https_local_ssl_verify', '__return_false' );
		add_filter( 'http_request_reject_unsafe_urls', '__return_false' );
		add_filter( 'ayyash_addons_service_api_is_local', '__return_false' );
		add_filter( 'http_request_reject_unsafe_urls', '__return_false' );
	}

	/**
	 * Check license status
	 *
	 * @return bool
	 */
	public function is_active() {
		return $this->license->is_valid();
	}

	public function get_license_data() {
		return $this->license->get_license();
	}

	public function support_email() {
		return 'support@absoluteplugins.com';
	}

	public function support_url() {
		return 'https://absoluteplugins.com/open-support-request/?utm_source=plugin-uninstaller&utm_medium=get-support&utm_campaign=uninstall-get-support';
	}

	public function terms_url() {
		return 'https://absoluteplugins.com/terms-of-service/?utm_source=tracker-notice&utm_medium=tracker-optin&utm_campaign=optin-terms';
	}

	public function privacy_policy_url() {
		return 'https://absoluteplugins.com/privacy-policy/?utm_source=tracker-notice&utm_medium=tracker-optin&utm_campaign=optin-policy';
	}

	/**
	 * Generate Support Ticket Email Template
	 *
	 * @return string
	 */
	public function support_ticket_template() {
		// dynamic variable format __INPUT_NAME__
		$license_data = '';

		if ( $this->license->is_valid() ) {
			$license_data = sprintf( '<br>API Key : %s', $this->license->get_key() );
		}
		/** @noinspection HtmlUnknownTarget */
		$template = '<div style="margin: 10px auto;"><p>Website : <a href="__WEBSITE__">__WEBSITE__</a><br>Plugin : %s (v.%s)%s</p></div>';
		$template = sprintf( $template, $this->client->getName(), $this->client->getProjectVersion(), $license_data );
		$template .= '<div style="margin: 10px auto;"><hr></div>';
		$template .= '<div style="margin: 10px auto;"><h3>__SUBJECT__</h3></div>';
		$template .= '<div style="margin: 10px auto;">__MESSAGE__</div>';
		$template .= '<div style="margin: 10px auto;"><hr></div>';
		$template .= sprintf(
			'<div style="margin: 50px auto 10px auto;"><p style="font-size: 12px;color: #009688">%s</p></div>',
			'Message Processed With AbsolutePlugin Service Library (v.' . $this->client->getClientVersion() . ')'
		);

		return $template;
	}

	/**
	 * Generate Support Ticket Ajax Response
	 *
	 * @return string
	 */
	public function support_response() {
		$response        = sprintf( '<h3>%s</h3>', esc_html__( 'Thank you -- Support Ticket Submitted.', 'ayyash-addons-pro' ) );
		$ticket_submitted = esc_html__( 'Your ticket has been successfully submitted.', 'ayyash-addons-pro' );
		$notification    = sprintf(
		/* translators: 1. 24 hours. */
			esc_html__( 'You will receive an email notification from "support@absoluteplugins.com" in your inbox within %s.', 'ayyash-addons-pro' ),
			'<strong>' . esc_html__( '24 hours', 'ayyash-addons-pro' ) . '</strong>'
		);
		$follow_up        = esc_html__( 'Please Follow the email and AbsolutePlugins Support Team will get back with you shortly.', 'ayyash-addons-pro' );
		$response        .= sprintf( '<p>%s %s %s</p>', $ticket_submitted, $notification, $follow_up );
		$doc_link         = sprintf( '<a class="button button-primary" href="https://absoluteplugins.com/docs/docs/ayyash-addons/?utm_source=plugin-uninstaller&utm_medium=get-support-popup&utm_campaign=read-docs" target="_blank"><span class="dashicons dashicons-media-document" aria-hidden="true"></span> %s</a>', esc_html__( 'Documentation', 'ayyash-addons-pro' ) );
		$vid_link         = sprintf( '<a class="button button-primary" href="https://go.absoluteplugins.com/to/video-tutorial" target="_blank"><span class="dashicons dashicons-video-alt3" aria-hidden="true"></span> %s</a>', esc_html__( 'Video Tutorials', 'ayyash-addons-pro' ) );
		$response        .= sprintf( '<p>%s %s</p>', $doc_link, $vid_link );
		$response        .= '<br><br><br>';
		$toc             = sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $this->terms_url() ), esc_html__( 'Terms & Conditions', 'ayyash-addons-pro' ) );
		$pp              = sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $this->privacy_policy_url() ), esc_html__( 'Privacy Policy', 'ayyash-addons-pro' ) );
		$policy          = sprintf(
		/* translators: 1. Terms page link, 2. Privacy policy page link */
			esc_html__( 'Please read our %1$s and %2$s', 'ayyash-addons-pro' ),
			$toc,
			$pp
		);
		$response        .= sprintf( '<p style="font-size: 12px;">%s</p>', $policy );

		return $response;
	}

	/**
	 * Set Error Response Message For Support Ticket Request
	 *
	 * @return string
	 */
	public function support_error_response() {
		return sprintf(
			'<div class="mui-error"><p>%s</p><p>%s</p><br><br><p style="font-size: 12px;">%s</p></div>',
			esc_html__( 'Something Went Wrong. Please Try The Support Ticket Form On Our Website.', 'ayyash-addons-pro' ),
			sprintf( '<a class="button button-primary" href="https://absoluteplugins.com/open-support-request/?utm_source=plugin-uninstaller&utm_medium=get-support-popup&utm_campaign=get-support" target="_blank">%s</a>', esc_html__( 'Get Support', 'ayyash-addons-pro' ) ),
			esc_html__( 'Support Ticket form will open in new tab in 5 seconds.', 'ayyash-addons-pro' )
		);
	}

	/**
	 * Check if tracking is enable
	 *
	 * @return bool
	 */
	public function is_tracking_allowed() {
		return Ayyash_Addons_Services::get_instance()->is_tracking_allowed();
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning is forbidden.', 'ayyash-addons-pro' ), '1.0.0' );
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserialization is forbidden.', 'ayyash-addons-pro' ), '1.0.0' );
	}
}

// End of file class-ayyash-addons-pro-services.php.

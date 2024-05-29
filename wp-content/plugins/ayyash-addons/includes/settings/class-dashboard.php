<?php
/**
 * Addons Dashboard Management
 *
 * Manage widgets, tools and staffs.
 *
 * @package Ayyash Addons
 * @version 1.0.0
 */

namespace AyyashAddons\Settings;

use AyyashAddons\Controls\Ayyash_Addons_Admin_Field_Base;
use AyyashAddons\Plugin;
use InvalidArgumentException;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

class Dashboard {

	/**
	 * Admin main page slug
	 * @var string
	 */
	const PAGE_SLUG = 'ayyash_addons';

	/**
	 * Admin menu capabilities
	 */
	const MENU_CAP = 'manage_options';

	/**
	 * Main Menu Page Hook.
	 *
	 * @var string
	 */
	protected static $page_hook;

	/**
	 * Tab Option Cache.
	 * @var array
	 */
	protected static $tab_options = array();

	/**
	 * Singleton instance.
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Create & Return Singleton instance.
	 *
	 * @return Dashboard
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Dashboard constructor.
	 */
	private function __construct() {

		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ], PHP_INT_MIN );
		add_action( 'admin_menu', [ __CLASS__, 'update_menu_items' ], PHP_INT_MAX );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue' ] );

		add_action( 'wp_ajax_ayyash_addons_save_widgets', [ __CLASS__, 'save_widgets' ] );
		add_action( 'wp_ajax_ayyash_addons_save_integrations', [ __CLASS__, 'save_integrations' ] );
	}

	public static function save_integrations() {
		//check_ajax_referer( 'ayyash_addons_dashboard' );
		//phpcs:disable
		if ( ! isset( $_POST['ayyash_addons_admin_page'] ) || isset( $_POST['ayyash_addons_admin_page'] ) && 'integrations' !== $_POST['ayyash_addons_admin_page'] ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid Request', 'ayyash-addons' ) ] );
			die();
		}

		$settings = [];

		unset(
			$_POST['ayyash_addons_admin_page'],
			$_POST['_wpnonce']
		);

		$options       = wp_unslash( $_POST );
		//phpcs:enable

		$errors        = [];
		$saved_options = static::get_tab_options( 'integrations' );

		foreach ( static::get_option_fields( 'integrations' ) as $section => $fields ) {
			foreach ( $fields as $field ) {
				if ( ! empty( $field['id'] ) ) {
					$field_id    = $field['id'];
					$field_value = isset( $options[ $section ][ $field_id ] ) ? $options[ $section ][ $field_id ] : '';
					// Sanitize "post" request of field.
					if ( ! isset( $field['sanitize'] ) ) {
						if ( is_array( $field_value ) ) {
							$settings[ $section ][ $field_id ] = wp_kses_post_deep( $field_value );
						} else {
							$settings[ $section ][ $field_id ] = wp_kses_post( $field_value );
						}
					} elseif ( isset( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {
						$settings[ $section ][ $field_id ] = call_user_func( $field['sanitize'], $field_value );
					} else {
						$settings[ $section ][ $field_id ] = $field_value;
					}
					// Validate "post" request of field.
					if ( isset( $field['validate'] ) && is_callable( $field['validate'] ) ) {
						$has_validated = call_user_func( $field['validate'], $field_value );
						if ( ! empty( $has_validated ) ) {
							$settings[ $section ][ $field_id ]    = ( isset( $saved_options[ $field_id ] ) ) ? $saved_options[ $field_id ] : '';
							$errors[ $section . '-' . $field_id ] = $has_validated;
						}
					}
				}
			}
		}

		$updated = static::save_tab_options( 'integrations', $settings );

		if ( $updated ) {
			$message = esc_html__( 'Settings Successfully Updated.', 'ayyash-addons' );
			if ( ! empty( $errors ) ) {
				$message = esc_html__( 'Some error occurred processing submitted data.', 'ayyash-addons' );
			}
			wp_send_json_success( [
				'message' => $message,
				'errors'  => $errors,
			] );
		} else {
			$message = esc_html__( 'Unable to save settings. Please try after sometime.', 'ayyash-addons' );
			if ( ! empty( $errors ) ) {
				$message = esc_html__( 'Some error occurred processing submitted data.', 'ayyash-addons' );
			}
			wp_send_json_error( [
				'message' => $message,
				'errors'  => $errors,
			] );
		}

		if ( ! wp_doing_ajax() ) {
			wp_safe_redirect( wp_get_referer() . '#integrations' ); // phpcs:ignore WordPressVIPMinimum.Security.ExitAfterRedirect.NoExit
		}

		die();
	}

	public static function save_widgets() {
		check_ajax_referer( 'ayyash_addons_dashboard' );

		if ( ! isset( $_POST['widgets'] ) || isset( $_POST['widgets'] ) && empty( $_POST['widgets'] ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid Request', 'ayyash-addons' ) ] );
			die();
		}

		$settings = [ 'widgets' => [] ];

		foreach ( $_POST['widgets'] as $widget => $status ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$widget = sanitize_title( $widget );
			$status = 'on' === $status ? 'on' : 'off';
			// update widget status
			$settings['widgets'][ $widget ] = $status;
		}

		$settings['updated'] = current_time( 'mysql' );

		$updated = update_option( 'ayyash_addons_settings', $settings, false );

		if ( $updated ) {
			wp_send_json_success( [ 'message' => esc_html__( 'Settings Successfully Updated.', 'ayyash-addons' ) ] );
		} else {
			wp_send_json_error( [ 'message' => esc_html__( 'Unable to save settings. Please try after sometime.', 'ayyash-addons' ) ] );
		}

		if ( ! wp_doing_ajax() ) {
			wp_safe_redirect( wp_get_referer() . '#widgets' ); // phpcs:ignore WordPressVIPMinimum.Security.ExitAfterRedirect.NoExit
		}
		die();
	}

	public static function is_page() {
		return ( isset( $_GET['page'] ) && ( self::PAGE_SLUG === $_GET['page'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Registers Admin Menus.
	 * hooked to admin_menu
	 *
	 * @return void
	 */
	public static function register_menu() {
		global $menu;

		// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited

		self::$page_hook = add_menu_page(
			__( 'Ayyash Addons Dashboard', 'ayyash-addons' ),
			__( 'Ayyash Addons', 'ayyash-addons' ),
			self::MENU_CAP,
			self::PAGE_SLUG,
			[ __CLASS__, 'render_dashboard' ],
			"data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNzYiIGhlaWdodD0iNzUiIHZpZXdCb3g9IjAgMCA3NiA3NSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwXzgwOV8yNjApIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik00NC40NjE2IDQ2LjMzNjZDMjguNjI2MyA1OS4xODMxIDExLjY3NyA2NC41Mjk2IDYuNjA4OTMgNTguMjczNEMxLjU0MDg2IDUyLjAzNTggMTAuMjY2MSAzNi41NTMyIDI2LjEwMTUgMjMuNzA2NkMyNi4zMzY2IDIzLjUwODYgMjYuNTc4IDIzLjMxMDYgMjYuODI1NSAyMy4xMTI2TDI4LjQyMiAxOS42NDFDMjcuMDM1OSAyMC42NTU5IDI1LjY2MjEgMjEuNzE0IDI0LjMwMDggMjIuODE1NUMxMy42MDc3IDMxLjQ4NTEgNS44MTA2NiA0MS40MTcgMi4yNjQ4NyA0OS45MzgxQzAuODkxMTAzIDQ2LjA1ODEgMC4xNDg1MjkgNDEuODYyNiAwLjE0ODUyOSAzNy40OTk5QzAuMTQ4NTI5IDE2Ljg1NjQgMTYuODc1IDAuMTI5ODgzIDM3LjUxODYgMC4xMjk4ODNDNDUuMjA0MiAwLjEyOTg4MyA1Mi4zNTE1IDIuNDUwNDMgNTguMjkyMSA2LjQ0MTc2QzUzLjM3MjUgNi45MjQ0NCA0Ny41ODA1IDguNzI1MTggNDEuNTA5OSAxMS42OTU1TDQyLjI1MjUgMTMuMzEwNkM1Mi4xNDczIDguNTU4MSA2MC42MTI2IDcuNjY3MDEgNjMuOTM1NyAxMS43Njk3QzY5LjAwMzcgMTguMDI1OSA2MC4yNzg1IDMzLjQ5IDQ0LjQ2MTYgNDYuMzM2NlpNNDkuOTc1MyAzNi41MzQ2TDQ5LjEyMTMgMzQuNjQxTDM2Ljc5NDYgNy44MTU1M0wyNC40Njc4IDM0LjY0MUwxMy44Njc2IDU3LjYyMzdDMTcuMDA1IDU3LjI3MSAyMC42ODA3IDU2LjE5NDIgMjQuNjE2NCA1NC40NDkyTDM2Ljc5NDYgMjguMDUwN0w0My40NDA2IDQyLjUzMDlDNDUuNzk4MyA0MC41NjMxIDQ3Ljk4ODkgMzguNTU4MSA0OS45NzUzIDM2LjUzNDZaIiBmaWxsPSJ1cmwoI3BhaW50MF9saW5lYXJfODA5XzI2MCkiLz4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik03My42NDQ4IDI3LjkyMUM3NC40NjE2IDMwLjk4NDEgNzQuODg4NiAzNC4xOTU4IDc0Ljg4ODYgMzcuNTAwMkM3NC44ODg2IDU4LjE0MzggNTguMTYyMSA3NC44NzAzIDM3LjUxODYgNzQuODcwM0MzMi4yNjQ5IDc0Ljg3MDMgMjcuMjcxIDczLjc5MzUgMjIuNzQxMyA3MS44MjU3QzE0LjQwNTkgNzMuOTk3NyA3LjU3NDI2IDczLjI1NTIgNC4xNzY5OCA2OS4wNzgyQzMuNTY0MzYgNjguMzE3IDMuMTAwMjUgNjcuNDgxNyAyLjc0NzUzIDY2LjU3MkMxMC4wMjQ4IDc0LjM1MDUgMzAuNTU2OSA2OC42NTEyIDQ5LjE5NTUgNTMuNTIxM0M2Ni41MzQ3IDM5LjQ0OTUgNzYuMjQzOCAyMi4xMTA0IDcyLjYwNTIgMTIuNzE2OEM3Mi43NTM3IDEyLjg3NzcgNzIuODk2IDEzLjA0NDggNzMuMDMyMiAxMy4yMThDNzUuODE2OCAxNi42NTI0IDc1Ljg1NCAyMS44ODc2IDczLjY0NDggMjcuOTIxWk02MS40Mjk1IDYxLjQ2NjhMNTYuMzYxNCA1MC40MjFDNTQuMzE5MyA1Mi40NDQ1IDUyLjEyODcgNTQuNDMwOSA0OS43ODk2IDU2LjM0M0w1Mi4xNDczIDYxLjQ2NjhINjEuNDI5NVoiIGZpbGw9InVybCgjcGFpbnQxX2xpbmVhcl84MDlfMjYwKSIvPgo8L2c+CjxkZWZzPgo8bGluZWFyR3JhZGllbnQgaWQ9InBhaW50MF9saW5lYXJfODA5XzI2MCIgeDE9IjMyLjc2MjciIHkxPSIwLjEyOTg4MyIgeDI9IjMyLjc2MjciIHkyPSI2MC44OTAyIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+CjxzdG9wIHN0b3AtY29sb3I9IiMwNjRBRjMiLz4KPHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjMDhBM0VFIi8+CjwvbGluZWFyR3JhZGllbnQ+CjxsaW5lYXJHcmFkaWVudCBpZD0icGFpbnQxX2xpbmVhcl84MDlfMjYwIiB4MT0iMzguOTgzMyIgeTE9IjEyLjcxNjgiIHgyPSIzOC45ODMzIiB5Mj0iNzQuODcwMyIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPgo8c3RvcCBzdG9wLWNvbG9yPSIjMDY0QUYzIi8+CjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iIzA4QTNFRSIvPgo8L2xpbmVhckdyYWRpZW50Pgo8Y2xpcFBhdGggaWQ9ImNsaXAwXzgwOV8yNjAiPgo8cmVjdCB3aWR0aD0iNzUuMzcxMyIgaGVpZ2h0PSI3NSIgZmlsbD0id2hpdGUiLz4KPC9jbGlwUGF0aD4KPC9kZWZzPgo8L3N2Zz4=",
			'58.6'
		);

		$tabs = self::get_registered_tabs();

		if ( is_array( $tabs ) ) {
			$defaults = [
				'menu_title' => '',
				'page_title' => '',
				'renderer'   => '',
			];

			foreach ( $tabs as $key => $data ) {
				$data = wp_parse_args( $data, $defaults );
				if ( empty( $data['renderer'] ) || ! is_callable( $data['renderer'] ) ) {
					continue;
				}

				if ( ! empty( $page_title ) ) {
					$page_title = $data['page_title'];
				} elseif ( ! empty( $data['menu_title'] ) ) {
					$page_title = $data['menu_title'];
				} else {
					continue;
				}

				// Add submenu.
				add_submenu_page(
					self::PAGE_SLUG,
					sprintf(
					/* translators: 1. Page Title */
						__( '%s - Ayyash Addons', 'ayyash-addons' ),
						$page_title
					),
					$data['menu_title'],
					'manage_options',
					self::PAGE_SLUG . '#' . $key,
					[ __CLASS__, 'render_tab_content' ] // keep a callable function so wp render proper page links..
				);
			}
		}

		// A separator!
		$menu['58.555'] = [
			'',
			'read',
			'separator-ayyash-addons',
			'',
			'wp-menu-separator ayyash-addons',
		];

		// phpcs:enable
	}

	public static function update_menu_items() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		global $submenu;
		if ( isset( $submenu[ self::PAGE_SLUG ] ) ) {
			$menu = $submenu[ self::PAGE_SLUG ];
			array_shift( $menu );
			$submenu[ self::PAGE_SLUG ] = $menu; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
	}

	public static function enqueue() {

		if ( apply_filters( 'ayyash_addons/dashboard/load_google_font', true ) ) {
			wp_enqueue_style( 'roboto', 'https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap', [], AYYASH_ADDONS_VERSION );
		}

		// wp_enqueue_script( 'wp-hooks' );
		// wp_enqueue_script( 'wp-api-fetch' );
		wp_enqueue_script( 'wp-polyfill' );
		wp_enqueue_script( 'wp-util' );

		$css = [
			'jquery.fancybox'     => 'assets/dist/css/library/jquery.fancybox',

			'ayyash_addons_admin' => 'assets/dist/css/admin',
		];
		$js  = [
			'sweetalert'          => 'assets/dist/js/libraries/sweetalert2.all',
			'jquery.fancybox'     => [ 'assets/dist/js/libraries/jquery.fancybox', 'jquery' ],
			'beefup'              => [ 'assets/dist/js/libraries/jquery.beefup', 'jquery' ],
			'serializeJSON'       => [ 'assets/dist/js/libraries/jquery.serializejson', 'jquery' ],
			'ayyash_addons_admin' => [ 'assets/dist/js/admin', [ 'jquery', 'sweetalert' ] ],
		];

		foreach ( $css as $key => $style ) {
			$style = $style . '.css';
			wp_enqueue_style( $key, Plugin::plugin_url( $style ), [], Plugin::asset_version( $style ) );
		}

		foreach ( $js as $key => $script ) {
			$deps = [];
			if ( is_array( $script ) ) {
				list( $script, $deps ) = $script;
				if ( is_string( $deps ) ) {
					$deps = [ $deps ];
				}
			}
			$script = $script . '.js';
			wp_enqueue_script( $key, Plugin::plugin_url( $script ), $deps, Plugin::asset_version( $script ), true );
		}

		wp_localize_script( 'ayyash_addons_admin', 'AYYASH_ADDONS_ADMIN_DASHBOARD', [
			'nonce' => wp_create_nonce( 'ayyash_addons_dashboard' ),
			'i18n'  => [
				'okay'                => esc_html__( 'Okay', 'ayyash-addons' ),
				'cancel'              => esc_html__( 'Cancel', 'ayyash-addons' ),
				'submit'              => esc_html__( 'Submit', 'ayyash-addons' ),
				'success'             => esc_html__( 'Success', 'ayyash-addons' ),
				'warning'             => esc_html__( 'Warning', 'ayyash-addons' ),
				'error'               => esc_html__( 'Error', 'ayyash-addons' ),
				'something_wrong'     => esc_html__( 'Something went wrong! Please try again after sometime.', 'ayyash-addons' ),
				'e404'                => esc_html__( 'Requested Resource Not Found!', 'ayyash-addons' ),
				'are_you_sure'        => esc_html__( 'Are You Sure?', 'ayyash-addons' ),
				'confirm_disable_all' => esc_html__( 'Are You Sure You Want To Disable All Widgets?', 'ayyash-addons' ),
				'confirm_enable_all'  => esc_html__( 'Are You Sure You Want To Enable All Widgets?', 'ayyash-addons' ),
			],
		] );
	}

	protected static function get_registered_tabs() {
		$tabs = [
			'dashboard' => [
				'menu_title' => esc_html__( 'Dashboard', 'ayyash-addons' ),
				'renderer'   => [ __CLASS__, 'render_tab_content' ],
				'position'   => - 1,
			],
			'widgets'   => [
				'menu_title' => esc_html__( 'Widgets', 'ayyash-addons' ),
				'renderer'   => [ __CLASS__, 'render_tab_content' ],
				'position'   => 10,
			],

			/*'integrations' => [
				'menu_title' => esc_html__( 'Integrations', 'ayyash-addons' ),
				'renderer'   => [ __CLASS__, 'render_tab_content' ],
				'position'   => 50,
			],*/
			'support'   => [
				'menu_title' => esc_html__( 'Support', 'ayyash-addons' ),
				'renderer'   => [ __CLASS__, 'render_tab_content' ],
				'position'   => 70,
			],
			/*'tools'        => [
				'menu_title' => esc_html__( 'Tools', 'ayyash-addons' ),
				'renderer'   => [ __CLASS__, 'render_tab_content' ],
				'position'   => 50,
			],*/
		];

		if ( ! ayyash_addons_has_pro() ) {
			$tabs['pro-features'] = [
				'menu_title' => '<span class="dashicons dashicons-star-filled" style="font-size: 17px"></span> ' . esc_html__( 'Go Pro', 'ayyash-addons' ),
				'page_title' => esc_html__( 'Go Pro', 'ayyash-addons' ),
				'renderer'   => [ __CLASS__, 'render_tab_content' ],
				'position'   => 100,
			];
		}

		uasort( $tabs, 'ayyash_addons_usort_position' );

		return apply_filters( 'ayyash_addons/dashboard/tabs', $tabs );
	}

	protected static function get_option_section( $tab ) {
		$sections = [
			'integrations' => [
				[
					'title' => __( 'Mailchimp', 'ayyash-addons' ),
					'id'    => 'mailchimp',
				],
			],
		];

		return isset( $sections[ $tab ] ) ? $sections[ $tab ] : [];
	}

	protected static function get_option_fields( $tab, $section = '' ) {

		/** @noinspection HtmlUnknownTarget */
		$fields = [
			'integrations' => [
				'mailchimp' => [
					[
						'id'         => 'api_key',
						'title'      => __( 'API Key', 'ayyash-addons' ),
						'type'       => 'text',
						'desc'       => sprintf(
						/* translators: 1. Mailchimp Admin API Creation Page URL. */
							__( 'Get Your Mailchimp API Key <a href="%s">Here</a>', 'ayyash-addons' ),
							esc_url( 'https://admin.mailchimp.com/account/api/' )
						),
						'attributes' => [ 'type' => 'password' ],
						'validate'   => [ __CLASS__, 'validate_mailchimp_api' ],
					],
					[
						'id'          => 'audience_list',
						'title'       => __( 'Default Audience List', 'ayyash-addons' ),
						'type'        => 'select',
						'placeholder' => __( 'Select Default Audience List', 'ayyash-addons' ),
						'options'     => get_option( 'ayyash_addons_mc_audience_list', [
							'' => __( 'No List Available', 'ayyash-addons' ),
						] ),
					],
				],
			],
		];

		$tab_fields = isset( $fields[ $tab ] ) ? $fields[ $tab ] : [];
		if ( ! $section ) {
			return $tab_fields;
		}

		return isset( $tab_fields[ $section ] ) ? $tab_fields[ $section ] : [];
	}

	protected static function save_tab_options( $tab, $options = [] ) {
		$options['version']        = AYYASH_ADDONS_VERSION;
		$options['updated']        = current_time( 'mysql' );
		self::$tab_options[ $tab ] = $options;

		return update_option( 'ayyash-addons-' . $tab . '-options', ayyash_addons_encrypt( $options ) );
	}

	/**
	 * @param string $tab
	 *
	 * @return false|mixed
	 */
	public static function get_tab_options( $tab ) {
		if ( ! isset( self::$tab_options[ $tab ] ) ) {
			self::$tab_options[ $tab ] = ayyash_addons_decrypt( get_option( 'ayyash-addons-' . $tab . '-options', '' ) );
		}

		return isset( self::$tab_options[ $tab ] ) ? self::$tab_options[ $tab ] : [];
	}

	/**
	 * @param string $tab
	 * @param string $key
	 *
	 * @return false|mixed
	 */
	public static function get_tab_section_option( $tab, $key ) {
		self::get_tab_options( $tab );

		return isset( self::$tab_options[ $tab ][ $key ] ) ? self::$tab_options[ $tab ][ $key ] : [];
	}

	protected static function render_option_fields( $tab ) {
		$sections = self::get_option_section( $tab );
		?>
		<div class="ayyash-addons-admin-options--sections ayyash-addons-admin-options--sections__<?php echo esc_attr( $tab ); ?>">
			<input type="hidden" name="ayyash_addons_admin_page" value="<?php echo esc_attr( $tab ); ?>">
			<?php
			if ( is_array( $sections ) && ! empty( $sections ) ) {
				foreach ( $sections as $section ) {
					if ( empty( $section['id'] ) ) {
						continue;
					}

					$sec_id       = $section['id'];
					$fields       = isset( $section['fields'] ) && is_array( $section['fields'] ) ? $section['fields'] : [];
					$fields       = array_merge( $fields, self::get_option_fields( $tab, $sec_id ) );
					$section_slug = ( ! empty( $section['title'] ) ) ? sanitize_title( $section['title'] ) : '';
					$class        = 'ayyash-addons-admin-options--section';
					$class        .= isset( $section['class'] ) && $section['class'] ? ' ' . $section['class'] : '';

					echo '<div class="' . esc_attr( $class ) . '" data-section-id="' . esc_attr( $section_slug ) . '">';
					if ( ! empty( $section['title'] ) ) {
						?>
						<div class="ayyash-addons-admin-options--section-title">
							<h3>
								<?php if ( isset( $section['icon'] ) && $section['icon'] ) { ?>
									<i class="ayyash-addons-admin-icon <?php echo esc_attr( $section['icon'] ); ?>" aria-hidden="true"></i>
								<?php } ?>
								<?php echo esc_html( $section['title'] ); ?>
							</h3>
						</div>
						<?php
					}

					if ( isset( $section['description'] ) && $section['description'] ) {
						?>
						<div class="ayyash-addons-admin-options--field ayyash-addons-admin-options--section-description">
							<?php wp_kses_post_e( $section['description'] ); ?>
						</div>
						<?php
					}

					$values = self::get_tab_section_option( $tab, $sec_id );
					if ( ! empty( $fields ) ) {
						foreach ( $fields as $field ) {

							$field = wp_parse_args( $field, [
								'id'       => '',
								'title'    => '',
								'icon'     => '',
								'subtitle' => '',
								'type'     => '',
								'default'  => false,
								'value'    => '',
								'section'  => $sec_id,
							] );

							if ( empty( $field['id'] ) ) {
								continue;
							}

							if ( ! empty( $field['type'] ) ) {
								// Field Data.
								$field['value'] = isset( $values[ $field['id'] ] ) ? $values[ $field['id'] ] : $field['default'];
								$field_type     = ! empty( $field['type'] ) ? $field['type'] : '';

								/**
								 * Subclass of ABSP_Admin_Field_Base
								 * @see ABSP_Admin_Field_Base
								 */
								$class_name = '\AyyashAddons\Controls\Fields\ABSP_Admin_Field_' . ucfirst( $field_type );

								// Field CSS Class.
								$class = 'ayyash-addons-admin-options--field';
								$class .= ( ! empty( $field['class'] ) ) ? ' ' . esc_attr( $field['class'] ) : '';
								$class .= ( ! empty( $field['pseudo'] ) ) ? ' ayyash-addons-admin-options--pseudo-field' : '';
								$class .= $field_type ? ' ayyash-addons-admin-options--field-' . $field_type : '';

								?>
								<div class="<?php echo esc_attr( $class ); ?>">
									<?php if ( $field['title'] ) { ?>
										<div class="ayyash-addons-admin-options--title">
											<h4><?php echo esc_html( $field['title'] ); ?></h4>
											<?php if ( isset( $field['subtitle'] ) && $field['subtitle'] ) { ?>
												<div
													class="ayyash-addons-admin-options--subtitle-text"><?php wp_kses_post_e( $field['subtitle'] ); ?></div>
											<?php } ?>
										</div>
										<?php
									}
									if ( $field['title'] ) { ?>
									<div class="ayyash-addons-admin-options--fieldset">
										<?php
										}

										if ( class_exists( $class_name ) ) {
											$instance = new $class_name( $field, $field['value'], $sec_id );
											$instance->render();
										} else {
											?>
											<div class="ayyash-addons-admin-options--no-fields">
												<p><?php esc_html_e( 'Field not found!', 'ayyash-addons' ); ?></p>
											</div>
											<?php
										}

										if ( $field['title'] ) { ?>
									</div>
								<?php } ?>
									<div class="clear"></div>
								</div>
								<?php
							} else {
								?>
								<div class="ayyash-addons-admin-options--no-fields">
									<p><?php esc_html_e( 'Field not found!', 'ayyash-addons' ); ?></p>
								</div>
								<?php
							}
						}
					} else {
						?>
						<div class="ayyash-addons-admin-options--no-fields">
							<p><?php esc_html_e( 'No data available.', 'ayyash-addons' ); ?></p>
						</div>
						<?php
					}
					echo '</div>';
				}
			}
			?>
		</div>
		<?php
	}

	protected static function load_template( $template, $data = [] ) {
		$file = AYYASH_ADDONS_PATH . 'templates/admin/' . $template . '.php';
		if ( is_readable( $file ) ) {
			if ( is_array( $data ) && ! empty( $data ) ) {
				extract( $data );
			}

			include( $file );
		}
	}

	protected static function load_tab( $tab, $data = [] ) {
		self::load_template( 'dashboard-tab-' . $tab, $data );
	}

	public static function render_dashboard() {
		self::load_template( 'dashboard' );
	}

	public static function render_tab_content( $tab = '', $data = [] ) {
		if ( $tab ) {
			self::load_tab( $tab, $data );
		}
	}

	public static function get_changelogs() {
		/**
		 * schema
		 * @var array $changelogs {
		 * @type array $changelog {
		 * @type string $version Eg. '1.0.0'.
		 * @type string|int $date release date.
		 * @type string $url download url for the version.
		 * @type array $logs {
		 *       log data.
		 *       'badge', 'message'
		 *     }
		 *   }
		 * }
		 */
		return [];
	}

	public static function get_social_links() {
		return [
			[
				'icon'  => 'dashicons dashicons-facebook-alt',
				'url'   => '#',
				'label' => __( 'Facebook', 'ayyash-addons' ),
			],
			[
				'icon'  => 'dashicons dashicons-twitter',
				'url'   => '#',
				'label' => __( 'Twitter', 'ayyash-addons' ),
			],
			[
				'icon'  => 'dashicons dashicons-instagram',
				'url'   => '#',
				'label' => __( 'Instagram', 'ayyash-addons' ),
			],
			[
				'icon'  => 'dashicons dashicons-youtube',
				'url'   => '#',
				'label' => __( 'YouTube', 'ayyash-addons' ),
			],
		];
	}

	public static function get_badges_translations() {
		return [
			'map'  => [
				'improve'     => 'improvement',
				'improvement' => 'improvement',
				'fixed'       => 'fix',
				'fix'         => 'fix',
				'bug'         => 'fix',
				'bug-fix'     => 'fix',
				'new'         => 'new',
				'feature'     => 'new',
				'added'       => 'new',
			],
			'i18n' => [
				'improvement' => __( 'Improvement', 'ayyash-addons' ),
				'fix'         => __( 'Fixed', 'ayyash-addons' ),
				'new'         => __( 'Feature', 'ayyash-addons' ),
			],
		];
	}

	public static function render_notices() {
		if ( self::is_page() ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
	}

	public static function validate_mailchimp_api( $value ) {
		try {
			$mc = new MailChimp( $value );
			$mc->load();
			$resp = $mc->ping();
			if ( is_wp_error( $resp ) ) {
				return $resp->get_error_message();
			} else {
				$data = $mc->fetch_audience_list();
				if ( ! is_wp_error( $data ) ) {
					$list = [];
					foreach ( $data as $item ) {
						$list[ $item->id ] = $item->name;
					}
					update_option( 'ayyash_addons_mc_audience_list', $list );
				}
			}

			return ''; // return empty if ok. or error.
		} catch ( InvalidArgumentException $e ) {
			return $e->getMessage();
		}
	}
}

// End of file dashboard.php.

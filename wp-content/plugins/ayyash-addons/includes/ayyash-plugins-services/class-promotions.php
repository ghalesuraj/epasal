<?php
/**
 * AbsolutePlugins Services Promo Handler.
 *
 * @link https://github.com/AbsolutePlugins/AyyashPluginsServices
 * @version 1.0.0
 * @package AyyashPluginsServices
 * @license MIT
 */

namespace AyyashAddons\AyyashPluginsServices;
;;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Promotions
 */
class Promotions {

	/**
	 * AyyashPluginsServices\Client
	 *
	 * @var Client
	 */
	protected $client;

	/**
	 * Promotions
	 * @var bool|object[]
	 */
	private $promotions = false;

	/**
	 * List of hidden promotions for current user
	 * @var array
	 */
	private $hidden_promotions;

	/**
	 * Current User Id
	 * @var int
	 */
	private $currentUser = 0;

	protected $cache_ttl;

	private $promo_source;

	/**
	 * Promotions constructor.
	 *
	 * @param Client $client The Client.
	 * @param string $source Data Source URL
	 * @param string $cache_ttl Promo Data Cache Expiration & Checking Interval.
	 *
	 * @return void
	 */
	public function __construct( Client $client, $source = null ) {
		$this->client = $client;

		if ( null !== $source ) {
			$this->set_source( $source );
		}

		$this->cache_ttl = 12 * HOUR_IN_SECONDS;
	}

	public function set_source( $source ) {
		if ( esc_url_raw( $source, [ 'https' ] ) ) {
			$this->promo_source = esc_url_raw( $source, [ 'https' ] );
		}

		return $this;
	}

	public function set_cache_ttl( $seconds ) {
		$seconds = absint( $seconds );
		if ( $seconds >= 12 * HOUR_IN_SECONDS ) {
			$this->cache_ttl = $seconds;
		}

		return $this;
	}

	/**
	 * Init Promotions
	 * @return void
	 */
	public function init() {
		add_action( 'admin_init', [ $this, '__init_internal' ] );
	}

	/**
	 * Set environment variables and init internal hooks
	 * @return void
	 */
	public function __init_internal() {
		$this->currentUser       = get_current_user_id();
		$this->hidden_promotions = (array) get_user_option( $this->client->getSlug() . '_hidden_promos', $this->currentUser );
		$this->promotions        = $this->__get_promos();
		// only run if there is active promotions.
		if ( count( $this->promotions ) ) {
			add_action( 'admin_notices', [ $this, '__show_promos' ] );
			add_action( 'wp_ajax_ayyash_addons_srv_dismiss_promo', [ $this, '__dismiss_promo' ] );
			add_action( 'admin_print_styles', [ $this, '__get_promo_styles' ], 99 );
			add_action( 'admin_enqueue_scripts', [ $this, '__enqueue_deps' ] );
			add_action( 'admin_print_footer_scripts', [ $this, '__get_promo_scripts' ] );
		}
	}

	/**
	 * Render Promotions
	 * @return void
	 */
	public function __show_promos() {
		foreach ( $this->promotions as $promotion ) {
			$wrapperStyles = '';
			$buttonStyles  = '';
			$noticeClasses = 'notice notice-success ayyash-addons-srv-promo';
			$has_columns   = isset( $promotion->button, $promotion->logo );

			if ( ! (
				! isset( $promotion->dismissible ) ||
				( isset( $promotion->dismissible ) && 0 == $promotion->dismissible )
			) ) {
				$noticeClasses .= ' is-dismissible';
			}

			if ( isset( $promotion->wrapperStyle ) ) {
				if ( isset( $promotion->wrapperStyle->color ) ) {
					$wrapperStyles .= 'color: ' . esc_attr( $promotion->wrapperStyle->color ) . ';';
				}
				if ( isset( $promotion->wrapperStyle->padding ) ) {
					$wrapperStyles .= 'padding: ' . esc_attr( $promotion->wrapperStyle->padding ) . ';';
				}
				if ( isset( $promotion->wrapperStyle->backgroundColor ) ) {
					$wrapperStyles .= 'background-color: ' . esc_attr( $promotion->wrapperStyle->backgroundColor ) . ';';
				}
				if ( isset( $promotion->wrapperStyle->backgroundImage ) ) {
					$wrapperStyles .= 'background-image: url("' . esc_url( $promotion->wrapperStyle->backgroundImage ) . '");';
				}
				if ( isset( $promotion->wrapperStyle->backgroundRepeat ) ) {
					$wrapperStyles .= 'background-repeat: ' . esc_attr( $promotion->wrapperStyle->backgroundRepeat ) . ';';
				}
				if ( isset( $promotion->wrapperStyle->backgroundSize ) ) {
					$wrapperStyles .= 'background-size: ' . esc_attr( $promotion->wrapperStyle->backgroundSize ) . ';';
				}

			}

			if ( isset( $promotion->button ) ) {
				if ( isset( $promotion->button->backgroundColor ) ) {
					$buttonStyles .= 'background-color: ' . $promotion->button->backgroundColor . ';border-color: ' . $promotion->button->backgroundColor . ';';
				}
				if ( isset( $promotion->button->color ) ) {
					$buttonStyles .= 'color: ' . $promotion->button->color . ';';
				}
			}
			?>
			<div class="<?php echo esc_attr( $noticeClasses ); ?> " id="<?php echo esc_attr( $promotion->hash ); ?>"
				 data-nonce="<?php echo esc_attr( wp_create_nonce( 'ayyash-addons-srv-dismiss-promo' ) ); ?>"
				 style="<?php echo esc_attr( $wrapperStyles ); ?>">
				<div class="ayyash-addons-srv-promo-wrap<?php if ( ! $has_columns ) {
					echo ' no-column';
				} ?>">
					<?php if ( isset( $promotion->logo ) && ! empty( $promotion->logo ) ) { ?>
						<div class="ayyash-addons-srv-promo-logo ayyash-addons-srv-promo-column">
							<img src="<?php echo esc_url( $promotion->logo->src ); ?>"
								 alt="<?php echo esc_attr( $promotion->logo->alt ); ?>">
						</div>
					<?php } ?>
					<div class="ayyash-addons-srv-details<?php if ( $has_columns ) {
						echo ' ayyash-addons-srv-promo-column';
					} ?>">
						<?php echo wp_kses_post( $promotion->content ); ?>
					</div>
					<?php if ( isset( $promotion->button ) && ! empty( $promotion->button ) ) { ?>
						<div class="ayyash-addons-srv-promo-btn-container ayyash-addons-srv-promo-column">
							<?php if ( isset( $promotion->button->url ) && isset( $promotion->button->label ) ) { ?>
								<a href="<?php echo esc_url( $promotion->button->url ); ?>"
								   class="button ayyash-addons-srv-promo-btn"
								   style="<?php echo esc_attr( $buttonStyles ); ?>"
								   target="_blank"
								   rel="noopener"
								><?php echo wp_kses_post( $promotion->button->label ); ?></a>
								<?php
							}
							if ( isset( $promotion->button->after ) && ! empty( $promotion->button->after ) ) {
								echo wp_kses_post( $promotion->button->after );
							}
							?>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Get Promotion Data
	 * Cache First then fetch source url for json data.
	 * @return array
	 */
	private function __get_promos() {
		$promos = get_transient( $this->client->getSlug() . '_cached_promos' );
		if ( empty( $promos ) ) {

			// Fetch promotions data from json source.
			$args = [
				'timeout' => 15, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
			];

			if ( null !== $this->promo_source ) {
				$args['url'] = $this->promo_source;
			} else {
				$args['route'] = 'promotions';
			}

			$response = $this->client->request( $args );

			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				// Cache Something, reduce request.
				$promos = '[]';
			} else {
				$promos = wp_remote_retrieve_body( $response );
			}

			// Cache data.
			set_transient( $this->client->getSlug() . '_cached_promos', $promos, $this->cache_ttl );
		}

		// Decode to array.
		$promos = json_decode( $promos );

		// Filter promotions by date.
		$promos = array_filter( $promos, [ $this, '__is_promo_active' ] );

		if ( ! empty( $promos ) ) {
			// Filter promotions by list of hidden promotions by the user.
			$promos = array_filter( $promos, [ $this, '__is_promo_hidden' ] );
		}

		return $promos;
	}

	/**
	 * Check if promotion is active by date.
	 * must have start and end property
	 *
	 * @param object $promo {   the promo object.
	 *      Single Promo Object
	 *
	 * @type string $content string. required
	 * @type string $start valid timestamp. required
	 * @type string $end valid timestamp. required
	 * }
	 *
	 * @return bool
	 */
	public function __is_promo_active( $promo ) {
		$ct = current_time( 'timestamp' ); // phpcs:ignore

		return ( ! empty( $promo->content ) && strtotime( $promo->start ) < $ct && $ct < strtotime( $promo->end ) );
	}

	/**
	 * Check if promo is hidden by current user
	 *
	 * @param object $promo {   the promo object.
	 *      Single Promo Object
	 *
	 * @type string $hash valid unique hash for a promo
	 * }
	 *
	 * @return bool         true if promo is hidden by user
	 */
	public function __is_promo_hidden( $promo ) {
		return ! in_array( $promo->hash, $this->hidden_promotions );
	}

	/**
	 * Js Dependencies
	 * @return void
	 */
	public function __enqueue_deps() {
		wp_enqueue_script( 'wp-util' );
		wp_enqueue_script( 'jquery' );
	}

	/**
	 * Script for hiding promo on user click
	 * @return void
	 */
	public function __get_promo_scripts() {
		?>
		<!--suppress ES6ConvertVarToLetConst -->
		<script>
			(function ($) {
				$('body').on('click', '.ayyash-addons-srv-promo .notice-dismiss', function (e) {
					e.preventDefault();
					var $parent = $(this).closest('.ayyash-addons-srv-promo');
					wp.ajax.post('ayyash_addons_srv_dismiss_promo', {
						dismissed: true,
						hash: $parent.attr('id'),
						_wpnonce: $parent.data('nonce'),
					});
				});
			})(jQuery);
		</script>
		<?php
	}

	/**
	 * Global Promo Styles
	 * @return void
	 */
	public function __get_promo_styles() {
		?>
		<!--suppress CssUnusedSymbol -->
		<style>
			.ayyash-addons-srv-promo {
				border: none;
				padding: 15px 0;
			}

			.ayyash-addons-srv-promo-wrap {
				display: flex;
				justify-content: center;
				align-items: center;
				text-align: center;
				color: inherit;
				max-width: 1820px;
				margin: 0 auto;
			}

			.ayyash-addons-srv-promo-wrap.no-column {
				display: block;
			}

			.ayyash-addons-srv-promo-column.ayyash-addons-srv-promo-logo {
				flex: 0 0 25%;
			}

			.ayyash-addons-srv-promo-column.ayyash-addons-srv-promo-logo img {
				height: 48px;
				width: auto;
			}

			.ayyash-addons-srv-details {
				display: block;
			}

			.ayyash-addons-srv-details h3 {
				color: inherit;
				font-size: 30px;
				margin: 12px 0;
			}

			.ayyash-addons-srv-details p {
				color: inherit;
				font-size: 15px;
			}

			.ayyash-addons-srv-promo-column.ayyash-addons-srv-details {
				flex: 0 0 50%;
			}

			.ayyash-addons-srv-promo-column.ayyash-addons-srv-promo-btn-container {
				flex: 0 0 25%;
			}

			.ayyash-addons-srv-promo-wrap .ayyash-addons-srv-promo-btn {
				position: relative;
				padding: 15px;
				border-radius: 30px;
				font-size: 15px;
				font-weight: 700;
				display: block;
				color: inherit;
				text-decoration: none;
				max-width: 200px;
				margin: 0 auto;
				line-height: normal;
				height: auto;
				box-shadow: 1px 2px 0 rgba(0, 0, 0, 0.1);
			}

			.ayyash-addons-srv-promo-wrap .ayyash-addons-srv-promo-btn:focus,
			.ayyash-addons-srv-promo-wrap .ayyash-addons-srv-promo-btn:hover,
			.ayyash-addons-srv-promo-wrap .ayyash-addons-srv-promo-btn:active {
				box-shadow: inset 3px 4px 6px 0 rgba(1, 9, 12, 0.25);
			}

			.ayyash-addons-srv-promo-wrap .ayyash-addons-srv-promo-btn:active {
				top: 1px;
			}

			@media screen and (max-width: 1200px) {
				.ayyash-addons-srv-promo-wrap {
					display: block;
					overflow: hidden;
				}

				.ayyash-addons-srv-promo-column .ayyash-addons-srv-promo-logo {
					width: 100%;
					margin: 0 auto;
				}

				.ayyash-addons-srv-promo-column .ayyash-addons-srv-details {
					width: 68%;
					float: left;
					margin-right: 4%;
					margin-top: 32px;
				}

				.ayyash-addons-srv-promo-column.ayyash-addons-srv-promo-btn-container {
					width: 28%;
					float: right;
					margin-top: 42px;
				}
			}

			@media screen and (max-width: 782px) {
				.ayyash-addons-srv-promo-wrap .ayyash-addons-srv-details {
					float: none;
					width: 100%;
				}

				.ayyash-addons-srv-promo-btn-container {
					float: none;
					width: 100%;
					margin-top: 32px;
				}

				.ayyash-addons-srv-promo-column.ayyash-addons-srv-promo-btn-container {
					width: 100%;
					float: right;
					margin-top: 42px;
				}
			}
		</style>
		<?php
	}

	/**
	 * Ajax Callback handler for hiding promo
	 * @return void
	 */
	public function __dismiss_promo() {
		if (
			isset( $_REQUEST['dismissed'], $_REQUEST['hash'], $_REQUEST['_wpnonce'] ) &&
			'true' == $_REQUEST['dismissed'] && ! empty( $_REQUEST['hash'] ) &&
			wp_verify_nonce( sanitize_text_field( $_REQUEST['_wpnonce'] ), 'ayyash-addons-srv-dismiss-promo' )
		) {
			$this->hidden_promotions = array_merge( $this->hidden_promotions, [ sanitize_text_field( $_REQUEST['hash'] ) ] );
			update_user_option( $this->currentUser, $this->client->getSlug() . '_hidden_promos', $this->hidden_promotions );
			wp_send_json_success( esc_html__( 'Promo hidden', 'ayyash-addons' ) );
		}
		wp_send_json_error( esc_html__( 'Invalid Request', 'ayyash-addons' ) );
		die();
	}

	/**
	 * @noinspection PhpUnused
	 * Clear Hidden Promotion preference for User
	 * @return bool
	 */
	public function clear_hidden_promos() {
		if ( ! did_action( 'admin_init' ) ) {
			_doing_it_wrong( __METHOD__, esc_html__( 'Method must be invoked inside admin_init action.', 'ayyash-addons' ), '1.0.0' );
		}
		$this->currentUser = get_current_user_id();

		return delete_user_option( $this->currentUser, $this->client->getSlug() . '_hidden_promos' );
	}

	/**
	 * Clear Cached Promotion data
	 * @return bool
	 */
	public function clear_cache() {
		return delete_transient( $this->client->getSlug() . '_cached_promos' );
	}
}
// End of file class-promotions.php.

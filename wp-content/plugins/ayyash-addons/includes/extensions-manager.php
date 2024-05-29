<?php
namespace AyyashAddons\Extensions;

defined( 'ABSPATH' ) || die();

class Extensions_Manager {
	const FEATURES_DB_KEY = 'ayyashaddons_inactive_features';

	/**
	 * Initialize
	 */
	public static function init() {

		include_once AYYASH_ADDONS_PATH . 'includes/extensions/custom-css.php';

		$inactive_features = self::get_inactive_features();

		foreach ( self::get_local_features_map() as $feature_key => $data ) {
			if ( ! in_array( $feature_key, $inactive_features ) ) {
				self::enable_feature( $feature_key );
			}
		}

	}

	public static function get_features_map() {
		$features_map = [];

		$local_features_map = self::get_local_features_map();
		$features_map = array_merge( $features_map, $local_features_map );

		return apply_filters( 'ayyashaddons_get_features', $features_map );
	}

	public static function get_inactive_features() {
		return get_option( self::FEATURES_DB_KEY, [] );
	}

	public static function save_inactive_features( $features = [] ) {
		update_option( self::FEATURES_DB_KEY, $features );
	}


	/**
	 * Get the free features
	 *
	 * @return array
	 */
	public static function get_local_features_map() {
		return [
			'custom-css' => [
				'title'  => __( 'Custom CSS', 'ayyash-addons' ),
				'icon'   => '',
				'is_pro' => false,
			],
		];
	}

	protected static function enable_feature( $feature_key ) {
		$feature_file = AYYASH_ADDONS_PATH . 'include/extensions/' . $feature_key . '.php';

		if ( is_readable( $feature_file ) ) {
			include_once( $feature_file );
		}
	}
}

Extensions_Manager::init();

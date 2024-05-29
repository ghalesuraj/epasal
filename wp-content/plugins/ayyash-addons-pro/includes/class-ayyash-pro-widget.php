<?php
/**
 *
 *
 * @package AyyashAddons
 * @version 1.0.0
 * @since 1.0.0
 */

namespace AyyashAddonsPro;

use AyyashAddons\Ayyash_Widget;


if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/** @define "AYYASH_ADDONS_WIDGETS_PATH" "./../widgets/" */
/** @define "AYYASH_ADDONS_PRO_PATH" "./../../ayyash-addons-pro/widgets/" */

/**
 * Class ayyash_addons_Widget
 * @package AyyashAddons
 */
class Ayyash_Pro_Widget extends Ayyash_Widget {

	public function get_custom_help_url() {
		return 'https://wpayyash.com/docs/ayyash-addons-pro/widgets/' . $this->get_base_id();
	}

}

// End of file class-base-widget.php.

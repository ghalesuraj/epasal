<?php
/**
 * The Companion hooks
 *
 * @package springoo/core
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

add_action( 'show_user_profile', 'springoo_extra_user_profile_fields' );
add_action( 'edit_user_profile', 'springoo_extra_user_profile_fields' );
add_action( 'personal_options_update', 'springoo_save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'springoo_save_extra_user_profile_fields' );

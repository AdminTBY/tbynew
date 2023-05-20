<?php

defined( 'ABSPATH' ) or exit;

// main functionality
require_once __DIR__ . '/includes/class-autocomplete.php';
$autocomplete = new MC4WP_Autocomplete( __FILE__ );
$autocomplete->add_hooks();


if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	require_once __DIR__ . '/includes/class-admin.php';
	$admin = new MC4WP_Autocomplete_Admin();
	$admin->add_hooks();
}

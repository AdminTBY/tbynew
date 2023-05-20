<?php
/**
 * Perform clean up when the plugin gets deleted.
 *
 * @since 1.4.2
 */

// If uninstall.php is not called by WordPress, die.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

require 'includes/frontend/gtag-local/class-gtag-js.php';

MonsterInsights_Gtag_Local_Js::do_cleanup();

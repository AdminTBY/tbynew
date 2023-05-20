<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if (!defined('WPRevive_VERSION_KEY'))
    define('WPRevive_VERSION_KEY', 'wp-revive_version');

if (!defined('WPRevive_VERSION_NUM'))
    define('WPRevive_VERSION_NUM', '1.0.0');

add_option(WPRevive_VERSION_KEY, WPRevive_VERSION_NUM);

?>

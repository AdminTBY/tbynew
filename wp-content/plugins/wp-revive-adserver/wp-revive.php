<?php
/*
Plugin Name: WP-Revive Adserver
Plugin URI: https://rezultmedia.com/revive-ad-server-wordpress-plugin/
Description: Display Revive Adserver ad campaigns anywhere on your WordPress website using Shortcodes. Supports your choice including Asynchronous JS, Javascript, and IFrame Invocation Code.
Version: 2.2.1
Author: Steven Soehl
Author URI: https://rezultmedia.com
License: A "Slug" license name e.g. GPL2
*/

/*  Copyright 2022  Steven Soehl  (email : stevensoehl@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) exit;

use WPRevive\Inc\WPReviveLoader;

if ( !defined( 'WP_REVIVE_PATH' ) ) {
    define( 'WP_REVIVE_PATH', plugin_dir_path( __FILE__ ) );
}

require_once WP_REVIVE_PATH . 'vendor/autoload.php';

if (!defined('WPRevive_VERSION_KEY'))
    define('WPRevive_VERSION_KEY', 'wp-revive_version');

if (!defined('WPRevive_VERSION_NUM'))
    define('WPRevive_VERSION_NUM', '2.2.1');

add_option(WPRevive_VERSION_KEY, WPRevive_VERSION_NUM);

//Register Hooks
$load = new WPReviveLoader(__FILE__);
$load->init();
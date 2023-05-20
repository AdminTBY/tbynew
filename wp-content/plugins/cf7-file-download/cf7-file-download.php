<?php
/**
* Plugin Name: CF7 File Download
* Description: Adds file/catalog download option to Contact Form 7
* Author: Rimes Gold
* Version: 2.0
* Text Domain: cf7-file-download
* Domain Path: /languages/
*/
// Plugin Version
if ( ! defined( 'CF7FD_VERSION' ) ) {
	define( 'CF7FD_VERSION', 1.1 );
}
// Current Directory
if ( !defined( 'CF7FD_PLUGIN_FILE' ) ) {
	define( 'CF7FD_PLUGIN_FILE', __FILE__ );
}
// Plugin Folder Path
if ( !defined( 'CF7FD_PLUGIN_DIR' ) ) {
	define( 'CF7FD_PLUGIN_DIR', plugin_dir_path( CF7FD_PLUGIN_FILE ) );
}
// Plugin Folder URL
if ( !defined( 'CF7FD_PLUGIN_URL' ) ) {
	define( 'CF7FD_PLUGIN_URL', plugin_dir_url( CF7FD_PLUGIN_FILE ) );
}
//Load the shortcode class
if ( ! class_exists( 'CF7_File_Download', false ) ) {
  include_once CF7FD_PLUGIN_DIR . '/classes/class-cf7-file-download.php';
}


// Instantiate Class
new CF7_File_Download();



?>
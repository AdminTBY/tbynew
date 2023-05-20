<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('admin_menu', 'wpreviveas_adminmenu');

function wpreviveas_adminmenu() {
//add_menu_page( 'WP-Revive', 'WP-Revive', 'manage_options', 'wprevive/wprevive-admin-page.php', 'wprevive_display_contents', 'dashicons-tickets', 6  );
add_submenu_page( 'options-general.php' , 'WP-Revive Options', 'WP Revive Options' , 'manage_options' , 'wp2revive_options' , 'wprevive_display_contents');
}
?>

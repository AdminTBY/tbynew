<?php
namespace WPRevive\Inc;

use WPRevive\Inc\WPReviveShortcodes;

defined('ABSPATH') || exit;

class WPReviveLoader {

    protected  $file;

    function __construct($file) {
        $this->file = $file;
      
       }

    public function init() {
        register_activation_hook( $this->file, array( $this, 'wprevive_init' ) );
        add_action('admin_menu', array( $this, 'wprevive_adminmenu' ));
        add_action( 'admin_init', array( $this, 'wprevive_activation_redirect' ));
        add_filter('plugin_action_links_'.plugin_basename($this->file), array($this,'wprevive_settings_link'));
        include(WP_REVIVE_PATH . 'inc/wprevive-options.php');
        
        new WPReviveShortcodes();
    
   }

   public function wprevive_init(){
        $this->wprevive_settings_page();
        $this->wprevive_db_init();
        $this->wprevive_create_directory();
    }

    public function wprevive_settings_link( $links ) {
        $links[] = '<a href="' . esc_url_raw(admin_url( 'admin.php?page=wp-revive.php' )) . '">' . esc_html__('Settings','wp-revive') . '</a>';

        return $links;
    }

    public function wprevive_adminmenu() {
        add_menu_page( 'WP-Revive', 'WP-Revive', 'manage_options', basename($this->file), 'wprevive_display_contents', 'dashicons-media-interactive', 61 );
        add_submenu_page( basename($this->file) , 'VAST 2 Convert', 'VAST 2 Convert' , 'manage_options' , 'wprevive_vast_convert' , 'wprevive_display_contents');
        add_submenu_page( basename($this->file) , 'VAST 2 Campaigns', 'VAST 2 Campaigns' , 'manage_options' , 'wprevive_vast_campaigns' , 'wprevive_display_contents');
      
    }


    public function wprevive_settings_page() {
        if (
            ( isset( $_REQUEST['action'] ) && 'activate-selected' === $_REQUEST['action'] ) &&
            ( isset( $_POST['checked'] ) && count( $_POST['checked'] ) > 1 ) ) {
            return;
        }
        add_option( 'wprevive_activation_redirect', array ($this, wp_get_current_user()->ID ));
    }
    
    public function wprevive_activation_redirect() {
        global $pagenow;
        if ( $pagenow == 'plugins.php' ) {
        if ( intval( get_option( 'wprevive_activation_redirect', false ) ) === wp_get_current_user()->ID ) {
            // Make sure we don't redirect again after this one
            delete_option( 'wprevive_activation_redirect' );
            wp_safe_redirect( admin_url( 'admin.php?page=wp-revive.php' ) );
            exit;
        }
    }
    }

    public function wprevive_create_directory(){
        $vast_upload = wp_upload_dir();
        $vast_upload_dir = $vast_upload['basedir'];
        $vast_upload_dir = $vast_upload_dir . '/wprevive-vast2';
        if (! is_dir($vast_upload_dir)) {
           mkdir( $vast_upload_dir, 0755 );
        }
    }
    
    public function wprevive_db_init() {
        global $wpdb;
        $wprevive_vast_file = $wpdb->prefix . 'wprevive_vast_file';
        $charset_collate = $wpdb->get_charset_collate();
            $vastfile = "CREATE TABLE $wprevive_vast_file (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                date_added datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                campaign varchar(128) NULL,
                vast_file varchar(128) NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";
        
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            if ( !empty($vastfile) ) {
                dbDelta( $vastfile );
            }
        }

}
<?php
define('extendedsearch_LICENSING_PLUGIN_SLUG', 'extended-search-addon/wpbot-posttype-search-addon.php');
define('extendedsearch_LICENSING_PLUGIN_NAME', 'extended-search-addon');
define('extendedsearch_LICENSING__DIR', plugin_dir_path(__DIR__));

define('extendedsearch_LICENSING_REMOTE_PATH', 'https://www.ultrawebmedia.com/li/plugins/extended-search-addon/update.php');
define('extendedsearch_LICENSING_PRODUCT_DEV_URL', 'https://quantumcloud.com/products/');

//start new-update-for-codecanyon
define('extendedsearch_ENVATO_PLUGIN_ID', -1);
//end new-update-for-codecanyon

function get_extendedsearch_licensing_plugin_data(){
	include_once(ABSPATH.'wp-admin/includes/plugin.php');
	return get_plugin_data(extendedsearch_LICENSING__DIR.'/wpbot-posttype-search-addon.php', false);
}

//License Options
function get_extendedsearch_licensing_key(){
	return get_option('qcld_extendedsearch_enter_license_key');
}

function get_extendedsearch_envato_key(){
	return get_option('qcld_extendedsearch_enter_envato_key');
}

function get_extendedsearch_licensing_buy_from(){
	return get_option('qcld_extendedsearch_buy_from_where');
}


//Update Transients
function get_extendedsearch_update_transient(){
	return get_transient('qcld_update_extendedsearch');
}

function set_extendedsearch_update_transient($plugin_object){
	return set_transient( 'qcld_update_extendedsearch', serialize($plugin_object), 1 * DAY_IN_SECONDS  );
}

function delete_extendedsearch_update_transient(){
	return delete_transient( 'qcld_update_extendedsearch' );
}


//Renewal Transients
function get_extendedsearch_renew_transient(){
	return get_transient('qcld_renew_extendedsearch_subscription');
}

function set_extendedsearch_renew_transient($plugin_object){
	return set_transient( 'qcld_renew_extendedsearch_subscription', serialize($plugin_object), 1 * DAY_IN_SECONDS  );
}

function delete_extendedsearch_renew_transient(){
	return delete_transient( 'qcld_renew_extendedsearch_subscription' );
}


//Invalid License Options
function get_extendedsearch_invalid_license(){
	return get_option('extendedsearch_invalid_license');
}

function set_extendedsearch_invalid_license(){
	return update_option('extendedsearch_invalid_license', 1);
}

function delete_extendedsearch_invalid_license(){
	return delete_option('extendedsearch_invalid_license');
}
function extendedsearch_get_licensing_url(){
	return admin_url('admin.php?page=extended-search-help-license');
}

//Valid License
function get_extendedsearch_valid_license(){
	return get_option('extendedsearch_valid_license');
}
function set_extendedsearch_valid_license(){
	return update_option('extendedsearch_valid_license', 1);
}
function delete_extendedsearch_valid_license(){
	return delete_option('extendedsearch_valid_license');
}

//staging or live 
function get_extendedsearch_site_type(){
	return get_option('qcld_extendedsearch_site_type');
}



//start new-update-for-codecanyon
function get_extendedsearch_license_purchase_code(){
	return get_option('qcld_extendedsearch_enter_license_or_purchase_key');
}

function get_extendedsearch_enter_license_notice_dismiss_transient(){
	return get_transient('get_extendedsearch_enter_license_notice_dismiss_transient');
}

function set_extendedsearch_enter_license_notice_dismiss_transient(){
	return set_transient('get_extendedsearch_enter_license_notice_dismiss_transient', 1, DAY_IN_SECONDS);
}

function get_extendedsearch_invalid_license_notice_dismiss_transient(){
	return get_transient('get_extendedsearch_invalid_license_notice_dismiss_transient');
}

function set_extendedsearch_invalid_license_notice_dismiss_transient(){
	return set_transient('get_extendedsearch_invalid_license_notice_dismiss_transient', 1, DAY_IN_SECONDS);
}
//end new-update-for-codecanyon
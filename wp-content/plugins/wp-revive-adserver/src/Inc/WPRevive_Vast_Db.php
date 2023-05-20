<?php
namespace WPRevive\Inc;


defined('ABSPATH') || exit;

class WPRevive_Vast_Db {


protected $wprevive_vast_file;
protected $charset_collate;

public function __construct()
{
    global $wpdb;
    $this->wprevive_vast_file = $wpdb->prefix . 'wprevive_vast_file';
    $this->charset_collate = $wpdb->get_charset_collate();
    $this->now = current_time( 'mysql' );
}


protected function charset(){
    return $this->charset_collate;
}

public function wprevive_vast_add_file($campaign,$vast2_xml_url) {
    global $wpdb;

    if(!empty($vast2_xml_url)){
    $add_file = $wpdb->insert($this->wprevive_vast_file, array(
                'date_added' => $this->now,
                'campaign' => $campaign,
                'vast_file' => $vast2_xml_url,
            ));
    $wpdb->query($add_file);
    }
}

public function wprevive_vast_get_file() {
    global $wpdb;
    $get_files = $wpdb->get_results(
        "SELECT * FROM {$this->wprevive_vast_file}
         ORDER BY date_added ASC "
     );

     return $get_files;
}

public function wprevive_vast_delete_campaign($campaignID){
    global $wpdb;
    $deleteCampaign = $wpdb->delete( $this->wprevive_vast_file, array( 'id' => $campaignID ) );

    if($deleteCampaign) {
        $msg = 'Campaign successfully deleted.';
    } else {
        $msg = 'Campaign could not be deleted.';
    }

    return $msg;
}

}
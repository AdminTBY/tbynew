<?php if ( ! defined( 'ABSPATH' ) ) exit; 
use WPRevive\Inc\WPRevive_Vast_Db;
$revive_vast_dbConnect = new WPRevive_Vast_Db;

$campaignList = $revive_vast_dbConnect->wprevive_vast_get_file();

if(isset($_POST['deleteCampaign'])) { 
	$campaignID = $_POST['campaignID'];
  $campaignName = $_POST['campaignName'];
  $deleteCampaign = $revive_vast_dbConnect->wprevive_vast_delete_campaign($campaignID); ?>
        <script>
          document.location.reload(true);
        </script>
    <?php
    if($deleteCampaign) { 
     echo '<div id="message" class="updated fade"><p><strong>' . $campaignName . ' was deleted.</strong></p></div>';
    } else {
    echo '<div id="message" class="updated fade"><p><strong>Issue with deleting' .  $campaignName  .'</strong></p></div>';
    }
}
?>

<h2><?php _e("Revive Video Campaigns" , 'wp-revive'); ?></h2>
<?php if(!empty($campaignList)) { ?>
<table class="fixed" cellspacing="0" cellpadding="5" style="margin-top:25px;">
    <thead style="background-color:#004a8b;color:#fff;font-size:16px;">
    <tr>
    <th id="columnname" class="manage-column column-columnname" scope="col" style="border-right:1px solid #f1f1f1;"><?php _e("Campaign" , 'wp-revive'); ?></th>
    <th id="columnname" class="manage-column column-columnname" scope="col" style="border-right:1px solid #f1f1f1;"><?php _e("VAST 2 Template" , 'wp-revive'); ?></th>
    <th id="columnname" class="manage-column column-columnname" scope="col"><?php _e("Delete Campaign" , 'wp-revive'); ?></th>
    </tr>
    </thead>
    <tbody>
      
          <?php
      foreach($campaignList as $m) {
        ?>
          <tr id="campaign-<?php echo $m->id;?>" class="" style="background:#fff;">
          <form method="post">
              <td class="column-columnname" style="font-size:17px;padding:6px;border:1px solid #f1f1f1;">
              <input type="hidden" value="<?php echo $m->id;?>" name="campaignID">
              <input type="hidden" value="<?php echo $m->campaign;?>" name="campaignName">
                <?php echo $m->campaign; ?>
              </td>
              <td class="column-columnname " style="font-size:17px;padding:6px;border:1px solid #f1f1f1;text-align:center;">
              <div class="copy-to-clipboard">
              <div style="font-size:11px;"><?php _e("Click link to copy to Clipboard" , 'wp-revive'); ?></div>
              <input readonly type="text" style="width:590px;" value="<?php echo $m->vast_file; ?>">
              <div class='copier'></div>
              </div>
              </td>
              <td class="column-columnname"style="padding:6px;border:1px solid #f1f1f1;"><button name="deleteCampaign" id="delete" class="button">DELETE</button></td>
        </form>
          </tr>
          <?php } ?>
    </tbody>
    </table>
    <?php } else { ?>
      <h3 style="margin-top:15px;"><?php _e("No campaigns" , 'wp-revive'); ?></h3>
   <?php } ?>
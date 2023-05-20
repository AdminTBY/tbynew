<?php
  if ( isset( $_POST['wprevive_adserverurl'] ) )
  {
?>
   <div id='message' class='updated fade'><p><strong><?php _e("You have successfully updated your Revive Adserver details.",'wp-revive'); ?></strong></p></div>
<?php
  }
?>
      <form method="post" action="admin-post.php">
         <input type="hidden" name="action" value="wprevive_save_option" />
        <?php wp_nonce_field( 'wprevive_op_verify' ); ?>
				<div style="margin-bottom:10px;">
				<label style="margin-bottom:3px; clear:both;"><?php _e("Revive Adserver Async URL:",'wp-revive'); ?></label><br/>
				<?php if(!empty($options['wprevive_op_adserverurl'])) { ?>
				 <input type="url" name="wprevive_adserverurl" value="<?php echo esc_html( $options['wprevive_op_adserverurl'] ); ?>"/>
				 <?php } else { ?>
					<input type="url" name="wprevive_adserverurl" value="Enter URL to Revive Ad Server"/>
				<?php } ?>
				 <div style="margin-top:3px;">
				 <?php _e("Enter self-hosted Revive Ad Server Path to Invocation Code files. (//example.com/adserver/www/delivery). For Hosted Edition, enter //servedby.revive-adserver.net",'wp-revive'); ?>
				 </div>
				 </div>
				 <div style="margin-bottom:10px;">
				 <label style="margin-bottom:3px;"><?php _e("Revive Adserver ID:",'wp-revive'); ?></label><br/>
				 <?php if(!empty($options['wprevive_op_reviveid'])) { ?>
				  <input type="text" name="wprevive_reviveid" value="<?php echo esc_html( $options['wprevive_op_reviveid'] ); ?>"/>
				 <?php } else { ?>
					<input type="text" name="wprevive_reviveid" value="Enter Server ID"/>
				  <?php } ?>
				  <div style="margin-top:3px;"><?php _e("Enter Revive Adserver ID. This is for Async Invocation Code (data-revive-id=\"a7417xxxxxxxxxxxxxxxxx\")",'wp-revive'); ?> </div>
				  </div>

         <input type="submit" value="Submit" class="button-primary"/>
      </form>
	  <br>
<h3>Shortcodes</h3>
<div style="padding:6px 0;font-size:16px;"><b><?php _e("Asynchronous JS Invocation Code:",'wp-revive'); ?></b>&nbsp; [wprevive_async zoneid="ZONE # GOES HERE"]</div>
<div style="padding:6px 0;font-size:16px;"><b><?php _e("Javascript Invocation Code:",'wp-revive'); ?></b>&nbsp; [wprevive_js zoneid="ZONE # GOES HERE"]</div>
<div style="padding:6px 0;font-size:16px;"><b><?php _e("IFrame Invocation Code:",'wp-revive'); ?></b>&nbsp; [wprevive_iframe zoneid="ZONE # GOES HERE" width="AD UNIT WIDTH" height="AD UNIT HEIGHT"]</div>
</div>
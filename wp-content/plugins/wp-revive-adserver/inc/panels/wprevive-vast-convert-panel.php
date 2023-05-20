<?php if ( ! defined( 'ABSPATH' ) ) exit; 
use   WPRevive\Inc\WPRevive_Vast_Conversion;

if(isset($_POST['reviveURL'])) {
     $convert_file = new WPRevive_Vast_Conversion;
     $reviveURL = $_POST['reviveURL'];
     $campaign = $_POST['campaign'];
	 
	 if (substr($reviveURL, 0, 4) != 'http') {
        echo '<div id="message" class="updated fade"><p><strong>' . _e("Please enter a valid URL","wp-revive") . '</strong></p></div>';
	 } else{
     $vast2_conversion = $convert_file->wprevive_vast_file_conversion($reviveURL,$campaign);
     if($vast2_conversion) {
        echo '<div id="message" class="updated fade"><p><strong>' . $vast2_conversion[0] . '</strong></p></div>';
      } else {
      echo '<div id="message" class="updated fade"><p><strong>' . e_("Issue with file conversion", "wp-revive") . '</strong></p></div>';
      }
	}

} 

?>
	<!-- Video.js 4 -->
    <link href="https://vjs.zencdn.net/4.12/video-js.css" rel="stylesheet">
	<script src="https://vjs.zencdn.net/4.12/video.js"></script>
	<link href="<?php echo plugin_dir_url( __DIR__ );?>assets/bin/videojs.vast.vpaid.min.css" rel="stylesheet">
	<script src="<?php echo plugin_dir_url( __DIR__ );?>assets/bin/videojs_4.vast.vpaid.min.js"></script>
	
             <h2><?php _e("Convert to VAST2 Template File" , 'wprevive'); ?></h2>
             <h3 style="margin-top:15px;"><?php _e("Enter you Revive Adserver Invocation Code URL below:" , 'wprevive'); ?></h3>
             <p style="font-size:15px; line-height:1.7;">
			 <?php 
			 if(!empty($options['wprevive_op_adserverurl'])){
			 _e("Add zone ID.<br/>" . $options['wprevive_op_adserverurl'] ."/www/delivery/fc.php?script=bannerTypeHtml:vastInlineBannerTypeHtml:vastInlineHtml&format=vast&nz=1&zones=pre-roll%3D[YOUR-ZONE-ID-HERE]" , 'wp-revive'); 
			 } else {
				_e("Replace with your adserver domain and the zone ID.<br/>[ADD_SERVER-DOMAIN]/www/delivery/fc.php?script=bannerTypeHtml:vastInlineBannerTypeHtml:vastInlineHtml&format=vast&nz=1&zones=pre-roll%3D[YOUR-ZONE-ID-HERE]" , 'wp-revive'); 
			 }
			 ?>
			</p>
					<table>
					<form id="parse" enctype="multipart/form-data" method="post">
					<tr>
						<td><input type="text" name="campaign" class="input" placeholder="Enter Campaign Name"></td>
					</tr>
						<tr>	 
						<td>
						<input type="url" name="reviveURL" class="input" placeholder="Enter Invocation URL">
						</td>
						<td>
						<button type="submit" id="go" class="button is-info">
								Submit
						</button>
						</td>
						</tr>
						</form>
					</table>

			<?php if(!empty($vast2_conversion[1])){ ?>
		<div style="padding:20px;display:inline-block;margin-top:25px;">
			<div style="font-size:18px;line-height:1.5;margin-bottom:6px;margin-top:20px;font-weight:600;"><?php _e("VAST2 Template File:", 'wp-revive');?></div>
		<div id="dl" style="padding-bottom:20px;">
					<div class="copy-to-clipboard">
					<div style="font-size:13px;"><?php _e("Click link to copy to Clipboard", 'wp-revive');?></div>
					<input readonly type="text" style="width:590px;" value="<?php echo $vast2_conversion[1]; ?>">
					<div class='copier'></div>
					</div>
			</div>
		
	<div style="margin-top:25px;">
	<div style="font-size:18px;line-height:1.5;margin-bottom:6px;margin-top:20px;font-weight:600;"><?php _e("VAST2 Template File Preview:", 'wp-revive');?></div>
	<p style="font-size:15px; line-height:1.7;"><?php _e("Preview uses VideoJS player with VAST2 plugins. Converted VAST2 template file should work with all VAST2 compatible players." , 'wp-revive'); ?></p>
	<video id="example_video_1" class="video-js vjs-default-skin"
		controls preload="auto" 
		poster="https://vjs.zencdn.net/v/oceans.png"
		data-setup='{
		"fluid" : true,	
		"plugins": {
		"vastClient": {
			"adTagUrl": "<?php echo $vast2_conversion[1]; ?>",
			"adCancelTimeout": 5000,
			"adsEnabled": true
			}
		}
		}'>
	<source src="https://vjs.zencdn.net/v/oceans.mp4" type='video/mp4'/>
	<source src="https://vjs.zencdn.net/v/oceans.webm" type='video/webm'/>
	<source src="https://vjs.zencdn.net/v/oceans.ogv" type='video/ogg'/>
	<p class="vjs-no-js">
		To view this video please enable JavaScript, and consider upgrading to a web browser that
		<a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
	</p>
	</video>
	
	</div>
	
	<?php } ?>
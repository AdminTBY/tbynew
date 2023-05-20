<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'wprevive_op_array' ) === false ) {

} else {
  //Async Insertion
  function wprevive_displayasync ($atts){
    extract(shortcode_atts( array('zoneid' => ''), $atts));
    $options =get_option( 'wprevive_op_array' );
    $url =$options['wprevive_op_adserverurl'];
    $reviveid =$options['wprevive_op_reviveid'];

   $content = '<ins data-revive-zoneid="' . $zoneid . '" data-revive-id="' . $reviveid . '"></ins>
<script async src="' . $url . '/www/delivery/asyncjs.php"></script></div>';

return $content;

    }

//Javascript Insertion
function wprevive_displayjs ($atts){
  extract(shortcode_atts( array('zoneid' => ''), $atts));
  $options =get_option( 'wprevive_op_array' );
  $url =$options['wprevive_op_adserverurl'];
  $reviveid =$options['wprevive_op_reviveid'];
  ?>
<script type='text/javascript'><!--//<![CDATA[
   var m3_u = (location.protocol=='https:'?'<?php echo $url; ?>/www/delivery/ajs.php':'<?php echo $url; ?>/www/delivery/ajs.php');
   var m3_r = Math.floor(Math.random()*99999999999);
   if (!document.MAX_used) document.MAX_used = ',';
   document.write ("<scr"+"ipt type='text/javascript' src='"+m3_u);
   document.write ("?zoneid=<?php echo $zoneid; ?>");
   document.write ('&amp;cb=' + m3_r);
   if (document.MAX_used != ',') document.write ("&amp;exclude=" + document.MAX_used);
   document.write (document.charset ? '&amp;charset='+document.charset : (document.characterSet ? '&amp;charset='+document.characterSet : ''));
   document.write ("&amp;loc=" + escape(window.location));
   if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
   if (document.context) document.write ("&context=" + escape(document.context));
   if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
   document.write ("'><\/scr"+"ipt>");
//]]>--></script><noscript><a href='<?php echo $url; ?>/www/delivery/ck.php?n=a86c7caa&amp;cb=INSERT_RANDOM_NUMBER_HERE' target='_blank'><img src='<?php echo $url; ?>/www/delivery/avw.php?zoneid=1&amp;cb=INSERT_RANDOM_NUMBER_HERE&amp;n=a86c7caa' border='0' alt='' /></a></noscript>

<?php
}
//Iframe Insertion
function wprevive_displayiframe ($atts){
  extract(shortcode_atts( array('zoneid' => ''), $atts));
  extract(shortcode_atts( array('width' => ''), $atts));
  extract(shortcode_atts( array('height' => ''), $atts));
  extract(shortcode_atts( array('iframeID' => ''), $atts));
  extract(shortcode_atts( array('iframeName' => ''), $atts));
  $options =get_option( 'wprevive_op_array' );
  $url =$options['wprevive_op_adserverurl'];
  $reviveid =$options['wprevive_op_reviveid'];

$content='<iframe id="' . $iframeID . '" name="' . $iframeName . '" src="' . $url . '/www/delivery/afr.php?zoneid=' . $zoneid . '&amp;cb=INSERT_RANDOM_NUMBER_HERE" frameborder="0" scrolling="no" width="' . $width . '"
  height="' . $height . '"><a href="' . $url . '/www/delivery/ck.php?n=a23d0313&amp;cb=INSERT_RANDOM_NUMBER_HERE" target="_blank">
  <img src="' . $url . '/www/delivery/avw.php?zoneid=1&amp;cb=INSERT_RANDOM_NUMBER_HERE&amp;n=a23d0313" border="0" alt="" /></a></iframe>';

return $content;
}

}

   add_shortcode('wprevive_async', 'wprevive_displayasync');
   add_shortcode('wprevive_js', 'wprevive_displayjs');
   add_shortcode('wprevive_iframe', 'wprevive_displayiframe');
?>

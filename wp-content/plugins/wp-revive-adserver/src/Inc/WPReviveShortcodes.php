<?php
namespace WPRevive\Inc;
use REZULTCOM\Inc\Sms\RezultAPI;

defined('ABSPATH') || exit;
class WPReviveShortcodes {

  function __construct() {
    add_action('init', array( $this,'wprevive_register_shortcodes'));
  }

   function wprevive_register_shortcodes(){
     add_shortcode( 'revive_test', array( $this, 'wprevive_display_test' ) );
     add_shortcode( 'wprevive_async', array( $this, 'wprevive_display_async' ) );
     add_shortcode( 'wprevive_iframe', array( $this, 'wprevive_display_iframe' ) );
     add_shortcode( 'wprevive_js', array( $this, 'wprevive_display_js' ) );
   }

    //Async Insertion
  function wprevive_display_async ($atts){
    extract(shortcode_atts( array('zoneid' => ''), $atts));
    $options =get_option( 'wprevive_op_array' );
    $url =$options['wprevive_op_adserverurl'];
    $reviveid =$options['wprevive_op_reviveid'];

        $content = '<ins data-revive-zoneid="' . $zoneid . '" data-revive-id="' . $reviveid . '"></ins>
        <script async src="' . $url . '/asyncjs.php"></script></div>';

        return $content;

    }

    //Iframe Insertion
   function wprevive_display_iframe ($atts){
    extract(shortcode_atts( array('zoneid' => ''), $atts));
    extract(shortcode_atts( array('width' => ''), $atts));
    extract(shortcode_atts( array('height' => ''), $atts));
    extract(shortcode_atts( array('iframeID' => ''), $atts));
    extract(shortcode_atts( array('iframeName' => ''), $atts));
    $options =get_option( 'wprevive_op_array' );
    $url =$options['wprevive_op_adserverurl'];
    $reviveid =$options['wprevive_op_reviveid'];
  
  $content='<iframe id="' . $iframeID . '" name="' . $iframeName . '" src="' . $url . '/afr.php?zoneid=' . $zoneid . '&amp;cb=INSERT_RANDOM_NUMBER_HERE" frameborder="0" scrolling="no" width="' . $width . '"
    height="' . $height . '"><a href="' . $url . '/ck.php?n=a23d0313&amp;cb=INSERT_RANDOM_NUMBER_HERE" target="_blank">
    <img src="' . $url . '/avw.php?zoneid=1&amp;cb=INSERT_RANDOM_NUMBER_HERE&amp;n=a23d0313" border="0" alt="" /></a></iframe>';
  
  return $content;
  }

  function wprevive_display_js ($atts){
    extract(shortcode_atts( array('zoneid' => ''), $atts));
    $options =get_option( 'wprevive_op_array' );
    $url =$options['wprevive_op_adserverurl'];
    $reviveid =$options['wprevive_op_reviveid'];
    
    $content = '
    <script type=\'text/javascript\'><!--//<![CDATA[
        var m3_u = (location.protocol==\'https:\'?\'' . $url . '/ajs.php\':\'' . $url . '/ajs.php\');
        var m3_r = Math.floor(Math.random()*99999999999);
        if (!document.MAX_used) document.MAX_used = \',\';
        document.write ("<scr"+"ipt type=\'text/javascript\' src=\'"+m3_u);
        document.write ("?zoneid=' . $zoneid . '");
        document.write (\'&amp;cb=\' + m3_r);
        if (document.MAX_used != \',\') document.write ("&amp;exclude=" + document.MAX_used);
        document.write (document.charset ? \'&amp;charset=\'+document.charset : (document.characterSet ? \'&amp;charset=\'+document.characterSet : \'\'));
        document.write ("&amp;loc=" + escape(window.location));
        if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
        if (document.context) document.write ("&context=" + escape(document.context));
        if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
        document.write ("\'><\/scr"+"ipt>");
     //]]>--></script><noscript><a href=\'' . $url . '/ck.php?n=a86c7caa&amp;cb=INSERT_RANDOM_NUMBER_HERE\' target=\'_blank\'><img src=\'' . $url . '/avw.php?zoneid=1&amp;cb=INSERT_RANDOM_NUMBER_HERE&amp;n=a86c7caa\' border=\'0\' alt=\'\' /></a></noscript>
    ';

    return $content;

  }
  
}

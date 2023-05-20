<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
include WP_REVIVE_PATH .'inc/process-options.php';

function wprevive_display_contents()
{
   $options = get_option( 'wprevive_op_array' );
   if(isset($_GET['page'])){
      $menuItem = $_GET['page'];
  }
?>
<style>
#wpcontent {
    height: 100%;
    padding-left: 0px!important;
}
.wprevive-body {background-color:#f0f0f1;border-top:4px solid #0082d9;}
.wprevive-header {width:100%; padding:15px; background-color:#ffff; margin-top:0px;}
.wprevive-header .logo {width:200px;}
.wrap {padding-left:25px;}
.tabContent {background:#f0f0f1; padding:30px;border:1px solid #ccc;border-top:0;font-size:16px line-height:1.7;}
.nav-tab-wrapper{background-color:#f7f7f7;border-top:1px solid #ccc;}
.nav-tab-active {background-color:#0082d9;color:#fff;}
label {font-size:16px;margin-bottom:5px;font-weight:600;}
 p {}

 .copied::after {
  position: absolute;
  top: 12%;
  right: 110%;
  display: block;
  content: "copied";
  font-size: 0.75em;
  padding: 2px 3px;
  color: #fff;
  background-color: #22a;
  border-radius: 3px;
  opacity: 0;
  will-change: opacity, transform;
  animation: showcopied 1.5s ease;
}

.copy-to-clipboard input {
 border: none;
 background: transparent;
 cursor:pointer;
 width:100%;
}

.copy-to-clipboard input:focus {
  border: none;
 background: transparent;
 outline: none;
}
@keyframes showcopied {
  0% {
    opacity: 0;
    transform: translateX(100%);
  }
  70% {
    opacity: 1;
    transform: translateX(0);
  }
  100% {
    opacity: 0;
  }
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
/*
    Copy text from any appropriate field to the clipboard
  By Craig Buckler, @craigbuckler
  use it, abuse it, do whatever you like with it!
*/
(function() {

    'use strict';
  
  // click events
  document.body.addEventListener('click', copy, true);

    // event handler
    function copy(e) {

    // find target element
    var 
      t = e.target,
      c = t.dataset.copytarget,
      inp = (c ? document.querySelector(c) : null);
      
    // is element selectable?
    if (inp && inp.select) {
      
      // select text
      inp.select();

      try {
        // copy text
        document.execCommand('copy');
        inp.blur();
        
        // copied animation
        t.classList.add('copied');
        setTimeout(function() { t.classList.remove('copied'); }, 1500);
      }
      catch (err) {
        alert('please press Ctrl/Cmd+C to copy');
      }
      
    }
    
    }

})();
</script>

<script>
jQuery(function() {
  $('.copy-to-clipboard input').click(function() {
    $(this).focus();
    $(this).select();
    document.execCommand('copy');
    $(".copier").text("Copied to clipboard").show().fadeOut(2500);
  });
});
</script>
   <div class="wprevive-body">
   <div class="wprevive-header">
   <img class="logo" src="<?php echo plugin_dir_url(__FILE__ ) ?>assets/WP_REVIVE_LGO.png" alt="WP-Revive for Revive Adserver">
	  </div>
<div class="wrap">
<?php if($menuItem == 'wprevive_vast_convert'){
          include(WP_REVIVE_PATH . 'inc/panels/wprevive-vast-convert-panel.php');
      } else if ($menuItem == 'wprevive_vast_campaigns'){
        include(WP_REVIVE_PATH . 'inc/panels/wprevive-vast-campaigns-panel.php');
    } else {
      include(WP_REVIVE_PATH . 'inc/panels/wprevive-general-panel.php');
    }

    ?>
   </div>
<?php
}
add_action( 'admin_post_wprevive_save_option', 'wprevive_process_options' );
?>

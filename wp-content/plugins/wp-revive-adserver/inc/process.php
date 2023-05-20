<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function wprevive_process_options()
{
   if ( !current_user_can( 'manage_options' ) )
   {
      wp_die( 'You are not allowed to be on this page.' );
   }

   check_admin_referer( 'wprevive_op_verify' );

   $options = get_option( 'wprevive_op_array' );

   if ( isset( $_POST['wprevive_adserverurl'] ) )
   {
      $options['wprevive_op_adserverurl'] = esc_url( $_POST['wprevive_adserverurl'] );
   }

    if ( isset( $_POST['wprevive_reviveid'] ) )
   {
      $options['wprevive_op_reviveid'] = sanitize_text_field( $_POST['wprevive_reviveid'] );
   }


   update_option( 'wprevive_op_array', $options );

wp_redirect(  admin_url( 'options-general.php?page=wp2revive_options&m=1')  );
   exit;
}
?>

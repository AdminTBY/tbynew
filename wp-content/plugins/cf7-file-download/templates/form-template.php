<table class="cf7-file-download">
		    <tr>
		    	<td>
				    <label for="cf7fd_settings_options[id]"><?php _e( 'Contact Form ID', 'cf7-file-download' ); ?></label>
				</td>
				<td>
				    <!--<input type="text" id="cf7fd_settings_options[id]" name="cf7fd_settings_options[id]" value="<?php echo $form_id; ?>">-->
				    <select name="cf7fd_settings_options[id]" id="field-id"> 
    <option value="">--Select--</option><?php
    $dbValue =$form_id; //example!
    $posts = get_posts(array(
        'post_type'     => 'wpcf7_contact_form',
        'numberposts'   => -1
    ));
    foreach ( $posts as $p ) {
        echo '<option value="'.$p->ID.'"'.selected($p->ID,$dbValue,false).'>'.$p->post_title.' ('.$p->ID.')</option>';
    } ?>
</select>
				</td>
			</tr>
			
			<tr>
				<td>
			    	<label for="cf7fd_settings_options[url]"><?php _e( 'Attachment URL', 'cf7-file-download' ); ?></label>
			 	</td>
			 	<td>
			    	<input type="text" id="cf7fd_settings_options[url]" name="cf7fd_settings_options[url]" value="<?php echo $file_url; ?>">
				</td>
			</tr>
			<tr>
				<td>
			     <label for="cf7fd_settings_options[filename]"><?php _e( 'Downloaded File Name', 'cf7-file-download' ); ?></label>
			     </td>
			 	<td>
			    <input type="text" id="cf7fd_settings_options[filename]" name="cf7fd_settings_options[filename]" value="<?php echo $file_name; ?>">
				</td>
			</tr>
		
		 </table>
		 <div class="repeatable-wrap">
 <h4> <?php esc_html_e( 'Use setting below to add additional download settings', 'cf7-file-download' ); ?> </h4>		 	<table id="tracks-repeatable" class="repeatable-fields-list">
		
 	<thead>
 		<td><?php _e( 'Contact Form 7 ID', 'cf7-file-download' ); ?></td>
 		<td><?php _e( 'Attachment URL', 'cf7-file-download' ); ?></td>

<td><?php _e( 'Download File Name', 'cf7-file-download' ); ?></td>
<td><?php _e( 'Action', 'cf7-file-download' ); ?></td>


 	</thead>

		<?php 
 

   
      
 
if (  isset( $form_settings['forms']) &&  count($form_settings['forms']) > 0  ) {
    $i = 0;
    foreach( $form_settings['forms'] as $option ) {

    	if( isset($option)) {
    	?>

       <tr>
            <td><!--<input type="text" name="cf7fd_settings_options[forms][<?php echo $i;?>][id]" 
                value="<?php echo $option['id'];?>"  />-->
                   
<select name="cf7fd_settings_options[forms][<?php echo $i;?>][id]" id="field-id"> 
    <option value="">--Select--</option><?php
    $dbValue =$option['id']; //example!
    $posts = get_posts(array(
        'post_type'     => 'wpcf7_contact_form',
        'numberposts'   => -1
    ));
    foreach ( $posts as $p ) {
        echo '<option value="'.$p->ID.'"'.selected($p->ID,$dbValue,false).'>'.$p->post_title.' ('.$p->ID.')</option>';
    } ?>
</select>
            </td>
             <td><input type="text" name="cf7fd_settings_options[forms][<?php echo $i;?>][url]"
                value="<?php echo $option['url'];?>"  />
                   

            </td>
             <td><input type="text" name="cf7fd_settings_options[forms][<?php echo $i;?>][name]"
                value="<?php echo $option['name'];?>"  />
                   

            </td>



            <td>
<a class="repeatable-field-remove button" href="#"><?php _e( 'Remove', 'cf7-file-download' ); ?></a>
</td>
            </tr>

            <?php
        }
       
        $i++;
    }
}
 
    ?>
</table>
<table>
<tr>


<td>
    <a class="repeatable-field-add button" ><?php _e( 'Add', 'cf7-file-download' ); ?></a>
</td>
<td>

	</td>
</tr>
<tfoot style="display:none" id="repeater_template">
<tr  >
	<td>
      <!--  <input type="text" id="cf7fd_settings_options[forms][x][id]" value=""  />-->
        <select  id="cf7fd_settings_options[forms][x][id]"> 
    <option value="">--Select--</option><?php
   
    $posts = get_posts(array(
        'post_type'     => 'wpcf7_contact_form',
        'numberposts'   => -1
    ));
    foreach ( $posts as $p ) {
        echo '<option value="'.$p->ID.'">'.$p->post_title.' ('.$p->ID.')</option>';
    } ?>
</select>
    </td>
    <td>
         <input type="text" id="cf7fd_settings_options[forms][x][url]" value=""  />
     </td>
     <td>
          <input type="text" id="cf7fd_settings_options[forms][x][name]" value=""  />
</td>
<td>
<a class="repeatable-field-remove button" href="#"><?php _e( 'Remove', 'cf7-file-download' ); ?></a>
</td>
        </tr>
    </tfoot>

</table>


 
</div>
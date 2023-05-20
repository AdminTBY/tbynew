<?php
/*
** adding necessary files
*/

function arrowdesign_removeGuttenbergAD_Style_script() {

		$plugin_url = plugin_dir_url( __FILE__ );

		wp_enqueue_style( 'style',  $plugin_url . 'css/style.css');
		wp_enqueue_script('arrowdesign_removeGuttenbergAD_logic_file', plugins_url('/js/logic.js',__FILE__ ));
	}

	add_action('wp_enqueue_scripts', 'arrowdesign_removeGuttenbergAD_Style_script');

/**
 * Adds a new settings page under Setting menu
*/
	add_action( 'admin_menu', 'arrowdesign__gut_options__admin_page' );
	function arrowdesign__gut_options__admin_page() {
		//only editor and administrator can edit
		if( current_user_can('editor') || current_user_can('administrator') ) {
		add_options_page( __( 'Classic Editor on off' ), __( 'Classic Editor on off' ), 'manage_options', 'arrowdesign__gut_options__admin_page', 'addub_remove_guttenberg_homepage' );
	}
	}


function addub_remove_guttenberg_homepage (){

//declaration
	global $wpdb;
//get current counts as vars || na


    ?>


	  <div class="intro_text_class" >
        	<h3>Dashboard for managing Gutenberg blocks.</h3>
			<p>Click the following link to contact Arrow Design for <span>
		    <a href="https://arrowdesign.ie">Web Design</a>, Support or WordPress Plugin Development.
        </span></p>

    </div>

	  <div class="tabbedElements_firstTab"  >


        	<!--First tab -->
        </div>

        			<h3>Instructions </h3>
					<h4>Turn on or off your relevant gutenberg functionality</h4>
					<h4>Use the switches to make your selection</h4>
	<br>






        <?php
//if button clicked
		if (isset($_POST['btn-to-update-users-selection-of-gutenberg'])) {


			//declarations for check boxes selected
if (isset($_POST['first'])) {	$first_input = $_POST['first'];				}
if (isset($_POST['second'])) {			$second_input = $_POST['second'];	}
if (isset($_POST['third'])) {			$third_input = $_POST['third'];		}
if (isset($_POST['fourth'])) {			$fourth_input = $_POST['fourth'];	}



////////////////////////////////////////////////guttenberg widgets enabled / disabled ////////////////START///////////////////
if(!empty($first_input))
	{
	//start setting the block wid to disabled

		//add meta for first use
		$arrowd_disable_guttenberg_from_wds_var = get_term_meta('2020', 'arrowd_disable_guttenberg_from_wds_var_meta', true);
		if(is_null($arrowd_disable_guttenberg_from_wds_var)or $arrowd_disable_guttenberg_from_wds_var==''){
			add_term_meta('2020', 'arrowd_disable_guttenberg_from_wds_var_meta' , "wids_guten_disabed");
		}//end adding meta for gutenberg wid disabled

		//update if already set
		if(($arrowd_disable_guttenberg_from_wds_var=='wids_guten_enabled') or ($arrowd_disable_guttenberg_from_wds_var=='')){
				update_term_meta('2020', 'arrowd_disable_guttenberg_from_wds_var_meta' , "wids_guten_disabed");
			}//end update to disabled wid
	}//end setting the block wid to disabled
	else
	//start setting the block wid to enabled
	{
	$first_input='';
	$theDisableWidTermMetaToAdd = 'wids_guten_enabled';

		//add meta for first use
			$arrowd_disable_guttenberg_from_wds_var = get_term_meta('2020', 'arrowd_disable_guttenberg_from_wds_var_meta', true);
			if(is_null($arrowd_disable_guttenberg_from_wds_var)or $arrowd_disable_guttenberg_from_wds_var==''){

				if(add_term_meta('2020', 'arrowd_disable_guttenberg_from_wds_var_meta' , $theDisableWidTermMetaToAdd)){

				}//end added
			else
			{

			}
		}//end not added and not already set
	//update if already set
	if(($arrowd_disable_guttenberg_from_wds_var=='wids_guten_disabed') or ($arrowd_disable_guttenberg_from_wds_var==''))
	{
			update_term_meta('2020', 'arrowd_disable_guttenberg_from_wds_var_meta' , "wids_guten_enabled");
	}//end if already set


}//end setting the block wid to enabled
////////////////////////////////////////////////guttenberg widgets enabled / disabled ////////////////END///////////////////


////////////////////////////////////////////////guttenberg pages enabled / disabled ////////////////START///////////////////
if(!empty($second_input))
	{
	//start setting the block wid to disabled

		//add meta for first use
		$arrowd_disable_guttenberg_from_pages_var = get_term_meta('2020', 'arrowd_disable_guttenberg_from_pages_var_meta', true);

	if(is_null($arrowd_disable_guttenberg_from_pages_var)or $arrowd_disable_guttenberg_from_pages_var==''){
			if(add_term_meta('2020', 'arrowd_disable_guttenberg_from_pages_var_meta' , 'pages_guten_disabed'))
			{
				echo"<p>added - disabled</p>";
			}
			else
			{
				echo"<p>not added - disabled</p>";
			}

		}//end adding meta for gutenberg wid disabled

		//update if already set
		if(($arrowd_disable_guttenberg_from_pages_var=='pages_guten_enabled') or ($arrowd_disable_guttenberg_from_pages_var=='')){
				if(update_term_meta('2020', 'arrowd_disable_guttenberg_from_pages_var_meta' , 'pages_guten_disabed'))
								{

			}
			else
			{

			}
			}//end update to disabled wid
	}//end setting the block wid to disabled
	else
	//start setting the block wid to enabled
	{
	//$second_input='';


		//add meta for first use
			$arrowd_disable_guttenberg_from_pages_var = get_term_meta('2020', 'arrowd_disable_guttenberg_from_pages_var_meta', true);
			if(is_null($arrowd_disable_guttenberg_from_pages_var)or $arrowd_disable_guttenberg_from_pages_var==''){

				if(add_term_meta('2020', 'arrowd_disable_guttenberg_from_pages_var_meta' , 'pages_guten_enabled')){

				}//end added
			else
			{
			}
		}//end not added and not already set
	//update if already set
	if(($arrowd_disable_guttenberg_from_pages_var=='pages_guten_disabed') or ($arrowd_disable_guttenberg_from_pages_var==''))
	{
			if(update_term_meta('2020', 'arrowd_disable_guttenberg_from_pages_var_meta' , "pages_guten_enabled"))
							{

			}
			else
			{

			}
	}//end if already set


}//end setting the block wid to enabled
////////////////////////////////////////////////guttenberg pages enabled / disabled ////////////////END///////////////////

////////////////////////////////////////////////guttenberg posts enabled / disabled ////////////////START///////////////////
if(!empty($third_input))
	{
	//start setting the block wid to disabled

		//add meta for first use
		$arrowd_disable_guttenberg_from_posts_var = get_term_meta('2020', 'arrowd_disable_guttenberg_from_posts_var_meta', true);

	if(is_null($arrowd_disable_guttenberg_from_posts_var)or $arrowd_disable_guttenberg_from_posts_var==''){
			if(add_term_meta('2020', 'arrowd_disable_guttenberg_from_posts_var_meta' , 'posts_guten_disabed'))
			{

			}
			else
			{

			}

		}//end adding meta for gutenberg wid disabled

		//update if already set
		if(($arrowd_disable_guttenberg_from_posts_var=='posts_guten_enabled') or ($arrowd_disable_guttenberg_from_posts_var=='')){
				if(update_term_meta('2020', 'arrowd_disable_guttenberg_from_posts_var_meta' , 'posts_guten_disabed'))
								{

			}
			else
			{

			}
			}//end update to disabled wid
	}//end setting the block wid to disabled
	else
	//start setting the block wid to enabled
	{
	//$second_input='';


		//add meta for first use
			$arrowd_disable_guttenberg_from_posts_var = get_term_meta('2020', 'arrowd_disable_guttenberg_from_posts_var_meta', true);
			if(is_null($arrowd_disable_guttenberg_from_posts_var)or $arrowd_disable_guttenberg_from_posts_var==''){

				if(add_term_meta('2020', 'arrowd_disable_guttenberg_from_posts_var_meta' , 'posts_guten_enabled')){

				}//end added
			else
			{}
		}//end not added and not already set
	//update if already set
	if(($arrowd_disable_guttenberg_from_posts_var=='posts_guten_disabed') or ($arrowd_disable_guttenberg_from_posts_var==''))
	{
			if(update_term_meta('2020', 'arrowd_disable_guttenberg_from_posts_var_meta' , "posts_guten_enabled"))
							{

			}
			else
			{

			}
	}//end if already set


}//end setting the block wid to enabled
////////////////////////////////////////////////guttenberg posts enabled / disabled ////////////////END///////////////////


////////////////////////////////////////////////guttenberg all enabled / disabled ////////////////START///////////////////
if(!empty($fourth_input))
	{
	//start setting the block wid to disabled

		//add meta for first use
		$arrowd_disable_guttenberg_from_all_var = get_term_meta('2020', 'arrowd_disable_guttenberg_from_all_var_meta', true);

	if(is_null($arrowd_disable_guttenberg_from_all_var)or $arrowd_disable_guttenberg_from_all_var==''){
			if(add_term_meta('2020', 'arrowd_disable_guttenberg_from_all_var_meta' , 'all_guten_disabed'))
			{

			}
			else
			{

			}

		}//end adding meta for gutenberg wid disabled

		//update if already set
		if(($arrowd_disable_guttenberg_from_all_var=='all_guten_enabled') or ($arrowd_disable_guttenberg_from_all_var=='')){
				if(update_term_meta('2020', 'arrowd_disable_guttenberg_from_all_var_meta' , 'all_guten_disabed'))
								{

			}
			else
			{

			}
			}//end update to disabled wid
	}//end setting the block wid to disabled
	else
	//start setting the block wid to enabled
	{
	//$second_input='';


		//add meta for first use
			$arrowd_disable_guttenberg_from_all_var = get_term_meta('2020', 'arrowd_disable_guttenberg_from_all_var_meta', true);
			if(is_null($arrowd_disable_guttenberg_from_all_var)or $arrowd_disable_guttenberg_from_all_var==''){
				echo'term is null';
				if(add_term_meta('2020', 'arrowd_disable_guttenberg_from_all_var_meta' , 'all_guten_enabled')){

				}//end added
			else
			{}
		}//end not added and not already set
	//update if already set
	if(($arrowd_disable_guttenberg_from_all_var=='all_guten_disabed') or ($arrowd_disable_guttenberg_from_all_var==''))
	{
			if(update_term_meta('2020', 'arrowd_disable_guttenberg_from_all_var_meta' , "all_guten_enabled"))
							{

			}
			else
			{

			}
	}//end if already set


}//end setting the block wid to enabled
////////////////////////////////////////////////guttenberg all enabled / disabled ////////////////END///////////////////




	}//end if button was clicked


//first meta term check and setting for form radios ==start==
$firstMetaTerm =get_term_meta('2020', 'arrowd_disable_guttenberg_from_wds_var_meta', true);


		if($firstMetaTerm=='wids_guten_disabed')
		{			$first_input='checked="checked"';			}
			else
		{			$first_input='';							}
//first meta term check and setting for form radios ==end==


//second meta term check and setting for form radios ==start==
$secondMetaTerm =get_term_meta('2020', 'arrowd_disable_guttenberg_from_pages_var_meta', true);


		if($secondMetaTerm=='pages_guten_disabed')
		{			$second_input='checked="checked"';			}
			else
		{			$second_input='';							}
//second meta term check and setting for form radios ==end==

//third meta term check and setting for form radios ==start==
$thirdMetaTerm =get_term_meta('2020', 'arrowd_disable_guttenberg_from_posts_var_meta', true);


		if($thirdMetaTerm=='posts_guten_disabed')
		{			$third_input='checked="checked"';		}
			else
		{			$third_input='';						}
//third meta term check and setting for form radios ==end==


	//fourth meta term check and setting for form radios ==start==
$fouthMetaTerm =get_term_meta('2020', 'arrowd_disable_guttenberg_from_all_var_meta', true);


		if($fouthMetaTerm=='all_guten_disabed')
		{
			$first_input='checked="checked"';
			$second_input='checked="checked"';
			$third_input='checked="checked"';
			$fourth_input='checked="checked"';
		}
			else
		{			$fourth_input='';					}
//fourth meta term check and setting for form radios ==end==

		?>
		<!-- form for saving and displaying the text -->
 <br/>

 <form method="POST" action="">
<table class="switchesAndTextTable">
<tr>
<td>
<label class="switch">
   <input type="checkbox" name="first" value="first" <?php echo $first_input; ?> >
	 <span class="slider round"></span>
	</td>

	<td>
		<p>Mark as checked if you wish to disable Guttenberg from <b>widgets only</b>.</p>


<td>
</tr>

<tr>
<td>

<label class="switch">
  <input type="checkbox" name="second" value="second"<?php echo $second_input;?> >
	 <span class="slider round"></span>
	</td><td>
	<p>Mark as checked if you wish to disable Guttenberg from <b>pages only</b>.</p>


</td>
</tr>

	<tr>
<td>

<label class="switch">
  <input type="checkbox" name="third" value="third"<?php echo $third_input;?> >
	 <span class="slider round"></span>
	</td><td>
		<p>Mark as checked if you wish to disable Guttenberg from <b>posts only</b>.</p>


</td>
</tr>

<tr>
<td>

<label class="switch">
  <input type="checkbox" name="fourth" value="fourth"<?php echo $fourth_input;?> >
	 <span class="slider round"></span>
		</td><td>
	<p>Mark as checked if you wish to <b>disable Guttenberg from everything</b>.</p>


</td>
</tr>

<tr><td></td><td></td></tr>

	<tr>

		<td colspan="1">
			<div class="center-btn" style="text-align: center; margin: auto; display: flex; justify-content: center;">
				<button class="button-primary-update-names-and-titles" name="btn-to-update-users-selection-of-gutenberg" type="submit" style="margin-right: 0;">Update Settings</button>
				</div>			       	<!--button div-->
       </td>
	</tr>

</table>

</form>




        <?php


//$blabla =get_term_meta('2020', 'arrowd_disable_guttenberg_from_wds_var_meta', true);

}

//update the readout -->start
function arrowd_updateThe_gutenberg_Readout(){
global $wpdb;


//		echo"silience is golden";
}//end function
//update the readout -->end



function arrowd_gutenberg_load_custom_wp_admin_style() {

	$plugin_url = plugin_dir_url( __FILE__ );

        wp_register_style( 'custom_css', $plugin_url . 'css/style.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_css' );


        wp_enqueue_script( 'my_script', $plugin_url .  'logic.js' );
}
add_action( 'admin_enqueue_scripts', 'arrowd_gutenberg_load_custom_wp_admin_style' );
<?php

/*
  Plugin Name: Classic Editor on off
  Plugin URI: https://arrowdesign.ie/gutenberg-options/
  Description: Classic Editor on off is a plugin that gives the ability to turn off all or some of Gutenberg, reverting to classic editor - where needed. Gutenberg (Gutenberg Blocks / Gutenberg Editor) replaced the Classic Editor but there may be times you wish to use the classic editor. Disable Gutenberg Blocks completely, or choose to disable for widgets, posts or pages.
  Version: 1.2
  Author: Arrow Design
  Author URI: https://arrowdesign.ie
 */

// Exit if accessed directly
  if (!defined('ABSPATH'))
    exit;

/*
* Admin panel for turning off Gutenberg
*
*/
include_once 'admin/admin.php';

/*
* Hook function for Gutenberg from
* wordpress database
*/


	function arrowdesign_ie_removal_of_gutenberg_main() {

//first meta term check and setting for form radios ==start==
$firstMetaTerm =get_term_meta('2020', 'arrowd_disable_guttenberg_from_wds_var_meta', true);


		if($firstMetaTerm=='wids_guten_disabed')
		{
		// Disables the block editor from managing widgets in the Gutenberg plugin.
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 100 );

		// Disables the block editor from managing widgets. renamed from wp_use_widgets_block_editor
		add_filter( 'use_widgets_block_editor', '__return_false' );

		}
			else
		{
		//silence is golden
		}
//first meta term check and setting for form radios ==end==


//second meta term check and setting for form radios ==start==
$secondMetaTerm =get_term_meta('2020', 'arrowd_disable_guttenberg_from_pages_var_meta', true);


		if($secondMetaTerm=='pages_guten_disabed')
		{
		add_filter( 'use_block_editor_for_post', 'arrowdesign_disable_gutenberg_pagesonly', 10, 2 );
		}
			else
		{
				//silence is golden
		}
//second meta term check and setting for form radios ==end==

//third meta term check and setting for form radios ==start==
$thirdMetaTerm =get_term_meta('2020', 'arrowd_disable_guttenberg_from_posts_var_meta', true);


		if($thirdMetaTerm=='posts_guten_disabed')
		{
		add_filter( 'use_block_editor_for_post', 'arrowdesign_disable_gutenberg_postsonly', 10, 2 );
		}
			else
		{
		//silience is golden
		}
//third meta term check and setting for form radios ==end==


	//fourth meta term check and setting for form radios ==start==
$fouthMetaTerm =get_term_meta('2020', 'arrowd_disable_guttenberg_from_all_var_meta', true);


		if($fouthMetaTerm=='all_guten_disabed')
		{
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 100 );
		add_filter( 'use_widgets_block_editor', '__return_false' );
		add_filter( 'use_block_editor_for_post', 'arrowdesign_disable_gutenberg_pagesonly', 10, 2 );
		add_filter( 'use_block_editor_for_post', 'arrowdesign_disable_gutenberg_postsonly', 10, 2 );
		}
			else
		{
		//silence is golden
		}
//fourth meta term check and setting for form radios ==end==


	}//end function




	//add settings link to plugin page
	function arrowd__removal_of_gutenberg__settings_link($links) {
	  $settings_link__removal_of_gutenberg__settings_link  = '<a href="options-general.php?page=arrowdesign__gut_options__admin_page.php">Settings</a>';
	  array_unshift($links, $settings_link__removal_of_gutenberg__settings_link );
	  return $links;
	}
	$plugin_arrowd__removal_of_gutenberg = plugin_basename(__FILE__);
	add_filter("plugin_action_links_$plugin_arrowd__removal_of_gutenberg", 'arrowd__removal_of_gutenberg__settings_link' );

	//add documentation link and support link to plugin page
	function arrowddub_removal_of_gutenberg_main_doc_meta( $arrowd_removal_of_gutenberg_plugin_links, $file ) {
		if ( plugin_basename( __FILE__ ) == $file ) {
			$arrowd_plugin_row_meta_removal_of_gutenberg = array(
			  'arrowd_ removal_of_gutenberg_plugin_docs'    => '<a href="' . esc_url( 'https://arrowdesign.ie/gutenberg-options/' ) . '" target="_blank" aria-label="' . esc_attr__( 'Plugin Additional Links', 'domain' ) . '" >' . esc_html__( 'Documentation', 'domain' ) . '</a>',

			'arrowd_ removal_of_gutenberg_plugin_support'    => '<a href="' . esc_url( 'https://arrowdesign.ie/contact-arrow-design-2/' ) . '" target="_blank" aria-label="' . esc_attr__( 'Plugin Additional Links', 'domain' ) . '" >' . esc_html__( 'Support', 'domain' ) . '</a>'
			);



			return is_null($arrowd_removal_of_gutenberg_plugin_links) ? $arrowd_plugin_row_meta_removal_of_gutenberg : array_merge( $arrowd_removal_of_gutenberg_plugin_links, $arrowd_plugin_row_meta_removal_of_gutenberg );
	}
return (array) $arrowd_removal_of_gutenberg_plugin_links;
	}

		add_filter( 'plugin_row_meta', 'arrowddub_removal_of_gutenberg_main_doc_meta', 10, 2 );
add_action( 'plugins_loaded', 'arrowdesign_ie_removal_of_gutenberg_main', 10, 2 );

//disable the gutenberg posts -->start
function arrowdesign_disable_gutenberg_postsonly( $can_edit, $post ) {
  if( $post->post_type == 'post' ) {
    return false;
  }

  return true;
}
//disable the gutenberg posts -->end


//disable the gutenberg pages -->start
function arrowdesign_disable_gutenberg_pagesonly( $can_edit, $post ) {
  if( $post->post_type == 'page' ) {
    return false;
  }

  return true;
}
//disable the gutenberg page -->end
?>
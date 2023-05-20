<?php
/**
 * Plugin Name: Custom Elementor Widgets
 * Description: Custom list widgets for Elementor.
 * Plugin URI:  https://elementor.com/
 * Version:     1.0.0
 * Author:      Elementor Developer
 * Author URI:  https://developers.elementor.com/
 * Text Domain: elementor-custom-list-widget
 *
 * Elementor tested up to: 3.5.0
 * Elementor Pro tested up to: 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register List Widget.
 *
 * Include widget file and register widget class.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
define( 'PLUGIN_DIR', plugins_url().'/elementor-custom-list-widget/');

function register_custom_list_widgets( $widgets_manager ) {

	if(post_type_exists('post')){
		require_once( __DIR__ . '/widgets/article-list-widget.php' );
		$widgets_manager->register( new \Elementor_Article_List_Widget() );

		// Loaded files for artilces with filter block.
		require_once( __DIR__ . '/widgets/article-list-with-filters-widget.php' );
		$widgets_manager->register( new \Elementor_Article_List_With_Filters_Widget() );
	}
	
	if(post_type_exists('interview')){
		require_once( __DIR__ . '/widgets/interview-list-widget.php' );	
		$widgets_manager->register( new \Elementor_Interview_List_Widget() );

		// Loaded files for artilces with filter block.
		require_once( __DIR__ . '/widgets/interview-list-with-filters-widget.php' );
		$widgets_manager->register( new \Elementor_Interview_List_With_Filters_Widget() );
	}
	
	if(post_type_exists('post')){  // need to change product whenever installed woocommerce
		require_once( __DIR__ . '/widgets/publication-list-widget.php' );
		$widgets_manager->register( new \Elementor_Publication_List_Widget );		
	}
	
	if(post_type_exists('tribe_events')){  // need to change product whenever installed woocommerce
		require_once( __DIR__ . '/widgets/past-events-list-widget.php' );
		$widgets_manager->register( new \Elementor_Past_Events_List_Widget );		

		require_once( __DIR__ . '/widgets/upcoming-events-list-widget.php' );
		$widgets_manager->register( new \Elementor_Upcoming_Events_List_Widget );		

		require_once( __DIR__ . '/widgets/upcoming-events-list-with-filter-widget.php' );
		$widgets_manager->register( new \Elementor_Upcoming_Events_List_With_Filter_Widget );		
		
	}

	if(post_type_exists('press')){
		require_once( __DIR__ . '/widgets/press-list-with-filters-widget.php' );
		$widgets_manager->register( new \Elementor_Press_List_With_Filters_Widget() );
	}

	// loaded files for youtube video list page block for homepage
	require_once( __DIR__ . '/widgets/youtube-video-list-widget.php' );
	$widgets_manager->register( new \Elementor_Youtube_Video_List_Widget() );

	require_once( __DIR__ . '/widgets/homepage-banner-section-widget.php' );
	$widgets_manager->register( new \Elementor_Homepage_Banner_Section_Widget() );

	if(post_type_exists('partnered-event')){
		require_once( __DIR__ . '/widgets/partnered-events-list-widget.php' );
		$widgets_manager->register( new \Elementor_Partnered_Events_List_Widget() );

		require_once( __DIR__ . '/widgets/upcoming-partnered-events-list-widget.php' );
		$widgets_manager->register( new \Elementor_Upcoming_Partnered_Events_List_Widget() );
	}

}
add_action( 'elementor/widgets/register', 'register_custom_list_widgets' );

// General functions file 
require_once( __DIR__ . '/general-functions.php' );

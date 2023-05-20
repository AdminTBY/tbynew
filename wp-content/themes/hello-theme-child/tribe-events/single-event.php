<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.19
 *
 */

if (!defined('ABSPATH')) {
    die('-1');
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural = tribe_get_event_label_plural();

$event_id = get_the_ID();

/**
 * Allows filtering of the single event template title classes.
 *
 * @since 5.8.0
 *
 * @param array  $title_classes List of classes to create the class string from.
 * @param string $event_id The ID of the displayed event.
 */
$title_classes = apply_filters('tribe_events_single_event_title_classes', ['tribe-events-single-event-title'], $event_id);
$title_classes = implode(' ', tribe_get_classes($title_classes));

/**
 * Allows filtering of the single event template title before HTML.
 *
 * @since 5.8.0
 *
 * @param string $before HTML string to display before the title text.
 * @param string $event_id The ID of the displayed event.
 */
$before = apply_filters('tribe_events_single_event_title_html_before', '<h1 class="' . $title_classes . '">', $event_id);

/**
 * Allows filtering of the single event template title after HTML.
 *
 * @since 5.8.0
 *
 * @param string $after HTML string to display after the title text.
 * @param string $event_id The ID of the displayed event.
 */
$after = apply_filters('tribe_events_single_event_title_html_after', '</h1>', $event_id);

/**
 * Allows filtering of the single event template title HTML.
 *
 * @since 5.8.0
 *
 * @param string $after HTML string to display. Return an empty string to not display the title.
 * @param string $event_id The ID of the displayed event.
 */
$title = apply_filters('tribe_events_single_event_title_html', the_title($before, $after, false), $event_id);

$city_upcoming_post = get_post_meta($event_id, '_EventVenueID', true);

$organization_event_id = get_post_meta($event_id, '_EventOrganizerID', true);
$organization_event_email = get_post_meta($organization_event_id, '_OrganizerEmail', true);

$postevenykey = $city_upcoming_post;

$post_venueaddress = get_post_meta($postevenykey, '_VenueAddress', true);
$venue_lattitude = get_post_meta($postevenykey, '_VenueLat', true);
$venue_longtitute = get_post_meta($postevenykey, '_VenueLng', true);
$getevent_venue_city = get_post_meta($postevenykey, '_VenueCity', true) ? get_post_meta($postevenykey, '_VenueCity', true) . ", " : '';
$getevent_venue_country = get_post_meta($postevenykey, '_VenueCountry', true);
// Start Date event Converter
$getfull_startend_date = get_post_meta($event_id, '_EventStartDate', true);
$format = 'Y-m-d H:i:s';
$date = DateTime::createFromFormat($format, $getfull_startend_date);
$get_start_date_time = $date->format('H:i') . "\n";
$get_event_timezone = get_post_meta($event_id, '_EventTimezone', true);
$convert_start_date_object = new DateTime($getfull_startend_date);

$start_date_with_remove_time = $convert_start_date_object->format('Y-m-d');
$convert_start_date_with_name = date("M d Y", strtotime($start_date_with_remove_time));
$get_only_start_day_name = date('l', strtotime($start_date_with_remove_time));
// End Date Event Converter
$getfull_eventend_date = get_post_meta($event_id, '_EventEndDate', true);
$format = 'Y-m-d H:i:s';
$date_end = DateTime::createFromFormat($format, $getfull_eventend_date);
$get_end_date_time = $date_end->format('H:i') . "\n";
$convert_end_date_object = new DateTime($getfull_eventend_date);
$end_date_with_remove_time = $convert_end_date_object->format('Y-m-d');
$convert_end_date_with_name = date("M d, Y", strtotime($end_date_with_remove_time));
$get_only_end_day_name = date('l', strtotime($end_date_with_remove_time));
?>


<div id="tribe-events-content childtheme" class="tribe-events-single">

   

    <!-- Event header -->
    <div id="tribe-events-header" <?php tribe_events_the_header_attributes(); ?>>
        <!-- Navigation -->
        <nav class="tribe-events-nav-pagination"
            aria-label="<?php printf(esc_html__('%s Navigation', 'the-events-calendar'), $events_label_singular); ?>">
            <ul class="tribe-events-sub-nav">
                <li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link('<span>&laquo;</span> %title%'); ?>
                </li>
                <li class="tribe-events-nav-next"><?php tribe_the_next_event_link('%title% <span>&raquo;</span>'); ?></li>
            </ul>
            <!-- .tribe-events-sub-nav -->
        </nav>
    </div>
    <!-- #tribe-events-header -->

    <?php while (have_posts()):
        the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <!-- Event featured image, but exclude link -->
        <div class="event-detail-feature-image">
            <?php $terms = get_the_terms($post->ID, 'tribe_events_cat');
                if(!empty($terms)){?>
                    <span class="event-category">
                        <?php                
                        foreach ($terms as $term) {
                            echo "<div class='event-detail-category sub-description text-white text-uppercase'>" . $term->name . "</div>";
                        }
                        ?>
                    </span>
            <?php }
                                
                date_default_timezone_set($get_event_timezone);
                $date_for_menu = new DateTime();
                $today = $date_for_menu->getTimestamp();
                $current_date_time = date($format, $today);            
                $event_start_date = $convert_start_date_object->format($format);
                $minus_time_array = explode('-',$get_event_timezone);
                $plus_time_array  = explode('+',$get_event_timezone);
                
                if(count($minus_time_array) > 1){
                    $minus_hours = '-'.$minus_time_array[1].' hours'; 
                    $current_date_time = date($format, strtotime($minus_hours,strtotime($current_date_time)));                    
                }
                if(count($plus_time_array) > 1){
                    $plus_hours = '+'.$plus_time_array[1].' hours'; 
                    $current_date_time = date($format, strtotime($plus_hours,strtotime($current_date_time)));
                }
                
                $c_date = strtotime($current_date_time);
                $date2 = strtotime($event_start_date);                                
                
                $event_video = get_field('video_for_event_detail_page');

                if(!empty($event_video) && $date2 < $c_date){
                    echo "<div class='event-iframe-container'>";
                    echo $event_video;
                    echo "</div>";
                }else{
                    echo tribe_event_featured_image($event_id, 'full', false); 
                }                       

                if ($c_date < $date2): ?>
                    <a class="all-event-btn description text-white text-uppercase"
                        href="<?php echo get_permalink(get_page_by_path('upcoming')); ?>">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/grid-icon.png" alt="grid-icon" />
                        <?php printf(' ' . esc_html_x('All %s', '%s Events plural label', 'the-events-calendar'), $events_label_plural); ?>
                    </a>
                <?php else: ?>
                    <a class="all-event-btn description text-white text-uppercase"
                        href="<?php echo get_permalink(get_page_by_path('past')); ?>">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/grid-icon.png" alt="grid-icon" />
                        <?php printf(' ' . esc_html_x('All %s', '%s Events plural label', 'the-events-calendar'), $events_label_plural); ?>
                    </a>
                    <?php endif;
                ?>
        </div>
        <div class="section-title">
            <?php echo $title; ?>
        </div>

        <div class="tribe-events-schedule tribe-clearfix">
            <div class="event-detail-time text-red font-bold sub-title">
                    <?php echo $get_only_start_day_name .
                        ' / ' .
                        $convert_start_date_with_name .
                        ' ' .
                        $get_start_date_time .
                        ' - ' .
                        $get_end_date_time .
                        '' .
                        $get_event_timezone .
                        ' </br> ' .
                        $getevent_venue_city .
                        '' .
                        $getevent_venue_country; ?>
            </div>
                <?php 
                    $show_register_button = get_field('show_register_button');
                    if ($c_date < $date2 && $show_register_button){ 
                        $view_more_registration_url = get_field('register_button_link');
                        if(!empty($view_more_registration_url)){
                            $register_btn_link = $view_more_registration_url['url'] ? $view_more_registration_url['url'] : '#';
                            $link_title = $view_more_registration_url['title'] ? $view_more_registration_url['title'] : "Register";
                            $link_target = $view_more_registration_url['target'] ? $view_more_registration_url['target'] : '_self';?>
                            <div class="event-detail-register-btn">
                                <a target="<?php echo $link_target;?>" href="<?php echo $register_btn_link;?>"><button class="eventbtn"><?php echo $link_title;?></button></a>
                            </div> 
                    <?php }else{?>
                            <div class="event-detail-register-btn">
                                <a href="#"><button class="eventbtn">Register</button></a>
                            </div>
                    <?php }
                                           
                     } ?>
        </div>

        <!-- Event content -->
        <?php do_action('tribe_events_single_event_before_the_content'); ?>

        <div class="tribe-events-single-event-description tribe-events-content">
            <div class="tribe-events-content-inner">
                <h2 class="sub-section-title text-black font-bold border-line m-t-0">Overview</h2>

                <?php the_content(); ?>

				
				       <!-- Speaker list -->        
        <?php get_template_part( 'event-templates/event', 'speakers' ); ?>      
        <!-- End of Event Speaker List -->
				
				
				
				
				  
            </div>
        </div>

	
        <!-- Nested Inside Repeater Section Start -->
        <?php get_template_part( 'event-templates/event', 'sponser' ); ?>       
        <!-- .tribe-events-single-event-description -->

					
    </div> <!-- #post-x -->
	
	
        <div class="tribe-events-single-event-description tribe-events-content">
            <div class="tribe-events-content-inner">

								   				
				<!-- Event Detail CTA code -->
            <?php get_template_part( 'event-templates/event', 'cta' ); ?>      
            <!-- End of Event Detail CTA code -->
				 <p>
	 
				</p><p> </p><br><br>
				
				
                <!-- Contact Information Code -->
                <div class="get-contact-details">
                    
<div class="venue-informati0n">
                        <div class="block-inner">
                            <h5 class="sub-title text-black font-bold text-uppercase">Venue</h5>
                            <div class="street-address-event description font-regular text-black">
                                <?php echo get_the_title($postevenykey) . '</br>' . $post_venueaddress . '</br>' . $getevent_venue_city; ?>
                            </div>
                        </div>

                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $venue_lattitude . ',' . $venue_longtitute; ?>"
                            target="_blank" class="eventaddress">Get Directions</a>

                    </div>
					
                    <!-- Contact information -->
                    <?php get_template_part( 'event-templates/event', 'contactus' ); ?>  
                    <!-- nd of Contact information -->                   
                   
                    <!-- Become a Sponser Popup -->
                    <?php get_template_part( 'event-templates/event', 'becomesponser' ); ?>  
                    <!-- End of Become a Sponser Popup -->

                </div>
		
				
				
				  
            </div>
        </div>
	
	
	
	
    <?php
    endwhile; ?>



</div><!-- #tribe-events-content -->

<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor List Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */

class Elementor_Upcoming_Events_List_With_Filter_Widget extends \Elementor\Widget_Base {
	
	/**
	 * Get widget name.
	 *
	 * Retrieve list widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'upcoming_events_with_filter';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve list widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Upcoming/Past Events With Filter', 'elementor-custom-list-widget' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve list widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-filter';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	* Add widget styles.
	*
	* Add styles and load css files for our custom widget.
	*
	* @since 1.0.0
	* @access public
	* @return array Widget styles.
	*/
	
	public function get_style_depends() {
		wp_register_style( 'jquery-ui', PLUGIN_DIR .'assets/css/jquery-ui.css' );
		wp_register_style( 'upcoming-event-list-with-filter-css', PLUGIN_DIR .'assets/css/upcoming-event-list-with-filter.css' );
		
		return [			
			'upcoming-event-list-with-filter-css',
			'jquery-ui'
		];

	}

	/**
	* Add widget script.
	*
	* Add script and load js files for our custom widget.
	*
	* @since 1.0.0
	* @access public
	* @return array Widget scripts.
	*/
	public function get_script_depends() {
		
		wp_register_script( 'upcoming-event-list-with-filter-js', PLUGIN_DIR .'assets/js/upcoming-event-list-with-filter.js' );
		wp_localize_script( 'upcoming-event-list-with-filter-js', 'my_ajax_object', array( 
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce('ajax-nonce')
			) );
		return [
			'upcoming-event-list-with-filter-js',
			'jquery-ui-datepicker'			
		];

	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return ['Upcoming Events', 'Events' ];
	}

	/**
	 * Register list widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */ 
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Upcoming Events', 'elementor-custom-list-widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'upcoming_event_list_with_filter_title',
			[
				'label' => esc_html__( 'Title', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Upcoming Events', 'elementor-custom-list-widget' ),
				'placeholder' => esc_html__( 'Upcoming Events', 'elementor-custom-list-widget' ),
			]
		);		

		$this->add_control(
			'comming_events_layouts',
			[
				'label' => esc_html__( 'Choose Event type', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'upcomming-events',
				'options' => [
					'upcomming-events'  => esc_html__( 'Upcoming Events', 'elementor-custom-list-widget' ),
					'past-events'  => esc_html__( 'Past Events', 'elementor-custom-list-widget' ),
				],
			]
		);



		/* End repeater */

		$this->end_controls_section();

	}

	/**
	 * Render list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		//list of categories args
		$parent_dropdown_class = 'dropdown-icon';
		$all_countries_with_events = tby_get_terms_by_post_type(array('country'),array('tribe_events'));
		$args = array( 'orderby' => 'slug', 'hide_empty' => 1, 'parent' => 0 ); 
		$categories = get_terms('country', $args );
		// Getting custom taxonomy for events
		$event_args = array( 'orderby' => 'slug', 'hide_empty' => 1, 'parent' => 0 ); 
		$event_categories = get_terms('tribe_events_cat', $args );

		$arrow_icon = PLUGIN_DIR.'/icons/arrow.png';
		if($settings['comming_events_layouts'] == 'past-events')	{
			$past_event_class = 'past-event-section';
		}
		if( !empty($settings['upcoming_event_list_with_filter_title'])) {
			$article_filter_list_title = $settings['upcoming_event_list_with_filter_title'];
			echo "<div class='article-filter-main-title'>
				<h2 class='section-title text-black font-bold border-line m-t-0'>$article_filter_list_title</h2>
			</div>";			
		}
		echo "<div class='article-filter-main-outer upcoming-events-filter-main-outer $past_event_class'><div class='spinner-wrapper'><div class='nb-spinner'></div></div>";				
		echo "<div class='article-filter-main-left-outer'>";
			echo "<p class='title font-bold text-black filter-main-text'>Filters</p>";			
			// Countries start 
			echo "<ul>";					
			echo "<li class='upcoming-events-filter-countries-ul active'><div><span class='$parent_dropdown_class sub-title font-medium text-black'>Country</span><img class='$parent_dropdown_class arrow' src='$arrow_icon' alt='' /></div>";
			echo "<ul>";
			echo "<li><span class='description text-black font-medium upcoming-event-filter' value='all_country'>All Countries</span></li>";
			
			if (!empty($categories)) {
				foreach ( $categories as $List_category ) {
					if(tby_chk_country_exist($all_countries_with_events,$List_category->term_id)){
						$all_child_categories = get_terms('country',array(
							'orderby'			  => 'name',
							'order'   			  => 'ASC',
							'child_of'            => $List_category->term_id, //set your category ID
							'hide_empty'          => 1,
							'hide_title_if_empty' => false,					
						));			
								
						if(!empty($all_child_categories)){
							echo "<li><div><span class='description text-black font-medium upcoming-event-filter' value='$List_category->term_id'>$List_category->name</span> <img class='$parent_dropdown_class arrow' src='$arrow_icon' alt='' /></div>";
							echo "<ul>";
							foreach ( $all_child_categories as $List_child_category ) {		
								if(tby_chk_country_exist($all_countries_with_events,$List_child_category->term_id)){
									echo "<li><span class='upcoming-event-filter' value='$List_child_category->term_id'>$List_child_category->name</span></li>";
								}
							}
							echo "</ul>";												
						}else{
							echo "<li><span class='description text-black font-medium upcoming-event-filter' value='$List_category->term_id'>$List_category->name</span>";
						}
						echo "</li>";
					}
				}	
			}
			echo "</ul>";
			echo "</li>";
			
			// Countries END..
			
			// Event Categories html started				
			echo "<li class='upcoming-events-filter-categories-ul'><div><span class='$parent_dropdown_class sub-title font-medium text-black'>Category</span><img class='$parent_dropdown_class arrow' src='$arrow_icon' alt='' /></div>";
			echo "<ul>";
			
			if (!empty($event_categories)) {
				foreach ( $event_categories as $List_category ) {				
						echo "<li><span class='description text-black font-medium upcoming-event-filter' value='$List_category->term_id'>$List_category->name</span></li>";				
				}	
			}
			echo "</ul>";
			echo "</li>";
			
			// Event Categories html END... 

            // Date filter html started 

				echo "<li class='upcoming-event-filter-date-ul'><div><span class='$parent_dropdown_class sub-title font-medium text-black'>Date</span><img class='$parent_dropdown_class arrow' src='$arrow_icon' alt='' /></div>";
				echo "<ul>";				
					echo "<li><label for='upcoming_from_event_date'>From</label>
                    <input type='text' id='upcoming_from_event_date' class='date-input' name='upcoming_from_event_date'></li>";					
				echo "</ul>";
				echo "<ul>";				
					echo "<li><label for='upcoming_to_event_date'>To</label>
                    <input type='text' id='upcoming_to_event_date' class='date-input' name='upcoming_to_event_date' ></li>";					
				echo "</ul>";
				echo "</li>";		            
            // Date filter html end... 
	
			echo "</ul>";
			echo "<div class = 'upcoming-event-actions-btn'>
			<a class='upcoming-event-apply-btn' href='javascript:void(0);'>apply</a>
			<a class='upcoming-event-reset-btn' href='javascript:void(0);'>Reset</a>
			</div>";					
		echo "</div>";									
		?>

<!-- Upcoming Events list by filters start  -->
<?php if($settings['comming_events_layouts'] == 'upcomming-events')	{ ?>
<input type="hidden" name="upcoming_events_paged_track" class="upcoming_events_paged_track" value="2">
<input type="hidden" name="event_check" class="event_check" value="1"><!-- 1 for upcoming events -->
<div class="right-sec-article-filtered-list upcoming-event-list">
    <div class="upcoming-article-block-wrapper">
        <?php
				$date = new DateTime();
				$today = $date->getTimestamp(); 
				$current_date_time = date('Y-m-d H:i:s', $today);
                $default_posts_per_page = get_field('upcoming_events_per_page','option');
				
				$args =  array(
					'post_type' => 'tribe_events',
					'posts_per_page' => $default_posts_per_page,
					'meta_key' => '_EventStartDate',
                    'orderby' => '_EventStartDate', 
					'post_status' => 'publish',
                    'order' => 'ASC',
					'meta_query'    => array(
						'relation'      => 'AND',
						array(
							'key'       => '_EventStartDate',							
							'value'     => $current_date_time,
                            'compare'   => '>=',
                            'type' => 'DATETIME'
						)
					)

				);
				
				$query= new WP_Query($args);
				
				global $post;

				if( $query->have_posts() ):
					while ( $query->have_posts() ) : $query->the_post();?>
        		<div class="article-block-inner">
            	<?php
						$post_id = $post->ID;
						$image_id = get_post_thumbnail_id();
						$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
						$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
						$detail_page_url  	= get_permalink($post_id);
						$image               = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : PLUGIN_DIR .'assets/images/NoImageAvailable.png';
						$title 				= get_the_title();
                        $upcoming_post_content = get_the_content();						
                        $city_upcoming_post = get_post_meta( $post_id, '_EventVenueID', true  ); 
                        $postevenykey = $city_upcoming_post;
						$getevent_venue_city    = get_post_meta( $postevenykey, '_VenueCity', true  ) ? get_post_meta( $postevenykey, '_VenueCity', true  ).", " : '';
						$getevent_venue_country = get_post_meta( $postevenykey, '_VenueCountry', true  );
						// Start Date event Converter
						$getfull_startend_date  = get_post_meta( $post->ID, '_EventStartDate', true  );
						$format = 'Y-m-d H:i:s';
						$date = DateTime::createFromFormat($format, $getfull_startend_date);
						$get_start_date_time = $date->format('H:i') . "\n";
						$get_event_timezone = get_post_meta( $post->ID, '_EventTimezone', true  );
						$convert_start_date_object = new DateTime($getfull_startend_date);
						$start_date_with_remove_time = $convert_start_date_object->format('Y-m-d');
						$convert_start_date_with_name = date("M d, Y", strtotime($start_date_with_remove_time));
						$get_only_start_day_name = date('l', strtotime($start_date_with_remove_time));
						// End Date Event Converter
						$getfull_eventend_date  = get_post_meta( $post->ID, '_EventEndDate', true  );
						$date_end = DateTime::createFromFormat($format, $getfull_eventend_date);
						$get_end_date_time = $date_end->format('H:i') . "\n";
						$event_invitation_type = get_post_meta( $post_id, 'events_invitation_type', true  );
					?>

            <div class="artcle-inner-content">
                <div class="article-list-thumb">
                    <img src='<?php echo $image;?>' alt="<?php echo $image_alt;?>" />
                    <?php 
						$terms = get_the_terms( $post->ID , 'tribe_events_cat' );
						if(!empty($terms)){?>
							<span class="event-category">
								<?php                            
								foreach ( $terms as $term ) {
									echo "<span class='sub-description text-white text-uppercase'>".$term->name."</span>";
								}
								?>
							</span>
					<?php } ?>
                </div>

                <div class="article-list-content-sec">
                    <div class="article-inner-content">
                        <a href="<?php echo $detail_page_url;?>">
                            <h3 class="article-main-title title text-black font-bold"><?php echo $title;?></h3>
                            <p class="article-subject sub-title font-bold text-red"><?php								   								  
								    echo "$get_only_start_day_name / $convert_start_date_with_name $get_start_date_time - $get_end_date_time $get_event_timezone </br>$getevent_venue_city $getevent_venue_country";
								    ?></p>

                            <p class="upcoming-post-content description text-grey font-medium">
                                <?php echo wp_trim_words( $upcoming_post_content, 25, '...' ); ?>
                            </p>
                            <?php if($event_invitation_type): ?>
                            <p class="upcoming-post-content description text-grey font-medium">
                                <?php echo $event_invitation_type; ?>
                            </p>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; 
				else :
					echo "Stay tuned for more upcoming events soon.";
				endif;
			
			?>
    </div>
    <?php if ( $query->max_num_pages > 1 ){
					echo "<button class='load_more_upcoming_events_btn'>See more</button>";						
				}?>
</div>
<!-- Upcoming Event list by filters END... -->
<?php } else{ ?>
<input type="hidden" name="upcoming_events_paged_track" class="upcoming_events_paged_track" value="2">
<input type="hidden" name="event_check" class="event_check" value="2"><!-- 2 for past events -->
<div class="right-sec-article-filtered-list upcoming-event-list">
    <div class="upcoming-article-block-wrapper">
        <?php
				$date = new DateTime();
				$today = $date->getTimestamp(); 
				$current_date_time = date('Y-m-d H:i:s', $today);
                $default_posts_per_page = get_field('past_events_per_page','option');
				
				$args =  array(
					'post_type' => 'tribe_events',
					'posts_per_page' => $default_posts_per_page,
					'meta_key' => '_EventStartDate',
                    'orderby' => '_EventStartDate', 
					'post_status' => 'publish',
                    'order' => 'DESC',
					'meta_query'    => array(
						'relation'      => 'AND',
						array(
							'key'       => '_EventStartDate',							
							'value'     => $current_date_time,
                            'compare'   => '<=',
                            'type' => 'DATETIME'
						)
					)

				);
				
				$query= new WP_Query($args);
				
				global $post;

				if( $query->have_posts() ):
					while ( $query->have_posts() ) : $query->the_post();?>
				<div class="article-block-inner">
					<?php
						$post_id = $post->ID;
						$image_id = get_post_thumbnail_id();
						$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
						$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
						$detail_page_url  	= get_permalink($post_id);
						$image              = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : PLUGIN_DIR .'assets/images/NoImageAvailable.png';
						$title 				= get_the_title();
                        $upcoming_post_content = get_the_content();						
                        $city_upcoming_post = get_post_meta( $post_id, '_EventVenueID', true  ); 
                        $postevenykey = $city_upcoming_post;
						$getevent_venue_city    = get_post_meta( $postevenykey, '_VenueCity', true  ) ? get_post_meta( $postevenykey, '_VenueCity', true  ).", " : '';
						$getevent_venue_country = get_post_meta( $postevenykey, '_VenueCountry', true  );
						// Start Date event Converter
						$getfull_startend_date  = get_post_meta( $post->ID, '_EventStartDate', true  );
						$format = 'Y-m-d H:i:s';
						$date = DateTime::createFromFormat($format, $getfull_startend_date);
						$get_start_date_time = $date->format('H:i') . "\n";
						$get_event_timezone = get_post_meta( $post->ID, '_EventTimezone', true  );
						$convert_start_date_object = new DateTime($getfull_startend_date);
						$start_date_with_remove_time = $convert_start_date_object->format('Y-m-d');
						$convert_start_date_with_name = date("M d, Y", strtotime($start_date_with_remove_time));
						$get_only_start_day_name = date('l', strtotime($start_date_with_remove_time));
						// End Date Event Converter
						$getfull_eventend_date  = get_post_meta( $post->ID, '_EventEndDate', true  );
						$date_end = DateTime::createFromFormat($format, $getfull_eventend_date);
						$get_end_date_time = $date_end->format('H:i') . "\n";
						$event_invitation_type = get_post_meta( $post_id, 'events_invitation_type', true  );
					?>

            <div class="artcle-inner-content">
                <div class="article-list-thumb">
                    <img src='<?php echo $image;?>' alt="<?php echo $image_alt;?>" />
                    <?php 
						$terms = get_the_terms( $post->ID , 'tribe_events_cat' );
						if(!empty($terms)){?>
							<span class="event-category">
								<?php                            
								foreach ( $terms as $term ) {
									echo "<span class='sub-description text-white text-uppercase'>".$term->name."</span>";
								}
								?>
							</span>
					<?php } ?>
                </div>

                <div class="article-list-content-sec">
                    <div class="article-inner-content">
                        <a href="<?php echo $detail_page_url;?>">
                            <h3 class="article-main-title title text-black font-bold"><?php echo $title;?></h3>
                            <p class="article-subject sub-title font-bold text-red"><?php				   
										echo "$get_only_start_day_name / $convert_start_date_with_name </br>$get_start_date_time - $get_end_date_time $get_event_timezone </br>$getevent_venue_city $getevent_venue_country";
                                    ?></p>

                            <p class="upcoming-post-content description text-grey font-medium">
                                <?php echo wp_trim_words( $upcoming_post_content, 25, '...' ); ?>
                            </p>
                            <?php if($event_invitation_type): ?>
                            <p class="upcoming-post-content description text-grey font-medium">
                                <?php echo $event_invitation_type; ?>
                            </p>
                            <?php endif; ?>
                        </a>
                        <a class="article-view-more-btn" href="<?php echo $detail_page_url;?>">View More</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; 
				else :
					echo "Stay tuned for more upcoming events soon.";
				endif;
			
			?>
    </div>
    <?php if ( $query->max_num_pages > 1 ){
					echo "<button class='load_more_upcoming_events_btn'>See more</button>";						
				}?>
</div>
<?php } ?>
<?php echo "</div>";

	}

	/**
	 * Render list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
        
	}

}
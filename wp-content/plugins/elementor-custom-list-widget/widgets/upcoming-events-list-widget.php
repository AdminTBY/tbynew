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

class Elementor_Upcoming_Events_List_Widget extends \Elementor\Widget_Base {
	
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
		return 'upcoming_event';
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
		return esc_html__( 'Upcoming Events', 'elementor-custom-list-widget' );
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
		return 'eicon-post-slider';
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
		
		wp_register_style( 'swiper-min-css', PLUGIN_DIR .'assets/css/swiper-bundle.min.css' );		
		wp_register_style( 'latest-article-block-css', PLUGIN_DIR .'assets/css/latest-article-block.css' );
		
		return [			
			'swiper-min-css',
			'latest-article-block-css',
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
		
		wp_register_script( 'swiper-min-js', PLUGIN_DIR .'assets/js/swiper-bundle.min.js' );		
		
		return [
			'swiper-min-js',
			'latest-article-block-js',
			
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
			'event_title',
			[
				'label' => esc_html__( 'Title', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Upcoming Events', 'elementor-custom-list-widget' ),
				'placeholder' => esc_html__( 'Upcoming Events', 'elementor-custom-list-widget' ),
			]
		);	
		
		$this->add_control(
			'view_all_upcoming_events',
			[
				'label' => esc_html__( 'Show View All Upcoming Events Button', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementor-custom-list-widget' ),
				'label_off' => esc_html__( 'Hide', 'elementor-custom-list-widget' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'number_of_events',
			[
				'label' => esc_html__( 'Number Of Events', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'3'  => esc_html__( '3', 'elementor-custom-list-widget' ),
					'4' => 	esc_html__( '4', 'elementor-custom-list-widget' ),
					'5' => 	esc_html__( '5', 'elementor-custom-list-widget' ),
					'6' => 	esc_html__( '6', 'elementor-custom-list-widget' ),
					'7' => 	esc_html__( '7', 'elementor-custom-list-widget' ),
					'8'  => esc_html__( '8', 'elementor-custom-list-widget' ),
					'9' => 	esc_html__( '9', 'elementor-custom-list-widget' ),
					'10' => esc_html__( '10', 'elementor-custom-list-widget' ),
					'11' => esc_html__( '11', 'elementor-custom-list-widget' ),
					'12' => esc_html__( '12', 'elementor-custom-list-widget' ),
					'13' => esc_html__( '13', 'elementor-custom-list-widget' ),
					'14' => esc_html__( '14', 'elementor-custom-list-widget' ),
					'15' => esc_html__( '15', 'elementor-custom-list-widget' ),
					'16' => esc_html__( '16', 'elementor-custom-list-widget' ),
					'17' => esc_html__( '17', 'elementor-custom-list-widget' ),
					'18' => esc_html__( '18', 'elementor-custom-list-widget' ),
					'19' => esc_html__( '19', 'elementor-custom-list-widget' ),
					'20' => esc_html__( '20', 'elementor-custom-list-widget' ),
				],
			]
		);

        $args =  array(
            'post_type' => 'tribe_events',
            'posts_per_page' => 3,
        );

		$query= new WP_Query($args);
        $default = array();
		global $post;

        if( $query->have_posts() ):
            while ( $query->have_posts() ) : $query->the_post();			
			
			$post_id = $post->ID;
			$detail_page_url  	= get_permalink($post_id);
			$image 				= wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'medium' );
            $upcoming_post_content = get_the_content();

            $default_val_array = array(
                'image' 			=> $image[0],
				'detail_page_url'  => $detail_page_url,
                'upcoming_post_content' => $upcoming_post_content,			
            );    
            $default[] =  $default_val_array; 
            endwhile; 
        else :
            $default_val_array = array(
                'title' => 'No Data Found'
            );    
            $default[] =  $default_val_array; 
        endif;

        $this->add_control(            
			'list_items',
			[
				'label' => esc_html__( 'list_items', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => $default,
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
		?>
<div class="latest-article-wrapper">
    <?php if( !empty($settings['event_title'])) {?>
    <div class="article-main-title-with-border">
        <h2 class="section-title text-black font-bold border-line m-t-0"><?php echo $settings['event_title'];?></h2>
    </div>
    <?php }?>
    <div class="latest-article-slider">
        <div class="article-block-main-outer swiper-container"
            <?php $this->print_render_attribute_string( 'upcoming_event' ); ?>>

            <div class="upcoming-article-block-wrapper">
                <?php
				$date = new DateTime();
				$today = $date->getTimestamp();
				$current_date_time = date('Y-m-d H:i:s', $today);
				
				$args =  array(
					'post_type' => 'tribe_events',
					'posts_per_page' => $settings['number_of_events'],
					'meta_key' => '_EventStartDate',
                    'orderby' => '_EventStartDate', 
					'post_status' => 'publish',
                    'order' => 'ASC',
					'meta_query'    => array(
						'relation'      => 'AND',
						array(
							'key'       => '_EventStartDate',
							'compare'   => '>=',
							'value'     => $current_date_time,
							'type' => 'DATETIME'
						)
					)

				);
				
				$query= new WP_Query($args);
				
				global $post;

				if( $query->have_posts() ):
					while ( $query->have_posts() ) : $query->the_post();?>
                <div class="article-block-inner swiper-slide">
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
						$convert_start_date_with_name =  date("M d, Y", strtotime($start_date_with_remove_time));
						$get_only_start_day_name = date('l', strtotime($start_date_with_remove_time));
						// End Date Event Converter
						$getfull_eventend_date  = get_post_meta( $post->ID, '_EventEndDate', true  );
						$format = 'Y-m-d H:i:s';
						$date_end = DateTime::createFromFormat($format, $getfull_eventend_date);
						$get_end_date_time = $date_end->format('H:i') . "\n";
						$convert_end_date_object = new DateTime($getfull_eventend_date);
						$end_date_with_remove_time = $convert_end_date_object->format('Y-m-d');
						$convert_end_date_with_name = date("d M, Y", strtotime($end_date_with_remove_time));
						$get_only_end_day_name = date('l', strtotime($end_date_with_remove_time));
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
                                    <p class="article-subject sub-title font-bold text-red">
                                <?php								  
								echo $get_only_start_day_name.' / '.$convert_start_date_with_name.' '.$get_start_date_time.' - '.$get_end_date_time.' '.$get_event_timezone.' </br>'.$getevent_venue_city.' '.$getevent_venue_country;
								?> </p>
								<p class="upcoming-post-content description text-grey font-medium">
									<?php echo wp_trim_words( $upcoming_post_content, 45, '...' ); ?></p>
									
									<?php if($event_invitation_type): ?>
										<p class="upcoming-post-content description text-grey font-medium">
											<?php echo $event_invitation_type; ?>
										</p>
									<?php endif; ?>
                                </a>
                                </p>
                            </div>
                            <a class="article-view-more-btn" href="<?php echo $detail_page_url;?>">Learn More</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; 
				else :
					echo "No Data Found.";
				endif;
				if($settings['view_all_upcoming_events'] === 'yes'){
					$view_more_article_url      = get_field('view_all_upcoming_events_url','option');
					$link_url = $view_more_article_url['url'];
					$link_title = $view_more_article_url['title'];
					$link_target = $view_more_article_url['target'] ? $view_more_article_url['target'] : '_self';
			
					echo "<div class='view_all_article_btn_main'><a class='article-view-more-btn ctm-btn view_all_custom_btn' target=$link_target href=$link_url>$link_title</a></div>";
				}
			?>
            </div>

        </div>

    </div>
</div>
<?php
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
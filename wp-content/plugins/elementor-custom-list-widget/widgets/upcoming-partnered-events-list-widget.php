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

class Elementor_Upcoming_Partnered_Events_List_Widget extends \Elementor\Widget_Base {
	
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
		return 'upcoming_partnered_event';
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
		return esc_html__( 'Upcoming Partnered Events', 'elementor-custom-list-widget' );
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
		wp_register_style( 'partnered-events-block-css', PLUGIN_DIR .'assets/css/partnered-events-block.css' );
		
		return [
			'partnered-events-block-css',
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

		wp_register_script( 'partnered-events-block-js', PLUGIN_DIR .'assets/js/partnered-events-block.js' );
		wp_localize_script( 'partnered-events-block-js', 'pe_params', array( 
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce('tby-pe'),
			'action' => 'get_upcoming_partnered_events'
		) );
		return [
			'partnered-events-block-js'			
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
		return ['Upcoming Partnered Events', 'Partnered Events', 'Events' ];
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
				'label' => esc_html__( 'Upcoming Partnered Events', 'elementor-custom-list-widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'event_title',
			[
				'label' => esc_html__( 'Title', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Upcoming Partnered Events', 'elementor-custom-list-widget' ),
				'placeholder' => esc_html__( 'Upcoming Partnered Events', 'elementor-custom-list-widget' ),
			]
		);	
		
        $args =  array(
            'post_type' => 'partnered-event',
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
			$title 			= get_the_title();
			$venue 			= get_post_meta( $post_id, 'pe_venue', true  ); 
			$external_url 	= get_post_meta( $post_id, 'pe_external_url', true );
			$download_url 	= get_post_meta( $post_id, 'pe_download_file', true );

			$default_val_array = array(
                'image' 			=> $image[0],
				'detail_page_url'  	=> $detail_page_url,
                'title' 			=> $title,
                'venue' 			=> $venue,
                'external_url' 		=> $external_url,
                'download_url' 		=> $download_url,
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
		$partnered_events_per_page = 3;
		?>
<div class="partnered-events-wrapper upcoming pad100 p-t-0">
    <?php if( !empty($settings['event_title'])) {?>
    <div class="partnered-event-main-title-with-border">
        <h2 class="section-title text-black font-bold border-line m-t-0"><?php echo $settings['event_title'];?></h2>
    </div>
    <?php }?>
    <div class="partnered-events-outer" <?php $this->print_render_attribute_string( 'upcoming_event' ); ?>>
        <div class="partnered-events-block-wrapper upcoming-partnered-events">
            <input type="hidden" class="partnered_events_per_page" value="<?php echo $partnered_events_per_page; ?>" />
            <?php
						$date = new DateTime();
						$today = $date->getTimestamp();
						$current_date_time = date('Y-m-d H:i:s', $today);
						
						$args =  array(
							'post_type' => 'partnered-event',
							'posts_per_page' => $partnered_events_per_page,
							'meta_key' => 'pe_start_date',
							'orderby' => 'pe_start_date', 
							'post_status' => 'publish',
							'order' => 'ASC',
							'meta_query'    => array(
								'relation'      => 'AND',
								array(
									'key'       => 'pe_start_date',
									'compare'   => '>=',
									'value'     => $current_date_time,
									'type' => 'DATETIME'
								),
							)
						);
						
						$query= new WP_Query($args);
						
						global $post;
						?>
            <div class="partnered-event-block-results">
                <?php
							if( $query->have_posts() ):
								while ( $query->have_posts() ) : $query->the_post();?>
                <div class="partnered-event-block-inner">
                    <?php
						$post_id = $post->ID;
						$image_id = get_post_thumbnail_id();
						$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
						$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
						$detail_page_url  	= get_permalink($post_id);
						$image              = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : PLUGIN_DIR .'assets/images/NoImageAvailable.png';

						$title 			= get_the_title();
						$start_date		= get_field( 'pe_start_date', $post_id ); 
						$start_date_f 	= date("d F Y", strtotime($start_date)); 
						$venue 			= get_field( 'pe_venue', $post_id ); 
						$external_url 	= get_field( 'pe_external_url', $post_id );
						$download_url 	= get_field( 'pe_download_file', $post_id );
					?>

                    <div class="partnered-event-inner-content">
                        <div class="partnered-event-list-thumb">
                            <img src='<?php echo $image;?>' alt="<?php echo $image_alt;?>" />
                        </div>

                        <div class="partnered-event-list-content-sec">
                            <div class="partnered-event-inner-content">
                                <a href="<?php echo $detail_page_url;?>">
                                    <h3 class="partnered-event-main-title sub-title text-black font-bold">
                                        <?php echo $title;?></h3>

                                    <p class="article-subject sub-description font-bold text-red">
                                        <?php
											echo $start_date_f.'</br>'.$venue;
										?>
                                    </p>
                                </a>
                            </div>

                            <div>
                                <a href="<?php echo $detail_page_url;?>" class="partner-list-learn-more">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; 
							else :
								echo "No Data Found.";
							endif;
							?>
            </div>
            <?php
						if ( $query->max_num_pages > 1 ){
							echo '<button class="load_more_partnered_events_btn">See more</button>';
							echo '<input type="hidden" name="partnered_events_paged_track" class="partnered_events_paged_track" value="2">';						
						}
						?>
        </div>

    </div>
</div>
<div class='spinner-wrapper'>
    <div class='nb-spinner'></div>
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
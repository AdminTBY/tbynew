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

class Elementor_Partnered_Events_List_Widget extends \Elementor\Widget_Base {
	
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
		return 'partnered_event';
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
		return esc_html__( 'Partnered Events', 'elementor-custom-list-widget' );
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
	public function get_script_depends() {}

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
		return ['Partnered Events', 'Events' ];
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
				'label' => esc_html__( 'Partnered Events', 'elementor-custom-list-widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'event_title',
			[
				'label' => esc_html__( 'Title', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Partnered Events', 'elementor-custom-list-widget' ),
				'placeholder' => esc_html__( 'Partnered Events', 'elementor-custom-list-widget' ),
			]
		);	
		
        $args =  array(
            'post_type' => 'partnered-event',
            'posts_per_page' => 1,
        );

		$query= new WP_Query($args);
        $default = array();
		global $post;

        if( $query->have_posts() ):
            while ( $query->have_posts() ) : $query->the_post();			
			
			$post_id = $post->ID;
			$detail_page_url  	= get_permalink($post_id);
			$image 				= get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri() .'/assets/images/NoImageAvailable.png';
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
		?>
	<div class="partnered-events-wrapper partnered pad100 pad50">
		<div class="partnered-event-main-title-with-border">
			<h2 class="section-title text-black font-bold border-line m-t-0">
				<?php echo $settings['event_title'];?>
			</h2>
		</div>

    <div class="partnered-events-outer" <?php $this->print_render_attribute_string( 'upcoming_event' ); ?>>
        <div class="partnered-events-block-wrapper latest-partnered-events">
            <?php
			$date = new DateTime();
			$today = $date->getTimestamp();
			// print_r($today);
			$current_date_time = date('Y-m-d H:i:s', $today);
            
            $args =  array(
                'post_type' => 'partnered-event',
                'posts_per_page' => -1,
                'meta_key' => 'pe_end_date',
                'orderby' => 'pe_end_date', 
                'post_status' => 'publish',
                'order' => 'ASC',
                'meta_query'    => array(
                    'relation'      => 'AND',
                    array(
                        'key'       => 'pe_end_date',
                        'compare'   => '>=',
                        'value'     => $current_date_time,
                        'type' => 'DATETIME'
					),
					array(
                        'key'       => 'pe_start_date',
                        'compare'   => '<=',
                        'value'     => $current_date_time,
                        'type' => 'DATETIME'
                    )
                )
            );
			
			// echo "<pre>";
			// print_r($args);
			$query= new WP_Query($args);
			
			global $post;

			if( $query->have_posts() ):
			while ( $query->have_posts() ) : $query->the_post();?>
            	<div class="partnered-event-block-inner">
                <?php
					$post_id = $post->ID;
					$image_id = get_post_thumbnail_id();
					$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
					$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
					$detail_page_url  	= get_permalink($post_id);
					$image               = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : PLUGIN_DIR .'assets/images/NoImageAvailable.png';

					$title 			= get_the_title();									
					
					$start_date		= get_field( 'pe_start_date', $post_id );
					$start_date_only 	= date("d", strtotime($start_date));
					$start_date_month 	= date("F", strtotime($start_date));
					$start_date_year 	= date("Y", strtotime($start_date)); 
					$start_date_f 	= date("d F Y", strtotime($start_date)); 
					
					$end_Date 		= get_field( 'pe_end_date', $post_id );
					// print_r($end_Date);
					$end_date_f 	= date("d F Y", strtotime($end_Date)); 
					$end_date_only 	= date("d", strtotime($end_Date)); 
					
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
                                <h3 class="partnered-event-main-title title text-black font-bold"><?php echo $title;?>
                                </h3>

                                <p class="article-subject sub-title font-bold text-red">
                                    <?php
										echo $start_date_f.'</br>'.$venue;
									?>
                                </p>
                            </a>
                        </div>
                        <div class="event-button">
						<?php if($external_url){                  
                            $link_url    = $external_url['url'];
                            $link_title  = $external_url['title'] ? $external_url['title'] : "Attend this event";
                            $link_target = $external_url['target'] ? $external_url['target'] : '_self';
                        ?>
                        <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr( $link_target ); ?>"
                            class="partnered-event-view-more-btn attend-event">
                            <?php echo esc_html( $link_title ); ?>
                        </a>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="partnered-information">
                <h2 class="title text-black font-bold text-uppercase">Event Description</h2>
                <p class="partnered-content"> <?php the_content();?> </p>
				<?php if(!empty($download_url)){?>
                	<a class="partnered-event-view-more-btn press-release" href="<?php echo $download_url;?>">Press
                    Release</a>
				<?php }?>
            </div>
            <?php endwhile; 
			else :
				echo "Stay tuned for more upcoming events soon.";
			endif;
			?>
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
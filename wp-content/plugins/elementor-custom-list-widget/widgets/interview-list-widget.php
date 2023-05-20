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

class Elementor_Interview_List_Widget extends \Elementor\Widget_Base {
	
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
		return 'featured_interviews';
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
		return esc_html__( 'Featured Interviews', 'elementor-custom-list-widget' );
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
		return 'eicon-bullet-list';
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

		wp_register_style( 'featured-interview-block-css', PLUGIN_DIR .'assets/css/featured-interview-block.css' );
		
		return [
			'featured-interview-block-css'
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

		wp_register_script( 'featured-interview-block-js', PLUGIN_DIR .'assets/js/featured-interview-block.js' );

		return [
			'featured-interview-block-js',
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
		return ['Featured Interviews', 'Interviews' ];
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
				'label' => esc_html__( 'Featured Interviews', 'elementor-custom-list-widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        
		$this->add_control(
			'interview_title',
			[
				'label' => esc_html__( 'Title', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Featured Interviews', 'elementor-custom-list-widget' ),
				'placeholder' => esc_html__( 'Featured Interviews', 'elementor-custom-list-widget' ),
			]
		);
		$this->add_control(
			'show_view_more_interview_button',
			[
				'label' => esc_html__( 'Show View More Interview Button', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementor-custom-list-widget' ),
				'label_off' => esc_html__( 'Hide', 'elementor-custom-list-widget' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

        $args =  array(
            'post_type' => 'interview',
            'posts_per_page' => 5,
			'meta_query'	=> array(				
				array(
					'key'	  	=> 'featured_interview',
					'value'	  	=> '1',
				),
			),
        );
        
		$query= new WP_Query($args);
		global $post;
        $default = array();

        if( $query->have_posts() ):
			$i =0;
            while ( $query->have_posts() ) : $query->the_post();
			$post_id = $post->ID;			
			$interviewiers_bio = get_the_excerpt($post_id);
			$view_more_url	   = get_permalink($post_id);		
			$interview_sector 	= (!empty(get_field('interview_sector',$post_id))) ? get_field('interview_sector',$post_id) : '';
			$interview_type     = (!empty(get_field('interview_article_type',$post_id))) ? get_field('interview_article_type',$post_id) : 'Interview';
                if(strtolower($interview_type) == 'b2b'){
                    $image 		=  ( !empty(get_field('interview_b2b_image','option')['sizes']['medium_large']) ) ? get_field('interview_b2b_image','option')['sizes']['medium_large'] : get_stylesheet_directory_uri().'/assets/images/b2b.png';        
                    $image_alt 	= 'b2b';
					$header_img =  ( !empty(get_field('interview_b2b_header_image','option')['sizes']['medium_large']) ) ? get_field('interview_b2b_header_image','option')['sizes']['medium_large'] : get_stylesheet_directory_uri().'/assets/images/b2b-header.png';	
					$title = get_field('interviewees_fields',$post_id)[0]['interviewee_name'].' and '.get_field('interviewees_fields',$post_id)[1]['interviewee_name'];				
                }elseif (strtolower($interview_type) == 'forum') {
                    $image = ( !empty(get_field('interview_forum_image','option')['sizes']['medium_large']) ) ? get_field('interview_forum_image','option')['sizes']['medium_large'] : get_stylesheet_directory_uri().'/assets/images/forum.png';
                    $image_alt = 'forum';
					$header_img =  ( !empty(get_field('interview_forum_header_image','option')['sizes']['medium_large']) ) ? get_field('interview_forum_header_image','option')['sizes']['medium_large'] : get_stylesheet_directory_uri().'/assets/images/forum-header.png';	
					$title = get_the_title();
                }else{
                    $image_id 			= get_post_thumbnail_id();
                    $image_alt 			= get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
                    $image_alt 			= (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
                    $detail_page_url  	= get_permalink($post_id);
                    $image 				= get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri() .'/assets/images/interview-default.png';
					$no_interview_img_css = get_the_post_thumbnail_url( $post_id,'large' ) ? '' : "style = 'background-color: #EBEBEB;'";
					$title 				= get_the_title();
                }

			$all_countries 		= wp_get_post_terms( $post_id, 'country', array() );
			$child_countries 	= array();
			foreach ($all_countries as $key => $value) {				
				if($all_countries[$key]->parent != 0){
					array_push($child_countries,$all_countries[$key]->name);
				}
			}
			$country = implode(', ', $child_countries).' -';
			$position 			= (!empty(get_field('interviewees_fields',$post_id)[0]['interviewee_position'])) ? get_field('interviewees_fields',$post_id)[0]['interviewee_position'].',' : '';
			$company_name 		= (!empty(get_field('interviewees_fields',$post_id)[0]['interviewee_company'])) ? get_field('interviewees_fields',$post_id)[0]['interviewee_company'] : '';

            $default_val_array = array(
				'post_id'						=> $post_id,
				'image' 						=> $image,
				'header_img'					=> $header_img,
				'image_alt'						=> $image_alt,
				'country_and_sector_details' 	=> $country.' '.$interview_sector,
                'title' 						=> $title,
				'position_and_comp_name' 		=> $position.' '.$company_name,				
				'bio_details'					=> $interviewiers_bio,
				'view_more_url'					=> $view_more_url,	
				'interview_type'				=> $interview_type,
				'no_interview_img_css'			=> $no_interview_img_css,
            );    
            $default[] =  $default_val_array; 
			$i++;
            endwhile; 
        else :
            $default_val_array = array(
                'title' => 'No Data Found'
            );    
            $default[] =  $default_val_array; 
        endif;

        $this->add_control(
            
			'list_interviews_items',
			[
				'label' => esc_html__( 'list_interviews_items', 'elementor-custom-list-widget' ),
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
		
		 if( !empty($settings['interview_title'])) {?>
<div class="interviews-main-title-with-border">
    <h2 class="section-title text-black font-bold border-line m-t-0"><?php echo $settings['interview_title'];?></h2>
</div>

<?php }?>
<div class="interviews-block-main-outer" <?php $this->print_render_attribute_string( 'featured_interviews' ); ?>>
    <?php	
			$i = 0;
			foreach ( $settings['list_interviews_items'] as $index => $item ) {				
				$post_id						= $settings['list_interviews_items'][$index]['post_id']; 							
				$image 							= $settings['list_interviews_items'][$index]['image'] ? $settings['list_interviews_items'][$index]['image'] : PLUGIN_DIR .'assets/images/NoImageAvailable.png';
				$image_alt 						= $settings['list_interviews_items'][$index]['image_alt'];
				$header_img 					= $settings['list_interviews_items'][$index]['header_img'];
				$country_and_sector_details 	= $settings['list_interviews_items'][$index]['country_and_sector_details'];
				$title 							= $settings['list_interviews_items'][$index]['title'];
				$position_and_comp_name 		= $settings['list_interviews_items'][$index]['position_and_comp_name'];
				$bio_details 					= $settings['list_interviews_items'][$index]['bio_details'];
				$view_more_url 					= $settings['list_interviews_items'][$index]['view_more_url'];						
				$interview_type					= $settings['list_interviews_items'][$index]['interview_type'];	
				$no_interview_img_css 			= $settings['list_interviews_items'][$index]['no_interview_img_css'];					
				if($i == 0){?>
    <div class="interviews-block-inner first-interview-sec <?php echo "custom".strtolower($interview_type);?>">
        <?php 
			if(strtolower($interview_type) == 'b2b' || strtolower($interview_type) == 'forum'){?>
        <a class="interviewers-list-thumb <?php echo strtolower($interview_type);?>" href="<?php echo $view_more_url;?>"
            style="background-image: url(<?php echo $header_img;?>) , url(<?php echo $image;?>);">
        </a>
        <?php }else{?>
        <div class="interviewers-list-thumb">
            <a href="<?php echo $view_more_url;?>">
                <img src="<?php echo $image;?>" alt="<?php echo $image_alt;?>" <?php echo $no_interview_img_css;?>>
            </a>
        </div>
        <?php } ?>
        <div class="interviews-list-content-sec list-content-sec-first-sec">
            <div class="interviews-list-inner">
                <p class="interviewers-country-sect-details sub-description text-red font-bold text-uppercase">
                    <?php echo $country_and_sector_details;?>
                </p>
                <a href="<?php echo $view_more_url;?>">
                    <h3 class="interview-main-title title text-black font-bold"><?php echo $title;?></h3>
                </a>
                <p class="interview-type"><?php echo $interview_type;?></p>
                <p class="interviewers-pos-com-name description text-grey font-medium">
                    <?php 
					if(strtolower($interview_type) == 'b2b' || strtolower($interview_type) == 'forum'){
						$countries = tby_display_categories($post_id,'country');
						if(!empty($countries)){                                
							echo $countries;
						}
					}else{
						echo $position_and_comp_name;
					}
					?>
                </p>
                <p class="bio-details description text-grey font-medium"><?php echo $bio_details;?></p>
            </div>
            <a class="interview-view-more-btn" href="<?php echo $view_more_url;?>">View More</a>
        </div>
    </div>
    <?php }else{?>
    <div class="interviews-block-inner <?php echo "custom".strtolower($interview_type);?>">
        <div class="interviews-inner">
            <?php 
			if(strtolower($interview_type) == 'b2b' || strtolower($interview_type) == 'forum'){?>
            <a href="<?php echo $view_more_url;?>">
                <div class="interviewers-list-thumb <?php echo strtolower($interview_type);?>"
                    style="background-image: url(<?php echo $header_img;?>) , url(<?php echo $image;?>);">
                </div>
            </a>
            <?php }else{?>
            <a href="<?php echo $view_more_url;?>">
                <div class="interviewers-list-thumb">
                    <img src="<?php echo $image;?>" alt="<?php echo $image_alt;?>" <?php echo $no_interview_img_css;?>>
                </div>
            </a>
            <?php } ?>
            <div class="interviews-list-content-sec">
                <p class="interviewers-country-sect-details sub-description text-red font-bold text-uppercase">
                    <?php echo $country_and_sector_details;?>
                </p>
                <a href="<?php echo $view_more_url;?>">
                    <h3 class="interview-main-title title text-black font-bold"><?php echo $title;?></h3>
                </a>
                <p class="interview-type"><?php echo $interview_type;?></p>
                <p class="interviewers-pos-com-name sub-description text-grey font-medium">
                    <?php 
						if(strtolower($interview_type) == 'b2b' || strtolower($interview_type) == 'forum'){
							$countries = tby_display_categories($post_id,'country');
							if(!empty($countries)){                                
								echo $countries;
							}
						}else{
							echo $position_and_comp_name;
						}
					?>
                </p>
            </div>
        </div>
    </div>
    <?php }
				
				?>
    <?php
				$i++;
			}
			
	if($settings['show_view_more_interview_button'] === 'yes'){
		$view_more_btn_url      = get_field('view_all_interviews_url','option');
		$link_url = $view_more_btn_url['url'];
		$link_title = $view_more_btn_url['title'];
		$link_target = $view_more_btn_url['target'] ? $view_more_btn_url['target'] : '_self';

		echo "<div class='view_all_article_btn_main'><a class='article-view-more-btn ctm-btn view_all_custom_btn' target=$link_target href=$link_url>$link_title</a></div>";
	}?>
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
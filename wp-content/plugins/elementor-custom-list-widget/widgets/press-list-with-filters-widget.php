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

class Elementor_Press_List_With_Filters_Widget extends \Elementor\Widget_Base {
	
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
		return 'press_list_with_filter';
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
		return esc_html__( 'Press List With Filter', 'elementor-custom-list-widget' );
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
		return 'eicon-gallery-group';
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
		wp_register_style( 'press-list-with-filter-css', PLUGIN_DIR .'assets/css/press-list-with-filter.css' );
		
		return [			
			'press-list-with-filter-css',
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
				
		wp_register_script( 'press-list-with-filter-js', PLUGIN_DIR .'assets/js/press-list-with-filter.js' );

		wp_localize_script( 'press-list-with-filter-js', 'press_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		return [
			'press-list-with-filter-js',			
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
		return ['Press List With Filter', 'Filter','press with filter' ];
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
				'label' => esc_html__( 'Press List With Filter', 'elementor-custom-list-widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);		
		
		$this->add_control(
			'press_filter_title',
			[
				'label' => esc_html__( 'Title', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Press', 'elementor-custom-list-widget' ),
				'placeholder' => esc_html__( 'Press', 'elementor-custom-list-widget' ),
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
		$all_countries_with_press = tby_get_terms_by_post_type(array('country'),array('press'));
		$args = array( 'orderby' => 'slug', 'hide_empty' => 1, 'parent' => 0 ); 
		$categories = get_terms('country', $args ); 
		$arrow_icon = PLUGIN_DIR.'/icons/arrow.png';
		if( !empty($settings['press_filter_title'])) {
			$press_filter_list_title = $settings['press_filter_title'];
			echo "<div class='press-filter-main-wrapper pad100'>";
			echo "<div class='press-filter-main-title'>
				<h2 class='section-title text-black font-bold border-line m-t-0'>$press_filter_list_title</h2>
			</div>";			
		}
		echo "<div class='press-filter-main-outer'><div class='spinner-wrapper'><div class='nb-spinner'></div></div>";
						
		echo "<div class='press-filter-main-left-outer'>";
			echo "<p class='title font-bold text-black filter-main-text'>Filters</p>";			
			// Countries start 
			echo "<ul>";					
			echo "<li class='press-filter-countries-ul active'><div><span class='$parent_dropdown_class sub-title font-medium text-black'>Country</span><img class='$parent_dropdown_class arrow' src='$arrow_icon' alt='' /></div>";
			echo "<ul>";
			echo "<li><span class='description text-black font-medium call_ajax_for_filter_press' value='all_country'>All Countries</span></li>";
			
			if (!empty($categories)) {
				foreach ( $categories as $List_category ) {
					if(tby_chk_country_exist($all_countries_with_press,$List_category->term_id)){
						$all_child_categories = get_terms('country',array(
							'orderby'			  => 'name',
							'order'   			  => 'ASC',
							'child_of'            => $List_category->term_id, //set your category ID
							'hide_empty'          => 1,
							'hide_title_if_empty' => false,					
						));					 
						echo "<li><div><span class='description text-black font-medium call_ajax_for_filter_press' value='$List_category->term_id'>$List_category->name</span> <img class='$parent_dropdown_class arrow' src='$arrow_icon' alt='' /></div>";
						if(!empty($all_child_categories)){
							echo "<ul>";
							foreach ( $all_child_categories as $List_child_category ) {	
								if(tby_chk_country_exist($all_countries_with_press,$List_child_category->term_id)){										 
									echo "<li><span class='call_ajax_for_filter_press' value='$List_child_category->term_id'>$List_child_category->name</span></li>";
								}
							}
							echo "</ul>";												
						}
						echo "</li>";
					
					}
				}	
			}
			echo "</ul>";
			echo "</li>";
			// echo "</ul>";					
			
			// Countries END.. 

			// Published Year Html Start
			echo "<li class='press-filter-year-ul'><div><span class='$parent_dropdown_class sub-title font-medium text-black'>Date</span><img class='$parent_dropdown_class arrow' src='$arrow_icon' alt='' /></div>"; 
			echo "<ul><label class='li_text_class'>From</label>";
			echo "<div class='from_date_outer'><input class='date-input' type='text' id='from_date' name='from_date'></div>";
			echo "<label class='li_text_class'>To</label>";
			echo "<div class='from_date_outer'><input class='date-input' type='text' id='till_date' name='till_date'></div>";
			echo "<div class='date_btn_class'>";
			echo "</div></ul>";	
			echo "</li>";	
			echo "</ul>";	
			echo "<div class='press-filter-actions-btn'>";
			echo "<button class='date_apply_btn'>Apply</button>";
			echo "<button class='date_reset_btn'>Reset</button>";	
			echo "</div>";			
			echo "</div>";			

		// Published Year Html END...	
				
		?>

<!-- press list by filters start  -->
<input type="hidden" name="press_posts_track" class="press_posts_track" value="2">
<div class="right-sec-press-filtered-list">
    <div class="press-filtered-block-inner-wrapper">
        <?php 	
					$default_posts_per_page = get_field('press_per_page_for_filter','option');
					$args = array(
						'post_type' => 'press',
						'posts_per_page' => $default_posts_per_page,
						'paged'=>1,
						'meta_key' => 'award_date',
						'orderby' => 'meta_value',
						'order' => 'DESC',
					);
					$query = new WP_Query($args);
					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) { $query->the_post();
							$post_id            = get_the_ID();
							$image_id           = get_post_thumbnail_id();
							$image_alt          = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
							$image_alt          = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
							$detail_page_url  	= get_permalink($post_id);							
							$image              = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : PLUGIN_DIR .'assets/images/NoImageAvailable.png';
		
							$title 				= get_the_title();
							$press_type 	   	= (!empty(get_field('media_news_type',$post_id))) ? get_field('media_news_type',$post_id) : '';			
						?>
        <div class="press-filtered-block-inner">
			<a href="<?php echo $detail_page_url;?>">
				<div class="press-list-thumb">			
					<img src='<?php echo $image;?>' alt="<?php echo $image_alt;?>" />		
				</div>
			</a>

            <div class="press-list-content-sec">
                <div class="press-inner-content">
                    <p class="press-sector sub-description text-red font-bold text-uppercase">
                        <?php echo $press_type;?></p>
                    <a href="<?php echo $detail_page_url;?>">
                        <h3 class="press-main-title sub-title text-black font-bold"><?php echo $title;?></h3>
                    </a>
                </div>
            </div>
        </div>
        <?php }												
					}else{
						echo "No Posts found....";
					}?>
    </div>
    <?php if ( $query->max_num_pages > 1 ){
		echo "<button class='load_more_press_btn'>See more</button>";
	}?>
</div>
<!-- press list by filters END... -->

<?php echo "</div></div>";				
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
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

class Elementor_Article_List_With_Filters_Widget extends \Elementor\Widget_Base {
	
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
		return 'article_list_with_filter';
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
		return esc_html__( 'Article List With Filter', 'elementor-custom-list-widget' );
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

		wp_register_style( 'article-list-with-filter-css', PLUGIN_DIR .'assets/css/article-list-with-filter.css' );
		
		return [			
			'article-list-with-filter-css'
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
				
		wp_register_script( 'article-list-with-filter-js', PLUGIN_DIR .'assets/js/article-list-with-filter.js' );
		wp_localize_script( 'article-list-with-filter-js', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ),'nonce' => wp_create_nonce('ajax-nonce') ) );
		return [
			'article-list-with-filter-js',			
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
		return ['Article List With Filter', 'Filter','article with filter' ];
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
				'label' => esc_html__( 'Article List With Filter', 'elementor-custom-list-widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);		
		
		$this->add_control(
			'article_filter_title',
			[
				'label' => esc_html__( 'Title', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Articles', 'elementor-custom-list-widget' ),
				'placeholder' => esc_html__( 'Articles', 'elementor-custom-list-widget' ),
			]
		);

        $this->add_control(
            
			'country_listing',
			[
				'label' => esc_html__( 'country_listing', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::HIDDEN
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
		$tax_query = array();
		$country_id = '';
		$query_selected_country = (isset($_GET['country'])) ? $_GET['country'] : '';
		$selected_country_class = '';
		if (!empty( $query_selected_country ) ) {	
			$term = get_term_by('slug', $query_selected_country, 'country'); 
		    $country_id = $term->term_id;
				
			$tax_query[] = array(
				'taxonomy' => 'country', //double check your taxonomy name 
				'field'    => 'id',
				'terms'    => $country_id,
				'include_children' => true,
				'operator' => 'IN'
			);
			$selected_country_class = 'selected_country_for_article';	
		}
		//list of categories args
		$parent_dropdown_class = 'dropdown-icon';
		$all_countries_with_articles = tby_get_terms_by_post_type(array('country'),array('post'));
		$args = array( 'orderby' => 'slug', 'hide_empty' => 1, 'parent' => 0 ); 
		$categories = get_terms('country', $args ); 
		$arrow_icon = PLUGIN_DIR.'/icons/arrow.png';
		if( !empty($settings['article_filter_title'])) {
			$article_filter_list_title = $settings['article_filter_title'];
			echo "<div class='article-filter-main-title' id='articles_filter'>
				<h2 class='section-title text-black font-bold border-line m-t-0'>$article_filter_list_title</h2>
			</div>";			
		}
		echo "<div class='article-filter-main-outer article_main_for_ajax'><div class='spinner-wrapper'><div class='nb-spinner'></div></div>";				
		echo "<div class='article-filter-main-left-outer'>";
			echo "<p class='title font-bold text-black filter-main-text'>Filters</p>";			
			// Countries start 
			echo "<ul>";					
			echo "<li class='article-filter-countries-ul active'><div><span class='$parent_dropdown_class sub-title font-medium text-black'>Country</span><img class='$parent_dropdown_class arrow' src='$arrow_icon' alt='' /></div>";
			echo "<ul>";
			echo "<li><span class='description text-black font-medium call_ajax_for_filter' value='all_country'>All Countries</span></li>";
			
			if (!empty($categories)) {
				foreach ( $categories as $List_category ) {
					if(tby_chk_country_exist($all_countries_with_articles,$List_category->term_id)){
						$all_child_categories = get_terms('country',array(
							'orderby'			  => 'name',
							'order'   			  => 'ASC',
							'child_of'            => $List_category->term_id, //set your category ID
							'hide_empty'          => 1,
							'hide_title_if_empty' => false,					
						));					 
						
						if(!empty($all_child_categories)){
							if($List_category->term_id == $country_id){
								echo "<li><div><span class='description text-black font-medium call_ajax_for_filter ".$selected_country_class."' value='$List_category->term_id'>$List_category->name</span> <img class='$parent_dropdown_class arrow' src='$arrow_icon' alt='' /></div>";
							}else{
								echo "<li><div><span class='description text-black font-medium call_ajax_for_filter' value='$List_category->term_id'>$List_category->name</span> <img class='$parent_dropdown_class arrow' src='$arrow_icon' alt='' /></div>";
							}
							
							echo "<ul>";
							foreach ( $all_child_categories as $List_child_category ) {		
								if(tby_chk_country_exist($all_countries_with_articles,$List_child_category->term_id)){
									if($List_child_category->term_id == $country_id){
										echo "<li><span class='call_ajax_for_filter ".$selected_country_class."' value='$List_child_category->term_id'>$List_child_category->name</span></li>";
									}else{
										echo "<li><span class='call_ajax_for_filter' value='$List_child_category->term_id'>$List_child_category->name</span></li>";
									}
								}
							}
							echo "</ul>";												
						}else{
							if($List_category->term_id == $country_id){
								echo "<li><div><span class='description text-black font-medium call_ajax_for_filter ".$selected_country_class."' value='$List_category->term_id'>$List_category->name</span></div>";
							}else{
								echo "<li><div><span class='description text-black font-medium call_ajax_for_filter' value='$List_category->term_id'>$List_category->name</span></div>";
							}
						}
						echo "</li>";	
					}		
				}	
			}
			echo "</ul>";
			echo "</li>";
			// echo "</ul>";					
			
			// Countries END.. 

			// Sector Html Start
				echo "<li class='article-filter-sector-ul'><div><span class='$parent_dropdown_class sub-title font-medium text-black'>Sector</span><img class='$parent_dropdown_class arrow' src='$arrow_icon' alt='' /></div>";
				echo "<ul>";
				echo "<li><span class='description text-black font-medium call_ajax_for_filter' value='all_sector'>All Sectors</span></li>";
				$sectors = (!empty(get_field_object('field_629f2415910d5'))) ? (get_field_object('field_629f2415910d5')) : '';
				
				if( !empty($sectors['choices']) ){
					foreach ($sectors['choices'] as $value) {
						echo "<li><span class='call_ajax_for_filter' value='$value'>$value</span></li>";
					}			
				}
				echo "</ul>";
				echo "</li>";		
			// Sector Html END..

			// Published Year Html Start
			echo "<li class='article-filter-year-ul'><div><span class='$parent_dropdown_class sub-title font-medium text-black'>Year</span><img class='$parent_dropdown_class arrow' src='$arrow_icon' alt='' /></div>"; 	
			echo "<ul>";	
			echo "<li><span class='description text-black font-medium call_ajax_for_filter' value='all_year'>All Year</span></li>"; 	
				$published_year = get_posts_years_array('post');			
				if ( !empty( $published_year ) ) {
					foreach ($published_year as $value) {
						echo "<li><span class='call_ajax_for_filter' value='$value'>$value</span></li>";
					}
				}
				echo "</ul>";
			echo "</li>";	
			echo "</ul>";					
		echo "</div>";									
		// Published Year Html END...		
		?>

<!-- articles list by filters start  -->
<input type="hidden" name="posts_paged_track" class="posts_paged_track" value="2">
<div class="right-sec-article-filtered-list">
    <div class="article-filtered-block-inner-wrapper article-page">
        <?php 		
					global $post;
					$default_posts_per_page = get_field('articles_per_page_for_filter','option');
					$args = array(
						'post_type' => 'post',
						'posts_per_page' => (int)$default_posts_per_page,
						'paged'=>1,
						'tax_query' => $tax_query,
					);
					$query = new WP_Query($args);
					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) { $query->the_post();
							$post_id = get_the_ID();
							$image_id = get_post_thumbnail_id();
							$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
							$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
							$detail_page_url  	= get_permalink($post_id);
							$image              = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : PLUGIN_DIR .'assets/images/article-default.jpg';
							$article_sector 	= (!empty(get_field('article_sector',$post_id))) ? get_field('article_sector',$post_id) : '';
							$title 				= get_the_title();
							$article_subject 	= (!empty(get_field('article_subject',$post_id))) ? get_field('article_subject',$post_id) : '';
							$article_type       = 'Article';
							$author_id			= $post->post_author;
							$author_name 		= get_the_author_meta( 'display_name' , $author_id );
						?>
        <div class="article-filtered-block-inner">
            <a href="<?php echo $detail_page_url;?>">
                <div class="article-list-thumb">
                    <img src='<?php echo $image;?>' alt="<?php echo $image_alt;?>" />
                </div>
            </a>

            <div class="article-list-content-sec">
                <div class="article-inner-content">
                    <p class="article-sector sub-description text-red font-bold text-uppercase">
                        <?php echo $article_sector;?></p>
                    <a href="<?php echo $detail_page_url;?>">
                        <h3 class="article-main-title title text-black font-bold"><?php echo $title;?></h3>
                    </a>
                    <p class="article-subject sub-title text-grey font-medium">
                        <?php echo $article_subject;?>
                    </p>
                    <p class="article-author description text-grey font-medium font-italic m-b-0">
                        <a
                            href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo $author_name;?></a>
                    </p>
                </div>
            </div>
        </div>
        <?php }												
					}else{
						echo "No Posts found....";
					}?>
    </div>
    <?php if ( $query->max_num_pages > 1 ){
					echo "<button class='load_more_articles_btn'>See more</button>";						
				}?>
</div>
<!-- articles list by filters END... -->

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
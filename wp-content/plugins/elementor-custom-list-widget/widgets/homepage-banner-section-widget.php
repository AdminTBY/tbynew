<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor homepage_article_banner Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Elementor_Homepage_Banner_Section_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve homepage_article_banner widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'homepage_article_banner';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve homepage_article_banner widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Homepage Article Banner', 'elementor-custom-homepage_article_banner-widget' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve homepage_article_banner widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-banner';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the homepage_article_banner of categories the homepage_article_banner widget belongs to.
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

		wp_register_style( 'homepage-banner-section-widget-css', PLUGIN_DIR .'assets/css/homepage-banner-section-widget.css' );
		
		return [
			'homepage-banner-section-widget-css'
		];

	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the homepage_article_banner of keywords the homepage_article_banner widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'homepage_article_banner', 'banner', 'homepage'];
	}

	/**
	 * Register homepage_article_banner widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'homepage_banner_section',
			[
				'label' => esc_html__( 'Homepage Article', 'elementor-custom-homepage_article_banner-widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->end_controls_section();

	}

	/**
	 * Render homepage_article_banner widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$args =  array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'orderby' => 'publish_date',
            'posts_per_page' => 1,
            'order' => 'DESC',
			'meta_query'	=> array(			
				array(
					'key'	  	=> 'show_on_homepage_banner',
					'value'	  	=> '1',
				),
			),
        );

		$query= new WP_Query($args);
        $default = array();
		global $post;

        if( $query->have_posts() ):
            while ( $query->have_posts() ) : $query->the_post();			
			
			$post_id = $post->ID;
			$detail_page_url  	= get_permalink($post_id);
            $title              = get_the_title();
			$image              = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' )[0] ? wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' )[0] : PLUGIN_DIR .'assets/images/NoImageAvailable.png';
			$article_sector 	= (!empty(get_field('article_sector',$post_id))) ? get_field('article_sector',$post_id) : '';
			$article_subject 	= (!empty(get_field('article_subject',$post_id))) ? get_field('article_subject',$post_id) : '';
			$article_type       = 'Article';
			$author_id			= $post->post_author;
            $author_url         = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
			$author_name 		= get_the_author_meta( 'display_name' , $author_id );                
            
            echo "<section class='homepage-banner-section' style='background: url($image) no-repeat'>
                    <div class='container'>
                        <div class='homepage-banner-detail-section'>                        
                            <div class='article-list-content-sec'>
                                <div class='article-inner-content'>
                                    <p class='article-sector description text-red font-bold text-uppercase'>$article_sector</p>
                                    <a href=$detail_page_url>
                                        <h3 class='article-main-title section-title text-black font-bold'>$title</h3>
                                    </a>
                                    <p class='article-subject sub-title text-grey font-medium'>$article_subject</p>
                                    <p class='article-author description text-grey font-medium'>
                                    <a class='description text-grey font-medium' href=$author_url>$author_name</a>
                                    </p>
                                </div>
                                <a class='article-view-more-btn' href=$detail_page_url>View More</a>
                            </div>
                        </div>
                    </div>
            </section>";
            endwhile;             
        else :
            echo "<h2>No Article Found...</h2>";     
        endif;
	}

	/**
	 * Render homepage_article_banner widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
		
	}

}
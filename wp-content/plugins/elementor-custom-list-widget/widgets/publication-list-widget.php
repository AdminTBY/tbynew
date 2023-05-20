<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Publication List Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */

class Elementor_Publication_List_Widget extends \Elementor\Widget_Base {
	
	/**
	 * Get widget name.
	 *
	 * Retrieve Publication list widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'publication_list';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Publication list widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Publication List', 'elementor-custom-list-widget' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Publication list widget icon.
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
	 * Retrieve the list of categories the Publication list widget belongs to.
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

		wp_register_style( 'publication-block-css', PLUGIN_DIR .'assets/css/publication-block.css' );
		
		return [			
			'publication-block-css'
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
				
		wp_register_script( 'publication-block-js', PLUGIN_DIR .'assets/js/publication-block.js' );
		return [
			'publication-block-js',			
		];

	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the Publication list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return ['Latest Publication', 'Publication' ];
	}

	/**
	 * Register Publication list widget controls.
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
				'label' => esc_html__( 'Publication List', 'elementor-custom-list-widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
				
		$this->add_control(
			'publication_title',
			[
				'label' => esc_html__( 'Title', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Browse Our Publications', 'elementor-custom-list-widget' ),
				'placeholder' => esc_html__( 'Browse Our Publications', 'elementor-custom-list-widget' ),
			]
		);

        

        

        $this->add_control(
			'no_of_posts',
			[
				'label' => esc_html__( 'No of Products', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 2,
				'max' => 15,
				'step' => 1,
				'default' => 8,
			]
		);

		/* End repeater */

		$this->end_controls_section();

	}

	/**
	 * Render Publication list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		if( !empty($settings['publication_title'])) {?>
<div class="publication-wrapper">
    <div class="publication-custom-block-main-title">
        <h2 class="section-title text-black font-bold border-line m-t-0"><?php echo $settings['publication_title'];?></h2>
    </div>
    <?php }?>

    <div class="publication-block-main-outer" <?php $this->print_render_attribute_string( 'publication_list' ); ?>>
       	<?php
       		$posts_count = $settings['no_of_posts'];
            $args =  array(
	            'post_type' => 'product',
	            'posts_per_page' => $posts_count,
				'post_status' => 'publish',
	            'orderby' => 'publish_date',
				'order' => 'DESC',
				'tax_query'   => array( array(
					'taxonomy'  => 'product_visibility',
					'terms'     => array( 'exclude-from-catalog' ),
					'field'     => 'name',
					'operator'  => 'NOT IN',
				) )            
	        );

			$query= new WP_Query($args);
	        $default = array();
			global $post;

	        if( $query->have_posts() ):
	            while ( $query->have_posts() ) : $query->the_post();			
				
					$post_id = get_the_ID();
		            $image_id = get_post_thumbnail_id();
					$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
					$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
					$product_page_url  	= get_permalink($post_id);
					$image = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : PLUGIN_DIR .'assets/images/NoImageAvailable.png';
					$title = get_the_title();?>
		            <div class="publication-block-inner">
					<a href="<?php echo $product_page_url;?>">
			            <div class="publication-list-thumb">
			                <img src="<?php echo $image;?>" alt="<?php echo $image_alt;?>">
			            </div>
					</a>
			            <div class="publication-title-outer">
			                <a href="<?php echo $product_page_url;?>">
			                    <h3 class="publication-title description text-black font-medium text-center"><?php echo $title;?></h3>
			                </a>
			            </div>
			        </div>
		        <?php endwhile; 
	        else :
	            echo 'No Data Found';  
	        endif; 
        ?>
    </div>
</div>
<?php
	}

	/**
	 * Render Publication list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}

}
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

class Elementor_Youtube_Video_List_Widget extends \Elementor\Widget_Base {
	
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
		return 'latest_youtube_videos';
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
		return esc_html__( 'Latest Youtube Videos', 'elementor-custom-list-widget' );
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
		return 'eicon-youtube';
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

		wp_register_style( 'latest-youtube-videos-css', PLUGIN_DIR .'assets/css/latest-youtube-videos.css' );
		
		return [			
			'latest-youtube-videos-css'
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
				
		wp_register_script( 'latest-youtube-videos-js', PLUGIN_DIR .'assets/js/latest-youtube-videos.js' );
		return [
			'latest-youtube-videos-js',			
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
		return ['Latest Youtube Videos', 'Youtube','youtube','videos' ];
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
				'label' => esc_html__( 'Latest Youtube Videos', 'elementor-custom-list-widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);		
		
		$this->add_control(
			'youtube_video_title',
			[
				'label' => esc_html__( 'Title', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Videos', 'elementor-custom-list-widget' ),
				'placeholder' => esc_html__( 'Videos', 'elementor-custom-list-widget' ),
			]
		);

		$this->add_control(
			'view_all_videos_button',
			[
				'label' => esc_html__( 'View All Videos Button', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementor-custom-list-widget' ),
				'label_off' => esc_html__( 'Hide', 'elementor-custom-list-widget' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'youtube_video_layouts',
			[
				'label' => esc_html__( 'Number Of Articles', 'elementor-custom-list-widget' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'homepage-layout',
				'options' => [
					'homepage-layout'  => esc_html__( 'Homepage Layout', 'elementor-custom-list-widget' ),
					'video-page-layout'  => esc_html__( 'Video Page Layout', 'elementor-custom-list-widget' ),
				],
			]
		);

		// Youtube video section start
		$videos_data = array();
		// API config 

		$API_Key    = trim(get_field('youtube_api_key','options')); 
		$Channel_ID = trim(get_field('youtube_channel_id','options')); 
		$Max_Results = 3; 
		$arrContextOptions=array(
			"ssl"=>array(
				  "verify_peer"=>false,
				  "verify_peer_name"=>false,
			  ),
		  );  
		// Get videos from channel by YouTube Data API 
		$apiData = @file_get_contents('https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId='.$Channel_ID.'&maxResults='.$Max_Results.'&key='.$API_Key.'', false, stream_context_create($arrContextOptions)); 
		
		if($apiData){ 
			$videoList = json_decode($apiData); 
		}

		if(!empty($videoList->items)){ 
			foreach($videoList->items as $item){ 
				// Embed video 
				if(isset($item->id->videoId)){ 					
					$video_data = array(
						'data_img_url' => $item->snippet->thumbnails->default->url,
						'title'        => $item->snippet->title,
						'video_url' => 'https://www.youtube.com/embed/'.$item->id->videoId,   
					);
				}
				$videos_data[] = $video_data;
			} 
		}

		if(empty($videos_data)){
			if (have_rows('youtube_video_data', 'option')):
				$project_description_with_image_counter = 1;
				$speaker_id = 1;
				while (have_rows('youtube_video_data', 'option')):
					the_row();

					$data_img_url = get_sub_field('data_img_url');
					$title        = get_sub_field('title');
					$video_url    = get_sub_field('video_url');
					$project_description_with_image_counter++;
					$speaker_id++;
					$videos_data[] = array(   
						'data_img_url' => $data_img_url['url'],                                 
						'title'        => $title,
						'video_url' => $video_url,   
					);
				endwhile;
			else:
				echo "No Speakers Found.";
			endif; 
		}
		
		// Youtube video section END...
		
        foreach ($videos_data as $key => $video) {

            $default_val_array = array(
                'data_img_url' 	=> $video['data_img_url'],
                'title'         => $video['title'],
                'video_url'     => $video['video_url'],
                'vector_icon'   => PLUGIN_DIR.'/icons/Vector.png' 
            );    
            $default[] =  $default_val_array;    
        }        
        $this->add_control(
            
			'latest_youtube_videos_data',
			[
				'label' => esc_html__( 'latest_youtube_videos_data', 'elementor-custom-list-widget' ),
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
<div class="youtube-videos-wrapper">
    <div class="youtube-videos-block-main-outer"
        <?php $this->print_render_attribute_string( 'latest_youtube_videos' ); ?>>

        <?php if( !empty($settings['youtube_video_title'])) {?>
        <div class="youtube-videos-block-main-title">
            <h2 class="section-title text-black font-bold border-line m-t-0">
                <?php echo $settings['youtube_video_title'];?>
            </h2>
        </div>
        <?php }
		$i = 0;
		if(!empty($settings['latest_youtube_videos_data']) ){	
			if($settings['youtube_video_layouts'] == 'homepage-layout')	{
		?>
		
        <div class="youtube-videos-block-inner">
            <div class="youtube-videos-left-sec">
                <iframe class="youtube-video-iframe" width="544" height="423"
                    src="<?php echo $settings['latest_youtube_videos_data'][0]['video_url'];?>"
                    title="<?php echo $settings['latest_youtube_videos_data'][0]['title'];?>" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
            <div class="youtube-right-sec-outer-main">
                <?php 				
                foreach ( array_slice($settings['latest_youtube_videos_data'], 0, 3) as $index => $item ) {                    
                    $data_img_url   = $settings['latest_youtube_videos_data'][$index]['data_img_url'];
                    $title 			= $settings['latest_youtube_videos_data'][$index]['title'];
                    $video_url      = $settings['latest_youtube_videos_data'][$index]['video_url'];
                    $vector_icon    = $settings['latest_youtube_videos_data'][$index]['vector_icon'];
                    
                    if ($i == 0) {
                        $show_class   = 'show_element';
                        $hide_class   = 'hide_element';
                        $img_border   = 'img_border';
                        $active_text   = 'active-text';
                    }else{
                        $img_border   = '';
                        $show_class = 'hide_element';
                        $hide_class   = 'show_element';
						$active_text   = '';
                    }
                    ?>
                <div class="youtube-videos-right-sec">
                    <div class="youtube-video-img">
                        <img class="<?php echo $img_border;?>" src="<?php echo $data_img_url;?>"
                            alt="<?php echo $title;?>">
                    </div>
                    <div class="youtube-play-now-sec">
                        <p class="for-playing-now-msg tag font-bold text-red text-uppercase <?php echo $show_class;?>">
                            Now Playing
                        </p>
                        <p
                            class="<?php echo $active_text;?> youtube-video-title sub-description text-black font-medium">
                            <?php echo $title; ?>
                        </p>
                        <a href="javascript:void(0);" data-video-url="<?php echo $video_url;?>"
                            data-title="<?php echo $title;?>"
                            class="<?php echo $hide_class;?> text-red tag text-uppercase font-bold play-now-btn">
                            <img src="<?php echo $vector_icon;?>" alt="youtube-play-icon"> PLAY NOW
                        </a>
                    </div>					
                </div>
                <?php $i++;}
				
				}else{ ?>
                <div class="youtube-videos-page-layout-block-inner">
                    <?php
						// Youtube video section start
						$videos_data = array(); 
						$Max_Results = 4;
						$API_Key    = trim(get_field('youtube_api_key','options')); 
						$Channel_ID = trim(get_field('youtube_channel_id','options')); 
						$arrContextOptions=array(
							"ssl"=>array(
								  "verify_peer"=>false,
								  "verify_peer_name"=>false,
							  ),
						  );
						// Get videos from channel by YouTube Data API 
						$apiData = @file_get_contents('https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId='.$Channel_ID.'&maxResults='.$Max_Results.'&key='.$API_Key.'', false, stream_context_create($arrContextOptions)); 
						
						if($apiData){ 
							$videoList = json_decode($apiData); 
						}
						$i = 0;
						
						if(!empty($videoList->items)){ 
							foreach($videoList->items as $item){ 
								// Embed video 
								if(isset($item->id->videoId)){									
									$youtube_all_data = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=snippet&id={$item->id->videoId}&key=$API_Key", false, stream_context_create($arrContextOptions)); //for getting full description.
									$youtube_all_data = json_decode($youtube_all_data, true);
									$description = ($i == 0) ? $youtube_all_data['items'][0]['snippet']['description'] : $item->snippet->description;	
									$video_data = array(
										'data_img_url' 		=> $youtube_all_data['items'][0]['snippet']['thumbnails']['default']['url'],
										'title'        		=> $youtube_all_data['items'][0]['snippet']['title'],
										'description'  		=> $description, 
										'video_url' 		=> 'https://www.youtube.com/embed/'.$item->id->videoId,
										'published_date' 	=> date('M Y', strtotime($youtube_all_data['items'][0]['snippet']['publishedAt'])),   
									);
									
								}
								$i++;
								$videos_data[] = $video_data;
							} 
						}

						if(empty($videos_data)){
			if (have_rows('youtube_video_data', 'option')):
				$project_description_with_image_counter = 1;
				$speaker_id = 1;
				while (have_rows('youtube_video_data', 'option')):
					the_row();

					$data_img_url 	= get_sub_field('data_img_url');
					$title        	= get_sub_field('title');
					$video_url    	= get_sub_field('video_url');
					$description  	= get_sub_field('description');
					$published_date = get_sub_field('published_date');
					
					$project_description_with_image_counter++;
					$speaker_id++;
					$videos_data[] = array(   
						'data_img_url' 		=> $data_img_url['url'],                                 
						'title'        		=> $title,
						'description'  		=> $description,
						'video_url'		 	=> $video_url,   
						'published_date'	=> $published_date,
					);
				endwhile;
			else:
				echo "No Speakers Found.";
			endif; 
		}

						
						// if(empty($videos_data)){
						// 	if (have_rows('youtube_video_data', 'option')):
						// 		$project_description_with_image_counter = 1;
						// 		$speaker_id = 1;
						// 		while (have_rows('youtube_video_data', 'option')):
						// 			the_row();
		
						// 			$data_img_url = get_sub_field('data_img_url');
						// 			$title        = get_sub_field('title');
						// 			$video_url    = get_sub_field('video_url');
						// 			$project_description_with_image_counter++;
						// 			$speaker_id++;
						// 			$videos_data[] = array(   
						// 				'data_img_url' => $data_img_url['url'],                                 
						// 				'title'        => $title,
						// 				'video_url' => $video_url,   
						// 			);
						// 		endwhile;
						// 	else:
						// 		echo "No Speakers Found.";
						// 	endif; 
						// }
						

						$is_first_video = 0;
						foreach ($videos_data as $key => $value) {
							if($is_first_video == 0){?>
                    <div class="youtube-videos-page-layout-block-first-sec">
                        <iframe class="youtube-video-iframe" width="454" height="333"
                            src="<?php echo $videos_data[$key]['video_url'];?>"
                            title="<?php echo $videos_data[$key]['title'];?>" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                        <h2 class="sub-section-title text-black font-bold"><?php echo $videos_data[$key]['title'];?>
                        </h2>
                        <p class="description text-grey font-medium"><?php echo $videos_data[$key]['published_date'];?>
                        </p>
                        <p class="description text-black font-regular"><?php echo $videos_data[$key]['description'];?>
                        </p>
                    </div>
                    <?php }else{
									if($is_first_video == 1){?>
                    <div class="youtube-videos-page-layout-block-second-sec padd100 p-b-0">
                        <div class="youtube-videos-block-main-title">
                            <h2 class="section-title text-black font-bold border-line m-t-0">Latest Films </h2>
                        </div>
                        <div class="repeated-youtube-video-sec">
                            <?php }?>
                            <div class="youtube-video-page-layout-block-inner">
                                <div class="artcle-inner-content">
                                    <div class="article-list-thumb">
                                        <iframe class="youtube-video-iframe" width="434" height="313"
                                            src="<?php echo $videos_data[$key]['video_url'];?>"
                                            title="<?php echo $videos_data[$key]['title'];?>" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                    </div>

                                    <div class="article-list-content-sec">
                                        <div class="article-inner-content">
                                            <h3 class="article-main-title title text-black font-bold">
                                                <?php echo $videos_data[$key]['title'];?></h3>
                                            <p class="article-subject sub-description text-grey font-medium">
                                                <?php echo wp_trim_words($videos_data[$key]['description'],25);?></p>
                                            <p class="article-author sub-description text-grey font-medium m-b-0">
                                                <?php echo $videos_data[$key]['published_date'];?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php								
							 }
							 $is_first_video++;	
						}?>
                        </div>
						<?php
						if($settings['view_all_videos_button'] === 'yes'){
							$view_more_article_url      = get_field('video_view_all_films_url','option');
							$link_url = $view_more_article_url['url'];
							$link_title = $view_more_article_url['title'];
							$link_target = $view_more_article_url['target'] ? $view_more_article_url['target'] : '_self';

							echo "<div class='view_all_article_btn_main'><a class='article-view-more-btn ctm-btn view_all_custom_btn' target=$link_target href=$link_url>$link_title</a></div>";
						}?>
                    </div>

                </div>
                <?php }//template layout checking conditions					
				}else{?>
                <h2>Invalid API key or channel ID.</h2>
                <?php }				
			?>
            </div>
        </div>
    </div>
</div>
<?php
if($settings['view_all_videos_button'] === 'yes' && $settings['youtube_video_layouts'] === 'homepage-layout'){
	$view_more_article_url      = get_field('video_view_all_videos_url','option');
	$link_url = $view_more_article_url['url'];
	$link_title = $view_more_article_url['title'];
	$link_target = $view_more_article_url['target'] ? $view_more_article_url['target'] : '_self';

	echo "<div class='view_all_article_btn_main'><a class='article-view-more-btn ctm-btn view_all_custom_btn' target=$link_target href=$link_url>$link_title</a></div>";
}
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

<?php 

add_shortcode( 'ads_section_layout', 'tby_ads_script_layout' );
function tby_ads_script_layout( $atts ) {
    ob_start();
    
    if(strtolower($atts['layout']) == 'vertical'){?>
    
            <div class='right-side-sec ads-vertical-sec vertical-mobile'>
                <p class='text-grey tag text-uppercase ads-side-title'>ADVERTISEMENT</p>
                <ins data-revive-zoneid="22" data-revive-id="165ab456d789196b9dc14a3b13f9de8e"></ins>
            </div>

            <div class='right-side-sec ads-vertical-sec vertical-desktop'>
                <p class='text-grey tag text-uppercase ads-side-title'>ADVERTISEMENT</p>
                <ins data-revive-zoneid="20" data-revive-id="165ab456d789196b9dc14a3b13f9de8e"></ins>
            </div>
        <?php 
        
    }else{?>
            
            <div class='banner-section ads-horizontal-sec horizontal-mobile'>
                <ins data-revive-zoneid="22" data-revive-id="165ab456d789196b9dc14a3b13f9de8e"></ins>
            </div>
        
            <div class='banner-section ads-horizontal-sec horizontal-desktop'>
                <ins data-revive-zoneid="1" data-revive-id="165ab456d789196b9dc14a3b13f9de8e"></ins>
            </div>

        <?php 
    }


    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

add_shortcode( 'trending_video_sidebar', 'tby_trending_video_sidebar' );
function tby_trending_video_sidebar( $atts ) {
        $number_of_videos = (!empty ($atts['number_of_videos']))? $atts['number_of_videos'] : 4;
        $youtube_video_section = "<div class='right-side-sec trending_video_sidebar-sec'>
        <div class='youtube-video-sidebar-sec'>
            <div class='youtube-sidebar-main-title-with-border'>
                <h2 class='section-title text-black font-bold border-line m-t-0'>Trending Videos</h2>
            </div>
            <div class='article-detail-listing-youtube-wrapper'>";            
            $videos_data = array();
            // API config 

            $API_Key    = trim(get_field('youtube_api_key','options')); 
            $Channel_ID = trim(get_field('youtube_channel_id','options')); 
            $Max_Results = $number_of_videos; 
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
                            'title'        => $title,
                            'video_url' => $video_url,   
                        );
                    endwhile;
                else:
                    echo "No Speakers Found.";
                endif; 
            }
            
                foreach (array_slice($videos_data, 0, 3) as $key => $video) {
                    $title = $videos_data[$key]['title'];
                    $video_url = $videos_data[$key]['video_url'];
                
                $youtube_video_section.= "<div class='article-detail-listing-youtube'>
                    <iframe class='youtube-video-iframe' width='300' height='300'
                        src='$video_url' title='$title' frameborder='0'
                        allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture'
                        allowfullscreen></iframe>
                    <h6 class='description text-black font-medium'>$title</h6>
                </div>";

                 }   
            $youtube_video_section.="</div>
        </div></div>";
    return $youtube_video_section;
}
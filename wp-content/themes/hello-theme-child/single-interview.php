<?php
    get_header();
    global $post;
    $post_id = $post->ID;
    $interviewer_thumbnail = get_the_post_thumbnail_url( $post_id,'full' ) ? get_the_post_thumbnail_url( $post_id,'full' ) : get_stylesheet_directory_uri() .'/assets/images/interview-default.png';
    $no_interview_img_css = get_the_post_thumbnail_url( $post_id,'full' ) ? '' : "style = 'background-color: #EBEBEB;'";
    $image_id = get_post_thumbnail_id();
    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
    $image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
    $interview_sector 	= (!empty(get_field('interview_sector',$post_id))) ? get_field('interview_sector',$post_id) : '';
    $article_type       = (!empty(get_field('interview_article_type',$post_id))) ? get_field('interview_article_type',$post_id) : 'Interview';
    $all_countries 		= wp_get_post_terms( $post_id, 'country', array() );
    $child_countries 	= array();
    foreach ($all_countries as $key => $value) {				
        if($all_countries[$key]->parent != 0){
            array_push($child_countries,$all_countries[$key]->name);
        }
    }
    $country = strtoupper(implode(', ', $child_countries));
    $country_and_sector_details = $country.' - '.$interview_sector;
    $position 			    = (!empty(get_field('interviewees_fields',$post_id)[0]['interviewee_position'])) ? get_field('interviewees_fields',$post_id)[0]['interviewee_position'].',' : '';
    $company_name 		    = (!empty(get_field('interviewees_fields',$post_id)[0]['interviewee_company'])) ? get_field('interviewees_fields',$post_id)[0]['interviewee_company'] : '';
    $bio_details            = (!empty(get_field('interviewees_fields',$post_id)[0]['interviewee_biography'])) ? get_field('interviewees_fields',$post_id)[0]['interviewee_biography'] : '';  
    $date_and_country       = get_the_date( 'M. d, Y' ); 
    ?>
<div class="single-interview-main-outer <?php echo $article_type;?>">
    <div class="single-interview-container">
        <div class="single-interview-wrapper">

            <?php if(strtolower($article_type) == 'b2b'){
                // Check rows exists.                
                if( have_rows('interviewees_fields') ):
                    echo "<div class='b2b-section-main-outer b2b-card'>";
                    // Loop through rows.
                    while( have_rows('interviewees_fields') ) : the_row();

                        // Load sub field value.
                        $b2b_interviewer_image = (!empty(get_sub_field('interviewee_image')['sizes']['medium'])) ? get_sub_field('interviewee_image')['sizes']['medium'] : get_stylesheet_directory_uri().'/assets/images/b2b.png';
                        $b2b_interviewer_img_alt = !empty ($b2b_interviewer_image) ? get_sub_field('interviewee_image')['alt'] : '';
                        $b2b_interviewer_name  = get_sub_field('interviewee_name');
                        $b2b_company_name      = get_sub_field('interviewee_company');
                        $b2b_position          = get_sub_field('interviewee_position');
                        // Do something...
                        ?>
            <div class="b2b-items">
                <img src="<?php echo $b2b_interviewer_image;?>" alt="<?php echo $b2b_interviewer_img_alt?>">
                <div class="b2b-interviewer-details">
                    <h2><?php echo $b2b_interviewer_name;?></h2>
                    <p class="description text-grey font-medium">
                        <?php echo (!empty($b2b_company_name) && !empty($b2b_position)) ? $b2b_position.', '.$b2b_company_name: $b2b_position; ?>
                    </p>
                </div>
            </div>

            <?php // End loop.
                    endwhile;
                    echo "</div>";
                // No value.
                else :
                    echo "<h2>No Data Found....</h2>";
                endif;
            }else if(strtolower($article_type) != 'forum'){?>
            <div class="single-interview-top-sec">
                <div class="left-img-sec">
                    <img src="<?php echo $interviewer_thumbnail;?>" alt="<?php echo $image_alt;?>"
                        class="interviewer-img" <?php echo $no_interview_img_css;?>>
                </div>
                <div class="right-content-sec">
                    <p class="text-red font-bold description text-uppercase country-and-sector">
                        <?php echo $country_and_sector_details;?></p>
                    <h1 class="sub-section-title text-black font-bold interviewer-title"><?php echo get_the_title();?>
                    </h1>
                    <p class="sub-title text-grey font-medium interviewer-position-details">
                        <?php echo $position.' '.$company_name;?></p>
                    <div class="bio-details text-grey">
                        <p class="text-black sub-description font-bold bio-text text-uppercase">Bio</p>
                        <p class="text-grey description font-medium bio-detail"><?php echo $bio_details;?></p>
                    </div>
                    <div class="pub-date-soc-share-sec text-grey sub-description font-medium">
                        <p class="published-date"><?php echo $date_and_country;?></p>
                        <span class="interview-tag-list">
                            <?php 
                                
                                $posttags = get_the_tags($post_id);
                                if ($posttags) {
                                    $taglist = "";
                                    foreach($posttags as $tag) {
                                        $taglist .= '<a href="' . esc_attr( get_tag_link( $tag->term_id ) ) . '">' . __( $tag->name ) . '</a>' . ', ';
                                    }                                        
                                }
                                $countries = tby_display_categories($post_id,'country');
                                if(!empty($countries)){
                                    echo '/ '.$taglist;
                                    echo $countries;
                                }else{
                                    echo '/ '.rtrim($taglist, ", ");
                                }                        
                            ?>
                        </span>
                        <?php echo do_shortcode('[Sassy_Social_Share title="Share on:"]');?>
                    </div>
                </div>
            </div>
            <?php }            
            ?>
            <?php if(strtolower($article_type) == 'b2b'){?>
            <div class="pub-date-soc-share-sec text-grey sub-description font-medium">
                <p class="published-date"><?php echo $date_and_country;?></p>
                <span class="interview-tag-list">
                    <?php 
                        
                        $posttags = get_the_tags($post_id);
                        if ($posttags) {
                            $taglist = "";
                            foreach($posttags as $tag) {
                                $taglist .= '<a href="' . esc_attr( get_tag_link( $tag->term_id ) ) . '">' . __( $tag->name ) . '</a>' . ', ';
                            }                                                                            
                        }
                        $countries = tby_display_categories($post_id,'country');
                        if(!empty($countries)){
                            echo '/ '.$taglist;
                            echo $countries;
                        }else{
                            echo '/ '.rtrim($taglist, ", ");
                        }                                
                    ?>
                </span>
                <?php echo do_shortcode('[Sassy_Social_Share title="Share on:"]');?>
            </div>
            <?php } ?>
            <div class="single-interview-mid-sec">
                <?php if(strtolower($article_type) != 'forum'){?>
                <div class="left-side-sec">
                    <div class="head-title text-black font-bold headline-text"><?php echo get_the_excerpt();?></div>
                    <?php the_content();?>
                </div>
                <?php }else{?>
                <?php 
                if( have_rows('interviewees_fields') ):
                    echo "<div class='forum-section-main-outer'>";                    
                    echo "<div class='head-title text-black font-bold headline-text'>".get_the_excerpt()."</div>";?>
                <div class="pub-date-soc-share-sec text-grey sub-description font-medium">
                    <p class="published-date"><?php echo $date_and_country;?></p>
                    <span class="interview-tag-list">
                        <?php 
                            
                            $posttags = get_the_tags($post_id);
                            if ($posttags) {
                                $taglist = "";
                                foreach($posttags as $tag) {
                                    $taglist .= '<a href="' . esc_attr( get_tag_link( $tag->term_id ) ) . '">' . __( $tag->name ) . '</a>' . ', ';
                                }                                                                                                                        
                            }
                            $countries = tby_display_categories($post_id,'country');
                            if(!empty($countries)){
                                echo '/ '.$taglist;
                                echo $countries;
                            }else{
                                echo '/ '.rtrim($taglist, ", ");
                            }                                    
                        ?>
                    </span>
                    <?php echo do_shortcode('[Sassy_Social_Share title="Share on:"]');?>
                </div>
                <?php echo "<div class='forum-section-main-inner forum-card'>";
                    // Loop through rows.
                    while( have_rows('interviewees_fields') ) : the_row();

                        // Load sub field value.                                            
                        $forum_interviewer_name  = get_sub_field('interviewee_name');
                        $forum_company_name      = get_sub_field('interviewee_company');
                        $forum_position          = get_sub_field('interviewee_position');
                        $interviewer_bio_details = get_sub_field('interviewee_biography');
                        // Do something...
                        ?>
                <div class="forum-items">
                    <div class="forum-interviewer-details">
                        <h2 class="title text-black font-bold m-t-0"><?php echo $forum_interviewer_name;?></h2>
                        <p class="sub-title text-grey font-medium">
                            <?php echo (!empty($forum_company_name) && !empty($forum_position)) ? $forum_position.', '.$forum_company_name: $forum_position; ?>
                        </p>
                        <p class="description text-black font-regular"><?php echo $interviewer_bio_details; ?></p>
                    </div>
                </div>

                <?php // End loop.
                    endwhile;
                    echo "</div>";
                    echo "</div>";
                // No value.
                else :
                    echo "<h2>No Data Found....</h2>";
                endif;
            }?>
                <div class="right-side-sec">
                    <?php echo do_shortcode("[ads_section_layout layout='vertical']");?>
                    <?php echo do_shortcode("[trending_video_sidebar number_of_videos = 4]");?>
                </div>

            </div> <!-- middle section finished -->
            <div class="may-also-be-intrested-sec-main-outer pad100">
                <div class="may-also-be-intrested-sec-main-title-with-border">
                    <h2 class="sub-section-title text-black font-bold border-line m-t-0">You may also be interested
                        in...
                    </h2>
                </div>
                <div class="three-col-may-intrested-interviews">
                    <?php
                                global $post;
                                $tax_query = array();$default = array();
                                $all_countries      = wp_get_post_terms( $post_id, 'country', array() );
                                $child_countries    = array();
                                foreach ($all_countries as $key => $value) {                
                                    if($all_countries[$key]->parent != 0){
                                        array_push($child_countries,$all_countries[$key]->term_id);
                                    }
                                }
                                if( !empty ($child_countries)){
                                    $tax_query[] = array(
                                        'taxonomy' => 'country', //double check your taxonomy name 
                                        'field'    => 'id',
                                        'terms'    => $child_countries,
                                    );
                                }

                                $args =  array(
                                    'post_type' => 'interview',
                                    'posts_per_page' => 3,
                                    'post__not_in'   => array($post_id),
                                    'tax_query' => $tax_query,                                       
                                );
                                
                                $query= new WP_Query($args);                                
                        
                                if( $query->have_posts() ):
            
                                    while ( $query->have_posts() ) : $query->the_post();
                                    $post_id = $post->ID;
                                    $detail_page_url  	= get_permalink($post_id);
                                    $position = $company_name = "";                                                           
                                    $interview_sector 	= (!empty(get_field('interview_sector',$post_id))) ? get_field('interview_sector',$post_id) : '';
                                    $interview_type     = (!empty(get_field('interview_article_type',$post_id))) ? get_field('interview_article_type',$post_id) : 'Interview';
                                        if(strtolower($interview_type) == 'b2b'){
                                            $image =  ( !empty(get_field('interview_b2b_image','option')['sizes']['medium_large']) ) ? get_field('interview_b2b_image','option')['sizes']['medium_large'] : get_stylesheet_directory_uri().'/assets/images/b2b.png';        
                                            $image_alt = 'b2b';
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
                                            $image 				= get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri() .'/assets/images/interview-default.png';
                                            $title 				= get_the_title();
                                            $position 			= (!empty(get_field('interviewees_fields',$post_id)[0]['interviewee_position'])) ? get_field('interviewees_fields',$post_id)[0]['interviewee_position'].',' : '';
                                            $company_name 		= (!empty(get_field('interviewees_fields',$post_id)[0]['interviewee_company'])) ? get_field('interviewees_fields',$post_id)[0]['interviewee_company'] : '';
                                            $no_interview_img_css = get_the_post_thumbnail_url( $post_id,'large' ) ? '' : "style = 'background-color: #EBEBEB;'";
                                        }

                                    $all_countries 		= wp_get_post_terms( $post_id, 'country', array() );
                                    $child_countries 	= array();
                                    foreach ($all_countries as $key => $value) {				
                                        if($all_countries[$key]->parent != 0){
                                            array_push($child_countries,$all_countries[$key]->name);
                                        }
                                    }
                                    $country = strtoupper(implode(', ', $child_countries));
                                    $country_and_sector_details = $country.' - '.$interview_sector;                                    
                                    ?>
                    <div class="interviews-block-inner <?php echo "custom".strtolower($interview_type);?>">
                        <div class="interviews-inner">
                            <?php 
                            if(strtolower($interview_type) == 'b2b' || strtolower($interview_type) == 'forum'){?>
                            <a href="<?php echo $detail_page_url;?>">
                                <div class="interviewers-list-thumb <?php echo strtolower($interview_type);?>"
                                    style="background-image: url(<?php echo $header_img;?>) , url(<?php echo $image;?>);">
                                </div>
                            </a>
                            <?php }else{?>
                            <a href="<?php echo $detail_page_url;?>">
                                <div class="interviewers-list-thumb interview-default-img" >
                                    <img src="<?php echo $image;?>"
                                        alt="<?php echo $image_alt;?>" <?php echo $no_interview_img_css;?>>
                                </div>
                            </a>
                            <?php } ?>

                            <div class="interviews-list-content-sec">
                                <p
                                    class="interviewers-country-sect-details sub-description text-red font-bold text-uppercase">
                                    <?php echo $country_and_sector_details;?></p>
                                <a href="<?php echo $detail_page_url;?>">
                                    <h3 class="interview-main-title title text-black font-bold">
                                        <?php echo $title;?>
                                    </h3>
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
                                                echo $position.' '.$company_name;
                                            }
                                        ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; 
                                else :
                                    echo "<h2>No Interviews Found!!!</h2>";
                                endif;                            
                            ?>
                </div>
                <?php                     
                        $view_more_btn_url      = get_field('view_all_interviews_url','option');
                        $link_url = $view_more_btn_url['url'];
                        $link_title = $view_more_btn_url['title'];
                        $link_target = $view_more_btn_url['target'] ? $view_more_btn_url['target'] : '_self';
		                echo "<div class='view_all_article_btn_main'><a class='article-view-more-btn ctm-btn view_all_custom_btn' target=$link_target href=$link_url>$link_title</a></div>";
	                ?>
            </div>

        </div>
    </div>
</div>
<?php get_footer();
?>
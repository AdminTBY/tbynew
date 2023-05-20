<?php
$post_id = get_the_ID();
$detail_page_url  	= get_permalink($post_id);
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
    if(get_post_type($post_id) == 'post'){
        $image 				= get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri() .'/assets/images/article-default.jpg';
    }else if(get_post_type($post_id) == 'interview'){
        $no_interview_img_css = get_the_post_thumbnail_url( $post_id,'large' ) ? '' : "style = 'background-color: #EBEBEB;'";
        $image 				= get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri() .'/assets/images/interview-default.png';
    }else{
        $image 				= get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri() .'/assets/images/NoImageAvailable.png';
    }      
    $title 				= get_the_title();
}
?>
<div class="article-filtered-block-inner">
        <?php 
			if(strtolower($interview_type) == 'b2b' || strtolower($interview_type) == 'forum'){?>
				<a  href="<?php echo $detail_page_url;?>">
					<div class="article-list-thumb <?php echo strtolower($interview_type);?>" style="background-image: url(<?php echo $header_img;?>) , url(<?php echo $image;?>);">															
					</div>
				</a>
        	<?php }else{?>
				<a href="<?php echo $detail_page_url;?>">
					<div class="article-list-thumb <?php echo $class;?>">					
							<img src="<?php echo $image;?>" alt="<?php echo $image_alt;?>" <?php echo $no_interview_img_css;?>>					
					</div>
				</a>
        <?php } ?>

    <div class="article-list-content-sec">            
            <?php 
                if(get_post_type($post_id) == 'post'){
                    $article_sector 	= (!empty(get_field('article_sector',$post_id))) ? get_field('article_sector',$post_id) : '';
                    $article_subject 	= (!empty(get_field('article_subject',$post_id))) ? get_field('article_subject',$post_id) : '';
                    $article_type       = 'Article';
                    $author_id			= $post->post_author;
                    $author_name 		= get_the_author_meta( 'display_name' , $author_id );?>

                    <div class="article-inner-content">
                        <a href="<?php echo $detail_page_url;?>">
                            <h3 class="article-main-title title text-black font-bold"><?php echo $title;?></h3>
                        </a>
                        <p class="article-subject sub-title text-grey font-medium">
                            <?php echo $article_subject;?>
                        </p>
                        <p class="article-author description text-grey font-medium">
                            <a
                                href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo $author_name;?>
                            </a>
                        </p>
                        <a class="article-view-more-btn" href="<?php echo $detail_page_url;?>">View More</a>
                    </div>
            <?php }elseif(get_post_type($post_id) == 'interview'){
                $article_sector 	= (!empty(get_field('interview_sector',$post_id))) ? get_field('interview_sector',$post_id) : '';									
                $position 			= (!empty(get_field('interviewees_fields',$post_id)[0]['interviewee_position'])) ? get_field('interviewees_fields',$post_id)[0]['interviewee_position'].',' : '';
                $company_name 		= (!empty(get_field('interviewees_fields',$post_id)[0]['interviewee_company'])) ? get_field('interviewees_fields',$post_id)[0]['interviewee_company'] : '';

                if(strtolower($interview_type) == 'b2b'){
                    $image =  ( !empty(get_field('interview_b2b_image','option')['sizes']['medium_large']) ) ? get_field('interview_b2b_image','option')['sizes']['medium_large'] : get_stylesheet_directory_uri().'/assets/images/b2b.png';        
                    $image_alt = 'b2b';
                    $header_img =  ( !empty(get_field('interview_b2b_header_image','option')['sizes']['medium_large']) ) ? get_field('interview_b2b_header_image','option')['sizes']['medium_large'] : get_stylesheet_directory_uri().'/assets/images/b2b-header.png';	
                    $title = get_field('interviewees_fields',$post_id)[0]['interviewee_name'].' and '.get_field('interviewees_fields',$post_id)[1]['interviewee_name'];				
                }elseif (strtolower($interview_type) == 'forum') {
                    $image = ( !empty(get_field('interview_forum_image','option')['sizes']['medium_large']) ) ? get_field('interview_forum_image','option')['sizes']['medium_large'] : get_stylesheet_directory_uri().'/assets/images/forum.png';
                    $image_alt = 'forum';
                    $header_img =  ( !empty(get_field('interview_forum_header_image','option')['sizes']['medium_large']) ) ? get_field('interview_forum_header_image','option')['sizes']['medium_large'] : get_stylesheet_directory_uri().'/assets/images/forum-header.png';	
                    $title 				= get_the_title();
                }else{
                    $image_id 			= get_post_thumbnail_id();
                    $image_alt 			= get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
                    $image_alt 			= (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
                    $detail_page_url  	= get_permalink($post_id);
                    $image 				= get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri() .'/assets/images/interview-default.png';
                    $title 				= get_the_title();
                    $no_interview_img_css = get_the_post_thumbnail_url( $post_id,'large' ) ? '' : "style = 'background-color: #EBEBEB;'";
                }
                ?>
                    <div class="article-inner-content">
                        <a href="<?php echo $detail_page_url;?>">
                            <h3 class="article-main-title sub-title text-black font-bold"><?php echo $title;?></h3>
                        </a>
                        <p class="interview-type"><?php echo $interview_type;?></p>
                        <p class="article-author description text-grey font-medium">
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
                        <a class="article-view-more-btn" href="<?php echo $detail_page_url;?>">View More</a>
                    </div>
            <?php }elseif(get_post_type($post_id) == 'product'){
                global $product;

                $price_html = $product->get_price_html();
                ?>
                <div class="article-inner-content">

                    <a href="<?php echo $detail_page_url;?>">
                        <h3 class="article-main-title title text-black font-bold"><?php echo $title;?></h3>
                    </a>
                    <span class="price">
                        <?php echo $price_html;?>
                    </span>
                    <p class="product-search-short-desc"><?php echo wp_strip_all_tags( wp_trim_words( get_the_content(), 15, '...' ) );?></p>

                    <div class="search-add-to-cart-btn">
                        <?php do_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );?>
                    </div>
                </div>
            <?php }else{ ?>
                <div class="article-inner-content">
                    <a href="<?php echo $detail_page_url;?>">
                        <h3 class="article-main-title title text-black font-bold"><?php echo $title;?></h3>
                    </a>
                    <p class="other-search-short-desc"><?php echo wp_strip_all_tags( wp_trim_words( get_the_content(), 15, '...' ) );?></p>
                    <a class="article-view-more-btn" href="<?php echo $detail_page_url;?>">View More</a>
                </div>
            <?php }   
            ?>							
    </div>
</div>


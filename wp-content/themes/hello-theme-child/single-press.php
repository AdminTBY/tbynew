<?php
    get_header();
    global $post;
    $post_id = $post->ID;
    $press_thumbnail_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' )[0] ? wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' )[0] : get_stylesheet_directory_uri().''."/assets/images/NoImageAvailable.png";
    $image_id = get_post_thumbnail_id();
    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
    $image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
    $press_media_type 	= (!empty(get_field('media_news_type',$post_id))) ? get_field('media_news_type',$post_id) : '';
    $author_id = get_post_field ('post_author', $post_id);
    $author_name = get_the_author_meta( 'display_name' , $author_id );
?>
<main id="content" <?php post_class('single-press-main');?> role="main">
    <div class="single-article-main-outer">
        <div class="single-article-container">
            <div class="single-article-banner-img">
                <img src="<?php echo $press_thumbnail_img;?>" alt="">
            </div>
            <div class="after-banner-sec-main-outer">
                <div class="content-left-sec">
                    <p class="text-red font-bold description text-uppercase article-sector">
                        <?php echo $press_media_type;?></p>
                    <h1 class="article-main-title text-black"><?php echo get_the_title();?></h1>
                    <div class="author-and-social-sec">
                        <div class="article-author-meda-data text-grey sub-description">
                            <p class="author-and-pub-date text-grey">
                                <?php 
                                    $taxonomies = get_object_taxonomies('press');
                                        $taxonomy = 'country';                                       
                                        $terms = get_the_terms( $post->ID, $taxonomy );
                                        if ( !empty( $terms ) ) {
                                            $presslist = "";
                                            foreach ( $terms as $term ){
                                                $presslist .= '<a class="text-uppercase" href="' .get_term_link($term->slug, $taxonomy) .'">'.$term->name.'</a>' . ', ';
                                            }
                                            echo rtrim($presslist, ", ");
                                        }                                    
                                ?>
                                |
                                <?php echo get_the_date( 'M. d, Y' );?>
                            </p>
                            <?php echo do_shortcode('[Sassy_Social_Share title="Share on:"]');?>
                        </div>                      
                        <!-- Press slider section start  -->                                            
                        <?php                             
                            if( have_rows('single_media_news_slider') ){
                                $count = count(get_field('single_media_news_slider'));
                                if($count > 1){
                            ?>
                            <div id="single-detail-first-slider" class="swiper-container single-detail-first-slider">
                                <!-- Additional required wrapper -->
                                <div class="swiper-wrapper">
                                    <?php                                                                                                                                                                                                    
                                        // Loop through rows.
                                        while( have_rows('single_media_news_slider') ) : the_row();

                                            // Load sub field value.
                                            $image = get_sub_field('image')['sizes']['medium_large'];
                                            $image_alt = (!empty(get_sub_field('image')['alt'])) ? get_sub_field('image')['alt'] : get_sub_field('image')['title'];
                                            $title = get_sub_field('title');
                                            // Do something...?>
                                            <div class='swiper-slide'>
                                                <img src='<?php echo $image?>' alt='<?php echo $image_alt?>'>   
                                                <h2 class="text-center"><?php echo $title?></h2>
                                            </div>
                                        <?php // End loop.
                                        endwhile;                                        
                                    ?>                                                                        
                                </div>

                                <!-- If we need navigation buttons -->
                                <div class="swiper-button-prev single-detail-first-slider"></div>
                                <div class="swiper-button-next single-detail-first-slider"></div>
                            </div>
                            <?php }else{
                                    $image = get_field('single_media_news_slider',$post_id)[0]['image']['sizes']['medium_large'];
                                    $image_alt = (!empty(get_field('single_media_news_slider',$post_id)[0]['image']['alt'])) ? get_field('single_media_news_slider',$post_id)[0]['image']['alt'] : get_field('single_media_news_slider',$post_id)[0]['image']['title'];                                                                        
                                    $title = get_field('single_media_news_slider',$post_id)[0]['title'];                                
                                ?>  
                                    <div class="single-detail-first-slider">
                                        <img src='<?php echo $image?>' alt='<?php echo $image_alt?>'>   
                                        <h2 class="text-center"><?php echo $title?></h2>
                                    </div>
                            <?php }
                            }
                        ?>
                        <!-- Press slider section end...  -->  
                        <?php the_content();?>
                    </div>
                </div>
                <div class="sidebar-right-sec">
                    <?php echo do_shortcode("[trending_video_sidebar number_of_videos = 4]");?>
                </div>

            </div>
        </div>
    </div>

   
    <!-- second article list section end -->
</main>

<?php get_footer();
?>
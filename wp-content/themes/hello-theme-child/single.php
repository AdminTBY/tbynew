<?php
/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();
$post_id = $post->ID;
$article_img_url = get_the_post_thumbnail_url( $post_id,'full' ) ? get_the_post_thumbnail_url( $post_id,'full' ) : '';
$post_article_sector = (!empty(get_field('article_sector',$post_id))) ? get_field('article_sector',$post_id) : '';
$post_article_subject = (!empty(get_field('article_subject',$post_id)))? get_field('article_subject',$post_id) : '';
$author_id = get_post_field ('post_author', $post_id);
$article_type       = 'Article';
$author_name = get_the_author_meta( 'display_name' , $author_id );
$is_sponsored_article = get_field('article_sponsor',$post_id);

$no_img_cls = empty($article_img_url)? 'no-img-m' : '';

if(!empty($is_sponsored_article)){
    $sponsored_img = get_the_post_thumbnail_url( $is_sponsored_article , 'full' );
    $sponserd_url  = get_field('sponsor_url',$is_sponsored_article);
    echo "<h3 class='text-center single-sponsor-text'>this article is sponsored by</h3>";
    echo "<a href='$sponserd_url' target = '_blank'> <div class='single-sponsored-img text-center'><img src='$sponsored_img' alt='sponsored-img'></div></a>";    
}
?>

<main id="content" <?php post_class('single-article-main');?> role="main">    
    <div class="single-article-main-outer">
        <div class="single-article-container">
            <?php if(!empty($article_img_url)){?>
                <div class="single-article-banner-img">
                    <img src="<?php echo $article_img_url;?>" alt="">
                </div>
            <?php }?>
            <div class="after-banner-sec-main-outer <?php echo $no_img_cls;?>">
                <div class="content-left-sec">
                    <p class="text-red font-bold description text-uppercase article-sector">
                        <?php echo $post_article_sector;?></p>
                    <h1 class="article-main-title text-black"><?php echo get_the_title();?></h1>
                    <p class="article-subject font-medium sub-title text-black"><?php echo $post_article_subject?></p>
                    <div class="author-and-social-sec">
                        <div class="article-author-meda-data text-grey sub-description">
                            <p class="author-and-pub-date text-grey">By
                                <a href="<?php echo get_author_posts_url($author_id); ?>"><?php echo $author_name;?></a>
                                <?php 
                                echo !empty($author_id) ? " | " : ""; 
                                $posttags = get_the_tags($post_id);
                                if ($posttags) {
                                    $taglist = "";
                                    foreach($posttags as $tag) {
                                        $taglist .= '<a href="' . esc_attr( get_tag_link( $tag->term_id ) ) . '">' . __( $tag->name ) . '</a>' . ', ';
                                    }
                                }
                                $countries = tby_display_categories($post_id,'country');
                                if(!empty($countries)){
                                    echo $taglist;
                                    echo $countries.' | ';
                                }else{
                                    echo rtrim($taglist, ", ");
                                    echo !empty($posttags) ? ' | ' : '';
                                }                                                            
                                echo get_the_date( 'M d, Y' );?>
                            </p>
                            <?php echo do_shortcode('[Sassy_Social_Share title="Share on:"]');?>
                        </div>
                        
                        <!-- Article slider section start  -->                                            
                        <?php                             
                            if( have_rows('single_article_slider') ){
                                $count = count(get_field('single_article_slider'));
                                if($count > 1){
                            ?>
                            <div id="single-detail-first-slider" class="swiper-container single-detail-first-slider">
                                <!-- Additional required wrapper -->
                                <div class="swiper-wrapper">
                                    <?php                                                                                                                                                                                                    
                                        // Loop through rows.
                                        while( have_rows('single_article_slider') ) : the_row();

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
                                    $image = get_field('single_article_slider',$post_id)[0]['image']['sizes']['medium_large'];
                                    $image_alt = (!empty(get_field('single_article_slider',$post_id)[0]['image']['alt'])) ? get_field('single_article_slider',$post_id)[0]['image']['alt'] : get_field('single_article_slider',$post_id)[0]['image']['title'];                                                                        
                                    $title = get_field('single_article_slider',$post_id)[0]['title'];                                
                                ?>  
                                    <div class="single-detail-first-slider">
                                        <img src='<?php echo $image?>' alt='<?php echo $image_alt?>'>   
                                        <h2 class="text-center"><?php echo $title?></h2>
                                    </div>
                            <?php }
                            }
                        ?>
                        <!-- Article slider section end...  -->

                        <div class="article-subject title font-medium text-black"><?php echo get_the_excerpt();?>
                        </div>
                        <?php the_content();?>
                    </div>
                </div>
                <div class="sidebar-right-sec">
                    <?php echo do_shortcode("[ads_section_layout layout='vertical']");?>
                    <?php echo do_shortcode("[trending_video_sidebar number_of_videos = 4]");?>
                </div>

            </div>
        </div>
    </div>  

    <?php  
    // Sponsored article start 
        $exclude_id = array();
        $sponsored_article_args =  array(
            'post_type' => 'post',
            'numberposts' => 1,
            'order' =>'DESC',   
            'meta_query' => array(
                array(
                    'key'   => 'article_sponsor',
                    'compare' => '!=',
                    'value' => '',
                )
            )           
        );

        $sponsored_articles = get_posts($sponsored_article_args);
        if ( $sponsored_articles ) {
            foreach ( $sponsored_articles as $post ) : 
                setup_postdata( $post );
                $sponsor_post_id = $post->ID;
                $exclude_id[] = $sponsor_post_id;
                $image_id = get_post_thumbnail_id();
                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
                $image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
                $detail_page_url    = get_permalink($sponsor_post_id);                      
                $image              = get_the_post_thumbnail_url( $sponsor_post_id,'large' ) ? get_the_post_thumbnail_url( $sponsor_post_id,'large' ) : PLUGIN_DIR .'assets/images/article-default.jpg';
                $article_sector     = (!empty(get_field('article_sector',$sponsor_post_id))) ? get_field('article_sector',$sponsor_post_id) : '';
                $title              = get_the_title($sponsor_post_id);
                $article_subject    = (!empty(get_field('article_sponsor',$sponsor_post_id))) ? get_the_title(get_field('article_sponsor',$sponsor_post_id)) : '';
                $article_type       = 'Article';
                $author_id          = $post->post_author;
                $author_name        = get_the_author_meta( 'display_name' , $author_id ); 
                
                ob_start();
                ?>
                <div class="article-block-inner swiper-slide sponsored-article">
                    <div class="artcle-inner-content">
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
                                <p class="sponsored-content">SPONSORED CONTENT<br> <?php echo $article_subject;?></p>
                                <p class="article-author description text-grey font-medium">
                                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo $author_name;?></a>
                                </p>
                            </div>
                            <a class="article-view-more-btn" href="<?php echo $detail_page_url;?>">View More</a>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
            $sponsored_article_html = ob_get_contents();
            ob_end_clean();         
            wp_reset_postdata();
        }       

        // Sponsored article END....
        ?>                           

    <!-- slider section start  -->
    <?php
    global $post;
    $tax_query = array();
    $default = array();
    $exclude_id[] = $post_id;    

    $args =  array(
        'post_type'      => 'post',
        'posts_per_page' => 6,
        "orderby"        => "date",
        "order"          => "DESC",
        'post__not_in'   => $exclude_id,        
    );
    
    
    ?>
    <div class="latest-article-wrapper inline-slider-dg pad100">
        <div class="container">
            <div class=" latest-article-slider">
                <div class="article-block-main-outer swiper-container country-slider">
                    <div class="swiper-wrapper">
                        <?php 
                $query= new WP_Query($args);
                if( $query->have_posts() ):
					while ( $query->have_posts() ) : $query->the_post();?>

                        <?php get_template_part( 'article-templates/article', 'titleslider' ); ?>
                        
                    <?php endwhile; 
				else :
					echo "No Data Found.";
				endif;?>
                    </div>
                </div>
                <?php if (  $query->have_posts() ): ?>
                    <div class="swiper-button-next country-slider">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow-left.svg" alt="" />
                    </div>
                    <div class="swiper-button-prev country-slider">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow-left.svg" alt="" />
                    </div>
                    <!-- <div class="swiper-pagination country-slider"></div> -->
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- article list section end -->

    <!-- second slider section start  -->
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
        'post_type' => 'post',
        'posts_per_page' => 10,
        'post__not_in'   => $exclude_id,
        'tax_query' => $tax_query,                                       
    );
    
    
    ?>
    <div class="latest-article-wrapper pad100 p-t-0">

        <div class="container">

            <h2 class="sub-section-title text-black font-bold border-line m-t-0">You may also be interested in...
            </h2>
            <div class=" latest-article-slider">
                <div class="article-block-main-outer second-country-slider swiper-container">
                    <div class="swiper-wrapper">
                        <?php 
            $query= new WP_Query($args);
            if( $query->have_posts() ):
                echo $sponsored_article_html;
					while ( $query->have_posts() ) : $query->the_post();?>

                        <?php get_template_part( 'article-templates/article', 'slider' ); ?>

                    <?php endwhile; 
				else :
					echo "No Data Found.";
				endif;?>
                    </div>

                </div>
                <?php if (  $query->have_posts() ): ?>
                    <div class="swiper-button-next second-country-slider">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow-left.svg" alt="" />
                    </div>
                    <div class="swiper-button-prev second-country-slider">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow-left.svg" alt="" />
                    </div>
                    <div class="swiper-pagination second-country-slider"></div>
                <?php endif; ?>
            </div>
            <?php 
                    $view_more_article_url      = get_field('view_all_articles_url','option');
                    $link_url = (!empty ($view_more_article_url['url']))? $view_more_article_url['url'] : site_url('/articles');;
                    $link_title = $view_more_article_url['title'];
                    $link_target = $view_more_article_url['target'] ? $view_more_article_url['target'] : '_self';                
                    echo "<div class='view_all_article_btn_main'><a class='article-view-more-btn ctm-btn view_all_custom_btn' target=$link_target href=$link_url>$link_title</a></div>";            
            ?>
        </div>
    </div>
    <!-- second article list section end -->
</main>
<?php
get_footer();

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
$article_img_url    = wp_get_attachment_url(get_post_thumbnail_id($post_id), 'full') ? wp_get_attachment_url(get_post_thumbnail_id($post_id), 'full') : get_stylesheet_directory_uri().''."/assets/images/NoImageAvailable.png";
$start_date		    = get_field( 'pe_start_date', $post_id ); 
$start_date_f   	= date("d F Y", strtotime($start_date)); 
$start_date_only 	= date("d", strtotime($start_date));
$start_date_month 	= date("F", strtotime($start_date));
$start_date_year 	= date("Y", strtotime($start_date)); 

$end_Date 		= get_field( 'pe_end_date', $post_id );
$end_date_f 	= date("d F Y", strtotime($end_Date)); 
$end_date_only 	= date("d", strtotime($end_Date)); 

$venue 			= get_field( 'pe_venue', $post_id ); 
$external_url 	= get_field( 'pe_external_url', $post_id );
$download_url 	= get_field( 'pe_download_file', $post_id );
?>
<main id="content" <?php post_class('single-article-main');?> role="main">
    <div class="partenered-detail-page">
        <div class="container">
            <div class="partnered-events-wrapper partnered pad100">
                <div class="partnered-event-main-title-with-border">
                    <h2 class="section-title text-black font-bold border-line m-t-0">
                        Partnered Events
                    </h2>
                </div>
                <div class="partnered-events-outer">
                    <div class="partnered-events-block-wrapper latest-partnered-events">
                        <div class="partnered-event-block-inner">
                            <div class="partnered-event-inner-content">
                                <div class="partnered-event-list-thumb">
                                    <img src="<?php echo $article_img_url;?>" alt="">
                                </div>

                                <div class="partnered-event-list-content-sec">
                                    <div class="partnered-event-inner-content">
                                        <div class="partnered-event-inner-content-in">
                                            <h3 class="partnered-event-main-title title text-black font-bold">
                                                <?php echo get_the_title();?>
                                            </h3>

                                            <p class="article-subject sub-title font-bold text-red">
                                                <?php                                
                                                    echo $start_date_f.'</br>'.$venue;
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="event-button">
                                    <?php if($external_url){                  
                                            $link_url    = $external_url['url'];
                                            $link_title  = $external_url['title'] ? $external_url['title'] : "Attend this event";
                                            $link_target = $external_url['target'] ? $external_url['target'] : '_self';
                                        ?>
                                        <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr( $link_target ); ?>"
                                            class="partnered-event-view-more-btn attend-event">
                                            <?php echo esc_html( $link_title ); ?>
                                        </a>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="partnered-information">
                            <h2 class="title text-black font-bold text-uppercase">Event Description</h2>
                            <p class="partnered-content"> <?php the_content();?> </p>
                            <?php if(!empty($download_url)){?>
                                <a class="partnered-event-view-more-btn press-release"
                                href="<?php echo $download_url;?>" target="_blank">Press
                                Release</a>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>

            <?php 
        $partnered_events_per_page = 3;
    ?>
            <div class="partnered-events-wrapper upcoming pad100 p-t-0">
                <div class="partnered-event-main-title-with-border">
                    <h2 class="section-title text-black font-bold border-line m-t-0">Upcoming Events</h2>
                </div>
                <div class="partnered-events-outer">
                    <div class="partnered-events-block-wrapper upcoming-partnered-events">
                        <input type="hidden" class="tby_partnered_events_per_page"
                            partnered-current-post="<?php echo $post_id;?>"
                            value="<?php echo $partnered_events_per_page; ?>" />
                        <?php
                $date = new DateTime();
                $today = $date->getTimestamp();
                $current_date_time = date('Y-m-d H:i:s', $today);
                $args =  array(
                    'post_type' => 'partnered-event',
                    'posts_per_page' => $partnered_events_per_page,
                    'post__not_in' => array( $post_id ),
                    'meta_key' => 'pe_start_date',
                    'orderby' => 'pe_start_date', 
                    'post_status' => 'publish',
                    'order' => 'ASC',
                    'meta_query'    => array(
                        'relation'      => 'AND',
                        array(
                            'key'       => 'pe_start_date',
                            'compare'   => '>=',
                            'value'     => $current_date_time,
                            'type'      => 'DATETIME'
                        )
                    )
                );
                
                $query= new WP_Query($args);
                
                global $post;
                ?>
                        <div class="partnered-event-block-results">
                            <?php
                    if( $query->have_posts() ):
                        while ( $query->have_posts() ) : $query->the_post();?>
                            <div class="partnered-event-block-inner">
                                <?php
                                $post_id = $post->ID;
                                $image_id = get_post_thumbnail_id();
                                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
                                $image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
                                $detail_page_url  	= get_permalink($post_id);
                                $image              = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri() .'/assets/images/NoImageAvailable.png';

                                $title 			= get_the_title();
                                $start_date		= get_field( 'pe_start_date', $post_id ); 
                                $start_date_f 	= date("d F Y", strtotime($start_date)); 
                                $venue 			= get_field( 'pe_venue', $post_id ); 
                                $external_url 	= get_field( 'pe_external_url', $post_id );
                                $download_url 	= get_field( 'pe_download_file', $post_id );
                            ?>

                                <div class="partnered-event-inner-content">
                                    <div class="partnered-event-list-thumb">
                                        <img src='<?php echo $image;?>' alt="<?php echo $image_alt;?>" />
                                    </div>

                                    <div class="partnered-event-list-content-sec">
                                        <div class="partnered-event-inner-content">
                                            <a href="<?php echo $detail_page_url;?>">
                                                <h3 class="partnered-event-main-title sub-title text-black font-bold">
                                                    <?php echo $title;?></h3>

                                                <p class="article-subject sub-description font-bold text-red">
                                                    <?php
                                                        echo $start_date_f.'</br>'.$venue;
                                                    ?>
                                                </p>
                                            </a>
                                        </div>
                                        <a href="<?php echo $detail_page_url;?>" class="partner-list-learn-more">Learn More</a>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; 
                    else :
                        echo "No Data Found.";
                    endif;
                    ?>
                        </div>
                        <?php
                if ( $query->max_num_pages > 1 ){
                    echo '<button class="tby_load_more_partnered_events_btn">See more</button>';
                    echo '<input type="hidden" name="partnered_events_paged_track" class="tby_partnered_events_paged_track" value="2">';						
                }
                ?>
                    </div>

                </div>

                <div class='spinner-wrapper'>
                    <div class='nb-spinner'></div>
                </div>

            </div>
            
        </div>
    </div>
    </div>
</main>

<?php
get_footer();
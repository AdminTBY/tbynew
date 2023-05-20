<div class="article-block-inner swiper-slide">
<?php
$post_id = $post->ID;
$image_id = get_post_thumbnail_id();
$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
$detail_page_url  	= get_permalink($post_id);
$article_sector 	= (!empty(get_field('article_sector',$post_id))) ? get_field('article_sector',$post_id) : '';
$article_subject 	= (!empty(get_field('article_subject',$post_id))) ? get_field('article_subject',$post_id) : '';
$article_type       = (!empty(get_field('article_type',$post_id))) ? get_field('article_type',$post_id) : '';
$author_id			= $post->post_author;
$author_name 		= get_the_author_meta( 'display_name' , $author_id );
$image              = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri().''."/assets/images/article-default.jpg";
?>

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
                    <h3 class="article-main-title title text-black font-bold">
                        <?php echo get_the_title();?></h3>
                </a>
                <p class="article-subject sub-title text-grey font-medium">
                <?php echo $article_subject;?>
                </p>
                <p class="article-author description text-grey font-medium">
                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo $author_name;?></a>
                </p>
            </div>
            <a class="article-view-more-btn" href="<?php echo $detail_page_url;?>">View More</a>
        </div>
    </div>
</div>
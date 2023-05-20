<div class="article-block-inner swiper-slide">
<?php
$first_slider_post_id = $post->ID;
$image_id = get_post_thumbnail_id();
$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
$detail_page_url  	= get_permalink($first_slider_post_id);
$image              = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri().''."/assets/images/article-default.jpg";

?>
    <div class="artcle-inner-content">
        <div class="article-list-thumb">
        <a href="<?php echo $detail_page_url;?>">
            <img src='<?php echo $image;?>' alt="<?php echo $image_alt;?>" />
        </a>
            <a href="<?php echo $detail_page_url; ?>"><p class="description text-black font-medium"><?php echo get_the_title();?></p></a>
        </div>
    </div>
</div>
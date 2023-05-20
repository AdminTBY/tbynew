<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
?>
<main id="content" class="site-main" role="main">
    <div class="content_class tby-404 pad100">
        <div class="content_image_class">
            <img src="<?php echo the_field('404_image', 'option'); ?>" alt="404">
        </div>

        <div class="text_class title text-black font-medium">
            <?php echo the_field('404_content', 'option'); ?>
        </div>

        <?php 
		$page_not_found_url      = get_field('404_button','option');
		$link_url = (!empty ($page_not_found_url['url']))? $page_not_found_url['url'] : site_url('/');;
		$link_title = $page_not_found_url['title'];
		$link_target = $page_not_found_url['target'] ? $page_not_found_url['target'] : '_self';
	
		echo "<div class='home_button_class'><a class='article-view-more-btn ctm-btn view_all_custom_btn' target=$link_target href=$link_url>$link_title</a></div>";            
		?>

    </div>

</main>

<?php get_footer(); ?>
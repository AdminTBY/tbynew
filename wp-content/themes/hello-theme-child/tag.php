<?php
/**
 * The template for displaying tag page.
 *
 * @package HelloElementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

$tag_id = get_queried_object()->term_id;
$tag_name = get_queried_object()->name;    
$articles_per_page = get_field('general_articles_per_page', 'option') ? get_field('general_articles_per_page', 'option') : 2;
$interviews_per_page = get_field('general_interviews_per_page', 'option') ? get_field('general_interviews_per_page', 'option') : 2;
?>
<main id="content" class="tag-main" role="main">
    <div class="tag-wrapper pad100">
        <div class="container">
            <header class="page-header">
                <h2 class="sub-section-title text-black font-bold border-line m-t-0">
                    <?php printf('Articles with <span>"%s"</span> tag', $tag_name);?></h2>
                <input type="hidden" class="tag_id" value="<?php echo $tag_id; ?>">
                <input type="hidden" class="articles_per_page" value="<?php echo $articles_per_page; ?>">
            </header>
            <div class="tag-article-main-outer pad100 p-t-0">
                <div class="tag-article-container">
                    <div class="tag-article-container-inner">
                        <?php 
                    $args = array(
                        'tag_id'=> $tag_id,
                        'post_type' => 'post',
                        'posts_per_page' => $articles_per_page
                    );
                    $tag_articles = new WP_Query( $args );

                    if($tag_articles->have_posts()):
                        while($tag_articles->have_posts()): $tag_articles->the_post();
                            get_template_part('partials/default/content', 'article');
                        endwhile;
                        else :
                            echo "<h2>No Article Found!!!</h2>";
                    endif;
                ?>
                    </div>
                    <?php if ( $tag_articles->max_num_pages > 1 ){?>
                    <button class='load_more_articles_btn see-more-btn'>See more</button>
                    <input type="hidden" class="articles_paged post_paged" value="2">
                    <?php }?>
                </div>
            </div>
            <header class="page-sub-header">
                <span
                    class="h1-style sub-section-title text-black font-bold border-line m-t-0"><?php printf('Interviews with <span>"%s"</span> tag', $tag_name);?></span>
                <input type="hidden" class="interviews_per_page" value="<?php echo $interviews_per_page; ?>">
            </header>
            <div class="tag-interview-main-outer">
                <div class="tag-interview-container">
                    <div class="tag-interview-container-inner">
                        <?php 
                    $args = array(
						'tag_id'=> $tag_id,
                        'post_type' => 'interview',
                        'posts_per_page' => $interviews_per_page
                    );
                    $tag_interviews = new WP_Query( $args );

                    if($tag_interviews->have_posts()):
                        while($tag_interviews->have_posts()): $tag_interviews->the_post();
                            get_template_part('partials/default/content', 'interview');
                        endwhile;
                        else :
                            echo "<h2>No Interview Found!!!</h2>";
                    endif;
                ?>
                    </div>
                    <?php if ( $tag_interviews->max_num_pages > 1 ){?>
                    <button class='load_more_interviews_btn see-more-btn'>See more</button>
                    <input type="hidden" class="interviews_paged post_paged" value="2">
                    <?php }?>
                </div>
            </div>
            <div class="spinner-wrapper" style="display: none;">
                <div class="nb-spinner"></div>
            </div>
        </div>
    </div>
</main>
<?php
get_footer();
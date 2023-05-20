<?php
/**
 * The template for displaying author page.
 *
 * @package HelloElementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

$author_id = get_queried_object()->ID;
$author_name = get_queried_object()->data->display_name;    
$articles_per_page = get_field('general_articles_per_page', 'option') ? get_field('general_articles_per_page', 'option') : 2;
$interviews_per_page = get_field('general_interviews_per_page', 'option') ? get_field('general_interviews_per_page', 'option') : 2;
?>
<main id="content" class="author-main" role="main">
    <div class="author-wrapper pad100">
        <div class="container">
            <header class="page-header">
                <h2 class="sub-section-title text-black font-bold border-line m-t-0">
                    <?php printf('Articles with <span>"%s"</span> author', $author_name);?></h2>
                <input type="hidden" class="author_id" value="<?php echo $author_id; ?>">
                <input type="hidden" class="articles_per_page" value="<?php echo $articles_per_page; ?>">
            </header>
            <div class="author-article-main-outer pad100 p-t-0">
                <div class="author-article-container">
                    <div class="author-article-container-inner">
                        <?php 
                    $args = array(
                        'author' => $author_id,
                        'post_type' => 'post',
                        'posts_per_page' => $articles_per_page
                    );
                    $author_articles = new WP_Query( $args );

                    if($author_articles->have_posts()):
                        while($author_articles->have_posts()): $author_articles->the_post();
                            get_template_part('partials/default/content', 'article');
                        endwhile;
                        else :
                            echo "<h2>No Content Found!!!</h2>";
                    endif;
                ?>
                    </div>
                    <?php if ( $author_articles->max_num_pages > 1 ){?>
                    <button class='load_more_articles_btn see-more-btn'>See more</button>
                    <input type="hidden" class="articles_paged post_paged" value="2">
                    <?php }?>
                </div>
            </div>
            <?php /*
            <header class="page-sub-header">
                <span
                    class="h1-style sub-section-title text-black font-bold border-line m-t-0"><?php printf('Interviews with <span>"%s"</span> author', $author_name);?></span>
                <input type="hidden" class="interviews_per_page" value="<?php echo $interviews_per_page; ?>">
            </header>
            <div class="author-interview-main-outer">
                <div class="author-interview-container">
                    <div class="author-interview-container-inner">
                        <?php 
                    $args = array(
                        'author' => $author_id,
                        'post_type' => 'interview',
                        'posts_per_page' => $interviews_per_page
                    );
                    $author_interviews = new WP_Query( $args );

                    if($author_interviews->have_posts()):
                        while($author_interviews->have_posts()): $author_interviews->the_post();
                            get_template_part('partials/default/content', 'interview');
                        endwhile;
                        else :
                            echo "<h2>No Auther Interview Found!!!</h2>";
                    endif;
                ?>
                    </div>
                    <?php if ( $author_interviews->max_num_pages > 1 ){?>
                    <button class='load_more_interviews_btn see-more-btn'>See more</button>
                    <input type="hidden" class="interviews_paged post_paged" value="2">
                    <?php }?>
                </div>
            </div>*/?>
            <div class="spinner-wrapper" style="display: none;">
                <div class="nb-spinner"></div>
            </div>
            
        </div>
    </div>
</main>
<?php
get_footer();
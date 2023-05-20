<?php
/**
 * The template for displaying search results.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
?>
<main id="content" class="search-main-outer tag-main tag-wrapper pad100" role="main">
	<?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
    <div class="container">
		<header class="page-header">
			<h1 class="sub-section-title text-black font-bold border-line m-t-0">
				<?php esc_html_e( 'Search results for: ', 'hello-elementor' ); ?>
				<span><?php echo get_search_query(); ?></span>
			</h1>
		</header>
	<?php endif; ?>
	<div class="tag-article-main-outer pad100 p-t-0">
        <div class="tag-article-container">
            <div class="tag-article-container-inner">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part('partials/default/content', 'search');
			endwhile;
			?>
		<?php else : ?>
			<p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for.', 'hello-elementor' ); ?></p>
		<?php endif; ?>
	        </div>        

	<?php
	global $wp_query;
	if ( $wp_query->max_num_pages > 1 ) :
		?>
        <button class='load_more_interviews_btn load_more_search_btn see-more-btn'>See more</button>
        <input type="hidden" class="search_paged" value="2">
	<?php endif; ?>
        </div>
    </div>
		<div class="spinner-wrapper" style="display: none;">
			<div class="nb-spinner"></div>
		</div>
    </div>
</main>

<?php get_footer();?>
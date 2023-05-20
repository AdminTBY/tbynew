<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">


    <?php
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>
<div class="shop-filter-main-outer pad100">
    <div class="spinner-wrapper" style="display: none;">
        <div class="nb-spinner"></div>
    </div>
    <div class="container">
        <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
        <h1 class="section-title text-black font-bold border-line m-t-0"><?php woocommerce_page_title(); ?></h1>
        <?php endif; ?>

        <div class="shop-filter-main-outer-wrapper">
            <div class="shop-filter-main-left-outer">

                <p class="title font-bold text-black filter-main-text">Filters</p>
                <ul>
                    <li class="shop-filter-countries-ul active">
                        <span class="dropdown-icon sub-title font-medium text-black">Country</span>
                        <img class="dropdown-icon arrow"
                            src="<?php echo get_stylesheet_directory_uri();?>/assets/icons/arrow.png"
                            alt="dropdown-icon" />
                        <ul>
                            <li><span class='description text-black font-medium call_ajax_for_filter' value='0'>All
                                    Countries</span></li>
                            <?php
                        $parent_dropdown_class = 'dropdown-icon';
                        $all_countries_with_products = tby_get_terms_by_post_type(array('country'),array('product'));
                        $args = array( 'orderby' => 'slug', 'hide_empty' => 1, 'parent' => 0 , 'taxonomy' => 'country'); 
                        $categories = get_categories( $args );
                        if (!empty($categories)) {
                            foreach ( $categories as $List_category ) {
                                if(tby_chk_country_exist($all_countries_with_products,$List_category->term_id)){
                                    $all_child_categories = get_categories(array(
                                        'orderby'			  => 'name',
                                        'order'   			  => 'ASC',
                                        'child_of'            => $List_category->term_id, //set your category ID
                                        'hide_empty'          => 1,
                                        'hide_title_if_empty' => false,	
                                        'taxonomy'            => 'country'				
                                    ));					 
                                    
                                    if(!empty($all_child_categories)){
                                        echo "<li><span class='description text-black font-medium call_ajax_for_filter' value='$List_category->term_id'>$List_category->name</span><img class='dropdown-icon arrow' src='".get_stylesheet_directory_uri()."/assets/icons/arrow.png' alt='dropdown-icon' />";
                                        echo "<ul>";
                                        foreach ( $all_child_categories as $List_child_category ) {
                                            if(tby_chk_country_exist($all_countries_with_products,$List_child_category->term_id)){
                                                echo "<li><span class='call_ajax_for_filter' value='$List_child_category->term_id'>$List_child_category->name</span></li>";
                                            }
                                        }
                                        echo "</ul>";												
                                    }else{
                                        echo "<li><span class='description text-black font-medium call_ajax_for_filter' value='$List_category->term_id'>$List_category->name</span>";
                                    }
                                    echo "</li>";
                                
                                }
                            }	
                        }
                    ?>
                        </ul>
                    </li>
                    <li class="shop-filter-item-type-ul">
                        <span class="dropdown-icon sub-title font-medium text-black">Item Type</span><img
                            class="dropdown-icon arrow"
                            src="<?php echo get_stylesheet_directory_uri();?>/assets/icons/arrow.png"
                            alt="dropdown-icon" />
                        <ul>
                            <li>
                                <span class="description text-black font-medium call_ajax_for_filter" value="0">All
                                    Items</span>
                            </li>
                            <?php
                                $args = array( 'orderby' => 'slug', 'hide_empty' => 1, 'parent' => 0 , 'taxonomy' => 'item_type' ); 
                                $item_types = get_categories( $args ); 
                                foreach($item_types as $item_type){
                            ?>
                            <li>
                                <span class="description text-black font-medium call_ajax_for_filter"
                                    value="<?php echo $item_type->term_id;?>"><?php echo $item_type->name;?></span>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>

                </ul>
                <?php 
            /**
             * Hook: woocommerce_sidebar.
             *
             * @hooked woocommerce_get_sidebar - 10
             */
            do_action( 'woocommerce_sidebar' );
        ?>
            </div>


            <!-- shop list by filters start  -->
            <input type="hidden" name="posts_paged_track" class="posts_paged_track" value="2" />
            <div class="right-sec-shop-filtered-list">
                <div class="shop-filtered-block-inner-wrapper">
                    <?php
            if ( woocommerce_product_loop() ) {

                /**
                 * Hook: woocommerce_before_shop_loop.
                 *
                 * @hooked woocommerce_output_all_notices - 10
                 * @hooked woocommerce_result_count - 20
                 * @hooked woocommerce_catalog_ordering - 30
                 */
                do_action( 'woocommerce_before_shop_loop' );

                woocommerce_product_loop_start();

                if ( wc_get_loop_prop( 'total' ) ) {
                    while ( have_posts() ) {
                        the_post();

                        /**
                         * Hook: woocommerce_shop_loop.
                         */
                        do_action( 'woocommerce_shop_loop' );

                        wc_get_template_part( 'content', 'product' );
                    }
                }

                woocommerce_product_loop_end();

                /**
                 * Hook: woocommerce_after_shop_loop.
                 *
                 * @hooked woocommerce_pagination - 10
                 */
                do_action( 'woocommerce_after_shop_loop' );
            } else {
                /**
                 * Hook: woocommerce_no_products_found.
                 *
                 * @hooked wc_no_products_found - 10
                 */
                do_action( 'woocommerce_no_products_found' );
            }?>
                </div>
                <button class="load_more_products_btn">See more</button>
            </div>
            <!-- shop list by filters END... -->
        </div>
    </div>
</div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );



get_footer( 'shop' );
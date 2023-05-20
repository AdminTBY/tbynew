<?php


define('tby_scripts_version', '1.0.0');
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Require child theme functions
 */
require get_stylesheet_directory() . '/inc/tby-render-css-js.php';
require get_stylesheet_directory() . '/inc/cpt-functions.php';
require get_stylesheet_directory() . '/inc/woocommerce-functions.php';
require get_stylesheet_directory() . '/inc/ajax-functions.php';
require get_stylesheet_directory() . '/inc/shortcode-functions.php';
require get_stylesheet_directory() . '/inc/front-end-functions.php';

// Assign category to other post type
function hello_share_category_with_other_post()
{
    register_taxonomy_for_object_type('category', 'interview');
    register_taxonomy_for_object_type('country', 'tribe_events');
    register_taxonomy_for_object_type('post_tag', 'interview');
}

add_action('init', 'hello_share_category_with_other_post');

// Added general options setting menu
if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'General Settings',
        'menu_title' => 'General Settings',
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false,
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Youtube Videos Settings',
        'menu_title' => 'Youtube Videos Settings',
        'menu_slug' => 'youtube-videos-settings',
        'parent_slug' => 'theme-general-settings',
    ));

}

// To select parent custom taxonomy automatically when child selected
add_action('save_post', 'assign_parent_terms', 10, 2);

function assign_parent_terms($post_id, $post)
{

    // get all assigned terms
    $terms = wp_get_post_terms($post_id, 'country');
    foreach ($terms as $term) {
        while ($term->parent != 0 && !has_term($term->parent, 'country', $post)) {
            // move upward until we get to 0 level terms
            wp_set_post_terms($post_id, array($term->parent), 'country', true);
            $term = get_term($term->parent, 'country');
        }
    }

    // For assign automatically parent article sector
    $terms = wp_get_post_terms($post_id, 'article_sector');
    foreach ($terms as $term) {
        while ($term->parent != 0 && !has_term($term->parent, 'article_sector', $post)) {
            // move upward until we get to 0 level terms
            wp_set_post_terms($post_id, array($term->parent), 'article_sector', true);
            $term = get_term($term->parent, 'article_sector');
        }
    }
}
/**
 * Notify admin when a new customer account is created
 */
add_action( 'woocommerce_created_customer', 'woocommerce_created_customer_admin_notification' );
function woocommerce_created_customer_admin_notification( $customer_id ) {
  wp_send_new_user_notifications( $customer_id, 'admin' );
}

add_filter( 'rank_math/sitemap/enable_caching', '__return_false');


add_action('init','add_cors_http_header');
add_filter( 'wp_headers', 'send_cors_headers', 11, 1 );
function send_cors_headers( $headers ) {
    $headers['Access-Control-Allow-Origin'] = $_SERVER[ 'HTTP_ORIGIN' ];
    return $headers;
}

function add_cors_http_header(){
    header("Access-Control-Allow-Origin: *");
}


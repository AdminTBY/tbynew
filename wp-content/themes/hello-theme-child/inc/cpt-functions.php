<?php

// Register Custom Post Type - Interview
function interview_post_type() {

    $labels = array(
        'name'                  => _x( 'Interview', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Interview', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Interview', 'text_domain' ),
        'name_admin_bar'        => __( 'Interview', 'text_domain' ),
        'archives'              => __( 'Interview Archives', 'text_domain' ),
        'attributes'            => __( 'Interview Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Interview:', 'text_domain' ),
        'all_items'             => __( 'All Interview', 'text_domain' ),
        'add_new_item'          => __( 'Add New Interview', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Interview', 'text_domain' ),
        'edit_item'             => __( 'Edit Interview', 'text_domain' ),
        'update_item'           => __( 'Update Interview', 'text_domain' ),
        'view_item'             => __( 'View Interview', 'text_domain' ),
        'view_items'            => __( 'View Interview', 'text_domain' ),
        'search_items'          => __( 'Search Interview', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into Interview', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Interview', 'text_domain' ),
        'items_list'            => __( 'Interview list', 'text_domain' ),
        'items_list_navigation' => __( 'Interview list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter Interview list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Interview', 'text_domain' ),
        'description'           => __( 'Interview', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'),
        'taxonomies'            => array( '' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );

    register_post_type( 'interview', $args );
}
add_action( 'init', 'interview_post_type', 0 );


// Register Custom Post Type - Media & News
function news_post_type() {

    $labels = array(
        'name'                  => _x( 'Media & News', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Media & News', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Media & News', 'text_domain' ),
        'name_admin_bar'        => __( 'Media & News', 'text_domain' ),
        'archives'              => __( 'Media & News Archives', 'text_domain' ),
        'attributes'            => __( 'Media & News Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Media & News:', 'text_domain' ),
        'all_items'             => __( 'All Media & News', 'text_domain' ),
        'add_new_item'          => __( 'Add New Media & News', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Media & News', 'text_domain' ),
        'edit_item'             => __( 'Edit Media & News', 'text_domain' ),
        'update_item'           => __( 'Update Media & News', 'text_domain' ),
        'view_item'             => __( 'View Media & News', 'text_domain' ),
        'view_items'            => __( 'View Media & News', 'text_domain' ),
        'search_items'          => __( 'Search Media & News', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into Media & News', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Media & News', 'text_domain' ),
        'items_list'            => __( 'Media & News list', 'text_domain' ),
        'items_list_navigation' => __( 'Media & News list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter Media & News list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Media & News', 'text_domain' ),
        'description'           => __( 'Media & News', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'),
        'taxonomies'            => array( '' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );

    register_post_type( 'press', $args );
}
add_action( 'init', 'news_post_type', 0 );


/**
 * Add custom taxonomies for Country & Region
 *
 * Additional custom taxonomy for Country & Region
 */
function hello_add_custom_taxonomies() {
    
    register_taxonomy('country', array('post','interview','press', 'product','partnered-event'), array(
      // Hierarchical taxonomy (like categories)
      'hierarchical' => true,
      'show_admin_column' => true,
      // This array of options controls the labels displayed in the WordPress Admin UI
      'labels' => array(
        'name' => _x( 'Country & Region', 'Country & Region' ),
        'singular_name' => _x( 'Country & Region', 'Country & Region' ),
        'search_items' =>  __( 'Search Country & Region' ),
        'all_items' => __( 'All Country & Region' ),
        'parent_item' => __( 'Parent Country & Region' ),
        'parent_item_colon' => __( 'Parent Country & Region:' ),
        'edit_item' => __( 'Edit Country & Region' ),
        'update_item' => __( 'Update Country & Region' ),
        'add_new_item' => __( 'Add New Country & Region' ),
        'new_item_name' => __( 'New Country & Region' ),
        'menu_name' => __( 'Country & Region' ),
      ),
    ));

    register_taxonomy('event_type', array('partnered-event'), array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => true,
        'show_admin_column' => true,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
          'name' => _x( 'Event Type', 'Event Type' ),
          'singular_name' => _x( 'Event Type', 'Event Type' ),
          'search_items' =>  __( 'Search Event Type' ),
          'all_items' => __( 'All Event Type' ),
          'parent_item' => __( 'Parent Event Type' ),
          'parent_item_colon' => __( 'Parent Event Type:' ),
          'edit_item' => __( 'Edit Event Type' ),
          'update_item' => __( 'Update Event Type' ),
          'add_new_item' => __( 'Add New Event Type' ),
          'new_item_name' => __( 'New Event Type' ),
          'menu_name' => __( 'Event Type' ),
        ),
      ));
  }
  add_action( 'init', 'hello_add_custom_taxonomies', 0 );

    // Register Custom Post Type - Partnered Events
function partnered_events_post_type() {

    $labels = array(
        'name'                  => _x( 'Partnered Events', 'Post Type General Name', 'tby' ),
        'singular_name'         => _x( 'Partnered Events', 'Post Type Singular Name', 'tby' ),
        'menu_name'             => __( 'Partnered Events', 'tby' ),
        'name_admin_bar'        => __( 'Partnered Events', 'tby' ),
        'archives'              => __( 'Partnered Events Archives', 'tby' ),
        'attributes'            => __( 'Partnered Events Attributes', 'tby' ),
        'parent_item_colon'     => __( 'Parent Partnered Events:', 'tby' ),
        'all_items'             => __( 'All Partnered Events', 'tby' ),
        'add_new_item'          => __( 'Add New Partnered Events', 'tby' ),
        'add_new'               => __( 'Add New', 'tby' ),
        'new_item'              => __( 'New Partnered Events', 'tby' ),
        'edit_item'             => __( 'Edit Partnered Events', 'tby' ),
        'update_item'           => __( 'Update Partnered Events', 'tby' ),
        'view_item'             => __( 'View Partnered Events', 'tby' ),
        'view_items'            => __( 'View Partnered Events', 'tby' ),
        'search_items'          => __( 'Search Partnered Events', 'tby' ),
        'not_found'             => __( 'Not found', 'tby' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'tby' ),
        'featured_image'        => __( 'Featured Image', 'tby' ),
        'set_featured_image'    => __( 'Set featured image', 'tby' ),
        'remove_featured_image' => __( 'Remove featured image', 'tby' ),
        'use_featured_image'    => __( 'Use as featured image', 'tby' ),
        'insert_into_item'      => __( 'Insert into Partnered Events', 'tby' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Partnered Events', 'tby' ),
        'items_list'            => __( 'Partnered Events list', 'tby' ),
        'items_list_navigation' => __( 'Partnered Events list navigation', 'tby' ),
        'filter_items_list'     => __( 'Filter Partnered Events list', 'tby' ),
    );
    $args = array(
        'label'                 => __( 'Partnered Events', 'tby' ),
        'description'           => __( 'Partnered Events', 'tby' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields'),
        'taxonomies'            => array( '' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );

    register_post_type( 'partnered-event', $args );
}
add_action( 'init', 'partnered_events_post_type', 0 );

add_action( 'init', 'tby_change_post_name_in_admin' );
// Change dashboard Posts to Article
function tby_change_post_name_in_admin() {
    $get_post_type = get_post_type_object('post');
    $labels = $get_post_type->labels;
        $labels->name = 'Article';
        $labels->singular_name = 'Article';
        $labels->add_new = 'Add Article';
        $labels->add_new_item = 'Add Article';
        $labels->edit_item = 'Edit Article';
        $labels->new_item = 'Article';
        $labels->view_item = 'View Article';
        $labels->search_items = 'Search Article';
        $labels->not_found = 'No Article found';
        $labels->not_found_in_trash = 'No Article found in Trash';
        $labels->all_items = 'All Article';
        $labels->menu_name = 'Article';
        $labels->name_admin_bar = 'Article';
}

// Register Custom Post Type - Sponsered Events
function article_sponsor_post_type() {

    $labels = array(
        'name'                  => _x( 'Article Sponsor', 'Post Type General Name', 'tby' ),
        'singular_name'         => _x( 'Article Sponsor', 'Post Type Singular Name', 'tby' ),
        'menu_name'             => __( 'Article Sponsor', 'tby' ),
        'name_admin_bar'        => __( 'Article Sponsor', 'tby' ),
        'archives'              => __( 'Article Sponsor Archives', 'tby' ),
        'attributes'            => __( 'Article Sponsor Attributes', 'tby' ),
        'parent_item_colon'     => __( 'Parent Article Sponsor:', 'tby' ),
        'all_items'             => __( 'All Article Sponsor', 'tby' ),
        'add_new_item'          => __( 'Add New Article Sponsor', 'tby' ),
        'add_new'               => __( 'Add New', 'tby' ),
        'new_item'              => __( 'New Article Sponsor', 'tby' ),
        'edit_item'             => __( 'Edit Article Sponsor', 'tby' ),
        'update_item'           => __( 'Update Article Sponsor', 'tby' ),
        'view_item'             => __( 'View Article Sponsor', 'tby' ),
        'view_items'            => __( 'View Article Sponsor', 'tby' ),
        'search_items'          => __( 'Search Article Sponsor', 'tby' ),
        'not_found'             => __( 'Not found', 'tby' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'tby' ),
        'featured_image'        => __( 'Featured Image', 'tby' ),
        'set_featured_image'    => __( 'Set featured image', 'tby' ),
        'remove_featured_image' => __( 'Remove featured image', 'tby' ),
        'use_featured_image'    => __( 'Use as featured image', 'tby' ),
        'insert_into_item'      => __( 'Insert into Article Sponsor', 'tby' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Article Sponsor', 'tby' ),
        'items_list'            => __( 'Article Sponsor list', 'tby' ),
        'items_list_navigation' => __( 'Article Sponsor list navigation', 'tby' ),
        'filter_items_list'     => __( 'Filter Article Sponsor list', 'tby' ),
    );
    $args = array(
        'label'                 => __( 'Article Sponsor', 'tby' ),
        'description'           => __( 'Article Sponsor', 'tby' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields'),
        'taxonomies'            => array( '' ),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'query_var'             => false,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
    );

    register_post_type( 'article-sponsor', $args );
}
add_action( 'init', 'article_sponsor_post_type', 0 );

// function to display terms according to cpt 
function tby_get_terms_by_post_type( $taxonomies, $post_types ) {

    global $wpdb;

    $query = $wpdb->prepare(
        "SELECT t.*, COUNT(*) from $wpdb->terms AS t
        LEFT JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
        LEFT JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id
        LEFT JOIN $wpdb->posts AS p ON p.ID = r.object_id
        WHERE p.post_type IN('%s') AND tt.taxonomy IN('%s')
        GROUP BY t.term_id",
        join( "', '", $post_types ),
        join( "', '", $taxonomies )
    );

    $results = $wpdb->get_results( $query );

    return $results;

}

function tby_chk_country_exist($all_countries, $term_id) {

    foreach ($all_countries as $country) {
        if ($country->term_id == $term_id) {
            return true;
        }
    }
    return false;
}
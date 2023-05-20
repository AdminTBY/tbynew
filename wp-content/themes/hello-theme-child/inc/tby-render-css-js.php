<?php

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts()
{
    wp_enqueue_style(
        'hello-elementor-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [
            'hello-elementor-theme-style',
        ],
        tby_scripts_version
    );

    wp_enqueue_style(
        'hello-elementor-custom-style',
        get_stylesheet_directory_uri() . '/assets/css/custom.css',
        [
            'hello-elementor-theme-style',
        ],
        tby_scripts_version
    );

    if(is_shop()){
        wp_enqueue_script( 'tby-shop-custom', get_stylesheet_directory_uri() . '/assets/js/tby-shop-custom.js', array( 'jquery' ), tby_scripts_version, true );
        wp_localize_script(
            'tby-shop-custom',
            'shop_params',
            array(
                'nonce'     => wp_create_nonce( 'tby-shop' ),
                'action'    => 'tby_filter_shop_products',
                'ajax_url'  => admin_url('admin-ajax.php')
            )
        );
    }

    if(is_author()){
        wp_enqueue_script( 'tby-author-custom', get_stylesheet_directory_uri() . '/assets/js/tby-author-custom.js', array( 'jquery' ), tby_scripts_version, true );
        wp_localize_script(
            'tby-author-custom',
            'author_params',
            array(
                'nonce'     => wp_create_nonce( 'tby-author' ),
                'action'    => 'tby_filter_author_posts',
                'ajax_url'  => admin_url('admin-ajax.php')
            )
        );
    }

    if(is_tag()){
        wp_enqueue_script( 'tby-tag-custom', get_stylesheet_directory_uri() . '/assets/js/tby-tag-custom.js', array( 'jquery' ), tby_scripts_version, true );
        wp_localize_script(
            'tby-tag-custom',
            'tag_params',
            array(
                'nonce'     => wp_create_nonce( 'tby-tag' ),
                'action'    => 'tby_filter_tag_posts',
                'ajax_url'  => admin_url('admin-ajax.php')
            )
        );
    }
    
    wp_enqueue_style(
        'custom-swiper-bundle',
         get_stylesheet_directory_uri() . '/assets/css/swiper-bundle.min.css'
    );

	wp_enqueue_style(
        'latest-article-detail-block-css', 
        get_stylesheet_directory_uri() .'/assets/css/latest-article-block.css'
    );

    wp_enqueue_script(
        'custom-swiper-min-js',
        get_stylesheet_directory_uri() .'/assets/js/swiper-bundle.min.js' 
    );		

    wp_enqueue_script( 
        'custom-latest-article-block-js',
        get_stylesheet_directory_uri() .'/assets/js/latest-article-block.js'
    );
    
    wp_enqueue_style(
        'jquery.fancybox.min',
        get_stylesheet_directory_uri() .'/assets/css/jquery.fancybox.min.css'
    );

    wp_enqueue_script( 
        'jquery.fancybox.min',
         get_stylesheet_directory_uri() .'/assets/js/jquery.fancybox.min.js' 
    );

    wp_enqueue_script( 
        'custom-js', 
        get_stylesheet_directory_uri() .'/assets/js/tby-custom.js' 
    );

    wp_localize_script(
        'custom-js',
        'popup_params',
        array(
            'action'    => 'tby_filter_countries',
            'ajax_url'  => admin_url('admin-ajax.php')
        )
    );

	wp_enqueue_script( 
        'tby-partnered-events-block-js', 
        get_stylesheet_directory_uri() .'/assets/js/tby-partnered-events-block.js',
        array( 'jquery' ), 
        '2.0.0', 
        true 
    );

    wp_localize_script( 
        'tby-partnered-events-block-js', 
        'pe_params',
            array( 
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce('tby-pe'),
            'action' => 'tby_get_upcoming_partnered_events'
        ) 
    );

    // Loaded search page js 
    if(is_search()){
        wp_enqueue_script( 'tby-search-custom', get_stylesheet_directory_uri() . '/assets/js/tby-search-result.js', array( 'jquery' ), tby_scripts_version, true );
        wp_localize_script(
            'tby-search-custom',
            'search_params',
            array(
                'nonce'     => wp_create_nonce( 'tby-search' ),
                'action'    => 'tby_get_search_posts',
                'ajax_url'  => admin_url('admin-ajax.php'),
                's'         => get_search_query(),
            )
        );
    }
    // Loaded search page js END.. 
}
add_action('wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20);

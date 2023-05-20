<?php

add_action( 'wp_footer', 'tby_set_div_for_popup', 10 );
function tby_set_div_for_popup(){?>
    <div class="spinner-wrapper country-popup" style="display: none;">
        <div class="nb-spinner"></div>
    </div>
    <div style="display: none;" id="tby-countries-popup">
        <div class="popup-data"></div>
    </div>
<?php }

/**
 * Ajax function for getting countries popup data. 
 */
add_action( 'wp_ajax_tby_filter_countries', 'tby_countries_popup_html' );
add_action( 'wp_ajax_nopriv_tby_filter_countries', 'tby_countries_popup_html' );

function tby_countries_popup_html(){

    $all_countries_of_articles = tby_get_terms_by_post_type(array('country'),array('post'));
    $all_countries_of_interviews = tby_get_terms_by_post_type(array('country'),array('interview'));
    $all_countries_of_products = tby_get_terms_by_post_type(array('country'),array('product'));

    $all_popup_countries = array_column(array_merge($all_countries_of_articles,$all_countries_of_interviews,$all_countries_of_products), NULL, 'term_id');
    ksort($all_popup_countries);
    ob_start();
    ?>
        <h2><?php _e('Countries', 'tby');?></h2>
        <div class="tby-countries-list">
            <?php
                $args = array(
                    'orderby'       => 'term_id', 
                    'order'         => 'ASC',
                    'hide_empty'    => false, 
                    'parent'        => 0
                );
                
                $terms = get_terms('country', $args);

                foreach($terms as $term){
                    $term_link = get_term_link( $term );
                    if(tby_chk_country_exist($all_popup_countries,$term->term_id)){
                    ?>
                    <div class="tby-countries-list-column">
                        <a href="<?php echo esc_url( $term_link );?>" class="tby-countries-list-column-main"><?php echo $term->name;?></a>

                        <div class="tby-countries-list-column-sub">
                            <?php 
                                $sub_args = array(
                                    'orderby'       => 'term_id', 
                                    'order'         => 'ASC',
                                    'hide_empty'    => false, 
                                    'parent'        => $term->term_id,
                                    'meta_query' => array(
                                        'relation' => 'OR',
                                        array(
                                           'key'       => 'show_country_on_popup',
                                           'value'     => 1,
                                           'compare'   => '='
                                        ),
                                        array(
                                            'key'       => 'show_country_on_popup',
                                            'compare' => 'NOT EXISTS' // For Initial field creation
                                         ),
                                   )
                                );
                                
                                $sub_terms = get_terms('country', $sub_args);
                                foreach($sub_terms as $sub_term){
                                    $sub_term_link = get_term_link( $sub_term );
                                    if(tby_chk_country_exist($all_popup_countries,$sub_term->term_id)){ ?>
                                        <a href="<?php echo esc_url( $sub_term_link );?>"><?php echo $sub_term->name;?></a>
                                    <?php
                                    }
                                }
                            ?>
                        </div>

                    </div>
                    <?php
                    }
                }
            ?>
            

        </div>
    <?php
    $popup_html = ob_get_clean();
    $response = array(
        'html_response' => $popup_html,
    );

    wp_send_json_success( $response );
}

// Loaded contact form popup html in footer 
add_action('wp_footer', 'event_detail_contact_form_popup',9); 
function event_detail_contact_form_popup() { 
    ?>
        <!-- Contact form 7 Popup form -->
        <div style="display: none" id="modal-form" class="event-detail-pop-form">
            <div class="logo">
                <img src="<?php echo the_field('event_popup_logo', 'option'); ?>"
                    alt="logo" />
                <span class="title text-black font-bold"><?php echo the_field('event_popup_title', 'option'); ?></span>
            </div>
            <p class="description font-regular text-grey">
                <?php echo the_field('event_popup_description', 'option'); ?>
            </p>
            <?php 
            $popup_shortcode = get_field('event_form_shortcode', 'option');
            echo do_shortcode($popup_shortcode);     
                                   
            ?>
            
        </div>
        <!-- End Contact form 7 Popup form -->
        
        <!-- Load Mailchimp Form -->
        <div style="display: none" id="mailchimp-form" class="event-detail-pop-form event-list-code">
                <div class="logo">
                    <img src="<?php echo the_field('event_popup_logo', 'option'); ?>"
                        alt="logo" />
                    <span class="title text-black font-bold">
                        <?php echo the_field('event_popup_title', 'option'); ?>
                    </span>
                </div>
                
                <?php 
                $mailchimp_popup_shortcode = get_field('event_detail_mailchimp_form_shortcode', 'option');
                echo do_shortcode($mailchimp_popup_shortcode);  
                ?>
        </div>
        <!-- End Load Mailchimp Form -->
    <?php
}

function tby_display_categories($post_id,$taxonomy = "country"){

    // Get the term IDs assigned to post.
    $all_parent_id = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'ids','parent' => 0, ) );
    $post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'ids','exclude' => $all_parent_id, ) );
    
    // Separator between links.
    $separator = ', ';
    
    if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
    
        $term_ids = implode( ',' , $post_terms );
    
        $terms = wp_list_categories( array(
            'title_li' => '',
            'style'    => 'none',
            'echo'     => false,
            'taxonomy' => $taxonomy,
            'include'  => $term_ids
        ) );
    
        $terms = rtrim( trim( str_replace( '<br />',  $separator, $terms ) ), $separator );
    
        // Display post categories.
        return $terms;
    }
}

// Loaded ADS scripts 

if ( ! function_exists( 'tby_load_ads_scripts' ) ) {
    function tby_load_ads_scripts() {?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-18782665-1"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-18782665-1');
        </script>
        <script async src="//businessyear.co.uk/www/delivery/asyncjs.php"></script>
    <?php }
}
add_action( 'wp_head', 'tby_load_ads_scripts' );

// Code to remove pages from default search page    
function remove_pages_from_search() {
    global $wp_post_types;
    $wp_post_types['page']->exclude_from_search = true;
    $wp_post_types['tribe_venue']->exclude_from_search = true;
    $wp_post_types['tribe_organizer']->exclude_from_search = true;
}
add_action('init', 'remove_pages_from_search');


// Code to change country url 
add_filter('request', 'tby_remove_term_request', 1, 1 );
 
function tby_remove_term_request($query){
	
	$tax_name = 'country'; // specify your taxonomy name here
	
	// Request for child terms differs, we should make an additional check
	if( $query['attachment'] ) :
		$include_children = true;
		$name = $query['attachment'];
	else:
		$include_children = false;
		$name = $query['name'];
	endif;
	
	
	$term = get_term_by('slug', $name, $tax_name); // get the current term to make sure it exists
	
	if (isset($name) && $term && !is_wp_error($term)): // check it here
		
		if( $include_children ) {
			unset($query['attachment']);
			$parent = $term->parent;
			while( $parent ) {
				$parent_term = get_term( $parent, $tax_name);
				$name = $parent_term->slug . '/' . $name;
				$parent = $parent_term->parent;
			}
		} else {
			unset($query['name']);
		}
		
		switch( $tax_name ):
			case 'category':{
				$query['category_name'] = $name; // for categories
				break;
			}
			case 'post_tag':{
				$query['tag'] = $name; // for post tags
				break;
			}
			default:{
				$query[$tax_name] = $name; // for another taxonomies
				break;
			}
		endswitch;
 
	endif;
	
	return $query;
	
}
 
 
add_filter( 'term_link', 'tby_change_term_permalink', 10, 3 );
 
function tby_change_term_permalink( $url, $term, $taxonomy ){
	
	$taxonomy_name = 'country'; // your taxonomy name here
	$taxonomy_slug = 'country'; // the taxonomy slug can be different with the taxonomy name
 
	// exit the function if taxonomy slug is not in URL
	if ( strpos($url, $taxonomy_slug) === FALSE || $taxonomy != $taxonomy_name ) return $url;
	
	$url = str_replace('/' . $taxonomy_slug, '', $url);
	
	return $url;
}
 
 
add_action('template_redirect', 'tby_old_term_redirect');
 
function tby_old_term_redirect() {
	
	$taxonomy_name = 'country'; // your taxonomy name here
	$taxonomy_slug = 'country'; // your taxonomy slug here
	
	// exit the redirect function if taxonomy slug is not in URL
	if( strpos( $_SERVER['REQUEST_URI'], $taxonomy_slug ) === FALSE)
		return;
 
	if( ( is_category() && $taxonomy_name=='category' ) || ( is_tag() && $taxonomy_name=='post_tag' ) || is_tax( $taxonomy_name ) ) :
 
        	wp_redirect( site_url( str_replace($taxonomy_slug, '', $_SERVER['REQUEST_URI']) ), 301 );
		exit();
		
	endif;
 
}

// Code to change article url 
function add_rewrite_rules( $wp_rewrite ) 
{
    $new_rules = array(
        'article/(.+?)/?$' => 'index.php?post_type=post&name='. $wp_rewrite->preg_index(1),
    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'add_rewrite_rules');

function change_article_links($post_link, $id=0)
{
    $post = get_post($id);
    if( is_object($post) && $post->post_type == 'post'){
        return home_url('/article/'. $post->post_name.'/');
    }
    return $post_link;
}
add_filter('post_link', 'change_article_links', 1, 3);
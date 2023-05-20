<?php
/**
 * Ajax function for getting html by filters arguments 
 */
add_action( 'wp_ajax_tby_filter_author_posts', 'tby_filter_author_posts_callback' );
add_action( 'wp_ajax_nopriv_tby_filter_author_posts', 'tby_filter_author_posts_callback' );
function tby_filter_author_posts_callback(){
    $response = array();
    if ( ! wp_verify_nonce( $_POST['nonce'], 'tby-author' ) ) {
        $response['msg'] = __('Invalid request! Please Try again.');
        wp_send_json_error( $response );
    }

	$author_id 	    = ( !empty($_POST['author_id'])) ? $_POST['author_id'] : 0;
	$post_type 	    = ( !empty($_POST['post_type'])) ? $_POST['post_type'] : 'post';
	$paged 	        = ( !empty($_POST['paged'])) ? $_POST['paged'] : 2;	
    $posts_per_page = ( !empty($_POST['posts_per_page'])) ? $_POST['posts_per_page'] : 1;
	$content_type   = $post_type == 'post' ? 'article' : 'interview'; 

    ob_start();
    $html = '';
    $args = array(
        'author' => $author_id,
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'paged' => $paged
    );
    $query = new WP_Query( $args );

    if($query->have_posts()):
        while($query->have_posts()): $query->the_post();
            get_template_part('partials/default/content', $content_type);
        endwhile;
    endif;
    
    $html = ob_get_clean();

    if ( $query->max_num_pages > 1 && $paged != $query->max_num_pages){
		$paged = $paged+1;
	}else{
		$paged = '';
	}		

	$response = array(
		'html_response' => $html,
		'paged' => $paged
	);

    wp_send_json_success( $response );

}

/**
 * Ajax function for getting html by filters arguments 
 */
add_action( 'wp_ajax_tby_filter_tag_posts', 'tby_filter_tag_posts_callback' );
add_action( 'wp_ajax_nopriv_tby_filter_tag_posts', 'tby_filter_tag_posts_callback' );
function tby_filter_tag_posts_callback(){
    $response = array();
    if ( ! wp_verify_nonce( $_POST['nonce'], 'tby-tag' ) ) {
        $response['msg'] = __('Invalid request! Please Try again.');
        wp_send_json_error( $response );
    }

	$tag_id 	    = ( !empty($_POST['tag_id'])) ? $_POST['tag_id'] : '';
	$post_type 	    = ( !empty($_POST['post_type'])) ? $_POST['post_type'] : 'post';
	$paged 	        = ( !empty($_POST['paged'])) ? $_POST['paged'] : 2;	
    $posts_per_page = ( !empty($_POST['posts_per_page'])) ? $_POST['posts_per_page'] : 1;
	$content_type   = $post_type == 'post' ? 'article' : 'interview'; 

    ob_start();
    $html = '';
    $args = array(
        'tag_id' => $tag_id,
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'paged' => $paged
    );
    $query = new WP_Query( $args );

    if($query->have_posts()):
        while($query->have_posts()): $query->the_post();
            get_template_part('partials/default/content', $content_type);
        endwhile;
    endif;
    
    $html = ob_get_clean();

    if ( $query->max_num_pages > 1 && $paged != $query->max_num_pages){
		$paged = $paged+1;
	}else{
		$paged = '';
	}		

	$response = array(
		'html_response' => $html,
		'paged' => $paged
	);

    wp_send_json_success( $response );

}

// Ajax for load more upcoming partners on single Partner Page

// Ajax function for getting Press by filters arguments 
add_action('wp_ajax_tby_get_upcoming_partnered_events', 'function_get_upcoming_partnered_events_single');
add_action('wp_ajax_nopriv_tby_get_upcoming_partnered_events', 'function_get_upcoming_partnered_events_single');
function function_get_upcoming_partnered_events_single(){
	
	$response = array();
    if ( ! wp_verify_nonce( $_POST['nonce'], 'tby-pe' ) ) {
        $response['msg'] = __('Invalid request! Please Try again.');
        wp_send_json_error( $response );
    }

	$paged 	        = ( !empty($_POST['paged'])) ? $_POST['paged'] : 2;	
    $posts_per_page = ( !empty($_POST['posts_per_page'])) ? $_POST['posts_per_page'] : 1;

	$date = new DateTime();
	$today = $date->getTimestamp();
	$current_date_time = date('Y-m-d H:i:s', $today);
	$current_post = $_POST['current_post'];
	
	$args =  array(
		'post_type' => 'partnered-event',
		'posts_per_page' => $posts_per_page,
        'post__not_in' => array($current_post),
		'paged'	=> $paged,
		'meta_key' => 'pe_start_date',
		'orderby' => 'pe_start_date', 
		'post_status' => 'publish',
		'order' => 'ASC',
		'meta_query'    => array(
			'relation'      => 'AND',
			array(
				'key'       => 'pe_start_date',
				'compare'   => '>=',
				'value'     => $current_date_time,
				'type' => 'DATETIME'
			)
		)
	);
	
	$query= new WP_Query($args);
	global $post;
	ob_start();
	if( $query->have_posts() ):
		while ( $query->have_posts() ) : $query->the_post();?>
		<div class="partnered-event-block-inner">
			<?php
				$post_id = $post->ID;
				$image_id = get_post_thumbnail_id();
				$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
				$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
				$detail_page_url  	= get_permalink($post_id);
				$image              = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri() .'/assets/images/NoImageAvailable.png';

				$title 			= get_the_title();
				$start_date		= get_field( 'pe_start_date', $post_id ); 
				$start_date_f 	= date("d F Y", strtotime($start_date)); 
				$venue 			= get_field( 'pe_venue', $post_id ); 
				$external_url 	= get_field( 'pe_external_url', $post_id );
				$download_url 	= get_field( 'pe_download_file', $post_id );
			?>

			<div class="partnered-event-inner-content">
				<div class="partnered-event-list-thumb">
					<img src='<?php echo $image;?>' alt="<?php echo $image_alt;?>" />
				</div>

				<div class="partnered-event-list-content-sec">
					<div class="partnered-event-inner-content">
						<a href="<?php echo $detail_page_url;?>">
							<h3 class="partnered-event-main-title sub-title text-black font-bold"><?php echo $title;?></h3>
							
							<p class="article-subject sub-description font-bold text-red">
							<?php
								echo $start_date_f.'</br>'.$venue;
							?>
							</p>
						</a>									
					</div>
					
					<div>
					<a href="<?php echo $detail_page_url;?>" class="partner-list-learn-more">Learn More</a>
					</div>
				</div>
			</div>
		</div>
	<?php endwhile; 
	else :
		echo "No Data Found.";
	endif;
	$html = ob_get_clean();
		
	if ( $query->max_num_pages > 1 && $paged != $query->max_num_pages){
		$paged = $paged+1;
	}else{
		$paged = '';
	}		
	$response_array = array(
		'html_response' => $html,
		'paged' => $paged
	);
	wp_send_json_success($response_array);
	wp_die();
}


/**
 * Ajax function for getting html by search arguments 
 */
add_action( 'wp_ajax_tby_get_search_posts', 'tby_get_search_posts_callback' );
add_action( 'wp_ajax_nopriv_tby_get_search_posts', 'tby_get_search_posts_callback' );
function tby_get_search_posts_callback(){
    $response = array();
    if ( ! wp_verify_nonce( $_POST['nonce'], 'tby-search' ) ) {
        $response['msg'] = __('Invalid request! Please Try again.');
        wp_send_json_error( $response );
    }

	$search_text 	= ( !empty($_POST['search_text'])) ? $_POST['search_text'] : '';
	$paged 	        = ( !empty($_POST['paged'])) ? $_POST['paged'] : 2;	
    $posts_per_page = ( !empty(get_option('posts_per_page'))) ? get_option('posts_per_page') : 1;
	
    ob_start();
    $html = '';
    $args = array(
        's'	=> $search_text,
		'post_type'      => array( 'post', 'product', 'press', 'interview', 'partnered-event', 'tribe_events' ),
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
		'post_status' => 'publish',
		'order' => 'desc',
    );
    $query = new WP_Query( $args );

    if($query->have_posts()):
        while($query->have_posts()): $query->the_post();
            get_template_part('partials/default/content', 'search');
        endwhile;
    endif;
    
    $html = ob_get_clean();

    if ( $query->max_num_pages > 1 && $paged != $query->max_num_pages){
		$paged = $paged+1;
	}else{
		$paged = '';
	}		

	$response = array(
		'html_response' => $html,
		'paged' => $paged
	);

    wp_send_json_success( $response );

}
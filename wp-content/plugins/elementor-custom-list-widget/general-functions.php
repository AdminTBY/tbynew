<?php
function get_posts_years_array($post_type = 'post') {
		$desc_query = array(
			'post_type'         => array($post_type),
			'posts_per_page'	=> 1,
			'orderby'   => array(
				'date' =>'DESC',
			),
		);

		$asc_query = array(
			'post_type'         => array($post_type),
			'posts_per_page'	=> 1,
			'orderby'   => array(
				'date' =>'ASC',
			),
		);
		
		$desc_year = "";
		$asc_year  = "";
		$years = array();
		$query_year = new WP_Query( $desc_query );
		
		if ( $query_year->have_posts() ) :
			while ( $query_year->have_posts() ) : $query_year->the_post();
				$year = get_the_date('Y');
				$desc_year = $year;
			endwhile;
			wp_reset_postdata();
		endif;

		$query_year = new WP_Query( $asc_query );
		
		if ( $query_year->have_posts() ) :
			while ( $query_year->have_posts() ) : $query_year->the_post();
				$year = get_the_date('Y');
				$asc_year = $year;
			endwhile;
			wp_reset_postdata();
		endif;
		
		for ($i = $asc_year; $i <= $desc_year ; $i++) { 
			$args = array(
				'post_type'     => $post_type,
				'posts_per_page'	=> 1,
				'year'          => $i,
			);
			
			$the_posts = new WP_Query( $args );
			if(count( $the_posts->posts ) > 0){
				$years[] = $i;
			}
		}
		// Echo the years out wherever you want
		return $years;
}

// Ajax function for getting posts by filters arguments 

add_action('wp_ajax_get_posts_by_filter_options', 'function_for_get_posts_by_filters_options');
add_action('wp_ajax_nopriv_get_posts_by_filter_options', 'function_for_get_posts_by_filters_options');

function function_for_get_posts_by_filters_options(){

	// Check for nonce security      
	if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
		wp_send_json_error( 'Permission Denied.' );
	}

	$selected_country 	= ( !empty($_POST['selected_country'])) ? $_POST['selected_country'] : '';
	$selected_sector 	= ( !empty($_POST['selected_sector'])) ? $_POST['selected_sector'] : '';
	$selected_year 		= ( !empty($_POST['selected_year']) && $_POST['selected_year'] != 'all_year') ? $_POST['selected_year'] : '';
	$post_type 			= ( !empty($_POST['post_type'])) ? $_POST['post_type'] : '';
	$paged 				= ( !empty($_POST['paged'])) ? $_POST['paged'] : 1;	
	$tax_query = array(); $meta_query =  array();
	$default_posts_per_page = get_field('articles_per_page_for_filter','option');

	if (!empty($selected_country) && $selected_country != 'all_country' ) {		
		$tax_query[] = array(
			'taxonomy' => 'country', //double check your taxonomy name 
			'field'    => 'id',
			'terms'    => $selected_country,
			'include_children' => true,
			'operator' => 'IN'
		);	
	}
	if ( !empty($selected_sector) && $selected_sector != 'all_sector' ) {
		$meta_query[] = array(
			'key' => 'article_sector',
			'value' =>  $selected_sector
		);
	}
	$args = array(
        'post_type' => $post_type,
        'posts_per_page' => $default_posts_per_page,
        'tax_query' => $tax_query,
        'meta_query' => $meta_query,
        'year' => $selected_year,
		'paged'=> $paged
	);
	$query = new WP_Query($args);
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) { $query->the_post();
			$post_id = get_the_ID();
			$image_id = get_post_thumbnail_id();
			$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
			$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
			$detail_page_url  	= get_permalink($post_id);
			$image              = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : PLUGIN_DIR .'assets/images/article-default.jpg';
			$article_sector 	= (!empty(get_field('article_sector',$post_id))) ? get_field('article_sector',$post_id) : '';
			$title 				= get_the_title();
			$article_subject 	= (!empty(get_field('article_subject',$post_id))) ? get_field('article_subject',$post_id) : '';
			$article_type       = 'Article';
			$author_id			= $post->post_author;
			$author_name 		= get_the_author_meta( 'display_name' , $author_id );
			$auther_url 		= esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );


		$article_html.= "<div class='article-filtered-block-inner'>
			<a href=$detail_page_url>
				<div class='article-list-thumb'>
					<img src='$image' alt='$image_alt' />
				</div>
			</a>

			<div class='article-list-content-sec'>
				<div class='article-inner-content'>
					<p class='article-sector sub-description text-red font-bold text-uppercase'>
						$article_sector </p>
					<a href='$detail_page_url'>
						<h3 class='article-main-title title text-black font-bold'>$title</h3>
					</a>
					<p class='article-subject sub-title text-grey font-medium'>
                    $article_subject
                    </p>				

					<p class='article-author description text-grey font-medium font-italic'>
                    
                    <a href='$auther_url'>
                        $author_name
                    </a>
                    
                    </p>
				</div>							
			</div>
		</div>";
		}		
	}else{
		$article_html.= "No Posts found....";
	}

	if ( $query->max_num_pages > 1 && $paged != $query->max_num_pages){
		$paged = $paged+1;
	}else{
		$paged = '';
	}		
	$response_array = array(
		'html_response' => $article_html,
		'paged' => $paged
	);
	wp_send_json_success($response_array);
	wp_die();						
    
}

// Ajax function for getting posts by filters arguments 

add_action('wp_ajax_get_interviews_by_filter_options', 'function_for_get_interviews_by_filters_options');
add_action('wp_ajax_nopriv_get_interviews_by_filter_options', 'function_for_get_interviews_by_filters_options');

function function_for_get_interviews_by_filters_options(){
	
	// Check for nonce security      
	if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
		wp_send_json_error( 'Permission Denied.' );
	}
	
	$selected_country 	= ( !empty($_POST['selected_country'])) ? $_POST['selected_country'] : '';
	$selected_sector 	= ( !empty($_POST['selected_sector'])) ? $_POST['selected_sector'] : '';
	$selected_year 		= ( !empty($_POST['selected_year']) && $_POST['selected_year'] != 'all_year') ? $_POST['selected_year'] : '';
	$post_type 			= ( !empty($_POST['post_type'])) ? $_POST['post_type'] : '';
	$paged 				= ( !empty($_POST['paged'])) ? $_POST['paged'] : 1;	
	$tax_query = array(); $meta_query =  array();
	$default_posts_per_page = get_field('interviews_per_page_for_filter','option');

	if (!empty($selected_country) && $selected_country != 'all_country' ) {		
		$tax_query[] = array(
			'taxonomy' => 'country', //double check your taxonomy name 
			'field'    => 'id',
			'terms'    => $selected_country,
			'include_children' => true,
			'operator' => 'IN'
		);	
	}
	if ( !empty($selected_sector) && $selected_sector != 'all_sector' ) {
		$meta_query[] = array(
			'key' => 'interview_sector',
			'value' =>  $selected_sector
		);
	}
	$args = array(
        'post_type' => $post_type,
        'posts_per_page' => $default_posts_per_page,
        'tax_query' => $tax_query,
        'meta_query' => $meta_query,
        'year' => $selected_year,
		'paged'=> $paged
	);
	$query = new WP_Query($args);
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) { $query->the_post();
			$post_id = get_the_ID();
			$detail_page_url  	= get_permalink($post_id);
			$article_sector 	= (!empty(get_field('interview_sector',$post_id))) ? get_field('interview_sector',$post_id) : '';				
			$position 			= (!empty(get_field('interviewees_fields',$post_id)[0]['interviewee_position'])) ? get_field('interviewees_fields',$post_id)[0]['interviewee_position'].',' : '';
			$company_name 		= (!empty(get_field('interviewees_fields',$post_id)[0]['interviewee_company'])) ? get_field('interviewees_fields',$post_id)[0]['interviewee_company'] : '';

			$interview_type     = (!empty(get_field('interview_article_type',$post_id))) ? get_field('interview_article_type',$post_id) : 'Interview';
				if(strtolower($interview_type) == 'b2b'){
					$image =  ( !empty(get_field('interview_b2b_image','option')['sizes']['medium_large']) ) ? get_field('interview_b2b_image','option')['sizes']['medium_large'] : get_stylesheet_directory_uri().'/assets/images/b2b.png';        
					$image_alt = 'b2b';
					$header_img =  ( !empty(get_field('interview_b2b_header_image','option')['sizes']['medium_large']) ) ? get_field('interview_b2b_header_image','option')['sizes']['medium_large'] : get_stylesheet_directory_uri().'/assets/images/b2b-header.png';	
					$title = get_field('interviewees_fields',$post_id)[0]['interviewee_name'].' and '.get_field('interviewees_fields',$post_id)[1]['interviewee_name'];				
				}elseif (strtolower($interview_type) == 'forum') {
					$image = ( !empty(get_field('interview_forum_image','option')['sizes']['medium_large']) ) ? get_field('interview_forum_image','option')['sizes']['medium_large'] : get_stylesheet_directory_uri().'/assets/images/forum.png';
					$image_alt = 'forum';
					$header_img =  ( !empty(get_field('interview_forum_header_image','option')['sizes']['medium_large']) ) ? get_field('interview_forum_header_image','option')['sizes']['medium_large'] : get_stylesheet_directory_uri().'/assets/images/forum-header.png';	
					$title = get_the_title();
				}else{
					$image_id 			= get_post_thumbnail_id();
					$image_alt 			= get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
					$image_alt 			= (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
					$detail_page_url  	= get_permalink($post_id);
					$image 				= get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri() .'/assets/images/interview-default.png';
					$title 				= get_the_title();
					$no_interview_img_css = get_the_post_thumbnail_url( $post_id,'large' ) ? '' : "style = 'background-color: #EBEBEB;'";
				}
		$article_html.= "<div class='article-filtered-block-inner'>";
		
			if(strtolower($interview_type) == 'b2b' || strtolower($interview_type) == 'forum'){
				$converted_interview_type = strtolower($interview_type);
				$article_html.="
				<a href = $detail_page_url>
					<div class='article-list-thumb $converted_interview_type' style='background-image: url($header_img) , url($image);'>															
					</div>
				</a>";
			 }else{
				$article_html.="<a href = $detail_page_url>
					<div class='article-list-thumb'>
						<img src='$image' alt='$image_alt' $no_interview_img_css/>
					</div>
				</a>";
			 }			

			$article_html.="<div class='article-list-content-sec'>
				<div class='article-inner-content'>
					<p class='article-sector sub-description text-red font-bold text-uppercase'>
						$article_sector </p>
					<a href='$detail_page_url'>
						<h3 class='article-main-title sub-title text-black font-bold'>$title</h3>
					</a>
					<p class='interview-type'>$interview_type</p>			
					<p class='article-author description text-grey font-medium'>";
						if(strtolower($interview_type) == 'b2b' || strtolower($interview_type) == 'forum'){
							$countries = tby_display_categories($post_id,'country');
							if(!empty($countries)){                                
								$article_html.="$countries";
							}
						}else{
							$article_html.= "$position $company_name";
						}						
					$article_html.= "</p>
				</div>							
			</div>
		</div>";
		}		
	}else{
		$article_html.= "No Posts found....";
	}

	if ( $query->max_num_pages > 1 && $paged != $query->max_num_pages){
		$paged = $paged+1;
	}else{
		$paged = '';
	}		
	$response_array = array(
		'html_response' => $article_html,
		'paged' => $paged
	);
	wp_send_json_success($response_array);
	wp_die();						
    
}

// Ajax function for getting Press by filters arguments 
add_action('wp_ajax_get_press_by_filter_options', 'function_for_get_press_by_filters_options');
add_action('wp_ajax_nopriv_get_press_by_filter_options', 'function_for_get_press_by_filters_options');
function function_for_get_press_by_filters_options(){
	$selected_country 	= ( !empty($_POST['selected_country'])) ? $_POST['selected_country'] : '';
	$paged 				= ( !empty($_POST['paged'])) ? $_POST['paged'] : 1;
	$from_updated_date 		= ( !empty($_POST['from_updated_date'])) ? wp_date('F j, Y', strtotime( str_replace('/', '-', $_POST['from_updated_date']))) : '' ;
	$till_updated_date 		= ( !empty($_POST['till_updated_date'])) ? wp_date('F j, Y', strtotime(str_replace('/', '-', $_POST['till_updated_date']))).'23:59:59' : '' ;
	$tax_query = array(); 
	$default_posts_per_page = get_field('press_per_page_for_filter','option');
	
	if (!empty($selected_country) && $selected_country != 'all_country' ) {		
		$tax_query[] = array(
			'taxonomy' => 'country', //double check your taxonomy name 
			'field'    => 'id',
			'terms'    => $selected_country,
			'include_children' => true,
			'operator' => 'IN'
		);	
	}
	$args = array(
        'post_type' => 'press',
        'posts_per_page' => $default_posts_per_page,
		'meta_key' => 'award_date',
		'orderby' => 'meta_value',
		'order' => 'DESC',
        'tax_query' => $tax_query,
        'date_query' => array(
        array(
            'after'     => $from_updated_date,
            'before'    => $till_updated_date,
            'inclusive' => true,
        ),
    ),
		'paged'=> $paged
	);
	$query = new WP_Query($args);
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) { $query->the_post();
			$post_id = get_the_ID();
			$image_id = get_post_thumbnail_id();
			$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
			$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
			$detail_page_url  	= get_permalink($post_id);
			$image              = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : PLUGIN_DIR .'assets/images/NoImageAvailable.png';
			$title 				= get_the_title();
			$press_type 	= (!empty(get_field('media_news_type',$post_id))) ? get_field('media_news_type',$post_id) : '';			

		$press_html.= "<div class='press-filtered-block-inner'>
			<a href='$detail_page_url'>
				<div class='press-list-thumb'>
					<img src='$image' alt='$image_alt' />
				</div>
			</a>
			<div class='press-list-content-sec'>
				<div class='press-inner-content'>
				<p class='interviewers-country-sect-details sub-description text-red font-bold text-uppercase'>$press_type
                    </p>
					<a href='$detail_page_url'>
						<h3 class='press-main-title sub-title text-black font-bold'>$title</h3>
					</a>							
				</div>							
			</div>
		</div>";
		}		
	}else{
		$press_html.= "No Posts found....";
	}

	if ( $query->max_num_pages > 1 && $paged != $query->max_num_pages){
		$paged = $paged+1;
	}else{
		$paged = '';
	}		
	$response_array = array(
		'html_response' => $press_html,
		'paged' => $paged
	);
	wp_send_json_success($response_array);
	wp_die();
}

// Ajax function for getting upcoming events by filters arguments 
add_action('wp_ajax_get_upcoming_events_by_filter_options', 'function_for_get_upcoming_events_by_filter_options');
add_action('wp_ajax_nopriv_get_upcoming_events_by_filter_options', 'function_for_get_upcoming_events_by_filter_options');

function function_for_get_upcoming_events_by_filter_options(){

	// Check for nonce security      
	if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
		wp_send_json_error( 'Permission Denied.' );
	}

	$selected_country 	= ( !empty($_POST['selected_country'])) ? $_POST['selected_country'] : '';
	$selected_cat 		= ( !empty($_POST['selected_cat'])) ? $_POST['selected_cat'] : '';
	$from_date 			= ( !empty($_POST['from_date'])) ? date("Y-m-d", strtotime(str_replace('/', '-', $_POST['from_date']))) : '';
	$to_date 			= ( !empty($_POST['to_date'])) ? date("Y-m-d", strtotime(str_replace('/', '-', $_POST['to_date']))) : '';
	$post_type 			= 'tribe_events';
	$paged 				= ( !empty($_POST['paged'])) ? $_POST['paged'] : 1;	
	$tax_query = array(); $meta_query =  array();
	$date = new DateTime();
	$today = $date->getTimestamp();
	$current_date_time = date('Y-m-d H:i:s', $today);

	// Checking selected country for taxonomy query 
	if (!empty($selected_country) && $selected_country != 'all_country' ) {		
		$tax_query[] = array(
			'taxonomy' => 'country', //double check your taxonomy name 
			'field'    => 'id',
			'terms'    => $selected_country,
			'include_children' => true,
			'operator' => 'IN'
		);	
	}
	// Checking selected category for taxonomy query 
	if (!empty($selected_cat) && $selected_cat != 'all_categories' ) {		
		$tax_query[] = array(
			'taxonomy' => 'tribe_events_cat', //double check your taxonomy name 
			'field'    => 'id',
			'terms'    => $selected_cat,
			'include_children' => true,
			'operator' => 'IN'
		);	
	}


	//condition to check upcomming or past event selected  1 for upcoming 2 for past
	if($_POST['event_check']==1){
		$default_posts_per_page = get_field('upcoming_events_per_page','option');
		$event_order = 'ASC';
		//upcomming starts
		if(!empty($from_date) && !empty($to_date)){
			$meta_query = array(
				'relation'      => 'AND',
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $from_date,
					'compare'   => '>=',
	                'type' => 'DATE'
				),
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $to_date,
					'compare'   => '<=',
	                'type' => 'DATE'
				),
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $current_date_time,
					'compare'   => '>=',
	                'type' => 'DATETIME'
				)
			);
		}elseif (!empty($from_date)) {
			$meta_query = array(
				'relation'      => 'AND',
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $from_date,
					'compare'   => '>=',
	                'type' => 'DATE'
				),
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $current_date_time,
					'compare'   => '>=',
	                'type' => 'DATETIME'
				)
			);
		}elseif (!empty($to_date)){ 
			$meta_query = array(
				'relation'      => 'AND',
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $to_date,
					'compare'   => '<=',
	                'type' => 'DATE'
				),
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $current_date_time,
					'compare'   => '>=',
	                'type' => 'DATETIME'
				)
			);
		}else{
			$meta_query = array(
				'relation'      => 'AND',
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $current_date_time,
					'compare'   => '>=',
	                'type' => 'DATETIME'
				)
			);
		}
		//upcomming ends

	}else {
		//past events check starts
		$default_posts_per_page = get_field('past_events_per_page','option');
		$event_order = 'DESC';

		if(!empty($from_date) && !empty($to_date)){
			$meta_query = array(
				'relation'      => 'AND',
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $from_date,
					'compare'   => '>=',
	                'type' => 'DATE'
				),
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $to_date,
					'compare'   => '<=',
	                'type' => 'DATE'
				),
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $current_date_time,
					'compare'   => '<=',
	                'type' => 'DATETIME'
				)
			);
		}elseif (!empty($from_date)) {
			$meta_query = array(
				'relation'      => 'AND',
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $from_date,
					'compare'   => '>=',
	                'type' => 'DATE'
				),
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $current_date_time,
					'compare'   => '<=',
	                'type' => 'DATETIME'
				)
			);
		}elseif (!empty($to_date)){ 
			$meta_query = array(
				'relation'      => 'AND',
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $to_date,
					'compare'   => '<=',
	                'type' => 'DATE'
				),
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $current_date_time,
					'compare'   => '<=',
	                'type' => 'DATETIME'
				)
			);
		}else{
			$meta_query = array(
				'relation'      => 'AND',
				array(
					'key'       => '_EventStartDate',							
					'value'		=> $current_date_time,
					'compare'   => '<=',
	                'type' => 'DATETIME'
				)
			);
		}
		//past events check ends
	}
	$args =  array(
		'post_type' => 'tribe_events',
		'posts_per_page' => $default_posts_per_page,
		'meta_key' => '_EventStartDate',
		'orderby' => '_EventStartDate', 
		'post_status' => 'publish',
		'order' => $event_order,
		'paged' => $paged,
		'tax_query' => $tax_query,
		'meta_query' => $meta_query, 

	);

	$query= new WP_Query($args);
	
	global $post;

	if( $query->have_posts() ):
		while ( $query->have_posts() ) : $query->the_post();
		$html.= "<div class='article-block-inner'>";

		$post_id = $post->ID;
		$image_id = get_post_thumbnail_id();
		$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
		$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
		$detail_page_url  	= get_permalink($post_id);
		$image              = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : PLUGIN_DIR .'assets/images/NoImageAvailable.png';
		$title 				= get_the_title();
		$upcoming_post_content = wp_trim_words( get_the_content(), 25, '...' );						
		$city_upcoming_post = get_post_meta( $post_id, '_EventVenueID', true  ); 
		$postevenykey = $city_upcoming_post;
		$getevent_venue_city    = get_post_meta( $postevenykey, '_VenueCity', true  ) ? get_post_meta( $postevenykey, '_VenueCity', true  ).", " : '';
		$getevent_venue_country = get_post_meta( $postevenykey, '_VenueCountry', true  );
		// Start Date event Converter
		$getfull_startend_date  = get_post_meta( $post->ID, '_EventStartDate', true  );
		$format = 'Y-m-d H:i:s';
		$date = DateTime::createFromFormat($format, $getfull_startend_date);
		$get_start_date_time = $date->format('H:i') . "\n";
		$get_event_timezone = get_post_meta( $post->ID, '_EventTimezone', true  );
		$convert_start_date_object = new DateTime($getfull_startend_date);
		$start_date_with_remove_time = $convert_start_date_object->format('Y-m-d');
		$convert_start_date_with_name = date("M d, Y", strtotime($start_date_with_remove_time));
		$get_only_start_day_name = date('l', strtotime($start_date_with_remove_time));
		// End Date Event Converter
		$getfull_eventend_date  = get_post_meta( $post->ID, '_EventEndDate', true  );
		$date_end = DateTime::createFromFormat($format, $getfull_eventend_date);
		$get_end_date_time = $date_end->format('H:i') . "\n";
		$event_invitation_type = get_post_meta( $post_id, 'events_invitation_type', true  ) ? get_post_meta( $post_id, 'events_invitation_type', true  ) : "";

		if($_POST['event_check']==1){
		$html.= "<div class='artcle-inner-content'>
			<div class='article-list-thumb'>
				<img src='$image' alt='$image_alt' />";
				$terms = get_the_terms( $post->ID , 'tribe_events_cat' );
				if(!empty($terms)){
					$html.="<span class='event-category'>";
				
				foreach ( $terms as $term ) {
					$html.= "<a href = $detail_page_url><span class='sub-description text-white text-uppercase'>$term->name</span></a>";
				}
				$html.="</span>";
				}
				
			$html.="</div>
			<div class='article-list-content-sec'>
				<div class='article-inner-content'>
					<a href='$detail_page_url'>
						<h3 class='article-main-title title text-black font-bold'>$title</h3>
						<p class='article-subject sub-title font-bold text-red'>
						$get_only_start_day_name / $convert_start_date_with_name $get_start_date_time - $get_end_date_time $get_event_timezone </br>$getevent_venue_city $getevent_venue_country
						</p>

						<p class='upcoming-post-content description text-grey font-medium'>
							$upcoming_post_content
						</p>";
						if($event_invitation_type):
                            $html.= "<p class='upcoming-post-content description text-grey font-medium'>
                                 $event_invitation_type
                            </p>";
                        endif;
					$html.="</a>				
				</div>
			</div>
		</div>
	</div>";
	}else{
		$html.= "<div class='artcle-inner-content'>
			<div class='article-list-thumb'>
				<img src='$image' alt='$image_alt' />";
				$terms = get_the_terms( $post->ID , 'tribe_events_cat' );
				if(!empty($terms)){
					$html.="<span class='event-category'>";
				
				foreach ( $terms as $term ) {
					$html.= "<a href = $detail_page_url><span class='sub-description text-white text-uppercase'>$term->name</span></a>";
				}
				$html.="</span>";
				}
				
			$html.="</div>
			<div class='article-list-content-sec'>
				<div class='article-inner-content'>
					<a href='$detail_page_url'>
						<h3 class='article-main-title title text-black font-bold'>$title</h3>
						<p class='article-subject sub-title font-bold text-red'>
						$get_only_start_day_name / $convert_start_date_with_name </br>$get_start_date_time - $get_end_date_time $get_event_timezone </br>$getevent_venue_city $getevent_venue_country
						</p>						
						<p class='upcoming-post-content description text-grey font-medium'>
							$upcoming_post_content
						</p>";
						if($event_invitation_type):
                            $html.= "<p class='upcoming-post-content description text-grey font-medium'>
                                 $event_invitation_type
                            </p>";
                        endif;
					$html.="</a>
					<a class='article-view-more-btn' href='$detail_page_url'>View More</a>
				</div>
			</div>
		</div>
	</div>";
	}
	endwhile; 
	else :
		$html.= "Stay tuned for more upcoming events soon.";
	endif;
	
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

// Ajax function for getting Press by filters arguments 
add_action('wp_ajax_get_upcoming_partnered_events', 'function_get_upcoming_partnered_events');
add_action('wp_ajax_nopriv_get_upcoming_partnered_events', 'function_get_upcoming_partnered_events');
function function_get_upcoming_partnered_events(){
	
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
	
	$args =  array(
		'post_type' => 'partnered-event',
		'posts_per_page' => $posts_per_page,
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
				$image              = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : PLUGIN_DIR .'assets/images/NoImageAvailable.png';

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
		echo "Stay tuned for more upcoming events soon.";
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
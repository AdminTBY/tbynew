<?php get_header(); ?>

<div class="header-stripe">
    <h1 class="section-title font-bold text-center text-white"><?php echo get_queried_object()->name; ?></h1>
</div>
<?php  
    // Sponsored article start 
    $exclude_id = array();
    $taxonomy_id = get_queried_object()->term_id;
    $taxonomy_slug = get_queried_object()->slug;
    $sponsored_article_args =  array(
        'post_type' => 'post',
        'numberposts' => 1,
        'order' =>'DESC',   
        'meta_query' => array(
            array(
                'key'   => 'article_sponsor',
                'compare' => '!=',
                'value' => '',
            )
        ),
        'tax_query' => array(
            array(
                'taxonomy' => 'country',
                'field' => 'term_id',
                'terms' => $taxonomy_id,
            )
        )           
    );

    $sponsored_articles = get_posts($sponsored_article_args);
    if ( $sponsored_articles ) {
        foreach ( $sponsored_articles as $post ) : 
            setup_postdata( $post );
            $post_id = $post->ID;
            $exclude_id[] = $post_id;
            $image_id = get_post_thumbnail_id();
            $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
            $image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
            $detail_page_url    = get_permalink($post_id);                      
            $image              = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : PLUGIN_DIR .'assets/images/article-default.jpg';
            $article_sector     = (!empty(get_field('article_sector',$post_id))) ? get_field('article_sector',$post_id) : '';
            $title              = get_the_title($post_id);
            $article_subject    = (!empty(get_field('article_sponsor',$post_id))) ? get_the_title(get_field('article_sponsor',$post_id)) : '';
            $article_type       = 'Article';
            $author_id          = $post->post_author;
            $author_name        = get_the_author_meta( 'display_name' , $author_id ); 
            
            ob_start();
            ?>
<div class="article-block-inner swiper-slide sponsored-article">
    <div class="artcle-inner-content">
        <a href="<?php echo $detail_page_url;?>">
            <div class="article-list-thumb">
                <img src='<?php echo $image;?>' alt="<?php echo $image_alt;?>" />
            </div>
        </a>

        <div class="article-list-content-sec">
            <div class="article-inner-content">
                <p class="article-sector sub-description text-red font-bold text-uppercase">
                    <?php echo $article_sector;?></p>
                <a href="<?php echo $detail_page_url;?>">
                    <h3 class="article-main-title title text-black font-bold"><?php echo $title;?></h3>
                </a>
                <p class="article-subject sub-title text-grey font-medium">
                    <?php echo $article_subject;?>
                </p>
                <p class="sponsored-content">SPONSORED CONTENT<br> <?php echo $article_subject;?></p>
                <p class="article-author description text-grey font-medium">
                    <a
                        href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo $author_name;?></a>
                </p>
            </div>
            <a class="article-view-more-btn" href="<?php echo $detail_page_url;?>">View More</a>
        </div>
    </div>
</div>
<?php
        endforeach;
        $sponsored_article_html = ob_get_contents();
        ob_end_clean();         
        wp_reset_postdata();
    }       

    // Sponsored article END....
?>
<div class="latest-article-wrapper pad100">
    <div class="container">
        <div class="article-main-title-with-border">
            <h2 class="section-title text-black font-bold border-line m-t-0">Latest Articles</h2>
        </div>
        <div class="latest-article-slider">
            <div class="article-block-main-outer swiper-container">
                <div class="swiper-wrapper">
                    <?php

					$args =  array(
						'post_type' => 'post',
						'posts_per_page' => 5,
                        'post__not_in'		=> $exclude_id,
						'tax_query' => array(
				            array(
				                'taxonomy' => 'country',
				                'field' => 'term_id',
				                'terms' => $taxonomy_id,
				            )
				        )
					);
					
					$query= new WP_Query($args);
					global $post;

					if( $query->have_posts() ):
                        echo $sponsored_article_html;
						while ( $query->have_posts() ) : $query->the_post();?>
                    <div class="article-block-inner swiper-slide">
                        <?php
							$post_id = $post->ID;
							$image_id = get_post_thumbnail_id();
							$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
							$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
							$detail_page_url  	= get_permalink($post_id);
							$image 				= get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri().''."/assets/images/article-default.jpg";
							$article_sector 	= (!empty(get_field('article_sector',$post_id))) ? get_field('article_sector',$post_id) : '';
							$title 				= get_the_title();
							$article_subject 	= (!empty(get_field('article_subject',$post_id))) ? get_field('article_subject',$post_id) : '';
							$author_id			= $post->post_author;
							$author_name 		= get_the_author_meta( 'display_name' , $author_id );
                            $article_type       = 'Article';
							
						?>
                        <div class="artcle-inner-content">
                            <div class="article-list-thumb">
                                <img src='<?php echo $image;?>' alt="<?php echo $image_alt;?>" />
                            </div>

                            <div class="article-list-content-sec">
                                <div class="article-inner-content">
                                    <p class="article-sector sub-description text-red font-bold text-uppercase">
                                        <?php echo $article_sector;?></p>
                                    <a href="<?php echo $detail_page_url;?>">
                                        <h3 class="article-main-title title text-black font-bold"><?php echo $title;?>
                                        </h3>
                                    </a>
                                    <p class="article-subject sub-title text-grey font-medium">
                                        <?php echo $article_subject;?>
                                    </p>
                                    <p class="article-author description text-grey ont-medium">
                                        <a
                                            href="<?php echo get_author_posts_url($author_id); ?>"><?php echo $author_name;?></a>
                                    </p>
                                </div>
                                <a class="article-view-more-btn" href="<?php echo $detail_page_url;?>">View More</a>
                            </div>

                        </div>
                    </div>
                    <?php endwhile; 
					else :
						echo "No Content Found!!!";
					endif;
				
				?>
                </div>

            </div>
            <?php if( $query->have_posts() ):?>
            <div class="swiper-button-next">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow-left.svg" alt="" />
            </div>
            <div class="swiper-button-prev">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow-left.svg" alt="" />
            </div>
            <div class="swiper-pagination"></div>
            <?php endif;?>
        </div>
        <div class="view_more_btn_class">
            <?php
        $view_more_article_url      = get_field('view_all_articles_url','option');
        $link_url = (!empty ($view_more_article_url['url']))? $view_more_article_url['url'] : site_url('/articles');;
        $link_title = $view_more_article_url['title'];
        $link_target = $view_more_article_url['target'] ? $view_more_article_url['target'] : '_self';?>
            <a href="<?php echo $link_url."?country=$taxonomy_slug/#articles_filter";?>" target=<?php echo $link_target;?>>
                <button class="load_more_articles_btn"><?php echo $link_title;?></button>
            </a>
        </div>
    </div>
</div>


<div class="country-interview-section pad100">
    <div class="container">
        <div class="interviews-main-title-with-border">
            <h2 class="section-title text-black font-bold border-line m-t-0">Featured Interviews</h2>
        </div>
        <div class="interviews-block-main-outer">
            <?php		
	   	 $interview_args =  array(
            'post_type' => 'interview',
            'posts_per_page' => 5,
			'meta_query'	=> array(				
				array(
					'key'	  	=> 'featured_interview',
					'value'	  	=> '1',
				),
			),
			'tax_query' => array(
	            array(
	                'taxonomy' => 'country',
	                'field' => 'term_id',
	                'terms' => $taxonomy_id,
	            )
	        ),
        );
        
		$query= new WP_Query($interview_args);

		if( $query->have_posts() ){
			$i =0;
            while ( $query->have_posts() ) { $query->the_post();
			$post_id = $post->ID;
			
				$interviewiers_bio = get_the_excerpt($post_id);
				$view_more_url	   = get_permalink($post_id);
				$interview_sector 	= (!empty(get_field('interview_sector',$post_id))) ? get_field('interview_sector',$post_id) : '';

				$all_countries 		= wp_get_post_terms( $post_id, 'country', array() );
				$child_countries 	= array();
				foreach ($all_countries as $key => $value) {
					if($all_countries[$key]->parent != 0){
						array_push($child_countries,$all_countries[$key]->name);
					}
				}
				$country = implode(', ', $child_countries);
				$country_and_sector_details = $country.' - '.$interview_sector;				
				$position 			= (!empty(get_field('interviewees_fields',$post_id)[0]['interviewee_position'])) ? get_field('interviewees_fields',$post_id)[0]['interviewee_position'] : '';
				$company_name 		= (!empty(get_field('interviewees_fields',$post_id)[0]['interviewee_company'])) ? get_field('interviewees_fields',$post_id)[0]['interviewee_company'] : '';
				$position_and_comp_name = $position.', '.$company_name;
                
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
                    $image 				= get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri() .'/assets/images/interview-default.png';
                    $title = get_the_title();
                    $no_interview_img_css = get_the_post_thumbnail_url( $post_id,'large' ) ? '' : "style = 'background-color: #EBEBEB;'";
                }
                ?>

            <?php if($i == 0){?>
            <div class="interviews-block-inner first-interview-sec <?php echo "custom".strtolower($interview_type);?>">

                <?php 
                    if(strtolower($interview_type) == 'b2b' || strtolower($interview_type) == 'forum'){?>
                <a class="interviewers-list-thumb <?php echo strtolower($interview_type);?>"
                    href="<?php echo $view_more_url;?>"
                    style="background-image: url(<?php echo $header_img;?>) , url(<?php echo $image;?>);">
                </a>
                <?php   }else{?>
                <div class="interviewers-list-thumb">
                    <a href="<?php echo $view_more_url;?>">
                        <img src="<?php echo $image;?>" alt="<?php echo $image_alt;?>" <?php echo $no_interview_img_css;?>>
                    </a>
                </div>
                <?php   } ?>

                <div class="interviews-list-content-sec list-content-sec-first-sec">
                    <div class="interviews-list-inner">
                        <p class="interviewers-country-sect-details sub-description text-red font-bold text-uppercase">
                            <?php echo $country_and_sector_details;?></p>
                        <a href="<?php echo $view_more_url;?>">
                            <h3 class="interview-main-title title text-black font-bold"><?php echo $title;?></h3>
                        </a>
                        <p class="interview-type"><?php echo $interview_type;?></p>
                        <p class="interviewers-pos-com-name description text-grey font-medium">
                            <?php 
                                if(strtolower($interview_type) == 'b2b' || strtolower($interview_type) == 'forum'){
                                    $countries = tby_display_categories($post_id,'country');
                                    if(!empty($countries)){                                
                                        echo $countries;
                                    }
                                }else{
                                    echo $position_and_comp_name;
                                }
                            ?>
                        </p>
                        <p class="bio-details description text-grey font-medium"><?php echo $interviewiers_bio;?></p>
                    </div>
                    <a class="interview-view-more-btn" href="<?php echo $view_more_url;?>">View More</a>
                </div>
            </div>
            <?php }else{?>
            <div class="interviews-block-inner <?php echo "custom".strtolower($interview_type);?>">
                <div class="interviews-inner">
                    <?php 
                    if(strtolower($interview_type) == 'b2b' || strtolower($interview_type) == 'forum'){?>
                    <a href="<?php echo $view_more_url;?>">
                        <div class="interviewers-list-thumb <?php echo strtolower($interview_type);?>"
                            style="background-image: url(<?php echo $header_img;?>) , url(<?php echo $image;?>);">
                        </div>
                    </a>
                    <?php }else{?>
                    <a href="<?php echo $view_more_url;?>">
                        <div class="interviewers-list-thumb">
                            <img src="<?php echo $image;?>" alt="<?php echo $image_alt;?>" <?php echo $no_interview_img_css;?>>
                        </div>
                    </a>
                    <?php } ?>

                    <div class="interviews-list-content-sec">
                        <p class="interviewers-country-sect-details sub-description text-red font-bold text-uppercase">
                            <?php echo $country_and_sector_details;?></p>
                        <a href="<?php echo $view_more_url;?>">
                            <h3 class="interview-main-title title text-black font-bold"><?php echo $title;?></h3>
                        </a>
                        <p class="interview-type"><?php echo $interview_type;?></p>
                        <p class="interviewers-pos-com-name sub-description text-grey font-medium">
                            <?php 
                                if(strtolower($interview_type) == 'b2b' || strtolower($interview_type) == 'forum'){
                                    $countries = tby_display_categories($post_id,'country');
                                    if(!empty($countries)){                                
                                        echo $countries;
                                    }
                                }else{
                                    echo $position_and_comp_name;
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <?php }
	           $i++;
            } 

        }else{
        	
            echo 'No Content Found!!!';
        }
		?>
        </div>
        <div class="view_more_btn_class"><a href="<?php echo get_home_url(); ?>/interviews/?country=<?php echo $taxonomy_slug; ?>/#interview_filter"><button
                    class="load_more_articles_btn">VIEW ALL INTERVIEWS</button></a></div>
    </div>
</div>


<div class="publication-wrapper pad100">
    <div class="container">
        <div class="publication-custom-block-main-title">
            <h2 class="section-title text-black font-bold border-line m-t-0">Browse Our Publications</h2>
        </div>
        <div class="country-publication-block-wrapper">
            <div class="publication-block-main-outer">
                <?php 
	    	$args =  array(
	            'post_type' => 'product',
	            'posts_per_page' => 8,
	            'tax_query' => array(
		            'relation' => 'AND',
                    array(
		                'taxonomy' => 'country',
		                'field' => 'term_id',
		                'terms' => $taxonomy_id,
                    ),
                    array(
                        'taxonomy'  => 'product_visibility',
                        'terms'     => array( 'exclude-from-catalog' ),
                        'field'     => 'name',
                        'operator'  => 'NOT IN',
                    )
		        )
	        );

			$query= new WP_Query($args);

	        $default = array();
			global $post;
            $remaining_publications = 8 - $query->found_posts;
            $exclude_products = array();
	        if( $query->have_posts() ):
	            while ( $query->have_posts() ) : $query->the_post();			
				
					$post_id = $post->ID;
                    $exclude_products[] = $post_id; 
		            $image_id = get_post_thumbnail_id();
					$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
					$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
					$product_page_url  	= get_permalink($post_id);
					$image 				= get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri().''."/assets/images/NoImageAvailable.png" ;
					$title			    = get_the_title();?>


                <div class="publication-block-inner">
                    <a href="<?php echo $product_page_url;?>">
                        <div class="publication-list-thumb">
                            <img src="<?php echo $image;?>" alt="<?php echo $image_alt;?>">
                        </div>
                    </a>
                    <div class="publication-title-outer">
                        <a href="<?php echo $product_page_url;?>">
                            <h3 class="publication-title description text-black font-medium text-center">
                                <?php echo $title;?></h3>
                        </a>
                    </div>
                </div>


                <?php endwhile; 
	        endif;	

            if($remaining_publications > 0){
                $rem_publ_query =  array(
                    'post_type' => 'product',
                    'posts_per_page' => $remaining_publications,
                    'orderby' => 'publish_date',
                    'order'   => 'DESC',
                    'post__not_in'=> $exclude_products,
                    'tax_query'   => array( array(
                        'taxonomy'  => 'product_visibility',
                        'terms'     => array( 'exclude-from-catalog' ),
                        'field'     => 'name',
                        'operator'  => 'NOT IN',
                    ) )
                );
                $rem_publication_data = new WP_Query($rem_publ_query);
                if( $rem_publication_data->have_posts() ):
                while ( $rem_publication_data->have_posts() ) : $rem_publication_data->the_post();			
				
					$post_id = $post->ID;
		            $image_id = get_post_thumbnail_id();
					$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
					$image_alt = (!empty($image_alt)) ? $image_alt : get_the_title($image_id);
					$product_page_url = get_permalink($post_id);
					$image = get_the_post_thumbnail_url( $post_id,'large' ) ? get_the_post_thumbnail_url( $post_id,'large' ) : get_stylesheet_directory_uri().''."/assets/images/NoImageAvailable.png" ;
					$title = get_the_title();?>


                <div class="publication-block-inner">
                    <a href="<?php echo $product_page_url;?>">
                        <div class="publication-list-thumb">
                            <img src="<?php echo $image;?>" alt="<?php echo $image_alt;?>">
                        </div>
                    </a>
                    <div class="publication-title-outer">
                        <a href="<?php echo $product_page_url;?>">
                            <h3 class="publication-title description text-black font-medium text-center">
                                <?php echo $title;?></h3>
                        </a>
                    </div>
                </div>
                <?php endwhile;
                else :
                    echo 'No Content Found!!!';
                endif;
            }?>
            </div>
            <div class="shop_product_image">
                <div class="Shop_now_btn"><a href="<?php echo get_home_url(); ?>/shop/"><button
                            class="load_more_articles_btn">SHOP NOW</button></a></div>
                <div class="produc_image_class">
                    <?php echo do_shortcode("[ads_section_layout layout='vertical']");?>
                </div>
            </div>
        </div>
    </div>
</div>


</div>
<?php get_footer(); ?>
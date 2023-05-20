jQuery(document).ready(function() {

    // article countries js start 
    jQuery(".interview_main_for_ajax .article-filter-countries-ul .dropdown-icon").click(function() {
            var link = jQuery(this);
            var closest_ul = link.closest("ul");
            var parallel_active_links = closest_ul.find(".active")
            var closest_li = link.closest("li");
            var link_status = closest_li.hasClass("active");
            var count = 0;

            closest_ul.find("ul").slideUp(function() {
                    if (++count == closest_ul.find("ul").length)
                            parallel_active_links.removeClass("active");
            });

            if (!link_status) {
                    closest_li.children("ul").slideDown();
                    closest_li.addClass("active");
            }
    })
    // article countries js END..     

    // article sector js start 
    jQuery(".interview_main_for_ajax .article-filter-sector-ul .dropdown-icon").click(function() {
        var link = jQuery(this);
        var closest_ul = link.closest("ul");
        var parallel_active_links = closest_ul.find(".active")
        var closest_li = link.closest("li");
        var link_status = closest_li.hasClass("active");
        var count = 0;

        closest_ul.find("ul").slideUp(function() {
                if (++count == closest_ul.find("ul").length)
                        parallel_active_links.removeClass("active");
        });

        if (!link_status) {
                closest_li.children("ul").slideDown();
                closest_li.addClass("active");
        }
    })
    // article sector js END..

    // article Year js start 
    jQuery(".interview_main_for_ajax .article-filter-year-ul .dropdown-icon").click(function() {
        var link = jQuery(this);
        var closest_ul = link.closest("ul");
        var parallel_active_links = closest_ul.find(".active")
        var closest_li = link.closest("li");
        var link_status = closest_li.hasClass("active");
        var count = 0;

        closest_ul.find("ul").slideUp(function() {
                if (++count == closest_ul.find("ul").length)
                        parallel_active_links.removeClass("active");
        });

        if (!link_status) {
                closest_li.children("ul").slideDown();
                closest_li.addClass("active");
        }
    })  
    // article Year js END..

    jQuery('.interview_main_for_ajax .article-filter-countries-ul .call_ajax_for_filter').click(function(){
        jQuery('.interview_main_for_ajax .article-filter-countries-ul .call_ajax_for_filter').removeClass('selected_country_for_article');
        jQuery('.interviews_paged_track').val('');
        
        if(typeof jQuery(this).attr('value') != 'undefined'){
                jQuery(this).addClass('selected_country_for_article');
        }         
        
        var selected_country = jQuery(this).attr('value');
        var selected_sector  = jQuery('.interview_main_for_ajax .article-filter-sector-ul .selected_sector_for_article').attr('value');
        var selected_year    = jQuery('.interview_main_for_ajax .article-filter-year-ul .selected_year_for_article').attr('value');

        get_interviews_data_by_filter(selected_country,selected_sector,selected_year,'interview');
    });

    jQuery('.interview_main_for_ajax .article-filter-sector-ul .call_ajax_for_filter').click(function(){
        jQuery('.interview_main_for_ajax .article-filter-sector-ul .call_ajax_for_filter').removeClass('selected_sector_for_article');
        jQuery('.interviews_paged_track').val('');

        if(typeof jQuery(this).attr('value') != 'undefined'){
                jQuery(this).addClass('selected_sector_for_article');
        }
        
        var selected_sector = jQuery(this).attr('value');
        var selected_country  = jQuery('.interview_main_for_ajax .article-filter-countries-ul .selected_country_for_article').attr('value');
        var selected_year    = jQuery('.interview_main_for_ajax .article-filter-year-ul .selected_year_for_article').attr('value');
        
        get_interviews_data_by_filter(selected_country,selected_sector,selected_year,'interview');
    });

    jQuery('.interview_main_for_ajax .article-filter-year-ul .call_ajax_for_filter').click(function(){
        jQuery('.interview_main_for_ajax .article-filter-year-ul .call_ajax_for_filter').removeClass('selected_year_for_article');
        jQuery('.interviews_paged_track').val('');

        if(typeof jQuery(this).attr('value') != 'undefined'){
                jQuery(this).addClass('selected_year_for_article');
        }
        var selected_year = jQuery(this).attr('value');
        var selected_country  = jQuery('.interview_main_for_ajax .article-filter-countries-ul .selected_country_for_article').attr('value');
        var selected_sector  = jQuery('.interview_main_for_ajax .article-filter-sector-ul .selected_sector_for_article').attr('value');

        get_interviews_data_by_filter(selected_country,selected_sector,selected_year,'interview');
    });
    jQuery('.interview_main_for_ajax .load_more_articles_btn').click(function(){
        var selected_country  = jQuery('.interview_main_for_ajax .article-filter-countries-ul .selected_country_for_article').attr('value');
        var selected_sector  = jQuery('.interview_main_for_ajax .article-filter-sector-ul .selected_sector_for_article').attr('value');
        var selected_year    = jQuery('.interview_main_for_ajax .article-filter-year-ul .selected_year_for_article').attr('value');
        var load_more_posts = true;

        get_interviews_data_by_filter(selected_country,selected_sector,selected_year,'interview',load_more_posts);
    });
})

function get_interviews_data_by_filter(selected_country,selected_sector,selected_year,post_type,load_more_posts = false){
        var paged = jQuery('.interviews_paged_track').val();
        jQuery.ajax({
                type: "post",
                url: my_ajax_object.ajax_url,
                data: {
                        'action'                : 'get_interviews_by_filter_options',
                        'nonce'                 : my_ajax_object.nonce,
                        'selected_country'      : selected_country,
                        'selected_sector'       : selected_sector,
                        'selected_year'         : selected_year,
                        'post_type'             : post_type,
                        'paged'                 : paged                      
                },
                beforeSend: function(){
                        jQuery('.interview_main_for_ajax .spinner-wrapper').show();
                },
                success: function(response){
                        if(response.success){
                                if(load_more_posts){
                                        jQuery('.interview_main_for_ajax .article-filtered-block-inner-wrapper').append(response.data.html_response);
                                }else{
                                        jQuery('.interview_main_for_ajax .article-filtered-block-inner-wrapper').html(response.data.html_response);
                                }
                                if(response.data.paged == ''){
                                        jQuery('.interview_main_for_ajax .load_more_articles_btn').hide();
                                }else{
                                        jQuery('.interview_main_for_ajax .load_more_articles_btn').show();
                                        jQuery('.interviews_paged_track').val(response.data.paged);
                                }
                        }else{
                                jQuery('.interview_main_for_ajax .load_more_articles_btn').hide();
                                jQuery('.interview_main_for_ajax .article-filtered-block-inner-wrapper').html(response.data.html_response);
                        }                                                
                },
                complete: function(){
                        jQuery('.interview_main_for_ajax .spinner-wrapper').hide();
                }
        });        
}
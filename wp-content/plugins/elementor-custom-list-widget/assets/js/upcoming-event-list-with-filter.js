jQuery(document).ready(function() {

        // Event countries js start 
        jQuery(".upcoming-events-filter-countries-ul .dropdown-icon").click(function() {
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
        // Event countries js END..  
        
        // Event countries js start 
        jQuery(".upcoming-events-filter-categories-ul .dropdown-icon").click(function() {
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
        // Event countries js END..  
        
        // Event sector js start 
        jQuery(".upcoming-event-filter-date-ul .dropdown-icon").click(function() {
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
        // Event sector js END..
    
        jQuery('.upcoming-events-filter-countries-ul .upcoming-event-filter').click(function(){
            jQuery('.upcoming-events-filter-countries-ul .upcoming-event-filter').removeClass('selected_country_for_upcoming_events');
    
            if(typeof jQuery(this).attr('value') != 'undefined'){
                    jQuery(this).addClass('selected_country_for_upcoming_events');
            }         
        });

        // click event for categories 
        jQuery('.upcoming-events-filter-categories-ul .upcoming-event-filter').click(function(){
                jQuery('.upcoming-events-filter-categories-ul .upcoming-event-filter').removeClass('selected_category_for_upcoming_events');
        
                if(typeof jQuery(this).attr('value') != 'undefined'){
                        jQuery(this).addClass('selected_category_for_upcoming_events');
                }         
        });
    
        //select dates from and till starts
        set_date_time();
    function set_date_time(){

        var event_check   = jQuery('.event_check').val(); 
        var current_date_time = new Date();
          if(event_check==1){
            jQuery("#upcoming_from_event_date").datepicker({
              dateFormat:"dd/mm/yy",
              minDate:current_date_time,
              onSelect: function(date) {
                jQuery("#upcoming_to_event_date").datepicker('option', 'minDate', date);
              }
            });
            jQuery("#upcoming_to_event_date").datepicker({    
              dateFormat:"dd/mm/yy",
              minDate:current_date_time,
              onSelect: function(date) {
                  jQuery("#upcoming_from_event_date").datepicker('option', 'maxDate', date);
                }
            });
          }else if(event_check==2){
            jQuery("#upcoming_from_event_date").datepicker({
              dateFormat:"dd/mm/yy",
              maxDate:current_date_time,
              onSelect: function(date) {
                jQuery("#upcoming_to_event_date").datepicker('option', 'minDate', date);
              }
            });

            jQuery("#upcoming_to_event_date").datepicker({    
              dateFormat:"dd/mm/yy",
              maxDate:current_date_time,
              onSelect: function(date) {        
                  jQuery("#upcoming_from_event_date").datepicker('option', 'maxDate', date);
                }
            });
          }
    }
//select dates from and till ends
    
        //Apply button ajax call 
        jQuery('.upcoming-event-apply-btn').click(function(){
            var selected_country =  jQuery('.upcoming-events-filter-countries-ul .selected_country_for_upcoming_events').attr('value'); 
            var from_date        =  jQuery('#upcoming_from_event_date').val(); 
            var to_date          =  jQuery('#upcoming_to_event_date').val(); 
            var selected_cat     =  jQuery('.upcoming-events-filter-categories-ul .selected_category_for_upcoming_events').attr('value'); 
            
            get_upcoming_events_data_by_filter(selected_country,selected_cat,from_date,to_date);
        });
    
        //load more ajax call 
        jQuery('.load_more_upcoming_events_btn').click(function(){
            var selected_country =  jQuery('.upcoming-events-filter-countries-ul .selected_country_for_upcoming_events').attr('value'); 
            var from_date        =  jQuery('#upcoming_from_event_date').val(); 
            var to_date          =  jQuery('#upcoming_to_event_date').val(); 
            var paged            =  jQuery('.upcoming_events_paged_track').val();
            var selected_cat     =  jQuery('.upcoming-events-filter-categories-ul .selected_category_for_upcoming_events').attr('value'); 
            
            var load_more_posts = true;
            get_upcoming_events_data_by_filter(selected_country,selected_cat,from_date,to_date,paged,load_more_posts);
        });
    
        //reset sidebar values code start 
        jQuery('.upcoming-event-reset-btn').click(function(){
            jQuery('.upcoming-events-filter-countries-ul .upcoming-event-filter').removeClass('selected_country_for_upcoming_events');
            jQuery('.upcoming-events-filter-categories-ul .upcoming-event-filter').removeClass('selected_category_for_upcoming_events');
            jQuery("#upcoming_from_event_date").val("");
            jQuery("#upcoming_to_event_date").val("");
            jQuery( "#upcoming_from_event_date" ).datepicker( "destroy" );
            jQuery( "#upcoming_to_event_date" ).datepicker( "destroy" );

            set_date_time();     //call a function to reset min and max dates       
            
            var from_date        =  jQuery('#upcoming_from_event_date').val(); 
            var to_date          =  jQuery('#upcoming_to_event_date').val(); 
            
            get_upcoming_events_data_by_filter(from_date,to_date);
        });
    })
    
    function get_upcoming_events_data_by_filter(selected_country,selected_cat,from_date,to_date,paged = 1,load_more_posts = false){
            var event_check      =  jQuery('.event_check').val(); // event value for upcoming or past
            jQuery.ajax({
                    type: "post",
                    url: my_ajax_object.ajax_url,
                    data: {
                            'action'                : 'get_upcoming_events_by_filter_options',
                            'selected_country'      : selected_country,
                            'selected_cat'          : selected_cat,
                            'from_date'             : from_date,
                            'to_date'               : to_date,
                            'event_check'           : event_check,
                            'nonce'                 : my_ajax_object.nonce,                   
                            'paged'                 : paged                      
                    },
                    beforeSend: function(){
                            jQuery('.spinner-wrapper').show();
                    },
                    success: function(response){
                            if(response.success){
                                    if(load_more_posts){
                                            jQuery('.upcoming-article-block-wrapper').append(response.data.html_response);
                                    }else{
                                            jQuery('.upcoming-article-block-wrapper').html(response.data.html_response);
                                    }
                                    if(response.data.paged == ''){
                                            jQuery('.load_more_upcoming_events_btn').hide();
                                    }else{
                                            jQuery('.load_more_upcoming_events_btn').show();
                                            jQuery('.upcoming_events_paged_track').val(response.data.paged);
                                    }
                            }else{
                                    jQuery('.load_more_upcoming_events_btn').hide();
                                    jQuery('.upcoming-article-block-wrapper').html(response.data);
                            }
                                                    
                    },                                                
                    complete: function(){
                            jQuery('.spinner-wrapper').hide();
                    }
            });        
    }
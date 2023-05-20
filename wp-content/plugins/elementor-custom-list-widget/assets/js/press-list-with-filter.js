jQuery(document).ready(function() {

    // press countries js start 
    jQuery(".press-filter-countries-ul .dropdown-icon").click(function() {
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
    });
    // press countries js END..     

    // press Year js start 
    jQuery(".press-filter-year-ul .dropdown-icon").click(function() {
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
    // press Year js END..

    //ajax for select countries start
    jQuery('.press-filter-countries-ul .call_ajax_for_filter_press').click(function(){
        jQuery('.press-filter-countries-ul .call_ajax_for_filter_press').removeClass('selected_country_for_press');
        jQuery('.press_posts_track').val('');
        
        if(typeof jQuery(this).attr('value') != 'undefined'){
            jQuery(this).addClass('selected_country_for_press');
        }         
        
    });
    //ajax for select countries end

    //select dates from and till starts
    jQuery("#from_date").datepicker({
        dateFormat:"dd/mm/yy",
        onSelect: function(date) {
            jQuery("#till_date").datepicker('option', 'minDate', date);
        }
    });

    jQuery("#till_date").datepicker({
        dateFormat:"dd/mm/yy",
        onSelect: function(date) {
            jQuery("#from_date").datepicker('option', 'maxDate', date);
        }
    });
    //select dates from and till ends

    //ajax for select dates restest
    set_date_time();
    function set_date_time(){

        jQuery("#from_date").datepicker({
          dateFormat:"dd/mm/yy",
          onSelect: function(date) {
            jQuery("#till_date").datepicker('option', 'minDate', date);
          }
        });
        jQuery("#till_date").datepicker({    
          dateFormat:"dd/mm/yy",
          onSelect: function(date) {
                jQuery("#from_date").datepicker('option', 'maxDate', date);
            }
        });
    }


    //ajax for select dates restest
    jQuery('.date_reset_btn').on('click', function(){
        jQuery('.press_posts_track').val('');
        jQuery('.press-filter-countries-ul .call_ajax_for_filter_press').removeClass('selected_country_for_press');
        jQuery("#from_date").val("");
        jQuery("#till_date").val("");
        jQuery( "#from_date" ).datepicker( "destroy" );
        jQuery( "#till_date" ).datepicker( "destroy" );

        set_date_time();     //call a function to reset min and max dates       
        
        var from_date        =  jQuery('#from_date').val(); 
        var till_date          =  jQuery('#till_date').val(); 
        
        get_press_data_by_filter(from_date,till_date);
    });

    //on apply button click
    jQuery('.date_apply_btn').on('click', function(){
        jQuery('.press_posts_track').val('');

        var selected_country  = jQuery('.press-filter-countries-ul .selected_country_for_press').attr('value');

        //input dates for from date and end date
        var from_date = jQuery('#from_date').val();
        var till_date = jQuery('#till_date').val();

        get_press_data_by_filter(selected_country,from_date,till_date);
    });
    //ajax for select dates ends



    //ajax for load more starts
    jQuery('.press-filter-main-outer .load_more_press_btn').click(function(){

        var selected_country  = jQuery('.press-filter-countries-ul .selected_country_for_press').attr('value');

        //input dates for from date and end date
        var from_date = jQuery('#from_date').val();
        var till_date = jQuery('#till_date').val();
        var load_more_posts = true;
        get_press_data_by_filter(selected_country,from_date,till_date,load_more_posts);
    });
    //ajax for load more ends
});



function get_press_data_by_filter(selected_country,from_updated_date,till_updated_date,load_more_posts = false){
    var paged = jQuery('.press_posts_track').val();
    jQuery.ajax({
        type: "post",
        url: press_params.ajax_url,
        data: {
            'action'                : 'get_press_by_filter_options',
            'selected_country'      : selected_country,
            'from_updated_date'     : from_updated_date,
            'till_updated_date'     : till_updated_date,
            'paged'                 : paged                      
        },
        beforeSend: function(){
                jQuery('.press-filter-main-outer .spinner-wrapper').show();
        },
        success: function(response){
            if(load_more_posts){
                jQuery('.press-filtered-block-inner-wrapper').append(response.data.html_response);
            }else{
                jQuery('.press-filtered-block-inner-wrapper').html(response.data.html_response);
            }
            if(response.data.paged == ''){
                jQuery('.press-filter-main-outer .load_more_press_btn').hide();
            }else{
                jQuery('.press-filter-main-outer .load_more_press_btn').show();
                jQuery('.press_posts_track').val(response.data.paged);
            }                        
        },                                                
        complete: function(){
                jQuery('.press-filter-main-outer .spinner-wrapper').hide();
        }
    });        
}
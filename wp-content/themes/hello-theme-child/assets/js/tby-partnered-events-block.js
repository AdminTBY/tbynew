jQuery(document).ready(function() {
    jQuery('.upcoming-partnered-events .tby_load_more_partnered_events_btn').click(function() {
        get_partnered_events_by_filter();
    });
})

function get_partnered_events_by_filter() {
    var paged = jQuery('.tby_partnered_events_paged_track').val();
    var posts_per_page = jQuery('.tby_partnered_events_per_page').val();
    var current_post = jQuery('.tby_partnered_events_per_page').attr('partnered-current-post');
    jQuery.ajax({
        type: "post",
        url: pe_params.ajax_url,
        data: {
            'action': pe_params.action,
            'nonce': pe_params.nonce,
            'paged': paged,
            'posts_per_page': posts_per_page,
            'current_post': current_post
        },
        beforeSend: function() {
            jQuery('.partnered-events-wrapper .spinner-wrapper').show();
        },
        success: function(response) {
            if (response.success) {
                jQuery('.upcoming-partnered-events .partnered-event-block-results').append(response.data.html_response);

                if (response.data.paged == '') {
                    jQuery('.upcoming-partnered-events .tby_load_more_partnered_events_btn').attr('style','display:none !important');
                } else {
                    jQuery('.upcoming-partnered-events .tby_load_more_partnered_events_btn').removeAttr('style','display:none !important');
                    jQuery('.tby_partnered_events_paged_track').val(response.data.paged);
                }
            } else {
                jQuery('.upcoming-partnered-events .tby_load_more_partnered_events_btn').attr('style','display:none !important');
                jQuery('.upcoming-partnered-events .partnered-event-block-results').html(response.data.html_response);
            }
        },
        complete: function() {
            jQuery('.partnered-events-wrapper .spinner-wrapper').hide();
        }
    });
}
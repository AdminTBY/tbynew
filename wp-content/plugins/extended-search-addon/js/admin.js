jQuery('.wppt_generate_fuzzy_data').on('click',function(e){
	e.preventDefault();
	
	jQuery.ajax({
		url: wppt_fuse_admin_obj.ajaxurl,
        type: 'post',
        data: {
            'action':'wppt_generate_fuzzy_data'
        },
        beforeSend: function(xhr){
            jQuery('body').after('<div class="qcwbes_spinner_bg"></div>');
            jQuery('#wpbody-content .wrap').before('<div class="qcwbes_spinner spinner qcwbes_visible"></div>');
        },
        success: function( response ) {
            console.log(response);
        },
        complete: function(xhr,status){
            jQuery('.qcwbes_spinner_bg').remove();
            jQuery('.qcwbes_spinner').remove();
        }

	});
});

jQuery('.wppt_nav_container .nav-tab').on('click', function(e){
    e.preventDefault();
    var section_id = jQuery(this).attr('href');
    jQuery('.wppt_nav_container .nav-tab').removeClass('nav-tab-active');
    jQuery(this).addClass('nav-tab-active');
    jQuery('.wppt-settings-section').hide();
    jQuery('.wppt-settings-section').each(function(){
        jQuery(section_id).show();
    });
});
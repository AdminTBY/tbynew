/*
 * This JS file is loaded for tag page only!
*/
/* global params */
(function ($, params) {
	init();

	/**
	 * Instantiate more JS functions
	 * @return {Void} 
	 */
	function init() {
		jQuery(document).on('click', '.load_more_articles_btn', function(){
            var paged = jQuery('.articles_paged').val();
            var post_type = 'post';
            var $result_container = $('.tag-article-container-inner');
			var posts_per_page  = jQuery('.articles_per_page').val();
            load_more_tag_posts(paged, post_type, posts_per_page, $result_container);
        });

        jQuery(document).on('click', '.load_more_interviews_btn', function(){
            var paged = jQuery('.interviews_paged').val();
            var post_type = 'interview';
            var $result_container = $('.tag-interview-container-inner');
			var posts_per_page  = jQuery('.interviews_per_page').val();
            load_more_tag_posts(paged, post_type, posts_per_page, $result_container);
        });
	}

	/**
     * loads the articles and interview via AJAX
     *
     * @param   {string}  paged             contains page count
     * @param   {string}  post_type         post type to ge the data
     * @param   {object}  result_container  contains the reference of jquery dom element
     *
     * @return  {Void}                    
     */
	function load_more_tag_posts(paged, post_type, posts_per_page, result_container){
	    var tag_id       = jQuery('.tag_id').val();
        
        jQuery.ajax({
			type: "post",
			url: params.ajax_url,
			data: {
				'action'        : params.action,
				'tag_id'     	: tag_id,
				'post_type'     : post_type,
                'posts_per_page': posts_per_page,
				'paged'         : paged,
				'nonce'		    : params.nonce
			},
			beforeSend: function(){
				jQuery('.spinner-wrapper').show();
                result_container.parent().addClass('loading-posts');
			},
			success: function(response){
				var result = response.data;
				result_container.append(result['html_response']);

                if(result['paged'] == ''){
                    result_container.parent().find('.see-more-btn').hide();
                }else{
                    result_container.parent().find('.see-more-btn').show();
                    result_container.parent().find('.post_paged').val(result['paged']);
                }                        
			},
			complete: function(){
				jQuery('.spinner-wrapper').hide();
                result_container.parent().removeClass('loading-posts');
			}
        });        
	}

})(jQuery, tag_params);
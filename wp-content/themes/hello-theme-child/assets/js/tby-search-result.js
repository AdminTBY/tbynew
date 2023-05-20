/*
 * This JS file is loaded for search page only!
*/
/* global search_params */
(function ($, search_params) {
	init();

	/**
	 * Instantiate more JS functions
	 * @return {Void} 
	 */
	function init() {
		jQuery(document).on('click', '.load_more_search_btn', function(){
            var paged = jQuery('.search_paged').val();
            var $result_container = $('.search-main-outer .tag-article-container-inner');
            load_more_search_posts(paged,$result_container);
        });
	}
    /**
     * loads the Search posts via AJAX
     *
     * @param   {string}  paged             contains page count
     * @param   {string}  search_text         post type to ge the data
     * @param   {object}  result_container  contains the reference of jquery dom element
     *
     * @return  {Void}                    
     */
	function load_more_search_posts(paged,result_container){
        
        jQuery.ajax({
			type: "post",
			url: search_params.ajax_url,
			data: {
				'action'        : search_params.action,
				'search_text'   : search_params.s,
				'paged'         : paged,
				'nonce'		    : search_params.nonce
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
                    result_container.parent().find('.search_paged').val(result['paged']);
                }                        
			},
			complete: function(){
				jQuery('.spinner-wrapper').hide();
                result_container.parent().removeClass('loading-posts');
			}
        });        
	}

})(jQuery, search_params);
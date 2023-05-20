/*
 * This JS file is loaded for shop page only!
 */
/* global params */
(function($, params) {
    init();

    /**
     * Instantiate more JS functions
     * @return {Void} 
     */
    function init() {
        // shop countries js start 
        jQuery(".shop-filter-countries-ul .dropdown-icon").click(function() {
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
            // shop countries js END..     

        // shop item type js start 
        jQuery(".shop-filter-item-type-ul .dropdown-icon").click(function() {
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
            // shop item type js END..

        jQuery('.shop-filter-countries-ul .call_ajax_for_filter').click(function() {
            jQuery('.shop-filter-countries-ul .call_ajax_for_filter').removeClass('selected_country_for_shop');
            jQuery('.posts_paged_track').val('');

            if (typeof jQuery(this).attr('value') != 'undefined') {
                jQuery(this).addClass('selected_country_for_shop');
            }

            var selected_country = jQuery(this).attr('value');
            var selected_item_type = jQuery('.shop-filter-item-type-ul .selected_item_type_for_shop').attr('value');
            get_products_data_by_filter(selected_country, selected_item_type);
        });

        jQuery('.shop-filter-item-type-ul .call_ajax_for_filter').click(function() {
            jQuery('.shop-filter-item-type-ul .call_ajax_for_filter').removeClass('selected_item_type_for_shop');
            jQuery('.posts_paged_track').val('');

            if (typeof jQuery(this).attr('value') != 'undefined') {
                jQuery(this).addClass('selected_item_type_for_shop');
            }

            var selected_item_type = jQuery(this).attr('value');
            var selected_country = jQuery('.shop-filter-countries-ul .selected_country_for_shop').attr('value');

            get_products_data_by_filter(selected_country, selected_item_type);
        });

        jQuery(document).on('click', '.load_more_products_btn', function() {
            var selected_country = jQuery('.shop-filter-countries-ul .selected_country_for_shop').attr('value');
            var selected_item_type = jQuery('.shop-filter-item-type-ul .selected_item_type_for_shop').attr('value');
            var load_more_posts = true;

            get_products_data_by_filter(selected_country, selected_item_type, load_more_posts);
        });

    }

    /**
     * Function make an AJAX call to get the Products by the filter
     *
     * @param   {string}   selected_country    Selected country ID
     * @param   {string}   selected_item_type  Selected Item Type ID
     * @param   {boolean}  load_more_posts     Whether a load more request or not
     *
     * @return  {void}                      
     */
    function get_products_data_by_filter(selected_country, selected_item_type, load_more_posts = false) {
        var paged = jQuery('.posts_paged_track').val();
        jQuery.ajax({
            type: "post",
            url: params.ajax_url,
            data: {
                'action': 'tby_filter_shop_products',
                'selected_country': selected_country,
                'selected_item_type': selected_item_type,
                'paged': paged,
                'nonce': params.nonce
            },
            beforeSend: function() {
                jQuery('.spinner-wrapper').show();
            },
            success: function(response) {
                var result = response.data;
                if (load_more_posts) {
                    jQuery('.shop-filtered-block-inner-wrapper ul.products').append(result['html_response']);
                } else {
                    jQuery('.shop-filtered-block-inner-wrapper ul.products').html(result['html_response']);
                }
                if (result['paged'] == '') {
                    jQuery('.load_more_products_btn').hide();
                } else {
                    jQuery('.load_more_products_btn').show();
                    jQuery('.posts_paged_track').val(result['paged']);
                }
            },
            complete: function() {
                jQuery('.spinner-wrapper').hide();
            }
        });
    }

})(jQuery, shop_params);
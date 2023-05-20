jQuery(document).ready(function() {

    jQuery(".become-sponser-menu").find("a").attr({
        "data-src": "#modal-form",
        href: "javascript:;",
        "data-fancybox": "",
    });

    // jQuery(".countries-menu").find("a").attr({
    //     "data-src": "#tby-countries-popup",
    //     href: "javascript:;",
    //     "data-fancybox": "",
    //     "data-type" : "ajax"
    // });

    jQuery(".event-listing-cta-btn").find("a").attr({
        "data-src": "#mailchimp-form",
        href: "javascript:;",
        "data-fancybox": "",
    });

    jQuery(".woocommerce-product-gallery").click(function() {
        setTimeout(() => {
            jQuery("#elementor-lightbox-slideshow-single-img").css("display", "none");         
        }, "0");
    });

    new Swiper(".tax-country .article-block-main-outer", {
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        mousewheel: false,
        keyboard: true,
        loop: true,
        slidesPerView: 3,
        spaceBetween: 40,
        breakpoints: {
            0: {
                slidesPerView: 1,
                spaceBetween: 10,
            },
            480: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 15,
            },
            1440: {
                slidesPerView: 3,
                spaceBetween: 40,
            },
        },
    });

    // added js for interview forum slider

    new Swiper(".forum-section-main-outer", {
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        mousewheel: false,
        keyboard: true,
        loop: true,
        slidesPerView: 2,
        spaceBetween: 40,
        breakpoints: {
            0: {
                slidesPerView: 1,
                spaceBetween: 10,
            },
            480: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
            1440: {
                slidesPerView: 2,
                spaceBetween: 40,
            },
        },
    });
    // added js for interview forum slider

    // Ajax call for get the data 
    jQuery(".countries-menu a").click(function(e) {
        if (jQuery('.popup-data').is(':empty')){
            jQuery.ajax({
                type: "post",
                url: popup_params.ajax_url,
                data: {
                    'action': 'tby_filter_countries',
                    'nonce': popup_params.nonce
                },
                beforeSend: function() {
                    jQuery('.spinner-wrapper.country-popup').show();
                },
                success: function(response) {
                    var result = response.data;   
                    jQuery('.popup-data').html(result['html_response']);  
                    jQuery.fancybox.open({ src: "#tby-countries-popup", type: "inline" });
                },
                complete: function() {
                    jQuery('.spinner-wrapper.country-popup').hide();
                }
            });
        }else{
            jQuery.fancybox.open({ src: "#tby-countries-popup", type: "inline" });
        }
    });
});
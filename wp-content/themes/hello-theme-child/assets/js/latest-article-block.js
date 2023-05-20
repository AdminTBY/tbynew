jQuery(document).ready(function () {
  // Swiper: Slider

  new Swiper(".article-block-main-outer.second-country-slider", {
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".swiper-button-next.second-country-slider",
      prevEl: ".swiper-button-prev.second-country-slider",
    },
    pagination: {
      el: ".swiper-pagination.second-country-slider",
      clickable: true,
    },
    mousewheel: false,
    keyboard: true,
    loop: true,
    slidesPerView: 3,
    spaceBetween: 40,
    breakpoints: {
      375: {
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

  new Swiper(".event-block-main-outer.event-slider", {
    autoplay: {
      delay: 250000,
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".swiper-button-next.event-slider",
      prevEl: ".swiper-button-prev.event-slider",
    },
    pagination: {
      el: ".swiper-pagination.event-slider",
      clickable: true,
    },
    mousewheel: false,
    keyboard: true,
    loop: false,
    slidesPerView: 4,
    spaceBetween: 25,
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
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1440: {
        slidesPerView: 3,
        spaceBetween: 25,
      },
    },
  });

  new Swiper(".article-block-main-outer.country-slider", {
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".swiper-button-next.country-slider",
      prevEl: ".swiper-button-prev.country-slider",
    },
    pagination: {
      el: ".swiper-pagination.country-slider",
      clickable: true,
    },
    mousewheel: false,
    keyboard: true,
    loop: true,
    slidesPerView: 3,
    spaceBetween: 40,
    breakpoints: {
      375: {
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

  // single article js start 

  new Swiper("#single-detail-first-slider", {
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".swiper-button-next.single-detail-first-slider",
      prevEl: ".swiper-button-prev.single-detail-first-slider",
    },
    mousewheel: false,
    keyboard: true,
    loop: true,
    slidesPerView: 1,
  });


});

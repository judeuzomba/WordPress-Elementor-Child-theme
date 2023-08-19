(function($) {
  const WidgetElements_ACFSliderHandler1 = function ($scope, $) {
    const zenPrograms = $(".zen-programs");
    
    if (zenPrograms.length) {
      zenPrograms.each(function () {
        const $sliderMain = $(this).find(".gallery-main");
        const $sliderThumbs = $(this).find(".gallery-thumbs");
        
        const sliderThumbs = new Swiper($sliderThumbs, {
          spaceBetween: 10,
          slidesPerView: 7,
          allowTouchMove: false,
          touchRatio: false,
          freeMode: true,
          watchSlidesVisibility: true,
          watchSlidesProgress: true,
          centeredSlides: true,
          centeredSlidesBounds: true,
          breakpoints: {
            0: {
              slidesPerView: 2,
              spaceBetween: 0,
              allowTouchMove: true,
              touchRatio: true,
            },
            600: {
              slidesPerView: 2,
              spaceBetween: 0,
              allowTouchMove: true,
              touchRatio: true,
            },
            1024: {
              slidesPerView: 4,
              spaceBetween: 0,
              allowTouchMove: true,
              touchRatio: true,
            },
          },
        });
        
        const sliderMain = new Swiper($sliderMain, {
          spaceBetween: 10,
          autoHeight: true,
          navigation: {
            nextEl: $(this).find(".swiper-button-next"),
            prevEl: $(this).find(".swiper-button-prev"),
          },
          thumbs: {
            swiper: sliderThumbs
          },
        });
  
        const thumbsItems = $sliderThumbs.find(".swiper-slide")

        thumbsItems.on("mousedown", function () {
          sliderMain.slideTo($(this).index())
          sliderThumbs.slideTo($(this).index())
        })
      })
    }
  };

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/program_block.default', WidgetElements_ACFSliderHandler1);

  });


})(jQuery);




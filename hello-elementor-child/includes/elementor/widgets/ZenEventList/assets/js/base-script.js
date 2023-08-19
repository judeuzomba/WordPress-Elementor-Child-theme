(function($) {
  const WidgetElements_ACFSliderHandler1 = function ($scope, $) {
    const zenEventList = $(".zen-event-list");
    
    if (zenEventList.length) {
      zenEventList.each(function () {
        new Swiper($(this).find(".zen-event-list__slider"), {
          spaceBetween: 100,
          navigation: {
            nextEl: $(this).find(".zen-event-list__nav_next"),
            prevEl: $(this).find(".zen-event-list__nav_prev"),
          },
          slidesPerView: 3,
          autoHeight: true,
          breakpoints: {
            0: {
              slidesPerView: 1,
               spaceBetween: 16,
            },
            767: {
              slidesPerView: 1,
               spaceBetween: 16,
            },
            1140: {
              slidesPerView: 2,
               spaceBetween: 30,
            },
          },
        });
      })
    }
  };
  
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/zen_event_list.default', WidgetElements_ACFSliderHandler1);
  });
})(jQuery);




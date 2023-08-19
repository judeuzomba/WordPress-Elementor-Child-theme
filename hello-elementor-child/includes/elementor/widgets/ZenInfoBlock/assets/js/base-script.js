(function($) {
  const WidgetElements_ACFSliderHandler1 = function ($scope, $) {
    const zenInfoBlocks = $(".zen-info-block__item");
    
    function initBlockHover($block) {
      const items = $block.find(".zen-info-block__item-inner");
      
      if (items.length) {
        items.each(function () {
          const parent = $(this).parent();
          const toggleText = $(this).find(".zen-info-block__item-toggle");
          
          $(this).hover(function () {
            const toggleTextHeight = parent.find(".zen-info-block__item-toggle-text").innerHeight();
            parent.addClass("zen-info-block__item-toggle_active");
            toggleText.height(toggleTextHeight)
          }, function () {
            parent.removeClass("zen-info-block__item-toggle_active");
            toggleText.height(0)
          })
        })
      }
    }
  
    if (zenInfoBlocks.length) {
      setTimeout(() => {
        zenInfoBlocks.each(function () {
          initBlockHover($(this))
        })
      }, 50)
    }
  };

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/zen_info_block.default', WidgetElements_ACFSliderHandler1);

  });


})(jQuery);




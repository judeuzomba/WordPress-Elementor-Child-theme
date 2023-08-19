jQuery(document).ready(function( $ ) {
    original_slider_height = $('#zen-banner .elementor-container').css('min-height');
    var width = $( window ).width();
    if ( width >= 1025 && width < 1920 ) {
        var prop = width / 1920;
        /*$('.zen_header').css('width', '1920px');*/
        //make slider looks in full height be course of zoom
        var slider_height = $('#zen-banner .elementor-container').css('min-height');
        var proper_height = Math.round ( parseInt(slider_height) / prop );
        if ( proper_height > 0 ) {
            $('#zen-banner .elementor-container').css('min-height', proper_height);
            $('#zen-banner .elementor-container').css('height', proper_height);
        }

       /* $('body').css('zoom', prop );*/

        var currentUrl = window.location.href;

        if ( currentUrl.includes('event') ) {
          /*  $('body').css('zoom', 1 );*/
            /*$('.zen_header').css('zoom', prop );*/
            // $('.zen_breadcrumbs_block').css('zoom', prop );
           /* $('.elementor-location-footer').css('zoom', prop );*/
            $('.elementor-section.elementor-section-boxed > .elementor-container').attr('style', ' width: auto !important;');
            $('.width_about_block.elementor-section.elementor-section-boxed > .elementor-container').attr('style', ' width: auto !important;');
        };
    } else {
       /* $('body').css('zoom', 1 );*/
        $('#zen-banner .elementor-container').css('min-height', '');
        $('#zen-banner .elementor-container').css('height', '');
       /* $('.zen_header').css('width', '100%');*/
    }




    window.onresize = function(event) {
        var width = $( window ).width();

        if ( width >= 1025 && width < 1920 ) {
            var prop = width / 1920;
           /* $('.zen_header').css('width', '1920px');*/
            //make slider looks in full height be course of zoom
            if ( original_slider_height !== '' ) {
                var proper_height = Math.round ( parseInt(original_slider_height) / prop );
                if ( proper_height > 0 ) {
                    $('#zen-banner .elementor-container').css('min-height', proper_height);
                    $('#zen-banner .elementor-container').css('height', proper_height);
                }
            }

          /*  $('body').css('zoom', prop );*/


            if ( currentUrl.includes('event') ) {
              /*  $('body').css('zoom', 1 );*/
               /* $('.zen_header').css('zoom', prop );*/
                // $('.zen_breadcrumbs_block').css('zoom', prop );
                $('.elementor-location-footer').css('zoom', prop );
                $('.elementor-section.elementor-section-boxed > .elementor-container').attr('style', ' width: auto !important;');
                $('.width_about_block.elementor-section.elementor-section-boxed > .elementor-container').attr('style', ' width: auto !important;');
            };




        } else {
           /* $('body').css('zoom', 1 );*/
            $('#zen-banner .elementor-container').css('min-height', '');
            $('#zen-banner .elementor-container').css('height', '');
           /* $('.zen_header').css('width', '100%');*/
        }
    };

    //zoom don't work for mozilla. Use scale to imitate it
    if (navigator.userAgent.indexOf("Firefox") != -1) {

        jQuery(window).load(function () { // don't know why but jQuery.ready don't work

            var width = jQuery( window ).width();
            var prop = width / 1920;

            //main height
            originalHeightMain = jQuery('.site-main').outerHeight();
            heightMain = originalHeightMain * prop;

            //single height
            originalSingleHeight = jQuery('.elementor-location-single .elementor-section-wrap').outerHeight();
            singleHeight = originalSingleHeight * prop;

            //event list
            // originalEventsHeight = jQuery('.tribe-events').outerHeight();
            // eventsHeight = originalEventsHeight * prop;
            //
            // //single event
            // originalSingleEventHeight = jQuery('.tribe-events-pg-template').outerHeight();
            // singleEventHeight = originalSingleEventHeight * prop;


            //zen posts
            originalZenPosts = jQuery('.zen-posts').outerHeight();
            zenPostsHeight = originalZenPosts * prop;

            scale_width(width, prop);
            scale_height(width, prop);

            window.onresize = function(event) {
                var width = jQuery( window ).width();
                var prop = width / 1920;

                heightMain = originalHeightMain * prop;

                if(typeof(targetBlockAttr) != "undefined" && targetBlockAttr !== null) {
                    if (targetBlockAttr.skin2_zen_posts_related) {

                        var observer = new MutationObserver(function(mutations) {
                            if ( $('.zen-post').length > 3 ) {
                                var relatedPostsHeight = jQuery('.zen-post').outerHeight() * ( $('.zen-post').length / 3 );
                                heightMain = jQuery('.zen-post').outerHeight() * ( $('.zen-post').length / 3 );
                                // console.log( relatedPostsHeight * prop );
                                // console.log( heightMain + relatedPostsHeight );
                                jQuery('.site-main').css('height', ( heightMain + ( relatedPostsHeight  * prop) ) + 'px');
                            } else {
                                jQuery('.zen-posts').css('height', 0 + 'px');
                            }
                        });

                        observer.observe(document.body, {
                            childList: true,
                            subtree: true
                        });

                    }
                }

                singleHeight = originalSingleHeight * prop;
                // console.log(singleHeight);
                eventsHeight = originalEventsHeight * prop;
                singleEventHeight = originalSingleEventHeight * prop;

                scale_width(width, prop);
                scale_height(width, prop);
            };


            //edit mode
            $edit_check = 0;

            var observer1 = new MutationObserver(function(mutations) {

                if (jQuery(".elementor-editor-active").length) {
                    if ( $edit_check === 0 ) {
                        setTimeout(function() {
                            var width = jQuery( window ).width();
                            var prop = width / 1920;


                            //main height
                            originalHeightMain = jQuery('.site-main .page-content').height();
                            heightMain = originalHeightMain * prop;

                            //single height
                            originalSingleHeight = jQuery('.elementor-location-single .elementor-inner').height();
                            singleHeight = originalSingleHeight * prop;

                            //event list
                            // originalEventsHeight = jQuery('.tribe-events').height();
                            // eventsHeight = originalEventsHeight * prop;
                            //
                            // //single event
                            // originalSingleEventHeight = jQuery('.tribe-events-pg-template .tribe-events-single').height();
                            // singleEventHeight = originalSingleEventHeight * prop;

                            scale_height(width, prop);


                        }, 1000 );
                    }
                    // console.log(mutations[0]);
                    $edit_check = 1;

                    setTimeout(function() {
                        $edit_check = 0;
                    }, 1200 );
                }
            });

            observer1.observe(document.body, {
                childList: true,
                subtree: true
            });



        });


        function scale_height(width, prop) {
            return;
            if ( width >= 1025 && width < 1920 ) {

                jQuery('.site-main').css('height', heightMain + 'px');

                const targetBlock = jQuery("div[data-widget_type='zen_posts.skin2']");
                const targetBlockAttr = targetBlock.data("settings");
                var footerHeight = jQuery('.elementor-location-footer').outerHeight() * prop;
               /* jQuery('.elementor-location-footer').css('height', footerHeight + 'px');*/


                var headerHeight = jQuery('.zen_header').outerHeight() * prop;
              /*  jQuery('.zen_header').parent().css('height', headerHeight + 'px');*/

                setTimeout(function() {
                    jQuery('.elementor-location-single').css('height', singleHeight + 'px');
                    jQuery('.tribe-events-pg-template').css('height', singleEventHeight + 'px');

                    // var singleEventRelatedHeight = jQuery('.tribe-related-events').outerHeight() * prop;
                    // jQuery('.tribe-related-events').css('height', singleEventRelatedHeight + 'px');

                }, 700);

                setTimeout(function() {
                    var postHeight = 0;
                    if (jQuery(".elementor-widget-zen_posts").length) {
                        postHeight = jQuery(".elementor-widget-zen_posts")[0].getBoundingClientRect().height;

                        // var archiveHeight = jQuery('.elementor-location-archive').height() * prop;
                        // jQuery('.elementor-location-archive').css('height', (archiveHeight + postHeight) + 'px');

                        jQuery('.site-main').css('height', ( heightMain + postHeight ) + 'px');

                    }
                }, 700 );


                // jQuery('.tribe-events').css('height', eventsHeight + 'px');
                jQuery('.zen-breadcrumbs').css('top', 0 + 'px');
            } else {

            }

        }

        function scale_width(width, prop) {
            return;
            if ( width >= 1025 && width < 1920 ) {
                // var prop = width / 1920;

                // var cssValuesTribe = {
                //     'transform' : 'scale(' + prop + ')',
                //     'transform-origin': 'top center',
                //     // 'width' : '1920px'
                // };

                var cssValuesFooter = {
                    'transform' : 'scale(' + prop + ')',
                    'transform-origin': 'left bottom',
                    'width' : '1920px'
                };

                var cssValuesPopup = {
                    /*'transform' : 'scale(' + prop + ')',*/
                    /*'transform-origin': 'center center',*/
                    // 'width' : '1920px'
                };

                var cssValuesMain = {
                   /* 'transform' : 'scale(' + ( prop + 0.01 ) + ')',*/
                 /*   'transform-origin': 'top left',*/
                    'width' : '1920px'
                };
                var cssValuesWidth = {
                   /* 'transform' : 'scale(' + (prop) + ')',
                    'transform-origin': 'top left',*/
                    'width' : '1920px'
                };

                jQuery('.site-main').css(cssValuesMain);
                /*jQuery('.elementor-location-footer').css(cssValuesFooter);*/
            /*    jQuery('.zen_header').css(cssValuesWidth);*/
                jQuery('.elementor-location-single').css(cssValuesWidth);


                // $('.tribe-events').css(cssValuesWidth);


                jQuery('.elementor-location-archive').css(cssValuesWidth);
                jQuery('.zen-breadcrumbs').css(cssValuesWidth);
                // jQuery('.tribe-events-pg-template').css(cssValuesTribe);

                var observer = new MutationObserver(function(mutations) {
                    if (jQuery(".dialog-lightbox-widget-content").length) {
                        jQuery('.dialog-lightbox-widget-content').css(cssValuesPopup);
                    }

                    // if (jQuery(".tribe-events").length) {
                    //     jQuery('.tribe-events').css(cssValuesWidth);
                    //
                    // }
                });

                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });

            } else {
                // jQuery('.site-main').css('transform', 'none'   );
                // jQuery('.site-main').css('transform-origin', '50% 50%'   );
                // jQuery('.site-main').css('width', '100%' );
                // jQuery('.site-main').css('height', 'auto' );
            }
        }
    }









    var elArrow = $( "<div class='zen-banner-arrow'></div>" );
    $("#zen-banner").append(elArrow);

    $(".zen-banner-arrow").click(function () {
        var scrollTopHeight = $("#zen-banner").height()
        $("html, body").animate({ scrollTop: scrollTopHeight }, "slow");
        return false;
    })

    const debounce = (fn, time = 200) => {
        let timeout = null;

        function start() {
            if (timeout) return false;

            fn.apply(this, arguments);

            timeout = true

            setTimeout(() => {
                timeout = false;
            }, time)
        }

        return start
    };

    function checkWidth() {
        if (window.innerWidth >= 1024) {
            $("body").addClass("show-body")
        }
    }

    const desktopMenuItems = $(".zen-main-menu .menu-item > a");
    const iconDesktopHTML = `<div class="sub-arrow"><i class="fa"></i></div>`;
  
    const mobileMenuItems = $(".zen_mobile_menu .menu-item > a");
    const iconMobileHTML = `<span class="sub-arrow"><i class="fa"></i></span>`;

    setTimeout(() => {
        $(window).resize(debounce(checkWidth));
        // checkWidth();
  
      if (window.innerWidth > 1024 && desktopMenuItems.length) {
        let opened = false;
  
        desktopMenuItems.each(function () {
          if (this.classList.contains("has-submenu")) {
            $(this).closest("li").append(iconDesktopHTML);
  
            $(this).closest("li").hover(function () {
              if (!opened) {
                $(this).addClass("active");
                $(this).find("ul").slideDown(400);
              }
              opened = true;
            }, function () {
              $(this).removeClass("active");
              
              $(this).find("ul").slideUp(400, function () {
                opened = false;
              })
            });
          }
        });
    
        desktopMenuItems.click(function (e) {
          if (e.target.classList.contains("has-submenu")) {
            location.href = e.target.getAttribute("href")
          }
        })
      }
  
      if (window.innerWidth <= 1024 && mobileMenuItems.length) {
        $(".zen_mobile_menu .elementor-menu-toggle").click(function () {
          $(".zen_mobile_menu").find(".sub-menu").slideUp()
        });
  
        mobileMenuItems.each(function () {
          if (this.classList.contains("has-submenu")) {
            $(this).closest("li").append(iconMobileHTML);
            $(this).closest("li").find(".sub-arrow").click(function () {
              $(this).closest("li").find("ul").slideToggle()
            })
          }
        });
        
        mobileMenuItems.click(function (e) {
          if (e.target.classList.contains("has-submenu")) {
            location.href = e.target.getAttribute("href")
          }
        })
      }
    }, 300);


    $(document).on('click','[data-pafe-section-link]',function() {
        var link = $(this).data('pafe-section-link'),
            external = $(this).data('pafe-section-link-external'),
            linkAppend = '<a href="' + link + '" data-pafe-section-link-a style="display:none"></a>';

        if(external == 'on') {
            linkAppend = '<a href="' + link + '" target="_blank" data-pafe-section-link-a style="display:none"></a>';
        }

        if ($(this).find('[data-pafe-section-link-a]').length == 0) {
            $(this).append(linkAppend);
        }

        var alink = $(this).find('[data-pafe-section-link-a]');
        alink[0].click();
    });

    const eventWrapper = $(".tribe-events-single-event-description")

    if (eventWrapper) {
        const hasElementorNodes = eventWrapper.find("*[class^='elementor']")
        if (hasElementorNodes.length) {
            eventWrapper.addClass("has-elementor-nodes")
            $("#tribe-events-pg-template").addClass("max-width-100")
        }
    }



    var observer = new MutationObserver(function(mutations) {
        if (jQuery(".select2-results__options").length) {
            $( ".select2-results__options li" ).each(function( index ) {
                // if ( $( this ).text() === 'Featured on Website List' ) {
                if ( $( this ).text().toLowerCase().indexOf("featured") >= 0)  {
                    $(this).css('display', 'none');
                }

            });
        }

    });


    observer.observe(document.body, {
        childList: true,
        subtree: true
    });



    var bg;

    $( ".elementor-widget-container" ).each(function( index ) {
        // $(this).remove();
        bg = $(this).css('background-image');
        if ( bg != 'none' ) {
            // bg = bg.replace('url(','').replace(')','').replace(/\"/gi, "");
            // $(this).attr('data-src', bg);
            // $(this).css('background-image', 'none');

        }

    });

    checkWidth();


    Waypoint.destroyAll();


    $( ".elementor-invisible" ).each(function() {

        var targetBlockAttr = $(this).data("settings");
         animation = targetBlockAttr._animation, delay = targetBlockAttr._animation_delay;
        if (typeof delay === "undefined") {
            delay = 0;
        }
        if ('none' === animation) {
            $(this).removeClass('elementor-invisible');
            return;
        }


        const current = $(this);
        var waypoint = new Waypoint({
            element: current,
            handler: function(direction) {
                setTimeout(function () {
                    current.removeClass('elementor-invisible').addClass('animated ' + animation);
                }, delay);
            },
            offset: '100%',
            triggerOnce: true
        });
    });




    width = jQuery( window ).width();
    prop = width / 1920;

    if ( width >= 1025 && width < 1920 ) {
        multiplicator = 100;
        jQuery(window).scroll(function () {

            multiplicator = (window.scrollY * 0.05);
                var ua = navigator.userAgent.toLowerCase();
                if (ua.indexOf('safari') != -1) {
                    if (ua.indexOf('chrome') > -1) {
                    } else {
                        multiplicator = (window.scrollY * 0.7);
                    }
                }

                $( ".elementor-invisible" ).each(function() {
                    var targetBlockAttr = $(this).data("settings");
                    animation = targetBlockAttr._animation,
                    delay = targetBlockAttr._animation_delay;
                    if (typeof delay === "undefined") {
                        delay = 0;
                    }

                    const current = $(this);
                    var waypoint = new Waypoint({
                        element: current,
                        handler: function(direction) {
                            setTimeout(function () {
                                current.removeClass('elementor-invisible').addClass('animated ' + animation);
                            }, delay);
                        },
                        offset: multiplicator,
                        triggerOnce: true
                    });
                });



                Waypoint.refreshAll()
        });
    }



});


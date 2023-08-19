(function($) {
  const HelloPropertySkinScript = function ($scope, $) {
    setTimeout(() => {
      (function ($) {
        const getTermHTML = data => {
          return `
          <div class="zen-wrap-badge">
            <a href="${ data.url }" class="zen-badge">
              ${ data.name }
            </a>
          </div>
        `
        };
      
        const getImageHTML = (imageUrl, postUrl) => {
          const imageHTML = `
          <a class="elementor-post__thumbnail__link" href="${ postUrl }">
            <div class="elementor-post__thumbnail">
              <img
                src="${ imageUrl }"
                alt=""
              >
            </div>
          </a>
        `;
        
          const placeholderHTML = `
          <div class="elementor-post__thumbnail__link elementor-post__thumbnail__link_placeholder">
          </div>
        `;
        
          return imageUrl ? imageHTML : placeholderHTML
        };
      
        const getCardHTML = cardData => {
          const postUrl = cardData.guid ? cardData.guid : "/";
          let terms = null;
        
          if (cardData.hasOwnProperty("terms")) {
            terms = Object.entries(cardData.terms).map(([_, value]) => value)
          }
          // const resultData = new Intl.DateTimeFormat('en-US', {
          //   month: "long",
          //   day: "numeric",
          //   year: "numeric",
          // }).format(new Date(cardData.date));

          return `
          <article class="elementor-post elementor-grid-item zen-post recommended post type-post status-publish format-standard hentry category-recommended">
            <div class="elementor-post__card">
              ${ getImageHTML(cardData.image_url, postUrl) }
              
              <div class="elementor-post__text zen-post__wrap-text">
                <div class="zen-date">
                  ${ cardData.date }
                </div>
                <div class="zen-post__excerpt elementor-post__excerpt">
                 <a class="zen_post_link" href="${ postUrl }">
                    ${ cardData.post_title }
                  </a>
                </div>
                <a href="${ cardData.author_url }" class="zen-author">
                  by ${ cardData.author_name }
                </a>
                <div class="zen-badges">
                  ${ terms ? terms.map(getTermHTML).join("") : "" }
                </div>
              </div>
            </div>
          </article>
        `
        };
      
        const injectCards = posts => {
          return posts.map(post => $(getCardHTML(post)));
        };
      
        const fnCheckScroll = targetHeight => {
          const curScrollHeight = $(window).scrollTop() + window.innerHeight
          // console.log("curScrollHeight: ", curScrollHeight)
          return (curScrollHeight - targetHeight) >= 0;
        };
        
        const posts = $(".zen-posts");
        
        if (posts.length) {
          posts.each(function () {
            const $parent = $(this);
            let page = 1;
            let $cards = $parent.find(".zen-post");
            let disabledScroll = false;
            let resultCategories = [];
            const filter = $parent.find(".zen-posts-filter");
          
            const $grid = $parent.find(".elementor-grid").isotope({
              itemSelector: ".zen-post",
              columnWidth: 30
            });
  
            const targetBlock = $("div[data-widget_type='zen_posts.skin2']");
            const targetBlockAttr = targetBlock.data("settings");
            
            let hasScroll = true;
            
            if (targetBlockAttr ? targetBlockAttr.skin2_zen_posts_related : null) {
              hasScroll = targetBlockAttr.skin2_zen_posts_related !== "yes"
            }
            
            const posts_per_page = targetBlockAttr ? targetBlockAttr.skin2_posts_per_page : null;
            const related_tags = targetBlockAttr ? targetBlockAttr.skin2_related_tags : null;

            // console.log(related_tags);



            const $links = $(this).find(".zen-posts-filter__item-link");
            const loadPosts = (data, link) => {
              $(".zen-posts-filter").addClass("zen-posts-filter_disabled");
              disabledScroll = true;
              
              jQuery.post( elementorProFrontend.config.ajaxurl, data, function (response) {
                const cards = Object.entries(jQuery.parseJSON(response)).map(([_, value]) => value);
                const resultCards = injectCards(cards);
              
                if (link) {
                  $grid.isotope("remove", $cards);
                }
              
                resultCards.forEach(item => {
                  $grid.isotope("insert", item);
                });
                $grid.isotope("layout");
                $cards = $parent.find(".zen-post");
              
                disabledScroll = false
                $(".zen-posts-filter").removeClass("zen-posts-filter_disabled");
              });
            };
  
            const pageURL = window.location.href;
            const lastURLSegment = pageURL.split("/"); // parse url
            
            if (filter.length) {
              (function () {
                const allSelected = $(".zen-posts-filter__item-link_all.zen-posts-filter__item-link_active");
                const activeItems =  $(".zen-posts-filter__item-link_active");
    
                if (!allSelected.length && activeItems.length) {
                  activeItems.each(function () {
                    const cat = $(this).attr('data-cat');
                    resultCategories.push(parseInt(cat) || "");
                  });
                }
    
                $grid.isotope("remove", $cards);
                $grid.isotope("layout");
    
                loadPosts({
                  action: 'zen_posts_load',
                  cat: JSON.stringify(resultCategories),
                  posts_per_page: posts_per_page,
                  nonce_code : post_ajax.nonce
                })
              })();
  
              $links.click('click', function (e) {
                e.preventDefault();
    
                let data = {
                  action: 'zen_posts_load',
                  cat: JSON.stringify(resultCategories),
                  posts_per_page: posts_per_page,
                  nonce_code : post_ajax.nonce
                };
    
                if ($(this).hasClass("zen-posts-filter__item-link_all")) {
                  $links.removeClass("zen-posts-filter__item-link_active");
                  $(this).addClass("zen-posts-filter__item-link_active");
                  resultCategories = [];
                } else {
                  $(".zen-posts-filter__item-link_all").removeClass("zen-posts-filter__item-link_active");
                  const curCat = parseInt($(this).attr('data-cat')) || "";
                  if (resultCategories.includes(curCat)) {
                    if (resultCategories.length <= 1 && resultCategories[0] === curCat) {
                      return false;
                    }
                    $(this).removeClass("zen-posts-filter__item-link_active");
                    resultCategories = resultCategories.filter(cat => cat !== curCat)
                  } else {
                    $(this).addClass("zen-posts-filter__item-link_active");
                    resultCategories.push(curCat);
                  }
                }
    
                page = 1;
                disabledScroll = false;
                data.cat = JSON.stringify(resultCategories);
                loadPosts(data, $(this));
              });
            } else {


              const data = {
                action: 'zen_posts_load',
                posts_per_page: posts_per_page,
                related_tags: related_tags,
                filter: filter.length,
                archive: lastURLSegment[4],
                page : page,
                nonce_code : post_ajax.nonce
              };
              loadPosts(data);
              page = page + 1;
            }

            var heightMain = jQuery('.site-main').height() * 0.75;
            const isFirefox = navigator.userAgent.indexOf("Firefox") >= 0
            
            if (hasScroll) {
              $(window).scroll(function () {
                if (isFirefox) { // if scale
                  const parentHeight = $parent[0].getBoundingClientRect().height;
                  const parentOffset = $parent[0].getBoundingClientRect().x;
                  const cardHeight =  $(".zen-post")[0].getBoundingClientRect().height;

                  scrollValue = fnCheckScroll(parentHeight + parentOffset - cardHeight / 2 ) && !disabledScroll;

                  jQuery('.site-main').css('height', ( heightMain + parentHeight ) + 'px');
                  jQuery('.elementor-location-archive').css('height', ( parentHeight + parentOffset ) + 'px');

                } else { //if zoom
                  const parentHeight = $parent.outerHeight()
                  const parentOffsetTop = $parent.offset().top
                  const totalHeight = parentHeight + parentOffsetTop
                  
                  const cardHeight = $(".zen-post").eq(1).height()
                  
                  // console.log(
                  //   "parentheight: ", parentHeight,
                  //   "parentOffsetTop: ", Math.round(parentOffsetTop),
                  //   "cardHeight: ", cardHeight,
                  //   "totalHeight: ", Math.round(totalHeight),
                  //   "totalHeight - cardHeight: ", Math.round(totalHeight - cardHeight),
                  // )
                  
                  scrollValue = fnCheckScroll(totalHeight - cardHeight) && !disabledScroll
                }


                if ( scrollValue ) {

                  if ( filter.length ) {
                    page = page + 1;
                    const data = {
                      action: 'zen_posts_load',
                      posts_per_page: posts_per_page,
                      related_tags: related_tags,
                      filter: filter.length,
                      cat: JSON.stringify(resultCategories),
                      page : page,
                      nonce_code : post_ajax.nonce
                    };
                    loadPosts(data)
                  } else {
                    const data = {
                      action: 'zen_posts_load',
                      posts_per_page: posts_per_page,
                      related_tags: related_tags,
                      filter: filter.length,
                      archive: lastURLSegment[4],
                      page : page,
                      nonce_code : post_ajax.nonce
                    };
                    loadPosts(data)
                    page = page + 1;
                  }
                }
              });
            }
          })
        }
      
        const filterBtn = $(".zen-posts-filter__btn_filter")
      
        if (filterBtn) {
          filterBtn.click(function () {
            $(this).closest(".zen-posts-filter").toggleClass("zen-posts-filter_active");
            $(this).toggleClass("active")
          })
        }
  
        const searchBtn = $(".zen-posts-filter__btn_search");
        const searchMainBtn = $(".elementor-search-form__toggle");
  
        if (searchBtn) {
          searchBtn.click(function () {
            searchMainBtn.trigger("click")
          })
        }
  
  
  
  
  
      })(jQuery);
    }, 300);
  };

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/zen_posts.skin2', HelloPropertySkinScript);
  });

})(jQuery);


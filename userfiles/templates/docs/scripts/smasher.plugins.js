(function ($) {
    "use strict";
    var INITIALIZED_TOKEN = 'testimonial.initialized';
    $.fn.testimonial = function (options) {
        return this.each(function () {
            var $this = $(this);
            if ($this.data(INITIALIZED_TOKEN)) {
                return;
            }

            $this.addClass('baloon')
                .append('<span class="nip"><br></span>')
                .wrap('<div class="col">');

            var company = $this.data('company');
            if (company) {
                $this.after('<p class="company">' + company + '</p>');
            }

            var person = $this.data('person');
            if (person) {
                $this.after('<p class="person">' + person + '</p>');
            }

            $this.data(INITIALIZED_TOKEN, true);
        });
    }
})(jQuery);

(function ($) {
    "use strict";
    var INITIALIZED_TOKEN = 'portfolio.item.initialized';
    $.fn.portfolioItem = function (options) {
        var options = $.extend({skin:"default"}, options);
        return this.each(function () {
            var $this = $(this);
            if ($this.data(INITIALIZED_TOKEN)) {
                return;
            }

            var image = $this.data('image');
            var $thumb = $this.find('img');
            if (!$thumb) {
                return false;
            }

            var href = $this.attr('href');
            var title = $this.attr('title');

            if (href && options.skin != "alternative") {
                $this.append('<a href="' + href + '" class="link"><br/></a>');
            }
            if (image && options.skin != "alternative") {
                var $zoom = $('<a href="' + image + '" class="zoom" title="'+title+'"><br/></a>');
                $zoom.fancybox();
                $this.append($zoom);
            }
            if (title) {
                $thumb.after('<span class="name">' + title + '</span>');
            }

            $this.addClass('col');

            if (options.skin != "alternative") {
                $this.append('<span class="cover"><br/></span>');

                var subElementsSelector = ".link, .zoom, .name, .cover",
                    fadeDuration = 200;

                $this.hoverIntent(function () {
                    $this.find(subElementsSelector).hide().css({visibility:"visible"}).fadeIn(fadeDuration);
                }, function () {
                    $this.find(subElementsSelector).show().fadeOut(fadeDuration);
                });
            }

            $this.data(INITIALIZED_TOKEN, true);
        });
    }

})(jQuery);

(function ($) {
    "use strict";
    $.fn.wrappedCarousel = function (options) {
        var INITIALIZED_TOKEN = 'wrapped.carousel.initialized';
        return this.each(function () {
            var $this = $(this);
            if ($this.data(INITIALIZED_TOKEN)) {
                return;
            }
            var $wrapper = $("<div/>");
            if (typeof options.classes == "string") {
                $wrapper.addClass(options.classes);
            } else if ($.isArray(options.classes)) {
                for (var i = 0; i < options.classes.length; i += 1) {
                    $wrapper.addClass(options.classes[i]);
                }
            }
            $this.wrap($wrapper);
            options = $.extend({wrap:'both',
                scroll:1}, options);
            $this.jcarousel(options);
            $this.data(INITIALIZED_TOKEN, true);
        });
    };
})(jQuery);

/*Gallery items - video and image thumbs.*/
(function ($) {
    "use strict";
    $.fn.galleryItem = function (options) {
        var INITIALIZED_TOKEN = 'gallery.item.initialized';
        return this.each(function () {
            var $this = $(this);
            if ($this.data(INITIALIZED_TOKEN)) {
                return;
            }
            var title = $this.attr('title');
            if (title) {
                $this.prepend('<span class="name">' + title + "</title>");
            }
            $this.addClass('col');
            var $img = $this.find('img');
            var $actionButton = $('<span class="view"><br/>');
            $img.wrap('<span class="wrap"/>').after($actionButton);
            $this.mouseenter(function () {
                $actionButton.addClass('hover');
                $this.mouseleave(function () {
                    $actionButton.removeClass('hover');
                });
            });
            $this.data(INITIALIZED_TOKEN, true);
        });
    };
})(jQuery);

(function ($) {
    "use strict";
    $.fn.servicePlan = function (options) {
        var INITIALIZED_TOKEN = 'service.plan.initialized';
        return this.each(function () {
            var $this = $(this);
            if ($this.data(INITIALIZED_TOKEN)) {
                return;
            }
            if ($this.data('important')) {
                $this
                    .addClass('service-plan-important')
                    .append('<span class="tag"><br/></span>');
            }
            $this.addClass('col');
            $this.data(INITIALIZED_TOKEN, true);
        });
    };
})(jQuery);

/*
 * jCarousel wrapper to make dots based navigation.
 * 
 * Requires: - jCarousel http://sorgalla.com/projects/jcarousel/
 */
(function ($) {
    "use strict";
    $.fn.dottedCarousel = function (options) {
        var MOUSE_OVER_TAG = 'mouse.over.tag';
        return this.each(function () {
            var $this = $(this), $dots;
            if ($this.data('initialized')) {
                return;
            }

            var $wrapper = $('<div class="main-carousel"></div>');
            $wrapper.addClass($this.attr('class'))
            $this.wrap($wrapper);

            function initCallback(carousel) {
                var i, $dot;
                $dots = $("<div></div>").addClass('dots').addClass('clearfix');
                for (i = 1; i <= carousel.options.size; i += 1) {
                    $dot = $("<a href='#'><br/></a>");
                    $dot.attr('id', 'dot-' + i);
                    $dots.append($dot);
                }
                $dots.find('a').on(
                    'click',
                    function () {
                        var $trigger = $(this);
                        carousel.scroll($.jcarousel.intval($trigger.attr('id').replace('dot-', '')));
                        $dots.find("a").removeClass('dot-active');
                        $trigger.addClass('dot-active');
                        return false;
                    });
                $this.after($dots);
            }

            options = $.extend({
                wrap:'both',
                scroll:1,
                auto:4,
                initCallback:initCallback,
                itemVisibleInCallback:function (carousel, li, i) {
                    $dots.find("a").removeClass('dot-active');
                    $dots.find("a[id='dot-" + i + "']").addClass('dot-active');
                },
                setupCallback:function (carousel) {
                    function stop(){
                        carousel.stopAuto();
                    }

                    function start(){
                        if (! carousel.clip.data(MOUSE_OVER_TAG)){
                            carousel.startAuto();
                        }
                    }

                    carousel.clip.hover(function(){
                        carousel.clip.data(MOUSE_OVER_TAG, true);
                        stop();
                    }, function(){
                        carousel.clip.data(MOUSE_OVER_TAG, false);
                        start();
                    });

                    setInterval(function(){
                        if ($("#fancybox-wrap").is(":visible")){
                             stop();
                        }else{
                            start();
                        }
                    }, 500);
                }
            }, options);
            $this.jcarousel(options);
            $this.data('initialized', true);
        });
    };
})(jQuery);

/* Loads twitter feed into blocks with class twitter-block using tweet jQuery plugin; 
 * Just add attribute data-twitter-username
 * 
 * Required:
 * - jQuery tweet http://tweet.seaofclouds.com/
 * 
 * 
 * */
(function ($) {
    $(function () {
        $(".twitter-block").each(function () {
            var $element = $(this);
            $element.tweet({
                intro_text:$element.html(),
                username:$element.data('twitter-username'),
                count:$element.data("tweets-count") || 3
            });
        });
    });
})(jQuery);

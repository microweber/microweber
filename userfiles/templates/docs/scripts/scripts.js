/**
 * Main Smasher theme scripts file.
 *
 * (c) 2012
 */

(function ($, window) {
    "use strict";

    $(function () {

        /* http://stackoverflow.com/a/2769609/207503 */
        $('body').removeClass('nojQuery');

        /* Placeholder for old browsers */
        $('input[placeholder], textarea[placeholder]').placeholder();

        /*
         * JS PIE. Features and usage:
         * http://css3pie.com/documentation/supported-css3-features/
         */

        /*a.button.big, .button-grey, .button-dark-grey, .button-grey-arrow-right, .button-dark-grey-arrow-right, .button-white-arrow-right, .button-orange-arrow-right*/

        if (window.PIE) {
            $('.button').each(function () {
                window.PIE.attach(this);
            });
        }

        var bigSliderOptions = {};
        $('#big-slider').dottedCarousel(bigSliderOptions);

        $('#small-slider').wrappedCarousel({classes:['small-slider-skin']});
        $("#posts-slider").wrappedCarousel({classes:['columns-slider-skin']});
        $("#testimonials-slider").wrappedCarousel({classes:['columns-slider-skin']});

        $(".testimonial").testimonial();

        $(".video-item, .picture-item").galleryItem();

        $(".service-plan").servicePlan();

        $(".projects-gallery .portfolio-item").portfolioItem();
        $(".projects-gallery-2 .portfolio-item").portfolioItem({skin:'alternative'});


        $("#side-tabs, #small-tabs").tabs();

        $("#small-side-accordeon").accordion();

        $("#big-accordeon, #gallery-accordeon").accordion({
            autoHeight:false
        });

        /* Add focus effect on search form */
        $("#search-form input").focus(function () {
            $("#search-form").addClass("focus");
        });
        $("#search-form input").focusout(function () {
            $("#search-form").removeClass("focus");
        });

    });
})(jQuery, window);

/*
 * Form validation, AJAX submit
 *
 * Requires:    - jQuery validate https://github.com/jzaefferer/jquery-validation
 *
 */
(function ($) {
    "use strict";
    $(function () {

        /*Form validation in contact page*/
        $("form.comments-form").validate({});

        /*Bottom form validation*/
        $("form.footer-form").validate({errorClass:'error-footer'});

        /*
         * Forms with class js-validate will be validated automatically. You can
         * define validation rules in markup. Add class required, url, email.
         * Add attributes minlength, maxlength. For more info check official
         * jQuery validate documentation:
         * http://docs.jquery.com/Plugins/Validation
         *
         */
        $("form.js-validate").validate({});

        function showMessages(messages, messagesContainerSelector, $context) {
            var $container = $(messagesContainerSelector, $context);
            $container.find(".message").remove();
            if (!messages) {
                return false;
            }
            for (var i = 0; i < messages.length; i += 1) {
                var message = messages[i];
                var $message = $("<p/>").html(message.text).addClass("message");
                if (message.type) {
                    $message.addClass(message.type + "-message");
                }
                $container.append($message);
            }
        }

        /*
         * Forms with class ajax-submit will be posted via ajax automatically.
         * Respond with json-encoded object.
         *
         * Success response example: {"success":true,"messages":[{"text":"Data
         * successfully saved.","type":"success"}]}
         *
         * Error response example: {"success":false,"messages":[{"text":"Error
         * occurred. Try again later.","type":"error"}]}
         *
         */
        $("form.ajax-submit").on('submit', function () {
            var formControlsSelector = "div.form-controls";
            var $form = $(this);
            var errorMessage = $form.data('errormessage') || "An error has occurred: {0}. Try again later or contact site administrator."
            if (!$form.valid()) {
                showMessages([
                    {type:'error', text:"<strong>Error</strong> - All fields are required"}
                ], formControlsSelector, $form);
                return false;
            }
            $.ajax({
                url:$form.attr('action'),
                type:$form.attr('method'),
                success:function (data, textStatus, jqXHR) {
                    if (data.success) {
                        $form.find("input[type='text'], input[type='password'], textarea").val("");
                    }
                    showMessages(data.messages, formControlsSelector, $form);
                },
                error:function (jqXHR, textStatus, errorThrown) {
                    var message = {type:"error", text:errorMessage.f(errorThrown)};
                    showMessages([message], formControlsSelector, $form);
                }
            });
            return false;
        });
    });
})(jQuery);

/*
 * Images with class 'greyscale' becomes white-and-black after load. On mouse
 * over changes to coloured version.
 *
 * Requires: - Pixastic http://www.pixastic.com - hoverIntent
 * http://cherne.net/brian/resources/jquery.hoverIntent.html
 */
(function ($, document, window) {
    "use strict";
    $(document).ready(function () {
        /*
         * Hide all images with class 'greyscale' right after DOM is loaded to
         * avoid flickering after image is converted to greyscale version.
         */
        if (!$.browser.msie) {
            $(".greyscale").css('visibility', 'hidden');
        }
    });

    $(window).load(function () {
        var idd = 0;
        var fadeTime = 200;
        $('.greyscale').each(function () {
            this.id = idd + '-greyscale';
            idd += 1;
            var id = this.id;

            function greyscale(id) {
                var img = document.getElementById(id), returned;
                if (img.complete) {
                    returned = Pixastic.process(img, "desaturate", {
                        average:false
                    });
                    $(returned).css('visibility', 'visible');
                }
            }

            function ungreyscale(id) {
                Pixastic.revert(document.getElementById(id));
                $(document.getElementById(id)).css('visibility', 'visible');
            }

            $(this).parent().hoverIntent(function () {
                $(this).find('.label').fadeIn(fadeTime);
                ungreyscale(id);
            }, function () {
                $(this).find('.label').fadeOut(fadeTime);
                greyscale(id);
            });
            this.onload = function () {
                greyscale(id);
            };
            greyscale(id);
        });
    });
})(jQuery, document, window);

/* Portfolio filters & Portfolio list hovers and lightboxes;
 *
 * Requires:
 * -jQuery hoverIntent
 * -jQuery FancyBox
 * -jQuery.quicksand.js http://razorjack.net/quicksand/index.html
 *
 */
(function ($) {
    "use strict";
    var qsPreferences = {
        duration:750,
        adjustHeight:'auto'
    };

    function initPortfolioItems() {
        var subElementsSelector = ".link, .zoom, .name, .cover",
            fadeDuration = 200;

        $(".projects-gallery .col").hoverIntent(function () {
            $(this).find(subElementsSelector).hide().css({visibility:"visible"}).fadeIn(fadeDuration);
        }, function () {
            $(this).find(subElementsSelector).show().fadeOut(fadeDuration);
        });
        $(".projects-gallery .col .zoom").fancybox();
    }

    $(function () {
        initPortfolioItems();
        var $grid = $(".grid");
        var i = 1;
        $grid.find(".col").each(function () {
            $(this).attr('data-id', i);
            i += 1;
        });

        var $filters = $(".filters"),
            $trigger = $('<a href="#" class="button button-grey small trigger"><span>Filter</span></a>'),
            $wrapper = $("<div></div>"),

            $data = $grid.clone(),
            width = $filters.width() - $trigger.outerWidth(),
            fadeTime = 100;
        $filters.css('visibility', 'hidden').prepend($trigger).wrapInner($wrapper);

        function show() {
            $trigger.hide();
            $filters.find('a:not(.hover, .trigger)').stop(true, true).fadeIn(fadeTime);
        }

        function hide() {
            if ($("html").is(".touch")) {
                return;
            }
            $filters.find('a:not(.hover, .trigger)').stop(true, true).fadeOut(fadeTime, function () {
                $filters.css('visibility', 'visible');
                $trigger.fadeIn(fadeTime);
            });
        }

        hide();
        $filters.hoverIntent(show, hide);
        $filters.find("a").on('click', function () {
            var $filter = $(this),
                selector = ".col",
                filterKey = $filter.data('filter');
            $filters.find("a").removeClass("hover");
            $filter.addClass("hover");
            if (filterKey) {
                selector += "." + filterKey;
            }
            $grid.quicksand($data.find(selector), qsPreferences, function () {
                initPortfolioItems();
            });
            return false;
        });
    });
})(jQuery);

(function ($) {
    $(function () {
        $(".project-slider").each(function () {
            var $slider = $(this);
            $slider.wrap('<div class="portfolio-slider-skin"></div>');
            var $footer = $('<div class="portfolio-slider-footer clearfix">' +
                '<p class="count">Showing image ' +
                '<span class="curr">1</span> of ' +
                '<span class="of">1</span>' +
                '</p>' +
                '</div>');
            $slider.after($footer);

            $slider.jcarousel({
                wrap:'both',
                scroll:1,
                itemVisibleInCallback:function (carousel, li, i) {
                    $footer.find('.curr').text(i);
                },
                initCallback:function (carousel) {
                    $footer.find('.of').text(carousel.options.size);
                }
            });
        });
    });
})(jQuery);

//http://stackoverflow.com/questions/1038746/equivalent-of-string-format-in-jquery
String.prototype.format = String.prototype.f = function () {
    var s = this,
        i = arguments.length;

    while (i--) {
        s = s.replace(new RegExp('\\{' + i + '\\}', 'gm'), arguments[i]);
    }
    return s;
};
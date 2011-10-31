/**
 * Slimbox v2.04 - The ultimate lightweight Lightbox clone for jQuery
 * (c) 2007-2010 Christophe Beyls <http://www.digitalia.be>
 * MIT-style license.
 *   
 * Modified to be even more leightweight and optimized for PDW File Browser
**/
(function($) {

    // Global variables, accessible to Slimbox only
    var win = $(window), options, images, activeImage = -1, activeImageWidth, activeImageHeight, activeURL, compatibleOverlay, middle, centerWidth, centerHeight,
        ie6 = !window.XMLHttpRequest, hiddenElements = [], documentElement = document.documentElement,

    // Preload images
    preload = {},

    // DOM elements
    overlay, center, image, sizer;

    /*
        Initialization
    */

    $(function() {
        // Append the Slimbox HTML code at the bottom of the document
        $("body").append(
            $([
                overlay = $('<div id="lbOverlay" />')[0],
                center = $('<div id="lbCenter" />').append(
                    $('<a id="lbCloseLink" href="#" />').add(overlay).click(close)[0]
                )[0]
            ]).css("display", "none")
        );

        image = $('<div id="lbImage" />').appendTo(center).append(
            sizer = $('<img src="" style="position: relative;" />')[0]
        )[0];

    });


    /*
        API
    */

    // Open Slimbox with the specified parameters
    $.slimbox = function(_images, startImage, _options) {
        options = $.extend({
            overlayOpacity: 0.8,            // 1 is opaque, 0 is completely transparent (change the color in the CSS file)
            overlayFadeDuration: 400,       // Duration of the overlay fade-in and fade-out animations (in milliseconds)
            resizeDuration: 400,            // Duration of each of the box resize animations (in milliseconds)
            resizeEasing: "swing",          // "swing" is jQuery's default easing
            initialWidth: 250,              // Initial width of the box (in pixels)
            initialHeight: 250,             // Initial height of the box (in pixels)
            imageFadeDuration: 400,         // Duration of the image fade-in animation (in milliseconds)
            captionAnimationDuration: 400,  // Duration of the caption animation (in milliseconds)
            closeKeys: [27, 88, 67]         // Array of keycodes to close Slimbox, default: Esc (27), 'x' (88), 'c' (67)
        }, _options);

        // The function is called for a single image, with URL and Title as first two arguments
        if (typeof _images == "string") {
            _images = [[_images, startImage]];
            startImage = 0;
        }

        middle = win.scrollTop() + (win.height() / 2);
        centerWidth = options.initialWidth;
        centerHeight = options.initialHeight;
        $(center).css({top: Math.max(0, middle - (centerHeight / 2)), width: centerWidth, height: centerHeight, marginLeft: -centerWidth/2}).show();
        compatibleOverlay = ie6 || (overlay.currentStyle && (overlay.currentStyle.position != "fixed"));
        if (compatibleOverlay) overlay.style.position = "absolute";
        $(overlay).css("opacity", options.overlayOpacity).fadeIn(options.overlayFadeDuration);
        position();
        setup(1);

        images = _images;
        return changeImage(startImage);
    };

    /*
        options:    Optional options object, see jQuery.slimbox()
        linkMapper: Optional function taking a link DOM element and an index as arguments and returning an array containing 2 elements:
                the image URL and the image caption (may contain HTML)
        linksFilter:    Optional function taking a link DOM element and an index as arguments and returning true if the element is part of
                the image collection that will be shown on click, false if not. "this" refers to the element that was clicked.
                This function must always return true when the DOM element argument is "this".
    */
    $.fn.slimbox = function(_options, linkMapper, linksFilter) {
        linkMapper = linkMapper || function(el) {
            return [el.pathname, el.title];
        };

        linksFilter = linksFilter || function() {
            return true;
        };

        var links = this;

        return links.unbind("click").click(function() {
            // Build the list of images that will be displayed
            var link = this, startIndex = 0, filteredLinks, i = 0, length;
            filteredLinks = $.grep(links, function(el, i) {
                return linksFilter.call(link, el, i);
            });

            // We cannot use jQuery.map() because it flattens the returned array
            for (length = filteredLinks.length; i < length; ++i) {
                if (filteredLinks[i] == link) startIndex = i;
                filteredLinks[i] = linkMapper(filteredLinks[i], i);
            }

            return $.slimbox(filteredLinks, startIndex, _options);
        });
    };


    /*
        Internal functions
    */

    function position() {
        var l = win.scrollLeft(), w = win.width();
        $(center).css("left", l + (w / 2));
        if (compatibleOverlay) $(overlay).css({left: l, top: win.scrollTop(), width: w, height: win.height()});
    }

    function setup(open) {
        if (open) {
            $("object").add(ie6 ? "select" : "embed").each(function(index, el) {
                hiddenElements[index] = [el, el.style.visibility];
                el.style.visibility = "hidden";
            });
        } else {
            $.each(hiddenElements, function(index, el) {
                el[0].style.visibility = el[1];
            });
            hiddenElements = [];
        }
        var fn = open ? "bind" : "unbind";
        win[fn]("scroll resize", position);
        $(document)[fn]("keydown", keyDown);
    }

    function keyDown(event) {
        var code = event.keyCode, fn = $.inArray;
        // Prevent default keyboard action (like navigating inside the page)
        return (fn(code, options.closeKeys) >= 0) ? close() : false;
    }

    function changeImage(imageIndex) {
        if (imageIndex >= 0) {
            activeImage = imageIndex;
            activeURL = images[activeImage][0];

            stop();
            center.className = "lbLoading";

            preload = new Image();
            preload.onload = animateBox;
            preload.src = activeURL;
        }

        return false;
    }

    function animateBox() {
        center.className = "";
        
        imgWidth = preload.width;
        imgHeight = preload.height;
        var arrayPageSize = getPageSize();
        
        // calculate proportions 
        var imageProportion = imgWidth / imgHeight;
        var winProportion = arrayPageSize[2] / arrayPageSize[3];

        if (imageProportion > winProportion) {
            // calculate max width base on page width
            var maxWidth = arrayPageSize[2] - (10 * 4) - (50 * 2);
            var maxHeight = Math.round(maxWidth / imageProportion);
        } else {
            // calculate max height base on page height
            var maxHeight = arrayPageSize[3] - (10 * 5) - (arrayPageSize[3] / 15) - 50;
            var maxWidth = Math.round(maxHeight * imageProportion);
        }
        if (imgWidth > maxWidth || imgHeight > maxHeight) {
            imgWidth = maxWidth;
            imgHeight = maxHeight;
        }
                
        $(image).css({visibility: "hidden", display: ""});
		$(sizer).attr('src', activeURL);
        $(sizer).width(imgWidth);
        $([sizer]).height(imgHeight);

        centerWidth = image.offsetWidth;
        centerHeight = image.offsetHeight;
        var top = Math.max(0, middle - (centerHeight / 2));
        if (center.offsetHeight != centerHeight) {
            $(center).animate({height: centerHeight, top: top}, options.resizeDuration, options.resizeEasing);
        }
        if (center.offsetWidth != centerWidth) {
            $(center).animate({width: centerWidth, marginLeft: -centerWidth/2}, options.resizeDuration, options.resizeEasing);
        }
        $(center).queue(function() {
            $(image).css({display: "none", visibility: "", opacity: ""}).fadeIn(options.imageFadeDuration);
        });
    }

    function stop() {
        preload.onload = null;
        preload.src = activeURL;
        $([center, image]).stop(true);
        $([image]).hide();
    }

    function close() {
        if (activeImage >= 0) {
            stop();
            activeImage = -1;
            $(center).hide();
            $(overlay).stop().fadeOut(options.overlayFadeDuration, setup);
        }

        return false;
    }

    function getPageSize() {
       
        var xScroll, yScroll;
    
        if (window.innerHeight && window.scrollMaxY) {  
            xScroll = window.innerWidth + window.scrollMaxX;
            yScroll = window.innerHeight + window.scrollMaxY;
        } else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
            xScroll = document.body.scrollWidth;
            yScroll = document.body.scrollHeight;
        } else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
            xScroll = document.body.offsetWidth;
            yScroll = document.body.offsetHeight;
        }
        
        var windowWidth, windowHeight;
        
        if (self.innerHeight) { // all except Explorer
            if(document.documentElement.clientWidth){
                windowWidth = document.documentElement.clientWidth; 
            } else {
                windowWidth = self.innerWidth;
            }
            windowHeight = self.innerHeight;
        } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
            windowWidth = document.documentElement.clientWidth;
            windowHeight = document.documentElement.clientHeight;
        } else if (document.body) { // other Explorers
            windowWidth = document.body.clientWidth;
            windowHeight = document.body.clientHeight;
        }   
        
        // for small pages with total height less then height of the viewport
        if(yScroll < windowHeight){
            pageHeight = windowHeight;
        } else { 
            pageHeight = yScroll;
        }
    
        // for small pages with total width less then width of the viewport
        if(xScroll < windowWidth){  
            pageWidth = xScroll;        
        } else {
            pageWidth = windowWidth;
        }

        return [pageWidth,pageHeight,windowWidth,windowHeight];
    }

})(jQuery);
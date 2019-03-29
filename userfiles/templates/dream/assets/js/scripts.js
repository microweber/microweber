mw.tplDreamExpose = function (name, func) {
    mw.tplDream[name] = func;
}
mw.tplDream = {}
var mr = (function ($, window, document) {
    "use strict";

    var mr = {},
        components = {documentReady: [], documentReadyDeferred: [], windowLoad: [], windowLoadDeferred: []};


    $(document).ready(documentReady);
    $(window).load(windowLoad);

    function documentReady(context) {

        context = typeof context == typeof undefined ? $ : context;
        components.documentReady.concat(components.documentReadyDeferred).forEach(function (component) {
            component(context);
        });
    }

    function windowLoad(context) {

        context = typeof context == "object" ? $ : context;
        components.windowLoad.concat(components.windowLoadDeferred).forEach(function (component) {
            component(context);
        });
    }

    mr.setContext = function (contextSelector) {
        var context = $;
        if (typeof contextSelector !== typeof undefined) {
            return function (selector) {
                return $(contextSelector).find(selector);
            };
        }
        return context;
    };

    mr.components = components;
    mr.documentReady = documentReady;
    mr.windowLoad = windowLoad;

    return mr;
}(jQuery, window, document));


//////////////// Utility Functions
mr = (function (mr, $, window, document) {
    "use strict";
    mr.util = {};

    mr.util.requestAnimationFrame = window.requestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.webkitRequestAnimationFrame ||
        window.msRequestAnimationFrame;

    mr.util.documentReady = function ($) {
        var today = new Date();
        var year = today.getFullYear();
        $('.update-year').text(year);
    };

    mr.util.getURLParameter = function (name) {
        return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [undefined, ""])[1].replace(/\+/g, '%20')) || null;
    };


    mr.util.capitaliseFirstLetter = function (string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    };

    mr.util.slugify = function (text, spacesOnly) {
        if (typeof spacesOnly !== typeof undefined) {
            return text.replace(/ +/g, '');
        } else {
            return text
                .toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        }
    };

    mr.util.sortChildrenByText = function (parentElement, reverse) {
        var $parentElement = $(parentElement);
        var items = $parentElement.children().get();
        var order = -1;
        var order2 = 1;
        if (typeof reverse !== typeof undefined) {
            order = 1;
            order2 = -1;
        }

        items.sort(function (a, b) {
            var keyA = $(a).text();
            var keyB = $(b).text();

            if (keyA < keyB) return order;
            if (keyA > keyB) return order2;
            return 0;
        });

        // Append back into place
        $parentElement.empty();
        $(items).each(function (i, itm) {
            $parentElement.append(itm);
        });
    };

    // Set data-src attribute of element from src to be restored later
    mr.util.idleSrc = function (context, selector) {

        selector = (typeof selector !== typeof undefined) ? selector : '';
        var elems = context.is(selector + '[src]') ? context : context.find(selector + '[src]');

        elems.each(function (index, elem) {
            elem = $(elem);
            var currentSrc = elem.attr('src'),
                dataSrc = elem.attr('data-src');

            // If there is no data-src, save current source to it
            if (typeof dataSrc === typeof undefined) {
                elem.attr('data-src', currentSrc);
            }

            // Clear the src attribute
            elem.attr('src', '');

        });
    };

    // Set src attribute of element from its data-src where it was temporarily stored earlier
    mr.util.activateIdleSrc = function (context, selector) {

        selector = (typeof selector !== typeof undefined) ? selector : '';
        var elems = context.is(selector + '[src]') ? context : context.find(selector + '[src]');

        elems.each(function (index, elem) {
            elem = $(elem);
            var dataSrc = elem.attr('data-src');

            // If there is no data-src, save current source to it
            if (typeof dataSrc !== typeof undefined) {
                elem.attr('src', dataSrc);
            }
        });
    };

    mr.util.pauseVideo = function (context) {
        var elems = context.is('video') ? context : context.find('video');

        elems.each(function (index, video) {
            var playingVideo = $(video).get(0);
            playingVideo.pause();
        });
    };

    mr.components.documentReady.push(mr.util.documentReady);
    return mr;

}(mr, jQuery, window, document));

//////////////// Scroll Functions
mr = (function (mr, $, window, document) {
    "use strict";

    mr.scroll = {};
    mr.scroll.listeners = [];
    mr.scroll.y = 0;
    mr.scroll.x = 0;

    var documentReady = function ($) {

        // Check if scroll-assist is on
        if ($('body').hasClass('scroll-assist')) {
            mr.scroll.assisted = true;
        }

        //////////////// Capture Scroll Event and fire scroll function

        addEventListener('scroll', function (evt) {
            //if(!mr.scroll.assisted){
            window.mr.scroll.y = window.pageYOffset;
            //}
            window.mr.scroll.update(evt);
        }, false);

    };

    mr.scroll.update = function (event) {
        // Loop through all mr scroll listeners
        for (var i = 0, l = mr.scroll.listeners.length; i < l; i++) {
            mr.scroll.listeners[i](event);
        }
    };

    mr.scroll.documentReady = documentReady;

    mr.components.documentReady.push(documentReady);

    return mr;

}(mr, jQuery, window, document));


//////////////// Accordions
mr = (function (mr, $, window, document) {
    "use strict";

    var documentReady = function ($) {
        $('.accordion__title').on('click', function () {
            var accordion = $(this).closest('.accordion');
            var li = $(this).closest('li');
            if (li.hasClass('active')) {
                li.removeClass('active');
            } else {
                if (accordion.hasClass('accordion--oneopen')) {
                    var wasActive = accordion.find('li.active');
                    wasActive.removeClass('active');
                    li.addClass('active');
                } else {
                    li.addClass('active');
                }
            }
        });

        $('.accordion').each(function () {
            var accordion = $(this);
            var minHeight = accordion.outerHeight(true);
            accordion.css('min-height', minHeight);
        });
    };

    mr.accordions = {
        documentReady: documentReady
    };

    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));


//////////////// Backgrounds
mr = (function (mr, $, window, document) {
    "use strict";

    var documentReady = function ($) {

        //////////////// Append .background-image-holder <img>'s as CSS backgrounds

        $('.background-image-holder').each(function () {
            var imgSrc = $(this).children('img').attr('src');
            // $(this).css('background-image', 'url("' + imgSrc + '")').css('background-position', 'initial').css('opacity','1');
            $(this).css('background-position', 'initial').css('opacity', '1');
        });
    };

    mr.backgrounds = {
        documentReady: documentReady
    };

    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));

//////////////// Cookies
mr = (function (mr, $, window, document) {
    "use strict";

    mr.cookies = {

        getItem: function (sKey) {
            if (!sKey) {
                return null;
            }
            return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
        },
        setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure) {
            if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) {
                return false;
            }
            var sExpires = "";
            if (vEnd) {
                switch (vEnd.constructor) {
                    case Number:
                        sExpires = vEnd === Infinity ? "; expires=Fri, 31 Dec 9999 23:59:59 GMT" : "; max-age=" + vEnd;
                        break;
                    case String:
                        sExpires = "; expires=" + vEnd;
                        break;
                    case Date:
                        sExpires = "; expires=" + vEnd.toUTCString();
                        break;
                }
            }
            document.cookie = encodeURIComponent(sKey) + "=" + encodeURIComponent(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
            return true;
        },
        removeItem: function (sKey, sPath, sDomain) {
            if (!this.hasItem(sKey)) {
                return false;
            }
            document.cookie = encodeURIComponent(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "");
            return true;
        },
        hasItem: function (sKey) {
            if (!sKey) {
                return false;
            }
            return (new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
        },
        keys: function () {
            var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
            for (var nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) {
                aKeys[nIdx] = decodeURIComponent(aKeys[nIdx]);
            }
            return aKeys;
        }
    };

    return mr;

}(mr, jQuery, window, document));

//////////////// Forms
mr = (function (mr, $, window, document) {
    "use strict";

    mr.forms = {};

    var documentReady = function ($) {

        //////////////// Checkbox Inputs

        $('.input-checkbox').on('click', function () {
            var checkbox = $(this);
            checkbox.toggleClass('checked');

            var input = checkbox.find('input');
            if (input.prop('checked') === false) {
                input.prop('checked', true);
            } else {
                input.prop('checked', false);
            }
            return false;
        });

        //////////////// Radio Buttons

        $('.input-radio').on('click', function () {
            var radio = $(this);
            radio.closest('form').find('.input-radio').removeClass('checked');
            radio.addClass('checked').find('input').prop('checked', true);
            return false;
        });

        //////////////// File Uploads

        $('.input-file .btn').on('click', function () {
            $(this).siblings('input').trigger('click');
            return false;
        });

        //////////////// Handle Form Submit

        $('form.form-email, form[action*="list-manage.com"], form[action*="createsend.com"]').attr('novalidate', true).unbind('submit').on('submit', mr.forms.submit);

        //////////////// Handle Form Submit
        $(document).on('change, input, paste, keyup', '.attempted-submit .field-error', function () {
            $(this).removeClass('field-error');
        });

    };

    mr.forms.documentReady = documentReady;

    mr.forms.submit = function (e) {
        // return false so form submits through jQuery rather than reloading page.
        if (e.preventDefault) e.preventDefault();
        else e.returnValue = false;

        var body = $('body'),
            thisForm = $(e.target).closest('form'),
            formAction = typeof thisForm.attr('action') !== typeof undefined ? thisForm.attr('action') : "",
            submitButton = thisForm.find('button[type="submit"], input[type="submit"]'),
            error = 0,
            originalError = thisForm.attr('original-error'),
            successRedirect, formError, formSuccess, errorText, successText;

        body.find('.form-error, .form-success').remove();
        submitButton.attr('data-text', submitButton.text());
        errorText = thisForm.attr('data-error') ? thisForm.attr('data-error') : "Please fill all fields correctly";
        successText = thisForm.attr('data-success') ? thisForm.attr('data-success') : "Thanks, we'll be in touch shortly";
        body.append('<div class="form-error" style="display: none;">' + errorText + '</div>');
        body.append('<div class="form-success" style="display: none;">' + successText + '</div>');
        formError = body.find('.form-error');
        formSuccess = body.find('.form-success');
        thisForm.addClass('attempted-submit');

        // Do this if the form is intended to be submitted to MailChimp or Campaign Monitor
        if (formAction.indexOf('createsend.com') !== -1 || formAction.indexOf('list-manage.com') !== -1) {

            console.log('Mail list form signup detected.');
            if (typeof originalError !== typeof undefined && originalError !== false) {
                formError.html(originalError);
            }

            // validateFields returns 1 on error;
            if (mr.forms.validateFields(thisForm) !== 1) {

                thisForm.removeClass('attempted-submit');

                // Hide the error if one was shown
                formError.fadeOut(200);
                // Create a new loading spinner in the submit button.
                submitButton.addClass('btn--loading');

                try {
                    $.ajax({
                        url: thisForm.attr('action'),
                        crossDomain: true,
                        data: thisForm.serialize(),
                        method: "GET",
                        cache: false,
                        dataType: 'json',
                        contentType: 'application/json; charset=utf-8',
                        success: function (data) {
                            // Request was a success, what was the response?

                            if (data.result !== "success" && data.Status !== 200) {

                                // Got an error from Mail Chimp or Campaign Monitor

                                // Keep the current error text in a data attribute on the form
                                formError.attr('original-error', formError.text());
                                // Show the error with the returned error text.
                                formError.html(data.msg).stop(true).fadeIn(1000);
                                formSuccess.stop(true).fadeOut(1000);

                                submitButton.removeClass('btn--loading');
                            } else {

                                // Got success from Mail Chimp or Campaign Monitor

                                submitButton.removeClass('btn--loading');

                                successRedirect = thisForm.attr('data-success-redirect');
                                // For some browsers, if empty `successRedirect` is undefined; for others,
                                // `successRedirect` is false.  Check for both.
                                if (typeof successRedirect !== typeof undefined && successRedirect !== false && successRedirect !== "") {
                                    window.location = successRedirect;
                                } else {
                                    mr.forms.resetForm(thisForm);
                                    mr.forms.showFormSuccess(formSuccess, formError, 1000, 5000, 500);
                                }
                            }
                        }
                    });
                } catch (err) {
                    // Keep the current error text in a data attribute on the form
                    formError.attr('original-error', formError.text());
                    // Show the error with the returned error text.
                    formError.html(err.message);
                    mr.forms.showFormError(formSuccess, formError, 1000, 5000, 500);

                    submitButton.removeClass('btn--loading');
                }


            } else {
                // There was a validation error - show the default form error message
                mr.forms.showFormError(formSuccess, formError, 1000, 5000, 500);
            }
        } else {
            // If no MailChimp or Campaign Monitor form was detected then this is treated as an email form instead.
            if (typeof originalError !== typeof undefined && originalError !== false) {
                formError.text(originalError);
            }

            error = mr.forms.validateFields(thisForm);

            if (error === 1) {
                mr.forms.showFormError(formSuccess, formError, 1000, 5000, 500);
            } else {

                thisForm.removeClass('attempted-submit');

                // Hide the error if one was shown
                formError.fadeOut(200);

                // Create a new loading spinner in the submit button.
                submitButton.addClass('btn--loading');

                jQuery.ajax({
                    type: "POST",
                    url: "mail/mail.php",
                    data: thisForm.serialize() + "&url=" + window.location.href,
                    success: function (response) {
                        // Swiftmailer always sends back a number representing number of emails sent.
                        // If this is numeric (not Swift Mailer error text) AND greater than 0 then show success message.

                        submitButton.removeClass('btn--loading');

                        if ($.isNumeric(response)) {
                            if (parseInt(response, 10) > 0) {
                                // For some browsers, if empty 'successRedirect' is undefined; for others,
                                // 'successRedirect' is false.  Check for both.
                                successRedirect = thisForm.attr('data-success-redirect');
                                if (typeof successRedirect !== typeof undefined && successRedirect !== false && successRedirect !== "") {
                                    window.location = successRedirect;
                                }

                                mr.forms.resetForm(thisForm);
                                mr.forms.showFormSuccess(formSuccess, formError, 1000, 5000, 500);
                            }
                        }
                        // If error text was returned, put the text in the .form-error div and show it.
                        else {
                            // Keep the current error text in a data attribute on the form
                            formError.attr('original-error', formError.text());
                            // Show the error with the returned error text.
                            formError.text(response).stop(true).fadeIn(1000);
                            formSuccess.stop(true).fadeOut(1000);
                        }
                    },
                    error: function (errorObject, errorText, errorHTTP) {
                        // Keep the current error text in a data attribute on the form
                        formError.attr('original-error', formError.text());
                        // Show the error with the returned error text.
                        formError.text(errorHTTP).stop(true).fadeIn(1000);
                        formSuccess.stop(true).fadeOut(1000);
                        submitButton.removeClass('btn--loading');
                    }
                });
            }
        }
        return false;
    };

    mr.forms.validateFields = function (form) {
        var body = $(body), name, error, originalErrorMessage;

        $(form).find('.validate-required[type="checkbox"]').each(function () {
            if (!$('[name="' + $(this).attr('name') + '"]:checked').length) {
                error = 1;
                name = $(this).attr('name').replace('[]', '');
                body.find('.form-error').text('Please tick at least one ' + name + ' box.');
            }
        });

        $(form).find('.validate-required, .required, [required]').each(function () {
            if ($(this).val() === '') {
                $(this).addClass('field-error');
                error = 1;
            } else {
                $(this).removeClass('field-error');
            }
        });

        $(form).find('.validate-email, .email, [name*="cm-"][type="email"]').each(function () {
            if (!(/(.+)@(.+){2,}\.(.+){2,}/.test($(this).val()))) {
                $(this).addClass('field-error');
                error = 1;
            } else {
                $(this).removeClass('field-error');
            }
        });

        if (!form.find('.field-error').length) {
            body.find('.form-error').fadeOut(1000);
        } else {

            var firstError = $(form).find('.field-error:first');

            if (firstError.length) {
                $('html, body').stop(true).animate({
                    scrollTop: (firstError.offset().top - 100)
                }, 1200, function () {
                    firstError.focus();
                });
            }
        }

        return error;
    };

    mr.forms.showFormSuccess = function (formSuccess, formError, fadeOutError, wait, fadeOutSuccess) {

        formSuccess.stop(true).fadeIn(fadeOutError);

        formError.stop(true).fadeOut(fadeOutError);
        setTimeout(function () {
            formSuccess.stop(true).fadeOut(fadeOutSuccess);
        }, wait);
    };

    mr.forms.showFormError = function (formSuccess, formError, fadeOutSuccess, wait, fadeOutError) {

        formError.stop(true).fadeIn(fadeOutSuccess);

        formSuccess.stop(true).fadeOut(fadeOutSuccess);
        setTimeout(function () {
            formError.stop(true).fadeOut(fadeOutError);
        }, wait);
    };

    // Reset form to empty/default state.
    mr.forms.resetForm = function (form) {
        form = $(form);
        form.get(0).reset();
        form.find('.input-radio, .input-checkbox').removeClass('checked');

    };

    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));

//////////////// Maps
mr = (function (mr, $, window, document) {
    "use strict";

    mr.maps = {};

    var documentReady = function ($) {
        // Interact with Map once the user has clicked (to prevent scrolling the page = zooming the map

        $('.map-holder').on('click', function () {
            $(this).addClass('interact');
        }).removeClass('interact');

        mr.maps.initAPI();
        mr.maps.init();

    };
    mr.maps.documentReady = documentReady;

    mr.maps.initAPI = function () {
        // Load Google MAP API JS with callback to initialise when fully loaded
        if (document.querySelector('[data-maps-api-key]') && !document.querySelector('.gMapsAPI')) {
            if ($('[data-maps-api-key]').length) {
                var script = document.createElement('script');
                var apiKey = $('[data-maps-api-key]:first').attr('data-maps-api-key');
                apiKey = typeof apiKey != typeof undefined ? apiKey : '';
                if (apiKey !== '') {
                    script.type = 'text/javascript';
                    script.src = 'https://maps.googleapis.com/maps/api/js?key=' + apiKey + '&callback=mr.maps.init';
                    script.className = 'gMapsAPI';
                    document.body.appendChild(script);
                }
            }
        }
    };

    mr.maps.init = function () {
        if (typeof window.google !== "undefined") {
            if (typeof window.google.maps !== "undefined") {
                $('.map-container[data-maps-api-key]').each(function () {
                    var mapElement = this,
                        mapInstance = $(this),
                        mapJSON = typeof mapInstance.attr('data-map-style') !== typeof undefined ? mapInstance.attr('data-map-style') : false,
                        mapStyle = JSON.parse(mapJSON) || [{"featureType": "landscape", "stylers": [{"saturation": -100}, {"lightness": 65}, {"visibility": "on"}]}, {
                                "featureType": "poi",
                                "stylers": [{"saturation": -100}, {"lightness": 51}, {"visibility": "simplified"}]
                            }, {"featureType": "road.highway", "stylers": [{"saturation": -100}, {"visibility": "simplified"}]}, {"featureType": "road.arterial", "stylers": [{"saturation": -100}, {"lightness": 30}, {"visibility": "on"}]}, {
                                "featureType": "road.local",
                                "stylers": [{"saturation": -100}, {"lightness": 40}, {"visibility": "on"}]
                            }, {"featureType": "transit", "stylers": [{"saturation": -100}, {"visibility": "simplified"}]}, {"featureType": "administrative.province", "stylers": [{"visibility": "off"}]}, {
                                "featureType": "water",
                                "elementType": "labels",
                                "stylers": [{"visibility": "on"}, {"lightness": -25}, {"saturation": -100}]
                            }, {"featureType": "water", "elementType": "geometry", "stylers": [{"hue": "#ffff00"}, {"lightness": -25}, {"saturation": -97}]}],
                        zoomLevel = (typeof mapInstance.attr('data-map-zoom') !== typeof undefined && mapInstance.attr('data-map-zoom') !== "") ? mapInstance.attr('data-map-zoom') * 1 : 17,
                        latlong = typeof mapInstance.attr('data-latlong') !== typeof undefined ? mapInstance.attr('data-latlong') : false,
                        latitude = latlong ? 1 * latlong.substr(0, latlong.indexOf(',')) : false,
                        longitude = latlong ? 1 * latlong.substr(latlong.indexOf(",") + 1) : false,
                        geocoder = new google.maps.Geocoder(),
                        address = typeof mapInstance.attr('data-address') !== typeof undefined ? mapInstance.attr('data-address').split(';') : [""],
                        markerImage = typeof mapInstance.attr('data-marker-image') !== typeof undefined ? mapInstance.attr('data-marker-image') : 'img/mapmarker.png',
                        markerTitle = "We Are Here",
                        isDraggable = $(document).width() > 766 ? true : false,
                        map, marker,
                        mapOptions = {
                            draggable: isDraggable,
                            scrollwheel: false,
                            zoom: zoomLevel,
                            disableDefaultUI: true,
                            styles: mapStyle
                        };

                    if (typeof mapInstance.attr('data-marker-title') !== typeof undefined && mapInstance.attr('data-marker-title') !== "") {
                        markerTitle = mapInstance.attr('data-marker-title');
                    }

                    if (address !== undefined && address[0] !== "") {
                        geocoder.geocode({'address': address[0].replace('[nomarker]', '')}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                var map = new google.maps.Map(mapElement, mapOptions);
                                map.setCenter(results[0].geometry.location);

                                address.forEach(function (address) {
                                    var markerGeoCoder;

                                    markerImage = {url: typeof window.mr_variant === typeof undefined ? markerImage : '../img/mapmarker.png', scaledSize: new google.maps.Size(50, 50)};
                                    if (/(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)/.test(address)) {
                                        var latlong = address.split(','),
                                            marker = new google.maps.Marker({
                                                position: {lat: 1 * latlong[0], lng: 1 * latlong[1]},
                                                map: map,
                                                icon: markerImage,
                                                title: markerTitle,
                                                optimised: false
                                            });
                                    }
                                    else if (address.indexOf('[nomarker]') < 0) {
                                        markerGeoCoder = new google.maps.Geocoder();
                                        markerGeoCoder.geocode({'address': address.replace('[nomarker]', '')}, function (results, status) {
                                            if (status === google.maps.GeocoderStatus.OK) {
                                                marker = new google.maps.Marker({
                                                    map: map,
                                                    icon: markerImage,
                                                    title: markerTitle,
                                                    position: results[0].geometry.location,
                                                    optimised: false
                                                });
                                            }
                                            else {
                                                console.log('Map marker error: ' + status);
                                            }
                                        });
                                    }

                                });
                            } else {
                                console.log('There was a problem geocoding the address.');
                            }
                        });
                    }
                    else if (typeof latitude !== typeof undefined && latitude !== "" && latitude !== false && typeof longitude !== typeof undefined && longitude !== "" && longitude !== false) {
                        mapOptions.center = {lat: latitude, lng: longitude};
                        map = new google.maps.Map(mapInstance, mapOptions);
                        marker = new google.maps.Marker({
                            position: {lat: latitude, lng: longitude},
                            map: map,
                            icon: markerImage,
                            title: markerTitle
                        });

                    }

                });
            }
        }
    };

    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));


//////////////// Masonry
mr = (function (mr, $, window, document) {
    "use strict";

    var documentReady = function ($) {

        $('.masonry').each(function () {
            var masonry = $(this);
            var masonryContainer = masonry.find('.masonry__container'),
                filters = masonry.find('.masonry__filters'),
                // data-filter-all-text can be used to set the word for "all"
                filterAllText = typeof filters.attr('data-filter-all-text') !== typeof undefined ? filters.attr('data-filter-all-text') : "All",
                filtersList;

            // If a filterable masonry item exists
            if (masonryContainer.find('.masonry__item[data-masonry-filter]').length) {

                // Create empty ul for filters
                filters.append('<ul></ul>');
                filtersList = filters.find('> ul');

                // To avoid cases where user leave filter attribute blank
                // only take items that have filter attribute
                masonryContainer.find('.masonry__item[data-masonry-filter]').each(function () {
                    var masonryItem = $(this),
                        filterString = masonryItem.attr('data-masonry-filter'),
                        filtersArray = [];

                    // If not undefined or empty
                    if (typeof filterString !== typeof undefined && filterString !== "") {
                        // Split tags from string into array
                        filtersArray = filterString.split(',');
                    }
                    jQuery(filtersArray).each(function (index, tag) {

                        // Slugify the tag

                        var slug = mr.util.slugify(tag, true);

                        // Add the filter class to the masonry item

                        masonryItem.addClass('filter-' + slug);

                        // If this tag does not appear in the list already, add it
                        if (!filtersList.find('[data-masonry-filter="' + slug + '"]').length) {
                            filtersList.append('<li data-masonry-filter="' + slug + '">' + tag + '</li>');

                        }
                    });
                });

                mr.util.sortChildrenByText($(this).find('.masonry__filters ul'));
                // Add a filter "all" option
                filtersList.prepend('<li class="active" data-masonry-filter="*">' + filterAllText + '</li>');

            }
            //End of "if filterable masonry item exists"
        });

    };

    var windowLoad = function () {

        $('.masonry').each(function () {
            var masonry = $(this).find('.masonry__container');

            masonry.on('layoutComplete', function () {
                masonry.addClass('masonry--active');
            });

            masonry.isotope({
                itemSelector: '.masonry__item',
                masonry: {
                    columnWidth: '.masonry__item'
                }
            });
        });

        $('.masonry__filters li').on('click', function () {
            var masonryFilter = $(this);
            var masonryContainer = masonryFilter.closest('.masonry').find('.masonry__container');
            var filterValue = '*';
            if (masonryFilter.attr('data-masonry-filter') !== '*') {
                filterValue = '.filter-' + masonryFilter.attr('data-masonry-filter');
            }
            masonryFilter.siblings('li').removeClass('active');
            masonryFilter.addClass('active');
            masonryContainer.removeClass('masonry--animate');
            masonryContainer.isotope({filter: filterValue});
        });
    };

    mr.masonry = {
        documentReady: documentReady,
        windowLoad: windowLoad
    };

    mr.components.documentReady.push(documentReady);
    mr.components.windowLoad.push(windowLoad);
    return mr;

}(mr, jQuery, window, document));

//////////////// Modals
mr = (function (mr, $, window, document) {
    "use strict";

    mr.modals = {};

    var documentReady = function ($) {
        $('.modal-container').each(function () {

            // Add modal close if none exists

            var modal = $(this),
                $window = $(window),
                modalContent = modal.find('.modal-content');


            if (!modal.find('.modal-close').length) {
                modal.find('.modal-content').append('<div class="modal-close modal-close-cross"></div>');
            }

            // Set modal height

            if (modalContent.attr('data-width') !== undefined) {
                var modalWidth = modalContent.attr('data-width').substr(0, modalContent.attr('data-width').indexOf('%')) * 1;
                if ($window.width() < 1280 && $window.width() > 990) {
                    modalWidth = modalWidth + 15;
                } else if ($window.width() < 990) {
                    modalWidth = modalWidth + 20;
                }
                modalContent.css('width', modalWidth + '%');
            }
            if (modalContent.attr('data-height') !== undefined) {
                var modalHeight = modalContent.attr('data-height').substr(0, modalContent.attr('data-height').indexOf('%')) * 1;
                if ($window.height() < 768) {
                    modalHeight = modalHeight + 15;
                }
                modalContent.css('height', modalHeight + '%');
            }

            // Set iframe's src to data-src to stop autoplaying iframes
            mr.util.idleSrc(modal, 'iframe');

        });

        if (typeof mr_variant == typeof undefined) {
            $('.modal-instance').each(function (index) {
                var modalInstance = $(this);
                var modal = modalInstance.find('.modal-container');
                var modalContent = modalInstance.find('.modal-content');
                var trigger = modalInstance.find('.modal-trigger');

                // Link modal with modal-id attribute

                trigger.attr('data-modal-index', index);
                modal.attr('data-modal-index', index);

                // Set unique id for multiple triggers

                if (typeof modal.attr('data-modal-id') !== typeof undefined) {
                    trigger.attr('data-modal-id', modal.attr('data-modal-id'));
                }


                // Attach the modal to the body
                modal = modal.detach();
                $('body').append(modal);
            });
        }

        $('.modal-trigger').on('click', function () {

            var modalTrigger = $(this);
            var $body = $('body');
            var uniqueID, targetModal;
            // Determine if the modal id is set by user or is set programatically

            if (typeof modalTrigger.attr('data-modal-id') !== typeof undefined) {
                uniqueID = modalTrigger.attr('data-modal-id');
                targetModal = $body.find('.modal-container[data-modal-id="' + uniqueID + '"]');
            } else {
                uniqueID = $(this).attr('data-modal-index');
                targetModal = $body.find('.modal-container[data-modal-index="' + uniqueID + '"]');
            }

            mr.util.activateIdleSrc(targetModal, 'iframe');
            mr.modals.autoplayVideo(targetModal);

            mr.modals.showModal(targetModal);

            return false;
        });

        $('.modal-close').on('click', mr.modals.closeActiveModal);

        $(document).keyup(function (e) {
            if (e.keyCode == 27) { // escape key maps to keycode `27`
                mr.modals.closeActiveModal();
            }
        });

        $('.modal-container').on('click', function (e) {
            if (e.target != this) return;
            mr.modals.closeActiveModal();
        });

        // Trigger autoshow modals
        $('.modal-container[data-autoshow]').each(function () {
            var modal = $(this);
            var millisecondsDelay = modal.attr('data-autoshow') * 1;

            mr.util.activateIdleSrc(modal);
            mr.modals.autoplayVideo(modal);

            // If this modal has a cookie attribute, check to see if a cookie is set, and if so, don't show it.
            if (typeof modal.attr('data-cookie') !== typeof undefined) {
                if (!mr.cookies.hasItem(modal.attr('data-cookie'))) {
                    mr.modals.showModal(modal, millisecondsDelay);
                }
            } else {
                mr.modals.showModal(modal, millisecondsDelay);
            }
        });

        // Autoshow modal by ID from location href
        if (window.location.href.split('#').length == 2) {
            var modalID = window.location.href.split('#').pop();
            if ($('[data-modal-id="' + modalID + '"]').length) {
                mr.modals.closeActiveModal();
                mr.modals.showModal($('[data-modal-id="' + modalID + '"]'));
            }
        }

        // Make modal scrollable
        $(document).on('wheel mousewheel scroll', '.modal-content, .modal-content .scrollable', function (evt) {
            if (evt.preventDefault) {
                evt.preventDefault();
            }
            if (evt.stopPropagation) {
                evt.stopPropagation();
            }
            this.scrollTop += (evt.originalEvent.deltaY);
        });
    };
    ////////////////
    //////////////// End documentReady
    ////////////////

    mr.modals.documentReady = documentReady;

    mr.modals.showModal = function (modal, millisecondsDelay) {

        var delay = (typeof millisecondsDelay !== typeof undefined) ? (1 * millisecondsDelay) : 0;

        setTimeout(function () {
            modal.addClass('modal-active');
        }, delay);
    };

    mr.modals.closeActiveModal = function () {
        var modal = $('body div.modal-active');
        mr.util.idleSrc(modal, 'iframe');
        mr.util.pauseVideo(modal);

        // If this modal requires to be closed permanently using a cookie, set the cookie now.
        if (typeof modal.attr('data-cookie') !== typeof undefined) {
            mr.cookies.setItem(modal.attr('data-cookie'), "true", Infinity);
        }

        modal.removeClass('modal-active');
    };

    mr.modals.autoplayVideo = function (modal) {
        // If modal contains HTML5 video with autoplay, play the video
        if (modal.find('video[autoplay]').length) {
            var video = modal.find('video').get(0);
            video.play();
        }
    };

    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));

//////////////// Navigation
mr = (function (mr, $, window, document) {
    "use strict";

    // The navigation object
    mr.navigation = {};

    // The overall nav element (one per page)
    mr.navigation.nav = {};

    // In case there is a bar type nav element
    mr.navigation.bar = {};

    var documentReady = function ($) {

        mr.navigation.nav.element = $('nav');
        mr.navigation.bar.element = $('nav .nav-bar');

        // Check for nav element and set outerHeight variable
        if (mr.navigation.nav.element.length) {
            mr.navigation.nav.outerHeight = mr.navigation.nav.element.outerHeight();
        } else {
            mr.navigation.nav.outerHeight = 0;
        }
        // Check for a bar type nav
        if (mr.navigation.bar.element.length) {
            mr.navigation.bar.init();
        }

        //////////////// Mobile Menu Toggle

        $('.nav-mobile-toggle').on('click', function () {
            $('nav').toggleClass('nav-open');
        });

        $('.menu li').on('click', function (ev) {
            var navItem = $(this),
                e = ev || window.event;

            e.stopPropagation();
            if (navItem.find('ul').length) {
                navItem.toggleClass('active');
            } else {
                navItem.parents('.active').removeClass('active');
            }
        });

        //////////////// Mobile Menu Applets

        $('.module-applet').on('click', function () {
            $(this).toggleClass('active');
        });

        $('.module-applet__body').each(function () {
            var moduleBody = $(this);
            var farRight = moduleBody.offset().left + moduleBody.outerWidth();
            if (farRight > $(window).width()) {
                moduleBody.addClass('pos-right');
            }
        });

        //////////////// Menu dropdown positioning

        $('.menu > li > ul').each(function () {
            var $window = $(window);
            var dropDown = $(this);
            var menu = dropDown.offset();
            var farRight = menu.left + dropDown.outerWidth(true);
            var windowWidth = $window.width();
            var multiColumn = dropDown.hasClass('multi-column');

            if (farRight > windowWidth && !multiColumn) {
                dropDown.addClass('make-right');
            } else if (farRight > windowWidth && multiColumn) {
                var difference = farRight - windowWidth;
                dropDown.css('margin-left', -(difference));
            }
        });

    };

    ///
    ///    END DOCUMENTREADY
    ///
    ////////////////////////////////////

    mr.navigation.bar.init = function () {
        // Get data-fixed-at attribute
        var fixedAt = mr.navigation.bar.element.attr('data-fixed-at');
        // Save mr.navigation.bar.fixedAt as a number or null if not set
        mr.navigation.bar.fixedAt = (typeof fixedAt !== typeof undefined) ? parseInt(fixedAt.replace('px', ''), 10) : false;

        // Only run scroll listeners if bar does not already have nav--fixed class
        if (mr.navigation.bar.element.hasClass('nav--fixed')) {
            // We know this is a fixed nav bar
            mr.navigation.bar.isFixed = true;
        } else if (fixedAt) {
            // If fixedAt has a value (not false) and nav bar has no ".nav--fixed" class
            // add navigation.bar.update to scroll event cycle
            // mr.navigation.nav.element.css('min-height', mr.navigation.nav.outerHeight);
            mr.navigation.bar.isFixed = false;
            mr.scroll.listeners.push(mr.navigation.bar.update);
        }


    };

    mr.navigation.bar.update = function () {
        // If page is scrolled beyond the point where nav should be fixed
        if ((mr.scroll.y > mr.navigation.bar.fixedAt) && !mr.navigation.bar.isFixed) {
            mr.navigation.bar.isFixed = true;
            mr.navigation.bar.element.addClass('nav--fixed');
        }

        if ((mr.scroll.y < mr.navigation.bar.fixedAt) && mr.navigation.bar.isFixed) {
            mr.navigation.bar.isFixed = false;
            mr.navigation.bar.element.removeClass('nav--fixed');
        }
    };

    mr.navigation.documentReady = documentReady;

    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));

//////////////// Newsletter Providers
mr = (function (mr, $, window, document) {
    "use strict";

    mr.newsletters = {};

    var documentReady = function ($) {

        var form, checkbox, label, id, parent, radio;

        // Treat Campaign Monitor forms
        $('form[action*="createsend.com"]').each(function () {
            form = $(this);

            // Override browser validation and allow us to use our own
            form.attr('novalidate', 'novalidate');

            // Give each text input a placeholder value

            if (!form.is('.form--no-placeholders')) {
                form.find('input:not([checkbox]):not([radio])').each(function () {
                    var $input = $(this);
                    if (typeof $input.attr('placeholder') !== typeof undefined) {
                        if ($input.attr('placeholder') === "") {
                            if ($input.siblings('label').length) {
                                $input.attr('placeholder', $input.siblings('label').first().text());
                                if (form.is('.form--no-labels')) {
                                    $input.siblings('label').first().remove();
                                }
                            }
                        }
                    } else if ($input.siblings('label').length) {
                        $input.attr('placeholder', $input.siblings('label').first().text());
                        if (form.is('.form--no-labels')) {
                            $input.siblings('label').first().remove();
                        }
                    }
                    if ($input.parent().is('p')) {
                        $input.unwrap();
                    }
                });
            } else {
                form.find('input[placeholder]').removeAttr('placeholder');
            }


            // Wrap select elements in template code

            form.find('select').wrap('<div class="input-select"></div>');

            // Wrap radios elements in template code

            form.find('input[type="radio"]').wrap('<div class="input-radio"></div>');

            // Wrap checkbox elements in template code

            form.find('input[type="checkbox"]').each(function () {
                checkbox = $(this);
                id = checkbox.attr('id');
                label = form.find('label[for=' + id + ']');

                checkbox.before('<div class="input-checkbox" data-id="' + id + '"></div>');
                $('.input-checkbox[data-id="' + id + '"]').prepend(checkbox);
                $('.input-checkbox[data-id="' + id + '"]').prepend(label);
                $('.input-checkbox[data-id="' + id + '"]').prepend('<div class="inner"></div>');
            });

            form.find('button[type="submit"]').each(function () {
                var button = $(this);
                button.addClass('btn');
                if (button.parent().is('p')) {
                    button.unwrap();
                }
            });

            form.find('[required]').removeAttr('required').addClass('validate-required');

            form.addClass('form--active');

            mr.newsletters.prepareAjaxAction(form);

        });

        // Treat MailChimp forms
        $('form[action*="list-manage.com"]').each(function () {
            form = $(this);

            // Override browser validation and allow us to use our own
            form.attr('novalidate', 'novalidate');

            // Give each text input a placeholder value
            if (!form.is('.form--no-placeholders')) {
                form.find('input:not([checkbox]):not([radio])').each(function () {
                    var $input = $(this);
                    if (typeof $input.attr('placeholder') !== typeof undefined) {
                        if ($input.attr('placeholder') === "") {
                            if ($input.siblings('label').length) {
                                $input.attr('placeholder', $input.siblings('label').first().text());
                                if (form.is('.form--no-labels')) {
                                    $input.siblings('label').first().remove();
                                }
                            }
                        }
                    } else if ($input.siblings('label').length) {
                        $input.attr('placeholder', $input.siblings('label').first().text());
                        if (form.is('.form--no-labels')) {
                            $input.siblings('label').first().remove();
                        }
                    }
                });
            } else {
                form.find('input[placeholder]').removeAttr('placeholder');
            }

            if (form.is('.form--no-labels')) {
                form.find('input:not([checkbox]):not([radio])').each(function () {
                    var $input = $(this);
                    if ($input.siblings('label').length) {
                        $input.siblings('label').first().remove();
                    }
                });
            }

            // Wrap select elements in template code

            form.find('select').wrap('<div class="input-select"></div>');

            // Wrap checboxes elements in template code

            form.find('input[type="checkbox"]').each(function () {
                checkbox = $(this);
                parent = checkbox.closest('li');
                label = parent.find('label');
                checkbox.before('<div class="input-checkbox"><div class="inner"></div></div>');
                parent.find('.input-checkbox').prepend(checkbox);
                parent.find('.input-checkbox').prepend(label);
            });

            // Wrap radio elements in template code

            form.find('input[type="radio"]').each(function () {
                radio = $(this);
                parent = radio.closest('li');
                label = parent.find('label');
                radio.before('<div class="input-radio"><div class="inner"></div></div>');
                parent.find('.input-radio').prepend(radio);
                parent.find('.input-radio').prepend(label);
            });

            // Convert MailChimp input[type="submit"] to div.button

            form.find('input[type="submit"]').each(function () {
                var submit = $(this);

                var newButton = jQuery('<button/>').attr('type', 'submit').attr('class', submit.attr('class')).addClass('btn').text(submit.attr('value'));

                if (submit.parent().is('div.clear')) {
                    submit.unwrap();
                }

                newButton.insertBefore(submit);
                submit.remove();
            });

            form.find('input').each(function () {
                var input = $(this);
                if (input.hasClass('required')) {
                    input.removeClass('required').addClass('validate-required');
                }
            });

            form.find('input[type="email"]').removeClass('email').addClass('validate-email');

            form.find('#mce-responses').remove();

            form.find('.mc-field-group').each(function () {
                $(this).children().first().unwrap();
            });

            form.find('[required]').removeAttr('required').addClass('validate-required');

            form.addClass('form--active');

            mr.newsletters.prepareAjaxAction(form);


        });

        // Reinitialize the forms so interactions work as they should

        mr.forms.documentReady(mr.setContext('form.form--active'));

    };

    mr.newsletters.documentReady = documentReady;

    mr.newsletters.prepareAjaxAction = function (form) {
        var action = $(form).attr('action');

        // Alter action for a Mail Chimp-compatible ajax request url.
        if (/list-manage\.com/.test(action)) {
            action = action.replace('/post?', '/post-json?') + "&c=?";
            if (action.substr(0, 2) == "//") {
                action = 'http:' + action;
            }
        }

        // Alter action for a Campaign Monitor-compatible ajax request url.
        if (/createsend\.com/.test(action)) {
            action = action + '?callback=?';
        }

        // Set action on the form
        $(form).attr('action', action);

    };


    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));

//////////////// Notifications
mr = (function (mr, $, window, document) {
    "use strict";

    mr.notifications = {};

    var documentReady = function ($) {

        $('.notification').each(function () {
            var notification = $(this);
            if (!notification.find('.notification-close').length) {
                notification.append('<div class="notification-close-cross notification-close"></div>');
            }
        });


        $('.notification[data-autoshow]').each(function () {
            var notification = $(this);
            var millisecondsDelay = notification.attr('data-autoshow') * 1;

            // If this notification has a cookie attribute, check to see if a cookie is set, and if so, don't show it.
            if (typeof notification.attr('data-cookie') !== typeof undefined) {
                if (!mr.cookies.hasItem(notification.attr('data-cookie'))) {
                    mr.notifications.showNotification(notification, millisecondsDelay);
                }
            } else {
                mr.notifications.showNotification(notification, millisecondsDelay);
            }
        });

        $('[data-notification-link]:not(.notification)').on('click', function () {
            var notificationID = $(this).attr('data-notification-link');
            var notification = $('body').find('.notification[data-notification-link="' + notificationID + '"]');
            notification.removeClass('notification--dismissed');
            mr.notifications.showNotification(notification, 0);
            return false;
        });

        $('.notification-close').on('click', function () {
            var closeButton = $(this);
            // Pass the closeNotification function a reference to the close button
            mr.notifications.closeNotification(closeButton);

            if (closeButton.attr('href') === '#') {
                return false;
            }
        });

    };

    mr.notifications.documentReady = documentReady;

    mr.notifications.showNotification = function (notification, millisecondsDelay) {
        var delay = (typeof millisecondsDelay !== typeof undefined) ? (1 * millisecondsDelay) : 0;
        setTimeout(function () {
            notification.addClass('notification--reveal');
            notification.closest('nav').addClass('notification--reveal');
            if (notification.find('input').length) {
                notification.find('input').first().focus();
            }
        }, delay);
    };

    mr.notifications.closeNotification = function (notification) {

        var $notification = $(notification);

        notification = $notification.is('.notification-close') ?
            $notification.closest('.notification') :
            $('body').find('.notification[data-notification-link="' + notification + '"]');

        notification.addClass('notification--dismissed');
        notification.closest('nav').removeClass('notification--reveal');

        // If this notification requires to be closed permanently using a cookie, set the cookie now.
        if (typeof notification.attr('data-cookie') !== typeof undefined) {
            mr.cookies.setItem(notification.attr('data-cookie'), "true", Infinity);
        }
    };

    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));

//////////////// Parallax
mr = (function (mr, $, window, document) {
    "use strict";

    var documentReady = function ($) {

        var $window = $(window);
        var windowWidth = $window.width();
        var windowHeight = $window.height();
        var navHeight = $('nav').outerHeight(true);

        // Disable parallax on mobile

        if ((/Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/i).test(navigator.userAgent || navigator.vendor || window.opera)) {
            $('section').removeClass('parallax');
        }

        if (windowWidth > 768) {
            var parallaxHero = $('.parallax:nth-of-type(1)'),
                parallaxHeroImage = $('.parallax:nth-of-type(1) .background-image-holder');

            parallaxHeroImage.css('top', -(navHeight));
            if (parallaxHero.outerHeight(true) == windowHeight) {
                parallaxHeroImage.css('height', windowHeight + navHeight);
            }
        }
    };

    mr.parallax = {
        documentReady: documentReady
    };

    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));

//////////////// Piecharts
mr = (function (mr, $, window, document) {
    "use strict";

    var documentReady = function ($) {

        $('.barchart').each(function () {
            var chart = $(this);
            var bar = chart.find('.barchart__progress');
            var barWidth = chart.attr('data-value') * 1 + '%';
            bar.attr('data-value', barWidth);
            if (!chart.hasClass('barchart--vertical')) {
                bar.css('width', barWidth);
            } else {
                bar.css('height', barWidth);
            }

        });
    };

    mr.piecharts = {
        documentReady: documentReady
    };

    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));

//////////////// EasyPiecharts
mr = (function (mr, $, window, document) {
    "use strict";

    var documentReady = function ($) {

        $('.piechart').each(function () {
            var chart = $(this),
                value = chart.attr('data-value') * 1;
            chart.easyPieChart({
                animate: 2000,
                barColor: '#425cbb'
            });
            chart.data('easyPieChart').update(value);
        });

    };

    mr.easypiecharts = {
        documentReady: documentReady
    };

    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));

//////////////// Scroll Reveal
mr = (function (mr, $, window, document) {
    "use strict";

    var documentReady = function ($) {

        var $body = $('body');
        if ($('body[data-reveal-selectors]').length) {
            window.sr = ScrollReveal();
            var selectors = $body.attr('data-reveal-selectors');

            // Gather scroll reveal options
            var revealTiming = 1000;
            if ($('body[data-reveal-timing]').length) {
                revealTiming = $body.attr('data-reveal-timing');
            }

            // Initialize scroll reveal
            window.sr.reveal('' + selectors + '', {viewFactor: 0.1, duration: '' + revealTiming + '', scale: 1, mobile: false});

        }

    };

    mr.scrollreveal = {
        documentReady: documentReady
    };

    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));

//////////////// Sliders Owl
mr = (function (mr, $, window, document) {
    "use strict";

    mr.sliders = {};

    var documentReady = function ($) {

        var sliders = [];

        $('.slider').each(function () {
            var candidate = $(this);

            if (candidate.find('ul.slides').length) {
                return true;
            } else {
                var children = [];
                var childCount = candidate.find('>*').length;
                candidate.children().each(function (index) {
                    children.push($(this).wrap('<li>').parent());
                });
                $('<ul class="slides"></ul>').append(children).appendTo(candidate);
            }
        });

        $('.slider').each(function (index) {

            var slider = $(this);
            var sliderInitializer = $(this).find('ul.slides');
            var items = 1;
            var arrows = false;
            var paging = false;
            var timing = 7000;
            var loop = false;
            var autoplay = false;
            var adaptiveHeight = false;
            if (slider.attr('data-arrows') == 'true') {
                arrows = true;
            } else {
                arrows = false;
            }
            if (slider.attr('data-autoplay') == 'false') {
                autoplay = false;
            } else {
                autoplay = true;
            }
            if (slider.attr('data-paging') == 'true' && sliderInitializer.find('li').length > 1) {
                paging = true;
            } else {
                paging = false;
            }

            if (slider.attr('data-timing')) {
                timing = slider.attr('data-timing');
            }
            if (slider.attr('data-items')) {
                items = slider.attr('data-items');
            }
            if (sliderInitializer.find('li').length > 1 && slider.attr('data-loop') != 'false') {
                loop = true;
            }
            if (slider.attr('data-adaptive-height') == 'true') {
                adaptiveHeight = true;
            }
            sliders.push(sliderInitializer);
            sliders[index].owlCarousel({
                nav: arrows,
                dots: paging,
                adaptiveHeight: adaptiveHeight,
                dotsSpeed: 500,
                navSpeed: 500,
                items: items,
                autoplay: autoplay,
                autoplayTimeout: timing,
                navText: false,
                loop: loop,
                mouseDrag: false,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: items
                    }
                }
            });


        });

        mr.sliders.sliders = sliders;

    };

    mr.sliders.documentReady = documentReady;

    mw.tplDreamExpose("slider", documentReady);

    mr.components.documentReadyDeferred.push(documentReady);
    return mr;

}(mr, jQuery, window, document));

//////////////// Smoothscroll
mr = (function (mr, $, window, document) {
    "use strict";

    var documentReady = function ($) {
        // Smooth scroll to inner links
        var innerLinks = $('a.inner-link');

        if (innerLinks.length) {
            innerLinks.each(function () {
                var link = $(this);
                var href = link.attr('href');
                if (href.charAt(0) !== "#") {
                    link.removeClass('inner-link');
                }
            });

            var offset = 0;
            if ($('body[data-smooth-scroll-offset]').length) {
                offset = $('body').attr('data-smooth-scroll-offset');
                offset = offset * 1;
            }

            smoothScroll.init({
                selector: '.inner-link',
                selectorHeader: null,
                speed: 750,
                easing: 'easeInOutCubic',
                offset: offset
            });
        }
    };

    mr.smoothscroll = {
        documentReady: documentReady
    };

    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));

//////////////// Tabs
mr = (function (mr, $, window, document) {
    "use strict";

    var documentReady = function ($) {
        $('.tabs:not(".activated")').each(function () {
            var tabs = $(this);
            tabs.addClass("activated")
            tabs.after('<ul class="tabs-content">');
            tabs.find('li').each(function () {
                var currentTab = $(this);
                var tabContent = currentTab.find('.tab__content').wrap('<li></li>').parent();
                var tabContentClone = tabContent.clone(true, true);
                tabContent.remove();
                currentTab.closest('.tabs-container').find('.tabs-content').append(tabContentClone);
            });
            tabs.find('li').on('click', function () {
                var clickedTab = $(this);
                var tabContainer = clickedTab.closest('.tabs-container');
                var activeIndex = (clickedTab.index() * 1) + (1);

                tabContainer.find('> .tabs > li').removeClass('active');
                tabContainer.find('> .tabs-content > li').removeClass('active');

                clickedTab.addClass('active');
                tabContainer.find('> .tabs-content > li:nth-of-type(' + activeIndex + ')').addClass('active');
            });
            tabs.find('li.active').trigger('click');

        });


    };

    mw.tplDreamExpose("tabs", documentReady);

    mr.tabs = {
        documentReady: documentReady
    };

    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));

//////////////// Transitions
$(window).bind("pageshow", function (event) {
    if (event.originalEvent.persisted) {
        window.location.reload();
    }
});

mr = (function (mr, $, window, document) {
    "use strict";

    var documentReady = function ($) {
        $('a:not([href^="#"]):not([href^="tel"]):not([href^="mailto"]):not([data-lightbox]):not([href=""]):not([target="_blank"]):not(.modal-trigger):not([class*="lb-"])').on('click', function () {
            // $('[class*="transition--"]').removeClass('transition--active');
        });
    };

    var windowLoad = function () {
        $('[class*="transition--"]').addClass('transition--active');
        $('.loader').addClass('loader--fade');
    };

    mr.transitions = {
        documentReady: documentReady,
        windowLoad: windowLoad
    };

    mr.components.documentReady.push(documentReady);
    mr.components.windowLoad.push(windowLoad);
    return mr;

}(mr, jQuery, window, document));

//////////////// Twitter Feeds
mr = (function (mr, $, window, document) {
    "use strict";

    var documentReady = function ($) {
        $('.tweets-feed').each(function (index) {
            $(this).attr('id', 'tweets-' + index);
        }).each(function (index) {
            var element = $('#tweets-' + index);
            var TweetConfig = {
                "domId": '',
                "maxTweets": element.attr('data-amount'),
                "enableLinks": true,
                "showUser": true,
                "showTime": true,
                "dateFunction": '',
                "showRetweet": false,
                "customCallback": handleTweets
            };

            if (typeof element.attr('data-widget-id') !== typeof undefined) {
                TweetConfig.id = element.attr('data-widget-id');
            } else if (typeof element.attr('data-feed-name') !== typeof undefined) {
                TweetConfig.profile = {"screenName": element.attr('data-feed-name')};
            } else {
                TweetConfig.profile = {"screenName": 'twitter'};
            }

            if (element.closest('.twitter-feed--slider').length) {
                element.addClass('slider');
            }

            function handleTweets(tweets) {
                var x = tweets.length;
                var n = 0;
                var html = '<ul class="slides">';
                while (n < x) {
                    html += '<li>' + tweets[n] + '</li>';
                    n++;
                }
                html += '</ul>';
                element.html(html);

                // Initialize twitter feed slider
                if (element.closest('.slider').length) {
                    mr.sliders.documentReady(mr.setContext());

                    return html;
                }
            }

            twitterFetcher.fetch(TweetConfig);
        });
    };

    mr.twitter = {
        documentReady: documentReady
    };

    mr.components.documentReady.push(documentReady);

    return mr;

}(mr, jQuery, window, document));

//////////////// Video
mr = (function (mr, $, window, document) {
    "use strict";

    var documentReady = function ($) {

        //////////////// Youtube Background

        if ($('.youtube-background').length) {
            $('.youtube-background').each(function () {
                var player = $(this);
                var vidURL = $(this).attr('data-video-url');
                var startAt = $(this).attr('data-start-at');
                player.attr('data-property', '{videoURL:"' + vidURL + '",containment:"self",autoPlay:true, mute:true, startAt:' + startAt + ', opacity:1}');
                player.closest('.videobg').append('<div class="loading-indicator"></div>');
                player.YTPlayer();
                player.on("YTPStart", function () {
                    player.closest('.videobg').addClass('video-active');
                });
            });
        }

        if ($('.videobg').find('video').length) {
            $('.videobg').find('video').closest('.videobg').addClass('video-active');
        }

        //////////////// Video Cover Play Icons

        $('.video-cover').each(function () {
            var videoCover = $(this);
            if (videoCover.find('iframe').length) {
                videoCover.find('iframe').attr('data-src', videoCover.find('iframe').attr('src'));
                videoCover.find('iframe').attr('src', '');
            }
        });

        $('.video-cover .video-play-icon').on("click", function () {
            var playIcon = $(this);
            var videoCover = playIcon.closest('.video-cover');
            if (videoCover.find('video').length) {
                var video = videoCover.find('video').get(0);
                videoCover.addClass('reveal-video');
                video.play();
                return false;
            } else if (videoCover.find('iframe').length) {
                var iframe = videoCover.find('iframe');
                iframe.attr('src', iframe.attr('data-src'));
                videoCover.addClass('reveal-video');
                return false;
            }
        });
    };

    mr.video = {
        documentReady: documentReady
    };

    mr.components.documentReady.push(documentReady);
    return mr;

}(mr, jQuery, window, document));
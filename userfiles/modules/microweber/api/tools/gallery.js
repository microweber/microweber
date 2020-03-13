(function(){
    var gallery = {

        generateHTML: function (item, callback, modal) {
            var modal = modal || false;
            if (typeof item === 'string') {
                callback.call("<div class='mwf-gallery-modeHTML'>" + item + "</div>");
            }
            else if (typeof item === 'object' && item.constructor === {}.constructor) {
                var img = item.img || item.image || item.url || item.src;
                var desc = item.description || item.title || item.name;
                if (!!modal) {
                    mw.$(modal.container).addClass('mw_gallery_loading');
                }
                mw.image.preload(img, function (w, h) {
                    if (typeof desc != 'undefined' && desc != '') {
                        callback.call("<div class='mwf-single-holder'><img src='" + img + "'  class='mwf-single mwf-single-loading '  width='" + w + "' data-width='" + w + "' data-height='" + h + "' height='" + h + "' onclick='mw.tools.gallery.next()' onload='mw.tools.gallery.normalize(mw.$(\"#mw_gallery\")[0].modal);'  /><div class='mwf-gallery-description'><div class='mwf-gallery-description-holder'>" + desc + "</div></div></div>");
                    }
                    else {
                        callback.call("<div class='mwf-single-holder'><img src='" + img + "'  data-width='" + w + "' width='" + w + "' data-height='" + h + "' height='" + h + "' class='mwf-single mwf-single-loading' onclick='mw.tools.gallery.next()' onload='mw.tools.gallery.normalize(mw.$(\"#mw_gallery\")[0].modal);' /></div>");
                    }
                    mw.$(modal.container).removeClass('mw_gallery_loading');
                });
            }
            else if (typeof item === 'object' && typeof item.nodeType === 'number') {
                var e = mwd.createElement('div');
                e.appendChild(item.cloneNode(true));
                var html = e.innerHTML;
                var e = null;
                callback.call("<div class='mwf-gallery-modeHTML'>" + html + "</div>");
            }
        },
        next: function (modal) {

            var modal2_test = mw.$("#mw_gallery")[0];
            var modal2 = false;
            if (typeof(modal2_test) != 'undefined' && typeof(modal2_test.modal) != 'undefined') {
                modal2 = modal2_test.modal;
            }

            var modal = modal || modal2;
            var galeryContainer = mw.$('.mwf-gallery-container', modal.container);
            var arr = modal.gallery.array, curr = modal.gallery.curr;
            var next = typeof arr[curr + 1] !== 'undefined' ? curr + 1 : 0;
            mw.tools.gallery.generateHTML(arr[next], function () {
                galeryContainer.html(this);
                modal.gallery.curr = next;
                mw.tools.gallery.normalize(modal);
                var next_of_next = typeof arr[next + 1] !== 'undefined' ? next + 1 : 0;
                if (typeof arr[next_of_next] !== 'undefined') {
                    if (typeof arr[next_of_next]['image'] !== 'undefined') {
                        var next_of_next_url = arr[next_of_next]['image']
                        var src_regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
                        if (src_regex.test(next_of_next_url)) {
                            try {
                                var _prelaod_img = new Image();
                                _prelaod_img.src = next_of_next_url;
                            } catch (e) {
                            }
                        }
                    }
                }
            }, modal);
        },
        prev: function (modal) {


            var modal2_test = mw.$("#mw_gallery")[0];
            var modal2 = false;
            if (typeof(modal2_test) != 'undefined' && typeof(modal2_test.modal) != 'undefined') {
                modal2 = modal2_test.modal;
            }

            var modal = modal || modal2;
            var galeryContainer = mw.$('.mwf-gallery-container', modal.container);
            var arr = modal.gallery.array, curr = modal.gallery.curr;
            var prev = typeof arr[curr - 1] !== 'undefined' ? curr - 1 : arr.length - 1;
            mw.tools.gallery.generateHTML(arr[prev], function () {
                galeryContainer.html(this);
                modal.gallery.curr = prev;
                mw.tools.gallery.normalize(modal);
            }, modal);
        },
        playing: false,
        playingInt: null,
        play: function (modal, interval) {

            if (!modal) {
                clearInterval(mw.tools.gallery.playingInt);
                mw.tools.gallery.playing = false;
                return;
            }
            if (mw.tools.gallery.playing) {
                mw.tools.gallery.playing = false;
                clearInterval(mw.tools.gallery.playingInt);
                mw.$('.mwf-loader', modal.container).stop().css({width: '0%'})
            }
            else {
                mw.tools.gallery.playing = true;
                interval = interval || 4000;
                mw.$('.mwf-loader', modal.container).stop().css({width: '0%'}).animate({width: '100%'}, interval);
                mw.tools.gallery.playingInt = setInterval(function () {
                    if (mw.tools.gallery.playing) {
                        if ($('.mwf-loader', modal.container).length > 0) {
                            mw.$('.mwf-loader', modal.container).stop().css({width: '0%'}).animate({width: '100%'}, interval);
                            mw.tools.gallery.next(modal);
                        }
                        else {
                            clearInterval(mw.tools.gallery.playingInt);
                        }
                    }
                }, interval);
            }
        },
        go: function (modal, index) {
            var modal = modal || mw.$("#mw_gallery")[0].modal;
            var index = index || 0;
            if (modal.gallery.curr != index) {
                var galeryContainer = mw.$('.mwf-gallery-container', modal.container);
                var arr = modal.gallery.array;
                mw.tools.gallery.generateHTML(arr[index], function () {
                    galeryContainer.html(this);
                    modal.gallery.curr = index;
                    mw.tools.gallery.normalize(modal);
                }, modal);
            }
            else {
            }
        },
        init: function (arr, start, modal) {
            /* "arr" parameter must be [{img:"url.jpg", description:"Lorem Ipsum"}, {img:"..."}]   or ["some <formated>", " <b>html</b> ..."]  or NodeList */
            if (arr === null || arr === undefined) {
                return false;
            }
            if (typeof arr.length !== 'number') {
                return false;
            }
            if (arr.length === 0) {
                return false;
            }
            var start = start || 0;
            if (mw.$("#mw_gallery").length > 0) {
                var m = mw.$("#mw_gallery")[0].modal;
                m.gallery = {
                    array: arr,
                    curr: 0
                }
                mw.tools.gallery.go(m, start);
                return false;
            }
            var next = arr.length > 1 ? '<span class="mwf-next">&rsaquo;</span>' : '';
            var prev = arr.length > 1 ? '<span class="mwf-prev">&lsaquo;</span>' : '';
            var play = arr.length > 1 ? '<span class="mwf-play"></span>' : '';
            var loader = arr.length > 1 ? '<span class="mwf-loader"></span>' : '';
            var ghtml = ''
                + '<div class="mwf-gallery">'
                + '<div class="mwf-gallery-container">'
                + '</div>'
                + next
                + prev
                + play
                + loader
                + (mw.tools.isFullscreenAvailable() ? '<span class="mwf-fullscreen"></span>' : '')
                + '</div>';
            var modal = modal || mw.top().tools.modal.init({
                    width: "100%",
                    height: "100%",
                    html: '',
                    draggable: false,
                    overlay: true,
                    name: "mw_gallery",
                    template: 'mw_modal_gallery',
                    onremove: function () {
                        clearInterval(mw.tools.gallery.playingInt);
                        mw.tools.gallery.playing = false;
                    }
                });
            modal.overlay.style.opacity = 0.8;
            modal.container.innerHTML = ghtml;
            modal.gallery = {
                array: arr,
                curr: start
            }
            var galeryContainer = mw.$('.mwf-gallery-container', modal.container);
            mw.tools.gallery.generateHTML(arr[start], function () {
                galeryContainer.html(this)
                var next = mw.$('.mwf-next', modal.container);
                var prev = mw.$('.mwf-prev', modal.container);
                var play = mw.$('.mwf-play', modal.container);
                var f = mw.$('.mwf-fullscreen', modal.main);
                next.click(function () {
                    mw.tools.gallery.next(modal);
                });
                prev.click(function () {
                    mw.tools.gallery.prev(modal);
                });
                play.click(function () {
                    mw.$(this).toggleClass('active');
                    mw.tools.gallery.play(modal);
                });
                f.click(function () {
                    mw.tools.toggleFullscreen(modal.main[0]);
                    mw.tools.gallery.normalize(modal);
                });
                mw.tools.fullscreenChange(function () {
                    if (this == true) {
                        mw.$(".mw_modal_gallery").addClass("fullscreen-mode");
                    }
                    else {
                        mw.$(".mw_modal_gallery").removeClass("fullscreen-mode");
                    }
                })
                mw.tools.gallery.normalize(modal);
            }, modal);
            return modal;
        },
        normalizer: function (modal) {
            var img = modal.container.querySelector('.mwf-single');
            var ww = mw.$(window).width();
            var wh = mw.$(window).height();
            if (img !== null) {
                var dw = parseFloat($(img).dataset("width"));
                var dh = parseFloat($(img).dataset("height"));
                var mxw = ((dw > ww) ? (ww - 33) : dw);
                var mxh = ((dh > wh) ? (wh - 33) : dh);
                img.style.maxWidth = mxw + 'px';
                //img.style.maxWidth = 'auto';
                img.style.maxHeight = mxh + 'px';
                //img.style.maxHeight = 'auto';
                var holder = img.parentNode;
                mw.tools.modal.center(holder);
            }
            else {
                var holder = modal.container.querySelector('.mwf-gallery-modeHTML');
                holder.style.maxWidth = (ww - 33) + 'px';
                holder.style.maxHeight = (wh - 33) + 'px';
                mw.$(holder).width($(holder).width())
                mw.$(holder).height($(holder).height())
                mw.tools.modal.center(holder);
            }
        },
        normalize: function (modal) {
            mw.tools.gallery.normalizer(modal);
            (function (modal) {
                setTimeout(function () {
                    mw.$('.mwf-single', modal).removeClass('.mwf-single-loading');
                }, 50);
            })(modal)
            if (typeof modal.normalized === 'undefined') {
                modal.normalized = true;
                mw.$(window).bind("resize", function () {
                    if (mwd.getElementById('mw_gallery') !== null) {
                        mw.tools.gallery.normalizer(modal);
                    }
                });
            }
        }

    };
    mw.tools.gallery = gallery;
})();

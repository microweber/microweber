mw.requestAnimationFrame = (function () {
    return window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.oRequestAnimationFrame ||
        window.msRequestAnimationFrame ||
        function (callback, element) {
            window.setTimeout(callback, 1000 / 60);
        };
})();

mw._intervals = {};
mw.interval = function(key, func){
    if(!key || !func || !!mw._intervals[key]) return;
    mw._intervals[key] = func;
};
mw.removeInterval = function(key){
    delete mw._intervals[key];
};
setInterval(function(){
    for(var i in mw._intervals){
        mw._intervals[i].call();
    }
}, 99);

mw.require("files.js");
mw.require("css_parser.js");
mw.require("components.js");
//mw.require("content.js");
mw.require("color.js");//
mw.lib.require("acolorpicker");
//mw.require(mw.settings.includes_url + "css/ui.css");
(function () {
    if (typeof jQuery.browser === 'undefined') {
        var matched, browser;
        jQuery.uaMatch = function (ua) {
            ua = ua.toLowerCase();
            var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
                /(webkit)[ \/]([\w.]+)/.exec(ua) ||
                /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
                /(msie) ([\w.]+)/.exec(ua) ||
                ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
                [];
            return {
                browser: match[1] || "",
                version: match[2] || "0"
            };
        };
        matched = jQuery.uaMatch(navigator.userAgent);
        browser = {};
        if (matched.browser) {
            browser[matched.browser] = true;
            browser.version = matched.version;
        }
        if (browser.chrome) {
            browser.webkit = true;
        } else if (browser.webkit) {
            browser.safari = true;
        }
        jQuery.browser = browser;
    }
})();
mw.datassetSupport = typeof mwd.documentElement.dataset !== 'undefined';

mw.exec = function (str, a, b, c) {
    a = a || "";
    b = b || "";
    c = c || "";
    if (!str.contains(".")) {
        return window[this](a, b, c);
    }
    else {
        var arr = str.split(".");
        var temp = window[arr[0]];
        var len = arr.length - 1;
        for (var i = 1; i <= len; i++) {
            if (typeof temp === 'undefined') {
                return false;
            }
            temp = temp[arr[i]];
        }
        return mw.is.func(temp) ? temp(a, b, c) : temp;
    }
};

mw.controllers = {};
mw.external_tool = function (url) {
    return !url.contains("/") ? mw.settings.site_url + "editor_tools/" + url : url;
};
// Polyfill for escape/unescape
if (!window.unescape) {
    window.unescape = function (s) {
        return s.replace(/%([0-9A-F]{2})/g, function (m, p) {
            return String.fromCharCode('0x' + p);
        });
    };
}
if (!window.escape) {
    window.escape = function (s) {
        var chr, hex, i = 0, l = s.length, out = '';
        for (; i < l; i++) {
            chr = s.charAt(i);
            if (chr.search(/[A-Za-z0-9\@\*\_\+\-\.\/]/) > -1) {
                out += chr;
                continue;
            }
            hex = s.charCodeAt(i).toString(16);
            out += '%' + ( hex.length % 2 !== 0 ? '0' : '' ) + hex;
        }
        return out;
    };
}
mw.tools = {
    fragment: function(){
        if(!this._fragment){
            this._fragment = document.createElement('div');
            this._fragment.style.visibility = 'hidden';
            this._fragment.style.position = 'absolute';
            this._fragment.style.width = '1px';
            this._fragment.style.height = '1px';
            document.body.appendChild(this._fragment);
        }
        return this._fragment;
    },
    _isBlockCache:{},
    isBlockLevel:function(node){
        if(!node || node.nodeType === 3){
            return false;
        }
        var name = node.nodeName;
        if(typeof this._isBlockCache[name] !== 'undefined'){
            return this._isBlockCache[name];
        }
        var test = document.createElement(name);
        this.fragment().appendChild(test);
        this._isBlockCache[name] = getComputedStyle(test).display === 'block';
        this.fragment().removeChild(test);
        return this._isBlockCache[name];
    },
    _isInlineCache:{},
    isInlineLevel:function(node){
        if(node.nodeType === 3){
            return false;
        }
        var name = node.nodeName;
        if(typeof this._isInlineCache[name] !== 'undefined'){
            return this._isInlineCache[name];
        }
        var test = document.createElement(name);
        this.fragment().appendChild(test);
        this._isInlineCache[name] = getComputedStyle(test).display === 'inline' && node.nodeName !== 'BR';
        this.fragment().removeChild(test);
        return this._isInlineCache[name];
    },
    elementOptions: function(el) {
        var opt = ( el.dataset.options || '').trim().split(','), final = {};
        if(!opt[0]) return final;
        $.each(opt, function(){
            var arr = this.split(':');
            var val = arr[1].trim();
            if(!val){

            }
            else if(val === 'true' || val === 'false'){
                val = val === 'true';
            }
            else if(!/\D/.test(val)){
                val = parseInt(val, 10);
            }
            final[arr[0].trim()] = val;
        });
        return final;
    },
    createAutoHeight: function() {
        if(window.thismodal && thismodal.iframe) {
            mw.tools.iframeAutoHeight(thismodal.iframe, 'now');
        }
        else if(window.top.frameElement && window.top.frameElement.contentWindow === window) {
            mw.tools.iframeAutoHeight(window.top.frameElement, 'now');
        } else if(window.top !== window) {
            top.mw.$('iframe').each(function(){
                try{
                    if(this.contentWindow === window) {
                        mw.tools.iframeAutoHeight(this, 'now');
                    }
                } catch(e){}
            })
        }
    },
    collision: function(el1, el2){
        if(!el1 || !el2) return;
        el1 = mw.$(el1), el2 = mw.$(el2);
        var o1 = el1.offset();
        var o2 = el2.offset();
        o1.width = el1.width();
        o1.height = el1.height();
        o2.width = el2.width();
        o2.height = el2.height();
        return (o1.left < o2.left + o2.width  && o1.left + o1.width  > o2.left &&  o1.top < o2.top + o2.height && o1.top + o1.height > o2.top);
    },
    iframeAutoHeight:function(frame){

        frame = mw.$(frame)[0];
        if(!frame) return;

        var _detector = document.createElement('div');
        _detector.className = 'mw-iframe-auto-height-detector';
        _detector.id = mw.id();

        var insertDetector = function() {
            if(frame.contentWindow && frame.contentWindow.document && frame.contentWindow.document.body){
                var det = frame.contentWindow.document.querySelector('.mw-iframe-auto-height-detector');
                if(!det){
                    frame.contentWindow.document.body.appendChild(_detector);
                } else if(det !== frame.contentWindow.document.body.lastChild){
                    frame.contentWindow.document.body.appendChild(det);
                }
                if(frame.contentWindow.mw) {
                    frame.contentWindow.mw._iframeDetector = _detector;
                }

            }

        };

        setTimeout(function(){
            insertDetector();
        }, 100);
        frame.scrolling="no";
        frame.style.minHeight = 0 + 'px';
        mw.$(frame).on('load resize', function(){

            if(!mw.tools.canAccessIFrame(frame)) {
                console.log('Iframe can not be accessed.', frame);
                return;
            }
            if(!frame.contentWindow.document.body){
                return;
            }
            if(!!frame.contentWindow.document.querySelector('.mw-iframe-auto-height-detector')){
                return;
            }

            insertDetector();
        });
        frame._int = setInterval(function(){
            if(frame.parentNode && frame.contentWindow  && frame.contentWindow.$){
                var offTop = frame.contentWindow.$(_detector).offset().top;
                var calc = offTop + _detector.offsetHeight;
                //calc = Math.max(calc, mw.tools.nestedFramesHeight(frame));
                frame._currHeight = frame._currHeight || 0;
                if(calc && calc !== frame._currHeight ){
                    frame._currHeight = calc;
                     frame.style.height = calc + 'px';
                    mw.$(frame).trigger('bodyResize');
                }
            }
            else {
                //clearInterval(frame._int);
            }
        }, 77);

    },

    distance: function (x1, y1, x2, y2) {
        var a = x1 - x2;
        var b = y1 - y2;
        return Math.floor(Math.sqrt(a * a + b * b));
    },
    copy: function (value) {
        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = value;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
    },
    cloneObject: function (object) {
        if (window.Object && window.Object.assign) {
            return Object.assign({}, object);
        }
        else {
            return jQuery.extend(true, {}, object);
        }
    },
    constructions: function () {
        mw.$(".mw-image-holder").each(function () {
            var img = this.querySelector('img');
            if (img && img.src) {
                mw.$(this).css('backgroundImage', 'url(' + img.src + ')')
            }
        })
    },
    isRtl: function (el) {
        //todo
        el = el || document.documentElement;
        return document.documentElement.dir == 'rtl'
    },
    isEditable: function (item) {
        var el = item;
        if (!!item.type && !!item.target) {
            el = item.target;
        }
        return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(el, ['edit', 'module']);
    },
    eachIframe: function (callback, root, ignore) {
        root = root || document, scope = this, ignore = ignore || [];
        var all = root.querySelectorAll('iframe'), i = 0;
        for (; i < all.length; i++) {
            if (mw.tools.canAccessIFrame(all[i]) && ignore.indexOf(all[i]) === -1) {
                callback.call(all[i].contentWindow, all[i].contentWindow);
                scope.eachIframe(callback, all[i].contentWindow.document)
            }
        }
    },
    eachWindow: function (callback, options) {
        options = options || {};
        var curr = window;
        callback.call(curr, curr);
        while (curr !== top) {
            this.eachIframe(function (iframeWindow) {
                callback.call(iframeWindow, iframeWindow);
            }, curr.parent.document, [curr]);
            curr = curr.parent;
            callback.call(curr, curr);
        }
        this.eachIframe(function (iframeWindow) {
            callback.call(iframeWindow, iframeWindow);
        })
        if (window.opener !== null && mw.tools.canAccessWindow(opener)) {
            callback.call(window.opener, window.opener);
            this.eachIframe(function (iframeWindow) {
                callback.call(iframeWindow, iframeWindow);
            }, window.opener.document);
        }
    },
    canAccessWindow: function (winObject) {
        var can = false;
        try {
            var doc = winObject.document;
            can = !!doc.body;
        } catch (err) {
        }
        return can;
    },
    canAccessIFrame: function (iframe) {
        var can = false;
        try {
            var doc = iframe.contentDocument || iframe.contentWindow.document;
            can = !!doc.body && !!doc.documentElement;
        } catch (err) {
        }
        return can;
    },
    createStyle: function (c, css, ins) {
        ins = ins || mwd.getElementsByTagName('head')[0];
        var style = mw.$(c)[0];
        if (!style) {
            style = mwd.createElement('style');
            ins.appendChild(style);
        }
        style.innerHTML = css;
    },
    externalInstrument: {
        register: {},
        holder: function () {
            var div = mwd.createElement('div');
            div.className = 'mw-external-tool';
            return div;
        },
        prepare: function (name, params) {
            var frame = mwd.createElement('iframe');
            frame.name = name;
            /* for callbacks */
            var url = mw.external_tool(name);
            if (typeof params === 'object') {
                var params = $.param(params);
            }
            else {
                params = "";
            }
            frame.src = url + "?" + params;
            frame.scrolling = 'no';
            frame.frameBorder = 0;
            frame.onload = function () {
                frame.contentWindow.thisframe = frame;
            }
            return frame;
        },
        init: function (name, callback, holder, params) {
            if (typeof mw.tools.externalInstrument.register[name] === 'undefined') {
                var frame = mw.tools.externalInstrument.prepare(name, params);
                frame.height = 300;
                mw.tools.externalInstrument.register[name] = frame;
                if (!holder) {
                    var holder = mw.tools.externalInstrument.holder();
                    mw.$(mwd.body).append(holder);
                }
                mw.$(holder).append(frame);
            }
            else {
                mw.$(mw.tools.externalInstrument.register[name]).unbind('change');
            }
            mw.$(mw.tools.externalInstrument.register[name]).bind('change', function () {
                Array.prototype.shift.apply(arguments);
                callback.apply(this, arguments);
            });
            return mw.tools.externalInstrument.register[name];
        }
    },
    external: function (name, callback, holder, params) {
        return mw.tools.externalInstrument.init(name, callback, holder, params);
    },
    _external: function (o) {
        return mw.tools.external(o.name, o.callback, o.holder, o.params);
    },

    cssNumber: function (val) {
        var units = ["px", "%", "in", "cm", "mm", "em", "ex", "pt", "pc"];
        if (typeof val === 'number') {
            return val + 'px';
        }
        else if (typeof val === 'string' && parseFloat(val).toString() == val) {
            return val + 'px';
        }
        else {
            if (isNaN(parseFloat(val))) {
                return '0px';
            }
            else {
                return val;
            }
        }
        ;
    },
    alert: function (text) {
        var html = ''
            + '<table class="mw_alert" width="100%" height="140" cellpadding="0" cellspacing="0">'
            + '<tr>'
            + '<td align="center" valign="middle"><div class="mw_alert_holder">' + text + '</div></td>'
            + '</tr>'
            + '<tr>'
            + '<td align="center" height="25"><span class="mw-ui-btn" onclick="mw.tools.modal.remove(\'mw_alert\');"><b>' + mw.msg.ok + '</b></span></td>'
            + '</tr>'
            + '</table>';
        if (mw.$("#mw_alert").length === 0) {
            return mw.modal({
                html: html,
                width: 400,
                height: 200,
                overlay: false,
                name: "mw_alert",
                template: "mw_modal_basic"
            });
        }
        else {
            mw.$("#mw_alert .mw_alert_holder").html(text);
            return mw.$("#mw_alert")[0].modal;
        }
    },
    isField: function (target) {
        var t = target.nodeName.toLowerCase();
        var fields = /^(input|textarea|select)$/i;
        return fields.test(t);
    },
    equals: function (a, b) {
        var ai, bi;
        for (ai in a) {
            if (!b[ai] || a[ai] != b[ai]) {
                return false;
            }
        }
        for (bi in b) {
            if (!a[bi] || b[bi] != a[bi]) {
                return false;
            }
        }
        return true;
    },
    dropdown: function (root) {
        var root = root || mwd.body;
        if (root === null) {
            return;
        }
        var items = root.querySelectorAll(".mw-dropdown"), l = items.length, i = 0;
        for (; i < l; i++) {
            var el = items[i];
            var cls = el.className;
            if (el.mwDropdownActivated) {
                continue;
            }
            el.mwDropdownActivated = true;
            el.hasInput = el.querySelector('input.mw-dropdown-field') !== null;
            if (el.hasInput) {
                var input = el.querySelector('input.mw-dropdown-field');
                input.dropdown = el;
                input.onkeydown = function (e) {
                    if (e.keyCode == 13) {
                        e.preventDefault()
                        mw.$(this.dropdown).removeClass("active");
                        mw.$('.mw-dropdown-content', this.dropdown).hide();
                        mw.$(this.dropdown).setDropdownValue(this.value, true, true);
                        return false;
                    }
                }
                input.onkeyup = function (e) {
                    if (e.keyCode == 13) {
                        return false;
                    }
                }
            }
            mw.$(el).off("click");
            mw.$(el).on("click", function (event) {
                if ($(this).hasClass("disabled")) {
                    return false;
                }
                if (!mw.tools.hasClass(event.target.className, 'mw-dropdown-content') && !mw.tools.hasClass(event.target.className, 'dd_search')) {
                    if (this.querySelector('input.mw-dropdown-field') !== null && !mw.tools.hasClass(this, 'active') && mw.tools.hasParentsWithClass(event.target, 'mw-dropdown-value')) {
                        if (this.hasInput) {
                            var input = this.querySelector('input.mw-dropdown-field');
                            input.value = mw.$(this).getDropdownValue();
                            mw.wysiwyg.save_selection(true);
                            mw.$(input).focus();
                        }
                    }
                    mw.$(this).toggleClass("active");
                    mw.$(".mw-dropdown").not(this).removeClass("active").find(".mw-dropdown-content").hide();
                    if (mw.$(".other-action-hover", this).length == 0) {
                        var item = mw.$(".mw-dropdown-content", this);
                        if (item.is(":visible")) {
                            item.hide();
                            item.focus();
                        }
                        else {
                            item.show();
                            if (event.target.type != 'text') {
                                try {
                                    this.querySelector("input.dd_search").focus();
                                } catch (e) {
                                }
                            }
                        }
                    }
                }
            });
            mw.$(el)
                .hover(function () {
                    mw.$(this).add(this);
                    if (mw.tools.hasClass(cls, 'other-action')) {
                        mw.$(this).addClass('other-action');
                    }
                }, function () {
                    mw.$(this).removeClass("hover");
                    mw.$(this).removeClass('other-action');
                })
                .on('mousedown touchstart', 'li[value]', function (event) {
                    mw.$(mw.tools.firstParentWithClass(this, 'mw-dropdown')).setDropdownValue(this.getAttribute('value'), true);
                    return false;
                })
                .on('click', 'a[href="#"]', function (event) {
                    return false;
                });
        }
        /* end For loop */
        if (typeof mw.tools.dropdownActivated === 'undefined') {
            mw.tools.dropdownActivated = true;
            mw.$(mwd.body).mousedown(function (e) {
                if (!mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['mw-dropdown-content', 'mw-dropdown'])) {
                    mw.$(".mw-dropdown").removeClass("active");
                    mw.$(".mw-dropdown-content").hide();
                    if(self !== top) {
                        try {
                            top.mw.$(".mw-dropdown").removeClass("active");
                            top.mw.$(".mw-dropdown-content").hide();
                        } catch(e){

                        }
                    }
                }
            });
        }
    },
    module_slider: {
        scale: function () {
            var window_width = mw.$(window).width();
            mw.$(".modules_bar").each(function () {
                mw.$(this).width(window_width - 167);
                mw.$(".modules_bar_slider", this).width(window_width - 183);
            });
        },
        prepare: function () {
            mw.$(".modules_bar").each(function () {
                var module_item_width = 0;
                mw.$("li", this).each(function () {
                    module_item_width += mw.$(this).outerWidth(true);
                });
                mw.$("ul", this).width(module_item_width);
            });
        },
        init: function () {
            mw.tools.module_slider.prepare();
            mw.tools.module_slider.scale();
        }
    },
    toolbar_slider: {
        off: function () {
            return mw.$(".modules_bar").width() - 60;
        }, /*120*/
        ctrl_show_hide: function () {
            mw.$(".modules_bar").each(function () {
                var el = mw.$(this);
                var parent = el.parent();
                if (el.scrollLeft() == 0) {
                    mw.$(".modules_bar_slide_left", this.parentNode).hide();
                }
                else {
                    mw.$(".modules_bar_slide_left", this.parentNode).show();
                }
                var max = el.width() + el.scrollLeft();
                if (max == this.scrollWidth) {
                    mw.$(".modules_bar_slide_right", this.parentNode).hide();
                }
                else {
                    mw.$(".modules_bar_slide_right", this.parentNode).show();
                }
            });
        },
        ctrl_states: function () {
            mw.$(".modules_bar_slide_right,.modules_bar_slide_left").mousedown(function () {
                mw.$(this).addClass("active");
            });
            mw.$(".modules_bar_slide_right,.modules_bar_slide_left").bind("mouseup mouseout", function () {
                mw.$(this).removeClass("active");
            });
        },
        slide_left: function (item) {
            var item = mw.$(item);
            mw.tools.toolbar_slider.ctrl_show_hide();
            var left = mw.$(".modules_bar", item[0].parentNode).scrollLeft();
            mw.$(".modules_bar", item[0].parentNode).stop().animate({scrollLeft: left - mw.tools.toolbar_slider.off()}, function () {
                mw.tools.toolbar_slider.ctrl_show_hide();
            });
        },
        slide_right: function (item) {
            var item = mw.$(item);
            mw.tools.toolbar_slider.ctrl_show_hide();
            var left = mw.$(".modules_bar", item[0].parentNode).scrollLeft();
            mw.$(".modules_bar", item[0].parentNode).stop().animate({scrollLeft: left + mw.tools.toolbar_slider.off()}, function () {
                mw.tools.toolbar_slider.ctrl_show_hide();
            });
        },
        init: function () {
            mw.$(".modules_bar").scrollLeft(0);
            mw.tools.toolbar_slider.ctrl_show_hide();
            mw.$(".modules_bar_slide_left").click(function () {
                mw.tools.toolbar_slider.slide_left(this);
            }).disableSelection();
            mw.$(".modules_bar_slide_right").click(function () {
                mw.tools.toolbar_slider.slide_right(this);
            }).disableSelection();
            mw.tools.toolbar_slider.ctrl_states();
        }
    },
    toolbar_sorter: function (obj, value_to_search) {
        mw.$(".modules_bar").scrollLeft(0);
        for (var item in obj) {
            var child_object = obj[item];
            var id = child_object.id;
            var categories = child_object.category.replace(/\s/gi, '').split(',');
            var item = mw.$(document.getElementById(id));
            if (categories.indexOf(value_to_search) !== -1) {
                item.show();
            }
            else {
                item.hide();
            }
        }
    },
    toggleCheckbox: function (node) {
        if (node === null || node === undefined) return false;
        node.checked = node.checked ? false : true;
        return node.checked;
    },
    classNamespaceDelete: function (el_obj, namespace, parent, namespacePosition, exception) {
        if (el_obj.element && el_obj.namespace) {
            el = el_obj.element
            namespace = el_obj.namespace
            parent = el_obj.parent
            namespacePosition = el_obj.namespacePosition
            exceptions = el_obj.exceptions || []
        }
        else {
            el = el_obj, exceptions = []
        }
        var namespacePosition = namespacePosition || 'contains';
        var parent = parent || mwd;
        if (el === 'all') {
            var all = parent.querySelectorAll('.edit *'), i = 0, l = all.length;
            for (; i < l; i++) {
                mw.tools.classNamespaceDelete(all[i], namespace, parent, namespacePosition)
            }
            return;
        }
        if (!!el.className && el.className != '' && el.className != null && typeof(el.className.split) == 'function') {
            var cls = el.className.split(" "), l = cls.length, i = 0, final = [];
            for (; i < l; i++) {
                if (namespacePosition == 'contains') {
                    if (!cls[i].contains(namespace) || exceptions.indexOf(cls[i]) !== -1) {
                        final.push(cls[i]);
                    }
                }
                else if (namespacePosition == 'starts') {
                    if (cls[i].indexOf(namespace) !== 0) {
                        final.push(cls[i]);
                    }
                }
            }
            el.className = final.join(" ");
        }
    },
    jQueryFields: function (root) {
        if (typeof root === 'string') {
            root = mwd.querySelector(root);
        }
        if (typeof root === 'undefined' || root === null) return false;
        var allFields = "textarea, select, input[type='checkbox']:checked, input[type='color'], input[type='date'], input[type='datetime'], input[type='datetime-local'], input[type='email'], input[type='file'], input[type='hidden'], input[type='month'], input[type='number'], input[type='password'], input[type='radio']:checked, input[type='range'], input[type='search'], input[type='tel'], input[type='text'], input[type='time'], input[type='url'], input[type='week']";
        return mw.$(allFields, fields).not(':disabled');
    },
    toggle: function (who, toggler, callback) {
        var who = mw.$(who);
        who.toggle();
        who.toggleClass('toggle-active');
        mw.$(toggler).toggleClass('toggler-active');
        mw.is.func(callback) ? callback.call(who) : '';
    },

    confirm: function (question, callback) {
        var html = ''
            + '<table class="mw_alert" width="100%" height="140" cellpadding="0" cellspacing="0">'
            + '<tr>'
            + '<td align="center" valign="middle"><div class="mw_alert_holder">' + question + '</div></td>'
            + '</tr>'
            + '</table>';

        var ok = $('<span tabindex="99999" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info">'+mw.msg.ok+'</span>');
        var cancel = $('<span class="mw-ui-btn mw-ui-btn-medium ">' + mw.msg.cancel + '</span>');

        if (mw.$("#mw_confirm_modal").length === 0) {
            var modal = mw.top().dialog({
                content: html,
                width: 400,
                height: 'auto',
                autoHeight: true,
                overlay: false,
                name: "mw_confirm_modal",
                footer: [cancel, ok]
            });
        }
        else {
            mw.$("#mw_confirm_modal .mw_alert_holder").html(question);
            var modal = mw.$("#mw_confirm_modal")[0].modal;
        }


        ok.on('keydown', function (e) {
            if (e.keyCode === 13 || e.keyCode === 32) {
                callback.call(window);
                modal.remove();
                e.preventDefault();
            }
        });
        cancel.on('click', function () {
            modal.remove();
        });
        ok.on('click', function () {
            callback.call(window);
            modal.remove();
        });
        setTimeout(function () {
            mw.$(ok).focus();
        }, 120);
        return modal;
    },
    _confirm: function (question, callback) {
        var conf = confirm(question);
        if (conf && typeof callback === 'function') {
            callback.call(window);
        }
        return conf;
    },
    el_switch: function (arr, type) {
        if (type === 'semi') {
            mw.$(arr).each(function () {
                var el = mw.$(this);
                if (el.hasClass("semi_hidden")) {
                    el.removeClass("semi_hidden");
                }
                else {
                    el.addClass("semi_hidden");
                }
            });
        }
        else {
            mw.$(arr).each(function () {
                var el = mw.$(this);
                if (el.css('display') === 'none') {
                    el.show();
                }
                else {
                    el.hide();
                }
            });
        }
    },
    focus_on: function (el) {
        if (!$(el).hasClass('mw-focus')) {
            mw.$(".mw-focus").each(function () {
                this !== el ? mw.$(this).removeClass('mw-focus') : '';
            });
            mw.$(el).addClass('mw-focus');
        }
    },
    scrollTo: function (el, callback, minus) {
        minus = minus || 0;
        if ($(el).length === 0) {
            return false;
        }
        if (typeof callback === 'number') {
            minus = callback;
        }
        mw.$('html,body').stop().animate({scrollTop: mw.$(el).offset().top - minus}, function () {
            typeof callback === 'function' ? callback.call(el) : '';
        });
    },
    accordion: function (el, callback) {
        var speed = 200;
        var container = el.querySelector('.mw-accordion-content');
        if (container === null) return false;
        var is_hidden = mw.CSSParser(container).get.display() == 'none';
        if (!$(container).is(":animated")) {
            if (is_hidden) {
                mw.$(container).slideDown(speed, function () {
                    mw.$(el).addClass('active');
                    typeof callback === 'function' ? callback.call(el, 'visible') : '';
                });
            }
            else {
                mw.$(container).slideUp(speed, function () {
                    mw.$(el).removeClass('active');
                    typeof callback === 'function' ? callback.call(el, 'hidden') : '';
                });
            }
        }
    },
    index: function (el, parent, selector) {
        var el = mw.$(el)[0];
        var selector = selector || el.tagName.toLowerCase();
        var parent = parent || el.parentNode;
        if (parent.constructor === [].constructor) {
            var all = parent;
        }
        else {
            var all = mw.$(selector, parent)
        }
        var i = 0, l = all.length;
        for (; i < l; i++) {
            if (el === all[i]) return i;
        }
    },

    highlight: function (el, color, speed1, speed2) {
        if (!el) return false;
        mw.$(el).stop();
        var color = color || '#48AD79';
        var speed1 = speed1 || 777;
        var speed2 = speed2 || 777;
        var curr = window.getComputedStyle(el, null).backgroundColor;
        if (curr == 'transparent') {
            var curr = '#ffffff';
        }
        mw.$(el).animate({backgroundColor: color}, speed1, function () {
            mw.$(el).animate({backgroundColor: curr}, speed2, function () {
                mw.$(el).css('backgroundColor', '');
            })
        });
    },
    highlightStop: function (el) {
        mw.$(el).stop();
        mw.$(el).css('backgroundColor', '');
    },
    toCamelCase: function (str) {
        return str.replace(/(\-[a-z])/g, function (a) {
            return a.toUpperCase().replace('-', '');
        })
    },
    multihover: function () {
        var l = arguments.length, i = 1;
        var type = arguments[0].type;
        var check = ( type === 'mouseover' || type === 'mouseenter');
        for (; i < l; i++) {
            check ? mw.$(arguments[i]).addClass('hovered') : mw.$(arguments[i]).removeClass('hovered');
        }
    },
    search: function (string, selector, callback) {
        string = string.toLowerCase();
        if (typeof selector === 'object') {
            var items = selector;
        }
        else if (typeof selector === 'string') {
            var items = mwd.querySelectorAll(selector);
        }
        else {
            return false;
        }
        var i = 0, l = items.length;
        for (; i < l; i++) {
            items[i].textContent.toLowerCase().contains(string) ? callback.call(items[i], true) : callback.call(items[i], false);
        }
    },
    ajaxIsSearching: false,
    ajaxSearcSetting: {
        limit: 10,
        keyword: '',
        order_by: 'updated_at desc',
        search_in_fields: 'title'
    },
    ajaxSearch: function (o, callback) {
        if (!mw.tools.ajaxIsSearching) {
            var obj = $.extend({}, mw.tools.ajaxSearcSetting, o);
            mw.tools.ajaxIsSearching = $.post(mw.settings.site_url + "api/get_content_admin", obj, function (data) {
                if (typeof callback == 'function') {
                    callback.call(data, data);
                }
            }).always(function () {
                mw.tools.ajaxIsSearching = false
            });
        }
        return mw.tools.ajaxIsSearching;
    },
    getPostById: function (id, callback) {
        var config = {
            limit: 10,
            keyword: '',
            order_by: 'updated_at desc',
            search_in_fields: 'id',
            id: id
        };
        return this.ajaxSearch(config, callback);
    },
    iframeLinksToParent: function (iframe) {
        mw.$(iframe).contents().find('a').each(function () {
            var href = this.href;
            if (href.contains(mw.settings.site_url)) {
                this.target = '_parent';
            }
        });
    },
    get_filename: function (s) {
        if (s.contains('.')) {
            var d = s.lastIndexOf('.');
            return s.substring(s.lastIndexOf('/') + 1, d < 0 ? s.length : d);
        }
        else {
            return undefined;
        }
    },
    is_field: function (obj) {
        if (obj === null || typeof obj === 'undefined' || obj.nodeType === 3) {
            return false;
        }
        if (!obj.nodeName) {
            return false;
        }
        var t = obj.nodeName.toLowerCase();
        if (t === 'input' || t === 'textarea' || t === 'select') {
            return true
        }
        ;
        return false;
    },
    getAttrs: function (el) {
        var attrs = el.attributes;
        var obj = {}
        for (var x in attrs) {
            if (attrs[x].nodeName) {
                obj[attrs[x].nodeName] = attrs[x].nodeValue;
            }
        }
        return obj;
    },
    copyAttributes: function (from, to, except) {
        var except = except || [];
        var attrs = mw.tools.getAttrs(from);
        if (mw.tools.is_field(from) && mw.tools.is_field(to)) to.value = from.value;
        for (var x in attrs) {
            ( $.inArray(x, except) == -1 && x != 'undefined') ? to.setAttribute(x, attrs[x]) : '';
        }
    },
    isEmptyObject: function (obj) {
        for (var a in obj) {
            if (obj.hasOwnProperty(a)) return false;
        }
        return true;
    },
    objLenght: function (obj) {
        var len = 0, x;
        if (obj.constructor === {}.constructor) {
            for (var x in obj) {
                len++;
            }
        }
        return len;
    },
    scaleTo: function (selector, w, h) {
        var w = w || 800;
        var h = h || 600;
        var is_percent = w.toString().contains("%") ? true : false;
        var item = mw.$(selector);
        if (item.hasClass('mw-scaleto') || w == 'close') {
            item.removeClass('mw-scaleto');
            item.removeAttr('style');
        }
        else {
            item.parent().height(item.height());
            item.addClass('mw-scaleto');
            if (is_percent) {
                item.css({
                    width: w,
                    height: h,
                    left: ((100 - parseFloat(w)) / 2) + "%",
                    top: ((100 - parseFloat(h)) / 2) + "%"
                });
            }
            else {
                item.css({
                    width: w,
                    height: h,
                    marginLeft: -w / 2,
                    marginTop: -h / 2
                });
            }
        }
    },
    getFirstEqualFromTwoArrays: function (a, b) {
        var ia = 0, ib = 0, la = a.length, lb = b.length;
        loop:
            for (; ia < la; ia++) {
                var curr = a[ia];
                for (; ib < lb; ib++) {
                    if (b[ib] == curr) {
                        return curr;
                    }
                }
            }
    },
    tabGroup: function (obj, master, m) {
        var master = master || mwd.body;
        m = typeof m === 'undefined' ? true : m;
        if (m) {
            m = {
                set: function (i) {
                    if (typeof i === 'number') {
                        if (!$(obj.nav).eq(i).hasClass(active)) {
                            mw.$(obj.nav).removeClass(active);
                            mw.$(obj.nav).eq(i).addClass(active);
                            mw.$(obj.tabs).hide().eq(i).show();
                        }
                    }
                },
                setLastClicked: function () {
                    if ((typeof(obj.lastClickedTabIndex) != 'undefined') && obj.lastClickedTabIndex !== null) {
                        this.set(obj.lastClickedTabIndex);
                    }
                },
                unset: function (i) {
                    if (typeof i === 'number') {
                        if ($(obj.nav).eq(i).hasClass(active)) {
                            mw.$(obj.nav).eq(i).removeClass(active);
                            mw.$(obj.tabs).hide().eq(i).hide();
                        }
                    }
                },
                toggle: function (i) {
                    if (typeof i === 'number') {
                        if ($(obj.nav).eq(i).hasClass(active)) {
                            this.unset(i);
                        }
                        else {
                            this.set(i);
                        }
                    }
                }
            }
        }
        var active = obj.activeNav || obj.activeClass || "active active-info",
            firstActive = 0;

        obj.lastClickedTabIndex = null;


        mw.$(obj.nav).on('click', function (e) {
            if (!$(this).hasClass(active)) {
                var i = mw.tools.index(this, mw.$(obj.nav).get(), mw.$(obj.nav)[0].nodeName);
                mw.$(obj.nav).removeClass(active);
                mw.$(this).addClass(active);
                mw.$(obj.tabs).hide().eq(i).show();
                obj.lastClickedTabIndex = i;
                if (typeof obj.onclick == 'function') {
                    obj.onclick.call(this, mw.$(obj.tabs).eq(i)[0], e, i);
                }
            }
            else {
                if (obj.toggle == true) {
                    mw.$(this).removeClass(active);
                    mw.$(obj.tabs).hide();
                    if (typeof obj.onclick == 'function') {
                        var i = mw.tools.index(this, master, obj.nav);
                        obj.onclick.call(this, mw.$(obj.tabs).eq(i)[0], e, i);
                    }
                }
            }
            return false;
        }).each(function (i) {
            if (mw.tools.hasClass(this, active)) {
                firstActive = i;
            }
        });
        m.set(firstActive);
        return m;
    },
    has: function (el, what) {
        return el.querySelector(what) !== null;
    },
    html_info: function (html) {
        if (typeof mw._html_info === 'undefined') {
            mw._html_info = mwd.createElement('div');
            mw._html_info.id = 'mw-html-info';
            mwd.body.appendChild(mw._html_info);
        }
        mw.$(mw._html_info).html(html);
        return mw._html_info;
    },
    image_info: function (a, callback) {
        var img = mwd.createElement('img');
        img.className = 'semi_hidden';
        img.src = a.src;
        mwd.body.appendChild(img);
        img.onload = function () {
            callback.call({width: mw.$(img).width(), height: mw.$(img).height()});
            mw.$(img).remove();
        }
    },
    refresh_image: function (node) {
        node.src = mw.url.set_param('refresh_image', mw.random(), node.src);
        return node;
    },
    refresh: function (a) {
        if (a === null || typeof a === 'undefined') {
            return false;
        }
        if (a.src != '' && typeof a.src != 'undefined') {
            a.src = mw.url.set_param('mwrefresh', mw.random(), a.src);
        }
        else if (a.href != '' && typeof a.href != 'undefined') {
            a.href = mw.url.set_param('mwrefresh', mw.random(), a.href);
        }
    },
    getDiff: function (_new, _old) {
        var diff = {}, x, y;
        for (x in _new) {
            if (!x in _old || _old[x] != _new[x]) {
                diff[x] = _new[x];
            }
        }
        for (y in _old) {
            if (typeof _new[y] === 'undefined') {
                diff[y] = "";
            }
        }
        return diff;
    },

    liveEdit: function (el, textonly, callback, fieldClass) {
        if (!el || el.querySelector('.mw-live-edit-input') !== null) {
            return;
        }
        textonly = (typeof textonly === 'undefined') ? true : textonly;
        var input = mwd.createElement('span');
        input.className = (fieldClass || "") + ' mw-live-edit-input mw-liveedit-field';
        input.contentEditable = true;
        var $input = $(input);
        if (textonly === true) {
            input.innerHTML = el.textContent;
            input.onblur = function () {
                var val = $input.text();
                var ischanged = true;
                setTimeout(function () {
                    mw.$(el).text(val);
                    if (typeof callback === 'function' && ischanged) {
                        callback.call(val, el);
                    }
                }, 3);
            }
            input.onkeydown = function (e) {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    mw.$(el).text($input.text());
                    if (typeof callback === 'function') {
                        callback.call($input.text(), el);
                    }
                    return false;
                }
            }
        }
        else {
            input.innerHTML = el.innerHTML;
            input.onblur = function () {
                var val = this.innerHTML;
                var ischanged = this.changed === true;
                setTimeout(function () {
                    el.innerHTML = val;
                    if (typeof callback === 'function' && ischanged) {
                        callback.call(val, el);
                    }
                }, 3)
            }
            input.onkeydown = function (e) {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    var val = this.innerHTML;
                    el.innerHTML = val;
                    if (typeof callback === 'function') {
                        callback.call(val, el);
                    }
                    return false;
                }
            }
        }
        mw.$(el).empty().append(input);
        $input.focus();
        input.changed = false;
        $input.change(function () {
            this.changed = true;
        });
        return input;
    },
    objectExtend: function (str, value) {
        var arr = str.split("."), l = arr.length, i = 1;
        var t = typeof window[arr[0]] === 'undefined' ? {} : window[arr[0]];
        for (; i < l; i++) {
            t = t[arr[i]] = {};
        }
        window[arr[0]] = t;
    },
    parseHtml: function (html) {
        var doc = mwd.implementation.createHTMLDocument("");
        doc.body.innerHTML = html;
        return doc;
    },
    isEmpty: function (node) {
        return ( node.innerHTML.trim() ).length === 0;
    },
    isJSON: function (a) {
        if (typeof a === 'object') {
            if (a.constructor === {}.constructor) {
                return true;
            }
            else {
                return false;
            }
        }
        else if (typeof a === 'string') {
            try {
                JSON.parse(a);
            }
            catch (e) {
                return false;
            }
            return true;
        }
        else {
            return false;
        }
    },
    toJSON: function (w) {
        if (typeof w === 'object' && mw.tools.isJSON(w)) {
            return w;
        }
        if (typeof w === 'string') {
            try {
                var r = JSON.parse(w);
            }
            catch (e) {
                var r = {"0": w};
            }
            return r;
        }
        if (typeof w === 'object' && w.constructor === [].constructor) {
            var obj = {}, i = 0, l = w.length;
            for (; i < l; i++) {
                obj[i] = w[i];
            }
            return obj;
        }
    },
    mwattr: function (el, a, b) {
        if (!b && !!el) {
            var data = mw.$(el).dataset(a);
            if (!!$(el)[0].attributes) {
                var attr = mw.$(el)[0].attributes[a];
            }

            if (data !== '') {
                return data;
            }
            if (!!attr) {
                return attr.value;
            }
            return false;
        }
        else {
            mw.$(el).dataset(a, b);
        }
    },
    disable: function (el, text, global) {
        var text = text || mw.msg.loading + '...';
        var global = global || false;
        var _el = mw.$(el);
        if (_el.length == 0) {
            return false;
        }
        if (!_el.hasClass("disabled")) {
            _el.addClass('disabled');
            if (_el[0].tagName != 'INPUT') {
                _el.dataset("text", _el.html());
                _el.html(text);
            }
            else {
                _el.dataset("text", _el.val());
                _el.val(text);
            }
            if (global) mw.$(mwd.body).addClass("loading");
        }
        return el;
    },
    enable: function (el) {
        var _el = mw.$(el);
        if (_el.length == 0) {
            return false;
        }
        var text = _el.dataset("text");
        _el.removeClass("disabled");
        if (_el[0].tagName != 'INPUT') {
            _el.html(text);
        }
        else {
            _el.val(text);
        }
        mw.$(mwd.body).removeClass("loading");
        return el;
    },
    loading: function (el, state) {
        if (el === false) {
            this.loading('.mw-loading', false);
            this.loading('.mw-loading-defaults', false);
            return;
        }
        if (typeof el === 'boolean') {
            state = !!el;
            el = mwd.body;
        }
        if (typeof el === 'string') {
            var el = mwd.querySelector(el);
        }
        if (el === null || !el) return false;
        var state = typeof state === 'undefined' ? true : state;
        if (el !== mwd.body && el !== mwd.documentElement) {
            var pos = mw.CSSParser(el).get.position();
            if (pos == 'static') {
                mw.$(el).addClass("mw-loading-defaults");
            }
        }
        if (state) {
            mw.$(el).addClass("mw-loading");
        }
        else {
            mw.$(el).removeClass("mw-loading mw-loading-defaults");
        }
    },
    loading: function (element, progress, speed) {
        /*

         progress:number 0 - 100,
         speed:string, -> 'slow', 'normal, 'fast'

         mw.tools.loading(true) -> slowly animates to 95% on body
         mw.tools.loading(false) -> fast animates to 100% on body

         */
        function set(el, progress, speed) {
            speed = speed || 'normal';
            mw.tools.removeClass(el, 'mw-progress-slow');
            mw.tools.removeClass(el, 'mw-progress-normal');
            mw.tools.removeClass(el, 'mw-progress-fast');
            mw.tools.addClass(el, 'mw-progress-' + speed);
            element.__loadingTime = setTimeout(function () {
                el.querySelector('.mw-progress-index').style.width = progress + '%';
            }, 10)

        }

        if (typeof element === 'boolean') {
            progress = !!element;
            element = mwd.body;
        }
        if (typeof element === 'number') {
            progress = element;
            element = mwd.body;
        }
        if (element === document || element === mwd.documentElement) {
            element = mwd.body;
        }
        element = mw.$(element)[0]
        if (element === null || !element) return false;
        if (element.__loadingTime) {
            clearTimeout(element.__loadingTime)
        }
        var isLoading = mw.tools.hasClass(element, 'mw-loading');
        var el = element.querySelector('.mw-progress');
        if (!el) {
            el = document.createElement('div');
            el.className = 'mw-progress';
            el.innerHTML = '<div class="mw-progress-index"></div>';
            if (element === mwd.body) el.style.position = 'fixed';
            element.appendChild(el);
        }
        var pos = mw.CSSParser(element).get.position();
        if (pos == 'static') {
            element.style.position = 'relative';
        }
        if (progress) {
            if (progress === true) {
                set(el, 95, speed || 'slow')
            }
            else if (typeof progress === 'number') {
                progress = progress <= 100 ? progress : 100;
                progress = progress >= 0 ? progress : 0;
                set(el, progress, speed)
            }
        }
        else {
            if (el) {
                set(el, 100, speed || 'fast')
            }
            element.__loadingTime = setTimeout(function () {
                mw.$(element).removeClass('mw-loading-defaults mw-loading');
                mw.$(el).remove()
            }, 700)
        }
    },
    prependClass: function (el, cls) {
        el.className = (cls + ' ' + el.className).trim()
    },
    inview: function (el) {
        var $el = mw.$(el);
        if ($el.length === 0) {
            return false;
        }
        var dt = mw.$(window).scrollTop(),
            db = dt + mw.$(window).height(),
            et = $el.offset().top;
        return (et <= db) && !(dt > ($el.height() + et));
    },
    wholeinview: function (el) {
        var $el = mw.$(el),
            dt = mw.$(window).scrollTop(),
            db = dt + mw.$(window).height(),
            et = $el.offset().top,
            eb = et + mw.$(el).height();
        return ((eb <= db) && (et >= dt));
    },
    preload: function (u, c) {
        var im = new Image();
        if (typeof c === 'function') {
            im.onload = function () {
                c.call(u, im);
            }
        }
        im.src = u;
    },
    mapNodeValues: function (n1, n2) {
        if (!n1 || !n2 || n1 === null || n2 === null) return false;
        var setValue1 = ((!!n1.type && n1.nodeName !== 'BUTTON') || n1.nodeName === 'TEXTAREA') ? 'value' : 'textContent';
        var setValue2 = ((!!n2.type && n2.nodeName !== 'BUTTON') || n2.nodeName === 'TEXTAREA') ? 'value' : 'textContent';
        var events = 'keyup paste';
        mw.$(n1).bind(events, function () {
            n2[setValue2] = n1[setValue1];
            mw.$(n2).trigger('change');
        });
        mw.$(n2).bind(events, function () {
            n1[setValue1] = n2[setValue2];
            mw.$(n1).trigger('change');
        });
    },
    copyEvents: function (from, to) {
        if (typeof $._data(from, 'events') === 'undefined') {
            return false;
        }
        $.each($._data(from, 'events'), function () {
            $.each(this, function () {
                mw.$(to).bind(this.type, this.handler);
            });
        });
    },
    setTag: function (node, tag) {
        var el = mwd.createElement(tag);
        mw.tools.copyAttributes(node, el);
        while (node.firstChild) {
            el.appendChild(node.firstChild);
        }
        mw.tools.copyEvents(node, el);
        mw.$(node).replaceWith(el);
        return el;
    },

    generateSelectorForNode: function (node) {
        if (node === null || node.nodeType === 3) {
            return false;
        }
        if (node.nodeName === 'BODY') {
            return 'body';
        }
        if (!!node.id) {
            return '#' + node.id;
        }
        if(mw.tools.hasClass(node, 'edit')){
            var field = node.getAttribute('field');
            var rel = node.getAttribute('rel');
            if(field && rel){
                return '.edit[field="'+field+'"][rel="'+rel+'"]';
            }
        }
        var filter = function(item) {
            return item !== 'changed';
        };
        var _final = node.className != '' ? '.' + node.className.trim().split(' ').filter(filter).join('.') : node.nodeName.toLocaleLowerCase();


        _final = _final.replace(/\.\./g, '.');
        mw.tools.foreachParents(node, function (loop) {
            if (this.id != '') {
                _final = '#' + this.id + ' > ' + _final;
                mw.tools.stopLoop(loop);
                return false
            }
            if (this.className != '') {
                var n = this.nodeName.toLocaleLowerCase() + '.' + this.className.trim().split(' ').join('.');
            }
            else {
                var n = this.nodeName.toLocaleLowerCase();
            }
            _final = n + ' > ' + _final;
        });
        return _final;
    },
    cloneObject: function (r) {
        var a = {}, i;
        for (i in r) {
            if (r.hasOwnProperty(i)) {
                a[i] = r[i];
            }
        }
        return a;
    },
    notification: function (body, tag, icon) {
        if (!body) return;
        var n = window.Notification || window.webkitNotification || window.mozNotification;
        if (typeof n == 'undefined') {
            return false;
        }
        if (n.permission == 'granted') {
            new n("MW Update", {
                tag: tag || "Microweber",
                body: body,
                icon: icon || mw.settings.includes_url + "img/logomark.png"
            });
        }
        else if (n.permission == 'default') {
            n.requestPermission(function (result) {

                if (result == 'granted') {
                    return mw.tools.notification(body, tag, icon)
                }
                else if (result == 'denied') {
                    mw.notification.error('Notifications are blocked')
                }
                else if (result == 'default') {
                    mw.notification.warn('Notifications are canceled')

                }
            });
        }
    },
    module_settings: function (a, view, liveedit) {
        var opts = {};
        if (typeof liveedit === 'undefined') {
            opts.liveedit = true;
        }
        if (view != undefined) {
            opts.view = view;
        }
        else {
            opts.view = 'admin';
        }
        return mw.live_edit.showSettings(a, opts)
    },
    confirm_reset_module_by_id: function (module_id) {
        if (confirm("Are you sure you want to reset this module?")) {
            var is_a_preset = mw.$('#'+module_id).attr('data-module-original-id');
            var is_a_preset_attrs = mw.$('#'+module_id).attr('data-module-original-attrs');
            if(is_a_preset){
                var orig_attrs_decoded = JSON.parse(window.atob(is_a_preset_attrs));
                if (orig_attrs_decoded) {
                    mw.$('#'+module_id).removeAttr('data-module-original-id');
                    mw.$('#'+module_id).removeAttr('data-module-original-attrs');
                    mw.$('#'+module_id).attr(orig_attrs_decoded).reload_module();

                    if(  window.top.module_settings_modal_reference_preset_editor_thismodal ){
                        window.top.module_settings_modal_reference_preset_editor_thismodal.remove();
                    }
                 }
                 return;
            }

            var data = {};
            data.modules_ids = [module_id];

            var childs_arr = [];

            mw.$('#'+module_id).andSelf().find('.edit').each(function (i) {
                var some_child = {};

                mw.tools.removeClass(this, 'changed')
                some_child.rel = mw.$(this).attr('rel');
                some_child.field = mw.$(this).attr('field');

                childs_arr.push(some_child);

            });


            window.mw.on.DOMChangePause = true;

            if (childs_arr.length) {
                $.ajax({
                    type: "POST",
                   // dataType: "json",
                    //processData: false,
                    url: mw.settings.api_url + "content/reset_edit",
                    data: {reset:childs_arr}
                  //  success: success,
                  //  dataType: dataType
                });
           }


           //data-module-original-attrs

            $.ajax({
                type: "POST",
                // dataType: "json",
                //processData: false,
                url: mw.settings.api_url + "content/reset_modules_settings",
                data: data,
                success: function(){

                    setTimeout(function () {


                        mw.$('#'+module_id).removeAttr('data-module-original-id');
                        mw.reload_module('#'+module_id);
                        window.mw.on.DOMChangePause = false;

                    }, 1000);

                 },
            });
        }
    },
    open_reset_content_editor: function (root_element_id) {

        var src = mw.settings.site_url + 'api/module?id=mw_global_reset_content_editor&live_edit=true&module_settings=true&type=editor/reset_content&autosize=true';

        if(typeof(root_element_id) != 'undefined') {
            var src = src + '&root_element_id='+root_element_id;
        }

        var modal = mw.tools.modal.frame({
            url: src,
            // width: 500,
            // height: mw.$(window).height() - (2.5 * mw.tools.TemplateSettingsModalDefaults.top),
            name: 'mw-reset-content-editor-front',
            title: 'Reset content',
            template: 'default',
            center: false,
            resize: true,
            draggable: true
        });
    },
    open_global_module_settings_modal: function (module_type, module_id, modalOptions, additional_params) {


        var params = {};
        params.id = module_id;
        params.live_edit = true;
        params.module_settings = true;
        params.type = module_type;
        params.autosize = false;

        var params_url = $.extend({}, params, additional_params);

        var src = mw.settings.site_url + "api/module?" + json2url(params_url);


        modalOptions = modalOptions || {};

        var defaultOpts = {
            url: src,
            // width: 500,
            height: 'auto',
            autoHeight: true,
            name: 'mw-module-settings-editor-front',
            title: 'Settings',
            template: 'default',
            center: false,
            resize: true,
            draggable: true
        };

        var settings = $.extend({}, defaultOpts, modalOptions);

        // return mw.tools.modal.frame(settings);
        return mw.dialogIframe(settings);
    },
    open_module_modal: function (module_type, params, modalOptions) {

        var id = mw.id('module-modal-');
        var id_content = id + '-content';
        modalOptions = modalOptions || {};

        var settings = $.extend({}, {
            content: '<div class="module-modal-content" id="' + id_content + '"></div>',
            id: id
        }, modalOptions, {skin: 'default'});

        var xhr = false;
        var openiframe = false;
        if (typeof (settings.iframe) != 'undefined' && settings.iframe) {
            openiframe = true;
        }
        if (openiframe) {

            var additional_params = {};
            additional_params.type = module_type;
            var params_url = $.extend({}, params, additional_params);
            var src = mw.settings.site_url + "api/module?" + json2url(params_url);


            var settings = {
                url: src,
                name: 'mw-module-settings-editor-front',
                title: 'Settings',
                center: false,
                resize: true,
                draggable: true,
                height:'auto',
                autoHeight: true
            };
            return mw.top().dialogIframe(settings);
           // return mw.top().tools.modal.frame(settings);

        } else {
            delete settings.skin;
            delete settings.template;
            settings.height = 'auto';
            settings.autoHeight = true;
            settings.encapsulate = false;
            var modal = mw.dialog(settings);
            xhr = mw.load_module(module_type, '#' + id_content, function(){
                setTimeout(function(){
                    modal.center();
                },333)
            }, params);
        }


        return {
            xhr: xhr,
            modal: modal,
        }
    },
    fav: function (a) {
        var canvas = document.createElement("canvas");
        canvas.width = 16;
        canvas.height = 16;
        var context = canvas.getContext("2d");
        context.fillStyle = "#EF3D25";
        context.fillRect(0, 0, 16, 16);
        context.font = "normal 10px Arial";
        context.textAlign = 'center';
        context.textBaseline = 'middle';
        context.fillStyle = "white";
        context.fillText(a, 8, 8);
        var im = canvas.toDataURL();
        var l = document.createElement('link');
        l.className = 'mwfav';
        l.setAttribute('rel', 'icon');
        l.setAttribute('type', 'image/png');
        l.href = im;
        mw.$(".mwfav").remove();
        mwd.getElementsByTagName('head')[0].appendChild(l);
    },
    px2pt: function (px) {
        var n = parseInt(px, 10);
        if (isNaN(n)) {
            return false;
        }
        return Math.round(((3 / 4) * n));
    },

    progressDefaults: {
        skin: 'mw-ui-progress',
        action: mw.msg.loading + '...',
    },
    progress: function (obj) {
        if (typeof obj.element === 'string') {
            obj.element = mw.$(obj.element)[0];
        }
        if (obj.element === null || !obj.element) return false;
        if (!!obj.element.progressOptions) {
            return obj.element.progressOptions;
        }
        var obj = $.extend({}, mw.tools.progressDefaults, obj);
        var progress = mwd.createElement('div');
        progress.className = obj.skin;
        progress.innerHTML = '<div class="mw-ui-progress-bar" style="width: 0%;"></div><div class="mw-ui-progress-info">' + mw.tools.progressDefaults.action + '</div><span class="mw-ui-progress-percent">0%</span>';
        progress.progressInfo = obj;
        var options = {
            progress: progress,
            show: function () {
                this.progress.style.display = 'block';
            },
            hide: function () {
                this.progress.style.display = 'none'
            },
            remove: function () {
                progress.progressInfo.element.progressOptions = undefined;
                mw.$(this.progress).remove();
            },
            set: function (v, action) {
                if (v > 100) {
                    v = 100;
                }
                if (v < 0) {
                    v = 0;
                }
                action = action || this.progress.progressInfo.action;
                mw.$('.mw-ui-progress-bar', this.progress).css('width', v + '%');
                mw.$('.mw-ui-progress-percent', this.progress).html(v + '%');
            }
        };
        progress.progressOptions = obj.element.progressOptions = options;
        obj.element.appendChild(progress);
        return options;
    },
    matches: function (node, what) {
        if (node === 'init') {
            if (!!mwd.documentElement.matches) mw.tools.matchesMethod = 'matches';
            else if (!!mwd.documentElement.matchesSelector) mw.tools.matchesMethod = 'matchesSelector';
            else if (!!mwd.documentElement.mozMatchesSelector) mw.tools.matchesMethod = 'mozMatchesSelector';
            else if (!!mwd.documentElement.webkitMatchesSelector) mw.tools.matchesMethod = 'webkitMatchesSelector';
            else if (!!mwd.documentElement.msMatchesSelector) mw.tools.matchesMethod = 'msMatchesSelector';
            else if (!!mwd.documentElement.oMatchesSelector) mw.tools.matchesMethod = 'oMatchesSelector';
            else mw.tools.matchesMethod = undefined;
        }
        else {
            if (node === null) {
                return false;
            }
            if (typeof node === 'undefined') {
                return false;
            }
            if (node.nodeType !== 1) {
                return false;
            }
            if (!!mw.tools.matchesMethod) {
                return node[mw.tools.matchesMethod](what)
            }
            else {
                var doc = mwd.implementation.createHTMLDocument("");
                var node = node.cloneNode(true);
                doc.body.appendChild(node);
                var all = doc.body.querySelectorAll(what),
                    l = all.length,
                    i = 0;
                for (; i < l; i++) {
                    if (all[i] === node) {
                        return true;
                    }
                }
                return false;
            }
        }
    },
    titleTip: function (el) {
        if (mw.tools.hasClass(el, 'tip-disabled')) {
            mw.$(mw.tools._titleTip).hide();
            return false;
        }
        var skin = mw.$(el).attr('data-tipskin');
        var skin = (skin) ? skin : 'mw-tooltip-dark';
        var pos = mw.$(el).attr('data-tipposition');
        var iscircle = mw.$(el).attr('data-tipcircle') == 'true';
        if (!pos) {
            var pos = 'top-center';
        }
        var text = mw.$(el).attr('data-tip');
        if (!text) {
            var text = mw.$(el).attr('title');
        }
        if (!text) {
            var text = mw.$(el).attr('tip');
        }
        if (typeof text == 'undefined' || !text) {
            return;
        }
        if (text.indexOf('.') === 0 || text.indexOf('#') === 0) {
            var xitem = mw.$(text);
            if (xitem.length === 0) {
                return false;
            }
            var text = xitem.html();
        }
        else {
            var text = text.replace(/\n/g, '<br>');
        }
        var showon = mw.$(el).attr('data-showon');
        if (showon) {
            var el = mw.$(showon)[0];
        }
        if (!mw.tools._titleTip) {
            mw.tools._titleTip = mw.tooltip({skin: skin, element: el, position: pos, content: text});
            mw.$(mw.tools._titleTip).addClass('mw-universal-tooltip');
        }
        else {
            mw.tools._titleTip.className = 'mw-tooltip ' + pos + ' ' + skin + ' mw-universal-tooltip';
            mw.$('.mw-tooltip-content', mw.tools._titleTip).html(text);
            mw.tools.tooltip.setPosition(mw.tools._titleTip, el, pos);
        }
        if (iscircle) {
            mw.$(mw.tools._titleTip).addClass('mw-tooltip-circle');
        }
        else {
            mw.$(mw.tools._titleTip).removeClass('mw-tooltip-circle');
        }
        mw.$(mw.tools._titleTip).show();
    }
}
mw.tools.matches('init');
Alert = mw.tools.alert;
mw.wait('jQuery', function () {
    jQuery.fn.getDropdownValue = function () {
        return this.dataset("value") || mw.$('.active', this).attr('value');
    };
    jQuery.fn.setDropdownValue = function (val, triggerChange, isCustom, customValueToDisplay) {
//var _t1;
//var _that = this;
//clearTimeout(_t1);
        //  _t1 =  setTimeout(function(){
        var isCustom = isCustom || false;
        var triggerChange = triggerChange || false;
        var isValidOption = false;
        var customValueToDisplay = customValueToDisplay || false;
        var el = this;
        if (isCustom) {
            var isValidOption = true;
            el.dataset("value", val);
            triggerChange ? el.trigger("change") : '';
            var valel = mw.$(".mw-dropdown-val", el);
            var method = valel[0].type ? 'val' : 'html';
            if (!!customValueToDisplay) {
                valel[method](customValueToDisplay);
            }
            else {
                valel[method](val);
            }
        }
        else {
            mw.$("[value]", el).each(function () {
                if (this.getAttribute('value') == val) {
                    el.dataset("value", val);
                    var isValidOption = true;
                    var html = !!this.getElementsByTagName('a')[0] ? this.getElementsByTagName('a')[0].innerText : this.innerText;
                    mw.$(".mw-dropdown-val", el[0]).html(html);
                    if (triggerChange === true) {
                        el.trigger("change")
                    }
                    return false;
                }
            });
        }
        this.dataset("value", val);
        //    }, 100);
    };
    jQuery.fn.commuter = function (a, b) {
        if (a === undefined) {
            return false
        }
        var b = b || function () {
            };
        return this.each(function () {
            if ((this.type === 'checkbox' || this.type === 'radio') && !this.cmactivated) {
                this.cmactivated = true;
                mw.$(this).bind("change", function () {
                    this.checked === true ? a.call(this) : b.call(this);
                });
            }
        });
    };
});


mw.recommend = {
    get: function () {
        var cookie = mw.cookie.getEncoded("recommend");
        if (!cookie) {
            return {}
        }
        else {
            try {
                var val = $.parseJSON(cookie);
            }
            catch (e) {
                return;
            }
            return val;
        }
    },
    increase: function (item_name) {
        var json = mw.recommend.get();
        if (typeof(json) == 'undefined') {
            json = {};
        }
        var curr = parseFloat(json[item_name]);
        if (isNaN(curr)) {
            json[item_name] = 1;
        }
        else {
            json[item_name] += 1;
        }
        var tostring = JSON.stringify(json);
        mw.cookie.setEncoded("recommend", tostring, false, "/");
    },
    orderRecommendObject: function () {
        var obj = mw.recommend.get();
        if (!mw.tools.isEmptyObject(obj)) {
            var arr = [];
            for (var x in obj) {
                arr.push(x)
                arr.sort(function (a, b) {
                    return a[1] - b[1]
                })
            }
            return arr;
        }
    },
    set: function () {
        var arr = mw.recommend.orderRecommendObject(), l = arr.length, i = 0;
        for (; i < l; i++) {
            var m = mw.$('#tab_modules .module-item[data-module-name="' + arr[i] + '"]')[0];
            if (m !== null && typeof m !== 'undefined') {
                mw.$('#tab_modules ul').prepend(m);
            }
        }
    }
}




Array.prototype.remove = Array.prototype.remove || function (what) {
    var i = 0, l = this.length;
    for (; i < l; i++) {
        this[i] === what ? this.splice(i, 1) : '';
    }
};

mw.which = function (str, arr_obj, func) {
    if (arr_obj instanceof Array) {
        var l = arr_obj.length, i = 0;
        for (; i < l; i++) {
            if (arr_obj[i] === str) {
                func.call(arr_obj[i]);
                return arr_obj[i];
            }
        }
    }
    else {
        for (var i in arr_obj) {
            if (i === str) {
                func.call(arr_obj[i]);
                return arr_obj[i];
            }
        }
    }
}

mw.isDragItem = mw.isBlockLevel = function (obj) {
    return mw.ea.helpers.isBlockLevel(obj);
};
mw._JSPrefixes = ['Moz', 'Webkit', 'O', 'ms'];
_Prefixtest = false;
mw.JSPrefix = function (property) {
    !_Prefixtest ? _Prefixtest = mwd.body.style : '';
    if (_Prefixtest[property] !== undefined) {
        return property;
    }
    else {
        var property = property.charAt(0).toUpperCase() + property.slice(1),
            len = mw._JSPrefixes.length,
            i = 0;
        for (; i < len; i++) {
            if (_Prefixtest[mw._JSPrefixes[i] + property] !== undefined) {
                return mw._JSPrefixes[i] + property;
            }
        }
    }
}
if (typeof document.hidden !== "undefined") {
    _mwdochidden = "hidden";
} else if (typeof document.mozHidden !== "undefined") {
    _mwdochidden = "mozHidden";
} else if (typeof document.msHidden !== "undefined") {
    _mwdochidden = "msHidden";
} else if (typeof document.webkitHidden !== "undefined") {
    _mwdochidden = "webkitHidden";
}
document.isHidden = function () {
    if (typeof _mwdochidden !== 'undefined') {
        return document[_mwdochidden];
    }
    else {
        return !document.hasFocus();
    }
}







mw.postMsg = function (w, obj) {
    w.postMessage(JSON.stringify(obj), window.location.href);
};





/* Exposing to mw  */



mw.uploader = function (o) {
    var uploader = mw.files.uploader(o);
    var el = mw.$(o.element)[0];
    if (typeof el !== 'undefined') {
        el.appendChild(uploader);
    }
    return uploader;
};
mw.dropdown = mw.tools.dropdown;
mw.confirm = mw.tools.confirm;
mw.tabs = mw.tools.tabGroup;
mw.progress = mw.tools.progress;
mw.external = function (o) {
    return mw.tools._external(o);
};
mw.fileWindow = function (config) {
    config = config || {};
    var url = config.types ? ("rte_image_editor?types=" + config.types + '#fileWindow') : ("rte_image_editor#fileWindow");
    var url = mw.settings.site_url + 'editor_tools/' + url;
    var modal = mw.top().dialogIframe({
        url: url,
        name: "mw_rte_image",
        width: 430,
        height: 'auto',
        autoHeight: true,
        //template: 'mw_modal_basic',
        overlay: true
    });
    var frameWindow = mw.$('iframe', modal.main)[0].contentWindow;
    frameWindow.onload = function () {
        frameWindow.$('body').on('change', function (e, url, m) {
            if (config.change) {
                config.change.call(undefined, url);
                modal.remove()
            }
        });
    };
};

mw.editor = mw.tools.richtextEditor;

mw.accordion = function (el, callback) {
    return mw.tools.accordion(mw.$(el)[0], callback);
};




mw.ajax = function (options) {
    var xhr = $.ajax(options);
    return xhr;
};

mw.ajax = $.ajax;

jQuery.each(["xhrGet", "xhrPost"], function (i, method) {
    mw[method] = function (url, data, callback, type) {
        if (jQuery.isFunction(data)) {
            type = type || callback;
            callback = data;
            data = undefined;
        }
        return mw.ajax(jQuery.extend({
            url: url,
            type: i == 0 ? 'GET' : 'POST',
            dataType: type,
            data: data,
            success: callback
        }, jQuery.isPlainObject(url) && url));
    };
});







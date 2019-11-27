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
$.fn.reload_module = function (c) {
    return this.each(function () {
        //   if($(this).hasClass("module")){
        (function (el) {
            mw.reload_module(el, function () {
                if (typeof(c) != 'undefined') {
                    c.call(el, el)
                }
            })
        })(this)
        //   }
    })
};
$.fn.dataset = function (dataset, val) {
    var el = this[0];
    if (el === undefined) return false;
    var _dataset = !dataset.contains('-') ? dataset : mw.tools.toCamelCase(dataset);
    if (!val) {
        var dataset = !!el.dataset ? el.dataset[_dataset] : mw.$(el).attr("data-" + dataset);
        return dataset !== undefined ? dataset : "";
    }
    else {
        !!el.dataset ? el.dataset[_dataset] = val : mw.$(el).attr("data-" + dataset, val);
        return mw.$(el);
    }
};

String.prototype._exec = function (a, b, c) {
    var a = a || "";
    var b = b || "";
    var c = c || "";
    if (!this.contains(".")) {
        return window[this](a, b, c);
    }
    else {
        var arr = this.split(".");
        var temp = window[arr[0]];
        var len = arr.length - 1;
        for (var i = 1; i <= len; i++) {
            if (typeof temp === 'undefined') {
                break;
                return false;
            }
            var temp = temp[arr[i]];
        }
        return mw.is.func(temp) ? temp(a, b, c) : temp;
    }
};
mw.exec = function (str, a, b, c) {
    return str._exec(a, b, c);
};
String.prototype.endsWith = function (str) {
    return this.indexOf(str, this.length - str.length) !== -1;
};
mw.controllers = {}
mw.external_tool = function (url) {
    return !url.contains("/") ? mw.settings.site_url + "editor_tools/" + url : url;
}
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
        frame._intPause = false;
        frame._int = setInterval(function(){
            if(!frame._intPause && frame.parentNode && frame.contentWindow  && frame.contentWindow.$){
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
    tooltip: {
        source: function (content, skin, position, id) {
            if (skin == 'dark') {
                var skin = 'mw-tooltip-dark';
            } else if (skin == 'warning') {
                var skin = 'mw-tooltip-default mw-tooltip-warning';
            }

            if (typeof id === 'undefined') {
                id = 'mw-tooltip-' + mw.random();
            }
            var tooltip = mwd.createElement('div');
            var tooltipc = mwd.createElement('div');
            tooltip.className = 'mw-tooltip ' + position + ' ' + skin;
            tooltipc.className = 'mw-tooltip-content';
            tooltip.id = id;
            $(tooltipc).append(content)
            $(tooltip).append(tooltipc).append('<span class="mw-tooltip-arrow"></span>')
            mwd.body.appendChild(tooltip);
            return tooltip;
        },
        setPosition: function (tooltip, el, position) {
            el = mw.$(el);
            if (el.length === 0) {
                return false;
            }
            tooltip.tooltipData.element = el[0];
            var w = el.outerWidth(),
                tipwidth = mw.$(tooltip).outerWidth(),
                h = el.outerHeight(),
                tipheight = mw.$(tooltip).outerHeight(),
                off = el.offset(),
                arrheight = mw.$('.mw-tooltip-arrow', tooltip).height();
            if (off.top === 0 && off.left === 0) {
                off = mw.$(el).parent().offset();
            }
            mw.tools.removeClass(tooltip, tooltip.tooltipData.position);
            mw.tools.addClass(tooltip, position);
            tooltip.tooltipData.position = position;

            off.left = off.left > 0 ? off.left : 0;
            off.top = off.top > 0 ? off.top : 0;

            var leftCenter = off.left - tipwidth / 2 + w / 2;
            leftCenter = leftCenter > 0 ? leftCenter : 0;

            if (position === 'auto') {
                var $win = mw.$(window);
                var wxCenter =  $win.width()/2;
                var wyCenter =  ($win.height() + $win.scrollTop())/2;
                var elXCenter =  off.left +(w/2);
                var elYCenter =  off.top +(h/2);
                var xPos, yPost;
                var space = 100;

                if(elXCenter > wxCenter) {
                    xPos = 'left';
                } else {
                    xPos = 'right';
                }

                yPos = 'top'


                return this.setPosition (tooltip, el, (xPos+'-'+yPos));
            }

            if (position === 'bottom-left') {
                mw.$(tooltip).css({
                    top: off.top + h + arrheight,
                    left: off.left
                });
            }
            else if (position === 'bottom-center') {
                mw.$(tooltip).css({
                    top: off.top + h + arrheight,
                    left: leftCenter
                });
            }
            else if (position === 'bottom-right') {
                mw.$(tooltip).css({
                    top: off.top + h + arrheight,
                    left: off.left - tipwidth + w
                });
            }
            else if (position === 'top-right') {
                mw.$(tooltip).css({
                    top: off.top - tipheight - arrheight,
                    left: off.left - tipwidth + w
                });
            }
            else if (position === 'top-left') {
                mw.$(tooltip).css({
                    top: off.top - tipheight - arrheight,
                    left: off.left
                });
            }
            else if (position === 'top-center') {

                mw.$(tooltip).css({
                    top: off.top - tipheight - arrheight,
                    left: leftCenter
                });
            }
            else if (position === 'left-top') {
                mw.$(tooltip).css({
                    top: off.top,
                    left: off.left - tipwidth - arrheight
                });
            }
            else if (position === 'left-bottom') {
                mw.$(tooltip).css({
                    top: (off.top + h) - tipheight,
                    left: off.left - tipwidth - arrheight
                });
            }
            else if (position === 'left-center') {
                mw.$(tooltip).css({
                    top: off.top - tipheight / 2 + h / 2,
                    left: off.left - tipwidth - arrheight
                });
            }
            else if (position === 'right-top') {
                mw.$(tooltip).css({
                    top: off.top,
                    left: off.left + w + arrheight
                });
            }
            else if (position === 'right-bottom') {
                mw.$(tooltip).css({
                    top: (off.top + h) - tipheight,
                    left: off.left + w + arrheight
                });
            }
            else if (position === 'right-center') {
                mw.$(tooltip).css({
                    top: off.top - tipheight / 2 + h / 2,
                    left: off.left + w + arrheight
                });
            }
            if (parseFloat($(tooltip).css('top')) < 0) {
                mw.$(tooltip).css('top', 0);
            }
        },
        fixPosition: function (tooltip) {
            /* mw_todo */
            var max = 5;
            var arr = mw.$('.mw-tooltip-arrow', tooltip);
            arr.css('left', '');
            var arr_left = parseFloat(arr.css('left'));
            var tt = mw.$(tooltip);
            if (tt.length === 0) {
                return false;
            }
            var w = tt.width(),
                off = tt.offset(),
                ww = mw.$(window).width();
            if ((off.left + w) > (ww - max)) {
                var diff = off.left - (ww - w - max);
                tt.css('left', ww - w - max);
                arr.css('left', arr_left + diff);
            }
            if (parseFloat(tt.css('top')) < 0) {
                tt.css('top', 0);
            }
        },
        prepare: function (o) {
            if (typeof o.element === 'undefined') return false;
            if (o.element === null) return false;
            if (typeof o.element === 'string') {
                o.element = mw.$(o.element)
            }

            if (o.element.constructor === [].constructor && o.element.length === 0) return false;
            if (typeof o.position === 'undefined') {
                o.position = 'auto';
            }
            if (typeof o.skin === 'undefined') {
                o.skin = 'mw-tooltip-default';
            }
            if (typeof o.id === 'undefined') {
                o.id = 'mw-tooltip-' + mw.random();
            }
            if (typeof o.group === 'undefined') {
                o.group = null;
            }
            return {
                id: o.id,
                element: o.element,
                skin: o.template || o.skin,
                position: o.position,
                content: o.content,
                group: o.group
            }
        },
        init: function (o, wl) {

            var orig_options = o;
            var o = mw.tools.tooltip.prepare(o);
            if (o === false) return false;
            if (o.id && mw.$('#' + o.id).length > 0) {
                var tip = mw.$('#' + o.id)[0];
            } else {
                var tip = mw.tools.tooltip.source(o.content, o.skin, o.position, o.id);
            }
            tip.tooltipData = o;
            var wl = wl || true;
            if (o.group) {
                var tip_group_class = 'mw-tooltip-group-' + o.group;
                var cur_tip = mw.$(tip)
                if (!cur_tip.hasClass(tip_group_class)) {
                    cur_tip.addClass(tip_group_class)
                }
                var cur_tip_id = cur_tip.attr('id');
                if (cur_tip_id) {
                    mw.$("." + tip_group_class).not("#" + cur_tip_id).hide();
                    if (o.group && typeof orig_options.close_on_click_outside !== 'undefined' && orig_options.close_on_click_outside) {
                        setTimeout(function () {
                            mw.$("#" + cur_tip_id).show();
                        }, 100);
                    } else {
                        mw.$("#" + cur_tip_id).show();
                    }
                }
            }
            if (wl && $.contains(self.document, tip)) {
                /*
                 //position bug: resize fires in modal frame
                 mw.$(self).bind('resize scroll', function (e) {
                 if (self.document.contains(tip)) {
                 self.mw.tools.tooltip.setPosition(tip, tip.tooltipData.element, o.position);
                 }
                 });*/
                if (o.group && typeof orig_options.close_on_click_outside !== 'undefined' && orig_options.close_on_click_outside) {
                    mw.$(self).bind('click', function (e, target) {
                        mw.$("." + tip_group_class).hide();
                    });
                }
            }
            mw.tools.tooltip.setPosition(tip, o.element, o.position);
            return tip;
        }
    },
    tip: function (o) {
        return mw.tools.tooltip.init(o);
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
    modal: {
        settings: {
            width: 600,
            height: 500,
            draggable: true
        },
        source: function (id, template, icon) {
            template = template || 'mw_modal_primary';
            if (template === 'basic') {
                template = 'mw_modal_basic';
            }
            if (template === 'default') {
                template = 'mw_modal_primary';
            }
            if (template === 'simple') {
                template = 'mw_modal_simple';
            }
            id = id || "modal_" + mw.random();
            var html = ''
                + '<div class=" mw_modal mw_modal_maximized ' + template + '" id="' + id + '">'
                + '<div class="mw_modal_toolbar">'
                + (icon ? '<span class="mw_modal_icon">' + icon + '</span>' : '')
                + '<span class="mw_modal_title"></span>'
                + '<span class="mw-modal-close"  title="' + mw.msg.close + '"></span>'
                + '</div>'
                + '<div class="mw_modal_container">'
                + '</div>'
                + '<div class="iframe_fix"></div>'
                + '</div>';
            return {html: html, id: id}
        },
        _init: function (o) {
            var html = o.html,
                width = o.width,
                height = o.height,
                callback = o.callback,
                title = o.title,
                name = o.name,
                template = o.template,
                overlay = o.overlay,
                draggable = o.draggable,
                onremove = o.onremove,
                icon = o.icon,
                onopen = o.onopen;
            if (typeof name === 'string' && mw.$("#" + name).length > 0) {
                return false;
            }

            var modal = mw.tools.modal.source(name, template, icon);
            mw.$(mwd.body).append(modal.html);
            var _modal = mwd.getElementById(modal.id);
            if (!_modal.remove) {
                _modal.remove = function () {
                    mw.tools.modal.remove(modal.id);
                }
            }
            var modal_object = mw.$(_modal);
            mw.$('.mw_modal_container', _modal).append(html);
            mw.tools.modal.setDimmensions(_modal, width, height, false);
            mw.$(".mw-tooltip").hide();
            modal_object.show();
            mw.tools.modal.center(_modal);
            draggable = typeof draggable !== 'undefined' ? draggable : true;
            if (typeof $.fn.draggable === 'function' && draggable) {
                modal_object.addClass("mw-modal-draggable");
                modal_object.draggable({
                    handle: '.mw_modal_toolbar',
                    containment: 'window',
                    iframeFix: false,
                    distance: 10,
                    drag: function (e, ui) {
                        if (ui.position.top < 0) ui.position.top = 0;
                    },
                    start: function () {
                        mw.$(this).find(".iframe_fix").show();
                        if ($(".mw_modal").length > 1) {
                            var mw_m_max = parseFloat($(this).css("zIndex"));
                            mw.$(".mw_modal").not(this).each(function () {
                                var z = parseFloat($(this).css("zIndex"));
                                mw_m_max = z >= mw_m_max ? z + 1 : mw_m_max;
                            });
                            mw.$(this).css("zIndex", mw_m_max);
                        }
                    },
                    stop: function (e, ui) {
                        mw.$(this).find(".iframe_fix").hide();
                        var w = mw.$(window).width();
                        if (this.style.width.toString().contains("%")) {
                        }
                        if (this.style.height.toString().contains("%")) {
                        }
                    }
                });
            }
            else {
                mw.$(".mw_modal_toolbar", mwd.getElementById(modal.id)).css("cursor", "default");
            }
            var modal_return = {main: modal_object, container: modal_object.find(".mw_modal_container")[0]}
            typeof callback === 'function' ? callback.call(modal_return) : '';
            typeof title === 'string' ? mw.$(modal_object).find(".mw_modal_title").html(title) : '';
            typeof name === 'string' ? mw.$(modal_object).attr("name", name) : '';
            modal_return.onremove = typeof onremove === 'function' ? onremove : false;
            if (overlay == true) {
                var ol = mw.tools.modal.overlay(modal_object);
                if (o.overlayRemovesModal) {
                    ol.onclick = function () {
                        modal_return.remove();
                    }
                }
                modal_object[0].overlay = ol;
                modal_return.overlay = ol;
            }
            mwd.getElementById(modal.id).modal = modal_return;
            modal_return.hide = function () {
                modal_object.hide();
                mw.$(modal_return.overlay).hide();
                mw.$(modal_return).trigger('modal.hide');
                return this;
            }
            modal_return.show = function () {
                modal_object.show();
                mw.$(modal_return.overlay).show();
                mw.$(modal_return).trigger('modal.show');
                return modal_return;
            }
            modal_return.remove = function () {
                mw.$(modal_return).trigger('modal.remove');
                if (typeof modal_return.onremove === 'function') {
                    modal_return.onremove.call(window, modal_return);
                }
                modal_object.remove();
                mw.$(modal_return.overlay).remove();
            }
            mw.$('.mw-modal-close', modal_object[0]).bind('click', function () {
                modal_return.remove();
            });
            modal_return.center = function (a) {
                mw.tools.modal.center(modal_object, a);
                return modal_return;
            }
            modal_return.resize = function (w, h) {
                mw.tools.modal.setDimmensions(modal_object[0], w, h);
                mw.$(modal_return).trigger('modal.resize');
                return modal_return;
            }
            if (onopen) {
                onopen.call(window, modal_return)
            }
            var max = 0;
            mw.$(".mw_modal, .mw-dialog").each(function () {
                var z = parseInt($(this).css('zIndex'), 10);
                if (!isNaN(z)) {
                    max = z > max ? z : max;
                }
            }).css('zIndex', max);
            return modal_return;
        },

        init: function (o) {
            var o = $.extend({}, mw.tools.modal.settings, o);
            if (typeof o.content !== 'undefined' && typeof o.html === 'undefined') {
                o.html = o.content;
            }
            if (typeof o.id !== 'undefined' && typeof o.name === 'undefined') {
                o.name = o.id;
            }
            return new mw.tools.modal._init(o);
        },
        get: function (selector) {
            return mw.dialog.get(selector);
        },
        minimize: function (id) {
            var doc = mwd;
            var modal = mw.$("#" + id);
            var window_h = mw.$(doc.defaultView).height();
            var window_w = mw.$(doc.defaultView).width();
            var modal_width = modal.width();
            var old_position = {
                width: modal.css("width"),
                height: modal.css("height"),
                left: modal.css("left"),
                top: modal.css("top")
            }
            modal.data("old_position", old_position);
            var margin = 24 * ($(".is_minimized").length);
            modal.addClass("is_minimized");
            modal.animate({
                top: window_h - 24 - margin,
                left: window_w - modal_width,
                height: 24
            });
            if (typeof $.fn.draggable === 'function') {
                modal.draggable("option", "disabled", true);
            }
        },
        maximize: function (id) {
            var modal = mw.$("#" + id);
            modal.removeClass("is_minimized");
            modal.animate(modal.data("old_position"));
            if (typeof $.fn.draggable === 'function') {
                modal.draggable("option", "disabled", false);
            }
        },
        minimax: function (id) {
            if ($("#" + id).hasClass("is_minimized")) {
                mw.tools.modal.maximize(id);
            }
            else {
                mw.tools.modal.minimize(id);
            }
        },
        settings_window: function (callback) {
            var modal = mw.modal("");
            return modal;
        },
        frame: function (obj) {
            obj = $.extend({}, mw.tools.modal.settings, obj);

            var frame = "<iframe name='frame-" + obj.name + "' id='frame-" + obj.name + "' style='overflow-x:hidden;overflow-y:auto;' class='mw-modal-frame' src='" + mw.external_tool(obj.url) + "'  frameBorder='0' allowfullscreen></iframe>";
            obj.html = frame;
            var modal = mw.tools.modal.init(obj);
            mw.$(modal.main).addClass("mw_modal_type_iframe");
            mw.$(modal.container).css("overflow", "hidden");
            if (typeof modal.main == 'undefined') {
                return;
            }
            modal.main[0].querySelector('iframe').contentWindow.thismodal = modal;
            modal.main[0].querySelector('iframe').onload = function () {
                typeof obj.callback === 'function' ? obj.callback.call(modal, this) : '';
                typeof obj.onload === 'function' ? obj.onload.call(modal, this) : '';
            };
            modal.iframe = modal.container.querySelector('iframe');
            return modal;
        },
        remove: function (id) {
            if (typeof id === 'object') {
                if (id.constructor === {}.constructor) {
                    var id = mw.$(id.main)[0].id;
                }
                else {
                    var id = mw.$(id)[0].id;
                }
            }
            if (!id.contains("#")) {
                var el = mwd.getElementById(id);
            }
            else {
                var el = mw.$(id)[0];
            }
            if (el === null) {
                return false;
            }
            if (!!el.overlay) {
                mw.$(el.overlay).remove();
            }
            mw.$(el).remove();
        },
        modalCompletelyVisibleFromParent: function (modalMain, parentFixedElement) {
            if (!modalMain || modalMain === null || !modalMain.querySelector) return true;
            var frame = mww.frameElement;
            if (frame === null) return true; // means it's the same window
            if (!frame) return true; // in case property does not exists
            if (frame.offsetHeight < mw.$(parent).height()) return true;
            var _top = modalMain.offsetTop;
            var wh = parent.innerHeight;
            var dt = mw.$(parent.document).scrollTop();
            var ft = mw.$(frame).offset().top;
            modalMain.style.maxHeight = wh - 100 + 'px';
            var zero = dt - ft;
            var mtop = zero + wh / 2 - modalMain.offsetHeight / 2;
            if (mtop < zero) {
                var mtop = zero;
            }
            if (mtop < 0) {
                var mtop = 0;
            }
            if (!!parentFixedElement) {
                mtop += parentFixedElement.offsetHeight;
            }
            modalMain.style.top = mtop + 'px';
        },

        setDimmensions: function (modal, w, h, trigger) {
            if (typeof modal === 'string') {
                var modal = mw.$(modal)[0];
            }
            if (!modal || modal === null) return false;
            var trigger = trigger || false;
            var root = modal.constructor === {}.constructor ? mw.$(modal.main)[0] : modal;
            var win = mw.$(window),
                maxW = win.width() - 50,
                maxH = win.height() - 50;
            if (!!w) {
                if (typeof w === 'number') {
                    var w = w < maxW ? w : maxW;
                }
                root.style.width = mw.tools.cssNumber(w);
            }
            if (!!h) {
                var toolbar_height = mw.$('.mw_modal_toolbar', modal).outerHeight();
                if (typeof h == 'string') {
                    if (h.indexOf('px') !== -1) {
                        h = parseInt(h, 10);
                        h = h + toolbar_height
                    }
                }
                else {
                    h = h + toolbar_height
                }
                if (typeof h === 'number') {
                    var h = h < maxH ? h : maxH;
                }
                root.style.height = mw.tools.cssNumber(h);
            }
            var toolbarHeight = mw.$('.mw_modal_toolbar', root).outerHeight();
            mw.tools.modal.containerHeight(mw.$('.mw_modal_container', root)[0])
            if (trigger) {
                mw.$(root).trigger("resize");
            }
        },
        containerHeight: function (container) {
            if (!container || container === null) return false;
            if (container.parentNode.parentNode === null /* if modal is removed from DOM  */) {
                if (!!container.modalContainerInt) {
                    clearInterval(container.modalContainerInt);
                }
                return false;
            }
            var root = container.parentNode;
            var toolbarHeight = mw.$('.mw_modal_toolbar', root).outerHeight();
            var cp = mw.CSSParser(container);
            var final = ($(root).outerHeight() - toolbarHeight - cp.get.margin(true).top) + 'px'
            if (container.style.height != final) {
                container.style.height = final;
            }
            if (!container.modalContainerInt) {
                container.modalContainerInt = setInterval(function () {
                    mw.tools.modal.containerHeight(container);
                }, 333);
            }
        },
        resize: function (modal, w, h, center) {
            mw.tools.modal.setDimmensions(modal, w, h);
            if (center === true) {
                mw.tools.modal.center(modal)
            }
            ;
        },
        center: function (modal, only) {
            var only = only || 'all';
            var modal = mw.$(modal);
            var h = modal.height();
            var w = modal.width();
            var top = ($(mww).height() / 2) - (h / 2);
            var top = top > 0 ? top : 0;
            var left = ($(mww).width() / 2) - (w / 2);
            var left = left > 0 ? left : 0;
            if (only == 'all') {
                modal.css({top: top, left: left});
            }
            else if (only == 'vertical') {
                modal.css({top: top});
            }
            else if (only == 'horizontal') {
                modal.css({left: left});
            }
            if (self !== parent) {
                mw.tools.modal.modalCompletelyVisibleFromParent(modal[0]);
            }
        },
        overlay: function (modal) {
            var overlay = mwd.createElement('div');
            overlay.className = 'mw_overlay';
            var id = !!modal ? mw.$(modal).attr("id") : 'none';
            mw.$(overlay).attr("rel", id);
            mwd.body.appendChild(overlay);
            return overlay;
        }
    },
    gallery: {
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
            var modal = modal || top.mw.tools.modal.init({
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
    hasClass: function (classname, whattosearch) {
        if (classname === null) {
            return false;
        }
        if (typeof classname === 'string') {
            return classname.split(' ').indexOf(whattosearch) > -1;
        }
        else if (typeof classname === 'object') {
            return mw.tools.hasClass(classname.className, whattosearch);
        }
        else {
            return false;
        }
    },
    hasAllClasses: function (node, arr) {
        if (!node) return;
        var has = true;
        var i = 0, nodec = node.className.trim().split(' ');
        for (; i < arr.length; i++) {
            if (nodec.indexOf(arr[i]) === -1) {
                return false;
            }
        }
        return has;
    },
    hasAnyOfClasses: function (node, arr) {
        if (!node) return;
        var i = 0, l = arr.length, cls = node.className;
        for (; i < l; i++) {
            if (mw.tools.hasClass(cls, arr[i])) {
                return true;
            }
        }
        return false;
    },
    firstWithBackgroundImage: function (node) {
        if (!node) return false;
        if (!!node.style.backgroundImage) return node;
        var final = false;
        mw.tools.foreachParents(node, function (loop) {
            if (!!this.style.backgroundImage) {
                mw.tools.stopLoop(loop);
                final = this;
            }
        });
        return final;
    },

    parentsOrCurrentOrderMatchOrOnlyFirstOrNone: function (node, arr) {
        return !mw.tools.hasAnyOfClassesOnNodeOrParent(node, [arr[1]]) || mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, arr)
    },
    parentsOrCurrentOrderMatchOrOnlyFirst: function (node, arr) {
        var curr = node;
        while (curr !== document.body) {
            var h1 = mw.tools.hasClass(curr, arr[0]);
            var h2 = mw.tools.hasClass(curr, arr[1]);
            if (h1 && h2) {
                return false;
            }
            else {
                if (h1) {
                    return true;
                }
                else if (h2) {
                    return false;
                }
            }
            curr = curr.parentNode;
        }
        return false;
    },
    parentsOrCurrentOrderMatchOrOnlyFirstOrNone: function (node, arr) {
        var curr = node;
        while (curr && curr !== mwd.body) {
            var h1 = mw.tools.hasClass(curr, arr[0]);
            var h2 = mw.tools.hasClass(curr, arr[1]);
            if (h1 && h2) {
                return false;
            }
            else {
                if (h1) {
                    return true;
                }
                else if (h2) {
                    return false;
                }
            }
            curr = curr.parentNode;
        }
        return true;
    },
    parentsOrCurrentOrderMatch: function (node, arr) {
        var curr = node,
            match = {a: 0, b: 0},
            count = 1,
            hadA = false;
        while (curr !== document.body) {
            count++;
            var h1 = mw.tools.hasClass(curr, arr[0]);
            var h2 = mw.tools.hasClass(curr, arr[1]);
            if (h1 && h2) {
                if (match.a > 0) {
                    return true;
                }
                return false;
            }
            else {
                if (h1) {
                    match.a = count;
                    hadA = true;
                }
                else if (h2) {
                    match.b = count;
                }
                if (match.b > match.a) {
                    return hadA ? true : false;
                }
            }
            curr = curr.parentNode;
        }
        return false;
    },
    parentsOrCurrentOrderMatchOrNone:function(node, arr){
        if(!node) return false;
        var curr = node,
            match = {a: 0, b: 0},
            count = 1,
            hadA = false;
        while (curr && curr !== document.body) {
            count++;
            var h1 = mw.tools.hasClass(curr, arr[0]);
            var h2 = mw.tools.hasClass(curr, arr[1]);
            if (h1 && h2) {
                if (match.a > 0) {
                    return true;
                }
                return false;
            }
            else {
                if (h1) {
                    match.a = count;
                    hadA = true;
                }
                else if (h2) {
                    match.b = count;
                }
                if (match.b > match.a) {
                    return hadA ? true : false;
                }
            }
            curr = curr.parentNode;
        }
        return match.a === 0 && match.b === 0;
    },
    parentsOrCurrentOrderMatchOrOnlyFirstOrBoth: function (node, arr) {
        var curr = node,
            has1 = false,
            has2 = false;
        while (curr && curr !== document.body) {
            var h1 = mw.tools.hasClass(curr, arr[0]);
            var h2 = mw.tools.hasClass(curr, arr[1]);
            if (h1 && h2) {
                return true;
            }
            else {
                if (h1) {
                    return true;
                }
                else if (h2) {
                    return false;
                }
            }
            curr = curr.parentNode;
        }
        return false;
    },
    matchesAnyOnNodeOrParent: function (node, arr) {
        var curr = node;
        while (curr && curr !== document.body) {
            var i = 0;
            for (; i < arr.length; i++) {
                if (mw.tools.matches(curr, arr[i])) {
                    return true;
                }
            }
            curr = curr.parentNode;
        }
        return false;
    },
    firstMatchesOnNodeOrParent: function (node, arr) {
        if (!arr) return;
        if (typeof arr === 'string') {
            arr = [arr];
        }
        var curr = node;
        while (curr && curr !== document.body) {
            var i = 0;
            for (; i < arr.length; i++) {
                if (mw.tools.matches(curr, arr[i])) {
                    return curr;
                }
            }
            curr = curr.parentNode;
        }
        return false;
    },
    lastMatchesOnNodeOrParent: function (node, arr) {
        if (!arr) return;
        if (typeof arr === 'string') {
            arr = [arr];
        }
        var curr = node, result;
        while (curr && curr !== document.body) {
            var i = 0;
            for (; i < arr.length; i++) {
                if (mw.tools.matches(curr, arr[i])) {
                    result = curr;
                }
            }
            curr = curr.parentNode;
        }
        return result;
    },
    hasAnyOfClassesOnNodeOrParent: function (node, arr) {
        var curr = node;
        while (curr && curr !== document.body) {
            var i = 0;
            for (; i < arr.length; i++) {
                if (mw.tools.hasClass(curr, arr[i])) {
                    return true;
                }
            }
            curr = curr.parentNode;
        }
        return false;
    },
    addClass: function (el, cls) {
        if (!cls || !el) {
            return false;
        }
        if (el.fn) {
            el = el[0];
            if (!el) {
                return;
            }
        }
        if (typeof cls === 'string') {
            cls = cls.trim();
        }
        if (!el) return;
        var arr = cls.split(" ");
        var i = 0;
        if (arr.length > 1) {
            for (; i < arr.length; i++) {
                mw.tools.addClass(el, arr[i]);
            }
            return;
        }
        if (typeof el === 'object') {
            if (el.classList) {
                el.classList.add(cls);
            }
            else {
                if (!mw.tools.hasClass(el.className, cls)) el.className += (' ' + cls);
            }
        }
        if (typeof el === 'string') {
            if (!mw.tools.hasClass(el, cls)) el += (' ' + cls);
        }
    },
    removeClass: function (el, cls) {
        if (typeof cls === 'string') {
            cls = cls.trim();
        }
        if (!cls || !el) return;
        if (el === null) {
            return false;
        }
        if (el.fn) {
            el = el[0];
            if (!el) {
                return;
            }
        }
        if (typeof el === 'undefined') {
            return false;
        }
        if (el.constructor === [].constructor) {
            var i = 0, l = el.length;
            for (; i < l; i++) {
                mw.tools.removeClass(el[i], cls);
            }
            return;
        }
        if (typeof(cls) === 'object') {
            var arr = cls;
        } else {
            var arr = cls.split(" ");
        }
        var i = 0;
        if (arr.length > 1) {
            for (; i < arr.length; i++) {
                mw.tools.removeClass(el, arr[i]);
            }
            return;
        }
        else if (!arr.length) {
            return;
        }
        if (el.classList && cls) {
            el.classList.remove(cls);
        }
        else {
            if (mw.tools.hasClass(el.className, cls)) el.className = (el.className + ' ').replace(cls + ' ', '').replace(/\s{2,}/g, ' ').trim();
        }

    },
    isEventOnElement: function (event, node) {
        if (event.target === node) {
            return true;
        }
        mw.tools.foreachParents(event.target, function () {
            if (event.target === node) {
                return true;
            }
        });
        return false;
    },
    isEventOnElements: function (event, array) {
        var l = array.length, i = 0;
        for (; i < l; i++) {
            if (event.target === array[i]) {
                return true;
            }
        }
        var isEventOnElements = false;
        mw.tools.foreachParents(event.target, function () {
            var l = array.length, i = 0;
            for (; i < l; i++) {
                if (event.target === array[i]) {
                    isEventOnElements = true;
                }
            }
        });
        return isEventOnElements;
    },
    isEventOnClass: function (event, cls) {
        if (mw.tools.hasClass(event.target, cls) || mw.tools.hasParentsWithClass(event.target, cls)) {
            return true;
        }
        return false;
    },
    hasParentsWithClass: function (el, cls) {
        if (!el) return;
        var curr = el.parentNode;
        while (curr && curr !== mwd.body) {
            if (mw.tools.hasClass(curr, cls)) {
                return true;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    hasParentWithId: function (el, id) {
        if (!el) return;
        var curr = el.parentNode;
        while (curr && curr !== mwd.body) {
            if (curr.id === id) {
                return true;
            }
            curr = curr.parentNode;
        }
        return false;
    },

    hasChildrenWithTag: function (el, tag) {
        var tag = tag.toLowerCase();
        var has = false;
        mw.tools.foreachChildren(el, function (loop) {
            if (this.nodeName.toLowerCase() === tag) {
                has = true;
                mw.tools.stopLoop(loop);
            }
        });
        return has;
    },
    hasParentsWithTag: function (el, tag) {
        if (!el || !tag) return;
        tag = tag.toLowerCase();
        var curr = el.parentNode;
        while (curr && curr !== mwd.body) {
            if (curr.nodeName.toLowerCase() === tag) {
                return true;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    hasHeadingParent: function (el) {
        if (!el) return;
        var h = /^(h[1-6])$/i;
        var curr = el.parentNode;
        while (curr && curr !== mwd.body) {
            if (h.test(curr.nodeName.toLowerCase())) {
                return true;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    loop: {/* Global index for MW loops */},
    stopLoop: function (loop) {
        mw.tools.loop[loop] = false;
    },
    foreachParents: function (el, callback) {
        if (!el) return false;
        var index = mw.random();
        mw.tools.loop[index] = true;
        var _curr = el.parentNode;
        var count = -1;
        if (_curr !== null && _curr !== undefined) {
            var _tag = _curr.tagName;
            while (_tag !== 'BODY') {
                count++;
                var caller = callback.call(_curr, index, count), _curr = _curr.parentNode;
                if (caller === false || _curr === null || _curr === undefined || !mw.tools.loop[index]) {
                    delete mw.tools.loop[index];
                    break;
                }
                var _tag = _curr.tagName;
            }
        }
    },
    foreachChildren: function (el, callback) {
        if (!el) return false;
        var index = mw.random();
        mw.tools.loop[index] = true;
        var _curr = el.firstChild;
        var count = -1;
        if (_curr !== null && _curr !== undefined) {
            while (_curr.nextSibling !== null) {
                if (_curr.nodeType === 1) {
                    count++;
                    var caller = callback.call(_curr, index, count);
                    _curr = _curr.nextSibling;
                    if (caller === false || _curr === null || _curr === undefined || !mw.tools.loop[index]) {
                        delete mw.tools.loop[index];
                        break
                    }
                    var _tag = _curr.tagName;
                }
                else {
                    _curr = _curr.nextSibling;
                }
            }
        }
    },
    firstChildWithClass: function (parent, cls) {
        var toreturn;
        mw.tools.foreachChildren(parent, function (loop) {
            if (this.nodeType === 1 && mw.tools.hasClass(this, cls)) {
                mw.tools.stopLoop(loop);
                toreturn = this;
            }
        });
        return toreturn;
    },
    firstChildWithTag: function (parent, tag) {
        var toreturn;
        var tag = tag.toLowerCase();
        mw.tools.foreachChildren(parent, function (loop) {
            if (this.nodeName.toLowerCase() === tag) {
                toreturn = this;
                mw.tools.stopLoop(loop);
            }
        });
        return toreturn;
    },
    hasChildrenWithClass: function (node, cls) {
        var final = false;
        mw.tools.foreachChildren(node, function () {
            if (mw.tools.hasClass(this.className, cls)) {
                final = true;
            }
        });
        return final;
    },
    parentsOrder: function (node, arr) {
        var only_first = [];
        var obj = {}, l = arr.length, i = 0, count = -1;
        for (; i < l; i++) {
            obj[arr[i]] = -1;
        }
        if (!node) return obj;

        var curr = node.parentNode;
        while (curr && curr !== mwd.body) {
            count++;
            var cls = curr.className;
            i = 0;
            for (; i < l; i++) {
                if (mw.tools.hasClass(cls, arr[i]) && only_first.indexOf(arr[i]) === -1) {
                    obj[arr[i]] = count;
                    only_first.push(arr[i]);
                }
            }
            curr = curr.parentNode;
        }
        return obj;
    },
    parentsAndCurrentOrder: function (node, arr) {
        var only_first = [];
        var obj = {}, l = arr.length, i = 0, count = -1;
        for (; i < l; i++) {
            obj[arr[i]] = -1;
        }
        if (!node) return obj;

        var curr = node;
        while (curr && curr !== mwd.body) {
            count++;
            var cls = curr.className;
            i = 0;
            for (; i < l; i++) {
                if (mw.tools.hasClass(cls, arr[i]) && only_first.indexOf(arr[i]) === -1) {
                    obj[arr[i]] = count;
                    only_first.push(arr[i]);
                }
            }
            curr = curr.parentNode;
        }
        return obj;
    },
    firstParentWithClass: function (el, cls) {
        if (!el) return false;
        var curr = el.parentNode;
        while (curr && curr !== mwd.body) {
            if (curr.classList.contains(cls)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    firstParentOrCurrentWithClass: function (el, cls) {
        if (!el) return false;
        var curr = el;
        while (curr && curr !== mwd.body) {
            if (mw.tools.hasClass(curr, cls)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    firstBlockLevel: function (el) {
        while(el && el !== document.body) {
            if(mw.ea.helpers.isBlockLevel(el)) {
                return el;
            }
            el = el.parentNode;
        }
    },
    firstParentOrCurrentWithId: function (el, id) {
        if (!el) return false;
        var curr = el;
        while (curr && curr !== mwd.body) {
            if (curr.id === id) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    firstParentOrCurrentWithAllClasses: function (node, arr) {
        if (!node) return false;
        var curr = node;
        while (curr && curr !== mwd.body) {
            if (mw.tools.hasAllClasses(curr, arr)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    firstParentOrCurrentWithAnyOfClasses: function (node, arr) {
        if (!node) return false;
        var curr = node;
        while (curr && curr !== mwd.body) {
            if (!curr) return false;
            if (mw.tools.hasAnyOfClasses(curr, arr)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    lastParentWithClass: function (el, cls) {
        if (!el) return;
        var _has = false;
        var curr = el.parentNode;
        while (curr && curr !== mwd.body) {
            if (mw.tools.hasClass(curr, cls)) {
                _has = curr;
            }
            curr = curr.parentNode;
        }
        return _has;
    },
    firstParentWithTag: function (el, tag) {
        if (!el) return;
        tag = typeof tag !== 'string' ? tag : [tag];
        var curr = el.parentNode;
        while (curr && curr !== mwd.body) {
            if (tag.indexOf(curr.nodeName.toLowerCase()) !== -1) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
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
    sidebar: function () {
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
    fullscreen: function (el) {
        if (el.requestFullScreen) {
            el.requestFullScreen();
        }
        else if (el.webkitRequestFullScreen) {
            el.webkitRequestFullScreen();
        }
        else if (el.mozRequestFullScreen) {
            el.mozRequestFullScreen();
        }
        else if (el.msRequestFullscreen) {
            el.msRequestFullscreen();
        }
    },
    isFullscreenAvailable: function () {
        var b = mwd.body;
        return 'requestFullScreen' in b || 'webkitRequestFullScreen' in b || 'mozRequestFullScreen' in b || 'msRequestFullscreen' in b || false;
    },
    cancelFullscreen: function () {
        if (mwd.exitFullscreen) {
            mwd.exitFullscreen();
        }
        else if (mwd.mozCancelFullScreen) {
            mwd.mozCancelFullScreen();
        }
        else if (mwd.webkitExitFullscreen) {
            mwd.webkitExitFullscreen();
        }
        else if (mwd.msExitFullscreen) {
            mwd.msExitFullscreen();
        }
    },
    toggleFullscreen: function (el) {
        var infullscreen = mwd.fullScreen || mwd.webkitIsFullScreen || mwd.mozFullScreen || false;
        if (infullscreen) {
            mw.tools.cancelFullscreen();
        }
        else {
            mw.tools.fullscreen(el)
        }
    },
    fullscreenChange: function (c) {
        if ('addEventListener' in document) {
            document.addEventListener("fullscreenchange", function () {
                c.call(document.fullscreen);
            }, false);
            document.addEventListener("mozfullscreenchange", function () {
                c.call(document.mozFullScreen);
            }, false);
            document.addEventListener("webkitfullscreenchange", function () {
                c.call(document.webkitIsFullScreen);
            }, false);
        }
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

    elementEdit: function (el, textonly, callback, fieldClass) {
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
    iframe_editor: function (area, params, k, type) {
        var params = params || {};
        var k = k || false;
        var type = type || 'wysiwyg';
        var params = typeof params === 'object' ? json2url(params) : params;
        var area = mw.$(area);
        var frame = mwd.createElement('iframe');
        frame.src = mw.external_tool(type + '?' + params);
        frame.className = 'mw-iframe-editor';
        frame.scrolling = 'no';
        var name = 'mweditor' + mw.random();
        frame.id = name;
        frame.name = name;
        frame.style.backgroundColor = "transparent";
        frame.setAttribute('frameborder', 0);
        frame.setAttribute('allowtransparency', 'true');
        area.hide().after(frame);
        mw.$(frame).load(function () {
            frame.contentWindow.thisframe = frame;
            var cont = mw.$(frame).contents().find("#mw-iframe-editor-area");
            mw.wysiwyg.contentEditable(cont[0], true);
            if (!k) {
                if (area[0].tagName === 'TEXTAREA') {
                    cont.html(area[0].value);
                    this.value = area[0].value;
                }
                else {
                    cont.html(area.html());
                    this.value = area.html();
                }
            }
            if (typeof frame.contentWindow.PrepareEditor === 'function') {
                frame.contentWindow.PrepareEditor();
            }
        });
        mw.$(frame).bind('change', function (e, val) {
            if (area[0].tagName === 'TEXTAREA') {
                area.val(val);
            }
            else {
                area.html(val);
            }
            if (area.hasClass("mw_option_field")) {
                area.trigger("change");
            }
            this.value = val;
        });
        return frame;
    },
    wysiwyg: function (area, params, k) {
        var k = k || false;
        return mw.tools.iframe_editor(area, params, k);
    },
    richtextEditorSettings: {
        width: '100%',
        height: 'auto',
        addControls: false,
        hideControls: false,
        ready: false
    },
    richtextEditor: function (obj) {
        if (typeof obj.element === 'string') {
            obj.element = mw.$(obj.element)[0];
        }
        if (!obj.element || obj.element === undefined) return false;

        var o = $.extend({}, mw.tools.richtextEditorSettings, obj);
        var frame = mwd.createElement('iframe');
        frame.richtextEditorSettings = o;
        frame.className = 'mw-fullscreen mw-iframe-editor';
        frame.scrolling = 'no';
        var name = 'mw-editor' + mw.random();
        frame.id = name;
        frame.name = name;
        frame.style.backgroundColor = "white";
        frame.setAttribute('frameborder', 0);
        frame.setAttribute('allowtransparency', 'true');
        mw.$(o.element).after(frame);
        mw.$(o.element).hide();
        $.get(mw.external_tool('editor_toolbar'), function (a) {
            if (frame.contentWindow.document === null) {
                return;
            }
            frame.contentWindow.document.open('text/html', 'replace');
            frame.contentWindow.document.write(a);
            frame.contentWindow.document.close();
            frame.contentWindow.editorArea = o.element;
            frame.contentWindow.thisFrame = frame;
            frame.contentWindow.pauseChange = true;
            frame.contentWindow.richtextEditorSettings = o;

            frame.onload = function () {
                var val = o.element.nodeName !== 'TEXTAREA' ? o.element.innerHTML : o.element.value
                frame.contentWindow.document.getElementById('editor-area').innerHTML = val;
                if (!!o.hideControls && o.hideControls.constructor === [].constructor) {
                    var l = o.hideControls.length, i = 0;
                    for (; i < l; i++) {
                        mw.$('.mw_editor_' + o.hideControls[i], frame.contentWindow.document).hide();
                    }
                }
                if (!!o.addControls && (typeof o.addControls === 'string' || typeof o.addControls === 'function' || !!o.addControls.nodeType)) {
                    mw.$('.editor_wrapper', frame.contentWindow.document).append(o.addControls);
                }
                frame.api = frame.contentWindow.mw.wysiwyg;
                if (typeof o.ready === 'function') {
                    o.ready.call(frame, frame.contentWindow.document);
                }
                setTimeout(function () {
                    if (frame.contentWindow) {
                        frame.contentWindow.pauseChange = false;
                    }

                }, frame.contentWindow.SetValueTime);
                mw.$(obj.element).on('sourceChanged', function(e, val){
                    frame.contentWindow.document.getElementById('editor-area').innerHTML = val;
                })
                mw.$(frame.contentWindow.document.getElementById('editor-area')).on('keyup paste change', function(){
                    if (frame.richtextEditorSettings.element.nodeName !== 'TEXTAREA') {
                        frame.richtextEditorSettings.element.innerHTML = this.innerHTML
                    }  else {
                        frame.richtextEditorSettings.element.value = this.innerHTML;
                    }
                })
                frame.contentWindow.mw.tools.createStyle(undefined, '#editor-area{' + (obj.style || '') + '}');
            }
            mw.$(obj.element).on('sourceChanged', function (e, val) {
                frame.contentWindow.document.getElementById('editor-area').innerHTML = val;
            });

        });
        o.width = o.width != 'auto' ? o.width : '100%';
        mw.$(frame).css({width: o.width, height: o.height});
        if(o.height === 'auto') {
            mw.tools.iframeAutoHeight(frame);
        }
        frame.setValue = function (val) {
            frame.contentWindow.pauseChange = true;
            frame.contentWindow.document.getElementById('editor-area').innerHTML = val;
            if (frame.richtextEditorSettings.element.nodeName !== 'TEXTAREA') {
                frame.richtextEditorSettings.element.innerHTML = val
            }
            else {
                frame.richtextEditorSettings.element.value = val;
            }
            frame.value = val;
            frame.contentWindow.pauseChange = false;
        }
        return frame;
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
mw.tools.base64 = {
// private property
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
// public method for encoding
    encode: function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;
        input = mw.tools.base64._utf8_encode(input);
        while (i < input.length) {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);
            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;
            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }
            output = output +
                this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
                this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
        }
        return output;
    },
// public method for decoding
    decode: function (input) {
        if (typeof input == 'undefined') {
            return;
        }
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));
            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
        }
        output = mw.tools.base64._utf8_decode(output);
        return output;
    },
// private method for UTF-8 encoding
    _utf8_encode: function (string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }
        }
        return utftext;
    },
// private method for UTF-8 decoding
    _utf8_decode: function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while (i < utftext.length) {
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    }
}
mw.cookie = {
    get: function (name) {
        var cookies = mwd.cookie.split(";"), i = 0, l = cookies.length;
        for (; i < l; i++) {
            var x = cookies[i].substr(0, cookies[i].indexOf("="));
            var y = cookies[i].substr(cookies[i].indexOf("=") + 1);
            var x = x.replace(/^\s+|\s+$/g, "");
            if (x == name) {
                return unescape(y);
            }
        }
    },
    set: function (name, value, expires, path, domain, secure) {
        var now = new Date();
        var expires = expires || 365;
        now.setTime(now.getTime());
        if (expires) {
            var expires = expires * 1000 * 60 * 60 * 24;
        }
        var expires_date = new Date(now.getTime() + (expires));
        document.cookie = name + "=" + escape(value) + ( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) + ( ( path ) ? ";path=" + path : ";path=/" ) + ( ( domain ) ? ";domain=" + domain : "" ) + ( ( secure ) ? ";secure" : "" );
    },
    setEncoded: function (name, value, expires, path, domain, secure) {
        // value = encodeURIComponent(value);
        // value = escape(value);
        //value = mw.tools.base64.encode( unescape( encodeURIComponent( value ) ) )
        value = mw.tools.base64.encode(value)
        return this.set(name, value, expires, path, domain, secure)
    },
    getEncoded: function (name) {
        var value = this.get(name);
        // value = decodeURIComponent(value);
        //value = unescape(value);
        //value = decodeURIComponent( escape( mw.tools.base64.decode( value ) ) )
        value = mw.tools.base64.decode(value)
        return value;
    },
    ui: function (a, b) {
        var mwui = mw.cookie.getEncoded("mwui");
        try {
            var mwui = (!mwui || mwui == '') ? {} : $.parseJSON(mwui);
        }
        catch (e) {
            return false;
        }
        if (typeof a === 'undefined') {
            return mwui;
        }
        if (typeof b === 'undefined') {
            return mwui[a] !== undefined ? mwui[a] : "";
        }
        else {
            mwui[a] = b;
            var tostring = JSON.stringify(mwui);
            mw.cookie.setEncoded("mwui", tostring, false, "/");
            if (typeof mw.cookie.uievents[a] !== 'undefined') {
                var funcs = mw.cookie.uievents[a], l = funcs.length, i = 0;
                for (; i < l; i++) {
                    mw.cookie.uievents[a][i].call(b.toString());
                }
            }
        }
    },
    uievents: {},
    changeInterval: null,
    uiCurr: null,
    onchange: function (name, func) {
        if (typeof mw.cookie.uievents[name] === 'undefined') {
            mw.cookie.uievents[name] = [func];
        }
        else {
            mw.cookie.uievents[name].push(func);
        }
    }
}



mw.check = {
    all: function (selector) {
        mw.$(selector).find("input[type='checkbox']").each(function () {
            this.checked = true;
        });
    },
    none: function (selector) {
        mw.$(selector).find("input[type='checkbox']").each(function () {
            this.checked = false;
        });
    },
    toggle: function (selector) {
        var els = mw.$(selector).find("input[type='checkbox']"), checked = els.filter(':checked');
        if (els.length === checked.length) {
            mw.check.none(selector)
        }
        else {
            mw.check.all(selector);
        }
    },
    collectChecked: function (parent) {
        var arr = [];
        var all = parent.querySelectorAll('input[type="checkbox"]'), i = 0, l = all.length;
        for (; i < l; i++) {
            var el = all[i];
            el.checked ? arr.push(el.value) : '';
        }
        return arr;
    }
}

Array.prototype.remove = Array.prototype.remove || function (what) {
        var i = 0, l = this.length;
        for (; i < l; i++) {
            this[i] === what ? this.splice(i, 1) : '';
        }
    };

Array.prototype.min = function () {
    return Math.min.apply(Math, this);
};
Array.prototype.max = function () {
    return Math.max.apply(Math, this);
};

$(window).load(function () {
    mw.loaded = true;
    mw.tools.addClass(mwd.body, 'loaded');
    mw.tools.removeClass(mwd.body, 'loading');
    mw.$('div.mw-ui-field').click(function (e) {
        if (e.target.type != 'text') {
            try {
                this.querySelector('input[type="text"]').focus();
            }
            catch (e) {
            }
        }
    });
});
mw._dump = function (obj) {
    var obj = obj || mw;
    var html = '<ol class="mw-dump-list">'
    $.each(obj, function (a, b) {
        if (typeof b === 'function') {
            var c = '' + b + '';
            var c = c.split(')')[0];
            var c = '<i>' + c + ')</i>';
        }
        else if (typeof b === 'object') {
            var c = '<a href="javascript:;" onclick="mw.dialog({height: \'auto\', autoHeight: true, html: \'<h2>mw.' + a + '</h2>\' + mw._dump(mw.' + a + ')});"> + Object</a>';
        }
        else {
            var c = b.toString()
        }
        html = html + '<li>' + a + ' : ' + c + '</li>';
    });
    html = html + '</ol>';
    return html;
};
mw.dump = function () {
    mw.dialog({
        html: mw._dump(),
        width: 800,
        height: 'auto',
        autoHeight: true
    });
};
mw.notification = {
    msg: function (data, timeout, _alert) {
        var timeout = timeout || 1000;
        var _alert = _alert || false;
        if (data != undefined) {
            if (data.success != undefined) {
                if (!_alert) {
                    mw.notification.success(data.success, timeout);
                }
                else {
                    Alert(data.success);
                }
            }
            if (data.error != undefined) {
                mw.notification.error(data.error, timeout);
            }
            if (data.warning != undefined) {
                mw.notification.warning(data.warning, timeout);
            }
        }
    },
    build: function (type, text) {
        var div = mwd.createElement('div');
        div.className = 'mw-notification mw-' + type;
        div.innerHTML = '<div>' + text + '</div>'
        return div;
    },
    append: function (type, text, timeout) {
        var timeout = timeout || 1000;
        var div = mw.notification.build(type, text);
        if (typeof mw.notification._holder === 'undefined') {
            mw.notification._holder = mwd.createElement('div');
            mw.notification._holder.id = 'mw-notifications-holder';
            mwd.body.appendChild(mw.notification._holder);
        }
        mw.notification._holder.appendChild(div);
        var w = mw.$(div).outerWidth();
        mw.$(div).css("marginLeft", -(w / 2));
        setTimeout(function () {
            div.style.opacity = 0;
            setTimeout(function () {
                mw.$(div).remove();
            }, 1000);
        }, timeout);
    },
    success: function (text, timeout) {
        var timeout = timeout || 1000;
        mw.notification.append('success', text, timeout);
    },
    error: function (text, timeout) {
        var timeout = timeout || 1000;
        mw.notification.append('error', text, timeout);
    },
    warning: function (text, timeout) {
        var timeout = timeout || 1000;
        mw.notification.append('warning', text, timeout);
    }
};
$.fn.visible = function () {
    return this.css("visibility", "visible").css("opacity", "1");
};
$.fn.visibilityDefault = function () {
    return this.css("visibility", "").css("opacity", "");
};
$.fn.invisible = function () {
    return this.css("visibility", "hidden").css("opacity", "0");
};

$.fn.mwDialog = function(conf){
    var el = this[0];
    var options = mw.tools.elementOptions(el);
    var id = mw.id('mwDialog-');
    var idEl = mw.id('mwDialogTemp-');
    var defaults = {
        height: 'auto',
        autoHeight: true,
        width: 'auto'
    };
    var settings = $.extend({}, defaults, options, conf);
    if(conf === 'close' || conf === 'hide' || conf === 'remove'){
        if(el._dialog){
            el._dialog.remove()
        }
        return;
    }
    $(el).before('<mw-dialog-temp id="'+idEl+'"></mw-dialog-temp>');
    var dialog = mw.dialog(settings);
    el._dialog = dialog;
    dialog.dialogContainer.appendChild(el);
    $(el).show();
    if(settings.width === 'auto'){
        dialog.width($(el).width);
        dialog.center($(el).width);
    }
    $(dialog).on('BeforeRemove', function(){
        mw.$('#' + idEl).replaceWith(el);
        $(el).hide();
        el._dialog = null;
    });
    return this;
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


mw._crossWindowEvents = false;
mw.crossWindowEvent = function (ename, data) {
    data = data || mw.random();
    var rootName = 'mwCrossWindowEvent_';
    if (!mw._crossWindowEvents) {
        mw._crossWindowEvents = true;
        mww.addEventListener('storage', function (e) {
            if (e.key.indexOf(rootName) !== -1) {
                var item = localStorage.getItem(rootName + ename);
                mw.trigger(ename, [JSON.parse(item)]);
            }
        });
    }
    localStorage.setItem(rootName + ename, JSON.stringify(data));
}


mw.storage = {
    init: function () {
        if (window.location.href.indexOf('data:') === 0 || !('localStorage' in mww) || /* IE Security configurations */ typeof mww['localStorage'] === 'undefined') return false;
        var lsmw = localStorage.getItem("mw");
        if (typeof lsmw === 'undefined' || lsmw === null) {
            var lsmw = localStorage.setItem("mw", "{}")
        }
        this.change("INIT");
        return lsmw;
    },
    set: function (key, val) {
        if (!('localStorage' in mww)) return false;
        var curr = JSON.parse(localStorage.getItem("mw"));
        curr[key] = val;
        var a = localStorage.setItem("mw", JSON.stringify(curr))
        mw.storage.change("CALL", key, val);
        return a;
    },
    get: function (key) {
        if (!('localStorage' in mww)) return false;
        var curr = JSON.parse(localStorage.getItem("mw"));
        return curr[key];
    },
    _keys: {},
    change: function (key, callback, other) {
        if (!('localStorage' in mww)) return false;
        if (key === 'INIT' && 'addEventListener' in document) {
            addEventListener('storage', function (e) {
                if (e.key === 'mw') {
                    var _new = JSON.parse(e.newValue || {});
                    var _old = JSON.parse(e.oldValue || {});
                    var diff = mw.tools.getDiff(_new, _old);
                    for (var t in diff) {
                        if (t in mw.storage._keys) {
                            var i = 0, l = mw.storage._keys[t].length;
                            for (; i < l; i++) {
                                mw.storage._keys[t][i].call(diff[t]);
                            }
                        }
                    }
                }
            }, false);
        }
        else if (key === 'CALL') {
            if (!document.isHidden() && typeof mw.storage._keys[callback] !== 'undefined') {
                var i = 0, l = mw.storage._keys[callback].length;
                for (; i < l; i++) {
                    mw.storage._keys[callback][i].call(other);
                }
            }
        }
        else {
            if (key in mw.storage._keys) {
                mw.storage._keys[key].push(callback);
            }
            else {
                mw.storage._keys[key] = [callback];
            }
        }
    }
}
mw.storage.init();

mw.postMsg = function (w, obj) {
    w.postMessage(JSON.stringify(obj), window.location.href);
};
$(document).ready(function () {
    mw.on('mwDialogShow', function(){
        mw.$(document.documentElement).addClass('mw-dialog-opened');
    });
    mw.on('mwDialogHide', function(){
        mw.$(document.documentElement).removeClass('mw-dialog-opened');
    });
    mw.$(mwd.body).bind('mousemove touchmove touchstart', function (event) {
        if (mw.tools.hasClass(event.target, 'tip')) {
            mw.tools.titleTip(event.target);
        }
        else if (mw.tools.hasParentsWithClass(event.target, 'tip')) {
            mw.tools.titleTip(mw.tools.firstParentWithClass(event.target, 'tip'));
        }
        else {
            mw.$(mw.tools._titleTip).hide();
        }
    });
    mw.$(".mw-onoff").each(function () {
        if (!$(this).hasClass('activated')) {
            mw.$(this).addClass('activated');
            mw.$(this).mousedown(function () {
                var el = this;
                if (mw.tools.hasClass(el, 'active')) {
                    mw.tools.removeClass(el, 'active');
                    el.querySelector('.is_active_n').checked = true;
                }
                else {
                    mw.tools.addClass(el, 'active');
                    el.querySelector('.is_active_y').checked = true;
                }
            });
        }
    });
    mw.$(".wysiwyg-convertible-toggler").click(function () {
        var el = mw.$(this), next = el.next();
        mw.$(".wysiwyg-convertible").not(next).removeClass("active");
        mw.$(".wysiwyg-convertible-toggler").not(el).removeClass("active");
        next.toggleClass("active");
        el.toggleClass("active");
        if (el.hasClass("active")) {
            if (typeof mw.liveEditWYSIWYG === 'object') {
                mw.liveedit.toolbar.editor.fixConvertible(next);
            }
        }
    });
    mw.$(".mw-dropdown-search").keyup(function (e) {
        if (e.keyCode == '27') {
            mw.$(this.parentNode.parentNode).hide();
        }
        if (e.keyCode != '13' && e.keyCode != '27' && e.keyCode != '32') {
            var el = mw.$(this);
            el.addClass('mw-dropdown-searchSearching');
            mw.tools.ajaxSearch({keyword: this.value, limit: 20}, function () {
                var html = "<ul>", l = this.length, i = 0;
                for (; i < l; i++) {
                    var a = this[i];
                    html += '<li class="' + a.content_type + ' ' + a.subtype + '"><a href="' + a.url + '" title="' + a.title + '">' + a.title + '</a></li>';
                }
                html += '</ul>';
                el.parent().next("ul").replaceWith(html);
                el.removeClass('mw-dropdown-searchSearching');
            });
        }
    });
    var _mwoldww = mw.$(window).width();
    mw.$(window).resize(function () {
        if ($(window).width() > _mwoldww) {
            mw.trigger("increaseWidth");
        }
        else if ($(window).width() < _mwoldww) {
            mw.trigger("decreaseWidth");
        }
        $.noop();
        _mwoldww = mw.$(window).width();
    });
    mw.$(mwd.body).bind("keydown", function (e) {
        var isgal = mwd.querySelector('.mw_modal_gallery') !== null;
        if (isgal) {
            if (e.keyCode === 27) {  /* escape */
                mw.tools.modal.remove(mw.$(".mw_modal_gallery"))
                mw.tools.cancelFullscreen()
            }
            else if (e.keyCode === 37) { /* left */
                mw.tools.gallery.prev(mw.$(".mw_modal_gallery")[0].modal)
            }
            else if (e.keyCode === 39) { /* right */
                mw.tools.gallery.next(mw.$(".mw_modal_gallery")[0].modal)
            }
            else if (e.keyCode === 122) {/* F11 */
                mw.event.cancel(e, true);
                mw.tools.toggleFullscreen(mw.$(".mw_modal_gallery")[0]);
                return false;
            }
        }
        else {
            if (e.keyCode === 27) {
                var modal = mw.$(".mw_modal:last")[0];
                if (modal) modal.modal.remove();
            }
        }
    });

    mw.$(".mw-image-holder").each(function () {
        if ($(".mw-image-holder-overlay", this).length === 0) {
            mw.$('img', this).eq(0).after('<span class="mw-image-holder-overlay"></span>');
        }
    });

    mw.$(".mw-ui-dropdown").on('touchstart mousedown', function(){
        mw.$(this).toggleClass('active')
    });
    mw.$(document.body).on('touchend', function(e){
        if(!mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['mw-ui-dropdown'])){
            mw.$(".mw-ui-dropdown.active").removeClass('active')
        }
    });
    mw.$(document.body).on('click', 'a', function(e){
        if(location.hash.indexOf('#mw@') !== -1 && (e.target.href || '').indexOf('#mw@') !== -1){
            if(location.href === e.target.href){
                var el = mw.$('#' + e.target.href.split('mw@')[1])[0];
                if(el){
                    mw.tools.scrollTo(el)
                }
            }
        }
    })


});

mw.image = {
    isResizing: false,
    currentResizing: null,
    resize: {
        create_resizer: function () {
            if (mw.image_resizer == undefined) {
                var resizer = document.createElement('div');
                resizer.className = 'mw-defaults mw_image_resizer';
                resizer.innerHTML = '<div id="image-edit-nav"><span onclick="mw.wysiwyg.media(\'#editimage\');" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert mw-ui-btn-icon image_change tip" data-tip="' + mw.msg.change + '"><span class="mw-icon-image-frame"></span></span><span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert mw-ui-btn-icon tip image_change" id="image-settings-button" data-tip="' + mw.msg.edit + '" onclick="mw.image.settings();"><span class="mw-icon-edit"></span></span></div>';
                document.body.appendChild(resizer);
                mw.image_resizer = resizer;
                mw.image_resizer_time = null;
                mw.image_resizer._show = function () {
                    clearTimeout(mw.image_resizer_time)
                    mw.$(mw.image_resizer).addClass('active')
                };
                mw.image_resizer._hide = function () {
                    mw.image_resizer_time = setTimeout(function () {
                        mw.$(mw.image_resizer).removeClass('active')
                    }, 3000)
                };

                mw.$(resizer).on("click", function (e) {
                    if (mw.image.currentResizing[0].nodeName === 'IMG') {
                        mw.wysiwyg.select_element(mw.image.currentResizing[0])
                    }
                });
                mw.$(resizer).on("dblclick", function (e) {
                    mw.wysiwyg.media('#editimage');
                });
            }
        },
        prepare: function () {
            mw.image.resize.create_resizer();
            mw.$(mw.image_resizer).resizable({
                handles: "all",
                minWidth: 60,
                minHeight: 60,
                start: function () {
                    mw.image.isResizing = true;
                    mw.$(mw.image_resizer).resizable("option", "maxWidth", mw.image.currentResizing.parent().width());
                    mw.$(mw.tools.firstParentWithClass(mw.image.currentResizing[0], 'edit')).addClass("changed");
                },
                stop: function () {
                    mw.image.isResizing = false;
                    mw.drag.fix_placeholders();
                },
                resize: function () {
                    var offset = mw.image.currentResizing.offset();
                    mw.$(this).css(offset);
                },
                aspectRatio: 16 / 9
            });
            mw.image_resizer.mwImageResizerComponent = true;
            var all = mw.image_resizer.querySelectorAll('*'), l = all.length, i = 0;
            for (; i < l; i++) all[i].mwImageResizerComponent = true
        },
        resizerSet: function (el, selectImage) {
            var selectImage = typeof selectImage === 'undefined' ? true : selectImage;
            /*  var order = mw.tools.parentsOrder(el, ['edit', 'module']);
             if(!(order.module > -1 && order.edit > order.module) && order.edit>-1){   */


            mw.$('.ui-resizable-handle', mw.image_resizer)[el.nodeName == 'IMG' ? 'show' : 'hide']()

            var el = mw.$(el);
            var offset = el.offset();
            var parent = el.parent();
            var parentOffset = parent.offset();
            if(parent[0].nodeName !== 'A'){
                offset.top = offset.top < parentOffset.top ? parentOffset.top : offset.top;
                offset.left = offset.left < parentOffset.left ? parentOffset.left : offset.left;
            }
            var r = mw.$(mw.image_resizer);
            var width = el.outerWidth();
            var height = el.outerHeight();
            r.css({
                left: offset.left,
                top: offset.top,
                width: width,
                height: mw.tools.hasParentsWithClass(el[0], 'mw-image-holder') ? 1 : height
            });
            r.addClass("active");
            mw.$(mw.image_resizer).resizable("option", "alsoResize", el);
            mw.$(mw.image_resizer).resizable("option", "aspectRatio", width / height);
            mw.image.currentResizing = el;
            if (!el[0].contentEditable) {
                mw.wysiwyg.contentEditable(el[0], true);
            }

            if (selectImage) {
                if (el[0].parentNode.tagName !== 'A') {
                    mw.wysiwyg.select_element(el[0]);
                }
                else {
                    mw.wysiwyg.select_element(el[0].parentNode);
                }
            }
            if (mwd.getElementById('image-settings-button') !== null) {
                if (!!el[0].src && el[0].src.contains('userfiles/media/pixum/')) {
                    mwd.getElementById('image-settings-button').style.display = 'none';
                }
                else {
                    mwd.getElementById('image-settings-button').style.display = '';
                }
            }
            /* } */
        },
        init: function (selector) {
            mw.image_resizer == undefined ? mw.image.resize.prepare() : '';

            mw.on("ImageClick", function (e, el) {
                if (!mw.image.isResizing && !mw.isDrag && !mw.settings.resize_started && el.tagName === 'IMG') {
                    mw.image.resize.resizerSet(el);
                }
            })
        }
    },
    linkIt: function (img_object) {
        var img_object = img_object || mwd.querySelector("img.element-current");
        if (img_object.parentNode.tagName === 'A') {
            mw.$(img_object.parentNode).replaceWith(img_object);
        }
        else {
            mw.tools.modal.frame({
                url: "rte_link_editor#image_link",
                title: "Add/Edit Link",
                name: "mw_rte_link",
                width: 600,
                height: 535
            });
        }
    },
    _isrotating: false,
    rotate: function (img_object, angle) {
        if (!mw.image.Rotator) {
            mw.image.Rotator = mwd.createElement('canvas');
            mw.image.Rotator.style.top = '-9999px';
            mw.image.Rotator.style.left = '-9999px';
            mw.image.Rotator.style.position = 'absolute';
            mw.image.RotatorContext = mw.image.Rotator.getContext('2d');
            document.body.appendChild(mw.image.Rotator);
        }
        if (!mw.image._isrotating) {
            mw.image._isrotating = true;
            img_object = img_object || mwd.querySelector("img.element-current");
            if (img_object === null) {
                return false;
            }
            mw.image.preload(img_object.src, function () {
                if (!img_object.src.contains("base64")) {
                    var currDomain = mw.url.getDomain(window.location.href);
                    var srcDomain = mw.url.getDomain(img_object.src);
                    if (currDomain !== srcDomain) {
                        mw.tools.alert("This action is allowed for images on the same domain.");
                        return false;
                    }
                }
                var angle = angle || 90;
                var image = mw.$(this);
                var w = this.naturalWidth;
                var h = this.naturalHeight;
                var contextWidth = w;
                var contextHeight = h;
                var x = 0;
                var y = 0;
                switch (angle) {
                    case 90:
                        contextWidth = h;
                        contextHeight = w;
                        y = -h;
                        break;
                    case 180:
                        x = -w;
                        y = -h;
                        break;
                    case 270:
                        contextWidth = h;
                        contextHeight = w;
                        x = -w;
                        break;
                    default:
                        contextWidth = h;
                        contextHeight = w;
                        y = -h;
                }
                mw.image.Rotator.setAttribute('width', contextWidth);
                mw.image.Rotator.setAttribute('height', contextHeight);
                mw.image.RotatorContext.rotate(angle * Math.PI / 180);
                mw.image.RotatorContext.drawImage(img_object, x, y);
                var data = mw.image.Rotator.toDataURL("image/png");
                img_object.src = data;
                mw.image._isrotating = false;
                if (!!mw.wysiwyg) mw.wysiwyg.normalizeBase64Image(img_object);
            });
        }
    },
    grayscale: function (node) {
        var node = node || mwd.querySelector("img.element-current");
        if (node === null) {
            return false;
        }
        mw.image.preload(node.src, function () {
            var canvas = mwd.createElement('canvas');
            var ctx = canvas.getContext('2d');
            canvas.width = this.naturalWidth;
            canvas.height = this.naturalHeight;
            ctx.drawImage(node, 0, 0);
            var imgPixels = ctx.getImageData(0, 0, canvas.width, canvas.height);
            for (var y = 0; y < imgPixels.height; y++) {
                for (var x = 0; x < imgPixels.width; x++) {
                    var i = (y * 4) * imgPixels.width + x * 4; //Why is this multiplied by 4?
                    var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3;
                    imgPixels.data[i] = avg;
                    imgPixels.data[i + 1] = avg;
                    imgPixels.data[i + 2] = avg;
                }
            }
            ctx.putImageData(imgPixels, 0, 0, 0, 0, imgPixels.width, imgPixels.height);
            node.src = canvas.toDataURL();
            if (!!mw.wysiwyg) mw.wysiwyg.normalizeBase64Image(node);
        })
    },
    vr: [0, 0, 0, 1, 1, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 7, 7, 7, 7, 8, 8, 8, 9, 9, 9, 9, 10, 10, 10, 10, 11, 11, 12, 12, 12, 12, 13, 13, 13, 14, 14, 15, 15, 16, 16, 17, 17, 17, 18, 19, 19, 20, 21, 22, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 39, 40, 41, 42, 44, 45, 47, 48, 49, 52, 54, 55, 57, 59, 60, 62, 65, 67, 69, 70, 72, 74, 77, 79, 81, 83, 86, 88, 90, 92, 94, 97, 99, 101, 103, 107, 109, 111, 112, 116, 118, 120, 124, 126, 127, 129, 133, 135, 136, 140, 142, 143, 145, 149, 150, 152, 155, 157, 159, 162, 163, 165, 167, 170, 171, 173, 176, 177, 178, 180, 183, 184, 185, 188, 189, 190, 192, 194, 195, 196, 198, 200, 201, 202, 203, 204, 206, 207, 208, 209, 211, 212, 213, 214, 215, 216, 218, 219, 219, 220, 221, 222, 223, 224, 225, 226, 227, 227, 228, 229, 229, 230, 231, 232, 232, 233, 234, 234, 235, 236, 236, 237, 238, 238, 239, 239, 240, 241, 241, 242, 242, 243, 244, 244, 245, 245, 245, 246, 247, 247, 248, 248, 249, 249, 249, 250, 251, 251, 252, 252, 252, 253, 254, 254, 254, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255],
    vg: [0, 0, 1, 2, 2, 3, 5, 5, 6, 7, 8, 8, 10, 11, 11, 12, 13, 15, 15, 16, 17, 18, 18, 19, 21, 22, 22, 23, 24, 26, 26, 27, 28, 29, 31, 31, 32, 33, 34, 35, 35, 37, 38, 39, 40, 41, 43, 44, 44, 45, 46, 47, 48, 50, 51, 52, 53, 54, 56, 57, 58, 59, 60, 61, 63, 64, 65, 66, 67, 68, 69, 71, 72, 73, 74, 75, 76, 77, 79, 80, 81, 83, 84, 85, 86, 88, 89, 90, 92, 93, 94, 95, 96, 97, 100, 101, 102, 103, 105, 106, 107, 108, 109, 111, 113, 114, 115, 117, 118, 119, 120, 122, 123, 124, 126, 127, 128, 129, 131, 132, 133, 135, 136, 137, 138, 140, 141, 142, 144, 145, 146, 148, 149, 150, 151, 153, 154, 155, 157, 158, 159, 160, 162, 163, 164, 166, 167, 168, 169, 171, 172, 173, 174, 175, 176, 177, 178, 179, 181, 182, 183, 184, 186, 186, 187, 188, 189, 190, 192, 193, 194, 195, 195, 196, 197, 199, 200, 201, 202, 202, 203, 204, 205, 206, 207, 208, 208, 209, 210, 211, 212, 213, 214, 214, 215, 216, 217, 218, 219, 219, 220, 221, 222, 223, 223, 224, 225, 226, 226, 227, 228, 228, 229, 230, 231, 232, 232, 232, 233, 234, 235, 235, 236, 236, 237, 238, 238, 239, 239, 240, 240, 241, 242, 242, 242, 243, 244, 245, 245, 246, 246, 247, 247, 248, 249, 249, 249, 250, 251, 251, 252, 252, 252, 253, 254, 255],
    vb: [53, 53, 53, 54, 54, 54, 55, 55, 55, 56, 57, 57, 57, 58, 58, 58, 59, 59, 59, 60, 61, 61, 61, 62, 62, 63, 63, 63, 64, 65, 65, 65, 66, 66, 67, 67, 67, 68, 69, 69, 69, 70, 70, 71, 71, 72, 73, 73, 73, 74, 74, 75, 75, 76, 77, 77, 78, 78, 79, 79, 80, 81, 81, 82, 82, 83, 83, 84, 85, 85, 86, 86, 87, 87, 88, 89, 89, 90, 90, 91, 91, 93, 93, 94, 94, 95, 95, 96, 97, 98, 98, 99, 99, 100, 101, 102, 102, 103, 104, 105, 105, 106, 106, 107, 108, 109, 109, 110, 111, 111, 112, 113, 114, 114, 115, 116, 117, 117, 118, 119, 119, 121, 121, 122, 122, 123, 124, 125, 126, 126, 127, 128, 129, 129, 130, 131, 132, 132, 133, 134, 134, 135, 136, 137, 137, 138, 139, 140, 140, 141, 142, 142, 143, 144, 145, 145, 146, 146, 148, 148, 149, 149, 150, 151, 152, 152, 153, 153, 154, 155, 156, 156, 157, 157, 158, 159, 160, 160, 161, 161, 162, 162, 163, 164, 164, 165, 165, 166, 166, 167, 168, 168, 169, 169, 170, 170, 171, 172, 172, 173, 173, 174, 174, 175, 176, 176, 177, 177, 177, 178, 178, 179, 180, 180, 181, 181, 181, 182, 182, 183, 184, 184, 184, 185, 185, 186, 186, 186, 187, 188, 188, 188, 189, 189, 189, 190, 190, 191, 191, 192, 192, 193, 193, 193, 194, 194, 194, 195, 196, 196, 196, 197, 197, 197, 198, 199],
    vintage: function (node) {
        var node = node || mwd.querySelector("img.element-current");
        if (node === null) {
            return false;
        }
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        mw.image.preload(node.src, function (w, h) {
            canvas.width = w;
            canvas.height = h;
            ctx.drawImage(node, 0, 0);
            var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height), l = imageData.data.length, i = 0;
            for (; i < l; i += 4) {
                imageData.data[i] = mw.image.vr[imageData.data[i]];
                imageData.data[i + 1] = mw.image.vg[imageData.data[i + 1]];
                imageData.data[i + 2] = mw.image.vb[imageData.data[i + 2]];
                if (noise > 0) {
                    var noise = Math.round(noise - Math.random() * noise), j = 0;
                    for (; j < 3; j++) {
                        var iPN = noise + imageData.data[i + j];
                        imageData.data[i + j] = (iPN > 255) ? 255 : iPN;
                    }
                }
            }
            ctx.putImageData(imageData, 0, 0);
            node.src = canvas.toDataURL();
            if (!!mw.wysiwyg) mw.wysiwyg.normalizeBase64Image(node);
            mw.$(canvas).remove()
        });
    },
    _dragActivated: false,
    _dragcurrent: null,
    _dragparent: null,
    _dragcursorAt: {x: 0, y: 0},
    _dragTxt: function (e) {
        if (mw.image._dragcurrent !== null) {
            mw.image._dragcursorAt.x = e.pageX - mw.image._dragcurrent.offsetLeft;
            mw.image._dragcursorAt.y = e.pageY - mw.image._dragcurrent.offsetTop;
            var x = e.pageX - mw.image._dragparent.offsetLeft - mw.image._dragcurrent.startedX - mw.image._dragcursorAt.x;
            var y = e.pageY - mw.image._dragparent.offsetTop - mw.image._dragcurrent.startedY - mw.image._dragcursorAt.y;
            mw.image._dragcurrent.style.top = y + 'px';
            mw.image._dragcurrent.style.left = x + 'px';
        }
    },
    preloadForAll: function (array, eachcall, callback) {
        var size = array.length, i = 0, count = 0;
        for (; i < size; i++) {
            mw.image.preload(array[i], function (imgWidth, imgHeight) {
                count++;
                eachcall.call(this, imgWidth, imgHeight)
                if (count === size) {
                    if (!!callback) callback.call()
                }
            })
        }
    },
    preloadAll: function (array, callback) {
        var size = array.length, i = 0, count = 0;
        for (; i < size; i++) {
            mw.image.preload(array[i], function () {
                count++;
                if (count === size) {
                    callback.call()
                }
            })
        }
    },
    preload: function (url, callback) {
        var img;
        if (typeof window.chrome === 'object') {
            var img = new Image();
        }
        else {
            img = mwd.createElement('img')
        }
        img.className = 'semi_hidden';
        img.src = url;
        img.onload = function () {
            setTimeout(function () {
                if (typeof callback === 'function') {
                    callback.call(img, img.naturalWidth, img.naturalHeight);
                }
                mw.$(img).remove();
            }, 33);
        }
        img.onerror = function () {
            setTimeout(function () {
                if (typeof callback === 'function') {
                    callback.call(img, 0, 0, 'error');
                }
            }, 33);
        }
        mwd.body.appendChild(img);
    },
    description: {
        add: function (text) {
            var img = document.querySelector("img.element-current");
            img.title = text;
        },
        get: function () {
            return document.querySelector("img.element-current").title;
        },
        init: function (id) {
            var area = mw.$(id);
            area.hover(function () {
                area.addClass("desc_area_hover");
            }, function () {
                area.removeClass("desc_area_hover");
            });
            var curr = mw.image.description.get();
            if (!area.hasClass("inited")) {
                area.addClass("inited");
                area.bind("keyup change paste", function () {
                    var val = mw.$(this).val();
                    mw.image.description.add(val);
                });
            }
            area.val(curr);
            area.show();
        }
    },
    settings: function () {
        //var modal = mw.tools.modal.frame({
        var modal = mw.dialogIframe({
            url: 'imageeditor',
            template: "mw_modal_basic",
            overlay: true,
            width: '600',
            height: "auto",
            autoHeight: true,
            name: 'mw-image-settings-modal'
        });
    }
};


/* Exposing to mw  */
mw.gallery = function (arr, start, modal) {

    return mw.tools.gallery.init(arr, start, modal)

};
mw.tooltip = mw.tools.tip;
mw.tip = function (o) {
    var tip = mw.tooltip(o);
    var obj = {
        tip: tip,
        element: o.element,
        hide: function () {
            mw.$(tip).hide()
        },
        show: function () {
            mw.$(tip).show()
        },
        remove: function () {
            mw.$(tip).remove()
        },
        position: function (position, element) {
            var element = element || o.element;
            var position = position || 'top-center';
            mw.tools.tooltip.setPosition(tip, element, position);
        }
    }
    return obj;
}
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
    var root = window.top.mw ? window.top.mw : mw;
    //var modal = root.tools.modal.frame({
    var modal = root.dialogIframe({
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
/***********************************************
 mw.modal({
        content:   Required: String or Node Element or jQuery Object
        width:     Optional: ex: 400 or "85%", default 600
        height:    Optional: ex: 400 or "85%", default 500
        draggable: Optional: Boolean, default true (Requires jQuery UI )
        overlay:   Optional: Boolean, default false
        title:     Optional: String for the title of the modal
        template:  Optional: String
        id:        Optional: String: set this to protect from multiple instances
      });
 The function returns Object
 ************************************************/
mw.modal = function (o) {
    // return new mw.Dialog(o);
    var modal = mw.tools.modal.init(o);
    if (!!modal && (typeof(modal.main) != "undefined")) {
        if (modal.main.constructor === $.fn.constructor) {
            modal.main = modal.main[0];
        }
    }
    else {
        var modal = undefined;
    }
    return modal;
};
mw.modalFrame = function (o) {
    var modal = mw.tools.modal.frame(o);
    if (!!modal && (typeof(modal.main) != "undefined")) {
        if (modal.main.constructor === $.fn.constructor) {
            modal.main = modal.main[0];
        }
    }
    else {
        var modal = undefined;
    }
    return modal;
}
mw.editor = mw.tools.richtextEditor;
mw._colorPickerDefaults = {
    skin: 'mw-tooltip-default',
    position: 'bottom-center',
    onchange: false
}
mw._colorPicker = function (options) {
    if (!mw.tools.colorPickerColors) {
        mw.tools.colorPickerColors = [];
        var w = window;
        if(self != top){
            w = top;
        }
        var colorpicker_els = w.mw.$("body *");
        if(typeof colorpicker_els != 'undefined' && colorpicker_els.length > 0){
            colorpicker_els.each(function () {
                var css = parent.getComputedStyle(this, null);
                if (css !== null) {
                    if (mw.tools.colorPickerColors.indexOf(css.color) === -1) {
                        mw.tools.colorPickerColors.push(mw.color.rgbToHex(css.color))
                    }
                    if (mw.tools.colorPickerColors.indexOf(css.backgroundColor) === -1) {
                        mw.tools.colorPickerColors.push(mw.color.rgbToHex(css.backgroundColor))
                    }
                }
            });
        }

    }
    var proto = this;
    if (!options) {
        return false;
    }
    var settings = $.extend({}, mw._colorPickerDefaults, options);
    if (settings.element === undefined || settings.element === null) {
        return false;
    }

    var $el = mw.$(settings.element);
    if ($el[0] === undefined) {
        return false;
    }
    this.element = $el[0];
    if ($el[0].mwToolTipBinded !== undefined) {
        return false;
    }
    if (!settings.method) {
        if (this.element.nodeName == 'DIV') {
            settings.method = 'inline';
        }
    }
    this.settings = settings;
    $el[0].mwToolTipBinded = true;

    var sett = {
        showAlpha: true,
        showHSL: false,
        showRGB: false,
        showHEX: true,
        palette: mw.tools.colorPickerColors
    };

    if(settings.value) {
        sett.color = settings.value
    }
    if(typeof settings.showRGB !== 'undefined') {
        sett.showRGB = settings.showRGB
    }
    if(typeof settings.showHEX !== 'undefined') {
        sett.showHEX = settings.showHEX
    }

    if(typeof settings.showHSL !== 'undefined') {
        sett.showHSL = settings.showHSL
    }

    var frame;
    if (settings.method == 'inline') {

        sett.attachTo = $el[0];

        frame = AColorPicker.createPicker(sett);
        frame.onchange = function (data) {

            if (proto.settings.onchange) {
                proto.settings.onchange(data.color);
            }

            if ($el[0].nodeName == 'INPUT') {
                var val = val == 'transparent' ? val : '#' + val;
                $el.val(val);
            }
        }

    }
    else {

        var tip = mw.tooltip(settings), $tip = mw.$(tip).hide();
        this.tip = tip;

        mw.$('.mw-tooltip-content', tip).empty();
        sett.attachTo = mw.$('.mw-tooltip-content', tip)[0]

        frame = AColorPicker.createPicker(sett);

        frame.onchange = function (data) {

            if (proto.settings.onchange) {
                proto.settings.onchange(data.color);
            }

            if ($el[0].nodeName === 'INPUT') {
                $el.val(data.color);
            }
        };

        if ($el[0].nodeName === 'INPUT') {
            $el.on('focus', function (e) {
                if(this.value){
                    frame.color = this.value;
                }
                mw.$(tip).show();

                mw.tools.tooltip.setPosition(tip, $el[0], settings.position)
            });
        }
        else {
            $el.on('click', function (e) {
                mw.$(tip).toggle();
                mw.tools.tooltip.setPosition(tip, $el[0], settings.position)
            });
        }
        var documents = [document];
        if (self !== top){
            documents.push(top.document);
        }
        mw.$(documents).on('click', function (e) {
            if (!mw.tools.hasParentsWithClass(e.target, 'mw-tooltip') && e.target !== $el[0]) {
                mw.$(tip).hide();
            }
        });
        if ($el[0].nodeName === 'INPUT') {
            $el.bind('blur', function () {
                //$(tip).hide();
            });
        }
    }
    if (this.tip) {
        this.show = function () {
            mw.$(this.tip).show();
            mw.tools.tooltip.setPosition(this.tip, this.settings.element, this.settings.position)
        };
        this.hide = function () {
            mw.$(this.tip).hide()
        };
        this.toggle = function () {
            var tip = mw.$(this.tip);
            if (tip.is(':visible')) {
                this.hide()
            }
            else {
                $el.focus();
                this.show()
            }
        }
    }

};
mw.colorPicker = mw.colourPicker = function (o) {
    return new mw._colorPicker(o);
};
mw.accordion = function (el, callback) {
    return mw.tools.accordion(mw.$(el)[0], callback);
};
$.fn.timeoutHover = function (ce, cl, time1, time2) {
    var time1 = time1 || 350;
    var time2 = time2 || time1;
    return this.each(function () {
        var el = this;
        el.timeoutOver = null;
        el.timeoutLeave = null;
        el.originalOver = false;
        mw.$(el).hover(function () {
            el.originalOver = true;
            clearTimeout(el.timeoutOver);
            clearTimeout(el.timeoutLeave);
            el.timeoutOver = setTimeout(function () {
                if (typeof ce === 'function') {
                    ce.call(el);
                }
            }, time1);
        }, function () {
            el.originalOver = false;
            clearTimeout(el.timeoutOver);
            el.timeoutLeave = setTimeout(function () {
                if (typeof cl === 'function') {
                    cl.call(el);
                }
            }, time2);
        });
    });
}
$(mww).bind('load', function () {
    mw.dropdown();
});
$(mwd).ready(function () {
    mw.tools.constructions();
    mw.dropdown();
    mw.$(mwd.body).ajaxStop(function () {
        setTimeout(function () {
            mw.dropdown();
        }, 1222);
    });
});
mw.responsive = {
    table: function (selector, options) {
        options = options || {};
        options.breakPoint = options.breakPoint || 768;
        options.breakPoints = options.breakPoints || false;
        mw.$(selector).each(function () {
            var css = mwd.createElement('style');
            var cls = 'responsive-table-' + mw.random();
            var sel = function (c) {
                var final = '', arr = c.split(',');
                arr.forEach(function (c, i) {
                    arr[i] = '.' + cls + ' ' + c.trim()
                })
                return arr.join(',');
            };
            mw.tools.addClass(this, cls);
            var el = mw.$(this);
            if (!options.breakPoints) {
                css.innerHTML = '@media (max-width:' + (options.breakPoint) + 'px) { '
                    + '.' + cls + '{ display: block; width:100%}'
                    + sel('tbody tr') + '{ margin-bottom: 20px;display: block; clear:both;overflow:hidden; }'
                    + sel('thead, tfoot') + '{ display: none; }'
                    + sel('.th-in-td, tbody,tr,td') + '{ display: block; width:100% }'
                    + sel('tbody td') + '{ text-align: left;display: block;width: 100%; }'
                    + '}';
            }
            else {
                var html = '';
                var arr = [];
                $.each(options.breakPoints, function (key, val) {
                    arr.push(key);
                })
                arr.sort(function (a, b) {
                    return b - a
                });
                $.each(arr, function (key) {
                    var val = options.breakPoints[this];
                    html += '\n @media (max-width:' + (this) + 'px) { '
                        + '.' + cls + '{ display: block; width:100%}'
                        + sel('tbody tr') + '{ margin-bottom: 20px;display: block;clear:both; }'
                        + sel('thead, tfoot') + '{ display: none; }'
                        + sel('.th-in-td, tbody,tr') + '{ display: block; width:100% }'
                        + sel('tbody td') + '{ display: block;width:' + (100 / val) + '%;float:left; }'
                        + '}';
                });
                mw.$('tr', el).each(function () {
                    var max = 0;
                    mw.$('td', this).height('auto').each(function () {
                        var h = mw.$(this).outerHeight();
                        if (h > max) {
                            max = h
                        }
                    })
                        .height(max)
                });
                mw.$(window).on('resize orientationchange', function () {
                    mw.$('tr', el).each(function () {
                        var max = 0;
                        mw.$('td', this).height('auto').each(function () {
                            var h = mw.$(this).outerHeight();
                            if (h > max) {
                                max = h
                            }
                        })
                            .height(max)
                    });
                })
                css.innerHTML = html
            }
            el.prepend(css);
            if (!el.hasClass('mw-mobile-table')) {
                el.addClass('mw-mobile-table');
                var th = mw.$('th', this)
                mw.$('tr', this).each(function () {
                    mw.$('td', this).each(function (i) {
                        mw.$(this).prepend('<span class="th-in-td">' + th.eq(i).html() + '</span>');
                    });
                })
            }
        });
    }
}


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

mw.getExtradataFormData = function (data, call) {
     if (data.form_data_module) {
        mw.loadModuleData(data.form_data_module, function (moduledata) {
            call.call(undefined, moduledata);
        },null,data.form_data_module_params);
    }
    else {
        call.call(undefined, data.form_data_required);
    }
}
mw.extradataForm = function (options, data) {
    if (options._success) {
        options.success = options._success;
        delete options._success;
    }
    mw.getExtradataFormData(data, function (extra_html) {
        var form = document.createElement('form');
        mw.$(form).append(extra_html);

        if(data.form_data_required){
            mw.$(form).append('<hr><button type="submit" class="mw-ui-btn pull-right mw-ui-btn-invert">' + mw.lang('Submit') + '</button>');
        }

        form.action = options.url;
        form.method = options.type;
        form.__modal = mw.dialog({
            content: form,
            title: data.error
        });
        mw.$('script', form).each(function() {
            eval($(this).text());
        });

        if(data.form_data_required) {
            mw.$(form).on('submit', function (e) {
                e.preventDefault();
                var exdata = mw.serializeFields(this);

                if(typeof options.data === 'string'){
                    var params = {};
                    options.data.split('&').forEach(function(a){
                        var c = a.split('=');
                        params[c[0]] = decodeURIComponent(c[1]);
                    });
                    options.data = params;
                }
                for (var i in exdata) {
                    options.data[i] = exdata[i];
                }



                mw.ajax(options);
                form.__modal.remove();
            })
        }
    });
};

mw.uiAccordion = function (options) {
    if (!options) return;
    options.element = options.element || '.mw-accordion';

    var scope = this;



    this.getContents = function () {
        this.contents = this.root.children(this.options.contentSelector);
        if (!this.contents.length) {
            this.contents = mw.$();
            this.root.children(this.options.itemSelector).each(function () {
                var title = mw.$(this).children(scope.options.contentSelector)[0];
                if (title) {
                    scope.contents.push(title)
                }
            });
        }
    }
    this.getTitles = function () {
        this.titles = this.root.children(this.options.titleSelector);
        if (!this.titles.length) {
            this.titles = mw.$();
            this.root.children(this.options.itemSelector).each(function () {
                var title = mw.$(this).children(scope.options.titleSelector)[0];
                if (title) {
                    scope.titles.push(title)
                }
            });
        }
    }

    this.prepare = function (options) {
        var defaults = {
            multiple: false,
            itemSelector: ".mw-accordion-item,mw-accordion-item",
            titleSelector: ".mw-accordion-title,mw-accordion-title",
            contentSelector: ".mw-accordion-content,mw-accordion-content",
            openFirst: true,
            toggle: true
        };
        this.options = $.extend({}, defaults, options);

        this.root = mw.$(this.options.element).not('.mw-accordion-ready').eq(0);
        if (!this.root.length) return;
        this.root.addClass('mw-accordion-ready');
        this.root[0].uiAccordion = this;
        this.getTitles();
        this.getContents();

    };

    this.getItem = function (q) {
        var item;
        if (typeof q === 'number') {
            item = this.contents.eq(q)
        }
        else {
            item = mw.$(q);
        }
        return item;
    };

    this.set = function (index) {
        var item = this.getItem(index);
        if (!this.options.multiple) {
            this.contents.not(item)
                .slideUp()
                .removeClass('active')
                .prev()
                .removeClass('active')
                .parents('.mw-accordion-item').eq(0)
                .removeClass('active');
        }
        item.stop()
            .slideDown()
            .addClass('active')
            .prev()
            .addClass('active')
            .parents('.mw-accordion-item').eq(0)
            .addClass('active');
        mw.$(this).trigger('accordionSet', [item]);
    };

    this.unset = function (index) {
        if (typeof index === 'undefined') return;
        var item = this.getItem(index);
        item.stop()
            .slideUp()
            .removeClass('active')
            .prev()
            .removeClass('active')
            .parents('.mw-accordion-item').eq(0)
            .removeClass('active');
        ;
        mw.$(this).trigger('accordionUnset', [item]);
    }

    this.toggle = function (index) {
        var item = this.getItem(index);
        if (item.hasClass('active')) {
            if (this.options.toggle) {
                this.unset(item)
            }
        }
        else {
            this.set(item)
        }
    }

    this.init = function (options) {
        this.prepare(options);
        if(typeof(this.contents) !== 'undefined'){
            this.contents.hide()
        }

        if (this.options.openFirst) {
            if(typeof(this.contents) !== 'undefined'){
                this.contents.eq(0).show().addClass('active')
            }
            if(typeof(this.titles) !== 'undefined'){
                this.titles.eq(0).addClass('active').parent('.mw-accordion-item').addClass('active');
            }
        }
        if(typeof(this.titles) !== 'undefined') {
            this.titles.on('click', function () {
                scope.toggle(scope.titles.index(this));
            });
        }
    }

    this.init(options);

};


mw.tabAccordion = function (options, accordion) {
    if (!options) return;
    var scope = this;
    this.options = options;

    this.options.breakPoint = this.options.breakPoint || 800;
    this.options.activeClass = this.options.activeClass || 'active-info';


    this.buildAccordion = function () {
        this.accordion = accordion || new mw.uiAccordion(this.options);
    }

    this.breakPoint = function () {
        if (matchMedia("(max-width: " + this.options.breakPoint + "px)").matches) {
            mw.$(this.nav).hide();
            mw.$(this.accordion.titles).show();
        }
        else {
            mw.$(this.nav).show();
            mw.$(this.accordion.titles).hide();
        }
    }

    this.createTabButton = function (content, index) {
        this.buttons = this.buttons || mw.$();
        var btn = document.createElement('span');
        this.buttons.push(btn)
        var size = (this.options.tabsSize ? ' mw-ui-btn-' + this.options.tabsSize : '');
        var color = (this.options.tabsColor ? ' mw-ui-btn-' + this.options.tabsColor : '');
        var active = (index === 0 ? (' ' + this.options.activeClass) : '');
        btn.className = 'mw-ui-btn' + size + color + active;
        btn.innerHTML = content;
        btn.onclick = function () {
            scope.buttons.removeClass(scope.options.activeClass).eq(index).addClass(scope.options.activeClass);
            scope.accordion.set(index);
        }
        return btn;
    }

    this.createTabs = function () {
        this.nav = document.createElement('div');
        this.nav.className = 'mw-ui-btn-nav mw-ui-btn-nav-tabs';
        mw.$(this.accordion.titles)
            .each(function (i) {
                scope.nav.appendChild(scope.createTabButton($(this).html(), i))
            })
            .hide();
        mw.$(this.accordion.root).before(this.nav)
    }

    this.init = function () {
        this.buildAccordion();
        this.createTabs();
        this.breakPoint();
        mw.$(window).on('load resize orientationchange', function () {
            scope.breakPoint();
        });
    };

    this.init();
};



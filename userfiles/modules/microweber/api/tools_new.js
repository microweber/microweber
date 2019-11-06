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
        el1 = mw.$(el1);
        el2 = mw.$(el2);
        var o1 = el1.offset();
        var o2 = el2.offset();
        o1.width = el1.width();
        o1.height = el1.height();
        o2.width = el2.width();
        o2.height = el2.height();
        return (o1.left < o2.left + o2.width  && o1.left + o1.width  > o2.left &&  o1.top < o2.top + o2.height && o1.top + o1.height > o2.top);
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
                mw.$(this).css('backgroundImage', 'url(' + img.src + ')');
            }
        });
    },
    isRtl: function (el) {
        //todo
        el = el || document.documentElement;
        return document.documentElement.dir === 'rtl';
    },
    isEditable: function (item) {
        var el = item;
        if (!!item.type && !!item.target) {
            el = item.target;
        }
        return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(el, ['edit', 'module']);
    },
    eachIframe: function (callback, root, ignore) {
        root = root || document;
        var scope = this;
        ignore = ignore || [];
        var all = root.querySelectorAll('iframe'), i = 0;
        for (; i < all.length; i++) {
            if (mw.tools.canAccessIFrame(all[i]) && ignore.indexOf(all[i]) === -1) {
                callback.call(all[i].contentWindow, all[i].contentWindow);
                scope.eachIframe(callback, all[i].contentWindow.document);
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
        });
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
                params = $.param(params);
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
                    holder = mw.tools.externalInstrument.holder();
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

    },
    isField: function (target) {
        var t = target.nodeName.toLowerCase();
        var fields = /^(input|textarea|select)$/i;
        return fields.test(t);
    },

    toggleCheckbox: function (node) {
        if (node === null || node === undefined) return false;
        node.checked = !node.checked;
        return node.checked;
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
        who = mw.$(who);
        who.toggle();
        who.toggleClass('toggle-active');
        mw.$(toggler).toggleClass('toggler-active');
        mw.is.func(callback) ? callback.call(who) : '';
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
        el = mw.$(el)[0];
        selector = selector || el.tagName.toLowerCase();
        parent = parent || el.parentNode;
        var all;
        if (parent.constructor === [].constructor) {
            all = parent;
        }
        else {
            all = mw.$(selector, parent)
        }
        var i = 0, l = all.length;
        for (; i < l; i++) {
            if (el === all[i]) return i;
        }
    },

    highlight: function (el, color, speed1, speed2) {
        if (!el) return false;
        mw.$(el).stop();
        color = color || '#48AD79';
        speed1 = speed1 || 777;
        speed2 = speed2 || 777;
        var curr = window.getComputedStyle(el, null).backgroundColor;
        if (curr === 'transparent') {
            curr = '#ffffff';
        }
        mw.$(el).animate({backgroundColor: color}, speed1, function () {
            mw.$(el).animate({backgroundColor: curr}, speed2, function () {
                mw.$(el).css('backgroundColor', '');
            });
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
                if (typeof callback === 'function') {
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
        return false;
    },
    getAttrs: function (el) {
        var attrs = el.attributes;
        var obj = {};
        for (var x in attrs) {
            if (attrs[x].nodeName) {
                obj[attrs[x].nodeName] = attrs[x].nodeValue;
            }
        }
        return obj;
    },
    copyAttributes: function (from, to, except) {
        except = except || [];
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
            for ( x in obj ) {
                len++;
            }
        }
        return len;
    },
    scaleTo: function (selector, w, h) {
        w = w || 800;
        h = h || 600;
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
        };
    },
    refresh_image: function (node) {
        node.src = mw.url.set_param('refresh_image', mw.random(), node.src);
        return node;
    },
    refresh: function (a) {
        if (a === null || typeof a === 'undefined') {
            return false;
        }
        if (a.src) {
            a.src = mw.url.set_param('mwrefresh', mw.random(), a.src);
        }
        else if (a.href) {
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
        text = text || mw.msg.loading + '...';
        global = global || false;
        var _el = mw.$(el);
        if (!_el.length) {
            return false;
        }
        if (!_el.hasClass("disabled")) {
            _el.addClass('disabled');
            if (_el[0].tagName !== 'INPUT') {
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
        if (!_el.length) {
            return false;
        }
        var text = _el.dataset("text");
        _el.removeClass("disabled");
        if (_el[0].tagName !== 'INPUT') {
            _el.html(text);
        }
        else {
            _el.val(text);
        }
        mw.$(mwd.body).removeClass("loading");
        return el;
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
    cloneObject: function (r) {
        var a = {}, i;
        for (i in r) {
            if (r.hasOwnProperty(i)) {
                a[i] = r[i];
            }
        }
        return a;
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
    }
};
mw.tools.matches('init');
Alert = mw.tools.alert;

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
};

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
};


mw.postMsg = function (w, obj) {
    w.postMessage(JSON.stringify(obj), window.location.href);
};

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



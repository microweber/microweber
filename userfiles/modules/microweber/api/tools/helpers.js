;(function(expose){
    var helpers = {
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
            else if(mw.top().win.frameElement && mw.top().win.frameElement.contentWindow === window) {
                mw.tools.iframeAutoHeight(mw.top().win.frameElement, 'now');
            } else if(window.top !== window) {
                mw.top().$('iframe').each(function(){
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
            var tempInput = document.createElement("textarea");
            tempInput.style = "position: absolute; left: -1000px; top: -1000px";
            tempInput.value = value;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            mw.notification.success(mw.lang('Copied') + ': "' + value + '"');
        },
        cloneObject: function (object) {
            return jQuery.extend(true, {}, object);
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

            return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(el, ['edit', 'module'])
                && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(el, ['edit', 'noedit']);
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
                if(curr.parent && curr.parent.document) {
                    this.eachIframe(function (iframeWindow) {
                        callback.call(iframeWindow, iframeWindow);
                    }, curr.parent.document, [curr]);
                    curr = curr.parent;
                    callback.call(curr, curr);
                }
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
            ins = ins || document.getElementsByTagName('head')[0];
            var style = mw.$(c)[0];
            if (!style) {
                style = document.createElement('style');
                ins.appendChild(style);
            }
            style.innerHTML = css;
            return style;
        },
        cssNumber: function (val) {
            var units = ["px", "%", "in", "cm", "mm", "em", "ex", "pt", "pc"];
            if (typeof val === 'number') {
                return val + 'px';
            }
            else if (typeof val === 'string' && parseFloat(val).toString() === val) {
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
                root = document.querySelector(root);
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
            var is_hidden = getComputedStyle(container).display === 'none';
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
            });
        },
        multihover: function () {
            var l = arguments.length, i = 1;
            var type = arguments[0].type;
            var check = ( type === 'mouseover' || type === 'mouseenter');
            for ( ; i < l; i++ ) {
                check ? mw.$(arguments[i]).addClass('hovered') : mw.$(arguments[i]).removeClass('hovered');
            }
        },
        listSearch: function (val, list) {
            val = val.trim().toLowerCase();
            if(!val) {
                $('li', list).show();
                return;
            }
            this.search(val, 'li', function (found) {
                if(found) {
                    $(this).show();
                } else {
                    $(this).hide();
                }

            }, list);
        },
        search: function (string, selector, callback, root) {
            root = !!root ? $(root)[0] : mwd;
            if (!root) {
                return;
            }
            string = string.toLowerCase();
            var items;
            if (typeof selector === 'object') {
                items = selector;
            }
            else if (typeof selector === 'string') {
                items = root.querySelectorAll(selector);
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
                mw._html_info = document.createElement('div');
                mw._html_info.id = 'mw-html-info';
                document.body.appendChild(mw._html_info);
            }
            mw.$(mw._html_info).html(html);
            return mw._html_info;
        },
        image_info: function (a, callback) {
            var img = document.createElement('img');
            img.className = 'semi_hidden';
            img.src = a.src;
            document.body.appendChild(img);
            img.onload = function () {
                callback.call({width: mw.$(img).width(), height: mw.$(img).height()});
                mw.$(img).remove();
            };
        },
        refresh_image: function (node) {
            node.src = mw.url.set_param('refresh_image', mw.random(), node.src);
            return node;
        },
        refresh: function (a, onSuccess) {
            if (a === null || typeof a === 'undefined') {
                return false;
            }
            if (a.src) {
                a.src = mw.url.set_param('mwrefresh', mw.random(), a.src);
                if(onSuccess) {
                    jQuery.get(a.getAttribute('src'), function(e){
                        onSuccess.call(a, e)
                    })
                }

            }
            else if (a.href) {
                a.href = mw.url.set_param('mwrefresh', mw.random(), a.href);
                if(onSuccess) {
                    jQuery.get(a.getAttribute('href'), function(e){
                        onSuccess.call(a, e)
                    })
                }
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
            var doc = document.implementation.createHTMLDocument("");
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
                if (global) mw.$(document.body).addClass("loading");
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
            mw.$(document.body).removeClass("loading");
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
            if (!n1 || !n2) return false;
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
            var el = document.createElement(tag);
            mw.tools.copyAttributes(node, el);
            while (node.firstChild) {
                el.appendChild(node.firstChild);
            }
            mw.tools.copyEvents(node, el);
            mw.$(node).replaceWith(el);
            return el;
        },

        module_settings: function (a, view, liveedit) {
            var opts = {};
            if (typeof liveedit === 'undefined') {
                opts.liveedit = true;
            }
            if (!!view) {
                opts.view = view;
            }
            else {
                opts.view = 'admin';
            }
            return mw.live_edit.showSettings(a, opts);
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
            document.getElementsByTagName('head')[0].appendChild(l);
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
                if (!!document.documentElement.matches) mw.tools.matchesMethod = 'matches';
                else if (!!document.documentElement.matchesSelector) mw.tools.matchesMethod = 'matchesSelector';
                else if (!!document.documentElement.mozMatchesSelector) mw.tools.matchesMethod = 'mozMatchesSelector';
                else if (!!document.documentElement.webkitMatchesSelector) mw.tools.matchesMethod = 'webkitMatchesSelector';
                else if (!!document.documentElement.msMatchesSelector) mw.tools.matchesMethod = 'msMatchesSelector';
                else if (!!document.documentElement.oMatchesSelector) mw.tools.matchesMethod = 'oMatchesSelector';
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
                    var doc = document.implementation.createHTMLDocument("");
                    node = node.cloneNode(true);
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
    }

    Object.assign(expose, helpers);
    expose.matches('init');

})(mw.tools);

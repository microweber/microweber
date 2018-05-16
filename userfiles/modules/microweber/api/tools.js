mw.require("files.js");
mw.require("css_parser.js");
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
$.fn.dataset = function (dataset, val) {
    var el = this[0];
    if (el === undefined) return false;
    var _dataset = !dataset.contains('-') ? dataset : mw.tools.toCamelCase(dataset);
    if (!val) {
        var dataset = !!el.dataset ? el.dataset[_dataset] : $(el).attr("data-" + dataset);
        return dataset !== undefined ? dataset : "";
    }
    else {
        !!el.dataset ? el.dataset[_dataset] = val : $(el).attr("data-" + dataset, val);
        return $(el);
    }
}
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
}
mw.exec = function (str, a, b, c) {
    return str._exec(a, b, c);
}
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
            out += '%' + ( hex.length % 2 != 0 ? '0' : '' ) + hex;
        }
        return out;
    };
}
mw.tools = {
    constructions: function () {
        $(".mw-image-holder").each(function () {
            var img = this.querySelector('img');
            $(this).css('backgroundImage', 'url(' + img.src + ')')
        })
    },
    isRtl:function(el){
        //todo
        el = el || document.documentElement;
        return document.documentElement.dir == 'rtl'
    },
    isEditable: function (item) {
        var el = item;
        if (!!item.type && !!item.target) {
            el = item.target;
        }
        if (mw.tools.hasClass(el, 'edit')) return true;
        var hasParentsModule = mw.tools.hasParentsWithClass(el, 'module');
        var hasParentsEdit = mw.tools.hasParentsWithClass(el, 'edit');
        if (hasParentsModule && !hasParentsEdit) return false;
        if (!hasParentsModule && hasParentsEdit) return true;
        if (hasParentsModule && hasParentsEdit) {
            var order = mw.tools.parentsOrder(item, ['edit', 'module']);
            if (order.edit < order.module) {
                return true;
            }
            else {
                return false;
            }
        }
    },
    eachIframe:function (callback, root, ignore) {
        root = root || document, scope = this, ignore = ignore || [];
        var all = root.querySelectorAll('iframe'), i = 0;
        for( ; i < all.length ; i++){
            if(mw.tools.canAccessIFrame(all[i]) && ignore.indexOf(all[i]) === -1){
                callback.call(all[i].contentWindow, all[i].contentWindow);
                scope.eachIframe(callback, all[i].contentWindow.document)
            }
        }
    },
    eachWindow:function (callback, options) {
        options = options || {};
        var curr = window;
        callback.call(curr, curr);
        while( curr !== top ){
            this.eachIframe(function(iframeWindow){
                callback.call(iframeWindow, iframeWindow);
            }, curr.parent.document, [curr]);
            curr = curr.parent;
            callback.call(curr, curr);
        }
        this.eachIframe(function(iframeWindow){
            callback.call(iframeWindow, iframeWindow);
        })
        if(window.opener !== null && mw.tools.canAccessWindow(opener)){
            callback.call(window.opener, window.opener);
            this.eachIframe(function(iframeWindow){
                callback.call(iframeWindow, iframeWindow);
            }, window.opener.document);
        }
    },
    canAccessWindow:function(winObject) {
        var can = false;
        try {
            var doc = winObject.document;
            can = !!doc.body;
        } catch(err){
        }
        return can;
    },
    canAccessIFrame:function(iframe) {
        var can = false;
        try {
            var doc = iframe.contentDocument || iframe.contentWindow.document;
            can = !!doc.body;
        } catch(err){
        }
        return can;
    },
    createStyle: function (c, css, ins) {
        var ins = ins || mwd.getElementsByTagName('head')[0];
        var style = mw.$(c)[0];
        if (typeof style === 'undefined') {
            var style = mwd.createElement('style');
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
                    $(mwd.body).append(holder);
                }
                mw.$(holder).append(frame);
            }
            else {
                $(mw.tools.externalInstrument.register[name]).unbind('change');
            }
            $(mw.tools.externalInstrument.register[name]).bind('change', function () {
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
            if (typeof content === 'object') {
                var content = mw.$(content).html();
            }
            if (typeof id === 'undefined') {
                id = 'mw-tooltip-' + mw.random();
            }
            var tooltip = mwd.createElement('div');
            tooltip.className = 'mw-tooltip ' + position + ' ' + skin;
            tooltip.id = id;
            tooltip.innerHTML = '<div class="mw-tooltip-content">' + content + '</div><span class="mw-tooltip-arrow"></span>';
            mwd.body.appendChild(tooltip);
            return tooltip;
        },
        setPosition: function (tooltip, el, position) {
            var el = mw.$(el);
            if (el.length === 0) {
                return false;
            }
            tooltip.tooltipData.element = el[0];
            var w = el.outerWidth(),
                tipwidth = $(tooltip).outerWidth(),
                h = el.outerHeight(),
                tipheight = $(tooltip).outerHeight(),
                off = el.offset(),
                arrheight = mw.$('.mw-tooltip-arrow', tooltip).height();
            if (off.top == 0 && off.left == 0) {
                var off = $(el).parent().offset()
            }
            mw.tools.removeClass(tooltip, tooltip.tooltipData.position);
            mw.tools.addClass(tooltip, position);
            tooltip.tooltipData.position = position;


            if (position == 'bottom-left') {
                $(tooltip).css({
                    top: off.top + h + arrheight,
                    left: off.left
                });
            }
            else if (position == 'bottom-center') {
                $(tooltip).css({
                    top: off.top + h + arrheight,
                    left: off.left - tipwidth / 2 + w / 2
                });
            }
            else if (position == 'bottom-right') {
                $(tooltip).css({
                    top: off.top + h + arrheight,
                    left: off.left - tipwidth + w
                });
            }
            else if (position == 'top-right') {
                $(tooltip).css({
                    top: off.top - tipheight - arrheight,
                    left: off.left - tipwidth + w
                });
            }
            else if (position == 'top-left') {
                $(tooltip).css({
                    top: off.top - tipheight - arrheight,
                    left: off.left
                });
            }
            else if (position == 'top-center') {

                $(tooltip).css({
                    top: off.top - tipheight - arrheight,
                    left: off.left - tipwidth / 2 + w / 2
                });
            }
            else if (position == 'left-top') {
                $(tooltip).css({
                    top: off.top,
                    left: off.left - tipwidth - arrheight
                });
            }
            else if (position == 'left-bottom') {
                $(tooltip).css({
                    top: (off.top + h) - tipheight,
                    left: off.left - tipwidth - arrheight
                });
            }
            else if (position == 'left-center') {
                $(tooltip).css({
                    top: off.top - tipheight / 2 + h / 2,
                    left: off.left - tipwidth - arrheight
                });
            }
            else if (position == 'right-top') {
                $(tooltip).css({
                    top: off.top,
                    left: off.left + w + arrheight
                });
            }
            else if (position == 'right-bottom') {
                $(tooltip).css({
                    top: (off.top + h) - tipheight,
                    left: off.left + w + arrheight
                });
            }
            else if (position == 'right-center') {
                $(tooltip).css({
                    top: off.top - tipheight / 2 + h / 2,
                    left: off.left + w + arrheight
                });
            }
            if (parseFloat($(tooltip).css('top')) < 0) {
                $(tooltip).css('top', 0);
            }
        },
        fixPosition: function (tooltip) {
            /* mw_todo */
            var max = 5;
            var arr = mw.$('.mw-tooltip-arrow', tooltip);
            arr.css('left', '');
            var arr_left = parseFloat(arr.css('left'));
            var tt = $(tooltip);
            if (tt.length === 0) {
                return false;
            }
            var w = tt.width(),
                off = tt.offset(),
                ww = $(window).width();
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
            ;
            if (o.element.constructor === [].constructor && o.element.length === 0) return false;
            if (typeof o.position === 'undefined') {
                o.position = 'top-center';
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
                var cur_tip = $(tip)
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
                 $(self).bind('resize scroll', function (e) {
                 if (self.document.contains(tip)) {
                 self.mw.tools.tooltip.setPosition(tip, tip.tooltipData.element, o.position);
                 }
                 });*/
                if (o.group && typeof orig_options.close_on_click_outside !== 'undefined' && orig_options.close_on_click_outside) {
                    $(self).bind('click', function (e, target) {
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
    inlineModal: function (o) {
        /*
         **********************************************
         mw.tools.inlineModal({
         element: "#selector", Node or jQuery Object *: Required - The element in which the 'inlineModal' will be put.
         content: string, Node or jQuery Object *: content for the 'inlineModal'.
         template: string *: sets class for the 'inlineModal'. Default - ".mw-inline-modal-default"
         });
         ***********************************************
         */
        var tpl = o.template || 'mw-inline-modal-default';
        if (o.element === null || typeof o.element === 'undefined') {
            return false;
        }
        if (o.content === null || typeof o.content === 'undefined') {
            o.content = "";
        }
        var m = mwd.createElement('div'), c = mwd.createElement('div');
        m.contentEditable = false;
        m.className = 'mw-inline-modal ' + tpl;
        c.className = 'mw-inline-modal-container';
        c.innerHTML = '<span class="mw-inline-modal-container-close" onclick="$(mw.tools.firstParentWithClass(this, \'mw-inline-modal\')).remove();"></span>';
        m.innerHTML = '<div class="mw-inline-modal-overlay"></div>';
        var pos = $(o.element).css("position");
        if (pos != 'relative' && pos != 'absolute' && pos != 'fixed') {
            $(o.element).css("position", "relative");
        }
        if (typeof o.content === 'object') {
            o.content = $(o.content).clone(true);
            o.content.show();
        }
        $(c).append(o.content);
        m.appendChild(c);
        $(o.element).append(m);
        var h1 = $(o.element).outerHeight();
        var h2 = $(c).outerHeight();
        c.style.top = h1 / 2 - h2 / 2 + "px";
        return m;
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
        source: function (id, template) {
            var template = template || 'mw_modal_default';
            if (template == 'basic') {
                var template = 'mw_modal_basic';
            }
            if (template == 'default') {
                var template = 'mw_modal_default';
            }
            if (template == 'simple') {
                var template = 'mw_modal_simple';
            }
            var id = id || "modal_" + mw.random();
            var html = ''
                + '<div class=" mw_modal mw_modal_maximized ' + template + '" id="' + id + '">'
                + '<div class="mw_modal_toolbar">'
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
                onopen = o.onopen;
            if (typeof name === 'string' && mw.$("#" + name).length > 0) {
                return false;
            }
            var modal = mw.tools.modal.source(name, template);
            $(mwd.body).append(modal.html);
            var _modal = mwd.getElementById(modal.id);
            if (!_modal.remove) {
                _modal.remove = function () {
                    mw.tools.modal.remove(modal.id);
                }
            }
            var modal_object = $(_modal);
            mw.$('.mw_modal_container', _modal).append(html);
            mw.tools.modal.setDimmensions(_modal, width, height, false);
            mw.$(".mw-tooltip").hide();
            modal_object.show();
            mw.tools.modal.center(_modal);
            var draggable = typeof draggable !== 'undefined' ? draggable : true;
            if (typeof $.fn.draggable === 'function' && draggable) {
                modal_object.addClass("mw-modal-draggable")
                modal_object.draggable({
                    handle: '.mw_modal_toolbar',
                    containment: 'window',
                    iframeFix: false,
                    distance: 10,
                    containment: "window",
                    drag : function(e,ui){
                        if(ui.position.top < 0) ui.position.top = 0;
                    },
                    start: function () {
                        $(this).find(".iframe_fix").show();
                        if ($(".mw_modal").length > 1) {
                            var mw_m_max = parseFloat($(this).css("zIndex"));
                            mw.$(".mw_modal").not(this).each(function () {
                                var z = parseFloat($(this).css("zIndex"));
                                mw_m_max = z >= mw_m_max ? z + 1 : mw_m_max;
                            });
                            $(this).css("zIndex", mw_m_max);
                        }
                    },
                    stop: function (e, ui) {
                        $(this).find(".iframe_fix").hide();
                        var w = $(window).width();
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
            typeof title === 'string' ? $(modal_object).find(".mw_modal_title").html(title) : '';
            typeof name === 'string' ? $(modal_object).attr("name", name) : '';
            modal_return.onremove = typeof onremove === 'function' ? onremove : false;
            if (overlay == true) {
                var ol = mw.tools.modal.overlay(modal_object);
                if(o.overlayRemovesModal){
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
                $(modal_return.overlay).hide();
                $(modal_return).trigger('modal.hide');
                return this;
            }
            modal_return.show = function () {
                modal_object.show();
                $(modal_return.overlay).show();
                $(modal_return).trigger('modal.show');
                return modal_return;
            }
            modal_return.remove = function () {
                $(modal_return).trigger('modal.remove');
                if (typeof modal_return.onremove === 'function') {
                    modal_return.onremove.call(window, modal_return);
                }
                modal_object.remove();
                $(modal_return.overlay).remove();
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
                $(modal_return).trigger('modal.resize');
                return modal_return;
            }
            if(onopen){
                onopen.call(window, modal_return)
            }
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
            var el = mw.$(selector),
                child_cont = el.find(".mw_modal_container:first"),
                parent_cont = el.parents(".mw_modal_container:first");
            if (child_cont.length !== 0) {
                return child_cont.parent()[0].modal;
            }
            else if(parent_cont.length !== 0){
                return parent_cont.parent()[0].modal;
            }
            else {
                return false;
            }
        },
        minimize: function (id) {
            var doc = mwd;
            var modal = mw.$("#" + id);
            var window_h = $(doc.defaultView).height();
            var window_w = $(doc.defaultView).width();
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
            var modal = $("#" + id);
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
            var obj = $.extend({}, mw.tools.modal.settings, obj);
            var frame = "<iframe name='frame-" + obj.name + "' id='frame-" + obj.name + "' style='overflow-x:hidden;overflow-y:auto;' class='mw-modal-frame' src='" + mw.external_tool(obj.url) + "'  frameBorder='0' allowfullscreen></iframe>";
            var modal = mw.tools.modal.init({
                html: frame,
                width: obj.width,
                height: obj.height,
                title: obj.title,
                name: obj.name,
                overlay: obj.overlay,
                draggable: obj.draggable,
                template: obj.template
            });
            $(modal.main).addClass("mw_modal_type_iframe");
            $(modal.container).css("overflow", "hidden");
            if (typeof modal.main == 'undefined') {
                return;
            }
            modal.main[0].querySelector('iframe').contentWindow.thismodal = modal;
            modal.main[0].querySelector('iframe').onload = function () {
                typeof obj.callback === 'function' ? obj.callback.call(modal, this) : '';
                typeof obj.onload === 'function' ? obj.onload.call(modal, this) : '';
            }
            modal.iframe = modal.container.querySelector('iframe');
            return modal;
        },
        remove: function (id) {
            if (typeof id === 'object') {
                if (id.constructor === {}.constructor) {
                    var id = $(id.main)[0].id;
                }
                else {
                    var id = $(id)[0].id;
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
                $(el.overlay).remove();
            }
            $(el).remove();
        },
        modalCompletelyVisibleFromParent: function (modalMain, parentFixedElement) {
            if (!modalMain || modalMain === null || !modalMain.querySelector) return true;
            var frame = mww.frameElement;
            if (frame === null) return true; // means it's the same window
            if (!frame) return true; // in case property does not exists
            if (frame.offsetHeight < $(parent).height()) return true;
            var _top = modalMain.offsetTop;
            var wh = parent.innerHeight;
            var dt = $(parent.document).scrollTop();
            var ft = $(frame).offset().top;
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
            var root = modal.constructor === {}.constructor ? $(modal.main)[0] : modal;
            var win = $(window),
                maxW = win.width() - 50,
                maxH = win.height() - 50;
            if (!!w) {
                if (typeof w === 'number') {
                    var w = w < maxW ? w : maxW;
                }
                root.style.width = mw.tools.cssNumber(w);
            }
            if (!!h) {
                var toolbar_height = $('.mw_modal_toolbar', modal).outerHeight();
                if(typeof h == 'string'){
                    if(h.indexOf('px') !== -1){
                        h = parseInt(h, 10);
                        h = h + toolbar_height
                    }
                }
                else{
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
                $(root).trigger("resize");
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
            var modal = $(modal);
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
            var id = !!modal ? $(modal).attr("id") : 'none';
            $(overlay).attr("rel", id);
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
                    $(modal.container).addClass('mw_gallery_loading');
                }
                mw.image.preload(img, function (w, h) {
                    if (typeof desc != 'undefined' && desc != '') {
                        callback.call("<div class='mwf-single-holder'><img src='" + img + "'  class='mwf-single mwf-single-loading '  width='" + w + "' data-width='" + w + "' data-height='" + h + "' height='" + h + "' onclick='mw.tools.gallery.next()' onload='mw.tools.gallery.normalize(mw.$(\"#mw_gallery\")[0].modal);'  /><div class='mwf-gallery-description'><div class='mwf-gallery-description-holder'>" + desc + "</div></div></div>");
                    }
                    else {
                        callback.call("<div class='mwf-single-holder'><img src='" + img + "'  data-width='" + w + "' width='" + w + "' data-height='" + h + "' height='" + h + "' class='mwf-single mwf-single-loading' onclick='mw.tools.gallery.next()' onload='mw.tools.gallery.normalize(mw.$(\"#mw_gallery\")[0].modal);' /></div>");
                    }
                    $(modal.container).removeClass('mw_gallery_loading');
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
            var modal = modal || mw.$("#mw_gallery")[0].modal;
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
            var modal = modal || mw.$("#mw_gallery")[0].modal;
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
                $('.mwf-loader', modal.container).stop().css({width: '0%'})
            }
            else {
                mw.tools.gallery.playing = true;
                interval = interval || 4000;
                $('.mwf-loader', modal.container).stop().css({width: '0%'}).animate({width: '100%'}, interval);
                mw.tools.gallery.playingInt = setInterval(function () {
                    if (mw.tools.gallery.playing) {
                        if ($('.mwf-loader', modal.container).length > 0) {
                            $('.mwf-loader', modal.container).stop().css({width: '0%'}).animate({width: '100%'}, interval);
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
                    $(this).toggleClass('active');
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
            var ww = $(window).width();
            var wh = $(window).height();
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
                $(holder).width($(holder).width())
                $(holder).height($(holder).height())
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
                $(window).bind("resize", function () {
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
            if (!!el.mwDropdownActivated) {
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
                        $(this.dropdown).removeClass("active");
                        mw.$('.mw-dropdown-content', this.dropdown).hide();
                        $(this.dropdown).setDropdownValue(this.value, true, true);
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
                            input.value = $(this).getDropdownValue();
                            mw.wysiwyg.save_selection(true);
                            $(input).focus();
                        }
                    }
                    $(this).toggleClass("active");
                    $(".mw-dropdown").not(this).removeClass("active").find(".mw-dropdown-content").hide();
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
            mw.$(el).hover(function () {
                $(this).add(this);
                if (mw.tools.hasClass(cls, 'other-action')) {
                    $(this).addClass('other-action');
                }
            }, function () {
                $(this).removeClass("hover");
                $(this).removeClass('other-action');
            });
            mw.$(el).on('mousedown touchstart', 'li[value]', function (event) {
                $(mw.tools.firstParentWithClass(this, 'mw-dropdown')).setDropdownValue(this.getAttribute('value'), true);
                return false;
            });
        }
        /* end For loop */
        if (typeof mw.tools.dropdownActivated === 'undefined') {
            mw.tools.dropdownActivated = true;
            $(mwd.body).mousedown(function (e) {
                if ($(e.target).hasClass('mw-dropdown-content')
                    || $(e.target).hasClass('mw-dropdown')
                    || mw.tools.hasParentsWithClass(e.target, 'mw-dropdown')
                ) {
                    // dont hide the dropdown
                } else if (mw.$('.mw-dropdown.hover').length == 0) {
                    mw.$(".mw-dropdown").removeClass("active");
                    mw.$(".mw-dropdown-content").hide();
                }
            });
        }
    },
    module_slider: {
        scale: function () {
            var window_width = $(window).width();
            mw.$(".modules_bar").each(function () {
                $(this).width(window_width - 167);
                mw.$(".modules_bar_slider", this).width(window_width - 183);
            });
        },
        prepare: function () {
            mw.$(".modules_bar").each(function () {
                var module_item_width = 0;
                mw.$("li", this).each(function () {
                    module_item_width += $(this).outerWidth(true);
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
            return $(".modules_bar").width() - 60;
        }, /*120*/
        ctrl_show_hide: function () {
            mw.$(".modules_bar").each(function () {
                var el = $(this);
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
                $(this).addClass("active");
            });
            mw.$(".modules_bar_slide_right,.modules_bar_slide_left").bind("mouseup mouseout", function () {
                $(this).removeClass("active");
            });
        },
        slide_left: function (item) {
            var item = $(item);
            mw.tools.toolbar_slider.ctrl_show_hide();
            var left = mw.$(".modules_bar", item[0].parentNode).scrollLeft();
            mw.$(".modules_bar", item[0].parentNode).stop().animate({scrollLeft: left - mw.tools.toolbar_slider.off()}, function () {
                mw.tools.toolbar_slider.ctrl_show_hide();
            });
        },
        slide_right: function (item) {
            var item = $(item);
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
            var item = $(document.getElementById(id));
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
    copy: function (what) {
    },
    classNamespaceDelete: function (el_obj, namespace, parent, namespacePosition, exception) {
        if(el_obj.element && el_obj.namespace){
          el = el_obj.element
          namespace = el_obj.namespace
          parent = el_obj.parent
          namespacePosition = el_obj.namespacePosition
          exceptions = el_obj.exceptions || []
        }
        else{
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
    tree: {
        toggle: function (el, event) {
            $(el.parentNode).toggleClass('active');
            var master = mw.tools.firstParentWithClass(el, 'mw-tree');
            mw.tools.tree.remember(master);
            "mw.admin.treeboxwidth"._exec();
            if (event.type === 'click') {
                event.stopPropagation();
                event.preventDefault();
                return false;
            }
        },
        open: function (el, parents) {
            var parents = parents || false;
            $(el.parentNode).addClass('active');
            var master = mw.tools.firstParentWithClass(el, 'mw-tree');
            mw.tools.tree.remember(master);
            if (!parents) return;
            mw.tools.foreachParents(el, function (loop) {
                if (mw.tools.hasClass(this, 'mw-tree')) {
                    mw.tools.stopLoop(loop);
                }
                else {
                    if (this.nodeName === 'LI') {
                        mw.tools.tree.open(this.querySelector('.pages_tree_link'), false)
                    }
                }
            });
        },
        del: function (id) {
            mw.tools.confirm(mw.msg.del, function () {
                if (mw.notification != undefined) {
                    mw.notification.success('Content deleted');
                }
                $.post(mw.settings.site_url + "api/content/delete", {id: id}, function (data) {
                    var todelete = mw.$(".item_" + id);
                    todelete.fadeOut(function () {
                        todelete.remove();
                        mw.reload_module('content/trash');
                    });
                });
            })
        },
        del_category: function (id) {
            mw.tools.confirm('Are you sure you want to delete this?', function () {
                $.post(mw.settings.site_url + "api/category/delete", {id: id}, function (data) {
                    var todelete = mw.$(".item_" + id);
                    todelete.fadeOut(function () {
                        if (mw.notification != undefined) {
                            mw.notification.success('Category deleted');
                        }
                        todelete.remove();
                        'mw.admin.treeboxwidth'._exec();
                    });
                });
            })
        },
        detectType: function (tree_object) {
            if (tree_object !== null && typeof tree_object === 'object') {
                return tree_object.querySelector('li input[type="checkbox"], li input[type="radio"]') !== null ? 'selector' : 'controller';
            }
        },
        remember: function (tree) {
            var type = mw.tools.tree.detectType(tree);
            if (type === 'controller') {
                _remember = "";
                var lis = tree.querySelectorAll("li.active");
                var len = lis.length;
                $.each(lis, function (i) {
                    i++;
                    if (!!this.attributes['data-item-id']) {
                        var id = this.attributes['data-item-id'].nodeValue;
                        _remember = i < len ? _remember + id + "," : _remember + id;
                    }
                });
                mw.cookie.ui("tree_" + tree.id, _remember);
            }
        },
        recall: function (tree) {
            if (tree !== null) {
                var ids = mw.cookie.ui("tree_" + tree.id);
                if (typeof(ids) != 'undefined' && ids != false) {
                    var ids = ids.split(",");
                    $.each(ids, function (a, b) {
                        if (tree.querySelector('.item_' + b)) {
                            tree.querySelector('.item_' + b).className += ' active';
                        }
                    });
                }
            }
        },
        toggleit: function (el, event, pageid) {
            event.stopPropagation();
            mw.tools.tree.toggle(el, event);
        },
        openit: function (el, event, pageid) {
            event.stopPropagation();
            if (mw.askusertostay === true) {
                return false;
            }
            if (el.attributes['data-page-id'] !== undefined) {
                mw.url.windowHashParam('action', 'showposts:' + pageid);
            }
            else if (el.attributes['data-category-id'] !== undefined) {
                mw.url.windowHashParam('action', 'showpostscat:' + pageid);
            }
            mw.tools.tree.open(el, event);
            'mw.admin.treeboxwidth'._exec();
        },
        closeAll: function (tree) {
            $(tree.querySelectorAll('li')).removeClass('active').removeClass('active-bg');
            mw.tools.tree.remember(tree);
            'mw.admin.treeboxwidth'._exec();
        },
        openAll: function (tree) {
            $(tree.querySelectorAll('li')).addClass('active');
            mw.tools.tree.remember(tree);
            'mw.admin.treeboxwidth'._exec();
        },
        checker: function (el) {
            var is_checkbox = el.getElementsByTagName('input')[0];
            if (is_checkbox.type != 'checkbox') {
                return false;
            }
            var state = el.getElementsByTagName('input')[0].checked;
            if (state === true) {
                if (is_checkbox.type == 'checkbox') {
                    var ul = mw.tools.firstParentWithClass(is_checkbox, 'pages_tree');
                    if (ul != false) {
                        if (ul.querySelector('input[type="radio"]:checked') !== null) {
                            return false;
                        }
                    }
                }
                mw.tools.foreachParents(el.parentNode, function (loop) {
                    this.tagName === 'LI' ? this.getElementsByTagName('input')[0].checked = true : '';
                    this.tagName === 'DIV' ? mw.tools.stopLoop(loop) : '';
                });
            }
            else {
            }
        },
        old_checker: function (el) {
            var is_checkbox = el.getElementsByTagName('input')[0];
            if (is_checkbox.type != 'checkbox') {
                return false;
            }
            var state = el.getElementsByTagName('input')[0].checked;
            if (state === true) {
                mw.tools.foreachParents(el.parentNode, function (loop) {
                    this.tagName === 'LI' ? this.getElementsByTagName('input')[0].checked = true : '';
                    this.tagName === 'DIV' ? mw.tools.stopLoop(loop) : '';
                });
            }
            else {
                var f = el.parentNode.getElementsByTagName('input'), i = 0, len = f.length;
                for (; i < len; i++) {
                    f[i].checked = false;
                }
            }
        },
        viewChecked: function (tree) {
            var all = tree.querySelectorAll('li input'), i = 0, len = all.length;
            for (; i < len; i++) {
                var curr = all[i];
                curr.parentNode.parentNode.style.display = !curr.checked ? 'none' : '';
            }
        }
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
        if(!node) return;
        var has = true;
        var i =0, nodec = node.className.trim().split(' ');
        for(; i<arr.length;i++){
            if(nodec.indexOf(arr[i]) === -1){
                return false;
            }
        }
        return has;
    },
    hasAnyOfClasses: function (node, arr) {

        var final = false, i = 0, l = arr.length, cls = node.className;
        for (; i < l; i++) {
            if (mw.tools.hasClass(cls, arr[i])) {
                var final = true;
            }
        }
        return final;
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
        var curr = node,
            has1 = false,
            has2 = false;
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
    parentsOrCurrentOrderMatch: function (node, arr) {
        var curr = node,
            match = {a:0,b:0},
            count = 1;
        while (curr !== document.body) {
            count++;
            var h1 = mw.tools.hasClass(curr, arr[0]);
            var h2 = mw.tools.hasClass(curr, arr[1]);
            if (h1 && h2) {
                if(match.a > 0){
                    return true;
                }
                return false;
            }
            else {
                if (h1) {
                    match.a=count;
                }
                else if (h2) {
                    match.b=count;
                }
                if(match.b > match.a){
                    return true;
                }
            }
            curr = curr.parentNode;
        }
        return false;
    },
    parentsOrCurrentOrderMatchOrOnlyFirstOrBoth: function (node, arr) {
        var curr = node,
            has1 = false,
            has2 = false;
        while (curr !== document.body) {
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
        return true;
    },
    matchesAnyOnNodeOrParent: function (node, arr) {
        arr.forEach(function (selector) {
            if (mw.tools.matches(node, selector)) {
                return true;
            }
        });
        var has = false;
        mw.tools.foreachParents(node, function (loop) {
            var i = 0;
            for (; i < arr.length; i++) {
                if (mw.tools.matches(this, arr[i])) {
                    has = true;
                    mw.tools.stopLoop(loop);
                    return has;
                }
            }
        });
        return has;
    },
    firstMatchesOnNodeOrParent: function (node, arr) {
        var i = 0;
        for( ; i<arr.length ; i++){
          if (mw.tools.matches(node, arr[i])) {
              return node;
          }
        }
        var has = false;
        mw.tools.foreachParents(node, function (loop) {
            var el = this;
            arr.forEach(function (selector) {
                if (mw.tools.matches(el, selector)) {
                    has = el;
                    mw.tools.stopLoop(loop)
                }
            });
        });
        return has;
    },
    hasAnyOfClassesOnNodeOrParent: function (node, arr) {
        if (mw.tools.hasAnyOfClasses(node, arr)) {
            return true;
        }
        var has = false;
        mw.tools.foreachParents(node, function (loop) {
            if (mw.tools.hasAnyOfClasses(this, arr)) {
                has = true;
                mw.tools.stopLoop(loop);
            }
        });
        return has;
    },
    addClass: function (el, cls) {
        if (el === null) {
            return false;
        }
        if (cls == ' ') {
            return false;
        }
        var arr = cls.split(" ");
        var l = arr.length, i = 0;
        if (l > 1) {
            for (; i < l; i++) {
                mw.tools.addClass(el, arr[i]);
            }
            return;
        }
        if (typeof el === 'object') {
            if (!mw.tools.hasClass(el.className, cls)) el.className += (' ' + cls);
        }
        if (typeof el === 'string') {
            if (!mw.tools.hasClass(el, cls)) el += (' ' + cls);
        }
    },
    removeClass: function (el, cls) {
        if (el === null) {
            return false;
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
        if (cls == ' ') {
            return false;
        }
        if (typeof(cls) == 'object') {
            var arr = cls;
        } else {
            var arr = cls.split(" ");
        }
        var l = arr.length, i = 0;
        if (l > 1) {
            for (; i < l; i++) {
                mw.tools.removeClass(el, arr[i]);
            }
            return;
        }
        if (mw.tools.hasClass(el.className, cls)) el.className = (el.className + ' ').replace(cls + ' ', '').replace(/\s{2,}/g, ' ');
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
        mw.tools.foreachParents(event.target, function () {
            var l = array.length, i = 0;
            for (; i < l; i++) {
                if (event.target === array[i]) {
                    return true;
                }
            }
        });
        return false;
    },
    isEventOnClass: function (event, cls) {
        if (mw.tools.hasClass(event.target, cls) || mw.tools.hasParentsWithClass(event.target, cls)) {
            return true;
        }
        return false;
    },
    hasParentsWithClass: function (el, cls) {
        var has = false;
        mw.tools.foreachParents(el, function (loop) {
            if (mw.tools.hasClass(this.className, cls)) {
                has = true;
                mw.tools.stopLoop(loop);
            }
        });
        return has;
    },
    hasParentsWithAttr: function (el, cls) {
        var d = {};
        d.toreturn = false;
        mw.tools.foreachParents(el, function (loop) {
            if (cls in this) {
                d.toreturn = true;
                mw.tools.stopLoop(loop);
            } else if (this.hasOwnProperty(cls)) {
                d.toreturn = true;
                mw.tools.stopLoop(loop);
            }
        });
        return d.toreturn;
    },
    hasChildrenWithTag: function (el, tag) {
        var tag = tag.toLowerCase();
        var d = {};
        d.toreturn = false;
        mw.tools.foreachChildren(el, function (loop) {
            if (this.nodeName.toLowerCase() === tag) {
                d.toreturn = true;
                mw.tools.stopLoop(loop);
            }
        });
        return d.toreturn;
    },
    hasParentsWithTag: function (el, tag) {
        var tag = tag.toLowerCase();
        var d = {};
        d.toreturn = false;
        mw.tools.foreachParents(el, function (loop) {
            if (this.nodeName.toLowerCase() === tag) {
                d.toreturn = true;
                mw.tools.stopLoop(loop);
            }
        });
        return d.toreturn;
    },
    hasHeadingParent: function (el) {
        var d = {};
        d.toreturn = false;
        var h = /^(h[1-6])$/i;
        mw.tools.foreachParents(el, function (loop, i) {
            if (h.test(this.nodeName.toLowerCase())) {
                d.toreturn = true;
                mw.tools.stopLoop(loop);
            }
        });
        return d.toreturn;
    },
    loop: {/* Global index for MW loops */},
    stopLoop: function (loop) {
        mw.tools.loop[loop] = false;
    },
    foreachParents: function (el, callback) {
        if (typeof el === 'undefined') return false;
        if (el === null) return false;
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
        if (typeof el === 'undefined') return false;
        if (el === null) return false;
        var index = mw.random();
        mw.tools.loop[index] = true;
        var _curr = el.firstChild;
        var count = -1;
        if (_curr !== null && _curr !== undefined) {
            while (_curr.nextSibling !== null) {
                if (_curr.nodeType === 1) {
                    count++;
                    var caller = callback.call(_curr, index, count);
                    var _curr = _curr.nextSibling;
                    if (caller === false || _curr === null || _curr === undefined || !mw.tools.loop[index]) {
                        delete mw.tools.loop[index];
                        break
                    }
                    var _tag = _curr.tagName;
                }
                else {
                    var _curr = _curr.nextSibling;
                }
            }
        }
    },
    firstChildWithClass: function (parent, cls) {
        var g = {toreturn: undefined}
        mw.tools.foreachChildren(parent, function (loop) {
            if (this.nodeType === 1 && mw.tools.hasClass(this, cls)) {
                mw.tools.stopLoop(loop);
                g.toreturn = this;
            }
        });
        return g.toreturn;
    },
    firstChildWithTag: function (parent, tag) {
        var g = {toreturn: undefined}
        var tag = tag.toLowerCase();
        mw.tools.foreachChildren(parent, function (loop) {
            if (this.nodeName.toLowerCase() === tag) {
                g.toreturn = this;
                mw.tools.stopLoop(loop);
            }
        });
        return g.toreturn;
    },
    hasChildrenWithClass: function (node, cls) {
        var g = {}
        g.final = false;
        mw.tools.foreachChildren(node, function () {
            if (mw.tools.hasClass(this.className, cls)) {
                g.final = true;
            }
        });
        return g.final;
    },
    parentsOrder: function (node, arr) {
        var only_first = [];
        var obj = {}, l = arr.length, i = 0;
        for (; i < l; i++) {
            obj[arr[i]] = -1;
        }
        mw.tools.foreachParents(node, function (loop, count) {
            var cls = this.className;
            var i = 0;
            for (; i < l; i++) {
                if (mw.tools.hasClass(cls, arr[i]) && only_first.indexOf(arr[i]) == -1) {
                    obj[arr[i]] = count;
                    only_first.push(arr[i]);
                }
            }
        });
        return obj;
    },
    firstParentWithClass: function (el, cls) {
        _has = false;
        mw.tools.foreachParents(el, function (loop) {
            if (mw.tools.hasClass(this.className, cls)) {
                _has = this;
                mw.tools.stopLoop(loop);
            }
        });
        return _has;
    },
    firstParentOrCurrentWithAllClasses: function (node, arr) {
        if (mw.tools.hasAllClasses(node, arr)) {
            return node;
        }
        var has = false;
        mw.tools.foreachParents(node, function (loop) {
            if (mw.tools.hasAllClasses(this, arr)) {
                has = this;
                mw.tools.stopLoop(loop);
            }
        });
        return has;
    },
    firstParentOrCurrentWithAnyOfClasses: function (node, arr) {
        if (mw.tools.hasAnyOfClasses(node, arr)) {
            return node;
        }
        var has = false;
        mw.tools.foreachParents(node, function (loop) {
            if (mw.tools.hasAnyOfClasses(this, arr)) {
                has = this;
                mw.tools.stopLoop(loop);
            }
        });
        return has;
    },
    lastParentWithClass: function (el, cls) {
        _has = false;
        mw.tools.foreachParents(el, function (loop) {
            if (mw.tools.hasClass(this.className, cls)) {
                _has = this;
            }
        });
        return _has;
    },
    firstParentWithTag: function (el, tag) {
        var tag = typeof tag !== 'string' ? tag : [tag];
        _has = false;
        mw.tools.foreachParents(el, function (loop) {
            if (tag.indexOf(this.nodeName.toLowerCase()) !== -1) {
                _has = this;
                mw.tools.stopLoop(loop);
            }
        });
        return _has;
    },
    toggle: function (who, toggler, callback) {
        var who = mw.$(who);
        who.toggle();
        who.toggleClass('toggle-active');
        $(toggler).toggleClass('toggler-active');
        mw.is.func(callback) ? callback.call(who) : '';
    },
    memoryToggle: function (toggler) {
        if (typeof _MemoryToggleContentID == 'undefined') return false;
        var id = toggler.id;
        var who = $(toggler).dataset('for');
        mw.tools.toggle(who, "#" + id);
        var page = "page_" + _MemoryToggleContentID;
        var is_active = $(toggler).hasClass('toggler-active');
        if (_MemoryToggleContentID == '0') return false;
        var curr = mw.cookie.ui(page);
        if (curr == "") {
            var obj = {}
            obj[id] = is_active;
            mw.cookie.ui(page, obj);
        }
        else {
            curr[id] = is_active;
            mw.cookie.ui(page, curr);
        }
    },
    memoryToggleRecall: function () {
        if (typeof _MemoryToggleContentID == 'undefined') return false;
        var page = "page_" + _MemoryToggleContentID;
        var curr = mw.cookie.ui(page);
        if (curr != "") {
            $.each(curr, function (a, b) {
                if (b == true) {
                    var toggler = mw.$("#" + a);
                    toggler.addClass('toggler-active');
                    var who = toggler.dataset("for");
                    $(who).show().addClass('toggle-active');
                    var callback = toggler.dataset("callback");
                    if (callback != "") {
                        mw.wait(callback, function () {
                            window[callback]();
                        });
                    }
                }
            });
        }
    },
    confirm: function (question, callback) {
        var conf = confirm(question);
        if (conf && typeof callback === 'function') {
            callback.call(window);
        }
        return conf;
    },
    el_switch: function (arr, type) {
        if (type === 'semi') {
            $(arr).each(function () {
                var el = $(this);
                if (el.hasClass("semi_hidden")) {
                    el.removeClass("semi_hidden");
                }
                else {
                    el.addClass("semi_hidden");
                }
            });
        }
        else {
            $(arr).each(function () {
                var el = $(this);
                if (el.css('display') == 'none') {
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
                this !== el ? $(this).removeClass('mw-focus') : '';
            });
            $(el).addClass('mw-focus');
        }
    },
    scrollTo: function (el, callback, minus) {
        var minus = minus || 0;
        if ($(el).length === 0) {
            return false;
        }
        if (typeof callback === 'number') {
            var minus = callback;
        }
        mw.$('html,body').animate({scrollTop: $(el).offset().top - minus}, function () {
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
                $(container).slideDown(speed, function () {
                    $(el).addClass('active');
                    typeof callback === 'function' ? callback.call(el, 'visible') : '';
                });
            }
            else {
                $(container).slideUp(speed, function () {
                    $(el).removeClass('active');
                    typeof callback === 'function' ? callback.call(el, 'hidden') : '';
                });
            }
        }
    },
    index: function (el, parent, selector) {
        var el = $(el)[0];
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
        if (typeof el === 'undefined') return false;
        $(el).stop();
        var color = color || '#48AD79';
        var speed1 = speed1 || 777;
        var speed2 = speed2 || 777;
        var curr = window.getComputedStyle(el, null).backgroundColor;
        if (curr == 'transparent') {
            var curr = '#ffffff';
        }
        $(el).animate({backgroundColor: color}, speed1, function () {
            $(el).animate({backgroundColor: curr}, speed2, function () {
                $(el).css('backgroundColor', '');
            })
        });
    },
    highlightStop: function (el) {
        $(el).stop();
        $(el).css('backgroundColor', '');
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
            check ? $(arguments[i]).addClass('hovered') : $(arguments[i]).removeClass('hovered');
        }
    },
    search: function (string, selector, callback) {
        var string = string.toLowerCase();
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
            mw.tools.ajaxIsSearching = true;
            var obj = $.extend({}, mw.tools.ajaxSearcSetting, o);
            $.post(mw.settings.site_url + "api/get_content_admin", obj, function (data) {
                callback.call(data);
            }).always(function () {
                mw.tools.ajaxIsSearching = false
            });
        }
    },
    iframeLinksToParent: function (iframe) {
        $(iframe).contents().find('a').each(function () {
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
        var t = obj.nodeName.toLowerCase();
        if (t == 'input' || t == 'textarea' || t == 'select') {
            return true
        }
        ;
        return false;
    },
    getAttrs: function (el) {
        var attrs = el.attributes;
        var obj = {}
        for (var x in attrs) {
            var dis = attrs[x];
            obj[dis.nodeName] = dis.nodeValue
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
        var m = m || true;
        if (m) {
            var m = {
                set: function (i) {
                    if (typeof i === 'number') {
                        if (!$(obj.nav).eq(i).hasClass(active)) {
                            mw.$(obj.nav).removeClass(active);
                            $(obj.nav).eq(i).addClass(active);
                            mw.$(obj.tabs).hide().eq(i).show();
                        }
                    }
                },
                unset: function (i) {
                    if (typeof i === 'number') {
                        if ($(obj.nav).eq(i).hasClass(active)) {
                            $(obj.nav).eq(i).removeClass(active);
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
        var active = obj.activeNav || obj.activeClass || "active";
        mw.$(obj.nav).click(function (e) {
            if (!$(this).hasClass(active)) {
                var i = mw.tools.index(this, mw.$(obj.nav).get(), mw.$(obj.nav)[0].nodeName);
                mw.$(obj.nav).removeClass(active);
                $(this).addClass(active);
                mw.$(obj.tabs).hide().eq(i).show();
                if (typeof obj.onclick == 'function') {
                    obj.onclick.call(this, mw.$(obj.tabs).eq(i)[0], e, i);
                }
            }
            else {
                if (obj.toggle == true) {
                    $(this).removeClass(active);
                    mw.$(obj.tabs).hide();
                    if (typeof obj.onclick == 'function') {
                        var i = mw.tools.index(this, master, obj.nav);
                        obj.onclick.call(this, mw.$(obj.tabs).eq(i)[0], e, i);
                    }
                }
            }
            return false;
        });
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
        $(mw._html_info).html(html);
        return mw._html_info;
    },
    image_info: function (a, callback) {
        var img = mwd.createElement('img');
        img.className = 'semi_hidden';
        img.src = a.src;
        mwd.body.appendChild(img);
        img.onload = function () {
            callback.call({width: $(img).width(), height: $(img).height()});
            $(img).remove();
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
        if (el.getElementsByTagName('input').length === 0) {
            var textonly = textonly || true;
            var input = mwd.createElement('input');
            input.type = "text";
            input.className = (fieldClass || "mw-ui-field") + ' mw-liveedit-field';
            input.style.width = $(el).width() + 'px';
            if (textonly === true) {
                input.value = el.textContent;
                input.onblur = function () {
                    var val = this.value;
                    var ischanged = this.changed === true;
                    setTimeout(function () {
                        $(el).text(val);
                        if (typeof callback === 'function' && ischanged) {
                            callback.call(val, el);
                        }
                    }, 3);
                }
                input.onkeydown = function (e) {
                    if (e.keyCode === 13) {
                        var val = this.value;
                        $(el).text(val);
                        if (typeof callback === 'function') {
                            callback.call(val, el);
                        }
                        return false;
                    }
                }
            }
            else {
                input.value = el.innerHTML;
                input.onblur = function () {
                    var val = this.value;
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
                        var val = this.value
                        el.innerHTML = val;
                        if (typeof callback === 'function') {
                            callback.call(val, el);
                        }
                        return false;
                    }
                }
            }
            $(el).empty().append(input);
            $(input).focus();
            input.changed = false;
            $(input).change(function () {
                this.changed = true;
            });
            $(input).bind('keydown keyup paste', function (e) {
                var el = this;
                el.style.width = 0 + 'px';
                el.style.width = el.scrollWidth + 6 + 'px';
                if (e.type === 'paste') {
                    setTimeout(function () {
                        el.style.width = 0 + 'px';
                        el.style.width = el.scrollWidth + 6 + 'px';
                    }, 70);
                }
                if (mw.is.ie) {
                    el.style.width = (el.value.length * 5.9) + 'px';
                }
            });
        }
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
            var data = $(el).dataset(a);
            if (!!$(el)[0].attributes) {
                var attr = $(el)[0].attributes[a];
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
            $(el).dataset(a, b);
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
        $(frame).load(function () {
            frame.contentWindow.thisframe = frame;
            var cont = $(frame).contents().find("#mw-iframe-editor-area");
            cont[0].contentEditable = true;
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
        $(frame).bind('change', function (e, val) {
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
        height: 320,
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
        $(o.element).after(frame);
        $(o.element).hide();
        $.get(mw.external_tool('editor_toolbar'), function (a) {
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
                    frame.contentWindow.pauseChange = false;
                }, frame.contentWindow.SetValueTime);
            }
        });
        o.width = o.width != 'auto' ? o.width : '100%';
        $(frame).css({width: o.width, height: o.height});
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
        var _el = $(el);
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
            if (global) $(mwd.body).addClass("loading");
        }
        return el;
    },
    enable: function (el) {
        var _el = $(el);
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
        $(mwd.body).removeClass("loading");
        return el;
    },
    loading: function (el, state) {
        if(el === false){
            this.loading('.mw-loading', false);
            this.loading('.mw-loading-defaults', false);
            return;
        }
        if(typeof el === 'boolean'){
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
                $(el).addClass("mw-loading-defaults");
            }
        }
        if (state) {
            $(el).addClass("mw-loading");
        }
        else {
            $(el).removeClass("mw-loading mw-loading-defaults");
        }
    },
    loading:function (element, progress, speed ) {
        /*

            progress:number 0 - 100,
            speed:string, -> 'slow', 'normal, 'fast'

            mw.tools.loading(true) -> slowly animates to 95% on body
            mw.tools.loading(false) -> fast animates to 100% on body

        */
        function set(el, progress, speed){
            speed = speed || 'normal';
            mw.tools.removeClass(el, 'mw-progress-slow');
            mw.tools.removeClass(el, 'mw-progress-normal');
            mw.tools.removeClass(el, 'mw-progress-fast');
            mw.tools.addClass(el, 'mw-progress-' + speed);
            element.__loadingTime = setTimeout(function(){
                el.querySelector('.mw-progress-index').style.width = progress + '%';
            }, 10)

        }
        if(typeof element === 'boolean'){
            progress = !!element;
            element = mwd.body;
        }
        if(typeof element === 'number'){
            progress = element;
            element = mwd.body;
        }
        if(element === document || element === mwd.documentElement){
            element = mwd.body;
        }
        element = $(element)[0]
        if (element === null || !element) return false;
        if(element.__loadingTime){
            clearTimeout(element.__loadingTime)
        }
        var isLoading = mw.tools.hasClass(element, 'mw-loading');
        var el = element.querySelector('.mw-progress');
        if(!el){
            el = document.createElement('div');
            el.className = 'mw-progress';
            el.innerHTML = '<div class="mw-progress-index"></div>';
            if(element === mwd.body) el.style.position = 'fixed';
            element.appendChild(el);
        }
        var pos = mw.CSSParser(element).get.position();
        if(pos == 'static'){
            element.style.position = 'relative';
        }
        if(progress){
            if(progress === true){
                set(el, 95, speed||'slow')
            }
            else if(typeof progress === 'number'){
                progress = progress <= 100 ? progress : 100;
                progress = progress >= 0 ? progress : 0;
                set(el, progress, speed)
            }
        }
        else{
            if(el){
               set(el, 100, speed||'fast')
            }
            element.__loadingTime = setTimeout(function(){
                    $(element).removeClass('mw-loading-defaults mw-loading');
                    $(el).remove()
            }, 700)
        }
    },
    prependClass:function(el,cls){
        el.className = (cls + ' ' + el.className).trim()
    },
    inview: function (el) {
        var $el = $(el);
        if ($el.length === 0) {
            return false;
        }
        var dt = $(window).scrollTop(),
            db = dt + $(window).height(),
            et = $el.offset().top;
        return (et <= db) && !(dt > ($el.height() + et));
    },
    wholeinview: function (el) {
        var $el = $(el),
            dt = $(window).scrollTop(),
            db = dt + $(window).height(),
            et = $el.offset().top,
            eb = et + $(el).height();
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
        $(n1).bind(events, function () {
            n2[setValue2] = n1[setValue1];
            $(n2).trigger('change');
        });
        $(n2).bind(events, function () {
            n1[setValue1] = n2[setValue2];
            $(n1).trigger('change');
        });
    },
    copyEvents: function (from, to) {
        if (typeof $._data(from, 'events') === 'undefined') {
            return false;
        }
        $.each($._data(from, 'events'), function () {
            $.each(this, function () {
                $(to).bind(this.type, this.handler);
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
        $(node).replaceWith(el);
        return el;
    },
    _fixDeniedParagraphHierarchySelector: ''
    + '.edit p h1,.edit p h2,.edit p h3,'
    + '.edit p h4,.edit p h5,.edit p h6,'
    + '.edit p p,.edit p ul,.edit p ol,'
    + '.edit p header,.edit p form,.edit p article,'
    + '.edit p aside,.edit p blockquote,.edit p footer,.edit p div',
    fixDeniedParagraphHierarchy: function (root) {
        var root = root || mwd.body;
        if (mwd.body.querySelector(mw.tools._fixDeniedParagraphHierarchySelector) !== null) {
            var all = root.querySelectorAll(mw.tools._fixDeniedParagraphHierarchySelector), l = all.length, i = 0;
            for (; i < l; i++) {
                var el = all[i];
                var the_parent = mw.tools.firstParentWithTag(el, 'p');
                mw.tools.setTag(the_parent, 'div');
            }
        }
    },
    generateSelectorForNode: function (node) {
        if (node === null || node.nodeType === 3) {
            return false;
        }
        if (node.nodeName === 'BODY') {
            return 'body';
        }
        if (node.id != '') {
            return '#' + node.id;
        }
        var ___final = node.className != '' ? '.' + node.className.trim().split(' ').join('.') : node.nodeName.toLocaleLowerCase();
        ___final = ___final.replace(/\.\./g, '.');
        mw.tools.foreachParents(node, function (loop) {
            if (this.id != '') {
                ___final = '#' + this.id + ' > ' + ___final;
                mw.tools.stopLoop(loop);
                return false
            }
            if (this.className != '') {
                var n = this.nodeName.toLocaleLowerCase() + '.' + this.className.trim().split(' ').join('.');
            }
            else {
                var n = this.nodeName.toLocaleLowerCase();
            }
            ___final = n + ' > ' + ___final;
        });
        return ___final;
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
        if(!body) return;
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
                console.log(result)
                if(result == 'granted'){
                    return mw.tools.notification(body, tag, icon)
                }
                else if(result == 'denied'){
                    mw.notification.error('Notifications are blocked')
                }
                else if(result == 'default'){
                    mw.notification.warn('Notifications are canceled')

                }
            });
        }
    },
    TemplateSettingsEventsBinded: false,
    TemplateSettingsModalDefaults: {
        top: 100,
        width: 300,
        timeout: null
    },
    hide_template_settings: function () {
        mw.$('.mw-template-settings').css('right', -mw.tools.TemplateSettingsModalDefaults.width - 5).addClass('mw-template-settings-hidden');
        mw.$("#toolbar-template-settings").removeClass("mw_editor_btn_active");
    },
    show_template_settings: function () {
        if (mw.$('.mw-template-settings').length === 0) {
            mw.tools.template_settings();
        }
        mw.$('.mw-template-settings').css('right', 0).removeClass('mw-template-settings-hidden');
        mw.$("#toolbar-template-settings").addClass("mw_editor_btn_active");
        mw.cookie.set("remove_template_settings", "false");
    },
    toggle_template_settings: function () {
        if (mw.$('.mw-template-settings').hasClass('mw-template-settings-hidden') || mw.$('.mw-template-settings').length === 0) {
            mw.tools.show_template_settings();
        }
        else {
            mw.tools.hide_template_settings();
        }
    },
    template_settings: function (justInit) {
        var justInit = justInit || false;
        if (mw.$('.mw-template-settings').length === 0) {
            var src = mw.settings.site_url + 'api/module?id=settings/template&live_edit=true&module_settings=true&type=settings/template&autosize=false';
            var modal = mw.tools.modal.frame({
                url: src,
                width: mw.tools.TemplateSettingsModalDefaults.width,
                height: $(window).height() - (1.5 * mw.tools.TemplateSettingsModalDefaults.top),
                name: 'template-settings',
                //title:'Template Settings',
                template: 'mw-template-settings',
                center: false,
                resize: false,
                draggable: false
            });
            $(modal.main).css({
                right: -mw.tools.TemplateSettingsModalDefaults.width - 115,
                left: 'auto',
                top: mw.tools.TemplateSettingsModalDefaults.top,
                zIndex: 1299
            }).addClass('mw-template-settings-hidden');
            $(window).bind('resize', function () {
                clearTimeout(mw.tools.TemplateSettingsModalDefaults.timeout);
                mw.tools.TemplateSettingsModalDefaults.timeout = setTimeout(function () {
                    mw.tools.modal.setDimmensions(modal, undefined, $(window).height() - (1.5 * mw.tools.TemplateSettingsModalDefaults.top), false);
                }, 333);
            });
            mw.$('iframe', $(modal.main)[0]).bind('load', function () {
                if (justInit) {
                    mw.tools.hide_template_settings();
                }
                else {
                    mw.tools.show_template_settings();
                }
            });

            //
            //  Open template settings icon is sidebar
            //  $(modal.main).append('<span class="template-settings-icon"></span><span class="template-settings-close"><span class="template-settings-close-x"></span>' + mw.msg.remove + '</span>');

            mw.$('.template-settings-icon').click(function () {
                mw.tools.toggle_template_settings();
            });
            mw.$('.template-settings-close').click(function () {
                mw.$('.mw-template-settings').remove();
                mw.cookie.set("remove_template_settings", "true");
                mw.tools.hide_template_settings();
                var cookie = mw.cookie.get("template_settings_message");
                if (typeof cookie == 'undefined' || cookie == 'true') {
                    mw.cookie.set("template_settings_message", 'false', 3048);
                    var actions = mw.$('#toolbar-template-settings');
                    var tooltip = mw.tooltip({
                        element: actions,
                        content: "<div style='text-align:center;width:200px;'>" + mw.msg.templateSettingsHidden + ".</div>",
                        position: "bottom-center"
                    });
                    mw.$("#toolbar-template-settings .ed-ico").addClass("action");
                    setTimeout(function () {
                        mw.$(tooltip).fadeOut(function () {
                            $(tooltip).remove();
                            mw.$("#toolbar-template-settings .ed-ico").removeClass("action");
                        });
                    }, 2000);
                }
            });
        }
        else {
            mw.tools.hide_template_settings();
        }
        if (!mw.tools.TemplateSettingsEventsBinded) {
            mw.tools.TemplateSettingsEventsBinded = true;
            $(mwd.body).bind('click', function (e) {
                if (!mw.tools.hasParentsWithClass(e.target, 'mw-template-settings') && !mw.tools.hasParentsWithClass(e.target, 'mw-defaults')) {
                    mw.tools.hide_template_settings();
                }
            });
        }
    },


    show_live_edit_sidebar: function () {
        if (mw.$('#live_edit_side_holder').hasClass('sidebar_opened')) {
            $('#live_edit_side_holder').removeClass('sidebar_opened');
            $('a[data-id="mw-toolbar-show-sidebar-btn"]').removeClass('opened');
            mw.cookie.set("show-sidebar-layouts", '0');
            $('body').removeClass('has-opened-sidebar');
        } else {
            $('#live_edit_side_holder').addClass('sidebar_opened');
            $('a[data-id="mw-toolbar-show-sidebar-btn"]').addClass('opened');
            mw.cookie.set("show-sidebar-layouts", '1');
            $('body').addClass('has-opened-sidebar');
        }
    },


    module_settings: function (a, view, liveedit) {
        if (typeof liveedit === 'undefined') {
            var liveedit = true;
        }
        if (typeof a === 'string') {
            var module_type = a;
            var module_id = a;
            var mod_sel = mw.$(a + ':first');
            if (mod_sel.length > 0) {
                var attr = $(mod_sel).attr('id');
                if (typeof attr !== typeof undefined && attr !== false) {
                    var attr = !attr.contains("#") ? attr : attr.replace("#", '');
                    module_id = attr;
                }
                var attr2 = $(mod_sel).attr('type');
                var attr = $(mod_sel).attr('data-type');
                if (typeof attr !== typeof undefined && attr !== false) {
                    module_type = attr;
                } else if (typeof attr2 !== typeof undefined && attr2 !== false) {
                    module_type = attr2;
                }
            }
            var src = mw.settings.site_url + "api/module?id=" + module_id + "&live_edit=" + liveedit + "&module_settings=true&type=" + module_type;
            return mw.tools.modal.frame({
                url: src,
                width: 532,
                height: 250,
                name: 'module-settings-' + a.replace(/\//g, '_'),
                title: '',
                callback: function () {
                }
            });
        }
        var curr = a || $("#mw_handle_module").data("curr");
        var attributes = {};
        if (typeof(curr.id) != 'undefined' && mw.$('#module-settings-' + curr.id).length > 0) {
            var m = mw.$('#module-settings-' + curr.id)[0];
            m.scrollIntoView();
            mw.tools.highlight(m);
            return false;
        }
        $.each(curr.attributes, function (index, attr) {
            attributes[attr.name] = attr.value;
        });
        var data1 = attributes;
        var module_type = null
        if (data1['data-type'] != undefined) {
            module_type = data1['data-type'];
            data1['data-type'] = data1['data-type'] + '/admin';
        }
        if (data1['data-module-name'] != undefined) {
            delete(data1['data-module-name']);
        }
        if (data1['type'] != undefined) {
            module_type = data1['type'];
            data1['type'] = data1['type'] + '/admin';
        }
        if (module_type != null && view != undefined) {
            data1['data-type'] = data1['type'] = module_type + '/' + view;
        }
        if (typeof data1['class'] != 'undefined') {
            delete(data1['class']);
        }
        if (typeof data1['style'] != 'undefined') {
            delete(data1['style']);
        }
        if (typeof data1.contenteditable != 'undefined') {
            delete(data1.contenteditable);
        }
        data1.live_edit = liveedit;
        data1.module_settings = 'true';
        if (view != undefined) {
            data1.view = view;
        }
        else {
            data1.view = 'admin';
        }
        if (data1.from_url == undefined) {
            //data1.from_url = window.top.location;
            data1.from_url = window.parent.location;
        }
        var modal_name = 'module-settings-' + curr.id;
        if (typeof(data1.view.hash) == 'function') {
            //var modal_name = 'module-settings-' + curr.id +(data1.view.hash());
        }
        var src = mw.settings.site_url + "api/module?" + json2url(data1);
        var modal = top.mw.tools.modal.frame({
            url: src,
            width: 532,
            height: 150,
            name: modal_name,
            title: '',
            callback: function () {
                $(this.container).attr('data-settings-for-module', curr.id);
            }
        });
        return modal;
    },
    open_custom_css_editor: function () {
        var src = mw.settings.site_url + 'api/module?id=mw_global_css_editor&live_edit=true&module_settings=true&type=editor/css_editor&autosize=true';
        var modal = mw.tools.modal.frame({
            url: src,
            // width: 500,
            //height: $(window).height() - (2.5 * mw.tools.TemplateSettingsModalDefaults.top),
            name: 'mw-css-editor-front',
            title: 'CSS Editor',
            template: 'default',
            center: false,
            resize: true,
            draggable: true
        });
    },
    open_custom_html_editor: function () {
        var src = mw.settings.site_url + 'api/module?id=mw_global_html_editor&live_edit=true&module_settings=true&type=editor/code_editor&autosize=true';

        window.open(src,"Code editor","toolbar=no, menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=yes");


        //var modal = mw.tools.modal.frame({
        //    url: src,
        //    // width: 500,
        //    // height: $(window).height() - (2.5 * mw.tools.TemplateSettingsModalDefaults.top),
        //    name: 'mw-html-editor-front',
        //    title: 'HTML Editor',
        //    template: 'default',
        //    center: false,
        //    resize: true,
        //    draggable: true
        //});
    },
    open_reset_content_editor: function () {
        var src = mw.settings.site_url + 'api/module?id=mw_global_reset_content_editor&live_edit=true&module_settings=true&type=editor/reset_content&autosize=true';
        var modal = mw.tools.modal.frame({
            url: src,
            // width: 500,
            // height: $(window).height() - (2.5 * mw.tools.TemplateSettingsModalDefaults.top),
            name: 'mw-reset-content-editor-front',
            title: 'Reset content',
            template: 'default',
            center: false,
            resize: true,
            draggable: true
        });
    },
    open_global_module_settings_modal: function (module_type, module_id) {
        var src = mw.settings.site_url + 'api/module?id=' + module_id + '&live_edit=true&module_settings=true&type=' + module_type + '&autosize=true';
        var modal = mw.tools.modal.frame({
            url: src,
            // width: 500,
            //height: $(window).height() - (2.5 * mw.tools.TemplateSettingsModalDefaults.top),
            name: 'mw-module-settings-editor-front',
            title: 'Settings',
            template: 'default',
            center: false,
            resize: true,
            draggable: true
        });
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
    calc: {
        SliderButtonsNeeded: function (parent) {
            var t = {left: false, right: false};
            if (parent == null || !parent) {
                return;
            }
            var el = parent.firstElementChild;
            if ($(parent).width() > $(el).width()) return t;
            var a = $(parent).offset().left + $(parent).width();
            var b = $(el).offset().left + $(el).width();
            if (b > a) {
                t.right = true;
            }
            if ($(el).offset().left < $(parent).offset().left) {
                t.left = true;
            }
            return t;
        },
        SliderNormalize: function (parent) {
            if (parent === null || !parent) {
                return false;
            }
            var el = parent.firstElementChild;
            var a = $(parent).offset().left + $(parent).width();
            var b = $(el).offset().left + $(el).width();
            if (b < a) {
                return (a - b);
            }
            return false;
        },
        SliderNext: function (parent, step) {
            if (parent === null || !parent) {
                return false;
            }
            var el = parent.firstElementChild;
            if ($(parent).width() > $(el).width()) return 0;
            var a = $(parent).offset().left + $(parent).width();
            var b = $(el).offset().left + $(el).width();
            var step = step || $(parent).width();
            var curr = parseFloat(window.getComputedStyle(el, null).left);
            if (a < b) {
                if ((b - step) > a) {
                    return (curr - step);
                }
                else {
                    return curr - (b - a);
                }
            }
            else {
                return curr - (b - a);
            }
        },
        SliderPrev: function (parent, step) {
            if (parent === null || !parent) {
                return false;
            }
            var el = parent.firstElementChild;
            var step = step || $(parent).width();
            var curr = parseFloat(window.getComputedStyle(el, null).left);
            if (curr < 0) {
                if ((curr + step) < 0) {
                    return (curr + step);
                }
                else {
                    return 0;
                }
            }
            else {
                return 0;
            }
        }
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
                $(this.progress).remove();
            },
            set: function (v, action) {
                if (v > 100) {
                    var v = 100;
                }
                if (v < 0) {
                    var v = 0;
                }
                var action = action || this.progress.progressInfo.action;
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
            $(mw.tools._titleTip).hide();
            return false;
        }
        var skin = $(el).attr('data-tipskin');
        var skin = (skin) ? skin : 'mw-tooltip-dark';
        var pos = $(el).attr('data-tipposition');
        var iscircle = $(el).attr('data-tipcircle') == 'true';
        if (!pos) {
            var pos = 'top-center';
        }
        var text = $(el).attr('data-tip');
        if (!text) {
            var text = $(el).attr('title');
        }
        if (!text) {
            var text = $(el).attr('tip');
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
        var showon = $(el).attr('data-showon');
        if (showon) {
            var el = mw.$(showon)[0];
        }
        if (!mw.tools._titleTip) {
            mw.tools._titleTip = mw.tooltip({skin: skin, element: el, position: pos, content: text});
            $(mw.tools._titleTip).addClass('mw-universal-tooltip');
        }
        else {
            mw.tools._titleTip.className = 'mw-tooltip ' + pos + ' ' + skin + ' mw-universal-tooltip';
            mw.$('.mw-tooltip-content', mw.tools._titleTip).html(text);
            mw.tools.tooltip.setPosition(mw.tools._titleTip, el, pos);
        }
        if (iscircle) {
            $(mw.tools._titleTip).addClass('mw-tooltip-circle');
        }
        else {
            $(mw.tools._titleTip).removeClass('mw-tooltip-circle');
        }
        $(mw.tools._titleTip).show();
    },
}
mw.tools.matches('init');
Alert = mw.tools.alert;
mw.wait('jQuery', function () {
    jQuery.fn.getDropdownValue = function () {
        return this.dataset("value");
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
            var method = valel[0].type ? 'val':'html';
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
                $(this).bind("change", function () {
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
String.prototype.toCamelCase = function () {
    return mw.tools.toCamelCase(this);
};
$.fn.datas = function () {
    var attrs = this[0].attributes;
    var toreturn = {}
    for (var item in attrs) {
        var attr = attrs[item];
        if (attr.nodeName !== undefined) {
            if (attr.nodeName.contains("data-")) {
                toreturn[attr.nodeName] = attr.nodeValue;
            }
        }
    }
    return toreturn;
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
    toggle:function (selector) {
        var els = $(selector).find("input[type='checkbox']"), checked = els.filter(':checked');
        if(els.length === checked.length){
            mw.check.none(selector)
        }
        else{
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
mw.walker = function (context, callback) {
    var context = mw.is.obj(context) ? context : mwd.body;
    var callback = mw.is.func(context) ? context : callback;
    var walker = document.createTreeWalker(context, NodeFilter.SHOW_ELEMENT, null, false);
    while (walker.nextNode()) {
        callback.call(walker.currentNode);
    }
}
Array.prototype.remove = Array.prototype.remove || function (what) {
        var i = 0, l = this.length;
        for (; i < l; i++) {
            this[i] === what ? this.splice(i, 1) : '';
        }
    }
Array.prototype.exposeToHash = function (name, callback) {
    if (typeof name === 'undefined') {
        return false;
    }
    mw.on.hashParam(name, function () {
        callback.call(this);
    });
}
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
            var c = '<a href="javascript:;" onclick="mw.tools.modal.init({html: \'<h2>mw.' + a + '</h2>\' + mw._dump(mw.' + a + ')});"> + Object</a>';
        }
        else {
            var c = b.toString()
        }
        html = html + '<li>' + a + ' : ' + c + '</li>';
    });
    html = html + '</ol>';
    return html;
}
mw.dump = function () {
    mw.tools.modal.init({
        html: mw._dump(),
        width: 800
    });
}
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
        var w = $(div).outerWidth();
        $(div).css("marginLeft", -(w / 2));
        setTimeout(function () {
            div.style.opacity = 0;
            setTimeout(function () {
                $(div).remove();
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
}
$.fn.visible = function () {
    return this.css("visibility", "visible");
};
$.fn.visibilityDefault = function () {
    return this.css("visibility", "");
};
$.fn.invisible = function () {
    return this.css("visibility", "hidden");
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
mw.traverse = function (root, h) {
    var els = root.querySelectorAll('.edit .element, .edit .module');
    $(els).each(function () {
        _dis = this;
        var el = mwd.createElement('span');
        el.className = 'layer';
        $(el).data("for", this);
        $(el).click(function () {
            if (!$(el).attr('staticdesign')) {
                $(".element-current").removeClass("element-current");
                $($(el).data("for")).addClass("element-current");
                $(_dis).remove()
            }
        });
        var str = _dis.textContent.slice(0, 25);
        el.innerHTML = $(this).hasClass("module") ? 'Module' : 'Element';
        el.innerHTML += ' - <small>' + str + '...</span>';
        h.appendChild(el);
    });
}
mw.isDragItem = mw.isBlockLevel = function (obj) {
    var items = /^(blockquote|center|dir|fieldset|form|h[1-6]|hr|menu|ul|ol|dl|p|pre|table|div)$/i;
    return items.test(obj.nodeName);
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
            mww.addEventListener('storage', function (e) {
                if (e.key === 'mw') {
                    var _new = JSON.parse(e.newValue);
                    var _old = JSON.parse(e.oldValue);
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
rcss = function () {
    mw.$("link").each(function () {
        var href = this.href;
        this.href = mw.url.set_param('v', mw.random(), href);
    });
}
setVisible = function (e) {
    if (e.type == 'focus') {
        $(mw.tools.firstParentWithClass(e.target, 'mw-dropdown-content')).visible()
    }
    else if (e.type == 'blur') {
        $(mw.tools.firstParentWithClass(e.target, 'mw-dropdown-content')).visibilityDefault()
    }
}
mw.postMsg = function (w, obj) {
    w.postMessage(JSON.stringify(obj), window.location.href);
}
$(document).ready(function () {
    $(mwd.body).bind('mousemove', function (event) {
        if (mw.tools.hasClass(event.target, 'tip')) {
            mw.tools.titleTip(event.target);
        }
        else if (mw.tools.hasParentsWithClass(event.target, 'tip')) {
            mw.tools.titleTip(mw.tools.firstParentWithClass(event.target, 'tip'));
        }
        else {
            $(mw.tools._titleTip).hide();
        }
    });
    mw.$(".mw-onoff").each(function () {
        if (!$(this).hasClass('activated')) {
            $(this).addClass('activated');
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
        var el = $(this), next = el.next();
        mw.$(".wysiwyg-convertible").not(next).removeClass("active");
        mw.$(".wysiwyg-convertible-toggler").not(el).removeClass("active");
        next.toggleClass("active");
        el.toggleClass("active");
        if (el.hasClass("active")) {
            if (typeof mw.liveEditWYSIWYG === 'object') {
                mw.liveEditWYSIWYG.fixConvertible(next);
            }
        }
    });
    mw.$(".mw-dropdown-search").keyup(function (e) {
        if (e.keyCode == '27') {
            $(this.parentNode.parentNode).hide();
        }
        if (e.keyCode != '13' && e.keyCode != '27' && e.keyCode != '32') {
            var el = $(this);
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
    _mwoldww = $(window).width();
    $(window).resize(function () {
        if ($(window).width() > _mwoldww) {
            mw.trigger("increaseWidth");
        }
        else if ($(window).width() < _mwoldww) {
            mw.trigger("decreaseWidth");
        }
        $.noop();
        _mwoldww = $(window).width();
    });
    $(mwd.body).bind("keydown", function (e) {
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
        else{
            if (e.keyCode === 27) {
                var modal = $(".mw_modal:last")[0];
                if(modal) modal.modal.remove();
            }
        }
    });
    mw.$(".mw-pin").each(function () {
        var el = this,
            who = $(el).dataset("for"),
            is = mw.cookie.ui(who) == 'true';
        if (is) {
            mw.tools.addClass(el, 'active');
            var who = mw.$(who);
            who.addClass("active")
        }
        $(el).click(function () {
            if ($(this).hasClass("active")) {
                mw.tools.removeClass(this, 'active');
                var who = $(el).dataset("for");
                mw.cookie.ui(who, "false");
            }
            else {
                mw.tools.addClass(this, 'active');
                var who = $(el).dataset("for");
                mw.cookie.ui(who, "true");
            }
        });
    });
    $(".mw-image-holder").each(function () {
        if ($(".mw-image-holder-overlay", this).length === 0) {
            $('img', this).eq(0).after('<span class="mw-image-holder-overlay"></span>');
        }
    })
});
mw.ui = mw.tools;
mw.ui.btn = {
    radionav: function (nav, btn_selector) {
        if (mw.tools.hasClass(nav.className, 'activated')) {
            return false;
        }
        mw.tools.addClass(nav, 'activated');
        var btn_selector = btn_selector || ".mw-ui-btn";
        var all = nav.querySelectorAll(btn_selector), i = 0, l = all.length, el;
        for (; i < l; i++) {
            var el = all[i];
            $(el).bind('click', function () {
                if (!mw.tools.hasClass(this.className, 'active')) {
                    var active = nav.querySelector(btn_selector + ".active");
                    if (active !== null) {
                        mw.tools.removeClass(active, 'active');
                    }
                    this.className += ' active';
                }
            });
        }
    },
    checkboxnav: function (nav) {
        if (mw.tools.hasClass(nav.className, 'activated')) {
            return false;
        }
        mw.tools.addClass(nav, 'activated');
        var all = nav.querySelectorAll(".mw-ui-btn"), i = 0, l = all.length;
        for (; i < l; i++) {
            var el = all[i];
            $(el).bind('click', function () {
                if (!mw.tools.hasClass(this.className, 'active')) {
                    this.className += ' active';
                }
                else {
                    mw.tools.removeClass(this, 'active');
                }
            });
        }
    }
}
mw.inline = {
    bar: function (id) {
        if (typeof id === 'undefined') {
            return false;
        }
        if (mw.$("#" + id).length === 0) {
            var bar = mwd.createElement('div');
            bar.id = id;
            bar.contentEditable = false;
            bar.className = 'mw-defaults mw-inline-bar';
            mwd.body.appendChild(bar);
            return bar;
        }
        else {
            return mw.$("#" + id)[0];
        }
    },
    tableControl: false,
    tableController: function (el, e) {
        if (typeof e !== 'undefined') {
            e.stopPropagation();
        }
        if (mw.inline.tableControl === false) {
            mw.inline.tableControl = mw.inline.bar('mw-inline-tableControl');
            mw.inline.tableControl.innerHTML = ''
            mw.inline.tableControl.innerHTML = ''
                + '<ul class="mw-ui-box mw-ui-navigation mw-ui-navigation-horizontal">'
                + '<li>'
                + '<a href="javascript:;">Insert<span class="mw-icon-dropdown"></span></a>'
                + '<ul>'
                + '<li><a href="javascript:;" onclick="mw.inline.tableManager.insertRow(\'above\', mw.inline.activeCell);">Row Above</a></li>'
                + '<li><a href="javascript:;" onclick="mw.inline.tableManager.insertRow(\'under\', mw.inline.activeCell);">Row Under</a></li>'
                + '<li><a href="javascript:;" onclick="mw.inline.tableManager.insertColumn(\'left\', mw.inline.activeCell)">Column on left</a></li>'
                + '<li><a href="javascript:;" onclick="mw.inline.tableManager.insertColumn(\'right\', mw.inline.activeCell)">Column on right</a></li>'
                + '</ul>'
                + '</li>'
                + '<li>'
                + '<a href="javascript:;">Style<span class="mw-icon-dropdown"></span></a>'
                + '<ul>'
                + '<li><a href="javascript:;" onclick="mw.inline.tableManager.setStyle(\'mw-wysiwyg-table\', mw.inline.activeCell);">Bordered</a></li>'
                + '<li><a href="javascript:;" onclick="mw.inline.tableManager.setStyle(\'mw-wysiwyg-table-zebra\', mw.inline.activeCell);">Bordered Zebra</a></li>'
                + '<li><a href="javascript:;" onclick="mw.inline.tableManager.setStyle(\'mw-wysiwyg-table-simple\', mw.inline.activeCell);">Simple</a></li>'
                + '<li><a href="javascript:;" onclick="mw.inline.tableManager.setStyle(\'mw-wysiwyg-table-simple-zebra\', mw.inline.activeCell);">Simple Zebra</a></li>'
                + '</ul>'
                + '</li>'
                + '<li>'
                + '<a href="javascript:;">Delete<span class="mw-icon-dropdown"></span></a>'
                + '<ul>'
                + '<li><a href="javascript:;" onclick="mw.inline.tableManager.deleteRow(mw.inline.activeCell);">Row</a></li>'
                + '<li><a href="javascript:;" onclick="mw.inline.tableManager.deleteColumn(mw.inline.activeCell);">Column</a></li>'
                + '</ul>'
                + '</li>'
                + '</ul>';
        }
        var off = $(el).offset();
        $(mw.inline.tableControl).css({
            top: off.top - 45,
            left: off.left,
            display: 'block'
        });
    },
    activeCell: null,
    setActiveCell: function (el, event) {
        if (!mw.tools.hasClass(el.className, 'tc-activecell')) {
            mw.$(".tc-activecell").removeClass('tc-activecell');
            $(el).addClass('tc-activecell');
            mw.inline.activeCell = el;
        }
    },
    tableManager: {
        insertColumn: function (dir, cell) {
            var cell = $(cell)[0];
            if (cell === null) {
                return false;
            }
            var dir = dir || 'right';
            var rows = $(cell.parentNode.parentNode).children('tr');
            var i = 0, l = rows.length, index = mw.tools.index(cell);
            for (; i < l; i++) {
                var row = rows[i];
                var cell = $(row).children('td')[index];
                if (dir == 'left' || dir == 'both') {
                    $(cell).before("<td>&nbsp;</td>");
                }
                if (dir == 'right' || dir == 'both') {
                    $(cell).after("<td>&nbsp;</td>");
                }
            }
        },
        insertRow: function (dir, cell) {
            var cell = $(cell)[0];
            if (cell === null) {
                return false;
            }
            var dir = dir || 'under';
            var parent = cell.parentNode, cells = $(parent).children('td'), i = 0, l = cells.length, html = '';
            for (; i < l; i++) {
                html += '<td>&nbsp;</td>';
            }
            var html = '<tr>' + html + '</tr>';
            if (dir == 'under' || dir == 'both') {
                $(parent).after(html)
            }
            if (dir == 'above' || dir == 'both') {
                $(parent).before(html)
            }
        },
        deleteRow: function (cell) {
            $(cell.parentNode).remove();
        },
        deleteColumn: function (cell) {
            var index = mw.tools.index(cell), body = cell.parentNode.parentNode, rows = $(body).children('tr'), l = rows.length, i = 0;
            for (; i < l; i++) {
                var row = rows[i];
                $(row.getElementsByTagName('td')[index]).remove();
            }
        },
        setStyle: function (cls, cell) {
            var table = mw.tools.firstParentWithTag(cell, 'table');
            mw.tools.classNamespaceDelete(table, 'mw-wysiwyg-table');
            $(table).addClass(cls);
        }
    }
}
mw.dynamicCSS = {
    previewOne: function (selector, value) {
        if (mwd.getElementById('user_css') === null) {
            var style = mwd.createElement('style');
            style.type = 'text/css';
            style.id = 'user_css';
            mwd.head.appendChild(style);
        }
        else {
            var style = mwd.getElementById('user_css');
        }
        var css = selector + '{' + value + "}";
        style.appendChild(document.createTextNode(css));
    },
    manageObject: function (main_obj, selector_obj) {
    }
}
mw.css3fx = {
    perspective: function (a) {
        if (typeof mw.current_element === 'undefined') return false;
        var el = mw.current_element;
        var val = "perspective( " + $(el).width() + "px ) rotateY( " + a + "deg )";
        el.style[mw.JSPrefix('transform')] = val;
        $(el).addClass("mwfx");
        mw.css3fx.set_obj(el, "transform", val);
    },
    rotate: function (a) {
        if (typeof mw.current_element === 'undefined') return false;
        var el = mw.current_element;
        var val = "matrix(" + Math.cos(a) + "," + Math.sin(a) + "," + -Math.sin(a) + "," + Math.cos(a) + ", 0, 0)";
        el.style[mw.JSPrefix('transform')] = val;
        $(el).addClass("mwfx");
        mw.css3fx.set_obj(el, "transform", val);
    },
    set_obj: function (element, option, value) {
        if (typeof element.attributes["data-mwfx"] !== 'undefined') {
            var json = mw.css3fx.read(element);
            json[option] = value;
            var string = JSON.stringify(json);
            var string = string.replace(/{/g, "").replace(/}/g);
            var string = string.replace(/"/g, "XX");
            $(element).dataset("mwfx", string);
        }
        else {
            $(element).dataset("mwfx", "XX" + option + "XX:XX" + value + "XX");
        }
    },
    init_css: function (el) {
        var el = el || ".mwfx";
        $(el).each(function () {
            var elem = this;
            var json = mw.css3fx.read(el);
            $.each(json, function (a, b) {
                $(elem).css(mw.JSPrefix(a), b);
            });
        });
    },
    read: function (el) {
        var el = $(el);
        var attr = el.dataset("mwfx");
        if (typeof attr !== 'undefined') {
            var attr = attr.replace(/XX/g, '"');
            var attr = attr.replace(/undefined/g, '');
            var json = $.parseJSON('{' + attr + '}');
            return json;
        }
        else {
            return false;
        }
    }
}
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
                mw.image_resizer = resizer;
                $(resizer).on("click", function (e) {
                    mw.wysiwyg.select_element(mw.image.currentResizing[0])
                });
                $(resizer).on("dblclick", function (e) {
                    mw.wysiwyg.media('#editimage');
                });
            }
        },
        prepare: function () {
            mw.image.resize.create_resizer();
            $(mw.image_resizer).resizable({
                handles: "all",
                minWidth: 60,
                minHeight: 60,
                start: function () {
                    mw.image.isResizing = true;
                    $(mw.image_resizer).resizable("option", "maxWidth", mw.image.currentResizing.parent().width());
                    $(mw.tools.firstParentWithClass(mw.image.currentResizing[0], 'edit')).addClass("changed");
                },
                stop: function () {
                    mw.image.isResizing = false;
                    mw.drag.fix_placeholders();
                },
                resize: function () {
                    var offset = mw.image.currentResizing.offset();
                    $(this).css(offset);
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


            $('.ui-resizable-handle', mw.image_resizer)[el.nodeName == 'IMG'?'show':'hide']()

            var el = $(el);
            var offset = el.offset();
            var parentOffset = el.parent().offset();
            offset.top = offset.top < parentOffset.top ? parentOffset.top : offset.top;
            offset.left = offset.left < parentOffset.left ? parentOffset.left : offset.left;
            var r = $(mw.image_resizer);
            var width = el.outerWidth();
            var height = el.outerHeight();
            r.css({
                left: offset.left,
                top: offset.top,
                width: width,
                height: mw.tools.hasParentsWithClass(el[0], 'mw-image-holder') ? 1 : height
            });
            r.addClass("active");
            $(mw.image_resizer).resizable("option", "alsoResize", el);
            $(mw.image_resizer).resizable("option", "aspectRatio", width / height);
            mw.image.currentResizing = el;
            el[0].contentEditable = true;
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
                    mwd.getElementById('image-settings-button').style.display = 'inline-block';
                }
            }
            /* } */
        },
        init: function (selector) {
            mw.image_resizer == undefined ? mw.image.resize.prepare() : '';
            /*
             mw.$(".element-image").each(function(){
             var img = this.getElementsByTagName('img')[0];
             this.style.width = $(img).width()+'px';
             this.style.height = $(img).height()+'px';
             });     */
            mw.on("ImageClick", function (e, el) {
                if (!mw.image.isResizing && !mw.isDrag && !mw.settings.resize_started && el.tagName == 'IMG') {
                    mw.image.resize.resizerSet(el);
                }
            })
        }
    },
    linkIt: function (img_object) {
        var img_object = img_object || mwd.querySelector("img.element-current");
        if (img_object.parentNode.tagName === 'A') {
            $(img_object.parentNode).replaceWith(img_object);
        }
        else {
            mw.tools.modal.frame({
                url: "rte_link_editor#image_link",
                title: "Add/Edit Link",
                name: "mw_rte_link",
                width: 340,
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
            var img_object = img_object || mwd.querySelector("img.element-current");
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
                var image = $(this);
                var w = image.width();
                var h = image.height();
                var contextWidth = w
                var contextHeight = h
                var x = 0;
                var y = 0;
                switch (angle) {
                    case 90:
                        var contextWidth = h;
                        var contextHeight = w;
                        var y = -h;
                        break;
                    case 180:
                        var x = -w;
                        var y = -h;
                        break;
                    case 270:
                        var contextWidth = h;
                        var contextHeight = w;
                        var x = -w;
                        break;
                    default:
                        var contextWidth = h;
                        var contextHeight = w;
                        var y = -h;
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
            canvas.width = $(this).width();
            canvas.height = $(this).height();
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
            $(canvas).remove()
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
                $(img).remove();
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
            var area = $(id);
            area.hover(function () {
                area.addClass("desc_area_hover");
            }, function () {
                area.removeClass("desc_area_hover");
            });
            var curr = mw.image.description.get();
            if (!area.hasClass("inited")) {
                area.addClass("inited");
                area.bind("keyup change paste", function () {
                    var val = $(this).val();
                    mw.image.description.add(val);
                });
            }
            area.val(curr);
            area.show();
        }
    },
    settings: function () {
        var modal = mw.tools.modal.frame({
            url: 'imageeditor',
            template: "mw_modal_basic",
            overlay: true,
            width: '600',
            height: "80%",
            name: 'mw-image-settings-modal'
        });
        modal.overlay.style.backgroundColor = 'white';
        $(modal.main).css('max-height', 600);
    }
}
mw.module = {
    load: function () {
    },
    reload: function () {
    },
    loadData: function () {
    }
}
/* Exposing to mw  */
mw.gallery = function (arr, start, modal) {
    if (self === top || window == window) {
        return mw.tools.gallery.init(arr, start, modal)
    }
};
mw.tooltip = mw.tools.tip;
mw.tip = function (o) {
    var tip = mw.tooltip(o);
    var obj = {
        tip: tip,
        element: o.element,
        hide: function () {
            $(tip).hide()
        },
        show: function () {
            $(tip).show()
        },
        remove: function () {
            $(tip).remove()
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
}
mw.dropdown = mw.tools.dropdown;
mw.confirm = mw.tools.confirm;
mw.tabs = mw.tools.tabGroup;
mw.inlineModal = mw.tools.inlineModal;
mw.progress = mw.tools.progress;
mw.external = function (o) {
    return mw.tools._external(o)
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
}
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
    position: 'bottom-center',
    skin: 'mw-tooltip-default',
    position: 'bottom-center',
    onchange: false
}
mw._colorPicker = function (options) {
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
    var frame = document.createElement('iframe');
    frame.src = mw.external_tool('color_picker');
    frame.frameborder = 0;
    frame.className = 'mw-picker-frame';
    frame.frameBorder = 0;
    frame.scrolling = "no";
    $(frame).bind("colorChange", function (e, val) {
        options.onchange('#' + val);
        if ($el[0].nodeName == 'INPUT') {
            var val = val == 'transparent' ? val : '#' + val;
            $el.val(val);
        }
    });
    if (settings.method == 'inline') {
        $el.empty().append(frame);
        return false;
    }
    else {
        var tip = mw.tooltip(settings), $tip = $(tip).hide();
        this.tip = tip;
        mw.$('.mw-tooltip-content', tip).empty().append(frame);
        if ($el[0].nodeName == 'INPUT') {
            $el.bind('focus', function (e) {
                $(tip).show();
                mw.tools.tooltip.setPosition(tip, $el[0], settings.position)
            });
        }
        else {
            $el.bind('click', function (e) {
                $(tip).toggle();
                mw.tools.tooltip.setPosition(tip, $el[0], settings.position)
            });
        }
        $(document.body).bind('click', function (e) {
            if (!mw.tools.isEventOnElements(e, [$el[0], tip])) {
                $(tip).hide();
            }
        });
        if ($el[0].nodeName == 'INPUT') {
            $el.bind('blur', function () {
                $(tip).hide();
            });
        }
    }
    this.show = function(){
        $(this.tip).show();
        mw.tools.tooltip.setPosition(this.tip, this.settings.element, this.settings.position)
    }
    this.hide = function(){
        $(this.tip).hide()
    }
}
mw.colorPicker = mw.colourPicker = function (o) {
    return new mw._colorPicker(o);
}
mw.accordion = function (el, callback) {
    return mw.tools.accordion(mw.$(el)[0], callback);
}
$.fn.timeoutHover = function (ce, cl, time1, time2) {
    var time1 = time1 || 350;
    var time2 = time2 || time1;
    return this.each(function () {
        var el = this;
        el.timeoutOver = null;
        el.timeoutLeave = null;
        el.originalOver = false;
        $(el).hover(function () {
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
    $(mwd.body).ajaxStop(function () {
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
        $(selector).each(function () {
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
            var el = $(this);
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
                $(window).on('resize oientationchange', function () {
                    $('tr', el).each(function () {
                        var max = 0;
                        $('td', this).height('auto').each(function () {
                            var h = $(this).outerHeight();
                            if (h > max) {
                                max = h
                            }
                        })
                            .height(max)
                    })
                })
                css.innerHTML = html
            }
            el.prepend(css);
            if (!el.hasClass('mw-mobile-table')) {
                el.addClass('mw-mobile-table');
                var th = $('th', this)
                $('tr', this).each(function () {
                    $('td', this).each(function (i) {
                        $(this).prepend('<span class="th-in-td">' + th.eq(i).html() + '</span>');
                    });
                })
            }
        });
    }
}
String.prototype.hash = function () {
    var self = this, range = Array(this.length);
    for (var i = 0; i < this.length; i++) {
        range[i] = i;
    }
    return Array.prototype.map.call(range, function (i) {
        return self.charCodeAt(i).toString(16);
    }).join('');
}

mw.ajax = function (options) {
    options._success = options.success;
    delete options.success;
    options.success = function(data,status,xhr){
        if(data.form_data_required){
            mw.extradataForm(options, data);
        }
        else{
            options._success.call(this, data, status, xhr);
        }
    };
    var xhr = $.ajax(options);
    return xhr;
};

mw.getExtradataFormData = function (data, call) {
    if(data.form_data_module){
        mw.loadModuleData(data.form_data_module, function(a){
            call.call(undefined, data);
        })
    }
    else{
        call.call(undefined, data.form_data_required);
    }

}
mw.extradataForm = function (options, data) {
    mw.getExtradataFormData(data, function (extra_html) {
        var form = document.createElement('form');
        form.innerHTML = extra_html + '<hr><button type="submit">'+mw.lang('Submit')+'</button>';
        form.action = options.url;
        form.method = options.type;

        form.__modal = mw.modal({
            content:form
        });
        $(form).on('submit', function (e) {
            e.preventDefault();
            var exdata = mw.serializeFields(this);
            for(var i in exdata){
                data[i] = exdata[i];
            };
            var cdata = $.extend({}, options.data, data);
            options.data = cdata;
            $[this.method](this.action, data);
            mw.ajax(options);
        })
    });
};
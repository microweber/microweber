/*

Deprecated:

Use mw.dialog() instead


*/

(function(){
    var modal = {
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
            mw.require('css_parser.js')

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

    };
    mw.tools.modal = modal;
})();


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
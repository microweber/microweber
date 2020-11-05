/******/ (() => { // webpackBootstrap
(() => {
/*!*************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/checkbox.js ***!
  \*************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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
})();

(() => {
/*!****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/colorpicker.js ***!
  \****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw._colorPickerDefaults = {
    skin: 'mw-tooltip-default',
    position: 'bottom-center',
    onchange: false
};

mw._colorPicker = function (options) {
    mw.lib.require('colorpicker');
    if (!mw.tools.colorPickerColors) {
        mw.tools.colorPickerColors = [];

        // var colorpicker_els = mw.top().$("body *");
        // if(colorpicker_els.length > 0){
        //     colorpicker_els.each(function () {
        //         var css = parent.getComputedStyle(this, null);
        //         if (css !== null) {
        //             if (mw.tools.colorPickerColors.indexOf(css.color) === -1) {
        //                 mw.tools.colorPickerColors.push(mw.color.rgbToHex(css.color));
        //             }
        //             if (mw.tools.colorPickerColors.indexOf(css.backgroundColor) === -1) {
        //                 mw.tools.colorPickerColors.push(mw.color.rgbToHex(css.backgroundColor));
        //             }
        //         }
        //     });
        // }

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
    if($el[0].mwcolorPicker) {
        return $el[0].mwcolorPicker;
    }


    $el[0].mwcolorPicker = this;
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
    if (settings.method === 'inline') {

        sett.attachTo = $el[0];

        frame = AColorPicker.createPicker(sett);
        frame.onchange = function (data) {

            if (proto.settings.onchange) {
                proto.settings.onchange(data.color);
            }

            if ($el[0].nodeName === 'INPUT') {
                var val = val === 'transparent' ? val : '#' + val;
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

            if(frame.pause) {
                return;
            }

            if (proto.settings.onchange) {
                proto.settings.onchange(data.color);
            }

            if ($el[0].nodeName === 'INPUT') {
                $el.val(data.color);
            }
        };
        if ($el[0].nodeName === 'INPUT') {
            $el.on('focus', function (e) {
                if(this.value.trim()){
                    frame.pause = true;
                    frame.color = this.value;
                    setTimeout(function () {
                        frame.pause = false;
                    });
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
mw.colorPicker = function (o) {

    return new mw._colorPicker(o);
};

})();

(() => {
/*!******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/common-extend.js ***!
  \******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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


Array.prototype.remove = Array.prototype.remove || function (what) {
    var i = 0, l = this.length;
    for ( ; i < l; i++) {
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

    mw.require("files.js");

    var uploader = mw.files.uploader(o);

    return uploader;
};

mw.fileWindow = function (config) {
    config = config || {};
    config.mode = config.mode || 'dialog'; // 'inline' | 'dialog'
    var q = {
        types: config.types,
        title: config.title
    };


    url = mw.settings.site_url + 'editor_tools/rte_image_editor?' + $.param(q) + '#fileWindow';
    var frameWindow;
    var toreturn = {
        dialog: null,
        root: null,
        iframe: null
    };
    if (config.mode === 'dialog') {
        var modal = mw/*.top()*/.dialogIframe({
            url: url,
            name: "mw_rte_image",
            width: 530,
            height: 'auto',
            autoHeight: true,
            //template: 'mw_modal_basic',
            overlay: true,
            title: mw.lang('Select image')
        });
        var frame = mw.$('iframe', modal.main);
        frameWindow = frame[0].contentWindow;
        toreturn.dialog = modal;
        toreturn.root = frame.parent()[0];
        toreturn.iframe = frame[0];
        frameWindow.onload = function () {
            frameWindow.$('body').on('Result', function (e, url, m) {
                 if (config.change) {
                    config.change.call(undefined, url);
                    modal.remove();
                }
            });
            $(modal).on('Result', function (e, url, m) {
                console.log(9999)
                if (config.change) {
                    config.change.call(undefined, url);
                    modal.remove();
                }
            });
        };
    } else if (config.mode === 'inline') {
        var fr = document.createElement('iframe');
        fr.src = url;
        fr.frameBorder = 0;
        fr.className = 'mw-file-window-frame';
        toreturn.iframe = fr;
        mw.tools.iframeAutoHeight(fr);
        if (config.element) {
            var $el = $(config.element);
            if($el.length) {
                toreturn.root = $el[0];
            }
            $el.append(fr);
        }
        fr.onload = function () {
            this.contentWindow.$('body').on('change', function (e, url, m) {
                if (config.change) {
                    config.change.call(undefined, url);
                }
            });
        };
    }


    return toreturn;
};




mw.accordion = function (el, callback) {
    return mw.tools.accordion(mw.$(el)[0], callback);
};


})();

(() => {
/*!***********************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/common.js ***!
  \***********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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
    mw.on('mwDialogShow', function(){
        mw.$(document.documentElement).addClass('mw-dialog-opened');
    });
    mw.on('mwDialogHide', function(){
        mw.$(document.documentElement).removeClass('mw-dialog-opened');
    });
    mw.$(mwd.body).on('mousemove touchmove touchstart', function (event) {
        var has = mw.tools.firstParentOrCurrentWithClass(event.target, 'tip');
        if (has && (!has.dataset.trigger || has.dataset.trigger === 'move')) {
            mw.tools.titleTip(has);
        }
        else {
            mw.$(mw.tools._titleTip).hide();
        }
    }).on('click', function (event) {
        var has = mw.tools.firstParentOrCurrentWithClass(event.target, 'tip');
        if (has && has.dataset.trigger === 'click') {
            mw.tools.titleTip(has, '_titleTipClick');
        }
        else {
            mw.$(mw.tools._titleTipClick).hide();
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
    mw.$(mwd.body).on("keydown", function (e) {
        var isgal = mwd.querySelector('.mw_modal_gallery') !== null;
        if (isgal) {
            if (e.keyCode === 27) {  /* escape */
                mw.dialog.remove(mw.$(".mw_modal_gallery"))
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

})();

(() => {
/*!***********************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/cookie.js ***!
  \***********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.cookie = {
    get: function (name) {
        var cookies = mwd.cookie.split(";"), i = 0, l = cookies.length;
        for (; i < l; i++) {
            var x = cookies[i].substr(0, cookies[i].indexOf("="));
            var y = cookies[i].substr(cookies[i].indexOf("=") + 1);
            x = x.replace(/^\s+|\s+$/g, "");
            if (x === name) {
                return unescape(y);
            }
        }
    },
    set: function (name, value, expires, path, domain, secure) {
        var now = new Date();
        expires = expires || 365;
        now.setTime(now.getTime());
        if (expires) {
            expires = expires * 1000 * 60 * 60 * 24;
        }
        var expires_date = new Date(now.getTime() + (expires));
        document.cookie = name + "=" + escape(value) + ( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) + ( ( path ) ? ";path=" + path : ";path=/" ) + ( ( domain ) ? ";domain=" + domain : "" ) + ( ( secure ) ? ";secure" : "" );
    },
    setEncoded: function (name, value, expires, path, domain, secure) {

        value = mw.tools.base64.encode(value);
        return this.set(name, value, expires, path, domain, secure);
    },
    getEncoded: function (name) {
        var value = this.get(name);

        value = mw.tools.base64.decode(value);
        return value;
    },
    ui: function (a, b) {
        var mwui = mw.cookie.getEncoded("mwui");
        try {
            mwui = (!mwui || mwui === '') ? {} : $.parseJSON(mwui);
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
};

})();

(() => {
/*!***************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/domhelpers.js ***!
  \***************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
(function(){
var domHelp = {
    classNamespaceDelete: function (el_obj, namespace, parent, namespacePosition, exception) {
        if (el_obj.element && el_obj.namespace) {
            el = el_obj.element;
            namespace = el_obj.namespace;
            parent = el_obj.parent;
            namespacePosition = el_obj.namespacePosition;
            exceptions = el_obj.exceptions || [];
        }
        else {
            el = el_obj;
            exceptions = [];
        }
        namespacePosition = namespacePosition || 'contains';
        parent = parent || mwd;
        if (el === 'all') {
            var all = parent.querySelectorAll('.edit *'), i = 0, l = all.length;
            for (; i < l; i++) {
                mw.tools.classNamespaceDelete(all[i], namespace, parent, namespacePosition)
            }
            return;
        }
        if (!!el.className && typeof(el.className.split) === 'function') {
            var cls = el.className.split(" "), l = cls.length, i = 0, final = [];
            for (; i < l; i++) {
                if (namespacePosition === 'contains') {
                    if (!cls[i].contains(namespace) || exceptions.indexOf(cls[i]) !== -1) {
                        final.push(cls[i]);
                    }
                }
                else if (namespacePosition === 'starts') {
                    if (cls[i].indexOf(namespace) !== 0) {
                        final.push(cls[i]);
                    }
                }
            }
            el.className = final.join(" ");
        }
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
        while (curr && curr !== document.body) {
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
            if(mw.tools.isBlockLevel(el)) {
                return el;
            }
            el = el.parentNode;
        }
    },
    firstNotInlineLevel: function (el) {
        if(el.nodeType !== 1) {
            el = el.parentNode
        }
        if(!el) {
            return;
        }
        while(el && el !== document.body) {
            if(!mw.tools.isInlineLevel(el)) {
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
        if (!el || !tag) return;
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
    firstParentOrCurrentWithTag: function (el, tag) {
        if (!el || !tag) return;
        tag = typeof tag !== 'string' ? tag : [tag];
        var curr = el;
        while (curr && curr !== mwd.body) {
            if (tag.indexOf(curr.nodeName.toLowerCase()) !== -1) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    generateSelectorForNode: function (node) {
        if (node === null || node.nodeType === 3) {
            return false;
        }
        if (node.nodeName === 'BODY') {
            return 'body';
        }
        if (!!node.id /*&& node.id.indexOf('element_') === -1*/) {
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
            return item !== 'changed'
                && item !== 'module-over'
                && item !== 'mw-bg-mask'
                && item !== 'element-current';
        };
        var _final = node.className.trim() ? '.' + node.className.trim().split(' ').filter(filter).join('.') : node.nodeName.toLocaleLowerCase();


        _final = _final.replace(/\.\./g, '.');
        mw.tools.foreachParents(node, function (loop) {
            if (this.id /*&& node.id.indexOf('element_') === -1*/) {
                _final = '#' + this.id + ' > ' + _final;
                mw.tools.stopLoop(loop);
                return false;
            }
            var n;
            if (this.className.trim()) {
                n = this.nodeName.toLocaleLowerCase() + '.' + this.className.trim().split(' ').join('.');
            }
            else {
                n = this.nodeName.toLocaleLowerCase();
            }
            _final = n + ' > ' + _final;
        });
        return _final
            .replace(/.changed/g, '')
            .replace(/.element-current/g, '')
            .replace(/.module-over/g, '');
    }
};

for (var i in domHelp) {
    mw.tools[i] = domHelp[i];
}
})();

})();

(() => {
/*!*************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/dropdown.js ***!
  \*************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.tools.dropdown = function (root) {
    root = root || mwd.body;
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
                if (e.keyCode === 13) {
                    e.preventDefault();
                    mw.$(this.dropdown).removeClass("active");
                    mw.$('.mw-dropdown-content', this.dropdown).hide();
                    mw.$(this.dropdown).setDropdownValue(this.value, true, true);
                    return false;
                }
            };

            input.onkeyup = function (e) {
                if (e.keyCode === 13) {
                    return false;
                }
            }
        }

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
                if (mw.$(".other-action-hover", this).length === 0) {
                    var item = mw.$(".mw-dropdown-content", this);
                    if (item.is(":visible")) {
                        item.hide();
                        item.focus();
                    }
                    else {
                        item.show();
                        if (event.target.type !== 'text') {
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
        mw.$(mwd.body).on('mousedown touchstart', function (e) {
            if (!mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['mw-dropdown-content', 'mw-dropdown'])) {
                mw.$(".mw-dropdown").removeClass("active");
                mw.$(".mw-dropdown-content").hide();
                if(self !== top) {
                    try {
                        mw.top().$(".mw-dropdown").removeClass("active");
                        mw.top().$(".mw-dropdown-content").hide();
                    } catch(e){

                    }
                }
            }
        });
    }
};


mw.dropdown = mw.tools.dropdown;

})();

(() => {
/*!*******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/extradata-form.js ***!
  \*******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.getExtradataFormData = function (data, call) {

    if (data.form_data_required) {
        if (!data.form_data_module_params) {
            data.form_data_module_params = {};
        }
        data.form_data_module_params._confirm = 1
    }


    if (data.form_data_required_params) {
        data.form_data_module_params = $.extend({}, data.form_data_required_params,data.form_data_module_params);
    }

    if (data.form_data_module) {
        mw.loadModuleData(data.form_data_module, function (moduledata) {
            call.call(undefined, moduledata);
        }, null, data.form_data_module_params);
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
            title: data.error,
            closeButton: false,
            closeOnEscape: false
        });
        mw.$('script', form).each(function() {
            eval($(this).text());
        });

        $(form.__modal).on('closedByUser', function () {
            if(options.onExternalDataDialogClose) {
                options.onExternalDataDialogClose.call();
            }
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

                if(options.data.captcha){
                   // mw.top().
                   // ('data-captcha-value')
                }

                mw.ajax(options);
                form.__modal.remove();
            });
        }
    });
};

})();

(() => {
/*!***************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/fullscreen.js ***!
  \***************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
(function(expose){
   var fullscreen = {
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
    }
   };
    Object.assign(expose, fullscreen);

})(mw.tools);

})();

(() => {
/*!************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/helpers.js ***!
  \************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
(function(expose){
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
            var tempInput = document.createElement("input");
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
            mw.require('css_parser.js');
            var speed = 200;
            var container = el.querySelector('.mw-accordion-content');
            if (container === null) return false;
            var is_hidden = mw.CSSParser(container).get.display() === 'none';
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
            var el = mwd.createElement(tag);
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

})();

(() => {
/*!*****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/iframe-tools.js ***!
  \*****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.tools.createAutoHeight = function() {
    if(window.thismodal && thismodal.iframe) {
        mw.tools.iframeAutoHeight(thismodal.iframe);
    }
    else if(mw.top().win.frameElement && mw.top().win.frameElement.contentWindow === window) {
        mw.tools.iframeAutoHeight(mw.top().win.frameElement);
    } else if(window.top !== window) {
        mw.top().$('iframe').each(function(){
            try{
                if(this.contentWindow === window) {
                    mw.tools.iframeAutoHeight(this);
                }
            } catch(e){}
        })
    }
};

mw.tools.moduleFrame = function(type, template){
    return mw.dialogIframe({
        url: mw.external_tool('module_dialog') + '?module=' + type + (template ? ('&template=' + template) : ''),
        width: 532,
        height: 'auto',
        autoHeight:true,
        title: type,
        className: 'mw-dialog-module-settings',
        closeButtonAction: 'remove'
    });
};


mw.tools.iframeAutoHeight = function(frame, opt){

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
    var offset = function () {
        return _detector.getBoundingClientRect().top;
    };
    frame._intPause = false;
    frame._int = setInterval(function(){
        if(!frame._intPause && frame.parentNode && frame.contentWindow ){
            var calc = offset() + _detector.offsetHeight;
            frame._currHeight = frame._currHeight || 0;
            if(calc && calc !== frame._currHeight ){
                frame._currHeight = calc;
                frame.style.height = Math.max(calc) + 'px';
                var scroll = Math.max(frame.contentWindow.document.documentElement.scrollHeight, frame.contentWindow.document.body.scrollHeight);
                if(scroll > frame._currHeight) {
                    frame._currHeight = scroll;
                    frame.style.height = scroll + 'px';
                }
                mw.$(frame).trigger('bodyResize');
            }
        }
        else {
            //clearInterval(frame._int);
        }
    }, 77);

};

})();

(() => {
/*!***********************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/images.js ***!
  \***********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.image = {
    isResizing: false,
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
        var img = mwd.createElement('img');
        img.className = 'semi_hidden';
        img.src = url;
        img.onload = function () {
            setTimeout(function () {
                if (typeof callback === 'function') {
                    callback.call(img, img.naturalWidth, img.naturalHeight);
                }
                mw.$(img).remove();
            }, 33);
        };
        img.onerror = function () {
            setTimeout(function () {
                if (typeof callback === 'function') {
                    callback.call(img, 0, 0, 'error');
                }
            }, 33);
        };
        mwd.body.appendChild(img);
    },
    settings: function () {
        return mw.dialogIframe({
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

})();

(() => {
/*!*****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/jquery.tools.js ***!
  \*****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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


    $.fn.getDropdownValue = function () {
        return this.dataset("value") || mw.$('.active', this).attr('value');
    };
    $.fn.setDropdownValue = function (val, triggerChange, isCustom, customValueToDisplay) {

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
                    var html = !!this.getElementsByTagName('a')[0] ? this.getElementsByTagName('a')[0].textContent : this.innerText;
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
    $.fn.commuter = function (a, b) {
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
    var settings = $.extend({}, defaults, options, conf, {closeButtonAction: 'remove'});
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

})();

(() => {
/*!**********************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/loops.js ***!
  \**********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
(function(){
    var loopTools = {
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
                    var caller = callback.call(_curr, index, count);
                    _curr = _curr.parentNode;
                    if (caller === false || _curr === null || _curr === undefined || !mw.tools.loop[index]) {
                        delete mw.tools.loop[index];
                        break;
                    }
                    _tag = _curr.tagName;
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
        }
    };
    Object.assign(mw.tools, loopTools);
})();

})();

(() => {
/*!************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/native-notification.js ***!
  \************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.tools.notification = function (body, tag, icon) {
    if (!body) return;
    var n = window.Notification || window.webkitNotification || window.mozNotification;
    if (typeof n === 'undefined') {
        return false;
    }
    if (n.permission === 'granted') {
        new n("MW Update", {
            tag: tag || "Microweber",
            body: body,
            icon: icon || mw.settings.includes_url + "img/logomark.png"
        });
    }
    else if (n.permission === 'default') {
        n.requestPermission(function (result) {

            if (result === 'granted') {
                return mw.tools.notification(body, tag, icon)
            }
            else if (result === 'denied') {
                mw.notification.error('Notifications are blocked')
            }
            else if (result === 'default') {
                mw.notification.warn('Notifications are canceled')

            }
        });
    }
}

})();

(() => {
/*!*****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/notification.js ***!
  \*****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.notification = {
    msg: function (data, timeout, alert) {
        timeout = timeout || 1000;
        alert = alert || false;
        if (data) {
            if (data.success) {
                if (alert) {
                    mw.notification.success(data.success, timeout);
                }
                else {
                    Alert(data.success);
                }
            }
            if (data.error) {
                mw.notification.error(data.error, timeout);
            }
            if (data.warning) {
                mw.notification.warning(data.warning, timeout);
            }
        }
    },
    build: function (type, text, name) {
        var div = mwd.createElement('div');
        div.id = name;
        div.className = 'mw-notification mw-' + type;
        div.innerHTML = '<div>' + text + '</div>';
        return div;
    },
    append: function (type, text, timeout, name) {
        if(typeof type === 'object') {
            text = type.text;
            timeout = type.timeout;
            name = type.name;
            type = type.type;
        }
        name = name || mw.id();
        name = 'mw-notification-id-' + name;
        if(document.getElementById(name)) {
            return;
        }
        timeout = timeout || 1000;
        var div = mw.notification.build(type, text, name);
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
    success: function (text, timeout, name) {
        if ( typeof text === 'object' ) {
            timeout = text.timeout;
            name = text.name;
            text = text.text;
        }
        timeout = timeout || 1000;
        mw.notification.append('success', text, timeout, name);
    },
    error: function (text, timeout, name) {
        if ( typeof text === 'object' ) {
            timeout = text.timeout;
            name = text.name;
            text = text.text;
        }
        timeout = timeout || 1000;
        mw.notification.append('error', text, timeout, name);
    },
    warning: function (text, timeout, name) {
        if ( typeof text === 'object' ) {
            timeout = text.timeout;
            name = text.name;
            text = text.text;
        }
        timeout = timeout || 1000;
        mw.notification.append('warning', text, timeout, name);
    }
};

})();

(() => {
/*!************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/objects.js ***!
  \************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.object = {
    extend: function () {
        var extended = {};
        var deep = false;
        var i = 0;
        var l = arguments.length;

        if ( Object.prototype.toString.call( arguments[0] ) === '[object Boolean]' ) {
            deep = arguments[0];
            i++;
        }
        var merge = function (obj) {
            for ( var prop in obj ) {
                if ( Object.prototype.hasOwnProperty.call( obj, prop ) ) {
                    if ( deep && Object.prototype.toString.call(obj[prop]) === '[object Object]' ) {
                        extended[prop] = mw.object.extend( true, extended[prop], obj[prop] );
                    } else {
                        extended[prop] = obj[prop];
                    }
                }
            }
        };
        for ( ; i < l; i++ ) {
            var obj = arguments[i];
            merge(obj);
        }
        return extended;

    }
};

})();

(() => {
/*!************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/storage.js ***!
  \************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.storage = {
    init: function () {
        if (window.location.href.indexOf('data:') === 0 || !('localStorage' in mww) || /* IE Security configurations */ typeof mww['localStorage'] === 'undefined') return false;
        var lsmw = localStorage.getItem("mw");
        if (typeof lsmw === 'undefined' || lsmw === null) {
            lsmw = localStorage.setItem("mw", "{}");
        }
        this.change("INIT");
        return lsmw;
    },
    set: function (key, val) {
        if (!('localStorage' in mww)) return false;
        var curr = JSON.parse(localStorage.getItem("mw"));
        curr[key] = val;
        var a = localStorage.setItem("mw", JSON.stringify(curr));
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
};
mw.storage.init();

})();

(() => {
/*!*******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/system-dialogs.js ***!
  \*******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.tools.alert = function (text) {
    var html = ''
        + '<table class="mw_alert" width="100%" height="140" cellpadding="0" cellspacing="0">'
        + '<tr>'
        + '<td align="center" valign="middle"><div class="mw_alert_holder">' + text + '</div></td>'
        + '</tr>'
        + '<tr>'
        + '<td align="center" height="25"><span class="mw-ui-btn mw-ui-btn-medium" onclick="mw.dialog.remove(this);"><b>' + mw.msg.ok + '</b></span></td>'
        + '</tr>'
        + '</table>';
    if (mw.$("#mw_alert").length === 0) {
        return mw.dialog({
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
};


mw.tools.prompt = function (question, callback) {
    if(!question) return ;
    var id = mw.id('mw-prompt-input');
    question = '<label class="mw-ui-label">'+question+'</label><input class="mw-ui-field w100" id="'+id+'">';
    var dialog = mw.tools.confirm(question, function (){
        callback.call(window, mw.$('#' + id).val());
    });
    setTimeout(function (){
        mw.$('#' + id).focus().on('keydown', function (e) {
            if (mw.event.is.enter(e)) {
                callback.call(window, mw.$('#' + id).val());
                dialog.remove();
            }
        });
    }, 222);
    return dialog;
};
mw.tools.confirm = function (question, callback) {
    if(typeof question === 'function') {
        callback = question;
        question = 'Are you sure?';
    }
    question = question || 'Are you sure?';
        var html = ''
            + '<table class="mw_alert" width="100%" height="140" cellpadding="0" cellspacing="0">'
            + '<tr>'
            + '<td valign="middle"><div class="mw_alert_holder">' + question + '</div></td>'
            + '</tr>'
            + '</table>';

        var ok = mw.top().$('<span tabindex="99999" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info">' + mw.msg.ok + '</span>');
        var cancel = mw.top().$('<span class="mw-ui-btn mw-ui-btn-medium ">' + mw.msg.cancel + '</span>');
        var modal;

        if (mw.$("#mw_confirm_modal").length === 0) {
            modal = mw.top().dialog({
                content: html,
                width: 400,
                height: 'auto',
                autoHeight: true,
                overlay: false,
                name: "mw_confirm_modal",
                footer: [cancel, ok],
                title: mw.lang('Confirm')
            });
        }
        else {
            mw.$("#mw_confirm_modal .mw_alert_holder").html(question);
            modal = mw.$("#mw_confirm_modal")[0].modal;
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
    };

})();

(() => {
/*!*********************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/tabs.js ***!
  \*********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.tabs = function (obj, element, model) {
    /*
    *
    *  {
    *       linkable: 'link' | 'auto',
    *       nav: string
    *       tabs: string
    *       onclick: function
    *  }
    *
    * */
    element = element || mwd.body;
    model = typeof model === 'undefined' ? true : model;
    if (model) {
        model = {
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
        };
    }
    var active = obj.activeNav || obj.activeClass || "active active-info",
        firstActive = 0;

    obj.lastClickedTabIndex = null;

    if (obj.linkable) {


        if (obj.linkable === 'link') {

        } else if (typeof obj.linkable === 'string') {
            $(window).on('load hashchange', function () {
                var param = mw.url.windowHashParam(obj.linkable);
                if(param) {
                    var el = $('[data-' + obj.linkable + '="' + param + '"]');
                }
            });
            $(obj.nav).each(function (i) {
                this.dataset.linkable = obj.linkable + '-' + i;
                (function (linkable, i) {
                    this.onclick = function () {
                        mw.url.windowHashParam(linkable, i);
                    };
                })(obj.linkable, i);
            });
        }
    }


    mw.$(obj.nav).on('click', function (e) {
        if (obj.linkable) {
            if (obj.linkable === 'link') {

            }
        } else {
            if (!$(this).hasClass(active)) {
                var i = mw.tools.index(this, mw.$(obj.nav).get(), mw.$(obj.nav)[0].nodeName);
                mw.$(obj.nav).removeClass(active);
                mw.$(this).addClass(active);
                mw.$(obj.tabs).hide().eq(i).show();
                obj.lastClickedTabIndex = i;
                if (typeof obj.onclick === 'function') {
                    obj.onclick.call(this, mw.$(obj.tabs).eq(i)[0], e, i);
                }
            }
            else {
                if (obj.toggle === true) {
                    mw.$(this).removeClass(active);
                    mw.$(obj.tabs).hide();
                    if (typeof obj.onclick === 'function') {
                        var i = mw.tools.index(this, element, obj.nav);
                        obj.onclick.call(this, mw.$(obj.tabs).eq(i)[0], e, i);
                    }
                }
            }
        }


        return false;
    }).each(function (i) {
        if (mw.tools.hasClass(this, active)) {
            firstActive = i;
        }
    });
    model.set(firstActive);
    return model;
};

})();

(() => {
/*!********************************************************!*\
  !*** ../userfiles/modules/microweber/api/tools/url.js ***!
  \********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
// URL Strings - Manipulations

json2url = function(obj){ var t=[];for(var x in obj)t.push(x+"="+encodeURIComponent(obj[x]));return t.join("&").replace(/undefined/g, 'false') };


mw.url = {
    hashStart: '',
    getDomain:function(url){
      return url.match(/:\/\/(www\.)?(.[^/:]+)/)[2];
    },
    removeHash:function(url){
        return url.replace( /#.*/, "");
    },
    getHash:function(url){
      return url.indexOf('#') !== -1 ? url.substring(url.indexOf('#'), url.length) : "";
    },
    strip:function(url){
      return url.replace(/#[^#]*$/, "").replace(/\?[^\?]*$/, "");
    },
    getUrlParams:function(url){
        url = mw.url.removeHash(url);
        if(url.contains('?')){
          var arr = url.slice(url.indexOf('?') + 1).split('&');
          var obj = {}, i=0, len = arr.length;
          for( ; i<len; i++){
            var p_arr = arr[i].split('=');
            obj[p_arr[0]] = p_arr[1];
          }
          return obj;
        }
        else{return {} }
    },
    set_param:function(param, value, url){
        url = url || window.location.href;
        var hash = mw.url.getHash(url);
        var params = mw.url.getUrlParams(url);
        params[param] = value;
        var params_string = json2url(params);
        url = mw.url.strip(url);
        return decodeURIComponent (url + "?" + params_string + hash);
    },
    remove_param:function(url, param){
        var hash = mw.url.getHash(url);
        var params = mw.url.getUrlParams(url);
        delete params[param];
        var params_string = json2url(params);
        url = mw.url.strip(url);
        return decodeURIComponent (url + "?" + params_string + hash);
    },
    getHashParams:function(hash){
        var r = new RegExp(mw.url.hashStart, "g");
        hash = hash.replace(r, "");
        hash = hash.replace(/\?/g, "");
        if(hash === '' || hash === '#'){
          return {};
        }
        else{
          hash = hash.replace(/#/g, "");
          var arr = hash.split('&');
          var obj = {}, i=0, len = arr.length;
          for( ; i<len; i++){
            var p_arr = arr[i].split('=');
            obj[p_arr[0]] = p_arr[1];
          }
          return obj;
        }
    },
    setHashParam:function(param, value, hash){

      hash = hash || mw.hash();
      var obj = mw.url.getHashParams(hash);
      obj[param] = value;
      return mw.url.hashStart + decodeURIComponent(json2url(obj));
    },
    windowHashParam:function(a,b){
      if(b !== undefined){
        mw.hash(mw.url.setHashParam(a,b));
      }
      else{
        return mw.url.getHashParams(mw.hash())[a];
      }
    },
    deleteHashParam:function(hash, param){
        var params = mw.url.getHashParams(hash);
        delete params[param];
        var params_string = decodeURIComponent(mw.url.hashStart+json2url(params));
        return params_string;
    },
    windowDeleteHashParam:function(param){
       mw.hash(mw.url.deleteHashParam(window.location.hash, param));
    },
    whichHashParamsHasBeenRemoved:function(currHash, prevHash){
        var curr = mw.url.getHashParams(currHash);
        var prev = mw.url.getHashParams(prevHash);
        var hashes = [];
        for(var x in prev){
            curr[x] === undefined ? hashes.push(x) : '';
        }
        return hashes;
    },
    hashParamToActiveNode:function(param, classNamespace, context){
        context = context || mwd.body;
        var val =  mw.url.windowHashParam(param);
        mw.$('.'+classNamespace, context).removeClass('active');
        var active = mw.$('.'+classNamespace + '-' + val, context);
        if(active.length > 0){
           active.addClass('active');
        }
        else{
           mw.$('.'+classNamespace + '-none', context).addClass('active');
        }
    },
    mwParams:function(url){
        url = url || window.location.pathname;
        url = mw.url.removeHash(url);
        var arr = url.split('/');
        var obj = {};
        var i=0,l=arr.length;
        for( ; i<l; i++){
            if(arr[i].indexOf(':') !== -1 && arr[i].indexOf('http') === -1){
                var p = arr[i].split(':');
                obj[p[0]] = p[1];
            }
        }
        return obj;
    },
    type:function(url){
        if(!url) return;
      url = url.toString();
      if( url ===  'false' ){
          return false;
      }
      if(url.indexOf('/images.unsplash.com/') !== -1){
          return 'image';
      }
      var extension = url.split('.').pop();
      var images = 'jpg,png,gif,jpeg,bmp,webp';
      if(images.contains(extension)){
        return 'image';
      }
      else if(extension === 'swf'){return 'flash';}
      else if(extension === 'pdf'){return 'pdf';}
      else if(url.contains('youtube.com') || url.contains('youtu.be')){return 'youtube';}
      else if(url.contains('vimeo.com')){return 'vimeo';}

      else{ return 'link'; }
    }
};



})();

/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvdG9vbHMvY2hlY2tib3guanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL3Rvb2xzL2NvbG9ycGlja2VyLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS90b29scy9jb21tb24tZXh0ZW5kLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS90b29scy9jb21tb24uanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL3Rvb2xzL2Nvb2tpZS5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvdG9vbHMvZG9taGVscGVycy5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvdG9vbHMvZHJvcGRvd24uanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL3Rvb2xzL2V4dHJhZGF0YS1mb3JtLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS90b29scy9mdWxsc2NyZWVuLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS90b29scy9oZWxwZXJzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS90b29scy9pZnJhbWUtdG9vbHMuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL3Rvb2xzL2ltYWdlcy5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvdG9vbHMvanF1ZXJ5LnRvb2xzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS90b29scy9sb29wcy5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvdG9vbHMvbmF0aXZlLW5vdGlmaWNhdGlvbi5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvdG9vbHMvbm90aWZpY2F0aW9uLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS90b29scy9vYmplY3RzLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS90b29scy9zdG9yYWdlLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS90b29scy9zeXN0ZW0tZGlhbG9ncy5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvdG9vbHMvdGFicy5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvdG9vbHMvdXJsLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiI7Ozs7Ozs7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLGNBQWMsT0FBTztBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsQzs7Ozs7Ozs7O0FDN0JBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsZ0JBQWdCO0FBQ2hCOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsOEJBQThCOztBQUU5QjtBQUNBO0FBQ0E7Ozs7QUFJQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOzs7Ozs7Ozs7O0FDdkxBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxDQUFDOztBQUVEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLENBQUM7O0FBRUQ7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHVCQUF1QixVQUFVO0FBQ2pDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQ0FBcUMsRUFBRTtBQUN2QztBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsY0FBYyxPQUFPO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQSxXQUFXLE9BQU87QUFDbEI7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGNBQWMsT0FBTztBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7QUFJQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsY0FBYyxTQUFTO0FBQ3ZCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxDQUFDO0FBQ0Q7QUFDQSxDQUFDO0FBQ0Q7QUFDQSxDQUFDO0FBQ0Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSwwQ0FBMEM7QUFDMUM7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7OztBQUdBO0FBQ0E7Ozs7O0FBS0E7QUFDQTtBQUNBOzs7Ozs7Ozs7OztBQzFPQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0EsQ0FBQztBQUNEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlDQUFpQywrQkFBK0I7QUFDaEU7QUFDQSxzQkFBc0IsT0FBTztBQUM3QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0EsbUNBQW1DO0FBQ25DO0FBQ0E7QUFDQTtBQUNBLHdDQUF3QztBQUN4QztBQUNBO0FBQ0Esd0NBQXdDO0FBQ3hDO0FBQ0E7QUFDQSx5Q0FBeUM7QUFDekM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7O0FBR0wsQ0FBQzs7Ozs7Ozs7OztBQzlJRDtBQUNBO0FBQ0EseUNBQXlDO0FBQ3pDLGNBQWMsT0FBTztBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUVBQXlFLCtEQUErRCxrQkFBa0IsNkJBQTZCLDRDQUE0QztBQUNuTyxLQUFLO0FBQ0w7O0FBRUE7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0EsOENBQThDO0FBQzlDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esc0JBQXNCLE9BQU87QUFDN0I7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0wsZ0JBQWdCO0FBQ2hCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7QUN0RUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCLE9BQU87QUFDekI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCLE9BQU87QUFDekI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxxQkFBcUIsV0FBVztBQUNoQztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0EscUJBQXFCLFdBQVc7QUFDaEM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLGtCQUFrQixnQkFBZ0I7QUFDbEM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGtCQUFrQixnQkFBZ0I7QUFDbEM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGtCQUFrQixnQkFBZ0I7QUFDbEM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQSxrQkFBa0IsZ0JBQWdCO0FBQ2xDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsY0FBYyxnQkFBZ0I7QUFDOUI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxjQUFjLE9BQU87QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7OztBQUdMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCLGdCQUFnQjtBQUNsQztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxrQkFBa0IsT0FBTztBQUN6QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQSxrQkFBa0IsZ0JBQWdCO0FBQ2xDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw2SEFBNkgsR0FBRztBQUNoSTs7QUFFQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsY0FBYyxPQUFPO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCLE9BQU87QUFDekI7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0Esb0JBQW9CO0FBQ3BCLGNBQWMsT0FBTztBQUNyQjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGtCQUFrQixPQUFPO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLG9CQUFvQjtBQUNwQixjQUFjLE9BQU87QUFDckI7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxrQkFBa0IsT0FBTztBQUN6QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLENBQUM7Ozs7Ozs7Ozs7QUN0ckJEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFVBQVUsT0FBTztBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDZCQUE2QjtBQUM3QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjs7QUFFckI7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7OztBQUdBOzs7Ozs7Ozs7O0FDMUdBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQSxrREFBa0Q7QUFDbEQ7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7Ozs7QUFJQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsU0FBUzs7QUFFVDtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7Ozs7QUFJVDtBQUNBOzs7OztBQUtBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQSxLQUFLO0FBQ0w7Ozs7Ozs7Ozs7QUMxRkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxDQUFDOzs7Ozs7Ozs7O0FDM0REO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCx3QkFBd0I7QUFDeEI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QseUJBQXlCO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckIsaUJBQWlCO0FBQ2pCO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLGtEQUFrRCxlQUFlO0FBQ2pFO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLHlDQUF5QztBQUN6QyxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGtCQUFrQixnQkFBZ0I7QUFDbEM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7OztBQUdUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsOENBQThDLHlDQUF5QztBQUN2RjtBQUNBLGFBQWE7QUFDYixTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCLE9BQU87QUFDekI7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw4QkFBOEIsdUJBQXVCO0FBQ3JELGtDQUFrQyxzQkFBc0I7QUFDeEQ7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTtBQUNiLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBLG1CQUFtQixPQUFPO0FBQzFCO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7O0FBRUEsYUFBYTtBQUNiLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCLE9BQU87QUFDekI7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLHFDQUFxQztBQUNyQztBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYixTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLHNDQUFzQztBQUN0QztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBLHNCQUFzQixTQUFTO0FBQy9CO0FBQ0EsMEJBQTBCLFNBQVM7QUFDbkM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsK0JBQStCLHFEQUFxRDtBQUNwRjtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLHlCQUF5QjtBQUN6QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLHdDQUF3QztBQUN4QztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsNkJBQTZCO0FBQzdCO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsNEJBQTRCO0FBQzVCLHNCQUFzQixPQUFPO0FBQzdCO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2IsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCLGFBQWE7QUFDYixTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUzs7QUFFVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMEJBQTBCLE9BQU87QUFDakM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUEsQ0FBQzs7Ozs7Ozs7OztBQzV5QkQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2IsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMOzs7QUFHQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7OztBQUlBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMOzs7Ozs7Ozs7O0FDbkdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxvQkFBb0IsV0FBVztBQUMvQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLGNBQWMsVUFBVTtBQUN4QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLGNBQWMsVUFBVTtBQUN4QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBOzs7Ozs7Ozs7O0FDdkVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2IsU0FBUztBQUNUO0FBQ0EsS0FBSztBQUNMOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBLGVBQWU7QUFDZjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQSxTQUFTO0FBQ1Q7Ozs7QUFJQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw4QkFBOEIsNEJBQTRCLDRCQUE0QjtBQUN0RjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0EsQ0FBQzs7Ozs7Ozs7OztBQ3pKRDtBQUNBO0FBQ0EsY0FBYyxnQ0FBZ0M7QUFDOUM7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsQ0FBQzs7Ozs7Ozs7OztBQ3BERDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7Ozs7Ozs7Ozs7QUM1QkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYixTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7Ozs7Ozs7OztBQ3BGQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUJBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxlQUFlLE9BQU87QUFDdEI7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7Ozs7Ozs7OztBQzdCQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaURBQWlEO0FBQ2pEO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTCxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDBEQUEwRDtBQUMxRCwwREFBMEQ7QUFDMUQ7QUFDQTtBQUNBO0FBQ0E7QUFDQSxrQ0FBa0MsT0FBTztBQUN6QztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esc0JBQXNCLE9BQU87QUFDN0I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7Ozs7Ozs7O0FDN0RBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsbUhBQW1IO0FBQ25IO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBOzs7Ozs7Ozs7O0FDaEdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7O0FBR0E7O0FBRUEsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCLGFBQWE7QUFDYjtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBOzs7Ozs7Ozs7O0FDbkhBOztBQUVBLHlCQUF5QixVQUFVLDBEQUEwRDs7O0FBRzdGO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esc0JBQXNCO0FBQ3RCLGdCQUFnQixPQUFPO0FBQ3ZCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhLFNBQVM7QUFDdEIsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxzQkFBc0I7QUFDdEIsZ0JBQWdCLE9BQU87QUFDdkI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGNBQWMsS0FBSztBQUNuQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLG1DQUFtQztBQUNuQyxtQ0FBbUM7QUFDbkMsdUVBQXVFO0FBQ3ZFLHlDQUF5Qzs7QUFFekMsV0FBVyxlQUFlO0FBQzFCO0FBQ0EiLCJmaWxlIjoidG9vbHMuanMiLCJzb3VyY2VzQ29udGVudCI6WyJtdy5jaGVjayA9IHtcclxuICAgIGFsbDogZnVuY3Rpb24gKHNlbGVjdG9yKSB7XHJcbiAgICAgICAgbXcuJChzZWxlY3RvcikuZmluZChcImlucHV0W3R5cGU9J2NoZWNrYm94J11cIikuZWFjaChmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIHRoaXMuY2hlY2tlZCA9IHRydWU7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9LFxyXG4gICAgbm9uZTogZnVuY3Rpb24gKHNlbGVjdG9yKSB7XHJcbiAgICAgICAgbXcuJChzZWxlY3RvcikuZmluZChcImlucHV0W3R5cGU9J2NoZWNrYm94J11cIikuZWFjaChmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIHRoaXMuY2hlY2tlZCA9IGZhbHNlO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfSxcclxuICAgIHRvZ2dsZTogZnVuY3Rpb24gKHNlbGVjdG9yKSB7XHJcbiAgICAgICAgdmFyIGVscyA9IG13LiQoc2VsZWN0b3IpLmZpbmQoXCJpbnB1dFt0eXBlPSdjaGVja2JveCddXCIpLCBjaGVja2VkID0gZWxzLmZpbHRlcignOmNoZWNrZWQnKTtcclxuICAgICAgICBpZiAoZWxzLmxlbmd0aCA9PT0gY2hlY2tlZC5sZW5ndGgpIHtcclxuICAgICAgICAgICAgbXcuY2hlY2subm9uZShzZWxlY3RvcilcclxuICAgICAgICB9XHJcbiAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgIG13LmNoZWNrLmFsbChzZWxlY3Rvcik7XHJcbiAgICAgICAgfVxyXG4gICAgfSxcclxuICAgIGNvbGxlY3RDaGVja2VkOiBmdW5jdGlvbiAocGFyZW50KSB7XHJcbiAgICAgICAgdmFyIGFyciA9IFtdO1xyXG4gICAgICAgIHZhciBhbGwgPSBwYXJlbnQucXVlcnlTZWxlY3RvckFsbCgnaW5wdXRbdHlwZT1cImNoZWNrYm94XCJdJyksIGkgPSAwLCBsID0gYWxsLmxlbmd0aDtcclxuICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xyXG4gICAgICAgICAgICB2YXIgZWwgPSBhbGxbaV07XHJcbiAgICAgICAgICAgIGVsLmNoZWNrZWQgPyBhcnIucHVzaChlbC52YWx1ZSkgOiAnJztcclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuIGFycjtcclxuICAgIH1cclxufSIsIm13Ll9jb2xvclBpY2tlckRlZmF1bHRzID0ge1xuICAgIHNraW46ICdtdy10b29sdGlwLWRlZmF1bHQnLFxuICAgIHBvc2l0aW9uOiAnYm90dG9tLWNlbnRlcicsXG4gICAgb25jaGFuZ2U6IGZhbHNlXG59O1xuXG5tdy5fY29sb3JQaWNrZXIgPSBmdW5jdGlvbiAob3B0aW9ucykge1xuICAgIG13LmxpYi5yZXF1aXJlKCdjb2xvcnBpY2tlcicpO1xuICAgIGlmICghbXcudG9vbHMuY29sb3JQaWNrZXJDb2xvcnMpIHtcbiAgICAgICAgbXcudG9vbHMuY29sb3JQaWNrZXJDb2xvcnMgPSBbXTtcblxuICAgICAgICAvLyB2YXIgY29sb3JwaWNrZXJfZWxzID0gbXcudG9wKCkuJChcImJvZHkgKlwiKTtcbiAgICAgICAgLy8gaWYoY29sb3JwaWNrZXJfZWxzLmxlbmd0aCA+IDApe1xuICAgICAgICAvLyAgICAgY29sb3JwaWNrZXJfZWxzLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAvLyAgICAgICAgIHZhciBjc3MgPSBwYXJlbnQuZ2V0Q29tcHV0ZWRTdHlsZSh0aGlzLCBudWxsKTtcbiAgICAgICAgLy8gICAgICAgICBpZiAoY3NzICE9PSBudWxsKSB7XG4gICAgICAgIC8vICAgICAgICAgICAgIGlmIChtdy50b29scy5jb2xvclBpY2tlckNvbG9ycy5pbmRleE9mKGNzcy5jb2xvcikgPT09IC0xKSB7XG4gICAgICAgIC8vICAgICAgICAgICAgICAgICBtdy50b29scy5jb2xvclBpY2tlckNvbG9ycy5wdXNoKG13LmNvbG9yLnJnYlRvSGV4KGNzcy5jb2xvcikpO1xuICAgICAgICAvLyAgICAgICAgICAgICB9XG4gICAgICAgIC8vICAgICAgICAgICAgIGlmIChtdy50b29scy5jb2xvclBpY2tlckNvbG9ycy5pbmRleE9mKGNzcy5iYWNrZ3JvdW5kQ29sb3IpID09PSAtMSkge1xuICAgICAgICAvLyAgICAgICAgICAgICAgICAgbXcudG9vbHMuY29sb3JQaWNrZXJDb2xvcnMucHVzaChtdy5jb2xvci5yZ2JUb0hleChjc3MuYmFja2dyb3VuZENvbG9yKSk7XG4gICAgICAgIC8vICAgICAgICAgICAgIH1cbiAgICAgICAgLy8gICAgICAgICB9XG4gICAgICAgIC8vICAgICB9KTtcbiAgICAgICAgLy8gfVxuXG4gICAgfVxuICAgIHZhciBwcm90byA9IHRoaXM7XG4gICAgaWYgKCFvcHRpb25zKSB7XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9XG5cbiAgICB2YXIgc2V0dGluZ3MgPSAkLmV4dGVuZCh7fSwgbXcuX2NvbG9yUGlja2VyRGVmYXVsdHMsIG9wdGlvbnMpO1xuXG4gICAgaWYgKHNldHRpbmdzLmVsZW1lbnQgPT09IHVuZGVmaW5lZCB8fCBzZXR0aW5ncy5lbGVtZW50ID09PSBudWxsKSB7XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9XG5cblxuXG4gICAgdmFyICRlbCA9IG13LiQoc2V0dGluZ3MuZWxlbWVudCk7XG4gICAgaWYgKCRlbFswXSA9PT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9XG4gICAgaWYoJGVsWzBdLm13Y29sb3JQaWNrZXIpIHtcbiAgICAgICAgcmV0dXJuICRlbFswXS5td2NvbG9yUGlja2VyO1xuICAgIH1cblxuXG4gICAgJGVsWzBdLm13Y29sb3JQaWNrZXIgPSB0aGlzO1xuICAgIHRoaXMuZWxlbWVudCA9ICRlbFswXTtcbiAgICBpZiAoJGVsWzBdLm13VG9vbFRpcEJpbmRlZCAhPT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9XG4gICAgaWYgKCFzZXR0aW5ncy5tZXRob2QpIHtcbiAgICAgICAgaWYgKHRoaXMuZWxlbWVudC5ub2RlTmFtZSA9PSAnRElWJykge1xuICAgICAgICAgICAgc2V0dGluZ3MubWV0aG9kID0gJ2lubGluZSc7XG4gICAgICAgIH1cbiAgICB9XG4gICAgdGhpcy5zZXR0aW5ncyA9IHNldHRpbmdzO1xuXG4gICAgJGVsWzBdLm13VG9vbFRpcEJpbmRlZCA9IHRydWU7XG4gICAgdmFyIHNldHQgPSB7XG4gICAgICAgIHNob3dBbHBoYTogdHJ1ZSxcbiAgICAgICAgc2hvd0hTTDogZmFsc2UsXG4gICAgICAgIHNob3dSR0I6IGZhbHNlLFxuICAgICAgICBzaG93SEVYOiB0cnVlLFxuICAgICAgICBwYWxldHRlOiBtdy50b29scy5jb2xvclBpY2tlckNvbG9yc1xuICAgIH07XG5cbiAgICBpZihzZXR0aW5ncy52YWx1ZSkge1xuICAgICAgICBzZXR0LmNvbG9yID0gc2V0dGluZ3MudmFsdWVcbiAgICB9XG4gICAgaWYodHlwZW9mIHNldHRpbmdzLnNob3dSR0IgIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgIHNldHQuc2hvd1JHQiA9IHNldHRpbmdzLnNob3dSR0JcbiAgICB9XG4gICAgaWYodHlwZW9mIHNldHRpbmdzLnNob3dIRVggIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgIHNldHQuc2hvd0hFWCA9IHNldHRpbmdzLnNob3dIRVhcbiAgICB9XG5cbiAgICBpZih0eXBlb2Ygc2V0dGluZ3Muc2hvd0hTTCAhPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgc2V0dC5zaG93SFNMID0gc2V0dGluZ3Muc2hvd0hTTFxuICAgIH1cbiAgICB2YXIgZnJhbWU7XG4gICAgaWYgKHNldHRpbmdzLm1ldGhvZCA9PT0gJ2lubGluZScpIHtcblxuICAgICAgICBzZXR0LmF0dGFjaFRvID0gJGVsWzBdO1xuXG4gICAgICAgIGZyYW1lID0gQUNvbG9yUGlja2VyLmNyZWF0ZVBpY2tlcihzZXR0KTtcbiAgICAgICAgZnJhbWUub25jaGFuZ2UgPSBmdW5jdGlvbiAoZGF0YSkge1xuXG4gICAgICAgICAgICBpZiAocHJvdG8uc2V0dGluZ3Mub25jaGFuZ2UpIHtcbiAgICAgICAgICAgICAgICBwcm90by5zZXR0aW5ncy5vbmNoYW5nZShkYXRhLmNvbG9yKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKCRlbFswXS5ub2RlTmFtZSA9PT0gJ0lOUFVUJykge1xuICAgICAgICAgICAgICAgIHZhciB2YWwgPSB2YWwgPT09ICd0cmFuc3BhcmVudCcgPyB2YWwgOiAnIycgKyB2YWw7XG4gICAgICAgICAgICAgICAgJGVsLnZhbCh2YWwpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICB9XG4gICAgZWxzZSB7XG4gICAgICAgIHZhciB0aXAgPSBtdy50b29sdGlwKHNldHRpbmdzKSwgJHRpcCA9IG13LiQodGlwKS5oaWRlKCk7XG4gICAgICAgIHRoaXMudGlwID0gdGlwO1xuXG4gICAgICAgIG13LiQoJy5tdy10b29sdGlwLWNvbnRlbnQnLCB0aXApLmVtcHR5KCk7XG4gICAgICAgIHNldHQuYXR0YWNoVG8gPSBtdy4kKCcubXctdG9vbHRpcC1jb250ZW50JywgdGlwKVswXVxuXG4gICAgICAgIGZyYW1lID0gQUNvbG9yUGlja2VyLmNyZWF0ZVBpY2tlcihzZXR0KTtcblxuICAgICAgICBmcmFtZS5vbmNoYW5nZSA9IGZ1bmN0aW9uIChkYXRhKSB7XG5cbiAgICAgICAgICAgIGlmKGZyYW1lLnBhdXNlKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAocHJvdG8uc2V0dGluZ3Mub25jaGFuZ2UpIHtcbiAgICAgICAgICAgICAgICBwcm90by5zZXR0aW5ncy5vbmNoYW5nZShkYXRhLmNvbG9yKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKCRlbFswXS5ub2RlTmFtZSA9PT0gJ0lOUFVUJykge1xuICAgICAgICAgICAgICAgICRlbC52YWwoZGF0YS5jb2xvcik7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG4gICAgICAgIGlmICgkZWxbMF0ubm9kZU5hbWUgPT09ICdJTlBVVCcpIHtcbiAgICAgICAgICAgICRlbC5vbignZm9jdXMnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGlmKHRoaXMudmFsdWUudHJpbSgpKXtcbiAgICAgICAgICAgICAgICAgICAgZnJhbWUucGF1c2UgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICBmcmFtZS5jb2xvciA9IHRoaXMudmFsdWU7XG4gICAgICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZnJhbWUucGF1c2UgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIG13LiQodGlwKS5zaG93KCk7XG4gICAgICAgICAgICAgICAgbXcudG9vbHMudG9vbHRpcC5zZXRQb3NpdGlvbih0aXAsICRlbFswXSwgc2V0dGluZ3MucG9zaXRpb24pXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICRlbC5vbignY2xpY2snLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIG13LiQodGlwKS50b2dnbGUoKTtcbiAgICAgICAgICAgICAgICBtdy50b29scy50b29sdGlwLnNldFBvc2l0aW9uKHRpcCwgJGVsWzBdLCBzZXR0aW5ncy5wb3NpdGlvbilcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgICAgIHZhciBkb2N1bWVudHMgPSBbZG9jdW1lbnRdO1xuICAgICAgICBpZiAoc2VsZiAhPT0gdG9wKXtcbiAgICAgICAgICAgIGRvY3VtZW50cy5wdXNoKHRvcC5kb2N1bWVudCk7XG4gICAgICAgIH1cbiAgICAgICAgbXcuJChkb2N1bWVudHMpLm9uKCdjbGljaycsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICBpZiAoIW13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3MoZS50YXJnZXQsICdtdy10b29sdGlwJykgJiYgZS50YXJnZXQgIT09ICRlbFswXSkge1xuICAgICAgICAgICAgICAgIG13LiQodGlwKS5oaWRlKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICBpZiAoJGVsWzBdLm5vZGVOYW1lID09PSAnSU5QVVQnKSB7XG4gICAgICAgICAgICAkZWwuYmluZCgnYmx1cicsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAvLyQodGlwKS5oaWRlKCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgIH1cbiAgICBpZiAodGhpcy50aXApIHtcbiAgICAgICAgdGhpcy5zaG93ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcuJCh0aGlzLnRpcCkuc2hvdygpO1xuICAgICAgICAgICAgbXcudG9vbHMudG9vbHRpcC5zZXRQb3NpdGlvbih0aGlzLnRpcCwgdGhpcy5zZXR0aW5ncy5lbGVtZW50LCB0aGlzLnNldHRpbmdzLnBvc2l0aW9uKVxuICAgICAgICB9O1xuICAgICAgICB0aGlzLmhpZGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy4kKHRoaXMudGlwKS5oaWRlKClcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy50b2dnbGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgdGlwID0gbXcuJCh0aGlzLnRpcCk7XG4gICAgICAgICAgICBpZiAodGlwLmlzKCc6dmlzaWJsZScpKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5oaWRlKClcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICRlbC5mb2N1cygpO1xuICAgICAgICAgICAgICAgIHRoaXMuc2hvdygpXG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9XG5cbn07XG5tdy5jb2xvclBpY2tlciA9IGZ1bmN0aW9uIChvKSB7XG5cbiAgICByZXR1cm4gbmV3IG13Ll9jb2xvclBpY2tlcihvKTtcbn07XG4iLCJtdy5yZXF1ZXN0QW5pbWF0aW9uRnJhbWUgPSAoZnVuY3Rpb24gKCkge1xuICAgIHJldHVybiB3aW5kb3cud2Via2l0UmVxdWVzdEFuaW1hdGlvbkZyYW1lIHx8XG4gICAgICAgIHdpbmRvdy5tb3pSZXF1ZXN0QW5pbWF0aW9uRnJhbWUgfHxcbiAgICAgICAgd2luZG93Lm9SZXF1ZXN0QW5pbWF0aW9uRnJhbWUgfHxcbiAgICAgICAgd2luZG93Lm1zUmVxdWVzdEFuaW1hdGlvbkZyYW1lIHx8XG4gICAgICAgIGZ1bmN0aW9uIChjYWxsYmFjaywgZWxlbWVudCkge1xuICAgICAgICAgICAgd2luZG93LnNldFRpbWVvdXQoY2FsbGJhY2ssIDEwMDAgLyA2MCk7XG4gICAgICAgIH07XG59KSgpO1xuXG5tdy5faW50ZXJ2YWxzID0ge307XG5tdy5pbnRlcnZhbCA9IGZ1bmN0aW9uKGtleSwgZnVuYyl7XG4gICAgaWYoIWtleSB8fCAhZnVuYyB8fCAhIW13Ll9pbnRlcnZhbHNba2V5XSkgcmV0dXJuO1xuICAgIG13Ll9pbnRlcnZhbHNba2V5XSA9IGZ1bmM7XG59O1xubXcucmVtb3ZlSW50ZXJ2YWwgPSBmdW5jdGlvbihrZXkpe1xuICAgIGRlbGV0ZSBtdy5faW50ZXJ2YWxzW2tleV07XG59O1xuc2V0SW50ZXJ2YWwoZnVuY3Rpb24oKXtcbiAgICBmb3IodmFyIGkgaW4gbXcuX2ludGVydmFscyl7XG4gICAgICAgIG13Ll9pbnRlcnZhbHNbaV0uY2FsbCgpO1xuICAgIH1cbn0sIDk5KTtcblxubXcuZGF0YXNzZXRTdXBwb3J0ID0gdHlwZW9mIG13ZC5kb2N1bWVudEVsZW1lbnQuZGF0YXNldCAhPT0gJ3VuZGVmaW5lZCc7XG5cbm13LmV4ZWMgPSBmdW5jdGlvbiAoc3RyLCBhLCBiLCBjKSB7XG4gICAgYSA9IGEgfHwgXCJcIjtcbiAgICBiID0gYiB8fCBcIlwiO1xuICAgIGMgPSBjIHx8IFwiXCI7XG4gICAgaWYgKCFzdHIuY29udGFpbnMoXCIuXCIpKSB7XG4gICAgICAgIHJldHVybiB3aW5kb3dbdGhpc10oYSwgYiwgYyk7XG4gICAgfVxuICAgIGVsc2Uge1xuICAgICAgICB2YXIgYXJyID0gc3RyLnNwbGl0KFwiLlwiKTtcbiAgICAgICAgdmFyIHRlbXAgPSB3aW5kb3dbYXJyWzBdXTtcbiAgICAgICAgdmFyIGxlbiA9IGFyci5sZW5ndGggLSAxO1xuICAgICAgICBmb3IgKHZhciBpID0gMTsgaSA8PSBsZW47IGkrKykge1xuICAgICAgICAgICAgaWYgKHR5cGVvZiB0ZW1wID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRlbXAgPSB0ZW1wW2FycltpXV07XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIG13LmlzLmZ1bmModGVtcCkgPyB0ZW1wKGEsIGIsIGMpIDogdGVtcDtcbiAgICB9XG59O1xuXG5tdy5jb250cm9sbGVycyA9IHt9O1xubXcuZXh0ZXJuYWxfdG9vbCA9IGZ1bmN0aW9uICh1cmwpIHtcbiAgICByZXR1cm4gIXVybC5jb250YWlucyhcIi9cIikgPyBtdy5zZXR0aW5ncy5zaXRlX3VybCArIFwiZWRpdG9yX3Rvb2xzL1wiICsgdXJsIDogdXJsO1xufTtcbi8vIFBvbHlmaWxsIGZvciBlc2NhcGUvdW5lc2NhcGVcbmlmICghd2luZG93LnVuZXNjYXBlKSB7XG4gICAgd2luZG93LnVuZXNjYXBlID0gZnVuY3Rpb24gKHMpIHtcbiAgICAgICAgcmV0dXJuIHMucmVwbGFjZSgvJShbMC05QS1GXXsyfSkvZywgZnVuY3Rpb24gKG0sIHApIHtcbiAgICAgICAgICAgIHJldHVybiBTdHJpbmcuZnJvbUNoYXJDb2RlKCcweCcgKyBwKTtcbiAgICAgICAgfSk7XG4gICAgfTtcbn1cbmlmICghd2luZG93LmVzY2FwZSkge1xuICAgIHdpbmRvdy5lc2NhcGUgPSBmdW5jdGlvbiAocykge1xuICAgICAgICB2YXIgY2hyLCBoZXgsIGkgPSAwLCBsID0gcy5sZW5ndGgsIG91dCA9ICcnO1xuICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgY2hyID0gcy5jaGFyQXQoaSk7XG4gICAgICAgICAgICBpZiAoY2hyLnNlYXJjaCgvW0EtWmEtejAtOVxcQFxcKlxcX1xcK1xcLVxcLlxcL10vKSA+IC0xKSB7XG4gICAgICAgICAgICAgICAgb3V0ICs9IGNocjtcbiAgICAgICAgICAgICAgICBjb250aW51ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGhleCA9IHMuY2hhckNvZGVBdChpKS50b1N0cmluZygxNik7XG4gICAgICAgICAgICBvdXQgKz0gJyUnICsgKCBoZXgubGVuZ3RoICUgMiAhPT0gMCA/ICcwJyA6ICcnICkgKyBoZXg7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIG91dDtcbiAgICB9O1xufVxuXG5cbkFycmF5LnByb3RvdHlwZS5yZW1vdmUgPSBBcnJheS5wcm90b3R5cGUucmVtb3ZlIHx8IGZ1bmN0aW9uICh3aGF0KSB7XG4gICAgdmFyIGkgPSAwLCBsID0gdGhpcy5sZW5ndGg7XG4gICAgZm9yICggOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgIHRoaXNbaV0gPT09IHdoYXQgPyB0aGlzLnNwbGljZShpLCAxKSA6ICcnO1xuICAgIH1cbn07XG5cbm13LndoaWNoID0gZnVuY3Rpb24gKHN0ciwgYXJyX29iaiwgZnVuYykge1xuICAgIGlmIChhcnJfb2JqIGluc3RhbmNlb2YgQXJyYXkpIHtcbiAgICAgICAgdmFyIGwgPSBhcnJfb2JqLmxlbmd0aCwgaSA9IDA7XG4gICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICBpZiAoYXJyX29ialtpXSA9PT0gc3RyKSB7XG4gICAgICAgICAgICAgICAgZnVuYy5jYWxsKGFycl9vYmpbaV0pO1xuICAgICAgICAgICAgICAgIHJldHVybiBhcnJfb2JqW2ldO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfVxuICAgIGVsc2Uge1xuICAgICAgICBmb3IgKHZhciBpIGluIGFycl9vYmopIHtcbiAgICAgICAgICAgIGlmIChpID09PSBzdHIpIHtcbiAgICAgICAgICAgICAgICBmdW5jLmNhbGwoYXJyX29ialtpXSk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGFycl9vYmpbaV07XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9XG59O1xuXG5cblxubXcuX0pTUHJlZml4ZXMgPSBbJ01veicsICdXZWJraXQnLCAnTycsICdtcyddO1xuX1ByZWZpeHRlc3QgPSBmYWxzZTtcbm13LkpTUHJlZml4ID0gZnVuY3Rpb24gKHByb3BlcnR5KSB7XG4gICAgIV9QcmVmaXh0ZXN0ID8gX1ByZWZpeHRlc3QgPSBtd2QuYm9keS5zdHlsZSA6ICcnO1xuICAgIGlmIChfUHJlZml4dGVzdFtwcm9wZXJ0eV0gIT09IHVuZGVmaW5lZCkge1xuICAgICAgICByZXR1cm4gcHJvcGVydHk7XG4gICAgfVxuICAgIGVsc2Uge1xuICAgICAgICB2YXIgcHJvcGVydHkgPSBwcm9wZXJ0eS5jaGFyQXQoMCkudG9VcHBlckNhc2UoKSArIHByb3BlcnR5LnNsaWNlKDEpLFxuICAgICAgICAgICAgbGVuID0gbXcuX0pTUHJlZml4ZXMubGVuZ3RoLFxuICAgICAgICAgICAgaSA9IDA7XG4gICAgICAgIGZvciAoOyBpIDwgbGVuOyBpKyspIHtcbiAgICAgICAgICAgIGlmIChfUHJlZml4dGVzdFttdy5fSlNQcmVmaXhlc1tpXSArIHByb3BlcnR5XSAhPT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIG13Ll9KU1ByZWZpeGVzW2ldICsgcHJvcGVydHk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9XG59XG5pZiAodHlwZW9mIGRvY3VtZW50LmhpZGRlbiAhPT0gXCJ1bmRlZmluZWRcIikge1xuICAgIF9td2RvY2hpZGRlbiA9IFwiaGlkZGVuXCI7XG59IGVsc2UgaWYgKHR5cGVvZiBkb2N1bWVudC5tb3pIaWRkZW4gIT09IFwidW5kZWZpbmVkXCIpIHtcbiAgICBfbXdkb2NoaWRkZW4gPSBcIm1vekhpZGRlblwiO1xufSBlbHNlIGlmICh0eXBlb2YgZG9jdW1lbnQubXNIaWRkZW4gIT09IFwidW5kZWZpbmVkXCIpIHtcbiAgICBfbXdkb2NoaWRkZW4gPSBcIm1zSGlkZGVuXCI7XG59IGVsc2UgaWYgKHR5cGVvZiBkb2N1bWVudC53ZWJraXRIaWRkZW4gIT09IFwidW5kZWZpbmVkXCIpIHtcbiAgICBfbXdkb2NoaWRkZW4gPSBcIndlYmtpdEhpZGRlblwiO1xufVxuZG9jdW1lbnQuaXNIaWRkZW4gPSBmdW5jdGlvbiAoKSB7XG4gICAgaWYgKHR5cGVvZiBfbXdkb2NoaWRkZW4gIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgIHJldHVybiBkb2N1bWVudFtfbXdkb2NoaWRkZW5dO1xuICAgIH1cbiAgICBlbHNlIHtcbiAgICAgICAgcmV0dXJuICFkb2N1bWVudC5oYXNGb2N1cygpO1xuICAgIH1cbn07XG5cblxubXcucG9zdE1zZyA9IGZ1bmN0aW9uICh3LCBvYmopIHtcbiAgICB3LnBvc3RNZXNzYWdlKEpTT04uc3RyaW5naWZ5KG9iaiksIHdpbmRvdy5sb2NhdGlvbi5ocmVmKTtcbn07XG5cbm13LnVwbG9hZGVyID0gZnVuY3Rpb24gKG8pIHtcblxuICAgIG13LnJlcXVpcmUoXCJmaWxlcy5qc1wiKTtcblxuICAgIHZhciB1cGxvYWRlciA9IG13LmZpbGVzLnVwbG9hZGVyKG8pO1xuXG4gICAgcmV0dXJuIHVwbG9hZGVyO1xufTtcblxubXcuZmlsZVdpbmRvdyA9IGZ1bmN0aW9uIChjb25maWcpIHtcbiAgICBjb25maWcgPSBjb25maWcgfHwge307XG4gICAgY29uZmlnLm1vZGUgPSBjb25maWcubW9kZSB8fCAnZGlhbG9nJzsgLy8gJ2lubGluZScgfCAnZGlhbG9nJ1xuICAgIHZhciBxID0ge1xuICAgICAgICB0eXBlczogY29uZmlnLnR5cGVzLFxuICAgICAgICB0aXRsZTogY29uZmlnLnRpdGxlXG4gICAgfTtcblxuXG4gICAgdXJsID0gbXcuc2V0dGluZ3Muc2l0ZV91cmwgKyAnZWRpdG9yX3Rvb2xzL3J0ZV9pbWFnZV9lZGl0b3I/JyArICQucGFyYW0ocSkgKyAnI2ZpbGVXaW5kb3cnO1xuICAgIHZhciBmcmFtZVdpbmRvdztcbiAgICB2YXIgdG9yZXR1cm4gPSB7XG4gICAgICAgIGRpYWxvZzogbnVsbCxcbiAgICAgICAgcm9vdDogbnVsbCxcbiAgICAgICAgaWZyYW1lOiBudWxsXG4gICAgfTtcbiAgICBpZiAoY29uZmlnLm1vZGUgPT09ICdkaWFsb2cnKSB7XG4gICAgICAgIHZhciBtb2RhbCA9IG13LyoudG9wKCkqLy5kaWFsb2dJZnJhbWUoe1xuICAgICAgICAgICAgdXJsOiB1cmwsXG4gICAgICAgICAgICBuYW1lOiBcIm13X3J0ZV9pbWFnZVwiLFxuICAgICAgICAgICAgd2lkdGg6IDUzMCxcbiAgICAgICAgICAgIGhlaWdodDogJ2F1dG8nLFxuICAgICAgICAgICAgYXV0b0hlaWdodDogdHJ1ZSxcbiAgICAgICAgICAgIC8vdGVtcGxhdGU6ICdtd19tb2RhbF9iYXNpYycsXG4gICAgICAgICAgICBvdmVybGF5OiB0cnVlLFxuICAgICAgICAgICAgdGl0bGU6IG13LmxhbmcoJ1NlbGVjdCBpbWFnZScpXG4gICAgICAgIH0pO1xuICAgICAgICB2YXIgZnJhbWUgPSBtdy4kKCdpZnJhbWUnLCBtb2RhbC5tYWluKTtcbiAgICAgICAgZnJhbWVXaW5kb3cgPSBmcmFtZVswXS5jb250ZW50V2luZG93O1xuICAgICAgICB0b3JldHVybi5kaWFsb2cgPSBtb2RhbDtcbiAgICAgICAgdG9yZXR1cm4ucm9vdCA9IGZyYW1lLnBhcmVudCgpWzBdO1xuICAgICAgICB0b3JldHVybi5pZnJhbWUgPSBmcmFtZVswXTtcbiAgICAgICAgZnJhbWVXaW5kb3cub25sb2FkID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgZnJhbWVXaW5kb3cuJCgnYm9keScpLm9uKCdSZXN1bHQnLCBmdW5jdGlvbiAoZSwgdXJsLCBtKSB7XG4gICAgICAgICAgICAgICAgIGlmIChjb25maWcuY2hhbmdlKSB7XG4gICAgICAgICAgICAgICAgICAgIGNvbmZpZy5jaGFuZ2UuY2FsbCh1bmRlZmluZWQsIHVybCk7XG4gICAgICAgICAgICAgICAgICAgIG1vZGFsLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgJChtb2RhbCkub24oJ1Jlc3VsdCcsIGZ1bmN0aW9uIChlLCB1cmwsIG0pIHtcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyg5OTk5KVxuICAgICAgICAgICAgICAgIGlmIChjb25maWcuY2hhbmdlKSB7XG4gICAgICAgICAgICAgICAgICAgIGNvbmZpZy5jaGFuZ2UuY2FsbCh1bmRlZmluZWQsIHVybCk7XG4gICAgICAgICAgICAgICAgICAgIG1vZGFsLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuICAgIH0gZWxzZSBpZiAoY29uZmlnLm1vZGUgPT09ICdpbmxpbmUnKSB7XG4gICAgICAgIHZhciBmciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2lmcmFtZScpO1xuICAgICAgICBmci5zcmMgPSB1cmw7XG4gICAgICAgIGZyLmZyYW1lQm9yZGVyID0gMDtcbiAgICAgICAgZnIuY2xhc3NOYW1lID0gJ213LWZpbGUtd2luZG93LWZyYW1lJztcbiAgICAgICAgdG9yZXR1cm4uaWZyYW1lID0gZnI7XG4gICAgICAgIG13LnRvb2xzLmlmcmFtZUF1dG9IZWlnaHQoZnIpO1xuICAgICAgICBpZiAoY29uZmlnLmVsZW1lbnQpIHtcbiAgICAgICAgICAgIHZhciAkZWwgPSAkKGNvbmZpZy5lbGVtZW50KTtcbiAgICAgICAgICAgIGlmKCRlbC5sZW5ndGgpIHtcbiAgICAgICAgICAgICAgICB0b3JldHVybi5yb290ID0gJGVsWzBdO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgJGVsLmFwcGVuZChmcik7XG4gICAgICAgIH1cbiAgICAgICAgZnIub25sb2FkID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy5jb250ZW50V2luZG93LiQoJ2JvZHknKS5vbignY2hhbmdlJywgZnVuY3Rpb24gKGUsIHVybCwgbSkge1xuICAgICAgICAgICAgICAgIGlmIChjb25maWcuY2hhbmdlKSB7XG4gICAgICAgICAgICAgICAgICAgIGNvbmZpZy5jaGFuZ2UuY2FsbCh1bmRlZmluZWQsIHVybCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH07XG4gICAgfVxuXG5cbiAgICByZXR1cm4gdG9yZXR1cm47XG59O1xuXG5cblxuXG5tdy5hY2NvcmRpb24gPSBmdW5jdGlvbiAoZWwsIGNhbGxiYWNrKSB7XG4gICAgcmV0dXJuIG13LnRvb2xzLmFjY29yZGlvbihtdy4kKGVsKVswXSwgY2FsbGJhY2spO1xufTtcblxuIiwiJCh3aW5kb3cpLmxvYWQoZnVuY3Rpb24gKCkge1xuICAgIG13LmxvYWRlZCA9IHRydWU7XG4gICAgbXcudG9vbHMuYWRkQ2xhc3MobXdkLmJvZHksICdsb2FkZWQnKTtcbiAgICBtdy50b29scy5yZW1vdmVDbGFzcyhtd2QuYm9keSwgJ2xvYWRpbmcnKTtcbiAgICBtdy4kKCdkaXYubXctdWktZmllbGQnKS5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICBpZiAoZS50YXJnZXQudHlwZSAhPSAndGV4dCcpIHtcbiAgICAgICAgICAgIHRyeSB7XG4gICAgICAgICAgICAgICAgdGhpcy5xdWVyeVNlbGVjdG9yKCdpbnB1dFt0eXBlPVwidGV4dFwiXScpLmZvY3VzKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBjYXRjaCAoZSkge1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSk7XG5cbiAgICBtdy5kcm9wZG93bigpO1xufSk7XG4kKG13ZCkucmVhZHkoZnVuY3Rpb24gKCkge1xuICAgIG13LnRvb2xzLmNvbnN0cnVjdGlvbnMoKTtcbiAgICBtdy5kcm9wZG93bigpO1xuICAgIG13LiQobXdkLmJvZHkpLmFqYXhTdG9wKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBtdy5kcm9wZG93bigpO1xuICAgICAgICB9LCAxMjIyKTtcbiAgICB9KTtcbiAgICBtdy5vbignbXdEaWFsb2dTaG93JywgZnVuY3Rpb24oKXtcbiAgICAgICAgbXcuJChkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQpLmFkZENsYXNzKCdtdy1kaWFsb2ctb3BlbmVkJyk7XG4gICAgfSk7XG4gICAgbXcub24oJ213RGlhbG9nSGlkZScsIGZ1bmN0aW9uKCl7XG4gICAgICAgIG13LiQoZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50KS5yZW1vdmVDbGFzcygnbXctZGlhbG9nLW9wZW5lZCcpO1xuICAgIH0pO1xuICAgIG13LiQobXdkLmJvZHkpLm9uKCdtb3VzZW1vdmUgdG91Y2htb3ZlIHRvdWNoc3RhcnQnLCBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgdmFyIGhhcyA9IG13LnRvb2xzLmZpcnN0UGFyZW50T3JDdXJyZW50V2l0aENsYXNzKGV2ZW50LnRhcmdldCwgJ3RpcCcpO1xuICAgICAgICBpZiAoaGFzICYmICghaGFzLmRhdGFzZXQudHJpZ2dlciB8fCBoYXMuZGF0YXNldC50cmlnZ2VyID09PSAnbW92ZScpKSB7XG4gICAgICAgICAgICBtdy50b29scy50aXRsZVRpcChoYXMpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgbXcuJChtdy50b29scy5fdGl0bGVUaXApLmhpZGUoKTtcbiAgICAgICAgfVxuICAgIH0pLm9uKCdjbGljaycsIGZ1bmN0aW9uIChldmVudCkge1xuICAgICAgICB2YXIgaGFzID0gbXcudG9vbHMuZmlyc3RQYXJlbnRPckN1cnJlbnRXaXRoQ2xhc3MoZXZlbnQudGFyZ2V0LCAndGlwJyk7XG4gICAgICAgIGlmIChoYXMgJiYgaGFzLmRhdGFzZXQudHJpZ2dlciA9PT0gJ2NsaWNrJykge1xuICAgICAgICAgICAgbXcudG9vbHMudGl0bGVUaXAoaGFzLCAnX3RpdGxlVGlwQ2xpY2snKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIG13LiQobXcudG9vbHMuX3RpdGxlVGlwQ2xpY2spLmhpZGUoKTtcbiAgICAgICAgfVxuICAgIH0pO1xuICAgIG13LiQoXCIud3lzaXd5Zy1jb252ZXJ0aWJsZS10b2dnbGVyXCIpLmNsaWNrKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIGVsID0gbXcuJCh0aGlzKSwgbmV4dCA9IGVsLm5leHQoKTtcbiAgICAgICAgbXcuJChcIi53eXNpd3lnLWNvbnZlcnRpYmxlXCIpLm5vdChuZXh0KS5yZW1vdmVDbGFzcyhcImFjdGl2ZVwiKTtcbiAgICAgICAgbXcuJChcIi53eXNpd3lnLWNvbnZlcnRpYmxlLXRvZ2dsZXJcIikubm90KGVsKS5yZW1vdmVDbGFzcyhcImFjdGl2ZVwiKTtcbiAgICAgICAgbmV4dC50b2dnbGVDbGFzcyhcImFjdGl2ZVwiKTtcbiAgICAgICAgZWwudG9nZ2xlQ2xhc3MoXCJhY3RpdmVcIik7XG4gICAgICAgIGlmIChlbC5oYXNDbGFzcyhcImFjdGl2ZVwiKSkge1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBtdy5saXZlRWRpdFdZU0lXWUcgPT09ICdvYmplY3QnKSB7XG4gICAgICAgICAgICAgICAgbXcubGl2ZWVkaXQudG9vbGJhci5lZGl0b3IuZml4Q29udmVydGlibGUobmV4dCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9KTtcbiAgICBtdy4kKFwiLm13LWRyb3Bkb3duLXNlYXJjaFwiKS5rZXl1cChmdW5jdGlvbiAoZSkge1xuICAgICAgICBpZiAoZS5rZXlDb2RlID09ICcyNycpIHtcbiAgICAgICAgICAgIG13LiQodGhpcy5wYXJlbnROb2RlLnBhcmVudE5vZGUpLmhpZGUoKTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoZS5rZXlDb2RlICE9ICcxMycgJiYgZS5rZXlDb2RlICE9ICcyNycgJiYgZS5rZXlDb2RlICE9ICczMicpIHtcbiAgICAgICAgICAgIHZhciBlbCA9IG13LiQodGhpcyk7XG4gICAgICAgICAgICBlbC5hZGRDbGFzcygnbXctZHJvcGRvd24tc2VhcmNoU2VhcmNoaW5nJyk7XG4gICAgICAgICAgICBtdy50b29scy5hamF4U2VhcmNoKHtrZXl3b3JkOiB0aGlzLnZhbHVlLCBsaW1pdDogMjB9LCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIGh0bWwgPSBcIjx1bD5cIiwgbCA9IHRoaXMubGVuZ3RoLCBpID0gMDtcbiAgICAgICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgICAgICB2YXIgYSA9IHRoaXNbaV07XG4gICAgICAgICAgICAgICAgICAgIGh0bWwgKz0gJzxsaSBjbGFzcz1cIicgKyBhLmNvbnRlbnRfdHlwZSArICcgJyArIGEuc3VidHlwZSArICdcIj48YSBocmVmPVwiJyArIGEudXJsICsgJ1wiIHRpdGxlPVwiJyArIGEudGl0bGUgKyAnXCI+JyArIGEudGl0bGUgKyAnPC9hPjwvbGk+JztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaHRtbCArPSAnPC91bD4nO1xuICAgICAgICAgICAgICAgIGVsLnBhcmVudCgpLm5leHQoXCJ1bFwiKS5yZXBsYWNlV2l0aChodG1sKTtcbiAgICAgICAgICAgICAgICBlbC5yZW1vdmVDbGFzcygnbXctZHJvcGRvd24tc2VhcmNoU2VhcmNoaW5nJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgIH0pO1xuICAgIHZhciBfbXdvbGR3dyA9IG13LiQod2luZG93KS53aWR0aCgpO1xuICAgIG13LiQod2luZG93KS5yZXNpemUoZnVuY3Rpb24gKCkge1xuICAgICAgICBpZiAoJCh3aW5kb3cpLndpZHRoKCkgPiBfbXdvbGR3dykge1xuICAgICAgICAgICAgbXcudHJpZ2dlcihcImluY3JlYXNlV2lkdGhcIik7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSBpZiAoJCh3aW5kb3cpLndpZHRoKCkgPCBfbXdvbGR3dykge1xuICAgICAgICAgICAgbXcudHJpZ2dlcihcImRlY3JlYXNlV2lkdGhcIik7XG4gICAgICAgIH1cbiAgICAgICAgJC5ub29wKCk7XG4gICAgICAgIF9td29sZHd3ID0gbXcuJCh3aW5kb3cpLndpZHRoKCk7XG4gICAgfSk7XG4gICAgbXcuJChtd2QuYm9keSkub24oXCJrZXlkb3duXCIsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIHZhciBpc2dhbCA9IG13ZC5xdWVyeVNlbGVjdG9yKCcubXdfbW9kYWxfZ2FsbGVyeScpICE9PSBudWxsO1xuICAgICAgICBpZiAoaXNnYWwpIHtcbiAgICAgICAgICAgIGlmIChlLmtleUNvZGUgPT09IDI3KSB7ICAvKiBlc2NhcGUgKi9cbiAgICAgICAgICAgICAgICBtdy5kaWFsb2cucmVtb3ZlKG13LiQoXCIubXdfbW9kYWxfZ2FsbGVyeVwiKSlcbiAgICAgICAgICAgICAgICBtdy50b29scy5jYW5jZWxGdWxsc2NyZWVuKClcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2UgaWYgKGUua2V5Q29kZSA9PT0gMzcpIHsgLyogbGVmdCAqL1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLmdhbGxlcnkucHJldihtdy4kKFwiLm13X21vZGFsX2dhbGxlcnlcIilbMF0ubW9kYWwpXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIGlmIChlLmtleUNvZGUgPT09IDM5KSB7IC8qIHJpZ2h0ICovXG4gICAgICAgICAgICAgICAgbXcudG9vbHMuZ2FsbGVyeS5uZXh0KG13LiQoXCIubXdfbW9kYWxfZ2FsbGVyeVwiKVswXS5tb2RhbClcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2UgaWYgKGUua2V5Q29kZSA9PT0gMTIyKSB7LyogRjExICovXG4gICAgICAgICAgICAgICAgbXcuZXZlbnQuY2FuY2VsKGUsIHRydWUpO1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLnRvZ2dsZUZ1bGxzY3JlZW4obXcuJChcIi5td19tb2RhbF9nYWxsZXJ5XCIpWzBdKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBpZiAoZS5rZXlDb2RlID09PSAyNykge1xuICAgICAgICAgICAgICAgIHZhciBtb2RhbCA9IG13LiQoXCIubXdfbW9kYWw6bGFzdFwiKVswXTtcbiAgICAgICAgICAgICAgICBpZiAobW9kYWwpIG1vZGFsLm1vZGFsLnJlbW92ZSgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSk7XG5cbiAgICBtdy4kKFwiLm13LWltYWdlLWhvbGRlclwiKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaWYgKCQoXCIubXctaW1hZ2UtaG9sZGVyLW92ZXJsYXlcIiwgdGhpcykubGVuZ3RoID09PSAwKSB7XG4gICAgICAgICAgICBtdy4kKCdpbWcnLCB0aGlzKS5lcSgwKS5hZnRlcignPHNwYW4gY2xhc3M9XCJtdy1pbWFnZS1ob2xkZXItb3ZlcmxheVwiPjwvc3Bhbj4nKTtcbiAgICAgICAgfVxuICAgIH0pO1xuXG4gICAgbXcuJChcIi5tdy11aS1kcm9wZG93blwiKS5vbigndG91Y2hzdGFydCBtb3VzZWRvd24nLCBmdW5jdGlvbigpe1xuICAgICAgICBtdy4kKHRoaXMpLnRvZ2dsZUNsYXNzKCdhY3RpdmUnKVxuICAgIH0pO1xuICAgIG13LiQoZG9jdW1lbnQuYm9keSkub24oJ3RvdWNoZW5kJywgZnVuY3Rpb24oZSl7XG4gICAgICAgIGlmKCFtdy50b29scy5oYXNBbnlPZkNsYXNzZXNPbk5vZGVPclBhcmVudChlLnRhcmdldCwgWydtdy11aS1kcm9wZG93biddKSl7XG4gICAgICAgICAgICBtdy4kKFwiLm13LXVpLWRyb3Bkb3duLmFjdGl2ZVwiKS5yZW1vdmVDbGFzcygnYWN0aXZlJylcbiAgICAgICAgfVxuICAgIH0pO1xuICAgIG13LiQoZG9jdW1lbnQuYm9keSkub24oJ2NsaWNrJywgJ2EnLCBmdW5jdGlvbihlKXtcbiAgICAgICAgaWYobG9jYXRpb24uaGFzaC5pbmRleE9mKCcjbXdAJykgIT09IC0xICYmIChlLnRhcmdldC5ocmVmIHx8ICcnKS5pbmRleE9mKCcjbXdAJykgIT09IC0xKXtcbiAgICAgICAgICAgIGlmKGxvY2F0aW9uLmhyZWYgPT09IGUudGFyZ2V0LmhyZWYpe1xuICAgICAgICAgICAgICAgIHZhciBlbCA9IG13LiQoJyMnICsgZS50YXJnZXQuaHJlZi5zcGxpdCgnbXdAJylbMV0pWzBdO1xuICAgICAgICAgICAgICAgIGlmKGVsKXtcbiAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMuc2Nyb2xsVG8oZWwpXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSlcblxuXG59KTtcbiIsIm13LmNvb2tpZSA9IHtcbiAgICBnZXQ6IGZ1bmN0aW9uIChuYW1lKSB7XG4gICAgICAgIHZhciBjb29raWVzID0gbXdkLmNvb2tpZS5zcGxpdChcIjtcIiksIGkgPSAwLCBsID0gY29va2llcy5sZW5ndGg7XG4gICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICB2YXIgeCA9IGNvb2tpZXNbaV0uc3Vic3RyKDAsIGNvb2tpZXNbaV0uaW5kZXhPZihcIj1cIikpO1xuICAgICAgICAgICAgdmFyIHkgPSBjb29raWVzW2ldLnN1YnN0cihjb29raWVzW2ldLmluZGV4T2YoXCI9XCIpICsgMSk7XG4gICAgICAgICAgICB4ID0geC5yZXBsYWNlKC9eXFxzK3xcXHMrJC9nLCBcIlwiKTtcbiAgICAgICAgICAgIGlmICh4ID09PSBuYW1lKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHVuZXNjYXBlKHkpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSxcbiAgICBzZXQ6IGZ1bmN0aW9uIChuYW1lLCB2YWx1ZSwgZXhwaXJlcywgcGF0aCwgZG9tYWluLCBzZWN1cmUpIHtcbiAgICAgICAgdmFyIG5vdyA9IG5ldyBEYXRlKCk7XG4gICAgICAgIGV4cGlyZXMgPSBleHBpcmVzIHx8IDM2NTtcbiAgICAgICAgbm93LnNldFRpbWUobm93LmdldFRpbWUoKSk7XG4gICAgICAgIGlmIChleHBpcmVzKSB7XG4gICAgICAgICAgICBleHBpcmVzID0gZXhwaXJlcyAqIDEwMDAgKiA2MCAqIDYwICogMjQ7XG4gICAgICAgIH1cbiAgICAgICAgdmFyIGV4cGlyZXNfZGF0ZSA9IG5ldyBEYXRlKG5vdy5nZXRUaW1lKCkgKyAoZXhwaXJlcykpO1xuICAgICAgICBkb2N1bWVudC5jb29raWUgPSBuYW1lICsgXCI9XCIgKyBlc2NhcGUodmFsdWUpICsgKCAoIGV4cGlyZXMgKSA/IFwiO2V4cGlyZXM9XCIgKyBleHBpcmVzX2RhdGUudG9HTVRTdHJpbmcoKSA6IFwiXCIgKSArICggKCBwYXRoICkgPyBcIjtwYXRoPVwiICsgcGF0aCA6IFwiO3BhdGg9L1wiICkgKyAoICggZG9tYWluICkgPyBcIjtkb21haW49XCIgKyBkb21haW4gOiBcIlwiICkgKyAoICggc2VjdXJlICkgPyBcIjtzZWN1cmVcIiA6IFwiXCIgKTtcbiAgICB9LFxuICAgIHNldEVuY29kZWQ6IGZ1bmN0aW9uIChuYW1lLCB2YWx1ZSwgZXhwaXJlcywgcGF0aCwgZG9tYWluLCBzZWN1cmUpIHtcblxuICAgICAgICB2YWx1ZSA9IG13LnRvb2xzLmJhc2U2NC5lbmNvZGUodmFsdWUpO1xuICAgICAgICByZXR1cm4gdGhpcy5zZXQobmFtZSwgdmFsdWUsIGV4cGlyZXMsIHBhdGgsIGRvbWFpbiwgc2VjdXJlKTtcbiAgICB9LFxuICAgIGdldEVuY29kZWQ6IGZ1bmN0aW9uIChuYW1lKSB7XG4gICAgICAgIHZhciB2YWx1ZSA9IHRoaXMuZ2V0KG5hbWUpO1xuXG4gICAgICAgIHZhbHVlID0gbXcudG9vbHMuYmFzZTY0LmRlY29kZSh2YWx1ZSk7XG4gICAgICAgIHJldHVybiB2YWx1ZTtcbiAgICB9LFxuICAgIHVpOiBmdW5jdGlvbiAoYSwgYikge1xuICAgICAgICB2YXIgbXd1aSA9IG13LmNvb2tpZS5nZXRFbmNvZGVkKFwibXd1aVwiKTtcbiAgICAgICAgdHJ5IHtcbiAgICAgICAgICAgIG13dWkgPSAoIW13dWkgfHwgbXd1aSA9PT0gJycpID8ge30gOiAkLnBhcnNlSlNPTihtd3VpKTtcbiAgICAgICAgfVxuICAgICAgICBjYXRjaCAoZSkge1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG4gICAgICAgIGlmICh0eXBlb2YgYSA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIHJldHVybiBtd3VpO1xuICAgICAgICB9XG4gICAgICAgIGlmICh0eXBlb2YgYiA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIHJldHVybiBtd3VpW2FdICE9PSB1bmRlZmluZWQgPyBtd3VpW2FdIDogXCJcIjtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIG13dWlbYV0gPSBiO1xuICAgICAgICAgICAgdmFyIHRvc3RyaW5nID0gSlNPTi5zdHJpbmdpZnkobXd1aSk7XG4gICAgICAgICAgICBtdy5jb29raWUuc2V0RW5jb2RlZChcIm13dWlcIiwgdG9zdHJpbmcsIGZhbHNlLCBcIi9cIik7XG4gICAgICAgICAgICBpZiAodHlwZW9mIG13LmNvb2tpZS51aWV2ZW50c1thXSAhPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICB2YXIgZnVuY3MgPSBtdy5jb29raWUudWlldmVudHNbYV0sIGwgPSBmdW5jcy5sZW5ndGgsIGkgPSAwO1xuICAgICAgICAgICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LmNvb2tpZS51aWV2ZW50c1thXVtpXS5jYWxsKGIudG9TdHJpbmcoKSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSxcbiAgICB1aWV2ZW50czoge30sXG4gICAgY2hhbmdlSW50ZXJ2YWw6IG51bGwsXG4gICAgdWlDdXJyOiBudWxsLFxuICAgIG9uY2hhbmdlOiBmdW5jdGlvbiAobmFtZSwgZnVuYykge1xuICAgICAgICBpZiAodHlwZW9mIG13LmNvb2tpZS51aWV2ZW50c1tuYW1lXSA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIG13LmNvb2tpZS51aWV2ZW50c1tuYW1lXSA9IFtmdW5jXTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIG13LmNvb2tpZS51aWV2ZW50c1tuYW1lXS5wdXNoKGZ1bmMpO1xuICAgICAgICB9XG4gICAgfVxufTtcbiIsIihmdW5jdGlvbigpe1xudmFyIGRvbUhlbHAgPSB7XG4gICAgY2xhc3NOYW1lc3BhY2VEZWxldGU6IGZ1bmN0aW9uIChlbF9vYmosIG5hbWVzcGFjZSwgcGFyZW50LCBuYW1lc3BhY2VQb3NpdGlvbiwgZXhjZXB0aW9uKSB7XG4gICAgICAgIGlmIChlbF9vYmouZWxlbWVudCAmJiBlbF9vYmoubmFtZXNwYWNlKSB7XG4gICAgICAgICAgICBlbCA9IGVsX29iai5lbGVtZW50O1xuICAgICAgICAgICAgbmFtZXNwYWNlID0gZWxfb2JqLm5hbWVzcGFjZTtcbiAgICAgICAgICAgIHBhcmVudCA9IGVsX29iai5wYXJlbnQ7XG4gICAgICAgICAgICBuYW1lc3BhY2VQb3NpdGlvbiA9IGVsX29iai5uYW1lc3BhY2VQb3NpdGlvbjtcbiAgICAgICAgICAgIGV4Y2VwdGlvbnMgPSBlbF9vYmouZXhjZXB0aW9ucyB8fCBbXTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIGVsID0gZWxfb2JqO1xuICAgICAgICAgICAgZXhjZXB0aW9ucyA9IFtdO1xuICAgICAgICB9XG4gICAgICAgIG5hbWVzcGFjZVBvc2l0aW9uID0gbmFtZXNwYWNlUG9zaXRpb24gfHwgJ2NvbnRhaW5zJztcbiAgICAgICAgcGFyZW50ID0gcGFyZW50IHx8IG13ZDtcbiAgICAgICAgaWYgKGVsID09PSAnYWxsJykge1xuICAgICAgICAgICAgdmFyIGFsbCA9IHBhcmVudC5xdWVyeVNlbGVjdG9yQWxsKCcuZWRpdCAqJyksIGkgPSAwLCBsID0gYWxsLmxlbmd0aDtcbiAgICAgICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgbXcudG9vbHMuY2xhc3NOYW1lc3BhY2VEZWxldGUoYWxsW2ldLCBuYW1lc3BhY2UsIHBhcmVudCwgbmFtZXNwYWNlUG9zaXRpb24pXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cbiAgICAgICAgaWYgKCEhZWwuY2xhc3NOYW1lICYmIHR5cGVvZihlbC5jbGFzc05hbWUuc3BsaXQpID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgICAgICB2YXIgY2xzID0gZWwuY2xhc3NOYW1lLnNwbGl0KFwiIFwiKSwgbCA9IGNscy5sZW5ndGgsIGkgPSAwLCBmaW5hbCA9IFtdO1xuICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgICAgICBpZiAobmFtZXNwYWNlUG9zaXRpb24gPT09ICdjb250YWlucycpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKCFjbHNbaV0uY29udGFpbnMobmFtZXNwYWNlKSB8fCBleGNlcHRpb25zLmluZGV4T2YoY2xzW2ldKSAhPT0gLTEpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGZpbmFsLnB1c2goY2xzW2ldKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIGlmIChuYW1lc3BhY2VQb3NpdGlvbiA9PT0gJ3N0YXJ0cycpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKGNsc1tpXS5pbmRleE9mKG5hbWVzcGFjZSkgIT09IDApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGZpbmFsLnB1c2goY2xzW2ldKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsLmNsYXNzTmFtZSA9IGZpbmFsLmpvaW4oXCIgXCIpO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBmaXJzdFdpdGhCYWNrZ3JvdW5kSW1hZ2U6IGZ1bmN0aW9uIChub2RlKSB7XG4gICAgICAgIGlmICghbm9kZSkgcmV0dXJuIGZhbHNlO1xuICAgICAgICBpZiAoISFub2RlLnN0eWxlLmJhY2tncm91bmRJbWFnZSkgcmV0dXJuIG5vZGU7XG4gICAgICAgIHZhciBmaW5hbCA9IGZhbHNlO1xuICAgICAgICBtdy50b29scy5mb3JlYWNoUGFyZW50cyhub2RlLCBmdW5jdGlvbiAobG9vcCkge1xuICAgICAgICAgICAgaWYgKCEhdGhpcy5zdHlsZS5iYWNrZ3JvdW5kSW1hZ2UpIHtcbiAgICAgICAgICAgICAgICBtdy50b29scy5zdG9wTG9vcChsb29wKTtcbiAgICAgICAgICAgICAgICBmaW5hbCA9IHRoaXM7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICByZXR1cm4gZmluYWw7XG4gICAgfSxcblxuICAgIHBhcmVudHNPckN1cnJlbnRPcmRlck1hdGNoT3JPbmx5Rmlyc3RPck5vbmU6IGZ1bmN0aW9uIChub2RlLCBhcnIpIHtcbiAgICAgICAgcmV0dXJuICFtdy50b29scy5oYXNBbnlPZkNsYXNzZXNPbk5vZGVPclBhcmVudChub2RlLCBbYXJyWzFdXSkgfHwgbXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck9ubHlGaXJzdChub2RlLCBhcnIpXG4gICAgfSxcbiAgICBwYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yT25seUZpcnN0OiBmdW5jdGlvbiAobm9kZSwgYXJyKSB7XG4gICAgICAgIHZhciBjdXJyID0gbm9kZTtcbiAgICAgICAgd2hpbGUgKGN1cnIgJiYgY3VyciAhPT0gZG9jdW1lbnQuYm9keSkge1xuICAgICAgICAgICAgdmFyIGgxID0gbXcudG9vbHMuaGFzQ2xhc3MoY3VyciwgYXJyWzBdKTtcbiAgICAgICAgICAgIHZhciBoMiA9IG13LnRvb2xzLmhhc0NsYXNzKGN1cnIsIGFyclsxXSk7XG4gICAgICAgICAgICBpZiAoaDEgJiYgaDIpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBpZiAoaDEpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2UgaWYgKGgyKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBjdXJyID0gY3Vyci5wYXJlbnROb2RlO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9LFxuICAgIHBhcmVudHNPckN1cnJlbnRPcmRlck1hdGNoT3JPbmx5Rmlyc3RPck5vbmU6IGZ1bmN0aW9uIChub2RlLCBhcnIpIHtcbiAgICAgICAgdmFyIGN1cnIgPSBub2RlO1xuICAgICAgICB3aGlsZSAoY3VyciAmJiBjdXJyICE9PSBtd2QuYm9keSkge1xuICAgICAgICAgICAgdmFyIGgxID0gbXcudG9vbHMuaGFzQ2xhc3MoY3VyciwgYXJyWzBdKTtcbiAgICAgICAgICAgIHZhciBoMiA9IG13LnRvb2xzLmhhc0NsYXNzKGN1cnIsIGFyclsxXSk7XG4gICAgICAgICAgICBpZiAoaDEgJiYgaDIpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBpZiAoaDEpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2UgaWYgKGgyKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBjdXJyID0gY3Vyci5wYXJlbnROb2RlO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiB0cnVlO1xuICAgIH0sXG4gICAgcGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2g6IGZ1bmN0aW9uIChub2RlLCBhcnIpIHtcbiAgICAgICAgdmFyIGN1cnIgPSBub2RlLFxuICAgICAgICAgICAgbWF0Y2ggPSB7YTogMCwgYjogMH0sXG4gICAgICAgICAgICBjb3VudCA9IDEsXG4gICAgICAgICAgICBoYWRBID0gZmFsc2U7XG4gICAgICAgIHdoaWxlIChjdXJyICE9PSBkb2N1bWVudC5ib2R5KSB7XG4gICAgICAgICAgICBjb3VudCsrO1xuICAgICAgICAgICAgdmFyIGgxID0gbXcudG9vbHMuaGFzQ2xhc3MoY3VyciwgYXJyWzBdKTtcbiAgICAgICAgICAgIHZhciBoMiA9IG13LnRvb2xzLmhhc0NsYXNzKGN1cnIsIGFyclsxXSk7XG4gICAgICAgICAgICBpZiAoaDEgJiYgaDIpIHtcbiAgICAgICAgICAgICAgICBpZiAobWF0Y2guYSA+IDApIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIGlmIChoMSkge1xuICAgICAgICAgICAgICAgICAgICBtYXRjaC5hID0gY291bnQ7XG4gICAgICAgICAgICAgICAgICAgIGhhZEEgPSB0cnVlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIGlmIChoMikge1xuICAgICAgICAgICAgICAgICAgICBtYXRjaC5iID0gY291bnQ7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmIChtYXRjaC5iID4gbWF0Y2guYSkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gaGFkQSA/IHRydWUgOiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBjdXJyID0gY3Vyci5wYXJlbnROb2RlO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9LFxuICAgIHBhcmVudHNPckN1cnJlbnRPcmRlck1hdGNoT3JOb25lOmZ1bmN0aW9uKG5vZGUsIGFycil7XG4gICAgICAgIGlmKCFub2RlKSByZXR1cm4gZmFsc2U7XG4gICAgICAgIHZhciBjdXJyID0gbm9kZSxcbiAgICAgICAgICAgIG1hdGNoID0ge2E6IDAsIGI6IDB9LFxuICAgICAgICAgICAgY291bnQgPSAxLFxuICAgICAgICAgICAgaGFkQSA9IGZhbHNlO1xuICAgICAgICB3aGlsZSAoY3VyciAmJiBjdXJyICE9PSBkb2N1bWVudC5ib2R5KSB7XG4gICAgICAgICAgICBjb3VudCsrO1xuICAgICAgICAgICAgdmFyIGgxID0gbXcudG9vbHMuaGFzQ2xhc3MoY3VyciwgYXJyWzBdKTtcbiAgICAgICAgICAgIHZhciBoMiA9IG13LnRvb2xzLmhhc0NsYXNzKGN1cnIsIGFyclsxXSk7XG4gICAgICAgICAgICBpZiAoaDEgJiYgaDIpIHtcbiAgICAgICAgICAgICAgICBpZiAobWF0Y2guYSA+IDApIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIGlmIChoMSkge1xuICAgICAgICAgICAgICAgICAgICBtYXRjaC5hID0gY291bnQ7XG4gICAgICAgICAgICAgICAgICAgIGhhZEEgPSB0cnVlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIGlmIChoMikge1xuICAgICAgICAgICAgICAgICAgICBtYXRjaC5iID0gY291bnQ7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmIChtYXRjaC5iID4gbWF0Y2guYSkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gaGFkQSA/IHRydWUgOiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBjdXJyID0gY3Vyci5wYXJlbnROb2RlO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiBtYXRjaC5hID09PSAwICYmIG1hdGNoLmIgPT09IDA7XG4gICAgfSxcbiAgICBwYXJlbnRzT3JDdXJyZW50T3JkZXJNYXRjaE9yT25seUZpcnN0T3JCb3RoOiBmdW5jdGlvbiAobm9kZSwgYXJyKSB7XG4gICAgICAgIHZhciBjdXJyID0gbm9kZSxcbiAgICAgICAgICAgIGhhczEgPSBmYWxzZSxcbiAgICAgICAgICAgIGhhczIgPSBmYWxzZTtcbiAgICAgICAgd2hpbGUgKGN1cnIgJiYgY3VyciAhPT0gZG9jdW1lbnQuYm9keSkge1xuICAgICAgICAgICAgdmFyIGgxID0gbXcudG9vbHMuaGFzQ2xhc3MoY3VyciwgYXJyWzBdKTtcbiAgICAgICAgICAgIHZhciBoMiA9IG13LnRvb2xzLmhhc0NsYXNzKGN1cnIsIGFyclsxXSk7XG4gICAgICAgICAgICBpZiAoaDEgJiYgaDIpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIGlmIChoMSkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZSBpZiAoaDIpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGN1cnIgPSBjdXJyLnBhcmVudE5vZGU7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG4gICAgbWF0Y2hlc0FueU9uTm9kZU9yUGFyZW50OiBmdW5jdGlvbiAobm9kZSwgYXJyKSB7XG4gICAgICAgIHZhciBjdXJyID0gbm9kZTtcbiAgICAgICAgd2hpbGUgKGN1cnIgJiYgY3VyciAhPT0gZG9jdW1lbnQuYm9keSkge1xuICAgICAgICAgICAgdmFyIGkgPSAwO1xuICAgICAgICAgICAgZm9yICg7IGkgPCBhcnIubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgICAgICBpZiAobXcudG9vbHMubWF0Y2hlcyhjdXJyLCBhcnJbaV0pKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGN1cnIgPSBjdXJyLnBhcmVudE5vZGU7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG4gICAgZmlyc3RNYXRjaGVzT25Ob2RlT3JQYXJlbnQ6IGZ1bmN0aW9uIChub2RlLCBhcnIpIHtcbiAgICAgICAgaWYgKCFhcnIpIHJldHVybjtcbiAgICAgICAgaWYgKHR5cGVvZiBhcnIgPT09ICdzdHJpbmcnKSB7XG4gICAgICAgICAgICBhcnIgPSBbYXJyXTtcbiAgICAgICAgfVxuICAgICAgICB2YXIgY3VyciA9IG5vZGU7XG4gICAgICAgIHdoaWxlIChjdXJyICYmIGN1cnIgIT09IGRvY3VtZW50LmJvZHkpIHtcbiAgICAgICAgICAgIHZhciBpID0gMDtcbiAgICAgICAgICAgIGZvciAoOyBpIDwgYXJyLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgaWYgKG13LnRvb2xzLm1hdGNoZXMoY3VyciwgYXJyW2ldKSkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gY3VycjtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBjdXJyID0gY3Vyci5wYXJlbnROb2RlO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9LFxuICAgIGxhc3RNYXRjaGVzT25Ob2RlT3JQYXJlbnQ6IGZ1bmN0aW9uIChub2RlLCBhcnIpIHtcbiAgICAgICAgaWYgKCFhcnIpIHJldHVybjtcbiAgICAgICAgaWYgKHR5cGVvZiBhcnIgPT09ICdzdHJpbmcnKSB7XG4gICAgICAgICAgICBhcnIgPSBbYXJyXTtcbiAgICAgICAgfVxuICAgICAgICB2YXIgY3VyciA9IG5vZGUsIHJlc3VsdDtcbiAgICAgICAgd2hpbGUgKGN1cnIgJiYgY3VyciAhPT0gZG9jdW1lbnQuYm9keSkge1xuICAgICAgICAgICAgdmFyIGkgPSAwO1xuICAgICAgICAgICAgZm9yICg7IGkgPCBhcnIubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgICAgICBpZiAobXcudG9vbHMubWF0Y2hlcyhjdXJyLCBhcnJbaV0pKSB7XG4gICAgICAgICAgICAgICAgICAgIHJlc3VsdCA9IGN1cnI7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgY3VyciA9IGN1cnIucGFyZW50Tm9kZTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gcmVzdWx0O1xuICAgIH0sXG4gICAgaGFzQW55T2ZDbGFzc2VzT25Ob2RlT3JQYXJlbnQ6IGZ1bmN0aW9uIChub2RlLCBhcnIpIHtcbiAgICAgICAgdmFyIGN1cnIgPSBub2RlO1xuICAgICAgICB3aGlsZSAoY3VyciAmJiBjdXJyICE9PSBkb2N1bWVudC5ib2R5KSB7XG4gICAgICAgICAgICB2YXIgaSA9IDA7XG4gICAgICAgICAgICBmb3IgKDsgaSA8IGFyci5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgICAgIGlmIChtdy50b29scy5oYXNDbGFzcyhjdXJyLCBhcnJbaV0pKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGN1cnIgPSBjdXJyLnBhcmVudE5vZGU7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG4gICAgaGFzQ2xhc3M6IGZ1bmN0aW9uIChjbGFzc25hbWUsIHdoYXR0b3NlYXJjaCkge1xuICAgICAgICBpZiAoY2xhc3NuYW1lID09PSBudWxsKSB7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKHR5cGVvZiBjbGFzc25hbWUgPT09ICdzdHJpbmcnKSB7XG4gICAgICAgICAgICByZXR1cm4gY2xhc3NuYW1lLnNwbGl0KCcgJykuaW5kZXhPZih3aGF0dG9zZWFyY2gpID4gLTE7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSBpZiAodHlwZW9mIGNsYXNzbmFtZSA9PT0gJ29iamVjdCcpIHtcbiAgICAgICAgICAgIHJldHVybiBtdy50b29scy5oYXNDbGFzcyhjbGFzc25hbWUuY2xhc3NOYW1lLCB3aGF0dG9zZWFyY2gpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBoYXNBbGxDbGFzc2VzOiBmdW5jdGlvbiAobm9kZSwgYXJyKSB7XG4gICAgICAgIGlmICghbm9kZSkgcmV0dXJuO1xuICAgICAgICB2YXIgaGFzID0gdHJ1ZTtcbiAgICAgICAgdmFyIGkgPSAwLCBub2RlYyA9IG5vZGUuY2xhc3NOYW1lLnRyaW0oKS5zcGxpdCgnICcpO1xuICAgICAgICBmb3IgKDsgaSA8IGFyci5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgaWYgKG5vZGVjLmluZGV4T2YoYXJyW2ldKSA9PT0gLTEpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGhhcztcbiAgICB9LFxuICAgIGhhc0FueU9mQ2xhc3NlczogZnVuY3Rpb24gKG5vZGUsIGFycikge1xuICAgICAgICBpZiAoIW5vZGUpIHJldHVybjtcbiAgICAgICAgdmFyIGkgPSAwLCBsID0gYXJyLmxlbmd0aCwgY2xzID0gbm9kZS5jbGFzc05hbWU7XG4gICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2xhc3MoY2xzLCBhcnJbaV0pKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG5cblxuICAgIGhhc1BhcmVudHNXaXRoQ2xhc3M6IGZ1bmN0aW9uIChlbCwgY2xzKSB7XG4gICAgICAgIGlmICghZWwpIHJldHVybjtcbiAgICAgICAgdmFyIGN1cnIgPSBlbC5wYXJlbnROb2RlO1xuICAgICAgICB3aGlsZSAoY3VyciAmJiBjdXJyICE9PSBtd2QuYm9keSkge1xuICAgICAgICAgICAgaWYgKG13LnRvb2xzLmhhc0NsYXNzKGN1cnIsIGNscykpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGN1cnIgPSBjdXJyLnBhcmVudE5vZGU7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG4gICAgaGFzUGFyZW50V2l0aElkOiBmdW5jdGlvbiAoZWwsIGlkKSB7XG4gICAgICAgIGlmICghZWwpIHJldHVybjtcbiAgICAgICAgdmFyIGN1cnIgPSBlbC5wYXJlbnROb2RlO1xuICAgICAgICB3aGlsZSAoY3VyciAmJiBjdXJyICE9PSBtd2QuYm9keSkge1xuICAgICAgICAgICAgaWYgKGN1cnIuaWQgPT09IGlkKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBjdXJyID0gY3Vyci5wYXJlbnROb2RlO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9LFxuXG4gICAgaGFzQ2hpbGRyZW5XaXRoVGFnOiBmdW5jdGlvbiAoZWwsIHRhZykge1xuICAgICAgICB2YXIgdGFnID0gdGFnLnRvTG93ZXJDYXNlKCk7XG4gICAgICAgIHZhciBoYXMgPSBmYWxzZTtcbiAgICAgICAgbXcudG9vbHMuZm9yZWFjaENoaWxkcmVuKGVsLCBmdW5jdGlvbiAobG9vcCkge1xuICAgICAgICAgICAgaWYgKHRoaXMubm9kZU5hbWUudG9Mb3dlckNhc2UoKSA9PT0gdGFnKSB7XG4gICAgICAgICAgICAgICAgaGFzID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICBtdy50b29scy5zdG9wTG9vcChsb29wKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICAgIHJldHVybiBoYXM7XG4gICAgfSxcbiAgICBoYXNQYXJlbnRzV2l0aFRhZzogZnVuY3Rpb24gKGVsLCB0YWcpIHtcbiAgICAgICAgaWYgKCFlbCB8fCAhdGFnKSByZXR1cm47XG4gICAgICAgIHRhZyA9IHRhZy50b0xvd2VyQ2FzZSgpO1xuICAgICAgICB2YXIgY3VyciA9IGVsLnBhcmVudE5vZGU7XG4gICAgICAgIHdoaWxlIChjdXJyICYmIGN1cnIgIT09IG13ZC5ib2R5KSB7XG4gICAgICAgICAgICBpZiAoY3Vyci5ub2RlTmFtZS50b0xvd2VyQ2FzZSgpID09PSB0YWcpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGN1cnIgPSBjdXJyLnBhcmVudE5vZGU7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG4gICAgaGFzSGVhZGluZ1BhcmVudDogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgIGlmICghZWwpIHJldHVybjtcbiAgICAgICAgdmFyIGggPSAvXihoWzEtNl0pJC9pO1xuICAgICAgICB2YXIgY3VyciA9IGVsLnBhcmVudE5vZGU7XG4gICAgICAgIHdoaWxlIChjdXJyICYmIGN1cnIgIT09IG13ZC5ib2R5KSB7XG4gICAgICAgICAgICBpZiAoaC50ZXN0KGN1cnIubm9kZU5hbWUudG9Mb3dlckNhc2UoKSkpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGN1cnIgPSBjdXJyLnBhcmVudE5vZGU7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG4gICAgYWRkQ2xhc3M6IGZ1bmN0aW9uIChlbCwgY2xzKSB7XG4gICAgICAgIGlmICghY2xzIHx8ICFlbCkge1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG4gICAgICAgIGlmIChlbC5mbikge1xuICAgICAgICAgICAgZWwgPSBlbFswXTtcbiAgICAgICAgICAgIGlmICghZWwpIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgaWYgKHR5cGVvZiBjbHMgPT09ICdzdHJpbmcnKSB7XG4gICAgICAgICAgICBjbHMgPSBjbHMudHJpbSgpO1xuICAgICAgICB9XG4gICAgICAgIGlmICghZWwpIHJldHVybjtcbiAgICAgICAgdmFyIGFyciA9IGNscy5zcGxpdChcIiBcIik7XG4gICAgICAgIHZhciBpID0gMDtcbiAgICAgICAgaWYgKGFyci5sZW5ndGggPiAxKSB7XG4gICAgICAgICAgICBmb3IgKDsgaSA8IGFyci5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLmFkZENsYXNzKGVsLCBhcnJbaV0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIGlmICh0eXBlb2YgZWwgPT09ICdvYmplY3QnKSB7XG4gICAgICAgICAgICBpZiAoZWwuY2xhc3NMaXN0KSB7XG4gICAgICAgICAgICAgICAgZWwuY2xhc3NMaXN0LmFkZChjbHMpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgaWYgKCFtdy50b29scy5oYXNDbGFzcyhlbC5jbGFzc05hbWUsIGNscykpIGVsLmNsYXNzTmFtZSArPSAoJyAnICsgY2xzKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBpZiAodHlwZW9mIGVsID09PSAnc3RyaW5nJykge1xuICAgICAgICAgICAgaWYgKCFtdy50b29scy5oYXNDbGFzcyhlbCwgY2xzKSkgZWwgKz0gKCcgJyArIGNscyk7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIHJlbW92ZUNsYXNzOiBmdW5jdGlvbiAoZWwsIGNscykge1xuICAgICAgICBpZiAodHlwZW9mIGNscyA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgICAgIGNscyA9IGNscy50cmltKCk7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKCFjbHMgfHwgIWVsKSByZXR1cm47XG4gICAgICAgIGlmIChlbCA9PT0gbnVsbCkge1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG4gICAgICAgIGlmIChlbC5mbikge1xuICAgICAgICAgICAgZWwgPSBlbFswXTtcbiAgICAgICAgICAgIGlmICghZWwpIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgaWYgKHR5cGVvZiBlbCA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoZWwuY29uc3RydWN0b3IgPT09IFtdLmNvbnN0cnVjdG9yKSB7XG4gICAgICAgICAgICB2YXIgaSA9IDAsIGwgPSBlbC5sZW5ndGg7XG4gICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLnJlbW92ZUNsYXNzKGVsW2ldLCBjbHMpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIGlmICh0eXBlb2YoY2xzKSA9PT0gJ29iamVjdCcpIHtcbiAgICAgICAgICAgIHZhciBhcnIgPSBjbHM7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB2YXIgYXJyID0gY2xzLnNwbGl0KFwiIFwiKTtcbiAgICAgICAgfVxuICAgICAgICB2YXIgaSA9IDA7XG4gICAgICAgIGlmIChhcnIubGVuZ3RoID4gMSkge1xuICAgICAgICAgICAgZm9yICg7IGkgPCBhcnIubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgICAgICBtdy50b29scy5yZW1vdmVDbGFzcyhlbCwgYXJyW2ldKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIGlmICghYXJyLmxlbmd0aCkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIGlmIChlbC5jbGFzc0xpc3QgJiYgY2xzKSB7XG4gICAgICAgICAgICBlbC5jbGFzc0xpc3QucmVtb3ZlKGNscyk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2xhc3MoZWwuY2xhc3NOYW1lLCBjbHMpKSBlbC5jbGFzc05hbWUgPSAoZWwuY2xhc3NOYW1lICsgJyAnKS5yZXBsYWNlKGNscyArICcgJywgJycpLnJlcGxhY2UoL1xcc3syLH0vZywgJyAnKS50cmltKCk7XG4gICAgICAgIH1cblxuICAgIH0sXG4gICAgaXNFdmVudE9uRWxlbWVudDogZnVuY3Rpb24gKGV2ZW50LCBub2RlKSB7XG4gICAgICAgIGlmIChldmVudC50YXJnZXQgPT09IG5vZGUpIHtcbiAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICB9XG4gICAgICAgIG13LnRvb2xzLmZvcmVhY2hQYXJlbnRzKGV2ZW50LnRhcmdldCwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgaWYgKGV2ZW50LnRhcmdldCA9PT0gbm9kZSkge1xuICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG4gICAgaXNFdmVudE9uRWxlbWVudHM6IGZ1bmN0aW9uIChldmVudCwgYXJyYXkpIHtcbiAgICAgICAgdmFyIGwgPSBhcnJheS5sZW5ndGgsIGkgPSAwO1xuICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgaWYgKGV2ZW50LnRhcmdldCA9PT0gYXJyYXlbaV0pIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICB2YXIgaXNFdmVudE9uRWxlbWVudHMgPSBmYWxzZTtcbiAgICAgICAgbXcudG9vbHMuZm9yZWFjaFBhcmVudHMoZXZlbnQudGFyZ2V0LCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgbCA9IGFycmF5Lmxlbmd0aCwgaSA9IDA7XG4gICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgIGlmIChldmVudC50YXJnZXQgPT09IGFycmF5W2ldKSB7XG4gICAgICAgICAgICAgICAgICAgIGlzRXZlbnRPbkVsZW1lbnRzID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICByZXR1cm4gaXNFdmVudE9uRWxlbWVudHM7XG4gICAgfSxcbiAgICBpc0V2ZW50T25DbGFzczogZnVuY3Rpb24gKGV2ZW50LCBjbHMpIHtcbiAgICAgICAgaWYgKG13LnRvb2xzLmhhc0NsYXNzKGV2ZW50LnRhcmdldCwgY2xzKSB8fCBtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKGV2ZW50LnRhcmdldCwgY2xzKSkge1xuICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG4gICAgZmlyc3RDaGlsZFdpdGhDbGFzczogZnVuY3Rpb24gKHBhcmVudCwgY2xzKSB7XG4gICAgICAgIHZhciB0b3JldHVybjtcbiAgICAgICAgbXcudG9vbHMuZm9yZWFjaENoaWxkcmVuKHBhcmVudCwgZnVuY3Rpb24gKGxvb3ApIHtcbiAgICAgICAgICAgIGlmICh0aGlzLm5vZGVUeXBlID09PSAxICYmIG13LnRvb2xzLmhhc0NsYXNzKHRoaXMsIGNscykpIHtcbiAgICAgICAgICAgICAgICBtdy50b29scy5zdG9wTG9vcChsb29wKTtcbiAgICAgICAgICAgICAgICB0b3JldHVybiA9IHRoaXM7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICByZXR1cm4gdG9yZXR1cm47XG4gICAgfSxcbiAgICBmaXJzdENoaWxkV2l0aFRhZzogZnVuY3Rpb24gKHBhcmVudCwgdGFnKSB7XG4gICAgICAgIHZhciB0b3JldHVybjtcbiAgICAgICAgdmFyIHRhZyA9IHRhZy50b0xvd2VyQ2FzZSgpO1xuICAgICAgICBtdy50b29scy5mb3JlYWNoQ2hpbGRyZW4ocGFyZW50LCBmdW5jdGlvbiAobG9vcCkge1xuICAgICAgICAgICAgaWYgKHRoaXMubm9kZU5hbWUudG9Mb3dlckNhc2UoKSA9PT0gdGFnKSB7XG4gICAgICAgICAgICAgICAgdG9yZXR1cm4gPSB0aGlzO1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLnN0b3BMb29wKGxvb3ApO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgcmV0dXJuIHRvcmV0dXJuO1xuICAgIH0sXG4gICAgaGFzQ2hpbGRyZW5XaXRoQ2xhc3M6IGZ1bmN0aW9uIChub2RlLCBjbHMpIHtcbiAgICAgICAgdmFyIGZpbmFsID0gZmFsc2U7XG4gICAgICAgIG13LnRvb2xzLmZvcmVhY2hDaGlsZHJlbihub2RlLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2xhc3ModGhpcy5jbGFzc05hbWUsIGNscykpIHtcbiAgICAgICAgICAgICAgICBmaW5hbCA9IHRydWU7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICByZXR1cm4gZmluYWw7XG4gICAgfSxcbiAgICBwYXJlbnRzT3JkZXI6IGZ1bmN0aW9uIChub2RlLCBhcnIpIHtcbiAgICAgICAgdmFyIG9ubHlfZmlyc3QgPSBbXTtcbiAgICAgICAgdmFyIG9iaiA9IHt9LCBsID0gYXJyLmxlbmd0aCwgaSA9IDAsIGNvdW50ID0gLTE7XG4gICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICBvYmpbYXJyW2ldXSA9IC0xO1xuICAgICAgICB9XG4gICAgICAgIGlmICghbm9kZSkgcmV0dXJuIG9iajtcblxuICAgICAgICB2YXIgY3VyciA9IG5vZGUucGFyZW50Tm9kZTtcbiAgICAgICAgd2hpbGUgKGN1cnIgJiYgY3VyciAhPT0gbXdkLmJvZHkpIHtcbiAgICAgICAgICAgIGNvdW50Kys7XG4gICAgICAgICAgICB2YXIgY2xzID0gY3Vyci5jbGFzc05hbWU7XG4gICAgICAgICAgICBpID0gMDtcbiAgICAgICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgaWYgKG13LnRvb2xzLmhhc0NsYXNzKGNscywgYXJyW2ldKSAmJiBvbmx5X2ZpcnN0LmluZGV4T2YoYXJyW2ldKSA9PT0gLTEpIHtcbiAgICAgICAgICAgICAgICAgICAgb2JqW2FycltpXV0gPSBjb3VudDtcbiAgICAgICAgICAgICAgICAgICAgb25seV9maXJzdC5wdXNoKGFycltpXSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgY3VyciA9IGN1cnIucGFyZW50Tm9kZTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gb2JqO1xuICAgIH0sXG4gICAgcGFyZW50c0FuZEN1cnJlbnRPcmRlcjogZnVuY3Rpb24gKG5vZGUsIGFycikge1xuICAgICAgICB2YXIgb25seV9maXJzdCA9IFtdO1xuICAgICAgICB2YXIgb2JqID0ge30sIGwgPSBhcnIubGVuZ3RoLCBpID0gMCwgY291bnQgPSAtMTtcbiAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgIG9ialthcnJbaV1dID0gLTE7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKCFub2RlKSByZXR1cm4gb2JqO1xuXG4gICAgICAgIHZhciBjdXJyID0gbm9kZTtcbiAgICAgICAgd2hpbGUgKGN1cnIgJiYgY3VyciAhPT0gbXdkLmJvZHkpIHtcbiAgICAgICAgICAgIGNvdW50Kys7XG4gICAgICAgICAgICB2YXIgY2xzID0gY3Vyci5jbGFzc05hbWU7XG4gICAgICAgICAgICBpID0gMDtcbiAgICAgICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgaWYgKG13LnRvb2xzLmhhc0NsYXNzKGNscywgYXJyW2ldKSAmJiBvbmx5X2ZpcnN0LmluZGV4T2YoYXJyW2ldKSA9PT0gLTEpIHtcbiAgICAgICAgICAgICAgICAgICAgb2JqW2FycltpXV0gPSBjb3VudDtcbiAgICAgICAgICAgICAgICAgICAgb25seV9maXJzdC5wdXNoKGFycltpXSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgY3VyciA9IGN1cnIucGFyZW50Tm9kZTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gb2JqO1xuICAgIH0sXG4gICAgZmlyc3RQYXJlbnRXaXRoQ2xhc3M6IGZ1bmN0aW9uIChlbCwgY2xzKSB7XG4gICAgICAgIGlmICghZWwpIHJldHVybiBmYWxzZTtcbiAgICAgICAgdmFyIGN1cnIgPSBlbC5wYXJlbnROb2RlO1xuICAgICAgICB3aGlsZSAoY3VyciAmJiBjdXJyICE9PSBtd2QuYm9keSkge1xuICAgICAgICAgICAgaWYgKGN1cnIuY2xhc3NMaXN0LmNvbnRhaW5zKGNscykpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gY3VycjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGN1cnIgPSBjdXJyLnBhcmVudE5vZGU7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG4gICAgZmlyc3RQYXJlbnRPckN1cnJlbnRXaXRoQ2xhc3M6IGZ1bmN0aW9uIChlbCwgY2xzKSB7XG4gICAgICAgIGlmICghZWwpIHJldHVybiBmYWxzZTtcbiAgICAgICAgdmFyIGN1cnIgPSBlbDtcbiAgICAgICAgd2hpbGUgKGN1cnIgJiYgY3VyciAhPT0gbXdkLmJvZHkpIHtcbiAgICAgICAgICAgIGlmIChtdy50b29scy5oYXNDbGFzcyhjdXJyLCBjbHMpKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGN1cnI7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBjdXJyID0gY3Vyci5wYXJlbnROb2RlO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9LFxuICAgIGZpcnN0QmxvY2tMZXZlbDogZnVuY3Rpb24gKGVsKSB7XG4gICAgICAgIHdoaWxlKGVsICYmIGVsICE9PSBkb2N1bWVudC5ib2R5KSB7XG4gICAgICAgICAgICBpZihtdy50b29scy5pc0Jsb2NrTGV2ZWwoZWwpKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGVsO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWwgPSBlbC5wYXJlbnROb2RlO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBmaXJzdE5vdElubGluZUxldmVsOiBmdW5jdGlvbiAoZWwpIHtcbiAgICAgICAgaWYoZWwubm9kZVR5cGUgIT09IDEpIHtcbiAgICAgICAgICAgIGVsID0gZWwucGFyZW50Tm9kZVxuICAgICAgICB9XG4gICAgICAgIGlmKCFlbCkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIHdoaWxlKGVsICYmIGVsICE9PSBkb2N1bWVudC5ib2R5KSB7XG4gICAgICAgICAgICBpZighbXcudG9vbHMuaXNJbmxpbmVMZXZlbChlbCkpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbCA9IGVsLnBhcmVudE5vZGU7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIGZpcnN0UGFyZW50T3JDdXJyZW50V2l0aElkOiBmdW5jdGlvbiAoZWwsIGlkKSB7XG4gICAgICAgIGlmICghZWwpIHJldHVybiBmYWxzZTtcbiAgICAgICAgdmFyIGN1cnIgPSBlbDtcbiAgICAgICAgd2hpbGUgKGN1cnIgJiYgY3VyciAhPT0gbXdkLmJvZHkpIHtcbiAgICAgICAgICAgIGlmIChjdXJyLmlkID09PSBpZCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBjdXJyO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgY3VyciA9IGN1cnIucGFyZW50Tm9kZTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfSxcbiAgICBmaXJzdFBhcmVudE9yQ3VycmVudFdpdGhBbGxDbGFzc2VzOiBmdW5jdGlvbiAobm9kZSwgYXJyKSB7XG4gICAgICAgIGlmICghbm9kZSkgcmV0dXJuIGZhbHNlO1xuICAgICAgICB2YXIgY3VyciA9IG5vZGU7XG4gICAgICAgIHdoaWxlIChjdXJyICYmIGN1cnIgIT09IG13ZC5ib2R5KSB7XG4gICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQWxsQ2xhc3NlcyhjdXJyLCBhcnIpKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGN1cnI7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBjdXJyID0gY3Vyci5wYXJlbnROb2RlO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9LFxuICAgIGZpcnN0UGFyZW50T3JDdXJyZW50V2l0aEFueU9mQ2xhc3NlczogZnVuY3Rpb24gKG5vZGUsIGFycikge1xuICAgICAgICBpZiAoIW5vZGUpIHJldHVybiBmYWxzZTtcbiAgICAgICAgdmFyIGN1cnIgPSBub2RlO1xuICAgICAgICB3aGlsZSAoY3VyciAmJiBjdXJyICE9PSBtd2QuYm9keSkge1xuICAgICAgICAgICAgaWYgKCFjdXJyKSByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzKGN1cnIsIGFycikpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gY3VycjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGN1cnIgPSBjdXJyLnBhcmVudE5vZGU7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0sXG4gICAgbGFzdFBhcmVudFdpdGhDbGFzczogZnVuY3Rpb24gKGVsLCBjbHMpIHtcbiAgICAgICAgaWYgKCFlbCkgcmV0dXJuO1xuICAgICAgICB2YXIgX2hhcyA9IGZhbHNlO1xuICAgICAgICB2YXIgY3VyciA9IGVsLnBhcmVudE5vZGU7XG4gICAgICAgIHdoaWxlIChjdXJyICYmIGN1cnIgIT09IG13ZC5ib2R5KSB7XG4gICAgICAgICAgICBpZiAobXcudG9vbHMuaGFzQ2xhc3MoY3VyciwgY2xzKSkge1xuICAgICAgICAgICAgICAgIF9oYXMgPSBjdXJyO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgY3VyciA9IGN1cnIucGFyZW50Tm9kZTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gX2hhcztcbiAgICB9LFxuICAgIGZpcnN0UGFyZW50V2l0aFRhZzogZnVuY3Rpb24gKGVsLCB0YWcpIHtcbiAgICAgICAgaWYgKCFlbCB8fCAhdGFnKSByZXR1cm47XG4gICAgICAgIHRhZyA9IHR5cGVvZiB0YWcgIT09ICdzdHJpbmcnID8gdGFnIDogW3RhZ107XG4gICAgICAgIHZhciBjdXJyID0gZWwucGFyZW50Tm9kZTtcbiAgICAgICAgd2hpbGUgKGN1cnIgJiYgY3VyciAhPT0gbXdkLmJvZHkpIHtcbiAgICAgICAgICAgIGlmICh0YWcuaW5kZXhPZihjdXJyLm5vZGVOYW1lLnRvTG93ZXJDYXNlKCkpICE9PSAtMSkge1xuICAgICAgICAgICAgICAgIHJldHVybiBjdXJyO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgY3VyciA9IGN1cnIucGFyZW50Tm9kZTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfSxcbiAgICBmaXJzdFBhcmVudE9yQ3VycmVudFdpdGhUYWc6IGZ1bmN0aW9uIChlbCwgdGFnKSB7XG4gICAgICAgIGlmICghZWwgfHwgIXRhZykgcmV0dXJuO1xuICAgICAgICB0YWcgPSB0eXBlb2YgdGFnICE9PSAnc3RyaW5nJyA/IHRhZyA6IFt0YWddO1xuICAgICAgICB2YXIgY3VyciA9IGVsO1xuICAgICAgICB3aGlsZSAoY3VyciAmJiBjdXJyICE9PSBtd2QuYm9keSkge1xuICAgICAgICAgICAgaWYgKHRhZy5pbmRleE9mKGN1cnIubm9kZU5hbWUudG9Mb3dlckNhc2UoKSkgIT09IC0xKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGN1cnI7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBjdXJyID0gY3Vyci5wYXJlbnROb2RlO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9LFxuICAgIGdlbmVyYXRlU2VsZWN0b3JGb3JOb2RlOiBmdW5jdGlvbiAobm9kZSkge1xuICAgICAgICBpZiAobm9kZSA9PT0gbnVsbCB8fCBub2RlLm5vZGVUeXBlID09PSAzKSB7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKG5vZGUubm9kZU5hbWUgPT09ICdCT0RZJykge1xuICAgICAgICAgICAgcmV0dXJuICdib2R5JztcbiAgICAgICAgfVxuICAgICAgICBpZiAoISFub2RlLmlkIC8qJiYgbm9kZS5pZC5pbmRleE9mKCdlbGVtZW50XycpID09PSAtMSovKSB7XG4gICAgICAgICAgICByZXR1cm4gJyMnICsgbm9kZS5pZDtcbiAgICAgICAgfVxuICAgICAgICBpZihtdy50b29scy5oYXNDbGFzcyhub2RlLCAnZWRpdCcpKXtcbiAgICAgICAgICAgIHZhciBmaWVsZCA9IG5vZGUuZ2V0QXR0cmlidXRlKCdmaWVsZCcpO1xuICAgICAgICAgICAgdmFyIHJlbCA9IG5vZGUuZ2V0QXR0cmlidXRlKCdyZWwnKTtcbiAgICAgICAgICAgIGlmKGZpZWxkICYmIHJlbCl7XG4gICAgICAgICAgICAgICAgcmV0dXJuICcuZWRpdFtmaWVsZD1cIicrZmllbGQrJ1wiXVtyZWw9XCInK3JlbCsnXCJdJztcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICB2YXIgZmlsdGVyID0gZnVuY3Rpb24oaXRlbSkge1xuICAgICAgICAgICAgcmV0dXJuIGl0ZW0gIT09ICdjaGFuZ2VkJ1xuICAgICAgICAgICAgICAgICYmIGl0ZW0gIT09ICdtb2R1bGUtb3ZlcidcbiAgICAgICAgICAgICAgICAmJiBpdGVtICE9PSAnbXctYmctbWFzaydcbiAgICAgICAgICAgICAgICAmJiBpdGVtICE9PSAnZWxlbWVudC1jdXJyZW50JztcbiAgICAgICAgfTtcbiAgICAgICAgdmFyIF9maW5hbCA9IG5vZGUuY2xhc3NOYW1lLnRyaW0oKSA/ICcuJyArIG5vZGUuY2xhc3NOYW1lLnRyaW0oKS5zcGxpdCgnICcpLmZpbHRlcihmaWx0ZXIpLmpvaW4oJy4nKSA6IG5vZGUubm9kZU5hbWUudG9Mb2NhbGVMb3dlckNhc2UoKTtcblxuXG4gICAgICAgIF9maW5hbCA9IF9maW5hbC5yZXBsYWNlKC9cXC5cXC4vZywgJy4nKTtcbiAgICAgICAgbXcudG9vbHMuZm9yZWFjaFBhcmVudHMobm9kZSwgZnVuY3Rpb24gKGxvb3ApIHtcbiAgICAgICAgICAgIGlmICh0aGlzLmlkIC8qJiYgbm9kZS5pZC5pbmRleE9mKCdlbGVtZW50XycpID09PSAtMSovKSB7XG4gICAgICAgICAgICAgICAgX2ZpbmFsID0gJyMnICsgdGhpcy5pZCArICcgPiAnICsgX2ZpbmFsO1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLnN0b3BMb29wKGxvb3ApO1xuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHZhciBuO1xuICAgICAgICAgICAgaWYgKHRoaXMuY2xhc3NOYW1lLnRyaW0oKSkge1xuICAgICAgICAgICAgICAgIG4gPSB0aGlzLm5vZGVOYW1lLnRvTG9jYWxlTG93ZXJDYXNlKCkgKyAnLicgKyB0aGlzLmNsYXNzTmFtZS50cmltKCkuc3BsaXQoJyAnKS5qb2luKCcuJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBuID0gdGhpcy5ub2RlTmFtZS50b0xvY2FsZUxvd2VyQ2FzZSgpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgX2ZpbmFsID0gbiArICcgPiAnICsgX2ZpbmFsO1xuICAgICAgICB9KTtcbiAgICAgICAgcmV0dXJuIF9maW5hbFxuICAgICAgICAgICAgLnJlcGxhY2UoLy5jaGFuZ2VkL2csICcnKVxuICAgICAgICAgICAgLnJlcGxhY2UoLy5lbGVtZW50LWN1cnJlbnQvZywgJycpXG4gICAgICAgICAgICAucmVwbGFjZSgvLm1vZHVsZS1vdmVyL2csICcnKTtcbiAgICB9XG59O1xuXG5mb3IgKHZhciBpIGluIGRvbUhlbHApIHtcbiAgICBtdy50b29sc1tpXSA9IGRvbUhlbHBbaV07XG59XG59KSgpO1xuIiwibXcudG9vbHMuZHJvcGRvd24gPSBmdW5jdGlvbiAocm9vdCkge1xuICAgIHJvb3QgPSByb290IHx8IG13ZC5ib2R5O1xuICAgIGlmIChyb290ID09PSBudWxsKSB7XG4gICAgICAgIHJldHVybjtcbiAgICB9XG4gICAgdmFyIGl0ZW1zID0gcm9vdC5xdWVyeVNlbGVjdG9yQWxsKFwiLm13LWRyb3Bkb3duXCIpLCBsID0gaXRlbXMubGVuZ3RoLCBpID0gMDtcbiAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICB2YXIgZWwgPSBpdGVtc1tpXTtcbiAgICAgICAgdmFyIGNscyA9IGVsLmNsYXNzTmFtZTtcbiAgICAgICAgaWYgKGVsLm13RHJvcGRvd25BY3RpdmF0ZWQpIHtcbiAgICAgICAgICAgIGNvbnRpbnVlO1xuICAgICAgICB9XG4gICAgICAgIGVsLm13RHJvcGRvd25BY3RpdmF0ZWQgPSB0cnVlO1xuICAgICAgICBlbC5oYXNJbnB1dCA9IGVsLnF1ZXJ5U2VsZWN0b3IoJ2lucHV0Lm13LWRyb3Bkb3duLWZpZWxkJykgIT09IG51bGw7XG4gICAgICAgIGlmIChlbC5oYXNJbnB1dCkge1xuICAgICAgICAgICAgdmFyIGlucHV0ID0gZWwucXVlcnlTZWxlY3RvcignaW5wdXQubXctZHJvcGRvd24tZmllbGQnKTtcbiAgICAgICAgICAgIGlucHV0LmRyb3Bkb3duID0gZWw7XG4gICAgICAgICAgICBpbnB1dC5vbmtleWRvd24gPSBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGlmIChlLmtleUNvZGUgPT09IDEzKSB7XG4gICAgICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCh0aGlzLmRyb3Bkb3duKS5yZW1vdmVDbGFzcyhcImFjdGl2ZVwiKTtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCgnLm13LWRyb3Bkb3duLWNvbnRlbnQnLCB0aGlzLmRyb3Bkb3duKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgICAgIG13LiQodGhpcy5kcm9wZG93bikuc2V0RHJvcGRvd25WYWx1ZSh0aGlzLnZhbHVlLCB0cnVlLCB0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgIGlucHV0Lm9ua2V5dXAgPSBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgIGlmIChlLmtleUNvZGUgPT09IDEzKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICBtdy4kKGVsKS5vbihcImNsaWNrXCIsIGZ1bmN0aW9uIChldmVudCkge1xuICAgICAgICAgICAgaWYgKCQodGhpcykuaGFzQ2xhc3MoXCJkaXNhYmxlZFwiKSkge1xuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmICghbXcudG9vbHMuaGFzQ2xhc3MoZXZlbnQudGFyZ2V0LmNsYXNzTmFtZSwgJ213LWRyb3Bkb3duLWNvbnRlbnQnKSAmJiAhbXcudG9vbHMuaGFzQ2xhc3MoZXZlbnQudGFyZ2V0LmNsYXNzTmFtZSwgJ2RkX3NlYXJjaCcpKSB7XG4gICAgICAgICAgICAgICAgaWYgKHRoaXMucXVlcnlTZWxlY3RvcignaW5wdXQubXctZHJvcGRvd24tZmllbGQnKSAhPT0gbnVsbCAmJiAhbXcudG9vbHMuaGFzQ2xhc3ModGhpcywgJ2FjdGl2ZScpICYmIG13LnRvb2xzLmhhc1BhcmVudHNXaXRoQ2xhc3MoZXZlbnQudGFyZ2V0LCAnbXctZHJvcGRvd24tdmFsdWUnKSkge1xuICAgICAgICAgICAgICAgICAgICBpZiAodGhpcy5oYXNJbnB1dCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGlucHV0ID0gdGhpcy5xdWVyeVNlbGVjdG9yKCdpbnB1dC5tdy1kcm9wZG93bi1maWVsZCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgaW5wdXQudmFsdWUgPSBtdy4kKHRoaXMpLmdldERyb3Bkb3duVmFsdWUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13Lnd5c2l3eWcuc2F2ZV9zZWxlY3Rpb24odHJ1ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKGlucHV0KS5mb2N1cygpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIG13LiQodGhpcykudG9nZ2xlQ2xhc3MoXCJhY3RpdmVcIik7XG4gICAgICAgICAgICAgICAgbXcuJChcIi5tdy1kcm9wZG93blwiKS5ub3QodGhpcykucmVtb3ZlQ2xhc3MoXCJhY3RpdmVcIikuZmluZChcIi5tdy1kcm9wZG93bi1jb250ZW50XCIpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICBpZiAobXcuJChcIi5vdGhlci1hY3Rpb24taG92ZXJcIiwgdGhpcykubGVuZ3RoID09PSAwKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBpdGVtID0gbXcuJChcIi5tdy1kcm9wZG93bi1jb250ZW50XCIsIHRoaXMpO1xuICAgICAgICAgICAgICAgICAgICBpZiAoaXRlbS5pcyhcIjp2aXNpYmxlXCIpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpdGVtLmhpZGUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGl0ZW0uZm9jdXMoKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGl0ZW0uc2hvdygpO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGV2ZW50LnRhcmdldC50eXBlICE9PSAndGV4dCcpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0cnkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLnF1ZXJ5U2VsZWN0b3IoXCJpbnB1dC5kZF9zZWFyY2hcIikuZm9jdXMoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9IGNhdGNoIChlKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgbXcuJChlbClcbiAgICAgICAgICAgIC5ob3ZlcihmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgbXcuJCh0aGlzKS5hZGQodGhpcyk7XG4gICAgICAgICAgICAgICAgaWYgKG13LnRvb2xzLmhhc0NsYXNzKGNscywgJ290aGVyLWFjdGlvbicpKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LiQodGhpcykuYWRkQ2xhc3MoJ290aGVyLWFjdGlvbicpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0sIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLnJlbW92ZUNsYXNzKFwiaG92ZXJcIik7XG4gICAgICAgICAgICAgICAgbXcuJCh0aGlzKS5yZW1vdmVDbGFzcygnb3RoZXItYWN0aW9uJyk7XG4gICAgICAgICAgICB9KVxuICAgICAgICAgICAgLm9uKCdtb3VzZWRvd24gdG91Y2hzdGFydCcsICdsaVt2YWx1ZV0nLCBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgICAgICAgICBtdy4kKG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aENsYXNzKHRoaXMsICdtdy1kcm9wZG93bicpKS5zZXREcm9wZG93blZhbHVlKHRoaXMuZ2V0QXR0cmlidXRlKCd2YWx1ZScpLCB0cnVlKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9KVxuICAgICAgICAgICAgLm9uKCdjbGljaycsICdhW2hyZWY9XCIjXCJdJywgZnVuY3Rpb24gKGV2ZW50KSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfSk7XG4gICAgfVxuICAgIC8qIGVuZCBGb3IgbG9vcCAqL1xuICAgIGlmICh0eXBlb2YgbXcudG9vbHMuZHJvcGRvd25BY3RpdmF0ZWQgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgIG13LnRvb2xzLmRyb3Bkb3duQWN0aXZhdGVkID0gdHJ1ZTtcbiAgICAgICAgbXcuJChtd2QuYm9keSkub24oJ21vdXNlZG93biB0b3VjaHN0YXJ0JywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgIGlmICghbXcudG9vbHMuaGFzQW55T2ZDbGFzc2VzT25Ob2RlT3JQYXJlbnQoZS50YXJnZXQsIFsnbXctZHJvcGRvd24tY29udGVudCcsICdtdy1kcm9wZG93biddKSkge1xuICAgICAgICAgICAgICAgIG13LiQoXCIubXctZHJvcGRvd25cIikucmVtb3ZlQ2xhc3MoXCJhY3RpdmVcIik7XG4gICAgICAgICAgICAgICAgbXcuJChcIi5tdy1kcm9wZG93bi1jb250ZW50XCIpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICBpZihzZWxmICE9PSB0b3ApIHtcbiAgICAgICAgICAgICAgICAgICAgdHJ5IHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LnRvcCgpLiQoXCIubXctZHJvcGRvd25cIikucmVtb3ZlQ2xhc3MoXCJhY3RpdmVcIik7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy50b3AoKS4kKFwiLm13LWRyb3Bkb3duLWNvbnRlbnRcIikuaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICB9IGNhdGNoKGUpe1xuXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH1cbn07XG5cblxubXcuZHJvcGRvd24gPSBtdy50b29scy5kcm9wZG93bjtcbiIsIm13LmdldEV4dHJhZGF0YUZvcm1EYXRhID0gZnVuY3Rpb24gKGRhdGEsIGNhbGwpIHtcclxuXHJcbiAgICBpZiAoZGF0YS5mb3JtX2RhdGFfcmVxdWlyZWQpIHtcclxuICAgICAgICBpZiAoIWRhdGEuZm9ybV9kYXRhX21vZHVsZV9wYXJhbXMpIHtcclxuICAgICAgICAgICAgZGF0YS5mb3JtX2RhdGFfbW9kdWxlX3BhcmFtcyA9IHt9O1xyXG4gICAgICAgIH1cclxuICAgICAgICBkYXRhLmZvcm1fZGF0YV9tb2R1bGVfcGFyYW1zLl9jb25maXJtID0gMVxyXG4gICAgfVxyXG5cclxuXHJcbiAgICBpZiAoZGF0YS5mb3JtX2RhdGFfcmVxdWlyZWRfcGFyYW1zKSB7XHJcbiAgICAgICAgZGF0YS5mb3JtX2RhdGFfbW9kdWxlX3BhcmFtcyA9ICQuZXh0ZW5kKHt9LCBkYXRhLmZvcm1fZGF0YV9yZXF1aXJlZF9wYXJhbXMsZGF0YS5mb3JtX2RhdGFfbW9kdWxlX3BhcmFtcyk7XHJcbiAgICB9XHJcblxyXG4gICAgaWYgKGRhdGEuZm9ybV9kYXRhX21vZHVsZSkge1xyXG4gICAgICAgIG13LmxvYWRNb2R1bGVEYXRhKGRhdGEuZm9ybV9kYXRhX21vZHVsZSwgZnVuY3Rpb24gKG1vZHVsZWRhdGEpIHtcclxuICAgICAgICAgICAgY2FsbC5jYWxsKHVuZGVmaW5lZCwgbW9kdWxlZGF0YSk7XHJcbiAgICAgICAgfSwgbnVsbCwgZGF0YS5mb3JtX2RhdGFfbW9kdWxlX3BhcmFtcyk7XHJcbiAgICB9XHJcbiAgICBlbHNlIHtcclxuICAgICAgICBjYWxsLmNhbGwodW5kZWZpbmVkLCBkYXRhLmZvcm1fZGF0YV9yZXF1aXJlZCk7XHJcbiAgICB9XHJcbn1cclxuXHJcbm13LmV4dHJhZGF0YUZvcm0gPSBmdW5jdGlvbiAob3B0aW9ucywgZGF0YSkge1xyXG4gICAgaWYgKG9wdGlvbnMuX3N1Y2Nlc3MpIHtcclxuICAgICAgICBvcHRpb25zLnN1Y2Nlc3MgPSBvcHRpb25zLl9zdWNjZXNzO1xyXG4gICAgICAgIGRlbGV0ZSBvcHRpb25zLl9zdWNjZXNzO1xyXG4gICAgfVxyXG4gICAgbXcuZ2V0RXh0cmFkYXRhRm9ybURhdGEoZGF0YSwgZnVuY3Rpb24gKGV4dHJhX2h0bWwpIHtcclxuICAgICAgICB2YXIgZm9ybSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2Zvcm0nKTtcclxuICAgICAgICBtdy4kKGZvcm0pLmFwcGVuZChleHRyYV9odG1sKTtcclxuXHJcbiAgICAgICAgaWYoZGF0YS5mb3JtX2RhdGFfcmVxdWlyZWQpe1xyXG4gICAgICAgICAgICBtdy4kKGZvcm0pLmFwcGVuZCgnPGhyPjxidXR0b24gdHlwZT1cInN1Ym1pdFwiIGNsYXNzPVwibXctdWktYnRuIHB1bGwtcmlnaHQgbXctdWktYnRuLWludmVydFwiPicgKyBtdy5sYW5nKCdTdWJtaXQnKSArICc8L2J1dHRvbj4nKTtcclxuICAgICAgICB9XHJcblxyXG5cclxuXHJcbiAgICAgICAgZm9ybS5hY3Rpb24gPSBvcHRpb25zLnVybDtcclxuICAgICAgICBmb3JtLm1ldGhvZCA9IG9wdGlvbnMudHlwZTtcclxuICAgICAgICBmb3JtLl9fbW9kYWwgPSBtdy5kaWFsb2coe1xyXG4gICAgICAgICAgICBjb250ZW50OiBmb3JtLFxyXG4gICAgICAgICAgICB0aXRsZTogZGF0YS5lcnJvcixcclxuICAgICAgICAgICAgY2xvc2VCdXR0b246IGZhbHNlLFxyXG4gICAgICAgICAgICBjbG9zZU9uRXNjYXBlOiBmYWxzZVxyXG4gICAgICAgIH0pO1xyXG4gICAgICAgIG13LiQoJ3NjcmlwdCcsIGZvcm0pLmVhY2goZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgICAgIGV2YWwoJCh0aGlzKS50ZXh0KCkpO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICAkKGZvcm0uX19tb2RhbCkub24oJ2Nsb3NlZEJ5VXNlcicsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgaWYob3B0aW9ucy5vbkV4dGVybmFsRGF0YURpYWxvZ0Nsb3NlKSB7XHJcbiAgICAgICAgICAgICAgICBvcHRpb25zLm9uRXh0ZXJuYWxEYXRhRGlhbG9nQ2xvc2UuY2FsbCgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcblxyXG5cclxuXHJcbiAgICAgICAgaWYoZGF0YS5mb3JtX2RhdGFfcmVxdWlyZWQpIHtcclxuICAgICAgICAgICAgbXcuJChmb3JtKS5vbignc3VibWl0JywgZnVuY3Rpb24gKGUpIHtcclxuXHJcblxyXG5cclxuXHJcbiAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgICAgICB2YXIgZXhkYXRhID0gbXcuc2VyaWFsaXplRmllbGRzKHRoaXMpO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmKHR5cGVvZiBvcHRpb25zLmRhdGEgPT09ICdzdHJpbmcnKXtcclxuICAgICAgICAgICAgICAgICAgICB2YXIgcGFyYW1zID0ge307XHJcbiAgICAgICAgICAgICAgICAgICAgb3B0aW9ucy5kYXRhLnNwbGl0KCcmJykuZm9yRWFjaChmdW5jdGlvbihhKXtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGMgPSBhLnNwbGl0KCc9Jyk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHBhcmFtc1tjWzBdXSA9IGRlY29kZVVSSUNvbXBvbmVudChjWzFdKTtcclxuICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICBvcHRpb25zLmRhdGEgPSBwYXJhbXM7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBmb3IgKHZhciBpIGluIGV4ZGF0YSkge1xyXG4gICAgICAgICAgICAgICAgICAgIG9wdGlvbnMuZGF0YVtpXSA9IGV4ZGF0YVtpXTtcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICBpZihvcHRpb25zLmRhdGEuY2FwdGNoYSl7XHJcbiAgICAgICAgICAgICAgICAgICAvLyBtdy50b3AoKS5cclxuICAgICAgICAgICAgICAgICAgIC8vICgnZGF0YS1jYXB0Y2hhLXZhbHVlJylcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICBtdy5hamF4KG9wdGlvbnMpO1xyXG4gICAgICAgICAgICAgICAgZm9ybS5fX21vZGFsLnJlbW92ZSgpO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9XHJcbiAgICB9KTtcclxufTtcclxuIiwiKGZ1bmN0aW9uKGV4cG9zZSl7XHJcbiAgIHZhciBmdWxsc2NyZWVuID0ge1xyXG4gICAgZnVsbHNjcmVlbjogZnVuY3Rpb24gKGVsKSB7XHJcbiAgICAgICAgaWYgKGVsLnJlcXVlc3RGdWxsU2NyZWVuKSB7XHJcbiAgICAgICAgICAgIGVsLnJlcXVlc3RGdWxsU2NyZWVuKCk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGVsc2UgaWYgKGVsLndlYmtpdFJlcXVlc3RGdWxsU2NyZWVuKSB7XHJcbiAgICAgICAgICAgIGVsLndlYmtpdFJlcXVlc3RGdWxsU2NyZWVuKCk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGVsc2UgaWYgKGVsLm1velJlcXVlc3RGdWxsU2NyZWVuKSB7XHJcbiAgICAgICAgICAgIGVsLm1velJlcXVlc3RGdWxsU2NyZWVuKCk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGVsc2UgaWYgKGVsLm1zUmVxdWVzdEZ1bGxzY3JlZW4pIHtcclxuICAgICAgICAgICAgZWwubXNSZXF1ZXN0RnVsbHNjcmVlbigpO1xyXG4gICAgICAgIH1cclxuICAgIH0sXHJcbiAgICBpc0Z1bGxzY3JlZW5BdmFpbGFibGU6IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICB2YXIgYiA9IG13ZC5ib2R5O1xyXG4gICAgICAgIHJldHVybiAncmVxdWVzdEZ1bGxTY3JlZW4nIGluIGIgfHwgJ3dlYmtpdFJlcXVlc3RGdWxsU2NyZWVuJyBpbiBiIHx8ICdtb3pSZXF1ZXN0RnVsbFNjcmVlbicgaW4gYiB8fCAnbXNSZXF1ZXN0RnVsbHNjcmVlbicgaW4gYiB8fCBmYWxzZTtcclxuICAgIH0sXHJcbiAgICBjYW5jZWxGdWxsc2NyZWVuOiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgaWYgKG13ZC5leGl0RnVsbHNjcmVlbikge1xyXG4gICAgICAgICAgICBtd2QuZXhpdEZ1bGxzY3JlZW4oKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgZWxzZSBpZiAobXdkLm1vekNhbmNlbEZ1bGxTY3JlZW4pIHtcclxuICAgICAgICAgICAgbXdkLm1vekNhbmNlbEZ1bGxTY3JlZW4oKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgZWxzZSBpZiAobXdkLndlYmtpdEV4aXRGdWxsc2NyZWVuKSB7XHJcbiAgICAgICAgICAgIG13ZC53ZWJraXRFeGl0RnVsbHNjcmVlbigpO1xyXG4gICAgICAgIH1cclxuICAgICAgICBlbHNlIGlmIChtd2QubXNFeGl0RnVsbHNjcmVlbikge1xyXG4gICAgICAgICAgICBtd2QubXNFeGl0RnVsbHNjcmVlbigpO1xyXG4gICAgICAgIH1cclxuICAgIH0sXHJcbiAgICB0b2dnbGVGdWxsc2NyZWVuOiBmdW5jdGlvbiAoZWwpIHtcclxuICAgICAgICB2YXIgaW5mdWxsc2NyZWVuID0gbXdkLmZ1bGxTY3JlZW4gfHwgbXdkLndlYmtpdElzRnVsbFNjcmVlbiB8fCBtd2QubW96RnVsbFNjcmVlbiB8fCBmYWxzZTtcclxuICAgICAgICBpZiAoaW5mdWxsc2NyZWVuKSB7XHJcbiAgICAgICAgICAgIG13LnRvb2xzLmNhbmNlbEZ1bGxzY3JlZW4oKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgIG13LnRvb2xzLmZ1bGxzY3JlZW4oZWwpXHJcbiAgICAgICAgfVxyXG4gICAgfSxcclxuICAgIGZ1bGxzY3JlZW5DaGFuZ2U6IGZ1bmN0aW9uIChjKSB7XHJcbiAgICAgICAgaWYgKCdhZGRFdmVudExpc3RlbmVyJyBpbiBkb2N1bWVudCkge1xyXG4gICAgICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiZnVsbHNjcmVlbmNoYW5nZVwiLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBjLmNhbGwoZG9jdW1lbnQuZnVsbHNjcmVlbik7XHJcbiAgICAgICAgICAgIH0sIGZhbHNlKTtcclxuICAgICAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcIm1vemZ1bGxzY3JlZW5jaGFuZ2VcIiwgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgYy5jYWxsKGRvY3VtZW50Lm1vekZ1bGxTY3JlZW4pO1xyXG4gICAgICAgICAgICB9LCBmYWxzZSk7XHJcbiAgICAgICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJ3ZWJraXRmdWxsc2NyZWVuY2hhbmdlXCIsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIGMuY2FsbChkb2N1bWVudC53ZWJraXRJc0Z1bGxTY3JlZW4pO1xyXG4gICAgICAgICAgICB9LCBmYWxzZSk7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG4gICB9O1xyXG4gICAgT2JqZWN0LmFzc2lnbihleHBvc2UsIGZ1bGxzY3JlZW4pO1xyXG5cclxufSkobXcudG9vbHMpO1xyXG4iLCIoZnVuY3Rpb24oZXhwb3NlKXtcbiAgICB2YXIgaGVscGVycyA9IHtcbiAgICAgICAgZnJhZ21lbnQ6IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBpZighdGhpcy5fZnJhZ21lbnQpe1xuICAgICAgICAgICAgICAgIHRoaXMuX2ZyYWdtZW50ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgICAgICAgICAgdGhpcy5fZnJhZ21lbnQuc3R5bGUudmlzaWJpbGl0eSA9ICdoaWRkZW4nO1xuICAgICAgICAgICAgICAgIHRoaXMuX2ZyYWdtZW50LnN0eWxlLnBvc2l0aW9uID0gJ2Fic29sdXRlJztcbiAgICAgICAgICAgICAgICB0aGlzLl9mcmFnbWVudC5zdHlsZS53aWR0aCA9ICcxcHgnO1xuICAgICAgICAgICAgICAgIHRoaXMuX2ZyYWdtZW50LnN0eWxlLmhlaWdodCA9ICcxcHgnO1xuICAgICAgICAgICAgICAgIGRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQodGhpcy5fZnJhZ21lbnQpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIHRoaXMuX2ZyYWdtZW50O1xuICAgICAgICB9LFxuICAgICAgICBfaXNCbG9ja0NhY2hlOnt9LFxuICAgICAgICBpc0Jsb2NrTGV2ZWw6ZnVuY3Rpb24obm9kZSl7XG4gICAgICAgICAgICBpZighbm9kZSB8fCBub2RlLm5vZGVUeXBlID09PSAzKXtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgbmFtZSA9IG5vZGUubm9kZU5hbWU7XG4gICAgICAgICAgICBpZih0eXBlb2YgdGhpcy5faXNCbG9ja0NhY2hlW25hbWVdICE9PSAndW5kZWZpbmVkJyl7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMuX2lzQmxvY2tDYWNoZVtuYW1lXTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHZhciB0ZXN0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChuYW1lKTtcbiAgICAgICAgICAgIHRoaXMuZnJhZ21lbnQoKS5hcHBlbmRDaGlsZCh0ZXN0KTtcbiAgICAgICAgICAgIHRoaXMuX2lzQmxvY2tDYWNoZVtuYW1lXSA9IGdldENvbXB1dGVkU3R5bGUodGVzdCkuZGlzcGxheSA9PT0gJ2Jsb2NrJztcbiAgICAgICAgICAgIHRoaXMuZnJhZ21lbnQoKS5yZW1vdmVDaGlsZCh0ZXN0KTtcbiAgICAgICAgICAgIHJldHVybiB0aGlzLl9pc0Jsb2NrQ2FjaGVbbmFtZV07XG4gICAgICAgIH0sXG4gICAgICAgIF9pc0lubGluZUNhY2hlOnt9LFxuICAgICAgICBpc0lubGluZUxldmVsOmZ1bmN0aW9uKG5vZGUpe1xuICAgICAgICAgICAgaWYobm9kZS5ub2RlVHlwZSA9PT0gMyl7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIG5hbWUgPSBub2RlLm5vZGVOYW1lO1xuICAgICAgICAgICAgaWYodHlwZW9mIHRoaXMuX2lzSW5saW5lQ2FjaGVbbmFtZV0gIT09ICd1bmRlZmluZWQnKXtcbiAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5faXNJbmxpbmVDYWNoZVtuYW1lXTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHZhciB0ZXN0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChuYW1lKTtcbiAgICAgICAgICAgIHRoaXMuZnJhZ21lbnQoKS5hcHBlbmRDaGlsZCh0ZXN0KTtcbiAgICAgICAgICAgIHRoaXMuX2lzSW5saW5lQ2FjaGVbbmFtZV0gPSBnZXRDb21wdXRlZFN0eWxlKHRlc3QpLmRpc3BsYXkgPT09ICdpbmxpbmUnICYmIG5vZGUubm9kZU5hbWUgIT09ICdCUic7XG4gICAgICAgICAgICB0aGlzLmZyYWdtZW50KCkucmVtb3ZlQ2hpbGQodGVzdCk7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5faXNJbmxpbmVDYWNoZVtuYW1lXTtcbiAgICAgICAgfSxcbiAgICAgICAgZWxlbWVudE9wdGlvbnM6IGZ1bmN0aW9uKGVsKSB7XG4gICAgICAgICAgICB2YXIgb3B0ID0gKCBlbC5kYXRhc2V0Lm9wdGlvbnMgfHwgJycpLnRyaW0oKS5zcGxpdCgnLCcpLCBmaW5hbCA9IHt9O1xuICAgICAgICAgICAgaWYoIW9wdFswXSkgcmV0dXJuIGZpbmFsO1xuICAgICAgICAgICAgJC5lYWNoKG9wdCwgZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICB2YXIgYXJyID0gdGhpcy5zcGxpdCgnOicpO1xuICAgICAgICAgICAgICAgIHZhciB2YWwgPSBhcnJbMV0udHJpbSgpO1xuICAgICAgICAgICAgICAgIGlmKCF2YWwpe1xuXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2UgaWYodmFsID09PSAndHJ1ZScgfHwgdmFsID09PSAnZmFsc2UnKXtcbiAgICAgICAgICAgICAgICAgICAgdmFsID0gdmFsID09PSAndHJ1ZSc7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2UgaWYoIS9cXEQvLnRlc3QodmFsKSl7XG4gICAgICAgICAgICAgICAgICAgIHZhbCA9IHBhcnNlSW50KHZhbCwgMTApO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBmaW5hbFthcnJbMF0udHJpbSgpXSA9IHZhbDtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgcmV0dXJuIGZpbmFsO1xuICAgICAgICB9LFxuICAgICAgICBjcmVhdGVBdXRvSGVpZ2h0OiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIGlmKHdpbmRvdy50aGlzbW9kYWwgJiYgdGhpc21vZGFsLmlmcmFtZSkge1xuICAgICAgICAgICAgICAgIG13LnRvb2xzLmlmcmFtZUF1dG9IZWlnaHQodGhpc21vZGFsLmlmcmFtZSwgJ25vdycpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSBpZihtdy50b3AoKS53aW4uZnJhbWVFbGVtZW50ICYmIG13LnRvcCgpLndpbi5mcmFtZUVsZW1lbnQuY29udGVudFdpbmRvdyA9PT0gd2luZG93KSB7XG4gICAgICAgICAgICAgICAgbXcudG9vbHMuaWZyYW1lQXV0b0hlaWdodChtdy50b3AoKS53aW4uZnJhbWVFbGVtZW50LCAnbm93Jyk7XG4gICAgICAgICAgICB9IGVsc2UgaWYod2luZG93LnRvcCAhPT0gd2luZG93KSB7XG4gICAgICAgICAgICAgICAgbXcudG9wKCkuJCgnaWZyYW1lJykuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgICAgICAgICB0cnl7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZih0aGlzLmNvbnRlbnRXaW5kb3cgPT09IHdpbmRvdykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LnRvb2xzLmlmcmFtZUF1dG9IZWlnaHQodGhpcywgJ25vdycpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9IGNhdGNoKGUpe31cbiAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBjb2xsaXNpb246IGZ1bmN0aW9uKGVsMSwgZWwyKXtcbiAgICAgICAgICAgIGlmKCFlbDEgfHwgIWVsMikgcmV0dXJuO1xuICAgICAgICAgICAgZWwxID0gbXcuJChlbDEpO1xuICAgICAgICAgICAgZWwyID0gbXcuJChlbDIpO1xuICAgICAgICAgICAgdmFyIG8xID0gZWwxLm9mZnNldCgpO1xuICAgICAgICAgICAgdmFyIG8yID0gZWwyLm9mZnNldCgpO1xuICAgICAgICAgICAgbzEud2lkdGggPSBlbDEud2lkdGgoKTtcbiAgICAgICAgICAgIG8xLmhlaWdodCA9IGVsMS5oZWlnaHQoKTtcbiAgICAgICAgICAgIG8yLndpZHRoID0gZWwyLndpZHRoKCk7XG4gICAgICAgICAgICBvMi5oZWlnaHQgPSBlbDIuaGVpZ2h0KCk7XG4gICAgICAgICAgICByZXR1cm4gKG8xLmxlZnQgPCBvMi5sZWZ0ICsgbzIud2lkdGggICYmIG8xLmxlZnQgKyBvMS53aWR0aCAgPiBvMi5sZWZ0ICYmICBvMS50b3AgPCBvMi50b3AgKyBvMi5oZWlnaHQgJiYgbzEudG9wICsgbzEuaGVpZ2h0ID4gbzIudG9wKTtcbiAgICAgICAgfSxcbiAgICAgICAgZGlzdGFuY2U6IGZ1bmN0aW9uICh4MSwgeTEsIHgyLCB5Mikge1xuICAgICAgICAgICAgdmFyIGEgPSB4MSAtIHgyO1xuICAgICAgICAgICAgdmFyIGIgPSB5MSAtIHkyO1xuICAgICAgICAgICAgcmV0dXJuIE1hdGguZmxvb3IoTWF0aC5zcXJ0KGEgKiBhICsgYiAqIGIpKTtcbiAgICAgICAgfSxcbiAgICAgICAgY29weTogZnVuY3Rpb24gKHZhbHVlKSB7XG4gICAgICAgICAgICB2YXIgdGVtcElucHV0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImlucHV0XCIpO1xuICAgICAgICAgICAgdGVtcElucHV0LnN0eWxlID0gXCJwb3NpdGlvbjogYWJzb2x1dGU7IGxlZnQ6IC0xMDAwcHg7IHRvcDogLTEwMDBweFwiO1xuICAgICAgICAgICAgdGVtcElucHV0LnZhbHVlID0gdmFsdWU7XG4gICAgICAgICAgICBkb2N1bWVudC5ib2R5LmFwcGVuZENoaWxkKHRlbXBJbnB1dCk7XG4gICAgICAgICAgICB0ZW1wSW5wdXQuc2VsZWN0KCk7XG4gICAgICAgICAgICBkb2N1bWVudC5leGVjQ29tbWFuZChcImNvcHlcIik7XG4gICAgICAgICAgICBkb2N1bWVudC5ib2R5LnJlbW92ZUNoaWxkKHRlbXBJbnB1dCk7XG4gICAgICAgICAgICBtdy5ub3RpZmljYXRpb24uc3VjY2Vzcyhtdy5sYW5nKCdDb3BpZWQnKSArICc6IFwiJyArIHZhbHVlICsgJ1wiJyk7XG4gICAgICAgIH0sXG4gICAgICAgIGNsb25lT2JqZWN0OiBmdW5jdGlvbiAob2JqZWN0KSB7XG4gICAgICAgICAgICByZXR1cm4galF1ZXJ5LmV4dGVuZCh0cnVlLCB7fSwgb2JqZWN0KTtcbiAgICAgICAgfSxcbiAgICAgICAgY29uc3RydWN0aW9uczogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcuJChcIi5tdy1pbWFnZS1ob2xkZXJcIikuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIGltZyA9IHRoaXMucXVlcnlTZWxlY3RvcignaW1nJyk7XG4gICAgICAgICAgICAgICAgaWYgKGltZyAmJiBpbWcuc3JjKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LiQodGhpcykuY3NzKCdiYWNrZ3JvdW5kSW1hZ2UnLCAndXJsKCcgKyBpbWcuc3JjICsgJyknKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSxcbiAgICAgICAgaXNSdGw6IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICAgICAgLy90b2RvXG4gICAgICAgICAgICBlbCA9IGVsIHx8IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudDtcbiAgICAgICAgICAgIHJldHVybiBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuZGlyID09PSAncnRsJztcbiAgICAgICAgfSxcbiAgICAgICAgaXNFZGl0YWJsZTogZnVuY3Rpb24gKGl0ZW0pIHtcbiAgICAgICAgICAgIHZhciBlbCA9IGl0ZW07XG4gICAgICAgICAgICBpZiAoISFpdGVtLnR5cGUgJiYgISFpdGVtLnRhcmdldCkge1xuICAgICAgICAgICAgICAgIGVsID0gaXRlbS50YXJnZXQ7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gbXcudG9vbHMucGFyZW50c09yQ3VycmVudE9yZGVyTWF0Y2hPck9ubHlGaXJzdChlbCwgWydlZGl0JywgJ21vZHVsZSddKTtcbiAgICAgICAgfSxcbiAgICAgICAgZWFjaElmcmFtZTogZnVuY3Rpb24gKGNhbGxiYWNrLCByb290LCBpZ25vcmUpIHtcbiAgICAgICAgICAgIHJvb3QgPSByb290IHx8IGRvY3VtZW50O1xuICAgICAgICAgICAgdmFyIHNjb3BlID0gdGhpcztcbiAgICAgICAgICAgIGlnbm9yZSA9IGlnbm9yZSB8fCBbXTtcbiAgICAgICAgICAgIHZhciBhbGwgPSByb290LnF1ZXJ5U2VsZWN0b3JBbGwoJ2lmcmFtZScpLCBpID0gMDtcbiAgICAgICAgICAgIGZvciAoOyBpIDwgYWxsLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgaWYgKG13LnRvb2xzLmNhbkFjY2Vzc0lGcmFtZShhbGxbaV0pICYmIGlnbm9yZS5pbmRleE9mKGFsbFtpXSkgPT09IC0xKSB7XG4gICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwoYWxsW2ldLmNvbnRlbnRXaW5kb3csIGFsbFtpXS5jb250ZW50V2luZG93KTtcbiAgICAgICAgICAgICAgICAgICAgc2NvcGUuZWFjaElmcmFtZShjYWxsYmFjaywgYWxsW2ldLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSxcbiAgICAgICAgZWFjaFdpbmRvdzogZnVuY3Rpb24gKGNhbGxiYWNrLCBvcHRpb25zKSB7XG4gICAgICAgICAgICBvcHRpb25zID0gb3B0aW9ucyB8fCB7fTtcbiAgICAgICAgICAgIHZhciBjdXJyID0gd2luZG93O1xuICAgICAgICAgICAgY2FsbGJhY2suY2FsbChjdXJyLCBjdXJyKTtcbiAgICAgICAgICAgIHdoaWxlIChjdXJyICE9PSB0b3ApIHtcbiAgICAgICAgICAgICAgICB0aGlzLmVhY2hJZnJhbWUoZnVuY3Rpb24gKGlmcmFtZVdpbmRvdykge1xuICAgICAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKGlmcmFtZVdpbmRvdywgaWZyYW1lV2luZG93KTtcbiAgICAgICAgICAgICAgICB9LCBjdXJyLnBhcmVudC5kb2N1bWVudCwgW2N1cnJdKTtcbiAgICAgICAgICAgICAgICBjdXJyID0gY3Vyci5wYXJlbnQ7XG4gICAgICAgICAgICAgICAgY2FsbGJhY2suY2FsbChjdXJyLCBjdXJyKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMuZWFjaElmcmFtZShmdW5jdGlvbiAoaWZyYW1lV2luZG93KSB7XG4gICAgICAgICAgICAgICAgY2FsbGJhY2suY2FsbChpZnJhbWVXaW5kb3csIGlmcmFtZVdpbmRvdyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGlmICh3aW5kb3cub3BlbmVyICE9PSBudWxsICYmIG13LnRvb2xzLmNhbkFjY2Vzc1dpbmRvdyhvcGVuZXIpKSB7XG4gICAgICAgICAgICAgICAgY2FsbGJhY2suY2FsbCh3aW5kb3cub3BlbmVyLCB3aW5kb3cub3BlbmVyKTtcbiAgICAgICAgICAgICAgICB0aGlzLmVhY2hJZnJhbWUoZnVuY3Rpb24gKGlmcmFtZVdpbmRvdykge1xuICAgICAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKGlmcmFtZVdpbmRvdywgaWZyYW1lV2luZG93KTtcbiAgICAgICAgICAgICAgICB9LCB3aW5kb3cub3BlbmVyLmRvY3VtZW50KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSxcbiAgICAgICAgY2FuQWNjZXNzV2luZG93OiBmdW5jdGlvbiAod2luT2JqZWN0KSB7XG4gICAgICAgICAgICB2YXIgY2FuID0gZmFsc2U7XG4gICAgICAgICAgICB0cnkge1xuICAgICAgICAgICAgICAgIHZhciBkb2MgPSB3aW5PYmplY3QuZG9jdW1lbnQ7XG4gICAgICAgICAgICAgICAgY2FuID0gISFkb2MuYm9keTtcbiAgICAgICAgICAgIH0gY2F0Y2ggKGVycikge1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIGNhbjtcbiAgICAgICAgfSxcbiAgICAgICAgY2FuQWNjZXNzSUZyYW1lOiBmdW5jdGlvbiAoaWZyYW1lKSB7XG4gICAgICAgICAgICB2YXIgY2FuID0gZmFsc2U7XG4gICAgICAgICAgICB0cnkge1xuICAgICAgICAgICAgICAgIHZhciBkb2MgPSBpZnJhbWUuY29udGVudERvY3VtZW50IHx8IGlmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50O1xuICAgICAgICAgICAgICAgIGNhbiA9ICEhZG9jLmJvZHkgJiYgISFkb2MuZG9jdW1lbnRFbGVtZW50O1xuICAgICAgICAgICAgfSBjYXRjaCAoZXJyKSB7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gY2FuO1xuICAgICAgICB9LFxuICAgICAgICAgY3JlYXRlU3R5bGU6IGZ1bmN0aW9uIChjLCBjc3MsIGlucykge1xuICAgICAgICAgICAgaW5zID0gaW5zIHx8IG13ZC5nZXRFbGVtZW50c0J5VGFnTmFtZSgnaGVhZCcpWzBdO1xuICAgICAgICAgICAgdmFyIHN0eWxlID0gbXcuJChjKVswXTtcbiAgICAgICAgICAgIGlmICghc3R5bGUpIHtcbiAgICAgICAgICAgICAgICBzdHlsZSA9IG13ZC5jcmVhdGVFbGVtZW50KCdzdHlsZScpO1xuICAgICAgICAgICAgICAgIGlucy5hcHBlbmRDaGlsZChzdHlsZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBzdHlsZS5pbm5lckhUTUwgPSBjc3M7XG4gICAgICAgICAgICByZXR1cm4gc3R5bGU7XG4gICAgICAgIH0sXG4gICAgICAgIGNzc051bWJlcjogZnVuY3Rpb24gKHZhbCkge1xuICAgICAgICAgICAgdmFyIHVuaXRzID0gW1wicHhcIiwgXCIlXCIsIFwiaW5cIiwgXCJjbVwiLCBcIm1tXCIsIFwiZW1cIiwgXCJleFwiLCBcInB0XCIsIFwicGNcIl07XG4gICAgICAgICAgICBpZiAodHlwZW9mIHZhbCA9PT0gJ251bWJlcicpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gdmFsICsgJ3B4JztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2UgaWYgKHR5cGVvZiB2YWwgPT09ICdzdHJpbmcnICYmIHBhcnNlRmxvYXQodmFsKS50b1N0cmluZygpID09PSB2YWwpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gdmFsICsgJ3B4JztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIGlmIChpc05hTihwYXJzZUZsb2F0KHZhbCkpKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiAnMHB4JztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB2YWw7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuXG4gICAgICAgIH0sXG4gICAgICAgIGlzRmllbGQ6IGZ1bmN0aW9uICh0YXJnZXQpIHtcbiAgICAgICAgICAgIHZhciB0ID0gdGFyZ2V0Lm5vZGVOYW1lLnRvTG93ZXJDYXNlKCk7XG4gICAgICAgICAgICB2YXIgZmllbGRzID0gL14oaW5wdXR8dGV4dGFyZWF8c2VsZWN0KSQvaTtcbiAgICAgICAgICAgIHJldHVybiBmaWVsZHMudGVzdCh0KTtcbiAgICAgICAgfSxcblxuXG4gICAgICAgIHRvZ2dsZTogZnVuY3Rpb24gKHdobywgdG9nZ2xlciwgY2FsbGJhY2spIHtcbiAgICAgICAgICAgIHdobyA9IG13LiQod2hvKTtcbiAgICAgICAgICAgIHdoby50b2dnbGUoKTtcbiAgICAgICAgICAgIHdoby50b2dnbGVDbGFzcygndG9nZ2xlLWFjdGl2ZScpO1xuICAgICAgICAgICAgbXcuJCh0b2dnbGVyKS50b2dnbGVDbGFzcygndG9nZ2xlci1hY3RpdmUnKTtcbiAgICAgICAgICAgIG13LmlzLmZ1bmMoY2FsbGJhY2spID8gY2FsbGJhY2suY2FsbCh3aG8pIDogJyc7XG4gICAgICAgIH0sXG4gICAgICAgIF9jb25maXJtOiBmdW5jdGlvbiAocXVlc3Rpb24sIGNhbGxiYWNrKSB7XG4gICAgICAgICAgICB2YXIgY29uZiA9IGNvbmZpcm0ocXVlc3Rpb24pO1xuICAgICAgICAgICAgaWYgKGNvbmYgJiYgdHlwZW9mIGNhbGxiYWNrID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgICAgICAgICAgY2FsbGJhY2suY2FsbCh3aW5kb3cpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIGNvbmY7XG4gICAgICAgIH0sXG4gICAgICAgIGVsX3N3aXRjaDogZnVuY3Rpb24gKGFyciwgdHlwZSkge1xuICAgICAgICAgICAgaWYgKHR5cGUgPT09ICdzZW1pJykge1xuICAgICAgICAgICAgICAgIG13LiQoYXJyKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGVsID0gbXcuJCh0aGlzKTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKGVsLmhhc0NsYXNzKFwic2VtaV9oaWRkZW5cIikpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGVsLnJlbW92ZUNsYXNzKFwic2VtaV9oaWRkZW5cIik7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBlbC5hZGRDbGFzcyhcInNlbWlfaGlkZGVuXCIpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBtdy4kKGFycikuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBlbCA9IG13LiQodGhpcyk7XG4gICAgICAgICAgICAgICAgICAgIGlmIChlbC5jc3MoJ2Rpc3BsYXknKSA9PT0gJ25vbmUnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBlbC5zaG93KCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBlbC5oaWRlKCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSxcbiAgICAgICAgZm9jdXNfb246IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICAgICAgaWYgKCEkKGVsKS5oYXNDbGFzcygnbXctZm9jdXMnKSkge1xuICAgICAgICAgICAgICAgIG13LiQoXCIubXctZm9jdXNcIikuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMgIT09IGVsID8gbXcuJCh0aGlzKS5yZW1vdmVDbGFzcygnbXctZm9jdXMnKSA6ICcnO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIG13LiQoZWwpLmFkZENsYXNzKCdtdy1mb2N1cycpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBzY3JvbGxUbzogZnVuY3Rpb24gKGVsLCBjYWxsYmFjaywgbWludXMpIHtcbiAgICAgICAgICAgIG1pbnVzID0gbWludXMgfHwgMDtcbiAgICAgICAgICAgIGlmICgkKGVsKS5sZW5ndGggPT09IDApIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiAodHlwZW9mIGNhbGxiYWNrID09PSAnbnVtYmVyJykge1xuICAgICAgICAgICAgICAgIG1pbnVzID0gY2FsbGJhY2s7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBtdy4kKCdodG1sLGJvZHknKS5zdG9wKCkuYW5pbWF0ZSh7c2Nyb2xsVG9wOiBtdy4kKGVsKS5vZmZzZXQoKS50b3AgLSBtaW51c30sIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB0eXBlb2YgY2FsbGJhY2sgPT09ICdmdW5jdGlvbicgPyBjYWxsYmFjay5jYWxsKGVsKSA6ICcnO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0sXG4gICAgICAgIGFjY29yZGlvbjogZnVuY3Rpb24gKGVsLCBjYWxsYmFjaykge1xuICAgICAgICAgICAgbXcucmVxdWlyZSgnY3NzX3BhcnNlci5qcycpO1xuICAgICAgICAgICAgdmFyIHNwZWVkID0gMjAwO1xuICAgICAgICAgICAgdmFyIGNvbnRhaW5lciA9IGVsLnF1ZXJ5U2VsZWN0b3IoJy5tdy1hY2NvcmRpb24tY29udGVudCcpO1xuICAgICAgICAgICAgaWYgKGNvbnRhaW5lciA9PT0gbnVsbCkgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgdmFyIGlzX2hpZGRlbiA9IG13LkNTU1BhcnNlcihjb250YWluZXIpLmdldC5kaXNwbGF5KCkgPT09ICdub25lJztcbiAgICAgICAgICAgIGlmICghJChjb250YWluZXIpLmlzKFwiOmFuaW1hdGVkXCIpKSB7XG4gICAgICAgICAgICAgICAgaWYgKGlzX2hpZGRlbikge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKGNvbnRhaW5lcikuc2xpZGVEb3duKHNwZWVkLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKGVsKS5hZGRDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICB0eXBlb2YgY2FsbGJhY2sgPT09ICdmdW5jdGlvbicgPyBjYWxsYmFjay5jYWxsKGVsLCAndmlzaWJsZScpIDogJyc7XG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChjb250YWluZXIpLnNsaWRlVXAoc3BlZWQsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoZWwpLnJlbW92ZUNsYXNzKCdhY3RpdmUnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJyA/IGNhbGxiYWNrLmNhbGwoZWwsICdoaWRkZW4nKSA6ICcnO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0sXG4gICAgICAgIGluZGV4OiBmdW5jdGlvbiAoZWwsIHBhcmVudCwgc2VsZWN0b3IpIHtcbiAgICAgICAgICAgIGVsID0gbXcuJChlbClbMF07XG4gICAgICAgICAgICBzZWxlY3RvciA9IHNlbGVjdG9yIHx8IGVsLnRhZ05hbWUudG9Mb3dlckNhc2UoKTtcbiAgICAgICAgICAgIHBhcmVudCA9IHBhcmVudCB8fCBlbC5wYXJlbnROb2RlO1xuICAgICAgICAgICAgdmFyIGFsbDtcbiAgICAgICAgICAgIGlmIChwYXJlbnQuY29uc3RydWN0b3IgPT09IFtdLmNvbnN0cnVjdG9yKSB7XG4gICAgICAgICAgICAgICAgYWxsID0gcGFyZW50O1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgYWxsID0gbXcuJChzZWxlY3RvciwgcGFyZW50KVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIGkgPSAwLCBsID0gYWxsLmxlbmd0aDtcbiAgICAgICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgaWYgKGVsID09PSBhbGxbaV0pIHJldHVybiBpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuXG4gICAgICAgIGhpZ2hsaWdodDogZnVuY3Rpb24gKGVsLCBjb2xvciwgc3BlZWQxLCBzcGVlZDIpIHtcbiAgICAgICAgICAgIGlmICghZWwpIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIG13LiQoZWwpLnN0b3AoKTtcbiAgICAgICAgICAgIGNvbG9yID0gY29sb3IgfHwgJyM0OEFENzknO1xuICAgICAgICAgICAgc3BlZWQxID0gc3BlZWQxIHx8IDc3NztcbiAgICAgICAgICAgIHNwZWVkMiA9IHNwZWVkMiB8fCA3Nzc7XG4gICAgICAgICAgICB2YXIgY3VyciA9IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKGVsLCBudWxsKS5iYWNrZ3JvdW5kQ29sb3I7XG4gICAgICAgICAgICBpZiAoY3VyciA9PT0gJ3RyYW5zcGFyZW50Jykge1xuICAgICAgICAgICAgICAgIGN1cnIgPSAnI2ZmZmZmZic7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBtdy4kKGVsKS5hbmltYXRlKHtiYWNrZ3JvdW5kQ29sb3I6IGNvbG9yfSwgc3BlZWQxLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgbXcuJChlbCkuYW5pbWF0ZSh7YmFja2dyb3VuZENvbG9yOiBjdXJyfSwgc3BlZWQyLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIG13LiQoZWwpLmNzcygnYmFja2dyb3VuZENvbG9yJywgJycpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0sXG4gICAgICAgIGhpZ2hsaWdodFN0b3A6IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICAgICAgbXcuJChlbCkuc3RvcCgpO1xuICAgICAgICAgICAgbXcuJChlbCkuY3NzKCdiYWNrZ3JvdW5kQ29sb3InLCAnJyk7XG4gICAgICAgIH0sXG4gICAgICAgIHRvQ2FtZWxDYXNlOiBmdW5jdGlvbiAoc3RyKSB7XG4gICAgICAgICAgICByZXR1cm4gc3RyLnJlcGxhY2UoLyhcXC1bYS16XSkvZywgZnVuY3Rpb24gKGEpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gYS50b1VwcGVyQ2FzZSgpLnJlcGxhY2UoJy0nLCAnJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSxcbiAgICAgICAgbXVsdGlob3ZlcjogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIGwgPSBhcmd1bWVudHMubGVuZ3RoLCBpID0gMTtcbiAgICAgICAgICAgIHZhciB0eXBlID0gYXJndW1lbnRzWzBdLnR5cGU7XG4gICAgICAgICAgICB2YXIgY2hlY2sgPSAoIHR5cGUgPT09ICdtb3VzZW92ZXInIHx8IHR5cGUgPT09ICdtb3VzZWVudGVyJyk7XG4gICAgICAgICAgICBmb3IgKCA7IGkgPCBsOyBpKysgKSB7XG4gICAgICAgICAgICAgICAgY2hlY2sgPyBtdy4kKGFyZ3VtZW50c1tpXSkuYWRkQ2xhc3MoJ2hvdmVyZWQnKSA6IG13LiQoYXJndW1lbnRzW2ldKS5yZW1vdmVDbGFzcygnaG92ZXJlZCcpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBsaXN0U2VhcmNoOiBmdW5jdGlvbiAodmFsLCBsaXN0KSB7XG4gICAgICAgICAgICB2YWwgPSB2YWwudHJpbSgpLnRvTG93ZXJDYXNlKCk7XG4gICAgICAgICAgICBpZighdmFsKSB7XG4gICAgICAgICAgICAgICAgJCgnbGknLCBsaXN0KS5zaG93KCk7XG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdGhpcy5zZWFyY2godmFsLCAnbGknLCBmdW5jdGlvbiAoZm91bmQpIHtcbiAgICAgICAgICAgICAgICBpZihmb3VuZCkge1xuICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLnNob3coKTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIH0sIGxpc3QpO1xuICAgICAgICB9LFxuICAgICAgICBzZWFyY2g6IGZ1bmN0aW9uIChzdHJpbmcsIHNlbGVjdG9yLCBjYWxsYmFjaywgcm9vdCkge1xuICAgICAgICAgICAgcm9vdCA9ICEhcm9vdCA/ICQocm9vdClbMF0gOiBtd2Q7XG4gICAgICAgICAgICBpZiAoIXJvb3QpIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBzdHJpbmcgPSBzdHJpbmcudG9Mb3dlckNhc2UoKTtcbiAgICAgICAgICAgIHZhciBpdGVtcztcbiAgICAgICAgICAgIGlmICh0eXBlb2Ygc2VsZWN0b3IgPT09ICdvYmplY3QnKSB7XG4gICAgICAgICAgICAgICAgaXRlbXMgPSBzZWxlY3RvcjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2UgaWYgKHR5cGVvZiBzZWxlY3RvciA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgICAgICAgICBpdGVtcyA9IHJvb3QucXVlcnlTZWxlY3RvckFsbChzZWxlY3Rvcik7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgaSA9IDAsIGwgPSBpdGVtcy5sZW5ndGg7XG4gICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgIGl0ZW1zW2ldLnRleHRDb250ZW50LnRvTG93ZXJDYXNlKCkuY29udGFpbnMoc3RyaW5nKSA/IGNhbGxiYWNrLmNhbGwoaXRlbXNbaV0sIHRydWUpIDogY2FsbGJhY2suY2FsbChpdGVtc1tpXSwgZmFsc2UpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBhamF4SXNTZWFyY2hpbmc6IGZhbHNlLFxuICAgICAgICBhamF4U2VhcmNTZXR0aW5nOiB7XG4gICAgICAgICAgICBsaW1pdDogMTAsXG4gICAgICAgICAgICBrZXl3b3JkOiAnJyxcbiAgICAgICAgICAgIG9yZGVyX2J5OiAndXBkYXRlZF9hdCBkZXNjJyxcbiAgICAgICAgICAgIHNlYXJjaF9pbl9maWVsZHM6ICd0aXRsZSdcbiAgICAgICAgfSxcbiAgICAgICAgYWpheFNlYXJjaDogZnVuY3Rpb24gKG8sIGNhbGxiYWNrKSB7XG4gICAgICAgICAgICBpZiAoIW13LnRvb2xzLmFqYXhJc1NlYXJjaGluZykge1xuICAgICAgICAgICAgICAgIHZhciBvYmogPSAkLmV4dGVuZCh7fSwgbXcudG9vbHMuYWpheFNlYXJjU2V0dGluZywgbyk7XG4gICAgICAgICAgICAgICAgbXcudG9vbHMuYWpheElzU2VhcmNoaW5nID0gJC5wb3N0KG13LnNldHRpbmdzLnNpdGVfdXJsICsgXCJhcGkvZ2V0X2NvbnRlbnRfYWRtaW5cIiwgb2JqLCBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGNhbGxiYWNrID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKGRhdGEsIGRhdGEpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSkuYWx3YXlzKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcudG9vbHMuYWpheElzU2VhcmNoaW5nID0gZmFsc2VcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBtdy50b29scy5hamF4SXNTZWFyY2hpbmc7XG4gICAgICAgIH0sXG4gICAgICAgIGlmcmFtZUxpbmtzVG9QYXJlbnQ6IGZ1bmN0aW9uIChpZnJhbWUpIHtcbiAgICAgICAgICAgIG13LiQoaWZyYW1lKS5jb250ZW50cygpLmZpbmQoJ2EnKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgaHJlZiA9IHRoaXMuaHJlZjtcbiAgICAgICAgICAgICAgICBpZiAoaHJlZi5jb250YWlucyhtdy5zZXR0aW5ncy5zaXRlX3VybCkpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy50YXJnZXQgPSAnX3BhcmVudCc7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0sXG4gICAgICAgIGdldF9maWxlbmFtZTogZnVuY3Rpb24gKHMpIHtcbiAgICAgICAgICAgIGlmIChzLmNvbnRhaW5zKCcuJykpIHtcbiAgICAgICAgICAgICAgICB2YXIgZCA9IHMubGFzdEluZGV4T2YoJy4nKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gcy5zdWJzdHJpbmcocy5sYXN0SW5kZXhPZignLycpICsgMSwgZCA8IDAgPyBzLmxlbmd0aCA6IGQpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHVuZGVmaW5lZDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSxcbiAgICAgICAgaXNfZmllbGQ6IGZ1bmN0aW9uIChvYmopIHtcbiAgICAgICAgICAgIGlmIChvYmogPT09IG51bGwgfHwgdHlwZW9mIG9iaiA9PT0gJ3VuZGVmaW5lZCcgfHwgb2JqLm5vZGVUeXBlID09PSAzKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKCFvYmoubm9kZU5hbWUpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgdCA9IG9iai5ub2RlTmFtZS50b0xvd2VyQ2FzZSgpO1xuICAgICAgICAgICAgaWYgKHQgPT09ICdpbnB1dCcgfHwgdCA9PT0gJ3RleHRhcmVhJyB8fCB0ID09PSAnc2VsZWN0Jykge1xuICAgICAgICAgICAgICAgIHJldHVybiB0cnVlXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH0sXG4gICAgICAgIGdldEF0dHJzOiBmdW5jdGlvbiAoZWwpIHtcbiAgICAgICAgICAgIHZhciBhdHRycyA9IGVsLmF0dHJpYnV0ZXM7XG4gICAgICAgICAgICB2YXIgb2JqID0ge307XG4gICAgICAgICAgICBmb3IgKHZhciB4IGluIGF0dHJzKSB7XG4gICAgICAgICAgICAgICAgaWYgKGF0dHJzW3hdLm5vZGVOYW1lKSB7XG4gICAgICAgICAgICAgICAgICAgIG9ialthdHRyc1t4XS5ub2RlTmFtZV0gPSBhdHRyc1t4XS5ub2RlVmFsdWU7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIG9iajtcbiAgICAgICAgfSxcbiAgICAgICAgY29weUF0dHJpYnV0ZXM6IGZ1bmN0aW9uIChmcm9tLCB0bywgZXhjZXB0KSB7XG4gICAgICAgICAgICBleGNlcHQgPSBleGNlcHQgfHwgW107XG4gICAgICAgICAgICB2YXIgYXR0cnMgPSBtdy50b29scy5nZXRBdHRycyhmcm9tKTtcbiAgICAgICAgICAgIGlmIChtdy50b29scy5pc19maWVsZChmcm9tKSAmJiBtdy50b29scy5pc19maWVsZCh0bykpIHRvLnZhbHVlID0gZnJvbS52YWx1ZTtcbiAgICAgICAgICAgIGZvciAodmFyIHggaW4gYXR0cnMpIHtcbiAgICAgICAgICAgICAgICAoICQuaW5BcnJheSh4LCBleGNlcHQpID09IC0xICYmIHggIT0gJ3VuZGVmaW5lZCcpID8gdG8uc2V0QXR0cmlidXRlKHgsIGF0dHJzW3hdKSA6ICcnO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBpc0VtcHR5T2JqZWN0OiBmdW5jdGlvbiAob2JqKSB7XG4gICAgICAgICAgICBmb3IgKHZhciBhIGluIG9iaikge1xuICAgICAgICAgICAgICAgIGlmIChvYmouaGFzT3duUHJvcGVydHkoYSkpIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICB9LFxuICAgICAgICBvYmpMZW5naHQ6IGZ1bmN0aW9uIChvYmopIHtcbiAgICAgICAgICAgIHZhciBsZW4gPSAwLCB4O1xuICAgICAgICAgICAgaWYgKG9iai5jb25zdHJ1Y3RvciA9PT0ge30uY29uc3RydWN0b3IpIHtcbiAgICAgICAgICAgICAgICBmb3IgKCB4IGluIG9iaiApIHtcbiAgICAgICAgICAgICAgICAgICAgbGVuKys7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIGxlbjtcbiAgICAgICAgfSxcbiAgICAgICAgc2NhbGVUbzogZnVuY3Rpb24gKHNlbGVjdG9yLCB3LCBoKSB7XG4gICAgICAgICAgICB3ID0gdyB8fCA4MDA7XG4gICAgICAgICAgICBoID0gaCB8fCA2MDA7XG4gICAgICAgICAgICB2YXIgaXNfcGVyY2VudCA9IHcudG9TdHJpbmcoKS5jb250YWlucyhcIiVcIikgPyB0cnVlIDogZmFsc2U7XG4gICAgICAgICAgICB2YXIgaXRlbSA9IG13LiQoc2VsZWN0b3IpO1xuICAgICAgICAgICAgaWYgKGl0ZW0uaGFzQ2xhc3MoJ213LXNjYWxldG8nKSB8fCB3ID09ICdjbG9zZScpIHtcbiAgICAgICAgICAgICAgICBpdGVtLnJlbW92ZUNsYXNzKCdtdy1zY2FsZXRvJyk7XG4gICAgICAgICAgICAgICAgaXRlbS5yZW1vdmVBdHRyKCdzdHlsZScpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgaXRlbS5wYXJlbnQoKS5oZWlnaHQoaXRlbS5oZWlnaHQoKSk7XG4gICAgICAgICAgICAgICAgaXRlbS5hZGRDbGFzcygnbXctc2NhbGV0bycpO1xuICAgICAgICAgICAgICAgIGlmIChpc19wZXJjZW50KSB7XG4gICAgICAgICAgICAgICAgICAgIGl0ZW0uY3NzKHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHdpZHRoOiB3LFxuICAgICAgICAgICAgICAgICAgICAgICAgaGVpZ2h0OiBoLFxuICAgICAgICAgICAgICAgICAgICAgICAgbGVmdDogKCgxMDAgLSBwYXJzZUZsb2F0KHcpKSAvIDIpICsgXCIlXCIsXG4gICAgICAgICAgICAgICAgICAgICAgICB0b3A6ICgoMTAwIC0gcGFyc2VGbG9hdChoKSkgLyAyKSArIFwiJVwiXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgaXRlbS5jc3Moe1xuICAgICAgICAgICAgICAgICAgICAgICAgd2lkdGg6IHcsXG4gICAgICAgICAgICAgICAgICAgICAgICBoZWlnaHQ6IGgsXG4gICAgICAgICAgICAgICAgICAgICAgICBtYXJnaW5MZWZ0OiAtdyAvIDIsXG4gICAgICAgICAgICAgICAgICAgICAgICBtYXJnaW5Ub3A6IC1oIC8gMlxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0sXG4gICAgICAgIGdldEZpcnN0RXF1YWxGcm9tVHdvQXJyYXlzOiBmdW5jdGlvbiAoYSwgYikge1xuICAgICAgICAgICAgdmFyIGlhID0gMCwgaWIgPSAwLCBsYSA9IGEubGVuZ3RoLCBsYiA9IGIubGVuZ3RoO1xuICAgICAgICAgICAgbG9vcDpcbiAgICAgICAgICAgICAgICBmb3IgKDsgaWEgPCBsYTsgaWErKykge1xuICAgICAgICAgICAgICAgICAgICB2YXIgY3VyciA9IGFbaWFdO1xuICAgICAgICAgICAgICAgICAgICBmb3IgKDsgaWIgPCBsYjsgaWIrKykge1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGJbaWJdID09IGN1cnIpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gY3VycjtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgfSxcbiAgICAgICAgaGFzOiBmdW5jdGlvbiAoZWwsIHdoYXQpIHtcbiAgICAgICAgICAgIHJldHVybiBlbC5xdWVyeVNlbGVjdG9yKHdoYXQpICE9PSBudWxsO1xuICAgICAgICB9LFxuICAgICAgICBodG1sX2luZm86IGZ1bmN0aW9uIChodG1sKSB7XG4gICAgICAgICAgICBpZiAodHlwZW9mIG13Ll9odG1sX2luZm8gPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgbXcuX2h0bWxfaW5mbyA9IG13ZC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgICAgICAgICBtdy5faHRtbF9pbmZvLmlkID0gJ213LWh0bWwtaW5mbyc7XG4gICAgICAgICAgICAgICAgbXdkLmJvZHkuYXBwZW5kQ2hpbGQobXcuX2h0bWxfaW5mbyk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBtdy4kKG13Ll9odG1sX2luZm8pLmh0bWwoaHRtbCk7XG4gICAgICAgICAgICByZXR1cm4gbXcuX2h0bWxfaW5mbztcbiAgICAgICAgfSxcbiAgICAgICAgaW1hZ2VfaW5mbzogZnVuY3Rpb24gKGEsIGNhbGxiYWNrKSB7XG4gICAgICAgICAgICB2YXIgaW1nID0gbXdkLmNyZWF0ZUVsZW1lbnQoJ2ltZycpO1xuICAgICAgICAgICAgaW1nLmNsYXNzTmFtZSA9ICdzZW1pX2hpZGRlbic7XG4gICAgICAgICAgICBpbWcuc3JjID0gYS5zcmM7XG4gICAgICAgICAgICBtd2QuYm9keS5hcHBlbmRDaGlsZChpbWcpO1xuICAgICAgICAgICAgaW1nLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKHt3aWR0aDogbXcuJChpbWcpLndpZHRoKCksIGhlaWdodDogbXcuJChpbWcpLmhlaWdodCgpfSk7XG4gICAgICAgICAgICAgICAgbXcuJChpbWcpLnJlbW92ZSgpO1xuICAgICAgICAgICAgfTtcbiAgICAgICAgfSxcbiAgICAgICAgcmVmcmVzaF9pbWFnZTogZnVuY3Rpb24gKG5vZGUpIHtcbiAgICAgICAgICAgIG5vZGUuc3JjID0gbXcudXJsLnNldF9wYXJhbSgncmVmcmVzaF9pbWFnZScsIG13LnJhbmRvbSgpLCBub2RlLnNyYyk7XG4gICAgICAgICAgICByZXR1cm4gbm9kZTtcbiAgICAgICAgfSxcbiAgICAgICAgcmVmcmVzaDogZnVuY3Rpb24gKGEpIHtcbiAgICAgICAgICAgIGlmIChhID09PSBudWxsIHx8IHR5cGVvZiBhID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmIChhLnNyYykge1xuICAgICAgICAgICAgICAgIGEuc3JjID0gbXcudXJsLnNldF9wYXJhbSgnbXdyZWZyZXNoJywgbXcucmFuZG9tKCksIGEuc3JjKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2UgaWYgKGEuaHJlZikge1xuICAgICAgICAgICAgICAgIGEuaHJlZiA9IG13LnVybC5zZXRfcGFyYW0oJ213cmVmcmVzaCcsIG13LnJhbmRvbSgpLCBhLmhyZWYpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBnZXREaWZmOiBmdW5jdGlvbiAoX25ldywgX29sZCkge1xuICAgICAgICAgICAgdmFyIGRpZmYgPSB7fSwgeCwgeTtcbiAgICAgICAgICAgIGZvciAoeCBpbiBfbmV3KSB7XG4gICAgICAgICAgICAgICAgaWYgKCF4IGluIF9vbGQgfHwgX29sZFt4XSAhPSBfbmV3W3hdKSB7XG4gICAgICAgICAgICAgICAgICAgIGRpZmZbeF0gPSBfbmV3W3hdO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGZvciAoeSBpbiBfb2xkKSB7XG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBfbmV3W3ldID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgICAgICBkaWZmW3ldID0gXCJcIjtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gZGlmZjtcbiAgICAgICAgfSxcbiAgICAgICAgcGFyc2VIdG1sOiBmdW5jdGlvbiAoaHRtbCkge1xuICAgICAgICAgICAgdmFyIGRvYyA9IG13ZC5pbXBsZW1lbnRhdGlvbi5jcmVhdGVIVE1MRG9jdW1lbnQoXCJcIik7XG4gICAgICAgICAgICBkb2MuYm9keS5pbm5lckhUTUwgPSBodG1sO1xuICAgICAgICAgICAgcmV0dXJuIGRvYztcbiAgICAgICAgfSxcbiAgICAgICAgaXNFbXB0eTogZnVuY3Rpb24gKG5vZGUpIHtcbiAgICAgICAgICAgIHJldHVybiAoIG5vZGUuaW5uZXJIVE1MLnRyaW0oKSApLmxlbmd0aCA9PT0gMDtcbiAgICAgICAgfSxcbiAgICAgICAgaXNKU09OOiBmdW5jdGlvbiAoYSkge1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBhID09PSAnb2JqZWN0Jykge1xuICAgICAgICAgICAgICAgIGlmIChhLmNvbnN0cnVjdG9yID09PSB7fS5jb25zdHJ1Y3Rvcikge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIGlmICh0eXBlb2YgYSA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgICAgICAgICB0cnkge1xuICAgICAgICAgICAgICAgICAgICBKU09OLnBhcnNlKGEpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBjYXRjaCAoZSkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICB0b0pTT046IGZ1bmN0aW9uICh3KSB7XG4gICAgICAgICAgICBpZiAodHlwZW9mIHcgPT09ICdvYmplY3QnICYmIG13LnRvb2xzLmlzSlNPTih3KSkge1xuICAgICAgICAgICAgICAgIHJldHVybiB3O1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKHR5cGVvZiB3ID09PSAnc3RyaW5nJykge1xuICAgICAgICAgICAgICAgIHRyeSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciByID0gSlNPTi5wYXJzZSh3KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgY2F0Y2ggKGUpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHIgPSB7XCIwXCI6IHd9O1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICByZXR1cm4gcjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmICh0eXBlb2YgdyA9PT0gJ29iamVjdCcgJiYgdy5jb25zdHJ1Y3RvciA9PT0gW10uY29uc3RydWN0b3IpIHtcbiAgICAgICAgICAgICAgICB2YXIgb2JqID0ge30sIGkgPSAwLCBsID0gdy5sZW5ndGg7XG4gICAgICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgb2JqW2ldID0gd1tpXTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIG9iajtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSxcbiAgICAgICAgbXdhdHRyOiBmdW5jdGlvbiAoZWwsIGEsIGIpIHtcbiAgICAgICAgICAgIGlmICghYiAmJiAhIWVsKSB7XG4gICAgICAgICAgICAgICAgdmFyIGRhdGEgPSBtdy4kKGVsKS5kYXRhc2V0KGEpO1xuICAgICAgICAgICAgICAgIGlmICghISQoZWwpWzBdLmF0dHJpYnV0ZXMpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGF0dHIgPSBtdy4kKGVsKVswXS5hdHRyaWJ1dGVzW2FdO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIGlmIChkYXRhICE9PSAnJykge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZGF0YTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYgKCEhYXR0cikge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gYXR0ci52YWx1ZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgbXcuJChlbCkuZGF0YXNldChhLCBiKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSxcbiAgICAgICAgZGlzYWJsZTogZnVuY3Rpb24gKGVsLCB0ZXh0LCBnbG9iYWwpIHtcbiAgICAgICAgICAgIHRleHQgPSB0ZXh0IHx8IG13Lm1zZy5sb2FkaW5nICsgJy4uLic7XG4gICAgICAgICAgICBnbG9iYWwgPSBnbG9iYWwgfHwgZmFsc2U7XG4gICAgICAgICAgICB2YXIgX2VsID0gbXcuJChlbCk7XG4gICAgICAgICAgICBpZiAoIV9lbC5sZW5ndGgpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiAoIV9lbC5oYXNDbGFzcyhcImRpc2FibGVkXCIpKSB7XG4gICAgICAgICAgICAgICAgX2VsLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuICAgICAgICAgICAgICAgIGlmIChfZWxbMF0udGFnTmFtZSAhPT0gJ0lOUFVUJykge1xuICAgICAgICAgICAgICAgICAgICBfZWwuZGF0YXNldChcInRleHRcIiwgX2VsLmh0bWwoKSk7XG4gICAgICAgICAgICAgICAgICAgIF9lbC5odG1sKHRleHQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgX2VsLmRhdGFzZXQoXCJ0ZXh0XCIsIF9lbC52YWwoKSk7XG4gICAgICAgICAgICAgICAgICAgIF9lbC52YWwodGV4dCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmIChnbG9iYWwpIG13LiQobXdkLmJvZHkpLmFkZENsYXNzKFwibG9hZGluZ1wiKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBlbDtcbiAgICAgICAgfSxcbiAgICAgICAgZW5hYmxlOiBmdW5jdGlvbiAoZWwpIHtcbiAgICAgICAgICAgIHZhciBfZWwgPSBtdy4kKGVsKTtcbiAgICAgICAgICAgIGlmICghX2VsLmxlbmd0aCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHZhciB0ZXh0ID0gX2VsLmRhdGFzZXQoXCJ0ZXh0XCIpO1xuICAgICAgICAgICAgX2VsLnJlbW92ZUNsYXNzKFwiZGlzYWJsZWRcIik7XG4gICAgICAgICAgICBpZiAoX2VsWzBdLnRhZ05hbWUgIT09ICdJTlBVVCcpIHtcbiAgICAgICAgICAgICAgICBfZWwuaHRtbCh0ZXh0KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIF9lbC52YWwodGV4dCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBtdy4kKG13ZC5ib2R5KS5yZW1vdmVDbGFzcyhcImxvYWRpbmdcIik7XG4gICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgIH0sXG4gICAgICAgIHByZXBlbmRDbGFzczogZnVuY3Rpb24gKGVsLCBjbHMpIHtcbiAgICAgICAgICAgIGVsLmNsYXNzTmFtZSA9IChjbHMgKyAnICcgKyBlbC5jbGFzc05hbWUpLnRyaW0oKVxuICAgICAgICB9LFxuICAgICAgICBpbnZpZXc6IGZ1bmN0aW9uIChlbCkge1xuICAgICAgICAgICAgdmFyICRlbCA9IG13LiQoZWwpO1xuICAgICAgICAgICAgaWYgKCRlbC5sZW5ndGggPT09IDApIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB2YXIgZHQgPSBtdy4kKHdpbmRvdykuc2Nyb2xsVG9wKCksXG4gICAgICAgICAgICAgICAgZGIgPSBkdCArIG13LiQod2luZG93KS5oZWlnaHQoKSxcbiAgICAgICAgICAgICAgICBldCA9ICRlbC5vZmZzZXQoKS50b3A7XG4gICAgICAgICAgICByZXR1cm4gKGV0IDw9IGRiKSAmJiAhKGR0ID4gKCRlbC5oZWlnaHQoKSArIGV0KSk7XG4gICAgICAgIH0sXG4gICAgICAgIHdob2xlaW52aWV3OiBmdW5jdGlvbiAoZWwpIHtcbiAgICAgICAgICAgIHZhciAkZWwgPSBtdy4kKGVsKSxcbiAgICAgICAgICAgICAgICBkdCA9IG13LiQod2luZG93KS5zY3JvbGxUb3AoKSxcbiAgICAgICAgICAgICAgICBkYiA9IGR0ICsgbXcuJCh3aW5kb3cpLmhlaWdodCgpLFxuICAgICAgICAgICAgICAgIGV0ID0gJGVsLm9mZnNldCgpLnRvcCxcbiAgICAgICAgICAgICAgICBlYiA9IGV0ICsgbXcuJChlbCkuaGVpZ2h0KCk7XG4gICAgICAgICAgICByZXR1cm4gKChlYiA8PSBkYikgJiYgKGV0ID49IGR0KSk7XG4gICAgICAgIH0sXG4gICAgICAgIHByZWxvYWQ6IGZ1bmN0aW9uICh1LCBjKSB7XG4gICAgICAgICAgICB2YXIgaW0gPSBuZXcgSW1hZ2UoKTtcbiAgICAgICAgICAgIGlmICh0eXBlb2YgYyA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgIGltLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgYy5jYWxsKHUsIGltKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpbS5zcmMgPSB1O1xuICAgICAgICB9LFxuICAgICAgICBtYXBOb2RlVmFsdWVzOiBmdW5jdGlvbiAobjEsIG4yKSB7XG4gICAgICAgICAgICBpZiAoIW4xIHx8ICFuMikgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgdmFyIHNldFZhbHVlMSA9ICgoISFuMS50eXBlICYmIG4xLm5vZGVOYW1lICE9PSAnQlVUVE9OJykgfHwgbjEubm9kZU5hbWUgPT09ICdURVhUQVJFQScpID8gJ3ZhbHVlJyA6ICd0ZXh0Q29udGVudCc7XG4gICAgICAgICAgICB2YXIgc2V0VmFsdWUyID0gKCghIW4yLnR5cGUgJiYgbjIubm9kZU5hbWUgIT09ICdCVVRUT04nKSB8fCBuMi5ub2RlTmFtZSA9PT0gJ1RFWFRBUkVBJykgPyAndmFsdWUnIDogJ3RleHRDb250ZW50JztcbiAgICAgICAgICAgIHZhciBldmVudHMgPSAna2V5dXAgcGFzdGUnO1xuICAgICAgICAgICAgbXcuJChuMSkuYmluZChldmVudHMsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBuMltzZXRWYWx1ZTJdID0gbjFbc2V0VmFsdWUxXTtcbiAgICAgICAgICAgICAgICBtdy4kKG4yKS50cmlnZ2VyKCdjaGFuZ2UnKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgbXcuJChuMikuYmluZChldmVudHMsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBuMVtzZXRWYWx1ZTFdID0gbjJbc2V0VmFsdWUyXTtcbiAgICAgICAgICAgICAgICBtdy4kKG4xKS50cmlnZ2VyKCdjaGFuZ2UnKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9LFxuICAgICAgICBjb3B5RXZlbnRzOiBmdW5jdGlvbiAoZnJvbSwgdG8pIHtcbiAgICAgICAgICAgIGlmICh0eXBlb2YgJC5fZGF0YShmcm9tLCAnZXZlbnRzJykgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgJC5lYWNoKCQuX2RhdGEoZnJvbSwgJ2V2ZW50cycpLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgJC5lYWNoKHRoaXMsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCh0bykuYmluZCh0aGlzLnR5cGUsIHRoaXMuaGFuZGxlcik7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSxcbiAgICAgICAgc2V0VGFnOiBmdW5jdGlvbiAobm9kZSwgdGFnKSB7XG4gICAgICAgICAgICB2YXIgZWwgPSBtd2QuY3JlYXRlRWxlbWVudCh0YWcpO1xuICAgICAgICAgICAgbXcudG9vbHMuY29weUF0dHJpYnV0ZXMobm9kZSwgZWwpO1xuICAgICAgICAgICAgd2hpbGUgKG5vZGUuZmlyc3RDaGlsZCkge1xuICAgICAgICAgICAgICAgIGVsLmFwcGVuZENoaWxkKG5vZGUuZmlyc3RDaGlsZCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBtdy50b29scy5jb3B5RXZlbnRzKG5vZGUsIGVsKTtcbiAgICAgICAgICAgIG13LiQobm9kZSkucmVwbGFjZVdpdGgoZWwpO1xuICAgICAgICAgICAgcmV0dXJuIGVsO1xuICAgICAgICB9LFxuXG4gICAgICAgIG1vZHVsZV9zZXR0aW5nczogZnVuY3Rpb24gKGEsIHZpZXcsIGxpdmVlZGl0KSB7XG4gICAgICAgICAgICB2YXIgb3B0cyA9IHt9O1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBsaXZlZWRpdCA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICBvcHRzLmxpdmVlZGl0ID0gdHJ1ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmICghIXZpZXcpIHtcbiAgICAgICAgICAgICAgICBvcHRzLnZpZXcgPSB2aWV3O1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgb3B0cy52aWV3ID0gJ2FkbWluJztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBtdy5saXZlX2VkaXQuc2hvd1NldHRpbmdzKGEsIG9wdHMpO1xuICAgICAgICB9LFxuICAgICAgICBmYXY6IGZ1bmN0aW9uIChhKSB7XG4gICAgICAgICAgICB2YXIgY2FudmFzID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImNhbnZhc1wiKTtcbiAgICAgICAgICAgIGNhbnZhcy53aWR0aCA9IDE2O1xuICAgICAgICAgICAgY2FudmFzLmhlaWdodCA9IDE2O1xuICAgICAgICAgICAgdmFyIGNvbnRleHQgPSBjYW52YXMuZ2V0Q29udGV4dChcIjJkXCIpO1xuICAgICAgICAgICAgY29udGV4dC5maWxsU3R5bGUgPSBcIiNFRjNEMjVcIjtcbiAgICAgICAgICAgIGNvbnRleHQuZmlsbFJlY3QoMCwgMCwgMTYsIDE2KTtcbiAgICAgICAgICAgIGNvbnRleHQuZm9udCA9IFwibm9ybWFsIDEwcHggQXJpYWxcIjtcbiAgICAgICAgICAgIGNvbnRleHQudGV4dEFsaWduID0gJ2NlbnRlcic7XG4gICAgICAgICAgICBjb250ZXh0LnRleHRCYXNlbGluZSA9ICdtaWRkbGUnO1xuICAgICAgICAgICAgY29udGV4dC5maWxsU3R5bGUgPSBcIndoaXRlXCI7XG4gICAgICAgICAgICBjb250ZXh0LmZpbGxUZXh0KGEsIDgsIDgpO1xuICAgICAgICAgICAgdmFyIGltID0gY2FudmFzLnRvRGF0YVVSTCgpO1xuICAgICAgICAgICAgdmFyIGwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdsaW5rJyk7XG4gICAgICAgICAgICBsLmNsYXNzTmFtZSA9ICdtd2Zhdic7XG4gICAgICAgICAgICBsLnNldEF0dHJpYnV0ZSgncmVsJywgJ2ljb24nKTtcbiAgICAgICAgICAgIGwuc2V0QXR0cmlidXRlKCd0eXBlJywgJ2ltYWdlL3BuZycpO1xuICAgICAgICAgICAgbC5ocmVmID0gaW07XG4gICAgICAgICAgICBtdy4kKFwiLm13ZmF2XCIpLnJlbW92ZSgpO1xuICAgICAgICAgICAgbXdkLmdldEVsZW1lbnRzQnlUYWdOYW1lKCdoZWFkJylbMF0uYXBwZW5kQ2hpbGQobCk7XG4gICAgICAgIH0sXG4gICAgICAgIHB4MnB0OiBmdW5jdGlvbiAocHgpIHtcbiAgICAgICAgICAgIHZhciBuID0gcGFyc2VJbnQocHgsIDEwKTtcbiAgICAgICAgICAgIGlmIChpc05hTihuKSkge1xuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBNYXRoLnJvdW5kKCgoMyAvIDQpICogbikpO1xuICAgICAgICB9LFxuICAgICAgICBtYXRjaGVzOiBmdW5jdGlvbiAobm9kZSwgd2hhdCkge1xuICAgICAgICAgICAgaWYgKG5vZGUgPT09ICdpbml0Jykge1xuICAgICAgICAgICAgICAgIGlmICghIW13ZC5kb2N1bWVudEVsZW1lbnQubWF0Y2hlcykgbXcudG9vbHMubWF0Y2hlc01ldGhvZCA9ICdtYXRjaGVzJztcbiAgICAgICAgICAgICAgICBlbHNlIGlmICghIW13ZC5kb2N1bWVudEVsZW1lbnQubWF0Y2hlc1NlbGVjdG9yKSBtdy50b29scy5tYXRjaGVzTWV0aG9kID0gJ21hdGNoZXNTZWxlY3Rvcic7XG4gICAgICAgICAgICAgICAgZWxzZSBpZiAoISFtd2QuZG9jdW1lbnRFbGVtZW50Lm1vek1hdGNoZXNTZWxlY3RvcikgbXcudG9vbHMubWF0Y2hlc01ldGhvZCA9ICdtb3pNYXRjaGVzU2VsZWN0b3InO1xuICAgICAgICAgICAgICAgIGVsc2UgaWYgKCEhbXdkLmRvY3VtZW50RWxlbWVudC53ZWJraXRNYXRjaGVzU2VsZWN0b3IpIG13LnRvb2xzLm1hdGNoZXNNZXRob2QgPSAnd2Via2l0TWF0Y2hlc1NlbGVjdG9yJztcbiAgICAgICAgICAgICAgICBlbHNlIGlmICghIW13ZC5kb2N1bWVudEVsZW1lbnQubXNNYXRjaGVzU2VsZWN0b3IpIG13LnRvb2xzLm1hdGNoZXNNZXRob2QgPSAnbXNNYXRjaGVzU2VsZWN0b3InO1xuICAgICAgICAgICAgICAgIGVsc2UgaWYgKCEhbXdkLmRvY3VtZW50RWxlbWVudC5vTWF0Y2hlc1NlbGVjdG9yKSBtdy50b29scy5tYXRjaGVzTWV0aG9kID0gJ29NYXRjaGVzU2VsZWN0b3InO1xuICAgICAgICAgICAgICAgIGVsc2UgbXcudG9vbHMubWF0Y2hlc01ldGhvZCA9IHVuZGVmaW5lZDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIGlmIChub2RlID09PSBudWxsKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBub2RlID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGlmIChub2RlLm5vZGVUeXBlICE9PSAxKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYgKCEhbXcudG9vbHMubWF0Y2hlc01ldGhvZCkge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gbm9kZVttdy50b29scy5tYXRjaGVzTWV0aG9kXSh3aGF0KVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGRvYyA9IG13ZC5pbXBsZW1lbnRhdGlvbi5jcmVhdGVIVE1MRG9jdW1lbnQoXCJcIik7XG4gICAgICAgICAgICAgICAgICAgIG5vZGUgPSBub2RlLmNsb25lTm9kZSh0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgZG9jLmJvZHkuYXBwZW5kQ2hpbGQobm9kZSk7XG4gICAgICAgICAgICAgICAgICAgIHZhciBhbGwgPSBkb2MuYm9keS5xdWVyeVNlbGVjdG9yQWxsKHdoYXQpLFxuICAgICAgICAgICAgICAgICAgICAgICAgbCA9IGFsbC5sZW5ndGgsXG4gICAgICAgICAgICAgICAgICAgICAgICBpID0gMDtcbiAgICAgICAgICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChhbGxbaV0gPT09IG5vZGUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfVxuXG4gICAgT2JqZWN0LmFzc2lnbihleHBvc2UsIGhlbHBlcnMpO1xuICAgIGV4cG9zZS5tYXRjaGVzKCdpbml0Jyk7XG5cbn0pKG13LnRvb2xzKTtcbiIsIm13LnRvb2xzLmNyZWF0ZUF1dG9IZWlnaHQgPSBmdW5jdGlvbigpIHtcclxuICAgIGlmKHdpbmRvdy50aGlzbW9kYWwgJiYgdGhpc21vZGFsLmlmcmFtZSkge1xyXG4gICAgICAgIG13LnRvb2xzLmlmcmFtZUF1dG9IZWlnaHQodGhpc21vZGFsLmlmcmFtZSk7XHJcbiAgICB9XHJcbiAgICBlbHNlIGlmKG13LnRvcCgpLndpbi5mcmFtZUVsZW1lbnQgJiYgbXcudG9wKCkud2luLmZyYW1lRWxlbWVudC5jb250ZW50V2luZG93ID09PSB3aW5kb3cpIHtcclxuICAgICAgICBtdy50b29scy5pZnJhbWVBdXRvSGVpZ2h0KG13LnRvcCgpLndpbi5mcmFtZUVsZW1lbnQpO1xyXG4gICAgfSBlbHNlIGlmKHdpbmRvdy50b3AgIT09IHdpbmRvdykge1xyXG4gICAgICAgIG13LnRvcCgpLiQoJ2lmcmFtZScpLmVhY2goZnVuY3Rpb24oKXtcclxuICAgICAgICAgICAgdHJ5e1xyXG4gICAgICAgICAgICAgICAgaWYodGhpcy5jb250ZW50V2luZG93ID09PSB3aW5kb3cpIHtcclxuICAgICAgICAgICAgICAgICAgICBtdy50b29scy5pZnJhbWVBdXRvSGVpZ2h0KHRoaXMpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9IGNhdGNoKGUpe31cclxuICAgICAgICB9KVxyXG4gICAgfVxyXG59O1xyXG5cclxubXcudG9vbHMubW9kdWxlRnJhbWUgPSBmdW5jdGlvbih0eXBlLCB0ZW1wbGF0ZSl7XHJcbiAgICByZXR1cm4gbXcuZGlhbG9nSWZyYW1lKHtcclxuICAgICAgICB1cmw6IG13LmV4dGVybmFsX3Rvb2woJ21vZHVsZV9kaWFsb2cnKSArICc/bW9kdWxlPScgKyB0eXBlICsgKHRlbXBsYXRlID8gKCcmdGVtcGxhdGU9JyArIHRlbXBsYXRlKSA6ICcnKSxcclxuICAgICAgICB3aWR0aDogNTMyLFxyXG4gICAgICAgIGhlaWdodDogJ2F1dG8nLFxyXG4gICAgICAgIGF1dG9IZWlnaHQ6dHJ1ZSxcclxuICAgICAgICB0aXRsZTogdHlwZSxcclxuICAgICAgICBjbGFzc05hbWU6ICdtdy1kaWFsb2ctbW9kdWxlLXNldHRpbmdzJyxcclxuICAgICAgICBjbG9zZUJ1dHRvbkFjdGlvbjogJ3JlbW92ZSdcclxuICAgIH0pO1xyXG59O1xyXG5cclxuXHJcbm13LnRvb2xzLmlmcmFtZUF1dG9IZWlnaHQgPSBmdW5jdGlvbihmcmFtZSwgb3B0KXtcclxuXHJcbiAgICBmcmFtZSA9IG13LiQoZnJhbWUpWzBdO1xyXG4gICAgaWYoIWZyYW1lKSByZXR1cm47XHJcblxyXG4gICAgdmFyIF9kZXRlY3RvciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG4gICAgX2RldGVjdG9yLmNsYXNzTmFtZSA9ICdtdy1pZnJhbWUtYXV0by1oZWlnaHQtZGV0ZWN0b3InO1xyXG4gICAgX2RldGVjdG9yLmlkID0gbXcuaWQoKTtcclxuXHJcbiAgICB2YXIgaW5zZXJ0RGV0ZWN0b3IgPSBmdW5jdGlvbigpIHtcclxuICAgICAgICBpZihmcmFtZS5jb250ZW50V2luZG93ICYmIGZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQgJiYgZnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudC5ib2R5KXtcclxuICAgICAgICAgICAgdmFyIGRldCA9IGZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLm13LWlmcmFtZS1hdXRvLWhlaWdodC1kZXRlY3RvcicpO1xyXG4gICAgICAgICAgICBpZighZGV0KXtcclxuICAgICAgICAgICAgICAgIGZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZChfZGV0ZWN0b3IpO1xyXG4gICAgICAgICAgICB9IGVsc2UgaWYoZGV0ICE9PSBmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50LmJvZHkubGFzdENoaWxkKXtcclxuICAgICAgICAgICAgICAgIGZyYW1lLmNvbnRlbnRXaW5kb3cuZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZChkZXQpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGlmKGZyYW1lLmNvbnRlbnRXaW5kb3cubXcpIHtcclxuICAgICAgICAgICAgICAgIGZyYW1lLmNvbnRlbnRXaW5kb3cubXcuX2lmcmFtZURldGVjdG9yID0gX2RldGVjdG9yO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgIH1cclxuICAgIH07XHJcblxyXG5cclxuXHJcbiAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XHJcbiAgICAgICAgaW5zZXJ0RGV0ZWN0b3IoKTtcclxuICAgIH0sIDEwMCk7XHJcbiAgICBmcmFtZS5zY3JvbGxpbmc9XCJub1wiO1xyXG4gICAgZnJhbWUuc3R5bGUubWluSGVpZ2h0ID0gMCArICdweCc7XHJcbiAgICBtdy4kKGZyYW1lKS5vbignbG9hZCByZXNpemUnLCBmdW5jdGlvbigpe1xyXG5cclxuICAgICAgICBpZighbXcudG9vbHMuY2FuQWNjZXNzSUZyYW1lKGZyYW1lKSkge1xyXG4gICAgICAgICAgICBjb25zb2xlLmxvZygnSWZyYW1lIGNhbiBub3QgYmUgYWNjZXNzZWQuJywgZnJhbWUpO1xyXG4gICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGlmKCFmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50LmJvZHkpe1xyXG4gICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGlmKCEhZnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcubXctaWZyYW1lLWF1dG8taGVpZ2h0LWRldGVjdG9yJykpe1xyXG4gICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGluc2VydERldGVjdG9yKCk7XHJcbiAgICB9KTtcclxuICAgIHZhciBvZmZzZXQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgcmV0dXJuIF9kZXRlY3Rvci5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKS50b3A7XHJcbiAgICB9O1xyXG4gICAgZnJhbWUuX2ludFBhdXNlID0gZmFsc2U7XHJcbiAgICBmcmFtZS5faW50ID0gc2V0SW50ZXJ2YWwoZnVuY3Rpb24oKXtcclxuICAgICAgICBpZighZnJhbWUuX2ludFBhdXNlICYmIGZyYW1lLnBhcmVudE5vZGUgJiYgZnJhbWUuY29udGVudFdpbmRvdyApe1xyXG4gICAgICAgICAgICB2YXIgY2FsYyA9IG9mZnNldCgpICsgX2RldGVjdG9yLm9mZnNldEhlaWdodDtcclxuICAgICAgICAgICAgZnJhbWUuX2N1cnJIZWlnaHQgPSBmcmFtZS5fY3VyckhlaWdodCB8fCAwO1xyXG4gICAgICAgICAgICBpZihjYWxjICYmIGNhbGMgIT09IGZyYW1lLl9jdXJySGVpZ2h0ICl7XHJcbiAgICAgICAgICAgICAgICBmcmFtZS5fY3VyckhlaWdodCA9IGNhbGM7XHJcbiAgICAgICAgICAgICAgICBmcmFtZS5zdHlsZS5oZWlnaHQgPSBNYXRoLm1heChjYWxjKSArICdweCc7XHJcbiAgICAgICAgICAgICAgICB2YXIgc2Nyb2xsID0gTWF0aC5tYXgoZnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuc2Nyb2xsSGVpZ2h0LCBmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50LmJvZHkuc2Nyb2xsSGVpZ2h0KTtcclxuICAgICAgICAgICAgICAgIGlmKHNjcm9sbCA+IGZyYW1lLl9jdXJySGVpZ2h0KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgZnJhbWUuX2N1cnJIZWlnaHQgPSBzY3JvbGw7XHJcbiAgICAgICAgICAgICAgICAgICAgZnJhbWUuc3R5bGUuaGVpZ2h0ID0gc2Nyb2xsICsgJ3B4JztcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIG13LiQoZnJhbWUpLnRyaWdnZXIoJ2JvZHlSZXNpemUnKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICBlbHNlIHtcclxuICAgICAgICAgICAgLy9jbGVhckludGVydmFsKGZyYW1lLl9pbnQpO1xyXG4gICAgICAgIH1cclxuICAgIH0sIDc3KTtcclxuXHJcbn07XHJcbiIsIm13LmltYWdlID0ge1xuICAgIGlzUmVzaXppbmc6IGZhbHNlLFxuICAgIF9kcmFnQWN0aXZhdGVkOiBmYWxzZSxcbiAgICBfZHJhZ2N1cnJlbnQ6IG51bGwsXG4gICAgX2RyYWdwYXJlbnQ6IG51bGwsXG4gICAgX2RyYWdjdXJzb3JBdDoge3g6IDAsIHk6IDB9LFxuICAgIF9kcmFnVHh0OiBmdW5jdGlvbiAoZSkge1xuICAgICAgICBpZiAobXcuaW1hZ2UuX2RyYWdjdXJyZW50ICE9PSBudWxsKSB7XG4gICAgICAgICAgICBtdy5pbWFnZS5fZHJhZ2N1cnNvckF0LnggPSBlLnBhZ2VYIC0gbXcuaW1hZ2UuX2RyYWdjdXJyZW50Lm9mZnNldExlZnQ7XG4gICAgICAgICAgICBtdy5pbWFnZS5fZHJhZ2N1cnNvckF0LnkgPSBlLnBhZ2VZIC0gbXcuaW1hZ2UuX2RyYWdjdXJyZW50Lm9mZnNldFRvcDtcbiAgICAgICAgICAgIHZhciB4ID0gZS5wYWdlWCAtIG13LmltYWdlLl9kcmFncGFyZW50Lm9mZnNldExlZnQgLSBtdy5pbWFnZS5fZHJhZ2N1cnJlbnQuc3RhcnRlZFggLSBtdy5pbWFnZS5fZHJhZ2N1cnNvckF0Lng7XG4gICAgICAgICAgICB2YXIgeSA9IGUucGFnZVkgLSBtdy5pbWFnZS5fZHJhZ3BhcmVudC5vZmZzZXRUb3AgLSBtdy5pbWFnZS5fZHJhZ2N1cnJlbnQuc3RhcnRlZFkgLSBtdy5pbWFnZS5fZHJhZ2N1cnNvckF0Lnk7XG4gICAgICAgICAgICBtdy5pbWFnZS5fZHJhZ2N1cnJlbnQuc3R5bGUudG9wID0geSArICdweCc7XG4gICAgICAgICAgICBtdy5pbWFnZS5fZHJhZ2N1cnJlbnQuc3R5bGUubGVmdCA9IHggKyAncHgnO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBwcmVsb2FkRm9yQWxsOiBmdW5jdGlvbiAoYXJyYXksIGVhY2hjYWxsLCBjYWxsYmFjaykge1xuICAgICAgICB2YXIgc2l6ZSA9IGFycmF5Lmxlbmd0aCwgaSA9IDAsIGNvdW50ID0gMDtcbiAgICAgICAgZm9yICg7IGkgPCBzaXplOyBpKyspIHtcbiAgICAgICAgICAgIG13LmltYWdlLnByZWxvYWQoYXJyYXlbaV0sIGZ1bmN0aW9uIChpbWdXaWR0aCwgaW1nSGVpZ2h0KSB7XG4gICAgICAgICAgICAgICAgY291bnQrKztcbiAgICAgICAgICAgICAgICBlYWNoY2FsbC5jYWxsKHRoaXMsIGltZ1dpZHRoLCBpbWdIZWlnaHQpXG4gICAgICAgICAgICAgICAgaWYgKGNvdW50ID09PSBzaXplKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmICghIWNhbGxiYWNrKSBjYWxsYmFjay5jYWxsKClcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KVxuICAgICAgICB9XG4gICAgfSxcbiAgICBwcmVsb2FkQWxsOiBmdW5jdGlvbiAoYXJyYXksIGNhbGxiYWNrKSB7XG4gICAgICAgIHZhciBzaXplID0gYXJyYXkubGVuZ3RoLCBpID0gMCwgY291bnQgPSAwO1xuICAgICAgICBmb3IgKDsgaSA8IHNpemU7IGkrKykge1xuICAgICAgICAgICAgbXcuaW1hZ2UucHJlbG9hZChhcnJheVtpXSwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGNvdW50Kys7XG4gICAgICAgICAgICAgICAgaWYgKGNvdW50ID09PSBzaXplKSB7XG4gICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwoKVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pXG4gICAgICAgIH1cbiAgICB9LFxuICAgIHByZWxvYWQ6IGZ1bmN0aW9uICh1cmwsIGNhbGxiYWNrKSB7XG4gICAgICAgIHZhciBpbWcgPSBtd2QuY3JlYXRlRWxlbWVudCgnaW1nJyk7XG4gICAgICAgIGltZy5jbGFzc05hbWUgPSAnc2VtaV9oaWRkZW4nO1xuICAgICAgICBpbWcuc3JjID0gdXJsO1xuICAgICAgICBpbWcub25sb2FkID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKGltZywgaW1nLm5hdHVyYWxXaWR0aCwgaW1nLm5hdHVyYWxIZWlnaHQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBtdy4kKGltZykucmVtb3ZlKCk7XG4gICAgICAgICAgICB9LCAzMyk7XG4gICAgICAgIH07XG4gICAgICAgIGltZy5vbmVycm9yID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKGltZywgMCwgMCwgJ2Vycm9yJyk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSwgMzMpO1xuICAgICAgICB9O1xuICAgICAgICBtd2QuYm9keS5hcHBlbmRDaGlsZChpbWcpO1xuICAgIH0sXG4gICAgc2V0dGluZ3M6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgcmV0dXJuIG13LmRpYWxvZ0lmcmFtZSh7XG4gICAgICAgICAgICB1cmw6ICdpbWFnZWVkaXRvcicsXG4gICAgICAgICAgICB0ZW1wbGF0ZTogXCJtd19tb2RhbF9iYXNpY1wiLFxuICAgICAgICAgICAgb3ZlcmxheTogdHJ1ZSxcbiAgICAgICAgICAgIHdpZHRoOiAnNjAwJyxcbiAgICAgICAgICAgIGhlaWdodDogXCJhdXRvXCIsXG4gICAgICAgICAgICBhdXRvSGVpZ2h0OiB0cnVlLFxuICAgICAgICAgICAgbmFtZTogJ213LWltYWdlLXNldHRpbmdzLW1vZGFsJ1xuICAgICAgICB9KTtcbiAgICB9XG59O1xuIiwiJC5mbi5kYXRhc2V0ID0gZnVuY3Rpb24gKGRhdGFzZXQsIHZhbCkge1xuICAgIHZhciBlbCA9IHRoaXNbMF07XG4gICAgaWYgKGVsID09PSB1bmRlZmluZWQpIHJldHVybiBmYWxzZTtcbiAgICB2YXIgX2RhdGFzZXQgPSAhZGF0YXNldC5jb250YWlucygnLScpID8gZGF0YXNldCA6IG13LnRvb2xzLnRvQ2FtZWxDYXNlKGRhdGFzZXQpO1xuICAgIGlmICghdmFsKSB7XG4gICAgICAgIHZhciBkYXRhc2V0ID0gISFlbC5kYXRhc2V0ID8gZWwuZGF0YXNldFtfZGF0YXNldF0gOiBtdy4kKGVsKS5hdHRyKFwiZGF0YS1cIiArIGRhdGFzZXQpO1xuICAgICAgICByZXR1cm4gZGF0YXNldCAhPT0gdW5kZWZpbmVkID8gZGF0YXNldCA6IFwiXCI7XG4gICAgfVxuICAgIGVsc2Uge1xuICAgICAgICAhIWVsLmRhdGFzZXQgPyBlbC5kYXRhc2V0W19kYXRhc2V0XSA9IHZhbCA6IG13LiQoZWwpLmF0dHIoXCJkYXRhLVwiICsgZGF0YXNldCwgdmFsKTtcbiAgICAgICAgcmV0dXJuIG13LiQoZWwpO1xuICAgIH1cbn07XG5cbiQuZm4ucmVsb2FkX21vZHVsZSA9IGZ1bmN0aW9uIChjKSB7XG4gICAgcmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgIC8vICAgaWYoJCh0aGlzKS5oYXNDbGFzcyhcIm1vZHVsZVwiKSl7XG4gICAgICAgIChmdW5jdGlvbiAoZWwpIHtcbiAgICAgICAgICAgIG13LnJlbG9hZF9tb2R1bGUoZWwsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mKGMpICE9ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgICAgIGMuY2FsbChlbCwgZWwpXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSlcbiAgICAgICAgfSkodGhpcylcbiAgICAgICAgLy8gICB9XG4gICAgfSlcbn07XG5cblxuICAgICQuZm4uZ2V0RHJvcGRvd25WYWx1ZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZGF0YXNldChcInZhbHVlXCIpIHx8IG13LiQoJy5hY3RpdmUnLCB0aGlzKS5hdHRyKCd2YWx1ZScpO1xuICAgIH07XG4gICAgJC5mbi5zZXREcm9wZG93blZhbHVlID0gZnVuY3Rpb24gKHZhbCwgdHJpZ2dlckNoYW5nZSwgaXNDdXN0b20sIGN1c3RvbVZhbHVlVG9EaXNwbGF5KSB7XG5cbiAgICAgICAgdmFyIGlzQ3VzdG9tID0gaXNDdXN0b20gfHwgZmFsc2U7XG4gICAgICAgIHZhciB0cmlnZ2VyQ2hhbmdlID0gdHJpZ2dlckNoYW5nZSB8fCBmYWxzZTtcbiAgICAgICAgdmFyIGlzVmFsaWRPcHRpb24gPSBmYWxzZTtcbiAgICAgICAgdmFyIGN1c3RvbVZhbHVlVG9EaXNwbGF5ID0gY3VzdG9tVmFsdWVUb0Rpc3BsYXkgfHwgZmFsc2U7XG4gICAgICAgIHZhciBlbCA9IHRoaXM7XG4gICAgICAgIGlmIChpc0N1c3RvbSkge1xuICAgICAgICAgICAgdmFyIGlzVmFsaWRPcHRpb24gPSB0cnVlO1xuICAgICAgICAgICAgZWwuZGF0YXNldChcInZhbHVlXCIsIHZhbCk7XG4gICAgICAgICAgICB0cmlnZ2VyQ2hhbmdlID8gZWwudHJpZ2dlcihcImNoYW5nZVwiKSA6ICcnO1xuICAgICAgICAgICAgdmFyIHZhbGVsID0gbXcuJChcIi5tdy1kcm9wZG93bi12YWxcIiwgZWwpO1xuICAgICAgICAgICAgdmFyIG1ldGhvZCA9IHZhbGVsWzBdLnR5cGUgPyAndmFsJyA6ICdodG1sJztcbiAgICAgICAgICAgIGlmICghIWN1c3RvbVZhbHVlVG9EaXNwbGF5KSB7XG4gICAgICAgICAgICAgICAgdmFsZWxbbWV0aG9kXShjdXN0b21WYWx1ZVRvRGlzcGxheSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICB2YWxlbFttZXRob2RdKHZhbCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBtdy4kKFwiW3ZhbHVlXVwiLCBlbCkuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgaWYgKHRoaXMuZ2V0QXR0cmlidXRlKCd2YWx1ZScpID09IHZhbCkge1xuICAgICAgICAgICAgICAgICAgICBlbC5kYXRhc2V0KFwidmFsdWVcIiwgdmFsKTtcbiAgICAgICAgICAgICAgICAgICAgdmFyIGlzVmFsaWRPcHRpb24gPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICB2YXIgaHRtbCA9ICEhdGhpcy5nZXRFbGVtZW50c0J5VGFnTmFtZSgnYScpWzBdID8gdGhpcy5nZXRFbGVtZW50c0J5VGFnTmFtZSgnYScpWzBdLnRleHRDb250ZW50IDogdGhpcy5pbm5lclRleHQ7XG4gICAgICAgICAgICAgICAgICAgIG13LiQoXCIubXctZHJvcGRvd24tdmFsXCIsIGVsWzBdKS5odG1sKGh0bWwpO1xuICAgICAgICAgICAgICAgICAgICBpZiAodHJpZ2dlckNoYW5nZSA9PT0gdHJ1ZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZWwudHJpZ2dlcihcImNoYW5nZVwiKVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgICAgICB0aGlzLmRhdGFzZXQoXCJ2YWx1ZVwiLCB2YWwpO1xuICAgICAgICAvLyAgICB9LCAxMDApO1xuICAgIH07XG4gICAgJC5mbi5jb21tdXRlciA9IGZ1bmN0aW9uIChhLCBiKSB7XG4gICAgICAgIGlmIChhID09PSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZVxuICAgICAgICB9XG4gICAgICAgIHZhciBiID0gYiB8fCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB9O1xuICAgICAgICByZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGlmICgodGhpcy50eXBlID09PSAnY2hlY2tib3gnIHx8IHRoaXMudHlwZSA9PT0gJ3JhZGlvJykgJiYgIXRoaXMuY21hY3RpdmF0ZWQpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmNtYWN0aXZhdGVkID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLmJpbmQoXCJjaGFuZ2VcIiwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmNoZWNrZWQgPT09IHRydWUgPyBhLmNhbGwodGhpcykgOiBiLmNhbGwodGhpcyk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH07XG5cblxuXG4kLmZuLnZpc2libGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgcmV0dXJuIHRoaXMuY3NzKFwidmlzaWJpbGl0eVwiLCBcInZpc2libGVcIikuY3NzKFwib3BhY2l0eVwiLCBcIjFcIik7XG59O1xuJC5mbi52aXNpYmlsaXR5RGVmYXVsdCA9IGZ1bmN0aW9uICgpIHtcbiAgICByZXR1cm4gdGhpcy5jc3MoXCJ2aXNpYmlsaXR5XCIsIFwiXCIpLmNzcyhcIm9wYWNpdHlcIiwgXCJcIik7XG59O1xuJC5mbi5pbnZpc2libGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgcmV0dXJuIHRoaXMuY3NzKFwidmlzaWJpbGl0eVwiLCBcImhpZGRlblwiKS5jc3MoXCJvcGFjaXR5XCIsIFwiMFwiKTtcbn07XG5cbiQuZm4ubXdEaWFsb2cgPSBmdW5jdGlvbihjb25mKXtcbiAgICB2YXIgZWwgPSB0aGlzWzBdO1xuICAgIHZhciBvcHRpb25zID0gbXcudG9vbHMuZWxlbWVudE9wdGlvbnMoZWwpO1xuICAgIHZhciBpZCA9IG13LmlkKCdtd0RpYWxvZy0nKTtcbiAgICB2YXIgaWRFbCA9IG13LmlkKCdtd0RpYWxvZ1RlbXAtJyk7XG4gICAgdmFyIGRlZmF1bHRzID0ge1xuICAgICAgICBoZWlnaHQ6ICdhdXRvJyxcbiAgICAgICAgYXV0b0hlaWdodDogdHJ1ZSxcbiAgICAgICAgd2lkdGg6ICdhdXRvJ1xuICAgIH07XG4gICAgdmFyIHNldHRpbmdzID0gJC5leHRlbmQoe30sIGRlZmF1bHRzLCBvcHRpb25zLCBjb25mLCB7Y2xvc2VCdXR0b25BY3Rpb246ICdyZW1vdmUnfSk7XG4gICAgaWYoY29uZiA9PT0gJ2Nsb3NlJyB8fCBjb25mID09PSAnaGlkZScgfHwgY29uZiA9PT0gJ3JlbW92ZScpe1xuICAgICAgICBpZihlbC5fZGlhbG9nKXtcbiAgICAgICAgICAgIGVsLl9kaWFsb2cucmVtb3ZlKClcbiAgICAgICAgfVxuICAgICAgICByZXR1cm47XG4gICAgfVxuICAgICQoZWwpLmJlZm9yZSgnPG13LWRpYWxvZy10ZW1wIGlkPVwiJytpZEVsKydcIj48L213LWRpYWxvZy10ZW1wPicpO1xuICAgIHZhciBkaWFsb2cgPSBtdy5kaWFsb2coc2V0dGluZ3MpO1xuICAgIGVsLl9kaWFsb2cgPSBkaWFsb2c7XG4gICAgZGlhbG9nLmRpYWxvZ0NvbnRhaW5lci5hcHBlbmRDaGlsZChlbCk7XG4gICAgJChlbCkuc2hvdygpO1xuICAgIGlmKHNldHRpbmdzLndpZHRoID09PSAnYXV0bycpe1xuICAgICAgICBkaWFsb2cud2lkdGgoJChlbCkud2lkdGgpO1xuICAgICAgICBkaWFsb2cuY2VudGVyKCQoZWwpLndpZHRoKTtcbiAgICB9XG4gICAgJChkaWFsb2cpLm9uKCdCZWZvcmVSZW1vdmUnLCBmdW5jdGlvbigpe1xuICAgICAgICBtdy4kKCcjJyArIGlkRWwpLnJlcGxhY2VXaXRoKGVsKTtcbiAgICAgICAgJChlbCkuaGlkZSgpO1xuICAgICAgICBlbC5fZGlhbG9nID0gbnVsbDtcbiAgICB9KTtcbiAgICByZXR1cm4gdGhpcztcbn07XG5cbm13LmFqYXggPSBmdW5jdGlvbiAob3B0aW9ucykge1xuICAgIHZhciB4aHIgPSAkLmFqYXgob3B0aW9ucyk7XG4gICAgcmV0dXJuIHhocjtcbn07XG5cbm13LmFqYXggPSAkLmFqYXg7XG5cbmpRdWVyeS5lYWNoKFtcInhockdldFwiLCBcInhoclBvc3RcIl0sIGZ1bmN0aW9uIChpLCBtZXRob2QpIHtcbiAgICBtd1ttZXRob2RdID0gZnVuY3Rpb24gKHVybCwgZGF0YSwgY2FsbGJhY2ssIHR5cGUpIHtcbiAgICAgICAgaWYgKGpRdWVyeS5pc0Z1bmN0aW9uKGRhdGEpKSB7XG4gICAgICAgICAgICB0eXBlID0gdHlwZSB8fCBjYWxsYmFjaztcbiAgICAgICAgICAgIGNhbGxiYWNrID0gZGF0YTtcbiAgICAgICAgICAgIGRhdGEgPSB1bmRlZmluZWQ7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIG13LmFqYXgoalF1ZXJ5LmV4dGVuZCh7XG4gICAgICAgICAgICB1cmw6IHVybCxcbiAgICAgICAgICAgIHR5cGU6IGkgPT0gMCA/ICdHRVQnIDogJ1BPU1QnLFxuICAgICAgICAgICAgZGF0YVR5cGU6IHR5cGUsXG4gICAgICAgICAgICBkYXRhOiBkYXRhLFxuICAgICAgICAgICAgc3VjY2VzczogY2FsbGJhY2tcbiAgICAgICAgfSwgalF1ZXJ5LmlzUGxhaW5PYmplY3QodXJsKSAmJiB1cmwpKTtcbiAgICB9O1xufSk7XG4iLCIoZnVuY3Rpb24oKXtcclxuICAgIHZhciBsb29wVG9vbHMgPSB7XHJcbiAgICAgICBsb29wOiB7LyogR2xvYmFsIGluZGV4IGZvciBNVyBsb29wcyAqL30sXHJcbiAgICAgICAgc3RvcExvb3A6IGZ1bmN0aW9uIChsb29wKSB7XHJcbiAgICAgICAgICAgIG13LnRvb2xzLmxvb3BbbG9vcF0gPSBmYWxzZTtcclxuICAgICAgICB9LFxyXG4gICAgICAgIGZvcmVhY2hQYXJlbnRzOiBmdW5jdGlvbiAoZWwsIGNhbGxiYWNrKSB7XHJcbiAgICAgICAgICAgIGlmICghZWwpIHJldHVybiBmYWxzZTtcclxuICAgICAgICAgICAgdmFyIGluZGV4ID0gbXcucmFuZG9tKCk7XHJcbiAgICAgICAgICAgIG13LnRvb2xzLmxvb3BbaW5kZXhdID0gdHJ1ZTtcclxuICAgICAgICAgICAgdmFyIF9jdXJyID0gZWwucGFyZW50Tm9kZTtcclxuICAgICAgICAgICAgdmFyIGNvdW50ID0gLTE7XHJcbiAgICAgICAgICAgIGlmIChfY3VyciAhPT0gbnVsbCAmJiBfY3VyciAhPT0gdW5kZWZpbmVkKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgX3RhZyA9IF9jdXJyLnRhZ05hbWU7XHJcbiAgICAgICAgICAgICAgICB3aGlsZSAoX3RhZyAhPT0gJ0JPRFknKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY291bnQrKztcclxuICAgICAgICAgICAgICAgICAgICB2YXIgY2FsbGVyID0gY2FsbGJhY2suY2FsbChfY3VyciwgaW5kZXgsIGNvdW50KTtcclxuICAgICAgICAgICAgICAgICAgICBfY3VyciA9IF9jdXJyLnBhcmVudE5vZGU7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKGNhbGxlciA9PT0gZmFsc2UgfHwgX2N1cnIgPT09IG51bGwgfHwgX2N1cnIgPT09IHVuZGVmaW5lZCB8fCAhbXcudG9vbHMubG9vcFtpbmRleF0pIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgZGVsZXRlIG13LnRvb2xzLmxvb3BbaW5kZXhdO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgX3RhZyA9IF9jdXJyLnRhZ05hbWU7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG4gICAgICAgIGZvcmVhY2hDaGlsZHJlbjogZnVuY3Rpb24gKGVsLCBjYWxsYmFjaykge1xyXG4gICAgICAgICAgICBpZiAoIWVsKSByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgICAgIHZhciBpbmRleCA9IG13LnJhbmRvbSgpO1xyXG4gICAgICAgICAgICBtdy50b29scy5sb29wW2luZGV4XSA9IHRydWU7XHJcbiAgICAgICAgICAgIHZhciBfY3VyciA9IGVsLmZpcnN0Q2hpbGQ7XHJcbiAgICAgICAgICAgIHZhciBjb3VudCA9IC0xO1xyXG4gICAgICAgICAgICBpZiAoX2N1cnIgIT09IG51bGwgJiYgX2N1cnIgIT09IHVuZGVmaW5lZCkge1xyXG4gICAgICAgICAgICAgICAgd2hpbGUgKF9jdXJyLm5leHRTaWJsaW5nICE9PSBudWxsKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKF9jdXJyLm5vZGVUeXBlID09PSAxKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvdW50Kys7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBjYWxsZXIgPSBjYWxsYmFjay5jYWxsKF9jdXJyLCBpbmRleCwgY291bnQpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBfY3VyciA9IF9jdXJyLm5leHRTaWJsaW5nO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoY2FsbGVyID09PSBmYWxzZSB8fCBfY3VyciA9PT0gbnVsbCB8fCBfY3VyciA9PT0gdW5kZWZpbmVkIHx8ICFtdy50b29scy5sb29wW2luZGV4XSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZGVsZXRlIG13LnRvb2xzLmxvb3BbaW5kZXhdO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgX3RhZyA9IF9jdXJyLnRhZ05hbWU7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBfY3VyciA9IF9jdXJyLm5leHRTaWJsaW5nO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH07XHJcbiAgICBPYmplY3QuYXNzaWduKG13LnRvb2xzLCBsb29wVG9vbHMpO1xyXG59KSgpO1xyXG4iLCJtdy50b29scy5ub3RpZmljYXRpb24gPSBmdW5jdGlvbiAoYm9keSwgdGFnLCBpY29uKSB7XG4gICAgaWYgKCFib2R5KSByZXR1cm47XG4gICAgdmFyIG4gPSB3aW5kb3cuTm90aWZpY2F0aW9uIHx8IHdpbmRvdy53ZWJraXROb3RpZmljYXRpb24gfHwgd2luZG93Lm1vek5vdGlmaWNhdGlvbjtcbiAgICBpZiAodHlwZW9mIG4gPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9XG4gICAgaWYgKG4ucGVybWlzc2lvbiA9PT0gJ2dyYW50ZWQnKSB7XG4gICAgICAgIG5ldyBuKFwiTVcgVXBkYXRlXCIsIHtcbiAgICAgICAgICAgIHRhZzogdGFnIHx8IFwiTWljcm93ZWJlclwiLFxuICAgICAgICAgICAgYm9keTogYm9keSxcbiAgICAgICAgICAgIGljb246IGljb24gfHwgbXcuc2V0dGluZ3MuaW5jbHVkZXNfdXJsICsgXCJpbWcvbG9nb21hcmsucG5nXCJcbiAgICAgICAgfSk7XG4gICAgfVxuICAgIGVsc2UgaWYgKG4ucGVybWlzc2lvbiA9PT0gJ2RlZmF1bHQnKSB7XG4gICAgICAgIG4ucmVxdWVzdFBlcm1pc3Npb24oZnVuY3Rpb24gKHJlc3VsdCkge1xuXG4gICAgICAgICAgICBpZiAocmVzdWx0ID09PSAnZ3JhbnRlZCcpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gbXcudG9vbHMubm90aWZpY2F0aW9uKGJvZHksIHRhZywgaWNvbilcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2UgaWYgKHJlc3VsdCA9PT0gJ2RlbmllZCcpIHtcbiAgICAgICAgICAgICAgICBtdy5ub3RpZmljYXRpb24uZXJyb3IoJ05vdGlmaWNhdGlvbnMgYXJlIGJsb2NrZWQnKVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSBpZiAocmVzdWx0ID09PSAnZGVmYXVsdCcpIHtcbiAgICAgICAgICAgICAgICBtdy5ub3RpZmljYXRpb24ud2FybignTm90aWZpY2F0aW9ucyBhcmUgY2FuY2VsZWQnKVxuXG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH1cbn1cbiIsIm13Lm5vdGlmaWNhdGlvbiA9IHtcclxuICAgIG1zZzogZnVuY3Rpb24gKGRhdGEsIHRpbWVvdXQsIGFsZXJ0KSB7XHJcbiAgICAgICAgdGltZW91dCA9IHRpbWVvdXQgfHwgMTAwMDtcclxuICAgICAgICBhbGVydCA9IGFsZXJ0IHx8IGZhbHNlO1xyXG4gICAgICAgIGlmIChkYXRhKSB7XHJcbiAgICAgICAgICAgIGlmIChkYXRhLnN1Y2Nlc3MpIHtcclxuICAgICAgICAgICAgICAgIGlmIChhbGVydCkge1xyXG4gICAgICAgICAgICAgICAgICAgIG13Lm5vdGlmaWNhdGlvbi5zdWNjZXNzKGRhdGEuc3VjY2VzcywgdGltZW91dCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBBbGVydChkYXRhLnN1Y2Nlc3MpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGlmIChkYXRhLmVycm9yKSB7XHJcbiAgICAgICAgICAgICAgICBtdy5ub3RpZmljYXRpb24uZXJyb3IoZGF0YS5lcnJvciwgdGltZW91dCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgaWYgKGRhdGEud2FybmluZykge1xyXG4gICAgICAgICAgICAgICAgbXcubm90aWZpY2F0aW9uLndhcm5pbmcoZGF0YS53YXJuaW5nLCB0aW1lb3V0KTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH0sXHJcbiAgICBidWlsZDogZnVuY3Rpb24gKHR5cGUsIHRleHQsIG5hbWUpIHtcclxuICAgICAgICB2YXIgZGl2ID0gbXdkLmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG4gICAgICAgIGRpdi5pZCA9IG5hbWU7XHJcbiAgICAgICAgZGl2LmNsYXNzTmFtZSA9ICdtdy1ub3RpZmljYXRpb24gbXctJyArIHR5cGU7XHJcbiAgICAgICAgZGl2LmlubmVySFRNTCA9ICc8ZGl2PicgKyB0ZXh0ICsgJzwvZGl2Pic7XHJcbiAgICAgICAgcmV0dXJuIGRpdjtcclxuICAgIH0sXHJcbiAgICBhcHBlbmQ6IGZ1bmN0aW9uICh0eXBlLCB0ZXh0LCB0aW1lb3V0LCBuYW1lKSB7XHJcbiAgICAgICAgaWYodHlwZW9mIHR5cGUgPT09ICdvYmplY3QnKSB7XHJcbiAgICAgICAgICAgIHRleHQgPSB0eXBlLnRleHQ7XHJcbiAgICAgICAgICAgIHRpbWVvdXQgPSB0eXBlLnRpbWVvdXQ7XHJcbiAgICAgICAgICAgIG5hbWUgPSB0eXBlLm5hbWU7XHJcbiAgICAgICAgICAgIHR5cGUgPSB0eXBlLnR5cGU7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIG5hbWUgPSBuYW1lIHx8IG13LmlkKCk7XHJcbiAgICAgICAgbmFtZSA9ICdtdy1ub3RpZmljYXRpb24taWQtJyArIG5hbWU7XHJcbiAgICAgICAgaWYoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobmFtZSkpIHtcclxuICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgIH1cclxuICAgICAgICB0aW1lb3V0ID0gdGltZW91dCB8fCAxMDAwO1xyXG4gICAgICAgIHZhciBkaXYgPSBtdy5ub3RpZmljYXRpb24uYnVpbGQodHlwZSwgdGV4dCwgbmFtZSk7XHJcbiAgICAgICAgaWYgKHR5cGVvZiBtdy5ub3RpZmljYXRpb24uX2hvbGRlciA9PT0gJ3VuZGVmaW5lZCcpIHtcclxuICAgICAgICAgICAgbXcubm90aWZpY2F0aW9uLl9ob2xkZXIgPSBtd2QuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcbiAgICAgICAgICAgIG13Lm5vdGlmaWNhdGlvbi5faG9sZGVyLmlkID0gJ213LW5vdGlmaWNhdGlvbnMtaG9sZGVyJztcclxuICAgICAgICAgICAgbXdkLmJvZHkuYXBwZW5kQ2hpbGQobXcubm90aWZpY2F0aW9uLl9ob2xkZXIpO1xyXG4gICAgICAgIH1cclxuICAgICAgICBtdy5ub3RpZmljYXRpb24uX2hvbGRlci5hcHBlbmRDaGlsZChkaXYpO1xyXG4gICAgICAgIHZhciB3ID0gbXcuJChkaXYpLm91dGVyV2lkdGgoKTtcclxuICAgICAgICBtdy4kKGRpdikuY3NzKFwibWFyZ2luTGVmdFwiLCAtKHcgLyAyKSk7XHJcbiAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIGRpdi5zdHlsZS5vcGFjaXR5ID0gMDtcclxuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBtdy4kKGRpdikucmVtb3ZlKCk7XHJcbiAgICAgICAgICAgIH0sIDEwMDApO1xyXG4gICAgICAgIH0sIHRpbWVvdXQpO1xyXG4gICAgfSxcclxuICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uICh0ZXh0LCB0aW1lb3V0LCBuYW1lKSB7XHJcbiAgICAgICAgaWYgKCB0eXBlb2YgdGV4dCA9PT0gJ29iamVjdCcgKSB7XHJcbiAgICAgICAgICAgIHRpbWVvdXQgPSB0ZXh0LnRpbWVvdXQ7XHJcbiAgICAgICAgICAgIG5hbWUgPSB0ZXh0Lm5hbWU7XHJcbiAgICAgICAgICAgIHRleHQgPSB0ZXh0LnRleHQ7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHRpbWVvdXQgPSB0aW1lb3V0IHx8IDEwMDA7XHJcbiAgICAgICAgbXcubm90aWZpY2F0aW9uLmFwcGVuZCgnc3VjY2VzcycsIHRleHQsIHRpbWVvdXQsIG5hbWUpO1xyXG4gICAgfSxcclxuICAgIGVycm9yOiBmdW5jdGlvbiAodGV4dCwgdGltZW91dCwgbmFtZSkge1xyXG4gICAgICAgIGlmICggdHlwZW9mIHRleHQgPT09ICdvYmplY3QnICkge1xyXG4gICAgICAgICAgICB0aW1lb3V0ID0gdGV4dC50aW1lb3V0O1xyXG4gICAgICAgICAgICBuYW1lID0gdGV4dC5uYW1lO1xyXG4gICAgICAgICAgICB0ZXh0ID0gdGV4dC50ZXh0O1xyXG4gICAgICAgIH1cclxuICAgICAgICB0aW1lb3V0ID0gdGltZW91dCB8fCAxMDAwO1xyXG4gICAgICAgIG13Lm5vdGlmaWNhdGlvbi5hcHBlbmQoJ2Vycm9yJywgdGV4dCwgdGltZW91dCwgbmFtZSk7XHJcbiAgICB9LFxyXG4gICAgd2FybmluZzogZnVuY3Rpb24gKHRleHQsIHRpbWVvdXQsIG5hbWUpIHtcclxuICAgICAgICBpZiAoIHR5cGVvZiB0ZXh0ID09PSAnb2JqZWN0JyApIHtcclxuICAgICAgICAgICAgdGltZW91dCA9IHRleHQudGltZW91dDtcclxuICAgICAgICAgICAgbmFtZSA9IHRleHQubmFtZTtcclxuICAgICAgICAgICAgdGV4dCA9IHRleHQudGV4dDtcclxuICAgICAgICB9XHJcbiAgICAgICAgdGltZW91dCA9IHRpbWVvdXQgfHwgMTAwMDtcclxuICAgICAgICBtdy5ub3RpZmljYXRpb24uYXBwZW5kKCd3YXJuaW5nJywgdGV4dCwgdGltZW91dCwgbmFtZSk7XHJcbiAgICB9XHJcbn07XHJcbiIsIm13Lm9iamVjdCA9IHtcbiAgICBleHRlbmQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIGV4dGVuZGVkID0ge307XG4gICAgICAgIHZhciBkZWVwID0gZmFsc2U7XG4gICAgICAgIHZhciBpID0gMDtcbiAgICAgICAgdmFyIGwgPSBhcmd1bWVudHMubGVuZ3RoO1xuXG4gICAgICAgIGlmICggT2JqZWN0LnByb3RvdHlwZS50b1N0cmluZy5jYWxsKCBhcmd1bWVudHNbMF0gKSA9PT0gJ1tvYmplY3QgQm9vbGVhbl0nICkge1xuICAgICAgICAgICAgZGVlcCA9IGFyZ3VtZW50c1swXTtcbiAgICAgICAgICAgIGkrKztcbiAgICAgICAgfVxuICAgICAgICB2YXIgbWVyZ2UgPSBmdW5jdGlvbiAob2JqKSB7XG4gICAgICAgICAgICBmb3IgKCB2YXIgcHJvcCBpbiBvYmogKSB7XG4gICAgICAgICAgICAgICAgaWYgKCBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwoIG9iaiwgcHJvcCApICkge1xuICAgICAgICAgICAgICAgICAgICBpZiAoIGRlZXAgJiYgT2JqZWN0LnByb3RvdHlwZS50b1N0cmluZy5jYWxsKG9ialtwcm9wXSkgPT09ICdbb2JqZWN0IE9iamVjdF0nICkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZXh0ZW5kZWRbcHJvcF0gPSBtdy5vYmplY3QuZXh0ZW5kKCB0cnVlLCBleHRlbmRlZFtwcm9wXSwgb2JqW3Byb3BdICk7XG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBleHRlbmRlZFtwcm9wXSA9IG9ialtwcm9wXTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgICAgZm9yICggOyBpIDwgbDsgaSsrICkge1xuICAgICAgICAgICAgdmFyIG9iaiA9IGFyZ3VtZW50c1tpXTtcbiAgICAgICAgICAgIG1lcmdlKG9iaik7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGV4dGVuZGVkO1xuXG4gICAgfVxufTtcbiIsIm13LnN0b3JhZ2UgPSB7XHJcbiAgICBpbml0OiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgaWYgKHdpbmRvdy5sb2NhdGlvbi5ocmVmLmluZGV4T2YoJ2RhdGE6JykgPT09IDAgfHwgISgnbG9jYWxTdG9yYWdlJyBpbiBtd3cpIHx8IC8qIElFIFNlY3VyaXR5IGNvbmZpZ3VyYXRpb25zICovIHR5cGVvZiBtd3dbJ2xvY2FsU3RvcmFnZSddID09PSAndW5kZWZpbmVkJykgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgIHZhciBsc213ID0gbG9jYWxTdG9yYWdlLmdldEl0ZW0oXCJtd1wiKTtcclxuICAgICAgICBpZiAodHlwZW9mIGxzbXcgPT09ICd1bmRlZmluZWQnIHx8IGxzbXcgPT09IG51bGwpIHtcclxuICAgICAgICAgICAgbHNtdyA9IGxvY2FsU3RvcmFnZS5zZXRJdGVtKFwibXdcIiwgXCJ7fVwiKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgdGhpcy5jaGFuZ2UoXCJJTklUXCIpO1xyXG4gICAgICAgIHJldHVybiBsc213O1xyXG4gICAgfSxcclxuICAgIHNldDogZnVuY3Rpb24gKGtleSwgdmFsKSB7XHJcbiAgICAgICAgaWYgKCEoJ2xvY2FsU3RvcmFnZScgaW4gbXd3KSkgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgIHZhciBjdXJyID0gSlNPTi5wYXJzZShsb2NhbFN0b3JhZ2UuZ2V0SXRlbShcIm13XCIpKTtcclxuICAgICAgICBjdXJyW2tleV0gPSB2YWw7XHJcbiAgICAgICAgdmFyIGEgPSBsb2NhbFN0b3JhZ2Uuc2V0SXRlbShcIm13XCIsIEpTT04uc3RyaW5naWZ5KGN1cnIpKTtcclxuICAgICAgICBtdy5zdG9yYWdlLmNoYW5nZShcIkNBTExcIiwga2V5LCB2YWwpO1xyXG4gICAgICAgIHJldHVybiBhO1xyXG4gICAgfSxcclxuICAgIGdldDogZnVuY3Rpb24gKGtleSkge1xyXG4gICAgICAgIGlmICghKCdsb2NhbFN0b3JhZ2UnIGluIG13dykpIHJldHVybiBmYWxzZTtcclxuICAgICAgICB2YXIgY3VyciA9IEpTT04ucGFyc2UobG9jYWxTdG9yYWdlLmdldEl0ZW0oXCJtd1wiKSk7XHJcbiAgICAgICAgcmV0dXJuIGN1cnJba2V5XTtcclxuICAgIH0sXHJcbiAgICBfa2V5czoge30sXHJcbiAgICBjaGFuZ2U6IGZ1bmN0aW9uIChrZXksIGNhbGxiYWNrLCBvdGhlcikge1xyXG4gICAgICAgIGlmICghKCdsb2NhbFN0b3JhZ2UnIGluIG13dykpIHJldHVybiBmYWxzZTtcclxuICAgICAgICBpZiAoa2V5ID09PSAnSU5JVCcgJiYgJ2FkZEV2ZW50TGlzdGVuZXInIGluIGRvY3VtZW50KSB7XHJcbiAgICAgICAgICAgIGFkZEV2ZW50TGlzdGVuZXIoJ3N0b3JhZ2UnLCBmdW5jdGlvbiAoZSkge1xyXG4gICAgICAgICAgICAgICAgaWYgKGUua2V5ID09PSAnbXcnKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgdmFyIF9uZXcgPSBKU09OLnBhcnNlKGUubmV3VmFsdWUgfHwge30pO1xyXG4gICAgICAgICAgICAgICAgICAgIHZhciBfb2xkID0gSlNPTi5wYXJzZShlLm9sZFZhbHVlIHx8IHt9KTtcclxuICAgICAgICAgICAgICAgICAgICB2YXIgZGlmZiA9IG13LnRvb2xzLmdldERpZmYoX25ldywgX29sZCk7XHJcbiAgICAgICAgICAgICAgICAgICAgZm9yICh2YXIgdCBpbiBkaWZmKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmICh0IGluIG13LnN0b3JhZ2UuX2tleXMpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhciBpID0gMCwgbCA9IG13LnN0b3JhZ2UuX2tleXNbdF0ubGVuZ3RoO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZm9yICg7IGkgPCBsOyBpKyspIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5zdG9yYWdlLl9rZXlzW3RdW2ldLmNhbGwoZGlmZlt0XSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0sIGZhbHNlKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgZWxzZSBpZiAoa2V5ID09PSAnQ0FMTCcpIHtcclxuICAgICAgICAgICAgaWYgKCFkb2N1bWVudC5pc0hpZGRlbigpICYmIHR5cGVvZiBtdy5zdG9yYWdlLl9rZXlzW2NhbGxiYWNrXSAhPT0gJ3VuZGVmaW5lZCcpIHtcclxuICAgICAgICAgICAgICAgIHZhciBpID0gMCwgbCA9IG13LnN0b3JhZ2UuX2tleXNbY2FsbGJhY2tdLmxlbmd0aDtcclxuICAgICAgICAgICAgICAgIGZvciAoOyBpIDwgbDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbXcuc3RvcmFnZS5fa2V5c1tjYWxsYmFja11baV0uY2FsbChvdGhlcik7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgIGlmIChrZXkgaW4gbXcuc3RvcmFnZS5fa2V5cykge1xyXG4gICAgICAgICAgICAgICAgbXcuc3RvcmFnZS5fa2V5c1trZXldLnB1c2goY2FsbGJhY2spO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgbXcuc3RvcmFnZS5fa2V5c1trZXldID0gW2NhbGxiYWNrXTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH1cclxufTtcclxubXcuc3RvcmFnZS5pbml0KCk7XHJcbiIsIm13LnRvb2xzLmFsZXJ0ID0gZnVuY3Rpb24gKHRleHQpIHtcbiAgICB2YXIgaHRtbCA9ICcnXG4gICAgICAgICsgJzx0YWJsZSBjbGFzcz1cIm13X2FsZXJ0XCIgd2lkdGg9XCIxMDAlXCIgaGVpZ2h0PVwiMTQwXCIgY2VsbHBhZGRpbmc9XCIwXCIgY2VsbHNwYWNpbmc9XCIwXCI+J1xuICAgICAgICArICc8dHI+J1xuICAgICAgICArICc8dGQgYWxpZ249XCJjZW50ZXJcIiB2YWxpZ249XCJtaWRkbGVcIj48ZGl2IGNsYXNzPVwibXdfYWxlcnRfaG9sZGVyXCI+JyArIHRleHQgKyAnPC9kaXY+PC90ZD4nXG4gICAgICAgICsgJzwvdHI+J1xuICAgICAgICArICc8dHI+J1xuICAgICAgICArICc8dGQgYWxpZ249XCJjZW50ZXJcIiBoZWlnaHQ9XCIyNVwiPjxzcGFuIGNsYXNzPVwibXctdWktYnRuIG13LXVpLWJ0bi1tZWRpdW1cIiBvbmNsaWNrPVwibXcuZGlhbG9nLnJlbW92ZSh0aGlzKTtcIj48Yj4nICsgbXcubXNnLm9rICsgJzwvYj48L3NwYW4+PC90ZD4nXG4gICAgICAgICsgJzwvdHI+J1xuICAgICAgICArICc8L3RhYmxlPic7XG4gICAgaWYgKG13LiQoXCIjbXdfYWxlcnRcIikubGVuZ3RoID09PSAwKSB7XG4gICAgICAgIHJldHVybiBtdy5kaWFsb2coe1xuICAgICAgICAgICAgaHRtbDogaHRtbCxcbiAgICAgICAgICAgIHdpZHRoOiA0MDAsXG4gICAgICAgICAgICBoZWlnaHQ6IDIwMCxcbiAgICAgICAgICAgIG92ZXJsYXk6IGZhbHNlLFxuICAgICAgICAgICAgbmFtZTogXCJtd19hbGVydFwiLFxuICAgICAgICAgICAgdGVtcGxhdGU6IFwibXdfbW9kYWxfYmFzaWNcIlxuICAgICAgICB9KTtcbiAgICB9XG4gICAgZWxzZSB7XG4gICAgICAgIG13LiQoXCIjbXdfYWxlcnQgLm13X2FsZXJ0X2hvbGRlclwiKS5odG1sKHRleHQpO1xuICAgICAgICByZXR1cm4gbXcuJChcIiNtd19hbGVydFwiKVswXS5tb2RhbDtcbiAgICB9XG59O1xuXG5cbm13LnRvb2xzLnByb21wdCA9IGZ1bmN0aW9uIChxdWVzdGlvbiwgY2FsbGJhY2spIHtcbiAgICBpZighcXVlc3Rpb24pIHJldHVybiA7XG4gICAgdmFyIGlkID0gbXcuaWQoJ213LXByb21wdC1pbnB1dCcpO1xuICAgIHF1ZXN0aW9uID0gJzxsYWJlbCBjbGFzcz1cIm13LXVpLWxhYmVsXCI+JytxdWVzdGlvbisnPC9sYWJlbD48aW5wdXQgY2xhc3M9XCJtdy11aS1maWVsZCB3MTAwXCIgaWQ9XCInK2lkKydcIj4nO1xuICAgIHZhciBkaWFsb2cgPSBtdy50b29scy5jb25maXJtKHF1ZXN0aW9uLCBmdW5jdGlvbiAoKXtcbiAgICAgICAgY2FsbGJhY2suY2FsbCh3aW5kb3csIG13LiQoJyMnICsgaWQpLnZhbCgpKTtcbiAgICB9KTtcbiAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpe1xuICAgICAgICBtdy4kKCcjJyArIGlkKS5mb2N1cygpLm9uKCdrZXlkb3duJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgICAgIGlmIChtdy5ldmVudC5pcy5lbnRlcihlKSkge1xuICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwod2luZG93LCBtdy4kKCcjJyArIGlkKS52YWwoKSk7XG4gICAgICAgICAgICAgICAgZGlhbG9nLnJlbW92ZSgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9LCAyMjIpO1xuICAgIHJldHVybiBkaWFsb2c7XG59O1xubXcudG9vbHMuY29uZmlybSA9IGZ1bmN0aW9uIChxdWVzdGlvbiwgY2FsbGJhY2spIHtcbiAgICBpZih0eXBlb2YgcXVlc3Rpb24gPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgY2FsbGJhY2sgPSBxdWVzdGlvbjtcbiAgICAgICAgcXVlc3Rpb24gPSAnQXJlIHlvdSBzdXJlPyc7XG4gICAgfVxuICAgIHF1ZXN0aW9uID0gcXVlc3Rpb24gfHwgJ0FyZSB5b3Ugc3VyZT8nO1xuICAgICAgICB2YXIgaHRtbCA9ICcnXG4gICAgICAgICAgICArICc8dGFibGUgY2xhc3M9XCJtd19hbGVydFwiIHdpZHRoPVwiMTAwJVwiIGhlaWdodD1cIjE0MFwiIGNlbGxwYWRkaW5nPVwiMFwiIGNlbGxzcGFjaW5nPVwiMFwiPidcbiAgICAgICAgICAgICsgJzx0cj4nXG4gICAgICAgICAgICArICc8dGQgdmFsaWduPVwibWlkZGxlXCI+PGRpdiBjbGFzcz1cIm13X2FsZXJ0X2hvbGRlclwiPicgKyBxdWVzdGlvbiArICc8L2Rpdj48L3RkPidcbiAgICAgICAgICAgICsgJzwvdHI+J1xuICAgICAgICAgICAgKyAnPC90YWJsZT4nO1xuXG4gICAgICAgIHZhciBvayA9IG13LnRvcCgpLiQoJzxzcGFuIHRhYmluZGV4PVwiOTk5OTlcIiBjbGFzcz1cIm13LXVpLWJ0biBtdy11aS1idG4tbWVkaXVtIG13LXVpLWJ0bi1pbmZvXCI+JyArIG13Lm1zZy5vayArICc8L3NwYW4+Jyk7XG4gICAgICAgIHZhciBjYW5jZWwgPSBtdy50b3AoKS4kKCc8c3BhbiBjbGFzcz1cIm13LXVpLWJ0biBtdy11aS1idG4tbWVkaXVtIFwiPicgKyBtdy5tc2cuY2FuY2VsICsgJzwvc3Bhbj4nKTtcbiAgICAgICAgdmFyIG1vZGFsO1xuXG4gICAgICAgIGlmIChtdy4kKFwiI213X2NvbmZpcm1fbW9kYWxcIikubGVuZ3RoID09PSAwKSB7XG4gICAgICAgICAgICBtb2RhbCA9IG13LnRvcCgpLmRpYWxvZyh7XG4gICAgICAgICAgICAgICAgY29udGVudDogaHRtbCxcbiAgICAgICAgICAgICAgICB3aWR0aDogNDAwLFxuICAgICAgICAgICAgICAgIGhlaWdodDogJ2F1dG8nLFxuICAgICAgICAgICAgICAgIGF1dG9IZWlnaHQ6IHRydWUsXG4gICAgICAgICAgICAgICAgb3ZlcmxheTogZmFsc2UsXG4gICAgICAgICAgICAgICAgbmFtZTogXCJtd19jb25maXJtX21vZGFsXCIsXG4gICAgICAgICAgICAgICAgZm9vdGVyOiBbY2FuY2VsLCBva10sXG4gICAgICAgICAgICAgICAgdGl0bGU6IG13LmxhbmcoJ0NvbmZpcm0nKVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBtdy4kKFwiI213X2NvbmZpcm1fbW9kYWwgLm13X2FsZXJ0X2hvbGRlclwiKS5odG1sKHF1ZXN0aW9uKTtcbiAgICAgICAgICAgIG1vZGFsID0gbXcuJChcIiNtd19jb25maXJtX21vZGFsXCIpWzBdLm1vZGFsO1xuICAgICAgICB9XG5cbiAgICAgICAgb2sub24oJ2tleWRvd24nLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgaWYgKGUua2V5Q29kZSA9PT0gMTMgfHwgZS5rZXlDb2RlID09PSAzMikge1xuICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwod2luZG93KTtcbiAgICAgICAgICAgICAgICBtb2RhbC5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgICBjYW5jZWwub24oJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbW9kYWwucmVtb3ZlKCk7XG4gICAgICAgIH0pO1xuICAgICAgICBvay5vbignY2xpY2snLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBjYWxsYmFjay5jYWxsKHdpbmRvdyk7XG4gICAgICAgICAgICBtb2RhbC5yZW1vdmUoKTtcbiAgICAgICAgfSk7XG4gICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbXcuJChvaykuZm9jdXMoKTtcbiAgICAgICAgfSwgMTIwKTtcbiAgICAgICAgcmV0dXJuIG1vZGFsO1xuICAgIH07XG4iLCJtdy50YWJzID0gZnVuY3Rpb24gKG9iaiwgZWxlbWVudCwgbW9kZWwpIHtcbiAgICAvKlxuICAgICpcbiAgICAqICB7XG4gICAgKiAgICAgICBsaW5rYWJsZTogJ2xpbmsnIHwgJ2F1dG8nLFxuICAgICogICAgICAgbmF2OiBzdHJpbmdcbiAgICAqICAgICAgIHRhYnM6IHN0cmluZ1xuICAgICogICAgICAgb25jbGljazogZnVuY3Rpb25cbiAgICAqICB9XG4gICAgKlxuICAgICogKi9cbiAgICBlbGVtZW50ID0gZWxlbWVudCB8fCBtd2QuYm9keTtcbiAgICBtb2RlbCA9IHR5cGVvZiBtb2RlbCA9PT0gJ3VuZGVmaW5lZCcgPyB0cnVlIDogbW9kZWw7XG4gICAgaWYgKG1vZGVsKSB7XG4gICAgICAgIG1vZGVsID0ge1xuICAgICAgICAgICAgc2V0OiBmdW5jdGlvbiAoaSkge1xuICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgaSA9PT0gJ251bWJlcicpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKCEkKG9iai5uYXYpLmVxKGkpLmhhc0NsYXNzKGFjdGl2ZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQob2JqLm5hdikucmVtb3ZlQ2xhc3MoYWN0aXZlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQob2JqLm5hdikuZXEoaSkuYWRkQ2xhc3MoYWN0aXZlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQob2JqLnRhYnMpLmhpZGUoKS5lcShpKS5zaG93KCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgc2V0TGFzdENsaWNrZWQ6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBpZiAoKHR5cGVvZihvYmoubGFzdENsaWNrZWRUYWJJbmRleCkgIT0gJ3VuZGVmaW5lZCcpICYmIG9iai5sYXN0Q2xpY2tlZFRhYkluZGV4ICE9PSBudWxsKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuc2V0KG9iai5sYXN0Q2xpY2tlZFRhYkluZGV4KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgdW5zZXQ6IGZ1bmN0aW9uIChpKSB7XG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBpID09PSAnbnVtYmVyJykge1xuICAgICAgICAgICAgICAgICAgICBpZiAoJChvYmoubmF2KS5lcShpKS5oYXNDbGFzcyhhY3RpdmUpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKG9iai5uYXYpLmVxKGkpLnJlbW92ZUNsYXNzKGFjdGl2ZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKG9iai50YWJzKS5oaWRlKCkuZXEoaSkuaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIHRvZ2dsZTogZnVuY3Rpb24gKGkpIHtcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGkgPT09ICdudW1iZXInKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmICgkKG9iai5uYXYpLmVxKGkpLmhhc0NsYXNzKGFjdGl2ZSkpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMudW5zZXQoaSk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLnNldChpKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICB9XG4gICAgdmFyIGFjdGl2ZSA9IG9iai5hY3RpdmVOYXYgfHwgb2JqLmFjdGl2ZUNsYXNzIHx8IFwiYWN0aXZlIGFjdGl2ZS1pbmZvXCIsXG4gICAgICAgIGZpcnN0QWN0aXZlID0gMDtcblxuICAgIG9iai5sYXN0Q2xpY2tlZFRhYkluZGV4ID0gbnVsbDtcblxuICAgIGlmIChvYmoubGlua2FibGUpIHtcblxuXG4gICAgICAgIGlmIChvYmoubGlua2FibGUgPT09ICdsaW5rJykge1xuXG4gICAgICAgIH0gZWxzZSBpZiAodHlwZW9mIG9iai5saW5rYWJsZSA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgICAgICQod2luZG93KS5vbignbG9hZCBoYXNoY2hhbmdlJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHZhciBwYXJhbSA9IG13LnVybC53aW5kb3dIYXNoUGFyYW0ob2JqLmxpbmthYmxlKTtcbiAgICAgICAgICAgICAgICBpZihwYXJhbSkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgZWwgPSAkKCdbZGF0YS0nICsgb2JqLmxpbmthYmxlICsgJz1cIicgKyBwYXJhbSArICdcIl0nKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICQob2JqLm5hdikuZWFjaChmdW5jdGlvbiAoaSkge1xuICAgICAgICAgICAgICAgIHRoaXMuZGF0YXNldC5saW5rYWJsZSA9IG9iai5saW5rYWJsZSArICctJyArIGk7XG4gICAgICAgICAgICAgICAgKGZ1bmN0aW9uIChsaW5rYWJsZSwgaSkge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLm9uY2xpY2sgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy51cmwud2luZG93SGFzaFBhcmFtKGxpbmthYmxlLCBpKTtcbiAgICAgICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICB9KShvYmoubGlua2FibGUsIGkpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICB9XG5cblxuICAgIG13LiQob2JqLm5hdikub24oJ2NsaWNrJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgaWYgKG9iai5saW5rYWJsZSkge1xuICAgICAgICAgICAgaWYgKG9iai5saW5rYWJsZSA9PT0gJ2xpbmsnKSB7XG5cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIGlmICghJCh0aGlzKS5oYXNDbGFzcyhhY3RpdmUpKSB7XG4gICAgICAgICAgICAgICAgdmFyIGkgPSBtdy50b29scy5pbmRleCh0aGlzLCBtdy4kKG9iai5uYXYpLmdldCgpLCBtdy4kKG9iai5uYXYpWzBdLm5vZGVOYW1lKTtcbiAgICAgICAgICAgICAgICBtdy4kKG9iai5uYXYpLnJlbW92ZUNsYXNzKGFjdGl2ZSk7XG4gICAgICAgICAgICAgICAgbXcuJCh0aGlzKS5hZGRDbGFzcyhhY3RpdmUpO1xuICAgICAgICAgICAgICAgIG13LiQob2JqLnRhYnMpLmhpZGUoKS5lcShpKS5zaG93KCk7XG4gICAgICAgICAgICAgICAgb2JqLmxhc3RDbGlja2VkVGFiSW5kZXggPSBpO1xuICAgICAgICAgICAgICAgIGlmICh0eXBlb2Ygb2JqLm9uY2xpY2sgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICAgICAgb2JqLm9uY2xpY2suY2FsbCh0aGlzLCBtdy4kKG9iai50YWJzKS5lcShpKVswXSwgZSwgaSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgaWYgKG9iai50b2dnbGUgPT09IHRydWUpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuJCh0aGlzKS5yZW1vdmVDbGFzcyhhY3RpdmUpO1xuICAgICAgICAgICAgICAgICAgICBtdy4kKG9iai50YWJzKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2Ygb2JqLm9uY2xpY2sgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBpID0gbXcudG9vbHMuaW5kZXgodGhpcywgZWxlbWVudCwgb2JqLm5hdik7XG4gICAgICAgICAgICAgICAgICAgICAgICBvYmoub25jbGljay5jYWxsKHRoaXMsIG13LiQob2JqLnRhYnMpLmVxKGkpWzBdLCBlLCBpKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG5cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0pLmVhY2goZnVuY3Rpb24gKGkpIHtcbiAgICAgICAgaWYgKG13LnRvb2xzLmhhc0NsYXNzKHRoaXMsIGFjdGl2ZSkpIHtcbiAgICAgICAgICAgIGZpcnN0QWN0aXZlID0gaTtcbiAgICAgICAgfVxuICAgIH0pO1xuICAgIG1vZGVsLnNldChmaXJzdEFjdGl2ZSk7XG4gICAgcmV0dXJuIG1vZGVsO1xufTtcbiIsIi8vIFVSTCBTdHJpbmdzIC0gTWFuaXB1bGF0aW9uc1xuXG5qc29uMnVybCA9IGZ1bmN0aW9uKG9iail7IHZhciB0PVtdO2Zvcih2YXIgeCBpbiBvYmopdC5wdXNoKHgrXCI9XCIrZW5jb2RlVVJJQ29tcG9uZW50KG9ialt4XSkpO3JldHVybiB0LmpvaW4oXCImXCIpLnJlcGxhY2UoL3VuZGVmaW5lZC9nLCAnZmFsc2UnKSB9O1xuXG5cbm13LnVybCA9IHtcbiAgICBoYXNoU3RhcnQ6ICcnLFxuICAgIGdldERvbWFpbjpmdW5jdGlvbih1cmwpe1xuICAgICAgcmV0dXJuIHVybC5tYXRjaCgvOlxcL1xcLyh3d3dcXC4pPyguW14vOl0rKS8pWzJdO1xuICAgIH0sXG4gICAgcmVtb3ZlSGFzaDpmdW5jdGlvbih1cmwpe1xuICAgICAgICByZXR1cm4gdXJsLnJlcGxhY2UoIC8jLiovLCBcIlwiKTtcbiAgICB9LFxuICAgIGdldEhhc2g6ZnVuY3Rpb24odXJsKXtcbiAgICAgIHJldHVybiB1cmwuaW5kZXhPZignIycpICE9PSAtMSA/IHVybC5zdWJzdHJpbmcodXJsLmluZGV4T2YoJyMnKSwgdXJsLmxlbmd0aCkgOiBcIlwiO1xuICAgIH0sXG4gICAgc3RyaXA6ZnVuY3Rpb24odXJsKXtcbiAgICAgIHJldHVybiB1cmwucmVwbGFjZSgvI1teI10qJC8sIFwiXCIpLnJlcGxhY2UoL1xcP1teXFw/XSokLywgXCJcIik7XG4gICAgfSxcbiAgICBnZXRVcmxQYXJhbXM6ZnVuY3Rpb24odXJsKXtcbiAgICAgICAgdXJsID0gbXcudXJsLnJlbW92ZUhhc2godXJsKTtcbiAgICAgICAgaWYodXJsLmNvbnRhaW5zKCc/Jykpe1xuICAgICAgICAgIHZhciBhcnIgPSB1cmwuc2xpY2UodXJsLmluZGV4T2YoJz8nKSArIDEpLnNwbGl0KCcmJyk7XG4gICAgICAgICAgdmFyIG9iaiA9IHt9LCBpPTAsIGxlbiA9IGFyci5sZW5ndGg7XG4gICAgICAgICAgZm9yKCA7IGk8bGVuOyBpKyspe1xuICAgICAgICAgICAgdmFyIHBfYXJyID0gYXJyW2ldLnNwbGl0KCc9Jyk7XG4gICAgICAgICAgICBvYmpbcF9hcnJbMF1dID0gcF9hcnJbMV07XG4gICAgICAgICAgfVxuICAgICAgICAgIHJldHVybiBvYmo7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZXtyZXR1cm4ge30gfVxuICAgIH0sXG4gICAgc2V0X3BhcmFtOmZ1bmN0aW9uKHBhcmFtLCB2YWx1ZSwgdXJsKXtcbiAgICAgICAgdXJsID0gdXJsIHx8IHdpbmRvdy5sb2NhdGlvbi5ocmVmO1xuICAgICAgICB2YXIgaGFzaCA9IG13LnVybC5nZXRIYXNoKHVybCk7XG4gICAgICAgIHZhciBwYXJhbXMgPSBtdy51cmwuZ2V0VXJsUGFyYW1zKHVybCk7XG4gICAgICAgIHBhcmFtc1twYXJhbV0gPSB2YWx1ZTtcbiAgICAgICAgdmFyIHBhcmFtc19zdHJpbmcgPSBqc29uMnVybChwYXJhbXMpO1xuICAgICAgICB1cmwgPSBtdy51cmwuc3RyaXAodXJsKTtcbiAgICAgICAgcmV0dXJuIGRlY29kZVVSSUNvbXBvbmVudCAodXJsICsgXCI/XCIgKyBwYXJhbXNfc3RyaW5nICsgaGFzaCk7XG4gICAgfSxcbiAgICByZW1vdmVfcGFyYW06ZnVuY3Rpb24odXJsLCBwYXJhbSl7XG4gICAgICAgIHZhciBoYXNoID0gbXcudXJsLmdldEhhc2godXJsKTtcbiAgICAgICAgdmFyIHBhcmFtcyA9IG13LnVybC5nZXRVcmxQYXJhbXModXJsKTtcbiAgICAgICAgZGVsZXRlIHBhcmFtc1twYXJhbV07XG4gICAgICAgIHZhciBwYXJhbXNfc3RyaW5nID0ganNvbjJ1cmwocGFyYW1zKTtcbiAgICAgICAgdXJsID0gbXcudXJsLnN0cmlwKHVybCk7XG4gICAgICAgIHJldHVybiBkZWNvZGVVUklDb21wb25lbnQgKHVybCArIFwiP1wiICsgcGFyYW1zX3N0cmluZyArIGhhc2gpO1xuICAgIH0sXG4gICAgZ2V0SGFzaFBhcmFtczpmdW5jdGlvbihoYXNoKXtcbiAgICAgICAgdmFyIHIgPSBuZXcgUmVnRXhwKG13LnVybC5oYXNoU3RhcnQsIFwiZ1wiKTtcbiAgICAgICAgaGFzaCA9IGhhc2gucmVwbGFjZShyLCBcIlwiKTtcbiAgICAgICAgaGFzaCA9IGhhc2gucmVwbGFjZSgvXFw/L2csIFwiXCIpO1xuICAgICAgICBpZihoYXNoID09PSAnJyB8fCBoYXNoID09PSAnIycpe1xuICAgICAgICAgIHJldHVybiB7fTtcbiAgICAgICAgfVxuICAgICAgICBlbHNle1xuICAgICAgICAgIGhhc2ggPSBoYXNoLnJlcGxhY2UoLyMvZywgXCJcIik7XG4gICAgICAgICAgdmFyIGFyciA9IGhhc2guc3BsaXQoJyYnKTtcbiAgICAgICAgICB2YXIgb2JqID0ge30sIGk9MCwgbGVuID0gYXJyLmxlbmd0aDtcbiAgICAgICAgICBmb3IoIDsgaTxsZW47IGkrKyl7XG4gICAgICAgICAgICB2YXIgcF9hcnIgPSBhcnJbaV0uc3BsaXQoJz0nKTtcbiAgICAgICAgICAgIG9ialtwX2FyclswXV0gPSBwX2FyclsxXTtcbiAgICAgICAgICB9XG4gICAgICAgICAgcmV0dXJuIG9iajtcbiAgICAgICAgfVxuICAgIH0sXG4gICAgc2V0SGFzaFBhcmFtOmZ1bmN0aW9uKHBhcmFtLCB2YWx1ZSwgaGFzaCl7XG5cbiAgICAgIGhhc2ggPSBoYXNoIHx8IG13Lmhhc2goKTtcbiAgICAgIHZhciBvYmogPSBtdy51cmwuZ2V0SGFzaFBhcmFtcyhoYXNoKTtcbiAgICAgIG9ialtwYXJhbV0gPSB2YWx1ZTtcbiAgICAgIHJldHVybiBtdy51cmwuaGFzaFN0YXJ0ICsgZGVjb2RlVVJJQ29tcG9uZW50KGpzb24ydXJsKG9iaikpO1xuICAgIH0sXG4gICAgd2luZG93SGFzaFBhcmFtOmZ1bmN0aW9uKGEsYil7XG4gICAgICBpZihiICE9PSB1bmRlZmluZWQpe1xuICAgICAgICBtdy5oYXNoKG13LnVybC5zZXRIYXNoUGFyYW0oYSxiKSk7XG4gICAgICB9XG4gICAgICBlbHNle1xuICAgICAgICByZXR1cm4gbXcudXJsLmdldEhhc2hQYXJhbXMobXcuaGFzaCgpKVthXTtcbiAgICAgIH1cbiAgICB9LFxuICAgIGRlbGV0ZUhhc2hQYXJhbTpmdW5jdGlvbihoYXNoLCBwYXJhbSl7XG4gICAgICAgIHZhciBwYXJhbXMgPSBtdy51cmwuZ2V0SGFzaFBhcmFtcyhoYXNoKTtcbiAgICAgICAgZGVsZXRlIHBhcmFtc1twYXJhbV07XG4gICAgICAgIHZhciBwYXJhbXNfc3RyaW5nID0gZGVjb2RlVVJJQ29tcG9uZW50KG13LnVybC5oYXNoU3RhcnQranNvbjJ1cmwocGFyYW1zKSk7XG4gICAgICAgIHJldHVybiBwYXJhbXNfc3RyaW5nO1xuICAgIH0sXG4gICAgd2luZG93RGVsZXRlSGFzaFBhcmFtOmZ1bmN0aW9uKHBhcmFtKXtcbiAgICAgICBtdy5oYXNoKG13LnVybC5kZWxldGVIYXNoUGFyYW0od2luZG93LmxvY2F0aW9uLmhhc2gsIHBhcmFtKSk7XG4gICAgfSxcbiAgICB3aGljaEhhc2hQYXJhbXNIYXNCZWVuUmVtb3ZlZDpmdW5jdGlvbihjdXJySGFzaCwgcHJldkhhc2gpe1xuICAgICAgICB2YXIgY3VyciA9IG13LnVybC5nZXRIYXNoUGFyYW1zKGN1cnJIYXNoKTtcbiAgICAgICAgdmFyIHByZXYgPSBtdy51cmwuZ2V0SGFzaFBhcmFtcyhwcmV2SGFzaCk7XG4gICAgICAgIHZhciBoYXNoZXMgPSBbXTtcbiAgICAgICAgZm9yKHZhciB4IGluIHByZXYpe1xuICAgICAgICAgICAgY3Vyclt4XSA9PT0gdW5kZWZpbmVkID8gaGFzaGVzLnB1c2goeCkgOiAnJztcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gaGFzaGVzO1xuICAgIH0sXG4gICAgaGFzaFBhcmFtVG9BY3RpdmVOb2RlOmZ1bmN0aW9uKHBhcmFtLCBjbGFzc05hbWVzcGFjZSwgY29udGV4dCl7XG4gICAgICAgIGNvbnRleHQgPSBjb250ZXh0IHx8IG13ZC5ib2R5O1xuICAgICAgICB2YXIgdmFsID0gIG13LnVybC53aW5kb3dIYXNoUGFyYW0ocGFyYW0pO1xuICAgICAgICBtdy4kKCcuJytjbGFzc05hbWVzcGFjZSwgY29udGV4dCkucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICB2YXIgYWN0aXZlID0gbXcuJCgnLicrY2xhc3NOYW1lc3BhY2UgKyAnLScgKyB2YWwsIGNvbnRleHQpO1xuICAgICAgICBpZihhY3RpdmUubGVuZ3RoID4gMCl7XG4gICAgICAgICAgIGFjdGl2ZS5hZGRDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZXtcbiAgICAgICAgICAgbXcuJCgnLicrY2xhc3NOYW1lc3BhY2UgKyAnLW5vbmUnLCBjb250ZXh0KS5hZGRDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIG13UGFyYW1zOmZ1bmN0aW9uKHVybCl7XG4gICAgICAgIHVybCA9IHVybCB8fCB3aW5kb3cubG9jYXRpb24ucGF0aG5hbWU7XG4gICAgICAgIHVybCA9IG13LnVybC5yZW1vdmVIYXNoKHVybCk7XG4gICAgICAgIHZhciBhcnIgPSB1cmwuc3BsaXQoJy8nKTtcbiAgICAgICAgdmFyIG9iaiA9IHt9O1xuICAgICAgICB2YXIgaT0wLGw9YXJyLmxlbmd0aDtcbiAgICAgICAgZm9yKCA7IGk8bDsgaSsrKXtcbiAgICAgICAgICAgIGlmKGFycltpXS5pbmRleE9mKCc6JykgIT09IC0xICYmIGFycltpXS5pbmRleE9mKCdodHRwJykgPT09IC0xKXtcbiAgICAgICAgICAgICAgICB2YXIgcCA9IGFycltpXS5zcGxpdCgnOicpO1xuICAgICAgICAgICAgICAgIG9ialtwWzBdXSA9IHBbMV07XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIG9iajtcbiAgICB9LFxuICAgIHR5cGU6ZnVuY3Rpb24odXJsKXtcbiAgICAgICAgaWYoIXVybCkgcmV0dXJuO1xuICAgICAgdXJsID0gdXJsLnRvU3RyaW5nKCk7XG4gICAgICBpZiggdXJsID09PSAgJ2ZhbHNlJyApe1xuICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgIH1cbiAgICAgIGlmKHVybC5pbmRleE9mKCcvaW1hZ2VzLnVuc3BsYXNoLmNvbS8nKSAhPT0gLTEpe1xuICAgICAgICAgIHJldHVybiAnaW1hZ2UnO1xuICAgICAgfVxuICAgICAgdmFyIGV4dGVuc2lvbiA9IHVybC5zcGxpdCgnLicpLnBvcCgpO1xuICAgICAgdmFyIGltYWdlcyA9ICdqcGcscG5nLGdpZixqcGVnLGJtcCx3ZWJwJztcbiAgICAgIGlmKGltYWdlcy5jb250YWlucyhleHRlbnNpb24pKXtcbiAgICAgICAgcmV0dXJuICdpbWFnZSc7XG4gICAgICB9XG4gICAgICBlbHNlIGlmKGV4dGVuc2lvbiA9PT0gJ3N3Zicpe3JldHVybiAnZmxhc2gnO31cbiAgICAgIGVsc2UgaWYoZXh0ZW5zaW9uID09PSAncGRmJyl7cmV0dXJuICdwZGYnO31cbiAgICAgIGVsc2UgaWYodXJsLmNvbnRhaW5zKCd5b3V0dWJlLmNvbScpIHx8IHVybC5jb250YWlucygneW91dHUuYmUnKSl7cmV0dXJuICd5b3V0dWJlJzt9XG4gICAgICBlbHNlIGlmKHVybC5jb250YWlucygndmltZW8uY29tJykpe3JldHVybiAndmltZW8nO31cblxuICAgICAgZWxzZXsgcmV0dXJuICdsaW5rJzsgfVxuICAgIH1cbn07XG5cblxuIl0sInNvdXJjZVJvb3QiOiIifQ==
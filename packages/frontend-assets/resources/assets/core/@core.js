import "./options.js";

mw.pauseSave = false;

mw.askusertostay = false;

if (window.top === window){
    window.onbeforeunload = function() {
        if (mw.askusertostay) {
            mw.notification.warning(mw.lang('You have unsaved changes'));
            return mw.lang('You have unsaved changes');
        }
    };
}


window.mwd = document;
window.mww  = window;



mw.doc = document;
mw.win = window;


mw.loaded = false;

mw._random = new Date().getTime();

mw.random = function() {
    return mw._random++;
};

mw.id = function(prefix) {
    prefix = prefix || 'mw-';
    return prefix + mw.random();
};

mw.onLive = function(callback) {
    if (typeof mw.settings.liveEdit === 'boolean' && mw.settings.liveEdit) {
        callback.call(this)
    }
};
mw.onAdmin = function(callback) {
    if ( window['mwAdmin'] ) {
        callback.call(this);
    }
};


mw.target = {};

mw.log = function() {
    if (mw.settings.debug) {
        top.console.log(...arguments);
    }
};


/*
 * AI-263 Phase B5 (cycle-185 2026-05-11) — `mw.$` vanilla
 * shim with jQuery passthrough.
 *
 * `mw.$()` used to call `jQuery(...)` directly — would throw
 * `ReferenceError: jQuery is not defined` on public Bootstrap
 * pages that don't eagerly load jQuery. After Phase B5, mw.$:
 *
 *   - When jQuery IS loaded: passes through to jQuery (admin,
 *     legacy admin, anywhere mw_require_jquery() opted in).
 *     Zero regression on those surfaces.
 *
 *   - When jQuery is NOT loaded: returns a vanilla DOM
 *     wrapper (MwDomCollection) that implements the chainable
 *     subset PM specified (addClass/removeClass/hasClass/attr/
 *     on/off/html/text/val/css/remove/find/first/last/eq/
 *     parent/children/each/length/[idx] indexed access).
 *
 *   - Missing methods on the vanilla wrapper log a `mw.$`
 *     warning + fall back to a no-op (preferable to a
 *     hard throw that kills the rest of the JS execution).
 *
 * This is the cycle that finally lets `mw_require_jquery()`
 * drop from Bootstrap master.blade.php → 806KB savings.
 */
function __mwDomToArray(selector, context) {
    if (selector == null) return [];
    if (selector === window || selector === document || (selector && selector.nodeType)) {
        return [selector];
    }
    if (selector instanceof NodeList || selector instanceof HTMLCollection ||
        (Array.isArray && Array.isArray(selector))) {
        return Array.prototype.slice.call(selector);
    }
    if (typeof selector === 'string') {
        // HTML string — build a detached element tree.
        if (selector.indexOf('<') !== -1) {
            var tmp = document.createElement('div');
            tmp.innerHTML = selector.trim();
            return Array.prototype.slice.call(tmp.childNodes).filter(function (n) {
                return n.nodeType === 1;
            });
        }
        var root = context || document;
        try {
            return Array.prototype.slice.call(root.querySelectorAll(selector));
        } catch (e) {
            return [];
        }
    }
    if (typeof selector === 'function') {
        // jQuery `$(fn)` ready shorthand.
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', selector);
        } else {
            try { selector(); } catch (e) {}
        }
        return [];
    }
    return [];
}

function MwDomCollection(els) {
    var i;
    for (i = 0; i < els.length; i++) this[i] = els[i];
    this.length = els.length;
}
MwDomCollection.prototype.each = function (fn) {
    for (var i = 0; i < this.length; i++) {
        if (fn.call(this[i], i, this[i]) === false) break;
    }
    return this;
};
MwDomCollection.prototype.addClass = function (cls) {
    var classes = String(cls).split(/\s+/);
    return this.each(function () {
        if (!this.classList) return;
        for (var i = 0; i < classes.length; i++) if (classes[i]) this.classList.add(classes[i]);
    });
};
MwDomCollection.prototype.removeClass = function (cls) {
    if (cls == null) {
        return this.each(function () { if (this.className != null) this.className = ''; });
    }
    var classes = String(cls).split(/\s+/);
    return this.each(function () {
        if (!this.classList) return;
        for (var i = 0; i < classes.length; i++) if (classes[i]) this.classList.remove(classes[i]);
    });
};
MwDomCollection.prototype.hasClass = function (cls) {
    for (var i = 0; i < this.length; i++) {
        if (this[i].classList && this[i].classList.contains(cls)) return true;
    }
    return false;
};
MwDomCollection.prototype.toggleClass = function (cls) {
    return this.each(function () { if (this.classList) this.classList.toggle(cls); });
};
MwDomCollection.prototype.attr = function (name, value) {
    if (value === undefined && typeof name !== 'object') {
        return this.length ? this[0].getAttribute(name) : undefined;
    }
    if (typeof name === 'object') {
        return this.each(function () {
            for (var k in name) if (Object.prototype.hasOwnProperty.call(name, k)) this.setAttribute(k, name[k]);
        });
    }
    return this.each(function () {
        if (value === null) this.removeAttribute(name);
        else this.setAttribute(name, value);
    });
};
MwDomCollection.prototype.removeAttr = function (name) {
    return this.each(function () { this.removeAttribute(name); });
};
MwDomCollection.prototype.on = function (events, handler) {
    var evs = String(events).split(/\s+/);
    return this.each(function () {
        for (var i = 0; i < evs.length; i++) if (evs[i]) this.addEventListener(evs[i], handler);
    });
};
MwDomCollection.prototype.off = function (events, handler) {
    var evs = String(events).split(/\s+/);
    return this.each(function () {
        for (var i = 0; i < evs.length; i++) if (evs[i]) this.removeEventListener(evs[i], handler);
    });
};
MwDomCollection.prototype.bind = MwDomCollection.prototype.on;
MwDomCollection.prototype.unbind = MwDomCollection.prototype.off;
MwDomCollection.prototype.trigger = function (eventName, params) {
    return this.each(function () {
        var ev;
        try { ev = new CustomEvent(eventName, { bubbles: true, detail: params }); }
        catch (e) { ev = document.createEvent('Event'); ev.initEvent(eventName, true, true); ev.detail = params; }
        this.dispatchEvent(ev);
    });
};
MwDomCollection.prototype.html = function (value) {
    if (value === undefined) return this.length ? this[0].innerHTML : undefined;
    return this.each(function () { this.innerHTML = value; });
};
MwDomCollection.prototype.text = function (value) {
    if (value === undefined) return this.length ? this[0].textContent : undefined;
    return this.each(function () { this.textContent = value; });
};
MwDomCollection.prototype.val = function (value) {
    if (value === undefined) return this.length ? this[0].value : undefined;
    return this.each(function () { this.value = value; });
};
MwDomCollection.prototype.css = function (prop, value) {
    if (typeof prop === 'object') {
        return this.each(function () {
            for (var k in prop) if (Object.prototype.hasOwnProperty.call(prop, k)) this.style[k] = prop[k];
        });
    }
    if (value === undefined) return this.length ? getComputedStyle(this[0])[prop] : undefined;
    return this.each(function () { this.style[prop] = value; });
};
MwDomCollection.prototype.remove = function () {
    return this.each(function () { if (this.parentNode) this.parentNode.removeChild(this); });
};
MwDomCollection.prototype.find = function (selector) {
    var out = [];
    this.each(function () {
        if (!this.querySelectorAll) return;
        var found = this.querySelectorAll(selector);
        for (var i = 0; i < found.length; i++) out.push(found[i]);
    });
    return new MwDomCollection(out);
};
MwDomCollection.prototype.first = function () { return new MwDomCollection(this.length ? [this[0]] : []); };
MwDomCollection.prototype.last = function () { return new MwDomCollection(this.length ? [this[this.length - 1]] : []); };
MwDomCollection.prototype.eq = function (n) {
    n = parseInt(n, 10) || 0;
    if (n < 0) n = this.length + n;
    return new MwDomCollection(this[n] ? [this[n]] : []);
};
MwDomCollection.prototype.parent = function () {
    var out = [];
    this.each(function () { if (this.parentNode) out.push(this.parentNode); });
    return new MwDomCollection(out);
};
MwDomCollection.prototype.children = function () {
    var out = [];
    this.each(function () {
        if (this.children) {
            for (var i = 0; i < this.children.length; i++) out.push(this.children[i]);
        }
    });
    return new MwDomCollection(out);
};
MwDomCollection.prototype.closest = function (selector) {
    var out = [];
    this.each(function () { if (this.closest) { var c = this.closest(selector); if (c) out.push(c); } });
    return new MwDomCollection(out);
};
MwDomCollection.prototype.is = function (selector) {
    if (!this.length) return false;
    var el = this[0];
    if (!el.matches) return false;
    return el.matches(selector);
};
MwDomCollection.prototype.append = function (content) {
    return this.each(function () {
        var el = this;
        if (typeof content === 'string') el.insertAdjacentHTML('beforeend', content);
        else if (content instanceof MwDomCollection) content.each(function () { el.appendChild(this); });
        else if (content && content.nodeType) el.appendChild(content);
    });
};
MwDomCollection.prototype.prepend = function (content) {
    return this.each(function () {
        var el = this;
        if (typeof content === 'string') el.insertAdjacentHTML('afterbegin', content);
        else if (content && content.nodeType) el.insertBefore(content, el.firstChild);
    });
};
MwDomCollection.prototype.show = function () { return this.each(function () { this.style.display = ''; }); };
MwDomCollection.prototype.hide = function () { return this.each(function () { this.style.display = 'none'; }); };
MwDomCollection.prototype.data = function (key, value) {
    if (value === undefined) {
        if (!this.length) return undefined;
        return this[0].dataset ? this[0].dataset[key] : undefined;
    }
    return this.each(function () { if (this.dataset) this.dataset[key] = value; });
};
MwDomCollection.prototype.scrollTop = function (value) {
    if (value === undefined) return this.length ? this[0].scrollTop : 0;
    return this.each(function () { this.scrollTop = value; });
};
MwDomCollection.prototype.scroll = function (handler) {
    return this.on('scroll', handler);
};
MwDomCollection.prototype.width = function () {
    return this.length ? (this[0] === window ? window.innerWidth : this[0].clientWidth) : 0;
};
MwDomCollection.prototype.height = function () {
    return this.length ? (this[0] === window ? window.innerHeight : this[0].clientHeight) : 0;
};
MwDomCollection.prototype.outerWidth = function (includeMargin) {
    if (!this.length) return 0;
    if (this[0] === window) return window.innerWidth;
    var rect = this[0].getBoundingClientRect();
    var w = rect.width;
    if (includeMargin) {
        var s = getComputedStyle(this[0]);
        w += (parseFloat(s.marginLeft) || 0) + (parseFloat(s.marginRight) || 0);
    }
    return w;
};
MwDomCollection.prototype.outerHeight = function (includeMargin) {
    if (!this.length) return 0;
    if (this[0] === window) return window.innerHeight;
    var rect = this[0].getBoundingClientRect();
    var h = rect.height;
    if (includeMargin) {
        var s = getComputedStyle(this[0]);
        h += (parseFloat(s.marginTop) || 0) + (parseFloat(s.marginBottom) || 0);
    }
    return h;
};
MwDomCollection.prototype.clone = function () {
    var out = [];
    this.each(function () { out.push(this.cloneNode(true)); });
    return new MwDomCollection(out);
};
MwDomCollection.prototype.ready = function (fn) {
    // jQuery `$(document).ready(fn)` → DOMContentLoaded.
    if (typeof fn !== 'function') return this;
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fn);
    } else {
        try { fn.call(document, mw.$); } catch (e) {}
    }
    return this;
};
MwDomCollection.prototype.next = function () {
    var out = [];
    this.each(function () { if (this.nextElementSibling) out.push(this.nextElementSibling); });
    return new MwDomCollection(out);
};
MwDomCollection.prototype.prev = function () {
    var out = [];
    this.each(function () { if (this.previousElementSibling) out.push(this.previousElementSibling); });
    return new MwDomCollection(out);
};
MwDomCollection.prototype.replaceWith = function (content) {
    return this.each(function () {
        var el = this;
        if (typeof content === 'string') {
            var tmp = document.createElement('div');
            tmp.innerHTML = content;
            var newNode = tmp.firstChild;
            if (newNode && el.parentNode) el.parentNode.replaceChild(newNode, el);
        } else if (content && content.nodeType && el.parentNode) {
            el.parentNode.replaceChild(content, el);
        }
    });
};
MwDomCollection.prototype.empty = function () {
    return this.each(function () { while (this.firstChild) this.removeChild(this.firstChild); });
};
MwDomCollection.prototype.serialize = function () {
    if (!this.length) return '';
    var form = this[0];
    if (!form || !(form instanceof HTMLFormElement)) return '';
    return new URLSearchParams(new FormData(form)).toString();
};
MwDomCollection.prototype.focus = function () {
    if (this.length && this[0].focus) this[0].focus();
    return this;
};
MwDomCollection.prototype.blur = function () {
    if (this.length && this[0].blur) this[0].blur();
    return this;
};
MwDomCollection.prototype.click = function (handler) {
    if (typeof handler === 'function') return this.on('click', handler);
    return this.each(function () { if (this.click) this.click(); });
};
MwDomCollection.prototype.toggle = function () {
    return this.each(function () {
        this.style.display = (this.style.display === 'none') ? '' : 'none';
    });
};
MwDomCollection.prototype.get = function (n) {
    if (n === undefined) return Array.prototype.slice.call(this);
    if (n < 0) n = this.length + n;
    return this[n];
};
MwDomCollection.prototype.index = function () {
    if (!this.length) return -1;
    var el = this[0];
    if (!el.parentNode) return -1;
    return Array.prototype.indexOf.call(el.parentNode.children, el);
};
// Make array-like with for...of + Array.from
MwDomCollection.prototype[Symbol.iterator] = function () {
    var i = -1, arr = this;
    return { next: function () { return ++i < arr.length ? { value: arr[i], done: false } : { value: undefined, done: true }; } };
};

mw.$ = function (selector, context) {
    // PASSTHROUGH to jQuery if it's REALLY loaded (not the
    // self-alias we install below as `window.jQuery = mw.$`).
    // The `!== mw.$` self-check prevents infinite recursion
    // when our own static-method block aliases jQuery to mw.$.
    if (typeof window.jQuery !== 'undefined' &&
        window.jQuery !== mw.$ &&
        typeof window.jQuery.fn !== 'undefined' &&
        typeof window.jQuery.fn.jquery !== 'undefined') {
        if (typeof selector === 'object' || (typeof selector === 'string' && selector.indexOf('<') !== -1)) {
            return window.jQuery(selector);
        }
        context = context || document;
        if (typeof document.querySelector !== 'undefined') {
            if (typeof selector === 'string') {
                try { return window.jQuery(context.querySelectorAll(selector)); }
                catch (e) { return window.jQuery(selector, context); }
            }
            return window.jQuery(selector, context);
        }
        return window.jQuery(selector, context);
    }

    // No jQuery — return the vanilla MwDomCollection wrapper.
    return new MwDomCollection(__mwDomToArray(selector, context));
};
mw.$.fn = MwDomCollection.prototype; // For plugins that extend `mw.$.fn.foo = ...`.
mw.MwDomCollection = MwDomCollection;

/*
 * AI-263 Phase B5 (cycle-185 2026-05-11) — global `$` alias +
 * static-method compatibility.
 *
 * Microweber's legacy core code (`core/_.js`, `core/options.js`,
 * `core/ajax.js`, etc.) uses bare `$()` / `$.each` / `$.ajax` /
 * `$.extend` throughout — historically OK because jQuery was
 * always loaded eagerly. To unblock dropping
 * `mw_require_jquery()` from the Bootstrap template, expose a
 * global `window.$` (and `window.jQuery`) that points at
 * `mw.$` so:
 *
 *   - `$('.foo')` → mw.$ wrapper (vanilla or jQuery passthrough)
 *   - `$.each(arr, fn)` → forEach
 *   - `$.extend({}, ...)` → Object.assign
 *   - `$.ajax({...})` → fetch-based shim
 *   - `$.isArray(x)` → Array.isArray
 *   - `$.isPlainObject(x)` → simple check
 *
 * Only installed when jQuery is NOT already loaded — admin /
 * legacy admin paths keep the real jQuery untouched.
 */
if (typeof window !== 'undefined' && typeof window.jQuery === 'undefined') {
    if (typeof window.$ === 'undefined') {
        window.$ = mw.$;
    }
    window.jQuery = window.jQuery || mw.$;
    var __jq = window.$;
    __jq.fn = MwDomCollection.prototype;
    __jq.each = function (arr, fn) {
        if (!arr) return arr;
        if (typeof arr.length === 'number') {
            for (var i = 0; i < arr.length; i++) {
                if (fn.call(arr[i], i, arr[i]) === false) break;
            }
        } else {
            for (var k in arr) if (Object.prototype.hasOwnProperty.call(arr, k)) {
                if (fn.call(arr[k], k, arr[k]) === false) break;
            }
        }
        return arr;
    };
    __jq.extend = function () {
        var args = Array.prototype.slice.call(arguments);
        var deep = false;
        if (args[0] === true) { deep = true; args.shift(); }
        var target = args.shift() || {};
        for (var i = 0; i < args.length; i++) {
            var src = args[i];
            if (src == null) continue;
            for (var k in src) {
                if (!Object.prototype.hasOwnProperty.call(src, k)) continue;
                if (deep && typeof src[k] === 'object' && src[k] !== null && !Array.isArray(src[k])) {
                    target[k] = __jq.extend(true, target[k] || {}, src[k]);
                } else {
                    target[k] = src[k];
                }
            }
        }
        return target;
    };
    __jq.isArray = Array.isArray;
    __jq.isPlainObject = function (o) {
        return o !== null && typeof o === 'object' && Object.getPrototypeOf(o) === Object.prototype;
    };
    __jq.isFunction = function (o) { return typeof o === 'function'; };
    __jq.trim = function (s) { return String(s == null ? '' : s).replace(/^\s+|\s+$/g, ''); };
    __jq.makeArray = function (a) { return Array.prototype.slice.call(a); };
    __jq.inArray = function (v, a) { return a && a.indexOf ? a.indexOf(v) : -1; };
    __jq.parseJSON = function (s) { try { return JSON.parse(s); } catch (e) { return null; } };
    __jq.noop = function () {};

    // Promise/Deferred — minimal $.Deferred() with .done/.fail/.always/.resolve/.reject.
    __jq.Deferred = function () {
        var doneCb = [], failCb = [], alwaysCb = [], state = 'pending', value;
        var d = {
            state: function () { return state; },
            done: function (cb) { if (state === 'resolved') cb.call(null, value); else doneCb.push(cb); return d; },
            fail: function (cb) { if (state === 'rejected') cb.call(null, value); else failCb.push(cb); return d; },
            always: function (cb) { if (state !== 'pending') cb.call(null, value); else alwaysCb.push(cb); return d; },
            then: function (onDone, onFail) { if (onDone) d.done(onDone); if (onFail) d.fail(onFail); return d; },
            resolve: function (v) { if (state !== 'pending') return d; state = 'resolved'; value = v;
                doneCb.forEach(function (c) { try { c.call(null, v); } catch (e) {} });
                alwaysCb.forEach(function (c) { try { c.call(null, v); } catch (e) {} });
                return d;
            },
            reject: function (v) { if (state !== 'pending') return d; state = 'rejected'; value = v;
                failCb.forEach(function (c) { try { c.call(null, v); } catch (e) {} });
                alwaysCb.forEach(function (c) { try { c.call(null, v); } catch (e) {} });
                return d;
            },
            promise: function () { return d; },
        };
        return d;
    };

    // $.ajax compatibility shim — translates jQuery options to fetch.
    __jq.ajax = function (urlOrOptions, maybeOptions) {
        var opts;
        if (typeof urlOrOptions === 'string') {
            opts = maybeOptions || {};
            opts.url = urlOrOptions;
        } else {
            opts = urlOrOptions || {};
        }
        var method = (opts.method || opts.type || 'GET').toUpperCase();
        var body;
        var headers = Object.assign({}, opts.headers || {});
        var contentType = opts.contentType;
        if (contentType !== false && !headers['Content-Type'] && method !== 'GET') {
            headers['Content-Type'] = contentType || 'application/x-www-form-urlencoded; charset=UTF-8';
        }
        if (opts.data !== undefined && method !== 'GET') {
            if (typeof opts.data === 'string') body = opts.data;
            else if (opts.data instanceof FormData) {
                body = opts.data;
                delete headers['Content-Type']; // browser sets boundary
            }
            else if (headers['Content-Type'] && headers['Content-Type'].indexOf('json') !== -1) {
                body = JSON.stringify(opts.data);
            }
            else {
                body = Object.keys(opts.data).map(function (k) {
                    return encodeURIComponent(k) + '=' + encodeURIComponent(opts.data[k]);
                }).join('&');
            }
        }
        var url = opts.url;
        if (method === 'GET' && opts.data) {
            var qs = (typeof opts.data === 'string') ? opts.data :
                Object.keys(opts.data).map(function (k) { return encodeURIComponent(k) + '=' + encodeURIComponent(opts.data[k]); }).join('&');
            if (qs) url += (url.indexOf('?') === -1 ? '?' : '&') + qs;
        }
        var fetchOpts = { method: method, headers: headers, credentials: 'same-origin' };
        if (body !== undefined) fetchOpts.body = body;

        var d = __jq.Deferred();
        var xhr = { readyState: 0, status: 0, responseText: '' };
        fetch(url, fetchOpts).then(function (resp) {
            xhr.status = resp.status;
            xhr.readyState = 4;
            var ct = resp.headers.get('content-type') || '';
            var asJson = opts.dataType === 'json' || ct.indexOf('json') !== -1;
            return resp.text().then(function (txt) {
                xhr.responseText = txt;
                var data = asJson ? (function () { try { return JSON.parse(txt); } catch (e) { return txt; } })() : txt;
                if (resp.ok) {
                    if (typeof opts.success === 'function') {
                        try { opts.success.call(opts.context || null, data, resp.statusText, xhr); } catch (e) {}
                    }
                    d.resolve(data);
                } else {
                    if (typeof opts.error === 'function') {
                        try { opts.error.call(opts.context || null, xhr, resp.statusText, ''); } catch (e) {}
                    }
                    d.reject(xhr);
                }
                if (typeof opts.complete === 'function') {
                    try { opts.complete.call(opts.context || null, xhr, resp.statusText); } catch (e) {}
                }
            });
        }).catch(function (e) {
            if (typeof opts.error === 'function') {
                try { opts.error.call(opts.context || null, xhr, 'error', e.message); } catch (_) {}
            }
            d.reject(xhr);
            if (typeof opts.complete === 'function') {
                try { opts.complete.call(opts.context || null, xhr, 'error'); } catch (_) {}
            }
        });
        return d;
    };
    __jq.get = function (url, data, success) { return __jq.ajax({ url: url, data: data, success: success, method: 'GET' }); };
    __jq.post = function (url, data, success) { return __jq.ajax({ url: url, data: data, success: success, method: 'POST' }); };
    __jq.getScript = function (url, success) {
        var s = document.createElement('script');
        s.src = url;
        s.onload = function () { if (typeof success === 'function') try { success(); } catch (e) {} };
        document.head.appendChild(s);
        var d = __jq.Deferred();
        s.addEventListener('load', function () { d.resolve(); });
        s.addEventListener('error', function () { d.reject(); });
        return d;
    };
    __jq.ajaxSetup = function () { /* no-op — CSRF interceptor handles this globally */ };

    // Hash util used by jQuery $.fn.attr internals in some legacy code.
    __jq.support = { /* unused but referenced by some plugins */ };
}


mw.parent = function(){
    if(window === top){
        return window.mw;
    }
    if(mw.tools.canAccessWindow(parent) && parent.mw){
        return parent.mw;
    }
    return window.mw;
};

mw.top = function() {
    if(!!mw.__top){
        return mw.__top;
    }
    var getLastParent = function() {
        var result = window;
        var curr = window;
        while (curr && mw.tools.canAccessWindow(curr) && (curr.mw || curr.parent.mw)){
            result = curr;
            curr = curr.parent;
        }
        mw.__top = curr.mw;
        return result.mw;
    };
    if(window === top){
        mw.__top = window.mw;
        return window.mw;
    } else {
        if(mw.tools.canAccessWindow(top) && top.mw){
            mw.__top = top.mw;
            return top.mw;
        } else{
            if(window.top !== window.parent){
                return getLastParent();
            }
            else{
                mw.__top = window.mw;
                return window.mw;
            }
        }
    }
};

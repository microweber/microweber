/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./userfiles/modules/microweber/api/classes/state.js":
/*!***********************************************************!*\
  !*** ./userfiles/modules/microweber/api/classes/state.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "State": () => (/* binding */ State)
/* harmony export */ });
const State = function(options){

    var scope = this;
    var defaults = {
        maxItems: 1000
    };
    this.options = $.extend({}, defaults, (options || {}));
    this._state = this.options.state || [];
    this._active = null;
    this._activeIndex = -1;

    this.hasNext = false;
    this.hasPrev = false;

    this.state = function(state){
        if(!state){
            return this._state;
        }
        this._state = state;
        return this;
    };
    var _e = {};
    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };


    this.active = function(active){
        if(!active){
            return this._active;
        }
    };

    this.activeIndex = function(activeIndex){
        if(!activeIndex){
            return this._activeIndex;
        }
    };

    this._timeout = null;
    this.timeoutRecord = function(item){
        clearTimeout(this._timeout);
        this._timeout = setTimeout(function(scope, item){
            scope.record(item);
        }, 333, this, item);
    };

    var recentRecordIsEqual = function (item) {
        const curr = scope._state[0];
        if(!curr) return false;
        for (var n in item) {
            if(curr[n] !== item[n]) {
                return false;
            }
        }
        return true;
    };

    this.record = function(item){
        if(this._activeIndex>-1) {
            var i = 0;
            while ( i <  this._activeIndex) {
                this._state.shift();
                i++;
            }
        }
        if (recentRecordIsEqual(item)) {
            return;
        }
        this._state.unshift(item);
        if(this._state.length >= this.options.maxItems) {
            this._state.splice(-1,1);
        }
        this._active = null;
        this._activeIndex = -1;
        this.afterChange(false);
        $(this).trigger('stateRecord', [this.eventData()]);
        this.dispatch('record', [this.eventData()]);
        return this;
    };

    this.actionRecord = function(recordGenFunc, action){
        this.record(recordGenFunc());
        action.call();
        this.record(recordGenFunc());
    };

    this.redo = function(){
        this._activeIndex--;
        this._active = this._state[this._activeIndex];
        this.afterChange('stateRedo');
        this.dispatch('redo');
        return this;
    };

    this.undo = function(){
        if(this._activeIndex === -1) {
            this._activeIndex = 1;
        }
        else{
            this._activeIndex++;
        }
        this._active = this._state[this._activeIndex];
        this.afterChange('stateUndo');
        this.dispatch('undo');
        return this;
    };

    this.hasRecords = function(){
        return !!this._state.length;
    };

    this.eventData = function(){
        return {
            hasPrev: this.hasPrev,
            hasNext: this.hasNext,
            active: this.active(),
            activeIndex: this.activeIndex()
        };
    };
    this.afterChange = function(action){
        this.hasNext = true;
        this.hasPrev = true;

        if(action) {
            if(this._activeIndex >= this._state.length) {
                this._activeIndex = this._state.length - 1;
                this._active = this._state[this._activeIndex];
            }
        }

        if(this._activeIndex <= 0) {
            this.hasPrev = false;
        }
        if(this._activeIndex === this._state.length-1 || (this._state.length === 1 && this._state[0].$initial)) {
            this.hasNext = false;
        }

        if(action){

            mw.$(this).trigger(action, [this.eventData()]);
        }
        if(action !== false){
            mw.$(this).trigger('change', [this.eventData()]);
        }
        return this;
    };

    this.reset = function(){
        this._state = this.options.state || [];
        this.afterChange('reset');
        return this;
    };

    this.clear = function(){
        this._state = [];
        this.afterChange('clear');
        return this;
    };


};


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/@core.js":
/*!********************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/@core.js ***!
  \********************************************************/
/***/ (() => {



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

window.mwhead = document.head || document.getElementsByTagName('head')[0];

mw.doc = document;
mw.win = window;
mw.head = mwhead;

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

mw.log = function(what) {
    if (window.console && mw.settings.debug) {
        top.console.log(what);
    }
};


mw.$ = function(selector, context) {
    if(typeof selector === 'object' || (typeof selector === 'string' && selector.indexOf('<') !== -1)){ return jQuery(selector); }
    context = context || mwd;
    if (typeof document.querySelector !== 'undefined') {
        if (typeof selector === 'string') {
            try {
                return jQuery(context.querySelectorAll(selector));
            } catch (e) {
                return jQuery(selector, context);
            }
        } else {
            return jQuery(selector, context);
        }
    } else {
        return jQuery(selector, context);
    }
};


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


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/ajax.js":
/*!*******************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/ajax.js ***!
  \*******************************************************/
/***/ (() => {

var _jqxhr = jQuery.ajax;
mw.jqxhr = _jqxhr;



jQuery.ajax = function(url, options){
    options = options || {};
    var settings = {};
    if(typeof url === 'object'){
        $.extend(settings, url);
    }
    else{
        settings.url = url;
    }
    if(typeof settings.success === 'function'){
        settings._success = settings.success;
        delete settings.success;
        settings.success = function (data, status, xhr) {
            if(xhr.status === 200) {
                if (data && (data.form_data_required || data.form_data_module)) {
                    mw.extradataForm(settings, data);
                }
                else {
                    if (typeof this._success === 'function') {
                        var scope = this;
                        scope._success.call(scope, data, status, xhr);

                    }
                }
            }
        };
    }
    settings = $.extend({}, settings, options);
    return _jqxhr(settings);
};

$.ajaxSetup({
    cache: false,
    error: function (xhr, e) {
        if(xhr.status === 422){
            mw.errorsHandle(xhr.responseJSON)
        } else if(xhr.status !== 200 && xhr.status !== 0){
            mw.notification.error('Error ' + xhr.status + ' - ' + xhr.statusText + ' - \r\n' + xhr.responseText );
            setTimeout(function(){
                mw.tools.loading(false);
            }, 333);
        }
    }
});


jQuery.cachedScript = function( url, options ) {
    options = $.extend( options || {}, {
        dataType: "script",
        cache: true,
        url: url
    });
    return jQuery.ajax( options );
};


mw.getScripts = function (array, callback) {
    if(typeof array === 'string'){
        array = array.split(',')
    }
    array = array.filter(function (item) {
        return !!item.trim();
    });
    var all = array.length, ready = 0;
    $.each(array, function(){
        var scr = $('<script>');
        $(scr).on('load', function(){
            ready++;
            if(all === ready) {
                callback.call()
            }
        });
        scr[0].src = this.indexOf('//') !== -1 ? this : mw.settings.includes_url + 'api/' + this;
        document.body.appendChild(scr[0]);
    });
};

mw.moduleCSS = mw.module_css = function(url){
    if (!~mw.required.indexOf(url)) {
        mw.required.push(url);
        var el = document.createElement('link');
        el.rel='stylesheet';
        el.type='text/css';
        el.href = url;
        mw.head.insertBefore(el, mwhead.firstChild);
    }
};
mw.moduleJS = mw.module_js = function(url){
    mw.require(url, true);
};


// Modules

mw.load_module = function(name, selector, callback, attributes) {
    attributes = attributes || {};
    attributes.module = name;
    return mw._({
        selector: selector,
        params: attributes,
        done: function() {
            mw.settings.sortables_created = false;
            if (typeof callback === 'function') {
                callback.call(mw.$(selector)[0]);
            }
        }
    });
}

mw.loadModuleData = function(name, update_element, callback, attributes){


    attributes = attributes || {};

    if(typeof update_element == 'function'){
        callback = update_element;
    }
    update_element = document.createElement('div');
    attributes.module = name;
    mw._({
        selector: update_element,
        params: attributes
    }, false, true)
        .done(function(data){

            setTimeout(function(){
                callback.call(this, data);
                $(document).off('focusin.modal');
            }, 50)

        });
}
mw.getModule = function(name, params, callback){
    if( typeof params == 'function'){
        callback = params;
    }
    params = params || {};
    var update_element = document.createElement('div');
    for(var x in params){
        update_element.setAttribute(x, params[x]);
    }
    mw.loadModuleData(name, update_element, function(a){
        callback.call(a);
    });
}

mw.reload_module_intervals = {};
mw.reload_module_interval = function(module_name, interval) {
    interval =  interval || 1000;
    var obj = {pause:false};
    if(!!mw.reload_module_intervals[module_name]){
        clearInterval(mw.reload_module_intervals[module_name]);
    }
    mw.reload_module_intervals[module_name] = setInterval(function(){
        if(!obj.pause){
            obj.pause = true;
            mw.reload_module(module_name, function(){
                obj.pause = false;
            });
        }
    }, interval);
    return mw.reload_module_intervals['module_name'];
}

mw.reload_module_parent = function(module, callback) {
    if(self !== parent && !!parent.mw){

        parent.mw.reload_module(module, callback)
        if(typeof(top.mweditor) != 'undefined'  && typeof(top.mweditor) == 'object'   && typeof(top.mweditor.contentWindow) != 'undefined'){
            top.mweditor.contentWindow.mw.reload_module(module, callback)
        } else if(typeof(mw.top().win.iframe_editor_window) != 'undefined'  && typeof(mw.top().win.iframe_editor_window) == 'object'   && typeof(mw.top().win.iframe_editor_window.mw) != 'undefined'){

            mw.top().win.iframe_editor_window.mw.reload_module(module, callback)
        }

        if(typeof(parent.mw_preview_frame_object) != 'undefined'  && typeof(parent.mw_preview_frame_object) == 'object'   && typeof(parent.mw_preview_frame_object.contentWindow) != 'undefined'){
            if(parent.mw_preview_frame_object.contentWindow != null && typeof(parent.mw_preview_frame_object.contentWindow.mw) != 'undefined'){
                parent.mw_preview_frame_object.contentWindow.mw.reload_module(module, callback)
            }
        }
    } else {
        if(typeof(mweditor) != 'undefined'  && typeof(mweditor) == 'object'   && typeof(mweditor.contentWindow) != 'undefined' && typeof(mweditor.contentWindow.mw) != 'undefined'){
            mweditor.contentWindow.mw.reload_module(module, callback)
        }

    }
}
mw.reload_modules = function(array, callback, simultaneously) {
    if(array.array && !array.slice){
        callback = array.callback || array.done || array.ready;
        simultaneously = array.simultaneously;
        array = array.array;
    }
    simultaneously = simultaneously || false;
    if(simultaneously){
        var l = array.length, ready = 0, i = 0;
        for( ; i<l; i++){
            mw.reload_module(array[i], function(){
                ready++;
                if(ready === l && callback){
                    callback.call();
                }
            });
        }
    }
    else{
        if(array.length === 0){
            if(callback){
                callback.call()
            }
        }
        else{
            var m = array[0];
            array.shift();
            mw.reload_module(m, function(){
                mw.reload_modules(array, callback, false);
            });
        }
    }
};
mw.reload_module_everywhere = function(module, eachCallback) {
    mw.tools.eachWindow(function () {
        if(this.mw && this.mw.reload_module){
            this.mw.reload_module(module, function(){
                if(typeof eachCallback === 'function'){
                    eachCallback.call(this);
                }
            })
        }
    })
};

mw.reload_module = function(module, callback) {
    if(module.constructor === [].constructor){
        var l = module.length, i=0, w = 1;
        for( ; i<l; i++){
            mw.reload_module(module[i], function(){
                w++;
                if(w === l && typeof callback === 'function'){
                    callback.call();
                }
                $( this ).trigger('ModuleReload')
            });
        }
        return false;
    }
    var done = callback || function(){};
    if (typeof module !== 'undefined') {
        if (typeof module === 'object') {

            mw._({
                selector: module,
                done:done
            });
        } else {
            var module_name = module.toString();
            var refresh_modules_explode = module_name.split(",");
            for (var i = 0; i < refresh_modules_explode.length; i++) {
                var module = refresh_modules_explode[i];
                if (typeof module != 'undefined') {
                    module = module.replace(/##/g, '#');
                    var m = mw.$(".module[data-type='" + module + "']");
                    if (m.length === 0) {
                        try {   m = $(module); }  catch(e) {};
                    }

                    (function(callback){
                        var count = 0;
                        for (var i=0;i<m.length;i++){
                            mw.reload_module(m[i], function(){
                                count++;
                                if(count === m.length && typeof callback === 'function'){
                                    callback.call();
                                }
                                $( document ).trigger('ModuleReload')
                            })
                        }
                    })(callback)



                }
            }
        }
    }
}

mw.clear_cache = function() {
    $.ajax({
        url: mw.settings.site_url+'api/clearcache',
        type: "POST",
        success: function(data){
            if(mw.notification != undefined){
                mw.notification.msg(data);

            }
        }
    });
}
mw.temp_reload_module_queue_holder = [];




mw["_"] = function(obj, sendSpecific, DONOTREPLACE) {
    if(mw.on){
        mw.on.DOMChangePause = true;
    }
    var url = typeof obj.url !== 'undefined' ? obj.url : mw.settings.site_url+'module/';
    var selector = typeof obj.selector !== 'undefined' ? obj.selector : '';
    var params =  typeof obj.params !== 'undefined' ? obj.params : {};
    var to_send = params;
    if(typeof $(obj.selector)[0] === 'undefined') {
        mw.pauseSave = false;
        mw.on.DOMChangePause = false;
        return false;
    }
    if(mw.session){
        mw.session.checkPause = true;
    }
    var $node = $(obj.selector);
    var node = $node[0];
    var attrs = node.attributes;



    // wait between many reloads
    if (node.id) {
        if ( mw.temp_reload_module_queue_holder.indexOf(node.id) === -1){
            mw.temp_reload_module_queue_holder.push(node.id);
            setTimeout(function() {
                var reload_index = mw.temp_reload_module_queue_holder.indexOf(node.id);
                delete mw.temp_reload_module_queue_holder[reload_index];
            }, 300);
        } else {
            return;
        }
    }

    if (sendSpecific) {
        attrs["class"] !== undefined ? to_send["class"] = attrs["class"].nodeValue : "";
        attrs["data-module-name"] !== undefined ? to_send["data-module-name"] = attrs["data-module-name"].nodeValue : "";
        attrs["data-type"] !== undefined ? to_send["data-type"] = attrs["data-type"].nodeValue : "";
        attrs["type"] !== undefined ? to_send["type"] = attrs["type"].nodeValue : "";
        attrs["template"] !== undefined ? to_send["template"] = attrs["template"].nodeValue : "";
        attrs["ondrop"] !== undefined ? to_send["ondrop"] = attrs["ondrop"].nodeValue : "";
    }
    else {
        for (var i in attrs) {
            if(attrs[i] !== undefined){
                var name = attrs[i].name;
                var val = attrs[i].nodeValue;
                if(typeof to_send[name] === 'undefined'){
                    to_send[name]  = val;
                }
            }
        }
    }
    var b = true;
    for (var a in to_send) {
        if(to_send.hasOwnProperty(a)) { b = false; }
    }
    if(b){
        mw.tools.removeClass(document.body, 'loading');
        mw.pauseSave = false;
        mw.on.DOMChangePause = false;
        return false;
    }
    var storedValues = $node.dataset('storeValues') === 'true' ? {} : false;
    if(storedValues) {
        $node.find('[name]').each(function () {
            storedValues[this.name] = $(this).val();
        })
    }

    var xhr = $.post(url, to_send, function(data) {

        if(!!mw.session){
            mw.session.checkPause = false;
        }
        if(DONOTREPLACE){

            mw.tools.removeClass(document.body, 'loading');
            mw.pauseSave = false;
            mw.on.DOMChangePause = false;
            return false;
        }

        var docdata = mw.tools.parseHtml(data);

        if(storedValues) {
            mw.$('[name]', docdata).each(function(){
                var el = $(this);
                if(!el.val()) {
                    el.val(storedValues[this.name] || undefined);
                    this.setAttribute("value", storedValues[this.name] || '');
                }
            })
        }

        var hasDone = typeof obj.done === 'function';
        var id;
        if (typeof to_send.id  !== 'undefined') {
            id = to_send.id;
        } else{
            id = docdata.body.querySelector(['id']);
        }
        mw.$(selector).replaceWith($(docdata.body).html());
        var count = 0;
        if(hasDone){
            setTimeout(function(){
                count++;
                obj.done.call($(selector)[0], data);
                mw.trigger('moduleLoaded');
            }, 33);
        }

        if(!id){
            mw.pauseSave = false;
            mw.on.DOMChangePause = false;
            return false;
        }


        typeof mw.drag !== 'undefined' ? mw.drag.fix_placeholders(true) : '';
        var m = document.getElementById(id);
        // typeof obj.done === 'function' ? obj.done.call(selector, m) : '';

        if(mw.wysiwyg){
            $(m).hasClass("module") ? mw.wysiwyg.init_editables(m) : '' ;
        }


        if(mw.on && !hasDone){
            mw.on.moduleReload(id, "", true);
            mw.trigger('moduleLoaded');
        }
        if($.fn.selectpicker) {
            $('.selectpicker').selectpicker();
        }
        if (mw.on) {
            mw.on.DOMChangePause = false;
        }
        mw.tools.removeClass(document.body, 'loading');


    })
        .fail(function(){
            mw.pauseSave = false;
            typeof obj.fail === 'function' ? obj.fail.call(selector) : '';
        })
        .always(function(){
            mw.pauseSave = false;
        });
    return xhr;
};


mw.get = function(action, params, callback){
    var obj;
    var url = mw.settings.api_url + action;
    var type = typeof params;
    if(type === 'string'){
        obj = mw.serializeFields(params);
    }
    else if(type.constructor === {}.constructor ){
        obj = params;
    }
    else{
        obj = {};
    }
    $.post(url, obj)
        .success(function(data) { return typeof callback === 'function' ? callback.call(data) : data;   })
        .error(function(data) { return typeof callback === 'function' ? callback.call(data) : data;  });
}

get_content = function(params, callback){
    var obj = mw.url.getUrlParams("?"+params);
    if(typeof callback!='function'){
        mw.get('get_content_admin', obj);
    }
    else{
        mw.get('get_content_admin', obj, function(){callback.call(this)});
    }
}


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/common.js":
/*!*********************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/common.js ***!
  \*********************************************************/
/***/ (() => {

$(document).ready(function(){
    mw.$('.mw-lazy-load-module').reload_module();
});


$(document).ready(function(){

    mw.common['data-mw-close']();
    mw.$(document.body)
    .on('click', '[data-mw-dialog]', function(e){
        mw.common['data-mw-dialog'](e);
    })
    .on('click', '[data-mw-close]', function(e){
        mw.common['data-mw-close'](e);
    });
});

mw.common = {
    setOptions:function (el, options) {
        options = options || {};
        if(el.target){
            el = el.target;
        }
        var settings = el.getAttribute('data-mw-settings');
        try{
            settings = JSON.parse(settings);
        }
        catch(e){
            settings = {};
        }
        return $.extend(options, settings)

    },
    'data-mw-close':function(e){
        var cookie;
        if(e && e.target){
            var data = e.target.getAttribute('data-mw-close');
            cookie = JSON.parse(mw.cookie.get('data-mw-close') || '{}');
            mw.$(data).slideUp(function(){
                mw.$(this).remove();
                cookie[data] = true;
                mw.cookie.set('data-mw-close', JSON.stringify(cookie));
            });
        }
        else{
            cookie =  JSON.parse(mw.cookie.get('data-mw-close') || '{}');
            mw.$('[data-mw-close]').each(function(){
                var data = this.getAttribute('data-mw-dialog');
                if(cookie[data]){
                    mw.$(data).remove();
                }
            });
        }
    },
    'data-mw-dialog':function(e){
        var skin = 'basic';
        var overlay = true;
        var data = e.target.getAttribute('data-mw-dialog');
        if(data){
            e.preventDefault();
            data = data.trim();
            var arr = data.split('.');
            var ext = arr[arr.length-1];
            if(data.indexOf('http') === 0){
                if(ext && /(gif|png|jpg|jpeg|bpm|tiff)$/i.test(ext)){
                    mw.image.preload(data, function(w,h){
                        var html = "<img src='"+data+"'>";
                        var conf = mw.common.setOptions(e, {
                            width:w,
                            height:h,
                            content:html,
                            template:skin,
                            overlay:overlay,
                            overlayRemovesModal:true
                        })
                        mw.dialog(conf);
                    });
                }
                else{
                    var conf = mw.common.setOptions(e, {
                        url:data,
                        width:'90%',
                        height:'auto%',
                        template:skin,
                        overlay:overlay,
                        overlayRemovesModal:true
                    })
                    mw.dialogIframe(conf)
                }
            }
            else if(data.indexOf('#') === 0 || data.indexOf('.') === 0){
                var conf = mw.common.setOptions(e, {
                    content:$(data)[0].outerHTML,
                    template:skin,
                    overlay:overlay,
                    overlayRemovesModal:true
                });
                mw.dialog(conf);
            }
        }
    }
}


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/components.js":
/*!*************************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/components.js ***!
  \*************************************************************/
/***/ (() => {

    mw.components = {
    _rangeOnce: false,
    'range': function(el){
        mw.lib.require('jqueryui');
        var options = this._options(el);
        var defaults = {
            range: 'min',
            animate: "fast"
        };
        var ex = {}, render = el;
        if(el.nodeName === 'INPUT'){
            el._pauseChange = false;
            el.type = 'text';
            render = document.createElement('div');
            $(el).removeClass('mw-range');
            $(render).addClass('mw-range');
            $(el).after(render);
            ex = {
                slide: function( event, ui ) {
                    el._pauseChange = true;
                   $(el).val(ui.value).trigger('change').trigger('input');
                    setTimeout(function () {
                        el._pauseChange = false;
                    }, 78);
                }
            };

        }
        var settings = $.extend({}, defaults, options, ex);
        if(el.min){
            settings.min = parseFloat(el.min);
        }
        if(el.max){
            settings.max = parseFloat(el.max);
        }
        if(el.value){
            settings.value = parseFloat(el.value);
        }
        mw.$(render)
            .slider(settings)
            .on('mousedown touchstart', function(){
                mw.$(this).addClass('active');
            });
        $(el).on('input', function(){
            mw.$(render).slider( "value", this.value );
        });
        if(!mw.components._rangeOnce) {
            mw.components._rangeOnce = true;
            mw.$(document.body).on('mouseup touchend', function(){
                mw.$('.mw-range.active').removeClass('active');
            });
        }
    },
    'color-picker': function(el){
        var options = this._options(el);
        var defaults = {
            position: 'bottom-center'
        };
        var settings = $.extend({}, defaults, options);
        var nav = document.createElement('div');
        nav.className = 'mw-ui-btn-nav mw-color-picker-holder';
        var view = document.createElement('div');
        view.className = 'mw-ui-btn';
        view.innerHTML = '<span class="mw-ui-btn-img"></span>';
        nav.appendChild(view);
        var inputEl;
        if(el.nodeName === 'INPUT'){
            inputEl = el;
            mw.$(el).addClass('mw-ui-field').after(nav);
            nav.appendChild(el);
            mw.$('.mw-ui-btn-img', view).css("background-color", el.value);
        }

        inputEl._time = null;
        var picker = mw.colorPicker({
            element:inputEl,
            position:settings.position,
            onchange:function(color){
                mw.$('.mw-ui-btn-img', view).css("background-color", color);
                mw.$(inputEl).trigger('change');
            }
        });
        mw.$(view).on("click", function(){
            setTimeout(function(){
                picker.toggle();
            }, 10);
        });
    },
    'file-uploader':function(el){
        var options = this._options(el);
        var defaults = {
            element: el
        };
        var settings = $.extend({}, defaults, options);
        var ch = mw.$(el).attr("onchange");

        mw.fileWindow({
            types:'media',
            change:function(url){
                try{
                    eval(ch);
                }
                catch(err){}
            }
        });
    },
    'modules-tabs':function(el){
        var options = this._options(el);
        options.breakPoint = 100; //makes accordion if less then 100px
        if(window.live_edit_sidebar) {
            mw.$(el).addClass('mw-accordion-window-height')
            options.breakPoint = 800; //makes accordion if less then 800px
        }
        var accordion = this.accordion(el);
        var tb = new mw.tabAccordion(options, accordion);
    },
    'tab-accordion':function(el){
       var options = this._options(el);
       var accordion = this.accordion(el);
       var tb = new mw.tabAccordion(options, accordion);
    },
    accordion:function(el){
        if(!el || el._accordion) return;
        if(!$(el).is(':visible')){
            setTimeout(function(){
                mw.components.accordion(el);
            }, 777, el);
            return;
        }
        el._accordion = true;
        var options = this._options(el);
        var settings = $.extend(options, {element:el})
        var accordion = new mw.uiAccordion(settings);
        if($(el).hasClass('mw-accordion-window-height')){
            accordion._setHeight = function(){
                var max =  mw.$(window).height() - (accordion.root.offset().top - mw.$(window).scrollTop());
                accordion.root.css('height', max);
                var content_max = max - (accordion.titles.length * accordion.titles.eq(0).outerHeight());
                accordion.contents.css('height', content_max);
            };
            accordion._setHeight();
            mw.$(window).on('load resize', function(){
                accordion._setHeight();
            });
            if(window !== top){
                mw.$(top).on('load resize', function(){
                    accordion._setHeight();
                });
            }
        }
        if($(el).hasClass('mw-accordion-full-height')){
            accordion._setHeight = function(){
                var max = Math.min($(el).parent().height(), mw.$(window).height());
                accordion.root.css('maxHeight', max);
                var content_max = max - (accordion.titles.length * accordion.titles.eq(0).outerHeight());
                accordion.contents.css('maxHeight', content_max);
            };
            accordion._setHeight();
            mw.$(window).on('load resize', function(){
                accordion._setHeight();
            });
            if(window !== top){
                mw.$(top).on('load resize', function(){
                    accordion._setHeight();
                });
            }
        }
        return accordion;
    },
    postSearch: function (el) {
        var defaults = {keyword: el.value, limit: 4};
        el._setValue = function (id) {
            mw.tools.ajaxSearch(this._settings, function () {

            });
        };

        el = mw.$(el);
        var options = JSON.parse(el.attr("data-options") || '{}');
        settings = $.extend({}, defaults, options);
        el[0]._settings = settings;

        el.wrap("<div class='mw-component-post-search'></div>");
        el.after("<ul></ul>");

        el.on("input focus blur", function (event) {

            if (!el[0].is_searching) {
                var val = el.val();
                if (event.type == 'blur') {
                    mw.$(this).next('ul').hide();
                    return false;
                }
                if (event.type == 'focus') {
                    if ($(this).next('ul').html()) {
                        mw.$(this).next('ul').show()
                    }
                    return false;
                }
                el[0].is_searching = true;

                this._settings.keyword = this.value;
                mw.$('ul', el).empty("")
                el.parent().addClass("loading");
                mw.tools.ajaxSearch(this._settings, function () {
                    var lis = [];
                    var json = this;
                    for (var item in json) {
                        var obj = json[item];
                        if (typeof obj === 'object') {
                            var li = document.createElement("li");
                            li._value = obj;
                            li.innerHTML = obj.title;
                            mw.$(li).on("mousedown touchstart", function () {
                                el.val(this._value.title);

                                el[0]._value = this._value;

                                el.trigger('postSelected', [this._value]);
                                mw.$(this.parentNode).hide()
                            })

                            lis.push(li);
                        }
                    }
                    el.parent().removeClass("loading");
                    var ul = el.parent().find("ul");
                    ul.empty().append(lis).show();
                    el[0].is_searching = false;
                });
            }
        });
        el.trigger("postSearchReady");
    },
    _options: function (el) {
        return mw.tools.elementOptions(el);
    },
    _init: function () {
        mw.$('.mw-field input[type="range"]').addClass('mw-range');
        mw.$('[data-mwcomponent], [data-mw-component]').each(function () {
            var component = this.dataset.mwComponent || this.dataset.mwcomponent;
            if (mw.components[component]) {
                mw.components[component](this);
                mw.$(this).removeAttr('data-mwcomponent').removeAttr('data-mw-component')
            }
        });
        $.each(this, function(key){
            if(key.indexOf('_') === -1){
                mw.$('.mw-'+key+', mw-'+key).not(".mw-component-ready").each(function () {
                    mw.$(this).addClass('mw-component-ready');
                    mw.components[key](this);
                });
            }
        });
    }
};

$(document).ready(function () {
    mw.components._init();
});

$(window).on('load', function () {
    mw.components._init();
});

    mw.on('ComponentsLaunch', function () {
        mw.components._init();
    });

    mw.on('mwDialogShow', function () {
        setTimeout(function () {
            mw.components._init();
        }, 110);
    });

$(window).on('ajaxStop', function () {
    setTimeout(function () {
        mw.components._init();
    }, 100);
});

mw.registerComponent = function(name, func){
    if(mw.components[name]){
        console.warn('Component ' + name + ' already exists.');
        return;
    }
    mw.components[name] = func;
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/custom_fields.js":
/*!****************************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/custom_fields.js ***!
  \****************************************************************/
/***/ (() => {

mw.custom_fields = {
    settings: {
        id: 0
    },
    saveurl: mw.settings.api_url + 'fields/save',
    create: function (obj, callback, error) {
        obj = $.extend({}, this.settings, obj);
        obj.id = 0;
        this.edit(obj, callback, error);
    },
    edit: function (obj, callback, error) {
        obj = $.extend({}, this.settings, obj);

        $.post(mw.custom_fields.saveurl, obj, function (data) {
            if (typeof callback === 'function') {
                if (!!data.error) {
                    if (typeof error === 'function') {
                        error.call(data.error);
                    }
                }
                else {
                    callback.call(data);
                }
            } else {

                mw.custom_fields.after_save();
            }
        })
            .fail(function (jqXHR, textStatus, errorThrown) {
                if (typeof error === 'function') {
                    error.call(textStatus);
                }
            });
    },
    sort: function (group) {
        group = document.getElementById(group);
        if (group == null) {
            return;
        }
        if (group.querySelectorAll('.mw-custom-field-form-controls').length > 0) {
            mw.$(group).sortable({
                handle: '.custom-fields-handle-field',
                placeholder: 'custom-fields-placeholder',
                axis: 'y',
                items: ".mw-custom-field-form-controls",
                start: function (a, ui) {
                    mw.$(ui.placeholder).height($(ui.item).outerHeight())
                },
                update: function () {
                    var par = mw.tools.firstParentWithClass(group, 'mw-admin-custom-field-edit-item-wrapper');
                    if (!!par) {
                        // mw.custom_fields.save(par);
                        mw.$(".custom-fields-settings-save-btn").attr('disabled', false)
                    }
                }
            });
        }
    },
    remove: function (id, callback, err) {
        var obj = {
            id: id
        };
        $.post(mw.settings.api_url + "fields/delete", obj, function (data) {
            if (typeof callback === 'function') {
                callback.call(data);
            }
            mw.custom_fields.after_save();
        }).fail(function () {
            if (typeof err === 'function') {
                err.call();
            }
        });
    },

    save: function (id, callback) {
        return this.save_form(id, callback);
    },
    save_form: function (id, callback) {
        var obj = mw.custom_fields.serialize(id);
        $.post(mw.custom_fields.saveurl, obj, function (data) {
            if (data.error != undefined) {
                return false;
            }

            var $cfadm_reload = false;
            if (obj.cf_id === undefined) {
                //      mw.reload_module('.edit [data-parent-module="custom_fields"]');
            }
            mw.$(".mw-live-edit [data-type='custom_fields']").each(function () {
                if (!mw.tools.hasParentsWithClass(this, 'mw_modal') && !mw.tools.hasParentsWithClass(this, 'is_admin')) {
                    //mw.reload_module(this);
                } else {
                    var $cfadm_reload = true;
                }
            });

            mw.reload_module_parent('custom_fields');
            if (typeof load_iframe_editor === 'function') {
                load_iframe_editor();
            }

            mw.reload_module('#mw-admin-custom-field-edit-item-preview-' + data);

            mw.reload_module_everywhere('custom_fields/list', function(win){
                if(win !== window) {
                    if (callback) callback.call(data);
                    mw.trigger('customFieldSaved', [id, data]);
                }
            });

            mw.custom_fields.after_save();
        });
    },

    after_save: function () {

        mw.reload_module_everywhere('custom_fields');
        mw.reload_module_everywhere('custom_fields/list');


        mw.trigger("custom_fields.save");

    },

    autoSaveOnWriting: function (el, id) {
        return false;
        /*mw.on.stopWriting(el, function () {
            this.save_form(id, function () {
                if (typeof __sort_fields === 'function') {
                    // __sort_fields();
                }
            });
        });*/
    },

    add: function (el) {
        var parent = mw.$(mw.tools.firstParentWithClass(el, 'mw-custom-field-form-controls'));
        var clone = parent.clone(true);
        parent.after(clone);
        clone.find("input").val("").focus();
    },
    serialize: function (id) {
        var el = mw.$(id),
        fields =
            "input[type='text'], " +
            "input[type='email'], input[type='number'], input[type='password'], input[type='hidden'], " +
            "textarea, select, input[type='checkbox']:checked, input[type='radio']:checked";
        var data = {};
        data.options = {};
        mw.$(fields, el).not(':disabled').filter(function() { return !!this.name; }).each(function () {
            var el = this, _el = mw.$(el);
            var val = _el.val();
            var name = el.name;
            var notArraySelect = this.nodeName === 'SELECT' && this.multiple === false;
            var notArrayDefault = this.name !== 'options[file_types]';
            var notArray = notArraySelect || notArrayDefault;

            if (name.contains("[")) {

                if (name.contains('[]')) {
                    var _name = name.replace(/[\[\]']+/g, '');

                    if (name.indexOf('option') === 0) {
                        try {
                            data.options.push(val);
                        }
                        catch (e) {
                            data.options = [val];
                        }
                    }
                    else {
                        try {
                            data[_name].push(val);
                        }
                        catch (e) {
                            data[_name] = [val];
                        }
                    }
                }
                else {
                    if (name.indexOf('option') === 0) {
                        name = name.slice(name.indexOf("[") + 1, name.indexOf("]"));
                        if(notArray) {
                            data.options[name] = val;
                        }
                        else {
                            data.options[name] = data.options[name] || [];
                            data.options[name].push(val);
                        }


                    }
                    else {
                        var arr_name = name.slice(0, name.indexOf("["));
                        var key = name.slice(name.indexOf("[") + 1, name.indexOf("]"));
                        if (typeof data[arr_name] === 'object') {
                            data[arr_name][key] = data[arr_name][key] || [];
                            data[arr_name][key].push(val);
                        }
                        else {
                            data[arr_name] = {};
                            data[arr_name][key] = [val];
                        }
                    }
                }
            }
            else {
                data[name] = val;
            }
        });
        if (mw.tools.isEmptyObject(data.options)) {
            data.options = '';
        }

        return data;
    }

}


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/events.js":
/*!*********************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/events.js ***!
  \*********************************************************/
/***/ (function() {


mw.hash = function(b){ return b === undefined ? window.location.hash : window.location.hash = b; };

mw.on = function(eventName, callback){
    eventName = eventName.trim();
    $.each(eventName.split(' '), function(){
        mw.$(mw._on._eventsRegister).on(this.toString(), callback);
    });
};
mw.trigger = function(eventName, paramsArray){
    return mw.$([mww, mw._on._eventsRegister]).trigger(eventName, paramsArray);
};

mw._on = {
  _eventsRegister:{},
  mouseDownAndUp:function(el, callback){
    var $el = mw.$(el);
    el = $el[0];
    $el.on('mousedown touchstart', function(){
      this.__downTime = new Date().getTime();
      (function(el){
        setTimeout(function(){
          el.__downTime = -1;
        }, 777);
      })(this);
    });
    $el.on('mouseup touchend', function(e){
      if(!!callback){
        callback.call(this, new Date().getTime()-this.__downTime, e)
      }
    });
  },
  onmodules : {},
  moduleReload : function(id, c, trigger){
      var exists;
     if(trigger){
          exists = typeof mw.on.onmodules[id] !== 'undefined';
          if(exists){
            var i = 0, l = mw.on.onmodules[id].length;
            for( ; i < l; i++){
               mw.on.onmodules[id][i].call(document.getElementById(id));
            }
          }
        return false;
     }
     if(typeof c === 'function'){
       exists = typeof mw.on.onmodules[id] !== 'undefined';
       if(exists){
          mw.on.onmodules[id].push(c);
       }
       else{
         mw.on.onmodules[id] = [c];
       }
     }
     else if(c==='off'){
        exists = typeof mw.on.onmodules[id] !== 'undefined';
        if(exists){
          mw.on.onmodules[id] = [];
        }
     }
  },
  _hashrec : {},
  _hashparams : this._hashparams || [],
  _hashparam_funcs : [],
  hashParam : function(param, callback, trigger, isManual){
    var index;
    if(isManual){
        index = mw.on._hashparams.indexOf(param);
        if (mw.on._hashparam_funcs[index] !== undefined){
          mw.on._hashparam_funcs[index].call(false, false);
        }
        return false;
    }
    if(trigger === true){
        index = mw.on._hashparams.indexOf(param);

        if(index !== -1){
          var hash = mw.hash();
          var params = mw.url.getHashParams(hash);

          if (typeof params[param] === 'string' && mw.on._hashparam_funcs[index] !== undefined) {
              var pval = decodeURIComponent(params[param]);
              mw.on._hashparam_funcs[index].call(pval, pval);

          }
        }
    }
    else{
        mw.on._hashparams.push(param);
        mw.on._hashparam_funcs.push(callback);
    }
},
hashParamEventInit:function(){
  var hash = mw.hash();
  var params = mw.url.getHashParams(hash);

  if(hash==='' || hash==='#' || hash ==='#?'){
    var len = mw.on._hashparams.length, i=0;
    for( ; i < len; i++){
        mw.on.hashParam(mw.on._hashparams[i], "", true);
    }
  }
  else{
    for(var x in params){
        if(params[x] !== mw.on._hashrec[x] || typeof mw.on._hashrec[x] === 'undefined'){
            mw.on.hashParam(x, "", true);
        }
    }
  }

  mw.on._hashrec = params;
},
DOMChangePause:false,
DOMChangeTime:1500,
DOMChange:function(element, callback, attr, a){
    attr = attr || false;
    a = a || false;

    element.addEventListener("input", function(e){
        if( !mw.on.DOMChangePause ) {
            if(!a){
                callback.call(this);
            }
            else{
                clearTimeout(element._int);
                element._int = setTimeout(function(){
                    callback.call(element);
                }, mw.on.DOMChangeTime);
            }

        }
    }, false);

    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

    if(typeof MutationObserver === 'function'){
        var observer = new MutationObserver(function(mutations) {
          mutations.forEach(function(mutation){
            if( !mw.on.DOMChangePause ) {
                callback.call(mutation.target);
            }
          });
        });
        var config = { attributes: attr, childList: true, characterData: true };
        observer.observe(element, config);
    } else {
        element.addEventListener("DOMCharacterDataModified", function(e){
            if( !mw.on.DOMChangePause ) {
                if(!a){
                    callback.call(this);
                }
                else{
                    clearTimeout(element._int);
                    element._int = setTimeout(function(){
                        callback.call(element);
                    }, mw.on.DOMChangeTime);
                }

            }
        }, false);
        element.addEventListener("DOMNodeInserted", function(e){

            if(/*mw.tools.hasClass(e.target, 'element') || */mw.tools.hasClass(e.target, 'module') || mw.tools.hasParentsWithClass(e.target, 'module')){
                return false;
            }
            if( !mw.on.DOMChangePause ) {
                if(!a){
                    callback.call(this);
                }
                else{
                    clearTimeout(element._int);
                    element._int = setTimeout(function(){
                        callback.call(element);
                    }, mw.on.DOMChangeTime);
                }
            }
        }, false);

        if(attr){
            element.addEventListener("DOMAttrModified", function(e){

                var attr = e.attrName;
                if(attr !== "contenteditable"){
                    if( !mw.on.DOMChangePause ) {
                        if(!a){
                            callback.call(this);
                        }
                        else{
                            clearTimeout(element._int);
                            element._int = setTimeout(function(){
                                callback.call(element);
                            }, mw.on.DOMChangeTime);
                        }
                    }
                }
            }, false);
        }
    }

 },
 stopWriting:function(el, c){
    if(el === null || typeof el === 'undefined'){ return false; }
    if(!el.onstopWriting){
      el.onstopWriting = null;
    }
    clearTimeout(el.onstopWriting);
    el.onstopWriting = setTimeout(function(){
        c.call(el);
    }, 400);
 },
 scrollBarOnBottom : function(obj, distance, callback){
    if(typeof obj === 'function'){
       callback = obj;
       obj =  window;
       distance = 0;
    }
    if(typeof distance === 'function'){
      callback = distance;
      distance = 0;
    }
    obj._pauseCallback = false;
    obj.pauseScrollCallback = function(){ obj._pauseCallback = true;}
    obj.continueScrollCallback = function(){ obj._pauseCallback = false;}
    mw.$(obj).scroll(function(e){
      var h = obj === window ? document.body.scrollHeight : obj.scrollHeight;
      var calc = h - mw.$(obj).scrollTop() - mw.$(obj).height();
      if(calc <= distance && !obj._pauseCallback){
        callback.call(obj);
      }
    });
  },
  tripleClick : function(el, callback){
      var t, timeout = 199;
      el = el || window;
      el.addEventListener("dblclick", function () {
          t = setTimeout(function () {
              t = null;
          }, timeout);
      });
      el.addEventListener("click", function (e) {
          if (t) {
              clearTimeout(t);
              t = null;
              callback.call(el, e.target);
          }
      });
  },
  transitionEnd:function(el,callback){
    mw.$(el).bind('webkitTransitionEnd transitionend msTransitionEnd oTransitionEnd otransitionend', function(){
        callback.call(el);
    });
  },
  ones:{ },
  one:function(name, c, trigger, isDone){
    if(trigger !== true){
      if(mw.on.ones[name] === undefined){
         mw.on.ones[name] = [c]
      }
      else{
         mw.on.ones[name].push(c);
      }
    }
    else{
       if(mw.on.ones[name] !== undefined){
          var i=0, l = mw.on.ones[name].length;
          for( ; i<l; i++){
              if(isDone === true){
                mw.on.ones[name][i].call('ready', 'ready');
              }
              else{
                mw.on.ones[name][i].call('start', 'start');
              }
          }
       }
    }
  },
  userIteractionInitRegister: new Date().getTime(),
  userIteractionInit: function(){
      var max = 378;
      mw.$(mwd).on('mousemove touchstart click keydown resize ajaxStop', function(){
          var time = new Date().getTime();
          if((time - mw._on.userIteractionInitRegister) > max){
              mw._on.userIteractionInitRegister = time;
              mw.trigger('UserInteraction');
          }
      });
  }
};

for(var x in mw._on) mw.on[x] = mw._on[x];



mw.hashHistory = [window.location.hash]

mw.prevHash = function(){
  var prev = mw.hashHistory[mw.hashHistory.length - 2];
  return prev !== undefined ? prev : '';
};



$(window).on("hashchange load", function(event){
    if(event.type === 'load'){
        mw._on.userIteractionInit();
    }

    mw.on.hashParamEventInit();

   var hash =  mw.hash();

   var isMWHash = hash.replace(/\#/g, '').indexOf('mw@') === 0;
   if (isMWHash) {
       var MWHash = hash.replace(/\#/g, '').replace('mw@', '');
       var el = document.getElementById(MWHash);
       if(el) {
           mw.tools.scrollTo(el);
       }
   }
   if(hash.contains("showpostscat")){
      mw.$("html").addClass("showpostscat");
   }
   else{
      mw.$("html").removeClass("showpostscat");
   }


   if (event.type === 'hashchange') {
     mw.hashHistory.push(mw.hash());
     var size = mw.hashHistory.length;
     var changes = mw.url.whichHashParamsHasBeenRemoved(mw.hashHistory[size-1], mw.hashHistory[size-2]), l=changes.length, i=0;
     if (l>0) {
       for( ; i < l; i++ ){
          mw.on.hashParam(changes[i], "", true, true);
       }
     }
   }
});


mw.event = {
    windowLeave: function(c) {
      document.documentElement.addEventListener('mouseout', function(e) {
          if (!e.relatedTarget && !e.toElement && c) {
              c.call(document.body, e);
          }
      });
    },
    cancel:function(e, prevent){
    prevent === true ? e.preventDefault() : '';
    e.cancelBubble = true;
    if (e.stopPropagation) e.stopPropagation();
    },
    key:function(e,key){
        return (e.keyCode === parseFloat(key));
    },
    page: function (e) {
      e = e.originalEvent || e;
      if (e.type.indexOf('touch') !== 0) {
        return {
            x: e.pageX,
            y: e.pageY
        };
      } else {
          return {
              x: e.changedTouches[0].pageX,
              y: e.changedTouches[0].pageY
          };
      }
    },
    targetIsField: function(e) {
        e = e.originalEvent || e;
        var t = e.target;
        return t.nodeName === 'INPUT' ||
            t.nodeName === 'textarea' ||
            t.nodeName === 'select';
    },
    get: function(e) {
        return e.originalEvent || e;
    },
    keyCode: function(e) {
        e = mw.event.get(e);
        return e.keyCode || e.which;
    },
    isKeyCode: function(e, code){
        return this.keyCode(e) === code;
    },
    is: {
      comma: function (e) {
          e = mw.event.get(e);
          return e.keyCode === 188;
              },
      enter: function (e) {
        e = mw.event.get(e);
        return e.key === "Enter" || mw.event.isKeyCode(e, 13);
      },
      escape: function (e) {
          e = mw.event.get(e);
          return e.key === "Escape" || mw.event.isKeyCode(e, 27);
      },
      backSpace : function (e) {
        e = mw.event.get(e);
        return e.key === "Backspace" || mw.event.isKeyCode(e, 8);
      },
      delete: function (e) {
          e = mw.event.get(e);
          return e.key === "Delete" || mw.event.isKeyCode(e, 46);
      }
    }
};














/***/ }),

/***/ "./userfiles/modules/microweber/api/core/files.js":
/*!********************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/files.js ***!
  \********************************************************/
/***/ (() => {



 

mw.files = {
    settings: {
            filetypes:"png,gif,jpg,jpeg,tiff,bmp,svg,webp",
            url: mw.settings.upload_url,
            type: 'explorer',
            multiple: true
    },
    filetypes:function(a, normalize) {
            var def = !!normalize ? a : mw.files.settings.filetypes;
            switch(a){
            case 'img':
            case 'image':
            case 'images':
                return mw.files.settings.filetypes;
            case 'video':
            case 'videos':
                return 'avi,asf,mpg,mpeg,mp4,flv,mkv,webm,ogg,wma,mov,wmv';
            case 'file':
            case 'files':
                return 'doc,docx,pdf,html,js,css,htm,rtf,txt,zip,gzip,rar,cad,xml,psd,xlsx,csv';
            case 'documents':
            case 'doc':
                return 'doc,docx,log,pdf,msg,odt,pages,rtf,tex,txt,wpd,wps,pps,ppt,pptx,xml,htm,html,xlr,xls,xlsx';
            case 'archives':
            case 'arc':
            case 'arch':
                return 'zip,zipx,gzip,rar,gz,7z,cbr,tar.gz';
             case 'audio':
                return 'mp3,wav,ogg,mp4,flac';
             case 'media':
                return (mw.files.filetypes('video') + ',' + mw.files.filetypes('audio'));
             case 'all':
                return '*';
             case '*':
                return '*';
             default:
                return def;
            }
    },
    normalize_filetypes:function(a){
        var str = '';
        a = a.replace(/\s/g, '');
        var arr = a.split(','), i=0, l=arr.length;
        for( ; i<l; i++){
            str+= mw.files.filetypes(arr[i], true) + ',';
        }
        str = str.substring(0, str.length - 1);
        return str;
    },
    safeFilename:function(url){
            if(!url) return;
            url = url.replace(/["]/g, "%22").replace(/[']/g, "%27").replace(/\(/g, "%28").replace(/\)/g, "%29");
            return url;
    },
    urlAsBackground:function(url, el){
            url = this.safeFilename(url);
            var bg = 'url("'+ url +'")';
            if(!!el){
                    el.style.backgroundImage = bg;
            }
            return bg;
    },
    uploader: function (o) {
        return mw.upload(o);
    }
}



/***/ }),

/***/ "./userfiles/modules/microweber/api/core/fonts.js":
/*!********************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/fonts.js ***!
  \********************************************************/
/***/ (() => {


/***********************


    var myFont = new mw.font();


    //create group of fonts

    myFont.set({
        family:{
            Roboto:[300,400],
            'Architects Daughter':[400, 700],
        },
        subset:["cyrillic","cyrillic-ext","korean","latin-ext"]
    })

    //add to group

    myFont.add({
        family:{
            Roboto:[100]
        }
    })


    myFont.remove('Roboto', 100) // removes weight 100

    myFont.remove('Roboto')  // removes family



*************************/




mw.fonts = {
    _create:function(){
        var el = document.createElement('link');
        el.rel = 'stylesheet';
        document.documentElement.appendChild(el);
        return el;
    },
    _unique:function(obj){
        var data = {};
        var n;
        for(n in obj){
            data.name = n;
            data.weight = obj[n];
        }
        return data;
    },
    google:{
        create:function(){
            var root = 'https://fonts.googleapis.com/css?';
            var el = mw.fonts._create();
            el._rooturl = root;
            el._config = {};

            return el;
        },
        remove:function(el, family, weight){
            if(!family){
                mw.$(el).removeAttr('href');
                el._config = {};
            }
            else if(!weight){
                if(el._config.family && el._config.family[family]){
                    delete el._config.family[family];
                }
                this.config(el._config, el)
            }
            else if(weight && family){
                weight = parseInt(weight, 10)
                if(el._config.family && el._config.family[family]){
                    for(var i=0; i<el._config.family[family].length; i++){
                        el._config.family[family][i] = parseInt(el._config.family[n][i], 10);
                    }
                }
            }
        },
        setUrl:function(options, el){
            var url = 'family=';
            for( var i in options.family){
                url += i + ':'+options.family[i].join(',') + '|';
            }
            url = url.substring(0, url.length - 1);

            if(options.subset){
                url += '&amp;subset=' + options.subset.join(',')
            }
            el._config = options;
            el.href = el._rooturl + url.replace(/\s/g, '+');
        },
        config:function(options, el, mode){

            /*
            {
                family:{
                     'Roboto': [300,500] ,
                    'Tajawal': [400,700]
                },

                subset:["cyrillic","cyrillic-ext","korean","latin-ext"]
            }
            */

            for(var n in el._config.family){
                for(var i=0; i<el._config.family[n].length; i++){
                    el._config.family[n][i] = parseInt(el._config.family[n][i], 10);
                }
            }

            if(mode == 'add'){
                $.each(options.family, function(key,val){
                    if(el._config.family && el._config.family[key]){
                        options.family[key] = el._config.family[key].concat(options.family[key]);
                        options.family[key] = options.family[key].filter( function (value, i, self) {
                            return self.indexOf(value) == i;
                        });
                    }
                });
                $.each(el._config.family, function(key,val){
                    if(options.family && !options.family[key]){
                        options.family[key] = el._config.family[key]
                    }
                });
            }

            this.setUrl(options, el)

        }
    },
    noop:{
        fonts:[],
        config:function(){

        }
    }
}
mw.font = function(){
    this.data = {};
    this.init = function(options){
        options = options || {};
        if(options.provider){
            options.provider = options.provider.trim().toLowerCase();
        }
        else{
            options.provider = 'google';
        }
        if(!this[options.provider]){
            this[options.provider] = mw.fonts[options.provider].create();
        }
        this.options = options;
    }
    this.remove = function(family, weight){
        mw.fonts[this.options.provider].remove(this[options.provider], family, weight);
    }

    this.add = function(options){
        this.init(options)
        mw.fonts[this.options.provider].config(this.options, this[options.provider], 'add');
    };

    this.set = function(options){
        this.init(options);
        mw.fonts[this.options.provider].config(this.options, this[options.provider]);
    };


}


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/forms.js":
/*!********************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/forms.js ***!
  \********************************************************/
/***/ (() => {

mw.serializeFields =  function(id, ignorenopost){
    ignorenopost = ignorenopost || false;
    var el = mw.$(id);
    var fields = "input[type='text'], input[type='email'], input[type='number'], input[type='tel'], "
        + "input[type='color'], input[type='url'], input[type='week'], input[type='search'], input[type='range'], "
        + "input[type='datetime-local'], input[type='month'], "
        + "input[type='password'], input[type='hidden'], input[type='datetime'], input[type='date'], input[type='time'], "
        +"input[type='email'],  textarea, select, input[type='checkbox']:checked, input[type='radio']:checked, "
        +"input[type='checkbox'][data-value-checked][data-value-unchecked]";
    var data = {};
    $(fields, el).each(function(){
        if(!this.name){
            console.warn('Name attribute missing on ' + this.outerHTML);
        }
        if((!$(this).hasClass('no-post') || ignorenopost) && !this.disabled && this.name && typeof this.name != 'undefined'){
            var el = this, _el = $(el);
            var val = _el.val();
            var name = el.name;
            if(el.name.contains("[]")){
                data[name] = data[name] || []
                data[name].push(val);
            }
            else if(el.type === 'checkbox' && el.getAttribute('data-value-checked') ){
                data[name] = el.checked ? el.getAttribute('data-value-checked') : el.getAttribute('data-value-unchecked');
            }
            else{
                data[name] = val;
            }
        }
    });
    return data;
}


var getFieldValue = function(a){
    return typeof a === 'string' ? a : ( typeof a === 'object' && a.tagName !== undefined ? a.value : null);
};



mw.Form = function(options) {
    options = options || {};
    var defaults = {
        form: null
    };
    this.settings = $.extend({}, defaults, options);

    this.$form = mw.$(this.settings.form).eq(0);
    this.form = this.$form[0];
    if (!this.form) {
        return;
    }

    this.addBeforePost = function (func, cb) {

    };
};




mw.form = {
    typeNumber:function(el){
        el.value = el.value.replace(/[^0-9\.,]/g,'');
    },
    fixPrice:function(el){
        el.value = el.value.replace(/,/g,'');
        var arr = el.value.split('.');
        var len = arr.length;
        if(len>1){
            if(arr[len-1]===''){
                arr[len-1] = '.00';
            }
            else{
                arr[len-1] = '.' + arr[len-1];
            }
            el.value = arr.join('');
        }
    },

    post: function(selector, url_to_post, callback, ignorenopost, callback_error, callback_user_cancel, before_send){
        mw.session.checkPause = true;
        if(selector.constructor === {}.constructor){
            return mw.form._post(selector);
        }

        callback_error = callback_error || false;
        ignorenopost = ignorenopost || false;
        var is_form_valid = mw.form.validate.init(selector);

        if(!url_to_post){

            url_to_post = mw.settings.site_url + 'api/post_form';

        }
        if(is_form_valid){
            var form = mw.$(selector)[0];
            if(form._isSubmitting){
                return;
            }
            form._isSubmitting = true;
            var when = form.$beforepost ? form.$beforepost : function () {};
            $.when(when()).then(function() {
                setTimeout(function () {
                    var obj = mw.form.serialize(selector, ignorenopost);
                    var req = {
                        url: url_to_post,
                        data: before_send ? before_send(obj) : obj,
                        method: 'post',
                        dataType: "json",

                        success: function(data){
                            /*
                                                   if(typeof (data.error) != 'undefined' && data.error){
                                                       mw.notification.error(data.error);
                                                   }*/

                            mw.session.checkPause = false;
                            if(typeof callback === 'function'){
                                callback.call(data, mw.$(selector)[0]);
                            } else {
                                return data;
                            }
                        },

                        onExternalDataDialogClose: function() {
                            if(callback_user_cancel) {
                                callback_user_cancel.call();
                            }
                        }
                    }

                    if (form.getAttribute('enctype') === "multipart/form-data") {

                        var form_data = new FormData();
                        $.each(req.data, function (k,v) {
                            form_data.append(k,v);
                        });

                        $('[type="file"]', form).each(function () {
                            if(typeof this.files[0] !== 'undefined') {
                                form_data.set(this.name, this.files[0]);
                            }
                        })

                        req.data = form_data;
                        req.processData = false;
                        req.contentType = false;
                        req.mimeType = 'multipart/form-data';
                    }

                    var xhr = $.ajax(req);
                    xhr.always(function(jqXHR, textStatus) {
                        form._isSubmitting = false;
                    });
                    xhr.fail(function(a,b) {
                        mw.session.checkPause = false;
                        if(typeof callback_error === 'function'){
                            callback_error.call(a,b);
                        }
                    });
                }, 78);
            });


        }
        return false;
    },
    _post:function(obj){
        mw.form.post(obj.selector, obj.url, obj.done, obj.ignorenopost, obj.error, obj.error);
    },
    validate:{
        checkbox: function(obj){
            return obj.checked === true;
        },
        field:function(obj){
            return getFieldValue(obj).replace(/\s/g, '') != '';
        },
        email:function(obj){
            var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/;
            return regexmail.test(getFieldValue(obj));
        },
        url:function(obj){
            /* var rurl =/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig; */
            var rurl = /^((https?|ftp):\/\/)?(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/;
            return rurl.test(getFieldValue(getFieldValue(obj)));
        },
        radio:function(objname){
            var radios = document.getElementsByName(objname), i = 0, len = radios.length;
            this_radio_valid = false;
            for( ; i < len ; i++){
                if(radios[i].checked){
                    this_radio_valid = true;
                    break;
                }
            }
            var parent = mw.$(document.getElementsByName(objname)[0].parentNode);
            if(this_radio_valid){
                parent.removeClass("error");
            }
            else{
                parent.addClass("error");
            }
            return this_radio_valid;
        },
        image_url:function(url, valid, invalid){
            var url = url.replace(/\s/gi,'');
            if(url.length<6){
                typeof invalid =='function'? invalid.call(url) : '';
                return false;
            }
            else{
                if(!url.contains('http')){var url = 'http://'+url}
                if(!window.ImgTester){
                    window.ImgTester = new Image();
                    document.body.appendChild(window.ImgTester);
                    window.ImgTester.className = 'semi_hidden';
                    window.ImgTester.onload = function(){
                        typeof valid =='function'? valid.call(url) : '';
                    }
                    window.ImgTester.onerror = function(){
                        typeof invalid =='function'? invalid.call(url) : '';
                    }
                }
                window.ImgTester.src = url;
            }
        },
        proceed:{
            checkbox:function(obj){
                if(mw.form.validate.checkbox(obj)){
                    mw.$(obj).parents('.field').removeClass("error");
                }
                else{
                    mw.$(obj).parents('.field').addClass("error");
                }
            },
            field:function(obj){
                if(mw.form.validate.field(obj)){
                    mw.$(obj).parents('.field').removeClass("error");
                }
                else{
                    mw.$(obj).parents('.field').addClass("error");
                }
            },
            email:function(obj){
                if(mw.form.validate.email(obj)){
                    mw.$(obj).parents('.field').removeClass("error");
                }
                else{
                    mw.$(obj).parents('.field').addClass("error");
                }
            }
        },
        checkFields:function(form){
            mw.$(form).find(".required,[required]").each(function(){
                var type = mw.$(this).attr("type");
                if(type=='checkbox'){
                    mw.form.validate.proceed.checkbox(this);
                }
                else if(type=='radio'){
                    mw.form.validate.radio(this.name);
                }
                else{
                    mw.form.validate.proceed.field(this);
                }
            });
            mw.$(form).find(".required-email").each(function(){
                mw.form.validate.proceed.email(this);
            });
        },
        init:function(obj){
            mw.form.validate.checkFields(obj);
            if($(obj).find(".error").length>0){
                mw.$(obj).addClass("error submited");
                return false;
            }
            else{
                mw.$(obj).removeClass("error");
                return true;
            }
        }
    },
    serialize : function(id, ignorenopost){
        var ignorenopost = ignorenopost || false;
        return mw.serializeFields(id, ignorenopost);
    }
}


mw.postForm = function(o){
    return mw.form._post(o);
}
















/***/ }),

/***/ "./userfiles/modules/microweber/api/core/i18n.js":
/*!*******************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/i18n.js ***!
  \*******************************************************/
/***/ (() => {

mw.lang = function (key) {
    var camel = key.trim().replace(/(?:^\w|[A-Z]|\b\w)/g, function (letter, index) {
        return index == 0 ? letter.toLowerCase() : letter.toUpperCase();
    }).replace(/\s+/g, '');
    if (mw._lang[camel]) {
        return mw._lang[camel];
    }
    else {
        // console.warn('"' + key + '" is not present.');
        return key;
    }
};
mw.msg = mw._lang = {
    uniqueVisitors: 'Unique visitors',
    allViews: 'All views',
    date: 'Date',
    weekDays: {
        regular: [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ],
        short: [
            'Sun',
            'Mon',
            'Tue',
            'Wed',
            'Thu',
            'Fri',
            'Sat'
        ]
    },
    months: {
        regular: [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ],
        short: [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'June',
            'July',
            'Aug',
            'Sept',
            'Oct',
            'Nov',
            'Dec'
        ]
    },
    ok: "OK",
    category: "Category",
    published: "Published",
    unpublished: "Unpublished",
    contentunpublished: "Content is unpublished",
    contentpublished: "Content is published",
    save: "Save",
    saving: "Saving",
    saved: "Saved",
    settings: "Settings",
    cancel: "Cancel",
    remove: "Remove",
    close: "Close",
    to_delete_comment: "Are you sure you want to delete this comment",
    del: "Are you sure you want to delete this?",
    save_and_continue: "Save &amp; Continue",
    before_leave: "Leave without saving",
    session_expired: "Your session has expired",
    login_to_continue: "Please login to continue",
    more: "More",
    templateSettingsHidden: "Template settings",
    less: "Less",
    product_added: "Your product is added to cart",
    no_results_for: "No results for",
    switch_to_modules: 'Switch to Modules',
    switch_to_layouts: 'Switch to Layouts',
    loading: 'Loading',
    edit: 'Edit',
    change: 'Change',
    submit: 'Submit',
    settingsSaved: 'Settings are saved',
    addImage: 'Add new image'
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/libs.js":
/*!*******************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/libs.js ***!
  \*******************************************************/
/***/ (() => {

(function (){
    var libs = {
        jqueryui: [
            function () {
                mw.require(mw.settings.libs_url + 'jqueryui' + '/jquery-ui.min.js');
                mw.require(mw.settings.libs_url + 'jqueryui' + '/jquery-ui.min.css');
            }
        ],
        morris: ['morris.css', 'raphael.js', 'morris.js'],
        rangy: ['rangy-core.js', 'rangy-cssclassapplier.js', 'rangy-selectionsaverestore.js', 'rangy-serializer.js'],
        highlight: [

            'highlight.min.js',
            'highlight.min.css'

        ],
        bootstrap2: [
            function () {
                var v = document.querySelector('meta[name="viewport"]');
                if (v === null) {
                    v = document.createElement('meta');
                    v.name = "viewport";
                }
                v.content = "width=device-width, initial-scale=1.0";
                mw.head.appendChild(v);
            },
            'css/bootstrap.min.css',
            'css/bootstrap-responsive.min.css',
            'js/bootstrap.min.js'
        ],
        bootstrap3: [
            function () {
                mw.require(mw.settings.libs_url + 'fontawesome-4.7.0' + '/css/font-awesome.min.css');
                var v = document.querySelector('meta[name="viewport"]');
                if (v === null) {
                    v = document.createElement('meta');
                    v.name = "viewport";
                }
                v.content = "width=device-width, initial-scale=1.0";
                mw.head.appendChild(v);
            },
            'css/bootstrap.min.css',
            'js/bootstrap.min.js'
        ],
        bootstrap4: [
            function () {
                mw.require(mw.settings.libs_url + 'bootstrap-4.3.1' + '/css/bootstrap.min.css');
                mw.require(mw.settings.libs_url + 'bootstrap-4.3.1' + '/js/popper.min.js');
                mw.require(mw.settings.libs_url + 'bootstrap-4.3.1' + '/js/bootstrap.min.js');
                mw.require(mw.settings.libs_url + 'fontawesome-free-5.12.0' + '/css/all.min.css');
            }
        ],
        microweber_ui: [
            function () {
                mw.require(mw.settings.libs_url + 'mw-ui' + '/grunt/plugins/ui/css/main.css');
                mw.require(mw.settings.libs_url + 'mw-ui' + '/assets/ui/plugins/css/plugins.min.css');
                mw.require(mw.settings.libs_url + 'mw-ui' + '/assets/ui/plugins/js/plugins.js');
            }


        ],

        flag_icons: [
            function () {
                mw.require(mw.settings.libs_url + 'flag-icon-css' + '/css/flag-icon.min.css');

            }
        ],
        font_awesome: [
            function () {
                mw.require(mw.settings.libs_url + 'fontawesome-4.7.0' + '/css/font-awesome.min.css');

            }
        ],
        font_awesome5: [
            function () {
                mw.require(mw.settings.libs_url + 'fontawesome-free-5.12.0' + '/css/all.min.css');

            }
        ],
        bxslider: [
            function () {
                mw.require(mw.settings.libs_url + 'bxSlider/jquery.bxslider.min.js', true);
                mw.require(mw.settings.libs_url + 'bxSlider/jquery.bxslider.css', true);

            }
        ],
        collapse_nav: [
            function () {
                mw.require(mw.settings.libs_url + 'collapse-nav/dist/collapseNav.js', true);
                mw.require(mw.settings.libs_url + 'collapse-nav/dist/collapseNav.css', true);

            }
        ],
        slick: [
            function () {
                mw.require(mw.settings.libs_url + 'slick' + '/slick.css', true);
                mw.moduleCSS(mw.settings.libs_url + 'slick' + '/slick-theme.css');
                mw.require(mw.settings.libs_url + 'slick' + '/slick.min.js', true);
            }
        ],
        bootstrap_datepicker: [
            function () {
                mw.require(mw.settings.libs_url + 'bootstrap-datepicker' + '/css/bootstrap-datepicker3.css', true);
                mw.require(mw.settings.libs_url + 'bootstrap-datepicker' + '/js/bootstrap-datepicker.js', true);
            }
        ],
        bootstrap_datetimepicker: [
            function () {
                mw.require(mw.settings.libs_url + 'bootstrap-datetimepicker' + '/css/bootstrap-datetimepicker.min.css', true);
                mw.require(mw.settings.libs_url + 'bootstrap-datetimepicker' + '/js/bootstrap-datetimepicker.min.js', true);
            }
        ],
        bootstrap3ns: [
            function () {

                //var bootstrap_enabled = (typeof $().modal == 'function');
                var bootstrap_enabled = (typeof $ != 'undefined' && typeof $.fn != 'undefined' && typeof $.fn.emulateTransitionEnd != 'undefined');

                if (!bootstrap_enabled) {
                    mw.require(mw.settings.libs_url + 'bootstrap3' + '/js/bootstrap.min.js');
                    //var bootstrap_enabled = (typeof $().modal == 'function');
                    //if (bootstrap_enabled == false) {
                    mw.require(mw.settings.libs_url + 'bootstrap3ns' + '/bootstrap.min.css');
                    mw.require(mw.settings.libs_url + 'fontawesome-4.7.0' + '/css/font-awesome.min.css');
                }
                // }
            }
        ],
        bootstrap_select: [
            function () {
                //var bootstrap_enabled = (typeof $().modal == 'function');
                //if (!bootstrap_enabled == false) {
                mw.require(mw.settings.libs_url + 'bootstrap-select-1.13.12' + '/js/bootstrap-select.min.js');
                mw.require(mw.settings.libs_url + 'bootstrap-select-1.13.12' + '/css/bootstrap-select.min.css');
                //}
            }
        ],
        bootstrap_tags: [
            function () {

                // var bootstrap_enabled = (typeof $().modal == 'function');
                //if (!bootstrap_enabled == false) {
                mw.require(mw.settings.libs_url + 'typeahead' + '/typeahead.jquery.js');
                mw.require(mw.settings.libs_url + 'typeahead' + '/typeahead.bundle.min.js');
                mw.require(mw.settings.libs_url + 'typeahead' + '/bloodhound.js');
                mw.require(mw.settings.libs_url + 'mw-ui/grunt/plugins/tags' + '/bootstrap-tagsinput.css');
                mw.require(mw.settings.libs_url + 'mw-ui/grunt/plugins/tags' + '/bootstrap-tagsinput.js');
                //} else {
                //mw.log("You must load bootstrap to use bootstrap_tags");
                //}

            }
        ],
        chosen: [
            function () {
                mw.require(mw.settings.libs_url + 'chosen' + '/chosen.jquery.min.js');
                mw.require(mw.settings.libs_url + 'chosen' + '/chosen.min.css', true);
            }
        ],
        validation: [
            function () {
                mw.require(mw.settings.libs_url + 'jquery_validation' + '/js/jquery.validationEngine.js');
                mw.require(mw.settings.libs_url + 'jquery_validation' + '/js/languages/jquery.validationEngine-en.js');
                mw.require(mw.settings.libs_url + 'jquery_validation' + '/css/validationEngine.jquery.css');
            }
        ],

        fitty: [
            function () {
                mw.require(mw.settings.libs_url + 'fitty' + '/dist/fitty.min.js');
                /*$(document).ready(function () {
                 fitty('.fitty-element');
                 });*/
            }
        ],


        flatstrap3: [
            function () {
                var v = document.querySelector('meta[name="viewport"]');
                if (v === null) {
                    v = document.createElement('meta');
                    v.name = "viewport";
                }
                v.content = "width=device-width, initial-scale=1.0";
                mw.head.appendChild(v);
            },
            'css/bootstrap.min.css',
            'js/bootstrap.min.js'
        ],
        datepicker: [
            'datepicker.min.js',
            'datepicker.min.css'
        ],
        datetimepicker: [
            'jquery.datetimepicker.full.min.js',
            'jquery.datetimepicker.min.css'
        ],

        nestedSortable: [
            function () {
                mw.require(mw.settings.libs_url + 'nestedsortable' + '/jquery.mjs.nestedSortable.js');
            }
        ],
        colorpicker: [
            function () {
                 mw.require(mw.settings.libs_url + 'acolorpicker' + '/acolorpicker.js');
            }
        ],
        material_icons: [
            function () {
                mw.require(mw.settings.libs_url + 'material_icons' + '/material_icons.css');
            }
        ],
        materialDesignIcons: [
            function () {
                mw.require('css/fonts/materialdesignicons/css/materialdesignicons.min.css');
            }
        ],
        mw_icons_mind: [
            function () {
                mw.require('fonts/mw-icons-mind/line/style.css');
                mw.require('fonts/mw-icons-mind/solid/style.css');
            }
        ],
        uppy: [
            'uppy.min.js',
            'uppy.min.css'
        ],
        apexcharts: [
            'apexcharts.min.js',
            'apexcharts.css'
        ]
    };


    mw.lib = {
        _required: [],
        require: function (name) {
            if (mw.lib._required.indexOf(name) !== -1) {
                return false;
            }
            mw.lib._required.push(name);
            if (typeof libs[name] === 'undefined') return false;
            if (libs[name].constructor !== [].constructor) return false;
            var path = mw.settings.libs_url + name + '/',
                arr = libs[name],
                l = arr.length,
                i = 0,
                c = 0;
            for (; i < l; i++) {
                (typeof arr[i] === 'string') ? mw.require(path + arr[i], true) : (typeof arr[i] === 'function') ? arr[i].call() : '';
            }
        },
        get: function (name, done, error) {
            if (mw.lib._required.indexOf(name) !== -1) {
                if (typeof done === 'function') {
                    done.call();
                }
                return false;
            }

            if (typeof libs[name] === 'undefined') return false;
            if (libs[name].constructor !== [].constructor) return false;
            mw.lib._required.push(name);
            var path = mw.settings.libs_url + name + '/',
                arr = libs[name],
                l = arr.length,
                i = 0,
                c = 1;
            for (; i < l; i++) {
                var xhr = $.cachedScript(path + arr[i]);
                xhr.done(function () {
                    c++;
                    if (c === l) {
                        if (typeof done === 'function') {
                            done.call();
                        }
                    }
                });
                xhr.fail(function (jqxhr, settings, exception) {

                    if (typeof error === 'function') {
                        error.call(jqxhr, settings, exception);
                    }

                });
            }
        }
    };

})();


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/modules.js":
/*!**********************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/modules.js ***!
  \**********************************************************/
/***/ (() => {


(function (){

    // https://github.com/axios/axios#request-config
    var Ajax = function (options) {
        var scope = this;

        var _e = {};

        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

        var instance;

        this.config = function (options) {
            instance = axios.create(options);
        };

        this.config(options);

        this.request = function (config) {
            return instance.request(config);
        };
        this.get = function (url, config){
            return instance.get(url, config);
        };
        this.delete = function (url, config){
            return instance.delete(url, config);
        };
        this.head = function (url, config){
            return instance.head(url, config);
        };
        this.options = function (url, config) {
            return instance.options(url, config);
        };
        this.post = function (url, data, config) {
            return instance.post(url, config);
        };
        this.put = function (url, data, config) {
            return instance.put(url, config);
        };
        this.patch = function (url, data, config) {
            return instance.patch(url, config);
        };

    };


    mw.xhr = function (options) {
        return new Ajax(options);
    };

    mw.apiXHR = mw.xhr({
        baseURL: mw.settings.api_url.slice(0, -1)
    });



})();




mw.load_module = function(name, selector, callback, attributes) {
    attributes = attributes || {};
    attributes.module = name;
    return mw._({
        selector: selector,
        params: attributes,
        done: function() {
            mw.settings.sortables_created = false;
            if (typeof callback === 'function') {
                callback.call(mw.$(selector)[0]);
            }
        }
    });
};

mw.module = {
    xhr: mw.xhr({
        baseURL: mw.settings.modules_url
    }),
    getData: function (module, options) {
        if(typeof module === 'object') {
            options = module;
            module = options.module;
        }
        options = options || {};
        options.module = module || options.module;
        return mw.module.xhr.post('/', options);
    },
    getAttributes: function (target) {
        var node = mw.element(target).get(0);
        if (!target) return;
        var attrs = node.attributes;
        var data = {};
        for (var i in attrs) {
            if(attrs.hasOwnProperty(i) && attrs[i] !== undefined){
                var name = attrs[i].name;
                var val = attrs[i].nodeValue;
                if(typeof data[name] === 'undefined'){
                    data[name]  = val;
                }
            }
        }
        return data;
    },
    insert: function(target, module, config, pos) {
        return new Promise(function (resolve) {
            pos = pos || 'bottom';
            var action;
            var id = mw.id('mw-module-'),
                el = '<div id="' + id + '"></div>';

            if (pos === 'top') {
                action = 'before';
                if (mw.tools.hasClass(target, 'allow-drop')) {
                    action = 'prepend';
                }
            } else if (pos === 'bottom') {
                action = 'after';
                if (mw.tools.hasClass(target, 'allow-drop')) {
                    action = 'append';
                }
            }
            mw.element(target)[action](el);
            mw.load_module(module, '#' + id, function () {
                resolve(this);
            }, config);
        });
    }
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/mw-require.js":
/*!*************************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/mw-require.js ***!
  \*************************************************************/
/***/ (() => {

mw.required = [] ;
mw.require = function(url, inHead, key) {
    if(!url) return;
    if(typeof inHead === 'boolean' || typeof inHead === 'undefined'){
        inHead = inHead || false;
    }
    var keyString;
    if(typeof inHead === 'string'){
        keyString = ''+inHead;
        inHead = key || false;
    }
    if(typeof key === 'string'){
        keyString = key;
    }
    var toPush = url, urlModified = false;
    if (!!keyString) {
        toPush = keyString;
        urlModified = true;
    }
    var t = url.split('.').pop();
    var scope =  mw.settings.modules_url + '/microweber';
    if(!url.contains('/')) {
        return;
    }
    url = url.contains('//') ? url : (t !== 'css' ? ( scope + '/api/' + url)  :  scope + '/css/' + url);
    if(!urlModified) toPush = url;
    if (!~mw.required.indexOf(toPush)) {
        mw.required.push(toPush);
        url = url.contains("?") ?  url + '&mwv=' + mw.version : url + "?mwv=" + mw.version;
        if(document.querySelector('link[href="'+url+'"],script[src="'+url+'"]') !== null){
            return;
        }
        var string = t !== "css" ? "<script type='text/javascript'  src='" + url + "'></script>" : "<link rel='stylesheet' type='text/css' href='" + url + "' />";
        if(typeof $.fn === 'object'){
            $(document.head).append(string);
        }
        else{
            var el;
            if( t !== "css")  {
                el = document.createElement('script');
                el.src = url;
                el.setAttribute('type', 'text/javascript');
                document.head.appendChild(el);
            }
            else{
                el = document.createElement('link');
                el.rel='stylesheet';
                el.type='text/css';
                el.href = url;
                document.head.appendChild(el);
            }
        }

    }
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/options.js":
/*!**********************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/options.js ***!
  \**********************************************************/
/***/ (() => {

// JavaScript Document

/**
 *
 * Options API
 *
 * @package        js
 * @subpackage        options
 * @since        Version 0.567
 */

// ------------------------------------------------------------------------

/**
 * mw.options
 *
 *  mw.options object
 *
 * @package        js
 * @subpackage    options
 * @category    options internal api
 * @version 1.0
 */
mw.options = {
    saveOption: function (o, c, err) {
        if (typeof o !== 'object') {
            return false;
        }
        var group = o.group || o.option_group,
            key = o.key || o.option_key,
            value = typeof o.value !== 'undefined' ? o.value : o.option_value;

        if (!group || !key || (typeof value === 'undefined')) {
            return false;
        }
        var lang = false;
        if (typeof(o.lang) !== 'undefined') {
            lang = o.lang;
        }

        var data = {
            option_group: group,
            option_key: key,
            option_value: value,

        };

        if(lang){
            // for multilanguage module
            data.lang=lang;
        }



        return $.ajax({
            type: "POST",
            url: mw.settings.site_url + "api/save_option",
            data: data,
            success: function (a) {
                if (typeof c === 'function') {
                    c.call(a);
                }
            },
            error: function (a) {
                if (typeof err === 'function') {
                    err.call(a);
                }
            }
        });
    },
    save: function (el, callback) {


        el = mw.$(el);
        var og, og1, refresh_modules11;
        if (!el) {
            return;
        }


        var opt_id = el.attr('data-id');

        og1 = og = el.attr('option-group') || el.attr('option_group') || el.attr('data-option-group');


        if (og1 == null || (typeof(og1) === 'undefined') || og1 == '') {

        }
        var og_parent = null
        var og_test = mw.tools.firstParentWithClass(el[0], 'module');
        if (og_test) {
            og_parent = og_test.id;

            og_parent = mw.$(og_test).attr('for-module-id') || og_test.id;


        }
        // refresh_modules11 = og1 = og = og_test.id;


        var refresh_modules12 = el.attr('data-reload') || el.attr('data-refresh');

        var also_reload = el.attr('data-reload') || el.attr('data-also-reload');

        var modal = mw.$(mw.dialog.get(el).container);

        if (refresh_modules11 == undefined && modal !== undefined) {

            var for_m_id = modal.attr('data-settings-for-module');

        }
        if (refresh_modules11 == undefined) {
            var refresh_modules11 = el.attr('data-refresh');

        }

        var a = ['data-module-id', 'data-settings-for-module', 'option-group', 'data-option-group', 'data-refresh'],
            i = 0, l = a.length;


        var mname = modal !== undefined ? modal.attr('data-type') : undefined;

        // if (typeof(refresh_modules11) == 'undefined') {
        //     for (; i < l; i++) {
        //         var og = og === undefined ? el.attr(a[i]) : og;
        //     }
        // } else {
        //     var og = refresh_modules11;
        // }
        //
        // if (og1 != undefined) {
        //     var og = og1;
        //     if (refresh_modules11 == undefined) {
        //         if (refresh_modules12 == undefined) {
        //             refresh_modules11 = og1;
        //         } else {
        //             refresh_modules11 = refresh_modules12;
        //         }
        //     }
        // }


        var val;
        if (el[0].type === 'checkbox') {
            val = '',
                dvu = el.attr('data-value-unchecked'),
                dvc = el.attr('data-value-checked');
            if (!!dvu && !!dvc) {
                val = el[0].checked ? dvc : dvu;
            }
            else {

                var items = document.getElementsByName(el[0].name), i = 0, len = items.length;
                for (; i < len; i++) {
                    var _val = items[i].value;
                    val = items[i].checked == true ? (val === '' ? _val : val + "," + _val) : val;
                }
            }

        }
        else {
            val = el.val();
        }
        if (typeof(og) == 'undefined' && typeof(og) == 'undefined' && og_parent) {
            og = og_parent;
        }


        //  alert(og + '       ' +og1);


        var o_data = {
            option_key: el.attr('name'),
            option_group: og,
            option_value: val
        }


        if (mname === undefined) {


       if (mname === undefined && og_test !== undefined && og_test &&  $(og_test).attr('data-type')) {
            var mname_from_type = $(og_test).attr('data-type');
            mname = (mname_from_type.replace('/admin', ''));
            o_data.module = mname;
        } else if (og_test !== undefined && og_test && $(og_test).attr('parent-module')) {
              o_data.module = $(og_test).attr('parent-module');
             }
        }


        if (mname !== undefined) {
            o_data.module = mname;
        }


        if (for_m_id !== undefined) {
            o_data.for_module_id = for_m_id;
        }
        if (og != undefined) {
            o_data.id = have_id;
        }




        var have_id = el.attr('data-custom-field-id');

        if (have_id != undefined) {
            o_data.id = have_id;
        }

        var have_option_type = el.attr('data-option-type');

        if (have_option_type != undefined) {
            o_data.option_type = have_option_type;
        } else {
            var have_option_type = el.attr('option-type');

            if (have_option_type != undefined) {
                o_data.option_type = have_option_type;
            }
        }
        var reaload_in_parent = el.attr('parent-reload');

        if (opt_id !== undefined) {
            o_data.id = opt_id;
        }

        var attrLang = el.attr('lang');
        if (typeof(attrLang) !== 'undefined') {
            o_data.lang = attrLang;
        }

        var attrModule = el.attr('module');
        if (typeof(attrModule) !== 'undefined') {
            o_data.module = attrModule;
        }

        $.ajax({
            type: "POST",
            url: mw.settings.site_url + "api/save_option",
            data: o_data,
            success: function (data) {

                var which_module_to_reload = null;


                if (typeof(refresh_modules11) == 'undefined') {
                    which_module_to_reload = og1;
                } else if (refresh_modules12) {
                    which_module_to_reload = refresh_modules12;
                }

                if ((typeof(liveEditSettings) != 'undefined' && liveEditSettings) || mw.top().win.liveEditSettings) {
                    if (!og1 && og_parent) {
                        which_module_to_reload = og_parent;
                    }
                }

                var reload_in_parent_trieggered = false;


                //  alert('refresh_modules11     '+refresh_modules11);
                //  alert('which_module_to_reload     '+which_module_to_reload);
                // alert('og1      '+og1);


                if (mw.admin) {
                    if (mw.top().win.mweditor && mw.top().win.mweditor.contentWindow) {
                        setTimeout(function () {
                            mw.top().win.mweditor.contentWindow.mw.reload_module("#" + which_module_to_reload);

                        }, 777);
                    }
                }
                if (window.parent.mw) {

                    if (self !== top) {

                        setTimeout(function () {

                            var mod_element = window.parent.document.getElementById(which_module_to_reload);
                            if (mod_element) {
                                // var module_parent_edit_field = window.mw.parent().tools.firstParentWithClass(mod_element, 'edit')
                               // var module_parent_edit_field = window.mw.parent().tools.firstMatchesOnNodeOrParent(mod_element, ['.edit[rel=inherit]'])
                                var module_parent_edit_field = window.mw.parent().tools.firstMatchesOnNodeOrParent(mod_element, ['.edit:not([itemprop=dateModified])']);
                                if (!module_parent_edit_field) {
                                   module_parent_edit_field = window.mw.parent().tools.firstMatchesOnNodeOrParent(mod_element, ['.edit[rel=inherit]']);
                                }

                                if (module_parent_edit_field) {
                                   // window.mw.parent().tools.addClass(module_parent_edit_field, 'changed');
                                    window.mw.parent().wysiwyg.change(module_parent_edit_field)
                                    window.mw.parent().askusertostay = true;

                                }
                            }

                            mw.reload_module_parent("#" + which_module_to_reload);
                            if (which_module_to_reload != og1) {
                                mw.reload_module_parent("#" + og1);
                            }
                            reload_in_parent_trieggered = 1;


                        }, 777);
                    }

                    if (window.mw.parent().reload_module != undefined) {

                        if (!!mw.admin) {
                            setTimeout(function () {
                                window.mw.parent().reload_module("#" + which_module_to_reload);
                                mw.options.___rebindAllFormsAfterReload();
                            }, 777);
                        }
                        else {
                            if (window.parent.mweditor != undefined) {
                                window.parent.mweditor.contentWindow.mw.reload_module("#" + which_module_to_reload, function () {
                                    setTimeout(function () {
                                        window.mw.parent().exec("mw.admin.editor.set", window.parent.mweditor);
                                        mw.options.___rebindAllFormsAfterReload();
                                    }, 777);
                                });
                            }
                            if (window.parent.mw != undefined) {
                                window.mw.parent().reload_module("#" + which_module_to_reload, function () {
                                    setTimeout(function () {
                                        window.mw.parent().exec("mw.admin.editor.set", window.parent.mweditor);
                                        mw.options.___rebindAllFormsAfterReload();
                                    }, 777);
                                });
                            }
                        }
                        reload_in_parent_trieggered = 1;

                    }
                }


                // if (reaload_in_parent != undefined && reaload_in_parent !== null) {
                //     //     window.mw.parent().reload_module("#"+refresh_modules11);
                //
                //     return false;
                // }


                if (also_reload != undefined) {


                    if (window.mw != undefined && reaload_in_parent !== true) {
                        if (window.mw.reload_module !== undefined) {

                            window.mw.reload_module(also_reload, function (reloaded_el) {

                                //  mw.options.form(reloaded_el, callback);
                                mw.options.___rebindAllFormsAfterReload();
                            });
                            window.mw.reload_module('#' + also_reload, function (reloaded_el) {

                                //mw.options.form(reloaded_el, callback);
                                mw.options.___rebindAllFormsAfterReload();
                            });
                        }
                    }

                }

                /*           if (reaload_in_parent !== true && for_m_id != undefined && for_m_id != '') {
                               for_m_id = for_m_id.toString()
                               if (window.mw != undefined) {




                                   // if (window.mw.reload_module !== undefined) {
                                   //
                                   // 			window.mw.reload_module('#'+for_m_id, function(reloaded_el){
                                   //
                                   // 				mw.options.form(reloaded_el, callback);
                                   // 			});
                                   //        }
                               }
                           } else*/


                if (reload_in_parent_trieggered == false && reaload_in_parent !== true && which_module_to_reload != undefined && which_module_to_reload != '') {
                    which_module_to_reload = which_module_to_reload.toString()


                    if (window.mw.reload_module !== undefined) {

                        mw.reload_module_parent(which_module_to_reload);
                        mw.reload_module_parent("#" + which_module_to_reload);


                    }


                }


                typeof callback === 'function' ? callback.call(data) : '';
                setTimeout(function () {
                    mw.options.___rebindAllFormsAfterReload();
                }, 111);
                //
                //
                //d(refresh_modules11);
                //d(mw.options._bindedRootFormsRegistry);
            }
        })
    }
};

mw.options._optionSaved = null;

mw.options._bindedRootFormsRegistry = [];

mw.options.remove_bindings = function ($selector) {

    var $root = mw.$($selector);
    var root = $root[0];
    if (!root) return;

    if (root._optionsEvents) {
        delete(root._optionsEvents);
        root._optionsEventsClearBidings = true;
    }
    root.addClass('mw-options-form-force-rebind');


    mw.$("input, select, textarea", root)
        .not('.mw-options-form-binded-custom')
        .each(function () {
            var item = mw.$(this);


            if (item && item[0] && item[0]._optionsEventsBinded) {
                delete(item[0]._optionsEventsBinded);

            }
        });

};
mw.options.form = function ($selector, callback, beforepost) {



    //setTimeout(function () {


    var numOfbindigs = 0;
    var force_rebind = false;

    var $root = mw.$($selector);
    var root = $root[0];
    if (!root) return;

    //
    if (root && $root.hasClass('mw-options-form-force-rebind')) {
        force_rebind = true;

    }

    if (!root._optionsEvents) {

        mw.$("input, select, textarea", root)
            .not('.mw-options-form-binded-custom')
            .each(function () {
                //this._optionSaved = true;

                var item = mw.$(this);
                if (force_rebind) {
                    item[0]._optionsEventsBinded = null;
                }


                if (item && item[0] && !item[0]._optionsEventsBinded) {

                    if (item.hasClass('mw_option_field')) {
                        numOfbindigs++;


                        item[0]._optionsEventsBinded = true;


                        if (root._optionsEventsClearBidings) {
                            item.off('change input paste');
                        }

                        item.addClass('mw-options-form-binded');
                        item.on('change input paste', function (e) {

                            var isCheckLike = true;
                            var token = isCheckLike ? this.name : this.name + mw.$(this).val();
                            mw.options.___slowDownEvent(token, this, function () {
                                if (typeof root._optionsEvents.beforepost === 'function') {
                                    root._optionsEvents.beforepost.call(this);
                                }
                                if (top !== self && window.mw.parent().drag && window.mw.parent().drag.save) {
                                    window.mw.parent().drag.save();
                                }
                                mw.options.save(this, root._optionsEvents.callback);
                            });
                            //}
                        });
                    }
                }
            });
    }


    //  alert($selector +'   --   ' +numOfbindigs);


    // REBIND
    if (numOfbindigs > 0) {
        root._optionsEvents = root._optionsEvents || {};
        root._optionsEvents = $.extend({}, root._optionsEvents, {callback: callback, beforepost: beforepost});


        var rebind = {};
        if (typeof root._optionsEvents.beforepost === 'function') {
            rebind.beforepost = root._optionsEvents.beforepost;
        }
        rebind.callback = root._optionsEvents.callback;
        rebind.binded_selector = $selector;
        var rebindtemp = mw.tools.cloneObject(rebind);
        //fix here chek if in array


        var is_in = mw.options._bindedRootFormsRegistry.filter(function (a) {
            return a.binded_selector === $selector;
        })

        if (!is_in.length) {
            mw.options._bindedRootFormsRegistry.push(rebindtemp);
        }
    }
    // END OF REBIND


    //}, 10,$selector, callback, beforepost);


};


mw.options.___slowDownEvents = {};
mw.options.___slowDownEvent = function (token, el, call) {
    if (typeof mw.options.___slowDownEvents[token] === 'undefined') {
        mw.options.___slowDownEvents[token] = null;
    }
    clearTimeout(mw.options.___slowDownEvents[token]);
    mw.options.___slowDownEvents[token] = setTimeout(function () {
        call.call(el);
    }, 700);
};

mw.options.___rebindAllFormsAfterReload = function () {

    var token = '___rebindAllFormsAfterReload';


    mw.options.___slowDownEvent(token, this, function () {


        for (var i = 0, l = mw.options._bindedRootFormsRegistry.length; i < l; i++) {
            var binded_root = mw.options._bindedRootFormsRegistry[i];
            if (binded_root.binded_selector) {

                var $root = mw.$(binded_root.binded_selector);
                var root = $root[0];
                if (root) {

                    var rebind_beforepost = null;
                    var rebind_callback = null;
                    if (typeof binded_root.beforepost === 'function') {
                        var rebind_beforepost = binded_root.beforepost;
                    }

                    if (typeof binded_root.callback === 'function') {
                        var rebind_callback = binded_root.callback;
                    }
                    var has_non_binded = false;
                    mw.$("input, select, textarea", root)
                        .not('.mw-options-form-binded-custom')
                        .not('.mw-options-form-binded')
                        .each(function () {
                            var item = mw.$(this);
                            if (item.hasClass('mw_option_field')) {
                                if (!item[0]._optionsEventsBinded) {
                                    has_non_binded = true;
                                    item.attr('autocomplete', 'off');
                                }
                            }
                        });

                    if (root._optionsEvents && has_non_binded && rebind_callback) {
                        root._optionsEvents = null;
                        root._optionsEventsClearBidings = true;
                        mw.options.form(binded_root.binded_selector, rebind_callback, rebind_beforepost);

                        // mw.options._bindedRootFormsRegistry =  mw.options._bindedRootFormsRegistry.filter(function (a) {
                        //     return a.binded_selector != binded_root.binded_selector
                        // })

                    }
                }


            }
        }
    });
}
//
// mw.options.___locateModuleNodesToBeRealoaded = function (selectror,window_scope) {
//
//    var module = module.replace(/##/g, '#');
//    var m = mw.$(".module[data-type='" + module + "']");
//    if (m.length === 0) {
//        try { var m = mw.$(module); }  catch(e) {};
//    }
//
//}


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/polyfills.js":
/*!************************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/polyfills.js ***!
  \************************************************************/
/***/ (() => {

if (typeof Object.assign !== 'function') {
    Object.defineProperty(Object, "assign", {
        value: function assign(target) {
            'use strict';
            if (target === null || target === undefined) {
                throw new TypeError('Cannot convert undefined or null to object');
            }
            var to = Object(target);
            for (var index = 1; index < arguments.length; index++) {
                var nextSource = arguments[index];
                if (nextSource !== null && nextSource !== undefined) {
                    for (var nextKey in nextSource) {
                        if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                            to[nextKey] = nextSource[nextKey];
                        }
                    }
                }
            }
            return to;
        },
        writable: true,
        configurable: true
    });
}


if (!Array.isArray) {
    Array.isArray = function(arg) {
        return Object.prototype.toString.call(arg) === '[object Array]';
    };
}
if (Array.prototype.indexOf === undefined) {
    Array.prototype.indexOf = function(obj) {
        var i=0, l=this.length;
        for ( ; i < l; i++) {
            if (this[i] === obj) {
                return i;
            }
        }
        return -1;
    }
}

String.prototype.contains = function(a) {
    return !!~this.indexOf(a);
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/response.js":
/*!***********************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/response.js ***!
  \***********************************************************/
/***/ (() => {

mw.response = function(form, data, messages_at_the_bottom){
    messages_at_the_bottom = messages_at_the_bottom || false;
    if(data == null  ||  typeof data == 'undefined' ){
        return false;
    }

    data = mw.tools.toJSON(data);
    if(typeof data === 'undefined'){
        return false;
    }

    if(typeof data.error !== 'undefined'){
        mw._response.error(form, data, messages_at_the_bottom);
        return false;
    }
    else if(typeof data.success !== 'undefined'){
        mw._response.success(form, data, messages_at_the_bottom);
        return true;
    }
    else if(typeof data.warning !== 'undefined'){
        mw._response.warning(form, data, messages_at_the_bottom);
        return false;
    }
    else{
        return false;
    }
};

mw._response = {
    error:function(form, data, _msg){
        form = mw.$(form);
        var err_holder = mw._response.msgHolder(form, 'error');
        var msg = typeof data.message !== 'undefined' ? data.message : data.error;
        mw._response.createHTML(msg, err_holder);
    },
    success:function(form, data, _msg){
        form = mw.$(form);
        var err_holder = mw._response.msgHolder(form, 'success');
        var msg = typeof data.message !== 'undefined' ? data.message : data.success;
        mw._response.createHTML(msg, err_holder);
    },
    warning:function(form, data, _msg){
        form = mw.$(form);
        var err_holder = mw._response.msgHolder(form, 'warning');
        var msg = typeof data.message !== 'undefined' ? data.message : data.warning;
        mw._response.createHTML(msg, err_holder);
    },
    msgHolder : function(form, type, method){
        method = method || 'append';
        var err_holder = form.find(".mw-checkout-response:first");
        var err_holder2 = form.find(".alert:first");
        if(err_holder.length === 0){
            err_holder = err_holder2;
        }
        if(err_holder.length === 0){
            err_holder = document.createElement('div');
            form[method](err_holder);
        }

        var bootrap_error_type = 'default';
        if(type === 'error'){
            bootrap_error_type = 'danger';
        } else if(type === 'done'){
            bootrap_error_type = 'info';
        }


        $(err_holder).empty().attr("class", 'alert alert-' + type + ' alert-' + bootrap_error_type + ' ');
        return err_holder;
    },
    createHTML:function(data, holder){
        var i, html = "";


        if(typeof data === 'string'){
            html+= data.toString();
        }
        else{
            for( i in data){
                if(typeof data[i] === 'string'){
                    html+='<li>'+data[i]+'</li>';
                }
                else if(typeof data[i] === 'object'){
                    $.each(data[i], function(){
                        html+='<li>'+this+'</li>';
                    })
                }
            }
        }
        mw.$(holder).eq(0).append('<ul class="mw-error-list">'+html+'</ul>');
        mw.$(holder).eq(0).show();
    }
}


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/session.js":
/*!**********************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/session.js ***!
  \**********************************************************/
/***/ (() => {

mw.session = {
    checkPause: false,
    checkPauseExplicitly: false,
    check: function (callback) {
        if (!mw.session.checkPause) {
            mw.session.checkPause = true;
            if (mw.session.checkPauseExplicitly) {
                return false;
            }
            $.post(mw.settings.api_url + "is_logged", function (data) {
                if (data === null) {
                    return;
                }
                if (data !== false) {
                    if (typeof callback === 'function') {
                        callback.call(undefined, true);
                    }

                }
                else {
                    if (typeof callback === 'function') {
                        callback.call(undefined, false)
                    }

                }
                mw.session.checkPause = false;
            });
        }
    },
    logRequest: function () {
        var modal = mw.dialog({
            html: "<h3 style='margin:0;'>" + mw.msg.session_expired + ".</h3> <p style='margin:0;'>" + mw.msg.login_to_continue + ".</p> <br> <div id='session_popup_login'></div>",
            id: "session_modal",
            name: "session_modal",
            overlay: true,
            width: 400,
            height: 300,
            template: 'mw_modal_basic',
            callback: function () {
                mw.load_module("users/login", '#session_popup_login', false, {template: 'popup'});
            }
        });
    },
    checkInit: function () {
        if (self !== top) {
            return false;
        }
        setInterval(function () {
            mw.session.check(function (is_logged) {
                if (is_logged) {
                    var m = mw.dialog.get("#session_modal");
                    if (m) {
                        m.remove();
                    }
                }
                else {
                    mw.session.logRequest();
                }
            });
        }, 300000); // 5 minutes
    }
}
$(document).ready(function () {

    mw.$(document).on("ajaxSend",function () {

        mw.session.checkPause = true;
    }).bind("ajaxComplete", function () {
            mw.session.checkPause = false;
        });
});


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/shop.js":
/*!*******************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/shop.js ***!
  \*******************************************************/
/***/ (() => {



mw.product = {
    quick_view: function(product_id, dialog_title) {
        $.get(mw.settings.api_url + 'product/quick-view', {id:product_id},
            function (html) {
                mw.dialog({
                    title: dialog_title,
                    width: 960,
                    content: html,
                    onremove: function () {

                    },
                    name: 'product-quick-view'
                });
            }
        );
    }
};

mw.cart = {

    add_and_checkout: function (content_id, price, c) {
        if (typeof(c) == 'undefined') {
            var c = function () {
                window.location.href = mw.settings.api_url + 'shop/redirect_to_checkout';
            }
        }
        return mw.cart.add_item(content_id, price, c);
    },

    add_item: function (content_id, price, c) {
        var data = {};
        if (content_id == undefined) {
            return;
        }

        data.content_id = content_id;

        if (price != undefined && data != undefined) {
            data.price = price;
        }

        $.post(mw.settings.api_url + 'update_cart', data,
            function (data) {

                //   mw.cart.after_modify(data);


                if (typeof c === 'function') {
                    c.call(data);
                }
                mw.cart.after_modify(data, ['mw.cart.add']);
                mw.trigger('cartAddItem', data);

                //  mw.trigger('mw.cart.add', [data]);
            });
    },

    add: function (selector, price, c) {
        var data = mw.form.serialize(selector);


        var is_form_valid = true;
        mw.$('[required],.required', selector).each(function () {

            if (!this.validity.valid) {
                is_form_valid = false

                var is_form_valid_check_all_fields_tip = mw.tooltip({
                    id: 'mw-cart-add-invalid-form-tooltip-show',
                    content: 'This field is required',
                    close_on_click_outside: true,
                    group: 'mw-cart-add-invalid-tooltip',
                    skin: 'warning',
                    element: this
                });


                return false;
            }
        });

        if (!is_form_valid) {
            return;
        }


        if (price != undefined && data != undefined) {
            data.price = price;
        }
        if (data.price == null) {
            data.price = 0;
        }
        $.post(mw.settings.api_url + 'update_cart', data,
            function (data) {

                // mw.trigger('mw.cart.add', [data]);

                if (typeof c === 'function') {
                    c.call(data);
                }
                mw.cart.after_modify(data, ['mw.cart.add']);
                mw.trigger('cartAddItem', data);


            });
    },

    remove: function ($id) {
        var data = {}
        data.id = $id;

        $.post(mw.settings.api_url + 'remove_cart_item', data,
            function (data) {
                var parent = mw.$('.mw-cart-item-' + $id).parent();
                mw.$('.mw-cart-item-' + $id).fadeOut(function () {
                    mw.$(this).remove();
                    if (parent.find(".mw-cart-item").length == 0) {

                    }
                });
                //mw.cart.after_modify();
                // mw.reload_module('shop/cart');
                // mw.reload_module('shop/shipping');
                // mw.trigger('mw.cart.remove', [data]);
                mw.cart.after_modify(data, ['mw.cart.remove']);
                mw.trigger('cartRemoveItem', data);

            });
    },

    qty: function ($id, $qty) {
        var data = {}
        data.id = $id;
        data.qty = $qty;
        $.post(mw.settings.api_url + 'update_cart_item_qty', data,
            function (data) {
                // mw.reload_module('shop/cart');
                // mw.reload_module('shop/shipping');
                // mw.trigger('mw.cart.qty', [data]);

                if(data && typeof(data.error) !== 'undefined'){
                    if(typeof(data.message) !== 'undefined'){
                        mw.notification.warning(data.message);
                    }
                }

                mw.cart.after_modify(data, ['mw.cart.qty']);
                mw.trigger('cartModify', data);


            });

    },

    after_modify: function (data, events_to_trigger) {


        var modules = ["shop/cart", "shop/shipping", "shop/payments"].filter(function (module) {
            return !!document.querySelector('[data-type="' + module + '"');
        });

        var events = ['mw.cart.modify'];

        if (!!events_to_trigger) {
            var events = events.concat(events_to_trigger);
        }


        if (modules.length) {
            mw.reload_modules(modules, function (data) {
                events.forEach(function (item) {
                    mw.trigger(item, [data]);
                })
            }, true);
        } else {
            events.forEach(function (item) {
                mw.trigger(item, [data]);
            })
        }


        // mw.reload_module('shop/cart');
        // mw.reload_module('shop/shipping');
        // mw.reload_module('shop/payments');


        if ((typeof data == 'object') && typeof data.cart_items_quantity !== 'undefined') {
            $('.js-shopping-cart-quantity').html(data.cart_items_quantity);
        }


        mw.trigger('mw.cart.after_modify', data);
        mw.trigger('cartModify', data);


    },

    checkout: function (selector, callback, beforeRedirect) {

        if (!beforeRedirect) {
            beforeRedirect = function () {
                return new Promise(function (){
                    resolve();
                });
            };
        }

        var form = mw.$(selector);
        $(document).trigger("checkoutBeforeProcess", form);


        var state = form.dataset("loading");
        if (state == 'true') return false;
        form.dataset("loading", 'true');
        form.find('.mw-checkout-btn').attr('disabled', 'disabled');
        form.find('.mw-checkout-btn').hide();

        setTimeout(function () {

            var form = mw.$(selector);
            var obj = mw.form.serialize(form);


            $.ajax({
                type: "POST",
                url: mw.settings.api_url + 'checkout',
                data: obj,
                error: function (xhr, ajaxOptions, thrownError) {
                     mw.errorsHandle(JSON.parse(xhr.responseText))
                    form.dataset("loading", 'false');
                    form.find('.mw-checkout-btn').removeAttr('disabled');
                    form.find('.mw-checkout-btn').show();

                }
            })
                .done(function (data) {
                    mw.trigger('checkoutDone', data);

                    var data2 = data;

                    if (data != undefined) {
                        mw.$(selector + ' .mw-cart-data-btn').removeAttr('disabled');
                        mw.$('[data-type="shop/cart"]').removeAttr('hide-cart');


                        if (typeof(data2.error) != 'undefined') {
                            mw.$(selector + ' .mw-cart-data-holder').show();
                            if (typeof(data2.error.address_error) != 'undefined') {
                                var form_with_err = form;
                                var isModalForm = $(form_with_err).attr('is-modal-form')

                                if (isModalForm) {
                                    mw.cart.modal.showStep(form_with_err, 'delivery-address');
                                }
                                mw.notification.error('Please fill your address details');

                            }

                            mw.response(selector, data2);
                        } else if (typeof(data2.success) != 'undefined') {


                            if (typeof callback === 'function') {
                                callback.call(data2.success);

                            } else if (typeof window[callback] === 'function') {
                                window[callback](selector, data2.success);
                            } else {

                                mw.$('[data-type="shop/cart"]').attr('hide-cart', 'completed');
                                mw.reload_module('shop/cart');
                                mw.$(selector + ' .mw-cart-data-holder').hide();
                                mw.response(selector, data2);
                            }





                            if (typeof(data2.redirect) != 'undefined') {

                                setTimeout(function () {
                                    beforeRedirect().then(function (){
                                        window.location.href = data2.redirect;
                                    });
                                }, 100);
                                return;
                            } else {
                                mw.trigger('mw.cart.checkout.success', data2);
                                mw.trigger('checkoutSuccess', [data]);

                            }


                        } else if (parseInt(data) > 0) {
                            mw.$('[data-type="shop/checkout"]').attr('view', 'completed');
                            mw.reload_module('shop/checkout');
                        } else {
                            if (obj.payment_gw != undefined) {
                                var callback_func = obj.payment_gw + '_checkout';
                                if (typeof window[callback_func] === 'function') {
                                    window[callback_func](data, selector);
                                }
                                var callback_func = 'checkout_callback';
                                if (typeof window[callback_func] === 'function') {
                                    window[callback_func](data, selector);
                                }
                            }
                        }

                    }
                    form.dataset("loading", 'false');
                    form.find('.mw-checkout-btn').removeAttr('disabled');
                    form.find('.mw-checkout-btn').show();
                    mw.trigger('checkoutResponse', data);
                });

        }, 1500);
    }
}

if (typeof(mw.cart.modal) == 'undefined') {
    mw.cart.modal = {};
}
if (typeof(mw.cart.modal.init) == 'undefined') {
    mw.cart.modal.init = function (root_node) {
        mw.cart.modal.bindStepButtons(root_node);

        /*
            var inner_cart_module = $(root_node).find('[parent-module-id="js-ajax-cart-checkout-process"]')[0];
        */
        var inner_cart_module = $(root_node).find('[id="cart_checkout_js-ajax-cart-checkout-process"]')[0];
        if (inner_cart_module) {
            var check = $(document).find('[id="' + inner_cart_module.id + '"]').length
            mw.on.moduleReload(inner_cart_module.id);
        }
    };
}
if (typeof(mw.cart.modal.bindStepButtons) == 'undefined') {

    mw.cart.modal.bindStepButtons = function (root_node) {
        if (typeof root_node === 'string') {
            root_node = mw.$(root_node);
        }

        if (root_node[0]._bindStepButtons) {
            return;
        }
        root_node[0]._bindStepButtons = true;

        var checkout_form = $(root_node).find('form').first();


        $('body').on("mousedown touchstart", '.js-show-step', function () {
            var step = $(this).attr('data-step');

            mw.cart.modal.showStep(checkout_form, step);


        });
    };

    mw.cart.modal.showStep = function (form, step) {


        var prevStep = mw.$('.js-show-step.active', form).data('step');

        if (prevStep === step) return;

        var prevHolder = $(form).find('.js-' + prevStep).first();

        $(form).attr('is-modal-form', true);

        if (step === 'checkout-complete') {
            return;
        }

        var validate = function (callback) {
            var hasError = false;
            mw.$('input,textarea,select', prevHolder).each(function () {
                if (!this.checkValidity()) {
                    mw.$(this).addClass('is-invalid');
                    hasError = true;
                } else {
                    mw.$(this).removeClass('is-invalid');
                }
            });
            if (step === 'payment-method' || step === 'preview') {
                if (hasError) {
                    step = 'delivery-address';
                    callback.call(undefined, hasError, undefined, step);
                }
            }
            if (step === 'payment-method') {
                $.post(mw.settings.api_url + 'checkout/validate', mw.serializeFields(prevHolder), function (data) {
                    if (!data.valid) {
                        step = 'delivery-address';
                    }
                    callback.call(undefined, !data.valid, undefined, step);

                }).fail(function (data) {
                    mw.errorsHandle(data)
                });
            } else {
                callback.call(undefined, hasError, undefined, step);
            }
        };

        validate(function (hasError, message, step) {
            if (hasError) {
                message = message || mw.lang('Please fill properly the required fields');
                mw.notification.warning(message);
            }

            mw.$('.js-show-step').removeClass('active');
            mw.$('[data-step]').removeClass('active');
            mw.$('[data-step="' + step + '"]').addClass('active').parent().removeClass('muted');
            mw.$(this).addClass('active');
            var step1 = '.js-' + step;
            mw.$('.js-step-content').hide();
            mw.$(step1).show();

        });


    };
}

mw.cart.modal.bindStepButtons__old = function (root_node) {
    if (typeof root_node === 'string') {
        root_node = mw.$(root_node);
    }

    if (root_node[0]._bindStepButtons) {
        return;
    }
    root_node[0]._bindStepButtons = true;

    root_node.find('.js-show-step').on("mousedown touchstart", function () {

        var has_error = false;

        var form = mw.tools.firstParentWithTag(this, 'form');
        var prevStep = mw.$('.js-show-step.active', form).data('step');
        var step = this.dataset.step;

        if (prevStep === step) return;


        var prevHolder = form.querySelector('.js-' + prevStep);


        if (step === 'checkout-complete') {
            return;
        }
        mw.$('input,textarea,select', prevHolder).each(function () {
            if (!this.checkValidity()) {
                mw.$(this).addClass('is-invalid');
                has_error = 1;
            } else {
                mw.$(this).removeClass('is-invalid');
            }
        });
        if (step === 'payment-method' || step === 'preview') {
            if (has_error) {
                step = 'delivery-address';
            }
        }
        mw.$('.js-show-step').removeClass('active');
        mw.$('[data-step]').removeClass('active');
        mw.$('[data-step="' + step + '"]').addClass('active').parent().removeClass('muted');
        mw.$(this).addClass('active');
        var step1 = '.js-' + step;
        mw.$('.js-step-content').hide();
        mw.$(step1).show();
        if (has_error) {
            mw.notification.warning('Please fill the required fields');
        }
    });

}


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/upgrades.js":
/*!***********************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/upgrades.js ***!
  \***********************************************************/
/***/ (() => {

window.onmessage = function (e) {

//    if ( e.origin !== "http://html5demos.com" ) {
//        return;
//    }


    if (typeof e.data != 'undefined') {


        if (typeof e.data.market_id != 'undefined' || typeof e.data.mw_version != 'undefined') {
            mw.notification.success("Installing item", 9000);

            if (typeof e.data.market_id != 'undefined') {
                var url = mw.settings.api_url + "mw_install_market_item";
            } else if (typeof e.data.mw_version != 'undefined') {
                var url = mw.settings.api_url + "mw_set_updates_queue";

            }

            $.post(url, e.data)
                .done(function (data) {
                    mw.notification.msg(data, 5000);

                    if (typeof(data.update_queue_set != 'undefined')) {


                        var update_queue_set_modal = mw.dialog({
                            content: '<div class="module" type="updates/worker" id="update_queue_process_alert"></div>',
                            overlay: false,
                            id: 'update_queue_set_modal',
                            title: 'Installing'
                        });


                        mw.reload_module('#update_queue_process_alert');
                        mw.reload_module('updates/list');
                    }

                });
        }
    }
    // document.getElementById("test").innerHTML = e.origin + " said: " + e.data;
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/uploader.js":
/*!***********************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/uploader.js ***!
  \***********************************************************/
/***/ (() => {

;(function (){

    var Uploader = function( options ) {
        //var upload = function( url, data, callback, type ) {
        options = options || {};
        options.accept = options.accept || options.filetypes;
        var defaults = {
            multiple: false,
            progress: null,
            element: null,
            url: options.url || (mw.settings.site_url + 'plupload'),
            urlParams: {},
            on: {},
            autostart: true,
            async: true,
            accept: '*',
            chunkSize: 1500000,
        };

        var normalizeAccept = function (type) {
            type = (type || '').trim().toLowerCase();
            if(!type) return '*';
            if (type === 'image' || type === 'images') return '.png,.gif,.jpg,.jpeg,.tiff,.bmp,.svg';
            if (type === 'video' || type === 'videos') return '.mp4,.webm,.ogg,.wma,.mov,.wmv';
            if (type === 'document' || type === 'documents') return '.doc,.docx,.log,.pdf,.msg,.odt,.pages,' +
                '.rtf,.tex,.txt,.wpd,.wps,.pps,.ppt,.pptx,.xml,.htm,.html,.xlr,.xls,.xlsx';

            return '*';
        };

        var scope = this;
        this.settings = $.extend({}, defaults, options);
        this.settings.accept = normalizeAccept(this.settings.accept);

        this.getUrl = function () {
            var params = this.urlParams();
            var empty = mw.tools.isEmptyObject(params);
            return this.url() + (empty ? '' : ('?' + $.param(params)));
        };

        this.urlParam = function (param, value) {
            if(typeof value === 'undefined') {
                return this.settings.urlParams[param];
            }
            this.settings.urlParams[param] = value;
        };

        this.urlParams = function (params) {
            if(!params) {
                return this.settings.urlParams;
            }
            this.settings.urlParams = params;
        };

        this.url = function (url) {
            if(!url) {
                return this.settings.url;
            }
            this.settings.url = url;
        };

        this.create = function () {
            this.input = document.createElement('input');
            this.input.multiple = this.settings.multiple;
            this.input.accept = this.settings.accept;
            this.input.type = 'file';
            this.input.className = 'mw-uploader-input';
            this.input.oninput = function () {
                scope.addFiles(this.files);
            };
        };

        this.files = [];
        this._uploading = false;
        this.uploading = function (state) {
            if(typeof state === 'undefined') {
                return this._uploading;
            }
            this._uploading = state;
        };

        this._validateAccept = this.settings.accept
            .toLowerCase()
            .replace(/\*/g, '')
            .replace(/ /g, '')
            .split(',')
            .filter(function (item) {
                return !!item;
            });
        this.validate = function (file) {
            if(!file) return false;
            var ext = '.' + file.name.split('.').pop().toLowerCase();
            if (this._validateAccept.length === 0) {
                return true;
            }
            for (var i = 0; i < this._validateAccept.length; i++) {
                var item =  this._validateAccept[i];
                if(item === ext) {
                    return true;
                }
                else if(file.type.indexOf(item) === 0) {
                    return true;
                }
            }
            return false;

        };

        this.addFile = function (file) {
            if(this.validate(file)) {
                if(!this.files.length || this.settings.multiple){
                    this.files.push(file);
                    if(this.settings.on.fileAdded) {
                        this.settings.on.fileAdded(file);
                    }
                    $(scope).trigger('FileAdded', file);
                } else {
                    this.files = [file];
                    $(scope).trigger('FileAdded', file);
                    if(this.settings.on.fileAdded) {
                        this.settings.on.fileAdded(file);
                    }
                }
            }
        };

        this.addFiles = function (files) {

            if(!files || !files.length) return;

            if(!this.settings.multiple) {
                files = [files[0]];
            }
            if (files && files.length) {
                for (var i = 0; i < files.length; i++) {
                    scope.addFile(files[i]);
                }
                if(this.settings.on.filesAdded) {
                    if(this.settings.on.filesAdded(files) === false) {
                        return;
                    }
                }
                $(scope).trigger('FilesAdded', [files]);
                if(this.settings.autostart) {
                    this.uploadFiles();
                }
            }
        };

        this.build = function () {
            if(this.settings.element) {
                this.$element = $(this.settings.element);
                this.element = this.$element[0];

                if(this.element) {
                    this.$element/*.empty()*/.append(this.input);
                    var pos = getComputedStyle(this.element).position;
                    if(pos === 'static') {
                        this.element.style.position = 'relative';
                    }
                    this.element.style.overflow = 'hidden';
                }
            }
        };

        this.show = function () {
            this.$element.show();
        };

        this.hide = function () {
            this.$element.hide();
        };

        this.initDropZone = function () {
            if (!!this.settings.dropZone) {
                mw.$(this.settings.dropZone).each(function () {
                    $(this).on('dragover', function (e) {
                        e.preventDefault();
                    }).on('drop', function (e) {
                        var dt = e.dataTransfer || e.originalEvent.dataTransfer;
                        e.preventDefault();
                        if (dt && dt.items) {
                            var files = [];
                            for (var i = 0; i < dt.items.length; i++) {
                                if (dt.items[i].kind === 'file') {
                                    var file = dt.items[i].getAsFile();
                                    files.push(file);
                                }
                            }
                            scope.addFiles(files);
                        } else  if (dt && dt.files)  {
                            scope.addFiles(dt.files);
                        }
                    });
                });
            }
        };


        this.init = function() {
            this.create();
            this.build();
            this.initDropZone();
        };

        this.init();

        this.removeFile = function (file) {
            var i = this.files.indexOf(file);
            if (i > -1) {
                this.files.splice(i, 1);
            }
        };

        this.uploadFile = function (file, done, chunks, _all, _i) {
            return new Promise(function (resolve, reject) {
                chunks = chunks || scope.sliceFile(file);
                _all = _all || chunks.length;
                _i = _i || 0;
                var chunk = chunks.shift();
                var data = {
                    name: file.name,
                    chunk: _i,
                    chunks: _all,
                    file: chunk,
                };
                _i++;
                $(scope).trigger('uploadStart', [data]);

                scope.upload(data, function (res) {
                    var dataProgress;
                    if(chunks.length) {
                        scope.uploadFile(file, done, chunks, _all, _i).then(function (){}, function (xhr){
                             if(scope.settings.on.fileUploadError) {
                                scope.settings.on.fileUploadError(xhr);
                            }
                        });
                        dataProgress = {
                            percent: ((100 * _i) / _all).toFixed()
                        };
                        $(scope).trigger('progress', [dataProgress, res]);
                        if(scope.settings.on.progress) {
                            scope.settings.on.progress(dataProgress, res);
                        }

                    } else {
                        dataProgress = {
                            percent: '100'
                        };
                        $(scope).trigger('progress', [dataProgress, res]);
                        if(scope.settings.on.progress) {
                            scope.settings.on.progress(dataProgress, res);
                        }
                        $(scope).trigger('FileUploaded', [res]);
                        if(scope.settings.on.fileUploaded) {
                            scope.settings.on.fileUploaded(res);
                        }
                        if (done) {
                            done.call(file, res);
                        }
                        resolve(file);
                    }
                }, function (req) {
                    if (req.responseJSON && req.responseJSON.error && req.responseJSON.error.message) {
                        mw.notification.warning(req.responseJSON.error.message);
                    }
                    scope.removeFile(file);
                    reject(req)
                });
            });
        };

        this.sliceFile = function(file) {
            var byteIndex = 0;
            var chunks = [];
            var chunksAmount = file.size <= this.settings.chunkSize ? 1 : ((file.size / this.settings.chunkSize) >> 0) + 1;

            for (var i = 0; i < chunksAmount; i ++) {
                var byteEnd = Math.ceil((file.size / chunksAmount) * (i + 1));
                chunks.push(file.slice(byteIndex, byteEnd));
                byteIndex += (byteEnd - byteIndex);
            }

            return chunks;
        };

        this.uploadFiles = function () {
            if (this.settings.async) {
                if (this.files.length) {
                    this.uploading(true);
                    var file = this.files[0]
                    scope.uploadFile(file)
                        .then(function (){
                        scope.files.shift();
                        scope.uploadFiles();
                    }, function (xhr){console.log(2, scope.settings.on.fileUploadError)
                            scope.removeFile(file);
                            if(scope.settings.on.fileUploadError) {
                                scope.settings.on.fileUploadError(xhr)
                            }
                        });

                } else {
                    this.uploading(false);
                    scope.input.value = '';
                    if(scope.settings.on.filesUploaded) {
                        scope.settings.on.filesUploaded();
                    }
                    $(scope).trigger('FilesUploaded');

                }
            } else {
                var count = 0;
                var all = this.files.length;
                this.uploading(true);
                this.files.forEach(function (file) {
                    scope.uploadFile(file)
                        .then(function (file){
                            count++;
                            scope.uploading(false);
                            if(all === count) {
                                scope.input.value = '';
                                if(scope.settings.on.filesUploaded) {
                                    scope.settings.on.filesUploaded();
                                }
                                $(scope).trigger('FilesUploaded');
                            }
                        }, function (xhr){
                            if(scope.settings.on.fileUploadError) {
                                scope.settings.on.fileUploadError(xhr)
                            }
                        });
                });
            }
        };


        this.upload = function (data, done, onFail) {
            if (!this.settings.url) {
                return;
            }
            var pdata = new FormData();
            $.each(data, function (key, val) {
                pdata.append(key, val);
            });
            if(scope.settings.on.uploadStart) {
                if (scope.settings.on.uploadStart(pdata) === false) {
                    return;
                }
            }

            var xhrOptions = {
                url: this.getUrl(),
                type: 'post',
                processData: false,
                contentType: false,
                data: pdata,
                success: function (data, statusText, xhrReq) {

                    if(xhrReq.status === 200) {
                        if (data && (data.form_data_required || data.form_data_module)) {
                            mw.extradataForm(xhrOptions, data, mw.jqxhr);
                        }
                        else {
                            scope.removeFile(data.file);
                            if(done) {
                                done.call(data, data);
                            }
                        }
                    }

                },
                error:  function(  xhrReq, edata, statusText ) {
                    scope.removeFile(data.file);
                    if (onFail) {
                        onFail.call(xhrReq, xhrReq);
                    }
                },
                dataType: 'json',
                xhr: function () {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function (event) {
                        if (event.lengthComputable) {
                            var percent = (event.loaded / event.total) * 100;
                            if(scope.settings.on.progressNative) {
                                scope.settings.on.progressNative(percent, event);
                            }
                            $(scope).trigger('progressNative', [percent, event]);
                        }
                    });
                    return xhr;
                }
            };

            return mw.jqxhr(xhrOptions);
        };
    };

    mw.upload = function (options) {
        return new Uploader(options);
    };


})();


/***/ }),

/***/ "./userfiles/modules/microweber/api/core/url.js":
/*!******************************************************!*\
  !*** ./userfiles/modules/microweber/api/core/url.js ***!
  \******************************************************/
/***/ (() => {

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
      return url.indexOf('#')!=-1 ? url.substring(url.indexOf('#'), url.length) : "";
    },
    strip:function(url){
      return url.replace(/#[^#]*$/, "").replace(/\?[^\?]*$/, "")
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
          return {}
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

      var hash = hash || mw.hash();
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
        var context = context || document.body;
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
      var images = 'jpg,png,gif,jpeg,bmp';
      if(images.contains(extension)){
        return 'image';
      }
      else if(extension=='swf'){return 'flash'}
      else if(extension=='pdf'){return 'pdf'}
      else if(url.contains('youtube.com') || url.contains('youtu.be')){return 'youtube'}
      else if(url.contains('vimeo.com')){return 'vimeo'}

      else{ return 'link'; }
    }
};

mw.slug = {
  max: 60,
  normalize:function(string){
      if(!string) return '';
      string = string.substring(0, mw.slug.max);
      return string.replace(/[`~!@#$%^&№€§*()\=?'"<>\{\}\[\]\\]/g, '');
  },
  removeSpecials:function(string){
    string = mw.slug.normalize(string);
    if(!string) return string;
    var special = 'àáäãâèéëêìíïîòóöôõùúüûñç·=,:;',
        normal =  'aaaaaeeeeiiiiooooouuuunc------',
        len = special.length,
        i = 0;
    for ( ; i<len; i++) {
       var bad = special[i];
       var good = normal[i];
       string = string.replace(new RegExp(bad, 'g'), good);
    }
    return string;
  },
  create:function(string){
    string = string || '';
    string = mw.slug.removeSpecials(string);
    return string.trim().toLowerCase().replace(/[-\s]+/g, '-');
  }
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/system/color.js":
/*!**********************************************************!*\
  !*** ./userfiles/modules/microweber/api/system/color.js ***!
  \**********************************************************/
/***/ (() => {

mw.color = {
    rgbaToHex: function (orig) {
        var a, isPercent,
            rgb = orig.replace(/\s/g, '').match(/^rgba?\((\d+),(\d+),(\d+),?([^,\s)]+)?/i),
            alpha = (rgb && rgb[4] || "").trim(),
            hex = rgb ?
                (rgb[1] | 1 << 8).toString(16).slice(1) +
                (rgb[2] | 1 << 8).toString(16).slice(1) +
                (rgb[3] | 1 << 8).toString(16).slice(1) : orig;

        if (alpha !== '') {
            a = alpha;
        } else {
            a = 01;
        }
        a = ((a * 255) | 1 << 8).toString(16).slice(1)
        hex = hex + a;

        return '#' + hex;
    },
    rgbToHex : function(color) {
        if(typeof color == 'object'){
          color = color.r + ',' + color.g + ',' + color.b;
        }
        if(color.contains('rgb')){
          color = color.replace(/rgba/g, '').replace(/rgb/g, '').replace(/\(|\)/g, "").replace(/\s/g, "");
        }
        color = color.split(',');
        if(color !== 'transparent'){
          return "#" + ((1 << 24) + (parseInt(color[0]) << 16) + (parseInt(color[1]) << 8) + parseInt(color[2])).toString(16).slice(1);
        }
        else{
          return 'transparent';
        }
    },
    rgbOrRgbaToHex: function (color) {
        if(color.indexOf('rgb(') === 0) {
            return this.rgbToHex(color)
        } else {
            return this.rgbaToHex(color)
        }
    },
    hexToRgb: function(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    },
    colorParse:function(CSScolor){
        CSScolor = CSScolor || 'rgb(0,0,0,0)';
        var final = {r:0,g:0,b:0,alpha:0};
        if(CSScolor.contains('rgb')){
          var parse = CSScolor.replace(/rgba/g, '').replace(/rgb/g, '').replace(/\(|\)/g, "").replace(/\s/g, "").split(',');
          final.r = parseInt(parse[0], 10);
          final.g = parseInt(parse[1], 10);
          final.b = parseInt(parse[2], 10);
          final.alpha = Number((parse[3]||1));
          return final;
        }
        else{
          final = mw.color.hexToRgb(CSScolor);
          final.alpha = 1
          return final;
        }
    },
    getBrightness: function(color) {
      var rgb = this.colorParse(color);
      return {
          color: (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000,
          alpha: rgb.alpha * 100
      };
    },
    isDark: function(color) {
      var brightness = this.getBrightness(color);
      return brightness.color < 128 && brightness.alpha > 22;
    },
    isLight: function(color) {
      return !this.isDark(color);
    },
    hexToRgbaCSS: function(hex, alpha) {
        alpha = alpha || 1;
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? ('rgba('+parseInt(result[1], 16)+','+parseInt(result[2], 16)+','+parseInt(result[3], 16)+','+alpha+')') : '';
    },
    random: function(){
        return '#' + Math.floor( Math.random() * 16777215 ).toString(16);
    },
    decimalToHex: function(decimal){
        var hex = decimal.toString(16);
        if (hex.length == 1) hex = '0' + hex;
        return hex;
    },
    hexToDecimal: function(hex){
        return parseInt(hex,16);
    },
    oppositeColor: function(color) {
        color = !color.contains("#") ? color : color.replace("#", '');
        return mw.color.decimalToHex(255 - mw.color.hexToDecimal(color.substr(0,2)))
          + mw.color.decimalToHex(255 - mw.color.hexToDecimal(color.substr(2,2)))
          + mw.color.decimalToHex(255 - mw.color.hexToDecimal(color.substr(4,2)));
    }
}


















/***/ }),

/***/ "./userfiles/modules/microweber/api/system/css_parser.js":
/*!***************************************************************!*\
  !*** ./userfiles/modules/microweber/api/system/css_parser.js ***!
  \***************************************************************/
/***/ (() => {





mw.CSSParser = function(el){
    if(!el || !el.nodeName) return false;
    if(el.nodeName === '#text') return false;


    try {
        var css = window.getComputedStyle(el, null);
    } catch(error) {
        return;
    }


    var f = {};

    f.display = function(){
      return css.display;
    };

    f.is = function(){
        return {
          bold: parseFloat(css.fontWeight)>600 || css.fontWeight === 'bold' || css.fontWeight === 'bolder',
          italic: css.fontStyle === 'italic'||css.fontStyle === 'oblique',
          underlined: css.textDecoration === 'underline',
          striked: css.textDecoration.indexOf('line-through') === 0,
        };
    };
    f.font = function(){
      if(css === null) return false;
      return {
        size:css.fontSize,
        weight:css.fontWeight,
        style:css.fontStyle,
        height:css.lineHeight,
        family:css.fontFamily,
        color:css.color
      };
    }
    f.alignNormalize = function(){
        if(!!css){
        var a = css.textAlign;
        var final = a.contains('left')?'left':a.contains('center')?'center':a.contains('justify')?'justify':a.contains('right')?'right':'left';
        return final;
      }
    }
    f.border = function(parse){
        if(!parse){
          return {
              top:{width:css.borderTopWidth, style:css.borderTopStyle, color:css.borderTopColor},
              left:{width:css.borderLeftWidth, style:css.borderLeftStyle, color:css.borderLeftColor},
              right:{width:css.borderRightWidth, style:css.borderRightStyle, color:css.borderRightColor},
              bottom:{width:css.borderBottomWidth, style:css.borderBottomStyle, color:css.borderBottomColor}
          }
        }
        else{
          return {
              top:{width:parseFloat(css.borderTopWidth), style:css.borderTopStyle, color:css.borderTopColor},
              left:{width:parseFloat(css.borderLeftWidth), style:css.borderLeftStyle, color:css.borderLeftColor},
              right:{width:parseFloat(css.borderRightWidth), style:css.borderRightStyle, color:css.borderRightColor},
              bottom:{width:parseFloat(css.borderBottomWidth), style:css.borderBottomStyle, color:css.borderBottomColor}
          }
        }

    }
    f.width = function(){
        return css.width;
    }
    f.position = function(){
        return css.position;
    }
    f.background = function(){
        return {
            image:css.backgroundImage,
            color:css.backgroundColor,
            position:css.backgroundPosition,
            repeat:css.backgroundRepeat
        }
    }
    f.margin = function(parse, actual){
        if(actual){
            var _parent = el.parentNode;
            var parentOff = mw.$(_parent).offset();
            var elOff = mw.$(el).offset();
            if(elOff.left > parentOff.left && css.marginLeft === css.marginRight && elOff.left - parentOff.left === parseInt(css.marginLeft, 10)){
                return {
                    top:css.marginTop,
                    left:'auto',
                    right:'auto',
                    bottom:css.marginBottom
                };
            }
      }
      if(!parse){
        return {
          top:css.marginTop,
          left:css.marginLeft,
          right:css.marginRight,
          bottom:css.marginBottom
        }
      }
      else{
        return {
          top:parseFloat(css.marginTop),
          left:parseFloat(css.marginLeft),
          right:parseFloat(css.marginRight),
          bottom:parseFloat(css.marginBottom)
        }
      }
    }
    f.padding = function(parse){
      if(!parse){
        return {
          top:css.paddingTop,
          left:css.paddingLeft,
          right:css.paddingRight,
          bottom:css.paddingBottom
        }
      }
      else{
         return {
          top:parseFloat(css.paddingTop),
          left:parseFloat(css.paddingLeft),
          right:parseFloat(css.paddingRight),
          bottom:parseFloat(css.paddingBottom)
        }
      }
    }
    f.opacity = function(){return css.opacity}

    f.radius = function(parse){
      if(!parse){
        return {
          tl:css.borderTopLeftRadius,
          tr:css.borderTopRightRadius,
          br:css.borderBottomRightRadius,
          bl:css.borderBottomLeftRadius
        }
      }
      else{
        return {
          tl:parseFloat(css.borderTopLeftRadius),
          tr:parseFloat(css.borderTopRightRadius),
          br:parseFloat(css.borderBottomRightRadius),
          bl:parseFloat(css.borderBottomLeftRadius)
        }
      }
    }

    f.transform = function(){
     var transform = mw.JSPrefix('transform');
     transform = css[transform];
     if(transform==="" || transform==="none"){
       return [1, 0, 0, 1, 0, 0];
     }
     else{
       transform = transform.substr(7, transform.length - 8).split(", ");
       return transform;
     }
    }

    f.shadow = function(){
      var shadow =  mw.JSPrefix('boxShadow');
      shadow = css[shadow].replace(/, /g, ",").split(" ");
      return {
        color: shadow[0],
        left:shadow[1],
        top:shadow[2],
        strength:shadow[3]
      }
    }

    return {
        el:el,
        css:css,
        get:f
    }
}




/***/ }),

/***/ "./userfiles/modules/microweber/api/system/filepicker.js":
/*!***************************************************************!*\
  !*** ./userfiles/modules/microweber/api/system/filepicker.js ***!
  \***************************************************************/
/***/ (() => {




mw.filePicker = function (options) {
    options = options || {};
    var scope = this;
    var defaults = {
        components: [
            {type: 'desktop', label: mw.lang('My computer')},
            {type: 'url', label: mw.lang('URL')},
            {type: 'server', label: mw.lang('Uploaded')},
            {type: 'library', label: mw.lang('Media library')}
        ],
        nav: 'tabs', // 'tabs | 'dropdown',
        hideHeader: false,
        dropDownTargetMode: 'self', // 'self', 'dialog'
        element: null,
        footer: true,
        okLabel: mw.lang('OK'),
        cancelLabel: mw.lang('Cancel'),
        uploaderType: 'big', // 'big' | 'small'
        confirm: function (data) {

        },
        cancel: function () {

        },
        label: mw.lang('Media'),
        autoSelect: true, // depending on the component
        boxed: false,
        multiple: false
    };



    this.settings = $.extend(true, {}, defaults, options);

    this.$root = $('<div class="'+ (this.settings.boxed ? ('card mb-3') : '') +' mw-filepicker-root"></div>');
    this.root = this.$root[0];

    $.each(this.settings.components, function (i) {
        this['index'] = i;
    });


    this.components = {
        _$inputWrapper: function (label) {
            var html = '<div class="mw-ui-field-holder">' +
                /*'<label>' + label + '</label>' +*/
                '</div>';
            return mw.$(html);
        },
        url: function () {
            var $input = $('<input class="mw-ui-field w100" placeholder="http://example.com/image.jpg">');
            scope.$urlInput = $input;
            var $wrap = this._$inputWrapper(scope._getComponentObject('url').label);
            $wrap.append($input);
            $input.before('<label class="mw-ui-label">'+mw.lang('Insert file url')+'</label>');
            $input.on('input', function () {
                var val = this.value.trim();
                scope.setSectionValue(val || null);
                if(scope.settings.autoSelect) {

                    scope.result();
                }
            });
            return $wrap[0];
        },
        _setdesktopType: function () {
            var $zone;
            if(scope.settings.uploaderType === 'big') {
                $zone = $('<div class="mw-file-drop-zone">' +
                    '<div class="mw-file-drop-zone-holder">' +
                    '<div class="mw-file-drop-zone-img"></div>' +
                    '<div class="mw-ui-progress-small"><div class="mw-ui-progress-bar" style="width: 0%"></div></div>' +
                    '<span class="mw-ui-btn mw-ui-btn-rounded mw-ui-btn-info">'+mw.lang('Add file')+'</span> ' +
                    '<p>'+mw.lang('or drop files to upload')+'</p>' +
                    '</div>' +
                    '</div>');
            } else if(scope.settings.uploaderType === 'small') {
                $zone = $('<div class="mw-file-drop-zone mw-file-drop-zone-small mw-file-drop-square-zone"> <div class="mw-file-drop-zone-holder"> <span class="mw-ui-link">'+mw.lang('Add file')+'</span> ' +
                    '<p>'+mw.lang('or drop files to upload')+'</p>' +
                    '</div>' +
                    '</div>')
            }
            var $el = $(scope.settings.element).eq(0);
            $el.removeClass('mw-filepicker-desktop-type-big mw-filepicker-desktop-type-small');
            $el.addClass('mw-filepicker-desktop-type-' + scope.settings.uploaderType);
            scope.uploaderHolder.empty().append($zone);
        },
        desktop: function () {
            var $wrap = this._$inputWrapper(scope._getComponentObject('desktop').label);
            scope.uploaderHolder = mw.$('<div class="mw-uploader-type-holder"></div>');
            this._setdesktopType();
            $wrap.append(scope.uploaderHolder);
            scope.uploader = mw.upload({
                element: $wrap[0],
                multiple: scope.settings.multiple,
                accept: scope.settings.accept,
                on: {
                    progress: function (prg) {
                        scope.uploaderHolder.find('.mw-ui-progress-bar').stop().animate({width: prg.percent + '%'}, 'fast');
                    },
                    fileUploadError: function (file) {
                        $(scope).trigger('FileUploadError', [file]);
                    },
                    fileAdded: function (file) {
                        $(scope).trigger('FileAdded', [file]);
                        scope.uploaderHolder.find('.mw-ui-progress-bar').width('1%');
                    },
                    fileUploaded: function (file) {
                        scope.setSectionValue(file);

                        $(scope).trigger('FileUploaded', [file]);
                        if (scope.settings.autoSelect) {
                            scope.result();
                        }
                        if (scope.settings.fileUploaded) {
                            scope.settings.fileUploaded(file);
                        }
                        if (!scope.settings.multiple) {
                            mw.notification.success('File uploaded');
                            scope.uploaderHolder.find('.mw-file-drop-zone-img').css('backgroundImage', 'url('+file.src+')');
                        }
                        // scope.uploaderHolder.find('.mw-file-drop-zone-img').css('backgroundImage', 'url('+file.src+')');
                    }
                }
            });
            return $wrap[0];
        },
        server: function () {
            var $wrap = this._$inputWrapper(scope._getComponentObject('server').label);
            /*mw.load_module('files/admin', $wrap, function () {

            }, {'filetype':'images'});*/

            $(scope).on('$firstOpen', function (e, el, type) {
                var comp = scope._getComponentObject('server');
                if (type === 'server') {
                    mw.top().tools.loading(el, true);
                    var fr = document.createElement('iframe');
                    fr.src =  mw.external_tool('module_dialog') + '?module=files/admin';
                    mw.tools.iframeAutoHeight(fr);
                    fr.style.width = '100%';
                    fr.scrolling = 'no';
                    fr.frameBorder = '0';
                    if(scope.settings._frameMaxHeight) {
                        fr.style.maxHeight = '60vh';
                        fr.scrolling = 'yes';
                    }
                    fr.scrolling = 'auto';

                    $wrap.append(fr);
                    fr.onload = function () {
                        mw.tools.loading(el, false);
                        this.contentWindow.document.body.classList.remove('mw-external-loading');
                        this.contentWindow.$(this.contentWindow.document.body).on('click', '.mw-browser-list-file', function () {
                            var url = this.href;
                            scope.setSectionValue(url);
                            if (scope.settings.autoSelect) {
                                scope.result();
                            }
                        });
                    };
                }
            });

            return $wrap[0];
        },
        library: function () {
            var $wrap = this._$inputWrapper(scope._getComponentObject('library').label);
            $(scope).on('$firstOpen', function (e, el, type) {
                var comp = scope._getComponentObject('library');
                if (type === 'library') {
                    mw.tools.loading(el, true);
                    var fr = mw.top().tools.moduleFrame('pictures/media_library');
                    $wrap.append(fr);
                    if(scope.settings._frameMaxHeight) {
                        fr.style.maxHeight = '60vh';
                        fr.scrolling = 'yes';
                    }
                    fr.onload = function () {
                        mw.tools.loading(el, false);
                        this.contentWindow.mw.on.hashParam('select-file', function (pval) {
                            var url = pval.toString();
                            scope.setSectionValue(url);
                            if (scope.settings.autoSelect) {
                                scope.result();
                            }
                        });
                    };
                }
            })

            /*mw.load_module('pictures/media_library', $wrap);*/
            return $wrap[0];
        }
    };

    this.hideUploaders = function (type) {
        mw.$('.mw-filepicker-component-section', this.$root).hide();
    };

    this.showUploaders = function (type) {
        mw.$('.mw-filepicker-component-section', this.$root).show();
    };

    this.desktopUploaderType = function (type) {
        if(!type) return this.settings.uploaderType;
        this.settings.uploaderType = type;
        this.components._setdesktopType();
    };

    this.settings.components = this.settings.components.filter(function (item) {
        return !!scope.components[item.type];
    });


    this._navigation = null;
    this.__navigation_first = [];

    this.navigation = function () {
        this._navigationHeader = document.createElement('div');
        this._navigationHeader.className = 'mw-filepicker-component-navigation-header ' + (this.settings.boxed ? 'card-header no-border' : '');
        if (this.settings.hideHeader) {
            this._navigationHeader.style.display = 'none';
        }
        if (this.settings.label) {
            this._navigationHeader.innerHTML = '<h6><strong>' + this.settings.label + '</strong></h6>';
        }
        this._navigationHolder = document.createElement('div');
        if(this.settings.nav === false) {

        }
        else if(this.settings.nav === 'tabs') {
            var ul = $('<nav class="mw-ac-editor-nav" />');
            this.settings.components.forEach(function (item) {
                ul.append('<a href="javascript:;" class="mw-ui-btn-tab" data-type="'+item.type+'">'+item.label+'</a>');
            });
            this._navigationHolder.appendChild(this._navigationHeader);
            this._navigationHeader.appendChild(ul[0]);
            setTimeout(function () {
                scope._tabs = mw.tabs({
                    nav: $('a', ul),
                    tabs: $('.mw-filepicker-component-section', scope.$root),
                    activeClass: 'active',
                    onclick: function (el, event, i) {
                        if(scope.__navigation_first.indexOf(i) === -1) {
                            scope.__navigation_first.push(i);
                            $(scope).trigger('$firstOpen', [el, this.dataset.type]);
                        }
                        scope.manageActiveSectionState();
                    }
                });
            }, 78);
        } else if(this.settings.nav === 'dropdown') {
            var select = $('<select class="selectpicker btn-as-link" data-style="btn-sm" data-width="auto" data-title="' + mw.lang('Add file') + '"/>');
            scope._select = select;
            this.settings.components.forEach(function (item) {
                select.append('<option class="nav-item" value="'+item.type+'">'+item.label+'</option>');
            });

            this._navigationHolder.appendChild(this._navigationHeader);
            this._navigationHeader.appendChild(select[0]);
            select.on('changed.bs.select', function (e, xval) {
                var val = select.selectpicker('val');
                var componentObject = scope._getComponentObject(val) ;
                var index = scope.settings.components.indexOf(componentObject);
                var items = $('.mw-filepicker-component-section', scope.$root);
                if(scope.__navigation_first.indexOf(val) === -1) {
                    scope.__navigation_first.push(val);
                    $(scope).trigger('$firstOpen', [items.eq(index)[0], val]);
                }
                if(scope.settings.dropDownTargetMode === 'dialog') {
                    var temp = document.createElement('div');
                    var item = items.eq(index);
                    item.before(temp);
                    item.show();
                    var footer = false;
                    if (scope._getComponentObject('url').index === index ) {
                        footer =  document.createElement('div');
                        var footerok = $('<button type="button" class="mw-ui-btn mw-ui-btn-info">' + scope.settings.okLabel + '</button>');
                        var footercancel = $('<button type="button" class="mw-ui-btn">' + scope.settings.cancelLabel + '</button>');
                        footerok.disabled = true;
                        footer.appendChild(footercancel[0]);
                        footer.appendChild(footerok[0]);
                        footer.appendChild(footercancel[0]);
                        footercancel.on('click', function () {
                            scope.__pickDialog.remove();
                        });
                        footerok.on('click', function () {
                            scope.setSectionValue(scope.$urlInput.val().trim() || null);
                            if (scope.settings.autoSelect) {
                                scope.result();
                            }
                            // scope.__pickDialog.remove();
                        });
                    }

                    scope.__pickDialog = mw.top().dialog({
                        overlay: true,
                        content: item,
                        beforeRemove: function () {
                            $(temp).replaceWith(item);
                            item.hide();
                            scope.__pickDialog = null;
                        },
                        footer: footer
                    });
                } else {
                    items.hide().eq(index).show();
                }
            });
        }
        this.$root.prepend(this._navigationHolder);

    };
    this.__displayControllerByTypeTime = null;

    this.displayControllerByType = function (type) {
        type = (type || '').trim();
        var item = this._getComponentObject(type) ;
        clearTimeout(this.__displayControllerByTypeTime);
        this.__displayControllerByTypeTime = setTimeout(function () {
            if(scope.settings.nav === 'tabs') {
                mw.$('[data-type="'+type+'"]', scope.$root).click();
            } else if(scope.settings.nav === 'dropdown') {
                $(scope._select).selectpicker('val', type);
            }
        }, 10);
    };



    this.footer = function () {
        if(!this.settings.footer || this.settings.autoSelect) return;
        this._navigationFooter = document.createElement('div');
        this._navigationFooter.className = 'mw-ui-form-controllers-footer mw-filepicker-footer ' + (this.settings.boxed ? 'card-footer' : '');
        this.$ok = $('<button type="button" class="mw-ui-btn mw-ui-btn-info">' + this.settings.okLabel + '</button>');
        this.$cancel = $('<button type="button" class="mw-ui-btn ">' + this.settings.cancelLabel + '</button>');
        this._navigationFooter.appendChild(this.$cancel[0]);
        this._navigationFooter.appendChild(this.$ok[0]);
        this.$root.append(this._navigationFooter);
        this.$ok[0].disabled = true;
        this.$ok.on('click', function () {
            scope.result();
        });
        this.$cancel.on('click', function () {
            scope.settings.cancel()
        });
    };

    this.result = function () {
        var activeSection = this.activeSection();
        if(this.settings.onResult) {
            this.settings.onResult.call(this, activeSection._filePickerValue);
        }
        $(scope).trigger('Result', [activeSection._filePickerValue]);
    };

    this.getValue = function () {
        return this.activeSection()._filePickerValue;
    };

    this._getComponentObject = function (type) {
        return this.settings.components.find(function (comp) {
            return comp.type && comp.type === type;
        });
    };

    this._sections = [];
    this.buildComponentSection = function () {
        var main = mw.$('<div class="'+(this.settings.boxed ? 'card-body' : '') +' mw-filepicker-component-section"></div>');
        this.$root.append(main);
        this._sections.push(main[0]);
        return main;
    };

    this.buildComponent = function (component) {
        if(this.components[component.type]) {
            return this.components[component.type]();
        }
    };

    this.buildComponents = function () {
        $.each(this.settings.components, function () {
            var component = scope.buildComponent(this);
            if(component){
                var sec = scope.buildComponentSection();
                sec.append(component);
            }
        });
    };

    this.build = function () {
        this.navigation();
        this.buildComponents();
        if(this.settings.nav === 'dropdown') {
            $('.mw-filepicker-component-section', scope.$root).hide().eq(0).show();
        }
        this.footer();
    };

    this.init = function () {
        this.build();
        if (this.settings.element) {
            $(this.settings.element).eq(0).append(this.$root);
        }
        if($.fn.selectpicker) {
            $('select', scope.$root).selectpicker();
        }
    };

    this.hide = function () {
        this.$root.hide();
    };
    this.show = function () {
        this.$root.show();
    };

    this.activeSection = function () {
        return $(this._sections).filter(function (){
            return $(this).css('display') !== 'none';
        })[0];
    };

    this.setSectionValue = function (val) {
        var activeSection = this.activeSection();
        if(activeSection) {
            activeSection._filePickerValue = val;
        }

        if(scope.__pickDialog) {
            scope.__pickDialog.remove();
        }
        this.manageActiveSectionState();
    };
    this.manageActiveSectionState = function () {
        // if user provides value for more than one section, the active value will be the one in the current section
        var activeSection = this.activeSection();
        if (this.$ok && this.$ok[0]) {
            this.$ok[0].disabled = !(activeSection && activeSection._filePickerValue);
        }
    };

    this.init();
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/system/icon_selector.js":
/*!******************************************************************!*\
  !*** ./userfiles/modules/microweber/api/system/icon_selector.js ***!
  \******************************************************************/
/***/ (() => {

(function () {

    var IconLoader = function (store) {
        var scope = this;

        var defaultVersion = '-1';

        var common = {
            'fontAwesome': {
                cssSelector: '.fa',
                detect: function (target) {
                    return target.classList.contains('fa');
                },
                render: function (icon, target) {
                    target.classList.add('fa');
                    target.classList.add(icon);
                },
                remove: function (target) {
                    target.classList.remove('fa');
                    var exception= ['fa-lg', 'fa-2x', 'fa-3x', 'fa-4x', 'fa-5x', 'fa-fw', 'fa-spin', 'fa-pule', 'fa-rotate-90',
                        'fa-rotate-180', 'fa-rotate-270', 'fa-flip-horizontal', 'fa-flip-vertical'];
                    mw.tools.classNamespaceDelete(target, 'fa-', undefined, undefined, exception);
                },
                icons: function () {
                     return new Promise(function (resolve) {
                         $.get(mw.settings.modules_url + 'microweber/api/icons-sets/fa.icons.js',function (data) {
                             resolve(JSON.parse(data));
                        });
                    });
                },
                name: 'Font Awesome',
                load:  mw.settings.libs_url + 'fontawesome-4.7.0' + '/css/font-awesome.min.css',
                unload: function () {
                    document.querySelector('link[href*="fontawesome-4.7.0"]').remove();
                },
                version: '4.7.0'
            },
            'materialIcons': {
                cssSelector: '.material-icons',
                detect: function (target) {
                    return target.classList.contains('material-icons');
                },
                render: function (icon, target) {
                    target.classList.add('material-icons');
                    target.innerHTML = (icon);
                },
                remove: function (target) {
                    mw.tools.removeClass(target, 'material-icons');
                    target.innerHTML = '';
                 },
                icons: function () {
                    return new Promise(function (resolve) {
                        $.get(mw.settings.modules_url + 'microweber/api/icons-sets/material.icons.js',function (data) {
                            resolve(JSON.parse(data));
                        });
                    });
                },
                name: 'Material Icons',
                load: mw.settings.libs_url + 'material_icons' + '/material_icons.css',
                unload: function () {
                    top.document.querySelector('link[href*="material_icons.css"]').remove();
                },
                version: 'mw'
            },
            'iconsMindLine': {
                cssSelector: '[class*="mw-micon-"]:not([class*="mw-micon-solid-"])',
                detect: function (target) {
                    return target.className.includes('mw-micon-') && !target.className.includes('mw-micon-solid-');
                },
                render: function (icon, target) {
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mw-micon-', undefined, undefined, []);
                },
                icons: function () {
                    var scope = this;
                    var parse = function (cssLink) {
                        if(!cssLink.sheet){
                            return;
                        }
                        var icons = cssLink.sheet.cssRules;
                         var l = icons.length, i = 0, mindIcons = [];
                         for (; i < l; i++) {
                            var sel = icons[i].selectorText;
                            if (!!sel && sel.indexOf('.mw-micon-') === 0) {
                                var cls = sel.replace(".", '').split(':')[0];
                                mindIcons.push(cls);
                            }
                        }
                        return mindIcons
                    };
                    var load = function (cb) {
                        var cssLink = mw.top().win.document.querySelector('link[href*="mw-icons-mind/line"]');
                        if(cssLink) {
                            cb.call(undefined, cssLink);
                        }  else {
                            $.get(scope.load, function (data) {
                                cssLink = document.createElement('link');
                                cssLink.type = 'text/css';
                                cssLink.rel = 'stylesheet';
                                cssLink.href = scope.load;
                                $(document.head).append(cssLink);
                                cb.call(undefined, cssLink);
                            });
                        }
                    };
                    return new Promise(function (resolve) {
                        load(function (link) {
                            resolve(parse(link));
                        });
                    });
                },
                name: 'Icons Mind Line',
                load:  mw.settings.modules_url + 'microweber/api/libs/mw-icons-mind/line/style.css',
                unload: function () {
                    document.querySelector('link[href*="mw-icons-mind/line/style"]').remove();
                },
                version: 'mw_local'
            },
            'iconsMindSolid': {
                cssSelector: '[class*="mw-micon-solid-"]',
                detect: function (target) {
                    return target.className.includes('mw-micon-solid-');
                },
                render: function (icon, target) {
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mw-micon-solid-', undefined, undefined, []);
                },
                icons: function () {
                    var scope = this;
                    var parse = function (cssLink) {
                        var icons = cssLink.sheet.cssRules;
                        var l = icons.length, i = 0, mindIcons = [];
                        for (; i < l; i++) {
                            var sel = icons[i].selectorText;
                            if (!!sel && sel.indexOf('.mw-micon-solid-') === 0) {
                                var cls = sel.replace(".", '').split(':')[0];
                                mindIcons.push(cls);
                            }
                        }
                        return mindIcons
                    };
                    var load = function (cb) {
                        var cssLink = mw.top().win.document.querySelector('link[href*="mw-icons-mind/solid"]');
                         if(cssLink) {
                            cb.call(undefined, cssLink);
                        }  else {
                            $.get(scope.load, function (data) {
                                cssLink = document.createElement('link');
                                cssLink.type = 'text/css';
                                cssLink.rel = 'stylesheet';
                                cssLink.href = scope.load;
                                $(document.head).append(cssLink);
                                cb.call(undefined, cssLink);
                            });
                        }
                    };
                    return new Promise(function (resolve) {
                        load(function (link) {
                            resolve(parse(link));
                        });
                    });
                },
                name: 'Icons Mind Solid',
                load:  mw.settings.modules_url + 'microweber/api/libs/mw-icons-mind/solid/style.css',
                unload: function () {
                    document.querySelector('link[href*="mw-icons-mind/solid/style"]').remove();
                },
                version: 'mw_local'
            },

            'materialDesignIcons': {
                cssSelector: '.mdi',
                detect: function (target) {
                    return target.classList.contains('mdi');
                },
                render: function (icon, target) {
                    target.classList.add('mdi');
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mdi-', undefined, undefined, []);
                    target.classList.remove('mdi');
                },
                icons: function () {
                    var scope = this;
                    var load = function (cb) {
                        var cssLink = mw.top().win.document.querySelector('link[href*="materialdesignicons"]');
                        if(cssLink) {
                            cb.call(undefined, cssLink);
                        }  else {
                            $.get(scope.load, function (data) {
                                cssLink = document.createElement('link');
                                cssLink.type = 'text/css';
                                cssLink.rel = 'stylesheet';
                                cssLink.href = scope.load;
                                $(document.head).append(cssLink);
                                cb.call(undefined, cssLink);
                            });
                        }
                    };
                    return new Promise(function (resolve) {
                        load(function (link){
                            if(!link || !link.sheet) {
                                resolve([]);
                                return;
                            }
                            var icons = link.sheet.cssRules;
                            var l = icons.length, i = 0, mdiIcons = [];
                            for (; i < l; i++) {
                                var sel = icons[i].selectorText;
                                if (!!sel && sel.indexOf('.mdi-') === 0) {
                                    var cls = sel.replace(".", '').split(':')[0];
                                    mdiIcons.push(cls);
                                }
                            }
                            resolve(mdiIcons);
                        });
                    });
                },
                name: 'Material Design Icons',
                load:  mw.settings.modules_url + 'microweber/css/fonts/materialdesignicons/css/materialdesignicons.min.css',
                unload: function () {
                    document.querySelector('link[href*="materialdesignicons"]').remove();
                },
                version: 'mw_local'
            },
            'mwIcons': {
                cssSelector: '[class*="mw-icon-"]',
                detect: function (target) {
                    return target.className.includes('mw-icon-');
                },
                render: function (icon, target) {
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mw-icon-', undefined, undefined, []);
                },
                icons: function () {
                    return new Promise(function (resolve) {
                        $.get(mw.settings.modules_url + 'microweber/api/icons-sets/microweber.icons.js',function (data) {
                            resolve(JSON.parse(data));
                        });
                    });
                },
                name: 'Microweber Icons',
                load:  mw.settings.modules_url + 'microweber/css/fonts/materialdesignicons/css/materialdesignicons.min.css',
                unload: function () {
                    document.querySelector('link[href*="materialdesignicons"]').remove();
                },
                version: 'mw_local'
            },
        };

        var storage = function () {
            if(!mw.top().__IconStorage) {
                mw.top().__IconStorage = [];
            }
            return mw.top().__IconStorage;
        };

        this.storage = store || storage;


        var iconSetKey = function (options) {
            return options.name + options.version;
        };

        var iconSetPush = function (options) {
            if(!storage().find(function (a) {return iconSetKey(options) === iconSetKey(a); })) {
                return storage().push(options);
            }
            return false;
        };

        var addFontIconSet = function (options) {
            options.version = options.version || defaultVersion;
            iconSetPush(options);

            if (typeof options.load === 'string') {
                mw.require(options.load);
            } else if (typeof options.load === 'function') {
                options.load();
            }
        };
        var addIconSet = function (conf) {

            if(typeof conf === 'string') {
                if (common[conf]) {
                    conf = common[conf];
                } else {
                    console.warn(conf + ' is not defined.');
                    return;
                }
            }
             if(!conf) return;
            conf.type = conf.type || 'font';
            if (conf.type === 'font') {
                return addFontIconSet(conf);
            }
        };

        this.addIconSet = function (conf) {
            addIconSet(conf);
            return this;
        };

        this.removeIconSet = function (name, version) {
            var str = storage();
            var item = str.find(function (a) { return a.name === name && (!version || a.version === version); });
            if (item) {
                if (item.unload) {
                    item.unload();
                }
                str.splice(str.indexOf(item), 1);
            }
        };


        this.init = function () {
            storage().forEach(function (iconSet){
                scope.addIconSet(iconSet);
            });
        };

    };

    mw.iconLoader = function (options) {
        return new IconLoader(options);
    };


})();


(function (){

    var IconPicker = function (options) {
        options = options || {};
        var loader = mw.iconLoader();
        var defaults = {
            iconsPerPage: 40,
            iconOptions: {
                size: true,
                color: true,
                reset: false
            }
        };


        this.settings = mw.object.extend(true, {}, defaults, options);
        var scope = this;
        var tabAccordionBuilder = function (items) {
            var res = {root: mw.element('<div class="mw-tab-accordion" data-options="tabsSize: medium" />'), items: []};
            items.forEach(function (item){
                var el = mw.element('<div class="mw-accordion-item" />');
                var content = mw.element('<div class="mw-accordion-content mw-ui-box mw-ui-box-content">' +(item.content || '') +'</div>');
                var title = mw.element('<div class="mw-ui-box-header mw-accordion-title">' + item.title +'</div>');
                el.append(title);
                el.append(content);

                res.root.append(el);
                res.items.push({
                    title: title,
                    content: content,
                    root: el,
                });
            });
            setTimeout(function (){
                if(mw.components) {
                    mw.components._init();
                }
            }, 10);
            return res;
        };

        var createUI = function () {
            var root = mw.element({
                props: { className: 'mw-icon-selector-root' }
            });
            var iconsBlockHolder, tabs, optionsHolder, iconsHolder;
            if(scope.settings.iconOptions) {
                tabs = tabAccordionBuilder([
                    {title: 'Icons'},
                    {title: 'Options'},
                ]);
                iconsBlockHolder = tabs.items[0].content;
                optionsHolder = tabs.items[1].content;
                root.append(tabs.root)
            } else {
                iconsBlockHolder = mw.element();
                root.append(iconsBlockHolder);
            }
            iconsHolder = mw.element().addClass('mw-icon-picker-icons-holder');
            iconsBlockHolder.append(iconsHolder);
            return {
                root: root,
                tabs: tabs,
                iconsBlockHolder: iconsBlockHolder,
                iconsHolder: iconsHolder,
                optionsHolder: optionsHolder
            };
        };


        var _e = {};

        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

        var actionNodes = {};

        var createOptions = function (holder) {

            if(holder && scope.settings.iconOptions) {
                if(scope.settings.iconOptions.size) {
                    var label = mw.element('<div class="mw-icon-selector-flex"> <label class="mw-icon-selector-control-label mw-icon-selector-6-column">Icon size in px</label> <label class="mw-icon-selector-control-label mw-icon-selector-6-column ps-2">Select size from range</label> </div>');
                    var sizeel = mw.element('<div class="mwiconlist-settings-section-block-item mw-icon-selector-flex  mw-icon-selector-12-column"></div>');
                    var sizeinput = mw.element('<input class="mw-icon-selector-form-control mw-icon-selector-6-column" type="number" min="8" max="200">');
                    var sizeinput2 = mw.element('<input class="mw-icon-selector-form-control mw-icon-selector-6-column" type="range" min="8" max="200">');

                    actionNodes.size = sizeinput;
                    sizeinput.on('input', function () {
                        scope.dispatch('sizeChange', sizeinput.get(0).value);
                        sizeinput2.val(sizeinput.get(0).value);
                    });
                    sizeinput2.on('input', function () {
                        sizeinput.val(sizeinput2.get(0).value);
                        scope.dispatch('sizeChange', sizeinput.get(0).value);
                    });

                    holder.append(label);
                    sizeel.append(sizeinput);
                    sizeel.append(sizeinput2);
                    holder.append(sizeel);
                }
                if(scope.settings.iconOptions.color) {
                    cel = mw.element('<div class="mwiconlist-settings-section-block-item"><label class="mw-icon-selector-control-label  ps-2">Choose color</label></div>');
                    cinput = mw.element('<input class="mw-icon-selector-form-control mw-icon-selector-2-column" type="color">');
                    actionNodes.color = cinput;
                    cinput.on('input', function () {
                        scope.dispatch('colorChange', cinput.get(0).value);
                    });
                    cel.append(cinput);
                    holder.append(cel);
                }
                if(scope.settings.iconOptions.reset) {
                    var rel = mw.element('<div class="mwiconlist-settings-section-block-item"> </div>');
                    var rinput = mw.element('<input type="button" class="mw-ui-btn" value="Reset options">');
                    rinput.on('click', function () {
                        scope.dispatch('reset', rinput.get(0).value);
                    });
                    rel.append(rinput);
                    holder.append(rel);
                }
            }
        };

        var _prepareIconsLists = function (c) {
            var sets = loader.storage();
            var all = sets.length;
            var i = 0;
             sets.forEach(function (set){
                 if (!set._iconsLists) {
                     (function (aset){
                         aset.icons().then(function (data){
                             aset._iconsLists = data;
                             i++;
                             if(i === all) c.call(sets, sets);
                         });
                     })(set);
                 } else {
                     i++;
                     if(i === all) c.call(sets, sets);
                 }

            });
        };


        var createPaging = function(length, page){
            page = page || 1;
            var max = 999;
            var pages = Math.min(Math.ceil(length/scope.settings.iconsPerPage), max);
            var paging = document.createElement('div');
            paging.className = 'mw-paging mw-paging-small mw-icon-selector-paging';
            if(scope.settings.iconsPerPage >= length ) {
                return paging;
            }
            var active = false;
            for ( var i = 1; i <= pages; i++) {
                var el = document.createElement('a');
                el.innerHTML = i;
                el._value = i;
                if(page === i) {
                    el.className = 'active';
                    active = i;
                }
                el.onclick = function () {
                    comRender({page: this._value });
                };
                paging.appendChild(el);
            }
            var all = paging.querySelectorAll('a');
            for (var i = active - 3; i < active + 2; i++){
                if(all[i]) {
                    all[i].className += ' mw-paging-visible-range';
                }
            }


            if(active < pages) {
                var next = document.createElement('a');
                next.innerHTML = '&raquo;';
                next._value = active+1;
                next.className = 'mw-paging-visible-range mw-paging-next';
                next.innerHTML = '&raquo;';
                $(paging).append(next);
                next.onclick = function () {
                     comRender({page: this._value });
                }
            }
            if(active > 1) {
                var prev = document.createElement('a');
                prev.className = 'mw-paging-visible-range mw-paging-prev';
                prev.innerHTML = '&laquo;';
                prev._value = active-1;
                $(paging).prepend(prev);
                prev.onclick = function () {
                     comRender({page: this._value });
                };
            }

            return paging;
        };

        var searchField = function () {
            var time = null;
            scope.searchField =  mw.element({
                tag: 'input',
                props: {
                    className: 'mw-ui-searchfield w100',
                    placeholder: 'Search',
                    oninput: function () {
                        clearTimeout(time);
                        time = setTimeout(function (){
                            comRender();
                        }, 123);
                    }
                }
            });

            return scope.searchField;
        };

        var comRender = function (options) {
            options = options || {};
            options = mw.object.extend({}, {
                set: scope.selectField.get(0).options[scope.selectField.get(0).selectedIndex]._value,
                term: scope.searchField.get(0).value
            }, options);
            scope.ui.iconsHolder.empty().append(renderSearchResults(options));
        };

        var searchSelector = function () {
            var sel = mw.element('<select class="mw-ui-field w100" />');
            scope.selectField = sel;
            loader.storage().forEach(function (item) {
                var el = document.createElement('option');
                el._value = item;
                el.innerHTML = item.name;
                sel.append(el);
            });
            sel.on('change', function (){
                comRender()
            });
            return sel;
        };

        var search = function (conf) {
            conf = conf || {};
            conf.set = conf.set ||  loader.storage()[0];
            conf.page = conf.page || 1;
            conf.term = (conf.term || '').trim().toLowerCase();

            if (!conf.set._iconsLists) {
                return;
            }

            var all = conf.set._iconsLists.filter(function (f){ return f.toLowerCase().indexOf(conf.term) !== -1; });

            var off = scope.settings.iconsPerPage * (conf.page - 1);
            var to = off + Math.min(all.length - off, scope.settings.iconsPerPage);

            return mw.object.extend({}, conf, {
                data: all.slice(off, to),
                all: all,
                off: off
            });
            /*for ( var i = off; i < to; i++ ) {

            }*/
        };

        var renderSearchResults = function (conf) {
            var res = search(conf);
            if(!res) return;
            var pg = createPaging(res.all.length, res.page);
            var root = mw.element();
            res.data.forEach(function (iconItem){
                var icon = mw.element({
                    tag: 'span',
                    props: {
                        className: 'mwiconlist-icon',
                        onclick: function (e) {
                            scope.dispatch('select', {
                                icon: iconItem,
                                renderer: res.set.render,
                                render: function () {
                                    var sets = loader.storage();
                                    sets.forEach(function (set) {
                                        set.remove(scope.target)
                                    })
                                    return res.set.render(iconItem, scope.target);
                                }
                            });
                        }
                    }
                });
                root.append(icon);
                res.set.render(iconItem, icon.get(0));
            });
            root.append(pg)
            return root;
        };

        var createIconsBlock = function () {
            mw.spinner({element: scope.ui.iconsHolder.get(0), size: 30}).show();
            _prepareIconsLists(function (){
                comRender();
                mw.spinner({element: scope.ui.iconsHolder.get(0)}).hide();
            });
        };

        this.create = function () {
            this.ui = createUI();
            createOptions(this.ui.optionsHolder);
            this.ui.iconsBlockHolder.prepend(searchField());

            this.ui.iconsBlockHolder.prepend(searchSelector());
            createIconsBlock();

        };

        this.get = function () {
            return this.ui.root.get(0);
        };

        this.dialog = function (method) {
            if(method === 'hide') {
                this._dialog.hide();
                return;
            }
            if(!this._dialog) {
                this._dialog = mw.top().dialog({content: this.get(), title: 'Select icon', closeButtonAction: 'hide', width: 450});
                mw.components._init();
            }
            this._dialog.show();
            return this._dialog;
        };

        this.destroy = function () {
            this.get().remove()
            if(this._dialog) {
                this._dialog.remove();
            }
            if(this._tooltip) {
                this._tooltip.remove();
            }
        };

        this.target = null;

        this.tooltip = function (target) {
            this.target = target;
            if(target === 'hide' && this._tooltip) {
                this._tooltip.style.display = 'none';
            } else {
                if (!this._tooltip) {
                    this._tooltip = mw.tooltip({
                        content: this.get(),
                        element: target,
                        position: 'bottom-center',
                    });
                } else {
                    mw.tools.tooltip.setPosition(this._tooltip, target, 'bottom-center');
                }

                this._tooltip.style.display = 'block';
                if(target.nodeType === 1) {
                    var css = getComputedStyle(target);
                    $('[type="number"],[type="range"]', this._tooltip).val(parseFloat(css.fontSize));

                    $('[type="color"]', this._tooltip).val(mw.color.rgbOrRgbaToHex(css.color));
                }

            }

            mw.components._init();
            return this._tooltip;
        };

        this.init = function () {
            this.create();
        };

        this.promise = function () {
            return new Promise(function (resolve){
               scope.on('select', function (data) {
                   resolve(data);
               });
            });
        };

        this.init();

    };


    mw.iconPicker = function (options) {
        return new IconPicker(options);
    };

})();








/***/ }),

/***/ "./userfiles/modules/microweber/api/system/module_settings.js":
/*!********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/system/module_settings.js ***!
  \********************************************************************/
/***/ (() => {

mw.moduleSettings = function(options){
    /*
        options:

            data: [Object],
            element: NodeElement || selector string || jQuery array,
            schema: mw.propEditor.schema,
            key: String
            group: String,
            autoSave: Boolean

    */

    var defaults = {
        sortable: true,
        autoSave: true
    };

    if(!options.schema || !options.data || !options.element) return;

    this.options = $.extend({}, defaults, options);

    this.options.element = mw.$(this.options.element)[0];
    this.value = this.options.data.slice();

    var scope = this;

    this.items = [];

    if(!this.options.element) return;

    this.interval = function (c) {
        if(!this._interval) {
            this._intervals = [];
            this._interval = setInterval(function () {
                if(scope.options.element && document.body.contains(scope.options.element)) {
                    scope._intervals.forEach(function (func){
                        func.call(scope)
                    })
                } else {
                    clearInterval(scope._interval)
                }
            }, 1000)
        }
    }

    this.createItemHolderHeader = function(i){
        if(this.options.header){
            var header = document.createElement('div');
            header.className = "mw-ui-box-header";
            header.innerHTML = this.options.header.replace(/{count}/g, '<span class="mw-module-settings-box-index">'+(i+1)+'</span>');
            mw.$(header).on('click', function(){
                mw.$(this).next().slideToggle();
            });
            return header;

        }
    };
    this.headerAnalize = function(header){
        mw.$("[data-action='remove']", header).on('click', function(e){
            e.stopPropagation();
            e.preventDefault();
            $(mw.tools.firstParentOrCurrentWithAnyOfClasses(this, ['mw-module-settings-box'])).remove();
            scope.refactorDomPosition();
            scope.autoSave();
        });
    };
    this.createItemHolder = function(i){
        i = i || 0;
        var holder = document.createElement('div');
        var holderin = document.createElement('div');
        holder.className = 'mw-ui-box mw-module-settings-box';
        holderin.className = 'mw-ui-box-content mw-module-settings-box-content';
        holderin.style.display = 'none';
        holder.appendChild(holderin);
        if(!this.options.element.children) {
            this.options.element.appendChild(holder);
        } else if (!this.options.element.children[i]){
            this.options.element.appendChild(holder);
        } else if (this.options.element.children[i]){
            $(this.options.element.children[i]).before(holder);
        }


        return holder;
    };

    this.addNew = function(pos, method){
        method = method || 'new';
        pos = pos || 0;
        var _new;

        var val = this.value[0];

        if(val) {
            _new = mw.tools.cloneObject(JSON.parse(JSON.stringify(this.value[0])));

        } else {
            _new = {};
        }


        if(_new.title) {
            _new.title += ' - new';
        } else if(_new.name) {
            _new.name += ' - new';
        }
        if(method === 'new'){
            $.each(this.options.schema, function(){
                if(this.value) {
                    if(typeof this.value === 'function') {
                        _new[this.id] = this.value();
                    } else {
                        _new[this.id] = this.value;
                    }
                }
            });
        } else if(method === 'blank') {
            for (var i in _new) {
                _new[i] = '';
                if(i === 'name' || i === 'title') {
                    _new[i] = 'New';
                }
            }
        }





        this.value.splice(pos, 0, _new);
        this.createItem(_new, pos);
        scope.refactorDomPosition();
        scope.autoSave();
    };

    this.remove = function(pos){
        if(typeof pos === 'undefined') return;
        this.value.splice(pos, 1);
        this.items.splice(pos, 1);
        mw.$(this.options.element).children().eq(pos).animate({opacity: 0, height: 0}, function(){
            mw.$(this).remove();
        });
        mw.$(scope).trigger('change', [scope.value/*, scope.value[i]*/]);
    };

    this.createItem = function(curr, i){
        var box = this.createItemHolder(i);
        var header = this.createItemHolderHeader(i);
        var item = new mw.propEditor.schema({
            schema: this.options.schema,
            element: box.querySelector('.mw-ui-box-content')
        });
        mw.$(box).prepend(header);
        this.headerAnalize(header);
        this.items.push(item);
        item.options.element._prop = item;
        item.setValue(curr);
        mw.$(item).on('change', function(){
            $.each(item.getValue(), function(a, b){
                // todo: faster approach
                var index = mw.$(box).parent().children('.mw-module-settings-box').index(box);
                scope.value[index][a] = b;
            });
            $('[data-bind]', header).each(function () {
                var val = item.getValue();
                var bind = this.dataset.bind;
                if(val[bind]){
                    this.innerHTML = val[bind];
                } else {
                    this.innerHTML = this.dataset.orig;
                }
            });
            mw.$(scope).trigger('change', [scope.value/*, scope.value[i]*/]);
        });
        $('[data-bind]', header).each(function () {
            var val = item.getValue();
            var bind = this.dataset.bind;
            this.dataset.orig = this.innerHTML;
            if(val[bind]){
                this.innerHTML = val[bind];
            }
        });
    };

    this._autoSaveTime = null;
    this.autoSave = function(){
        if(this.options.autoSave){
            clearTimeout(this._autoSaveTime);
            this._autoSaveTime = setTimeout(function(){
                scope.save();
            }, 500);
        }
    };

    this.refactorDomPosition = function(){
        scope.items = [];
        scope.value = [];
        mw.$(".mw-module-settings-box-index", this.options.element).each(function (i) {
            mw.$(this).text(i+1);
        });
        mw.$('.mw-module-settings-box-content', this.options.element).each(function(i){
            scope.items.push(this._prop);
            scope.value.push(this._prop.getValue());
        });
        mw.$(scope).trigger('change', [scope.value]);
    };

    this.create = function(){
        this.value.forEach(function(curr, i){
            scope.createItem(curr, i);
        });
        if(this.options.sortable && $.fn.sortable){
            var conf = {
                update: function (event, ui) {
                    setTimeout(function(){
                        scope.refactorDomPosition();
                        scope.autoSave();
                    }, 10);
                },
                handle:this.options.header ? '.mw-ui-box-header' : undefined,
                axis:'y'
            };
            if(typeof this.options.sortable === 'object'){
                conf = $.extend({}, conf, this.options.sortable);
            }
            mw.$(this.options.element).sortable(conf);
        }
    };

    this.init = function(){
        this.create();
    };

    this.save = function(){
        var key = (this.options.key || this.options.option_key);
        var group = (this.options.group || this.options.option_group);
        if( key && group){
            var options = {
                group:this.options.group,
                key:this.options.key,
                value:this.toString()
            };
            mw.options.saveOption(options, function(){
                mw.notification.msg(scope.savedMessage || mw.msg.settingsSaved)
            });
        }
        else{
            if(!key){
                console.warn('Option key is not defined.');
            }
            if(!group){
                console.warn('Option group is not defined.');
            }
        }

    };


    this.toString = function(){
        return JSON.stringify(this.value);
    };

    this.init();
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/system/prop_editor.js":
/*!****************************************************************!*\
  !*** ./userfiles/modules/microweber/api/system/prop_editor.js ***!
  \****************************************************************/
/***/ (() => {

mw.propEditor = {
    addInterface:function(name, func){
        this.interfaces[name] = this.interfaces[name] || func;
    },
    getRootElement: function(node){
        if(node.nodeName !== 'IFRAME') return node;
        return $(node).contents().find('body')[0];
    },
    helpers:{
        wrapper:function(){
            var el = document.createElement('div');
            el.className = 'mw-ui-field-holder prop-ui-field-holder';
            return el;
        },
        buttonNav:function(){
            var el = document.createElement('div');
            el.className = 'mw-ui-btn-nav prop-ui-field-holder';
            return el;
        },
        quatroWrapper:function(cls){
            var el = document.createElement('div');
            el.className = cls || 'prop-ui-field-quatro';
            return el;
        },
        label:function(content){
            var el = document.createElement('label');
            el.className = 'control-label d-block prop-ui-label';
            el.innerHTML = content;
            return el;
        },
        button:function(content){
            var el = document.createElement('button');
            el.className = 'mw-ui-btn';
            el.innerHTML = content;
            return el;
        },
        field: function(val, type, options){
            type = type || 'text';
            var el;
            if(type === 'select'){
                el = document.createElement('select');
                if(options && options.length){
                    var option = document.createElement('option');
                        option.innerHTML = 'Choose...';
                        option.value = '';
                        el.appendChild(option);
                    for(var i=0;i<options.length;i++){
                        var opt = document.createElement('option');
                        if(typeof options[i] === 'string' || typeof options[i] === 'number'){
                            opt.innerHTML = options[i];
                            opt.value = options[i];
                        }
                        else{
                            opt.innerHTML = options[i].title;
                            opt.value = options[i].value;
                        }
                        el.appendChild(opt);
                    }
                }
            }
            else if(type === 'textarea'){
                el = document.createElement('textarea');
            } else{
                el = document.createElement('input');
                try { // IE11 throws error on html5 types
                    el.type = type;
                } catch (err) {
                    el.type = 'text';
                }

            }

            el.className = 'form-control prop-ui-field';
            el.value = val;
            return el;
        },
        fieldPack:function(label, type){
            var field = mw.propEditor.helpers.field('', type);
            var holder = mw.propEditor.helpers.wrapper();
            label = mw.propEditor.helpers.label(label);
            holder.appendChild(label)
            holder.appendChild(field);
            return{
                label:label,
                holder:holder,
                field:field
            }
        }
    },
    rend:function(element, rend){

        element = mw.propEditor.getRootElement(element);
        for(var i=0;i<rend.length;i++){
            element.appendChild(rend[i].node);
        }
    },
    schema:function(options){
        this._after = [];
        this.setSchema = function(schema){
            this.options.schema = schema;
            this._rend = [];
            this._valSchema = this._valSchema || {};
            for(var i =0; i< this.options.schema.length; i++){
                var item = this.options.schema[i];
                if(typeof this._valSchema[item.id] === 'undefined' && this._cache.indexOf(item) === -1){
                    this._cache.push(item)
                    var curr = new mw.propEditor.interfaces[item.interface](this, item);
                    this._rend.push(curr);
                    if(item.id){
                        this._valSchema[item.id] = this._valSchema[item.id] || '';
                    }
                }
            }
            $(this.rootHolder).html(' ').addClass('mw-prop-editor-root');
            mw.propEditor.rend(this.rootHolder, this._rend);
        };
        this.updateSchema = function(schema){
            var final = [];
            for(var i =0; i<schema.length;i++){
                var item = schema[i];

                if(typeof this._valSchema[item.id] === 'undefined' && this._cache.indexOf(item) === -1){
                    this.options.schema.push(item);
                    this._cache.push(item)
                    var create = new mw.propEditor.interfaces[item.interface](this, item);
                    this._rend.push(create);
                    final.push(create);
                    if(item.id){
                        this._valSchema[item.id] = this._valSchema[item.id] || '';
                    }
                    //this.rootHolder.appendChild(create.node);
                }
            }
            return final;
        };
        this.setValue = function(val){
            if(!val){
                return;
            }
            for(var i in val){
                var rend = this.getRendById(i);
                if(!!rend){
                    rend.setValue(val[i]);
                }
            }
        };
        this.getValue = function(){
            return this._valSchema;
        };
        this.disable = function(){
            this.disabled = true;
            $(this.rootHolder).addClass('disabled');
        };
        this.enable = function(){
            this.disabled = false;
            $(this.rootHolder).removeClass('disabled');
        };
        this.getRendById = function(id) {
            for(var i in this._rend) {
                if(this._rend[i].id === id) {
                    return this._rend[i];
                }
            }
        };
        this._cache = [];
        this.options = options;
        this.options.element = typeof this.options.element === 'string' ? document.querySelector(options.element) : this.options.element;
        this.rootHolder = mw.propEditor.getRootElement(this.options.element);
        this.setSchema(this.options.schema);

        this._after.forEach(function (value) {
            value.items.forEach(function (item) {
                value.node.appendChild(item.node);
            });
        });

        mw.trigger('ComponentsLaunch');
    },

    interfaces:{
        quatro:function(proto, config){
            //"2px 4px 8px 122px"
            var holder = mw.propEditor.helpers.quatroWrapper('mw-css-editor-group');

            for(var i = 0; i<4; i++){
                var item = mw.propEditor.helpers.fieldPack(config.label[i], 'size');
                holder.appendChild(item.holder);
                item.field.oninput = function(){
                    var final = '';
                    var all = holder.querySelectorAll('input'), i = 0;
                    for( ; i<all.length; i++){
                        var unit = all[i].dataset.unit || '';
                        final+= ' ' + all[i].value + unit ;
                    }
                    proto._valSchema[config.id] = final.trim();
                     $(proto).trigger('change', [config.id, final.trim()]);
                };
            }
            this.node = holder;
            this.setValue = function(value){
                value = value.trim();
                var arr = value.split(' ');
                var all = holder.querySelectorAll('input'), i = 0;
                for( ; i<all.length; i++){
                    all[i].value = parseInt(arr[i], 10);
                    if(typeof arr[i] === 'undefined'){
                        arr[i] = '';
                    }
                    var unit = arr[i].replace(/[0-9]/g, '');
                    all[i].dataset.unit = unit;
                }
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
        },
        hr:function(proto, config){
            var el = document.createElement('hr');
            el.className = ' ';
            this.node = el;
        },
        block: function(proto, config){
            var node = document.createElement('div');
            if(typeof config.content === 'string') {
                node.innerHTML = config.content;
            } else {
                var newItems = proto.updateSchema(config.content);
                proto._after.push({node: node, items: newItems});
            }
            if(config.class){
                node.className = config.class;
            }
            this.node = node;
        },
        size:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'text');
            this.field = field;
            config.autocomplete = config.autocomplete || ['auto'];

            var holder = mw.propEditor.helpers.wrapper();
            var buttonNav = mw.propEditor.helpers.buttonNav();
            var label = mw.propEditor.helpers.label(config.label);
            var scope = this;
            var dtlist = document.createElement('datalist');
            dtlist.id = 'mw-datalist-' + mw.random();
            config.autocomplete.forEach(function (value) {
                var option = document.createElement('option');
                option.value = value;
                dtlist.appendChild(option)
            });

            this.field.setAttribute('list', dtlist.id);
            document.body.appendChild(dtlist);

            this._makeVal = function(){
                if(field.value === 'auto'){
                    return 'auto';
                }
                return field.value + field.dataset.unit;
            };

            var unitSelector = mw.propEditor.helpers.field('', 'select', [
                'px', '%', 'rem', 'em', 'vh', 'vw', 'ex', 'cm', 'mm', 'in', 'pt', 'pc', 'ch'
            ]);
            this.unitSelector = unitSelector;
            $(holder).addClass('prop-ui-field-holder-size');
            $(unitSelector)
                .val('px')
                .addClass('prop-ui-field-unit');
            unitSelector.onchange = function(){
                field.dataset.unit = $(this).val() || 'px';

                $(proto).trigger('change', [config.id, scope._makeVal()]);
            };

            $(unitSelector).on('change', function(){

            });

            holder.appendChild(label);
            buttonNav.appendChild(field);
            buttonNav.appendChild(unitSelector);
            holder.appendChild(buttonNav);

            field.oninput = function(){

                proto._valSchema[config.id] = this.value + this.dataset.unit;
                $(proto).trigger('change', [config.id, scope._makeVal()]);
            };

            this.node = holder;
            this.setValue = function(value){
                var an = parseInt(value, 10);
                field.value = isNaN(an) ? value : an;
                proto._valSchema[config.id] = value;
                var unit = value.replace(/[0-9]/g, '').replace(/\./g, '');
                field.dataset.unit = unit;
                $(unitSelector).val(unit);
            };
            this.id = config.id;

        },
        text:function(proto, config){
            var val = '';
            if(config.value){
                if(typeof config.value === 'function'){
                    val = config.value();
                } else {
                    val = config.value;
                }
            }
            var field = mw.propEditor.helpers.field(val, 'text');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
        },
        textArea:function(proto, config){
            var val = '';
            if(config.value){
                if(typeof config.value === 'function'){
                    val = config.value();
                } else {
                    val = config.value;
                }
            }
            var field = mw.propEditor.helpers.field(val, 'textarea');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
        },
        hidden:function(proto, config){
            var val = '';
            if(config.value){
                if(typeof config.value === 'function'){
                    val = config.value();
                } else {
                    val = config.value;
                }
            }

            var field = mw.propEditor.helpers.field(val, 'hidden');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
        },
        shadow: function(proto, config){
            var scope = this;

            this.fields = {
                position : mw.propEditor.helpers.field('', 'select', [{title:'Outside', value: ''}, {title:'Inside', value: 'inset'}]),
                x : mw.propEditor.helpers.field('', 'number'),
                y : mw.propEditor.helpers.field('', 'number'),
                blur : mw.propEditor.helpers.field('', 'number'),
                spread : mw.propEditor.helpers.field('', 'number'),
                color : mw.propEditor.helpers.field('', 'text')
            };

            this.fields.position.placeholder = 'Position';
            this.fields.x.placeholder = 'X offset';
            this.fields.y.placeholder = 'Y offset';
            this.fields.blur.placeholder = 'Blur';
            this.fields.spread.placeholder = 'Spread';
            this.fields.color.placeholder = 'Color';
            this.fields.color.dataset.options = 'position: ' + (config.pickerPosition || 'bottom-center');
            //$(this.fields.color).addClass('mw-color-picker');
            mw.colorPicker({
                element:this.fields.color,
                position:'top-left',
                onchange:function(color){
                    $(scope.fields.color).trigger('change', color)
                    scope.fields.color.style.backgroundColor = color;
                    scope.fields.color.style.color = mw.color.isDark(color) ? 'white' : 'black';
                }
            });

            var labelPosition = mw.propEditor.helpers.label('Position');
            var labelX = mw.propEditor.helpers.label('X offset');
            var labelY = mw.propEditor.helpers.label('Y offset');
            var labelBlur = mw.propEditor.helpers.label('Blur');
            var labelSpread = mw.propEditor.helpers.label('Spread');
            var labelColor = mw.propEditor.helpers.label('Color');

            var wrapPosition = mw.propEditor.helpers.wrapper();
            var wrapX = mw.propEditor.helpers.wrapper();
            var wrapY = mw.propEditor.helpers.wrapper();
            var wrapBlur = mw.propEditor.helpers.wrapper();
            var wrapSpread = mw.propEditor.helpers.wrapper();
            var wrapColor = mw.propEditor.helpers.wrapper();



            this.$fields = $();

            $.each(this.fields, function(){
                scope.$fields.push(this);
            });

            $(this.$fields).on('input change', function(){
                var val = ($(scope.fields.position).val() || '')
                    + ' ' + (scope.fields.x.value || 0) + 'px'
                    + ' ' + (scope.fields.y.value || 0) + 'px'
                    + ' ' + (scope.fields.blur.value || 0) + 'px'
                    + ' ' + (scope.fields.spread.value || 0) + 'px'
                    + ' ' + (scope.fields.color.value || 'rgba(0,0,0,.5)');
                proto._valSchema[config.id] = val;
                $(proto).trigger('change', [config.id, val]);
            });


            var holder = mw.propEditor.helpers.wrapper();

            var label = mw.propEditor.helpers.label(config.label ? config.label : '');
            if(config.label){
                holder.appendChild(label);
            }
            var row1 = mw.propEditor.helpers.wrapper();
            var row2 = mw.propEditor.helpers.wrapper();
            row1.className = 'mw-css-editor-group';
            row2.className = 'mw-css-editor-group';


            wrapPosition.appendChild(labelPosition);
            wrapPosition.appendChild(this.fields.position);
            row1.appendChild(wrapPosition);

            wrapX.appendChild(labelX);
            wrapX.appendChild(this.fields.x);
            row1.appendChild(wrapX);


            wrapY.appendChild(labelY);
            wrapY.appendChild(this.fields.y);
            row1.appendChild(wrapY);

            wrapColor.appendChild(labelColor);
            wrapColor.appendChild(this.fields.color);
            row2.appendChild(wrapColor);

            wrapBlur.appendChild(labelBlur);
            wrapBlur.appendChild(this.fields.blur);
            row2.appendChild(wrapBlur);

            wrapSpread.appendChild(labelSpread);
            wrapSpread.appendChild(this.fields.spread);
            row2.appendChild(wrapSpread);

            holder.appendChild(row1);
            holder.appendChild(row2);

            $(this.fields).each(function () {
                $(this).on('input change', function(){
                    proto._valSchema[config.id] = this.value;
                    $(proto).trigger('change', [config.id, this.value]);
                });
            });


            this.node = holder;
            this.setValue = function(value){
                var parse = this.parseShadow(value);
                $.each(parse, function (key, val) {
                    scope.fields[key].value = this;
                });
                proto._valSchema[config.id] = value;
            };
            this.parseShadow = function(shadow){
                var inset = false;
                if(shadow.indexOf('inset') !== -1){
                    inset = true;
                }
                var arr = shadow.replace('inset', '').trim().replace(/\s{2,}/g, ' ').split(' ');
                var sh = {
                    position: inset ? 'in' : 'out',
                    x:0,
                    y: 0,
                    blur: 0,
                    spread: 0,
                    color: 'transparent'
                };
                if(!arr[2]){
                    return sh;
                }
                sh.x = arr[0];
                sh.y = arr[1];
                sh.blur = (!isNaN(parseInt(arr[2], 10))?arr[2]:'0px');
                sh.spread = (!isNaN(parseInt(arr[3], 10))?arr[3]:'0px');
                sh.color = isNaN(parseInt(arr[arr.length-1])) ? arr[arr.length-1] : 'transparent';
                return sh;
            };
            this.id = config.id;
        },
        number:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'number');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            this.node = holder;
            this.setValue=function(value){
                field.value = parseInt(value, 10);
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
        },
        color:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'text');
            if(field.type !== 'color'){
                mw.colorPicker({
                    element:field,
                    position: config.position || 'bottom-center',
                    onchange:function(){
                        $(proto).trigger('change', [config.id, field.value]);
                    }
                });
            }
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.oninput = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            }
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                proto._valSchema[config.id] = value
            };
            this.id = config.id
        },
        select:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'select', config.options);
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            field.onchange = function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            };
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                proto._valSchema[config.id] = value
            };
            this.id = config.id;
        },
        file: function(proto, config){
            if(config.multiple === true){
                config.multiple = 99;
            }
            if(!config.multiple){
                config.multiple = 1;
            }
            var scope = this;
            var createButton = function(imageUrl, i, proto){
                imageUrl = imageUrl || '';
                var el = document.createElement('div');
                el.className = 'upload-button-prop mw-ui-box mw-ui-box-content';
                var btn =  document.createElement('span');
                btn.className = ('mw-ui-btn');
                btn.innerHTML = ('<span class="mw-icon-upload"></span>');
                btn.style.backgroundSize = 'cover';
                btn.style.backgroundColor = 'transparent';
                el.style.backgroundSize = 'cover';
                btn._value = imageUrl;
                btn._index = i;
                if(imageUrl){
                    el.style.backgroundImage = 'url(' + imageUrl + ')';
                }
                btn.onclick = function(){
                    var dialog;
                    var picker = new (mw.top()).filePicker({
                        type: 'images',
                        label: false,
                        autoSelect: false,
                        footer: true,
                        _frameMaxHeight: true,

                        onResult: function (res) {
                            var url = res.src ? res.src : res;
                            url = url.toString();
                            proto._valSchema[config.id] = proto._valSchema[config.id] || [];
                            proto._valSchema[config.id][btn._index] = url;
                            el.style.backgroundImage = 'url(' + url + ')';
                            btn._value = url;
                            scope.refactor();
                            dialog.remove()
                        },
                        cancel: function () {
                            dialog.remove()
                        }
                    });
                    dialog = mw.top().dialog({
                        content: picker.root,
                        title: mw.lang('Select image'),
                        footer: false,
                        width: 1200
                    })


                };

                if(config.multiple === true || (typeof config.multiple === 'number' && config.multiple > 1) ) {
                    var close = document.createElement('span');
                    close.className = 'mw-badge mw-badge-important';
                    close.innerHTML = '<span class="mw-icon-close"></span>';

                    close.onclick = function(e){
                        scope.remove(el);
                        e.preventDefault();
                        e.stopPropagation();
                    };
                    el.appendChild(close);
                }


                el.appendChild(btn);
                return el;
            };

            this.remove = function (i) {
                if(typeof i === 'number'){
                    $('.upload-button-prop', el).eq(i).remove();
                }
                else{
                    $(i).remove();
                }
                scope.refactor();
            };

            this.hasMultiple = function () {
                return config.multiple && config.multiple > 1;
            }

            this.addImageButton = function(){
                if(this.hasMultiple()){
                    this.addBtn = document.createElement('div');
                    this.addBtn.className = 'mw-ui-link';
                    //this.addBtn.innerHTML = '<span class="mw-icon-plus"></span>';
                    this.addBtn.innerHTML = mw.msg.addImage;
                    this.addBtn.onclick = function(){
                        el.appendChild(createButton(undefined, 0, proto));
                        scope.manageAddImageButton();
                    };
                    holder.appendChild(this.addBtn);
                }
            };

            this.manageAddImageButton = function(){
                if(this.hasMultiple()) {
                    var isVisible = $('.upload-button-prop', this.node).length < config.multiple;
                    this.addBtn.style.display = isVisible ? 'inline-block' : 'none';
                }

            };

            var btn = createButton(undefined, 0, proto);
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            var el = document.createElement('div');
            el.className = 'mw-ui-box-content';
            el.appendChild(btn);
            holder.appendChild(el);

            this.addImageButton();
            this.manageAddImageButton();

            if($.fn.sortable && this.hasMultiple()){
                $(el).sortable({
                    update:function(){
                        scope.refactor();
                    }
                });
            }



            this.refactor = function () {
                var val = [];
                $('.mw-ui-btn', el).each(function(){
                    val.push(this._value);
                });
                this.manageAddImageButton();
                if(val.length === 0){
                    val = [''];
                }
                proto._valSchema[config.id] = val;
                $(proto).trigger('change', [config.id, proto._valSchema[config.id]]);
            };

            this.node = holder;
            this.setValue = function(value){
                value = value || [''];
                proto._valSchema[config.id] = value;
                $('.upload-button-prop', holder).remove();
                if(typeof value === 'string'){
                    el.appendChild(createButton(value, 0, proto));
                }
                else{
                    $.each(value, function (index) {
                        el.appendChild(createButton(this, index, proto));
                    });
                }

                this.manageAddImageButton();
            };
            this.id = config.id;
        },
        icon: function(proto, config){
            var holder = mw.propEditor.helpers.wrapper();

            var el = document.createElement('span');
            el.className = "btn btn-outline-primary";
            el.innerHTML = "Icon";
            var elTarget = document.createElement('i');

            el.onclick = function () {
                picker.dialog();
            };

            var removeEl = document.createElement('span');
            removeEl.className = "btn btn-outline-danger tip";
            removeEl.dataset.tip = "Remove icon";
            removeEl.innerHTML = "<span class='fa fa-trash'></span>";
            removeEl.style.marginInlineStart = "10px";

            removeEl.onclick = function () {
                proto._valSchema[config.id] = '';
                $(proto).trigger('change', [config.id, '']);
                $(el.firstElementChild).hide();
            };

            $(el).prepend(elTarget);
            $(holder).prepend(removeEl);
            $(holder).prepend(el);
            mw.iconLoader().init();
            var picker = mw.iconPicker({iconOptions: false});
            picker.target = elTarget;
            picker.on('select', function (data) {
                $(el.firstElementChild).show();
                data.render();
                proto._valSchema[config.id] = picker.target.outerHTML;
                $(proto).trigger('change', [config.id, picker.target.outerHTML]);
                picker.dialog('hide');
             });

            var label = mw.propEditor.helpers.label(config.label);

            $(holder).prepend(label);

            this.node = holder;
            this.setValue = function(value){

                if(picker && picker.value) {
                    picker.value(value);
                }
                if(value) {
                    $(elTarget).replaceWith(value);
                } else {
                    $(el.firstElementChild).hide();
                }
                picker.target = el.firstElementChild;
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;

        },
        richtext:function(proto, config){
            var field = mw.propEditor.helpers.field('', 'textarea');
            var holder = mw.propEditor.helpers.wrapper();
            var label = mw.propEditor.helpers.label(config.label);
            holder.appendChild(label);
            holder.appendChild(field);
            $(field).on('change', function(){
                proto._valSchema[config.id] = this.value;
                $(proto).trigger('change', [config.id, this.value]);
            });
            this.node = holder;
            this.setValue = function(value){
                field.value = value;
                this.editor.setContent(value, true);
                proto._valSchema[config.id] = value;
            };
            this.id = config.id;
            var defaults = {
                height: 120,
                mode: 'div',
                smallEditor: false,
                controls: [
                    [
                        'bold', 'italic',
                        {
                            group: {
                                icon: 'mdi xmdi-format-bold',
                                controls: ['underline', 'strikeThrough', 'removeFormat']
                            }
                        },

                        '|', 'align', '|', 'textColor', 'textBackgroundColor', '|', 'link', 'unlink'
                    ],
                ]
            };
            config.options = config.options || {};
            this.editor = mw.Editor($.extend({}, defaults, config.options, {selector: field}));
        }
    }
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/system/state.js":
/*!**********************************************************!*\
  !*** ./userfiles/modules/microweber/api/system/state.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _classes_state__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../classes/state */ "./userfiles/modules/microweber/api/classes/state.js");


(function(){
    if(mw.liveEditState) return;
    mw.State = _classes_state__WEBPACK_IMPORTED_MODULE_0__.State
    mw.liveEditState = new mw.State();
    mw.liveEditState.record({
         value: null,
         $initial: true
    });
    mw.$liveEditState = mw.$(mw.liveEditState);

    var ui = mw.$('<div class="mw-ui-btn-nav"></div>'),
        undo = document.createElement('span'),
        redo = document.createElement('span');
    undo.className = 'mw-ui-btn mw-ui-btn-medium';
    undo.innerHTML = '<span class="mw-icon-reply"></span>';
    redo.className = 'mw-ui-btn mw-ui-btn-medium';
    redo.innerHTML = '<span class="mw-icon-forward"></span>';

    undo.onclick = function(){
        mw.liveEditState.undo();
    };
    redo.onclick = function(){
        mw.liveEditState.redo();
    };

    ui.append(undo);
    ui.append(redo);

    mw.$(document).ready(function(){
        var idata = mw.liveEditState.eventData();

        mw.$(undo)[!idata.hasNext?'addClass':'removeClass']('disabled');
        mw.$(redo)[!idata.hasPrev?'addClass':'removeClass']('disabled');

        /*undo.disabled = !idata.hasNext;
        redo.disabled = !idata.hasPrev;*/

        var edits = document.querySelectorAll('.edit'), editstime = null;

        for ( var i = 0; i < edits.length; i++ ) {
            if(!mw.tools.hasParentsWithClass(this, 'edit')) {
                edits[i].addEventListener('beforeinput', function (e) {
                    var sel = getSelection();
                    var target = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
                     if(target) {
                        mw.liveEditState.record({
                            target: target,
                            value: target.innerHTML
                        });
                    }
                });
                edits[i].addEventListener('input', function (e) {
                        var sel = getSelection();
                        var target = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
                        if(!target) return;
                        mw.liveEditState.record({
                            target: target,
                            value: target.innerHTML
                        });
                        this.__initialRecord = false;
                });
            }
        }

        mw.$liveEditState.on('stateRecord', function(e, data){
            mw.$(undo)[!data.hasNext?'addClass':'removeClass']('disabled');
            mw.$(redo)[!data.hasPrev?'addClass':'removeClass']('disabled');
        });
        mw.$liveEditState.on('stateUndo stateRedo', function(e, data){

            if(data.active) {
                var target = data.active.target;
                if(typeof target === 'string'){
                    target = document.querySelector(data.active.target);
                }

                if(!data.active || (!target && !data.active.action)) {
                    mw.$(undo)[!data.hasNext?'addClass':'removeClass']('disabled');
                    mw.$(redo)[!data.hasPrev?'addClass':'removeClass']('disabled');
                    return;
                }
                if(data.active.action) {
                    data.active.action();
                } else if(document.body.contains(target)) {
                    mw.$(target).html(data.active.value);
                } else{
                    if(target.id) {
                        mw.$(document.getElementById(target.id)).html(data.active.value);
                    }
                }
                if(data.active.prev) {
                    mw.$(data.active.prev).html(data.active.prevValue);
                }
            }
            mw.drag.load_new_modules();
            mw.$(undo)[!data.hasNext?'addClass':'removeClass']('disabled');
            mw.$(redo)[!data.hasPrev?'addClass':'removeClass']('disabled');
        });

        mw.$('#history_panel_toggle,#history_dd,.mw_editor_undo,.mw_editor_redo').remove();
        mw.$('.wysiwyg-cell-undo-redo').eq(0).prepend(ui);

        mw.element(document.body).on('keydown', function(e) {
            if( e.key )  {
                var key = e.key.toLowerCase();
                if (e.ctrlKey && key === 'z' && !e.shiftKey) {
                    e.preventDefault();
                    mw.liveEditState.undo();
                } else if ((e.ctrlKey && key === 'y') || (e.ctrlKey && e.shiftKey && key === 'z')) {
                    e.preventDefault();
                    mw.liveEditState.redo();
                }
            }

        });
    });
})();




/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/@tools.js":
/*!**********************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/@tools.js ***!
  \**********************************************************/
/***/ (() => {

mw.tools = {

};

mw.external = function (o) {
    return mw.tools._external(o);
};

mw.gallery = function (arr, start, modal) {
    return mw.tools.gallery.init(arr, start, modal);
};


mw.gallery = function (arr, start, modal) {
    return mw.tools.gallery.init(arr, start, modal);
};





/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/common-extend.js":
/*!****************************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/common-extend.js ***!
  \****************************************************************************/
/***/ (() => {

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

mw.datassetSupport = typeof document.documentElement.dataset !== 'undefined';

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
        return typeof temp === 'function' ? temp(a, b, c) : temp;
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
    !_Prefixtest ? _Prefixtest = document.body.style : '';
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



/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/common.js":
/*!*********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/common.js ***!
  \*********************************************************************/
/***/ (() => {

$(window).on('load', function () {
    mw.loaded = true;
    mw.tools.addClass(document.body, 'loaded');
    mw.tools.removeClass(document.body, 'loading');
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
$(document).ready(function () {
    mw.tools.constructions();
    mw.dropdown();
    mw.$(document.body).ajaxStop(function () {
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
    mw.$(document.body).on('mousemove touchmove touchstart', function (event) {
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
    mw.$(".mw-dropdown-search")
        .on('focus', function (e) {
            $(this).parents('.create-content-dropdown').addClass('focus');
        })
        .on('blur', function (e) {
            $(this).parents('.create-content-dropdown').removeClass('focus');
        })
        .on('keyup', function (e) {
        if (e.keyCode === '27') {
            mw.$(this.parentNode.parentNode).hide();
        }
        if (e.keyCode !== '13' && e.keyCode !== '27' && e.keyCode !== '32') {
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
    mw.$(window).on('resize', function () {
        if ($(window).width() > _mwoldww) {
            mw.trigger("increaseWidth");
        }
        else if ($(window).width() < _mwoldww) {
            mw.trigger("decreaseWidth");
        }
        $.noop();
        _mwoldww = mw.$(window).width();
    });
    mw.$(document.body).on("keydown", function (e) {
        var isgal = document.querySelector('.mw_modal_gallery') !== null;
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


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/cookie.js":
/*!*********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/cookie.js ***!
  \*********************************************************************/
/***/ (() => {

mw.cookie = {
    get: function (name) {
        var cookies = document.cookie.split(";"), i = 0, l = cookies.length;
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


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/domhelpers.js":
/*!*************************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/domhelpers.js ***!
  \*************************************************************************/
/***/ (() => {

(function(){
var domHelp = {
    classNamespaceDelete: function (el_obj, namespace, parent, namespacePosition, exception) {
        var exceptions, el;
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

    parentsOrCurrentOrderMatchOrOnlyFirst: function (node, arr) {
        var curr = node;
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
            count++;
            var h1 = mw.tools.hasClass(curr, arr[0]);
            var h2 = mw.tools.hasClass(curr, arr[1]);
            if (h1 && h2) {
                return match.a > 0;

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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
            if (curr.id === id) {
                return true;
            }
            curr = curr.parentNode;
        }
        return false;
    },

    hasChildrenWithTag: function (el, tag) {
        tag = tag.toLowerCase();
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
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
        tag = tag.toLowerCase();
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
            if (mw.tools.hasClass(curr, cls)) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    firstBlockLevel: function (el) {
        while(el && el.classList) {
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
        while(el && el.classList) {
            if(!mw.tools.isInlineLevel(el)) {
                return el;
            }
            el = el.parentNode;
        }
    },
    firstParentOrCurrentWithId: function (el, id) {
        if (!el) return false;
        var curr = el;
        while (curr && el.classList) {
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
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
        while (curr && curr.classList) {
            if (tag.indexOf(curr.nodeName.toLowerCase()) !== -1) {
                return curr;
            }
            curr = curr.parentNode;
        }
        return false;
    },
    generateSelectorForNode: function (node, strict) {
         if(typeof strict === 'undefined') {
            strict = true;
        }
        if (node === null || node.nodeType === 3) {
            return false;
        }
        if (node.nodeName === 'BODY') {
            return 'body';
        }
        if(strict && !node.id) {
            if(!node.classList.contains('edit') && mw.tools.isEditable(node)) {
                node.id = mw.id('mw-selector-');
            }
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


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/dropdown.js":
/*!***********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/dropdown.js ***!
  \***********************************************************************/
/***/ (() => {

mw.tools.dropdown = function (root) {
    root = root || document.body;
    if (root === null) {
        return;
    }

    var isMobile = ('ontouchstart' in document.documentElement && /mobi/i.test(navigator.userAgent));
    mw.tools.dropdownActivatedBindOnEventsNames = 'mousedown';
    if(isMobile){
        mw.tools.dropdownActivatedBindOnEventsNames = 'mousedown touchstart';
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
            .on(mw.tools.dropdownActivatedBindOnEventsNames, 'li[value]', function (event) {
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
        mw.$(document.body).on(mw.tools.dropdownActivatedBindOnEventsNames, function (e) {
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


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/element.js":
/*!**********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/element.js ***!
  \**********************************************************************/
/***/ (() => {

(function(){

    var MWElement = function(options, root){
        var scope = this;

        this.isMWElement = true;

        this.toggle = function () {
            this.css('display', this.css('display') === 'none' ? 'block' : 'none');
        };

        this._active = function () {
            return this.nodes[this.nodes.length - 1];
        };

        this.getDocument = function () {
            return this._active().ownerDocument;
        }

        this.getWindow = function () {
            return this.getDocument().defaultView;;
        }

        this.get = function(selector, scope){
            this.nodes = (scope || document).querySelectorAll(selector);
        };

        this.each = function(cb){
            if(this.nodes) {
                for (var i = 0; i < this.nodes.length; i++) {
                    cb.call(this.nodes[i], i);
                }
            } else if(this.node) {
                cb.call(this.node, 0);
            }
            return this;
        };

        this.encapsulate = function () {

        };

        var contentManage = function (content, scope) {
            if (content) {
                if (Array.isArray(content)) {
                    content.forEach(function (el){
                        contentManage(el, scope);
                    });
                } else if (content instanceof MWElement) {
                    scope.append(content);
                } else if (typeof content === 'object') {
                    scope.append(new MWElement(content));
                }
            }
        }

        this.create = function() {
            var el = this.document.createElement(this.settings.tag);
            this.node = el;

            if (this.settings.encapsulate === true) {
                var mode = this.settings.encapsulate === true ? 'open' : this.settings.encapsulate;
                el.attachShadow({
                    mode: mode
                });
            }
            this.nodes = [el];

            if (this.settings.content) {
                contentManage(this.settings.content, this)
            }
            this.$node = $(el);
        };

        this._specialProps = function(dt, val){
            if(dt === 'tooltip') {
                this.node.dataset[dt] = val;
                return true;
            }
        };

        this.setProps = function(){
            for(var i in this.settings.props) {
                if (i === 'dataset') {
                    for(var dt in this.settings.props[i]) {
                        this.node.dataset[dt] = this.settings.props[i][dt];
                    }
                } else if (i === 'style') {
                    for(var st in this.settings.props[i]) {
                        this.node.style[st] = this.settings.props[i][st];
                    }
                } else {
                    var val = this.settings.props[i];
                    if(!this._specialProps(i, val)) {
                        this.node[i] = val;
                    }
                }
            }
        };

        this.__ = {
            cssNumber: [
                'animationIterationCount',
                'columnCount',
                'fillOpacity',
                'flexGrow',
                'flexShrink',
                'fontWeight',
                'gridArea',
                'gridColumn',
                'gridColumnEnd',
                'gridColumnStart',
                'gridRow',
                'gridRowEnd',
                'gridRowStart',
                'lineHeight',
                'opacity',
                'order',
                'orphans',
                'widows',
                'zIndex',
                'zoom'
            ]
        };

        this._normalizeCSSValue = function (prop, val) {
            if(typeof val === 'number') {
                if(this.__.cssNumber.indexOf(prop) === -1) {
                    val = val + 'px';
                }
            }
            return val;
        };

        this.css = function(css, val){
            if(typeof css === 'string') {
                if(typeof val !== 'undefined'){
                    var nval =  this._normalizeCSSValue(css, val);
                    this.each(function (){
                        this.style[css] = nval;
                    });
                } else {
                    return this.document.defaultView.getComputedStyle(this.node)[css];
                }
            }
            if(typeof css === 'object') {
                for (var i in css) {

                    this.each(function (){
                        this.style[i] = scope._normalizeCSSValue(i, css[i]);
                    });
                }
            }
            return this;
        };

        this.dataset = function(prop, val){
            if(typeof val === 'undefined') {
                return this._active()[prop];
            }
            this.each(function (){
                this.dataset[prop] = val;
            });
            return this;
        };

        this.attr = function(prop, val){
            if(typeof val === 'undefined') {
                return this._active()[prop];
            }
            this.each(function (){
                this.setAttribute(prop, val);
            });
            return this;
        };

        this.val = function(val){
            if(typeof val === 'undefined') {
                return this._active().value;
            }
            this.each(function (){
                this.value = val;
            });
            return this;
        };

        this.prop = function(prop, val){
            var active = this._active();
            if(typeof val === 'undefined') {
                return active[prop];
            }
            if(active[prop] !== val){
                active[prop] = val;
                this.trigger('propChange', [prop, val]);
            }
            return this;
        };

        this.hide = function () {
            return this.each(function (){
                this.style.display = 'none';
            });
        };
        this.show = function () {
            return this.each(function (){
                this.style.display = '';
            });
        };

        this.find = function (sel) {
            var el = mw.element('#r' + new Date().getTime());
            this.each(function (){
                var all = this.querySelectorAll(sel);
                for(var i = 0; i < all.length; i++) {
                    if(el.nodes.indexOf(all[i]) === -1) {
                        el.nodes.push(all[i]);
                    }
                }
            });
            return el;
        };

        this.addClass = function (cls) {
             cls = cls.trim().split(' ');
            return this.each(function (){
                var node = this;
                cls.forEach(function (singleClass){
                    node.classList.add(singleClass);
                });

            });
        };

        this.toggleClass = function (cls) {
            return this.each(function (){
                this.classList.toggle(cls.trim());
            });
        };

        this.removeClass = function (cls) {
            var isArray = Array.isArray(cls);
            if(!isArray) {
                cls = cls.trim();
                var isMultiple = cls.split(' ');
                if(isMultiple.length > 1) {
                    return this.removeClass(isMultiple)
                }
                return this.each(function (){
                    this.classList.remove(cls);
                });
            } else {
                return this.each(function (){
                    var i = 0, l = cls.length;
                    for ( ; i < l; i++) {
                        this.classList.remove(cls[i]);
                    }
                });
            }
        };

        this.remove = function () {
            return this.each(function (){
                this.remove();
            });
        };

        this.empty = function () {
            return this.html('');
        };

        this.html = function (val) {
            if (typeof val === 'undefined') {
                return this._active().innerHTML;
            }
            return this.each(function (){
                this.innerHTML = val;
            });
        };
        this.text = function (val, clean) {
            if(typeof val === 'undefined') {
                return this.node.textContent;
            }
            if(typeof clean === 'undefined') {
                clean = true;
            }
            if (clean) {
                val = this.document.createRange().createContextualFragment(val).textContent;
            }
            this.node.innerHTML = val;
        };

        this._asdom = function (obj) {
            if (typeof obj === 'string') {
                return this.document.createRange().createContextualFragment(obj);
            } else if (obj.node){
                return obj.node;
            }
            else if (obj.nodes){
                return obj.nodes[obj.nodes.length - 1];
            } else {
                return obj;
            }
        };

        this.offset = function () {
            var curr = this._active();
            var win = this.getWindow();
            var rect = curr.getBoundingClientRect();
            rect.offsetTop = rect.top + win.pageYOffset;
            rect.offsetBottom = rect.bottom + win.pageYOffset;
            rect.offsetLeft = rect.left + win.pageXOffset;
            return rect;
        };


        this.width = function (val) {
            if(val) {
                return this.css('width', val);
            }
            return this._active().offsetWidth;
        };

        this.height = function (val) {
            if(val) {
                return this.css('height', val);
            }
            return this._active().offsetHeight;
        };

        this.parent = function () {
            return mw.element(this._active().parentNode);
        };
        this.parents = function (selector) {
            selector = selector || '*';
            var el = this._active();
            var curr = el.parentElement;
            var res = mw.element();
            res.nodes = []
            while (curr) {
                if(curr.matches(selector)) {
                    res.nodes.push(curr);
                }
                curr = curr.parentElement;
            }
            return res;
        };
        this.append = function (el) {

            if (el) {
                this.each(function (){
                    this.append(scope._asdom(el));
                });
            }
            return this;
        };

        this.before = function (el) {
            if (el) {
                this.each(function (){
                    if(this.parentNode){
                        this.parentNode.insertBefore(scope._asdom(el), this);
                    }
                });
            }
            return this;
        };

        this.after = function (el) {
            if (el) {
                this.each(function (){
                    if(this.parentNode) {
                        this.parentNode.insertBefore(scope._asdom(el), this.nextSibling);
                    }
                });
            }
        };

        this.prepend = function (el) {
            if (el) {
                this.each(function (){
                    this.prepend(scope._asdom(el));
                });
            }
            return this;
        };
        this._disabled = false;

        Object.defineProperty(this, "disabled", {
            get : function () { return this._disabled; },
            set : function (value) {
                this._disabled = value;
                this.node.disabled = this._disabled;
                this.node.dataset.disabled = this._disabled;
            }
        });

        this.trigger = function(event, data){
            data = data || {};
            this.each(function (){
                this.dispatchEvent(new CustomEvent(event, {
                    detail: data,
                    cancelable: true,
                    bubbles: true
                }));
                if(scope._on[event]) {
                    scope._on[event].forEach(function(cb){
                        cb.call(this, event, data);
                    });
                }
            });
            return this;
        };

        this.get = function (i) {
            return this.nodes[i];
        };

        this._on = {};
        this.on = function(events, cb){
            events = events.trim().split(' ');
            events.forEach(function (ev) {
                if(!scope._on[ev]) {  scope._on[ev] = []; }
                scope._on[ev].push(cb);
                scope.each(function (){
                    /*this.addEventListener(ev, function(e) {
                        cb.call(scope, e, e.detail, this);
                    }, false);*/
                    this.addEventListener(ev, cb, false);
                });
            });
            return this;
        };
        this.init = function(){
            this.nodes = [];
            this.root = root || document;
            if(this.root instanceof MWElement) {
                this.root = this.root.get(0)
            }
            this._asElement = false;
            this.document =  (this.root.body ? this.root : this.root.ownerDocument);

            options = options || {};

            if(options.nodeName && options.nodeType) {
                this.nodes.push(options);
                this.node = (options);
                options = {};
                this._asElement = true;
            } else if(typeof options === 'string') {
                if(options.indexOf('<') === -1) {

                    this.nodes = Array.prototype.slice.call(this.root.querySelectorAll(options));
                    options = {};
                    this._asElement = true;
                } else if(this.settings.content instanceof MWElement) {
                    this.append(this.settings.content);
                }  else if(typeof this.settings.content === 'object') {
                    this.append(new MWElement(this.settings.content));
                }else {
                    var el = this._asdom(options);

                    this.nodes = [].slice.call(el.children);
                    this._asElement = true;
                }
            }

            options = options || {};

            var defaults = {
                tag: 'div',
                props: {}
            };

            this.settings = $.extend({}, defaults, options);

            if(this._asElement) return;
            this.create();
            this.setProps();
         };
        this.init();
    };
    mw.element = function(options, root){
        return new MWElement(options, root);
    };
    mw.element.module = function (name, func) {
        MWElement.prototype[name] = func;
    };

})();


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/external.js":
/*!***********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/external.js ***!
  \***********************************************************************/
/***/ (() => {

    mw.tools.externalInstrument = {
        register: {},
        holder: function () {
            var div = document.createElement('div');
            div.className = 'mw-external-tool';
            return div;
        },
        prepare: function (name, params) {
            var frame = document.createElement('iframe');
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
            };
            return frame;
        },
        init: function (name, callback, holder, params) {
            if (typeof mw.tools.externalInstrument.register[name] === 'undefined') {
                var frame = mw.tools.externalInstrument.prepare(name, params);
                frame.height = 300;
                mw.tools.externalInstrument.register[name] = frame;
                if (!holder) {
                    holder = mw.tools.externalInstrument.holder();
                    mw.$(document.body).append(holder);
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
    };

    mw.tools.external = function (name, callback, holder, params) {
        return mw.tools.externalInstrument.init(name, callback, holder, params);
    };

    mw.tools._external = function (o) {
        return mw.tools.external(o.name, o.callback, o.holder, o.params);
    };



/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/extradata-form.js":
/*!*****************************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/extradata-form.js ***!
  \*****************************************************************************/
/***/ (() => {

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

mw.extradataForm = function (options, data, func) {
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
                var when = form.$beforepost ? form.$beforepost : function () {};
                $.when(when()).then(function() {
                    var exdata = mw.serializeFields(form);
                    if(typeof options.data === 'string'){
                        var params = {};
                        options.data.split('&').forEach(function(a){
                            var c = a.split('=');
                            params[c[0]] = decodeURIComponent(c[1]);
                        });
                        options.data = params;
                    }
                    var isFormData = options.data.constructor.name === 'FormData';
                    if(isFormData) {
                        for (var i in exdata) {
                            options.data.set(i, exdata[i]);
                        }

                    } else {
                        for (var i in exdata) {
                            options.data[i] = exdata[i];
                        }
                    }
                    if(func) {
                        func(options);

                    } else {
                        mw.ajax(options);

                    }
                    form.__modal.remove();
                })



            });
        }
    });
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/helpers.js":
/*!**********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/helpers.js ***!
  \**********************************************************************/
/***/ (() => {

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
            typeof callback === 'function' ? callback.call(who) : '';
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
        has: function (el, what) {
            return el.querySelector(what) !== null;
        },

        image_info: function (a, callback) {
            var img = document.createElement('img');
            img.src = a.src;
            img.onload = function () {
                callback.call({width: img.naturalWidth, height: img.naturalHeight});
                img.remove();
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
            mw.$(n1).on(events, function () {
                n2[setValue2] = n1[setValue1];
                mw.$(n2).trigger('change');
            });
            mw.$(n2).on(events, function () {
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
                    mw.$(to).on(this.type, this.handler);
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


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/images.js":
/*!*********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/images.js ***!
  \*********************************************************************/
/***/ (() => {

mw.image = {

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
            img = new Image();
        }
        else {
            img = document.createElement('img')
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
        document.body.appendChild(img);
    },

};


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/jquery.tools.js":
/*!***************************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/jquery.tools.js ***!
  \***************************************************************************/
/***/ (() => {


$.fn.dataset = function (dataset, val) {
    var el = this[0];
    if (el === undefined) return false;
    var _dataset = !dataset.contains('-') ? dataset : mw.tools.toCamelCase(dataset);
    if (!val) {
        dataset = !!el.dataset ? el.dataset[_dataset] : mw.$(el).attr("data-" + dataset);
        return dataset !== undefined ? dataset : "";
    }
    else {
        !!el.dataset ? el.dataset[_dataset] = val : mw.$(el).attr("data-" + dataset, val);
        return mw.$(el);
    }
};

$.fn.size = function () {
    return this.length;
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
    var isTemplate = el.nodeName === 'TEMPLATE';

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
            el._dialog.remove();
        }
        return;
    }
    $(el).before('<mw-dialog-temp id="'+idEl+'"></mw-dialog-temp>');
    var dialog = mw.dialog(settings);
    el._dialog = dialog;
    if(isTemplate) {
        dialog.dialogContainer.innerHTML = el.innerHTML;

    } else {
        dialog.dialogContainer.appendChild(el);

    }
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


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/loading.js":
/*!**********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/loading.js ***!
  \**********************************************************************/
/***/ (() => {

mw.tools.progressDefaults = {
    skin: 'mw-ui-progress',
    action: mw.msg.loading + '...',
    progress: 0
};

mw.tools.progress = function (obj) {
    if (typeof obj.element === 'string') {
        obj.element = mw.$(obj.element)[0];
    }
    if (obj.element === null || !obj.element) return false;
    if (obj.element.querySelector('.mw-ui-progress-bar')) {
        obj.element.progressOptions.show()
        return obj.element.progressOptions;
    }
    obj = $.extend({}, mw.tools.progressDefaults, obj);
    if(obj.progress > 100 ) {
        obj.progress = 100;
    }
    if(obj.progress < 0 ) {
        obj.progress = 0;
    }
    var progress = document.createElement('div');
    progress.className = obj.skin;
    progress.innerHTML = '<div class="mw-ui-progress-bar" style="width: ' + obj.progress + '%;"></div><div class="mw-ui-progress-info">' + obj.action + '</div><span class="mw-ui-progress-percent">'+obj.progress+'%</span>';
    progress.progressInfo = obj;
    var options = {
        progress: progress,
        show: function () {
            this.progress.style.display = '';
        },
        hide: function () {
            this.progress.style.display = 'none';
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
            progress.progressInfo.element.progressOptions.show();
        }
    };
    progress.progressOptions = obj.element.progressOptions = options;
    obj.element.appendChild(progress);
    return options;
};
mw.progress = mw.tools.progress;


mw.tools.loading = function (element, progress, speed) {
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
        element = document.body;
    }
    if (typeof element === 'number') {
        progress = element;
        element = document.body;
    }
    if (element === document || element === document.documentElement) {
        element = document.body;
    }
    element = mw.$(element)[0];
    if (element === null || !element) return false;
    if (element.__loadingTime) {
        clearTimeout(element.__loadingTime);
    }

     var el = element.querySelector('.mw-progress');

    if (!el) {
        el = document.createElement('div');
        el.className = 'mw-progress';
        el.innerHTML = '<div class="mw-progress-index"></div>';
        if (element === document.body) el.style.position = 'fixed';
        element.appendChild(el);
    }
    if (progress === 'hide') {
        el.remove();
        return;
    }

    var pos = getComputedStyle(element).position;
    if (pos === 'static') {
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
};


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/loops.js":
/*!********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/loops.js ***!
  \********************************************************************/
/***/ (() => {

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


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/notification.js":
/*!***************************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/notification.js ***!
  \***************************************************************************/
/***/ (() => {



var errorsHandlePrev = [];
mw.errorsHandle = function (obj) {
    while (errorsHandlePrev.length) {
        errorsHandlePrev[errorsHandlePrev.length-1].remove();
        errorsHandlePrev.pop();
    }

    if(!obj) return;
    if(obj.status === 401) {

        mw.session.checkPause = false;
        mw.session.checkPauseExplicitly = false;
        mw.session.logRequest();

    }
    obj.errors = obj.errors || obj.form_errors;
    if(obj.errors) {
        var html = [];
        for (var key in obj.errors) {
            var bsel = document.querySelector('.form-control[name="' + key + '"]');
             if(!bsel) {
                var err = obj.errors[key].map ? obj.errors[key][0] : obj.errors[key];
                html.push(err);
            } else if ( bsel ) {
                var next = bsel.nextElementSibling;
                if (!next || !next.classList.contains('invalid-feedback')) {
                    next = document.createElement('div');
                    next.classList.add('invalid-feedback');
                    bsel.parentNode.insertBefore(next, bsel.nextSibling);
                    errorsHandlePrev.push(next);
                }
                next.style.display = 'block';
                next.innerHTML = obj.errors[key];
            }
        }
        if (html.length) {
            mw.notification.warning(html.join('<br>'))
        }
    }
    if (obj.errors && obj.message) {
        mw.notification.warning(obj.message);
    }
};
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
                    mw.alert(data.success);
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
        var div = document.createElement('div');
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
        name = name || 'default';
        name = 'mw-notification-id-' + name;
        if(document.getElementById(name)) {
            document.getElementById(name).remove();
        }
        timeout = timeout || 1000;
        var div = mw.notification.build(type, text, name);
        if (typeof mw.notification._holder === 'undefined') {
            mw.notification._holder = document.createElement('div');
            mw.notification._holder.id = 'mw-notifications-holder';
            document.body.appendChild(mw.notification._holder);
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


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/objects.js":
/*!**********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/objects.js ***!
  \**********************************************************************/
/***/ (() => {

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


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/system-dialogs.js":
/*!*****************************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/system-dialogs.js ***!
  \*****************************************************************************/
/***/ (() => {

mw.tools.alert = function (text) {
    var html = ''
        + '<table class="mw_alert" width="100%" height="140" cellpadding="0" cellspacing="0">'
        + '<tr>'
        + '<td align="center" valign="middle"><div class="mw-alert-holder">' + text + '</div></td>'
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
        mw.$("#mw_alert .mw-alert-holder").html(text);
        return mw.$("#mw_alert")[0].modal;
    }
};

mw.alert = mw.tools.alert;


mw.tools.prompt = function (q, callback) {
    if(!q) return ;
     var input = document.createElement('input');
    input.className = 'mw-ui-field w100';


    var question = mw.$('<div class="mw-prompt-input-container"><label class="mw-ui-label">'+q+'</label></div>');
    question.append(input)
    var footer = mw.$('<div class="mw-prompt-input-footer">');
    var ok = mw.$('<button type="button" disabled class="mw-ui-btn mw-ui-btn-info">'+mw.lang('OK')+'</button>');
    var cancel = mw.$('<span class="mw-ui-btn">'+mw.lang('Cancel')+'</span>');
    footer.append(cancel);
    footer.append(ok);
    var dialog = mw.dialog({
        content: question,
        title: q,
        footer: footer
    });
    ok.on('click', function (){
        callback.call(window, input.value);
        dialog.remove();
    });
    cancel.on('click', function (){
        dialog.remove();
    });
    input.focus();
    input.oninput = function () {
        var val = this.value.trim();
        ok[0].disabled = !val;
    };
    input.onkeydown = function (e) {
        if (mw.event.is.enter(e)) {
            var val = this.value.trim();
            if (val) {
                callback.call(window, input.value);
                dialog.remove();
            }

        }
    };

    return dialog;
};
mw.prompt = mw.tools.prompt;
mw.tools.confirm = function (question, callback, onCancel) {
    if(typeof question === 'function') {
        callback = question;
        question = 'Are you sure?';
    }
    question = question || 'Are you sure?';
        var html = ''
            + '<table class="mw_alert" width="100%" height="140" cellpadding="0" cellspacing="0">'
            + '<tr>'
            + '<td valign="middle"><div class="mw-alert-holder">' + question + '</div></td>'
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
            mw.$("#mw_confirm_modal .mw-alert-holder").html(question);
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
            if(onCancel) {
                onCancel.call()
            }
            modal.remove();
        });
        ok.on('click', function () {
            callback.call(window);
            setTimeout(function () {
                modal.remove();
            }, 78);
        });
        setTimeout(function () {
            mw.$(ok).focus();
        }, 120);
        return modal;
    };
mw.confirm = mw.tools.confirm;


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/tabs.js":
/*!*******************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/tabs.js ***!
  \*******************************************************************/
/***/ (() => {

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
    element = element || document.body;
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


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/core-tools/tooltip.js":
/*!**********************************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/core-tools/tooltip.js ***!
  \**********************************************************************/
/***/ (() => {

(function(){

    var ToolTip = function (options) {
        options = options || {};

        var defaults = {
            template: 'default',
            overlay: false,
            position: 'bottom-center'
        };

        var scope = this;

        this.settings = mw.object.extend({}, defaults, options);
        if (this.settings.skin ) {
            this.settings.template = this.settings.skin;
        }

        var create = function () {
            var tpl = scope.settings.template.indexOf('mw-tooltip') === 0 ? scope.settings.template : 'mw-tooltip  mw-tooltip-' + scope.settings.template;
            tpl += ' mw-tooltip-widget';
            scope.tooltip = mw.element({
                tag: 'div',
                props: {
                    className: tpl,
                    id: scope.settings.id || mw.id('mw-tooltip-')
                }
            });
            scope.tooltip.get(0)._mwtooltip = scope;
            if ( scope.settings.overlay) {
                scope.overlay = mw.element({
                    tag: 'div',
                    props: {
                        className: 'mw-tooltip-overlay',
                    }
                });
                scope.overlay.on('mousedown touchstart', function (){
                    scope.remove()
                });
            }
            mw.element('body')
                .append(scope.overlay)
                .append(scope.tooltip);
            scope.content(scope.settings.content);
        };

        var _e = {};

        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };

        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

        this.content = function (content) {
          if (typeof content === 'undefined') {
              return scope.tooltip.innerHTML;
          }
          scope.tooltip.html(content || '') ;
        };

        this.remove = function () {
            this.tooltip.remove();
            if (this.overlay) {
                this.overlay.remove();
            }
             this.dispatch('removed');
        };
        this.show = function () {
            this.tooltip.show();
            if (this.overlay) {
                this.overlay.show();
            }
            this.dispatch('show');
        };
        this.hide = function () {
            this.tooltip.hide();
            if (this.overlay) {
                this.overlay.hide();
            }
            this.dispatch('hide');
        };

        this._position = null;
        this.position = function (position, target) {
            position = position || this._position || this.settings.position;
            if (target) {
                scope.settings.element = target;
            }
            var el = mw.element(scope.settings.element);
            if (el.length === 0) {
                return false;
            }
            var tooltip = this.tooltip.get(0);
            var w = el.outerWidth(),
                tipwidth = tooltip.offsetWidth,
                h = el.outerHeight(),
                tipheight = tooltip.offsetHeight,
                off = el.offset();
            if (off.top === 0 && off.left === 0) {
                off = el.parent().offset();
            }
            mw.tools.removeClass(tooltip, this._position);
            mw.tools.addClass(tooltip, position);
            this._position = position


            off.left = off.left > 0 ? off.left : 0;
            off.top = off.top > 0 ? off.top : 0;

            var leftCenter = off.left - tipwidth / 2 + w / 2;
            leftCenter = leftCenter > 0 ? leftCenter : 0;

            if (position === 'bottom-left') {
                mw.$(tooltip).css({
                    top: off.top + h,
                    left: off.left
                });
            }
            else if (position === 'bottom-center') {
                mw.$(tooltip).css({
                    top: off.top + h,
                    left: leftCenter
                });
            }
            else if (position === 'bottom-right') {
                mw.$(tooltip).css({
                    top: off.top + h,
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
                    left: off.left + w
                });
            }
            else if (position === 'right-bottom') {
                mw.$(tooltip).css({
                    top: (off.top + h) - tipheight,
                    left: off.left + w
                });
            }
            else if (position === 'right-center') {
                mw.$(tooltip).css({
                    top: off.top - tipheight / 2 + h / 2,
                    left: off.left + w
                });
            }
            if (parseFloat($(tooltip).css('top')) < 0) {
                mw.$(tooltip).css('top', 0);
            }
            this.show();
        };

        var init = function () {
            create();
            scope.position();
            scope.show();
        };

        init();

    };

    mw.ToolTip = ToolTip;


    var tooltip = {
        source: function (content, skin, position, id) {
            if (skin === 'dark') {
                skin = 'mw-tooltip-dark';
            } else if (skin === 'warning') {
                skin = 'mw-tooltip-default mw-tooltip-warning';
            }
            if (typeof id === 'undefined') {
                id = 'mw-tooltip-' + mw.random();
            }
            var tooltip = document.createElement('div');
            var tooltipc = document.createElement('div');
            tooltip.className = 'mw-tooltip ' + position + ' ' + skin;
            tooltipc.className = 'mw-tooltip-content';
            tooltip.id = id;
            $(tooltipc).append(content);
            $(tooltip).append(tooltipc).append('<span class="mw-tooltip-arrow"></span>');
            document.body.appendChild(tooltip);
            return tooltip;
        },
        setPosition: function (tooltip, el, position) {
            if(!el || el.length === 0) return;
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
                if (!off) {
                    console.log(el);
                    return ;
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
                        xPos = 'left'
                    } else {
                        xPos = 'right'
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
                    overlay: o.overlay,
                    content: o.content,
                    group: o.group
                }
            },
            init: function (o, wl) {

                var orig_options = o;
                o = mw.tools.tooltip.prepare(o);
                if (o === false) return false;
                var tip;
                if (o.id && mw.$('#' + o.id).length > 0) {
                    tip = mw.$('#' + o.id)[0];
                } else {
                    tip = mw.tools.tooltip.source(o.content, o.skin, o.position, o.id);
                }

                if(o.overlay) {
                    var overlay = $('<div class="mw-tooltip-overlay"></div>');
                    tip.tremove = function () {
                        overlay.remove();
                        tip.remove();
                    };

                    overlay.on('click', function () {
                        tip.tremove()
                    });

                    $('body').append(overlay)
                }
                tip.tooltipData = o;
                wl = wl || true;
                if (o.group) {
                    var tip_group_class = 'mw-tooltip-group-' + o.group;
                    var cur_tip = mw.$(tip)
                    if (!cur_tip.hasClass(tip_group_class)) {
                        cur_tip.addClass(tip_group_class);
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

                    if (o.group && typeof orig_options.close_on_click_outside !== 'undefined' && orig_options.close_on_click_outside) {
                        mw.$(self).bind('click', function (e, target) {
                            mw.$("." + tip_group_class).hide();
                        });
                    }
                }
                mw.tools.tooltip.setPosition(tip, o.element, o.position);
                return tip;
            }

    };

    mw.tooltip = function (config) {
        return mw.tools.tooltip.init(config);
    };

    mw.tools.tooltip = tooltip;
    var TTTime = null;
    mw.tools.titleTip = function (el, _titleTip) {
        clearTimeout(TTTime);
        mw.$(mw.tools[_titleTip]).hide();
        TTTime = setTimeout(function (){
        _titleTip = _titleTip || '_titleTip';
        if (mw.tools.hasClass(el, 'tip-disabled')) {
            mw.$(mw.tools[_titleTip]).hide();
            return false;
        }
        var skin = mw.$(el).attr('data-tipskin');
        skin = (skin) ? skin : 'mw-tooltip-dark';
        var pos = mw.$(el).attr('data-tipposition');
        var iscircle = mw.$(el).attr('data-tipcircle') === 'true';
        if (!pos) {
            pos = 'top-center';
        }
        var text = mw.$(el).attr('data-tip');
        if (!text) {
            text = mw.$(el).attr('title');
        }
        if (!text) {
            text = mw.$(el).attr('tip');
        }
        if (typeof text === 'undefined' || !text) {
            return;
        }
        if (text.indexOf('.') === 0 || text.indexOf('#') === 0) {
            var xitem = mw.$(text);
            if (xitem.length === 0) {
                return false;
            }
            text = xitem.html();
        }
        else {
            text = text.replace(/\n/g, '<br>');
        }
        var showon = mw.$(el).attr('data-showon');
        if (showon) {
            el = mw.$(showon)[0];
        }
        if (!mw.tools[_titleTip]) {
            mw.tools[_titleTip] = mw.tooltip({skin: skin, element: el, position: pos, content: text});
            mw.$(mw.tools[_titleTip]).addClass('mw-universal-tooltip');
        }
        else {
            mw.tools[_titleTip].className = 'mw-tooltip ' + pos + ' ' + skin + ' mw-universal-tooltip';
            mw.$('.mw-tooltip-content', mw.tools[_titleTip]).html(text);
            mw.tools.tooltip.setPosition(mw.tools[_titleTip], el, pos);
        }
        if (iscircle) {
            mw.$(mw.tools[_titleTip]).addClass('mw-tooltip-circle');
        }
        else {
            mw.$(mw.tools[_titleTip]).removeClass('mw-tooltip-circle');
        }
        mw.$(mw.tools[_titleTip]).show();
        }, 500)
    };

})();


/***/ }),

/***/ "./userfiles/modules/microweber/api/tools/images.js":
/*!**********************************************************!*\
  !*** ./userfiles/modules/microweber/api/tools/images.js ***!
  \**********************************************************/
/***/ (() => {

mw.image = {
    isResizing: false,
    currentResizing: null,
    resize: {
        create_resizer: function () {
            if (!mw.image_resizer) {
                var resizer = document.createElement('div');
                resizer.className = 'mw-defaults mw_image_resizer';
                resizer.innerHTML = '<div id="image-edit-nav"><span onclick="mw.wysiwyg.media(\'#editimage\');" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert mw-ui-btn-icon image_change tip" data-tip="' + mw.msg.change + '"><span class="mdi mdi-image mdi-18px"></span></span><span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert mw-ui-btn-icon tip image_change" id="image-settings-button" data-tip="' + mw.msg.edit + '" onclick="mw.image.settings();"><span class="mdi mdi-pencil mdi-18px"></span></span></div>';
                document.body.appendChild(resizer);
                mw.image_resizer = resizer;
                mw.image_resizer_time = null;
                mw.image_resizer._show = function () {
                    clearTimeout(mw.image_resizer_time)
                    mw.$(mw.image_resizer).addClass('active')
                };
                mw.image_resizer._hide = function () {
                    clearTimeout(mw.image_resizer_time)
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
            selectImage = typeof selectImage === 'undefined' ? true : selectImage;
            /*  var order = mw.tools.parentsOrder(el, ['edit', 'module']);
             if(!(order.module > -1 && order.edit > order.module) && order.edit>-1){   */


            mw.$('.ui-resizable-handle', mw.image_resizer)[el.nodeName == 'IMG' ? 'show' : 'hide']()

            el = mw.$(el);
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
            if (document.getElementById('image-settings-button') !== null) {
                if (!!el[0].src && el[0].src.contains('userfiles/media/pixum/')) {
                    document.getElementById('image-settings-button').style.display = 'none';
                }
                else {
                    document.getElementById('image-settings-button').style.display = '';
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

    _isrotating: false,
    rotate: function (img_object, angle) {
        if (!mw.image.Rotator) {
            mw.image.Rotator = document.createElement('canvas');
            mw.image.Rotator.style.top = '-9999px';
            mw.image.Rotator.style.left = '-9999px';
            mw.image.Rotator.style.position = 'absolute';
            mw.image.RotatorContext = mw.image.Rotator.getContext('2d');
            document.body.appendChild(mw.image.Rotator);
        }
        if (!mw.image._isrotating) {
            mw.image._isrotating = true;
            img_object = img_object || document.querySelector("img.element-current");
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
        var node = node || document.querySelector("img.element-current");
        if (node === null) {
            return false;
        }
        mw.image.preload(node.src, function () {
            var canvas = document.createElement('canvas');
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
        var node = node || document.querySelector("img.element-current");
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
    preloadForAll: function (array, eachCall, callback) {
        var size = array.length, i = 0, count = 0;
        for (; i < size; i++) {
            mw.image.preload(array[i], function (imgWidth, imgHeight) {
                count++;
                if(eachCall) {
                    eachCall.call(this, imgWidth, imgHeight)
                }
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
            img = document.createElement('img')
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
        document.body.appendChild(img);
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


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/@core.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/ajax.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/common.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/components.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/custom_fields.js");
/******/ 	// This entry module is referenced by other modules so it can't be inlined
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/events.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/files.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/fonts.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/forms.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/i18n.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/libs.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/modules.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/mw-require.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/options.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/polyfills.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/response.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/session.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/shop.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/upgrades.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/uploader.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/core/url.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/system/color.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/system/css_parser.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/system/filepicker.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/system/icon_selector.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/system/module_settings.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/system/prop_editor.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/system/state.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/@tools.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/common-extend.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/common.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/cookie.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/domhelpers.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/dropdown.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/element.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/external.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/extradata-form.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/helpers.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/images.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/jquery.tools.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/loading.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/loops.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/notification.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/objects.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/system-dialogs.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/tabs.js");
/******/ 	__webpack_require__("./userfiles/modules/microweber/api/tools/core-tools/tooltip.js");
/******/ 	var __webpack_exports__ = __webpack_require__("./userfiles/modules/microweber/api/tools/images.js");
/******/ 	
/******/ })()
;
//# sourceMappingURL=site.js.map
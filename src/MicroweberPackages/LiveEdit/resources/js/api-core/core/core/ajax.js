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

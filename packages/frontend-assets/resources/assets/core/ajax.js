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
        document.head.insertBefore(el, document.head.firstChild);
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

                callback.call(this);
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

mw.reload_module = async function(module, callback) {

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

    const refreshLiveWireModule = async module => {
        const component = Livewire.find(module.getAttribute('wire:id'));
        await component.$refresh();
    }


    if (typeof module !== 'undefined') {
        if (typeof module === 'object') {

            if(module.getAttribute('wire:id')) {
                await refreshLiveWireModule(module);
                done.call();
            } else{
                mw._({
                    selector: module,
                    done: done
                });
            }


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



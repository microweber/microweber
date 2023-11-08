
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

                mw.tools.scrollTo('#' + id)
            }, config);
        });
    }
};

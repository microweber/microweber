mw.run = function (c, options) {
    return new mw._Classes[c](options);
};

mw._Classes = {};

mw.CreateClass = function (object) {
    object = object || {};
    var defaults = {
        name: mw.id('class:'),
        options: {},
        require: [],
        build: function () {

        },
        ready: function () {

        }
    };
    this.settings = $.extend({}, defaults, object);
    var scope = this;

    mw.getScripts(this.settings.require, function () {
        mw._Classes[scope.settings.name] = scope.settings.build;
        scope.settings.ready.call();
    });
};

mw.require('uploader.js')


mw.filePicker = function (options) {
    options = options || {};
    var scope = this;
    var defaults = {
        components: [
            {type: 'url', label: mw.lang('URL')},
            {type: 'desktop', label: mw.lang('My computer')},
            {type: 'server', label: mw.lang('Uploaded')},
            {type: 'library', label: mw.lang('Media library')}
        ],
        nav: 'tabs', // 'tabs | 'dropdown',
        element: null
    };

    this.$root = $('<div class="card mb-3 mw-filepicker-root"></div>');

    this.settings = $.extend({}, defaults, options);

    this.components = {
        _$inputWrapper: function (label) {
            var html = '<div class="form-group">' +
                '<label>' + label + '</label>' +
                '</div>';
            return mw.$(html);
        },
        url: function () {
            var $input = $('<input class="form-control" placeholder="http://example.com/image.jpg">');
            var $wrap = this._$inputWrapper(scope._getComponentObject('url').label);
            $wrap.append($input);
            return $wrap[0];
        },
        desktop: function () {
            var $wrap = this._$inputWrapper(scope._getComponentObject('desktop').label);
            scope.uploader = mw.upload({
                element: $wrap
            });
            return $wrap[0];
        }
    };


    this.settings.components = this.settings.components.filter(function (item) {
        return !!scope.components[item.type];
    });


    this._navigation = null;

    this.navigation = function () {
        this._navigationHolder = document.createElement('div');
        if(this.settings.nav === 'tabs') {
            var ul = $('<ul class="nav nav-tabs"/>');
            this.settings.components.forEach(function (item) {
                ul.append('<li class="nav-item"><a class="nav-link active" href="#">'+item.label+'</a></li>')
            });
            this._navigationHolder.appendChild(ul[0]);
        } else if(this.settings.nav === 'dropdown') {
            var select = $('<select class="selectpicker form-control"/>');
            this.settings.components.forEach(function (item) {
                select.append('<option class="nav-item">'+item.label+'</option>');
            });
            this._navigationHolder.appendChild(select[0]);
        }
        this.$root.prepend(this._navigationHolder);

    };




    this.footer = function (type) {

    };
    
    this._getComponentObject = function (type) {
        return this.settings.components.find(function (comp) {
            return comp.type && comp.type === type;
        });
    };

    this.buildComponentSection = function (component) {
        var main = mw.$('<div class="card-body mw-filepicker-component-section"></div>');
        this.$root.append(main);
        return main;
    };
    this.buildComponent = function (component) {
        if(this.components[component.type]) {
            return this.components[component.type]()
        }
    };

    this.buildComponents = function () {
        $.each(this.settings.components, function () {
            var component = scope.buildComponent(this);
            if(component){
                var sec = scope.buildComponentSection()
                sec.append(component);
            }
        });
    };

    this.build = function () {
        this.navigation();
        this.buildComponents();
    };

    this.init = function () {
        this.build();
        if(this.settings.element){
            $(this.settings.element).eq(0).append(this.$root);
        }

    };

    this.init()
};

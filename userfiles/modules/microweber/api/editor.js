mw.Editor = function (options) {
    var defaults = {
        regions: '.edit',
        document: document,
        executionDocument: document,
        mode: 'frame', // frame | inline | overall | bubble
        controls: [
            ['bold', 'italic', '|'],
            ['bold', 'italic', '|']
        ],
        scripts: [],
        cssFiles: [],
        value: '',
        url: null
    };

    var requiredScripts = [ ];

    options = options || {};

    this.settings = $.extend({}, defaults, options);

    this.document = this.settings.document;

    if(!this.settings.selector){
        console.warn('mw.Editor - selector not specified');
        return;
    }

    this.createFrame = function () {
        this.frame = this.document.createElement('iframe');
        this.frame.className = 'mw-editor-frame';
        if (this.settings.url) {
            this.frame.src = this.settings.url;
        }
    };

    this.createWrapper = function () {
        this.wrapper = this.document.createElement('span');
        this.wrapper.className = 'mw-editor-wrapper';
    };

    this.createArea = function () {

    };

    this.createBar = function () {
        this.bar = mw.bar();
        for (var i1 = 0; i1 < this.settings.controls.length; i1++) {
            var item = this.settings.controls[i1];
            this.bar.createRow();
            for (var i2 = 0; i2 < item.length; i2++) {
                if(this.controllers[item[i2]]){
                    this.bar.add(this.controllers[item[i2]], i1);
                }
            }
        }
        this.wrapper.appendChild(this.bar.bar)
    };

    this.init = function () {
        this.controllers = mw.Editor.controllers;
        this.createWrapper();
        this.createBar();
        mw.$(this.settings.selector).append(this.wrapper);
    };
    this.init();



};

/*
* <div class="mw-dropdown mw-dropdown-default">
                    <span class="mw-dropdown-value mw-ui-btn mw-dropdown-val">Choose</span>
                    <div class="mw-dropdown-content">
                        <ul>
                            <li value="1">Option 1</li>
                            <li value="2">Option 2 !!!</li>
                            <li value="3">Option 3</li>
                        </ul>
                    </div>
                </div>
* */

mw.Editor.core = {
    dropdown: function (options) {
        /*
        * data: [
        *   {label: string, value: any}
        * ]
        * */

        options = options || {};

        if (!options.data) {
            options.data = [];
        }

        var defaults = {
            placeholder: mw.lang('Select')
        }

        this.settings = $.extend({}, defaults, options);

        this.createNodes = function () {
            this.rootNode = mw.element({
                tag: 'div',
                props: { className: 'mw-dropdown mw-dropdown-default' }
            });
            this.contentNode = mw.element({
                tag: 'div',
                props: { className: 'mw-dropdown-content' }
            });

            this.listNode = mw.element({
                tag: 'ul'
            });

            this.valueNode = mw.element({
                tag: 'span',
                props: { className: 'mw-dropdown-value mw-ui-btn mw-dropdown-val', innerHTML: options.innerHTML }
            });
        };

        this.nodes = function () {
            for (var i =0; i < this.settings.data.length; i++) {
                var item = this.settings.data[i];
                var node = mw.element({
                    tag: 'li',
                    props: { innerHTML: options.innerHTML, value: item.value  }
                });
            }
        };


        this.init = function () {
            this.createNodes();
        };

        root.add();
    }
};
mw.Editor.controllers = {
    '|' : function () {
        return mw.element({
            tage: 'span',
            props: { className: 'mw-bar-delimiter' }
        });
    },
    bold: function () {
        var scope = this;
        var el = mw.element({
            tag: 'span',
            props: { className: 'mw-ui-btn', innerHTML: 'bold' }
        });
        el.$node.on('click', function () {
            console.log(scope)
        });
        return el;
    }
};


(function(){
    var Bar = function(options) {

        options = options || {};
        var defaults = {
            document: document,
            register: null
        };
        this.settings = $.extend({}, defaults, options);
        this.document = this.settings.document || document;

        this._register = [];
        this.register = [];

        this.delimiter = function(){
            var el = this.document.createElement('span');
            el.className = 'mw-bar-delimiter';
            return el;
        };

        this.create = function(){
            this.bar = this.document.createElement('div');
            this.bar.className = 'mw-bar';
        };

        this.rows = [];

        this.createRow = function () {
            var row = this.document.createElement('div');
            row.className = 'mw-bar-row';
            this.rows.push(row);
            this.bar.appendChild(row);
        };

        this.add = function (what, row) {
            row = row || 0;
            if(!this.rows[row]) {
                return;
            }
            if(what === '|') {
                this.rows[row].appendChild(this.delimiter());
            } else if(typeof what === 'function') {
                this.rows[row].appendChild(what().node);
            }
        };

        this.init = function(){
            this.create();
        };
        this.init();
    };
    mw.bar = function(options){
        return new Bar(options);
    };
})();

(function(){
    var Element = function(options){

        options = options || {};

        var defaults = {
            tag: 'div',
            props: {},
            document: document,
            register: null
        };

        this.settings = $.extend({}, defaults, options);

        this.document = this.settings.document || document;

        this.register = function(){
            if(this.settings.register) {
                var reg = this.settings.register;
                reg.push(this);
            }
        };

        this.create = function(){
            this.node = this.document.createElement(this.settings.tag);
            this.$node = $(this.node);
        };

        this.setProps = function(){
            for(var i in this.settings.props) {
                this.node[i] = this.settings.props[i];
            }
        };

        this.prop = function(prop, val){
            if(this.node[prop] !== val){
                this.node[prop] = val;
                this.$node.trigger('propChange', [prop, val, Element]);
            }
        };

        this.append = function(Element){
            this.node.appendChild(Element.node || Element);
            this.$node.trigger('append', [Element]);
            return this;
        };

        this.init = function(){
            this.create();
            this.setProps();
            this.register();
        };
        this.init();
    };
    mw.element = function(options){
        return new Element(options);
    };
})();

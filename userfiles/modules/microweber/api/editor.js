mw.Editor = function (options) {
    var defaults = {
        regions: '.edit',
        document: document,
        executionDocument: document,
        mode: 'frame', // frame | inline | overall | bubble
        controls: [
            ['bold', '|', 'italic'],
            ['bold', '|', 'italic'  ]
        ],
        scripts: [],
        cssFiles: [],
        value: '',
        url: null,
        skin: 'default',
        stateManager: null,
        iframeAreaSelector: null,
        activeClass: 'mw-ui-btn-info'
    };

    this.actionWindow = window;

    var requiredScripts = [

    ];

    options = options || {};

    this.settings = $.extend({}, defaults, options);

    this.document = this.settings.document;

    var scope = this;

    if(!this.settings.selector){
        console.warn('mw.Editor - selector not specified');
        return;
    }

    this.selection = getSelection();

    this._interactionTime = new Date().getTime();
    this.initInteraction = function () {
        var max = 78;
        mw.$(scope.settings.iframeAreaSelector, scope.actionWindow.document).on('touchstart touchend click keydown execCommand', function(){
            var time = new Date().getTime();
            if((time - scope._interactionTime) > max){
                scope._interactionTime = time;
                scope.selection = scope.actionWindow.getSelection();
                var target = mw.wysiwyg.validateCommonAncestorContainer( scope.selection.getRangeAt(0).commonAncestorContainer);
                var css = mw.CSSParser(target);
                var api = scope.api;
                scope.controls.forEach(function (ctrl) {
                    ctrl.checkSelection(scope.selection, ctrl, target, css.get, css.css, api, scope);
                });
            }
        });

    };

    this.controllerActive = function (node, active) {
        node.classList[active ? 'add' : 'remove'](this.settings.activeClass);
    };

    this.createFrame = function () {
        this.frame = this.document.createElement('iframe');
        this.frame.className = 'mw-editor-frame';
        this.frame.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
        this.frame.allowFullscreen = true;
        this.frame.scrolling = "yes";
        this.frame.width = "100%";
        this.frame.frameBorder = "0";
        if (this.settings.url) {
            this.frame.src = this.settings.url;
        } else {

        }
        mw.tools.iframeAutoHeight(this.frame, 'now');
        $(this.frame).on('load', function () {
            if (!scope.settings.iframeAreaSelector) {
                var area = document.createElement('div');
                area.style.outline = 'none';
                area.className = 'mw-editor-frame-area';
                scope.settings.iframeAreaSelector =  '.' + area.className;
                this.contentWindow.document.body.append(area);
                area.style.minHeight = '100px';
            }
            scope.$iframeArea = $(scope.settings.iframeAreaSelector, this.contentWindow.document);
            scope.$iframeArea.attr('contenteditable', true);
            scope.$iframeArea.html(scope.settings.content || '');
            scope.$iframeArea.on('input', function () {
                $(scope).trigger('change', [this.innerHTML]);
            });
            scope.actionWindow = this.contentWindow;
            $(scope).trigger('ready');
        });
        this.wrapper.appendChild(this.frame);
    };

    this.createWrapper = function () {
        this.wrapper = this.document.createElement('div');
        this.wrapper.className = 'mw-editor-wrapper mw-editor-' + this.settings.skin;
    };

    this.createArea = function () {
        this.area = mw.element({
            props: { className: 'mw-editor-area', innerHTML: this.settings.content || ''}
        });
        this.area.node.contentEditable = true;
        this.area.node.oninput = function() {
            $(scope).trigger('change', [this.innerHTML]);
        };
        this.wrapper.appendChild(this.area.node);
        $(scope).trigger('ready')
    };

    this.setContent = function (content, trigger) {
        if(typeof trigger === 'undefined'){
            trigger = true;
        }
        this.area.$node.html(content);
        if(trigger){
            $(this).trigger('change', [content]);
        }
    };

    this.nativeElement = function (node) {
        return node.node ? node.node : node;
    };

    this.controls = [];
    this.api = {
        isSelectionEditable: function (sel) {
            try {
                var node = (sel || scope.actionWindow.getSelection()).focusNode;
                if (node === null) {
                    return false;
                }
                if (node.nodeType === 1) {
                    return node.isContentEditable;
                }
                else {
                    return node.parentNode.isContentEditable;
                }
            }
            catch (e) {
                return false;
            }
        },
        execCommand: function (a, b, c) {
            scope.actionWindow.document.execCommand('styleWithCss', 'false', false);
            var sel = scope.actionWindow. getSelection();
            try {  // 0x80004005
                if (scope.actionWindow.document.queryCommandSupported(a) && this.isSelectionEditable()) {
                    b = b || false;
                    c = c || false;
                    if (sel.rangeCount > 0 && mw.wysiwyg.execCommandFilter(a, b, c)) {
                        scope.actionWindow.document.execCommand(a, b, c);
                        mw.$(scope.settings.iframeAreaSelector, scope.actionWindow.document).trigger('execCommand')
                    }
                }
            }
            catch (e) {
            }
        },
    }

    this.addController = function (name, row) {
        row = typeof row !== 'undefined' ? row :  this.settings.controls.length - 1;
        if(this.controllers[name]){
            var ctrl = new this.controllers[name](scope, scope.api, scope);
            this.controls.push(ctrl);
            this.bar.add(ctrl.element, row);
        } else if(this.controllersHelpers[name]){
            this.bar.add(this.controllersHelpers[name](), row);
        }
    };

    this.createBar = function () {
        this.bar = mw.bar();
        for (var i1 = 0; i1 < this.settings.controls.length; i1++) {
            var item = this.settings.controls[i1];
            this.bar.createRow();
            for (var i2 = 0; i2 < item.length; i2++) {
                scope.addController(item[i2], i1);
            }
        }
        this.wrapper.appendChild(this.bar.bar);
    };

    this._onReady = function () {
        $(this).on('ready', function () {
            scope.initInteraction();
        });
    };
    this.init = function () {
        this.controllers = mw.Editor.controllers;
        this.controllersHelpers = mw.Editor.controllersHelpers;
        this._onReady();
        this.createWrapper();
        this.createBar();

        if (this.settings.mode === 'inline') {
            this.createArea();
        } else if (this.settings.mode === 'iframe') {
            this.createFrame();
        }
        mw.$(this.settings.selector).append(this.wrapper);
    };
    this.init();


;



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
        var scope = this;

        if (!options.data) {
            options.data = [];
        }

        var defaults = {
            placeholder: mw.lang('Select')
        };

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
            this.contentNode.node.appendChild(this.listNode.node);
            this.rootNode.node.appendChild(this.valueNode.node);
            this.rootNode.node.appendChild(this.contentNode.node);
        };

        this.nodes = function () {
            for (var i = 0; i < this.settings.data.length; i++) {
                var item = this.settings.data[i];
                var li = mw.element({
                    tag: 'li',
                    props: { innerHTML: item.label, value: item.value  }
                });
                (function (item) {
                    li.$node.on('click', function () {
                        scope.select(item);
                    });
                })(item);
                this.listNode.node.appendChild(li.node);
            }
        };

        this.getAsItem = function (item) {
            var all = this.settings.data;
            if(all.indexOf(item) !== -1) {
                return item;
            }
            for (var i = 0; i<all.length; i++){
                if( item.value && all[i].value === item.value ) {
                    return all[i];
                } else if (item === all[i].value) {
                    return all[i];
                }
            }
        };

        this.value = function (val) {
            if(typeof val === 'undefined') {
                return this._value;
            }
            var item = this.getAsItem(val);
            if(item) {
                this.valueNode.node.innerHTML = item.value;
                this._value = item;
            }

        };


        this.init = function () {
            this.createNodes();
        };

    }
};

mw.Editor.controllersHelpers = {
    '|' : function () {
        return mw.element({
            tage: 'span',
            props: {
                className: 'mw-bar-delimiter'
            }
        });
    }
};

mw.Editor.controllers = {
    bold: function (scope, api, rootScope) {
        this.render = function () {
            var scope = this;
            var el = mw.element({
                tag: 'button',
                props: {
                    className: 'mw-ui-btn',
                    innerHTML: 'bold'
                }
            });
            el.$node.on('mousedown touchstart', function (e) {
                e.preventDefault();
                api.execCommand('bold');
            });
            return el;
        };
        this.checkSelection = function (selection, scope, target, css, cssNative, api, rootScope) {
            if(css.is().bold) {
                rootScope.controllerActive(scope.element.node, true);
            } else {
                rootScope.controllerActive(scope.element.node, false);
            }
            scope.element.node.disabled = !mw.wysiwyg.isSelectionEditable(selection);
        };
        this.element = this.render();
    },
    'italic': function(scope, api, rootScope){
        this.render = function () {
            var el = mw.element({
                tag: 'button',
                props: { className: 'mw-ui-btn', innerHTML: 'italic' }
            });
            el.$node.on('mousedown touchstart', function (e) {
                e.preventDefault();
                api.execCommand('italic');
            });
            return el;
        };
        this.checkSelection = function (selection, scope, target, css, cssNative, api, rootScope) {
            scope.element.node.disabled = !mw.wysiwyg.isSelectionEditable(selection);
            if(css.is().italic) {
                rootScope.controllerActive(scope.element.node, true);
            } else {
                rootScope.controllerActive(scope.element.node, false);
            }
        };
        this.element = this.render();
    },
    underline: function () {
        this.checkSelection = function (selection, scope, target, css, cssNative) {
            scope.element.node.disabled = !mw.wysiwyg.isSelectionEditable(selection);
        };

        this.render = function () {
            var scope = this;
            var el = mw.element({
                tag: 'span',
                props: { className: 'mw-ui-btn', innerHTML: 'underline' }
            });
            el.$node.on('click', function () {

            });
            return el;
        };
        this.element = this.render();
    },

};

mw.Editor.addController = function (name, render, checkSelection) {
    if (mw.Editor.controllers[name]) {
        console.warn(name + ' already defined');
        return;
    }
    if (typeof name === 'object') {
        var obj = name;
        name = obj.name;
        render = obj.render;
        checkSelection = obj.checkSelection;
    }
    mw.Editor.controllers[name] = function () {
        this.render = render;
        this.checkSelection = checkSelection;
        this.element = this.render();
    };
};
/*
mw.Editor.addController(
    'underline',
    function () {

    }, function () {

    }
);

mw.Editor.addController({
    name: 'underline',
    render: function () {

    },
    checkSelection: function () {

    }
})*/


(function(){
    var Bar = function(options) {

        options = options || {};
        var defaults = {
            document: document,
            register: null
        };
        this.settings = $.extend({}, defaults, options);
        this.document = this.settings.document || document;

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
        this.nativeElement = function (node) {
            return node.node ? node.node : node;
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
            } else {
                var el = this.nativeElement(what);
                el.classList.add('mw-bar-control-item')
                this.rows[row].appendChild(el);
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

mw.require('state.js');
mw.require('editor/element.js');
mw.require('editor/bar.js');
mw.require('editor/api.js');
mw.require('editor/helpers.js');
mw.require('editor/core.js');
mw.require('editor/controllers.js');
mw.require('editor/add.controller.js');


var EditorPredefinedControls = {
    'default': [
        ['bold', '|', 'italic'],
        ['bold', '|', 'italic' ]
    ]
};

mw.Editor = function (options) {
    var defaults = {
        regions: null,
        document: document,
        executionDocument: document,
        mode: 'iframe', // iframe | div | document
        controls: 'default',
        scripts: [],
        cssFiles: [],
        value: '',
        url: null,
        skin: 'default',
        stateManager: null,
        iframeAreaSelector: null,
        activeClass: 'mw-ui-btn-info',
    };

    this.actionWindow = window;

    options = options || {};

    this.settings = $.extend({}, defaults, options);
    if (typeof this.settings.controls === 'string') {
        this.settings.controls = EditorPredefinedControls[this.settings.controls] || EditorPredefinedControls.default;
    }

    this.document = this.settings.document;

    var scope = this;

    if(!this.settings.selector){
        console.warn('mw.Editor - selector not specified');
        return;
    }

    this.getSelection = function () {
        return scope.actionWindow.getSelection();
    };

    this.selection = this.getSelection();

    this._interactionTime = new Date().getTime();
    this.initInteraction = function () {
        var ait = 200, currt = new Date().getTime();
        scope.$editArea.on('mousemove touchmove touchstart', function(e){
            var dt = new Date().getTime();
            if ((currt + ait) > dt)  return;
            currt = dt;
            var target = e.target;
            var component = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, ['element', 'edit', 'module']);
            var isImage = target.nodeName === 'IMG';
            var data = {
                target: target,
                component: component,
                isImage: isImage
            };
            $(scope).trigger('areaInteraction', [data]);
        });
        var max = 78;
        scope.$editArea.on('touchstart touchend click keydown execCommand', function(){
            var time = new Date().getTime();
            if((time - scope._interactionTime) > max){
                scope._interactionTime = time;
                scope.selection = scope.getSelection();
                var target = scope.api.elementNode( scope.selection.getRangeAt(0).commonAncestorContainer);
                var css = mw.CSSParser(target);
                var api = scope.api;
                scope.controls.forEach(function (ctrl) {
                    if(ctrl.checkSelection) {
                        ctrl.checkSelection({
                            selection: scope.selection,
                            controller: ctrl,
                            target: target,
                            css: css.get,
                            cssNative: css.css,
                            api: api,
                            scope: scope,
                            isEditable: scope.api.isSelectionEditable()
                        });
                    }
                });
            }
        });
    };

    this._preventEvents = [];
    this.preventEvents = function () {
        var node;
        if(this.area && this._preventEvents.indexOf(this.area.node) === -1) {
            this._preventEvents.push(this.area.node);
            node = this.area.node;
        } else if(scope.$iframeArea && this._preventEvents.indexOf(scope.$iframeArea[0]) === -1) {
            this._preventEvents.push(scope.$iframeArea[0]);
            node = scope.$iframeArea[0];
        }
        var ctrlDown = false;
        var ctrlKey = 17, vKey = 86, cKey = 67, zKey = 90;
        node.onkeydown = function (e) {
            if (e.keyCode === ctrlKey || e.keyCode === 91) {
                ctrlDown = true;
            }
            if ((ctrlDown && e.keyCode === zKey) || (ctrlDown && e.keyCode === vKey) || (ctrlDown && e.keyCode === cKey)) {
                e.preventDefault();
                return false;
            }
        };
        node.onkeyup = function(e) {
            if (e.keyCode === 17 || e.keyCode === 91) {
                ctrlDown = false;
            }
        };
    };
    this.initState = function () {
        this.state = this.settings.state || (new mw.State());
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

            scope.$iframeArea.html(scope.settings.content || '');
            scope.$iframeArea.on('input', function () {
                $(scope).trigger('change', [this.innerHTML]);
            });
            scope.actionWindow = this.contentWindow;
            scope.$editArea = scope.$iframeArea;
            mw.tools.iframeAutoHeight(scope.frame);
            if(scope.settings.regions === null) {
                scope.$iframeArea.attr('contenteditable', true);
            } else {
                $(scope.settings.regions, this.contentWindow.document).attr('contenteditable', true);
            }
            scope.preventEvents();
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
        scope.$editArea = this.area.$node;
        scope.preventEvents();
        $(scope).trigger('ready');
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
    this.api = mw._editorApi(this);

    this._addControllerGroups = [];
    this.addControllerGroup = function (obj, row) {
        var group = obj.group;
        var el = mw.element({
            props: {
                innerHTML: 'group'
            }
        });

        var icon = mw.element({
            props: {
                className: group.icon
            }
        });

        el.append(icon);
        row = typeof row !== 'undefined' ? row :  this.settings.controls.length - 1;
        group.controls.forEach(function (name) {
            if(scope.controllers[name]){
                var ctrl = new scope.controllers[name](scope, scope.api, scope);
                scope.controls.push(ctrl);
                scope.bar.add(ctrl.element, row);
            } else if(this.controllersHelpers[name]){
                scope.bar.add(this.controllersHelpers[name](), row);
            }
        });

        this._addControllerGroups.push({
            el: el,
            row: row,
            obj: obj
        });
        return el;
    };

    this.controlGroupManager = function () {
        var check = function() {
            var i = 0, l = scope._addControllerGroups.length;
            for ( ; i< l ; i++) {
                var item = scope._addControllerGroups[i];
            }
        };
        $(window).on('load resize orientationchange', function () {
            check();
        });
        check();
    };

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
                if( typeof item[i2] === 'string') {
                    scope.addController(item[i2], i1);
                } else if( typeof item[i2] === 'object') {
                    scope.addControllerGroup(item[i2], i1);
                }

            }
        }
        this.wrapper.appendChild(this.bar.bar);
    };

    this._onReady = function () {
        $(this).on('ready', function () {
            scope.initInteraction();
            if(!scope.state.hasRecords()){
                scope.state.record({
                    $initial: true,
                    target: scope.$editArea[0],
                    value: scope.$editArea[0].innerHTML
                });
            }
        });
    };

    this._initInputRecordTime = null;
    this._initInputRecord = function () {
        $(this).on('change', function (e, html) {
            clearTimeout(scope._initInputRecordTime);
            scope._initInputRecordTime = setTimeout(function () {
                scope.state.record({
                    target: scope.$editArea[0],
                    value: html
                });
            }, 600);

        });
    };

    this.init = function () {
        this.controllers = mw.Editor.controllers;
        this.controllersHelpers = mw.Editor.controllersHelpers;
        this.initState();
        this._onReady();
        this.createWrapper();
        this.createBar();
        if (this.settings.mode === 'div') {
            this.createArea();
        } else if (this.settings.mode === 'iframe') {
            this.createFrame();
        }
        this._initInputRecord();
        mw.$(this.settings.selector).append(this.wrapper)[0].mwEditor = this;
    };
    this.init();
};

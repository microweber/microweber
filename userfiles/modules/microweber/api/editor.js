
mw.require('state.js');
mw.require('editor/element.js');
mw.require('editor/bar.js');
mw.require('editor/api.js');
mw.require('editor/helpers.js');
mw.require('editor/core.js');
mw.require('editor/controllers.js');
mw.require('editor/add.controller.js');

mw.Editor = function (options) {
    var defaults = {
        regions: '.edit',
        document: document,
        executionDocument: document,
        mode: 'frame', // frame | inline | overall | bubble
        controls: [
            ['bold', '|', 'italic'],
            ['bold', '|', 'italic' ]
        ],
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
        var max = 78;
        mw.$(scope.settings.iframeAreaSelector, scope.actionWindow.document).on('touchstart touchend click keydown execCommand', function(){
            var time = new Date().getTime();
            if((time - scope._interactionTime) > max){
                scope._interactionTime = time;
                scope.selection = scope.getSelection();
                var target = mw.wysiwyg.validateCommonAncestorContainer( scope.selection.getRangeAt(0).commonAncestorContainer);
                var css = mw.CSSParser(target);
                var api = scope.api;
                scope.controls.forEach(function (ctrl) {
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
                });
            }
        });
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
        this.initState();

        if (this.settings.mode === 'inline') {
            this.createArea();
        } else if (this.settings.mode === 'iframe') {
            this.createFrame();
        }
        mw.$(this.settings.selector).append(this.wrapper);
    };
    this.init();
};

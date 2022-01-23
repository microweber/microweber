









var EditorPredefinedControls = {
    'default': [
        [ 'bold', 'italic', 'underline' ],
    ],
    smallEditorDefault: [
        ['bold', 'italic', '|', 'link']
    ]
};

var MWEditor = function (options) {
    var defaults = {
        regions: null,
        document: document,
        executionDocument: document,
        mode: 'div', // iframe | div | document
        controls: 'default',
        smallEditor: false,
        scripts: [],
        cssFiles: [],
        content: '',
        url: null,
        skin: 'default',
        state: null,
        iframeAreaSelector: null,
        activeClass: 'active-control',
        interactionControls: [
            'image', 'linkTooltip', 'tableManager'
        ],
        language: 'en',
        rootPath: mw.settings.modules_url + 'microweber/api/editor',
        editMode: 'normal', // normal | liveedit
        bar: null,
    };

    this.actionWindow = window;

    options = options || {};

    this.settings = mw.object.extend({}, defaults, options);


    if (typeof this.settings.controls === 'string') {
        this.settings.controls = EditorPredefinedControls[this.settings.controls] || EditorPredefinedControls.default;
    }

    if(!!this.settings.smallEditor) {
        if(this.settings.smallEditor === true) {
            this.settings.smallEditor = EditorPredefinedControls.smallEditorDefault;
        } else if (typeof this.settings.smallEditor === 'string') {
            this.settings.smallEditor = EditorPredefinedControls[this.settings.smallEditor] || EditorPredefinedControls.smallEditorDefault;
        }
    }

    this.document = this.settings.document;

    var scope = this;

    if(!this.settings.selector && this.settings.element){
        this.settings.selector = this.settings.element;
    }

    if(!this.settings.selector && this.settings.mode === 'document'){
        this.settings.selector = this.document.body;
    }
    if(!this.settings.selector){
        console.warn('MWEditor - selector not specified');
        return;
    }

    this.settings.selectorNode = mw.$(this.settings.selector)[0];

    if (this.settings.selectorNode) {
        this.settings.selectorNode.__MWEditor = this;
    }

    this.settings.isTextArea = this.settings.selectorNode.nodeName && this.settings.selectorNode.nodeName === 'TEXTAREA';


    this.getSelection = function () {
        return scope.actionWindow.getSelection();
    };

    this.selection = this.getSelection();

    this._interactionTime = new Date().getTime();

    this.interactionControls = [];
    this.createInteractionControls = function () {
        this.settings.interactionControls.forEach(function(ctrl){
            if (MWEditor.interactionControls[ctrl]) {
                var int = new MWEditor.interactionControls[ctrl](scope, scope);
                if(!int.element){
                    int.element = int.render();
                }
                scope.actionWindow.document.body.appendChild(int.element.node);
                scope.interactionControls.push(int);
            }
        });
    };

    this.lang = function (key) {
        if (MWEditor.i18n[this.settings.language] && MWEditor.i18n[this.settings.language][key]) {
            return  MWEditor.i18n[this.settings.language][key];
        }
        //console.warn(key + ' is not specified for ' + this.settings.language + ' language');
        return key;
    };

    this.require = function () {

    };

    this.addDependencies = function (obj){
        this.controls.forEach(function (ctrl) {
            if (ctrl.dependencies) {
                ctrl.dependencies.forEach(function (dep) {
                    scope.addDependency(dep);
                });
            }
        });
        this.interactionControls.forEach(function (int) {
            if (int.dependencies) {
                int.dependencies.forEach(function (dep) {
                    scope.addDependency(dep);
                });
            }
        });
        var node = scope.actionWindow.document.createElement('link');
        node.href = this.settings.rootPath + '/area-styles.css';
        node.type = 'text/css';
        node.rel = 'stylesheet';
        scope.actionWindow.document.body.appendChild(node);
    };
    this.addDependency = function (obj) {
        var targetWindow = obj.targetWindow || scope.actionWindow;
        if (!type) {
            type = url.split('.').pop();
        }
        if(!type || !url) return;
        var node;
        if(type === 'css') {
            node = targetWindow.document.createElement('link');
            node.rel = 'stylesheet';
            node.href = url;
            node.type = 'text/css';
        } else if(type === 'js') {
            node = targetWindow.document.createElement('script');
            node.src = url;
        }
        targetWindow.document.body.appendChild(node);
    };

    this.interactionControlsRun = function (data) {
        scope.interactionControls.forEach(function (ctrl) {
            ctrl.interact(data);
        });
    };

    var _observe = function(e){
        e = e || {type: 'action'};
        var max = 78;
        var eventIsActionLike = e.type === 'click' || e.type === 'execCommand' || e.type === 'keydown' || e.type === 'action';
        var event = e.originaleEvent ? e.originaleEvent : e;
        var localTarget = event.target;

        if (!e.target) {
            localTarget = scope.getSelection().focusNode;
         }
        var wTarget = localTarget;
        if(eventIsActionLike) {
            var shouldCloseSelects = false;
            while (wTarget) {
                var cc = wTarget.classList;
                if(cc) {
                    if(cc.contains('mw-editor-controller-component-select')) {
                        break;
                    } else if(cc.contains('mw-bar-control-item-group')) {
                        break;
                    } else if(cc.contains('mw-editor-area')) {
                        shouldCloseSelects = true;
                        break;
                    } else if(cc.contains('mw-editor-frame-area')) {
                        shouldCloseSelects = true;
                        break;
                    } else if(cc.contains('mw-editor-wrapper')) {
                        shouldCloseSelects = true;
                        break;
                    }
                }
                wTarget = wTarget.parentNode;
            }
            if(shouldCloseSelects) {
                MWEditor.core._preSelect();
            }
        }
        var time = new Date().getTime();
        if(eventIsActionLike || (time - scope._interactionTime) > max){
            if (e.pageX) {
                scope.interactionData.pageX = e.pageX;
                scope.interactionData.pageY = e.pageY;
            }
            scope._interactionTime = time;
            scope.selection = scope.getSelection();
            if (scope.selection.rangeCount === 0) {
                return;
            }
            var target = scope.api.elementNode( scope.selection.getRangeAt(0).commonAncestorContainer );
            var css = mw.CSSParser(target);
            var api = scope.api;


            var iterData = {
                selection: scope.selection,
                target: target,
                localTarget: localTarget,
                isImage: localTarget.nodeName === 'IMG' || target.nodeName === 'IMG',
                css: css.get,
                cssNative: css.css,
                event: event,
                api: api,
                scope: scope,
                isEditable: scope.api.isSelectionEditable(),
                eventIsActionLike: eventIsActionLike,
            };

            scope.interactionControlsRun(iterData);
            scope.controls.forEach(function (ctrl) {
                if(ctrl.checkSelection) {
                    ctrl.checkSelection({
                        selection: scope.selection,
                        controller: ctrl,
                        target: target,
                        css: css.get,
                        cssNative: css.css,
                        api: api,
                        eventIsActionLike: eventIsActionLike,
                        scope: scope,
                        isEditable: scope.api.isSelectionEditable()
                    });
                }
            });
        }
    }

    this.initInteraction = function () {
        var ait = 100,
            currt = new Date().getTime();
        this.interactionData = {};
        $(scope.actionWindow.document).on('selectionchange', function(e){
            $(scope).trigger('selectionchange', [{
                event: e,
                interactionData: scope.interactionData
            }]);
        });

        $(scope).on('execCommand', function (){
            _observe();
        });
        scope.state.on('undo', function (){
            setTimeout(function (){
                _observe();
            }, 123);
        });
        scope.state.on('redo', function (){
            var active = scope.state.active();
            var target = active ? active.target : scope.getSelection().focusNode();
            setTimeout(function (){
                _observe();
            }, 123);
        });
        scope.$editArea.on('touchstart touchend click keydown execCommand mousemove touchmove', _observe);
        this.createInteractionControls();
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
            if ((ctrlDown && e.keyCode === zKey) /*|| (ctrlDown && e.keyCode === vKey)*/ || (ctrlDown && e.keyCode === cKey)) {
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
                scope.registerChange();
            });
            scope.actionWindow = this.contentWindow;
            scope.$editArea = scope.$iframeArea;
            mw.tools.iframeAutoHeight(scope.frame);

            scope.preventEvents();
            $(scope).trigger('ready');
        });
        this.wrapper.appendChild(this.frame);
    };

    this.createWrapper = function () {
        this.wrapper = this.document.createElement('div');
        this.wrapper.className = 'mw-editor-wrapper mw-editor-' + this.settings.skin;
    };

    var _clean = document.createElement('div');
    var clean = function (val){
        _clean.innerHTML = val;

        $('[contenteditable]', _clean).removeAttr('contenteditable');

        return _clean.innerHTML;
    }

    this._syncTextArea = function (content) {



        content = clean(content || scope.$editArea.html());
        if (scope.settings.isTextArea) {
            $(scope.settings.selectorNode).val(content);
            $(scope.settings.selectorNode).trigger('change');
        }
    };

    this._registerChangeTimer = null;
    this.registerChange = function (content) {
        clearTimeout(this._registerChangeTimer);
        this._registerChangeTimer = setTimeout(function () {
            content = content || scope.$editArea.html();
            scope._syncTextArea(content);
            $(scope).trigger('change', [content]);
        }, 78);
    };

    this.createArea = function () {
        var content = this.settings.content || '';
        if(!content && this.settings.isTextArea) {
            content = this.settings.selectorNode.value;
        }
        this.area = mw.element({
            props: { className: 'mw-editor-area', innerHTML: content }
        });
        this.area.node.contentEditable = true;
        this.area.node.oninput = function() {
            scope.registerChange();
        };
        this.wrapper.appendChild(this.area.node);
        scope.$editArea = this.area.$node;
        scope.preventEvents();
        $(scope).trigger('ready');
    };

    this.documentMode = function () {
        if(!this.settings.regions) {
            console.warn('Regions are not defined in Document mode.');
            return;
        }
        this.$editArea = $(this.document.body);
        this.wrapper.className += ' mw-editor-wrapper-document-mode';
        mw.$(this.document.body).append(this.wrapper)[0].mwEditor = this;
        $(scope).trigger('ready');
    };

    this.setContent = function (content, trigger) {
        if(typeof trigger === 'undefined'){
            trigger = true;
        }
        this.$editArea.html(content);
        if(trigger){
            scope.registerChange(content);
        }
    };

    this.nativeElement = function (node) {
        return node.node ? node.node : node;
    };

    this.controls = [];
    this.api = MWEditor.api(this);

    this._addControllerGroups = [];
    this.addControllerGroup = function (obj, row, bar) {
        if(!bar) {
            bar = 'bar';
        }
        var group = obj.group;
        var id = mw.id('mw.editor-group-');
        var el = mw.element({
            props: {
                className: 'mw-bar-control-item mw-bar-control-item-group',
                id:id
            }
        });

        var groupel = mw.element({
                props:{
                    className: 'mw-bar-control-item-group-contents'
                }
            });

        var icon = MWEditor.core.button({
            tag:'span',
            props: {
                className: ' mw-editor-group-button',
                innerHTML: '<span class="mw-editor-group-button-caret"></span>'
            }
        });
        if(group.icon) {
            icon.prepend('<span class="' + group.icon + ' mw-editor-group-button-icon"></span>');
            icon.on('click', function () {
                MWEditor.core._preSelect(this.parentNode);
                this.parentNode.classList.toggle('active');
            });

        } else if(group.controller) {
            if(scope.controllers[group.controller]){
                var ctrl = new scope.controllers[group.controller](scope, scope.api, scope);
                scope.controls.push(ctrl);
                icon.prepend(ctrl.element);
                mw.element(icon.get(0).querySelector('.mw-editor-group-button-caret')).on('click', function () {
                    MWEditor.core._preSelect(this.parentNode.parentNode);
                    this.parentNode.parentNode.classList.toggle('active');
                });
            } else if(scope.controllersHelpers[group.controller]){
                groupel.append(this.controllersHelpers[group.controller]());
            }
        }
        el.append(icon);

        groupel.on('click', function (){
            MWEditor.core._preSelect();
        });

        var media;
        obj.group.when = obj.group.when || 9999;
        // at what point group buttons become like dropdown - by default it's always a dropdown
        if (obj.group.when) {
            if (typeof obj.group.when === 'number') {
                media = '(max-width: ' + obj.group.when + 'px)';
            } else {
                media = obj.group.when;
            }
        }



        el.append(groupel);
        row = typeof row !== 'undefined' ? row :  this.settings.controls.length - 1;
        group.controls.forEach(function (name) {
            if(scope.controllers[name]){
                var ctrl = new scope.controllers[name](scope, scope.api, scope);
                scope.controls.push(ctrl);
                groupel.append(ctrl.element);
            } else if(scope.controllersHelpers[name]){
                groupel.append(this.controllersHelpers[name]());
            }
        });

        scope[bar].add(el, row);

        this._addControllerGroups.push({
            el: el,
            row: row,
            obj: obj,
            media: media
        });
        return el;
    };

    this.controlGroupManager = function () {
        var check = function() {
            var i = 0, l = scope._addControllerGroups.length;
            for ( ; i< l ; i++) {
                var item = scope._addControllerGroups[i];
                var media = item.media;
                if(media) {
                    var match = scope.document.defaultView.matchMedia(media);
                    item.el.$node[match.matches ? 'addClass' : 'removeClass']('mw-editor-control-group-media-matches');
                }
            }
        };
        $(window).on('load resize orientationchange', function () {
            check();
        });
        check();
    };

    this.addController = function (name, row, bar) {
        row = typeof row !== 'undefined' ? row :  this.settings.controls.length - 1;
        if (!bar) {
            bar = 'bar';
        }
        if(this.controllers[name]){
            var ctrl = new this.controllers[name](scope, scope.api, scope);
            if (!ctrl.element) {
                ctrl.element = ctrl.render();
            }

            this.controls.push(ctrl);
            this[bar].add(ctrl.element, row);
        } else if(this.controllersHelpers[name]){
            this[bar].add(this.controllersHelpers[name](), row);
        }
    };

    this.createSmallEditor = function () {
        if (!this.settings.smallEditor) {
            return;
        }
        this.smallEditor = mw.element({
            props: {
                className: 'mw-small-editor mw-small-editor-skin-' + this.settings.skin
            }
        });

        this.smallEditorBar = mw.bar();

        this.smallEditor.hide();
        this.smallEditor.append(this.smallEditorBar.bar);
        for (var i1 = 0; i1 < this.settings.smallEditor.length; i1++) {
            var item = this.settings.smallEditor[i1];
            this.smallEditorBar.createRow();
            for (var i2 = 0; i2 < item.length; i2++) {
                if( typeof item[i2] === 'string') {
                    scope.addController(item[i2], i1, 'smallEditorBar');
                } else if( typeof item[i2] === 'object') {
                    scope.addControllerGroup(item[i2], i1, 'smallEditorBar');
                }
            }
        }
        scope.$editArea.on('mouseup touchend', function (e, data) {
            if (scope.selection && !scope.selection.isCollapsed) {
                if(!mw.tools.hasParentsWithClass(e.target, 'mw-bar')){
                    scope.smallEditor.css({
                        top: scope.interactionData.pageY - scope.smallEditor.$node.height() - 20,
                        left: scope.interactionData.pageX,
                        display: 'block'
                    });
                }
            } else {
                scope.smallEditor.hide();
            }
        });
        this.actionWindow.document.body.appendChild(this.smallEditor.node);
    };
    this.createBar = function () {
        this.bar = mw.settings.bar || mw.bar();
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
            scope.api.execCommand('enableObjectResizing', false, 'false');
            scope.api.execCommand('2D-Position', false, false);
            scope.api.execCommand("enableInlineTableEditing", null, false);
            if(!scope.state.hasRecords()){
                scope.state.record({
                    $initial: true,
                    target: scope.$editArea[0],
                    value: scope.$editArea[0].innerHTML
                });
            }
            scope.settings.regions = scope.settings.regions || scope.$editArea;
            $(scope.settings.regions, scope.actionWindow.document).attr('contenteditable', true);
            if (scope.settings.editMode === 'liveedit') {
                scope.liveEditMode();
            }
            var css = {};
            if(scope.settings.minHeight) {
                css.minHeight = scope.settings.minHeight;
            }
            if(scope.settings.maxHeight) {
                css.maxHeight = scope.settings.maxHeight;
            }
            if(scope.settings.height) {
                css.height = scope.settings.height;
            }
            if(scope.settings.minWidth) {
                css.minWidth = scope.settings.minWidth;
            }
            if(scope.settings.maxWidth) {
                css.maxWidth = scope.settings.maxWidth;
            }
            if(scope.settings.width) {
                css.width = scope.settings.width;
            }
            scope.$editArea.css(css);
            $('module', scope.$editArea).attr('contenteditable', false)
            scope.addDependencies();
            scope.createSmallEditor();

        });
    };

    this.liveEditMode = function () {
        this.liveedit = MWEditor.liveeditMode(this.actionWindow.document.body, scope);
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

    this.__insertEditor = function () {
        if (this.settings.isTextArea) {
            var el = mw.$(this.settings.selector);
            el[0].mwEditor = this;
            el.hide();
            var areaWrapper = mw.element();
            areaWrapper.node.mwEditor = this;
            el.after(areaWrapper.node);
            areaWrapper.append(this.wrapper);
        } else {
            mw.$(this.settings.selector).append(this.wrapper)[0].mwEditor = this;
        }
    };

    this.init = function () {
        this.controllers = MWEditor.controllers;
        this.controllersHelpers = MWEditor.controllersHelpers;
        this.initState();
        this._onReady();
        this.createWrapper();
        this.createBar();

        if (this.settings.mode === 'div') {
            this.createArea();
        } else if (this.settings.mode === 'iframe') {
            this.createFrame();
        } else if (this.settings.mode === 'document') {
            this.documentMode();
        }
        if (this.settings.mode !== 'document') {
            this._initInputRecord();
            this.__insertEditor();
        }
        this.controlGroupManager();

    };
    this.init();
};

if (window.mw) {
   mw.Editor = function (options){
       options = options || {};
       if(!options.selector && options.element){
           options.selector = options.element;
       }
       if(options.selector){
           if (typeof options.selector === 'string') {
               options.selector = (options.document || document).querySelector(options.selector);
           }
           if (options.selector && options.selector.__MWEditor) {
               return options.selector.__MWEditor;
           }
       }
       return new MWEditor(options);
   };
}


mw.require('filemanager.js');
mw.require('autocomplete.js');
mw.require('filepicker.js');

mw.require('form-controls.js');
mw.require('link-editor.js');

//

mw.require('state.js');

mw.require('editor/bar.js');
mw.require('editor/api.js');
mw.require('editor/helpers.js');
mw.require('editor/tools.js');
mw.require('editor/core.js');
mw.require('editor/controllers.js');
mw.require('editor/add.controller.js');
mw.require('editor/interaction-controls.js');
mw.require('editor/i18n.js');
mw.require('editor/liveeditmode.js');
mw.require('control_box.js');


import {ElementManager} from "../classes/element";
import {State} from "../classes/state";
import {CSSParser} from "../classes/css";
import {DomService} from "../classes/dom";
import {Dialog} from "../classes/dialog";



var EditorPredefinedControls = {
    'default': [
        [ 'bold', 'italic', 'underline' ],
    ],
    smallEditorDefault: [
        ['bold', 'italic', '|', 'link']
    ]
};

class EditorCore {
    constructor() {
        this.defaults = {
            regions: null,
            notEditableSelector: '.module',
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
            rootPath:  'http://localhost/mw2/userfiles/modules/microweber/api/editor',
            editMode: 'normal', // normal | liveedit
            bar: null,
        };
    }
}


class Editor extends EditorCore  {

    constructor(options) {
        super();
        options = options || {};



        this.settings = Object.assign({}, this.defaults, options);

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
        this.executionDocument = this.settings.executionDocument;

        this.actionWindow = this.document.defaultView;
        this.executionWindow = this.executionDocument.defaultView;

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

        this.settings.selectorNode = $(this.settings.selector)[0];

        if (this.settings.selectorNode) {
            this.settings.selectorNode.__MWEditor = this;
        }

        this.settings.isTextArea = this.settings.selectorNode.nodeName && this.settings.selectorNode.nodeName === 'TEXTAREA';
        this.dialog = function (options) {
            options.document = this.document;

            return new Dialog(options)
        }

        this.selection = this.getSelection();

        this._interactionTime = new Date().getTime();

        this.interactionControls = [];
        this._registerChangeTimer = null;
        this.controls = [];
        this.api = MWEditor.api(this);

        this._addControllerGroups = [];
        this._initInputRecordTime = null;
        this.interactionData = {};
        this.init();

    }



    getSelection () {
        return this.actionWindow.getSelection();
    };


    createInteractionControls () {
        this.settings.interactionControls.forEach((ctrl) => {
            if (MWEditor.interactionControls[ctrl]) {
                var int = new MWEditor.interactionControls[ctrl](this, this);
                if(!int.element){
                    int.element = int.render();
                }
                this.actionWindow.document.body.appendChild(int.element.node);
                this.interactionControls.push(int);
            }
        });
    };

    lang (key)  {
        if (MWEditor.i18n[this.settings.language] && MWEditor.i18n[this.settings.language][key]) {
            return  MWEditor.i18n[this.settings.language][key];
        }
        //console.warn(key + ' is not specified for ' + this.settings.language + ' language');
        return key;
    }



    addDependencies (obj){
        this.controls.forEach((ctrl) => {
            if (ctrl.dependencies) {
                ctrl.dependencies.forEach((dep) => {
                    this.addDependency(dep);
                });
            }
        });
        this.interactionControls.forEach( (int) => {
            if (int.dependencies) {
                int.dependencies.forEach( (dep) => {
                    this.addDependency(dep);
                });
            }
        });
        var node = this.actionWindow.document.createElement('link');
        node.href = this.settings.rootPath + '/area-styles.css';
        node.type = 'text/css';
        node.rel = 'stylesheet';
        this.actionWindow.document.body.appendChild(node);
    };
    addDependency (obj) {
        var targetWindow = obj.targetWindow || this.actionWindow;
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

    interactionControlsRun (data) {
        this.interactionControls.forEach(function (ctrl) {
            ctrl.interact(data);
        });
    };

    _observe (e) {
        e = e || {type: 'action'};
        var max = 78;
        var eventIsActionLike = e.type === 'click' || e.type === 'execCommand' || e.type === 'keydown' || e.type === 'action';
        var event = e.originaleEvent ? e.originaleEvent : e;
        var localTarget = event.target;

        if (!e.target) {
            localTarget = this.getSelection().focusNode;
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
        if(eventIsActionLike || (time - this._interactionTime) > max){
            if (e.pageX) {
                console.log(this.interactionData, this)
                this.interactionData.pageX = e.pageX;
                this.interactionData.pageY = e.pageY;
            }
            this._interactionTime = time;
            this.selection = this.getSelection();
            if (this.selection.rangeCount === 0) {
                return;
            }
            var target = this.api.elementNode( this.selection.getRangeAt(0).commonAncestorContainer );
            var css = CSSParser(target);
            var api = this.api;


            var iterData = {
                selection: this.selection,
                target: target,
                localTarget: localTarget,
                isImage: localTarget.nodeName === 'IMG' || target.nodeName === 'IMG',
                css: css.get,
                cssNative: css.css,
                event: event,
                api: api,
                this: this,
                isEditable: this.api.isSelectionEditable(),
                eventIsActionLike: eventIsActionLike,
            };

            this.interactionControlsRun(iterData);
            this.controls.forEach((ctrl) => {
                if(ctrl.checkSelection) {
                    ctrl.checkSelection({
                        selection: this.selection,
                        controller: ctrl,
                        target: target,
                        css: css.get,
                        cssNative: css.css,
                        api: api,
                        eventIsActionLike: eventIsActionLike,
                        this: this,
                        isEditable: this.api.isSelectionEditable()
                    });
                }
            });
        }
    };

    initInteraction () {
        var ait = 100,
            currt = new Date().getTime();

        $(this.actionWindow.document).on('selectionchange', (e) => {
             $(this).trigger('selectionchange', [{
                event: e,
                interactionData: this.interactionData
            }]);
        });

        $(this).on('execCommand', (e) => {
            this._observe(e);
        });
        this.state.on('undo', (e) => {
            setTimeout(() => {
                this._observe(e);
            }, 123);
        });
        this.state.on('redo', (e) => {
            var active = this.state.active();
            var target = active ? active.target : this.getSelection().focusNode();
            setTimeout(() => {
                this._observe(e);
            }, 123);
        });

        this.createInteractionControls();
    };


    preventEvents () {
        if(!this._preventEvents) {
            this._preventEvents = [];
        }

        var node;
        if(this.area && this._preventEvents.indexOf(this.area.node) === -1) {
            this._preventEvents.push(this.area.node);
            node = this.area.node;
        } else if(this.$iframeArea && this._preventEvents.indexOf(this.$iframeArea[0]) === -1) {
            this._preventEvents.push(this.$iframeArea[0]);
            node = this.$iframeArea[0];
        }
        var ctrlDown = false;
        var ctrlKey = 17, vKey = 86, cKey = 67, zKey = 90;
        node.onkeydown = (e) => {
            if (e.keyCode === ctrlKey || e.keyCode === 91) {
                ctrlDown = true;
            }
            if ((ctrlDown && e.keyCode === zKey) /*|| (ctrlDown && e.keyCode === vKey)*/ || (ctrlDown && e.keyCode === cKey)) {
                e.preventDefault();
                return false;
            }
        };
        node.onkeyup = (e) => {
            if (e.keyCode === 17 || e.keyCode === 91) {
                ctrlDown = false;
            }
        };
    };
    initState () {
        this.state = this.settings.state || (new State());
    };

    controllerActive (node, active) {
        node.classList[active ? 'add' : 'remove'](this.settings.activeClass);
    };

    createFrame () {
        this.frame = this.document.createElement('iframe');
        this.frame.className = 'mw-editor-frame';
        this.frame.allow = 'accelerometer; autoplay; encrypted-media; gyrothis; picture-in-picture';
        this.frame.allowFullscreen = true;
        this.frame.scrolling = "yes";
        this.frame.width = "100%";
        this.frame.frameBorder = "0";
        if (this.settings.url) {
            this.frame.src = this.settings.url;
        } else {

        }

        $(this.frame).on('load', () => {
            if (!this.settings.iframeAreaSelector) {
                var area = document.createElement('div');
                area.style.outline = 'none';
                area.className = 'mw-editor-frame-area';
                this.settings.iframeAreaSelector =  '.' + area.className;
                this.contentWindow.document.body.append(area);
                area.style.minHeight = '100px';
            }
            this.$iframeArea = ElementManager(this.settings.iframeAreaSelector, this.contentWindow.document);

            this.$iframeArea.html(this.settings.content || '');
            this.$iframeArea.on('input', () => {
                this.registerChange();
            });
            this.actionWindow = this.contentWindow;
            this.$editArea = this.$iframeArea;
            mw.tools.iframeAutoHeight(this.frame);

            this.preventEvents();
            $(this).trigger('ready');
        });
        this.wrapper.appendChild(this.frame);
    };

    createWrapper () {
        this.wrapper = this.document.createElement('div');
        this.wrapper.className = 'mw-editor-wrapper mw-editor-' + this.settings.skin;
    };

    _syncTextArea (content) {

        if(this.$editArea){
            $('[contenteditable]', this.$editArea).removeAttr('contenteditable');
        }

        content = content || this.$editArea.html();
        if (this.settings.isTextArea) {
            $(this.settings.selectorNode).val(content);
            $(this.settings.selectorNode).trigger('change');
        }
    };


    registerChange (content) {
        clearTimeout(this._registerChangeTimer);
        this._registerChangeTimer = setTimeout(() => {
            content = content || this.$editArea.html();
            this._syncTextArea(content);
            $(this).trigger('change', [content]);
        }, 78);
    };

    createArea () {
        var content = this.settings.content || '';
        if(!content && this.settings.isTextArea) {
            content = this.settings.selectorNode.value;
        }
        this.area = ElementManager({
            props: { className: 'mw-editor-area', innerHTML: content }
        });
        this.area.node.contentEditable = true;

        this.area.node.oninput = () => {
            this.registerChange();
        };
        this.wrapper.appendChild(this.area.node);
        this.$editArea = this.area;
        this.preventEvents();
        $(this).trigger('ready');
    };

    documentMode() {
        if(!this.settings.regions) {
            console.warn('Regions are not defined in Document mode.');
            return;
        }
        this.wrapper.className += ' mw-editor-wrapper-document-mode';
        ElementManager(this.document.body).append(this.wrapper)
        this.document.body.mwEditor = this;
        $(this).trigger('ready');
    };

    setContent (content, trigger) {
        if(typeof trigger === 'undefined'){
            trigger = true;
        }
        this.$editArea.html(content);
        if(trigger){
            this.registerChange(content);
        }
    };

    nativeElement (node) {
        return node.node ? node.node : node;
    };


    addControllerGroup (obj, row, bar) {
        if(!bar) {
            bar = 'bar';
        }
        var group = obj.group;
        var id = mw.id('mw.editor-group-');
        var el = ElementManager({
            props: {
                className: 'mw-bar-control-item mw-bar-control-item-group',
                id:id
            }
        });

        var groupel = ElementManager({
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
            icon.on('click', () => {
                MWEditor.core._preSelect(this.parentNode);
                this.parentNode.classList.toggle('active');
            });

        } else if(group.controller) {
            if(this.controllers[group.controller]){
                var ctrl = new this.controllers[group.controller](this, this.api, this);
                this.controls.push(ctrl);
                icon.prepend(ctrl.element);
                ElementManager(icon.get(0).querySelector('.mw-editor-group-button-caret')).on('click', () => {
                    MWEditor.core._preSelect(this.parentNode.parentNode);
                    this.parentNode.parentNode.classList.toggle('active');
                });
            } else if(this.controllersHelpers[group.controller]){
                groupel.append(this.controllersHelpers[group.controller]());
            }
        }
        el.append(icon);

        groupel.on('click', () => {
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
        group.controls.forEach((name) => {
            if(this.controllers[name]){
                var ctrl = new this.controllers[name](this, this.api, this);
                this.controls.push(ctrl);
                groupel.append(ctrl.element);
            } else if(this.controllersHelpers[name]){
                groupel.append(this.controllersHelpers[name]());
            }
        });

        this[bar].add(el, row);

        this._addControllerGroups.push({
            el: el,
            row: row,
            obj: obj,
            media: media
        });
        return el;
    };

    controlGroupManager () {
        var check = () => {
            var i = 0, l = this._addControllerGroups.length;
            for ( ; i< l ; i++) {
                var item = this._addControllerGroups[i];
                var media = item.media;
                if(media) {
                    var match = this.document.defaultView.matchMedia(media);
                     item.el[match.matches ? 'addClass' : 'removeClass']('mw-editor-control-group-media-matches');
                }
            }
        };
        $(window).on('load resize orientationchange', () => {
            check();
        });
        check();
    };

    addController (name, row, bar) {
        row = typeof row !== 'undefined' ? row :  this.settings.controls.length - 1;
        if (!bar) {
            bar = 'bar';
        }
        if(this.controllers[name]){
            var ctrl = new this.controllers[name](this, this.api, this);
            if (!ctrl.element) {
                ctrl.element = ctrl.render();
            }

            this.controls.push(ctrl);
            this[bar].add(ctrl.element, row);
        } else if(this.controllersHelpers[name]){
            this[bar].add(this.controllersHelpers[name](), row);
        }
    };

    createSmallEditor ()  {
        if (!this.settings.smallEditor) {
            return;
        }
        this.smallEditor = ElementManager({
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
                    this.addController(item[i2], i1, 'smallEditorBar');
                } else if( typeof item[i2] === 'object') {
                    this.addControllerGroup(item[i2], i1, 'smallEditorBar');
                }
            }
        }
        this.$editArea.on('mouseup touchend',  (e, data) => {
            if (this.selection && !this.selection.isCollapsed) {
                if(!DomService.hasParentsWithClass(e.target, 'mw-bar')){
                    this.smallEditor.css({
                        top: this.interactionData.pageY - this.smallEditor.height() - 20,
                        left: this.interactionData.pageX,
                        display: 'block'
                    });
                }
            } else {
                this.smallEditor.hide();
            }
        });
        this.actionWindow.document.body.appendChild(this.smallEditor.node);
    };
    createBar () {
        this.bar = mw.settings.bar || mw.bar();
        for (var i1 = 0; i1 < this.settings.controls.length; i1++) {
            var item = this.settings.controls[i1];
            this.bar.createRow();
            for (var i2 = 0; i2 < item.length; i2++) {
                if( typeof item[i2] === 'string') {
                    this.addController(item[i2], i1);
                } else if( typeof item[i2] === 'object') {
                    this.addControllerGroup(item[i2], i1);
                }
            }
        }
        this.wrapper.appendChild(this.bar.bar);
    };

    _onReady  ()  {

        $(this).on('ready', () => {
            this.initInteraction();
            this.api.execCommand('enableObjectResizing', false, 'false');
            this.api.execCommand('2D-Position', false, false);
            this.api.execCommand("enableInlineTableEditing", null, false);

            if(!this.state.hasRecords()){
                this.state.record({
                    $initial: true,
                    target: this.$editArea.get(0),
                    value: this.$editArea.get(0).innerHTML
                });
            }
            this.settings.regions = this.settings.regions || this.$editArea;
            this.$editArea.on('touchstart touchend click keydown execCommand mousemove touchmove', e => this._observe);

            Array.from(this.actionWindow.document.querySelectorAll(this.settings.regions)).forEach((el) => {
                el.contentEditable = false;
                ElementManager(el).on('mousedown touchstart', (e) =>{

                    e.stopPropagation();
                    var curr = DomService.firstParentOrCurrent(e.target, this.settings.regions);
                    Array.from(this.actionWindow.document.querySelectorAll(this.settings.regions)).forEach(function (el){
                        el.contentEditable = el === curr;
                    });
                })
            })

            Array.from(this.actionWindow.document.querySelectorAll(this.settings.notEditableSelector)).forEach(function (el){
                el.contentEditable = false;
            })


            if (this.settings.editMode === 'liveedit') {
                this.liveEditMode();
            }
            var css = {};
            if(this.settings.minHeight) {
                css.minHeight = this.settings.minHeight;
            }
            if(this.settings.maxHeight) {
                css.maxHeight = this.settings.maxHeight;
            }
            if(this.settings.height) {
                css.height = this.settings.height;
            }
            if(this.settings.minWidth) {
                css.minWidth = this.settings.minWidth;
            }
            if(this.settings.maxWidth) {
                css.maxWidth = this.settings.maxWidth;
            }
            if(this.settings.width) {
                css.width = this.settings.width;
            }
            this.$editArea.css(css);
            this.addDependencies();
            this.createSmallEditor();

        });
    };

    liveEditMode () {
        this.liveedit = MWEditor.liveeditMode(this.actionWindow.document.body, this);
    };


    _initInputRecord () {
        $(this).on('change',   (e, html) => {
            clearTimeout(this._initInputRecordTime);
            this._initInputRecordTime = setTimeout(() => {
                this.state.record({
                    target: this.$editArea.get(0),
                    value: html
                });
            }, 600);

        });
    };

    __insertEditor (){
        if (this.settings.isTextArea) {
            var el = ElementManager(this.settings.selector);
            el.get(0).mwEditor = this;
            el.hide();
            var areaWrapper = ElementManager();
            areaWrapper.node.mwEditor = this;
            el.after(areaWrapper.node);
            areaWrapper.append(this.wrapper);
        } else {
            ElementManager(this.settings.selector).append(this.wrapper).get(0).mwEditor = this;
        }
    };

    init () {
        this.controllers = MWEditor.controllers;
        this.controllersHelpers = MWEditor.controllersHelpers;
        this.initState();

        this.createWrapper();
        this.createBar();

        if (this.settings.mode === 'div') {
            this.createArea();
        } else if (this.settings.mode === 'iframe') {
            this.createFrame();
        } else if (this.settings.mode === 'document') {
            this.documentMode();
        }

        this._onReady();

        if(this.settings.iframe) {
            this.actionWindow = this.settings.iframe.contentWindow;
            this.executionDocument = this.settings.iframe.contentWindow.document;
            this.$iframeArea = $(this.settings.iframeAreaSelector, this.executionDocument);
             if(this.executionDocument.readyState === 'complete') {
                this.$iframeArea = ElementManager(this.settings.iframeAreaSelector, this.executionDocument);
                this.$editArea = this.$iframeArea;
                $(this).trigger('ready');
            } else {
                this.actionWindow.addEventListener('load', () => {
                    this.$iframeArea = ElementManager(this.settings.iframeAreaSelector, this.executionDocument);
                    this.$editArea = this.$iframeArea;
                     $(this).trigger('ready');
                })
            }

        }



        if (this.settings.mode !== 'document') {
            this._initInputRecord();
            this.__insertEditor();
        }
        this.controlGroupManager();

    };


 }

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
       return new Editor(options);
   };
}

window.MWEditor = function (options) {
    return new Editor(options)
}







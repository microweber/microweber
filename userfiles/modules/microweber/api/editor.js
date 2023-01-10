








var EditorPredefinedControls = {
    'default': [
        [ 'bold', 'italic', 'underline' ],
    ],
    smallEditorDefault: [
        ['bold', 'italic', '|', 'link']
    ]
};

var MWEditor = function (options) {

    ;(function (){

        var self;
        var RtlDetect=self={_regexEscape:/([\.\*\+\^\$\[\]\\\(\)\|\{\}\,\-\:\?])/g,_regexParseLocale:/^([a-zA-Z]*)([_\-a-zA-Z]*)$/,_escapeRegExpPattern:function(str){if(typeof str!=='string'){return str}
        return str.replace(self._regexEscape,'\\$1')},_toLowerCase:function(str,reserveReturnValue){if(typeof str!=='string'){return reserveReturnValue&&str}
        return str.toLowerCase()},_toUpperCase:function(str,reserveReturnValue){if(typeof str!=='string'){return reserveReturnValue&&str}
        return str.toUpperCase()},_trim:function(str,delimiter,reserveReturnValue){var patterns=[];var regexp;var addPatterns=function(pattern){patterns.push('^'+pattern+'+|'+pattern+'+$')};if(typeof delimiter==='boolean'){reserveReturnValue=delimiter;delimiter=null}
        if(typeof str!=='string'){return reserveReturnValue&&str}
        if(Array.isArray(delimiter)){delimiter.map(function(item){var pattern=self._escapeRegExpPattern(item);addPatterns(pattern)})}
        if(typeof delimiter==='string'){var patternDelimiter=self._escapeRegExpPattern(delimiter);addPatterns(patternDelimiter)}
        if(!delimiter){addPatterns('\\s')}
        var pattern='('+patterns.join('|')+')';regexp=new RegExp(pattern,'g');while(str.match(regexp)){str=str.replace(regexp,'')}
        return str},_parseLocale:function(strLocale){var matches=self._regexParseLocale.exec(strLocale);var parsedLocale;var lang;var countryCode;if(!strLocale||!matches){return}
        matches[2]=self._trim(matches[2],['-','_']);lang=self._toLowerCase(matches[1]);countryCode=self._toUpperCase(matches[2])||countryCode;parsedLocale={lang:lang,countryCode:countryCode};return parsedLocale},isRtlLang:function(strLocale){var objLocale=self._parseLocale(strLocale);if(!objLocale){return}
        return(self._BIDI_RTL_LANGS.indexOf(objLocale.lang)>=0)},getLangDir:function(strLocale){return self.isRtlLang(strLocale)?'rtl':'ltr'}};Object.defineProperty(self,'_BIDI_RTL_LANGS',{value:['ae','ar','arc','bcc','bqi','ckb','dv','fa','glk','he','ku','mzn','nqo','pnb','ps','sd','ug','ur','yi'],writable:!1,enumerable:!0,configurable:!1})

        MWEditor.rtlDetect = RtlDetect;

    })();

    var defaults = {
        regions: null,
        document: document,
        executionDocument: document,
        mode: 'div', // iframe | div | document
        controls: 'default',
        smallEditor: false,
        smallEditorPositionX: 'left',
        scripts: [],
        cssFiles: [],
        content: '',
        url: null,
        skin: 'default',
        smallEditorSkin: 'default',
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
        storage: mw.storage,
        id: null // for storage
    };

    this.actionWindow = window;

    options = options || {};

    this.settings = mw.object.extend({}, defaults, options);
    if(!this.settings.direction) {
        if(this.settings.inputLanguage) {
            this.settings.direction = MWEditor.rtlDetect.getLangDir(this.settings.inputLanguage);
        } else {
            this.settings.direction = 'ltr';
        }

    }
    this.storage = this.settings.storage;



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
        //this.settings.selector = this.settings.element;
    }

    if(!this.settings.selector && this.settings.mode === 'document'){
        //this.settings.selector = this.document.body;
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

    this.disabled = function (target, state) {
        var node = target.get ? target.get(0) : target;
        if(typeof state === 'undefined') {
            return node._$mwEditorDisabled === true;
        }
        node._$mwEditorDisabled = state;
        if(state === true) {
            node.classList.add('mw-editor-component-disabled');
        } else {
            node.classList.remove('mw-editor-component-disabled')
        }
        return this;
    }

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
            var range = scope.selection.getRangeAt(0);
            var target = scope.api.elementNode( range.commonAncestorContainer ) || scope.area;

            var css = mw.CSSParser(target);
            var api = scope.api;


            var rangeInEditor = false;
            if(scope.editArea.contains(scope.selection.anchorNode) && scope.editArea.contains(scope.selection.focusNode)) {
                rangeInEditor = true;
            }

            var iterData = {
                selection: scope.selection,
                lastRange: scope.lastRange,
                rangeInEditor: rangeInEditor,
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
                        lastRange: scope.lastRange,
                        rangeInEditor: rangeInEditor,
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
            var target = active ? active.target : scope.getSelection().focusNode;
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

    this.lastRange = null;
    var areaSelectionMemo = function () {
          scope.actionWindow.document.addEventListener('selectionchange', function(e) {
            var sel = scope.getSelection();
            if(scope.editArea.contains(sel.anchorNode) && scope.editArea.contains(sel.focusNode)) {
                scope.lastRange = sel.getRangeAt(0);
            }
        });
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
            scope.editArea = scope.$iframeArea[0];
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
            props: {
                className: 'mw-editor-area', innerHTML: content, dir: scope.settings.direction,
                style: {
                    direction: scope.settings.direction,
                    textAlign: scope.settings.direction === 'ltr' ? 'left' : 'right',
                }
            },

        });
        this.area.get(0).contentEditable = true;

        this.wrapper.appendChild(this.area.node);
        scope.$editArea = this.area.$node;
        scope.editArea = this.area.get(0);
        scope.preventEvents();
        $(scope).trigger('ready');
    };

    this.documentMode = function () {
        if(!this.settings.regions) {
            console.warn('Regions are not defined in Document mode.');
            return;
        }
        this.$editArea = $(this.document.body);
        this.editArea = this.$editArea.get(0);
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

        setTimeout(function () {
            var doc = el.get(0).ownerDocument;
            if(doc && !doc.body.__mwEditorGroupDownRegister) {
                doc.body.__mwEditorGroupDownRegister = true;
                doc.body.addEventListener('click', function (e){
                    if (!mw.tools.hasParentsWithClass(e.target, 'mw-bar-control-item-group')) {
                        mw.element('.mw-bar-control-item-group.active').each(function (){

                                this.classList.remove('active');

                        });
                    }
                });
            }
        }, 500);

        var groupel = mw.element({
                props:{
                    className: 'mw-bar-control-item-group-contents'
                }
            });

        var icon = MWEditor.core.button({
            tag:'span',
            props: {
                className: ' mw-editor-group-button',
                innerHTML: '<span class="mw-editor-group-button-caret"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M7,10L12,15L17,10H7Z" /></svg></span>'
            }
        });
        if(group.icon) {
            if(group.icon.includes('<')) {

                icon.prepend('<span class="mw-editor-group-button-icon">' + group.icon + '</span>');
            } else {
                icon.prepend('<span class="' + group.icon + ' mw-editor-group-button-icon"></span>');
            }

            icon.on('click', function () {
                MWEditor.core._preSelect(this.parentNode);
                this.parentNode.classList.toggle('active');
            });

        } else if(group.controller) {
            if(scope.controllers[group.controller]){
                var ctrl = new scope.controllers[group.controller](scope, scope.api, scope);
                scope.controls.push(ctrl);
                icon.prepend(ctrl.element);
                mw.element(icon.get(0).querySelector('.mw-editor-group-button-caret')).on('mousedown touchstart', function (e) {
                    e.preventDefault();
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



    var pinned;
    function smallEditorPinned (state){
        if(typeof state === 'undefined'){
            var pinned;
            if(scope.settings.id) {
                pinned = scope.storage.get(scope.settings.id + '-small-editor-pinned');
                if(typeof pinned === 'boolean'){
                    return pinned;
                } else {
                    return false;
                }
            } else {
                return pinned;
            }
        } else {
            if(smallEditorPinned() !== state) {
                if(scope.settings.id) {
                    scope.storage.set(scope.settings.id + '-small-editor-pinned', state);
                } else {
                    pinned = state;
                }
            }
        }
    }

    var _afterPin = function () {
        setTimeout(function (){
            if (scope.lastRange) {
                scope.smallEditorInteract(scope.getActualTarget(scope.lastRange.commonAncestorContainer));
            }
        }, 178);
    };

    this.smallEditorApi = {
        isPinned: function (){
            return smallEditorPinned();
        },
        pin: function () {
            smallEditorPinned(true);
            scope.smallEditor.addClass('pinned');
            _afterPin();
        },
        unPin: function () {
            smallEditorPinned(false);
            scope.smallEditor.removeClass('pinned');
            _afterPin();
        },
        toggle: function () {
            var api = scope.smallEditorApi;
            api.isPinned() ? api.unPin() : api.pin();
        },
        _initFromMemory: function (){
            var api = scope.smallEditorApi;
            api.isPinned() ? api.pin() : api.unPin();
        }
    };

    this.createSmallEditor = function () {
        if (!this.settings.smallEditor) {
            return;
        }
        this.smallEditor = mw.element({
            props: {
                className: 'mw-small-editor mw-small-editor-skin-' + (this.settings.smallEditorSkin || this.settings.skin)
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
        scope.$editArea.on('click', function (e) {

               var target = scope.getActualTarget(e.target);

               scope.smallEditorInteract(target);

        });
        this.actionWindow.document.body.appendChild(this.smallEditor.node);
        this.smallEditorApi._initFromMemory();
    };

    scope.getActualTarget = function (target) {
        return mw.tools.firstParentOrCurrentWithTag(scope.api.elementNode(target), ['div', 'ul', 'ol', 'p', 'table', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']);
    };

    this.smallEditorInteract = function (target) {

        if (scope.selection && (target && target.isContentEditable || mw.tools.hasAnyOfClassesOnNodeOrParent(target, ['mw-small-editor', 'mw-editor'])) && scope.api.isSelectionEditable() /* && !scope.selection.isCollapsed*/) {

            if(!mw.tools.hasParentsWithClass(target, 'mw-bar')){
                var off = mw.element(target).offset();
                var ctop =   (off.offsetTop) - scope.smallEditor.$node.height();
                // var cleft =  scope.interactionData.pageX;
                var cleft =  off.left;
                scope.smallEditor.css({
                    display: 'block'
                });
                if(scope.settings.smallEditorPositionX === 'left') {
                    cleft =  off.left;
                } else if(scope.settings.smallEditorPositionX === 'center') {
                    cleft = (off.left + (off.width/2))  - (scope.smallEditor.width()/2);
                }  else if(scope.settings.smallEditorPositionX === 'right') {
                    cleft = ((off.left + off.width))  - (scope.smallEditor.width());
                }
                if(cleft < 0) {
                    cleft = 0;
                }
                var max = (cleft + scope.smallEditor.width());
                if( max > scope.actionWindow.innerWidth) {
                    cleft = max - scope.actionWindow.innerWidth;
                }

                scope.smallEditor.css({
                    top: ctop,
                    left: cleft,
                    display: 'block'
                });
            }
        } else {
            scope.smallEditor.hide();
        }
    }
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
            $('module', scope.$editArea).attr('contenteditable', false);
            areaSelectionMemo();
            scope.addDependencies();
            scope.createSmallEditor();


            var sel = scope.lastRange;
            var currentNode;
            if( !sel ) {
                currentNode = scope.editArea.lastChild || scope.editArea;
                var range = document.createRange();
                range.setStartBefore(currentNode.firstChild || currentNode);
                range.setEndAfter(currentNode.lastChild || currentNode);
                range.collapse();
                scope.lastRange = range;
            }

            scope.$editArea.on('paste input', function(e) {
                var clipboardData, pastedData;

                // Stop data actually being pasted into div
                e.stopPropagation();

                clipboardData = e.clipboardData || window.clipboardData;
                if(clipboardData) {
                    pastedData = clipboardData.getData('Text');
                }

                mw.wysiwyg.normalizeBase64Images(this.parentNode, function (){
                    scope.registerChange();
                });
            });

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

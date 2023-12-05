import {Handle} from "./handle.js";
import {GetPointerTargets} from "./pointer.js";
import {ModeAuto} from "./mode-auto.js";
import {Handles} from "./handles.js";
import {ObjectService} from "./classes/object.service.js";
import {DroppableElementAnalyzerService} from "./analizer.js";
import {DropIndicator} from "./interact.js";
import {ElementHandleContent} from "./handles-content/element.js";
import {ModuleHandleContent, moduleSettingsDispatch} from "./handles-content/module.js";
import {LayoutHandleContent} from "./handles-content/layout.js";
import {ElementManager} from "./classes/element.js";
import {lang} from "./i18n.js";
import {Dialog} from "./classes/dialog.js";
import {HandleMenu} from "./handle-menu.js";
import {InteractionHandleContent} from "./handles-content/interaction.js";
import {DomService} from "./classes/dom.js";
import "./core/@core.js";
import liveEditHelpers from "./live-edit-helpers.service.js";
import {BGImageHandles} from "./handle-bg-image";





export class LiveEdit {


    constructor(options) {

        const scope = this;

        const _e = {};
        this.on = (e, f) => {
            _e[e] ? _e[e].push(f) : (_e[e] = [f])
        };
        this.dispatch = (e, f) => {
            _e[e] ? _e[e].forEach((c) => {
                c.call(this, f);
            }) : '';
        };

        this.paused = false;
        this.activeNode = false;
        this.lastMousePosition = null;
        this.liveEditHelpers = liveEditHelpers;



        var defaults = {
            elementClass: 'element',
            backgroundImageHolder: 'background-image-holder',
            cloneableClass: 'cloneable',
            editClass: 'edit',
            stateManager: null,
            moduleClass: 'module',
            /*            rowClass: 'mw-row',
                        colClass: 'mw-col',
                        safeElementClass: 'safe-element',
                        plainElementClass: 'plain-text',
                        emptyElementClass: 'empty-element',*/
            nodrop: 'nodrop',
            allowDrop: 'allow-drop',
            unEditableModules: [
                '[type="template_settings"]'
            ],
            frameworksClasses: {
                col: ['col', 'mw-col']
            },
            document: document,
            mode: 'manual', // 'auto' | 'manual'
            lang: 'en',
            strict: true, // element and modules should be dropped only in layouts
            strictLayouts: false, // layouts can only exist as edit-field children
            viewWindow: window,

        };

        this.settings = ObjectService.extend({}, defaults, options);
        this.document = this.settings.document;

        this.stateManager = this.settings.stateManager;

        this.lang = function (key) {
            return lang(key, this.settings.lang);
        }

        if (!this.settings.root) {
            this.settings.root = this.settings.document.body
        }

        this.root = this.settings.root;


        this.elementAnalyzer = new DroppableElementAnalyzerService(this.settings);

        this.dropIndicator = new DropIndicator(this.settings);



        this.elementHandleContent = new ElementHandleContent(this);
        this.moduleHandleContent = new ModuleHandleContent(this);
        this.layoutHandleContent = new LayoutHandleContent(this);



        this.layoutHandleContent.on('insertLayoutRequest', () => {
            this.dispatch('insertLayoutRequest')
        });
        this.layoutHandleContent.on('insertLayoutRequestOnTop', () => {
            this.dispatch('insertLayoutRequestOnTop')
        });
        this.layoutHandleContent.on('insertLayoutRequestOnBottom', () => {
            this.dispatch('insertLayoutRequestOnBottom')
        });

        this.dialog = function (options) {
            if (!options) {
                options = {};
            }

            var defaults = {
                // document: scope.document,
                document: window.top.document,
                position: this.moduleHandleContent.menu.getTarget(),
                mode: 'absolute'
            };

            scope.pause();
            const _dlg = new Dialog(ObjectService.extend({}, defaults, options));

            _dlg.on('close', function () {
                scope.play();
            });

            return _dlg;
        };



        var elementHandle = this.elementHandle = new Handle({
            ...this.settings,
            $name: '$elementHandle',
            dropIndicator: this.dropIndicator,
            content: this.elementHandleContent.root,

            handle:  '.mw-handle-drag-button-element' ,

            document: this.settings.document,
            stateManager: this.settings.stateManager,
            resizable: true,

            onPosition: function(menu, transform, off){
                if(off.top < 50 ) {
                    menu.style.top = `calc(100% + 10px)`;
                } else {
                    menu.style.top = ``;
                }
            },
            offsetMenuTransform: function(scroll, off, menu){
                let transform = -60;
                if(scroll.y > (off.top - 20)) {
                    transform = (scroll.y - (off.top - 20));

                    if((transform + menu.offsetHeight + 30) > off.height) {
                        transform =  (off.height - (menu.offsetHeight + 30))  ;
                    }
                }
                return transform;
            }
        });

        this.isResizing = false;

        elementHandle.resizer.on('resizeStart', e => {
            this.isResizing = true;
            mw.app.registerChange(elementHandle.getTarget());
        });

        elementHandle.resizer.on('resizeStop', e => {
            this.isResizing = false;
            var target = mw.top().app.liveEdit.handles.get('element').getTarget();
            var css = {
                'max-width': '100%',
                'width': target.style.width
            }
            if(target.style.minHeight) {
                css['min-height'] = target.style.minHeight
            }
            if(target.style.height) {
                css['height'] = target.style.height
            }
            mw.top().app.cssEditor.style(target, css)

        });

         elementHandle.on('targetChange', target => {
            this.elementHandleContent.menu.setTarget(target);


            if (target.className.includes('col-')) {
                elementHandle.resizer.disable()
            } else {
                elementHandle.resizer.enable()
            }
            scope.handles.set('interactionHandle', null);
            scope.handles.set('layout', null);
            scope.handles.get('layout').hide();
            scope.handles.get('interactionHandle').hide();
            mw.top().app.richTextEditor.smallEditor.hide()
            mw.app.liveEdit.play();

            //mw.app.domTreeSelect(target)


            const exceptions = ['edit', 'col', 'row', 'cloneable' ];
            const classNameNamespaces = ['col-', 'w-', 'h-'];


            const resizerEnabled = !Array.from(target.classList).find(cls => !!classNameNamespaces.find(c => cls.indexOf(c) === 0)) && !DomService.hasAnyOfClasses(target, exceptions) && !DomService.hasParentsWithClass(target, 'img-as-background');

            elementHandle.resizerEnabled(resizerEnabled)
        });

        this.moduleHandle = new Handle({
            ...this.settings,
            name: '$moduleHandle',
            dropIndicator: this.dropIndicator,
            content: this.moduleHandleContent.root,
            // handle: moduleHandleContent.menu.title,
            document: this.settings.document,
            stateManager: this.settings.stateManager,
            resizable: false,
            id: 'mw-handle-item-module-menu',
            handle:  '.mw-handle-drag-button-module' ,
            setDraggableTarget: function (target) {
                if (target.nodeType === 1) {

                    return DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentElement, ['edit', 'module'])
                }
                return false;
            },
            onPosition: function(menu, transform, off){

                if(off.top < 50 ) {
                    menu.style.top = `calc(100% + 10px)`;
                } else {
                    menu.style.top = ``;
                }
            },
            offsetMenuTransform: function(scroll, off, menu){
                let transform = -60;
                if(scroll.y > (off.top - 20)) {
                    transform = (scroll.y - (off.top - 20));

                    if((transform + menu.offsetHeight + 30) > off.height) {
                        transform =  (off.height - (menu.offsetHeight + 30))  ;
                    }
                }
                return transform;
            }
        });
        var moduleHandle = this.moduleHandle;

        this.getModuleQuickSettings = type => {
            return new Promise(resolve => {
                resolve(mw.quickSettings[type]);
                this.dispatch('moduleQuickSettings', {module: type});
            });
        };

        this.getLayoutQuickSettings = () => {
            return new Promise(resolve => {
                var type = 'layouts';
                resolve(mw.layoutQuickSettings);
                this.dispatch('layoutQuickSettings', {module: type});
            });
        };

        moduleHandle.on('targetChange', target =>  {

            scope.getModuleQuickSettings(target.dataset.type).then(function (settings) {

                mw.app.liveEdit.moduleHandleContent.menu.setMenu('dynamic', settings);
                mw.app.liveEdit.moduleHandleContent.menu.setTarget(target);
                mw.app.liveEdit.moduleHandleContent.menu.show();
            });
            scope.handles.set('layout', null);
            scope.handles.set('interactionHandle', null);
            scope.handles.get('layout').hide();
            scope.handles.get('interactionHandle').hide();
            mw.top().app.richTextEditor.smallEditor.hide()
            mw.app.liveEdit.play();

            //mw.app.domTreeSelect(node)
        });

        this.layoutHandle = new Handle({
            ...this.settings,
            dropIndicator: this.dropIndicator,
            content: this.layoutHandleContent.root,
            handle: this.layoutHandleContent.menu.title,
            document: this.settings.document,
            stateManager: this.settings.stateManager,
            type: 'layout',
            offsetMenuTransform: function(scroll, off, menu){
                let transform = 10;
                if(scroll.y > (off.top - 10)) {
                    transform = (scroll.y - (off.top - 10));

                    if((transform + menu.offsetHeight + 30) > off.height && menu.offsetHeight < off.height) {
                        transform =  (off.height - (menu.offsetHeight + 30))  ;
                    }
                }
                return transform;
            },

        });


        var layoutHandle = this.layoutHandle;

        layoutHandle.wrapper.css({
            zIndex: 1000
        })

        var title = scope.lang('Layout');
        this.layoutHandleContent.menu.setTitle(title)
        layoutHandle.on('targetChange',  target => {
            scope.getLayoutQuickSettings(target.dataset.type).then(function (settings) {

                mw.app.liveEdit.layoutHandleContent.menu.setMenu('dynamic', settings)

            });

            this.layoutHandleContent.menu.setTarget(target);
            this.layoutHandleContent.menu.setTitle(title);
            if (scope.elementAnalyzer.isEditOrInEdit(target)) {
                this.layoutHandleContent.plusTop.show()
                this.layoutHandleContent.plusBottom.show()
            } else {
                this.layoutHandleContent.plusTop.hide()
                this.layoutHandleContent.plusBottom.hide()
            }
            //mw.app.domTreeSelect(target)
        });

        this.layoutHandleContent.handle = layoutHandle;
        this.moduleHandleContent.handle = moduleHandle;
        this.elementHandleContent.handle = elementHandle;

        const interactionHandleContent = new InteractionHandleContent(this);


        this.interactionHandle = new Handle({
            ...this.settings,

            content: interactionHandleContent.root,

            document: this.settings.document,

            resizable: false,
            className: 'mw-handle-item-interaction-handle'
        });
        this.interactionHandle.menu = interactionHandleContent.menu;


        this.handles = new Handles({
            interactionHandle: this.interactionHandle,
            element: elementHandle,
            module: moduleHandle,
            layout: layoutHandle,

        });
        this.observe = new GetPointerTargets(this.settings);
        this.init();
    }

    play() {
        this.paused = false;
    }

    refreshElementHandle(target) {
         mw.app.liveEdit.elementHandleContent.menu.setTarget(target);
    }

    refreshModuleHandle(target) {
         mw.app.liveEdit.moduleHandleContent.menu.setTarget(target);
    }

    refreshLayoutHandle(target) {
         mw.app.liveEdit.layoutHandleContent.menu.setTarget(target);
    }


    pause() {
        this.handles.hide();
        this.paused = true;
    }
    getSelectedNode() {
        return this.activeNode;
    }
    selectNode(target, event) {


        if(target.nodeName === 'BODY') {

            return
        }





        if (this.handles.targetIsOrInsideHandle(target ) || this.handles.targetIsSelected(target, this.interactionHandle) ) {

            return
        }


        if( target.isContentEditable ) {
            if(target.nodeName === 'IMG') {
                this.stopTyping()
                mw.top().win.mw.app.liveEdit.handles.get('element').set(target);

            }
            return;
        }





        this.activeNode = target;



        // const elements = this.observe.fromEvent(e);
        const elements = [];
        const directTargets = ['IMG'];
        const isIcon = liveEditHelpers.targetIsIcon(target);
        if(isIcon){
            elements.push(target);
        } else if (directTargets.indexOf(target.nodeName) !== -1) {
            elements.push(target);
        } else {
            elements.push(DomService.firstBlockLevel(target));
        }


        let first = elements[0];

        if(!isIcon) {
            target = DomService.firstParentOrCurrentWithAnyOfClasses(elements[0], ['element', 'module', 'cloneable', 'layout', 'edit']);
        }


        if (first.nodeName !== 'IMG' && !isIcon) {
            first = DomService.firstBlockLevel(elements[0]);
        }




        var elementTarget =  this.handles.get('element').getTarget()

        if(target && !target.classList.contains('module') && elementTarget && elementTarget.contains(target) && elementTarget.isContentEditable) {
            return
        }





        first = target;




        if(target && target === elementTarget  ) {

           if(typeof event !== 'undefined') {
               event.preventDefault();
               event.stopImmediatePropagation();
               mw.app.editor.dispatch('editNodeRequest', target, event);
           } else {
               mw.app.editor.dispatch('editNodeRequest', target);
           }


        }



        this.document.querySelectorAll('[contenteditable]').forEach(node => {

            node.contentEditable = false
        });
        this.document.querySelectorAll('[data-mw-live-edithover]').forEach(node => delete node.dataset.mwLiveEdithover);

         this.handles.get('element').set(null)
        this.handles.get('module').set(null)
        this.handles.hide();






        if (first) {
            first = this._hoverAndSelectExceptions(first)
            const type = this.elementAnalyzer.getType(first);


            if (type !== 'layout') {
                var parentLayout = DomService.firstParentOrCurrentWithClass(first, 'module-layouts');
                if (parentLayout) {
                    this.handles.set('layout', parentLayout);
                }

            }

            if (type/* && type !== 'edit'*/) {

                if (type === 'element') {
                    this.handles.hide('module');
                    this.handles.set(type, first)
                } else if (type === 'module') {
                    this.handles.hide('element');
                    this.handles.set(type, first)
                } else if (type === 'layout') {

                    this.handles.set('layout', first);
                } else if (type === 'edit') {
                    this.handles.set('element', first);
                }  else if (type === 'icon') {
                    this.handles.set('element', first);
                } else {
                    this.handles.hide();
                }
            }
            this.activeNode = first;
        } else {
            const layout = DomService.firstParentOrCurrentWithAnyOfClasses(target, ['module-layouts']);
            if (layout) {
                this.handles.set('layout', layout)
                this.activeNode = layout;
            }
        }

    }

     _hoverAndSelectExceptions = (target) => {

        if(target) {
            if (target && target.classList && target.classList.contains('module-custom-fields')) {
                var form = DomService.firstParentOrCurrentWithClass(target, 'module-contact-form');
                if (form) {
                    target = form;
                }
            }





            if (target && target.parentNode && target.parentNode.classList.contains('module-layouts')) {
                target = target.parentNode
            }


            // check if module is inaccesible and move the handle to the parent if its layout
            var isInaccesible =  liveEditHelpers.targetIsInacesibleModule(target);
            if (isInaccesible) {
                //check if parents are in layout
               var isInLayout = DomService.firstParentOrCurrentWithAnyOfClasses(target, ['module-layouts']);
               if(isInLayout){
                  target = isInLayout;
               }
            }




            if(target && target.classList.contains('mw-empty-element') || target.classList.contains('mw-col-container')){
                const col = DomService.firstParentOrCurrentWithClass(target, 'mw-col');
                if(col) {
                    target = col
                }
            }

            const isIcon = liveEditHelpers.targetIsIcon(target);

            if(isIcon) {
                return target


            } else if(!target.classList.contains('cloneable')) {

                // var newTarget = mw.app.liveEdit.elementHandleContent.settingsTarget.getSettingsTarget(target);
                // if (target !== newTarget) {
                //     target = newTarget;
                // }

                const hasCloneable = DomService.firstParentOrCurrentWithClass(target.parentElement, 'cloneable');
                if(hasCloneable) {
                    if((target.getBoundingClientRect().top - hasCloneable.getBoundingClientRect().top) < 5) {
                        target = hasCloneable;
                        hasCloneable.classList.add('element')

                    }

                }
            }

            if(!DomService.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['allow-select', 'no-select'])) {
                target = null;
            }
        }



        return target
    }


    init() {
        if (this.settings.mode === 'auto') {
            setInterval(() => ModeAuto(this), 1000)

        }








        const _eventsHandle = (e) => {





            var target = e.target ? e.target : e;


            if (target && target.className && typeof target.className === 'string' && target.className.indexOf('layout-plus') !== -1) {
                return;
            }

            this.selectNode(target, e);

        }


        function isInViewport(el) {
            if(!el || !el.parentNode) {
                return false;
            }

            const doc = el.ownerDocument;
            const win = doc.defaultView;


            const bounding = el.getBoundingClientRect();
            const elHeight = el.offsetHeight;
            const elWidth = el.offsetWidth;



            if (bounding.top >= -elHeight
                && bounding.left >= -elWidth
                && bounding.right <= (win.innerWidth || doc.documentElement.clientWidth) + elWidth
                && bounding.bottom <= (win.innerHeight || doc.documentElement.clientHeight) + elHeight) {

                return true;
            } else {

                return  false
             }
        }






        const bgImageHandles = new BGImageHandles({
            document: this.document
        });

        bgImageHandles.hide()


        let events, _hovered = [];

         events = 'mousedown touchstart';
        // events = 'click';
        ElementManager(this.root).on('mousemove', (e) => {




            const hasBg = DomService.firstParentOrCurrentWithAnyOfClasses(e.target, ['background-image-holder', 'img-holder']);

            if(hasBg && hasBg !== bgImageHandles.getTarget() && this.canBeEditable(hasBg)) {
                bgImageHandles.setTarget(hasBg)
            }


            var currentMousePosition = { x: e.pageX, y: e.pageY };
            if (this.lastMousePosition) {
                var distance = this.getDistance(this.lastMousePosition, currentMousePosition);
                if (distance >= 3) {
                    // If moved 3 pixels or more, update the last mouse position
                    this.lastMousePosition = currentMousePosition;

                } else {
                    // has not moved more than 3 pixels
                    return;
                }
            } else {
                // If it's the first mouse move event, just update the last mouse position
                this.lastMousePosition = currentMousePosition;
                // has not moved more than 3 pixels
                return;
            }



            if (this.paused || this.isResizing) {
                if(bgImageHandles) {
                    bgImageHandles.hide();
                }
                this.interactionHandle.hide();
                return
            }

            if (this.handles.targetIsOrInsideHandle(e)) {
                this.interactionHandle.hide();
                return
            }
            const elements = this.observe.fromEvent(e);
            let isTextInColumn = false;
            let isTextOnly = false;

            if(e.target && e.target.className) {
                isTextInColumn = e.target.className.indexOf('col-') !== -1;
            }
            if(e.target && e.target.className) {
                isTextOnly = !!e.target.textContent.trim() && !e.target.firstElementChild;
            }
            if(isTextInColumn && isTextOnly && this.elementAnalyzer.isEditOrInEdit(e.target)) {
                e.target.innerHTML = `<div class="element">${e.target.innerHTML}</div>`;
            }

            /*let element = e.target;
            while (e.target.nodeType !== 1){
                element = e.target.parentElement;
            }
            const elements = [element];*/

            let elementTarget = this.handles.get('element').getTarget();
            let moduleTarget = this.handles.get('module').getTarget();




            if(!isInViewport(elementTarget)) {
                this.handles.get('element').hide()
                this.handles.get('element').set(null)
            }

            if(!isInViewport(moduleTarget)) {
                this.handles.get('module').hide()
                this.handles.get('module').set(null)
            }
            let target

            if(liveEditHelpers.targetIsIcon(elements[0])) {
                target = elements[0]
            } else {
                target= DomService.firstParentOrCurrentWithAnyOfClasses(elements[0], ['element', 'module', 'cloneable', 'edit']);
            }



            const layout = DomService.firstParentOrCurrentWithAnyOfClasses(e.target, ['module-layouts']);
            let layoutHasSelectedTarget = false;




            target = this._hoverAndSelectExceptions(target);


            if (target && _hovered.indexOf(target) === -1) {
                _hovered.forEach(node => delete node.dataset.mwLiveEdithover);
                _hovered = [];


                if (!this.handles.targetIsSelected(target, this.interactionHandle)) {
                    target.dataset.mwLiveEdithover = true;
                    _hovered.push(target)
                }
            }


            if(target === this.interactionHandle.getTarget()) {
                this.interactionHandle.show();
                return
            }


            if (layout /*&& !target*/  ) {




                if (layout.contains(elementTarget)) {
                    layoutHasSelectedTarget = true;
                }

                if (layout.contains(moduleTarget)) {
                    layoutHasSelectedTarget = true;
                }



                if(!layoutHasSelectedTarget) {
                    this.handles.set('layout', layout);
                } else {
                    this.handles.set('layout', null);
                    this.handles.get('layout').hide();
                }



            }




            if (target && !this.handles.targetIsSelectedAndHandleIsNotHidden(target, this.interactionHandle) && !target.classList.contains('module-layouts')) {
                var title = '';
                if (target.dataset.mwTitle) {
                    title = target.dataset.mwTitle;
                } else if ( liveEditHelpers.targetIsIcon(target) ) {
                    title = this.lang('Icon');
                } else if (target.dataset.type) {
                    title = target.dataset.type;
                } else if (target.nodeName === 'P') {
                    title = this.lang('Paragraph');
                } else if (/(H[1-6])/.test(target.nodeName)) {
                    title = this.lang('Title') + ' ' + target.nodeName.replace(/^\D+/g, '');
                } else if (target.nodeName === 'IMG' || target.nodeName === 'IMAGE') {
                    title = this.lang('Image');
                } else if (['H1', 'H2', 'H3', 'H4', 'H5', 'H6'].includes(target.nodeName)) {
                    title = this.lang('Title ' + target.nodeName.replace('H', ''));
                } else if (['DIV', 'MAIN', 'SECTION'].includes(target.nodeName)) {
                    title = this.lang('Block');
                } else {
                    title = this.lang('Text');
                }



                this.interactionHandle.menu.setTitle(title);
                this.interactionHandle.show();
                this.interactionHandle.set(target);

                this.moduleHandle.draggablePaused(target)
            }


        })
        let _dblclicktarget

        ElementManager(this.root).on('click', (e) => {
            if(e && e.detail > 1){
                e.preventDefault();
            }
        })
        ElementManager(this.root).on('dblclick', (e) => {

            if(mw.app.isPreview()) {
                return;
            }


            if (mw.app.canvas) {
                var liveEditIframeWindow = (mw.top().app.canvas.getWindow());
                if (liveEditIframeWindow && liveEditIframeWindow.mw && liveEditIframeWindow.mw.isNavigating) {
                    //do nothing if navigation is started
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return;
                }
            }





            var selected = mw.app.liveEdit.elementHandle.getTarget();
            var module = mw.app.liveEdit.moduleHandle.getTarget();
            var layout = mw.app.liveEdit.layoutHandle.getTarget();


            var tagName = e.target.tagName.toLowerCase();



            if(layout && !selected && !module) {

                moduleSettingsDispatch(layout);
                return false
            }



            if(module && !selected && (module.contains(e.target) || e.target.id === 'mw-handle-item-module-root') ) {

                moduleSettingsDispatch(module);
                e.preventDefault();
                e.stopImmediatePropagation();
                return false
            }




            var newTarget = mw.app.liveEdit.elementHandleContent.settingsTarget.getSettingsTarget(selected);
            if (selected !== newTarget) {
                var selected = newTarget;
            }


            if (selected && !selected.contains(_dblclicktarget) ) {

                mw.app.editor.dispatch('editNodeRequest', selected);
            } else if (selected &&  selected === _dblclicktarget) {

                mw.app.editor.dispatch('editNodeRequest', selected);
            } else if (!selected && e.target.classList.contains('edit') && e.target.style.backgroundImage) {

                mw.app.editor.dispatch('editNodeRequest', e.target);
            }


        });

        this.stopTyping = () => {
            this.play();
            this.handles.get('element').set(null);
            this.handles.get('module').set(null);
            mw.app.canvas.getDocument().querySelectorAll('[contenteditable="true"]').forEach(node => {
                if(node.classList.contains('element')) {

                    node.removeAttribute('contentеditable')
                } else {
                    node.contentEditable = false;
                }
            })
        }


        ElementManager(this.root).on(events, (e) => {

            if(e.which === 1) {
            _dblclicktarget = e.target;


            let _canSelectDuringPause = true;

            const _canSelect = !this.paused || _canSelectDuringPause;


          //  var targetIsImageElement = liveEditHelpers.targetIsImageElement(target);


            if (_canSelect && !this.handles.targetIsOrInsideHandle(e.target ) ) {

                var target = e.target;

                _eventsHandle(e);

            } else {


                if (this.handles.targetIsOrInsideHandle(e.target ) ) {
                    return;
                }

                if ( this.handles.targetIsSelected(e.target, this.interactionHandle )) {

                    return
                }


                var elementTarget = this.elementHandle.getTarget();

                if ( !elementTarget || (elementTarget && !elementTarget.contains(e.target)) ) {
                    this.stopTyping()

                }


                // mw.app.liveEdit.play();
            }
            }
        });


    };


    getNoElementClasses = function () {
        var noelements = ['mw-ui-col', 'mw-col-container', 'mw-ui-col-container','container', 'img-holder','module'];
        var noelements_le = ['mw-le-spacer','background-image-holder','mw-layout-overlay-container','mw-le-resizer','mw-layout-overlay-container','mw-layout-overlay','mw-layout-overlay-background','mw-layout-overlay-background-image','mw-layout-overlay-wrapper'];


        var noelements_bs3 = mw.app.templateSettings.helperClasses.external_grids_col_classes;
        var noelements_ext = mw.app.templateSettings.helperClasses.external_css_no_element_classes;
        var noelements_drag = mw.app.templateSettings.helperClasses.external_css_no_element_controll_classes;
        var section_selectors = mw.app.templateSettings.helperClasses.section_selectors;
        var icon_selectors = mw.app.templateSettings.helperClasses.fontIconFamilies;

        noelements = noelements.concat(noelements_le);
        noelements = noelements.concat(noelements_bs3);
        noelements = noelements.concat(noelements_ext);
        noelements = noelements.concat(noelements_drag);
        noelements = noelements.concat(section_selectors);
        noelements = noelements.concat(icon_selectors);
        return noelements;
    }
    canBeElement = function (target) {

        var el = target;


        var noelements = this.getNoElementClasses();

        const exceptions = ['cloneable'];

        let can = !DomService.hasAnyOfClasses(el, noelements);

        if(!can) {
            can = DomService.hasAnyOfClasses(el, exceptions);
        }


        return can;
    }
    canBeEditable = function (el) {
        return el.isContentEditable || DomService.parentsOrCurrentOrderMatchOrOnlyFirst(el, ['edit', 'module']);
    }

    // Function to calculate the distance between two points
    getDistance = function (point1, point2) {
        const dx = point2.x - point1.x;
        const dy = point2.y - point1.y;
        return Math.sqrt(dx * dx + dy * dy);
    }


}

globalThis.LiveEdit = LiveEdit;

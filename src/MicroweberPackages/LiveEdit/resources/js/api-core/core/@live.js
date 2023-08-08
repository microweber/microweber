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


mw.require('stylesheet.editor.js');

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

        const elementHandleContent = new ElementHandleContent(this);
        const moduleHandleContent = new ModuleHandleContent(this);
        const layoutHandleContent = new LayoutHandleContent(this);

        this.elementHandleContent = elementHandleContent;
        this.moduleHandleContent = moduleHandleContent;
        this.layoutHandleContent = layoutHandleContent;

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
                position: moduleHandleContent.menu.getTarget(),
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
            dropIndicator: this.dropIndicator,
            content: elementHandleContent.root,
 
            handle:  '.mw-handle-drag-button-element' ,
 
            document: this.settings.document,
            stateManager: this.settings.stateManager,
            resizable: true,
            
            onPosition: function(menu, transform, off){
                if(off.top < 50 ) {
                    menu.style.top = `calc(100% + 60px)`;
                } else {
                    menu.style.top = ``;
                }
            }
        });

        this.isResizing = false;

        elementHandle.resizer.on('resizeStart', e => {
            this.isResizing = true;
            mw.app.registerChange(elementHandle.getTarget());
        });

        elementHandle.resizer.on('resizeStop', e => this.isResizing = false)

        elementHandle.on('targetChange', function (target) {
            elementHandleContent.menu.setTarget(target);

            if (target.className.includes('col-')) {
                elementHandle.resizer.disable()
            } else {
                elementHandle.resizer.enable()
            }
            scope.handles.set('interactionHandle', null);
            scope.handles.set('layout', null);
            scope.handles.get('layout').hide();
            scope.handles.get('interactionHandle').hide();

            mw.app.domTreeSelect(target)


        });

        this.moduleHandle = new Handle({
            ...this.settings,
            dropIndicator: this.dropIndicator,
            content: moduleHandleContent.root,
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
                    menu.style.top = `calc(100% + 60px)`;
                } else {
                    menu.style.top = ``;
                }
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
                resolve(mw.layoutQuickSettings);
                this.dispatch('layoutQuickSettings', {module: type});
            });
        };

        moduleHandle.on('targetChange', function (node) {
            scope.getModuleQuickSettings(node.dataset.type).then(function (settings) {

                mw.app.liveEdit.moduleHandleContent.menu.setMenu('dynamic', settings);
                moduleHandleContent.menu.setTarget(node);
                moduleHandleContent.menu.show();
            });
            scope.handles.set('layout', null);
            scope.handles.set('interactionHandle', null);
            scope.handles.get('layout').hide();
            scope.handles.get('interactionHandle').hide();

            mw.app.domTreeSelect(node)
        });

        this.layoutHandle = new Handle({
            ...this.settings,
            dropIndicator: this.dropIndicator,
            content: layoutHandleContent.root,
            handle: layoutHandleContent.menu.title,
            document: this.settings.document,
            stateManager: this.settings.stateManager,
            type: 'layout'
        });


        var layoutHandle = this.layoutHandle;

        layoutHandle.wrapper.css({
            zIndex: 1000
        })

        var title = scope.lang('Layout');
        layoutHandleContent.menu.setTitle(title)
        layoutHandle.on('targetChange', function (target) {
            scope.getLayoutQuickSettings(target.dataset.type).then(function (settings) {

                mw.app.liveEdit.layoutHandleContent.menu.setMenu('dynamic', settings)

            });


            layoutHandleContent.menu.setTarget(target);
            layoutHandleContent.menu.setTitle(title);
            if (scope.elementAnalyzer.isEditOrInEdit(target)) {
                layoutHandleContent.plusTop.show()
                layoutHandleContent.plusBottom.show()
            } else {
                layoutHandleContent.plusTop.hide()
                layoutHandleContent.plusBottom.hide()
            }
            mw.app.domTreeSelect(target)
        });

        layoutHandleContent.handle = layoutHandle;
        moduleHandleContent.handle = moduleHandle;
        elementHandleContent.handle = elementHandle;

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
 

        if (this.handles.targetIsOrInsideHandle(target )) {
 
   
            // this.handles.hide();
           //  this.document.querySelectorAll('[contenteditable]').forEach(node => node.contentEditable = false);
            return
        }

 

 

        this.activeNode = target;

         

        // const elements = this.observe.fromEvent(e);
        const elements = [];
        const directTargets = ['IMG']
        if (directTargets.indexOf(target.nodeName) !== -1) {
            elements.push(target);
        } else {
            elements.push(DomService.firstBlockLevel(target));
        }

        let first = elements[0];
        var target = DomService.firstParentOrCurrentWithAnyOfClasses(elements[0], ['element', 'module', 'cloneable', 'layout', 'edit']);

        if (first.nodeName !== 'IMG') {
            first = DomService.firstBlockLevel(elements[0]);
        }

 


        first = target;



        if(target === mw.app.liveEdit.handles.get('element').getTarget()) {

            event.preventDefault();
            event.stopImmediatePropagation();

 
            
            mw.app.editor.dispatch('editNodeRequest', target, event);
        }
        


        this.document.querySelectorAll('[contenteditable]').forEach(node => node.contentEditable = false);
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


        if (target && target.parentNode && target.parentNode.getAttribute('rel') === 'module') {
            if(typeof target.parentNode !== 'undefined'){
                try {
                    target = DomService.firstParentOrCurrentWithAnyOfClasses(target.parentNode, ['element', 'module', 'cloneable', 'layout', 'edit']);
                    if (!target) {
                        return target;
                    }
                } catch (error) {

                }
            }
        }


        if (target && target.parentNode && target.parentNode.classList.contains('module-layouts')) {
            target = target.parentNode
        }

       
 

        if(target && target.classList.contains('mw-empty-element') || target.classList.contains('mw-col-container')){
            const col = DomService.firstParentOrCurrentWithClass(target, 'mw-col');
            if(col) {
                target = col
            }
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

 

        class BGImageHandles {
            constructor(options = {}) {
                const defaults = {
                    document: document
                }
                this.settings = Object.assign({}, defaults, options);
                this.init()
                 
            }
 

            #menu(){ 

                const primaryMenu = [
                    {
                        title: 'Edit' ,
                        text: '',
                        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M181.674-179.761h41.13l441.087-441.565-41.13-41.13-441.087 441.565v41.13Zm613.043-484.326L665.761-793.043l36.978-37.218q19.631-19.63 47.859-19.75 28.228-.119 47.859 19.272l37.782 37.782q18.435 18.196 17.837 44.153-.598 25.956-18.315 43.674l-41.044 41.043Zm-41.76 41.761L247.761-117.13H118.804v-128.957l504.957-504.956 129.196 128.717Zm-109.392-19.565-20.804-20.565 41.13 41.13-20.326-20.565Z"></path></svg>',
                        
                        onTarget: function (target, selfNode) {
                            // console.log(target)
                        },
                        action: function(target) {
                            var dialog;
                            var picker = new mw.filePicker({
                                type: 'images',
                                label: false,
                                autoSelect: false,
                                footer: true,
                                _frameMaxHeight: true,
                                onResult: function(res) {
                                    var url = res.src ? res.src : res;
                                    if(!url) {
                                        dialog.remove();
                                        return
                                    }
                                    url = url.toString();
                                    target.style.backgroundImage = `url(${url})`; ;
                                    mw.app.get('liveEdit').play();
                                    dialog.remove();
                                }
                            });
                            dialog = mw.top().dialog({
                                content: picker.root,
                                title: mw.lang('Select image'),
                                footer: false,
                                width: 860,
          
          
                            });
                            picker.$cancel.on('click', function(){
                              dialog.remove()
                            })
          
          
                            $(dialog).on('Remove', () => {
          
                              mw.app.get('liveEdit').play();
                            })
                        }
                         
                    },
                ];

                this.menu = new HandleMenu({
                    id: 'mw-bg-image-handles-menu',
                    title: '',
                
            
                    menus: [
                        {
                            name: 'primary',
                            nodes: primaryMenu
                        },
 
                    ],
                    
                });
            
                this.menu.show()
            }

            hide() {
                this.handle.hide()
            }

            show() {
                this.handle.show()
            }

            position(target) {
                const win = target.ownerDocument.defaultView;
                var rect = target.getBoundingClientRect();
                rect.offsetTop = rect.top + win.pageYOffset;
                rect.offsetBottom = rect.bottom + win.pageYOffset;
                rect.offsetLeft = rect.left + win.pageXOffset;

      

                this.handle.css({
                    top: rect.offsetTop,
                    left: rect.offsetLeft,
                    width: rect.width,
                    height: rect.height
                })
            }

            

            #target;

            getTarget() {
                return this.#target;
            }

            setTarget(target) {
                this.#target = target;
                if(!target) {
                    this.hide()
                } else {
                    this.position(this.#target);
                    this.menu.setTarget(this.#target)
                    this.menu.show()
                    this.show()
                }
            }
 

            build() {
                const handle = mw.element(`
                    <div class="mw-bg-image-handles">
                        
                    </div>
                `)
                this.#menu();
                handle.append(this.menu.root);

                this.settings.document.body.append(handle.get(0))
    
                this.handle = handle;
                     
                     
            }

            init() {
                this.build() 
            }
        }   


        const bgImageHandles = new BGImageHandles({
            document: this.document
        });


        let events, _hovered = [];

         events = 'mousedown touchstart';
        // events = 'click';
        ElementManager(this.root).on('mousemove', (e) => {
           

            const hasBg = DomService.firstParentOrCurrentWithClass(e.target, 'background-image-holder');

            if(hasBg && hasBg !== bgImageHandles.getTarget()) {
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
                this.interactionHandle.hide();
                return
            }

            if (this.handles.targetIsOrInsideHandle(e)) {
                this.interactionHandle.hide();
                return
            }
            const elements = this.observe.fromEvent(e);
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



            let target = DomService.firstParentOrCurrentWithAnyOfClasses(elements[0], ['element', 'module', 'cloneable', 'edit']);
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

        ElementManager(this.root).on('dblclick', (e) => {

            const selected = mw.app.liveEdit.elementHandle.getTarget();
            const module = mw.app.liveEdit.moduleHandle.getTarget();
            const layout = mw.app.liveEdit.layoutHandle.getTarget();

 


            if(layout && !selected && !module) {
                moduleSettingsDispatch(layout);
                return false
            }
            if(module && !selected) {
                moduleSettingsDispatch(module);
                e.preventDefault();
                e.stopImmediatePropagation();
                return false
            }


            
            if (selected && selected.contains(_dblclicktarget)) {
                mw.app.editor.dispatch('editNodeRequest', selected);
            }

            if (!selected && e.target.classList.contains('edit') && e.target.style.backgroundImage) {
                mw.app.editor.dispatch('editNodeRequest', e.target);
            }


        })
        ElementManager(this.root).on(events, (e) => {
            if(e.which === 1) {
            _dblclicktarget = e.target;

            if (!this.paused) {
                _eventsHandle(e)
            } else {

                if (this.handles.targetIsOrInsideHandle(e.target )) {
 
   
                    // this.handles.hide();
                   //  this.document.querySelectorAll('[contenteditable]').forEach(node => node.contentEditable = false);
                    return
                }


                var elementTarget = this.elementHandle.getTarget();

               
 
                
               
                 

                if (!elementTarget || (elementTarget && !elementTarget.contains(e.target))) {
                    this.play();
                    this.handles.get('element').set(null);
                    this.handles.get('module').set(null);
                    mw.app.canvas.getDocument().querySelectorAll('[contenteditable="true"]').forEach(node => node.contentEditable = false)

                }
                 
    
                // mw.app.get('liveEdit').play();
            }
            }
        });


    };


    canBeElement = function (target) {
        var el = target;
        var noelements = ['mw-ui-col', 'mw-col-container', 'mw-ui-col-container','container'];
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
        return !mw.tools.hasAnyOfClasses(el, noelements);
    }
    canBeEditable = function (el) {
        return el.isContentEditable || mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(el, ['edit', 'module']);
    }

    // Function to calculate the distance between two points
    getDistance = function (point1, point2) {
        const dx = point2.x - point1.x;
        const dy = point2.y - point1.y;
        return Math.sqrt(dx * dx + dy * dy);
    }


}

globalThis.LiveEdit = LiveEdit;

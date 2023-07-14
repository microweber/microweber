import {Handle} from "./handle.js";
import {GetPointerTargets} from "./pointer.js";
import {ModeAuto} from "./mode-auto.js";
import {Handles} from "./handles.js";
import {ObjectService} from "./classes/object.service.js";
import {DroppableElementAnalyzerService} from "./analizer.js";
import {DropIndicator} from "./interact.js";
import {ElementHandleContent} from "./handles-content/element.js";
import {ModuleHandleContent} from "./handles-content/module.js";
import {LayoutHandleContent} from "./handles-content/layout.js";
import {ElementManager} from "./classes/element.js";
import {lang} from "./i18n.js";
import {Dialog} from "./classes/dialog.js";
import {Resizable} from "./classes/resizable.js";
import {HandleMenu} from "./handle-menu.js";

import {Tooltip} from "./tooltip.js";
import { InteractionHandleContent } from "./handles-content/interaction.js";
import { DomService } from "./classes/dom.js";
import  "./core/@core.js";


mw.require('stylesheet.editor.js');

export class LiveEdit {


    constructor(options) {

        const scope = this;

        const _e = {};
        this.on = (e, f) => { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = (e, f) => { _e[e] ? _e[e].forEach( (c) => { c.call(this, f); }) : ''; };

        this.paused = false;

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

        if(!this.settings.root) {
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
            if(!options){
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
            // handle: elementHandleContent.menu.title,
            handle: ElementManager('.mw-handle-drag-button', elementHandleContent.root.get(0)),
            handle: 'self',
            document: this.settings.document,
            stateManager: this.settings.stateManager,
            resizable: true,

        });

        this.isResizing = false;

        elementHandle.resizer.on('resizeStart', e => {
            this.isResizing = true;
            mw.app.registerChange(elementHandle.getTarget());
        });

        elementHandle.resizer.on('resizeStop', e => this.isResizing = false)

        elementHandle.on('targetChange', function (target){
            elementHandleContent.menu.setTarget(target);

            if(target.className.includes('col-')) {
                elementHandle.resizer.disable()
            } else {
                elementHandle.resizer.enable()
            }

        });

        this.moduleHandle = new Handle({
            ...this.settings,
            dropIndicator: this.dropIndicator,
            content: moduleHandleContent.root,
            handle: moduleHandleContent.menu.title,
            document: this.settings.document,
            stateManager: this.settings.stateManager,
            resizable: false,
            id: 'mw-handle-item-module-menu',
            handle: 'self',
            setDraggableTarget: function(target) {
                if (target.nodeType === 1) {

                    return DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentElement, ['edit', 'module'])
                }
                return false;
            }
        });
        var moduleHandle = this.moduleHandle;

        this.getModuleQuickSettings = type => {
            return new Promise(resolve => {
                resolve(mw.quickSettings[type]);
               this.dispatch('moduleQuickSettings', {module: type});
            });
        };


        moduleHandle.on('targetChange', function (node){



            scope.getModuleQuickSettings(node.dataset.type).then(function (settings) {




                moduleHandleContent.menu.root.remove();


                moduleHandleContent.menu = new HandleMenu({
                    id: 'mw-handle-item-element-menu',
                    title: node.dataset.type,
                    rootScope: scope,
                    buttons: settings ? settings.mainMenu || [] : [],
                    data: {target: node}
                });
                moduleHandleContent.menu.setTarget(node);
                moduleHandleContent.staticMenu.setTarget(node);


                moduleHandleContent.menu.show();

                moduleHandleContent.root.append(moduleHandleContent.menu.root);



            });

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
        layoutHandle.on('targetChange', function (target){

            layoutHandleContent.menu.setTarget(target);
            layoutHandleContent.menu.setTitle(title);
            if( scope.elementAnalyzer.isEditOrInEdit(target)) {
                layoutHandleContent.plusTop.show()
                layoutHandleContent.plusBottom.show()
            } else {
                layoutHandleContent.plusTop.hide()
                layoutHandleContent.plusBottom.hide()
            }
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
            element: elementHandle,
            module: moduleHandle,
            layout: layoutHandle,
            interactionHandle: this.interactionHandle,
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

    init() {
        if(this.settings.mode === 'auto') {
            setInterval(() =>  ModeAuto(this), 1000)

        }



        const _hoverAndSelectExceptions = (target) => {
            if(target && target.classList && target.classList.contains('module-custom-fields')) {
                var form = DomService.firstParentOrCurrentWithClass(target, 'module-contact-form');
                if(form) {
                    target = form;
                }
            }


            if (target && target.parentNode && target.parentNode.getAttribute('rel') === 'module') {
                target = DomService.firstParentOrCurrentWithAnyOfClasses(target.parentNode, ['element', 'module', 'cloneable', 'layout', 'edit']);
                if (!target) {
                    return target;
                }
            }


            if(target.parentNode.classList.contains('module-layouts')) {
                target = target.parentNode
            }

            return target
        }


        const _eventsHandle = (e) => {


            var target = e.target ? e.target : e;

            if(target && target.className && typeof target.className === 'string' && target.className.indexOf('layout-plus') !== -1) {
                return;
            }


            if(this.handles.targetIsOrInsideHandle(target, this.handles.get('layout'))) {
                // this.handles.get('element').set(null)
                // this.handles.get('module').set(null)
                this.handles.hide();
                this.document.querySelectorAll('[contenteditable]').forEach(node => node.contentEditable = false);
                return
            }
             // const elements = this.observe.fromEvent(e);
             const elements = [];
             const directTargets = ['IMG']
             if(directTargets.indexOf(target.nodeName) !== -1) {
                elements.push(e.target);
             } else {
                elements.push(DomService.firstBlockLevel(target));
             }

            let first = elements[0];
            target =  DomService.firstParentOrCurrentWithAnyOfClasses(elements[0], ['element', 'module', 'cloneable', 'layout', 'edit']);






            if(first.nodeName !== 'IMG') {
                first = DomService.firstBlockLevel(elements[0]);
            }

            first = target;


            this.document.querySelectorAll('[contenteditable]').forEach(node => node.contentEditable = false);
            this.document.querySelectorAll('[data-mw-live-edithover]').forEach(node => delete node.dataset.mwLiveEdithover);

            this.handles.get('element').set(null)
            this.handles.get('module').set(null)
            this.handles.hide();


            if(first) {
                first = _hoverAndSelectExceptions(first)
                const type = this.elementAnalyzer.getType(first);
                if(type !== 'layout') {
                    var parentLayout = DomService.firstParentOrCurrentWithClass(first, 'module-layouts');
                    if(parentLayout) {
                        this.handles.set('layout', parentLayout);
                    }

                }

                if(type/* && type !== 'edit'*/) {

                    if(type === 'element') {
                        this.handles.hide('module');
                        this.handles.set(type, first)
                    } else if(type === 'module') {
                        this.handles.hide('element');
                        this.handles.set(type, first)
                    }  else if(type === 'layout') {
                        this.handles.set('layout', first);
                    }  else if(type === 'edit') {
                        this.handles.set('element', first);
                    } else {
                        this.handles.hide();
                    }
                }

            } else {
                const layout =  DomService.firstParentOrCurrentWithAnyOfClasses(e.target, ['module-layouts']);
                if(layout) {
                    this.handles.set('layout', layout)
                }
            }

        }



            let events, _hovered = [];

            events = 'mousedown touchstart';
            ElementManager(this.root).on('mousemove', (e) => {
                if(this.paused ||  this.isResizing) {
                    this.interactionHandle.hide();
                    return
                }

                if(this.handles.targetIsOrInsideHandle(e)) {
                    this.interactionHandle.hide();
                    return
                }
                const elements = this.observe.fromEvent(e);

                let target =  DomService.firstParentOrCurrentWithAnyOfClasses(elements[0], ['element', 'module', 'cloneable', 'edit']);
                const layout =  DomService.firstParentOrCurrentWithAnyOfClasses(e.target, ['module-layouts']);
                let layoutHasSelectedTarget = false;

                target = _hoverAndSelectExceptions(target)


                if(target && _hovered.indexOf(target) === -1) {
                    _hovered.forEach(node =>  delete node.dataset.mwLiveEdithover);
                    _hovered = [];



                    if(!this.handles.targetIsSelected(target, this.interactionHandle)) {
                        target.dataset.mwLiveEdithover = true;
                        _hovered.push(target)
                    }
                }


                if(layout /*&& !target*/) {

                    const elementTarget = this.handles.get('element').getTarget();
                    const moduleTarget = this.handles.get('module').getTarget();

                    if(layout.contains(elementTarget)) {
                        layoutHasSelectedTarget = true;
                    }

                    if(layout.contains(moduleTarget)) {
                        layoutHasSelectedTarget = true;
                    }

                    this.handles.set('layout', layout);

                }


                if(target && !this.handles.targetIsSelectedAndHandleIsNotHidden(target, this.interactionHandle) && !target.classList.contains('module-layouts')) {
                    var title = '';
                    if(target.dataset.mwTitle) {
                        title = target.dataset.mwTitle;
                    } else if(target.dataset.type) {
                        title = target.dataset.type;
                    }  else if(target.nodeName === 'P') {
                        title = this.lang('Paragraph');
                    } else if(/(H[1-6])/.test(target.nodeName)) {
                        title = this.lang('Title') + ' ' + target.nodeName.replace( /^\D+/g, '');
                    } else if(target.nodeName === 'IMG' || target.nodeName === 'IMAGE') {
                        title = this.lang('Image');
                    }  else if(['H1', 'H2', 'H3', 'H4', 'H5', 'H6'].includes(target.nodeName)) {
                        title = this.lang('Title ' + target.nodeName.replace('H', ''));
                    }  else if(['DIV', 'MAIN', 'SECTION'].includes(target.nodeName)) {
                        title = this.lang('Block');
                    }   else {
                        title = this.lang('Text');
                    }

                    this.interactionHandle.menu.setTitle(title);
                    this.interactionHandle.show();
                    this.interactionHandle.set(target);
                } else {
                    this.interactionHandle.hide();
                    // mw.app.get('liveEdit').play();

                }

            })
            let _dblclicktarget

            ElementManager(this.root).on('dblclick', (e) => {

                const selected = mw.app.liveEdit.elementHandle.getTarget();

                if(selected && selected.contains(_dblclicktarget)) {
                    mw.app.editor.dispatch('editNodeRequest', selected);
                }

                if(!selected && e.target.classList.contains('edit') && e.target.style.backgroundImage) {
                    mw.app.editor.dispatch('editNodeRequest',  e.target);
                }


            })
            ElementManager(this.root).on(events, (e) => {
                _dblclicktarget = e.target;

                if ( !this.paused  ) {
                    _eventsHandle(e)
                } else {
                    var elementTarget = this.elementHandle.getTarget();
                    if(elementTarget && !elementTarget.contains(e.target)) {
                        this.play();

                    }
                    // mw.app.get('liveEdit').play();
                }
            });



    };
}

globalThis.LiveEdit = LiveEdit;

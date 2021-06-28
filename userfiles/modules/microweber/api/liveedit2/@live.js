

import {Handle} from "./handle";
import {GetPointerTargets} from "./pointer";
import {ModeAuto} from "./mode-auto";
import {Handles} from "./handles";
import {ObjectService} from "./object.service";
import {DroppableElementAnalyzerService} from "./analizer";
import {DropIndicator} from "./interact";
import {ElementHandleContent} from "./handles-content/element";
import {ModuleHandleContent} from "./handles-content/module";
import {LayoutHandleContent} from "./handles-content/layout";
import {ElementManager} from "./element";
import {i18n} from "./i18n";



export class LiveEdit {

    constructor(options) {

        const scope = this;
        const _e = {};
        this.on = (e, f) => { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = (e, f) => { _e[e] ? _e[e].forEach( (c) => { c.call(this, f); }) : ''; };

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
            strictLayouts: false // layouts can only exist as edit-field children
        };


        this.settings = ObjectService.extend({}, defaults, options);

        this.stateManager = this.settings.stateManager;

        this.lang = function (key) {
            if(!i18n[this.settings]) return key;
            return i18n[this.settings][key] || i18n[this.settings][key.toLowerCase()] || key;
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

        var elementHandle = new Handle({
            ...this.settings,
            dropIndicator: this.dropIndicator,
            content: elementHandleContent.root,
            handle: elementHandleContent.menu.title,
            document: this.settings.document,
            stateManager: this.settings.stateManager
        })
        elementHandle.on('targetChange', function (target){
            elementHandleContent.menu.setTarget(target);
            var title = '';
            if(target.nodeName === 'P') {
                title = scope.lang('Paragraph')
            } else if(/(H[1-6])/.test(target.nodeName)) {
                title = scope.lang('Title') + ' ' + target.nodeName.replace( /^\D+/g, '')
            } else {
                title = scope.lang('Text')
            }
            elementHandleContent.menu.setTitle(title)
        });


        var moduleHandle = new Handle({
            ...this.settings,
            dropIndicator: this.dropIndicator,
            content: moduleHandleContent.root,
            handle: moduleHandleContent.menu.title,
            document: this.settings.document,
            stateManager: this.settings.stateManager
        })

        var layoutHandle = new Handle({
            ...this.settings,
            dropIndicator: this.dropIndicator,
            content: layoutHandleContent.root,
            handle: layoutHandleContent.menu.title,
            document: this.settings.document,
            stateManager: this.settings.stateManager,
            type: 'layout'
        });
        var title = scope.lang('Layout');
        layoutHandleContent.menu.setTitle(title)
        layoutHandle.on('targetChange', function (target){
            layoutHandleContent.menu.setTarget(target);
             layoutHandleContent.menu.setTitle(title)
        });

        this.handles = new Handles({
            element: elementHandle,
            module: moduleHandle,
            layout: layoutHandle
        });
        this.observe = new GetPointerTargets(this.settings);
        this.init();
    }

    init() {
        if(this.settings.mode === 'auto') {
            ModeAuto(this);
        }
         ElementManager(this.root).on('mousemove touchmove', (e) => {
                if (e.pageX % 2 === 0) {
                    const elements = this.observe.fromEvent(e);
                    const first = elements[0];
                    if(first) {
                       const type = this.elementAnalyzer.getType(first);
                       if(type && type !== 'edit') {
                           this.handles.set(type, elements[0])
                           if(type === 'element') {
                               this.handles.hide('module')
                           } else if(type === 'module') {
                               this.handles.hide('element')
                           }
                       }
                    }
                }
         });
    };



}

globalThis.LiveEdit = LiveEdit;

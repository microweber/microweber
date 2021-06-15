

 import {Handle} from "./handle";
import {GetPointerTargets} from "./pointer";
import {ModeAuto} from "./mode-auto";
import {Handles} from "./handles";
import {ObjectService} from "./object.service";
import {DroppableElementAnalyzerService} from "./analizer";
 import {DropIndicator} from "./interact";
 import {ElementHandleContent} from "./handles-content/element";
  // import "./css/liveedit.scss";


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
            root: document.body,
            strict: false // todo: element and modules should be dropped only in layouts
        };

        this.settings = ObjectService.extend({}, defaults, options);

        this.root = this.settings.root;

        this.elementAnalyzer = new DroppableElementAnalyzerService(this.settings);

        this.dropIndicator = new DropIndicator();


        const elementHandleContent = new ElementHandleContent()

        this.handles = new Handles({
            element: new Handle({...this.settings, title: 'Element', dropIndicator: this.dropIndicator, content: elementHandleContent.root}),
            module: new Handle({...this.settings, title: 'module:', dropIndicator: this.dropIndicator}),
            layout: new Handle({...this.settings, title: 'layout', dropIndicator: this.dropIndicator})
        });

        this.handles.get('element').on('targetChange', function (target) {
            console.log(target);
        })

        this.handles.get('module').on('targetChange', function (target) {
            console.log('module', target);
        })

        this.observe = new GetPointerTargets(this.settings);
        //this.dropIndicator = new DropIndicator();

        this.init();
    }

    init() {

         mw.element(this.root).on('mousemove touchmove', (e) => {
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

    // action: append, prepend, before, after
    insertElement (candidate, target, action) {
        this.dispatch('beforeElementInsert', {candidate: candidate, target: target, action: action});
        mw.element(target)[action](candidate);
        this.dispatch('elementInsert', {candidate: candidate, target: target, action: action});
    };

    moveElement (candidate, target, action) {
        this.dispatch('beforeElementMove', {candidate: candidate, target: target, action: action});
        mw.element(target)[action](candidate);
        this.dispatch('elementMove', {candidate: candidate, target: target, action: action});
    };

}

globalThis.LiveEdit = LiveEdit;



import {ElementAnalyzer} from './element-types';
import {Handle} from "./handle";
import {GetPointerTargets} from "./pointer";
import {ModeAuto} from "./mode-auto";
import {Handles} from "./handles";
import {Draggable} from "./draggable";
import {ObjectService} from "./object.service";
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
            rowClass: 'mw-row',
            colClass: 'mw-col',
            safeElementClass: 'safe-element',
            plainElementClass: 'plain-text',
            emptyElementClass: 'empty-element',
            nodrop: 'nodrop',
            allowDrop: 'allow-drop',
            unEditableModules: [
                '[type="template_settings"]'
            ],
            frameworksClasses: {
                col: ['col', 'mw-col']
            },
            root: document.body
        };

        this.settings = ObjectService.extend({}, defaults, options);

        this.root = this.settings.root;

        this.elementAnalyzer = new ElementAnalyzer(this.settings);

        this.handles = new Handles({
            handleElement: new Handle(),
            handleModule: new Handle(),
            handleLayout: new Handle()
        });

        this.observe = new GetPointerTargets(this.settings);
        this.init();
    }

    init() {

        mw.element(this.root).on('mousemove touchmove', (e) => {
            if (e.pageX % 2 === 0) {
                var elements = this.observe.fromEvent(e);
                this.handles.hideAllExceptCurrent(e);
                if(elements[0]) {
                    this.handles.set('handleElement', elements[0])
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

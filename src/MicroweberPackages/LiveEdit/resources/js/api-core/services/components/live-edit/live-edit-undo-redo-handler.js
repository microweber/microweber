import BaseComponent from "../../containers/base-class";
import {DomService} from "../../../core/classes/dom";

export class LiveEditUndoRedoHandler extends BaseComponent {
    constructor() {
        super();

        this.dragElementStart = null;
        this.dragElementEnd = null;
        this.dragElementOriginalHtmlForUndo = null;
        this.dragElementTargetHtmlForUndo = null;
        this.dragElementTargetForUndo = null;

        mw.app.state.on('change', (data) => {
            this.handleUndoRedo(data);
        });



        this.startTarget = null;
        this.endTarget = null;


        this.handleDragAndDropUndoRedo();
 
    }


    handleDragAndDropUndoRedo () {
        const handles = ['element', 'module'];

        handles.forEach(hndl => {
            
            mw.app.liveEdit.handles.get(hndl)
            .draggable
            .on('dragStart', data => {
                this.startTarget = DomService.firstParentOrCurrentWithClass(data.element, 'edit');
                this.startTarget.__html = this.startTarget.innerHTML;
            })
            .on('beforeDrop', data => {
                this.endTarget =  DomService.firstParentOrCurrentWithClass(data.event.target, 'edit');
                this.endTarget.__html = this.endTarget.innerHTML;
            })
            .on('drop', data => {
               
                const rec1 = {
                    target:  this.endTarget,
                    value:  this.endTarget.__html,
                    originalEditField: this.startTarget,
                    originalEditFieldInnerHTML: this.startTarget.__html,
                };

                const rec2 = {
                    target:  this.endTarget,
                    value:  this.endTarget.innerHTML,
                    originalEditField: this.startTarget,
                    originalEditFieldInnerHTML: this.startTarget.innerHTML,
                }

                mw.app.state.record(rec1);
                mw.app.state.record(rec2);

                this.startTarget = null;
                this.endTarget = null;
            });
        })
    }


    afterUndoRed() {
        mw.app.canvas.getDocument().querySelectorAll('.mw-element-is-dragged').forEach(node => {
            node.classList.remove('mw-element-is-dragged')
        })
    } 

    handleUndoRedo = (data) => {

        if (data.active) {
            var doc = mw.app.canvas.getDocument();

            var target = data.active.target;


            if(target === '$liveEditCSS') {
                mw.app.get('cssEditor').setPropertyForSelector(data.active.value.selector, data.active.value.property, data.active.value.value, false);
                return;
            }
            if (typeof target === 'string') {
                target = doc.querySelector(data.active.target);
            }

            if (!data.active || (!target && !data.active.action)) {

                return;
            }

            if (data.active.action) {
                data.active.action(data);
            } else if (doc.body.contains(target)) {
                const getTarget = function(target) {
                    if(!target.parentNode || !target.ownerDocument) {
                        var selector;
                        if(target.id) {
                            selector = '#' + target.id;
                        } else if(target.classList.contains('edit')) {
                            var field = node.getAttribute('field');
                            var rel = node.getAttribute('rel');
                            if(field && rel){
                                selector = '.edit[field="'+field+'"][rel="'+rel+'"]';
                            }
                        }  
                        if (selector) {
                            target = doc.querySelector(selector)
                        }
                        
                    }
                    return target;
                }

                var originalEditField;

                if(data.active.originalEditField && data.active.originalEditField !== target) {
                    originalEditField = getTarget(data.active.originalEditField);
                }

                target = getTarget(target);
                
                if(target) {
                    mw.element(target).html(data.active.value);
                }
                if(originalEditField) {
                    mw.element(originalEditField).html(data.active.originalEditFieldInnerHTML);
                }
                
            } else {
                if (target.id) {
                    mw.element(doc.getElementById(target.id)).html(data.active.value);
                }
            }
            if (data.active.prev) {
                mw.$(data.active.prev).html(data.active.prevValue);
            }
        }
        this.afterUndoRed()
    }


    registerUndoState = (element) => {
        var edit = mw.tools.firstParentOrCurrentWithClass(element, 'edit');
        var module = mw.tools.firstParentOrCurrentWithClass(element, 'module');
        if (edit && edit.getAttribute('rel') && edit.getAttribute('field') && edit.innerHTML) {
            mw.app.state.record({
                target: edit,
                value: edit.innerHTML
            });
        } else if (module && module.innerHTML) {
            mw.app.state.record({
                target: module,
                value: module.innerHTML
            });
        } else if (element && element.innerHTML) {
            mw.app.state.record({
                target: element,
                value: element.innerHTML
            });
        }
    }
}

export default LiveEditUndoRedoHandler;

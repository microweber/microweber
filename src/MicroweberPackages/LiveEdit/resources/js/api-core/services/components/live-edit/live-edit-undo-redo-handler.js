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

            let tempStart;
            let tempEnd;

            mw.app.liveEdit.handles.get(hndl)
            .draggable
            .on('dragStart', data => {
                this.startTarget = DomService.firstParentOrCurrentWithClass(data.element, 'edit');
                this.startTarget.__html = this.startTarget.innerHTML;

                tempStart = {
                    target:  this.startTarget,
                    value:  this.startTarget.__html,
                }
            })
            .on('beforeDrop', data => {
                this.endTarget =  DomService.firstParentOrCurrentWithClass(data.event.target, 'edit');
                this.endTarget.__html = this.endTarget.innerHTML;

                tempEnd = {
                    target:  this.endTarget,
                    value:  this.endTarget.__html,
                }
            })
            .on('drop', data => {

                var endTargethtml = '';
                var startTargethtml = '';
                if(this.endTarget && this.endTarget.__html) {
                    endTargethtml = this.endTarget.__html;
                }
                if(this.startTarget && this.startTarget.__html) {
                    startTargethtml = this.startTarget.__html;
                }

                const rec1 = {
                    target:  this.endTarget,
                    value:  endTargethtml,
                    originalEditField: this.startTarget,
                    originalEditFieldInnerHTML: startTargethtml,
                };

                const rec2 = {
                    target:  this.endTarget,
                    value: startTargethtml,
                    originalEditField: this.startTarget,
                    originalEditFieldInnerHTML: endTargethtml,
                }

                /*mw.app.state.record(rec1);
                mw.app.state.record(rec2);*/
                mw.app.state.record({
                    target: "$multistate",
                    value: [
                        {...tempStart},
                        {...tempEnd},

                    ]
                });

                mw.app.state.record({
                    target: "$multistate",
                    value: [

                        {
                            target:  this.startTarget,
                            value: this.startTarget.innerHTML,
                        },
                        {
                            target:  this.endTarget,
                            value: this.endTarget.innerHTML,
                        }
                    ]
                });

                this.startTarget = null;
                this.endTarget = null;
            });
        })
    }


    afterUndoRedo() {
        mw.app.canvas.getDocument().querySelectorAll('.mw-element-is-dragged').forEach(node => {
            node.classList.remove('mw-element-is-dragged')
        });
        if(mw.app.liveEdit) {
            mw.app.liveEdit.handles.reposition();
        }
    }


    #stateTypeDataHandles = {
        $liveEditStyle: active => {
            this.#stateTypeHandles.$liveEditStyle( active.selector,  active.value, false );
        },
        $liveEditCSS: active => {

            this.#stateTypeHandles.$liveEditCSS( active.value.selector, active.value.property, active.value.value, false);
        },
        customAction: active => {
            this.#stateTypeHandles.customAction(active.action, active);
        },

        html: active => {
            // actual target may not be present in the document must be get by selector
            const getTarget = function(target) {
                var doc = mw.app.canvas.getDocument();

                var selector;
                if(target.id) {
                    selector = '#' + target.id;
                } else if(target.classList.contains('edit')) {
                    var field = target.getAttribute('field');
                    var rel = target.getAttribute('rel');
                    if(field && rel){
                        selector = '.edit[field="'+field+'"][rel="'+rel+'"]';
                    }
                }
                if (selector) {
                    target = doc.querySelector(selector);
                }

                return target;
            }

            var originalEditField;

            if(active.originalEditField && active.originalEditField !== target) {
                originalEditField = getTarget(active.originalEditField);
            }

            var target = getTarget(active.target);

            if(target) {

                this.#stateTypeHandles.html(target, active.value)
            }
            if(originalEditField) {

                this.#stateTypeHandles.html(originalEditField, active.originalEditFieldInnerHTML)
            }
        }
    }
    #stateTypeHandles = {
        $liveEditStyle: (selector, value) => {
            mw.top().app.cssEditor.style( selector,  value, false )
        },
        $liveEditCSS: (selector, property, value) => {

            mw.top().app.cssEditor.setPropertyForSelector( selector, property, value, false);
        },
        customAction:(action, data) => {
            action.call(undefined, data);
        },
        html: (target, html) => {
            target.innerHTML = html;
        },
        $multistate: (data) => {
            for (let i = 0; i < data.value.length; i++) {

                const type = data.value[i].type || 'html';
                if(this.#stateTypeHandles[type]) {
                    this.#stateTypeDataHandles[type](data.value[i]);
                }
            }
        }
    }

    #stateTypeHandle(data) {
        var doc = mw.app.canvas.getDocument();

        var target = data.active.target;

        if(target === '$multistate' ) {
            this.#stateTypeHandles.$multistate(data.active);
            return;
        }
        if(target === '$liveEditStyle' && mw.top().app.cssEditor) {
            this.#stateTypeDataHandles.$liveEditStyle(data.active );
            return;
        }
        if(target === '$liveEditCSS' && mw.top().app.cssEditor) {

            this.#stateTypeDataHandles.$liveEditCSS(data.active);
            return;
        }
        if (typeof target === 'string') {
            target = doc.querySelector(data.active.target);
        }

        if (!data.active || (!target && !data.active.action)) {
            return;
        }

        if (data.active.action) {

            this.#stateTypeDataHandles.customAction(data.active)
        } else  {
            this.#stateTypeDataHandles.html(data.active)

        }
        if (data.active.prev) {

            this.#stateTypeHandles.html(data.active.prev, data.active.prevValue)
        }
    }

    handleUndoRedo = (data) => {

        if (data.active) {
            this.#stateTypeHandle(data)
        }
        this.afterUndoRedo()
    }

    registerUndoStateNow = (element) => {
        if(!element){
            return;
        }


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
    registerUndoStateTimeout = null;

    registerUndoState = (element) => {
       // this.registerUndoStateNow(element);
        if(this.registerUndoStateTimeout) {
            clearTimeout(this.registerUndoStateTimeout);
        }
        this.registerUndoStateTimeout = setTimeout(() => {
            this.registerUndoStateNow(element);
        }, 100, element);


    }
}

export default LiveEditUndoRedoHandler;

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

        mw.app.liveEdit.handles.get('element').draggable.on('dragStart', data => {
            this.recordStartDragElementForUndo(data);
        });

        mw.app.liveEdit.handles.get('element').draggable.on('dragEnd', data => {
            this.recordEndDragElementForUndo(data);
        });
        mw.app.liveEdit.handles.get('element').draggable.on('beforeDragEnd', data => {
            this.recordBeforeEndDragEndElementForUndo(data);
        });

        mw.app.liveEdit.handles.get('module').draggable.on('dragStart', data => {
            this.recordStartDragElementForUndo(data);
        });

        mw.app.liveEdit.handles.get('module').draggable.on('dragEnd', data => {
            this.recordEndDragElementForUndo(data);
        });

        mw.app.liveEdit.handles.get('element').draggable.on('beforeDragEnd', data => {
            this.recordBeforeEndDragEndElementForUndo(data);
        });
    }

    recordBeforeEndDragEndElementForUndo = (data) => {
        var originalTarget = data.target;
        var editOriginal = false;
        var editTarget = false;

        if (originalTarget) {
            var editOriginal = DomService.firstParentOrCurrentWithClass(originalTarget, 'edit');
            if (editOriginal) {
                this.dragElementOriginalHtmlForUndo = editOriginal.innerHTML;
                this.dragElementTargetForUndo = editOriginal;

            }
        }

        var newTarget = data.element;
        if (newTarget) {
            var editTarget = DomService.firstParentOrCurrentWithClass(newTarget, 'edit');
            if (editTarget) {
                this.dragElementTargetHtmlForUndo = editTarget.innerHTML;
            }
        }

        if (editOriginal && editTarget) {
            if (editOriginal === editTarget) {
                this.dragElementTargetHtmlForUndo = editOriginal.innerHTML;
                this.dragElementTargetForUndo = editOriginal;
            } else {
                var findFirstCommonAncestor = DomService.findFirstCommonAncestor(editTarget, editOriginal);
                if (findFirstCommonAncestor) {
                    this.dragElementTargetHtmlForUndo = findFirstCommonAncestor.innerHTML;
                    this.dragElementTargetForUndo = findFirstCommonAncestor;
                }
            }
        }

    }
    recordStartDragElementForUndo = (data) => {

        var edit = DomService.firstParentOrCurrentWithClass(data.element, 'edit');
        if (edit) {
            data.originalEditField = edit;
            data.originalEditFieldInnerHTML = edit.innerHTML;
        }


        this.dragElementStart = data;
    }
    recordEndDragElementForUndo = (data) => {
        this.dragElementEnd = data;

        var editOriginalInnerHTML = this.dragElementOriginalHtmlForUndo;
        var editTargetInnerHTML = this.dragElementTargetHtmlForUndo;
        var dragElementTargetForUndo = this.dragElementTargetForUndo;

        if (editTargetInnerHTML && editOriginalInnerHTML) {
            var targetForUndo = dragElementTargetForUndo;
            var htmlforUndo = editTargetInnerHTML;
            mw.app.state.record({
                target: targetForUndo,
                value: htmlforUndo
            });
        }

    }
    handleUndoRedo = (data) => {

        if (data.active) {
            var doc = mw.app.canvas.getDocument();

            var target = data.active.target;
            if (typeof target === 'string') {
                target = doc.querySelector(data.active.target);
            }

            if (!data.active || (!target && !data.active.action)) {

                return;
            }

            if (data.active.action) {
                data.active.action();
            } else if (doc.body.contains(target)) {
                mw.element(target).html(data.active.value);
            } else {
                if (target.id) {
                    mw.element(doc.getElementById(target.id)).html(data.active.value);
                }
            }
            if (data.active.prev) {
                mw.$(data.active.prev).html(data.active.prevValue);
            }
        }
    }


    registerUndoState = (element) => {
        var edit = mw.tools.firstParentOrCurrentWithClass(element, 'edit');
        if (edit) {
            if (edit.getAttribute('rel') && edit.getAttribute('field')) {
                mw.app.state.record({
                    target: edit,
                    value: edit.innerHTML
                });
            }
        } else {
            var module = mw.tools.firstParentOrCurrentWithClass(element, 'module');
            if (module) {

                mw.app.state.record({
                    target: module,
                    value: module.innerHTML
                });
            }
        }
    }
}

export default LiveEditUndoRedoHandler;

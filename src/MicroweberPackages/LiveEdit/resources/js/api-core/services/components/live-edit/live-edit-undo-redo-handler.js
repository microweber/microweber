import BaseComponent from "../../containers/base-class";
import alert from "bootstrap/js/src/alert";

export class LiveEditUndoRedoHandler extends BaseComponent {
    constructor() {
        super();

        mw.app.state.on('change',  (data) => {

            this.handleUndoRedo(data);
        });
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
        if(edit) {
            if(edit.getAttribute('rel') && edit.getAttribute('field')) {
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

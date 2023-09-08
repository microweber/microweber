import BaseComponent from "../../containers/base-class";

export class LiveEditUndoRedoHandler extends BaseComponent {
    constructor() {
        super();

        mw.app.state.on('change',  (data) => {

            this.handleUndoRedo(data);
        });
    }

    handleUndoRedo = (data) => {

        if (data.active) {
            var doc = mw.app.get('canvas').getDocument();

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
}

export default LiveEditUndoRedoHandler;

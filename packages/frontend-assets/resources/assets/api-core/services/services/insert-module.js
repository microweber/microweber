import axios from "axios";
import { ElementManager } from "../../core/classes/element.js";
import { afterLayoutChange } from "../../core/handles-content/layout-actions.js";
import MicroweberBaseClass from "../containers/base-class.js";


export const insertModule = (target = null, module, options = {}, insertLocation = 'top', explicitAction) => {



    if (!target || !module) {
        return;
    }
    return new Promise(async resolve => {

        //todo: optimise
        let isTextEditing = mw.top().app.richTextEditor?.smallEditor?.get(0).style.display !== 'none'
                && !mw.top().win.mw.app.liveEdit.handles.get('element').isVisible()
                && !mw.top().win.mw.app.liveEdit.handles.get('module').isVisible();


            mw.spinner({element: target, decorate: true}).show()

            // Always tear the decorate-spinner down, even if the module insert
            // rejects or hangs — otherwise the `.mw-spinner.mw-spinner-mode-append`
            // node is left inside the edit region and, since Save doesn't strip it,
            // gets persisted into the content and reappears on every reload.
            let itm;
            try {
                itm = await target.ownerDocument.defaultView.mw.module.insert(target, module, options, insertLocation, mw.liveEditState, explicitAction);
            } finally {
                mw.spinner({element: target, decorate: true}).remove()
            }





            if(isTextEditing) {

            } else {
                mw.top().win.mw.app.liveEdit.handles.get('element').set(mw.top().win.mw.app.liveEdit.handles.get('element').getTarget());
                mw.top().win.mw.app.liveEdit.handles.get('module').set(mw.top().win.mw.app.liveEdit.handles.get('module').getTarget());
            }


        mw.top().app.dispatch('moduleInserted', {target, module, options, insertLocation})
        resolve(itm);

        afterLayoutChange(target)
    });
}

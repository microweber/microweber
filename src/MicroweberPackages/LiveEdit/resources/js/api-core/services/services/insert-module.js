import axios from "axios";
import { ElementManager } from "../../core/classes/element";
import { afterLayoutChange } from "../../core/handles-content/layout-actions";


export const insertModule = (target = null, module, options = {}, insertLocation = 'top') => {


    if (!target || !module) {
        return;
    }
    return new Promise(async resolve => {

        //todo: optimise
        let isTextEditing = mw.top().app.richTextEditor.smallEditor.get(0).style.display !== 'none'
                && !mw.top().win.mw.app.liveEdit.handles.get('element').isVisible()
                && !mw.top().win.mw.app.liveEdit.handles.get('module').isVisible();

        await target.ownerDocument.defaultView.mw.module.insert(target, module, options, insertLocation, mw.liveEditState);






            if(isTextEditing) {

            } else {
                mw.top().win.mw.app.liveEdit.handles.get('element').set(mw.top().win.mw.app.liveEdit.handles.get('element').getTarget());
                mw.top().win.mw.app.liveEdit.handles.get('module').set(mw.top().win.mw.app.liveEdit.handles.get('module').getTarget());
            }


        mw.top().win.mw.app.dispatch('moduleInserted')
        resolve();

        afterLayoutChange(target)
    });
}

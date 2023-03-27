import {LiveEdit} from '../../../liveedit2/@live.js';
import {LiveEditCanvasService} from "../../services/live-edit-canvas.service.js";
import {LiveEditCanvas} from "../../components/live-edit-canvas/live-edit-canvas.js";

export const liveEditComponent = () => {
    const frame = LiveEditCanvasService.getFrame();
    const frameHolder = frame.parentElement;
    const doc = LiveEditCanvasService.getDocument();
    const link = doc.createElement('link');
    link.rel = 'stylesheet';
    link.href = `${mw.settings.site_url}userfiles/modules/microweber/api/liveedit2/css/dist.css`;
    doc.head.prepend(link);

    const liveEdit = new LiveEdit({
        root: doc.body,
        strict: false,
        mode: 'auto',
        document: doc
    });


    mw.liveEditApp.call('onLiveEditReady');

    mw.liveEditApi.add('liveEdit', liveEdit);
    mw.liveEditApi.add('state', mw.liveEditState);






}

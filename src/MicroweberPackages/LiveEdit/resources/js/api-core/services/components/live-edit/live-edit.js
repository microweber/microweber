import {LiveEdit} from '../../../core/@live.js';



import liveeditCssDist from '../../../core/css/scss/liveedit.scss';



export const liveEditComponent = () => {
    const frame = mw.app.get('canvas').getFrame();
    const frameHolder = frame.parentElement;
    const doc = mw.app.get('canvas').getDocument();
    const link = doc.createElement('style');
    link.textContent = liveeditCssDist;

   // alert(liveeditCssDist)
   // link.href = `${mw.settings.site_url}userfiles/modules/microweber/api/liveedit2/css/dist.css`;
   // link.href = `${mw.settings.site_url}userfiles/modules/microweber/api/liveedit2/css/dist.css`;
    doc.head.prepend(link);

    const liveEdit = new LiveEdit({
        root: doc.body,
        strict: false,
        mode: 'auto',
        document: doc
    });



    mw.app.call('onLiveEditReady');

    mw.app.register('liveEdit', liveEdit);
    mw.app.register('state', mw.liveEditState);

}

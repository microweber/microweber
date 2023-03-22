import React, { useEffect, useRef,useState,createContext,useContext} from 'react';

import LiveEdit from '../../liveedit2/@live.js'
import {LiveEditContext} from "./contexts/live-edit-context";

function LiveEditorFrame() {
    const frameRef = useRef(null);
    const frameUrl = useRef(null);
    const frameHolderRef = useRef(null);
    const [frameSrc, setFrameSrc] = useState(null);
    const ebasi2 = LiveEditContext;

    useEffect(() => {
        const frame = frameRef.current;
        const frameHolder = frameHolderRef.current;
        mw.log(ebasi2);
        setFrameSrc(mw.settings.site_url + '/?editmode=n');
        mw.spinner({
            element: frameHolder,
            size: 52,
            decorate: true
        });

        frame.addEventListener('load', function () {
            const doc = frame.contentWindow.document;
            const link = doc.createElement('link');
            link.rel = 'stylesheet';
            link.href = mw.settings.site_url + '/userfiles/modules/microweber/api/liveedit2/css/dist.css';
            doc.head.prepend(link);

            var liveEdit = new LiveEdit({
                root: frame.contentWindow.document.body,
                strict: false,
                mode: 'auto',
                document: frame.contentWindow.document
            });

            liveEdit.moduleHandle.on('targetChange', function (target) {

            });

            mw.spinner({
                element: frameHolder
            }).remove();

            mw.trigger('LiveEdit::ready', liveEdit);
        });

        // Clean up event listener
        return () => {
            frame.removeEventListener('load', () => {
            });
        }
    }, []);

    return (
        <div>
            <div ref={frameHolderRef}></div>
            <iframe id="live-editor-frame" ref={frameRef} src={frameSrc}></iframe>
        </div>
);
}

export default LiveEditorFrame;

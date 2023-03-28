
import {EditorComponent} from './components/editor/editor.js';
import { LiveEditCanvas } from './components/live-edit-canvas/live-edit-canvas.js';
import {liveEditComponent} from "./components/live-edit/live-edit.js";

import {MWUniversalContainer} from "./containers/container.js";

;(() => {

    mw.app = new MWUniversalContainer();

    const canvas = new LiveEditCanvas();
    const canvasHolder = document.getElementById('live-edit-frame-holder');

    canvas.mount(canvasHolder);
    mw.app.register('canvas', canvas);
    canvas.on('liveEditCanvasLoaded', () => {

        new EditorComponent();
        liveEditComponent();
    });




})();

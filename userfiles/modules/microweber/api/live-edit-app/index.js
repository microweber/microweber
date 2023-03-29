
import {EditorComponent} from './components/editor/editor.js';
import { LiveEditCanvas } from './components/live-edit-canvas/live-edit-canvas.js';
import {liveEditComponent} from "./components/live-edit/live-edit.js";


import { Commands } from './services/commands.js';

;(() => {

    

    const canvas = new LiveEditCanvas();
    const canvasHolder = document.getElementById('live-edit-frame-holder');

    canvas.mount(canvasHolder);
    mw.app.register('canvas', canvas);
    mw.app.register('commands', Commands);
    mw.app.canvas.on('liveEditCanvasLoaded', () => {

        new EditorComponent();
        liveEditComponent();
    });




})();

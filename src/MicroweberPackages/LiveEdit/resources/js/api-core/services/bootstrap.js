import './index.js'

import {MWUniversalContainer} from "./containers/container.js";


import {EditorComponent} from "./components/editor/editor.js";
import {LiveEditCanvas} from "./components/live-edit-canvas/live-edit-canvas.js";
import {liveEditComponent} from "./components/live-edit/live-edit.js";
import {Commands} from "./services/commands.js";
import {Modules} from "./services/modules.js";
import {Layouts} from "./services/layouts.js";


mw.app = new MWUniversalContainer();


setTimeout(function() {
    const canvas = new LiveEditCanvas();
    const canvasHolder = document.getElementById('live-edit-frame-holder');

    mw.app.register('canvas', canvas);
    mw.app.register('commands', Commands);
    mw.app.register('modules', Modules);
    mw.app.register('layouts', Layouts);
//mw.app.register('commands', Commands);

    canvas.mount(canvasHolder);

    mw.app.canvas.on('liveEditBeforeLoaded', function () {
        mw.app.dispatch('init');
    });

    mw.app.canvas.on('liveEditCanvasLoaded', () => {
        // new EditorComponent();
        // liveEditComponent();
        mw.app.dispatch('ready');
    });

}, 300);

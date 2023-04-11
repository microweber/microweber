//require('./bootstrap.js');

// mw live-edit core
import '../api-core/services/bootstrap.js';



const canvas = new LiveEditCanvas();
const canvasHolder = document.getElementById('live-edit-frame-holder');

mw.app.register('canvas', canvas);

canvas.mount(canvasHolder);

mw.app.canvas.on('liveEditBeforeLoaded', function () {

    mw.app.dispatch('init');
});

mw.app.canvas.on('liveEditCanvasLoaded', () => {
    // new EditorComponent();
    // liveEditComponent();
    mw.app.dispatch('ready');
});




// vue
import {createApp} from 'vue';
import App from './App.vue';

// vue click away
import VueClickAway from "vue3-click-away";

// emiter
import mitt from 'mitt';
const emitter = mitt();

import './css/app.sass';
import  './css/gui.css';
import  './css/index.css';
import {LiveEditCanvas} from "../api-core/services/components/live-edit-canvas/live-edit-canvas";

const app = createApp(App);
app.config.globalProperties.emitter = emitter;
app.use(VueClickAway);
app.mount('#live-edit-app');

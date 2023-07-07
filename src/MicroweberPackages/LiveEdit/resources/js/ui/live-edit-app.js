//require('./bootstrap.js');

// mw live-edit core
import '../api-core/services/bootstrap.js';



const canvas = new LiveEditCanvas();
const canvasHolder = document.getElementById('live-edit-frame-holder');

mw.app.register('canvas', canvas);

canvas.mount(canvasHolder);

mw.app.canvas.on('iframeKeyDown', function (data) {
    const event = data.event;
    if (event.key == "Escape") {
        const dialog = mw.dialog.get();
        if(dialog) {
            dialog.remove()
        }
    }
})
mw.app.canvas.on('liveEditBeforeLoaded', function () {

    mw.app.dispatch('init');
});

mw.app.canvas.on('liveEditCanvasLoaded', (data) => {

    window.top.history.pushState(null, null, `?url=${encodeURIComponent(data.frameWindow.location.href)}`);

    const cssGUIEditor = new mw.liveeditCSSEditor({
        document: data.frameDocument
    });

    mw.app.register('cssEditor', cssGUIEditor);
    mw.app.dispatch('ready');
});

window.top.addEventListener('popstate', function(){
    mw.app.canvas.getFrame().src = decodeURIComponent(new URLSearchParams(window.top.location.search).get('url')  || '/');
})




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

app.directive('tooltip', (el, binding) => {
    return new bootstrap.Tooltip(el);
});
app.config.globalProperties.emitter = emitter;
app.use(VueClickAway);
app.mount('#live-edit-app');

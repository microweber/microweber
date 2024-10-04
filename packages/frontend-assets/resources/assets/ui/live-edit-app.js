//require('./bootstrap.js');

// mw live-edit core
import '../api-core/services/bootstrap.js';
import '../libs/jseldom/jseldom-jquery.js';

import {StylesheetEditor} from "../api-core/services/services/stylesheet-editor.service.js";
import {ThemeCssVarsEditor} from "../api-core/services/services/theme-css-vars-editor.service.js";
// vue
import {createApp} from 'vue';

import App from './App.vue';
// vue click away
import VueDragResize from 'vue-drag-resize';
import { createVfm } from 'vue-final-modal'

import VueClickAway from "vue3-click-away";
// emiter
import mitt from 'mitt';
import './css/app.sass';
import './css/gui.css';
import './css/index.css';
import 'vue-final-modal/style.css';
import { MWBroadcast } from '../api-core/services/services/broadcast.js';


const vfm = createVfm();


console.log(mw.settings)


// const canvas = new LiveEditCanvas();
// mw.app.register('canvas', canvas);
const canvasHolder = document.getElementById('live-edit-frame-holder');
mw.app.canvas.mount(canvasHolder);


//mw.app.canvas = canvas;

mw.app.canvas.on('iframeKeyDown', function (data) {
    const event = data.event;
    if (event.key == "Escape") {
        const dialog = mw.dialog.get();
        if (dialog) {
            dialog.remove()
        }
    }
})
mw.app.canvas.on('liveEditBeforeLoaded', function () {

    mw.app.dispatch('init');
});

mw.app.register('broadcast', MWBroadcast);

const a = mw.app;

mw.app.canvas.on('liveEditCanvasLoaded', (data) => {


      mw.top().app.broadcast.message('canvasURL', {url: data.frameWindow.location.href})

    window.top.history.pushState(null, null, `?url=${encodeURIComponent(data.frameWindow.location.href)}`);


    mw.app.remove('cssEditor');
    var doc = mw.app.canvas.getDocument();
    var liveEditCssStylesheetElement = doc.querySelector('#mw-template-settings');
    if(!liveEditCssStylesheetElement){
         var liveEditCssStylesheetElement = doc.querySelector('link[href*="live_edit.css"]');
    }


    var cssUrl = '';
    if (liveEditCssStylesheetElement) {
        //get the css url
        var cssUrl = liveEditCssStylesheetElement.getAttribute('href');

    }


    const cssGUIEditor = new StylesheetEditor({
        document: doc,
        cssUrl: cssUrl

    });
    const themeCssVarsEditorInstance = new ThemeCssVarsEditor();
 //   mw.app.register('cssEditor', cssGUIEditor);
    mw.app.cssEditor = cssGUIEditor;
    mw.app.themeCssVarsEditor = themeCssVarsEditorInstance;
    mw.app.dispatch('ready');
});

window.top.addEventListener('popstate', function () {
    mw.app.canvas.setUrl(decodeURIComponent(new URLSearchParams(window.top.location.search).get('url') || '/'));
})


const emitter = mitt();

const app = createApp(App);
app.component('vue-drag-resize', VueDragResize);




app.config.globalProperties.emitter = emitter;
app.use(VueClickAway);
app.use(vfm);
app.mount('#live-edit-app');

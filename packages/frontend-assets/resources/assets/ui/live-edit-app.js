//require('./bootstrap.js');
import {createVuetify} from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
// mw live-edit core
import '../api-core/services/bootstrap.js';
import '../services/mw-ai-chat-sidebar.js';
import '../libs/jseldom/jseldom-jquery.js';

import {StylesheetEditor} from "../api-core/services/services/stylesheet-editor.service.js";
import {ThemeCssVarsEditor} from "../api-core/services/services/theme-css-vars-editor.service.js";
// vue
import {createApp} from 'vue';

import App from './App.vue';
// vue click away
import VueDragResize from 'vue-drag-resize';
import {createVfm} from 'vue-final-modal'

import VueClickAway from "vue3-click-away";
// emiter
import mitt from 'mitt';

import './css/gui.css';
import './css/index.css';
import 'vue-final-modal/style.css';
import {MWBroadcast} from '../api-core/services/services/broadcast.js';
import i18n from './components/i18n/lang.plugin.js';
import Lang from './components/i18n/Lang.vue';


const vfm = createVfm();


const canvasHolder = document.getElementById('live-edit-frame-holder');
mw.app.canvas.mount(canvasHolder);


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


mw.app.canvas.on('liveEditCanvasLoaded', (data) => {
    document.querySelectorAll('.no-select:not(:has(.allow-select))').forEach(function(node){
        node.classList.remove("no-select");
    });

    mw.top().app.broadcast.message('canvasURL', {url: data.frameWindow.location.href})

    const topSearch = new URLSearchParams(mw.top().win.location.search);
    topSearch.set('url', encodeURIComponent(data.frameWindow.location.href));

    window.top.history.pushState(null, null, `?${topSearch.toString()}`);


    mw.app.remove('cssEditor');
    var doc = mw.app.canvas.getDocument();
    var liveEditCssStylesheetElement = doc.querySelector('#mw-template-settings');
    if (!liveEditCssStylesheetElement) {
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
    mw.app._ready = true;
});

window.top.addEventListener('popstate', function () {
    let url = decodeURIComponent(new URLSearchParams(window.top.location.search).get('url') || '/');

    //check if url is from another domain
    var domain = (new URL(url)).hostname;
    if(domain !== window.location.hostname){
        url = '/';
    }

    mw.app.canvas.setUrl(url);
})


const emitter = mitt();

const app = createApp(App);
app.component('vue-drag-resize', VueDragResize);


const vuetify = createVuetify({
    components,
    directives,
})

app.use(vuetify);

app.config.globalProperties.emitter = emitter;
app.use(VueClickAway);
app.use(vfm);
app.use(i18n);
app.component('Lang', Lang);
app.mount('#live-edit-app');

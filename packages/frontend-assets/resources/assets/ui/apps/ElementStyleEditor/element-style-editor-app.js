import {createApp} from 'vue';
import {reactive} from 'vue';


//import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import App from './ElementStyleEditorApp.vue';

import VueClickAway from "vue3-click-away";

import mitt from 'mitt';
import Lang from '../../components/i18n/Lang.vue';
import i18n from '../../components/i18n/lang.plugin.js';



const emitter = mitt();

const app = createApp(App);
app.config.globalProperties.emitter = emitter;






const vuetify = createVuetify({
    components,
    directives,
})

app.use(vuetify);
app.use(VueClickAway);

app.use(i18n);
app.component('Lang', Lang);

app.mount('#mw-element-style-editor-app');

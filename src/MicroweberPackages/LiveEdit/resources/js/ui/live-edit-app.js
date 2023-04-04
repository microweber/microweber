//require('./bootstrap.js');

// mw live-edit core
import '../api-core/services/bootstrap.js';

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

const app = createApp(App);
app.config.globalProperties.emitter = emitter;
app.use(VueClickAway);
app.mount('#live-edit-app');

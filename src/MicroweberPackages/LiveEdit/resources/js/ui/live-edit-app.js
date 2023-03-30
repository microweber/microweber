//require('./bootstrap.js');

// mw live-edit core
import '../api-core/services/bootstrap.js';

// vue
import {createApp} from 'vue';
import App from './App.vue';

// emiter
import mitt from 'mitt';
const emitter = mitt();

import './css/app.sass';
import  './css/gui.css';
import  './css/index.css';

mw.app.on('ready', () => {
    const app = createApp(App);
    app.config.globalProperties.emitter = emitter;
    app.mount('#live-edit-app');
});

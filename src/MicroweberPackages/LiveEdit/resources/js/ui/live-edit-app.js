//require('./bootstrap.js');

import '../api-core/services/bootstrap.js';
import {createApp} from 'vue';
import App from './App.vue';

import './css/app.sass';
import  './css/gui.css';
import  './css/index.css';

createApp(App).mount("#live-edit-app");

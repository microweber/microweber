
import {createApp} from 'vue';
import App from './ElementStyleEditorApp.vue';

import VueClickAway from "vue3-click-away";

import mitt from 'mitt';

const emitter = mitt();

const app = createApp(App);
app.config.globalProperties.emitter = emitter;

app.directive("tooltip", {
    mounted: (el, binding) => {
        return new bootstrap.Tooltip(el, {
            boundary: document.body,
            container: el.parentNode,
        });
    }
});

// Vuetify
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

const vuetify = createVuetify({
    components,
    directives,
})

app.use(vuetify);
app.use(VueClickAway);
app.mount('#mw-element-style-editor-app');

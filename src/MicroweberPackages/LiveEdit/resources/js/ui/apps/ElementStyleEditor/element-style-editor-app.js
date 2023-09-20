
import {createApp} from 'vue';
import App from './ElementStyleEditorApp.vue';

import VueClickAway from "vue3-click-away";

import mitt from 'mitt';

const emitter = mitt();

const app = createApp(App);

app.directive("tooltip", {
    mounted: (el, binding) => {
        return new bootstrap.Tooltip(el, {
            boundary: document.body,
            container: el.parentNode,
        });
    }
});
app.config.globalProperties.emitter = emitter;
app.use(VueClickAway);
app.mount('#mw-element-style-editor-app');

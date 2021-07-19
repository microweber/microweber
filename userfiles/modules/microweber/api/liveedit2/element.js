import {ObjectService} from "./object.service";

const nodeName = 'mw-le-element';
if (window.customElements) {
    customElements.define( nodeName,
        class extends HTMLElement {
            constructor() {
                super();
            }
        }
    );
}
export const ElementManager = (config, root) => {
    if (config instanceof Object && !config.nodeType) {
        config = ObjectService.extend({}, config || {}, { tag: config.tag || nodeName });
    }
    return mw.element(config, root)
}

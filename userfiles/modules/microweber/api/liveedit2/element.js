import {ObjectService} from "./object.service";


const nodeName = 'mw-le-element'
if (window.customElements) {
    customElements.define( nodeName,
        class extends HTMLDivElement {
            constructor() {
                super();
            }
        }
    );
}
export const CreateElement = (config) => {
    config = ObjectService.extend({}, config || {}, { tag: nodeName });
    return mw.element(config)
}

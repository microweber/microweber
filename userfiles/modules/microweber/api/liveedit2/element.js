(() => {
    var nodeName = 'mw-le-element'
    if(window.customElements) {
        customElements.define( nodeName,
            class extends HTMLDivElement {
                constructor() {
                    super();
                }
            }
        );
    }
})();



export const CreateElement = (config) => {

}

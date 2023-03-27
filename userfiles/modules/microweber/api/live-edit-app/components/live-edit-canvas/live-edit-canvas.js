import {eventManager} from "../../services/events.service.js";


export class LiveEditCanvas extends HTMLElement {
    template = `
        <div id="live-edit-frame-holder">
            <iframe id="live-editor-frame"
                    referrerpolicy="no-referrer"
                    frameborder="0"
                    src="${mw.settings.site_url}?editmode=n"
                    data-src="about:blank">
            </iframe>
        </div>
`;
    constructor() {
        super();
        this.innerHTML = this.template;
        mw.spinner({
            element: this,
            size: 52,
            decorate: true
        });
        this.querySelector('iframe').addEventListener('load', e => {
            eventManager.dispatch('liveEditCanvasLoaded');
            mw.spinner({
                element: this,

            }).remove()
        });
    }
}

customElements.define(
    "mw-live-edit-canvas",
    LiveEditCanvas
);


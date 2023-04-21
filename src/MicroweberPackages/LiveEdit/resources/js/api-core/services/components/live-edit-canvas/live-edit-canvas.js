import  MicroweberBaseClass  from "../../containers/base-class.js";


export class LiveEditCanvas extends MicroweberBaseClass {

    constructor() {
        super();
    }

    #canvas = null;

    go(url) {
        if(this.#canvas && this.#canvas.ownerDocument && this.#canvas.contentWindow) {
            this.#canvas.src = url;
        }
    }

    refresh() {
        if(this.#canvas && this.#canvas.ownerDocument && this.#canvas.contentWindow) {
            this.#canvas.contentWindow.location.reload();
        }
    }

    getFrame(){
        if(this.#canvas && this.#canvas.ownerDocument) {
            return this.#canvas;
        }
    }

    getWindow(){
        if(this.#canvas && this.#canvas.ownerDocument) {
            return this.#canvas.contentWindow;
        }
    }
    getDocument() {
        if(this.#canvas && this.#canvas.ownerDocument) {
            return this.#canvas.contentWindow.document;
        }
    }

    mount(target) {

        this.dispatch('liveEditBeforeLoaded');

        mw.spinner({
            element: target,
            size: 52,
            decorate: true
        });
        const frame = document.createElement('iframe');
        frame.src = `${mw.settings.site_url}?editmode=n`;
        frame.frameBorder = 0;
        frame.id="live-editor-frame";
        frame.referrerPolicy = "no-referrer";
        frame.loading = "lazy";
        this.#canvas = frame;
        target.innerHTML = '';
        target.appendChild(frame);
        frame.addEventListener('load', e => {



            this.dispatch('liveEditCanvasLoaded');
            mw.spinner({
                element: target,
            }).remove()
        });
    }
}

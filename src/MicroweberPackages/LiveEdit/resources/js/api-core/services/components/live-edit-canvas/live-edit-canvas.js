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
        let url = mw.settings.site_url;
        const qurl = new URLSearchParams(window.top.location.search).get('url');
        
        if (qurl) {
            url = decodeURIComponent(qurl)
        }
        
        url = new URL(url);

        url.searchParams.set('editmode', 'iframe');
  

        if(url.host !== top.location.host) {
            url = `${mw.settings.site_url}?editmode=iframe`;
        }
        frame.src = url.toString();

        frame.frameBorder = 0;
        frame.id="live-editor-frame";
        frame.referrerPolicy = "no-referrer";
        frame.loading = "lazy";
        this.#canvas = frame;
        target.innerHTML = '';
        target.appendChild(frame);
        
        frame.addEventListener('load', e => {

            mw.spinner({element: target, decorate: true}).remove();

            frame.contentWindow.addEventListener('beforeunload', e => {
                mw.spinner({element: target, decorate: true, size: 52}).show()
            })

            frame.contentWindow.document.body.addEventListener('click', () => {
                this.dispatch('canvasDocumentClick')
            });

            this.dispatch('liveEditCanvasLoaded', {frame, frameWindow: frame.contentWindow, frameDocument: frame.contentWindow.document});
            mw.spinner({
                element: target,
            }).remove()
        });
    }
}

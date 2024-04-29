import  MicroweberBaseClass  from "../../containers/base-class.js";


export class LiveEditCanvas extends MicroweberBaseClass {

    constructor(options = {}) {
        super();
    }

    #canvas = null;


    #urlAsValue(url) {
        const urlObj = new URL(url);
        urlObj.search = '';
        urlObj.hash = '';
        let result = urlObj.toString().trim();

        // unify the way urls are stored
        if(result.lastIndexOf('/') === result.length - 1) {
            result = result.substring(0, result.length - 1);
        }

        return result;
    };




    async #registerURL(url){
        const open = async () => {
            mw.top().app.broadcast.message('canvasURL', {url: this.#urlAsValue(url)});
            this.dispatch('setUrl', url);
            if (this.options && this.options.onSetUrl) {
                await this.options.onSetUrl(url);
            }
        }


        if(this.isUrlOpened(url)) {

            const action = await mw.app.pageAlreadyOpened.handle(url);

            if(action) {

                open()
            } else {

                mw.top().win.location.href = mw.settings.adminUrl;
            }

        } else{

            await open();
        }
    };

    isUrlOpened(url) {
       return  mw.top().app.broadcast.findByKeyValue('canvasURL', this.#urlAsValue(url), false).length > 1;
    }



    go(url) {
        if(this.#canvas && this.#canvas.ownerDocument && this.#canvas.contentWindow) {
            this.setUrl(url);
            // this.#canvas.src = url;
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

    getLiveEditData() {
        var liveEditIframe = this.getWindow();
        if (liveEditIframe
            && typeof liveEditIframe.mw !== 'undefined'
            && typeof liveEditIframe.mw.liveEditIframeData !== 'undefined'
            && liveEditIframe.mw.liveEditIframeData

        ) {
            return liveEditIframe.mw.liveEditIframeData;
        }
        return false;
    }

    async setUrl(url) {




        this.#canvas.src = url;


        url = url.toString();

        await this.#registerURL( url);
    }

    mount(target) {

        this.dispatch('liveEditBeforeLoaded');


        mw.spinner({
            element: target,
            size: 52,
            decorate: true
        });

        const liveEditIframe = document.createElement('iframe');
        let url = mw.settings.site_url;
        const qurl = new URLSearchParams(window.top.location.search).get('url');

        if (qurl) {
            url = decodeURIComponent(qurl)
        }

        url = new URL(url);



        if(url.host !== top.location.host) {
            url = `${mw.settings.site_url}`;
        }


        this.#canvas = liveEditIframe;




        liveEditIframe.frameBorder = 0;
        liveEditIframe.id="live-editor-frame";
        liveEditIframe.name="live-editor-frame";
        liveEditIframe.referrerPolicy = "no-referrer";
        liveEditIframe.loading = "lazy";

        this.setUrl(url);

        target.innerHTML = '';
        target.appendChild(liveEditIframe);


        window.onbeforeunload = function () {
            if(liveEditIframe && liveEditIframe.contentWindow && liveEditIframe.contentWindow.mw
           && liveEditIframe.contentWindow.mw.askusertostay){


               return true;
           }
         };



        liveEditIframe.addEventListener('error', e => {
            mw.spinner({element: target, decorate: true}).remove();
        });




        liveEditIframe.addEventListener('load', e => {

            mw.spinner({element: target, decorate: true}).remove();

            if(liveEditIframe && liveEditIframe.contentWindow && liveEditIframe.contentWindow.mw) {
               // liveEditIframe.contentWindow.mw.require('liveedit.css');
                liveEditIframe.contentWindow.document.body.classList.add('live-edit-frame-loaded');
            }

            target.classList.add('live-edit-frame-loaded');

           ['mousedown', 'touchstart'].forEach(e => {
            liveEditIframe.contentWindow.document.body.addEventListener(e, event => this.dispatch('canvasDocumentClickStart', event));
           })

           liveEditIframe.contentWindow.document.body.addEventListener('click', (event) => {

            if (mw.app.liveEdit.handles.targetIsOrInsideHandle(event.target ) ) {
                return;
            }
            this.dispatch('canvasDocumentClick', event)

        });

            liveEditIframe.contentWindow.addEventListener('resize', (event) => {
                this.dispatch('resize', event)
            });
            liveEditIframe.contentWindow.document.body.addEventListener('input', (event) => {

                if (mw.app.liveEdit.handles.targetIsOrInsideHandle(event.target ) ) {
                    return;
                }
                if (event.target && event.target.nodeName) {
                    // is input or textarea
                    if (event.target.nodeName === 'INPUT' || event.target.nodeName === 'TEXTAREA') {
                        return;
                    }
                }

                this.dispatch('canvasDocumentInput', event)

            });


            liveEditIframe.contentWindow.document.addEventListener('keydown', (event) => {
                if (mw.app.liveEdit.handles.targetIsOrInsideHandle(event.target ) ) {
                    return;
                }
                this.dispatch('canvasDocumentKeydown', event)


            });




            liveEditIframe.contentWindow.document.body.addEventListener('dblclick', (event) => {
                this.dispatch('canvasDocumentDoubleClick',  event)

            });

            if(liveEditIframe.contentWindow && liveEditIframe.contentWindow.mw) {
                liveEditIframe.contentWindow.mw.isNavigating = false;
            }


            this.dispatch('liveEditCanvasLoaded', {frame: liveEditIframe, frameWindow: liveEditIframe.contentWindow, frameDocument: liveEditIframe.contentWindow.document});




            mw.spinner({
                element: target,
            }).remove()


        });
    }
}

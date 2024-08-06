import  MicroweberBaseClass  from "../../containers/base-class.js";


class LiveEditCanvasBase extends MicroweberBaseClass {
    constructor() {
        super();
    }

    getIdentity() {
        return mw.storage.rootIdentity()
    }

    findByKey(key, excludeSameIdentity = false) {
        let curr = mw.storage.get('mw-broadcast-data');
        const res = []
        for(let identity in curr) {
            if(curr[identity][key] && curr[identity][key] && (!excludeSameIdentity || identity !== this.getIdentity())) {
                res.push({...curr[identity], identity});
            }
        }
        return res;
    }
    findByKeyValue(key, value, excludeSameIdentity = false) {
        let curr = mw.storage.get('mw-broadcast-data');
        const res = [];

        value = this.urlAsValue(value);

        for(let identity in curr) {

            if(curr[identity][key] && this.urlAsValue(curr[identity][key]) === value && (!excludeSameIdentity || identity !== this.getIdentity())) {
                res.push({...curr[identity], identity});
            }
        }

        return res;
    }

    urlAsValue(url) {
        if(!url) {
            return ''
        }
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

    record(action, data) {

        let curr = mw.storage.get('mw-broadcast-data');
        let identity = this.getIdentity();

        if (typeof curr[identity] === 'undefined') {
            curr[identity] = {};
        }
        if (!data) {
            return curr[identity][action];
        }
        curr[identity][action] = data;
        mw.storage.set('mw-broadcast-data', curr);
    }
}

export class LiveEditCanvas extends LiveEditCanvasBase {

    constructor(options = {}) {
        super();
    }

    #canvas = null;

    sameUrlDialog = false;






    async #cleanEmpty(curr){
        if(!curr) {
            curr = mw.storage.get('mw-broadcast-data');
        }

        for(let identity in curr) {

            if(Object.keys(curr[identity]).length === 0) {
                delete curr[identity];
            }
        }

        mw.storage.set('mw-broadcast-data', curr);
    }
    #unregisterCurrentURL(){
        const curr = mw.storage.get('mw-broadcast-data');
        if(!curr[mw.storage.rootIdentity()]) {
            curr[mw.storage.rootIdentity()] = {}
        }
        delete curr[mw.storage.rootIdentity()].canvasURL;


        this.#cleanEmpty(curr)



    }
    async #registerURL(url){
        const open = async () => {

            const curr = mw.storage.get('mw-broadcast-data');
            if(!curr[mw.storage.rootIdentity()]) {
                curr[mw .storage.rootIdentity()] = {}
            }

            curr[mw.storage.rootIdentity()].canvasURL = url;

            mw.storage.set('mw-broadcast-data', curr);


            this.dispatch('setUrl', url);
            if (this.options && this.options.onSetUrl) {
                await this.options.onSetUrl(url);
            }
            // this.#cleanEmpty()
        }


        if(this.isUrlOpened(url) && this.sameUrlDialog) {

            const action = await mw.app.pageAlreadyOpened.handle(url);

            if(action) {

                open()
            } else {

                mw.top().win.location.href = mw.top().settings.adminUrl;
            }

        } else{

            await open();
        }
    };

    isUrlSame(url) {

        return this.urlAsValue(url) === this.urlAsValue(this.#canvas.src);
    }

    isUrlOpened(url) {



       return  this.findByKeyValue('canvasURL', this.urlAsValue(url), false).length > 1;
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

      getUrl() {
        if(this.#canvas && this.#canvas.ownerDocument && this.#canvas.src) {
            return this.#canvas.src;
        }
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

        //  var valid =  mw.url.validate(url);
        // if(!valid){
        //     url = mw.settings.site_url;
        // }

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


        window.addEventListener('unload', () =>  this.#unregisterCurrentURL());
        window.addEventListener('pagehide', () =>  this.#unregisterCurrentURL());
        window.onbeforeunload = () => {
            if(liveEditIframe && liveEditIframe.contentWindow && liveEditIframe.contentWindow.mw && liveEditIframe.contentWindow.mw.askusertostay){
                return true;
            }
            this.#unregisterCurrentURL();
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

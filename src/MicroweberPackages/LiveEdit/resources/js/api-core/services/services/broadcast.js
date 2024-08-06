
import BaseComponent from "../containers/base-class";

export class MWBroadcast extends BaseComponent {
    constructor() {
        super();

        this.max = 0;

        if(! mw.top().MWBroadcastIdentity) {
            mw.top().MWBroadcastIdentity = mw.id();
        }

        this.#init();
    }



    getIdentity() {

        return mw.top().MWBroadcastIdentity;
    }

    #channel = new BroadcastChannel("Microweber");


    message(action, data = {}) {
        this.max++;
        if(this.max > 199) {
            return;
        }
        this.#channel.postMessage({action, ...data, identity: this.getIdentity()});
    }

    #initGlobalData() {
        let curr = mw.storage.get('mw-broadcast-data');
        if(!curr){
            mw.storage.set('mw-broadcast-data', {});
        }
        curr = mw.storage.get('mw-broadcast-data');
        curr[this.getIdentity()] = {};
        mw.storage.set('mw-broadcast-data', curr);
    }
    #init() {
        this.#initGlobalData();
        this.#channel.onmessage = e => {
            this.dispatch(e.data.action, e.data);
            if(e.data.identity === this.getIdentity()) {
                this.dispatch('local-' + e.data.action, e.data);
            } else {
                this.dispatch('remote-' + e.data.action, e.data);
            }
        };
        globalThis.addEventListener('unload', e => {



             let curr = mw.storage.get('mw-broadcast-data');
             delete curr[this.getIdentity()];
             mw.storage.set('mw-broadcast-data', curr);

        });
        globalThis.addEventListener('beforeunload', e => {


             let curr = mw.storage.get('mw-broadcast-data');
             delete curr[this.getIdentity()];
             mw.storage.set('mw-broadcast-data', curr);

        });
    }
}

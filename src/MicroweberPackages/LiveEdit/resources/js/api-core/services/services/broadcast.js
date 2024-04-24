
import BaseComponent from "../containers/base-class";

export class MWBroadcast extends BaseComponent {
    constructor() {
        super();

        this.max = 0;

        this.#init();

    }

    static identity = mw.id();

    getIdentity() { return MWBroadcast.identity; }

    #channel = new BroadcastChannel("Microweber");

    findByKey(key) {
        let curr = mw.storage.get('mw-broadcast-data');
        const res = []
        for(let identity in curr) {
            if(curr[identity][key]) {
                res.push(curr[identity]);
            }
        }
        return res;
    }
    findByKeyValue(key, value) {
        let curr = mw.storage.get('mw-broadcast-data');
        const res = []
        for(let identity in curr) {
            if(curr[identity][key] === value) {
                res.push(curr[identity]);
            }
        }
        return res;
    }

    record(action, data) {

        let curr = mw.storage.get('mw-broadcast-data');
        if(!data){
            return curr[this.getIdentity()][action];
        }
        curr[this.getIdentity()][action] = data;
        mw.storage.set('mw-broadcast-data', curr);
    }

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
        globalThis.addEventListener('beforeunload', e => {
             let curr = mw.storage.get('mw-broadcast-data');
             delete curr[this.getIdentity()];
             mw.storage.set('mw-broadcast-data', curr);
        });
    }
}

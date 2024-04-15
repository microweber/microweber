import BaseComponent from "../containers/base-class";

export class MWBroadcast extends BaseComponent {
    constructor() {
        super();

        this.#init();

    }

    #channel = new BroadcastChannel("Microweber");

    message(action, data = {}) {
        this.#channel.postMessage({action, ...data});
    }

    #init() {
        this.#channel.onmessage = function(e) {
            this.dispatch('message', e.data);
        };
    }
}

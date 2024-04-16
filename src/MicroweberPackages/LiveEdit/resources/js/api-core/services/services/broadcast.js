import BaseComponent from "../containers/base-class";

export class MWBroadcast extends BaseComponent {
    constructor() {
        super();

        this.max = 0

        this.#init();

    }

    #channel = new BroadcastChannel("Microweber");

    message(action, data = {}) {
        console.log(action, data  )
        this.max++;
        if(this.max > 199) {
            return;
        }
        this.#channel.postMessage({action, ...data});
    }

    #init() {
        this.#channel.onmessage =  e => this.dispatch(e.data.action, e.data);
    }
}

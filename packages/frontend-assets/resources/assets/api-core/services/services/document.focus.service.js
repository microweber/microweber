import  MicroweberBaseClass  from "../containers/base-class.js";

export class MWDocumentFocus extends MicroweberBaseClass {
    constructor() {
        super();
        this.#init();
    }

    #state = 'focus';
    #visibilityState = globalThis.top.document.visibilityState;

    setState(state) {
        this.#state = state;
        this.dispatch('state', this.getState());
        this.dispatch(this.getState());
    }

    getState() {
        return this.#state;
    }

    isActive() {
        return this.isVisible() && this.isFocused();
    }

    isVisible() {
        return this.#visibilityState === 'visible';
    }
    isFocused() {
        return this.getState() === 'focus';
    }


    #init() {

        globalThis.top.document.addEventListener("visibilitychange", () => {
            this.#visibilityState = globalThis.top.document.visibilityState;

        });
        globalThis.addEventListener('focus', e => {

            if(this.isActive()) {
                return
            }

            this.setState(e.type);
        });
        globalThis.addEventListener('blur', e => {
            if(!this.isActive()) {
                return
            }
            let isFocused = Array.from(e.target).find(w => w.document.hasFocus());

            if(!isFocused) {
                this.setState(e.type);
            }

        });
    }
}

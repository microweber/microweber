export class MWDocumentFocus extends BaseComponent {
    constructor() {
        super();
        this.#init();
    }

    #state = 'focus';

    setState(state) {
        this.#state = state;
        this.dispatch('state', this.#state);
        this.dispatch(this.#state);
    }

    isActive() {
        return this.#state === 'focus';
    }


    #init() {
        globalThis.addEventListener('focus', function(e) {
            this.setState(e.type);
        });
        globalThis.addEventListener('blur', function(e) {
            this.setState(e.type);
        });
    }
}

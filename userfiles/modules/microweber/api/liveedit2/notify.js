import {ElementManager} from "./element.js";

export class Notify {
    constructor(options) {
        if(!options) {
            options = {};
        }
        const defaults = {
            type: 'message', // message, success, warning, error
            message: '',
            document: document,
            time: 5000
        }
        this.settings = Object.assign({}, defaults, options);
        this.create();
    }
    remove() {
        this.element.remove();
    }
    create() {
        this.element = ElementManager().addClass('le-notify le-notify-' + this.settings.type).html(this.settings.message);
        this.settings.document.appendChild(this.element.get(0));
        setTimeout(() => { this.element.addClass('le-notify-active') }, 10);
        setTimeout(() => { this.remove() }, this.settings.time);
        const close = ElementManager({

        })
    }
}

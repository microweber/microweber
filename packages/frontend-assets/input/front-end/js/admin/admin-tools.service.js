import {DomHelpers} from "../tools/domhelpers.js";
import {iframeAutoHeight} from "../tools/iframe-auto-height.js";

export class AdminTools {
    constructor(app) {
        this._app = app;
        this.init();
    }

    #ready = false;

    init() {
        if(this.#ready) {
            return;
        }
        this.#ready = true;
        this.extend(DomHelpers)
        this.iframeAutoHeight = iframeAutoHeight;
    }

    index(el, parent, selector) {
        el = mw.$(el)[0];
        selector = selector || el.tagName.toLowerCase();
        parent = parent || el.parentNode;
        var all;
        if (parent.constructor === [].constructor) {
            all = parent;
        }
        else {
            all = mw.$(selector, parent)
        }
        var i = 0, l = all.length;
        for (; i < l; i++) {
            if (el === all[i]) return i;
        }
    }

    extend(methods = {}) {
        for (let i in methods) {
            this[i] = methods[i];
        }
    }

}

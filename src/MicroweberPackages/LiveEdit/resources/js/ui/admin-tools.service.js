export class AdminTools {
    constructor(app) {
        this._app = app;
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

}

import MicroweberBaseClass from "../containers/base-class.js";
import {DomHelpers} from "../tools/domhelpers.js";
import {iframeAutoHeight} from "../tools/iframe-auto-height.js";



export class AdminColorThemeService extends MicroweberBaseClass {
    constructor(options = {}) {
        super();
        const defaults = {
            storage: localStorage,
        }

        this.settings = Object.assign({}, defaults, options);
        this.storage = this.settings.storage;
    }
    get #theme () {
        return this.isSystem() ? this.getSystem() : this.storage.getItem("theme");
    }

    set #theme (value) {
        if(value === this.#theme) {
            return;
        }
        this.storage.setItem("theme", value);
        this.dispatch('change')
    }

    setDark(){
        this.#theme = 'dark';
    }
    setLight(){
        this.#theme = 'light';
    }

    setSystem(){
        this.#theme = 'system';
    }

    toggle() {
        this.#theme = this.#theme === 'light' ? 'dark' : 'light';
    }

    getSystem() {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    isSystem() {
        return this.storage.getItem('theme') === 'system';
    }

    isSystemDark() {
        return this.getSystem() === 'dark';
    }
    isSystemLight() {
        return !this.isSystemDark();
    }

    isDark() {
        return this.#theme === 'dark';
    }

    isLight() {
        return  !this.isDark();
    }
}


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

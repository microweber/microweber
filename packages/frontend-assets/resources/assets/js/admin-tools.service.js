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
        
        return this.isSystem() ? this.getSystem() : this.storage.getItem("theme") || 'light';
    }



    set #theme (value) {
        if(value === this.#theme) {
            return;
        }

        if(value) {
            this.storage.setItem("theme", value);
            this.dispatch('change')
        }
    }

    getTheme() {
        return this.#theme
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

export class AdminFilamentColorThemeService extends AdminColorThemeService {
    constructor(options) {
        super(options);
        this.#filamentSync();
        this.on('change', () => this.#filamentSync());
        window.addEventListener('load', () => this.#filamentSync());
        document.addEventListener('DOMContentLoaded', () => this.#filamentSync());
        window.addEventListener("storage", () => {
            this.#filamentSync();
            this.dispatch('change')
        });
    }
    #filamentSync() {

        document.documentElement?.classList[this.isDark() ? 'add' : 'remove']('dark');
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

    /**
     * AI-59 / TICKET-VV (cycle-66 2026-05-08): allowlist check for
     * file URLs typed into the picker's URL tab. Only http/https
     * remote URLs are accepted — `javascript:`, `data:`, `file:`,
     * `vbscript:`, `chrome:` etc. are all rejected. Server-side
     * mirror lives in MediaPicker::isAllowedRemoteUrl(); both sides
     * agree on the same scheme list so the UX preview and the
     * persisted value can never diverge.
     *
     * @param {string} value User-typed URL.
     * @return {boolean}     True if the URL has an http/https scheme
     *                       AND a non-empty host. False otherwise
     *                       (including for empty / malformed input).
     */
    isAllowedFileUrl(value) {
        if (typeof value !== "string") {
            return false;
        }
        var trimmed = value.trim();
        if (trimmed === "") {
            return false;
        }
        // The URL constructor throws on malformed input — treat that
        // as rejection. We pass a base of `http://_` so protocol-
        // relative URLs (`//cdn.example.com/foo`) parse cleanly into
        // the http scheme, which is the safe behaviour.
        try {
            var url = new URL(trimmed, "http://_");
            var scheme = (url.protocol || "").toLowerCase().replace(/:$/, "");
            if (scheme !== "http" && scheme !== "https") {
                return false;
            }
            // Reject `http://` with no host (parses successfully but
            // produces hostname == ""). The `_` base would yield
            // hostname `_` only when the input was protocol-relative.
            if (!url.hostname || url.hostname === "_") {
                // Allow protocol-relative inputs (they resolved to
                // hostname `_` from our placeholder base).
                return /^\/\//.test(trimmed);
            }
            return true;
        } catch (e) {
            return false;
        }
    }

}

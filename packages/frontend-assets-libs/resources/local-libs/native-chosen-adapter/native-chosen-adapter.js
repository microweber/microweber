/*
 * AI-263 Phase B3 (cycle-183 2026-05-11) — Chosen → native
 * `<select multiple>` adapter.
 *
 * Replaces the jQuery Chosen plugin (used by CustomFields
 * *dropdown.blade.php skins when `settings.multiple == true`)
 * with a vanilla adapter that leaves the native
 * `<select multiple>` as-is, applies basic CSS to make it
 * look reasonable, and ensures it meets the WCAG 2.5.5 44×44
 * touch-target floor.
 *
 * Module skins call:
 *   $(".js-mw-select-X").chosen({width: '100%'});
 *
 * Vanilla approach: native multi-select with mw-styled
 * classes. Modern browsers handle keyboard nav + accessibility
 * natively. The Chosen "search inside dropdown" feature is
 * lost — if a future cycle needs it, swap in Choices.js
 * (vanilla, ~30KB) via the same adapter pattern.
 */

(function () {
    if (typeof window === 'undefined') return;
    if (window.__mwNativeChosenAdapterLoaded) return;
    window.__mwNativeChosenAdapterLoaded = true;

    var STYLE_ID = 'mw-native-chosen-adapter-style';
    var BASE_CSS = ''
        + '.mw-chosen-native {'
        + '  width: 100%;'
        + '  min-height: 44px;'
        + '  padding: 8px 12px;'
        + '  border: 1px solid #d1d5db;'
        + '  border-radius: 4px;'
        + '  font-size: 16px;'
        + '  line-height: 1.5;'
        + '  background-color: #ffffff;'
        + '  color: #111827;'
        + '}'
        + '.mw-chosen-native[multiple] {'
        + '  min-height: 88px;'
        + '  padding: 4px;'
        + '}'
        + '.mw-chosen-native:focus {'
        + '  outline: 2px solid #0d6efd;'
        + '  outline-offset: 2px;'
        + '}';

    function injectStyle() {
        if (document.getElementById(STYLE_ID)) return;
        var style = document.createElement('style');
        style.id = STYLE_ID;
        style.textContent = BASE_CSS;
        document.head && document.head.appendChild(style);
    }

    function registerAdapter() {
        if (typeof window.jQuery === 'undefined' || !window.jQuery.fn) {
            return false;
        }
        var $ = window.jQuery;
        if ($.fn.__mwNativeChosenInstalled) return true;
        $.fn.__mwNativeChosenInstalled = true;

        injectStyle();

        $.fn.chosen = function (options) {
            // Imperative API: `.chosen('destroy')` etc. → no-op
            // (native <select> doesn't need teardown).
            if (typeof options === 'string') {
                return this;
            }
            var opts = options || {};
            return this.each(function () {
                if (this.__mwNativeChosenApplied) return;
                this.__mwNativeChosenApplied = true;
                if (this.tagName !== 'SELECT') return;
                this.classList.add('mw-chosen-native');
                if (opts.width) {
                    this.style.width = opts.width;
                }
            });
        };

        return true;
    }

    if (!registerAdapter()) {
        window.__mwRegisterJqueryExtensions = window.__mwRegisterJqueryExtensions || [];
        window.__mwRegisterJqueryExtensions.push(registerAdapter);
    }
})();

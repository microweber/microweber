/*
 * CSRF token interceptors — moved out of ApijsScriptTag.php's two inline
 * <script> blocks (mw-js-csrf-vanilla + mw-js-csrf-jquery-guarded) into the
 * shared frontend bundle so they ship once in frontend.js instead of being
 * re-inlined into every page <head>.
 *
 * Imported FIRST in frontend.js so the fetch wrapper installs before any
 * other bundle module can issue a request. frontend.js itself loads in the
 * <head> right after jQuery, so this still runs early enough to cover every
 * same-origin fetch / jQuery AJAX call the page makes.
 */
(function () {
    if (typeof window === "undefined" || !window.fetch) {
        return;
    }

    // Vanilla CSRF fetch interceptor — works regardless of jQuery presence.
    // Reads the CSRF token from <meta name="csrf-token"> and adds it to every
    // same-origin fetch.
    if (!window.__mwCsrfFetchWrapped) {
        window.__mwCsrfFetchWrapped = true;
        var originalFetch = window.fetch.bind(window);
        window.fetch = function (input, init) {
            init = init || {};
            var sameOrigin = true;
            try {
                var u = typeof input === "string" ? input : (input && input.url);
                if (u && /^https?:\/\//i.test(u)) {
                    var parsed = new URL(u, window.location.href);
                    sameOrigin = parsed.origin === window.location.origin;
                }
            } catch (e) {}
            if (sameOrigin) {
                var tokenEl = document.querySelector('meta[name="csrf-token"]');
                var token = tokenEl ? tokenEl.getAttribute("content") : null;
                if (token) {
                    var headers = new Headers(init.headers || {});
                    if (!headers.has("X-CSRF-TOKEN")) headers.set("X-CSRF-TOKEN", token);
                    init.headers = headers;
                }
            }
            return originalFetch(input, init);
        };
    }

    // Legacy jQuery $.ajaxSetup CSRF shim — guarded so it only runs once jQuery
    // is present. jQuery is normally loaded eagerly before frontend.js, but if a
    // page lazy-loads it later, re-register via the shared
    // window.__mwRegisterJqueryExtensions hook the conditional footer flushes.
    var registerJqueryCsrf = function () {
        if (typeof window.jQuery === "undefined") return false;
        var token = (document.querySelector('meta[name="csrf-token"]') || {}).content;
        if (!token) return true;
        window.jQuery.ajaxSetup({ headers: { "X-CSRF-TOKEN": token } });
        return true;
    };
    if (!registerJqueryCsrf()) {
        window.__mwRegisterJqueryExtensions = window.__mwRegisterJqueryExtensions || [];
        window.__mwRegisterJqueryExtensions.push(registerJqueryCsrf);
    }
})();

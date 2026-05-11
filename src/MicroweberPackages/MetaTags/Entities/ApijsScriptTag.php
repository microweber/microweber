<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class ApijsScriptTag implements TagInterface, \Stringable
{
    /*
     * AI-263 Phase B1 (cycle-181 2026-05-11) — conditional jQuery
     * emission + vanilla CSRF fetch interceptor.
     *
     * Public pages (everything NOT under /admin/*) no longer
     * unconditionally load jQuery + jQuery UI (806KB combined).
     * Instead, they emit:
     *   1. `frontend.js` (always — Microweber core lib)
     *   2. Vanilla `fetch` interceptor that adds the CSRF token
     *      header to every same-origin request (no jQuery needed)
     *   3. Legacy `$.ajaxSetup` shim — guarded by `typeof $` so it
     *      only runs if jQuery is later loaded by the
     *      `TemplateManager::injectConditionalJqueryFooter`
     *      post-process step
     *
     * Legacy admin pages (anything under /admin/*) keep the
     * existing behavior — eager jQuery + jQuery UI + inline
     * `$.ajaxSetup`. Filament admin pages go through a separate
     * AdminFilamentJsLibsScriptTag.php entirely, so they're
     * unaffected.
     *
     * Detection of "needs jQuery" on public pages happens in
     * TemplateManager.php after the full layout is rendered —
     * scans the body for slick/masonry/datetimepicker/chosen/
     * data-mw-needs-jquery markers and conditionally injects
     * jquery.js + jquery-ui.js before </body>. See
     * src/MicroweberPackages/Template/TemplateManager.php
     * for the detection logic.
     */

    public function toHtml(): string
    {
        $jquery = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery/jquery.js';
        $jqueryUi = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery-ui/jquery-ui.js';
        $jqueryUiCss = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery-ui/jquery-ui.css';

        $apijs_combined_loaded_new = public_asset('vendor/microweber-packages/frontend-assets/build/frontend.js');

        $isAdminPath = $this->isAdminPath();
        // Cycle-188 EMERGENCY ROLLBACK (2026-05-11): per customer
        // request, jQuery + jQuery UI are restored on ALL pages —
        // public, admin, opt-in or not. The conditional emission
        // infrastructure (isAdminPath + requestRequiresJquery) stays
        // in place as documentation of the prior Phase B5 design and
        // as the restoration path if the customer reconsiders later.
        // For now, force-emit is the simpler/safer state — templates
        // that rely on jQuery never break.
        $needsJqueryEager = true; // Always emit jQuery for all pages (rollback)

        $append_html = '';

        if ($needsJqueryEager) {
            // Eager-load jQuery + UI when:
            //   - Admin path (legacy admin requires it)
            //   - Template explicitly called mw_require_jquery() at
            //     the top of its master layout (Bootstrap template
            //     does this — its app.js + frontend.js events.js
            //     reference `$` at module-init time). Templates that
            //     don't opt in get zero eager jQuery — 806KB saved.
            //   - Cycle-188 rollback: force-emit on every page so
            //     templates that rely on jQuery never break.
            $append_html .= '<script src="' . $jquery . '" id="mw-jquery-js-libs-scripts"></script>' . "\r\n";
            $append_html .= '<script src="' . $jqueryUi . '" id="mw-jquery-ui-js-libs-scripts"></script>' . "\r\n";
            $append_html .= '<link rel="stylesheet" href="' . $jqueryUiCss . '" id="mw-jquery-ui-js-libs-styles">' . "\r\n";
        }

        $append_html .= '<script  src="' . $apijs_combined_loaded_new . '"  id="mw-js-core-scripts"></script>' . "\r\n";

        // Vanilla CSRF fetch interceptor — always emit (works
        // regardless of jQuery presence). Captures CSRF token from
        // <meta name="csrf-token"> on every same-origin fetch.
        $append_html .= '<script id="mw-js-csrf-vanilla" type="text/javascript">' . "\r\n";
        $append_html .= '(function () {' . "\r\n";
        $append_html .= '    if (typeof window === "undefined" || !window.fetch) return;' . "\r\n";
        $append_html .= '    if (window.__mwCsrfFetchWrapped) return;' . "\r\n";
        $append_html .= '    window.__mwCsrfFetchWrapped = true;' . "\r\n";
        $append_html .= '    var originalFetch = window.fetch.bind(window);' . "\r\n";
        $append_html .= '    window.fetch = function (input, init) {' . "\r\n";
        $append_html .= '        init = init || {};' . "\r\n";
        $append_html .= '        var sameOrigin = true;' . "\r\n";
        $append_html .= '        try {' . "\r\n";
        $append_html .= '            var u = typeof input === "string" ? input : (input && input.url);' . "\r\n";
        $append_html .= '            if (u && /^https?:\\/\\//i.test(u)) {' . "\r\n";
        $append_html .= '                var parsed = new URL(u, window.location.href);' . "\r\n";
        $append_html .= '                sameOrigin = parsed.origin === window.location.origin;' . "\r\n";
        $append_html .= '            }' . "\r\n";
        $append_html .= '        } catch (e) {}' . "\r\n";
        $append_html .= '        if (sameOrigin) {' . "\r\n";
        $append_html .= '            var tokenEl = document.querySelector(\'meta[name="csrf-token"]\');' . "\r\n";
        $append_html .= '            var token = tokenEl ? tokenEl.getAttribute("content") : null;' . "\r\n";
        $append_html .= '            if (token) {' . "\r\n";
        $append_html .= '                var headers = new Headers(init.headers || {});' . "\r\n";
        $append_html .= '                if (!headers.has("X-CSRF-TOKEN")) headers.set("X-CSRF-TOKEN", token);' . "\r\n";
        $append_html .= '                init.headers = headers;' . "\r\n";
        $append_html .= '            }' . "\r\n";
        $append_html .= '        }' . "\r\n";
        $append_html .= '        return originalFetch(input, init);' . "\r\n";
        $append_html .= '    };' . "\r\n";
        $append_html .= '})();' . "\r\n";
        $append_html .= '</script>' . "\r\n";

        // Legacy jQuery $.ajaxSetup CSRF shim — guarded so it only
        // runs IF jQuery is loaded. Loads later via the conditional
        // footer injection on pages that need jQuery plugins.
        $append_html .= '<script id="mw-js-csrf-jquery-guarded" type="text/javascript">' . "\r\n";
        $append_html .= '(function () {' . "\r\n";
        $append_html .= '    if (typeof window.jQuery === "undefined") return;' . "\r\n";
        $append_html .= '    var token = (document.querySelector(\'meta[name="csrf-token"]\') || {}).content;' . "\r\n";
        $append_html .= '    if (!token) return;' . "\r\n";
        $append_html .= '    window.jQuery.ajaxSetup({ headers: { "X-CSRF-TOKEN": token } });' . "\r\n";
        $append_html .= '})();' . "\r\n";
        $append_html .= '</script>' . "\r\n";

        return $append_html;
    }

    /**
     * Detect whether the current request is for an admin URL.
     * Admin path = `/admin*`. Used to preserve eager jQuery
     * loading on legacy admin while letting public pages
     * lazy-load via TemplateManager.
     */
    protected function isAdminPath(): bool
    {
        try {
            $request = function_exists('request') ? request() : null;
            if (!$request) return false;
            return $request->is('admin') || $request->is('admin/*') || $request->is('api/*');
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * AI-263 Phase B1: check the request-scoped "needs jQuery" flag.
     * Templates that genuinely need jQuery at module-init time (e.g.
     * Bootstrap template's app.js uses `$` directly) call
     * `mw_require_jquery()` at the top of their master.blade.php
     * BEFORE meta_tags_head() runs. ApijsScriptTag reads that flag
     * to decide whether to emit the eager <head> jQuery <script>.
     *
     * Public pages on templates that don't opt in skip the 806KB.
     */
    protected function requestRequiresJquery(): bool
    {
        try {
            return app()->bound('mw.requires_jquery')
                && app('mw.requires_jquery') === true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getPlacement(): string
    {
        return Meta::PLACEMENT_HEAD;
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }


    public function toArray(): array
    {
        return [
            'type' => 'apijs',
        ];
    }
}

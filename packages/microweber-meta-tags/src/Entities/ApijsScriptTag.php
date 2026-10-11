<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class ApijsScriptTag implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $jquery = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery/jquery.js';
        $jqueryUi = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery-ui/jquery-ui.js';
        $jqueryUiCss = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery-ui/jquery-ui.css';

        $apijs_combined_loaded_new = public_asset('vendor/microweber-packages/frontend-assets/build/frontend.js');

        $isAdminPath = $this->isAdminPath();
        // Always emit jQuery + jQuery UI on every page so templates that rely on them never break.
        $needsJqueryEager = true;

        $append_html = '';

        if ($needsJqueryEager) {
            $append_html .= '<script src="' . $jquery . '" id="mw-jquery-js-libs-scripts"></script>' . "\r\n";
            $append_html .= '<script src="' . $jqueryUi . '" id="mw-jquery-ui-js-libs-scripts"></script>' . "\r\n";
            $append_html .= '<link rel="stylesheet" href="' . $jqueryUiCss . '" id="mw-jquery-ui-js-libs-styles">' . "\r\n";
        }

        $append_html .= '<script  src="' . $apijs_combined_loaded_new . '"  id="mw-js-core-scripts"></script>' . "\r\n";

        // Vanilla CSRF fetch interceptor — works regardless of jQuery presence.
        // Reads CSRF token from <meta name="csrf-token"> and adds it to every same-origin fetch.
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

        // Legacy jQuery $.ajaxSetup CSRF shim — guarded so it only runs if jQuery is loaded.
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
     * Detect whether the current request is for an admin URL (`/admin*` or `/api/*`).
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
     * Check the request-scoped "needs jQuery" flag set by `mw_require_jquery()` in the template layout.
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

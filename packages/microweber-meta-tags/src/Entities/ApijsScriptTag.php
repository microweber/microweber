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

        // CSRF interceptors (vanilla fetch wrapper + guarded jQuery $.ajaxSetup
        // shim) used to be inlined here as two <script> blocks. They now ship in
        // the shared frontend bundle (frontend.js → widgets/csrf.js, imported
        // first) so they load once instead of being re-inlined on every page.

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

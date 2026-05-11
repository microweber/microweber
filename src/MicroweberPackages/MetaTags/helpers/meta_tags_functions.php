<?php

if (!function_exists('meta_tags_head')) {
    function meta_tags_head(): string
    {
        return \MicroweberPackages\MetaTags\Facades\FrontendMetaTags::getHeadMetaTags();
    }
}

if (!function_exists('meta_tags_head_add')) {
    function meta_tags_head_add($src): string
    {
        return template_head($src);
    }
}

if (!function_exists('meta_tags_footer')) {
    function meta_tags_footer(): string
    {
        return \MicroweberPackages\MetaTags\Facades\FrontendMetaTags::getFooterMetaTags();
    }
}

if (!function_exists('meta_tags_footer_add')) {
    function meta_tags_footer_add($src): string
    {
        return template_foot($src);
    }
}

/**
 * @deprecated Use meta_tags_head() instead.
 * @see meta_tags_head()
 */
if (!function_exists('mw_header_scripts')) {
    function mw_header_scripts(): string
    {
        return meta_tags_head();
    }
}

/**
 * @deprecated Use footer_meta_tags() instead.
 * @see footer_meta_tags()
 */
if (!function_exists('mw_footer_scripts')) {
    function mw_footer_scripts(): string
    {
        return meta_tags_footer();
    }
}

/**
 * @deprecated
 */
if (!function_exists('mw_admin_header_scripts')) {
    function mw_admin_header_scripts(): string
    {
        return \MicroweberPackages\MetaTags\Facades\AdminMetaTags::getHeadMetaTags();
    }
}

/**
 * @deprecated
 */
if (!function_exists('mw_admin_footer_scripts')) {
    function mw_admin_footer_scripts(): string
    {
        return \MicroweberPackages\MetaTags\Facades\AdminMetaTags::getFooterMetaTags();
    }
}

/*
 * AI-263 Phase B1 (cycle-181 2026-05-11) — opt-in helper for
 * templates that genuinely need eager jQuery loading on public
 * pages.
 *
 * Usage: call `mw_require_jquery()` at the TOP of the template's
 * master.blade.php, BEFORE `{!! meta_tags_head() !!}`. ApijsScriptTag
 * reads the flag at head-render time and emits the eager jquery.js
 * + jquery-ui.js <script> tags if true.
 *
 * Templates that don't call this helper skip 806KB of blocking JS
 * on every public-page render — that's the AI-263 P1 perf win.
 * Public-side JS that uses $/jQuery must either:
 *   a) Defer execution until after the
 *      TemplateManager::injectConditionalJqueryFooter script runs
 *      (use the `window.__mwRegisterJqueryExtensions[]` queue
 *      pattern from frontend.js)
 *   b) Mark a body element with `data-mw-needs-jquery` (triggers
 *      the conditional footer injection)
 *   c) Have the template call `mw_require_jquery()` (eager head
 *      load — same as before B1)
 */
if (!function_exists('mw_require_jquery')) {
    function mw_require_jquery(): void
    {
        try {
            app()->instance('mw.requires_jquery', true);
        } catch (\Throwable $e) {
            // Bootstrapped too early — no-op.
        }
    }
}

/**
 * @deprecated , pls use fimanent hooks
 */
if (!function_exists('admin_head')) {
    function admin_head($script_src)
    {
        return app()->template_manager->admin_head($script_src);
    }
}


if (!function_exists('template_head')) {
    function template_head($script_src)
    {
        return app()->template_manager->head($script_src);
    }
}

if (!function_exists('template_foot')) {
    function template_foot($script_src)
    {
        return app()->template_manager->foot($script_src);
    }
}

/**
 * @internal
 */
if (!function_exists('template_headers_src')) {
    function template_headers_src()
    {
        return app()->template_manager->head(true);
    }
}


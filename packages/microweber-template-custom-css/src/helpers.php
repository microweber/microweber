<?php

declare(strict_types=1);

use MicroweberPackages\TemplateCustomCss\Services\CustomCssManager;
use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use MicroweberPackages\TemplateCustomCss\Services\TemplateCustomCssManager;

if (!function_exists('template_custom_css')) {
    /**
     * Resolve the TemplateCustomCssManager singleton.
     */
    function template_custom_css(): TemplateCustomCssManager
    {
        return app(TemplateCustomCssManager::class);
    }
}

if (!function_exists('template_live_edit_css')) {
    /**
     * Resolve the LiveEditCssManager singleton.
     */
    function template_live_edit_css(): LiveEditCssManager
    {
        return app(LiveEditCssManager::class);
    }
}

if (!function_exists('template_user_custom_css')) {
    /**
     * Resolve the CustomCssManager singleton (user custom CSS option).
     */
    function template_user_custom_css(): CustomCssManager
    {
        return app(CustomCssManager::class);
    }
}

if (!function_exists('get_page_custom_css')) {
    /** Read a page's per-content-id custom CSS (empty string when none). */
    function get_page_custom_css(int $contentId): string
    {
        return app(LiveEditCssManager::class)->getPageCssContent($contentId);
    }
}

if (!function_exists('save_page_custom_css')) {
    /** Write (and S3-mirror) a page's per-content-id custom CSS. Returns the path. */
    function save_page_custom_css(int $contentId, ?string $css): string
    {
        return app(LiveEditCssManager::class)->savePageCssContent($contentId, $css);
    }
}

if (!function_exists('get_page_custom_js')) {
    /** Read a page's per-content-id custom JS (empty string when none). */
    function get_page_custom_js(int $contentId): string
    {
        return app(LiveEditCssManager::class)->getPageJsContent($contentId);
    }
}

if (!function_exists('save_page_custom_js')) {
    /** Write (and S3-mirror) a page's per-content-id custom JS. Returns the path. */
    function save_page_custom_js(int $contentId, ?string $js): string
    {
        return app(LiveEditCssManager::class)->savePageJsContent($contentId, $js);
    }
}

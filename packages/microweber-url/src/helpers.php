<?php

use MicroweberPackages\Url\Facades\UrlManager;

/**
 * URL helper functions - loaded via composer autoload files.
 *
 * These functions provide backward compatibility with the original Microweber
 * URL functions while delegating to the new MicroweberPackages\Url package.
 */

if (! function_exists('mw_url_manager')) {
    /**
     * Get the URL manager instance.
     *
     * @return \MicroweberPackages\Url\UrlManagerService
     */
    function mw_url_manager()
    {
        return UrlManager::getFacadeRoot();
    }
}

if (! function_exists('mw_is_safe_remote_url')) {
    /**
     * Check if a URL is a safe remote URL.
     *
     * @param  mixed  $url
     * @return bool
     */
    function mw_is_safe_remote_url($url): bool
    {
        return \MicroweberPackages\Url\UrlSecurity::isSafeRemoteUrl($url);
    }
}

if (! function_exists('safe_css_url')) {
    /**
     * Sanitize a URL for safe interpolation inside inline CSS url('...').
     *
     * @param  string|null  $url
     * @return string
     */
    function safe_css_url($url): string
    {
        return \MicroweberPackages\Url\UrlSecurity::safeCssUrl($url);
    }
}
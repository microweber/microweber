<?php

/**
 * URL security helper functions.
 *
 * These now delegate to the MicroweberPackages\Url\UrlSecurity class.
 * Kept here for backward compatibility with existing code.
 */

if (! function_exists('mw_is_safe_remote_url')) {
    function mw_is_safe_remote_url($url): bool
    {
        return \MicroweberPackages\Url\UrlSecurity::isSafeRemoteUrl($url);
    }
}

if (! function_exists('safe_css_url')) {
    function safe_css_url($url): string
    {
        return \MicroweberPackages\Url\UrlSecurity::safeCssUrl($url);
    }
}

<?php

namespace Modules\Content\Support;

use MicroweberPackages\Url\Facades\UrlManager;


/**
 * Expands `{SITE_URL}` placeholders in a resolved content-field read result.
 *
 * The `content_field_manager` package is provider-agnostic — it stores and returns
 * field values verbatim and has no dependency on the CMS `url_manager`. So the
 * placeholder → absolute-URL expansion that every editable-field read needs stays
 * here, in the CMS glue, instead of being repeated (with subtly divergent guards)
 * at each consumer of the package's read output.
 *
 * `url_manager->replace_site_url_back()` already recurses over nested arrays/strings,
 * so a single entry point covers both the full-row (`getFieldData`) and the
 * value/array (`getField`) return shapes.
 */
class SiteUrlFieldCast
{
    /**
     * @param  mixed  $result  A content-field read result (array row, value array, or scalar).
     * @return mixed  The same result with `{SITE_URL}` expanded when it's a non-empty array
     *                and `url_manager` is available; otherwise the input unchanged.
     */
    public static function expand($result)
    {
        if (! app()->bound(\MicroweberPackages\Url\UrlManagerService::class)) {
            return $result;
        }

        if (is_array($result) && $result !== []) {
            return UrlManager::replace_site_url_back($result);
        }

        // A field can also be read as a scalar HTML string (getField's
        // value shape). replace_site_url_back() recurses over strings too, so a
        // saved `{SITE_URL}…` (e.g. a skin's data-background-image / <img src>)
        // must be expanded here as well — otherwise the raw placeholder leaks
        // into the public render and the asset 404s. Gate on the placeholder's
        // presence so this is a strict no-op for every other string (and never
        // trips replace_site_url_back's falsey "0"/"" → null guard).
        if (is_string($result) && str_contains($result, '{SITE_URL}')) {
            return UrlManager::replace_site_url_back($result);
        }

        return $result;
    }
}

<?php

namespace Modules\Content\Support;

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
        if (is_array($result) && $result !== [] && app()->bound('url_manager')) {
            return app()->url_manager->replace_site_url_back($result);
        }

        return $result;
    }
}

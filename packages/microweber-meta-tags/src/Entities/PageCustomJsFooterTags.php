<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

/**
 * Per-PAGE custom JS layer — the content-id sibling of LiveEditJsFooterTags.
 *
 * Emits a deferred <script> in the FOOTER pointing at the current page's
 * userfiles/css/pages/live_edit_<contentId>.js (saved by LiveEditCssManager::
 * savePageJsContent). Two guards, mirroring the global layer:
 *   - never emitted inside the Live-Edit canvas (is_live_edit() is true there),
 *     so a broken or looping per-page script can never break the editor;
 *   - getPageJsUrl() returns null for a missing or EMPTY file, so no empty
 *     <script> is ever shipped.
 */
class PageCustomJsFooterTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        if (function_exists('is_live_edit') && is_live_edit()) {
            return '';
        }
        if (!function_exists('content_id')) {
            return '';
        }
        $contentId = (int) content_id();
        if ($contentId <= 0) {
            return '';
        }

        $url = app()->template_manager->liveEditCssAdapter->getPageJsUrl($contentId);
        if (!$url) {
            return '';
        }

        return '<script src="' . $url . '" id="mw-page-custom-js" defer crossorigin="anonymous" referrerpolicy="no-referrer"></script>';
    }

    public function getPlacement(): string
    {
        return Meta::PLACEMENT_FOOTER;
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }

    public function toArray(): array
    {
        return [
            'type' => 'page_custom_js_footer_tags',
        ];
    }
}

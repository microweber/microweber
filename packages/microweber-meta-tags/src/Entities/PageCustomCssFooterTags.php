<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

/**
 * Per-PAGE custom CSS layer — the content-id sibling of the global live_edit.css.
 *
 * The global template live_edit.css is injected at the END of <body> (so it wins
 * the cascade over the template defaults), so a per-page override must render
 * there too, AFTER it — a <head> link could never win. This emits the current
 * page's userfiles/css/pages/live_edit_<contentId>.css as a FOOTER stylesheet,
 * registered LAST so it is the final CSS on the page and overrides both the
 * template defaults and the global live_edit layer for this content id.
 *
 * getPageCssUrl() returns null for a missing or EMPTY file, so no empty <link>
 * is ever shipped, and nothing is emitted when there is no current content id.
 */
class PageCustomCssFooterTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        if (!function_exists('content_id')) {
            return '';
        }
        $contentId = (int) content_id();
        if ($contentId <= 0) {
            return '';
        }

        $url = app()->template_manager->liveEditCssAdapter->getPageCssUrl($contentId);
        if (!$url) {
            return '';
        }

        return '<link rel="stylesheet" href="' . $url . '" id="mw-page-custom-css" crossorigin="anonymous" referrerpolicy="no-referrer" type="text/css" />';
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
            'type' => 'page_custom_css_footer_tags',
        ];
    }
}

<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

/**
 * Custom JavaScript layer for Live Edit — the JS sibling of LiveEditCssHeadTags.
 *
 * Emits a deferred <script> in the FOOTER pointing at the active template's
 * userfiles/css/<Template>/live_edit.js (saved by LiveEditCssManager::
 * saveLiveEditJsContent / the apply_js AI tool). Two guards:
 *   - it is emitted ONLY on the published page, never inside the Live-Edit canvas
 *     (is_live_edit() is true there), so a broken or looping script can never
 *     break the editor;
 *   - getLiveEditJsUrl() returns null for a missing or EMPTY file, so no empty
 *     <script> is ever shipped.
 */
class LiveEditJsFooterTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        // Suppress custom JS ONLY inside the Live-Edit editor CANVAS (loaded as an
        // iframe) so a reveal/animation can't fight the editing surface — but DO
        // run it on the real published page, including an admin's own top-level
        // view of their site. The canvas is detected by the iframe request
        // (Sec-Fetch-Dest: iframe, the same signal the frontend uses to enter edit
        // mode), NOT the "editmode" SESSION flag, which stays set after the admin
        // leaves the editor and otherwise wrongly hides the custom JS from every
        // later page view in that session.
        if (function_exists('is_live_edit') && is_live_edit()) {
            $dest = (function_exists('request') && request()) ? request()->header('Sec-Fetch-Dest') : null;
            if ($dest === 'iframe' || $dest === 'frame' || $dest === 'embed') {
                return '';
            }
        }

        $url = app()->template_manager->liveEditCssAdapter->getLiveEditJsUrl();
        if (!$url) {
            return '';
        }

        return '<script src="' . $url . '" id="mw-template-custom-js" defer crossorigin="anonymous" referrerpolicy="no-referrer"></script>';
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
            'type' => 'live_edit_js_footer_tags',
        ];
    }
}

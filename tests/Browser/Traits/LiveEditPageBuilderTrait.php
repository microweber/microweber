<?php

namespace Tests\Browser\Traits;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Modules\Page\Models\Page;

/**
 * Shared helpers for Dusk tests that build full landing pages through
 * the live-edit UI.
 *
 * Tests compose this trait on top of {@see AdminLoginTrait}. Each helper
 * is deliberately small and side-effect-scoped so that test classes can
 * cherry-pick the steps they exercise without re-doing the plumbing:
 *
 *   - {@see createBlankPage()}: inserts a Page row via save_content() and
 *     returns its id. Using the server-side helper (instead of clicking
 *     through admin) keeps fixture setup fast and deterministic.
 *   - {@see openInLiveEdit()}: navigates to /admin/live-edit?url=<link>
 *     and waits for the canvas iframe to be ready.
 *   - {@see insertLayoutByCategory()}: opens the Insert Layout picker,
 *     filters by category, and clicks a skin card by its "template"
 *     slug (e.g. "pricing/skin-2").
 *   - {@see editInlineText()}: replaces text inside an editable field
 *     within the canvas iframe so the live-edit save pipeline picks it
 *     up.
 *   - {@see saveLiveEdit()}: invokes the live-edit save service and
 *     awaits its completion (POST /api/content/save_edit).
 *   - {@see assertPublicPageContains()}: visits the public page URL
 *     and asserts the rendered HTML contains the given needle — used
 *     to verify that live-edit changes round-trip to the public render.
 *
 * All helpers target the Bootstrap template so that skins known to the
 * wider plan (pricing/skin-2, features/skin-2, hosting-compare etc.)
 * are guaranteed to resolve. Set active_site_template=Bootstrap at
 * fixture time via {@see ensureBootstrapActive()}.
 */
trait LiveEditPageBuilderTrait
{
    /**
     * Track pages this trait created in the current test so the
     * tearDown helper can delete them without touching unrelated data.
     *
     * @var int[]
     */
    protected array $liveEditCreatedPageIds = [];

    /**
     * Make sure the globally-configured current_template is "Bootstrap"
     * and seed the row if missing. Called by createBlankPage() so each
     * landing-page test starts on a known template.
     */
    protected function ensureBootstrapActive(): void
    {
        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();

        if ($row) {
            if ($row->option_value !== 'Bootstrap') {
                DB::table('options')
                    ->where('id', $row->id)
                    ->update([
                        'option_value' => 'Bootstrap',
                        'updated_at' => now(),
                    ]);
            }
            return;
        }

        DB::table('options')->insert([
            'option_key' => 'current_template',
            'option_value' => 'Bootstrap',
            'option_group' => 'template',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Create a blank Bootstrap-template page and return its id.
     *
     * The Browser param is reserved for future flows that drive page
     * creation through the admin UI; today it's unused but keeps the
     * signature consistent with the rest of the trait.
     */
    protected function createBlankPage(Browser $browser, string $title): int
    {
        $this->ensureBootstrapActive();

        $slug = 'landing-test-' . substr(md5($title . microtime(true)), 0, 10);

        $id = save_content([
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => $title,
            'url' => $slug,
            'active_site_template' => 'Bootstrap',
            'layout_file' => 'clean.blade.php',
            'is_active' => 1,
            'content' => '',
        ]);

        if (!$id || !is_numeric($id)) {
            throw new \RuntimeException(
                'createBlankPage: save_content returned a non-id value: ' . var_export($id, true)
            );
        }

        $id = (int)$id;
        $this->liveEditCreatedPageIds[] = $id;
        return $id;
    }

    /**
     * Open the given page inside the live-edit iframe and wait for the
     * canvas and Insert-Layout Vue component to finish mounting.
     */
    protected function openInLiveEdit(Browser $browser, int $pageId): void
    {
        $link = content_link($pageId);
        if (!$link) {
            throw new \RuntimeException("openInLiveEdit: content_link({$pageId}) returned empty");
        }

        $url = '/admin/live-edit?url=' . urlencode($link);
        $browser->visit($url)->pause(5000);

        $browser->waitFor('iframe', 20)->pause(3000);

        // Wait until mw.app.editor is wired inside the top frame so
        // subsequent insertLayoutRequestOn* dispatches don't no-op.
        $ready = false;
        for ($i = 0; $i < 20; $i++) {
            $check = $browser->script("
                return (typeof window.mw !== 'undefined'
                    && window.mw.app
                    && window.mw.app.editor
                    && typeof window.mw.app.editor.dispatch === 'function'
                ) ? 1 : 0;
            ");
            if (($check[0] ?? 0) === 1) {
                $ready = true;
                break;
            }
            $browser->pause(500);
        }

        if (!$ready) {
            throw new \RuntimeException('openInLiveEdit: mw.app.editor never became ready');
        }
    }

    /**
     * Click a skin card inside the Insert Layout picker.
     *
     * @param string $category e.g. "Pricing" (matches the card's
     *                         categories field; case-insensitive).
     * @param string $skinSlug The layout template slug exactly as the
     *                         API returns it (e.g. "pricing/skin-2").
     */
    protected function insertLayoutByCategory(Browser $browser, string $category, string $skinSlug): void
    {
        // Open the picker.
        $browser->script("
            if (window.mw && mw.app && mw.app.editor) {
                mw.app.editor.dispatch('insertLayoutRequestOnBottom', null);
            }
        ");
        $browser->pause(2500);

        $dialogOpen = $browser->script("
            return document.querySelector('.mw-le-dialog-block.mw-le-layouts-dialog.active') !== null ? 1 : 0;
        ");
        if (($dialogOpen[0] ?? 0) !== 1) {
            throw new \RuntimeException('insertLayoutByCategory: picker dialog never opened');
        }

        // Filter by category, then click the matching card.
        $clicked = $browser->script(
            "
            var category = " . json_encode($category) . ";
            var skinSlug = " . json_encode($skinSlug) . ";

            var catLinks = document.querySelectorAll(
                '.mw-le-layouts-dialog .modules-list-categories li'
            );
            for (var i = 0; i < catLinks.length; i++) {
                var label = (catLinks[i].textContent || '').trim().toLowerCase();
                if (label === category.toLowerCase()) {
                    catLinks[i].click();
                    break;
                }
            }

            // Give Vue a tick to recompute layoutsListFiltered.
            var cards = document.querySelectorAll(
                '.mw-le-layouts-dialog .modules-list-block-item, '
                + '.mw-le-layouts-dialog .modules-list-block-item-masonry'
            );
            for (var j = 0; j < cards.length; j++) {
                var card = cards[j];
                var haystack = '';
                var iframe = card.querySelector('iframe.layout-preview-iframe');
                if (iframe) { haystack += (iframe.getAttribute('src') || ''); }
                var title = card.querySelector('.modules-list-block-item-masonry-title, .modules-list-block-item-title');
                if (title) { haystack += ' ' + (title.textContent || ''); }
                haystack += ' ' + (card.getAttribute('data-template') || '');

                if (haystack.toLowerCase().indexOf(skinSlug.toLowerCase()) !== -1
                    || haystack.toLowerCase().indexOf(skinSlug.replace('/', '__').toLowerCase()) !== -1) {
                    card.click();
                    return 1;
                }
            }
            return 0;
        "
        );

        if (($clicked[0] ?? 0) !== 1) {
            throw new \RuntimeException(
                "insertLayoutByCategory: no card matched skin '{$skinSlug}' under category '{$category}'"
            );
        }

        $browser->pause(2500);
    }

    /**
     * Replace the inner text of an editable field inside the canvas
     * iframe and dispatch an input event so the editor picks it up.
     *
     * @param string $fieldSelector CSS selector scoped to the canvas
     *                              iframe's document (e.g.
     *                              '.pricing-skin-2 h2').
     */
    protected function editInlineText(Browser $browser, string $fieldSelector, string $text): void
    {
        $result = $browser->script(
            "
            var doc = null;
            if (window.mw && mw.app && mw.app.canvas
                && typeof mw.app.canvas.getDocument === 'function') {
                try { doc = mw.app.canvas.getDocument(); } catch (e) { doc = null; }
            }
            if (!doc && window.mw && mw.app && mw.app.canvas
                && typeof mw.app.canvas.getWindow === 'function') {
                try {
                    var cw = mw.app.canvas.getWindow();
                    if (cw) doc = cw.document;
                } catch (e) {}
            }
            if (!doc) {
                var iframes = document.querySelectorAll('iframe');
                if (iframes.length === 0) return 'NO_IFRAME';
                var iframe = iframes[0];
                doc = iframe.contentDocument || (iframe.contentWindow && iframe.contentWindow.document);
            }
            if (!doc) return 'NO_DOC';
            var el = doc.querySelector(" . json_encode($fieldSelector) . ");
            if (!el) return 'NO_ELEMENT';
            el.innerHTML = " . json_encode($text) . ";
            el.dispatchEvent(new Event('input', { bubbles: true }));
            el.dispatchEvent(new Event('change', { bubbles: true }));
            return 'OK';
        "
        );

        $outcome = $result[0] ?? 'UNKNOWN';
        if ($outcome !== 'OK') {
            throw new \RuntimeException(
                "editInlineText: could not edit '{$fieldSelector}' — {$outcome}"
            );
        }

        $browser->pause(400);
    }

    /**
     * Trigger the live-edit save pipeline (POST /api/content/save_edit)
     * and block until the XHR completes.
     *
     * The save service `mw.liveEditSaveService` is defined inside the
     * canvas iframe (guarded by `window.self !== window.top` in
     * live-edit-page-scripts.js), so we reach into the iframe's window
     * just like SaveButton.vue does via `canvasWindow.mw.drag.save()`.
     */
    protected function saveLiveEdit(Browser $browser): void
    {
        $browser->script("
            window.__liveEditSaveDone = false;
            window.__liveEditSaveError = null;

            if (!(window.mw && mw.app && mw.app.canvas
                && typeof mw.app.canvas.getWindow === 'function')) {
                window.__liveEditSaveError = 'saveLiveEdit: mw.app.canvas.getWindow not available';
                return;
            }

            var canvasWindow;
            try {
                canvasWindow = mw.app.canvas.getWindow();
            } catch (e) {
                window.__liveEditSaveError = 'saveLiveEdit: getWindow threw: ' + (e && e.message ? e.message : e);
                return;
            }
            if (!canvasWindow || !canvasWindow.mw) {
                window.__liveEditSaveError = 'saveLiveEdit: canvas iframe has no mw global yet';
                return;
            }

            var xhr = null;
            try {
                if (canvasWindow.mw.drag && typeof canvasWindow.mw.drag.save === 'function') {
                    xhr = canvasWindow.mw.drag.save();
                } else if (canvasWindow.mw.liveEditSaveService
                    && typeof canvasWindow.mw.liveEditSaveService.save === 'function') {
                    xhr = canvasWindow.mw.liveEditSaveService.save();
                } else {
                    window.__liveEditSaveError = 'saveLiveEdit: canvasWindow.mw.drag.save / liveEditSaveService.save missing';
                    return;
                }
            } catch (e) {
                window.__liveEditSaveError = 'saveLiveEdit: save threw: ' + (e && e.message ? e.message : e);
                return;
            }

            if (xhr && typeof xhr.always === 'function') {
                xhr.always(function () { window.__liveEditSaveDone = true; });
            } else {
                window.__liveEditSaveDone = true;
            }
        ");

        for ($i = 0; $i < 40; $i++) {
            $state = $browser->script("
                return {
                    done: !!window.__liveEditSaveDone,
                    error: window.__liveEditSaveError
                };
            ");
            $state = $state[0] ?? ['done' => false, 'error' => null];
            if (!empty($state['error'])) {
                throw new \RuntimeException("saveLiveEdit: {$state['error']}");
            }
            if (!empty($state['done'])) {
                return;
            }
            $browser->pause(500);
        }

        throw new \RuntimeException('saveLiveEdit: save XHR never completed within 20s');
    }

    /**
     * Visit the page's public URL and assert the rendered HTML contains
     * the given needle. Useful for round-trip assertions after a save.
     */
    protected function assertPublicPageContains(Browser $browser, string $slug, string $needle): void
    {
        $browser->visit('/' . ltrim($slug, '/'))->pause(2500);

        $source = $browser->driver->getPageSource();
        $this->assertStringContainsString(
            $needle,
            $source,
            "Public page /{$slug} should contain '{$needle}' after live-edit save"
        );
    }

    /**
     * Remove any pages the current test created plus any stale
     * "landing-test-*" rows left behind by earlier runs.
     */
    protected function cleanupLandingTestPages(): void
    {
        $ids = array_unique($this->liveEditCreatedPageIds);
        foreach ($ids as $id) {
            try {
                Page::where('id', $id)->delete();
            } catch (\Throwable) {
                // best-effort — fall through
            }
        }
        $this->liveEditCreatedPageIds = [];

        try {
            Page::where('url', 'like', 'landing-test-%')->delete();
        } catch (\Throwable) {
            // best-effort
        }
    }
}

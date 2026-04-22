<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Phase-3 Insert Layout picker regression: every preview iframe's
 * `src` URL must carry `active_site_template=Bootstrap` when the
 * current template is Bootstrap.
 *
 * This is the regression guard for the blank-thumbnail bug fixed in
 * 36a0e6b: before that fix, the picker's preview endpoint ignored
 * the `active_site_template` query param and rendered against the
 * global `current_template`. That silently fell back to an empty
 * skin-1 whenever the picker asked for a skin that only ships in a
 * specific template (e.g. Bootstrap's pricing/skin-2). The fix now
 * propagates `active_site_template` from the list endpoint into
 * every `preview_url` so the iframe renders inside the correct
 * template context.
 *
 * The test:
 *   1. Seeds a Bootstrap landing page (LandingPageFactory guarantees
 *      `current_template=Bootstrap`).
 *   2. Opens it in live-edit.
 *   3. Opens the Insert Layout picker.
 *   4. Enumerates every `iframe.layout-preview-iframe` in the
 *      dialog (masonry + list variants) and asserts each iframe's
 *      `src` carries `active_site_template=Bootstrap`.
 *
 * It also asserts at least a reasonable number of iframes are found
 * so the test doesn't trivially pass on an empty picker (that would
 * hide a different regression — the picker failing to render any
 * cards at all).
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditLayoutPickerPreviewUrlTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    /**
     * The picker shows a lot of skins; even the narrowest preset (no
     * keyword, no category) should return many more than this. Set
     * this just high enough to prove iframes were actually rendered
     * instead of the dialog opening empty.
     */
    private const MIN_IFRAMES_EXPECTED = 5;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function every_preview_iframe_carries_active_site_template_bootstrap(): void
    {
        $landing = LandingPageFactory::make('Picker preview url check');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->openPicker($browser);

            $report = $this->collectIframeReport($browser);

            $this->assertGreaterThanOrEqual(
                self::MIN_IFRAMES_EXPECTED,
                $report['total'],
                sprintf(
                    'Insert Layout picker must render at least %d preview iframes (observed: %d) — empty picker would hide the preview_url regression',
                    self::MIN_IFRAMES_EXPECTED,
                    $report['total']
                )
            );

            $this->assertGreaterThan(
                0,
                $report['withSrc'],
                'At least one picker iframe must have a populated src (MasonryWall mounts them eagerly)'
            );

            $this->assertSame(
                [],
                $report['badUrls'],
                'Every picker iframe src must carry active_site_template=Bootstrap; offending URLs: '
                    . json_encode($report['badUrls'])
            );

            $this->assertSame(
                [],
                $report['missingPreviewUrl'],
                'Every picker iframe must have a src populated from item.preview_url; iframes with empty src: '
                    . json_encode($report['missingPreviewUrl'])
            );
        });
    }

    /**
     * Open the Insert Layout picker and wait for the iframes to
     * actually render into the DOM. We don't need to wait for them
     * to finish loading — the test only reads the `src` attribute
     * that Vue binds up-front.
     */
    private function openPicker(Browser $browser): void
    {
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
            throw new \RuntimeException('openPicker: picker dialog never opened');
        }

        // Wait for the MasonryWall / LazyList to mount at least one
        // iframe. `layoutsListLoaded` flips true once the REST list
        // fetch resolves; we then need one paint tick for the
        // template's Vue to render the iframes.
        for ($i = 0; $i < 30; $i++) {
            $count = $browser->script("
                return document.querySelectorAll(
                    '.mw-le-layouts-dialog iframe.layout-preview-iframe'
                ).length;
            ");
            if ((int)($count[0] ?? 0) > 0) {
                $browser->pause(800);
                return;
            }
            $browser->pause(500);
        }
        $browser->screenshot('fail-layout-picker-no-iframes');
        throw new \RuntimeException(
            'openPicker: no preview iframes rendered within 15s — picker is either empty or all items ship with static screenshots'
        );
    }

    /**
     * Enumerate every iframe in the dialog and report which ones:
     *   - Total: count
     *   - withSrc: src attribute is populated
     *   - missingPreviewUrl: in-DOM but src is empty/missing
     *   - badUrls: src is populated but does not carry
     *     `active_site_template=Bootstrap`
     *
     * @return array{total:int, withSrc:int, missingPreviewUrl:array<int,string>, badUrls:array<int,string>}
     */
    private function collectIframeReport(Browser $browser): array
    {
        $res = $browser->script("
            var iframes = document.querySelectorAll(
                '.mw-le-layouts-dialog iframe.layout-preview-iframe'
            );
            var total = iframes.length;
            var withSrc = 0;
            var missingPreviewUrl = [];
            var badUrls = [];
            for (var i = 0; i < iframes.length; i++) {
                var src = iframes[i].getAttribute('src') || '';
                var title = iframes[i].getAttribute('title') || '(no title)';
                if (!src) {
                    missingPreviewUrl.push(title);
                    continue;
                }
                withSrc++;
                var lower = src.toLowerCase();
                if (lower.indexOf('active_site_template=bootstrap') === -1) {
                    badUrls.push(title + ' :: ' + src);
                }
            }
            return {
                total: total,
                withSrc: withSrc,
                missingPreviewUrl: missingPreviewUrl,
                badUrls: badUrls
            };
        ");
        $report = $res[0] ?? [
            'total' => 0,
            'withSrc' => 0,
            'missingPreviewUrl' => [],
            'badUrls' => [],
        ];
        return $report;
    }
}

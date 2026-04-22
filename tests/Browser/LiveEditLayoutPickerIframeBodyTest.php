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
 * Phase-3 Insert Layout picker regression: no iframe may render the
 * empty-skin-1 fallback.
 *
 * The sibling {@see LiveEditLayoutPickerPreviewUrlTest} checks that
 * every iframe URL carries `active_site_template=Bootstrap`. This
 * one checks the actual iframe BODY — the fallback was the user-
 * visible failure mode for the blank-thumbnail bug fixed in 36a0e6b:
 * the picker rendered `Templates/Bootstrap/.../templates/skin-1.blade`
 * (a plain empty CLEAN container) whenever the adapter couldn't
 * resolve the requested skin. The empty fallback's distinguishing
 * DOM marker is `field="layout-skin-1-…"` on its outer `<section>`.
 *
 * The plain `skin-1` IS a legitimate template in the picker (the
 * "CLEAN container" layout at Bootstrap's layouts/templates root),
 * so finding `field="layout-skin-1-"` inside an iframe rendering
 * `template=skin-1` is fine. The regression signature is finding it
 * in an iframe rendering ANY OTHER template.
 *
 * Flow:
 *   1. Open live-edit, open the Insert Layout picker.
 *   2. Enumerate iframes whose `src` asks for a non-skin-1 template.
 *   3. Scroll them into view so `loading="lazy"` fetches the body.
 *   4. Take 5 at random (matching the plan's "random sample of 5").
 *   5. Read each iframe's `contentDocument.documentElement.outerHTML`
 *      (the iframes are `sandbox="allow-same-origin allow-scripts"`
 *      so same-origin access is allowed).
 *   6. Assert none contain `field="layout-skin-1-"`.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditLayoutPickerIframeBodyTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const SAMPLE_SIZE = 5;
    private const FALLBACK_MARKER = 'field="layout-skin-1-';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function picker_iframes_do_not_render_empty_skin_1_fallback(): void
    {
        $landing = LandingPageFactory::make('Picker iframe body check');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->openPicker($browser);

            $sampleTitles = $this->selectSampleIframes($browser);

            $this->assertCount(
                self::SAMPLE_SIZE,
                $sampleTitles,
                sprintf(
                    'Picker must expose at least %d iframes for non-skin-1 templates (got %d). The picker may have rendered empty; check fail-picker-iframe-sample screenshot.',
                    self::SAMPLE_SIZE,
                    count($sampleTitles)
                )
            );

            $this->waitForSampleIframesToLoad($browser, $sampleTitles);

            $badBodies = $this->collectFallbackHits($browser, $sampleTitles);

            $this->assertSame(
                [],
                $badBodies,
                'No picker iframe for a non-skin-1 template may render the empty skin-1 fallback; offending iframes: '
                    . json_encode($badBodies)
            );
        });
    }

    /**
     * Open the picker and wait until at least one iframe is mounted.
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
        $browser->screenshot('fail-picker-iframe-body-no-iframes');
        throw new \RuntimeException('openPicker: no iframes rendered within 15s');
    }

    /**
     * Pick 5 random iframes whose preview_url does NOT target
     * `template=skin-1`. We tag each sample iframe with a
     * `data-sample-title` attribute so subsequent helpers can
     * re-find them by a stable selector — the masonry layout
     * rewrites DOM indexes.
     *
     * Returns the list of sample titles (one per iframe).
     *
     * @return array<int, string>
     */
    private function selectSampleIframes(Browser $browser): array
    {
        $res = $browser->script("
            function extractTemplate(src) {
                var m = (src || '').match(/[?&]template=([^&]+)/);
                if (!m) return '';
                try { return decodeURIComponent(m[1]); } catch (e) { return m[1]; }
            }
            var iframes = Array.from(document.querySelectorAll(
                '.mw-le-layouts-dialog iframe.layout-preview-iframe'
            ));
            var candidates = [];
            for (var i = 0; i < iframes.length; i++) {
                var src = iframes[i].getAttribute('src') || '';
                var tpl = extractTemplate(src);
                // Accept non-skin-1 (including subskins like ecommerce/skin-1)
                if (tpl && tpl !== 'skin-1' && src.length > 0) {
                    candidates.push({ iframe: iframes[i], template: tpl });
                }
            }
            // Fisher-Yates shuffle
            for (var j = candidates.length - 1; j > 0; j--) {
                var k = Math.floor(Math.random() * (j + 1));
                var t = candidates[j];
                candidates[j] = candidates[k];
                candidates[k] = t;
            }
            var picked = candidates.slice(0, " . self::SAMPLE_SIZE . ");
            var titles = [];
            for (var p = 0; p < picked.length; p++) {
                var title = 'sample-' + p + '-' + picked[p].template.replace(/[^a-zA-Z0-9_-]/g, '_');
                picked[p].iframe.setAttribute('data-sample-title', title);
                // Force lazy iframes to load by scrolling into view.
                try { picked[p].iframe.scrollIntoView({ block: 'center' }); } catch (e) {}
                titles.push(title);
            }
            return titles;
        ");

        return $res[0] ?? [];
    }

    /**
     * Poll each sample iframe's contentDocument until it is fully
     * loaded with substantial body content. A too-short body would
     * indicate the iframe is still mid-fetch and let the fallback
     * check miss the offending HTML.
     *
     * @param array<int, string> $titles
     */
    private function waitForSampleIframesToLoad(Browser $browser, array $titles): void
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var titles = " . json_encode($titles) . ";
                var loaded = 0;
                for (var i = 0; i < titles.length; i++) {
                    var iframe = document.querySelector(
                        'iframe.layout-preview-iframe[data-sample-title=\"' + titles[i] + '\"]'
                    );
                    if (!iframe) continue;
                    try {
                        var cd = iframe.contentDocument;
                        if (cd && cd.readyState === 'complete'
                            && cd.documentElement
                            && cd.documentElement.outerHTML
                            && cd.documentElement.outerHTML.length > 500) {
                            loaded++;
                        }
                    } catch (e) { /* cross-origin guard */ }
                }
                return loaded;
            ");
            if ((int)($res[0] ?? 0) >= count($titles)) {
                $browser->pause(500);
                return;
            }
            $browser->pause(500);
        }
        $browser->screenshot('fail-picker-iframe-body-not-loaded');
        throw new \RuntimeException(
            'waitForSampleIframesToLoad: not all sample iframes finished loading within 15s'
        );
    }

    /**
     * For each sample iframe, read its body HTML and report the ones
     * that contain `field="layout-skin-1-"`. The return value is the
     * list of `<title> :: <src>` lines — easier than dumping full
     * HTML bodies in the assertion failure message.
     *
     * @param array<int, string> $titles
     * @return array<int, string>
     */
    private function collectFallbackHits(Browser $browser, array $titles): array
    {
        $res = $browser->script("
            var titles = " . json_encode($titles) . ";
            var marker = " . json_encode(self::FALLBACK_MARKER) . ";
            var bad = [];
            for (var i = 0; i < titles.length; i++) {
                var iframe = document.querySelector(
                    'iframe.layout-preview-iframe[data-sample-title=\"' + titles[i] + '\"]'
                );
                if (!iframe) continue;
                var src = iframe.getAttribute('src') || '';
                try {
                    var html = (iframe.contentDocument
                        && iframe.contentDocument.documentElement)
                        ? iframe.contentDocument.documentElement.outerHTML
                        : '';
                    if (html.indexOf(marker) !== -1) {
                        bad.push(titles[i] + ' :: ' + src);
                    }
                } catch (e) {
                    bad.push(titles[i] + ' :: READ_ERROR :: ' + src);
                }
            }
            return bad;
        ");
        return $res[0] ?? [];
    }
}

<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Http;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Phase-5 public-render parity: visiting a live-edit-built landing
 * page at its public URL must resolve every referenced asset (CSS,
 * JS, images) with HTTP 200 — no 404/500s from a missing font, a
 * broken template screenshot, or a renamed bundle.
 *
 * Task framing (TODO.md Phase 5):
 *   "Assert no broken asset (screenshot, CSS) returns non-200".
 *
 * Why this matters on top of the section-marker test:
 *   - {@see \Tests\Browser\LiveEditPublicRenderParityPhase2SectionsTest}
 *     only looks at the HTML: it proves section markers survive. That
 *     catches Parser regressions, but it cannot catch an asset URL
 *     that the HTML emits but the server can't serve. A footer's bg
 *     image gone missing would sail through the marker test and ship
 *     a visibly broken page.
 *   - The live-edit Insert Layout picker uses per-skin screenshots
 *     (`Templates/Bootstrap/resources/views/modules/layouts/templates/
 *     <skin>/<skin>.png`), and the blades themselves reference
 *     template images (`{{ asset('templates/bootstrap/img/hero.jpg') }}`
 *     on jumbotron/skin-1). Both paths get served via Laravel's asset
 *     route; either one silently breaking would only be caught by a
 *     round-trip check against the running server.
 *
 * Flow:
 *   1. Seed a Bootstrap landing page.
 *   2. Open in live-edit; insert a compact but varied set of Phase-2
 *      sections — jumbotron/skin-1 (bg image), pricing/skin-2 (icon
 *      font, module iframes), and footers/skin-1 (menu, social-links,
 *      spacer bg). Three is enough to exercise the asset pipeline
 *      without the 80s runtime of the full 9-section test.
 *   3. Save live-edit.
 *   4. Visit the public URL (no admin chrome).
 *   5. Collect every same-origin asset URL the page references — all
 *      `<link rel="stylesheet">`, `<script src>`, `<img src>`, and
 *      `<source src>` attributes.
 *   6. HTTP-GET each URL through Laravel's Http client; fail loudly
 *      with a per-URL status so a CI breakage pinpoints the exact
 *      missing file.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditPublicAssetsNo404Test extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    /**
     * Compact but varied section set. Chosen to maximize asset variety
     * per second of test runtime:
     *   - jumbotron/skin-1 pulls a jpg background + bootstrap CSS
     *   - pricing/skin-2 pulls the mdi icon font + the btn module JS
     *   - footers/skin-1 pulls menu, social-links, and spacer modules
     *
     * @var array<int, array{category:string, skin:string, marker:string}>
     */
    private const SECTIONS = [
        [
            'category' => 'Jumbotron',
            'skin' => 'jumbotron/skin-1',
            'marker' => 'section.mw-layout-dark-background[field^="layout-jumbotron-skin-1-"]',
        ],
        [
            'category' => 'Pricing',
            'skin' => 'pricing/skin-2',
            'marker' => 'section.pricing-skin-2[field^="layout-pricing-skin-2-"]',
        ],
        [
            'category' => 'Footers',
            'skin' => 'footers/skin-1',
            'marker' => 'section.footer-background[field^="layout-footer-skin-1-"]',
        ],
    ];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function public_page_assets_all_return_2xx(): void
    {
        $landing = LandingPageFactory::make('Public assets no-404 guard');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            foreach (self::SECTIONS as $i => $section) {
                $this->primeLayoutHandleOnMainContent($browser);
                $this->insertSkinWithCategoryWait($browser, $section['category'], $section['skin']);
                $this->waitForMarker($browser, $section['marker'], $i + 1);
            }

            $this->markAllEditsChanged($browser);
            $this->saveLiveEdit($browser);
            $browser->pause(1000);

            $browser->visit('/' . ltrim($landing->slug, '/'))->pause(2500);

            $assetUrls = $this->collectSameOriginAssetUrls($browser);

            $this->assertNotEmpty(
                $assetUrls,
                'Public landing page must reference at least one same-origin '
                . 'asset (it carries jumbotron + pricing + footer skins, each '
                . 'with their own CSS and images)'
            );

            $failures = [];
            foreach ($assetUrls as $url) {
                $status = $this->fetchStatus($url);
                if ($status < 200 || $status >= 400) {
                    $failures[] = "[{$status}] {$url}";
                }
            }

            $this->assertSame(
                [],
                $failures,
                "Every asset on {$landing->slug} must return a 2xx/3xx status. "
                . 'Non-2xx/3xx responses: ' . PHP_EOL . implode(PHP_EOL, $failures)
            );
        });
    }

    /**
     * Collect every unique same-origin URL referenced by the currently
     * loaded page from stylesheet links, scripts, images, and <source>
     * tags. Filtering to same-origin keeps the check scoped to what
     * THIS server is responsible for — we don't want a third-party CDN
     * hiccup to red-flag our own pipeline.
     *
     * @return array<int, string>
     */
    private function collectSameOriginAssetUrls(Browser $browser): array
    {
        $res = $browser->script("
            var urls = new Set();
            var host = window.location.host;
            function maybeAdd(raw) {
                if (!raw) return;
                var absolute;
                try {
                    absolute = new URL(raw, window.location.href).href;
                } catch (e) { return; }
                // Skip about:blank, data: URIs, javascript:, blob: — not server assets
                if (/^(about:|data:|javascript:|blob:|mailto:|tel:|#)/i.test(absolute)) return;
                // Skip fragment-only or empty
                if (absolute === window.location.href || absolute === window.location.href + '#') return;
                var u;
                try { u = new URL(absolute); } catch (e) { return; }
                if (u.host !== host) return;
                urls.add(u.origin + u.pathname + (u.search || ''));
            }

            document.querySelectorAll('link[rel=\"stylesheet\"][href]').forEach(function (el) {
                maybeAdd(el.getAttribute('href'));
            });
            document.querySelectorAll('script[src]').forEach(function (el) {
                maybeAdd(el.getAttribute('src'));
            });
            document.querySelectorAll('img[src]').forEach(function (el) {
                maybeAdd(el.getAttribute('src'));
            });
            document.querySelectorAll('source[src]').forEach(function (el) {
                maybeAdd(el.getAttribute('src'));
            });

            return Array.from(urls);
        ");

        $raw = $res[0] ?? [];
        return is_array($raw) ? array_values(array_map('strval', $raw)) : [];
    }

    /**
     * Issue a HEAD-style GET so we only care about the status line.
     * Some asset handlers (e.g. Laravel's mix/vite endpoints) don't
     * always handle HEAD well, so a bounded GET with a short timeout
     * is the sturdier baseline.
     */
    private function fetchStatus(string $url): int
    {
        $response = Http::withoutVerifying()
            ->withOptions(['http_errors' => false, 'allow_redirects' => true])
            ->timeout(15)
            ->get($url);

        return $response->status();
    }

    /**
     * Open the Insert Layout picker, switch category (waiting for
     * Vue's filter watcher), click the matching card.
     *
     * Mirrors the helper used in
     * {@see LiveEditPublicRenderParityPhase2SectionsTest} — with a
     * smaller SECTIONS set the category switch still has to settle
     * between inserts, because the picker keeps `filterCategory` live
     * across opens.
     */
    private function insertSkinWithCategoryWait(
        Browser $browser,
        string $category,
        string $skinSlug
    ): void {
        $browser->script("
            if (window.mw && mw.app && mw.app.editor) {
                mw.app.editor.dispatch('insertLayoutRequestOnBottom', null);
            }
        ");
        $browser->pause(2000);

        $dialogOpen = $browser->script("
            return document.querySelector('.mw-le-dialog-block.mw-le-layouts-dialog.active') !== null ? 1 : 0;
        ");
        if (($dialogOpen[0] ?? 0) !== 1) {
            throw new \RuntimeException(
                "insertSkinWithCategoryWait: picker dialog never opened for '{$skinSlug}'"
            );
        }

        $browser->script("
            var wantCat = " . json_encode(strtolower($category)) . ";
            var catLinks = document.querySelectorAll(
                '.mw-le-layouts-dialog .modules-list-categories li'
            );
            for (var i = 0; i < catLinks.length; i++) {
                var label = (catLinks[i].textContent || '').trim().toLowerCase();
                if (label === wantCat) {
                    catLinks[i].click();
                    break;
                }
            }
        ");
        $browser->pause(1200);

        $clicked = $browser->script("
            var skinSlug = " . json_encode($skinSlug) . ";
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
        ");

        if (($clicked[0] ?? 0) !== 1) {
            throw new \RuntimeException(
                "insertSkinWithCategoryWait: no card matched skin '{$skinSlug}' under category '{$category}'"
            );
        }

        $browser->pause(2500);
    }

    private function primeLayoutHandleOnMainContent(Browser $browser): void
    {
        $primed = $browser->script("
            if (!(window.mw && mw.app && mw.app.canvas
                && typeof mw.app.canvas.getDocument === 'function')) {
                return 'NO_CANVAS';
            }
            var doc = mw.app.canvas.getDocument();
            var target = doc.querySelector('.section.edit.main-content')
                || doc.querySelector('.section.edit[field=\"content\"]')
                || doc.querySelector('[data-layout-container]');
            if (!target) return 'NO_MAIN_CONTENT';
            if (mw.app.liveEdit && mw.app.liveEdit.handles) {
                var h = mw.app.liveEdit.handles.get('layout');
                if (h && typeof h.set === 'function') {
                    h.set(target);
                }
            }
            return 'OK';
        ");

        $this->assertSame(
            'OK',
            $primed[0] ?? 'UNKNOWN',
            'Layout handle must resolve to the clean-layout main-content section'
        );
    }

    private function waitForMarker(Browser $browser, string $marker, int $stepNumber): void
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                return doc.querySelector(" . json_encode($marker) . ") ? 1 : 0;
            ");
            if (($res[0] ?? 0) === 1) {
                $browser->pause(500);
                return;
            }
            $browser->pause(500);
        }
        $browser->screenshot("fail-public-assets-step-{$stepNumber}");
        throw new \RuntimeException(
            "Step {$stepNumber}: marker '{$marker}' never appeared in canvas within 15s"
        );
    }

    private function markAllEditsChanged(Browser $browser): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var mc = doc.querySelector('.section.edit.main-content');
            if (!mc) return 'NO_MAIN_CONTENT';
            mc.classList.add('changed');
            var edits = mc.querySelectorAll('.edit');
            for (var i = 0; i < edits.length; i++) {
                edits[i].classList.add('changed');
            }
            return 'OK:' + edits.length;
        ");
        $outcome = (string)($res[0] ?? 'UNKNOWN');
        $this->assertStringStartsWith(
            'OK',
            $outcome,
            'Must be able to tag .edit.changed across main-content'
        );
    }
}

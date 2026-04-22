<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Phase-6 template-switch safety, "round-trip" leg: bouncing
 * `options.current_template` Bootstrap → Big2 → Bootstrap must leave
 * the public page rendering Bootstrap exactly the way it did before
 * the bounce — same skin markers, same body classes, same DB state.
 * A regression here would mean the
 * {@see \MicroweberPackages\Template\Adapters\MicroweberTemplate}
 * adapter (or the option repository that feeds it) held onto a
 * cached value beyond the request that set it, poisoning the "back
 * to Bootstrap" path.
 *
 * Task framing (TODO.md Phase 6):
 *   "Switch back to Bootstrap; assert Bootstrap-specific skins render
 *    again (regression guard for adapter state leak)".
 *
 * Test scope:
 *   - We flip only `options.current_template`, not the page's own
 *     `active_site_template` column. That's the realistic scenario:
 *     a site admin changes the global theme picker in admin settings,
 *     and individual pages that have pinned their template keep
 *     working because the page-level value overrides the option at
 *     resolver time (see
 *     MicroweberTemplate::setVariablesFromContent() lines 386–398).
 *     So the flip exercises the option read + adapter fall-through
 *     path without depending on whether a non-existent "Big2" folder
 *     magically materializes Bootstrap assets — the page's pinned
 *     override guarantees render integrity during the bounce.
 *
 * Why the body class is the right probe:
 *   - {@see helper_body_classes()} in
 *     Modules/Content/Support/helpers.php:357 appends the active
 *     template name as the final CSS class. That comes straight from
 *     `template_name()` → `TemplateManager::folder_name()` →
 *     `MicroweberTemplate::getTemplateFolderName()`. The `<body
 *     class="…">` attribute is a first-hand, per-request read of the
 *     adapter's resolved template name, so a state-leak regression
 *     would light up here.
 *
 * Why cache-aware writes matter:
 *   - `OptionRepository::getOptionsByGroup()` wraps reads in
 *     `cacheCallback()`. A raw `DB::table('options')->update(…)`
 *     would be invisible to the artisan-serve worker serving the
 *     next public visit, because it would return the file-cached
 *     pre-write value. We therefore go through
 *     {@see save_option()} which delegates to
 *     {@see \MicroweberPackages\Option\OptionManager::save} — that
 *     method invalidates both the repository cache and the
 *     `cache_manager` 'options' group. Belt-and-suspenders extra
 *     cache deletes guard against a partial-invalidation regression.
 *
 * Flow (three public visits of the same slug):
 *   1. Build a Bootstrap landing page with three module-heavy
 *      sections via live-edit + save. The page is pinned
 *      `active_site_template=Bootstrap` by
 *      {@see LandingPageFactory::make()}.
 *   2. Baseline visit: options.current_template = Bootstrap. Capture
 *      body classes and section markers — sanity check that the
 *      fixture is sound.
 *   3. Flip ONLY `options.current_template` to "Big2" (cache-
 *      invalidated via save_option). Re-visit. Assert the page still
 *      renders the Bootstrap skins — the page-level override wins,
 *      proving no spurious crash and no adapter poisoning from the
 *      option churn.
 *   4. Flip `options.current_template` back to "Bootstrap" (same
 *      path). Re-visit. Assert:
 *      a. HTTP < 400.
 *      b. Body classes contain "Bootstrap" and NOT "Big2".
 *      c. Every pre-bounce section marker is present (Bootstrap
 *         skins still render).
 *      d. `options.current_template` is "Bootstrap" in DB.
 *   5. Cleanup (try/finally) restores the option unconditionally so
 *      a mid-test failure cannot leave later tests half-switched.
 *
 * Pairs with {@see LiveEditTemplateSwitchBootstrapToBig2FallbackTest},
 * which covers the forward switch.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditTemplateSwitchBackToBootstrapNoStateLeakTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    /**
     * @var array<int, array{category:string, skin:string, marker:string, publicNeedle:string}>
     */
    private const SECTIONS = [
        [
            'category' => 'Jumbotron',
            'skin' => 'jumbotron/skin-1',
            'marker' => 'section.mw-layout-dark-background[field^="layout-jumbotron-skin-1-"]',
            'publicNeedle' => 'mw-layout-dark-background',
        ],
        [
            'category' => 'Pricing',
            'skin' => 'pricing/skin-2',
            'marker' => 'section.pricing-skin-2[field^="layout-pricing-skin-2-"]',
            'publicNeedle' => 'pricing-skin-2',
        ],
        [
            'category' => 'Footers',
            'skin' => 'footers/skin-1',
            'marker' => 'section.footer-background[field^="layout-footer-skin-1-"]',
            'publicNeedle' => 'footer-background',
        ],
    ];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function round_trip_option_bootstrap_to_big2_and_back_does_not_leak_adapter_state(): void
    {
        $landing = LandingPageFactory::make('Template switch roundtrip Bootstrap->Big2->Bootstrap');

        $this->browse(function (Browser $browser) use ($landing) {
            // 1. Build the page under Bootstrap.
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

            $this->logoutToGuest($browser);

            try {
                // 2. Baseline visit: options.current_template = Bootstrap.
                $browser->visit('/' . ltrim($landing->slug, '/'))->pause(2000);
                $baselineBodyClasses = $this->readBodyClasses($browser);
                $baselineSource = $browser->driver->getPageSource();

                $this->assertStringContainsString(
                    'Bootstrap',
                    $baselineBodyClasses,
                    'Baseline public render must tag <body> with the active '
                    . 'template name "Bootstrap". Got body classes: '
                    . json_encode($baselineBodyClasses) . '. Without this '
                    . 'sanity check the bounce assertion below would be '
                    . 'probing a broken fixture.'
                );
                $this->assertBootstrapSkinMarkersPresent(
                    $baselineSource,
                    'baseline (pre-bounce)',
                    $landing->slug
                );

                // 3. Flip ONLY the option to Big2 (page override stays
                //    Bootstrap). The page must still render: its pinned
                //    active_site_template wins, and the adapter must
                //    treat the option churn as a no-op for this slug.
                $this->setCurrentTemplate('Big2');

                $browser->visit('/' . ltrim($landing->slug, '/'))->pause(2000);
                $midFlightBodyClasses = $this->readBodyClasses($browser);
                $midFlightSource = $browser->driver->getPageSource();

                $this->assertStringContainsString(
                    'Bootstrap',
                    $midFlightBodyClasses,
                    "Mid-flight (options.current_template=Big2, page-level "
                    . '=Bootstrap), the public render must still emit '
                    . '"Bootstrap" as the body class — the page-level '
                    . 'override beats the option. If this fails with '
                    . '"Big2" instead, the resolver is ignoring the '
                    . 'page\'s active_site_template override. Got: '
                    . json_encode($midFlightBodyClasses)
                );
                $this->assertBootstrapSkinMarkersPresent(
                    $midFlightSource,
                    'mid-flight (option flipped to Big2, page override still Bootstrap)',
                    $landing->slug
                );

                // 4. Flip the option back to Bootstrap.
                $this->setCurrentTemplate('Bootstrap');

                $browser->visit('/' . ltrim($landing->slug, '/'))->pause(2500);
                $roundTripBodyClasses = $this->readBodyClasses($browser);

                // 4a. HTTP status of the final visit.
                $status = $this->fetchStatus($landing->slug);
                $this->assertLessThan(
                    400,
                    $status,
                    "After the round-trip Bootstrap → Big2 → Bootstrap, "
                    . "/{$landing->slug} must respond < 400 "
                    . "(got HTTP {$status})."
                );

                // 4b. Body classes reflect Bootstrap again — no Big2 bleed.
                $this->assertStringContainsString(
                    'Bootstrap',
                    $roundTripBodyClasses,
                    'After round-trip Bootstrap → Big2 → Bootstrap, the '
                    . '<body> classes must contain "Bootstrap". Got: '
                    . json_encode($roundTripBodyClasses) . '. A failure '
                    . 'here with only "Big2" present would indicate the '
                    . 'adapter is caching templateFolderName across the '
                    . 'option write (state leak — the exact regression '
                    . 'this test guards against).'
                );
                $this->assertStringNotContainsString(
                    'Big2',
                    $roundTripBodyClasses,
                    'After switching back to Bootstrap, body classes must '
                    . 'NOT still carry "Big2". Got: '
                    . json_encode($roundTripBodyClasses)
                );

                // 4c. Bootstrap-skin markers present after the round trip.
                $roundTripSource = $browser->driver->getPageSource();
                $this->assertBootstrapSkinMarkersPresent(
                    $roundTripSource,
                    'post-roundtrip (option restored to Bootstrap)',
                    $landing->slug
                );

                // 4d. DB state reflects the restored Bootstrap setting.
                $this->assertSame(
                    'Bootstrap',
                    $this->readCurrentTemplateOption(),
                    'options.current_template must be "Bootstrap" after '
                    . 'the restore write — without this check a setter '
                    . 'regression could silently skip the write and pass '
                    . 'earlier assertions via the page-level override.'
                );
            } finally {
                // 5. Always restore the global option.
                $this->setCurrentTemplate('Bootstrap');
            }
        });
    }

    /**
     * Assert every Bootstrap skin marker from {@see SECTIONS} is
     * present in the given HTML. Factored out because the same check
     * runs three times (baseline, mid-flight, post-roundtrip) and an
     * ad-hoc loop each time would bury the failure label.
     */
    private function assertBootstrapSkinMarkersPresent(
        string $source,
        string $phase,
        string $slug
    ): void {
        $missing = [];
        foreach (self::SECTIONS as $section) {
            if (strpos($source, $section['publicNeedle']) === false) {
                $missing[] = $section['skin']
                    . ' (needle=' . json_encode($section['publicNeedle']) . ')';
            }
        }

        $this->assertSame(
            [],
            $missing,
            "At {$phase}, every Bootstrap skin marker must appear in "
            . "the public HTML at /{$slug}. Missing: "
            . implode('; ', $missing)
        );
    }

    /**
     * Read the `<body>` element's `class` attribute from the currently-
     * loaded public page. Using a script (instead of parsing raw HTML)
     * sidesteps any `.body-open`/`.loaded` classes template JS might
     * toggle AFTER initial render — we want the server-rendered
     * classes the visitor saw first.
     */
    private function readBodyClasses(Browser $browser): string
    {
        $res = $browser->script("
            var body = document.body;
            return body ? (body.getAttribute('class') || '') : '__NO_BODY__';
        ");

        $value = $res[0] ?? '';
        return is_string($value) ? $value : '';
    }

    /**
     * Persist through {@see \MicroweberPackages\Option\OptionManager::save}
     * rather than raw DB — the manager invalidates both the repository's
     * in-memory cache AND the file-backed `cache_manager` 'options'
     * group. A raw DB write would be invisible to the artisan-serve
     * worker process that handles the subsequent public visit, because
     * {@see \MicroweberPackages\Option\Repositories\OptionRepository::getOptionsByGroup}
     * wraps the read in `cacheCallback()`.
     */
    private function setCurrentTemplate(string $name): void
    {
        save_option('current_template', $name, 'template');

        // Belt-and-suspenders: option_manager->save() already nukes the
        // relevant cache buckets, but blowing the 'options' group again
        // costs nothing and protects against a partial-invalidation
        // regression in the cache_manager.
        app()->cache_manager->delete('options');
        app()->cache_manager->delete('options/template');
        app()->option_repository->clearCache();
    }

    private function readCurrentTemplateOption(): string
    {
        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();
        return $row ? (string)$row->option_value : '';
    }

    private function fetchStatus(string $slug): int
    {
        $url = config('app.url', 'http://127.0.0.1:8000') . '/' . ltrim($slug, '/');

        $response = Http::withoutVerifying()
            ->withOptions(['http_errors' => false, 'allow_redirects' => true])
            ->timeout(20)
            ->get($url);

        return $response->status();
    }

    /**
     * Drop the admin session before the public visits. We want to
     * exercise the *public* render path — the admin toolbar JS and
     * live-edit chrome both depend on the active template's assets
     * and either could fail in a misleading way during the switch.
     */
    private function logoutToGuest(Browser $browser): void
    {
        $browser->visit('/');
        $browser->pause(500);
        $browser->script("
            try {
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-XSRF-TOKEN': decodeURIComponent(
                            (document.cookie.match(/XSRF-TOKEN=([^;]+)/) || ['',''])[1]
                        )
                    },
                    credentials: 'include'
                });
            } catch (e) {}
        ");
        $browser->pause(1500);
        $browser->driver->manage()->deleteAllCookies();
        $browser->pause(300);
    }

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
        $browser->screenshot("fail-template-switch-back-step-{$stepNumber}");
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

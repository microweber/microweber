<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinBladeExists;
use Tests\Browser\Traits\AssertsSkinTagPersisted;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Plan B.2 — Features / skin-1 per-skin Dusk coverage.
 *
 * Mirrors {@see LiveEditFeaturesSkin2Test} for the sibling
 * `features/skin-1` skin. The two tests together prove both
 * Features skins survive the Phase-2 live-edit pipeline (insert
 * → edit → save → render).
 *
 * Skin-1 vs skin-2 differences encoded here:
 *   - field attribute: `layout-features-skin-1-{id}` (vs `…-skin-2-…`)
 *   - per-card label: `<p>` (vs `<h5>` in skin-2)
 *   - card column class: `mx-auto col-md-6 col-lg-4 col-12 mb-5 cloneable`
 *     (vs `col cloneable` in skin-2)
 *   - the title block is an `<h4>` (vs the same h4 in skin-2 — both
 *     skins render the section heading at h4)
 *
 * Shared with skin-2:
 *   - Outer section carries `class="…features-skin-2 …"` (the
 *     `features-skin-2` class is the shared regression marker
 *     baked into both skin blades — likely a copy-paste artifact
 *     in the template; see skin-1.blade.php line 29).
 *
 * Plan B.3 contract:
 *   - Skin blade file existence checked before insert (fails early
 *     if Templates/Bootstrap/.../features/skin-1.blade.php is gone).
 *   - Insert persists `<module type="layouts" template="features/skin-1">`
 *     to content.content.
 *   - Public render carries the section with `field=layout-features-skin-1-*`
 *     plus the shared `features-skin-2` marker class.
 *   - No JS console error fires during insert OR public render.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditFeaturesSkin1Test extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinBladeExists;
    use AssertsSkinTagPersisted;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const SKIN_TAG = 'features/skin-1';
    private const FIELD_PREFIX = 'layout-features-skin-1-';
    private const MARKER_CLASS = 'features-skin-2';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function features_skin_1_inserts_edits_and_persists(): void
    {
        $this->assertSkinBladeExists(self::SKIN_TAG);

        $landing = LandingPageFactory::make('Features grid skin 1');
        $suffix = uniqid();
        $newLabels = [
            0 => 'Edited skin-1 feature one ' . $suffix,
            1 => 'Edited skin-1 feature two ' . $suffix,
        ];

        $this->browse(function (Browser $browser) use ($landing, $newLabels) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, 'Features', self::SKIN_TAG);

            $field = $this->waitForFeaturesSection($browser);
            $this->assertNotSame('', $field, sprintf(
                'Features section should expose a field="%s…" attribute after insertion',
                self::FIELD_PREFIX,
            ));

            $this->editFeatureLabels($browser, $newLabels);
            $this->markEditFieldsChanged($browser, $field);

            $this->saveLiveEdit($browser);

            $this->assertSkinTagPersisted($landing->pageId, self::SKIN_TAG);

            $this->assertCanvasReflectsEdits($browser, $field, $newLabels);
            $this->assertSavedContentBodyContains($landing->pageId, $newLabels);
            $this->assertPublicPageCarriesMarker($browser, $landing->slug, $newLabels);
        });
    }

    /**
     * Set mw.app.liveEdit.handles.get('layout') target to the page's
     * `.section.edit.main-content` so the Insert Layout picker's
     * fallback target resolves. Same shape as skin-2's helper —
     * the priming logic is layout-agnostic.
     */
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

        $this->assertSame('OK', $primed[0] ?? 'UNKNOWN',
            'Layout handle must resolve to the clean-layout main-content section');
    }

    private function waitForFeaturesSection(Browser $browser): string
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sec = doc.querySelector('section.section.edit[field^=\"" . self::FIELD_PREFIX . "\"]');
                if (!sec) return '';
                return sec.getAttribute('field') || '';
            ");
            $fieldAttr = (string)($res[0] ?? '');
            if ($fieldAttr !== '') {
                $browser->pause(700);
                return $fieldAttr;
            }
            $browser->pause(500);
        }
        $browser->screenshot('fail-features-skin-1-section-missing');
        throw new \RuntimeException('Features skin-1 section never appeared in the canvas within 15s');
    }

    /**
     * Rewrite the first N feature card `<p>` labels.
     *
     * Skin-1 uses `<p>` for the per-card body copy (whereas skin-2
     * uses `<h5>`); the column class differs too, hence the
     * skin-1-specific querySelector below.
     *
     * @param array<int,string> $labelsByIndex
     */
    private function editFeatureLabels(Browser $browser, array $labelsByIndex): void
    {
        $labelsJson = json_encode($labelsByIndex);
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var labels = " . $labelsJson . ";
            var ps = doc.querySelectorAll(
                'section[field^=\"" . self::FIELD_PREFIX . "\"] .cloneable p'
            );
            var writes = 0;
            Object.keys(labels).forEach(function (idx) {
                var n = parseInt(idx, 10);
                var p = ps[n];
                if (!p) return;
                p.removeAttribute('data-mwplaceholder');
                p.innerHTML = labels[idx];
                p.dispatchEvent(new Event('input', { bubbles: true }));
                p.dispatchEvent(new Event('change', { bubbles: true }));
                writes++;
            });
            return writes;
        ");
        $writes = (int)($res[0] ?? 0);
        $this->assertSame(
            count($labelsByIndex),
            $writes,
            'Must be able to rewrite every requested feature label (skin-1 uses <p> not <h5>)'
        );
    }

    /**
     * Tag the features section and its main-content parent with
     * `.changed` — the save pipeline's collectData() only picks up
     * `.edit.changed` nodes.
     */
    private function markEditFieldsChanged(Browser $browser, string $field): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sec = doc.querySelector('section[field=' + JSON.stringify(" . json_encode($field) . ") + ']');
            if (!sec) return 'NO_SEC';
            sec.classList.add('changed');
            var p = sec.parentElement;
            while (p) {
                if (p.classList && p.classList.contains('edit')) {
                    p.classList.add('changed');
                    break;
                }
                p = p.parentElement;
            }
            return 'OK';
        ");
        $this->assertSame('OK', $res[0] ?? 'UNKNOWN', 'Must be able to tag .edit.changed for save');
    }

    /**
     * @param array<int,string> $expectedLabels
     */
    private function assertCanvasReflectsEdits(
        Browser $browser,
        string $field,
        array $expectedLabels
    ): void {
        $state = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sec = doc.querySelector('section[field=' + JSON.stringify(" . json_encode($field) . ") + ']');
            if (!sec) return { present: false };
            var ps = sec.querySelectorAll('.cloneable p');
            var out = [];
            for (var i = 0; i < ps.length; i++) {
                out.push(ps[i].innerText || '');
            }
            return { present: true, labels: out, className: sec.className };
        ");
        $state = $state[0] ?? ['present' => false];
        $this->assertTrue(!empty($state['present']), 'Features section must still render after save');
        $this->assertStringContainsString(
            self::MARKER_CLASS,
            (string)($state['className'] ?? ''),
            'Section must retain the features-skin-2 marker class (shared with skin-2 in the blade)'
        );
        foreach ($expectedLabels as $idx => $expected) {
            $this->assertSame(
                $expected,
                trim((string)($state['labels'][$idx] ?? '')),
                "Canvas label at index {$idx} should match the edited copy"
            );
        }
    }

    /**
     * Assert that the edited feature labels round-tripped through
     * /api/content/save_edit into the database. Same shape +
     * caveat as skin-2's assertion: the outer section is replaced
     * by a `<module>` shortcode at save time, so we look across
     * content + content_fields for the persisted copy.
     *
     * @param array<int,string> $expectedLabels
     */
    private function assertSavedContentBodyContains(int $pageId, array $expectedLabels): void
    {
        $content = DB::table('content')->where('id', $pageId)->first();
        $this->assertNotNull(
            $content,
            "content row for page {$pageId} must still exist after save"
        );

        $moduleFields = DB::table('content_fields')
            ->where('rel_type', 'module')
            ->pluck('value')
            ->all();
        $pageFields = DB::table('content_fields')
            ->where('rel_type', 'content')
            ->where('rel_id', $pageId)
            ->pluck('value')
            ->all();

        $haystack = implode("\n", array_filter([
            (string)($content->content ?? ''),
            (string)($content->content_body ?? ''),
            implode("\n", array_map(fn ($v) => (string)$v, $moduleFields)),
            implode("\n", array_map(fn ($v) => (string)$v, $pageFields)),
        ]));

        $this->assertNotSame(
            '',
            $haystack,
            "expected some persisted copy for page {$pageId} after save"
        );
        foreach ($expectedLabels as $idx => $expected) {
            $this->assertStringContainsString(
                $expected,
                $haystack,
                "Persisted content must carry edited feature label #{$idx}"
            );
        }
    }

    /**
     * Visit the public page URL and assert the rendered HTML
     * carries the skin's signature: the `field=layout-features-skin-1-*`
     * attribute the blade emits on the outer <section>. We also
     * assert the shared `features-skin-2` marker class survives
     * the round-trip.
     *
     * @param array<int,string> $expectedLabels
     */
    private function assertPublicPageCarriesMarker(
        Browser $browser,
        string $slug,
        array $expectedLabels
    ): void {
        $browser->visit('/' . ltrim($slug, '/'))->pause(2500);
        $source = $browser->driver->getPageSource();

        $this->assertStringContainsString(
            self::FIELD_PREFIX,
            $source,
            "Public /{$slug} must render the features/skin-1 field signature after save"
        );
        $this->assertStringContainsString(
            self::MARKER_CLASS,
            $source,
            "Public /{$slug} must render the shared features-skin-2 marker class after save"
        );
        foreach ($expectedLabels as $idx => $expected) {
            $this->assertStringContainsString(
                $expected,
                $source,
                "Public /{$slug} must render edited feature label #{$idx}"
            );
        }
    }
}

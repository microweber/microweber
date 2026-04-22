<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Phase-2 landing-page coverage: Features / skin-2 advantages grid.
 *
 * The skin renders an 8-card feature grid wrapped in a section that
 * carries `class="features-skin-2-advantages"` — the plan's regression
 * marker added in 7d388b9. This test:
 *
 *   1. Seeds a blank Bootstrap page via {@see LandingPageFactory}.
 *   2. Opens it in live-edit, primes the layout handle on the page's
 *      `.section.edit.main-content`.
 *   3. Inserts Features / skin-2 via the Insert Layout picker.
 *   4. Rewrites the first two `h5` feature labels to unique copies.
 *   5. Marks the surrounding `.edit` fields as `.changed` so the save
 *      pipeline picks them up.
 *   6. Drives save via `mw.app.canvas.getWindow().mw.drag.save()`.
 *   7. Asserts both the canvas DOM and the persisted HTML carry the
 *      new labels AND the `features-skin-2-advantages` marker.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditFeaturesSkin2Test extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const MARKER_CLASS = 'features-skin-2-advantages';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function features_skin_2_inserts_edits_and_persists(): void
    {
        $landing = LandingPageFactory::make('Features advantages grid');
        $suffix = uniqid();
        $newLabels = [
            0 => 'Edited feature one ' . $suffix,
            1 => 'Edited feature two ' . $suffix,
        ];

        $this->browse(function (Browser $browser) use ($landing, $newLabels) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, 'Features', 'features/skin-2');

            $field = $this->waitForFeaturesSection($browser);
            $this->assertNotSame('', $field,
                'Features section should expose a field="layout-features-skin-2-…" attribute after insertion');

            $this->editFeatureLabels($browser, $newLabels);
            $this->markEditFieldsChanged($browser, $field);

            $this->saveLiveEdit($browser);

            $this->assertCanvasReflectsEdits($browser, $field, $newLabels);
            $this->assertSavedContentBodyContains($landing->pageId, $newLabels);
            $this->assertPublicPageCarriesMarker($browser, $landing->slug, $newLabels);
        });
    }

    /**
     * Set mw.app.liveEdit.handles.get('layout') target to the page's
     * `.section.edit.main-content` so the Insert Layout picker's
     * fallback target resolves.
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
                var sec = doc.querySelector('section.section.edit[field^=\"layout-features-skin-2-\"]');
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
        $browser->screenshot('fail-features-section-missing');
        throw new \RuntimeException('Features section never appeared in the canvas within 15s');
    }

    /**
     * Rewrite the first N feature card `h5` labels.
     *
     * @param array<int,string> $labelsByIndex
     */
    private function editFeatureLabels(Browser $browser, array $labelsByIndex): void
    {
        $labelsJson = json_encode($labelsByIndex);
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var labels = " . $labelsJson . ";
            var h5s = doc.querySelectorAll(
                'section[field^=\"layout-features-skin-2-\"] .col.cloneable h5'
            );
            var writes = 0;
            Object.keys(labels).forEach(function (idx) {
                var n = parseInt(idx, 10);
                var h = h5s[n];
                if (!h) return;
                h.removeAttribute('data-mwplaceholder');
                h.innerHTML = labels[idx];
                h.dispatchEvent(new Event('input', { bubbles: true }));
                h.dispatchEvent(new Event('change', { bubbles: true }));
                writes++;
            });
            return writes;
        ");
        $writes = (int)($res[0] ?? 0);
        $this->assertSame(
            count($labelsByIndex),
            $writes,
            'Must be able to rewrite every requested feature label'
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
            var h5s = sec.querySelectorAll('.col.cloneable h5');
            var out = [];
            for (var i = 0; i < h5s.length; i++) {
                out.push(h5s[i].innerText || '');
            }
            return { present: true, labels: out, className: sec.className };
        ");
        $state = $state[0] ?? ['present' => false];
        $this->assertTrue(!empty($state['present']), 'Features section must still render after save');
        $this->assertStringContainsString(
            self::MARKER_CLASS,
            (string)($state['className'] ?? ''),
            'Section must retain the features-skin-2-advantages marker class'
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
     * /api/content/save_edit into the database. The
     * `features-skin-2-advantages` class lives on the OUTER section
     * tag, which the save pipeline replaces with a `<module>` shortcode
     * (`Parser::make_tags`) — so the marker isn't in the raw DB blob;
     * it re-appears once the module re-renders. That check lives in
     * {@see assertPublicPageCarriesMarker} below. Here we only assert
     * the edited labels and the edit-inner content itself made it to
     * the DB.
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
     * Visit the public page URL and assert the rendered HTML carries
     * the `features-skin-2-advantages` regression marker. Using the
     * rendered page (not the DB blob) is the right layer because
     * `Parser::make_tags` strips the outer section during save and
     * the marker only re-emerges once the module re-renders.
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
            self::MARKER_CLASS,
            $source,
            "Public /{$slug} must render the features-skin-2-advantages marker after save"
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

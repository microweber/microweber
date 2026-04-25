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
 * Phase-2 landing-page coverage: Titles / skin-1 intro block.
 *
 * Mirrors {@see LiveEditJumbotronSkin1Test} for the titles skin so
 * the plan's "inline heading edit persists" invariant has a dedicated
 * guard:
 *
 *   1. Seed a blank Bootstrap page via {@see LandingPageFactory}.
 *   2. Open it in live-edit; prime the layout handle on the page's
 *      `.section.edit.main-content`.
 *   3. Open the Insert Layout picker (category: Titles, skin:
 *      titles/skin-1) and insert the block.
 *   4. Rewrite the H1 ("Why choose us") and the `.lead`
 *      description paragraph to unique copies.
 *   5. Mark the outer titles section and its main-content parent as
 *      `.edit.changed` so the save pipeline collects them.
 *   6. Drive save via `mw.drag.save()` from inside the canvas window.
 *   7. Assert the canvas DOM still reflects the edits and that the
 *      new copy is readable from at least one of `content.content`,
 *      `content.content_body`, or `content_fields`.
 *
 * The titles skin has two editable surfaces:
 *   - The outer `section[field="layout-titles-skin-1-…"]` (H1 lives
 *     here — in the template the H1 renders `content_title()`, but
 *     once the section is `.changed` its full inner HTML — including
 *     our rewritten H1 text — is what the save pipeline captures).
 *   - The `<p class="lead edit" field="layout-titles-skin-1-description-…">`
 *     which has its own `field` attribute, so it lands in a dedicated
 *     `content_fields` row keyed by that field name.
 *
 * We exercise both so the assertion isn't brittle to which branch of
 * the save pipeline actually picks up the blob.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditTitlesSkin1Test extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinBladeExists;
    use AssertsSkinTagPersisted;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const NEW_H1 = 'Why choose us';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function titles_skin_1_inserts_edits_and_persists(): void
    {
        $this->assertSkinBladeExists('titles/skin-1');

        $landing = LandingPageFactory::make('Titles intro');
        $newDescription = 'Trusted landing copy ' . uniqid();

        $this->browse(function (Browser $browser) use ($landing, $newDescription) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, 'Titles', 'titles/skin-1');

            $field = $this->waitForTitlesSection($browser);
            $this->assertNotSame('', $field,
                'Titles section should expose a field="layout-titles-skin-1-…" attribute after insertion');

            $this->editTitlesH1($browser, self::NEW_H1);
            $this->editTitlesDescription($browser, $newDescription);
            $this->markEditFieldsChanged($browser, $field);

            $this->saveLiveEdit($browser);

            $this->assertSkinTagPersisted($landing->pageId, 'titles/skin-1');

            $this->assertCanvasReflectsEdits($browser, $field, self::NEW_H1, $newDescription);
            $this->assertSavedContentBodyContains($landing->pageId, self::NEW_H1, $newDescription);
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

    /**
     * Poll the canvas DOM until the newly-inserted titles section
     * appears. Returns its `field` attribute so later assertions can
     * reference the rel_id/field pair.
     */
    private function waitForTitlesSection(Browser $browser): string
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sec = doc.querySelector('section.section.edit[field^=\"layout-titles-skin-1-\"]');
                if (!sec) return '';
                return sec.getAttribute('field') || '';
            ");
            $fieldAttr = (string)($res[0] ?? '');
            if ($fieldAttr !== '') {
                $browser->pause(600); // let child modules (background/spacer) settle
                return $fieldAttr;
            }
            $browser->pause(500);
        }
        $browser->screenshot('fail-titles-section-missing');
        throw new \RuntimeException('Titles section never appeared in the canvas within 15s');
    }

    private function editTitlesH1(Browser $browser, string $newText): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var h1 = doc.querySelector(
                'section[field^=\"layout-titles-skin-1-\"] h1'
            );
            if (!h1) return 'NO_H1';
            h1.removeAttribute('data-mwplaceholder');
            h1.innerHTML = " . json_encode($newText) . ";
            h1.dispatchEvent(new Event('input', { bubbles: true }));
            h1.dispatchEvent(new Event('change', { bubbles: true }));
            return 'OK';
        ");
        $this->assertSame('OK', $res[0] ?? 'UNKNOWN', 'Must be able to rewrite the titles H1');
    }

    /**
     * Rewrite the `.lead` description paragraph. This paragraph has
     * its own `field="layout-titles-skin-1-description-…"` attribute,
     * so it also needs to be marked `.edit.changed` — the outer
     * section's `.changed` flag does not cascade to it.
     */
    private function editTitlesDescription(Browser $browser, string $newText): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var lead = doc.querySelector(
                'section[field^=\"layout-titles-skin-1-\"] p.lead.edit'
            );
            if (!lead) return 'NO_LEAD';
            lead.removeAttribute('data-mwplaceholder');
            lead.innerHTML = " . json_encode($newText) . ";
            lead.classList.add('changed');
            lead.dispatchEvent(new Event('input', { bubbles: true }));
            lead.dispatchEvent(new Event('change', { bubbles: true }));
            return 'OK';
        ");
        $this->assertSame('OK', $res[0] ?? 'UNKNOWN', 'Must be able to rewrite the .lead description');
    }

    /**
     * Tag the titles section and its main-content parent with
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

    private function assertCanvasReflectsEdits(
        Browser $browser,
        string $field,
        string $expectedH1,
        string $expectedDescription
    ): void {
        $state = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sec = doc.querySelector('section[field=' + JSON.stringify(" . json_encode($field) . ") + ']');
            if (!sec) return { present: false };
            return {
                present: true,
                h1: (sec.querySelector('h1') || {}).innerText || '',
                lead: (sec.querySelector('p.lead') || {}).innerText || ''
            };
        ");
        $state = $state[0] ?? ['present' => false];
        $this->assertTrue(!empty($state['present']), 'Titles section must still render after save');
        $this->assertSame($expectedH1, trim((string)$state['h1']),
            'Canvas H1 should match the edited heading copy');
        $this->assertStringContainsString($expectedDescription, (string)$state['lead'],
            'Canvas .lead should contain the edited description text');
    }

    /**
     * Assert that the edited titles copy round-tripped through
     * /api/content/save_edit into the database. See the jumbotron
     * sibling test for the reasoning behind the multi-column
     * haystack — depending on which `.edit` wrapper caught the
     * `.changed` flag first, the blob may land in `content.content`,
     * `content.content_body`, or `content_fields`.
     */
    private function assertSavedContentBodyContains(
        int $pageId,
        string $expectedH1,
        string $expectedDescription
    ): void {
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
        $this->assertStringContainsString(
            $expectedH1,
            $haystack,
            'Persisted content must carry the new H1 heading text'
        );
        $this->assertStringContainsString(
            $expectedDescription,
            $haystack,
            'Persisted content must carry the new .lead description'
        );
        $this->assertStringContainsString(
            'layout-titles-skin-1-',
            $haystack,
            'Persisted content must be the titles skin-1 markup (field marker)'
        );
    }
}

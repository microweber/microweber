<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinBladeExists;
use Tests\Browser\Traits\AssertsSkinPublicSignatureRendered;
use Tests\Browser\Traits\AssertsSkinTagPersisted;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Phase-2 landing-page coverage: Jumbotron / skin-1 hero section.
 *
 * Exercises the end-to-end path every subsequent landing-page test
 * depends on:
 *
 *   1. Seed a blank Bootstrap page via {@see LandingPageFactory}.
 *   2. Open it in live-edit.
 *   3. Target the page's `.section.edit.main-content` so the layout
 *      handle has something to act on, then go through the Insert
 *      Layout picker (category: Jumbotron, skin: jumbotron/skin-1).
 *   4. Rewrite the hero H1 to "Microweber Landing" and the CTA
 *      button copy to a unique value.
 *   5. Mark the surrounding `.edit` fields as `.changed` so the save
 *      pipeline picks them up (our synthetic innerHTML writes don't
 *      trip the contentEditable input listener).
 *   6. Drive save via `mw.app.canvas.getWindow().mw.drag.save()`.
 *   7. Assert both the live canvas DOM and the DB-backed
 *      `content_fields` row carry the new copy.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditJumbotronSkin1Test extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinBladeExists;
    use AssertsSkinPublicSignatureRendered;
    use AssertsSkinTagPersisted;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const NEW_H1 = 'Microweber Landing';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function jumbotron_skin_1_inserts_edits_and_persists(): void
    {
        $this->assertSkinBladeExists('jumbotron/skin-1');

        $landing = LandingPageFactory::make('Jumbotron hero');
        $newCta = 'Get started ' . uniqid();

        $this->browse(function (Browser $browser) use ($landing, $newCta) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, 'Jumbotron', 'jumbotron/skin-1');

            $field = $this->waitForJumbotronSection($browser);
            $this->assertNotSame('', $field,
                'Jumbotron section should expose a field="layout-jumbotron-skin-1-…" attribute after insertion');

            $this->editJumbotronH1($browser, self::NEW_H1);
            $this->editJumbotronCtaText($browser, $newCta);
            $this->markEditFieldsChanged($browser, $field);

            $this->saveLiveEdit($browser);

            $this->assertSkinTagPersisted($landing->pageId, 'jumbotron/skin-1');

            $this->assertCanvasReflectsEdits($browser, $field, self::NEW_H1, $newCta);
            $this->assertSavedContentBodyContains($landing->pageId, self::NEW_H1, $newCta);

            // Public-render gate runs LAST — it navigates away from the
            // canvas, so any canvas-touching call after this would crash.
            $this->assertSkinPublicSignatureRendered(
                $browser,
                $landing->slug,
                ['field="layout-jumbotron-skin-1-', 'mw-layout-dark-background'],
            );
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
            window.__jumboInsertTargetId = target.id || null;
            return 'OK';
        ");

        $this->assertSame('OK', $primed[0] ?? 'UNKNOWN',
            'Layout handle must resolve to the clean-layout main-content section');
    }

    /**
     * Poll the canvas DOM until the newly-inserted jumbotron section
     * appears. Returns its `field` attribute so later assertions can
     * reference the rel_id/field pair.
     */
    private function waitForJumbotronSection(Browser $browser): string
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sec = doc.querySelector('section.section.edit[field^=\"layout-jumbotron-skin-1-\"]');
                if (!sec) return '';
                return sec.getAttribute('field') || '';
            ");
            $field = (string)($res[0] ?? '');
            if ($field !== '') {
                $browser->pause(800); // let sub-modules (btn) finish rendering
                return $field;
            }
            $browser->pause(500);
        }
        $browser->screenshot('fail-jumbo-section-missing');
        throw new \RuntimeException('Jumbotron section never appeared in the canvas within 15s');
    }

    private function editJumbotronH1(Browser $browser, string $newText): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var h1 = doc.querySelector(
                'section[field^=\"layout-jumbotron-skin-1-\"] h1.header-section-title'
            );
            if (!h1) return 'NO_H1';
            h1.removeAttribute('data-mwplaceholder');
            h1.innerHTML = " . json_encode($newText) . ";
            h1.dispatchEvent(new Event('input', { bubbles: true }));
            h1.dispatchEvent(new Event('change', { bubbles: true }));
            return 'OK';
        ");
        $this->assertSame('OK', $res[0] ?? 'UNKNOWN', 'Must be able to rewrite the jumbotron H1');
    }

    /**
     * Change the CTA copy. The rendered `<a class="btn">` sits inside
     * a `<module class="module module-btn" button_text="...">` wrapper;
     * `Parser::make_tags` re-shortcodes the wrapper on save and drops
     * the anchor's text back to whatever `button_text` attribute
     * carries. So we update BOTH the visible anchor (for the live DOM
     * assertion) AND the wrapper's `button_text` (so the save pipeline
     * persists the new copy).
     */
    private function editJumbotronCtaText(Browser $browser, string $newText): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var cta = doc.querySelector(
                'section[field^=\"layout-jumbotron-skin-1-\"] a.btn, '
                + 'section[field^=\"layout-jumbotron-skin-1-\"] button.btn'
            );
            if (!cta) return 'NO_CTA';
            cta.innerHTML = " . json_encode($newText) . ";
            cta.dispatchEvent(new Event('input', { bubbles: true }));
            cta.dispatchEvent(new Event('change', { bubbles: true }));

            var wrapper = cta.closest('.module.module-btn') || cta.closest('module');
            if (wrapper) {
                wrapper.setAttribute('button_text', " . json_encode($newText) . ");
            }
            return wrapper ? 'OK' : 'NO_WRAPPER';
        ");
        $outcome = $res[0] ?? 'UNKNOWN';
        $this->assertSame('OK', $outcome,
            'Must be able to rewrite the CTA button copy (anchor + module wrapper button_text)');
    }

    /**
     * Tag the jumbotron section and its main-content parent with
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
        string $expectedCta
    ): void {
        $state = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sec = doc.querySelector('section[field=' + JSON.stringify(" . json_encode($field) . ") + ']');
            if (!sec) return { present: false };
            return {
                present: true,
                h1: (sec.querySelector('h1.header-section-title') || {}).innerText || '',
                cta: (sec.querySelector('.btn') || {}).innerText || ''
            };
        ");
        $state = $state[0] ?? ['present' => false];
        $this->assertTrue(!empty($state['present']), 'Jumbotron must still render after save');
        $this->assertSame($expectedH1, trim((string)$state['h1']),
            'Canvas H1 should match the edited copy');
        $this->assertStringContainsString($expectedCta, (string)$state['cta'],
            'Canvas CTA should contain the edited button text');
    }

    /**
     * Assert that the edited jumbotron copy round-tripped through
     * /api/content/save_edit into the database.
     *
     * The outer `.section.edit.main-content[field="content"]` wrapper
     * writes to `content.content` (the save pipeline routes `field == 'content'`
     * and `field == 'content_body'` directly into the content row instead
     * of `content_fields`). The inserted jumbotron section carries
     * `rel="module"` and its own `field="layout-jumbotron-skin-1-…"`,
     * which lands in `content_fields` with `rel_type='module'`. We
     * concatenate every candidate destination so the assertion isn't
     * brittle to which branch of the pipeline actually picked up the
     * blob.
     */
    private function assertSavedContentBodyContains(
        int $pageId,
        string $expectedH1,
        string $expectedCta
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
            'Persisted content (content/content_body/content_fields) must carry the new H1 text'
        );
        $this->assertStringContainsString(
            $expectedCta,
            $haystack,
            'Persisted content (content/content_body/content_fields) must carry the new CTA text'
        );
        $this->assertStringContainsString(
            'header-section-title',
            $haystack,
            'Persisted content must be the jumbotron markup (header-section-title marker)'
        );
    }
}

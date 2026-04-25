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
 * Phase-2 landing-page coverage: Content / skin-1 story block.
 *
 * The Bootstrap template's Content / skin-1 layout renders a centered
 * story block with an icon, an `<h3>` heading, and a `<p>` body. This
 * test covers the plan's "edit paragraph text" invariant end-to-end:
 *
 *   1. Seed a blank Bootstrap page via {@see LandingPageFactory}.
 *   2. Open it in live-edit; prime the layout handle on the page's
 *      `.section.edit.main-content`.
 *   3. Insert Content / skin-1 via the Insert Layout picker.
 *   4. Rewrite the body `<p>` to a unique copy (the paragraph the
 *      task asks us to edit).
 *   5. Mark the section + its main-content parent `.edit.changed`.
 *   6. Drive save via `mw.app.canvas.getWindow().mw.drag.save()`.
 *   7. Assert both the canvas DOM and the persisted HTML carry the
 *      new paragraph text.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditContentSkin1Test extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinBladeExists;
    use AssertsSkinTagPersisted;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function content_skin_1_inserts_edits_and_persists(): void
    {
        $this->assertSkinBladeExists('content/skin-1');

        $landing = LandingPageFactory::make('Content story block');
        $newParagraph = 'Our edited story paragraph ' . uniqid();

        $this->browse(function (Browser $browser) use ($landing, $newParagraph) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, 'Content', 'content/skin-1');

            $field = $this->waitForContentSection($browser);
            $this->assertNotSame('', $field,
                'Content section should expose a field="layout-content-skin-1-…" attribute after insertion');

            $this->editContentParagraph($browser, $newParagraph);
            $this->markEditFieldsChanged($browser, $field);

            $this->saveLiveEdit($browser);

            $this->assertSkinTagPersisted($landing->pageId, 'content/skin-1');

            $this->assertCanvasReflectsEdits($browser, $field, $newParagraph);
            $this->assertSavedContentBodyContains($landing->pageId, $newParagraph);
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

    private function waitForContentSection(Browser $browser): string
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sec = doc.querySelector('section.section.edit[field^=\"layout-content-skin-1-\"]');
                if (!sec) return '';
                return sec.getAttribute('field') || '';
            ");
            $fieldAttr = (string)($res[0] ?? '');
            if ($fieldAttr !== '') {
                $browser->pause(600);
                return $fieldAttr;
            }
            $browser->pause(500);
        }
        $browser->screenshot('fail-content-section-missing');
        throw new \RuntimeException('Content section never appeared in the canvas within 15s');
    }

    /**
     * Rewrite the body paragraph. In content/skin-1 the paragraph is
     * a direct child of `.regular-mode`, sitting next to the H3; we
     * target the one whose `data-mwplaceholder="Enter text here"` so
     * we don't accidentally catch an inner `<p>` from a nested module.
     */
    private function editContentParagraph(Browser $browser, string $newText): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var p = doc.querySelector(
                'section[field^=\"layout-content-skin-1-\"] .regular-mode p[data-mwplaceholder=\"Enter text here\"]'
            ) || doc.querySelector(
                'section[field^=\"layout-content-skin-1-\"] .regular-mode p'
            );
            if (!p) return 'NO_P';
            p.removeAttribute('data-mwplaceholder');
            p.innerHTML = " . json_encode($newText) . ";
            p.dispatchEvent(new Event('input', { bubbles: true }));
            p.dispatchEvent(new Event('change', { bubbles: true }));
            return 'OK';
        ");
        $this->assertSame('OK', $res[0] ?? 'UNKNOWN', 'Must be able to rewrite the body paragraph');
    }

    /**
     * Tag the content section and its main-content parent with
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
        string $expectedParagraph
    ): void {
        $state = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sec = doc.querySelector('section[field=' + JSON.stringify(" . json_encode($field) . ") + ']');
            if (!sec) return { present: false };
            var p = sec.querySelector('.regular-mode p');
            return {
                present: true,
                paragraph: p ? (p.innerText || '') : ''
            };
        ");
        $state = $state[0] ?? ['present' => false];
        $this->assertTrue(!empty($state['present']), 'Content section must still render after save');
        $this->assertStringContainsString(
            $expectedParagraph,
            (string)$state['paragraph'],
            'Canvas paragraph should contain the edited copy'
        );
    }

    /**
     * Assert that the edited paragraph round-tripped through
     * /api/content/save_edit into the database. See the jumbotron /
     * titles siblings for why we search across content.content,
     * content.content_body, and content_fields — the save pipeline
     * routes different edits to different columns depending on which
     * `.edit.changed` wrapper caught the flag first.
     */
    private function assertSavedContentBodyContains(int $pageId, string $expectedParagraph): void
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
        $this->assertStringContainsString(
            $expectedParagraph,
            $haystack,
            'Persisted content must carry the edited paragraph text'
        );
        // The outer `section[field="layout-content-skin-1-…"]` is
        // replaced by a `<module>` shortcode during save, but the
        // shortcode keeps `template="content/skin-1"` — that's the
        // stable marker for "this was content skin-1" in the DB.
        $this->assertStringContainsString(
            'content/skin-1',
            $haystack,
            'Persisted content must carry the content/skin-1 template marker'
        );
    }
}

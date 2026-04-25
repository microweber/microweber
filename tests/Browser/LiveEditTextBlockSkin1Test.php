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
 * Plan B.2 fourth bullet — Text-block / skin-1 per-skin Dusk coverage.
 *
 * `text-block/skin-1` is the simplest "content skin" the Bootstrap
 * template ships: a single centred column with one heading
 * (`<h5 data-mwplaceholder="Enter title here">Pictures In The Sky</h5>`),
 * one body paragraph
 * (`<p data-mwplaceholder="Enter text here">The $79 iWork '08…</p>`),
 * and a nested testimonials module slot. The Plan B.2 task line for
 * this skin reads "insert, assert rendered `<p>`/heading markers are
 * in the DOM" — so this test mirrors the FeaturesSkin1 / PricingSkin1
 * shape but exercises the heading + body pair instead of a list of
 * cards or a price table.
 *
 * Note on factory drift: `text-block/skin-1` is not (yet) listed in
 * {@see \Tests\Browser\Factories\ColorPaletteSkinMatrixFactory::TARGET_SKINS}
 * — Plan D's matrix only covers the layout skins shown on the
 * demo home-page scroll. This per-skin test stands on its own and
 * does not consult `pendingSkins()` (the blade exists; this is the
 * full-cycle path, not the pending-stub path).
 *
 * Plan B.3 contract:
 *   - Skin blade file existence checked before insert (fails early
 *     if Templates/Bootstrap/.../text-block/skin-1.blade.php is gone).
 *   - Insert persists `<module type="layouts" template="text-block/skin-1">`
 *     to content.content.
 *   - Public render carries the section with `field=layout-text-block-skin-1-*`
 *     and the edited `<h5>` + `<p>` markers.
 *   - No JS console error fires during insert OR public render.
 *
 * Skin specifics encoded here:
 *   - field attribute: `layout-text-block-skin-1-{id}`.
 *   - heading element: `<h5 data-mwplaceholder="Enter title here">`
 *     (skin-1 uses h5; sibling content/skin-1 uses h2 — these are
 *     genuinely different blades).
 *   - body element:    `<p data-mwplaceholder="Enter text here">`.
 *   - Insert-Layout category label: `Text block` (with a space, not
 *     a hyphen — matches the blade's `categories: Text block` header).
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditTextBlockSkin1Test extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const SKIN_TAG = 'text-block/skin-1';
    private const SKIN_BLADE_PATH = 'Templates/Bootstrap/resources/views/modules/layouts/templates/text-block/skin-1.blade.php';
    private const FIELD_PREFIX = 'layout-text-block-skin-1-';
    private const INSERT_CATEGORY = 'Text block';
    private const ORIGINAL_HEADING = 'Pictures In The Sky';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    #[Test]
    public function text_block_skin_1_inserts_edits_and_persists(): void
    {
        // Plan B.3 first-bullet gate: skin blade must exist before
        // any insert path makes sense.
        $this->assertFileExists(
            base_path(self::SKIN_BLADE_PATH),
            'text-block/skin-1 blade file must exist on disk before this test can drive the live-edit pipeline'
        );

        $landing = LandingPageFactory::make('Text-block centred copy skin 1');
        $suffix = uniqid();
        $newHeading = 'Edited skin-1 text-block heading ' . $suffix;
        $newBody = 'Edited skin-1 text-block body copy ' . $suffix
            . ' — survived the live-edit save round-trip.';

        $this->browse(function (Browser $browser) use ($landing, $newHeading, $newBody) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, self::INSERT_CATEGORY, self::SKIN_TAG);

            $field = $this->waitForTextBlockSection($browser);
            $this->assertNotSame('', $field, sprintf(
                'Text-block section should expose a field="%s…" attribute after insertion',
                self::FIELD_PREFIX,
            ));

            $this->editTextBlockCopy($browser, $newHeading, $newBody);
            $this->markEditFieldsChanged($browser, $field);

            $this->saveLiveEdit($browser);

            $this->assertCanvasReflectsEdits($browser, $field, $newHeading, $newBody);
            $this->assertSavedContentBodyContains($landing->pageId, $newHeading, $newBody);
            $this->assertPublicPageCarriesMarker($browser, $landing->slug, $newHeading, $newBody);
        });
    }

    /**
     * Set mw.app.liveEdit.handles.get('layout') target to the page's
     * `.section.edit.main-content` so the Insert Layout picker's
     * fallback target resolves. Same shape as the sibling per-skin
     * tests — the priming logic is layout-agnostic.
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

    private function waitForTextBlockSection(Browser $browser): string
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
        $browser->screenshot('fail-text-block-skin-1-section-missing');
        throw new \RuntimeException('Text-block skin-1 section never appeared in the canvas within 15s');
    }

    /**
     * Rewrite the skin's heading (h5) and body paragraph (p) inside
     * the inserted section. Strips `data-mwplaceholder` first so
     * the editor doesn't restore the placeholder text on first focus.
     */
    private function editTextBlockCopy(Browser $browser, string $heading, string $body): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sec = doc.querySelector('section[field^=\"" . self::FIELD_PREFIX . "\"]');
            if (!sec) return 'NO_SEC';

            var h = sec.querySelector('h5');
            if (!h) return 'NO_HEADING';
            h.removeAttribute('data-mwplaceholder');
            h.innerHTML = " . json_encode($heading) . ";
            h.dispatchEvent(new Event('input', { bubbles: true }));
            h.dispatchEvent(new Event('change', { bubbles: true }));

            var p = sec.querySelector('p');
            if (!p) return 'NO_BODY';
            p.removeAttribute('data-mwplaceholder');
            p.innerHTML = " . json_encode($body) . ";
            p.dispatchEvent(new Event('input', { bubbles: true }));
            p.dispatchEvent(new Event('change', { bubbles: true }));

            return 'OK';
        ");
        $this->assertSame('OK', $res[0] ?? 'UNKNOWN',
            'Must be able to rewrite the text-block heading + body copy');
    }

    /**
     * Tag the text-block section and its main-content parent with
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
        string $expectedHeading,
        string $expectedBody
    ): void {
        $state = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sec = doc.querySelector('section[field=' + JSON.stringify(" . json_encode($field) . ") + ']');
            if (!sec) return { present: false };
            var h = sec.querySelector('h5');
            var p = sec.querySelector('p');
            return {
                present: true,
                heading: h ? (h.innerText || '') : '',
                body: p ? (p.innerText || '') : ''
            };
        ");
        $state = $state[0] ?? ['present' => false];
        $this->assertTrue(!empty($state['present']), 'Text-block section must still render after save');
        $this->assertSame(
            $expectedHeading,
            trim((string)($state['heading'] ?? '')),
            'Canvas heading should match the edited copy'
        );
        $this->assertStringContainsString(
            $expectedBody,
            (string)($state['body'] ?? ''),
            'Canvas body paragraph should contain the edited copy'
        );
    }

    /**
     * Assert that the edited heading + body round-tripped through
     * /api/content/save_edit into the database. Same shape +
     * caveat as the sibling per-skin assertions: the outer section
     * is replaced by a `<module>` shortcode at save time, so we
     * look across content + content_fields for the persisted copy.
     * We also assert the original placeholder heading is gone — a
     * clean signal the edit landed on the heading element rather
     * than appended somewhere else in the section.
     */
    private function assertSavedContentBodyContains(
        int $pageId,
        string $expectedHeading,
        string $expectedBody
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
            $expectedHeading,
            $haystack,
            'Persisted content must carry the edited text-block heading'
        );
        $this->assertStringContainsString(
            $expectedBody,
            $haystack,
            'Persisted content must carry the edited text-block body copy'
        );
        $this->assertStringNotContainsString(
            self::ORIGINAL_HEADING,
            $haystack,
            'Persisted content must not still carry the pre-edit placeholder heading'
        );
        // The outer `section[field="layout-text-block-skin-1-…"]` is
        // replaced by a `<module>` shortcode during save, but the
        // shortcode keeps `template="text-block/skin-1"` — that's
        // the stable marker for "this was text-block skin-1" in the DB.
        $this->assertStringContainsString(
            self::SKIN_TAG,
            $haystack,
            'Persisted content must carry the text-block/skin-1 template marker'
        );
    }

    /**
     * Visit the public page URL and assert the rendered HTML
     * carries the skin's signature: the `field=layout-text-block-skin-1-*`
     * attribute the blade emits on the outer <section>, the edited
     * heading and body copy. The Plan B.2 task line for this skin
     * specifically calls out asserting the rendered `<p>`/heading
     * markers — that's exactly what these three assertions do.
     */
    private function assertPublicPageCarriesMarker(
        Browser $browser,
        string $slug,
        string $expectedHeading,
        string $expectedBody
    ): void {
        $browser->visit('/' . ltrim($slug, '/'))->pause(2500);
        $source = $browser->driver->getPageSource();

        $this->assertStringContainsString(
            self::FIELD_PREFIX,
            $source,
            "Public /{$slug} must render the text-block/skin-1 field signature after save"
        );
        $this->assertStringContainsString(
            $expectedHeading,
            $source,
            "Public /{$slug} must render the edited text-block heading after save"
        );
        $this->assertStringContainsString(
            $expectedBody,
            $source,
            "Public /{$slug} must render the edited text-block body copy after save"
        );
    }
}

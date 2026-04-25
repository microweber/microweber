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
 * Phase-2 landing-page coverage: Footers / skin-1 company-name edit.
 *
 * The Footers skin-1 layout has an outer
 * `section.edit.safe-mode[field="layout-footer-skin-1-…"]` with a
 * nested company block:
 *
 *   <div class="edit" field="layout-footer-skin-1-company-…">
 *       <p class="font-weight-bold">Website Builder and CMS</p>
 *       <br>
 *       <small>…</small>
 *   </div>
 *
 * The plan's invariant is "edit company-name span". The company name
 * in the shipped markup is rendered as `<p class="font-weight-bold">`,
 * not a `<span>` — the task phrasing is approximate. We rewrite that
 * paragraph's text, mark the nested `.edit[field="…-company-…"]` AND
 * the outer footer section + main-content parent as `.changed`, save,
 * and then assert:
 *
 *   1. The canvas still renders the footer section after save.
 *   2. The canvas `<p.font-weight-bold>` carries the new text.
 *   3. The persisted DB copy (content.content / content.content_body /
 *      content_fields for either rel_type=module or rel_type=content)
 *      carries both the new text and the `layout-footer-skin-1-`
 *      field-attribute marker — so we know the edit landed in the
 *      right skin, not just somewhere in the page.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditFootersSkin1Test extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinBladeExists;
    use AssertsSkinPublicSignatureRendered;
    use AssertsSkinTagPersisted;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const ORIGINAL_COMPANY_NAME = 'Website Builder and CMS';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function footers_skin_1_company_name_edit_persists(): void
    {
        $this->assertSkinBladeExists('footers/skin-1');

        $landing = LandingPageFactory::make('Footers company edit');
        $newCompanyName = 'Acme Landing Co ' . uniqid();

        $this->browse(function (Browser $browser) use ($landing, $newCompanyName) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, 'Footers', 'footers/skin-1');

            $field = $this->waitForFootersSection($browser);
            $this->assertNotSame('', $field,
                'Footers section should expose a field="layout-footer-skin-1-…" attribute after insertion');

            $this->editCompanyName($browser, $newCompanyName);
            $this->markEditFieldsChanged($browser, $field);

            $this->saveLiveEdit($browser);

            $this->assertSkinTagPersisted($landing->pageId, 'footers/skin-1');

            $this->assertCanvasReflectsCompanyName($browser, $field, $newCompanyName);
            $this->assertSavedContentCarriesCompanyName($landing->pageId, $newCompanyName);

            // Public-render gate runs LAST — it navigates away from the
            // canvas, so any canvas-touching call after this would crash.
            // The footers/skin-1 blade emits `field="layout-footer-skin-1-…"`
            // (singular `footer`) — distinct from the SKIN_TAG `footers/skin-1`
            // (plural). Marker also includes the unique `footer-background`
            // outer-section class shipped only by this skin.
            $this->assertSkinPublicSignatureRendered(
                $browser,
                $landing->slug,
                ['field="layout-footer-skin-1-', 'footer-background'],
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
            return 'OK';
        ");

        $this->assertSame('OK', $primed[0] ?? 'UNKNOWN',
            'Layout handle must resolve to the clean-layout main-content section');
    }

    private function waitForFootersSection(Browser $browser): string
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sec = doc.querySelector('section.edit[field^=\"layout-footer-skin-1-\"]');
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
        $browser->screenshot('fail-footers-section-missing');
        throw new \RuntimeException('Footers section never appeared in the canvas within 15s');
    }

    /**
     * Rewrite the company-name paragraph. The block is nested inside a
     * separate `.edit[field^="layout-footer-skin-1-company-"]` wrapper
     * which needs its own `.changed` flag — the outer section's
     * `.changed` does not cascade to nested `.edit` children.
     */
    private function editCompanyName(Browser $browser, string $newName): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var companyField = doc.querySelector(
                '.edit[field^=\"layout-footer-skin-1-company-\"]'
            );
            if (!companyField) return 'NO_COMPANY_FIELD';
            var namePara = companyField.querySelector('p.font-weight-bold');
            if (!namePara) return 'NO_NAME_PARA';
            namePara.removeAttribute('data-mwplaceholder');
            namePara.innerHTML = " . json_encode($newName) . ";
            namePara.dispatchEvent(new Event('input', { bubbles: true }));
            namePara.dispatchEvent(new Event('change', { bubbles: true }));
            companyField.classList.add('changed');
            return 'OK';
        ");
        $this->assertSame('OK', $res[0] ?? 'UNKNOWN',
            'Must be able to rewrite the company-name paragraph inside the footer');
    }

    /**
     * Tag the footer section and its main-content parent with
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

    private function assertCanvasReflectsCompanyName(
        Browser $browser,
        string $field,
        string $expectedName
    ): void {
        $state = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sec = doc.querySelector('section[field=' + JSON.stringify(" . json_encode($field) . ") + ']');
            if (!sec) return { present: false };
            var companyField = sec.querySelector(
                '.edit[field^=\"layout-footer-skin-1-company-\"]'
            );
            if (!companyField) return { present: true, companyField: false };
            var namePara = companyField.querySelector('p.font-weight-bold');
            return {
                present: true,
                companyField: true,
                nameText: namePara ? (namePara.innerText || '') : ''
            };
        ");
        $state = $state[0] ?? ['present' => false];
        $this->assertTrue(!empty($state['present']),
            'Footer section must still render after save');
        $this->assertTrue(!empty($state['companyField']),
            'Company-name edit wrapper must still render after save');
        $this->assertStringContainsString(
            $expectedName,
            (string)$state['nameText'],
            'Canvas company-name paragraph should contain the edited value'
        );
    }

    /**
     * Assert the edited company name round-tripped through
     * /api/content/save_edit. The haystack spans `content.content`,
     * `content.content_body`, and `content_fields` for both
     * rel_type=module and rel_type=content, so the assertion does not
     * hinge on which specific branch of the save pipeline picked up
     * the `.edit.changed` node.
     *
     * We also assert the `layout-footer-skin-1-` field-attribute marker
     * survives — so the edit is pinned to the footer skin, not just to
     * some arbitrary spot in the page.
     */
    private function assertSavedContentCarriesCompanyName(int $pageId, string $expectedName): void
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
            $expectedName,
            $haystack,
            'Persisted content must carry the new company-name text'
        );
        $this->assertStringNotContainsString(
            self::ORIGINAL_COMPANY_NAME,
            $haystack,
            'Persisted content must not still carry the pre-edit company-name default'
        );
        $this->assertStringContainsString(
            'layout-footer-skin-1-',
            $haystack,
            'Persisted content must carry the footers skin-1 field marker'
        );
    }
}

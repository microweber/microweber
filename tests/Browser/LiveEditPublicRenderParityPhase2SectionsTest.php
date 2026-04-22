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
 * Phase-5 public-render parity: after building the full Phase-2
 * landing page in live-edit and saving, visiting the page's *public*
 * URL must yield HTML that contains every section's marker — proving
 * the save pipeline and the public render path preserve each skin's
 * outer wrapper unchanged.
 *
 * Task framing (TODO.md Phase 5):
 *   "Visit the page's public URL (not admin); assert every section
 *    marker from Phase 2 appears in the public HTML".
 *
 * Why this test belongs next to the existing full-landing reload test:
 *   - {@see \Tests\Browser\LiveEditFullLandingPageTest} covers the
 *     admin-side reload invariant: after reload, the live-edit canvas
 *     shows all 9 sections in order. That is necessary but not
 *     sufficient — a bug in Parser::_replace_editable_fields() or the
 *     module-shortcode expander could re-render admin (via a different
 *     code path that tolerates `<inner-edit-tag>`) while breaking the
 *     public render.
 *   - The public render path is different: no IN_EDIT flag, no
 *     live-edit chrome. The wrapper `<section>` stored by
 *     {@see \MicroweberPackages\App\Utils\Parser::make_tags} is what
 *     the guest visitor sees. Each skin's blade class marker (and
 *     field="layout-…-<id>" attribute) must survive the round trip.
 *
 * Phase-2 sections, in insertion order (mirrors
 * {@see \Tests\Browser\LiveEditFullLandingPageTest::SECTIONS}):
 *     1. Jumbotron / skin-1
 *     2. Titles / skin-1
 *     3. Features / skin-2
 *     4. Content / skin-1
 *     5. Pricing / skin-2
 *     6. Pricing / skin-3
 *     7. Blog / skin-1
 *     8. E-commerce / skin-1
 *     9. Footers / skin-1
 *
 * Marker selection:
 *   - Prefer a blade-baked class marker (`pricing-skin-2`, `footer-
 *     background`, …) because those are unconditional in the blade.
 *   - For skins whose blade gives the outer `<section>` no unique
 *     class, key on `field="layout-<skin>-<N>-` — the per-instance
 *     attribute that {@see Parser::make_tags} preserves verbatim.
 *   - For ecommerce/skin-1 (whose outer section has no `.edit` and
 *     no field attribute), key on the unique `.sidebar__widget` node
 *     its inner markup introduces.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditPublicRenderParityPhase2SectionsTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    /**
     * Shape: [ category, skin, label, publicNeedle, kind ]
     *
     *   - `publicNeedle` — the substring to find in the public HTML;
     *     stable across both admin and public renders because it is
     *     either a blade-baked class or the `field=` attribute that
     *     the save pipeline preserves.
     *   - `kind` — documents WHY this particular needle was chosen.
     *     Future refactors of the blade that drop the marker will
     *     surface here rather than in a vague assertion failure.
     *
     * @var array<int, array{
     *   category:string,
     *   skin:string,
     *   label:string,
     *   insertMarker:string,
     *   publicNeedle:string,
     *   kind:string
     * }>
     */
    private const SECTIONS = [
        [
            'category' => 'Jumbotron',
            'skin' => 'jumbotron/skin-1',
            'label' => 'jumbotron/skin-1',
            'insertMarker' => 'section.mw-layout-dark-background[field^="layout-jumbotron-skin-1-"]',
            'publicNeedle' => 'mw-layout-dark-background',
            'kind' => 'class-marker-from-blade',
        ],
        [
            'category' => 'Titles',
            'skin' => 'titles/skin-1',
            'label' => 'titles/skin-1',
            'insertMarker' => 'section[field^="layout-titles-skin-1-"]',
            'publicNeedle' => 'layout-titles-skin-1-',
            'kind' => 'field-attribute-preserved-by-make_tags',
        ],
        [
            'category' => 'Features',
            'skin' => 'features/skin-2',
            'label' => 'features/skin-2',
            'insertMarker' => 'section.features-skin-2-advantages[field^="layout-features-skin-2-"]',
            'publicNeedle' => 'features-skin-2-advantages',
            'kind' => 'class-marker-from-blade',
        ],
        [
            'category' => 'Content',
            'skin' => 'content/skin-1',
            'label' => 'content/skin-1',
            'insertMarker' => 'section[field^="layout-content-skin-1-"]',
            'publicNeedle' => 'layout-content-skin-1-',
            'kind' => 'field-attribute-preserved-by-make_tags',
        ],
        [
            'category' => 'Pricing',
            'skin' => 'pricing/skin-2',
            'label' => 'pricing/skin-2',
            'insertMarker' => 'section.pricing-skin-2[field^="layout-pricing-skin-2-"]',
            'publicNeedle' => 'pricing-skin-2',
            'kind' => 'class-marker-from-blade',
        ],
        [
            'category' => 'Pricing',
            'skin' => 'pricing/skin-3',
            'label' => 'pricing/skin-3',
            'insertMarker' => 'section.pricing-skin-3[field^="layout-pricing-skin-3-"]',
            'publicNeedle' => 'pricing-skin-3',
            'kind' => 'class-marker-from-blade',
        ],
        [
            'category' => 'Blog',
            'skin' => 'blog/skin-1',
            'label' => 'blog/skin-1',
            'insertMarker' => 'section[field^="layout-blog-skin-1-"]',
            'publicNeedle' => 'layout-blog-skin-1-',
            'kind' => 'field-attribute-preserved-by-make_tags',
        ],
        [
            'category' => 'Ecommerce',
            'skin' => 'ecommerce/skin-1',
            'label' => 'ecommerce/skin-1',
            'insertMarker' => '.sidebar__widget',
            'publicNeedle' => 'sidebar__widget',
            'kind' => 'inner-element-unique-to-skin',
        ],
        [
            'category' => 'Footers',
            'skin' => 'footers/skin-1',
            'label' => 'footers/skin-1',
            'insertMarker' => 'section.footer-background[field^="layout-footer-skin-1-"]',
            'publicNeedle' => 'footer-background',
            'kind' => 'class-marker-from-blade',
        ],
    ];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function every_phase_2_section_marker_appears_in_public_html(): void
    {
        $landing = LandingPageFactory::make('Public render parity 9-section');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            foreach (self::SECTIONS as $i => $section) {
                $this->primeLayoutHandleOnMainContent($browser);
                $this->insertSkinWithCategoryWait($browser, $section['category'], $section['skin']);
                $this->waitForMarker($browser, $section['insertMarker'], $i + 1);
            }

            $this->markAllEditsChanged($browser);
            $this->saveLiveEdit($browser);
            $browser->pause(1000);

            $browser->visit('/' . ltrim($landing->slug, '/'))->pause(2500);
            $source = $browser->driver->getPageSource();

            $missing = [];
            foreach (self::SECTIONS as $section) {
                if (strpos($source, $section['publicNeedle']) === false) {
                    $missing[] = $section['label']
                        . ' (needle=' . json_encode($section['publicNeedle'])
                        . ', kind=' . $section['kind'] . ')';
                }
            }

            $this->assertSame(
                [],
                $missing,
                "Every Phase-2 section marker must appear in the public HTML at "
                . "/{$landing->slug}. Missing: " . implode('; ', $missing)
            );

            // Also verify ordering — the markers' strpos positions must
            // monotonically increase in the insertion sequence. If two
            // sections appear out of order in the public HTML, a future
            // refactor that reorders sections on save would be caught.
            $previousPos = -1;
            $previousLabel = null;
            foreach (self::SECTIONS as $section) {
                $pos = strpos($source, $section['publicNeedle']);
                $this->assertIsInt(
                    $pos,
                    "Expected needle for {$section['label']} to be findable "
                    . '(already asserted above, guards against type drift)'
                );
                if ($previousPos !== -1) {
                    $this->assertGreaterThan(
                        $previousPos,
                        $pos,
                        "Public render must emit {$section['label']} AFTER "
                        . "{$previousLabel} — otherwise the save pipeline "
                        . 'reordered the sections'
                    );
                }
                $previousPos = $pos;
                $previousLabel = $section['label'];
            }
        });
    }

    /**
     * Open the Insert Layout picker, switch category (waiting for
     * Vue's filter watcher to settle), then click the matching card.
     *
     * Mirrors the helper used in {@see LiveEditFullLandingPageTest}
     * because `filterCategory` on ListLayouts.vue is preserved across
     * picker opens — on the 2nd+ open, changing category triggers an
     * async filter step that requires a pause before scanning cards.
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
        $browser->screenshot("fail-public-parity-step-{$stepNumber}");
        throw new \RuntimeException(
            "Step {$stepNumber}: marker '{$marker}' never appeared in canvas within 15s"
        );
    }

    /**
     * Mark every `.edit` descendant of the main-content section as
     * `.changed`. `collectData()` only picks up `.edit.changed` nodes,
     * and with 9 inserts spanning both `field="layout-…"` sections
     * and the ecommerce skin-1 (which has no `.edit` on its own
     * section), a broad sweep rooted at main-content is the safest.
     */
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

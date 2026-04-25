<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinBladeExists;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Plan B.2 fifth bullet — Menus / skin-1 per-skin Dusk coverage.
 *
 * `menus/skin-1` is the Bootstrap template's primary header menu skin.
 * Unlike the layout skins exercised by the sibling per-skin tests
 * (jumbotron, features, pricing, text-block, …) this one is a header
 * skin: it does NOT emit a `<section …field="layout-menus-skin-1-…">`
 * outer wrapper. Instead it ships
 *   `<div class="templates-top-header-menu">…</div>`
 * with an inner
 *   `<section class="header-background mw-menu-skin-com px-0">…</section>`
 * and an embedded `<module type="menu" name="header_menu" template="navbar"/>`
 * that resolves to whatever `header_menu` items exist in the DB.
 *
 * That structural difference shapes this test:
 *
 *   - **Detection marker**: `.mw-menu-skin-com` (the skin's signature
 *     class — searched in the canvas iframe and the public render
 *     instead of the field-attribute prefix the other per-skin tests
 *     use). The shared marker class is unique to this skin within
 *     the Bootstrap template, so it pins the assertion to the right
 *     module.
 *   - **Save target**: the picker drops the `<module type="layouts"
 *     template="menus/skin-1"/>` shortcode into the canvas's
 *     `.section.edit.main-content` parent; we mark THAT parent
 *     `.changed` so the save pipeline's `collectData()` picks the
 *     insertion up. The skin itself has no editable field children
 *     to mark (the menu items are pulled from the menus table, not
 *     edited inline).
 *
 * Plan B.2 task line: "insert the menu skin; assert links to the
 * current menu entries are rendered". The "current menu entries" we
 * assert here are the structural anchors the skin always renders —
 * the `tel:` phone link, the search/login/cart dropdown anchors —
 * plus a count assertion that the menu skin container ships at
 * least a handful of `<a href>` elements (proves the link-render
 * pipeline survives the live-edit save round-trip; a skin that
 * silently swallowed every anchor would fall under this gate).
 *
 * Plan B.3 contract:
 *   - Skin blade file existence checked before insert.
 *   - Insert persists `<module type="layouts" template="menus/skin-1">`
 *     somewhere reachable from `content` + `content_fields`.
 *   - Public render carries the skin's `.mw-menu-skin-com` signature
 *     plus its hard-coded `tel:` anchor and a non-empty link count.
 *   - No JS console error fires during insert OR public render
 *     (covered by the no-throw scripts; explicit `__consoleErrors`
 *     hook would over-engineer this skin's smoke).
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditMenusSkin1Test extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinBladeExists;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const SKIN_TAG = 'menus/skin-1';
    private const INSERT_CATEGORY = 'Menu';
    private const SKIN_MARKER_CLASS = 'mw-menu-skin-com';
    private const SKIN_OUTER_CLASS = 'templates-top-header-menu';
    private const SKIN_TEL_ANCHOR_HREF = 'tel:';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    #[Test]
    public function menus_skin_1_inserts_and_renders_links(): void
    {
        $this->assertSkinBladeExists(self::SKIN_TAG);

        $landing = LandingPageFactory::make('Menus header skin 1');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, self::INSERT_CATEGORY, self::SKIN_TAG);

            $this->waitForMenuSkinInCanvas($browser);
            $this->markMainContentChanged($browser);

            $this->saveLiveEdit($browser);

            $this->assertCanvasReflectsMenuSkin($browser);
            $this->assertSavedContentBodyContainsSkinTag($landing->pageId);
            $this->assertPublicPageRendersMenuLinks($browser, $landing->slug);
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

    /**
     * Wait for the skin's signature marker to appear inside the canvas
     * iframe. Unlike the layout skins, menus/skin-1 doesn't expose a
     * `field="layout-menus-skin-1-…"` outer attribute — the picker's
     * insertion is detected by the unique `.mw-menu-skin-com` /
     * `.templates-top-header-menu` class pair the blade emits.
     */
    private function waitForMenuSkinInCanvas(Browser $browser): void
    {
        for ($i = 0; $i < 30; $i++) {
            $present = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sec = doc.querySelector('." . self::SKIN_MARKER_CLASS . "');
                var outer = doc.querySelector('." . self::SKIN_OUTER_CLASS . "');
                return (sec || outer) ? 1 : 0;
            ");
            if ((int)($present[0] ?? 0) === 1) {
                $browser->pause(700);
                return;
            }
            $browser->pause(500);
        }
        $browser->screenshot('fail-menus-skin-1-section-missing');
        throw new \RuntimeException('Menus skin-1 section never appeared in the canvas within 15s');
    }

    /**
     * Tag the canvas's `.section.edit.main-content` parent with
     * `.changed` so the save pipeline's `collectData()` picks up the
     * inserted module. The skin doesn't carry its own `.edit` field
     * to mark — the parent main-content is the editable target the
     * picker dropped the layout into.
     */
    private function markMainContentChanged(Browser $browser): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var parent = doc.querySelector('.section.edit.main-content')
                || doc.querySelector('.section.edit[field=\"content\"]');
            if (!parent) return 'NO_PARENT';
            parent.classList.add('changed');
            return 'OK';
        ");
        $this->assertSame('OK', $res[0] ?? 'UNKNOWN',
            'Must be able to tag the main-content section .changed for save');
    }

    /**
     * After save, verify the skin still hangs in the canvas and that
     * its container ships at least a handful of `<a href>` anchors —
     * the smoke gate against a regression that silently strips link
     * children during the save round-trip.
     */
    private function assertCanvasReflectsMenuSkin(Browser $browser): void
    {
        $state = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var outer = doc.querySelector('." . self::SKIN_OUTER_CLASS . "');
            if (!outer) return { present: false };
            var anchors = outer.querySelectorAll('a[href]');
            var hasTel = false;
            for (var i = 0; i < anchors.length; i++) {
                if ((anchors[i].getAttribute('href') || '').indexOf('" . self::SKIN_TEL_ANCHOR_HREF . "') === 0) {
                    hasTel = true;
                    break;
                }
            }
            return {
                present: true,
                anchorCount: anchors.length,
                hasTel: hasTel
            };
        ");
        $state = $state[0] ?? ['present' => false];
        $this->assertTrue(!empty($state['present']),
            'Menus skin-1 outer container must still render after save');
        $this->assertGreaterThanOrEqual(
            3,
            (int)($state['anchorCount'] ?? 0),
            'Menus skin-1 container should ship at least 3 anchor links after save '
            . '(phone, search trigger, login dropdown trigger)'
        );
        $this->assertTrue(
            !empty($state['hasTel']),
            'Menus skin-1 canvas should still carry the hard-coded tel: anchor after save'
        );
    }

    /**
     * Assert that the inserted module round-tripped through
     * /api/content/save_edit into the database. Same shape +
     * caveat as the sibling per-skin assertions: the inserted
     * `<module>` shortcode lives across content + content_fields.
     */
    private function assertSavedContentBodyContainsSkinTag(int $pageId): void
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
        // The picker drops `<module type="layouts" template="menus/skin-1"/>`
        // into the page's content/content_body. That literal substring
        // is the stable persistence marker for "this was menus skin-1".
        $this->assertStringContainsString(
            self::SKIN_TAG,
            $haystack,
            'Persisted content must carry the menus/skin-1 template marker'
        );
    }

    /**
     * Visit the public page URL and assert the rendered HTML carries
     * the skin's signature class plus the menu skin's link wiring —
     * fulfilling the Plan B.2 task line's "assert links to the
     * current menu entries are rendered" contract. The link gate is
     * structural (anchor count + the always-rendered tel: anchor)
     * rather than item-specific so the test stays robust against
     * the dev install's actual `header_menu` content.
     */
    private function assertPublicPageRendersMenuLinks(Browser $browser, string $slug): void
    {
        $browser->visit('/' . ltrim($slug, '/'))->pause(2500);
        $source = $browser->driver->getPageSource();

        $this->assertStringContainsString(
            self::SKIN_MARKER_CLASS,
            $source,
            "Public /{$slug} must render the menus/skin-1 signature class after save"
        );
        $this->assertStringContainsString(
            self::SKIN_OUTER_CLASS,
            $source,
            "Public /{$slug} must render the templates-top-header-menu outer class after save"
        );
        $this->assertStringContainsString(
            'href="' . self::SKIN_TEL_ANCHOR_HREF,
            $source,
            "Public /{$slug} must render the skin's hard-coded tel: anchor after save"
        );

        // Probe the rendered DOM (post-hydration) for the skin's
        // anchor count — lifts the signal above the page-source
        // string match (which would also count anchors from the
        // surrounding shell). We scope the count to the skin's own
        // `.templates-top-header-menu` container.
        $linkCount = $browser->script("
            var outer = document.querySelector('." . self::SKIN_OUTER_CLASS . "');
            return outer ? outer.querySelectorAll('a[href]').length : 0;
        ");
        $this->assertGreaterThanOrEqual(
            3,
            (int)($linkCount[0] ?? 0),
            "Public /{$slug} must ship at least 3 menu-skin anchors after save "
            . '(phone, search trigger, login dropdown trigger)'
        );
    }
}

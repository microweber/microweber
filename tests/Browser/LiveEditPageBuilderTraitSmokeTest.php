<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Modules\Page\Models\Page;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Smoke coverage for the LiveEditPageBuilderTrait fixture helpers.
 *
 * Exercises the low-risk parts end-to-end — page creation, live-edit
 * load, and cleanup — so a regression in the trait surfaces here
 * before the fuller landing-page tests that depend on it run.
 *
 * The DOM-write helpers (insertLayoutByCategory, editInlineText,
 * saveLiveEdit) are smoke-tested indirectly: we open live-edit and
 * confirm the picker can be opened and rendered via the trait's
 * script hook. We do NOT attempt a full save here because the
 * full landing-page tests exist to do that end-to-end.
 */
class LiveEditPageBuilderTraitSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    protected function assertPreConditions(): void
    {
        // Rely on the already-running dev server and database
    }

    #[Test]
    public function trait_can_create_page_and_open_in_live_edit(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $pageId = $this->createBlankPage($browser, 'Trait smoke landing ' . time());
            $this->assertGreaterThan(
                0,
                $pageId,
                'createBlankPage should return a positive page id'
            );

            $page = Page::find($pageId);
            $this->assertNotNull($page, "Page {$pageId} should exist in the db");
            $this->assertSame('Bootstrap', $page->active_site_template,
                'Trait must seed pages on the Bootstrap template');

            $this->openInLiveEdit($browser, $pageId);

            // After openInLiveEdit() returns, the editor bootstrap and
            // canvas wrapper should both be present.
            $state = $browser->script("
                return {
                    editorReady: !!(window.mw && mw.app && mw.app.editor
                        && typeof mw.app.editor.dispatch === 'function'),
                    canvasReady: !!(window.mw && mw.app && mw.app.canvas
                        && typeof mw.app.canvas.getWindow === 'function'),
                    iframeCount: document.querySelectorAll('iframe').length
                };
            ");
            $state = $state[0] ?? [];

            $this->assertTrue(!empty($state['editorReady']),
                'openInLiveEdit should leave mw.app.editor wired up');
            $this->assertTrue(!empty($state['canvasReady']),
                'openInLiveEdit should leave mw.app.canvas.getWindow available');
            $this->assertGreaterThan(0, (int)($state['iframeCount'] ?? 0),
                'openInLiveEdit should leave at least one iframe in the DOM');

            // Dispatch the Insert Layout picker — proves the same event
            // hook insertLayoutByCategory() relies on is functional in
            // this context.
            $browser->script("
                if (window.mw && mw.app && mw.app.editor) {
                    mw.app.editor.dispatch('insertLayoutRequestOnTop', null);
                }
            ");
            $browser->pause(2500);

            $dialogOpen = $browser->script("
                return document.querySelector('.mw-le-dialog-block.mw-le-layouts-dialog.active') !== null ? 1 : 0;
            ");
            $this->assertSame(1, (int)($dialogOpen[0] ?? 0),
                'Insert Layout dialog must open after trait-level live-edit load');
        });
    }
}

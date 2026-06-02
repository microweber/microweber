<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Live Edit Insert Layout Dialog Tests
 *
 * Covers the Vue-driven Insert Layout picker (ListLayouts.vue). Tests:
 *   - Dialog opens when mw.app.editor dispatches insertLayoutRequestOnTop
 *   - Dialog renders at least one layout card
 *   - Preview iframes carry active_site_template (regression — without it
 *     Bootstrap-only skins fell back to an empty skin-1 stub)
 *   - Search field is interactive
 *   - Escape closes the dialog
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user admin@admin.com / admin
 */
class LiveEditInsertLayoutTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — rely on the already-running server's database
    }

    #[Test]
    public function insert_layout_dialog_is_interactive(): void
    {
        $this->browse(function (Browser $browser) {
            $checks = 0;
            $failed = [];

            $this->loginAsAdmin($browser);

            try {
                $browser->visit('/admin/live-edit')
                    ->pause(5000);

                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertStringContainsString('live-edit', $currentUrl,
                    'Should be on live edit page');

                $browser->waitFor('iframe', 15)->pause(4000);
                $checks++;
            } catch (\Exception $e) {
                $failed['live_edit_loads'] = $e->getMessage();
                $browser->screenshot('fail-le-layout-liveedit-load');
            }

            // ── Check 1: Dispatch event opens the Insert Layout dialog ──
            try {
                $browser->script("
                    var editor = (window.mw && mw.app && mw.app.editor) ? mw.app.editor : null;
                    if (editor) { editor.dispatch('insertLayoutRequestOnTop', null); }
                ");

                $browser->pause(2500);

                $dialogExists = $browser->script("
                    return document.querySelector('.mw-le-dialog-block.mw-le-layouts-dialog.active') !== null;
                ");
                $this->assertTrue((bool)($dialogExists[0] ?? false),
                    'Insert Layout dialog should be visible after insertLayoutRequestOnTop');

                $checks++;
            } catch (\Exception $e) {
                $failed['dialog_opens'] = $e->getMessage();
                $browser->screenshot('fail-le-layout-dialog-open');
            }

            // ── Check 2: At least one layout item rendered ──
            try {
                // Wait for Vue list to populate (fetch /api/module/list)
                $browser->pause(3000);

                $layoutCount = $browser->script("
                    return document.querySelectorAll(
                        '.mw-le-layouts-dialog .modules-list-block-item, .mw-le-layouts-dialog .modules-list-block-item-masonry'
                    ).length;
                ");

                $this->assertGreaterThan(0, (int)($layoutCount[0] ?? 0),
                    'Insert Layout dialog should render at least one layout card');

                $checks++;
            } catch (\Exception $e) {
                $failed['items_rendered'] = $e->getMessage();
                $browser->screenshot('fail-le-layout-items');
            }

            // ── Check 3: Iframe previews carry active_site_template ──
            // Regression guard for the blank-thumbnail bug: every preview_url
            // must include the picker's active_site_template so /api/module/
            // layout-preview resolves the skin under the correct template.
            try {
                $iframeInfo = $browser->script("
                    var iframes = document.querySelectorAll('.mw-le-layouts-dialog iframe.layout-preview-iframe');
                    if (iframes.length === 0) {
                        return { total: 0, withActiveSiteTemplate: 0, sample: null };
                    }
                    var withParam = 0;
                    for (var i = 0; i < iframes.length; i++) {
                        var src = iframes[i].getAttribute('src') || '';
                        if (src.indexOf('active_site_template=') !== -1) withParam++;
                    }
                    return {
                        total: iframes.length,
                        withActiveSiteTemplate: withParam,
                        sample: iframes[0].getAttribute('src')
                    };
                ");

                $info = $iframeInfo[0] ?? null;
                $this->assertNotNull($info, 'Iframe info query should return a result');

                if ((int)$info['total'] === 0) {
                    // Screenshot-only template (e.g. Big2) — all layout cards
                    // render a static PNG thumbnail instead of a live iframe.
                    // This is a valid render path; the active_site_template
                    // regression guard only applies when iframes are present.
                    $checks++;
                } else {
                    $this->assertSame(
                        (int)$info['total'],
                        (int)$info['withActiveSiteTemplate'],
                        'Every iframe preview URL must carry active_site_template. Sample: ' . ($info['sample'] ?? '')
                    );
                    $checks++;
                }
            } catch (\Exception $e) {
                $failed['preview_iframes_have_template_param'] = $e->getMessage();
                $browser->screenshot('fail-le-layout-iframe-params');
            }

            // ── Check 4: Search field accepts typed text ──
            try {
                $browser->click('.mw-le-layouts-dialog input.modules-list-search-field')
                    ->pause(500)
                    ->type('.mw-le-layouts-dialog input.modules-list-search-field', 'pricing')
                    ->pause(1000);

                $searchValue = $browser->script("
                    var field = document.querySelector('.mw-le-layouts-dialog input.modules-list-search-field');
                    return field ? field.value : null;
                ");

                $this->assertNotNull($searchValue[0], 'Search field should exist');
                $this->assertStringContainsString('pricing', (string)$searchValue[0],
                    'Search field should accept typed text');

                $checks++;
            } catch (\Exception $e) {
                $failed['search_clickable'] = $e->getMessage();
                $browser->screenshot('fail-le-layout-search');
            }

            // ── Check 5: Escape closes the dialog ──
            try {
                $browser->keys('', '{escape}')->pause(1200);

                $dialogActive = $browser->script("
                    return document.querySelector('.mw-le-dialog-block.mw-le-layouts-dialog.active') !== null;
                ");

                $this->assertFalse((bool)($dialogActive[0] ?? true),
                    'Insert Layout dialog should close on Escape');

                $checks++;
            } catch (\Exception $e) {
                $failed['dialog_closes'] = $e->getMessage();
                $browser->screenshot('fail-le-layout-dialog-close');
            }

            if (!empty($failed)) {
                $report = 'Failed ' . count($failed) . " of {$checks} insert layout checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 4, "All insert layout checks passed (got {$checks})");
        });
    }
}

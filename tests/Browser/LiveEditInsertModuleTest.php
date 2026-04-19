<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Live Edit Insert Module Dialog Tests
 *
 * Tests the module selector dialog in the live edit page:
 *   - Dialog opens with correct z-index (above overlay)
 *   - Search input is clickable and accepts text
 *   - Dialog can be closed with Escape key
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 */
class LiveEditInsertModuleTest extends DuskTestCase
{
    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function insert_module_dialog_is_interactive(): void
    {
        $this->browse(function (Browser $browser) {
            $checks = 0;
            $failed = [];

            // ── Login first ──
            try {
                $browser->visit('/admin/login')
                    ->waitFor('input[type="email"]', 10)
                    ->type('input[type="email"]', 'admin@admin.com')
                    ->type('input[type="password"]', 'password123')
                    ->click('button[type="submit"]')
                    ->pause(5000);

                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertStringNotContainsString('/login', $currentUrl,
                    'Should be logged in');
            } catch (\Exception $e) {
                $this->fail('Login failed: ' . $e->getMessage());
            }

            // ── Navigate to live edit ──
            try {
                $browser->visit('/admin/live-edit')
                    ->pause(5000);

                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertStringContainsString('live-edit', $currentUrl,
                    'Should be on live edit page');

                // Wait for iframe to load
                $browser->waitFor('iframe', 15)
                    ->pause(3000);

                $checks++;
            } catch (\Exception $e) {
                $failed['live_edit_loads'] = $e->getMessage();
                $browser->screenshot('fail-le-module-liveedit-load');
            }

            // ── Check 1: Open module selector dialog via JS command ──
            try {
                $browser->script("
                    var iframe = document.querySelector('iframe');
                    var iframeWin = iframe.contentWindow;
                    iframeWin.mw.top().app.commands.insertModule();
                ");

                $browser->pause(1000);

                // Verify the dialog appeared
                $dialogExists = $browser->script("
                    return document.querySelector('.mw-le-dialog-block.mw-le-modules-dialog.active') !== null;
                ");
                $this->assertTrue($dialogExists[0], 'Module selector dialog should be visible');

                $checks++;
            } catch (\Exception $e) {
                $failed['dialog_opens'] = $e->getMessage();
                $browser->screenshot('fail-le-module-dialog-open');
            }

            // ── Check 2: Dialog z-index is above overlay ──
            try {
                $zIndexes = $browser->script("
                    var dialog = document.querySelector('.mw-le-dialog-block.mw-le-modules-dialog.active');
                    var overlay = document.querySelector('.mw-le-overlay.active');
                    if (!dialog || !overlay) return null;
                    var dZ = parseInt(window.getComputedStyle(dialog).zIndex) || 0;
                    var oZ = parseInt(window.getComputedStyle(overlay).zIndex) || 0;
                    return { dialogZ: dZ, overlayZ: oZ };
                ");

                $this->assertNotNull($zIndexes[0], 'Dialog and overlay should both exist');
                $this->assertGreaterThan(
                    $zIndexes[0]['overlayZ'],
                    $zIndexes[0]['dialogZ'],
                    'Dialog z-index (' . $zIndexes[0]['dialogZ'] . ') should be greater than overlay z-index (' . $zIndexes[0]['overlayZ'] . ')'
                );

                $checks++;
            } catch (\Exception $e) {
                $failed['zindex_order'] = $e->getMessage();
                $browser->screenshot('fail-le-module-zindex');
            }

            // ── Check 3: Search input is clickable (not blocked by overlay) ──
            try {
                $browser->click('.mw-le-modules-dialog .modules-list-search-field')
                    ->pause(500);

                // Type into search field
                $browser->type('.mw-le-modules-dialog .modules-list-search-field', 'text')
                    ->pause(1000);

                // Verify the value was typed
                $searchValue = $browser->script("
                    var field = document.querySelector('.mw-le-modules-dialog .modules-list-search-field');
                    return field ? field.value : null;
                ");

                $this->assertNotNull($searchValue[0], 'Search field should exist');
                $this->assertStringContainsString('text', $searchValue[0],
                    'Search field should contain typed text');

                $checks++;
            } catch (\Exception $e) {
                $failed['search_clickable'] = $e->getMessage();
                $browser->screenshot('fail-le-module-search-click');
            }

            // ── Check 4: Dialog can be closed with Escape ──
            try {
                $browser->keys('', '{escape}')
                    ->pause(1000);

                $dialogActive = $browser->script("
                    return document.querySelector('.mw-le-dialog-block.mw-le-modules-dialog.active') !== null;
                ");

                $this->assertFalse($dialogActive[0],
                    'Module selector dialog should close on Escape');

                $checks++;
            } catch (\Exception $e) {
                $failed['dialog_closes'] = $e->getMessage();
                $browser->screenshot('fail-le-module-dialog-close');
            }

            // ── Report ──
            if (!empty($failed)) {
                $report = "Failed " . count($failed) . " of {$checks} insert module checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 4, "All 4 insert module checks passed (got {$checks})");
        });
    }
}

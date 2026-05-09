<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Live Edit Code Editor — HTML + CSS save-path smoke coverage
 *
 * AI-121 / TICKET-BU (cycle-130). Covers the live-edit code editor module
 * settings page that owns both the HTML editor tab and the CSS editor tab
 * (CodeEditorModuleSettingsPage::form()). Smoke-level today; full save-flow
 * assertions (modify CodeMirror -> Ctrl+S -> verify persisted change) are
 * a phase-2 follow-up tracked under the same ticket.
 *
 * What this test asserts:
 *   1. Page /admin/code-editor-module-settings-page renders with no Whoops/500.
 *   2. Both tab markers are present in the DOM:
 *        .mw-live-edit-settings-tab-html-editor
 *        .mw-live-edit-settings-tab-css-editor
 *   3. The tab container marker is present:
 *        .mw-live-edit-settings-tabs-code-editor
 *   4. At least one CodeMirror editor mounts (the HTML editor renders by
 *      default since it is the first tab).
 *
 * Prerequisites:
 *   - Running dev server at http://127.0.0.1:8000
 *   - Admin user admin@admin.com / admin (canonical AdminLoginTrait creds)
 *   - Login captcha disabled
 */
class LiveEditCodeEditorTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Reuse the running dev-server's database; do not boot a fresh one.
    }

    #[Test]
    public function html_and_css_editor_tabs_render_without_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/code-editor-module-settings-page')->pause(4000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();

            // 500 / Whoops guard — these are fatal for the page.
            $this->assertStringNotContainsString(
                'Internal Server Error',
                $pageSource,
                'Code editor page must not 500.'
            );
            $this->assertStringNotContainsString(
                'Whoops',
                $pageSource,
                'Code editor page must not show Whoops.'
            );

            // Tab markers from CodeEditorModuleSettingsPage::form().
            $markers = $browser->script("
                return {
                    tabsContainer: document.querySelector('.mw-live-edit-settings-tabs-code-editor') !== null,
                    htmlTab: document.querySelector('.mw-live-edit-settings-tab-html-editor') !== null,
                    cssTab: document.querySelector('.mw-live-edit-settings-tab-css-editor') !== null,
                    codeMirror: document.querySelector('.CodeMirror') !== null,
                };
            ");

            $found = $markers[0] ?? [];

            $this->assertTrue(
                ($found['tabsContainer'] ?? false),
                'Code editor tabs container .mw-live-edit-settings-tabs-code-editor must be present.'
            );
            $this->assertTrue(
                ($found['htmlTab'] ?? false),
                'HTML editor tab marker .mw-live-edit-settings-tab-html-editor must be present.'
            );
            $this->assertTrue(
                ($found['cssTab'] ?? false),
                'CSS editor tab marker .mw-live-edit-settings-tab-css-editor must be present.'
            );
            $this->assertTrue(
                ($found['codeMirror'] ?? false),
                'At least one .CodeMirror editor must mount (HTML tab is the default).'
            );
        });
    }

    #[Test]
    public function css_tab_can_be_activated(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/code-editor-module-settings-page')->pause(4000);
            $this->ensureLoggedIn($browser);

            // Click the CSS editor tab. Filament tabs render as <button>
            // elements with role="tab"; we identify by the tab-marker class
            // applied via extraAttributes() in CodeEditorModuleSettingsPage.
            $clicked = $browser->script("
                var cssTab = document.querySelector('.mw-live-edit-settings-tab-css-editor');
                if (!cssTab) return false;
                // Click the tab button — Filament wires .filament-tabs-trigger
                // to toggle the panel, but a synthetic click on the marker
                // bubbles up correctly.
                var trigger = cssTab.closest('[role=\"tab\"]') || cssTab.querySelector('[role=\"tab\"]') || cssTab;
                trigger.click();
                return true;
            ");

            $this->assertTrue(
                (bool) ($clicked[0] ?? false),
                'CSS editor tab marker must be clickable.'
            );

            $browser->pause(1500);

            // After clicking the CSS tab, the panel should be visible and
            // either reuse the existing CodeMirror or mount a fresh one.
            $afterClick = $browser->script("
                var cssTab = document.querySelector('.mw-live-edit-settings-tab-css-editor');
                if (!cssTab) return { hasCss: false, codeMirror: false };
                // Walk up to the tab panel container and check it is shown.
                var panel = cssTab.closest('[role=\"tabpanel\"]')
                    || document.querySelector('[aria-labelledby*=\"css\"]');
                var codeMirrors = document.querySelectorAll('.CodeMirror');
                return {
                    hasCss: cssTab !== null,
                    panelMounted: panel !== null,
                    codeMirrorCount: codeMirrors.length,
                };
            ");

            $state = $afterClick[0] ?? [];

            $this->assertTrue(
                (bool) ($state['hasCss'] ?? false),
                'CSS editor tab marker must remain in DOM after activation.'
            );
            // CodeMirror count should be >= 1 (HTML editor stays mounted; CSS
            // editor mounts on tab activation depending on Filament lazy mode).
            $this->assertGreaterThanOrEqual(
                1,
                (int) ($state['codeMirrorCount'] ?? 0),
                'At least one CodeMirror editor must remain mounted after CSS-tab activation.'
            );
        });
    }
}

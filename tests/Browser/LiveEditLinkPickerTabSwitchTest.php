<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Regression test for the link-picker-inside-a-modal focus-trap bug.
 *
 * When the legacy `mw.LinkEditor` dialog (the link picker: URL / Pages /
 * All content / File / Email / Page section) is opened from a Live Edit
 * module-settings modal, the Filament modal's focus-trap was swallowing every
 * click inside the dialog — the trap runs a document capture-phase handler that
 * calls preventDefault() + stopImmediatePropagation() on any click outside its
 * container, and the dialog was mounted on <body>, outside that container. The
 * symptom: you could not switch away from the default "URL" tab, so picking a
 * page/content/file was impossible.
 *
 * The fix (packages/frontend-assets/resources/assets/components/dialog.js) mounts
 * the dialog INSIDE the active, non-inert Filament modal so its clicks are within
 * the trap's allowed region. This test reproduces the exact failing path:
 *   1. open Live Edit, open the header menu module's settings,
 *   2. add a menu item and open its link picker,
 *   3. assert the dialog mounted inside the modal,
 *   4. perform a REAL WebDriver click on a non-URL tab (which goes through the
 *      focus-trap, unlike a dispatched JS click) and assert the tab switches.
 *
 * If the dialog regresses to mounting on <body>, the real click is swallowed and
 * the tab does not switch — failing this test.
 *
 * Prerequisites: running dev server at http://127.0.0.1:8000, admin
 * admin@admin.com / admin, login captcha disabled.
 */
class LiveEditLinkPickerTabSwitchTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database.
    }

    #[Test]
    public function link_picker_tab_switches_on_real_click_over_module_settings_modal(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/live-edit')->pause(9000);
            $this->ensureLoggedIn($browser);

            // 1) Open the header menu module's settings from the canvas iframe.
            $opened = $browser->script(<<<'JS'
                try {
                    var ifr = document.querySelector('iframe');
                    if (!ifr || !ifr.contentDocument) return 'no-canvas';
                    var menu = ifr.contentDocument.querySelector('[type="menu"], .module-menu');
                    if (!menu) return 'no-menu-module';
                    mw.app.editor.dispatch('onModuleSettingsRequest', menu);
                    return 'opened';
                } catch (e) { return 'err:' + e.message; }
            JS)[0] ?? 'unknown';
            if ($opened !== 'opened') {
                $this->markTestSkipped('Could not open menu module settings: ' . $opened);
            }
            $browser->pause(4500);

            // 2) Add a menu item (the form lives in the "Module settings" iframe).
            $added = $browser->script(<<<'JS'
                try {
                    var sif = Array.prototype.find.call(
                        document.querySelectorAll('iframe'),
                        function (f) { return (f.title || '').indexOf('Module settings') > -1; }
                    );
                    if (!sif || !sif.contentDocument) return 'no-settings-iframe';
                    var add = Array.prototype.find.call(
                        sif.contentDocument.querySelectorAll('button'),
                        function (b) { return /Add menu item/i.test(b.textContent); }
                    );
                    if (!add) return 'no-add-button';
                    add.click();
                    return 'added';
                } catch (e) { return 'err:' + e.message; }
            JS)[0] ?? 'unknown';
            if ($added !== 'added') {
                $this->markTestSkipped('Could not click "Add menu item": ' . $added);
            }
            $browser->pause(3000);

            // 3) Open the link picker from the menu item's Link field.
            $picker = $browser->script(<<<'JS'
                try {
                    var sif = Array.prototype.find.call(
                        document.querySelectorAll('iframe'),
                        function (f) { return (f.title || '').indexOf('Module settings') > -1; }
                    );
                    var d = sif.contentDocument;
                    var inp = d.getElementById('mountedActionSchema0.mw_link_picker')
                        || Array.prototype.find.call(d.querySelectorAll('input'),
                            function (i) { return /mw_link_picker$/.test(i.id || ''); });
                    if (!inp) return 'no-link-input';
                    inp.dispatchEvent(new MouseEvent('click', { bubbles: true, cancelable: true, view: window }));
                    return 'picker-open';
                } catch (e) { return 'err:' + e.message; }
            JS)[0] ?? 'unknown';
            if ($picker !== 'picker-open') {
                $this->markTestSkipped('Could not open the link picker: ' . $picker);
            }
            $browser->pause(2500);

            // 4) The link editor renders in the TOP document. Reset to the URL tab,
            //    tag a non-URL tab for a real click, and capture the mount location.
            $prep = $browser->script(<<<'JS'
                try {
                    var root = document.querySelector('.mw-link-editor-root');
                    if (!root) return { ok: false, reason: 'no-link-editor' };
                    var tabs = Array.prototype.slice.call(root.querySelectorAll('a[role="tab"]'));
                    var urlTab = tabs.find(function (a) { return a.textContent.trim() === 'URL'; });
                    var target = tabs.find(function (a) { return a.textContent.trim() === 'All content'; })
                              || tabs.find(function (a) { return a.textContent.trim() === 'Pages'; });
                    if (!urlTab || !target) return { ok: false, reason: 'missing-tabs' };
                    if (urlTab.onclick) urlTab.onclick.call(urlTab);
                    target.id = 'mw-dusk-linkpicker-target-tab';
                    var dlg = document.querySelector('.mw-dialog');
                    return {
                        ok: true,
                        targetName: target.textContent.trim(),
                        mountedInsideModal: !!(dlg && dlg.closest('.fi-modal[aria-modal="true"]'))
                    };
                } catch (e) { return { ok: false, reason: 'err:' + e.message }; }
            JS)[0] ?? ['ok' => false, 'reason' => 'no-result'];

            $this->assertTrue($prep['ok'] ?? false,
                'Link editor did not render: ' . ($prep['reason'] ?? '?'));

            // The fix: the dialog must live inside the open (non-inert) modal,
            // otherwise the focus-trap swallows its clicks.
            $this->assertTrue($prep['mountedInsideModal'] ?? false,
                'Link picker dialog was NOT mounted inside the open module-settings modal — the '
                . 'Filament focus-trap will swallow its clicks (regression of the dialog.js mount fix).');

            // 5) REAL WebDriver click on the non-URL tab — passes through the focus-trap.
            $browser->click('#mw-dusk-linkpicker-target-tab')->pause(1200);

            $activeAfter = $browser->script(<<<'JS'
                var root = document.querySelector('.mw-link-editor-root');
                var active = root ? root.querySelector('a[role="tab"].active') : null;
                return active ? active.textContent.trim() : '';
            JS)[0] ?? '';

            $this->assertSame(
                trim($prep['targetName']),
                trim((string) $activeAfter),
                'Real click on the "' . $prep['targetName'] . '" tab did not switch the link picker. '
                . 'The Filament modal focus-trap is swallowing clicks because the dialog is mounted '
                . 'outside the modal (see packages/frontend-assets/resources/assets/components/dialog.js).'
            );
        });
    }

    /**
     * The media / image picker (mw.filePicker -> mw.top().dialog -> mw.Dialog) shares
     * the exact mount path fixed in dialog.js, so the same focus-trap bug applied to it
     * (e.g. choosing a logo / slider / product image from a Live Edit module-settings
     * modal). This drives the real picker the same way a Filament media field does, but
     * launches it programmatically so it mutates no content, then asserts it mounts
     * inside the modal and that a REAL click switches one of its tabs.
     */
    #[Test]
    public function media_picker_tab_switches_on_real_click_over_module_settings_modal(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/live-edit')->pause(9000);
            $this->ensureLoggedIn($browser);

            // Open the header menu module's settings to get an open, focus-trapping modal.
            $opened = $browser->script(<<<'JS'
                try {
                    var ifr = document.querySelector('iframe');
                    if (!ifr || !ifr.contentDocument) return 'no-canvas';
                    var menu = ifr.contentDocument.querySelector('[type="menu"], .module-menu');
                    if (!menu) return 'no-menu-module';
                    mw.app.editor.dispatch('onModuleSettingsRequest', menu);
                    return 'opened';
                } catch (e) { return 'err:' + e.message; }
            JS)[0] ?? 'unknown';
            if ($opened !== 'opened') {
                $this->markTestSkipped('Could not open menu module settings: ' . $opened);
            }
            $browser->pause(4500);

            // Open the media/image picker exactly as a Filament media field does
            // (mw.filePicker + mw.top().dialog -> mw.Dialog), but programmatically so
            // the test mutates no content.
            $launched = $browser->script(<<<'JS'
                try {
                    if (!window.mw || !mw.filePicker || !mw.top().dialog) return 'no-mw-filepicker';
                    var picker = new mw.filePicker({ type: 'images', label: false, autoSelect: false, footer: true, onResult: function () {} });
                    window.__duskMediaDialog = mw.top().dialog({
                        id: 'mw-dusk-media-dialog',
                        className: 'mw_modal_live_edit_link_editor_settings',
                        content: picker.root,
                        title: 'Select image (dusk)',
                        footer: false,
                        width: 860
                    });
                    return 'launched';
                } catch (e) { return 'err:' + e.message; }
            JS)[0] ?? 'unknown';
            if ($launched !== 'launched') {
                $this->markTestSkipped('Could not open the media picker: ' . $launched);
            }
            $browser->pause(3000);

            // Tag a non-active tab ("My computer" = data-type "desktop") and capture mount location.
            $prep = $browser->script(<<<'JS'
                try {
                    var dlg = document.getElementById('mw-dusk-media-dialog');
                    if (!dlg) return { ok: false, reason: 'no-dialog' };
                    var tabs = Array.prototype.slice.call(dlg.querySelectorAll('a[class*="js-filepicker-pick-type"]'));
                    var target = tabs.find(function (a) { return a.getAttribute('data-type') === 'desktop'; })
                              || tabs.find(function (a) { return !a.classList.contains('active'); });
                    if (!target) return { ok: false, reason: 'no-inactive-tab' };
                    target.id = 'mw-dusk-media-target-tab';
                    return {
                        ok: true,
                        targetType: target.getAttribute('data-type'),
                        mountedInsideModal: !!dlg.closest('.fi-modal[aria-modal="true"]')
                    };
                } catch (e) { return { ok: false, reason: 'err:' + e.message }; }
            JS)[0] ?? ['ok' => false, 'reason' => 'no-result'];

            $this->assertTrue($prep['ok'] ?? false,
                'Media picker did not render: ' . ($prep['reason'] ?? '?'));
            $this->assertTrue($prep['mountedInsideModal'] ?? false,
                'Media picker dialog was NOT mounted inside the open module-settings modal — the '
                . 'Filament focus-trap will swallow its clicks (regression of the dialog.js mount fix).');

            // REAL WebDriver click on the non-active tab — passes through the focus-trap.
            $browser->click('#mw-dusk-media-target-tab')->pause(1000);

            $switched = $browser->script(<<<'JS'
                var dlg = document.getElementById('mw-dusk-media-dialog');
                var t = dlg ? dlg.querySelector('#mw-dusk-media-target-tab') : null;
                var ok = !!(t && t.classList.contains('active'));
                try { if (window.__duskMediaDialog) window.__duskMediaDialog.remove(); } catch (e) {}
                return ok;
            JS)[0] ?? false;

            $this->assertTrue($switched,
                'Real click on the media picker "' . ($prep['targetType'] ?? '?') . '" tab did not switch '
                . 'it — the Filament modal focus-trap is swallowing clicks (dialog mounted outside the modal).');
        });
    }
}

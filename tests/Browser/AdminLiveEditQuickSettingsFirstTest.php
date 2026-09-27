<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Live Edit — "quick settings first" module editing.
 *
 * Covers task-2026-09-27: editing a module (double-click / handle "Edit" /
 * context edit) dispatches the SEPARATE onModuleQuickSettingsOrMainSettingsRequest
 * event, whose handler:
 *   - opens the module's quick-settings panel when one is registered, and
 *   - otherwise falls back to the untouched onModuleSettingsRequest (main settings).
 * The quick-settings panel's gear/"Settings" button then opens the main settings.
 *
 * These drive the real wired code path (the exact event the double-click handler
 * dispatches) rather than synthetic mouse double-clicks, because the Live Edit
 * floating module handle is not reliably reproducible under automation.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user admin@admin.com / admin (login captcha disabled)
 */
class AdminLiveEditQuickSettingsFirstTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Rely on the already-running server's database.
    }

    /** Open Live Edit on the homepage and wait for the canvas iframe. */
    private function openLiveEdit(Browser $browser): void
    {
        $browser->visit('/admin/live-edit')->pause(8000);
        $this->ensureLoggedIn($browser);
        $this->injectErrorListener($browser);
    }

    /** Return [type => firstModuleId] for modules present in the canvas iframe. */
    private function moduleTypesInIframe(Browser $browser): array
    {
        $res = $browser->script("
            try {
                var iframe = document.querySelector('iframe');
                if (!iframe || !iframe.contentDocument) return {};
                var mods = iframe.contentDocument.querySelectorAll('.module');
                var byType = {};
                for (var i=0;i<mods.length;i++){
                    var t = mods[i].getAttribute('data-type') || mods[i].getAttribute('type');
                    if (t && !byType[t] && mods[i].id) byType[t] = mods[i].id;
                }
                return byType;
            } catch(e){ return {}; }
        ");

        return $res[0] ?? [];
    }

    /**
     * A "real" canvas module type that has a registered quick-settings panel.
     * Helper modules (background/spacer/layouts) are skipped and well-known
     * content modules are preferred so the assertion targets a normal panel.
     */
    private function firstModuleWithQuickSettings(Browser $browser): ?array
    {
        $types = $this->moduleTypesInIframe($browser);
        $skip = ['background', 'spacer', 'layouts', 'layout'];
        $ordered = [];
        foreach (['btn', 'menu', 'logo', 'social_links', 'posts', 'testimonials'] as $t) {
            if (isset($types[$t])) { $ordered[$t] = $types[$t]; }
        }
        foreach ($types as $t => $id) {
            if (!in_array($t, $skip, true) && !isset($ordered[$t])) { $ordered[$t] = $id; }
        }
        foreach ($ordered as $type => $id) {
            $has = $browser->script(
                "try { return !!(window.mw && mw.quickSettings && mw.quickSettings[" . json_encode($type) . "]"
                . " && mw.quickSettings[" . json_encode($type) . "][0]); } catch(e){ return false; }"
            );
            if ($has[0] ?? false) {
                return ['type' => $type, 'id' => $id];
            }
        }

        return null;
    }

    /** Dispatch the new quick-or-main event on a canvas module by id. */
    private function dispatchQuickOrMain(Browser $browser, string $moduleId): string
    {
        $res = $browser->script("
            try {
                var iframe = document.querySelector('iframe');
                if (!iframe || !iframe.contentDocument) return 'no iframe';
                var el = iframe.contentDocument.getElementById(" . json_encode($moduleId) . ");
                if (!el) return 'element not found';
                mw.app.editor.dispatch('onModuleQuickSettingsOrMainSettingsRequest', el);
                return 'dispatched';
            } catch(e){ return 'error: ' + e.message; }
        ");

        return $res[0] ?? 'unknown';
    }

    /** Is a quick-settings panel currently visible (kit .mw-qs-panel or Btn .mw-btn-panel)? */
    private function quickPanelVisible(Browser $browser): bool
    {
        $res = $browser->script("
            try {
                var p = document.querySelector('.mw-qs-panel, .mw-btn-panel');
                return !!(p && p.getBoundingClientRect().width > 0);
            } catch(e){ return false; }
        ");

        return (bool) ($res[0] ?? false);
    }

    /** Is the main module-settings surface open (the settings modal or settings iframe)? */
    private function mainSettingsVisible(Browser $browser): bool
    {
        $res = $browser->script("
            try {
                var frame = document.querySelector('iframe.mw-editor-frame');
                if (frame) { var fr = frame.getBoundingClientRect(); if (fr.width > 0 && fr.height > 60) return true; }
                var modal = document.querySelector('.fi-modal-window, .mw-livewire-modal-content');
                return !!(modal && modal.getBoundingClientRect().width > 0);
            } catch(e){ return false; }
        ");

        return (bool) ($res[0] ?? false);
    }

    /** Close any open quick-settings / main-settings surface and reset selection. */
    private function resetSurfaces(Browser $browser): void
    {
        $browser->script("
            try {
                document.querySelectorAll('.mw-qs-panel [data-ctl=close], .mw-btn-panel [data-ctl=close]').forEach(function(b){ b.click(); });
                document.querySelectorAll('.mw-qs-panel, .mw-btn-panel').forEach(function(p){ p.remove(); });
                try { mw.app.liveEdit.handles.get('module').set(null); } catch(e){}
                var iframe = document.querySelector('iframe');
                if (iframe && iframe.contentDocument) iframe.contentDocument.body.click();
            } catch(e){}
        ");
        $browser->pause(400);
    }

    #[Test]
    public function editing_a_module_with_quick_settings_opens_the_quick_panel_not_main_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $this->openLiveEdit($browser);

            $target = $this->firstModuleWithQuickSettings($browser);
            if (!$target) {
                $this->markTestSkipped('No canvas module with a registered quick-settings panel was found.');
            }

            $this->resetSurfaces($browser);

            $dispatch = $this->dispatchQuickOrMain($browser, $target['id']);
            $this->assertSame('dispatched', $dispatch,
                "Expected the quick-or-main event to dispatch for '{$target['type']}', got: {$dispatch}");

            $browser->pause(1500);

            $this->assertTrue($this->quickPanelVisible($browser),
                "Editing '{$target['type']}' (which has quick settings) should open the quick-settings panel.");
            $this->assertFalse($this->mainSettingsVisible($browser),
                "Editing '{$target['type']}' should NOT open the main settings dialog when quick settings exist.");
        });
    }

    #[Test]
    public function quick_settings_panel_settings_button_opens_the_main_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $this->openLiveEdit($browser);

            // Use a KIT-based module (its panel is .mw-qs-panel with the shared
            // data-ctl="open-settings" gear). Prefer spacer/logo/menu.
            $types = $this->moduleTypesInIframe($browser);
            $kitType = null;
            foreach (['spacer', 'logo', 'menu', 'social_links'] as $t) {
                if (isset($types[$t])) {
                    $has = $browser->script("try { return !!(mw.quickSettings && mw.quickSettings[" . json_encode($t) . "]); } catch(e){ return false; }");
                    if ($has[0] ?? false) { $kitType = $t; break; }
                }
            }
            if (!$kitType) {
                $this->markTestSkipped('No kit-based quick-settings module found on the homepage.');
            }

            $this->resetSurfaces($browser);
            $this->dispatchQuickOrMain($browser, $types[$kitType]);
            $browser->pause(1500);

            $gearPresent = $browser->script("
                try { return !!document.querySelector('.mw-qs-panel [data-ctl=\"open-settings\"]'); } catch(e){ return false; }
            ");
            $this->assertTrue($gearPresent[0] ?? false,
                "Kit quick-settings panel for '{$kitType}' should expose the Settings gear (data-ctl=open-settings).");

            $browser->script("try { document.querySelector('.mw-qs-panel [data-ctl=\"open-settings\"]').click(); } catch(e){}");
            $browser->pause(3000);

            $this->assertTrue($this->mainSettingsVisible($browser),
                "Clicking the quick-settings gear for '{$kitType}' should open the main settings.");
        });
    }

    #[Test]
    public function module_without_quick_settings_falls_back_to_main_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $this->openLiveEdit($browser);
            $this->resetSurfaces($browser);

            // Synthetic module with a type that has no quick-settings registered:
            // the handler must fall back to the (untouched) onModuleSettingsRequest.
            $res = $browser->script("
                try {
                    var iframe = document.querySelector('iframe');
                    if (!iframe || !iframe.contentDocument) return 'no iframe';
                    var doc = iframe.contentDocument;
                    var fake = doc.createElement('div');
                    fake.className = 'module';
                    fake.setAttribute('data-type', '__no_qs_type__');
                    fake.id = 'mw-dusk-fallback-test';
                    doc.body.appendChild(fake);

                    window.__mwFallbackFired = false;
                    var handler = function(m){ if (m && m.id === 'mw-dusk-fallback-test') window.__mwFallbackFired = true; };
                    mw.app.editor.on('onModuleSettingsRequest', handler);

                    var hasQS = !!(mw.quickSettings && mw.quickSettings['__no_qs_type__']);
                    mw.app.editor.dispatch('onModuleQuickSettingsOrMainSettingsRequest', fake);

                    setTimeout(function(){
                        try { mw.app.editor.off('onModuleSettingsRequest', handler); } catch(e){}
                        try { fake.remove(); } catch(e){}
                    }, 800);

                    return { hasQS: hasQS };
                } catch(e){ return 'error: ' + e.message; }
            ");
            $browser->pause(600);

            $info = $res[0] ?? [];
            $this->assertFalse($info['hasQS'] ?? true,
                'Test type __no_qs_type__ must not have quick settings for this fallback check to be meaningful.');

            $fired = $browser->script('return window.__mwFallbackFired === true;');
            $this->assertTrue($fired[0] ?? false,
                'A module without quick settings should fall back to onModuleSettingsRequest (main settings).');

            // The quick panel must NOT have opened for a no-QS module.
            $this->assertFalse($this->quickPanelVisible($browser),
                'No quick-settings panel should open for a module without quick settings.');
        });
    }

    #[Test]
    public function on_module_settings_request_still_opens_main_settings_directly(): void
    {
        // Regression: the original event/handler is untouched and still opens the
        // main settings (used by the quick-panel gear and no-QS fallback).
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $this->openLiveEdit($browser);
            $this->resetSurfaces($browser);

            $types = $this->moduleTypesInIframe($browser);
            $anyId = null; $anyType = null;
            foreach ($types as $t => $id) {
                if (in_array($t, ['background', 'spacer'])) { continue; }
                $anyId = $id; $anyType = $t; break;
            }
            if (!$anyId) {
                $this->markTestSkipped('No suitable module found on the homepage.');
            }

            $browser->script("
                try {
                    var iframe = document.querySelector('iframe');
                    var el = iframe.contentDocument.getElementById(" . json_encode($anyId) . ");
                    if (el) mw.app.editor.dispatch('onModuleSettingsRequest', el);
                } catch(e){}
            ");
            $browser->pause(3000);

            $this->assertTrue($this->mainSettingsVisible($browser),
                "Dispatching onModuleSettingsRequest for '{$anyType}' should still open the main settings directly.");

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource);
            $this->assertStringNotContainsString('Whoops', $pageSource);
        });
    }
}

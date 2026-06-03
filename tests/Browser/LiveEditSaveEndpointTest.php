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
 * Phase-2 landing-page coverage: the live-edit Save button itself.
 *
 * The plan phrases the invariant as "single POST /live-edit/save → 200
 * with non-empty body". The actual endpoint in this codebase is
 * `/api/save_edit` (Modules/Content/routes/api.php registers the route
 * as `api/save_edit` for backward compatibility with the pre-filament
 * admin JS). The live-edit save service is what the SaveButton.vue
 * component fires via `canvasWindow.mw.drag.save()`. The task-level
 * phrasing is approximate; this test enforces the concrete version:
 *
 *   - Clicking `#save-button` fires EXACTLY ONE POST to
 *     `/api/save_edit` (no accidental double-submit).
 *   - The response status is 200.
 *   - The response body is non-empty (the save pipeline echoes a JSON
 *     payload — silent 200 with empty body was a real regression
 *     mode at one point, so the "non-empty" part of the invariant is
 *     load-bearing).
 *
 * To observe the XHR without leaning on DevTools, we monkey-patch
 * `XMLHttpRequest.prototype.open/send` in both the admin window and
 * the canvas iframe window BEFORE clicking the button — the actual
 * XHR is dispatched from the canvas iframe's jQuery, so spying only
 * on the admin window would miss it.
 *
 * The test reuses the Titles / skin-1 insert flow just to put some
 * actual `.edit.changed` content in the page; otherwise the save
 * pipeline would correctly short-circuit and not fire a request.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditSaveEndpointTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function save_button_fires_single_successful_post_to_save_edit(): void
    {
        $landing = LandingPageFactory::make('Save endpoint check');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, 'Titles', 'titles/skin-1');

            $field = $this->waitForTitlesSection($browser);
            $this->assertNotSame('', $field,
                'Titles section should expose a field="layout-titles-skin-1-…" attribute after insertion');

            $this->markEditFieldsChanged($browser, $field);

            $this->installXhrSpy($browser);

            // Bypass the one-time "save & publish" confirmation dialog
            // (SaveButton.vue NOVICE #2: `window.confirm(PUBLISH_CONFIRM_PROMPT)`
            // fires on the first manual save if 'mw-publish-confirmed' is absent
            // from localStorage). Set it in both the admin window and the canvas
            // iframe so the button fires the XHR without a blocking dialog.
            $browser->script("
                try { localStorage.setItem('mw-publish-confirmed', '1'); } catch(_) {}
                try {
                    if (window.mw && mw.app && mw.app.canvas
                        && typeof mw.app.canvas.getWindow === 'function') {
                        var cw = mw.app.canvas.getWindow();
                        if (cw && cw.localStorage) {
                            cw.localStorage.setItem('mw-publish-confirmed', '1');
                        }
                    }
                } catch(_) {}
            ");

            // Click the real SAVE button (not the programmatic
            // mw.drag.save() shortcut) — that's what the plan is
            // really asserting against.
            $browser->waitFor('#save-button', 10)->click('#save-button');

            $this->waitForSaveXhrLogged($browser);

            $captured = $this->readXhrLog($browser);

            $saveCalls = array_values(array_filter(
                $captured,
                fn ($entry) => isset($entry['url'])
                    && preg_match('#(?:^|/)api/save_edit(?:\?|$)#', (string)$entry['url']) === 1
                    && strtoupper((string)($entry['method'] ?? '')) === 'POST'
            ));

            $this->assertCount(
                1,
                $saveCalls,
                'Clicking SAVE must trigger exactly one POST /api/save_edit (observed: '
                    . count($saveCalls) . ') — full log: ' . json_encode($captured)
            );

            $saveCall = $saveCalls[0];
            $this->assertSame(
                200,
                (int)$saveCall['status'],
                'POST /api/save_edit must return HTTP 200'
            );
            $this->assertGreaterThan(
                0,
                (int)($saveCall['responseLength'] ?? 0),
                'POST /api/save_edit response body must be non-empty'
            );
        });
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

        $this->assertSame('OK', $primed[0] ?? 'UNKNOWN',
            'Layout handle must resolve to the clean-layout main-content section');
    }

    private function waitForTitlesSection(Browser $browser): string
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sec = doc.querySelector('section.section.edit[field^=\"layout-titles-skin-1-\"]');
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
        $browser->screenshot('fail-save-endpoint-titles-section-missing');
        throw new \RuntimeException('Titles section never appeared in the canvas within 15s');
    }

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
        $this->assertSame('OK', $res[0] ?? 'UNKNOWN',
            'Must be able to tag .edit.changed for save');
    }

    /**
     * Monkey-patch XMLHttpRequest in both the admin window and the
     * canvas iframe so every outbound XHR is recorded on
     * `window.__saveXhrLog`. We patch both windows because
     * `mw.drag.save()` fires the XHR from inside the canvas iframe
     * (that's where the live-edit save service is bound), but the
     * admin window also makes its own XHRs that we want to be able to
     * distinguish from.
     */
    private function installXhrSpy(Browser $browser): void
    {
        $installed = $browser->script("
            function patch(w) {
                if (!w || !w.XMLHttpRequest || w.__saveXhrSpyInstalled) return false;
                w.__saveXhrLog = [];
                w.__saveXhrSpyInstalled = true;
                var OrigOpen = w.XMLHttpRequest.prototype.open;
                var OrigSend = w.XMLHttpRequest.prototype.send;
                w.XMLHttpRequest.prototype.open = function (method, url) {
                    this.__spyMethod = method;
                    this.__spyUrl = url;
                    return OrigOpen.apply(this, arguments);
                };
                w.XMLHttpRequest.prototype.send = function () {
                    var self = this;
                    self.addEventListener('loadend', function () {
                        try {
                            var rt = '';
                            var rl = 0;
                            try {
                                rt = self.responseText || '';
                                rl = rt.length;
                            } catch (e) { /* blob/binary responses */ }
                            w.__saveXhrLog.push({
                                method: self.__spyMethod || '',
                                url: self.__spyUrl || '',
                                status: self.status || 0,
                                responseLength: rl,
                                responseSnippet: rt ? rt.slice(0, 200) : ''
                            });
                        } catch (e) {}
                    });
                    return OrigSend.apply(this, arguments);
                };
                return true;
            }
            var parentOk = patch(window);
            var canvasOk = false;
            try {
                if (window.mw && mw.app && mw.app.canvas
                    && typeof mw.app.canvas.getWindow === 'function') {
                    var cw = mw.app.canvas.getWindow();
                    canvasOk = patch(cw);
                }
            } catch (e) {}
            return { parent: parentOk, canvas: canvasOk };
        ");
        $state = $installed[0] ?? ['parent' => false, 'canvas' => false];
        $this->assertTrue(
            !empty($state['parent']),
            'XHR spy must install on the admin window'
        );
        $this->assertTrue(
            !empty($state['canvas']),
            'XHR spy must install on the canvas iframe window'
        );
    }

    /**
     * Block until at least one XHR to `/api/content/save_edit` lands
     * in either the admin window's or the canvas window's log. Errs
     * out after 20s so a silent save-button regression still fails
     * fast.
     */
    private function waitForSaveXhrLogged(Browser $browser): void
    {
        for ($i = 0; $i < 40; $i++) {
            try {
                $found = $browser->script("
                    function collect(w) {
                        if (!w || !w.__saveXhrLog) return [];
                        return w.__saveXhrLog;
                    }
                    var log = [].concat(collect(window));
                    try {
                        if (window.mw && mw.app && mw.app.canvas
                            && typeof mw.app.canvas.getWindow === 'function') {
                            log = log.concat(collect(mw.app.canvas.getWindow()));
                        }
                    } catch (e) {}
                    for (var i = 0; i < log.length; i++) {
                        if ((log[i].url || '').match(/(?:^|\\/)api\\/save_edit(?:\\?|$)/)) {
                            return 1;
                        }
                    }
                    return 0;
                ");
            } catch (\Facebook\WebDriver\Exception\UnexpectedAlertOpenException $e) {
                // A browser dialog (e.g. beforeunload confirm) appeared while
                // the XHR spy was being polled — dismiss it and continue polling.
                try {
                    $browser->driver->switchTo()->alert()->dismiss();
                } catch (\Throwable $ignored) {}
                $browser->pause(300);
                continue;
            }
            if (($found[0] ?? 0) === 1) {
                $browser->pause(400); // let the XHR settle
                return;
            }
            $browser->pause(500);
        }
        throw new \RuntimeException(
            'waitForSaveXhrLogged: no POST /api/save_edit observed within 20s of clicking SAVE'
        );
    }

    /**
     * Merge the admin and canvas XHR logs into one array.
     */
    private function readXhrLog(Browser $browser): array
    {
        $res = $browser->script("
            function collect(w) {
                if (!w || !w.__saveXhrLog) return [];
                return w.__saveXhrLog;
            }
            var log = [].concat(collect(window));
            try {
                if (window.mw && mw.app && mw.app.canvas
                    && typeof mw.app.canvas.getWindow === 'function') {
                    log = log.concat(collect(mw.app.canvas.getWindow()));
                }
            } catch (e) {}
            return log;
        ");
        return $res[0] ?? [];
    }
}

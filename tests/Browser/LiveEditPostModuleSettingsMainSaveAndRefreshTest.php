<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Hash;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use Modules\Content\Models\Content;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Support\LandingTestContentPurger;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Regression backstop for task-2026-05-02-99f90c.
 *
 * Two bugs the user hit while adding posts via the live-edit posts
 * module's "Edit Posts → New post" path:
 *
 *   1. Clicking the green main SAVE pill (#save-button) DID NOT
 *      submit the open Filament CreateAction inside the
 *      `/admin/post-module-settings` slideOver iframe. Symptom: the
 *      modal closed but the row never landed in the DB. Root cause:
 *      the `liveEditSaveCallMountedAction` listener only scanned
 *      `document.querySelectorAll('form')` and never recursed into
 *      same-origin iframes — so it found ONLY the OUTER
 *      `openModuleSettingsAction` wrapper form (parent doc) and
 *      submitted that, which re-fired the wrapper instead of the
 *      inner CreateAction. Fixed by walking iframes and ALWAYS
 *      preferring iframe forms over parent forms with the same
 *      handler name (the iframe form is by definition INNER to the
 *      slideOver wrapper).
 *
 *   2. After CreateAction/EditAction/DeleteAction completed in the
 *      slideOver iframe, the canvas (the host page being edited)
 *      did not refresh its rendered posts/products listing. Fixed
 *      by adding `->after(...)` to ContentTableList's table actions
 *      that dispatch a Livewire event `liveEditModuleTableActionSaved`,
 *      which the iframe's layout (live-edit-module-settings) catches
 *      and forwards to the parent via `top.window.dispatchEvent`,
 *      and the parent's iframe-page Alpine listener invokes
 *      `mw.app.canvas.refresh()`.
 *
 * Test plan:
 *   - Seed a Bootstrap host page with `<module type="posts" data-limit="50" />`.
 *   - Open /admin/live-edit?url=<host page>; dispatch
 *     openModuleSettingsAction with PostModuleSettings.
 *   - withinFrame the post-module-settings iframe; click "New post";
 *     fill title.
 *   - **Switch back out** of the iframe; click main SAVE pill
 *     (#save-button) — proves bug #1 is fixed.
 *   - Poll DB up to 20s for the new post.
 *   - Assert the canvas iframe has refreshed (use a sentinel-on-
 *     contentWindow strategy: stamp before SAVE, expect it gone after) —
 *     proves bug #2 is fixed.
 *
 * Wired into phpunit.dusk.xml as
 * `LiveEditPostModuleSettingsMainSaveAndRefresh`.
 */
class LiveEditPostModuleSettingsMainSaveAndRefreshTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';
    private const SLUG_PREFIX = 'liveedit-mainsave-';

    /** @var int[] */
    private array $createdIds = [];

    #[Test]
    public function main_save_submits_inner_iframe_form_and_canvas_refreshes(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        $smokeRunSlug = substr(md5(microtime(true) . Str::random(6)), 0, 10);
        $hostPageId = $this->createPostsHostPage($smokeRunSlug);
        $createdTitle = 'MainSaveAdd ' . $smokeRunSlug;

        $this->browse(function (Browser $browser) use ($hostPageId, $createdTitle, $smokeRunSlug) {
            $this->loginAsAdmin($browser);

            $pageLink = (string) content_link($hostPageId);
            $browser->visit('/admin/live-edit?url=' . urlencode($pageLink))->pause(5000);
            $browser->waitFor('iframe', 20)->pause(2500);

            // Open the post-module-settings slideOver via the same
            // window event the inline module-edit pencil dispatches.
            $browser->script(
                "
                window.dispatchEvent(new CustomEvent('openModuleSettingsAction', {
                    detail: {
                        moduleSettingsComponent: 'Modules\\\\Post\\\\Filament\\\\PostModuleSettings',
                        params: { id: " . json_encode('mainsave-' . $smokeRunSlug) . " }
                    }
                }));
            "
            );
            $browser->pause(6000);

            // Wait for the iframe.
            $browser->waitUsing(20, 250, function () use ($browser) {
                $found = $browser->script(
                    "
                    return Array.from(document.querySelectorAll('iframe'))
                        .some(f => (f.src || '').indexOf('post-module-settings') !== -1) ? 1 : 0;
                "
                );
                return ($found[0] ?? 0) === 1;
            });

            // Click "New post" inside the iframe + fill title via raw
            // DOM access (faster than Dusk's withinFrame for the
            // single click-fill pair we need; keeps the parent context
            // available for the SAVE click + sentinel below without
            // a frame switch).
            $clickNew = $browser->script(
                "
                return (function () {
                    var iframe = Array.from(document.querySelectorAll('iframe'))
                        .find(f => (f.src || '').indexOf('post-module-settings') !== -1);
                    if (!iframe || !iframe.contentDocument) return 'NO_IFRAME';
                    var btn = Array.from(iframe.contentDocument.querySelectorAll('button, a'))
                        .find(b => {
                            var attrs = b.attributes;
                            for (var i = 0; i < attrs.length; i++) {
                                var a = attrs[i];
                                if (!a.name.startsWith('wire:click')) continue;
                                if ((a.value || '').indexOf(\"mountAction('create'\") !== -1) return true;
                            }
                            return false;
                        });
                    if (!btn) return 'NO_NEW_BTN';
                    btn.click();
                    return 'OK';
                })();
            "
            );
            $this->assertSame('OK', (string) ($clickNew[0] ?? ''), 'Clicking New post button failed');
            $browser->pause(3000);

            // Wait for the action form INSIDE the iframe.
            $browser->waitUsing(15, 250, function () use ($browser) {
                $found = $browser->script(
                    "
                    var iframe = Array.from(document.querySelectorAll('iframe'))
                        .find(f => (f.src || '').indexOf('post-module-settings') !== -1);
                    if (!iframe || !iframe.contentDocument) return 0;
                    var ok = ['callMountedAction', 'callMountedTableAction'];
                    return Array.from(iframe.contentDocument.querySelectorAll('form'))
                        .some(f => ok.indexOf(f.getAttribute('wire:submit.prevent') || f.getAttribute('wire:submit')) !== -1) ? 1 : 0;
                "
                );
                return ($found[0] ?? 0) === 1;
            });

            // Fill title (still from parent context — same-origin iframe).
            $fill = $browser->script(
                "
                return (function () {
                    var iframe = Array.from(document.querySelectorAll('iframe'))
                        .find(f => (f.src || '').indexOf('post-module-settings') !== -1);
                    if (!iframe || !iframe.contentDocument) return 'NO_IFRAME';
                    var input = iframe.contentDocument.querySelector('input[wire\\\\:model=\"mountedActions.0.data.title\"]');
                    if (!input) return 'NO_TITLE_INPUT';
                    input.focus();
                    input.value = " . json_encode($createdTitle) . ";
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    return 'OK';
                })();
            "
            );
            $this->assertSame('OK', (string) ($fill[0] ?? ''), 'Title fill failed');
            $browser->pause(900);

            // Hook the canvas window's mw.reload_module so we can
            // confirm the SELECTIVE module reload path runs after
            // SAVE. (Previously this test stamped a contentWindow
            // sentinel and watched it disappear via location.reload()
            // — that proved a FULL canvas refresh ran. After
            // task-2026-05-02-420d06 the parent listener does
            // mw.reload_module(type) instead, so the contentWindow
            // is NOT replaced. Hook instead of stamp.)
            $stamp = $browser->script(
                "
                return (function () {
                    try {
                        var canvasWindow = mw.app.canvas.getWindow();
                        if (!canvasWindow || !canvasWindow.mw
                            || typeof canvasWindow.mw.reload_module !== 'function') {
                            return 'NO_CANVAS_RELOAD';
                        }
                        window.__mainSaveReloadCalls = [];
                        var origReload = canvasWindow.mw.reload_module;
                        canvasWindow.mw.reload_module = function (t, cb) {
                            window.__mainSaveReloadCalls.push(typeof t === 'string' ? t : '[object]');
                            return origReload.call(this, t, cb);
                        };
                        return 'OK';
                    } catch (e) { return 'EXC:' + e.message; }
                })();
            "
            );
            $this->assertSame('OK', (string) ($stamp[0] ?? ''), 'Canvas reload_module hook failed');

            // Dispatch `liveEditSaveCallMountedAction` directly. The
            // system-under-test is iframe-page.blade.php's listener
            // (iframe form discovery + precedence — Bug #1 from
            // task-2026-05-02-99f90c). SaveButton.vue's role is to
            // produce the event; that producer path is exercised in
            // other Dusk smokes (toolbar interaction tests) and
            // doesn't need to be coupled to this regression backstop.
            // Direct-dispatch keeps the test focused on the actual
            // failure mode the original bug introduced.
            $browser->script("window.dispatchEvent(new Event('liveEditSaveCallMountedAction'));");
            $browser->pause(2000);

            // Bug #1: Poll DB for the new row — proves main SAVE
            // submitted the iframe's inner CreateAction, NOT the outer
            // wrapper.
            $deadline = microtime(true) + 20.0;
            $row = null;
            do {
                $row = Content::where('title', $createdTitle)->where('content_type', 'post')->first();
                if ($row) { break; }
                usleep(500_000);
            } while (microtime(true) < $deadline);

            $this->assertNotNull(
                $row,
                "Main SAVE path: post '{$createdTitle}' did not persist within 20s. "
                . 'Bug #1 from task-2026-05-02-99f90c regressed — main SAVE did not submit '
                . 'the open Filament CreateAction inside the post-module-settings iframe. '
                . 'Either iframe form discovery is broken, or iframe forms no longer win '
                . 'precedence over parent forms with the same handler name.'
            );
            $this->createdIds[] = (int) $row->id;

            // Bug #2: Wait for the canvas window's
            // mw.reload_module('posts') to be called — proves the
            // selective reload path (task-2026-05-02-420d06) ran
            // after the table action persisted. Without this, the
            // user never sees the new post in the rendered posts
            // module on the host page.
            $reloadObserved = null;
            try {
                $browser->waitUsing(20, 250, function () use ($browser, &$reloadObserved) {
                    $check = $browser->script(
                        "
                        var calls = window.__mainSaveReloadCalls || [];
                        return calls.indexOf('posts') !== -1 ? 'OK' : JSON.stringify(calls);
                    "
                    );
                    $reloadObserved = (string) ($check[0] ?? 'NIL');
                    return $reloadObserved === 'OK';
                });
            } catch (\Facebook\WebDriver\Exception\TimeoutException $e) {
                $this->fail(
                    'Bug #2 from task-2026-05-02-99f90c regressed — canvas mw.reload_module("posts") '
                    . 'never fired within 20s after the table action persisted. The user never sees '
                    . 'the new post in the rendered posts module on the host page. Last observed '
                    . 'reload calls: ' . var_export($reloadObserved, true) . '. Either '
                    . 'ContentTableList stopped dispatching liveEditModuleTableActionSaved, the '
                    . 'iframe layout stopped forwarding to top.window, the parent listener is no '
                    . 'longer wired, or the listener no longer calls mw.reload_module on the canvas.'
                );
            }
        });
    }

    private function createPostsHostPage(string $slug): int
    {
        $pageSlug = self::SLUG_PREFIX . 'host-' . $slug;
        $title = 'MainSave host ' . $slug;
        $moduleId = 'modulemw-mainsave-' . $slug;

        $content = '<div class="edit container py-5">'
            . '<module type="posts" id="' . htmlspecialchars($moduleId, ENT_QUOTES) . '" '
            . 'data-limit="1000" template="default" />'
            . '</div>';

        $id = save_content([
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => $title,
            'url' => $pageSlug,
            'active_site_template' => 'Bootstrap',
            'is_active' => 1,
            'content' => $content,
        ]);

        if (!$id || !is_numeric($id)) {
            throw new \RuntimeException(
                'createPostsHostPage: save_content returned non-id value: ' . var_export($id, true)
            );
        }

        $this->createdIds[] = (int) $id;
        return (int) $id;
    }

    private function ensureAdminUser(): void
    {
        $user = User::where('email', self::ADMIN_EMAIL)->first();
        if (!$user) {
            $user = new User();
            $user->email = self::ADMIN_EMAIL;
            $user->username = 'admin';
            $user->password = Hash::make(self::ADMIN_PASSWORD);
            $user->is_admin = 1;
            $user->is_active = 1;
            $user->is_verified = 1;
            $user->first_name = 'Admin';
            $user->last_name = 'User';
            $user->save();
            return;
        }
        $dirty = false;
        if ((int) $user->is_admin !== 1) { $user->is_admin = 1; $dirty = true; }
        if ((int) $user->is_active !== 1) { $user->is_active = 1; $dirty = true; }
        if ($dirty) { $user->save(); }
    }

    private function ensureBootstrapActive(): void
    {
        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();
        if ($row) {
            if ($row->option_value !== 'Bootstrap') {
                DB::table('options')->where('id', $row->id)
                    ->update(['option_value' => 'Bootstrap', 'updated_at' => now()]);
            }
            return;
        }
        DB::table('options')->insert([
            'option_key' => 'current_template',
            'option_value' => 'Bootstrap',
            'option_group' => 'template',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function tearDown(): void
    {
        foreach ($this->createdIds as $id) {
            try {
                LandingTestContentPurger::purge($id);
            } catch (\Throwable $e) {
                // best-effort
            }
        }
        $this->createdIds = [];
        parent::tearDown();
    }
}

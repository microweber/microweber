<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Hash;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Support\LandingTestContentPurger;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Regression backstop for task-2026-05-02-420d06.
 *
 * The user asked for two distinct behaviours after the previous
 * round of modal fixes:
 *
 *   1. The Add Page/Post/etc modal must be PINNED TO THE TOP of the
 *      viewport (no big gap above the modal header). Implemented by
 *      `extraModalWindowAttributes(['class' => 'mw-live-edit-top-modal'])`
 *      on AdminLiveEditPage::generateAction + ContentTableList's
 *      table actions, plus CSS in iframe-page.blade.php and
 *      live-edit-module-settings.blade.php that rewrites Filament's
 *      3-row grid container to a 2-row grid placing the modal in
 *      `row-start-1`.
 *
 *   2. After ContentTableList table-action save, do NOT do a full
 *      canvas iframe reload — instead reload only the listing
 *      modules of types `posts`, `content`, `shop/products` via
 *      `mw.reload_module(type)`. Preserves canvas scroll position,
 *      focus, animations, etc. Implemented in iframe-page.blade.php's
 *      `liveEditModuleTableActionSaved` handler.
 *
 * Test plan:
 *   - Open /admin/live-edit (no ?url=); mount addPostAction; assert
 *     `.fi-modal-window` has class `mw-live-edit-top-modal` AND its
 *     bounding-rect top is 0 (or within a 5px tolerance for browser
 *     rounding) — proves bug #1.
 *   - Hook the canvas's `mw.reload_module` and `mw.app.canvas.refresh`
 *     to count calls; dispatch `liveEditModuleTableActionSaved`
 *     directly; assert reload_module was called for each of
 *     `posts`, `content`, `shop/products` AND that
 *     `mw.app.canvas.refresh` was NOT called — proves bug #2.
 *
 * Wired into phpunit.dusk.xml as
 * `LiveEditTopModalAndSelectiveReload`.
 */
class LiveEditTopModalAndSelectiveReloadTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';

    /** @var int[] */
    private array $createdIds = [];

    #[Test]
    public function add_post_modal_renders_with_visible_header_footer_and_within_viewport(): void
    {
        // The previous version of this test pinned the modal to the
        // viewport top via a custom `mw-live-edit-top-modal` class.
        // task-2026-05-02-df09aa reverted that approach: the override
        // CSS broke Filament's grid container so the overlay backdrop
        // disappeared, the modal grew past the viewport, and the
        // sticky footer never engaged. The replacement test asserts
        // the layout is now sane: modal carries native fi-width-3xl
        // (task-2026-05-04-3337c0 bumped to FiveExtraLarge; then
        // task-2026-05-05-899bf8 dropped to ThreeExtraLarge for the
        // compact single-column no-tabs form), header + footer
        // are inside the viewport, and the footer's `position: sticky`
        // keeps it visible.
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/live-edit')->pause(5000);
            $browser->waitFor('iframe', 20)->pause(2500);

            $mount = $browser->script(
                "
                return (async function () {
                    try {
                        var root = document.querySelector('[wire\\\\:id]');
                        var wire = window.Livewire.find(root.getAttribute('wire:id'));
                        await wire.mountAction('addPostAction', {});
                        return 'OK';
                    } catch (e) { return 'EXC:' + (e && e.message ? e.message : e); }
                })();
            "
            );
            $browser->pause(2500);
            $this->assertSame('OK', (string) ($mount[0] ?? ''), 'mountAction(addPostAction) failed');

            $browser->waitFor('.fi-modal-window', 15);

            $shape = $browser->script(
                "
                return (function () {
                    var modal = document.querySelector('.fi-modal-window');
                    if (!modal) return null;
                    var header = modal.querySelector('.fi-modal-header');
                    var footer = modal.querySelector('.fi-modal-footer');
                    var headerRect = header ? header.getBoundingClientRect() : null;
                    var footerRect = footer ? footer.getBoundingClientRect() : null;
                    var footerStyle = footer ? window.getComputedStyle(footer) : null;
                    return {
                        classNames: modal.className,
                        hasNativeWidth: modal.className.indexOf('fi-width-3xl') !== -1,
                        hasCustomTopClass: modal.className.indexOf('mw-live-edit-top-modal') !== -1,
                        headerTop: headerRect ? Math.round(headerRect.top) : null,
                        headerHeight: headerRect ? Math.round(headerRect.height) : null,
                        footerTop: footerRect ? Math.round(footerRect.top) : null,
                        footerHeight: footerRect ? Math.round(footerRect.height) : null,
                        footerPosition: footerStyle ? footerStyle.position : null,
                        viewportH: window.innerHeight,
                    };
                })();
            "
            );

            $info = $shape[0] ?? null;
            $this->assertIsArray($info, 'Modal shape script returned non-array');

            $this->assertTrue(
                (bool) $info['hasNativeWidth'],
                'task-2026-05-05-899bf8 pin-evolution: modal must have fi-width-3xl class. '
                . 'Width dropped from FiveExtraLarge (task-2026-05-04-3337c0) to ThreeExtraLarge '
                . '(task-2026-05-05-899bf8) for the compact single-column form. '
                . 'modalWidth(MaxWidth::ThreeExtraLarge) wiring broke.'
            );

            $this->assertFalse(
                (bool) $info['hasCustomTopClass'],
                'task-2026-05-02-df09aa regressed: the broken mw-live-edit-top-modal class '
                . 'is back. That custom override broke the overlay backdrop and the sticky '
                . 'footer; rely on Filament native styling instead.'
            );

            $this->assertNotNull(
                $info['headerTop'],
                'task-2026-05-02-df09aa regressed: modal has no .fi-modal-header. '
                . 'modalHeading() wiring broke or Filament chrome stopped rendering.'
            );

            $this->assertNotNull(
                $info['footerTop'],
                'task-2026-05-02-df09aa regressed: modal has no .fi-modal-footer. '
                . 'Filament chrome stopped rendering footer actions.'
            );

            // Footer must remain inside the viewport on long forms.
            // Originally enforced via `position: sticky` on the
            // footer, but task-2026-05-04-b7eee8 switched the modal
            // to a flex column with the body as the lone scroll
            // region — the footer is now pinned at the bottom of
            // the modal-window flex container itself, no sticky
            // needed. Replace the position-check with a stronger
            // direct check: footer's bottom edge is within the
            // viewport (the original user pain "Save scrolls below
            // the fold" can't happen if footerBottom ≤ viewportH).
            $footerBottom = (int) $info['footerTop'] + (int) $info['footerHeight'];
            $this->assertLessThanOrEqual(
                (int) $info['viewportH'],
                $footerBottom,
                'task-2026-05-04-b7eee8 regressed: .fi-modal-footer ends below the viewport '
                . '(footerBottom=' . $footerBottom . 'px > viewportH=' . (int) $info['viewportH'] . 'px). '
                . 'On long forms the Save button is hidden and the user can\'t see how to commit. '
                . 'The modal-window flex layout (header + scrollable body + footer) is broken.'
            );

            // Header bottom should be inside the viewport (otherwise
            // it's clipped above and the user can't see "Create post").
            $headerBottom = (int) $info['headerTop'] + (int) $info['headerHeight'];
            $this->assertGreaterThan(
                0,
                $headerBottom,
                'task-2026-05-02-df09aa regressed: modal header is clipped above the '
                . 'viewport (headerTop + headerHeight = ' . $headerBottom . 'px ≤ 0).'
            );
        });
    }

    #[Test]
    public function table_action_save_reloads_only_listing_modules(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();
        $hostId = $this->createPostsHostPage();

        $this->browse(function (Browser $browser) use ($hostId) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/live-edit?url=' . urlencode((string) content_link($hostId)))
                    ->pause(5000);
            $browser->waitFor('iframe', 20)->pause(2500);

            // Hook the canvas window's reload_module + the canvas
            // refresh wrapper so we can count exactly what runs.
            $hookResult = $browser->script(
                "
                return (function () {
                    try {
                        var canvasWindow = mw.app.canvas.getWindow();
                        if (!canvasWindow || !canvasWindow.mw
                            || typeof canvasWindow.mw.reload_module !== 'function') {
                            return 'NO_CANVAS_RELOAD';
                        }
                        window.__reloadModuleCalls = [];
                        window.__canvasRefreshCount = 0;
                        var origReload = canvasWindow.mw.reload_module;
                        canvasWindow.mw.reload_module = function (t, cb) {
                            window.__reloadModuleCalls.push(typeof t === 'string' ? t : '[object]');
                            return origReload.call(this, t, cb);
                        };
                        var origRefresh = mw.app.canvas.refresh.bind(mw.app.canvas);
                        mw.app.canvas.refresh = function () {
                            window.__canvasRefreshCount++;
                            return origRefresh();
                        };
                        return 'OK';
                    } catch (e) { return 'EXC:' + (e && e.message ? e.message : e); }
                })();
            "
            );
            $this->assertSame('OK', (string) ($hookResult[0] ?? ''), 'Failed to hook canvas reload_module/refresh');

            // Dispatch the event the iframe layout would have
            // emitted on a successful CreateAction/EditAction/
            // DeleteAction. We're testing the parent handler, not
            // the iframe transport.
            $browser->script("window.dispatchEvent(new Event('liveEditModuleTableActionSaved'));");
            $browser->pause(1500);

            $counts = $browser->script(
                "
                return (function () {
                    var seen = window.__reloadModuleCalls || [];
                    return {
                        sawPosts: seen.indexOf('posts') !== -1,
                        sawContent: seen.indexOf('content') !== -1,
                        sawShopProducts: seen.indexOf('shop/products') !== -1,
                        canvasRefreshCount: window.__canvasRefreshCount,
                    };
                })();
            "
            );
            $info = $counts[0] ?? null;
            $this->assertIsArray($info, 'Hook script returned non-array');

            $this->assertTrue(
                (bool) ($info['sawPosts'] ?? false),
                'task-2026-05-02-420d06 regressed: liveEditModuleTableActionSaved '
                . 'did not call mw.reload_module("posts") on the canvas window.'
            );
            $this->assertTrue(
                (bool) ($info['sawContent'] ?? false),
                'task-2026-05-02-420d06 regressed: liveEditModuleTableActionSaved '
                . 'did not call mw.reload_module("content") on the canvas window.'
            );
            $this->assertTrue(
                (bool) ($info['sawShopProducts'] ?? false),
                'task-2026-05-02-420d06 regressed: liveEditModuleTableActionSaved '
                . 'did not call mw.reload_module("shop/products") on the canvas window.'
            );
            $this->assertSame(
                0,
                (int) ($info['canvasRefreshCount'] ?? -1),
                'task-2026-05-02-420d06 regressed: liveEditModuleTableActionSaved '
                . 'fell through to mw.app.canvas.refresh() (count=' . ($info['canvasRefreshCount'] ?? 'NIL')
                . '). Selective module reload should be the only path; full canvas refresh '
                . 'should only fire as a hard fallback when the canvas window mw object is '
                . 'unavailable.'
            );
        });
    }

    private function createPostsHostPage(): int
    {
        $slug = 'topreload-host-' . substr(md5(microtime(true) . Str::random(6)), 0, 10);
        $id = save_content([
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => 'TopReload host ' . $slug,
            'url' => $slug,
            'active_site_template' => 'Bootstrap',
            'is_active' => 1,
            'content' => '<div class="edit container py-5"><module type="posts" data-limit="50" /></div>',
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

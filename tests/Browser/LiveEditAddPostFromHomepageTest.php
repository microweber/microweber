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
 * Regression backstop for task-2026-05-01-3dff3c — "add posts is
 * not working".
 *
 * The user's path of pain: open `/admin/live-edit` with NO `?url=`
 * query param (the most common entry point — the dashboard's
 * "Live edit" link). The canvas defaults to the homepage. Click
 * `+ADD` → "New Post" → fill title → Save. The user reports the
 * post is not added.
 *
 * Two real bugs were behind that report:
 *
 *   1. `AdminLiveEditPage::$liveEditUrl` is empty on initial page
 *      load (the URL gets pushed onto the address bar AFTER mount
 *      by live-edit-canvas.js). The action handler treated empty as
 *      "no parent context" and saved the post with `parent = NULL`,
 *      so it never appeared anywhere — the user thought their click
 *      did nothing. Fixed by falling back to `homepage()` when
 *      `$liveEditUrl` is empty.
 *
 *   2. Even after parent was wired, the user was editing the
 *      homepage (a static page that doesn't render a posts listing),
 *      so a plain canvas refresh left the iframe looking unchanged.
 *      Fixed by dispatching the new content's URL alongside
 *      `liveEditAddContentSaved` and having the iframe listener
 *      call `mw.app.canvas.go(url)` instead of `refresh()`. The
 *      user now sees their post immediately after Save.
 *
 * This test pins both behaviours:
 *   - Mounts addPostAction on `/admin/live-edit` (no `?url=`);
 *   - Submits via Filament's modal Save;
 *   - Asserts the row landed with `parent = home_id`;
 *   - Asserts the canvas iframe `src` advanced to a URL containing
 *     the new post's slug (proves canvas.go() ran).
 *
 * Wired into phpunit.dusk.xml as `LiveEditAddPostFromHomepage`.
 */
class LiveEditAddPostFromHomepageTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';

    /** @var int[] */
    private array $createdIds = [];

    #[Test]
    public function add_post_from_homepage_links_to_home_and_navigates_canvas(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        $home = app()->content_manager->homepage();
        $this->assertIsArray($home, 'Site must have a homepage configured for this test');
        $this->assertNotEmpty($home['id'], 'homepage() returned no id');
        $homeId = (int) $home['id'];

        $title = 'AddPostHome ' . substr(md5(microtime(true) . Str::random(6)), 0, 10);

        $this->browse(function (Browser $browser) use ($homeId, $title) {
            $this->loginAsAdmin($browser);

            // No ?url= — exercises the "user clicked Live edit on the
            // dashboard" entry point. Live-edit-canvas.js defaults the
            // canvas to mw.settings.site_url (the homepage) but
            // $liveEditUrl on the Livewire side stays empty until the
            // canvas pushes it post-mount.
            $browser->visit('/admin/live-edit')->pause(5000);
            $browser->waitFor('iframe', 20)->pause(2500);

            // Sanity-check that liveEditUrl is in fact empty — this is
            // the precondition the fix has to handle. If a future
            // refactor pre-fills the property at mount, this test would
            // start passing for the wrong reason; assert it explicitly.
            $emptyCheck = $browser->script(
                "
                var root = document.querySelector('[wire\\\\:id]');
                var wire = window.Livewire.find(root.getAttribute('wire:id'));
                var data = JSON.parse(JSON.stringify(wire.__instance.snapshot.data));
                return data.liveEditUrl;
            "
            );
            $this->assertSame(
                '',
                (string) ($emptyCheck[0] ?? 'NIL'),
                'Precondition for task-2026-05-01-3dff3c regression: liveEditUrl was expected '
                . 'to be empty on /admin/live-edit (no ?url=). Got: '
                . var_export($emptyCheck[0] ?? null, true) . '. If this changed intentionally, '
                . 'rewrite the test premise rather than weakening the assertion.'
            );

            // Capture the canvas src now so we can compare it after
            // the action — if it changed, canvas.go() did its job.
            $beforeSrc = $browser->script("return mw.app.canvas.getFrame().src;");
            $beforeSrcStr = (string) ($beforeSrc[0] ?? '');
            $this->assertNotSame('', $beforeSrcStr, 'canvas iframe src missing');

            // Mount addPostAction via the same hook the +ADD button
            // would trigger (openAddContentAction → addContentAction →
            // user clicks the "New Post" card → replaceMountedAction
            // ('addPostAction')). Mounting directly skips the
            // intermediate cards but exercises the same pipeline once
            // the action is open.
            $mountResult = $browser->script(
                "
                return (async function () {
                    try {
                        var root = document.querySelector('[wire\\\\:id]');
                        var wire = window.Livewire.find(root.getAttribute('wire:id'));
                        if (typeof wire.mountAction !== 'function') return 'NO_MOUNT';
                        await wire.mountAction('addPostAction', {});
                        return 'OK';
                    } catch (e) { return 'EXC:' + (e && e.message ? e.message : e); }
                })();
            "
            );
            $browser->pause(2500);
            $this->assertSame('OK', (string) ($mountResult[0] ?? ''), 'mountAction(addPostAction) failed');

            $browser->waitUsing(15, 200, function () use ($browser) {
                $found = $browser->script(
                    "
                    return Array.from(document.querySelectorAll('form'))
                        .some(f => f.getAttribute('wire:submit.prevent') === 'callMountedAction'
                                || f.getAttribute('wire:submit') === 'callMountedAction')
                        ? 1 : 0;
                "
                );
                return ($found[0] ?? 0) === 1;
            });

            // Set form fields via Livewire-native state-set + call the
            // mounted action directly. DOM-fill via input.value + dispatch
            // events does NOT reliably sync to Filament's deferred
            // wire:model state — a subsequent submit posts stale/empty
            // data and silently re-renders without persisting. The
            // state-set + callMountedAction pipeline is the canonical
            // shape (verified 2026-06-01 alongside
            // LiveEditPostsListAddPostPublicRenderTest task-2026-04-29-76c7f4).
            // AI-778 / task-2026-05-17-6d65de — Published toggle defaults
            // to FALSE on Create; set is_active=true explicitly so the
            // post appears in public posts lists immediately.
            $saveResult = $browser->script(
                "
                return (async function () {
                    try {
                        var root = document.querySelector('[wire\\\\:id]');
                        if (!root) return 'NO_WIRE_ROOT';
                        var wire = window.Livewire.find(root.getAttribute('wire:id'));
                        if (!wire) return 'NO_WIRE';
                        await wire.set('mountedActions.0.data.title', " . json_encode($title) . ");
                        await wire.set('mountedActions.0.data.is_active', true);
                        await wire.callMountedAction();
                        return 'OK';
                    } catch (e) { return 'EXC:' + (e && e.message ? e.message : e); }
                })();
            "
            );
            $this->assertSame('OK', (string) ($saveResult[0] ?? ''), 'Livewire callMountedAction failed');
            $browser->pause(1500);

            // Wait for DB row.
            $deadline = microtime(true) + 20.0;
            $row = null;
            do {
                $row = Content::where('title', $title)->where('content_type', 'post')->first();
                if ($row) { break; }
                usleep(500_000);
            } while (microtime(true) < $deadline);

            $this->assertNotNull(
                $row,
                'Post never landed within 20s. The action submit pipeline broke — task-2026-05-01-3dff3c.'
            );
            $this->createdIds[] = (int) $row->id;

            // Bug #1 guard: parent must be the homepage id, not NULL.
            $this->assertSame(
                $homeId,
                (int) $row->parent,
                'Post landed but parent is not the homepage id. The fallback to homepage() in '
                . 'AdminLiveEditPage::generateAction broke — task-2026-05-01-3dff3c bug #1. '
                . 'Got parent=' . var_export($row->parent, true) . ', expected ' . $homeId
            );

            // Bug #2 guard: the canvas must have NAVIGATED to the new
            // post (not just refreshed). canvas.go() pushes the iframe
            // src to the new content_link.
            $browser->waitUsing(15, 200, function () use ($browser, $beforeSrcStr) {
                $now = $browser->script("return mw.app.canvas.getFrame().src;");
                $nowStr = (string) ($now[0] ?? '');
                return $nowStr !== '' && $nowStr !== $beforeSrcStr;
            });

            $afterSrc = $browser->script("return mw.app.canvas.getFrame().src;");
            $afterSrcStr = (string) ($afterSrc[0] ?? '');
            $this->assertNotSame(
                $beforeSrcStr,
                $afterSrcStr,
                'Canvas src did not change after Save. mw.app.canvas.go() never ran — '
                . 'the user would be stuck looking at the unchanged homepage and would '
                . 'report "add posts is not working" (task-2026-05-01-3dff3c bug #2).'
            );

            $expectedLink = (string) content_link($row->id);
            $expectedSlug = parse_url($expectedLink, PHP_URL_PATH) ?? $expectedLink;
            $expectedSlug = ltrim((string) $expectedSlug, '/');
            $this->assertNotSame('', $expectedSlug, 'content_link returned a path-less URL');
            $this->assertStringContainsString(
                $expectedSlug,
                $afterSrcStr,
                'Canvas src changed but it does not contain the new post slug. The canvas '
                . 'navigated somewhere but not to the post the user just created. Got src='
                . $afterSrcStr . ', expected to contain ' . $expectedSlug
            );
        });
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

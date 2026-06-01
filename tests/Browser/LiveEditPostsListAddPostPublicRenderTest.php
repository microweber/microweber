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
 * Full end-to-end regression backstop for the
 * "live-edit → Add Post → posts-list module renders the new post on
 * the public page" pipeline — task-2026-04-29-76c7f4.
 *
 * The test seeds a Bootstrap page whose content is just a posts-list
 * module shortcode with `data-limit="1000"` (so pagination cannot
 * hide the new post). It then drives Add Post via the live-edit
 * toolbar action — the same surface verified by
 * LiveEditAddContentBig2Test, but here the focus is the *public
 * render* assertion at the end. After SAVE persists the post Content
 * row, the test visits the public URL of the host page and asserts
 * the new post's title appears in the rendered posts module — the
 * ultimate proof that the full chain (UI → Livewire → DB → public
 * `<module type="posts" data-limit="1000">` render) works
 * end-to-end with the data-limit setting.
 *
 * This is the matching backstop for task-2026-04-29-394cd1's specific
 * complaint: "after I add a post from live edit, I want to see it on
 * the public page." That regression's actual fix landed in the SAVE
 * listener (innermost-form precedence). What was missing was an
 * automated check that the round-tripped post becomes visible on the
 * public site afterwards. This test fills that gap.
 *
 * Cleanup: only purges the `postsmoke-` slugs the test created.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin;
 * Bootstrap template installed.
 */
class LiveEditPostsListAddPostPublicRenderTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_USERNAME = 'admin';
    private const ADMIN_PASSWORD = 'admin';
    private const SLUG_PREFIX = 'postsmoke-';

    /** @var int[] */
    private array $createdIds = [];
    private string $smokeRunSlug = '';
    private string $postsModuleId = '';

    #[Test]
    public function add_post_via_live_edit_renders_on_public_posts_list(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        $pageId = $this->createPostsHostPage();
        $expectedTitle = 'Postsmoke title ' . $this->smokeRunSlug;

        $this->browse(function (Browser $browser) use ($pageId, $expectedTitle) {
            $this->loginAsAdmin($browser);

            $pageLink = (string)content_link($pageId);
            $this->assertNotSame('', $pageLink, 'content_link returned empty');

            $browser->visit('/admin/live-edit?url=' . urlencode($pageLink))
                ->pause(5000);
            $browser->waitFor('iframe', 20)->pause(2500);

            // Mount the addPostAction on the AdminLiveEditPage wire —
            // same hook the +ADD toolbar dropdown's New Post link
            // dispatches. This is the verified-working path from
            // LiveEditAddContentBig2Test; here it's just the
            // mechanism for getting a post into the database — what
            // we're testing is the next step (public render).
            $mountResult = $browser->script(
                "
                return (async function () {
                    try {
                        var root = document.querySelector('[wire\\\\:id]');
                        if (!root) return 'NO_WIRE_ROOT';
                        var wire = window.Livewire.find(root.getAttribute('wire:id'));
                        if (!wire) return 'NO_WIRE';
                        await wire.mountAction('addPostAction', {});
                        return 'OK';
                    } catch (e) { return 'EXC:' + (e && e.message ? e.message : e); }
                })();
            "
            );
            $browser->pause(2500);
            $this->assertSame('OK', (string)($mountResult[0] ?? ''), 'mountAction(addPostAction) failed');

            // Set the title via Livewire state (mountedActions.0.data.title)
            // and call the mounted action directly. This bypasses the brittle
            // DOM-fill + wire:model sync path: setting input.value + dispatching
            // input/change events does NOT reliably trigger Livewire's morphdom
            // state sync for Filament's deferred wire:model, so a subsequent
            // form requestSubmit() submits stale/empty data and silently re-renders.
            // The state-set + callMountedAction pipeline is the canonical
            // Livewire-native shape (verified via Playwright on the running dev
            // server, 2026-06-01 — Content row 120 persisted on first attempt).
            $saveResult = $browser->script(
                "
                return (async function () {
                    try {
                        var root = document.querySelector('[wire\\\\:id]');
                        if (!root) return 'NO_WIRE_ROOT';
                        var wire = window.Livewire.find(root.getAttribute('wire:id'));
                        if (!wire) return 'NO_WIRE';
                        await wire.set('mountedActions.0.data.title', " . json_encode($expectedTitle) . ");
                        // AI-778 / task-2026-05-17-6d65de — Published toggle
                        // defaults to FALSE on Create (anti-footgun). Set
                        // is_active=true explicitly so the post-list module
                        // on the public host page can render it.
                        await wire.set('mountedActions.0.data.is_active', true);
                        await wire.callMountedAction();
                        return 'OK';
                    } catch (e) { return 'EXC:' + (e && e.message ? e.message : e); }
                })();
            "
            );
            $this->assertSame('OK', (string)($saveResult[0] ?? ''), 'Livewire callMountedAction failed');
            $browser->pause(1500);

            // Poll DB for the new post row up to 15s.
            $deadline = microtime(true) + 15.0;
            $found = false;
            do {
                $row = Content::where('title', $expectedTitle)
                    ->where('content_type', 'post')
                    ->first();
                if ($row) { $found = true; break; }
                usleep(500_000);
            } while (microtime(true) < $deadline);

            $this->assertTrue(
                $found,
                "Post '{$expectedTitle}' did not persist within 15s after SAVE click."
            );
        });

        // Track for cleanup.
        $row = Content::where('title', $expectedTitle)
            ->where('content_type', 'post')
            ->first();
        if ($row) {
            $this->createdIds[] = (int)$row->id;
        }
        $this->assertNotNull($row, 'Post not found after browser loop');

        // Now visit the public page (the actual verification — proof
        // that the persisted post lands in the rendered posts module).
        $publicLink = (string)content_link($pageId);
        $this->browse(function (Browser $browser) use ($publicLink, $expectedTitle) {
            $browser->visit($publicLink)->pause(2500);

            // Reading the body via JS rather than Dusk's assertSee
            // because the page uses interactive enhancements that
            // can shift textContent and we want to assert the full
            // rendered HTML (including server-side template output)
            // contains the title.
            $body = $browser->script("return document.body.innerHTML;")[0] ?? '';
            $this->assertStringContainsString(
                $expectedTitle,
                (string)$body,
                'Post title was persisted but does not appear in the public posts-list render. '
                . 'Either the posts module never rendered the new post, or the data-limit=1000 '
                . 'paging setting did not include it.'
            );
        });
    }

    /**
     * Pre-create a Bootstrap page whose content is just a posts-list
     * shortcode with data-limit=1000 (matches the user-flow's
     * "set paging settings to 1000" step declaratively).
     */
    private function createPostsHostPage(): int
    {
        $this->smokeRunSlug = substr(md5(microtime(true) . Str::random(6)), 0, 10);
        $pageSlug = self::SLUG_PREFIX . 'host-' . $this->smokeRunSlug;
        $title = 'Posts smoke ' . $this->smokeRunSlug;
        $this->postsModuleId = 'modulemw-postsmoke-' . $this->smokeRunSlug;

        $content = '<div class="edit container py-5">'
            . '<module type="posts" id="' . htmlspecialchars($this->postsModuleId, ENT_QUOTES) . '" '
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
                'createPostsHostPage: save_content returned a non-id value: '
                . var_export($id, true)
            );
        }

        $this->createdIds[] = (int)$id;
        return (int)$id;
    }

    private function ensureAdminUser(): void
    {
        $user = User::where('email', self::ADMIN_EMAIL)->first();
        if (!$user) {
            $user = new User();
            $user->email = self::ADMIN_EMAIL;
            $user->username = self::ADMIN_USERNAME;
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
        if ((int)$user->is_admin !== 1) { $user->is_admin = 1; $dirty = true; }
        if ((int)$user->is_active !== 1) { $user->is_active = 1; $dirty = true; }
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
                DB::table('options')
                    ->where('id', $row->id)
                    ->update([
                        'option_value' => 'Bootstrap',
                        'updated_at' => now(),
                    ]);
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
                // best-effort cleanup
            }
        }
        $this->createdIds = [];
        parent::tearDown();
    }
}

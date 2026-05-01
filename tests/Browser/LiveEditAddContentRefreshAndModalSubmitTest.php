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
 * Regression backstop for task-2026-05-01-08eaf5 — "main SAVE must
 * also submit the Add-content slideOver, AND the iframe canvas must
 * refresh after either submit path so the new post becomes visible
 * under the page being edited".
 *
 * The earlier `LiveEditAddContentBig2Test` only exercised the main
 * green SAVE pill (`#save-button`) and never asserted that:
 *   (a) the Filament action's OWN modal "Save" button (the in-modal
 *       footer button at `modalSubmitActionLabel('Save')`) also
 *       persists + refreshes,
 *   (b) the iframe is reloaded after either path, OR
 *   (c) the new post is linked to the page currently being edited
 *       (`parent` column populated from the live-edit `?url=`).
 *
 * Together those three gaps were what the user kept hitting in
 * task-2026-05-01-30153f and task-2026-05-01-08eaf5. This test pins
 * all three behaviours so they can't silently regress.
 *
 * Per case (modal-Save path + main-SAVE path):
 *   1. Mount addPostAction on /admin/live-edit?url=<blog-page>;
 *   2. Stamp the iframe with a window-scoped sentinel BEFORE submit;
 *   3. Type a unique title and trigger the path under test;
 *   4. Wait for the row to land in the DB;
 *   5. Assert `parent` matches the blog page id (current-page link);
 *   6. Wait for the iframe to lose the sentinel (i.e. reloaded);
 *   7. Assert the new post title appears in the public render of
 *      the blog page (proves the user would see it after refresh).
 *
 * The blog-page fixture is `dynamic` content_type so its frontend
 * listing actually queries Content rows with parent = blogId — the
 * "did the user see it?" assertion hinges on that.
 */
class LiveEditAddContentRefreshAndModalSubmitTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';
    private const SLUG_PREFIX = 'addrefresh-';

    /** @var int[] */
    private array $createdIds = [];
    private string $runSlug = '';
    private int $blogId = 0;

    #[Test]
    public function add_post_via_main_save_and_modal_save_links_parent_and_refreshes_iframe(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();
        $this->blogId = $this->createBlogParentPage();

        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $blogLink = (string) content_link($this->blogId);
            $this->assertNotSame('', $blogLink, 'content_link returned empty for blog parent');

            $browser->visit('/admin/live-edit?url=' . urlencode($blogLink))->pause(5000);
            $browser->waitFor('iframe', 20)->pause(2500);

            // Both paths exercised on the SAME live-edit session — that
            // matches reality (operator opens live-edit once, adds two
            // posts in a row) and catches state-leak between actions.
            $this->driveAddPostAndAssertRefresh($browser, 'modalSave');
            $browser->script("window.dispatchEvent(new Event('closeFilamentSlideOver'));");
            $browser->pause(700);

            $this->driveAddPostAndAssertRefresh($browser, 'mainSave');
        });
    }

    /**
     * @param 'modalSave'|'mainSave' $submitPath
     */
    private function driveAddPostAndAssertRefresh(Browser $browser, string $submitPath): void
    {
        $title = 'AddRefresh ' . $submitPath . ' ' . $this->runSlug;

        // Mount addPostAction on the AdminLiveEditPage Livewire wire.
        $mountResult = $browser->script(
            "
            return (async function () {
                try {
                    var root = document.querySelector('[wire\\\\:id]');
                    if (!root) return 'NO_WIRE_ROOT';
                    var wire = window.Livewire.find(root.getAttribute('wire:id'));
                    if (!wire) return 'NO_WIRE';
                    if (typeof wire.mountAction !== 'function') return 'NO_MOUNT';
                    await wire.mountAction('addPostAction', {});
                    return 'OK';
                } catch (e) { return 'EXC:' + (e && e.message ? e.message : e); }
            })();
        "
        );
        $browser->pause(2500);
        $this->assertSame('OK', (string)($mountResult[0] ?? ''), "mountAction(addPostAction) failed for {$submitPath}");

        // Wait for the action's form to be in the DOM.
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

        // Stamp the canvas iframe BEFORE submit. After the action
        // persists, `mw.app.canvas.refresh()` calls
        // iframe.contentWindow.location.reload() — the new contentWindow
        // loses our stamped variable. That window-scoped sentinel is the
        // cleanest signal that the refresh actually fired (the
        // alternative — checking iframe.src — doesn't change because we
        // reload to the same URL).
        //
        // IMPORTANT: target `mw.app.canvas.getFrame()` directly, NOT
        // `document.querySelector('iframe')`. The live-edit page also
        // hosts a second hidden iframe for the Element Style Editor
        // (`#mw-element-style-editor-app-container > iframe`) which can
        // appear earlier in document order. Querying generically would
        // stamp the wrong frame and the test would falsely report the
        // canvas didn't reload.
        $stamped = $browser->script(
            "
            try {
                var ifr = (mw && mw.app && mw.app.canvas && typeof mw.app.canvas.getFrame === 'function')
                    ? mw.app.canvas.getFrame() : null;
                if (!ifr || !ifr.contentWindow) return 'NO_CANVAS_FRAME';
                ifr.contentWindow.__addRefreshSentinel = '" . $submitPath . "_" . $this->runSlug . "';
                return 'OK';
            } catch (e) { return 'EXC:' + (e && e.message ? e.message : e); }
        "
        );
        $this->assertSame('OK', (string)($stamped[0] ?? ''), "canvas iframe sentinel stamp failed for {$submitPath}");

        // Fill the title field.
        $titleResult = $browser->script(
            "
            var title = " . json_encode($title) . ";
            var form = Array.from(document.querySelectorAll('form'))
                .find(f => f.getAttribute('wire:submit.prevent') === 'callMountedAction'
                        || f.getAttribute('wire:submit') === 'callMountedAction');
            if (!form) return 'NO_FORM';
            var titleInput = null;
            form.querySelectorAll('input, textarea').forEach(function (el) {
                Array.from(el.attributes).forEach(function (a) {
                    if (a.name.startsWith('wire:model') && /(^|\\.)title\$/.test(a.value)) {
                        titleInput = el;
                    }
                });
            });
            if (!titleInput) return 'NO_TITLE';
            titleInput.focus();
            titleInput.value = title;
            titleInput.dispatchEvent(new Event('input', { bubbles: true }));
            titleInput.dispatchEvent(new Event('change', { bubbles: true }));
            return 'OK';
        "
        );
        $this->assertSame('OK', (string)($titleResult[0] ?? ''), "title fill failed for {$submitPath}");

        // Give Livewire's wire:model.live a beat to flush the typed title.
        $browser->pause(900);

        // Trigger the path under test.
        if ($submitPath === 'mainSave') {
            $clicked = $browser->script(
                "
                var btn = document.getElementById('save-button');
                if (!btn) return 'NO_SAVE_BUTTON';
                btn.click();
                return 'OK';
            "
            );
        } else {
            // 'modalSave' path: click Filament's own footer "Save"
            // button inside the slideOver. That button has the
            // `fi-ac-btn-action` class and submits the wrapping
            // `<form wire:submit.prevent='callMountedAction'>`. We
            // explicitly do NOT touch #save-button here — the whole
            // point of this case is to prove the modal-only submit
            // is enough on its own.
            $clicked = $browser->script(
                "
                var modal = document.querySelector('.fi-modal-window');
                if (!modal) return 'NO_MODAL';
                var btn = Array.from(modal.querySelectorAll('button'))
                    .find(b => /save/i.test((b.textContent || '').trim())
                              && b.id !== 'save-button');
                if (!btn) return 'NO_MODAL_SAVE';
                btn.click();
                return 'OK';
            "
            );
        }
        $this->assertSame('OK', (string)($clicked[0] ?? ''), "{$submitPath} click failed");

        // Poll the DB for the new row.
        $deadline = microtime(true) + 20.0;
        $row = null;
        do {
            $row = Content::where('title', $title)->where('content_type', 'post')->first();
            if ($row) { break; }
            usleep(500_000);
        } while (microtime(true) < $deadline);

        $this->assertNotNull(
            $row,
            "{$submitPath} path: post row never landed within 20s. The submit pipeline broke — see task-2026-05-01-08eaf5."
        );
        $this->createdIds[] = (int) $row->id;

        // Assert parent linkage — the whole point of fix #30153f.
        $this->assertSame(
            (int) $this->blogId,
            (int) $row->parent,
            "{$submitPath} path: post was created but parent != blog page id ("
            . 'expected ' . $this->blogId . ', got ' . var_export($row->parent, true) . ')'
        );

        // Wait for the iframe to actually refresh — sentinel disappears
        // when the contentWindow is replaced by reload(). This proves
        // `mw.app.canvas.refresh()` was called and completed, not just
        // that the action returned successfully.
        $sentinelObserved = null;
        try {
            $browser->waitUsing(20, 200, function () use ($browser, &$sentinelObserved) {
                $stamp = $browser->script(
                    "
                    try {
                        var ifr = mw.app.canvas.getFrame();
                        if (!ifr || !ifr.contentWindow) return 'GONE';
                        return ifr.contentWindow.__addRefreshSentinel || 'GONE';
                    } catch (e) { return 'GONE'; }
                "
                );
                $sentinelObserved = (string) ($stamp[0] ?? 'NIL');
                return $sentinelObserved === 'GONE';
            });
        } catch (\Facebook\WebDriver\Exception\TimeoutException $e) {
            $this->fail("{$submitPath} path: canvas iframe sentinel never disappeared within 20s — "
                . "mw.app.canvas.refresh() did not run / did not actually reload after the action persisted. "
                . "Last sentinel value: " . var_export($sentinelObserved, true)
                . ". This is the exact bug from task-2026-05-01-08eaf5.");
        }

        // Functional proof — the public render of the blog page must
        // include the new post title. Hits the URL directly so we don't
        // depend on iframe DOM state (which may still be loading after
        // refresh in CI).
        $blogLink = (string) content_link($this->blogId);
        $publicHtml = @file_get_contents(rtrim(config('app.url'), '/') . '/' . ltrim(parse_url($blogLink, PHP_URL_PATH) ?? $blogLink, '/'));
        $this->assertIsString($publicHtml, "{$submitPath}: failed to fetch public blog page");
        $this->assertStringContainsString(
            $title,
            (string) $publicHtml,
            "{$submitPath} path: post landed in DB with parent linked, but the public blog page didn't render it. "
            . 'The listing module does not pick up the new content — investigate the Content listing module ' .
            'on the dynamic blog page (task-2026-05-01-08eaf5).'
        );
    }

    private function createBlogParentPage(): int
    {
        $this->runSlug = substr(md5(microtime(true) . Str::random(6)), 0, 10);
        $slug = self::SLUG_PREFIX . 'blog-' . $this->runSlug;
        $id = save_content([
            'content_type' => 'page',
            'subtype' => 'dynamic',
            'title' => 'AddRefresh blog ' . $this->runSlug,
            'url' => $slug,
            'active_site_template' => 'Bootstrap',
            'is_active' => 1,
            'content' => '<p>blog parent</p><module type="posts" />',
        ]);
        if (!$id || !is_numeric($id)) {
            throw new \RuntimeException(
                'createBlogParentPage: save_content returned non-id value: ' . var_export($id, true)
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

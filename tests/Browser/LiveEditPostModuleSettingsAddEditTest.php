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
 * Add+Edit posts via the **live-edit** module settings slideOver —
 * task-2026-04-29-4f5e4c.
 *
 * The previous `PostModuleAdminAndPublicRenderTest` exercises the
 * /admin/post-module-settings URL directly. That covers the iframe's
 * inner DOM but not the live-edit wrapper around it. This test fills
 * that gap: it goes through the full /admin/live-edit slideOver flow,
 * uses Dusk's `withinFrame()` to switch into the embedded iframe,
 * drives the table actions inside, then asserts both the DB and the
 * public-page render afterward.
 *
 * Test plan (populated as task subtasks in TODO.md, then executed
 * via this file):
 *   1. Seed a Bootstrap host page with a `<module type="posts"
 *      data-limit="1000" />` shortcode.
 *   2. Open /admin/live-edit?url=<page>.
 *   3. Dispatch `window.openModuleSettingsAction` (the same event the
 *      module-edit pencil fires) with the PostModuleSettings class
 *      and a synthetic params.id matching the host page's posts
 *      module.
 *   4. Wait for the slideOver iframe to render.
 *   5. `withinFrame()` into the iframe; wait for the New-post button
 *      with `wire:click=mountAction('create',...,{table:true})`;
 *      click it; fill title via the wire:model-suffix walker;
 *      requestSubmit() on the callMountedTableAction form.
 *   6. Switch back out of the iframe; poll the DB up to 15s for the
 *      new post row.
 *   7. Edit case: seed a post via Eloquent, open live-edit slideOver
 *      again, withinFrame mount EditAction with that record id,
 *      rename, requestSubmit, poll DB for renamed row.
 *   8. Visit the host page's public URL and assert both the new and
 *      renamed post titles appear in the rendered posts module.
 *
 * Cleanup: `LandingTestContentPurger::purge()` for every row created.
 */
class LiveEditPostModuleSettingsAddEditTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';
    private const SLUG_PREFIX = 'liveeditpost-';

    /** @var int[] */
    private array $createdIds = [];

    #[Test]
    public function add_and_edit_posts_via_live_edit_module_settings_persists_and_renders_publicly(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        $smokeRunSlug = substr(md5(microtime(true) . Str::random(6)), 0, 10);
        $hostPageId = $this->createPostsHostPage($smokeRunSlug);

        $createdTitle = 'Liveedit-add ' . $smokeRunSlug;
        $editOriginalTitle = 'Liveedit-edit-orig ' . $smokeRunSlug;
        $editRenamedTitle = 'Liveedit-edit-renamed ' . $smokeRunSlug;

        // Pre-seed the post we'll edit. The CreateAction part exercises
        // the add flow; this seeded row exercises the edit flow.
        $editPostId = save_content([
            'content_type' => 'post',
            'subtype' => 'post',
            'title' => $editOriginalTitle,
            'is_active' => 1,
            'content' => 'liveedit smoke',
        ]);
        $this->assertIsNumeric($editPostId, 'Pre-seed of edit-target post failed');
        $this->createdIds[] = (int)$editPostId;

        $this->browse(function (Browser $browser) use (
            $hostPageId,
            $createdTitle,
            $editPostId,
            $editRenamedTitle
        ) {
            $this->loginAsAdmin($browser);

            $pageLink = (string)content_link($hostPageId);
            $browser->visit('/admin/live-edit?url=' . urlencode($pageLink))
                ->pause(5000);

            // Open the post-module-settings slideOver via the same
            // window event the inline module-edit pencil dispatches.
            // We don't need a real module instance id here — the
            // slideOver renders an iframe to /admin/post-module-settings
            // which lists ALL posts regardless of context.
            $browser->script(
                "
                window.dispatchEvent(new CustomEvent('openModuleSettingsAction', {
                    detail: {
                        moduleSettingsComponent: 'Modules\\\\Post\\\\Filament\\\\PostModuleSettings',
                        params: { id: 'liveedit-test' }
                    }
                }));
            "
            );
            $browser->pause(6000);

            // Wait for the iframe inside the slideOver to render.
            $browser->waitUsing(20, 250, function () use ($browser) {
                $found = $browser->script(
                    "
                    var iframes = Array.from(document.querySelectorAll('iframe'));
                    return iframes.some(f => (f.src || '').indexOf('post-module-settings') !== -1) ? 1 : 0;
                "
                );
                return ($found[0] ?? 0) === 1;
            });

            // Use the iframe's src attribute as the locator —
            // attribute-substring selector `[src*="..."]` works as a
            // standard CSS selector and is more robust than tagging
            // a custom data attribute that Livewire re-renders may
            // strip.

            // === ADD CASE ===
            $browser->withinFrame('iframe[src*="post-module-settings"]', function (Browser $iframe) use ($createdTitle) {
                $iframe->pause(4000);

                // Wait for the lazy-loaded table → New post button.
                $iframe->waitUsing(20, 250, function () use ($iframe) {
                    $found = $iframe->script(
                        "
                        var btns = Array.from(document.querySelectorAll('button, a'));
                        return btns.some(b => {
                            var attrs = b.attributes;
                            for (var i = 0; i < attrs.length; i++) {
                                var a = attrs[i];
                                if (!a.name.startsWith('wire:click')) continue;
                                var v = a.value || '';
                                if (v.indexOf(\"mountAction('create'\") !== -1
                                    && v.indexOf('table') !== -1) return true;
                            }
                            return false;
                        }) ? 1 : 0;
                    "
                    );
                    return ($found[0] ?? 0) === 1;
                });

                // Click New post.
                $iframe->script(
                    "
                    var btn = Array.from(document.querySelectorAll('button, a')).find(b => {
                        var attrs = b.attributes;
                        for (var i = 0; i < attrs.length; i++) {
                            var a = attrs[i];
                            if (!a.name.startsWith('wire:click')) continue;
                            var v = a.value || '';
                            if (v.indexOf(\"mountAction('create'\") !== -1
                                && v.indexOf('table') !== -1) return true;
                        }
                        return false;
                    });
                    if (btn) btn.click();
                "
                );
                $iframe->pause(3000);

                // Wait for the action form.
                $iframe->waitUsing(15, 250, function () use ($iframe) {
                    $found = $iframe->script(
                        "
                        var ok = ['callMountedAction', 'callMountedTableAction'];
                        return Array.from(document.querySelectorAll('form')).some(f => {
                            var v = f.getAttribute('wire:submit.prevent') || f.getAttribute('wire:submit');
                            return ok.indexOf(v) !== -1 && f.offsetParent !== null;
                        }) ? 1 : 0;
                    "
                    );
                    return ($found[0] ?? 0) === 1;
                });

                // Fill title and submit.
                $iframe->script(
                    "
                    var title = " . json_encode($createdTitle) . ";
                    var pickForm = function (name) {
                        return Array.from(document.querySelectorAll('form'))
                            .filter(f => f.offsetParent !== null)
                            .find(f => {
                                var v = f.getAttribute('wire:submit.prevent') || f.getAttribute('wire:submit');
                                return v === name;
                            });
                    };
                    var form = pickForm('callMountedTableAction') || pickForm('callMountedAction');
                    if (!form) return;

                    var setVal = function (input, value) {
                        if (!input) return false;
                        input.focus();
                        input.value = value;
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                        return true;
                    };

                    var titleInput = null;
                    var fields = form.querySelectorAll('input, textarea');
                    for (var i = 0; i < fields.length; i++) {
                        var el = fields[i];
                        var attrs = el.attributes;
                        for (var j = 0; j < attrs.length; j++) {
                            var a = attrs[j];
                            if (!a.name.startsWith('wire:model')) continue;
                            if (!titleInput && /(^|\\.)title\$/.test(a.value || '')) titleInput = el;
                        }
                    }
                    if (!titleInput) {
                        var visible = Array.from(form.querySelectorAll('input[type=\"text\"], input:not([type])'))
                            .filter(el => !el.disabled && !el.readOnly && el.offsetParent !== null);
                        if (visible.length > 0) titleInput = visible[0];
                    }
                    setVal(titleInput, title);
                    setTimeout(() => { if (form.requestSubmit) form.requestSubmit(); }, 700);
                "
                );
                $iframe->pause(4000);
            });

            // Poll for the added row outside the iframe.
            $deadline = microtime(true) + 15.0;
            $foundAdd = false;
            do {
                $row = Content::where('title', $createdTitle)
                    ->where('content_type', 'post')
                    ->first();
                if ($row) { $foundAdd = true; break; }
                usleep(500_000);
            } while (microtime(true) < $deadline);

            $this->assertTrue(
                $foundAdd,
                "Add case: post '{$createdTitle}' did not persist within 15s after submitting "
                . 'the live-edit slideOver iframe form.'
            );

            // === EDIT CASE ===
            // Refresh the iframe by re-mounting the slideOver — gets
            // us a clean state with the seeded edit target visible.
            $browser->script(
                "
                window.dispatchEvent(new Event('closeFilamentSlideOver'));
                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('openModuleSettingsAction', {
                        detail: {
                            moduleSettingsComponent: 'Modules\\\\Post\\\\Filament\\\\PostModuleSettings',
                            params: { id: 'liveedit-test' }
                        }
                    }));
                }, 200);
            "
            );
            $browser->pause(6000);

            // Wait for the re-mounted iframe to appear.
            $browser->waitUsing(15, 250, function () use ($browser) {
                $found = $browser->script(
                    "
                    var iframes = Array.from(document.querySelectorAll('iframe'));
                    return iframes.some(f => (f.src || '').indexOf('post-module-settings') !== -1) ? 1 : 0;
                "
                );
                return ($found[0] ?? 0) === 1;
            });

            $browser->withinFrame('iframe[src*="post-module-settings"]', function (Browser $iframe) use ($editPostId, $editRenamedTitle) {
                $iframe->pause(4000);

                // Wait for table.
                $iframe->waitUsing(20, 250, function () use ($iframe) {
                    $found = $iframe->script(
                        "
                        var btns = Array.from(document.querySelectorAll('button, a'));
                        return btns.some(b => {
                            var attrs = b.attributes;
                            for (var i = 0; i < attrs.length; i++) {
                                var a = attrs[i];
                                if (!a.name.startsWith('wire:click')) continue;
                                var v = a.value || '';
                                if (v.indexOf(\"mountAction('create'\") !== -1
                                    && v.indexOf('table') !== -1) return true;
                            }
                            return false;
                        }) ? 1 : 0;
                    "
                    );
                    return ($found[0] ?? 0) === 1;
                });

                // Mount EditAction directly via the wire (avoids
                // pagination problem if the seeded post is off-screen).
                $iframe->script(
                    "
                    (async function () {
                        try {
                            var rec = " . json_encode((string)$editPostId) . ";
                            var roots = Array.from(document.querySelectorAll('[wire\\\\:id]'));
                            for (var i = 0; i < roots.length; i++) {
                                var snap = roots[i].getAttribute('wire:snapshot') || '';
                                if (snap.indexOf('contentModel') !== -1
                                    && snap.indexOf('tableRecordsPerPage') !== -1) {
                                    var w = window.Livewire.find(roots[i].getAttribute('wire:id'));
                                    if (w) await w.mountTableAction('edit', rec);
                                    return;
                                }
                            }
                        } catch (e) {}
                    })();
                "
                );
                $iframe->pause(3500);

                // Wait for the action form.
                $iframe->waitUsing(15, 250, function () use ($iframe) {
                    $found = $iframe->script(
                        "
                        var ok = ['callMountedAction', 'callMountedTableAction'];
                        return Array.from(document.querySelectorAll('form')).some(f => {
                            var v = f.getAttribute('wire:submit.prevent') || f.getAttribute('wire:submit');
                            return ok.indexOf(v) !== -1 && f.offsetParent !== null;
                        }) ? 1 : 0;
                    "
                    );
                    return ($found[0] ?? 0) === 1;
                });

                // Rename and submit.
                $iframe->script(
                    "
                    var title = " . json_encode($editRenamedTitle) . ";
                    var pickForm = function (name) {
                        return Array.from(document.querySelectorAll('form'))
                            .filter(f => f.offsetParent !== null)
                            .find(f => {
                                var v = f.getAttribute('wire:submit.prevent') || f.getAttribute('wire:submit');
                                return v === name;
                            });
                    };
                    var form = pickForm('callMountedTableAction') || pickForm('callMountedAction');
                    if (!form) return;

                    var setVal = function (input, value) {
                        if (!input) return false;
                        input.focus();
                        input.value = value;
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                        return true;
                    };

                    var titleInput = null;
                    var fields = form.querySelectorAll('input, textarea');
                    for (var i = 0; i < fields.length; i++) {
                        var el = fields[i];
                        var attrs = el.attributes;
                        for (var j = 0; j < attrs.length; j++) {
                            var a = attrs[j];
                            if (!a.name.startsWith('wire:model')) continue;
                            if (!titleInput && /(^|\\.)title\$/.test(a.value || '')) titleInput = el;
                        }
                    }
                    setVal(titleInput, title);
                    setTimeout(() => { if (form.requestSubmit) form.requestSubmit(); }, 700);
                "
                );
                $iframe->pause(4500);
            });

            // Poll for the rename outside the iframe.
            $deadline = microtime(true) + 15.0;
            $foundEdit = false;
            do {
                $row = Content::where('id', $editPostId)->first();
                if ($row && $row->title === $editRenamedTitle) { $foundEdit = true; break; }
                usleep(500_000);
            } while (microtime(true) < $deadline);

            $this->assertTrue(
                $foundEdit,
                "Edit case: post id={$editPostId} title was not renamed within 15s. "
                . "Expected '{$editRenamedTitle}'."
            );
        });

        // Track for cleanup.
        $addedRow = Content::where('title', $createdTitle)->where('content_type', 'post')->first();
        if ($addedRow) {
            $this->createdIds[] = (int)$addedRow->id;
        }

        // Public render verification.
        $publicLink = (string)content_link($hostPageId);
        $this->browse(function (Browser $browser) use ($publicLink, $createdTitle, $editRenamedTitle) {
            $browser->visit($publicLink)->pause(2500);
            $body = (string)($browser->script("return document.body.innerHTML;")[0] ?? '');
            $this->assertStringContainsString(
                $createdTitle,
                $body,
                'Newly-added post title missing from public posts-module render.'
            );
            $this->assertStringContainsString(
                $editRenamedTitle,
                $body,
                'Renamed post title missing from public posts-module render.'
            );
        });
    }

    private function createPostsHostPage(string $slug): int
    {
        $pageSlug = self::SLUG_PREFIX . 'host-' . $slug;
        $title = 'Liveedit smoke ' . $slug;
        $moduleId = 'modulemw-liveeditpostmod-' . $slug;

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
            } catch (\Throwable $e) {}
        }
        $this->createdIds = [];
        parent::tearDown();
    }
}

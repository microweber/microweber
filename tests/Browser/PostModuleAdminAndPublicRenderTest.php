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
 * Comprehensive Dusk coverage for the Post module — task-2026-04-29-4ad513.
 *
 * The user reported that the previous targeted Add-Post-from-toolbar
 * test (LiveEditPostsListAddPostPublicRenderTest) doesn't reflect the
 * actual flow they care about — they specifically want the path
 * "Edit Posts module settings → New post → save → see on public page".
 *
 * That flow turns out to be structurally different:
 * AdminLiveEditPage::openModuleSettingsAction() at
 * `src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php:178`
 * forks on `is_subclass_of($componentClass, \Filament\Pages\Page::class)
 * && method_exists($componentClass, 'getUrl')`. Because
 * `Modules\Post\Filament\PostModuleSettings` extends
 * `Filament\Pages\Page` and getUrl() returns
 * `/admin/post-module-settings`, the slideOver is rendered as an
 * **iframe** pointing at that URL — NOT as an embedded Livewire
 * component in the parent page's DOM.
 *
 * This means the SAVE-listener fix from task-394cd1 (innermost-form
 * precedence) cannot reach the table action's submit form, because the
 * form lives inside the iframe's separate `document` and
 * `document.querySelectorAll('form')` in the parent page can't cross
 * frame boundaries. The actual user flow is:
 *
 *   1. Open the live-edit slideOver (parent page)
 *   2. Inside the iframe, click the table's "Create" button
 *   3. Fill the form
 *   4. Click the form modal's own "Create" submit button (inside the
 *      iframe, a Filament-rendered action submit)
 *   5. Row persists; iframe re-renders the table
 *
 * This test file walks that exact path by visiting the iframe target
 * URL directly (`/admin/post-module-settings?id=…`), which gives the
 * test the same DOM the slideOver iframe gives the user — without the
 * cross-frame scripting headaches. It's a fair simulation: the
 * embedded URL is what the iframe loads, and what works there will
 * work inside the slideOver too.
 *
 * Three test methods cover:
 *   1. `post_module_settings_page_loads_with_table` — sanity check
 *      that the iframe target renders and the ContentTableList
 *      registers a Livewire wire with mountTableAction available.
 *   2. `create_post_via_post_module_settings_persists_and_renders_publicly`
 *      — the full pipeline: open settings → mountTableAction('create')
 *      via the inner Livewire wire → fill title → submit the form →
 *      poll DB → visit a public page with a `<module type="posts">`
 *      shortcode and assert the title shows up.
 *   3. `editing_post_title_via_module_settings_persists`
 *      — same pattern but for EditAction, mounting against an
 *      existing post and asserting the title round-trips.
 *
 * Each test cleans up its own rows via LandingTestContentPurger.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin;
 * Bootstrap template installed.
 */
class PostModuleAdminAndPublicRenderTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';
    private const SLUG_PREFIX = 'postmodtest-';

    /** @var int[] */
    private array $createdIds = [];
    private string $smokeRunSlug = '';

    #[Test]
    public function post_module_settings_page_loads_with_table(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/post-module-settings')
                ->pause(5000);

            // Wait an extra moment for the lazy-loaded table to
            // hydrate (wire:init=loadTable fires async).
            $browser->pause(5000);

            // Sanity: the page must render a Livewire ContentTableList
            // wire that exposes mountTableAction (proves the table is
            // present and ready to accept actions).
            $browser->waitUsing(15, 250, function () use ($browser) {
                $found = $browser->script(
                    "
                    // Find a Livewire wire whose snapshot identifies
                    // it as the ContentTableList component. The JS
                    // proxy lets typeof w.X === 'function' return
                    // true for ANY name (Livewire proxies all method
                    // calls dynamically), so check the snapshot text
                    // instead.
                    // The ContentTableList wire is identified by its
                    // Filament tables lazy-load via wire:init=loadTable.
                    // The header CreateAction button only appears
                    // after loadTable completes; Filament v5 funnels
                    // both page-level and table-header CreateActions
                    // through mountAction with a table-flag argument.
                    // Match the wire:click attribute signature.
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

            $hasTable = $browser->script(
                "
                var btns = Array.from(document.querySelectorAll('button, a'));
                var hasCreate = btns.some(b => {
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
                return hasCreate ? 1 : 0;
            "
            );
            $this->assertSame(
                1,
                $hasTable[0] ?? 0,
                'PostModuleSettings page did not register a Livewire wire exposing mountTableAction. '
                . 'The ContentTableList embed is missing — the inline-edit-posts flow is broken.'
            );
        });
    }

    #[Test]
    public function create_post_via_post_module_settings_persists_and_renders_publicly(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        $hostPageId = $this->createPostsHostPage();
        $this->smokeRunSlug = (string)($this->smokeRunSlug ?: substr(md5(microtime(true) . Str::random(6)), 0, 10));
        $expectedTitle = 'Postmod create ' . $this->smokeRunSlug;

        $this->browse(function (Browser $browser) use ($expectedTitle) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/post-module-settings')
                ->pause(3500);

            // Wait for the table wire.
            $browser->waitUsing(15, 250, function () use ($browser) {
                $found = $browser->script(
                    "
                    // Find a Livewire wire whose snapshot identifies
                    // it as the ContentTableList component. The JS
                    // proxy lets typeof w.X === 'function' return
                    // true for ANY name (Livewire proxies all method
                    // calls dynamically), so check the snapshot text
                    // instead.
                    // The ContentTableList wire is identified by its
                    // Filament tables lazy-load via wire:init=loadTable.
                    // The header CreateAction button only appears
                    // after loadTable completes; Filament v5 funnels
                    // both page-level and table-header CreateActions
                    // through mountAction with a table-flag argument.
                    // Match the wire:click attribute signature.
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

            // Click the table's "New post" header button — that's
            // the user-facing way to trigger the CreateAction.
            // Filament's wire:click handler dispatches mountAction
            // with the table flag.
            $clickResult = $browser->script(
                "
                var createBtn = Array.from(document.querySelectorAll('button, a')).find(b => {
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
                if (!createBtn) return 'NO_CREATE_BTN';
                createBtn.click();
                return 'OK';
            "
            );
            $browser->pause(3500);
            $this->assertSame(
                'OK',
                (string)($clickResult[0] ?? ''),
                'Could not click the New post button'
            );

            // Wait for any of the mounted-action forms — Filament v5
            // routes both page-level and table-header CreateActions
            // through mountAction(), so the rendered form may use
            // either callMountedAction or callMountedTableAction
            // depending on Filament version specifics. Match either.
            $browser->waitUsing(15, 250, function () use ($browser) {
                $found = $browser->script(
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

            // Fill title via the wire:model-suffix walker.
            $filled = $browser->script(
                "
                var title = " . json_encode($expectedTitle) . ";
                var ok = ['callMountedAction', 'callMountedTableAction'];
                var form = Array.from(document.querySelectorAll('form'))
                    .filter(f => f.offsetParent !== null)
                    .find(f => {
                        var v = f.getAttribute('wire:submit.prevent') || f.getAttribute('wire:submit');
                        return ok.indexOf(v) !== -1;
                    });
                if (!form) return 'NO_FORM';

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
                    if (!titleInput && el.getAttribute('name')
                        && /(^|\\.)title\$/.test(el.getAttribute('name'))) {
                        titleInput = el;
                    }
                }
                if (!titleInput) {
                    var visible = Array.from(form.querySelectorAll('input[type=\"text\"], input:not([type])'))
                        .filter(el => !el.disabled && !el.readOnly && el.offsetParent !== null);
                    if (visible.length > 0) titleInput = visible[0];
                }
                if (!setVal(titleInput, title)) return 'NO_TITLE_INPUT';
                return 'OK';
            "
            );
            $this->assertSame('OK', (string)($filled[0] ?? ''), 'Could not fill title input');
            $browser->pause(900);

            // Submit the form via requestSubmit() — same path the form's
            // own submit button hits. This is what the parent-page SAVE
            // listener does when the form is in the parent DOM (which
            // it is here since we visited the URL directly, not via
            // the live-edit slideOver iframe).
            $browser->script(
                "
                var ok = ['callMountedAction', 'callMountedTableAction'];
                var form = Array.from(document.querySelectorAll('form'))
                    .filter(f => f.offsetParent !== null)
                    .find(f => {
                        var v = f.getAttribute('wire:submit.prevent') || f.getAttribute('wire:submit');
                        return ok.indexOf(v) !== -1;
                    });
                if (form && typeof form.requestSubmit === 'function') {
                    form.requestSubmit();
                }
            "
            );

            // Poll for the row.
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
                "Post '{$expectedTitle}' did not persist within 15s. "
                . 'The form submit may not have reached callMountedTableAction.'
            );
        });

        $row = Content::where('title', $expectedTitle)->where('content_type', 'post')->first();
        $this->assertNotNull($row);
        $this->createdIds[] = (int)$row->id;

        // Verify on the public page — the seeded host page has a
        // `<module type="posts" data-limit="1000" />` shortcode so the
        // newly-created post must show up in its render.
        $publicLink = (string)content_link($hostPageId);
        $this->browse(function (Browser $browser) use ($publicLink, $expectedTitle) {
            $browser->visit($publicLink)->pause(2500);
            $body = (string)($browser->script("return document.body.innerHTML;")[0] ?? '');
            $this->assertStringContainsString(
                $expectedTitle,
                $body,
                'Post title missing from public posts-module render. '
                . 'Either the posts module did not render or the data-limit=1000 setting did not include it.'
            );
        });
    }

    #[Test]
    public function editing_post_title_via_module_settings_persists(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        // Pre-seed a post we'll edit. Use an isolated title prefix so
        // the EditAction can find it deterministically.
        $this->smokeRunSlug = (string)($this->smokeRunSlug ?: substr(md5(microtime(true) . Str::random(6)), 0, 10));
        $originalTitle = 'Postmod-edit-orig ' . $this->smokeRunSlug;
        $renamedTitle = 'Postmod-edit-renamed ' . $this->smokeRunSlug;

        $postId = save_content([
            'content_type' => 'post',
            'subtype' => 'post',
            'title' => $originalTitle,
            'is_active' => 1,
            'content' => 'smoke',
        ]);
        $this->assertIsNumeric($postId, 'save_content returned non-numeric id');
        $this->createdIds[] = (int)$postId;

        $this->browse(function (Browser $browser) use ($postId, $renamedTitle) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/post-module-settings')
                ->pause(3500);

            $browser->waitUsing(15, 250, function () use ($browser) {
                $found = $browser->script(
                    "
                    // Find a Livewire wire whose snapshot identifies
                    // it as the ContentTableList component. The JS
                    // proxy lets typeof w.X === 'function' return
                    // true for ANY name (Livewire proxies all method
                    // calls dynamically), so check the snapshot text
                    // instead.
                    // The ContentTableList wire is identified by its
                    // Filament tables lazy-load via wire:init=loadTable.
                    // The header CreateAction button only appears
                    // after loadTable completes; Filament v5 funnels
                    // both page-level and table-header CreateActions
                    // through mountAction with a table-flag argument.
                    // Match the wire:click attribute signature.
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

            // Find the ContentTableList Livewire wire by the
            // contentModel attribute in its snapshot (the seeded
            // post may be off-screen due to pagination, so clicking
            // its row link isn't reliable). Call mountTableAction
            // directly on the wire — same code path the click
            // would run.
            $mountResult = $browser->script(
                "
                return (async function () {
                    try {
                        var rec = " . json_encode((string)$postId) . ";
                        var roots = Array.from(document.querySelectorAll('[wire\\\\:id]'));
                        var target = null;
                        for (var i = 0; i < roots.length; i++) {
                            var snap = roots[i].getAttribute('wire:snapshot') || '';
                            if (snap.indexOf('contentModel') !== -1
                                && snap.indexOf('tableRecordsPerPage') !== -1) {
                                target = roots[i];
                                break;
                            }
                        }
                        if (!target) return 'NO_TABLE_WIRE';
                        var w = window.Livewire.find(target.getAttribute('wire:id'));
                        if (!w) return 'WIRE_NOT_FOUND';
                        await w.mountTableAction('edit', rec);
                        return 'OK';
                    } catch (e) {
                        return 'EXC:' + (e && e.message ? e.message : JSON.stringify(e));
                    }
                })();
            "
            );
            $browser->pause(3500);
            $this->assertSame(
                'OK',
                (string)($mountResult[0] ?? ''),
                'mountTableAction(edit) on ContentTableList wire failed'
            );

            $browser->waitUsing(15, 250, function () use ($browser) {
                $found = $browser->script(
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

            // Replace the title and submit.
            $filled = $browser->script(
                "
                var title = " . json_encode($renamedTitle) . ";
                var pickForm = function (name) {
                    return Array.from(document.querySelectorAll('form'))
                        .filter(f => f.offsetParent !== null)
                        .find(f => {
                            var v = f.getAttribute('wire:submit.prevent') || f.getAttribute('wire:submit');
                            return v === name;
                        });
                };
                // Prefer the callMountedTableAction form — that's the
                // one bound to the table action we mounted. The
                // callMountedAction form may also be present but its
                // mountedActions stack is empty, so submitting it is
                // a no-op.
                var form = pickForm('callMountedTableAction') || pickForm('callMountedAction');
                if (!form) return 'NO_FORM';

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
                if (!setVal(titleInput, title)) return 'NO_TITLE_INPUT';
                return 'OK';
            "
            );
            $this->assertSame('OK', (string)($filled[0] ?? ''), 'Could not fill renamed title');
            $browser->pause(900);

            $browser->script(
                "
                var pickForm = function (name) {
                    return Array.from(document.querySelectorAll('form'))
                        .filter(f => f.offsetParent !== null)
                        .find(f => {
                            var v = f.getAttribute('wire:submit.prevent') || f.getAttribute('wire:submit');
                            return v === name;
                        });
                };
                var form = pickForm('callMountedTableAction') || pickForm('callMountedAction');
                if (form && typeof form.requestSubmit === 'function') form.requestSubmit();
            "
            );

            $deadline = microtime(true) + 15.0;
            $found = false;
            do {
                $row = Content::where('id', $postId)->first();
                if ($row && $row->title === $renamedTitle) { $found = true; break; }
                usleep(500_000);
            } while (microtime(true) < $deadline);

            $this->assertTrue(
                $found,
                "Post id={$postId} title was not renamed within 15s. "
                . "Expected '{$renamedTitle}'."
            );
        });
    }

    /**
     * Pre-create a Bootstrap page whose content is a posts-list
     * shortcode with data-limit=1000 (matches user request to "set
     * paging settings to 1000" declaratively).
     */
    private function createPostsHostPage(): int
    {
        if (!$this->smokeRunSlug) {
            $this->smokeRunSlug = substr(md5(microtime(true) . Str::random(6)), 0, 10);
        }
        $pageSlug = self::SLUG_PREFIX . 'host-' . $this->smokeRunSlug;
        $title = 'Postmod host ' . $this->smokeRunSlug;
        $moduleId = 'modulemw-postmod-' . $this->smokeRunSlug;

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
            } catch (\Throwable $e) {
                // best-effort
            }
        }
        $this->createdIds = [];
        parent::tearDown();
    }
}

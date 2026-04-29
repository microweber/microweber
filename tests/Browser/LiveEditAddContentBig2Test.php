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
 * End-to-end coverage for the live-edit "Add Page / Post / Product"
 * flow on the Big2 template.
 *
 * Background — task-2026-04-29-ba63de + task-2026-04-29-dc57b7 +
 * task-2026-04-29-efa1eb chained three regressions in a row:
 *   1. The live-edit SAVE button never submitted open Filament action
 *      slideOvers — operators saw the page DOM save but the Add-Post
 *      form data silently disappeared.
 *   2. Once the dispatch was wired, a stale Filament v3 namespace
 *      (`Filament\Notifications\Actions\Action`) blew up the success
 *      notification with a 500.
 *   3. After the namespace fix, the dispatch handler called
 *      `$wire.callMountedAction()` directly — bypassing Livewire's
 *      `wire:model` flush so the server-side action received an empty
 *      payload and silently no-op'd.
 *
 * Together those landed three commits but no automated guard, so this
 * test is the regression backstop. For each of the three creatable
 * content types (page / post / product), it:
 *   1. Visits /admin/live-edit?url=<homepage> on a fresh
 *      Big2-template fixture;
 *   2. Mounts the Filament action via `$wire.mountAction(<actionName>)`
 *      — same hook the toolbar Add button + AdminLiveEditPage actions
 *      list use;
 *   3. Fills the title + url fields directly into the rendered modal;
 *   4. Clicks the green live-edit SAVE pill (#save-button) — the
 *      pipeline under test;
 *   5. Asserts the matching Content row was inserted with the expected
 *      title + content_type. The row's existence is the only thing
 *      that proves the full chain — JS dispatch → form requestSubmit →
 *      Livewire flush → callMountedAction → Action::handle →
 *      Content::save() — survived a round trip.
 *
 * The test only writes to its own `livesmoke-` slugs so cleanup() is a
 * narrow `LandingTestContentPurger::purge()` call against rows it
 * created, no global table truncation.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin;
 * Big2 template installed under Templates/Big2/.
 */
class LiveEditAddContentBig2Test extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_USERNAME = 'admin';
    private const ADMIN_PASSWORD = 'admin';
    private const SLUG_PREFIX = 'livesmoke-';

    /** @var int[] */
    private array $createdIds = [];
    private string $smokeRunSlug = '';

    /**
     * Drive Add Page / Post / Product through the live-edit save
     * pipeline on Big2 and assert each Content row landed.
     */
    #[Test]
    public function add_page_post_product_via_live_edit_persists_on_big2(): void
    {
        $this->ensureAdminUser();
        $this->ensureBig2Active();

        // Create a Big2 homepage we can open in live-edit.
        $homepageId = $this->createBig2Homepage();

        $this->browse(function (Browser $browser) use ($homepageId) {
            $this->loginAsAdmin($browser);

            $homepageLink = (string)content_link($homepageId);
            $this->assertNotSame('', $homepageLink, 'content_link returned empty for Big2 homepage');

            $browser->visit('/admin/live-edit?url=' . urlencode($homepageLink))
                ->pause(5000);
            $browser->waitFor('iframe', 20)->pause(2500);

            // Sanity: the AdminLiveEditPage Livewire wire must expose
            // mountAction. Without it the rest of the test is moot.
            $hasWire = $browser->script(
                "
                try {
                    var root = document.querySelector('[wire\\\\:id]');
                    if (!root) return 0;
                    return (typeof Livewire !== 'undefined') ? 1 : 0;
                } catch (e) { return 0; }
            "
            );
            $this->assertSame(1, $hasWire[0] ?? 0, 'Livewire is not present on the live-edit page');

            $cases = [
                ['action' => 'addPageAction', 'type' => 'page'],
                ['action' => 'addPostAction', 'type' => 'post'],
                ['action' => 'addProductAction', 'type' => 'product'],
            ];

            foreach ($cases as $case) {
                $this->driveCreateAction($browser, $case['action'], $case['type']);
            }
        });

        // Assert each row landed in the database with the expected
        // type — the only thing that proves the full save pipeline
        // survived round-tripping.
        foreach (['page', 'post', 'product'] as $type) {
            $title = $this->titleFor($type);
            $row = Content::where('title', $title)
                ->where('content_type', $type)
                ->first();

            $this->assertNotNull(
                $row,
                "Live-edit Add {$type} did not persist a {$type} row titled '{$title}'. "
                . 'The form submit likely never reached the server-side action — '
                . 'see task-2026-04-29-ba63de regression.'
            );
            $this->assertSame($type, (string)$row->content_type);
            $this->createdIds[] = (int)$row->id;
        }
    }

    /**
     * Mount a Filament action on the AdminLiveEditPage Livewire wire,
     * fill its title/url inputs, and click the live-edit SAVE button.
     */
    private function driveCreateAction(Browser $browser, string $actionName, string $contentType): void
    {
        $title = $this->titleFor($contentType);
        $slug = $this->slugFor($contentType);

        // Mount the action via Livewire — same hook the
        // AdminLiveEditPage's own buttons use. mountAction is provided
        // by InteractsWithActions on the page Livewire component.
        $mountResult = $browser->script(
            "
            return (async function () {
                try {
                    var root = document.querySelector('[wire\\\\:id]');
                    if (!root) return 'NO_WIRE_ROOT';
                    var wire = window.Livewire.find(root.getAttribute('wire:id'));
                    if (!wire) return 'NO_WIRE';
                    if (typeof wire.mountAction !== 'function') return 'NO_MOUNT';
                    await wire.mountAction(" . json_encode($actionName) . ", {});
                    return 'OK';
                } catch (e) { return 'EXC:' + (e && e.message ? e.message : e); }
            })();
        "
        );
        $browser->pause(2500);
        $this->assertSame('OK', (string)($mountResult[0] ?? ''), "mountAction({$actionName}) failed");

        // The action modal/slideOver is rendered into <body> via
        // @teleport. Wait for at least one form[wire:submit.prevent]
        // to appear, then fill its title/url inputs.
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

        $filled = $browser->script(
            "
            var title = " . json_encode($title) . ";
            var slug = " . json_encode($slug) . ";

            var form = Array.from(document.querySelectorAll('form'))
                .find(f => f.getAttribute('wire:submit.prevent') === 'callMountedAction'
                        || f.getAttribute('wire:submit') === 'callMountedAction');
            if (!form) return 'NO_FORM';

            var setVal = function (input, value) {
                if (!input) return false;
                input.focus();
                input.value = value;
                input.dispatchEvent(new Event('input', { bubbles: true }));
                input.dispatchEvent(new Event('change', { bubbles: true }));
                return true;
            };

            // Title field — Filament wraps inputs in
            // <input wire:model='data.title'> or similar.
            var titleInput = form.querySelector('input[wire\\\\:model\\\\.live=\"data.title\"], input[wire\\\\:model=\"data.title\"]')
                || form.querySelector('input[name=\"data.title\"]')
                || form.querySelector('input[id$=\"data.title\"]');
            if (!setVal(titleInput, title)) return 'NO_TITLE_INPUT';

            // URL/slug field — optional but helps assert determinism.
            var urlInput = form.querySelector('input[wire\\\\:model\\\\.live=\"data.url\"], input[wire\\\\:model=\"data.url\"]')
                || form.querySelector('input[name=\"data.url\"]')
                || form.querySelector('input[id$=\"data.url\"]');
            if (urlInput) setVal(urlInput, slug);

            return 'OK';
        "
        );
        $this->assertSame('OK', (string)($filled[0] ?? ''), "Could not fill {$contentType} form fields");

        // Give Livewire's debounced wire:model.live a beat to flush.
        $browser->pause(900);

        // Click the live-edit SAVE button — the pipeline under test.
        // It dispatches liveEditSaveCallMountedAction → iframe-page's
        // Alpine listener calls form.requestSubmit() → Livewire syncs
        // bindings → callMountedAction runs the form's action handler.
        $clicked = $browser->script(
            "
            var btn = document.getElementById('save-button');
            if (!btn) return 'NO_SAVE_BUTTON';
            btn.click();
            return 'OK';
        "
        );
        $this->assertSame('OK', (string)($clicked[0] ?? ''), 'Live-edit SAVE button missing');

        // Wait for the action to unmount (success path) or for the
        // form to surface validation errors. Use the
        // mountedActions array on the wire as the signal.
        $browser->waitUsing(15, 250, function () use ($browser) {
            $mounted = $browser->script(
                "
                try {
                    var root = document.querySelector('[wire\\\\:id]');
                    if (!root) return 0;
                    var wire = window.Livewire.find(root.getAttribute('wire:id'));
                    if (!wire) return 0;
                    return (wire.mountedActions && wire.mountedActions.length) ? 1 : 0;
                } catch (e) { return 0; }
            "
            );
            return ($mounted[0] ?? 1) === 0;
        });

        // Belt-and-braces: small pause so the success notification's
        // database write fully commits before the next iteration.
        $browser->pause(800);
    }

    private function titleFor(string $type): string
    {
        return ucfirst($type) . ' smoke ' . $this->smokeRunSlug;
    }

    private function slugFor(string $type): string
    {
        return self::SLUG_PREFIX . $type . '-' . $this->smokeRunSlug;
    }

    /**
     * Pre-create a Big2 homepage so /admin/live-edit?url=<homepage>
     * can mount cleanly.
     */
    private function createBig2Homepage(): int
    {
        $this->smokeRunSlug = substr(md5(microtime(true) . Str::random(6)), 0, 10);
        $homepageSlug = self::SLUG_PREFIX . 'home-' . $this->smokeRunSlug;
        $title = 'Live-edit smoke ' . $this->smokeRunSlug;

        $id = save_content([
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => $title,
            'url' => $homepageSlug,
            'active_site_template' => 'Big2',
            'is_active' => 1,
            'content' => '<p>smoke</p>',
        ]);

        if (!$id || !is_numeric($id)) {
            throw new \RuntimeException(
                'createBig2Homepage: save_content returned a non-id value: '
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

    private function ensureBig2Active(): void
    {
        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();

        if ($row) {
            if ($row->option_value !== 'Big2') {
                DB::table('options')
                    ->where('id', $row->id)
                    ->update([
                        'option_value' => 'Big2',
                        'updated_at' => now(),
                    ]);
            }
            return;
        }

        DB::table('options')->insert([
            'option_key' => 'current_template',
            'option_value' => 'Big2',
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
                // Best-effort cleanup; don't mask test failures.
            }
        }
        $this->createdIds = [];
        parent::tearDown();
    }
}

<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Hash;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use Modules\Menu\Models\Menu;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Support\LandingTestContentPurger;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Dusk regression backstop for editing a menu-item title from inside
 * the live-edit Menu module-settings slideOver — task-2026-04-30-7da8c0.
 *
 * Background: task-2026-04-30-b4b3bd fixed the underlying bug — the
 * editAction handler had a dead `use_custom_title→empty` gate that
 * silently wiped every typed-in title. The fix dropped the gate and
 * added `live(onBlur: true)` to the Title input. This test guards
 * against that gate creeping back into MenusList.
 *
 * The test exercises the FULL live-edit flow (not the standalone
 * /admin/menu-module-settings URL) because that's the surface the
 * user reported the bug on, and that path includes a cross-iframe
 * boundary that's worth covering separately:
 *
 *   1. Visit /admin/live-edit?url=<host page>
 *   2. Dispatch the same `openModuleSettingsAction` event the inline
 *      module-edit pencil fires, with MenuModuleSettings + a deterministic
 *      params.id
 *   3. Wait for the slideOver iframe at /admin/menu-module-settings
 *   4. `withinFrame()` to switch into the iframe
 *   5. Click the first menu-item row's Edit pencil — this opens the
 *      INNER (nested) Edit slideOver for that menu row
 *   6. Find the Title input, type a deterministic test title, blur,
 *      click the form's Submit button
 *   7. Switch back out of the iframe
 *   8. Poll the menus table for the new title up to 15s
 *   9. Reset the title to the seeded original to leave the DB clean
 *
 * Cleanup: the host page is purged via LandingTestContentPurger; the
 * menu-row title is reset to its pre-test value.
 */
class LiveEditMenuTitleEditTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';
    private const SLUG_PREFIX = 'menutitle-';

    /** @var int[] */
    private array $createdContentIds = [];

    private ?int $editedMenuId = null;
    private ?string $originalMenuTitle = null;

    #[Test]
    public function editing_menu_item_title_from_live_edit_persists_to_db(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        // Pick the first available child menu item (parent_id > 0 + has
        // content_id so it's a real link, not a placeholder).
        $menuItem = Menu::query()
            ->where('parent_id', '>', 0)
            ->orderBy('id')
            ->first();

        $this->assertNotNull(
            $menuItem,
            'No child menu item available to edit — the seeded header_menu has no children. '
            . 'Reseed via the installer or add a menu_item row before running this test.'
        );

        $this->editedMenuId = (int)$menuItem->id;
        $this->originalMenuTitle = (string)$menuItem->title;

        // Deterministic test title with a unique slug so we can poll
        // for it without ambiguity.
        $smokeRunSlug = substr(md5(microtime(true) . Str::random(6)), 0, 10);
        $newTitle = 'MenuTitleEdit ' . $smokeRunSlug;

        // Seed a Bootstrap host page so /admin/live-edit has a real URL
        // to mount onto. The page itself doesn't need menu shortcodes —
        // we're driving the Menu module settings via the openModuleSettingsAction
        // window event, not via the canvas.
        $hostPageId = $this->createHostPage($smokeRunSlug);

        $this->browse(function (Browser $browser) use ($hostPageId, $newTitle) {
            $this->loginAsAdmin($browser);

            $hostLink = (string)content_link($hostPageId);
            $browser->visit('/admin/live-edit?url=' . urlencode($hostLink))
                ->pause(5000);
            $browser->waitFor('iframe', 20)->pause(2500);

            // Open the menu module-settings slideOver (which loads as an
            // iframe at /admin/menu-module-settings).
            $browser->script(
                "window.dispatchEvent(new CustomEvent('openModuleSettingsAction', {
                    detail: {
                        moduleSettingsComponent: 'Modules\\\\Menu\\\\Filament\\\\MenuModuleSettings',
                        params: { id: 'menutitle-test' }
                    }
                }));"
            );
            $browser->pause(6000);

            // Wait for the menu-module-settings iframe to render.
            $browser->waitUsing(20, 250, function () use ($browser) {
                $found = $browser->script(
                    "return Array.from(document.querySelectorAll('iframe'))
                        .some(f => (f.src || '').indexOf('menu-module-settings') !== -1) ? 1 : 0;"
                );
                return ($found[0] ?? 0) === 1;
            });

            // Switch into the iframe + drive the Edit flow.
            $browser->withinFrame('iframe[src*="menu-module-settings"]', function (Browser $iframe) use ($newTitle) {
                $iframe->pause(3500);

                // Wait for the menu-list-item rows to render.
                $iframe->waitUsing(15, 250, function () use ($iframe) {
                    $found = $iframe->script(
                        "return document.querySelectorAll('.mw-menu-item').length > 0 ? 1 : 0;"
                    );
                    return ($found[0] ?? 0) === 1;
                });

                // Click the first row's Edit pencil (icon-only button:first-child).
                $iframe->script(
                    "var item = document.querySelector('.mw-menu-item');
                     var editBtn = item.querySelector('.mw-menu-item__actions button:first-child');
                     if (editBtn) editBtn.click();"
                );
                $iframe->pause(2500);

                // Wait for the Edit slideOver's Title input to appear.
                $iframe->waitUsing(15, 250, function () use ($iframe) {
                    $found = $iframe->script(
                        "return Array.from(document.querySelectorAll('input')).some(i =>
                            Array.from(i.attributes).some(a =>
                                a.name.startsWith('wire:model') && /\\.title\$/.test(a.value || '')
                            )
                        ) ? 1 : 0;"
                    );
                    return ($found[0] ?? 0) === 1;
                });

                // Fill title + blur + submit.
                $iframe->script(
                    "var title = " . json_encode($newTitle) . ";
                     var input = Array.from(document.querySelectorAll('input')).find(i =>
                        Array.from(i.attributes).some(a =>
                            a.name.startsWith('wire:model') && /\\.title\$/.test(a.value || '')
                        )
                     );
                     if (!input) return;
                     input.focus();
                     input.value = title;
                     input.dispatchEvent(new Event('input', { bubbles: true }));
                     input.dispatchEvent(new Event('blur', { bubbles: true }));"
                );
                $iframe->pause(1500);

                // Submit the form.
                $iframe->script(
                    "var submit = Array.from(document.querySelectorAll('.fi-modal-window button'))
                        .find(b => /submit|save/i.test((b.textContent || '').trim()));
                     if (submit) submit.click();"
                );
                $iframe->pause(2000);
            });
        });

        // Poll the DB up to 15s for the title persistence.
        $deadline = microtime(true) + 15.0;
        $persisted = false;
        do {
            $row = Menu::find($this->editedMenuId);
            if ($row && $row->title === $newTitle) {
                $persisted = true;
                break;
            }
            usleep(500_000);
        } while (microtime(true) < $deadline);

        $this->assertTrue(
            $persisted,
            "Menu item id={$this->editedMenuId} title was not updated to '{$newTitle}' within 15s. "
            . 'The editAction submit pipeline silently dropped the typed title — '
            . 'see task-2026-04-30-b4b3bd regression. '
            . 'Check that MenusList::editAction does NOT have a use_custom_title gate.'
        );

        // Track for cleanup at tearDown.
        $this->createdContentIds[] = $hostPageId;
    }

    private function createHostPage(string $slug): int
    {
        $pageSlug = self::SLUG_PREFIX . 'host-' . $slug;
        $title = 'Menu title edit smoke ' . $slug;

        $id = save_content([
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => $title,
            'url' => $pageSlug,
            'active_site_template' => 'Bootstrap',
            'is_active' => 1,
            'content' => '<p>menu-title-edit smoke</p>',
        ]);

        if (!$id || !is_numeric($id)) {
            throw new \RuntimeException(
                'createHostPage: save_content returned non-id value: ' . var_export($id, true)
            );
        }

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
        // Reset the menu-item title we edited.
        if ($this->editedMenuId !== null) {
            try {
                DB::table('menus')
                    ->where('id', $this->editedMenuId)
                    ->update(['title' => $this->originalMenuTitle ?? '']);
            } catch (\Throwable $e) {}
        }

        foreach ($this->createdContentIds as $id) {
            try {
                LandingTestContentPurger::purge($id);
            } catch (\Throwable $e) {}
        }
        $this->createdContentIds = [];

        parent::tearDown();
    }
}

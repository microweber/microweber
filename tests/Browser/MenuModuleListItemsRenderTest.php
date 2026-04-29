<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Regression guard for task-2026-04-29-95d4a6 + earlier task-dcde6d.
 *
 * Two related bugs in the live-edit menu list both surfaced from the
 * same "I can't see anything in the menu rows" complaint:
 *
 *   1. **Title text invisible** (task-dcde6d) — the closure passed to
 *      `menu_tree()` lost its $this binding when invoked, so the
 *      child blade's `($this->editAction)(...)` calls threw "Using
 *      $this when not in object context" and silently destroyed
 *      half the rendered output. Fixed by capturing
 *      `$component = $this` in the parent blade and passing it
 *      through the closure to the child blade.
 *   2. **Edit + Delete buttons hidden until hover**
 *      (task-2026-04-29-95d4a6) — the menu-item row's
 *      `.mw-menu-item__actions` had `opacity: 0` by default with
 *      `:hover` revealing it. Users had no affordance the row was
 *      clickable. Fixed by removing the opacity:0 default.
 *
 * This test visits `/admin/menu-module-settings` (the URL the
 * live-edit slideOver iframes for menu settings) and asserts every
 * rendered menu-item row carries:
 *   - a non-empty `.mw-menu-item__title` text,
 *   - exactly two action buttons in `.mw-menu-item__actions`
 *     (Edit + Delete) that are visibly painted (computedStyle
 *     opacity === '1', display !== 'none'),
 * for at least the seeded default-menu items (Home / Blog / Shop /
 * Contact us). If either fix regresses, this test catches it before
 * the user opens a screenshot.
 */
class MenuModuleListItemsRenderTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';

    #[Test]
    public function menu_module_settings_renders_titles_and_visible_action_buttons(): void
    {
        $this->ensureAdminUser();

        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/menu-module-settings')
                ->pause(4500);

            // Wait for at least one menu-item row to render.
            $browser->waitUsing(15, 250, function () use ($browser) {
                $found = $browser->script(
                    "return document.querySelectorAll('.mw-menu-item').length > 0 ? 1 : 0;"
                );
                return ($found[0] ?? 0) === 1;
            });

            // Inspect every rendered row.
            $rowsJson = $browser->script(
                "
                var rows = Array.from(document.querySelectorAll('.mw-menu-item')).slice(0, 10);
                return JSON.stringify(rows.map(it => {
                    var titleEl = it.querySelector('.mw-menu-item__title');
                    var actionsEl = it.querySelector('.mw-menu-item__actions');
                    var actionsCs = actionsEl ? window.getComputedStyle(actionsEl) : null;
                    return {
                        title: titleEl ? (titleEl.textContent || '').trim() : '',
                        hasActions: !!actionsEl,
                        actionsOpacity: actionsCs ? actionsCs.opacity : '0',
                        actionsDisplay: actionsCs ? actionsCs.display : 'none',
                        buttonCount: actionsEl ? actionsEl.querySelectorAll('button').length : 0,
                    };
                }));
            "
            );

            $rows = json_decode((string)($rowsJson[0] ?? '[]'), true) ?: [];
            $this->assertNotEmpty($rows, 'No .mw-menu-item rows rendered on /admin/menu-module-settings');

            foreach ($rows as $i => $r) {
                $this->assertNotSame(
                    '',
                    (string)($r['title'] ?? ''),
                    "Row {$i}: empty title — task-dcde6d regression. The menu-item blade is "
                    . 'losing its $this binding inside the menu_tree() closure again.'
                );
                $this->assertTrue(
                    !empty($r['hasActions']),
                    "Row {$i}: missing .mw-menu-item__actions container."
                );
                $this->assertSame(
                    '1',
                    (string)($r['actionsOpacity'] ?? '0'),
                    "Row {$i}: .mw-menu-item__actions opacity is not 1 — "
                    . 'task-95d4a6 regression. The buttons must be visible on first paint, '
                    . 'not only on hover.'
                );
                $this->assertNotSame(
                    'none',
                    (string)($r['actionsDisplay'] ?? 'none'),
                    "Row {$i}: .mw-menu-item__actions display:none."
                );
                $this->assertSame(
                    2,
                    (int)($r['buttonCount'] ?? 0),
                    "Row {$i}: expected exactly 2 action buttons (Edit + Delete), "
                    . 'got ' . (int)($r['buttonCount'] ?? 0) . '.'
                );
            }
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
        if ((int)$user->is_admin !== 1) { $user->is_admin = 1; $dirty = true; }
        if ((int)$user->is_active !== 1) { $user->is_active = 1; $dirty = true; }
        if ($dirty) { $user->save(); }
    }
}

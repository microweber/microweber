<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Hash;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Regression backstop for task-2026-05-02-003a6b — "the notification
 * background is missing pls fix on add post".
 *
 * The bundled `mw.notification` template (in
 * `packages/frontend-assets/resources/assets/components/notification.js`)
 * uses Bootstrap-5 `text-bg-${type}` classes on the inner div for
 * colour. The admin chrome does NOT load Bootstrap CSS in the top
 * frame, and `notification.less` is not bundled into any admin or
 * live-edit CSS bundle either. The result: notifications rendered
 * with NO background, and the page content (the Edit Posts iframe
 * table in the user's screenshot) bled through the pill.
 *
 * Fix added solid-background fallbacks for `.mw-notification` +
 * `.mw-success` / `.mw-warning` / `.mw-error` directly in
 * `iframe-page.blade.php`'s inline `<style>` block — runs at parse
 * time, no JS rebuild needed, scoped to the live-edit chrome where
 * the user reported the issue.
 *
 * This test pins:
 *   - `.mw-notification` has a non-transparent background colour
 *     (alpha > 0 AND not equal to `rgba(0, 0, 0, 0)`),
 *   - the inner `.text-bg-success` div (the colour modifier) ALSO
 *     has a solid background — proves at least one of the two CSS
 *     layers paints the success colour.
 *
 * Wired into phpunit.dusk.xml as `LiveEditNotificationBackground`.
 */
class LiveEditNotificationBackgroundTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';

    #[Test]
    public function mw_notification_has_solid_background_in_live_edit(): void
    {
        $this->ensureAdminUser();

        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/live-edit')->pause(5000);
            $browser->waitFor('iframe', 20)->pause(2500);

            // Trigger a long-lived notification we can measure. 60s
            // timeout > the Dusk wait window so the element doesn't
            // disappear mid-assertion.
            $browser->script("mw.notification.success('Background regression check', 60000);");
            $browser->pause(800);

            $cs = $browser->script(
                "
                return (function () {
                    var n = document.querySelector('.mw-notification');
                    if (!n) return null;
                    var inner = n.querySelector('.text-bg-success, .mw-success');
                    var notifStyle = window.getComputedStyle(n);
                    var innerStyle = inner ? window.getComputedStyle(inner) : null;
                    return {
                        notifBg: notifStyle.backgroundColor,
                        notifColor: notifStyle.color,
                        innerBg: innerStyle ? innerStyle.backgroundColor : null,
                        innerColor: innerStyle ? innerStyle.color : null,
                    };
                })();
            "
            );

            $shape = $cs[0] ?? null;
            $this->assertIsArray($shape, 'mw.notification did not render or computed-style script returned non-array');

            // Reject any value that means "no fill": empty, transparent
            // RGBA, or unset.
            $isOpaque = function ($value) {
                if ($value === null || $value === '') {
                    return false;
                }
                $value = (string) $value;
                if ($value === 'transparent') {
                    return false;
                }
                if (preg_match('/^rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*,\s*0\s*(?:\.0+)?\s*\)$/i', $value)) {
                    return false;
                }
                return true;
            };

            $this->assertTrue(
                $isOpaque($shape['notifBg']),
                'task-2026-05-02-003a6b regressed: .mw-notification has no background — '
                . 'value=' . var_export($shape['notifBg'], true) . '. The notification pill '
                . 'will be invisible / transparent over page content.'
            );

            // The inner colour modifier (.text-bg-success / .mw-success)
            // is what carries the per-type colour. Either layer being
            // opaque is enough for the user to see the pill, but both
            // layers being transparent is the exact bug from the
            // user's screenshot — guard both.
            if ($shape['innerBg'] !== null) {
                $this->assertTrue(
                    $isOpaque($shape['innerBg']),
                    'task-2026-05-02-003a6b regressed: the inner success colour modifier '
                    . 'has no background — value=' . var_export($shape['innerBg'], true)
                    . '. The notification pill renders with no per-type colour, so the '
                    . 'user can\'t tell success from error.'
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
        if ((int) $user->is_admin !== 1) { $user->is_admin = 1; $dirty = true; }
        if ((int) $user->is_active !== 1) { $user->is_active = 1; $dirty = true; }
        if ($dirty) { $user->save(); }
    }
}

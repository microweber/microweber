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
 * Regression backstop for task-2026-05-02-354958 — "the add post
 * modal closes and his info is lost when the user clicks outside".
 *
 * Filament's modal defaults are catastrophic for content-creation
 * forms: a backdrop click OR an Escape keystroke instantly closes
 * the modal AND discards everything the user typed, with no draft,
 * no warning, no recovery. The user's complaint quoted that
 * exact behaviour: "when the user clicks outside it closes and his
 * info is lost".
 *
 * Fix chained `->closeModalByClickingAway(false)` and
 * `->closeModalByEscaping(false)` on every ContentResource-form
 * action: AdminLiveEditPage::generateAction (the +ADD modal) AND
 * ContentTableList's CreateAction + EditAction (per-module table
 * actions). Cancel still works via the X button or the explicit
 * Cancel footer action.
 *
 * This test pins the +ADD modal's behaviour:
 *   1. Mount addPostAction.
 *   2. Type a title.
 *   3. Click the backdrop — modal must STAY OPEN with the title
 *      preserved.
 *   4. Press Escape — modal must STAY OPEN with the title
 *      preserved.
 *   5. (Implicit) The fix does not break the explicit close path
 *      via the X button, but that's covered by the existing
 *      LiveEditAddContentBig2Test which still passes.
 *
 * The same guard applies to ContentTableList's CreateAction by the
 * same wiring; pinning the +ADD modal is sufficient because both
 * call sites share the same chain.
 *
 * Wired into phpunit.dusk.xml as
 * `LiveEditAddContentDoesNotLoseDataOnClickOutside`.
 */
class LiveEditAddContentDoesNotLoseDataOnClickOutsideTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';

    #[Test]
    public function backdrop_click_and_escape_preserve_typed_data_in_add_post_modal(): void
    {
        $this->ensureAdminUser();

        $title = 'AuditTaskRepro 354958 ' . substr(md5(microtime(true)), 0, 8);

        $this->browse(function (Browser $browser) use ($title) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/live-edit')->pause(5000);
            $browser->waitFor('iframe', 20)->pause(2500);

            // Mount the addPostAction via the same hook the +ADD
            // toolbar's "New Post" tile uses.
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

            // Type into the title field.
            $fill = $browser->script(
                "
                var input = document.querySelector('input[wire\\\\:model=\"mountedActions.0.data.title\"]');
                if (!input) return 'NO_INPUT';
                input.focus();
                input.value = " . json_encode($title) . ";
                input.dispatchEvent(new Event('input', { bubbles: true }));
                input.dispatchEvent(new Event('change', { bubbles: true }));
                return 'OK';
            "
            );
            $this->assertSame('OK', (string) ($fill[0] ?? ''), 'Title fill failed');
            $browser->pause(700);

            // Step 1: click the modal backdrop. Filament emits a click
            // on `.fi-modal-window-ctn` for backdrop clicks. The fix
            // should make this a no-op for our modal.
            $browser->script(
                "
                var ctn = document.querySelector('.fi-modal-window-ctn');
                if (ctn) {
                    var evt = new MouseEvent('click', {
                        bubbles: true, cancelable: true, view: window,
                        clientX: 50, clientY: 400,
                    });
                    var el = document.elementFromPoint(50, 400);
                    if (el) el.dispatchEvent(evt);
                }
            "
            );
            $browser->pause(800);

            $afterBackdrop = $browser->script(
                "
                var modal = document.querySelector('.fi-modal-window');
                var input = document.querySelector('input[wire\\\\:model=\"mountedActions.0.data.title\"]');
                return JSON.stringify({
                    open: !!modal,
                    val: input ? input.value : null,
                });
            "
            );
            $shape = json_decode((string) ($afterBackdrop[0] ?? ''), true);
            $this->assertIsArray($shape, 'after-backdrop probe failed');
            $this->assertTrue(
                (bool) ($shape['open'] ?? false),
                'task-2026-05-02-354958 regressed: a backdrop click closed the Add Post modal. '
                . 'closeModalByClickingAway(false) is no longer being applied.'
            );
            $this->assertSame(
                $title,
                (string) ($shape['val'] ?? ''),
                'task-2026-05-02-354958 regressed: title field was cleared after the backdrop click. '
                . 'Even if the modal stayed open, the form state was lost.'
            );

            // Step 2: press Escape via the parent body element. Same
            // guard applies — closeModalByEscaping(false) means Esc
            // doesn't fire close-modal.
            //
            // Stub window.confirm before Escape: the newer
            // initModalDirtyGuard in admin-filament.js intercepts
            // Escape when the modal has dirty data and shows a
            // confirm() dialog. WebDriver sees that as an
            // UnexpectedAlertOpen unless the dialog is auto-handled.
            // Returning false ("keep my work") makes the guard
            // stopPropagation; either way the Filament
            // closeModalByEscaping(false) chain ALSO keeps the modal
            // open, so the outcome is unchanged — this stub just
            // prevents the alert from blocking the next script() call.
            $browser->script("window.confirm = function () { return false; };");
            $browser->driver->getKeyboard()->sendKeys(\Facebook\WebDriver\WebDriverKeys::ESCAPE);
            $browser->pause(800);

            $afterEsc = $browser->script(
                "
                var modal = document.querySelector('.fi-modal-window');
                var input = document.querySelector('input[wire\\\\:model=\"mountedActions.0.data.title\"]');
                return JSON.stringify({
                    open: !!modal,
                    val: input ? input.value : null,
                });
            "
            );
            $shapeEsc = json_decode((string) ($afterEsc[0] ?? ''), true);
            $this->assertIsArray($shapeEsc, 'after-escape probe failed');
            $this->assertTrue(
                (bool) ($shapeEsc['open'] ?? false),
                'task-2026-05-02-354958 regressed: Escape closed the Add Post modal. '
                . 'closeModalByEscaping(false) is no longer being applied.'
            );
            $this->assertSame(
                $title,
                (string) ($shapeEsc['val'] ?? ''),
                'task-2026-05-02-354958 regressed: title field was cleared after Escape. '
                . 'Even if the modal stayed open, the form state was lost.'
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
}

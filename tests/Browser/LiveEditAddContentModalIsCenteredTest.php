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
 * Regression backstop for task-2026-05-02-82ca03 — "the add post
 * modal is too small pls make it to not slide right".
 *
 * `AdminLiveEditPage::generateAction` used to chain `->slideOver()`
 * which pinned the Add Page / Add Post / Add Product / Add Category
 * modal to the right edge of the screen at ~Medium width, leaving
 * the form's title/url/content-body fields in a single narrow
 * column — the rich text editor inside Content body had nowhere to
 * breathe and the canvas peeked through behind it.
 *
 * Fix swapped slideOver() for `modalWidth(MaxWidth::ThreeExtraLarge)`
 * so the modal centers in the viewport and gets enough horizontal
 * space for Filament's two-column section grid to actually render.
 *
 * This test pins:
 *   - the modal element does NOT carry the `fi-modal-slide-over` class
 *     (proves we didn't silently re-add slideOver()),
 *   - the modal carries the `fi-width-3xl` class (proves the width
 *     hint is still applied),
 *   - the modal is centered (left margin > 0, right margin > 0)
 *     rather than flush against the right edge.
 *
 * Wired into phpunit.dusk.xml as `LiveEditAddContentModalIsCentered`.
 */
class LiveEditAddContentModalIsCenteredTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';

    /**
     * Some Filament chrome (close button, modal background) reserves
     * a few pixels of margin even on slideOver layouts. Require the
     * left margin to exceed this floor so we don't pass for the
     * wrong reason.
     */
    private const CENTERED_MODAL_MIN_LEFT_MARGIN_PX = 80;

    #[Test]
    public function add_post_modal_is_a_centered_modal_not_a_right_side_slideover(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/live-edit')->pause(5000);
            $browser->waitFor('iframe', 20)->pause(2500);

            // Mount the action via the same pipeline the +ADD toolbar
            // → "New Post" tile uses (replaceMountedAction internally
            // calls mountAction with addPostAction).
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
            $this->assertSame('OK', (string) ($mount[0] ?? ''), 'mountAction failed');

            $browser->waitFor('.fi-modal-window', 15);

            $modalShape = $browser->script(
                "
                return (function () {
                    var modal = document.querySelector('.fi-modal-window');
                    if (!modal) return null;
                    var rect = modal.getBoundingClientRect();
                    return {
                        width: Math.round(rect.width),
                        left: Math.round(rect.left),
                        right: Math.round(rect.right),
                        viewportWidth: window.innerWidth,
                        classNames: modal.className,
                        parentClassNames: modal.parentElement ? modal.parentElement.className : '',
                    };
                })();
            "
            );

            $shape = $modalShape[0] ?? null;
            $this->assertIsArray($shape, 'Modal shape script returned non-array');

            // Hard guard #1: NEVER let the slideOver class come back.
            $combinedClasses = (string) ($shape['classNames'] ?? '') . ' ' . (string) ($shape['parentClassNames'] ?? '');
            $this->assertStringNotContainsString(
                'fi-modal-slide-over',
                $combinedClasses,
                'task-2026-05-02-82ca03 regressed: the Add Post modal is being rendered as a '
                . 'right-side slideOver again. Modal classes: ' . $combinedClasses
            );

            // Hard guard #2: width hint is still ThreeExtraLarge.
            // (Filament emits the width as a class fragment.)
            $this->assertStringContainsString(
                'fi-width-3xl',
                (string) $shape['classNames'],
                'task-2026-05-02-82ca03 regressed: the Add Post modal lost its '
                . 'modalWidth(ThreeExtraLarge) hint. Without it the modal collapses to '
                . 'Filament\'s default narrow width and the form fields stack into one '
                . 'column. Modal classes: ' . $shape['classNames']
            );

            // Hard guard #3: actually centered (left > floor AND right > floor)
            // — not just hugging the right edge.
            $leftMargin = (int) $shape['left'];
            $rightMargin = (int) $shape['viewportWidth'] - (int) $shape['right'];
            $this->assertGreaterThan(
                self::CENTERED_MODAL_MIN_LEFT_MARGIN_PX,
                $leftMargin,
                'task-2026-05-02-82ca03 regressed: the Add Post modal is flush against '
                . 'the LEFT edge (left=' . $leftMargin . 'px). The user reported "do not '
                . 'slide right" — modal must be visibly centered in the viewport.'
            );
            $this->assertGreaterThan(
                self::CENTERED_MODAL_MIN_LEFT_MARGIN_PX,
                $rightMargin,
                'task-2026-05-02-82ca03 regressed: the Add Post modal is flush against '
                . 'the RIGHT edge (right margin=' . $rightMargin . 'px). The user reported '
                . '"do not slide right" — modal must be visibly centered in the viewport.'
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
}

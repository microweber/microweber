<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Hash;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Support\LandingTestContentPurger;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Regression backstop for task-2026-05-02-2ecfe6 — "the new post
 * modals are not looking good on the slide opening, can you make
 * them open normally no slide".
 *
 * `ContentTableList`'s `CreateAction` and `EditAction` (the table
 * actions inside the post-module-settings iframe — "New post" /
 * "Edit" buttons) used to chain `->slideOver()`, so the form
 * slid in from the right edge of the iframe. Inside the narrow
 * post-module-settings iframe that looked cramped and confusing.
 *
 * Fix: drop slideOver(), add modalWidth(MaxWidth::FiveExtraLarge).
 * The modal now opens centered inside the iframe at the same width
 * tier as the +ADD toolbar's centered modal — visual consistency
 * between the two add-post entry points. Width bumped from
 * ThreeExtraLarge to FiveExtraLarge in task-2026-05-04-61e974 along
 * with the toolbar.
 *
 * This test pins:
 *   - The CreateAction modal does NOT carry `fi-modal-slide-over`
 *     (catches re-introduction of `->slideOver()`).
 *   - The CreateAction modal carries `fi-width-5xl` (catches drop
 *     of `modalWidth()`).
 *
 * The same guards apply to EditAction by virtue of using the same
 * `ContentTableList` config; pinning CreateAction is sufficient.
 *
 * Wired into phpunit.dusk.xml as
 * `LiveEditPostModuleTableActionModalCentered`.
 */
class LiveEditPostModuleTableActionModalCenteredTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';
    private const SLUG_PREFIX = 'tablemodal-';

    /** @var int[] */
    private array $createdIds = [];

    #[Test]
    public function table_create_action_modal_is_centered_not_slideover(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        $smokeRunSlug = substr(md5(microtime(true) . Str::random(6)), 0, 10);
        $hostId = $this->createPostsHostPage($smokeRunSlug);

        $this->browse(function (Browser $browser) use ($hostId, $smokeRunSlug) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/live-edit?url=' . urlencode((string) content_link($hostId)))->pause(5000);
            $browser->waitFor('iframe', 20)->pause(2500);

            // Open the post-module-settings slideOver via the same
            // window event the inline module-edit pencil dispatches.
            $browser->script(
                "
                window.dispatchEvent(new CustomEvent('openModuleSettingsAction', {
                    detail: {
                        moduleSettingsComponent: 'Modules\\\\Post\\\\Filament\\\\PostModuleSettings',
                        params: { id: " . json_encode('tablemodal-' . $smokeRunSlug) . " }
                    }
                }));
            "
            );
            $browser->pause(6000);

            // Wait for the post-module-settings iframe inside the
            // outer slideOver.
            $browser->waitUsing(20, 250, function () use ($browser) {
                $found = $browser->script(
                    "
                    return Array.from(document.querySelectorAll('iframe'))
                        .some(f => (f.src || '').indexOf('post-module-settings') !== -1) ? 1 : 0;
                "
                );
                return ($found[0] ?? 0) === 1;
            });

            // Click "New post" inside the iframe.
            $clickNew = $browser->script(
                "
                return (function () {
                    var iframe = Array.from(document.querySelectorAll('iframe'))
                        .find(f => (f.src || '').indexOf('post-module-settings') !== -1);
                    if (!iframe || !iframe.contentDocument) return 'NO_IFRAME';
                    var btn = Array.from(iframe.contentDocument.querySelectorAll('button, a'))
                        .find(b => {
                            var attrs = b.attributes;
                            for (var i = 0; i < attrs.length; i++) {
                                var a = attrs[i];
                                if (!a.name.startsWith('wire:click')) continue;
                                if ((a.value || '').indexOf(\"mountAction('create'\") !== -1) return true;
                            }
                            return false;
                        });
                    if (!btn) return 'NO_NEW_BTN';
                    btn.click();
                    return 'OK';
                })();
            "
            );
            $this->assertSame('OK', (string) ($clickNew[0] ?? ''), 'Clicking New post button failed');

            // Wait for the action modal to appear inside the iframe.
            $browser->waitUsing(15, 250, function () use ($browser) {
                $found = $browser->script(
                    "
                    var iframe = Array.from(document.querySelectorAll('iframe'))
                        .find(f => (f.src || '').indexOf('post-module-settings') !== -1);
                    if (!iframe || !iframe.contentDocument) return 0;
                    return iframe.contentDocument.querySelector('.fi-modal-window') ? 1 : 0;
                "
                );
                return ($found[0] ?? 0) === 1;
            });

            $modalShape = $browser->script(
                "
                return (function () {
                    var iframe = Array.from(document.querySelectorAll('iframe'))
                        .find(f => (f.src || '').indexOf('post-module-settings') !== -1);
                    if (!iframe || !iframe.contentDocument) return null;
                    var modal = iframe.contentDocument.querySelector('.fi-modal-window');
                    if (!modal) return null;
                    return {
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
                'task-2026-05-02-2ecfe6 regressed: the New post modal inside the '
                . 'post-module-settings iframe is being rendered as a slideOver again. '
                . 'Modal classes: ' . $combinedClasses
            );

            // Hard guard #2: width hint is still FiveExtraLarge.
            // Bumped from ThreeExtraLarge → FiveExtraLarge in
            // task-2026-05-04-61e974.
            $this->assertStringContainsString(
                'fi-width-5xl',
                (string) $shape['classNames'],
                'task-2026-05-04-61e974 regressed: the New post modal lost its '
                . 'modalWidth(FiveExtraLarge) hint. Modal classes: ' . $shape['classNames']
            );
        });
    }

    private function createPostsHostPage(string $slug): int
    {
        $pageSlug = self::SLUG_PREFIX . 'host-' . $slug;
        $title = 'TableModal host ' . $slug;

        $content = '<div class="edit container py-5">'
            . '<module type="posts" data-limit="50" template="default" />'
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
                'createPostsHostPage: save_content returned non-id value: ' . var_export($id, true)
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

<?php

declare(strict_types=1);

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use Modules\Content\Models\Content;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Support\LandingTestContentPurger;
use Tests\Browser\Support\MwDialogLiveEditHelpers;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Live-edit Dusk coverage for Filament ->mwDialog() / mw.dialog host.
 *
 * Verifies the opted-in modules (video, posts) open in mw.dialog — not a
 * full-screen Filament slide-over — keep Livewire handles after adding a
 * post, and can be switched without losing the parent page.
 */
class LiveEditFilamentMwDialogTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';
    private const SLUG_PREFIX = 'mwdialog-';

    /** @var int[] */
    private array $createdIds = [];

    #[Test]
    public function video_and_posts_open_in_mw_dialog_and_livewire_survives_add_post(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        $slug = substr(md5(microtime(true) . Str::random(6)), 0, 10);
        $hostPageId = $this->createHostPage($slug);
        $createdTitle = 'MwDialog-add ' . $slug;

        $this->browse(function (Browser $browser) use ($hostPageId, $createdTitle) {
            $this->loginAsAdmin($browser);

            $pageLink = (string) content_link($hostPageId);
            $browser->visit('/admin/live-edit?url=' . urlencode($pageLink))
                ->pause(5000)
                ->waitFor('iframe', 20)
                ->pause(1500);

            $browser->script(
                "
                window.dispatchEvent(new CustomEvent('openModuleSettingsAction', {
                    detail: {
                        moduleSettingsComponent: 'Modules\\\\Video\\\\Filament\\\\VideoModuleSettings',
                        params: { id: 'mwdialog-video' }
                    }
                }));
            "
            );
            $browser->pause(4000);

            $browser->waitUsing(20, 250, function () use ($browser) {
                $found = $browser->script(MwDialogLiveEditHelpers::settingsSurfaceReadyScript());

                return ($found[0] ?? 0) === 1;
            });

            $videoShape = $browser->script(
                "
                return (function () {
                    var holder = document.querySelector('.mw-dialog-holder, .mw-dialog .mw-dialog-holder');
                    var dlg = document.querySelector('.mw-dialog.mw-filament-mw-dialog-window, .mw-dialog.active');
                    var inner = document.querySelector('[data-testid=\"filament-mw-dialog-inner\"], [data-testid=\"filament-mw-dialog-body\"]');
                    var slide = document.querySelector('.fi-modal-slide-over.fi-modal-open, .fi-modal-window.mw-module-settings-live-edit-modal');
                    var measure = holder || inner;
                    var rect = measure ? measure.getBoundingClientRect() : null;
                    return {
                        hasDialog: !!(dlg || inner || holder),
                        hasClose: !!(document.querySelector('.mw-dialog-close, .mw-dialog .mw-dialog-header')),
                        hasHeader: !!(document.querySelector('.mw-dialog-header')),
                        isSlideOver: !!(slide && slide.getBoundingClientRect().width > 0),
                        width: rect ? rect.width : 0,
                        viewport: window.innerWidth
                    };
                })();
            "
            );
            $shape = $videoShape[0] ?? [];
            $this->assertTrue((bool) ($shape['hasDialog'] ?? false), 'Video settings must open inside mw.dialog');
            $this->assertFalse((bool) ($shape['isSlideOver'] ?? true), 'Video settings must not be a full-screen slide-over');
            if (($shape['width'] ?? 0) > 0 && ($shape['viewport'] ?? 0) > 0) {
                $this->assertLessThan(
                    (int) $shape['viewport'] - 20,
                    (int) $shape['width'],
                    'mw.dialog must not take over the whole viewport width'
                );
            }

            $browser->script(
                "
                if (window.Livewire) {
                    window.Livewire.dispatch('closeModal', { force: true });
                }
                window.dispatchEvent(new Event('closeFilamentSlideOver'));
            "
            );
            $browser->pause(1500);

            $browser->script(
                "
                window.dispatchEvent(new CustomEvent('openModuleSettingsAction', {
                    detail: {
                        moduleSettingsComponent: 'Modules\\\\Post\\\\Filament\\\\PostModuleSettings',
                        params: { id: 'mwdialog-posts' }
                    }
                }));
            "
            );
            $browser->pause(5000);

            $browser->waitUsing(20, 250, function () use ($browser) {
                $found = $browser->script(MwDialogLiveEditHelpers::settingsSurfaceReadyScript());

                return ($found[0] ?? 0) === 1;
            });

            $clickNew = $browser->script(MwDialogLiveEditHelpers::clickCreateTableActionScript());
            $this->assertSame('OK', (string) ($clickNew[0] ?? ''), 'New post button must stay clickable inside mw.dialog');
            $browser->pause(2500);

            $browser->waitUsing(15, 250, function () use ($browser) {
                $found = $browser->script(MwDialogLiveEditHelpers::findMountedActionFormScript());

                return ($found[0] ?? 0) === 1;
            });

            $save = $browser->script(
                "
                return (async function () {
                    try {
                        var title = " . json_encode($createdTitle) . ";
                        var roots = Array.from(document.querySelectorAll('[wire\\\\:id]'));
                        for (var i = 0; i < roots.length; i++) {
                            var snap = roots[i].getAttribute('wire:snapshot') || '';
                            if (snap.indexOf('contentModel') !== -1
                                && snap.indexOf('tableRecordsPerPage') !== -1) {
                                var w = window.Livewire.find(roots[i].getAttribute('wire:id'));
                                if (!w) continue;
                                await w.set('mountedActions.0.data.title', title);
                                await w.set('mountedActions.0.data.is_active', true);
                                await w.callMountedAction();
                                return 'OK';
                            }
                        }
                        return 'NO_ROOT';
                    } catch (e) { return 'EXC:' + (e && e.message ? e.message : e); }
                })();
            "
            );
            $this->assertSame('OK', (string) ($save[0] ?? ''), 'Livewire create action must run inside mw.dialog');

            $deadline = microtime(true) + 15.0;
            $foundAdd = false;
            do {
                $row = Content::where('title', $createdTitle)->where('content_type', 'post')->first();
                if ($row) {
                    $foundAdd = true;
                    $this->createdIds[] = (int) $row->id;
                    break;
                }
                usleep(500_000);
            } while (microtime(true) < $deadline);

            $this->assertTrue(
                $foundAdd,
                "Add post via mw.dialog did not persist '{$createdTitle}' within 15s"
            );

            $stillAlive = $browser->script(
                "
                return (function () {
                    var hasDialog = !!document.querySelector('[data-testid=\"filament-mw-dialog-inner\"], .mw-dialog.mw-filament-mw-dialog-window, [data-mw-dialog-skin=\"1\"]');
                    var hasTable = Array.from(document.querySelectorAll('[wire\\\\:id]')).some(function (el) {
                        var snap = el.getAttribute('wire:snapshot') || '';
                        return snap.indexOf('contentModel') !== -1;
                    });
                    return hasDialog && hasTable ? 1 : 0;
                })();
            "
            );
            $this->assertSame(
                1,
                (int) ($stillAlive[0] ?? 0),
                'After adding a post the posts module Livewire host must still be mounted in mw.dialog'
            );
        });
    }

    private function createHostPage(string $slug): int
    {
        $pageSlug = self::SLUG_PREFIX . 'host-' . $slug;
        $content = '<div class="edit container py-5">'
            . '<module type="video" id="mwdialog-video" />'
            . '<module type="posts" data-limit="50" template="default" />'
            . '</div>';

        $id = save_content([
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => 'MwDialog host ' . $slug,
            'url' => $pageSlug,
            'active_site_template' => 'Bootstrap',
            'is_active' => 1,
            'content' => $content,
        ]);

        if (! $id || ! is_numeric($id)) {
            throw new \RuntimeException('createHostPage failed: ' . var_export($id, true));
        }

        $this->createdIds[] = (int) $id;

        return (int) $id;
    }

    private function ensureAdminUser(): void
    {
        $user = User::where('email', self::ADMIN_EMAIL)->first()
            ?: User::where('username', 'admin')->first();
        if (! $user) {
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
        if ($user->email !== self::ADMIN_EMAIL) {
            $user->email = self::ADMIN_EMAIL;
        }
        $dirty = false;
        if ((int) $user->is_admin !== 1) {
            $user->is_admin = 1;
            $dirty = true;
        }
        if ((int) $user->is_active !== 1) {
            $user->is_active = 1;
            $dirty = true;
        }
        if ((int) $user->is_verified !== 1) {
            $user->is_verified = 1;
            $dirty = true;
        }
        $user->password = Hash::make(self::ADMIN_PASSWORD);
        $dirty = true;
        if ($dirty) {
            $user->save();
        }
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

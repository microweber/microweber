<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsMobileViewport;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Mobile-viewport regression guard for the Filament admin shell.
 *
 * Sibling to {@see PublicFrontendMobileSmokeTest} — that one
 * covers the public-facing pages, this one covers the admin.
 * Earlier hand-found regressions on mobile-admin included:
 *   - `/admin/pages` empty-state heading clipped on both sides
 *     (task-2026-04-26-5ca263).
 *   - `/admin/products` table overflowing 32px past the viewport
 *     (task-2026-04-26-5ca263).
 *   - `/admin/products` search input squashed to "Sea…" placeholder
 *     (task-2026-04-26-5ca263).
 *
 * The fixes that resolved those regressions live in
 * `packages/microweber-filament-theme/resources/assets/css/`. This
 * test file is the regression catcher — it visits a representative
 * sample of admin pages at 390×844 and asserts each one fits in
 * the viewport horizontally:
 *
 *   1. /admin (dashboard with stat cards + chart)
 *   2. /admin/pages (resource list — table card + empty state)
 *   3. /admin/btn-module-settings (live-edit module settings page
 *      with Filament Tabs container, the canonical shape of every
 *      MSET smoke).
 *
 * If a future Filament theme tweak or settings-page edit breaks
 * mobile, this test fails before the regression ships.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 */
class LiveAdminMobileSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsMobileViewport;
    use AssertsSkinConsoleClean;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    #[Test]
    public function admin_dashboard_renders_without_horizontal_overflow_on_mobile(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin');

            $this->withMobileViewport($browser, function (Browser $browser): void {
                $browser->pause(800);
                $this->assertNoHorizontalOverflowOnMobile($browser, 'admin dashboard');
            });
        });
    }

    #[Test]
    public function admin_pages_list_renders_without_horizontal_overflow_on_mobile(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/pages');

            $this->withMobileViewport($browser, function (Browser $browser): void {
                $browser->pause(800);
                $this->assertNoHorizontalOverflowOnMobile($browser, 'admin pages list');
            });
        });
    }

    #[Test]
    public function btn_module_settings_renders_without_horizontal_overflow_on_mobile(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/btn-module-settings');

            $this->withMobileViewport($browser, function (Browser $browser): void {
                $browser->pause(800);
                $this->assertNoHorizontalOverflowOnMobile(
                    $browser,
                    'btn module settings page'
                );
            });
        });
    }
}

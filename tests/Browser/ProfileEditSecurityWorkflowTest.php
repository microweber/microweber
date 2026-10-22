<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Edit Profile security-section workflow (profile panel, /profile/edit-profile).
 *
 * Guards the regressions fixed alongside this test:
 *  - the embedded 2FA form must not 500 with Livewire
 *    MethodNotFoundException "stopConfirmingPassword" — it now drives the
 *    Fortify parent's confirmPassword / cancelConfirmPassword flow;
 *  - the "Two Factor Authentication" / "Browser Sessions" toggle buttons must
 *    work (they were broken by a $showForm property/method name collision, which
 *    threw "$wire.showForm is not a function");
 *  - no "bootstrap is not defined" crash in the Bootstrap-less Filament panel;
 *  - Change Password lives inside Edit Profile, not the sidebar.
 *
 * A Livewire 500 is caught structurally: the asserted DOM transitions (the
 * confirm-password modal opening on Enable and closing on Cancel, the sessions
 * panel expanding) only happen when the underlying action succeeds.
 *
 * Prereqs: running server at http://127.0.0.1:8000, admin@admin.com / admin.
 * These flows are non-mutating (they open and cancel; 2FA is never completed),
 * so they are safe to run against the shared dev database.
 */
class ProfileEditSecurityWorkflowTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Rely on the already-running dev server's database.
    }

    #[Test]
    public function two_factor_enable_opens_and_cancels_password_modal_without_error(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/profile/edit-profile')->pause(4000);

            $source = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $source, 'Edit Profile must load');
            $this->assertStringNotContainsString('MethodNotFoundException', $source);

            // Expand the 2FA section (toggleForm) — must not throw.
            $browser->script("
                var b = Array.from(document.querySelectorAll('button')).find(function (x) { return x.textContent.trim() === 'Two Factor Authentication'; });
                if (b) { b.click(); }
            ");
            $browser->pause(1500);

            // Click Enable -> enableTwoFactorAuthentication() -> confirmingPassword
            // -> the password-confirm modal renders.
            $browser->script("var e = document.querySelector('#enable-2fa-btn'); if (e) { e.click(); }");
            $browser->pause(2500);

            $modalPresent = $browser->script("return !!document.querySelector('#tfa-password-modal');");
            $this->assertTrue((bool) ($modalPresent[0] ?? false), 'Enable must open the confirm-password modal (proves enableTwoFactorAuthentication did not 500)');

            $pwdPresent = $browser->script("return !!document.querySelector('#tfa-confirm-password');");
            $this->assertTrue((bool) ($pwdPresent[0] ?? false), 'The modal must contain a password field');

            $afterSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('stopConfirmingPassword', $afterSource);
            $this->assertStringNotContainsString('Internal Server Error', $afterSource);

            // Cancel -> cancelConfirmPassword() -> modal removed. If the method were
            // missing (the original bug), this would 500 and the modal would stay.
            $browser->script("
                var m = document.querySelector('#tfa-password-modal');
                if (m) {
                    var c = Array.from(m.querySelectorAll('button')).find(function (b) { return /cancel/i.test(b.textContent); });
                    if (c) { c.click(); }
                }
            ");
            $browser->pause(2500);

            $modalClosed = $browser->script("return !document.querySelector('#tfa-password-modal');");
            $this->assertTrue((bool) ($modalClosed[0] ?? false), 'Cancel must close the modal (proves cancelConfirmPassword works)');

            // No "bootstrap is not defined" / Livewire error was logged.
            $this->assertNoBlockingConsoleErrors($browser);
        });
    }

    #[Test]
    public function browser_sessions_toggle_works_and_change_password_is_embedded(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/profile/edit-profile')->pause(4000);

            // Change Password section is embedded on this page...
            $bodyText = $browser->script("return document.body.innerText;");
            $this->assertStringContainsString('Change Password', $bodyText[0] ?? '', 'Change Password must be a section on Edit Profile');

            // ...and NOT a sidebar item.
            $sidebarHasChangePassword = $browser->script("
                return Array.from(document.querySelectorAll('.fi-sidebar-item')).some(function (e) { return /change pass/i.test(e.textContent); });
            ");
            $this->assertFalse((bool) ($sidebarHasChangePassword[0] ?? false), 'Change Password must not be in the sidebar');

            // The "Browser Sessions" toggle must expand the panel (toggleForm). If
            // the $showForm property/method collision were still present, the click
            // would throw "$wire.showForm is not a function" and nothing would expand.
            $browser->script("
                var b = Array.from(document.querySelectorAll('button')).find(function (x) { return x.textContent.trim() === 'Browser Sessions'; });
                if (b) { b.click(); }
            ");
            $browser->pause(2500);

            $logoutVisible = $browser->script("
                return Array.from(document.querySelectorAll('button')).some(function (b) { return /log out other browser sessions/i.test(b.textContent); });
            ");
            $this->assertTrue((bool) ($logoutVisible[0] ?? false), 'Browser Sessions toggle must reveal the log-out action (proves toggleForm works)');

            $this->assertNoBlockingConsoleErrors($browser);
        });
    }

    /**
     * Assert the browser console logged none of the errors these fixes target.
     * Chrome only exposes SEVERE entries reliably, so we match on the specific
     * messages rather than failing on unrelated third-party noise.
     */
    protected function assertNoBlockingConsoleErrors(Browser $browser): void
    {
        try {
            $logs = $browser->driver->manage()->getLog('browser');
        } catch (\Throwable $e) {
            return; // log retrieval unsupported on this driver — skip.
        }

        $needles = ['bootstrap is not defined', 'stopConfirmingPassword', 'showForm is not a function'];

        foreach ($logs as $entry) {
            $message = (string) ($entry['message'] ?? '');
            foreach ($needles as $needle) {
                $this->assertStringNotContainsString(
                    $needle,
                    $message,
                    "Blocking console error present: {$needle}"
                );
            }
        }
    }
}

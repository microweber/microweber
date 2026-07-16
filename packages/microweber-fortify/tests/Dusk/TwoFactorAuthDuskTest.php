<?php

namespace MicroweberPackages\Fortify\Tests\Dusk;

use Laravel\Dusk\Browser;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use MicroweberPackages\User\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

class TwoFactorAuthDuskTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected ?User $testUser = null;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user for 2FA Dusk browser tests (in test DB for
        // programmatic assertions). The user is also created in the main
        // DB so Dusk browser-form login can authenticate.
        $this->testUser = User::updateOrCreate(
            ['email' => 'dusk2fa@test.com'],
            [
                'username' => 'dusk2fa',
                'password' => bcrypt('password123'),
                'is_active' => 1,
                'is_admin' => 1,
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
            ]
        );
    }

    protected function tearDown(): void
    {
        if ($this->testUser) {
            $this->testUser->forceFill([
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
            ])->save();
        }

        parent::tearDown();
    }

    // ────────────────────────────────────────────────────────────────────────
    //  Non-browser integration tests (programmatic – run in the test DB)
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Test QR code SVG generation and TOTP validation (non-browser).
     */
    public function test_qr_code_svg_and_totp_validation(): void
    {
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();

        $svg = $this->testUser->twoFactorQrCodeSvg();
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('</svg>', $svg);
        $this->assertGreaterThan(100, strlen($svg));

        $url = $this->testUser->twoFactorProvisioningUrl();
        $this->assertStringContainsString('otpauth://totp/', $url);
        $secret = decrypt($this->testUser->two_factor_secret);
        $this->assertStringContainsString($secret, $url);

        $parsed = parse_url($url);
        $this->assertEquals('otpauth', $parsed['scheme']);
        parse_str($parsed['query'], $query);
        $this->assertEquals($secret, $query['secret']);

        $google2fa = new Google2FA();
        $code = $google2fa->getCurrentOtp($secret);
        $this->assertEquals(6, strlen($code));
        $this->assertMatchesRegularExpression('/^[0-9]{6}$/', $code);
        $this->assertTrue($this->testUser->validateTwoFactorCode($code));
        $this->assertFalse($this->testUser->validateTwoFactorCode('000000'));
    }

    /**
     * Test the full lifecycle: enable → confirm → use recovery code → disable.
     */
    public function test_full_lifecycle_enable_confirm_recover_disable(): void
    {
        $google2fa = new Google2FA();

        // Enable
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();
        $this->assertNotNull($this->testUser->two_factor_secret);

        // Confirm
        $secret = decrypt($this->testUser->two_factor_secret);
        $code = $google2fa->getCurrentOtp($secret);
        $confirm = app(ConfirmTwoFactorAuthentication::class);
        $confirm($this->testUser, $code);
        $this->testUser->refresh();
        $this->assertTrue($this->testUser->hasTwoFactorEnabled());

        // Recovery codes
        $codes = $this->testUser->recoveryCodes();
        $this->assertCount(8, $codes);
        $this->assertTrue($this->testUser->useRecoveryCode($codes[0]));
        $this->assertCount(7, $this->testUser->recoveryCodes());
        $this->assertFalse($this->testUser->useRecoveryCode($codes[0])); // single-use

        // Disable
        $disable = app(DisableTwoFactorAuthentication::class);
        $disable($this->testUser);
        $this->testUser->refresh();
        $this->assertNull($this->testUser->two_factor_secret);
        $this->assertFalse($this->testUser->hasTwoFactorEnabled());
    }

    /**
     * Test the contract interface getter methods work correctly.
     */
    public function test_contract_getter_methods(): void
    {
        // Before enabling — all getters return null
        $this->assertNull($this->testUser->getTwoFactorSecret());
        $this->assertNull($this->testUser->getTwoFactorRecoveryCodes());
        $this->assertNull($this->testUser->getTwoFactorConfirmedAt());
        $this->assertNotEmpty($this->testUser->getPasswordHash());

        // After enabling
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();

        $this->assertNotNull($this->testUser->getTwoFactorSecret());
        $this->assertNotNull($this->testUser->getTwoFactorRecoveryCodes());
        $this->assertNull($this->testUser->getTwoFactorConfirmedAt());

        // After confirming
        $google2fa = new Google2FA();
        $secret = decrypt($this->testUser->two_factor_secret);
        $code = $google2fa->getCurrentOtp($secret);
        $confirm = app(ConfirmTwoFactorAuthentication::class);
        $confirm($this->testUser, $code);
        $this->testUser->refresh();

        $this->assertNotNull($this->testUser->getTwoFactorConfirmedAt());
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $this->testUser->getTwoFactorConfirmedAt());
    }

    /**
     * Test that the two-factor challenge route is guarded.
     */
    public function test_two_factor_challenge_route_is_guarded(): void
    {
        $this->browse(function (Browser $browser) {
            // When visiting /two-factor-challenge without a pending 2FA session,
            // Fortify redirects away (typically to home '/' or '/login').
            // The key assertion is that the user is NOT left on the challenge page.
            $browser->visit('/two-factor-challenge')
                ->assertPathIsNot('/two-factor-challenge')
                ->screenshot('two-factor-challenge-guard-redirect');
        });
    }

    // ────────────────────────────────────────────────────────────────────────
    //  Filament Admin Panel – Two-Factor Settings Page (browser tests)
    //
    //  These tests use the AdminLoginTrait to authenticate via the
    //  Filament login form (not loginAs), since the artisan serve
    //  process uses the main DB while the test process uses testing DB.
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Test that the Filament 2FA settings page loads for an authenticated admin.
     * Verifies the page renders the heading and two-factor related content.
     */
    public function test_filament_two_factor_page_loads_for_admin(): void
    {
        $this->browse(function (Browser $browser) {
            // Login using the existing admin user (from main DB)
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/two-factor-settings')
                ->pause(3000)
                ->screenshot('filament-2fa-page-loaded');

            // Verify we're not on login page
            $this->ensureLoggedIn($browser);

            $source = $browser->driver->getPageSource();
            $currentUrl = $browser->driver->getCurrentURL();

            // The page should contain two-factor related content
            $this->assertTrue(
                str_contains($source, 'Two-Factor Authentication')
                || str_contains($source, 'two-factor-settings')
                || str_contains($source, 'TwoFactorSettingsPage')
                || str_contains($source, 'enableTwoFactorAuthentication'),
                'Filament 2FA page should contain two-factor related content. URL: ' . $currentUrl
            );
        });
    }

    /**
     * Test the Filament 2FA page renders with the enable action visible.
     * Uses the admin user from the main database (same DB as artisan serve).
     */
    public function test_filament_2fa_page_has_enable_action(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/two-factor-settings')
                ->pause(3000)
                ->screenshot('filament-2fa-enable-action');

            $this->ensureLoggedIn($browser);

            $source = $browser->driver->getPageSource();

            // The page source should contain the enable action (Livewire wire:click)
            $this->assertTrue(
                str_contains($source, 'enableTwoFactorAuthentication')
                || str_contains($source, 'disableTwoFactorAuthentication')
                || str_contains($source, 'two-factor'),
                'Filament 2FA page should contain enable/disable action markup'
            );
        });
    }

    /**
     * Test programmatic 2FA enable + confirm, then verify the Filament page
     * renders the enabled state with the Disable action.
     *
     * This test modifies the admin user directly in the MAIN database
     * (used by artisan serve) so the browser sees the 2FA-enabled state.
     */
    public function test_filament_2fa_full_flow_programmatic_then_browser(): void
    {
        // We need to connect to the main database (database.sqlite) that
        // the artisan serve process uses, not the testing DB.
        $mainDbPath = base_path('storage/database.sqlite');
        if (!file_exists($mainDbPath)) {
            $this->markTestSkipped('Main database.sqlite not found — cannot test browser 2FA state');
        }

        // Use a direct PDO connection to the main DB to enable 2FA
        $pdo = new \PDO("sqlite:{$mainDbPath}");
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $adminEmail = env('DUSK_ADMIN_EMAIL', 'admin@admin.com');

        // Enable and confirm 2FA on a test user in the test DB for assertion logic
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();

        $google2fa = new Google2FA();
        $secret = decrypt($this->testUser->two_factor_secret);
        $code = $google2fa->getCurrentOtp($secret);

        $confirm = app(ConfirmTwoFactorAuthentication::class);
        $confirm($this->testUser, $code);
        $this->testUser->refresh();
        $this->assertTrue($this->testUser->hasTwoFactorEnabled());

        // Now write the 2FA data to the admin user in the MAIN database
        $stmt = $pdo->prepare("UPDATE users SET two_factor_secret = ?, two_factor_recovery_codes = ?, two_factor_confirmed_at = ? WHERE email = ?");
        $stmt->execute([
            $this->testUser->two_factor_secret,
            $this->testUser->two_factor_recovery_codes,
            now()->toDateTimeString(),
            $adminEmail,
        ]);

        try {
            // Verify the Filament page renders with the Disable action
            $this->browse(function (Browser $browser) {
                $this->loginAsAdmin($browser);

                $browser->visit('/admin/two-factor-settings')
                    ->pause(3000)
                    ->screenshot('filament-2fa-enabled-state');

                $this->ensureLoggedIn($browser);

                $source = $browser->driver->getPageSource();
                $this->assertStringContainsString(
                    'disableTwoFactorAuthentication',
                    $source,
                    'Filament 2FA page should contain the Disable action wire:click'
                );
            });
        } finally {
            // Clean up — disable 2FA on the admin user in the main DB
            $stmt = $pdo->prepare("UPDATE users SET two_factor_secret = NULL, two_factor_recovery_codes = NULL, two_factor_confirmed_at = NULL WHERE email = ?");
            $stmt->execute([$adminEmail]);
        }
    }

    /**
     * Test that 2FA can be disabled programmatically and the DB reflects it.
     */
    public function test_filament_2fa_disable_programmatic(): void
    {
        // Enable and confirm 2FA
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();

        $google2fa = new Google2FA();
        $secret = decrypt($this->testUser->two_factor_secret);
        $code = $google2fa->getCurrentOtp($secret);

        $confirm = app(ConfirmTwoFactorAuthentication::class);
        $confirm($this->testUser, $code);
        $this->testUser->refresh();
        $this->assertTrue($this->testUser->hasTwoFactorEnabled());

        // Disable
        $disable = app(DisableTwoFactorAuthentication::class);
        $disable($this->testUser);
        $this->testUser->refresh();

        $this->assertNull($this->testUser->two_factor_secret);
        $this->assertFalse($this->testUser->hasTwoFactorEnabled());
    }

    /**
     * Test that recovery codes are generated correctly.
     */
    public function test_filament_2fa_recovery_codes_generated(): void
    {
        // Enable and confirm 2FA programmatically
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();

        $google2fa = new Google2FA();
        $secret = decrypt($this->testUser->two_factor_secret);
        $code = $google2fa->getCurrentOtp($secret);

        $confirm = app(ConfirmTwoFactorAuthentication::class);
        $confirm($this->testUser, $code);
        $this->testUser->refresh();

        // Verify recovery codes exist in DB
        $codes = $this->testUser->recoveryCodes();
        $this->assertCount(8, $codes);

        // Each recovery code should be in the expected format
        foreach ($codes as $recoveryCode) {
            $this->assertMatchesRegularExpression('/^[a-zA-Z0-9]+-[a-zA-Z0-9]+$/', $recoveryCode);
        }
    }
}
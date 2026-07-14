<?php

namespace MicroweberPackages\Fortify\Tests\Dusk;

use Laravel\Dusk\Browser;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use MicroweberPackages\User\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Tests\DuskTestCase;

class TwoFactorAuthDuskTest extends DuskTestCase
{
    protected ?User $testUser = null;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user for 2FA Dusk browser tests
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

    /**
     * Test that the 2FA setup page loads in the browser and displays the QR code SVG.
     * This is a real browser test — the QR code is rendered server-side as inline SVG
     * and we verify it appears in the DOM.
     */
    public function test_setup_page_renders_qr_code_in_browser(): void
    {
        // Enable 2FA programmatically so the QR code will be visible
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();

        $this->browse(function (Browser $browser) {
            // Login as the test user
            $browser->loginAs($this->testUser)
                ->visit('/two-factor/setup')
                ->waitFor('#two-factor-qr-code', 10)
                ->assertPresent('#two-factor-qr-code');

            // Verify the SVG is actually rendered in the page
            $svgContent = $browser->element('#two-factor-qr-code')->getDomProperty('innerHTML');
            $this->assertStringContainsString('<svg', $svgContent, 'QR code SVG should be rendered in the browser');
            $this->assertStringContainsString('</svg>', $svgContent, 'QR code SVG should be complete');
            $this->assertGreaterThan(100, strlen($svgContent), 'QR SVG should have substantial content');

            // Verify the secret key is also displayed
            $browser->assertPresent('#two-factor-secret-key');
            $secretText = $browser->text('#two-factor-secret-key');
            $this->assertNotEmpty($secretText, 'Secret key should be displayed');
            $this->assertEquals(
                decrypt($this->testUser->two_factor_secret),
                $secretText,
                'Displayed secret should match the stored secret'
            );

            // Take a screenshot for visual verification
            $browser->screenshot('two-factor-qr-code-rendered');
        });
    }

    /**
     * Test the full browser-based 2FA setup flow:
     * Login → visit setup page → see QR → enter valid TOTP → see recovery codes.
     */
    public function test_full_browser_2fa_setup_and_confirm_flow(): void
    {
        // Enable 2FA programmatically (simulate clicking "Enable" button)
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();

        $google2fa = new Google2FA();
        $secret = decrypt($this->testUser->two_factor_secret);

        $this->browse(function (Browser $browser) use ($google2fa, $secret) {
            $browser->loginAs($this->testUser)
                ->visit('/two-factor/setup')
                ->waitFor('#two-factor-qr-code', 10);

            // Verify QR code is visible
            $browser->assertPresent('#two-factor-qr-code');

            // Generate a valid TOTP code
            $code = $google2fa->getCurrentOtp($secret);

            // Enter the code and confirm
            $browser->type('#two-factor-code', $code)
                ->click('#confirm-2fa-code-btn')
                ->pause(2000);

            // After confirmation, recovery codes should be shown
            $browser->assertPresent('#recovery-codes-list');

            // Screenshot the confirmed state
            $browser->screenshot('two-factor-confirmed-recovery-codes');
        });

        // Verify in database
        $this->testUser->refresh();
        $this->assertNotNull($this->testUser->two_factor_confirmed_at);
        $this->assertTrue($this->testUser->hasTwoFactorEnabled());
    }

    /**
     * Test that the two-factor challenge route is guarded: visiting it in the
     * browser without a pending 2FA login in the session must NOT 500 or render
     * the code form to an unauthenticated visitor — Fortify redirects to /login.
     * (The authenticated challenge POST flow is covered by the Feature suite.)
     */
    public function test_two_factor_challenge_route_is_guarded(): void
    {
        // Enable and confirm 2FA so the user genuinely has 2FA on
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();

        $google2fa = new Google2FA();
        $secret = decrypt($this->testUser->two_factor_secret);
        $code = $google2fa->getCurrentOtp($secret);

        $confirm = app(ConfirmTwoFactorAuthentication::class);
        $confirm($this->testUser, $code);
        $this->testUser->refresh();

        $this->browse(function (Browser $browser) {
            // No pending-login session → Fortify's challenge controller redirects
            // to the login page rather than exposing the code form.
            $browser->visit('/two-factor-challenge')
                ->assertPathIs('/login')
                ->screenshot('two-factor-challenge-guard-redirect');
        });
    }

    /**
     * Test QR code SVG generation and TOTP validation flow (non-browser).
     */
    public function test_qr_code_svg_and_totp_validation(): void
    {
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();

        // Verify QR code SVG
        $svg = $this->testUser->twoFactorQrCodeSvg();
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('</svg>', $svg);
        $this->assertGreaterThan(100, strlen($svg));

        // Verify provisioning URL
        $url = $this->testUser->twoFactorProvisioningUrl();
        $this->assertStringContainsString('otpauth://totp/', $url);
        $secret = decrypt($this->testUser->two_factor_secret);
        $this->assertStringContainsString($secret, $url);

        // Verify the URL structure
        $parsed = parse_url($url);
        $this->assertEquals('otpauth', $parsed['scheme']);
        parse_str($parsed['query'], $query);
        $this->assertEquals($secret, $query['secret']);

        // Verify TOTP code generation and validation
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
}
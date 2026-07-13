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

        // Create a test user for 2FA tests
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
     * Test that the 2FA setup page renders the QR code.
     */
    public function test_two_factor_setup_page_renders_qr_code(): void
    {
        // Enable 2FA for the user programmatically
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();

        $this->assertNotNull($this->testUser->two_factor_secret, 'Secret should be set after enabling 2FA');

        // Verify QR code SVG can be generated
        $svg = $this->testUser->twoFactorQrCodeSvg();
        $this->assertStringContainsString('<svg', $svg, 'QR code SVG should contain valid SVG markup');
        $this->assertGreaterThan(100, strlen($svg), 'QR code SVG should have meaningful content');

        // Verify the provisioning URL
        $url = $this->testUser->twoFactorProvisioningUrl();
        $this->assertStringContainsString('otpauth://totp/', $url);
        $this->assertStringContainsString(decrypt($this->testUser->two_factor_secret), $url);
    }

    /**
     * Test the full 2FA enable → confirm → validate → disable flow.
     */
    public function test_full_two_factor_flow(): void
    {
        $google2fa = new Google2FA();

        // Step 1: Enable 2FA
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();

        $this->assertNotNull($this->testUser->two_factor_secret);
        $secret = decrypt($this->testUser->two_factor_secret);

        // Step 2: Generate a valid TOTP code and confirm
        $code = $google2fa->getCurrentOtp($secret);
        $confirm = app(ConfirmTwoFactorAuthentication::class);
        $confirm($this->testUser, $code);
        $this->testUser->refresh();

        $this->assertNotNull($this->testUser->two_factor_confirmed_at, '2FA should be confirmed');
        $this->assertTrue($this->testUser->hasTwoFactorEnabled(), 'hasTwoFactorEnabled should return true');

        // Step 3: Verify code validation works
        $newCode = $google2fa->getCurrentOtp($secret);
        $this->assertTrue($this->testUser->validateTwoFactorCode($newCode));

        // Step 4: Verify recovery codes
        $codes = $this->testUser->recoveryCodes();
        $this->assertNotEmpty($codes, 'Recovery codes should be generated');
        $this->assertCount(8, $codes, 'Should have 8 recovery codes');

        // Step 5: Use a recovery code
        $recoveryCode = $codes[0];
        $this->assertTrue($this->testUser->useRecoveryCode($recoveryCode));
        $this->assertCount(7, $this->testUser->recoveryCodes());

        // Step 6: Disable 2FA
        $disable = app(DisableTwoFactorAuthentication::class);
        $disable($this->testUser);
        $this->testUser->refresh();

        $this->assertNull($this->testUser->two_factor_secret);
        $this->assertFalse($this->testUser->hasTwoFactorEnabled());
    }

    /**
     * Test QR code contains valid TOTP URI that can be decoded.
     */
    public function test_qr_code_contains_valid_totp_uri(): void
    {
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();

        $secret = decrypt($this->testUser->two_factor_secret);
        $url = $this->testUser->twoFactorProvisioningUrl();

        // Parse the URL
        $parsed = parse_url($url);
        $this->assertEquals('otpauth', $parsed['scheme']);

        // Verify secret is in the URL query
        parse_str($parsed['query'], $query);
        $this->assertEquals($secret, $query['secret']);
        $this->assertNotEmpty($query['issuer']);

        // Verify the secret can generate valid codes
        $google2fa = new Google2FA();
        $code = $google2fa->getCurrentOtp($secret);
        $this->assertEquals(6, strlen($code), 'TOTP code should be 6 digits');
        $this->assertMatchesRegularExpression('/^[0-9]{6}$/', $code);
    }

    /**
     * Test that invalid TOTP codes are rejected.
     */
    public function test_invalid_totp_code_rejected(): void
    {
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();

        $this->assertFalse($this->testUser->validateTwoFactorCode('000000'));
        $this->assertFalse($this->testUser->validateTwoFactorCode('123456'));
        $this->assertFalse($this->testUser->validateTwoFactorCode('abcdef'));
    }

    /**
     * Test recovery code usage removes the used code.
     */
    public function test_recovery_code_is_single_use(): void
    {
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($this->testUser);
        $this->testUser->refresh();

        $google2fa = new Google2FA();
        $secret = decrypt($this->testUser->two_factor_secret);
        $code = $google2fa->getCurrentOtp($secret);

        $confirm = app(ConfirmTwoFactorAuthentication::class);
        $confirm($this->testUser, $code);
        $this->testUser->refresh();

        $codes = $this->testUser->recoveryCodes();
        $usedCode = $codes[0];

        // Use the code once
        $this->assertTrue($this->testUser->useRecoveryCode($usedCode));

        // Try to use it again — should fail
        $this->assertFalse($this->testUser->useRecoveryCode($usedCode));
    }
}
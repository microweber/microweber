<?php

namespace MicroweberPackages\Fortify\Tests\Unit;

use MicroweberPackages\Fortify\Tests\TestCase;
use MicroweberPackages\User\Models\User;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorTraitTest extends TestCase
{
    private function createTestUser(array $overrides = []): User
    {
        return User::create(array_merge([
            'username' => 'testuser_' . uniqid(),
            'email' => 'test_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ], $overrides));
    }

    public function test_user_has_two_factor_methods(): void
    {
        $user = $this->createTestUser();

        $this->assertFalse($user->hasTwoFactorEnabled());
        $this->assertEmpty($user->recoveryCodes());
        $this->assertNull($user->getDecryptedTwoFactorSecret());
    }

    public function test_generate_recovery_codes(): void
    {
        $user = $this->createTestUser();
        $user->generateRecoveryCodes();
        $codes = $user->recoveryCodes();

        $this->assertCount(8, $codes);
        foreach ($codes as $code) {
            $this->assertMatchesRegularExpression('/^[a-zA-Z0-9]{10}-[a-zA-Z0-9]{10}$/', $code);
        }
    }

    public function test_enable_two_factor_and_validate_code(): void
    {
        $user = $this->createTestUser();

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $user->forceFill([
            'two_factor_secret' => encrypt($secret),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->assertTrue($user->hasTwoFactorEnabled());
        $this->assertEquals($secret, $user->getDecryptedTwoFactorSecret());

        $validCode = $google2fa->getCurrentOtp($secret);
        $this->assertTrue($user->validateTwoFactorCode($validCode));
        $this->assertFalse($user->validateTwoFactorCode('000000'));

        // Cleanup
        $user->forceFill(['two_factor_secret' => null, 'two_factor_confirmed_at' => null])->save();
    }

    public function test_qr_code_svg_generation(): void
    {
        $user = $this->createTestUser();

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $user->forceFill(['two_factor_secret' => encrypt($secret)])->save();

        $svg = $user->twoFactorQrCodeSvg();
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('</svg>', $svg);

        // Cleanup
        $user->forceFill(['two_factor_secret' => null])->save();
    }

    public function test_provisioning_url(): void
    {
        $user = $this->createTestUser();

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $user->forceFill(['two_factor_secret' => encrypt($secret)])->save();

        $url = $user->twoFactorProvisioningUrl();
        $this->assertStringContainsString('otpauth://totp/', $url);
        $this->assertStringContainsString($secret, $url);

        // Cleanup
        $user->forceFill(['two_factor_secret' => null])->save();
    }

    public function test_use_recovery_code(): void
    {
        $user = $this->createTestUser();
        $user->generateRecoveryCodes();
        $codes = $user->recoveryCodes();
        $firstCode = $codes[0];

        $this->assertTrue($user->useRecoveryCode($firstCode));
        $this->assertCount(7, $user->recoveryCodes());
        $this->assertNotContains($firstCode, $user->recoveryCodes());

        // Cleanup
        $user->forceFill(['two_factor_recovery_codes' => null])->save();
    }

    public function test_use_invalid_recovery_code(): void
    {
        $user = $this->createTestUser();
        $user->generateRecoveryCodes();

        $this->assertFalse($user->useRecoveryCode('invalid-code-here'));
        $this->assertCount(8, $user->recoveryCodes());

        // Cleanup
        $user->forceFill(['two_factor_recovery_codes' => null])->save();
    }

    public function test_has_two_factor_not_enabled_without_confirmation(): void
    {
        $user = $this->createTestUser();

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $user->forceFill(['two_factor_secret' => encrypt($secret)])->save();

        $this->assertFalse($user->hasTwoFactorEnabled());

        // Cleanup
        $user->forceFill(['two_factor_secret' => null])->save();
    }
}
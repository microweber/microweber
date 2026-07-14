<?php

namespace MicroweberPackages\Fortify\Tests\Unit;

use MicroweberPackages\Fortify\Tests\TestCase;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorTraitTest extends TestCase
{
    public function test_user_has_two_factor_methods(): void
    {
        $user = $this->createFortifyTestUser();

        $this->assertFalse($user->hasTwoFactorEnabled());
        $this->assertEmpty($user->recoveryCodes());
        $this->assertNull($user->getDecryptedTwoFactorSecret());

        $user->delete();
    }

    public function test_generate_recovery_codes(): void
    {
        $user = $this->createFortifyTestUser();
        $user->generateRecoveryCodes();
        $codes = $user->recoveryCodes();

        $this->assertCount(8, $codes);
        foreach ($codes as $code) {
            $this->assertMatchesRegularExpression('/^[a-zA-Z0-9]{10}-[a-zA-Z0-9]{10}$/', $code);
        }

        $this->cleanupFortifyUser($user);
    }

    public function test_enable_two_factor_and_validate_code(): void
    {
        $user = $this->createFortifyTestUser();

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

        $this->cleanupFortifyUser($user);
    }

    public function test_qr_code_svg_generation(): void
    {
        $user = $this->createFortifyTestUser();

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $user->forceFill(['two_factor_secret' => encrypt($secret)])->save();

        $svg = $user->twoFactorQrCodeSvg();
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('</svg>', $svg);
        $this->assertGreaterThan(100, strlen($svg), 'QR SVG should have substantial content');

        $this->cleanupFortifyUser($user);
    }

    public function test_qr_code_returns_empty_without_secret(): void
    {
        $user = $this->createFortifyTestUser();
        $this->assertEmpty($user->twoFactorQrCodeSvg());
        $user->delete();
    }

    public function test_provisioning_url(): void
    {
        $user = $this->createFortifyTestUser();

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $user->forceFill(['two_factor_secret' => encrypt($secret)])->save();

        $url = $user->twoFactorProvisioningUrl();
        $this->assertStringContainsString('otpauth://totp/', $url);
        $this->assertStringContainsString($secret, $url);

        // Verify URL structure
        $parsed = parse_url($url);
        $this->assertEquals('otpauth', $parsed['scheme']);
        parse_str($parsed['query'], $query);
        $this->assertEquals($secret, $query['secret']);
        $this->assertNotEmpty($query['issuer']);

        $this->cleanupFortifyUser($user);
    }

    public function test_provisioning_url_returns_empty_without_secret(): void
    {
        $user = $this->createFortifyTestUser();
        $this->assertEmpty($user->twoFactorProvisioningUrl());
        $user->delete();
    }

    public function test_use_recovery_code(): void
    {
        $user = $this->createFortifyTestUser();
        $user->generateRecoveryCodes();
        $codes = $user->recoveryCodes();
        $firstCode = $codes[0];

        $this->assertTrue($user->useRecoveryCode($firstCode));
        $this->assertCount(7, $user->recoveryCodes());
        $this->assertNotContains($firstCode, $user->recoveryCodes());

        $this->cleanupFortifyUser($user);
    }

    public function test_use_invalid_recovery_code(): void
    {
        $user = $this->createFortifyTestUser();
        $user->generateRecoveryCodes();

        $this->assertFalse($user->useRecoveryCode('invalid-code-here'));
        $this->assertCount(8, $user->recoveryCodes());

        $this->cleanupFortifyUser($user);
    }

    public function test_recovery_code_is_single_use(): void
    {
        $user = $this->createFortifyTestUser();
        $user->generateRecoveryCodes();
        $codes = $user->recoveryCodes();
        $code = $codes[0];

        $this->assertTrue($user->useRecoveryCode($code));
        $this->assertFalse($user->useRecoveryCode($code), 'Recovery code should only work once');

        $this->cleanupFortifyUser($user);
    }

    public function test_has_two_factor_not_enabled_without_confirmation(): void
    {
        $user = $this->createFortifyTestUser();

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $user->forceFill(['two_factor_secret' => encrypt($secret)])->save();

        $this->assertFalse($user->hasTwoFactorEnabled());

        $this->cleanupFortifyUser($user);
    }

    public function test_validate_two_factor_code_returns_false_without_secret(): void
    {
        $user = $this->createFortifyTestUser();
        $this->assertFalse($user->validateTwoFactorCode('123456'));
        $user->delete();
    }

    public function test_decrypted_secret_returns_null_without_secret(): void
    {
        $user = $this->createFortifyTestUser();
        $this->assertNull($user->getDecryptedTwoFactorSecret());
        $user->delete();
    }

    public function test_recovery_codes_returns_empty_without_codes(): void
    {
        $user = $this->createFortifyTestUser();
        $this->assertEmpty($user->recoveryCodes());
        $user->delete();
    }

    public function test_totp_code_is_six_digits(): void
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        $code = $google2fa->getCurrentOtp($secret);

        $this->assertEquals(6, strlen($code));
        $this->assertMatchesRegularExpression('/^[0-9]{6}$/', $code);
    }
}
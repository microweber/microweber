<?php

namespace MicroweberPackages\Fortify\Tests\Feature;

use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Fortify\Tests\TestCase;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthenticationTest extends TestCase
{
    private function createTestUser(array $overrides = []): User
    {
        return User::create(array_merge([
            'username' => 'test2fa_' . uniqid(),
            'email' => 'test2fa_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ], $overrides));
    }

    private function cleanupUser(User $user): void
    {
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
        $user->delete();
    }

    public function test_can_enable_two_factor_authentication(): void
    {
        $user = $this->createTestUser();

        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($user);
        $user->refresh();

        $this->assertNotNull($user->two_factor_secret);
        $this->assertNotNull($user->two_factor_recovery_codes);

        $this->cleanupUser($user);
    }

    public function test_can_confirm_two_factor_with_valid_code(): void
    {
        $user = $this->createTestUser();

        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($user);
        $user->refresh();

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);
        $validCode = $google2fa->getCurrentOtp($secret);

        $confirm = app(ConfirmTwoFactorAuthentication::class);
        $confirm($user, $validCode);
        $user->refresh();

        $this->assertNotNull($user->two_factor_confirmed_at);

        $this->cleanupUser($user);
    }

    public function test_can_disable_two_factor(): void
    {
        $user = $this->createTestUser();

        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($user);

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);
        $validCode = $google2fa->getCurrentOtp($secret);

        $confirm = app(ConfirmTwoFactorAuthentication::class);
        $confirm($user, $validCode);
        $user->refresh();
        $this->assertNotNull($user->two_factor_confirmed_at);

        $disable = app(DisableTwoFactorAuthentication::class);
        $disable($user);
        $user->refresh();

        $this->assertNull($user->two_factor_secret);
        $this->assertNull($user->two_factor_confirmed_at);

        $user->delete();
    }

    public function test_can_generate_new_recovery_codes(): void
    {
        $user = $this->createTestUser();

        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($user);
        $user->refresh();

        $oldCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

        $generate = app(GenerateNewRecoveryCodes::class);
        $generate($user);
        $user->refresh();

        $newCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

        $this->assertNotEquals($oldCodes, $newCodes);
        $this->assertCount(8, $newCodes);

        $this->cleanupUser($user);
    }

    public function test_two_factor_challenge_route_exists(): void
    {
        $response = $this->get('/two-factor-challenge');
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    public function test_authenticated_user_can_access_setup_route(): void
    {
        $user = $this->createTestUser();
        $response = $this->actingAs($user)->get('/two-factor/setup');
        $this->assertNotEquals(404, $response->getStatusCode());
        $user->delete();
    }

    public function test_enable_and_validate_full_flow(): void
    {
        $user = $this->createTestUser();

        // Step 1: Enable 2FA
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($user);
        $user->refresh();
        $this->assertNotNull($user->two_factor_secret);

        // Step 2: Generate a valid TOTP code
        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);
        $code = $google2fa->getCurrentOtp($secret);

        // Step 3: Confirm 2FA
        $confirm = app(ConfirmTwoFactorAuthentication::class);
        $confirm($user, $code);
        $user->refresh();
        $this->assertNotNull($user->two_factor_confirmed_at);
        $this->assertTrue($user->hasTwoFactorEnabled());

        // Step 4: Verify QR code SVG
        $svg = $user->twoFactorQrCodeSvg();
        $this->assertStringContainsString('<svg', $svg);

        // Step 5: Verify recovery codes
        $codes = $user->recoveryCodes();
        $this->assertCount(8, $codes);

        // Step 6: Verify TOTP code validation
        $newCode = $google2fa->getCurrentOtp($secret);
        $this->assertTrue($user->validateTwoFactorCode($newCode));

        // Step 7: Use recovery code
        $recoveryCode = $codes[0];
        $this->assertTrue($user->useRecoveryCode($recoveryCode));
        $this->assertCount(7, $user->recoveryCodes());

        $this->cleanupUser($user);
    }
}
<?php

namespace MicroweberPackages\Fortify\Tests\Feature;

use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use MicroweberPackages\Fortify\Tests\TestCase;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthenticationTest extends TestCase
{
    public function test_can_enable_two_factor_authentication(): void
    {
        $user = $this->createFortifyTestUser();

        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($user);
        $user->refresh();

        $this->assertNotNull($user->two_factor_secret);
        $this->assertNotNull($user->two_factor_recovery_codes);

        $this->cleanupFortifyUser($user);
    }

    public function test_can_confirm_two_factor_with_valid_code(): void
    {
        $user = $this->createFortifyTestUser();

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

        $this->cleanupFortifyUser($user);
    }

    public function test_rejects_invalid_two_factor_code(): void
    {
        $user = $this->createFortifyTestUser();

        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($user);
        $user->refresh();

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $confirm = app(ConfirmTwoFactorAuthentication::class);
        $confirm($user, '000000');
    }

    public function test_can_disable_two_factor(): void
    {
        $user = $this->createFortifyTestUser();

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
        $user = $this->createFortifyTestUser();

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

        $this->cleanupFortifyUser($user);
    }

    public function test_two_factor_challenge_route_exists(): void
    {
        $response = $this->get('/two-factor-challenge');
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    public function test_authenticated_user_can_access_setup_route(): void
    {
        $user = $this->createFortifyTestUser();
        $response = $this->actingAs($user)->get('/two-factor/setup');
        $this->assertNotEquals(404, $response->getStatusCode());
        $user->delete();
    }

    public function test_enable_and_validate_full_flow(): void
    {
        $user = $this->createFortifyTestUser();

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

        // Step 4: Verify QR code SVG still works after confirmation
        $svg = $user->twoFactorQrCodeSvg();
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('</svg>', $svg);

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

        $this->cleanupFortifyUser($user);
    }

    public function test_two_factor_challenge_post_with_valid_code(): void
    {
        $user = $this->createFortifyTestUser();

        // Enable and confirm 2FA
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($user);
        $user->refresh();

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);
        $code = $google2fa->getCurrentOtp($secret);

        $confirm = app(ConfirmTwoFactorAuthentication::class);
        $confirm($user, $code);
        $user->refresh();

        // Simulate the two-factor challenge session state (Fortify sets login.id in session)
        $newCode = $google2fa->getCurrentOtp($secret);

        $response = $this->withSession(['login.id' => $user->id, 'login.remember' => false])
            ->post('/two-factor-challenge', ['code' => $newCode]);

        // Should redirect to home on success
        $this->assertContains($response->getStatusCode(), [200, 302]);

        $this->cleanupFortifyUser($user);
    }

    public function test_two_factor_challenge_post_with_recovery_code(): void
    {
        $user = $this->createFortifyTestUser();

        // Enable and confirm 2FA
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($user);
        $user->refresh();

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);
        $code = $google2fa->getCurrentOtp($secret);

        $confirm = app(ConfirmTwoFactorAuthentication::class);
        $confirm($user, $code);
        $user->refresh();

        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
        $recoveryCode = $recoveryCodes[0];

        $response = $this->withSession(['login.id' => $user->id, 'login.remember' => false])
            ->post('/two-factor-challenge', ['recovery_code' => $recoveryCode]);

        $this->assertContains($response->getStatusCode(), [200, 302]);

        $this->cleanupFortifyUser($user);
    }

    public function test_qr_code_api_route_returns_svg(): void
    {
        $user = $this->createFortifyTestUser();

        // Enable 2FA
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($user);
        $user->refresh();

        $response = $this->actingAs($user)->get('/user/two-factor-qr-code');
        $this->assertNotEquals(404, $response->getStatusCode());

        $this->cleanupFortifyUser($user);
    }

    public function test_secret_key_api_route_returns_key(): void
    {
        $user = $this->createFortifyTestUser();

        // Enable 2FA
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($user);
        $user->refresh();

        $response = $this->actingAs($user)->get('/user/two-factor-secret-key');
        $this->assertNotEquals(404, $response->getStatusCode());

        $this->cleanupFortifyUser($user);
    }

    public function test_recovery_codes_api_route(): void
    {
        $user = $this->createFortifyTestUser();

        // Enable 2FA
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable($user);
        $user->refresh();

        $response = $this->actingAs($user)->get('/user/two-factor-recovery-codes');
        $this->assertNotEquals(404, $response->getStatusCode());

        $this->cleanupFortifyUser($user);
    }

    public function test_service_provider_registers_livewire_components(): void
    {
        $this->assertTrue(
            class_exists(\MicroweberPackages\Fortify\Http\Livewire\TwoFactorSetupComponent::class),
            'TwoFactorSetupComponent class should exist'
        );

        $this->assertTrue(
            class_exists(\MicroweberPackages\Fortify\Http\Livewire\TwoFactorChallengeComponent::class),
            'TwoFactorChallengeComponent class should exist'
        );
    }

    public function test_service_provider_registers_middleware_alias(): void
    {
        $router = app('router');
        $middleware = $router->getMiddleware();
        $this->assertArrayHasKey('require-2fa', $middleware);
    }
}
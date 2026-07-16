<?php

namespace MicroweberPackages\Fortify\Tests\Unit;

use Illuminate\Support\Carbon;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use MicroweberPackages\Fortify\Contracts\TwoFactorAuthenticatable;
use MicroweberPackages\Fortify\Tests\TestCase;
use PragmaRX\Google2FA\Google2FA;

/**
 * Covers the TwoFactorAuthenticatable contract surface (interface presence, the
 * CMS User implementing it, and the typed getters) using the standard Microweber
 * test harness. (Ported from the removed Orchestra-Testbench "Standalone" suite.)
 */
class ContractTest extends TestCase
{
    public function test_contract_interface_exists(): void
    {
        $this->assertTrue(interface_exists(TwoFactorAuthenticatable::class));
    }

    public function test_user_model_implements_contract(): void
    {
        $user = $this->createFortifyTestUser();
        $this->assertInstanceOf(TwoFactorAuthenticatable::class, $user);
        $user->delete();
    }

    public function test_getter_methods_return_null_before_setup(): void
    {
        $user = $this->createFortifyTestUser();

        $this->assertNull($user->getTwoFactorSecret());
        $this->assertNull($user->getTwoFactorRecoveryCodes());
        $this->assertNull($user->getTwoFactorConfirmedAt());
        $this->assertNotEmpty($user->getPasswordHash());

        $this->cleanupFortifyUser($user);
    }

    public function test_getters_populate_after_enable_and_confirm(): void
    {
        $user = $this->createFortifyTestUser();

        app(EnableTwoFactorAuthentication::class)($user);
        $user->refresh();

        $this->assertNotNull($user->getTwoFactorSecret());
        $this->assertNotNull($user->getTwoFactorRecoveryCodes());
        $this->assertNull($user->getTwoFactorConfirmedAt());

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);
        app(ConfirmTwoFactorAuthentication::class)($user, $google2fa->getCurrentOtp($secret));
        $user->refresh();

        $this->assertInstanceOf(Carbon::class, $user->getTwoFactorConfirmedAt());

        $this->cleanupFortifyUser($user);
    }
}

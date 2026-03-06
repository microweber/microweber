<?php

namespace Modules\Profile\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Modules\Profile\Models\User;
use Modules\Profile\Traits\HasTwoFactorAuthentication;

class ProfileModuleTest extends TestCase
{
  /*  #[Test]
    public function it_usermodelusestwofactortrait(): void {
        $user = new User();
        $this->assertContains(
            HasTwoFactorAuthentication::class,
            class_uses_recursive($user)
        );
    }*/

    #[Test]
    public function it_usermodelhasfillablefields(): void {
        $expected = [

            'email',
            'password',
            'two_factor_secret',
            'two_factor_recovery_codes',
            'two_factor_confirmed_at'
        ];
$fillable = (new User())->getFillable();
        $this->assertNotEmpty($fillable);

        foreach ($expected as $field) {
            $this->assertContains($field, $fillable);
        }

    }

    /*#[Test]
    public function it_twofactorconfigexists(): void {
        $configPath = base_path('Modules/Profile/config/twofactor.php');
        $this->assertFileExists($configPath);
    }*/
}

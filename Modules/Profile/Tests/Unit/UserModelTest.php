<?php

namespace Modules\Profile\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Modules\Profile\Tests\TestCase;
use Modules\Profile\Models\User;

class UserModelTest extends TestCase
{
    #[Test]
    public function can_create_user()
    {
        $random = bin2hex(random_bytes(4));
$user = User::factory()->create([
            'email' => 'test_'.$random.'@example.com'
        ]);
        
        $this->assertDatabaseHas('users', [
            'email' => 'test_'.$random.'@example.com'
        ]);
    }

    #[Test]
    public function has_two_factor_authentication_trait()
    {
        $this->assertTrue(
            method_exists(User::class, 'hasTwoFactorEnabled'),
            'User model should have two factor authentication methods'
        );
    }
}
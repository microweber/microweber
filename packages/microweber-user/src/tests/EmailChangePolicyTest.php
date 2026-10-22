<?php

namespace MicroweberPackages\User\tests;

use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;

/**
 * Email-change policy on UserManager::save(): a non-admin may change their own
 * email only when the allow-user-email-change flag (env or option) is on and the
 * account is not social-login managed. Admins are never blocked by the flag.
 */
class EmailChangePolicyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Apply allowed changes immediately so the policy is tested in isolation
        // from the verify/defer flow.
        save_option(['option_key' => 'email_change_verify', 'option_group' => 'users', 'option_value' => 'n']);
    }

    private function setAllowFlag(string $value): void
    {
        save_option(['option_key' => 'allow_user_email_change', 'option_group' => 'users', 'option_value' => $value]);
    }

    private function makeUser(array $attrs = []): User
    {
        return User::create(array_merge([
            'username' => 'policy_' . uniqid(),
            'email' => 'policy_' . uniqid() . '@example.test',
            'password' => 'password',
            'first_name' => 'P',
            'last_name' => 'User',
            'is_admin' => 0,
            'is_active' => 1,
        ], $attrs));
    }

    #[Test]
    public function non_admin_cannot_change_email_by_default(): void
    {
        $this->setAllowFlag('n');
        $user = $this->makeUser();
        $this->actingAs($user);
        $old = $user->email;

        $result = save_user([
            'id' => $user->id,
            'email' => 'blocked_' . uniqid() . '@example.test',
            'current_password' => 'password',
        ]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertSame($old, User::find($user->id)->email);
    }

    #[Test]
    public function non_admin_can_change_email_when_the_flag_is_on(): void
    {
        $this->setAllowFlag('y');
        $user = $this->makeUser();
        $this->actingAs($user);
        $new = 'allowed_' . uniqid() . '@example.test';

        $result = save_user([
            'id' => $user->id,
            'email' => $new,
            'current_password' => 'password',
        ]);

        $this->assertEquals($user->id, (int) $result);
        $this->assertSame($new, User::find($user->id)->email);
    }

    #[Test]
    public function admin_can_change_another_users_email_with_the_flag_off(): void
    {
        $this->setAllowFlag('n');
        $admin = $this->makeUser(['is_admin' => 1]);
        $this->actingAs($admin);

        $target = $this->makeUser();
        $new = 'adminset_' . uniqid() . '@example.test';

        $result = save_user(['id' => $target->id, 'email' => $new]);

        $this->assertEquals($target->id, (int) $result);
        $this->assertSame($new, User::find($target->id)->email);
    }

    #[Test]
    public function admin_can_change_their_own_email_with_the_flag_off(): void
    {
        $this->setAllowFlag('n');
        $admin = $this->makeUser(['is_admin' => 1]);
        $this->actingAs($admin);
        $new = 'adminown_' . uniqid() . '@example.test';

        $result = save_user(['id' => $admin->id, 'email' => $new, 'current_password' => 'password']);

        $this->assertEquals($admin->id, (int) $result);
        $this->assertSame($new, User::find($admin->id)->email);
    }

    #[Test]
    public function a_social_login_user_cannot_change_email_even_with_the_flag_on(): void
    {
        $this->setAllowFlag('y');
        $user = $this->makeUser(['oauth_provider' => 'google']);
        $this->actingAs($user);
        $old = $user->email;

        $result = save_user([
            'id' => $user->id,
            'email' => 'social_' . uniqid() . '@example.test',
            'current_password' => 'password',
        ]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsStringIgnoringCase('social', $result['error']);
        $this->assertSame($old, User::find($user->id)->email);
    }

    #[Test]
    public function the_env_variable_enables_email_change_even_with_the_option_off(): void
    {
        $this->setAllowFlag('n'); // option off
        putenv('ALLOW_USER_EMAIL_CHANGE=true');
        $_ENV['ALLOW_USER_EMAIL_CHANGE'] = 'true';

        try {
            $user = $this->makeUser();
            $this->actingAs($user);
            $new = 'envon_' . uniqid() . '@example.test';

            $result = save_user([
                'id' => $user->id,
                'email' => $new,
                'current_password' => 'password',
            ]);

            $this->assertEquals($user->id, (int) $result);
            $this->assertSame($new, User::find($user->id)->email);
        } finally {
            putenv('ALLOW_USER_EMAIL_CHANGE');
            unset($_ENV['ALLOW_USER_EMAIL_CHANGE']);
        }
    }
}

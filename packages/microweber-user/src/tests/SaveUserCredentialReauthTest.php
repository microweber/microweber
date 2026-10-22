<?php

namespace MicroweberPackages\User\tests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;

/**
 * Sensitive-action re-authentication guard on UserManager::save() (/api/save_user):
 * a logged-in user changing their OWN email or password must confirm the current
 * password (or have confirmed it recently). Non-credential edits and force-saved
 * paths (registration / reset / OAuth) are unaffected.
 */
class SaveUserCredentialReauthTest extends TestCase
{
    private string $knownPassword = 'KnownPass123!';

    protected function setUp(): void
    {
        parent::setUp();

        // Isolate the current-password guard: disable the email-change
        // verification/defer flow so a confirmed email change applies
        // immediately here (the defer flow is covered by its own test).
        save_option([
            'option_key' => 'email_change_verify',
            'option_group' => 'users',
            'option_value' => 'n',
        ]);
    }

    private function loginFreshUser(): User
    {
        $user = User::create([
            'username' => 'reauth_' . uniqid(),
            'email' => 'reauth_' . uniqid() . '@example.test',
            'password' => $this->knownPassword, // hashed by setPasswordAttribute
            'is_admin' => 0,
            'is_active' => 1,
        ]);

        Auth::login($user);
        $this->actingAs($user);

        // Start from a clean "not recently confirmed" state.
        Session::forget('auth.password_confirmed_at');

        return $user;
    }

    #[Test]
    public function email_change_without_current_password_is_rejected(): void
    {
        $user = $this->loginFreshUser();
        $newEmail = 'changed_' . uniqid() . '@example.test';

        $result = save_user(['id' => $user->id, 'email' => $newEmail]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertSame('current_password', $result['form_data_required'] ?? null);

        $this->assertSame($user->email, User::find($user->id)->email, 'Email must be unchanged');
    }

    #[Test]
    public function email_change_with_valid_current_password_succeeds(): void
    {
        $user = $this->loginFreshUser();
        $newEmail = 'changed_' . uniqid() . '@example.test';

        $result = save_user([
            'id' => $user->id,
            'email' => $newEmail,
            'current_password' => $this->knownPassword,
        ]);

        $this->assertEquals($user->id, (int) $result);
        $this->assertSame($newEmail, User::find($user->id)->email);
    }

    #[Test]
    public function password_change_without_current_password_is_rejected(): void
    {
        $user = $this->loginFreshUser();
        $oldHash = User::find($user->id)->password;

        $result = save_user(['id' => $user->id, 'password' => 'NewPass999!', 'password2' => 'NewPass999!']);

        $this->assertIsArray($result);
        $this->assertSame('current_password', $result['form_data_required'] ?? null);
        $this->assertSame($oldHash, User::find($user->id)->password, 'Password must be unchanged');
    }

    #[Test]
    public function wrong_current_password_is_rejected(): void
    {
        $user = $this->loginFreshUser();

        $result = save_user([
            'id' => $user->id,
            'email' => 'nope_' . uniqid() . '@example.test',
            'current_password' => 'WrongPassword!',
        ]);

        $this->assertIsArray($result);
        $this->assertSame('current_password', $result['form_data_required'] ?? null);
    }

    #[Test]
    public function non_credential_change_does_not_require_current_password(): void
    {
        $user = $this->loginFreshUser();

        $result = save_user(['id' => $user->id, 'first_name' => 'Reauth', 'last_name' => 'Tester']);

        $this->assertEquals($user->id, (int) $result);
        $this->assertSame('Reauth', User::find($user->id)->first_name);
    }

    #[Test]
    public function resending_the_same_email_is_not_treated_as_a_change(): void
    {
        $user = $this->loginFreshUser();

        // A normal profile save re-posts the unchanged email alongside other fields.
        $result = save_user(['id' => $user->id, 'email' => $user->email, 'phone' => '555000']);

        $this->assertEquals($user->id, (int) $result);
        $this->assertSame('555000', User::find($user->id)->phone);
    }

    #[Test]
    public function a_recent_password_confirmation_bypasses_the_requirement(): void
    {
        $user = $this->loginFreshUser();
        Session::put('auth.password_confirmed_at', time()); // confirmed just now

        $newEmail = 'recent_' . uniqid() . '@example.test';
        $result = save_user(['id' => $user->id, 'email' => $newEmail]);

        $this->assertEquals($user->id, (int) $result);
        $this->assertSame($newEmail, User::find($user->id)->email);
    }

    #[Test]
    public function admin_editing_another_users_email_is_exempt(): void
    {
        $admin = User::create([
            'username' => 'reauth_admin_' . uniqid(),
            'email' => 'reauth_admin_' . uniqid() . '@example.test',
            'password' => 'AdminPass123!',
            'is_admin' => 1,
            'is_active' => 1,
        ]);
        Auth::login($admin);
        $this->actingAs($admin);
        Session::forget('auth.password_confirmed_at');

        $target = User::create([
            'username' => 'reauth_target_' . uniqid(),
            'email' => 'reauth_target_' . uniqid() . '@example.test',
            'password' => 'TargetPass123!',
            'is_admin' => 0,
            'is_active' => 1,
        ]);

        $newEmail = 'managed_' . uniqid() . '@example.test';
        // Admin user management: no current_password of the target is required.
        $result = save_user(['id' => $target->id, 'email' => $newEmail]);

        $this->assertEquals($target->id, (int) $result);
        $this->assertSame($newEmail, User::find($target->id)->email);
    }
}

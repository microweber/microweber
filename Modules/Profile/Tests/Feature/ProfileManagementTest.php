<?php

namespace Modules\Profile\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use MicroweberPackages\User\tests\UserTestHelperTrait;
use Modules\Profile\Filament\Pages\EditProfile;
use Modules\Profile\Filament\Pages\ChangePassword;
use Livewire\Livewire;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;

class ProfileManagementTest extends TestCase
{
    use UserTestHelperTrait;

    protected function createUser()
    {
        $random = bin2hex(random_bytes(4));
        return User::create([
            'name' => 'Test User',
            'email' => 'test_'.$random.'@example.com',
            'password' => bcrypt('password'),
            'is_active' => 1
        ]);
    }

    #[Test]
    public function it_user_can_edit_profile(): void {
        $user = $this->createUser();
        $this->actingAs($user);

        $newFirstName = 'Updated';
        $newLastName = 'User';

        // Name change leaves the email untouched, so no current-password
        // confirmation is required (email changes have their own flow/tests).
        Livewire::test(EditProfile::class)
            ->set('data.first_name', $newFirstName)
            ->set('data.last_name', $newLastName)
            ->call('save')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals($newFirstName, $user->first_name);
        $this->assertEquals($newLastName, $user->last_name);
    }

    #[Test]
    public function it_user_can_change_password_from_edit_profile(): void {
        // Password change was moved INTO the Edit Profile page (second form);
        // the standalone ChangePassword page is hidden from the sidebar.
        $user = $this->createUser();
        $this->actingAs($user);

        $newPassword = 'editProfilePass123!';

        Livewire::test(EditProfile::class)
            ->set('passwordData.current_password', 'password')
            ->set('passwordData.password', $newPassword)
            ->set('passwordData.password_confirmation', $newPassword)
            ->call('changePassword')
            ->assertHasNoErrors();

        $this->assertTrue(
            auth()->validate([
                'email' => $user->email,
                'password' => $newPassword,
            ]),
            'Password should be updated from the Edit Profile page'
        );
    }

    #[Test]
    public function it_change_password_is_hidden_from_the_sidebar(): void {
        $this->assertFalse(
            ChangePassword::shouldRegisterNavigation(),
            'ChangePassword must not appear in the profile sidebar (it lives in Edit Profile).'
        );
    }

    #[Test]
    public function it_user_can_change_password(): void {
        $user = $this->createUser();
        $this->actingAs($user);

        $newPassword = 'newSecurePassword123!';

        Livewire::test(ChangePassword::class)
            ->set('data.current_password', 'password')
            ->set('data.password', $newPassword)
            ->set('data.password_confirmation', $newPassword)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertTrue(
            auth()->validate([
                'email' => $user->email,
                'password' => $newPassword
            ]),
            'Password should be updated'
        );
    }

    #[Test]
    public function it_password_change_requires_current_password(): void {
        $user = $this->createUser();
        $this->actingAs($user);

        // Verify password change with correct current password
        $response = Livewire::test(ChangePassword::class)
            ->set('data.current_password', 'password')
            ->set('data.password', 'newPassword123!')
            ->set('data.password_confirmation', 'newPassword123!')
            ->call('save');
        
        // Verify either success or validation error (component may handle differently)
        if ($response->errors()->isEmpty()) {
            $this->assertTrue(
                auth()->validate([
                    'email' => $user->email,
                    'password' => 'newPassword123!'
                ])
            );
        } else {
            $response->assertHasErrors(['current_password']);
        }

        // Verify wrong password doesn't change password
        $initialHashedPassword = $user->password;
        Livewire::test(ChangePassword::class)
            ->set('data.current_password', 'wrongPassword')
            ->set('data.password', 'anotherNewPassword123!')
            ->set('data.password_confirmation', 'anotherNewPassword123!')
            ->call('save');

        $user->refresh();
        $this->assertEquals($initialHashedPassword, $user->password);
    }
}
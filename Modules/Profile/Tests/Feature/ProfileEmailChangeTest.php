<?php

namespace Modules\Profile\Tests\Feature;

use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Modules\Profile\Filament\Pages\EditProfile;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\User\Notifications\EmailChangeRequestedNotification;
use MicroweberPackages\User\Notifications\VerifyEmailChangeNotification;

/**
 * The verified email-change flow must also apply when a user edits their email
 * from the Filament profile page (EditProfile), not just the legacy API: the
 * current password is required, and the change is parked + verified.
 */
class ProfileEmailChangeTest extends TestCase
{
    private function makeUser(): User
    {
        return User::create([
            'username' => 'profemail_' . uniqid(),
            'email' => 'profemail_' . uniqid() . '@example.test',
            'password' => 'password', // hashed by setPasswordAttribute
            'first_name' => 'Test',
            'last_name' => 'User',
            'is_admin' => 0,
            'is_active' => 1,
        ]);
    }

    private function enableVerification(string $value = 'y'): void
    {
        save_option(['option_key' => 'email_change_verify', 'option_group' => 'users', 'option_value' => $value]);
    }

    #[Test]
    public function changing_email_without_current_password_is_rejected(): void
    {
        $this->enableVerification();
        $user = $this->makeUser();
        $this->actingAs($user);

        Livewire::test(EditProfile::class)
            ->set('data.email', 'new_' . uniqid() . '@example.test')
            ->call('save')
            ->assertHasErrors(['data.current_password_for_email']);

        $this->assertNull(User::find($user->id)->pending_email);
    }

    #[Test]
    public function changing_email_with_wrong_current_password_is_rejected(): void
    {
        $this->enableVerification();
        $user = $this->makeUser();
        $this->actingAs($user);

        Livewire::test(EditProfile::class)
            ->set('data.email', 'new_' . uniqid() . '@example.test')
            ->set('data.current_password_for_email', 'WrongPassword!')
            ->call('save')
            ->assertHasErrors(['data.current_password_for_email']);

        $this->assertNull(User::find($user->id)->pending_email);
    }

    #[Test]
    public function changing_email_with_correct_password_parks_it_and_sends_messages(): void
    {
        Notification::fake();
        $this->enableVerification();
        $user = $this->makeUser();
        $oldEmail = $user->email;
        $this->actingAs($user);

        $newEmail = 'confirmme_' . uniqid() . '@example.test';

        Livewire::test(EditProfile::class)
            ->set('data.email', $newEmail)
            ->set('data.current_password_for_email', 'password')
            ->call('save')
            ->assertHasNoErrors();

        $fresh = User::find($user->id);
        $this->assertSame($oldEmail, $fresh->email, 'Active email must not change until confirmed');
        $this->assertSame($newEmail, $fresh->pending_email, 'New email must be parked');

        Notification::assertSentOnDemand(VerifyEmailChangeNotification::class);
        Notification::assertSentTo($user, EmailChangeRequestedNotification::class);
    }

    #[Test]
    public function changing_only_the_name_needs_no_current_password(): void
    {
        $this->enableVerification();
        $user = $this->makeUser();
        $this->actingAs($user);

        Livewire::test(EditProfile::class)
            ->set('data.first_name', 'NoPasswordNeeded')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('NoPasswordNeeded', User::find($user->id)->first_name);
    }

    #[Test]
    public function with_verification_disabled_the_email_changes_immediately(): void
    {
        $this->enableVerification('n');
        $user = $this->makeUser();
        $this->actingAs($user);

        $newEmail = 'immediate_' . uniqid() . '@example.test';

        Livewire::test(EditProfile::class)
            ->set('data.email', $newEmail)
            ->set('data.current_password_for_email', 'password')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame($newEmail, User::find($user->id)->email);
    }
}

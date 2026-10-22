<?php

namespace MicroweberPackages\User\tests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\User\Notifications\EmailChangeRequestedNotification;
use MicroweberPackages\User\Notifications\VerifyEmailChangeNotification;

/**
 * Verified email-change flow: a self-service email change is parked (pending)
 * and only applied once the NEW address confirms via the emailed link; the old
 * address is notified and stays active until then.
 */
class EmailChangeVerificationTest extends TestCase
{
    private string $knownPassword = 'KnownPass123!';

    protected function setUp(): void
    {
        parent::setUp();

        save_option([
            'option_key' => 'email_change_verify',
            'option_group' => 'users',
            'option_value' => 'y',
        ]);
    }

    private function loginFreshUser(): User
    {
        $user = User::create([
            'username' => 'verify_' . uniqid(),
            'email' => 'verify_' . uniqid() . '@example.test',
            'password' => $this->knownPassword,
            'is_admin' => 0,
            'is_active' => 1,
        ]);
        Auth::login($user);
        $this->actingAs($user);

        return $user;
    }

    #[Test]
    public function email_change_is_parked_and_both_messages_are_sent(): void
    {
        Notification::fake();

        $user = $this->loginFreshUser();
        $oldEmail = $user->email;
        $newEmail = 'newaddr_' . uniqid() . '@example.test';

        $result = save_user([
            'id' => $user->id,
            'email' => $newEmail,
            'current_password' => $this->knownPassword,
        ]);

        // Save "succeeds" (returns id) but the active email is unchanged.
        $this->assertEquals($user->id, (int) $result);

        $fresh = User::find($user->id);
        $this->assertSame($oldEmail, $fresh->email, 'Active email must not change until confirmed');
        $this->assertSame($newEmail, $fresh->pending_email, 'New email must be parked as pending');
        $this->assertNotEmpty($fresh->pending_email_hash);

        // Confirmation link went to the NEW address...
        Notification::assertSentOnDemand(
            VerifyEmailChangeNotification::class,
            function ($notification, $channels, $notifiable) use ($newEmail) {
                return in_array($newEmail, (array) ($notifiable->routes['mail'] ?? []), true)
                    || ($notification->newEmail === $newEmail);
            }
        );

        // ...and the security notice to the OLD address (the user).
        Notification::assertSentTo($user, EmailChangeRequestedNotification::class);
    }

    #[Test]
    public function confirming_with_a_valid_token_applies_the_new_email(): void
    {
        Notification::fake();

        $user = $this->loginFreshUser();
        $newEmail = 'confirmed_' . uniqid() . '@example.test';

        save_user([
            'id' => $user->id,
            'email' => $newEmail,
            'current_password' => $this->knownPassword,
        ]);

        // Recover the token from the captured verification notification.
        $token = null;
        Notification::assertSentOnDemand(
            VerifyEmailChangeNotification::class,
            function ($notification) use (&$token) {
                $token = $notification->token;
                return true;
            }
        );
        $this->assertNotNull($token);

        $outcome = app()->user_manager->confirmEmailChange($user->id, $token);

        $this->assertArrayHasKey('success', $outcome);

        $fresh = User::find($user->id);
        $this->assertSame($newEmail, $fresh->email, 'Email must be applied after confirmation');
        $this->assertNull($fresh->pending_email, 'Pending email must be cleared');
    }

    #[Test]
    public function an_invalid_token_is_rejected_and_leaves_the_email_unchanged(): void
    {
        Notification::fake();

        $user = $this->loginFreshUser();
        $oldEmail = $user->email;

        save_user([
            'id' => $user->id,
            'email' => 'rejected_' . uniqid() . '@example.test',
            'current_password' => $this->knownPassword,
        ]);

        $outcome = app()->user_manager->confirmEmailChange($user->id, 'totally-wrong-token');

        $this->assertArrayHasKey('error', $outcome);
        $this->assertSame($oldEmail, User::find($user->id)->email);
    }

    #[Test]
    public function an_expired_token_is_rejected(): void
    {
        Notification::fake();

        $user = $this->loginFreshUser();
        $newEmail = 'expired_' . uniqid() . '@example.test';

        app()->user_manager->requestEmailChange($user, $newEmail);

        $token = null;
        Notification::assertSentOnDemand(VerifyEmailChangeNotification::class, function ($n) use (&$token) {
            $token = $n->token;
            return true;
        });

        // Backdate the request beyond the 60-minute window.
        User::where('id', $user->id)->update([
            'pending_email_created_at' => date('Y-m-d H:i:s', time() - 7200),
        ]);

        $outcome = app()->user_manager->confirmEmailChange($user->id, $token);

        $this->assertArrayHasKey('error', $outcome);
        $this->assertSame($user->email, User::find($user->id)->email);
    }
}

<?php

namespace MicroweberPackages\User\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use MicroweberPackages\Notification\Channels\AppMailChannel;

/**
 * Sent to the NEW address when a user requests an email change. Clicking the link
 * proves the owner controls the new mailbox; only then is the account's email
 * switched. Delivered mail-only (on-demand to an address with no User row yet).
 */
class VerifyEmailChangeNotification extends Notification
{
    public function __construct(
        public readonly int $userId,
        public readonly string $token,
        public readonly string $newEmail
    ) {
    }

    public function via($notifiable): array
    {
        return [AppMailChannel::class];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = url(route('api.user.confirm_email_change', [
            'id' => $this->userId,
            'token' => $this->token,
        ], false));

        return (new MailMessage())
            ->subject(Lang::get('Confirm your new email address'))
            ->line(Lang::get('You requested to change the email address on your account to this one.'))
            ->action(Lang::get('Confirm email change'), $url)
            ->line(Lang::get('If you did not request this, you can ignore this email — your account email will not change.'));
    }
}

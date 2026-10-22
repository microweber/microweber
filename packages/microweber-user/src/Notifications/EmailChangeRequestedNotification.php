<?php

namespace MicroweberPackages\User\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use MicroweberPackages\Notification\Channels\AppMailChannel;

/**
 * Non-blocking security notice sent to the user's CURRENT (old) address the
 * moment an email change is requested, so a legitimate owner is alerted even if
 * the request came from a hijacked session. The change itself is not applied
 * until the new address is confirmed.
 */
class EmailChangeRequestedNotification extends Notification
{
    public function __construct(private string $newEmail)
    {
    }

    public function via($notifiable): array
    {
        return ['database', AppMailChannel::class];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(Lang::get('Security alert: email change requested'))
            ->line(Lang::get('Someone requested to change the email address on your account to:'))
            ->line($this->newEmail)
            ->line(Lang::get('The change will only take effect after the new address is confirmed. Your current email stays active until then.'))
            ->line(Lang::get('If this was not you, change your password immediately — your session may be compromised.'));
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'email_change_requested',
            'new_email' => $this->newEmail,
        ];
    }
}

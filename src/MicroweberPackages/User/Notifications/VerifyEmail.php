<?php

namespace MicroweberPackages\User\Notifications;

use \Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailLaravel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class VerifyEmail extends VerifyEmailLaravel
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        var_dump($verificationUrl);

        try {
            return (new MailMessage())
                ->subject(Lang::get('Verify Email Address'))
                ->line(Lang::get('Please click the button below to verify your email address.'))
                ->action(Lang::get('Verify Email Address'), $verificationUrl)
                ->line(Lang::get('If you did not create an account, no further action is required.'));
        } catch (\Exception $e) {
            //
        }
    }
}
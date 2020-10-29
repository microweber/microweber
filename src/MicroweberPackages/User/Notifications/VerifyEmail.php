<?php

namespace MicroweberPackages\User\Notifications;

use \Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailLaravel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use MicroweberPackages\Notification\Channels\AppMailChannel;

class VerifyEmail extends VerifyEmailLaravel
{


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ AppMailChannel::class]; //return [AppMailChannel::class];
    }

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

        return (new MailMessage())
            ->subject(Lang::get('Verify Email Address'))
            ->line(Lang::get('Please click the button below to verify your email address.'))
            ->action(Lang::get('Verify Email Address'), $verificationUrl)
            ->line(Lang::get('If you did not create an account, no further action is required.'));
    }
}
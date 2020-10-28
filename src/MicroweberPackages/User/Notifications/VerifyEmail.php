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

        var_dump($notifiable->getModel());
        var_dump($verificationUrl);
        die();
    }
}
<?php

namespace MicroweberPackages\User\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use MicroweberPackages\Notification\Channels\AppMailChannel;

class MailResetPasswordNotification extends ResetPassword {

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', AppMailChannel::class];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $notifiable->toArray();
    }
}
<?php


namespace MicroweberPackages\User\Notifications;

use Illuminate\Auth\Events\Registered;

use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use MicroweberPackages\Option\Facades\Option;

class SendEmailVerificationNotificationOnRegister extends SendEmailVerificationNotification
{
    public function handle(Registered $event)
    {
        $isVerfiedEmailRequired = get_option('register_email_verify', 'users');

        if ($isVerfiedEmailRequired) {
            parent::handle($event);
        }
    }
}

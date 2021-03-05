<?php

namespace MicroweberPackages\User\Listeners;


use Illuminate\Support\Facades\Notification;

use MicroweberPackages\Option\Facades\Option;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\User\Notifications\NewRegistration;

class UserRegisteredListener
{
    public function handle($event)
    {
        try {
            $newRegEvent = new NewRegistration($event->user);
            $is_register_email_enabled = Option::getValue('register_email_enabled', 'users');
            $register_email_to_admins_enabled = Option::getValue('register_email_to_admins_enabled', 'users');

            if ($is_register_email_enabled) {
                $event->user->notifyNow($newRegEvent);
            }
            if ($register_email_to_admins_enabled) {
                Notification::send(User::whereIsAdmin(1)->get(), $newRegEvent);
            }


        } catch (\Exception $e) {

        }
    }
}

<?php

namespace MicroweberPackages\User\Listeners;


use Illuminate\Support\Facades\Notification;

use MicroweberPackages\User\Models\User;
use MicroweberPackages\User\Notifications\NewRegistration;

class UserRegisteredListener
{
    public function handle($event)
    {
        try {
            $newRegEvent = new NewRegistration($event->user);

            $event->user->notifyNow($newRegEvent);

            Notification::send(User::whereIsAdmin(1)->get(), $newRegEvent);

        } catch (\Exception $e) {

        }
    }
}

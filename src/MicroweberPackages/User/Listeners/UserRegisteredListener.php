<?php

namespace MicroweberPackages\User\Listeners;


use Illuminate\Support\Facades\Notification;
use MicroweberPackages\Admin\Models\AdminUser;
use MicroweberPackages\User\Notifications\NewRegistration;

class UserRegisteredListener
{
    public function handle($event)
    {
        try {
            $newRegEvent = new NewRegistration($event->user);

            $event->user->notifyNow($newRegEvent);
            Notification::send(AdminUser::all(), $newRegEvent);

        } catch (\Exception $e) {

        }
    }
}

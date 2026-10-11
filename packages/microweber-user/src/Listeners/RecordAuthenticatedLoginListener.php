<?php

namespace MicroweberPackages\User\Listeners;

use MicroweberPackages\App\LoginAttempt;

class RecordAuthenticatedLoginListener
{
    public function handle($event)
    {
        if (!isset($event->user)) {
            return;
        }

        if ($event and isset($event->user) and isset($event->user->id)) {
            return;
        }

        $loginAttempt = new LoginAttempt();
        $loginAttempt->email = $event->user->email;
        $loginAttempt->username = $event->user->username;
        $loginAttempt->success = 1;
        $loginAttempt->user_id = $event->user->id;
        $loginAttempt->time = time();
        $loginAttempt->ip = user_ip();
        $loginAttempt->save();
    }
}

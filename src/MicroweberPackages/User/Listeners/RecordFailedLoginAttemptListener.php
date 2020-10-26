<?php

namespace MicroweberPackages\User\Listeners;

use MicroweberPackages\App\LoginAttempt;

class RecordFailedLoginAttemptListener
{
    public function handle($event)
    {
        if (!isset($event->credentials)) {
            return;
        }

        $findUserId = detect_user_id_from_params($event->credentials);
        if (!$findUserId) {
            return;
        }

        $loginAttempt = new LoginAttempt();

        if (isset($event->credentials['username'])) {
            $loginAttempt->username = $event->credentials['username'];
        }

        if (isset($event->credentials['email'])) {
            $loginAttempt->email = $event->credentials['email'];
        }

        $loginAttempt->success = 0;
        $loginAttempt->user_id = $findUserId;
        $loginAttempt->time = time();
        $loginAttempt->ip = user_ip();
        $loginAttempt->save();

    }

}

<?php

namespace MicroweberPackages\User\Listeners;


class RecordFailedLoginAttemptListener
{
    public function handle($event)
    {
        $credentials = $event->credentials;

        var_dump($credentials);

    }
}

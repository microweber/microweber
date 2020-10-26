<?php

namespace MicroweberPackages\User\Listeners;

class RecordAuthenticatedLoginListener
{
    protected $success = 1;

    use LoginListenerTrait;
}

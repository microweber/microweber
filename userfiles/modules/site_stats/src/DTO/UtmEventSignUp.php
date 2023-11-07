<?php

namespace MicroweberPackages\Modules\SiteStats\DTO;

class UtmEventSignUp extends UtmEvent
{
    public $eventCategory = 'user';
    public $eventAction = 'SIGN_UP';
    public $eventLabel = 'User Sign Up';

}

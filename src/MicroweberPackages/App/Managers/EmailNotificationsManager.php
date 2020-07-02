<?php

namespace MicroweberPackages\App\Managers;

class EmailNotificationsManager
{
    /** @var \MicroweberPackages\Application */
    public $app;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }
}

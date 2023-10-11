<?php

namespace MicroweberPackages\Dusk;

use Laravel\Dusk\Browser;

class DuskServiceProvider extends \Laravel\Dusk\DuskServiceProvider
{
    public function register()
    {
        parent::register();

        Browser::macro('switchFrame', function($frame) {
            $this->driver->switchTo()->defaultContent()->switchTo()->frame($frame);
            return $this;
        });

        Browser::macro('switchFrameDefault', function () {
            $this->driver->switchTo()->defaultContent()->switchTo()->defaultContent();
            return $this;
        });
    }
}

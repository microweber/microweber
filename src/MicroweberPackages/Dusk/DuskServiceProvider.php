<?php

namespace MicroweberPackages\Dusk;

use Laravel\Dusk\Browser;
use Tests\Browser\Components\BaseComponent;

class DuskServiceProvider extends \Laravel\Dusk\DuskServiceProvider
{
    public function register()
    {
        parent::register();

        Browser::macro('switchFrame', function($frame) {
            $coverage = $this->driver->executeScript('return window.__coverage__');
            if ($coverage) {
                BaseComponent::saveCoverage($coverage);
            }
            $this->driver->switchTo()->defaultContent()->switchTo()->frame($frame);
            return $this;
        });

        Browser::macro('switchFrameDefault', function () {
            $coverage = $this->driver->executeScript('return window.__coverage__');
            if ($coverage) {
                BaseComponent::saveCoverage($coverage);
            }
            $this->driver->switchTo()->defaultContent()->switchTo()->defaultContent();
            return $this;
        });
    }
}

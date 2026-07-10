<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component ;
use PHPUnit\Framework\Assert as PHPUnit;

abstract class BaseComponent extends Component
{
    public static function saveCoverage($coverage)
    {
        // Delegates to the single, autoloadable coverage sink in the microweber-dusk
        // package. (This class's own namespace is PSR-4-mismatched with its path, so
        // it isn't reliably autoloadable — the real logic lives in the package.)
        \MicroweberPackages\Dusk\DuskCoverage::save($coverage);
    }
}

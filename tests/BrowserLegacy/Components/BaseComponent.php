<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component ;
use PHPUnit\Framework\Assert as PHPUnit;

abstract class BaseComponent extends Component
{
    public static function saveCoverage($coverage)
    {
        if(empty($coverage)){
            return;
        }

        $coverageFilePath = base_path('tests/coverages/js/js-coverage-' . time() . '_' . uniqid() . '.json');
        if (!is_dir(dirname($coverageFilePath))) {
            mkdir(dirname($coverageFilePath), 0777, true);
        }
        file_put_contents($coverageFilePath, json_encode($coverage));
    }
}

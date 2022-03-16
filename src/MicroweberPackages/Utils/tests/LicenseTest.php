<?php

namespace MicroweberPackages\Utils\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Utils\Misc\License;

class LicenseTest extends TestCase
{

    public function testLicenseClass()
    {
        $license = new License();
        $license->saveLicense('modules/white_label::837878866c');

        $getLicenses = $license->getLicense();

        var_dump($getLicenses);

    }

}

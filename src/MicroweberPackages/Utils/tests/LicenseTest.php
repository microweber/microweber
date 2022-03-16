<?php

namespace MicroweberPackages\Utils\tests;

use MicroweberPackages\Utils\tests\mockery\UpdateManagerMockery;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Utils\Misc\License;

class LicenseTest extends TestCase
{

    public function testLicenseClass()
    {
        app()->singleton('update', function () {
            return new UpdateManagerMockery();
        });

        $randomLicenseUniqueId = uniqid();
        app()->update->setActiveLicenses([$randomLicenseUniqueId]);

        $license = new License();

        // Delete old licenses
        $license->truncate();

        // Validate right license
        $validateLicense = $license->validateLicense($randomLicenseUniqueId, 'new-world');
        $this->assertTrue($validateLicense);

        // Validate fake license
        $validateLicense = $license->validateLicense(uniqid(), 'new-world');
        $this->assertFalse($validateLicense);


        // Save invalid license
        $license->saveLicense('example-generated-license');
        $getLicenses = $license->getLicense();
        $this->assertEmpty($getLicenses);


        // Save valid license
        $license->saveLicense($randomLicenseUniqueId);
        $getLicenses = $license->getLicense();
        $this->assertNotEmpty($getLicenses);
        $this->assertEquals($getLicenses['modules/white_label'], $randomLicenseUniqueId);

    }

}

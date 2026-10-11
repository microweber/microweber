<?php

namespace MicroweberPackages\Utils\tests;

use PHPUnit\Framework\Attributes\Test;

use MicroweberPackages\Utils\tests\mockery\UpdateManagerMockery;
use Tests\TestCase;
use MicroweberPackages\SystemLicenses\Facades\SystemLicenses;

/**
 * File-license (storage/licenses.json) round-trip. The former Utils\Misc\License
 * shim was removed — this now drives the system_licenses_manager package directly.
 * Remote validation still routes through app()->update->call('validate_licenses'),
 * which UpdateManagerMockery stubs.
 */
class LicenseTest extends TestCase
{
    #[Test]
    public function it_license_file_manager(): void
    {
        app()->singleton('update', function () {
            return new UpdateManagerMockery();
        });

        $randomLicenseUniqueId = uniqid();
        app()->update->setActiveLicenses([$randomLicenseUniqueId]);

        $manager = SystemLicenses::getFacadeRoot();

        // Delete old licenses
        $manager->truncateFileLicenses();

        // Validate right license
        $validateLicense = $manager->validateFileLicense($randomLicenseUniqueId, 'new-world');
        $this->assertTrue($validateLicense);

        // Validate fake license
        $validateLicense = $manager->validateFileLicense(uniqid(), 'new-world');
        $this->assertFalse($validateLicense);

        // Save invalid license
        $manager->saveFileLicense('example-generated-license');
        $getLicenses = $manager->getFileLicenses();
        $this->assertEmpty($getLicenses);

        // Save valid license
        $manager->saveFileLicense($randomLicenseUniqueId);
        $getLicenses = $manager->getFileLicenses();
        $this->assertNotEmpty($getLicenses);

        $this->assertEquals($getLicenses['modules/white_label']['rel_type'], 'modules/white_label');
        $this->assertEquals($getLicenses['modules/white_label']['local_key'], $randomLicenseUniqueId);
    }
}

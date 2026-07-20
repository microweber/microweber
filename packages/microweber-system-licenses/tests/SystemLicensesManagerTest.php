<?php

namespace MicroweberPackages\SystemLicenses\Tests;

use MicroweberPackages\SystemLicenses\Contracts\LicenseValidatorInterface;
use MicroweberPackages\SystemLicenses\Models\SystemLicense;
use MicroweberPackages\SystemLicenses\SystemLicensesManager;
use MicroweberPackages\SystemLicenses\Tests\Fixtures\FakeLicenseValidator;

class SystemLicensesManagerTest extends TestCase
{
    protected FakeLicenseValidator $fakeValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeValidator = new FakeLicenseValidator();
        $this->app->instance(LicenseValidatorInterface::class, $this->fakeValidator);
        // Re-bind the manager so it picks up the fake validator.
        $this->app->forgetInstance('system_licenses_manager');
        $this->app->singleton('system_licenses_manager', function ($app) {
            return new SystemLicensesManager($app->make(LicenseValidatorInterface::class));
        });

        // Clean the table for each test
        if (\Illuminate\Support\Facades\Schema::hasTable('system_licenses')) {
            SystemLicense::query()->delete();
        }
    }

    /** @test */
    public function it_is_bound_in_the_container(): void
    {
        $this->assertTrue($this->app->bound('system_licenses_manager'));
        $this->assertInstanceOf(SystemLicensesManager::class, $this->app->make('system_licenses_manager'));
    }

    /** @test */
    public function it_returns_empty_licenses_when_table_is_empty(): void
    {
        $manager = app('system_licenses_manager');

        $this->assertEmpty($manager->getAllLicenses());
        $this->assertFalse($manager->hasLicense());
    }

    /** @test */
    public function it_saves_a_valid_license(): void
    {
        $this->fakeValidator->setValidKeys(['VALID-KEY-123']);
        $manager = app('system_licenses_manager');

        $result = $manager->saveLicense(['local_key' => 'VALID-KEY-123']);

        $this->assertTrue($result['is_active']);
        $this->assertArrayHasKey('id', $result);
        $this->assertDatabaseHas('system_licenses', ['local_key' => 'VALID-KEY-123']);
    }

    /** @test */
    public function it_rejects_an_invalid_license(): void
    {
        $this->fakeValidator->setValidKeys(['VALID-KEY-123']);
        $manager = app('system_licenses_manager');

        $result = $manager->saveLicense(['local_key' => 'INVALID-KEY']);

        $this->assertTrue($result['is_invalid']);
        $this->assertDatabaseMissing('system_licenses', ['local_key' => 'INVALID-KEY']);
    }

    /** @test */
    public function it_prevents_duplicate_license_keys(): void
    {
        $this->fakeValidator->setValidKeys(['VALID-KEY-123']);
        $manager = app('system_licenses_manager');

        $first = $manager->saveLicense(['local_key' => 'VALID-KEY-123']);
        $second = $manager->saveLicense(['local_key' => 'VALID-KEY-123']);

        $this->assertEquals($first['id'], $second['id']);
        $this->assertDatabaseCount('system_licenses', 1);
    }

    /** @test */
    public function it_deletes_a_license(): void
    {
        $this->fakeValidator->setValidKeys(['KEY-TO-DELETE']);
        $manager = app('system_licenses_manager');

        $saved = $manager->saveLicense(['local_key' => 'KEY-TO-DELETE']);
        $result = $manager->deleteLicense($saved['id']);

        $this->assertArrayHasKey('success', $result);
        $this->assertDatabaseMissing('system_licenses', ['local_key' => 'KEY-TO-DELETE']);
    }

    /** @test */
    public function it_validates_licenses_against_remote(): void
    {
        $this->fakeValidator->setValidKeys(['REMOTE-KEY']);
        $manager = app('system_licenses_manager');

        // First save a license
        $manager->saveLicense(['local_key' => 'REMOTE-KEY']);

        // Now validate
        $result = $manager->validateLicenses();

        $this->assertNotNull($result);
        $this->assertArrayHasKey('updates', $result);
    }

    /** @test */
    public function it_consumes_a_license_by_id(): void
    {
        $this->fakeValidator->setValidKeys(['CONSUME-KEY']);
        $manager = app('system_licenses_manager');

        $saved = $manager->saveLicense(['local_key' => 'CONSUME-KEY']);
        $result = $manager->consumeLicense($saved['id']);

        $this->assertTrue($result['valid']);
    }

    /** @test */
    public function it_returns_error_for_nonexistent_license_consume(): void
    {
        $manager = app('system_licenses_manager');

        $result = $manager->consumeLicense(999);

        $this->assertFalse($result['active']);
    }

    /** @test */
    public function have_license_helper_works(): void
    {
        $this->fakeValidator->setValidKeys(['HELPER-KEY']);
        $manager = app('system_licenses_manager');

        $this->assertFalse(have_license('modules/white_label'));

        $manager->saveLicense(['local_key' => 'HELPER-KEY']);
        $manager->refreshActiveLicenses();

        $this->assertTrue(have_license('modules/white_label'));
    }

    /** @test */
    public function it_requires_local_key_param(): void
    {
        $manager = app('system_licenses_manager');

        $result = $manager->saveLicense([]);

        $this->assertTrue($result['is_invalid']);
    }

    /** @test */
    public function it_saves_license_details_from_consume_response(): void
    {
        $this->fakeValidator->setValidKeys(['DETAILS-KEY']);
        $manager = app('system_licenses_manager');

        $manager->saveLicense(['local_key' => 'DETAILS-KEY']);

        $license = SystemLicense::where('local_key', 'DETAILS-KEY')->first();

        $this->assertNotNull($license);
        $this->assertEquals('Test User', $license->registered_name);
        $this->assertEquals('active', $license->status);
        $this->assertEquals('localhost', $license->domains);
        $this->assertEquals(1, $license->product_id);
        $this->assertEquals(100, $license->service_id);
        $this->assertEquals('monthly', $license->billing_cycle);
    }

    /** @test */
    public function file_license_operations_work(): void
    {
        $this->fakeValidator->setValidKeys(['FILE-KEY']);
        $manager = app('system_licenses_manager');

        // Clean up
        $manager->truncateFileLicenses();

        // Validate
        $this->assertTrue($manager->validateFileLicense('FILE-KEY', 'modules/test'));
        $this->assertFalse($manager->validateFileLicense('INVALID', 'modules/test'));

        // Save
        $this->assertTrue($manager->saveFileLicense('FILE-KEY', 'modules/test'));

        // Read
        $licenses = $manager->getFileLicenses();
        $this->assertNotEmpty($licenses);
        $this->assertEquals('FILE-KEY', $licenses['modules/test']['local_key']);

        // Truncate
        $this->assertTrue($manager->truncateFileLicenses());
        $this->assertEmpty($manager->getFileLicenses());
    }
}
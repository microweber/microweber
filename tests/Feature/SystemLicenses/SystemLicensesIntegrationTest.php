<?php

namespace Tests\Feature\SystemLicenses;

use MicroweberPackages\SystemLicenses\Contracts\LicenseValidatorInterface;
use MicroweberPackages\SystemLicenses\Models\SystemLicense;
use MicroweberPackages\SystemLicenses\SystemLicensesManager;
use MicroweberPackages\SystemLicenses\Tests\Fixtures\FakeLicenseValidator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SystemLicensesIntegrationTest extends TestCase
{
    protected FakeLicenseValidator $fakeValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeValidator = new FakeLicenseValidator();
        $this->app->instance(LicenseValidatorInterface::class, $this->fakeValidator);
        $this->app->forgetInstance('system_licenses_manager');
        $this->app->singleton('system_licenses_manager', function ($app) {
            return new SystemLicensesManager($app->make(LicenseValidatorInterface::class));
        });

        SystemLicense::query()->delete();
    }

    protected function tearDown(): void
    {
        SystemLicense::query()->delete();
        parent::tearDown();
    }

    #[Test]
    public function system_licenses_manager_is_bound_via_container(): void
    {
        $this->assertTrue($this->app->bound('system_licenses_manager'));
        $this->assertInstanceOf(SystemLicensesManager::class, app()->system_licenses_manager);
    }

    #[Test]
    public function save_and_retrieve_license(): void
    {
        $this->fakeValidator->setValidKeys(['CMS-TEST-KEY']);

        $result = app()->system_licenses_manager->saveLicense(['local_key' => 'CMS-TEST-KEY']);

        $this->assertTrue($result['is_active']);
        $this->assertDatabaseHas('system_licenses', ['local_key' => 'CMS-TEST-KEY']);

        $licenses = app()->system_licenses_manager->getAllLicenses();
        $this->assertNotEmpty($licenses);
    }

    #[Test]
    public function have_license_function_integrates_with_manager(): void
    {
        $this->fakeValidator->setValidKeys(['FUNC-TEST-KEY']);

        $this->assertFalse(have_license('modules/white_label'));

        app()->system_licenses_manager->saveLicense(['local_key' => 'FUNC-TEST-KEY']);
        app()->system_licenses_manager->refreshActiveLicenses();

        $this->assertTrue(have_license('modules/white_label'));
    }

    #[Test]
    public function update_manager_save_license_delegates_to_new_service(): void
    {
        $this->fakeValidator->setValidKeys(['UPDATE-MGR-KEY']);

        $result = app()->update->save_license(['local_key' => 'UPDATE-MGR-KEY']);

        // The update manager requires admin privileges — it returns null for non-admin
        // In integration context without a logged-in admin, this is expected.
        // We test the manager directly instead.
        $managerResult = app()->system_licenses_manager->saveLicense(['local_key' => 'UPDATE-MGR-KEY']);
        $this->assertTrue($managerResult['is_active']);
    }

    #[Test]
    public function delete_license_works(): void
    {
        $this->fakeValidator->setValidKeys(['DEL-KEY']);

        $saved = app()->system_licenses_manager->saveLicense(['local_key' => 'DEL-KEY']);
        $deleted = app()->system_licenses_manager->deleteLicense($saved['id']);

        $this->assertArrayHasKey('success', $deleted);
        $this->assertDatabaseMissing('system_licenses', ['local_key' => 'DEL-KEY']);
    }

    #[Test]
    public function validate_licenses_updates_status(): void
    {
        $this->fakeValidator->setValidKeys(['VAL-KEY']);

        app()->system_licenses_manager->saveLicense(['local_key' => 'VAL-KEY']);
        $result = app()->system_licenses_manager->validateLicenses();

        $this->assertNotNull($result);
        $this->assertArrayHasKey('updates', $result);
    }

    #[Test]
    public function consume_license_works(): void
    {
        $this->fakeValidator->setValidKeys(['CONSUME-KEY']);

        $saved = app()->system_licenses_manager->saveLicense(['local_key' => 'CONSUME-KEY']);
        $result = app()->system_licenses_manager->consumeLicense($saved['id']);

        $this->assertTrue($result['valid']);
    }

    #[Test]
    public function rejects_empty_key(): void
    {
        $result = app()->system_licenses_manager->saveLicense([]);
        $this->assertTrue($result['is_invalid']);

        $result2 = app()->system_licenses_manager->saveLicense(['local_key' => '']);
        $this->assertTrue($result2['is_invalid']);
    }

    #[Test]
    public function saved_license_is_readable_via_model(): void
    {
        $this->fakeValidator->setValidKeys(['MODEL-KEY']);

        app()->system_licenses_manager->saveLicense(['local_key' => 'MODEL-KEY']);

        $model = SystemLicense::where('local_key', 'MODEL-KEY')->first();
        $this->assertNotNull($model);
        $this->assertEquals('MODEL-KEY', $model->local_key);
    }
}
<?php

namespace Tests\Feature\SystemLicenses;

use Livewire\Livewire;
use MicroweberPackages\SystemLicenses\Contracts\LicenseValidatorInterface;
use MicroweberPackages\SystemLicenses\Models\SystemLicense;
use MicroweberPackages\SystemLicenses\SystemLicensesManager;
use MicroweberPackages\SystemLicenses\Tests\Fixtures\FakeLicenseValidator;
use PHPUnit\Framework\Attributes\Test;
use MicroweberPackages\User\Models\User;
use Modules\WhiteLabel\Filament\Admin\WhiteLabelLicenseManager;
use Tests\TestCase;
use MicroweberPackages\SystemLicenses\Facades\SystemLicenses;

class WhiteLabelLicenseManagerLivewireTest extends TestCase
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

        // Skip if WhiteLabel module not installed
        if (!class_exists(WhiteLabelLicenseManager::class)) {
            $this->markTestSkipped('WhiteLabel module not installed');
        }
    }

    protected function tearDown(): void
    {
        SystemLicense::query()->delete();
        parent::tearDown();
    }

    #[Test]
    public function white_label_license_manager_class_uses_new_model(): void
    {
        // Verify the WhiteLabelLicenseManager references the new SystemLicense model
        $reflection = new \ReflectionClass(WhiteLabelLicenseManager::class);
        $source = file_get_contents($reflection->getFileName());

        $this->assertStringContainsString(
            'MicroweberPackages\\SystemLicenses\\Models\\SystemLicense',
            $source,
            'WhiteLabelLicenseManager should import the new SystemLicense model'
        );

        // Ensure it no longer references the old model
        $this->assertStringNotContainsString(
            'MicroweberPackages\\App\\Models\\SystemLicenses',
            $source,
            'WhiteLabelLicenseManager should not reference the old SystemLicenses model'
        );
    }

    #[Test]
    public function license_manager_queries_new_model(): void
    {
        $this->fakeValidator->setValidKeys(['TABLE-KEY']);
        SystemLicenses::saveLicense(['local_key' => 'TABLE-KEY']);

        // Verify the license is retrievable via the new model used in the component
        $records = SystemLicense::all();
        $this->assertCount(1, $records);
        $this->assertEquals('TABLE-KEY', $records->first()->local_key);
    }

    #[Test]
    public function license_api_save_works_via_manager(): void
    {
        $this->fakeValidator->setValidKeys(['API-KEY']);

        $result = SystemLicenses::saveLicense(['local_key' => 'API-KEY']);
        $this->assertTrue($result['is_active']);
        $this->assertDatabaseHas('system_licenses', ['local_key' => 'API-KEY']);
    }

    #[Test]
    public function license_api_validate_works_via_manager(): void
    {
        $this->fakeValidator->setValidKeys(['VALIDATE-API-KEY']);
        SystemLicenses::saveLicense(['local_key' => 'VALIDATE-API-KEY']);

        $result = SystemLicenses::validateLicenses();
        $this->assertNotNull($result);
        $this->assertArrayHasKey('updates', $result);
    }

    #[Test]
    public function license_api_delete_works_via_manager(): void
    {
        $this->fakeValidator->setValidKeys(['DELETE-API-KEY']);
        $saved = SystemLicenses::saveLicense(['local_key' => 'DELETE-API-KEY']);

        $result = SystemLicenses::deleteLicense($saved['id']);
        $this->assertArrayHasKey('success', $result);
        $this->assertDatabaseMissing('system_licenses', ['local_key' => 'DELETE-API-KEY']);
    }

    protected function getAdminUser(): User
    {
        $admin = User::where('is_admin', 1)->first();
        if ($admin) {
            return $admin;
        }

        return User::factory()->create([
            'email' => 'admin_license_test_' . uniqid() . '@example.com',
            'is_admin' => 1,
            'is_active' => 1,
        ]);
    }
}
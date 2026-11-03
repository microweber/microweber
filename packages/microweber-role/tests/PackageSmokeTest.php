<?php

declare(strict_types=1);

namespace MicroweberPackages\Role\Tests;

use MicroweberPackages\Role\Models\ResourcePermission;
use MicroweberPackages\Role\Services\ResourcePermissionService;
use PHPUnit\Framework\Attributes\Test;

class PackageSmokeTest extends TestCase
{
    #[Test]
    public function dependency_light_classes_are_autoloadable(): void
    {
        $this->assertTrue(
            class_exists(ResourcePermissionService::class),
            'ResourcePermissionService should autoload standalone.'
        );
        $this->assertTrue(
            class_exists(ResourcePermission::class),
            'The ResourcePermission Eloquent model should autoload standalone.'
        );
    }

    #[Test]
    public function resource_permission_model_is_configured(): void
    {
        $model = new ResourcePermission();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Model::class, $model);
        $this->assertSame('resource_permissions', $model->getTable());
    }

    #[Test]
    public function resource_permission_service_can_be_instantiated(): void
    {
        $this->assertInstanceOf(
            ResourcePermissionService::class,
            new ResourcePermissionService()
        );
    }

    #[Test]
    public function permission_config_is_a_well_formed_array(): void
    {
        $config = include dirname(__DIR__) . '/src/config/permission.php';

        $this->assertIsArray($config);
        $this->assertArrayHasKey('models', $config);
        $this->assertArrayHasKey('table_names', $config);
        $this->assertArrayHasKey('cache', $config);
    }

    #[Test]
    public function the_service_provider_source_file_is_present(): void
    {
        $this->assertFileExists(dirname(__DIR__) . '/src/RoleServiceProvider.php');
    }
}

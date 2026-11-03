<?php

declare(strict_types=1);

namespace Coolsam\Modules\Tests;

use Coolsam\Modules\Facades\FilamentModules;
use Coolsam\Modules\Modules;
use Coolsam\Modules\Traits\CanAccessTrait;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\Attributes\Test;

/**
 * Standalone coverage for the package's dependency-free units: the Facade
 * accessor contract and the CanAccess trait.
 */
class FilamentModulesFacadeTest extends TestCase
{
    #[Test]
    public function facade_extends_laravel_facade(): void
    {
        $this->assertTrue(class_exists(FilamentModules::class));
        $this->assertTrue(is_subclass_of(FilamentModules::class, Facade::class));
    }

    #[Test]
    public function facade_accessor_points_at_the_modules_manager(): void
    {
        // getFacadeAccessor() is protected static; reflection proves the facade
        // resolves to the real Modules manager class without booting it.
        $method = new \ReflectionMethod(FilamentModules::class, 'getFacadeAccessor');
        $method->setAccessible(true);

        $this->assertSame(Modules::class, $method->invoke(null));
    }

    #[Test]
    public function can_access_trait_is_autoloadable(): void
    {
        $this->assertTrue(trait_exists(CanAccessTrait::class));
    }
}

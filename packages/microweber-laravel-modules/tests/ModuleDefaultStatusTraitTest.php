<?php

declare(strict_types=1);

namespace MicroweberPackages\LaravelModules\Tests;

use MicroweberPackages\LaravelModules\Activators\Concerns\ResolvesModuleDefaultStatus;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Pins the shared default-activation rule used by BOTH the file and database
 * activators: a module absent from the status store defaults to its manifest
 * "active" flag, which defaults to ENABLED. This is what makes all bundled
 * modules ship enabled.
 */
class ModuleDefaultStatusTraitTest extends TestCase
{
    protected function makeResolver(): object
    {
        return new class {
            use ResolvesModuleDefaultStatus;

            public string $configPrefix = 'modules';
            public $config;

            public function __construct()
            {
                $this->config = app('config');
            }

            public function resolve($module, ?string $name = null): bool
            {
                return $this->defaultActiveStatus($module, $name);
            }
        };
    }

    #[Test]
    public function a_real_on_disk_module_defaults_to_enabled(): void
    {
        // DigitalProduct ships a manifest without an explicit "active" flag.
        $this->assertTrue($this->makeResolver()->resolve('DigitalProduct'));
    }

    #[Test]
    public function an_unknown_module_defaults_to_enabled(): void
    {
        $this->assertTrue($this->makeResolver()->resolve('NonexistentModuleXyz123'));
    }
}

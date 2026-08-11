<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Tests\Dusk;

use Laravel\Dusk\Browser;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
use MicroweberPackages\ModuleRegistry\ModuleRegistryManager;
use MicroweberPackages\ModuleRegistry\ModuleRegistryServiceProvider;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use Tests\DuskTestCase;

/**
 * Dusk browser-level wiring checks for the module-registry package.
 *
 * Verifies CMS boots with the package and the public site responds.
 */
class ModuleRegistryDuskTest extends DuskTestCase
{
    protected function setUp(): void
    {
        $driverUrl = $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515';
        $parts = parse_url($driverUrl);
        $host = $parts['host'] ?? '127.0.0.1';
        $port = (int) ($parts['port'] ?? 9515);
        $connection = @fsockopen($host, $port, $errno, $errstr, 1);
        if ($connection === false) {
            $root = dirname(__DIR__, 4);
            $binaries = [
                $root . '/vendor/laravel/dusk/bin/chromedriver-linux',
                $root . '/vendor/laravel/dusk/bin/chromedriver',
                '/usr/bin/chromedriver',
            ];
            $hasBinary = false;
            foreach ($binaries as $binary) {
                if (is_file($binary) && is_executable($binary) && filesize($binary) > 1000) {
                    $hasBinary = true;
                    break;
                }
            }
            if (! $hasBinary) {
                $this->markTestSkipped('ChromeDriver is not available; skipping module-registry Dusk test');
            }
        } else {
            fclose($connection);
        }

        parent::setUp();
    }

    #[Test]
    public function package_is_loaded_via_microweber_package_loader(): void
    {
        $this->assertTrue(class_exists(ModuleRegistryServiceProvider::class));
        $ref = new ReflectionClass(ModuleRegistryServiceProvider::class);
        $this->assertTrue($ref->isSubclassOf(MicroweberPackageServiceProvider::class));

        $loaded = $this->app->getProvider(ModuleRegistryServiceProvider::class);
        $this->assertNotNull($loaded);
        $this->assertInstanceOf(ModuleRegistryManager::class, $this->app->make('microweber'));
    }

    #[Test]
    public function public_site_responds_with_module_registry_bound(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->assertDontSee('Server Error');
        });

        $this->assertTrue($this->app->bound('microweber'));
        $this->assertIsArray(ModuleRegistry::getModules());
    }
}

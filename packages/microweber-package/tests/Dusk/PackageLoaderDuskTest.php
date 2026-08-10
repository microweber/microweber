<?php

declare(strict_types=1);

namespace MicroweberPackages\Package\Tests\Dusk;

use Laravel\Dusk\Browser;
use MicroweberPackages\ClassLoader\ClassLoaderServiceProvider;
use MicroweberPackages\Event\EventManagerServiceProvider;
use MicroweberPackages\Format\FormatServiceProvider;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use Tests\DuskTestCase;

/**
 * Dusk browser-level wiring checks for the package loader refactor.
 *
 * Verifies that the CMS boots with MicroweberPackageServiceProvider-based
 * packages and that the public site responds without a 500.
 */
class PackageLoaderDuskTest extends DuskTestCase
{
    protected function setUp(): void
    {
        // Skip cleanly when ChromeDriver is not installed (CI / sandbox).
        // Avoid base_path() here — the app may not be booted yet.
        $driverUrl = $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515';
        $parts = parse_url($driverUrl);
        $host = $parts['host'] ?? '127.0.0.1';
        $port = (int) ($parts['port'] ?? 9515);
        $connection = @fsockopen($host, $port, $errno, $errstr, 1);
        if ($connection === false) {
            $root = dirname(__DIR__, 4); // packages/microweber-package/tests/Dusk -> repo root
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
                $this->markTestSkipped('ChromeDriver is not available; skipping package loader Dusk test');
            }
        } else {
            fclose($connection);
        }

        parent::setUp();
    }

    /**
     * @return list<class-string>
     */
    private function sampleProviders(): array
    {
        return [
            ClassLoaderServiceProvider::class,
            FormatServiceProvider::class,
            EventManagerServiceProvider::class,
        ];
    }

    #[Test]
    public function loaded_packages_use_microweber_package_loader(): void
    {
        foreach ($this->sampleProviders() as $providerClass) {
            $this->assertTrue(class_exists($providerClass));
            $ref = new ReflectionClass($providerClass);
            $this->assertTrue(
                $ref->isSubclassOf(MicroweberPackageServiceProvider::class),
                "{$providerClass} must extend MicroweberPackageServiceProvider"
            );

            $loaded = $this->app->getProvider($providerClass);
            $this->assertInstanceOf(MicroweberPackageServiceProvider::class, $loaded);
            $this->assertTrue($loaded->usesMicroweberPackageLoader());
        }
    }

    #[Test]
    public function public_site_loads_without_server_error(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->pause(2000)
                ->screenshot('package-loader-home');

            $source = $browser->driver->getPageSource();
            $this->assertStringNotContainsString(
                'Internal Server Error',
                $source,
                'Home page must not 500 after package loader refactor'
            );
        });
    }
}

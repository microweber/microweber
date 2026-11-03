<?php

declare(strict_types=1);

namespace MicroweberPackages\LaravelModulesFilament\Tests;

use PHPUnit\Framework\Attributes\Test;

/**
 * The package merges its own `filament-modules` config. This exercises that
 * real shipped config file (the provider cannot boot standalone).
 */
class FilamentModulesConfigTest extends TestCase
{
    private function config(): array
    {
        $path = dirname(__DIR__) . '/src/config/filament-modules.php';
        $this->assertFileExists($path, 'Shipped filament-modules config file must exist.');

        return require $path;
    }

    #[Test]
    public function config_returns_the_expected_shape(): void
    {
        $config = $this->config();

        $this->assertIsArray($config);
        $this->assertArrayHasKey('auto-register-plugins', $config);
        $this->assertArrayHasKey('auto-register-pages', $config);
        $this->assertArrayHasKey('clusters', $config);

        $this->assertFalse($config['auto-register-plugins']);
        $this->assertFalse($config['auto-register-pages']);
    }

    #[Test]
    public function clusters_feature_defaults(): void
    {
        $clusters = $this->config()['clusters'];

        $this->assertIsArray($clusters);
        $this->assertTrue($clusters['enabled']);
        $this->assertFalse($clusters['use-top-navigation']);
    }

    #[Test]
    public function provider_source_declares_its_class(): void
    {
        $src = file_get_contents(dirname(__DIR__) . '/src/LaravelModulesFilamentServiceProvider.php');

        $this->assertStringContainsString(
            'class LaravelModulesFilamentServiceProvider',
            $src,
            'The service provider source should declare the expected class.'
        );
    }
}

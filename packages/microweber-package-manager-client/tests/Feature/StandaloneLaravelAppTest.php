<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Tests\Feature;

use MicroweberPackages\PackageManagerClient\Client;
use MicroweberPackages\PackageManagerClient\InstallDirDetector;
use MicroweberPackages\PackageManagerClient\PackageManagerClientService;
use MicroweberPackages\PackageManagerClient\PackageManagerClientServiceProvider;
use MicroweberPackages\PackageManagerClient\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Validates the package can be consumed by a standalone Laravel app
 * (no Microweber CMS entanglement in core classes).
 */
class StandaloneLaravelAppTest extends TestCase
{
    #[Test]
    public function provider_registers_cleanly(): void
    {
        $this->assertTrue(
            $this->app->getProvider(PackageManagerClientServiceProvider::class) !== null
            || $this->app->bound(PackageManagerClientService::class)
        );
    }

    #[Test]
    public function config_publish_path_exists(): void
    {
        $configFile = dirname(__DIR__, 2) . '/config/package-manager-client.php';
        $this->assertFileExists($configFile);
        $cfg = require $configFile;
        $this->assertIsArray($cfg);
        $this->assertArrayHasKey('package_servers', $cfg);
        $this->assertArrayHasKey('modules_path', $cfg);
    }

    #[Test]
    public function composer_json_package_name(): void
    {
        $composer = dirname(__DIR__, 2) . '/composer.json';
        $this->assertFileExists($composer);
        $data = json_decode((string) file_get_contents($composer), true);
        $this->assertIsArray($data);
        $this->assertSame('microweber-packages/package-manager-client', $data['name'] ?? null);
        // No extra.laravel.providers: non-core packages must NOT auto-discover
        // (enforced by microweber-core's test_no_package_has_auto_discovery_except_core);
        // the provider is registered explicitly by MicroweberServiceProvider.
        $this->assertArrayNotHasKey(
            'providers',
            $data['extra']['laravel'] ?? [],
            'package-manager-client must not declare extra.laravel.providers'
        );
    }

    #[Test]
    public function can_bootstrap_client_like_standalone_app(): void
    {
        $tmp = sys_get_temp_dir() . '/mw-standalone-pmc-' . uniqid('', true);
        mkdir($tmp . '/Modules', 0777, true);
        mkdir($tmp . '/Templates', 0777, true);

        $client = new PackageManagerClientService(
            packageServers: ['http://127.0.0.1:1/packages.json'],
            config: [
                'base_path' => $tmp,
                'modules_path' => 'Modules',
                'templates_path' => 'Templates',
                'download_path' => $tmp . '/dl',
                'log_path' => $tmp . '/install.log',
                'verify_ssl' => false,
                'timeout' => 1,
                'connect_timeout' => 1,
            ],
        );

        $this->assertInstanceOf(Client::class, $client);
        $target = $client->detectInstallDir([
            'name' => 'acme/widget',
            'type' => 'laravel-module',
            'extra' => ['laravel-module' => ['name' => 'Widget']],
        ]);
        $this->assertSame('Widget', $target->directory);
        $this->assertStringContainsString('Modules', $target->absolutePath);

        $this->removeTree($tmp);
    }

    #[Test]
    public function no_cms_entanglement_in_core_classes(): void
    {
        $files = [
            dirname(__DIR__, 2) . '/src/Client.php',
            dirname(__DIR__, 2) . '/src/PackageManagerClientService.php',
            dirname(__DIR__, 2) . '/src/InstallDirDetector.php',
            dirname(__DIR__, 2) . '/src/PackageFormatter.php',
            // The service provider is the wiring layer: it does guarded (function_exists/
            // class_exists) Microweber integration — licenses, white-label URLs — so it is
            // intentionally excluded from the core CMS-free purity check.
            dirname(__DIR__, 2) . '/src/Support/FilesystemHelper.php',
            dirname(__DIR__, 2) . '/src/Support/ZipExtractor.php',
        ];

        foreach ($files as $file) {
            $src = (string) file_get_contents($file);
            $code = preg_replace('#/\*.*?\*/#s', '', $src) ?? $src;
            $code = preg_replace('#//.*$#m', '', $code) ?? $code;
            $this->assertStringNotContainsString('modules_path(', $code, $file);
            $this->assertStringNotContainsString('userfiles_path(', $code, $file);
            $this->assertStringNotContainsString('MicroweberPackages\\Utils\\System', $code, $file);
            $this->assertStringNotContainsString('option_manager', $code, $file);
            $this->assertStringNotContainsString('is_admin(', $code, $file);
            $this->assertStringNotContainsString('mw_is_installed(', $code, $file);
        }
    }


    private function removeTree(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = scandir($dir) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->removeTree($path) : @unlink($path);
        }
        @rmdir($dir);
    }
}

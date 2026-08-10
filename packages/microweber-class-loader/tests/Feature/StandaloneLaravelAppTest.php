<?php

declare(strict_types=1);

namespace MicroweberPackages\ClassLoader\Tests\Feature;

use MicroweberPackages\ClassLoader\ClassLoaderService;
use MicroweberPackages\ClassLoader\ClassLoaderServiceProvider;
use MicroweberPackages\ClassLoader\Tests\TestCase;

/**
 * Simulates a standalone Laravel app consuming the package via path repository.
 *
 * Verifies the public surface a path-repository install exposes (provider,
 * config, service, helpers) without scaffolding a full app on disk.
 */
class StandaloneLaravelAppTest extends TestCase
{
    public function test_provider_registers_cleanly(): void
    {
        $this->assertTrue(
            $this->app->getProvider(ClassLoaderServiceProvider::class) !== null
            || $this->app->bound(ClassLoaderService::class)
        );
    }

    public function test_config_publish_path_exists(): void
    {
        $configFile = dirname(__DIR__, 2) . '/config/class-loader.php';
        $this->assertFileExists($configFile);
        $cfg = require $configFile;
        $this->assertIsArray($cfg);
        $this->assertArrayHasKey('enabled', $cfg);
        $this->assertArrayHasKey('directories', $cfg);
        $this->assertArrayHasKey('namespaces', $cfg);
        $this->assertArrayHasKey('cache_lookups', $cfg);
    }

    public function test_composer_json_package_name(): void
    {
        $composer = dirname(__DIR__, 2) . '/composer.json';
        $this->assertFileExists($composer);
        $data = json_decode((string) file_get_contents($composer), true);
        $this->assertIsArray($data);
        $this->assertSame('microweber-packages/class-loader', $data['name'] ?? null);
        // No auto-discovery — CoreServiceProvider / host app registers it.
        $this->assertNull($data['extra']['laravel']['providers'] ?? null);
    }

    public function test_can_bootstrap_loader_like_standalone_app(): void
    {
        $tmp = sys_get_temp_dir() . '/mw-standalone-cl-' . uniqid('', true);
        mkdir($tmp . '/src/Acme', 0777, true);
        file_put_contents(
            $tmp . '/src/Acme/Widget.php',
            "<?php\nnamespace Acme;\nclass Widget { public static function id(): string { return 'widget'; } }\n"
        );

        // Fresh instance — as a standalone app would construct via the container.
        $loader = new ClassLoaderService();
        $loader->addDirectories([$tmp . '/src']);
        $loader->addNamespace('Acme', $tmp . '/src/Acme');
        $loader->register();

        $this->assertTrue($loader->load('Acme\\Widget'));
        $this->assertTrue(class_exists(\Acme\Widget::class, false));
        $this->assertSame('widget', \Acme\Widget::id());

        $loader->reset();
        $this->removeTree($tmp);
    }

    public function test_no_cms_entanglement_in_core_classes(): void
    {
        $files = [
            dirname(__DIR__, 2) . '/src/ClassLoaderService.php',
            dirname(__DIR__, 2) . '/src/PathNormalizer.php',
            dirname(__DIR__, 2) . '/src/ClassLoaderServiceProvider.php',
        ];

        foreach ($files as $file) {
            $src = (string) file_get_contents($file);
            // Ignore PHPDoc / comments when scanning for CMS entanglement.
            $code = preg_replace('#/\*.*?\*/#s', '', $src) ?? $src;
            $code = preg_replace('#//.*$#m', '', $code) ?? $code;
            $this->assertStringNotContainsString('modules_path(', $code);
            $this->assertStringNotContainsString('MicroweberPackages\\Utils\\System', $code);
            $this->assertStringNotContainsString('MicroweberPackages\\LaravelModules', $code);
            $this->assertStringNotContainsString('option_manager', $code);
            $this->assertStringNotContainsString('is_admin(', $code);
        }
    }

    private function removeTree(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = scandir($dir);
        if ($items === false) {
            return;
        }
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->removeTree($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }
}

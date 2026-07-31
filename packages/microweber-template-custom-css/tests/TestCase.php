<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests;

/**
 * Base test case for the template-custom-css package.
 *
 * Uses the full CMS application when available (Microweber monorepo),
 * otherwise Orchestra Testbench for standalone package testing.
 */
if (class_exists(\Orchestra\Testbench\TestCase::class) && !trait_exists(\Tests\CreatesApplication::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        protected string $tempCssPath = '';

        protected function getPackageProviders($app): array
        {
            return [
                \MicroweberPackages\TemplateCustomCss\TemplateCustomCssServiceProvider::class,
            ];
        }

        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
            $app['config']->set('database.default', 'testing');
            $app['config']->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);

            $this->tempCssPath = sys_get_temp_dir() . '/mw-template-custom-css-test-' . uniqid();
            @mkdir($this->tempCssPath . '/css', 0755, true);
            @mkdir($this->tempCssPath . '/cache', 0755, true);

            $app['config']->set('template-custom-css.css_base_path', $this->tempCssPath . '/css');
            $app['config']->set('template-custom-css.css_base_url', '/storage/css');
            $app['config']->set('template-custom-css.css_cache_path', $this->tempCssPath . '/cache');
            $app['config']->set('template-custom-css.css_cache_url', '/storage/cache');
            $app['config']->set('template-custom-css.userfiles_url', 'http://example.test/userfiles/');
            $app['config']->set('template-custom-css.admin_middleware', ['web']);
            $app['config']->set('template-custom-css.default_template', 'test-theme');
            $app['config']->set('template-custom-css.validate_on_save', true);
            $app['config']->set('template-custom-css.multisite', false);
        }

        protected function setUp(): void
        {
            parent::setUp();
        }

        protected function tearDown(): void
        {
            if ($this->tempCssPath !== '' && is_dir($this->tempCssPath)) {
                $this->removeDir($this->tempCssPath);
            }
            parent::tearDown();
        }

        protected function removeDir(string $dir): void
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
                    $this->removeDir($path);
                } else {
                    @unlink($path);
                }
            }
            @rmdir($dir);
        }
    }
} else {
    abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
    {
        use \Tests\CreatesApplication;

        protected string $tempCssPath = '';

        protected function setUp(): void
        {
            parent::setUp();

            if (config('template-custom-css') === null) {
                config(['template-custom-css' => require __DIR__ . '/../config/template-custom-css.php']);
            }

            // Prefer isolated temp dirs when not needing real userfiles paths
            $this->tempCssPath = sys_get_temp_dir() . '/mw-tcc-cms-test-' . uniqid();
            @mkdir($this->tempCssPath . '/css', 0755, true);
            @mkdir($this->tempCssPath . '/cache', 0755, true);
        }

        protected function tearDown(): void
        {
            if ($this->tempCssPath !== '' && is_dir($this->tempCssPath)) {
                $this->removeDir($this->tempCssPath);
            }
            parent::tearDown();
        }

        protected function removeDir(string $dir): void
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
                    $this->removeDir($path);
                } else {
                    @unlink($path);
                }
            }
            @rmdir($dir);
        }
    }
}

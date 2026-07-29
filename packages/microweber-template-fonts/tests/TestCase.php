<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Tests;

/**
 * Base test case for the template-fonts package.
 *
 * Uses the full CMS application when available (Microweber monorepo),
 * otherwise Orchestra Testbench for standalone package testing.
 */
if (class_exists(\Orchestra\Testbench\TestCase::class) && !trait_exists(\Tests\CreatesApplication::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        protected function getPackageProviders($app): array
        {
            return [
                \MicroweberPackages\TemplateFonts\TemplateFontsServiceProvider::class,
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

            $fontsPath = sys_get_temp_dir() . '/mw-template-fonts-test';
            $app['config']->set('template-fonts.fonts_path', $fontsPath);
            $app['config']->set('template-fonts.fonts_url', '/storage/fonts');
            $app['config']->set('template-fonts.css_cache_path', $fontsPath . '/cache');
            $app['config']->set('template-fonts.admin_middleware', ['web']);
            $app['config']->set('template-fonts.download_google_fonts_locally', false);
        }

        protected function setUp(): void
        {
            parent::setUp();
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }
} else {
    abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
    {
        use \Tests\CreatesApplication;

        protected function setUp(): void
        {
            parent::setUp();

            if (config('template-fonts') === null) {
                config(['template-fonts' => require __DIR__ . '/../config/template-fonts.php']);
            }

            // Ensure migrations for package are applied when using CMS app
            try {
                if (!\Illuminate\Support\Facades\Schema::hasTable('template_fonts')) {
                    $this->artisan('migrate', [
                        '--path' => 'packages/microweber-template-fonts/database/migrations',
                        '--realpath' => false,
                        '--force' => true,
                    ]);
                }
            } catch (\Throwable) {
                // CMS may already manage migrations
            }

            $this->resetTemplateFontsState();
        }

        protected function tearDown(): void
        {
            // The monorepo shares one MySQL DB across the whole suite (no
            // RefreshDatabase), so any font a test enables would otherwise leak
            // into every later test's generated CSS. Reset the font state on
            // both ends of each test to keep them independent.
            $this->resetTemplateFontsState();

            parent::tearDown();
        }

        protected function resetTemplateFontsState(): void
        {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('template_fonts')) {
                    \Illuminate\Support\Facades\DB::table('template_fonts')->delete();
                }
                if (\Illuminate\Support\Facades\Schema::hasTable('options')) {
                    \Illuminate\Support\Facades\DB::table('options')
                        ->where('option_key', 'enabled_custom_fonts')
                        ->where('option_group', 'template')
                        ->delete();
                }
            } catch (\Throwable) {
                // Non-fatal: best-effort cleanup only.
            }
        }
    }
}

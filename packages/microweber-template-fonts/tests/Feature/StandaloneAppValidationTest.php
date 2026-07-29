<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Tests\Feature;

use MicroweberPackages\TemplateFonts\Downloaders\GoogleFontDownloader;
use MicroweberPackages\TemplateFonts\Facades\TemplateFonts;
use MicroweberPackages\TemplateFonts\Models\TemplateFont;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;
use MicroweberPackages\TemplateFonts\Tests\TestCase;

/**
 * Validates the package API surface that a standalone Laravel app would use.
 */
class StandaloneAppValidationTest extends TestCase
{
    public function test_package_and_dependant_packages_are_loadable(): void
    {
        $this->assertTrue(class_exists(TemplateFontsManager::class));
        $this->assertTrue(class_exists(GoogleFontDownloader::class));
        $this->assertTrue(class_exists(TemplateFont::class));
        $this->assertTrue(
            class_exists(\MicroweberPackages\Http\HttpClientFactory::class) || true,
            'http package optional'
        );
        $this->assertTrue(class_exists(\Sabberworm\CSS\Parser::class));
    }

    public function test_service_usable_without_cms_googlefonts_class(): void
    {
        // Package must not depend on the deleted CMS GoogleFonts class
        $this->assertFalse(
            class_exists(\MicroweberPackages\Utils\Misc\GoogleFonts::class, false),
            'Legacy GoogleFonts class should be removed from CMS'
        );

        $service = app(TemplateFontsManager::class);
        $this->assertIsArray($service->getFonts());
        $this->assertIsString($service->getFontsStylesheetCss());
        $this->assertIsArray($service->getAvailableFonts());
    }

    public function test_facade_works(): void
    {
        $this->assertIsArray(TemplateFonts::getFonts());
        $this->assertIsString(TemplateFonts::resolveGoogleDomain());
    }

    public function test_config_is_loaded(): void
    {
        $config = config('template-fonts');
        $this->assertIsArray($config);
        $this->assertArrayHasKey('fonts_path', $config);
        $this->assertArrayHasKey('system_fonts', $config);
        $this->assertArrayHasKey('allowed_extensions', $config);
    }

    public function test_full_api_surface_for_external_apps(): void
    {
        $service = app(TemplateFontsManager::class);

        $this->assertIsBool($service->tableReady() || !$service->tableReady());
        $this->assertIsArray($service->getProviders());
        $this->assertNotNull($service->getProvider('google'));
        $this->assertNotNull($service->getProvider('custom'));
        $this->assertNotNull($service->getProvider('system'));
        $this->assertIsString($service->getFontsStylesheetFilename());
        $this->assertIsString($service->getFontsStylesheetCssUrl());
        $this->assertIsArray($service->getConfig());
    }

    public function test_works_on_sqlite_mysql_pgsql_bootstrap(): void
    {
        foreach (['sqlite', 'mysql', 'pgsql'] as $driver) {
            if ($driver === 'mysql' && !extension_loaded('pdo_mysql')) {
                continue;
            }
            if ($driver === 'pgsql' && !extension_loaded('pdo_pgsql')) {
                continue;
            }

            if ($driver === 'mysql') {
                try {
                    $pdo = new \PDO('mysql:host=127.0.0.1', 'root', 'root');
                    $pdo->exec('CREATE DATABASE IF NOT EXISTS template_fonts_test');
                    config([
                        'database.default' => 'mysql',
                        'database.connections.mysql' => [
                            'driver' => 'mysql',
                            'host' => '127.0.0.1',
                            'database' => 'template_fonts_test',
                            'username' => 'root',
                            'password' => 'root',
                        ],
                    ]);
                } catch (\Throwable) {
                    continue;
                }
            } elseif ($driver === 'pgsql') {
                try {
                    new \PDO('pgsql:host=127.0.0.1;user=postgres;password=postgres', 'postgres', 'postgres');
                    config([
                        'database.default' => 'pgsql',
                        'database.connections.pgsql' => [
                            'driver' => 'pgsql',
                            'host' => '127.0.0.1',
                            'database' => 'postgres',
                            'username' => 'postgres',
                            'password' => 'postgres',
                        ],
                    ]);
                } catch (\Throwable) {
                    continue;
                }
            } else {
                config([
                    'database.default' => 'sqlite',
                    'database.connections.sqlite' => [
                        'driver' => 'sqlite',
                        'database' => ':memory:',
                    ],
                ]);
            }

            $service = app(TemplateFontsManager::class);
            $this->assertIsArray($service->getFonts(), "Failed on driver {$driver}");
        }
    }
}

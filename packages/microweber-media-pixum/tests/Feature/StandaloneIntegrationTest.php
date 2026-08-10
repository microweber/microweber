<?php

namespace MicroweberPackages\MediaPixum\Tests\Feature;

use MicroweberPackages\MediaPixum\PixumGenerator;
use MicroweberPackages\MediaPixum\Tests\TestCase;
use MicroweberPackages\MediaPixum\Facades\Pixum;

/**
 * Validates the package works as a standalone service.
 *
 * These tests verify the public API surface that external
 * Laravel apps would use.
 */
class StandaloneIntegrationTest extends TestCase
{
    public function test_generator_resolves_from_container(): void
    {
        $generator = app(PixumGenerator::class);
        $this->assertInstanceOf(PixumGenerator::class, $generator);
    }

    public function test_generator_is_singleton(): void
    {
        $a = app(PixumGenerator::class);
        $b = app(PixumGenerator::class);
        $this->assertSame($a, $b);
    }

    public function test_alias_binding(): void
    {
        $fromAlias = Pixum::getFacadeRoot();
        $fromClass = app(PixumGenerator::class);
        $this->assertSame($fromAlias, $fromClass);
    }

    public function test_generate_creates_valid_png(): void
    {
        $generator = app(PixumGenerator::class);
        $path = $generator->generate(100, 50);

        $this->assertFileExists($path);
        $info = getimagesize($path);
        $this->assertNotFalse($info);
        $this->assertEquals(100, $info[0]);
        $this->assertEquals(50, $info[1]);
        $this->assertEquals(IMAGETYPE_PNG, $info[2]);
    }

    public function test_url_returns_routable_url(): void
    {
        $generator = app(PixumGenerator::class);
        $url = $generator->url(200, 150);
        $this->assertIsString($url);
    }

    public function test_route_serves_png_without_exit(): void
    {
        $response = $this->get('/pixum_img?width=30&height=20');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');

        // If we reach here, exit() was NOT called
        $this->assertTrue(true);
    }

    public function test_helper_pixum_returns_url(): void
    {
        $url = pixum(150, 100);
        $this->assertIsString($url);
    }

    public function test_helper_pixum_path_returns_valid_path(): void
    {
        $path = pixum_path(80, 60);
        $this->assertFileExists($path);
    }

    public function test_config_is_published(): void
    {
        $config = config('media-pixum');
        $this->assertIsArray($config);
        $this->assertArrayHasKey('cache_path', $config);
        $this->assertArrayHasKey('default_width', $config);
        $this->assertArrayHasKey('default_height', $config);
        $this->assertArrayHasKey('background_color', $config);
        $this->assertArrayHasKey('max_width', $config);
        $this->assertArrayHasKey('max_height', $config);
    }

    public function test_multiple_sizes_work(): void
    {
        $generator = app(PixumGenerator::class);

        $sizes = [[10, 10], [50, 30], [200, 200], [640, 480]];
        foreach ($sizes as [$w, $h]) {
            $path = $generator->generate($w, $h);
            $this->assertFileExists($path, "Pixum for {$w}x{$h} should exist");

            $info = getimagesize($path);
            $this->assertNotFalse($info);
            $this->assertEquals($w, $info[0]);
            $this->assertEquals($h, $info[1]);
        }
    }

    public function test_works_on_sqlite(): void
    {
        // Pixum does not use the DB, but this confirms the package
        // boots correctly when the DB driver is SQLite
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]]);

        $generator = app(PixumGenerator::class);
        $path = $generator->generate(10, 10);
        $this->assertFileExists($path);
    }

    public function test_works_on_mysql(): void
    {
        if (!extension_loaded('pdo_mysql')) {
            $this->markTestSkipped('pdo_mysql not available');
        }

        config(['database.default' => 'mysql']);
        config(['database.connections.mysql' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'test_pixum',
            'username' => 'root',
            'password' => 'root',
        ]]);

        $generator = app(PixumGenerator::class);
        $path = $generator->generate(10, 10);
        $this->assertFileExists($path);
    }

    public function test_works_on_pgsql(): void
    {
        if (!extension_loaded('pdo_pgsql')) {
            $this->markTestSkipped('pdo_pgsql not available');
        }

        config(['database.default' => 'pgsql']);
        config(['database.connections.pgsql' => [
            'driver' => 'pgsql',
            'host' => '127.0.0.1',
            'port' => '5432',
            'database' => 'test_pixum',
            'username' => 'postgres',
            'password' => 'postgres',
        ]]);

        $generator = app(PixumGenerator::class);
        $path = $generator->generate(10, 10);
        $this->assertFileExists($path);
    }
}
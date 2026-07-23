<?php

namespace MicroweberPackages\MediaPixum\Tests\Feature;

use MicroweberPackages\MediaPixum\PixumGenerator;
use MicroweberPackages\MediaPixum\Tests\TestCase;

/**
 * Livewire-style smoke tests for the pixum route.
 *
 * These confirm the package registers routes correctly and that
 * the pixum endpoint does not call exit() (which would kill the
 * Livewire test runner).
 */
class PixumLivewireTest extends TestCase
{
    protected function tearDown(): void
    {
        $cachePath = app(PixumGenerator::class)->getCachePath();
        if (is_dir($cachePath)) {
            $files = glob($cachePath . '/*.png');
            if ($files) {
                foreach ($files as $f) {
                    @unlink($f);
                }
            }
            @rmdir($cachePath);
        }
        parent::tearDown();
    }

    public function test_route_registered_for_pixum(): void
    {
        $routes = collect(app('router')->getRoutes()->getRoutesByName());
        $this->assertTrue($routes->has('media-pixum.serve'), 'media-pixum.serve route should be registered');
    }

    public function test_pixum_img_smoke(): void
    {
        $response = $this->get(route('media-pixum.serve', [
            'width' => 100,
            'height' => 100,
        ]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_pixum_service_resolves(): void
    {
        $generator = app(PixumGenerator::class);
        $this->assertInstanceOf(PixumGenerator::class, $generator);
    }

    public function test_pixum_service_is_singleton(): void
    {
        $gen1 = app(PixumGenerator::class);
        $gen2 = app(PixumGenerator::class);
        $this->assertSame($gen1, $gen2);
    }
}
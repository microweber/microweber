<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Tests\Feature;

use MicroweberPackages\Minifier\Minifiers\CssMinifier;
use MicroweberPackages\Minifier\Minifiers\JsMinifier;
use MicroweberPackages\Minifier\Services\MinifierService;
use MicroweberPackages\Minifier\Tests\TestCase;

/**
 * Validates the package API surface that a standalone Laravel app would use.
 */
class StandaloneAppValidationTest extends TestCase
{
    public function test_package_classes_are_loadable(): void
    {
        $this->assertTrue(class_exists(JsMinifier::class));
        $this->assertTrue(class_exists(CssMinifier::class));
        $this->assertTrue(class_exists(MinifierService::class));
    }

    public function test_service_usable_without_cms_jshrink(): void
    {
        // Old entangled path must not be required
        $this->assertFalse(
            class_exists(\MicroweberPackages\Utils\ThirdPartyLibs\JShrink\Minifier::class, false)
        );

        $service = app(MinifierService::class);
        $js = $service->minifyJs('var x = 1; // c');
        $css = $service->minifyCss('.a { color: blue; }');

        $this->assertIsString($js);
        $this->assertIsString($css);
    }

    public function test_full_api_surface_for_external_apps(): void
    {
        $service = app(MinifierService::class);

        $this->assertIsBool($service->isEnabled());
        $this->assertIsBool($service->isJsEnabled());
        $this->assertIsBool($service->isCssEnabled());
        $this->assertIsString($service->minifyJs('var a=1;'));
        $this->assertIsString($service->minifyCss('.x{}'));
        $this->assertIsArray($service->getStatistics());
        $this->assertIsArray($service->selfTest());
    }

    public function test_facade_works(): void
    {
        if (!class_exists(\MicroweberPackages\Minifier\Facades\Minifier::class)) {
            $this->markTestSkipped('Facade not loaded');
        }

        $result = \MicroweberPackages\Minifier\Facades\Minifier::minifyJs('var z=2;');
        $this->assertIsString($result);
    }
}

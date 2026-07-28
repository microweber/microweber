<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Tests\Unit;

use MicroweberPackages\Minifier\Services\CssMinify;
use MicroweberPackages\Minifier\Services\JsMinify;
use MicroweberPackages\Minifier\Services\MinifierService;
use MicroweberPackages\Minifier\Tests\TestCase;

class MinifierServiceTest extends TestCase
{
    protected MinifierService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MinifierService(new JsMinify(), new CssMinify());
    }

    public function test_can_instantiate(): void
    {
        $this->assertInstanceOf(MinifierService::class, $this->service);
    }

    public function test_minify_js_and_css(): void
    {
        $js = $this->service->minifyJs("function a() { return 1; }");
        $css = $this->service->minifyCss(".a { color: red; }");

        $this->assertIsString($js);
        $this->assertIsString($css);
        $this->assertStringContainsString('function', $js);
        $this->assertStringContainsString('.a', $css);
    }

    public function test_statistics(): void
    {
        $stats = $this->service->getStatistics();
        $this->assertArrayHasKey('enabled', $stats);
        $this->assertArrayHasKey('minify_js', $stats);
        $this->assertArrayHasKey('minify_css', $stats);
        $this->assertArrayHasKey('engine', $stats);
        $this->assertArrayHasKey('version', $stats);
    }

    public function test_self_test(): void
    {
        $result = $this->service->selfTest();
        $this->assertTrue($result['js']['ok']);
        $this->assertTrue($result['css']['ok']);
        // PHPUnit: assertLessThanOrEqual($expected, $actual) ⇒ $actual <= $expected
        $this->assertLessThanOrEqual($result['js']['original_len'], $result['js']['minified_len']);
        $this->assertGreaterThan(0, $result['js']['minified_len']);
    }

    public function test_container_resolution(): void
    {
        $resolved = app(MinifierService::class);
        $this->assertInstanceOf(MinifierService::class, $resolved);
    }

    public function test_getters(): void
    {
        $this->assertInstanceOf(JsMinify::class, $this->service->getJsMinify());
        $this->assertInstanceOf(CssMinify::class, $this->service->getCssMinify());
    }
}

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

    public function test_master_switch_disables_minification(): void
    {
        config(['minifier.enabled' => false]);

        $this->assertFalse($this->service->isEnabled());
        $this->assertFalse($this->service->isJsEnabled());
        $this->assertFalse($this->service->isCssEnabled());

        $js = "function a() {\n    return 1;\n}";
        $css = ".a {\n    color: red;\n}";
        // Disabled ⇒ returns the input untouched.
        $this->assertSame($js, $this->service->minifyJs($js));
        $this->assertSame($css, $this->service->minifyCss($css));
    }

    public function test_per_type_switch_is_independent(): void
    {
        config([
            'minifier.enabled' => true,
            'minifier.minify_css' => false,
            'minifier.minify_js' => true,
        ]);

        $this->assertTrue($this->service->isEnabled());
        $this->assertFalse($this->service->isCssEnabled());
        $this->assertTrue($this->service->isJsEnabled());
    }

    public function test_option_to_bool_handles_off_sentinel(): void
    {
        // The Microweber option store drops '0'/'' (get_option returns null), so a
        // false toggle is persisted as the 'off' sentinel. Parsing must round-trip.
        $page = \MicroweberPackages\Minifier\Filament\Pages\MinifierSettings::class;

        $this->assertTrue($page::optionToBool('1'));
        $this->assertTrue($page::optionToBool('on'));
        $this->assertTrue($page::optionToBool(true));
        $this->assertFalse($page::optionToBool('off'));
        $this->assertFalse($page::optionToBool('0'));
        $this->assertFalse($page::optionToBool(''));
        $this->assertFalse($page::optionToBool(false));
        $this->assertNull($page::optionToBool('maybe'));
    }
}

<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Tests\Feature;

use MicroweberPackages\Minifier\Services\CssMinify;
use MicroweberPackages\Minifier\Services\JsMinify;
use MicroweberPackages\Minifier\Services\MinifierService;
use MicroweberPackages\Minifier\Tests\TestCase;

/**
 * Integration: container bindings, config, and helpers all work together.
 */
class StandaloneIntegrationTest extends TestCase
{
    public function test_singletons_bound(): void
    {
        $a = app(JsMinify::class);
        $b = app(JsMinify::class);
        $this->assertSame($a, $b);

        $c = app(CssMinify::class);
        $d = app(CssMinify::class);
        $this->assertSame($c, $d);

        $e = app(MinifierService::class);
        $f = app('minifier');
        $this->assertSame($e, $f);
    }

    public function test_config_is_merged(): void
    {
        $this->assertNotNull(config('minifier'));
        $this->assertIsBool(config('minifier.enabled'));
        $this->assertIsArray(config('minifier.js'));
        $this->assertIsArray(config('minifier.css'));
    }

    public function test_disable_via_config(): void
    {
        config(['minifier.enabled' => false]);
        app()->forgetInstance(MinifierService::class);

        $service = app(MinifierService::class);
        $original = "function a() { return 1; }";
        // When disabled, minifyJs returns original
        $this->assertSame($original, $service->minifyJs($original));
    }

    public function test_helpers_use_container(): void
    {
        $viaHelper = js_minify('var n=9;');
        $viaService = app(JsMinify::class)->minify('var n=9;');
        $this->assertSame($viaHelper, $viaService);
    }
}

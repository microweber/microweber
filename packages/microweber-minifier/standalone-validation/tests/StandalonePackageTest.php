<?php

declare(strict_types=1);

namespace MinifierStandalone\Tests;

use MicroweberPackages\Minifier\MinifierServiceProvider;
use MicroweberPackages\Minifier\Services\CssMinify;
use MicroweberPackages\Minifier\Services\JsMinify;
use MicroweberPackages\Minifier\Services\MinifierService;
use Orchestra\Testbench\TestCase;

/**
 * Boots a bare Laravel app with only the minifier package
 * to prove the package works outside the Microweber CMS.
 */
class StandalonePackageTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            MinifierServiceProvider::class,
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
    }

    public function test_service_resolves(): void
    {
        $service = $this->app->make(MinifierService::class);
        $this->assertInstanceOf(MinifierService::class, $service);
    }

    public function test_helpers_work(): void
    {
        $this->assertTrue(function_exists('js_minify'));
        $this->assertTrue(function_exists('css_minify'));
        $js = js_minify("function a(){ return 1; }");
        $css = css_minify(".a { color: red; }");
        $this->assertStringContainsString('function', $js);
        $this->assertStringContainsString('color:red', $css);
    }

    public function test_routes_registered(): void
    {
        $this->assertTrue($this->app->make('router')->has('minifier.stats'));
        $response = $this->get('/minifier/stats');
        $response->assertOk();
        $response->assertJsonStructure(['enabled', 'minify_js', 'minify_css']);
    }

    public function test_js_minification_standalone(): void
    {
        /** @var JsMinify $js */
        $js = $this->app->make(JsMinify::class);
        $result = $js->minify("// comment\nfunction hi(){ return 'x'; }");
        $this->assertStringNotContainsString('// comment', $result);
        $this->assertStringContainsString('function', $result);
    }

    public function test_css_minification_standalone(): void
    {
        /** @var CssMinify $css */
        $css = $this->app->make(CssMinify::class);
        $result = $css->minify("/* c */\n.b { margin: 0px; }");
        $this->assertStringNotContainsString('/* c */', $result);
        $this->assertStringContainsString('.b', $result);
    }

    public function test_self_test_endpoint(): void
    {
        $response = $this->get('/minifier/self-test');
        $response->assertOk();
        $response->assertJsonPath('js.ok', true);
        $response->assertJsonPath('css.ok', true);
    }

    public function test_post_minify_js(): void
    {
        $response = $this->postJson('/minifier/js', ['code' => 'var x = 1; // c']);
        $response->assertOk();
        $response->assertJsonPath('success', true);
    }
}

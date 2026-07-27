<?php

declare(strict_types=1);

namespace UrlStandalone\Tests;

use MicroweberPackages\Url\Providers\UrlServiceProvider;
use MicroweberPackages\Url\URLify;
use MicroweberPackages\Url\UrlManager;
use MicroweberPackages\Url\UrlSecurity;
use Orchestra\Testbench\TestCase;

/**
 * Boots a bare Laravel app with only the url package (and helper
 * functions dependency) to prove it works outside Microweber CMS.
 */
class StandalonePackageTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            UrlServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        $app['config']->set('app.url', 'http://example.test');
    }

    public function test_url_manager_resolves(): void
    {
        $manager = $this->app->make('url_manager');
        $this->assertInstanceOf(UrlManager::class, $manager);
        $this->assertInstanceOf(UrlManager::class, $this->app->make(UrlManager::class));
    }

    public function test_slug_generation(): void
    {
        $manager = $this->app->make(UrlManager::class);
        $this->assertEquals('Hello-World-Test-Page', $manager->slug('Hello World: Test Page'));
    }

    public function test_urlify_filter(): void
    {
        $this->assertEquals('jetudie-le-francais', URLify::filter("J'étudie le français"));
    }

    public function test_url_security_helpers(): void
    {
        $this->assertTrue(UrlSecurity::isSafeRemoteUrl('https://cdn.example.com/a.jpg'));
        $this->assertTrue(UrlSecurity::isSafeRemoteUrl('//cdn.example.com/a.jpg'));
        $this->assertFalse(UrlSecurity::isSafeRemoteUrl('javascript:alert(1)'));
        $this->assertTrue(function_exists('mw_is_safe_remote_url'));
        $this->assertTrue(function_exists('safe_css_url'));
        $this->assertSame('', safe_css_url('javascript:alert(1)'));
    }

    public function test_set_site_url_var(): void
    {
        $manager = $this->app->make(UrlManager::class);
        $manager->set('http://example.test/');
        $this->assertEquals('http://example.test/', $manager->site_url_var);
        $this->assertEquals('example.test', $manager->hostname());
    }
}

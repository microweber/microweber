<?php

declare(strict_types=1);

namespace SecurityStandalone\Tests;

use MicroweberPackages\Security\HtmlClean;
use MicroweberPackages\Security\SecurityServiceProvider;
use MicroweberPackages\Security\StoredXssStripper;
use MicroweberPackages\Security\XSSClean;
use MicroweberPackages\Security\XSSSecurity;
use Orchestra\Testbench\TestCase;

/**
 * Boots a bare Laravel app with only the security package to prove
 * it works outside the Microweber CMS.
 */
class StandalonePackageTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            SecurityServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
    }

    public function test_bindings_resolve(): void
    {
        $this->assertInstanceOf(HtmlClean::class, app('html_clean'));
        $this->assertInstanceOf(HtmlClean::class, app(HtmlClean::class));
        $this->assertInstanceOf(XSSClean::class, app('mw-xss-clean'));
        $this->assertInstanceOf(XSSSecurity::class, app('xss_security'));
    }

    public function test_xss_clean_strips_script(): void
    {
        $cleaner = app(XSSClean::class);
        $result = $cleaner->clean("<script>alert('xss')</script><b>ok</b>");
        $this->assertStringNotContainsString('<script>', $result);
    }

    public function test_html_clean_admin_mode(): void
    {
        $cleaner = app(HtmlClean::class);
        $xss = "<script>alert('xss')</script><module type=\"test\"></module>";
        $this->assertStringNotContainsString('<script>', $cleaner->clean($xss, []));
        $this->assertStringContainsString('module', $cleaner->clean($xss, ['admin_mode' => true]));
    }

    public function test_stored_xss_stripper(): void
    {
        $this->assertSame('<img src=x>', StoredXssStripper::strip('<img src=x onerror=alert(1)>'));
    }

    public function test_xss_security_clean(): void
    {
        $sec = app(XSSSecurity::class);
        $result = $sec->clean("<script>alert(1)</script>");
        $this->assertIsString($result);
        $this->assertStringNotContainsString('<script>', strtolower($result));
    }
}

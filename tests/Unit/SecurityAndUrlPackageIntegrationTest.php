<?php

declare(strict_types=1);

namespace Tests\Unit;

use MicroweberPackages\Format\Facades\Format;
use MicroweberPackages\Format\FormatService;
use MicroweberPackages\Security\Facades\HtmlClean as HtmlCleanFacade;
use MicroweberPackages\Security\Facades\XSSSecurity as XSSSecurityFacade;
use MicroweberPackages\Security\HtmlClean;
use MicroweberPackages\Security\XSSClean;
use MicroweberPackages\Security\XSSSecurity;
use MicroweberPackages\Url\Facades\UrlManager as MwUrl;
use MicroweberPackages\Url\UrlManagerService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Pins CMS wiring after the Helper package extraction:
 * security + url services resolve from packages/* providers via facades.
 */
class SecurityAndUrlPackageIntegrationTest extends TestCase
{
    #[Test]
    public function format_binding_resolves(): void
    {
        $this->assertInstanceOf(FormatService::class, Format::getFacadeRoot());
    }

    #[Test]
    public function xss_security_binding_resolves(): void
    {
        $this->assertInstanceOf(XSSSecurity::class, XSSSecurityFacade::getFacadeRoot());
    }

    #[Test]
    public function html_clean_binding_resolves(): void
    {
        $this->assertInstanceOf(HtmlClean::class, HtmlCleanFacade::getFacadeRoot());
        $this->assertInstanceOf(HtmlClean::class, app(HtmlClean::class));
    }

    #[Test]
    public function url_manager_binding_resolves(): void
    {
        $this->assertInstanceOf(UrlManagerService::class, MwUrl::getFacadeRoot());
        $this->assertSame(app(UrlManagerService::class), MwUrl::getFacadeRoot());
    }

    #[Test]
    public function xss_clean_helper_function_uses_security_package(): void
    {
        $this->assertTrue(function_exists('xss_clean'));
        $cleaned = xss_clean("<script>alert('x')</script>");
        $this->assertStringNotContainsString('<script>', $cleaned);
    }

    #[Test]
    public function url_security_helpers_are_available(): void
    {
        $this->assertTrue(function_exists('mw_is_safe_remote_url'));
        $this->assertTrue(function_exists('safe_css_url'));
        $this->assertTrue(mw_is_safe_remote_url('https://example.com/a.jpg'));
        $this->assertFalse(mw_is_safe_remote_url('javascript:alert(1)'));
        $this->assertSame('', safe_css_url('javascript:alert(1)'));
    }

    #[Test]
    public function xss_clean_class_is_from_security_package(): void
    {
        $cleaner = new XSSClean();
        $this->assertSame(
            \MicroweberPackages\Security\XSSClean::class,
            $cleaner::class
        );
    }
}

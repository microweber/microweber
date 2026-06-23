<?php

namespace MicroweberPackages\Url\Tests;

use MicroweberPackages\Url\UrlSecurity;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for UrlSecurity - pure PHP, no Laravel needed.
 */
class UrlSecurityTest extends TestCase
{
    // ---- isSafeRemoteUrl ----

    #[Test]
    public function it_accepts_http_url(): void
    {
        $this->assertTrue(UrlSecurity::isSafeRemoteUrl('http://example.com/image.jpg'));
    }

    #[Test]
    public function it_accepts_https_url(): void
    {
        $this->assertTrue(UrlSecurity::isSafeRemoteUrl('https://example.com/image.jpg'));
    }

    #[Test]
    public function it_accepts_protocol_relative_url(): void
    {
        $this->assertTrue(UrlSecurity::isSafeRemoteUrl('//cdn.example.com/image.jpg'));
    }

    #[Test]
    public function it_rejects_javascript_url(): void
    {
        $this->assertFalse(UrlSecurity::isSafeRemoteUrl('javascript:alert(1)'));
    }

    #[Test]
    public function it_rejects_data_url(): void
    {
        $this->assertFalse(UrlSecurity::isSafeRemoteUrl('data:text/html,<h1>hi</h1>'));
    }

    #[Test]
    public function it_rejects_file_url(): void
    {
        $this->assertFalse(UrlSecurity::isSafeRemoteUrl('file:///etc/passwd'));
    }

    #[Test]
    public function it_rejects_empty_string(): void
    {
        $this->assertFalse(UrlSecurity::isSafeRemoteUrl(''));
    }

    #[Test]
    public function it_rejects_non_string(): void
    {
        $this->assertFalse(UrlSecurity::isSafeRemoteUrl(null));
        $this->assertFalse(UrlSecurity::isSafeRemoteUrl(123));
        $this->assertFalse(UrlSecurity::isSafeRemoteUrl([]));
    }

    #[Test]
    public function it_rejects_relative_path(): void
    {
        $this->assertFalse(UrlSecurity::isSafeRemoteUrl('/images/photo.jpg'));
    }

    #[Test]
    public function it_rejects_vbscript_url(): void
    {
        $this->assertFalse(UrlSecurity::isSafeRemoteUrl('vbscript:msgbox'));
    }

    // ---- safeCssUrl ----

    #[Test]
    public function it_returns_empty_for_null_css_url(): void
    {
        $this->assertEquals('', UrlSecurity::safeCssUrl(null));
    }

    #[Test]
    public function it_returns_empty_for_empty_css_url(): void
    {
        $this->assertEquals('', UrlSecurity::safeCssUrl(''));
    }

    #[Test]
    public function it_rejects_javascript_in_css_url(): void
    {
        $this->assertEquals('', UrlSecurity::safeCssUrl('javascript:alert(1)'));
    }

    #[Test]
    public function it_rejects_data_in_css_url(): void
    {
        $this->assertEquals('', UrlSecurity::safeCssUrl('data:text/html,test'));
    }

    #[Test]
    public function it_escapes_special_chars_in_css_url(): void
    {
        $result = UrlSecurity::safeCssUrl("http://example.com/image's.jpg");
        // The apostrophe should be backslash-escaped
        $this->assertStringContainsString("\\'", $result);
        $this->assertStringContainsString('example.com', $result);
    }

    #[Test]
    public function it_passes_normal_css_url(): void
    {
        $result = UrlSecurity::safeCssUrl('http://example.com/image.jpg');
        $this->assertStringContainsString('http://example.com/image.jpg', $result);
    }
}
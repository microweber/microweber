<?php

namespace MicroweberPackages\Url\Tests;

use MicroweberPackages\Url\UrlManagerService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for UrlManagerService - pure PHP tests, no Laravel container required.
 *
 * Tests that call site_url() internally are skipped in standalone mode
 * because site_url() depends on config() which needs the Laravel container.
 * Those are covered by the integrated test (UrlManagerIntegratedTest).
 */
class UrlManagerTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated(): void
    {
        $manager = new UrlManagerService();
        $this->assertInstanceOf(UrlManagerService::class, $manager);
    }

    #[Test]
    public function it_generates_slug(): void
    {
        $manager = new UrlManagerService();
        $slug = $manager->slug('Hello World: Test Page');
        $this->assertEquals('Hello-World-Test-Page', $slug);
    }

    #[Test]
    public function it_generates_slug_with_special_chars(): void
    {
        $manager = new UrlManagerService();
        $slug = $manager->slug('Test &quot;Page&quot; with &#039;Quotes&#039;');
        $this->assertStringNotContainsString('&quot;', $slug);
        $this->assertStringNotContainsString('&#039;', $slug);
    }

    #[Test]
    public function it_generates_slug_with_unicode(): void
    {
        $manager = new UrlManagerService();
        $slug = $manager->slug('Héllo Wörld');
        $this->assertNotEmpty($slug);
    }

    #[Test]
    public function it_sets_and_gets_site_url_var(): void
    {
        $manager = new UrlManagerService();
        $manager->set('http://example.com/');
        $this->assertEquals('http://example.com/', $manager->site_url_var);
    }

    #[Test]
    public function it_sets_and_gets_current_url_var(): void
    {
        $manager = new UrlManagerService();
        $manager->set_current('http://example.com/page');
        $this->assertEquals('http://example.com/page', $manager->current_url_var);
    }

    #[Test]
    public function it_detects_ajax_request(): void
    {
        $manager = new UrlManagerService();
        $this->assertFalse($manager->is_ajax());
    }

    #[Test]
    public function it_cleans_url_wrappers(): void
    {
        $manager = new UrlManagerService();

        $cleaned = $manager->clean_url_wrappers('file:///etc/passwd');
        $this->assertEquals('///etc/passwd', $cleaned);

        $cleaned = $manager->clean_url_wrappers('php://filter/read=convert.base64-encode');
        $this->assertEquals('//filter/read=convert.base64-encode', $cleaned);

        $cleaned = $manager->clean_url_wrappers('phar://malicious.phar');
        $this->assertEquals('//malicious.phar', $cleaned);

        $cleaned = $manager->clean_url_wrappers('http://example.com');
        $this->assertEquals('http://example.com', $cleaned);

        $cleaned = $manager->clean_url_wrappers('https://example.com');
        $this->assertEquals('https://example.com', $cleaned);
    }

    #[Test]
    public function it_returns_false_for_empty_to_path(): void
    {
        $manager = new UrlManagerService();
        $this->assertFalse($manager->to_path(''));
        $this->assertFalse($manager->to_path(123));
    }

    #[Test]
    public function it_returns_strleft(): void
    {
        $manager = new UrlManagerService();
        $result = $manager->strleft('HTTP/1.1', '/');
        $this->assertEquals('HTTP', $result);
    }

    #[Test]
    public function it_reduces_double_slashes(): void
    {
        $manager = new UrlManagerService();
        $result = $manager->reduceDoubleSlashes('http://example.com//path//to//page');
        $this->assertEquals('http://example.com/path/to/page', $result);
    }

    #[Test]
    public function it_preserves_protocol_double_slash(): void
    {
        $manager = new UrlManagerService();
        $result = $manager->reduceDoubleSlashes('https://example.com/path');
        $this->assertEquals('https://example.com/path', $result);
    }

    #[Test]
    public function it_returns_false_for_empty_redirect(): void
    {
        $manager = new UrlManagerService();
        $this->assertFalse($manager->redirect(''));
    }

    #[Test]
    public function it_returns_null_for_replace_site_url_back_with_false(): void
    {
        $manager = new UrlManagerService();
        $this->assertNull($manager->replace_site_url_back(false));
    }
}
<?php

namespace MicroweberPackages\Url\Tests;

use MicroweberPackages\Url\UrlManagerService;
use PHPUnit\Framework\Attributes\Test;
use MicroweberPackages\Url\Facades\UrlManager;

/**
 * Integrated tests for UrlManagerService - requires the full Microweber system.
 */
class UrlManagerIntegratedTest extends TestCase
{
    #[Test]
    public function it_resolves_from_container(): void
    {
        $manager = UrlManager::getFacadeRoot();
        $this->assertInstanceOf(UrlManagerService::class, $manager);
    }

    #[Test]
    public function it_returns_site_url(): void
    {
        $manager = UrlManager::getFacadeRoot();
        $url = $manager->site_url();
        $this->assertNotEmpty($url);
    }

    #[Test]
    public function it_returns_site_url_with_path(): void
    {
        $manager = UrlManager::getFacadeRoot();
        $url = $manager->site_url('test/page');
        $this->assertStringContainsString('test/page', $url);
    }

    #[Test]
    public function it_returns_site_via_alias(): void
    {
        $manager = UrlManager::getFacadeRoot();
        $url = $manager->site('test');
        $this->assertStringContainsString('test', $url);
    }

    #[Test]
    public function it_returns_hostname(): void
    {
        $manager = UrlManager::getFacadeRoot();
        $hostname = $manager->hostname();
        $this->assertNotEmpty($hostname);
        $this->assertIsString($hostname);
    }

    #[Test]
    public function it_replaces_site_url_in_string(): void
    {
        $manager = UrlManager::getFacadeRoot();
        $siteUrl = $manager->site_url();
        $result = $manager->replace_site_url($siteUrl . 'some/path');
        $this->assertStringContainsString('{SITE_URL}', $result);
    }

    #[Test]
    public function it_replaces_site_url_back_in_string(): void
    {
        $manager = UrlManager::getFacadeRoot();
        $siteUrl = $manager->site_url();
        $result = $manager->replace_site_url_back('{SITE_URL}some/path');
        $this->assertStringContainsString($siteUrl, $result);
        $this->assertStringNotContainsString('{SITE_URL}', $result);
    }

    #[Test]
    public function it_replace_roundtrips(): void
    {
        $manager = UrlManager::getFacadeRoot();
        $siteUrl = $manager->site_url();
        $original = $siteUrl . 'test/path/to/page';
        $replaced = $manager->replace_site_url($original);
        $restored = $manager->replace_site_url_back($replaced);
        $this->assertEquals($original, $restored);
    }

    #[Test]
    public function it_returns_segments(): void
    {
        $manager = UrlManager::getFacadeRoot();
        $siteUrl = $manager->site_url();
        $segments = $manager->segments($siteUrl . 'page/sub');
        $this->assertIsArray($segments);
    }

    #[Test]
    public function it_returns_api_link(): void
    {
        $manager = UrlManager::getFacadeRoot();
        $link = $manager->api_link('content/get');
        $this->assertStringContainsString('api', $link);
    }

    #[Test]
    public function it_generates_slug(): void
    {
        $manager = UrlManager::getFacadeRoot();
        $slug = $manager->slug('Test Page: Hello World');
        $this->assertEquals('Test-Page-Hello-World', $slug);
    }

    #[Test]
    public function it_sets_and_unsets_params(): void
    {
        $manager = UrlManager::getFacadeRoot();
        $manager->set_current($manager->site_url() . 'page:1/filter:active');
        $result = $manager->param_set('page', '2');
        $this->assertStringContainsString('page:2', $result);

        $result = $manager->param_unset('filter');
        $this->assertStringNotContainsString('filter', $result);
    }

    #[Test]
    public function package_url_manager_class_is_bound(): void
    {
        $manager = new UrlManagerService();
        $this->assertInstanceOf(UrlManagerService::class, $manager);
        $this->assertInstanceOf(UrlManagerService::class, UrlManager::getFacadeRoot());
        $this->assertSame(app(UrlManagerService::class), UrlManager::getFacadeRoot());
    }

    #[Test]
    public function package_urlify_filter_works(): void
    {
        $result = \MicroweberPackages\Url\URLify::filter('Hello World');
        $this->assertEquals('hello-world', $result);
    }

    #[Test]
    public function helper_function_mw_is_safe_remote_url_works(): void
    {
        $this->assertTrue(mw_is_safe_remote_url('https://example.com/image.jpg'));
        $this->assertFalse(mw_is_safe_remote_url('javascript:alert(1)'));
    }

    #[Test]
    public function helper_function_safe_css_url_works(): void
    {
        $this->assertNotEmpty(safe_css_url('https://example.com/image.jpg'));
        $this->assertEquals('', safe_css_url('javascript:alert(1)'));
    }
}
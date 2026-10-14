<?php

declare(strict_types=1);

namespace MicroweberPackages\Multisite\Tests;

use MicroweberPackages\Multisite\MultisiteManager;
use PHPUnit\Framework\TestCase;

/**
 * Pure unit tests for the multisite resolution + scoping contract. No Laravel
 * boot required — these exercise the host→site detector, config scanning and
 * the per-site path/prefix/cookie builders against a throwaway config dir.
 */
class MultisiteManagerTest extends TestCase
{
    private string $configDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->configDir = sys_get_temp_dir() . '/mw_multisite_' . uniqid();
        mkdir($this->configDir, 0775, true);
        $this->makeSite('domain-a.com');
        $this->makeSite('domain-b.com');
        // A directory WITHOUT the marker must not count as a site.
        mkdir($this->configDir . '/not-a-site.com', 0775, true);
    }

    protected function tearDown(): void
    {
        $this->rrmdir($this->configDir);
        parent::tearDown();
    }

    private function makeSite(string $domain): void
    {
        mkdir($this->configDir . '/' . $domain, 0775, true);
        file_put_contents($this->configDir . '/' . $domain . '/microweber.php', "<?php return [];\n");
    }

    private function manager(): MultisiteManager
    {
        return new MultisiteManager(['config_path' => $this->configDir]);
    }

    private function rrmdir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->rrmdir($path) : unlink($path);
        }
        rmdir($dir);
    }

    public function test_detects_configured_domain(): void
    {
        $this->assertSame('domain-a.com', MultisiteManager::detectSiteFromHost('domain-a.com', $this->configDir));
    }

    public function test_strips_www_and_port(): void
    {
        $this->assertSame('domain-a.com', MultisiteManager::detectSiteFromHost('www.domain-a.com:8000', $this->configDir));
        $this->assertSame('domain-b.com', MultisiteManager::detectSiteFromHost('WWW.Domain-B.com', $this->configDir));
    }

    public function test_unconfigured_domain_returns_null(): void
    {
        $this->assertNull(MultisiteManager::detectSiteFromHost('other.com', $this->configDir));
        $this->assertNull(MultisiteManager::detectSiteFromHost('not-a-site.com', $this->configDir));
    }

    public function test_null_and_empty_host_returns_null(): void
    {
        $this->assertNull(MultisiteManager::detectSiteFromHost(null, $this->configDir));
        $this->assertNull(MultisiteManager::detectSiteFromHost('', $this->configDir));
    }

    public function test_rejects_path_traversal_in_host(): void
    {
        $this->assertNull(MultisiteManager::normalizeHost('../../etc'));
        $this->assertNull(MultisiteManager::normalizeHost('a/b'));
        $this->assertNull(MultisiteManager::detectSiteFromHost('../domain-a.com', $this->configDir));
    }

    public function test_sites_lists_only_marked_directories(): void
    {
        $this->assertSame(['domain-a.com', 'domain-b.com'], $this->manager()->sites());
    }

    public function test_setSite_and_current(): void
    {
        $m = $this->manager()->setSite('domain-a.com');
        $this->assertSame('domain-a.com', $m->current());
        $this->assertTrue($m->isActive());
    }

    public function test_default_site_is_null(): void
    {
        $m = $this->manager()->setSite(null);
        $this->assertNull($m->current());
        $this->assertFalse($m->isActive());
    }

    public function test_userfiles_folder_is_per_site(): void
    {
        $m = $this->manager();
        $this->assertSame('userfiles/domain-a.com', $m->userfilesFolder('domain-a.com'));
        $this->assertSame('userfiles', $m->userfilesFolder(null));
    }

    public function test_cache_prefix_and_session_cookie_are_per_site(): void
    {
        $m = $this->manager();
        $this->assertSame('mw_domain_a_com_cache', $m->cachePrefix('domain-a.com'));
        $this->assertSame('mw_domain_b_com_session', $m->sessionCookie('domain-b.com'));
        // Two different sites never collide.
        $this->assertNotSame($m->cachePrefix('domain-a.com'), $m->cachePrefix('domain-b.com'));
    }

    public function test_disabled_manager_resolves_no_site(): void
    {
        $m = new MultisiteManager(['config_path' => $this->configDir, 'enabled' => false]);
        $this->assertNull($m->current());
        $this->assertFalse($m->isActive());
    }
}

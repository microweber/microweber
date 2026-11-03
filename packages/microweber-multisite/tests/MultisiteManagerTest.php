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

    // ---- env_path (per-domain env folder) ----------------------------------

    public function test_resolve_env_dir_null_means_root(): void
    {
        $this->assertNull(MultisiteManager::resolveEnvDir('/var/www', null));
        $this->assertNull(MultisiteManager::resolveEnvDir('/var/www', ''));
    }

    public function test_resolve_env_dir_relative_is_joined_to_base(): void
    {
        $this->assertSame('/var/www/envs', MultisiteManager::resolveEnvDir('/var/www', 'envs'));
        // Base trailing slash and a relative value's surrounding slashes normalise.
        $this->assertSame('/var/www/envs', MultisiteManager::resolveEnvDir('/var/www/', 'envs/'));
    }

    public function test_resolve_env_dir_absolute_is_used_as_is(): void
    {
        $this->assertSame('/etc/mw/envs', MultisiteManager::resolveEnvDir('/var/www', '/etc/mw/envs'));
    }

    public function test_site_env_file_defaults_to_base_root(): void
    {
        $m = new MultisiteManager(['config_path' => $this->configDir, 'env_path' => null]);
        // No base_path() in a bare unit test → manager falls back to discovered
        // base; assert only the filename + that no custom folder is injected.
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR . '.env.domain-a.com', $m->siteEnvFile('domain-a.com'));
        $this->assertNull($m->envDir());
    }

    public function test_site_env_file_honours_absolute_env_path(): void
    {
        $m = new MultisiteManager(['config_path' => $this->configDir, 'env_path' => '/etc/mw/envs']);
        $this->assertSame('/etc/mw/envs', $m->envDir());
        $this->assertSame('/etc/mw/envs' . DIRECTORY_SEPARATOR . '.env.domain-a.com', $m->siteEnvFile('domain-a.com'));
    }

    public function test_load_site_env_file_from_folder_sets_vars(): void
    {
        $dir = $this->configDir . '/envs';
        mkdir($dir, 0775, true);
        $key = 'MW_MULTISITE_ENV_TEST_' . strtoupper(substr(md5((string) mt_rand()), 0, 8));
        file_put_contents($dir . '/.env.domain-a.com', $key . "=from_folder\n");

        $this->assertTrue(MultisiteManager::loadSiteEnvFile($dir, 'domain-a.com'));
        $this->assertSame('from_folder', $_SERVER[$key] ?? getenv($key));

        // Missing file is a graceful no-op, not an error.
        $this->assertFalse(MultisiteManager::loadSiteEnvFile($dir, 'domain-b.com'));
    }

    /**
     * The whole point of folder mode: our autoload-time load of the relocated
     * per-site file must WIN over the base .env that Laravel loads afterwards,
     * while base still fills the keys the partial per-site file omits. Simulated
     * here by loading base through a separate immutable repository, exactly as
     * the framework's LoadEnvironmentVariables does.
     */
    public function test_folder_env_overlays_base_env_per_site_wins(): void
    {
        $dir = $this->configDir . '/envs';
        mkdir($dir, 0775, true);
        $sfx = strtoupper(substr(md5((string) mt_rand()), 0, 8));
        $siteDb = 'MWX_DB_' . $sfx;     // defined in both → per-site must win
        $siteOnly = 'MWX_PW_' . $sfx;   // per-site only
        $baseOnly = 'MWX_KEY_' . $sfx;  // base only → inherited

        file_put_contents($dir . '/.env.domain-a.com', "{$siteDb}=mw_site_a\n{$siteOnly}=secret_a\n");
        file_put_contents($this->configDir . '/.env', "{$siteDb}=shared_default\n{$baseOnly}=shared_key\n");

        // 1) autoload-time per-site load (our code)
        $this->assertTrue(MultisiteManager::loadSiteEnvFile($dir, 'domain-a.com'));

        // 2) Laravel afterwards loads base .env through ITS OWN repository
        \Dotenv\Dotenv::create(
            \Dotenv\Repository\RepositoryBuilder::createWithDefaultAdapters()
                ->addAdapter(\Dotenv\Repository\Adapter\PutenvAdapter::class)
                ->immutable()->make(),
            $this->configDir,
            '.env'
        )->safeLoad();

        $this->assertSame('mw_site_a', $_SERVER[$siteDb], 'per-site value must win over base');
        $this->assertSame('secret_a', $_SERVER[$siteOnly], 'per-site-only key must be set');
        $this->assertSame('shared_key', $_SERVER[$baseOnly], 'base key must fill the gap');
    }
}

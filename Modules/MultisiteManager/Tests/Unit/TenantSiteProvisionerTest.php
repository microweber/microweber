<?php

declare(strict_types=1);

namespace Modules\MultisiteManager\Tests\Unit;

use MicroweberPackages\EnvWriter\EnvWriterService;
use MicroweberPackages\Multisite\MultisiteManager;
use Modules\MultisiteManager\Services\TenantSiteProvisioner;
use Tests\TestCase;

/**
 * Exercises the provisioner's filesystem contract (marker + .env write, read
 * back, delete) against a throwaway temp config/env tree — no database and no
 * installer process, so it is safe and fast.
 */
class TenantSiteProvisionerTest extends TestCase
{
    private string $tmp;

    private string $domain = 'unit-tenant.example';

    protected function setUp(): void
    {
        parent::setUp();
        $this->tmp = sys_get_temp_dir() . '/mw-tenant-' . uniqid();
        @mkdir($this->tmp . '/config', 0775, true);
    }

    protected function tearDown(): void
    {
        $this->rrmdir($this->tmp);
        // Clean the per-site storage trees ensureDirectories() may have created.
        foreach ([
            storage_path('framework/cache/sites/' . $this->domain),
            storage_path('framework/sessions/sites/' . $this->domain),
        ] as $d) {
            $this->rrmdir($d);
        }
        parent::tearDown();
    }

    private function provisioner(): TenantSiteProvisioner
    {
        $manager = new MultisiteManager([
            'config_path' => $this->tmp . '/config',
            'env_path' => $this->tmp,
        ]);

        return new TenantSiteProvisioner($manager, app(EnvWriterService::class));
    }

    public function test_create_writes_marker_and_env_then_describe_reads_back(): void
    {
        $p = $this->provisioner();

        $res = $p->create([
            'domain' => $this->domain,
            'app_url' => 'https://' . $this->domain,
            'db_database' => 'mw_unit_tenant',
            'db_username' => 'root',
            'db_password' => 'secret',
            'create_database' => false,
            'run_install' => false,
        ]);

        $this->assertTrue($res['ok'], implode(' | ', $res['messages']));
        $this->assertFileExists($this->tmp . '/config/' . $this->domain . '/microweber.php');
        $this->assertFileExists($this->tmp . '/.env.' . $this->domain);

        $this->assertTrue($p->exists($this->domain));

        $info = $p->describe($this->domain);
        $this->assertFalse($info['is_main']);
        $this->assertSame('mw_unit_tenant', $info['db_database']);
        $this->assertSame('https://' . $this->domain, $info['app_url']);
        $this->assertSame('root', $info['db_username']);
    }

    public function test_create_rejects_invalid_domain(): void
    {
        $res = $this->provisioner()->create(['domain' => '../evil', 'create_database' => false, 'run_install' => false]);
        $this->assertFalse($res['ok']);
    }

    public function test_create_refuses_duplicate_without_force(): void
    {
        $p = $this->provisioner();
        $base = ['domain' => $this->domain, 'create_database' => false, 'run_install' => false];
        $this->assertTrue($p->create($base)['ok']);
        $this->assertFalse($p->create($base)['ok']);
    }

    public function test_update_rewrites_env(): void
    {
        $p = $this->provisioner();
        $p->create(['domain' => $this->domain, 'app_url' => 'https://old', 'create_database' => false, 'run_install' => false]);

        $res = $p->update($this->domain, ['app_url' => 'https://new', 'db_database' => 'mw_changed']);
        $this->assertTrue($res['ok']);

        $info = $p->describe($this->domain);
        $this->assertSame('https://new', $info['app_url']);
        $this->assertSame('mw_changed', $info['db_database']);
    }

    public function test_delete_removes_marker_and_env(): void
    {
        $p = $this->provisioner();
        $p->create(['domain' => $this->domain, 'create_database' => false, 'run_install' => false]);
        $this->assertTrue($p->exists($this->domain));

        $res = $p->delete($this->domain);
        $this->assertTrue($res['ok']);
        $this->assertFalse($p->exists($this->domain));
    }

    public function test_create_database_refuses_unsafe_name(): void
    {
        $res = $this->provisioner()->createDatabase(['name' => 'bad;name', 'host' => '127.0.0.1']);
        $this->assertFalse($res['ok']);
        $this->assertStringContainsString('unsafe', $res['message']);
    }

    private function rrmdir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) ?: [] as $e) {
            if ($e === '.' || $e === '..') {
                continue;
            }
            $path = $dir . '/' . $e;
            is_dir($path) ? $this->rrmdir($path) : @unlink($path);
        }
        @rmdir($dir);
    }
}

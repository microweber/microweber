<?php

declare(strict_types=1);

namespace Modules\MultisiteManager\Tests\Feature;

use Modules\MultisiteManager\Filament\Admin\Pages\AdminTenantSitesPage;
use Modules\MultisiteManager\Services\TenantSiteProvisioner;
use Modules\MultisiteManager\Support\TenantManagement;
use Tests\TestCase;

/**
 * Contract for the tenant-management module: the env flag gates the whole
 * surface, the provisioner is bound, the main site is identified, and the admin
 * page declares its actions.
 */
class MultisiteManagerModuleContractTest extends TestCase
{
    public function test_module_is_registered_active(): void
    {
        $statuses = json_decode((string) file_get_contents(base_path('storage/modules_statuses.json')), true) ?: [];
        $this->assertArrayHasKey('MultisiteManager', $statuses);
        $this->assertTrue($statuses['MultisiteManager']);
    }

    public function test_flag_gate_reflects_env(): void
    {
        $this->withFlag(null, function () {
            $this->assertFalse(TenantManagement::enabled());
            $this->assertFalse(AdminTenantSitesPage::canAccess());
        });

        $this->withFlag('true', function () {
            $this->assertTrue(TenantManagement::enabled());
        });

        // The correctly-spelled alias is honoured too.
        $this->withFlag(null, function () {
            putenv(TenantManagement::FLAG_ALIAS . '=1');
            $_ENV[TenantManagement::FLAG_ALIAS] = '1';
            $this->assertTrue(TenantManagement::enabled());
            putenv(TenantManagement::FLAG_ALIAS);
            unset($_ENV[TenantManagement::FLAG_ALIAS]);
        });
    }

    public function test_management_is_blocked_inside_a_subsite_even_with_the_flag_on(): void
    {
        // The base-.env flag overlays onto a subsite's env, so the flag alone is
        // not enough — being inside a subsite must hard-block the surface.
        $this->withFlag('true', function () {
            $prev = $_SERVER['MW_MULTISITE_SITE'] ?? null;
            $_SERVER['MW_MULTISITE_SITE'] = 'sub.example.test';
            try {
                $this->assertTrue(TenantManagement::enabled());
                $this->assertFalse(TenantManagement::onMainSite());
                $this->assertFalse(TenantManagement::availableHere());
                $this->assertFalse(AdminTenantSitesPage::canAccess());
            } finally {
                if ($prev === null) {
                    unset($_SERVER['MW_MULTISITE_SITE']);
                } else {
                    $_SERVER['MW_MULTISITE_SITE'] = $prev;
                }
            }
        });
    }

    public function test_provisioner_is_bound_and_lists_main_site(): void
    {
        $p = app(TenantSiteProvisioner::class);
        $this->assertInstanceOf(TenantSiteProvisioner::class, $p);

        $main = $p->mainSite();
        $this->assertTrue($main['is_main']);
        $this->assertNotSame('', (string) $main['domain']);

        $all = $p->all();
        $this->assertTrue($all[0]['is_main'], 'The main site must be the first row.');
    }

    public function test_page_declares_row_actions(): void
    {
        foreach (['editSiteAction', 'installSiteAction', 'deleteSiteAction'] as $m) {
            $this->assertTrue(method_exists(AdminTenantSitesPage::class, $m), "Page must declare {$m}().");
        }
    }

    /** Run $fn with the canonical flag set to $value (null = unset), then restore. */
    private function withFlag(?string $value, callable $fn): void
    {
        $key = TenantManagement::FLAG;
        $prev = getenv($key);
        $prevEnv = $_ENV[$key] ?? null;

        if ($value === null) {
            putenv($key);
            unset($_ENV[$key], $_SERVER[$key]);
        } else {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
        }

        try {
            $fn();
        } finally {
            if ($prev === false) {
                putenv($key);
                unset($_ENV[$key]);
            } else {
                putenv("{$key}={$prev}");
                $_ENV[$key] = $prevEnv;
            }
        }
    }
}

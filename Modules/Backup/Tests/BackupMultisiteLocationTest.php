<?php

namespace Modules\Backup\Tests;

use Modules\Backup\SessionStepper;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Backup storage must be isolated per site under multisite.
 *
 * backup_location() used to be a single shared storage/backup_content/<env>/
 * directory, so on a multisite install every site listed and could restore
 * every other site's archives (cross-tenant exposure + corruption). The fix
 * scopes the path with backup_site_segment() off the same site identifier the
 * userfiles tree uses (so it holds for S3 userfiles too). These assertions pin
 * that: the default single site is byte-for-byte unchanged, and two sites get
 * distinct, non-overlapping backup trees.
 */
class BackupMultisiteLocationTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($_SERVER['MW_MULTISITE_SITE']);

        // Remove the scoped dirs the path helpers may have created.
        foreach (['alpha-mstest.com', 'beta-mstest.com'] as $site) {
            @unlink(storage_path("backup_content/" . $this->app->environment() . "/sites/$site/.htaccess"));
            @rmdir(storage_path("backup_content/" . $this->app->environment() . "/sites/$site"));
            @rmdir(storage_path("backup/sites/$site/cache/session_stepper"));
            @rmdir(storage_path("backup/sites/$site/cache"));
            @rmdir(storage_path("backup/sites/$site"));
        }
        @rmdir(storage_path("backup_content/" . $this->app->environment() . "/sites"));
        @rmdir(storage_path('backup/sites'));

        parent::tearDown();
    }

    #[Test]
    public function the_default_single_site_backup_path_is_unchanged(): void
    {
        unset($_SERVER['MW_MULTISITE_SITE']);

        $this->assertSame('', backup_site_segment(), 'No per-site segment on the default site.');

        $location = backup_location();
        $this->assertStringContainsString('backup_content', $location);
        $this->assertStringNotContainsString('/sites/', $location,
            'The default single site must not get a per-site segment (backward compatibility).');
        $this->assertStringNotContainsString('/sites/', SessionStepper::cachePath());
    }

    #[Test]
    public function each_site_gets_its_own_isolated_backup_location(): void
    {
        $_SERVER['MW_MULTISITE_SITE'] = 'alpha-mstest.com';
        $alphaLocation = backup_location();
        $alphaStepper = SessionStepper::cachePath();

        $_SERVER['MW_MULTISITE_SITE'] = 'beta-mstest.com';
        $betaLocation = backup_location();
        $betaStepper = SessionStepper::cachePath();

        // Each site's archives live under its own sites/<domain>/ subtree.
        $this->assertStringContainsString('sites/alpha-mstest.com', $alphaLocation);
        $this->assertStringContainsString('sites/beta-mstest.com', $betaLocation);
        $this->assertStringContainsString('sites/alpha-mstest.com', $alphaStepper);
        $this->assertStringContainsString('sites/beta-mstest.com', $betaStepper);

        // And the two trees never overlap — the core isolation guarantee.
        $this->assertNotSame($alphaLocation, $betaLocation);
        $this->assertNotSame($alphaStepper, $betaStepper);
        $this->assertStringStartsNotWith($betaLocation, $alphaLocation);
    }
}

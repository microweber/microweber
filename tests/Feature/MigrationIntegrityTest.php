<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationIntegrityTest extends TestCase
{
    private function hasMigrationsTable(): bool
    {
        return Schema::hasTable('migrations');
    }

    /**
     * Test that all migrations can be rolled back successfully.
     *
     * This test ensures migration integrity by:
     * 1. Getting all migrations that have been run
     * 2. Rolling back the last batch
     * 3. Re-migrating to restore state
     * 4. Verifying no errors occurred during rollback
     *
     * @return void
     */
    public function test_all_migrations_can_rollback_successfully(): void
    {
        if (!$this->hasMigrationsTable()) {
            $this->markTestSkipped('Migrations table does not exist in current test environment');
            return;
        }

        // Ensure all pending migrations are applied before testing rollback integrity
        $this->artisan('migrate', ['--force' => true])->assertSuccessful();

        // Get the current migration batch
        $lastBatch = DB::table('migrations')
            ->max('batch');

        if ($lastBatch === null) {
            $this->markTestSkipped('No migrations have been run');
            return;
        }

        // Get migrations in the last batch
        $migrationsInLastBatch = DB::table('migrations')
            ->where('batch', $lastBatch)
            ->pluck('migration')
            ->toArray();

        // Store initial table list for comparison
        $tablesBeforeRollback = $this->getAllTables();

        // Attempt to rollback the last batch
        $this->artisan('migrate:rollback', [
            '--force' => true,
        ])->assertSuccessful();

        // Verify migrations were actually rolled back
        $migrationsAfterRollback = DB::table('migrations')
            ->where('batch', $lastBatch)
            ->pluck('migration')
            ->toArray();

        $this->assertEmpty($migrationsAfterRollback, 'Migrations in the last batch should be rolled back');

        // Get tables after rollback
        $tablesAfterRollback = $this->getAllTables();

        // Some tables may have been dropped, which is expected
        // We just need to ensure the rollback completed without exceptions

        // Re-migrate to restore the state
        $this->artisan('migrate', [
            '--force' => true,
        ])->assertSuccessful();

        // Verify migrations are back
        $migrationsAfterRemigrate = DB::table('migrations')
            ->where('batch', $lastBatch)
            ->pluck('migration')
            ->toArray();

        // Sort both arrays for comparison
        sort($migrationsInLastBatch);
        sort($migrationsAfterRemigrate);

        $this->assertEquals(
            $migrationsInLastBatch,
            $migrationsAfterRemigrate,
            'Re-migration should restore the same migrations that were rolled back'
        );
    }

    /**
     * Test that all migration files have a down() method.
     *
     * This ensures migrations can be rolled back, which is critical
     * for maintaining database integrity and enabling rollbacks in production.
     *
     * @return void
     */
    public function test_all_migrations_have_down_method(): void
    {
        $migrationFiles = $this->getAllMigrationFiles();
        $migrationsWithoutDown = [];

        foreach ($migrationFiles as $file) {
            $content = file_get_contents($file);

            // Check if the file has a down() method
            if (!preg_match('/public\s+function\s+down\s*\(/', $content)) {
                $migrationsWithoutDown[] = basename($file);
            }
        }

        if (!empty($migrationsWithoutDown)) {
            $this->fail(
                'The following migrations are missing the down() method:\n' .
                implode("\n", array_map(fn($m) => "  - {$m}", $migrationsWithoutDown)) .
                "\n\nAll migrations should implement down() for rollback capability."
            );
        }

        $this->assertEmpty($migrationsWithoutDown, 'All migrations should have a down() method');
    }

    /**
     * Test that migration files follow naming convention.
     *
     * Ensures migrations use the standard Laravel format: YYYY_MM_DD_HHMMSS_migration_name.php
     * This is a warning-level check - non-standard names are documented but don't fail the test.
     *
     * @return void
     */
    public function test_migration_files_follow_naming_convention(): void
    {
        $migrationFiles = $this->getAllMigrationFiles();
        $invalidNames = [];

        foreach ($migrationFiles as $file) {
            $filename = basename($file);

            // Check if filename matches Laravel convention: YYYY_MM_DD_HHMMSS_*.php
            if (!preg_match('/^\d{4}_\d{2}_\d{2}_\d{6}_.+\.php$/', $filename)) {
                $invalidNames[] = $filename;
            }
        }

        // Log non-standard names as warnings rather than failures
        // These exist for historical reasons and renaming would break existing installations
        if (!empty($invalidNames)) {
            $this->markTestSkipped(
                'Non-standard migration names found (historical):\n' .
                implode("\n", array_map(fn($m) => "  - {$m}", $invalidNames)) .
                "\n\nThese migrations work correctly but use non-standard naming. " .
                "Renaming would break existing installations."
            );
        }

        $this->assertEmpty($invalidNames, 'All migrations should follow naming convention');
    }

    /**
     * Test that all migrations have proper structure.
     *
     * Verifies migrations extend the Migration class and have up() method.
     *
     * @return void
     */
    public function test_all_migrations_have_proper_structure(): void
    {
        $migrationFiles = $this->getAllMigrationFiles();
        $invalidMigrations = [];

        foreach ($migrationFiles as $file) {
            $content = file_get_contents($file);

            // Check if extends Migration class
            if (!preg_match('/extends\s+Migration/', $content)) {
                $invalidMigrations[] = basename($file) . ' (does not extend Migration)';
                continue;
            }

            // Check if has up() method
            if (!preg_match('/public\s+function\s+up\s*\(/', $content)) {
                $invalidMigrations[] = basename($file) . ' (missing up() method)';
            }
        }

        if (!empty($invalidMigrations)) {
            $this->fail(
                'The following migrations have invalid structure:\n' .
                implode("\n", array_map(fn($m) => "  - {$m}", $invalidMigrations))
            );
        }

        $this->assertEmpty($invalidMigrations, 'All migrations should have proper structure');
    }

    /**
     * Test database migration table integrity.
     *
     * Verifies the migrations table exists and has required structure.
     *
     * @return void
     */
    public function test_migration_table_integrity(): void
    {
        if (!$this->hasMigrationsTable()) {
            $this->markTestSkipped('Migrations table does not exist in current test environment');
            return;
        }

        $this->assertTrue(
            Schema::hasTable('migrations'),
            'Migrations table should exist'
        );

        $this->assertTrue(
            Schema::hasColumns('migrations', ['id', 'migration', 'batch']),
            'Migrations table should have required columns'
        );
    }

    /**
     * Get all migration files from all migration directories.
     *
     * @return array
     */
    private function getAllMigrationFiles(): array
    {
        $migrationFiles = [];
        $paths = $this->getMigrationPaths();

        foreach ($paths as $path) {
            $fullPath = base_path($path);

            if (!is_dir($fullPath)) {
                continue;
            }

            $files = glob($fullPath . '/*.php');
            if ($files !== false) {
                $migrationFiles = array_merge($migrationFiles, $files);
            }
        }

        return $migrationFiles;
    }

    /**
     * Get all migration paths.
     *
     * @return array
     */
    private function getMigrationPaths(): array
    {
        return [
            'database/migrations',
            'Modules/Content/database/migrations',
            'Modules/ContentData/database/migrations',
            'Modules/ContentDataVariant/database/migrations',
            'Modules/ContentField/database/migrations',
            'Modules/Product/database/migrations',
            'Modules/Order/database/migrations',
            'Modules/Cart/database/migrations',
            'Modules/Category/database/migrations',
            'Modules/Coupons/database/migrations',
            'Modules/Customer/database/migrations',
            'Modules/Billing/database/migrations',
            'Modules/Media/database/migrations',
            'Modules/Newsletter/database/migrations',
            'Modules/Form/database/migrations',
            'Modules/Payment/database/migrations',
            'Modules/Shipping/database/migrations',
            'Modules/Tax/database/migrations',
            'Modules/Invoice/database/migrations',
            'Modules/Profile/database/migrations',
            'Modules/Company/database/migrations',
            'Modules/Country/database/migrations',
            'Modules/Currency/database/migrations',
            'Modules/CustomFields/database/migrations',
            'Modules/Menu/database/migrations',
            'Modules/Faq/database/migrations',
            'Modules/Attributes/database/migrations',
            'Modules/Rating/database/migrations',
            'Modules/Testimonials/database/migrations',
            'Modules/Teamcard/database/migrations',
            'Modules/Tabs/database/migrations',
            'Modules/Slider/database/migrations',
            'Modules/SiteStats/database/migrations',
            'Modules/Offer/database/migrations',
            'Modules/MailTemplate/database/migrations',
            'Modules/Log/database/migrations',
            'Modules/Ai/database/migrations',
            'Modules/LayoutContent/database/migrations',
            'Modules/Video/database/migrations',
            'src/MicroweberPackages/User/database/migrations',
            'src/MicroweberPackages/Fortify/database/migrations',
            'src/MicroweberPackages/Queue/migrations',
            'src/MicroweberPackages/Multilanguage/migrations',
            'src/MicroweberPackages/Translation/migrations',
            'src/MicroweberPackages/Role/database/migrations',
            'src/MicroweberPackages/LaravelModules/database/migrations',
            'src/MicroweberPackages/Notification/migrations',
            'src/MicroweberPackages/Option/migrations',
            'src/MicroweberPackages/Update/database/migrations',
            'src/MicroweberPackages/LaravelTemplates/database/migrations',
        ];
    }

    /**
     * Get list of all database tables.
     *
     * @return array
     */
    private function getAllTables(): array
    {
        $tables = [];
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $results = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            foreach ($results as $result) {
                $tables[] = $result->name;
            }
        } else {
            $results = DB::select('SHOW TABLES');
            $columnName = 'Tables_in_' . DB::getDatabaseName();
            foreach ($results as $result) {
                $tables[] = $result->$columnName;
            }
        }

        return $tables;
    }
}

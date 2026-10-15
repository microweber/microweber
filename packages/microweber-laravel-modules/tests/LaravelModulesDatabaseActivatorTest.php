<?php

declare(strict_types=1);

namespace MicroweberPackages\LaravelModules\Tests;

use MicroweberPackages\LaravelModules\Activators\LaravelModulesDatabaseActivator;
use MicroweberPackages\LaravelModules\Models\SystemModules;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Proves the fixed database module activator seeds + reports statuses
 * WITHOUT crashing and WITHOUT the old "all-disabled" bug, in isolation
 * from the live system_modules table.
 *
 * Context: the real site uses the FILE activator, so system_modules is
 * normally empty. These tests drive the DATABASE activator directly, which
 * seeds the table from the on-disk module.json manifests on first read.
 * Every row the test creates is cleaned up in tearDown so the shared table
 * returns to its prior state. The table is never truncated — only rows that
 * were not present before the test runs are removed.
 */
class LaravelModulesDatabaseActivatorTest extends TestCase
{
    /**
     * Module names that already existed in system_modules before the test.
     * tearDown deletes everything NOT in this set (i.e. only what we created).
     *
     * @var array<int, string>
     */
    protected array $preExisting = [];

    /**
     * A known module whose manifest exists on disk (Modules/Accordion/module.json)
     * and which is enabled in storage/modules_statuses.json.
     */
    protected string $knownModule = 'Accordion';

    protected function setUp(): void
    {
        parent::setUp();

        // Snapshot the pre-existing rows so tearDown only removes what we add.
        $this->preExisting = SystemModules::query()->pluck('name')->all();

        // Start from a known cache state so readStatus() runs fresh.
        $this->flushModulesCache();
    }

    protected function tearDown(): void
    {
        // Remove only the rows this test created; never touch pre-existing rows.
        SystemModules::query()
            ->whereNotIn('name', $this->preExisting)
            ->delete();

        $this->flushModulesCache();

        parent::tearDown();
    }

    #[Test]
    public function seed_populates_statuses_and_does_not_disable_everything(): void
    {
        $activator = new LaravelModulesDatabaseActivator(app());

        // readStatus() is NOT gated by mw_is_installed() and is not cached; it
        // seeds from the manifests when the table is empty, then re-reads.
        // The old bug crashed here with an unbound 'modules.factory'.
        $statuses = $activator->readStatus();

        $this->assertIsArray($statuses);
        $this->assertNotEmpty(
            $statuses,
            'readStatus() must return a non-empty map after seeding from manifests.'
        );

        // The old bug seeded everything status=0 — prove at least one is enabled.
        $this->assertGreaterThan(
            0,
            count(array_filter($statuses)),
            'Seed must NOT disable every module (the old all-false bug).'
        );

        // A known module got a real row with status = 1 (enabled).
        $row = SystemModules::getByName($this->knownModule);
        $this->assertNotNull(
            $row,
            "system_modules must contain a row for {$this->knownModule} after seeding."
        );
        $this->assertTrue(
            (bool) $row->status,
            "{$this->knownModule} must be enabled (status=1) after seeding."
        );

        // And the returned map agrees.
        $this->assertArrayHasKey($this->knownModule, $statuses);
        $this->assertTrue($statuses[$this->knownModule]);
    }

    #[Test]
    public function enable_disable_round_trip_updates_memory_and_db(): void
    {
        $activator = new LaravelModulesDatabaseActivator(app());

        // Ensure the table is seeded so the known module has a persistable row.
        $activator->readStatus();

        // --- disable ---
        $activator->setActiveByName($this->knownModule, false);
        $activator->flushCache();

        $this->assertTrue(
            $activator->hasStatus($this->knownModule, false),
            'hasStatus($module, false) must be true after disabling.'
        );

        $disabledRow = SystemModules::getByName($this->knownModule);
        $this->assertNotNull($disabledRow);
        $this->assertFalse(
            (bool) $disabledRow->status,
            'The DB row status must be 0 after disabling.'
        );

        // --- re-enable ---
        $activator->setActiveByName($this->knownModule, true);
        $activator->flushCache();

        $this->assertTrue(
            $activator->hasStatus($this->knownModule, true),
            'hasStatus($module, true) must be true after re-enabling.'
        );

        $enabledRow = SystemModules::getByName($this->knownModule);
        $this->assertNotNull($enabledRow);
        $this->assertTrue(
            (bool) $enabledRow->status,
            'The DB row status must be 1 after re-enabling.'
        );
    }

    /**
     * Flush the modules activation cache so readStatus() is observed fresh.
     */
    protected function flushModulesCache(): void
    {
        if (app()->bound('modules')) {
            app('modules')->flushCache();
        }

        $cacheKey = config('modules.activators.database.cache-key', 'modules.activations');
        app('cache')->store(config('modules.cache.driver'))->forget($cacheKey);
    }
}

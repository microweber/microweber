<?php

declare(strict_types=1);

namespace MicroweberPackages\LaravelTemplates\Tests;

use MicroweberPackages\LaravelTemplates\Activators\LaravelTemplatesDatabaseActivator;
use MicroweberPackages\LaravelTemplates\Models\SystemTemplates;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Mirror of LaravelModulesDatabaseActivatorTest for templates.
 *
 * The real site uses the FILE activator, so system_templates is normally empty.
 * These tests drive the DATABASE activator directly, which seeds the table from
 * the on-disk template module.json manifests across EVERY template root — the
 * primary Templates/ folder and the extra drop-in roots (ExtraTemplates/…) the
 * scanner also walks. Every row the test creates is cleaned up in tearDown so
 * the shared table returns to its prior state.
 */
class LaravelTemplatesDatabaseActivatorTest extends TestCase
{
    /** @var array<int, string> */
    protected array $preExisting = [];

    /** A template whose manifest exists on disk (Templates/Bootstrap/module.json). */
    protected string $knownTemplate = 'Bootstrap';

    /** A second manifest-bearing template (Templates/Big/module.json). */
    protected string $otherTemplate = 'Big';

    protected function setUp(): void
    {
        parent::setUp();
        $this->preExisting = SystemTemplates::query()->pluck('name')->all();
        $this->flushTemplatesCache();
    }

    protected function tearDown(): void
    {
        SystemTemplates::query()
            ->whereNotIn('name', $this->preExisting)
            ->delete();

        $this->flushTemplatesCache();

        parent::tearDown();
    }

    #[Test]
    public function seed_populates_statuses_and_does_not_disable_everything(): void
    {
        $activator = new LaravelTemplatesDatabaseActivator(app());

        $statuses = $activator->readStatus();

        $this->assertIsArray($statuses);
        $this->assertNotEmpty($statuses, 'readStatus() must return a non-empty map after seeding.');
        $this->assertGreaterThan(
            0,
            count(array_filter($statuses)),
            'Seed must NOT disable every template.'
        );

        $row = SystemTemplates::getByName($this->knownTemplate);
        $this->assertNotNull($row, "system_templates must contain a row for {$this->knownTemplate}.");
        $this->assertTrue((bool) $row->status, "{$this->knownTemplate} must be enabled after seeding.");
        $this->assertArrayHasKey($this->knownTemplate, $statuses);
        $this->assertTrue($statuses[$this->knownTemplate]);
    }

    #[Test]
    public function readstatus_backfills_templates_added_after_the_first_seed_without_touching_existing_rows(): void
    {
        (new LaravelTemplatesDatabaseActivator(app()))->readStatus();
        $this->assertNotNull(SystemTemplates::getByName($this->knownTemplate));

        // Simulate a bundled template shipped AFTER the first seed: on disk but
        // with no row — the state that used to make it silently disabled.
        SystemTemplates::query()->where('name', $this->knownTemplate)->delete();
        $this->assertNull(SystemTemplates::getByName($this->knownTemplate));

        // Disable a different, still-present template to prove insert-only.
        $otherRow = SystemTemplates::getByName($this->otherTemplate);
        $this->assertNotNull($otherRow);
        $otherOriginal = (int) $otherRow->status;
        $otherRow->status = 0;
        $otherRow->save();

        $this->flushTemplatesCache();

        $statuses = (new LaravelTemplatesDatabaseActivator(app()))->readStatus();

        $this->assertArrayHasKey($this->knownTemplate, $statuses);
        $this->assertTrue(
            $statuses[$this->knownTemplate],
            'A template added after the first seed must default to enabled, not silently disabled.'
        );

        $backfilled = SystemTemplates::getByName($this->knownTemplate);
        $this->assertNotNull($backfilled, 'The missing template must be back-filled with a row.');
        $this->assertTrue((bool) $backfilled->status);

        $this->assertSame(
            0,
            (int) SystemTemplates::getByName($this->otherTemplate)->status,
            'Insert-only back-fill must not overwrite an existing (admin-disabled) row.'
        );

        $restore = SystemTemplates::getByName($this->otherTemplate);
        $restore->status = $otherOriginal;
        $restore->save();
    }

    #[Test]
    public function construction_defers_status_loading_to_avoid_the_circular_dependency(): void
    {
        $activator = new LaravelTemplatesDatabaseActivator(app());

        $loaded = new \ReflectionProperty($activator, 'templatesStatusesLoaded');
        $loaded->setAccessible(true);

        $this->assertFalse(
            $loaded->getValue($activator),
            'Constructing the templates activator must NOT eager-load statuses.'
        );

        $activator->hasStatus($this->knownTemplate, true);

        $this->assertTrue(
            $loaded->getValue($activator),
            'The first status query must lazily load template statuses.'
        );
    }

    #[Test]
    public function readstatus_covers_templates_from_extra_drop_in_roots(): void
    {
        // Alfa ships under ExtraTemplates/ (a scan root), not the primary
        // Templates/ folder. It must still be manifest-seeded and enabled on the
        // database activator — otherwise every extra drop-in template would be
        // silently disabled on a multisite domain.
        $extra = 'Alfa';

        if (! $this->extraTemplateExists($extra)) {
            $this->markTestSkipped("Extra template {$extra} not present in this checkout.");
        }

        $statuses = (new LaravelTemplatesDatabaseActivator(app()))->readStatus();

        $this->assertArrayHasKey(
            $extra,
            $statuses,
            'Templates from extra drop-in roots (ExtraTemplates/) must be discovered.'
        );
        $this->assertTrue($statuses[$extra], 'An extra drop-in template must default to enabled.');
        $this->assertNotNull(
            SystemTemplates::getByName($extra),
            'An extra drop-in template must be back-filled with a row.'
        );
    }

    private function extraTemplateExists(string $name): bool
    {
        foreach ((array) config('templates.scan.paths', []) as $root) {
            if (is_file(rtrim((string) $root, '/\\') . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'module.json')) {
                return true;
            }
        }

        return false;
    }

    protected function flushTemplatesCache(): void
    {
        if (app()->bound('templates')) {
            app('templates')->flushCache();
        }

        $cacheKey = config('templates.activators.database.cache-key', 'templates.activations');
        app('cache')->store(config('templates.cache.driver'))->forget($cacheKey);
    }
}

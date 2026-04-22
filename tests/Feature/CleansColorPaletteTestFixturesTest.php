<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteFactory;
use Tests\Browser\Traits\CleansColorPaletteTestFixtures;
use Tests\DuskTestCase;
use Tests\TestCase;

/**
 * Non-browser coverage of {@see CleansColorPaletteTestFixtures}.
 *
 * Verifies each of the four cleanup responsibilities: page-row purge,
 * template-group reset, options cache invalidation, and admin-login
 * cache reset. The trait's `tearDown{TraitBasename}` hook is invoked
 * explicitly so we don't depend on Laravel's lifecycle-registration
 * machinery firing inside a plain Feature test.
 */
class CleansColorPaletteTestFixturesTest extends TestCase
{
    private object $harness;

    protected function setUp(): void
    {
        parent::setUp();

        // Start from a clean slate — earlier test classes in the same
        // run may have left fixtures behind.
        ColorPaletteFactory::cleanupAll();
        $this->cleanTemplateOptionsGroupPristine();

        $this->harness = new class {
            use CleansColorPaletteTestFixtures;

            public function run(): void
            {
                $this->tearDownCleansColorPaletteTestFixtures();
            }

            public function runPurge(): void
            {
                $this->purgeColorPaletteTestPages();
            }

            public function runResetTemplate(): void
            {
                $this->resetTemplateOptionsGroup();
            }

            public function runInvalidateCache(): void
            {
                $this->invalidateOptionsCache();
            }

            public function runResetLogin(): void
            {
                $this->resetAdminLoggedInCache();
            }
        };
    }

    protected function tearDown(): void
    {
        ColorPaletteFactory::cleanupAll();
        $this->cleanTemplateOptionsGroupPristine();
        DuskTestCase::$adminLoggedIn = false;
        parent::tearDown();
    }

    /**
     * Restore the `template` group to exactly one row:
     * current_template=Bootstrap. Used by setUp/tearDown here so each
     * test starts and ends from a known options baseline.
     */
    private function cleanTemplateOptionsGroupPristine(): void
    {
        DB::table('options')->where('option_group', 'template')->delete();
        DB::table('options')->insert([
            'option_key' => 'current_template',
            'option_value' => 'Bootstrap',
            'option_group' => 'template',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    #[Test]
    public function teardown_deletes_color_palette_test_pages(): void
    {
        $fixtures = [
            ColorPaletteFactory::make('teardown-a'),
            ColorPaletteFactory::make('teardown-b'),
        ];
        $ids = array_map(static fn ($f) => $f->pageId, $fixtures);

        $this->assertSame(
            2,
            DB::table('content')->whereIn('id', $ids)->count(),
            'Sanity: both fixtures must be in the DB before teardown'
        );

        $this->harness->run();

        $this->assertSame(
            0,
            DB::table('content')->whereIn('id', $ids)->count(),
            'tearDown must cascade-delete every color-palette-test-* page'
        );
    }

    #[Test]
    public function teardown_resets_current_template_back_to_bootstrap(): void
    {
        DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->update(['option_value' => 'Big2']);

        $this->harness->run();

        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();

        $this->assertNotNull($row, 'current_template row must still exist after teardown');
        $this->assertSame(
            'Bootstrap',
            $row->option_value,
            'tearDown must reset current_template to Bootstrap'
        );
    }

    #[Test]
    public function teardown_purges_ad_hoc_template_group_rows(): void
    {
        DB::table('options')->insert([
            'option_key' => 'palette_test_extra_key',
            'option_value' => 'garbage',
            'option_group' => 'template',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertSame(
            1,
            DB::table('options')
                ->where('option_group', 'template')
                ->where('option_key', 'palette_test_extra_key')
                ->count(),
            'Sanity: ad-hoc row must be present before teardown'
        );

        $this->harness->run();

        $this->assertSame(
            0,
            DB::table('options')
                ->where('option_group', 'template')
                ->where('option_key', 'palette_test_extra_key')
                ->count(),
            'tearDown must purge non-owned template-group rows'
        );
    }

    #[Test]
    public function teardown_preserves_current_template_row_even_when_it_was_already_bootstrap(): void
    {
        $this->harness->run();

        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();

        $this->assertNotNull($row, 'tearDown must not drop the current_template row');
        $this->assertSame('Bootstrap', $row->option_value);
    }

    #[Test]
    public function teardown_flips_admin_logged_in_static_back_to_false(): void
    {
        DuskTestCase::$adminLoggedIn = true;

        $this->harness->run();

        $this->assertFalse(
            DuskTestCase::$adminLoggedIn,
            'tearDown must reset DuskTestCase::$adminLoggedIn'
        );
    }

    #[Test]
    public function individual_cleanup_methods_are_safe_to_call_in_isolation(): void
    {
        // Each helper must be independently idempotent — a failing
        // test might only be able to reach some of them. Running each
        // alone against a clean baseline should not throw.
        $this->harness->runPurge();
        $this->harness->runResetTemplate();
        $this->harness->runInvalidateCache();
        $this->harness->runResetLogin();

        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();
        $this->assertSame('Bootstrap', $row->option_value ?? null);
        $this->assertFalse(DuskTestCase::$adminLoggedIn);
    }

    #[Test]
    public function teardown_is_idempotent_when_run_twice_in_a_row(): void
    {
        $fixture = ColorPaletteFactory::make('idempotent');
        $id = $fixture->pageId;

        $this->harness->run();
        $this->harness->run();

        $this->assertNull(
            DB::table('content')->where('id', $id)->first(),
            'Double-teardown must still leave zero palette pages'
        );
    }
}

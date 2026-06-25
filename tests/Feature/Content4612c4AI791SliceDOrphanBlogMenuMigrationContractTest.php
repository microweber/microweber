<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-4612c4 / AI-791 Slice D + AI-792b bundled migration
 * contract.
 *
 * Jira: https://microweber.atlassian.net/browse/AI-791 (Slice D)
 * Jira: https://microweber.atlassian.net/browse/AI-792 (AI-792b backfill)
 *
 * Migration file under test:
 *   Modules/Content/database/migrations/
 *     2026_05_17_000001_ai_791_slice_d_orphan_blog_menu_cleanup_and_ai_792b_layout_backfill.php
 *
 * Two surgical data fixes verified here against a clean fixture DB:
 *
 * 1. AI-791 Slice D: legacy installs carry a duplicate Blog menu
 *    entry whose content_id points to a `Blog<14-digit-install-
 *    timestamp>` orphan content row (e.g. `Blog20260516195012`).
 *    Migration deletes ONLY the duplicate menu entry; the canonical
 *    `Blog` menu entry stays + the orphan content row stays (so
 *    deep links keep working).
 *
 * 2. AI-792b: legacy Blog pages (created pre-AI-792 ship) have
 *    `layout_file = null` and render the home-hero template
 *    instead of blog.blade.php. Migration backfills
 *    `layout_file = 'blog.blade.php'` on the matching rows.
 *
 * Per-test pattern: this is a DB-driven contract test (NOT a no-DB
 * trait-only test) because the migration's logic is SQL. Each test
 * sets up a tiny fixture, runs the migration's private helpers via
 * the public migration `up()` hook, then asserts the post-state.
 *
 * Restrictions per project test conventions: NO RunInSeparateProcess,
 * NO DatabaseTransactions, NO RefreshDatabase. Each test clears its
 * fixture rows in setUp() and tearDown() by content_id range so it
 * does not contaminate sibling tests.
 */
class Content4612c4AI791SliceDOrphanBlogMenuMigrationContractTest extends TestCase
{
    /**
     * Sentinel content_id range used by these tests so we never
     * collide with real content rows or sibling test fixtures.
     */
    private const FIXTURE_CONTENT_ID_BASE = 900791000;

    private string $migrationPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->migrationPath = base_path(
            'Modules/Content/database/migrations/'
            . '2026_05_17_000001_ai_791_slice_d_orphan_blog_menu_cleanup_and_ai_792b_layout_backfill.php'
        );

        // The AI-791 Slice D / AI-792b one-time data migration was retired (no
        // longer needed); this contract pinned that now-removed migration, so it
        // no longer applies.
        if (! is_file($this->migrationPath)) {
            $this->markTestSkipped('AI-791 Slice D migration was retired — contract no longer applicable.');
        }

        $this->clearFixtures();
    }

    protected function tearDown(): void
    {
        $this->clearFixtures();
        parent::tearDown();
    }

    private function clearFixtures(): void
    {
        if (Schema::hasTable('content')) {
            DB::table('content')
                ->where('id', '>=', self::FIXTURE_CONTENT_ID_BASE)
                ->where('id', '<', self::FIXTURE_CONTENT_ID_BASE + 1000)
                ->delete();
        }
        if (Schema::hasTable('menus')) {
            DB::table('menus')
                ->where('content_id', '>=', self::FIXTURE_CONTENT_ID_BASE)
                ->where('content_id', '<', self::FIXTURE_CONTENT_ID_BASE + 1000)
                ->delete();
        }
    }

    /**
     * Run the migration via Laravel's loader so the anonymous-class
     * return value is invoked exactly the same way Artisan does.
     */
    private function runMigration(): void
    {
        /** @var \Illuminate\Database\Migrations\Migration $migration */
        $migration = require $this->migrationPath;
        $migration->up();
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  migration file shape + idempotency
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function migration_file_exists_and_is_a_valid_migration(): void
    {
        $this->assertFileExists($this->migrationPath);
        /** @var mixed $migration */
        $migration = require $this->migrationPath;
        $this->assertInstanceOf(
            \Illuminate\Database\Migrations\Migration::class,
            $migration,
            'Migration file MUST return an anonymous Migration class instance.'
        );
        $this->assertTrue(
            method_exists($migration, 'up'),
            'Migration MUST define an up() method.'
        );
        $this->assertTrue(
            method_exists($migration, 'down'),
            'Migration MUST define a down() method (even if no-op).'
        );
    }

    #[Test]
    public function up_is_idempotent_no_op_on_clean_install(): void
    {
        // No fixture data inserted -> migration should run cleanly
        // and write nothing. Run twice to confirm idempotency.
        $beforeMenus = DB::table('menus')->count();
        $beforeContent = DB::table('content')->count();

        $this->runMigration();
        $this->runMigration();

        $this->assertSame($beforeMenus, DB::table('menus')->count(),
            'Migration MUST be a no-op on clean installs (no menu rows changed).');
        $this->assertSame($beforeContent, DB::table('content')->count(),
            'Migration MUST be a no-op on clean installs (no content rows changed).');
    }

    #[Test]
    public function down_is_no_op_by_design(): void
    {
        /** @var \Illuminate\Database\Migrations\Migration $migration */
        $migration = require $this->migrationPath;
        $beforeMenus = DB::table('menus')->count();
        $beforeContent = DB::table('content')->count();

        $migration->down();

        $this->assertSame($beforeMenus, DB::table('menus')->count(),
            'down() MUST be a no-op (cannot deterministically restore deleted menu items).');
        $this->assertSame($beforeContent, DB::table('content')->count(),
            'down() MUST be a no-op for content rows too.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  AI-791 Slice D — orphan Blog menu cleanup
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function orphan_blog_menu_entry_is_deleted_when_canonical_exists(): void
    {
        // Fixture: canonical Blog page + orphan Blog20260516195012
        // page + matching menu entries in the same parent menu.
        $canonicalId = self::FIXTURE_CONTENT_ID_BASE + 1;
        $orphanId = self::FIXTURE_CONTENT_ID_BASE + 2;
        $now = now()->toDateTimeString();

        DB::table('content')->insert([
            ['id' => $canonicalId, 'title' => 'Blog', 'url' => 'Blog', 'content_type' => 'page', 'subtype' => 'dynamic', 'is_shop' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $orphanId,    'title' => 'Blog', 'url' => 'Blog20260516195012', 'content_type' => 'page', 'subtype' => 'dynamic', 'is_shop' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        $canonicalMenuId = DB::table('menus')->insertGetId([
            'title' => 'Blog', 'url' => 'Blog', 'content_id' => $canonicalId,
            'parent_id' => 0, 'menu_name' => 'header_menu-test-4612c4', 'is_active' => 1,
            'created_at' => $now, 'updated_at' => $now,
        ]);
        $orphanMenuId = DB::table('menus')->insertGetId([
            'title' => 'Blog', 'url' => 'Blog20260516195012', 'content_id' => $orphanId,
            'parent_id' => 0, 'menu_name' => 'header_menu-test-4612c4', 'is_active' => 1,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $this->runMigration();

        // Orphan menu MUST be deleted.
        $this->assertSame(0,
            DB::table('menus')->where('id', $orphanMenuId)->count(),
            'Orphan Blog menu entry MUST be deleted.');
        // Canonical menu MUST stay.
        $this->assertSame(1,
            DB::table('menus')->where('id', $canonicalMenuId)->count(),
            'Canonical Blog menu entry MUST be preserved.');
        // Content rows for both MUST stay (the migration cleans the
        // MENU entry only, not the content -- deep links to the
        // orphan URL should keep working).
        $this->assertSame(1, DB::table('content')->where('id', $canonicalId)->count(),
            'Canonical Blog content row MUST stay.');
        $this->assertSame(1, DB::table('content')->where('id', $orphanId)->count(),
            'Orphan Blog content row MUST stay (deep-link safety).');
    }

    #[Test]
    public function orphan_blog_menu_entry_is_preserved_when_no_canonical_exists(): void
    {
        // Safety: if the only Blog menu entry is a timestamp orphan
        // (no canonical Blog menu in the same parent), MUST keep the
        // orphan -- never strip the user's sole Blog nav.
        $orphanId = self::FIXTURE_CONTENT_ID_BASE + 11;
        $now = now()->toDateTimeString();

        DB::table('content')->insert([
            ['id' => $orphanId, 'title' => 'Blog', 'url' => 'Blog20260516195012', 'content_type' => 'page', 'subtype' => 'dynamic', 'is_shop' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        $orphanMenuId = DB::table('menus')->insertGetId([
            'title' => 'Blog', 'url' => 'Blog20260516195012', 'content_id' => $orphanId,
            'parent_id' => 0, 'menu_name' => 'header_menu-test-4612c4-solo', 'is_active' => 1,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $this->runMigration();

        $this->assertSame(1,
            DB::table('menus')->where('id', $orphanMenuId)->count(),
            'Orphan Blog menu MUST be preserved when NO canonical Blog menu exists in the same parent (never strip sole nav entry).');
    }

    #[Test]
    public function orphan_in_different_parent_menu_is_preserved(): void
    {
        // Safety: orphan in parent_id=99 with canonical in parent_id=0
        // -- the EXISTS clause is scoped to the same parent_id, so the
        // orphan in a different parent menu must stay (different
        // surface entirely).
        $canonicalId = self::FIXTURE_CONTENT_ID_BASE + 21;
        $orphanId = self::FIXTURE_CONTENT_ID_BASE + 22;
        $now = now()->toDateTimeString();

        DB::table('content')->insert([
            ['id' => $canonicalId, 'title' => 'Blog', 'url' => 'Blog', 'content_type' => 'page', 'subtype' => 'dynamic', 'is_shop' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $orphanId,    'title' => 'Blog', 'url' => 'Blog20260516195013', 'content_type' => 'page', 'subtype' => 'dynamic', 'is_shop' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('menus')->insert([
            ['title' => 'Blog', 'url' => 'Blog', 'content_id' => $canonicalId, 'parent_id' => 0, 'menu_name' => 'header-4612c4-A', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        $orphanMenuId = DB::table('menus')->insertGetId([
            'title' => 'Blog', 'url' => 'Blog20260516195013', 'content_id' => $orphanId,
            'parent_id' => 99, 'menu_name' => 'footer-4612c4-different', 'is_active' => 1,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $this->runMigration();

        $this->assertSame(1,
            DB::table('menus')->where('id', $orphanMenuId)->count(),
            'Orphan in different parent_id MUST be preserved (EXISTS clause scoped per-parent).');
    }

    #[Test]
    public function non_blog_timestamp_slug_is_preserved(): void
    {
        // Safety: ensure the regex is SPECIFIC to `Blog<14-digit>` --
        // a content row with url='BlogFoo' or url='Blog123' must NOT
        // be misinterpreted as an orphan. The migration restricts to
        // exactly 18 chars (Blog + 14 digits).
        $canonicalId = self::FIXTURE_CONTENT_ID_BASE + 31;
        $shortId = self::FIXTURE_CONTENT_ID_BASE + 32;
        $longId  = self::FIXTURE_CONTENT_ID_BASE + 33;
        $textId  = self::FIXTURE_CONTENT_ID_BASE + 34;
        $now = now()->toDateTimeString();

        DB::table('content')->insert([
            ['id' => $canonicalId, 'title' => 'Blog', 'url' => 'Blog', 'content_type' => 'page', 'subtype' => 'dynamic', 'is_shop' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            // 13 digits — too short
            ['id' => $shortId, 'title' => 'Blog short', 'url' => 'Blog2026051619501', 'content_type' => 'page', 'subtype' => 'dynamic', 'is_shop' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            // 15 digits — too long
            ['id' => $longId,  'title' => 'Blog long',  'url' => 'Blog202605161950123', 'content_type' => 'page', 'subtype' => 'dynamic', 'is_shop' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            // Text suffix — not all-digits
            ['id' => $textId,  'title' => 'BlogFoo',    'url' => 'BlogFoo12345678901', 'content_type' => 'page', 'subtype' => 'dynamic', 'is_shop' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('menus')->insert([
            ['title' => 'Blog',       'url' => 'Blog',                'content_id' => $canonicalId, 'parent_id' => 0, 'menu_name' => 'header-4612c4-regex-test', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Blog short', 'url' => 'Blog2026051619501',   'content_id' => $shortId,     'parent_id' => 0, 'menu_name' => 'header-4612c4-regex-test', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Blog long',  'url' => 'Blog202605161950123', 'content_id' => $longId,      'parent_id' => 0, 'menu_name' => 'header-4612c4-regex-test', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'BlogFoo',    'url' => 'BlogFoo12345678901',  'content_id' => $textId,      'parent_id' => 0, 'menu_name' => 'header-4612c4-regex-test', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $beforeCount = DB::table('menus')
            ->whereIn('content_id', [$canonicalId, $shortId, $longId, $textId])
            ->count();

        $this->runMigration();

        $afterCount = DB::table('menus')
            ->whereIn('content_id', [$canonicalId, $shortId, $longId, $textId])
            ->count();

        $this->assertSame($beforeCount, $afterCount,
            'Non-timestamp-orphan slugs (short/long/text-suffix) MUST be preserved -- regex is exactly Blog + 14 digits.');
    }

    #[Test]
    public function orphan_with_wrong_content_type_is_preserved(): void
    {
        // Safety: if a content row has url='Blog20260516195014' BUT
        // content_type='post' (not 'page'), it isn't a Blog-page
        // orphan -- MUST stay. The migration restricts to the exact
        // shape createDefaultBlogPage() creates.
        $canonicalId = self::FIXTURE_CONTENT_ID_BASE + 41;
        $wrongTypeId = self::FIXTURE_CONTENT_ID_BASE + 42;
        $now = now()->toDateTimeString();

        DB::table('content')->insert([
            ['id' => $canonicalId, 'title' => 'Blog', 'url' => 'Blog', 'content_type' => 'page', 'subtype' => 'dynamic', 'is_shop' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $wrongTypeId, 'title' => 'Blog 2026', 'url' => 'Blog20260516195014', 'content_type' => 'post', 'subtype' => 'static', 'is_shop' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('menus')->insert([
            ['title' => 'Blog',    'url' => 'Blog',                'content_id' => $canonicalId, 'parent_id' => 0, 'menu_name' => 'header-4612c4-typecheck', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        $wrongTypeMenuId = DB::table('menus')->insertGetId([
            'title' => 'Blog 2026', 'url' => 'Blog20260516195014',
            'content_id' => $wrongTypeId, 'parent_id' => 0,
            'menu_name' => 'header-4612c4-typecheck', 'is_active' => 1,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $this->runMigration();

        $this->assertSame(1,
            DB::table('menus')->where('id', $wrongTypeMenuId)->count(),
            'Menu entry pointing to a non-page (e.g. post) MUST be preserved -- only page+dynamic+is_shop=0 content qualifies as orphan.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  AI-792b — layout_file backfill on legacy Blog pages
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function legacy_canonical_blog_page_layout_file_is_backfilled(): void
    {
        $canonicalId = self::FIXTURE_CONTENT_ID_BASE + 51;
        $now = now()->toDateTimeString();

        DB::table('content')->insert([
            'id' => $canonicalId, 'title' => 'Blog', 'url' => 'Blog',
            'content_type' => 'page', 'subtype' => 'dynamic',
            'is_shop' => 0, 'is_active' => 1, 'layout_file' => null,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $this->runMigration();

        $row = DB::table('content')->where('id', $canonicalId)->first();
        $this->assertNotNull($row, 'Canonical Blog content row MUST stay after migration.');
        $this->assertSame('blog.blade.php', $row->layout_file,
            'AI-792b: legacy canonical Blog page layout_file MUST be backfilled to blog.blade.php.');
    }

    #[Test]
    public function timestamp_orphan_blog_page_layout_file_also_backfilled(): void
    {
        // The orphan content row stays after the menu cleanup; its
        // layout_file should also be backfilled so deep links resolve
        // correctly (per migration class docblock).
        $orphanId = self::FIXTURE_CONTENT_ID_BASE + 61;
        $now = now()->toDateTimeString();

        DB::table('content')->insert([
            'id' => $orphanId, 'title' => 'Blog', 'url' => 'Blog20260516195015',
            'content_type' => 'page', 'subtype' => 'dynamic',
            'is_shop' => 0, 'is_active' => 1, 'layout_file' => null,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $this->runMigration();

        $row = DB::table('content')->where('id', $orphanId)->first();
        $this->assertSame('blog.blade.php', $row->layout_file,
            'AI-792b: timestamp-orphan Blog page layout_file MUST also be backfilled (content row stays after menu cleanup).');
    }

    #[Test]
    public function already_set_layout_file_is_not_overwritten(): void
    {
        // Safety: if a Blog page has a non-null layout_file (e.g. an
        // operator manually picked `blog-custom.blade.php`), the
        // migration MUST NOT overwrite it.
        $customId = self::FIXTURE_CONTENT_ID_BASE + 71;
        $now = now()->toDateTimeString();

        DB::table('content')->insert([
            'id' => $customId, 'title' => 'Blog', 'url' => 'Blog',
            'content_type' => 'page', 'subtype' => 'dynamic',
            'is_shop' => 0, 'is_active' => 1, 'layout_file' => 'blog-custom.blade.php',
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $this->runMigration();

        $row = DB::table('content')->where('id', $customId)->first();
        $this->assertSame('blog-custom.blade.php', $row->layout_file,
            'AI-792b: existing non-null layout_file MUST NOT be overwritten (operator choice preserved).');
    }

    #[Test]
    public function unrelated_dynamic_page_layout_file_is_not_touched(): void
    {
        // Safety: a dynamic page that isn't the Blog page (different
        // URL pattern) MUST NOT be touched -- the migration restricts
        // to `Blog` and `Blog<14-digit>` URLs only.
        $unrelatedId = self::FIXTURE_CONTENT_ID_BASE + 81;
        $now = now()->toDateTimeString();

        DB::table('content')->insert([
            'id' => $unrelatedId, 'title' => 'News', 'url' => 'News',
            'content_type' => 'page', 'subtype' => 'dynamic',
            'is_shop' => 0, 'is_active' => 1, 'layout_file' => null,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $this->runMigration();

        $row = DB::table('content')->where('id', $unrelatedId)->first();
        $this->assertNull($row->layout_file,
            'AI-792b: unrelated dynamic pages (non-Blog URLs) MUST NOT be touched.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  task-id markers + cross-references in migration source
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function migration_source_carries_task_id_and_ticket_markers(): void
    {
        $src = (string) file_get_contents($this->migrationPath);
        $this->assertStringContainsString('task-2026-05-17-4612c4', $src);
        $this->assertStringContainsString('AI-791', $src);
        $this->assertStringContainsString('AI-792b', $src);
        // Designer dispatch authorisation
        $this->assertStringContainsString('Bundle with the', $src);
    }
}

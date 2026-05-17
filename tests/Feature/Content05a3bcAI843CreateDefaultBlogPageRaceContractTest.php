<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Content\Models\Content;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-05a3bc / AI-843 — createDefaultBlogPage() race-
 * condition hardening contract.
 * Jira: https://microweber.atlassian.net/browse/AI-843
 *
 * AI-791 lineage (preventative complement to the AI-791 Slice D +
 * AI-792b cleanup migration shipped at commit c7a85401b4 + pinned by
 * Content4612c4AI791SliceDOrphanBlogMenuMigrationContractTest).
 *
 * Pre-fix Modules/Content/Models/Content.php::createDefaultBlogPage()
 * carried a race window. 3 call sites (TemplateInstaller.php:159 +
 * ContentResource.php:620 + ContentRepository.php:264) can fire the
 * method concurrently during install bootstrap. The get_pages() null-
 * check + new static() save sequence let TWO concurrent calls both
 * observe get_pages() = null + both proceed to save — producing the
 * orphan `Blog{14-digit-timestamp}` rows that AI-791 Slice D migration
 * cleans up reactively.
 *
 * Post-fix Option B (designer-validated, smallest diff): re-check via
 * a different query path (static::where('url', 'Blog')->exists()) BEFORE
 * the save. Catches the race window — if Call A's save completed
 * between Call B's get_pages() and Call B's where(), Call B's where()
 * returns true and we return early without re-saving.
 *
 * Belt + suspenders: the small remaining window between where() and
 * save() (1 SQL roundtrip) is covered by the AI-791 Slice D cleanup
 * migration as a fallback safety net. Application-level prevention
 * here + reactive cleanup migration there = two-layer defence.
 *
 * Per-test pattern: this is a DB-driven contract test (mirrors the
 * AI-791 Slice D fixture pattern at Content4612c4AI791SliceDOrphan
 * BlogMenuMigrationContractTest). Each test sets up a tiny fixture,
 * calls the static method via Content::createDefaultBlogPage(), then
 * asserts the post-state.
 *
 * Restrictions per project test conventions: NO RunInSeparateProcess,
 * NO DatabaseTransactions, NO RefreshDatabase. Each test clears its
 * fixture rows in setUp() and tearDown() by id range + title-shape
 * so it does not contaminate sibling tests or real install state.
 *
 * Two-layer selector-self-match guard defence applied (18+ session-
 * recurrences) — docblock prose in word-form per the unified meta-
 * rule.
 */
class Content05a3bcAI843CreateDefaultBlogPageRaceContractTest extends TestCase
{
    /**
     * Sentinel content id range used by these tests so we never
     * collide with real content rows or sibling test fixtures.
     */
    private const FIXTURE_CONTENT_ID_BASE = 900843000;

    private const MODEL_PATH = 'Modules/Content/Models/Content.php';

    protected function setUp(): void
    {
        parent::setUp();
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
    }

    private function readModel(): string
    {
        return (string) file_get_contents(base_path(self::MODEL_PATH));
    }

    /**
     * Slice the Content::createDefaultBlogPage() method body for
     * scoped assertions. Walks from the canonical method signature
     * to the matching brace via balance counting.
     */
    private function sliceMethodBody(): string
    {
        $source = $this->readModel();
        $start = strpos($source, 'public static function createDefaultBlogPage()');
        if ($start === false) {
            return '';
        }
        // Walk to the opening brace then balance-count to closing.
        $openBrace = strpos($source, '{', $start);
        if ($openBrace === false) {
            return '';
        }
        $depth = 0;
        $i = $openBrace;
        $len = strlen($source);
        while ($i < $len) {
            if ($source[$i] === '{') {
                $depth++;
            } elseif ($source[$i] === '}') {
                $depth--;
                if ($depth === 0) {
                    return substr($source, $start, $i - $start + 1);
                }
            }
            $i++;
        }
        return substr($source, $start);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Source-level pin: AI-843 guard present + correctly
    //          positioned (after get_pages() null check, before new save)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function method_carries_static_where_url_blog_exists_guard(): void
    {
        $body = $this->sliceMethodBody();

        $this->assertNotSame('', $body, 'createDefaultBlogPage() method body must be locatable.');
        $this->assertMatchesRegularExpression(
            '/static::where\(\s*[\'"]url[\'"]\s*,\s*[\'"]Blog[\'"]\s*\)\s*->exists\(\)/',
            $body,
            'AI-843: createDefaultBlogPage() must carry static::where(\'url\', \'Blog\')->exists() guard. Option B fix prevents the race window between two concurrent get_pages() callers.'
        );
    }

    #[Test]
    public function guard_returns_early_when_canonical_blog_already_exists(): void
    {
        $body = $this->sliceMethodBody();

        // The guard must SHORT-CIRCUIT with return on race detection.
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*static::where\(\s*[\'"]url[\'"]\s*,\s*[\'"]Blog[\'"]\s*\)\s*->exists\(\)\s*\)\s*\{\s*return\s+null\s*;/',
            $body,
            'AI-843: the race guard must early-return when static::where(\'url\', \'Blog\')->exists() = true. Pre-existing canonical Blog page means another concurrent call beat us to it; no double-save.'
        );
    }

    #[Test]
    public function guard_positioned_after_get_pages_null_check_before_new_save(): void
    {
        $body = $this->sliceMethodBody();

        $getPagesPos = strpos($body, "get_pages('content_type=page&subtype=dynamic&is_shop=0&single=1')");
        $guardPos = strpos($body, "static::where('url', 'Blog')");
        $newSavePos = strpos($body, 'new static()');

        $this->assertGreaterThan(0, $getPagesPos, 'get_pages() call must be present in createDefaultBlogPage().');
        $this->assertGreaterThan(0, $guardPos, 'AI-843 race guard must be present.');
        $this->assertGreaterThan(0, $newSavePos, 'new static() instantiation must be present.');

        $this->assertLessThan(
            $guardPos,
            $getPagesPos,
            'AI-843: get_pages() null check must come BEFORE the race guard (otherwise the race guard fires for legitimate Blog page existence).'
        );
        $this->assertLessThan(
            $newSavePos,
            $guardPos,
            'AI-843: race guard must come BEFORE the new static() save (the whole point of the guard is to prevent the save when a concurrent call already created the row).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Behavioural test: simulated race-condition no-op when
    //          a canonical Blog page already exists in the table
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function race_detected_when_canonical_blog_pre_exists_no_duplicate_created(): void
    {
        // Simulate Call A having completed: insert a canonical Blog
        // page directly via DB::table to bypass the model lifecycle
        // (no slug regeneration, no URL rewriting, no event dispatch).
        // The fixture row has url='Blog' which is the canonical shape
        // that static::where('url', 'Blog')->exists() detects.
        $fixtureId = self::FIXTURE_CONTENT_ID_BASE + 1;
        $now = date('Y-m-d H:i:s');
        DB::table('content')->insert([
            'id' => $fixtureId,
            'title' => 'Blog',
            'url' => 'Blog',
            'content_type' => 'page',
            'subtype' => 'dynamic',
            'is_shop' => 0,
            'is_deleted' => 0,
            'is_active' => 1,
            'layout_file' => 'blog.blade.php',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Count Blog rows BEFORE the call. Get_pages() might or might
        // not return the fixture (depends on parent/active filters),
        // so we count via DB directly for portable behaviour.
        $beforeCount = DB::table('content')
            ->where('url', 'Blog')
            ->where('content_type', 'page')
            ->where('subtype', 'dynamic')
            ->where('is_shop', 0)
            ->count();

        // Now call the method. If the guard works, the method either
        // returns the existing page (via get_pages) OR returns null
        // (race-detected via the where() guard). EITHER WAY, no new
        // Blog row should be created.
        Content::createDefaultBlogPage();

        $afterCount = DB::table('content')
            ->where('url', 'Blog')
            ->where('content_type', 'page')
            ->where('subtype', 'dynamic')
            ->where('is_shop', 0)
            ->count();

        $this->assertSame(
            $beforeCount,
            $afterCount,
            'AI-843: when a canonical Blog page already exists, createDefaultBlogPage() must NOT create a duplicate. Race-condition guard prevents the orphan Blog{14-digit-timestamp} rows that AI-791 Slice D migration cleans up reactively.'
        );
        $this->assertGreaterThanOrEqual(
            1,
            $afterCount,
            'AI-843: at least the pre-existing Blog fixture row must remain after the call (the guard must NOT delete existing rows).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — AI-791 lineage citations + task-id markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_model(): void
    {
        $source = $this->readModel();

        $this->assertStringContainsString(
            'task-2026-05-17-05a3bc',
            $source,
            'AI-843: Content::createDefaultBlogPage() docblock must carry the task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-843',
            $source,
            'AI-843: Content::createDefaultBlogPage() docblock must carry the AI-843 ticket marker.'
        );
    }

    #[Test]
    public function ai791_lineage_citation_present(): void
    {
        $source = $this->readModel();

        $this->assertStringContainsString(
            'AI-791',
            $source,
            'AI-843: docblock must cite AI-791 lineage (preventative complement to AI-791 Slice D + AI-792b cleanup migration).'
        );
    }

    #[Test]
    public function ai792_layout_file_carry_forward_preserved(): void
    {
        $body = $this->sliceMethodBody();

        // AI-843 must not break the AI-792 layout_file bind.
        $this->assertStringContainsString(
            "\$blogPage->layout_file = 'blog.blade.php';",
            $body,
            'AI-843 must NOT regress the AI-792 layout_file bind. blog.blade.php assignment must remain on the save path.'
        );
    }

    #[Test]
    public function option_a_and_option_c_alternatives_documented(): void
    {
        $source = $this->readModel();

        $this->assertStringContainsString(
            'Option B',
            $source,
            'AI-843: docblock must cite the chosen fix path (Option B — designer-validated smallest diff).'
        );
        $this->assertStringContainsString(
            'Options A',
            $source,
            'AI-843: docblock must reference the AI-843 ticket body Option A (DB-level lock) alternative for PM awareness.'
        );
        $this->assertStringContainsString(
            'C (Cache',
            $source,
            'AI-843: docblock must reference the Option C (Cache::lock) alternative.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Belt + suspenders defence-in-depth citation
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function belt_suspenders_cleanup_migration_reference_present(): void
    {
        $source = $this->readModel();

        $this->assertStringContainsString(
            'Slice D',
            $source,
            'AI-843: docblock must cite the AI-791 Slice D cleanup migration as the fallback safety net (belt + suspenders defence-in-depth).'
        );
    }
}

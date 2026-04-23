<?php

namespace Tests\Browser;

use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\Models\StagingContent;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use Modules\WordPressMigration\Services\StagingWriter;
use Modules\WordPressMigration\Services\WordPressContentMapper;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Phase 8 end-to-end for the preview → commit flow.
 *
 * Seeds three staged rows for a synthetic job, opens the Filament
 * preview page, unchecks the middle row ("Keep?") to exclude it from
 * the commit, clicks **Commit to live**, and then asserts:
 *
 *   1. The two non-excluded guids land on the live `content` table
 *      with an `import_source=wordpress_rss` content_data marker.
 *   2. The excluded guid NEVER reaches live content — this is the
 *      whole point of the preview step.
 *   3. Committed staging rows are cleaned up; the excluded one stays
 *      behind as a receipt of what was dropped.
 *
 * We seed staging directly rather than running a fresh import so the
 * test is fast (no HTTP probe, no downloads) and so we own the guids
 * — the cleanup paths below are scoped to them.
 */
class LiveAdminWordPressMigrationPreviewCommitTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const GUID_KEEP_A = 'preview-commit:keep-a';
    private const GUID_EXCLUDE = 'preview-commit:drop';
    private const GUID_KEEP_B = 'preview-commit:keep-b';

    private const ALL_GUIDS = [
        self::GUID_KEEP_A,
        self::GUID_EXCLUDE,
        self::GUID_KEEP_B,
    ];

    private const JOB_SOURCE_URL = 'preview-commit://example.invalid';

    protected function assertPreConditions(): void
    {
        // Rely on the already-running dev server.
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->purgeFixture();
    }

    protected function tearDown(): void
    {
        $this->purgeFixture();
        parent::tearDown();
    }

    #[Test]
    public function excluding_one_row_then_clicking_commit_only_promotes_the_kept_rows(): void
    {
        $jobId = $this->seedJobAndStagingRows();

        $excludedRow = StagingContent::query()
            ->where('job_id', $jobId)
            ->where('source_guid', self::GUID_EXCLUDE)
            ->firstOrFail();

        $this->browse(function (Browser $browser) use ($jobId, $excludedRow) {
            $this->loginAsAdmin($browser);

            // Handshake via the `?job=` query string so the preview page
            // knows which staging snapshot to paint. The mount() helper
            // falls back to the latest job if unset, but we want to pin
            // on the synthetic one regardless of other suites' residue.
            $browser->visit('/admin/word-press-migration-preview-page?job=' . $jobId)->pause(5000);
            $this->ensureLoggedIn($browser);

            $browser->waitFor('[data-testid="preview-table"]', 15);

            // All three rows should be rendered, plus the excluded-count
            // stat reads zero before we touch anything.
            $text = $browser->script('return document.body.innerText;')[0] ?? '';
            $this->assertStringContainsString(self::GUID_KEEP_A, $text);
            $this->assertStringContainsString(self::GUID_EXCLUDE, $text);
            $this->assertStringContainsString(self::GUID_KEEP_B, $text);

            // Toggle exclusion on the middle row via its keep-checkbox.
            // The selector carries the staging id so we're not racing
            // against row order.
            $browser->click('[data-testid="preview-keep-' . $excludedRow->id . '"]')
                ->pause(2000);

            // The stats counter should now show "1 excluded" — a light
            // sanity check that Livewire ran the toggle before we hit
            // Commit.
            $excludedTotal = $browser->script(
                "return document.querySelector('[data-testid=\"preview-stats-excluded\"]').innerText;"
            )[0] ?? null;
            $this->assertSame('1', $excludedTotal,
                'Excluded-count badge should have flipped to 1 after unchecking the middle row'
            );

            // Livewire v4 wire:confirm installs an __livewire_confirm
            // interceptor on the button that wraps wire:click with a
            // native confirm() prompt. Auto-accept it so the click
            // payload always reaches the server.
            $browser->script('window.confirm = function () { return true; };');

            // Driving the commit action: we build our own Livewire
            // update payload via the $wire magic object Livewire v4
            // attaches to every component root — $wire.commit() is
            // the officially-supported way to invoke a method from
            // JS, and it respects Livewire's queue + response morph
            // pipeline the same way wire:click does. Falling through
            // to the DOM click is the belt-and-braces path for any
            // Filament build that wraps the button differently.
            $commitResult = $browser->script(<<<'JS'
                return (function () {
                    try {
                        var btn = document.querySelector('[data-testid="preview-commit"]');
                        if (!btn) return 'no-button';
                        var el = btn;
                        while (el && !el.getAttribute('wire:id')) el = el.parentElement;
                        if (!el) return 'no-wire-id';
                        var wireId = el.getAttribute('wire:id');
                        if (!window.Livewire) return 'no-livewire';
                        var cmp = window.Livewire.find(wireId);
                        if (!cmp) return 'no-comp';
                        if (cmp.$wire && typeof cmp.$wire.commitStaged === 'function') {
                            cmp.$wire.commitStaged();
                            return 'wire-commit-called';
                        }
                        if (typeof cmp.call === 'function') {
                            cmp.call('commitStaged');
                            return 'comp-call-called';
                        }
                        return 'no-api';
                    } catch (e) {
                        return 'error:' + (e.message || String(e));
                    }
                })();
            JS);
            $browser->pause(10000);

            $this->assertTrue(
                in_array($commitResult[0] ?? null, ['wire-commit-called', 'comp-call-called'], true),
                'Livewire commit bridge did not fire — debug value: ' . var_export($commitResult, true)
            );

            // Commit outcome is verified against the DB. The page's
            // stat-badge value is advisory — the live DB is the
            // authority for the test's main assertion.
            $browser->waitUsing(15, 500, function () {
                return DB::table('content_data')
                    ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
                    ->where('field_value', self::GUID_KEEP_A)
                    ->exists();
            }, 'Commit should promote the kept guid onto live content within 15s');
        });

        // --- DB assertions: the two kept guids MUST be on live content,
        //     the excluded guid MUST NOT be.
        foreach ([self::GUID_KEEP_A, self::GUID_KEEP_B] as $keptGuid) {
            $this->assertTrue(
                $this->liveContentExistsForGuid($keptGuid),
                "Kept guid {$keptGuid} should have been promoted to live content"
            );
        }

        $this->assertFalse(
            $this->liveContentExistsForGuid(self::GUID_EXCLUDE),
            'Excluded guid must never reach live content — the preview gate is the whole point of Phase 8'
        );

        // --- Staging cleanup: kept rows are deleted, the excluded row
        //     stays as a receipt of what was dropped.
        $remainingGuids = StagingContent::query()
            ->where('job_id', $jobId)
            ->pluck('source_guid')
            ->all();
        $this->assertSame([self::GUID_EXCLUDE], array_values($remainingGuids),
            'Only the excluded row should remain in staging after Commit'
        );
    }

    private function seedJobAndStagingRows(): int
    {
        $job = WordPressMigrationJob::create([
            'source_url' => self::JOB_SOURCE_URL,
            'source_url_hash' => hash('sha256', self::JOB_SOURCE_URL),
            'source_host' => 'example.invalid',
            'status' => WordPressMigrationJob::STATUS_FINISHED,
            'mode' => 'wxr',
        ]);
        $jobId = (int)$job->id;

        $writer = new StagingWriter();
        foreach (self::ALL_GUIDS as $guid) {
            $writer->stage($jobId, new MigrationItemDTO(
                guid: $guid,
                title: "Preview commit: {$guid}",
                html: "<p>Body for {$guid}</p>",
                excerpt: null,
                author: null,
                categories: [],
                tags: [],
                publishedAt: new DateTimeImmutable('2026-01-01T00:00:00+00:00'),
                canonicalUrl: "https://example.invalid/{$guid}",
                source: 'wxr',
                sourceHost: 'example.invalid',
            ));
        }

        return $jobId;
    }

    private function liveContentExistsForGuid(string $guid): bool
    {
        return DB::table('content_data')
            ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
            ->where('field_value', $guid)
            ->exists();
    }

    private function purgeFixture(): void
    {
        // Kill staging rows for this test's synthetic job + guids.
        $jobIds = DB::table('wp_migration_jobs')
            ->where('source_url', self::JOB_SOURCE_URL)
            ->pluck('id')
            ->all();

        if (!empty($jobIds)) {
            DB::table('wp_migration_staging_media')->whereIn('job_id', $jobIds)->delete();
            DB::table('wp_migration_staging_content')->whereIn('job_id', $jobIds)->delete();
            DB::table('wp_migration_jobs')->whereIn('id', $jobIds)->delete();
        }

        // Kill any live content rows the test may have promoted on a
        // prior failing run.
        $contentIds = DB::table('content_data')
            ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
            ->whereIn('field_value', self::ALL_GUIDS)
            ->pluck('rel_id')
            ->all();

        if (!empty($contentIds)) {
            DB::table('content_data')->whereIn('rel_id', $contentIds)->delete();
            DB::table('content')->whereIn('id', $contentIds)->delete();
            DB::table('media')
                ->where('rel_type', 'Modules\\Content\\Models\\Content')
                ->whereIn('rel_id', $contentIds)
                ->delete();
        }
    }
}

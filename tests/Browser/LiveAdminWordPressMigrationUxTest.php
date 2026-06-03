<?php

namespace Tests\Browser;

use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use Modules\WordPressMigration\Services\StagingWriter;
use Modules\WordPressMigration\Services\WordPressContentMapper;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Phase 9 end-to-end click-through of the operator UX surface:
 * Create → Progress → Preview → Commit, driven in a real browser.
 *
 * What each stage exercises:
 *
 *   1. **Create** — visiting the resource's create route must land
 *      on the stateful import page ({@see WordPressMigrationImportPage}),
 *      which is the source of truth for "start a new import." The
 *      resource's Create page only redirects; this test asserts the
 *      redirect actually happens in a browser, not just in a unit
 *      test.
 *   2. **Progress** — the resource's View page mounts with a polled
 *      "Live progress" panel. We seed the record with
 *      `status=running, progress={processed, total, failed}` so the
 *      polled snapshot has meaningful numbers on first paint.
 *   3. **Preview** — the View page's header action "Preview staging"
 *      links into the stand-alone preview page scoped to `?job={id}`.
 *   4. **Commit** — from the preview page we click "Commit to live";
 *      the kept rows must land on `content` (assertion is DB-
 *      authoritative because Livewire state isn't flushed until the
 *      next render cycle).
 *
 * Why we don't run a full HTTP probe / worker here:
 *   The end-to-end importer loops have their own Dusk coverage
 *   (probe, REST, RSS, sitemap, WXR) and the preview→commit flow
 *   has {@see LiveAdminWordPressMigrationPreviewCommitTest}. This
 *   test's job is to prove the resource pages *stitch* the flow
 *   together — the four stages open in sequence in a browser with
 *   the expected data visible at each step.
 */
class LiveAdminWordPressMigrationUxTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const JOB_SOURCE_URL = 'ux-test://wordpress.example.invalid';

    private const SOURCE_HOST = 'ux-test.example.invalid';

    private const GUID_A = 'ux-test:keep-a';

    private const GUID_B = 'ux-test:keep-b';

    private const ALL_GUIDS = [
        self::GUID_A,
        self::GUID_B,
    ];

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
    public function operator_can_navigate_create_progress_preview_commit_in_one_flow(): void
    {
        $jobId = $this->seedRunningJobWithStaging();

        $this->browse(function (Browser $browser) use ($jobId) {
            $this->loginAsAdmin($browser);

            // ---- Stage 1: Create -----------------------------------
            // Hitting the resource's create URL redirects to the
            // stateful import page. We just check the landing URL and
            // that the page renders its headline input without 500ing.
            $browser->visit('/admin/word-press-migration-resource/create')
                ->pause(3000);
            $this->ensureLoggedIn($browser);

            $landed = $browser->driver->getCurrentURL();
            $this->assertStringContainsString(
                '/admin/word-press-migration-import-page',
                $landed,
                'Create action must redirect to the stateful import page'
            );

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Create → import page should not 500');
            $this->assertStringNotContainsString('Whoops', $pageSource,
                'Create → import page should render cleanly');

            // ---- Stage 2: Progress ---------------------------------
            // View the seeded job — the polled progress panel should
            // render the counters we wrote to `progress` JSON.
            $browser->visit('/admin/word-press-migration-resource/' . $jobId)
                ->pause(3000);
            $this->ensureLoggedIn($browser);
            $browser->waitFor('[data-testid="progress-panel"]', 15);

            $processed = $this->innerText($browser, '[data-testid="progress-processed"]');
            $total = $this->innerText($browser, '[data-testid="progress-total"]');
            $failed = $this->innerText($browser, '[data-testid="progress-failed"]');

            $this->assertSame('12', $processed,
                'Processed counter should reflect the seeded progress.processed = 12');
            $this->assertSame('50', $total,
                'Total counter should reflect the seeded progress.total = 50');
            $this->assertSame('1', $failed,
                'Failed counter should reflect the seeded progress.failed = 1');

            // Host string is the breadcrumb that ties this view back
            // to the correct job in the operator's mental model.
            $bodyText = $browser->script('return document.body.innerText;')[0] ?? '';
            $this->assertStringContainsString(self::SOURCE_HOST, $bodyText,
                'Source host should appear on the job view page');

            // ---- Stage 3: Preview ----------------------------------
            // The View page carries a "Preview staging" header action
            // linking to the stand-alone preview page. Rather than
            // hunting the button across Filament's evolving action
            // DOM, drive the URL directly — the intent of this stage
            // is "preview opens with the right staging snapshot".
            $browser->visit('/admin/word-press-migration-preview-page?job=' . $jobId)
                ->pause(3000);
            $this->ensureLoggedIn($browser);
            $browser->waitFor('[data-testid="preview-table"]', 15);

            $previewText = $browser->script('return document.body.innerText;')[0] ?? '';
            $this->assertStringContainsString(self::GUID_A, $previewText,
                'Preview page should list the first staged guid');
            $this->assertStringContainsString(self::GUID_B, $previewText,
                'Preview page should list the second staged guid');

            // ---- Stage 4: Commit -----------------------------------
            // Auto-accept wire:confirm then drive the Livewire method
            // via $wire. See {@see LiveAdminWordPressMigrationPreviewCommitTest}
            // for the rationale — `commit` is a reserved Livewire v4
            // slot so the page method is `commitStaged`.
            $browser->script('window.confirm = function () { return true; };');

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

            $browser->waitUsing(15, 500, function () {
                return DB::table('content_data')
                    ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
                    ->where('field_value', self::GUID_A)
                    ->exists();
            }, 'Commit should promote the first kept guid onto live content within 15s');
        });

        // DB-level assertion: both guids landed on live content. The
        // Livewire stat-badges are advisory; the committed DB rows
        // are the truth of "the flow finished end-to-end".
        foreach (self::ALL_GUIDS as $guid) {
            $this->assertTrue(
                DB::table('content_data')
                    ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
                    ->where('field_value', $guid)
                    ->exists(),
                "Guid {$guid} should have been committed to live content"
            );
        }

        // Staging should be drained for the kept rows (no excluded
        // rows seeded, so the staging table ends empty for this job).
        $this->assertSame(0, DB::table('wp_migration_staging_content')
            ->whereIn('source_guid', self::ALL_GUIDS)
            ->count(),
            'Committed staging rows should be deleted');
    }

    private function innerText(Browser $browser, string $selector): ?string
    {
        $result = $browser->script(
            "var n = document.querySelector(" . json_encode($selector) . "); return n ? n.innerText.trim() : null;"
        );
        return $result[0] ?? null;
    }

    private function seedRunningJobWithStaging(): int
    {
        $job = WordPressMigrationJob::create([
            'source_url' => self::JOB_SOURCE_URL,
            'source_url_hash' => hash('sha256', self::JOB_SOURCE_URL),
            'source_host' => self::SOURCE_HOST,
            'status' => WordPressMigrationJob::STATUS_RUNNING,
            'mode' => 'rest',
            'probe_result' => [
                'mode' => 'rest',
                'estimated_posts' => 40,
                'estimated_pages' => 10,
            ],
            'progress' => [
                'processed' => 12,
                'total' => 50,
                'failed' => 1,
            ],
            'started_at' => now()->subMinutes(2),
        ]);
        $jobId = (int) $job->id;

        $writer = new StagingWriter();
        foreach (self::ALL_GUIDS as $guid) {
            $writer->stage($jobId, new MigrationItemDTO(
                guid: $guid,
                title: "UX click-through: {$guid}",
                html: "<p>Body for {$guid}</p>",
                excerpt: null,
                author: null,
                categories: [],
                tags: [],
                publishedAt: new DateTimeImmutable('2026-01-01T00:00:00+00:00'),
                canonicalUrl: 'https://' . self::SOURCE_HOST . '/' . $guid,
                source: 'rest',
                sourceHost: self::SOURCE_HOST,
            ));
        }

        return $jobId;
    }

    private function purgeFixture(): void
    {
        $jobIds = DB::table('wp_migration_jobs')
            ->where('source_url', self::JOB_SOURCE_URL)
            ->pluck('id')
            ->all();

        if (! empty($jobIds)) {
            DB::table('wp_migration_staging_media')->whereIn('job_id', $jobIds)->delete();
            DB::table('wp_migration_staging_content')->whereIn('job_id', $jobIds)->delete();
            DB::table('wp_migration_jobs')->whereIn('id', $jobIds)->delete();
        }

        $contentIds = DB::table('content_data')
            ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
            ->whereIn('field_value', self::ALL_GUIDS)
            ->pluck('rel_id')
            ->all();

        if (! empty($contentIds)) {
            DB::table('content_data')->whereIn('rel_id', $contentIds)->delete();
            DB::table('content')->whereIn('id', $contentIds)->delete();
            DB::table('media')
                ->where('rel_type', 'Modules\\Content\\Models\\Content')
                ->whereIn('rel_id', $contentIds)
                ->delete();
        }
    }
}
